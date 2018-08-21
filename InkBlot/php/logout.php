<?php
	session_start();
	unset($_SESSION['username'], $_SESSION['timestamp'], $_SESSION['userId'], $_SESSION['name']);
    $_SESSION['logged_in'] = false;
    $_SESSION['err_msg'] = "You are successfully logout.";
    header("Location: ../index.php");
    exit;
?>