<?php
	include("../shared/bootstrap.php");
	$form = registerForm();
	$validate = $form->validate();

	if($validate === true) {
		$user = new User; 
		if(count($user->get("WHERE email = ?",array($_POST['email']))) == 0) {
			if($user->create($_POST['email'],$_POST['password'],$_POST['name'])) {
				$_SESSION['flash']['messages'] = array("successes"=>array("Account created"));
				header('location:../login.php');
				exit;
			} else {
				$_SESSION['flash']['messages'] = array("errors"=>array("Account not created"));
				header('location:../login.php');
				exit;
			}
		} else {
			$_SESSION['flash']['messages'] = array("errors"=>array("Email already in use"));
			header('location:../login.php');
			exit;
		}
		
	} else {
		$_SESSION['flash']['messages'] = $validate;
		$_SESSION['flash']['validation'] = $validate;
		$_SESSION['flash']['postData'] = $_POST;
		header('location:../login.php');
		exit;
	}
?>