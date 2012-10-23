<?php
global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
if (!function_exists('add_action'))
{
    require_once("../../../../wp-config.php");
}

global $wpdb;


$table_name = $wpdb->prefix . "wpstorecart_products";

$primkey = intval($_POST['primkey']);

$theResults = $wpdb->get_results("SELECT `name` FROM `{$table_name}` WHERE `primkey`={$primkey};", ARRAY_A);

echo $theResults[0]['name'];


?>