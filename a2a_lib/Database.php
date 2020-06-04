<?php

//ini_set("display_errors", 1);
//require_once(LIB_DIR . 'adodb5/adodb.inc.php');

class Database {
	static protected $database;
	
	var $host;
	var $username;
	var $password;
	var $dbname;
	
	var $connection;

	static function connect() {
		if (self::$database)
			return self::$database;

		self::$database = new Database(DB_HOST, DB_USER, DB_PW, DB_SCHEMA);
		if (self::$database->dbconnect()) {
				
			//self::$database->debug = 1;
			return self::$database;
		}
		else {
			throw new DatabaseException('Cannot connect to database', -1);
		}
	}
	
	function Database($host, $username, $pw, $database) {
		$this->host = $host;
		$this->username = $username;
		$this->password = $pw;
		$this->dbname = $database;
	}
	
	function dbconnect() {
		$this->connection = mysql_pconnect($this->host, $this->username, $this->password);
		if (!$this->connection) {
			$this->logError();
			return false;
		}
		mysql_select_db($this->dbname, $this->connection);
		return true;
	}
	
	function execute($sql, $params = array()) {
		$pieces = explode('?', $sql);
		$sql = $pieces[0];
		for ($i = 1; $i < count($pieces); $i++) {
			$type = gettype($params[$i - 1]);
			if ($type == 'string')
				$params[$i - 1] = "'" . mysql_real_escape_string($params[$i - 1]) . "'";
			$sql = $sql . $params[$i - 1] . $pieces[$i];
		}
		
		//$sql = str_replace('?', "'%s'", $sql);
		//$sql = vsprintf($sql, $params);
		
		$rs = mysql_query($sql, $this->connection);
		if (mysql_errno($this->connection)) {
			$this->logError($sql);
		}
		return new ResultSet($rs);
	}
	
	function logError($sql = '') {
		if (!$sql)
			error_log("MySQL error: " . mysql_error($this->connection));
			
		else
			error_log("MySQL error on $sql: " . mysql_error($this->connection));
	}
	
	function errormsg() {
		return mysql_error($this->connection);
	}
	
	function insert_id() {
		return mysql_insert_id($this->connection);
	}
	
	function starttrans() {
		// hack - this doesn't do anything
	}
	
	function completetrans() {
		// hack - this doesn't do anything
	}
	
	function getAffectedRows() {
		return mysql_affected_rows($this->connection);
	}
}

class ResultSet {
	var $result;
	
	function ResultSet($result) {
		$this->result = $result;
	}
	
	function getRowsAssoc() {
		$rows = array();
		while ($row = mysql_fetch_assoc($this->result))
			$rows[] = $row;
			
		mysql_free_result($this->result);
		return $rows;
	}
	function getRowsNumeric() {
		$rows = array();
		while ($row = mysql_fetch_array($this->result))
			$rows[] = $row;
			
		mysql_free_result($this->result);
		return $rows;
	}
	
	function getRowAssoc() {
		$row = mysql_fetch_assoc($this->result);
		if (!$row)
			mysql_free_result($this->result);
		return $row;
	}
	function getRowNumeric() {
		$row = mysql_fetch_array($this->result);
		if (!$row)
			mysql_free_result($this->result);
		return $row;
	}
}

class DatabaseException extends Exception {
	
}

?>