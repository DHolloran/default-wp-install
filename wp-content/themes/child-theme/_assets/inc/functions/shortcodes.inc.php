<?php
// == BlogInfo == [dh-blog-info show="<value>"] ==============================
function dh_blog_info( $atts ) {
	// What the user wants to show
	$atts = extract( shortcode_atts( array( 'show'=>'' ), $atts ) );
	// Output info to screen
	return get_bloginfo( $show );
}
add_shortcode( 'dh-blog-info', 'dh_blog_info' );


// == Directory Shortcodes ===================================================
// Site URL [dh-site-url]
function dh_site_url( $atts ) {
	return site_url();
} // dh_site_url($atts)
add_shortcode( 'dh-site-url', 'dh_site_url' );

// Home URL [dh-home-url]
function dh_home_url( $atts ) {
	return home_url();
} // dh_home_url($atts)
add_shortcode( 'dh-home-url', 'dh_home_url' );

// Template URL [dh-template-url]
function dh_template_url( $atts ) {
	return get_template_directory_uri();
} // dh_template_url($atts)
add_shortcode( 'dh-template-url', 'dh_template_url' );

// Child Theme URL [dh-child-theme-url]
function dh_child_theme_url( $atts ) {
	return get_stylesheet_directory_uri();
} // dh_child_theme_url($atts)
add_shortcode( 'dh-child-theme-url', 'dh_child_theme_url' );


// == Date Shortcodes ========================================================
// Get Date [dh-date date_string=""]
function dh_month_name_shortcode( $atts ) {
	$atts = extract( shortcode_atts( array( 'date_string'=>'' ), $atts ) );
	$date = date( $date_string );
	return $date;
} // dh_month_name_shortcode($atts)
add_shortcode( 'dh-date', 'dh_date_shortcode' );

// Get Current Day of the Week Name [dh-day-of-week]
function dh_day_of_week_name_shortcode() {
	$day_of_week = date( 'l' );
	return $day_of_week;
} // dh_month_name_shortcode()
add_shortcode( 'dh-day-of-week', 'dh_day_of_week_name_shortcode' );

// Get Current Date [dh-date]
function dh_date_shortcode() {
	$date = date( 'j' );
	return $date;
} // dh_date_shortcode()
add_shortcode( 'dh-date', 'dh_date_shortcode' );

// Get Current Month Name [dh-month-name]
function dh_month_shortcode() {
	$month = date( 'F' );
	return $month;
} // dh_month_shortcode()
add_shortcode( 'dh-month-name', 'dh_month_shortcode' );

// Get Current Year [dh-year]
function dh_year_shortcode() {
	$year = date( 'Y' );
	return $year;
} // dh_year_shortcode()
add_shortcode( 'dh-year', 'dh_year_shortcode' );