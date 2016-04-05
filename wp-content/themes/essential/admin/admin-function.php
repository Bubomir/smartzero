<?php
/**
 * Function used in theme
 *
 * @package Essential
 * @since Essential 1.0
 */
 
 
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------*/
/* Custom head generator */
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'custom_head' ) ) {
	function custom_head() {
		do_action( 'custom_head');
	}
}

/*-----------------------------------------------------------------------------------*/
/* Enable Home link in WP Menus */
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'home_page_menu_args' ) ) {
	function home_page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Check for Widgets in Widget-Areas */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'is_sidebar_active' ) ) {
	function is_sidebar_active($index) {
	    global $wp_registered_sidebars;
	    $widgetcolums = wp_get_sidebars_widgets();
	    if (isset($widgetcolums[$index]) &&  $widgetcolums[$index])
	        return true;
	    return false;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Check how many active widget area in upper footer */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'upper_footer_count' ) ) {
	function upper_footer_count() {
		$count = 0;
	    for ($i=0; $i < 5; $i++) { 
			if(is_sidebar_active('footer-'.$i)) ++$count; 
		}
	    return $count;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Check how many active widget area in bottom footer */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'bottom_footer_count' ) ) {
	function bottom_footer_count() {
		$count = 0;
	    for ($i=5; $i < 9; $i++) { 
			if(is_sidebar_active('footer-'.$i)) ++$count; 
		}
	    return $count;
	}
}

/*-----------------------------------------------------------------------------------*/
/*  Returns true if a blog has more than 1 category */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'custom_categorized_blog' ) ) {
	function custom_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
			// Create an array of all the categories that are attached to posts
			$all_the_cool_cats = get_categories( array(
				'hide_empty' => 1,
			) );
	
			// Count the number of categories that are attached to the posts
			$all_the_cool_cats = count( $all_the_cool_cats );
	
			set_transient( 'all_the_cool_cats', $all_the_cool_cats );
		}
	
		if ( '1' != $all_the_cool_cats ) {
			// This blog has more than 1 category so toj_categorized_blog should return true
			return true;
		} else {
			// This blog has only 1 category so toj_categorized_blog should return false
			return false;
		}
	}
}


/*-----------------------------------------------------------------------------------*/
/*  Template for comments and pingbacks. */
/*  Used as a callback by wp_list_comments() for displaying the comments. */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'custom_comment' ) ) {
	function custom_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e('Pingback:', 'essential'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('(Edit)', 'essential'), ' '); ?></p>
		<?php
		break;
		default:
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<?php echo get_avatar($comment, 68); ?>
				<div class="comment-content"><?php comment_text(); ?></div>
				
				<footer>
					<div class="comment-author vcard">						
						<?php printf(__('<span class="says">Writen by:</span> %s <span class="sep">|</span>', 'essential'), sprintf('<cite class="fn">%s</cite>', get_comment_author_link())); ?>
					</div>
					<div class="comment-meta commentmetadata">
						<a href="<?php echo esc_url(get_comment_link($comment -> comment_ID)); ?>">
					       <time pubdate datetime="<?php comment_time('c'); ?>">
						      <?php printf(__('%1$s at %2$s', 'essential'), get_comment_date(), get_comment_time()); // translators: 1: date, 2: time ?>
						   </time>
						</a>
						<?php edit_comment_link(__('(Edit)', 'essential'), ' '); ?>
					</div>					
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<br/>
						<em><?php _e('Your comment is awaiting moderation.', 'essential'); ?></em>
						<br />
					<?php endif; ?>
				</footer>

				<div class="reply">
					<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
				</div>
				
			</article>
	
		<?php
		break;
		endswitch;
		}
}

