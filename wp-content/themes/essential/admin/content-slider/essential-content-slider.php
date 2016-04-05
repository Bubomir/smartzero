<?php
/**
 * Essential slider
 *
 * @package Essential
 * @since Essential 1.0
 */
class EssentialContentSlider
{
	/**
	 * Constructor: Initializes the plugin
	 */
	function __construct() {
		// i18n init
		/*
			
		*/
		
		add_action('wp_enqueue_scripts', array(&$this, 'slider_enqueue_scripts'));
		add_action( 'custom_head',array(&$this, 'slider_script_iclude'), 10 ); 
		add_filter( 'body_class',array(&$this, 'slider_body_class'), 10 );	
		add_action( 'init', array(&$this, 'init_post_type') );
		add_action('admin_menu',  array(&$this, 'add_slider_meta_box'));
		add_action('admin_menu',  array(&$this, 'add_post_slider_meta_box'));
		add_action( 'save_post', array(&$this, 'add_slider_meta_box_save'));
		
		
		
	} // end constructor
	
	
	/**
	 * Constructor: enqueue scripts
	 */
	public function slider_enqueue_scripts()
	
	{	global  $post;
		if (is_singular()){
			$post_meta = get_post_meta( get_the_ID() );
		}	
		if(is_home() OR ( isset($post_meta['_use_slider'][0] )  && $post_meta['_use_slider'][0] == 'yes' )){
			wp_enqueue_script('jquery');
			wp_enqueue_script( 'wp-parallax-content-slider-modernizr', get_template_directory_uri() . '/admin/content-slider/js/modernizr.custom-2.6.2.js', __FILE__ ) ;
			wp_enqueue_script( 'wp-parallax-content-slider-jgestures', get_template_directory_uri() . '/admin/content-slider/js/jgestures.min.js', __FILE__ ) ;
			wp_enqueue_script( 'wp-parallax-content-slider-jswipe', get_template_directory_uri() . '/admin/content-slider/js/jquery.touchSwipe.min.js', __FILE__ ) ;
			wp_enqueue_script( 'wp-parallax-content-slider-cslider', get_template_directory_uri() . '/admin/content-slider/js/jquery.cslider.js', __FILE__ );
			wp_enqueue_style( 'wp-parallax-content-slider-css', get_template_directory_uri() . '/admin/content-slider/css/style.css' );
			wp_register_script('wp-parallax-content-slider-include', get_template_directory_uri() . '/admin/content-slider/js/slider.js',array( 'jquery' ),false,true);
		}
	}
	
	

