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

	$sql_output = "
	CREATE TABLE IF NOT EXISTS `{$table_name}` (
	  `primkey` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(512) NOT NULL,
	  `introdescription` text NOT NULL,
	  `description` text NOT NULL,
	  `thumbnail` varchar(512) NOT NULL,
	  `price` decimal(9,2) NOT NULL,
	  `shipping` decimal(9,2) NOT NULL,
	  `download` varchar(512) NOT NULL,
	  `tags` text NOT NULL,
	  `category` int(11) NOT NULL,
	  `inventory` int(11) NOT NULL,
	  `dateadded` int(8) NOT NULL,
	  `postid` int(11) NOT NULL,
	  `timesviewed` int(11) NOT NULL,
	  `timesaddedtocart` int(11) NOT NULL,
	  `timespurchased` int(11) NOT NULL,
	  `useinventory` tinyint(1) NOT NULL DEFAULT '1',
	  `donation` tinyint(1) NOT NULL DEFAULT '0',
        `weight` int(7) NOT NULL DEFAULT '0',
        `length` int(7) NOT NULL DEFAULT '0',
        `width` int(7) NOT NULL DEFAULT '0',
        `height` int(7) NOT NULL DEFAULT '0',
        `discountprice` DECIMAL(9,2) NOT NULL
	  PRIMARY KEY (`primkey`)
	);	
	
	INSERT INTO `{$table_name}` (`primkey`, `name`, `introdescription`, `description`, `thumbnail`, `price`, `shipping`, `download`, `tags`, `category`, `inventory`, `dateadded`, `postid`, `timesviewed`, `timesaddedtocart`, `timespurchased`, `useinventory`, `donation`, `weight`, `length`, `width`, `height`, `discountprice`) VALUES
	";

    $updateSQL2 = "SELECT * FROM {$table_name};";

    $results2 = $wpdb->get_results($updateSQL2, ARRAY_A);
    foreach ($results2 as $result) {
        $sql_output .= "(NULL, '{$result['name']}', '{$result['introdescription']}', '{$result['description']}', '{$result['thumbnail']}', '{$result['price']}', '{$result['shipping']}', '{$result['download']}', '{$result['tags']}', '{$result['category']}', '{$result['inventory']}', '{$result['dateadded']}', '{$result['postid']}', '{$result['timesviewed']}', '{$result['timesaddedtocart']}', '{$result['timespurchased']}', '{$result['useinventory']}', '{$result['donation']}' , '{$result['weight']}', '{$result['length']}', '{$result['width']}', '{$result['height']}', '{$result['discountprice']}'),";
    }

	$sql_output = substr($sql_output, 0, -1) . ';'; //Remove the last comma and replace it with a semi-colon
	
    $filename = "wpscExport_".date("Y-m-d_H-i",time());
    header("Content-type: application/force-download");
    header("Content-disposition: sql" . date("Y-m-d") . ".sql");
    header("Content-disposition: filename=".$filename.".sql");
    print $sql_output;
    exit;

}


?>