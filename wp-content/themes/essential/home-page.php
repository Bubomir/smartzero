<?php
/**
 * The template used for displaying home page without products in tabs
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */

  if ( is_woocommerce_activated() ): 
      
?>
<div id="tabs_container" class="no-tab">
 	<?php	
	$sort_home = explode(',',thm_get_option('thm_sort-home'));
			if (!$sort_home or !is_array($sort_home) or count($sort_home)<4){
			 $sort_home = array(  "thm_sort-home_featured" ,"thm_sort-home_new" , "thm_sort-home_best", "thm_sort-home_sale"  ); 
	}	
	foreach ($sort_home as  $value) {
		
		switch ($value) {
			case 'thm_sort-home_featured':?>
 	
		   <?php // Listing featured products ?>
		   
		   <?php if ( thm_get_option('thm_home-featured-products') > 0): ?>
		       
				<h2 class="title"><span><?php _e('Featured items' , 'essential')?></span></h2>
				
		        <div id="tab1" class="carusel">
		        	
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
			
			<?php break;
			
			case 'thm_sort-home_new':?>
	
				<?php // Listing new products ?>
				
				<?php if ( thm_get_option('thm_home-new-products') > 0): ?>
				    
					<h2 class="title"><span><?php _e('New items' , 'essential')?></span></h2>
					
			        <div id="tab2" class="carusel">
			        	
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
				<?php
				break;
				case 'thm_sort-home_best':?>
	
					<?php // Listing best seller products ?>
					
					<?php if (thm_get_option('thm_home-best-products') > 0): ?>
					    
						<h2 class="title"><span><?php _e('Best Sellers' , 'essential')?></span></h2>
						
				        <div id="tab3" class="carusel">
				        	
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
				        
					<?php endif; ?>
	
				<?php
				break;
				case 'thm_sort-home_auctions':?>
	
					<?php // Listing best seller products ?>
					
					<?php if (thm_get_option('thm_home-auctions-products') > 0): ?>
					    
						<h2 class="title"><span><?php _e('Auctions' , 'essential')?></span></h2>
						
				        <div id="tab3" class="carusel">
				        	
				            <div class="buttons-container">
				            	<a class="buttons prev" href="#" data-icon="&#xe0d5;"></a>
				            	<a class="buttons next" href="#" data-icon="&#xe0d8;"></a>
				            </div><!-- .buttons-container -->	
				            
				            <div class="viewport">
					    	<?php 
					    	    
					    	    woocommerce_product_loop_start();
					    	
						    	$args = array( 'post_type' => 'product', 'posts_per_page' => thm_get_option('thm_home-best-products') , 'post_type' => 'product',  
											'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'auction')), 'auction_arhive' => TRUE  );
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
				        
					<?php endif; ?>
	
				<?php
				break;
				case 'thm_sort-home_sale':?>
	
					<?php // Listing products on sale ?>
					
					<?php if ( thm_get_option('thm_home-sale-products') > 0): ?>
					    
						<h2 class="title"><span><?php _e('On Sale' , 'essential')?></span></h2>
				        
				        <div id="tab4" class="carusel">
				        	
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
				<?php
				break;
													
				}// end switch
				
			} //end foreach?>		
	
</div><!-- #tabs_container .no-tabs -->		

<?php endif; ?>