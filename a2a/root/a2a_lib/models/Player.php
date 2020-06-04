<?php

require_once(LIB_DIR . 'Database.php');

class Player {
	protected $id;
	protected $name;
	protected $hand; // array of RedCard objects
	protected $score;
	protected $is_away;
	protected $judge_order;
	protected $ip;
	protected $password;
	protected $room_id;
	protected $color;
	protected $creator;
	protected $deleted;
	protected $skipped;
	
	public $timeout;

	function __construct($id, $roomid = 0, $name = '', $password = '', $score = 0, $is_away = 0, $judge_order = 0, $ip = '', $color = '', $creator = 0, $deleted = 0, $skipped = 0) {
		$this->id = intval($id);
		$this->room_id = intval($roomid);
		$this->name = $name;
		$this->password = $password;
		$this->score = $score;
		$this->is_away = $is_away;
		$this->judge_order = $judge_order;
		$this->ip = $ip;
		$this->color = $color;
		$this->creator = $creator;
		$this->deleted = $deleted;
		$this->skipped = $skipped;
	}

	function getID() { return $this->id; }
	function getName() { return $this->name; }
	function getScore() { return $this->score; }
	function getAway() { return $this->is_away; }
	function getJudgeOrder() { return $this->judge_order; }
	function getIP() { return $this->ip; }
	function verify($password) { return ($password == $this->password); }
	function getPassword() { return $this->password; }
	function getRoomID() { return $this->room_id; }
	function getColor() { return $this->color; }
	function isCreator() { return $this->creator; }
	function increaseScore() { $this->score++; }
	function getHand() {
		if (!$this->hand) $this->_loadHand();
		return $this->hand;
	}
	function getHash() {
		if (!isset($this->password) || !$this->password) {
			$this->load();
		}
		
		$text = $this->id . ' ' . $this->password;
		//overkill much?  nah.
		$encrypt = hash('md5', $text);
		return $encrypt;
	}
	function isDeleted() { return $this->deleted; }
	function isSkipped() { return $this->skipped; }

