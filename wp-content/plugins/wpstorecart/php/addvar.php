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
    $createnewvar = $wpdb->escape($_POST['createnewvar']);
    $varvalue = $wpdb->escape($_POST['varvalue']);
    $varprice = $wpdb->escape($_POST['varprice']);
    $vardesc = $wpdb->escape($_POST['vardesc']);
    $vartype = $wpdb->escape($_POST['vartype']);
    if($vartype=='false'){
        $vartype = 'advanced';
    }
    $vardownloads = $wpdb->escape($_POST['vardownloads']);
    $keytoedit = $wpdb->escape($_POST['keytoedit']);

    $insert = "
    INSERT INTO `{$table_name}` (
    `primkey`, `value`, `type`, `foreignkey`)
    VALUES (
            NULL,
            '{$createnewvar}||{$varvalue}||{$varprice}||{$vardesc}||{$vardownloads}||{$vartype}',
            'productvariation',
            '{$keytoedit}'
    );
    ";

    $results = $wpdb->query($insert);
    $lastID = $wpdb->insert_id;
    echo $lastID;


}
?>