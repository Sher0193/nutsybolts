var room_id;
var your_pid;
var password;

// error flag constants
var RESEND = -1;
var PARTIAL = -2;
var FAIL = -3;

function AJAXRequest(op, query, handler) {
	var url = "/ajax_handler.php?op=" + op;
	query.room = room_id;
	query.pid = your_pid;
	query.pw = password;

	$.post(url, query, handler, "json");
}