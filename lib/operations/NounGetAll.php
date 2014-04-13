<?php

require_once MODEL_DIR . 'RoomDeck.php';

class NounGetAll extends AJAXOperation {
	function process() {
		$deck_id = 1;
		if (isset($this->request['d']))
			$deck_id = $this->request['d'];
			
		$room_deck = new RoomDeck(-1, $deck_id);
		return join("\n", $room_deck->getAllRedCards());
	}
}

?>