<?php

class StateTest extends AJAXOperation {
	function process() {
		$state = $this->request['ers'];
		
		return $this->error(-1);
		
		if (!$state) {
			return ($this->error(-2, 1));
		}
		elseif ($state == 1) {
			return ($this->error(-2, 2));
		}
		elseif ($state == 2) {
			return 1;
		}
	}
}

?>