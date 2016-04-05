<?php
class FrmPaymentsController{
    public static $min_version = '1.07.05';
    public static $db_version = 1;
    public static $db_opt_name = 'frm_pay_db_version';
    
    public static function load_hooks(){
        add_action('plugins_loaded', 'FrmPaymentsController::load_lang');
        register_activation_hook(dirname(dirname(__FILE__)) .'/formidable-paypal.php', 'FrmPaymentsController::install');

        if ( is_admin() ) {
            add_action('admin_menu', 'FrmPaymentsController::menu', 26);
            add_filter('frm_nav_array', 'FrmPaymentsController::frm_nav', 30);
            add_filter('plugin_action_links_formidable-paypal/formidable-paypal.php', 'FrmPaymentsController::settings_link', 10, 2 );
            add_action('after_plugin_row_formidable-paypal/formidable-paypal.php', 'FrmPaymentsController::min_version_notice');
            add_action('admin_notices', 'FrmPaymentsController::get_started_headline');
            add_action('admin_init', 'FrmPaymentsController::include_updater', 1);
            add_action('wp_ajax_frmpay_install', 'FrmPaymentsController::install');
            add_filter('set-screen-option', 'FrmPaymentsController::save_per_page', 10, 3);
            add_action('wp_ajax_frm_payments_paypal_ipn', 'FrmPaymentsController::paypal_ipn');
            add_action('wp_ajax_nopriv_frm_payments_paypal_ipn', 'FrmPaymentsController::paypal_ipn');

            add_filter('frm_form_options_before_update', 'FrmPaymentsController::update_options', 15, 2);
            add_action('frm_show_entry_sidebar', 'FrmPaymentsController::sidebar_list');
        }

        // 2.0 hook
        add_action('frm_trigger_paypal_create_action', 'FrmPaymentsController::create_payment_trigger', 10, 3);

        // < 2.0 hook
        add_action('frm_after_create_entry', 'FrmPaymentsController::pre_v2_maybe_redirect', 30, 2);
    }
    
    public static function path(){
        return dirname(dirname(__FILE__));
    }
    
    public static function load_lang(){
        load_plugin_textdomain('frmpp', false, 'formidable-paypal/languages/' );
    }

    public static function menu(){
        $frm_settings = FrmPaymentsHelper::get_settings();
        $menu = $frm_settings ? $frm_settings->menu : 'Formidable';
        add_submenu_page('formidable', $menu .' | PayPal', 'PayPal', 'frm_view_entries', 'formidable-payments', 'FrmPaymentsController::route');
         
        add_filter('manage_'. sanitize_title($menu) .'_page_formidable-payments_columns', 'FrmPaymentsController::payment_columns');
    }
    
    public static function frm_nav($nav){
        if ( current_user_can('frm_view_entries') ) {
            $nav['formidable-payments'] = 'PayPal';
        }

        return $nav;
    }
    
    public static function payment_columns($cols=array()){
        add_screen_option( 'per_page', array('label' => __('Payments', 'frmpp'), 'default' => 20, 'option' => 'formidable_page_formidable_payments_per_page') );
	    
		return array(
			'cb'        => '<input type="checkbox" />',
			'receipt_id' => __( 'Receipt ID', 'frmpp' ),
			'user_id'   => __( 'User', 'frmpp' ),
			'item_id'   => __( 'Entry', 'frmpp' ),
			'form_id'   => __( 'Form', 'frmpp' ),
			'completed' => __( 'Completed', 'frmpp' ),
			'amount'    => __( 'Amount', 'frmpp' ),
			'created_at' => __( 'Date', 'frmpp' ),
			'begin_date' => __( 'Begin Date', 'frmpp' ),
			//'expire_date' => __( 'Expire Date', 'frmpp' ),
			'paysys'    => __( 'Processor', 'frmpp' ),
		);
    }
    
    // Adds a settings link to the plugins page
    public static function settings_link($links, $file){
        $settings = '<a href="'. admin_url('admin.php?page=formidable-settings').'">' . __('Settings', 'frmpp') . '</a>';
        array_unshift($links, $settings);
        
        return $links;
    }
    
