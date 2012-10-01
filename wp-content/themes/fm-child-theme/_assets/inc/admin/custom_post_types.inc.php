<?php
// == Custom Post Type ==================================================
	// function create_custom_post_type_test()
	// {

	// 	// Setup the custom post type labels
	// 	$labels = array(
	//     'name' => _x('Customs', 'post type general name', 'your_text_domain'),
	//     'singular_name' => _x('Custom', 'post type singular name', 'your_text_domain'),
	//     'add_new' => _x('Add New', 'custom', 'your_text_domain'),
	//     'add_new_item' => __('Add New Custom', 'your_text_domain'),
	//     'edit_item' => __('Edit Custom', 'your_text_domain'),
	//     'new_item' => __('New Custom', 'your_text_domain'),
	//     'all_items' => __('All Customs', 'your_text_domain'),
	//     'view_item' => __('View Custom', 'your_text_domain'),
	//     'search_items' => __('Search Customs', 'your_text_domain'),
	//     'not_found' =>  __('No customs found', 'your_text_domain'),
	//     'not_found_in_trash' => __('No customs found in Trash', 'your_text_domain'),
	//     'parent_item_colon' => '',
	//     'menu_name' => __('Customs', 'your_text_domain')
	//   );
	//	 // Setup the custom post type arguments
	//   $args = array(
	//     'labels' => $labels,
	//     'description' => 'This is a custom post type',
	//     'public' => true,
	//     'publicly_queryable' => true,
	//     'show_ui' => true,
	//     'show_in_menu' => true,
	//     'show_in_nav_menus'	=>TRUE,
	//     'show_in_admin_bar'	=>	TRUE,
	//     'query_var' => true,
	//     'rewrite' => array( 'slug' => _x( 'custom', 'URL slug', 'your_text_domain' ) ),
	//     'capability_type' => 'post',
	//     'has_archive' => true,
	//     'hierarchical' => false,
	//     // 'menu_icon'	=>	'',
	//     // 'capability_type'	=>	'page',
	//     'menu_position' => null,
	//     'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	//   );
	//   // Register the Customs post type
	//   register_post_type('custom', $args);
	// } // create_custom_post_type_test()
	// add_action( 'init', 'create_custom_post_type_test' );


// == Slides Post Type ==================================================
	function create_custom_post_type_slide()
	{

		// Setup the slide post type labels
		$labels = array(
	    'name' => _x('Slides', 'post type general name', 'your_text_domain'),
	    'singular_name' => _x('Slide', 'post type singular name', 'your_text_domain'),
	    'add_new' => _x('Add New', 'slide', 'your_text_domain'),
	    'add_new_item' => __('Add New Slide', 'your_text_domain'),
	    'edit_item' => __('Edit Slides', 'your_text_domain'),
	    'new_item' => __('New Slide', 'your_text_domain'),
	    'all_items' => __('All Slides', 'your_text_domain'),
	    'view_item' => __('View Slides', 'your_text_domain'),
	    'search_items' => __('Search Slides', 'your_text_domain'),
	    'not_found' =>  __('No slides found', 'your_text_domain'),
	    'not_found_in_trash' => __('No slides found in Trash', 'your_text_domain'),
	    'parent_item_colon' => '',
	    'menu_name' => __('Slides', 'your_text_domain')
	  );

		// Setup the custom post type arguments
	  $args = array(
	    'labels' => $labels,
	    'description' => 'Add your slides to the slideshow',
	    'public' => true,
	    'publicly_queryable' => true,
	    'show_ui' => true,
	    'show_in_menu' => true,
	    'show_in_nav_menus'	=>TRUE,
	    'show_in_admin_bar'	=>	TRUE,
	    'query_var' => true,
	    'rewrite' => array( 'slug' => _x( 'slide', 'URL slug', 'your_text_domain' ) ),
	    'capability_type' => 'post',
	    'has_archive' => true,
	    'hierarchical' => false,
	    'menu_icon'	=>	get_stylesheet_directory_uri(). '/_assets/img/admin/icons/slides.png',
	    'capability_type'	=>	'page',
	    'menu_position' => null,
	    'supports' => array( 'title', 'editor', 'thumbnail' )
	  );

	  // Register the Slides post type
	  register_post_type('slide', $args);

	} // create_custom_post_type_slide()
	add_action( 'init', 'create_custom_post_type_slide' );