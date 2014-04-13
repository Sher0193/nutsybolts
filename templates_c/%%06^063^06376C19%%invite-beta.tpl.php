<?php /* Smarty version 2.6.18, created on 2011-01-06 11:35:43
         compiled from invite-beta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'invite-beta.tpl', 5, false),array('function', 'cycle', 'invite-beta.tpl', 53, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header-beta.tpl", 'smarty_include_vars' => array('page' => 'invite')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">

<?php $_from = $this->_tpl_vars['players']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pid'] => $this->_tpl_vars['name']):
?>
names[<?php echo $this->_tpl_vars['pid']; ?>
] = '<?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';
<?php endforeach; endif; unset($_from); ?>
<?php $_from = $this->_tpl_vars['colors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pid'] => $this->_tpl_vars['color']):
?>
colors[<?php echo $this->_tpl_vars['pid']; ?>
] = '#<?php echo $this->_tpl_vars['color']; ?>
';
<?php endforeach; endif; unset($_from); ?>
<?php $_from = $this->_tpl_vars['player_ids']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['pid']):
?>
player_ids[<?php echo $this->_tpl_vars['index']; ?>
] = <?php echo $this->_tpl_vars['pid']; ?>
;
<?php endforeach; endif; unset($_from); ?>
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "logo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="game-bar">
	<div class="game-leftbar">
		<div class="block">
			<div style="text-align:center;"><img src="/images/join_invited.png" title="invite" /></div>
			Welcome to <b><?php echo $this->_tpl_vars['room_name']; ?>
</b>!<br/>
			<?php if ($this->_tpl_vars['started']): ?>
			The game has already started, but don't worry, you can still get in on the fun!
			<?php else: ?>
			The game hasn't started yet, so enter your name and get in on the fun!
			<?php endif; ?>
			<form action="/join-game.php" style="padding: 5px;" method="post" name="joinform" onsubmit="return joinValidate();">
				<input type="hidden" name="roomid" value="<?php echo $this->_tpl_vars['room_id']; ?>
" />
				<?php if ($this->_tpl_vars['password']): ?><input type="hidden" name="password" value="<?php echo $this->_tpl_vars['password']; ?>
" /><?php endif; ?>
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
					
					<?php $_from = $this->_tpl_vars['players']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pid'] => $this->_tpl_vars['playername']):
?>
					<tr class="<?php echo smarty_function_cycle(array('values' => "player1,player2"), $this);?>
" id="playerrow<?php echo $this->_tpl_vars['pid']; ?>
">
						<td id="playericon<?php echo $this->_tpl_vars['pid']; ?>
"></td>
						<td width="70%" id="playername<?php echo $this->_tpl_vars['pid']; ?>
"><?php echo $this->_tpl_vars['playername']; ?>
</td>
						<td id="playerscore<?php echo $this->_tpl_vars['pid']; ?>
">0</td>
					</tr>
					<?php endforeach; endif; unset($_from); ?>
				</table>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>-->
	</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer-beta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>