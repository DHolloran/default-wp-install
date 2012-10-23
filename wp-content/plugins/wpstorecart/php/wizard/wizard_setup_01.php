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
    
    if(!isset($devOptions['mainpage']) || !is_numeric($devOptions['mainpage']) || $devOptions['mainpage']==0) {
        $startwizardat = '/wpstorecart/php/wizard/wizard_setup_01a.php';
    } else {
        $startwizardat = '/wpstorecart/php/wizard/wizard_setup_02.php';
    }

?><html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>wpStoreCart Wizard</title>
    </head>
    <body>
        <div id="wpstorecart_ajax">
            <div style="color:#000;background:transparent url('<?PHP echo plugins_url('/wpstorecart/images/wizard/question001.png'); ?>') top center no-repeat;width:568px;height:214px;min-width:568px;min-height:214px;padding:25px 10px 0 40px;position:absolute;top:170px;left:16px;"> &nbsp; </div>
            <a href="" onclick="return false;" ><img style="position:absolute;top:390px;left:215px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_begin.png'); ?>" alt="" onclick="jQuery('#wpstorecart_ajax').animate({opacity: 0}, 1000, function(){jQuery('#wpstorecart_ajax').load('<?PHP echo plugins_url($startwizardat); ?>', function(){jQuery('#wpstorecart_ajax').animate({opacity: 100}, 1000);}); });" /></a>
            <a href="" onclick="return false;" ><img onclick="jQuery('#wpstorecart_ajax').animate({opacity: 0}, 1000, function(){jQuery('#wpstorecart_ajax').load('<?PHP echo plugins_url('/wpstorecart/php/wizard/wizard_setup_01.php'); ?>', function(){jQuery('#wpstorecart_ajax').animate({opacity: 100}, 1000);}); });" style="cursor:pointer;position:absolute;top:12px;left:505px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_setup_wizard.png'); ?>" alt="" /></a>
        </div>
    </body>
</html><?PHP } ?>