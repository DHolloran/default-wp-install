<?php

// wpStoreCart, (c) 2011 wpStoreCart.com.  All rights reserved.

global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
global $wpdb, $wpStoreCart;

if (!function_exists('add_action'))
{
    require_once("../../../../../wp-config.php");
}
$devOptions = $wpStoreCart->getAdminOptions();

if(current_user_can('administrator')) {
?><html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>wpStoreCart Wizard</title>
    </head>
    <body>
        <div id="wpstorecart_ajax">
            <div style="color:#000;background:transparent url('<?PHP echo plugins_url('/wpstorecart/images/wizard/smallbg.png'); ?>') top center no-repeat;width:414px;height:266px;min-width:414px;min-height:266px;padding:25px 10px 0 40px;position:absolute;top:125px;left:94px;"><?PHP $wpStoreCart->wpstorecart_main_dashboard_widget_function(); ?></div>
            <a href="admin.php?page=wpstorecart-admin"><img style="position:absolute;top:400px;left:125px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_basic_stats.png'); ?>" alt="" /></a><a href="admin.php?page=wpstorecart-statistics"><img style="position:absolute;top:400px;left:326px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_advanced_stats.png'); ?>" alt="" /></a>
            <a href="" onclick="return false;" ><img onclick="jQuery('#wpstorecart_ajax').animate({opacity: 0}, 1000, function(){jQuery('#wpstorecart_ajax').load('<?PHP echo plugins_url('/wpstorecart/php/wizard/wizard_setup_01.php'); ?>', function(){jQuery('#wpstorecart_ajax').animate({opacity: 100}, 1000);}); });" style="cursor:pointer;position:absolute;top:12px;left:505px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_setup_wizard.png'); ?>" alt="" /></a>
        </div>
    </body>
</html><?PHP } ?>