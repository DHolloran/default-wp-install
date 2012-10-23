<?php
global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
if (!function_exists('add_action'))
{
    require_once("../../../../wp-config.php");
}

global $current_user,$wpdb;



    $table_name = $wpdb->prefix . "wpstorecart_av";
    $table_name2 = $wpdb->prefix . "wpstorecart_products";
    $advvarkey = $wpdb->escape($_POST['advvarkey']);
    $advvarcombo = $wpdb->escape($_POST['advvarcombo']);
    $finaladvvar = '';
    foreach ($advvarcombo as $current) {
        $finaladvvar .= $current .'^^^^';
    }

    $grabrecord = "SELECT * FROM `{$table_name}` WHERE `productkey`={$advvarkey} AND `values` = '{$finaladvvar}' ORDER BY `primkey` LIMIT 0, 1;";

    $vresults = $wpdb->get_results( $grabrecord , ARRAY_N );


    if(@isset($vresults[0][0])) {
        $advvarprice = $vresults[0][3];
    } else {
        $grabrecord = "SELECT `price` FROM `{$table_name2}` WHERE `primkey`={$advvarkey}  ORDER BY `primkey` LIMIT 0, 1;";
        $xresults = $wpdb->get_results( $grabrecord , ARRAY_N );
        $advvarprice = $xresults[0][0];
    }



    echo $advvarprice;


?>