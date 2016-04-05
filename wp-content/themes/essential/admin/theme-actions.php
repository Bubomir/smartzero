<?php
/**
 * All theme actions
 *
 * @package Essential
 * @since Essential 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------*/
/* Google Webfonts Stylesheet Generator */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_head', 'google_webfonts' );
if ( ! function_exists( 'google_webfonts' ) ) {
	function google_webfonts() {
		$output = '';
			if(thm_get_option('thm_title-font'))
				$titlefont = thm_get_option('thm_title-font') ;
			if(thm_get_option('thm_content-font'))
				$contentfont = thm_get_option('thm_content-font');
			if ( isset($titlefont) && strlen( $titlefont )  &&  isset( $contentfont ) && strlen( $contentfont ) && $titlefont == $contentfont){
				wp_enqueue_style( 'mainandcontentfontlink',  'http'. ( is_ssl() ? 's' : '' ) .'://fonts.googleapis.com/css?family=' . $titlefont);
			} else {
				if ( isset($titlefont) && strlen( $titlefont ) ){
					wp_enqueue_style( 'mainfontlink',  'http'. ( is_ssl() ? 's' : '' ) .'://fonts.googleapis.com/css?family=' . $titlefont );
					
				}
				if ( isset($contentfont) && strlen( $contentfont ) ){
					
					wp_enqueue_style( 'contentfontlink',  'http'. ( is_ssl() ? 's' : '' ) .'://fonts.googleapis.com/css?family=' . $contentfont );
					
				}
			}
			
	} 
}

