<?php

// wpsc library, v1.1a by wpStoreCart.com
// Forked from jcart v1.1 http://conceptlogic.com/jcart/

// THIS FILE IS CALLED WHEN ANY BUTTON ON THE CHECKOUT PAGE (PAYPAL CHECKOUT, UPDATE, OR EMPTY) IS CLICKED
// WE CAN ONLY DEFINE ONE FORM ACTION, SO THIS FILE ALLOWS US TO FORK THE FORM SUBMISSION DEPENDING ON WHICH BUTTON WAS CLICKED
// ALSO ALLOWS US TO VERIFY PRICES BEFORE SUBMITTING TO PAYPAL

// INCLUDE wpsc BEFORE SESSION START

global $wpsc_error_reporting, $wpsc_cart_type, $cart, $devOptions, $wpdb;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
if (!function_exists('add_action'))
{
    require_once("../../../../../../wp-config.php");
}
include_once(ABSPATH . 'wp-content/plugins/wpstorecart/php/wpsc-1.1/wpsc/wpsc.php');

if(!isset($_SESSION)) {
	session_start();
}

if(isset($wpStoreCart)) {
	$devOptions = $wpStoreCart->getAdminOptions();
} else {
	exit();
}

// INITIALIZE wpsc AFTER SESSION START
if($wpsc_cart_type == 'session') {
    $cart =& $_SESSION['wpsc']; if(!is_object($cart)) $cart = new wpsc();
}


// WHEN JAVASCRIPT IS DISABLED THE UPDATE AND EMPTY BUTTONS ARE DISPLAYED
// RE-DISPLAY THE CART IF THE VISITOR CLICKS EITHER BUTTON
if (isset($_POST['wpsc_update_cart'])  || isset($_POST['wpsc_empty']))
	{

	// UPDATE THE CART
	if (isset($_POST['wpsc_update_cart']))
		{
		$cart_updated = $cart->update_cart();
		if ($cart_updated !== true)
			{
			$_SESSION['quantity_error'] = true;
			}
		}

	// EMPTY THE CART
	if (isset($_POST['wpsc_empty']))
		{
		$cart->empty_cart();
		}

	// REDIRECT BACK TO THE CHECKOUT PAGE
	header('Location: ' . $_POST['wpsc_checkout_page']);
	exit;
	}