	/**
	 * Return the plugin HTML code for output
	 */
	public function get_essenital_content_slider()
	{
			
		
		global $essential_slider_settings,$prefix, $post;
		
		global $essential_title_max_chars, $essential_excerpt_max_chars;
		 
		if (is_singular()){
			$post_meta = get_post_meta( get_the_ID() );
		}
		// Retrieving plugin parameters (user choices or default values)
		if(is_home() OR ( (isset($post_meta['_use_slider'][0]) && $post_meta['_use_slider'][0] == 'yes') AND (isset($post_meta['_use_def'][0]) && $post_meta['_use_def'][0] =='yes')) ){
			
			$essential_slider_settings 			= get_option('thm_settings');
			$prefix = 'thm_';
		} else if ((isset($post_meta['_use_slider'][0]) && $post_meta['_use_slider'][0] == 'yes')  && (is_single() OR is_page())){
			$essential_slider_settings = unserialize($post_meta['_essential_slider_settings'][0]);
			$prefix = '';
			
		} else{
			return FALSE;
		}
		
		
		$essential_slider_type				= (isset($essential_slider_settings[$prefix.'slider-type']) ? $essential_slider_settings[$prefix.'slider-type'] : FALSE );
		$essential_slider_boxed				= (isset($essential_slider_settings[$prefix.'slider-boxed']) && $essential_slider_settings[$prefix.'slider-boxed'] == 'on' ? 'boxed' : '')  ;
		//$essential_slider_theme 			= $essential_slider_settings[$prefix.'slider-theme'];
		$essential_slider_bgincrement 		= (isset($essential_slider_settings[$prefix.'slider-bgincrement'] ) && $essential_slider_settings[$prefix.'slider-bgincrement'] ? $essential_slider_settings[$prefix.'slider-bgincrement'] : '0');
		$essential_slider_autoplay 			= (isset($essential_slider_settings[$prefix.'slider-autoplay']) && $essential_slider_settings[$prefix.'slider-autoplay'] == 'on' ? '1' : '0');
		$essential_slider_interval 			= (isset($essential_slider_settings[$prefix.'slider-interval'] ) && $essential_slider_settings[$prefix.'slider-interval'] ? $essential_slider_settings[$prefix.'slider-interval'] : '5000');
		$essential_slider_nb_articles  		= (isset($essential_slider_settings[$prefix.'slider-ng-articles']) ? $essential_slider_settings[$prefix.'slider-ng-articles'] : FALSE);
		$essential_title_max_chars 			= (isset($essential_slider_settings[$prefix.'slider-title_max_chars']) ? $essential_slider_settings[$prefix.'slider-title_max_chars'] : FALSE);
		$essential_excerpt_max_chars 		= (isset($essential_slider_settings[$prefix.'slider-excerpt_max_chars']) ?  $essential_slider_settings[$prefix.'slider-excerpt_max_chars']: FALSE);
		$essential_sort 					= (isset($essential_slider_settings[$prefix.'slider-sort-by']) ?  $essential_slider_settings[$prefix.'slider-sort-by']: FALSE);
		$essential_order 					= (isset($essential_slider_settings[$prefix.'slider-sort-order-by']) ?  $essential_slider_settings[$prefix.'slider-sort-order-by']: FALSE);
		$essential_slider_categories		= (isset($essential_slider_settings[$prefix.'slider-categories']) ?  $essential_slider_settings[$prefix.'slider-categories']: FALSE);
		$essential_slider_tags				= (isset($essential_slider_settings[$prefix.'slider-tags']) ? $essential_slider_settings[$prefix.'slider-tags'] : FALSE);
		$essential_slider_product_categories= (isset($essential_slider_settings[$prefix.'slider-product-categories']) ? $essential_slider_settings[$prefix.'slider-product-categories']: FALSE);
		$essential_slider_product_tags		= (isset($essential_slider_settings[$prefix.'slider-product-tag']) ? $essential_slider_settings[$prefix.'slider-product-tag']: FALSE);
		$essential_slider_post_type			= (isset($essential_slider_settings[$prefix.'slider-post-type']) ? $essential_slider_settings[$prefix.'slider-post-type']: FALSE);
		$essential_slider_posts				= (isset($essential_slider_settings[$prefix.'slider-post_id']) ? $essential_slider_settings[$prefix.'slider-post_id']: FALSE);
		$essential_custom_query				= (isset($essential_slider_settings[$prefix.'custom-query']) ? $essential_slider_settings[$prefix.'custom-query']: FALSE);
		
		if (is_array($essential_slider_categories)) $essential_slider_categories = implode(',', $essential_slider_categories);
		if(is_string($essential_slider_posts) AND !empty($essential_slider_posts))
			$essential_slider_posts = explode(',', $essential_slider_posts);
		
		wp_localize_script(
			'wp-parallax-content-slider-include', // Script handle,
			'slider', // Name of global js object
			array(
				'bgincrement'	=> $essential_slider_bgincrement,
				'autoplay'		=> $essential_slider_autoplay,
				'interval'		=> $essential_slider_interval,
			) 
		);
		// Then enqueue modified script
		wp_enqueue_script( 'wp-parallax-content-slider-include' );
		
		global $post;
		
		if ($essential_custom_query) {
			$args = $essential_custom_query;;
			
		} else {
		
			$args = array( 'post_type' => 'any',
						   'tax_query' => array(
						   		'relation' => 'AND',
								
							),
						   'suppress_filters'=>0 ); // Added for WPML support
			
			if($essential_slider_post_type){
								$args['post_type'] = $essential_slider_post_type;
								
			} 
			if($essential_slider_posts & !empty($essential_slider_posts)){
								$args['post__in'] = $essential_slider_posts;
								
			}
			if($essential_sort){
								$args['orderby'] = $essential_sort;
								
			}
			if($essential_order){
								$args['order'] = $essential_order;
										
			}
			if($essential_slider_nb_articles){
								$args['numberposts'] = $essential_slider_nb_articles;
										
			}
			if($essential_slider_categories){
								$args['category_name'] = $essential_slider_categories;
										
			}
			if($essential_slider_tags){
								$args['tag_slug__in'] = $essential_slider_tags;
										
			}
			
			
			
			if($essential_slider_product_categories){
								$args['tax_query'][]	=	array(
									'taxonomy' => 'product_cat',
									'field' => 'slug',
									'terms' => $essential_slider_product_categories
								);
			}
			if($essential_slider_product_tags){
								$args['tax_query'][]	=	array(
									'taxonomy' => 'product_tag',
									'field' => 'slug',
									'terms' => $essential_slider_product_tags
								);
			}
		}			   
	   	
		global $myposts;
		//var_dump($args);
		$myposts = get_posts( $args );
		

		/*-----------------------------------------------------------------------------------*/
		/* GHTML Output beginning
		/*-----------------------------------------------------------------------------------*/
		if ($essential_slider_type == 'default'){

			echo "<div id='da-slider' class='da-slider '>\n";
			echo "<div class='$essential_slider_boxed'>";
	
			foreach( $myposts as $post ) :	setup_postdata($post);
				get_template_part( 'slide', $post->post_type );
	
			endforeach; 
			
		
	
			echo '<nav class="da-arrows">	<span class="da-arrows-prev"></span>	<span class="da-arrows-next"></span></nav></div>';
			echo "</div >";	
		} elseif ($essential_slider_type == 'simple'){
			get_template_part( 'simple-slider' );
		}
			
		wp_reset_postdata();	
		
	}

