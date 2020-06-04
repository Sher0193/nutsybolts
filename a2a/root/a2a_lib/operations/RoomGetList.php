<?php

require_once(MODEL_DIR . 'RoomList.php');

class RoomGetList extends AJAXOperation {
	function process() {
		$room_list = RoomList::getHomepageList($this->request['v'], $this->request['s']);

		$return_array = array();
		
		foreach ($room_list as $room) {
			$pl = array();	
			foreach ($room->getPlayerList() as $player) {
				$pl[] = $player->getName();
			}
			
			$return_array[] = array(
				'id' => $room->getID(),
				'name' => $room->getName(),
				'mp' => $room->getMaxPlayers(),
				'r' => $room->getRoundNumber(),
				'mr' => $room->getMaxRounds(),
				'p' => $room->isPublic() ? 0 : 1,
				'pl' => $pl,
				'd' => $room->getDeckID()
			);
		}
		
		return $return_array;
	}
}

?>