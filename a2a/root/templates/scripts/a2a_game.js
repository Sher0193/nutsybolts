var FADEIN_DELTA = 1;
var FADEOUT_DELTA = 6;
var MOVE_DELTA = 8;
var highlight_color = new Array(255, 128, 0);
var FADEOUT_STEPS = 20;
var TOTAL_SHUFFLES = 15;
var SHUFFLE_TIME = 75;
var TITLE_INTERVAL = 1200;

var judge_order = new Array();
var current_judge;
var away = 0;
var played_pids = new Array();
var pid_cards = new Array();

var playerlist = new Array();
var scores = new Array();
var is_skipped = new Array();
var is_idle;

var shuffle_x;
var shuffle_y;

var global_card;
var global_order;
var global_judge_winner;
var global_green_card;
var global_judge_feedback_displayed = false;
var global_round_number;
var global_max_rounds;

var global_gameover_judge;
var global_gameover_winner;
var global_gameover_greencard;

var fadeout_timeout;
var phase = 'play';
var creator_flag;
var ignores;

var your_pid;
var your_hand = new Array();



function playCard(order) {
	if (order < 0 || order > 6) return;
	
	if (global_order || document.getElementById('hand_cards').className == 'disabled')
		return; // false;

	global_card = document.getElementById('hand' + order);
	global_order = order;
	
	var card_name = your_hand[order];
	
	deactivateHand();
	
	$('#skip_block').slideUp('slow');
	
	$('#hand_message').html('You played <b>' + card_name + '</b> this round, for the adjective <b>' + global_green_card + '</b>.');
	$('#hand' + order).css('position', 'relative').animate({
		opacity: "hide",
		top: -150
	}, "slow", "linear", sendPlayedCard);
}

function sendPlayedCard() {
	AJAXRequest('play', { 'order' : global_order }, updateCard);
}

function updateCard(response) {
	// change the card caption
	your_hand[global_order] = response;
	$('#hand' + global_order + ' .card_caption').text(response);
	
	// change the wiki link
	$('#container' + global_order + ' a[href*="wiki"]').attr('href', 'http://en.wikipedia.org/wiki/' + response); 
	$('#hand' + global_order).css('position', '').css('top', '').css('display', 'none').fadeIn('slow');
}

function activateHand() {
	global_order = null;	
	$('#hand_cards').removeClass('disabled').addClass('enabled');	
}

function deactivateHand() {
	$('#hand_cards').removeClass('enabled').addClass('disabled');
}

function addCardToTable(response) {	
	if (pid_cards[response.p] != null) return;
	pid_cards[response.p] = response.n;
	
	var order = played_pids.length;
	if (played_pids.length == 0) {
		// clear out the cards from last round if a new card is played
		$('#table_cards').empty();
		window.clearTimeout(fadeout_timeout);
	}	
	
	displayTableCard(order, response.n);
	
	played_pids.push(response.p);
	var icon = document.getElementById('playericon_img' + response.p);
	
	// may be the case that a player who was removed added the card to the table
	if (icon != null) {
		icon.width = 15;
		icon.src = '/images/card_icon.png';
		icon.title = 'Played Noun Card';
	}
	
	if (response.j == 1) {
		shuffleCards();
	}
}

function shuffleCards() {
	deactivateHand();
	var table = document.getElementById('table_cards');
	
	while (table.firstChild.className != "card_container") {
		table.removeChild(table.firstChild);
		continue;
	}
	
	playSound('shuffle');
	animateShuffles(0);
}

function animateShuffles(shuffle) {
	var table = document.getElementById('table_cards');
	
	for (var i = 0; i < table.childNodes.length; i++) {
		var card = table.childNodes[i];
		
		card.style.position = 'relative';
		card.style.top = (random(21) - 10) + 'px';
		card.style.left = (random(21) - 10 - (76 * i)) + 'px';
	}
	
	if (shuffle < TOTAL_SHUFFLES) {
		shuffle++;
		setTimeout('animateShuffles(' + shuffle + ');', SHUFFLE_TIME);
	}
	else {
		var nodes = table.childNodes;
		var length = nodes.length;		
	
		for (; length > 0; length--) {
			var random_card = nodes[random(length)];
			random_card.style.position = 'static';
			random_card.style.top = '0px';
			random_card.style.left = '0px';
			table.appendChild(random_card);
		}
	
		switchToJudge();
	}	
}

