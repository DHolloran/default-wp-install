<?php
// == Enqueue Site Styles ==================================================
	function fm_enqueue_site_styles()
	{
		// Register main site stylesheet
		wp_register_style('fm-site-styles', get_stylesheet_directory_uri(). '/_assets/css/site.css',
		 									array(), null, 'all');

		// Register custom site stylesheet
		wp_register_style('fm-custom-site-styles', get_stylesheet_directory_uri(). '/_assets/css/custom.css',
		 									array('fm-site-styles'), null, 'all');

		// Enqueue StyleSheets
		wp_enqueue_style('fm-site-styles');
		wp_enqueue_style('fm-custom-site-styles');
	} // fm_enqueue_site_styles()
	add_action('wp_enqueue_scripts', 'fm_enqueue_site_styles');


// == Enqueue Site Scripts =================================================
	function fm_enqueue_site_scripts()
	{
		// Unregister and re-register jQuery
		// wp_deregister_script('jquery');
		// wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'
		//                    ,array(), '1.8.2', true);

		// Register site main script
		// wp_register_script('fm-site-script', get_stylesheet_directory_uri(). '/_assets/js/site.js', array('jquery'), null, true);

		// Register site coffee script
		wp_register_script('fm-coffee-script', get_stylesheet_directory_uri(). '/_assets/js/coffee/coffee.js', array('jquery'), null, true);

		// Register TimeAgo
		// wp_register_script('fm-timeago-js', get_stylesheet_directory_uri(). '/wp-content/themes/fm-child-theme/_assets/js/libs/jquery.timeago.js', null, true);

		// Register Retina.js
		// wp_register_script('fm-retina-js', get_stylesheet_directory_uri(). '/wp-content/themes/fm-child-theme/_assets/js/libs/retina.js', null, true);

		// Register Prefix-Free
		// wp_register_script('fm-prefix-free', get_stylesheet_directory_uri(). '/_assets/js/libs/prefixfree.min.js', null, false);

		// Enqueue Scripts
		// wp_enqueue_script('jquery');
		// wp_enqueue_script('fm-site-script');
		wp_enqueue_script('fm-coffee-script');
		// wp_enqueue_script('fm-retina-js');
		// wp_enqueue_script('fm-prefix-free');
		// wp_enqueue_script('fm-timeago-js');

	} // fm_enqueue_site_scripts()
	add_action('wp_enqueue_scripts', 'fm_enqueue_site_scripts');


// == Add Favicon .ico =====================================================
	function fm_add_favicon()
	{
		echo '<link rel="shortcut icon" href="'.get_stylesheet_directory_uri().'/_assets/img/icons/favicon.ico" />';
	} // fm_add_favicon()
	// Add to all front end pages
	add_action('wp_head', 'fm_add_favicon');
	// Add to login
	add_action('login_head', 'fm_add_favicon');


// == HTML 5 Shim ===========================================================
	function fm_add_ie_html5_shim () {
		global $is_IE;
		if ($is_IE)
	   	echo '<!--[if lt IE 9]>';
	    	echo '<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>';
	    	echo '<![endif]-->';
	}
	add_action('wp_head', 'fm_add_ie_html5_shim');



