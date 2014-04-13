{include file="header-beta.tpl" page="game"}
	{include file="logo.tpl"}
	
	<audio id="sound-gavel">
		<source src="/sounds/gavel.mp3" />
		<source src="/sounds/gavel.ogg" type="application/ogg" />
	</audio>
	
	<audio id="sound-shuffle">
		<source src="/sounds/shuffle.mp3" />
		<source src="/sounds/shuffle.ogg" type="application/ogg" />
	</audio>	
	
<div class="game-bar">
	<div class="game-leftbar">	
		<div>
			<div class="top_info_bar" id="round_text"></div>
			<div class="top_info_bar" id="greencard"><a id="greencard_link" href="http://en.wiktionary.org/wiki/" target="_blank"></a></div>
			<div class="top_info_bar" id="judge_name"></div>
		</div>
		
		<div id="gameover">
			<div id="gameover-message"></div>
			<div>
				<span style="width: 50%; float: left; display: inline-block;"><a href="/">Go back to the lobby</a></span>
				<span style="width: 50%; float: left; display: inline-block;"><a href="javascript:oneMoreRound();">Just...one...more...round...</a></span>
			</div>	
		</div>
	
				
		<div class="table_bg">
			<div class="header_big">Table</div>
			<div id="table_message"></div>
			<div id="table_cards" class="disabled">
			</div>
			<div style="clear:both;"></div>
		</div>
				
		<div class="hand_bg">
			<div class="header_big">Your Hand</div>
			<div id="hand_message"></div>
			<div id="hand_cards">
				{foreach from=$hand item=card_name key=order}
				<div class="card_container" id="container{$order}">
					<a class="hand_card" href="javascript:playCard({$order});" id="hand{$order}" onMouseOver="return true;"><div class="card_caption">{$card_name}</div></a>
					<div><a href="http://en.wikipedia.org/wiki/{$card_name}" target="_blank">look it up</a></div>
				</div>
				{/foreach}
			</div>
		</div>
	</div>	

	<div class="game-rightbar">
		<div id="skip_block" class="game-message">
			Oh no!  The other players voted to skip your turn.  If you want to chill out and not play, that's cool; but if you want to get back into the game, just play a card next time you have the chance.
		</div>
	
		{include file="chat.tpl"}

		<div class="block">
			<div class="header_small">Players</div>
			<div><a href="javascript:showInviteDialog();" id="invite-link">Invite more players</a></div>
			<div class="body">
				<table cellspacing="0" cellpadding="1" width="100%" id="player_table">
					<tr class="playerlistheader" id="playerlistheader">
						<td>&nbsp;</td><td>Name</td><td>Score</td><td>Ignore</td><td></td>
					</tr>
				</table>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>
				
		<div class="block">
			<div class="header_small">Round History</div>
			<div class="body">
				<table cellspacing="0" cellpadding="1" width="100%">
					<tr class="historyheader">
						<td>#</td><td>Winner</td><td>Cards</td>
					</tr>
				</table>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>
	</div>
	
	<div id="invite-dialog">
		{include file="invitebox.tpl" dialog=1}
	</div>
</div>	
	<script type="text/javascript">
	room_id = {$room_id};
	your_pid = {$player_id};
	password = '{$password}';
	global_round_number = {$round_number};
	global_max_rounds = {$max_rounds};

	log_id = {$log_id};

	{foreach from=$players item=player key=pid}
	playerAdded({ldelim}'id':{$pid}, 'name':'{$player.name|escape:'javascript'}', 'color':'{$player.color}', 'ignores':{$player.ignored}, 'score':{$player.score}, 'j':{$player.judge}, 'start': 1{rdelim});
	{if $player.idle}
	playerIdle({$pid});
	{/if}
	{if $player.skipped}
	playerSkip({ldelim}'p':{$pid}{rdelim});
	{/if}
	{/foreach}
	refreshTableStripes();
	
	{foreach from=$deleted_players item=player key=pid}
	deletedPlayerAdded({ldelim}'id':{$pid},'name':'{$player.name|escape:'javascript'}'{rdelim});
	{/foreach}

	{foreach from=$history item=historyitem}
	addToHistory({$historyitem.round}, {$historyitem.winner}, '{$historyitem.adjective|escape:'javascript'}', '{$historyitem.noun|escape:'javascript'}');
	{/foreach}

		
	{foreach from=$messages item=message}
	addMessage({ldelim}'p':{$message.pid},'msg':'{$message.text|escape:'javascript'}'{rdelim});
	{/foreach}

	{foreach from=$played_cards key=pid item=name}
	addCardToTable({ldelim}'p':{$pid},'n':'{$name|escape:'javascript'}'{rdelim});
	{/foreach}

	updateGreenCard('{$green_card}');
	updateJudge({$current_judge});

	{if $played_flag || $is_judge}
	deactivateHand();
	{else}
	activateHand();
	{/if}
	
	{if $phase == 2}
	switchToJudge();
	{/if}

	creator_flag = {if $creator_flag}1{else}0{/if};
	{if $creator_flag}activateCreator();{/if}
	
	{foreach from=$hand item=card_name key=order}
	your_hand[{$order}] = '{$card_name|escape:'javascript'}';
	{/foreach}
	
	updateRoundText();
	{if $round_number > $max_rounds}
	gameOver();
	{/if}
	
	initLogHandlers({$room_id});
	//getLogs();
				
	</script>
{include file="footer-beta.tpl"}