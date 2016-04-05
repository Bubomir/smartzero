<?php
/**
 *
 * The template for displaying simple slider
 *
 * @package Essential
 * @since Essential 1.0
 */

 global $essential_slider_settings, $prefix, $myposts; ?>


<div class="inner fix">
    
	<div class="simple-slider-wrapper">
	    
		<?php echo wp_get_attachment_image( $essential_slider_settings[$prefix.'sipmle-slider-image'], 'full' , FALSE , array('class' => "main-img") ); // Get image for slider ?>
		
		<h2><?php echo $essential_slider_settings[$prefix.'simple-slider-heading-text']; ?></h2>
		
		<h3><?php echo $essential_slider_settings[$prefix.'simple-slider-sub-heading-text']; ?></h3>		
		
		<div class="carusel simple-slider-posts">
		    
        	<a class="buttons prev" href="#" data-icon="&#xe0d5;"></a>
        	
            <div class="viewport">
                
            	<ul class="products">
        			<?php foreach( $myposts as $post ) :  setup_postdata($post); ?>    			
        			<li>
        			    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'essential' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
        			         <?php the_post_thumbnail('slide'); ?>
        			    </a>
        			</li>	 
        			<?php endforeach; ?>
    			</ul><!-- .products -->
    			
			</div><!-- .viewport -->
			
			<a class="buttons next" href="#" data-icon="&#xe0d8;"></a>
			
		</div><!-- .carusel .simple-slider-posts -->	
		
		<p><?php echo $essential_slider_settings[$prefix.'simple-slider-text']; ?></p>
	
	</div><!-- .simple-slider-wrapper -->
	
</div><!-- .inner .fix -->		 