<?php
/*
Template Name: Confirmation Page
*/

function confirmation_sessions_ajax_action()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'sessions_scheduler';

    $recent_session = $wpdb->get_row("SELECT * FROM $table_name ORDER BY session_date_created DESC LIMIT 1", ARRAY_A);
    return $recent_session
?>
    wp_die();

<?php

}

wp_head();
$recent_session = confirmation_sessions_ajax_action();
?>

<div class="confirmation-container">
    <div id="scheduled-sessions-table" class="container">
        <div class="header">
            <h1>Confirmed Session</h1>
        </div>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Mentee Name</th>
                    <th>When </th>
                    <th>Time</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recent_session) : ?>
                    <div>
                        <tr>
                            <td><?php echo $recent_session['session_name']; ?></td>
                            <td><?php echo $recent_session['session_date']; ?></td>
                            <td><?php echo $recent_session['session_hour']; ?></td>
                            <td><?php echo $recent_session['session_notes']; ?></td>
                        </tr>
                    </div>

                <?php else : ?>
                    <tr>
                        <td colspan="5">No scheduled sessions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
        <div>
            <?php if ($recent_session) : ?>
                <br>
                <div class="confirmation-info">
                    <p>This session has been scheduled. Thank you, Mentor</p>
                </div>
            <?php endif; ?>
        </div>
        <br>
        <div class="button-wrapper">
            <button class="mtl-btn" id="add-more-button"><a href="http://localhost/session">Add More</a></button>
        </div>
    </div>
</div>