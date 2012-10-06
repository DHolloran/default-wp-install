<?php
/*
Plugin Name: Newborns Form
Plugin URI: http://freemanhelp.com
Description: Form for newborns post
Version: 1.0
Author: Dan Holloran
Author URI: http://freemanhelp.com
*/

// Enqueue Scripts
function fm_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('fm-newborns-form-script',  plugins_url(). '/newborns-form/_assets/scripts/plugin.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('fm-newborns-form-script');
} // fm_admin_scripts()

// Enqueue Styles
function fm_admin_styles() {
	wp_enqueue_style('thickbox');
	wp_register_style('fm-newborns-form-styles', plugins_url(). '/newborns-form/_assets/scripts/plugin.css');
} // fm_admin_styles()

// Add Scripts and Styles
if (isset($_GET['page']) && $_GET['page'] == 'newborns-template') {
	add_action('admin_print_scripts', 'fm_admin_scripts');
	add_action('admin_print_styles', 'fm_admin_styles');
} // if()

// Add menu page under posts
function fm_add_post_menu_page()
{
	add_posts_page( 'Newborns Template', 'Newborns', 'publish_posts', 'newborns-template', 'fm_add_menu_page_form');
} // fm_add_post_menu_page()
add_action('admin_menu', 'fm_add_post_menu_page');

function fm_add_menu_page_form()
{
	require_once "_assets/inc/form.inc.php";
} // fm_add_menu_page_form()



