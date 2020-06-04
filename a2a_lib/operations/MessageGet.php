<?php

require_once(MODEL_DIR . 'Room.php');

class MessageGet extends AJAXOperation {
	function process() {
		// just in case the player was away and came back to the game screen
		$this->player->touch();
		
		$room = new Room($this->player->getRoomID());
		
		// check for new messages
		$ignores = $this->player->getIgnores();
		
		$messages = $room->getMessages($this->request['id'], $ignores);
		if ($messages === false)
			return $this->error(E_MESSAGEGET_NOMESG);

		// set the "last received message" time
		// if we don't have any new messages, just return the old time
		$last_id = $this->request['id'];
		if ($messages) {
			foreach ($messages as $index => $message) {
				$last_id = $message['id'];
				unset($messages[$index]['id']);
			}
		}
		
		$room->setPlayersAway();
		
		$players = $room->getPlayerList();
		$player_status = array();
		foreach ($players as $player) {
			if ($player->isSkipped())
				$player_status[$player->getID()] = 2;
			else
				$player_status[$player->getID()] = $player->getAway();
		}
		
		// sadly, we must communicate the creator every time
		// since we don't know which player changed the creator
		$result = array('chat' => $messages, 'id' => $last_id, 'ps' => $player_status, 'cid' => $room->getCreator());
		
		return $result;
	}
}


?>