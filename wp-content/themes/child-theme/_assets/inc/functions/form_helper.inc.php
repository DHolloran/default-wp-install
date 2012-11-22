<?php

// == Save Form Data On Error ==================================
function dh_save_values( $values = array() ) {
	// Make sure we have something to work with
	if ( count( $values ) and get_type( $values ) == 'array' ) {
		// Make sure a session is started
		if ( !isset( $_SESSION ) ) session_start();

		// Loop over the values the user passed in
		foreach ( $values as $value ) {
			if ( isset( $_POST[$value] ) ) {
				// If there is something in the POST Global save it to a Session
				$_SESSION[$value] = $_POST[$value];
			} elseif ( isset( $_GET[$value] ) ) {
				// If there is something in the GET Super Global save it to a Session
				$_SESSION[$value] = $_POST[$value];
			} // if/elseif()
		} // foreach

		return true;
	} // if()


	return false;
} // dh_save_values($values = array())

// == Retrieve Saved Form Values
function dh_retrieve_value( $value ) {
	// Make sure a session is started
	if ( !isset( $_SESSION ) ) session_start();

	if ( !empty( $_SESSION[$value] ) ) {
		return $_SESSION[$value];
	} else {
		return false;
	} // if/else()
} // dh_retrieve_value($value)
