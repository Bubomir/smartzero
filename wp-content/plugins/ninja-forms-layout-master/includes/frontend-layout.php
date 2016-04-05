<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Bti_Layout_Master_Frontend_Layout {

	function __construct() {

		// Form class filter
		add_filter( 'ninja_forms_cont_class', array( $this, 'add_custom_form_class' ), 10, 2 );

		// Order form fields
		add_filter( 'ninja_forms_display_fields_array', array( $this, 'layout_master_field_order' ), 100, 2 );

		// Add custom styles
		add_action( 'wp_head', array( $this, 'add_styles' ), 20 );

		// Add columns
		add_action( 'ninja_forms_display_before_field', array( $this, 'before_field') );
		add_action( 'ninja_forms_display_after_field',  array( $this, 'after_field' ) );
	}

	/**
	 * Reorders form fields as defined in 'Layout' tab.
	 *
	 * @since Ninja Forms Layout Master 1.4.0
	 */
	function layout_master_field_order( $field_results, $form_id ) {

		// No layout details found, return field list unchanged.
		if ( is_array( $field_results ) && 0 < count( $field_results ) ) {
			if ( !isset( $field_results[0]['data']['bti_layout_master']['order'] ) ) {
				return $field_results;
			}
		}

		// Reorder
		$result = array();
		$unsorted = array();
		foreach ( $field_results as $field ) {
			if ( isset( $field['data']['bti_layout_master']['order'] ) ) {
				$order = $field['data']['bti_layout_master']['order'];
				$result[$order] = $field;
			} else {
				$unsorted[] = $field;
			}
		}

		// Sort
		ksort( $result );

		// Merge both arrays
		foreach( $unsorted as $field ) {
			$result[] = $field;
		}

		return $result;
	}

	/**
	 * Called by Ninja Forms. Adds CSS classes to support multi column layout.
	 */
	function before_field( $field_id ) {

		// Form data
		$form       = ninja_forms_get_form_by_field_id( $field_id );
		$form_data  = isset( $form['data']['bti_layout_master'] ) ? $form['data']['bti_layout_master'] : array();
		$rendering  = isset( $form_data['rendering'] ) ? $form_data['rendering'] : '';

		// Field data
		$field      = ninja_forms_get_field_by_id( $field_id );
		$field_data = isset( $field['data']['bti_layout_master'] ) ? $field['data']['bti_layout_master'] : array();

		// Rendering type
		if ( '' == $rendering ) {
			// Data for class name
			$sizex  = isset( $field_data['sizex'] ) ? $field_data['sizex'] : 1;
			$cols   = isset( $form_data['cols'] ) ? $form_data['cols'] : 1;
			$before = '';
			$after  = '';

			// Before
			if ( isset( $field_data['before'] ) && $field_data['before'] > 0 ) {
				$before = 'bti-lm-b-'. $cols . '-' . $field_data['before'].' ';
			}

			// After
			if ( isset( $field_data['after'] ) && $field_data['after'] > 0 ) {
				$after = ' bti-lm-a-'. $cols . '-' . $field_data['after'];
			}

			echo '<div class="' . $before . 'bti-lm-col-' . $cols . '-' . $sizex . $after . '">';
		} else {
			$row  = isset( $field_data['row'] ) ? $field_data['row'] : 1;
			$col  = isset( $field_data['col'] ) ? $field_data['col'] : 1;
			$cols = isset( $form_data['cols'] ) ? $form_data['cols'] : 1;

			if ( 1 == $row ) {
				echo '<div class="bti-lm-col2-' . $cols . '-' . $col . '">';
			}

			echo '<div class="bti-lm-col2-item">';
		}
	}

	/**
	 * Called by Ninja Forms. Closes div opened by before_field method.
	 * (Wraps field into extra container)
	 */
	function after_field( $field_id ) {
		$field      = ninja_forms_get_field_by_id( $field_id );
		$field_data = isset( $field['data']['bti_layout_master'] ) ? $field['data']['bti_layout_master'] : array();

		// Rendering
		if ( isset( $field_data['rendering'] ) && 'columns' == $field_data['rendering'] ) {
			$last      = ( isset( $field_data['last'] ) && true == $field_data['last'] ) ? '</div>' : '';
			$form_last = ( isset( $field_data['form_last'] ) && true == $field_data['form_last'] ) ? '</div><div class="bti-lm-clear"></div>' : '';
		} else {
			$last = ( isset( $field_data['last'] ) && true == $field_data['last'] ) ? '<div class="bti-lm-clear"></div>' : '';
			$form_last = ( isset( $field_data['form_last'] ) && true == $field_data['form_last'] ) ? '<div class="bti-lm-clear"></div>' : '';
		}

		echo '</div>' . $last . $form_last;
	}

	/**
	 * Adds additional form class to form.
	 */
	function add_custom_form_class( $form_class, $form_id ) {

		$settings = ninja_forms_get_form_by_id( $form_id );

		$form_class .= ' ' . 'bti-lm-' . $form_id;
		if ( isset( $settings['data']['bti_layout_master'] )
			&& isset( $settings['data']['bti_layout_master']['form_class'] )
			&& '' != $settings['data']['bti_layout_master']['form_class'] ) {

			$form_class .= ' ' . $settings['data']['bti_layout_master']['form_class'];
		}

		return $form_class;
	}

	/**
	 * Custom CSS to support multi column layout and responsive layout options.
	 */
	function add_styles() {

		$custom_css = '';

		// Set styles for different layouts
		$custom_css .= '
			div[class*="bti-lm-col-"], div[class*="bti-lm-col2-"] { float: left; }

			.bti-lm-col2-1-1 { width: 100%; }
			div[class*="bti-lm-col2-2-"] { width: 50%; }
			div[class*="bti-lm-col2-3-"] { width: 33.33%; }
			div[class*="bti-lm-col2-4-"] { width: 25%; }
			div[class="bti-lm-col2-item"] { float: none; }

			.bti-lm-col-1-1 { width: 100%; }
			.bti-lm-col-2-1 { width: 50%; }
			.bti-lm-col-2-2 { width: 100%; }
			.bti-lm-col-3-1 { width: 33.3%; }
			.bti-lm-col-3-2 { width: 66.6%; }
			.bti-lm-col-3-3 { width: 99.9%; }
			.bti-lm-col-4-1 { width: 25%; }
			.bti-lm-col-4-2 { width: 50%; }
			.bti-lm-col-4-3 { width: 75%; }
			.bti-lm-col-4-4 { width: 100%; }
			.bti-lm-a-2-1 { margin-right: 50%; }
			.bti-lm-a-3-1 { margin-right: 33.3%; }
			.bti-lm-a-3-2 { margin-right: 66.6%; }
			.bti-lm-a-4-1 { margin-right: 25%; }
			.bti-lm-a-4-2 { margin-right: 50%; }
			.bti-lm-a-4-3 { margin-right: 75%; }
			.bti-lm-b-2-1 { margin-left: 50%; }
			.bti-lm-b-3-1 { margin-left: 33.3%; }
			.bti-lm-b-3-2 { margin-left: 66.6%; }
			.bti-lm-b-4-1 { margin-left: 25%; }
			.bti-lm-b-4-2 { margin-left: 50%; }
			.bti-lm-b-4-3 { margin-left: 75%; }
			.bti-lm-clear { clear: both; }

			@media (max-width: 480px) {
				div[class^="bti-lm-a-"], div[class^="bti-lm-b-"] { margin-left: 0%; margin-right: 0%; }
				div[class^="bti-lm-col-"],div[class^="bti-lm-col2-"] { width: 100%; }
			}
		';

		// Get all forms
		$forms = ninja_forms_get_all_forms();

		foreach ( $forms as $form ) {
			if ( isset( $form['data']['bti_layout_master'] ) ) {
				$layout_master = $form['data']['bti_layout_master'];
				$lm            = $form['data']['bti_layout_master'];

				/**
				 * Visually selected styles
				 */
				if ( isset( $layout_master['enable_css'] ) && 1 == $layout_master['enable_css'] ) {

					// Hide Required Note
					if ( isset( $lm['hide_required_note'] ) && 1 == $lm['hide_required_note'] ) {
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap>.ninja-forms-required-items {display: none;}',
							$form['id']
						);
					}

					// Success message
					$success_message_text_color    = isset( $lm['success_message_text_color'] ) ? sprintf( 'color: %s;', $lm['success_message_text_color'] ) : '';
					$success_message_font_size     = isset( $lm['success_message_font_size'] ) ? sprintf( 'font-size: %dpx;', $lm['success_message_font_size'] ) : '';
					$success_message_bg_color      = isset( $lm['success_message_bg_color'] ) ? sprintf( 'background-color: %s;', $lm['success_message_bg_color'] ) : '';
					$success_message_border_color  = isset( $lm['success_message_border_color'] ) ? sprintf( 'border-color: %s;', $lm['success_message_border_color'] ) : '';
					$success_message_border_size   = isset( $lm['success_message_border_size'] ) ? sprintf( 'border-style: solid; border-width: %dpx;', $lm['success_message_border_size'] ) : '';
					$success_message_border_radius = isset( $lm['success_message_border_radius'] ) ? sprintf( 'border-radius: %dpx;', $lm['success_message_border_radius'] ) : '';
					$success_message_padding       = isset( $lm['success_message_padding'] ) ? sprintf( 'padding: %dpx;', $lm['success_message_padding'] ) : '';

					$custom_css .= sprintf(
						'.bti-lm-%d .ninja-forms-form-wrap .ninja-forms-success-msg>div { %s %s %s %s %s %s %s } ',
						$form['id'],
						$success_message_text_color,
						$success_message_font_size,
						$success_message_bg_color,
						$success_message_border_color,
						$success_message_border_size,
						$success_message_border_radius,
						$success_message_padding
					);

					$custom_css .= sprintf(
						'.bti-lm-%d .ninja-forms-form-wrap .ninja-forms-success-msg>div>p { margin-bottom: 0px; }',
						$form['id']
					);

					// Error message
					$error_message_text_color    = isset( $lm['error_message_text_color'] ) ? sprintf( 'color: %s;', $lm['error_message_text_color'] ) : '';
					$error_message_font_size     = isset( $lm['error_message_font_size'] ) ? sprintf( 'font-size: %dpx;', $lm['error_message_font_size'] ) : '';
					$error_message_bg_color      = isset( $lm['error_message_bg_color'] ) ? sprintf( 'background-color: %s;', $lm['error_message_bg_color'] ) : '';
					$error_message_border_color  = isset( $lm['error_message_border_color'] ) ? sprintf( 'border-color: %s;', $lm['error_message_border_color'] ) : '';
					$error_message_border_size   = isset( $lm['error_message_border_size'] ) ? sprintf( 'border-style: solid; border-width: %dpx;', $lm['error_message_border_size'] ) : '';
					$error_message_border_radius = isset( $lm['error_message_border_radius'] ) ? sprintf( 'border-radius: %dpx;', $lm['error_message_border_radius'] ) : '';
					$error_message_padding       = isset( $lm['error_message_padding'] ) ? sprintf( 'padding: %dpx;', $lm['error_message_padding'] ) : '';

					$custom_css .= sprintf(
						'.bti-lm-%d .ninja-forms-form-wrap .ninja-forms-error-msg>div, '.
						'.bti-lm-%d .ninja-forms-form-wrap .ninja-forms-error-msg>p { %s %s %s %s %s %s %s } ',
						$form['id'],
						$form['id'],
						$error_message_text_color,
						$error_message_font_size,
						$error_message_bg_color,
						$error_message_border_color,
						$error_message_border_size,
						$error_message_border_radius,
						$error_message_padding
					);

					// Background Color
					if ( isset( $lm['background'] ) && !empty( $lm['background'] ) ) {
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap { background: %s; } ',
							$form['id'], $lm['background']
						);
					}

					// Border
					if ( isset( $lm['border_color'] ) && !empty( $lm['border_color'] ) ) {
						$border_size = !empty( $lm['border_size'] ) ? $lm['border_size'] : 0;
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap { border: %dpx solid %s; } ',
							$form['id'], $border_size, $lm['border_color']
						);
					}

					// Border Radius
					if ( isset( $lm['border_radius'] ) && !empty( $lm['border_radius'] ) ) {
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap { border-radius: %dpx; } ',
							$form['id'], $lm['border_radius']
						);
					}

					// Form Padding
					if ( isset( $lm['form_padding'] ) && !empty( $lm['form_padding'] ) ) {
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap { padding: %dpx; } ',
							$form['id'], $lm['form_padding']
						);
					}

					// Field Padding
					if ( isset( $lm['field_padding'] ) && !empty( $lm['field_padding'] ) ) {
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap .field-wrap { margin: %dpx; } ',
							$form['id'], $lm['field_padding']
						);
					}

					// Width
					if ( isset( $lm['width'] ) && !empty( $lm['width'] ) ) {
						$custom_css .= sprintf(
							'.bti-lm-%d form { width: %dpx; } ',
							$form['id'], $lm['width']
						);
					}

					// Height
					if ( isset( $lm['height'] ) && !empty( $lm['height'] ) ) {
						$custom_css .= sprintf(
							'.bti-lm-%d form { height: %dpx; } ',
							$form['id'], $lm['height']
						);
					}

					// Label
					$label_color     = isset( $lm['label_color'] ) ? sprintf( 'color: %s;', $lm['label_color'] ) : '';
					$label_font_size = isset( $lm['label_font_size'] ) ? sprintf( 'font-size: %dpx;', $lm['label_font_size'] ) : '';
					$label_width     = ( isset( $lm['label_width'] ) && !empty( $lm['label_width'] ) ) ? sprintf( 'width: %dpx;', $lm['label_width'] ) : '';
					$label_height    = ( isset( $lm['label_height'] ) && !empty( $lm['label_height'] ) ) ? sprintf( 'height: %dpx;', $lm['label_height'] ) : '';

					// Width & Height
					if ( !empty( $label_width ) || !empty( $label_height ) ) {
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap .text-wrap label, '.
							'.bti-lm-%d .ninja-forms-all-fields-wrap .textarea-wrap label { %s %s }',
							$form['id'], $form['id'],
							$label_width, $label_height
						);
					}

					$custom_css .= sprintf(
						'.bti-lm-%d .ninja-forms-all-fields-wrap label { %s %s }',
						$form['id'], $label_color, $label_font_size
					);

					// Label Required
					if ( isset( $lm['label_req_color'] ) && !empty( $lm['label_req_color'] ) ) {
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap label .ninja-forms-req-symbol strong { color: %s; }',
							$form['id'], $lm['label_req_color']
						);
					}

					// Input / Textarea
					$input_color         = isset( $lm['input_color'] ) ? sprintf( 'color: %s;', $lm['input_color'] ) : '';
					$input_font_size     = isset( $lm['input_font_size'] ) ? sprintf( 'font-size: %dpx;', $lm['input_font_size'] ) : '';
					$input_border_color  = isset( $lm['input_border_color'] ) ? sprintf( 'border-color: %s;', $lm['input_border_color'] ) : '';
					$input_border_size   = isset( $lm['input_border_size'] ) ? sprintf( 'border-width: %dpx;', $lm['input_border_size'] ) : '';
					$input_border_radius = isset( $lm['input_border_radius'] ) ? sprintf( 'border-radius: %dpx;', $lm['input_border_radius'] ) : '';

					$custom_css .= sprintf(
						'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="text"], '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="number"], '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="password"], '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap textarea, '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap select { %s %s %s %s %s } ',
						$form['id'], $form['id'], $form['id'], $form['id'], $form['id'],
						$input_color, $input_font_size, $input_border_color, $input_border_size, $input_border_radius
					);

					// Width & Height
					$input_width     = ( isset( $lm['input_width'] ) && !empty( $lm['input_width'] ) ) ? sprintf( 'width: %spx;', $lm['input_width'] ) : '';
					$input_height    = ( isset( $lm['input_height'] ) && !empty( $lm['input_height'] ) ) ? sprintf( 'height: %spx;', $lm['input_height'] ) : '';
					$textarea_width  = ( isset( $lm['textarea_width'] ) && !empty( $lm['textarea_width'] ) ) ? sprintf( 'width: %spx;', $lm['textarea_width'] ) : '';
					$textarea_height = ( isset( $lm['textarea_height'] ) && !empty( $lm['textarea_height'] ) ) ? sprintf( 'height: %spx;', $lm['textarea_height'] ) : '';

					if ( !empty( $lm['input_width'] ) || !empty( $lm['input_height'] ) ) {
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="text"], '.
							'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="number"], '.
							'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="password"], '.
							'.bti-lm-%d .ninja-forms-all-fields-wrap select { %s %s } ',
							$form['id'], $form['id'], $form['id'], $form['id'],
							$input_width, $input_height
						);
					}

					if ( !empty( $lm['textarea_width'] ) || !empty( $lm['textarea_height'] ) ) {
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap .textarea-wrap textarea { %s %s } ',
							$form['id'], $textarea_width, $textarea_height
						);
					}

					// Field error (text color)
					if ( isset( $lm['input_error_color'] ) ) {
						$input_error_color = sprintf( 'color: %s;', $lm['input_error_color'] );
						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap .ninja-forms-field-error{ %s }',
							$form['id'],
							$input_error_color
						);
					}

					// Field error (border color)
					if ( isset( $lm['input_border_error_color'] ) ) {
						$input_border_error_color = sprintf( 'border-color: %s', $lm['input_border_error_color'] );

						$custom_css .= sprintf(
							'.bti-lm-%d .ninja-forms-all-fields-wrap .ninja-forms-error input, '.
							'.bti-lm-%d .ninja-forms-all-fields-wrap .ninja-forms-error select, '.
							'.bti-lm-%d .ninja-forms-all-fields-wrap .ninja-forms-error textarea { %s }',
							$form['id'],
							$form['id'],
							$form['id'],
							$input_border_error_color
						);
					}

					// Buttons
					$button_color              = $this->get_css_property( $lm, 'button_color', 'color: %s;' );
					$button_font_size          = $this->get_css_property( $lm, 'button_font_size', 'font-size: %dpx;' );
					$button_bg_color           = $this->get_css_property( $lm, 'button_bg_color', 'background: %s;' );
					$button_border_color       = $this->get_css_property( $lm, 'button_border_color', 'border-color: %s;' );
					$button_border_size        = $this->get_css_property( $lm, 'button_border_size', 'border-width: %dpx;' );
					$button_border_radius      = $this->get_css_property( $lm, 'button_border_radius', 'border-radius: %dpx;' );

					$button_top_bottom_padding = ( isset( $lm['button_top_bottom_padding'] ) && !empty( $lm['button_top_bottom_padding'] ) ) ? $lm['button_top_bottom_padding'] : '';
					$button_left_right_padding = ( isset( $lm['button_left_right_padding'] ) && !empty( $lm['button_left_right_padding'] ) ) ? $lm['button_left_right_padding'] : '';

					$tmp_css = '';
					if ( $button_top_bottom_padding != 0 ) {
						$tmp_css .= sprintf( 'padding-top: %spx; padding-bottom: %spx;', $button_top_bottom_padding, $button_top_bottom_padding );
					}

					if ( $button_left_right_padding != 0 ) {
						$tmp_css .= sprintf( 'padding-left: %spx; padding-right: %spx;', $button_left_right_padding, $button_left_right_padding );
					}

					$custom_css .= sprintf(
						'.bti-lm-%d .ninja-forms-all-fields-wrap button, '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap .button, '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="button"], '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="reset"], '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="submit"] { %s %s %s %s %s %s border-style: solid; %s }',
						$form['id'], $form['id'], $form['id'], $form['id'], $form['id'],
						$button_color, $button_bg_color, $button_border_color, $button_border_size, $button_border_radius, $button_font_size, $tmp_css
					);

					// Hover Buttons
					$button_hover_color              = $this->get_css_property( $lm, 'button_hover_color', 'color: %s;' );
					$button_hover_font_size          = $this->get_css_property( $lm, 'button_hover_font_size', 'font-size: %dpx;' );
					$button_hover_bg_color           = $this->get_css_property( $lm, 'button_hover_bg_color', 'background: %s;' );
					$button_hover_border_color       = $this->get_css_property( $lm, 'button_hover_border_color', 'border-color: %s;' );
					$button_hover_border_size        = $this->get_css_property( $lm, 'button_hover_border_size', 'border-width: %dpx;' );
					$button_hover_border_radius      = $this->get_css_property( $lm, 'button_hover_border_radius', 'border-radius: %dpx;' );

					$button_hover_top_bottom_padding = ( isset( $lm['button_hover_top_bottom_padding'] ) && !empty( $lm['button_hover_top_bottom_padding'] ) ) ? $lm['button_hover_top_bottom_padding'] : '';
					$button_hover_left_right_padding = ( isset( $lm['button_hover_left_right_padding'] ) && !empty( $lm['button_hover_left_right_padding'] ) ) ? $lm['button_hover_left_right_padding'] : '';

					$tmp_css = '';
					if ( $button_hover_top_bottom_padding !== 0 ) {
						$tmp_css .= sprintf( 'padding-top: %spx; padding-bottom: %spx;', $button_hover_top_bottom_padding, $button_hover_top_bottom_padding );
					}

					if ( $button_hover_left_right_padding !== 0 ) {
						$tmp_css .= sprintf( 'padding-left: %spx; padding-right: %spx;', $button_hover_left_right_padding, $button_hover_left_right_padding );
					}

					$custom_css .= sprintf(
						'.bti-lm-%d .ninja-forms-all-fields-wrap button:hover, '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap .button:hover, '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="button"]:hover, '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="reset"]:hover, '.
						'.bti-lm-%d .ninja-forms-all-fields-wrap input[type="submit"]:hover { %s %s %s %s %s %s border-style: solid; %s }',
						$form['id'], $form['id'], $form['id'],  $form['id'],  $form['id'],
						$button_hover_color, $button_hover_bg_color, $button_hover_border_color, $button_hover_border_size, $button_hover_border_radius, $button_hover_font_size, $tmp_css
					);
				}

				/**
				 * Custom CSS
				 */
				if ( isset( $layout_master['enable_custom_css'] ) && 1 == $layout_master['enable_custom_css'] ) {
					$custom_css .= stripslashes( $layout_master['custom_css'] );
				}
			}
		}

		// Add styles
		$custom_css = str_replace( "\n", " ", $custom_css );
		$custom_css = str_replace( "\t", "", $custom_css );
		$custom_css = preg_replace( "/\s+/", " ", $custom_css );
		echo '<style type="text/css">' . $custom_css . '</style>';
	}

	/**
	 * Method to handle isset() checks and css assignment
	 *
	 * @since 1.7.0
	 */
	function get_css_property( $lm, $var, $css_property ) {
		if ( isset( $lm[$var] ) && ( !empty( $lm[$var] ) || '0' == $lm[$var] ) ) {
			return sprintf( $css_property, $lm[$var] );
		}

		return '';
	}

}
