<?php

require_once (LIB_DIR . '/adodb5/adodb.inc.php');

$db = NewADOConnection('mysql');
$db->pconnect('localhost','a2a','d#$d&tr##','a2a');
$db->debug = 1;

$red_cards = file('red-cards.txt');

foreach ($red_cards as $card) {
	list($name, $description) = explode(' - ', $card, 2);
	print "$name * $description";
	$name = str_replace("'", "''", $name);
	$description = str_replace("'", "''", $description);
	$result = $db->Execute("insert into RedCards(name, deck_id, description) values('$name', 1, '$description');");
}

$db->commit();

?>
