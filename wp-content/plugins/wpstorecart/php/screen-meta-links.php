<?php

/**
 * @author Janis Elsts
 * @copyright 2010
 */


if ( !class_exists('wsScreenMetaLinks10') ):

//Load JSON functions for PHP < 5.2
if (!class_exists('Services_JSON')){
	//require ABSPATH . WPINC . '/class-json.php';
}
 
class wsScreenMetaLinks10 {
	var $registered_links; //List of meta links registered for each page. 
	
	/**
	 * Class constructor.
	 * 
	 * @return void
	 */
	function wsScreenMetaLinks10(){
		$this->registered_links = array();
		
		add_action('admin_notices', array(&$this, 'append_meta_links'));
		add_action('admin_print_styles', array(&$this, 'add_link_styles'));
	}
	
	/**
	 * Add a new link to the screen meta area.
	 * 
	 * Do not call this method directly. Instead, use the global add_screen_meta_link() function.
	 * 
	 * @param string $id Link ID. Should be unique and a valid value for a HTML ID attribute.
	 * @param string $text Link text.
	 * @param string $href Link URL.
	 * @param string|array $page The page(s) where you want to add the link.
	 * @param array $attributes Optional. Additional attributes for the link tag.
	 * @return void
	 */
	function add_screen_meta_link($id, $text, $href, $page, $attributes = null){
		if ( !is_array($page) ){
			$page = array($page);
		}
		if ( is_null($attributes) ){
			$attributes = array();
		}
		
		//Basically a list of props for a jQuery() call
		$link = compact('id', 'text', 'href');
		$link = array_merge($link, $attributes);
		
		//Add the CSS classes that will make the look like a proper meta link
		if ( empty($link['class']) ){
			$link['class'] = '';
		}
		$link['class'] = 'show-settings custom-screen-meta-link ' . $link['class'];
		
		//Save the link in each relevant page's list
		foreach($page as $page_id){
			if ( !isset($this->registered_links[$page_id]) ){
				$this->registered_links[$page_id] = array();
			}
			$this->registered_links[$page_id][] = $link;
		}
	}
	
	/**
	 * Output the JS that appends the custom meta links to the page.
	 * Callback for the 'admin_notices' action.
	 * 
	 * @access private
	 * @return void
	 */
	function append_meta_links(){
		global $hook_suffix;
		
		//Find links registered for this page
		$links = $this->get_links_for_page($hook_suffix);
		
		if ( empty($links) ){
			return;
		}

                global $wp_db_version;

                if ( $wp_db_version < 18715 ) { // If this is less than Wordpress 3.3 Beta 1
                    $screenmeta = '#screen-meta-links';
                }
                if ( $wp_db_version >= 18715 ) { // If this is equal to or greater than Wordpress 3.3 Beta 1
                    echo '<div id="screen-meta-links-wpsc"></div>';
                    $screenmeta = '#screen-meta-links-wpsc';
                }

		?>
                
		<script type="text/javascript">
			(function($, links){
				var container = $("<?PHP echo $screenmeta;?>");
				for(var i = 0; i < links.length; i++){
					container.append(
						$('<div/>')
							.attr({
								'id' : links[i].id + '-wrap',
								'class' : 'hide-if-no-js screen-meta-toggle custom-screen-meta-link-wrap'
							})
							.append( $('<a/>', links[i]) )
					);
				}
			})(jQuery, <?php echo $this->json_encode($links); ?>);
		</script>
		<?php
	}
	
	/**
	 * Get a list of custom screen meta links registered for a specific page.
	 * 
	 * @param string $page
	 * @return array
	 */
	function get_links_for_page($page){
		$links = array();
		
		if ( isset($this->registered_links[$page]) ){
			$links = array_merge($links, $this->registered_links[$page]);
		}
		$page_as_screen = $this->page_to_screen_id($page);
		if ( ($page_as_screen != $page) && isset($this->registered_links[$page_as_screen]) ){
			$links = array_merge($links, $this->registered_links[$page_as_screen]);
		}
		
		return $links;
	}
	
	/**
	 * Output the CSS code for custom screen meta links. Required because WP only
	 * has styles for specific meta links (by #id), not meta links in general.
	 * 
	 * Callback for 'admin_print_styles'.
	 * 
	 * @access private 
	 * @return void
	 */
	function add_link_styles(){
		global $hook_suffix;
		//Don't output the CSS if there are no custom meta links for this page.
		$links = $this->get_links_for_page($hook_suffix);
		if ( empty($links) ){
			return;
		}

                
		?>
		<style type="text/css">
		.custom-screen-meta-link-wrap {
			float: right;
			height: 22px;
			padding: 0;
			margin: 0 6px 0 0;
			font-family: "Lucida Grande", Verdana, Arial, "Bitstream Vera Sans", sans-serif;
			background: #e3e3e3;
			
			border-bottom-left-radius: 3px;
			border-bottom-right-radius: 3px;
			-moz-border-radius-bottomleft: 3px;
			-moz-border-radius-bottomright: 3px;
			-webkit-border-bottom-left-radius: 3px;
			-webkit-border-bottom-right-radius: 3px;
		}
		
		#screen-meta .custom-screen-meta-link-wrap a.custom-screen-meta-link {
			background-image: none;
			padding-right: 6px;
		}
		</style>
		<?php
	}
	
	/**
	 * Convert a page hook name to a screen ID.
	 * 
	 * @uses convert_to_screen()
	 * @access private
	 * 
	 * @param string $page
	 * @return string
	 */
	function page_to_screen_id($page){
		if ( function_exists('convert_to_screen') ){
			$screen = convert_to_screen($page);
			if ( isset($screen->id) ){
				return $screen->id;
			} else {
				return '';
			}
		} else {
			return str_replace( array('.php', '-new', '-add' ), '', $page);
		}
	}
	
	/**
	 * Back-wards compatible json_encode(). Used to encode link data before
	 * passing it to the JavaScript that actually creates the links.
	 * 
	 * @param mixed $data
	 * @return string
	 */
	function json_encode($data){
		if ( function_exists('json_encode') ){
			return json_encode($data);
		} else {
			$json = new Services_JSON();
        	return( $json->encodeUnsafe($data) );
		}
	}
	
}

global $ws_screen_meta_links_versions;
if ( !isset($ws_screen_meta_links_versions) ){
	$ws_screen_meta_links_versions = array();
} 
$ws_screen_meta_links_versions['1.0'] = 'wsScreenMetaLinks10';

endif;

/**
 * Add a new link to the screen meta area.
 * 
 * @param string $id Link ID. Should be unique and a valid value for a HTML ID attribute.
 * @param string $text Link text.
 * @param string $href Link URL.
 * @param string|array $page The page(s) where you want to add the link.
 * @param array $attributes Optional. Additional attributes for the link tag.
 * @return void
 */
function add_screen_meta_link($id, $text, $href, $page, $attributes = null){
	global $ws_screen_meta_links_versions;
		
	static $instance = null;
	if ( is_null($instance) ){
		//Instantiate the latest version of the wsScreenMetaLinks class
		uksort($ws_screen_meta_links_versions, 'version_compare');
		$className = end($ws_screen_meta_links_versions);
		$instance = new $className;
	}
	
	return $instance->add_screen_meta_link($id, $text, $href, $page, $attributes);
}

?>