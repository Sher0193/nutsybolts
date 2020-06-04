<?php /* Smarty version 2.6.18, created on 2011-06-02 20:22:25
         compiled from index-beta2.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header-beta.tpl", 'smarty_include_vars' => array('page' => 'index')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "logo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
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
?  <a href="/rejoin.php?rid=<?php echo $this->_tpl_vars['inactive_room']; ?>
&pid=<?php echo $this->_tpl_vars['inactive_id']; ?>
&pw=<?php echo $this->_tpl_vars['inactive_pw']; ?>
">Rejoin it!</a>
		</div>
		<?php endif; ?>
		
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
				<?php if (! $this->_tpl_vars['spam_flag']): ?>
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
				<?php else: ?>
				Please wait a bit before you create another game.  If you like, you can join someone else's game.
				<?php endif; ?>
				<div class="bottom">&nbsp;</div>
			</div>
			</div>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "deckbox.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</form>
		
		<script type="text/javascript">
		<?php echo '
			$(\'document\').ready(function() {
				selectGameVisibility(\'all\');
				$(\'#selector-visibility span\').click(
					function() {
						selectGameVisibility($(this).attr(\'value\'));
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
			
		'; ?>

		</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer-beta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>