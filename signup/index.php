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
<li class="selected"><a href="">Sign Up</a></li>
<?php
if (isset($_COOKIE['reddituser'])) {
	print "<li><a href='../logout.php'>Logout</a></li>";
} else {
	print "<li><a href='../login/'>Login</a></li>";
}
?>
</ul>
</div>
</div>
<form id="signup" action="signup.php" method="post">
<h1>Create an Account</h1>
<?php 
if (!empty($_POST['errors'])) {
	print "<h2>There are errors:</h2>";
	print "<h2>" . $_POST['errors'] . "</h2>";	
}
?>
<h3>Username</h3>
<input style="width: 188px" name="user">
<h3>Password</h3>
<input type="password" style="width: 210px; letter-spacing: 2px" name="pass1">
<h3>Validate password</h3>
<input type="password" style="width: 210px; letter-spacing: 2px" name="pass2">
<h3>Subreddits,<br/>space-separated</h3>
<input value="i.e. pics funny gaming askreddit worldnews" onfocus="if(this.value === 'i.e. pics funny gaming askreddit worldnews'){this.value = ''}" onblur="if(this.value === ''){this.value = 'i.e. pics funny gaming askreddit worldnews'}" style="width: 410px" name="subreddits">
<button type="submit">Create Account</button>
</form>