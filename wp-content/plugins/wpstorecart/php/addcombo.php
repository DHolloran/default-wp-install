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
    $wpsc_combo_product_names = $wpdb->escape($_POST['wpsc_combo_product_names']);
    $exploded = explode('||', $wpsc_combo_product_names);
    $wpsc_combo_discount_price = $wpdb->escape($_POST['wpsc_combo_discount_price']);
    $wpsc_combo_primkey = $wpdb->escape($_POST['wpsc_combo_primkey']);


    $insert = "
    INSERT INTO `{$table_name}` (
    `primkey`, `value`, `type`, `foreignkey`)
    VALUES (
            NULL,
            '{$exploded[0]}||{$wpsc_combo_discount_price}',
            'productcombo',
            '{$wpsc_combo_primkey}'
    );
    ";

    $results = $wpdb->query($insert);
    $lastID = $wpdb->insert_id;
    echo $lastID;


}
?>