<?php
/**
 * Loop Action buttons
 *
* @package Essential
* @since Essential 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
?>
<div class="action-buttons">
	<a href="<?php echo get_permalink( $product->id )?>" class="view-product linkcolor-as-bg" title="<?php _e('View product','essential')?>"><span data-icon="&#xe024;"></span></a>
	<?php if ( ! $product->is_in_stock() ) : ?>
	
		
	
	<?php else : ?>
	
		<?php
			$link = array(
				'url'   => '',
				'label' => '',
				'class' => ''
			);
	
			$handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );
			if($handler != 'variable' AND $handler != 'grouped' AND $handler != 'external') {
				if ( $product->is_purchasable() ) {
					$link['url'] 	= apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
					$link['label'] 	= apply_filters( 'add_to_cart_text', __( 'Add to cart', 'woocommerce' ) );
					$link['class']  = apply_filters( 'add_to_cart_class', 'add_to_cart_button' );
					echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s linkcolor-as-bg" title="%s"><span data-icon="&#xe067;"></span></a>', esc_url( $link['url'] ), esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), esc_html( $link['label'] ) ), $product, $link,__('Add to cart','essential') );
				}
			}
		?>
	
	<?php endif; ?>
	
	<?php
	if(thm_get_option('thm_compare-page')){			
	 if (essential_is_product_in_compare($product->id)){?> 
		<a href="#" data-product-id="<?php echo $product->id ?>" class="remove-compare remove linkcolor-as-bg" title="<?php _e('Remove from compare','essential')?>"><span data-icon="&#xe09a;"></span></a>
	<?php } else {?>
		<a href="#" data-product-id="<?php echo $product->id ?>" class="add-compare  linkcolor-as-bg" title="<?php _e('Compare product','essential')?>"><span data-icon="&#xe09a;"></span></a>	
	<?php }
	}?>
	<?php
	if(thm_get_option('thm_wishlist-page')){ 
		if (essential_is_product_in_wishlist($product->id)){?>
		<a href="#" data-product-id="<?php echo $product->id ?>" class="remove-wishlist remove linkcolor-as-bg" title="<?php _e('Remove from compare','essential')?>"><span data-icon="&#xe01a;"></span></a>
	<?php } else {?>	 
		<a  href="#" data-product-id="<?php echo $product->id ?>" class="add-wishlist linkcolor-as-bg" title="<?php _e('Add to wishlist', 'essential')?>"><span data-icon="&#xe01a;"></span></a>
	<?php }
	}?>
</div>