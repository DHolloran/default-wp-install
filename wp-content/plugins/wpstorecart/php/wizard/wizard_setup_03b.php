<?php

// wpStoreCart, (c) 2011 wpStoreCart.com.  All rights reserved.

global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
global $wpdb, $wpStoreCart;

if (!function_exists('add_action'))
{
    require_once("../../../../../wp-config.php");
}
$devOptions = $wpStoreCart->getAdminOptions();

if(current_user_can('administrator')) {

    $devOptions['storetype'] = 'Physical Goods Only';
    update_option('wpStoreCartAdminOptions', $devOptions);
    header("HTTP/1.1 301 Moved Permanently");
    header ('Location: '.plugins_url().'/wpstorecart/php/wizard/wizard_setup_04.php');
    exit();

}

?>