<?php
/**
 * Functions
 *
 * @package Essential
 * @since Essential 1.0
 */
global $theme_version; 
$theme_version = '1.1.21';
 
/*-----------------------------------------------------------------------------------*/
/* Load the theme-specific files.
/*-----------------------------------------------------------------------------------*/

include ( get_template_directory() . '/admin/theme-options.php' );
include ( get_template_directory() . '/admin/admin-function.php' );
include ( get_template_directory() . '/admin/theme-actions.php' );
include ( get_template_directory() . '/admin/theme-meta-boxes.php');
include ( get_template_directory() . '/admin/shortcodes.php' );
include ( get_template_directory() . '/admin/content-slider/essential-content-slider.php' );

if (is_woocommerce_activated()) include ( get_template_directory() . '/admin/woocommerce.php' );



add_theme_support('woocommerce');
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');

if ( !isset($content_width) ) $content_width = 940;

if (is_woocommerce_activated()){
	if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
		add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	} else {
		define( 'WOOCOMMERCE_USE_CSS', false );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Register picture sizes */
/*-----------------------------------------------------------------------------------*/
add_image_size( 'slide', 300, 300, TRUE );

/*-----------------------------------------------------------------------------------*/
/* Register widgetized areas */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'the_widgets_init')) {
	function the_widgets_init() {
	    if ( !function_exists( 'register_sidebar') ) return;	
	    register_sidebar(array( 'name' => 'Primary','id' => 'primary','description' => "Normal full width sidebar", 'before_widget' => '<section id="%1$s" class="widget %2$s">','after_widget' => '</section>','before_title' => '<h3 class="widget-title bg-color2">','after_title' => '</h3>'));   
	    register_sidebar(array( 'name' => 'Footer 1','id' => 'footer-1', 'description' => "Widetized footer", 'before_widget' => '<section id="%1$s" class="widget %2$s">','after_widget' => '</section>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => 'Footer 2','id' => 'footer-2', 'description' => "Widetized footer", 'before_widget' => '<section id="%1$s" class="widget %2$s">','after_widget' => '</section>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => 'Footer 3','id' => 'footer-3', 'description' => "Widetized footer", 'before_widget' => '<section id="%1$s" class="widget %2$s">','after_widget' => '</section>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => 'Footer 4','id' => 'footer-4', 'description' => "Widetized footer", 'before_widget' => '<section id="%1$s" class="widget %2$s">','after_widget' => '</section>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
		register_sidebar(array( 'name' => 'Footer 5','id' => 'footer-5', 'description' => "Widetized footer", 'before_widget' => '<section id="%1$s" class="widget %2$s">','after_widget' => '</section>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => 'Footer 6','id' => 'footer-6', 'description' => "Widetized footer", 'before_widget' => '<section id="%1$s" class="widget %2$s">','after_widget' => '</section>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => 'Footer 7','id' => 'footer-7', 'description' => "Widetized footer", 'before_widget' => '<section id="%1$s" class="widget %2$s">','after_widget' => '</section>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => 'Footer 8','id' => 'footer-8', 'description' => "Widetized footer", 'before_widget' => '<section id="%1$s" class="widget %2$s">','after_widget' => '</section>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
	}
}
add_action( 'init', 'the_widgets_init' );

/*-----------------------------------------------------------------------------------*/
/* check for sidebar position */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp', 'essential_sidebar_position' );

/*-----------------------------------------------------------------------------------*/
/* Register scripts */
/*-----------------------------------------------------------------------------------*/
if (!is_admin()) add_action('wp_enqueue_scripts', 'essential_front_end_scripts');

function essential_front_end_scripts (){
	wp_enqueue_style( 'woocommerce-stylesheet', get_template_directory_uri() .'/woocommerce/woocommerce.css');
	wp_enqueue_style( 'main-stylesheet', get_stylesheet_uri(),array('woocommerce-stylesheet') );
	wp_enqueue_script( 'touchSwipe',  get_template_directory_uri() . '/js/jquery.touchSwipe.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'Tiny-carusel',  get_template_directory_uri() . '/js/jquery.tinycarousel.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'Essential',  get_template_directory_uri() . '/js/custom.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery-selectbox',  get_template_directory_uri() . '/js/jquery.selectbox-0.2.min.js', array( 'jquery' ) );
	wp_localize_script( 'Essential', 'EssentialAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'ajaxnonce' => wp_create_nonce( 'ajaxnonce' ) ) );
	if( thm_get_option('thm_include') != 'inline' or ( thm_get_option('thm_include') == 'inline' && thm_get_option('thm_site-style') != 'custom' ))
		wp_enqueue_style( 'essential-color-style', get_template_directory_uri() .'/css/'.thm_get_option('thm_site-style').'.css',array('main-stylesheet') );
	if( thm_get_option('thm_site-style') != 'custom' && thm_get_option('thm_include') != 'inline')
		wp_enqueue_style( 'essential-custom-style', get_template_directory_uri() .'/css/custom.css', array('main-stylesheet') );
	if (is_woocommerce_activated() && is_product()){
		wp_enqueue_script( 'Elevate-zoom',  get_template_directory_uri() . '/js/jquery.elevateZoom-2.5.5.min.js', array( 'jquery' ) );
		}
}

/*-----------------------------------------------------------------------------------*/
/* Register WP Menus */
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu', 'esential' ) ) );
	register_nav_menus( array( 'top-menu' => __( 'Top Menu', 'esential' ) ) );
	register_nav_menus( array( 'mobile-menu' => __( 'Mobile menu', 'esential' ) ) );
}

/*-----------------------------------------------------------------------------------*/
/* Register languages  */
/*-----------------------------------------------------------------------------------*/
add_action('after_setup_theme', 'essential_lang_setup');
function essential_lang_setup(){
    load_theme_textdomain('essential', get_template_directory() . '/languages');
}
/*-----------------------------------------------------------------------------------*/
/* Make sure that widget do shortcodes */
/*-----------------------------------------------------------------------------------*/
add_filter('widget_text', 'do_shortcode');
/*-----------------------------------------------------------------------------------*/
/* prints HTML with meta information for the current post-date/time and author.
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'custom_posted_on' ) ) :
	function custom_posted_on() {
		printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'toj' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'essential' ), get_the_author() ) ),
			esc_html( get_the_author() )
		);
	}
endif;

/*-----------------------------------------------------------------------------------*/
/* Add wishlist and compare status to a product */
/*-----------------------------------------------------------------------------------*/
add_action( 'woocommerce_single_product_summary', 'essential_wishlist_and_compare_single_product', 35);
function essential_wishlist_and_compare_single_product(){
	global $product;
	
	echo '<div class="clear"></div><div class="action_bar">';
	do_action('essential_start_action_bar');
	
	if(thm_get_option('thm_wishlist-page')){
		if (essential_is_product_in_wishlist($product->id))
			echo '<div class="wishlist"><a href="#" data-product-id="'.$product->id.'" class="remove-wishlist" data-icon="&#xe01a;">'.__('Remove from Wishlist','essential').'</a></div>';
		else
			echo '<div class="wishlist"><a href="#" data-product-id="'.$product->id.'" class="add-wishlist" data-icon="&#xe01a;">'.__('Add to Wishlist','essential').'</a></div>';
	}
	if(thm_get_option('thm_compare-page')){			
		if (essential_is_product_in_compare($product->id))
			echo '<div class="compare"><a data-product-id="'.$product->id.'" class="remove-compare" data-icon="&#xe09a;">'.__('Remove from Compare','essential').'</a></div>';
		else
			echo '<div class="compare"><a data-product-id="'.$product->id.'" class="add-compare" data-icon="&#xe09a;">'.__('Add to Compare','essential').'</a></div>';
	}	
	do_action('essential_end_action_bar');
	echo '</div>';
}
add_action( 'wp_ajax_nopriv_add_to_wishlist', 'essential_add_to_wishlist' );
add_action( 'wp_ajax_add_to_wishlist', 'essential_add_to_wishlist_logged' );

/*-----------------------------------------------------------------------------------*/
/* Check if product is in wishlist  */
/*-----------------------------------------------------------------------------------*/
function essential_is_product_in_wishlist($product_id){
	$current_user = wp_get_current_user();
	if ( is_user_logged_in() ){
		$wishlist 	= maybe_unserialize(get_user_meta( $current_user->ID, 'essential_wishlist' , TRUE ));
	} else {
		$wishlist 	= essential_getcookie('essential_wishlist');
	}
	if (!is_array($wishlist)) return FALSE;
	if (in_array($product_id, $wishlist)) return TRUE;
	return FALSE;
}

/*-----------------------------------------------------------------------------------*/
/* Check if product is in compare  */
/*-----------------------------------------------------------------------------------*/
function essential_is_product_in_compare($product_id){
	$current_user = wp_get_current_user();
	if ( is_user_logged_in() ){
			$compare 	= maybe_unserialize(get_user_meta( $current_user->ID, 'essential_compare' , TRUE ));
	} else {
			$compare 	= essential_getcookie('essential_compare');
	}	
	if (!is_array($compare)) return FALSE;
	if (in_array($product_id, $compare)) return TRUE;
	return FALSE;
}

/*-----------------------------------------------------------------------------------*/
/* Add product to wishlist (if not logged in, via cookie) */
/*-----------------------------------------------------------------------------------*/ 
function essential_add_to_wishlist() {
	$product_id    = $_POST['product_id'];
	$nonce         = $_POST['nonce'];
	if ( !wp_verify_nonce( $nonce, 'ajaxnonce' ) ) die ();
	if ($product_id){
		$wishlist = essential_getcookie('essential_wishlist');
		if(!in_array($product_id, $wishlist)) $wishlist [] = $product_id;
		essential_setcookie('essential_wishlist',$wishlist );
		$wishlist_page = thm_get_option('thm_wishlist-page');
	 	echo sprintf (__('Added to %s wishlist %s', 'essential'), '<a href="'.get_page_link($wishlist_page).'">', '</a>');
	}
	exit;
}

/*-----------------------------------------------------------------------------------*/
/* Add product to wishlist if logged in */
/*-----------------------------------------------------------------------------------*/
function essential_add_to_wishlist_logged() {
	$current_user  = wp_get_current_user();
	$product_id    = $_POST['product_id'];
	$nonce         = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) )	die ();
	if ($product_id){
		$wishlist = maybe_unserialize(get_user_meta( $current_user->ID, 'essential_wishlist' , TRUE ));
		if ($wishlist){
			if(!in_array($product_id, $wishlist)) $wishlist [] = $product_id;
		} else {
				$wishlist [] = $product_id;
		}	
		update_user_meta( $current_user->ID, 'essential_wishlist', maybe_serialize($wishlist) );
		$wishlist_page = thm_get_option('thm_wishlist-page');
		echo sprintf (__('Added to %s wishlist %s', 'essential'), '<a href="'.get_page_link($wishlist_page).'">', '</a>');		
	}
 	exit;
}
add_action( 'wp_ajax_nopriv_remove_from_wishlist', 'essential_remove_from_wishlist' );
add_action( 'wp_ajax_remove_from_wishlist', 'essential_remove_from_wishlist_logged' );

/*-----------------------------------------------------------------------------------*/
/* Remove product from wishlist (if not logged in, via cookie) */
/*-----------------------------------------------------------------------------------*/
function essential_remove_from_wishlist() {
	$product_id    = $_POST['product_id'];
	$nonce         = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) )	die ();
	if ($product_id){
		$wishlist = essential_getcookie('essential_wishlist');
		if(($key = array_search($product_id, $wishlist)) !== false) unset($wishlist[$key]);
		essential_setcookie('essential_wishlist',$wishlist );
		$wishlist_page = thm_get_option('thm_wishlist-page');
		echo sprintf (__('Removed from %s wishlist %s', 'essential'), '<a href="'.get_page_link($wishlist_page).'">', '</a>');
	}
	exit;
}

