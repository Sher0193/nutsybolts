{include file="header-beta.tpl" page="landing"}
{include file="logo.tpl"}
<div class="game-bar">
	<div class="game-leftbar">
		<div class="helpbar">
			Welcome to <b>{$room_name}</b>!  This is a landing area where you can hang out and chat.<br/>
			<div id="message-gamestart">

			<span id="creator_name">{$creator}</span> will start the game when everyone's ready.
 
 			</div>
			<form action="/start-game.php" method="post" name="startform" onsubmit="game_started=1;">
				<input type="hidden" name="room_id" value="{$room_id}" />
				<input type="hidden" name="player_id" value="{$player_id}" />
				<input type="hidden" name="password" value="{$password}" />
				<input type="image" id="start_button" src="/images/start_button.png" width="79" height="30" name="start" value="Start" style="font-size: 8pt;float: right;margin: 5px;display:none;" disabled="true"
					onmouseover="this.src='/images/start_button_hl.png';" onmouseout="this.src='/images/start_button.png';" />		
			</form>
		</div>
		
		{include file="invitebox.tpl"}
		
		<div class="table_bg">
			<div class="header_big">Table</div>
			<div id="table_cards" class="disabled">
				<div class="help">This is the table where you can see and vote on the noun cards that everyone has played.</div>
				<div class="bottom"> </div>
			</div>
		</div>
		<div class="hand_bg">
			<div class="header_big">Your Hand</div>
			<div class="enabled" id="hand_cards">
				<div class="help">This is where you can choose one of your noun cards to play.</div>
				<div class="bottom"> </div>
			</div>
		</div>
	</div>
	<div class="game-rightbar">
		{include file="chat.tpl"}
		
		<div class="block">
			<div class="header_small">Players</div>
			<div class="body">
		
				<table cellspacing="0" cellpadding="1" width="100%" id="player_table">
					<tr class="playerlistheader">
						<td>Status</td><td>Name</td><td>Score</td><td>Ignore</td>{if $creator_flag}<td>Remove player?</td>{/if}
					</tr>
				</table>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>
	</div>
</div>
<script type="text/javascript">

room_id = {$room_id};
your_pid = {$player_id};
password = '{$password}';
room_password = '{$room_pw}';
hash = '{$hash|escape:'javascript'}';
creator_flag = {if $creator_flag}1{else}0{/if};
PUSHER_KEY = '{$pusher_key}';

if (creator_flag) activateCreator();

{foreach from=$players item=player key=pid}
playerAdded({ldelim}'id':{$pid}, 'name':'{$player.name|escape:'javascript'}', 'color':'{$player.color}', 'ignores':{$player.ignored}{rdelim});
{if $player.idle}
playerIdle({$pid});
{/if}
{/foreach}

{foreach from=$messages item=message}
addMessage({ldelim}'p':{$message.pid},'msg':'{$message.text|escape:'javascript'}'{rdelim});
{/foreach}

log_id = {if $log_id}{$log_id}{else}0{/if};
initLogHandlers({$room_id});
//getLogs();
</script>

{include file="footer-beta.tpl"}