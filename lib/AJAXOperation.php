<?php

abstract class AJAXOperation {
	protected $request;
	protected $player;
	function __construct($request, Player $player) {
		$this->request = $request;
		$this->player = $player;
	}
	
	abstract function process();
	
	// returns an error flag to the AJAX library,
	// as well as a possible error state indicating where to restart from
	// pass in the error code constant as defined in AJAXErrorList.
	function error($code) {
		$result = array('erf' => AJAXErrorList::getErrorFlag($code));
		if ($result['erf'] == AJAX_PARTIAL)
			$result['ers'] = AAJAXErrorList::getErrorState($code);
		
		
		// do a reverse lookup to find the name of the error code
		$error_name = 'undefined';
		$constants = get_defined_constants();
		foreach ($constants as $name => $value) {
			if (preg_match('/^E_/', $name) and $value == $code) {
				$error_name = $name;
				break;
			}
		}
		$class_name = get_class($this);
		
		error_log("$class_name - error code $code [$error_name]");
		
		return $result;
	}
}

?>