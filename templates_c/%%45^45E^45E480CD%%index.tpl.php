<?php /* Smarty version 2.6.18, created on 2007-11-23 21:37:05
         compiled from index.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('page_name' => 'index')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php if ($this->_tpl_vars['status'] == -1): ?>
		<div class="error_message">We're sorry, the room you tried to enter no longer exists.</div>
		<?php elseif ($this->_tpl_vars['status'] == -2): ?>
		<div class="error_message">We're sorry, the room you tried to enter has already started playing.</div>
		<?php elseif ($this->_tpl_vars['status'] == -3): ?>
		<div class="error_message">We're sorry, the room you tried to enter is private.  Make sure you've entered the password correctly.</div>
		<?php elseif ($this->_tpl_vars['status'] == -4): ?>
		<div class="error_message">We're sorry, the room you tried to enter is now full.  Please pick another room.</div>
		<?php elseif ($this->_tpl_vars['status'] == -5): ?>
		<div class="error_message">We're sorry, your identity could not be verified.  Please make sure cookies are activated in your browser.</div>
		<?php endif; ?>

		<div class="apple_table">
			<div class="table_header">
				<span class="th_left"></span>
				<span class="th_right"></span>
				<div class="th_text">Welcome to Apples to AJAX!</div>
			</div>
			<div class="table_body">
				<div style="display:inline;width:100%;">
					<div class="green_form" style="float:left;">
						<div class="create_form_header"><img src="images/create_text.png" height="17" width="154" alt="Create a new game" /></div>
						<form action="create-game.php" method="post" id="create_form">
							<div class="formfield">
								<label for="name">Your name</label>
								<input id="name" type="text" name="name" size="20" maxlength="20" class="text" />
							</div>
							<div class="formfield">
								<label for="room_name">Room name</label>
								<input id="room_name" type="text" name="room_name" size="30" maxlength="100" class="text" />
							</div>
							<div class="formfield">
								<label for="rounds">Rounds</label>
								<input id="rounds" type="text" name="rounds" size="5" value="20" class="text" onBlur="this.value=Math.abs(parseInt(this.value));if(isNaN(this.value) || this.value==0)this.value=20;" />
							</div>
							<div class="formfield">
								<label for="private">Private?</label>
								<select id="private" name="private" style="float:left;margin-left: 1px; margin-top: 4px;">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
								<label for="password">Password</label>
								<input id="password" type="password" name="password" size="12" maxlength="20" class="text" />
							</div>
							<div class="formfield">
								<label for="max_players">Max Players</label>
								<input id="max_players" type="text" name="max_players" size="5" value="12" class="text" onBlur="this.value=Math.abs(parseInt(this.value));if(isNaN(this.value) || this.value==0)this.value=12;" />
							</div>
							<div style="text-align:center;clear:both;">
								<input type="image" name="create" value="Create" src="images/create_button.png" onMouseOver="this.src='/images/create_button_hl.png';" width="77" height="30" onMouseOut="this.src='/images/create_button.png';" />
							</div>
						</form>
					</div>

					<span class="orcontainer">
						<img src="images/or_text.png" alt="or" height="9" width="16" />
					</span>

					<div class="green_form" style="float:right;">
						<div class="join_form_header"><img src="images/join_text.png" height="18" width="173" alt="Join an existing game"></div>
						<form action="join-game.php" method="post" style="margin:5px;text-align:center;" name="joinform">
							<div class="formfield"><label for="join_name">Your name</label>
							<input type="text" id="join_name" name="name" size="20" maxlength="20" class="text" /></div>

							<div>

								<div class="roomlistcontainer">
									<div class="formfield">
										<label for="roomid">Choose a game</label>
										<br style="clear:both;">
										<select id="roomid" name="roomid" size="8" onChange="getPlayerList();">
										<?php $_from = $this->_tpl_vars['roomlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['name']):
?>
											<option value="<?php echo $this->_tpl_vars['id']; ?>
"><?php echo $this->_tpl_vars['name']; ?>
</option>
										<?php endforeach; endif; unset($_from); ?>
										</select>
									</div>

									<div class="formfield">
										<label for="join_password">Password</label>
										<input id="join_password" type="password" name="password" size="10" class="text" />
									</div>
								</div>

								<div class="playerlistcontainer">
									<div class="playerlistheader">
										<span class="playerlistheader_left"></span>
										<span class="playerlistheader_right"></span>
										<div>Player&nbsp;List</div>
									</div>
									<div id="playerlist">
										<div class="message">Please choose a room.</div>
									</div>
									<div class="playerlistbottom">
										<span class="playerlistbottom_left"></span>
										<span class="playerlistbottom_right"></span>
									</div>
								</div>
							</div>

							<div style="text-align:center;clear:both;">
								<input type="image" name="join" value="Join" src="images/join_button.png" width="77" height="30" onMouseOver="this.src='/images/join_button_hl.png';" onMouseOut="this.src='/images/join_button.png';" />
							</div>
						</form>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div class="table_footer">
				<span class="tf_left"></span>
				<span class="tf_right"></span>
			</div>

		</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>