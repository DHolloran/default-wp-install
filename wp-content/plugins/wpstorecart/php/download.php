<?php

global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}

/** if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    try {
        @apache_setenv('no-gzip', '1');
    } catch (Exception $e) {

    }
}
try {
    @ini_set('zlib.output_compression', 'Off');
} catch (Exception $e) {

}
*/

if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

if (!function_exists('add_action'))
{
    require_once("../../../../wp-config.php");
}

global $current_user,$wpdb;

wp_get_current_user();
if ( 0 == $current_user->ID ) {
    // Not logged in.
} else {

    //Give actual path here
    if(isset($_GET['file']) && is_numeric($_GET['file'])){
        $file = $_GET['file'];
    }

    $file_real = NULL;

    // Logged in.
    $table_name3 = $wpdb->prefix . "wpstorecart_orders";
    $sql = "SELECT * FROM `{$table_name3}` WHERE `wpuser`='{$current_user->ID}'  ORDER BY `date` DESC;";
    $results = $wpdb->get_results( $sql , ARRAY_A );
    if(isset($results)) {
        foreach ($results as $result) {
            if ($result['orderstatus']=='Completed') {
                
                $specific_items = explode(",", $result['cartcontents']);
                foreach($specific_items as $specific_item) {
                    if($specific_item != '0*0') { // This is filler, all cart entries contain a 0*0 entry
                        $current_item = explode('*', $specific_item);

                        if(isset($current_item[0]) && $current_item[0]==$file) { // If the customer has purchased the product that is being requested for download
                            $table_name4 = $wpdb->prefix . "wpstorecart_products";
                            $current_item[0] = substr($current_item[0], 0, strpos("$current_item[0]-", "-")); // Remove the variation from the initial product lookup
                            $sql2 = "SELECT * FROM `{$table_name4}` WHERE `primkey`='{$current_item[0]}';";
                            $results2 = $wpdb->get_results( $sql2 , ARRAY_A );
                            if(isset($results2)) {
                                $xpart = 1;
                                foreach ($results2 as $result2) {
                                    if($result2['download']!='') {
                                        $multidownloads = explode('||', $result2['download']);
                                        if(@isset($_GET['isvariation']) && @isset($_GET['variationdl']) && @$_GET['isvariation']=="true") {
                                            // Variation download
                                            $secretPath = WP_CONTENT_DIR . '/uploads/wpstorecart/';
                                            $file_real = $secretPath.$_GET['variationdl'];
                                            $productDownloadName =  $result2['name']. '_' .$current_user->user_login.'_part'.$xpart.'_'. substr($_GET['variationdl'], -12);
                                        } else {
                                            // Standard download
                                            if(@isset($_GET['part']) && @isset($multidownloads[$_GET['part']])) {
                                                // Multi part file download
                                                $secretPath = WP_CONTENT_DIR . '/uploads/wpstorecart/';
                                                $file_real = $secretPath.stripslashes($multidownloads[$_GET['part']]);
                                                $productDownloadName =  $result2['name']. '_' .$current_user->user_login.'_part'.$_GET['part'].'_'. stripslashes(substr($multidownloads[$_GET['part']], -12));
                                            } else {
                                                // Single files to download
                                                $secretPath = WP_CONTENT_DIR . '/uploads/wpstorecart/';
                                                $file_real = $secretPath.stripslashes($result2['download']);
                                                $productDownloadName =  $result2['name']. '_' .$current_user->user_login.'_part'.$xpart.'_'. stripslashes(substr($result2['download']), -12);
                                            }
                                        }
                                    }
                                    $xpart++;
                                }
                                
                            }
                        }
                    }
                }
            }
        }
    } else {
        echo("Unauthorized access - No order found.");
        die();
    }

    if (file_exists($file_real)){
                // Get extension of requested file
                $extension = strtolower(substr(strrchr($file_real, "."), 1));
                // Determine correct MIME type

                switch($extension){
                    case "avi": $type = "video/x-msvideo"; break;
                    case "exe": $type = "application/octet-stream"; break;
                    case "mov": $type = "video/quicktime"; break;
                    case "mp3": $type = "audio/mpeg"; break;
                    case "mpg": $type = "video/mpeg"; break;
                    case "mpeg": $type = "video/mpeg"; break;
                    case "rar": $type = "encoding/x-compress"; break;
                    case "txt": $type = "text/plain"; break;
                    case "wav": $type = "audio/wav"; break;
                    case "wma": $type = "audio/x-ms-wma"; break;
                    case "wmv": $type = "video/x-ms-wmv"; break;
                    case "zip": $type = "application/x-zip-compressed"; break;
                    case "7z": $type = "application/x-zip-compressed"; break;
                    case "asf": $type = "video/x-ms-asf"; break;
                    default: $type = "application/force-download"; break;
                }
    // Fix IE bug [0]
    $header_file = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ? preg_replace('/\./', '%2e', $productDownloadName, substr_count($productDownloadName, '.') - 1) : $productDownloadName;
    // Prepare headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public", false);
    header("Content-Description: File Transfer");
    header("Content-Type: " . $type);
    header("Accept-Ranges: bytes");
    header("Content-Disposition: attachment; filename=\"" . $header_file . "\";");
    header("Content-Transfer-Encoding: binary");
    header("X-Compression: None");
    //header("Content-Length: " . filesize($file_real));
    // Send file for download
    if ($stream = fopen($file_real, 'rb')){
    while(!feof($stream) && connection_status() == 0){
    //reset time limit for big files
    set_time_limit(0);
    print(fread($stream,1024*8));
    flush();
    }
    fclose($stream);
    }
    }else{
        // Requested file does not exist (File not found)
        echo("Unathorized Access.  File does not exist.");
    die();
    }
}
?>