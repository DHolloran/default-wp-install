<?php
// == Object To Array ===========================================================
	function fm_to_array($data) {
		if (is_object($data)) $data = get_object_vars($data);
		return is_array($data) ? array_map(__FUNCTION__, $data) : $data;
	} // fm_to_array($data)


// == Array To Object ============================================================
	function fm_to_object($data) {
		return is_array($data) ? (object) array_map(__FUNCTION__, $data) : $data;
	} // fm_to_object($data)


// == Add Breadcrumbs To Pages/Posts =============================================
	function the_breadcrumb() {
		if (!is_home()) {
			echo '<a href="'.get_option('home').'">';
			bloginfo('name');
			echo "</a> » ";
			if (is_category() || is_single()) {
				the_category('title_li=');
				if (is_single()) {
				echo " » ";
				the_title();
				} // if()
			} elseif (is_page()) {
				echo the_title();
			} // if/elseif()/
		} // if (!is_home())
	} // the_breadcrumb()


// == Check If LocalHost =========================================================
	function fm_is_localhost()
	{
		$localhost = array('localhost', '127.0.0.1');
		$host = $_SERVER['HTTP_HOST'];
		if (in_array($host, $localhost)) {
			return true;
		} else {
			return false;
		} // if/else()

	} // fm_is_localhost()


// == Email Validation ===========================================================
	function fm_is_valid_email($email)
	{
		//Perform a basic syntax-check if this check fails, there's no need to continue
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;

		//extract host
		list($user, $host) = explode("@", $email);
		//check, if host is accessible
		if (!checkdnsrr($host, "MX") && !checkdnsrr($host, "A"))
		{
					 return false;
		}

		return true;
	} //fm_is_valid_email($email)


// == Links in Text to URLs ======================================================
	function fm_url_to_link($text) {
		// The Regular Expression filter
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/g";

		// Check if there is a url in the text
		if (preg_match_all($reg_exUrl, $text, $url)) {
			// Make the URLs hyper links
			foreach($url[0] as $v){
				// Current position of the searached url
				$curpos = strpos($text,' '.$v)+1;
				// Delete the url
				$text = substr_replace($text,'', $curpos, strlen($v));
				// Insert the link
				$text = substr_replace($text,''.$v.'', $curpos ,0);
			} // foreach()

			// Return URLified text
			return $text;

		} // if()

		// if no URLs in the text just return the text
		return $text;
	} // url_to_link($text)


//  == Set Default Post Thumbnail ================================================
	function fm_set_default_thumbnail( $post_id ) {
	   // Link to default thumbnail
	   $default_thumb = get_stylesheet_directory_uri(). '_assets/img/thumbs/default_thumb.png';
	   // Get Thumbnail
	   $post_thumbnail = get_post_meta( $post_id, $key = '_thumbnail_id', $single = true );

	   // Verify that post is not a revision
	   if ( !wp_is_post_revision( $post_id ) ) {
	      // Check if Thumbnail exists
	      if ( empty( $post_thumbnail ) ) {
	         // Add thumbnail to post
	         update_post_meta( $post_id, $meta_key = '_thumbnail_id',
	                          $meta_value = $default_thumb );
	      } // if()
	   } // if()

	} // fm_set_default_thumbnail( $post_id )



