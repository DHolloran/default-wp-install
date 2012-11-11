<?php
/* Plugin Name: Modal Dialog
Plugin URI: http://yannickcorner.nayanna.biz/modal-dialog/
Description: A plugin used to display a modal dialog to visitors with text content or the contents of an external web site
Version: 2.4
Author: Yannick Lefebvre
Author URI: http://yannickcorner.nayanna.biz
Copyright 2011  Yannick Lefebvre  (email : ylefebvre@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA*/

define('MODAL_DIALOG_ADMIN_PAGE_NAME', 'modal-dialog');

define('MDDIR', dirname(__FILE__) . '/');

require_once(ABSPATH . '/wp-admin/includes/template.php');

/* === Text Editor Style Sheet === */
function admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/style.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}
add_action('admin_head', 'admin_register_head');

//class that reperesent the complete plugin
class modal_dialog_plugin {

	//constructor of class, PHP4 compatible construction for backward compatibility
	function modal_dialog_plugin() {

		$this->mdpluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';
		load_plugin_textdomain( 'modal-dialog', $this->mdpluginpath . '/languages', 'modal-dialog/languages');

		$options = get_option('MD_PP');
		$genoptions = get_option('MD_General');

		if ($genoptions == false) {
			$this->md_install();
		}

		//add filter for WordPress 2.8 changed backend box system !
		add_filter('screen_layout_columns', array($this, 'on_screen_layout_columns'), 10, 2);
		//register callback for admin menu  setup
		add_action('admin_menu', array($this, 'on_admin_menu'));
		//register the callback been used if options of page been submitted and needs to be processed
		add_action('admin_post_save_modal_dialog_general', array($this, 'on_save_changes_general'));
		add_action('admin_post_save_modal_dialog_configurations', array($this, 'on_save_changes_configurations'));

		// Add addition section to Post/Page Edition page
		add_meta_box ('modaldialog_meta_box', __('Modal Dialog', 'modal-dialog'), array($this, 'md_post_edit_extra'), 'post', 'normal', 'high');
		add_meta_box ('modaldialog_meta_box', __('Modal Dialog', 'modal-dialog'), array($this, 'md_post_edit_extra'), 'page', 'normal', 'high');

		add_action('edit_post', array($this, 'md_editsave_post_field'));
		add_action('save_post', array($this, 'md_editsave_post_field'));

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		$genoptions = get_option('MD_General');

		for ($counter = 1; $counter <= $genoptions['numberofmodaldialogs']; $counter++) {
			$optionsname = "MD_PP" . $counter;
			$options = get_option($optionsname);

			if ($genoptions['disableonmobilebrowsers'] == true)
			{
				require_once(ABSPATH . '/wp-content/plugins/modal-dialog/mdetect.php');
				$browserchecker = new uagent_info();
				$mobilebrowser = $browserchecker->DetectMobileQuick();
			}

			if ($options['active'] == true)
			{
				if ($genoptions['disableonmobilebrowsers'] == false || ($genoptions['disableonmobilebrowsers'] == true && $mobilebrowser == false))
				{
				add_action('wp_footer', array($this, 'modal_dialog_footer'));
				add_action('wp_head', array($this, 'modal_dialog_header'));
				break;
				}
			}
		}

		add_action('admin_head', array($this, 'modal_dialog_admin_header'));

		add_action('comment_post_redirect', array($this, 'comment_redirect_filter'), 10, 2);

		register_activation_hook(__FILE__, array($this, 'md_install'));
		register_deactivation_hook(__FILE__, array($this, 'md_uninstall'));

		return $this;
	}

	function comment_redirect_filter($location, $comment) {
		$genoptions = get_option('MD_General');

		for ($counter = 1; $counter <= $genoptions['numberofmodaldialogs']; $counter++) {
			$optionsname = "MD_PP" . $counter;
			$options = get_option($optionsname);

			if ($options['showaftercommentposted'] == true)
			{
				$commentanchorpos = strpos($location, '#comment');
				$getpresence = strpos($location, '?');
				if ($commentanchorpos != false)
				{
					if ($getpresence == false)
						$showdialogstring = '?showmodaldialog=' . $counter;
					else
						$showdialogstring = '&showmodaldialog=' . $counter;
					$location = substr_replace($location, $showdialogstring, $commentanchorpos, 0);
				}
				break;
			}

		}

		return $location;
	}

	//for WordPress 2.8 we have to tell, that we support 2 columns !
	function on_screen_layout_columns($columns, $screen) {
		if ($screen == $this->pagehooktop) {
			$columns[$this->pagehooktop] = 1;
		}
		elseif ($screen == $this->pagehooksettings) {
			$columns[$this->pagehooksettings] = 1;
		}

		return $columns;
	}

