<?php

class Services_Twilio_Rest_Account extends Services_Twilio_InstanceResource {

    protected function init($client, $uri) {
        $this->setupSubresources(
            'media',
            'messages',
            'sms_messages'
            //'applications',
            //'available_phone_numbers',
            //'outgoing_caller_ids',
            //'calls',
            //'conferences',
            //'incoming_phone_numbers',
            //'notifications',
            //'outgoing_callerids',
            //'recordings',
            //'short_codes',
            //'transcriptions',
            //'connect_apps',
            //'authorized_connect_apps',
            //'usage_records',
            //'usage_triggers',
            //'queues',
            //'sip'
        );

        /*$this->sandbox = new Services_Twilio_Rest_Sandbox(
            $client, $uri . '/Sandbox'
        );*/
    }
}
