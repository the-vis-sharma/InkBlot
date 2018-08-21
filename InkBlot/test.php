<?php
    session_start();
    if ($_SESSION['logged_in'] == false) {
        $_SESSION['err_msg'] = "You need to login first to access this page.";
        header("Location: index.php");
    }
    else if (time() - $_SESSION['timestamp'] > 900) { 
        unset($_SESSION['username'], $_SESSION['timestamp']);
        $_SESSION['logged_in'] = false;
        $_SESSION['err_msg'] = "Your session is expired. Login again!";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['timestamp'] = time();
    }

    if (isset($_SESSION['exam_start_time'])) {
        if($_SESSION['exam_start_time'] - time() > 600) {
            unset($_SESSION['examId'], $_SESSION['exam_start_time']);
        }
    }

    if (isset($_SESSION['examId'])) {
        $_SESSION['exam_start_time'] = time();

        $con = mysqli_connect("localhost", "root", "root", "InkBlotDB");

        if (!con) {
            die("Sorry, Can't connect to database.");
        }

        $query = "SELECT topic, max_marks, neg_marks FROM Exams WHERE examId=" . $_SESSION["examId"];

        $result = mysqli_query($con, $query);

        if ($result) {
            $row = mysqli_fetch_array($result);
            $topic = $row[0];
            $max_marks = $row[1];
            $neg_marks = $row[2];
        }
    }
    else {
        unset($_SESSION['username'], $_SESSION['timestamp']);
        $_SESSION['logged_in'] = false;
        $_SESSION['err_msg'] = "You are not authoried to access this page directly.";
        header("Location: index.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

</head>

<body ONLOAD="examTimer();">

    <div id="wrapper">

        <form action="cgi-bin/check-result.py" method="get" id="form">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a href="dashboard.php">
                    <img src="dist/img/logo.png" style="width: 150px; height: 50px; margin: 4px;">
                </a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right" style="margin: 10px" >
                <li style="margin-right: 30px; font-size: large; color: red;" id="wordCount">
                    Words: 0
                </li>
                <!-- ----------------------------- -->
                <li style="margin-right: 30px; font-size: large;" id="timer">
                    10 m : 00 s
                </li>
                <!-- ----------------------------- -->
                <li class="dropdown" style="margin-right: 30px">
                    <input type="submit" class="btn btn-primary" name="" value="Finish Exam" onclick="return finish('Are you really want to finish the exam?', 1);" id="btnsubmit">
                </li>

                <!-- ----------------------------- -->
            </ul>
            <!-- /.navbar-top-links -->

            <div style="display: none" class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.html"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Charts<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="flot.html">Flow Charts</a>
                                </li>
                                <li>
                                    <a href="morris.html">Morris.js Charts</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="tables.html"><i class="fa fa-table fa-fw"></i> Tables</a>
                        </li>
                        <li>
                            <a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="panels-wells.html">Panels and Wells</a>
                                </li>
                                <li>
                                    <a href="buttons.html">Buttons</a>
                                </li>
                                <li>
                                    <a href="notifications.html">Notifications</a>
                                </li>
                                <li>
                                    <a href="typography.html">Typography</a>
                                </li>
                                <li>
                                    <a href="icons.html"> Icons</a>
                                </li>
                                <li>
                                    <a href="grid.html">Grid</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li class="active">
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a class="active" href="blank.html">Blank Page</a>
                                </li>
                                <li>
                                    <a href="login.html">Login Page</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper" style="margin: 0 0 0 0">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header"><?php echo $topic; ?></h2>

                        <section id="fh5co-hero" class="no-js-fullheight" style="background-image: url(images/full_image_3.jpg);" data-next="yes">
                            <div class="fh5co-overlay"></div>
                            <div class="container">
                                <div class="fh5co-intro no-js-fullheight">
                                    <div class="fh5co-intro-text">

                                        <div class="fh5co-center-position">
                                            <div id="main-content" >
                                                <div>
                                                        <textarea id="para"  maxlength="5000" spellcheck="false" class="form-control" style="display: block; resize: none; font-size: 1.4em; background-color: #eee; border: 10px red; color: black; width: 100%; height: 350px; align: center;" name="para" onkeypress="return onKeyPress(event)" onkeyup="onKeyUp();" placeholder="Write your content here..."></textarea>
                                                        <input type="hidden" name="backSpace" id="backSpace" value="1">
                                                        <input type="hidden" name="wordsRepeat" id="wordsRepeat" value="2">
                                                        <input type="hidden" name="abbrUsed" id="abbrUsed" value="3">
                                                        <input type="hidden" name="andCount" id="andCount" value="4">
                                                        <input type="hidden" name="totalWords" id="totalWords" value="5">
                                                        <input type="hidden" name="isDisqualified" id="isDisqualified" value="0">
                                                        <input type="hidden" name="max_marks" id="max_marks" value="<?php echo $max_marks ?>">
                                                        <input type="hidden" name="neg_marks" id="neg_marks" value="<?php echo $neg_marks ?>">
                                                        <input type="hidden" name="examId" id="examId" value="<?php echo $_SESSION['examId']; ?>">
                                                        <input type="hidden" name="userId" id="userId" value="<?php echo $_SESSION['userId']; ?>">
                                                
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

        </form>

    </div>
    <!-- /#wrapper -->

    <script src="js/script.js"></script>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>


</body>

</html>
