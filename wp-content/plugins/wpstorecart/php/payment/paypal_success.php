<?PHP

// wpStoreCart, (c) 2010 wpStoreCart.com.  All rights reserved.

global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
if (!function_exists('add_action'))
{
    require_once("../../../../../wp-config.php");
}

global $wpStoreCart, $current_user, $wpstorecart_version;
get_currentuserinfo();
$message = '';
if (isset($wpStoreCart)) {
	$devOptions = $wpStoreCart->getAdminOptions();

         // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($wpStoreCart->makeEmailTxt($devOptions['emailonpurchase']) . $wpStoreCart->makeEmailTxt($devOptions['emailsig']), 70);

        $headers = 'From: '.$devOptions['wpStoreCartEmail'] . "\r\n" .
            'Reply-To: ' .$devOptions['wpStoreCartEmail']. "\r\n" .
            'X-Mailer: PHP/wpStoreCart v'.$wpstorecart_version;

        
        // Send an email when purchase is submitted
        @ini_set("sendmail_from", $devOptions['wpStoreCartEmail']);
        if($current_user->ID != 0) {
            @wp_mail($current_user->user_email, 'Thank you for your recent purchase', $message, $headers);
        } else {
            if(@isset($_SESSION['wpsc_email'])) {
                @wp_mail($_SESSION['wpsc_email'], 'Thank you for your recent purchase', $message, $headers);
            } elseif(@isset($_POST['payer_email'])) {
                @wp_mail($_POST['payer_email'], 'Thank you for your recent purchase', $message, $headers);
            }
        }

        header ('HTTP/1.1 301 Moved Permanently');
        if(strpos(get_permalink($devOptions['mainpage']),'?')===false) {
            header ('Location: '.get_permalink($devOptions['mainpage']).'?wpsc=success');
        } else {
            header ('Location: '.get_permalink($devOptions['mainpage']).'&wpsc=success');
        }
}
?>