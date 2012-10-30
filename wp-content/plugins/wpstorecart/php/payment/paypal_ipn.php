<?php

// wpStoreCart, (c) 2010 wpStoreCart.com.  All rights reserved.

global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
global $wpdb, $wpStoreCart, $wpstorecart_version;

if (!function_exists('add_action'))
{
    require_once("../../../../../wp-config.php");
}
$devOptions = $wpStoreCart->getAdminOptions();


// Include the paypal library
include_once ('Paypal.php');

// Create an instance of the paypal library
$myPaypal = new Paypal();

// Log the IPN results
$myPaypal->ipnLog = TRUE;

if($devOptions['paypaltestmode']=='true') {
        $myPaypal->enableTestMode();
}

// Check validity and write down it
if ($myPaypal->validateIpn())
{
     $keyToLookup = $myPaypal->ipnData['custom'];
     if(is_numeric($keyToLookup)) {
            $table_name = $wpdb->prefix . "wpstorecart_orders";
            $table_name2 = $wpdb->prefix . "wpstorecart_products";
            if($myPaypal->ipnData['payment_status']=='Canceled_Reversal' || $myPaypal->ipnData['payment_status']=='Completed') {
                 $insert = "
                UPDATE `{$table_name}` SET `orderstatus` = 'Completed' WHERE `primkey` ={$keyToLookup};
                ";
            } else {
                $insert = "
                UPDATE `{$table_name}` SET `orderstatus` = '{$myPaypal->ipnData['payment_status']}' WHERE `primkey` ={$keyToLookup};
                ";
            }

            $stop = false;
            if($myPaypal->ipnData['txn_type']=='subscr_signup') { // This will count against inventory, count as a new sale, and count towards affiliates
                $insert = "UPDATE `{$table_name}` SET `orderstatus` = 'Completed' WHERE `primkey` ={$keyToLookup};";
            }
            if($myPaypal->ipnData['txn_type']=='subscr_cancel') {
                $insert = '';
                $stop = true; // Don't recount this as a new sale, don't decrease inventory, don't credit affiliate
                exit();
            }
            if($myPaypal->ipnData['txn_type']=='subscr_modify') {
                $insert = '';
                $stop = true; // Don't recount this as a new sale, don't decrease inventory, don't credit affiliate
                exit();
            }
            if($myPaypal->ipnData['txn_type']=='subscr_payment') {
                $insert = "UPDATE `{$table_name}` SET `orderstatus` = 'Completed' WHERE `primkey` ={$keyToLookup};";
                $stop = true; // Don't recount this as a new sale, don't decrease inventory, don't credit affiliate
                $results = $wpdb->query( $insert );
                exit();
            }
            if($myPaypal->ipnData['txn_type']=='subscr_failed') {
                $insert = '';
                $stop = true; // Don't recount this as a new sale, don't decrease inventory, don't credit affiliate
                $results = $wpdb->query( $insert );
                exit();
            }
            if($myPaypal->ipnData['txn_type']=='subscr_eot') {
                $insert = "UPDATE `{$table_name}` SET `orderstatus` = 'Expired' WHERE `primkey` ={$keyToLookup};";
                $stop = true; // Don't recount this as a new sale, don't decrease inventory, don't credit affiliate
                $results = $wpdb->query( $insert );
                exit();
            }

            if($insert != '') {
                $results = $wpdb->query( $insert );
            }
            // If we've got a successful payment and we are using the inventory:
            if($myPaypal->ipnData['payment_status']=='Completed' && $stop == false) {
                $sql = "SELECT `cartcontents`, `email` FROM `{$table_name}` WHERE `primkey`={$keyToLookup};";
		$results = $wpdb->get_results( $sql , ARRAY_A );
                if(isset($results)) {
                    $specific_items = explode(",", $results[0]['cartcontents']);
                    foreach($specific_items as $specific_item) {
                        if($specific_item != '0*0') { // This is filler, all cart entries contain a 0*0 entry
                            $current_item = explode('*', $specific_item);
                            if(isset($current_item[0]) && isset($current_item[1])) {
                                $sql2 = "SELECT `primkey`, `inventory`, `useinventory` FROM `{$table_name2}` WHERE `primkey`={$current_item[0]};";
                                $wpStoreCart->assignSerialNumber($current_item[0], $keyToLookup);
                                $moreresults = $wpdb->get_results( $sql2 , ARRAY_A );
                                if(isset($moreresults) && $moreresults[0]['useinventory']==1) {
                                        $newInventory = $moreresults[0]['inventory'] - $current_item[1];
                                        $wpdb->query("UPDATE `{$table_name2}` SET `inventory` = '{$newInventory}' WHERE `primkey` = {$moreresults[0]['primkey']} LIMIT 1 ;");
                                }
                            }
                        }
                    }
                }

             // Let's send them an email telling them their purchase was successful
             // In case any of our lines are larger than 70 characters, we should use wordwrap()
            $message = wordwrap($wpStoreCart->makeEmailTxt($devOptions['emailonapproval']) . $wpStoreCart->makeEmailTxt($devOptions['emailsig']), 70);

            $headers = 'From: '.$devOptions['wpStoreCartEmail'] . "\r\n" .
                'Reply-To: ' .$devOptions['wpStoreCartEmail']. "\r\n" .
                'X-Mailer: PHP/wpStoreCart v'.$wpstorecart_version;


            // Send an email when purchase is submitted
            @ini_set("sendmail_from", $devOptions['wpStoreCartEmail']);
            if($current_user->ID != 0) {
                @wp_mail($current_user->user_email, 'Your order has been fulfilled!', $message, $headers);
            } else {
                // Send an email when purchase is submitted
                if(isset($results[0]['email'])) {
                    @wp_mail($results[0]['email'], 'Your order has been fulfilled!', $message, $headers);
                } else {                
                    if(@isset($_SESSION['wpsc_email'])) {
                        @wp_mail($_SESSION['wpsc_email'], 'Your order has been fulfilled!', $message, $headers);
                    } elseif(@isset($_POST['payer_email'])) {
                        @wp_mail($_POST['payer_email'], 'Your order has been fulfilled!', $message, $headers);
                    }
                }
            }

            }
     }
}