/*-----------------------------------------------------------------------------------*/
/*  Template for product categories on home page */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'essential_product_categories' ) ) {
	function essential_product_categories($args = array()){
    	$defaults = array(
    	'menu_order'	=> 'ASC',
    	'hide_empty'	=> 0,
    	'hierarchical'	=> 1,
    	'taxonomy'		=> 'product_cat',
    	'pad_counts'	=> 1
    	);    	
    	$args                  = wp_parse_args( $args, $defaults );    	
    	$product_categories    = get_categories( $args );	
        if ( $product_categories ) {	
    		foreach ( $product_categories as $category ) {		
    			woocommerce_get_template( 'content-product_cat.php', array(
    		'category' => $category
    		) );			
    	   }		
        }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Check if WooCommerce is activated */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Nav Menu Dropdown */
/*-----------------------------------------------------------------------------------*/
class Walker_Nav_Menu_Dropdown extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth = 0, $args = array()){
	$indent = str_repeat("\t", $depth); // don't output children opening tag (`<ul>`)
	}

	function end_lvl(&$output, $depth = 0, $args = array()){
	$indent = str_repeat("\t", $depth); // don't output children closing tag
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	$url = '#' !== $item->url ? $item->url : '';
	$output .= '<option value="' . $url . '">' . $item->title.'</option>';
	}
	
	function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){
	$output .= "\n"; // replace closing </li> with the option tag
	}
}

/*-----------------------------------------------------------------------------------*/
/* Function that outputs the contents of the dashboard widget */
/*-----------------------------------------------------------------------------------*/
function essential_dashboard_widget_function() {
		echo '<a href="http://prospekt.hr"><img src="http://2.s3.envato.com/files/7210491/prospekt2.jpg" alt="ProspektDesign" style="width:100%; height:auto;"></a> <br />';
		echo '<p>Thank you for purchasing our responsive WooCommerce ecommerce theme. If you have any questions, please feel free to email us via contact form <a href="http://prospekt.hr/contact/">here</a> or directly at
		<a href="mailto:prospekt@prospekt.hr">prospekt@prospekt.hr</a> - Happy selling!</p>';
		echo '<ul>';
		echo '<li>&rarr; <a href="http://essential.prospekt-solutions.com/documentation/">Theme Documentation</a></li>';
		echo '<li>&rarr; <a href="http://themeforest.net/user/ProspektDesign">Theme Support</a></li>';
		echo '<li>&rarr; <a href="http://essential.prospekt-solutions.com/documentation/#install1">How to remove this dashboard widget?</a></li>';
		echo '<li>&rarr; <a href="http://prospekt.hr/">Need payment integration or WooCommerce plugin?</a></li>';
		echo '<li>&rarr; <a href="http://prospekt.hr/">Need WordPress securing and performance optimization?</a></li>';
		echo '<li>&rarr; <a href="http://themeforest.net/user/ProspektDesign">Visit us on Theme Forest</a></li>';
		echo '</ul>';

}

/*-----------------------------------------------------------------------------------*/
/* Function used in the action hook wp_dashboard_setup */
/*-----------------------------------------------------------------------------------*/
function add_dashboard_widgets() {
	global $theme_version; 
	wp_add_dashboard_widget( 'essential_dashboard_widget', 'Essential eCommerce Theme v'.$theme_version, 'essential_dashboard_widget_function' );
	global $wp_meta_boxes;
	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	$essential_widget_backup = array( 'essential_dashboard_widget' => $normal_dashboard['essential_dashboard_widget'] );
	unset( $normal_dashboard['essential_dashboard_widget'] );
	$sorted_dashboard = array_merge( $essential_widget_backup, $normal_dashboard );
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

/*-----------------------------------------------------------------------------------*/
/* Register the new dashboard widget with the 'wp_dashboard_setup' action */
/*-----------------------------------------------------------------------------------*/
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );


/*-----------------------------------------------------------------------------------*/
/* get favicon url */
/*-----------------------------------------------------------------------------------*/

function essential_favicon_url(){
		$url =  thm_get_option('thm_favicon');
        if( !$url or empty( $url ) )
            { $url = get_template_directory_uri() . '/favicon.ico'; }
		else {
			$url = wp_get_attachment_image_src($url,'full');
			$url = $url[0]; 
		}	
        
        if( is_ssl() )
            { $url = str_replace( 'http://', 'https://', $url ); }
        
        return $url;
}
