/* Daten loeschen */
function DeleteRow(id) {
	$.get('includes/delete.php?id=' + id, function() {
		$('#' + id).remove();
		$('.confirmbox').remove();
		if (tabelle == 'jobs') { // Jobliste Summe ersetzen
			$.get('includes/summe_jobs.php', function(summe) {
				$tfoot_summe_data = summe;
				$('.summe').replaceWith($tfoot_summe_data);
			});
		}
		if (tabelle == 'jobs_positionen') { // Jobzettel Summe ersetzen
			$.get('includes/summe_jobs_positionen.php?job_id=' + job_id, function(summe) {
				$tfoot_summe_data = summe;
				$('.summe').replaceWith($tfoot_summe_data);
			});
		}
		if (tabelle == 'jobs_stunden') { // Zeiterfassung Summe Stunden ersetzen
			$.get('includes/summe_jobs_stunden.php?jobs_positionen_id=' + job_id, function(summe) {
				$tfoot_summe_data = summe;
				$('.summe').replaceWith($tfoot_summe_data);
			});
		}
		if (tabelle == 'ausgaben') { // Ausgaben Summe ersetzen
			$.get('includes/summe_ausgaben.php', function(summe) {
				$tfoot_summe_data = summe;
				$('.summe').replaceWith($tfoot_summe_data);
			});
		}
	});
}

function DeleteZeiterfassung(jobs_positionen_id, gesamtstunden) {
	$.get('includes/delete_zeiterfassung.php?id=' + jobs_positionen_id + '&gesamtstunden=' + gesamtstunden, function() {
		jobzettel = $('div.arrow a').attr('href');
		window.location.href = jobzettel;
	});
}