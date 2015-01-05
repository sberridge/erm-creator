<?php
	include('shared/bootstrap.php');
	include('includes/begin.php');
?>
<div class="container">
<h2>Login</h2>
<?php
	$form = loginForm();
	$form->render((isset($postData) ? $postData : null),(isset($validation) ? $validation : null));
?>
<h2>Or register</h2>
<?php
	$form = registerForm();
	$form->render((isset($postData) ? $postData : null),(isset($validation) ? $validation : null));
?>
</div>
<?php
	
	include('includes/end.php');
?>