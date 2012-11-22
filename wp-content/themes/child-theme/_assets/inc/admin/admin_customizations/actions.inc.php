<?php
// == All Add Actions Below ===========================================
if ( is_admin() ) {
	// == FMM Developers Only ==============
	if ( user_is_admin() )
		add_action( 'admin_menu', 'dh_add_developer_documentation' ); // Adds a developer documentation menu page

	// == Administrator or FMM Developer ==============
	if ( user_is_admin() or user_is_admin() ) {
		// Replaces site link with a view your site link
		add_action( 'wp_before_admin_bar_render', 'dh_remove_admin_bar_menus' ); // Remove the original site name
		add_action( 'admin_bar_menu', 'dh_add_view_site_admin_bar_link', 25 ); // Adds a new link that will open in a new window
		add_action( 'admin_menu', 'dh_add_site_options' ); // Adds a site options menu page
		// add_action('admin_bar_menu', 'dh_add_fmhelp_admin_bar_link',25); // Adds website link to admin bar
	} // if(user_is_admin() or user_is_admin())

	// == All users except FMM Developer =============
	if ( !user_is_admin() ) {
		// add_filter('screen_options_show_screen', 'dh_remove_screen_options'); // Removes Screen Options
		add_action( 'admin_menu', 'dh_remove_admin_menu_pages' ); // Removes admin menu pages
		add_action( 'wp_dashboard_setup', 'dh_remove_dashboard_widgets' ); // Removes default widgets
		add_action( 'add_meta_boxes', 'dh_remove_posts_meta_boxes' ); // Remove posts meta boxes
		add_action( 'add_meta_boxes', 'dh_remove_pages_meta_boxes' ); // Remove pages meta boxes
		add_action( 'widgets_init', 'dh_remove_default_widgets', 1 ); // Removes default widgets
	} // if(!user_is_admin())

	// == All users =============================================
	// Add Meta Box
	// add_action( 'add_meta_boxes', 'dh_add_meta_boxes' );

	// Remove un-needed items from admin bar NEW+
	add_action( 'wp_before_admin_bar_render', 'dh_admin_bar_new_btn' );

	// Remove meta boxes
	add_action( 'admin_menu', 'dh_remove_meta_boxes' );

	// Adds admin stylesheet
	add_action( 'admin_init', 'dh_add_admin_stylesheet' );

	// Sets admin bar to not display when viewing page by default
	add_action( "user_register", "dh_set_user_view_page_admin_bar_false", 10, 1 );

	// Adds custom admin footer text
	add_filter( 'admin_footer_text', 'dh_custom_admin_footer' );

	// Makes Tiny MCE awesome
	add_filter( 'tiny_mce_before_init', 'dh_make_mce_awesome' );

	// Tiny MCE buttons 2
	add_filter( 'mce_buttons_2', 'dh_mce_buttons_2' );

	// Adds styles drop down menu
	add_filter( 'tiny_mce_before_init', 'dh_mce_before_init' );

	// Adds a favicon for admin area
	add_action( 'admin_head', 'dh_add_favicon' );

	// Remove registered sidebars
	add_action( 'widgets_init', 'dh_unregister_sidebars', 11 );


} // if(is_admin())
