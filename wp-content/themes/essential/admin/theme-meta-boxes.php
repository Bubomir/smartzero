<?php
/**
 * Meta boxes for theme
 *
 * @package Essential
 * @since Essential 1.0
 */


if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------*/
/*  Adds a meta box to the post/page editing screen */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'essential_custom_meta' ) ) {
	function essential_custom_meta() {
		$screens = array( 'post', 'page','product' );
	    foreach ($screens as $screen) {
	        add_meta_box('essential_meta', __( 'Essential theme', 'essential' ), 'essential_meta_callback', $screen);
			add_meta_box('Layout', __( 'Layout', 'essential' ), 'essential_layout_meta_callback', $screen,'side');
	    }
	} 
}

/*-----------------------------------------------------------------------------------*/
/*  Adds header image box to the post/page editing screen */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'essential_meta_callback' ) ) {
	function essential_meta_callback( $post ) {
	    
		$stored_meta = get_post_meta( $post->ID );
		$def_image =  get_template_directory_uri().'/images/placeholder.png';
		wp_nonce_field( basename( __FILE__ ), 'essential_nonce' );
        
		if (isset($stored_meta['essential_header_image'][0]) && $stored_meta['essential_header_image'][0]) { 
			$image = wp_get_attachment_image_src($stored_meta['essential_header_image'][0], 'large');
			$image = $image[0]; } 
		else {
			$stored_meta['essential_header_image'][0] = FALSE; 
			$image =  get_template_directory_uri().'/images/placeholder.png'; 
		}
		echo '	<p><h4>'.__('Header Image', 'essential').'</h4>
				<span class="custom_default_image" style="display:none">'.$def_image.'</span>';
                
		echo	'<input name="essential_header_image" id="essential_header_image" type="hidden" class="custom_upload_image" value="'.$stored_meta['essential_header_image'][0].'" />
				<img src="'.$image.'" class="custom_preview_image" alt="" /><br />
				<input class="custom_upload_image_button button" type="button" value="'.__('Choose Image', 'essential').'" />
				<small> <a href="#" class="custom_clear_image_button">'.__('Remove Image', 'essential').'</a></small>
				<br clear="all" /><span class="description">'.__('Upload image for header Image', 'essential').'</p>';
   }
}

/*-----------------------------------------------------------------------------------*/
/*  Adds choose layout radio button to the post/page editing screen */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'essential_layout_meta_callback' ) ) {
	function essential_layout_meta_callback( $post ) {
		$stored_meta = get_post_meta( $post->ID );
		if(!isset($stored_meta['_layout'][0]))
			$stored_meta['_layout'][0] ='';
		?>
		<p>
		    <span class="example-row-title">Chose layout option</span>
		    <div class="example-row-content">
		    	<label for="meta-radio-def">
		            <input type="radio" name="_layout" id="meta-radio-def" value="" <?php checked( $stored_meta['_layout'][0], '' )?>>
		           Default
		        </label>
		        <label for="meta-radio-one">
		            <input type="radio" name="_layout" id="meta-radio-one" value="layout-full" '<?php checked( $stored_meta['_layout'][0], 'layout-full' )?>>
		            Full
		        </label>
		        <label for="meta-radio-two"> 
		            <input type="radio" name="_layout" id="meta-radio-two" value="layout-right"<?php checked( $stored_meta['_layout'][0], 'layout-right' )?>>
		            Right 
		        </label>
		        <label for="meta-radio-three">
		            <input type="radio" name="_layout" id="meta-radio-three" value="layout-left"'<?php checked( $stored_meta['_layout'][0], 'layout-left' )?>>
		           Left 
		        </label>
		       
		    </div>
		</p>
		<?php		
	  }
}

/*-----------------------------------------------------------------------------------*/
/*  Adds wp admin scripts */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'essential_admin_scripts' ) ) {
	function essential_admin_scripts() {
		global $typenow;		
	    if( $typenow == 'post' OR $typenow == 'page'  ) {
      	    wp_enqueue_media();
    	    wp_register_script( 'media-uploader', get_stylesheet_directory_uri() . '/admin/includes/js/media-uploader.js', array( 'jquery' ) );
        	wp_localize_script( 'meta-image', 'meta_image', array('title' => 'Choose or Upload an Image', 'button' => 'Use this image') );
			wp_enqueue_script( 'meta-image' );
        }
	}
}
add_action('add_meta_boxes', 'essential_custom_meta');
add_action('admin_print_scripts', 'essential_admin_scripts');

