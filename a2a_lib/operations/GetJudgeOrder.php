<?php

require_once(MODEL_DIR . 'Room.php');

class GetJudgeOrder extends AJAXOperation {
	function process() {
		$room = new Room($this->request['r']);
		$room->load();
		return $room->getJudgeNumber();
	}
}

?>