<?php

class FrmPaymentsHelper{
    
    public static function get_default_options(){
        return array(
            'paypal' => 0, 'paypal_item_name' => '', 'paypal_amount_field' => '',
            'paypal_amount' => '', 'paypal_list' => array(), 'paypal_stop_email' => 0,
            'paypal_type' => ''
        );
    }

    /*
    * Check if the version number of Formidable is below 2.0
    */
    public static function is_below_2() {
        $frm_version = is_callable('FrmAppHelper::plugin_version') ? FrmAppHelper::plugin_version() : 0;
        return version_compare( $frm_version, '1.07.19' ) == '-1';
    }

    /*
    * Check global $frm_settings as a 2.0 fallback
    */
    public static function get_settings() {
        global $frm_settings;
        if ( ! empty($frm_settings) ) {
            return $frm_settings;
        } else if ( is_callable('FrmAppHelper::get_settings') ) {
            return FrmAppHelper::get_settings();
        } else {
            return array();
        }
    }

	/**
	 * @since 2.04.02
	 */
	public static function stop_email_set( $form_id, $settings = array() ) {
		if ( empty ( $settings ) ) {
			$form = FrmForm::getOne( $form_id );
			if ( empty( $form ) ) {
				return false;
			}
			$settings = self::get_form_settings( $form );
		}

        return ( isset( $settings['paypal_stop_email'] ) && ! empty( $settings['paypal_stop_email'] ) );
	}

	/**
	 * @since 2.04.02
	 */
	public static function get_form_settings( $form ) {
		$form_settings = $form->options;
		if ( ( isset( $form->options['paypal'] ) && ! empty( $form->options['paypal'] ) ) || ! class_exists( 'FrmFormActionsHelper' ) ) {
			return $form_settings;
		}

		// get the 2.0 form action settings
		$action_control = FrmFormActionsHelper::get_action_for_form( $form->id, 'paypal', 1 );
		if ( ! $action_control ) {
			return;
		}
		$form_settings = $action_control->post_content;
		$form_settings['paypal'] = 1;
		return $form_settings;
	}

