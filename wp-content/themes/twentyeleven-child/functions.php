<?php
// == Add Breadcrumbs To Pages/Posts =======================================
	function the_breadcrumb() {
		if (!is_home()) {
			echo '<a href="'.get_option('home').'">';
			bloginfo('name');
			echo "</a> » ";
			if (is_category() || is_single()) {
				the_category('title_li=');
				if (is_single()) {
				echo " » ";
				the_title();
				} // if()
			} elseif (is_page()) {
				echo the_title();
			} // if/elseif()/
		} // if (!is_home())
	} // the_breadcrumb()
// == Enqueue Site Styles ==================================================
	function fm_enqueue_site_styles()
	{
		// Register main site stylesheet
		wp_register_style('fm-site-styles', get_stylesheet_directory_uri(). '/_assets/css/site.css',
		 									array(), '1.0', 'all');

		// Register custom site stylesheet
		wp_register_style('fm-custom-site-styles', get_stylesheet_directory_uri(). '/_assets/css/custom.css',
		 									array('fm-site-styles'), '1.0', 'all');

		// Enqueue StyleSheets
		wp_enqueue_style('fm-site-styles');
		wp_enqueue_style('fm-custom-site-styles');
	} // fm_enqueue_site_styles()
	add_action('wp_enqueue_scripts', 'fm_enqueue_site_styles');
// == Enqueue Site Scripts =================================================
	function fm_enqueue_site_scripts()
	{
		// Unregister and re-register jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'
		                   ,array(), '1.8.2', TRUE);

		// Register site main script
		wp_register_script('fm-site-script', get_stylesheet_directory_uri(). '/_assets/js/site.js',
											array('jquery'), '1.0', TRUE);

		// Enqueue Scripts
		wp_enqueue_script('jquery');
		wp_enqueue_script('fm-site-script');
	} // fm_enqueue_site_scripts()
	add_action('wp_enqueue_scripts', 'fm_enqueue_site_scripts');
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
// == Add Favicon .ico =====================================================
	function fm_add_favicon()
	{
		echo '<link rel="shortcut icon" href="'.get_stylesheet_directory_uri().'/_assets/img/icons/favicon.ico" />';
	} // fm_add_favicon()
	add_action('wp_head', 'fm_add_favicon');
// == Create Developer User Role ===========================================
	function fm_create_developer_user_role()
	{
		if (!get_option('fmm_developer_created')) {
			// Adds a new FreeMan Marketing Developer with the capabilities of an Administrator.
			$result = add_role('fmm_developer', 'FreeMan Marketing Developer', $capabilities = array(
	      'activate_plugins'	=>	true,
	      'create_users'	=>	true,
	      'delete_others_pages'	=>	true,
	      'delete_others_posts'	=>	true,
	      'delete_pages'	=>	true,
	      'delete_plugins'	=>	true,
	      'delete_posts'	=>	true,
	      'delete_private_pages'	=>	true,
	      'delete_private_posts'	=>	true,
	      'delete_published_pages'	=>	true,
	      'delete_published_posts'	=>	true,
	      'delete_users'	=>	true,
	      'edit_dashboard'	=>	true,
	      'edit_files'	=>	true,
	      'edit_others_pages'	=>	true,
	      'edit_others_posts'	=>	true,
	      'edit_pages'	=>	true,
	      'edit_posts'	=>	true,
	      'edit_private_pages'	=>	true,
	      'edit_private_posts'	=>	true,
	      'edit_published_pages'	=>	true,
	      'edit_published_posts'	=>	true,
	      'edit_theme_options'	=>	true,
	      'export'	=>	true,
	      'import'	=>	true,
	      'list_users'	=>	true,
	      'manage_categories'	=>	true,
	      'manage_links'	=>	true,
	      'manage_options'	=>	true,
	      'moderate_comments'	=>	true,
	      'promote_users'	=>	true,
	      'publish_pages'	=>	true,
	      'publish_posts'	=>	true,
	      'read_private_pages'	=>	true,
	      'read_private_posts'	=>	true,
	      'read'	=>	true,
	      'remove_users'	=>	true,
	      'switch_themes'	=>	true,
	      'unfiltered_upload'	=>	true,
	      'upload_files' 	=>	true,
	      'update_core'	=>	true,
	      'update_plugins'	=>	true,
	      'update_themes'	=>	true,
	      'install_plugins'	=>	true,
	      'install_themes'	=>	true,
	      'delete_themes'	=>	true,
	      'edit_plugins'	=>	true,
	      'edit_themes'	=>	true,
	      'edit_users'	=>	true,
	      'unfiltered_html' 	=>	true));
			if ($result != null) {
				update_option('fmm_developer_created', true);
			} // if($result != null)
		} // if(!get_option('fmm_developer_created'))
	} // fm_create_developer_user_role()

	// Execute create FreeMan Marketing developer user role
	fm_create_developer_user_role();

// All PHP requires should be placed in _assets/inc/ thanks.
require_once "_assets/inc/debug.inc.php";
require_once "_assets/inc/admin/custom_post_types.inc.php";
require_once "_assets/inc/admin/login_customizations.inc.php";
require_once "_assets/inc/admin/admin_customizations.inc.php";

