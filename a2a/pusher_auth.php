<?php 

require_once('./config.php');
require_once(MODEL_DIR . 'Player.php');
require_once(LIB_DIR . 'Authenticator.php');
require_once(MODEL_DIR . 'Pusher.php');

$socket_id = $_POST['socket_id'];
$channel_name = $_POST['channel_name'];

$matches = array();
preg_match("/\d+$/", $channel_name, $matches);

if (!$matches) exit;
$room_id = $matches[0];

$auth = new Authenticator();

try {
	$player = $auth->getPlayerInfo($room_id);
}
catch (NoPlayerCookieException $e) {
	exit;
}
catch (PlayerPasswordException $e) {
	exit;
}
catch (PlayerRoomIDException $e) {
	exit;
}
	
$player_data = array(
	'name' => $player->getName(),
	'color' => $player->getColor(),
	'j' => $player->getJudgeOrder()
);

$pusher = new Pusher(PUSHER_APP_KEY, PUSHER_APP_SECRET, PUSHER_APP_ID);
print $pusher->presence_auth($channel_name, $socket_id, $player_id, $player_data);

?>