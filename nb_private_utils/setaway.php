<?

if ($_SERVER['argv'][1] == 1)
	$config = 'nutsybolts.com';
else
	$config = 'echoes.nutsybolts.com';
	
require_once('/home/mgoetz/nb_private_utils/config/' . $config);
require_once(MODEL_DIR . 'RoomList.php');
require_once(MODEL_DIR . 'Player.php');
require_once(MODEL_DIR . 'Logger.php');

$rooms = RoomList::getHomepageList('all', 0);
print_r($rooms);

$db = Database::connect();

foreach ($rooms as $room) {
	print "Setting away for room " . $room->getID() . "\n";
	$logger = Logger::getLogger($room->getID());
		
	$rs = $db->execute('select p.id from players p, playerlastseen pls where pls.player_id = p.id and p.room_id = ? and pls.last_seen < now() - interval 30 second and p.is_away = 0',
		array($room->getID()));
		
	$away_players = array();
	while ($row = $rs->getRowNumeric()) {
		$away_players[] = $row[0];
		$logger->addLog(LOG_PLAYER_IDLE, $row[0]);
	}
	
	if ($away_players) {
		$away_string = implode(',', $away_players);
		$db->execute('update players set is_away = 1 where id in ('. $away_string . ')');
		
		$room->updateCreator();
	}
}

?>