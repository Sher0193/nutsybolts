<?php

/**
 * ajax_handler.php
 * This script contains the logic for all AJAX requests within A2A.
 * The exact outcome depends on the specific request.
 *
 * All requests are required to have an "op" parameter specifying the operation to run
 * Some operations require a player ID in the "pid" parameter and a password in the "pw" parameter.
 */

require_once('./config.php');
require_once(LIB_DIR . 'AJAXController.php');
require_once(MODEL_DIR . 'Player.php');

if (STATUS_DOWN == 1) {
	print json_encode(array('error' => 'down'));
	exit;
}

try {
	$controller = new AJAXController($_GET['op'], $_POST);
	if ($controller->needsPlayer()) {
		$status = $controller->verifyPlayer($_POST['pid'], $_POST['pw'], $_SERVER['REMOTE_ADDR']);
		if (!$status) {
			error_log("Could not verify player for operation " . $_GET['op'] . "!");
			print json_encode(array('error' => 'noplayer'));
			exit;
		}
	}
		
	//error_log(memory_get_peak_usage());
	print $controller->getResponse();
}

catch (DatabaseException $e) { // database failure code goes here.  This keeps people from bring kicked.
	print json_encode(array('error' => 'database'));
	exit;
}


?>