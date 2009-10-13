=== UniTweets ===
Contributors: unitinteractive
Tags: twitter, social-network, posts
Requires at least: 2.8
Tested up to: 2.8.4
Stable tag: 0.1.0

Don't settle for just displaying your latest tweets on your WordPres blog. Integrate Twitter by 
saving your tweets as posts and displaying them however you want to! 

== Description ==

 UniTweets is a plugin for WordPress 2.8+ that allows tweets from an unlimited number of accounts to be 
 added as posts to you blog. It doesn't just show your latest tweets in a list, but actually adds a whole 
 new WordPress post that can then be edited and displayed however you choose. You can post all tweets from 
 an account or optionally filter the tweets by an exact match string.

* Doesn't just list recent tweets, but actually adds them to your WordPress database.
* Add tweets from as many accounts as you want.
* Filter tweets by hashtag or any other exact string match.
* Uses built-in WordPress cron system to look for new tweets "every hour".
* Built on [PHP Twitter by Emmense Techologies](http://emmense.com/php-twitter/ "PHP Twitter")


== Installation ==

1. Unzip the UniTweets folder into the plugins directory of your WordPress installation.
2. Next, log in to your WordPress admin and go to the 'Plugins" section. Click 'activate' under 'UniTweets'.
3. Finally, under 'Settings' click on 'UniTweets' to be taken to the UniTweets options panel. Here you can 
configure which accounts the plugin should check, their respective filters, which blog author the posts should 
be attributed to, and which category the tweets will be posted in. After configuring the plugin, your tweets 
should begin to show up within the hour! 


== Frequently Asked Questions ==

= Why don't my tweets get posted immediately? =

UniTweets uses the built-in WordPress cron feature to automate the process of searching Twitter accounts. While 
not a true 'cronjob', as long as the blog has a decent amount of traffic the plugin will effectively check for 
new tweets once an hour. This is because WordPress's cron runs on each page-load and checks to see if it has 
been longer than the scheduled interval since the function was last run. If so, it runs the function and sets 
the timer forward. So, while your tweets won't appear instantly, there should be a minimal amount of time between 
postings.  

= Why are my tweets displaying like regular blog posts? =

UniTweets posts *are* regular WordPress posts but with some Twitter specific information embedded in each one. 
How you choose to display the tweet is up to you. A default theme isn’t likely to have anything built in to handle 
UniTweet posts and will simply display them as it would any other post.

== Screenshots ==

1. The plugin configuration screen

== Changelog ==

= 0.1.0 =
* Initial release.
