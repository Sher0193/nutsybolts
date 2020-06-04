<?php

require_once MODEL_DIR . 'Logger.php';

class GameGetLogs extends AJAXOperation {
	function process() {
		$last_id = $this->request['l'];
		//$this->player->touch();
		
		$logger = Logger::getLogger($this->player->getRoomID());
		
		$start_time = time();
		
		// lazy loading - if there are no logs, sleep for 1 seconds up to a max time of 10 seconds
		//$logs = array();
		//while (!$logs && time() - $start_time < 10) {
			$logs = $logger->getLogs($last_id);
		//	if (!$logs)
		//		sleep(1);
		//}
		
		//$room->setPlayersAway();
		//if ($room->getStatus() == 0)
		//	$room->clearAwayPlayers();
		
		return $logs;
	}
}

?>