<?php
// wpStoreCart, (c) 2011 wpStoreCart.com.  All rights reserved.

global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
global $wpdb, $wpStoreCart, $wpsc_cart_type;

if (!function_exists('add_action'))
{
    require_once("../../../../../wp-config.php");
}

$devOptions = $wpStoreCart->getAdminOptions();

require_once('shareyourcart-sdk.php');
require_once(WP_CONTENT_DIR . '/plugins/wpstorecart/php/wpsc-1.1/wpsc/wpsc-config.php');
require_once(WP_CONTENT_DIR . '/plugins/wpstorecart/php/wpsc-1.1/wpsc/wpsc-defaults.php');
require_once(WP_CONTENT_DIR . '/plugins/wpstorecart/php/wpsc-1.1/wpsc/wpsc.php');


if($wpsc_cart_type == 'session') {
    if(!isset($_SESSION)) {
            @session_start();
    }
    if(@!is_object($cart)) {
        $cart =& $_SESSION['wpsc'];
        if(@!is_object($cart)) {
            $cart = new wpsc();
        }
    }
}

if($wpsc_cart_type == 'cookie') {
    if(!isset($_SESSION)) { @session_start(); }
    if(@!is_object($cart)) {
        if(isset($_COOKIE['wpsccart'])) { @$cart =& unserialize(base64_decode($_COOKIE['wpsccart'])); }
        if(@!is_object($cart) && !isset($_COOKIE['wpsccart'])) {
            $cart = new wpsc();
            $xdomain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;setcookie('wpsccart', base64_encode(serialize($cart)), time()+7222, '/', $xdomain, false);
        }
    }
}



function sendCart() {
        global $devOptions, $wpdb, $wpStoreCart, $cart;
        //TODO: populate the params with either the cart's content, or the product the user is currently viewing
        $params = array();
        

        if(!isset($_GET['product']) || !is_numeric($_GET['product']) ) { // If we're sending the cart
            if($cart->itemcount > 0) {

                    @$newsplit = explode('-', $item['id'] );
                    if(isset($newsplit[0])) {
                        @$item['id'] = $newsplit[0];
                    }

                    // DISPLAY LINE ITEMS
                    $icounter = 0;
                    foreach($cart->get_contents() as $item) {
                        $params['cart'][$icounter]['item_name'] = $item['name'];
                        $params['cart'][$icounter]['item_description'] = $item['name'] . ' (x'.$item['qty'].')';
                        $params['cart'][$icounter]['item_url'] = get_permalink($devOptions['mainpage']);
                        $params['cart'][$icounter]['item_price'] = $devOptions['currency_symbol'].number_format($item['subtotal'],2).$devOptions['currency_symbol_right'];
                        $params['cart'][$icounter]['item_picture_url'] = get_permalink($devOptions['mainpage']);
                        $icounter++;
                    }

            } // IF ANY ITEMS IN THE CART
        } else { // Here, we're sending a specific item
                    $table_name = $wpdb->prefix . "wpstorecart_products";
                    $sql = "SELECT * FROM `{$table_name}` WHERE `primkey`={$wpdb->escape($_GET['product'])};";
                    $results = $wpdb->get_results( $sql , ARRAY_A );
                    if(isset($results)) {
                        $params['cart'][0]['item_name'] = $results[0]['name'];
                        $params['cart'][0]['item_description'] = $results[0]['introdescription'];
                        $params['cart'][0]['item_url'] = get_permalink($results[0]['postid']);
                        $params['cart'][0]['item_price'] = $devOptions['currency_symbol'].number_format($results[0]['price'],2).$devOptions['currency_symbol_right'];
                        $params['cart'][0]['item_picture_url'] = $results[0]['thumbnail'];
                    }
        }


        // Save the app and client ID/keys
        $params['app_key'] = $devOptions['shareyourcart_appid'];
        $params['client_id'] = $devOptions['shareyourcart_clientid'];
        $params['callback_url'] = plugins_url('/wpstorecart/php/shareyourcart/savecoupon.php');
        $params['success_url'] = get_permalink($devOptions['checkoutpage']);
        $params['cancel_url'] = get_permalink($devOptions['checkoutpage']);

        //call the core SDK function
        $data = shareyourcart_startSessionAPI($params);

        //save the returned data, for later reference.
        //token and the session_id
        if(is_array($data)) {
            $table_name_meta = $wpdb->prefix . "wpstorecart_meta";
            $results = $wpdb->query("INSERT INTO `{$table_name_meta}` (`primkey`, `value`, `type`, `foreignkey`) VALUES (NULL, '".base64_encode(serialize($data))."', 'shareyourcarttoken', '0');");
        }

}

sendCart();

?>