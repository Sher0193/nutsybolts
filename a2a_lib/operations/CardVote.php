<?php

require_once(MODEL_DIR . 'Room.php');

class CardVote extends AJAXOperation {
	function process() {
		if (!isset($this->request['win'])) return $this->error(E_ALL_MISSINGPARAM);
	
		$state = isset($this->request['ers']) ? $this->request['ers'] : 0;
	
		$pid = intval($this->request['win']);
		
		$room = new Room($this->player->getRoomID());
		if (!$room->load())
			return $this->error(E_ALL_ROOMLOAD);
		
		// make sure they're the judge
		if ($room->getJudgeNumber() != $this->player->getJudgeOrder()) {
			error_log("CardVote: non-judge " . $this->player->getName() . " attempting to vote.");
			return $this->error(E_CARDVOTE_NOTJUDGE);
		}
		
		$status = $room->vote($pid, $state);
		
		if ($status < 0)
			return $this->error($status);
		
		$this->player->touch();
		if ($this->player->isSkipped())
			$this->player->setUnskipped();
			
		$result = array();
		$result['stat'] = $status;
		
		if ($result['stat']) {	
			$card = $room->getDeck()->getGreenCard($room->getRoundNumber());
			if (!$card) return $this->error(E_CARDVOTE_NEWCARDFAIL);
			
			$result['card'] = $card[1]; // 0 is ID, 1 is card name, 2 is description
			$result['j'] = $room->getJudgeNumber();
		}
		
		return $result;
	}
}

?>