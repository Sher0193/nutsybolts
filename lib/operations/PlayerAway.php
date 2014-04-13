<?php

class PlayerAway extends AJAXOperation {
	function process() {
		$this->player->setAway(1);
		
		/*$room = new Room($this->player->getRoomID());
		$active_players = $room->getActivePlayers();

		if (count($active_players) == 0) {
			$room->cleanup();
		}*/
	}
}