/*-----------------------------------------------------------------------------------*/
/* Remove product from wishlist if logged in */
/*-----------------------------------------------------------------------------------*/
function essential_remove_from_wishlist_logged() {
	$current_user  = wp_get_current_user();
	$product_id    = $_POST['product_id'];
	$nonce         = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) )	die ();
	if ($product_id){
		$wishlist = maybe_unserialize(get_user_meta( $current_user->ID, 'essential_wishlist' , TRUE ));
		if ($wishlist){
			if(($key = array_search($product_id, $wishlist)) !== false) unset($wishlist[$key]);
		} 
		update_user_meta( $current_user->ID, 'essential_wishlist', maybe_serialize($wishlist) );
		$wishlist_page = thm_get_option('thm_wishlist-page');
		echo sprintf (__('Removed from %s wishlist %s', 'essential'), '<a href="'.get_page_link($wishlist_page).'">', '</a>');
	}
 	exit;
}

/*-----------------------------------------------------------------------------------*/
/* Empty wishlist (if not logged in, via cookie) */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_empty_wishlist', 'essential_empty_wishlist' );
add_action( 'wp_ajax_empty_wishlist', 'essential_empty_wishlist_wishlist_logged' );
function essential_empty_wishlist() {	
	$nonce = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) )	die ();
	essential_setcookie('essential_wishlist',$wishlist );
	_e('Wishlist is cleared', 'essential');		
	exit;
}

