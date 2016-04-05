<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
?>

<article id="post-0" class="post no-results not-found">
    
	<header class="entry-header">
		<h1 class="entry-title"><?php _e( 'Nothing Found', 'essential' ); ?></h1>
	</header><!-- .entry-header -->
	
	<div class="entry-content">
	    
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
		    
			<div class="entry-content-wrap clearfix no-thumb">
				<div class="entry-summary">
				<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'essential' ), admin_url( 'post-new.php' ) ); ?></p>
				</div>			
			</div><!-- .entry-content-wrap .clearfix .no-thumb -->
			
		<?php elseif ( is_search() ) : ?>
		    
			<div class="entry-content-wrap clearfix no-thumb">
				<div class="entry-summary">
					<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'essential' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</div><!-- .entry-content-wrap .clearfix .no-thumb -->
			
		<?php else : ?>
		    
			<div class="entry-content-wrap clearfix no-thumb">
				<div class="entry-summary">
					<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'essential' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</div><!-- .entry-content-wrap .clearfix .no-thumb -->
			
		<?php endif; ?>
		
	</div><!-- .entry-content -->
	
</article><!-- #post-0 .post .no-results .not-found -->