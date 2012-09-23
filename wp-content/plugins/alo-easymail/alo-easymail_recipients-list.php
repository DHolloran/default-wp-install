<?php
include('../../../wp-load.php');
//auth_redirect();

$button_exit = '<br /><a href="javascript:self.parent.tb_remove();" class="easymail-navbutton easymail-recipients-close-popup">'. __("close", "alo-easymail") .'</a>';
if ( !current_user_can( "edit_posts" ) ) 	wp_die( __('Cheatin&#8217; uh?'). $button_exit ); 

global $user_email;

/*
 * Checks Required vars
 */

if ( isset( $_REQUEST['action'] ) ) {
	$action = $_REQUEST['action'];
} else {
	wp_die( __('Cheatin&#8217; uh?').$button_exit );   
}
if ( isset( $_REQUEST['newsletter'] ) ) {
	$newsletter = (int)$_REQUEST['newsletter'];
	if ( get_post_type( $newsletter ) != "newsletter" ) wp_die( __('The required newsletter does not exist', "alo-easymail"). $button_exit ); 
	if ( !get_post( $newsletter ) ) wp_die( __('The required newsletter does not exist', "alo-easymail") . $button_exit );
	if ( !get_edit_post_link( $newsletter ) ) wp_die( __('Cheatin&#8217; uh?') . $button_exit ); 
} else {
	wp_die( __('Cheatin&#8217; uh?') . $button_exit ); 
}



/*
 * Do the ajax work to generate list
 **************************************************************************/
 
if ( $action == "easymail_do_ajaxloop" ) :   
	
	check_ajax_referer( "alo-easymail_recipients-list" );
	
	$response = array();
	
	// If missing prepare cache 
	if ( !alo_em_get_cache_recipients( $newsletter ) ) {
		alo_em_create_cache_recipients( $newsletter );
	} else {
		// Now add a part of recipients into the db table 
		$sendnow = ( isset( $_REQUEST['sendnow'] ) && $_REQUEST['sendnow'] == "yes" ) ? true : false;
		$limit = apply_filters ( 'alo_easymail_ajaxloop_recipient_limit', 10 );  // Hook
		alo_em_add_recipients_from_cache_to_db( $newsletter, $limit, $sendnow );
	}
	
	$response['n_done'] = alo_em_count_newsletter_recipients( $newsletter );
	$response['n_tot'] =  alo_em_count_recipients_from_meta( $newsletter /*, true */ );
	$response['perc'] =  ( $response['n_done'] > 0 && $response['n_tot'] > 0 ) ? round ( $response['n_done'] * 100 / $response['n_tot'] ) : 0;

	header( "Content-Type: application/json" );
	die( json_encode ( $response ) );
 	
endif; // "easymail_do_ajaxloop"



/*
 * Show the generation html page 
 **************************************************************************/
 
if ( $action == "open_popup" ) : 

check_admin_referer('alo-easymail_recipients-list');
if ( alo_em_get_newsletter_status( $newsletter ) ) wp_die( __('The required newsletter seems to already have a list', "alo-easymail"). $button_exit );

// Recipients, from meta
$arr_recipients = alo_em_get_recipients_from_meta( $newsletter );
if ( !$arr_recipients ) wp_die( __( 'No recipients selected yet', "alo-easymail") . $button_exit ); 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php  _e("Newsletter subscribers creation", "alo-easymail") ?></title>

<?php // 1) Load only plugin js: smartupdater.js
if ( get_option('alo_em_js_rec_list') == "ajax_minimal" ) : ?>

