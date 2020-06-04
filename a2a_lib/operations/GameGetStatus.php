<?php

require_once(MODEL_DIR . 'Room.php');

class GameGetStatus extends AJAXOperation {
	function process() {
		// just in case the player was away and came back to the game screen
		$this->player->touch();

		$return = array();
		
		$room = new Room($this->player->getRoomID());
		
		$new_creator = $room->setPlayersAway();
	
		// check the room status if we're not the creator
		if (!$this->request['creator']) {
			if (!$room->load())
				return $this->error(E_ALL_ROOMLOAD);
			$return['stat'] = $room->getStatus();
			if ($return['stat'] == 1)
				$return['hash'] = $this->player->getHash(); // send the hash to the client because it's insecure if the client calculates it
		}

		$return['cid'] = $room->getCreator();
		
		// check for new messages
		$messages = $room->getMessages($this->request['id'], $this->player->getIgnores());
		if ($messages === false)
			return $this->error(E_GAMEGETSTATUS_MESGFAIL);
		
		// set the "last received message" time
		// if we don't have any new messages, just return the old time
		$last_id = $this->request['id'];
		if ($messages) {
			foreach ($messages as $index => $message) {
				$last_id = $message['id'];
				unset($messages[$index]['id']);
			}
		}
		
		$return['chat'] = $messages;
		$return['id'] = $last_id;
		
		$room->clearAwayPlayers();
		
		$players = $room->getPlayerList();
		if ($players === false)
			return $this->error(E_GAMEGETSTATUS_PLISTFAIL);
		$new_player_ids = array_keys($players);
			
		// check for new or removed players
		$player_ids = explode(',', $this->request['pids']);
		$new_players = array();
		$removed_players = array();
		foreach ($player_ids as $old_id) {
			if (!in_array($old_id, $new_player_ids))
				$removed_players[] = $old_id;
		}
		foreach ($new_player_ids as $new_id) {
			if (!in_array($new_id, $player_ids)) {
				$player = $players[$new_id];
				$new_players[] = array(
					'id' => $new_id,
					'name' => $player->getName(),
					'color' => '#' . $player->getColor()
				);
			}
		}
		
		$return['pl'] = $new_players;
		$return['rp'] = $removed_players;
		
		$player_status = array();
		foreach ($players as $player) {
			$player_status[$player->getID()] = $player->getAway();
		}
		
		$return['ps'] = $player_status;
	
		return $return;	
	}
}

?>