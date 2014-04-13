<?php /* Smarty version 2.6.18, created on 2011-07-08 20:39:12
         compiled from header-beta.tpl */ ?>
<?php if ($this->_tpl_vars['page'] == 'landing' || $this->_tpl_vars['page'] == 'game'): ?>
	<?php $this->assign('script', 'game'); ?>
<?php else: ?>
	<?php $this->assign('script', 'index'); ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['page'] == 'invite'): ?><?php $this->assign('page', 'index'); ?><?php endif; ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="utf-8"<?php echo '?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Nutsy Bolts | <?php if ($this->_tpl_vars['room_name']): ?><?php echo $this->_tpl_vars['room_name']; ?>
<?php else: ?>The game of crazy choices<?php endif; ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="description" content="Nutsy Bolts is an online browser-based word association game.  Make the most hilarious associations between adjectives and nouns to come out on top!" />
	<meta name="keywords" content="game, browser game, free game, multiplayer game" />
	<link href="http://<?php echo $this->_tpl_vars['hostname']; ?>
/a2a.css" type="text/css" rel="stylesheet" />
	<link href="http://<?php echo $this->_tpl_vars['hostname']; ?>
/a2a_<?php echo $this->_tpl_vars['script']; ?>
.css" type="text/css" rel="stylesheet" />
	<link rel="shortcut icon" href="http://<?php echo $this->_tpl_vars['hostname']; ?>
/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="http://<?php echo $this->_tpl_vars['hostname']; ?>
/favicon.ico" type="image/x-icon" /> 
	<script src="/scripts/ajax_lib.js" type="text/javascript"></script>
	<script src="/scripts/a2a_<?php echo $this->_tpl_vars['page']; ?>
<?php if ($this->_tpl_vars['PACKED']): ?>_packed<?php endif; ?>.js" type="text/javascript"></script>
	<script src="/scripts/jquery-1.4.3.min.js" type="text/javascript"></script>
	<?php if ($this->_tpl_vars['script'] == 'game'): ?>
	<script type="text/javascript">page = '<?php echo $this->_tpl_vars['page']; ?>
';</script>
	<script src="/scripts/a2a_logs<?php if ($this->_tpl_vars['PACKED']): ?>_packed<?php endif; ?>.js" type="text/javascript"></script>
	<script src="/scripts/jquery.titlealert.min.js" type="text/javascript"></script>
	<script src="/scripts/a2a_sound<?php if ($this->_tpl_vars['PACKED']): ?>_packed<?php endif; ?>.js" type="text/javascript"></script>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['script'] == 'index'): ?>
	<script src="/scripts/jquery.blockUI.js" type="text/javascript"></script>
	<link href="http://<?php echo $this->_tpl_vars['hostname']; ?>
/jquery-ui-1.8.7.custom.css" type="text/css" rel="stylesheet" />
	<?php endif; ?>
</head>
<body<?php if ($this->_tpl_vars['script'] == 'game'): ?> <?php endif; ?>>
	<div id="layout-container">

	<div class="layout-leftbar">
	<?php if ($this->_tpl_vars['SHOW_ADS']): ?>
	<script type="text/javascript"><!--
google_ad_client = "pub-5036305202960568";
/* Sidebar ads */
google_ad_slot = "3825747992";
google_ad_width = 120;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	<?php else: ?>
	&nbsp;
	<?php endif; ?>
	</div>
	<div class="layout-center">