/*-----------------------------------------------------------------------------------*/
/* Empty wishlist if logged in */
/*-----------------------------------------------------------------------------------*/
function essential_empty_wishlist_wishlist_logged() {
	$current_user  = wp_get_current_user();
	$nonce         = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) ) die ();
	if ($current_user){
		delete_user_meta( $current_user->ID, 'essential_wishlist');
		_e('Wishlist is cleared', 'essential');		
	}
 	exit;
} 

/*-----------------------------------------------------------------------------------*/
/* Add to compare (if not logged in, via cookie) */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_add_to_compare', 'essential_add_to_compare' );
add_action( 'wp_ajax_add_to_compare', 'essential_add_to_compare_logged' ); 
function essential_add_to_compare() {
	$product_id    = $_POST['product_id'];
	$nonce         = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) )	die ();
	if ($product_id){
		$wishlist = essential_getcookie('essential_compare');
		if(!in_array($product_id, $wishlist)) $wishlist [] = $product_id;
		essential_setcookie('essential_compare',$wishlist );
		$compare_page = thm_get_option('thm_compare-page');
		echo sprintf (__('Added to  %s compare %s', 'essential'), '<a href="'.get_page_link($compare_page).'">', '</a>');		
	}
	exit;
}

/*-----------------------------------------------------------------------------------*/
/* Add to compare if logged in */
/*-----------------------------------------------------------------------------------*/
function essential_add_to_compare_logged() {
	$current_user  = wp_get_current_user();
	$product_id    = $_POST['product_id'];
	$nonce         = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) )	die ();
	if ($product_id){
		$wishlist = maybe_unserialize(get_user_meta( $current_user->ID, 'essential_compare' , TRUE ));
		if ($wishlist){
			if(!in_array($product_id, $wishlist)) $wishlist [] = $product_id;
		} else {
			$wishlist [] = $product_id;
		}	
		update_user_meta( $current_user->ID, 'essential_compare', maybe_serialize($wishlist) );
		$compare_page = thm_get_option('thm_compare-page');
		echo sprintf (__('Added to  %s compare %s', 'essential'), '<a href="'.get_page_link($compare_page).'">', '</a>');
	}
 	exit;
}

