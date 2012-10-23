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
        // Insert the PAGE into the WP database
        $my_post = array();
        $my_post['post_title'] = 'Store';
        $my_post['post_type'] = 'page';
        $my_post['post_author'] = 1;
        $my_post['post_parent'] = 0;
        $my_post['post_content'] = '[wpstorecart]';
        $my_post['post_status'] = 'publish';
        $thePostIDx = wp_insert_post( $my_post );

        if($thePostIDx==0) {
                echo '<div class="updated"><p><strong>';
                _e("ERROR 4: wpStoreCart didn't like your data and failed to create a page for it!", "wpStoreCart");
                echo $wpdb->print_error();
                echo '</strong></p></div>';
                return false;
        }
        @$devOptions['mainpage']=$thePostIDx;
        update_option('wpStoreCartAdminOptions', $devOptions);



    }
    if(!isset($devOptions['checkoutpage']) || !is_numeric($devOptions['checkoutpage']) || $devOptions['checkoutpage']==0) {
        // Insert the PAGE into the WP database
        $my_post = array();
        $my_post['post_title'] = 'Checkout';
        $my_post['post_type'] = 'page';
        $my_post['post_author'] = 1;
        $my_post['post_parent'] = 0;
        $my_post['post_content'] = '[wpstorecart display="checkout"]';
        $my_post['post_status'] = 'publish';
        $thePostIDy = wp_insert_post( $my_post );

        if($thePostIDy==0) {
                echo '<div class="updated"><p><strong>';
                _e("ERROR 4: wpStoreCart didn't like your data and failed to create a page for it!", "wpStoreCart");
                echo $wpdb->print_error();
                echo '</strong></p></div>';
                return false;
        }
        @$devOptions['checkoutpage']=$thePostIDy;
        @$devOptions['checkoutpageurl'] = get_permalink($devOptions['checkoutpage']);
        update_option('wpStoreCartAdminOptions', $devOptions);


    }

    header("HTTP/1.1 301 Moved Permanently");
    header ('Location: '.plugins_url().'/wpstorecart/php/wizard/wizard_setup_02.php');
    exit();

}

?>