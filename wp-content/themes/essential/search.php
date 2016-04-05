<?php
/**
 * 
 * The main template file for displaying search results.
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
 get_header(); ?>
 
 
<div class="title-area clearfix main-color">
	<div class="inner">	  
	    <h1 class="title"><?php printf( __( 'Search Results for: %s', 'essential' ), '<span>' . get_search_query() . '</span>' ); ?></h1>	   
	    <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) woocommerce_breadcrumb(); ?>
   </div><!-- .inner -->
</div><!-- .title-area .clearfix -->


<div id="primary" class="content-area">
	<div class="inner">
		<div id="content" class="site-content" role="main">	
		<?php 
		
			if ( have_posts() ) : 

				 while ( have_posts() ) : 
				       
				       the_post(); 
					   get_template_part( 'content', get_post_format() );    						

				 endwhile;
				 
				 essential_pagination();

			else :

				 get_template_part( 'no-results', 'index' );

		    endif; 
		 
		?>	
		</div><!-- #content .site-content -->
		
		<?php get_sidebar(); ?>	
		
	</div><!-- .inner -->
	
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>