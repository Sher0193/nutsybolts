<?php

/***
 * Join-game.php
 * This script allows a player to join an existing room which has not started yet.
 * Intended result: Player joins the game and is forwarded to the landing page
 * Error conditions:
 *  o Room doesn't exist
 *  o Room has already started playing
 *  o Player's password doesn't match the room's
 *  o Room is full (based on the max_players parameter)
 */

require_once './config.php';
require_once MODEL_DIR . 'Room.php';
require_once MODEL_DIR . 'Player.php';
require_once MODEL_DIR . 'RedCard.php';
require_once LIB_DIR . 'Authenticator.php';


$room = new Room($_POST['roomid']);
// generic database failure, including "room does not exist"
if (!$room->load()) NBUtils::raiseError(-1);

// room has already started playing
//if ($room->getStatus() == 1) raiseError(-2);

// password does not match
if (!$room->validate($_POST['password'])) NBUtils::raiseError(-3);

// room is full already
$players = $room->getPlayerList();
$player_count = count($players);
if ($player_count >= $room->getMaxPlayers()) NBUtils::raiseError(-4);

// ignore duplicate players
foreach ($players as $player) {
	if ($player->getName() == $_POST['name'])// or $player->getIP() == $_SERVER['REMOTE_ADDR'])
		raiseError(-6);
}

// add the player to the room, communicate the player's ID by cookie and send them to the landing page
$player_id = $room->addPlayer($_POST['name'], $_SERVER['REMOTE_ADDR'], 0);
$player = new Player($player_id);

$room->updateCreator(); // force it to update the creator just in case the previous creator went away

$auth = new Authenticator();
$auth->authenticatePlayer($room->getID(), $player);

//header('Location: /landing.php/' . $room->getID() . '_' . $player->getHash());
header('Location: /landing.php/' . $room->getID() . '/' . $room->getPassword());

?>