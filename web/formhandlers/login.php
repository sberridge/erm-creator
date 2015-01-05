<?php
	include('../shared/bootstrap.php');
	$form = loginForm();
	$validate = $form->validate();
	
	if($validate === true) {
		$user = new User;
		if($user->authenticate($_POST['email'],$_POST['password'])) {
			$_SESSION['currentUser'] = $user->id;
			header('location:../');
			exit;
		} else {
			$_SESSION['flash']['messages'] = array("errors"=>array("Authentication failed"));
			$_SESSION['flash']['postData'] = $_POST;
			header('location:../login.php');
			exit;
		}
	} else {
		$_SESSION['flash']["messages"] = $validate;
		$_SESSION["flash"]['validation'] = $validate;
		$_SESSION["flash"]['postData'] = $_POST;
		header('location:../login.php');
		exit;
	}
?>