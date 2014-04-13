<?php

class Authenticator {
	function getRoomID() {
		$path_info = substr($_SERVER['PATH_INFO'], 1);
		preg_match('/^(\d+)\/?/', $path_info, $matches);
		return $matches[1];
	}
	
	function getRoomPassword() {
		$path_info = substr($_SERVER['PATH_INFO'], 1);
		preg_match('/^\d+\/(.*)$/', $path_info, $matches);
		return $matches[1];
	}
	
	function getPlayerInfo($room_id) {
		if (DEBUG_PIDPARAM && isset($_GET['pid'])) {
			$player_id = $_GET['pid'];
			$player = new Player($player_id);
			$player->load();
			return $player;
		}
		else {
			$player_hash = $_COOKIE['room' . $room_id];

			if (!isset($player_hash))
				throw new NoPlayerCookieException();
				
			$decrypted_hash = NBUtils::decrypt($player_hash);
			list($player_id, $password) = explode(' ', $decrypted_hash, 2);
			
			$player = new Player($player_id);
			$player->load();
			if ($player->getPassword() != $password)
				throw new PlayerPasswordException();
				
			if ($player->getRoomID() != $room_id)
				throw new PlayerRoomIDException();
		}
		
		return $player;
	}
	
	function authenticatePlayer($room_id, $player) {
		setcookie('room' . $room_id, $player->getHash(), time() + COOKIE_TIMEOUT);
	}
}

class NoPlayerCookieException extends Exception { }

class PlayerPasswordException extends Exception { }

class PlayerRoomIDException extends Exception { }

?>