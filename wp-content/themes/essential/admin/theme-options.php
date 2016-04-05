<?php
/**
 * Function used in theme options
 *
 * @package Essential
 * @since Essential 1.0
 */
 

if ( ! defined( 'ABSPATH' ) ) exit;



/* ---------------------------------------- */
/* Globals
/* ---------------------------------------- */

global $thm_base_dir;
$thm_base_dir = dirname(__FILE__);

global $thm_prefix;
$thm_prefix = 'thm_';

global $custom_css_file;
$custom_css_file = get_template_directory() . '/css/custom.css';
	
/* ---------------------------------------- */
/* Includes
/* ---------------------------------------- */
if( isset($_GET['page']) AND ($_GET['page'] == 'thm-settings' OR $_GET['page'] == 'essential-settings') ) {
	include($thm_base_dir . '/includes/options.php');
	include($thm_base_dir . '/includes/thm_functions.php');
	$fontsJson = file_get_contents(get_template_directory() . '/admin/fonts/fonts.json');
	global $thm_base_dir;
	$fontsArray = json_decode($fontsJson, true);
}

/* ---------------------------------------- */
/* Default data
/* ---------------------------------------- */
$default_options= array(  
			"thm_site-style"				=> "orange",
			"thm_include"					=> "file",
			"thm_title-font" 				=> "Carme",
			"thm_content-font"				=> "Carme",
			"thm_sidebar-position"			=> "layout-right",
			"thm_copyright-text"				=> "<p>Copyright ##year## - ##blog_name##. All rights reserved.</p>",
			"thm_home-header-text"			=> "##blog_name## - ##description##",
			"thm_active-tab"				=> 'featured',
			"thm_home-featured-products"	=> '8',
			"thm_home-new-products"			=> '8',
			"thm_home-best-products"		=> '8',
			"thm_home-sale-products"		=> '8',
			"thm_home-posts"				=> '0',
			"thm_slider-type"				=> 'default',
			"thm_slider-boxed"				=> 'on',
			"thm_slider-bgincrement"		=> '0',
			"thm_slider-autoplay"			=> 'on',
			"thm_slider-interval"			=> '5000',
			"thm_slider-ng-articles"		=> '5',
			"thm_slider-title_max_chars"	=> '50',
			"thm_slider-excerpt_max_chars"  => '200',
			"thm_home-tabs"					=> 'on'			
			);
if (!file_exists($custom_css_file) OR !is_writable($custom_css_file)) $default_options["thm_include"] = 'inline';


    
/* ---------------------------------------- */
/* Load data
/* ---------------------------------------- */
$thm_options = get_option('thm_settings');
if($thm_options == FALSE){
	$thm_options = $default_options;
	add_option('thm_settings', $thm_options);
}
/* ---------------------------------------- */
/* Adds subpage in appearance menu
/* ---------------------------------------- */
function thm_settings_menu() {
	add_theme_page( 'themes.php', 'Essential Options', 'administrator', 'essential-settings', 'thm_settings_page' );
	add_menu_page( 'themes.php', 'Essential Options', 'administrator', 'essential-settings', 'thm_settings_page' );
		
}
add_action('admin_menu', 'thm_settings_menu', 100);

/* ---------------------------------------- */
/* Registers the plugin settings
/* ---------------------------------------- */
function thm_register_settings() {
	register_setting( 'thm_settings_group', 'thm_settings' );
}
add_action( 'admin_init', 'thm_register_settings', 100 );

/* ---------------------------------------- */
/* Creates the submenu links in plugins page
/* ---------------------------------------- */
function thm_plugin_action_links($links, $file) {
    static $this_plugin; 
    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }
    if ($file == $this_plugin) {                                                                                                    // check to make sure we are on the correct plugin		
        $plugin_links[] = '<a href="' . site_url() . '/wp-admin/themes.php?page=thm-settings">'.__('Theme Options','thm').'</a>';   // link to what ever you want       
		foreach($plugin_links as $link) array_unshift($links, $link);                                                               // add the links to the list of links already there
    } 
    return $links;
}
add_filter('plugin_action_links', 'thm_plugin_action_links', 10, 2);

/* ---------------------------------------- */
/* Gets an image
/* ---------------------------------------- */
function thm_image($field_id,  $width = '', $height = '') {
	
	global $thm_options;
	
	if( isset($field_id) ) {
		$image_data = wp_get_attachment_image_src( $thm_options["$field_id"], '' );
		$url = $image_data[0];
		if( $height != '' && $width != '' ) {
			$height = $height;
			$width 	= $width;
		} else {
			$width = $image_data[1];
			$height = $image_data[2];
		}
		echo '<img src="'.$url.'" with="'.$width.'" height="'.$height.'" alt="" />';
		
	}
}

