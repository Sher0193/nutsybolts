<?php

/***
 * invite.php
 * This script services the "invite page" where people can enter their name upon being invited to a room.
 * 
 ***/

require_once('./config.php');
require_once(MODEL_DIR . 'Room.php');
require_once(MODEL_DIR . 'Player.php');
require_once(LIB_DIR . 'A2ASmartyWrapper.php');

function raiseError($code) {
	header('Location: /index.php?status=' . $code);
	exit;
}

// get the room ID number from the URL
$path_info = substr($_SERVER['PATH_INFO'], 1);
$pieces = explode('/', $path_info, 2);

$room_id = $pieces[0];
if (isset($pieces[1]))
	$password = $pieces[1];
else
	$password = '';

$room = new Room($room_id);
if (!$room->load()) {
	raiseError(-1);
	// room doesn't exist
}
/*if ($room->getStatus()) {
	raiseError(-2);
	// room has already started
}*/
// password does not match
if (!$room->validate($password)) raiseError(-3);

$players = $room->getPlayerList();

$player_array = array();
$color_array = array();
$player_ids = array();
$creator_name = '';
foreach ($players as $player) {
	if ($player->isCreator()) {
		$creator_name = $player->getName();
	}
	$player_array[$player->getID()] = $player->getName();
	$color_array[$player->getID()] = $player->getColor();
	$player_ids[] = $player->getID();
}

$smarty = new A2ASmartyWrapper();
$smarty->assign('creator', $creator_name);
$smarty->assign('players', $player_array);
$smarty->assign('player_ids', $player_ids);
$smarty->assign('colors', $color_array);
$smarty->assign('room_id', $room_id);
$smarty->assign('password', $password);
$smarty->assign('room_name', $room->getName());
$smarty->assign('started', $room->getStatus());
$smarty->assign('timestamp', time());
$smarty->assign('PACKED', JS_PACKED);
$smarty->assign('ANALYTICS', SHOW_ANALYTICS);
$smarty->assign('SHOW_ADS', SHOW_ADS);
$smarty->display('invite-beta.tpl');

?>