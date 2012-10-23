<?php
/**
 *	CLASS: Share Your Cart Wordpress WPStoreCart
 *	AUTHOR: Barandi Solutions
 *	COUNTRY: Romania
 *	EMAIL: vlad.barliba@barandisolutions.ro
 *	VERSION : 2.0
 *	DESCRIPTION: Compatible with WPStoreCart
 *     Copyright (C) 2011 Barandi Solutions
 */

require_once("class.shareyourcart-wpstorecart.php");

if(!class_exists('ShareYourCartWPStoreCartEx',false)){

class ShareYourCartWPStoreCartEx extends ShareYourCartWPStoreCart {

	/**
	 *
	 * Check if WPStoreCart is Active
	 *
	 */
	protected function isCartActive()
	{
		return true;
	}

	/**
	 *
	 * Get the secret key
	 *
	 */
	protected function getSecretKey()
	{
		return '5cdd34e1-c767-4540-8abd-1217bd99171d';
	}
}

new ShareYourCartWPStoreCartEx();

//TODO: see why this is not used
add_action(ShareYourCartWordpressPlugin::getPluginFile(), array('ShareYourCartWPStoreCartEx','uninstallHook'));

} //END IF