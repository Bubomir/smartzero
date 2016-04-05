<?php

class FrmSigAppController{
    function __construct(){
        add_action( 'init', 'FrmSigAppController::load_hooks', 9 );
        add_action( 'admin_init', 'FrmSigAppController::include_updater', 1 );
    }

    public static function load_hooks(){
        add_filter( 'frm_pre_display_form', 'FrmSigAppController::register_scripts' );
        add_action( 'wp_footer', 'FrmSigAppController::footer_js', 20 );
        add_action( 'admin_footer', 'FrmSigAppController::footer_js', 20 );
        add_filter( 'frm_pro_available_fields', 'FrmSigAppController::add_field' );
        add_filter( 'frm_before_field_created', 'FrmSigAppController::set_defaults' );
        add_action( 'frm_display_added_fields', 'FrmSigAppController::admin_field' );
        add_action( 'frm_field_options_form', 'FrmSigAppController::options_form', 10, 3 );
        add_filter( 'frm_update_field_options', 'FrmSigAppController::update', 10, 3 );

        add_filter( 'frm_setup_new_fields_vars', 'FrmSigAppController::check_signature_fields', 20, 2 );
        add_filter( 'frm_setup_edit_fields_vars', 'FrmSigAppController::check_signature_fields', 20, 3 );
        add_action( 'frm_form_fields', 'FrmSigAppController::front_field', 10, 2 );
        add_filter( 'frm_validate_field_entry', 'FrmSigAppController::validate', 9, 3 );
        add_filter( 'frm_email_value', 'FrmSigAppController::email_value', 8, 2 );
        add_filter( 'frmpro_fields_replace_shortcodes', 'FrmSigAppController::custom_display_signature', 10, 4 );
        add_filter( 'frm_display_value_custom', 'FrmSigAppController::check_signature', 10, 3 );
        add_filter( 'frm_display_value', 'FrmSigAppController::display_signature', 10, 3 );
        add_filter( 'frm_csv_value', 'FrmSigAppController::csv_value', 10, 2 );
        add_filter( 'frm_graph_value', 'FrmSigAppController::graph_value', 10, 2 );
        add_action( 'frm_before_destroy_entry', 'FrmSigAppController::delete_images' );
    }

    public static function path(){
        return WP_PLUGIN_DIR .'/formidable-signature';
    }

    public static function register_scripts( $form ){
        if ( is_callable( 'FrmAppHelper::plugin_url' ) ) {
            $url = FrmAppHelper::plugin_url();
        } else if ( defined('FRM_URL') ) {
            $url = FRM_URL;
        } else {
            return $form;
        }

		add_filter( 'frm_ajax_load_scripts', 'FrmSigAppController::ajax_load_scripts' );
		add_filter( 'frm_ajax_load_styles', 'FrmSigAppController::ajax_load_styles' );

        wp_register_script( 'flashcanvas', $url .'-signature/js/flashcanvas.js', array('jquery'), '1.5', true );
        wp_register_script( 'jquery-signaturepad', $url .'-signature/js/jquery.signaturepad.min.js', array('jquery', 'flashcanvas', 'json2'), '2.5.0', true );
        wp_register_script( 'frm-signature', $url .'-signature/js/frm.signature.js', array('jquery-signaturepad') );
        wp_register_style( 'jquery-signaturepad', $url .'-signature/css/jquery.signaturepad.css' );

        return $form;
    }

	/**
	 * Make sure these scripts are loaded on ajax page change if enqueued
	 */
	public static function ajax_load_scripts( $scripts ) {
		$scripts = array_merge( $scripts, array( 'flashcanvas', 'jquery-signaturepad', 'frm-signature' ) );
		return $scripts;
	}

	/**
	 * Make sure these styles are loaded on ajax page change if enqueued
	 */
	public static function ajax_load_styles( $styles ) {
		$styles[] = 'jquery-signaturepad';
		return $styles;
	}

    public static function add_field( $fields ) {
        $fields['signature'] = __( 'Signature', 'frmsig' );
        return $fields;
    }

