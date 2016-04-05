<?php
/**
 * Arrays for admin options
 *
 * @package Essential
 * @since Essential 1.0
 */

/* ----------------------------------------
* To retrieve a value use: $thm_options[$prefix.'var']
----------------------------------------- */

$prefix = 'thm_';

/* ----------------------------------------
* Create the TABS
----------------------------------------- */

$thm_custom_tabs = array(
		array(
			'label'=> __('General', 'essential'),
			'id'	=> $prefix.'general'
		),
		array(
			'label'=> __('Home page', 'essential'),
			'id'	=> $prefix.'home'
		),
		array(
			'label'=> __('Content slider', 'essential'),
			'id'	=> $prefix.'slider'
		),
		array(
			'label'=> __('Custom CSS', 'essential'),
			'id'	=> $prefix.'custom_css'
		)
		
	);

/* ----------------------------------------
* Options Field Array
----------------------------------------- */
$thm_custom_meta_fields = array(

	/* -- TAB 1 -- */
	array(
		'id'	=> $prefix.'general', // Use data in $thm_custom_tabs
		'type'	=> 'tab_start'
	),
	
	array(
		'label'=> 'Title',
		'id'	=> $prefix.'title',
		'type'	=> 'title'
	),
	
	array(
		'label'=> 'Site style',
		'desc'	=> 'Chose site style.',
		'id'	=> $prefix.'site-style',
		'type'	=> 'select',
		'options' => array (
			'blue' => array (
				'label' => 'Blue',
				'value'	=> 'blue'
			),
			'dark' => array (
				'label' => 'Black',
				'value'	=> 'black'
			),
			'red' => array (
				'label' => 'Orange',
				'value'	=> 'orange'
			),
			'nosidebar' => array (
				'label' => 'Custom',
				'value'	=> 'custom'
			)
		)
	),
	array(
		'id'	=> 'custom-colors',
		'class'	=> 'hidden-container',
		'type'	=> 'table-start'
	),
	array(
		'label'	=> 'Main color',
		'desc'	=> '',
		'id'	=> $prefix.'main-color',
		'type'	=> 'coloroption'
	),
	array(
		'label'	=> 'Color 2',
		'desc'	=> '',
		'id'	=> $prefix.'color2',
		'type'	=> 'coloroption'
	),
	array(
		'label'	=> 'Color 3',
		'desc'	=> '',
		'id'	=> $prefix.'color3',
		'type'	=> 'coloroption'
	),
	array(
		'label'	=> 'Color 4',
		'desc'	=> '',
		'id'	=> $prefix.'color4',
		'type'	=> 'coloroption'
	),
	array(
		'label'	=> 'Body Color',
		'desc'	=> '',
		'id'	=> $prefix.'body-color',
		'type'	=> 'colorpicker'
	),
	array(
		'label'	=> 'Link Color',
		'desc'	=> '',
		'id'	=> $prefix.'link-color',
		'type'	=> 'colorpicker'
	),
	array(
		'label'	=> 'Text Color',
		'desc'	=> '',
		'id'	=> $prefix.'text-color',
		'type'	=> 'colorpicker'
	),
	array(
		'label'=> 'Post header background',
		'desc'	=> 'Gradient for post/page headers.',
		'id'	=> $prefix.'header-gradient',
		'type'	=> 'gradient'
	),
	array(
		'type'	=> 'table-end',
		'id'	=> 'table-end'
	),
	array(
		'label'	=> 'Main background image',
		'desc'	=> '',
		'id'	=> $prefix.'main-bg-image',
		'type'	=> 'image+color'
	),
	array(
		'label'=> 'Background aligment',
		'desc'	=> 'Chose background aligment',
		'id'	=> $prefix.'background-aligment',
		'type'	=> 'select',
		'options' => array (
			'default' => array (
				'label' => 'Default',
				'value'	=> 'default'
			),
			'strech' => array (
				'label' => 'Strech',
				'value'	=> 'strech'
			),
			'center' => array (
				'label' => 'Center',
				'value'	=> 'center'
			),
			'center' => array (
				'label' => 'Fixed',
				'value'	=> 'fixed'
			),
			'repeat' => array (
				'label' => 'Repeat',
				'value'	=> 'repeat'
			),
			'repeatx' => array (
				'label' => 'Repea-x',
				'value'	=> 'repeax'
			),
			'repeaty' => array (
				'label' => 'Repeat-y',
				'value'	=> 'repeaty'
			)
			
		)
	),
	
	array(
		'label'=> 'Title font',
		'desc'	=> 'Chose font for titles.',
		'id'	=> $prefix.'title-font',
		'type'	=> 'googlefont',
		//'options' => get_available_fonts()
	),
	array(
		'label'=> 'Content font',
		'desc'	=> 'Chose font for content.',
		'id'	=> $prefix.'content-font',
		'type'	=> 'googlefont',
		//'options' => get_available_fonts()
	),
	array (
		'label' => 'How to include  custom css',
		'desc'	=> '',
		'id'	=> $prefix.'include',
		'type'	=> 'radio',
		'options' => array (
			'inline' => array (
				'label' => 'Inline',
				'value'	=> 'inline'
			),
			'file' => array (
				'label' => 'Use file',
				'value'	=> 'file'
			),
			
		)
	),
	array(
		'label'	=> 'Logo image',
		'desc'	=> 'Use logo in header instead Sitename and description',
		'id'	=> $prefix.'logo',
		'type'	=> 'image'
	),
	array(
		'label'	=> 'Favicon image',
		'desc'	=> 'A <a href="http://en.wikipedia.org/wiki/Favicon" target="_blank">favicon</a> is a icon that represents your site',
		'id'	=> $prefix.'favicon',
		'type'	=> 'image',
		'def'	=> get_template_directory_uri() . '/favicon.ico',
	),
	
	array(
		'label'=> 'Footer area text',
		'desc'	=> 'Text for footer use ##year## for display of curent year,  ##blog_name## for gloge name.',
		'id'	=> $prefix.'copyright-text',
		'type'	=> 'wysiswg'
	),
	array(
		'label'=> 'Contact page',
		'desc'	=> 'Please select page for contact page',
		'id'	=> $prefix.'contact-page',
		'type'	=> 'post_list',
		'single'=> FALSE,
		'post_type' => array('page')
	),
	array(
		'label'=> 'Wishlist ',
		'desc'	=> 'Please select page for wishlist',
		'id'	=> $prefix.'wishlist-page',
		'type'	=> 'post_list',
		'single'=> TRUE,
		'post_type' => array('page')
	),
	array(
		'label'=> 'Compare page ',
		'desc'	=> 'Please select page for compare',
		'id'	=> $prefix.'compare-page',
		'type'	=> 'post_list',
		'single'=> TRUE,
		'post_type' => array('page')
	),
	array(
		'label'=> 'Sidebar position',
		'desc'	=> 'Chose defaulth sidebar position type.',
		'id'	=> $prefix.'sidebar-position',
		'type'	=> 'select',
		'options' => array (
			'left' => array (
				'label' => 'Left',
				'value'	=> 'layout-left'
			),
			'right' => array (
				'label' => 'Right',
				'value'	=> 'layout-right'
			),
			'nosidebar' => array (
				'label' => 'No sidebar',
				'value'	=> 'layout-full'
			)
		)
	),
	
	array(
		'type'	=> 'tab_end',
		'id'	=> 'tab_end'
	),
	/* -- /TAB 1 -- */
	
	/* -- TAB 2 -- */
	array(
		'id'	=> $prefix.'home', // Use data in $thm_custom_tabs
		'type'	=> 'tab_start'
	),
	array(
		'label'=> 'Home header text',
		'desc'	=> 'A description for the field.',
		'id'	=> $prefix.'home-header-text',
		'type'	=> 'wysiswg'
	),
	array(
		'label' => 'Product Category',
		'id'	=> $prefix.'home-product-categories',
		'tax'	=> 'product_cat',
		'type'	=> 'tax_select_sortable'
	),
	array(
		'label'=> 'Use tabs',
		'desc'	=> 'Use tabs on home page.',
		'id'	=> $prefix.'home-tabs',
		'type'	=> 'checkbox'
	),
	array(
		'label'=> 'Active tab',
		'desc'	=> 'Chose active tab on load.',
		'id'	=> $prefix.'active-tab',
		'type'	=> 'select',
		'options' => array (
			'featured' => array (
				'label' => 'Featured products',
				'value'	=> 'featured'
			),
			'new' => array (
				'label' => 'New products',
				'value'	=> 'new'
			),
			'best' => array (
				'label' => 'Best Sell products',
				'value'	=> 'best'
			),
			'sale' => array (
				'label' => 'Sale products',
				'value'	=> 'sale'
			),
			'auctions' => array (
				'label' => 'Auctions',
				'value'	=> 'auctions'
			)
		)
	),
	array(
		'label'	=> 'Sort items on home page',
		'desc'	=> 'Sort items on home page',
		'id'	=> $prefix.'sort-home',
		'type'	=> 'sortable',
		'options' => array (
			'featured' => array (
				'label' => 'Featured products',
				'value'	=> 'featured'
			),
			'new' => array (
				'label' => 'New products',
				'value'	=> 'new'
			),
			'best' => array (
				'label' => 'Best Sell products',
				'value'	=> 'best'
			),
			'sale' => array (
				'label' => 'Sale products',
				'value'	=> 'sale'
			),
			'auctions' => array (
				'label' => 'Auctions',
				'value'	=> 'auctions'
			)
		)
	),
	array(
		'label' => 'Number of featured products',
		'desc'	=> 'Number of featured products on home page 0 for show none',
		'id'	=> $prefix.'home-featured-products',
		'type'	=> 'text'
	),
	array(
		'label' => 'Number of new products',
		'desc'	=> 'Number of new products on home page 0 for show none',
		'id'	=> $prefix.'home-new-products',
		'type'	=> 'text'
	),
	array(
		'label' => 'Number of best sell products',
		'desc'	=> 'Number of best sell products on home page 0 for show none',
		'id'	=> $prefix.'home-best-products',
		'type'	=> 'text'
	),
	array(
		'label' => 'Number of on sale products',
		'desc'	=> 'Number of on sale products on home page 0 for show none',
		'id'	=> $prefix.'home-sale-products',
		'type'	=> 'text'
	),
	array(
		'label' => 'Number of Auctions',
		'desc'	=> 'Number of auctions on home page 0 for show none',
		'id'	=> $prefix.'home-auctions-products',
		'type'	=> 'text'
	),
	
	array(
		'label' => 'Number of posts',
		'desc'	=> 'Number of on posts on home page 0 for show none',
		'id'	=> $prefix.'home-posts',
		'type'	=> 'text'
	),
	array(
		'label'=> 'Home footer heading',
		'desc'	=> 'A heading for home footer.',
		'id'	=> $prefix.'home-footer-head',
		'type'	=> 'text'
	),
	array(
		'label'=> 'Home footer link',
		'desc'	=> 'A link for home footer.',
		'id'	=> $prefix.'home-footer-link',
		'type'	=> 'text'
	),array(
		'label'=> 'Home footer text',
		'desc'	=> 'A text for home footer.',
		'id'	=> $prefix.'home-footer-text',
		'type'	=> 'wysiswg'
	),array(
		'label'	=> 'Home footer Image',
		'desc'	=> 'A description for the field.',
		'id'	=> $prefix.'home-footer-image',
		'type'	=> 'image'
	),
	
	array(
		'type'	=> 'tab_end',
		'id'	=> 'tab_end'
		
	),
	
	
	/* -- TAB 3 slider -- */
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
		'type'	=> 'table-end',
		'id'	=> 'table-end'
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
		'type'	=> 'table-end',
		'id'	=> 'table-end'
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
		'label' => 'Post type',
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
		'single'=> FALSE,	
		'post_type' => array('post','page','product','slide')
	),
	array(
		'label'=> 'Custom Query args',
		'desc'	=> 'Use custom query arguments to grab posts for slides. It will overide all above!',
		'id'	=> 'slider_defaulth_custom-query',
		'type'	=> 'textarea'
	),
	
	array(
		'type'	=> 'tab_end',
		'id'	=> 'tab_end'
	),
	/* -- /TAB 2 -- */
	/* -- TAB 4 -- */
	array(
		'id'	=> $prefix.'custom_css', // Use data in $thm_custom_tabs
		'type'	=> 'tab_start'
		),
	array(
		'label'=> 'Custom css',
		'desc'	=> '',
		'id'	=> $prefix.'custom-css',
		'type'	=> 'big_textarea'
	),	
		
	array(
		'type'	=> 'tab_end',
		'id'	=> 'tab_end'
	)	
	/* -- /TAB 4 -- */

);