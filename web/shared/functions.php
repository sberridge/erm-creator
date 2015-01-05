<?php
	function hash_pwd($password) {
		$string = "abcdefghijklmnopqrstuvwxyz0123456789./ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$salt = "";
		for($i = 0; $i < 22; $i++) {
			$salt .= $string[mt_rand(0,strlen($string)-1)];
		}
		$hashedPassword = crypt($password,"$2a$08$".$salt);
		return $hashedPassword;
	}
	function auth($password,$hash) {
		if($hash == crypt($password,$hash)) {
			return true;
		} else {
			return false;
		}
	}
?>