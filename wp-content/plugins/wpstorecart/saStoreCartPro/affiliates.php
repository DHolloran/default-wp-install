<?PHP
global $wpStoreCart, $allowedToAccess, $wpdb, $affiliatepurchases, $affiliatesettings,$wpstorecart_version,$wpscAffiliateVersion;
$wpscAffiliateVersion = 1.1;

$allowedToAccess = true;

if(!file_exists(WP_PLUGIN_DIR.'/wpsc-affiliates-pro/saStoreCartPro/affiliates.pro.php')) {

    echo '
    <center><img src="'.plugins_url().'/wpstorecart/images/upgrade_affiliates.png" alt="" style="position:relative;top:100px;z-index:999;cursor:pointer;" onclick="jQuery(\'#buypro\').submit();" /></center>
    <center><img src="'.plugins_url().'/wpstorecart/images/affiliates.jpg" alt="" style="position:relative;top:-120px;z-index:500;" /></center>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="buypro" name="buypro">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="6PZ2X87LHLQV8">
    <input type="hidden" name="on0" value="License">
    <select name="os0" style="display:none;">
        <option value="Single Domain" selected="selected">Single Domain $29.99</option>
        <option value="2 Domains">2 Domains $49.99</option>
        <option value="10 Domains">10 Domains $209.99</option>
        <option value="Unlimited Domains">Unlimited Domains $389.99</option>
    </select>
    <input type="hidden" name="currency_code" value="USD">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
    ';
    
} else {


    $devOptions = $wpStoreCart->getAdminOptions();

    if(@isset($_POST['minimumAffiliatePayment']) || @isset($_POST['minimumDaysBeforePaymentEligable'])) {
        if (isset($_POST['minimumAffiliatePayment'])) {
                $devOptions['minimumAffiliatePayment'] = $wpdb->escape($_POST['minimumAffiliatePayment']);
        }
        if (isset($_POST['minimumDaysBeforePaymentEligable'])) {
                $devOptions['minimumDaysBeforePaymentEligable'] = $wpdb->escape($_POST['minimumDaysBeforePaymentEligable']);
        }
        if (isset($_POST['affiliateInstructions'])) {
                $devOptions['affiliateInstructions'] = $wpdb->prepare($_POST['affiliateInstructions']);
        }
        update_option($wpStoreCart->adminOptionsName, $devOptions);
    }
    $affiliatesettings['minimumAffiliatePayment'] = $devOptions['minimumAffiliatePayment'];
    $affiliatesettings['minimumDaysBeforePaymentEligable'] = $devOptions['minimumDaysBeforePaymentEligable'];
    $affiliatesettings['affiliateInstructions'] = $devOptions['affiliateInstructions'];

    $table_name = $wpdb->prefix . "wpstorecart_orders";
    $table_name_meta = $wpdb->prefix . "wpstorecart_meta";
    $sql = "SELECT * FROM `{$table_name_meta}`, `{$table_name}` WHERE  `{$table_name}`.`affiliate`>0 AND  `{$table_name}`.`orderstatus`='Completed' AND `{$table_name}`.`primkey`=`{$table_name_meta}`.`foreignkey` AND `{$table_name_meta}`.`type` != 'requiredinfo' ORDER BY  `{$table_name}`.`affiliate`,  `{$table_name}`.`date` DESC;";
    $results = $wpdb->get_results( $sql , ARRAY_A );
    $icounter = 0;
    foreach ($results as $result) {
        global $userinfo2;
        $affiliatepurchases[$icounter]['cartcontents'] = $wpStoreCart->splitOrderIntoProduct($result['primkey']);
        $affiliatepurchases[$icounter]['amountpaid'] = $result['value'];
        // Mark that we paid people or vice versa
        if(@isset($_POST['amountpaid'. $result['primkey']])) {
            $amountpaid = $wpdb->prepare($_POST['amountpaid'. $result['primkey']]);
            $wpdb->query("UPDATE `{$table_name_meta}` SET `value`='$amountpaid' WHERE `foreignkey`={$result['primkey']} AND `type`='affiliatepayment'; ");
            $affiliatepurchases[$icounter]['amountpaid'] = $amountpaid;
        }
        $affiliatepurchases[$icounter]['primkey'] = $result['primkey'];
        $affiliatepurchases[$icounter]['price'] = $result['price'];
        $affiliatepurchases[$icounter]['date'] = $result['date'];
        $affiliatepurchases[$icounter]['orderstatus'] = $result['orderstatus'];
        $userinfo2 = get_userdata($result['affiliate']);
        @$affiliatepurchases[$icounter]['affiliateusername'] = $userinfo2->user_login;
        $userinfo2 = get_userdata($result['wpuser']);
        @$affiliatepurchases[$icounter]['affiliatecustomer'] = $userinfo2->user_login;
        $icounter++;
    }
    require_once(WP_PLUGIN_DIR.'/wpsc-affiliates-pro/saStoreCartPro/affiliates.pro.php');
}

?>