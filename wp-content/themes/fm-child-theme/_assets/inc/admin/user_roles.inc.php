<?php
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
// == Get Current Users Role ==========================================
	function get_current_user_role()
	{
		// Get the current users role
		global $current_user, $wpdb;
		get_currentuserinfo();
		$role = $wpdb->prefix . 'capabilities';
		$current_user->role = array_keys($current_user->$role);
		$role = $current_user->role[0];

		return $role;
	} // get_current_user_role()
// == Check FMM Developer Role ========================================
	function user_is_fmm_developer()
	{
		$role = get_current_user_role();

		// Check if the current user is a FreeMan Marketing Developer
		if ($role == 'fmm_developer') {
			return true;
		} else {
			return false;
		} // if()
	} // user_is_fmm_developer()
// == Check Admin Role ================================================
	function user_is_admin()
	{
		$role = get_current_user_role();
		// Check if the current user is a FreeMan Marketing Developer
		if ($role == 'administrator') {
			return true;
		} else {
			return false;
		} // if()
	} // user_is_admin()
// == Check Editor Role ===============================================
	function user_is_editor()
	{
		$role = get_current_user_role();
		// Check if the current user is a FreeMan Marketing Developer
		if ($role == 'editor') {
			return true;
		} else {
			return false;
		} // if()
	} // user_is_editor()
// == Check Author Role ===============================================
	function user_is_author()
	{
		$role = get_current_user_role();
		// Check if the current user is a FreeMan Marketing Developer
		if ($role == 'author') {
			return true;
		} else {
			return false;
		} // if()
	} // user_is_author()
// == Check Contributor Role ===============================================
	function user_is_contributor()
	{
		$role = get_current_user_role();
		// Check if the current user is a FreeMan Marketing Developer
		if ($role == 'contributor') {
			return true;
		} else {
			return false;
		} // if()
	} // user_is_contributor()
// == Check Subscriber Role ===============================================
	function user_is_subscriber()
	{
		$role = get_current_user_role();
		// Check if the current user is a FreeMan Marketing Developer
		if ($role == 'subscriber') {
			return true;
		} else {
			return false;
		} // if()
	} // user_is_subscriber()


