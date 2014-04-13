<?php

require_once(LIB_DIR . 'Database.php');
require_once(LIB_DIR . 'LockManager.php');
require_once(MODEL_DIR . 'RoomDeck.php');
require_once(MODEL_DIR . 'Logger.php');
require_once(MODEL_DIR . 'RedCard.php');

class Room {
	protected $id;
	protected $name;
	protected $players;
	protected $max_players;
	protected $password;
	protected $round_num;
	protected $phase;
	protected $max_rounds;
	protected $status;
	protected $judge_num;
	protected $deck_id;
	
	protected $logger;
	protected $room_deck;

	function __construct($id, $name = '', $players = array(), $max_players = '', $password = '', $round_num = 0, $phase = 0, $max_rounds = 0, $status = 0, $judge_num = 0, $deck_id = 0) {
		$this->id = intval($id);
		$this->name = $name;
		$this->players = $players;
		$this->max_players = intval($max_players);
		$this->password = addslashes($password);
		$this->round_num = intval($round_num);
		$this->phase = intval($phase);
		$this->max_rounds = intval($max_rounds);
		$this->status = ($status) ? true : false;
		$this->judge_num = intval($judge_num);
		$this->deck_id = intval($deck_id);
		
		if (preg_match('/<[^>]+>/', $this->name))
			$this->name = strip_tags($this->name);
			
		$this->logger = Logger::getLogger($this->id);
		$this->room_deck = new RoomDeck($id, $deck_id);
	}

	function getID() { return $this->id; }
	function getName() { return $this->name; }
	function getMaxPlayers() { return $this->max_players; }
	function getRoundNumber() { return $this->round_num; }
	function getPhase() { return $this->phase; }
	function getJudgeNumber() { return $this->judge_num; }
	function getMaxRounds() { return $this->max_rounds; }
	function getPassword() { return $this->password; }
	function getPlayerList($deleted = false) {
		if (!$this->players) {
			$this->_loadPlayerList($deleted);
		}
		return $this->players;
	}
	function getStatus() { return $this->status; }
	function validate($password) {
		// public rooms have no password, so validate them no matter what they enter
		if (!$this->password) return true;
		return ($this->password == $password);
	}
	function isPublic() {
		if ($this->password == '') return true;
		return false;
	}
	function getDeckID() { return $this->deck_id; }
	function getDeck() {
		if (!$this->room_deck) {
			if (!$this->deck_id) $this->load();
			$this->room_deck = new RoomDeck($this->id, $this->deck_id);
		}
		
		return $this->room_deck;
	}
	function getLogger() { return $this->logger; }

	function load() {
		$db = Database::connect();
		$rs = $db->Execute('select name, max_players, round_num, phase, max_rounds, password, status, judge_num, deck_id
			from rooms where id = ?',
				array($this->id));

		$row = $rs->getRowNumeric();
		if (!$row) return false;

		list($this->name, $this->max_players, $this->round_num, $this->phase, $this->max_rounds, $this->password, $this->status, $this->judge_num, $this->deck_id) = $row;
		$this->name = stripslashes($this->name);
		$this->room_deck = new RoomDeck($this->id, $this->deck_id);
		
		return true;
	}

