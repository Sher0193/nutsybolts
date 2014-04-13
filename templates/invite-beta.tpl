{include file="header-beta.tpl" page="invite"}
<script type="text/javascript">

{foreach from=$players item=name key=pid}
names[{$pid}] = '{$name|escape:'javascript'}';
{/foreach}
{foreach from=$colors item=color key=pid}
colors[{$pid}] = '#{$color}';
{/foreach}
{foreach from=$player_ids item=pid key=index}
player_ids[{$index}] = {$pid};
{/foreach}
</script>
{include file="logo.tpl"}
<div class="game-bar">
	<div class="game-leftbar">
		<div class="block">
			<div style="text-align:center;"><img src="/images/join_invited.png" title="invite" /></div>
			Welcome to <b>{$room_name}</b>!<br/>
			{if $started}
			The game has already started, but don't worry, you can still get in on the fun!
			{else}
			The game hasn't started yet, so enter your name and get in on the fun!
			{/if}
			<form action="/join-game.php" style="padding: 5px;" method="post" name="joinform" onsubmit="return joinValidate();">
				<input type="hidden" name="roomid" value="{$room_id}" />
				{if $password}<input type="hidden" name="password" value="{$password}" />{/if}
				<input type="hidden" name="method" value="invite" />
				<div class="formfield">
					<label for="join_name">Your name</label>
					<input type="text" class="text" id="join_name" name="name" size="20" maxlength="20" onfocus="this.className='text';" />
					<input type="image" name="join" id="joinbutton" value="Join" src="/images/join_button.png" onMouseOver="this.src='/images/join_button_hl.png';" width="79" height="23" onMouseOut="this.src='/images/join_button.png';" style="margin-left: 5px;" />
					<div class="invalid_text" id="name_message">Please enter your name.</div>
				</div>
				<div style="clear: both;"></div>
			</form>
			<div class="bottom">&nbsp;</div>
		</div>
	</div>
	<div class="game-rightbar">
		<!--
		
		<div class="block">
			<div style="text-align:center;"><img src="/images/players_text.png" title="Players" /></div>
			<div class="body">
		
				<table cellspacing="0" cellpadding="1" width="100%" id="player_table">
					<tr class="playerlistheader">
						<td>Status</td><td>Name</td><td>Score</td>
					</tr>
					
					{foreach from=$players key=pid item=playername}
					<tr class="{cycle values="player1,player2"}" id="playerrow{$pid}">
						<td id="playericon{$pid}"></td>
						<td width="70%" id="playername{$pid}">{$playername}</td>
						<td id="playerscore{$pid}">0</td>
					</tr>
					{/foreach}
				</table>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>-->
	</div>
</div>
{include file="footer-beta.tpl"}