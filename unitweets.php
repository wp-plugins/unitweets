<?php
/*
Plugin Name: UniTweets
Plugin URI: http://unitinteractive.com/labs/unitweets.php
Description: Searches one or many Twitter accounts and inserts matching tweets as blog posts. Uses <a href="http://emmense.com/php-twitter/">PHP Twitter by Emmense Techologies</a>
Version: 0.1.0
Author: Unit Interactive
Author URI: http://unitinteractive.com/unit-info.php

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require('includes/class.twitter.php');

	// set up recurrance on install
	function installUniTweets()
	{
		wp_schedule_event( time(), 'hourly', 'updateTweets' );
	}
	
	// remove recurrance
	function uninstallUniTweets()
	{
		wp_clear_scheduled_hook( 'updateTweets' );
	}
	
	// formatting '@username' to '<a href="http://twitter.com/username">@username</a>'
	function utRefLinks( $ref )
	{
		return '<a href="http://twitter.com/' . str_replace('@', '', $ref[0]) . '">' . $ref[0] . '</a>';
	}
	
	
	// formatting 'http://www.site.com' to '<a href="http://www.site.com">http://www.site.com</a>'
	function utURLLinks( $url )
	{
		return '<a href="' . $url[0] . '">' . $url[0] . '</a>';
	}
	
	
	// controls reformatting of tweets
	function utFormatTweet( $text )
	{
		// replace URLs with links
		preg_match_all( '/http[^\s]+/', $text, $urls );
		
		$urls = preg_replace( '/\//', '\/', $urls[0] );
		
		for( $i = 0; $i < count( $urls ); $i++ )
		{
			$urls[ $i ] = '/' . $urls[ $i ] . '/';
		}
	
		$cleanTweet = preg_replace_callback( $urls, 'utURLLinks', $text );
		
		// replace @username references with links
		preg_match_all('/^@[^\s]+|\s@[^\s]+\b/', $cleanTweet, $users);
		
		$users = preg_replace( "/\s/", "", $users[0] );
		
		for( $i = 0; $i < count( $users ); $i++ )
		{
			$users[ $i ] = '/' . $users[ $i ] . '/';
		}
		
		$cleanTweet = preg_replace_callback( $users, 'utRefLinks', $cleanTweet );
		
		return $cleanTweet;
	}


	function uniTweets() 
	{
		global $wpdb;
		
		$options = get_option( 'uniTweetsSettings' );
		$category = $options['category'];
		
		$accounts = get_option( 'uniTweetsAccounts' );
		
		echo '<div style="display:none;">';
		var_dump( $accounts );
		echo '</div>';
		
		foreach ( $accounts as $account )
		{
			// retrieve tweets
			$t = new twitter;
			$t->username = $account['user'];
			$t->password = $account['pass'];
		
			$data = $t->userTimeline();
			
			/* $data = 
			 * 	'text'
			 * 	'created_at'
			 * 	'id'
			 */
			
			// loop through all returned tweets
			foreach($data as $tweet) 
			{
				// check criteria for storing the tweet
				if ( ( $account['hash'] && preg_match('/' . $account['hash'] . '/', $tweet->text ) ) || !$account['hash'] )
				{
					// make sure we haven't already stored the tweet
					$query = "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'tweetID' AND meta_value = '$tweet->id'";
					
					if ( !count( $wpdb->get_results( $query ) ) )
					{
						$content = utFormatTweet( $tweet->text );	
						// create post object
					  	$tweet_post = array(
							'post_title' 	=> 'Tweet ' . $tweet->id,
							'post_date' 	=> date( 'Y-m-d H:i:s', ( strtotime( $tweet->created_at ) + 7200 ) ),
							'post_content' 	=> $content,
							'post_status' 	=> 'publish',
							'post_author'	=> $account['author'],
							'post_category'	=> array( $category )
						);
						
						// insert the post into the database
						$post_id = wp_insert_post( $tweet_post );
				
						add_post_meta( $post_id, 'user', $account['user'] );
						add_post_meta( $post_id, 'tweetID', $tweet->id );
					}
				}
			}
		}
	}
	
	add_action( 'updateTweets', 'uniTweets' );
	register_activation_hook( __FILE__, 'installUniTweets' );
	register_deactivation_hook( __FILE__, 'uninstallUniTweets' );
	
	// init plugin options to white list our options
	function uniTweetsOptions()
	{
		register_setting( 'uniTweetsOptions', 'uniTweetsSettings' );
		register_setting( 'uniTweetsOptions', 'uniTweetsAccounts', 'uniTweetsOptionsValidate' );
	}
	
	// add menu page
	function uniTweetsMenu()
	{
		add_options_page('UniTweets Options', 'UniTweets', 'manage_options', 'unitweets', 'uniTweetsOptionsRender');
	}
	
	// draw the form
	function uniTweetsOptionsRender()
	{
		include( 'includes/options.php' );
	}
	
	// validate the accounts options
	function uniTweetsOptionsValidate( $input )
	{
		foreach ( $input as $key => $value )
		{
			if ( $input[ $key ]['user'] == '' || $input[ $key ]['pass'] == '')
			{
				unset( $input[ $key ] );
			}
		}

		return $input;		
	}
	
	add_action('admin_init', 'uniTweetsOptions' );
	add_action('admin_menu', 'uniTweetsMenu');
?>