	function load() {
		if (!$this->id) return false;
		$db = Database::connect();
		$rs = $db->execute('select room_id, name, score, is_away, judge_order, ip_address, password, color, is_creator, deleted, skipped
			from players where id = ?', array(intval($this->id)));
		$player = $rs->getRowNumeric();
		if (!$player) return false;

		$this->room_id = $player[0];
		$this->name = stripslashes($player[1]);
		$this->score = $player[2];
		$this->is_away = $player[3];
		$this->judge_order = $player[4];
		$this->ip = $player[5];
		$this->password = $player[6];
		$this->color = $player[7];
		$this->creator = $player[8];
		$this->deleted = $player[9];
		$this->skipped = $player[10];
		return true;
	}

	/*function discard($card_name) {

	}*/

	// submits the card from a player's hand
	// takes the card's position within the hand, and a possible error state
	// state 1 - remove card failed
	// state 2 - get new card failed
	// returns false on failure, or the name of the new card on success
	function playCard($card_order, $state = 0) {
		$db = Database::connect();
		$card_order = intval($card_order);
		
		// verify that the card order falls within bounds
		if ($card_order < 0 or $card_order > PLAYER_CARDS - 1)
			return E_CARDPLAY_OOB;
		
		// retrieve the card ID
		$hand = $this->getHand();
		if (!$hand) return E_CARDPLAY_NOHAND;
		$card_id = $hand[$card_order]->getID();

		$room = new Room($this->room_id);
		$room->load();
		$round = $room->getRoundNumber();
		
		// remove the card from the hand
		if ($state == 0) {
			$rs = $db->execute('insert into playedcards(player_id, round, card_id, winner)
				values(?, ?, ?, 0)',
				array($this->id, $round, $card_id));
			if (!$rs) {
				// did it fail because there was a duplicate row?
				if (strpos($db->ErrorMsg(), 'Duplicate entry') !== false)
					return E_CARDPLAY_DUPLICATE;
				
				return E_CARDPLAY_NOINSERT;
			}
		}
		
		if ($state < 2) {
			if ($this->_removeCard($card_order))
				return E_CARDPLAY_NOREMOVE;
		}
		
		$log = array('n' => $hand[$card_order]->getName(), 'p' => $this->id);
		if ($room->checkAllPlayed())
			$log['j'] = 1;
		
		$logger = $room->getLogger();
		$logger->addLog(LOG_CARD_PLAYED, $log);
		
		// now get the new card
		$deck = $room->getDeck();
		$new_card = $deck->getNextRedCard();
		if (!$new_card) return E_CARDPLAY_NONEW;
		$this->addCard($new_card, $card_order);
	
		return $new_card->getName();
	}
	
	// deletes a card from the a player's hand
	// Takes the position of the card in the player's hand as an integer
	// this function does update the database.
	protected function _removeCard($card_order) {
		$card_order = intval($card_order);
		$db = Database::connect();
		unset($this->hand[$card_order]);
		$db->execute('delete from hands where player_id = ? and card_order = ?',
			array($this->id, $card_order));
	}
	
	// takes a RedCard object and a position in the hand, and adds it to the hand
	// returns true on success, false on failure
	function addCard(RedCard $card, $order) {
		$order = intval($order);
		
		$this->hand[$order] = $card;
		$card_id = intval($card->getID());
		$db = Database::connect();
		$result = $db->execute('insert into hands(player_id, card_id, card_order)
			values(?, ?, ?)',
			array($this->id, $card_id, $order));
			
		return $result;
	}

	// This function performs cleanup when a player leaves the room.
	// All of his cards are removed from his hand, and he is deleted from the database.
	function cleanup($soft = false) {
		$db = Database::connect();
		
		if ($soft) {
			$db->execute('update players set deleted = 1 where id = ?', array($this->id));
		}
		else {
			if ($this->hand) {
				foreach ($this->hand as $order => $card) {
					$this->_removeCard($order);
				}
			}
			
			$db->execute('delete from hands where player_id = ?', array($this->id));
			$db->execute('delete from playerlastseen where player_id = ?', array($this->id));
			$db->execute('delete from ignores where player_id = ? or ignored_player_id = ?',
				array($this->id, $this->id));
			$db->execute('delete from skipvote where voting_player_id = ? or skipped_player_id = ?',
				array($this->id, $this->id));
			$db->execute('delete from playedcards where player_id = ?', array($this->id));
			$db->execute('delete from chatlog where id = ?', array($this->id));
			return $db->execute('delete from players where id = ?', array($this->id));
		}
	}

	function setAway($status) {
		 // protect against bad data by explicitly converting to bool
		/*$status = ($status) ? 1 : 0;
		$this->is_away = $status;
		
		$db = Database::connect();
		$db->execute('update players set is_away = ? where id = ? and is_away != ?',
			array($status, $this->id));
*/
	}
	
	function setCreator($creator_flag) {
		$creator_flag = ($creator_flag) ? 1 : 0;
		$this->creator = $creator_flag;
		
		$db = Database::connect();
		$db->execute('update players set is_creator = ? where id = ?',
			array($creator_flag, $this->id));
	}
	
	/**
	 * Initializes the internal "hand" property from the database.
	 *
	 * @return none
	 */
	protected function _loadHand() {
		$db = Database::connect();
		
		$rs = $db->execute('select h.card_order, h.card_id, c.name
			from hands h, redcards c where h.player_id = ? and h.card_id = c.id',
			array($this->id));
		if (!$rs) return false;
		
		while ($row = $rs->getRowAssoc())
			$this->hand[$row['card_order']] = new RedCard($row['card_id'], $row['name']);
	}
	
	/**
	 * Tests if there is an inactive player in a room somewhere with the given IP address
	 *
	 * This is used to auto-connect anyone who was dropped previously
	 * @param string $ip_address The IP address of the player
	 * @return int The player ID
	 */
	static function getInactiveIDByIP($ip_address) {
		$db = Database::connect();
		
		$rs = $db->execute('select p.id from players p, rooms r, roomlastseen rls where p.is_away = 1 and p.ip_address = ? and p.room_id = r.id and r.id = rls.room_id and r.round_num < r.max_rounds and rls.last_seen > NOW() - interval ? second and p.deleted = 0',
			array($ip_address, HP_MAX_ROOM_AGE));	
		if (!$rs) return false;
		
		$row = $rs->getRowNumeric();
		if (!isset($row[0])) return false;
		
		return $row[0];
	}
	
	static function isRecentRoomCreator($ip_address, $interval) {
		$db = Database::connect();
		$rs = $db->execute('select * from players p, rooms r where p.room_id = r.id and r.created_on > NOW() - interval ? second and p.is_creator = 1 and p.ip_address = ?',
			array($interval, $ip_address));
			
		$row = $rs->getRowNumeric();
		if (!$row) return false;
		return true;
	}

	function touch() {
		$db = Database::connect();
		
		if ($this->is_away)
			$rs = $db->execute('update players set is_away = 0 where id = ? and is_away = 1',
				array($this->id));
		$rs = $db->execute('replace into playerlastseen(player_id, last_seen) values(?, NOW())',
			array($this->id));
			
		$logger = Logger::getLogger($this->room_id);
			
		if ($this->is_away)
			$logger->addLog(LOG_PLAYER_UNIDLE, $this->id);
	}
	
	function setSkipped() {
		$db = Database::connect();
		
		$rs = $db->execute('update players set skipped = 1 where id = ?', array($this->id));
		
		$room = new Room($this->room_id);
		$room->load();
		
		$data = array('p' => $this->id);
		
		if ($room->getPhase() == 1 && $room->checkAllPlayed())
			$data['p2'] = 1;
		elseif ($room->checkSkipJudge($this->id))
			$data['j'] = $room->getJudgeNumber();
		
		$logger = $room->getLogger();
		$logger->addLog(LOG_PLAYER_SKIP, $data);
	}
	
	function voteToSkip($voting_player_id) {
		$db = Database::connect();
		$db->execute('insert into skipvote(voting_player_id, skipped_player_id) values(?,?)', array($voting_player_id, $this->id));
	}
	
	function getVotesToSkipMe() {
		$db = Database::connect();
		$rs = $db->execute('select count(*) from skipvote where skipped_player_id = ?', array($this->id));
		
		$row = $rs->getRowNumeric();
		return $row[0];
	}
	
	function setUnskipped() {
		$db = Database::connect();
		$db->execute('update players set skipped = 0 where id = ?', array($this->id));
		
		$db->execute('delete from skipvote where skipped_player_id = ?', array($this->id));
		
		$room = new Room($this->room_id);
		$logger = $room->getLogger();
		$logger->addLog(LOG_PLAYER_UNSKIP, $this->id);
	}
	
	function ignore($ignoree) {
		$db = Database::connect();

		$ignoree = intval($ignoree);
		$rs = $db->Execute('insert into ignores(player_id, ignored_player_id) values(?, ?)',
			array($this->id, $ignoree));
	}

	function unignore($ignoree) {
		$db = Database::connect();

		$ignoree = intval($ignoree);
		$rs = $db->Execute('delete from ignores where player_id = ? and ignored_player_id = ?',
			array($this->id, $ignoree));
	}
	
	function getIgnores() {
		$db = Database::connect();
		$rs = $db->Execute('select ignored_player_id from ignores where player_id = ?',
			array($this->id));
			
		$ignores = array();
		while ($row = $rs->getRowNumeric()) {
			$ignores[] = $row[0];
		}
		
		return $ignores;
	}
}

?>
