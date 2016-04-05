<?php
/*
Plugin Name: Formidable Zapier
Description: Integrate with everything through Zapier
Version: 1.0
*/

include(dirname(__FILE__) .'/controllers/FrmZapAppController.php');
FrmZapAppController::load_hooks();

include(dirname(__FILE__) .'/controllers/FrmZapApiController.php');
FrmZapApiController::load_hooks();