function tableCardsFadeOut() {
	$('#table_cards div').fadeOut('slow', displayTableCards);
}

function displayTableCards() {
	$('#table_cards').empty();
	
	for (var i = 0; i < played_pids.length; i++) {
		var pid = played_pids[i];
		displayTableCard(i, pid_cards[pid]);
	}
}

function displayTableCard(order, name) {
	$('<div class="card_container" id="tablecontainer'+order+'" style="display:none;">' +
		'<a class="hand_card" id="tablecard'+order+'" onMouseOver="return true;" onClick="judgeVote('+order+')"><div class="card_caption"></div></a>' +
		'<div class="lookitup_container"><a href="http://en.wikipedia.org/wiki/'+name+'" target="_blank"></a></div>' +
		'<div class="name_container"></div>' +
	'</div>')
		.appendTo('#table_cards')
		.fadeIn('slow'); //, function() { testShuffleCards(order); } );
		Console.log("Hmmmm");
}

function switchToJudge() {
	phase = 'judge';
	// just in case...
	deactivateHand();
	addCardLabels();
	updateJudge(current_judge);
}

function activateTable() {
	for (var i = 0; i < played_pids.length; i++) {
		$('#tablecontainer' + i + ' a:first .card_caption').text(pid_cards[played_pids[i]]);
		$('#tablecontainer' + i + ' a:last').text('look it up');
	}
	
	// make sure you can't vote for your own card
	for (i = 0; i < played_pids.length; i++) {
		if (played_pids[i] == your_pid)
			$('#tablecontainer' + i).addClass('disabled');
	}
	
	$('#table_cards').removeClass('disabled').addClass('enabled');
}

function deactivateTable() {
	$('#table_cards').removeClass('enabled').addClass('disabled');
}

function addCardLabels() {
	// put all the labels on the cards
	for (var i = 0; i < played_pids.length; i++) {
		$('#tablecontainer' + i + ' a:first .card_caption').text(pid_cards[played_pids[i]]);
		$('#tablecontainer' + i + ' a:last').text('look it up');
	}
}

function judgeVote(order) {
	if ($('#table_cards').hasClass('disabled') || $('#tablecontainer' + order).hasClass('disabled')) return;
	deactivateTable();
	
	global_judge_winner = played_pids[order];
	var q = { 'win' : played_pids[order] };
	AJAXRequest('judge_vote', q, handleJudgeVote);
	
	global_judge_feedback_displayed = true;
	displayJudgeFeedback(played_pids[order]);
}

function displayJudgeFeedback(winner) {
	global_judge_feedback_dispayed = true;
	showCardNames(winner);
	addToHistory(global_round_number, winner, global_green_card, pid_cards[winner]);
	highlight(winner);
	updateScore(winner);
	nextRound();
}

function handleJudgeVote(response) {
	//switchToPlay(global_judge_winner, response.card, response.j);
}

function getJudgeVote(response) {
	switchToPlay(response.p, response.c, response.j);
}

function switchToPlay(winner, new_green_card, new_judge) {	
	phase = 'play';
	deactivateTable();
	
	if (!global_judge_feedback_displayed) {
		displayJudgeFeedback(winner);
	}
	
	global_judge_feedback_displayed = false;
	
	if (global_round_number <= global_max_rounds) {
		updateGreenCard(new_green_card);

		if (judge_order[new_judge] != your_pid) {
			activateHand();
			global_card = null;
			global_order = null;
		}

		played_pids = new Array();
		pid_cards = new Array();
		
		updateJudge(new_judge);
		
		fadeout_timeout = setTimeout('tableCardsFadeOut()', 5000);
	}
}

