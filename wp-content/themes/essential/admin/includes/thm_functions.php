<?php
/**
 * Function used in theme-option.php
 *
 * @package Essential
 * @since Essential 1.0
 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit; 

/* ------------------------------------------------------------------*/
/* ADD CUSTOM SCRIPTS FOR JQUERY UI */
/* ------------------------------------------------------------------*/

function thm_add_custom_scripts() {
	global $thm_custom_meta_fields, $thm_options;

	// Date Picker
	$output = '<script type="text/javascript">
				jQuery(function() {';

	foreach ($thm_custom_meta_fields as $field) { // loop through the fields looking for certain types
		if($field['type'] == 'date')
			$output .= 'jQuery(".datepicker").datepicker();';
			
		// Slider
		if ($field['type'] == 'slider') {
			$field_id = $field['id'];
			$value = $thm_options["$field_id"] != '' ? $thm_options["$field_id"] : '0';
			
			$output .= '
					jQuery( "#'.$field['id'].'-slider" ).slider({
						value: '.$value.',
						min: '.$field['min'].',
						max: '.$field['max'].',
						step: '.$field['step'].',
						slide: function( event, ui ) {
							jQuery( "#thm_val_slider_'.$field['id'].'" ).val( ui.value );
						}
					});';
		}
	}

	
	$output .= '
		
		});
					
				
		</script>';
		
	echo $output;
	
}

add_action('admin_head','thm_add_custom_scripts');

/* ------------------------------------------------------------------*/
/* CREATE THE FIELDS AND DISPLAY THEM */
/* ------------------------------------------------------------------*/

function thm_show_custom_tabs() {
	
	global $thm_custom_tabs;
	
	echo '<h2 class="nav-tab-wrapper">';
	foreach ($thm_custom_tabs as $tab) {
		echo '<a href="#'.$tab['id'].'" class="nav-tab">'.$tab['label'].'</a>';
	}
	echo '</h2>';
}

/* ------------------------------------------------------------------*/
/* CREATE THE FIELDS AND DISPLAY THEM */
/* ------------------------------------------------------------------*/

