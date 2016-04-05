<?php
/**
 * The template used for displaying header
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
    
    <meta name="viewport" content="width=device-width" />
    
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    
    <title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?> <?php if ( !wp_title('', true, 'left') ); { ?> | <?php bloginfo('description'); ?> <?php } ?></title>
    
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
    
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
    <?php custom_head(); // output custom header ?>	
    
    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); // enqueue comment reply JS ?>
    
    <?php wp_head(); // output WP header ?> 
    
</head>

<body <?php body_class(); ?>>
    
<div id="wrapper">
    
 <header id="header" class="main-color">
     
 	<div class="inner fix">
 	    
 		<div id="topmenu">
 		    
 			<?php 
 			    /* Output top menu */
 			    wp_nav_menu( array('theme_location' => 'top-menu', 'container' => 'ul', 'container_id' => 'top-menu', 'walker' => new essential_walker_nav_menu(), 'fallback_cb' => false ));  			 
 			 ?>
 		
 		    <?php if (is_woocommerce_activated()): // Check if Woocommerce is activated ?>	
 		        
	 		<ul class="essential-top-menu">
                
                <?php if ( is_user_logged_in() ): 
						
                	?>
                    
                    <li class="my-account  has-submenu" ><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','woothemes'); ?>" data-icon="&#xe00d;"><?php _e('My Account','woothemes'); ?></a>
                    	
                    		<ul><?php wp_list_pages("title_li=&child_of=".get_option('woocommerce_myaccount_page_id')); ?>
                    			<? if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {?>
                    			<li class="logout-link"><a href="<?php echo wc_get_endpoint_url( 'customer-logout') ?>"><?php _e('Logout') ?></a></li>
                    			<? } ?>	
                    		</ul>
                    	
                    </li><!-- .my-account -->
                    
                <?php else: ?>
                    
                    <li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Login / Register','woothemes'); ?>" data-icon="&#xe076;"><?php _e('Login / Register','woothemes'); ?></a></li>
                
                <?php endif; ?>
				 
	 			<?php global $woocommerce; ?>  
	 			
	 			<li class="essential-cart has-submenu"><a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" data-icon="&#xe067;"><?php _e('Cart','essential'); ?> (<?php echo $woocommerce->cart->cart_contents_count ?>)</a>
	 				
	 				<?php /* Woocommerce cart dropdown */ ?>
	 				
	 				<ul id="quickcart">
	 				    
						<?php	
											
						  global $product_in_cart;
						  $product_in_cart = array();		
						  
						  if (sizeof($woocommerce->cart->get_cart())>0) :     // check if we have any items in cart			
						  			
    						foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) :
                                
    							$_product = $cart_item['data'];								
    							
    							if ( ! apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) || ! $_product->exists() || $cart_item['quantity'] == 0 ) continue; // Only display product if visible
    							
    							$product_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();              // Get price					
    							
    							$product_price = apply_filters( 'woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $cart_item, $cart_item_key );
    							
    							if ($_product->exists() && $cart_item['quantity']>0) :
                                    
    							     $product_in_cart[] = $cart_item['product_id'];
    					?>
    								<li>
    									<a href="<?php echo esc_url( get_permalink(apply_filters('woocommerce_in_cart_product_id', $cart_item['product_id'])) ); ?>" class='image-link'><?php echo $_product->get_image(); ?></a>
    									<h3><?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); ?></h3>
    									<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
    									<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key ); ?>
    								</li>
    						<?php
								endif;
							endforeach;
						else: 
						?>
						  <li class="empty"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></li>
						  					
						<?php endif; ?>						
						
						<li class="qc-buttons">
							<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>"><?php _e( 'View Cart &rarr;', 'woocommerce' ); ?></a>
							<a href="<?php echo $woocommerce->cart->get_checkout_url(); ?>"><?php _e( 'Checkout &rarr;', 'woocommerce' ); ?></a>
							<span class="total"><?php echo $woocommerce->cart->get_cart_subtotal(); ?></span>
						</li>
													       
				     </ul><!-- #quickcart -->
				     
	 			</li><!-- .essential-cart -->	 
	 						    
	 			<li class="checkout <?php if (get_pages('child_of='.get_option('woocommerce_checkout_page_id'))) {echo 'has-submenu';} ?>">
	 				<a href="<?php echo esc_url( $woocommerce->cart->get_checkout_url( ))?>" data-icon="&#xe050;"><?php _e('Checkout','essential'); ?></a>
 					<ul><?php wp_list_pages("title_li=&child_of=".get_option('woocommerce_checkout_page_id')); ?></ul>
					
	 			</li><!-- .checkout -->
	 			
	 			<li class="search">
	 			    
	 				<span data-icon="&#xe024;"> &nbsp;</span>
	 				
	 				<form method="get" id="searchform" action="<?php echo home_url();  ?>/">
						<input type="text" value="<?php the_search_query(); ?>" name="s"  />
						<input type="submit" value="<?php _e('Search','essential'); ?>"  />
						<input type="hidden" name="post_type" value="product" />
					</form> 		
							
	 			</li><!-- .search -->	
	 			 			
	 		</ul><!-- .essential-top-menu -->
	 		
	 	   <?php endif; ?>
	 	   
	 	</div><!-- #topmenu -->
	 	
	 	<div class="clear"></div>
	 	
 	</div><!-- .inner -->
 	 	
 	<div class="clear"></div>
 	
 	
 	
 	<div id="menubar" class="clearfix">
 		
 		<div class="menu-bg"></div>
 		
 		<div class="inner clearfix">
 		    
	 		
	 			<?php if (thm_get_option('thm_logo')) { // check if logo file is uploaded an use it
	 				$logoimage = wp_get_attachment_image_src(thm_get_option('thm_logo'),'full'); // get logo image from theme options
	 				?>
	 				<div class="sitelogo clearfix ">	
	 				<?php if (is_home()) { echo "<h1>"; } else {echo "<h2>";} ?>
	 					<a href="<?php echo home_url(); ?>/" title="<?php bloginfo('name'); ?>">
	 						<img src="<?php echo $logoimage[0]; ?>" alt="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>" />
	 					</a>
	 				<?php if (is_home()) { echo "</h1>"; } else {echo "</h2>";} ?>	
	 				</div><!-- .sitelogo -->
	 				
	 			<?php } else { ?>
	 				<div class="sitename clearfix">	
	 				<?php if (is_home()) { echo "<h1>"; } else {echo "<h2>";} ?>
	 					<a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a>
	 				<?php if (is_home()) { echo "</h1>"; } else {echo "</h2>";} ?>
	 				<p><?php bloginfo('description'); ?></p>
	 				</div><!-- .sitename -->
	 			<?php } ?>
   			
   			   			
   			<?php wp_nav_menu( array('theme_location' => 'primary-menu', 'container_id' => 'primary-menu', 'walker' => new essential_walker_nav_menu() , 'fallback_cb' => false )); ?>
   			
   			<?php wp_nav_menu( array('theme_location' => 'mobile-menu',	'walker' => new Walker_Nav_Menu_Dropdown(),	'container_id' => 'mobile-menu', 'items_wrap' => '<div class="mobile-menu clearfix"><form><select onchange="if (this.value) window.location.href=this.value" class="main-color"><option value="#">Menu</option>%3$s</select></form></div>', 'fallback_cb' => false ) ); ?>
   			
   		</div><!-- .inner -->
   				
	</div><!-- #menubar -->
	
	
	
 <?php if ( function_exists( 'get_essenital_content_slider' ) ) get_essenital_content_slider(); // Get content slider ?> 
 
 
 </header><!-- #header -->