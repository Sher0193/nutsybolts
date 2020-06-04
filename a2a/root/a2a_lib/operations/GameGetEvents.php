<?php

require_once(MODEL_DIR . 'Room.php');

class GameGetEvents extends AJAXOperation {
	function process() {
		$last_id = $this->request['id'];
		$room = new Room($this->player->getRoomID());
		$logs = $room->getLogs($this->player->getRoomID(), $last_id);
		return $logs;
	}
}

?>