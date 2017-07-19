<?php
/*
/*
Plugin Name: Facebook Like - Content Locker
Plugin URI: 
Description: A powerful plugin that require your visitors to hit the Facebook Like button in order for them to view your blog
Version: 1.0.1
Author: M. Vlad Cristian
Author URI: http://www.blastersuite.com
License: GPLv2
*/

//add settings link
function add_settings_link($links, $file) {
static $this_plugin;
if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
 
if ($file == $this_plugin){
$settings_link = '<a href="options-general.php?page=fblike_lock">'.__("Settings", "fblike-lock").'</a>';
 array_unshift($links, $settings_link);
}
return $links;
 }
add_filter('plugin_action_links', 'add_settings_link', 10, 2 );
 
// Add a menu for our option page
add_action('admin_menu', 'fblike_lock_add_page');
function fblike_lock_add_page() {
	add_options_page( 'Facebook Like - Content Locker', 'Content Locker', 'manage_options', 'fblike_lock', 'fblike_lock_option_page' );
}

// Draw the option page
function fblike_lock_option_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		
		<h2>Facebook Like - Content Locker</h2>
		<form action="options.php" id="myform" method="post">
			<?php settings_fields( 'fblike_lock_options' ); ?>
			<?php do_settings_sections( 'fblike_lock' ); ?>
			<input name="Submit" type="submit" class="button-primary" value="Save Changes"/>
		</form>
	</div>
	<?php
}

