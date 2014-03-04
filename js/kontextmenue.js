/* Kontextmenue */
function ContextMenue(e){
	document.oncontextmenu = function() {return false;};
	
    if( e.button == 2 || e.ctrlKey) {
    	var id = $(this).attr('id');
    	
    	if (tabelle == 'kunden') {
			title = $(this).children().eq(1).text();
			if (title == '') {
				title = 'Markierten Kunden';
			} else {
				title = 'Kunde <i>'+ title +'</i>';	
				}
		}
    	if (tabelle == 'jobs') {
			title = $(this).children().eq(1).text();
			title += ' ' + $(this).children().eq(2).text();
			if (title == '') {
				title = 'Markierte Rechnung';
			} else {
				title = 'Rechnung <i>'+title+'</i>';	
				}
		}
		if (tabelle == 'jobs_positionen') {
			rechnung = '<i>' + $('h1').text().replace(/Jobzettel /, "") + '</i>';
			title = $(this).children().eq(1).text();
			if (title == '') {
				title = 'Markierte Position';
			} else {
				title = 'Position <i>'+title+'</i>';	
				}
		}
		
		if (tabelle == 'jobs_stunden') {
			zeiterfassung = 'Zeiterfassung <i>'+$('h1').text().replace(/Zeiterfassung /, "")+'</i>';
			start_datum = $(this).children().eq(1).text();
			start_zeit = $(this).children().eq(2).text();
			if (start_datum == '') {
				eintrag = 'Markierten Eintrag';
			} else {
				eintrag = 'Eintrag vom  <i>'+start_datum;
				if (start_zeit == '') {eintrag += '</i>';} else {eintrag += ', '+start_zeit+'</i>';}
			}
		}
		
		if (tabelle == 'ausgaben') {
			title = $(this).children().eq(1).text() + ' ' + $(this).children().eq(2).text();
			if (title == ' ') {
				title = 'Markierte Ausgabe';
			} else {
				title = 'Ausgabe <i>'+ title +'</i>';	
				}
		}
		
		var menue = '<ul>';
		if (tabelle == 'kunden') {
			menue += '<li><a href="javascript:ConfirmDelete(' + id + ', \'' + title + '\')">' + title + ' l&ouml;schen</a></li>';
		}
		if (tabelle == 'jobs') {
			menue += '<li><a href="javascript:PrintRcn(' + id + ')">' + title + ' drucken</a></li>';
			menue += '<li><a href="javascript:ConfirmDelete(' + id + ', \'' + title + '\')">' + title + ' l&ouml;schen</a></li>';
		}
		if (tabelle == 'jobs_positionen') {
			menue += '<li><a href="javascript:PrintRcn(' + id + ')">Rechnung ' + rechnung + ' drucken</a></li>';
			menue += '<li><a href="javascript:ConfirmDelete(' + id + ', \'' + title + '\')">' + title + ' l&ouml;schen</a></li>';
		}
		if (tabelle == 'jobs_stunden') {
			menue += '<li><a href="javascript:ConfirmDelete(' + id + ', \'' + eintrag + '\')">' + eintrag + ' l&ouml;schen</a></li>';
			menue += '<li><a href="javascript:ConfirmDeleteZeiterfassung(' + id + ', \'' + zeiterfassung + '\')">' + zeiterfassung + ' &uuml;bertragen/l&ouml;schen</a></li>';
		}
		if (tabelle == 'ausgaben') {
			menue += '<li><a href="javascript:ConfirmDelete(' + id + ', \'' + title + '\')">' + title + ' l&ouml;schen</a></li>';
		}
		menue += '</ul>';
    	$(this).addClass('selected');
        $('table').before('<div class="contextmenue" style="left:' + (e.pageX - 3) + 'px; top:' + (e.pageY - 3) + 'px">' + menue +'</div>');
    } else {
    	return true;
    }
    
    $('.contextmenue').mouseleave(function () {
		$('.contextmenue').remove();
		$('tr').removeClass('selected');
	});
}

/* Confirm Box Eintrag loeschen*/
function ConfirmDelete(id, title) {
	content = '<div class="confirmbox">' + title + ' l&ouml;schen?<br>';
	content += '<a href="#" onclick="javascript:$(\'.confirmbox\').remove(); $(\'tr\').removeClass(\'selected\');">abbrechen</a>';
	content += '<a href="javascript:DeleteRow(' + id + ')"> l&ouml;schen </a>';
	content +='</div>';
	$('table').before(content);
	$('.confirmbox').css('margin-left',Math.floor($('.confirmbox').width() / -2) -30);
	$('.contextmenue').remove();
}

/* Confirm Box Zeiterfassung loeschen*/
function ConfirmDeleteZeiterfassung(id, title) {
	var gesamtstunden = $('.summe').text().replace('Gesamt','');
	gesamtstunden = gesamtstunden.replace('Std.','');
	gesamtstunden = gesamtstunden.replace(/\s/g, '');
	gesamtstunden = gesamtstunden.replace(':', '-');
	content = '<div class="confirmbox">' + title + ' &uuml;bertragen/l&ouml;schen?<br>';
	content += '<a href="#" onclick="javascript:$(\'.confirmbox\').remove(); $(\'tr\').removeClass(\'selected\');">abbrechen</a>';
	content += '<a href="javascript:DeleteZeiterfassung(' + job_id + ')"> l&ouml;schen </a>';
	content += '<a href="javascript:DeleteZeiterfassung(' + job_id + ',' + gesamtstunden + ')"> &uuml;bertragen</a>';
	content +='</div>';
	$('table').before(content);
	$('.confirmbox').css('margin-left',Math.floor($('.confirmbox').width() / -2) -30);
	$('.contextmenue').remove();
}

/* Uhr */
function RotateClock() {
	var deg = 0;
	setInterval(function(){
		if (deg < 360) {deg += 12;} else {deg = 0;}
		var rotate = 'rotate('+deg+',9,9)';
		$('#clock_line').attr('transform', rotate);
	},100);
}