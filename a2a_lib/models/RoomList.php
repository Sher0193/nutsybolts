<?php

require_once MODEL_DIR . 'Room.php';

class RoomList {
	static function getHomepageList($visibility, $unstarted_only) {
		$db = Database::connect();

		$select_from = 'select r.id, r.name, r.max_players, r.round_num, r.phase, r.max_rounds, r.password, r.status, r.judge_num, r.deck_id from rooms r, roomlastseen rls';
		$where = ' where rls.room_id = r.id and rls.last_seen > NOW() - interval ? second';
		$params = array(HP_MAX_ROOM_AGE);
		
		if ($visibility == 'public')
			$where .= ' and r.password = ""';
		elseif ($visibility == 'private')
			$where .= ' and r.password <> ""';
			
		if ($unstarted_only)
			$where .= ' and r.status = 0';
			
		$sql = $select_from . $where . ' order by r.created_on desc';
		
		$rs = $db->Execute($sql, $params);

		$rooms = array();
		while ($row = $rs->GetRowAssoc()) {
			$rooms[] = new Room(
				$row['id'],
				stripslashes($row['name']),
				array(),
				$row['max_players'],
				$row['password'],
				$row['round_num'],
				$row['phase'],
				$row['max_rounds'],
				$row['status'],
				$row['judge_num'],
				$row['deck_id']
			);
		}

		return $rooms;
	}
	
	static function getForCleanup() {
		$db = Database::connect();

		$rs = $db->execute('select r.id, r.name, r.max_players, r.round_num, r.phase, r.max_rounds, r.password, r.status, r.judge_num '.
			'from rooms r, roomlastseen rls ' . 
			'where rls.room_id = r.id and rls.last_seen <= NOW() - interval ? second',
			array(HP_MAX_ROOM_AGE)
		);
		
		$rooms = array();
		while ($row = $rs->GetRowAssoc()) {
			$rooms[] = new Room(
				$row['id'],
				stripslashes($row['name']),
				array(),
				$row['max_players'],
				$row['password'],
				$row['round_num'],
				$row['phase'],
				$row['max_rounds'],
				$row['status'],
				$row['judge_num']
			);
		}

		return $rooms;
	}
	
	static function existsWithName($name) {
		$db = Database::connect();
		$rs = $db->Execute('select * from rooms where status = 0 and name = ?', array($name));
		if ($row = $rs->getRowNumeric())
			return true;
			
		return false;
	}
}

?>