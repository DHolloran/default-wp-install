<?php

error_reporting(E_ALL^E_STRICT);

define('WP_USE_THEMES', false);
require('../../../../wp-blog-header.php');
require_once( ABSPATH . WPINC . '/pluggable.php');
global $wpStoreCart;
global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}

require_once( ABSPATH . WPINC . '/registration.php');
if('POST' != $_SERVER['REQUEST_METHOD']){@wp_safe_redirect($_POST['redirect_to']);exit();}


$user_login = sanitize_user($_POST['email']);
$user_email=$_POST['email'];
$user_pass = $_POST['user_pass'];
$redirect_to = $_POST['redirect_to'];

require_once(WP_CONTENT_DIR . '/plugins/wpstorecart/php/wpsc-1.1/wpsc/wpsc-config.php');
require_once(WP_CONTENT_DIR . '/plugins/wpstorecart/php/wpsc-1.1/wpsc/wpsc-defaults.php');
require_once(WP_CONTENT_DIR . '/plugins/wpstorecart/php/wpsc-1.1/wpsc/wpsc.php');
if(!isset($_SESSION)) {
        @session_start();
}

// Allows us to save this information in case we need to redisplay it later.
$_SESSION['wpsc_email'] = $user_email;
$_SESSION['wpsc_password'] = $user_pass;

$invalid_detected = false; // If there's an invalid item, this records it.

if(username_exists( $user_login )) {
    $invalid_detected = 1;
}

if(!validate_username( $user_login )){
    $invalid_detected = 2;
}

if (!is_email( $user_email )) {
    $invalid_detected = 3;
}

if (email_exists( $user_email )) {
    $invalid_detected = 4;
}

$user_id = wp_create_user( $user_login, $user_pass, $user_email );
if ( !$user_id )   {
    $invalid_detected = 5;
}

$fields = $wpStoreCart->grab_custom_reg_fields();
foreach ($fields as $field) {
    $specific_items = explode("||", $field['value']);
    if($specific_items[2]!='separator' && $specific_items[2]!='header' && $specific_items[2]!='text') {
        $current_field = trim($_POST[$wpStoreCart->slug($specific_items[0])]);
        $_SESSION['wpsc_'.$wpStoreCart->slug($specific_items[0])]=$_POST[$wpStoreCart->slug($specific_items[0])]; // This allows us to save data in case the form needs to be refilled out due to it being incomplete
        if ($specific_items[1]=='required' && $current_field=='') {
            $invalid_detected = 6;
        }
    }
}

if($invalid_detected==false) {
    wp_new_user_notification($user_id, $user_pass);
    $credentials=array('remember'=>true,'user_login'=>$user_login,'user_password'=>$user_pass);
    wp_signon($credentials);

    foreach ($fields as $field) {
        $specific_items = explode("||", $field['value']);

        if($specific_items[2]!='separator' && $specific_items[2]!='header' && $specific_items[2]!='text') {
            update_usermeta($user_id, $wpStoreCart->slug($specific_items[0]), $_POST[$wpStoreCart->slug($specific_items[0])] );
        }
        
    }

    wp_safe_redirect($redirect_to);
    exit();
}

if(strpos($redirect_to,'?')===false) {
    $redirect_to_with_errors = $redirect_to .'?wpscregerror='.urlencode($invalid_detected);
} else {
    $redirect_to_with_errors = $redirect_to .'&wpscregerror='.urlencode($invalid_detected);
}




wp_safe_redirect($redirect_to_with_errors);
exit();
?>