    public static function min_version_notice(){
        $frm_version = is_callable('FrmAppHelper::plugin_version') ? FrmAppHelper::plugin_version() : 0;
        
        // check if Formidable meets minimum requirements
        if ( version_compare($frm_version, self::$min_version, '>=') ) {
            return;
        }
        
        $wp_list_table = _get_list_table('WP_Plugins_List_Table');
        echo '<tr class="plugin-update-tr active"><th colspan="' . $wp_list_table->get_column_count() . '" class="check-column plugin-update colspanchange"><div class="update-message">'.
        __('You are running an outdated version of Formidable. This plugin may not work correctly if you do not update Formidable.', 'frmpp') .
        '</div></td></tr>';
    }
    
    public static function get_started_headline(){
        // Don't display this error as we're upgrading
        if(isset($_GET['action']) and $_GET['action'] == 'upgrade-plugin' and !isset($_GET['activate']))
            return;
        
        $db_version = get_option(self::$db_opt_name);
        if ( (int) $db_version < self::$db_version ) {
            if ( is_callable('FrmAppHelper::plugin_url') ) {
                $url = FrmAppHelper::plugin_url();
            } else if ( defined('FRM_URL') ) {
                $url = FRM_URL;
            } else {
                return;
            }
?>
<div class="error" id="frmpay_install_message" style="padding:7px;"><?php _e('Your Formidable Payments database needs to be updated.<br/>Please deactivate and reactivate the plugin to fix this or', 'frmpp'); ?> <a id="frmpay_install_link" href="javascript:frmpay_install_now()"><?php _e('Update Now', 'frmpp') ?></a></div>  
<script type="text/javascript">
function frmpay_install_now(){ 
jQuery('#frmpay_install_link').replaceWith('<img src="<?php echo $url ?>/images/wpspin_light.gif" alt="<?php _e('Loading&hellip;'); ?>" />');
jQuery.ajax({type:"POST",url:"<?php echo admin_url('admin-ajax.php') ?>",data:"action=frmpay_install",
success:function(msg){jQuery("#frmpay_install_message").fadeOut("slow");}
});
};
</script>
<?php
        }
    }
    
    public static function include_updater(){
        include_once(self::path() .'/models/FrmPaymentUpdate.php');
        $obj = new FrmPaymentUpdate();
    }
    
    public static function install($old_db_version=false){
        require_once(self::path() .'/models/FrmPaymentDb.php');
        $frm_payment_db = new FrmPaymentDb();
        $frm_payment_db->upgrade($old_db_version);
    }
    
    private static function show($id){
        if(!$id)
            die(__('Please select a payment to view', 'frmpp'));
        
        global $wpdb;
        $payment = $wpdb->get_row($wpdb->prepare("SELECT p.*, e.user_id FROM {$wpdb->prefix}frm_payments p LEFT JOIN {$wpdb->prefix}frm_items e ON (p.item_id = e.id) WHERE p.id=%d", $id));
        
        include(self::path() .'/views/payments/show.php');
    }
    
    private static function display_list($message='', $errors=array()){
        if(!class_exists('WP_List_Table'))
            die(__('Please upgrade to at least WordPress v3.1 to see your payments', 'frmpp'));
            
        include_once( self::path() . '/helpers/FrmPaymentsListHelper.php' );
        $title = __('Downloads', 'frmpp');
        $wp_list_table = new FrmPaymentsListHelper();
        
        $pagenum = $wp_list_table->get_pagenum();
        
        $wp_list_table->prepare_items();

        $total_pages = $wp_list_table->get_pagination_arg( 'total_pages' );
        if ( $pagenum > $total_pages && $total_pages > 0 ) {
        	wp_redirect( add_query_arg( 'paged', $total_pages ) );
        	exit;
        }

        include(self::path() .'/views/payments/list.php');
    }
    
    public static function save_per_page($save, $option, $value){
        if($option == 'formidable_page_formidable_payments_per_page')
            $save = (int)$value;
        return $save;
    }

