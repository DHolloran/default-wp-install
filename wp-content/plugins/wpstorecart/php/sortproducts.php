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
$devOptions = $wpStoreCart->getAdminOptions();

wp_get_current_user();
if ( 0 == $current_user->ID ) {
    // Not logged in.
} else {

    if (function_exists('current_user_can') && !current_user_can('manage_wpstorecart')) {
            die(__('Cheatin&#8217; uh?'));
    }

    $table_name = $wpdb->prefix . "wpstorecart_meta";

    $grabrecord = "SELECT * FROM `{$wpdb->prefix}wpstorecart_meta` WHERE `type`='custom_product_order';";
    $results = $wpdb->get_results( $grabrecord , ARRAY_A );
    if(!isset($results[0]['primkey'])) {
        $product_order = '';
        $grabrecord = "SELECT `primkey` FROM `{$wpdb->prefix}wpstorecart_products`;";

        $num = 0;
        $results = $wpdb->get_results( $grabrecord , ARRAY_A );
        if(isset($results)) {
                foreach ($results as $result) {
                    $product_order = $product_order . $result['primkey'] .',';
                }
                if($product_order!='') { // This removes the final comma
                    $product_order = substr($product_order,0,-1);
                }
        }
        $results = $wpdb->query("INSERT INTO `{$wpdb->prefix}wpstorecart_meta` (`primkey`, `value`, `type`, `foreignkey`) VALUES (NULL, '{$product_order}', 'custom_product_order', '0');");
    }


    // Grab the sort order
    $ordernum = '';
    $sortorder = $_POST['sort'];
    foreach ($sortorder as $sort) {
        $ordernum = $ordernum . $sort . ',';
    }
    if($ordernum!='') { // This removes the final comma
        $ordernum = substr($ordernum,0,-1);
    }
    $updateSQL = "UPDATE  `{$table_name}` SET  `value` =  '{$ordernum}' WHERE  `type` ='custom_product_order';";
    $results = $wpdb->query($updateSQL);

}
?>