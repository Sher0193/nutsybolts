<?php

class LandingPackage {
	protected $players;
	protected $deleted_players;
	protected $log_id;
	protected $messages;
	protected $creator_id;
	protected $creator_name;
	
	function isCreator($pid) {
		return ($this->creator_id == $pid);
	}
	
	function getCreatorName() {
		return ($this->players[$this->creator_id]['name']);
	}
	
	function initPlayers($players, $ignores) {
		$this->players = array();
		
		foreach ($players as $player) {
			if ($player->isDeleted()) {			
				$this->deleted_players[$player->getID()] = $this->_getPlayerEntry($player);
			}
			else {
				$this->players[$player->getID()] = $this->_getPlayerEntry($player);
			}
		}
		
		foreach ($ignores as $ignored_id) {
			$this->players[$ignored_id]['ignored'] = 1;
		}
	}
	
	function getPlayers() { return $this->players; }
	function getDeletedPlayers() { return $this->deleted_players; }
	
	function initMessages($messages) {
		$this->messages = $messages;
	}
	
	function validatePlayer($player_id) {
		return isset($this->players[$player_id]);
	}
	
	function getMessages() { return $this->messages; }
	
	function setLogID($log_id) { $this->log_id = $log_id; }
	function getLogID() { return $this->log_id; }
	
	protected function _getPlayerEntry(Player $player) {
		$entry = array();
		$pid = $player->getID();
		if (!isset($this->players[$pid]['ignored'])) {
			$entry['ignored'] = 0;
		}
		
		if ($player->isCreator()) {
			$this->creator_id = $pid;
		}
		
		if ($player->getAway())
			$entry['idle'] = 1;
		else
			$entry['idle'] = 0;
			
		$entry['name'] = $player->getName();
		$entry['color'] = $player->getColor();
		return $entry;
	}
}

?>