<?php
// == Enqueue Site Styles ==================================================
function dh_enqueue_site_styles() {
	// Register main site stylesheet
	wp_register_style( 'dh-site-styles', get_stylesheet_directory_uri(). '/_assets/css/site.css', array(), null, 'all' );

	// Register custom site stylesheet
	// wp_register_style('dh-custom-site-styles', get_stylesheet_directory_uri(). '/_assets/css/custom.css', array('dh-site-styles'), null, 'all');

	// Enqueue StyleSheets
	wp_enqueue_style( 'dh-site-styles' );
	// wp_enqueue_style('dh-custom-site-styles');
} // dh_enqueue_site_styles()
add_action( 'wp_enqueue_scripts', 'dh_enqueue_site_styles' );


// == Enqueue Site Scripts =================================================
function dh_enqueue_site_scripts() {

	if (!is_admin()) {
    wp_deregister_script('jquery'); // Deregister WordPress jQuery
    wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', array(), '1.8.2'); // Load Google CDN jQuery
    wp_enqueue_script('jquery'); // Enqueue it!
  }

	// Register site main script
	// wp_register_script('dh-site-script', get_stylesheet_directory_uri(). '/_assets/js/site.js', array('jquery'), null, true);

	// Register Modernizer
	wp_register_script( 'dh-modernizer', get_stylesheet_directory_uri(). '/_assets/js/libs/modernizr-custom.js', array('jquery') );

	// Register Webshim
	wp_register_script( 'dh-webshim', get_stylesheet_directory_uri(). '/_assets/js/libs/webshim/polyfiller.js', array('jquery', 'dh-modernizer') );

	// Register site coffee script
	wp_register_script( 'dh-coffee-script', get_stylesheet_directory_uri(). '/_assets/js/coffee/coffee.js', array( 'jquery' ), null, true );

	// Register Pikaday
	// $('.datepicker').pikaday({ firstDay: 1 });
	// Uncomment/Compile pikaday.less in site.less
	// wp_register_script('dh-pikaday-js',get_stylesheet_directory_uri(). '/_assets/js/libs/pikaday.jquery.min.js', array('jquery'), true);

	// Register TimeAgo
	/* http://timeago.yarp.com/
			title="ISO 8601 time stamp"
			<abbr class="timeago" title="2008-07-17T09:24:17Z">July 17, 2008</abbr>
			jQuery("abbr.timeago").timeago();  */
	// wp_register_script('dh-timeago-js', get_stylesheet_directory_uri(). '/wp-content/themes/dh-child-theme/_assets/js/libs/jquery.timeago.js', array('jquery'), true);

	// Register Retina.js
	// Uncomment/Compile retina.less in site.less
	// wp_register_script('dh-retina-js', get_stylesheet_directory_uri(). '/wp-content/themes/dh-child-theme/_assets/js/libs/retina.js', null, true);

	// Register Prefix-Free
	// wp_register_script('dh-prefix-free', get_stylesheet_directory_uri(). '/_assets/js/libs/prefixfree.min.js', null, false);

	// Enqueue Scripts
	// wp_enqueue_script('jquery');
	// wp_enqueue_script('dh-site-script');
	wp_enqueue_script('dh-modernizer');
	wp_enqueue_script('dh-webshim');
	wp_enqueue_script( 'dh-coffee-script' );
	// wp_enqueue_script('dh-retina-js');
	// wp_enqueue_script('dh-prefix-free');
	// wp_enqueue_script('dh-timeago-js');
	// wp_enqueue_script( 'dh-pikaday-js' );

} // dh_enqueue_site_scripts()
add_action( 'wp_enqueue_scripts', 'dh_enqueue_site_scripts' );


// == jQuery Fallback ================================
function dh_add_jquery_fallback()
{
    $jqueryfallback = "<!-- Protocol Relative jQuery fall back if Google CDN offline -->";
    $jqueryfallback .= "<script>";
    $jqueryfallback .= "window.jQuery || document.write('<script src=\"" . get_template_directory_uri() . "/js/jquery-1.8.2.min.js\"><\/script>')";
    $jqueryfallback .= "</script>";
    echo $jqueryfallback;
}
add_action('wp_footer', 'dh_add_jquery_fallback'); // jQuery fallbacks loaded through footer

// == Add Favicon .ico ===============================
function dh_add_favicon() {
	echo '<link rel="shortcut icon" href="'.get_stylesheet_directory_uri().'/_assets/img/icons/favicon.ico" />';
} // dh_add_favicon()
// Add to all front end pages
add_action( 'wp_head', 'dh_add_favicon' );
// Add to login
add_action( 'login_head', 'dh_add_favicon' );


// == HTML 5 Shim ===========================================================
function dh_add_ie_html5_shim() {
	global $is_IE;
	if ( $is_IE )
		echo '<!--[if lt IE 9]>';
	echo '<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>';
	echo '<![endif]-->';
}
add_action( 'wp_head', 'dh_add_ie_html5_shim' );


// == jQuery in Footer ======================================================
function dh_print_jquery_in_footer( $scripts ) {
	if ( !is_admin() ) $scripts->add_data( 'jquery', 'group', 1 );
} // dh_print_jquery_in_footer(&$scripts)
// add_action( 'wp_default_scripts', 'dh_print_jquery_in_footer' );


// == Admin StyleSheet ================================================
function dh_add_admin_stylesheet() {
	wp_register_style( 'dh-admin-styles', get_stylesheet_directory_uri() . '/_assets/css/admin.css' );
	wp_enqueue_style( 'dh-admin-styles' );
} // dh_add_admin_stylesheet()
