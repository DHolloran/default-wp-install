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

require_once("class.shareyourcart-wp.php");

if(!class_exists('ShareYourCartWPStoreCart',false)){

class ShareYourCartWPStoreCart extends ShareYourCartWordpressPlugin {

	/**
	 *
	 * When installing the new wordpress plugin, make sure to move the old data
	 *
	 */
	public function install(&$message = null) {
		global $wpdb, $devOptions;
		
		$shouldBeActive = true;
		
		//if there are no credentials to use,
		//try to get them from the wpStoreCart old implementation
		$appKey = $this->getAppKey();
		$clientId = $this->getClientId();
		if(empty($appKey) && empty($clientId)){
						
			$this->setConfigValue('appKey', $devOptions['shareyourcart_appid']);
			$this->setConfigValue('clientId', $devOptions['shareyourcart_clientid']);
			$this->setConfigValue('button_skin', $devOptions['shareyourcart_skin']);	
		
			//make sure we take into consideration the user's option
			//so if he decided to disable the old implementation
			//keep that setting for him
			$shouldBeActive = ($devOptions['shareyourcart_activate'] !== false);
			
			//make sure the old implementation is disabled
			$devOptions['shareyourcart_activate'] = false;
			update_option('wpStoreCartAdminOptions', $devOptions);
		}
		
		parent::install($message);
		
		//if the user deactivated the old implementation
		//make sure we also deactivate the current one
		if(!$shouldBeActive){
			
			$this->deactivate($message);
		}
	}

	/**
	 *
	 * Check if WPStoreCart is Active
	 *
	 */
	protected function isCartActive()
	{
		//check if WPStoreCart is active
		if (!function_exists( 'is_plugin_active' ) )
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

		return is_plugin_active( 'wpstorecart/wpstorecart.php' );
	}

	/**
	 *
	 * Get the secret key
	 *
	 */
	protected function getSecretKey()
	{
		return '8074d6a4-6f14-4d25-a0ee-7a7fbfdd1499';
	}

	/*
	 *
	 * Extend the base class implementation
	 *
	 */
	public function pluginsLoadedHook() {

		parent::pluginsLoadedHook();

		if(!$this->isCartActive()) return;
		
		//instead of using wp_ajax, better hook at init function
		//wp_ajax is not allways reliable, as some plugins might affect
		//it's behavior
		add_action('init', array(&$this, 'processInit'));

		remove_shortcode('wpstorecart');
		add_shortcode('wpstorecart', array(&$this, 'genericHook'));
	}
	
	/*************
	*
	* Called when Wordpress has been initialized
	*
	************/
	public function processInit(){
	
		if(isset($_REQUEST['action'])){
			switch($_REQUEST['action']){
			
			case 'shareyourcart_wpstorecart':
				$this->buttonCallback();
				break;
				
			case 'shareyourcart_wpstorecart_coupon':
				$this->couponCallback();
				break;
			}
		}
	}
	
	/**
	*
	* Return the jQuery sibling selector for the product button
	*
	*/
	protected function getProductButtonPosition(){
		$selector = parent::getProductButtonPosition();
		return (!empty($selector) ? $selector : ".wpsc-button.wpsc-addtocart");
	}
	
	/**
	*
	* Return the jQuery sibling selector for the cart button
	*
	*/
	protected function getCartButtonPosition(){
		$selector = parent::getCartButtonPosition();
		return (!empty($selector) ? $selector : "#wpsc-total");
	}

	/**
	 *
	 * Return the URL to be called when the button is pressed
	 *
	 */
	public function getButtonCallbackURL(){

		global $wp_query;

		$callback_url = get_bloginfo('wpurl').'/?action=shareyourcart_wpstorecart';

		if($this->isSingleProduct())
		{
			preg_match_all('/\[wpstorecart display="product" primkey="([0-9])"\]/', $wp_query->post->post_content, $matches);
			
			if(count($matches[1]) == 0) return $callback_url;
			
			//set the product id
			$callback_url .= '&p='. $matches[1][0];			
		}

		return $callback_url;
	}

	/*
	 *
	 * Check if this is a single product page
	 *
	 */
	protected function isSingleProduct(){
		global $wp_query;

		$pattern = '/\[wpstorecart display="product" primkey="[0-9]*"\]/';
		preg_match_all($pattern, $wp_query->post->post_content, $matches);

		// If multiple products are displayed on one page break;
		if(count($matches[0]) > 1) return false;

		// If we found any match
		if($matches[0][0]) {

			// If the pattern is found verifies that we are not on a category listing page
			if(is_single() or is_page()) return true;

		}

		// Return false otherwise
		return false;
	}

	/*
	 *
	 * Called when the button is pressed
	 *
	 */
	public function buttonCallback(){
		global $devOptions, $wpdb, $wpStoreCart, $cart;
		if(!$this->isCartActive()) return;

		//specify the parameters
		$params = array(
			'callback_url' => get_bloginfo('wpurl').'/?action=shareyourcart_wpstorecart_coupon'.(isset($_REQUEST['p']) ? '&p='.$_REQUEST['p'] : '' ),
			'success_url' => get_permalink($devOptions['checkoutpage']),
			'cancel_url' => get_permalink($devOptions['checkoutpage']),
		);

		//there is no product set, thus send the products from the shopping cart
		if(!isset($_GET['p']) || !is_numeric($_GET['p']) ) {
			if($cart->itemcount > 0) {
				@$newsplit = explode('-', $item['id'] );
				if(isset($newsplit[0])) {
					@$item['id'] = $newsplit[0];
				}

				foreach($cart->get_contents() as $item) {
					
					$params['cart'][] = array(
			'item_name' => $item['name'],
			'item_description' => $item['name'] . ' (x'.$item['qty'].')',
			'item_url' => $item['url'],
			'item_price' => $devOptions['currency_symbol'].number_format($item['subtotal'],2).$devOptions['currency_symbol_right'],
			'item_picture_url' =>  $item['img'],
					);
				}
			}
		}
		else
		{			
			$table_name = $wpdb->prefix . "wpstorecart_products";
			$sql = "SELECT * FROM `{$table_name}` WHERE `primkey`={$wpdb->escape($_GET['p'])};";
			$results = $wpdb->get_results( $sql , ARRAY_A );

			if(isset($results)) {
				$params['cart'][] = array(
					'item_name' => $results[0]['name'],
					'item_url' => get_permalink($results[0]['postid']),
					'item_price' => $devOptions['currency_symbol'].number_format($results[0]['price'],2).$devOptions['currency_symbol_right'],
					'item_description' => $results[0]['introdescription'],
					'item_picture_url' =>  $results[0]['thumbnail'],
				);
			}
		}

		try
		{
			$this->startSession($params);
		}
		catch(Exception $e)
		{
			//display the error to the user
			echo $e->getMessage();
		}
		exit;
	}

	/**
	 *
	 * Load the cart data
	 *
	 */
	protected function loadSessionData() {
		global $cart, $wpsc_cart_type;

		if($wpsc_cart_type == 'session') {
			if(!isset($_SESSION)) {
				@session_start();
			}
			if(@!is_object($cart)) {
				$cart =& $_SESSION['wpsc'];
				if(@!is_object($cart)) {
					$cart = new wpsc();
				}
			}
		}

		if($wpsc_cart_type == 'cookie') {
			if(!isset($_SESSION)) { @session_start(); }
			if(@!is_object($cart)) {
				if(isset($_COOKIE['wpsccart'])) { @$cart =& unserialize(base64_decode($_COOKIE['wpsccart'])); }
				if(@!is_object($cart) && !isset($_COOKIE['wpsccart'])) {
					$cart = new wpsc();
					$xdomain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;setcookie('wpsccart', base64_encode(serialize($cart)), time()+7222, '/', $xdomain, false);
				}
			}
		}
	}

	/**
	 *
	 * 	 Insert coupon in database
	 *
	 */
	protected function saveCoupon($token, $coupon_code, $coupon_value, $coupon_type) {
		global $wpdb, $blog_id, $eshopcartarray,$eshopoptions, $shiparray, $post;

		$syc_token = $token;
		$wpStoreCartcode = $coupon_code;
		$syc_coupon_type = $coupon_type;
		$syc_coupon_value = $coupon_value;
		if($syc_coupon_type=='amount') {
			$wpStoreCartamount = intval($syc_coupon_value);
			$wpStoreCartpercent = 0;
		}
		if($syc_coupon_type=='percent') {
			$wpStoreCartamount = 0;
			$wpStoreCartpercent = intval($syc_coupon_value);
		}
		$wpStoreCartdescription = SyC::t('sdk','Generated by ShareYourCart.com');
		$wpStoreCartproduct = 0;
		$yesterday = mktime(0, 0, 0, date("m"), date("d")-1, date("y"));
		$twodays = mktime(0, 0, 0, date("m"), date("d")+2, date("y"));
		$wpStoreCartstartdate = date("Ymd", $yesterday);
		$wpStoreCartenddate = date("Ymd", $twodays);

		$table_name = $wpdb->prefix . "wpstorecart_coupons";
		$insert = "INSERT INTO `{$table_name}` (`primkey`, `code`, `amount`, `percent`, `description`, `product`, `startdate`, `enddate`) VALUES (
			NULL,
			'{$wpStoreCartcode}',
			'{$wpStoreCartamount}',
			'{$wpStoreCartpercent}',
			'{$wpStoreCartdescription}',
			'{$wpStoreCartproduct}',
			'{$wpStoreCartstartdate}',
			'{$wpStoreCartenddate}');
			";

		$results = $wpdb->query($insert);

		//call the base class method
		parent::saveCoupon($token, $coupon_code, $coupon_value, $coupon_type);
	}

	/**
	 *
	 * Apply the coupon directly to the current shopping cart
	 *
	 */
	protected function applyCoupon($coupon_code){
		global $wpStoreCart, $cart, $wpsc;

		$_POST['ccoupon'] = $coupon_code;
		$cart->_update_total();
		$_SESSION['wpsc'] = $cart;
	}

	/**
	 *
	 * Append the button to the checkout page
	 *
	 **/
	function genericHook($atts, $content = '', $code=""){
		global $wpStoreCart;
		extract(shortcode_atts(array(
                                    'display' => NULL,
                                    'primkey' => '0',
                                    'quantity' => 'unset',
                                    'usetext' => 'true',
                                    'usepictures' => 'false',
                                    'thecategory' => '',
                                    'displaytype' => '',
                                    'orderby' => '',
                                    'ordertype' => '',
		), $atts));

		$output = '';

		switch ($display) {
			case 'shareyourcart':
				$output = $this->getButton();
				break;
			case 'checkout':
				$output = $this->getCartButton();
				break;
			case 'product':
				$output = $this->getProductButton();
				break;
		}

		$result = $wpStoreCart->wpstorecart_mainshortcode($atts, $content);
		return $output.$result;
	}
}

//TODO: see why this is not used
add_action(ShareYourCartWordpressPlugin::getPluginFile(), array('ShareYourCartWPStoreCart','uninstallHook'));

} //END IF