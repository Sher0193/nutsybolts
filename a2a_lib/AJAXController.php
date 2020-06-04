<?php

require_once LIB_DIR . 'AJAXOperation.php';
require_once LIB_DIR . 'AJAXErrorList.php';

class AJAXController {
	protected $operation;
	protected $request;
	protected $mapping;
	protected $player;

	function AJAXController($operation, $request) {
		$this->operation = $operation;
		$this->request = $request;
		$this->player = new Player(-1); // temporary fix for non-player requests
		$this->_loadMapping();
		//error_log("AJAX Operation $operation:" . print_r($this->request, true));
	}
	
	function needsPlayer() {
		if (!$this->mapping) $this->_loadMapping();
		
		if (!$this->mapping[$this->operation]) {
			return false;
		}
		
		return $this->mapping[$this->operation][1];
	}
	
	// validates the player's identity for this request
	// if the player is validated (based on their IP and password),
	// the $player property is set and the function returns true.
	// if not, the function returns false.
	function verifyPlayer($pid, $password, $ip) {
		$player = new Player($pid);
		if (!$player->load()) {
			error_log('AJAXController: Cannot load player ' . $pid);
			return false;
		}
		
		/* IP checking disabled for now for my own sanity
		if ($ip != $player->getIP()) {
			error_log('AJAXController: Player ' . $pid . ' is invalidated because of an IP address mismatch - the IP should be ' . $player->getIP() . ' but the request came from ' . $ip);
			return false;
		}*/
		
		if (!$player->verify($password)) {
			error_log('AJAXController: Player ' . $pid . ' is invalidated because of password mismatch - the password should be ' . $player->getPassword() . ' but the supplied password is ' . $password);
			return false;
		}
		
		if ($player->isDeleted())
			return false;
		
		$this->player = $player;
		return true;
	}

	function getResponse() {
		if (!$this->mapping) $this->_loadMapping();
		
		// make sure the operation specified is valid
		if (!isset($this->mapping[$this->operation])) {
			error_log("AJAXController - unmapped operation $this->operation");
			return;
		}

		// make sure the player is verified if we need to
		list($operation_name, $player_flag) = $this->mapping[$this->operation];
		if ($player_flag and !$this->player) {
			error_log("AJAXController - operation $this->operation requires a player");
			return;
		}
		
		require_once(OP_DIR . $operation_name . '.php');
		if (!class_exists($operation_name)) {
			error_log("AJAXController - nonexistent operation $this->operation, class name $operation_name");
			return;
		}

		$op_class = new $operation_name($this->request, $this->player);
		$result = $op_class->process();
		$json_response = json_encode($result);
		//error_log("JSON: $json_response");
		return $json_response;
	}
	
	protected function _loadMapping() {
		$this->mapping = array(
			'get_room_list' => array('RoomGetList', 0),
			'get_room_info' => array('RoomInfo', 0),
			'start_game' => array('GameStart', 1),
			'landing_status' => array('GameGetStatus', 1),
			'play' => array('CardPlay', 1),
			'logs' => array('GameGetLogs', 1),
			'judge_vote' => array('CardVote', 1),
			'post_message' => array('MessagePost', 1),
			'get_new_messages' => array('MessageGet', 1),
			'get_played_cards' => array('GetPlayedCards', 1),
			'has_judge_voted' => array('HasJudgeVoted', 1),
			'sign_off' => array('PlayerLeave', 1),
			'remove_player' => array('PlayerRemove', 1),
			'go_away' => array('PlayerAway', 1),
			'return' => array('PlayerReturn', 1),
			'rot13' => array('Rot13', 0),
			'ignore' => array('PlayerIgnore', 1),
			'unignore' => array('PlayerUnignore', 1),
			'ers' => array('StateTest', 0),
			'add_red' => array('AddRedCard', 0),
			'delete_red' => array('DeleteRedCard', 0),
			'add_green' => array('AddGreenCard', 0),
			'delete_green' => array('DeleteGreenCard', 0),
			'skip' => array('PlayerSkip', 1),
			'get_judge_order' => array('GetJudgeOrder', 0),
			'more' => array('MoreRounds', 1),
			'get_nouns' => array('NounGetAll', 0)
 		);
	}
}