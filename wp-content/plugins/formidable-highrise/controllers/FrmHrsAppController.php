<?php

class FrmHrsAppController{
    function FrmHrsAppController(){
        add_action('admin_init', 'FrmHrsAppController::include_updater', 1);
        add_action('frm_entry_form', 'FrmHrsAppController::hidden_form_fields');
        add_action('frm_after_create_entry', 'FrmHrsAppController::send_to_highrise', 25, 2);
    }
    
    public static function include_updater(){
        include_once(FrmHrsAppHelper::plugin_path() .'/models/FrmHrsUpdate.php');
        $obj = new FrmHrsUpdate();
    }
    
    public static function hidden_form_fields($form){
        if(isset($form->options['highrise']) and $form->options['highrise'] and isset($form->options['hrs_list']) and is_array($form->options['hrs_list']))
            echo '<input type="hidden" name="frm_highrise" value="1"/>'."\n";
    }
    
    public static function send_to_highrise($entry_id, $form_id){
        if(!isset($_POST) or !isset($_POST['frm_highrise']))
            return;
        
        global $wpdb;
        
        $form_options = $wpdb->get_var($wpdb->prepare("SELECT options FROM {$wpdb->prefix}frm_forms WHERE id=%d", $form_id));
        $form_options = maybe_unserialize($form_options);
        if(!isset($form_options['highrise']) or !$form_options['highrise'])
            return;
        
        if(!class_exists('FrmHighriseAPI'))
            require_once(FrmHrsAppHelper::plugin_path() . '/highrise-API.php');
        
        $frm_hrs_settings = new FrmHrsSettings();
        $api = new FrmHighriseAPI();
        $api->account = $frm_hrs_settings->settings->account;
        $api->token = $frm_hrs_settings->settings->token;
        unset($frm_hrs_settings);
        
		// enabling highrise debug
		//$api->debug = true;
            

        //check conditions
        $subscribe = true;
        if(isset($form_options['hrs_list']['hide_field']) and is_array($form_options['hrs_list']['hide_field']) and class_exists('FrmProFieldsHelper')){
            //for now we are assuming that if all conditions are met, then the user will be subscribed
            foreach($form_options['hrs_list']['hide_field'] as $hide_key => $hide_field){
                if(!$subscribe)
                    break;
                        
                $observed_value = (isset($_POST['item_meta'][$hide_field])) ? $_POST['item_meta'][$hide_field] : '';
                    
                $subscribe = FrmProFieldsHelper::value_meets_condition($observed_value, $form_options['hrs_list']['hide_field_cond'][$hide_key], $form_options['hrs_list']['hide_opt'][$hide_key]);
                    
            }
        }
            
        if(!$subscribe) //don't subscribe if conditional logic is not met
            return;

        $person = new HighrisePerson($api);
        foreach($form_options['hrs_list'] as $field_tag => $field_info){
            if(in_array($field_tag, array('hide_field', 'hide_field_cond', 'hide_opt')))
                continue;

            if($field_tag != 'tags' and isset($field_info['tag']) and !is_array($field_info['tag']))
               $field_val = self::get_field_value($field_info['tag'], $field_tag, $entry_id);
               
            switch($field_tag){
                case 'first_name':
                    $person->setFirstName($field_val);
                break;
                case 'last_name':
                    $person->setLastName($field_val);
                break;
                case 'title':
                    $person->setTitle($field_val);
                break;
                case 'company_name':
                    $person->setCompanyName($field_val);
                break;
                case 'email_address':
                    foreach($field_info['tag'] as $tkey => $field_id){
                        $field_val = self::get_field_value($field_id, $field_tag, $entry_id);
                        if(!empty($field_val))
                            $person->addEmailAddress($field_val, $field_info['location'][$tkey]);
                    }
                break;
                case 'instant_messenger':
                    foreach($field_info['tag'] as $tkey => $field_id){
                        $field_val = self::get_field_value($field_id, $field_tag, $entry_id);
                        if(!empty($field_val))
                            $person->addInstantMessenger($field_info['protocol'][$tkey], $field_val, $field_info['location'][$tkey]);
                    }
                break;
                case 'twitter_account':
                    foreach($field_info['tag'] as $tkey => $field_id){
                        $field_val = self::get_field_value($field_id, $field_tag, $entry_id);
                        if(!empty($field_val))
                            $person->addTwitterAccount($field_val, $field_info['location'][$tkey]);
                    }
                break;
                case 'web_address':
                    foreach($field_info['tag'] as $tkey => $field_id){
                        $field_val = self::get_field_value($field_id, $field_tag, $entry_id);
                        if(!empty($field_val))
                            $person->addWebAddress($field_val, $field_info['location'][$tkey]);
                    }
                break;
                case 'address':
                    foreach($field_info['tag'] as $tkey => $field_id){
                        $field_val = self::get_field_value($field_id, $field_tag, $entry_id);
                        if(!empty($field_val)){
                            $address = new HighriseAddress();
                            $address->setStreet($field_val);
                            //$address->setCity("Glasgow");
                            //$address->setCountry("Scotland");
                            //$address->setZip("GL1");
                            $address->setLocation($field_info['location'][$tkey]);
                            $person->addAddress($address);
                            unset($address);
                        }
                    }
                break;
                case 'phone_number':
                    foreach($field_info['tag'] as $tkey => $field_id){
                        $field_val = self::get_field_value($field_id, $field_tag, $entry_id);
                        if(!empty($field_val))
                            $person->addPhoneNumber($field_val, $field_info['location'][$tkey]);
                    }
                break;
                case 'tags':
                    if(!empty($field_info)){
                        $tags = explode(',', $field_info);
                        foreach($tags as $t)
                            $person->addTag(trim($t));
                    }
                break;
                case 'background':
                    $person->setBackground($field_info);
                break;
                default:
                    //custom fields
                    if(!empty($field_val)){
                        if(is_array($field_val))
                            $field_val = implode(', ', $field_val);
                        $person->addCustomField($field_tag, $field_val);
                    }
                break;
            }
            
            unset($field_val);
        }
        $person->save();
    }
    
    private static function get_field_value($field_id, $field_tag, $entry_id){
        $val = (isset($_POST['item_meta'][$field_id])) ? $_POST['item_meta'][$field_id] : '';
        if(!is_numeric($val))
            return $val;
        
        $frm_field = new FrmField();
        $field = $frm_field->getOne($field_id);
        unset($frm_field);
        
        if($field->type == 'user_id'){
            $user_data = get_userdata($val);
            if($field_tag == 'email_address')
                $val = $user_data->user_email;
            else if($field_tag == 'first_name')
                $val = $user_data->first_name;
            else if($field_tag == 'last_name')
                $val = $user_data->last_name;
        }else{
            $val = FrmProEntryMetaHelper::display_value($val, $field, array('type' => $field->type, 'truncate' => false, 'entry_id' => $entry_id)); 
        }
        
        return $val;
    }
}