<?php
// == Pretty Print Debug ==============================================
	function pp($value)
	{
		echo "<pre><code>";
		if ($value) {
			print_r($value);
		} else {
			var_dump($value);
		} // if/else()
		echo "</code></pre>";
	} // pp()
// == General =========================================================
	// Adds Breadcrumbs To Pages And Posts
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

	// Enqueue Site Styles
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

	// Enqueue Site Scripts
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
// == Footer ==========================================================
// == Login ===========================================================
	// Change Login Logo
		function fm_login_logo()
		{
	    $style = '<style type="text/css">';
	    $style .= 'body.login div#login h1 a {';
	    $style .= 'background-image: url("' . get_stylesheet_directory_uri();
	    $style .= '/_assets/img/admin/login_logo.png");';
	    $style .= 'padding-bottom: 30px;} </style>';
		} // fm_login_logo()
		add_action( 'login_enqueue_scripts', 'fm_login_logo' );

	// Logo Link To Home
		function fm_login_logo_url()
		{
	    return get_bloginfo('url');
		} // fm_login_logo_url()
		add_filter( 'login_headerurl', 'fm_login_logo_url' );

	// Logo Title Attribute
		function fm_login_logo_url_title()
		{
		    return get_bloginfo('name');
		} // fm_login_logo_url_title()
		add_filter( 'login_headertitle', 'fm_login_logo_url_title' );

	// Add Login StyleSheet
		function fm_add_login_stylesheet()
		{
			wp_register_style('fm-login-styles', get_stylesheet_directory_uri() . '/_assets/css/login.css');
			wp_enqueue_style('fm-login-styles');
		} // fm_add_login_stylesheet()
		add_action( 'login_enqueue_scripts', 'fm_add_login_stylesheet' );
