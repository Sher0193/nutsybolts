var colors = new Array();
var names = new Array();
var player_ids = new Array();
var last_message_id = 0;
var game_started = 0;
var check_status_timeout;

function addMessages(messages) {
	var chatwindow = $('#chatwindow');
	var scrolled_flag = (chatwindow.scrollTop() + chatwindow.height() == chatwindow.get(0).scrollHeight);
	
	if (messages.length > 0)
		playSound('chat');
	
	for (var i = 0; i < messages.length; i++) {
		var message = messages[i];
		chatwindow.append('<div><div class="messageuser" style="color:' + colors[message.pid] + '">' +
			'' + names[message.pid] + ': </div>' + message.text + '</div>');
	}
	
	if (scrolled_flag)
		chatwindow.scrollTop(chatwindow.get(0).scrollHeight);
}

function updateStatus(is_away) {
	for (var i = 0; i < player_ids.length; i++) {
		var player_id = player_ids[i];
		if (is_away[player_id] == 2) {
			$('#playerrow' + player_id).addClass('skipped');
			$('#playerrow' + player_id).addClass('idle');
		}
		else if (is_away[player_id] == 1) {
			$('#playerrow' + player_id).addClass('idle');
			$('#playerrow' + player_id).removeClass('skipped');
		}
		else {
			$('#playerrow' + player_id).removeClass('idle');
			$('#playerrow' + player_id).removeClass('skipped');
		}
	}
	
	player_is_away = is_away;
}

function postMessage() {
	var messagebar = document.message.messagebar;
	if (!messagebar.value) return false;
	
	var message = messagebar.value.replace(/&/, '%26');
	var q = { 'message' : message };
	document.message.messagebar.value = '';
	AJAXRequest('post_message', q, handleMessage);
	
	return false; // so the form submit doesn't go through
}

function handleMessage(response) {
	if (response) {
		//document.message.messagebar.value = '';
		document.message.messagebar.focus();
	}
}

function sendDropped() {
	if (game_started == 0) {
		AJAXRequest('go_away', {}, droppedHandler);
	}
}

function droppedHandler() { }

function offerToRefresh() {
	$('#offertorefresh').slideDown('slow');
}

function ignorePlayer(player_id) {
	AJAXRequest('ignore', {'i': player_id}, ignoreHandler);
	$('#ignorelink' + player_id).text('Unignore').attr('href', 'javascript:unignorePlayer(' + player_id + ')');
}

function ignoreHandler() { }

function unignorePlayer(player_id) {
	AJAXRequest('unignore', {'i': player_id}, unignoreHandler);
	$('#ignorelink' + player_id).text('Ignore').attr('href', 'javascript:ignorePlayer(' + player_id + ')');
}

function unignoreHandler() { }