function updateJudge(new_judge) {
	current_judge = new_judge;
	
	// update the icons
	for (var i = 0; i < player_ids.length; i++) {
		var player_id = player_ids[i];
		var icon_name, icon_title;	
		var icon_width = 20;
		if (judge_order[current_judge] == player_id) {
			icon_name = 'gavel.png';
			icon_title = 'Judge';
		}
		else {
			if (pid_cards[player_id] != null) {
				icon_name = 'card_icon.png';
				icon_title = 'Played Noun Card';
				icon_width = 15;
			}
			else {
				icon_name = 'blank20.gif';
				icon_title = '';
			}
		}		
		$('#playericon_img' + player_id).attr(
			{width: icon_width, src:'/images/'+icon_name, title:icon_title}
		);
	}
	
	// update the text
	if (phase == 'play') {
		if (judge_order[current_judge] == your_pid) {
			$('#judge_name').text(names[judge_order[current_judge]] + '   (You!)');
			//$('#judge_phrase').text('are the judge this round.');
			$('#table_message').text('Once everyone has played, pick the card you like the best.');
			$('#hand_message').text('You\'re the judge; you can\'t play a card this round.');
			//$('#judge_status').text('Once everyone has played, pick the card you like the best!');
			deactivateHand();
		}
		else {
			$('#judge_name').text(names[judge_order[current_judge]]);
			$('#table_message').html('<b>' + names[judge_order[current_judge]] + '</b> is the judge.');
			$('#hand_message').html('Play the card that ' + names[judge_order[current_judge]] + ' will think is <b>' + global_green_card + '</b>.');
			//$('#judge_phrase').text('is the judge this round.');
			//$('#judge_status').text('Play the card you think ' + names[judge_order[current_judge]] + ' will like the best.');
			activateHand();
		}
	}
	else if (phase == 'judge') {
		if (judge_order[current_judge] == your_pid) {
			//$('#judge_name').text('You');
			//$('#judge_phrase').text('are the judge this round.');
			$('#table_message').html('Pick the card you think is the most <b>' + global_green_card + '</b>.');
			//$('#judge_status').text('Pick the card you like the best!');
			activateTable();
			$.titleAlert("Time to vote!", {'interval':TITLE_INTERVAL});
		}
		else {
			$('#judge_name').text(names[judge_order[current_judge]]);
			$('#table_message').html('<b>' + names[judge_order[current_judge]] + '</b> is the judge.');
			//$('#judge_name').text(names[judge_order[current_judge]]);
			//$('#judge_phrase').text('is the judge this round.');
			//$('#judge_status').text('Wait for them to pick this round\'s winner.');
			deactivateTable();
		}
	}
}

function showCardNames(winner) {
	for (var i = 0; i < played_pids.length; i++) {
		var current_pid = played_pids[i];
		$('#tablecontainer' + i + ' .name_container').text(names[current_pid]);
		
		if (current_pid == winner)
			$('#tablecontainer' + i).addClass('winner');
	}
}

function updateScore(winner) {
	scores[winner]++;
	$('#playerscore' + winner).text(scores[winner]);	
	$('#winner').empty().text("Last round's winner: " + names[winner] + ", with " + pid_cards[winner]);
}

function updateGreenCard(card) {
	$('#greencard_link').attr('href', 'http://en.wiktionary.org/wiki/' + card).text(card);
	global_green_card = card;
}

function nextRound() {
	global_round_number++;
	updateRoundText();
	
	playSound('gavel');
	
	if (global_round_number > global_max_rounds) {
		gameOver();
	}
	else {
		$.titleAlert("A new round has started!", {'interval':TITLE_INTERVAL});
	}

}

function updateRoundText() {
	var round_message;
	if (global_round_number > global_max_rounds)
		round_message = 'Game over!';
	else if (global_round_number == global_max_rounds)
		round_message = 'Final round!';
	else
		round_message = global_round_number + ' of ' + global_max_rounds;
	
	$('#round_text').text(round_message);
}

function gameOver() {
	var tie = 0;
	var game_winner = 0;
	var high_score = 0;
	for (var i = 0; i < player_ids.length; i++) {
		if (scores[player_ids[i]] > high_score) {
			tie = 0;
			high_score = scores[player_ids[i]];
			game_winner = player_ids[i];
		}
		else if (scores[player_ids[i]] == high_score) {
			tie = 1;
		}
	}
	
	$('#round_text').empty().text('Game over!');
	
	if (tie) {
		$('#gameover-message').text('We have a tie game!');
	}
	else {
		$('#gameover-message').text(names[game_winner] + ' is the winner!');
	}
	
	$('#gameover').show('slow');
}

