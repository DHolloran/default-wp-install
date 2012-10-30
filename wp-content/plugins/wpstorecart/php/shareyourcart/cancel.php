<?php
// wpStoreCart, (c) 2011 wpStoreCart.com.  All rights reserved.

global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
global $wpdb, $wpStoreCart;

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
        // IF ANY ITEMS IN THE CART
        if($cart->itemcount > 0) {

                @$newsplit = explode('-', $item['id'] );
                @$item['id'] = $newsplit[0];

                // DISPLAY LINE ITEMS
                $icounter = 0;
                foreach($cart->get_contents() as $item) {
                    $params['cart'][$icounter]['item_name'] = $item['name'];
                    $params['cart'][$icounter]['item_description'] = $item['name'] . ' (x'.$item['qty'].')';
                    $params['cart'][$icounter]['item_url'] = $devOptions['mainpage'];
                    $params['cart'][$icounter]['item_price'] = $item['subtotal'];
                    $params['cart'][$icounter]['item_picture_url'] = $devOptions['mainpage'];
                    $icounter++;
                }

        }


        // Save the app and client ID/keys
        $params['app_key'] = $devOptions['shareyourcart_appid'];
        $params['client_id'] = $devOptions['shareyourcart_clientid'];

        //call the core SDK function
        $data = shareyourcart_startSessionAPI($params);

        //TODO: save the returned data, for later reference.
        //token and the session_id
        //$wpdb->insert($wpdb->base_prefix."shareyourcart_tokens",$data);
}

sendCart();

?>