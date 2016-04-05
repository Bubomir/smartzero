<?php
class FrmPaymentSettingsController{
    public static function load_hooks(){
        add_action('frm_add_settings_section', 'FrmPaymentSettingsController::add_settings_section'); // global settings
        add_action('frm_after_duplicate_form', 'FrmPaymentSettingsController::duplicate', 15, 2);

        // < 2.0 fallback
        add_action('init', 'FrmPaymentSettingsController::load_form_settings_hooks');

        // 2.0 hooks
        add_action('frm_registered_form_actions', 'FrmPaymentSettingsController::register_actions');
        add_action('frm_before_list_actions', 'FrmPaymentSettingsController::migrate_to_2');
    }

    public static function load_form_settings_hooks() {
        if ( FrmPaymentsHelper::is_below_2() ) {
            // load hooks for < v2.0
            add_action('frm_add_form_settings_section', 'FrmPaymentSettingsController::add_payment_options');
            add_filter('frm_setup_new_form_vars', 'FrmPaymentsController::setup_new_vars');
            add_filter('frm_setup_edit_form_vars', 'FrmPaymentsController::setup_edit_vars');
            add_action('frm_entry_form', 'FrmPaymentsController::hidden_payment_fields');
            add_action('wp_ajax_frm_pay_add_logic_row', 'FrmPaymentSettingsController::add_logic_row');
        }
    }

    public static function add_settings_section($sections){
        $sections['paypal'] = array('class' => 'FrmPaymentSettingsController', 'function' => 'route');
        return $sections;
    }
    
    public static function add_payment_options($sections){
        $sections['paypal'] = array('class' => 'FrmPaymentSettingsController', 'function' => 'payment_options');
        return $sections;
    }
    
    public static function payment_options($values){
        if(isset($values['id'])){
            $form_fields = FrmField::getAll("fi.form_id='$values[id]' and fi.type not in ('divider', 'html', 'break', 'captcha', 'rte', 'form')", ' ORDER BY field_order');
        }
        $hide_paypal = $values['paypal'] ? '' : 'style="display:none;"';
        
        $show_amount = ($values['paypal_amount'] == '') ? false : true;
        
        include(FrmPaymentsController::path() .'/views/settings/payment_options.php');
    }
    
    public static function add_logic_row(){
        $form_id = (int)$_POST['form_id'];
        $meta_name = $_POST['meta_name'];
        $hide_field = '';
        
        $paypal_list = array('hide_field' => array(), 'hide_field_cond' => array($meta_name => '=='), 'hide_opt' => array());
        self::include_logic_row($meta_name, $form_id, $paypal_list);
        
        die();
    }
    
    public static function include_logic_row($meta_name, $form_id, $values) {
        if ( !is_callable('FrmProFormsController::include_logic_row') ) {
            return;
        }
        
        FrmProFormsController::include_logic_row(array(
            'meta_name' => $meta_name,
            'condition' => array(
                'hide_field'    => ( isset($values['hide_field']) && isset($values['hide_field'][$meta_name]) ) ? $values['hide_field'][$meta_name] : '',
                'hide_field_cond' => ( isset($values['hide_field_cond']) && isset($values['hide_field_cond'][$meta_name]) ) ? $values['hide_field_cond'][$meta_name] : '',
                'hide_opt'      => ( isset($values['hide_opt']) && isset($values['hide_opt'][$meta_name]) ) ? $values['hide_opt'][$meta_name] : '',
            ),
            'type' => 'paypal',
            'showlast' => '.frm_add_paypal_logic',
            'key' => 'paypal',
            'form_id' => $form_id,
            'id' => 'frm_logic_paypal_'. $meta_name,
            'names' => array(
                'hide_field'    => 'options[paypal_list][hide_field][]',
                'hide_field_cond' => 'options[paypal_list][hide_field_cond][]',
                'hide_opt'      => 'options[paypal_list][hide_opt][]',
            ),
        ));
    }
        
    public static function display_form($errors=array(), $message=''){
        $frm_payment_settings = new FrmPaymentSettings();

        require(FrmPaymentsController::path() .'/views/settings/form.php');
    }

    public static function process_form(){
        $frm_payment_settings = new FrmPaymentSettings();

        //$errors = $frm_payment_settings->validate($_POST,array());
        $errors = array();
        $frm_payment_settings->update($_POST);

        if( empty($errors) ){
            $frm_payment_settings->store();
            $message = __('Settings Saved', 'frmpp');
        }
        
        self::display_form($errors, $message);
    }

    public static function route(){
        $action = isset($_REQUEST['frm_action']) ? 'frm_action' : 'action';
        $action = FrmAppHelper::get_param($action);
        if($action == 'process-form')
            return self::process_form();
        else
            return self::display_form();
    }
    
    // switch field keys/ids after form is duplicated
    public static function duplicate($id, $values) {
        if ( is_callable( 'FrmProFieldsHelper::switch_field_ids' ) ) {
            // don't switch IDs unless running Formidabe version that does
            return;
        }
        
        $form = FrmForm::getOne($id);
        $new_opts = $values['options'] = $form->options;
        unset($form);
        
        if ( !isset($values['options']['paypal_item_name']) || empty($values['options']['paypal_item_name']) ) {
            // don't continue if there aren't paypal settings to switch
            return;
        }
        
        global $frm_duplicate_ids;
        
        if ( is_numeric($new_opts['paypal_amount_field']) && isset($frm_duplicate_ids[$new_opts['paypal_amount_field']]) ) {
            $new_opts['paypal_amount_field'] = $frm_duplicate_ids[$new_opts['paypal_amount_field']];
        }
        
        $new_opts['paypal_item_name'] = FrmProFieldsHelper::switch_field_ids($new_opts['paypal_item_name']);
        
        // switch conditional logic
        if ( is_array($new_opts['paypal_list']) && isset($new_opts['paypal_list']['hide_field']) ) {
            foreach ( (array) $new_opts['paypal_list']['hide_field'] as $ck => $cv ) {
                if ( is_numeric($cv) && isset($frm_duplicate_ids[$cv]) ) {
                    $new_opts['paypal_list']['hide_field'][$ck] = $frm_duplicate_ids[$cv];
                }
                
                unset($ck, $cv);
            }
        }
        
        if ( $new_opts != $values['options'] ) {
            global $wpdb;
            $wpdb->update($wpdb->prefix .'frm_forms', array('options' => maybe_serialize($new_opts)), array('id' => $id));
        }
    }

    public static function register_actions($actions) {
        $actions['paypal'] = 'FrmPaymentAction';
        return $actions;
    }

    public static function migrate_to_2($form) {
        if ( ! isset($form->options['paypal']) || ! $form->options['paypal'] ) {
            return;
        }

        if ( FrmPaymentsHelper::is_below_2() ) {
            return;
        }

        $action_control = FrmFormActionsController::get_form_actions( 'paypal' );
        $form->options['conditions'] = $form->options['paypal_list'];
        unset($form->options['paypal_list']);

        $post_id = $action_control->migrate_to_2($form);

        return $post_id;
    }
}