		<div id="offertorefresh" class="game-message">Did your connection just drop?  <a href='javascript:restoreConnection();'>Restore it!</a></div>
		<div id="database_error" class="game-message">Oh dear.  Nutsy Bolts is experiencing really heavy traffic right now, and as such the site has just decided to go take a nap.  We hope you'll come back once it's feeling better.</div>
		<div class="block">
			<div class="header_small">Chat</div>
			<div class="body">
				<div id="chatwindow"></div>
				<form name="message" onsubmit="return postMessage();" style="margin: 0px; padding:0px;">
					<table width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%"><input type="text" name="messagebar" maxlength="200" class="messagebar" style="width: 100%;" autocomplete="off" /></td>
						<td width="0%"><input type="submit" name="send" value="send" style="font-size:8pt;"/></td>
					</tr>
					</table>
					<label for="mutesounds">Turn sounds off</label><input type="checkbox" name="mutesounds" id="mutesounds" />
				</form>
			</div>
			<div id="message-autoscroll" style="visibility:hidden;">To turn autoscroll back on, just scroll the window to the bottom or <a href="javascript:scrollChatWindow();">click here</a>.</div>
			<div class="bottom">&nbsp;</div>
		</div>
		<script type="text/javascript">
			$('#chatwindow').scroll(displayScrollMessage);
		</script>
		