    public static function create_payment_trigger($action, $entry, $form) {
        if ( ! isset($action->v2) ) {
            // 2.0 fallback - prevent extra processing
            remove_action('frm_after_create_entry', 'FrmPaymentsController::pre_v2_maybe_redirect', 30, 2);
        }

        return self::maybe_redirect_for_payment($action->post_content, $entry, $form);
    }

    public static function pre_v2_maybe_redirect($entry_id, $form_id) {
        if ( ! $_POST || ! isset($_POST['frm_payment']) || ( is_admin() && ! defined('DOING_AJAX') ) ) {
            return;
        }
        
        $form = FrmForm::getOne($form_id);
        
        // make sure PayPal is enabled
        if ( ! isset($form->options['paypal']) || ! $form->options['paypal'] ) {
            return;
        }

        if ( is_callable('FrmProEntriesHelper::get_field') && FrmProEntriesHelper::get_field('is_draft', $entry_id) ) {
            // don't send to PayPal if this is a draft
            return;
        }

        //check conditions
        $redirect = true;
        if ( isset($form->options['paypal_list']['hide_field']) && is_array($form->options['paypal_list']['hide_field']) && class_exists('FrmProFieldsHelper') ) {
            //for now we are assuming that if all conditions are met, then the user will be subscribed
            foreach ( $form->options['paypal_list']['hide_field'] as $hide_key => $hide_field ) {
                $observed_value = (isset($_POST['item_meta'][$hide_field])) ? $_POST['item_meta'][$hide_field] : '';

                $redirect = FrmProFieldsHelper::value_meets_condition($observed_value, $form->options['paypal_list']['hide_field_cond'][$hide_key], $form->options['paypal_list']['hide_opt'][$hide_key]);
                if ( ! $redirect ) {
                    break;
                }
            }
        }

        if ( ! $redirect ) {
            // don't pay if conditional logic is not met
            return;
        }

        // turn into an object to match with 2.0
        $entry = new stdClass();
        $entry->id = $entry_id;

        return self::maybe_redirect_for_payment($form->options, $entry, $form);
    }

    public static function maybe_redirect_for_payment($settings, $entry, $form) {
        if ( is_admin() && ! defined('DOING_AJAX') ) {
            // don't send to PayPal if submitting from the back-end
            return;
        }

        $amount = self::get_amount($form, $settings);
        if ( empty($amount) ) {
            return;
        }

        // stop the emails if payment is required
        if ( FrmPaymentsHelper::stop_email_set( $form, $settings ) ) {
			remove_action( 'frm_trigger_email_action', 'FrmNotification::trigger_email', 10, 3 );
            add_filter('frm_to_email', 'FrmPaymentsController::stop_the_email', 20, 4 );
            add_filter('frm_send_new_user_notification', 'FrmPaymentsController::stop_registration_email', 10, 3 );
        }

        // save in global for use building redirect url
        global $frm_pay_form_settings;
        $frm_pay_form_settings = $settings;

        // trigger payment redirect after other functions have a chance to complete
        add_action('frm_after_create_entry', 'FrmPaymentsController::redirect_for_payment', 50, 2);

        return true;
    }
    