function thm_show_custom_fields() {

	global $thm_custom_meta_fields;
	$prefix = 'thm_';
	$wp_editor_arg = array( 
								'media_buttons' => FALSE,
								'teeny'			=> TRUE,
								'wpautop'		=> FALSE
								);
	global $fontsArray;	
	
	// Use nonce for verification
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Begin the field table and loop
	echo '<div id="tab_container">';

	$thm_options = get_option('thm_settings');
	
	foreach ($thm_custom_meta_fields as $field) {
		
		// get value of this field if it exists for this post
		
		if (!isset($thm_options[$field['id']]))
				$thm_options[$field['id']] = FALSE;
		if (!isset($thm_options[$field['id'].'-bg']))
				$thm_options[$field['id'].'-bg'] = FALSE;
		if (!isset($thm_options[$field['id'].'-font']))
				$thm_options[$field['id'].'-font'] = FALSE;
		if (!isset($thm_options[$field['id'].'-gradient-css']))
				$thm_options[$field['id'].'-gradient-css'] = FALSE;
		if (!isset($thm_options[$field['id'].'-gradient-string']))
				$thm_options[$field['id'].'-gradient-string'] = FALSE;
		// Begin a new tab
		if( $field['type'] == 'tab_start') {
			echo '<div class="tab_content" id="'.$field['id'].'">';
			echo '<table class="form-table">';
		}
		// begin table
		

		// begin a table row with
		echo '<tr class="'.$field['id'].'">';

				if( $field['type'] != 'tab_start' && $field['type'] != 'tab_end'  && $field['type'] != 'table-start' && $field['type'] != 'table-end')  {
					if( $field['type'] == 'title') {
						echo '<th colspan="2"><h3 id="thm_settings['.$field['id'].']">'.$field['label'].'</h3></th>';
					} else {
						echo '<th class="'.$field['id'].'"><label for="thm_settings['.$field['id'].']">'.$field['label'].'</label></th>';
					}
				}
				
		if( $field['type'] != 'tab_start' && $field['type'] != 'tab_end' ) {
				if( $field['type'] == 'table-start') {
					echo	'<td colspan="2" class="sub-table-container">';
				} else {
					echo	'<td>';
				}	
				if( isset( $thm_options[$field['id']] ) ) {
					$meta = $thm_options[$field['id']];
				} else {
					$meta = '';
				}
				if( $field['type'] == 'table-start') {
					echo '<table class="'.$field['class'].' form-table" id="'.$field['id'].'">';
					
				}

				switch($field['type']) {
					// text
					case 'text':
						echo '<input type="text" name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']" value="'.$meta.'" size="30" class="regular-text" />
							<span class="description">'.$field['desc'].'</span>';
					break;
					// text
					case 'password':
						echo '<input type="password" name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']" value="'.$meta.'" size="30" class="regular-text" />
							<span class="description">'.$field['desc'].'</span>';
					break;
					// textarea
					case 'textarea':
						echo '<textarea name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']" cols="60" rows="4">'.$meta.'</textarea>
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					
					case 'wysiswg':
						wp_editor( $meta , "thm_settings[".$field['id']."]", $wp_editor_arg); 
						echo '<br /><span class="description">'.$field['desc'].'</span>';
					break;
					
					// textarea
					case 'big_textarea':
						echo '<textarea name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']"  style="width:100%; height:600px;">'.$meta.'</textarea>
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					// checkbox
					case 'checkbox':
						echo '<input type="checkbox" name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']" ',$meta != '' ? ' checked="checked"' : '',' />
							<label for="thm_settings['.$field['id'].']"><span class="description">'.$field['desc'].'</span></label>';
					break;
					// select
					case 'select':
						echo '<select class="chosen-select" name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']">';
						foreach ($field['options'] as $option) {
							echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
						}
						echo '</select>&nbsp;<span class="description">'.$field['desc'].'</span>';
					break;
					// radio
					case 'radio':
						if ($field['id'] = "thm_include"){
							global $custom_css_file;
							
							if (!file_exists($custom_css_file) OR !is_writable($custom_css_file))
								$meta = 'inline';
							foreach ( $field['options'] as $option ) {
								
								if($option ['value']== 'file'){
									if (!file_exists($custom_css_file) OR !is_writable($custom_css_file)){
											echo '<input type="radio" name="thm_settings['.$field['id'].']" id="thm_settings['.$option['value'].']"  disabled="disabled" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
											<label for="'.$option['value'].'">'.$option['label'].' <span class="error">('.__( get_template_directory_uri()."/css/custom.css file doesn't existis or it isn't writable").')</span></label><br />';
										} else {
											echo '<input type="radio" name="thm_settings['.$field['id'].']" id="thm_settings['.$option['value'].']" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
											<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
										}
											
								} else {
										echo '<input type="radio" name="thm_settings['.$field['id'].']" id="thm_settings['.$option['value'].']" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
											<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
								}		
							}
							if (!file_exists($custom_css_file) && !is_writable($custom_css_file));
						} else {
							foreach ( $field['options'] as $option ) {
								echo '<input type="radio" name="thm_settings['.$field['id'].']" id="thm_settings['.$option['value'].']" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
										<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
							}
						}
						echo '<span class="description">'.$field['desc'].'</span>';
					break;
					// checkbox_group
					case 'checkbox_group':
						foreach ($field['options'] as $option) {
							echo '<input type="checkbox" value="'.$option['value'].'" name="thm_settings['.$field['id'].'][]" id="thm_settings['.$option['value'].']"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' />
									<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
						}
						echo '<span class="description">'.$field['desc'].'</span>';
					break;
					// tax_select
					case 'tax_select':
						echo '<select class="chosen-select"  name="thm_settings['.$field['id'].'][]" id="thm_settings['.$field['id'].']" multiple>
								<option value="">-- '.__('Select','thm').' --</option>'; // Select One
						$terms = get_terms($field['tax'], 'get=all');
						if(is_array($terms)){
							$terms['-1'] = 'all';
							ksort($terms); 
							//$selected = wp_get_object_terms('', 'thm_settings['.$field['id'].']');
							foreach ($terms as $term) {
								if ( is_array($thm_options[$field['id']]) && in_array($term->slug , $thm_options[$field['id']]) )
									echo '<option value="'.$term->slug.'" selected="selected">'.$term->name.'</option>';
								elseif ($term === 'all' && is_array($thm_options[$field['id']]) && in_array('-1' , $thm_options[$field['id']]))
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
					// tax_select_sortable
					case 'tax_select_sortable':
						
						
						$terms = get_terms($field['tax'], 'get=all');
						if(is_array($terms)){
							$terms_joson['all'] = 'all';
							ksort($terms); 
							
							foreach ($terms as $term) {
							 $terms_joson[$term->slug ] = $term->name;
							}
							$taxonomy = get_taxonomy($field['tax']);
						        
						    $termsjoson =  json_encode($terms_joson);
							$termsjoson = str_replace("'", "", $termsjoson);
						
						}
						//echo "</select>";
						$value = (is_array($thm_options[$field['id']])) ?  implode(',', $thm_options[$field['id']] ) : $thm_options[$field['id']];
						echo'<input type="hidden" name="thm_settings['.$field['id'].'] id="thm_options['.$field['id'].']" style="width:300px;" value="'.$value.'" data-terms=\''.$termsjoson.'\' class="tax_sotable"/>';
              			
							
						echo '<br /><span class="description"><a href="'.get_site_url().'/wp-admin/edit-tags.php?taxonomy='.$field['tax'].'">'.__('Manage', 'thm').' '.$taxonomy->label.'</a></span>';
						
						
					break;
					case 'post_type':
						echo '<select name="thm_settings['.$field['id'].'][]" id="thm_settings['.$field['id'].']" multiple class="chosen-select" >
								<option value="">-- '.__('Select','thm').' --</option>'; // Select One
						$types = get_post_types(array('public'   => true));
						if(is_array($types)){
							unset($types['attachment']);
							unset($types['product_variation']);
							unset($types['shop_coupon']);
							$types['0'] = 'All';
							ksort($types); 
							foreach ($types as $key =>$type) {
								if (is_array( $thm_options[$field['id']]) AND in_array($type, $thm_options[$field['id']] ))
									echo '<option value="'.$type.'" selected="selected">'.$type.'</option>';
								else
									echo '<option value="'.$type.'">'.$type. '</option>';
							}
							
						}	
						echo '</select><br />';
					break; 
					// post_list
					case 'post_list':
						$items = get_posts( array (
							'post_type'	=> $field['post_type'],
							'posts_per_page' => -1
						));
						echo '<select class="chosen-select" name="thm_settings['.$field['id'].']';
						if (isset($field['single']) && $field['single'] != TRUE) echo '[]'; 
						echo '" id="thm_settings['.$field['id'].'] "';
						
						if (isset($field['single']) && $field['single'] != TRUE) echo  ' multiple '; 
						echo	'><option value="">-- '.__('Select','thm').' --</option>'; // Select One
							
							foreach($items as $item) {
								if( $item->post_type == 'page' OR $item->post_type == 'post') {
									$post_type = str_replace('page', __('page', 'thm'), $item->post_type);
									$post_type = str_replace('post', __('post', 'thm'), $item->post_type);
								} else { $post_type = $item->post_type; }
								echo '<option value="'.$item->ID.'"', in_array($item->ID, (array)$thm_options[$field['id']])  ? ' selected="selected"' : '','>'.$post_type.': '.$item->post_title.'</option>';
							} // end foreach
						echo '</select>&nbsp;<span class="description">'.$field['desc'].'</span>';
						
					break;
					// ajax postlis 
					case 'post_list_ajax':
						$postids = implode(',', (array)$thm_options[$field['id']]);
						echo '<input type="hidden" name="thm_settings['.$field['id'].']" class="select2-ajax-multiple" value="'.$postids.'" style="width:185px" data-json="'.htmlspecialchars (prospekt_get_post_id_for_ajax($postids)).'"/>';
						echo '&nbsp;<span class="description">'.$field['desc'].'</span>';
					break; 		    
					// date
					case 'date':
						
						echo '<input type="text" class="datepicker" name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']" value="'.$thm_options[$field['id']].'" size="30" />
								<span class="description">'.$field['desc'].'</span>';
						 	
					break;
					// image
					case 'image':
						if (isset($field['def'])){
							$def_image = $field['def'];
						} else {
							$def_image =  get_template_directory_uri().'/images/placeholder.png';
						}
						if ($thm_options[$field['id']]) { 
							$image = wp_get_attachment_image_src($thm_options[$field['id']], 'medium');
							$image = $image[0]; } 
						else {
							if (isset($field['def'])){
								$image = $field['def'];
							} else {
								$image =  get_template_directory_uri().'/images/placeholder.png';
							} 
						}
						echo '<span class="custom_default_image" style="display:none">'.$def_image.'</span>';
						echo	'<input name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']" type="hidden" class="custom_upload_image" value="'.$thm_options[$field['id']].'" />
									<img src="'.$image.'" class="custom_preview_image" alt="" max-width="300px" height="auto" /><br />
										<input class="custom_upload_image_button button" type="button" value="'.__('Choose Image', 'thm').'" />
										<small> <a href="#" class="custom_clear_image_button">'.__('Remove Image', 'thm').'</a></small>
										<br clear="all" /><span class="description">'.$field['desc'].'</span>';
					break;
					case 'image+color':
						$def_image =  get_template_directory_uri().'/images/placeholder.png';
						if ($thm_options[$field['id']]) { 
							$image = wp_get_attachment_image_src($thm_options[$field['id']], 'medium');
							$image = $image[0]; } 
						else { 
							$image =  get_template_directory_uri().'/images/placeholder.png'; 
						}
						echo '<table><tr>
									<td>'.__('Background image', 'essential').'</td>
									<td>'.__('Font color', 'essential').'</td>
								</tr>
								<tr><td><span class="custom_default_image" style="display:none">'.$def_image.'</span>';
						echo	'<input name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']" type="hidden" class="custom_upload_image" value="'.$thm_options[$field['id']].'" />
									<img src="'.$image.'" class="custom_preview_image" alt="" /><br />
										<input class="custom_upload_image_button button" type="button" value="'.__('Choose Image', 'thm').'" />
										<small> <a href="#" class="custom_clear_image_button">'.__('Remove Image', 'thm').'</a></small>
										</td>
										<td><input type="text" class="color" name="thm_settings['.$field['id'].'-font]" id="thm_settings['.$field['id'].'-font]" value="'.$thm_options[$field['id'].'-font'].'" size="30" /></td>
										</table>
										<br clear="all" /><span class="description">'.$field['desc'].'</span>'
										;
					break;
					// slider
					case 'slider':
					$field_id = $field['id'];
					$value = $thm_options["$field_id"] != '' ? $thm_options["$field_id"] : '0';
						echo '<div id="'.$field['id'].'-slider"></div>
								<input type="text" name="thm_settings['.$field['id'].']" id="thm_val_slider_'.$field['id'].'" value="'.$value.'" size="5" />
								<br /><span class="description">'.$field['desc'].'</span>';
					break;
					// repeatable
					case 'repeatable':
						echo '
								<ul id="thm_settings['.$field['id'].']-repeatable" class="custom_repeatable">';
						$i = 0;

						if ( $thm_options[$field['id']] ) {
							foreach($thm_options[$field['id']] as $row) {
								echo '<li><span class="sort hndle"><img src="' . get_template_directory_uri() . '/admin/includes/images/cursor_move.png" /></span>
											<input type="text" name="thm_settings['.$field['id'].']['.$i.']" id="thm_settings['.$field['id'].']" value="'.$row.'" size="30" />
											<a class="repeatable-remove button" href="#">'.__('Delete','thm').'</a></li>';
								$i++;
							}
						} else {
							echo '<li><span class="sort hndle">|||</span>
										<input type="text" name="thm_settings['.$field['id'].']['.$i.']" id="thm_settings['.$field['id'].']" value="" size="30" />
										<a class="repeatable-remove button" href="#">'.__('Delete','thm').'</a></li>';
						}
						echo '</ul>';
						echo '<a class="repeatable-add button" href="#">'.__('Add','thm').'</a>';
						echo '<br /><span class="description">'.$field['desc'].'</span>';
						
					break;
					//sortable
					case 'sortable':
						$sort_order = explode(',', $meta);
						if(is_array($sort_order)){
							$ordered = array();
						    foreach($sort_order as $key) {
						    	$key = str_replace($field['id'].'_', '', $key);
						    	if(array_key_exists( $key,$field['options'])) {
						    		$ordered[$key] = $field['options'][$key];
						    		unset($field['options'][$key]);
						    	}
						    }
							$field['options'] = array_merge($field['options'],$ordered);
						}
						echo '<ul id="thm_settings['.$field['id'].']" class="ui-sortable">';
						foreach ($field['options'] as $key => $option) {
							echo '<li class="ui-state-default" id="'.$field['id'].'_'.$key.'"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.$option['label'].'</li>';
						}
						echo '</ul>';
						echo '<input type="hidden" name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']" value="'.$meta.'" >';
				    break;
					// colorpicker
					case 'colorpicker':
						echo '<input type="text" class="color" name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']" value="'.$thm_options[$field['id']].'" size="30" />
								<br /><span class="description">'.$field['desc'].'</span>';
						break;
					
					case 'coloroption':
						echo '<table>
								<tr>
									<th>'.__('Background color', 'essential').'</th>
									<th>'.__('Font color', 'essential').'</th>
								</tr>
								<tr>
									<td><input type="text" class="color" name="thm_settings['.$field['id'].'-bg]" id="thm_settings['.$field['id'].'-bg]" value="'.$thm_options[$field['id'].'-bg'].'" size="30" /></td>
									<td><input type="text" class="color" name="thm_settings['.$field['id'].'-font]" id="thm_settings['.$field['id'].'-font]" value="'.$thm_options[$field['id'].'-font'].'" size="30" /></td>
								<tr>
							</table>	
								
								<span class="description">'.$field['desc'].'</span>
								';
						break;	
					case 'googlefont':
						echo '<select name="thm_settings['.$field['id'].']" id="thm_settings['.$field['id'].']" class="chosen-select">';
						foreach ($fontsArray['items'] as $font) {
							echo '<option', $meta == $font['family'] ? ' selected="selected"' : '', ' value="'.$font['family'].'">'.$font['family'].'</option>';
						}
						echo '</select>&nbsp;<span class="description">'.$field['desc'].'</span>';
						break;
					case 'gradient':
						echo '	<table>
								<tr>
									<th>'.__('Background gradient', 'essential').'</th>
									<th>'.__('Font color', 'essential').'</th>
								</tr>
								<tr>
									<td>
										<div id="target-'.$field['id'].'" class="target"></div>
										<div id="'.$field['id'].'" class="gradient1" ></div>
										<input type="hidden"  name="thm_settings['.$field['id'].'-gradient-css]" id="input-'.$field['id'].'-css" value="'.$thm_options[$field['id'].'-gradient-css'].'" size="30" />
										<input type="hidden"  name="thm_settings['.$field['id'].'-gradient-string]" id="input-'.$field['id'].'-string" value="'.$thm_options[$field['id'].'-gradient-string'].'" size="30" />
										<script type="text/javascript">
											jQuery(document).ready(function($) {
												$("#'.$field['id'].'").ClassyGradient({
											 	orientation : "vertical",
											 	target: "#target-'.$field['id'].'",';
											if(isset($thm_options[$field['id'].'-gradient-string']) && strlen($thm_options[$field['id'].'-gradient-string']))		
												echo 'gradient: "'.$thm_options[$field['id'].'-gradient-string'].'",';
											echo 'onChange: function(stringGradient,cssGradient) {
										            $("#input-'.$field['id'].'-css").val(cssGradient);
													$("#input-'.$field['id'].'-string").val(stringGradient);
										        } 
											 	});
											});	 
										</script>	
									</td>
									<td><input type="text" class="color" name="thm_settings['.$field['id'].'-font]" id="thm_settings['.$field['id'].'-font]" value="'.$thm_options[$field['id'].'-font'].'" size="30" /></td>
								</tr>
								</table>	
								<br /><span class="description">'.$field['desc'].'</span>';
						break;		

				} //end switch
		}
		if( $field['type'] == 'table-end') {
			echo '</table>';
			
		}
		echo '</td></tr>';
		
		//end table
		
		
		// End a tab
		if( $field['type'] == 'tab_end') {
			echo '</table>';
			echo '</div>';
		}
		
	} // end foreach
	
	
	
	echo '</div>'; // End Div tab container
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
		        $post_ids[] = array('id' => $result->ID, 'text' => "($result->post_type) $result->post_title");
		    }

		}
		return json_encode($post_ids);
}

