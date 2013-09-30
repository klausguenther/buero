// Daten Eintragen 

function tdFocussed() {
	id = $(this).parent().attr('id');
	spalte = $(this).attr('name');
	tabindex = $(this).attr('tabindex');
	klasse = $(this).attr('class');
	content = $(this).text();
	$(this).css('outline', 'solid 1px');
}

var color=100;

function tdBlurred() {
	color=(color+20);
	$(this).css('outline', 'solid 0px');
	var input = $(this).text();
	if (klasse == 'data datum') {
		datum = input.split('.');
		datum[0] = ('0' + datum[0]).slice(-2);
		datum[1] = ('0' + datum[1]).slice(-2);
		datum[2] = ('20' + datum[2]).slice(-4);
		input = datum[2] + '-' + datum[1] + '-' + datum[0];
	}
	if (klasse == 'data std numeric') {
		std = input.split(':');
		std[1] = std[1] / 60;
		input = parseFloat(std[0]) + parseFloat(std[1]);
	}

	if (input != content) {
		// Zeile ersetzen
		$.getJSON('includes/update.php?id=' + id + '&job_id=' + job_id + '&spalte=' + spalte + '&content='
				+ encodeURIComponent(input), function(data) {
			$.each(data,function(key,val) {
				$('#' + data.id.value).children('td[name="' + key + '"]').html(val.value).addClass(val.proof);
				
			});
			// Summe ersetzen
			$.get('includes/summe_' + tabelle + '.php?job_id=' + job_id, function(summe) {
				$('.summe').replaceWith(summe);
			});
		});
	}
}