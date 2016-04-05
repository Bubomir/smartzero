<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
 
get_header(); ?>

<div class="title-area clearfix main-color">
    
	<div class="inner">
	  <h1 class="title""><?php _e('Error 404 - Not Found','essential')?></h1>
	    <?php if (function_exists('woocommerce_breadcrumb')) woocommerce_breadcrumb(); ?>
    </div>
   
</div><!-- .title-area -->
  
  
<div id="primary" class="content-area">
    
 <div class="inner">
     
	<div id="content" class="site-content" role="main">
		
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-wrap clearfix">
				<div class="entry-content-wrap clearfix no-thumb">
					<div class="entry-content">
						<strong>
						    <?php _e("We're very sorry, but that page doesn't exist or has been moved.", 'essential')?><br />
	  					    <?php _e("Please make sure you have the right URL.", 'essential')?>
	  					</strong>	  					
	  				</div>
	  			</div>		
	  		</div>
  		</article><!-- #post-<?php the_ID(); ?> -->
  		
	</div><!-- #content .site-content -->
	
	<?php get_sidebar(); ?>
	
	</div><!-- .inner -->
	
</div><!-- #primary .content-area -->

<?php get_footer(); ?>