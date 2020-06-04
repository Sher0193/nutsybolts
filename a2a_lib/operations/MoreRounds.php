<?php

require_once(MODEL_DIR . 'Room.php');

class MoreRounds extends AJAXOperation {
	function process() {
		$lm = new LockManager($this->player->getRoomID());
		$lm->lockExclusive('morerounds');
		$room = new Room($this->player->getRoomID());	
		$room->load();
		
		if ($room->getMaxRounds() <= $room->getRoundNumber()) {
			$room->addMoreRounds(1);
		}
		
		$this->player->touch();
		
		$lm->unlock('morerounds');
	}
}

?>