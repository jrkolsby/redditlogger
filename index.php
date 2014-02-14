<!DOCTYPE html>
<head>
<title>Reddit Logger</title>
<meta name="description" content="Never miss another link with Reddit Logger">
<meta name="robots" content="ALL">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="sto/apple-icon-114x114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="sto/apple-icon-144x144-precomposed.png" />
<link rel="shortcut icon" href="sto/favicon.png" type="image/png">
<link href="global.css" rel="stylesheet" type="text/css" />
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
<script type="text/javascript" src="note/note.js"></script>
</head>
<body>
<?php

include 'credentials.php';

$link = mysql_connect('localhost', $credentials['db_user'], $credentials['db_pass']); 
if (!$link) { 
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($credentials['db_name']);

if (!isset($_COOKIE['reddituser'])) {
	$query = "SELECT * FROM posts"; 
	$result = mysql_query($query) or die(mysql_error());
	$link_total = 0;
	while($row = mysql_fetch_array($result)){
		$link_total += 1;
	}
	$subreddits_file = file('subreddits.txt', FILE_IGNORE_NEW_LINES);
	$subreddit_total = count($subreddits_file);
	
	print "<div id='header'><div id='header-wrapper'><object id='logo' data='sto/logo.svg' type='image/svg+xml'><img src='sto/logo.png' /></object><ul id='menu'><li class='selected'><a href=''>Home</a></li><li><a href='signup/'>Sign Up</a></li><li><a href='login/'>Login</a></li></ul></div></div><div id='main'><h1>Never miss a link again.</h1><p>Going on vacation? Studying for exams? Don't let all of those beautiful, blue links go to waste. Each day, RedditLogger records the seven top links of ten subreddits of your choosing, allowing you access to a Reddit timeline upon your return. <b>" . $link_total . "</b> links logged so far in <b>" . $subreddit_total . "</b> different subreddits!</p><a href='signup/'><button>Sign Up</button></a></div>";
} else {
	if (isset($_GET['date'])) {
		$date = $_GET['date'];
	} else {
		$date = 0;
	}
	$subreddits_array = unserialize($_COOKIE['reddituser']);
	$date_current = date(Ymd, time() + ($date * 86400));
	print "<div id='calendar'>";
	print "<h3>" . date('F', time() + ($date * 86400)) . "</h3>";
	print "<h1>" . date('d', time() + ($date * 86400)) . "</h1>";
	print "</div><div id='arrow-left'></div><div id='arrow-right'></div><a id='logout' href='logout.php'>Logout</a>";
	print "<div id='links-wrapper'>";
	$query = "SELECT * FROM posts ORDER BY subreddit DESC"; 
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		if ($row[date] == $date_current && in_array(strtolower($row[subreddit]), $subreddits_array)) {
			$links = true;
			print "<div class='link'>";
			print "<div class='score'>" . $row[score] . "</div>";
			print "<div class='title'><a href='" . $row[link] . "'>" . $row[title] . "</a></div>";
			print "<h3>Submitted by <a href='http://reddit.com/u/" . $row[author] . "'>" . $row[author] . "</a> to <a href='http://reddit.com/r/" . $row[subreddit] . "'>" . $row[subreddit] . "</a> <b><a href='http://reddit.com" . $row[comment] . "'>Comments</a></b> <i>(" . $row[domain] . ")</i></h3>";
			print "</div>";
		}
	}
	if (!$links && $date < 0) {
		print "</div>";
		print "<h1 id='awkward'>No links logged for this day</h1>";
	} else if (!$links && $date == 0) {
		for ($i = 0; $i < count($subreddits_array); $i++) {
			$current_subreddit = array_shift(array_slice($subreddits_array, $i, 1));
			$location = "http://reddit.com/r/" . $current_subreddit . "/.json?limit=7";
			$json = json_decode(file_get_contents($location), true);
				for ($j = 0; $j < 7; $j++) {
					print "<div class='link'>";
					print "<div class='score'>" . $json['data']['children'][$j]['data']['score'] . "</div>";
						$title_temp = $json['data']['children'][$j]['data']['title'];
						if (strlen($title_temp) > 150) {
							$title_temp = substr($title_temp, 0, 147) . "...";
						}
					print "<div class='title'><a href='" . $json['data']['children'][$j]['data']['url'] . "'>" . $title_temp . "</a></div>";
					print "<h3>Submitted by <a href='http://reddit.com/u/" . $json['data']['children'][$j]['data']['author'] . "'>" . $json['data']['children'][$j]['data']['author'] . "</a> to <a href='http://reddit.com/r/" . $json['data']['children'][$j]['data']['subreddit'] . "'>" . $json['data']['children'][$j]['data']['subreddit'] . "</a> <b><a href='http://reddit.com" . $json['data']['children'][$j]['data']['permalink'] . "'>Comments</a></b> <i>(" . $json['data']['children'][$j]['data']['domain'] . ")</i></h3>";
					print "</div>";			
				}
		}
		print "</div>";
	} else if (!$links && $date > 0) {
		print "</div>";
		print "<h1 id='awkward'>Once this baby hits 88 miles per hour, you're going to see some serious shit</h1>";
	}
}
?>
<script type='text/javascript'>
var newloc = window.location.origin + window.location.pathname;
$(document).ready(function() {
	var date = <?php if ($date == 0) {echo 0;} else {echo $date;} ?>;
	$('#arrow-left').click(function() {
		date -= 1;
		var url = newloc + '?date=' + date;
		window.location = url;
	});
	$('#arrow-right').click(function() {
		date += 1;
		var url = newloc + '?date=' + date;
		window.location = url;
	});
});
</script>