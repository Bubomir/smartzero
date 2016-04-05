<?php
/**
 * The template used for displaying home page with products in tabs
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
 
 if ( is_woocommerce_activated() ): 
 
?>
<div id="tabs_container">
    
	 
	 <ul class="tab_navigation">
	 	
	 	<?php 
	 		$active_tab = thm_get_option('thm_active-tab');
			if(!isset($active_tab) or !$active_tab){
				$active_tab = 'featured';
			}	
			$sort_home = explode(',',thm_get_option('thm_sort-home'));
			if (!$sort_home or !is_array($sort_home) or count($sort_home)<4){
			 $sort_home = array(  "thm_sort-home_featured" ,"thm_sort-home_new" , "thm_sort-home_best", "thm_sort-home_sale"  ); 
			}	
				foreach ($sort_home as  $value) {
					switch ($value) {
						case 'thm_sort-home_featured':
							if ( thm_get_option('thm_home-featured-products') > 0) {?>
			 					<li <?php if ($active_tab == 'featured') {echo  'class="active"';} ?>><a href="#" data-rel="#tab1" class="tab"><?php _e('Featured items' , 'essential')?></a></li>
			 				<?php } 
							
						break;
						case 'thm_sort-home_new':
							 if ( thm_get_option('thm_home-new-products') > 0) { ?>	
			 					<li <?php if ($active_tab == 'new') {echo  'class="active"';} ?>><a href="#" data-rel="#tab2" class="tab"><?php _e('New items' , 'essential')?></a></li>
							<?php } 
						break;
						case 'thm_sort-home_best':
							 if ( thm_get_option('thm_home-best-products') > 0) { ?>
							 	<li <?php if ($active_tab == 'best') {echo  'class="active"';} ?>><a href="#" data-rel="#tab3" class="tab" ><?php _e('Best Sellers' , 'essential')?></a></li>
							<?php } 
						break;
						case 'thm_sort-home_sale':
							if ( thm_get_option('thm_home-sale-products') > 0) { ?>
						 		<li <?php if ($active_tab == 'sale') {echo  'class="active"';} ?>><a href="#" data-rel="#tab4" class="tab"><?php _e('On Sale' , 'essential')?></a></li>
							<?php }
						break;
						case 'thm_sort-home_auctions':
							if ( thm_get_option('thm_home-auctions-products') > 0) { ?>
						 		<li <?php if ($active_tab == 'auctions') {echo  'class="active"';} ?>><a href="#" data-rel="#tab5" class="tab"><?php _e('Auctions' , 'essential')?></a></li>
							<?php }
						break;
													
					}
				}	
			
			
			?>
			
	 	
		
	 </ul><!-- .tab_navigation -->
              
              
               
       <?php // Listing featured products ?> 
       
       <?php if ( thm_get_option('thm_home-featured-products') > 0): ?>
	        <div id="tab1" class="carusel tab-content <?php if ($active_tab == 'featured') {echo  'active';} ?>">
	            
	            <div class="buttons-container">
	            	<a class="buttons prev" href="#" data-icon="&#xe0d5;"></a>
	            	<a class="buttons next" href="#" data-icon="&#xe0d8;"></a>
	            </div><!-- .buttons-container -->	
	            
	            <div class="viewport">
                <?php                       
                    woocommerce_product_loop_start();    
                    
                    $args = array( 'post_type' => 'product', 'posts_per_page' => thm_get_option('thm_home-featured-products') , 'meta_query' => array( array('key' => '_visibility','value' => array('catalog', 'visible'),'compare' => 'IN'),array('key' => '_featured','value' => 'yes')) );
                    $loop = new WP_Query( $args );                          
            
                    while ( $loop->have_posts() ) :                                   
                          $loop->the_post(); 
                          $_product;                    
                          if ( function_exists( 'get_product' ) ) {
                               $_product = get_product( $loop->post->ID );
                          } else {
                                $_product = new WC_Product( $loop->post->ID );
                          }                             
                          woocommerce_get_template_part( 'content', 'product' );                            
                    endwhile;
                    
                    woocommerce_product_loop_end();
                ?>
				</div><!-- .viewport -->
				
	        </div><!-- #tab1 .carusel -->
	        
		<?php endif; ?>
		
		
		
		<?php // Listing new products ?>
		
		<?php if ( thm_get_option('thm_home-new-products') > 0): ?>
		    
	        <div id="tab2" class="carusel tab-content <?php if ($active_tab == 'new') {echo  'active';} ?>">
	            
	            <div class="buttons-container">
	            	<a class="buttons prev" href="#" data-icon="&#xe0d5;"></a>
	            	<a class="buttons next" href="#" data-icon="&#xe0d8;"></a>
	            </div><!-- .buttons-container -->	
	            
	            <div class="viewport">
                <?php 
                    
                    woocommerce_product_loop_start();                           
                    
                    $args = array( 'post_type' => 'product', 'posts_per_page' => thm_get_option('thm_home-new-products'), 'meta_query' => array( array('key' => '_visibility','value' => array('catalog', 'visible'),'compare' => 'IN'),array('key' => '_featured','value' => 'no')) );
                    $loop = new WP_Query( $args );                          
            
                    while ( $loop->have_posts() ) : 
                    
                        $loop->the_post();                          
                        $_product;                  
                        if ( function_exists( 'get_product' ) ) {
                            $_product = get_product( $loop->post->ID );
                        } else {
                            $_product = new WC_Product( $loop->post->ID );
                        }                               
                        woocommerce_get_template_part( 'content', 'product' );                              
                    endwhile;
                    
                    woocommerce_product_loop_end(); 
                ?>
				</div><!-- .viewport -->
				
	        </div><!-- #tab2 .carusel -->
	        
		<?php endif; ?>
		
		
		
		<?php // Listing best seller products ?>
		
		<?php if (thm_get_option('thm_home-best-products') > 0) { ?>
		    
	        <div id="tab3" class="carusel tab-content <?php if ($active_tab == 'best') {echo  'active';} ?>" >
	            
	            <div class="buttons-container">
	            	<a class="buttons prev" href="#" data-icon="&#xe0d5;"></a>
	            	<a class="buttons next" href="#" data-icon="&#xe0d8;"></a>
	            </div><!-- .buttons-container -->	
	            
	            <div class="viewport">
                <?php 
                    
                    woocommerce_product_loop_start();
                
                    $args = array( 'post_type' => 'product', 'posts_per_page' => thm_get_option('thm_home-best-products') , 'meta_key' => 'total_sales', 'orderby' => 'meta_value' );
                    $loop = new WP_Query( $args );                          
            
                    while ( $loop->have_posts() ) : 
                        $loop->the_post(); 
                        $_product;                  
                        if ( function_exists( 'get_product' ) ) {
                            $_product = get_product( $loop->post->ID );
                        } else {
                            $_product = new WC_Product( $loop->post->ID );
                        }                               
                        woocommerce_get_template_part( 'content', 'product' );                          
                    endwhile;
                    
                    woocommerce_product_loop_end();
                ?>  							
				</div><!-- .viewport -->	
					
	        </div><!-- #tab3 .carusel -->
	        
		<?php } ?>
		
		
		
		<?php // Listing products on sale ?>
		
		<?php if ( thm_get_option('thm_home-sale-products') > 0): ?>
		    
	        <div id="tab4" class="carusel tab-content <?php if ($active_tab == 'sale') {echo  'active';} ?>">
	            
	            <div class="buttons-container">
	            	<a class="buttons prev" href="#" data-icon="&#xe0d5;"></a>
	            	<a class="buttons next" href="#" data-icon="&#xe0d8;"></a>
	            </div><!-- .buttons-container -->
	            
	            <div class="viewport">
                <?php 
                    
                    woocommerce_product_loop_start(); 
					
                    $args = array( 'post__in' => prospekt_return_products_onsale(thm_get_option('thm_home-new-products')), 'post_type' => 'product');
                    $loop = new WP_Query( $args );                          
            
                    while ( $loop->have_posts() ) :                         
                        $loop->the_post(); 
                        $_product;                  
                        if ( function_exists( 'get_product' ) ) {
                            $_product = get_product( $loop->post->ID );
                        } else {
                            $_product = new WC_Product( $loop->post->ID );
                        }                               
                        woocommerce_get_template_part( 'content', 'product' );                          
                    endwhile;
                    
                    woocommerce_product_loop_end();                         
                ?>
				</div><!-- .viewport -->
				
	        </div><!-- #tab4 .carusel -->
	        
		<?php endif; ?>
		
		<?php // Listing products on sale ?>
		
		<?php if ( thm_get_option('thm_home-auctions-products') > 0): ?>
		    
	        <div id="tab5" class="carusel tab-content <?php if ($active_tab == 'auctions') {echo  'active';} ?>">
	            
	            <div class="buttons-container">
	            	<a class="buttons prev" href="#" data-icon="&#xe0d5;"></a>
	            	<a class="buttons next" href="#" data-icon="&#xe0d8;"></a>
	            </div><!-- .buttons-container -->
	            
	            <div class="viewport">
                <?php 
                    
                    woocommerce_product_loop_start(); 
					
                    $args = array('post_type' => 'product', 'posts_per_page' => thm_get_option('thm_home-best-products') , 'post_type' => 'product',  
											'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'auction')), 'auction_arhive' => TRUE );
                    $loop = new WP_Query( $args );                          
            
                    while ( $loop->have_posts() ) :                         
                        $loop->the_post(); 
                        $_product;                  
                        if ( function_exists( 'get_product' ) ) {
                            $_product = get_product( $loop->post->ID );
                        } else {
                            $_product = new WC_Product( $loop->post->ID );
                        }                               
                        woocommerce_get_template_part( 'content', 'product' );                          
                    endwhile;
                    
                    woocommerce_product_loop_end();                         
                ?>
				</div><!-- .viewport -->
				
	        </div><!-- #tab5 .carusel -->
	        
		<?php endif; ?>

</div><!-- #tabs_container -->
				
<?php endif; ?>