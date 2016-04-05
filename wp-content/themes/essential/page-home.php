<?php
/**
 * Template Name: Home page
 * 
 * The main template file for displaying home page.
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
 get_header(); ?>
 
 
<div class="stripe">
	<div class="inner">
	   <div class="center"><?php echo  do_shortcode( stripslashes(str_replace(array('##year##', '##blog_name##', '##description##'), array(date('Y'), get_bloginfo('name'), get_bloginfo('description')), thm_get_option('thm_home-header-text')))); ?></div>
	</div><!-- .inner -->
</div><!-- .stripe -->


<div id="primary" class="content-area woocommerce">
	<div class="inner">
		<div id="content" class="site-content wide" role="main">
			<
		    <?php 
		    wp_reset_query();
		    if ($post->post_content): ?>	
    			<article id="post-<?php the_ID(); ?> main-article" <?php post_class(); ?>>
    				<div class="entry-wrap clearfix">
    					<?php if ($header_image_id =  get_post_meta( get_the_ID(), 'essential_header_image', true )): ?>
    						<div class="image-container">
    							 <?php echo wp_get_attachment_image($header_image_id,'full'); ?>
    						</div>
    					<?php endif; ?> 
    					<div class="entry-content-wrap clearfix no-thumb">			
    						<div class="entry-content">
    							<?php the_content(); ?>
    							<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'essential' ), 'after' => '</div>' ) ); ?>
    						</div>
    					</div><!-- .entry-content-wrap .clearfix .no-thumb -->						   
    				</div><!-- .entry-wrap .clearfix -->
    			</article><!-- #post-<?php the_ID(); ?> .main-article -->
			<?php endif; ?>					
			
			
			<?php
			
			/* Home products loop */
			if (is_woocommerce_activated()):
				
				add_filter('loop_shop_columns', 'loop_columns4'); //  4 products per row
				
				if( function_exists('essential_product_categories') && thm_get_option('thm_home-product-categories')):
						$args   = array() ;
						$cat    =  array();
						$categories = thm_get_option('thm_home-product-categories');
						if (!is_array($categories)){
							$categories = explode(',' , thm_get_option('thm_home-product-categories'));
						}
						foreach ($categories as $cat_slug):
							$idObj = get_term_by('slug', $cat_slug, 'product_cat');
							$cat[] = $idObj->term_id;									
                        endforeach;
						$cat = implode(',', $cat);								
						woocommerce_product_loop_start();
						essential_product_categories(array( 'include' => $cat));
						woocommerce_product_loop_end();
                endif;
                
				if ( thm_get_option('thm_home-tabs') == 'on'):
					woocommerce_get_template_part( 'home-page-tabs' ); 
				else:
					 woocommerce_get_template_part( 'home-page' ); 
                endif;
                 
			endif; 
            
		    
            /* Home loop */
		    if (thm_get_option('thm_home-posts') > 0 ) :
             
				$args = array( 'post_type' => 'post', 'posts_per_page' => thm_get_option('thm_home-posts')  );
				$loop = new WP_Query( $args );
				
				while ( $loop->have_posts() ) : 
				   $loop->the_post();
				   get_template_part( 'content', get_post_format() );	
				endwhile; 

		    endif; 				 
		 ?>	
		 
		</div><!-- #content .site-content .wide -->				
	</div><!-- .inner -->
</div><!-- #primary .content-area .woocommerce -->


<?php 

/* Home page footer text */

if (thm_get_option('thm_home-footer-text') ) : ?>
<div class="stripefoot clearfix">
	<div class="inner clearfix">
		<article>
			<div class="entry-wrap clearfix">
				<div class="entry-content-wrap clearfix <?php if ( !thm_get_option('thm_home-footer-image') ) echo "no-thumb";?>">
					<?php if (thm_get_option('thm_home-footer-image')) : ?>
					<div class="entry-featured-image">
						<a href="<?php echo thm_get_option('thm_home-footer-link') ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'essential' ), thm_get_option('thm_home-footer-head')) ); ?>" rel="bookmark">
							<?php echo wp_get_attachment_image(thm_get_option('thm_home-footer-image'),'full'); ?>
						</a>
					</div>
					<?php endif; ?>	
					<div class="entry-content">
						<h3 class="entry-title">
						    <a href="<?php echo thm_get_option('thm_home-footer-link') ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'essential' ), thm_get_option('thm_home-footer-head') ) ); ?>" rel="bookmark"><?php echo thm_get_option('thm_home-footer-head')?></a>
						</h3>
						<?php echo do_shortcode( stripslashes(thm_get_option('thm_home-footer-text'))) ?>
					</div><!-- .entry-content -->
				</div><!-- .entry-content-wrap .clearfix -->
			</div><!-- .entry-wrap .clearfix -->					
		</article>
	</div><!-- .inner .clearfix -->
</div><!-- .stripefoot .clearfix -->
<?php endif; ?>

<?php get_footer(); ?>