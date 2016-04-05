<?php

class FrmAwbrAppController{
    function FrmAwbrAppController(){
        add_action('admin_init', 'FrmAwbrAppController::include_updater', 1);
        add_action('frm_entry_form', 'FrmAwbrAppController::hidden_form_fields', 10, 2);
        add_action('frm_after_create_entry', 'FrmAwbrAppController::send_to_aweber', 25, 2);
        //add_action('frm_after_update_entry', 'FrmAwbrAppController::send_to_aweber', 25, 2);
    }
    
    public static function path(){
        return dirname(dirname(__FILE__));
    }
    
    public static function include_updater(){
        include_once(self::path() .'/models/FrmAwbrUpdate.php');
        $frm_awbr_update = new FrmAwbrUpdate();
    }
    
    public static function hidden_form_fields($form, $form_action){
        $form->options = maybe_unserialize($form->options);
        if(!isset($form->options['aweber']) or !$form->options['aweber'] or !isset($form->options['awbr_list']) or !is_array($form->options['awbr_list']))
            return;
            
        echo '<input type="hidden" name="frm_aweber" value="1"/>'."\n";
        
        //if($form_action != 'update')
            return;
        
        $list = reset($form->options['awbr_list']);
        $field_id = $list['fields']['email'];
        
        global $frm_editing_entry, $frm_vars;
        $frm_entry_meta = new FrmEntryMeta();
        $entry_id = (is_array($frm_vars) and isset($frm_vars['editing_entry'])) ? $frm_vars['editing_entry'] : $frm_editing_entry;
        $email = $frm_entry_meta->get_entry_meta((int)$entry_id, $field_id);
        unset($frm_entry_meta);
        
        echo '<input type="hidden" name="frm_aweber_email" value="'. esc_attr($email) .'"/>'."\n";
    }
    
    public static function send_to_aweber($entry_id, $form_id){
        if(!isset($_POST) or !isset($_POST['frm_aweber']))
            return;
        
        $frm_form = new FrmForm();
        $form = $frm_form->getOne($form_id);
        unset($frm_form);
        
        if(!isset($form->options['aweber']) or !$form->options['aweber'])
            return;
            
        if(!class_exists('FrmAWeberAPI'))
            require_once(self::path() . '/aweber_api/aweber.php');
          
        $frm_awbr_settings = new FrmAwbrSettings();
        $aweber = new FrmAWeberAPI($frm_awbr_settings->settings->consumer_key, $frm_awbr_settings->settings->consumer_secret);
        $account = $aweber->getAccount($frm_awbr_settings->settings->access_key, $frm_awbr_settings->settings->access_secret);
        unset($frm_awbr_settings);
        
        foreach($form->options['awbr_list'] as $list_id => $list_options){
            //check conditions
            $subscribe = true;
            if(isset($list_options['hide_field']) and is_array($list_options['hide_field'])){
                //for now we are assuming that if all conditions are met, then the user will be subscribed
                foreach($list_options['hide_field'] as $hide_key => $hide_field){
                    if(!$subscribe)
                        continue;
                        
                    $observed_value = (isset($_POST['item_meta'][$hide_field])) ? $_POST['item_meta'][$hide_field] : '';
                    
                    if ($observed_value == ''){
                        $subscribe = false;
                    }else{
                        $subscribe = FrmProFieldsHelper::value_meets_condition($observed_value, $list_options['hide_field_cond'][$hide_key], $list_options['hide_opt'][$hide_key]);
                    }
                }
            }
            
            if(!$subscribe) //don't subscribe if conditional logic is not met
                return;
            
            $list = $account->loadFromUrl("/accounts/{$account->id}/lists/{$list_id}");
            
            $vars = array(
                'custom_fields' => array(),
                'ip_address' => $_SERVER['REMOTE_ADDR']
                //'ad_tracking' => 'client_lib_example',
                //'misc_notes' => 'my cool app',
            );
            
            $frm_field = new FrmField();
            foreach($list_options['fields'] as $field_tag => $field_id){
                $val = (isset($_POST['item_meta'][$field_id])) ? $_POST['item_meta'][$field_id] : '';
                if(is_numeric($val)){
                    $field = $frm_field->getOne($field_id);    
                    if($field->type == 'user_id'){
                        $user_data = get_userdata($val);
                        if($field_tag == 'email')
                            $val = $user_data->user_email;
                        else if($field_tag == 'name')
                            $val = $user_data->first_name .' '. $user_data->last_name;
                        else
                            $val = $user_info->user_login;
                    }else{
                        $val = FrmProEntryMetaHelper::display_value($val, $field, array('type' => $field->type, 'truncate' => false, 'entry_id' => $entry_id)); 
                    } 
                }
                
                if($field_tag == 'email' or $field_tag == 'name')
                    $vars[$field_tag] = $val;
                else
                    $vars['custom_fields'][$field_tag] = $val;
            }
            unset($frm_field);
            
            $vars = apply_filters('frm_awbr_vars', $vars, $form);

            if(!isset($vars['email'])) //no email address is mapped
                return;
                
            if(empty($vars['custom_fields']))
                unset($vars['custom_fields']);
            
            try{
                $subscribers = $list->subscribers;
                //if(isset($_POST['frm_aweber_email']) and is_email($_POST['frm_aweber_email'])){ //we are editing the entry
                //    $email_field = $_POST['frm_aweber_email'];
                //}else{
                    $new_subscriber = $subscribers->create($vars);
                //}
            }
            catch(FrmAWeberAPIException $exc){
            }
        }
    }
}