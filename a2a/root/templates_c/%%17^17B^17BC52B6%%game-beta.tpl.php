<?php /* Smarty version 2.6.18, created on 2011-07-10 20:38:44
         compiled from game-beta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'game-beta.tpl', 100, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header-beta.tpl", 'smarty_include_vars' => array('page' => 'game')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "logo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	
	<audio id="sound-gavel">
		<source src="/sounds/gavel.mp3" />
		<source src="/sounds/gavel.ogg" type="application/ogg" />
	</audio>
	
	<audio id="sound-shuffle">
		<source src="/sounds/shuffle.mp3" />
		<source src="/sounds/shuffle.ogg" type="application/ogg" />
	</audio>	
	
<div class="game-bar">
	<div class="game-leftbar">	
		<div>
			<div class="top_info_bar" id="round_text"></div>
			<div class="top_info_bar" id="greencard"><a id="greencard_link" href="http://en.wiktionary.org/wiki/" target="_blank"></a></div>
			<div class="top_info_bar" id="judge_name"></div>
		</div>
		
		<div id="gameover">
			<div id="gameover-message"></div>
			<div>
				<span style="width: 50%; float: left; display: inline-block;"><a href="/">Go back to the lobby</a></span>
				<span style="width: 50%; float: left; display: inline-block;"><a href="javascript:oneMoreRound();">Just...one...more...round...</a></span>
			</div>	
		</div>
	
				
		<div class="table_bg">
			<div class="header_big">Table</div>
			<div id="table_message"></div>
			<div id="table_cards" class="disabled">
			</div>
			<div style="clear:both;"></div>
		</div>
				
		<div class="hand_bg">
			<div class="header_big">Your Hand</div>
			<div id="hand_message"></div>
			<div id="hand_cards">
				<?php $_from = $this->_tpl_vars['hand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['order'] => $this->_tpl_vars['card_name']):
?>
				<div class="card_container" id="container<?php echo $this->_tpl_vars['order']; ?>
">
					<a class="hand_card" href="javascript:playCard(<?php echo $this->_tpl_vars['order']; ?>
);" id="hand<?php echo $this->_tpl_vars['order']; ?>
" onMouseOver="return true;"><div class="card_caption"><?php echo $this->_tpl_vars['card_name']; ?>
</div></a>
					<div><a href="http://en.wikipedia.org/wiki/<?php echo $this->_tpl_vars['card_name']; ?>
" target="_blank">look it up</a></div>
				</div>
				<?php endforeach; endif; unset($_from); ?>
			</div>
		</div>
	</div>	

	<div class="game-rightbar">
		<div id="skip_block" class="game-message">
			Oh no!  The other players voted to skip your turn.  If you want to chill out and not play, that's cool; but if you want to get back into the game, just play a card next time you have the chance.
		</div>
	
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "chat.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

		<div class="block">
			<div class="header_small">Players</div>
			<div><a href="javascript:showInviteDialog();" id="invite-link">Invite more players</a></div>
			<div class="body">
				<table cellspacing="0" cellpadding="1" width="100%" id="player_table">
					<tr class="playerlistheader" id="playerlistheader">
						<td>&nbsp;</td><td>Name</td><td>Score</td><td>Ignore</td><td></td>
					</tr>
				</table>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>
				
		<div class="block">
			<div class="header_small">Round History</div>
			<div class="body">
				<table cellspacing="0" cellpadding="1" width="100%">
					<tr class="historyheader">
						<td>#</td><td>Winner</td><td>Cards</td>
					</tr>
				</table>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>
	</div>
	
	<div id="invite-dialog">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "invitebox.tpl", 'smarty_include_vars' => array('dialog' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
</div>	
	<script type="text/javascript">
	room_id = <?php echo $this->_tpl_vars['room_id']; ?>
;
	your_pid = <?php echo $this->_tpl_vars['player_id']; ?>
;
	password = '<?php echo $this->_tpl_vars['password']; ?>
';
	global_round_number = <?php echo $this->_tpl_vars['round_number']; ?>
;
	global_max_rounds = <?php echo $this->_tpl_vars['max_rounds']; ?>
;

	log_id = <?php echo $this->_tpl_vars['log_id']; ?>
;

	<?php $_from = $this->_tpl_vars['players']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pid'] => $this->_tpl_vars['player']):
?>
	playerAdded({'id':<?php echo $this->_tpl_vars['pid']; ?>
, 'name':'<?php echo ((is_array($_tmp=$this->_tpl_vars['player']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
', 'color':'<?php echo $this->_tpl_vars['player']['color']; ?>
', 'ignores':<?php echo $this->_tpl_vars['player']['ignored']; ?>
, 'score':<?php echo $this->_tpl_vars['player']['score']; ?>
, 'j':<?php echo $this->_tpl_vars['player']['judge']; ?>
, 'start': 1});
	<?php if ($this->_tpl_vars['player']['idle']): ?>
	playerIdle(<?php echo $this->_tpl_vars['pid']; ?>
);
	<?php endif; ?>
	<?php if ($this->_tpl_vars['player']['skipped']): ?>
	playerSkip({'p':<?php echo $this->_tpl_vars['pid']; ?>
});
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	refreshTableStripes();
	
	<?php $_from = $this->_tpl_vars['deleted_players']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pid'] => $this->_tpl_vars['player']):
?>
	deletedPlayerAdded({'id':<?php echo $this->_tpl_vars['pid']; ?>
,'name':'<?php echo ((is_array($_tmp=$this->_tpl_vars['player']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'});
	<?php endforeach; endif; unset($_from); ?>

	<?php $_from = $this->_tpl_vars['history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['historyitem']):
?>
	addToHistory(<?php echo $this->_tpl_vars['historyitem']['round']; ?>
, <?php echo $this->_tpl_vars['historyitem']['winner']; ?>
, '<?php echo ((is_array($_tmp=$this->_tpl_vars['historyitem']['adjective'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
', '<?php echo ((is_array($_tmp=$this->_tpl_vars['historyitem']['noun'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
');
	<?php endforeach; endif; unset($_from); ?>

		
	<?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['message']):
?>
	addMessage({'p':<?php echo $this->_tpl_vars['message']['pid']; ?>
,'msg':'<?php echo ((is_array($_tmp=$this->_tpl_vars['message']['text'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'});
	<?php endforeach; endif; unset($_from); ?>

	<?php $_from = $this->_tpl_vars['played_cards']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pid'] => $this->_tpl_vars['name']):
?>
	addCardToTable({'p':<?php echo $this->_tpl_vars['pid']; ?>
,'n':'<?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'});
	<?php endforeach; endif; unset($_from); ?>

	updateGreenCard('<?php echo $this->_tpl_vars['green_card']; ?>
');
	updateJudge(<?php echo $this->_tpl_vars['current_judge']; ?>
);

	<?php if ($this->_tpl_vars['played_flag'] || $this->_tpl_vars['is_judge']): ?>
	deactivateHand();
	<?php else: ?>
	activateHand();
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['phase'] == 2): ?>
	switchToJudge();
	<?php endif; ?>

	creator_flag = <?php if ($this->_tpl_vars['creator_flag']): ?>1<?php else: ?>0<?php endif; ?>;
	<?php if ($this->_tpl_vars['creator_flag']): ?>activateCreator();<?php endif; ?>
	
	<?php $_from = $this->_tpl_vars['hand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['order'] => $this->_tpl_vars['card_name']):
?>
	your_hand[<?php echo $this->_tpl_vars['order']; ?>
] = '<?php echo ((is_array($_tmp=$this->_tpl_vars['card_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';
	<?php endforeach; endif; unset($_from); ?>
	
	updateRoundText();
	<?php if ($this->_tpl_vars['round_number'] > $this->_tpl_vars['max_rounds']): ?>
	gameOver();
	<?php endif; ?>
	
	initLogHandlers(<?php echo $this->_tpl_vars['room_id']; ?>
);
	getLogs();
				
	</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer-beta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>