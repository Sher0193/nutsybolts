<?php

require_once MODEL_DIR . 'RedCard.php';
require_once MODEL_DIR . 'Room.php';

class PlayerSkip extends AJAXOperation {
	function process() {
		$this->player->touch();
		// include the room ID so the room can be updated.
		$skipped_player = new Player($this->request['sid'], $this->player->getRoomID());
		$skipped_player->voteToSkip($this->player->getID());
			
		if ($this->_checkForSkip($skipped_player)) {
			$skipped_player->setSkipped();
		}
	}
	
	private function _checkForSkip(Player $player) {
		$room = new Room($player->getRoomID());
		
		$vote_count = $player->getVotesToSkipMe();
		$player_count = count($room->getActivePlayers());
		
		if ($vote_count > ($player_count / 2))
			return true;
			
		return false;
	}
}

?>