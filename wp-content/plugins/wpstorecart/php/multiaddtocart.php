<?php
global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
if (!function_exists('add_action'))
{
    require_once("../../../../wp-config.php");
}

global $current_user,$wpdb, $wpStoreCart, $cart, $wpsc, $totalshippingcalculated;


$devOptions = $wpStoreCart->getAdminOptions();
$table_name = $wpdb->prefix . "wpstorecart_products";

$productsToAdd = $_POST['productsToAddArray'];
$masterProduct = $productsToAdd[0]; //This is the master product, we will use this to see if there are accessory discounts to add




$sql = "SELECT * FROM `{$table_name}` WHERE `primkey`=0 ";
foreach ($productsToAdd as $productToAdd ) {
    $sql .= ' OR `primkey`='.$productToAdd.' ';
}
$sql .= ' OR `primkey`=0;';

$theResults = $wpdb->get_results($sql, ARRAY_A);
$cart->multi_add_item($theResults, 1, $masterProduct);


?>