// Register and define the settings
add_action( 'admin_init', 'fblike_lock_admin_init' );
function fblike_lock_admin_init(){
	register_setting(
		'fblike_lock_options',
		'fblike_lock_options'
	);
	add_settings_section(
		'fblike_lock_main',
		'Lock your blog\'s content with a powerful Facebook Content Locker',
		'fblike_lock_section_text',
		'fblike_lock'
	);
	add_settings_field(
		'fblike_lock_title',
		'Post title',
		'fblike_lock_setting_input',
		'fblike_lock',
		'fblike_lock_main',
		array("title")
	);
	add_settings_field(
		'fblike_lock_description',
		'Post Description',
		'fblike_lock_setting_input',
		'fblike_lock',
		'fblike_lock_main',
		array("description")
	);
	add_settings_field(
		'fblike_lock_url',
		'Like link',
		'fblike_lock_setting_input',
		'fblike_lock',
		'fblike_lock_main',
		array("url")
	);
	add_settings_field(
		'fblike_lock_sitename',
		'Site name',
		'fblike_lock_setting_input',
		'fblike_lock',
		'fblike_lock_main',
		array("sitename")
	);
	add_settings_field(
		'fblike_lock_admins',
		'Facebook Administrator for the page',
		'fblike_lock_setting_input',
		'fblike_lock',
		'fblike_lock_main',
		array("admins")
	);
	add_settings_field(
		'fblike_lock_image',
		'Post image link',
		'fblike_lock_setting_input',
		'fblike_lock',
		'fblike_lock_main',
		array("image")
	);
	add_settings_section(
		'fblike_lock_main2',
		'',
		'fblike_lock_section_text2',
		'fblike_lock'
	);
	$extra="";
	$options = get_option('fblike_lock_options');
	$value = $options["anicetextarea"];
	if(strpos($value,"[facebook]")===false)
	{
		$extra="<font color='red'>You did not add the tag [facebook] in your template. The Conent Locker will not work.</font>";
	}
	add_settings_field(
		'fblike_lock_color',
		'Background color',
		'fblike_lock_setting_color',
		'fblike_lock',
		'fblike_lock_main2',
		array("color")
	);
	add_settings_field(
		'fblike_lock_color2',
		'Transparency color',
		'fblike_lock_setting_color',
		'fblike_lock',
		'fblike_lock_main2',
		array("color2")
	);
	add_settings_field(
		'fblike_lock_tran',
		'Transparency',
		'fblike_lock_setting_tran',
		'fblike_lock',
		'fblike_lock_main2',
		array("tran")
	);
	add_settings_field(
		'fblike_lock_anicetextarea',
		'HTML Code for Content Locker<br><br><i>Use the tag <b>[facebook]</b> to add the Facebook Like button in your template<BR>Use <b>&lt p id="closeme"&gt Click here to close&lt/p&gt</b> to add a close button<BR><BR>'.$extra,
		'fblike_lock_setting_appearance',
		'fblike_lock',
		'fblike_lock_main2',
		array("anicetextarea")
	);
}
// Display and fill the transparency
function fblike_lock_setting_tran($param) {
	$options = get_option('fblike_lock_options');
	$value = $options[$param[0]];
	$name=$param[0];
	$extra="";
	if($value==="")
		$value="85";
	echo"0<input type='range' min='0' max='100' step='1' id='fblike_lock_$name' name='fblike_lock_options[$name]' value='$value' width='300' onchange='OnSliderChanged (this)' />100&nbsp;&nbsp; <span id='slider1Value' class='slider1Value'></span><br><i>0 - Invisible 100-Solid";
}
// Display and fill the color field
function fblike_lock_setting_color($param) {
	$options = get_option('fblike_lock_options');
	$value = $options[$param[0]];
	$name=$param[0];
	$extra="";
	if($value==="")
		$value="#000000";
	echo"
	<label for='fblike_lock_$name'><input type='text' id='fblike_lock_$name' name='fblike_lock_options[$name]' value='$value' /> Pick color</label>
    <div id='ilctabscolorpicker_$name'></div>";
}
// Display and fill the form field for appearance part
function fblike_lock_setting_appearance($param) {
	$options = get_option('fblike_lock_options');
	$value = $options[$param[0]];
	$name=$param[0];
	// TinyMCE init settings
	$initArray = array (
	    'width' => '500',
		'height'=>'250',
	    'skin' => 'wp_theme',
	    'theme_advanced_toolbar_location' => 'top',
	    'theme_advanced_toolbar_align' => 'left',
	    'theme_advanced_statusbar_location' => 'bottom',
		'theme_advanced_resizing' => false,
		'theme_advanced_resize_horizontal' => false,
	    'relative_urls' => false,
	    'remove_script_host' => false,
		'convert_urls' => false,
	    'apply_source_formatting' => false,
	    'remove_linebreaks' => true,
		'paste_convert_middot_lists' => true,
	    'paste_remove_spans' => true,
	    'paste_remove_styles' => true,
	    'accessibility_focus' => true,
	    'tab_focus' => ':prev,:next',
		'editor_selector'=>'fblike_lock_anicetextarea'
	);
	wp_tiny_mce( false , // true makes the editor "teeny"
	    $initArray
	);
	echo '<p align="left">	    <a class="button toggleVisual">Visual</a>	    <a class="button toggleHTML">HTML</a>	</p>';
	echo "<textarea style='width: 500px; height: 250px;' class='fblike_lock_anicetextarea' id='fblike_lock_anicetextarea' name='fblike_lock_options[$name]' type='text' size='60'  />$value</textarea>";
}

// Draw the section header for colors
function fblike_lock_section_text2() {
	echo '<BR><h2>Configure the appearance of your Content Locker</h2>';
}
// Draw the section header
function fblike_lock_section_text() {
	echo '<iframe width=700 height=140" frameborder="0"  src="http://blastersuite.com/iframe/plugin.php"></iframe><h2>Enter the Facebook Post Meta Data (will appear in a Facebook Post when a user likes your page)</h2>';
}

