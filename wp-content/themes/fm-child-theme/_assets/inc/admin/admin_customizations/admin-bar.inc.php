<?php
// == Admin Bar =======================================================
// Add contact us email link
function dh_add_contact_admin_bar_link() {
	global $wp_admin_bar;
	if ( !is_super_admin() or !is_admin_bar_showing() ) return;
	$website_name = get_bloginfo( 'name' );
	$link = "mailto:dan@danholloran.com?subject=Question&nbsp;about&nbsp;my&nbsp;website&nbsp;-&nbsp;$website_name";
	$wp_admin_bar->add_menu( array(
			'id' => 'contact-dh-help',
			'title' => __( 'Contact FMM' ),
			'href' => $link ) );
} // dh_add_contact_admin_bar_link()

// Add link to website
function dh_add_fmhelp_admin_bar_link() {
	global $wp_admin_bar;
	if ( !is_super_admin() or !is_admin_bar_showing() ) return;
	$wp_admin_bar->add_menu( array(
			'id' => 'dh-website',
			'title' => __( 'FreeManHelp.com' ),
			'href' => __( 'http://danholloran.com' ) ) );
} // dh_add_fmhelp_admin_bar_link()

// Set admin bar to not display when viewing page by default
function dh_set_user_view_page_admin_bar_false( $user_id ) {
	update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
	update_user_meta( $user_id, 'show_admin_bar_admin', 'false' );
} // dh_set_user_view_page_admin_bar_false($user_id)

// Replace default site name with view your site link
// Removes the original menu item
function dh_remove_admin_bar_menus() {
	global $wp_admin_bar;
	// Removes the site name
	$wp_admin_bar->remove_menu( 'site-name' );
} // dh_remove_admin_bar_menus()

// Replaces with new and improved menu item
function dh_add_view_site_admin_bar_link() {
	global $wp_admin_bar;
	if ( !is_super_admin() or !is_admin_bar_showing() ) return;
	$wp_admin_bar->add_menu( array(
			'id' => 'dh-view-website',
			'title' => __( 'View Site' ),
			'href' => home_url(),
			'meta'  => array( 'target' => '_blank' ) ) );
} // dh_add_fmhelp_admin_bar_link()

// Removes Links from New+
function dh_admin_bar_new_btn() {
	global $wp_admin_bar;
	// $wp_admin_bar->remove_menu('new-content'); // This removes the complete menu “Add New”. You will not require the below “remove_menu” if you using this line.
	// $wp_admin_bar->remove_menu('new-post'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Post”.
	// $wp_admin_bar->remove_menu('new-page'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Page”.
	// $wp_admin_bar->remove_menu('new-media'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Media”.
	// $wp_admin_bar->remove_menu('new-link'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Link”.
	// $wp_admin_bar->remove_menu('new-user'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “User”.
	// $wp_admin_bar->remove_menu('new-theme'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Theme”.
	// $wp_admin_bar->remove_menu('new-plugin'); // This (when used individually with other “remove menu” lines removed) will hide the menu item “Plugin”.
}

// == Screen Options ==================================================
function dh_remove_screen_options() {
	return false;
} // dh_remove_screen_options()
