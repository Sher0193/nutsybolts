var game_visibility;
var refresh_timer;
var deck_save_disabled;
var unique_timeout;

function createValidate() {
	var gameform = document.gameform;
	var error_flag = true;
	
	if (!validatePlayerName())
		error_flag = false;
	
	if (!gameform.name.value) {
		$(gameform.name).addClass('invalid');
		$('#create_name_message').show();
		error_flag = false;
	}
	
	if (!gameform.room_name.value) {
		$(gameform.room_name).addClass('invalid');
		$('#create_room_message').show();
		error_flag = false;
	}
	
	var rounds = parseInt(gameform.rounds.value);
	if (isNaN(rounds) || rounds <= 0) {
		$(gameform.rounds).addClass('invalid');
		error_flag = false;
	}
	
	var max_players = parseInt(gameform.max_players.value);
	if (isNaN(max_players) || max_players < 3 || max_players > 30) {
		$(gameform.max_players).addClass('invalid');
		$('#player_number_message').show();
		error_flag = false;
	}
	
	if (gameform.private.checked && !gameform.create_password.value) {
		$(gameform.create_password).addClass('invalid');
		$('#create_password_message').show();		
		error_flag = false;
	}

	if (error_flag) {
		gameform.password.value = gameform.create_password.value;
		gameform.action="create-game.php";
		gameform.submit();
		$.blockUI(
			{
				message: '<div class="modal"><img src="/images/ajax-loader.gif" />Shuffling cards...</div>'
			}
		);
	}
	return error_flag;
}

function updatePassword() {
	var private_check = document.gameform.private;
	var private_status = private_check.checked;
	
	if (private_status == 1)
		$('#password_span').show();
	else
		$('#password_span').hide();
}

function refreshRoomList() {
	clearTimeout(refresh_timer);
	AJAXRequest('get_room_list', {'v': game_visibility, 's': unstartedGamesOnly()}, displayRoomList);
}

function displayRoomList(response) {
	if (response.error == 'database') {
		window.location = '/fail.php';
	}
	
	var state = getRoomUIState();
	
	$('#room_list').empty();
	
	for (var i = 0; i < response.length; i++) {
		addRoom(response[i]);
	}
	
	setRoomUIState(state);
	
	refresh_timer = setTimeout('refreshRoomList();', 5000);
}

function addRoom(room) {
	var html = '<div class="room"><div class="joinform">';

	if (room.pl.length == room.mp) {
		html += 'Sorry, this room is full.';
	}
	else {
		html += '<div class="private">';
		
		if (room.p) {
			html += '<input type="password" id="password' + room.id + '" style="width:100%;" class="text empty" onfocus="$(this).removeClass(\'empty\');" />';
		}
		
		html += '</div>';
	
		html += '<div class="button" onclick="joinroom(' + room.id + ');">Join!</div>';
	}
	
	html += '</div><div class="title">' + room.name + '</div>';

	html += '<div class="round">Round ' + room.r + ' of ' + room.mr + '</div>';
	
	if (room.d != 1)
		html += '<div class="deck"><a href="javascript:loadGameDeck(' + room.d + ');">Custom deck</a></div>';
	
	html += '<div class="playerlist"><div class="header">Players: </div>' + room.pl.join(', ') + '</div>';
	
	html += '</div>';
	
	$('#room_list').append(html);
}

function joinroom(room_id) {
	if (validatePlayerName()) {
		document.gameform.action = "join-game.php";
		document.gameform.roomid.value = room_id;
		document.gameform.password.value = $('#password' + room_id).val();
		document.gameform.submit();
	}
}

function selectGameVisibility(value) {
	if (value != 'all' && value != 'public' && value != 'private')
		return 0;
	
	game_visibility = value;
	
	if (value == 'all')
		$('#visibility-all').removeClass('unselected').addClass('selected');
	else
		$('#visibility-all').removeClass('selected').addClass('unselected');
	
	if (value == 'public')
		$('#visibility-public').removeClass('unselected').addClass('selected');
	else
		$('#visibility-public').removeClass('selected').addClass('unselected');
	
	if (value == 'private')
		$('#visibility-private').removeClass('unselected').addClass('selected');
	else
		$('#visibility-private').removeClass('selected').addClass('unselected');
	
	refreshRoomList();
}

function unstartedGamesOnly() {
	if ($('#unstarted').attr('checked') == true) return 1;
	return 0;
}

