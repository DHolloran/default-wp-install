<?php
// == Login ===========================================================
// == Change Login Logo ===============================================
	function fm_login_logo()
	{
    $style = '<style type="text/css">';
    $style .= 'body.login div#login h1 a {';
    $style .= 'background-image: url("' . get_stylesheet_directory_uri();
    $style .= '/_assets/img/admin/login_logo.png");';
    $style .= 'padding-bottom: 30px;} </style>';
	} // fm_login_logo()
	add_action( 'login_enqueue_scripts', 'fm_login_logo' );
// == Logo Link To Home ===============================================
	function fm_login_logo_url()
	{
    return get_bloginfo('url');
	} // fm_login_logo_url()
	add_filter( 'login_headerurl', 'fm_login_logo_url' );
// == Logo Title Attribute ============================================
	function fm_login_logo_url_title()
	{
	    return get_bloginfo('name');
	} // fm_login_logo_url_title()
	add_filter( 'login_headertitle', 'fm_login_logo_url_title' );
// == Add Login StyleSheet ============================================
	function fm_add_login_stylesheet()
	{
		wp_register_style('fm-login-styles', get_stylesheet_directory_uri() . '/_assets/css/login.css');
		wp_enqueue_style('fm-login-styles');
	} // fm_add_login_stylesheet()
	add_action( 'login_enqueue_scripts', 'fm_add_login_stylesheet' );

