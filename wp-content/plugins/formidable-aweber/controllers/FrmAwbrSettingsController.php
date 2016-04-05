<?php

class FrmAwbrSettingsController{
    function FrmAwbrSettingsController(){
        add_action('frm_add_settings_section', 'FrmAwbrSettingsController::add_settings_section');
        add_action('frm_add_form_settings_section', 'FrmAwbrSettingsController::add_aweber_options', 10);
        //add_action('frm_add_form_option_section', 'FrmAwbrSettingsController::aweber_options');
        add_action('wp_ajax_frm_awbr_add_list', 'FrmAwbrSettingsController::add_list');
        add_action('wp_ajax_frm_awbr_match_fields', 'FrmAwbrSettingsController::match_fields');
        add_action('wp_ajax_frm_awbr_add_logic_row', 'FrmAwbrSettingsController::add_logic_row');
        add_action('wp_ajax_frm_awbr_get_field_values', 'FrmAwbrSettingsController::get_field_values');
        
        add_filter('frm_setup_new_form_vars', 'FrmAwbrSettingsController::setup_new_vars');
        add_filter('frm_setup_edit_form_vars', 'FrmAwbrSettingsController::setup_edit_vars');
        add_filter('frm_form_options_before_update', 'FrmAwbrSettingsController::update_options', 15, 2);
    }

    public static function add_settings_section($sections){
        $sections['aweber'] = array('class' => 'FrmAwbrSettingsController', 'function' => 'route');
        return $sections;
    }
    
    public static function add_aweber_options($sections){
        $sections['aweber'] = array('class' => 'FrmAwbrSettingsController', 'function' => 'aweber_options');
        return $sections;
    }
    
    public static function match_fields(){
        $form_id = (isset($_POST['form_id'])) ? $_POST['form_id'] : false;
        $list_id = (isset($_POST['list_id'])) ? $_POST['list_id'] : false;
        if(!(int)$form_id or !$list_id)
            die;
        
        if(!class_exists('FrmAWeberAPI'))
            require_once(FrmAwbrAppController::path() . '/aweber_api/aweber.php');
        
        try{
            $frm_awbr_settings = new FrmAwbrSettings();
            $aweber = new FrmAWeberAPI($frm_awbr_settings->settings->consumer_key, $frm_awbr_settings->settings->consumer_secret);
            $account = $aweber->getAccount($frm_awbr_settings->settings->access_key, $frm_awbr_settings->settings->access_secret);
            unset($frm_awbr_settings);

            $list = $account->loadFromUrl("/accounts/{$account->id}/lists/{$list_id}");
            $list_fields = $list->custom_fields->data['entries'];
        }
        
        catch (Exception $exc) {
            #List ID was not in this account
            $error = __('Your AWeber account info is not correct.', 'formidable');
            $error .= ' <a href="'. admin_url('admin.php?page=formidable-settings') .'">'. __('Update it now.', 'formidable') .'</a>';
        }
        
        $frm_field = new FrmField();
        $form_fields = $frm_field->getAll("fi.form_id='$form_id' and fi.type not in ('break', 'divider', 'html', 'captcha', 'form')");
        unset($frm_field);
        
        $hide_aweber = '';

        include(FrmAwbrAppController::path() .'/views/settings/_match_fields.php');
        die();
    }
    
    public static function aweber_options($values){
        if($values['aweber']){
            if(!class_exists('FrmAWeberAPI'))
                require_once(FrmAwbrAppController::path() . '/aweber_api/aweber.php');

            try{
                $frm_awbr_settings = new FrmAwbrSettings();
                $aweber = new FrmAWeberAPI($frm_awbr_settings->settings->consumer_key, $frm_awbr_settings->settings->consumer_secret);
                $account = $aweber->getAccount($frm_awbr_settings->settings->access_key, $frm_awbr_settings->settings->access_secret);
                unset($frm_awbr_settings);

                $lists = $account->lists;
            }

            catch (Exception $exc) {
                #List ID was not in this account
                $error = __('Your AWeber account info is not correct.', 'formidable');
                $error .= ' <a href="'. admin_url('admin.php?page=formidable-settings') .'">'. __('Update it now.', 'formidable') .'</a>';
            }

            if(!empty($values['awbr_list'])){
                $frm_field = new FrmField();
                $form_fields = $frm_field->getAll("fi.form_id='". $values['id'] ."' and fi.type not in ('break', 'divider', 'html', 'captcha', 'form')");
                unset($frm_field);
            }
        }
        
        if(method_exists('FrmAppHelper', 'plugin_version'))
            $frm_version = FrmAppHelper::plugin_version();
        else
            global $frm_version; //version fallback < v1.07.02
         
        include_once(FrmAwbrAppController::path() .'/views/settings/aweber_options.php');
    }
    
