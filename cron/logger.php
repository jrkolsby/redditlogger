<?php

include '../credentials.php';

if ($_GET['a'] == $credentials['cron_pass']) {
	$link = mysql_connect('localhost', $credentials['db_user'], $credentials['db_pass']); 
	if (!$link) { 
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($credentials['db_name']);
	
	$subreddits_file = file('../subreddits.txt', FILE_IGNORE_NEW_LINES);
	
	for ($i = 0; $i < count($subreddits_file); $i = $i + 1) {
		$current_subreddit = array_shift(array_slice($subreddits_file, $i, 1));
		$location = "http://reddit.com/r/" . $current_subreddit . "/.json?limit=7";
		$json = json_decode(file_get_contents($location), true);
		for ($j = 0; $j < 7; $j++) {
			$subreddit = $json['data']['children'][$j]['data']['subreddit'];
			$title = $json['data']['children'][$j]['data']['title'];
			if (strlen($title) > 150) {
				$title = substr($title, 0, 147) . "...";
			}
			$score = $json['data']['children'][$j]['data']['score'];
			$domain = $json['data']['children'][$j]['data']['domain'];
			if (strlen($domain) > 15) {
				$domain = substr($domain, 0, 12) . "...";
			}
			$author = $json['data']['children'][$j]['data']['author'];
			$link = $json['data']['children'][$j]['data']['url'];
			$comment = $json['data']['children'][$j]['data']['permalink'];
			$date = date(Ymd);
			mysql_query("INSERT INTO posts (`subreddit`, `title`, `score`, `domain`, `author`, `link`, `comment`, `date`) VALUES ('$subreddit', '$title', '$score', '$domain', '$author', '$link', '$comment', '$date')");
		}
	}
	print "Successfully logged";
}

?>