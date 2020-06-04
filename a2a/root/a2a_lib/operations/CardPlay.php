<?php

require_once MODEL_DIR . 'RedCard.php';
require_once MODEL_DIR . 'Room.php';

class CardPlay extends AJAXOperation {
	function process() {
		if (!isset($this->request['order'])) return $this->error(E_ALL_MISSINGPARAM);
		
		$state = isset($this->request['ers']) ? $this->request['ers'] : 0;
		
		$order = $this->request['order'];
		$result = $this->player->playCard($order, $state);
		
		if ($result < 0) {
			return $this->error($result);
		}
		
		$this->player->touch();
		if ($this->player->isSkipped())
			$this->player->setUnskipped();
		
		return $result;
	}
}

?>