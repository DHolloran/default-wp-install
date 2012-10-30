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

//must check that the user has the required capability
if (function_exists('current_user_can') && !current_user_can('manage_wpstorecart'))
{
  wp_die( __('wpStoreCart: You do not have sufficient permissions to access this page.') );
}


if(isset($_GET['emailaddr'])) {
    $devOptions['wpStoreCartEmail'] = $_GET['emailaddr'];
}


require_once('shareyourcart-sdk.php');

$recovery = shareyourcart_recoverAPI(trim($devOptions['shareyourcart_secret']), trim('http://'.$_SERVER['HTTP_HOST']), trim($devOptions['wpStoreCartEmail']));
if($recovery) {
    echo 'ShareYourCart.com credentials were sent to '.$devOptions['wpStoreCartEmail'];
} else {
    $register = shareyourcart_registerAPI(trim($devOptions['shareyourcart_secret']), trim('http://'.$_SERVER['HTTP_HOST']), trim($devOptions['wpStoreCartEmail']));
    if(!$register) {
        echo 'This domain is already registered, and we FAILED to send ShareYourCart.com credentials to '.$devOptions['wpStoreCartEmail'].'<br /><br />';
        echo 'Did you sign up with a different email address? If so, enter it here: <form action="" type="get"><input name="emailaddr" type="text" value="'.$devOptions['wpStoreCartEmail'].'" /><input type="submit" value="Retry" /></form>';
    } else {
        echo 'Successfully registered with ShareYourCart.com';
    }
}

?>