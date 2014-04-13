<?php

require_once(MODEL_DIR . 'Room.php');

class MessagePost extends AJAXOperation {
	function process() {
		$room = new Room($this->player->getRoomID());
		
		$message = $this->request['message'];
		$message = str_replace('%26', '&', $message);
		
		// strip_tags can be overzealous, removing constructs like "<3"
		// so make sure there's a closing HTML tag too
		if (preg_match('/<[^>]+>/', $message))
			$message = strip_tags($message);
		
		$message = substr($message, 0, 200);
			
		$status = $room->writePlayerMessage($this->player->getID(), $message);
		
		if (!$status) return $this->error(E_MESSAGEPOST_WRITEFAIL);
		
		$this->player->touch();
		
		return 1;
	}
}

?>