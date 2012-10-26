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
    $taxname = $wpdb->escape($_POST['taxname']);
    $countriestotax = $wpdb->escape($_POST['countriestotax']);
    $statestotax = $wpdb->escape($_POST['statestotax']);
    $taxpercent = $wpdb->escape($_POST['taxpercent']);
    $taxprimkey = $wpdb->escape($_POST['taxprimkey']);

    $insert = NULL;
    if($taxprimkey==0) {
        $insert = "
        INSERT INTO `{$table_name}` (
        `primkey`, `value`, `type`, `foreignkey`)
        VALUES (
                NULL,
                '{$taxname}||{$countriestotax}||{$statestotax}||{$taxpercent}',
                'tax',
                '0'
        );
        ";

        $results = $wpdb->query($insert);
        $lastID = $wpdb->insert_id;
        echo $lastID;
    } else {
       if(is_numeric($taxprimkey)) {
            $insert = "
            UPDATE `{$table_name}` SET `value`='{$taxname}||{$countriestotax}||{$statestotax}||{$taxpercent}' WHERE `primkey`='{$taxprimkey}';
            ";
            $results = $wpdb->query($insert);
            echo $taxprimkey;
       }
    }




}
?>