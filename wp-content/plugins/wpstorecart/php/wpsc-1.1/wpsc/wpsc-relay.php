<?php

// wpsc library, v1.2 by wpStoreCart.com
// Forked from wpsc v1.1 http://conceptlogic.com/jcart/

// THIS FILE TAKES INPUT FROM AJAX REQUESTS VIA JQUERY post AND get METHODS, THEN PASSES DATA TO wpsc
// RETURNS UPDATED CART HTML BACK TO SUBMITTING PAGE

// INCLUDE wpsc BEFORE SESSION START

global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}

/*
if (!function_exists('add_action'))
{
    require_once("../../../../../../wp-config.php");
}
*/

include_once 'wpsc.php';

// START SESSION
if(!isset($_SESSION)) {
        session_start();
}

// INITIALIZE wpsc AFTER SESSION START
$cart =& $_SESSION['wpsc']; if(!is_object($cart)) $cart = new wpsc();

// PROCESS INPUT AND RETURN UPDATED CART HTML
$cart->display_cart($wpsc);

?>
