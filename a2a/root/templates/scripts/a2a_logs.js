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
var PUSHER_KEY;

function initLogHandlers(rid) {
	Pusher.channel_auth_endpoint = '/pusher_auth.php';
	var pusher = new Pusher(PUSHER_KEY);
	var myChannel = pusher.subscribe('presence-room' + rid);
	
	// events that occur on both landing and game pages
	myChannel.bind('crtr', updateCreator);
	myChannel.bind('msg', addMessage);
	myChannel.bind('idle', playerIdle);
	myChannel.bind('unidle', playerUnidle);
	myChannel.bind('newpl', playerAdded);
	//myChannel.bind('pusher:member_added', pusherAddPlayer);
	myChannel.bind('pusher:member_removed', pusherRemovePlayer);
	myChannel.bind('rmpl', playerRemoved);
	
	if (page == 'landing') {
		// events that occur on landing page only
		myChannel.bind('start', gameStart);
	}
	else if (page == 'game') {
		// events that occur on game page only
		myChannel.bind('skip', playerSkip);
		myChannel.bind('unskip', playerUnskip);
		myChannel.bind('played', addCardToTable);
		myChannel.bind('voted', getJudgeVote);
		myChannel.bind('mr', addOneMoreRound);
	}
}

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
/*	if (!message) return;
	
	var scrolled_flag = isChatAutoscroll();

	$('#chatwindow').append('<div class="temp_message"><div class="messageuser" style="color:' + colors[your_pid] + '">' +
		'' + names[your_pid] + ': </div>' + message + '</div>');
	
	if (scrolled_flag)
		scrollChatWindow();*/
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

function pusherRemovePlayer(member) {
	removePlayer(member.id);
}

function removePlayer(removed_id) {
	AJAXRequest('remove_player', {'rid' : removed_id}, handleRemovePlayer);
}

function handleRemovePlayer(response) { }