/*-----------------------------------------------------------------------------------*/
/*  Adds a meta box to the contact page editing screen
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'essential_contact_meta' ) ) {
	function essential_contact_meta() {
		if(isset($_GET['post']) OR isset($_POST['post_ID']) ){
			$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];			
			if ($contact_page = thm_get_option('thm_contact-page')){				
	   			wp_enqueue_style('thm-admin', get_template_directory_uri() . '/admin/includes/css/admin-styles.css');
				if ($contact_page == $post_id OR (is_array($contact_page) && in_array($post_id, $contact_page)))
			         add_meta_box('essential_contact_meta', __( 'Contact page options', 'essential' ), 'essential_contact_callback');            
			}
		}
	} 
}
if ( ! function_exists( 'essential_contact_callback' ) ) {
	function essential_contact_callback( $post ) {
		$stored_meta = get_post_meta( $post->ID );
		wp_nonce_field( basename( __FILE__ ), 'essential_nonce' );
		
		$wp_editor_arg = array( 'media_buttons' => FALSE,
								'teeny'			=> TRUE,
								'wpautop'		=> FALSE
								);
	    ?>
	
		<ul class="essential-options">
			<li>
				<h4>Contact form</h4>
				<label for="mail-to" class="example-row-title"><?php _e('Send mail to', 'essential')?></label>
			   <input type="text" name="contact-mail"  value="<?php if (isset($stored_meta['contact-mail'][0]) ) echo $stored_meta['contact-mail'][0]; ?>" />
			</li>
			<li>
				<h4>Header</h4>
				<label for="big-map" class="example-row-title"><?php _e('Big Map code', 'essential')?></label>
			   <textarea name="big-map" cols="50" rows="10"><?php if (isset($stored_meta['big-map'][0])) echo $stored_meta['big-map'][0] ?></textarea>
			</li>
			<li>
			    <label for="header-text" class="example-row-title"><?php _e('Header text', 'essential')?></label>
			    <input type="text" name="header-text"  value="<?php if (isset($stored_meta['header-text'][0])) echo $stored_meta['header-text'][0] ?>" />
			</li>
			<li><h4>First</h4>
				<p>
			        <label for="meta-text" class="example-row-title"><?php _e('Place name', 'essential')?></label>
			        <input type="text" name="place-name"  value="<?php if (isset($stored_meta['place-name'][0])) echo $stored_meta['place-name'][0] ?>" />
			    </p>
			    <p>
			        <label for="meta-text" class="example-row-title"><?php _e('Place Address', 'essential')?></label>
			        <?php 
			        	if (isset($stored_meta['place-adress'][0]))
			        		wp_editor( $stored_meta['place-adress'][0] , 'place-adress', $wp_editor_arg); 
						else
							wp_editor( '' , 'place-adress', $wp_editor_arg); 
			        ?>			        
			    </p>
			    <p>
			        <label for="meta-text" class="example-row-title"><?php _e('Place map', 'essential')?></label>
			        <textarea name="place-map" cols="50" rows="10"><?php if (isset($stored_meta['place-map'][0])) echo $stored_meta['place-map'][0] ?></textarea>
			    </p>
			</li>
			<li class="second">
				<h4>Second</h4>
				<p>
			        <label for="meta-text" class="example-row-title"><?php _e('Place name', 'essential')?></label>
			        <input type="text" name="place-name-2"  value="<?php  if (isset($stored_meta['place-name-2'][0])) echo $stored_meta['place-name-2'][0] ?>" />
			    </p>
			    <p>
			        <label for="meta-text" class="example-row-title"><?php _e('Place Address', 'essential')?></label>
			        <?php 
    			        if (isset($stored_meta['place-adress-2'][0]))
    			        	wp_editor( $stored_meta['place-adress-2'][0] , 'place-adress-2',$wp_editor_arg);
    					else
    						wp_editor( '' , 'place-adress-2', $wp_editor_arg); 
			        ?>  
			    </p>
			    <p>
			        <label for="meta-text" class="example-row-title"><?php _e('Place map', 'essential')?></label>
			        <textarea name="place-map-2" cols="50" rows="10"><?php if (isset($stored_meta['place-map-2'][0])) echo $stored_meta['place-map-2'][0] ?></textarea>
			    </p>
			</li>
		</ul>	
		
		<?php }		
	    
}
add_action( 'add_meta_boxes', 'essential_contact_meta' );


/*-----------------------------------------------------------------------------------*/
/*  Saves the custom meta input
/*-----------------------------------------------------------------------------------*/	
if ( ! function_exists( 'essential_meta_save' ) ) {
	function essential_meta_save( $post_id ) {
	 
	    // Checks save status
	    $is_autosave = wp_is_post_autosave( $post_id );
	    $is_revision = wp_is_post_revision( $post_id );
	    $is_valid_nonce = ( isset( $_POST[ 'essential_nonce' ] ) && wp_verify_nonce( $_POST[ 'essential_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	 
	   // Exits script depending on save status
       if ( $is_autosave || $is_revision || !$is_valid_nonce ) return;
	 
		
	   if( isset( $_POST[ 'essential_header_image' ] ) )
		    update_post_meta( $post_id, 'essential_header_image', $_POST[ 'essential_header_image' ] );
	
	   if( isset( $_POST[ '_layout' ] ) )
		    update_post_meta( $post_id, '_layout', $_POST[ '_layout' ] );
		   
		
	   if( isset( $_POST[ 'place-name' ] ) )	     					
		    update_post_meta( $post_id, 'place-name', $_POST[ 'place-name' ] );
			
		
		if( isset( $_POST[ 'place-adress' ] ) )	     	
		    update_post_meta( $post_id, 'place-adress', $_POST[ 'place-adress' ]  );
			
		
		if( isset( $_POST[ 'place-map' ] ) )
	       	update_post_meta( $post_id, 'place-map', $_POST[ 'place-map' ]  );
			
		
		if( isset( $_POST[ 'place-name-2' ] ) )
	    	update_post_meta( $post_id, 'place-name-2', $_POST[ 'place-name-2' ] );
			

        if( isset( $_POST[ 'place-adress-2' ] ) )
		   	update_post_meta( $post_id, 'place-adress-2', $_POST[ 'place-adress-2' ]  );
			
		
		if( isset( $_POST[ 'place-map' ] ) )	    
		    update_post_meta( $post_id, 'place-map-2', $_POST[ 'place-map-2' ]  );
			
		
		if( isset( $_POST[ 'big-map' ] ) )	     	
		    update_post_meta( $post_id, 'big-map', $_POST[ 'big-map' ]  );
			
		
		if( isset( $_POST[ 'header-text' ] ) )	     	
		    update_post_meta( $post_id, 'header-text', $_POST[ 'header-text' ]  );
			
		if( isset( $_POST[ 'contact-mail' ] ) )	     	
		    update_post_meta( $post_id, 'contact-mail', $_POST[ 'contact-mail' ]  );
	}
}
add_action( 'save_post', 'essential_meta_save' );


