<?PHP
global $wpStoreCart, $allowedToAccess, $statsOptions, $wpdb, $devOptions;

$allowedToAccess = true;

if(!file_exists(WP_PLUGIN_DIR.'/wpsc-statistics-pro/saStoreCartPro/statistics.pro.php')) {

    echo '
    <center><img src="'.plugins_url().'/wpstorecart/images/upgrade_statistics.png" alt="" style="position:relative;top:100px;z-index:999;cursor:pointer;" onclick="jQuery(\'#buypro\').submit();" /></center>
    <center><img src="'.plugins_url().'/wpstorecart/images/statistics.jpg" alt="" style="position:relative;top:-120px;z-index:500;" /></center>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="buypro" name="buypro">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="6PZ2X87LHLQV8">
    <input type="hidden" name="on0" value="License">
    <select name="os0" style="display:none;">
        <option value="Single Domain" selected="selected">Single Domain $29.99</option>
        <option value="2 Domains">2 Domains $49.99</option>
        <option value="10 Domains">10 Domains $209.99</option>
        <option value="Unlimited Domains">Unlimited Domains $389.99</option>
    </select>
    <input type="hidden" name="currency_code" value="USD">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
    ';
} else {
    
    $statsOptions['databasename'] = DB_NAME;
    $statsOptions['databaseuser'] = DB_USER;
    $statsOptions['databasepass'] = DB_PASSWORD;
    $statsOptions['databasehost'] =DB_HOST;
    $statsOptions['databaseprefix'] = $wpdb->prefix;
    $statsOptions['databasetable'] = $statsOptions['databaseprefix'] . 'wpstorecart_log';
    $statsOptions['databaseproductstable'] = $statsOptions['databaseprefix'] . 'wpstorecart_products';

    
    require_once(WP_PLUGIN_DIR.'/wpsc-statistics-pro/saStoreCartPro/statistics.pro.php');
}

?>