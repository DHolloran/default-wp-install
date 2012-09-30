<?php
/*
* Freeman Marketing Theme Shortcodes
* 1. [fm_site_url]
* 2. [fm_recent_tweets]
* 3. [theme_directory]
* 4. [google_map]
*	5. [fm_recent_posts]
* 6. :TODO [get_posts]
* 7. :TODO [related_links] (use list pages)
* 8. :TODO [accordian]
*/

/* 1. [fm_site_url] ========================================================================
* Site URL shortcode so we have access to the site URL in pages/posts/widgets.
*/
	function shortcode_fm_site_url($atts)
	{
		$atts = extract(shortcode_atts(array('default'=>'values'),$atts));
		return site_url();
	} // shortcode_fm_site_url($atts)
	add_shortcode('fm_site_url','shortcode_fm_site_url');
/* 2. [fm_recent_tweets] ========================================================================
* Shortcode to display the list of recent tweets displays 3 as default requires Twitter user name.
* EXAMPLE: [fm_recent_tweets user="dholloran" display="4"]
*/
	function shortcode_fm_recent_tweets($atts) {
		$defaults = array( 'user' => '', 'display' => '3');
		fm_recent_tweets( shortcode_atts( $defaults, $atts ) );
	} // shortcode_fm_recent_tweets($atts)
	add_shortcode('fm_recent_tweets','shortcode_fm_recent_tweets');
/* 3. [theme_directory] ========================================================================
* Theme directory URL shortcode so we have access to the theme directory URL in pages/posts/widgets.
* EXAMPLE: [theme_directory]
*/
	function shortcode_fm_theme_directory($atts) {
	$atts = extract(shortcode_atts(array('default'=>'values'),$atts));
		echo get_bloginfo('stylesheet_directory');
	} // shortcode_fm_theme_directory($atts)
	add_shortcode('fm_theme_directory','shortcode_fm_theme_directory');

/* 4. [google_map] ========================================================================
* Creates a Google Map shortcode for given location, zoom and type.
* EXAMPLE: [fm_google_map center="-34.397, 150.644" zoom="12" map_type="roadmap"]
*/
	function shortcode_google_map($atts) {
	$atts = extract(
	        	shortcode_atts(
	             array( 'gmaps_sensor'	=>	'false',
											'height'				=>	'350px',
											'width'					=>	'350px',
											'id'						=>	'fm_map_canvas',
											'center'				=>	'',
											'zoom'					=>	'8',
											'map_type'			=>	'ROADMAP',
											'marker'				=>	'',
											'marker_title'	=>	'' ),$atts));
	// Build map options array
	$options = array( 'center'				=>	"new google.maps.LatLng($center)",
	                  'zoom'					=>	$zoom,
	                  'mapTypeId'			=>	"google.maps.MapTypeId.".strtoupper($map_type) );
	$marker_options = array( 'marker'				=>	 "new google.maps.LatLng($marker)",
					                 'marker_title'	=>	$marker_title );
	// Call Google Map initialization.
	google_map_init( $gmaps_sensor, $height , $width, $id = "fm_map_canvas", $options, $marker_options );
	} // shortcode_google_map($atts)
	add_shortcode('fm_google_map','shortcode_google_map');
/* 5. [fm_recent_posts] ========================================================================
*
* EXAMPLE:
*/
	function shortcode_get_fm_recent_posts($atts) {

		// Setup shortcode defaults array.
		$defaults = array('limit'						=>	'3',
		                  'offset'					=>	'0',
		                  'category'				=>	'',
		                  'parent'					=>	'',
		                  'title'						=>	'Recent Posts',
		                  'show_content'		=> 	'1',
		                  'content_length'	=>	'100',
		                  'img_width'				=>	'100',
		                  'img_height'			=>	'100');

		// Extract the supplied shortcode attributes.
		$atts = shortcode_atts($defaults,$atts);

		// Call fm recent posts.
		fm_recent_posts($atts);
	} // shortcode_get_fm_recent_posts($atts)
	add_shortcode('fm_recent_posts','shortcode_get_fm_recent_posts');

/* 6. [get_posts] ========================================================================
*
* EXAMPLE:
*/

/* 7. [related_links] (use list pages) ========================================================================
*
* EXAMPLE:
*/

/* 8. [accordian] ========================================================================
*
* EXAMPLE:
*/