    public static function add_list($list_id=false, $hide_aweber=''){
        $frm_awbr_settings = new FrmAwbrSettings();
        
        if(empty($frm_awbr_settings->settings->consumer_key) or empty($frm_awbr_settings->settings->consumer_secret))
            return;
        
        $die = ($list_id) ? false : true;
        if(!$list_id and isset($_POST) and isset($_POST['list_id']))
            $list_id = $_POST['list_id'];
           
        if(!class_exists('FrmAWeberAPI'))
            require_once(FrmAwbrAppController::path() . '/aweber_api/aweber.php');
            
        $aweber = new FrmAWeberAPI($frm_awbr_settings->settings->consumer_key, $frm_awbr_settings->settings->consumer_secret);
        try {
            $account = $aweber->getAccount($frm_awbr_settings->settings->access_key, $frm_awbr_settings->settings->access_secret);
        } catch (FrmAWeberException $e) {
            $account = false;
        }
        unset($frm_awbr_settings);
        
        if(!$account)
            return;
        
        $lists = $account->lists;
        $list_options = array();
        
        include(FrmAwbrAppController::path() .'/views/settings/_list_options.php');
        
        if($die)
            die();
    }
    
    public static function add_logic_row(){
        global $wpdb;
        
        if(!isset($_POST) or !isset($_POST['list_id']))
            die();
            
        $list_id = $_POST['list_id'];
        $form_id = (int)$_POST['form_id'];
        $meta_name = $_POST['meta_name'];
        $hide_field = '';
        
        $frm_field = new FrmField();
        $form_fields = $frm_field->getAll("fi.form_id = ". $form_id ." and (type in ('select','radio','checkbox','10radio','scale','data') or (type = 'data' and (field_options LIKE '\"data_type\";s:6:\"select\"%' OR field_options LIKE '%\"data_type\";s:5:\"radio\"%' OR field_options LIKE '%\"data_type\";s:8:\"checkbox\"%') ))", " ORDER BY field_order");
        unset($frm_fields);

        $form_options = $wpdb->get_var($wpdb->prepare("SELECT options FROM {$wpdb->prefix}frm_forms WHERE id=%d", $form_id));
        $form_options = maybe_unserialize($form_options);
        if(isset($form_options['awbr_list'][$list_id]))
            $list_options = $form_options['awbr_list'][$list_id];
        else
            $list_options = array('hide_field' => array(), 'hide_field_cond' => array(), 'hide_opt' => array());
        
        if(!isset($list_options['hide_field_cond'][$meta_name]))
            $list_options['hide_field_cond'][$meta_name] = '==';
            
        include(FrmAwbrAppController::path() .'/views/settings/_logic_row.php');
        
        die();
    }
    
    public static function get_field_values(){
        global $wpdb;
        
        $list_id = $_POST['list_id'];
        $form_id = (int)$_POST['form_id'];
        
        $frm_field = new FrmField();
        $new_field = $frm_field->getOne($_POST['field_id']);
        unset($frm_field);
        
        $form_options = $wpdb->get_var($wpdb->prepare("SELECT options FROM {$wpdb->prefix}frm_forms WHERE id=%d", $form_id));
        $form_options = maybe_unserialize($form_options);
        if(isset($form_options['awbr_list'][$list_id]))
            $list_options = $form_options['awbr_list'][$list_id];
        else
            $list_options = array('hide_field' => array(), 'hide_field_cond' => array(), 'hide_opt' => array());
            
        require(FrmAwbrAppController::path() .'/views/settings/_field_values.php');
        die();
    }
    
    public static function setup_new_vars($values){
        $defaults = FrmAwbrAppHelper::get_default_options();
        foreach ($defaults as $opt => $default){
            $values[$opt] = FrmAppHelper::get_param($opt, $default);
            unset($default);
            unset($opt);
        }
        return $values;
    }
    
