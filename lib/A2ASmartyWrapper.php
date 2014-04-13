<?php

require_once(SMARTY_DIR . 'Smarty.class.php');

class A2ASmartyWrapper extends Smarty {
	function __construct() {
		$this->template_dir = TEMPLATE_DIR;
		$this->compile_dir = COMPILE_DIR;
		$this->assign('hostname', $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']);
	}
}

?>