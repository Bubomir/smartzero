<?php

class FrmHrsSettings{
    var $settings;

    function FrmHrsSettings(){
        $this->set_default_options();
    }
    
    function param(){
        return 'hrs';
    }
    
    function default_options(){
        return array(
            'account'       => '',
            'token'         => ''
        );
    }
    
    function set_default_options($settings=false){
        $default_settings = $this->default_options();
        
        if(!$settings)
            $settings = $this->get_options();
        else if($settings === true)
            $settings = new stdClass();
            
        if(!isset($this->settings))
            $this->settings = new stdClass();
        
        foreach($default_settings as $setting => $default){
            if(is_object($settings) and isset($settings->{$setting}))
                $this->settings->{$setting} = $settings->{$setting};
                
            if(!isset($this->settings->{$setting}))
                $this->settings->{$setting} = $default;
        }
    }
    
    function get_options(){
        $settings = get_option('frm_'. $this->param() .'_options');

        if(!is_object($settings)){
            if($settings){ //workaround for W3 total cache conflict
                $settings = unserialize(serialize($settings));
            }else{
                // If unserializing didn't work
                if(!is_object($settings)){
                    if($settings) //workaround for W3 total cache conflict
                        $settings = unserialize(serialize($settings));
                    else
                        $settings = $this->set_default_options(true);
                    $this->store();
                }
            }
        }else{
            $this->set_default_options($settings); 
        }
        
        return $this->settings;
    }
    
    function update($params){
        $settings = $this->default_options();
        
        foreach($settings as $setting => $default){
            if(isset($params['frm_'. $this->param() .'_'. $setting]))
                $this->settings->{$setting} = $params['frm_'. $this->param() .'_'. $setting];
        }
    }

    function store(){
        // Save the posted value in the database
        update_option( 'frm_'. $this->param() .'_options', $this->settings);
    }

}