	function modal_dialog_default_config( $confignumber ) {

		$options['dialogname'] = 'Default';
		$options['contentlocation'] = 'URL';
		$options['content'] = 'Example Dialog Text';
		$options['active'] = true;
		$options['cookieduration'] = 365;
		$options['contenturl'] = 'http://www.google.com';
		$options['pages'] = '';
		$options['overlaycolor'] = '#00CC00';
		$options['textcolor'] = '#000000';
		$options['backgroundcolor'] = '#FFFFFF';
		$options['delay'] = 2000;
		$options['dialogwidth'] = 900;
		$options['dialogheight'] = 700;
		$options['cookiename'] = 'modal-dialog';
		$options['numberoftimes'] = 1;
		$options['exitmethod'] = 'onlyexitbutton';
		$options['autosize'] = false;
		$options['showfrontpage'] = false;

		if ( $confignumber == 1 )
			$options['forcepagelist'] = false;
		elseif ( $confignumber > 1 )
			$options['forcepagelist'] = true;

		$options['sessioncookiename'] = 'modal-dialog-session';
		$options['oncepersession'] = false;
		$options['hideclosebutton'] = false;
		$options['centeronscroll'] = false;
		$options['manualcookiecreation'] = false;
		$options['overlayopacity'] = '0.3';
		$options['autoclose'] = false;
		$options['autoclosetime'] = 5000;
		$options['checklogin'] = false;
		$options['displayfrequency'] = 1;
		$options['showaftercommentposted'] = false;
		$options['dialogclosingcallback'] = '';
        $options['hidescrollbars'] = false;
        $options['excludepages'] = '';

		$configname = "MD_PP" . $confignumber;
		update_option($configname, $options);
	}

	function md_install() {
		$options = get_option('MD_PP1');

		if ($options == false) {
			$oldoptions = get_option('MD_PP');

			if ($oldoptions != false)
			{
				$oldoptions['dialogname'] = 'Default';
				update_option('MD_PP1', $oldoptions);
			}
			else
			{
				$this->modal_dialog_default_config(1);
			}
		}

		$genoptions = get_option('MD_General');

		if ($genoptions == false) {
			$genoptions['numberofmodaldialogs'] = 1;
			$genoptions['primarydialog'] = 1;
			$genoptions['disableonmobilebrowsers'] = false;

			update_option('MD_General', $genoptions);
		}
	}

	function md_uninstall() {
	}

	function remove_querystring_var($url, $key) {
		$keypos = strpos($url, $key);
		if ($keypos)
		{
			$ampersandpos = strpos($url, '&', $keypos);
			$newurl = substr($url, 0, $keypos - 1);

			if ($ampersandpos)
				$newurl .= substr($url, $ampersandpos);
		}
		else
			$newurl = $url;

		return $newurl;
	}

	//extend the admin menu
	function on_admin_menu() {
		//add our own option page, you can also add it to different sections or use your own one
		$this->pagehooktop = add_menu_page(__('Modal Dialog General Options', 'modal-dialog'), "Modal Dialog", 'manage_options', MODAL_DIALOG_ADMIN_PAGE_NAME, array($this, 'on_show_page'), $this->mdpluginpath . '/icons/ModalDialog16.png');
		$this->pagehooksettings = add_submenu_page( MODAL_DIALOG_ADMIN_PAGE_NAME, __('Modal Dialog - Configurations', 'modal-dialog') , __('Configurations', 'modal-dialog'), 'manage_options', 'modal-dialog-configurations', array($this,'on_show_page'));

		//register  callback gets call prior your own page gets rendered
		add_action('load-'.$this->pagehooktop, array(&$this, 'on_load_page'));
		add_action('load-'.$this->pagehooksettings, array(&$this, 'on_load_page'));
	}

	//will be executed if wordpress core detects this page has to be rendered
	function on_load_page() {
		//ensure, that the needed javascripts been loaded to allow drag/drop, expand/collapse and hide/show of boxes
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');

		//add several metaboxes now, all metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
		add_meta_box('modaldialog_general_meta_box', __('General Configuration', 'modal-dialog'), array($this, 'general_config_meta_box'), $this->pagehooktop, 'normal', 'high');
		add_meta_box('modaldialog_general_save_meta_box', __('Save General Configuration', 'modal-dialog'), array($this, 'general_save_meta_box'), $this->pagehooktop, 'normal', 'high');

		add_meta_box('modaldialog_dialog_config_selection_meta_box', __('Modal Dialog Selection', 'modal-dialog'), array($this, 'dialog_config_selection_meta_box'), $this->pagehooksettings, 'normal', 'high');
		add_meta_box('modaldialog_dialog_config_meta_box', __('General Configuration', 'modal-dialog'), array($this, 'dialog_config_meta_box'), $this->pagehooksettings, 'normal', 'high');
		add_meta_box('modaldialog_dialog_config_save_meta_box', __('Preview / Save', 'modal-dialog'), array($this, 'dialog_config_save_meta_box'), $this->pagehooksettings, 'normal', 'high');

	}

