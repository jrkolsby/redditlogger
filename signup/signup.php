<?php


include '../credentials.php';

$link = mysql_connect('localhost', $credentials['db_user'], $credentials['db_pass']); 
if (!$link) { 
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($credentials['db_name']);
$user = $_POST['user'];
$pass1 = $_POST['pass1'];
$pass2 = $_POST['pass2'];
$subreddits = $_POST['subreddits'];
$errors = array();
if (empty($user)) {
	array_push($errors, "Username is empty" . "<br/>");
} else {
	if (strlen($user) > 20) {
		array_push($errors, "Username is too long" . "<br/>");
	}
	if (strpos($user, " ") !== false) {
		array_push($errors, "Username cannot contain spaces" . "<br/>");
	}	
	$query = "SELECT * FROM users"; 
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		if ($row[user] == $user) {
			array_push($errors, "Username already taken" . "<br/>");
		};
	}
}
if (empty($pass1) && empty($pass2)) {
	array_push($errors, "Password is empty" . "<br/>");
} else if (empty($pass1) || empty($pass2)) {
	array_push($errors, "Please validate password" . "<br/>");
} else {
	if ($pass1 !== $pass2) {
		array_push($errors, "Passwords do not match" . "<br/>");
	} else {
		$pass = $pass1;
	}
	if (strlen($pass) > 30) {
		array_push($errors, "Password is too long" . "<br/>");
	}
	if (strpos($pass, " ") !== false) {
		array_push($errors, "Password cannot contain spaces" . "<br/>");
	}
}
if (empty($subreddits) || $subreddits == "i.e. pics funny gaming askreddit worldnews") {
	array_push($errors, "Subreddits are empty" . "<br/>");
} else {
	$subreddits = strtolower($subreddits);
	$subreddits = str_replace('_', 'AOMK95FERV9RWZN4W1CG', $subreddits);
	$subreddits = preg_replace('/[[:punct:]]/', '', $subreddits);
	$subreddits = str_replace('AOMK95FERV9RWZN4W1CG', '_', $subreddits);
	$subreddits_array = preg_split('/ /', $subreddits, -1, PREG_SPLIT_NO_EMPTY);
	if (count($subreddits_array) > 10) {
		array_push($errors, "Too many subreddits listed" . "<br/>");
	}
	$subreddits_array_temp = $subreddits_array;
	$subreddits_array = array();
	for ($i = 0; $i < count($subreddits_array_temp); $i++) {
		$subreddit_to_validate = array_shift(array_slice($subreddits_array_temp, $i, 1));
		$location = "http://reddit.com/r/" . $subreddit_to_validate . "/.json?limit=1";
		$json = json_decode(file_get_contents($location), true);
		if ($json !== NULL) {
			array_push($subreddits_array, $subreddit_to_validate);
		}
	}
}
if (!empty($errors)) {
	print "<!DOCTYPE html><head><title>...</title><script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script></head><body>";
	print "<script type='text/javascript'>$(document).ready(function() { $('form#errors').submit() }); </script>";
} else {
	$subreddits_file = file('../subreddits.txt', FILE_IGNORE_NEW_LINES);
	for ($i = 0; $i < count($subreddits_array); $i++) {
		$current_subreddit = array_shift(array_slice($subreddits_array, $i, 1));
		if (!in_array($current_subreddit, $subreddits_file)) {
			$current_data = $current_subreddit . "\n";
			file_put_contents('../subreddits.txt', $current_data, FILE_APPEND | LOCK_EX);
		}
	}
	$subreddit_serial = serialize($subreddits_array);
	$pass = md5($pass);
	mysql_query("INSERT INTO users (`user`, `pass`, `reddit`) VALUES ('$user', '$pass', '$subreddit_serial')");
	setcookie("reddituser", $subreddit_serial, time()+86400, "/", "redditlogger.com");
	print "<!DOCTYPE html><head><title>...</title>";
	print "<meta http-equiv='REFRESH' content='0;url=../'>";
}
?>
<form id="errors" method="post" action="../signup/">
<input name="errors" type="hidden" value="<?php 
for ($i = 0; $i <= count($errors); $i++) {
	print $errors[$i];
}
?>">
</form>
</body>