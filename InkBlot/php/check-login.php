<?php
	session_start();

	$username = $_POST['email'];
	$password = $_POST['password'];

	$con = mysqli_connect("localhost", "root", "root", "InkBlotDB");

	if (!con) {
		die("Sorry, Can't connect to database.");
	}

	$query = "SELECT password, userId, is_first_time, name FROM Users WHERE username='$username'";

	$result = mysqli_query($con, $query);

	if ($result) {
		$row = mysqli_fetch_array($result);
		if (strcmp($password, $row[0])==0) {
			$_SESSION['userId'] = $row[1];
			$_SESSION['name'] = $row[3];
			$_SESSION['username'] = $username;
			$_SESSION['timestamp'] = time();
			$_SESSION['logged_in'] = true;
			if ($row[2]==1) {
				header('location: ../get-started.php');
			}
			else {
				header('location: ../dashboard.php');
			}
		}
		else {
			$_SESSION['err_msg'] = "Invalid username/password.";
			header('location: ../index.php');
		}
	}

	mysqli_close($con);
?>