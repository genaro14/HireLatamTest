    <?php
    /*
Template Name: Session Scheduler
*/
    wp_head();
    ?>

    <body>
        <div class="container">
            <div class="header">
                <h1> Sesion Scheduler</h1>
            </div>
            <div class="date-picker">
                <h2>Select a day</h2>

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
        </div>
        <script src='/wp-content/themes/hireLatamTestTheme/scheduler.js'></script>
    </body>