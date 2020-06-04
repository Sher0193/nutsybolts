<?php

require_once MODEL_DIR . 'RedCard.php';
require_once(MODEL_DIR . 'Room.php');

class GetPlayedCards extends AJAXOperation {
	function process() {
		$room = new Room($this->player->getRoomID());
		
		// load the room to get the current round number
		if (!$room->load())
			return $this->error(E_ALL_ROOMLOAD);
		
		$known_players = explode(',', $this->request['pids']);
		$cards = $room->getPlayedCards($known_players);
		if ($cards === false)
			return $this->error(E_GETPLAYEDCARDS_CARDFAIL);
		
		$return = array('pc' => '');
		foreach ($cards as $pid => $card) {
			$return['pc'][] = array('pid' => $pid, 'card' => $card->getName());
		}
		if ($room->getPhase() == 2)
			$return['ph'] = 2;
		
		return $return;
	}
}

?>