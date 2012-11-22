<?php
// == Footer ==========================================================
// Custom admin footer text
function dh_custom_admin_footer() {
	$client_name = get_bloginfo( 'name' );
	$wp_version = get_bloginfo( 'version' );
	echo "Created by <a href=\"http://danholloran.com\">Dan Holloran</a> for $client_name powered by <a href=\"http://www.wordpress.org\">WordPress $wp_version</a>";
} // dh_custom_admin_footer()
