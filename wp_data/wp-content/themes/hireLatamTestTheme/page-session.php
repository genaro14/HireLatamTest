<?php
/*
Template Name: Session Scheduler
*/
wp_head();
$available_hours = apply_filters('get_available_session_hours', array());

?>

<body>
    <div class="container">
        <div class="header">
            <h1>Session Scheduler</h1>
        </div>
        <div class="blocks">
            <div class="blocks__date">
                <div class="wrapper">
                    <header>
                        <p class="current-date"></p>
                        <div class="icons">
                            <span id="prev" class="material-symbols-rounded" style="background-image: url('/wp-content/themes/hireLatamTestTheme/assets/images/scheduler/left-icon.svg');"></span>
                            <span id="next" class="material-symbols-rounded" style="background-image: url('/wp-content/themes/hireLatamTestTheme/assets/images/scheduler/right-icon.svg');"></span>
                        </div>
                    </header>
                    <div class="calendar">
                        <ul class="weeks">
                            <li>Sun</li>
                            <li>Mon</li>
                            <li>Tue</li>
                            <li>Wed</li>
                            <li>Thu</li>
                            <li>Fri</li>
                            <li>Sat</li>
                        </ul>
                        <ul class="days"></ul>
                    </div>
                </div>
            </div>
            <div class="blocks__form">
                <form id="session-form" class="session-form" method="post">
                    <label for="session-hour">Name: </label>
                    <input class="mtl-input" id="session-name" name="session-name">
                    <label for="session-date">Date: </label>
                    <input class="mtl-input" type="text" id="session-date" name="session-date" readonly>

                    <label for="session-hour">Select Hour: </label>
                    <select class="mtl-input" id="session-hour" name="session-hour">

                    </select>

                    <label for="session-notes">Notes:</label>
                    <textarea class="mtl-input" id="session-notes" name="session-notes" rows="6"></textarea>

                    <button id="submit-button" class="mtl-btn" type="submit" name="submit-session-form">Schedule Session</button>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script src='/wp-content/themes/hireLatamTestTheme/js/sanitizer.js'></script>
</body>