function activateCreator() {
	showRemoveLinks();
}

function deactivateCreator() {
	hideRemoveLinks();
}

function skipPlayer(skipping_pid) {
	AJAXRequest('skip', {'sid': skipping_pid}, handleSkipPlayer);
	skipVoteShowVoted(skipping_pid);
}

function handleSkipPlayer(response) { }

function highlight(rowid) {
	var row = document.getElementById('playerrow' + rowid);
	
	if (row == null) return;
	
	var current_color;

	if (row.currentStyle) {
		var current_color_string = row.currentStyle['backgroundColor'];
		var hex_string_rx = /([0-9a-f][0-9a-f])([0-9a-f][0-9a-f])([0-9a-f][0-9a-f])/;
		current_color = current_color_string.match(hex_string_rx);
		current_color.shift();
		for (var i = 0; i <= 2; i++) {
			current_color[i] = parseInt(current_color[i],16);
		}
	}
	else {
		var current_color_rgb = window.getComputedStyle(row, '').getPropertyValue('background-color');
		current_color = current_color_rgb.match(/([0-9]+)/g);
	}
	row.style.backgroundColor = arrayToRGB(highlight_color);

	setTimeout('delight(' + rowid + ',' + highlight_color.join(',') + ',' + current_color.join(',') + ');', 2000);
}

function arrayToRGB(color_array) {
	if (color_array.length != 3) return false;
	var rgb_string = '#';

	for (var i = 0; i <= 2; i++) {
		var hex_piece = parseInt(color_array[i]).toString(16);
		if (color_array[i] < 16) hex_piece = "0" + hex_piece;
		rgb_string = rgb_string + hex_piece;
	}
	return rgb_string;
}

function delight(rowid, current_r, current_g, current_b, r, g, b) {
	var row = document.getElementById('playerrow' + rowid);

	var delta_r = (r - highlight_color[0]) / FADEOUT_STEPS;
	var delta_g = (g - highlight_color[1]) / FADEOUT_STEPS;
	var delta_b = (b - highlight_color[2]) / FADEOUT_STEPS;

	var new_r = current_r + delta_r;
	var new_g = current_g + delta_g;
	var new_b = current_b + delta_b;

	row.style.backgroundColor = arrayToRGB(new Array(new_r,new_g,new_b));

	if (parseInt(new_r + .5) != r ||
		parseInt(new_g + .5) != g ||
		parseInt(new_b + .5) != b)
	setTimeout('delight(' + rowid + ',' + new_r + ',' + new_g + ',' + new_b +',' + r + ',' + g + ',' + b + ');', 30);
}

function random(size) {
	var seed = Math.random();
	return Math.floor(seed * size);
}

function addToHistory(round, winner_id, greencard, redcard) {
	var cycle = 2 - (round % 2);
	$(
		'<tr class="history' + cycle + '">' +
			'<td>' + round + '</td>' +
			'<td>' + names[winner_id] + '</td>' +
			'<td><span class="history_green">' + greencard + '</span> <span class="history_red">' + redcard + '</span></td>' +
		'</tr>'
	).toggle().insertAfter('.historyheader').fadeIn('slow');
}

function playerSkip(response) {
	var pid = response.p;
	$('#playerrow' + pid).addClass('skipped');
	
	skipVoteHide(pid);
	
	is_skipped[pid] = 1;
	
	if (pid == your_pid)
		$('#skip_block').slideDown('slow');
	
	if (response.j != null)
		updateJudge(response.j);
	if (response.p2 != null)
		shuffleCards();
}
	
function playerUnskip(pid) {
	$('#playerrow' + pid).removeClass('skipped');
	skipVoteShowLink(pid);
	
	is_skipped[pid] = 0;
}

function deletedPlayerAdded(player) {
	names[player.id] = player.name;
}

