<div class="block-transparent dialog" id="deckbox" style="text-align: center;">
	<div style="background: #e1f2ff;margin-top: 43px;">
		<div class="header">Make a custom deck</div>
		<div style="margin-bottom:1em;">You can create your own list of nouns just for this game.  Just type in each noun on a separate line.</div>
		
		<textarea name="nouns" onkeyup="updateDeckMessage();" id="textarea-nouns"></textarea>
		
		<div><span id="noun_count"></span>&nbsp;&nbsp;<span id="noun_warning"></span></div>
		<div><span id="unique_warning"></span></div>
		
		<div style="clear: both;"></div>
		
		<div class="button" onclick="closeDeck(0);">Use Standard Deck</div>
		<div class="button" onclick="closeDeck(1);" id="deck_save_button">Save Deck</div>
	</div>
	<div class="bottom-transparent">&nbsp;</div>
</div>

<div class="block-transparent dialog" id="gamedeckbox" style="text-align: center;">
	<div style="background: #e1f2ff;margin-top: 43px;">
		<div class="header">Custom Deck</div>
		<div style="margin-bottom:1em;">Here is the list of nouns for this game:</div>
		
		<div id="gamedeck-nouns">
		
		</div>
		
		<div style="clear: both;"></div>
		
		<div class="button" onclick="closeGameDeck();">Close</div>
	</div>
	<div class="bottom-transparent">&nbsp;</div>
</div>