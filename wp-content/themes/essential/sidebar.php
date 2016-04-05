<?php
/**
 *
 * The template for sidebar
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */

 global $theme_options; ?>
 
 

<?php if ( isset( $theme_options['layout'] ) && ( $theme_options['layout'] != 'layout-full' ) && is_active_sidebar('primary') ): ?>	
    
<div id="sidebar">
    
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('primary') ) : ?>
		
	<?php endif; ?>
	
</div><!-- #sidebar -->

<?php endif; ?>