<?php
// == Adds the documentation page content (admin_customizations.inc.php) ===
		function fm_add_documentation_content()
		{
			// :TODO Add a PDF button/icon
			$pdf_download_link = get_stylesheet_directory_uri(). '/documentation/documentation.pdf';

			// Output heading
		  echo '<div class="wrap">';
		  echo '<div id="icon-options-general" class="icon32"><br></div><h2 id="top">Site Documentation</h2>';
		  echo "<p><a href=\"$pdf_download_link\" class=\"button-primary\">Download PDF</a></p>";
		  echo '<div class="clear"></div>';
		  // Require  the documentation
		  require_once "documentation/documentation.php";

		  echo '</div> <!-- END .wrap -->';
		} // fm_add_documentation_content()


// All PHP requires should be placed in _assets/inc/ thanks!

// Functions Folder
require_once "_assets/inc/functions/file_loader.inc.php";
require_once "_assets/inc/functions/ajax_helper.inc.php";
require_once "_assets/inc/functions/debug.inc.php";
require_once "_assets/inc/functions/helpers.inc.php";
require_once "_assets/inc/functions/form_helper.inc.php";
require_once "_assets/inc/shortcodes/shortcodes.inc.php";
require_once "_assets/inc/functions/project_specific.inc.php";

// Admin Folder
require_once "_assets/inc/admin/user_roles.inc.php";
require_once "_assets/inc/admin/custom_post_types.inc.php";
require_once "_assets/inc/admin/login_customizations.inc.php";
require_once "_assets/inc/admin/admin_customizations.inc.php";



