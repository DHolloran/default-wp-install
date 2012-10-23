<?php
/**
 * Quetzalcoatl Ecommerce Library
 *
 * An open source ecommerce library, intended to be the backend store system for a number of ecommerce projects,
 * including wpStoreCart, a Wordpress ecommerce plugin, as well as wpStoreCart for Drupal.  Licensed under the
 * LGPL, it can be used in both commercial and open source software under the terms of the LGPL.
 *
 * @license LGPL
 * @author Jeff Quindlen
 * @version 0.0.2
 */


/**
 * quetzalcoatl class
 */
class quetzalcoatl {
    /**
     * @var decimal Contains the total price
     */
    var $total = 0;

    /**
     * @var integer The number of items currently in the cart
     */
    var $itemcount = 0;

    /**
     * @var array An array of items added to the cart
     */
    var $items = array();

    /**
     * @var array An array of prices of the items currently in the cart
     */
    var $itemprices = array();

    /**
     * @var array An array of the quantities for each item in the cart
     */
    var $itemqtys = array();

    /**
     * @var array An array of the item names in the cart
     */
    var $itemname = array();

    /**
     * @var array An array of the flat rate shipping for each item in the cart
     */
    var $itemshipping = array();

    /**
     * @var array An array of tax costs for each item in the cart
     */
    var $itemtax = array();

    /**
     * @var array An array that contains the URL to the product page for each item in the cart
     */
    var $itemurl = array();

    /**
     * @var array An array that contains the URL to an image of each product in the cart
     */
    var $itemimg = array();

    /**
     * @var array An array that contains the discounts for each item in the cart
     */
    var $itemdiscount = array();

    /**
     * @var array An array that contains each item's category
     */
    var $itemcategory = array();

    /**
     * The constructor
     *
     * Checks to see if the quetzalcoatl cookie is set for your cart, and if not, it creates it.
     */
    public function  __construct() {
        if (!isset($_COOKIE['quetzalcoatl']) && empty($_COOKIE['quetzalcoatl'])) {
            $state_save = json_encode($this);
            // Do a write to the database here, and then put the primkey returned from the database into the cookie
            setcookie("quetzalcoatl", 0, time()+60*60*24*14, "/");
        } else {

        }
    }



}


?>