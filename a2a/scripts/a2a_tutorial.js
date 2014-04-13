var step = 0;

var steps = new Array();

steps.push({
	'text': 'Welcome to Nutsy Bolts, the game of crazy comparisons!  This short playthrough should tell you all you need to know to play the game.',
	'src': 'step1.png'
});
steps.push({
	'text': ' The basic idea is that you pick a noun to go along with a given adjective, and then your friends pick which one they like the best - we\'ll get more into details later.',
	'src': 'step1.png'
});
steps.push({
	'text': 'To begin playing, you can join any game down here...',
	'src': 'step2.png'
});
steps.push({
	'text': 'or if you don\'t see one you like, you can make your own here.',
	'src': 'step3.png'
});
steps.push({
	'text': 'Once you begin a game, you go to a landing area to wait for all the players to be ready.  No one\'s here yet, so let\'s take a moment to go over the interface.',
	'src': 'step4.png'
});
steps.push({
	'text': 'This is the chat area, where you can talk with everyone else in the room.',
	'src': 'step5.png'
});
steps.push({
	'text': 'You can see who else is in the room with you on the player list here.',
	'src': 'step6.png'
});
steps.push({
	'text': 'Everyone\'s ready now, so let\'s start the game!',
	'src': 'step7.png'
});
steps.push({
	'text': 'The game is ready to go!  There are a few minor points to go over before we start.',
	'src': 'step8.png'
});
steps.push({
	'text': 'Notice that the player list is reorganized in the order that everyone will be the judge.  Also see the gavel next to Bill\'s name - he\'s the judge this turn.  We\'ll get to what being the judge means in a second.',
	'src': 'step9.png'
});
steps.push({
	'text': 'You also have a hand of seven cards.  These are nouns that you can pick to play.  Right now, your cards are Hangnails, Michelle Pfeiffer, Homer Simpson, Amputations, The New York Yankees, Shania Twain, and Going to School.',
	'src': 'step10.png'
});
steps.push({
	'text': 'Finally, the adjective for this round is "Normal", as you can see up here.',
	'src': 'step11.png'
});
steps.push({
	'text': 'The object of the game is to pick a noun that goes with this round\'s adjective, Normal.  Which of the nouns in your hand would Bill think is the most normal?  Let\'s say, Going to School.',
	'src': 'step8.png'
});
steps.push({
	'text': 'To play a card, click on it and it will move to the table up here.  You\'ll get a new card too - Rosie O\'Donnell.',
	'src': 'step13.png'
});
steps.push({
	'text': 'Jeff and Bob played Freckles and Garth Brooks, but you can\'t tell who played which.  Everyone has played now, so Bill gets to choose the winner.',
	'src': 'step14.png'
});
steps.push({
	'text': 'You won, congrats!  The table now shows who played each card and highlights the winner, and the player list shows you have earned one point.',
	'src': 'step15.png'
});
steps.push({
	'text': 'You can also see the history of all winners down here.',
	'src': 'step15b.png'
});
steps.push({
	'text': 'Now you are the judge, and the adjective for this round is "Sappy".  Nothing to do yet, just wait for everyone else to play.',
	'src': 'step16.png'
});
steps.push({
	'text': 'Jeff, Bob and Bill have played Julia Roberts, Romeo and Juliet, and Steven Spielberg, but again you don\'t know who played which card.  Time for you to pick the winner!',
	'src': 'step17.png'
});
steps.push({
	'text': 'Let\'s say you pick Steven Spielberg.  Bill played that card so he gets a point, and now the game continues to round 3.',
	'src': 'step18.png'
});
steps.push({
	'text': 'That\'s all, so feel free to go play the real game!',
	'src': 'step18.png'
});

for (var s in steps) {
	steps[s].image = new Image();
	steps[s].image.src = '/images/tutorial/' + steps[s].src;
}

function previous() {
	if (step > 0)
		step--;
		
	updateSteps();
}

function next() {
	if (step < steps.length - 1)
		step++;
	
	updateSteps();
}

function setStep(new_step) {
	step = new_step;
	updateSteps();
}

function updateSteps() {
	/*if (old_step && old_step != step) {
	//	$('#step' + old_step).hide();
	//	$('#link' + old_step).removeClass('current_step');
	}*/
	
	//$('#step' + step).show();
	//$('#link' + step).addClass('current_step');
	
	//old_step = step;
	
	$('#tutorial_text').text(steps[step].text);
	document.getElementById('tutorial_image').src = steps[step].image.src;
	//$('#tutorial_image').attr('src', '/images/tutorial/' + steps[step].src);
	if (steps[step].callback) {
		steps[step].callback();
	}
}

function testCallback() {
	alert('Callback works!');
}