/*-----------------------------------------------------------------------------------*/
/* Remove from compare (if not logged in, via cookie) */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_remove_from_compare', 'essential_remove_from_compare' );
add_action( 'wp_ajax_remove_from_compare', 'essential_remove_from_compare_logged' );
function essential_remove_from_compare() {
	$product_id    = $_POST['product_id'];
	$nonce         = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) )	die ();
	if ($product_id){
		$wishlist = essential_getcookie('essential_compare');
		if(($key = array_search($product_id, $wishlist)) !== false) unset($wishlist[$key]);
		essential_setcookie('essential_compare',$wishlist );
		$compare_page = thm_get_option('thm_compare-page');
		echo sprintf (__('Removed from  %s compare %s', 'essential'), '<a href="'.get_page_link($compare_page).'">', '</a>');		
	}
	exit;
}

/*-----------------------------------------------------------------------------------*/
/* Remove from compare if logged in */
/*-----------------------------------------------------------------------------------*/
function essential_remove_from_compare_logged() {
	$current_user  = wp_get_current_user();
	$product_id    = $_POST['product_id'];
	$nonce         = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) ) die ();
	if ($product_id){
		$wishlist = maybe_unserialize(get_user_meta( $current_user->ID, 'essential_compare' , TRUE ));
		if ($wishlist){
			if(($key = array_search($product_id, $wishlist)) !== false)	unset($wishlist[$key]);
		} 
		update_user_meta( $current_user->ID, 'essential_compare', maybe_serialize($wishlist) );
		$compare_page = thm_get_option('thm_compare-page');
		echo sprintf (__('Removed from  %s compare %s', 'essential'), '<a href="'.get_page_link($compare_page).'">', '</a>');
	}
 	exit;
}

