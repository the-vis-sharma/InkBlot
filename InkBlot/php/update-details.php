<?php
	session_start();

	$userId = $_POST['userId'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$mobile = $_POST['mobile'];

	$con = mysqli_connect("localhost", "root", "root", "InkBlotDB");

	if (!con) {
		die("Sorry, Can't connect to database.");
	}

	$query = "UPDATE Users SET name = '$name', email = '$email', mobile = $mobile, is_first_time = 0 WHERE userId=$userId";

	$result = mysqli_query($con, $query);

	if ($result) {
		$_SESSION['name'] = $name;
		header('location: ../dashboard.php');	
	}
	else {
		$_SESSION['err_msg'] = "Something went wrong, could not update your details. Try Again later.";
		header('location: ../get-started.php');
	}

	mysqli_close($con);
?>