function validatePlayerName() {
	if (document.gameform.name.value == '') {
		$('#namefield').addClass('invalid');
		$('#namefield_invalid').css('display', 'inline-block');
		return false;
	}
	
	return true;
}

function getRoomUIState() {
	var state = {};
	state.scroll = $('#room_list').scrollTop();
	
	state.passwords = new Array();
	// get any passwords that have been entered
	$('#room_list input').each(
		function() {
			var roomid = this.id.match(/\d+/)[0];
			state.passwords[roomid] = this.value;
		}
	);
	
	// figure out which password has the focus
	if (document.activeElement && document.activeElement.id.match(/^password\d+$/)) {
		state.focus = document.activeElement.id.match(/\d+/)[0];
	}
	
	return state;
}

function setRoomUIState(state) {
	$('#room_list').scrollTop(state.scroll);
	
	$('#room_list input').each(
		function() {
			var roomid = this.id.match(/\d+/)[0];
			
			if (state.passwords[roomid]) {
				this.value = state.passwords[roomid];
				$(this).removeClass("empty");
			}
		}
	);
	
	if (state.focus)
		$('#room_list input[id$="' + state.focus + '"]').focus();
}

function loadStandardDeck() {
	$('#textarea-nouns').val('Loading...');
	AJAXRequest('get_nouns', {'d':1}, showStandardDeck);
}

function showStandardDeck(response) {
	$('#textarea-nouns').val(response);
	updateDeckMessage();
}

function loadGameDeck(deck_id) {
	AJAXRequest('get_nouns', {'d':deck_id},showGameDeck);
	$('#gamedeck_nouns').text('Loading...');
	$('#gamedeckbox').show();
}

function showGameDeck(response) {
	$('#gamedeck-nouns').text(response);
}

function closeGameDeck() {
	$('#gamedeckbox').hide();
}

function showDeckDialog() {
	if ($('#deck_id').val() == 1)
		loadStandardDeck();
	
	$('#deckbox').show();
}

function closeDeck(save) {
	if (save) {
		if (deck_save_disabled) return;
		document.getElementById('deck_id').value = -1;
		$('#deckname').text('Custom');
	}
	else {
		document.getElementById('deck_id').value = 1;
		$('#deckname').text('Standard');
	}
	
	$('#deckbox').hide();
}

function updateDeckMessage() {
	window.clearTimeout(unique_timeout);
	var text = $('#textarea-nouns').val();
	var matches = text.match(/\w[ \t]*\n|\w[ \t]*$/g);
	var noun_count;
	if (matches)
		noun_count = matches.length;
	else
		noun_count = 0;
	
	var s = (noun_count == 1) ? '' : 's';
	$('#noun_count').text('This deck has ' + noun_count + ' noun'+s+' in it.');
	
	var max_players = $('#max_players').val();
	if (max_players < 3) max_players = 3;
	var min_nouns = max_players * 8;
	
	if (noun_count < min_nouns) {
		$('#noun_warning').text('You need at least ' + min_nouns + ' nouns to start for ' + max_players + ' players.');
		checkSaveButton();
	}
	else {
		$('#noun_warning').text('');
		checkSaveButton();
	}
	
	unique_timeout = window.setTimeout(uniqueCheck, 750);
}

function uniqueCheck() {
	var unique_nouns = new Array();
	var text = $('#textarea-nouns').val();
	var matches = text.split("\n");

	if (!matches) return;
	
	for (var i = 0; i < matches.length; i++) {
		var match = matches[i];
		
		var hashed_match = match.replace(/^\s+|\s+$/g,'').toLowerCase();
		
		if (unique_nouns[hashed_match] == 1) {
			$('#unique_warning').text('You have a duplicate noun (' + match + ') in this deck.');
			checkSaveButton();
			return;
		}
		unique_nouns[hashed_match] = 1;
	}
	
	$('#unique_warning').text('');
	checkSaveButton();
}

function checkSaveButton() {
	if ($('#unique_warning').text() || $('#noun_warning').text())
		disableSaveButton();
	else
		enableSaveButton();
}

function enableSaveButton() {
	deck_save_disabled = 0;
	$('#deck_save_button').removeClass('disabled');
}

function disableSaveButton() {
	deck_save_disabled = 1;
	$('#deck_save_button').addClass('disabled');
}