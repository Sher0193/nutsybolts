var colors = new Array();
var names = new Array();
var player_ids = new Array();
var last_message_id = 0;
var game_started = 0;
var page;
var check_status_timeout;
var log_handlers = new Array();
var is_idle = new Array();
var log_id;
var creator_flag;
var log_timeout;
var ignores = new Array();
var your_pid;
//var global_chat_autoscroll = true;
var CHAT_AUTOSCROLL_MARGIN = 10;

function initLogHandlers() {
	// events that occur on both landing and game pages
	log_handlers['crtr'] = updateCreator;
	log_handlers['msg'] = addMessage;
	log_handlers['idle'] = playerIdle;
	log_handlers['unidle'] = playerUnidle;
	log_handlers['newpl'] = playerAdded;
	log_handlers['rmpl'] = playerRemoved;
	
	if (page == 'landing') {
		// events that occur on landing page only
		log_handlers['start'] = gameStart;
	}
	else if (page == 'game') {
		// events that occur on game page only
		log_handlers['skip'] = playerSkip;
		log_handlers['unskip'] = playerUnskip;
		log_handlers['played'] = addCardToTable;
		log_handlers['voted'] = getJudgeVote;
		log_handlers['mr'] = addOneMoreRound;
	}
}

/*function setChatAutoScroll(value) {
	global_chat_autoscroll = value;
}*/

function isChatAutoscroll() {
//	return global_chat_autoscroll;
	var chatwindow = $('#chatwindow');
	return (chatwindow.scrollTop() + chatwindow.height() >= chatwindow.get(0).scrollHeight - CHAT_AUTOSCROLL_MARGIN);
}

function scrollChatWindow() {
	var chatwindow = $('#chatwindow');
	chatwindow.scrollTop(chatwindow.get(0).scrollHeight);
}

function displayScrollMessage() {
	if (isChatAutoscroll()) {
		$('#message-autoscroll').css('visibility', 'hidden');
	}
	else {
		$('#message-autoscroll').css('visibility', 'visible');
	}
}

function addMessage(message) {
	if (!message) return;
	
	if (ignores[message.p] == 1) return;
	
	var scrolled_flag = isChatAutoscroll();
	
	$('#chatwindow .temp_message').remove();
	
	/*for (var i = 0; i < messages.length; i++) {
		var message = messages[i];*/
		$('#chatwindow').append('<div><div class="messageuser" style="color:' + colors[message.p] + '">' +
			'' + names[message.p] + ': </div>' + message.msg + '</div>');
	//}
	
	if (scrolled_flag)
		scrollChatWindow();
}

function addTempMessage(message) {
	if (!message) return;
	
	var scrolled_flag = isChatAutoscroll();
	
	/*if (messages.length > 0)
		playSound('chat');*/
	
	/*for (var i = 0; i < messages.length; i++) {
		var message = messages[i];*/
		$('#chatwindow').append('<div class="temp_message"><div class="messageuser" style="color:' + colors[your_pid] + '">' +
			'' + names[your_pid] + ': </div>' + message + '</div>');
	//}
	
	if (scrolled_flag)
		scrollChatWindow();
}

function playerIdle(pid) {
	$('#playerrow' + pid).addClass('idle');
	is_idle[pid] = 1;
}
function playerUnidle(pid) {
	$('#playerrow' + pid).removeClass('idle');
	is_idle[pid] = 0;
}

function postMessage() {
	var messagebar = document.message.messagebar;
	if (!messagebar.value) return false;
	
	var message = messagebar.value.replace(/&/, '%26');
	var q = { 'message' : message };
	document.message.messagebar.value = '';
	AJAXRequest('post_message', q, handleMessage);
	addTempMessage(message);
	
	return false; // so the form submit doesn't go through
}

function handleMessage(response) {
	if (response) {
		//document.message.messagebar.value = '';
		document.message.messagebar.focus();
	}
}

function getLogs() {
	AJAXRequest('logs', {'l': log_id}, logHandler);
	refresh_timeout = window.setTimeout(offerToRefresh, 12000);
}

function logHandler(response) {
	clearTimeout(log_timeout);
	clearTimeout(refresh_timeout);
	$('#offertorefresh').slideUp('slow');
	
	if (response.error) {
		if (response.error == 'noplayer')
			window.location = '/index.php?status=-7';
		else if (response.error == 'database') {
			showDatabaseError();
			return;
		}
	}
	
	else {
		for (var i = 0; i < response.length; i++) {
			var log = response[i];
			
			if (log.id <= log_id) continue;
			
			if (typeof log_handlers[log.t] == 'function') {
				log_handlers[log.t](log.d);
			}
			log_id = 0 + log.id;
		}
		log_timeout = window.setTimeout(getLogs, 2500);
	}
}

function sendDropped() {
	if (game_started == 0) {
		AJAXRequest('go_away', {}, droppedHandler);
	}
}

function droppedHandler() { }

function updateCreator(response) {
	var was_creator = creator_flag;
	if (response == your_pid && !was_creator) {
		creator_flag = true;
		activateCreator();
	}
	else if (response != your_pid && was_creator) {
		creator_flag = false;
		deactivateCreator(response);
	}
	if (page == 'landing')
		updateCreatorName(response);
}


function ignorePlayer(player_id) {
	AJAXRequest('ignore', {'i': player_id}, ignoreHandler);
	ignores[player_id] = 1;
	$('#ignorelink' + player_id).text('Unignore').attr('href', 'javascript:unignorePlayer(' + player_id + ')');
}

function ignoreHandler() { }

function unignorePlayer(player_id) {
	AJAXRequest('unignore', {'i': player_id}, unignoreHandler);
	ignores[player_id] = 0;
	$('#ignorelink' + player_id).text('Ignore').attr('href', 'javascript:ignorePlayer(' + player_id + ')');
}

function unignoreHandler() { }

function offerToRefresh() {
	$('#offertorefresh').slideDown('slow');
}

function restoreConnection() {
	$('#offertorefresh').slideUp('slow');
	getLogs();
}

function showDatabaseError() {
	$('#database_error').slideDown('slow');
}

function refreshTableStripes() {
	$('#player_table tr:even[class!="playerlistheader"]').css('background-color','').removeClass('player1').removeClass('player2').addClass('player2');
	$('#player_table tr:odd[class!="playerlistheader"]').css('background-color','').removeClass('player1').removeClass('player2').addClass('player1');
	
	if ($('#playerrow' + your_pid).hasClass('player1'))
		$('#playerrow' + your_pid).removeClass().addClass('player1you');
	else if ($('#playerrow' + your_pid).hasClass('player2'))
		$('#playerrow' + your_pid).removeClass().addClass('player2you');
}

function showRemoveLinks(show) {
	$('.playerlistheader').append('<td></td>');
	for (var i = 0; i < player_ids.length; i++) {
		var p = player_ids[i];
		
		if (p == your_pid) {
			$('#playerrow' + p).append('<td></td>');
		}
		else {
			$('#playerrow' + p).append('<td>[&nbsp;<a href="javascript:removePlayer(' + p + ');">Remove</a>&nbsp;]</td>');
		}
	}
}

function hideRemoveLinks() {
	$('.playerlistheader td:last').remove();
	
	for (var i = 0; i < player_ids.length; i++) {
		var p = player_ids[i];
		
		$('#playerrow' + p + ' td:last').remove(); // get rid of the remove link
	}
}


function removePlayer(removed_id) {
	AJAXRequest('remove_player', {'rid' : removed_id}, handleRemovePlayer);
}

function handleRemovePlayer(response) { }

initLogHandlers();