<?php
// == Adds the site documentation page content (admin_customizations.inc.php) ===
function fm_add_documentation_content() {
	$pdf_download_link = get_stylesheet_directory_uri(). '/documentation/user/documentation.pdf';

	// Output heading
	echo '<div class="wrap">';
	echo '<div id="icon-options-general" class="icon32"><br></div><h2 id="top">Site User Guide</h2>';
	echo "<p><a href=\"$pdf_download_link\" class=\"button-primary\">Download</a></p>";
	echo '<div class="clear"></div>';
	// Require  the documentation
	require_once "documentation/user/documentation.html";
	echo '</div> <!-- END .wrap -->';
} // fm_add_documentation_content()

// == Adds the Dev documentation page content (admin_customizations.inc.php) ====
function fm_add_developer_documentation_content() {
	// Output heading
	echo '<div class="wrap">';
	echo '<div id="icon-options-general" class="icon32"><br></div><h2 id="top">Developer Documentation</h2>';
	echo '<div class="clear"></div>';

	// Require  the documentation
	require_once "documentation/developer/documentation.php";
	echo '</div> <!-- END .wrap -->';
} // fm_add_developer_documentation_content()

// == Adds the Site Options page content (admin_customizations.inc.php) =========
function fm_add_site_options_content() {
	// Output heading
	echo '<div id="fm_site_options" class="wrap">';
	echo '<div id="icon-options-general" class="icon32"><br></div><h2 id="top">Site Options</h2>';
	echo '<div class="clear"></div>';
	// Require Site Options
	require_once "_assets/inc/admin/site_options.inc.php";
	echo '</div> <!-- END .wrap -->';
} // fm_add_site_options_content()

// All PHP requires should be placed in _assets/inc/ thanks!

// General Requires
require_once "_assets/inc/functions/debug.inc.php";
require_once "_assets/inc/functions/file_loader.inc.php";
require_once "_assets/inc/functions/user_roles.inc.php";
require_once "_assets/inc/functions/custom_post_types.inc.php";
require_once "_assets/inc/functions/form_helper.inc.php";
require_once "_assets/inc/classes/class-fm-form.inc.php";
require_once "_assets/inc/functions/ajax_helper.inc.php";
require_once "_assets/inc/functions/helpers.inc.php";
require_once "_assets/inc/functions/shortcodes.inc.php";
require_once "_assets/inc/functions/project_specific.inc.php";


// Admin Requires
require_once "_assets/inc/admin/login_customizations.inc.php";
require_once "_assets/inc/admin/admin_customizations.inc.php";
