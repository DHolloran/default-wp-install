<?php
// == Detect Ajax ================================================================
	function fm_is_ajax()
	{
		if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			// We have an AJAX request
			return true;
		} // if()

		// We do not have an AJAX request
		return false;

	} // fm_is_ajax()

// == Return Action ==============================================================
	function fm_ajax_return_action($return_no_ajax='', $return_ajax='')
	{
		if (fm_is_ajax()) {
			// Send response back to server in JSON format
			echo json_encode($return_ajax);
		} else {
			// Redirect to the specified page with the specified parameters
			header("Location: $return_no_ajax");
		}
	} // fm_ajax_return_action($return_no_ajax='', $return_ajax='')