    public static function redirect_for_payment($entry_id, $form_id) {
        global $frm_pay_form_settings;

        $form = FrmForm::getOne($form_id);

        $amount = self::get_amount($form, $frm_pay_form_settings);
        if ( empty($amount) ) {
            return;
        }
        
        global $wpdb;
        $invoice = $wpdb->insert( $wpdb->prefix .'frm_payments', array(
            'item_id' => $entry_id, 'amount' => (float) $amount,
            'paysys' => 'paypal', 'begin_date' => current_time('mysql', 1),
            'created_at' => current_time('mysql', 1),
            'receipt_id' => '', 'expire_date' => '0000-00-00',
        ) );
        //TODO: add expire_date for subscriptions
        
        $invoice = $invoice ? $wpdb->insert_id .'-'. FrmPaymentsHelper::get_rand(3) : $form_id.'_'. $entry_id;
        
        $frm_payment_settings = new FrmPaymentSettings();
        
        $paypal_url = 'https://www.'. ($frm_payment_settings->settings->environment == 'sandbox' ? 'sandbox.' : '');
        $paypal_url .= 'paypal.com/cgi-bin/webscr/';
        	
        //payment type options are currently _xclick and _donations
        $paypal_url .= '?cmd='. (( isset($frm_pay_form_settings['paypal_type']) && ! empty($frm_pay_form_settings['paypal_type']) ) ? $frm_pay_form_settings['paypal_type'] : '_xclick');
        $paypal_url .= '&notify_url='. FrmPaymentsHelper::format_for_url(admin_url('admin-ajax.php') . "?action=frm_payments_paypal_ipn");
        $paypal_url .= '&business='. FrmPaymentsHelper::format_for_url($frm_payment_settings->settings->business_email);
        $paypal_url .= '&currency_code='. FrmPaymentsHelper::format_for_url($frm_payment_settings->settings->currency);
        $paypal_url .= '&return='. FrmPaymentsHelper::format_for_url($frm_payment_settings->settings->return_url); 
        $paypal_url .= '&cancel_return='. FrmPaymentsHelper::format_for_url($frm_payment_settings->settings->cancel_url); 
        $paypal_url .= '&invoice='. FrmPaymentsHelper::format_for_url($invoice);
        $paypal_url .= '&custom='. $entry_id.'|'. wp_hash($entry_id);
        $paypal_url .= '&amount='. urlencode($amount);
        
        if ( defined('ICL_LANGUAGE_CODE') ) {
            $paypal_url .= '&lc='. FrmPaymentsHelper::format_for_url(ICL_LANGUAGE_CODE);
        }
        
        $item_name = apply_filters('frm_content', $frm_pay_form_settings['paypal_item_name'], $form, $entry_id);
        $paypal_url .= "&item_name=". urlencode($item_name);
            
        // subscriptions
        /*$p3 = 1; //number of time periods between each recurrence
        $t3 = 'M'; //time period (D=days, W=weeks, M=months, Y=years)
        $sra = 1; //retry the subscription if transaction fails? (1 or 0)
        
        $paypal_url .= "&a3={$amount}&p3={$p3}&t3={$t3}&src=1&sra={$sra}";
        
        // add trial
        $p1 = 1; //TRIAL period number of time periods between each recurrence
        $t1 = 'M'; //TRIAL time period (D=days, W=weeks, M=months, Y=years)
        $a1 = 0; //TRIAL price
        $paypal_url .= "&a1={$a1}&p1={$p1}&t1={$t1}"; */
        
        $paypal_url = apply_filters('formidable_paypal_url', $paypal_url, $entry_id, $form_id);

        if ( is_callable('FrmAppHelper::plugin_version') ) {
            $frm_version = FrmAppHelper::plugin_version();
        } else {
            global $frm_version; //global fallback
        }
        
        add_filter('frm_redirect_url', 'FrmPaymentsController::redirect_url', 9, 3);
        $conf_args = array('paypal_url' => $paypal_url);
        if ( defined('DOING_AJAX') && isset($form->options['ajax_submit']) && $form->options['ajax_submit'] && $_POST && isset($_POST['action']) && in_array($_POST['action'], array('frm_entries_create', 'frm_entries_update')) ) {
            $conf_args['ajax'] = true;
        }
            
        FrmProEntriesController::confirmation('redirect', $form, $form->options, $entry_id, $conf_args);
    }
    
