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


    echo '
    <html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>wpStoreCart Wizard</title>
        </head>
        <body>
            <div id="wpstorecart_ajax">';?>
                <div style="color:#000;background:transparent url('<?PHP echo plugins_url('/wpstorecart/images/wizard/question005.png'); ?>') top center no-repeat;width:568px;height:214px;min-width:568px;min-height:214px;padding:25px 10px 0 40px;position:absolute;top:174px;left:20px;"> &nbsp; </div>
                                                    <img style="position:absolute;top:330px;left:78px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_q5_01.png'); ?>" alt="" />
                <a href="" onclick="return false;" ><img style="position:absolute;top:413px;left:78px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_q5_02.png'); ?>" alt="" onclick="jQuery('#wpstorecart_ajax').animate({opacity: 0}, 1000, function(){jQuery('#wpstorecart_ajax').load('<?PHP echo plugins_url('/wpstorecart/php/wizard/wizard_setup_06.php'); ?>', function(){jQuery('#wpstorecart_ajax').animate({opacity: 100}, 1000);}); });" /></a>
                <form name="someform" action=""><textarea cols="10" rows="3" style="position:absolute;top:364px;left:187px;width:296px;height:47px;font-size:10px;" id="paypalemailaddress" name="paypalemailaddress"><?PHP echo $devOptions['checkmoneyordertext']; ?></textarea></form>
                <a href="" onclick="return false;" ><img onclick="jQuery('#wpstorecart_ajax').animate({opacity: 0}, 1000, function(){jQuery('#wpstorecart_ajax').load('<?PHP echo plugins_url('/wpstorecart/php/wizard/wizard_setup_06a.php'); ?>', {paypalemail:jQuery('#paypalemailaddress').val()},  function(){jQuery('#wpstorecart_ajax').animate({opacity: 100}, 1000);}); });" style="cursor:pointer;position:absolute;top:364px;left:496px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_activate_paypal.png'); ?>" alt="" /></a>
                <a href="" onclick="return false;" ><img onclick="jQuery('#wpstorecart_ajax').animate({opacity: 0}, 1000, function(){jQuery('#wpstorecart_ajax').load('<?PHP echo plugins_url('/wpstorecart/php/wizard/wizard_setup_01.php'); ?>', function(){jQuery('#wpstorecart_ajax').animate({opacity: 100}, 1000);}); });" style="cursor:pointer;position:absolute;top:12px;left:505px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_setup_wizard.png'); ?>" alt="" /></a>
               <?PHP echo '
            </div>
        </body>
    </html>';



}

?>