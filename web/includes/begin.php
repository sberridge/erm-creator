<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>ERM Creator</title>
	<script src='/js/modal.js'></script>
	<link rel="stylesheet" href="/css/style.css">
</head>
<body>
	<?php
	if(isset($messages)) {
		foreach($messages as $type=>$messageList) {
			foreach($messageList as $message) {
	?>
	<h2 class="<?=$type?>"><?=$message?></h2>
	<?php	
			}
		}
	}
	?>