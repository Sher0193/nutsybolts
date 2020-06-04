{include file="header.tpl" page_name="landing"}

<script type="text/javascript">
room_id = {$room_id};
pid = {$player_id};
password = '{$password}';

{if !$creator_flag}
/*window.setTimeout('checkStatus()', 5000);*/
checkStatus();
{/if}
</script>

<div class="table_container" id="chatareacontainer" style="display:table-row;">
	<div class="apple_table">
		<div class="table_header">
			<span class="th_left"></span>
			<span class="th_right"></span>
			<div class="th_text">Chat Window</div>
		</div>
		<div class="table_body" style="height:400px; min-height: 400px;max-height: 400px;">
			<div id="chatwindow" style="min-height: 400px;height: 400px; max-height: 400px;">
				<div id="message">Lorem ipsum dolor sit amet</div>
			</div>
		</div>
		<div class="text_footer">
			<form onsubmit="return postMessage();" name="messagebar">
				<div class="text_left"></div>
				<div class="text_right"><input type="image" src="/images/send_button.png" height="29" width="55" onMouseOver="this.src='/images/send_button_hl.png';" onMouseOut="this.src='/images/send_button.png';" /></div>
				<div class="message_container"><input type="text" name="message" class="message_input" /></div>
			</form>
		</div>
	</div>
</div>

<div class="table_container" id="playerlistcontainer" style="display:table-row;">
	<div class="apple_table">
		<div class="table_header">
			<span class="th_left"></span>
			<span class="th_right"></span>
			<div class="th_text">Player List</div>
		</div>
		<div class="table_body" style="height:100%;">
			<div id="playerlist">
				{foreach from=$players key=pid item=playername}
				<div class="{cycle values="player1,player2"}"{if $pid==$player_id} id="you"{/if}>
					<div class="playericon"></div>
					<div class="playername">{$playername}</div>
					<div class="playerscore">0</div>
					<div class="playermute"><img id="mutebulb{$pid}" src="/images/mutebulb_on.png" onClick="mute({$pid});" /></div>
				</div>
				{/foreach}
			</div>
			<form name="awayform" onSubmit="return false;">
				<div id="away_message" style="visibility: hidden;">You are away now.<br/>You cannot play cards or act as the judge.<br/>Use the button below to return from away.</div>
				<input style="display: inline;" type="image" id="awaybutton" value="I'm Away" src="/images/away_button.png" height="30" width="77" onMouseOver="this.src='/images/away_button_hl.png';" onMouseOut="this.src='/images/away_button.png';" onClick="goAway();" />
				<input style="display: none;" type="image" id="backbutton" value="Back" src="/images/back_button.png" height="30" width="77" onMouseOver="this.src='/images/back_button_hl.png';" onMouseOut="this.src='/images/back_button.png';" onClick="comeBack();" />
			</form>
		</div>
		<div class="table_footer">
			<span class="tf_left"></span>
			<span class="tf_right"></span>
		</div>
	</div>
</div>

<div style="clear: both;">&nbsp;</div>

<div class="apple_table">
	<div class="table_header">
		<span class="th_left"></span>
		<span class="th_right"></span>
		<div class="th_text">Landing Area</div>
	</div>
	<div class="table_body">
		Welcome to the landing area!  This is a page where you can chat with other players before the game starts.
		{if $creator_flag}
		<p>As the creator of this room, you get to start off the game once everyone's ready.  Just click the button below when you're all set.</p>
		<form action="/start-game.php" method="post">
			<input type="hidden" name="room_id" value="{$room_id}" />
			<input type="hidden" name="player_id" value="{$player_id}" />
			<input type="hidden" name="password" value="{$password}" />
			<input type="image" name="start" value="Start!" src="/images/start_button.png" height="30" width="77" onMouseOver="this.src='/images/start_button_hl.png';" onMouseOut="this.src='/images/start_button.png';" />
		</form>
		{else}
		<p>Just hang tight and wait for the room's creator to start things off!</p>
		{/if}
	</div>
	<div class="table_footer">
		<span class="tf_left"></span>
		<span class="tf_right"></span>
	</div>
</div>

{include file="footer.tpl"}