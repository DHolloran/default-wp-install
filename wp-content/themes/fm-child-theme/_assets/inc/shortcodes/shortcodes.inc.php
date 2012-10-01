<?php
// :TODO Ideas get-posts, related-pages

// == BlogInfo == [fm-blog-info show="<value>"] ==============================
	// :TODO Make alias for common blog info values
	function fm_blog_info($atts) {
		// What the user wants to show
		$atts = extract(shortcode_atts(array('show'=>''),$atts));
		// Output info to screen
		return get_bloginfo($show);
	}
	add_shortcode('fm-blog-info','fm_blog_info');


// == Directory Shortcodes ===================================================
	// Site URL [fm-site-url]
	function fm_site_url($atts) {
		return site_url();
	} // fm_site_url($atts)
	add_shortcode('fm-site-url','fm_site_url');

	// Home URL [fm-home-url]
	function fm_home_url($atts) {
		return home_url();
	} // fm_home_url($atts)
	add_shortcode('fm-home-url','fm_home_url');

	// Template URL [fm-template-url]
	function fm_template_url($atts) {
		return get_template_directory_uri();
	} // fm_template_url($atts)
	add_shortcode('fm-template-url','fm_template_url');

	// Child Theme URL [fm-child-theme-url]
	function fm_child_theme_url($atts) {
		return get_stylesheet_directory_uri();
	} // fm_child_theme_url($atts)
	add_shortcode('fm-child-theme-url','fm_child_theme_url');


// == Date Shortcodes ========================================================
	// Get Date [fm-date date_string=""]
	function fm_month_name_shortcode($atts) {
		$atts = extract(shortcode_atts(array('date_string'=>''),$atts));
		$date = date($date_string);
		return $date;
	} // fm_month_name_shortcode($atts)
	add_shortcode('fm-date', 'fm_date_shortcode');

	// Get Current Day of the Week Name [fm-day-of-week]
	function fm_day_of_week_name_shortcode() {
		$day_of_week = date('l');
		return $day_of_week;
	} // fm_month_name_shortcode()
	add_shortcode('fm-day-of-week', 'fm_day_of_week_name_shortcode');

	// Get Current Date [fm-date]
	function fm_date_shortcode() {
		$date = date('j');
		return $date;
	} // fm_date_shortcode()
	add_shortcode('fm-date', 'fm_date_shortcode');

	// Get Current Month Name [fm-month-name]
	function fm_month_shortcode() {
		$month = date('F');
		return $month;
	} // fm_month_shortcode()
	add_shortcode('fm-month-name', 'fm_month_shortcode');

	// Get Current Year [fm-year]
	function fm_year_shortcode() {
		$year = date('Y');
		return $year;
	} // fm_year_shortcode()
	add_shortcode('fm-year', 'fm_year_shortcode');


// == Recent Tweets == fm-recent-tweets] =====================================
	// Shortcode to display the list of recent tweets displays 3 as default requires Twitter user name.
	// :TODO Reconfigure for O-Auth support
	// EXAMPLE: [fm-recent-tweets user="dholloran" display="4"]
	function shortcode_fm_recent_tweets($atts) {
		$defaults = array( 'user' => '', 'display' => '3');
		fm_recent_tweets( shortcode_atts( $defaults, $atts ) );
	} // shortcode_fm_recent_tweets($atts)
	add_shortcode('fm-recent-tweets','shortcode_fm_recent_tweets');


// == Google Map == [fm-google-map] ==========================================
	// Creates a Google Map shortcode for given location, zoom and type.
	// EXAMPLE: [fm-google-map center="-34.397, 150.644" zoom="12" map_type="roadmap"]
	function shortcode_google_map($atts) {
		$atts = extract(shortcode_atts(array(
			'gmaps_sensor'	=>	'false',
			'height'				=>	'350px',
			'width'					=>	'350px',
			'id'						=>	'fm_map_canvas',
			'center'				=>	'',
			'zoom'					=>	'8',
			'map_type'			=>	'ROADMAP',
			'marker'				=>	'',
			'marker_title'	=>	''),$atts));

		// Build map options array
		$options = array(
     'center'				=>	"new google.maps.LatLng($center)",
			'zoom'					=>	$zoom,
			'mapTypeId'			=>	"google.maps.MapTypeId.".strtoupper($map_type)
		);
		$marker_options = array(
		  'marker'	=>	"new google.maps.LatLng($marker)",
		  'marker_title'	=>	$marker_title
		);

		// Call Google Map initialization.
		google_map_init( $gmaps_sensor, $height , $width, $id = "fm_map_canvas", $options, $marker_options );
		} // shortcode_google_map($atts)
		add_shortcode('fm-google-map','shortcode_google_map');

// Recent Posts == [fm-recent-posts] =========================================
	function shortcode_get_fm_recent_posts($atts) {

		// Setup shortcode defaults array.
		$defaults = array(
	    'limit'						=>	'3',
			'offset'					=>	'0',
			'category'				=>	'',
			'parent'					=>	'',
			'title'						=>	'Recent Posts',
			'show_content'		=> 	'1',
			'content_length'	=>	'100',
			'img_width'				=>	'100',
			'img_height'			=>	'100'
		);

		// Extract the supplied shortcode attributes.
		$atts = shortcode_atts($defaults,$atts);

		// Call fm recent posts.
		fm_recent_posts($atts);
	} // shortcode_get_fm_recent_posts($atts)
	add_shortcode('fm-recent-posts','shortcode_get_fm_recent_posts');