    public static function set_defaults( $field_data ) {
        if ( $field_data['type'] == 'signature' ) {
            $field_data['name'] = __( 'Signature' , 'frmsig' );
            foreach ( self::get_defaults() as $k => $v ) {
                $field_data['field_options'][ $k ] = $v;
            }
        }

        return $field_data;
    }

    public static function get_defaults(){
        return array(
            'size' => 400, 'max' => 150, 'restrict' => false,
            'label1' => __( 'Draw It', 'frmsig' ),
            'label2' => __( 'Type It', 'frmsig' ),
            'label3' => __( 'Clear', 'frmsig' ),
        );
    }

    public static function admin_field( $field ) {
        if ( $field['type'] != 'signature' ) {
            return;
        }

        $field_name = 'item_meta['. $field['id'] .']';
        include( self::path() .'/views/admin_field.php' );
    }

    public static function options_form( $field, $display, $values ) {
        if ( $field['type'] != 'signature' ) {
            return;
        }

        foreach ( self::get_defaults() as $k => $v ) {
            if ( ! isset( $field[ $k ] ) ) {
                $field[ $k ] = $v;
            }
        }

        include( self::path() .'/views/options_form.php' );
    }

    /**
     * If this form uses ajax, we need all the signature field info in advance
     */
    public static function check_signature_fields ( $values, $field, $entry_id = false ) {
        // if this field is on another page, it will have the type 'hidden'
        if ( $field->type != 'signature' && ! isset( $field->field_options['label1'] ) ) {
            return $values;
        }

        $sig_val = is_array( $values['value'] ) ? ( isset( $values['value']['output'] ) && ( ! empty( $values['value']['output'] ) ) ? $values['value']['output'] : '' ) : $values['value'];

        if ( $entry_id && $values['value'] && ! empty( $sig_val ) ) {
            // the signature has already been saved, so we don't need to load scripts
            return $values;
        }

        global $frm_vars;
        if ( ! is_array( $frm_vars ) ) {
            $frm_vars = array();
        }

        if ( ! isset( $frm_vars['sig_fields'] ) || empty( $frm_vars['sig_fields'] ) ) {
            $frm_vars['sig_fields'] = array();
        }

        $style_settings = self::get_style_settings( $field->form_id  );

        $values['size'] = ( ! empty( $values['size'] ) && $values['size'] > 70 ) ? $values['size'] : 400;

        $frm_vars['sig_fields'][] = array(
            'id'            => $field->id,
            'width'         => $values['size'],
            'bg_color'      => '#'. $style_settings['bg_color'],
            'text_color'    => '#'. $style_settings['text_color'],
            'border_color'  => '#'. $style_settings['border_color'],
        );

        return $values;
    }

    public static function front_field( $field, $field_name ) {
        if ( $field['type'] != 'signature' ) {
            return;
        }

        if ( ! isset( $field['label1'] ) ) {
            $field_obj = FrmField::getOne( $field['id'] );

            foreach ( self::get_defaults() as $k => $v ) {
                if ( ! isset( $field[ $k ] ) ) {
                    $field[ $k ] = isset( $field_obj->field_options[ $k ] ) ? $field_obj->field_options[ $k ] : $v;
                }
            }

            unset($field_obj);
        }

        global $frm_editing_entry, $frm_vars;

        $entry_id = isset($frm_vars['editing_entry']) ? $frm_vars['editing_entry'] : $frm_editing_entry;
        if ( $entry_id ) {
            //make sure entry is for this form
            $entry = FrmEntry::getOne( (int) $entry_id );

            if ( ! $entry || $entry->form_id != $field['form_id'] ) {
                $entry_id = false;
            }
            unset($entry);
        }

        wp_enqueue_script( 'flashcanvas' );
        wp_enqueue_script( 'jquery-signaturepad' );
        wp_enqueue_style( 'jquery-signaturepad' );
        wp_enqueue_script( 'frm-signature' );

        $style_settings = self::get_style_settings( $field['form_id']  );

        $field['value'] = stripslashes_deep($field['value']);

        require(self::path() .'/views/front_field.php');
    }

