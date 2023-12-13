<?php

function removeEscaped($inputString) {
    $inputString = stripslashes($inputString);
    $outputString = sanitize_text_field($inputString);

    return $outputString;
}
?>

<div id="scheduled-sessions-table">
    <h2>Scheduled Sessions</h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Hour</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($scheduled_dates as $date) {
                $parsed_notes = removeEscaped($date['session_notes']); 
                echo "<tr>";
                echo "<td>{$date['session_name']}</td>";
                echo "<td>{$date['session_date']}</td>";
                echo "<td>{$date['session_hour']}</td>";
                echo "<td>{$parsed_notes}</td>";
                echo "<td><img src='https://cdn.pixabay.com/photo/2017/02/23/08/46/garbage-2091534_1280.png' alt='Delete' height='20' class='delete-session' data-session-id='{$date['id']}'></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <br>
    <label for="rows-selector">Display:</label>
    <select id="rows-selector" name="rows_to_display">
        <option value="5" <?php selected($rows_per_page, 5); ?>>5</option>
        <option value="10" <?php selected($rows_per_page, 10); ?>>10</option>
        <option value="20" <?php selected($rows_per_page, 20); ?>>20</option>
        <option value="100" <?php selected($rows_per_page, 100); ?>>100</option>
    </select>

    <div class="pagination">
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'sessions_scheduler';
        $total_pages = ceil($wpdb->get_var("SELECT COUNT(id) FROM $table_name") / $rows_per_page);

        echo '<span class="pagination-arrow"> &lt; </span>';

        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a class='page-selector' data-page='{$i}'>{$i}</a>";

            if ($i < $total_pages) {
                echo '<span class="pagination-dash"> - </span>';
            }
        }

        echo '<span class="pagination-arrow"> &gt; </span>';
        ?>
    </div>
</div>