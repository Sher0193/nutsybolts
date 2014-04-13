<?php

require_once(LIB_DIR . 'LockManager.php');

class FileLogger {
	private $room_id;
	private $lock_manager;
	
	function __construct($room_id) {
		$this->room_id = $room_id;
		$this->lock_manager = new LockManager($room_id);
	}
	
	function getLogID() {
		$this->lock_manager->lockShared('logs');
		$id = count($this->_getAllLogs());
		$this->lock_manager->unlock('logs');
		return $id;
	}
	
	function getLogs($id) {
		//error_log("getLogs: $id");
		$this->lock_manager->lockShared('logs');
		
		$logs = array_splice($this->_getAllLogs(), $id + 1);
		$this->lock_manager->unlock('logs');
		
		return $logs;
	}
	
	function addLog($type, $data) {
		// lock to avoid race conditions
		$this->lock_manager->lockExclusive('logs');
		
		$fh = fopen($this->_getFilename(), 'a');
		fwrite($fh, $type . "\t" . json_encode($data) . "\n");
		fclose($fh);
		
		$db = Database::connect();
		$db->execute('replace into roomlastseen(room_id, last_seen) values(?, now())', array($this->room_id));
		
		$this->lock_manager->unlock('logs');
	}
	
	function _getFilename() {
		$filename = LOG_DIR . $this->room_id . '.log';
		if (!file_exists($filename))
			touch($filename);
			
		return $filename;
	}
	
	function _getAllLogs() {
		$this->lock_manager->lockShared('logs');
		$unformatted_logs = file($this->_getFilename(), FILE_IGNORE_NEW_LINES);
		
		$logs = array();
		foreach ($unformatted_logs as $i => $log) {
			$pieces = explode("\t", $log);
			$logs[] = array('t' => $pieces[0], 'd' => json_decode($pieces[1]), 'id' => $i);
		}
		
		//error_log(print_r($logs, true));
		$this->lock_manager->unlock('logs');
		return $logs;
	}
}

?>