<?php

class FrmHrsSettingsController{
    function FrmHrsSettingsController(){
        add_action('frm_add_settings_section', 'FrmHrsSettingsController::add_settings_section');
        add_action('frm_add_form_settings_section', 'FrmHrsSettingsController::add_options', 10);
        //add_action('frm_add_form_option_section', 'FrmHrsSettingsController::options');
        add_action('wp_ajax_frm_hrs_add_tag_row', 'FrmHrsSettingsController::add_tag_row');
        add_action('wp_ajax_frm_hrs_add_logic_row', 'FrmHrsSettingsController::add_logic_row');
        add_action('wp_ajax_frm_hrs_get_field_values', 'FrmHrsSettingsController::get_field_values');
        
        add_filter('frm_setup_new_form_vars', 'FrmHrsSettingsController::setup_new_vars');
        add_filter('frm_setup_edit_form_vars', 'FrmHrsSettingsController::setup_edit_vars');
        add_filter('frm_form_options_before_update', 'FrmHrsSettingsController::update_options', 15, 2);
    }

    public static function add_settings_section($sections){
        $sections['highrise'] = array('class' => 'FrmHrsSettingsController', 'function' => 'route');
        return $sections;
    }
    
    public static function add_options($sections){
        $sections['highrise'] = array('class' => 'FrmHrsSettingsController', 'function' => 'options');
        return $sections;
    }
    
    public static function options($values){
        if(!class_exists('FrmHighriseAPI'))
            require_once(FrmHrsAppHelper::plugin_path() . '/highrise-API.php');
        
        $frm_hrs_settings = new FrmHrsSettings();
        $api = new FrmHighriseAPI();
        $api->account = $frm_hrs_settings->settings->account;
        $api->token = $frm_hrs_settings->settings->token;
        unset($frm_hrs_settings);
        
        $all_fields = array(
            'first_name', 'last_name', 'title', 'company_name', 'email_address', 
            'instant_messenger', 'twitter_account', 'web_address', 'address', 'phone_number'
        );
        
        $list_fields = FrmHrsAppHelper::get_field_details($all_fields);
        
        $custom_fields = $api->getXMLObjectForUrl('/subject_fields.xml');
        if(isset($custom_fields->{'subject-field'})){
            foreach($custom_fields->{'subject-field'} as $c){
                $list_fields[] = array('tag' => (int)$c->id, 'name' => $c->label, 'multi' => false);
                unset($custom_field);
            }
        }
        
        $frm_field = new FrmField();
        $form_fields = $frm_field->getAll("fi.form_id='". $values['id'] ."' and fi.type not in ('break', 'divider', 'html', 'captcha', 'form')", 'field_order');
        unset($frm_field);
        
        $hide_highrise = ($values['highrise']) ? '' : 'style="display:none;"';
        $show_add = $tag_count = array();
        
        if(method_exists('FrmAppHelper', 'plugin_version'))
            $frm_version = FrmAppHelper::plugin_version();
        else
            global $frm_version; //version fallback < v1.07.02
        
        include(FrmHrsAppHelper::plugin_path() .'/views/settings/options.php');
    }
    
    public static function add_tag_row(){
        $form_id = (int)$_POST['form_id'];
        $meta_name = $_POST['meta_name'];
        $list_fields = FrmHrsAppHelper::get_field_details(array($_POST['tag']));
        $list_field = reset($list_fields);
        $show_add = array($_POST['tag']);
        $hide_highrise = '';
        
        $frm_field = new FrmField();
        $form_fields = $frm_field->getAll("fi.form_id='". $form_id ."' and fi.type not in ('break', 'divider', 'html', 'captcha', 'form')", 'field_order');
        unset($frm_field);

        $values = array('hrs_list' => array($_POST['tag']));

        include(FrmHrsAppHelper::plugin_path() .'/views/settings/_tag_row.php');
        
        die();
    }
    
