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


    if(file_exists(WP_PLUGIN_DIR.'/wpsc-payments-pro/saStoreCartPro/payments.pro.php')) {
        header("HTTP/1.1 301 Moved Permanently");
        header ('Location: '.plugins_url().'/wpstorecart/php/wizard/wizard_setup_07.php');
        exit();
    }
    
    echo '
    <html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>wpStoreCart Wizard</title>
        </head>
        <body>
            <div id="wpstorecart_ajax">';?>
                <div style="color:#000;background:transparent url('<?PHP echo plugins_url('/wpstorecart/images/wizard/question006.png'); ?>') top center no-repeat;width:568px;height:214px;min-width:568px;min-height:214px;padding:25px 10px 0 40px;position:absolute;top:174px;left:10px;"> &nbsp; </div>
                <a href="http://wpstorecart.com/store/business-support-wpstorecart-pro/" target="blank"><img style="position:absolute;top:352px;left:84px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_q6_01.png'); ?>" alt="" /></a>
                <a href="" onclick="return false;" ><img style="position:absolute;top:391px;left:84px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_q6_02.png'); ?>" alt="" onclick="jQuery('#wpstorecart_ajax').animate({opacity: 0}, 1000, function(){jQuery('#wpstorecart_ajax').load('<?PHP echo plugins_url('/wpstorecart/php/wizard/wizard_setup_09.php'); ?>', function(){jQuery('#wpstorecart_ajax').animate({opacity: 100}, 1000);}); });" /></a>
                
                
                <a href="" onclick="return false;" ><img onclick="jQuery('#wpstorecart_ajax').animate({opacity: 0}, 1000, function(){jQuery('#wpstorecart_ajax').load('<?PHP echo plugins_url('/wpstorecart/php/wizard/wizard_setup_01.php'); ?>', function(){jQuery('#wpstorecart_ajax').animate({opacity: 100}, 1000);}); });" style="cursor:pointer;position:absolute;top:12px;left:505px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_setup_wizard.png'); ?>" alt="" /></a>
               <?PHP echo '
            </div>
        </body>
    </html>';



}

?>