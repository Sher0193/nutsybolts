<?php

class PlayerReturn extends AJAXOperation {
	function process() {
		$this->player->setAway(0);	
	}
}

?>