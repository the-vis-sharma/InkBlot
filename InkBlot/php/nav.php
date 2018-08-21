<?php
session_start();
echo "
<nav class='navbar navbar-default navbar-static-top' role='navigation' style='margin-bottom: 0'>
    <div class='navbar-header'>
        <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-collapse'>
            <span class='sr-only'>Toggle navigation</span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
        </button>
        <a href='dashboard.php'>
            <img src='dist/img/logo.png' style='width: 150px; height: 50px; margin: 4px;'>
        </a>
    </div>
    <!-- /.navbar-header -->

    <ul class='nav navbar-top-links navbar-right'>                
        <li class='dropdown'>
            <a class='dropdown-toggle' data-toggle='dropdown' href='#'>
                <i class='fa fa-bell fa-fw'></i> <i class='fa fa-caret-down'></i>
            </a>
            <ul class='dropdown-menu dropdown-alerts'>
                <li>
                    <a href='#'>
                        <div>
                            <span class='text-muted small text-center'>No New Notification!</span>
                        </div>
                    </a>
                </li>
                <li class='divider'></li>
                <li>
                    <a class='text-center' href='#'>
                        <strong>See All Alerts</strong>
                        <i class='fa fa-angle-right'></i>
                    </a>
                </li>
            </ul>
            <!-- /.dropdown-alerts -->
        </li>
        <li class='dropdown'>
            <a class='dropdown-toggle' data-toggle='dropdown' href='#'>
                <i class='fa fa-user fa-fw'></i> <i class='fa fa-caret-down'></i>
            </a>
            <ul class='dropdown-menu dropdown-user'>
                <!--<li><a href='#'><i class='fa fa-user fa-fw'></i> User Profile</a>
                </li>-->
                <li><a href='#'><i class='fa fa-user fa-fw'></i> " . $_SESSION['name'] . "</a>
                </li> 
                <li class='divider'></li> 
                <li><a href='php/logout.php'><i class='fa fa-sign-out fa-fw'></i> Logout</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class='navbar-default sidebar' role='navigation'>
        <div class='sidebar-nav navbar-collapse'>
            <ul class='nav' id='side-menu'>
                <li>
                    <a href='dashboard.php'><i class='fa fa-dashboard fa-fw'></i> Dashboard</a>
                </li>
                <li>
                    <a href='exam.php'><i class='fa fa-file-text fa-fw'></i> Exam</a>
                </li>
                <li>
                    <a href='result.php'><i class='fa fa-trophy fa-fw'></i> Result</a>
                </li>
                <li>
                    <a href='rank.php'><i class='fa fa-bar-chart-o fa-fw'></i> Ranks</a>
                </li>
                <li>
                    <a href='help.php'><i class='fa fa-question-circle fa-fw'></i> Help</a>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>
";
?>