<?php

require_once('../config.php');
require_once(MODEL_DIR . 'Room.php');

$rooms = Room::getAll();

$report_text = '';
foreach ($rooms as $room) {
	$report_text .= "Room " . $room->getID() . " (" . $room->getName() . ")\n";
	$report_text .= "Round " . $room->getRoundNumber() . ' of ' . $room->getMaxRounds() . "\n";
	$report_text .= "Players:\n";
	
	$players = $room->getPlayerList();
	foreach ($players as $player) {
		$report_text .= "\t" . $player->getName() . ' (' . $player->getID() . ")\n";
	}
	
	$report_text .= "\nChat list:\n";
	$chats = $room->getMessages(0);
	foreach ($chats as $chat) {
		$chat_player = $players[$chat['pid']];
		$report_text .= "\t" . $chat_player->getName() . ': ' . $chat['text'] . "\n";
	}
 	
	$report_text .= "\n\n--\n\n";	
}

print $report_text;

mail('breakawayblue@gmail.com', 'Nutsy Bolts Room Report', $report_text);

?>