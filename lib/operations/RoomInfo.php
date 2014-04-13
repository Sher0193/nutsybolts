<?php

require_once(MODEL_DIR . 'Room.php');

class RoomInfo extends AJAXOperation {
	function process() {
		$room = new Room($this->request['rid']);
		if (!$room->load())
			return $this->error(E_ALL_ROOMLOAD);
		$list = $room->getPlayerList();
		if ($list === false)
			return $this->error(E_ROOMINFO_PLISTFAIL);
		
		$creator = '';
		foreach ($list as $player) {
			if ($player->isCreator()) {
				$creator = $player->getName();
				break;
			}
		}
		
		$room_info = array(
			'creator' => $creator,
			'public' => $room->isPublic(),
			'rounds' => $room->getMaxRounds(),
			'max_players' => $room->getMaxPlayers()
		);
		
		$player_array = array();
		foreach ($list as $player) {
			$player_array[] = $player->getName();
		}
		$room_info['players'] = $player_array;
		return $room_info;
	}
}

?>