<?php

require_once('./config.php');
require_once(MODEL_DIR . 'Player.php');
require_once(MODEL_DIR . 'Room.php');

function raiseError($code) {
	header('Location: /index.php?status=' . $code);
	exit;
}

$player_id = $_GET['pid'];
$password = $_GET['pw'];
$room_id = $_GET['rid'];

// check to make sure things are OK first
$player = new Player($player_id);
if (!$player->load()) { error_log('player no load'); raiseError(-1); }

if ($player->getRoomID() != $room_id) { error_log('room no work'); raiseError(-1); }

if ($player->getPassword() != $password) raiseError(-3);

// now set their session, mark them as "back", and send them back to the game
$player->touch();

$room = new Room($room_id);
$room->load();

setcookie('room' . $room_id, $player->getHash(), COOKIE_TIMEOUT);
header('Location: /game.php/' . $room_id . "/" . $room->getPassword() . "\n");
exit;

?>