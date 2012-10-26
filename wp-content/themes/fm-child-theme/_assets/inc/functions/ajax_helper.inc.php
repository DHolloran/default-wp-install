<?php
// == Detect Ajax ================================================================
	function cb_is_ajax()
	{
		if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			// We have an AJAX request
			return true;
		} // if()

		// We do not have an AJAX request
		return false;

	} // cb_is_ajax()


// == Return Action ==============================================================
	function cb_ajax_return_action($return_no_ajax='', $return_ajax='')
	{
		if (cb_is_ajax()) {
			// Send response back to server in JSON format
			echo json_encode($return_ajax);
		} else {
			// Redirect to the specified page with the specified parameters
			header("Location: $return_no_ajax");
		}
	} // cb_ajax_return_action($return_no_ajax='', $return_ajax='')


// == Add Ajax ==================================================================
	function cb_add_ajax()
	{
		// Setup AJAX Script
		wp_register_script('ajax-script', get_stylesheet_directory_uri().'/_assets/js/coffee/ajax.js', array('jquery'), 1.0);
		wp_enqueue_script(  'ajax-script' );

		// Localize AJAX Script
		wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); // setting ajaxurl

		// Some Action
		add_action( 'wp_ajax_some_action', 'cb_ajax_some_action' ); // ajax for logged in users
		add_action( 'wp_ajax_nopriv_some_action', 'cb_ajax_some_action' ); // ajax for not logged in users

	} // cb_add_ajax()
	add_action('init', 'cb_add_ajax');


// == Ajax Action == some_action ================================================
	function cb_ajax_some_action() {
		// Do some magic
		// Send back to JS
		echo json_encode('works');
		die();
	} // cb_ajax_some_action()

