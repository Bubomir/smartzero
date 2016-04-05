<?php
/**
 * The template used for displaying single post type slide 
 *
 * @package Essential
 * @since Esential 1.0
 * 
 */
 
    global $essential_title_max_chars, $essential_excerpt_max_chars; 
    $button_link    = get_post_meta( get_the_ID(), 'button-link', true );
    $button_text    = get_post_meta( get_the_ID(), 'button-text', true );
    $use_org_image  = get_post_meta( get_the_ID(), 'use-org-image', true );
?>


<div class="da-slide">
	<h2><?php echo essential_shorten_text( get_the_title(), $essential_title_max_chars ); ?></h2>
	
	<div class="excerpt"><?php echo essential_shorten_text( get_the_excerpt(), $essential_excerpt_max_chars ); ?></div><!-- .excerpt -->
	
	<a href="<?php echo $button_link ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'essential' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark" class="da-link">
	    <?php echo $button_text; ?>
	</a>
	
	<?php if ( has_post_thumbnail() ) : ?> 
	    <?php if ($use_org_image == 'yes' ) : ?>
		  <div class="da-img org"> <?php the_post_thumbnail('full'); ?></div><!-- .da-img .org --> 	
		<?php else: ?>	
		  <div class="da-img"><?php the_post_thumbnail('slide'); ?></div><!-- .da-img --> 
	    <?php endif; ?>
	<?php endif; ?>
	
</div><!-- .da-slide -->

