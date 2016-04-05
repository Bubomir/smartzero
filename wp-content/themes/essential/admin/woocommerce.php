<?php
/**
 * All functions and hooks for woocommerce plugin  
 *
 * @package Essential
 * @since Essential 1.0
 */
global $woocommerce_auctions;	

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
remove_action('woocommerce_before_shop_loop',  'woocommerce_result_count', 20, 0 );
remove_action('woocommerce_before_shop_loop',  'woocommerce_catalog_ordering', 30,0 );
remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating',5);
remove_action('woocommerce_after_shop_loop_item', array( $woocommerce_auctions, 'add_pay_button'), 80);


add_filter( 'loop_shop_post_in',  'prospekt_price_filter'  );
if (!function_exists( 'prospekt_price_filter')) {
	function prospekt_price_filter( $filtered_posts ) {
	    global $wpdb;

	    if ( isset( $_GET['max_price'] ) && isset( $_GET['min_price'] ) ) {

	        $matched_products = array();
	        $min 	= floatval( $_GET['min_price'] );
	        $max 	= floatval( $_GET['max_price'] );

	        $matched_products_query = apply_filters( 'woocommerce_price_filter_results', $wpdb->get_results( $wpdb->prepare("
	        	SELECT DISTINCT ID, post_parent, post_type FROM $wpdb->posts
				INNER JOIN $wpdb->postmeta ON ID = post_id
				WHERE post_type IN ( 'product', 'product_variation' ) AND post_status = 'publish' AND meta_key = %s AND meta_value BETWEEN %d AND %d
			", '_price', $min, $max ), OBJECT_K ), $min, $max );

	        if ( $matched_products_query ) {
	            foreach ( $matched_products_query as $product ) {
	                if ( $product->post_type == 'product' )
	                    $matched_products[] = $product->ID;
	                if ( $product->post_parent > 0 && ! in_array( $product->post_parent, $matched_products ) )
	                    $matched_products[] = $product->post_parent;
	            }
	        }

	        // Filter the id's
	        if ( sizeof( $filtered_posts ) == 0) {
	            $filtered_posts = $matched_products;
	            $filtered_posts[] = 0;
	        } else {
	            $filtered_posts = array_intersect( $filtered_posts, $matched_products );
	            $filtered_posts[] = 0;
	        }

	    }

	    return (array) $filtered_posts;
	}
}	
/* ----------------------------------------------------------------- */
/* Display price filter slider on right side of title position
/* ----------------------------------------------------------------- */ 
add_action('right_side_title',  'essential_widget_price_filter', 1); 
if (!function_exists( 'essential_widget_price_filter')) {
	function essential_widget_price_filter(){
		
		global $_chosen_attributes, $wpdb, $woocommerce, $wp_query, $wp;
		
		if ( 1 == $wp_query->found_posts || ! woocommerce_products_will_display() )
		return;
		$min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
		$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';

		wp_enqueue_script( 'wc-price-slider' );

		wp_localize_script( 'wc-price-slider', 'woocommerce_price_slider_params', array(
			'currency_symbol' 	=> get_woocommerce_currency_symbol(),
			'currency_pos'      => get_option( 'woocommerce_currency_pos' ),
			'min_price'			=> $min_price,
			'max_price'			=> $max_price
		) );
		the_widget('WC_Widget_Price_Filter','title='.__('Filter by price', 'essential'));
		
	}
}

/* ----------------------------------------------------------------- */
/* Display catalog ordering dropdown on right side of title position
/* ----------------------------------------------------------------- */ 
add_action('right_side_title',  'essential_catalog_ordering', 20);	
if (!function_exists( 'essential_catalog_ordering')) {
	function essential_catalog_ordering(){
		global $wp_query;
		if ( 1 == $wp_query->found_posts || ! woocommerce_products_will_display() )
		return;
		echo "<div class='sorting'><h2>".__('Sort product', 'essential')."</h2>";
		woocommerce_catalog_ordering();
		echo "</div>";
	}
}

/* ----------------------------------------------------------------- */
/* Add excerpt/short description after shop loop title
/* ----------------------------------------------------------------- */  
add_action('woocommerce_after_shop_loop_item_title','shop_loop_description',5);
if (!function_exists( 'shop_loop_description')) {
	function shop_loop_description(){
		global $post;
		echo "<span class='short-description'>";
		echo apply_filters( 'woocommerce_loop_short_description', $post->post_excerpt );
		echo "</span>";
	}
}

/* ----------------------------------------------------------------- */
/* Limit description lenght in loop
/* ----------------------------------------------------------------- */   
add_filter( 'woocommerce_loop_short_description', 'essential_loop_desciption_length' );
function essential_loop_desciption_length( $text ) {
	$text = strip_tags($text);
	$getlength = strlen($text);
	$thelength = 48;
	$data = substr($text, 0, $thelength);
	if ($getlength > $thelength) $data .= "...";
	return $data;	
}

/* ----------------------------------------------------------------- */
/* Change number of columns in row
/* ----------------------------------------------------------------- */  

add_action( 'wp', 'essential_change_colums',99);
function essential_change_colums(){
	global $theme_options;
	if( is_home() OR !is_active_sidebar('primary') OR (isset($theme_options['layout']) AND  $theme_options['layout'] == 'layout-full')){
	add_filter('loop_shop_columns', 'loop_columns4');
	} else {
		add_filter('loop_shop_columns', 'loop_columns3');
	}
	if (!function_exists('loop_columns3')) {
		function loop_columns3() {
			return 3; // 3 products per row
		}
	}
	if (!function_exists('loop_columns4')) {
		function loop_columns4() {
			return 4; // 4 products per row
		}
	}
}


/* ----------------------------------------------------------------- */
/* Change template for product thumbnails
/* ----------------------------------------------------------------- */  
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
add_action( 'woocommerce_before_single_product_summary', 'essential_show_product_images', 20); 
if (!function_exists('essential_show_product_images')) {
	function essential_show_product_images() {
	    woocommerce_get_template_part('single-product/product-thumbnails');
	}
}

/* ----------------------------------------------------------------- */
/* Change template for related products
/* ----------------------------------------------------------------- */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);  
add_action( 'woocommerce_after_single_product_summary', 'essential_woocommerce_output_related_products', 20);
if (!function_exists('essential_woocommerce_output_related_products')) {
	function essential_woocommerce_output_related_products(){	
		woocommerce_get_template_part('single-product/related');
	}
}

/* ----------------------------------------------------------------- */
/* Remove add to cart button
/* ----------------------------------------------------------------- */ 
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
 	

/* ----------------------------------------------------------------- */
/* Change template for product thumbnail in loop
/* ----------------------------------------------------------------- */ 
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'essential_woocommerce_thumbnails', 10);
function essential_woocommerce_thumbnails(){
	echo '<div class="thumb-wrapper">'.woocommerce_get_product_thumbnail().'</div>';
}

/* ----------------------------------------------------------------- */
/* Include script for price filter 
/* ----------------------------------------------------------------- */  
add_action( 'init', 'woocommerce_price_filter_init_essential' ); 
function woocommerce_price_filter_init_essential() {
	global $woocommerce;
	if ( ! is_admin() ) {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'wc-price-slider', $woocommerce->plugin_url() . '/assets/js/frontend/price-slider' . $suffix . '.js', array( 'jquery-ui-slider' ), '1.6', true );
		if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) < 0 ) {
			add_filter( 'loop_shop_post_in', 'woocommerce_price_filter' );
		}	
	}
}