function playerAdded(player) {
	player_ids[player_ids.length] = player.id;
	names[player.id] = player.name;
	colors[player.id] = '#' + player.color;
	
	if (!player.score) player.score = 0;
	scores[player.id] = player.score;

	ignores[player.id] = player.ignores;
	
	if (!player.start) {
		// if this was a player added after the start of the game, update the judges
		for (var i = player_ids.length; i > player.j; i--) {
			judge_order[i] = judge_order[i - 1];
		}
		
		if (player.n)
			current_judge++;
	}
	
	judge_order[player.j] = player.id;
 	
	// update the player list HTML
	var ignore_link;
	var skip_link;
	if (player.id == your_pid) {
		ignore_link = '';
		skip_link = '';
	}
	else {
		skip_link = '<span id="playerskip' + player.id + '">[&nbsp;<a href="javascript:skipPlayer(' + player.id + ');">Vote to skip</a>&nbsp;]</span>';
		if (player.ignores == 1) {
			ignore_link = '[&nbsp;<a href="javascript:unignorePlayer(' + player.id + ');" id="ignorelink' + player.id + '">Unignore</a>&nbsp;]';
		}
		else {
			ignore_link = '[&nbsp;<a href="javascript:ignorePlayer(' + player.id + ');" id="ignorelink' + player.id + '">Ignore</a>&nbsp;]';
		}
	}
	
	var remove_link = '';
	
	if (creator_flag)
		remove_link = '<td>[&nbsp;<a href="javascript:removePlayer(' + player.id + ');">Remove</a>&nbsp;]</td>';
			
	var html =
		'<tr id="playerrow' + player.id + '">' +
		'<td id="playericon' + player.id + '" width="25"><img id="playericon_img' + player.id + '" /></td>' + 
		'<td class="playername" id="playername' + player.id + '">' + player.name + '</td>' + 
		'<td class="playerscore" id="playerscore' + player.id + '" width="0%">' + player.score + '</td>' +
		'<td>' + ignore_link + '</td>' + 
		'<td>' + skip_link + '</td>' + 
		remove_link +
		'</tr>'
	;
	
	var previous_judge_id = -1;
	for (var i = 0; i < player.j; i++) {
		if (judge_order[i] == null) continue;
		previous_judge_id = judge_order[i];
	}
	
	if (previous_judge_id == -1)
		$('#playerlistheader').after(html);
	else
		$('#playerrow' + previous_judge_id).after(html);
	
	if (!player.start)
		refreshTableStripes();
}

function skipVoteShowVoted(pid) {
	if (pid != your_pid) {
		$('#playerskip' + pid).html('Voted to skip');
		$('#playerskip' + pid).css('visibility', 'visible');
	}
}
function skipVoteShowLink(pid) {
	if (pid != your_pid) {
		$('#playerskip' + pid).html('<span id="playerskip' + pid + '">[&nbsp;<a href="javascript:skipPlayer(' + pid + ');">Vote to skip</a>&nbsp;]</span>');
		$('#playerskip' + pid).css('visibility', 'visible');
	}
}
function skipVoteHide(pid) {
	$('#playerskip' + pid).css('visibility', 'hidden');
}

function showInviteDialog() {
	$('#invitebox').css('display', 'block');
}

function hideInviteDialog() {
	$('#invitebox').css('display', 'none');
}

function oneMoreRound() {
	$('#gameover').hide('slow');
	AJAXRequest('more', {}, handleOneMoreRound);
}

function handleOneMoreRound() { }

function addOneMoreRound(response) {
	$('#gameover').hide('slow');
	global_max_rounds = response.m;
	$('#max_rounds').text(response.m);
	updateRoundText();
	
	global_judge_feedback_displayed = true;
	switchToPlay(null, response.g, response.j);
}

function playerRemoved(response) {
	var removed_pid = response.p;
	if (removed_pid == your_pid)
		window.location = '/index.php?status=-7';
	
	names[removed_pid] = null;
	colors[removed_pid] = null;
		
	for (var i=0; i < player_ids.length; i++) {
		if (player_ids[i] == removed_pid)
			player_ids.splice(i, 1);
	}
	
	var removed_judge_flag = false;
	for (var i = 1; i <= player_ids.length; i++) {
		if (judge_order[i] == removed_pid)
			removed_judge_flag = true;
		
		if (removed_judge_flag) {
			judge_order[i] = judge_order[i + 1];
		}
	}
	
	if (response.j != null) {
		updateJudge(response.j);
	}
	if (response.p2 != null) {
		shuffleCards();
	}
	
	$('#playerrow' + removed_pid).remove();
	refreshTableStripes();
}
