<?php

class PlayerIgnore extends AJAXOperation {
	function process() {
		$this->player->touch();
		$this->player->ignore($this->request['i']);
	}
}

?>