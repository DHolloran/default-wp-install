<?php

@ini_set('memory_limit', '2048M');

require_once('/usr/share/php/PHPUnit/Autoload.php');
require_once('/var/www/wordpress/wp-content/plugins/wpstorecart/test/nb-wordpress-tests/unittests-config.php');
require_once('/var/www/wordpress/wp-content/plugins/wpstorecart/test/nb-wordpress-tests/init.php');
require_once('/var/www/wordpress/wp-content/plugins/wpstorecart/wpstorecart.php');
require_once('/var/www/wordpress/wp-content/plugins/wpstorecart/php/wpsc-1.1/wpsc/wpsc.php');

class wpscTest extends PHPUnit_Framework_TestCase {
    private $wpscObj;
    
    /**
     * Creates a new instance of a wpsc object for testing
     */
    protected function setUp() {
        $this->wpscObj = new wpsc();
    }
    
    /**
     * Checks to insure the IP address is never null
     */
    public function test_get_ip_address() {
        $this->assertNotNull($this->wpscObj->get_ip_address());
    }
    
    /**
     * Make sure our cart get contents method returns an array
     */
    public function test_get_contents() {
        $this->assertTrue(is_array($this->wpscObj->get_contents()));
    }    
    
    /**
     * Make sure adding items still works
     */
    public function test_add_item() {
        $this->assertTrue($this->wpscObj->add_item(1) );
    }    
    
    /**
     * Make sure coupons at the worst, at least provides a numeric value
     */
    public function test_update_coupon() {
        $_POST['ccoupon'] = 'test';
        $this->assertTrue(is_numeric($this->wpscObj->update_coupon(1)));
    }    

}

?>