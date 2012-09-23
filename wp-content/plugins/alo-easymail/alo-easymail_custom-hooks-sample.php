<?php
/**
 * You can use this file to add you custom hooks to EasyMail plugin.
 *
 * To make loading this file you have to rename it to 'alo-easymail_custom-hooks.php'.
 * Some examples of custom hooks on http://www.eventualo.net/blog/wp-alo-easymail-newsletter/
 *
 * IMPORTANT! To avoid the loss of the file when you use the automatic WP upgrade,
 * I suggest that you move the file into folder /wp-content/mu-plugins 
 * (if the directory doesn't exist, simply create it).
 *
*/




/*******************************************************************************
 * 
 * EXAMPLE 
 *
 * The following set of functions adds a new placeholder that includes the latest 
 * published posts inside newsletter
 *
 * @since: 2.0
 *
 ******************************************************************************/


/**
 * Add placeholder to table in new/edit newsletter screen
 *
 */
function custom_easymail_placeholders ( $placeholders ) {
	$placeholders["custom_latest"] = array (
		"title" 		=> __("Latest posts", "alo-easymail"),
		"tags" 			=> array (
			"[LATEST-POSTS]"		=> __("A list with the latest published posts", "alo-easymail").". ".__("The visit to this url will be tracked.", "alo-easymail")
		)
	);
	return $placeholders;
}
add_filter ( 'alo_easymail_newsletter_placeholders_table', 'custom_easymail_placeholders' );


/**
 * Add selects in placeholders table
 * 
 * Note that the hook name is based upon the name of placeholder given in previous function as index:
 * alo_easymail_newsletter_placeholders_title_{your_placeholder}
 * If placeholder is 'my_archive' the hook will be:
 * alo_easymail_newsletter_placeholders_title_my_archive
 *
 */
function custom_easymail_placeholders_title_custom_latest ( $post_id ) {
	echo __("Select how many posts", "alo-easymail"). ": ";	
	echo '<select name="placeholder_custom_latest" id="placeholder_custom_latest" >';
	for ( $i = 3; $i <= 10; $i++ ) {
	    $select_custom_latest = ( get_post_meta ( $post_id, '_placeholder_custom_latest', true) == $i ) ? 'selected="selected"': '';
	    echo '<option value="'.$i.'" '. $select_custom_latest .'>'. $i. '</option>';
	}
	echo '</select><br />';

	$cat_args = array(
		'show_option_all' 	=> 	esc_html( '(no, all categories)' ),
		'name' 				=>	'placeholder_custom_latest_cat'
	);
	if ( $select_custom_latest_cat = get_post_meta ( $post_id, '_placeholder_custom_latest_cat', true ) ) {
		$cat_args['selected'] =  (int)$select_custom_latest_cat;
	} 
	echo __("Filter by category", "alo-easymail"). ": ";	
	wp_dropdown_categories( $cat_args );	
}
add_action('alo_easymail_newsletter_placeholders_title_custom_latest', 'custom_easymail_placeholders_title_custom_latest' );


/**
 * Save latest post number when the newsletter is saved
 */
function custom_save_placeholder_custom_latest ( $post_id ) {
	if ( isset( $_POST['placeholder_custom_latest'] ) && is_numeric( $_POST['placeholder_custom_latest'] ) ) {
		update_post_meta ( $post_id, '_placeholder_custom_latest', $_POST['placeholder_custom_latest'] );
	}
	if ( isset( $_POST['placeholder_custom_latest_cat'] ) && is_numeric( $_POST['placeholder_custom_latest_cat'] ) ) {
		update_post_meta ( $post_id, '_placeholder_custom_latest_cat', $_POST['placeholder_custom_latest_cat'] );
	}	
} 
add_action('alo_easymail_save_newsletter_meta_extra', 'custom_save_placeholder_custom_latest' );


/**
 * Replace the placeholder when the newsletter is sending 
 * @param	str		the newsletter text
 * @param	obj		newsletter object, with all post values
 * @param	obj		recipient object, with following properties: ID (int), newsletter (int: recipient ID), email (str), result (int: 1 if successfully sent or 0 if not), lang (str: 2 chars), unikey (str), name (str: subscriber name), user_id (int/false: user ID if registered user exists), subscriber (int: subscriber ID), firstname (str: firstname if registered user exists, otherwise subscriber name)
 * @param	bol    	if apply "the_content" filters: useful to avoid recursive and infinite loop
 */ 