    public static function footer_js(){
        global $frm_vars;

        if ( ! is_array( $frm_vars ) || ! isset( $frm_vars['sig_fields'] ) || empty( $frm_vars['sig_fields'] ) ) {
            return;
        }

        include_once( self::path() .'/views/footer_js.php' );
    }

    public static function update( $field_options, $field, $values ) {
        if ( $field->type != 'signature' ) {
            return $field_options;
        }

        $defaults = self::get_defaults();
        unset( $defaults['size'], $defaults['max'], $defaults['restrict'] );

        foreach ( $defaults as $opt => $default ) {
            $field_options[ $opt ] = isset( $values['field_options'][ $opt .'_'. $field->id ] ) ? $values['field_options'][ $opt .'_'. $field->id ] : $default;
        }

        return $field_options;
    }

    public static function validate( $errors, $field, $value ) {
        if ( $field->type != 'signature' || $field->required != '1' || isset( $errors['field'. $field->id] ) ) {
            return $errors;
        }

        if ( empty( $value ) || ( empty( $value['output'] ) && empty( $value['typed'] ) ) ) {
            if ( method_exists( 'FrmProFieldsHelper', 'is_field_hidden' ) ) {
                $hidden = FrmProFieldsHelper::is_field_hidden( $field, $_POST );
            } else {
                global $frmpro_field;
                $hidden = $frmpro_field->is_field_hidden( $field, $_POST );
            }

            if ( ! $hidden ) {
                global $frm_settings;
                $errors['field'. $field->id] = ( ! isset( $field->field_options['blank'] ) || empty( $field->field_options['blank'] ) ) ? $frm_settings->blank_msg : $field->field_options['blank'];
            }
        }

        return $errors;
    }

    public static function email_value( $value, $meta ) {
        $field = FrmField::getOne( $meta->field_id );
        if ( ! $field || $field->type != 'signature' ) {
            return $value;
        }

        if ( is_array( $value ) ) {
            $value = isset($value['typed']) ? $value['typed'] : reset( $value );
        }

        return $value;
    }

    public static function custom_display_signature( $value, $tag, $atts, $field ) {
        if ( $field->type != 'signature' ) {
            return $value;
        }

        if ( ! isset( $atts['entry_id'] ) ) {
            return '';
        }

        $value = self::display_signature($value, $field, $atts);
        return $value;
    }

    public static function check_signature( $value, $field, $atts ) {
        if ( $field->type != 'signature' || empty( $value ) ) {
            return $value;
        }

        if ( isset( $value['output'] ) && ! empty( $value['output'] ) ) {
            if ( ! isset( $value['typed'] ) ) {
                $value['typed'] = '';
            }

            $new_value = $value['typed'] .'||'. $value['output'];

            if ( isset( $value['height'] ) ) {
                $new_value .= '||'. $value['height'] .'||'. $value['width'];
            }

            $value = $new_value;
        } else {
            $value = isset( $value['typed'] ) ? $value['typed'] : ( isset( $value['width'] ) ? '' : reset( $value ) );
        }

        return $value;
    }

