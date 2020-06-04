<?php

class PlayerUnignore extends AJAXOperation {
	function process() {
		$this->player->touch();
		$this->player->unignore($this->request['i']);
	}
}

?>