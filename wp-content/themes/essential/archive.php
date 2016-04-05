<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
get_header(); ?>
    
    <div class="title-area clearfix main-color">
        
    	<div class="inner">
    	   
    	   <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
    	   
    	   <?php /* If this is a category archive */ if (is_category()) { ?>
    	    <h1 class="title"><?php single_cat_title(); ?></h1>
    	   <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
    	    <h1 class="title"><?php _e('Posts Tagged', 'essential') ?> &#8216;<?php single_tag_title(); ?>&#8217;</h1>
    	   <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
    	    <h1 class="title"><?php _e('Archive for', 'essential') ?> <?php the_time('F jS, Y'); ?></h1>
    	   <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
    	    <h1 class="title"><?php _e('Archive for', 'essential') ?> <?php the_time('F, Y'); ?></h1>
    	   <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
    	    <h1 class="title"><?php _e('Archive for', 'essential') ?> <?php the_time('Y'); ?></h1>
    	   <?php /* If this is an author archive */ } elseif (is_author()) { ?>
    	    <h1 class="title"><?php _e('Author Archive', 'essential') ?></h1>
    	   <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
    	    <h1 class="title"><?php _e('Blog Archives', 'essential') ?></h1>
    	   <?php } ?>
    	   
    	   <?php if (function_exists('woocommerce_breadcrumb')) woocommerce_breadcrumb(); ?>
    	   
       </div><!-- .inner -->
       
    </div><!-- #primary .content-area -->
      
      
    <div id="primary" class="content-area">
        
    	<div class="inner">
    	    
    		<div id="content" class="site-content" role="main">
    
    		<?php if ( have_posts() ) : ?>
    
                <?php /* Start the Loop */ ?>
    			<?php while ( have_posts() ) : the_post(); ?>
    
    				<?php 
                        /* Include the Post-Format-specific template for the content.
                         * If you want to overload this in a child theme then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */    				
    				    get_template_part('content', get_post_format()); 
    				?>
    
    			<?php endwhile; ?>
    			
    			<?php essential_pagination() ?>
    
    		<?php else : ?>
    
    			<?php get_template_part('no-results', 'index'); ?>
    
    		<?php endif; ?>
    
    		</div>
    		
    		<?php get_sidebar(); ?>	
    		
    	</div><!-- .inner -->
    	
    </div><!-- #primary .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>