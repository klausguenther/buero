/* Dropdown Menue */
function DropDown() {
	event.stopPropagation();
	$(this).unbind("click");
	var content = $(this).text();
	var offset = $(this).offset();
	var width = $(this).width();
	$(this).width(width);
	$(this).html('<input type="text" style="width:' + width + 'px;" value="' + content + '"/>');
	$.get('includes/dropdown.php', function(dropdown) {
		$('table').before(dropdown);
		$('.dropdown').css('top', (offset.top + 26));
		$('.dropdown').css('left', (offset.left - 1));
		$('.dropdown ul').css('width', (width + 3));
		$('div.dropdown>ul>.dropdown').click(PutInput);
	});
	var input = $(this).children().first();

	function PutInput() { // wird in DropDown() benutzt
		var tr = input.parent().parent();
		var id = tr.attr('id');
		var spalte = input.parent().attr('name');
		var content = $(this).text();
		// Zeile ersetzen
		$.ajax({
			url : 'includes/update.php?id=' + id + '&job_id=' + job_id + '&spalte=' + spalte + '&content='
					+ encodeURIComponent(content),
			success : function(data) {
				$tr_data = data;
				$sum_data = data;
				tr.replaceWith($tr_data);
				// $(tr).mousedown(ContextMenue);
			},
			async : false
		});
	}
}

/* Timer */
function ToggleTimer() {
	var tr = $(this).parent().parent();
	var id = tr.attr('id');
	$.get('includes/timer.php?id=' + id + '&jobs_positionen_id=' + job_id, function() {
		window.location.reload();
	});
}

/* Rechnung drucken */
function PrintRcn(id) {
	if (job_id > 0) {
		window.location.href = 'rechnung.php?job_id=' + job_id;
	} else {
		window.location.href = 'rechnung.php?job_id=' + id;
	}
}

/* Init */
$(document).ready(function() {
	$('.data_row').mousedown(ContextMenue); // Kontext Menue
	$('.dropdown').click(DropDown); // Dropdown Menue

	$('[contenteditable]').blur(tdBlurred);
	$('[contenteditable]').focus(tdFocussed);

	RotateClock();
});
