<?php

class FrmPaymentAction extends FrmFormAction {

	function __construct() {
		$action_ops = array(
		    'classes'   => 'frm_paypal_icon frm_icon_font',
            'active'    => true,
            'event'     => array('create'),
            'priority'  => 9, // trigger before emails are sent so they can be stopped
            'limit'     => 99,
		);
		
		$this->FrmFormAction( 'paypal', __( 'PayPal', 'frmpp' ), $action_ops );
	}

	function form( $form_action, $args = array() ) {
        $form_fields = FrmField::getAll("fi.form_id='". $args['form']->id ."' and fi.type not in ('divider', 'html', 'break', 'captcha', 'rte', 'form')", ' ORDER BY field_order');
        $show_amount = ($form_action->post_content['paypal_amount'] == '') ? false : true;
	    
	    include(FrmPaymentsController::path() .'/views/settings/_payment_settings.php');
	}
	
	function get_defaults() {
	    return FrmPaymentsHelper::get_default_options();
	}

	public function migrate_values($action, $form) {
	    if ( isset($action->post_content['conditions']['hide_field']) && ! empty($action->post_content['conditions']['hide_field']) ) {
            $new_conditions = array();
    	    $action->post_content['conditions']['send_stop'] = 'send';
    	    foreach ( $action->post_content['conditions']['hide_field'] as $k => $field_id ) {
                $new_conditions[] = array(
                    'hide_field'        => $field_id,
                    'hide_field_cond'   => isset($action->post_content['conditions']['hide_field_cond'][$k]) ? $action->post_content['conditions']['hide_field_cond'][$k] : '==',
                    'hide_opt'          => isset($action->post_content['conditions']['hide_opt'][$k]) ? $action->post_content['conditions']['hide_opt'][$k] : '',
                );
    	    }
            $action->post_content['conditions'] = $new_conditions;
        }

        $action->post_content['event'] = array('create');
	    return $action;
	}
}
