<?php

require_once(LIB_DIR . 'Database.php');
require_once(LIB_DIR . 'LockManager.php');
require_once(LIB_DIR . 'NBUtils.php');

class RoomDeck {
	var $deck_id;
	var $room_id;
	
	function __construct($room_id, $deck_id) {
		$this->room_id = $room_id;
		$this->deck_id = $deck_id;
	}
	
	function getAllRedCards() {
		$db = Database::connect();
		
		$sql = 'select name from redcards where active = 1 and deck_id = ? order by name';
		$params = array($this->deck_id);
		
		$rs = $db->Execute($sql, $params);
		$redcards = array();
		while ($row = $rs->getRowNumeric()) {
			$redcards[] = $row[0];
		}
		return $redcards;
	}
	
	function shuffleRedCards($empty = false) {
		$db = Database::connect();
		
		$sql = 'select id from redcards where active = 1 and deck_id = ?';
		$params = array($this->deck_id);
		
		// if we shuffle a second time during the game, don't deal out cards that are already in people's hands
		if ($empty) {
			$sql .= ' and id not in (select h.card_id from hands h, players p where h.player_id = p.id and p.room_id = ?)';
			$params[] = $this->room_id;
		}

		$rs = $db->Execute($sql, $params);
		$redcards = array();
		while ($row = $rs->getRowNumeric()) {
			$redcards[] = $row[0];
		}
			
		$redcards = NBUtils::shuffle($redcards);
		
		$redcard_stmts = array();
		foreach ($redcards as $order => $cardid) {
			$redcard_stmts[] = '(' . $this->room_id . ',' . $cardid . ',' . $order . ')';
		}
		$db->Execute('insert into roomredcards(room_id, card_id, card_order) values ' . join(',', $redcard_stmts));
	}
	
	function shuffleGreenCards() {
		$db = Database::connect();
		$rs = $db->Execute('select id from greencards');
		$greencards = array();
		while ($row = $rs->getRowNumeric())
			$greencards[] = $row[0];

		$greencards = NBUtils::shuffle($greencards);
		
		$greencard_stmts = array();
		foreach ($greencards as $order => $cardid) {
			$greencard_stmts[] = '(' . $this->room_id . ',' . $cardid . ',' . $order . ')';
		}
		$rs = $db->Execute('insert into roomgreencards(room_id, card_id, card_order) values ' . join(',', $greencard_stmts));
	}

	// Retrieves the red card on the "top" of the stack
	// This is used to deal a player a new card after playing one
	// Returns a RedCard object
	function getNextRedCard() {
		$db = Database::connect();
		
		// use a semaphore to prevent race conditions
		$lm = new LockManager($this->room_id);
		
		$lm->lock('redcard', true);

		// get the lowest-numbered card in the room
		$rs = $db->Execute('select c.id, c.name, rc.card_order
			from redcards c, roomredcards rc
			where c.id = rc.card_id and rc.room_id = ?
			and rc.card_order = (select min(card_order)
			from roomredcards where room_id = ?)',
			array($this->room_id, $this->room_id));
		if (!$rs) {
			return false;
		}
		
		$row = $rs->getRowNumeric();
		if (!$row) { // we're out of cards, so shuffle the deck and pick it again
			//error_log("Lack of rows:" . mysql_error());
			
			$this->shuffleRedCards(true);
			$rs = $db->Execute('select c.id, c.name, rc.card_order
			from redcards c, roomredcards rc
			where c.id = rc.card_id and rc.room_id = ?
			and rc.card_order = (select min(card_order)
			from roomredcards where room_id = ?)',
			array($this->room_id, $this->room_id));
			if (!$rs) {
				return false;
			}
		
			$row = $rs->getRowNumeric();
		}
		list($id, $name, $order) = $row;

		// now remove the card from the table
		$db->Execute('delete from roomredcards where room_id = ? and card_order = ?',
			array($this->room_id, $order));

		$card = new RedCard($id, $name);

		$lm->unlock('redcard');
		return $card;
	}

	function getGreenCard($round_num) {
		// use a semaphore to make sure the judge vote has gone through all the way
		$lm = new LockManager($this->room_id);
		$lm->lockShared('greencard');

		$db = Database::connect();

		// get the lowest-numbered card in the room
		$rs = $db->Execute('select c.id, c.name from greencards c, roomgreencards rc where c.id = rc.card_id and rc.room_id = ? and rc.card_order = ?',
			array($this->room_id, $round_num));
		$row = $rs->getRowNumeric();
		
		list($id, $name) = $row;

		$lm->unlock('greencard');
		return array($id, $name);
	}
	
	function create($name, $card_names) {
		if (!is_array($card_names)) return;
		
		$card_names = array_unique($card_names);
			
		$db = Database::connect();
		$db->execute('insert into decks(name) values (?)', array($name));
		
		$deck_id = $db->insert_id();
		
		foreach ($card_names as $card) {
			$card = $this->_card_cleanup($card);
			
			if ($this->_card_validate($card))
				$db->execute('insert into redcards(name, deck_id, active) values(?,?,1)',
					array($this->_card_cleanup($card), $deck_id));
		}
		
		return $deck_id;
	}
	
	protected function _card_cleanup($card) {
		$card = preg_replace('/^\s+/', '', $card);
		$card = preg_replace('/\s+$/', '', $card);
		return $card;
	}
	
	protected function _card_validate($card) {
		if ($card == '') return false;
		return true;
	}
}

?>