<?php

class AddRedCard extends AJAXOperation {
	function process() {
		if (!isset($this->request['l']))
			return;
		
		$db = Database::connect();
		$db->execute('insert into redcards(deck_id, name) values(?, ?)', array($this->request['did'], $this->request['name']));
		
		return $db->Insert_ID();
	}
}

?>