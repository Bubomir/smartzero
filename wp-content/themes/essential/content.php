<?php
/**
 * Fallback content include
 * 
 * @package Essential
 * @since Essential 1.0
 * 
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="entry-wrap clearfix">

		<div class="entry-header">
			
			<h2 class="entry-title">
			    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'essential' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
			        <?php the_title(); ?>
			    </a>
		    </h2>
			
			<?php 
			/* Check if post is password protected and if there are any comments */
			if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			
			     <span class="comments-link">
			         <span class="icon" data-icon="&#xe020;"></span><!-- .icon --> 
			         <?php comments_popup_link( __( 'No comment', 'essential' ), __( '1 Comment', 'essential' ), __( '% Comments', 'essential' ) ); ?>
			     </span>
			
			<?php endif; ?>
			
		</div><!-- .entry-header -->
		
	    <?php if ( has_post_thumbnail() ): ?>
	        
    		<div class="entry-featured-image">
    		    
    			<div class="overlay"></div>
    			
    			<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'essential' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
    				<?php the_post_thumbnail('thumbnail'); ?>
    			</a>
    			
    		</div><!-- .entry-featured-image -->
    		
		<?php endif; ?>	
		
		<div class="entry-content-wrap clearfix <?php if ( !has_post_thumbnail()) echo "no-thumb";?>">			    
			
			<div class="entry-content"> 
			    
				<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'essential' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'essential' ), 'after' => '</div>' ) ); ?>
				
			</div><!-- .entry-content -->				
			
    		<footer class="entry-meta">
    		    
				<span class="entry-meta"><?php custom_posted_on(); ?></span>
				<?php edit_post_link( __( 'Edit', 'essential' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
				<?php the_tags( '<span class="tags">'.__( 'Tags: ', 'essential' ), ', ', '</span>' ); ?>	
							
    		</footer>
    		    		
	    </div><!-- .entry-content-wrap -->
	    	    
	</div><!-- .entry-wrap -->
	
</article><!-- #post-<?php the_ID(); ?> -->