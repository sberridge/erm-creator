<?php
	include('shared/bootstrap.php');
	unset($_SESSION['currentUser']);
	session_destroy();
	header('location: login.php');
	exit;
?>