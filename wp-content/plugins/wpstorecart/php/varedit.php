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

    if (function_exists('current_user_can') && !current_user_can('manage_wpstorecart')) {
            die(__('Cheatin&#8217; uh?'));
    }

    $table_name = $wpdb->prefix . "wpstorecart_meta";

    $theId = $wpdb->escape($_POST['id']);
    $saveData = explode('_', $theId);

    $grabrecord = "SELECT * FROM `{$table_name}` WHERE `type`='productvariation' AND `primkey`={$saveData[1]};";
    $results = $wpdb->get_results( $grabrecord , ARRAY_A );
    if(isset($results)) {
            foreach ($results as $result) {
                $exploder = explode('||', $result['value']);
                $createnewvar = $exploder[0];
                $varvalue = $exploder[1];
                $varprice = $exploder[2];
                $vardesc = $exploder[3];
                $vardownloads = $exploder[4];
                $vartype = $exploder[5];
            }
    }

    if($saveData[0]=='varcat') {
        $createnewvar = $wpdb->escape($_POST['value']);
    }
    if($saveData[0]=='varvalue') {
        $varvalue = $wpdb->escape($_POST['value']);
    }
    if($saveData[0]=='varprice') {
        $varprice = $wpdb->escape($_POST['value']);
    }
    if($saveData[0]=='vardesc') {
        $vardesc = $wpdb->escape($_POST['value']);
    }

    $insert = "UPDATE `{$table_name}` SET `value` = '{$createnewvar}||{$varvalue}||{$varprice}||{$vardesc}||{$vardownloads}||{$vartype}' WHERE `primkey`={$saveData[1]};";

    $results = $wpdb->query($insert);
    $lastID = $wpdb->insert_id;
    echo $wpdb->escape($_POST['value']);


}
?>