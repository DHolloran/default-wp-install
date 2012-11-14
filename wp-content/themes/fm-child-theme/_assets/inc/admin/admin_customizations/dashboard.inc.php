<?php
// == Dashboard =======================================================
// Add The FreeMan Marketing Company Info Widget
function fm_add_company_info() {
	$doc_link = admin_url( 'admin.php?page=fm-site-documentation', 'http' );
	$website_name = get_bloginfo( 'name' );
?>
				<p>This site is proudly brought to you by,</p>
				<h1>FreeMan Marketing</h1>
				<p>If you have a question about how to use this site please consult the <a href="<?php echo $doc_link; ?>" title="Documentation">documentation</a>.</p>
				<p>If you need any further help do not hesitate to contact us by phone at (111)-111-1111 or email at <a href="mailto:info@freemanhelp.com?subject=Question%20about%20my%20website - <?php echo $website_name; ?>">info@freemanhelp.com</a>
		<?php
} // fm_add_company_info()

// Add the FreeMan Marketing Company Information
function fm_add_custom_widgets() {
	wp_add_dashboard_widget( 'fm-company-info', 'Thank You!', 'fm_add_company_info' );
}//fm_add_custom_widgets()

// Remove Default Dashboard Widgets
function fm_remove_dashboard_widgets() {
	// == Middle Column ==
	// Removes the incoming links dashboard widget
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	// Removes the right now dashboard widget
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	// Removes the recent comments dashboard widget
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	// Removes the plugins dashboard widget
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	// Removes the not a current browser dashboard widget. Why would you ever want to not use a current browser????? Please please do not remove this one!!!!!!
	// remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );

	// == Side Column ==
	// Removes the quick press dashboard widget
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	// Removes the recent drafts links dashboard widget
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	// Removes the wordpress blog dashboard widget
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	// Removes the other WordPress news dashboard widget
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
} // fm_remove_dashboard_widgets()
