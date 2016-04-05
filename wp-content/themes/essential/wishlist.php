<?php
/**
 * Template Name: Wishlist Template
 *
 * The template for displaying wishlist page
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
 
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    add_filter( 'body_class','essential_wishlist_body_class', 10 );
    $wishlist = array();
    $have_wishlist = FALSE;
    $current_user = wp_get_current_user(); 
    
    /* Check if user is logged in or we need to use cookie for wishlist */
    if(is_user_logged_in()){
    	$wishlist = maybe_unserialize(get_user_meta( $current_user->ID, 'essential_wishlist' , TRUE ));
    } else {
    	$wishlist = essential_getcookie('essential_wishlist');
    }
    
    get_header(); ?>


    <div class="title-area clearfix main-color">
        
    	<div class="inner">
    	    
    		<div class="left-side-title">		    	
    			<h1 class="page-title"><?php the_title(); ?></h1>			
    		    <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) woocommerce_breadcrumb(); ?>
    	  	</div><!-- .left-side-title -->
    	  	
    	   <div class="right-side-title">
    	       <?php do_action('right_side_title'); ?>	   	
    	   </div><!-- .right-side-title -->
    	   
       </div><!-- .inner -->
       
    </div><!-- .title-area .clearfix .main-color -->
    
    

    <?php do_action('aftet_title_area'); ?>
    
    <?php do_action('woocommerce_before_wishlist'); ?>	
    
    <?php do_action('woocommerce_before_main_content');	?>

	<?php do_action('woocommerce_wishlist_description'); ?>

	<?php 
	    // If there is something in wishlist, define query
		if (!empty ($wishlist)){
			global $wp_query;
			$args = array( 'post_type' => 'product', 'post__in' => $wishlist);
			query_posts( $args );
			$have_wishlist = TRUE;
		}	
	?>
	
	
	<?php if ( have_posts() && $have_wishlist ) : ?>

    	<?php do_action( 'woocommerce_before_shop_loop' ); ?>
    
    	<?php woocommerce_product_loop_start(); ?>
    
    		<?php while ( have_posts() ) : the_post(); ?>
    
    			<?php woocommerce_get_template_part( 'content', 'product' ); ?>
    
    		<?php endwhile; ?>
    
    	<?php woocommerce_product_loop_end(); ?>
    	
    	<?php do_action( 'woocommerce_after_wishlist_loop' ); ?>
    	
    	<?php do_action( 'woocommerce_after_shop_loop' ); ?>

	<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

		<?php woocommerce_get_template( 'loop/no-products-found-wishlist.php' ); ?>

	<?php endif; ?>
	

	<?php do_action('woocommerce_after_main_content'); ?>
    
    <?php do_action('woocommerce_after_wishlist');?>	

	<?php do_action('woocommerce_sidebar'); ?>


<?php get_footer(); ?>