<!DOCTYPE html>
<head>
<title>Reddit Logger</title>
<meta name="description" content="Never miss another link again with Reddit Logger">
<meta name="robots" content="ALL">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../sto/apple-icon-114x114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../sto/apple-icon-144x144-precomposed.png" />
<link rel="shortcut icon" href="../sto/favicon.png" type="image/png">
<link href="../global.css" rel="stylesheet" type="text/css" />
<style type="text/css" media="only screen and (max-device-width: 1024px)">
body {
min-width: 1024px;
}
</style>
<style type="text/css" media="only screen and (max-device-width: 480px)">
body {
min-width: 480px;
}
</style>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700|Bree+Serif' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
</head>
<body>
<div id="header">
<div id="header-wrapper">
<object id="logo" data="../sto/logo.svg" type="image/svg+xml">
<img src="../sto/logo.png" />
</object>
<ul id="menu">
<li><a href="../">Home</a></li>
<li><a href="../signup/">Sign Up</a></li>
<?php
if (isset($_COOKIE['reddituser'])) {
	print "<li><a href='../logout.php'>Logout</a></li>";
} else {
	print "<li class='selected'><a href=''>Login</a></li>";
}
?>
</ul>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("#username").focus();
});
</script>
<form id="login" action="login.php" method="post">
<h1>Login</h1>
<?php 
if (!empty($_POST['errors'])) {
	print "<h2>There are errors:</h2>";
	print "<h2>" . $_POST['errors'] . "</h2>";	
}
?>
<input id="username" value="username" onfocus="if(this.value === 'username'){this.value = ''}" onblur="if(this.value === ''){this.value = 'username'}" style="width: 188px;" name="user">
<input value="password" type="text" onfocus="if(this.value === 'password'){this.value = ''; this.type = 'password'; $(this).css('letter-spacing', '2px');}" onblur="if(this.value === ''){this.value = 'password'; this.type = 'text'; $(this).css('letter-spacing', '0px');}" style="width: 188px;" name="pass">
<button type="submit">Login</button>
</form>