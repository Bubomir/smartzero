<?php

class FrmTwloAppController{
    function __construct() {
        add_action('admin_init', array(__CLASS__, 'include_updater'), 1);
        add_action('wp_ajax_frm_twilio_vote', array(__CLASS__, 'process_notification'));
        add_action('wp_ajax_nopriv_frm_twilio_vote', array(__CLASS__, 'process_notification'));
        
        add_action('frm_trigger_twilio_action', array(__CLASS__, 'trigger_sms'), 10, 3);
        
        // < 2.0 fallback
        add_action('frm_send_to_not_email', array(__CLASS__, 'send_sms'));
    }
    
    public static function path() {
        return dirname(dirname(__FILE__));
    }
    
    public static function include_updater(){
        if ( defined('DOING_AJAX') ) {
            return;
        }
        
        include_once(self::path() .'/models/FrmTwloUpdate.php');
        $update = new FrmTwloUpdate();
    }
    
    public static function process_notification(){
        //The URL example.com/wp-admin/admin-ajax.php?action=frm_twilio_vote&form=5

        if(!class_exists('FrmAppHelper'))
            return;

        $form_id = FrmAppHelper::get_param('form');
        
        header('Content-type: text/xml');
        echo '<Response>';

        $phone_number = $_REQUEST['From'];
        $vote = $_REQUEST['Body'];

        // If we've got good data, save the vote
        if ( (strlen($phone_number) >= 10) && !empty($vote) ) {
            // verify this came from Twilio
            if ( self::verify($_REQUEST) ) {
                $response = self::save_vote($phone_number, $vote, $form_id);
            } else {
                $response = __("Sorry, your vote doesn't appear to be authentic.", 'formidable');
            }
        }else{
            // Otherwise, give the user an example of how to vote
            $response = __("Sorry, I didn't understand that.", 'formidable');
        }

        // Send an SMS back to the person that voted letting them know that their vote was saved, or that there was an error of some sort
        echo '<Sms>'.$response.'</Sms>';
        echo '</Response>';

    }
    
    public static function trigger_sms($action, $entry, $form) {
		$defaults = array( 'from', 'to', 'message' );
		foreach ( $defaults as $default ) {
			$action->post_content[ $default ] = apply_filters( 'frm_content', $action->post_content[ $default ], $form, $entry );
			$action->post_content[ $default ] = do_shortcode( $action->post_content[ $default ] );
		}

		$to = preg_split( '/(,|;)/', $action->post_content['to'] );
		foreach ( (array) $to as $phone ) {
			self::send_message( $action->post_content['from'], trim( $phone ), $action->post_content['message'] );
		}
    }
    
    private static function send_message($from, $to, $message) {
        $from = preg_replace("/[^0-9]/", '', $from);
        if ( strpos($from, '1') !== 0 && strlen($from) == 10 ) {
            $from = '1'. $from;
        }
        $from = '+'. $from;
        
        $to = preg_replace("/[^0-9]/", '', $to);
        if ( strpos($to, '1') !== 0 && strlen($to) == 10 ) {
            $to = '1'. $to;
        }
        $to = '+'. $to;
        
        $message = wp_specialchars_decode(strip_tags($message), ENT_QUOTES );
        if ( strlen($message) > 160 ) {
            $message = substr($message, 0, 157) .'...';
        }
        
        $frm_twlo_settings = new FrmTwloSettings();
        $sid = $frm_twlo_settings->settings['account_sid'];
        $token = $frm_twlo_settings->settings['auth_token'];

        require_once(self::path() .'/twilio_api/Services/Twilio.php');
        $client = new Services_Twilio($sid, $token);
        try {
            $client->account->sms_messages->create($from, $to, $message);
        } catch (Exception $e) {
            error_log('Twilio SMS failed. '. $e->getMessage());
        }
    }
    
    /*
    * v2.0 fallback
    */
    public static function send_sms($atts){
        //'e', 'subject', 'mail_body', 'reply_to', 'reply_to_name', 'plain_text', 'attachments', 'form', 'email_key'
        if ( ! isset($atts['e']) || empty($atts['e']) || ! isset($atts['form']) || ! is_object($atts['form']) || ! isset($atts['email_key']) || ! isset($atts['mail_body']) || empty($atts['mail_body']) || ! isset($atts['form']->options['notification']) ) {
            return;
        }

        $notification = $atts['form']->options['notification'][$atts['email_key']];
        if ( ! isset($notification['twilio']) || ! $notification['twilio'] || ! isset($notification['twfrom']) || empty($notification['twfrom']) ) {
            return; //Twilio is not enabled with this form
        }
        
        self::send_message($notification['twfrom'], $atts['e'], $atts['mail_body']);
    }
    
    /*
    * Verify incoming text
    */
    private static function verify(){
        $frm_twlo_settings = new FrmTwloSettings();
        if ( empty($frm_twlo_settings->settings['account_sid']) ) {
            return true;
        }
        
        $account_sid = FrmAppHelper::get_param('AccountSid');
        if ( $frm_twlo_settings->settings['account_sid'] != $account_sid ) {
            return false;
        }
        
        return true;
    }

    /*
    * Save incoming text
    */
    private static function save_vote($phone_number, $vote, $form_id){
        // Just the digits, please
        $phone_number = preg_replace('/\D/', '', $phone_number);
        $message = __('Sorry, there was an error saving your vote.', 'formidable');

        $form = FrmForm::getOne($form_id);
        if ( ! $form || $form->logged_in ) {
            return $message;
        }

        $form->option = maybe_unserialize($form->options);

        $allowed = true;

        if ( isset($form->options['single_entry']) && $form->options['single_entry'] ) {
            //if form is limited to one, check to see if person has already voted
            $prev_entry = FrmEntry::getAll("it.form_id=$form_id and it.ip = '$phone_number'", '', ' LIMIT 1');
            if ($prev_entry)
                $allowed = false;
        }

        if($allowed){
            global $wpdb;

            $values = array('ip' => $phone_number, 'form_id' => $form_id);
            $values['description'] = serialize(array('browser' => 'Phone', 'referrer' => 'http://twilio.com'));
            $values['item_meta'] = array();

            $frm_fields = new FrmField();
            $fields = $frm_fields->getAll($form_id);
            unset($frm_fields);
            foreach($fields as $field){
                if(in_array($field->type, array('select', 'radio', 'scale', 'checkbox'))){
                    foreach($field->options as $opt){
                        if(strtolower($opt) == strtolower($vote))
                            $values['item_meta'][$field->id] = $opt;
                    }

                    if ( ! isset($values['item_meta'][$field->id]) && is_numeric($vote) ) {
                        $values['item_meta'][$field->id] = $field->options[(int) $vote - 1];
                    }
                }else{
                    $values['item_meta'][$field->id] = $vote;
                }
                unset($field);
            }

            if($id = FrmEntry::create( $values )){
                $message = __('Thank you, your vote has been recorded.', 'formidable');
                $wpdb->update( $wpdb->prefix .'frm_items', array('ip' => $phone_number), compact('id') );
            }
        }else{
            $message = __('Sorry, you can only vote once.', 'formidable');
        }

        return $message;
    }
}