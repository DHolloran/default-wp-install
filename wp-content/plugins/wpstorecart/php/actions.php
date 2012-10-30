<?php
/*
 * wpStoreCart Actions
 */
global $wpdb;

/**
 * Add To Cart button
 */
function wpsc_addtocart() {
    do_action('wpsc_addtocart', $myitemid);
}

/**
 * Buy Now button
 */
function wpsc_buynow() {
    do_action('wpsc_buynow', $myitemid);
}

/**
 * Checkout button
 */
function wpsc_checkout() {
    do_action('wpsc_checkout');
}


?>