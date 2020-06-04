<?php

class LockManager {
	private $room_id;
	private $locks;
	private $debug;
	
	function __construct($rid) {
		$this->room_id = $rid;
		$this->locks = array();
	}
	
	function lock($name, $exclusive) {
		if ($exclusive) {
			$open_mode = 'w';
			$lock_mode = LOCK_EX;
			$locktype = ' shared';
		}
		else {
			$open_mode = 'r';
			$lock_mode = LOCK_SH;
			$locktype = ' exclusively';
		}
		
		$filename = SEMAPHORE_DIR . $name . $this->room_id . '.semaphore';
		if (!file_exists($filename))
			touch($filename); // in case the file doesn't exist yet
		
		//error_log('Attempting to lock ' . $filename . $locktype . '...');
		$fh = fopen($filename, $open_mode);
		if (flock($fh, $lock_mode)) {
			//error_log('...'  . $filename . ' locked.');
			$this->locks[$name] = $fh;
			return true;
		}
	}
	
	function lockExclusive($name) { return $this->lock($name, true);  }
	function lockShared($name)    { return $this->lock($name, false); }
	
	function unlock($name) {
		if (!isset($this->locks[$name])) {
			//error_log("$name not found in LockManager::unlock()");
			//error_log(print_r(array_keys($this->locks), true));
			return;
		}
		flock($this->locks[$name], LOCK_UN);
		fclose($this->locks[$name]);
		unset($this->locks[$name]);
	}
	
	function __destruct() {
		foreach ($this->locks as $name => $fh) {
			//error_log('Unlocking ' . $name . ' on destruct');
			flock($fh, LOCK_UN);
			fclose($fh);
			unset($this->locks[$name]);
		}
	}
}

?>