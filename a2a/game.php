<?php

/***
 * game.php
 * This script displays the page where A2A is actually played.
 * It loads the player list, hand and any chat messages from the landing page.
 * 
 * Error conditions:
 * o Room does not exist
 * o Room has not started playing
 * o Player is not in room
 */

require_once('./config.php');
require_once(MODEL_DIR . 'Room.php');
require_once(MODEL_DIR . 'Player.php');
require_once(MODEL_DIR . 'RedCard.php');
require_once(MODEL_DIR . 'GamePackage.php');
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
	
	if (!$room->getStatus()) {
		// send them back to the landing page if the game hasn't started
		header('Location: /landing.php' . $_SERVER['PATH_INFO']);
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
	
	$smarty->assign('player_id', $player->getID());
	$smarty->assign('players', $package->getPlayers());
	$smarty->assign('deleted_players', $package->getDeletedPlayers());
	$smarty->assign('creator_flag', $package->isCreator($player_id));
	$smarty->assign('room_id', $room_id);
	$smarty->assign('password', $player->getPassword());
	$smarty->assign('room_name', $room->getName());
	$smarty->assign('hand', $package->getHand());
	$smarty->assign('messages', $package->getMessages());
	$smarty->assign('green_card', $package->getGreenCard());
	$smarty->assign('judge_name', $package->getJudgeName());
	$smarty->assign('is_judge', $package->isJudge($player_id));
	$smarty->assign('current_judge', $package->getJudgeNumber());
	$smarty->assign('round_number', $package->getRoundNumber());
	$smarty->assign('max_rounds', $room->getMaxRounds());
	$smarty->assign('played_flag', $package->hasPlayed($player_id));
	$smarty->assign('history', $package->getHistory());
	$smarty->assign('log_id', $package->getLogID());
	$smarty->assign('played_cards', $package->getPlayedCards());
	$smarty->assign('phase', $package->getPhase());
	$smarty->assign('pusher_key', PUSHER_APP_KEY);
	$smarty->assign('PACKED', JS_PACKED);
	$smarty->assign('ANALYTICS', SHOW_ANALYTICS);
	$smarty->assign('SHOW_ADS', SHOW_ADS);
	$smarty->display('game-beta.tpl');
}
catch (DatabaseException $e) {
	$smarty->display('failwhale.tpl');
}

?>