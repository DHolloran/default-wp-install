<?php
/*
Plugin Name: Disable Comments
Plugin URI: http://wordpress.org/extend/plugins/disable-comments/
Description: Allows administrators to globally disable comments on their site. Comments can be disabled according to post type.
Version: 0.6
Author: Samir Shah
Author URI: http://rayofsolaris.net/
License: GPL2
*/

if( !defined( 'ABSPATH' ) )
	exit;

class Disable_Comments {
	const db_version = 4;
	private $options;
	private $modified_types = array();
	
	function __construct() {
		// load options
		$this->options = get_option( 'disable_comments_options', array() );
		
		// If it looks like first run, check compat
		if ( empty( $this->options ) && version_compare( $GLOBALS['wp_version'], '3.2', '<' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			deactivate_plugins( __FILE__ );
			if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'activate' || $_GET['action'] == 'error_scrape' ) )
				exit( 'Disable Comments requires WordPress version 3.2 or greater.' );
		}
		
		$old_ver = isset( $this->options['db_version'] ) ? $this->options['db_version'] : 0;
		if( $old_ver < self::db_version ) {
			if( $old_ver < 2 ) {
				// upgrade options from version 0.2.1 or earlier to 0.3
				$this->options['disabled_post_types'] = get_option( 'disable_comments_post_types', array() );
				delete_option( 'disable_comments_post_types' );
			}

			foreach( array( 'remove_admin_menu_comments', 'remove_admin_bar_comments', 'remove_recent_comments', 'remove_discussion', 'remove_rc_widget', 'permanent' ) as $v )
				if( !isset( $this->options[$v] ) )
					$this->options[$v] = false;

			$this->options['db_version'] = self::db_version;
			update_option( 'disable_comments_options', $this->options );
		}
		
		// these need to happen now
		if( $this->options['remove_rc_widget'] )
			add_action( 'widgets_init', array( $this, 'disable_rc_widget' ) );
		