// == Admin ===========================================================
	// :TODO Extend more admin customization functionality
	// == Admin StyleSheet ==
		function fm_add_admin_stylesheet()
		{
			$css_url =  get_stylesheet_directory_uri() . '/_assets/css/admin.css';
			wp_register_style('fm-admin-styles', $css_url);
			wp_enqueue_style('fm-admin-styles');
		} // fm_add_admin_stylesheet()

	// == Admin Bar ==
		// Add contact us email link
			function fm_add_contact_admin_bar_link()
			{
				global $wp_admin_bar;
				if (!is_super_admin() OR !is_admin_bar_showing()) return;
				$website_name = get_bloginfo('name');
				$link = "mailto:info@freemanhelp.com?subject=Question&nbsp;about&nbsp;my&nbsp;website&nbsp;-&nbsp;$website_name";
				$wp_admin_bar->add_menu( array(
					'id' => 'contact-fm-help',
					'title' => __('Contact FMM'),
					'href' => $link));
			} // fm_add_contact_admin_bar_link()

		// Add link to website
			function fm_add_fmhelp_admin_bar_link()
			{
				global $wp_admin_bar;
				if (!is_super_admin() OR !is_admin_bar_showing()) return;
				$wp_admin_bar->add_menu( array(
					'id' => 'fm-website',
					'title' => __('FreeManHelp.com'),
					'href' => __('http://www.freemanhelp.com')));
			} // fm_add_fmhelp_admin_bar_link()

		// Set admin bar to not display when viewing page by default
			function fm_set_user_view_page_admin_bar_false($user_id)
			{
			  update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
			  update_user_meta( $user_id, 'show_admin_bar_admin', 'false' );
			} // fm_set_user_view_page_admin_bar_false($user_id)

		// Replace default site name with view your site link
			// Removes the original menu item
			function fm_remove_admin_bar_menus()
			{
		    global $wp_admin_bar;
		    // Removes the site name
		    $wp_admin_bar->remove_menu('site-name');
			} // fm_remove_admin_bar_menus()

			// Replaces with new and improved menu item
			function fm_add_view_site_admin_bar_link()
			{
				global $wp_admin_bar;
				if ( !is_super_admin() OR !is_admin_bar_showing()) return;
				$wp_admin_bar->add_menu( array(
				'id' => 'fm-view-website',
				'title' => __( 'View your site'),
				'href' => home_url(),
				'meta'  => array( target => '_blank')));
			} // fm_add_fmhelp_admin_bar_link()

	// == Side Menu ==
		// :TODO Make it to where everything can be visible to developers only.
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

		// Add Documentation Menu Page
			// Adds the documentation page content
			function fm_add_documentation_content()
			{
				// :TODO Add a PDF button/icon
				$pdf_download_link = get_stylesheet_directory_uri(). '/documentation/documentation.pdf';
			  echo '<div class="wrap">';
			  echo '<div id="icon-options-general" class="icon32"><br></div><h2>Site Documentation</h2>';
			  echo '<p>Include all site documentation here</p>';
			  echo "<p><a href=\"$pdf_download_link\">Download PDF</a></p>";
			  // :TODO Fix require path just fails possibly use an iFrame as last resort.
			  // require_once "path/to/documentation.inc.php";

			  echo '</div> <!-- END .wrap -->';
			} // fm_add_documentation_content()

			// Adds the documentation page
			function fm_add_documentation()
			{
			  add_menu_page(
				  'Site Documentation',
				  'Documentation',
				  'manage_options',
				  'fm-site-documentation',
				  'fm_add_documentation_content');
			} // fm_add_documentation()

	// == Dashboard ==
		// Add The FreeMan Marketing Company Info Widget
			function fm_add_company_info()
			{
				$doc_link = admin_url('admin.php?page=fm-site-documentation', 'http');
				$website_name = get_bloginfo('name');
				?>
				<hgroup>
					<p>This site is proudly brought to you by,</p>
					<h1>FreeMan Marketing</h1>
					<p>If you have a question about how to use this site please consult the <a href="<?php echo $doc_link; ?>" title="Documentation">documentation</a>.</p>
					<p>If you need any further help do not hesitate to contact us by phone at (111)-111-1111 or email at <a href="mailto:info@freemanhelp.com?subject=Question about my website - <?php echo $website_name; ?>">info@freemanhelp.com</a>
				<hgroup>
			<?php
			} // fm_add_company_info()

		// Add the FreeMan Marketing Company Information
			function fm_add_company_info_widget()
			{
				wp_add_dashboard_widget('fm-company-info', 'Thank You!', 'fm_add_company_info');
			}//fm_add_company_info_widget()

		// Remove Default Dashboard Widgets
			function fm_remove_dashboard_widgets()
			{
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

	// == Widgets ==

		// Remove Default Widgets
	    function fm_remove_default_widgets()
	    {
	    	// Removes the default WordPress pages widget
		    unregister_widget('WP_Widget_Pages');

		    // Removes the default WordPress calendar widget
		    unregister_widget('WP_Widget_Calendar');

		    // Removes the default WordPress archives widget
		    unregister_widget('WP_Widget_Archives');

		    // Removes the default WordPress links widget
		    unregister_widget('WP_Widget_Links');

		    // Removes the default WordPress meta widget
		    unregister_widget('WP_Widget_Meta');

		    // Removes the default WordPress search widget
		    // unregister_widget('WP_Widget_Search');

		    // Removes the default WordPress text widget
		    // unregister_widget('WP_Widget_Text');

		    // Removes the default WordPress categories widget
		    unregister_widget('WP_Widget_Categories');

		    // Removes the default WordPress recent widget
		    unregister_widget('WP_Widget_Recent_Posts');

		    // Removes the default WordPress recent comments widget
		    unregister_widget('WP_Widget_Recent_Comments');

		    // Removes the default WordPress RSS widget
		    unregister_widget('WP_Widget_RSS');

		    // Removes the default WordPress tag cloud widget
		    unregister_widget('WP_Widget_Tag_Cloud');

		    // Removes the default WordPress custom menu widget
		    // unregister_widget('WP_Nav_Menu_Widget');

		    // Removes the default WordPress custom menu widget
		    // :TODO not working WP v3.4.2
		    unregister_widget('Twenty_Eleven_Ephemera_Widget');

	    } // fm_remove_default_widgets()

	// == Footer ==
	  // Custom admin footer text
			function fm_custom_admin_footer()
			{
				$client_name = get_bloginfo('name');
				$wp_version = get_bloginfo('version');
				echo "Created by <a href=\"http://www.freemanhelp.com\">Freeman Marketing</a> for $client_name powered by <a href=\"http://www.wordpress.org\">WordPress $wp_version</a>";
			} // fm_custom_admin_footer()

	// == :TODO Tiny MCE ==
		// :TODO Look into other Tiny MCE options
		// Make TinyMCE Editor Awesome
			function fm_make_mce_awesome( $init ) {
				// Removes H1 tags
			  $init['theme_advanced_blockformats'] = 'h2,h3,h4,p';

			  // Adds some custom buttons
			  $init['theme_advanced_buttons1_add'] = 'copy, cut, paste, redo, undo';

			  // Adds some more custom buttons
			  $init['theme_advanced_buttons2_add'] = 'anchor, hr, sub, sup';

			  // Disables the WP help button
			  $init['theme_advanced_disable'] = 'wp_help';

			  return $init;
			} // fm_make_mce_awesome($init)

		// Tiny MCE Buttons 2
			function fm_mce_buttons_2( $buttons ) {
		    array_unshift( $buttons, 'styleselect' );
		    return $buttons;
			} // fm_mce_buttons_2($buttons)

		// Add a style drop down to Tiny MCE
			function fm_mce_before_init( $settings ) {
				$style_formats = array();
				// Style Examples
		    // $style_formats = array(
		    //  array(
		    //  'title' => 'Button',
		    //  'selector' => 'a',
		    //  'classes' => 'btn'
		    //  ),
		    //     array(
		    //      'title' => 'Callout Box',
		    //      'block' => 'div',
		    //      'classes' => 'callout',
		    //      'wrapper' => true
		    //     ),
		    //     array(
		    //      'title' => 'Bold Red Text',
		    //      'inline' => 'span',
		    //      'styles' => array(
		    //      'color' => '#f00',
		    //      'fontWeight' => 'bold'
		    //      )
		    //     )
		    // );
		    $settings['style_formats'] = json_encode( $style_formats );
		    return $settings;
			} // fm_mce_before_init($settings)

		// Add Tiny MCE editor styles
		add_editor_style(get_stylesheet_directory_uri() . '/_assets/css/tiny-mce.css');
// == Execute all of the above only if we are in the admin section ====
	if (is_admin()) {

		// Adds contact us link to menu bar
		add_action('admin_bar_menu', 'fm_add_contact_admin_bar_link',25);

		// Adds website link to admin bar
		// add_action('admin_bar_menu', 'fm_add_fmhelp_admin_bar_link',25);

		// Replaces site link with a view your site link
		add_action( 'wp_before_admin_bar_render', 'fm_remove_admin_bar_menus' ); // Remove the original site name
		add_action('admin_bar_menu', 'fm_add_view_site_admin_bar_link',25); // Adds a new link that will open in a new window

		// Sets admin bar to not display when viewing page by default
		add_action("user_register", "fm_set_user_view_page_admin_bar_false", 10, 1);

		// Removes admin menu pages
		add_action( 'admin_menu', 'fm_remove_admin_menu_pages' );

		// Adds a documentation menu page
		add_action('admin_menu', 'fm_add_documentation');

		// Adds company information widget
		add_action('wp_dashboard_setup', 'fm_add_company_info_widget');

		// Removes default widgets
		add_action('wp_dashboard_setup', 'fm_remove_dashboard_widgets');

		// Adds admin stylesheet
		add_action('admin_init', 'fm_add_admin_stylesheet');

		// Removes default widgets
		add_action('widgets_init', 'fm_remove_default_widgets', 1);

		// Adds custom admin footer text
		add_filter('admin_footer_text', 'fm_custom_admin_footer');

		// Makes Tiny MCE awesome
		add_filter('tiny_mce_before_init', 'fm_make_mce_awesome');

		// Tiny MCE buttons 2
		add_filter( 'mce_buttons_2', 'fm_mce_buttons_2' );

		// Adds styles drop down menu
		add_filter( 'tiny_mce_before_init', 'fm_mce_before_init' );

	} // if(is_admin())