function custom_easymail_placeholders_get_latest ( $content, $newsletter, $recipient, $stop_recursive_the_content=false ) {  
	if ( !is_object( $recipient ) ) $recipient = new stdClass();
	if ( empty( $recipient->lang ) ) $recipient->lang = alo_em_short_langcode ( get_locale() );
	$limit = get_post_meta ( $newsletter->ID, '_placeholder_custom_latest', true );
	$categ = get_post_meta ( $newsletter->ID, '_placeholder_custom_latest_cat', true );
	$latest = "";
	if ( $limit ) {
		$args = array( 'numberposts' => $limit, 'order' => 'DESC', 'orderby' => 'date' );
		if ( (int)$categ > 0 ) $args['category'] = $categ;
		$myposts = get_posts( $args );
		if ( $myposts ) :
			$latest .= "<ul>\r\n";
			foreach( $myposts as $post ) :	// setup_postdata( $post );
				$post_title = stripslashes ( alo_em_translate_text ( $recipient->lang, $post->post_title, $post->ID, 'post_title' ) );

				$post_link = alo_em_translate_url( $post->ID, $recipient->lang );
				$trackable_post_link = alo_em_make_url_trackable ( $recipient, $post_link );
				
	   			$latest .= "<li><a href='". $trackable_post_link . "'>". $post_title ."</a></li>\r\n"; 
			endforeach; 
			$latest .= "</ul>\r\n";
		endif;	     
	} 
	$content = str_replace("[LATEST-POSTS]", $latest, $content);
   
	return $content;	
}
add_filter ( 'alo_easymail_newsletter_content',  'custom_easymail_placeholders_get_latest', 10, 4 );




/*******************************************************************************
 * 
 * EXAMPLE 
 *
 * Do actions when a newsletter delivery is complete
 *
 * @since: 2.0 
 *
 ******************************************************************************/

/**
 * Send a notification to author and to admin when a newsletter delivery is complete
 */ 
function custom_easymail_newsletter_is_delivered ( $newsletter ) {	
	$title = apply_filters( 'alo_easymail_newsletter_title', $newsletter->post_title, $newsletter, false );
	$content = "The newsletter **" . stripslashes ( $title ) . "**  was delivered to all recipients.";
	$content .= "\r\nTo disable this notification you have to edit: alo-easymail_custom-hooks.php";
	
  	$author = get_userdata( $newsletter->post_author );
  	wp_mail( $author->user_email, "Newsletter delivered!", $content );
  	wp_mail( get_option('admin_email'), "Newsletter delivered!", $content );
}
add_action ( 'alo_easymail_newsletter_delivered',  'custom_easymail_newsletter_is_delivered' );




/*******************************************************************************
 * 
 * EXAMPLE 
 *
 * Do actions when subscribers do something: eg. subscribe, unsubscribe,
 * edit subscription
 *
 * @since: 2.0 
 *
 ******************************************************************************/

 
/**
 * Send a notification to admin when there is a new subscriber
 * @param	obj
 * @param	int		user id optional: only if subscriber is also a registered user
 */ 
function custom_easymail_new_subscriber_is_added ( $subscriber, $user_id=false ) {
	if ( $user_id ) {
		$content = "A registered user has subscribed the newsletter:";
	} else {
		$content = "There is a new public subscriber:";
	}
	$content .= "\n\nemail: " . $subscriber->email ."\nname: ". $subscriber->name . "\nactivation: ". $subscriber->active . "\nlanguage: ". $subscriber->lang . "\n";
	if ( $user_id ) $content .= "user id: " . $user_id;
	$content .= "\r\nTo disable this notification you have to edit: alo-easymail_custom-hooks.php";
	wp_mail( get_option('admin_email'), "New subscriber", $content );
}
add_action('alo_easymail_new_subscriber_added',  'custom_easymail_new_subscriber_is_added', 10, 2 );


/**
 * Automatically add a new subscriber to a mailing list
 * @since 	2.1.3 
 * @param	obj
 * @param	int		user id optional: only if subscriber is also a registered user
 */ 
function custom_easymail_auto_add_subscriber_to_list ( $subscriber, $user_id=false ) {
	/*** Uncomment the next lines to make it works ***/
	// $list_id = 1; // put the ID of mailing list
	// alo_em_add_subscriber_to_list ( $subscriber->ID, $list_id ); 
}
add_action ( 'alo_easymail_new_subscriber_added',  'custom_easymail_auto_add_subscriber_to_list', 10, 2 );


/**
 * Do something when a subscriber updates own subscription info
 * @param	obj
 * @param	str 
 */ 
function custom_easymail_subscriber_is_updated ( $subscriber, $old_email ) {
	// do something...
}
add_action ( 'alo_easymail_subscriber_updated',  'custom_easymail_subscriber_is_updated', 10, 2);


/**
 * Do something when a subscriber unsubscribes
 * @param	str
 * @param	int		user id optional: only if subscriber is also a registered user
 */ 
function custom_easymail_subscriber_is_deleted ( $email, $user_id=false ) {
	// do something...
}
add_action('alo_easymail_subscriber_deleted',  'custom_easymail_subscriber_is_deleted', 10, 2 );


