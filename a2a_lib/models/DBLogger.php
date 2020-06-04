<?php

require_once(LIB_DIR . 'LockManager.php');
require_once(MODEL_DIR . 'Logger.php');

class DBLogger extends Logger {
	private $room_id;
	private $lock_manager;
	
	function __construct($room_id) {
		$this->room_id = $room_id;
		$this->lock_manager = new LockManager($room_id);
	}
	
	function getLogID() {
		$this->lock_manager->lockShared('log');
		$db = Database::connect();
		
		$rs = $db->execute('select max(id) from gamelog where room_id = ?', array($this->room_id));
		if (!$rs) return 0;
		
		if ($row = $rs->getRowNumeric()) {
			return $row[0];
		}
		else {
			return 0;
		}
		$this->lock_manager->unlock('log');
	}
	
	function getLogs($id) {
		$this->lock_manager->lockShared('log');

		$db = Database::connect();
		
		$rs = $db->Execute('select id, type, extra_info from gamelog where room_id = ? and id > ?',
			array($this->room_id, intval($id)));
		if (!$rs) return false;
			
		$logs = array();
		while ($row = $rs->getRowAssoc()) {
			$logs[] = array(
				'id' => $row['id'],
				't' => $row['type'],
				'd' => json_decode($row['extra_info'])
			);
		}
		
		$this->lock_manager->unlock('log');
		return $logs;
	}
	
	function addLog($type, $data) {
		// lock to avoid race conditions
		$this->lock_manager->lockExclusive('log');
		
		$db = Database::connect(); 
		$rs = $db->Execute('insert into gamelog(room_id, type, extra_info) values(?, ?, ?)', 
			array($this->room_id, $type, json_encode($data)));
		
		if (!$rs) return false;
		
		// STEP 2 - update the individual tables so the game still works on load
		/*$logger = $this->_getLogger($type);
		if (!$logger) {
			error_log('Error in DBLogger::addLog - _getLogger returned nothing.');
			return;
		}
		if (!$logger instanceof AbstractLogger) {
			error_log('Error in DBLogger::addLog - _getLogger did not return an AbstractLogger.');
			return;
		}
		
		$logger->init($room_id, $data);
		$logger->process();*/
		$db->execute('replace into roomlastseen(room_id, last_seen) values(?, now())', array($this->room_id));
		
		$this->lock_manager->unlock('log');
	}
}

?>