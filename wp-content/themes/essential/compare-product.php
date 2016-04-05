<?php
/**
 * The template for displaying product content within compare product list
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */

    if ( ! defined( 'ABSPATH' ) ) exit;     // exit if accessed directly
    global $product, $woocommerce_loop;     // get variables
    if ( ! $product->is_visible() )	return; // ensure that product is visible
?>

<ul class="compared-product">
    
	<li class="title headergradient">
	    <h3>
	        <a href="<?php the_permalink(); ?>"><?php echo essential_shorten_text( get_the_title(), 40 ) ?></a>
	    </h3>	    
	    <a data-product-id='<?php echo $post->ID ?>' class='remove-compare' data-icon='&#xe078;'></a>
	</li><!-- .title -->
	
	<li class="image">
	    <?php the_post_thumbnail( $post->ID , apply_filters( 'compare_product_img', 'shop_catalog' ) );?><?php woocommerce_template_loop_price() ?>	    
	</li><!-- .image -->
	
	<li class="rating">
	<?php
		$count    = $product->get_rating_count();
		$average  = $product->get_average_rating();
		echo '<span class="nuber-rating">'.sprintf( _n('%s review %s', '%s reviews  %s', $count, 'essential'), '<span itemprop="ratingCount" class="count">'.$count.'</span>', '' ).'</span>';
		echo '<div class="star-rating" title="'.sprintf(__( 'Rated %s out of 5', 'woocommerce' ), $average ).'"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div>';
	?>
	</li><!-- .rating -->
					
	<li class="details"><div><?php echo wp_trim_words( get_the_excerpt(), $num_words = 50, $more = null); ?></div></li><!-- .details -->
	
	<li class="available"><?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock';  ?></li><!-- .available -->
	
	<li class="price"><?php woocommerce_template_loop_price(); ?></li><!-- .price -->
	
</ul><!-- .compared-product -->