<?php

/**
 * Defines the RedCard class, which represents a red card.
 */

class RedCard {
	protected $id;
	protected $name;
	
	function __construct($id, $name) {
		$this->id = intval($id);
		$this->name = $name;
	}
	
	function getID() { return $this->id; }
	function getName() { return $this->name; }
}

?>