    public static function get_amount($form, $settings = array()) {
        if ( empty($settings) ) {
            // for reverse compatability
            $settings = $form->options;
        }
        $amount_field = isset($settings['paypal_amount_field']) ? $settings['paypal_amount_field'] : '';
		$amount = 0;
        if ( !empty($amount_field) && isset($_POST['item_meta'][$amount_field])) {
			$amount = $_POST['item_meta'][ $amount_field ];
        } else if ( isset($settings['paypal_amount']) ) {
            $amount = $settings['paypal_amount'];
        }

		if ( empty( $amount ) ) {
            // no amount has been set
            return 0;
        }
        
        $frm_payment_settings = new FrmPaymentSettings();
        $currencies = FrmPaymentsHelper::get_currencies($frm_payment_settings->settings->currency);

		$total = 0;
		foreach ( (array) $amount as $a ) {
			$this_amount = trim( $a );
			preg_match_all( '/[0-9,]*\.?[0-9]+/', $this_amount, $matches );
			$this_amount = $matches ? end( $matches[0] ) : 0;
			$this_amount = round( (float) $this_amount, $currencies['decimals'] );
			$total += $this_amount;
			unset( $a, $this_amount, $matches );
		}

        return $total;
    }
    
    public static function redirect_url($url, $form, $args = array()) {
        if ( isset($args['paypal_url']) ) {
            //only change it if it came from this plugin
            $url = $args['paypal_url'];
        }
        
        return $url;
    }
    
    public static function stop_registration_email($send_it, $form, $entry_id){
        if ( !is_callable('FrmRegAppController::send_paid_user_notification') ) {
            // don't stop the registration email unless the function exists to send it later
            return $send_it;
        }
        
        if ( !isset($_POST['payment_completed']) || empty($_POST['payment_completed']) ) {
            // stop the email if payment is not completed
            $send_it = false;
        }
        
        return $send_it;
    }
    
    public static function stop_the_email($emails, $values, $form_id, $args = array()) {
		if ( isset( $_POST['payment_completed'] ) && absint( $_POST['payment_completed'] ) ) {
			// always send the email if the payment was just completed
			return $emails;
		}

		$action = FrmAppHelper::get_post_param( 'action', '', 'sanitize_title' );
		$frm_action = FrmAppHelper::get_post_param( 'frm_action', '', 'sanitize_title' );
		if ( isset($args['entry']) && $action == 'frm_entries_send_email' ) {
            // if resending, make sure the payment is complete first
			global $wpdb;
			$complete = FrmDb::get_var( $wpdb->prefix .'frm_payments', array( 'item_id' => $args['entry']->id, 'completed' => 1 ), 'completed' );

		} else {
			// send the email when resending the email, and we don't know if the payment is complete
			$complete = ( ! isset( $args['entry'] ) && ( $frm_action == 'send_email' || $action == 'frm_entries_send_email' ) );
        }
            
        //do not send if payment is not complete
		if ( ! $complete ) {
            $emails = array();
        }
        
        return $emails;
    }

    //Trigger the email to send after a payment is completed:
    public static function send_email_now($vars, $payment, $entry) {
        if ( !isset($vars['completed']) || !$vars['completed']){
            //only send the email if payment is completed
            return;
        }
        
        $_POST['payment_completed'] = true; //to let the other function know to send the email
		if ( is_callable( 'FrmFormActionsController::trigger_actions' ) ) {
			// 2.0
			FrmFormActionsController::trigger_actions( 'create', $entry->form_id, $entry->id, 'email' );
		} else {
			// < 2.0
			FrmProNotification::entry_created( $entry->id, $entry->form_id );
		}

        // trigger registration email
        if ( is_callable('FrmRegAppController::send_paid_user_notification') ) {
            FrmRegAppController::send_paid_user_notification($entry);
        }
    }
    
