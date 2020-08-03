<?php

require_once(LIB_DIR . 'LockManager.php');
require_once('/home/dylan/Documents/pusher/vendor/autoload.php');

class PusherLogger {
	private $room_id;
	private $lock_manager;
	private $pusher;
		
	function __construct($room_id) {
		$this->room_id = $room_id;
		$this->pusher = new Pusher\Pusher(PUSHER_APP_KEY, PUSHER_APP_SECRET, PUSHER_APP_ID);
		$this->lock_manager = new LockManager($room_id);
	}
	
	// this function isn't really needed, is it?
	function getLogID() {
		return -1;
		
		/*
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
		*/
	}
	
	
	// or this one
	function getLogs($id) {
		return array();
		/*
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
		*/
	}
	
	function addLog($type, $data) {
		$db = Database::connect();
		$this->pusher->trigger('presence-room' . $this->room_id, $type, $data);
		$db->execute('replace into roomlastseen(room_id, last_seen) values(?, now())', array($this->room_id));
		
		//$this->lock_manager->unlock('log');
	}
}

?>