// Display and fill the form field
function fblike_lock_setting_input($param) {
	$options = get_option('fblike_lock_options');
	$value = $options[$param[0]];
	$name=$param[0];
	$extra="";
	// echo the field
	if($name==="url" && $value==="")
		$value=get_option('home');
	if($name==="url")
		{
		$val=get_option('home');
		$extra="<input type='button' value='Default link' class='button-secondary' OnClick='document.getElementById(\"fblike_lock_url\").value=\"$val\"; return false;'/>";
		}
	echo "<input id='fblike_lock_$name' name='fblike_lock_options[$name]' type='text' size='60' value='$value' />".$extra;
}

//Register the onload event
function add_my_scripts() {
    wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-form');
	$myStyleUrl=WP_PLUGIN_URL . '/facebook-like-content-locker/general.css';
	wp_register_style('myStyleSheets', $myStyleUrl);
	wp_enqueue_style( 'myStyleSheets');
	
	if (!is_admin()) {
	wp_register_script('newscriptt','http://connect.facebook.net/en_US/all.js#xfbml=1');
	wp_enqueue_script('newscriptt');
    wp_register_script('newscript', plugins_url('/js/pop.js', __FILE__), array('jquery'));
	wp_enqueue_script('newscript');
	
	}
	else
	{
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
	wp_register_script('tinymc', plugins_url('/js/tinymc.js', __FILE__), array('jquery'));
	wp_enqueue_script('tinymc');
	}
}
add_action('init', 'add_my_scripts');



// Echo the details in the header
add_action( 'wp_head', 'fblike_lock_hook',1);
function fblike_lock_hook() {
	$options = get_option( 'fblike_lock_options' );
	$op=$options["tran"]/100;
	echo "<script type='text/javascript'>var opac=".$op.";</script>";
	$css_link=WP_PLUGIN_URL . '/facebook-like-content-locker/general.css';
	echo '<!--Content Locker Start -->
<meta property="og:title" content="'.$options['title'].'"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="'.$options['url'].'"/>
<meta name="description" content="'.$options['description'].'">
<meta property="og:image" content="'.$options['image'].'"/>
<meta property="og:site_name" content="'.$options['sitename'].'">
<meta property="fb:admins" content="'.$options['admins'].'">
<!--Content Locker End -->
';
}

//echo the details in the body
add_action( 'wp_footer', 'fblike_lock_hook_body',2);
function fblike_lock_hook_body() {
	$options = get_option( 'fblike_lock_options' );
	$url=urlencode($options["url"]);
	$code=$options["anicetextarea"];
	$bgcolor=$options["color"];
	$bgcolor2=$options["color2"];
	$fbcode='<fb:like href="'.$url.'" layout="button_count" id="f3"></fb:like>';
	$code=str_replace("[facebook]",$fbcode,$code);
	echo '<!--Content Locker Start -->
		<div id="popupContact" bgcolor="red" style="background:'.$bgcolor.'">
        <!--<a id="popupContactClose">x</a><br>-->
		'.$code.'
		</div>
		<div id="backgroundPopup" style="background:'.$bgcolor2.'"></div>
		<!--Content Locker End -->
';
}

//default values
register_activation_hook( __FILE__, 'wp_ozh_yourls_activate_plugin' );
function wp_ozh_yourls_activate_plugin() {
$val=get_option('home');
	$ozh_yourls_default = array(
					'title' 				=> 'My Site Title',
					'description' 				=>'Visit my site to find our great details',
					'url' 			=> $val,
					'sitename'			=> 'My Site',
					'admins'		=> 'enter Facebook user ID',
					'image' 		=> '',
					'color' 		=> '#132f63',
					'color2' 		=> '#241919',
					'tran' 		=> '80',
					'anicetextarea' 	=> '<p style="text-align: center;">Like it to unlock it!</p><p style="text-align: center;">[facebook]</p><p style="text-align: center;">Don\'t Be AntiSocial</p><p style="text-align: center;">Let your friends know where they can watch free movies online</p><p id="closeme" style="text-align: center;">Click here to close this popup</p>',
					);
	add_option('fblike_lock_options',$ozh_yourls_default);
}