/**
 * Do something when a subscriber activates the subscription
 * (e.g. after click on activation link in email)
 * @since 	2.4.9
 * @param	str 
 */

function custom_easymail_subscriber_activated ( $email ) {
	// uncomment next lines to send a welcome message to just-activated subscribers
	/*
	$subscriber = alo_em_get_subscriber( $email );
	$subject = "Welcome on our newsletter!";
	$content = "Hi ". stripslashes( $subscriber->name ) .",\r\nwe are happy that you have activated the subscription to our newsletter.\r\n";
	$content .= "You'll receive news very soon.\r\n\r\nRegards\r\n". wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	wp_mail( $email, $subject, $content );
	*/
}
add_action ( 'alo_easymail_subscriber_activated',  'custom_easymail_subscriber_activated' );



/*******************************************************************************
 * 
 * EXAMPLE 
 *
 * The following functions add custom fields in subscription form 
 *
 * @since: 2.4
 *
 ******************************************************************************/


/**
 * Add these custom fields to in subscription form.
 * In this sample you will add 2 fields: Company (text) and Favourite music (select).
 * 
 * TO ADD THE SAMPLE FIELDS you have to uncomment the code inside the next function
 *
 * 
 * You have to populate an array following these rules.
 *
 * The array KEYS are the names of custom fields: they are used for database column name and variable name (so take care about variable names limitations).
 * It could be a good idea to use a 'cf_' prefix in name: e.g. cf_surname
 *
 * The array VALUES are arrays with parameters for custom fields.
 * Here you are the details:
 *
 *	humans_name			:	the "human readable" name used in blog (ugly default: the key)
 * 	sql_attr			: 	the attributes for the column in database table (default: "VARCHAR(100) DEFAULT NULL")
 * 	sql_key				:	the column in database table is an index (default: false): set up it to yes if you like to make custom queries
 * 							looking for the field. Note: if true, in subscribers list table, the column is ordinable by this field
 * 	input_validation	:	a string rappresenting the name of a php function to be invoked to check the value
 * 							when submitted by subscriber. It must return a bolean true or false.
 * 							Leave false for no validation check (default: false).
 * 							You can use:
 *							- php native functions: e.g. "is_numeric" (note: the submitted value is always a string, so "is_int" does not work as expected)
 *							- WP functions: e.g. "is_email"
 * 							- custom function: you can define it in this file (see below the "custom_easymail_cf_check_number_5_digits" function)
 *	input_type 			:	the type of the form field: "text", "textarea", "select" (default: "text")
 * 	input_values 		:	if the "input_type" is "select", you have to wrtie an array with option values (default: false).
 * 							E.g. for a Sex field: array( 'male' => __("Male", "alo-easymail"), 'female' => __("Female", "alo-easymail") )
 * 	input_attr			:	string with html attributes for the form field (default: ""): e.g "style=\"color: #f00\" width=\"20\" onclick=\"\""
							Do not add these attaributes: id, name, class, value, type, onchange, onblur, onkeydown
 */

function custom_easymail_set_my_custom_fields ( $fields ) {

	/*
	// Custom field: Company
	$fields['cf_company'] = array(
		'humans_name'		=> __("Company", "alo-easymail"),
		'sql_attr' 			=> "VARCHAR(200) NOT NULL AFTER `name`",	
		'input_type' 		=> "text",
		'input_mandatory' 	=> true,
		'input_validation' 	=> false
	);
	*/

	/*
	// Custom field: Fovourite music
	$fields['cf_music'] = array(
		'humans_name'		=> __("Favourite music", "alo-easymail"),
		'sql_attr' 			=> "VARCHAR(100) DEFAULT NULL",
		'sql_key' 			=> true,	
		'input_type' 		=> "select", 
		'input_options' 	=> array(
			"" 			=> '',
			"rock" 		=> __("Rock / Metal", "alo-easymail"),
			"jazz" 		=> __("Jazz", "alo-easymail"),
			"classic" 	=> __("Classic", "alo-easymail"),
			"country" 	=> __("Country / Folk", "alo-easymail"),
			"other" 	=> __("Other", "alo-easymail")
		),
		'input_mandatory' 	=> false,
		'input_validation' 	=> false,
		'input_attr'		=> "style=\"color: #f00\""
	);
	*/

	return $fields;
}
add_filter ( 'alo_easymail_newsletter_set_custom_fields', 'custom_easymail_set_my_custom_fields' );


/**
 * Sample of validation function: check if the passed data is a number 5 digits
 * 
 * To apply it to a custom field, add the name as value in field array:
 * 'input_validation' => 'custom_easymail_cf_check_number_5_digits'
 *
 */
function custom_easymail_cf_check_number_5_digits ($data) {
	if ( preg_match( "/^[0-9]{5}$/", $data ) ) {
		return true;
	} else {
		return false;
	}
}

?>
