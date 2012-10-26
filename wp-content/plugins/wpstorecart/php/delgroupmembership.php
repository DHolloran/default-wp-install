<?php
global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
if (!function_exists('add_action'))
{
    require_once("../../../../wp-config.php");
}

global $current_user,$wpdb, $wpStoreCart;
$devOptions = $wpStoreCart->getAdminOptions();

wp_get_current_user();
if ( 0 == $current_user->ID ) {
    // Not logged in.
} else {

    if ( function_exists('current_user_can') && !current_user_can('edit_users') ) { //
            die(__('You need the edit_users capability to access this admin page'));
    }

    $user_check = new WP_User( intval($_POST['user_id']) );
    $user_check->remove_cap( $wpdb->escape($_POST['cap_name']) );


}
?>