/* -------------------------------------------------------------------------- */
/* Ensure cart contents update when products are added to the cart via AJAX 
/* -------------------------------------------------------------------------- */  
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments ) {
    
	global $woocommerce;
	
	ob_start();
	
	?>
	<li class="essential-cart updated has-submenu" ><a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" data-icon="&#xe067;"><?php _e('Cart','essential'); ?> (<?php echo $woocommerce->cart->cart_contents_count ?>)  </a>
		<ul id="quickcart">
			<?php
				global  $product_in_cart;
				$product_in_cart = array();		
				if (sizeof($woocommerce->cart->get_cart())>0) :
					
					foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) :
						$_product = $cart_item['data'];						
						if ( ! apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) || ! $_product->exists() || $cart_item['quantity'] == 0 ) continue; // product visible?			
						$product_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();
						$product_price = apply_filters( 'woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $cart_item, $cart_item_key );
						if ($_product->exists() && $cart_item['quantity']>0) :
						$product_in_cart[] = $cart_item['product_id'];
			?>
							<li>
								<a href="<?php echo esc_url( get_permalink(apply_filters('woocommerce_in_cart_product_id', $cart_item['product_id'])) ); ?>" class='image-link'>
								<?php 							
									echo $_product->get_image();
								?>
								</a>
								<h3><?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); ?></h3>
								<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
								<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
								?>
							</li>
			    <?php
						endif;
					endforeach;
				else : ?>

					<li class="empty"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></li>
			
				<?php endif; ?>
					<li class="qc-buttons">
						<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" ><?php _e( 'View Cart &rarr;', 'woocommerce' ); ?></a>
						<a href="<?php echo $woocommerce->cart->get_checkout_url(); ?>" ><?php _e( 'Checkout &rarr;', 'woocommerce' ); ?></a>
						<span class="total"><?php echo $woocommerce->cart->get_cart_subtotal(); ?></span>
					</li>
								       
	        </ul>
       </li>
       <script>
       	jQuery("#topmenu ul li.has-submenu").hoverIntent(
		  		function(){
			  		jQuery(this).children('ul').slideDown();
			  		jQuery(this).addClass('hover');
			  		},
			  	function(){
			  		jQuery(this).children('ul').slideUp();
			  		jQuery(this).removeClass('hover');
			  	}
		  );
       </script>
	<?php	
	   $fragments['.essential-cart'] = ob_get_clean();
	   return $fragments;	
}
/* -------------------------------------------------------------------------- */
/* Return products on sale 
/* -------------------------------------------------------------------------- */  
function prospekt_return_products_onsale($count){
	$products_on_sale = woocommerce_get_product_ids_on_sale();
	$i=0;
	$the_ids = array();
	if(isset($products_on_sale) AND is_array($products_on_sale)){
		foreach ($products_on_sale as $product_on_sale => $value){
			$type = get_post_type($value);
				if($type == 'product'){
					$the_ids[] = (int)$value;
				}
			if ($i >= $count) {break;}	
			$i++;
		}
	}	
	return $the_ids;

}