    public static function paypal_ipn(){
		if ( ! FrmPaymentsHelper::verify_ipn() ) {
			// if ipn is not from PayPal
			FrmPaymentsHelper::log_message( __( 'The payment notification could not be verified.', 'frmpp' ) );
			return;
		}
        
        extract($_POST);
		$custom		= FrmAppHelper::get_post_param( 'custom', '', 'sanitize_text_field' );
		$business	= strtolower( FrmAppHelper::get_post_param( 'business', '', 'sanitize_text_field' ) );
		$receiver_email = strtolower( FrmAppHelper::get_post_param( 'receiver_email', '', 'sanitize_text_field' ) );
		$invoice	= FrmAppHelper::get_post_param( 'invoice', '', 'absint' );
		$txn_id		= FrmAppHelper::get_post_param( 'txn_id', '', 'sanitize_text_field' );
    
        $frm_payment_settings = new FrmPaymentSettings();
		$frm_payment_settings->settings->business_email = strtolower( $frm_payment_settings->settings->business_email );
        
        //Check email address to make sure that this is not a spoof
		$business_email_match = ( $business == $frm_payment_settings->settings->business_email );
		$receiver_email_match = ( $receiver_email == $frm_payment_settings->settings->business_email );
		if ( empty( $custom ) || ( ! $business_email_match && ! $receiver_email_match ) ) {
			FrmPaymentsHelper::log_message( __( 'The receiving email address in the IPN does not match the settings.', 'frmpp' ) );
			return;
		}

        //get entry associated with this payment
		list( $entry_id, $hash ) = explode( '|', $custom );
		$entry_id = absint( $entry_id );
        
        //validate that Entry Id wasn't tampered with
		$test_ipn = FrmAppHelper::get_post_param( 'test_ipn', '', 'sanitize_text_field' );
		if ( empty( $test_ipn ) && wp_hash( $entry_id ) != $hash ) {
			FrmPaymentsHelper::log_message( __( 'The IPN appears to have been tampered with.', 'frmpp' ) );
			return;
		}

		$entry = FrmEntry::getOne( $entry_id );
		if ( ! $entry ) {
			FrmPaymentsHelper::log_message( __( 'The IPN does not match an existing entry.', 'frmpp' ) );
			return;
		}
              
        //mark as paid
        global $wpdb;
        $payment = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}frm_payments WHERE id=%d AND item_id=%d", $invoice, $entry_id));

		if ( ! $payment ) {
			FrmPaymentsHelper::log_message( __( 'The IPN does not match an existing payment.', 'frmpp' ) );
			return;
		}

        /*
        TODO: process subscriptions
        switch(strtolower($txn_type)){
            case "subscr_payment" :
            case "subscr_signup" :
            case "subscr_cancel" :
            case "subscr_eot" : //expired
            case "subscr_failed" :
        */

		$pay_vars = (array) $payment;
		if ( ! $payment->receipt_id ) {
            $pay_vars['receipt_id'] = $txn_id;
		}
        
		if ( $pay_vars['meta_value'] && ! empty( $pay_vars['meta_value'] ) ) {
			$pay_vars['meta_value'] = maybe_unserialize( $pay_vars['meta_value'] );
		} else {
            $pay_vars['meta_value'] = array();
		}

		$ipn_track_id = FrmAppHelper::get_post_param( 'ipn_track_id', '', 'sanitize_text_field' );

		$pay_vars['meta_value'][ $ipn_track_id ] = array();
		foreach ( $_POST as $k => $v ) {
			$pay_vars['meta_value'][ sanitize_text_field( $k ) ] = sanitize_text_field( $v );
		}
        $pay_vars['meta_value'] = maybe_serialize($pay_vars['meta_value']);

		$payment_status = FrmAppHelper::get_post_param( 'payment_status', '', 'sanitize_text_field' );
		$pay_vars['completed'] = ( $payment_status == 'Completed' );

		$next_payment_date = FrmAppHelper::get_post_param( 'next_payment_date', '', 'sanitize_text_field' );
		if ( isset( $next_payment_date ) ) {
            $pay_vars['expire_date'] = date('Y-m-d H:i:s', strtotime($next_payment_date));
		}
        
        do_action('frm_payment_paypal_ipn', compact('pay_vars', 'payment', 'entry'));

		$payment_gross = FrmAppHelper::get_post_param( 'payment_gross', '', 'sanitize_text_field' );
		$mc_gross = FrmAppHelper::get_post_param( 'mc_gross', '', 'sanitize_text_field' );

		$amt = ( $payment_gross != '' && $payment_gross > 0.0 ) ? $payment_gross : $mc_gross;
		if ( $amt != $payment->amount ) {
			FrmPaymentsHelper::log_message( __( 'Payment amounts do not match.', 'frmpp' ) );
			return; //Payment amounts do not match
		}
        
