<?php

@ini_set('memory_limit', '2048M');

require_once('/usr/share/php/PHPUnit/Autoload.php');
require_once('/var/www/wordpress/wp-content/plugins/wpstorecart/test/nb-wordpress-tests/unittests-config.php');
require_once('/var/www/wordpress/wp-content/plugins/wpstorecart/test/nb-wordpress-tests/init.php');
require_once('/var/www/wordpress/wp-content/plugins/wpstorecart/wpstorecart.php');

class wpStoreCartTest extends PHPUnit_Framework_TestCase {
    private $wpscObj;
    
    /**
     * Creates a new instance of a wpStoreCart object for testing
     */
    protected function setUp() {
        $this->wpscObj = new wpStoreCart;
        //$this->wpscObj->wpstorecart_install();
    }
    
    /**
     * Checks to insure testing mode is OFF before a release
     */
    public function test_testingMode() {
        $this->assertEquals(0, $this->wpscObj->testingMode());
    }
    
    /**
     * Does a simple check to make sure the error message is blank
     */
    public function test_wpscError() {
        $this->assertTrue("<div id='wpsc-warning' class='updated fade'><p></p></div>" == $this->wpscObj->wpscError());
    }
        
}

?>