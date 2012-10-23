<?php
global $wpsc_error_reporting;
if($wpsc_error_reporting==false) {
    error_reporting(0);
}
error_reporting(E_ALL);
if (!function_exists('add_action'))
{
    require_once("../../../../wp-config.php");
}

global $current_user,$wpdb, $wpStoreCart;
$devOptions = $wpStoreCart->getAdminOptions();

wp_get_current_user();
if ( 0 == $current_user->ID ) {
    // Not logged in.
} else {

    if (function_exists('current_user_can') && !current_user_can('manage_wpstorecart')) {
            die(__('Cheatin&#8217; uh?'));
    }

    echo 'STEP 1: Beginning import process.<br />';
    $thefiletoimport = $_GET['file'];
    
    $file_real = WP_CONTENT_DIR.'/uploads/wpstorecart/'.$thefiletoimport;
    if (file_exists($file_real)){
        echo 'STEP 2: Successfully located the import file.<br />';
    } else {
        echo 'Import failed!  The uploaded file was not found at this location: <br />'.$file_real.'<br /><br />To resolve this, make sure that this directory is writable by the server: '.WP_CONTENT_DIR.'/uploads/wpstorecart/<br /><br />You may also want to check if you have any special characters in the file name. If so, try renaming your file to something like: import.csv and try again.';
        exit();
    }

    echo 'STEP 3: Parsing the CSV file for import.';

    $table_name = $wpdb->prefix.'wpstorecart_products';
    $devOptions = $wpStoreCart->getAdminOptions();

    $row = 1;
    if (($handle = fopen($file_real, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
            if($data[0]!='primkey') {

                // Create our PAGE in draft mode in order to get the POST ID
                $my_post = array();
                $my_post['post_title'] = stripslashes($data[1]);
                $my_post['post_type'] = 'page';
                $my_post['post_content'] = '';
                $my_post['post_status'] = 'draft';
                $my_post['post_author'] = 1;
                $my_post['post_parent'] = $devOptions['mainpage'];

                // Insert the PAGE into the WP database
                $thePostID = wp_insert_post( $my_post );
                if($thePostID==0) {
                        echo '<div class="updated"><p><strong>';
                        _e("ERROR 4: wpStoreCart didn't like your data and failed to create a page for it! Make sure you create a product with at least a title.", "wpStoreCart");
                        echo $wpdb->print_error();
                        echo '</strong></p></div>';
                        return false;
                }

                // Now insert the product into the wpStoreCart database
                $insert = "
                INSERT INTO {$table_name} (`primkey`, `name`, `introdescription`, `description`, `thumbnail`, `price`, `shipping`, `download`, `tags`, `category`, `inventory`, `dateadded`, `postid`, `timesviewed`, `timesaddedtocart`, `timespurchased`, `useinventory`, `donation`, `weight`, `length`, `width`, `height`, `discountprice`) VALUES
                (NULL,
                '{$data[1]}',
                '{$data[2]}',
                '{$data[3]}',
                '{$data[4]}',
                '{$data[5]}',
                '{$data[6]}',
                '{$data[7]}',
                '{$data[8]}',
                '{$data[9]}',
                '{$data[10]}',
                '{$data[11]}',
                '{$thePostID}',
                '{$data[13]}',
                '{$data[14]}',
                '{$data[15]}',
                '{$data[16]}',
                '{$data[17]}',
                '{$data[18]}',
                '{$data[19]}',
                '{$data[20]}',
                '{$data[21]}',
                '{$data[22]}'
                );
                ";


                $results = $wpdb->query( $insert );
                if($results===false) {
                    echo 'ERROR: Problem importing '.$data[1].'<br />';
                } else {
                    echo '.';
                    $lastID = $wpdb->insert_id;
                    $keytoedit = $lastID;


                    // Now that we've inserted both the PAGE and the product, let's update and publish our post with the correct content
                    $my_post = array();
                    $my_post['ID'] = $thePostID;
                    $my_post['post_content'] = '[wpstorecart display="product" primkey="'.$lastID.'"]';
                    $my_post['post_status'] = 'publish';
                    wp_update_post( $my_post );


                    $row++;
                }

            }
        }
        fclose($handle);
    }

    echo '<br />STEP 4: Success!  Import has finished. '.$row. ' records.';

    exit;

}


?>