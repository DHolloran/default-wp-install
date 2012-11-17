<?php
// == BlogInfo == [fm-blog-info show="<value>"] ==============================
function fm_blog_info( $atts ) {
	// What the user wants to show
	$atts = extract( shortcode_atts( array( 'show'=>'' ), $atts ) );
	// Output info to screen
	return get_bloginfo( $show );
}
add_shortcode( 'fm-blog-info', 'fm_blog_info' );


// == Directory Shortcodes ===================================================
// Site URL [fm-site-url]
function fm_site_url( $atts ) {
	return site_url();
} // fm_site_url($atts)
add_shortcode( 'fm-site-url', 'fm_site_url' );

// Home URL [fm-home-url]
function fm_home_url( $atts ) {
	return home_url();
} // fm_home_url($atts)
add_shortcode( 'fm-home-url', 'fm_home_url' );

// Template URL [fm-template-url]
function fm_template_url( $atts ) {
	return get_template_directory_uri();
} // fm_template_url($atts)
add_shortcode( 'fm-template-url', 'fm_template_url' );

// Child Theme URL [fm-child-theme-url]
function fm_child_theme_url( $atts ) {
	return get_stylesheet_directory_uri();
} // fm_child_theme_url($atts)
add_shortcode( 'fm-child-theme-url', 'fm_child_theme_url' );


// == Date Shortcodes ========================================================
// Get Date [fm-date date_string=""]
function fm_month_name_shortcode( $atts ) {
	$atts = extract( shortcode_atts( array( 'date_string'=>'' ), $atts ) );
	$date = date( $date_string );
	return $date;
} // fm_month_name_shortcode($atts)
add_shortcode( 'fm-date', 'fm_date_shortcode' );

// Get Current Day of the Week Name [fm-day-of-week]
function fm_day_of_week_name_shortcode() {
	$day_of_week = date( 'l' );
	return $day_of_week;
} // fm_month_name_shortcode()
add_shortcode( 'fm-day-of-week', 'fm_day_of_week_name_shortcode' );

// Get Current Date [fm-date]
function fm_date_shortcode() {
	$date = date( 'j' );
	return $date;
} // fm_date_shortcode()
add_shortcode( 'fm-date', 'fm_date_shortcode' );

// Get Current Month Name [fm-month-name]
function fm_month_shortcode() {
	$month = date( 'F' );
	return $month;
} // fm_month_shortcode()
add_shortcode( 'fm-month-name', 'fm_month_shortcode' );

// Get Current Year [fm-year]
function fm_year_shortcode() {
	$year = date( 'Y' );
	return $year;
} // fm_year_shortcode()
add_shortcode( 'fm-year', 'fm_year_shortcode' );