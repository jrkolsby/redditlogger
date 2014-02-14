<?php

	include '../credentials.php';

	$link = mysql_connect('localhost', $credentials['db_user'], $credentials['db_pass']); 
	if (!$link) { 
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($credentials['db_name']);
	
	$user = $_POST['user'];
	$pass = md5($_POST['pass']);
	$errors = array();
	$loggedin = false;
	
	$query = "SELECT * FROM users"; 
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		if ($user == $row[user] && $pass == $row[pass]) {
			$loggedin = true;
			setcookie("reddituser", $row[reddit], time()+86400, "/");
			print "<!DOCTYPE html><head><title>...</title>";
			print "<meta http-equiv='REFRESH' content='0;url=../'>";
		}
	}
	if ($loggedin == false) {
		array_push($errors, "Incorrect login" . "<br/>");
	}
	?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
	<?php
	if (!empty($errors)) {
		print "<script type='text/javascript'>$(document).ready(function() { $('form#errors').submit() }); </script>";
	}
	?>
	<form id="errors" method="post" action="../login/">
	<input name="errors" type="hidden" value="<?php 
	for ($i = 0; $i <= count($errors); $i++) {
		print $errors[$i];
	}
?>">
</form>
</body>