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

function wpstorecart_check_alert() {
        global $wpdb, $wpstorecart_version, $current_user, $wpStoreCart;

        $devOptions = $wpStoreCart->getAdminOptions();
        if(md5($devOptions['wpsc_api_key'] . $devOptions['wpsc_secret_hash'].'Refresher')==$_POST['hash']) {

            $table_name = $wpdb->prefix . "wpstorecart_products";
            $table_name_orders = $wpdb->prefix . "wpstorecart_orders";
            $table_name_meta = $wpdb->prefix . "wpstorecart_meta";


            $totalrecordssqlordercompleted = "SELECT COUNT(`primkey`) AS num FROM `{$table_name_orders}` WHERE `orderstatus`='Completed';";
            $totalrecordsresordercompleted = $wpdb->get_results( $totalrecordssqlordercompleted , ARRAY_A );
            if(isset($totalrecordsresordercompleted)) {
                    $totalrecordsordercompleted = $totalrecordsresordercompleted[0]['num'];
            } else {
                    $totalrecordsordercompleted = 0;
            }

            $amountknownbeforenow_sql = "SELECT `value` FROM `{$table_name_meta}` WHERE `type`='desktop_alert';";
            $amountknownbeforenow = $wpdb->get_results( $amountknownbeforenow_sql , ARRAY_A );
            if(isset($amountknownbeforenow[0]['value'])) {
                $newAmount = $totalrecordsordercompleted - $amountknownbeforenow[0]['value'];
                if($amountknownbeforenow[0]['value'] < $totalrecordsordercompleted) {
                    echo 'You have '.$newAmount.' new sale'; if($newAmount>1) {echo 's';} echo '!';
                }
                if($amountknownbeforenow[0]['value'] > $totalrecordsordercompleted) {
                    echo 'The number of completed orders has decreased by '.$newAmount.', indicating a refund, dispute, or chargeback.';
                }
                if($amountknownbeforenow[0]['value'] == $totalrecordsordercompleted) {
                    echo 'no change';
                }
                $wpdb->query("UPDATE `{$table_name_meta}` SET `value` = '{$totalrecordsordercompleted}' WHERE `type` = 'desktop_alert';");

            } else {
                echo 'This is your first time using wpStoreCart Desktop Alert.  Keep this application running at all times, and a popup like this will alert you anytime you get a new sale, dispute, or chargeback.';
                $sql = "INSERT INTO `{$table_name_meta}` (`primkey` ,`value` ,`type` ,`foreignkey`) VALUES (NULL , '{$totalrecordsordercompleted}', 'desktop_alert', '0');";
                $wpdb->query( $sql );
            }
        } else {
            echo 'wpStoreCart Desktop Alert is not configured correctly. Check your settings!';
            //echo 'GET: '.$_POST['hash'];
            //echo '
            //    ';
            //echo 'MD5: '.md5($devOptions['wpsc_api_key'] . $devOptions['wpsc_secret_hash'].'Refresher');
        }



}

wpstorecart_check_alert();

?>