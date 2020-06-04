<?php

require_once(MODEL_DIR . 'LandingPackage.php');

class GamePackage extends LandingPackage {
	protected $history;
	protected $round_num;
	protected $judge_num;
	protected $hand;
	protected $played_cards;
	protected $green_card;
	
	function initHistory($history) {
		$this->history = $history;
	}
	function initRoundNumber($round_num) {
		$this->round_num = $round_num;
	}
	function initJudgeNumber($judge_num) {
		$this->judge_num = $judge_num;
	}
	function initHand($hand) {
		foreach ($hand as $index => $card) {
			$this->hand[$index] = $card->getName();
		}
	}
	function initPlayedCards($cards) {
		foreach ($cards as $pid => $card) {
			$this->played_cards[$pid] = $card->getName();
		}
	}
	
	function initGreenCard($greencard) {
		$this->green_card = $greencard[1];
	}
	
	function initPhase($phase) {
		$this->phase = $phase;
	}
	
	function getHistory() { return $this->history; }
	function getRoundNumber() { return $this->round_num; }
	function getJudgeNumber() { return $this->judge_num; }
	function isJudge($player_id) {
		if ($this->players[$player_id]['judge'] == $this->judge_num)
			return 1;
		
		return 0;
	}
	
	function getHand() {
		return $this->hand;	
	}
	
	function hasPlayed($player_id) {
		if (isset($this->played_cards[$player_id]))
			return 1;
		
		return 0;
	}
	
	function getPlayedCards() { return $this->played_cards; }
	
	function getGreenCard() {
		return $this->green_card;
	}
	
	function getJudgeName() {
		foreach ($this->players as $player) {
			if (isset($player['judge']) and $player['judge'] == $this->judge_num)
				return $player['name'];
		}
	}
	
	function getPhase() { return $this->phase; }
	
	protected function _getPlayerEntry(Player $player) {
		$entry = parent::_getPlayerEntry($player);
		
		if ($player->isSkipped())
			$entry['skipped'] = 1;
		else
			$entry['skipped'] = 0;	
		
		$entry['judge'] = $player->getJudgeOrder();
		$entry['score'] = $player->getScore();
		return $entry;
	}
}

?>