/* ---------------------------------------- */
/* Returns an image url
/* ---------------------------------------- */
function thm_get_image($field_id) {	
	global $thm_options;	
	if( isset($field_id) ) {
		$image_data = wp_get_attachment_image_src( $thm_options["$field_id"], '' );
		$url = $image_data[0];	
		return $url;	
	}
}

/* ---------------------------------------- */
/* Retrieves the get_option() value
/* ---------------------------------------- */
function thm_get_option($option_name) {
	$thm_options = get_option('thm_settings');
	if (isset($thm_options[$option_name])){
		return $thm_options[$option_name];
	} else {
		return FALSE;
	}
}

/* ---------------------------------------- */
/* Loads CSS
/* ---------------------------------------- */
function thm_admin_styles() {
	if( is_admin() ) {
		wp_enqueue_style('thickbox');
		wp_enqueue_style('thm-admin', get_template_directory_uri() . '/admin/includes/css/admin-styles.css');
		wp_enqueue_style('jquery-ui-custom', get_template_directory_uri() . '/admin/includes/css/jquery-ui-custom.css');
		wp_enqueue_style('essential-chozen', get_template_directory_uri() . '/admin/includes/css/chozen.css');
		wp_enqueue_style('gradient', get_template_directory_uri() . '/admin/includes/css/jquery.classygradient.css');
		wp_enqueue_style('colorpicker', get_template_directory_uri() . '/admin/includes/css/colorpicker.css');
		wp_enqueue_style('select2', get_template_directory_uri() . '/admin/includes/js/select2/select2.css');
		
	}
}
if (isset($_GET['page']) && (( $_GET['page'] == 'thm-settings') OR  ( $_GET['page'] == 'essential-settings')) ) {
	add_action('init', 'thm_admin_styles');
}

/* ---------------------------------------- */
/* Loads scripts
/* ---------------------------------------- */
function thm_admin_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('colorpicker');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('thm-admin-scripts',  get_template_directory_uri() . '/admin/includes/js/admin-scripts.js');
	wp_enqueue_script('media-uploader',  get_template_directory_uri() . '/admin/includes/js/media-uploader.js');
	wp_enqueue_script('jscolor',  get_template_directory_uri() . '/admin/includes/js/jscolor.js');
	wp_enqueue_script('select2-essential',  get_template_directory_uri() . '/admin/includes/js/select2/select2.js');
	wp_enqueue_script('classy-gradient',  get_template_directory_uri() . '/admin/includes/js/jquery.classygradient.js');
	wp_enqueue_script('colorpicker-gradient',  get_template_directory_uri() . '/admin/includes/js/colorpicker.js');
	wp_enqueue_script('select2-essential-sortable',  get_template_directory_uri() . '/admin/includes/js/select2.sortable.js', array('select2-essential'));
}
if (isset($_GET['page']) && (( $_GET['page'] == 'thm-settings') OR  ( $_GET['page'] == 'essential-settings'))){
	add_action('admin_print_scripts', 'thm_admin_scripts');
}

/* ---------------------------------------- */
/* Creates the settings page layout
/* ---------------------------------------- */
function thm_settings_page() {
	
	global $thm_options ,$theme_version	;
	
	?>
	
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('Theme Options Settings', 'thm'); ?></h2>
		
		<?php if ( ! isset( $_REQUEST['settings-updated'] ) ) $_REQUEST['settings-updated'] = false; ?>
		
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		
		      <div class="updated fade"><p><strong><?php _e( 'Options saved<br>', 'thm' ); ?></strong></p></div>

		<?php endif; ?>
		
		<?php thm_save_css_file() ?>
		
		<form method="post" action="options.php" class="thm_options_form">

			<?php settings_fields( addslashes('thm_settings_group') ); ?>
			
			<?php thm_show_custom_tabs(); ?>

			<?php thm_show_custom_fields(); ?>
			<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
				<input type="hidden" name="thm_settings[last-tab]" value="<?php echo $thm_options['last-tab']; ?>" id="last-tab" />
			<?php else: ?>
				<input type="hidden" name="thm_settings[last-tab]" value="" id="last-tab" />
			<?php endif; ?>	

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'thm' ); ?>" />
			</p>
			<p class="themeversion">Essential <?php echo $theme_version?></p>
			
		</form>
		
	</div>
	<?php
}