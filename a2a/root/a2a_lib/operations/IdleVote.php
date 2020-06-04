<?php

class IdleVote extends AJAXOperation {
	function process() {
		$this->player->touch();
		return $this->player->idleVote($this->request['v']);
	}
}

?>