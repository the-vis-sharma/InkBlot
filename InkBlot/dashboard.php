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

    <!-- Morris Charts CSS -->
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">

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
                        <h1 class="page-header">Dashboard</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
            <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Performance
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="morris-line-chart" style="height: 250px;"></div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Top 10 Performers
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Score</th>
                                            <th>Rank</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $con = mysqli_connect("localhost", "root", "root", "InkBlotDB");

                                            if (!con) {
                                                die("Sorry, Can't connect to database.");
                                            }

                                            $query = "SELECT userId, obtained_marks, find_in_set(obtained_marks, (SELECT GROUP_CONCAT(obtained_marks ORDER BY obtained_marks DESC) from result)) as rank FROM result WHERE is_disqualified=0 and examId=(SELECT MAX(examId) FROM exams) ORDER BY rank LIMIT 10";

                                            $result = mysqli_query($con, $query);

                                            $i=1;
                                            while($row = mysqli_fetch_array($result)) {
                                                echo "<tr>";
                                                    echo "<td>" . $i++ . "</td>";
                                                    echo "<td>" . $row[0] . "</td>";
                                                    echo "<td>" . $row[1] . "</td>";
                                                    echo "<td>" . $row[2] . "</td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>

                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Your Analysis
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <?php 
                                            $query = "SELECT COUNT(userId) FROM result where userId=" . $_SESSION['userId'];

                                            $result = mysqli_query($con, $query);

                                            if ($result) {
                                                $row = mysqli_fetch_array($result);
                                                echo "<tr>";
                                                echo "<td>Total Tests Given</td>";
                                                echo "<td>" . $row[0] . "</td>";
                                                echo "</tr>";
                                            }

                                            $query = "SELECT COUNT(userId) FROM result where userId=" . $_SESSION['userId'] . " and result='Failed'";

                                            $result = mysqli_query($con, $query);

                                            if ($result) {
                                                $row = mysqli_fetch_array($result);
                                                echo "<tr>";
                                                echo "<td>Total Failed in Exams</td>";
                                                echo "<td>" . $row[0] . "</td>";
                                                echo "</tr>";
                                            }

                                            $query = "SELECT MAX(obtained_marks) FROM result where userId=" . $_SESSION['userId'];

                                            $result = mysqli_query($con, $query);

                                            if ($result) {
                                                $row = mysqli_fetch_array($result);
                                                echo "<tr>";
                                                echo "<td>Highest Score</td>";
                                                echo "<td>" . $row[0] . "</td>";
                                                echo "</tr>";
                                            }

                                            $query = "SELECT MIN(obtained_marks) FROM result where userId=" . $_SESSION['userId'];

                                            $result = mysqli_query($con, $query);

                                            if ($result) {
                                                $row = mysqli_fetch_array($result);
                                                echo "<tr>";
                                                echo "<td>Lowest Score Score</td>";
                                                echo "<td>" . $row[0] . "</td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
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

        <!-- Morris Charts JavaScript -->
    <script src="vendor/raphael/raphael.min.js"></script>
    <script src="vendor/morrisjs/morris.min.js"></script>
    <script src="data/morris-data.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <script type="text/javascript">
    	new Morris.Line({
  // ID of the element in which to draw the chart.
  element: 'morris-line-chart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
  <?php 
    $query = "SELECT attempt_date, obtained_marks FROM result WHERE userId= " . $_SESSION['userId'] . " LIMIT 7";

    $result = mysqli_query($con, $query);

    $nor = mysqli_num_rows($result);
    $i = 1;
    while($row = mysqli_fetch_array($result)) {
            if ($i==$nor) {
                echo "{ exams: '" . date_format(date_create($row[0]),'d/m/Y') . "', marks: " . $row[1] . "}";
            }
            else {
                echo "{ exams: '" . date_format(date_create($row[0]),'d/m/Y') . "', marks: " . $row[1] . "} ,";
            }
            $i++;
    }
  ?>
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'exams',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['marks'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['Marks']
});
    </script>

</body>

</html>