    public static function add_logic_row(){
        global $wpdb;
            
        $form_id = (int)$_POST['form_id'];
        $meta_name = $_POST['meta_name'];
        $hide_field = '';
        
        $frm_field = new FrmField();
        $form_fields = $frm_field->getAll("fi.form_id = ". $form_id ." and (type in ('select','radio','checkbox','10radio','scale','data') or (type = 'data' and (field_options LIKE '\"data_type\";s:6:\"select\"%' OR field_options LIKE '%\"data_type\";s:5:\"radio\"%' OR field_options LIKE '%\"data_type\";s:8:\"checkbox\"%') ))", " ORDER BY field_order");
        unset($frm_field);

        $values = $wpdb->get_var($wpdb->prepare("SELECT options FROM {$wpdb->prefix}frm_forms WHERE id=%d", $form_id));
        $values = maybe_unserialize($values);
        if(!isset($values['hrs_list']))
            $values['hrs_list'] = array('hide_field' => array(), 'hide_field_cond' => array(), 'hide_opt' => array());
        
        if(!isset($values['hrs_list']['hide_field_cond'][$meta_name]))
            $values['hrs_list']['hide_field_cond'][$meta_name] = '==';
            
        include(FrmHrsAppHelper::plugin_path() .'/views/settings/_logic_row.php');
        
        die();
    }
    
    public static function get_field_values(){
        global $wpdb;
        
        $form_id = (int)$_POST['form_id'];
        $meta_name = $_POST['meta_name'];
        
        $frm_field = new FrmField();
        $new_field = $frm_field->getOne($_POST['field_id']);
        unset($frm_field);
        
        $values = $wpdb->get_var($wpdb->prepare("SELECT options FROM {$wpdb->prefix}frm_forms WHERE id=%d", $form_id));
        $values = maybe_unserialize($values);
        if(!isset($values['hrs_list']))
            $values['hrs_list'] = array('hide_field' => array(), 'hide_field_cond' => array(), 'hide_opt' => array());
            
        require(FrmHrsAppHelper::plugin_path() .'/views/settings/_field_values.php');
        die();
    }
    
    public static function setup_new_vars($values){
        $defaults = FrmHrsAppHelper::get_default_options();
        foreach ($defaults as $opt => $default){
            $values[$opt] = FrmAppHelper::get_param($opt, $default);
            unset($default);
            unset($opt);
        }
        return $values;
    }
    
    public static function setup_edit_vars($values){
        $defaults = FrmHrsAppHelper::get_default_options();
        foreach ($defaults as $opt => $default){
            if (!isset($values[$opt]))
                $values[$opt] = ($_POST and isset($_POST['options'][$opt])) ? $_POST['options'][$opt] : $default;
            unset($default);
            unset($opt);
        }
        
        if(isset($_POST) and isset($_POST['options']['hrs_list']))
            $values['hrs_list'] = $_POST['options']['hrs_list'];

        return $values;
    }
    
    public static function update_options($options, $values){
        $defaults = FrmHrsAppHelper::get_default_options();
        
        foreach($defaults as $opt => $default){
            $options[$opt] = (isset($values['options'][$opt])) ? $values['options'][$opt] : $default;
            unset($default);
            unset($opt);
        }

        unset($defaults);
        
        return $options;
    }
    
    public static function display_form($errors=array(), $message=''){
        $frm_hrs_settings = new FrmHrsSettings();

        if(method_exists('FrmAppHelper', 'plugin_version'))
            $frm_version = FrmAppHelper::plugin_version();
        else
            global $frm_version; //version fallback < v1.07.02
        
        include(FrmHrsAppHelper::plugin_path() . '/views/settings/form.php');
    }

    public static function process_form(){
        $frm_hrs_settings = new FrmHrsSettings();
		
        //$errors = $frm_hrs_settings->validate($_POST,array());
        $errors = array();
        
        $frm_hrs_settings->update($_POST);

        if( empty($errors) ){
            $frm_hrs_settings->store();
            $message = __('Settings Saved', 'formidable');
        }
            
        self::display_form($errors, $message);
    }

    public static function route(){
        $action = FrmAppHelper::get_param('action');
        if($action == 'process-form')
            return self::process_form();
        else
            return self::display_form();
    }
}