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
$totalweight = 0;

                // IF ANY ITEMS IN THE CART
                if($cart->itemcount > 0) {

                        $newsplit = explode('-', $item['id'] );
                        $item['id'] = $newsplit[0];

                        // DISPLAY LINE ITEMS
                        foreach($cart->get_contents() as $item) {
                            $results = $wpdb->get_results('SELECT `weight` FROM `'.$table_name.'` WHERE `primkey`='.$item['id'].';', ARRAY_N);
                            $totalweight = $totalweight + ($results[0][0] * $item['qty']);
                            unset($results);
                        }

                }
                
if(!isset($_SESSION)) {
        @session_start();
}
$_SESSION['wpsc_zipcode'] = $wpdb->escape($_POST['zipcode']);

// USPS
$totalshippingcalculated = $wpStoreCart->USPSParcelRate($totalweight, $_SESSION['wpsc_zipcode'] );

echo number_format($totalshippingcalculated, 2);



?>