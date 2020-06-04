<?php

require_once('./config.php');
require_once(LIB_DIR . 'A2ASmartyWrapper.php');

$smarty = new A2ASmartyWrapper();
$smarty->assign('PACKED', JS_PACKED);
$smarty->assign('ANALYTICS', SHOW_ANALYTICS);
$smarty->assign('SHOW_ADS', SHOW_ADS);
$smarty->display('tutorial.tpl');

?>