    public static function display_signature( $value, $field, $atts ) {
        if ( $field->type != 'signature' || empty( $value ) ) {
            return $value;
        }

        if ( ! is_array( $value ) ) {
            $values = explode('||', $value);
            if ( is_array( $values ) && count( $values ) > 1 ) {
                $value = array( 'typed' => $values[0], 'output' => $values[1] );

                if ( count( $values ) > 2 ) {
                    $value['height'] = $values[2];
                    $value['width'] = $values[3];
                }
            }
            unset($values);
        }

        if ( isset( $atts['height'] ) ) {
            $value['height'] = $atts['height'];
        }

        if ( isset( $atts['width'] ) ) {
            if ( isset( $value['width'] ) ) {
                if ( ! isset( $atts['height'] ) ) {
                    $ratio = (int) $atts['width'] / (int) $value['width'];
                    $value['height'] = (int) ( $value['height'] * $ratio );
                }
            }
            $value['width'] = $atts['width'];
        }

        if ( ! is_array( $value ) || ! isset( $value['output'] ) || empty( $value['output'] ) ) {
            return ( is_array( $value ) ) ? ( isset( $value['typed'] ) ? $value['typed'] : '' ) : $value;
        }

        $uploads = wp_upload_dir();
        $target_path = $uploads['basedir'];

        if ( ! file_exists( $target_path ) ) {
            @mkdir( $target_path .'/' );
        }

        $relative_path = apply_filters( 'frm_sig_upload_folder', 'formidable/signatures' );
        $relative_path = untrailingslashit( $relative_path );
        $folders = explode( '/', $relative_path );

        foreach ( $folders as $folder ) {
            $target_path .= '/'. $folder;
            if ( ! file_exists( $target_path ) ) {
                @mkdir( $target_path .'/') ;
            }
        }

        $file_name = 'signature-'. $field->id .'-'. $atts['entry_id'] .'.png';
        $file = $target_path . '/'. $file_name;

        //if(file_exists($file))
        //    unlink($file); //delete the file to rebuild

        if ( ! file_exists( $file ) ) {
            require_once( self::path() .'/signature-to-image.php' );

            $options = array();
            if ( isset( $value['height'] ) && ! empty( $value['height'] ) && is_numeric( $value['height'] ) ) {
                $options['imageSize'] = array( (int) $value['width'], (int) $value['height'] );
                $options['drawMultiplier'] = apply_filters( 'frm_sig_multiplier', 5, $field, $value );
            }

            $img = sigJsonToImage( $value['output'], $options );
            if ( $img ) {
                imagepng( $img, $file );
                imagedestroy( $img );
            }
        }

        return  '<img src="'. esc_attr( str_replace( $uploads['basedir'], $uploads['baseurl'], $file ) ) .'" alt="'. ( isset( $value['typed'] ) ? esc_attr( $value['typed'] ) : '' ) .'" />';
    }

    public static function csv_value( $value, $atts ) {
        if ( $atts['field']->type != 'signature' ) {
            return $value;
        }

        if ( is_array($value) ) {
            $value = isset( $value['typed'] ) ? $value['typed'] : '';
        }

        return $value;
    }

    public static function graph_value( $values, $field ) {
        if ( ! is_object( $field ) || $field->type != 'signature' ) {
            return $values;
        }

        foreach ( $values as $k => $v ) {
            if ( is_array($v) ) {
                $values[ $k ] = isset( $v['typed'] ) ? $v['typed'] : reset($v);
            }
            unset($k, $v);
        }

        return $values;
    }

    public static function delete_images( $entry_id ) {
        global $wpdb;

        $fields = $wpdb->get_col( $wpdb->prepare("SELECT fi.id FROM {$wpdb->prefix}frm_fields fi LEFT JOIN {$wpdb->prefix}frm_items it ON (it.form_id=fi.form_id) WHERE fi.type=%s AND it.id=%d", 'signature', $entry_id) );

        if ( ! $fields ) {
            return;
        }

        $uploads = wp_upload_dir();
        $target_path = $uploads['basedir'] .'/'; 
        $target_path .= apply_filters( 'frm_sig_upload_folder', 'formidable/signatures' );
        $target_path = untrailingslashit( $target_path );

        foreach ( $fields as $field ) {
            $file = $target_path . '/signature-'. $field .'-'. $entry_id .'.png';
            if ( file_exists( $file ) ) {
                //delete it
                unlink( $file );
            }
            unset( $field );
        }
    }

    public static function include_updater(){
        include_once( self::path() .'/models/FrmSigUpdate.php' );
        $frm_sig_update = new FrmSigUpdate();
    }

    private static function get_style_settings( $form_id ) {
        if ( is_callable( 'FrmStylesController::get_form_style' ) ) {
            $style_settings = FrmStylesController::get_form_style( $form_id );
            $style_settings = $style_settings->post_content;
        } else {
            global $frmpro_settings;
            if ( ! $frmpro_settings && class_exists('FrmProSettings') ) {
                $frmpro_settings = new FrmProSettings();
            }
            $style_settings = (array) $frmpro_settings;
        }
        return $style_settings;
    }
}