var room_password;

function gameStart() {
	window.location = '/game.php/' + room_id + '/' + room_password;
}

function activateCreator() {
	$('#message-gamestart').text('You have the power to start the game.');
	showRemoveLinks();
	refreshStartButton();
}

function deactivateCreator(creator_id) {
	$('#message-gamestart').html('Hey, welcome back!  While you were out, we gave someone else the keys to start the game.  Just sit tight until <span id="creator_name">' + names[creator_id] + '</span> starts the game!');
	hideRemoveLinks();
	refreshStartButton();
}

function updateCreatorName(creator_id) {
	$('#creator_name').text(names[creator_id]);
}

function playerAdded(player) {
	player_ids[player_ids.length] = player.id;
	names[player.id] = player.name;
	colors[player.id] = '#' + player.color;
	
	// update the player list HTML
	var ignore_link;
	if (player.id == your_pid) {
		ignore_link = '';
	}
	else if (player.ignores == 1) {
		ignore_link = '[&nbsp;<a href="javascript:unignorePlayer(' + player.id + ');" id="ignorelink' + player.id + '">Unignore</a>&nbsp;]';
	}
	else {
		ignore_link = '[&nbsp;<a href="javascript:ignorePlayer(' + player.id + ');" id="ignorelink' + player.id + '">Ignore</a>&nbsp;]';
	}
	
	$('#player_table').append(
		'<tr id="playerrow' + player.id + '"><td id="playericon' + player.id + '"></td><td>' + player.name + '</td><td>0</td><td>' + ignore_link + '</td></tr>'
	);
	if (creator_flag)
		$('#playerrow' + player.id).append('<td>[&nbsp;<a href="javascript:removePlayer(' + player.id + ');">Remove</a>&nbsp;]</td>');
	
	refreshTableStripes();
	refreshStartButton();
}

function playerRemoved(data) {
	var removed_pid = data.p;
	if (removed_pid == your_pid)
		window.location = '/index.php?status=-7';
	
	names[removed_pid] = null;
	colors[removed_pid] = null;
	
	for (var i=0; i < player_ids.length; i++) {
		if (player_ids[i] == removed_pid)
			player_ids.splice(i, 1);
	}
	
	$('#playerrow' + removed_pid).remove();
	refreshTableStripes();
	refreshStartButton();
}

function refreshStartButton() {
	if (creator_flag == true && names.length >= 3)
		$('#start_button').removeAttr('disabled').css('display', 'inline').css('cursor', 'pointer');
	else
		$('#start_button').attr('disabled', 'disabled').css('display', 'none');
}