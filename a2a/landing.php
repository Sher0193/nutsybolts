<?php

/***
 * Landing.php
 * This script services the "landing page" where people can gather and chat before the game starts.
 * The game creator can deal the cards and start the game at any point.
 * 
 ***/

require_once('./config.php');
require_once(MODEL_DIR . 'Room.php');
require_once(MODEL_DIR . 'Player.php');
require_once(MODEL_DIR . 'LandingPackage.php');
require_once(LIB_DIR . 'Authenticator.php');
require_once(LIB_DIR . 'A2ASmartyWrapper.php');

$smarty = new A2ASmartyWrapper();

if (STATUS_DOWN == 1) {
	$smarty->display('down.tpl');
	exit;
}

try {
	$auth = new Authenticator();
	// get the room ID number from the URL
	$room_id = $auth->getRoomID();
	$room_password = $auth->getRoomPassword();
	
	$room = new Room($room_id);
	if (!$room->load()) {
		// generic database failure / room doesn't exist
		// send the user back home with an error message
		NBUtils::raiseError(-1);
	}
	if ($room->getPassword() != $room_password) {
		NBUtils::raiseError(-3);
	}
	if ($room->getStatus()) {
		// send them along to the game if the room has already started
		header('Location: /game.php' . $_SERVER['PATH_INFO']);
		exit;
	}
	
	// get the player ID and password
	try {
		$player = $auth->getPlayerInfo($room_id);
	}
	catch (NoPlayerCookieException $e) {
		header('Location: /invite.php' . $_SERVER['PATH_INFO']);
		exit;
	}
	catch (PlayerPasswordException $e) {
		NBUtils::raiseError(-5);
	}
	
	// verify that the player exists and belongs
	if ($player->getRoomID() != $room_id)
		NBUtils::raiseError(-5);
	
	$player_id = $player->getID();
	if ($player_id == -1)
		NBUtils::raiseError(-5);	
	
	$package = $room->getPackage($player_id);
	if (!$package->validatePlayer($player_id))
		NBUtils::raiseError(-5);
	
	$smarty->assign('creator_flag', $package->isCreator($player_id));
	$smarty->assign('creator', $package->getCreatorName());
	$smarty->assign('player_id', $player->getID());
	$smarty->assign('players', $package->getPlayers());
	$smarty->assign('messages', $package->getMessages());
	$smarty->assign('room_id', $room_id);
	$smarty->assign('room_pw', $room->getPassword());
	$smarty->assign('password', $player->getPassword());
	$smarty->assign('room_name', $room->getName());
	$smarty->assign('timestamp', time());
	$smarty->assign('hostname', $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']);
	//$smarty->assign('hash', $player_hash);
	$smarty->assign('log_id', $package->getLogID());
	$smarty->assign('pusher_key', PUSHER_APP_KEY);
	$smarty->assign('PACKED', JS_PACKED);
	$smarty->assign('ANALYTICS', SHOW_ANALYTICS);
	$smarty->assign('SHOW_ADS', SHOW_ADS);
	$smarty->display('landing-beta.tpl');
}
catch (DatabaseException $e) {
	$smarty->display('failwhale.tpl');
}

?>