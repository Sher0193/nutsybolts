<?php

/**
 * start-game.php
 * Begins a room, setting its status to 1 and dealing the cards
 * Error conditions:
 *	 o room doesn't exist
 *  o room has already started
 *  o player is not in the room
 *  o player is not the creator
 *  o player's password or IP doesn't match
 */
 
require_once './config.php';
require_once MODEL_DIR . 'Room.php';
require_once MODEL_DIR . 'Player.php';
require_once MODEL_DIR . 'RedCard.php';

// try to load the room.
$room_id = $_POST['room_id'];
$room = new Room($room_id);
if (!$room->load()) {
	// error out to the homepage
	header('Location: /index.php?status=-1');
	exit;
}

// has the room already started?
if ($room->getStatus() == 1) {
	// error back to the homepage
	header('Location: /index.php?status=-2');
	exit;
}

$players = $room->getPlayerList();
$creator = null;
foreach ($players as $pid => $player) {
	if ($pid == $_POST['player_id']) {
		$creator = $player;
		break;
	}
}

// this player is not in the game
if (!$creator) {
	error_log('start-game.php: Player ' . $pid . ' tried to start room ' . $room_id . ' but they are not in this room.');
	header('Location: /index.php?status=-6');
	exit;
}

// make sure that they are the creator
if (!$creator->isCreator()) {
	error_log('start-game.php: Player ' . $pid . ' tried to start room ' . $room_id . ' but they are not the creator.');
	header('Location: /landing.php/' . $room_id . '?status=-1');
	exit;
}

// verify their identity
// if it fails, log them and send them back to the homepage.
if (!$creator->verify($_POST['password'])){ // or $creator->getIP() != $_SERVER['REMOTE_ADDR']) {
	
	//if ($creator->getIP() != $_SERVER['REMOTE_ADDR'])
		//error_log('start-game.php: Player ' . $pid . ' is invalidated because of an IP address mismatch - the IP should be ' . $creator->getIP() . ' but the request came from ' . $_SERVER['REMOTE_ADDR']);
	
	if (!$creator->verify($_POST['password']))
		error_log('start-game.php: Player ' . $pid . ' is invalidated because of a password mismatch - the password should be ' . $creator->getPassword() . ' but the player gave ' . $_POST['password']);
	
	header('Location: /index.php?status=-7');
	exit;
}

$room->start();

header('Location: /game.php/' . $room_id . '/' . $room->getPassword());
exit;

?>