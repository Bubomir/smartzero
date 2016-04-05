<?php
/**
 * Template Name: Contact Template
 *
 * The template for displaying contact page.
 *
 * @package Essential
 * @since Essential 1.0
 * 
 */

    /* Get map code from page meta */
    $big_map = get_post_meta( get_the_ID(), 'big-map', true );
    $number_of_colums = 0 ;
    if ( get_post_meta( get_the_ID(), 'place-name', true ) OR  get_post_meta( get_the_ID(), 'place-adress', true ) OR   get_post_meta( get_the_ID(), 'place-map', true )){
    	$number_of_colums++;
    	$place_name = get_post_meta( get_the_ID(), 'place-name', true );
    	$place_adress = get_post_meta( get_the_ID(), 'place-adress', true ); 
    	$place_map = get_post_meta( get_the_ID(), 'place-map', true );
    	$first_column = TRUE;
    }
    if ( get_post_meta( get_the_ID(), 'place-name-2', true ) OR get_post_meta( get_the_ID(), 'place-adress-2', true ) OR get_post_meta( get_the_ID(), 'place-map-2', true )){
    	$number_of_colums++;
    	$place_name2 = get_post_meta( get_the_ID(), 'place-name-2', true );
    	$place_adress2 = get_post_meta( get_the_ID(), 'place-adress-2', true );
    	$place_map2 = get_post_meta( get_the_ID(), 'place-map-2', true );
    	$second_column = TRUE;
    }
    
    get_header(); 

?>

	<?php if ($big_map): ?>
		<div class="big-map">
		    <?php $map =  preg_replace('/width="(.*?)"/i', 'width="100%"', $big_map); echo  preg_replace('/height="(.*?)"/i', ' height="400px" ', $map);  ?>		    
		</div><!-- .big-map -->
	<?php endif; ?>
	
	
	<div class="title-area clearfix main-color">		
		<div class="inner">
		  <h1 class="title"><?php the_title(); ?></h1>
		  <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) woocommerce_breadcrumb(); ?>
	   </div><!-- .inner -->
	</div><!-- .title-area .clearfix .main-color -->
	
  	
  	<div class="stripe">
		<div class="inner">
			<p class="center"><?php echo get_post_meta( get_the_ID(), 'header-text', true ); ?></p>
		</div><!-- .inner -->
	</div><!-- .stripe -->	
	
	
	<div id="primary" class="content-area">
		
		 <div class="inner">
		 	
    		 	<?php if (isset($first_column)): ?>
    			 	<div class="contact-box-wrapper colums-<?php echo $number_of_colums?> first">
    			 		<div class="map"><?php $map =  preg_replace('/width="(.*?)"/i', 'width="100%"', $place_map); echo  preg_replace('/height="(.*?)"/i', ' height="300px" ', $map);  ?></div>
    			 		<h3><?php echo $place_name ?></h3>
    			 		<p><?php echo $place_adress ?></p> 
    			 	</div><!-- .contact-box-wrapper .colums-<?php echo $number_of_colums?> .first -->
    			<?php endif; ?>
    			 
    			<?php if (isset($second_column)): ?>	
        		 	<div class="contact-box-wrapper colums-<?php echo $number_of_colums?>">        		 	    
        		 		<div class="map"><?php $map2 =  preg_replace('/width="(.*?)"/i', 'width="100%"', $place_map2); echo  preg_replace('/height="(.*?)"/i', ' height="300px" ', $map2);  ?></div>
        		 		<h3><?php echo $place_name2 ?></h3>
        		 		<p><?php echo $place_adress2 ?></p>         		 		
        		 	</div><!-- .contact-box-wrapper .colums-<?php echo $number_of_colums?> -->
    		 	<?php endif; ?>
    		 	
    		 	<div class="clear"></div>    		 	
    		 	
    			<div id="content" class="site-content" role="main">	
    			         
    			</div><!-- #content -->
			
		  </div><!-- .inner -->
		  
	</div><!-- #primary .content-area -->
		
		
		
	<div class="stripefoot clearfix">
	    
		<div class="inner clearfix">
		    
			<h3><?php _e('Quick contact','esential')?></h3>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			    
			<div class="entry-wrap clearfix">
				
				<div class="entry-content-wrap clearfix">
					
					<div class="quick-contact clearfix">
					    
						<div id="emailSuccess"><?php _e('Your message has been sent successfully. Thank you!', 'essential'); ?></div>
						
						<div id="maincontactform" class="cf">
							<form action="#" id="contactform">
									<p class="clearfix">
										<label for="name"><?php _e('First name', 'essential'); ?><span>*</span></label> 
										<input type="text" name="name" class="textfield" id="name" value="" />
									</p>
									<p class="clearfix">
										<label for="email"><?php _e('Email addres', 'essential'); ?><span>*</span></label> 
										<input type="text" name="email" class="textfield" id="email" value="" />
										</p>
									<p class="clearfix">
										<label for="message"><?php _e('Message', 'essential'); ?><span>*</span></label> 
										<textarea name="message" id="message" class="textarea" cols="2" rows="5"></textarea>
									</p>
									<p class="clearfix">
										<input type="submit" name="submit" class="contact submit" id="buttonsend" value="<?php _e('Send message', 'essential'); ?>" />
										<input type="hidden" name="siteurl" id="siteurl" value="<?php echo get_template_directory_uri(); ?>" />
										<input type="hidden" name="postid" id="postid" value="<?php the_ID(); ?>" />
									</p>
									<p>          
										<span class="loading" style="display: none;"><?php _e('Please wait...','essential'); ?></span>
									</p>
							</form><!-- #contactform -->
							   
						</div><!-- #maincontactform .cf -->
						
			        </div><!-- .entry-content-wrap .clearfix -->
				   
					<?php while ( have_posts() ) : the_post(); ?>
			       		<div class="entry-content clearfix"> <?php the_content(); ?> </div>
			       	<?php endwhile; ?>
			       	
			     </div><!-- .entry-content-wrap .clearfix -->
			   
			</div><!-- .entry-wrap .clearfix -->						
			
	        </article><!-- #post-<?php the_ID(); ?> -->
	        
		</div><!-- .inner .clearfix -->
		
	</div><!-- .stripefoot .clearfix -->

<?php get_footer(); ?>