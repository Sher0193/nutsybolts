<?php

require_once('./config.php');
require_once(MODEL_DIR . 'Room.php');
require_once(MODEL_DIR . 'Player.php');
require_once(MODEL_DIR . 'RoomDeck.php');
require_once(LIB_DIR . 'A2ASmartyWrapper.php');

$smarty = new A2ASmartyWrapper();

if (STATUS_DOWN == 1) {
	$smarty->display('down.tpl');
	exit;
}

try {

/* TO-DO: rewrite this so it uses cookies.
 * $inactive_id = Player::getInactiveIDByIP($_SERVER['REMOTE_ADDR']);
if ($inactive_id) {
	$inactive_player = new Player($inactive_id);
	$inactive_player->load();
	
	$inactive_room_id = $inactive_player->getRoomID();
	
	$inactive_room = new Room($inactive_room_id);
	$inactive_room->load();

	$smarty->assign('inactive_id', $inactive_id);
	$smarty->assign('inactive_room', $inactive_room_id);
	//$smarty->assign('inactive_hash', $inactive_player->getHash());
	$smarty->assign('inactive_pw', $inactive_player->getPassword());
	$smarty->assign('inactive_roomname', $inactive_room->getName());
}*/

// block player from creating a new game if they have created one recently.
$spam_flag = 0;
if (SPAMBLOCK_INTERVAL > 0)
	$spam_flag = Player::isRecentRoomCreator($_SERVER['REMOTE_ADDR'], SPAMBLOCK_INTERVAL);

$status = 0;
if (isset($_GET['status']))
	$status = $_GET['status'];

$smarty->assign('status', $status);
$smarty->assign('hostname', $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']);

if (isset($_GET['rn'])) {
	$smarty->assign('room_name', $_GET['rn']);
}

if ($inactive_id) {

}

$smarty->assign('PACKED', JS_PACKED);
$smarty->assign('ANALYTICS', SHOW_ANALYTICS);
$smarty->assign('SHOW_ADS', SHOW_ADS);
$smarty->assign('spam_flag', $spam_flag);
$smarty->display('index-beta2.tpl');

}
catch (DatabaseException $e) {
	error_log('Database fail.' . $e->getMessage());
	$smarty->display('failwhale.tpl');	
}

?>