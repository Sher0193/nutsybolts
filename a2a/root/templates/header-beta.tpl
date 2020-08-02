{if $page === 'landing' || $page === 'game'}
	{assign var=script value='game'}
{else}
	{assign var=script value='index'}
{/if}
{if $page == 'invite'}{assign var=page value='index'}{/if}
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Nutsy Bolts | {if $room_name}{$room_name}{else}The game of crazy choices{/if}</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="description" content="Nutsy Bolts is an online browser-based word association game.  Make the most hilarious associations between adjectives and nouns to come out on top!" />
	<meta name="keywords" content="game, browser game, free game, multiplayer game" />
	<link href="http://{$hostname}/a2a.css" type="text/css" rel="stylesheet" />
	<link href="http://{$hostname}/a2a_{$script}.css" type="text/css" rel="stylesheet" />
	<link rel="shortcut icon" href="http://{$hostname}/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="http://{$hostname}/favicon.ico" type="image/x-icon" /> 
	<script src="/scripts/ajax_lib.js" type="text/javascript"></script>
	<script src="/scripts/a2a_{$page}{if $PACKED}_packed{/if}.js" type="text/javascript"></script>
	<script src="/scripts/jquery-1.4.3.min.js" type="text/javascript"></script>
	{if $script=='game'}
	<script type="text/javascript">page = '{$page}';</script>
	<script src="/scripts/a2a_logs{if $PACKED}_packed{/if}.js" type="text/javascript"></script>
	<script src="/scripts/jquery.titlealert.min.js" type="text/javascript"></script>
	<script src="/scripts/a2a_sound{if $PACKED}_packed{/if}.js" type="text/javascript"></script>
	{/if}
	{if $script=='index'}
	<script src="/scripts/jquery.blockUI.js" type="text/javascript"></script>
	<link href="http://{$hostname}/jquery-ui-1.8.7.custom.css" type="text/css" rel="stylesheet" />
	{/if}
</head>
<body{if $script=='game'} {*onload="document.message.messagebar.focus();" onclick="document.message.messagebar.focus();"*}{/if}>
	<div id="layout-container">

	<div class="layout-leftbar">
	{if $SHOW_ADS}
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
	{else}
	&nbsp;
	{/if}
	</div>
	<div class="layout-center">
