<div class="block{if $dialog==1}-transparent dialog{/if}" id="invitebox" style="text-align: center;">
	{if $dialog==1}<div style="background: #e1f2ff;margin-top: 43px;">{/if}
	<div class="header_big">Share this game</div>
	<div style="margin-bottom:1em;">Nutsy Bolts is more fun with friends!</div>
	
	<div style="float: right; padding: 0px 20px;">
		<div style="text-align:center;">Or share it online:</div>
		<div style="margin: 2px;"><a href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fnutsybolts.com%2Finvite.php%2F{$room_id}{if $room_pw}%2F{$room_pw}{/if}&t=Nutsy%20Bolts%20%7C%20{$room_name|escape:'url'}" target="_blank"><img src="/images/facebook.png" border="0" /></a></div>
		<div style="margin: 2px;"><a href="http://www.twitter.com/home?status=Playing+Nutsy+Bolts,+come+join+me!++http://nutsybolts.com/invite.php/{$room_id}{if $room_pw}/{$room_pw}{/if}" target="_blank"><img src="/images/twitter.png" border="0" /></a></div>
	</div>
	
	<div style="padding: 0px 20px;">
		<div>Send out this link:</div>
		<b>www.nutsybolts.com/invite.php/{$room_id}{if $room_pw}/{$room_pw}{/if}</b>
	</div>
	
	<div style="clear: both;"></div>
	
	{if $dialog==1}
	<input type="image" value="Close" src="/images/close_button.png" onmouseover="this.src='/images/close_button_hl.png'" onmouseout="this.src='/images/close_button.png'" onclick="hideInviteDialog();" />
	{/if}
	
	{if $dialog==0}<div class="bottom">&nbsp;{/if}</div>
	{if $dialog==1}<div class="bottom-transparent">&nbsp;</div>{/if}
</div>
