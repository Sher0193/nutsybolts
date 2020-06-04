<?php /* Smarty version 2.6.18, created on 2010-07-14 20:48:40
         compiled from index-beta.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header-beta.tpl", 'smarty_include_vars' => array('page' => 'index')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
<?php echo '
$(\'document\').ready(function() {
	$(\'.select_div div\').removeClass(\'selected\');
	document.joinform.roomid.value = 0;
	refreshRoomList();
});
'; ?>

</script>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "logo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
		<div style="float: right; text-align: center;">
			<div>Follow Nutsy Bolts!</div>
				<br/>
			<div style="margin: 2px;"><a href="http://www.facebook.com/pages/Nutsy-Bolts/246486184868" target="_blank"><img src="/images/facebook.png" alt="Visit the Facebook page" border="0" /></a></div>
			<div style="margin: 2px;"><a href="http://www.twitter.com/nutsybolts/" target="_blank"><img src="/images/twitter.png" alt="Follow Nutsy Bolts on Twitter" border="0"/></a></div>
		</div>
		
		<div class="welcome">
			<div>Welcome to <b>Nutsy Bolts</b>, the game of crazy choices!</div>
			<div>You can join a game that's waiting for more players, or create a new one if you don't see one you like.</div>
			<div>Want to learn more?  Visit the <a href="/tutorial.php">Tutorial!</a></div>
		</div>
		
		<?php if ($this->_tpl_vars['status']): ?>
		<div class="error_message">
			<?php if ($this->_tpl_vars['status'] == -1): ?>
			We're sorry, the room you tried to enter no longer exists.
			<?php elseif ($this->_tpl_vars['status'] == -2): ?>
			We're sorry, the room you tried to enter has already started playing.
			<?php elseif ($this->_tpl_vars['status'] == -3): ?>
			We're sorry, the room you tried to enter is private.  Make sure you've entered the password correctly.
			<?php elseif ($this->_tpl_vars['status'] == -4): ?>
			We're sorry, the room you tried to enter is now full.  Please pick another room.
			<?php elseif ($this->_tpl_vars['status'] == -5): ?>
			We're sorry, your identity could not be verified.
			<?php elseif ($this->_tpl_vars['status'] == -6): ?>
			We're sorry, there's already someone in this room named "<?php echo $this->_tpl_vars['name']; ?>
".  Please choose another name.
			<?php elseif ($this->_tpl_vars['status'] == -7): ?>
			We're sorry, you have been removed from the room.
			<?php elseif ($this->_tpl_vars['status'] == -8): ?>
			Sorry, there's already a room named <?php echo $this->_tpl_vars['room_name']; ?>
.  You can join that game, or create a room with another name.
			<?php endif; ?>
			<input type="button" value="Close" onclick="$('.error_message').slideUp('slow');" />
		</div>
		<?php endif; ?>
		
		<br />
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pissoffie6.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
		<?php if ($this->_tpl_vars['inactive_id']): ?>
		<div class="helpbar">
			Did you just get dropped from <?php echo $this->_tpl_vars['inactive_roomname']; ?>
?  <a href="/game.php/<?php echo $this->_tpl_vars['inactive_room']; ?>
_<?php echo $this->_tpl_vars['inactive_hash']; ?>
">Rejoin it!</a>
		</div>
		<?php endif; ?>
		
		<div style="position: relative;clear: both;">
		
			<div style="float:left;width:60%;">
			
				<div class="block">
				<div class="optionheader"><img src="/images/join_text.png" alt="Join a game" /></div>
					<form action="/join-game.php" style="padding: 5px;" method="post" name="joinform" onsubmit="return joinValidate();">
						<div class="formfield">
							<label for="join_name">Your name</label>
							<input type="text" class="text" id="join_name" name="name" size="20" maxlength="20" onfocus="this.className='text';" />
							<div class="invalid_text" id="name_message">Please enter your name.</div>
						</div>
						
						<div class="formfield">
							<input type="hidden" name="roomid" id="hidden_roomid" />
			
							<label for="roomid">
								Game
							</label>
							<div class="select_div" id="roomid">
							
							</div>
							<div class="roominfo">
								<div class="roominfoheader">Game Info</div>
								<div id="roominfo">
									<div class="message">Choose a game</div>
								</div>
								<div class="roominfoheader">Player List</div>
								<div id="playerlist">
									<div class="message">Choose a game</div>
								</div>
							</div>
						</div>
			
						<div class="formfield" id="passwordfield" style="display: none;">			
							<label for="password">Password</label>
							<input type="password" class="text" name="password" size="10" />
							<div class="invalid_text" id="password_message" style="display: none;">
								This is a private room.  Please enter the password to join.
							</div>
						</div>
						<div class="buttonfield">
							<input type="image" name="join" id="joinbutton" value="Join" src="images/join_button.png" onMouseOver="this.src='/images/join_button_hl.png';" width="79" height="23" onMouseOut="this.src='/images/join_button.png';" />
						</div>
						<div style="clear: both;"></div>
					</form>	
					<div class="bottom"> </div>
				</div>
			</div>
			
			<div style="float:right;width:40%;">
			
				<div class="block">
					<div class="optionheader"><img src="/images/create_text.png" alt="Create a game" /></div>
					<?php if (! $this->_tpl_vars['spam_flag']): ?>
					<form action="/create-game.php" method="post" name="createform" id="createform" onSubmit="return createValidate();">
						<div class="formfield">
							<label for="name">Your name</label>
							<input id="name" type="text" name="name" size="20" maxlength="20" class="text" onFocus="this.className='text';" />
							<div class="invalid_text" style="display: none;" id="create_name_message">Please enter your name.</div>
						</div>
						<div class="formfield">
							<label for="room_name">Game name</label>
							<input id="room_name" type="text" name="room_name" size="20" maxlength="100" class="text" onFocus="this.className='text';" />
							<div class="invalid_text" style="display: none;" id="create_room_message">Please enter a name for the room.</div>
						</div>
						<div class="formfield">
							<label for="rounds">Rounds</label>
							<input id="rounds" type="text" name="rounds" size="5" class="text" value="30" onblur="this.value=Math.abs(parseInt(this.value));if(isNaN(this.value)||this.value<=0)this.value=20;" />
						</div>
						<div class="formfield">
							<label for="private">Private?</label>
							<select id="private" name="private" style="float:left;margin-left: 1px; margin-top: 4px;" onChange="updatePassword();">
								<option value="0">No</option>							
								<option value="1" selected="selected">Yes</option>
							</select>
							<span id="password_span"><label for="password">Password</label>
							<input id="password" type="password" name="password" size="12" maxlength="20" class="text" onfocus="this.className='text';"/></span>
							<div class="invalid_text" style="display: none;" id="create_password_message">Please choose a password for the room.</div>
						</div>
						<div class="formfield">
							<label for="max_players">Max Players (3-100)</label>
							<input id="max_players" type="text" name="max_players" size="5" class="text" value="12" onblur="this.value=Math.abs(parseInt(this.value));if(isNaN(this.value)||this.value<=0)this.value=12;" />
							<div class="invalid_text" style="display: none;" id="player_number_message">Nutsy Bolts only supports between 3 and 100 players.</div>
						</div>
						<div class="buttonfield">
							<input type="image" name="create" value="Create" src="/images/create_button.png" onmouseover="this.src='/images/create_button_hl.png';" width="88" height="23" onmouseout="this.src='/images/create_button.png';" />
						</div>
						<div style="clear: both;"></div>
					</form>
					<?php else: ?>
					Please wait a bit before you create another game.  If you like, you can join someone else's game.
					<?php endif; ?>
					<div class="bottom"> </div>
				</div>
			</div>
		</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer-beta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>