/* Daten Eintragen 
function tdClicked() {
	$(this).unbind("click");
	var inhalt = $(this).text();
	
	// Naechstes Feld mit Tabulatortaste
	var n = -1;
	var tabbedOut = false;
	$(this).keydown(function(e) {
		if (e.keyCode == 9) {
			tabbedOut = true;
			var editedElement = this;
			$(this).parent().children().filter('[contenteditable]').each(function(index,element) {
				if (editedElement == element) {
					n = index;
				}
			});
		}
	});

	$(this).css('outline','solid 1px');
	
	$(this).blur(function() {
		$(this).unbind('blur keydown').css('outline','solid 0px');
	});

	$('[contenteditable]').blur(
			function() {
				var tr = $(this).parent();
				var id = tr.attr('id');
				var spalte = $(this).attr('name');
				var klasse = $(this).attr('class');
				var content = $(this).text();
				if (klasse == 'data datum') {
					datum = content.split('.');
					datum[0] = ('0' + datum[0]).slice(-2);
					datum[1] = ('0' + datum[1]).slice(-2);
					datum[2] = ('20' + datum[2]).slice(-4);
					content = datum[2] + '-' + datum[1] + '-' + datum[0];
				}
				if (klasse == 'data std numeric') {
					std = content.split(':');
					std[1] = std[1] / 60;
					content = parseFloat(std[0]) + parseFloat(std[1]);
				}
				
				if (inhalt != content) {
				// Zeile ersetzen
				$.get('includes/update.php?id='+id+'&job_id='+job_id+'&spalte='+spalte+'&content='+encodeURIComponent(content), function(data) {
					var tr_data = data;
					tr.replaceWith(tr_data);
					var tds = $('#'+id).mousedown(ContextMenue).children();
					tds.filter('.data').click(tdClicked);
					tds.filter('.dropdown').click(DropDown);
					$.get('includes/summe_'+tabelle+'.php?job_id='+job_id, function(summe) {
						$('.summe').replaceWith(summe);
					});
					if (tabbedOut) {
						tds.filter('[contenteditable]').each(function(index) {
							if (index == n + 1) {
								$(this).focus().trigger("click");
							}
						});
					}
				});
				}
				
			});
} */

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
	$('[contenteditable]').click(tdClicked); // erzeugt Eingabefeld
	$('.dropdown').click(DropDown); // Dropdown Menue

	RotateClock();
});

// TODO Bei Tab Focus auf nächsten Eintrag
// TODO unbind/bind aufraeumen (tdClicked/DropDown/ContextMenue)
