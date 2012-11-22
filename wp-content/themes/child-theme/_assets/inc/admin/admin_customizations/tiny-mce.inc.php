<?php
// == Tiny MCE ========================================================
// Make TinyMCE Editor Awesome
function dh_make_mce_awesome( $init ) {
	// Removes H1 tags
	$init['theme_advanced_blockformats'] = 'h2,h3,h4,p';

	// Adds some custom buttons
	$init['theme_advanced_buttons1_add'] = 'copy, cut, paste, redo, undo';

	// Adds some more custom buttons
	$init['theme_advanced_buttons2_add'] = 'anchor, hr, sub, sup';

	// Disables the WP help button
	$init['theme_advanced_disable'] = 'wp_help';

	return $init;
} // dh_make_mce_awesome($init)

// Tiny MCE Buttons 2
function dh_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
} // dh_mce_buttons_2($buttons)

// Add a style drop down to Tiny MCE
function dh_mce_before_init( $settings ) {
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
} // dh_mce_before_init($settings)

// Add Tiny MCE editor styles
add_editor_style( get_stylesheet_directory_uri() . '/_assets/css/tiny-mce.css' );
