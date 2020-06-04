<?php /* Smarty version 2.6.18, created on 2009-06-20 14:17:53
         compiled from tutorial.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header-beta.tpl", 'smarty_include_vars' => array('page' => 'tutorial')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "logo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
		<div style="text-align:center;margin-bottom: 1em;"><img src="images/tutorial_text.png" /></div>
		
		<div style="text-align:center;margin-bottom: 1em;">[<a href="/">Return to Nutsy Bolts</a>]</div>
		
		<div id="tutorial_text" style="height:2em;"></div>
			
		<div class="tutorial" style="clear: both;margin-top: 1em;margin-bottom: 1em;">
			<img id="tutorial_image" />
		</div>
		<div style="float:right;padding-right: 10px;"><a href="javascript:next();"><img src="/images/next_button.png" border="0" onmouseover="this.src='/images/next_button_hl.png'" onmouseout="this.src='/images/next_button.png'" /></a></div>
		<div style="float:left;padding-left: 10px;"><a href="javascript:previous();"><img src="/images/prev_button.png" border="0" onmouseover="this.src='/images/prev_button_hl.png'" onmouseout="this.src='/images/prev_button.png'" /></a></div>
		
		<script type="text/javascript">updateSteps();</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer-beta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>