/*-----------------------------------------------------------------------------------*/
/* Empty compare list (if not logged in, via cookie) */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_empty_compare', 'essential_empty_compare' );
add_action( 'wp_ajax_empty_compare', 'essential_empty_compare_logged' );
function essential_empty_compare() {	
	$nonce = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) )	die ();
	essential_setcookie('essential_compare',$wishlist );
	_e('Compare list is cleared', 'essential');		
	exit;
}

/*-----------------------------------------------------------------------------------*/
/* Empty compare list if logged in */
/*-----------------------------------------------------------------------------------*/
function essential_empty_compare_logged() {
	$current_user  = wp_get_current_user();
	$nonce         = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajaxnonce' ) )	die ();
	if ($current_user){
		delete_user_meta( $current_user->ID, 'essential_compare');
		_e('Compare list is cleared', 'essential');		
	}
 	exit;
}
add_action('woocommerce_after_wishlist_loop', 'essential_remove_all_from_wishlist',1);

/*-----------------------------------------------------------------------------------*/
/* Output remove all from wishlist link */
/*-----------------------------------------------------------------------------------*/
function essential_remove_all_from_wishlist(){
	echo '<div class="wishlist"><a class="empty-wishlist" href="#">'.__('Remove all from wishlist', 'essential').'</a></div>';	
}
function essential_wishlist_body_class($classes){
	$classes[] = 'woocommerce';
	return $classes;
}
add_action('woocommerce_after_compare_loop', 'essential_remove_all_from_compare',1);

/*-----------------------------------------------------------------------------------*/
/* Output remove all from compare link */
/*-----------------------------------------------------------------------------------*/
function essential_remove_all_from_compare(){
	echo '<div class="compare clearfix"><a class="empty-compare button" href="#">'.__('Remove all from compare', 'essential').'</a></div>';	
}
function essential_compare_body_class($classes){
	$classes[] = 'woocommerce';
	$classes[] = 'layout-full';
	return $classes;
}

/*-----------------------------------------------------------------------------------*/
/* Set a cookie */
/*-----------------------------------------------------------------------------------*/ 
if( !function_exists( 'essential_setcookie' ) ) {
    function essential_setcookie( $name, $value = array(), $time = null ) {
        $time = $time != null ? $time : time() + 60 * 60 * 24 * 30;        
        $value = maybe_serialize( stripslashes_deep( $value ) );
        $expiration = apply_filters( 'essential_wishlist_cookie_expiration_time', $time ); // Default 30 days        
        return setcookie( $name, $value, $expiration, '/' );
    }
}