    public static function get_currencies($currency=false){
        $currencies = array(
            'AUD' => array(
                'name' => __('Australian Dollar', 'frmpp'),
                'symbol_left' => '$', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'BRL' => array(
                'name' => __('Brazilian Real', 'frmpp'),
                'symbol_left' => 'R$', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => '.', 'decimal_separator' => ',', 'decimals' => 2,
            ),
            'CAD' => array(
                'name' => __('Canadian Dollar', 'frmpp'),
                'symbol_left' => '$', 'symbol_right' => 'CAD', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'CZK' => array(
                'name' => __('Czech Koruna', 'frmpp'),
                'symbol_left' => '', 'symbol_right' => '&#75;&#269;', 'symbol_padding' => ' ',
                'thousand_separator' => ' ', 'decimal_separator' => ',', 'decimals' => 2,
            ),
            'DKK' => array(
                'name' => __('Danish Krone', 'frmpp'),
                'symbol_left' => 'Kr', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => '.', 'decimal_separator' => ',', 'decimals' => 2,
            ),
            'EUR' => array(
                'name' => __('Euro', 'frmpp'),
                'symbol_left' => '', 'symbol_right' => '&#8364;', 'symbol_padding' => ' ',
                'thousand_separator' => '.', 'decimal_separator' => ',', 'decimals' => 2,
            ),
            'HKD' => array(
                'name' => __('Hong Kong Dollar', 'frmpp'),
                'symbol_left' => 'HK$', 'symbol_right' => '', 'symbol_padding' => '',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'HUF' => array(
                'name' => __('Hungarian Forint', 'frmpp'),
                'symbol_left' => '', 'symbol_right' => 'Ft', 'symbol_padding' => ' ',
                'thousand_separator' => '.', 'decimal_separator' => ',', 'decimals' => 2,
            ),
            'ILS' => array(
                'name' => __('Israeli New Sheqel', 'frmpp'),
                'symbol_left' => '&#8362;', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'JPY' => array(
                'name' => __('Japanese Yen', 'frmpp'),
                'symbol_left' => '&#165;', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '', 'decimals' => 0,
            ),
            'MYR' => array(
                'name' => __('Malaysian Ringgit', 'frmpp'),
                'symbol_left' => '&#82;&#77;', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'MXN' => array(
                'name' => __('Mexican Peso', 'frmpp'),
                'symbol_left' => '$', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'NOK' => array(
                'name' => __('Norwegian Krone', 'frmpp'),
                'symbol_left' => 'Kr', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => '.', 'decimal_separator' => ',', 'decimals' => 2,
            ),
            'NZD' => array(
                'name' => __('New Zealand Dollar', 'frmpp'),
                'symbol_left' => '$', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'PHP' => array(
                'name' => __('Philippine Peso', 'frmpp'),
                'symbol_left' => 'Php', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'PLN' => array(
                'name' => __('Polish Zloty', 'frmpp'),
                'symbol_left' => '&#122;&#322;', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => '.', 'decimal_separator' => ',', 'decimals' => 2,
            ),
            'GBP' => array(
                'name' => __('Pound Sterling', 'frmpp'),
                'symbol_left' => '&#163;', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'SGD' => array(
                'name' => __('Singapore Dollar', 'frmpp'),
                'symbol_left' => '$', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'SEK' => array(
                'name' => __('Swedish Krona', 'frmpp'),
                'symbol_left' => '', 'symbol_right' => 'Kr', 'symbol_padding' => ' ',
                'thousand_separator' => ' ', 'decimal_separator' => ',', 'decimals' => 2,
            ),
            'CHF' => array(
                'name' => __('Swiss Franc', 'frmpp'),
                'symbol_left' => 'Fr.', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => "'", 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'TWD' => array(
                'name' => __('Taiwan New Dollar', 'frmpp'),
                'symbol_left' => '$', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'THB' => array(
                'name' => __('Thai Baht', 'frmpp'),
                'symbol_left' => '&#3647;', 'symbol_right' => '', 'symbol_padding' => ' ',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
            'TRY' => array(
                'name' => __('Turkish Liras', 'frmpp'),
                'symbol_left' => '', 'symbol_right' => '&#8364;', 'symbol_padding' => ' ',
                'thousand_separator' => '.', 'decimal_separator' => ',', 'decimals' => 2,
            ),
            'USD' => array(
                'name' => __('U.S. Dollar', 'frmpp'),
                'symbol_left' => '$', 'symbol_right' => '', 'symbol_padding' =>  '',
                'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 2,
            ),
        );

        $currencies = apply_filters('frm_currencies', $currencies);
        if ( $currency && isset($currencies[$currency]) ) {
            return $currencies[$currency];
        }
            
        return $currencies;
    }
    
    public static function format_for_url($value){
        if ( seems_utf8($value) ) {
            $value = utf8_uri_encode($value, 200);
        } else {
            $value = strip_tags($value);
        }
        $value = urlencode($value);
        return $value;
    }
    
    public static function formatted_amount($amount){
        $frm_payment_settings = new FrmPaymentSettings();
        
        $currency = self::get_currencies($frm_payment_settings->settings->currency);
        
        return $currency['symbol_left'] .
         $currency['symbol_padding'] . 
        number_format($amount, $currency['decimals'], $currency['decimal_separator'], $currency['thousand_separator']) . 
        $currency['symbol_padding'] . $currency['symbol_right'];
    }
    
    public static function get_rand($length){
        $all_g = 'ABCDEFGHIJKLMNOPQRSTWXZ';
        $all_len = strlen($all_g) - 1;
        $pass = '';
        for ( $i=0; $i < $length; $i++ ) {
            $pass .= $all_g[ rand(0, $all_len) ];
        }
        return $pass;
    }
    
    public static function verify_ipn(){
        $frm_payment_settings = new FrmPaymentSettings();
        
        if($frm_payment_settings->settings->environment == 'sandbox')
    		$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr/";
    	else
    	    $paypal_url = "https://www.paypal.com/cgi-bin/webscr/";

        $log_data = array('last_error' => '', 'ipn_response' => '', 'ipn_data' => array());     

        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        
        foreach ($_POST as $key => $value) { 
           $log_data['ipn_data'][$key] = $value;
           $value = urlencode(stripslashes($value));
           $req .= "&{$key}={$value}";
        }
        
        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        
        $resp = wp_remote_post($paypal_url, array('ssl' => true, 'body' => $req));
        $body = wp_remote_retrieve_body( $resp );
        
        if ( $resp == 'error' || is_wp_error($resp) ) {
            $log_data['ipn_response'] = __('You had an error communicating with the PayPal API.', 'frmpp');
            $log_data['ipn_response'] .= ' '. $resp->get_error_message();
        } else {
            $log_data['ipn_response'] = $body;
        }
        
        if ($log_data['ipn_response'] == 'VERIFIED'){
           // Valid IPN transaction
           self::log_ipn_results(true, $log_data);
           return true;
        }else{
           // Invalid IPN transaction.  Check the log for details.
           $log_data['last_error'] = 'IPN Validation Failed.';
           self::log_ipn_results(false, $log_data);   
           return false;
        }
    }
    
    public static function log_ipn_results($success, $log_data) {
        extract($log_data); //$last_error, $ipn_response, $ipn_data
        
        // Timestamp
        $text = '['.date('m/d/Y g:i A').'] - '; 

        // Success or failure being logged?
        $text .= $success ? "SUCCESS!\n" : 'FAIL: '. $log_data['last_error'] ."\n";

        // Log the POST variables
        $text .= "IPN POST Vars from Paypal:\n";
        foreach ($ipn_data as $key => $value)
           $text .= "$key=$value, ";

        // Log the response from the paypal server
        $text .= "\nIPN Response from Paypal Server:\n ". $log_data['ipn_response'];

        // Write to log
        self::log_message($text);
    }
    
    public static function log_message($text){
        $frm_payment_settings = new FrmPaymentSettings();
        if (!$frm_payment_settings->settings->ipn_log) {
            return;  // is logging turned off?
        }
            
        // Write to log
        $fp = fopen($frm_payment_settings->settings->ipn_log_file, 'a');
        fwrite($fp, $text . "\n\n"); 

        fclose($fp);  // close file
        chmod($frm_payment_settings->settings->ipn_log_file, 0600);
    }

    public static function dump_fields($fields) {
        // Used for debugging, this function will output all the field/value pairs
        // that are currently defined in the instance of the class using the
        // add_field() function.
        ksort($fields);
?>
<h3>FrmPaymentsHelper::dump_fields() Output:</h3>
<table width="95%" border="1" cellpadding="2" cellspacing="0">
    <tr>
        <td bgcolor="black"><b><font color="white">Field Name</font></b></td>
        <td bgcolor="black"><b><font color="white">Value</font></b></td>
    </tr> 

<?php foreach ($fields as $key => $value) { ?>
    <tr><td><?php echo $key ?></td>
        <td><?php echo urldecode($value) ?>&nbsp;</td>
    </tr>
<?php } ?>
</table>
<br/>
<?php
    }

}