// THE VISITOR HAS CLICKED THE CHECKOUT BUTTON
else
	{

	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////
	/*

	A malicious visitor may try to change item prices before checking out,
	either via javascript or by posting from an external script.

	Here you can add PHP code that validates the submitted prices against
	your database or validates against hard-coded prices.

	The cart data has already been sanitized and is available thru the
	$cart->get_contents() function. For example:

	foreach ($cart->get_contents() as $item)
		{
		$item_id	= $item['id'];
		$item_name	= $item['name'];
		$item_price	= $item['price'];
		$item_qty	= $item['qty'];
		}

	*/
	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////

	$valid_prices = true;

	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////

	// IF THE SUBMITTED PRICES ARE NOT VALID
	if ($valid_prices !== true)
		{
		// KILL THE SCRIPT
		die($wpsc['text']['checkout_error']);
		}

	// PRICE VALIDATION IS COMPLETE
	// SEND CART CONTENTS TO PAYPAL USING THEIR UPLOAD METHOD, FOR DETAILS SEE http://tinyurl.com/djoyoa
	else if ($valid_prices === true)
		{

                global $current_user, $wpdb, $paymentGateway, $paymentGatewayOptions,$cart;
                get_currentuserinfo();




                if(isset($_POST['wpsc-shipping-type']) ||isset($_POST['wpsc-shipping-type-widget']) ) {
                    @$shipping_type = $_POST['wpsc-shipping-type'];
                    @$shipping_type_widget = $_POST['wpsc-shipping-type-widget'];
                } else {
                    $shipping_type='shipping_offered_by_flatrate';
                    $shipping_type_widget='shipping_offered_by_flatrate';
                }
                
                // USPS shipping calculations are done here, if applicable.
                if($shipping_type=='shipping_offered_by_usps' || $shipping_type_widget=='shipping_offered_by_usps') {
                    $table_name_products = $wpdb->prefix . "wpstorecart_products";
                    $totalweight = 0;

                                    // IF ANY ITEMS IN THE CART
                                    if($cart->itemcount > 0) {

                                            $newsplit = explode('-', $item['id'] );
                                            $item['id'] = $newsplit[0];

                                            // DISPLAY LINE ITEMS
                                            foreach($cart->get_contents() as $item) {
                                                $results = $wpdb->get_results('SELECT `weight` FROM `'.$table_name_products.'` WHERE `primkey`='.$item['id'].';', ARRAY_N);
                                                $totalweight = $totalweight + $results[0][0];
                                                unset($results);
                                            }

                                    }

                    if(!isset($_SESSION)) {
                            @session_start();
                    }
                    $_SESSION['wpsc_zipcode'] = $_POST['wpsc-zipcode-input'];

                    // USPS
                    $totalshippingcalculated = $wpStoreCart->USPSParcelRate($totalweight, $_SESSION['wpsc_zipcode'] );

                    $usps_shipping_total = number_format($totalshippingcalculated, 2, '.', '');
                    
                }

                // Allows us to bypass registration and have guest only checkout
                if($devOptions['requireregistration']=='false') {
                    if(@isset($_SESSION['wpsc_email'])) {
                        $purchaser_user_id = 0;
                        $purchaser_email = $wpdb->escape($_SESSION['wpsc_email']);
                        $purchasing_display_name = 'Guest ('.$_SERVER['REMOTE_ADDR'].')';
                    } else {
                        $purchaser_user_id = $current_user->ID;
                        $purchaser_email = $current_user->user_email;
                        $purchasing_display_name = '%user_display_name_with_link%';
                    }
                } else {
                        $purchaser_user_id = $current_user->ID;
                        $purchaser_email = $current_user->user_email;
                        $purchasing_display_name = '%user_display_name_with_link%';
                }

                /**
                 *
                 * Calculate taxes
                 *
                 * @global <type> $wpdb
                 * @global <type> $devOptions
                 */
                function calculateTaxes($theTotal) {
                    global $wpdb, $devOptions, $wpStoreCart;

                    $fields = $wpStoreCart->grab_custom_reg_fields();
                    $taxstates = false;
                    $taxcountries = false;
                    foreach ($fields as $field) {
                        $specific_items = explode("||", $field['value']);
                            if($specific_items[2]=='taxstates') {
                                $taxstates = true;
                            }
                            if($specific_items[2]=='taxcountries') {
                                $taxcountries = true;
                            }
                    }

                    if($taxstates || $taxcountries) {

                        // Tax is calculated
                        $mastertax = 0.0;
                        $taxamount = 0;

                        if($taxstates || $taxcountries) {
                            $table_name33 = $wpdb->prefix . "wpstorecart_meta";
                            $grabrecord = "SELECT * FROM `{$table_name33}` WHERE `type`='tax' ORDER BY `primkey` ASC;";

                            $results = $wpdb->get_results( $grabrecord , ARRAY_A );
                            if(isset($results)) {
                                    foreach ($results as $result) {
                                        $calculateTaxes = false;
                                        $exploder = explode('||', $result['value']);
                                        foreach ($exploder as $exploded) {
                                            $exploderInd = explode(',', $exploder[2]);
                                            foreach ($exploderInd as $exploderEnd) {
                                                if(trim($exploderEnd)==trim(get_the_author_meta("taxstate", wp_get_current_user()->ID))) {
                                                    $calculateTaxes = true;
                                                } else {
                                                    if (isset($_COOKIE["taxstate"])) {
                                                        if($exploderEnd==trim($_COOKIE["taxstate"])) {
                                                            $calculateTaxes = true;
                                                        }
                                                    }
                                                }
                                            }
                                        }


                                        if($calculateTaxes){
                                            $mastertax = $mastertax + $exploder[3];
                                        }
                                        $calculateTaxes = false;


                                        foreach ($exploder as $exploded) {
                                            $exploderInd = explode(',', $exploder[1]);
                                            foreach ($exploderInd as $exploderEnd) {
                                                if(trim($exploderEnd)==trim(get_the_author_meta("taxcountries", wp_get_current_user()->ID))) {
                                                    $calculateTaxes = true;
                                                } else {
                                                    if (isset($_COOKIE["taxcountries"])) {
                                                        if($exploderEnd==trim($_COOKIE["taxcountries"])) {
                                                            $calculateTaxes = true;
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        if($calculateTaxes){
                                            $mastertax = $mastertax + $exploder[3];
                                        }
                                        $calculateTaxes = false;

                                    }
                            }

                        }
                        

                        if($mastertax > 0) {
                            $taxamount = $theTotal * ($mastertax /100);
                        }
                        return number_format($taxamount,2, '.', '');

                    } else {
                        // Taxes aren't enabled or are incorrectly configured
                        return 0;
                    }
                }


                $paymentGateway = 'checkmoneyorder';

                if(@isset($_POST['paymentGateway'])) {
                    $paymentGateway = $wpdb->escape($_POST['paymentGateway']);
                }

                if($paymentGateway == 'checkmoneyorder') {
                    $cartContents = '';
                    $totalPrice = 0;
                    $totalShipping = 0;
                    $amountToSubtractFromCart = 0;
                    foreach ($cart->get_contents() as $item) {

                            if(($devOptions['storetype']!='Digital Goods Only' && $devOptions['flatrateshipping']=='individual') && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                // Implement shipping here if needed
                                $table_name = $wpdb->prefix . "wpstorecart_products";
                                $results = $wpdb->get_results( "SELECT `shipping` FROM {$table_name} WHERE `primkey`={$item['id']} LIMIT 0, 1;", ARRAY_A );
                                if(isset($results)) {
                                    if($results[0]['shipping']!='0.00') {
                                        $totalShipping = $totalShipping + round($results[0]['shipping'] * $item['qty'], 2);
                                    }
                                }
                            } else {
                                $totalShipping = 0;
                            }

                            if($devOptions['flatrateshipping']=='all_global' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $totalShipping = $devOptions['flatrateamount'];
                            }
                            if($devOptions['flatrateshipping']=='all_single' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $totalShipping = round($devOptions['flatrateamount'] * $item['qty'], 2);
                            }
                            if($shipping_type=='shipping_offered_by_usps' || $shipping_type_widget=='shipping_offered_by_usps') {
                                    $totalShipping = $usps_shipping_total; // We use the calculated USPS shipping total if applicable
                            }

                            // Check for a coupon
                            if(@!isset($_SESSION)) {
                                    @session_start();
                            }
                            if(@$_SESSION['validcouponid']==$item['id']) {
                                @$amountToSubtractFromCart  = $_SESSION['validcouponamount'];
                            }


                            $cartContents = $cartContents . $item['id'] .'*'.$item['qty'].',';
                            $totalPrice = $totalPrice + ($item['price'] * $item['qty']);

                    }

                    $totalPrice = $totalPrice - $amountToSubtractFromCart; // Apply the coupon plus shipping
                    $cartContents = $cartContents . '0*0';

                    // Insert the order into the database
                    $table_name = $wpdb->prefix . "wpstorecart_orders";
                    $timestamp = date('Ymd');
                    if(!isset($_COOKIE['wpscPROaff']) || !is_numeric($_COOKIE['wpscPROaff'])) {
                        $affiliateid = 0;
                    } else {
                        $affiliateid = $_COOKIE['wpscPROaff'];
                        //setcookie ("wpscPROaff", "", time() - 3600); // Remove the affiliate ID
                    }
                    $insert = "
                    INSERT INTO `{$table_name}`
                    (`primkey`, `orderstatus`, `cartcontents`, `paymentprocessor`, `price`, `shipping`,
                    `wpuser`, `email`, `affiliate`, `date`) VALUES
                    (NULL, 'Pending', '{$cartContents}', 'Check/Money Order', '{$totalPrice}', '{$totalShipping}', '{$purchaser_user_id}', '{$purchaser_email}', '{$affiliateid}', '{$timestamp}');
                    ";

                    $results = $wpdb->query( $insert );
                    $lastID = $wpdb->insert_id;
                    if(isset($_COOKIE['wpscPROaff']) || is_numeric($_COOKIE['wpscPROaff'])) { // More affiliate code
                        $wpdb->query( "INSERT INTO `{$wpdb->prefix}wpstorecart_meta` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '0.00', 'affiliatepayment', '{$lastID}');");
                    }
                    $keytoedit = $lastID;

                    // Tax
                    $theTaxAmount = calculateTaxes($totalPrice + $totalShipping);
                    if($theTaxAmount > 0) {
                        $table_name_meta = $wpdb->prefix . "wpstorecart_meta";
                        $sql = "INSERT INTO `{$table_name_meta}` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '{$theTaxAmount}', 'ordertax', '{$lastID}');";
                        $wpdb->query( $sql );
                    }

                    $totalPrice = number_format($totalPrice  + $totalShipping + $theTaxAmount, 2, '.', '') ; // Apply the coupon plus shipping

                    $cart->empty_cart();
                    
                    @header ('HTTP/1.1 301 Moved Permanently');
                    if(strpos(get_permalink($devOptions['mainpage']),'?')===false) {
                        @header ('Location: '.get_permalink($devOptions['mainpage']).'?wpsc=manual&order='.$keytoedit.'&price='.$totalPrice);
                    } else {
                        @header ('Location: '.get_permalink($devOptions['mainpage']).'&wpsc=manual&order='.$keytoedit.'&price='.$totalPrice);
                    }

                    echo '<script type="text/javascript">
                    /* <![CDATA[ */';
                    if(strpos(get_permalink($devOptions['mainpage']),'?')===false) {
                        echo 'window.location = "'.get_permalink($devOptions['mainpage']).'?wpsc=manual&order='.$keytoedit.'&price='.$totalPrice.'";';
                    } else {
                        echo 'window.location = "'.get_permalink($devOptions['mainpage']).'&wpsc=manual&order='.$keytoedit.'&price='.$totalPrice.'";';
                    }
                    echo '/* ]]> */
                    </script>
                    ';
                }
                
                if($paymentGateway == 'authorize.net') {
                    include_once(WP_PLUGIN_DIR.'/wpstorecart/php/payment/Authorize.php');
                    $paymentGatewayOptions['ipn'] = plugins_url().'/wpstorecart/php/payment/authorize_ipn.php';
                    $paymentGatewayOptions['success'] = plugins_url().'/wpstorecart/php/payment/authorize_success.php';
                    $paymentGatewayOptions['authorizenettestmode'] = $devOptions['authorizenettestmode'];
                    $paymentGatewayOptions['authorizenetemail'] = $devOptions['authorizenetemail'];
                    $paymentGatewayOptions['authorizenetsecretkey'] = $devOptions['authorizenetsecretkey'];
                    $paymentGatewayOptions['theCartNames'] = '';
                    $paymentGatewayOptions['theCartPrice'] = 0.00;
                    $cartContents = '';
                    $paymentGatewayOptions['totalPrice'] = 0;
                    $paymentGatewayOptions['totalShipping'] = 0;
                    foreach ($cart->get_contents() as $item) {
                            $paymentGatewayOptions['theCartNames'] = $paymentGatewayOptions['theCartNames'] . $item['name'] .' x'.$item['qty'].', ';
                            $paymentGatewayOptions['theCartPrice'] = $paymentGatewayOptions['theCartPrice'] + ($item['price'] * $item['qty']);

                            if(($devOptions['storetype']!='Digital Goods Only' && $devOptions['flatrateshipping']=='individual') && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                // Implement shipping here if needed
                                $table_name = $wpdb->prefix . "wpstorecart_products";
                                $results = $wpdb->get_results( "SELECT `shipping` FROM {$table_name} WHERE `primkey`={$item['id']} LIMIT 0, 1;", ARRAY_A );
                                if(isset($results)) {
                                    if($results[0]['shipping']!='0.00') {
                                        $paymentGatewayOptions['totalShipping'] = $paymentGatewayOptions['totalShipping'] + round($results[0]['shipping'] * $item['qty'], 2);
                                    }
                                }
                            } else {
                                $paymentGatewayOptions['totalShipping'] = 0;
                            }

                            if($devOptions['flatrateshipping']=='all_global' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $paymentGatewayOptions['totalShipping'] = $devOptions['flatrateamount'];
                            }
                            if($devOptions['flatrateshipping']=='all_single' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $paymentGatewayOptions['totalShipping'] = round($devOptions['flatrateamount'] * $item['qty'], 2);
                            }
                            if($shipping_type=='shipping_offered_by_usps' || $shipping_type_widget=='shipping_offered_by_usps') {
                                    $paymentGatewayOptions['totalShipping'] = $usps_shipping_total; // We use the calculated USPS shipping total if applicable
                            }

                            // Check for a coupon
                            if(@!isset($_SESSION)) {
                                    @session_start();
                            }
                            if(@$_SESSION['validcouponid']==$item['id']) {
                                $paymentGatewayOptions['theCartPrice'] = $paymentGatewayOptions['theCartPrice'] - $_SESSION['validcouponamount'];
                            }


                            $cartContents = $cartContents . $item['id'] .'*'.$item['qty'].',';
                            $totalPrice = $totalPrice + ($item['price'] * $item['qty']);

                    }

                    $paymentGatewayOptions['theCartNames'] = $paymentGatewayOptions['theCartNames'] . 'shipping: '.$paymentGatewayOptions['totalShipping'];

                    $cartContents = $cartContents . '0*0';

                    // Insert the order into the database
                    $table_name = $wpdb->prefix . "wpstorecart_orders";
                    $timestamp = date('Ymd');
                    if(!isset($_COOKIE['wpscPROaff']) || !is_numeric($_COOKIE['wpscPROaff'])) {
                        $affiliateid = 0;
                    } else {
                        $affiliateid = $_COOKIE['wpscPROaff'];
                        //setcookie ("wpscPROaff", "", time() - 3600); // Remove the affiliate ID
                    }
                    $paymentGatewayOptions['userid'] = $purchaser_user_id;

                    $insert = "
                    INSERT INTO `{$table_name}`
                    (`primkey`, `orderstatus`, `cartcontents`, `paymentprocessor`, `price`, `shipping`,
                    `wpuser`, `email`, `affiliate`, `date`) VALUES
                    (NULL, 'Pending', '{$cartContents}', 'Authorize.Net', '{$paymentGatewayOptions['theCartPrice']}', '{$paymentGatewayOptions['totalShipping']}', '{$purchaser_user_id}', '{$purchaser_email}', '{$affiliateid}', '{$timestamp}');
                    ";

                    $results = $wpdb->query( $insert );
                    $lastID = $wpdb->insert_id;
                    if(isset($_COOKIE['wpscPROaff']) || is_numeric($_COOKIE['wpscPROaff'])) { // More affiliate code
                        $wpdb->query( "INSERT INTO `{$wpdb->prefix}wpstorecart_meta` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '0.00', 'affiliatepayment', '{$lastID}');");
                    }
                    $keytoedit = $lastID;

                    // Tax
                    $theTaxAmount = calculateTaxes($paymentGatewayOptions['theCartPrice'] + $paymentGatewayOptions['totalShipping']);
                    if($theTaxAmount > 0) {
                        $table_name_meta = $wpdb->prefix . "wpstorecart_meta";
                        $sql = "INSERT INTO `{$table_name_meta}` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '{$theTaxAmount}', 'ordertax', '{$lastID}');";
                        $wpdb->query( $sql );
                    }

                    // Specify any custom value, here we send the primkey of the order record
                    $paymentGatewayOptions['invoice'] = $lastID;
                    $paymentGatewayOptions['theCartPrice'] = number_format($paymentGatewayOptions['theCartPrice'] + $paymentGatewayOptions['totalShipping'] + $theTaxAmount,2, '.', '');

                    //
                    $cart->empty_cart();
                    $paymentGatewayOptions['path']=WP_PLUGIN_DIR.'/wpsc-payments-pro/saStoreCartPro/';
                    include_once(WP_PLUGIN_DIR.'/wpsc-payments-pro/saStoreCartPro/payments.pro.php');
                }

                if($paymentGateway == '2checkout') {
                    include_once(WP_PLUGIN_DIR.'/wpstorecart/php/payment/TwoCo.php');
                    $paymentGatewayOptions['ipn'] = plugins_url().'/wpstorecart/php/payment/2co_ipn.php';
                    $paymentGatewayOptions['2checkouttestmode'] = $devOptions['2checkouttestmode'];
                    $paymentGatewayOptions['2checkoutemail'] = $devOptions['2checkoutemail'];
                    $paymentGatewayOptions['theCartNames'] = '';
                    $paymentGatewayOptions['theCartPrice'] = 0.00;
                    $cartContents = '';
                    $paymentGatewayOptions['totalPrice'] = 0;
                    $paymentGatewayOptions['totalShipping'] = 0;
                    foreach ($cart->get_contents() as $item) {
                            $paymentGatewayOptions['theCartNames'] = $paymentGatewayOptions['theCartNames'] . $item['name'] .' x'.$item['qty'].', ';
                            $paymentGatewayOptions['theCartPrice'] = $paymentGatewayOptions['theCartPrice'] + ($item['price'] * $item['qty']);

                            if(($devOptions['storetype']!='Digital Goods Only' && $devOptions['flatrateshipping']=='individual') && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                // Implement shipping here if needed
                                $table_name = $wpdb->prefix . "wpstorecart_products";
                                $results = $wpdb->get_results( "SELECT `shipping` FROM {$table_name} WHERE `primkey`={$item['id']} LIMIT 0, 1;", ARRAY_A );
                                if(isset($results)) {
                                    if($results[0]['shipping']!='0.00') {
                                        $paymentGatewayOptions['totalShipping'] = $paymentGatewayOptions['totalShipping'] + round($results[0]['shipping'] * $item['qty'], 2);
                                    }
                                }
                            } else {
                                $paymentGatewayOptions['totalShipping'] = 0;
                            }
                            if($devOptions['flatrateshipping']=='all_global' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $paymentGatewayOptions['totalShipping'] = $devOptions['flatrateamount'];
                            }
                            if($devOptions['flatrateshipping']=='all_single' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $paymentGatewayOptions['totalShipping'] = round($devOptions['flatrateamount'] * $item['qty'], 2);
                            }
                            if($shipping_type=='shipping_offered_by_usps' || $shipping_type_widget=='shipping_offered_by_usps') {
                                    $paymentGatewayOptions['totalShipping'] = $usps_shipping_total; // We use the calculated USPS shipping total if applicable
                            }

                            // Check for a coupon
                            if(@!isset($_SESSION)) {
                                    @session_start();
                            }
                            if(@$_SESSION['validcouponid']==$item['id']) {
                                $paymentGatewayOptions['theCartPrice'] = $paymentGatewayOptions['theCartPrice'] - $_SESSION['validcouponamount'];
                            }


                            $cartContents = $cartContents . $item['id'] .'*'.$item['qty'].',';
                            $totalPrice = $totalPrice + ($item['price'] * $item['qty']);

                    }

                    $paymentGatewayOptions['theCartNames'] = $paymentGatewayOptions['theCartNames'] . 'shipping: '.$paymentGatewayOptions['totalShipping'];

                    $cartContents = $cartContents . '0*0';

                    // Insert the order into the database
                    $table_name = $wpdb->prefix . "wpstorecart_orders";
                    $timestamp = date('Ymd');
                    if(!isset($_COOKIE['wpscPROaff']) || !is_numeric($_COOKIE['wpscPROaff'])) {
                        $affiliateid = 0;
                    } else {
                        $affiliateid = $_COOKIE['wpscPROaff'];
                        //setcookie ("wpscPROaff", "", time() - 3600); // Remove the affiliate ID
                    }
                    $paymentGatewayOptions['userid'] = $purchaser_user_id;

                    $insert = "
                    INSERT INTO `{$table_name}`
                    (`primkey`, `orderstatus`, `cartcontents`, `paymentprocessor`, `price`, `shipping`,
                    `wpuser`, `email`, `affiliate`, `date`) VALUES
                    (NULL, 'Pending', '{$cartContents}', '2CheckOut', '{$paymentGatewayOptions['theCartPrice']}', '{$paymentGatewayOptions['totalShipping']}', '{$purchaser_user_id}', '{$purchaser_email}', '{$affiliateid}', '{$timestamp}');
                    ";

                    $results = $wpdb->query( $insert );
                    $lastID = $wpdb->insert_id;
                    if(isset($_COOKIE['wpscPROaff']) || is_numeric($_COOKIE['wpscPROaff'])) { // More affiliate code
                        $wpdb->query( "INSERT INTO `{$wpdb->prefix}wpstorecart_meta` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '0.00', 'affiliatepayment', '{$lastID}');");
                    }
                    $keytoedit = $lastID;

                    // Tax
                    $theTaxAmount = calculateTaxes($paymentGatewayOptions['theCartPrice'] + $paymentGatewayOptions['totalShipping']);
                    if($theTaxAmount > 0) {
                        $table_name_meta = $wpdb->prefix . "wpstorecart_meta";
                        $sql = "INSERT INTO `{$table_name_meta}` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '{$theTaxAmount}', 'ordertax', '{$lastID}');";
                        $wpdb->query( $sql );
                    }

                    $paymentGatewayOptions['theCartPrice'] = number_format($paymentGatewayOptions['theCartPrice'] + $paymentGatewayOptions['totalShipping'] + $theTaxAmount,2, '.', '');

                    // Specify any custom value, here we send the primkey of the order record
                    $paymentGatewayOptions['invoice'] = $lastID;

                    $cart->empty_cart();

                    $paymentGatewayOptions['path']=WP_PLUGIN_DIR.'/wpsc-payments-pro/saStoreCartPro/';
                    $paymentGatewayOptions['shortpath']=WP_PLUGIN_DIR.'/wpsc-payments-pro/';
                    include_once(WP_PLUGIN_DIR.'/wpsc-payments-pro/saStoreCartPro/payments.pro.php');
                }





                if($paymentGateway == 'moneybookers') {
                    $paymentGatewayOptions['ipn'] = plugins_url().'/wpsc-payments-pro/moneybookers/mb_ipn.php';
                    $paymentGatewayOptions['mb_login'] = $devOptions['mb_login'];
                    $paymentGatewayOptions['mb_secretword'] = $devOptions['mb_secretword'];
                    $paymentGatewayOptions['mb_logo'] = $devOptions['mb_logo'];
                    $paymentGatewayOptions['mb_currency'] = $devOptions['mb_currency'];
                    $paymentGatewayOptions['theCartNames'] = '';
                    $paymentGatewayOptions['theCartPrice'] = 0.00;
                    $cartContents = '';
                    $paymentGatewayOptions['totalPrice'] = 0;
                    $paymentGatewayOptions['totalShipping'] = 0;
                    foreach ($cart->get_contents() as $item) {
                            $paymentGatewayOptions['theCartNames'] = $paymentGatewayOptions['theCartNames'] . $item['name'] .' x'.$item['qty'].', ';
                            $paymentGatewayOptions['theCartPrice'] = $paymentGatewayOptions['theCartPrice'] + ($item['price'] * $item['qty']);

                            if(($devOptions['storetype']!='Digital Goods Only' && $devOptions['flatrateshipping']=='individual') && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                // Implement shipping here if needed
                                $table_name = $wpdb->prefix . "wpstorecart_products";
                                $results = $wpdb->get_results( "SELECT `shipping` FROM {$table_name} WHERE `primkey`={$item['id']} LIMIT 0, 1;", ARRAY_A );
                                if(isset($results)) {
                                    if($results[0]['shipping']!='0.00') {
                                        $paymentGatewayOptions['totalShipping'] = $paymentGatewayOptions['totalShipping'] + round($results[0]['shipping'] * $item['qty'], 2);
                                    }
                                }
                            } else {
                                $paymentGatewayOptions['totalShipping'] = 0;
                            }

                            if($devOptions['flatrateshipping']=='all_global' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $paymentGatewayOptions['totalShipping'] = $devOptions['flatrateamount'];
                            }
                            if($devOptions['flatrateshipping']=='all_single' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $paymentGatewayOptions['totalShipping'] = round($devOptions['flatrateamount'] * $item['qty'], 2);
                            }
                            if($shipping_type=='shipping_offered_by_usps' || $shipping_type_widget=='shipping_offered_by_usps') {
                                    $paymentGatewayOptions['totalShipping'] = $usps_shipping_total; // We use the calculated USPS shipping total if applicable
                            }

                            // Check for a coupon
                            if(@!isset($_SESSION)) {
                                    @session_start();
                            }
                            if(@$_SESSION['validcouponid']==$item['id']) {
                                $paymentGatewayOptions['theCartPrice'] = $paymentGatewayOptions['theCartPrice'] - $_SESSION['validcouponamount'];
                            }


                            $cartContents = $cartContents . $item['id'] .'*'.$item['qty'].',';
                            $totalPrice = $totalPrice + ($item['price'] * $item['qty']);

                    }

                    $paymentGatewayOptions['theCartNames'] = $paymentGatewayOptions['theCartNames'] . 'shipping: '.$paymentGatewayOptions['totalShipping'];

                    $cartContents = $cartContents . '0*0';

                    // Insert the order into the database
                    $table_name = $wpdb->prefix . "wpstorecart_orders";
                    $timestamp = date('Ymd');
                    if(!isset($_COOKIE['wpscPROaff']) || !is_numeric($_COOKIE['wpscPROaff'])) {
                        $affiliateid = 0;
                    } else {
                        $affiliateid = $_COOKIE['wpscPROaff'];
                        //setcookie ("wpscPROaff", "", time() - 3600); // Remove the affiliate ID
                    }
                    $paymentGatewayOptions['userid'] = $purchaser_user_id;

                    $insert = "
                    INSERT INTO `{$table_name}`
                    (`primkey`, `orderstatus`, `cartcontents`, `paymentprocessor`, `price`, `shipping`,
                    `wpuser`, `email`, `affiliate`, `date`) VALUES
                    (NULL, 'Pending', '{$cartContents}', 'Skrill/Moneybookers', '{$paymentGatewayOptions['theCartPrice']}', '{$paymentGatewayOptions['totalShipping']}', '{$purchaser_user_id}', '{$purchaser_email}', '{$affiliateid}', '{$timestamp}');
                    ";

                    $results = $wpdb->query( $insert );
                    $lastID = $wpdb->insert_id;
                    if(isset($_COOKIE['wpscPROaff']) || is_numeric($_COOKIE['wpscPROaff'])) { // More affiliate code
                        $wpdb->query( "INSERT INTO `{$wpdb->prefix}wpstorecart_meta` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '0.00', 'affiliatepayment', '{$lastID}');");
                    }
                    $keytoedit = $lastID;

                    // Tax
                    $theTaxAmount = calculateTaxes($paymentGatewayOptions['theCartPrice'] + $paymentGatewayOptions['totalShipping']);
                    if($theTaxAmount > 0) {
                        $table_name_meta = $wpdb->prefix . "wpstorecart_meta";
                        $sql = "INSERT INTO `{$table_name_meta}` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '{$theTaxAmount}', 'ordertax', '{$lastID}');";
                        $wpdb->query( $sql );
                    }

                    // Specify any custom value, here we send the primkey of the order record
                    $paymentGatewayOptions['invoice'] = $lastID;
                    $paymentGatewayOptions['theCartPrice'] = number_format($paymentGatewayOptions['theCartPrice'] + $paymentGatewayOptions['totalShipping'] + $theTaxAmount,2, '.', '');

                    //
                    $cart->empty_cart();
                    $paymentGatewayOptions['path']=WP_PLUGIN_DIR.'/wpsc-payments-pro/saStoreCartPro/';
                    $paymentGatewayOptions['shortpath']=WP_PLUGIN_DIR.'/wpsc-payments-pro/';
                    echo '<center><img src="../../../images/redirect.gif" alt="redirecting" />';
                    @include_once(WP_PLUGIN_DIR.'/wpsc-payments-pro/moneybookers/mb_start.php');
                }










                if($paymentGateway == 'libertyreserve') {
                    @include_once(WP_PLUGIN_DIR.'/wpsc-payments-pro/saStoreCartPro/libertyreserve/lb.php');
                    $paymentGatewayOptions['ipn'] = plugins_url().'/wpsc-payments-pro/lr/lbi.php';
                    $paymentGatewayOptions['success'] = plugins_url().'/wpsc-payments-pro/lr/lbs.php';
                    if(strpos(get_permalink($devOptions['mainpage']),'?')===false) {
                        $failedpermalink = get_permalink($devOptions['mainpage']) .'?wpsc=failed';
                    } else {
                        $failedpermalink = get_permalink($devOptions['mainpage']) .'&wpsc=failed';
                    }
                    $paymentGatewayOptions['failure'] = $failedpermalink;
                    $paymentGatewayOptions['libertyreserveaccount'] = $devOptions['libertyreserveaccount'];
                    $paymentGatewayOptions['libertyreservestore'] = $devOptions['libertyreservestore'];
                    $paymentGatewayOptions['authorizenetsecretkey'] = $devOptions['authorizenetsecretkey'];
                    $paymentGatewayOptions['theCartNames'] = '';
                    $paymentGatewayOptions['theCartPrice'] = 0.00;
                    $cartContents = '';
                    $paymentGatewayOptions['totalPrice'] = 0;
                    $paymentGatewayOptions['totalShipping'] = 0;
                    foreach ($cart->get_contents() as $item) {
                            $paymentGatewayOptions['theCartNames'] = $paymentGatewayOptions['theCartNames'] . $item['name'] .' x'.$item['qty'].', ';
                            $paymentGatewayOptions['theCartPrice'] = $paymentGatewayOptions['theCartPrice'] + ($item['price'] * $item['qty']);

                            if(($devOptions['storetype']!='Digital Goods Only' && $devOptions['flatrateshipping']=='individual') && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                // Implement shipping here if needed
                                $table_name = $wpdb->prefix . "wpstorecart_products";
                                $results = $wpdb->get_results( "SELECT `shipping` FROM {$table_name} WHERE `primkey`={$item['id']} LIMIT 0, 1;", ARRAY_A );
                                if(isset($results)) {
                                    if($results[0]['shipping']!='0.00') {
                                        $paymentGatewayOptions['totalShipping'] = $paymentGatewayOptions['totalShipping'] + round($results[0]['shipping'] * $item['qty'], 2);
                                    }
                                }
                            } else {
                                $paymentGatewayOptions['totalShipping'] = 0;
                            }

                            if($devOptions['flatrateshipping']=='all_global' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $paymentGatewayOptions['totalShipping'] = $devOptions['flatrateamount'];
                            }
                            if($devOptions['flatrateshipping']=='all_single' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $paymentGatewayOptions['totalShipping'] = round($devOptions['flatrateamount'] * $item['qty'], 2);
                            }
                            if($shipping_type=='shipping_offered_by_usps' || $shipping_type_widget=='shipping_offered_by_usps') {
                                    $paymentGatewayOptions['totalShipping'] = $usps_shipping_total; // We use the calculated USPS shipping total if applicable
                            }

                            // Check for a coupon
                            if(@!isset($_SESSION)) {
                                    @session_start();
                            }
                            if(@$_SESSION['validcouponid']==$item['id']) {
                                $paymentGatewayOptions['theCartPrice'] = $paymentGatewayOptions['theCartPrice'] - $_SESSION['validcouponamount'];
                            }


                            $cartContents = $cartContents . $item['id'] .'*'.$item['qty'].',';
                            $totalPrice = $totalPrice + ($item['price'] * $item['qty']);

                    }

                    $paymentGatewayOptions['theCartNames'] = $paymentGatewayOptions['theCartNames'] . 'shipping: '.$paymentGatewayOptions['totalShipping'];

                    $cartContents = $cartContents . '0*0';

                    // Insert the order into the database
                    $table_name = $wpdb->prefix . "wpstorecart_orders";
                    $timestamp = date('Ymd');
                    if(!isset($_COOKIE['wpscPROaff']) || !is_numeric($_COOKIE['wpscPROaff'])) {
                        $affiliateid = 0;
                    } else {
                        $affiliateid = $_COOKIE['wpscPROaff'];
                        //setcookie ("wpscPROaff", "", time() - 3600); // Remove the affiliate ID
                    }
                    $paymentGatewayOptions['userid'] = $purchaser_user_id;

                    $insert = "
                    INSERT INTO `{$table_name}`
                    (`primkey`, `orderstatus`, `cartcontents`, `paymentprocessor`, `price`, `shipping`,
                    `wpuser`, `email`, `affiliate`, `date`) VALUES
                    (NULL, 'Pending', '{$cartContents}', 'Liberty Reserve', '{$paymentGatewayOptions['theCartPrice']}', '{$paymentGatewayOptions['totalShipping']}', '{$purchaser_user_id}', '{$purchaser_email}', '{$affiliateid}', '{$timestamp}');
                    ";

                    $results = $wpdb->query( $insert );
                    $lastID = $wpdb->insert_id;
                    if(isset($_COOKIE['wpscPROaff']) || is_numeric($_COOKIE['wpscPROaff'])) { // More affiliate code
                        $wpdb->query( "INSERT INTO `{$wpdb->prefix}wpstorecart_meta` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '0.00', 'affiliatepayment', '{$lastID}');");
                    }
                    $keytoedit = $lastID;

                    // Tax
                    $theTaxAmount = calculateTaxes($paymentGatewayOptions['theCartPrice'] + $paymentGatewayOptions['totalShipping']);
                    if($theTaxAmount > 0) {
                        $table_name_meta = $wpdb->prefix . "wpstorecart_meta";
                        $sql = "INSERT INTO `{$table_name_meta}` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '{$theTaxAmount}', 'ordertax', '{$lastID}');";
                        $wpdb->query( $sql );
                    }

                    // Specify any custom value, here we send the primkey of the order record
                    $paymentGatewayOptions['invoice'] = $lastID;
                    $paymentGatewayOptions['theCartPrice'] = number_format($paymentGatewayOptions['theCartPrice'] + $paymentGatewayOptions['totalShipping'] + $theTaxAmount,2, '.', '');

                    //
                    $cart->empty_cart();
                    $paymentGatewayOptions['path']=WP_PLUGIN_DIR.'/wpsc-payments-pro/saStoreCartPro/';
                    $paymentGatewayOptions['shortpath']=WP_PLUGIN_DIR.'/wpsc-payments-pro/';
                    include_once(WP_PLUGIN_DIR.'/wpsc-payments-pro/saStoreCartPro/payments.pro.php');
                }

                // QBMS payment
                if($paymentGateway == 'qbms') {
                    error_reporting(0);
                    $paymentGatewayOptions['qbms_login'] = $devOptions['qbms_login'];
                    $paymentGatewayOptions['qbms_ticket'] = $devOptions['qbms_ticket'];
                    $paymentGatewayOptions['qbms_testingmode'] = $devOptions['qbms_testingmode'];
                    $paymentGatewayOptions['theCartNames'] = '';
                    $paymentGatewayOptions['theCartPrice'] = 0.00;
                    $cartContents = '';
                    $paymentGatewayOptions['totalPrice'] = 0;
                    $paymentGatewayOptions['totalShipping'] = 0;
                    $totalPrice = 0;
                    foreach ($cart->get_contents() as $item) {
                            $paymentGatewayOptions['theCartNames'] = $paymentGatewayOptions['theCartNames'] . $item['name'] .' x'.$item['qty'].', ';
                            $paymentGatewayOptions['theCartPrice'] = $paymentGatewayOptions['theCartPrice'] + ($item['price'] * $item['qty']);

                            if(($devOptions['storetype']!='Digital Goods Only' && $devOptions['flatrateshipping']=='individual') && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                // Implement shipping here if needed
                                $table_name = $wpdb->prefix . "wpstorecart_products";
                                $results = $wpdb->get_results( "SELECT `shipping` FROM {$table_name} WHERE `primkey`={$item['id']} LIMIT 0, 1;", ARRAY_A );
                                if(isset($results)) {
                                    if($results[0]['shipping']!='0.00') {
                                        $paymentGatewayOptions['totalShipping'] = $paymentGatewayOptions['totalShipping'] + round($results[0]['shipping'] * $item['qty'], 2);
                                    }
                                }
                            } else {
                                $paymentGatewayOptions['totalShipping'] = 0;
                            }

                            if($devOptions['flatrateshipping']=='all_global' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $paymentGatewayOptions['totalShipping'] = $devOptions['flatrateamount'];
                            }
                            if($devOptions['flatrateshipping']=='all_single' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                $paymentGatewayOptions['totalShipping'] = round($devOptions['flatrateamount'] * $item['qty'], 2);
                            }
                            if($shipping_type=='shipping_offered_by_usps' || $shipping_type_widget=='shipping_offered_by_usps') {
                                    $paymentGatewayOptions['totalShipping'] = $usps_shipping_total; // We use the calculated USPS shipping total if applicable
                            }

                            // Check for a coupon
                            if(@!isset($_SESSION)) {
                                    @session_start();
                            }
                            if(@$_SESSION['validcouponid']==$item['id']) {
                                $paymentGatewayOptions['theCartPrice'] = $paymentGatewayOptions['theCartPrice'] - $_SESSION['validcouponamount'];
                            }


                            $cartContents = $cartContents . $item['id'] .'*'.$item['qty'].',';
                            $totalPrice = $totalPrice + ($item['price'] * $item['qty']);

                    }

                    $paymentGatewayOptions['theCartNames'] = $paymentGatewayOptions['theCartNames'] . 'shipping: '.$paymentGatewayOptions['totalShipping'];

                    $cartContents = $cartContents . '0*0';

                    // Insert the order into the database
                    $table_name = $wpdb->prefix . "wpstorecart_orders";
                    $timestamp = date('Ymd');
                    if(!isset($_COOKIE['wpscPROaff']) || !is_numeric($_COOKIE['wpscPROaff'])) {
                        $affiliateid = 0;
                    } else {
                        $affiliateid = $_COOKIE['wpscPROaff'];
                        //setcookie ("wpscPROaff", "", time() - 3600); // Remove the affiliate ID
                    }
                    $paymentGatewayOptions['userid'] = $purchaser_user_id;

                    $insert = "
                    INSERT INTO `{$table_name}`
                    (`primkey`, `orderstatus`, `cartcontents`, `paymentprocessor`, `price`, `shipping`,
                    `wpuser`, `email`, `affiliate`, `date`) VALUES
                    (NULL, 'Pending', '{$cartContents}', 'Quickbooks, Intuit', '{$paymentGatewayOptions['theCartPrice']}', '{$paymentGatewayOptions['totalShipping']}', '{$purchaser_user_id}', '{$purchaser_email}', '{$affiliateid}', '{$timestamp}');
                    ";

                    $results = $wpdb->query( $insert );
                    $lastID = $wpdb->insert_id;
                    if(@isset($_COOKIE['wpscPROaff']) || @is_numeric($_COOKIE['wpscPROaff'])) { // More affiliate code
                        $wpdb->query( "INSERT INTO `{$wpdb->prefix}wpstorecart_meta` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '0.00', 'affiliatepayment', '{$lastID}');");
                    }
                    $keytoedit = $lastID;

                    // Tax
                    $theTaxAmount = calculateTaxes($paymentGatewayOptions['theCartPrice'] + $paymentGatewayOptions['totalShipping']);
                    if($theTaxAmount > 0) {
                        $table_name_meta = $wpdb->prefix . "wpstorecart_meta";
                        $sql = "INSERT INTO `{$table_name_meta}` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '{$theTaxAmount}', 'ordertax', '{$lastID}');";
                        $wpdb->query( $sql );
                    }

                    // Specify any custom value, here we send the primkey of the order record
                    $paymentGatewayOptions['invoice'] = $lastID;
                    $paymentGatewayOptions['theCartPrice'] = number_format($paymentGatewayOptions['theCartPrice'] + $paymentGatewayOptions['totalShipping'] + $theTaxAmount, 2, '.', '');
                    $paymentGatewayOptions['path']=WP_PLUGIN_DIR.'/wpsc-payments-pro/qbms/quickbooks-php-devkit/';

                    global $QBMSTransaction, $QBMSErrorMessage, $QBMSStatus, $wpstorecart_version;
                    include_once(WP_PLUGIN_DIR.'/wpsc-payments-pro/qbms/qb_start.php');
                    if($QBMSStatus == 'failedauthorize' || $QBMSStatus == 'failedcapture') {
                        if(strpos(get_permalink($devOptions['mainpage']),'?')===false) {
                            $permalink = get_permalink($devOptions['mainpage']) .'?wpsc=failed&wpscerror='.urlencode($QBMSErrorMessage);
                        } else {
                            $permalink = get_permalink($devOptions['mainpage']) .'&wpsc=failed&wpscerror='.urlencode($QBMSErrorMessage);
                        }
                        wp_safe_redirect($permalink);
                        exit();

                    }
                    if($QBMSStatus == 'capture') {
                        $table_name2 = $wpdb->prefix . "wpstorecart_products";
                        $cart->empty_cart();
                        // ALL COOL, mark the order paid
                        $sql = "UPDATE `{$table_name}` SET `orderstatus` = 'Completed' WHERE `primkey` = {$keytoedit}";
                        $wpdb->query ($sql);
                        $sql = "SELECT `cartcontents`, `email` FROM `{$table_name}` WHERE `primkey`={$keytoedit};";
                        $results = $wpdb->get_results( $sql , ARRAY_A );
                        if(isset($results)) {
                                $specific_items = explode(",", $results[0]['cartcontents']);
                                foreach($specific_items as $specific_item) {
                                        if($specific_item != '0*0') { // This is filler, all cart entries contain a 0*0 entry
                                                $current_item = explode('*', $specific_item);
                                                if(isset($current_item[0]) && isset($current_item[1])) {
                                                        $sql2 = "SELECT `primkey`, `inventory`, `useinventory` FROM `{$wpdb->prefix}wpstorecart_products` WHERE `primkey`={$current_item[0]};";
                                                        $wpStoreCart->assignSerialNumber($current_item[0], $keytoedit);
                                                        $moreresults = $wpdb->get_results( $sql2 , ARRAY_A );
                                                        if(isset($moreresults[0])){
                                                            if( $moreresults[0]['useinventory']==1) {
                                                                $newInventory = $moreresults[0]['inventory'] - $current_item[1];
                                                                $wpdb->query("UPDATE `{$table_name2}` SET `inventory` = '{$newInventory}' WHERE `primkey` = {$moreresults[0]['primkey']} LIMIT 1 ;");
                                                            }
                                                        }
                                                }
                                        }
                                }
                        }
                        if($devOptions['pcicompliant']=='true') {
                            $table_name_meta = $wpdb->prefix . "wpstorecart_meta";
                            $results = $wpdb->query("INSERT INTO `{$table_name_meta}` (`primkey`, `value`, `type`, `foreignkey`) VALUES (NULL, '".$QBMSTransData."', 'qbms_transaction_record', '{$keytoedit}');");
                        }

                         // Let's send them an email telling them their purchase was successful
                         // In case any of our lines are larger than 70 characters, we should use wordwrap()
                        $message = wordwrap($wpStoreCart->makeEmailTxt($devOptions['emailonapproval']) . $wpStoreCart->makeEmailTxt($devOptions['emailsig']), 70);

                        $headers = 'From: '.$devOptions['wpStoreCartEmail'] . "\r\n" .
                                'Reply-To: ' .$devOptions['wpStoreCartEmail']. "\r\n" .
                                'X-Mailer: PHP/wpStoreCart v'.$wpstorecart_version;

                        // Send an email when purchase is submitted
                        if(isset($results)) {
                                @ini_set("sendmail_from", $devOptions['wpStoreCartEmail']);
                                wp_mail($results[0]['email'], 'Your order has been fulfilled!', $message, $headers);
                        }

                        if(strpos(get_permalink($devOptions['mainpage']),'?')===false) {
                            $permalink = get_permalink($devOptions['mainpage']) .'?wpsc=success';
                        } else {
                            $permalink = get_permalink($devOptions['mainpage']) .'&wpsc=success';
                        }
                        wp_safe_redirect($permalink);
                        exit();

                    }
                }

                if($paymentGateway == 'paypal') {
                    // PAYPAL COUNT STARTS AT ONE INSTEAD OF ZERO
                    // Include the paypal library
                    include_once(WP_PLUGIN_DIR.'/wpstorecart/php/payment/Paypal.php');


                    // Create an instance of the paypal library
                    $myPaypal = new Paypal();

                    // Specify your paypal email
                    $myPaypal->addField('business', $devOptions['paypalemail']);

                    // Specify the currency
                    $myPaypal->addField('currency_code', $devOptions['currency_code']);

                    $myPaypal->addField('rm', '2'); // Return method = POST

                    
                    $myPaypal->addField('upload', '1');


                    if(strpos(get_permalink($devOptions['mainpage']),'?')===false) {
                        $successpermalink = get_permalink($devOptions['mainpage']) .'?wpsc=success';
                    } else {
                        $successpermalink = get_permalink($devOptions['mainpage']) .'&wpsc=success';
                    }
                    // Specify the url where paypal will send the user on success/failure
                    $myPaypal->addField('return', $successpermalink);

                    if(strpos(get_permalink($devOptions['mainpage']),'?')===false) {
                        $failedpermalink = get_permalink($devOptions['mainpage']) .'?wpsc=failed';
                    } else {
                        $failedpermalink = get_permalink($devOptions['mainpage']) .'&wpsc=failed';
                    }

                    $myPaypal->addField('cancel_return', $failedpermalink);

                    // Specify the url where paypal will send the IPN
                    $myPaypal->addField('notify_url', $devOptions['paypalipnurl']);

                    // Enable test mode if needed
                    if($devOptions['paypaltestmode']=='true') {
                            $myPaypal->enableTestMode();
                    }

                    $paypal_count = 1;
                    $items_query_string;
                    $cartContents = '';
                    $totalPrice = 0;
                    $totalShipping = 0;
                    $couponset = false;
                    $donation = false;
                    foreach ($cart->get_contents() as $item) {
                                // BUILD THE QUERY STRING
                                // Specify the product information
                                // Put the coupon coding here too
                                $myPaypal->addField('item_name_' . $paypal_count, $item['name']);
                                $myPaypal->addField('amount_' . $paypal_count, $item['price']);
                                $myPaypal->addField('item_number_' . $paypal_count, $paypal_count);
                                $myPaypal->addField('quantity_' . $paypal_count, $item['qty']);




                                // Implement shipping here if needed
                                $table_name = $wpdb->prefix . "wpstorecart_products";
                                $results = $wpdb->get_results( "SELECT `shipping`, `donation` FROM {$table_name} WHERE `primkey`={$item['id']} LIMIT 0, 1;", ARRAY_A );
                                if(isset($results)) {
                                    if($results[0]['donation']=='1') {
                                        $donation = true;
                                    }
                                    if(($devOptions['storetype']!='Digital Goods Only' && $devOptions['flatrateshipping']=='individual') && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                        if($results[0]['shipping']!='0.00') {
                                            $myPaypal->addField('shipping_' . $paypal_count, round($results[0]['shipping'] * $item['qty'],2));
                                            $totalShipping = $totalShipping + round($results[0]['shipping'] * $item['qty'], 2);
                                        }
                                    } else {
                                        $totalShipping = 0;
                                    }
                                }
                                if($devOptions['flatrateshipping']=='all_global' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                    $totalShipping = $devOptions['flatrateamount'];
                                    $myPaypal->addField('shipping_' . $paypal_count, round($totalShipping, 2));
                                }
                                if($devOptions['flatrateshipping']=='all_single' && ($shipping_type=='shipping_offered_by_flatrate' || $shipping_type_widget=='shipping_offered_by_flatrate')) {
                                    $totalShipping = round($devOptions['flatrateamount'] * $item['qty'], 2);
                                    $myPaypal->addField('shipping_' . $paypal_count, round($totalShipping, 2));
                                }


                                // Check for a coupon
                                if(@!isset($_SESSION)) {
                                        @session_start();
                                }
                                //echo 'SES: '.$_SESSION['validcouponid'] .'<br />';echo 'ID: '.$item['id'];exit();
                                if($couponset==false && (@$_SESSION['validcouponid']==$item['id'])) {
                                    if(isset($_SESSION['validcouponamount'])) {
                                        @$myPaypal->addField('discount_amount_cart', $_SESSION['validcouponamount']);
                                    }
                                    if(isset($_SESSION['validcouponpercent']) && $_SESSION['validcouponpercent'] != 0) { //
                                        $discount_priceper = round(($item['qty'] * $item['price']) * ($_SESSION['validcouponpercent'] / 100), 2);
                                        @$myPaypal->addField('discount_amount_cart', $discount_priceper);
                                    }
                                    $couponset = true;
                                }


                                $cartContents = $cartContents . $item['id'] .'*'.$item['qty'].',';
                                $totalPrice = $totalPrice + ($item['price'] * $item['qty']);
                            

                            // INCREMENT THE COUNTER
                            ++$paypal_count;
                    }

                    if($shipping_type=='shipping_offered_by_usps' || $shipping_type_widget=='shipping_offered_by_usps') {
                        $totalShipping = $usps_shipping_total; // We use the calculated USPS shipping total if applicable
                        $myPaypal->addField('shipping_1', round($totalShipping, 2));
                    }

                    if($donation==true) {
                        $myPaypal->addField('cmd', '_donations');
                    } else {
                        $myPaypal->addField('cmd', '_cart');
                    }

                    if(@isset($_SESSION['validcouponamount']) && $couponset==false) {
                        if(isset($_SESSION['validcouponamount'])) {
                            @$myPaypal->addField('discount_amount_cart', $_SESSION['validcouponamount']);
                        }
                        if(isset($_SESSION['validcouponpercent']) && $_SESSION['validcouponpercent'] != 0) { //
                            @$myPaypal->addField('discount_rate_cart', $_SESSION['validcouponpercent']);
                        }
                        $couponset = true;
                    }

                    $cartContents = $cartContents . '0*0';

                    // Insert the order into the database
                    $table_name = $wpdb->prefix . "wpstorecart_orders";
                    $timestamp = date('Ymd');
                    if(!isset($_COOKIE['wpscPROaff']) || !is_numeric($_COOKIE['wpscPROaff'])) {
                        $affiliateid = 0;
                    } else {
                        $affiliateid = $_COOKIE['wpscPROaff'];
                        //setcookie ("wpscPROaff", "", time() - 3600); // Remove the affiliate ID
                    }
                    $insert = "
                    INSERT INTO `{$table_name}`
                    (`primkey`, `orderstatus`, `cartcontents`, `paymentprocessor`, `price`, `shipping`,
                    `wpuser`, `email`, `affiliate`, `date`) VALUES
                    (NULL, 'Pending', '{$cartContents}', 'PayPal', '{$totalPrice}', '{$totalShipping}', '{$purchaser_user_id}', '{$purchaser_email}', '{$affiliateid}', '{$timestamp}');
                    ";

                    $results = $wpdb->query( $insert );
                    $lastID = $wpdb->insert_id;
                    if(isset($_COOKIE['wpscPROaff']) || is_numeric($_COOKIE['wpscPROaff'])) { // More affiliate code
                        $wpdb->query( "INSERT INTO `{$wpdb->prefix}wpstorecart_meta` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '0.00', 'affiliatepayment', '{$lastID}');");
                    }
                    $keytoedit = $lastID;

                    // Specify any custom value, here we send the primkey of the order record
                    $myPaypal->addField('custom', $lastID);

                    // Tax
                    $theTaxAmount = calculateTaxes($totalPrice + $totalShipping);
                    if($theTaxAmount > 0) {
                        $myPaypal->addField('tax_cart', $theTaxAmount);
                        $table_name_meta = $wpdb->prefix . "wpstorecart_meta";
                        $sql = "INSERT INTO `{$table_name_meta}` (`primkey` ,`value` ,`type` ,`foreignkey`)VALUES (NULL , '{$theTaxAmount}', 'ordertax', '{$lastID}');";
                        $wpdb->query( $sql );
                    }


                    //

                    if(isset($wpsc['paypal_id']))
                            {
                            // REDIRECT TO PAYPAL WITH MERCHANT ID AND CART CONTENTS
                            // Let's start the train!
                            echo '<center><img src="../../../images/redirect.gif" alt="redirecting" />';
                            $myPaypal->submitPayment();
                            //$myPaypal->echoFields(); // Uncomment this and comment the line above if you need to diagnose a problem with the paypal payment gateway
                            //if(session_id()){echo "Session started!";}else{echo "Session not started!";} // To see if sessions are started here, uncomment this and comment the line that is 2 above this one, which reads: $myPaypal->submitPayment();
                            //print_r ($_SESSION); print_r ($cart); // To see what's in the cart and sessions

                            echo '</center>';
                            // EMPTY THE CART
                            $cart->empty_cart();
                            
                            exit;
                            }
                    else
                            // THE USER HAS NOT CONFIGURED A PAYPAL ID
                            // DISPLAY THE PAYPAL URL WITH AN ERROR MESSAGE
                            {
                            echo 'PayPal integration requires a secure merchant ID.  Please add your email address to the wpStoreCart options page.<br /><br />';

                            exit;
                            }
                    }
                } // End PayPal code
	}

?>