<?php

/***
 * create-game.php
 * Allows a user to create a new game of A2A.
 * Error conditions:
 *
 */

require_once './config.php';
require_once MODEL_DIR . 'RoomList.php';
require_once MODEL_DIR . 'Player.php';
require_once MODEL_DIR . 'RedCard.php';
require_once LIB_DIR . 'Authenticator.php';

if (RoomList::existsWithName($_POST['room_name'])) {
	header('Location: /index.php?status=-8&rn=' . $_POST['room_name']);
	exit;
}

$deck_cards = array();
$deck_id = $_POST['deck_id'];
if ($deck_id == -1 && isset($_POST['nouns'])) {
	$deck_cards = explode("\n", $_POST['nouns']);
}

$room = new Room(0, $_POST['room_name'], array(), $_POST['max_players'], $_POST['password'], 0, 1, $_POST['rounds'], 0, 0, $deck_id);
$room->create($deck_cards);

$player_id = $room->addPlayer($_POST['name'], $_SERVER['REMOTE_ADDR'], 1);

// communicate player ID
$player = new Player($player_id);
$auth = new Authenticator();
$auth->authenticatePlayer($room->getID(), $player);

//header('Location: /landing.php/' . $room->getID() . '_' . $player->getHash());
header('Location: /landing.php/' . $room->getID() . '/' . $room->getPassword());

?>