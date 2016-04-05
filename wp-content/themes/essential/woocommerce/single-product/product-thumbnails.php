<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */

global $post, $product, $woocommerce;
$small_thumbnail_size = apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail');
$medium_thumbnail_size = apply_filters('single_product_large_thumbnail_size', 'shop_single');
?>
<div class="images">
	<?php $attachment_ids = $product->get_gallery_attachment_ids();
	 
	if ( $attachment_ids  OR has_post_thumbnail() ) { //check if there is any image?>
		<div id="big-image-product">
			<?php 
			if ( has_post_thumbnail() ) {// check if the post has a Post Thumbnail assigned to it.
				$large_image = wp_get_attachment_url( get_post_thumbnail_id());
				$arg = array(
					'data-zoom-image' => $large_image
				);
			the_post_thumbnail($medium_thumbnail_size,$arg);
			$attachment_ids[] = get_post_thumbnail_id( );
			} else {
				
				$arg = array(
					'data-zoom-image' =>$large_image
				);
				echo wp_get_attachment_image($attachment_ids[0], $medium_thumbnail_size);
			}
			?>
		</div>
		<?php if (count($attachment_ids) > 1) { // check if there is more then one image and show carusel ?>
		<div id="product-thumbnails" class="carusel">
			            <div class="buttons-container">
			            	<a class="buttons prev" href="#" data-icon="&#xe0d5;"></a>
			            	<a class="buttons next" href="#" data-icon="&#xe0d8;"></a>
			            </div>
			            <div class="viewport">
			            	<ul class="products">
								<?php
				
								$loop = 0;
				
								foreach ( $attachment_ids as $id ) {
				
									$large_image = wp_get_attachment_url( $id );
									$medium_image = wp_get_attachment_image_src($id, $medium_thumbnail_size);
									$arg = array(
												'data-zoom-image' 	=> $large_image,
											);
									$thumbnail_image = wp_get_attachment_image($id, $small_thumbnail_size,false,$arg);
									
									if ( ! $large_image )
										continue;
									?>
							 		<li>                                      
					            			<?php echo '<a href="'.esc_attr( $medium_image[0] ).'">'; ?>
					            			<?php echo $thumbnail_image; ?>
											<?php echo '</a>'; ?>
									</li>
									<?php
				
									$loop++;
				
								} ?>
							</ul>
						</div>
			</div>
			<?php } // end check if there is more then one image ?>
	<?php } //  end check if there is any image?>
</div>	