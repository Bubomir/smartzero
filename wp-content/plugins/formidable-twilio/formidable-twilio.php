<?php
/*
Plugin Name: Formidable Twilio
Description: Accept SMS votes for a poll or send texts from forms
Version: 1.02
Plugin URI: http://formidablepro.com/
Author URI: http://strategy11.com
Author: Strategy11
*/


// Settings
require_once(dirname(__FILE__) .'/models/FrmTwloSettings.php');

//Controllers
require_once(dirname(__FILE__) .'/controllers/FrmTwloAppController.php');
require_once(dirname(__FILE__) .'/controllers/FrmTwloSettingsController.php');

$obj = new FrmTwloAppController();
$obj = new FrmTwloSettingsController();


/*
[plugin] => frm_twilio
[action] => vote
[form] => 9  
[AccountSid] => ACbb912381029rjhwe908700f63099f  
[Body] => red
[ToZip] => 94949
[FromState] => UT
[ToCity] => NOVATO
[SmsSid] => SM9wewl234234892171b2689c
[ToState] => CA
[To] => 5555552671
[ToCountry] => US
[FromCountry] => US
[SmsMessageSid] => SM9056109809sdfj987171b2689c
[ApiVersion] => 2008-08-01
[FromCity] => S SALT LAKE
[SmsStatus] => received
[From] => 8015551108
[FromZip] => 84111
*/