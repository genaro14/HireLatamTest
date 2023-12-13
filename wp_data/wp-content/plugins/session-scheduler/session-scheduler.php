<?php
/*
Plugin Name: Session Scheduler
Description: Handles form endpoint and saves data - For now.
Version: 1.0
Author: GP
*/
define('SESSIONS_TABLE_NAME', $wpdb->prefix . 'sessions_scheduler');

class SessionScheduler
{
    function __construct()
    {
        add_action('init', [$this, 'create_sessions_table']);
        add_action('init', [$this, 'handleFormSubmission']);
        add_action('admin_menu', [$this, 'scheduled_sessions_admin_menu']);
        add_action('admin_menu', [$this, 'scheduled_sessions_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'scheduled_sessions_admin_enqueue_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scheduler_script']);
        add_action('wp_ajax_scheduled_sessions_ajax_action', [$this,  'scheduled_sessions_ajax_action']);
        add_action('wp_ajax_get_available_slots', [$this,  'get_available_slots']);
        add_action('wp_ajax_delete_scheduled_session', [$this,  'delete_scheduled_session']);
    }

    function create_sessions_table(): void
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . SESSIONS_TABLE_NAME . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        session_name varchar (50) NOT NULL,
        session_date date NOT NULL,
        session_hour varchar(10) NOT NULL,
        session_notes text DEFAULT NULL,
        session_date_created datetime NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    function handleFormSubmission(): void
    {
        if (isset($_POST['submit-session-form'])) {
            $sessionName = sanitize_text_field($_POST['session-name']);
            $sessionDate = sanitize_text_field($_POST['session-date']);
            $sessionHour = sanitize_text_field($_POST['session-hour']);
            $sessionNotes = isset($_POST['session-notes']) ? sanitize_text_field($_POST['session-notes']) : null;
            $sessionDateCreation = date('Y-m-d H:i:s');

            global $wpdb;
            $table_name = $wpdb->prefix . 'sessions_scheduler';

            $wpdb->insert(
                $table_name,
                array(
                    'session_name' => $sessionName,
                    'session_date' => $sessionDate,
                    'session_hour' => $sessionHour,
                    'session_notes' => $sessionNotes,
                    'session_date_created' => $sessionDateCreation,
                ),
                array('%s', '%s', '%s', '%s', '%s')
            );

            wp_redirect(home_url('/confirmation'));
            exit();
        }
    }

    function scheduled_sessions_admin_menu()
    {
        add_menu_page(
            'Scheduled Sessions',
            'Scheduled Sessions',
            'manage_options',
            'scheduled-sessions',
            [$this, 'scheduled_sessions_ajax_action']
        );
    }

    function enqueue_scheduler_script()
    {
        if (is_page('session')) {
            wp_enqueue_script('scheduler', plugins_url('/js/scheduler.js', __FILE__), array('jquery'));
        }
    }




    function scheduled_sessions_admin_enqueue_scripts($hook)
    {
        wp_enqueue_script('scheduled-sessions-admin', plugins_url('/js/scheduled-sessions-admin.js', __FILE__), array('jquery'));
        wp_localize_script('scheduled-sessions-admin', 'scheduled_sessions_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }

    function get_scheduled_dates(int $rows_per_page, int $current_page, string $sort_order)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sessions_scheduler';
        $offset = ($current_page - 1) * $rows_per_page;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name ORDER BY session_date $sort_order LIMIT %d OFFSET %d",
                $rows_per_page,
                $offset
            ),
            ARRAY_A
        );
    }

    function scheduled_sessions_ajax_action(): void
    {
        $rows_per_page = isset($_POST['rows_to_display']) ? intval($_POST['rows_to_display']) : 5;
        $current_page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $sort_order = isset($_POST['sort_order']) ? sanitize_text_field($_POST['sort_order']) : 'asc';

        $scheduled_dates = $this->get_scheduled_dates($rows_per_page, $current_page, $sort_order);

        ob_start();
        $partial = plugin_dir_path(__FILE__) . 'partials/scheduler-admin-partial.php';
        include $partial;
        $content = ob_get_clean();
        echo $content;

        wp_die();
    }

    function get_available_session_hours(string $selected_date): array
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sessions_scheduler';

        $scheduled_hours = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT session_hour FROM $table_name WHERE session_date = %s",
                $selected_date
            )
        );

        $all_hours = array(
            '09:00 AM',
            '10:00 AM',
            '11:00 AM',
            '12:00 PM',
            '01:00 PM',
            '02:00 PM',
            '03:00 PM',
            '04:00 PM',
            '05:00 PM'
        );

        $available_hours = array_values(array_diff($all_hours, $scheduled_hours));

        return $available_hours;
    }

    function get_available_slots(): void
    {
        if (isset($_POST['selected_date'])) {
            $selected_date = sanitize_text_field($_POST['selected_date']);

            $available_slots = $this->get_available_session_hours($selected_date);
            wp_send_json($available_slots);
        }
        wp_send_json_error('Invalid request.');
    }

    function delete_scheduled_session(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sessions_scheduler';

        if (isset($_POST['session_id'])) {
            $session_id = sanitize_text_field($_POST['session_id']);
            $wpdb->delete($table_name, array('id' => $session_id));
            wp_send_json_success('Session deleted successfully.');
        }
        wp_send_json_error('Invalid request.');
    }
}

$sessionScheduler = new SessionScheduler();