		// these can happen later
		add_action( 'wp_loaded', array( $this, 'setup_filters' ) );	
	}
	
	function setup_filters(){
		if( !empty( $this->options['disabled_post_types'] ) ) {
			foreach( $this->options['disabled_post_types'] as $type ) {
				// we need to know what native support was for later
				if( post_type_supports( $type, 'comments' ) ) {
					$this->modified_types[] = $type;
					remove_post_type_support( $type, 'comments' );
					remove_post_type_support( $type, 'trackbacks' );
				}
			}
			add_filter( 'comments_open', array( $this, 'filter_comment_status' ), 20, 2 );
			add_filter( 'pings_open', array( $this, 'filter_comment_status' ), 20, 2 );
		}
		elseif( is_admin() ) {
			add_action( 'admin_notices', array( $this, 'setup_notice' ) );
		}
		
		if( $this->options['remove_admin_bar_comments'] && is_admin_bar_showing() ) {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 50 );	// WP<3.3
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );	// WP 3.3
		}
		
		if( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'settings_menu' ) );
			add_action( 'admin_print_footer_scripts', array( $this, 'discussion_notice' ) );
			
			if( !$this->options['permanent'] ) {
				add_action( 'edit_form_advanced', array( $this, 'edit_form_inputs' ) );
				add_action( 'edit_page_form', array( $this, 'edit_form_inputs' ) );
			}
			
			if( $this->options['remove_admin_menu_comments'] )
				add_action( 'admin_menu', array( $this, 'filter_admin_menu' ), 9999 );	// do this as late as possible
				
			if( $this->options['remove_discussion'] )
				add_action( 'admin_head', array( $this, 'hide_discussion_rightnow' ) );
				
			if( $this->options['remove_recent_comments'] )
				add_action( 'wp_dashboard_setup', array( $this, 'filter_dashboard' ) );
		}
	}
	
	function edit_form_inputs() {
		global $post;
		// Without a dicussion meta box, comment_status will be set to closed on new/updated posts
		if( in_array( $post->post_type, $this->modified_types ) ) {
			echo '<input type="hidden" name="comment_status" value="' . $post->comment_status . '" /><input type="hidden" name="ping_status" value="' . $post->ping_status . '" />';
		}
	}
	
	function discussion_notice(){
		if( get_current_screen()->id == 'options-discussion' && !empty( $this->options['disabled_post_types'] ) ) {
			$names = array();
			foreach( $this->options['disabled_post_types'] as $type )
				$names[$type] = get_post_type_object( $type )->labels->name;
?>
<script>
jQuery(document).ready(function($){
	$(".wrap h2").first().after( "<div style='color: #900'><p>Note: The <em>Disable Comments</em> plugin is currently active, and comments are completely disabled on: <?php echo implode( ', ', $names );?>. Many of the settings below will not be applicable for those post types.</div>" );
});
</script>
<?php
		}
	}
	
	function setup_notice(){
		if( current_user_can( 'manage_options' ) && get_current_screen()->id != 'settings_page_disable_comments_settings' )
			echo '<div class="updated fade"><p>The <em>Disable Comments</em> plugin is active, but isn\'t configured to do anything yet. Visit the <a href="options-general.php?page=disable_comments_settings">configuration page</a> to choose which post types to disable comments on.</p></div>';
	}
	
	function filter_admin_menu(){
		global $menu;
		if( isset( $menu[25] ) && $menu[25][2] == 'edit-comments.php' )
			unset( $menu[25] );
	}
	
	function filter_dashboard(){
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}
	
	function hide_discussion_rightnow(){
		if( 'dashboard' == get_current_screen()->id )
			add_action( 'admin_print_footer_scripts', array( $this, 'discussion_js' ) );
	}
	
	function discussion_js(){
		// getting hold of the discussion box is tricky. The table_discussion class is used for other things in multisite
		echo '<script> jQuery(document).ready(function($){ $("#dashboard_right_now .table_discussion").has(\'a[href="edit-comments.php"]\').first().hide(); }); </script>';
	}
	
	function filter_comment_status( $open, $post_id ) {
		$post = get_post( $post_id );
		return in_array( $post->post_type, $this->options['disabled_post_types'] ) ? false : $open;
	}
	
	function disable_rc_widget() {
		unregister_widget( 'WP_Widget_Recent_Comments' );
	}
	
	function settings_menu() {
		add_submenu_page('options-general.php', 'Disable Comments', 'Disable Comments', 'manage_options', 'disable_comments_settings', array( $this, 'settings_page' ) );
	}
	
	function settings_page() {
		$types = get_post_types( array( 'public' => true ), 'objects' );
		foreach( array_keys( $types ) as $type ) {
			if( ! in_array( $type, $this->modified_types ) && ! post_type_supports( $type, 'comments' ) )	// the type doesn't support comments anyway
				unset( $types[$type] );
		}
		
		if ( isset( $_POST['submit'] ) ) {
			$disabled_post_types =  empty( $_POST['disabled_types'] ) ? array() : (array) $_POST['disabled_types'];
			$disabled_post_types = array_intersect( $disabled_post_types, array_keys( $types ) );	
			// entering permanent mode, or post types have changed
			if( !empty( $_POST['permanent'] ) && ( !$this->options['permanent'] || $disabled_post_types != $this->options['disabled_post_types'] ) )
				$this->enter_permanent_mode();
			
			$this->options['disabled_post_types'] = $disabled_post_types;
			foreach( array( 'remove_admin_menu_comments', 'remove_admin_bar_comments', 'remove_recent_comments', 'remove_discussion', 'remove_rc_widget', 'permanent' ) as $v )
				$this->options[$v] = !empty( $_POST[$v] );	
			
			update_option( 'disable_comments_options', $this->options );
			echo '<div id="message" class="updated fade"><p>Options updated. Changes to the Admin Menu and Admin Bar will not appear until you leave or reload this page.</p></div>';
		}	
	?>
	<style> .indent {padding-left: 2em} </style>
	<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Disable Comments</h2>
	<form action="" method="post" id="disable-comments">
	<p>Globally disable comments on:</p>
	<ul class="indent">
		<?php foreach( $types as $k => $v ) echo "<li><label for='post-type-$k'><input type='checkbox' name='disabled_types[]' value='$k' ". checked( in_array( $k, $this->options['disabled_post_types'] ), true, false ) ." id='post-type-$k'> {$v->labels->name}</label></li>";?>
	</ul>
	<p><strong>Note:</strong> disabling comments will also disable trackbacks and pingbacks. All comment-related fields will also be hidden from the edit/quick-edit screens of the affected posts. These settings cannot be overridden for individual posts.</p>
	<h3>Other options</h3>
	<ul class="indent">
		<li><label for="remove_admin_menu_comments"><input type="checkbox" name="remove_admin_menu_comments" id="remove_admin_menu_comments" <?php checked( $this->options['remove_admin_menu_comments'] );?>> Remove the "Comments" link from the Admin Menu</label></li>
		<li><label for="remove_admin_bar_comments"><input type="checkbox" name="remove_admin_bar_comments" id="remove_admin_bar_comments" <?php checked( $this->options['remove_admin_bar_comments'] );?>> Remove the "Comments" icon from the Admin Bar</label></li>
		<li><label for="remove_recent_comments"><input type="checkbox" name="remove_recent_comments" id="remove_recent_comments" <?php checked( $this->options['remove_recent_comments'] );?>> Disable the "Recent Comments" Dashboard widget</label></li>
		<li><label for="remove_rc_widget"><input type="checkbox" name="remove_rc_widget" id="remove_rc_widget" <?php checked( $this->options['remove_rc_widget'] );?>> Disable the "Recent Comments" template widget (this prevents the widget from being available in <code>Appearance -> Widgets</code> and from being used by your theme)</label></li>
		<li><label for="remove_discussion"><input type="checkbox" name="remove_discussion" id="remove_discussion" <?php checked( $this->options['remove_discussion'] );?>> Remove the "Discussion" section from the "Right Now" Dashboard widget <span class="hide-if-js"><strong>(Note: this option will only work if you have Javascript enabled in your browser)</strong></span></label></li>
	</ul>
	<p><strong>Note:</strong> these options are global. They will affect all users, everywhere, regardless of whether comments are enabled on portions of your site. Use them only if you want to remove all references to comments <em>everywhere</em>.
	<ul class="indent"><li><label for="permanent"><input type="checkbox" name="permanent" id="permanent" <?php checked( $this->options['permanent'] );?>> Use permanent mode (see the <a href="http://wordpress.org/extend/plugins/disable-comments/faq/" target="_blank">FAQ</a> for what this means)</a></label></li></ul>
	<p class="submit"><input class="button-primary" type="submit" name="submit" value="Update settings"></p>
	</form>
	</div>
	<script>
	jQuery(document).ready(function($){
		$("#disable-comments :input").change(function(){
			$("#message").slideUp();
		});
	});
	</script>
<?php
	}
	
	private function enter_permanent_mode() {
		$types = $this->options['disabled_post_types'];
		if( empty( $types ) )
			return;
			
		global $wpdb;
		$bits = implode( ', ', array_pad( array(), count( $types ), '%s' ) );
		$wpdb->query( $wpdb->prepare( "UPDATE `$wpdb->posts` SET `comment_status` = 'closed', ping_status = 'closed' WHERE `post_type` IN ( $bits )", $types ) );
	}
}

new Disable_Comments();
