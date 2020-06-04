<?php

require_once MODEL_DIR . 'PusherLogger.php';

abstract class Logger {
	abstract function getLogID();
	abstract function getLogs($id);
	abstract function addLog($type, $data);
	
	static function getLogger($room_id) {
		return new PusherLogger($room_id);
	}
}

?>