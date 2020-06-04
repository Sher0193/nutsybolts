<?php /* Smarty version 2.6.18, created on 2008-06-03 20:28:16
         compiled from create-dialog.tpl */ ?>
<div id="create_popup" style="display:none;" title="Create a new game">
	<div class="block">
		<div class="blockheader">
			<span id="close_button"><img src="/images/x_button.png" height="10" width="10" onClick="closeCreate();" /></span>
			Create a new game
		</div>
		<form action="/create-game.php" method="post" name="createform" id="createform" onSubmit="return createValidate();">
			<div class="formfield">
				<label for="name">Your name</label>
				<input id="name" type="text" name="name" size="20" maxlength="20" class="text" onFocus="this.className='text';" />
				<div class="invalid_text" style="display: none;" id="create_name_message">Please enter your name.</div>
			</div>
			<div class="formfield">
				<label for="room_name">Room name</label>
				<input id="room_name" type="text" name="room_name" size="30" maxlength="100" class="text" onFocus="this.className='text';" />
				<div class="invalid_text" style="display: none;" id="create_room_message">Please enter a name for the room.</div>
			</div>
			<div class="formfield">
				<label for="rounds">Rounds</label>
				<input id="rounds" type="text" name="rounds" size="5" class="text" value="20" onblur="this.value=Math.abs(parseInt(this.value));if(isNaN(this.value) || this.value<=0)this.value=20;" />
			</div>
			<div class="formfield">
				<label for="private">Private?</label>
				<select id="private" name="private" style="float:left;margin-left: 1px; margin-top: 4px;" onChange="updatePassword();">
					<option value="0">No</option>							
					<option value="1">Yes</option>
				</select>
				<span id="password_span" style="display:none;"><label for="password">Password</label>
				<input id="password" type="password" name="password" size="12" maxlength="20" class="text" onfocus="this.className='text';"/></span>
				<div class="invalid_text" style="display: none;" id="create_password_message">Please choose a password for the room.</div>
			</div>
			<div class="formfield">
				<label for="max_players">Max Players</label>
				<input id="max_players" type="text" name="max_players" size="5" class="text" value="12" onblur="this.value=Math.abs(parseInt(this.value));if(isNaN(this.value) || this.value<=0)this.value=12;" />
			</div>
			<div style="text-align:center;clear:both;">
				<input type="image" name="create" value="Create" src="/images/create_button.png" onmouseover="this.src='/images/create_button_hl.png';" width="77" height="30" onMouseOut="this.src='/images/create_button.png';" />
			</div>
		</form>
	</div>
</div>