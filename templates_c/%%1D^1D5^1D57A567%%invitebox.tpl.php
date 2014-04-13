<?php /* Smarty version 2.6.18, created on 2011-02-17 20:19:52
         compiled from invitebox.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'invitebox.tpl', 8, false),)), $this); ?>
<div class="block<?php if ($this->_tpl_vars['dialog'] == 1): ?>-transparent dialog<?php endif; ?>" id="invitebox" style="text-align: center;">
	<?php if ($this->_tpl_vars['dialog'] == 1): ?><div style="background: #e1f2ff;margin-top: 43px;"><?php endif; ?>
	<div class="header_big">Share this game</div>
	<div style="margin-bottom:1em;">Nutsy Bolts is more fun with friends!</div>
	
	<div style="float: right; padding: 0px 20px;">
		<div style="text-align:center;">Or share it online:</div>
		<div style="margin: 2px;"><a href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fnutsybolts.com%2Finvite.php%2F<?php echo $this->_tpl_vars['room_id']; ?>
<?php if ($this->_tpl_vars['room_pw']): ?>%2F<?php echo $this->_tpl_vars['room_pw']; ?>
<?php endif; ?>&t=Nutsy%20Bolts%20%7C%20<?php echo ((is_array($_tmp=$this->_tpl_vars['room_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" target="_blank"><img src="/images/facebook.png" border="0" /></a></div>
		<div style="margin: 2px;"><a href="http://www.twitter.com/home?status=Playing+Nutsy+Bolts,+come+join+me!++http://nutsybolts.com/invite.php/<?php echo $this->_tpl_vars['room_id']; ?>
<?php if ($this->_tpl_vars['room_pw']): ?>/<?php echo $this->_tpl_vars['room_pw']; ?>
<?php endif; ?>" target="_blank"><img src="/images/twitter.png" border="0" /></a></div>
	</div>
	
	<div style="padding: 0px 20px;">
		<div>Send out this link:</div>
		<b>www.nutsybolts.com/invite.php/<?php echo $this->_tpl_vars['room_id']; ?>
<?php if ($this->_tpl_vars['room_pw']): ?>/<?php echo $this->_tpl_vars['room_pw']; ?>
<?php endif; ?></b>
	</div>
	
	<div style="clear: both;"></div>
	
	<?php if ($this->_tpl_vars['dialog'] == 1): ?>
	<input type="image" value="Close" src="/images/close_button.png" onmouseover="this.src='/images/close_button_hl.png'" onmouseout="this.src='/images/close_button.png'" onclick="hideInviteDialog();" />
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['dialog'] == 0): ?><div class="bottom">&nbsp;<?php endif; ?></div>
	<?php if ($this->_tpl_vars['dialog'] == 1): ?><div class="bottom-transparent">&nbsp;</div><?php endif; ?>
</div>