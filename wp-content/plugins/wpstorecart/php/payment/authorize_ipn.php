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

include_once ('Authorize.php');

// Create an instance of the authorize.net library
$myAuthorize = new Authorize();

// Log the IPN results
$myAuthorize->ipnLog = TRUE;

// Specify your authorize login and secret
$myAuthorize->setUserInfo($devOptions['authorizenetemail'], $devOptions['authorizenetsecretkey']);


if($devOptions['authorizenettestmode']=='true') {
    // Enable test mode if needed
    $myAuthorize->enableTestMode();
}

// Completed and approved orders have an x_response_code of 1
// Check validity and write down it
if ($myAuthorize->validateIpn())
{
    file_put_contents('authorize.txt', 'SUCCESS');
    echo 'Success!';
     $keyToLookup = $myAuthorize->ipnData['x_invoice_num'];
     if(is_numeric($keyToLookup)) {
            $table_name = $wpdb->prefix . "wpstorecart_orders";
            $table_name2 = $wpdb->prefix . "wpstorecart_products";
            $status = $myAuthorize->ipnData['x_response_code'];
            if ($myAuthorize->ipnData['x_response_code']===1 || $myAuthorize->ipnData['x_response_code']=='1') {
                $status = 'Completed';
            }

            $insert = "
            UPDATE `{$table_name}` SET `orderstatus` = '{$status}' WHERE `primkey` ={$keyToLookup};
            ";

            $results = $wpdb->query( $insert );

            // If we've got a successful payment and we are using the inventory:
            if($myAuthorize->ipnData['x_response_code']==1 ) {
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

                @ini_set("sendmail_from", $devOptions['wpStoreCartEmail']);
                // Send an email when purchase is submitted
                if(isset($results)) {
                    wp_mail($results[0]['email'], 'Your order has been fulfilled!', $message, $headers);
                }
            }
     }
}
else
{
    file_put_contents('authorize.txt', "FAILURE\n\n" . $myAuthorize->ipnData);
}