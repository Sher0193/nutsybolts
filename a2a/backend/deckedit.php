<?php

require_once '../../config.php';
require_once LIB_DIR . 'Database.php';
require_once(LIB_DIR . 'A2ASmartyWrapper.php');

$db = Database::connect();

$did = 0;
if (isset($_POST['did']))
	$did = $_POST['did'];

$deck_result = $db->execute('select * from decks');
$decks = array();
$deck_rows = $deck_result->GetRows();
foreach ($deck_rows as $row) {
	$decks[$row['id']] = $row['name'];
}

$red_cards = $green_cards = array();

if (isset($_POST['did'])) {
	$did = $_POST['did'];
	$green_result = $db->execute('select * from greencards where deck_id = ? order by name', $did);
	$green_rows = $green_result->GetRows();
	
	$green_cards = array();
	foreach ($green_rows as $row) {
		$green_cards[$row['id']] = $row['name'];
	}
	
	$red_result = $db->execute('select * from redcards where deck_id = ? order by name', $did);
	$red_rows = $red_result->GetRows();
	
	$red_cards = array();
	foreach ($red_rows as $row) {
		$red_cards[$row['id']] = $row['name'];
	}	
}

$smarty = new A2ASmartyWrapper();
$smarty->assign('decks', $decks);
$smarty->assign('did', $did);
$smarty->assign('redcards', $red_cards);
$smarty->assign('greencards', $green_cards);
$smarty->assign('hostname', $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']);
$smarty->display('admin-deck.tpl');

?>