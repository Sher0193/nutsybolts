function AdminAJAXRequest(op, query, handler) {
	query.l = 1;
	AJAXRequest(op, query, handler);
}

function addRedCard(did, name) {
	var q = new Query();
	q.name = name;
	q.did = did;
	$('#newredcard').attr('disabled', 'disabled');
	AdminAJAXRequest('add_red', q, addRedCardHandler);
}

function addRedCardHandler(response) {
	var cardname = $('#newredcard').val();
	$('#redcardlist li').each(function (){
		if ($(this).text().toLowerCase() > cardname.toLowerCase())
			return true;
			
		if ($(this).next() && $(this).next().text().toLowerCase() < cardname.toLowerCase())
			return true;
		
		$('<li id="redcard' + response + '">' + cardname + ' (' + response + ')<input type="button" style="font-size:9pt;" id="remove' + response + '" value="Delete" onclick="deleteRedCard(' + response + ');" /></li>').
			insertAfter(this).
			slideDown('slow');
		
		return false; 
	});
	$('#newredcard').removeAttr('disabled').val('');	
}

function deleteRedCard(cid) {
	var q = new Query();
	q.cid = cid;
	AdminAJAXRequest('delete_red', q, deleteRedCardHandler);
}

function deleteRedCardHandler(response) {
	if (!response) return;
	$('#redcard' + response).slideUp('slow');
}

function addGreenCard(did, name) {
	var q = new Query();
	q.name = name;
	q.did = did;
	$('#newgreencard').attr('disabled', 'disabled');
	AdminAJAXRequest('add_green', q, addGreenCardHandler);
}

function addGreenCardHandler(response) {
	var cardname = $('#newgreencard').val();
	$('#greencardlist li').each(function (){
		if ($(this).text() > cardname)
			return true;
			
		if ($(this).next() && $(this).next().text() < cardname)
			return true;
		
		$('<li id="greencard' + response + '">' + cardname + ' (' + response + ')<input type="button" style="font-size:9pt;" id="remove' + response + '" value="Delete" onclick="deleteGreenCard(' + response + ');" /></li>').
			slideUp().
			insertAfter(this).
			slideDown('slow');
		
		return false; 
	});
	$('#newgreencard').removeAttr('disabled').val('');	
}

function deleteGreenCard(cid) {
	var q = new Query();
	q.cid = cid;
	AdminAJAXRequest('delete_green', q, deleteGreenCardHandler);
}

function deleteGreenCardHandler(response) {
	if (!response) return;
	$('#greencard' + response).slideUp('slow');
}