<script type='text/javascript' src="<?php echo ALO_EM_PLUGIN_URL ?>/inc/jquery.js"></script>
<script type='text/javascript' src="<?php echo ALO_EM_PLUGIN_URL ?>/inc/smartupdater.js?ver=3.2.00"></script>
<script type='text/javascript' src="<?php echo ALO_EM_PLUGIN_URL ?>/inc/alo-easymail-backend-recipients-list.js"></script>
<script type='text/javascript'>
/* <![CDATA[ */
var easymailJs = {
	pagenow: "<?php echo $pagenow ?>",
	em_ajaxurl: "<?php echo ALO_EM_PLUGIN_URL . '/'. $pagenow ?>",
	action: "easymail_do_ajaxloop",
	newsletter: "<?php echo $newsletter ?>",
	nonce: "<?php echo wp_create_nonce( 'alo-easymail_recipients-list') ?>",
	ajaxurl: "<?php echo admin_url('admin-ajax.php') ?>",
	txt_success_added: "<?php echo esc_js( __( "Recipients successfully added", "alo-easymail" ) ) ?>",
	txt_success_sent: "<?php echo esc_js( __( "Newsletter successfully sent to recipients", "alo-easymail" ) ) ?>"
};
/* ]]> */
</script>
<link rel='stylesheet' id='global-css'  href="<?php echo ALO_EM_PLUGIN_URL ?>/inc/alo-easymail-backend.css" type='text/css'/>

<?php
// 2) otherwise load alternative js: jquery.periodicalupdater.js
elseif ( get_option('alo_em_js_rec_list') == "ajax_periodicalupdater" ) : 

	wp_enqueue_script( 'json2' );
	wp_enqueue_script( 'jquery', false, array( 'json2' ) );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'alo-easymail-periodicalupdater', ALO_EM_PLUGIN_URL . '/inc/jquery.periodicalupdater.js', array('jquery'), '3.1.00' );
	wp_enqueue_script( 'alo-easymail-backend-recipients-list', ALO_EM_PLUGIN_URL . '/inc/alo-easymail-backend-recipients-list.js' );

	$rec_url = wp_create_nonce( 'alo-easymail_recipients-list');
	if ( !isset( $offset ) ) $offset = 0;

	$localize = array(
		'updaterLibrary' => 'periodicalupdater',
		'pagenow' 	=> $pagenow,
		'em_ajaxurl' 	=> ALO_EM_PLUGIN_URL . '/'. $pagenow,
		'action' 	=> "easymail_do_ajaxloop",
		'newsletter'=> $newsletter,
		'nonce'		=> $rec_url,
		'ajaxurl' => admin_url('admin-ajax.php'),
		'txt_success_added' => esc_js( __( 'Recipients successfully added', "alo-easymail" ) ),
	   	'txt_success_sent' => esc_js( __( 'Newsletter successfully sent to recipients', "alo-easymail" ) )
	);
	wp_localize_script( 'alo-easymail-backend-recipients-list', 'easymailJs', $localize );
	wp_enqueue_style( 'alo-easymail-backend-css', ALO_EM_PLUGIN_URL.'/inc/alo-easymail-backend.css' );

	do_action('admin_print_scripts' );
	do_action( "admin_print_styles" );
	
else : // 3) otherwise load default js: smartupdater.js

	wp_enqueue_script( 'json2' );
	wp_enqueue_script( 'jquery', false, array( 'json2' ) );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'alo-easymail-smartupdater', ALO_EM_PLUGIN_URL . '/inc/smartupdater.js', array('jquery'), '3.2.00' );
	wp_enqueue_script( 'alo-easymail-backend-recipients-list', ALO_EM_PLUGIN_URL . '/inc/alo-easymail-backend-recipients-list.js' );

	$rec_url = wp_create_nonce( 'alo-easymail_recipients-list');
	if ( !isset( $offset ) ) $offset = 0;

	$localize = array(
		'updaterLibrary' => 'smartupdater',
		'pagenow' 	=> $pagenow,
		'em_ajaxurl' 	=> ALO_EM_PLUGIN_URL . '/'. $pagenow,
		'action' 	=> "easymail_do_ajaxloop",
		'newsletter'=> $newsletter,
		'nonce'		=> $rec_url,
		'ajaxurl' => admin_url('admin-ajax.php'),
		'txt_success_added' => esc_js( __( 'Recipients successfully added', "alo-easymail" ) ),
	   	'txt_success_sent' => esc_js( __( 'Newsletter successfully sent to recipients', "alo-easymail" ) )
	);
	wp_localize_script( 'alo-easymail-backend-recipients-list', 'easymailJs', $localize );
	wp_enqueue_style( 'alo-easymail-backend-css', ALO_EM_PLUGIN_URL.'/inc/alo-easymail-backend.css' );

	do_action('admin_print_scripts' );
	do_action( "admin_print_styles" );