/*-----------------------------------------------------------------------------------*/
/* Get a cookie */
/*-----------------------------------------------------------------------------------*/  
if( !function_exists( 'essential_getcookie' ) ) {
    function essential_getcookie( $name ) {
        if( isset( $_COOKIE[$name] ) ) return maybe_unserialize( stripslashes( $_COOKIE[$name] ) );
        return array();
    }
}

/*-----------------------------------------------------------------------------------*/
/* Destroy a cookie */
/*-----------------------------------------------------------------------------------*/   
if( !function_exists ( 'essential__destroycookie' ) ) {
    function essential__destroycookie( $name ) {
        essential_setcookie( $name, array(), time() - 3600 );
    }
}

/*-----------------------------------------------------------------------------------*/
/* Limit search to post and pages */
/*-----------------------------------------------------------------------------------*/  
function limit_search_post_type($query) {
	
    if (!is_admin() && $query->is_search && !$_GET['post_type']) {
        $query->set('post_type',array('post','page'));
    }
	return $query;
}
add_filter('pre_get_posts','limit_search_post_type');

/*-----------------------------------------------------------------------------------*/
/* Turncate text */
/*-----------------------------------------------------------------------------------*/  
if( !function_exists ( 'essential_shorten_text' ) ) {
	function essential_shorten_text($text, $chars_limit = 100) {
		if($chars_limit != 0 ){	
		  $chars_text = strlen($text);
		  $text = $text." ";
		  $text = substr($text,0,$chars_limit);
		  $text = substr($text,0,strrpos($text,' '));		 
		  if ($chars_text > $chars_limit) $text = $text."..."; 
		  return $text;			 
		} 
		return $text;		
	}
}

/*-----------------------------------------------------------------------------------*/
/* Special megamenu walker */
/*-----------------------------------------------------------------------------------*/ 
class essential_walker_nav_menu extends Walker_Nav_Menu {
	
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) ) $args[0]->children_number = !empty( $children_elements[$element->$id_field] ) ? count($children_elements[$element->$id_field]) : 0;
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    // add main and sub classes to li's and links
    function start_el(  &$output, $item, $depth = 0, $args = array(), $id = 0  ) {
        
        global $wp_query;
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );

        // depth dependent classes
        $depth_classes = array(
            ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
            ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
            ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
            'menu-item-depth-' . $depth
        );
        $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );
		$children_number = isset($args->children_number) ? $args->children_number : '0';
		
        // passed classes
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
		$class_names .= ' menu-item-children-' . $children_number;

        // build html
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

        // link attributes
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
        $childs = count(get_pages('child_of='.$item->object_id.'&parent='.$item->object_id));
        $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s %5$s</a>%6$s',
            $args->before,
            $attributes,
            $args->link_before,
            apply_filters( 'the_title', $item->title, $item->ID ),
            $args->link_after,
            $args->after
        );

        // build html
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

/*-----------------------------------------------------------------------------------*/
/* Pagination */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'essential_pagination' ) ) {
	function essential_pagination() {
		get_template_part( 'woocommerce/loop/pagination' );
	}
}
/*-----------------------------------------------------------------------------------*/
/* Ajax handler for select2 */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_ajax_prospekt_ajax_handler',  'callback_prospekt_ajax_handler'  );
if( !function_exists ( 'callback_prospekt_ajax_handler' ) ) {
	function callback_prospekt_ajax_handler(){
		global $wpdb;

        $ajax_method = isset( $_POST['action_type'] ) ? $_POST['action_type'] : false;
		if ( $ajax_method && is_admin() ) {
            include( get_template_directory(). '/admin/includes/ajax.php' );
            $ajax = new prospekt_ajax();
            if ( method_exists( $ajax, $ajax_method ) ) {
                echo $ajax->$ajax_method( $_POST );
            }
            exit;
        }
	}
}

/*-----------------------------------------------------------------------------------*/
/* You can add your custom functions below */
/*-----------------------------------------------------------------------------------*/

