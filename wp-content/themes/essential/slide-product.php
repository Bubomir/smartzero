<?php
/**
 * The template used for displaying single product slide 
 *
 * @package Essential
 * @since Esential 1.0
 * 
 */

 global $product, $essential_title_max_chars, $essential_excerpt_max_chars; ?>

<div class="da-slide">
    
	<h2><?php echo essential_shorten_text(get_the_title(), $essential_title_max_chars); ?></h2>
	
	<div class="excerpt"><?php echo essential_shorten_text(get_the_excerpt(), $essential_excerpt_max_chars ); ?></div><!-- .excerpt -->
	
	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'essential' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark" class="da-link">
	    <?php _e( 'Read more', 'essential' ) ?>
    </a>
	
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="da-img">
		<?php the_post_thumbnail('slide'); ?>
		<?php if ( $price_html = $product->get_price_html() ) : ?>
			<span class="price"><?php echo $price_html; ?></span>
		<?php endif; ?>			
	</div><!-- .da-img -->	
	<?php endif; ?>

</div><!-- .da-slide -->