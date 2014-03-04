var id_index = 0;
var id;
var spalte;
var klasse;
var content;
var new_row;

function tdFocussed() {
	$(this).css('outline', 'solid 1px');
}

var color=100;

function tdBlurred() {
	id = $(this).parent().attr('id'); // ID der Tabellenzeile abholen
	new_row = '<tr id="new" class="new_col">' + $('#new').html() + '</tr>'; // Leerzeile merken
	content = $(this).text();
	
	color=(color+20);
	$(this).removeAttr('style');
	
	if (content != '' && id == 'new') {
		if (tabelle == 'jobs_suche') { // Jobliste
			tabindex = parseInt($('#new [name="abschluss"]').attr('tabindex')); // hoechsten Tabindex merken
		}
		if (tabelle == 'ausgaben_suche') { // Ausgaben
			tabindex = parseInt($('#new [name="brutto"]').attr('tabindex')); // hoechsten Tabindex merken
		}
		
		id_index++;
		$('td.arrow').text('');
		$(this).parent().attr('id',id_index).attr('class','data_row');
		$(new_row).insertAfter('#' + id_index); // neue Leerzeile einfügen
			
		$('#new td').removeAttr('style').text('');
		$('#new td.arrow').text('+');
		$('#new td.data').each(function() {
			tabindex++;
			$(this).attr('tabindex',tabindex);
		});
		$('#new td[contenteditable]').blur(tdBlurred);
		$('#new td[contenteditable]').focus(tdFocussed);
	}
}

/* Suche */
function PreTagMonat(n) { // Tag und Monat "0" voranstellen
	return (Array(2).join('0') + n).slice(-2);
}

function PreJahr(n) { // Jahrzehnt Jahrhundert voranstellen
	jahrhundert = new Date().getFullYear().toString().slice(0, 2);
	return jahrhundert + n.substr(-2, 2);
}

function Monatsende(monat,jahr) {
	var m = [31,28,31,30,31,30,31,31,30,31,30,31];
	if (monat != 2) return m[monat - 1];
	if (jahr%4 != 0) return m[1];
	if (jahr%100 == 0 && jahr%400 != 0) return m[1];
	return m[1] + 1;
	}

function suche() {
	var zeile = new Array();
	$('tbody tr').each (function() {
		var search = '';
		var i = $(this).attr('id');
	
		$('#'+i + ' .data').each (function() {
			tage = [31,30,31,30,31,30,31,30,31,30,31,30];
			spalte = $(this).attr('name');
			content = $(this).text();
			content = content.replace('&amp;','_');
			klasse = $(this).attr('class');
			if (content) {
				if (search != '') {
					search += ' AND ';
				}
				if (klasse == 'data datum') { // Suche Datum
					datum = content.split('-', 2);
				
					if (datum[0] && datum[1]) { // BETWEEN
						d = datum[0].split('.', 3);
						if (d[2]) { // Beginn, Tag Monat Jahr
							datum[0] = d[2] + '-'+ PreTagMonat(d[1]) + '-' + PreTagMonat(d[0]);
						}
						if (!d[2]) { // Beginn, Monat Jahr
							datum[0] = d[1] + '-' + PreTagMonat(d[0]) + '-01';
						}
						if (!d[1]) { // Beginn, Jahr
							datum[0] = PreJahr(d[0]) + '-01-01';
						}
				
						d = datum[1].split('.', 3);
						if (d[2]) { // Ende, Tag Monat Jahr
							datum[1] = d[2] + '-'+ PreTagMonat(d[1]) + '-' + PreTagMonat(d[0]);
						}
						if (!d[2]) { // Ende, Manat Jahr
							datum[1] = d[1] + '-' + PreTagMonat(d[0]) + '-' + Monatsende(d[0],d[1]);
						}
						if (!d[1]) { // Ende, Jahr
							datum[1] = PreJahr(d[0]) + '-12-31';
						}
						search += spalte + " BETWEEN '" + datum[0] + "' AND '" + datum[1] + "'";
					} else { // LIKE
						d = datum[0].split('.', 3);
						if (d[2]) { // Tag Monat Jahr
							datum[0] = d[2] + '-'+ PreTagMonat(d[1]) + '-' + PreTagMonat(d[0]);
						}
						if (!d[2]) { // Monat und Jahr
							datum[0] = d[1] + '-' + PreTagMonat(d[0]) + '-%';
						}
						if (!d[1]) { // Jahr
							datum[0] = PreJahr(d[0]) + '-%';
						}
						search += spalte + " LIKE '" + datum[0] + "'";
					}
				}
			
				if (klasse == 'data numeric') { // Suche Zahl
					number = content.split('-', 2);
					for (var i = 0; i < number.length; i++) {
						number[i] = number[i].replace(/[^\d\,\-\ ]/g, '');
						number[i] = number[i].replace(/,/g, '.');
					}
					if (number[0] && number[1]) {
						search += spalte + " BETWEEN '" + number[0] + "' AND '" + number[1] + "'";
					} else {
						search += spalte + " LIKE '" + number[0] + "' ";
					}
				}
			
				if (klasse == 'data' || klasse == 'data dropdown') { // Suche Text
					search += spalte + " LIKE '%" + content + "%' ";
				}
			}
		});
		zeile.push(search);
	});
	sql_suche = 'WHERE ';
	for (var i = 0; i < zeile.length; i++) {
		if (zeile[i] != '') {
			if (i > 0) {
				sql_suche += ' OR ';
			}
			sql_suche += '(' + zeile[i] + ')';
		}
		
	}
	if (tabelle == 'jobs_suche') {
		window.location.href='jobliste.php?search=' + encodeURIComponent(sql_suche);
	}
	if (tabelle == 'ausgaben_suche') {
		window.location.href='ausgaben.php?search=' + encodeURIComponent(sql_suche);
	}
}

document.onkeydown = function(event) {
	if (event.keyCode == 13) {
		event.cancelBubble = true;
		event.returnValue = false;
		suche();
	}
	return event.returnValue;
};