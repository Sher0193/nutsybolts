function playSound(sound) {
	if ($('#mutesounds').attr('checked'))
		return;
	
	var audio = document.getElementById('sound-' + sound);
	if (audio && audio.play) {
		audio.play();
	}
}
