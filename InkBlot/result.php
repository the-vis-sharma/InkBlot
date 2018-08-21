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

    $con = mysqli_connect("localhost", "root", "root", "InkBlotDB");

    if (!con) {
        die("Sorry, Can't connect to database.");
    }

    $query = "SELECT MAX(examId) FROM exams";

    $result = mysqli_query($con, $query);

    $examId = -1;
    if (result) {
        $row = mysqli_fetch_array($result)[0];
        $examId = $row[0];
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
                        <h1 class="page-header">Results</h1>
                    </div>
                    <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Outcome
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">        
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="home">
                                                <div class="table-responsive table-bordered">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Name of Exam</th>
                                                                    <th>Date</th>
                                                                    <th>Max. Marks</th>
                                                                    <th>Obtained Marks</th>
                                                                    <th>Result</th>
                                                                    <th>Rank</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                
                                                                    $query = "SELECT exams.topic, result.attempt_date, exams.max_marks, result.obtained_marks, result.result, find_in_set(result.obtained_marks, (SELECT GROUP_CONCAT(result.obtained_marks ORDER BY result.obtained_marks DESC) from result WHERE result.examId=(SELECT MAX(examId) FROM exams))) as rank, is_disqualified FROM result INNER JOIN exams ON result.examId=exams.examId WHERE result.userId=" . $_SESSION['userId'] . " ORDER BY result.attempt_date DESC";

                                                                    $result = mysqli_query($con, $query);
                                                                    $i = 1;
                                                                    while($row = mysqli_fetch_array($result)) {
                                                                        echo "<tr>";
                                                                            echo "<td>" . $i++ . "</td>";
                                                                            echo "<td>" . $row[0] . "</td>";
                                                                            echo "<td>" . $row[1] . "</td>";
                                                                            echo "<td>" . $row[2] . "</td>";
                                                                            echo "<td>" . $row[3] . "</td>";
                                                                            if ($row[6]==0) {
                                                                                if ($row[4] == 'Pass') {
                                                                                    echo "<td><span style='background-color: #4caf50; color: white; padding: 5px;'>" . $row[4] . "</span></td>";
                                                                                }
                                                                                else {
                                                                                    echo "<td><span style='background-color: #f00; color: white; padding: 5px;'>" . $row[4] . "</span></td>";   
                                                                                }
                                                                                echo "<td>" . $row[5] . "</td>";
                                                                            }
                                                                            else {
                                                                                echo "<td><span style='background-color: #f00; color: white; padding: 5px;'> Disqualified </span></td>";
                                                                                echo "<td> NRA </td>";
                                                                            }
                                                                        echo "</tr>";
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- /.table-responsive -->
                                        </div>
                                    </div>
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Detailed Result
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <?php
                                       // connect to mongodb
                                       $m = new MongoClient('mongodb://localhost/');
                                        
                                       // select a database
                                       $db = $m->InkBlotDB;
                                       $collection = $db->examId;

                                        $cursor = $collection->find(array('examId' => (int)$examId, 'username' => (int)$_SESSION['userId']));
                                        
                                        foreach ($cursor as $document) {
                                            echo $document["hint_eassy"] . "\n";
                                            echo "<ul>";
                                            foreach($document['mistake'] as $mistake) {
                                                echo "<li>" . $mistake . "</li>";
                                            }
                                            echo "</ul>";
                                            echo "<br>
                                            <div class='table-responsive'>
                                                <table class='table'>
                                                    <tbody>
                                                        <tr>
                                                            <td>Grammar Mistakes</td>
                                                            <td>" . $document['grammar_mistakes'] . "</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Vocabulary</td>
                                                            <td>" . $document['spelling_mistakes'] . "</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Word Repeated</td>
                                                            <td>" . $document['word_repeated'] . "</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Abbreviations Used</td>
                                                            <td>" . $document['abbr_used'] . "</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Shortcuts Used</td>
                                                            <td>" . $document['and_count'] . "</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Typing Mistakes</td>
                                                            <td>" . $document['backspace'] . "</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>";
                                        }
                                    ?>
                                    <!-- /.table-responsive -->
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
