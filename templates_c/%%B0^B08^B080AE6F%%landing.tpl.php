<?php /* Smarty version 2.6.18, created on 2007-12-15 17:23:47
         compiled from landing.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'landing.tpl', 46, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('page_name' => 'landing')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
room_id = <?php echo $this->_tpl_vars['room_id']; ?>
;
pid = <?php echo $this->_tpl_vars['player_id']; ?>
;
password = '<?php echo $this->_tpl_vars['password']; ?>
';

<?php if (! $this->_tpl_vars['creator_flag']): ?>
/*window.setTimeout('checkStatus()', 5000);*/
checkStatus();
<?php endif; ?>
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
				<?php $_from = $this->_tpl_vars['players']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pid'] => $this->_tpl_vars['playername']):
?>
				<div class="<?php echo smarty_function_cycle(array('values' => "player1,player2"), $this);?>
"<?php if ($this->_tpl_vars['pid'] == $this->_tpl_vars['player_id']): ?> id="you"<?php endif; ?>>
					<div class="playericon"></div>
					<div class="playername"><?php echo $this->_tpl_vars['playername']; ?>
</div>
					<div class="playerscore">0</div>
					<div class="playermute"><img id="mutebulb<?php echo $this->_tpl_vars['pid']; ?>
" src="/images/mutebulb_on.png" onClick="mute(<?php echo $this->_tpl_vars['pid']; ?>
);" /></div>
				</div>
				<?php endforeach; endif; unset($_from); ?>
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
		<?php if ($this->_tpl_vars['creator_flag']): ?>
		<p>As the creator of this room, you get to start off the game once everyone's ready.  Just click the button below when you're all set.</p>
		<form action="/start-game.php" method="post">
			<input type="hidden" name="room_id" value="<?php echo $this->_tpl_vars['room_id']; ?>
" />
			<input type="hidden" name="player_id" value="<?php echo $this->_tpl_vars['player_id']; ?>
" />
			<input type="hidden" name="password" value="<?php echo $this->_tpl_vars['password']; ?>
" />
			<input type="image" name="start" value="Start!" src="/images/start_button.png" height="30" width="77" onMouseOver="this.src='/images/start_button_hl.png';" onMouseOut="this.src='/images/start_button.png';" />
		</form>
		<?php else: ?>
		<p>Just hang tight and wait for the room's creator to start things off!</p>
		<?php endif; ?>
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