        $u = $wpdb->update( $wpdb->prefix .'frm_payments', $pay_vars, array('id' => $payment->id) );
        if ( !$u ) {
            FrmPaymentsHelper::log_message(sprintf(__('Payment %d was complete, but failed to update.', 'frmpp'), $payment->id ));
            return;
        }
        
		FrmPaymentsHelper::log_message( __( 'Payment successfully updated.', 'frmpp' ) );

		if ( FrmPaymentsHelper::stop_email_set( $entry->form_id ) ) {
			self::send_email_now( $pay_vars, $payment, $entry );
        }
        
        die();
    }
    
    public static function hidden_payment_fields($form){
        if(isset($form->options['paypal']) and $form->options['paypal']){
            echo '<input type="hidden" name="frm_payment[item_name]" value="'. esc_attr($form->options['paypal_item_name']) .'"/>'."\n";
        }
    }
    
    public static function setup_new_vars($values){
        $defaults = FrmPaymentsHelper::get_default_options();
        foreach ($defaults as $opt => $default){
            $values[$opt] = FrmAppHelper::get_param($opt, $default);
			unset( $default, $opt );
        }
        return $values;
    }
    
    public static function setup_edit_vars($values){
        $defaults = FrmPaymentsHelper::get_default_options();
		foreach ( $defaults as $opt => $default ) {
			if ( ! isset( $values[ $opt ] ) ) {
				$values[$opt] = ( $_POST && isset( $_POST['options'][ $opt ] ) ) ? $_POST['options'][ $opt ] : $default;
			}
			unset($default, $opt);
        }
        
        if($values['paypal_item_name'] == ''){
            global $wpdb;
			$values['paypal_item_name'] = FrmDb::get_var( $wpdb->prefix .'frm_forms', array( 'id' => $values['id'] ), 'name' );
        }
        
        return $values;
    }
    
    public static function update_options($options, $values){
        $defaults = FrmPaymentsHelper::get_default_options();
        
		foreach( $defaults as $opt => $default ) {
			$options[ $opt ] = isset( $values['options'][ $opt ] ) ? $values['options'][ $opt ] : $default;
			unset( $default, $opt );
		}

        return $options;
    }
    
    public static function sidebar_list($entry){
        global $wpdb;
        
        $payments = $wpdb->get_results($wpdb->prepare("SELECT id,begin_date,amount,completed FROM {$wpdb->prefix}frm_payments WHERE item_id=%d ORDER BY created_at DESC", $entry->id));
        
        if(!$payments)
            return;
        
        $date_format = get_option('date_format');    
        $currencies = FrmPaymentsHelper::get_currencies();
        
        include(self::path() .'/views/payments/sidebar_list.php');
    }

    private static function new_payment(){
        self::get_new_vars();
    }
    
    private static function create(){
        $message = $error = '';
        
        require_once(self::path() .'/models/FrmPayment.php');
        $frm_payment = new FrmPayment();
        if( $id = $frm_payment->create( $_POST )){
            $message = __('Payment was Successfully Created', 'frmpp');
            self::get_edit_vars($id, '', $message);
        }else{
            $error = __('There was a problem creating that payment', 'frmpp');
            return self::get_new_vars($error);
        }
    }
        
    private static function edit(){
        $id = FrmAppHelper::get_param('id');
        return self::get_edit_vars($id);
    }
    
    private static function update(){
        require_once(self::path() .'/models/FrmPayment.php');
        $frm_payment = new FrmPayment();
        $id = FrmAppHelper::get_param('id');
        $message = $error = '';
        if( $frm_payment->update( $id, $_POST ))
            $message = __('Payment was Successfully Updated', 'frmpp');
        else
            $error = __('There was a problem updating that payment', 'frmpp');
        return self::get_edit_vars($id, $error, $message);
    }
    
    private static function destroy(){
        if(!current_user_can('administrator')){
            $frm_settings = FrmPaymentsHelper::get_settings();
            wp_die($frm_settings->admin_permission);
        }
        
        require_once(self::path() .'/models/FrmPayment.php');
        $frm_payment = new FrmPayment();
        $message = '';
        if ($frm_payment->destroy( FrmAppHelper::get_param('id') ))
            $message = __('Payment was Successfully Deleted', 'frmpp');
            
        self::display_list($message);
    }
    
    private static function bulk_actions($action){
        $errors = array();
        $message = '';
        $bulkaction = str_replace('bulk_', '', $action);

        $items = FrmAppHelper::get_param('item-action', '');
        if (empty($items)){
            $errors[] = __('No payments were selected', 'frmpp');
        }else{
            if(!is_array($items))
                $items = explode(',', $items);
                
            if($bulkaction == 'delete'){
                if(!current_user_can('frm_delete_entries')){
                    $frm_settings = FrmPaymentsHelper::get_settings();
                    $errors[] = $frm_settings->admin_permission;
                }else{
                    if(is_array($items)){
                        require_once(self::path() .'/models/FrmPayment.php');
                        $frm_payment = new FrmPayment();
                        foreach($items as $item_id){
                            if($frm_payment->destroy($item_id))
                                $message = __('Payments were Successfully Deleted', 'frmpp');
                        }
                    }
                }
            }
        }
        self::display_list($message, $errors);
    }
    
    private static function get_new_vars($error=''){
        global $wpdb;
        
        $defaults = array('completed' => 0, 'item_id' => '', 'receipt_id' => '', 'amount' => '', 'begin_date' => date('Y-m-d'), 'paysys' => 'manual');
        $payment = array();
        foreach($defaults as $var => $default)
            $payment[$var] = FrmAppHelper::get_param($var, $default); 
        
        $frm_payment_settings = new FrmPaymentSettings();
        $currency = FrmPaymentsHelper::get_currencies($frm_payment_settings->settings->currency);
        $users = FrmProFieldsHelper::get_user_options();
        
        require(self::path() .'/views/payments/new.php');
    }
    
    private static function get_edit_vars($id, $errors = '', $message= ''){
        if(!$id)
            die(__('Please select a payment to view', 'frmpp'));
            
        if(!current_user_can('frm_edit_entries'))
            return self::show($id);
            
        global $wpdb;
        $payment = $wpdb->get_row($wpdb->prepare("SELECT p.*, e.user_id FROM {$wpdb->prefix}frm_payments p LEFT JOIN {$wpdb->prefix}frm_items e ON (p.item_id = e.id) WHERE p.id=%d", $id), ARRAY_A);

        $frm_payment_settings = new FrmPaymentSettings();
        $currency = FrmPaymentsHelper::get_currencies($frm_payment_settings->settings->currency);
        
        $users = FrmProFieldsHelper::get_user_options();
        
        if(isset($_POST) and isset($_POST['receipt_id'])){
            foreach($payment as $var => $val){
                if($var == 'id') continue;
                $payment[$var] = FrmAppHelper::get_param($var, $val);
            }
        }
        
        require(self::path() .'/views/payments/edit.php');
    }
    
    public static function route(){
        $action = isset($_REQUEST['frm_action']) ? 'frm_action' : 'action';
        $action = FrmAppHelper::get_param($action);
        
        if($action == 'show'){
            return self::show(FrmAppHelper::get_param('id', false));
        }else if($action == 'new'){
            return self::new_payment();
        }else if($action == 'create'){
            return self::create();
        }else if($action == 'edit'){
            return self::edit();
        }else if($action == 'update'){
            return self::update();
        }else if($action == 'destroy'){
            return self::destroy();
        }else{
            $action = FrmAppHelper::get_param('action');
            if($action == -1)
                $action = FrmAppHelper::get_param('action2');
            
            if(strpos($action, 'bulk_') === 0){
                if(isset($_GET) and isset($_GET['action']))
                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action'], '', $_SERVER['REQUEST_URI']);
                if(isset($_GET) and isset($_GET['action2']))
                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action2'], '', $_SERVER['REQUEST_URI']);

                return self::bulk_actions($action);
            }else{
                return self::display_list();
            }
        }
    }
}
