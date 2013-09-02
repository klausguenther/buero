/* Daten Eintragen */
function tdClicked() {
	var tr = $(this).parent();
	var id = tr.attr('id');
	var spalte = $(this).attr('name');
	var klasse = $(this).attr('class');
	var inhalt = $(this).text();
	
	$(this).unbind("click");
	$(this).blur(function() {
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
		if (inhalt != content) { // Zeile ersetzen
			$.get('includes/update.php?id='+id+'&job_id='+job_id+'&spalte='+spalte+'&content='+encodeURIComponent(content), function(data) {
				var tr_data = data;
				tr.replaceWith(tr_data);
				var tds = $('#'+id).mousedown(ContextMenue).children();
				tds.filter('.data').click(tdClicked);
				tds.filter('.dropdown').click(DropDown);
				$.get('includes/summe_'+tabelle+'.php?job_id='+job_id, function(summe) {
					$('.summe').replaceWith(summe);
				});
			});
		}
	});
}