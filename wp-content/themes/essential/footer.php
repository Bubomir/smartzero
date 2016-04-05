<?php
/**
 * The template used for displaying footer
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */
?>

<footer class="site-footer fix">    
    
    <?php // Widget area 1-4 ?>
    
	<div class="upper-footer main-color clearfix">
	    
		<div class="inner area-count-<?php echo upper_footer_count()?> clearfix">
		    
			<?php if ( function_exists('is_sidebar_active') && is_sidebar_active('footer-1') ) { ?>
				<div class="widget-area-box">
					<?php dynamic_sidebar( 'footer-1' ) ?>
				</div> 
			<?php } ?>
			<?php if ( function_exists('is_sidebar_active') && is_sidebar_active('footer-2') ) { ?>
				<div class="widget-area-box">
					<?php dynamic_sidebar( 'footer-2' ) ?>
				</div>	 
			<?php } ?>
			<?php if ( function_exists('is_sidebar_active') && is_sidebar_active('footer-3') ) { ?>
				<div class="widget-area-box">
					<?php dynamic_sidebar( 'footer-3' ) ?>
				</div>	 
			<?php } ?>
			<?php if ( function_exists('is_sidebar_active') && is_sidebar_active('footer-4') ) { ?>
				<div class="widget-area-box">
					<?php dynamic_sidebar( 'footer-4' ) ?>
				</div>	 
			<?php } ?>
		
		</div><!-- .inner -->
		
	</div><!-- .upper-footer -->	
	
	
	<?php // Widget area 5-8 ?>
	
	<div class="bottom-footer clearfix">	
	    		
		<div class="inner area-count-<?php echo bottom_footer_count()?> clearfix">
		    
			<?php if ( function_exists('is_sidebar_active') && is_sidebar_active('footer-5') ) { ?>
				<div class="widget-area-box">
					<?php dynamic_sidebar( 'footer-5' ) ?>
				</div> 
			<?php } ?>
			<?php if ( function_exists('is_sidebar_active') && is_sidebar_active('footer-6') ) { ?>
				<div class="widget-area-box">
					<?php dynamic_sidebar( 'footer-6' ) ?>
				</div>	 
			<?php } ?>
			<?php if ( function_exists('is_sidebar_active') && is_sidebar_active('footer-7') ) { ?>
				<div class="widget-area-box">
					<?php dynamic_sidebar( 'footer-7' ) ?>
				</div>	 
			<?php } ?>
			<?php if ( function_exists('is_sidebar_active') && is_sidebar_active('footer-8') ) { ?>
				<div class="widget-area-box">
					<?php dynamic_sidebar( 'footer-8' ) ?>
				</div>	 
			<?php } ?>
		
		</div><!-- .inner -->
					
	</div><!-- .bottom-footer -->
	
	<div id="copywright-footer">
		
		<div class="inner">
		    <?php 
		      /* Output footer text and replace footer ##tags## with actual values */        
		      echo do_shortcode( stripslashes(str_replace( array('##year##', '##blog_name##', '##description##'), array(date('Y'), get_bloginfo('name'), get_bloginfo('description')), thm_get_option('thm_copyright-text')))); 
		     ?>
		</div><!-- .inner -->
			
	</div><!-- #copywright-footer -->
	
</footer><!-- .site-footer -->


</div><!-- #wrapper -->

<?php wp_footer(); ?>

</body>
</html>