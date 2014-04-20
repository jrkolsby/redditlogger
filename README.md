# Reddit Logger
`Reddit Logger` in essence, is a PHP program that logs links from [Reddit](http://reddit.com) into a MySQL database every day. It's like DVR for Reddit links.

## Sales Pitch

Going on vacation? Studying for exams? Don't let all of those beautiful, blue links go to waste. Each day, RedditLogger records the seven top links of ten subreddits of your choosing, allowing you access to a beautifully-designed Reddit timeline upon your return.

### [Repo hosted here](http://redditlogger.com/)


## Development

Each day at around 10PM EST, a cron job launches a PHP script that connects Reddit API, logs links from all of the subreddits listed in a .txt file, and stores them on a MySQL table (Thanks, [setcronjob](http://setcronjob.com/)). One thing that was more or less new to me was an account making process. To be honest, I probably screwed this part up a bit, but I managed to figure out a very rudimentary way of going about it. On signup, all of the inputs are verified for characters and whatnot, the listed subreddits are verified to actually exist, and if everything checks out OK, the subreddits are added into a `subreddits.txt` file, and the username, encrypted password, and serialized subreddit list are all logged to a seperate table. On login, the username and password are verified against the `users` MySQL table, and if the login checks out a cookie is stored on the client's machine with his account's serialized subreddit array. When he logs in, PHP iterates through the `posts` table and prints posts from the selected date with subreddits in the cookie's subreddit array. Without doubt there's a more sophisticated way of doing this, but this setup works for the moment being (granted, there are only ~10 accounts thus far).