/*-----------------------------------------------------------------------------------*/
/* Add custom typograhpy to custom_head */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_enqueue_scripts','custom_typography', 10 ); // Add custom typography to HEAD
if ( ! function_exists( 'custom_typography' ) ) {
	function custom_typography() {
		$thm_option   = get_option('thm_settings') ;
		if ( isset($thm_option['thm_include']) && $thm_option['thm_include'] == 'inline') {
			wp_enqueue_style( 'essential-color-style', get_template_directory_uri() .'/css/'.thm_get_option('thm_site-style').'.css',array('main-stylesheet')  );
			$output = '';
			if(thm_get_option('thm_title-font'))
				$titlefont = thm_get_option('thm_title-font') ;
			if(thm_get_option('thm_content-font'))
				$contentfont = thm_get_option('thm_content-font');
			$titlefont = str_replace("+", " ", $titlefont) ;
			$contentfont = str_replace("+", " ", $contentfont);	
			
			if ( isset($titlefont) && strlen( $titlefont ) ){
						$output .= "h1, h2, h3, h4, h5, h6, .widget .heading, #site-title a, #site-title, .entry-title {font-family: '$titlefont' }";	
						
			}
			if ( isset($contentfont) && strlen( $contentfont ) ){
						$output .= "body {font-family: '$contentfont'}";
			}
			if (isset($output) && $output != '') {
				$output = "\n" . "/* Custom Typography */\n" . $output . "\n/* End of Custom Typography */\n";
				wp_add_inline_style( 'essential-color-style', $output );		
			} 
		}
	} 
}
/*-----------------------------------------------------------------------------------*/
/* Add custom colors to custom_head if is set to inline */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_enqueue_scripts','essential_custom_css', 10 ); // Add custom css (colors) to HEAD
if ( ! function_exists( 'essential_custom_css' ) ) {
	function essential_custom_css() {
		
		$thm_option   = get_option('thm_settings') ;
		$output       = '';
        
		
		if ( isset($thm_option['thm_include']) && $thm_option['thm_include'] == 'inline') {
			wp_enqueue_style( 'essential-color-style', get_template_directory_uri() .'/css/'.thm_get_option('thm_site-style').'css',array('main-stylesheet')  );
			
			if ($thm_option['thm_site-style'] == 'custom'){
				$maincolor 				= (isset($thm_option['thm_main-color-bg']) 		? $thm_option['thm_main-color-bg'] :FALSE) ;
				$maincolor_fc 			= (isset($thm_option['thm_main-color-font']) 	? $thm_option['thm_main-color-font'] :FALSE) ;
				$color2 				= (isset($thm_option['thm_color2-bg'] ) 		? $thm_option['thm_color2-bg'] :FALSE);
				$color2_fc 				= (isset($thm_option['thm_color2-font'] ) 		? $thm_option['thm_color2-font']:FALSE);
				$color3 				= (isset($thm_option['thm_color3-bg'] ) 		? $thm_option['thm_color3-bg'] :FALSE);
				$color3_fc 				= (isset($thm_option['thm_color3-font'] ) 		? $thm_option['thm_color3-font'] :FALSE);
				$color4 				= (isset($thm_option['thm_color4-bg'] ) 		? $thm_option['thm_color4-bg']:FALSE);
				$color4_fc 				= (isset($thm_option['thm_color4-font']) 		? $thm_option['thm_color4-font']:FALSE) ;
				$bodycolor 				= (isset($thm_option['thm_body-color'] ) 		? $thm_option['thm_body-color']:FALSE);
				$linkcolor 				= (isset($thm_option['thm_link-color']) 		? $thm_option['thm_link-color']:FALSE) ;
				$textcolor 				= (isset($thm_option['thm_text-color'] ) 		? $thm_option['thm_text-color']  :FALSE);
				$headergradient			= (isset($thm_option['thm_header-gradient-gradient-css']) ? $thm_option['thm_header-gradient-gradient-css'] :FALSE) ;
				$header_fc				= (isset($thm_option['thm_header-gradient-font']) ? $thm_option['thm_header-gradient-font']:FALSE) ;
				}

				$mainbgimage			= (isset($thm_option['thm_main-bg-image']) ? $thm_option['thm_main-bg-image'] :FALSE);
				$mainbgimage_fc			= (isset($thm_option['thm_main-bg-image-font'] ) ? $thm_option['thm_main-bg-image-font']:FALSE);
				
				$custom_css				= (isset($thm_option['thm_custom-css'] ) ? $thm_option['thm_custom-css'] :FALSE);
				$background_aligment	= (isset($thm_option['thm_background-aligment'] ) ? $thm_option['thm_background-aligment'] :FALSE);
	
		
			if ( isset($maincolor) && strlen( $maincolor ) ){
						$output .= " 
						.woocommerce div.product .product_title,
						.woocommerce #content div.product .product_title,
						.woocommerce-page div.product .product_title,
						.woocommerce-page #content div.product .product_title,
						.woocommerce ul.products li.product h3, 
						.woocommerce-page ul.products li.product h3,
						#primary-menu ul > li li:hover > a,
						#tab-description h2,
						.upper-footer .widget-title,
						#primary-menu a{color:#$maincolor} 
						
						#primary-menu .megamenu > ul > li > a , 
						#topmenu ul > li li:hover, #primary-menu ul > li li:hover > a,
						.woocommerce div.product form.cart .button, 
						.woocommerce #content div.product form.cart .button, 
						.woocommerce-page div.product form.cart .button, 
						.woocommerce-page #content div.product form.cart .button,
						.woocommerce .widget_price_filter .price_slider_amount .button,
						.woocommerce-page .widget_price_filter 
						.price_slider_amount .button,
						.woocommerce a.button, 
						.woocommerce button.button, 
						.woocommerce input.button, 
						.woocommerce #respond input#submit, 
						.woocommerce #content input.button, 
						.woocommerce-page a.button, 
						.woocommerce-page button.button, 
						.woocommerce-page input.button, 
						.woocommerce-page #respond input#submit, 
						.woocommerce-page #content input.button,
						.woocommerce span.onsale,
						.woocommerce-page span.onsale,
						.main-color {background-color:#$maincolor; color:#$maincolor_fc}
						
						#topmenu ul > li li:hover a, #topmenu ul > li li:hover h3 {color:#$maincolor_fc}
						
						footer .upper-footer .widget > ul > li  {border-bottom-color: #$maincolor;}  
						";					
			}
			if ( isset($color2) && strlen( $color2 ) ){
						$output .= "
						.tab_navigation li a,
						.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
						.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,
						.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active,
						.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active {color:#$color2} 
						.related.products.carusel h2,
						.tab_navigation li.active a,
						.woocommerce .summary div[itemprop='offers'],
						#primary-menu .megamenu > ul > li > a:hover,
						.no-tab h2.title span,
						.title-area select,
						#sidebar .widget select, 
						#compare_table .image .price ,
						.da-slide .da-img span.price,
						#topmenu li.essential-cart .qc-buttons,
						.variations_form .single_variation,
						.sbHolder, .sbSelector:link, .sbSelector:visited, .sbSelector:hover, .sbOptions, #sidebar .widget a.sbSelector, 
						.bg-color2 {background-color:#$color2; color:#$color2_fc} 
						#primary-menu .sub-menu > li:hover > a, #primary-menu .megamenu > ul > li > a{color:#$color2_fc	}
						.tab_navigation,
						.no-tab h2.title,
						 #primary-menu > ul > li > ul, #primary-menu .megamenu > ul > li > a { border-bottom-color: #$color2;} ";						
			}
			if ( isset($color3) && strlen( $color3 ) ){
						$output .= "
						.action-buttons a > span a,
						.contact-box-wrapper h3, h3, .color3 {color:#$color3} 
						.woocommerce ul.products li.product .price,
						.widget.woocommerce .amount ,
						.woocommerce-page ul.products li.product .price,
						.action-buttons > span, .woocommerce ul.products .added_to_cart,
						.comments-title,
						#reply-title, 
						#compare_table .compared-product li.price,
						#topmenu .quantity,
						.bg-color3 {background-color:#$color3; color:#$color3_fc} 
						#primary-menu > ul > li > ul, #primary-menu ul > li > ul ul {border-bottom-color:#$color3} ";						
			}
			if ( isset($color4) && strlen( $color4 ) ){
						$output .= "
						.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
						.woocommerce-page ,
						.woocommerce div.product .product_title, 
						.woocommerce #content div.product .product_title, 
						.woocommerce-page div.product .product_title, 
						.woocommerce-page #content div.product .product_title, 
						.woocommerce ul.products li.product h3, 
						.woocommerce-page ul.products li.product h3, 
						#primary-menu ul > li li:hover > a, 
						#tab-description h2,
						.woocommerce ul.products li.product:hover h3,
						.woocommerce-page ul.products li.product:hover h3,
						.color4 {color:#$color4} 
						.circle-wrapper span,
						.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
						.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,						
						.bg-color4 {background-color:#$color4; color:#$color4_fc} ";						
			}
			if ( isset($mainbgimage_fc) && strlen( $mainbgimage_fc ) && isset($mainbgimage) && strlen( $mainbgimage ) && ($thm_option['thm_site-style'] == 'custom') ){
				$output .= ".upper-footer .widget-title ,.upper-footer  .widget-area-box a, main-image-font-color {color:#$mainbgimage_fc} ";
				
			}
			if ( isset($mainbgimage) && strlen( $mainbgimage ) ){
						$imagelink =  wp_get_attachment_image_src($mainbgimage,'full');
						$output .= ".main-color {background-image: url($imagelink[0]); color:#$mainbgimage_fc }";	
				if ( isset($background_aligment) && strlen( $background_aligment ) ){
					switch ($background_aligment) {
						case 'repeat':
								$output .= ".main-color {background-repeat: repeat;}";
							break;
						case 'repeatx':
								$output .= ".main-color {background-repeat: repeat-x;}";
							break;
						case 'repeaty':
								$output .= ".main-color {background-repeat: repea-y;}";
							break;
						case 'strech':
								$output .= ".main-color {background-repeat: no-repeat;
														background-position: center center;
														-webkit-background-size: cover;
														-moz-background-size: cover;
														-o-background-size: cover ;
														background-size: cover !important;
														filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.myBackground.jpg', sizingMethod='scale');
														-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='myBackground.jpg', sizingMethod='scale')\";}";
							break;				
						case 'fixed':
								$output .= ".main-color {background-repeat: no-repeat;
														background-position: center center;
														background-attachment: fixed;
														-webkit-background-size: cover;
														-moz-background-size: cover;
														-o-background-size: cover;
														background-size: cover !imortant;
														filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.myBackground.jpg', sizingMethod='scale');
														-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='myBackground.jpg', sizingMethod='scale')\";}";
							break;	
						default:
							
							break;
					}
				}			
			}
			if ( isset($bodycolor) && strlen( $bodycolor ) ){
						$output .= "body {background-color: #$bodycolor  }";
			}
			if ( isset($linkcolor) && strlen( $linkcolor ) ){
						$output .= "a ,.linkcolor, .bottom-footer .widget-title, .woocommerce-breadcrumb a, .sbOptions a:hover,.sbOptions a:focus,.sbOptions a.sbFocus, .sbOptions a:link, .sbOptions a:visited , #sidebar .widget a.sbToggle  {color: #$linkcolor } .linkcolor-as-bg{background:#$linkcolor}";
			}
			if ( isset($textcolor) && strlen( $textcolor ) ){
						$output .= "body,p,.summary  {color: #$textcolor  }";
			}
			if ( isset($headergradient) && strlen( $headergradient ) ){
						$output .= "#content article .entry-title, .stripe, .headergradient, #compare_table ul.compared-product .title{ $headergradient color:#$header_fc}
									#content article .entry-title a, .stripe p , #content article .comments-link a, #content article .comments-link .icon, .headergradient a, #compare_table ul.compared-product .title a { color:#$header_fc}";
			}
			

			if (isset($output) && $output != '') {
				$output = "\n" . "/* Custom Colors */\n" . $output . "\n/* End of Custom Colors */\n";	
				wp_add_inline_style( 'essential-color-style', $output );	
				
			} 
		
			if (isset($custom_css) && $custom_css != '') {
				$custom_css = 	"/* Custom CSS*/ \n" . $custom_css . "\n/* End off Custom Css */\n ";
				wp_add_inline_style( 'essential-color-style', $custom_css );	
				
			} 
		}	

	} 
}

/*-----------------------------------------------------------------------------------*/
/* Add menu-parent-item to menu */
/*-----------------------------------------------------------------------------------*/
add_filter( 'wp_nav_menu_objects', 'add_menu_parent_class' );
if ( ! function_exists( 'add_menu_parent_class' ) ) {
	function add_menu_parent_class( $items ) {
		
		$parents = array();
		foreach ( $items as $item ) {
			if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
				$parents[] = $item->menu_item_parent;
			}
		}
		
		foreach ( $items as $item ) {
			if ( in_array( $item->ID, $parents ) ) {
				$item->classes[] = 'menu-parent-item'; 
			}
		}
		
		return $items;    
	}
}

/*-----------------------------------------------------------------------------------*/
/* Add layout to body_class output */
/*-----------------------------------------------------------------------------------*/
add_filter( 'body_class','essential_ayout_body_class', 10 );		
if ( ! function_exists( 'essential_ayout_body_class' ) ) {
	function essential_ayout_body_class( $classes ) {
		global $theme_options;		
		
				
		// Add classes to body_class() output
		$classes[] = $theme_options['layout'];
		
		return $classes;

	} 
}
/*-----------------------------------------------------------------------------------*/
/* Check for sidebar position */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'essential_sidebar_position' ) ) {
	function essential_sidebar_position() {
		global $theme_options;		
		$layout = thm_get_option('thm_sidebar-position');
        
		
		if ( is_singular() ) {
			global $post;
			
			$single = get_post_meta($post->ID, '_layout', true);
			if ( $single != "" AND $single != "layout-default" ){
				$layout = $single;
			} elseif ($single == "layout-default" OR !$single ){
				$layout = thm_get_option('thm_sidebar-position');
			}				
		}
				
        if (is_woocommerce_activated()){
			if (is_shop() or is_product_category()){
				
				$single = get_post_meta(get_option('woocommerce_shop_page_id'),'_layout', true);
				if ( $single != "" AND $single != "layout-default" ){
					$layout = $single;
				} elseif ($single == "layout-default" OR !$single ){
					$layout = thm_get_option('thm_sidebar-position');
				}	
			}	
		}	
		
		if (!is_active_sidebar('primary'))
			 $layout = "layout-full";
		
		$theme_options['layout'] = $layout;
		
		return $layout;
	}
}
/*-----------------------------------------------------------------------------------*/
/* Add fav icon*/
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_head', 'essential_favicon' );
if ( ! function_exists( 'essential_favicon' ) ) {
	function essential_favicon() {?>
		<!-- [favicon] begin -->
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo essential_favicon_url() ?>" />
		<link rel="icon" type="image/x-icon" href="<?php echo essential_favicon_url() ?>" />
		<!-- [favicon] end -->	
		<?php 
	}
}	