<?php

class DeleteRedCard extends AJAXOperation {
	function process() {
		if (!isset($this->request['l']))
			return;
			
		$cid = $this->request['cid'];
		$db = Database::connect();
		if (!$db->execute('delete from redcards where id = ?', array($cid))) {
			error_log($db->ErrorMsg());
			return 0;
		}
		
		return $cid;
	}
}

?>