	//executed to show the plugins complete admin page
	function on_show_page() {
		//we need the global screen column value to beable to have a sidebar in WordPress 2.8
		global $screen_layout_columns;
		global $wpdb;

		$genoptions = get_option('MD_General');

		if ( isset($_GET['config']) && $config == '') {
			$config = $_GET['config'];

			if ($config > $genoptions['numberofmodaldialogs'])
			{
				$config = 1;
			}
		}
		else
		{
			$config = 1;
		}

		if ($_GET['page'] == 'modal-dialog')
		{
			$pagetitle = __('Modal Dialog General Settings', 'modal-dialog');
			$formvalue = 'save_modal_dialog_general';

			if ($_GET['message'] == '1')
				echo '<div id="message" class="updated fade"><p><strong>' . __('Modal Dialog General Settings Updated', 'modal-dialog') . '</strong></div>';
		}
		elseif ($_GET['page'] == 'modal-dialog-configurations')
		{
			$pagetitle = __('Modal Dialog Configurations', 'modal-dialog');
			$formvalue = 'save_modal_dialog_configurations';

			if ($_GET['message'] == '1')
				echo '<div id="message" class="updated fade"><p><strong>' . __('Modal Dialog Configuration Updated', 'modal-dialog') . ' (#' . $config . ')</strong></div>';
		}

		$configname = 'MD_PP' . $config;
		$options = get_option($configname);

		if ($options == false)
		{
			$this->modal_dialog_default_config($config);
			$options = get_option($configname);
		}

		//define some data can be given to each metabox during rendering
		$data['options'] = $options;
		$data['config'] = $config;
		$data['genoptions'] = $genoptions;
		$data['mode'] = $mode;
		$data['selectedcat'] = $selectedcat;
		$data['selectedvenue'] = $selectedvenue;
		$data['selectedevent'] = $selectedevent;
		?>




		<div id="modal-dialog-general" class="wrap">
		<div class='icon32'><img src="<?php echo $this->mdpluginpath . '/icons/ModalDialog32.png'; ?>" /></div>
		<h2><?php echo $pagetitle; ?><span style='padding-left: 50px'><a href="http://yannickcorner.nayanna.biz/wordpress-plugins/modal-dialog/" target="modaldialog"><img src="<?php echo $this->mdpluginpath; ?>/icons/btn_donate_LG.gif" /></a></span></h2>
		<form action="admin-post.php" method="post" id='mdform' enctype='multipart/form-data'>
			<?php wp_nonce_field('modal-dialog-general'); ?>
			<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
			<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
			<input type="hidden" name="action" value="<?php echo $formvalue; ?>" />

			<div id="poststuff" class="metabox-holder">
				<div id="post-body" class="has-sidebar">
					<div id="post-body-content" class="has-sidebar-content">
						<?php
							if ($_GET['page'] == 'modal-dialog')
								do_meta_boxes($this->pagehooktop, 'normal', $data);
							elseif ($_GET['page'] == 'modal-dialog-configurations')
								do_meta_boxes($this->pagehooksettings, 'normal', $data);

						?>
					</div>
				</div>
				<br class="clear"/>

			</div>
		</form>
		</div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
		});
		//]]>
	</script>

		<?php
	}

		//executed if the post arrives initiated by pressing the submit button of form
	function on_save_changes_general() {
		//user permission check
		if ( !current_user_can('manage_options') )
			wp_die( __('Cheatin&#8217; uh?') );
		//cross check the given referer
		check_admin_referer('modal-dialog-general');

		$options = get_option('MD_General');

		foreach (array('numberofmodaldialogs', 'primarydialog') as $option_name) {
				if (isset($_POST[$option_name])) {
					$options[$option_name] = $_POST[$option_name];
				}
			}

		foreach (array('disableonmobilebrowsers') as $option_name) {
			if (isset($_POST[$option_name])) {
				$options[$option_name] = true;
			} else {
				$options[$option_name] = false;
			}
		}

		update_option('MD_General', $options);

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		wp_redirect($this->remove_querystring_var($_POST['_wp_http_referer'], 'message') . "&message=1");
	}

	//executed if the post arrives initiated by pressing the submit button of form
	function on_save_changes_configurations() {
		//user permission check
		if ( !current_user_can('manage_options') )
			wp_die( __('Cheatin&#8217; uh?') );
		//cross check the given referer
		check_admin_referer('modal-dialog-general');

		if (isset($_POST['configid']))
		{
			$configid = $_POST['configid'];
		}
		else
		{
			$configid = 1;
		}

		$configname = 'MD_PP' . $configid;
		$options = get_option($configname);

		foreach ( array( 'content', 'contentlocation', 'cookieduration', 'contenturl', 'pages', 'overlaycolor', 'textcolor', 'backgroundcolor',
				'delay', 'dialogwidth', 'dialogheight', 'cookiename', 'numberoftimes', 'exitmethod', 'sessioncookiename', 'overlayopacity',
				'autoclosetime', 'dialogname', 'displayfrequency', 'dialogclosingcallback', 'excludepages' ) as $option_name ) {
				if (isset($_POST[$option_name])) {
					$options[$option_name] = $_POST[$option_name];
				}
			}

		foreach (array('active') as $option_name) {
			if (isset($_POST[$option_name]) && $_POST[$option_name] == "True") {
				$options[$option_name] = true;
			} elseif (isset($_POST[$option_name]) && $_POST[$option_name] == "False") {
				$options[$option_name] = false;
			}
		}

		foreach (array('autosize', 'showfrontpage', 'forcepagelist', 'oncepersession', 'hideclosebutton', 'centeronscroll', 'manualcookiecreation',
						'autoclose', 'checklogin', 'showaftercommentposted', 'hidescrollbars') as $option_name) {
			if (isset($_POST[$option_name])) {
				$options[$option_name] = true;
			} else {
				$options[$option_name] = false;
			}
		}

		update_option($configname, $options);

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		wp_redirect($this->remove_querystring_var($_POST['_wp_http_referer'], 'message') . "&message=1&config=" . $configid);
	}

	function general_config_meta_box($data) {
		$genoptions = $data['genoptions'];
	?>

	<table>
		<tr>
			<td style="width: 200px">Number of Modal Dialogs</td>
			<td><input type="text" id="numberofmodaldialogs" name="numberofmodaldialogs" size="5" value="<?php echo $genoptions['numberofmodaldialogs']; ?>"/></td>
		</tr>
		<tr>
			<td>Disable on mobile browsers</td>
			<td><input type="checkbox" id="disableonmobilebrowsers" name="disableonmobilebrowsers" <?php if ($genoptions['disableonmobilebrowsers']) echo ' checked="checked" '; ?>/></td>
		</tr>
	</table>

	<?php }

	function general_save_meta_box($data) {
	?>
		<div class="submitbox">
			<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Settings','modal-dialog'); ?>" />
		</div>

	<?php }

	function dialog_config_selection_meta_box($data) {
		$genoptions = $data['genoptions'];
		$config = $data['config'];
	?>

		<?php _e('Select Current Dialog Configuration', 'modal-dialog'); ?> :
		<SELECT id="configlist" name="configlist" style='width: 300px'>
		<?php if ($genoptions['numberofmodaldialogs'] == '') $numberofdialogs = 1; else $numberofdialogs = $genoptions['numberofmodaldialogs'];
			for ($counter = 1; $counter <= $numberofdialogs; $counter++): ?>
				<?php $tempoptionname = "MD_PP" . $counter;
				   $tempoptions = get_option($tempoptionname); ?>
				   <option value="<?php echo $counter ?>" <?php if ($config == $counter) echo 'SELECTED';?>><?php if ($counter == 1) _e('Primary ', 'modal-dialog'); ?><?php _e('Dialog', 'modal-dialog'); ?> <?php echo $counter ?><?php if ($tempoptions != "") echo " (" . $tempoptions['dialogname'] . ")"; ?></option>
			<?php endfor; ?>
		</SELECT>
		<INPUT type="button" name="go" value="<?php _e('Make Current', 'modal-dialog'); ?>!" onClick="window.location= 'admin.php?page=modal-dialog-configurations&amp;config=' + jQuery('#configlist').val()">

	<?php }

	function dialog_config_meta_box($data) {
		$options = $data['options'];
		$config = $data['config'];
	?>
			<input type='hidden' value='<?php echo $config; ?>' name='configid' id='configid' />
			<table style='width: 100%'>
			<td style='vertical-align: top'>
				<table>
					<tr>
						<td style='width: 200px;'>Dialog Name</td>
						<td><input type="text" id="dialogname" name="dialogname" size="40" value="<?php echo $options['dialogname']; ?>"/></td>
					</tr>
					<tr>
						<td style='width: 200px;'>Activate</td>
						<td>
							<select name="active" id="active" style="width:250px;">
								<option value="True"<?php if ($options['active'] == true) { echo ' selected="selected"';} ?>>Yes</option>
								<option value="False"<?php if ($options['active'] == false) { echo ' selected="selected"';} ?>>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Content Source</td>
						<td>
							<select name="contentlocation" id="contentlocation" style="width:250px;">
								<option value="URL"<?php if ($options['contentlocation'] == 'URL') { echo ' selected="selected"';} ?>>Web Site Address</option>
								<option value="Inline"<?php if ($options['contentlocation'] == 'Inline') { echo ' selected="selected"';} ?>>Specify Below in Dialog Contents</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Appearance Delay (in milliseconds)</td>
						<td><input type="text" id="delay" name="delay" size="5" value="<?php echo $options['delay']; ?>"/></td>
					</tr>
					<tr>
						<td>Web Site Address</td>
						<td colspan=3><input type="text" id="contenturl" name="contenturl" size="40" value="<?php echo $options['contenturl']; ?>"/></td>
					</tr>
				</table>
			</td>
			<td class="themefuse" style='padding: 8px; border: 1px solid #cccccc;'>
			<div><h3>ThemeFuse Original WP Themes</h3><br />If you are looking to buy an original WP theme, take a look at <a href="https://www.e-junkie.com/ecom/gb.php?cl=136641&c=ib&aff=153522" target="ejejcsingle">ThemeFuse</a><br />They have a nice 1-click installer, great support and good-looking themes.</div><div style='text-align: center; padding-top: 10px'><a href="https://www.e-junkie.com/ecom/gb.php?cl=136641&c=ib&aff=153522" target="ejejcsingle"><img src='http://themefuse.com/wp-content/themes/themefuse/images/campaigns/themefuse.jpg' /></a></div>
			</td>
			</table>
			<table>
			<tr>
				<td style='vertical-align: top; width: 250px'>Dialog Contents</td>
				<td colspan=3>

				<?php if ( current_user_can('edit_posts') ) : ?>

				<div class="modal-text-edit-box">

  				<?php the_editor($options['content']); ?>

  				</div>

					<?php endif; ?>


				</td>
			</tr>
			<tr>
				<td>Number of days until cookie expiration</td>
				<td><input type="text" id="cookieduration" name="cookieduration" size="4" value="<?php echo $options['cookieduration']; ?>"/></td>
				<td style="width: 200px">Number of times to display modal dialog</td>
				<td><input type="text" id="numberoftimes" name="numberoftimes" size="4" value="<?php echo $options['numberoftimes']; ?>"/></td>
			</tr>
			<tr>
				<td>Period to display dialog (every # page loads)</td>
				<td><input type="text" id="displayfrequency" name="displayfrequency" size="4" value="<?php echo ($options['displayfrequency'] == '' ? '1': $options['displayfrequency']); ?>"/></td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Cookie Name</td>
				<td><input type="text" id="cookiename" name="cookiename" size="30" value="<?php echo $options['cookiename']; ?>"/></td>
				<td>Dialog Exit Method</td>
				<td>
					<select name="exitmethod" id="exitmethod" style="width:100px;">
						<option value="onlyexitbutton"<?php if ($options['exitmethod'] == 'onlyexitbutton') { echo ' selected="selected"';} ?>>Only Close Button</option>
						<option value="anywhere"<?php if ($options['exitmethod'] == 'anywhere') { echo ' selected="selected"';} ?>>Anywhere</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Show once per session</td>
				<td><input type="checkbox" id="oncepersession" name="oncepersession" <?php if ($options['oncepersession']) echo ' checked="checked" '; ?>/></td>
				<td>Session Cookie Name</td>
				<td><input type="text" id="sessioncookiename" name="sessioncookiename" size="30" value="<?php if ($options['sessioncookiename'] != '') echo $options['sessioncookiename']; else echo 'modal-dialog-session'; ?>"/></td>
			</tr>
			<tr>
				<td>Set display cookies manually</td>
				<td><input type="checkbox" id="manualcookiecreation" name="manualcookiecreation" <?php if ($options['manualcookiecreation']) echo ' checked="checked" '; ?>/></td>
				<td></td>
				<td><input type="button" id="deletecookies" name="deletecookies" value="Delete All Cookies" /></td>
			</tr>
			<tr>
				<td>Do not show if user logged in</td>
				<td><input type="checkbox" id="checklogin" name="checklogin" <?php if ($options['checklogin']) echo ' checked="checked" '; ?>/></td>
				<td>Hide Close Button</td>
				<td><input type="checkbox" id="hideclosebutton" name="hideclosebutton" <?php if ($options['hideclosebutton']) echo ' checked="checked" '; ?>/></td>
			</tr>
			<tr>
				<td>Center on scroll</td>
				<td><input type="checkbox" id="centeronscroll" name="centeronscroll" <?php if ($options['centeronscroll']) echo ' checked="checked" '; ?>/></td>
				<td>Auto-Size Dialog</td>
				<td><input type="checkbox" id="autosize" name="autosize" <?php if ($options['autosize']) echo ' checked="checked" '; ?>/></td>
			</tr>
                        <tr>
				<td>Hide Scroll Bars</td>
				<td><input type="checkbox" id="hidescrollbars" name="hidescrollbars" <?php if ($options['hidescrollbars']) echo ' checked="checked" '; ?>/></td>
			</tr>
			<tr>
				<td>Dialog Width</td>
				<td><input type="text" id="dialogwidth" name="dialogwidth" size="4" value="<?php echo $options['dialogwidth']; ?>"/></td>
				<td>Dialog Height</td>
				<td><input type="text" id="dialogheight" name="dialogheight" size="4" value="<?php echo $options['dialogheight']; ?>"/></td>
			</tr>
			<tr>
				<td>Auto-Close Dialog</td>
				<td><input type="checkbox" id="autoclose" name="autoclose" <?php if ($options['autoclose']) echo ' checked="checked" '; ?>/></td>
				<td>Auto-Close Time (in ms)</td>
				<td><input type="text" id="autoclosetime" name="autoclosetime" size="8" value="<?php echo $options['autoclosetime']; ?>"/></td>
			</tr>
			<tr>
				<td>Javascript Dialog Closure Callback</td>
				<td><input type="text" id="dialogclosingcallback" name="dialogclosingcallback" size="30" value="<?php echo $options['dialogclosingcallback']; ?>"/></td>
			</tr>
			<tr>
				<td>Only show on specific pages and single posts</td>
				<td><input type="checkbox" <?php if ($config > 1) echo "DISABLED"; ?> id="forcepagelist" name="forcepagelist" <?php if ($options['forcepagelist'] == true) echo ' checked="checked" '; ?>/></td>
				<td>Display on front page</td>
				<td><input type="checkbox" id="showfrontpage" name="showfrontpage" <?php if ($options['showfrontpage'] == true) echo ' checked="checked" '; ?>/></td>
			</tr>
			<tr>
				<td>Pages and posts to display Modal Dialog (empty for all, comma-separated IDs)</td>
				<td colspan=3><input type="text" id="pages" name="pages" size="120" value="<?php echo $options['pages']; ?>"/></td>
			</tr>
			<tr>
				<td>Pages and posts not to display dialog on</td>
				<td colspan=3><input type="text" id="excludepages" name="excludepages" size="120" value="<?php echo $options['excludepages']; ?>"/></td>
			</tr>
			<tr>
				<td>Display after new comment posted</td>
				<td><input type="checkbox" id="showaftercommentposted" name="showaftercommentposted" <?php if ($options['showaftercommentposted'] == true) echo ' checked="checked" '; ?>/></td>
			</tr>
			<tr>
				<td>Overlay Color</td>
				<td><input type="text" id="overlaycolor" name="overlaycolor" size="8" value="<?php echo $options['overlaycolor']; ?>"/></td>
				<td>Overlay Opacity (0 to 1)</td>
				<td><input type="text" id="overlayopacity" name="overlayopacity" size="8" value="<?php if ($options['overlayopacity'] == '') echo '0.3'; else echo $options['overlayopacity']; ?>"/></td>
			</tr>
			<tr>
				<td>Text Color (not used with web site address)</td>
				<td><input type="text" id="textcolor" name="textcolor" size="8" value="<?php echo $options['textcolor']; ?>"/></td>
				<td>Background Color</td>
				<td><input type="text" id="backgroundcolor" name="backgroundcolor" size="8" value="<?php echo $options['backgroundcolor']; ?>"/></td>

			</tr>
			</table>

			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery("#deletecookies").click(function() {
						jQuery.cookie(jQuery("#cookiename").val(), null, { path: '/' });
						jQuery.cookie(jQuery("#sessioncookiename").val(), null, { path: '/' });
						alert("Deleted all cookies");
					});
				});
			</script>
	<?php }

	function dialog_config_save_meta_box($data) {
		$options = $data['options'];
		?>
		<!-- <span>
			<input type="button" class="button" id="previewdialog" name="previewdialog" value="Preview Modal Dialog" />
		</span> -->
		<span class="submitbox">
			<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Settings','modal-dialog'); ?>" />
		</span>

		<!--
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#previewdialog").click(function() {
					alert("Test");
				});
			});
		</script>
		-->

	<?php }

	function md_post_edit_extra($post) {
		global $wpdb;

		$genoptions = get_option('MD_General');

		if ($post->ID != '')
		{
			$dialogid = get_post_meta($post->ID, "modal-dialog-id", true);
			if ($dialogid == "") $dialogid = 0;
		}
		else
		{
			$dialogid = 0;
		}
	?>
		<table>
			<tr>
				<td colspan="2">Setting a dialog here will take priority over any other dialog specified under the Modal Dialog Settings page.</td>
			</tr>
			<tr>
				<td style='width: 200px'><?php _e('Modal Display to Display', 'modal-dialog'); ?></td>
				<td>
				<SELECT id="dialogid" name="dialogid" style='width: 300px'>
						<option value="0">None</option>
				<?php if ($genoptions['numberofmodaldialogs'] == '') $numberofdialogs = 1; else $numberofdialogs = $genoptions['numberofmodaldialogs'];
					for ($counter = 1; $counter <= $numberofdialogs; $counter++): ?>
						<?php $tempoptionname = "MD_PP" . $counter;
						   $tempoptions = get_option($tempoptionname); ?>
						   <option value="<?php echo $counter ?>" <?php if ($dialogid == $counter) echo 'SELECTED';?>><?php _e('Dialog', 'modal-dialog'); ?> <?php echo $counter ?><?php if ($tempoptions != "") echo " (" . $tempoptions['dialogname'] . ")"; ?></option>
					<?php endfor; ?>
				</SELECT>
				</td>
			</tr>
		</table>
	<?php }

	function md_editsave_post_field($post_id) {
		if (isset($_POST['dialogid']))
			update_post_meta($post_id, "modal-dialog-id", $_POST['dialogid']);
	}

	function modal_dialog_admin_header() {
		echo "<link rel='stylesheet' type='text/css' media='screen' href='". WP_PLUGIN_URL . "/modal-dialog/fancybox/jquery.fancybox-1.3.4.css'/>\n";
		echo "<!-- [if lt IE 7] -->\n";
		echo "<style type='text/css'>\n";

		echo "/*IE*/\n";
		echo "#fancybox-loading.fancybox-ie div	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_loading.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancybox-close		{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_close.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancybox-title-over	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_title_over.png', sizingMethod='scale'); zoom: 1; }\n";
		echo ".fancybox-ie #fancybox-title-left	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_title_left.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancybox-title-main	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_title_main.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancybox-title-right	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_title_right.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancybox-left-ico		{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_nav_left.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancybox-right-ico	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_nav_right.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie .fancy-bg { background: transparent !important; }\n";

		echo ".fancybox-ie #fancy-bg-n	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_shadow_n.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancy-bg-ne	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_shadow_ne.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancy-bg-e	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_shadow_e.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancy-bg-se	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_shadow_se.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancy-bg-s	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_shadow_s.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancy-bg-sw	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_shadow_sw.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancy-bg-w	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_shadow_w.png', sizingMethod='scale'); }\n";
		echo ".fancybox-ie #fancy-bg-nw	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" . WP_PLUGIN_URL . "/modal-dialog/fancybox/fancy_shadow_nw.png', sizingMethod='scale'); }\n";

		echo "</style>";
		echo "<!-- [endif] -->\n";
	}

	function modal_dialog_header($manualdisplay = false) {
		global $post;
		$thePostID = $post->ID;
        $display = false;

		if ( isset( $_GET['showmodaldialog'] ) ) {
			$display = true;
		} elseif ( !empty( $thePostID ) ) {
			$dialogid = get_post_meta( $post->ID, "modal-dialog-id", true );
			if ( !empty( $dialogid ) && $dialogid != 0 )
				$display = true;
		}

		if ($display == false)
		{
			$genoptions = get_option('MD_General');

			 for ( $counter = 1; $counter <= $genoptions['numberofmodaldialogs']; $counter++ ) {
                $dialogid = $counter;
                $optionsname = "MD_PP" . $counter;
                $options = get_option( $optionsname );

                if ( $options['checklogin'] == false || $options['checklogin'] == '' || ( $options['checklogin'] == true && !is_user_logged_in() ) ) {
                    if ( ( $options['active'] || $manualdisplay ) && !is_admin() ) {
                        if ( $options['showfrontpage'] && is_front_page() ) {
                            $display = true;
                            break;
                        } elseif ( $options['showfrontpage'] == false && is_front_page() ) {
                            $display = false;
                        } elseif ( $options['forcepagelist'] == true ) {
                            if ( $options['pages'] != '' ) {
                                $pagelist = explode(',', $options['pages']);

                                if ($pagelist) {
                                    foreach ($pagelist as $pageid) {
                                        if ( is_page(intval($pageid)) || is_single($pageid) ) {
                                            $display = true;
                                            break 2;
                                        } else {
                                            $display = false;
                                        }
                                    }
                                }
                            }
                        } elseif ( $options['forcepagelist'] == false && !is_front_page() ) {
                            $display = true;
                        } elseif ( $manualdisplay == true ) {
                            $display = true;
                        }

                        if ( !empty( $options['excludepages'] ) ) {
                            $exclude_page_list = explode( ',', $options['excludepages'] );

                            if ( $exclude_page_list ) {
                                foreach ( $exclude_page_list as $excluded_page_id ) {
                                    if ( is_page( intval( $excluded_page_id ) ) || is_single( $excluded_page_id ) ) {
                                        $display = false;
                                    }
                                }
                            }
                        }
                    }
                }
                else
                    $display = false;
            }
		}


		if ($display == true)
            $this->modal_dialog_admin_header();
	}

	function modal_dialog_footer( $manualdisplay = false ) {
		global $post;
		$thePostID = $post->ID;
        $display = false;

		wp_reset_query();

		if ( isset( $_GET['showmodaldialog'] ) ) {
			$display = true;
            $dialogid = $_GET['showmodaldialog'];
		} elseif ( !empty( $thePostID ) ) {
			$dialogid = get_post_meta( $post->ID, "modal-dialog-id", true );
			if ( !empty( $dialogid ) && $dialogid != 0 )
				$display = true;
            else
                $dialogid = 1;
		}

        if ( $display == false ) {
            $genoptions = get_option( 'MD_General' );
            for ( $counter = 1; $counter <= $genoptions['numberofmodaldialogs']; $counter++ ) {
                $dialogid = $counter;
                $optionsname = "MD_PP" . $counter;
                $options = get_option( $optionsname );

                if ( $options['checklogin'] == false || $options['checklogin'] == '' || ( $options['checklogin'] == true && !is_user_logged_in() ) ) {
                    if ( ( $options['active'] || $manualdisplay ) && !is_admin() ) {
                        if ( $options['showfrontpage'] && is_front_page() ) {
                            $display = true;
                            break;
                        } elseif ( $options['showfrontpage'] == false && is_front_page() ) {
                            $display = false;
                        } elseif ( $options['forcepagelist'] == true ) {
                            if ( $options['pages'] != '' ) {
                                $pagelist = explode(',', $options['pages']);

                                if ($pagelist) {
                                    foreach ($pagelist as $pageid) {
                                        if ( is_page(intval($pageid)) || is_single($pageid) ) {
                                            $display = true;
                                            break 2;
                                        } else {
                                            $display = false;
                                        }
                                    }
                                }
                            }
                        } elseif ( $options['forcepagelist'] == false && !is_front_page() ) {
                            $display = true;
                        } elseif ( $manualdisplay == true ) {
                            $display = true;
                        }

                        if ( !empty( $options['excludepages'] ) ) {
                            $exclude_page_list = explode( ',', $options['excludepages'] );

                            if ( $exclude_page_list ) {
                                foreach ( $exclude_page_list as $excluded_page_id ) {
                                    if ( is_page( intval( $excluded_page_id ) ) || is_single( $excluded_page_id ) ) {
                                        $display = false;
                                    }
                                }
                            }
                        }
                    }
                }
                else
                    $display = false;
            }
        }

		if ($display == true && $dialogid != 0)
		{
			global $wpdb;

			$optionsname = "MD_PP" . $dialogid;
			$options = get_option($optionsname);

			$output = "<!-- Modal Dialog Output -->\n";

			if ($options['contentlocation'] == 'Inline')
			{
				$output .= "<a id=\"inline\" href=\"#data\"></a>\n";
				$output .= "<div style=\"display:none\"><div id=\"data\" style=\"color:" . $options['textcolor']. ";background-color:" . $options['backgroundcolor'] . ";width:100%;height:100%\">";
				$output .= stripslashes(do_shortcode($options['content']));

				$output .= "</div></div>\n";
			}
			elseif ($options['contentlocation'] == "URL")
			{
				$output .= "<a href='" . $options['contenturl']. "' class='iframe'></a>\n";
			}

			$output .= "<div id='md-content'>\n";

				$output .= "<script type=\"text/javascript\">\n";

				$output .= "jQuery(document).ready(function() {\n";

				if ($options['contentlocation'] == 'Inline')
					$output .= "jQuery(\"a#inline\").fancybox({\n";
				elseif ($options['contentlocation'] == 'URL')
					$output .= "jQuery(\"a.iframe\").fancybox({\n";

				if ($options['exitmethod'] == 'onlyexitbutton')
				{
					$output .= "'hideOnOverlayClick': false,\n";
					$output .= "'hideOnContentClick': false,\n";
				}
				elseif ($options['exitmethod'] == 'anywhere')
				{
					$output .= "'hideOnOverlayClick': true,\n";
					$output .= "'hideOnContentClick': false,\n";
				}

				if ($options['hideclosebutton'] == true)
				{
					$output .= "'showCloseButton': false,\n";
				}

				if ($options['centeronscroll'] == true)
				{
					$output .= "'centerOnScroll': true,\n";
				}

                                if ($options['hidescrollbars'] == true)
				{
					$output .= "'scrolling': 'no',\n";
				}

				if ($options['dialogclosingcallback'] != '')
				{
					$output .= "'onClosed': function() {" . $options['dialogclosingcallback']. "},\n";
				}

				if ($options['autosize'] == true)
					$output .= "'autoDimensions': true,\n";
				elseif ($options['autosize'] == false)
					$output .= "'autoDimensions': false,\n";

				if ($options['sessioncookiename'] != '')
					$sessioncookiename = $options['sessioncookiename'];
				else
					$sessioncookiename = 'modaldialogsession';

				$output .= "'overlayColor': '" . $options['overlaycolor'] . "',\n";
				$output .= "'width': " . $options['dialogwidth'] . ",\n";
				$output .= "'height': " . $options['dialogheight'] . ",\n";

				if ($options['overlayopacity'] == '') $options['overlayopacity'] = '0.3';

				$output .= "'overlayOpacity': " . $options['overlayopacity'] . "\n";
				$output .= "});\n";

				if ($options['oncepersession'] == true)
				{
					$output .= "var sessioncookie = jQuery.cookie('" . $sessioncookiename . "');\n";
					$output .= "if (sessioncookie == null)\n";
					$output .= "{\n";
					if ($options['manualcookiecreation'] == false)
						$output .= "\tjQuery.cookie('" . $sessioncookiename . "', 0, { path: '/' });\n";
				}

				$output .= "\tvar cookievalue = jQuery.cookie('" . $options['cookiename'] . "');\n";

                                if ($options['displayfrequency'] != 1 && $options['displayfrequency'] != '' && $options['showaftercommentposted'] == false)
                                {
                                    $output .= "\tvar cookiechecksvalue = jQuery.cookie('" . $options['cookiename'] . "_checks');\n";
                                    $output .= "\tif (cookiechecksvalue == null) cookiechecksvalue = 0;\n";
                                }

				$output .= "\tif (cookievalue == null) cookievalue = 0;\n";

				$output .= "\tif (cookievalue < " . $options['numberoftimes'] . ")\n";

				$output .= "\t{\n";

				if ($options['displayfrequency'] != 1 && $options['displayfrequency'] != '' && $options['showaftercommentposted'] == false)
                                {
                                    $output .= "\t\tcookiechecksvalue++;\n";
                                    $output .= "\t\tjQuery.cookie('" . $options['cookiename'] . "_checks', cookiechecksvalue";

                                    if ($options['cookieduration'] > 0)
						$output .= ", { expires: " . $options['cookieduration'] .  ", path: '/'}";
					else
						$output .= ", { path: '/' }";

					$output .= ");\n";

                                    $output .= "\t\tif (cookiechecksvalue % " . $options['displayfrequency'] . " == 0) {\n";
                                }

				if ($options['manualcookiecreation'] == false)
				{
					$output .= "\t\tcookievalue++;\n";
					$output .= "\t\tjQuery.cookie('" . $options['cookiename'] . "', cookievalue";

					if ($options['cookieduration'] > 0)
						$output .= ", { expires: " . $options['cookieduration'] .  ", path: '/'}";
					else
						$output .= ", { path: '/' }";

					$output .= ");\n";
				}

				$output .= "\t\tsetTimeout(\n";
				$output .= "\t\t\tfunction(){\n";

				if ($options['contentlocation'] == 'Inline')
					$output .= "\t\t\t\tjQuery(\"a#inline\").trigger('click')\n";
				elseif ($options['contentlocation'] == 'URL')
					$output .= "\t\t\t\tjQuery(\"a.iframe\").trigger('click')\n";

				$output .= "\t\t\t}, " . $options['delay'] . ");\n";
				$output .= "\t\t};\n";

				if ($options['displayfrequency'] != 1 && $options['displayfrequency'] != '' && $options['showaftercommentposted'] == false)
					$output .= '}';

				if ($options['oncepersession'] == true)
				{
					$output .= "\t}\n";
				}

			$output .= "});\n";
			if ($options['autoclose'] == true && $options['autoclosetime'] != 0)
			{
				$output .= "setTimeout('parent.jQuery.fancybox.close();'," . $options['autoclosetime']. ");\n";
			}
			$output .= "</script>\n";

			$output .= "</div>\n";

			$output .= "<!-- End of Modal Dialog Output -->\n";

			echo $output;
		}
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('fancyboxpack', WP_PLUGIN_URL . "/modal-dialog/fancybox/jquery.fancybox-1.3.4.pack.js", "", "1.3.4");
		wp_enqueue_script('jquerycookies', WP_PLUGIN_URL . "/modal-dialog/cookie.js", "", "1.0");
	}

	public function admin_enqueue_scripts()
	{
		wp_enqueue_script('jquerycookies', WP_PLUGIN_URL . "/modal-dialog/cookie.js", "", "1.0");
	}
}

global $my_modal_dialog_plugin;
$my_modal_dialog_plugin = new modal_dialog_plugin();

?>