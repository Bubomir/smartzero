<?php
/**
 * The template for displaying Comments.
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */

 
    /* If the current post is protected by a password and the visitor has not yet entered the password we will return early without loading the comments. */ 
    if ( post_password_required() ) return;
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
    	
    	<h3 class="comments-title">
    		<?php printf( _n( 'Comments(%1$s)', 'Comments(%1$s)', get_comments_number(), 'essential' ), number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' ); ?>
    	</h3>
    	
    	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
        	
        	<nav role="navigation" id="comment-nav-above" class="site-navigation comment-navigation">
        		<h1 class="assistive-text"><?php _e( 'Comment navigation', 'essential' ); ?></h1>
        		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'essential' ) ); ?></div>
        		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'essential' ) ); ?></div>
        	</nav>
        	
    	<?php endif; ?>
    	
    	<ol class="commentlist">
    		<?php wp_list_comments( array( 'callback' => 'custom_comment' ) ); // Loop through and list the comments ?>
    	</ol>
    	
    	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
    	
        	<nav role="navigation" id="comment-nav-below" class="site-navigation comment-navigation">
        		<h1 class="assistive-text"><?php _e( 'Comment navigation', 'essential' ); ?></h1>
        		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'essential' ) ); ?></div>
        		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'essential' ) ); ?></div>
        	</nav>
    	
    	<?php endif;  ?>
    	
    <?php endif; ?>
    
    
    <?php
    	/* If comments are closed and there are comments, let's leave a little note, shall we? */
    	if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
    ?>
    	<p class="nocomments"><?php _e( 'Comments are closed.', 'essential' ); ?></p>
    	
    <?php endif; ?>
    
    
    <?php comment_form(); ?>

</div><!-- #comments .comments-area -->