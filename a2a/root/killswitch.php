<?php

require_once('../config.php');
require_once(MODEL_DIR . 'Room.php');

$rooms = Room::getAll();

foreach ($rooms as $room) {
	$room->setPlayersAway();
	if (!$room->getActivePlayers()) {
		print "Killing room " . $room->getID() . "\n";
		$room->cleanup();
	}
}

?>