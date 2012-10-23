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
    $primkeys = $wpdb->escape($_POST['primkeys']);
    $combopackname = $wpdb->escape($_POST['combopackname']);
    $isanedit = false;
    if(isset($_POST['isanedit'])) {
        $isanedit = intval($_POST['isanedit']);
    }
    
    if(!$isanedit) {
        $insert = "
        INSERT INTO `{$table_name}` (
        `primkey`, `value`, `type`, `foreignkey`)
        VALUES (
                NULL,
                '{$combopackname}||{$primkeys}',
                'combopack',
                '{$wpsc_combo_primkey}'
        );
        ";
    } else {
        $insert = "
        UPDATE `{$table_name}` SET `value`='{$combopackname}||{$primkeys}' WHERE `primkey`='{$isanedit}';  
        ";
    }
    
    $results = $wpdb->query($insert);
    $lastID = $wpdb->insert_id;
    echo $lastID;


}
?>