/* ------------------------------------------------------------------*/
/* Write css to file */
/* ------------------------------------------------------------------*/
function thm_save_css_file() {
	global $custom_css_file;
	
	$output = '';$filecontent='';
	
	if (!file_exists($custom_css_file) OR !is_writable($custom_css_file))
		return FALSE;
	$thm_option 				= get_option('thm_settings') ;
		
		if ( isset($thm_option['thm_include']) && $thm_option['thm_include'] == 'file') {
			if ($thm_option['thm_site-style'] == 'custom'){
				
				$maincolor 				= (isset($thm_option['thm_main-color-bg']) 		? $thm_option['thm_main-color-bg'] :FALSE) ;
				$maincolor_fc 			= (isset($thm_option['thm_main-color-font']) 	? $thm_option['thm_main-color-font'] :FALSE) ;
				$color2 				= (isset($thm_option['thm_color2-bg'] ) 		? $thm_option['thm_color2-bg'] :FALSE);
				$color2_fc 				= (isset($thm_option['thm_color2-font'] ) 		? $thm_option['thm_color2-font']:FALSE);
				$color3 				= (isset($thm_option['thm_color3-bg'] ) 		? $thm_option['thm_color3-bg'] :FALSE);
				$color3_fc 				= (isset($thm_option['thm_color3-font'] ) 		? $thm_option['thm_color3-font'] :FALSE);
				$color4 				= (isset($thm_option['thm_color4-bg'] ) 		? $thm_option['thm_color4-bg']:FALSE);
				$color4_fc 				= (isset($thm_option['thm_color4-font']) 		? $thm_option['thm_color4-font']:FALSE) ;
				$bodycolor 				= (isset($thm_option['thm_body-color'] ) 		? $thm_option['thm_body-color']:FALSE);
				$linkcolor 				= (isset($thm_option['thm_link-color']) 		? $thm_option['thm_link-color']:FALSE) ;
				$textcolor 				= (isset($thm_option['thm_text-color'] ) 		? $thm_option['thm_text-color']  :FALSE);
				$headergradient			= (isset($thm_option['thm_header-gradient-gradient-css']) ? $thm_option['thm_header-gradient-gradient-css'] :FALSE) ;
				$header_fc				= (isset($thm_option['thm_header-gradient-font']) ? $thm_option['thm_header-gradient-font']:FALSE) ;
				}

				$mainbgimage			= (isset($thm_option['thm_main-bg-image']) ? $thm_option['thm_main-bg-image'] :FALSE);
				$mainbgimage_fc			= (isset($thm_option['thm_main-bg-image-font'] ) ? $thm_option['thm_main-bg-image-font']:FALSE);
				
				$custom_css				= (isset($thm_option['thm_custom-css'] ) ? $thm_option['thm_custom-css'] :FALSE);
				$background_aligment	= (isset($thm_option['thm_background-aligment'] ) ? $thm_option['thm_background-aligment'] :FALSE);
	
		
			if ( isset($maincolor) && strlen( $maincolor ) ){
						$output .= " 
						.woocommerce div.product .product_title,
						.woocommerce #content div.product .product_title,
						.woocommerce-page div.product .product_title,
						.woocommerce-page #content div.product .product_title,
						.woocommerce ul.products li.product h3, 
						.woocommerce-page ul.products li.product h3,
						#primary-menu ul > li li:hover > a,
						#tab-description h2,
						.upper-footer .widget-title,
						#primary-menu a{color:#$maincolor} 
						
						#primary-menu .megamenu > ul > li > a , 
						#topmenu ul > li li:hover, #primary-menu ul > li li:hover > a,
						.woocommerce div.product form.cart .button, 
						.woocommerce #content div.product form.cart .button, 
						.woocommerce-page div.product form.cart .button, 
						.woocommerce-page #content div.product form.cart .button,
						.woocommerce .widget_price_filter .price_slider_amount .button,
						.woocommerce-page .widget_price_filter 
						.price_slider_amount .button,
						.woocommerce a.button, 
						.woocommerce button.button, 
						.woocommerce input.button, 
						.woocommerce #respond input#submit, 
						.woocommerce #content input.button, 
						.woocommerce-page a.button, 
						.woocommerce-page button.button, 
						.woocommerce-page input.button, 
						.woocommerce-page #respond input#submit, 
						.woocommerce-page #content input.button,
						.woocommerce span.onsale,
						.woocommerce-page span.onsale,
						.woocommerce span.winning, .woocommerce-page span.winning,
						.main-color {background-color:#$maincolor; color:#$maincolor_fc}
						
						#topmenu ul > li li:hover a, #topmenu ul > li li:hover h3 {color:#$maincolor_fc}
						
						footer .upper-footer .widget > ul > li  {border-bottom-color: #$maincolor;}  
						";
						
						
						
			}
			if ( isset($color2) && strlen( $color2 ) ){
						$output .= "
						.tab_navigation li a,
						.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
						.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,
						.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active,
						.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active {color:#$color2} 
						.related.products.carusel h2,
						.tab_navigation li.active a,
						.woocommerce .summary div[itemprop='offers'],
						#primary-menu .megamenu > ul > li > a:hover,
						.no-tab h2.title span,
						.title-area select,
						#sidebar .widget select, 
						#compare_table .image .price ,
						.da-slide .da-img span.price, 
						#topmenu li.essential-cart .qc-buttons,
						.variations_form .single_variation,
						.sbHolder, .sbSelector:link, .sbSelector:visited, .sbSelector:hover, .sbOptions, #sidebar .widget a.sbSelector,
						.variations select,
						.bg-color2 {background-color:#$color2; color:#$color2_fc} 
						#primary-menu .sub-menu > li:hover > a, #primary-menu .megamenu > ul > li > a{color:#$color2_fc	}
						.tab_navigation,
						.no-tab h2.title,
						 #primary-menu > ul > li > ul, #primary-menu .megamenu > ul > li > a { border-bottom-color: #$color2;} ";
						
			}
			if ( isset($color3) && strlen( $color3 ) ){
						$output .= "
						.action-buttons a > span a,
						.contact-box-wrapper h3, h3, .color3 {color:#$color3} 
						.woocommerce ul.products li.product .price,
						.widget.woocommerce .amount ,
						.woocommerce-page ul.products li.product .price,
						.action-buttons > span, .woocommerce ul.products .added_to_cart,
						.comments-title,
						#reply-title, 
						#compare_table .compared-product li.price,
						#topmenu .quantity,
						.bg-color3 {background-color:#$color3; color:#$color3_fc} 
						#primary-menu > ul > li > ul, #primary-menu ul > li > ul ul {border-bottom-color:#$color3} ";
						
			}
			if ( isset($color4) && strlen( $color4 ) ){
						$output .= "
						.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
						.woocommerce-page ,
						.woocommerce div.product .product_title, 
						.woocommerce #content div.product .product_title, 
						.woocommerce-page div.product .product_title, 
						.woocommerce-page #content div.product .product_title, 
						.woocommerce ul.products li.product h3, 
						.woocommerce-page ul.products li.product h3, 
						#primary-menu ul > li li:hover > a, 
						#tab-description h2,
						.woocommerce ul.products li.product:hover h3,
						.woocommerce-page ul.products li.product:hover h3,
						.color4 {color:#$color4} 
						.circle-wrapper span,
						.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
						.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,
						
						.bg-color4 {background-color:#$color4; color:#$color4_fc} ";
						
			}
			if ( isset($mainbgimage_fc) && strlen( $mainbgimage_fc ) && isset($mainbgimage) && strlen( $mainbgimage ) && ($thm_option['thm_site-style'] == 'custom') ){
				$output .= ".upper-footer .widget-title ,.upper-footer  .widget-area-box a, main-image-font-color {color:#$mainbgimage_fc} ";
				
			}
			if ( isset($mainbgimage) && strlen( $mainbgimage ) ){
						$imagelink =  wp_get_attachment_image_src($mainbgimage,'full');
						$output .= ".main-color {background-image: url($imagelink[0]); color:#$mainbgimage_fc }";	
				if ( isset($background_aligment) && strlen( $background_aligment ) ){
					switch ($background_aligment) {
						case 'repeat':
								$output .= ".main-color {background-repeat: repeat;}";
							break;
						case 'repeatx':
								$output .= ".main-color {background-repeat: repeat-x;}";
							break;
						case 'repeaty':
								$output .= ".main-color {background-repeat: repea-y;}";
							break;
						case 'strech':
								$output .= ".main-color {background-repeat: no-repeat;
														background-position: center center;
														-webkit-background-size: cover;
														-moz-background-size: cover;
														-o-background-size: cover ;
														background-size: cover !important;
														filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.myBackground.jpg', sizingMethod='scale');
														-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='myBackground.jpg', sizingMethod='scale')\";}";
							break;				
						case 'fixed':
								$output .= ".main-color {background-repeat: no-repeat;
														background-position: center center;
														background-attachment: fixed;
														-webkit-background-size: cover;
														-moz-background-size: cover;
														-o-background-size: cover;
														background-size: cover !imortant;
														filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.myBackground.jpg', sizingMethod='scale');
														-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='myBackground.jpg', sizingMethod='scale')\";}";
							break;	
						default:
							
							break;
					}
				}			
			}
			if ( isset($bodycolor) && strlen( $bodycolor ) ){
						$output .= "body {background-color: #$bodycolor  }";
			}
			if ( isset($linkcolor) && strlen( $linkcolor ) ){
						$output .= "a ,.linkcolor, .bottom-footer .widget-title, .woocommerce-breadcrumb a, .sbOptions a:hover,.sbOptions a:focus,.sbOptions a.sbFocus, .sbOptions a:link, .sbOptions a:visited , #sidebar .widget a.sbToggle {color: #$linkcolor } .linkcolor-as-bg{background:#$linkcolor}";
			}
			if ( isset($textcolor) && strlen( $textcolor ) ){
						$output .= "body,p,.summary  {color: #$textcolor  }";
			}
			
			
			if ( isset($headergradient) && strlen( $headergradient ) ){
						$output .= "#content article .entry-title, .stripe, .headergradient, #compare_table ul.compared-product .title{ $headergradient color:#$header_fc}
									#content article .entry-title a, .stripe p , #content article .comments-link a, #content article .comments-link .icon, .headergradient a, #compare_table ul.compared-product .title a { color:#$header_fc}
						
						";
			}
			
			// Output styles
			if (isset($output) && $output != '') {
	
				$filecontent .= "\n" . "/*** Custom Colors ***/\n" . $output . "\n/*** End off Custom colors ***/";
				
			
			} 
		
			if(thm_get_option('thm_title-font'))
				$titlefont = thm_get_option('thm_title-font') ;
			if(thm_get_option('thm_content-font'))
				$contentfont = thm_get_option('thm_content-font');
			$titlefont = str_replace("+", " ", $titlefont) ;
			$contentfont = str_replace("+", " ", $contentfont);		
			
			if ( isset($titlefont) && strlen( $titlefont ) ){
						$filecontent .= "h1, h2, h3, h4, h5, h6, .widget .heading, #site-title a, #site-title, .entry-title {font-family: '$titlefont' }";	
						
			}
			if ( isset($contentfont) && strlen( $contentfont ) ){
						$filecontent .= "body {font-family: '$contentfont'}";
			}
			
			
			if (isset($custom_css) && $custom_css != '') {
	
				$filecontent .=  "\n" . "/*** Custom Css ***/\n" . $custom_css . "\n/*** End off Custom Css ***/\n ";
				
			
			}
	
			
			file_put_contents ($custom_css_file, $filecontent);
		}
		
		
}