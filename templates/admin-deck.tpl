{include file="header-beta.tpl" page="admin"}

<script type="text/javascript">
{if $did}
var did = {$did};
{/if}
</script>

		{include file="logo.tpl"}
		<div class="shinyborder"></div>

<form action="deckedit.php" method="post">
Choose a deck to edit: <select name="did">
{html_options options=$decks selected=$did}
</select>
<input type="submit" name="edit" value="Edit" />
</form>
{if $did}
<table width="100%">
<tr valign="top">
<td>
<h3>Red Cards</h3>
<p>Insert a new card:
<input type="text" name="newredcard" id="newredcard" />
<input type="button" name="addred" value="Add" onclick="addRedCard(did, $('#newredcard').val());" />
</p>
<ul id="redcardlist">
{foreach from=$redcards key=cid item=cardname}
<li id="redcard{$cid}">{$cardname} ({$cid})<input type="button" style="font-size:9pt;" id="remove{$cid}" value="Delete" onclick="deleteRedCard({$cid});" />
</li>
{/foreach}
</ul>
</td>
<td>
<h3>Green Cards</h3>
<p>Insert a new card:
<input type="text" name="newgreencard" id="newgreencard" />
<input type="button" name="addgreen" value="Add" onclick="addGreenCard(did, $('#newgreencard').val());" />
</p>
<ul id="greencardlist">
{foreach from=$greencards key=cid item=cardname}
<li id="greencard{$cid}">{$cardname} ({$cid})<input type="button" style="font-size:9pt;" id="remove{$cid}" value="Delete" onclick="deleteGreenCard({$cid});" /></li>
{/foreach}
</ul>
</td>
</tr>
</table>
{/if}
{include file="footer-beta.tpl"}