    public static function setup_edit_vars($values){
        $defaults = FrmAwbrAppHelper::get_default_options();
        foreach ($defaults as $opt => $default){
            if (!isset($values[$opt]))
                $values[$opt] = ($_POST and isset($_POST['options'][$opt])) ? $_POST['options'][$opt] : $default;
            unset($default);
            unset($opt);
        }
        
        if(isset($_POST) and isset($_POST['options']['awbr_list']))
            $values['awbr_list'] = $_POST['options']['awbr_list'];

        return $values;
    }
    
    public static function update_options($options, $values){
        $defaults = FrmAwbrAppHelper::get_default_options();
        
        foreach($defaults as $opt => $default){
            $options[$opt] = (isset($values['options'][$opt])) ? $values['options'][$opt] : $default;
            unset($default);
            unset($opt);
        }

        unset($defaults);
        
        return $options;
    }
    
    public static function display_form(){
        $frm_awbr_settings = new FrmAwbrSettings();
        
        if(method_exists('FrmAppHelper', 'plugin_version'))
            $frm_version = FrmAppHelper::plugin_version();
        else
            global $frm_version; //version fallback < v1.07.02

        require_once(FrmAwbrAppController::path() .'/views/settings/form.php');
    }

    public static function process_form(){
        $frm_awbr_settings = new FrmAwbrSettings();

        if(!class_exists('FrmAWeberAPI'))
            require_once(FrmAwbrAppController::path() . '/aweber_api/aweber.php');
        
        $aweber = new FrmAWeberAPI($frm_awbr_settings->settings->consumer_key, $frm_awbr_settings->settings->consumer_secret);
        $message = $error = '';
        $old_key = $frm_awbr_settings->settings->access_key;
        
        $oauth_id = (isset($_POST) and isset($_POST['frm_awbr_oauth_id'])) ? $_POST['frm_awbr_oauth_id'] : '';
        if (!empty($oauth_id) and (empty($frm_awbr_settings->settings->access_secret) or $oauth_id != $frm_awbr_settings->settings->oauth_id)) {
            // Then they just saved a key and didn't remove anything
            // Check it's validity then save it for later use
            try {
                list($consumer_key, $consumer_secret, $access_key, $access_secret) = $aweber->getDataFromAweberID($oauth_id);
            } catch (FrmAWeberException $e) {
                list($consumer_key, $consumer_secret, $access_key, $access_secret) = null;
            } catch (FrmAWeberOAuthException $e) {
                list($consumer_key, $consumer_secret, $access_key, $access_secret) = null;
            }
            if (!$access_secret) {
                $error = __('There was a problem authenticating AWeber', 'formidable');
            } else {
                $_POST['frm_awbr_consumer_key'] = $frm_awbr_settings->settings->consumer_key = $consumer_key;
                $_POST['frm_awbr_consumer_secret'] = $frm_awbr_settings->settings->consumer_secret = $consumer_secret;
                $_POST['frm_awbr_access_key'] = $frm_awbr_settings->settings->access_key = $access_key;
                $_POST['frm_awbr_access_secret'] = $frm_awbr_settings->settings->access_secret = $access_secret;
 
                $frm_awbr_settings->update($_POST);
                $frm_awbr_settings->store();
                
                $aweber = new FrmAWeberAPI($frm_awbr_settings->settings->consumer_key, $frm_awbr_settings->settings->consumer_secret);
            }
        }
        
        if ($frm_awbr_settings->settings->access_key and $frm_awbr_settings->settings->access_key != $old_key){
            try {
                $account = $aweber->getAccount($frm_awbr_settings->settings->access_key, $frm_awbr_settings->settings->access_secret);
            } catch (FrmAWeberException $e) {
                $account = null;
            }
            if (!$account){
                $frm_awbr_settings->update(array('frm_awbr_access_secret' => null));
                $error = __('AWeber Authorization failed', 'formidable');
            }else{
                $message = __('AWeber successfully Authorized', 'formidable');
                $authorize_success = true;
            }
        }
        
        if(method_exists('FrmAppHelper', 'plugin_version'))
            $frm_version = FrmAppHelper::plugin_version();
        else
            global $frm_version; //version fallback < v1.07.02

        require_once(FrmAwbrAppController::path() . '/views/settings/form.php');
    }

    public static function route(){
        $action = FrmAppHelper::get_param('action');
        if($action == 'process-form')
            return self::process_form();
        else
            return self::display_form();
    }
}