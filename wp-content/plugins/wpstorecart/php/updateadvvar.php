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

    $table_name = $wpdb->prefix . "wpstorecart_av";
    $advvarkey = $wpdb->escape($_POST['advvarkey']);
    $advvarprice = $wpdb->escape($_POST['advvarprice']);
    $advvarcombo = $wpdb->escape($_POST['advvarcombo']);
    $finaladvvar = '';
    foreach ($advvarcombo as $current) {
        $finaladvvar .= $current .'^^^^';
    }

    $grabrecord = "SELECT * FROM `{$table_name}` WHERE `productkey`={$advvarkey} AND `values` = '{$finaladvvar}'  ORDER BY `primkey` LIMIT 0, 1;";

    $vresults = $wpdb->get_results( $grabrecord , ARRAY_N );

    if(!isset($vresults[0][0])) {
        
        $insert = "
        INSERT INTO `{$table_name}` (
        `primkey`, `productkey`, `values`, `price`)
        VALUES (
                NULL,
                '{$advvarkey}',
                '{$finaladvvar}',
                '{$advvarprice}'
        );
        ";
    } else {

        $insert = "
        UPDATE `{$table_name}` SET `values`='{$finaladvvar}', `price`='{$advvarprice}' WHERE `primkey`='{$vresults[0][0]}';
        ";
    }

    $results = $wpdb->query($insert);

    echo $advvarprice;


}
?>