endif; // end load js

$rec_url = wp_nonce_url( ALO_EM_PLUGIN_URL . '/alo-easymail_recipients-list.php?', 'alo-easymail_recipients-list');
?>
            
</head>
<body id="easymail-recipients-body">
<?php
$lang = ( isset($_REQUEST['lang'])) ? $_REQUEST['lang'] : false;

//echo "<pre>". print_r ( $arr_recipients, true ). "</pre>";


?>
<h3><?php _e( 'Create list of recipients', "alo-easymail") ?></h3>


<div id='alo-easymail-bar-outer' style="display:none"><div id='alo-easymail-bar-inner'></div></div>
<div id="ajaxloop-response">
	<p><?php _e("You have to prepare the list of recipients to send the newsletter to", "alo-easymail") ?>.</p>
	<p><?php _e("You can add the recipients to the sending queue (best choice) or send them the newsletter immediately (suggested only if few recipients)", "alo-easymail") ?>.</p>
	<p><em><?php _e("Warning: do not close or reload the browser window during process", "alo-easymail") ?>.</em></p>
	<br /><br />
	<p><?php _e("You can send the newsletter as test to", "alo-easymail") ?>:</strong>
		<input type="text" id="easymail-testmail" name="easymail-testmail" size="20" value="<?php echo $user_email; ?>" />
		<a href="#" class="easymail-navbutton easymail-send-testmail"><?php _e("Send", "alo-easymail") ?></a> 
		<img src="<?php echo ALO_EM_PLUGIN_URL?>/images/wpspin_light.gif" style="display:none;vertical-align: middle;" id="easymail-testmail-loading" />
		<img src="<?php echo ALO_EM_PLUGIN_URL?>/images/no.png" style="display:none;vertical-align: middle;"  id="easymail-testmail-no" alt="<?php _e("Yes", "alo-easymail") ?>" />
		<img src="<?php echo ALO_EM_PLUGIN_URL?>/images/yes.png" style="display:none;vertical-align: middle;" id="easymail-testmail-yes" alt="<?php _e("No", "alo-easymail") ?>" />
	</p>
</div>

<!--[if lte IE 7]>
<div style="float: left;">
<![endif]-->
<div id="easymail-recipients-navbar">
	<a href="#" class="easymail-navbutton easymail-navbutton-primary easymail-recipients-start-loop"><?php _e("Add to sending queue", "alo-easymail") ?></a> 
	
	<a href="#" class="easymail-navbutton easymail-recipients-start-loop-and-send"><?php _e("Send now", "alo-easymail") ?></a> 
	<a href="#" class="easymail-navbutton easymail-recipients-pause-loop" style="display:none"><?php _e("pause", "alo-easymail") ?></a> 
	<a href="#" class="easymail-navbutton easymail-recipients-restart-loop" style="display:none"><?php _e("continue", "alo-easymail") ?></a> 

	<a href="#" class="easymail-navbutton easymail-recipients-close-popup" rel="<?php echo $newsletter ?>"><?php _e("close", "alo-easymail") ?></a>  
</div>
<!--[if lte IE 7]>
</div>
<![endif]-->

<?php if ( get_option('alo_em_js_rec_list') != "ajax_minimal" ) do_action('admin_print_footer_scripts'); ?>
</body>
</html>
<?php 

endif; // "open_popup"

exit; ?>
