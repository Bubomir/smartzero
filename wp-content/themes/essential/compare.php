<?php
/**
 * Template Name: Compare Template
 *
 * The template for displaying compare products page
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */

    if ( ! defined( 'ABSPATH' ) ) exit;  // exit if accessed directly
    add_filter( 'body_class','essential_compare_body_class', 10 );
    $compare_list = array();
    $have_compare = FALSE;
    $current_user = wp_get_current_user(); 
    
    /* Check if user is logged in or we need to use cookie for compare */
    if(is_user_logged_in()){
    	$compare_list = maybe_unserialize(get_user_meta( $current_user->ID, 'essential_compare' , TRUE ));
    } else {
    	$compare_list = essential_getcookie('essential_compare');
    }

    get_header(); ?>

    <div class="title-area clearfix main-color">
        
    	<div class="inner">
    		<div class="left-side-title">
    			<h1 class="page-title"><?php the_title(); ?></h1>				
    		    <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) woocommerce_breadcrumb(); ?>
    	  	</div>
    	   <div class="right-side-title"><?php do_action('right_side_title'); // right side title hook ?></div>
       </div><!-- .inner -->
    
    </div><!-- .title-area -->
    
    
    <div id="primary" class="content-area">
        
		   <div class="inner">
		     
			 <div id="content" class="site-content" role="main">	            
	            <?php do_action('aftet_title_area');  // after title area hook	?>
                <?php do_action('woocommerce_before_compare'); // before compare hook ?>	
		        <?php do_action('woocommerce_compare_description'); // compare description hook ?>		
        		<?php         	
        		    	// If there is something to compare, define query
                		if (!empty ($compare_list)):
                			$args = array( 'post_type' => 'product', 'post__in' => $compare_list);
                			query_posts( $args );
                			$have_compare = TRUE; 
        	    ?>
        	    
        		<div id="compare_table">
        		    
    				<ul class="compare_head">
    					<li class="title">&nbsp;</li>
    					<li class="image">&nbsp;</li>
    					<li class="rating">&nbsp;</li>
    					<li class="full details"><span class="bg-color4"><?php _e('Details','essential') ?></span></li>
    					<li class="full available"><span class="bg-color4"><?php _e('Available','essential') ?></span></li>
    					<li class="price">&nbsp;</li>
    				</ul>
    				
    				<div id="compared-products">
    					<div class="view" style="width:<?php echo count($compare_list)*220;?>px">
    						<?php while ( have_posts() ) : the_post(); ?>    		
    							<?php woocommerce_get_template_part( 'compare', 'product' ); ?>    		
    						<?php endwhile; ?>
    					</div>	
    				</div><!-- #compared-products -->
    				
        		</div><!-- #compare_table -->
        		
        		<div class="clear"></div>	
        				
			    <?php do_action( 'woocommerce_after_compare_loop' ); // after compare loop hook ?>
		  
		    </div><!-- #content .site-content -->	

    		<?php else : ?>
    
    			<?php woocommerce_get_template( 'loop/no-products-found-compare.php' ); // no products to compare message ?>
    
    		<?php endif; ?>

        <?php do_action('woocommerce_after_compare'); // after compare hook ?>	
	
		</div><!-- .inner -->
		
	</div><!-- #primary .content-area -->

<?php get_footer(); ?>