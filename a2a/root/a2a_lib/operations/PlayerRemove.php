<?php

require_once(MODEL_DIR . 'Room.php');

class PlayerRemove extends AJAXOperation {
	function process() {
		$this->player->touch();
		
		
		$room = new Room($this->player->getRoomID());
		$room->load();
		
		$status = $room->removePlayer($this->request['rid']);
		if (!$status) return 0;
		return 1;
	}
}
?>