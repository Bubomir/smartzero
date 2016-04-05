<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
 
 get_header(); ?>

	<div class="title-area clearfix main-color ">
		<div class="inner">
		  <h1 class="title"><?php the_title(); ?></h1>
		    <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) woocommerce_breadcrumb(); ?>
	   </div><!-- .inner -->
	</div><!-- .title-area .clearfix -->
  
	<div id="primary" class="content-area">
	   
	   <div class="inner">
	       
    		<div id="content" class="site-content" role="main">
    
    		<?php while ( have_posts() ) : the_post(); ?>
    
        		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        			<div class="entry-wrap clearfix">
        				<?php if ($header_image_id =  get_post_meta( get_the_ID(), 'essential_header_image', true )): ?>
        					<div class="image-container">
        						 <?php echo wp_get_attachment_image( $header_image_id,'full'); ?>
        					</div>
        				<?php endif; ?> 
        				<div class="entry-content-wrap clearfix no-thumb">    
        					<div class="entry-content">
        						<?php the_content(); ?>
        						<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'essential' ), 'after' => '</div>' ) ); ?>
        					</div><!-- .entry-content -->   	
        				    <?php edit_post_link( __( 'Edit', 'essential' ), '<span class="edit-link">', '</span>' ); ?>    
        				</div><!-- .entry-content-wrap .clearfix .no-thumb -->        			   
        			</div><!-- .entry-wrap .clearfix -->
        		</article><!-- #post-<?php the_ID(); ?> -->
    
    		<?php endwhile; ?>
    
    		</div><!-- #content .site-content -->
    		
    		<?php get_sidebar(); ?>
		
		</div><!-- .inner -->
	
	</div><!-- #primary .content-area -->
	
<?php get_footer(); ?>