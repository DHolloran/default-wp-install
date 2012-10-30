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

    $table_name = $wpdb->prefix . "wpstorecart_products";
    $updateSQL = "SHOW COLUMNS FROM {$table_name};";

    $results = $wpdb->get_results($updateSQL, ARRAY_A);

    $i = 0;
    foreach ($results as $result) {
        $csv_output .= $result['Field'].", ";
        $i++;
    }
    $csv_output .= "\n";

    $updateSQL2 = "SELECT * FROM {$table_name};";

    $results2 = $wpdb->get_results($updateSQL2, ARRAY_N);
    foreach ($results2 as $rowr) {
        for ($j=0;$j<$i;$j++) {
            $csv_output .= str_replace(',', ' ', ereg_replace("(\r\n|\n|\r)", "<br />",$rowr[$j])).", ";
        }
        $csv_output .= "\n";
    }

    $filename = "wpscExport_".date("Y-m-d_H-i",time());
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
    header( "Content-disposition: filename=".$filename.".csv");
    print $csv_output;
    exit;

}


?>