	/*-----------------------------------------------------------------------------------*/
	/* include in custom head
	/*-----------------------------------------------------------------------------------*/
	function slider_script_iclude(){
		if(is_singular()){
			$post_meta = get_post_meta( get_the_ID() );
		}	
		
		if(is_home() OR ( isset($post_meta['_use_slider']) && ($post_meta['_use_slider'][0] == 'yes' AND $post_meta['_use_def'][0] =='yes') )){
			$essential_slider_settings 			= get_option('thm_settings');
			$prefix = 'thm_';
			
		} else if (isset($post_meta['_use_slider']) && $post_meta['_use_slider'][0] == 'yes'){
			$essential_slider_settings = unserialize($post_meta['_essential_slider_settings'][0]);
			$prefix = '';
		} else{
			return FALSE;
		}
		
		
		/*-----------------------------------------------------------------------------------*/
		/* include bg image
		/*-----------------------------------------------------------------------------------*/
		$essential_slider_bgimage = (isset($essential_slider_settings[$prefix.'slider-bg-image']) ? $essential_slider_settings[$prefix.'slider-bg-image'] : FALSE);
		if ( isset($essential_slider_bgimage) && strlen( $essential_slider_bgimage ) ){
					$sliderimagelink =  wp_get_attachment_image_src($essential_slider_bgimage,'full');
					echo  "<style>.da-slider {background-image: url($sliderimagelink[0]);}</style>";	
					
		}
		/*-----------------------------------------------------------------------------------*/
		/* if IE < 10  specific
		/*-----------------------------------------------------------------------------------*/
		echo "<!--[if lt IE 10]><style>.da-slider .da-slide {visibility: hidden;} .da-slider .da-slide.da-slide-current {visibility: visible;} </style><![endif]-->";
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* Add slider class to boddy_class 
	/*-----------------------------------------------------------------------------------*/
	function slider_body_class( $classes ) {
		if(is_singular()){
			$post_meta = get_post_meta( get_the_ID() );
		}	
		if(is_home() OR ( isset($post_meta['_use_slider']) && ($post_meta['_use_slider'][0] == 'yes' AND $post_meta['_use_def'][0] =='yes')) ){
			$essential_slider_settings 			= get_option('thm_settings');
			$prefix = 'thm_';
			
		} else if (isset($post_meta['_use_slider']) && $post_meta['_use_slider'][0] == 'yes'){
			$essential_slider_settings = unserialize($post_meta['_essential_slider_settings'][0]);
			$prefix = '';
		} else{
			return $classes;
		}
		
		if (isset($essential_slider_settings[$prefix.'slider-type'])){
			$essential_slider_type	= $essential_slider_settings[$prefix.'slider-type'];
			$classes[] = 'slider-'.$essential_slider_type;
		}
			
		return $classes;

	} 
	
	/*-----------------------------------------------------------------------------------*/
	/* Add slide custom post type
	/*-----------------------------------------------------------------------------------*/
	public function init_post_type() {
		if ( post_type_exists('slides') )
			return;
			
				register_post_type( "slide",
						array(
							'labels' => array(
									'name' 					=> __( 'Slides', 'essential' ),
									'singular_name' 		=> __( 'Slide', 'essential' ),
									'menu_name'				=> _x( 'Slides', 'Admin menu name', 'essential' ),
									'add_new' 				=> __( 'Add Slide', 'essential' ),
									'add_new_item' 			=> __( 'Add New Slide', 'essential' ),
									'edit' 					=> __( 'Edit', 'essential' ),
									'edit_item' 			=> __( 'Edit Slide', 'essential' ),
									'new_item' 				=> __( 'New Slide', 'essential' ),
									'view' 					=> __( 'View Slide', 'essential' ),
									'view_item' 			=> __( 'View Slide', 'essential' ),
									'search_items' 			=> __( 'Search Slides', 'essential' ),
									'not_found' 			=> __( 'No Slides found', 'essential' ),
									'not_found_in_trash' 	=> __( 'No Slides found in trash', 'essential' ),
									'parent' 				=> __( 'Parent Slide', 'essential' )
								),
							'description' 			=> __( 'This is where you can add new slide.', 'essential' ),
							'public' 				=> true,
							'show_ui' 				=> true,
							'capability_type' 		=> 'post',
							'map_meta_cap'			=> true,
							'publicly_queryable' 	=> false,
							'exclude_from_search' 	=> false,
							'hierarchical' 			=> false, // Hierarchical causes memory issues - WP loads all records!
							'rewrite' 				=> 'slide',
							'query_var' 			=> true,
							'supports' 				=> array( 'title', 'editor',  'thumbnail',  'custom-fields', 'page-attributes' ),
							'has_archive' 			=> false,
							'show_in_nav_menus' 	=> false
						)
				);
				
		
			
	    	

	}
	/*-----------------------------------------------------------------------------------*/
	/* Add slider meta box to slide post type
	/*-----------------------------------------------------------------------------------*/
	function add_slider_meta_box() {
		add_meta_box(
	            'Slder_meta',
	            __( 'Slider options', 'essential' ),
	            array(&$this, 'slider_meta_callback'),
	            'slide'
	        );
	}
	
	function slider_meta_callback( $post ) {
		$stored_meta = get_post_meta( $post->ID );
		$defaulth_image =  get_template_directory_uri().'/images/blank.gif';
		wp_nonce_field( basename( __FILE__ ), 'slider_nonce' );
		if ($stored_meta['essential_header_image'][0]) { 
			$image = wp_get_attachment_image_src($stored_meta['essential_header_image'][0], 'large');
			$image = $image[0]; } 
		else { 
			$image = ''; 
		}
		echo '	<h4>'.__('Button', 'essential').'</h4>
				<p>
			        <label for="meta-text" class="example-row-title">'.__('Button text', 'essential').'</label>
			        <input type="text" name="button-text" id="button-text" value="'.$stored_meta['button-text'][0].'" />
			    </p>
			    <p>
			        <label for="meta-text" class="example-row-title">'.__('Button link', 'essential').'</label>
			        <input type="text" name="button-link" id="button-link" value="'.$stored_meta['button-link'][0].'" />
			    </p>
			    <p>
			    <label for="use-org-image">
		            <input type="checkbox" name="use-org-image" id="use-org-image" value="yes"';
		         checked( $stored_meta['use-org-image'][0], 'yes' );
		   echo      ' />
		            '.__('Use original image', 'essential').'
		        </label>
			    </p>';
		
	    }
	
	function add_slider_meta_box_save( $post_id ) {
	 
	    // Checks save status
	    $is_autosave = wp_is_post_autosave( $post_id );
	    $is_revision = wp_is_post_revision( $post_id );
	    $is_valid_nonce = ( isset( $_POST[ 'slider_nonce' ] ) && wp_verify_nonce( $_POST[ 'slider_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	 
	    // Exits script depending on save status
	    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
	        return;
	    }
	 	
	   if( isset( $_POST[ 'button-text' ] ) ) {
		    update_post_meta( $post_id, 'button-text', $_POST[ 'button-text' ] );
		}
	   if( isset( $_POST[ 'button-link' ] ) ) {
		    update_post_meta( $post_id, 'button-link', $_POST[ 'button-link' ] );
		   
		}
		if( isset( $_POST[ 'use-org-image' ] ) ) {
		    update_post_meta( $post_id, 'use-org-image', 'yes' );
		} else {
		    update_post_meta( $post_id, 'use-org-image', '' );
		}
	    if( isset( $_POST[ '_use_slider' ] ) ) {
		    update_post_meta( $post_id, '_use_slider', $_POST[ '_use_slider' ] );
			
		} else {
			update_post_meta( $post_id, '_use_slider', '' );
		}
		 if( isset( $_POST[ '_use_def' ] ) ) {
		    update_post_meta( $post_id, '_use_def', $_POST[ '_use_def' ] );
		} else {
			update_post_meta( $post_id, '_use_def', '' );
		}
		  if( isset( $_POST[ '_essential_slider_settings' ] ) ) {
			  
		    update_post_meta( $post_id, '_essential_slider_settings', $_POST[ '_essential_slider_settings' ] );
		}
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* Add slider options meta box to slide post, page, product post type
	/*-----------------------------------------------------------------------------------*/
	function add_post_slider_meta_box() {
	 $screens =  apply_filters( 'essential_add_slider_postytpe', array( 'post', 'page' , 'product'));
		 foreach ( $screens as $screen ) {
			add_meta_box(
		            'Slider setup',
		            __( 'Slider options', 'essential' ),
		            array(&$this, 'slider_post_meta_callback'),
		            $screen
		            
		        );
		 }		
	}
	
	function slider_post_meta_callback( $post ) {
		$prefix ='';
		
		wp_enqueue_script('thm-admin-scripts',  get_template_directory_uri() . '/admin/includes/js/admin-scripts.js');
		wp_enqueue_script('media-uploader',  get_template_directory_uri() . '/admin/includes/js/media-uploader.js');
		wp_enqueue_script('jscolor',  get_template_directory_uri() . '/admin/includes/js/jscolor.js');
		wp_enqueue_script('select2-essential',  get_template_directory_uri() . '/admin/includes/js/select2/select2.js');
		wp_enqueue_style('select2', get_template_directory_uri() . '/admin/includes/js/select2/select2.css');
		wp_enqueue_style('matabox-admin', get_template_directory_uri() . '/admin/includes/css/admin-styles.css');
		
		
		$stored_meta = get_post_meta( $post->ID );
		if (isset($stored_meta['_essential_slider_settings'][0]))
			$slider_options = unserialize($stored_meta['_essential_slider_settings'][0]);
		$defaulth_image =  get_template_directory_uri().'/images/blank.gif';
		wp_nonce_field( basename( __FILE__ ), 'slider_nonce' );
		if (isset($stored_meta['essential_header_image'][0]) && $stored_meta['essential_header_image'][0]) { 
			$image = wp_get_attachment_image_src($stored_meta['essential_header_image'][0], 'large');
			$image = $image[0]; } 
		else { 
			$image = ''; 
		}
		$options_fields = array(
				array(
					'id'	=> $prefix.'slider', // Use data in $thm_custom_tabs
					'type'	=> 'tab_start'
				),
				array(
					'label'=> 'Slider type',
					'desc'	=> 'Chose slider type.',
					'id'	=> $prefix.'slider-type',
					'type'	=> 'select',
					'options' => array (
						'default' => array (
							'label' => 'Default',
							'value'	=> 'default'
						),
						'yellow' => array (
							'label' => 'Simple',
							'value'	=> 'simple'
						),
						'dark' => array (
							'label' => 'None',
							'value'	=> 'none'
						)
					)
				),
				array(
					'id'	=> 'slider-type-simple',
					'class'	=> 'slider-type',
					'type'	=> 'table-start'
					),
				array(
					'label'	=> 'Slider image',
					'desc'	=> 'Slider image',
					'id'	=> $prefix.'sipmle-slider-image',
					'type'	=> 'image'
				),
				array(
					'label'=> 'Heading text',
					'desc'	=> '',
					'id'	=> $prefix.'simple-slider-heading-text',
					'type'	=> 'text'
				),
				array(
					'label'=> 'Sub heading text',
					'desc'	=> '',
					'id'	=> $prefix.'simple-slider-sub-heading-text',
					'type'	=> 'text'
				),
				array(
					'label'=> 'Text',
					'desc'	=> 'Slider text',
					'id'	=> $prefix.'simple-slider-text',
					'type'	=> 'textarea'
				),
				array(
					'type'	=> 'table-end'
				),
				array(
					'id'	=> 'slider-type-default',
					'class'	=> 'slider-type',
					'type'	=> 'table-start'
					),
				array(
					'label'=> 'Boxed',
					'desc'	=> 'Full width or boxed.',
					'id'	=> $prefix.'slider-boxed',
					'type'	=> 'checkbox'
				),
				
				array(
					'label'	=> 'Slider background image',
					'desc'	=> 'Slider background image',
					'id'	=> $prefix.'slider-bg-image',
					'type'	=> 'image'
				),
				array(
					'label'=> 'Number of pixels for background increment',
					'desc'	=> 'A negative value will invert the parallax effect',
					'id'	=> $prefix.'slider-bgincrement',
					'type'	=> 'text'
				),
				array(
					'label'=> 'Auto play',
					'desc'	=> 'Activate auto-play.',
					'id'	=> $prefix.'slider-autoplay',
					'type'	=> 'checkbox'
				),
				array(
					'label'=> 'Time between each slide (in ms):',
					'desc'	=> '',
					'id'	=> $prefix.'slider-interval',
					'type'	=> 'text'
				),
				
				array(
					'label'=> 'Number of articles to display:',
					'desc'	=> 'Maximum number of articles to display in the dynamic slider',
					'id'	=> $prefix.'slider-ng-articles',
					'type'	=> 'text'
				),
				array(
					'label'=> 'Slide title max length:',
					'desc'	=> 'Maximum number of characters to display in a dynamic slide title',
					'id'	=> $prefix.'slider-title_max_chars',
					'type'	=> 'text'
				),
				array(
					'label'=> 'Excerpt max length:',
					'desc'	=> 'Maximum number of characters to display in a dynamic slide text, leave blank for default.',
					'id'	=> $prefix.'slider-excerpt_max_chars',
					'type'	=> 'text'
				),
				array(
					'type'	=> 'table-end'
				),
				array(
					'label'=> 'Sort by',
					'desc'	=> 'Choose how do you want to sort the posts in the slider.',
					'id'	=> $prefix.'slider-sort-by',
					'type'	=> 'select',
					'options' => array (
						'date' => array (
							'label' => 'Date',
							'value'	=> 'date'
						),
						'rand' => array (
							'label' => 'Random',
							'value'	=> 'rand'
						),
						'title' => array (
							'label' => 'Title',
							'value'	=> 'title'
						),
						'author' => array (
							'label' => 'Author',
							'value'	=> 'author'
						),
						'comment_count' => array (
							'label' => 'Comment count',
							'value'	=> 'comment_count'
						),
						'modified' => array (
							'label' => 'Last modified date',
							'value'	=> 'modified'
						),
					)
				),
				array(
					'label'=> 'Sort order',
					'desc'	=> 'Choose how do you want to order the posts in the slider.',
					'id'	=> $prefix.'slider-sort-order-by',
					'type'	=> 'select',
					'options' => array (
						'asc' => array (
							'label' => 'Ascending',
							'value'	=> 'asc'
						),
						'desc' => array (
							'label' => 'Descending',
							'value'	=> 'desc'
						)
						
					)
				),
				array(
					'label' => 'Type',
					'desc'	=> 'Choose what post type you want to iclude.',
					'id'	=> $prefix.'slider-post-type',
					'type'	=> 'post_type'
				),
				array(
					'label' => 'Category',
					'id'	=> $prefix.'slider-categories',
					'tax'	=> 'category',
					'type'	=> 'tax_select'
				),
				array(
					'label' => 'Tag',
					'id'	=> $prefix.'slider-tag',
					'tax'	=> 'post_tag',
					'type'	=> 'tax_select'
				),
				array(
					'label' => 'Product Category',
					'id'	=> $prefix.'slider-product-categories',
					'tax'	=> 'product_cat',
					'type'	=> 'tax_select'
				),
				array(
					'label' => 'Product Tags',
					'id'	=> $prefix.'slider-product-tag',
					'tax'	=> 'product_tag',
					'type'	=> 'tax_select'
				),
				array(
					'label' => 'Specific Post',
					'desc' => '',
					'id' 	=>  $prefix.'slider-post_id',
					'type' => 'post_list_ajax',
					'post_type' => array('post','page','product','slide')
				),
				array(
						'label'=> 'Custom Query args',
						'desc'	=> 'Use custom query arguments to grab posts for slides. It will overide all above!',
						'id'	=> $prefix.'custom-query',
						'type'	=> 'textarea'
					),
				
						array(
				'type'	=> 'tab_end'
			),
		)
		
		?>
		<div class="slider-control">
		    <label for="meta-checkbox">
		        <input type="checkbox" name="_use_slider"  value="yes" <?php if(isset($stored_meta['_use_slider'][0])) checked( $stored_meta['_use_slider'][0], 'yes' ); ?> class='use_slider'/>
		        Use slider on this page
		    </label>
		    <br />
		    <label for="meta-checkbox-two">
		        <input type="checkbox" name="_use_def" value="yes" <?php if(isset ( $stored_meta['_use_def'][0]) ) checked( $stored_meta['_use_def'][0], 'yes' ); ?> class='def_slider'/>
		        Use theme default slider settings
		    </label>
        </div>
        
    	<?php
    	echo '<div class="hidden-container'; 
    		if ($stored_meta['_use_slider'][0] == 'yes' &&  $stored_meta['_use_def'][0] != 'yes' )
    	 	echo " show ";
		echo '" >';	
			foreach ($options_fields as $field) {
						
						if(isset($field['id'])){
							if (!isset( $slider_options[$field['id']] ) )
								$slider_options[$field['id']] = FALSE;
						} else {
							$field['id'] ='';
						}		
						
						// Begin a new tab
						if( $field['type'] == 'tab_start') {
							echo '<div class="tab_content" id="'.$field['id'].'">';
							echo '<table class="form-table">';
						}
				
						// begin a table row with
						echo '<tr>';
				
								if( $field['type'] != 'tab_start' && $field['type'] != 'tab_end'  && $field['type'] != 'table-start' && $field['type'] != 'table-end')  {
									if( $field['type'] == 'title') {
										echo '<th colspan="2"><h3 id="thm_settings['.$field['id'].']">'.$field['label'].'</h3></th>';
									} else {
										echo '<th class="'.$field['id'].'"><label for="thm_settings['.$field['id'].']">'.$field['label'].'</label></th>';
									}
								}
								
						if( $field['type'] != 'tab_start' && $field['type'] != 'tab_end') {
								if( $field['type'] == 'table-start') {
									echo	'<td colspan="2" class="sub-table-container">';
									
								} else {
									echo	'<td>';
								}	
								if( isset( $slider_options[$field['id']] ) ) {
									$meta = $slider_options[$field['id']];
								} else {
									$meta = '';
								}
								if( $field['type'] == 'table-start') {
													echo '<table class="'.$field['class'].' form-table" id="'.$field['id'].'">';
													
								}

								switch($field['type']) {
									// text
									case 'text':
										echo '<input type="text" name="_essential_slider_settings['.$field['id'].']" id="_essential_slider_settings['.$field['id'].']" value="'.$meta.'" size="30" class="regular-text" />
											<span class="description">'.$field['desc'].'</span>';
									break;
									// textarea
									case 'textarea':
										echo '<textarea name="_essential_slider_settings['.$field['id'].']" id="_essential_slider_settings['.$field['id'].']" cols="60" rows="4">'.$meta.'</textarea>
											<br /><span class="description">'.$field['desc'].'</span>';
									break;
									
									// checkbox
									case 'checkbox':
										echo '<input type="checkbox" name="_essential_slider_settings['.$field['id'].']" id="_essential_slider_settings['.$field['id'].']" ',$meta != '' ? ' checked="checked"' : '',' />
											<label for="_essential_slider_settings['.$field['id'].']"><span class="description">'.$field['desc'].'</span></label>';
									break;
									// select
									case 'select':
										echo '<select class="chosen-select" name="_essential_slider_settings['.$field['id'].']" id="_essential_slider_settings['.$field['id'].']">';
										foreach ($field['options'] as $option) {
											echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
										}
										echo '</select>&nbsp;<span class="description">'.$field['desc'].'</span>';
									break;
									
									
									// tax_select
									case 'tax_select':
										echo '<select class="chosen-select"  name="_essential_slider_settings['.$field['id'].'][]" id="_essential_slider_settings['.$field['id'].']" multiple>
												<option value="">-- '.__('Select','thm').' --</option>'; // Select One
										$terms = get_terms($field['tax'], 'get=all');
										if(is_array($terms)){
											$terms['-1'] = 'all';
											ksort($terms); 
											//$selected = wp_get_object_terms('', '_essential_slider_settings['.$field['id'].']');
											//var_dump($slider_options[$field['id']]);
											foreach ($terms as $term) {
												if ( in_array($term->slug , $slider_options[$field['id']]) )
													echo '<option value="'.$term->slug.'" selected="selected">'.$term->name.'</option>';
												elseif ($term === 'all' && in_array('-1' , $slider_options[$field['id']]))
													echo '<option value="-1" selected="selected">All</option>';
												elseif ($term === 'all')
													echo '<option value="-1">All</option>';	
												else
													echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
											}
											$taxonomy = get_taxonomy($field['tax']);
											}	
										echo '</select><br /><span class="description"><a href="'.get_site_url().'/wp-admin/edit-tags.php?taxonomy='.$field['tax'].'">'.__('Manage', 'thm').' '.$taxonomy->label.'</a></span>';
									break;
									case 'post_type':
										echo '<select name="_essential_slider_settings['.$field['id'].'][]" id="_essential_slider_settings['.$field['id'].']" multiple class="chosen-select" >
												<option value="">-- '.__('Select','thm').' --</option>'; // Select One
										$types = get_post_types(array('public'   => true));
										if(is_array($types)){
											unset($types['attachment']);
											unset($types['product_variation']);
											unset($types['shop_coupon']);
											$types['0'] = 'All';
											ksort($types); 
											foreach ($types as $key =>$type) {
												if (in_array($type, $slider_options[$field['id']] ))
													echo '<option value="'.$type.'" selected="selected">'.$type.'</option>';
												else
													echo '<option value="'.$type.'">'.$type. '</option>';
											}
										}	
										echo '</select>';
									break; 
									// post_list
									case 'post_list':
										$items = get_posts( array (
											'post_type'	=> $field['post_type'],
											'posts_per_page' => -1
										));
										echo '<select class="chosen-select" name="_essential_slider_settings['.$field['id'].']';
										if ($field['single'] != TRUE) echo '[]'; 
										echo '" id="_essential_slider_settings['.$field['id'].'] "';
										
										if ($field['single'] != TRUE) echo  ' multiple '; 
										echo	'><option value="">-- '.__('Select','thm').' --</option>'; // Select One
											
											foreach($items as $item) {
												if( $item->post_type == 'page' OR $item->post_type == 'post') {
													$post_type = str_replace('page', __('page', 'thm'), $item->post_type);
													$post_type = str_replace('post', __('post', 'thm'), $item->post_type);
												} else { $post_type = $item->post_type; }
												echo '<option value="'.$item->ID.'"', in_array($item->ID, (array)$slider_options[$field['id']])  ? ' selected="selected"' : '','>'.$post_type.': '.$item->post_title.'</option>';
											} // end foreach
										echo '</select>&nbsp;<span class="description">'.$field['desc'].'</span>';
									break;
									case 'post_list_ajax':
										$postids = implode(',', (array)$slider_options[$field['id']]);
										echo '<input type="hidden" name="_essential_slider_settings['.$field['id'].']" class="select2-ajax-multiple" value="'.$postids.'" style="width:185px" data-json="'.htmlspecialchars($this->prospekt_get_post_id_for_ajax($postids)).'"/>';
										echo '&nbsp;<span class="description">'.$field['desc'].'</span>';    
									break;
									// image
									case 'image':
										$def_image =  get_template_directory_uri().'/images/placeholder.png';
										if (isset($slider_options[$field['id']]) && $slider_options[$field['id']] ) { 
											$image = wp_get_attachment_image_src($slider_options[$field['id']], 'medium');
											$image = $image[0]; } 
										else { 
											$image =  get_template_directory_uri().'/images/placeholder.png'; 
										}
										echo '<span class="custom_default_image" style="display:none">'.$def_image.'</span>';
										echo	'<input name="_essential_slider_settings['.$field['id'].']" id="_essential_slider_settings['.$field['id'].']" type="hidden" class="custom_upload_image" value="'.$slider_options[$field['id']].'" />
													<img src="'.$image.'" class="custom_preview_image" alt="" /><br />
														<input class="custom_upload_image_button button" type="button" value="'.__('Choose Image', 'thm').'" />
														<small> <a href="#" class="custom_clear_image_button">'.__('Remove Image', 'thm').'</a></small>
														<br clear="all" /><span class="description">'.$field['desc'].'';
									break;
									
									
									
				
								} //end switch
						}

						if( $field['type'] == 'table-end') {
							echo '</table>';
							}
						echo '</td></tr>';
						
						
						// End a tab
						if( $field['type'] == 'tab_end') {
							echo '</table>';
							echo '</div>';
						}
			}
		echo "</div>";
	    }

		/* ------------------------------------------------------------------*/
		/* Write get post id for ajax post list */
		/* ------------------------------------------------------------------*/
		function prospekt_get_post_id_for_ajax($postids){
				global $wpdb;
				$post_ids = array();
				$rules_post_ids = '';
				if ($postids)
				{
				   		
				    $sql = "
				    SELECT ID, post_type, post_title
				    FROM $wpdb->posts
				    WHERE ID IN ($postids)
				    ORDER BY post_type, post_title";
				    $results = $wpdb->get_results($sql);
				    foreach ($results as $result)
				    {
				        $post_ids[] = array('id' => $result->ID, 'text' => "($result->post_type)  $result->post_title");
				    }
		
				}
				return json_encode($post_ids);
		}


} // end class

$essenital_content_slider = new EssentialContentSlider();

function get_essenital_content_slider( )
{
	global $essenital_content_slider;
	echo $essenital_content_slider->get_essenital_content_slider(  );
}