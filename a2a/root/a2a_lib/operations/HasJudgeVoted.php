<?php

require_once(MODEL_DIR . 'Room.php');

class HasJudgeVoted extends AJAXOperation {
	function process() {
		$room = new Room($this->player->getRoomID());
		if (!$room->load())
			return $this->error(E_ALL_ROOMLOAD);
		
		$result = array('r' => $this->request['r'], 'j' => $room->getJudgeNumber());
		$result['win'] = $room->getWinner($this->request['r']);
		
		if ($result['win'] === false)
			return $this->error(E_HASJUDGEVOTED_WINNERFAIL);
		
		if ($result['win']) {
			$card = $room->getDeck()->getCurrentGreenCard($room->getRoundNumber());
			if (!$card)
				return $this->error(E_HASJUDGEVOTED_CARDFAIL);			
			
			$result['card'] = $card[1];
		}
		
		return $result;
	}
}

?>