<?php /* Smarty version 2.6.18, created on 2008-09-01 20:36:44
         compiled from admin-deck.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'admin-deck.tpl', 14, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header-beta.tpl", 'smarty_include_vars' => array('page' => 'admin')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<?php if ($this->_tpl_vars['did']): ?>
var did = <?php echo $this->_tpl_vars['did']; ?>
;
<?php endif; ?>
</script>

		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "logo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<div class="shinyborder"></div>

<form action="deckedit.php" method="post">
Choose a deck to edit: <select name="did">
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['decks'],'selected' => $this->_tpl_vars['did']), $this);?>

</select>
<input type="submit" name="edit" value="Edit" />
</form>
<?php if ($this->_tpl_vars['did']): ?>
<table width="100%">
<tr valign="top">
<td>
<h3>Red Cards</h3>
<p>Insert a new card:
<input type="text" name="newredcard" id="newredcard" />
<input type="button" name="addred" value="Add" onclick="addRedCard(did, $('#newredcard').val());" />
</p>
<ul id="redcardlist">
<?php $_from = $this->_tpl_vars['redcards']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cid'] => $this->_tpl_vars['cardname']):
?>
<li id="redcard<?php echo $this->_tpl_vars['cid']; ?>
"><?php echo $this->_tpl_vars['cardname']; ?>
 (<?php echo $this->_tpl_vars['cid']; ?>
)<input type="button" style="font-size:9pt;" id="remove<?php echo $this->_tpl_vars['cid']; ?>
" value="Delete" onclick="deleteRedCard(<?php echo $this->_tpl_vars['cid']; ?>
);" />
</li>
<?php endforeach; endif; unset($_from); ?>
</ul>
</td>
<td>
<h3>Green Cards</h3>
<p>Insert a new card:
<input type="text" name="newgreencard" id="newgreencard" />
<input type="button" name="addgreen" value="Add" onclick="addGreenCard(did, $('#newgreencard').val());" />
</p>
<ul id="greencardlist">
<?php $_from = $this->_tpl_vars['greencards']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cid'] => $this->_tpl_vars['cardname']):
?>
<li id="greencard<?php echo $this->_tpl_vars['cid']; ?>
"><?php echo $this->_tpl_vars['cardname']; ?>
 (<?php echo $this->_tpl_vars['cid']; ?>
)<input type="button" style="font-size:9pt;" id="remove<?php echo $this->_tpl_vars['cid']; ?>
" value="Delete" onclick="deleteGreenCard(<?php echo $this->_tpl_vars['cid']; ?>
);" /></li>
<?php endforeach; endif; unset($_from); ?>
</ul>
</td>
</tr>
</table>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer-beta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>