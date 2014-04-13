<?php

class Rot13 extends AJAXOperation {
	function process() {
		return str_rot13($this->request['text']);
	}
}

?>