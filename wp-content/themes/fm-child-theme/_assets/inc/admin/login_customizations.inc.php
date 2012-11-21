<?php
// == Login ===========================================================
// == Change Login Logo ===============================================
function dh_login_logo() {
	$style = '<style type="text/css">';
	$style .= 'body.login div#login h1 a {';
	$style .= 'background-image: url("' . get_stylesheet_directory_uri();
	$style .= '/_assets/img/admin/login_logo.png");';
	$style .= 'padding-bottom: 30px;} </style>';
} // dh_login_logo()
add_action( 'login_enqueue_scripts', 'dh_login_logo' );
// == Logo Link To Home ===============================================
function dh_login_logo_url() {
	return get_bloginfo( 'url' );
} // dh_login_logo_url()
add_filter( 'login_headerurl', 'dh_login_logo_url' );
// == Logo Title Attribute ============================================
function dh_login_logo_url_title() {
	return get_bloginfo( 'name' );
} // dh_login_logo_url_title()
add_filter( 'login_headertitle', 'dh_login_logo_url_title' );
// == Add Login StyleSheet ============================================
function dh_add_login_stylesheet() {
	wp_register_style( 'dh-login-styles', get_stylesheet_directory_uri() . '/_assets/css/login.css' );
	wp_enqueue_style( 'dh-login-styles' );
} // dh_add_login_stylesheet()
add_action( 'login_enqueue_scripts', 'dh_add_login_stylesheet' );
