<?php
// == Object To Array ===========================================================
function dh_to_array( $obj ) {
	if ( is_object( $obj ) ) $obj = get_object_vars( $obj );
	return is_array( $obj ) ? array_map( __FUNCTION__, $obj ) : $obj;
} // dh_to_array($data)


// == Array To Object ============================================================
function dh_to_object( $array ) {
	return is_array( $array ) ? (object) array_map( __FUNCTION__, $array ) : $array;
} // dh_to_object($data)


// == Add Breadcrumbs To Pages/Posts =============================================
function dh_the_breadcrumb() {
	if ( !is_home() or !is_front_page() ) {
		echo '<a href="'.get_option( 'home' ).'">';
		bloginfo( 'name' );
		echo "</a> » ";
		if ( is_category() || is_single() ) {
			the_category( 'title_li=' );
			if ( is_single() ) {
				echo " » ";
				the_title();
			} // if()
		} elseif ( is_page() ) {
			echo the_title();
		} // if/elseif()/
	} // if (!is_home())
} // the_breadcrumb()


// == Check If LocalHost =========================================================
function dh_is_localhost() {
	$localhost = array( 'localhost', '127.0.0.1' );
	$host = $_SERVER['HTTP_HOST'];
	if ( in_array( $host, $localhost ) ) {
		return true;
	} else {
		return false;
	} // if/else()

} // dh_is_localhost()


// == Email Validation ===========================================================
function dh_is_valid_email( $email ) {
	//Perform a basic syntax-check if this check fails, there's no need to continue
	if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) return false;

	//extract host
	list( $user, $host ) = explode( "@", $email );
	//check, if host is accessible
	if ( !checkdnsrr( $host, "MX" ) && !checkdnsrr( $host, "A" ) ) {
		return false;
	}

	return true;
} //dh_is_valid_email($email)


// == Links in Text to URLs ======================================================
function dh_url_to_link( $text ) {
	// The Regular Expression filter
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/g";

	// Check if there is a url in the text
	if ( preg_match_all( $reg_exUrl, $text, $url ) ) {
		// Make the URLs hyper links
		foreach ( $url[0] as $v ) {
			// Current position of the searached url
			$curpos = strpos( $text, ' '.$v )+1;
			// Delete the url
			$text = substr_replace( $text, '', $curpos, strlen( $v ) );
			// Insert the link
			$text = substr_replace( $text, ''.$v.'', $curpos , 0 );
		} // foreach()

		// Return URLified text
		return $text;

	} // if()

	// if no URLs in the text just return the text
	return $text;
} // url_to_link($text)


//  == Set Default Post Thumbnail ================================================
function dh_default_thumbnail( $post_id, $default_thumb=false ) {

	// Link to default thumbnail
	if ( !$default_thumb ) {
		$default_thumb = get_stylesheet_directory_uri(). '_assets/img/thumbs/default_thumb.png';
	}

	// Get Thumbnail
	$post_thumbnail = get_post_meta( $post_id, $key = '_thumbnail_id', $single = true );

	// Verify that post is not a revision
	if ( !wp_is_post_revision( $post_id ) ) {
		// Check if Thumbnail exists
		if ( empty( $post_thumbnail ) ) {
			// Add thumbnail to post
			update_post_meta( $post_id, $meta_key = '_thumbnail_id', $meta_value = $default_thumb );
		} // if(empty($post_thumbnail))
	} // if(!wp_is_post_revision)

} // dh_default_thumbnail( $post_id )


// == Safe Session Start =========================================================
function dh_session_start() {
	if ( !isset( $_SESSION ) ) session_start();
} // dh_session_start()


// == Check/Get Post Thumbnail ===================================================
function dh_get_thumbnail( $id, $size='thumbnail', $attr='' ) {
	// If the post does not have a thumbnail
	if ( !has_post_thumbnail( $id ) ) return false;

	// If the post has a thumbnail
	return get_the_post_thumbnail( $id, $size, $attr );
} // dh_post_thumbnail()


// == Check/Get Post Thumbnail URL ===============================================
function dh_get_thumbnail_url() {
	// If the post does not have a thumbnail
	if ( !has_post_thumbnail( $id ) ) return false;

	// If the post has a thumbnail
	$image_url = wp_get_attachment_image_src( get_post_thumbnail_id() );
	return $image_url[0];
} // dh_get_thumbnail_url()


// == Get Page ID by Slug ========================================================
function dh_get_page_id_by_slug( $page_slug ) {
	// Get the page from the page slug
	$page = get_page_by_path( $page_slug );

	// If we have a page then send back the page ID
	if ( $page ) return $page->ID;

	// If not return FALSE
	return FALSE;
} // dh_get_page_id_by_slug($page_slug)


// == Get User IP ================================================================
function dh_get_user_ip() {
	if ( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	} elseif ( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
		return $_SERVER["HTTP_CLIENT_IP"];
	} else {
		return $_SERVER["REMOTE_ADDR"];
	}
} // dh_get_user_ip()
