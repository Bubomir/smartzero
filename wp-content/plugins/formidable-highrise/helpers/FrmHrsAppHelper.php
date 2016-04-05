<?php
 
class FrmHrsAppHelper{
    public static function plugin_path(){
        return WP_PLUGIN_DIR .'/formidable-highrise';
    }
    
    public static function get_default_options(){
        return array(
            'highrise' => 0, 
            'hrs_list' => array()
        );
    }
    
    public static function get_field_details($all_fields){
        $multi_fields = array('email_address', 'instant_messenger', 'twitter_account', 'web_address', 'address', 'phone_number');
        
        $list_fields = array();
        foreach($all_fields as $list_field){
            $f_array = array('tag' => $list_field, 'name' => ucwords(str_replace('_', ' ', $list_field)));
            $f_array['multi'] = (in_array($list_field, $multi_fields)) ? true : false;
            switch($list_field){
                case 'email_address':
                case 'address':
                    $f_array['location'] = array('Work', 'Home', 'Other');
                break;
                case 'instant_messenger':
                    $f_array['protocol'] = array('AIM', 'MSN', 'ICQ', 'Jabber', 'Yahoo', 'Skype', 'QQ', 'Sametime', 'Gadu-Gadu', 'Google Talk', 'Other');
                case 'web_address':
                    $f_array['location'] = array('Work', 'Personal', 'Other');
                break;
                case 'twitter_account':
                    $f_array['location'] = array('Business', 'Personal', 'Other');
                break;
                case 'phone_number':
                    $f_array['location'] = array('Work', 'Mobile', 'Fax', 'Pager', 'Home', 'Skype', 'Other');
                break;
            }
            
            
            $list_fields[] = $f_array;
            unset($list_field);
        }
        
        unset($multi_fields);
        return $list_fields;
    }
}