	protected function _loadPlayerList($deleted = false) {
		$db = Database::connect();
		
		$sql = 'select id, room_id, name, password, score, is_away, judge_order, ip_address, color, is_creator, deleted, skipped from players where room_id = ?';
		if (!$deleted)
			$sql .= ' and deleted = 0';
		
		$rs = $db->Execute($sql, array($this->id));

		$this->players = array();
		while ($row = $rs->getRowNumeric()) {
			$this->players[$row[0]] = new Player($row[0], $row[1], stripslashes($row[2]), $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11]);
		}
	}
		
	// Creates this room in the database.
	function create($deck_cards = array()) {
		if ($deck_cards && is_array($deck_cards) && count($deck_cards) > 0) {
			$this->deck_id = $this->room_deck->create($this->name, $deck_cards);
		}

		$db = Database::connect();
		
		$db->Execute('insert into rooms(name, max_players, round_num, phase, max_rounds, password, status, judge_num, deck_id)
			values(?, ?, ?, ?, ?, ?, ?, ?, ?)',
			array($this->name, $this->max_players, 0, 1, $this->max_rounds, $this->password, 0, 0, $this->deck_id));
		
		$this->id = $db->Insert_ID();
		
		// re-create the logger and deck because the room ID has just been assigned, and the old objects don't have it
		$this->logger = Logger::getLogger($this->id);
		$this->room_deck = new RoomDeck($this->id, $this->deck_id);

		$this->room_deck->shuffleRedCards();
		$this->room_deck->shuffleGreenCards();

		// return the ID
		return $this->id;
	}

	// retrieves all of the red cards that have been played this round
	// The cards are returned as an array of RedCard objects, indexed by card ID.
	// $pids - a list of player IDs to ignore
	function getPlayedCards($pids = array()) {
		// validate $pids first
		if (!is_array($pids)) $pids = array();		
		foreach ($pids as $key => $pid) {
			$pids[$key] = intval($pid);
		}
		
		$lm = new LockManager($this->id);
		$lm->lockShared('playedcards');
		$db = Database::connect();

		$sql = 'select pc.player_id, c.id, c.name
			from playedcards pc, redcards c, players p
			where c.id = pc.card_id and pc.round = ? and pc.player_id = p.id and p.room_id = ?';
			
		if ($pids)
			$sql .= ' and pc.player_id not in (' . implode(',', $pids) . ')';
		
		$rs = $db->Execute($sql, array($this->round_num, $this->id));

		// create an associative array of player_id => card
		$cards = array();
		while ($row = $rs->getRowNumeric()) {
			$cards[$row[0]] = new RedCard($row[1], $row[2]);
		}
		$lm->unlock('playedcards');
		
		return $cards;
	}

	function getActivePlayers() {
		$players = $this->getPlayerList();
		
		foreach ($players as $index => $player) {
			if ($player->getAway()) {
				unset($players[$index]);
			}
		}
		
		return $players;
	}
	
	function cleanup($soft = false) {
		$db = Database::connect();
		foreach ($this->getPlayerList() as $player) {
			$player->cleanup($soft);	
		}
	
		$db->execute('delete from roomredcards where room_id = ?', array($this->id));
		$db->execute('delete from roomgreencards where room_id = ?', array($this->id));
		$db->execute('delete from gamelog where room_id = ?', array($this->id));
		$db->execute('delete from roomlastseen where room_id = ?', array($this->id));
		$db->execute('delete from rooms where id = ?', array($this->id));
	}
	
	function vote($player_id, $state = 0) {
		$lm = new LockManager($this->id);
		$lm->lockExclusive('greencard');
		
		$db = Database::connect();
		
		if ($state == 0) {
			$rs = $db->Execute('update playedcards set winner = 1 where round = ? and player_id = ?',
			array($this->round_num, $player_id));
			if (!$rs) return E_CARDVOTE_PLAYEDFAIL;
		}

		if ($state < 2) {
			$this->setNextJudge();
			$this->round_num++; // this must go after setNextJudge because setNextJudge will load the room info.
		
			$rs = $db->Execute('update rooms set round_num = ? where id = ?',
				array($this->round_num, $this->id));
			if (!$rs) return E_CARDVOTE_ROOMFAIL;
		}

		if ($state < 3) {
			$player_id = intval($player_id);
			$rs = $db->Execute('update players set score = score + 1 where id = ? and room_id = ?',
				array($player_id, $this->id));
			if (!$rs) return E_CARDVOTE_SCOREFAIL;
		}

		$lm->unlock('greencard');
		$card = $this->room_deck->getGreenCard($this->round_num);
		$this->logger->addLog(LOG_CARD_VOTED, array('p' => $player_id, 'c' => $card[1], 'j' => $this->judge_num));
		$this->switchToPlay();
		return 1;
	}
	
	function checkSkipJudge($skipped_pid = -1) {
		$lm = new LockManager($this->id);
		$lm->lockShared('judge');

		$this->_loadPlayerList();
		$this->load();
		
		foreach ($this->players as $pid => $player) {
			if ($skipped_pid == -1 or $pid == $skipped_pid) {
				if ($player->getJudgeOrder() == $this->judge_num && $player->isSkipped()) {
					$lm->unlock('judge'); // must release this lock because it's about to get an exclusive lock in setNextJudge 
					$this->setNextJudge();
					return true;  // return so there is no error on trying to unlock again
				}
			}
		}
		$lm->unlock('judge');
		return false;
	}
	
	function setNextJudge() {
		// use a semaphore to prevent race conditions
		$lm = new LockManager($this->id);
		$lm->lockExclusive('judge');

		$this->_loadPlayerList();
		
		$judges = array();
		foreach ($this->players as $pid => $player) {
			$judges[$player->getJudgeOrder()] = $player;
		}
	
		$this->load(); // get the latest judge number just in case another thread updated it previously
		
		$this->judge_num++;
		if ($this->judge_num > count($this->players))
			$this->judge_num = 1;

		$old_judge_num = $this->judge_num;
			
		while ($judges[$this->judge_num]->isSkipped()) {
			$this->judge_num++;
			if ($this->judge_num > count($this->players))
				$this->judge_num = 1;
				
			if ($this->judge_num == $old_judge_num) {
				// presumably, this means that we've gone all the way around and everyone is skipped
				$this->cleanup();
				return;
			}
		}

		$db = Database::connect();
		$db->execute('update rooms set judge_num = ? where id = ?',
			array($this->judge_num, $this->id));
		
		$lm->unlock('judge');
	}

	function writePlayerMessage($pid, $msg) {
		$db = Database::connect();
		
		$rs = $db->Execute('insert into chatlog(player_id, message, stamp)
			values(?, ?, ?)', array($pid, $msg, time()));
		
		if (!$rs) return 0;
		$this->logger->addLog(LOG_NEW_MESSAGE, array('p' => $pid, 'msg' => stripslashes($msg)));
		return 1;
	}
	
	// returns all messages that have been sent since the provided message ID
	// Return value: an array of arrays of the format (name => player name, message => message)
	function getMessages($id, $ignores = array(), $soft = false) {
		$db = Database::connect();
		
		$soft_sql = '';
		if (!$soft)
			$soft_sql = 'and p.deleted = 0 ';
		
		$sql = 'select p.id, c.message, c.id
			from players p, chatlog c
			where p.room_id = ? and c.player_id = p.id and c.id > ? ' . $soft_sql . 'order by c.stamp';
		
		$rs = $db->Execute($sql, array($this->id, $id));
		
		if (!$rs) return false;
		
		$messages = array();
		while ($row = $rs->getRowNumeric()) {
			if (in_array($row[0], $ignores)) continue; // skip messages from anyone who is being ignored
			$messages[] = array('pid' => $row[0], 'text' => $row[1], 'id' => $row[2]);
		}
		
		return $messages;
	}

	function start() {
		$db = Database::connect();
		
		$lm = new LockManager($this->id);
		$lm->lockShared('players');
		$player_list = $this->getPlayerList();

		// reorder judges!
		$judge_ids = NBUtils::shuffle(array_keys($player_list));

		foreach ($judge_ids as $order => $id) {
			// add one to the judge order because it's one-indexed
			$rs = $db->Execute('update players set judge_order = ? where id = ?',
				array($order + 1, $id));
		}
		
		// set the status after the cards are dealt to avoid inconstencies
		$this->status = 1;
		$rs = $db->Execute('update rooms set status = 1, judge_num = 1, round_num = 1 where id = ?', array($this->id));
		$this->logger->addLog(LOG_GAME_START, '');
		$lm->unlock('players');
	}

	function addPlayer($name, $ip, $creator) {
		// process the name and IP address for potential SQL issues
		$name = addslashes(strip_tags($name));
		if (!preg_match('/^\d+\.\d+\.\d+\.\d+$/', $ip)) return false;
		
		$password = NBUtils::getPassword(4);
		$color = NBUtils::getPlayerColor();

		$lm = new LockManager($this->id);
		$lm->lockExclusive('players');
		$db = Database::connect();
		
		$new_judge_flag = 0;
		
		if ($this->status) {
			$lm->lockExclusive('judge');
			$judge = rand(1, count($this->getActivePlayers()));
			
			$db->execute('update players set judge_order = judge_order + 1 where judge_order >= ? and room_id = ?', array($judge, $this->id));
			
			if ($judge <= $this->judge_num) {
				$db->execute('update rooms set judge_num = judge_num + 1 where id = ?', array($this->id));
				$new_judge_flag = 1;
			}
			
			$lm->unlock('judge');
		}
		else {
			$judge = 0;
		}
		
		$rs = $db->Execute('insert into players(room_id, name, score, is_away, judge_order, ip_address, password, color, is_creator)
			values(?, ?, 0, 0, ?, ?, ?, ?, ?)',
			array($this->id, $name, $judge, $ip, $password, $color, $creator));
		
		if (!$rs) {
			$lm->unlock('players');
			return false;
		}
		
		$new_pid = $db->insert_id();
		
		$rs = $db->execute('insert into playerlastseen(player_id, last_seen) values(?, NOW())', array($new_pid));
		
		// deal this player their cards
		$db->StartTrans(); // disable autocommit for better performance (theoretically)

		$player = new Player($new_pid);	
		for ($i = 0; $i < PLAYER_CARDS; $i++)
			$player->addCard($this->room_deck->getNextRedCard(), $i);

		$db->CompleteTrans();
		
		$lm->unlock('players');
		
		$player_data = array(
			'id' => $new_pid,
			'name' => $name,
			'color' => $color
		);
		
		if ($this->status) {
			$player_data['j'] = $judge;
			if ($new_judge_flag)
				$player_data['n'] = 1;
		}
		
		$this->logger->addLog(LOG_NEW_PLAYER, $player_data);
			
		// get the ID
		return $new_pid;
	}
	
	function removePlayer($pid, $soft = true) {	
		$data = array('p' => $pid);
		
		$player = new Player($pid);
		$player->load();
		if ($player->isDeleted()) return;
		$judge_order = $player->getJudgeOrder();
		$status = $player->cleanup($soft);
		
		if ($this->status) {
			// update the judge ordering, including if we deleted the judge.
			$db = Database::connect();
			
			$lm = new LockManager($this->id);
			$lm->lockExclusive('judge');
			$db->execute('update players set judge_order = judge_order - 1 where judge_order > ? and room_id = ?', array($player->getJudgeOrder(), $this->id));
			$lm->unlock('judge');
			
			if ($judge_order <= $this->judge_num) {
				$this->judge_num--;
				$db->execute('update rooms set judge_num = ? where id = ?', array($this->judge_num, $this->id));
				
				if ($judge_order == $this->judge_num + 1)
					$this->setNextJudge();
				
				$data['j'] = $this->judge_num;
			}
			
			if ($this->phase == 1 && $this->checkAllPlayed())
				$data['p2'] = 1;
		}
		
		$this->logger->addlog(LOG_REMOVED_PLAYER, $data);
		
		return $status;
	}
	
	// if everyone but the judge and skipped players has played a card, change to the judge.
	function checkAllPlayed() {
		$lm = new LockManager($this->id);
		$lm->lockShared('judge');

		$played_cards = $this->getPlayedCards(array());
		$this->_loadPlayerList();
		
		$phase2_flag = true;
		foreach ($this->players as $pid => $player) {			
			if (!isset($played_cards[$pid]) && $player->getJudgeOrder() != $this->judge_num && !$player->isSkipped()) {
				$phase2_flag = false;
			}
		}
		$lm->unlock('judge'); // need this to avoid a deadlock when we switch to judge
		
		if ($phase2_flag) {
			$this->switchToJudge();
			return true;
		}
		
		return false;
	}
	
	function switchToJudge() {
		$db = Database::connect();
		$rs = $db->execute('update rooms set phase = 2 where id = ?', array($this->id));
		if (!$rs) return false;
		
		// make sure the judge did not go idle this round
		$this->checkSkipJudge(-1);
		
		return true;
	}
	
	function switchToPlay() {
		$db = Database::connect();
		$rs = $db->execute('update rooms set phase = 1 where id = ?', array($this->id));
		if (!$rs) return false;
		
		return true;
	}

	
	// in case the room creator has gone idle during the landing phase, switch the creator flag to the next non-idle person
	// return 1 if we update the creator, 0 if not
	function updateCreator() {
		// use a semaphore to prevent race conditions
		$lm = new LockManager($this->id);
		$lm->lockExclusive('creator');

		$this->_loadPlayerList();
		
		for ($i = 0; $i < count($this->players); $i++) {
			$ids = array_keys($this->players);
			if ($this->players[$ids[$i]]->isCreator()) {
				if ($this->players[$ids[$i]]->getAway() == 1) {
					$j = $i;
					do {
						$j++;
						if ($j >= count($this->players))
							$j = 0;
						
						if ($this->players[$ids[$j]]->getAway() == 0) {
							$this->players[$ids[$j]]->setCreator(1);
							$this->players[$ids[$i]]->setCreator(0);
							$this->logger->addLog(LOG_NEW_CREATOR, $ids[$j]);
							$lm->unlock('creator');
							return 1;
						}
					} while ($j != $i);
					
					//$this->cleanup();
					$lm->unlock('creator');
					return 0;
				}
				
				$lm->unlock('creator');
				return 0;
			}
		}
		
		// we don't have a creator; set the first player to the creator
		$this->players[$ids[0]]->setCreator(1);
		$this->logger->addLog(LOG_NEW_CREATOR, $ids[0]);
		$lm->unlock('creator');
		return 1;
	}
	
	function getCreator() {
		// use a semaphore to prevent race conditions
		$lm = new LockManager($this->id);
		$lm->lockShared('creator');

		$this->_loadPlayerList();
		
		foreach ($this->players as $pid => $player) {
			if ($player->isCreator()) {
				$lm->unlock('creator');
				return $pid;
			}
		}
		$lm->unlock('creator');
		return 0;
	}
	
	function getHistory() {
		$db = Database::connect();
		
		$sql = "SELECT pc.round, pc.player_id as winner, rc.name as noun, gc.name as adjective
			from playedcards pc, players p, redcards rc, greencards gc, roomgreencards rgc
			where pc.winner = 1 and p.id = pc.player_id and pc.card_id = rc.id and p.room_id = rgc.room_id
			and rgc.card_order = pc.round and rgc.card_id = gc.id and p.room_id = ?
			order by pc.round";
		
		$rs = $db->Execute($sql, array($this->id));
		return $rs->getRowsAssoc();
	}
	
	function addMoreRounds($round_count) {
		$db = Database::connect();
		
		$this->max_rounds += $round_count;
		$db->execute('update rooms set max_rounds = ? where id = ?', array($this->max_rounds, $this->id));
		
		$greencard = $this->room_deck->getGreenCard($this->round_num);
		$this->logger->addLog('mr', array('m' => $this->max_rounds, 'g' => $greencard[1], 'j' => $this->getJudgeNumber()));
	}
	
	function getPackage($player_id) {
		if ($this->status == 0)
			$package = new LandingPackage();
		else
			$package = new GamePackage();
			
		$lm = new LockManager($this->id);
		$lm->lockShared('creator');
		$lm->lockShared('logs');
		$lm->lockShared('players');
		
		if ($this->status) {
			$lm->lockShared('judge');
			$lm->lockShared('greencard');
		}
		
		$this->load();
		
		$deleted_flag = ($this->status) ? true : false;
		
		$this->_loadPlayerList($deleted_flag);
		
		$player = $this->players[$player_id];
		$ignores = $player->getIgnores();
		
		$package->initMessages($this->getMessages(0, $ignores));
		$package->initPlayers($this->players, $ignores);
		$package->setLogID($this->logger->getLogID());
		
		if ($this->status) {
			$package->initGreenCard($this->getDeck()->getGreenCard($this->round_num));
			$package->initHistory($this->getHistory());
			$package->initPlayedCards($this->getPlayedCards());
			$package->initHand($this->players[$player_id]->getHand());
			$package->initJudgeNumber($this->getJudgeNumber());
			$package->initRoundNumber($this->getRoundNumber());
			$package->initPhase($this->phase);
			
			$lm->unlock('greencard');
			$lm->unlock('judge');
		}
		
		$lm->unlock('players');
		$lm->unlock('logs');
		$lm->unlock('creator');
		
		return $package;
	}
}

?>