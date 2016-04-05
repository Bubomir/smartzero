<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Bti_Layout_Master_Admin_Styles {

	function __construct() {
		add_action( 'admin_init', array( $this, 'register_tab' ), 100 );
	}

	/**
	 * Adds new options tab to Ninja Forms
	 *
	 * @since 1.0.0
	 */
	function register_tab() {
		$args = array(
			'name'               => __( 'Styles', 'bti_layout_master' ),
			'page'               => 'ninja-forms',
			'display_function'   => array( $this, 'styles_page' ),
			'save_function'      => array( $this, 'save_styles_page' ),
			'show_save'          => true,
			'disable_no_form_id' => true,
			'tab_reload'         => true,
		);

		ninja_forms_register_tab( 'styles', $args );
	}

	/**
	 * Styles tab content (page content)
	 *
	 * @since 1.0.0
	 */
	function styles_page() {

		// Form Settings
		$form_id = isset ( $_REQUEST['form_id'] ) ? $_REQUEST['form_id'] : '';
		$settings = ninja_forms_get_form_by_id( $form_id );
		$bti_layout_master = $settings['data']['bti_layout_master'];

		// Deprecated will be removed in upcoming releases.
		$dissable_css = isset( $bti_layout_master['dissable_css'] ) ? $bti_layout_master['dissable_css'] : 0;

		// Enable CSS options
		if ( 1 == $dissable_css && ! isset( $bti_layout_master['enable_css'] ) ) {
			$enable_css = 0;
		} elseif ( 0 == $dissable_css && ! isset( $bti_layout_master['enable_css'] ) ) {
			$enable_css = 1;
		} else {
			$enable_css = $bti_layout_master['enable_css'];
		}

		$hide_required_note = $this->get_default_value( $bti_layout_master, 'hide_required_note', 0 );
		$form_class         = $this->get_default_value( $bti_layout_master, 'form_class', '' );

		// Form
		$background    = $this->get_default_value( $bti_layout_master, 'background', '#ffffff' );
		$border_color  = $this->get_default_value( $bti_layout_master, 'border_color', '#ffffff' );
		$border_size   = $this->get_default_value( $bti_layout_master, 'border_color', 0 );
		$border_radius = $this->get_default_value( $bti_layout_master, 'border_radius', 0 );
		$form_padding  = $this->get_default_value( $bti_layout_master, 'form_padding', 0 );
		$field_padding = $this->get_default_value( $bti_layout_master, 'field_padding', 0 );
		$width         = $this->get_default_value( $bti_layout_master, 'width', '' );
		$height        = $this->get_default_value( $bti_layout_master, 'height', '' );

		// Form Success Message
		$success_message_text_color    = $this->get_default_value( $bti_layout_master, 'success_message_text_color', '#000000' );
		$success_message_font_size     = $this->get_default_value( $bti_layout_master, 'success_message_font_size', 13 );
		$success_message_bg_color      = $this->get_default_value( $bti_layout_master, 'success_message_bg_color', '#ffffff' );
		$success_message_border_color  = $this->get_default_value( $bti_layout_master, 'success_message_border_color', '#ffffff' );
		$success_message_border_size   = $this->get_default_value( $bti_layout_master, 'success_message_border_size', 0 );
		$success_message_border_radius = $this->get_default_value( $bti_layout_master, 'success_message_border_radius', 0 );
		$success_message_padding       = $this->get_default_value( $bti_layout_master, 'success_message_padding', 0);

		// Form Error Message
		$error_message_text_color    = $this->get_default_value( $bti_layout_master, 'error_message_text_color', '#ff0000' );
		$error_message_font_size     = $this->get_default_value( $bti_layout_master, 'error_message_font_size', 13 );
		$error_message_bg_color      = $this->get_default_value( $bti_layout_master, 'error_message_bg_color', '#ffffff' );
		$error_message_border_color  = $this->get_default_value( $bti_layout_master, 'error_message_border_color', '#ffffff' );
		$error_message_border_size   = $this->get_default_value( $bti_layout_master, 'error_message_border_size', 0 );
		$error_message_border_radius = $this->get_default_value( $bti_layout_master, 'error_message_border_radius', 0 );
		$error_message_padding       = $this->get_default_value( $bti_layout_master, 'error_message_padding', 0 );

		// Form Labels
		$label_color     = $this->get_default_value( $bti_layout_master, 'label_color', '#000000' );
		$label_req_color = $this->get_default_value( $bti_layout_master, 'label_req_color', '#ff0000' );
		$label_font_size = $this->get_default_value( $bti_layout_master, 'label_font_size', 13 );
		$label_width     = $this->get_default_value( $bti_layout_master, 'label_width', '' );
		$label_height    = $this->get_default_value( $bti_layout_master, 'label_height', '' );

		// Form Fields
		$input_color              = $this->get_default_value( $bti_layout_master, 'input_color', '#000000' );
		$input_error_color        = $this->get_default_value( $bti_layout_master, 'input_error_color', '#ff0000' );
		$input_border_error_color = $this->get_default_value( $bti_layout_master, 'input_border_error_color', '#ff0000' );
		$input_font_size          = $this->get_default_value( $bti_layout_master, 'input_font_size', 12 );
		$input_border_color       = $this->get_default_value( $bti_layout_master, 'input_border_color', '#eeeeee' );
		$input_border_size        = $this->get_default_value( $bti_layout_master, 'input_border_size', 1 );
		$input_border_radius      = $this->get_default_value( $bti_layout_master, 'input_border_radius', 0 );
		$input_width              = $this->get_default_value( $bti_layout_master, 'input_width', '' );
		$input_height             = $this->get_default_value( $bti_layout_master, 'input_height', '' );
		$textarea_width           = $this->get_default_value( $bti_layout_master, 'textarea_width', '' );
		$textarea_height          = $this->get_default_value( $bti_layout_master, 'textarea_height', '' );

		// Form Submit Button
		$button_color                    = $this->get_default_value( $bti_layout_master, 'button_color', '#ffffff' );
		$button_font_size                = $this->get_default_value( $bti_layout_master, 'button_font_size', 13 );
		$button_bg_color                 = $this->get_default_value( $bti_layout_master, 'button_bg_color', '#e80000' );
		$button_border_color             = $this->get_default_value( $bti_layout_master, 'button_border_color', '#ff0000' );
		$button_border_size              = $this->get_default_value( $bti_layout_master, 'button_border_size', 0 );
		$button_border_radius            = $this->get_default_value( $bti_layout_master, 'button_border_radius', 0 );
		$button_top_bottom_padding       = $this->get_default_value( $bti_layout_master, 'button_top_bottom_padding', 0 );
		$button_left_right_padding       = $this->get_default_value( $bti_layout_master, 'button_left_right_padding', 0 );

		$button_hover_color              = $this->get_default_value( $bti_layout_master, 'button_hover_color', '#ffffff' );
		$button_hover_font_size          = $this->get_default_value( $bti_layout_master, 'button_hover_font_size', 13 );
		$button_hover_bg_color           = $this->get_default_value( $bti_layout_master, 'button_hover_bg_color', '#e80000' );
		$button_hover_border_color       = $this->get_default_value( $bti_layout_master, 'button_hover_border_color', '#ff0000' );
		$button_hover_border_size        = $this->get_default_value( $bti_layout_master, 'button_hover_border_size', 0 );
		$button_hover_border_radius      = $this->get_default_value( $bti_layout_master, 'button_hover_border_radius', 0 );
		$button_hover_top_bottom_padding = $this->get_default_value( $bti_layout_master, 'button_hover_top_bottom_padding', 0 );
		$button_hover_left_right_padding = $this->get_default_value( $bti_layout_master, 'button_hover_left_right_padding', 0 );

		// Custom CSS
		$enable_custom_css = $this->get_default_value( $bti_layout_master, 'enable_custom_css', 1 );
		$custom_css        = $this->get_default_value( $bti_layout_master, 'custom_css', '' );
		$import_css        = $this->get_default_value( $bti_layout_master, 'import_css', '' );
		$export_css        = base64_encode( json_encode( $bti_layout_master ) );

		// Cookie
		$tab = array();
		if ( isset( $_COOKIE['nf_lm_session'] ) ) {
			$tab = explode( ',', $_COOKIE['nf_lm_session'] );
			unset( $_COOKIE['nf_lm_session'] );
		}
		?>

			<div class="ninja_forms_style_metaboxes">

				<!-- General Settings -->
				<div id="ninja_forms_metabox_lm_general_settings" class="postbox">
					<span class="item-controls">
						<a class="item-edit metabox-item-edit" title="Edit Menu Item" href="#"><?php _e( 'Edit Menu Item', 'bti_layout_master'); ?></a>
					</span>

					<h3 class="hndle">
						<span><?php _e( 'General Settings', 'bti_layout_master' ); ?></span>
					</h3>

					<div class="inside" <?php echo ( count( $tab ) == 0 || in_array( 'ninja_forms_metabox_lm_general_settings', $tab ) ) ? '' : 'style="display:none;"'; ?>>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_enable_css"><?php _e( 'Enable Form Styles', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="bti_layout_master_enable_css" id="bti_layout_master_enable_css" <?php checked( $enable_css ); ?> />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_hide_required_note"><?php _e( 'Hide Required Note', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="bti_layout_master_hide_required_note" id="bti_layout_master_hide_required_note" <?php checked( $hide_required_note ); ?> />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_form_class"><?php _e( 'Form Wrapper Class', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_form_class" id="bti_layout_master_form_class" class="regular-text" value="<?php echo esc_attr( $form_class ); ?>" />
										<p class="description" style="margin: 0px;"><?php _e( 'Ex. contact-form, my-form, custom-form', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Form -->
				<div id="ninja_forms_metabox_lm_form" class="postbox">
					<span class="item-controls">
						<a class="item-edit metabox-item-edit" title="Edit Menu Item" href="#"><?php _e( 'Edit Menu Item', 'bti_layout_master'); ?></a>
					</span>

					<h3 class="hndle">
						<span><?php _e( 'Form', 'bti_layout_master' ); ?></span>
					</h3>

					<div class="inside" <?php echo in_array( 'ninja_forms_metabox_lm_form', $tab ) ? '' : 'style="display:none;"'; ?>>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_background"><?php _e( 'Background Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_background" id="bti_layout_master_background" class="bti-layer-master-color" value="<?php echo esc_attr( $background ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_border_color"><?php _e( 'Border Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_border_color" id="bti_layout_master_border_color" class="bti-layer-master-color" value="<?php echo esc_attr( $border_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_border_size"><?php _e( 'Border Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_border_size" id="bti_layout_master_border_size" class="regular-text" value="<?php echo esc_attr( $border_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_border_radius"><?php _e( 'Border Radius', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_border_radius" id="bti_layout_master_border_radius" class="regular-text" value="<?php echo esc_attr( $border_radius ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_form_padding"><?php _e( 'Padding', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_form_padding" id="bti_layout_master_form_padding" class="regular-text" value="<?php echo esc_attr( $form_padding ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_field_padding"><?php _e( 'Form Field Padding', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_field_padding" id="bti_layout_master_field_padding" class="regular-text" value="<?php echo esc_attr( $field_padding ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_width"><?php _e( 'Form Width', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_width" id="bti_layout_master_width" class="regular-text" value="<?php echo esc_attr( $width ); ?>" /> px
										<p class="description" style="margin: 0px;"><?php _e( 'Optional. Resizes form to specific width (fixed).', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_height"><?php _e( 'Form Height', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_height" id="bti_layout_master_height" class="regular-text" value="<?php echo esc_attr( $height ); ?>" /> px
										<p class="description" style="margin: 0px;"><?php _e( 'Optional. Resizes form to specific height (fixed).', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Form Success Message -->
				<div id="ninja_forms_metabox_lm_form_success_msg" class="postbox">
					<span class="item-controls">
						<a class="item-edit metabox-item-edit" title="Edit Menu Item" href="#"><?php _e( 'Edit Menu Item', 'bti_layout_master'); ?></a>
					</span>

					<h3 class="hndle">
						<span><?php _e( 'Form Success Message', 'bti_layout_master' ); ?></span>
					</h3>

					<div class="inside" <?php echo in_array( 'ninja_forms_metabox_lm_form_success_msg', $tab ) ? '' : 'style="display:none;"'; ?>>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_success_message_text_color"><?php _e( 'Text Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_success_message_text_color" id="bti_layout_master_success_message_text_color" class="bti-layer-master-color" value="<?php echo esc_attr( $success_message_text_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_success_message_font_size"><?php _e( 'Font Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_success_message_font_size" id="bti_layout_master_success_message_font_size" class="regular-text" value="<?php echo esc_attr( $success_message_font_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_success_message_bg_color"><?php _e( 'Background Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_success_message_bg_color" id="bti_layout_master_success_message_bg_color" class="bti-layer-master-color" value="<?php echo esc_attr( $success_message_bg_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_success_message_border_color"><?php _e( 'Border Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_success_message_border_color" id="bti_layout_master_success_message_border_color" class="bti-layer-master-color" value="<?php echo esc_attr( $success_message_border_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_success_message_border_size"><?php _e( 'Border Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_success_message_border_size" id="bti_layout_master_success_message_border_size" class="regular-text" value="<?php echo esc_attr( $success_message_border_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_success_message_border_radius"><?php _e( 'Border Radius', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_success_message_border_radius" id="bti_layout_master_success_message_border_radius" class="regular-text" value="<?php echo esc_attr( $success_message_border_radius ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_success_message_padding"><?php _e( 'Padding', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_success_message_padding" id="bti_layout_master_success_message_padding" class="regular-text" value="<?php echo esc_attr( $success_message_padding ); ?>" /> px
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Form Error Message -->
				<div id="ninja_forms_metabox_lm_form_error_msg" class="postbox">
					<span class="item-controls">
						<a class="item-edit metabox-item-edit" title="Edit Menu Item" href="#"><?php _e( 'Edit Menu Item', 'bti_layout_master'); ?></a>
					</span>

					<h3 class="hndle">
						<span><?php _e( 'Form Error Message', 'bti_layout_master' ); ?></span>
					</h3>

					<div class="inside" <?php echo in_array( 'ninja_forms_metabox_lm_form_error_msg', $tab ) ? '' : 'style="display:none;"'; ?>>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_error_message_text_color"><?php _e( 'Text Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_error_message_text_color" id="bti_layout_master_error_message_text_color" class="bti-layer-master-color" value="<?php echo esc_attr( $error_message_text_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_error_message_font_size"><?php _e( 'Font Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_error_message_font_size" id="bti_layout_master_error_message_font_size" class="regular-text" value="<?php echo esc_attr( $error_message_font_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_error_message_bg_color"><?php _e( 'Background Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_error_message_bg_color" id="bti_layout_master_error_message_bg_color" class="bti-layer-master-color" value="<?php echo esc_attr( $error_message_bg_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_error_message_border_color"><?php _e( 'Border Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_error_message_border_color" id="bti_layout_master_error_message_border_color" class="bti-layer-master-color" value="<?php echo esc_attr( $error_message_border_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_error_message_border_size"><?php _e( 'Border Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_error_message_border_size" id="bti_layout_master_error_message_border_size" class="regular-text" value="<?php echo esc_attr( $error_message_border_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_error_message_border_radius"><?php _e( 'Border Radius', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_error_message_border_radius" id="bti_layout_master_error_message_border_radius" class="regular-text" value="<?php echo esc_attr( $error_message_border_radius ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_error_message_padding"><?php _e( 'Padding', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_error_message_padding" id="bti_layout_master_error_message_padding" class="regular-text" value="<?php echo esc_attr( $error_message_padding ); ?>" /> px
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Form Labels -->
				<div id="ninja_forms_metabox_lm_labels" class="postbox">
					<span class="item-controls">
						<a class="item-edit metabox-item-edit" title="Edit Menu Item" href="#"><?php _e( 'Edit Menu Item', 'bti_layout_master'); ?></a>
					</span>

					<h3 class="hndle">
						<span><?php _e( 'Form Labels', 'bti_layout_master' ); ?></span>
					</h3>

					<div class="inside" <?php echo in_array( 'ninja_forms_metabox_lm_labels', $tab ) ? '' : 'style="display:none;"'; ?>>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_label_color"><?php _e( 'Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_label_color" id="bti_layout_master_label_color" class="bti-layer-master-color" value="<?php echo esc_attr( $label_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_label_req_color"><?php _e( 'Required* Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_label_req_color" id="bti_layout_master_label_req_color" class="bti-layer-master-color" value="<?php echo esc_attr( $label_req_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_label_font_size"><?php _e( 'Font Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_label_font_size" id="bti_layout_master_label_font_size" class="regular-text" value="<?php echo esc_attr( $label_font_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_label_width"><?php _e( 'Width', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_label_width" id="bti_layout_master_label_width" class="regular-text" value="<?php echo esc_attr( $label_width ); ?>" /> px
										<p class="description" style="margin: 0px;"><?php _e( 'Optional. Resizes label to specific width (fixed).', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_label_height"><?php _e( 'Height', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_label_height" id="bti_layout_master_label_height" class="regular-text" value="<?php echo esc_attr( $label_height ); ?>" /> px
										<p class="description" style="margin: 0px;"><?php _e( 'Optional. Resizes label to specific height (fixed).', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Form Fields -->
				<div id="ninja_forms_metabox_lm_form_fields" class="postbox">
					<span class="item-controls">
						<a class="item-edit metabox-item-edit" title="Edit Menu Item" href="#"><?php _e( 'Edit Menu Item', 'bti_layout_master'); ?></a>
					</span>

					<h3 class="hndle">
						<span><?php _e( 'Form Fields', 'bti_layout_master' ); ?></span>
					</h3>

					<div class="inside" <?php echo in_array( 'ninja_forms_metabox_lm_form_fields', $tab ) ? '' : 'style="display:none;"'; ?>>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_input_color"><?php _e( 'Text Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_input_color" id="bti_layout_master_input_color" class="bti-layer-master-color" value="<?php echo esc_attr( $input_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_input_error_color"><?php _e( 'Error Text Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_input_error_color" id="bti_layout_master_input_error_color" class="bti-layer-master-color" value="<?php echo esc_attr( $input_error_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_input_border_error_color"><?php _e( 'Error Field Border Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_input_border_error_color" id="bti_layout_master_input_border_error_color" class="bti-layer-master-color" value="<?php echo esc_attr( $input_border_error_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_input_font_size"><?php _e( 'Font Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_input_font_size" id="bti_layout_master_input_font_size" class="regular-text" value="<?php echo esc_attr( $input_font_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_input_border_color"><?php _e( 'Border Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_input_border_color" id="bti_layout_master_input_border_color" class="bti-layer-master-color" value="<?php echo esc_attr( $input_border_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_input_border_size"><?php _e( 'Border Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_input_border_size" id="bti_layout_master_input_border_size" class="regular-text" value="<?php echo esc_attr( $input_border_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_input_border_radius"><?php _e( 'Border Radius', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_input_border_radius" id="bti_layout_master_input_border_radius" class="regular-text" value="<?php echo esc_attr( $input_border_radius ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_input_width"><?php _e( 'Input Field Width', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_input_width" id="bti_layout_master_input_width" class="regular-text" value="<?php echo esc_attr( $input_width ); ?>" /> px
										<p class="description" style="margin: 0px;"><?php _e( 'Optional. Resizes input field to specific width (fixed).', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_input_height"><?php _e( 'Input Field Height', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_input_height" id="bti_layout_master_input_height" class="regular-text" value="<?php echo esc_attr( $input_height ); ?>" /> px
										<p class="description" style="margin: 0px;"><?php _e( 'Optional. Resizes input field to specific height (fixed).', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_textarea_width"><?php _e( 'Textarea Field Width', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_textarea_width" id="bti_layout_master_textarea_width" class="regular-text" value="<?php echo esc_attr( $textarea_width ); ?>" /> px
										<p class="description" style="margin: 0px;"><?php _e( 'Optional. Resizes textarea field to specific width (fixed).', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_textarea_height"><?php _e( 'Textarea Field Height', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_textarea_height" id="bti_layout_master_textarea_height" class="regular-text" value="<?php echo esc_attr( $textarea_height ); ?>" /> px
										<p class="description" style="margin: 0px;"><?php _e( 'Optional. Resizes textarea field to specific height (fixed).', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Form Submit Button -->
				<div id="ninja_forms_metabox_lm_form_submit_button" class="postbox">
					<span class="item-controls">
						<a class="item-edit metabox-item-edit" title="Edit Menu Item" href="#"><?php _e( 'Edit Menu Item', 'bti_layout_master'); ?></a>
					</span>

					<h3 class="hndle">
						<span><?php _e( 'Form Submit Button', 'bti_layout_master' ); ?></span>
					</h3>

					<div class="inside" <?php echo in_array( 'ninja_forms_metabox_lm_form_submit_button', $tab ) ? '' : 'style="display:none;"'; ?>>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_bg_color"><?php _e( 'Background Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_bg_color" id="bti_layout_master_button_bg_color" class="bti-layer-master-color" value="<?php echo esc_attr( $button_bg_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_color"><?php _e( 'Text Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_color" id="bti_layout_master_button_color" class="bti-layer-master-color" value="<?php echo esc_attr( $button_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_font_size"><?php _e( 'Font Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_font_size" id="bti_layout_master_button_font_size" class="regular-text" value="<?php echo esc_attr( $button_font_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_border_color"><?php _e( 'Button Border Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_border_color" id="bti_layout_master_button_border_color" class="bti-layer-master-color" value="<?php echo esc_attr( $button_border_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_border_size"><?php _e( 'Button Border Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_border_size" id="bti_layout_master_button_border_size" class="regular-text" value="<?php echo esc_attr( $button_border_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_border_radius"><?php _e( 'Button Border Radius', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_border_radius" id="bti_layout_master_button_border_radius" class="regular-text" value="<?php echo esc_attr( $button_border_radius ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_top_bottom_padding"><?php _e( 'Button Padding (Top and Bottom)', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_top_bottom_padding" id="bti_layout_master_button_top_bottom_padding" class="regular-text" value="<?php echo esc_attr( $button_top_bottom_padding ); ?>" /> px
										<p class="description"><?php _e( 'Leave "0" (without quotes) to keep default padding. ', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_left_right_padding"><?php _e( 'Button Padding (Left and Right)', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_left_right_padding" id="bti_layout_master_button_left_right_padding" class="regular-text" value="<?php echo esc_attr( $button_left_right_padding ); ?>" /> px
										<p class="description"><?php _e( 'Leave "0" (without quotes) to keep default padding. ', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
							</tbody>
						</table>

						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_hover_bg_color"><?php _e( 'Hover Background Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_hover_bg_color" id="bti_layout_master_button_hover_bg_color" class="bti-layer-master-color" value="<?php echo esc_attr( $button_hover_bg_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_hover_color"><?php _e( 'Hover Text Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_hover_color" id="bti_layout_master_button_hover_color" class="bti-layer-master-color" value="<?php echo esc_attr( $button_hover_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_hover_font_size"><?php _e( 'Hover Font Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_hover_font_size" id="bti_layout_master_button_hover_font_size" class="regular-text" value="<?php echo esc_attr( $button_hover_font_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_hover_border_color"><?php _e( 'Hover Border Color', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_hover_border_color" id="bti_layout_master_button_hover_border_color" class="bti-layer-master-color" value="<?php echo esc_attr( $button_hover_border_color ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_hover_border_size"><?php _e( 'Hover Border Size', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_hover_border_size" id="bti_layout_master_button_hover_border_size" class="regular-text" value="<?php echo esc_attr( $button_hover_border_size ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_hover_border_radius"><?php _e( 'Hover Border Radius', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_hover_border_radius" id="bti_layout_master_button_hover_border_radius" class="regular-text" value="<?php echo esc_attr( $button_hover_border_radius ); ?>" /> px
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_hover_top_bottom_padding"><?php _e( 'Button Hover Padding (Top and Bottom)', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_hover_top_bottom_padding" id="bti_layout_master_button_hover_top_bottom_padding" class="regular-text" value="<?php echo esc_attr( $button_hover_top_bottom_padding ); ?>" /> px
										<p class="description"><?php _e( 'Leave "0" (without quotes) to keep default padding. ', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_button_hover_left_right_padding"><?php _e( 'Button Padding (Left and Right)', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="text" name="bti_layout_master_button_hover_left_right_padding" id="bti_layout_master_button_hover_left_right_padding" class="regular-text" value="<?php echo esc_attr( $button_hover_left_right_padding ); ?>" /> px
										<p class="description"><?php _e( 'Leave "0" (without quotes) to keep default padding. ', 'bti_layout_master' ); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Custom CSS -->
				<div id="ninja_forms_metabox_lm_custom_css" class="postbox">
					<span class="item-controls">
						<a class="item-edit metabox-item-edit" title="Edit Menu Item" href="#"><?php _e( 'Edit Menu Item', 'bti_layout_master'); ?></a>
					</span>

					<h3 class="hndle">
						<span><?php _e( 'Custom CSS', 'bti_layout_master' ); ?></span>
					</h3>

					<div class="inside" <?php echo in_array( 'ninja_forms_metabox_lm_custom_css', $tab ) ? '' : 'style="display:none;"'; ?>>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_enable_custom_css"><?php _e( 'Enable Custom CSS', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="bti_layout_master_enable_custom_css" id="bti_layout_master_enable_custom_css" <?php checked( $enable_custom_css ); ?> />
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_custom_css"><?php _e( 'Custom CSS', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<textarea name="bti_layout_master_custom_css" rows="10" cols="50" id="bti_layout_master_custom_css" class="large-text code"><?php echo stripslashes( esc_textarea( $custom_css ) ); ?></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="bti_layout_master_import_css"><?php _e( 'Import Form Styles', 'bti_layout_master' ); ?></label>
									</th>
									<td>
										<textarea name="bti_layout_master_import_css" rows="10" cols="50" id="bti_layout_master_import_css" class="large-text code"><?php echo stripslashes( esc_textarea( $import_css ) ); ?></textarea>
										<p class="description">
											<?php _e( 'Use clipboard button to copy current form styles into clipbord then open a form you are willing to paste the form styles and use CTRL+V (Windows) or CMD+V (Mac) to paste style information into Import CSS field (above).', 'bti_layout_master'); ?>
											<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="110" height="14" id="clippy">
												<param name="movie" value="<?php echo BTI_LAYOUT_MASTER_PLUGIN_URL; ?>/clippy/build/clippy.swf"/>
												<param name="allowScriptAccess" value="always" />
												<param name="quality" value="high" />
												<param name="scale" value="noscale" />
												<param NAME="FlashVars" value="text=<?php echo $export_css; ?>">
												<param name="bgcolor" value="#ffffff">
												<embed src="<?php echo BTI_LAYOUT_MASTER_PLUGIN_URL; ?>/clippy/build/clippy.swf" width="110" height="14" name="clippy" quality="high" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" FlashVars="text=<?php echo $export_css; ?>" bgcolor="#ffffff" />
											</object>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

			</div>

		<?php
	}

	/**
	 * Checks if variable isset() and returns value or default value provided.
	 *
	 * @since 1.7.0
	 */
	function get_default_value( $lm, $var, $default ) {
		if ( isset( $lm[$var] ) ) {
			return $lm[$var];
		}

		return $default;
	}

	/**
	 * Saves style settings
	 *
	 * @since 1.0.0
	 */
	function save_styles_page( $form_id, $data ){

		global $wpdb;

		// Get the form
		$form = ninja_forms_get_form_by_id( $form_id );

		// Basic Settings
		$form['data']['bti_layout_master']['enable_css']         = ( isset( $data['bti_layout_master_enable_css'] ) && $data['bti_layout_master_enable_css'] == 'on' ) ? 1 : 0;
		$form['data']['bti_layout_master']['hide_required_note'] = ( isset( $data['bti_layout_master_hide_required_note'] ) && $data['bti_layout_master_hide_required_note'] == 'on' ) ? 1 : 0;
		$form['data']['bti_layout_master']['form_class']         = sanitize_text_field( $data['bti_layout_master_form_class'] );

		// Form
		$form['data']['bti_layout_master']['background']    = sanitize_text_field( $data['bti_layout_master_background'] );
		$form['data']['bti_layout_master']['border_color']  = sanitize_text_field( $data['bti_layout_master_border_color'] );
		$form['data']['bti_layout_master']['border_size']   = intval( sanitize_text_field( $data['bti_layout_master_border_size'] ) );
		$form['data']['bti_layout_master']['border_radius'] = intval( sanitize_text_field( $data['bti_layout_master_border_radius'] ) );
		$form['data']['bti_layout_master']['form_padding']  = intval( sanitize_text_field( $data['bti_layout_master_form_padding'] ) );
		$form['data']['bti_layout_master']['field_padding'] = intval( sanitize_text_field( $data['bti_layout_master_field_padding'] ) );
		$form['data']['bti_layout_master']['width']         = sanitize_text_field( $data['bti_layout_master_width'] );
		$form['data']['bti_layout_master']['height']        = sanitize_text_field( $data['bti_layout_master_height'] );

		// Form Success Message
		$form['data']['bti_layout_master']['success_message_text_color']    = sanitize_text_field( $data['bti_layout_master_success_message_text_color'] );
		$form['data']['bti_layout_master']['success_message_font_size']     = intval( sanitize_text_field( $data['bti_layout_master_success_message_font_size'] ) );
		$form['data']['bti_layout_master']['success_message_bg_color']      = sanitize_text_field( $data['bti_layout_master_success_message_bg_color'] );
		$form['data']['bti_layout_master']['success_message_border_color']  = sanitize_text_field( $data['bti_layout_master_success_message_border_color'] );
		$form['data']['bti_layout_master']['success_message_border_size']   = intval( sanitize_text_field( $data['bti_layout_master_success_message_border_size'] ) );
		$form['data']['bti_layout_master']['success_message_border_radius'] = intval( sanitize_text_field( $data['bti_layout_master_success_message_border_radius'] ) );
		$form['data']['bti_layout_master']['success_message_padding']       = intval( sanitize_text_field( $data['bti_layout_master_success_message_padding'] ) );

		// Form Error Message
		$form['data']['bti_layout_master']['error_message_text_color']    = sanitize_text_field( $data['bti_layout_master_error_message_text_color'] );
		$form['data']['bti_layout_master']['error_message_font_size']     = intval( sanitize_text_field( $data['bti_layout_master_error_message_font_size'] ) );
		$form['data']['bti_layout_master']['error_message_bg_color']      = sanitize_text_field( $data['bti_layout_master_error_message_bg_color'] );
		$form['data']['bti_layout_master']['error_message_border_color']  = sanitize_text_field( $data['bti_layout_master_error_message_border_color'] );
		$form['data']['bti_layout_master']['error_message_border_size']   = intval( sanitize_text_field( $data['bti_layout_master_error_message_border_size'] ) );
		$form['data']['bti_layout_master']['error_message_border_radius'] = intval( sanitize_text_field( $data['bti_layout_master_error_message_border_radius'] ) );
		$form['data']['bti_layout_master']['error_message_padding']       = intval( sanitize_text_field( $data['bti_layout_master_error_message_padding'] ) );

		// Form Labels
		$form['data']['bti_layout_master']['label_color']     = sanitize_text_field( $data['bti_layout_master_label_color'] );
		$form['data']['bti_layout_master']['label_req_color'] = sanitize_text_field( $data['bti_layout_master_label_req_color'] );
		$form['data']['bti_layout_master']['label_font_size'] = intval( sanitize_text_field( $data['bti_layout_master_label_font_size'] ) );
		$form['data']['bti_layout_master']['label_width']     = sanitize_text_field( $data['bti_layout_master_label_width'] );
		$form['data']['bti_layout_master']['label_height']    = sanitize_text_field( $data['bti_layout_master_label_height'] );

		// Form Fields
		$form['data']['bti_layout_master']['input_color']              = sanitize_text_field( $data['bti_layout_master_input_color'] );
		$form['data']['bti_layout_master']['input_error_color']        = sanitize_text_field( $data['bti_layout_master_input_error_color'] );
		$form['data']['bti_layout_master']['input_border_error_color'] = sanitize_text_field( $data['bti_layout_master_input_border_error_color'] );
		$form['data']['bti_layout_master']['input_font_size']          = intval( sanitize_text_field( $data['bti_layout_master_input_font_size'] ) );
		$form['data']['bti_layout_master']['input_border_color']       = sanitize_text_field( $data['bti_layout_master_input_border_color'] );
		$form['data']['bti_layout_master']['input_border_size']        = intval( sanitize_text_field( $data['bti_layout_master_input_border_size'] ) );
		$form['data']['bti_layout_master']['input_border_radius']      = intval( sanitize_text_field( $data['bti_layout_master_input_border_radius'] ) );
		$form['data']['bti_layout_master']['input_width']              = sanitize_text_field( $data['bti_layout_master_input_width'] );
		$form['data']['bti_layout_master']['input_height']             = sanitize_text_field( $data['bti_layout_master_input_height'] );
		$form['data']['bti_layout_master']['textarea_width']           = sanitize_text_field( $data['bti_layout_master_textarea_width'] );
		$form['data']['bti_layout_master']['textarea_height']          = sanitize_text_field( $data['bti_layout_master_textarea_height'] );

		// Form Submit Button
		$form['data']['bti_layout_master']['button_color']              = sanitize_text_field( $data['bti_layout_master_button_color'] );
		$form['data']['bti_layout_master']['button_font_size']          = intval( sanitize_text_field( $data['bti_layout_master_button_font_size'] ) );
		$form['data']['bti_layout_master']['button_bg_color']           = sanitize_text_field( $data['bti_layout_master_button_bg_color'] );
		$form['data']['bti_layout_master']['button_border_color']       = sanitize_text_field( $data['bti_layout_master_button_border_color'] );
		$form['data']['bti_layout_master']['button_border_size']        = intval( sanitize_text_field( $data['bti_layout_master_button_border_size'] ) );
		$form['data']['bti_layout_master']['button_border_radius']      = intval( sanitize_text_field( $data['bti_layout_master_button_border_radius'] ) );
		$form['data']['bti_layout_master']['button_top_bottom_padding'] = intval( sanitize_text_field( $data['bti_layout_master_button_top_bottom_padding'] ) );
		$form['data']['bti_layout_master']['button_left_right_padding'] = intval( sanitize_text_field( $data['bti_layout_master_button_left_right_padding'] ) );

		$form['data']['bti_layout_master']['button_hover_color']              = sanitize_text_field( $data['bti_layout_master_button_hover_color'] );
		$form['data']['bti_layout_master']['button_hover_font_size']          = intval( sanitize_text_field( $data['bti_layout_master_button_hover_font_size'] ) );
		$form['data']['bti_layout_master']['button_hover_bg_color']           = sanitize_text_field( $data['bti_layout_master_button_hover_bg_color'] );
		$form['data']['bti_layout_master']['button_hover_border_color']       = sanitize_text_field( $data['bti_layout_master_button_hover_border_color'] );
		$form['data']['bti_layout_master']['button_hover_border_size']        = intval( sanitize_text_field( $data['bti_layout_master_button_hover_border_size'] ) );
		$form['data']['bti_layout_master']['button_hover_border_radius']      = intval( sanitize_text_field( $data['bti_layout_master_button_hover_border_radius'] ) );
		$form['data']['bti_layout_master']['button_hover_top_bottom_padding'] = intval( sanitize_text_field( $data['bti_layout_master_button_hover_top_bottom_padding'] ) );
		$form['data']['bti_layout_master']['button_hover_left_right_padding'] = intval( sanitize_text_field( $data['bti_layout_master_button_hover_left_right_padding'] ) );

		// Advanced Settings
		$form['data']['bti_layout_master']['enable_custom_css']  = ( isset( $data['bti_layout_master_enable_custom_css'] ) && $data['bti_layout_master_enable_custom_css'] == 'on' ) ? 1 : 0;
		$form['data']['bti_layout_master']['custom_css'] = wp_filter_nohtml_kses( $data['bti_layout_master_custom_css'] );

		// Import CSS
		if ( isset( $data['bti_layout_master_import_css'] ) && !empty( $data['bti_layout_master_import_css'] ) ) {
			$import_data = json_decode( base64_decode( $data['bti_layout_master_import_css'] ), true );
			foreach( $import_data as $key => $value ) {
				if ( isset( $form['data']['bti_layout_master'][$key] ) ) {
					$form['data']['bti_layout_master'][$key] = $value;
				}
			}
		}

		// Update settings
		if ( NF_PLUGIN_VERSION >= '2.9' ) {
			// New Ninja Form release
			Ninja_Forms()->form( $form_id )->update_setting(
				'bti_layout_master',
				$form['data']['bti_layout_master']
			);
		} else {
			// Save functionality for < Ninja Forms 2.9 versions
			$wpdb->update( NINJA_FORMS_TABLE_NAME, array( 'data' => serialize( $form['data'] ) ), array( 'id' => $form_id ) );
		}

	}

}
