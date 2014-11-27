<?php
/*
Plugin Name: WP Hidden Password Protected Pages 
Plugin URI: 
Description: The plugin is for hiding the password protected pages (posts) in WordPress.
Version: 1.0.0
Author: Kimiya Kitani
Author URI: https://profiles.wordpress.org/kimipooh/
*/

$wm = new wphppp();

class wphppp{
	var $set_op = 'wp-hidden-password-protected-pages_array';	// Save setting name in DB
	var $plugin_name = 'wp-hidden-password-protected-pages';
	var $lang_dir = 'lang';	// Language folder name
	var $cookie_time = 'wphppp_protected_cookie_time';
	var $cookie_time_max = 31622400;
	var $disabled_wphppp = 'wphppp_protected_disabled';
	var $settings;
	
	public function __construct(){
		$this->settings = get_option($this->set_op);
		$this->init_settings();
		register_activation_hook(__FILE__, array(&$this, 'installer'));
		// Add Setting to WordPress 'Settings' menu. 
		add_action('admin_menu', array(&$this, 'add_to_settings_menu'));
		add_action('plugins_loaded', array(&$this,'enable_language_translation'));

		// Main 
		if(!isset($this->settings[$this->disabled_wphppp]) || empty($this->settings[$this->disabled_wphppp]))
			add_filter('posts_where', array(&$this, 'my_posts_where'));

		// Optional
		add_action('after_setup_theme', array(&$this,'my_after_setup_theme'));
	}
	
	public function my_posts_where($where){
		global $wpdb;
		if(!is_singular() && !is_admin())
			$where .= " AND $wpdb->posts.post_password = ''";

		return $where;
	}
	public function my_after_setup_theme(){ 
		$settings = get_option($this->set_op);
		if(isset($settings[$this->cookie_time]) && isset( $_COOKIE['wp-postpass_' . COOKIEHASH] )):
			$cookie_time = intval(sanitize_text_field($settings[$this->cookie_time])); // Empty or Error: return 0

			if ( get_magic_quotes_gpc() )
				$co = esc_attr(stripslashes($_COOKIE['wp-postpass_' . COOKIEHASH]));
			else
				$co = esc_attr($_COOKIE['wp-postpass_' . COOKIEHASH]);

			if($cookie_time > 0 && $cookie_time <= $this->cookie_time_max):
				setcookie('wp-postpass_' . COOKIEHASH,  $co , time()+$cookie_time, COOKIEPATH);
			elseif($cookie_time == -1):
				setcookie('wp-postpass_' . COOKIEHASH,  $co , 0, COOKIEPATH);
			endif;
		endif;
	}

	public function enable_language_translation(){
		load_plugin_textdomain($this->plugin_name, false, dirname( plugin_basename( __FILE__ ) ) . '/' . $this->lang_dir . '/');
	}
	
	public function init_settings(){
		$this->settings['version'] = 100;
		$this->settings['db_version'] = 100;
	}
	
	public function installer(){
		update_option($this->set_op , $this->settings);
	}

	function add_to_settings_menu(){
		add_options_page(__('WP Hidden Password Protected Pages Settings', $this->plugin_name), __('WP Hidden Password Protected Pages Settings',$this->plugin_name), 'manage_options', __FILE__,array(&$this,'admin_settings_page'));
	}
	
	// Processing Setting menu for the plugin.
	function admin_settings_page(){
		$settings = get_option($this->set_op);
		$permission = false;
		// The user who can manage the WordPress option can only access the Setting menu of this plugin.
		if(current_user_can('manage_options')) $permission = true; 

		// Main
		if(isset($_POST[$this->disabled_wphppp])):
			$settings[$this->disabled_wphppp] =  esc_attr($_POST[$this->disabled_wphppp]);
		else:
			$settings[$this->disabled_wphppp] = '';
		endif;
		
		// Optional
		if(isset($_POST[$this->cookie_time])):
			$cookie_time = intval(sanitize_text_field($_POST[$this->cookie_time])); // Empty or Error: return 0
			if($cookie_time < -1 || $cookie_time > $this->cookie_time_max)
				$cookie_time = "";
			$settings[$this->cookie_time] =	$cookie_time;	
		else:
			$cookie_tile = "";
		endif;

		update_option($this->set_op , $settings);

?>
<?php
  if(isset($_POST[$this->cookie_time])):
?>
<div class="<?php print $this->plugin_name;?>_updated"><p><strong><?php _e('Updated', $this->plugin_name); ?></strong></p></div>
<?php
  endif;
?>
<div id="add_mime_media_admin_menu">
  <h2><?php _e('WP Hidden Password Protected Pages Settings', $this->plugin_name); ?></h2>
  
  <form method="post" action="">
     <fieldset style="border:1px solid #777777; width: 750px; padding-left: 6px;">
		<legend><h3><?php _e('How to use it', $this->plugin_name); ?></h3></legend>
		<div style="overflow:noscroll; height: 150px;">

		<p><?php _e('<p>When the plugin is turned on, the password protected pages will be hidden. The user who knows the access URL continues to be able to access to the pages. </p><p>The unlocked password protected page will be locked again after the idle time (Value of Idle time for Password Protected Pages).', $this->plugin_name); ?></p>
		</div>
	 </fieldset>
	 <br/><br/>
     <fieldset style="border:1px solid #777777; width: 750px; padding-left: 6px;">
		<legend><h3><?php _e('Turn off the plugin except Optional Settings.', $this->plugin_name); ?></h3></legend>
		<div style="overflow:noscroll; height: 100px;">
		<p>
		<?php if(!empty($settings[$this->disabled_wphppp])) $empty_flag = 'checked'; ?>
		<input type="checkbox" name="wphppp_protected_disabled" value="disabled" <?php print $empty_flag; ?>/>
			<?php _e('Turn off Hidden Password Protected Pages except Optional Settings.', $this->plugin_name); ?><br/>
		</p>
		<br/>
		<input type="submit" value="<?php _e('Save', $this->plugin_name);  ?>" />
		</div>
	</fieldset>
	 <br/><br/>
     <fieldset style="border:1px solid #777777; width: 750px; padding-left: 6px;">
		<legend><h3><?php _e('Optional Settings', $this->plugin_name); ?></h3></legend>
		<div style="overflow:noscroll; height: 200px;">

		<table><tr><td><strong>
		<?php _e('Idle time for Password Protected Pages: ', $this->plugin_name); ?> <input name="<?php print $this->cookie_time;?>" type="text" value="<?php print $cookie_time; ?>" size="15" maxlength="15"/> <?php _e('sec.', $this->plugin_name); ?></strong>
		<br/>
		<ul>
			<li><?php _e('[Default]: 864,000 sec (10 days).', $this->plugin_name); ?> </li>
			<li><?php _e('[Always Confirm Password]: -1', $this->plugin_name); ?></li>
			<li><?php _e('[Disable/Turn off]: empty, 0, less than -1, or more than 31,622,400 (366 days)<br/> * [Default] setting is used.', $this->plugin_name); ?></li>
		</ul>
		<br/>
    	 <input type="submit" value="<?php _e('Save', $this->plugin_name);  ?>" />
  		</form>
		</div>
		 </td></tr>
		</table>
	    </div>
     </fieldset>

<?php 
	}


}
?>
