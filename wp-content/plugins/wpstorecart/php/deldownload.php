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
    header('HTTP/1.1 403 Forbidden'); // Failure
    exit();
} else {

    if (function_exists('current_user_can') && !current_user_can('manage_wpstorecart')) {
            header('HTTP/1.1 403 Forbidden'); // Failure
            die(__('Cheatin&#8217; uh?'));
    }

    $delete = base64_decode($_POST['delete']).'||';
    $type = $_POST['type'];
    $primkey = $_POST['primkey'];

    // Non-variations downloads
    if($type=='single') {
        $table_name = $wpdb->prefix . "wpstorecart_products";

        $sql = "SELECT `download` FROM `{$table_name}` WHERE `primkey`={$primkey};";
        $results = $wpdb->get_results( $sql , ARRAY_A );
        if(isset($results[0]['download'])) {
            $newdownload = str_replace($delete, '', $results[0]['download']);
            $insert = "UPDATE `{$table_name}` SET `download`='{$newdownload}'  WHERE `primkey`={$primkey};";
            $results = $wpdb->query($insert);
            if($results && file_exists(WP_CONTENT_DIR . '/uploads/wpstorecart/'.base64_decode($_POST['delete']))) {
                @unlink(WP_CONTENT_DIR . '/uploads/wpstorecart/'.base64_decode($_POST['delete']));
            }
            if($results) {
                header('HTTP/1.1 200 OK'); // Success
                exit();
            } else {
                header('HTTP/1.1 403 Forbidden'); // Failure
                exit();
            }
        }
    }




}
?>