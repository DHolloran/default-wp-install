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

		
function wpstorecart_desktop_alert() {
        global $wpdb, $wpstorecart_version, $current_user, $wpStoreCart;

        echo '<html><head><title>wpStoreCart Desktop Alert - Loaded</title></head><body>';

        /*
         * @todo Add the ability to specify user permission levels for dashboard.  This is not a priority, but more of an after thought
         */
        //if ( function_exists('current_user_can') && !current_user_can('manage_options') ) { // Remove the main dashboard widget from end users
        //        exit();
        //}

        $devOptions = $wpStoreCart->getAdminOptions();
        if(md5($devOptions['wpsc_api_key'] . $devOptions['wpsc_secret_hash'].'Sales')==$_GET['hash']) {

            $table_name = $wpdb->prefix . "wpstorecart_products";
            $table_name_orders = $wpdb->prefix . "wpstorecart_orders";

            $totalrecordssql = "SELECT COUNT(`primkey`) AS num FROM `{$table_name}`";
            $totalrecordsres = $wpdb->get_results( $totalrecordssql , ARRAY_A );
            if(isset($totalrecordsres)) {
                    $totalrecords = $totalrecordsres[0]['num'];
            } else {
                    $totalrecords = 0;
            }

            $totalrecordssqlorder = "SELECT COUNT(`primkey`) AS num FROM `{$table_name_orders}`";
            $totalrecordsresorder = $wpdb->get_results( $totalrecordssqlorder , ARRAY_A );
            if(isset($totalrecordsresorder)) {
                    $totalrecordsorder = $totalrecordsresorder[0]['num'];
            } else {
                    $totalrecordsorder = 0;
            }

            $totalrecordssqlordercompleted = "SELECT COUNT(`primkey`) AS num FROM `{$table_name_orders}` WHERE `orderstatus`='Completed';";
            $totalrecordsresordercompleted = $wpdb->get_results( $totalrecordssqlordercompleted , ARRAY_A );
            if(isset($totalrecordsresordercompleted)) {
                    $totalrecordsordercompleted = $totalrecordsresordercompleted[0]['num'];
            } else {
                    $totalrecordsordercompleted = 0;
            }

            $permalink = get_permalink( $devOptions['mainpage'] );

            $orderpercentage = @round($totalrecordsordercompleted / $totalrecordsorder * 100);

            $startdate =date("Ymd", strtotime("30 days ago"));
            $enddate = date("Ymd");

            $theSQL = "SELECT SUM(`price`) AS `thetotal` FROM `{$table_name_orders}` WHERE `orderstatus`='Completed';";
            $salesAllTime = $wpdb->get_results( $theSQL , ARRAY_A );
            $allTimeGrossRevenue = 0;
            foreach ($salesAllTime as $sat) {
                $allTimeGrossRevenue = $sat['thetotal'];
            }

            $theSQL = "SELECT `date`, `price` FROM `{$table_name_orders}` WHERE `date` > {$startdate} AND `date` <= {$enddate} AND `orderstatus`='Completed' ORDER BY `date` DESC;";
            $salesThisMonth = $wpdb->get_results( $theSQL , ARRAY_A );
            $currentDay = $enddate;
            $dayAgo = 0 ;
            $highestNumber = 0;
                                    $totalearned = 0;
            while($currentDay != $startdate) {
                $salesOnDay[$currentDay] = 0;
                foreach($salesThisMonth as $currentSale) {
                    if($currentDay == $currentSale['date']) {
                        $salesOnDay[$currentDay] = $salesOnDay[$currentDay] + 1;
                        $totalearned = $totalearned + $currentSale['price'];
                    }
                }
                if($salesOnDay[$currentDay] > $highestNumber) {
                    $highestNumber = $salesOnDay[$currentDay];
                }
                $dayAgo++;
                $currentDay = date("Ymd", strtotime("{$dayAgo} days ago"));

            }
            $dayAgo = 29 ;
            $currentDay = $startdate;

            // inlinebar
            //
            $lastrecordssql = "SELECT * FROM `{$table_name_orders}` ORDER BY `date` DESC LIMIT 0, 30";
            $lastrecords = $wpdb->get_results( $lastrecordssql , ARRAY_A );

            echo '<ul>';
            echo '<li><u><span style="font-size:115%;"><strong>wpStoreCart v'.$wpstorecart_version.' :</strong></span> with '.$totalrecords.' product(s).</u></li>';
            echo '<li><strong>Gross Revenue last 30 days: <span style="font-size:170%;">'.$devOptions['currency_symbol'].number_format($totalearned).$devOptions['currency_symbol_right'].'</span></strong></li>';
            echo '<li><strong>All Time Gross Revenue: <span style="font-size:170%;">'.$devOptions['currency_symbol'].number_format($allTimeGrossRevenue).$devOptions['currency_symbol_right'].'</span></strong></li>';
            echo "<li><span style=\"float:left;padding:0 10px 0 0;border-right:1px #CCC solid;\"><strong>Completed Orders / Total:</strong>  {$totalrecordsordercompleted}/{$totalrecordsorder} ({$orderpercentage}%) <br /></span> </li>";
            echo "<li><span style=\"float:left;padding:0 0 0 10px;\"><strong>Sales last 30 days:</strong> ";$tttsales=0;while($currentDay != $enddate) {$tttsales = $tttsales + $salesOnDay[$currentDay];$dayAgo--;$currentDay = date("Ymd", strtotime("{$dayAgo} days ago"));} echo $tttsales."</span><div style=\"clear:both;\"></div></li>";
            echo '</ul>';
        } else {
            echo 'wpStoreCart Desktop Alert is not configured correctly.<br><br>  For help, please visit <a href="http://wpstorecart.com/desktop-alert/">http://wpstorecart.com/desktop-alert/</a>';
        }
} 

wpstorecart_desktop_alert();

?>