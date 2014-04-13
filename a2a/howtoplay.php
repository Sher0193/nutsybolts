<?php

require_once('../config.php');
require_once(LIB_DIR . 'A2ASmartyWrapper.php');

$smarty = new A2ASmartyWrapper();
$smarty->display('howtoplay.tpl');

?>