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

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include 'php/nav.php' ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Exams</h1>
                    </div>
                    <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Basic Tabs
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#home" data-toggle="tab">Today's Exams</a>
                                        </li>
                                        <li><a href="#profile" data-toggle="tab">Previous Exams</a>
                                        </li>
                                    </ul>
        
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="home">
                                                <div class="table-responsive table-bordered">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Name of Exam</th>
                                                                    <th>Start Date</th>
                                                                    <th>Exp. Date</th>
                                                                    <th>Max. Marks</th>
                                                                    <th>Negative Marks</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $con = mysqli_connect("localhost", "root", "root", "InkBlotDB");

                                                                    if (!con) {
                                                                        die("Sorry, Can't connect to database.");
                                                                    }

                                                                    $query1 = "SELECT * FROM Exams where examId = (SELECT Max(examId) FROM exams)";

                                                                    $query2 = "SELECT examId FROM result where examId = (SELECT Max(examId) FROM exams) and userId = " . $_SESSION['userId'];

                                                                    $result = mysqli_query($con, $query1);

                                                                    $i=1;
                                                                    while($row = mysqli_fetch_assoc($result)) {
                                                                        $result1 = mysqli_query($con, $query2);
                                                                        echo "<tr>";
                                                                        echo "<td>" . $i++ . "</td>";
                                                                        echo "<td>" . $row['topic'] . "</td>";
                                                                        echo "<td>" . date_format(date_create($row['start_date']), "Y-m-d") . "</td>";
                                                                        echo "<td>" . date_format(date_create($row['exp_date']), "Y-m-d") . "</td>";
                                                                        echo "<td>" . $row['max_marks'] . "</td>";
                                                                        echo "<td>" . $row['neg_marks'] . "</td>";
                                                                        echo "<td>";
                                                                        echo "<form action='instruction.php' method='post'>";
                                                                        echo "<input type='hidden' value=" . $row['examId'] . " name='examId'>";
                                                                        if (mysqli_num_rows($result1) == 0) {
                                                                            echo "<input type='submit' class='btn btn-primary' value='Take Test'>";
                                                                        }
                                                                        else {
                                                                            echo "<input type='submit' class='btn btn-primary' disabled value='Take Test'>";
                                                                        }
                                                                        echo "</form>";
                                                                        echo "</td>";
                                                                        echo "</tr>";
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- /.table-responsive -->
                                        </div>
                                        <div class="tab-pane fade" id="profile">
                                            <p><center>No Previous Exams!</center></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

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
