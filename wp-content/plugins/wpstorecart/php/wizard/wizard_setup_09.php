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
            <div style="color:#000;background:transparent url('<?PHP echo plugins_url('/wpstorecart/images/wizard/question009.png'); ?>') top center no-repeat;width:568px;height:214px;min-width:568px;min-height:214px;padding:25px 10px 0 40px;position:absolute;top:180px;left:23px;"> &nbsp; </div>
            <img style="position:absolute;top:295px;left:77px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_q9_01.png'); ?>" alt="" />
            <img style="position:absolute;top:261px;left:80px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_q9_02.png'); ?>" alt="" />
            <form name="someform" action="">
                <select id="currency_code" name="currency_code" style="position:absolute;top:268px;left:333px;width:168px;height:25px;font-size:12px;">
                    <?PHP
                    $theOptionsz[0] = 'USD';$theOptionszName[0] = 'U.S. Dollars ($)';
                    $theOptionsz[1] = 'AUD';$theOptionszName[1] = 'Australian Dollars (A $)';
                    $theOptionsz[2] = 'CAD';$theOptionszName[2] = 'Canadian Dollars (C $)';
                    $theOptionsz[3] = 'EUR';$theOptionszName[3] = 'Euros (&#8364)';
                    $theOptionsz[4] = 'GBP';$theOptionszName[4] = 'Pounds Sterling (&#163)';
                    $theOptionsz[5] = 'JPY';$theOptionszName[5] = 'Yen (&#165)';
                    $theOptionsz[6] = 'NZD';$theOptionszName[6] = 'New Zealand Dollar ($)';
                    $theOptionsz[7] = 'CHF';$theOptionszName[7] = 'Swiss Franc';
                    $theOptionsz[8] = 'HKD';$theOptionszName[8] = 'Hong Kong Dollar ($)';
                    $theOptionsz[9] = 'SGD';$theOptionszName[9] = 'Singapore Dollar ($)';
                    $theOptionsz[10] = 'SEK';$theOptionszName[10] = 'Swedish Krona';
                    $theOptionsz[11] = 'DKK';$theOptionszName[11] = 'Danish Krone';
                    $theOptionsz[12] = 'PLN';$theOptionszName[12] = 'Polish Zloty';
                    $theOptionsz[13] = 'NOK';$theOptionszName[13] = 'Norwegian Krone';
                    $theOptionsz[14] = 'HUF';$theOptionszName[14] = 'Hungarian Forint';
                    $theOptionsz[15] = 'CZK';$theOptionszName[15] = 'Czech Koruna';
                    $theOptionsz[16] = 'ILS';$theOptionszName[16] = 'Israeli Shekel';
                    $theOptionsz[17] = 'MXN';$theOptionszName[17] = 'Mexican Peso';
                    $theOptionsz[18] = 'BRL';$theOptionszName[18] = 'Brazilian Real (only for Brazilian users)';
                    $theOptionsz[19] = 'MYR';$theOptionszName[19] = 'Malaysian Ringgits (only for Malaysian users)';
                    $theOptionsz[20] = 'PHP';$theOptionszName[20] = 'Philippine Pesos';
                    $theOptionsz[21] = 'TWD';$theOptionszName[21] = 'Taiwan New Dollars';
                    $theOptionsz[22] = 'THB';$theOptionszName[22] = 'Thai Baht';
                    $icounter = 0;
                    foreach ($theOptionsz as $theOption) {

                            $option = '<option value="'.$theOption.'"';
                            if($theOption == $devOptions['currency_code']) {
                                    $option .= ' selected="selected"';
                            }
                            $option .='>';
                            $option .= $theOptionszName[$icounter];
                            $option .= '</option>';
                            echo $option;
                            $icounter++;
                    }
                    ?>
                </select>
                <input style="position:absolute;top:301px;left:333px;width:168px;height:30px;font-size:20px;" id="currency_symbol" name="currency_symbol" type="text" value="<?PHP echo $devOptions['currency_symbol']; ?>" />
                <input style="position:absolute;top:345px;left:333px;width:168px;height:30px;font-size:20px;" id="currency_symbol_right" name="currency_symbol_right" type="text" value="<?PHP echo $devOptions['currency_symbol_right']; ?>" />
            </form>
            <a href="" onclick="return false;" ><img onclick="jQuery('#wpstorecart_ajax').animate({opacity: 0}, 1000, function(){jQuery('#wpstorecart_ajax').load('<?PHP echo plugins_url('/wpstorecart/php/wizard/wizard_setup_10a.php'); ?>', {currency_symbol:jQuery('#currency_symbol').val(), currency_symbol_right:jQuery('#currency_symbol_right').val(), currency_code:jQuery('#currency_code').val()},  function(){jQuery('#wpstorecart_ajax').animate({opacity: 100}, 1000);}); });" style="cursor:pointer;position:absolute;top:400px;left:218px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_continue.png'); ?>" alt="" /></a>
            <a href="" onclick="return false;" ><img onclick="jQuery('#wpstorecart_ajax').animate({opacity: 0}, 1000, function(){jQuery('#wpstorecart_ajax').load('<?PHP echo plugins_url('/wpstorecart/php/wizard/wizard_setup_01.php'); ?>', function(){jQuery('#wpstorecart_ajax').animate({opacity: 100}, 1000);}); });" style="cursor:pointer;position:absolute;top:12px;left:505px;" src="<?PHP echo plugins_url('/wpstorecart/images/wizard/button_setup_wizard.png'); ?>" alt="" /></a>
           <?PHP echo '
        </div>
    </body>
</html>';


}

?>