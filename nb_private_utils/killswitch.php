<?php

require_once('/home/mgoetz/nb_private_utils/config/nutsybolts.com');
require_once(MODEL_DIR . 'Room.php');
require_once(MODEL_DIR . 'Player.php');

$rooms = Room::getForCleanup();

$filename = '/home/mgoetz/nb_private_utils/reports/' . date('Ymd') . '.txt';

$fh = fopen($filename, 'w');
foreach ($rooms as $room) {
	fwrite($fh, "Room " . $room->getID() . " (" . $room->getName() . ")\n");
	fwrite($fh, "Round " . $room->getRoundNumber() . ' of ' . $room->getMaxRounds() . "\n");
	fwrite($fh, "Players:\n");
	
	$players = $room->getPlayerList(true);
	foreach ($players as $player) {
		fwrite($fh, "\t" . $player->getName() . ' (' . $player->getID() . ")\n");
	}
	
	fwrite($fh, "\nChat list:\n");
	$chats = $room->getMessages(0, array(), true);
	foreach ($chats as $chat) {
		$chat_player = $players[$chat['pid']];
		fwrite($fh, "\t" . $chat_player->getName() . ': ' . $chat['text'] . "\n");
	}
 	
	fwrite($fh, "\n\n--\n\n");	
	
	$room->cleanup();
}

fclose($fh);

print(file_get_contents($filename));
?>
