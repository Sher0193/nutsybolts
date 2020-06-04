{include file="header-beta.tpl" page="index"}
		{include file="logo.tpl"}
		
		<script src="/scripts/jquery-ui-1.8.7.custom.min.js" type="text/javascript"></script>
		
		<div style="float: right; text-align: center;">
			<div>Follow Nutsy Bolts!</div>
				<br/>
			<div style="margin: 2px;"><a href="http://www.facebook.com/pages/Nutsy-Bolts/246486184868" target="_blank"><img src="/images/facebook.png" alt="Visit the Facebook page" border="0" /></a></div>
			<div style="margin: 2px;"><a href="http://www.twitter.com/nutsybolts/" target="_blank"><img src="/images/twitter.png" alt="Follow Nutsy Bolts on Twitter" border="0"/></a></div>
		</div>
		
		<form name="gameform" id="gameform" method="post">
		
		<div class="welcome">
			<div>Welcome to <b>Nutsy Bolts</b>, the game of crazy choices!</div>
			<div>Play for free - you'll never have to pay, sign up, or download anything!</div>
			<div>&nbsp;</div>
			<div>
				<label for="name">Enter your name:</label>
				<input type="text" name="name" id="namefield" maxlength="20" class="text" onFocus="$(this).removeClass('invalid');" />
				<div class="invalid_text" id="namefield_invalid">Please enter your name.</div>
			</div>
		</div>
		
		{if $status}
		<div class="error_message">
			{if $status == -1}
			We're sorry, the room you tried to enter no longer exists.
			{elseif $status == -2}
			We're sorry, the room you tried to enter has already started playing.
			{elseif $status == -3}
			We're sorry, the room you tried to enter is private.  Make sure you've entered the password correctly.
			{elseif $status == -4}
			We're sorry, the room you tried to enter is now full.  Please pick another room.
			{elseif $status == -5}
			We're sorry, your identity could not be verified.
			{elseif $status == -6}
			We're sorry, there's already someone in this room named "{$name}".  Please choose another name.
			{elseif $status == -7}
			We're sorry, you have been removed from the room.
			{elseif $status == -8}
			Sorry, there's already a room named {$room_name}.  You can join that game, or create a room with another name.
			{/if}
			<input type="button" value="Close" onclick="$('.error_message').slideUp('slow');" />
		</div>
		{/if}
		
		<br />
		{include file="pissoffie6.tpl"}
		
		{if $inactive_id}
		<div class="helpbar">
			Did you just get dropped from {$inactive_roomname}?  <a href="/rejoin.php?rid={$inactive_room}&pid={$inactive_id}&pw={$inactive_pw}">Rejoin it!</a>
		</div>
		{/if}
		
		<!--<div id="tablist">
			<span class="tab" onclick="showtab('join')" id="tab-join">
				Join a game
			</span>
			<span class="tab" onclick="showtab('create')" id="tab-create">
				Create a game
			</span>
		</div>-->
		
		<div style="clear: both;">&nbsp;</div>
		
		<input type="hidden" name="roomid" />
		<input type="hidden" name="password" />
		
		<div style="float: left; width: 50%;">
			<div class="block">
				<div class="header">Join a game</div>
				<div class="optionselector" id="selector-visibility">
					<span class="selected" id="visibility-all" value="all">All games</span>
					<span class="unselected" id="visibility-public" value="public">Public games</span>
					<span class="unselected" id="visibility-private" value="private">Private games</span>
				</div>
			
				<div class="form_field">
					<label for="unstarted">Show only games that haven't started yet</label>
					<input type="checkbox" name="unstarted" id="unstarted" onchange="refreshRoomList();" />
				</div>
			
				<div id="room_list">
				
				</div>
				<div class="bottom">&nbsp;</div>
			</div>
		</div>
		
		<div style="float: left; width: 50%;" id="div-create">	
			<div class="block">
				<div class="header">Create a game</div>
				{if !$spam_flag}
						<div class="formfield">
							<label for="room_name">Game name:</label>
							<input id="room_name" type="text" name="room_name" size="30" maxlength="100" class="text" onfocus="$(this).removeClass('invalid');" />
							<div class="invalid_text" id="create_room_message">Please enter a name for the game.</div>
						</div>
						<div class="formfield">
							<label for="rounds">Game length:</label>
							<input id="text-rounds" type="text" name="rounds" size="5" class="text" value="30" onfocus="$(this).removeClass('invalid');" onblur="this.value=Math.abs(parseInt(this.value));if(isNaN(this.value)||this.value<=0)this.value=20;" />
							<span class="postfield">rounds</span>
						</div>
				
						<div class="formfield">
							<label>&nbsp;</label>
							<div style="margin-left: 11em;margin-right:10px;" id="slider-rounds"></div>
						</div>
				
						<div class="formfield">
							<label for="max_players">Max Players:</label>
							<input id="max_players" type="text" name="max_players" size="5" class="text" value="12" onfocus="$(this).removeClass('invalid');" onblur="this.value=Math.abs(parseInt(this.value));if(isNaN(this.value)||this.value<=0)this.value=12;" />
							<span class="postfield">(3-30)</span>
							<div class="invalid_text" id="player_number_message">Nutsy Bolts only supports between 3 and 30 players.</div>
						</div>
						
						<div class="formfield">
							<label for="deck_id">Deck:</label>
							<input type="hidden" name="deck_id" id="deck_id" value="1" />
							<span id="deckname">Standard</span>
							<a href="javascript:showDeckDialog();">Edit deck</a>
						</div>
						
						<div class="formfield">
							<label for="private">Do others need to enter a password to join?</label>
							<input id="private" name="private" type="checkbox" checked="checked" onChange="updatePassword();" style="float: left; margin-top: 10px;" />
							<span id="password_span">
								<label for="create_password">Password</label>
								<input id="create_password" type="password" name="create_password" size="12" maxlength="20" class="text" onfocus="$(this).removeClass('invalid');"/>
								<div class="invalid_text" id="create_password_message">Please choose a password for the room.</div>
							</span>
						</div>
						
						<div class="formfield">
							<label>&nbsp;</label>
							<div class="button" onclick="createValidate();">Create!</div>
						</div>
					<div style="clear: both;"></div>
				{else}
				Please wait a bit before you create another game.  If you like, you can join someone else's game.
				{/if}
				<div class="bottom">&nbsp;</div>
			</div>
			</div>
		{include file="deckbox.tpl"}
		</form>
		
		<script type="text/javascript">
		{literal}
			$('document').ready(function() {
				selectGameVisibility('all');
				$('#selector-visibility span').click(
					function() {
						selectGameVisibility($(this).attr('value'));
					}
				);
				
				refreshRoomList();
			});
			
			$( "#slider-rounds" ).slider({
				value:30,
				min: 0,
				max: 150,
				step: 5,
				slide: function( event, ui ) {
					$( "#text-rounds" ).val( ui.value );
				}
			});
			$( "#text-rounds" ).val( $( "#slider-rounds" ).slider( "value" ) );
			
		{/literal}
		</script>
{include file="footer-beta.tpl"}