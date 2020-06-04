<?php

/**
 * AJAXErrorMapper.php
 * This file defines the AJAXErrorMapper class and initializes all the possible
 * AJAX errors that may occur.
 * Error flags: an indication to the JavaScript library whether it should resend the request or not
 * Error states: if the initial request failed partways through, the error state records where to state it up from again.
 **/

// start off by defining the valid error states
define('AJAX_RESEND', -1); // start over from the top
define('AJAX_PARTIAL', -2); // request was partially completed, error state will tell more
define('AJAX_FAILURE', -3); // do not resend

class AJAXErrorList {
	static $error_code = -1;
	static $error_flags = array();
	static $error_states = array();
	
	/*function AJAXErrorList() {
		$this->error_code = -1;
		$this->error_flags = array();
		$this->error_states = array();
		$this->initErrors();
	}*/
	
	// initializes an error by defining it as a constant and recording its flag and state
	static private function _registerError($name, $error_flag, $error_state = 0) {
		//if (defined($name)) return;
		define($name, self::$error_code);
		self::$error_flags[self::$error_code] = $error_flag;
		if ($error_flag == AJAX_PARTIAL and $error_state)
			self::$error_states[self::$error_code] = $error_state;
		
		self::$error_code--;
	}
	
	static function getErrorFlag($error_code) {
		return self::$error_flags[$error_code];
	}
	
	static function getErrorState($error_code) {
		return self::$error_states[$error_code];
	}
	
	/**
	 * All errors are set up here!
	 **/
	static function initErrors() {
		/** --- Errors for the CardPlay operation --- **/
		// Card index out of bounds
		self::_registerError('E_CARDPLAY_OOB', AJAX_FAILURE);
		// Hand load fails
		self::_registerError('E_CARDPLAY_NOHAND', AJAX_RESEND);
		// Insert into played cards fails
		self::_registerError('E_CARDPLAY_NOINSERT', AJAX_RESEND);
		// Insert into played cards failed because a row already exists
		self::_registerError('E_CARDPLAY_DUPLICATE', AJAX_FAILURE);
		// Remove card fails
		self::_registerError('E_CARDPLAY_NOREMOVE', AJAX_PARTIAL, 1);
		// Get new card fails
		self::_registerError('E_CARDPLAY_NONEW', AJAX_PARTIAL, 2);

		/** --- Errors for the CardVote operation --- **/
		// Player is not judge - fail, do not resend
		self::_registerError('E_CARDVOTE_NOTJUDGE', AJAX_FAILURE);
		// Update playedcards fails - full resend
		self::_registerError('E_CARDVOTE_PLAYEDFAIL', AJAX_RESEND);
		// Update room data fils - partial resend 1
		self::_registerError('E_CARDVOTE_ROOMFAIL', AJAX_PARTIAL, 1);
		// Update score fails - partial resend 2
		self::_registerError('E_CARDVOTE_SCOREFAIL', AJAX_PARTIAL, 2);
		//	Get new green card fails - partial resend 3	
		self::_registerError('E_CARDVOTE_NEWCARDFAIL', AJAX_PARTIAL, 3);

		/** --- Errors for the GameGetStatus operation --- **/
		// Message retrieval fails - full resend
		self::_registerError('E_GAMEGETSTATUS_MESGFAIL', AJAX_RESEND);
		// Player list fails - full resend
		self::_registerError('E_GAMEGETSTATUS_PLISTFAIL', AJAX_RESEND);

		/** --- Errors for the GetPlayedCards operation --- **/
		// Card load failure - full resend
		self::_registerError('E_GETPLAYEDCARDS_CARDFAIL', AJAX_RESEND);

		/** --- Errors for the HasJudgeVoted operation --- **/
		// Winner load fails - full resend
		self::_registerError('E_HASJUDGEVOTED_WINNERFAIL', AJAX_RESEND);
		// Green card fails - full resend
		self::_registerError('E_HASJUDGEVOTED_GREENFAIL', AJAX_RESEND);

		/** --- Errors for the MessageGet operation --- **/
		// Message load fails - full resend
		self::_registerError('E_MESSAGEGET_NOMESG', AJAX_RESEND);

		/** --- Errors for the MessagePost operation --- **/
		// Message write fails - full resend
		self::_registerError('E_MESSAGEPOST_WRITEFAIL', AJAX_RESEND);

		/** --- Errors for the RoomInfo operation --- **/
		// Player list fails - full resend
		self::_registerError('E_ROOMINFO_PLISTFAIL', AJAX_RESEND);

		/** --- Errors for all requests --- **/
		//	Missing parameter - fail, do not resend
		self::_registerError('E_ALL_MISSINGPARAM', AJAX_FAILURE);
		// Invalidated (by IP or password) - fail, do not resend
		self::_registerError('E_ALL_INVALIDATE', AJAX_FAILURE);
		// Room load fails - full resend
		self::_registerError('E_ALL_ROOMLOAD', AJAX_RESEND);
	}
}

AJAXErrorList::initErrors();

?>