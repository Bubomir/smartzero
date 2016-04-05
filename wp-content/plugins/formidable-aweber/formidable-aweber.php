<?php
/*
Plugin Name: Formidable to AWeber
Description: Send Posted results to AWeber
Version: 1.0.01
*/

//Controllers
require_once(dirname(__FILE__) .'/controllers/FrmAwbrAppController.php');
require_once(dirname(__FILE__) .'/controllers/FrmAwbrSettingsController.php');

$obj = new FrmAwbrAppController();
$obj = new FrmAwbrSettingsController();

include_once(dirname(__FILE__) .'/helpers/FrmAwbrAppHelper.php');
$obj = new FrmAwbrAppHelper();
unset($obj);

/***** SETUP SETTINGS OBJECT *****/
require_once(dirname(__FILE__) .'/models/FrmAwbrSettings.php');
