<?php
function cb_recent_tweets( $values )
{
	// :TODO Make links clickable.
	// :TODO Make into a jQuery plugin.
	// :TODO Make into a Wordpress plugin.
	// :TODO Make into a widget.
	// :TODO Make options for external wrapper and tweets wrapper.
	// :TODO Make it work with Handlebars and AJAX.
	// :TODO Better cache system.
	// :TODO Human readable time.

	// Extract the supplied values
	extract($values);
	if ($user == ''){
		echo 'Please supply a valid Twitter user name. Example: [recent_tweets user="username"]';
	} else {
		// Make sure we have a number and not a string.
		$display_amount = (int)$display_amount;

		// Get cache values.
		$twitter_cache_time = get_option('cb_twitter_time', FALSE);
		$twitter_cache_data = get_option('cb_twitter_data', FALSE);
		$twitter_cache_user = get_option('cb_twitter_user', FALSE);

		if($twitter_cache_user and $twitter_cache_user == $user){
			// Check if cache exists
			if ( $twitter_cache_time and $twitter_cache_data ) {
				// Check if the last access time was less than 10 minutes ago
				$last_access_time = strtotime($twitter_cache_time);
				if ( $last_access_time > time() - 60 * 10 ) {
					// If all tests have past we use the cached tweet to echo out
					$response = $twitter_cache_data;
					echo $response;
				} // if($twitter_cache_time>time()-60*10)
			} // if($twitter_cache_timeand$twitter_cache_data)
		} // if($twitter_cache_user)

		// If cache file does not exist, it has been longer then 10 minutes, user has changed or the file is empty...
		if ( !isset( $response) ) {
			// ...then query Twitter API and parse JSON response...
			$twitter_api = 'http://search.twitter.com/search.json?q=' . $user . '&result_type=recent';
			$response = file_get_contents($twitter_api);
			$tweets = json_decode($response);
				$users_tweets = '';
				$tweet_count = 0;
				// ...flip through JSON response and...
				foreach ( $tweets->results as $tweet ) {
					// ...if we are over the requested display amount then break out of foreach...
					if ( $tweet_count == $display_amount ) break;
					// ...append ONLY recent tweets sent by the user...
					if ( strtolower($tweet->from_user) == strtolower($user) ) {
							// Set the tweet created at time to a human readable format.
							$tweet_time = human_time_diff( time($tweet->created_at), current_time('timestamp') );
							if ( $tweet_time < 10) {
								$tweet_time = 'Less than 10 minutes';
							}
							$users_tweets .= '<li>';
							$users_tweets .= '<span class="tweet-text">' . $tweet->text . "</span>\n";
							$users_tweets .= '<span class="tweet-time">' . $tweet_time . ' ago' . "</span>\n";
							$users_tweets .= '<span class="user-link"><a href="';
							$users_tweets .= 'https://twitter.com/' . $user;
							$users_tweets .= '">' . $user . '</a></span>';
							$users_tweets .= "</li>\n";
							$tweet_count++;
					} // if(from_user == user )
				} // foreach($tweets->results as $tweet)

				// Make sure we have some tweets to work with...
				$tweet_text = '';
				if ( $users_tweets != '' ) {
					// ...if we have tweets then add them to tweet text...
					$tweet_text .=  "<ul class=\"twitter_feed\">\n" . $users_tweets . "</ul>";
				} else {
					// ...if we have no tweets add the notification.
					$tweet_text .= "Sorry, no recent tweets";
				} // if/else($users_tweets)

				// Update cache.
				update_option('cb_twitter_time', date('m/d/Y H:i:s'));
				update_option('cb_twitter_data', $tweet_text);
				update_option('cb_twitter_user', $user);

				// Print out the tweet text
				echo $tweet_text;
		} // if/else()
		//file_put_contents(  $cache_file , $response );
	} // if($user=='')
} // fmc_recent_tweets()
