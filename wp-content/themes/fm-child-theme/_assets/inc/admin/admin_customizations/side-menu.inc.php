<?php
// == Side Menu =======================================================
	// Remove Admin Menu Items
		function fm_remove_admin_menu_pages()
		{
			// Use this to get the values to remove top menu pages
			// global $menu;
			// pp($menu);

			// Use this to get the values to remove sub menu pages
			// global $submenu;
			// pp($submenu);

			// Removes Posts menu page
			// remove_menu_page('edit.php');

			// Remove Media menu page
			//remove_menu_page('upload.php');

			// Remove Link menu page
			// remove_menu_page('link-manager.php');

			// Removes Comments menu page
			// remove_menu_page('edit-comments.php');

			// Removes Plugins menu page
			// remove_menu_page('plugins.php');

			// Removes the Appearance > Theme sub-menu page
			// remove_submenu_page('themes.php', 'themes.php');

			// Removes the Tools > Available Tools sub-menu page
			// remove_submenu_page('tools.php', 'tools.php');

			// Removes the Tools > Import sub-menu page
			// remove_submenu_page('tools.php', 'import.php');

			// Removes the Settings > Permalinks sub-menu page
			// remove_submenu_page('options-general.php', 'options-permalink.php');

		} // fm_remove_admin_menu_pages()

	// Add Site Documentation Menu Page
		function fm_add_site_documentation()
		{
			add_menu_page(
				'Site User Guide',
				'User Guide',
				'manage_options',
				'fm-site-documentation',
				'fm_add_documentation_content');
		} // fm_add_site_documentation()

	// Add Developer Documentation Menu Page
		function fm_add_developer_documentation()
		{
			add_menu_page( 'Developer Documentation',
				'Developers',
				'manage_options',
				'developer-documentation',
				'fm_add_developer_documentation_content');
		} // fm_add_developer_documentation()

	// Add Site Options Menu Page
		function fm_add_site_options()
		{
			add_menu_page( 'Site Options',
				'Site Options',
				'manage_options',
				'site-options',
				'fm_add_site_options_content');
		} // fm_add_site_options()