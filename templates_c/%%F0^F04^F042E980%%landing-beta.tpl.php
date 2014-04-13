<?php /* Smarty version 2.6.18, created on 2011-07-02 12:19:28
         compiled from landing-beta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'landing-beta.tpl', 61, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header-beta.tpl", 'smarty_include_vars' => array('page' => 'landing')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "logo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="game-bar">
	<div class="game-leftbar">
		<div class="helpbar">
			Welcome to <b><?php echo $this->_tpl_vars['room_name']; ?>
</b>!  This is a landing area where you can hang out and chat.<br/>
			<div id="message-gamestart">

			<span id="creator_name"><?php echo $this->_tpl_vars['creator']; ?>
</span> will start the game when everyone's ready.
 
 			</div>
			<form action="/start-game.php" method="post" name="startform" onsubmit="game_started=1;">
				<input type="hidden" name="room_id" value="<?php echo $this->_tpl_vars['room_id']; ?>
" />
				<input type="hidden" name="player_id" value="<?php echo $this->_tpl_vars['player_id']; ?>
" />
				<input type="hidden" name="password" value="<?php echo $this->_tpl_vars['password']; ?>
" />
				<input type="image" id="start_button" src="/images/start_button.png" width="79" height="30" name="start" value="Start" style="font-size: 8pt;float: right;margin: 5px;display:none;" disabled="true"
					onmouseover="this.src='/images/start_button_hl.png';" onmouseout="this.src='/images/start_button.png';" />		
			</form>
		</div>
		
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "invitebox.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
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
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "chat.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
		<div class="block">
			<div class="header_small">Players</div>
			<div class="body">
		
				<table cellspacing="0" cellpadding="1" width="100%" id="player_table">
					<tr class="playerlistheader">
						<td>Status</td><td>Name</td><td>Score</td><td>Ignore</td><?php if ($this->_tpl_vars['creator_flag']): ?><td>Remove player?</td><?php endif; ?>
					</tr>
				</table>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>
	</div>
</div>
<script type="text/javascript">

room_id = <?php echo $this->_tpl_vars['room_id']; ?>
;
your_pid = <?php echo $this->_tpl_vars['player_id']; ?>
;
password = '<?php echo $this->_tpl_vars['password']; ?>
';
room_password = '<?php echo $this->_tpl_vars['room_pw']; ?>
';
hash = '<?php echo ((is_array($_tmp=$this->_tpl_vars['hash'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';
creator_flag = <?php if ($this->_tpl_vars['creator_flag']): ?>1<?php else: ?>0<?php endif; ?>;
PUSHER_KEY = '<?php echo $this->_tpl_vars['pusher_key']; ?>
';

if (creator_flag) activateCreator();

<?php $_from = $this->_tpl_vars['players']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pid'] => $this->_tpl_vars['player']):
?>
playerAdded({'id':<?php echo $this->_tpl_vars['pid']; ?>
, 'name':'<?php echo ((is_array($_tmp=$this->_tpl_vars['player']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
', 'color':'<?php echo $this->_tpl_vars['player']['color']; ?>
', 'ignores':<?php echo $this->_tpl_vars['player']['ignored']; ?>
});
<?php if ($this->_tpl_vars['player']['idle']): ?>
playerIdle(<?php echo $this->_tpl_vars['pid']; ?>
);
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

<?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['message']):
?>
addMessage({'p':<?php echo $this->_tpl_vars['message']['pid']; ?>
,'msg':'<?php echo ((is_array($_tmp=$this->_tpl_vars['message']['text'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'});
<?php endforeach; endif; unset($_from); ?>

log_id = <?php if ($this->_tpl_vars['log_id']): ?><?php echo $this->_tpl_vars['log_id']; ?>
<?php else: ?>0<?php endif; ?>;
initLogHandlers(<?php echo $this->_tpl_vars['room_id']; ?>
);
getLogs();
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer-beta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>