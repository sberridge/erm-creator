<?php
	session_start();
	function __autoload($class) {
		if(file_exists(dirname(dirname(__FILE__))."/classes/".$class.".php")) {
			include(dirname(dirname(__FILE__))."/classes/".$class.".php");
		}
	}
	$dbHandle = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS);
	if(isset($_SESSION['flash'])) {
		foreach($_SESSION['flash'] as $key=>$val) {
			$$key = $val;
		}
		unset($_SESSION['flash']);
	}
	if(isset($_SESSION['currentUser'])) {
		$currentUser = new User;
		$currentUser->load($_SESSION['currentUser']);
	}
?>