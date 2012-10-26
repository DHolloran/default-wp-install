<?php
// == Login ===========================================================
// == Change Login Logo ===============================================
	function cb_login_logo()
	{
    $style = '<style type="text/css">';
    $style .= 'body.login div#login h1 a {';
    $style .= 'background-image: url("' . get_stylesheet_directory_uri();
    $style .= '/_assets/img/admin/login_logo.png");';
    $style .= 'padding-bottom: 30px;} </style>';
	} // cb_login_logo()
	add_action( 'login_enqueue_scripts', 'cb_login_logo' );
// == Logo Link To Home ===============================================
	function cb_login_logo_url()
	{
    return get_bloginfo('url');
	} // cb_login_logo_url()
	add_filter( 'login_headerurl', 'cb_login_logo_url' );
// == Logo Title Attribute ============================================
	function cb_login_logo_url_title()
	{
	    return get_bloginfo('name');
	} // cb_login_logo_url_title()
	add_filter( 'login_headertitle', 'cb_login_logo_url_title' );
// == Add Login StyleSheet ============================================
	function cb_add_login_stylesheet()
	{
		wp_register_style('fm-login-styles', get_stylesheet_directory_uri() . '/_assets/css/login.css');
		wp_enqueue_style('fm-login-styles');
	} // cb_add_login_stylesheet()
	add_action( 'login_enqueue_scripts', 'cb_add_login_stylesheet' );

