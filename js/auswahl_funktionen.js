// Daten Eintragen 
var id;
var spalte;
var klasse;
var content;
var new_content;
var input;

function tdFocussed() {
	content = $(this).text(); // Alten Inhalt der Tabellenzelle abholen
	klasse = $(this).attr('class'); // Format der Tabellenzelle abholen
	$(this).css('outline', 'solid 1px');
}

var color=100;

function tdBlurred() {
	id = $(this).parent().attr('id'); // ID der Tabellenzeile abholen
	spalte = $(this).attr('name'); // Spaltenname abholen
	color=(color+20);
	$(this).removeAttr('style');
	input = $(this).text();
	new_content = input = $(this).text();
	if (klasse.match('datum')) {
		heute = new Date();
		datum = input.split('.');
		if (datum[0]) {
			datum[0] = ('0' + datum[0]).slice(-2);
			if (!datum[1]) {datum[1] = heute.getMonth() + 1;} else {datum[1] = ('0' + datum[1]).slice(-2);}
			if (!datum[2]) {datum[2] = heute.getFullYear();} else {datum[2] = ('20' + datum[2]).slice(-4);}
			input = datum[2] + '-' + datum[1] + '-' + datum[0];
		}
	}
	if (klasse.match('std')) {
		input = input.replace('Std.','');
		if (input.match(',')) {
			std = input.split(',');
			input = std[0] + '.' + std[1];
		}
		if (input.match(':')) {
			std = input.split(':');
			input = parseInt(std[0]) + parseFloat(std[1]/60);
			input = input.toString();
		}
	}
	if (klasse.match('zeit')) {
		input = input.replace('Uhr','');
		zeit = input.split(':');
		if (zeit[1]) {zeit[1] = parseInt(zeit[1]);} else {zeit[1] = '00';}
		input = zeit[0] + ':' + zeit[1];
	}
	
	if (klasse.match('euro')) {
		input = input.replace('€','');
		input = input.replace('\.','');
		input = input.replace('\,','\.');
	}
	
	// Kunden
	if (input != content && tabelle == 'kunden') {
		var tabindex = parseInt($('#new [name="anrede"]').attr('tabindex')); // hoechsten Tabindex merken
		new_row = '<tr id="new" class="new_col">' + $('#new').html() + '</tr>'; // Leerzeile merken
		// Zeile ersetzen
		$.getJSON('includes/update.php?id=' + id + '&spalte=' + spalte + '&content='
				+ encodeURIComponent(input), function(data) {
			$.each(data,function(key,val) {
				if (key=='id') {
					if (id=='new') {
						$('#new').attr('id',val.value).attr('class','data_row');
						$('#' + val.value).children('td.arrow').attr('style','border-style:dotted').html('');
						
						$(new_row).insertAfter('#' + val.value);
						$('#new td').text('');
						$('#new td').removeAttr('style');
						$('#new').children('td.arrow').eq(0).text('+');
						$( '#new td.data' ).each(function() {
							tabindex++;
							$(this).attr('tabindex',tabindex);
						});
						
						$('#' + val.value).mousedown(ContextMenue); // Kontext Menue
						$('#new td[contenteditable]').blur(tdBlurred);
						$('#new td[contenteditable]').focus(tdFocussed);
					}
				} 
				// traegt die neuen Werte in die Tabellenzellen ein
				$('#' + data.id.value).children('td[name="' + key + '"]').html(val.value).addClass(val.proof);
			});
		});
	}

	// Jobliste
	if (new_content != content && tabelle == 'jobs') {
		var tabindex = parseInt($('#new [name="abschluss"]').attr('tabindex')); // hoechsten Tabindex merken
		new_row = '<tr id="new" class="new_col">' + $('#new').html() + '</tr>'; // Leerzeile merken
		// Zeile ersetzen
		$.getJSON('includes/update.php?id=' + id + '&spalte=' + spalte + '&content='
				+ encodeURIComponent(input), function(data) {
			$.each(data,function(key,val) {
				if (key=='id') {
					if (id=='new') {
						$('#new').attr('id',val.value).attr('class','data_row');
						$('#' + val.value).children('td.arrow').html('<a href="jobzettel.php?id='+ val.value +'"><svg class="arrow" width="18px" height="16px"><polygon points="4,4 4,14 14,9" fill="#000"></polygon></svg></a>');
						
						$(new_row).insertAfter('#' + val.value);
						$('#new td').text('');
						$('#new td').removeAttr('style');
						$('#new').children('td.arrow').text('+');
						$( '#new td.data' ).each(function() {
							tabindex++;
							$(this).attr('tabindex',tabindex);
						});
						
						$('#' + val.value).mousedown(ContextMenue); // Kontext Menue
						$('#new td[contenteditable]').blur(tdBlurred);
						$('#new td[contenteditable]').focus(tdFocussed);
					}
				} 
				// traegt die neuen Werte in die Tabellenzellen ein
				$('#' + data.id.value).children('td[name="' + key + '"]').html(val.value).addClass(val.proof);
			});
			$( '#new td.dropdown' ).focus(DropDown); // Dropdown an die Leerzeile anbinden
			// Summe ersetzen
			$.get('includes/summe_' + tabelle + '.php', function(summe) {
				$('.summe').replaceWith(summe);
			});
		});
	}
	
	// Jobzettel
	if (new_content != content && tabelle == 'jobs_positionen') {
		var tabindex = parseInt($('#new [name="brutto"]').attr('tabindex')); // hoechsten Tabindex merken
		new_row = '<tr id="new" class="new_col">' + $('#new').html() + '</tr>'; // Leerzeile merken
		$(this).parent().children().removeClass('error'); // class 'error' aus Zeile entfernen
		// Zeile ersetzen
		$.getJSON('includes/update.php?id=' + id + '&job_id=' + job_id + '&spalte=' + spalte + '&content='
				+ encodeURIComponent(input), function(data) {
			$.each(data,function(key,val) {
				if (key=='id') {
					if (id=='new') {
						$('#new').attr('id',val.value).attr('class','data_row');
						$('#' + val.value).children('td.arrow').eq(0).css('border-style','dotted').text('');
						$('#' + val.value).children('td.arrow').eq(1).html('<a href="zeiterfassung.php?id='+ val.value +'"><svg class="arrow" width="18px" height="16px"><polygon points="4,4 4,14 14,9" fill="#000"></polygon></svg></a>');
						
						$(new_row).insertAfter('#' + val.value);
						$('#new td').text('');
						$('#new td').removeAttr('style');
						$('#new').children('td.arrow').eq(0).text('+');
						$( '#new td.data' ).each(function() {
							tabindex++;
							$(this).attr('tabindex',tabindex);
						});
						
						$('#' + val.value).mousedown(ContextMenue); // Kontext Menue
						$('#new td[contenteditable]').blur(tdBlurred);
						$('#new td[contenteditable]').focus(tdFocussed);
					}
				} 
				// traegt die neuen Werte in die Tabellenzellen ein
				$('#' + data.id.value).children('td[name="' + key + '"]').html(val.value).addClass(val.proof);
			});
			// Summe ersetzen
			$.get('includes/summe_' + tabelle + '.php?job_id=' + job_id, function(summe) {
				$('.summe').replaceWith(summe);
			});
		});
	}
	
	// Zeiterfassung
	if (new_content != 'ined-ed-0' && new_content != content && tabelle == 'jobs_stunden') {
		// alert ('input=' + input + ' content=' + content);
		var tabindex = parseInt($('#new [name="stunden"]').attr('tabindex')); // hoechsten Tabindex merken
		new_row = '<tr id="new" class="new_col">' + $('#new').html() + '</tr>'; // Leerzeile merken
		// Zeile ersetzen
		$.getJSON('includes/update.php?id=' + id + '&job_id=' + job_id + '&spalte=' + spalte + '&content='
				+ encodeURIComponent(input), function(data) {
			$.each(data,function(key,val) {
				if (key=='id') {
					if (id=='new') {
						$('#new').attr('id',val.value).attr('class','data_row');
						$('#' + val.value).children('td.arrow').html('<svg width="18px" height="16px"></svg>').css('border-style','dotted');
						
						$(new_row).insertAfter('#' + val.value);
						$('#new td').text('');
						$('#new td').removeAttr('style');
						$('#new').children('td.arrow').html('<svg class="clock" width="18px" height="18px"><circle cx="9" cy="9" r="7" stroke="black" stroke-width="2" fill="none"></circle><line x1="9" y1="2" x2="9" y2="9" stroke="black" stroke-width="2"></line></svg>');
						$('#new').children('td.arrow').click(ToggleTimer);
						$( '#new td.data' ).each(function() {
							tabindex++;
							$(this).attr('tabindex',tabindex);
						});
						
						$('#' + val.value).mousedown(ContextMenue); // Kontext Menue
						$('#new td[contenteditable]').blur(tdBlurred);
						$('#new td[contenteditable]').focus(tdFocussed);
					}
				} 
				// traegt die neuen Werte in die Tabellenzellen ein
				$('#' + data.id.value).children('td[name="' + key + '"]').html(val.value).addClass(val.proof);
				$('#' + data.id.value + ' td.arrow').click(ToggleTimer);
			});
			// Summe ersetzen
			$.get('includes/summe_' + tabelle + '.php?jobs_positionen_id=' + job_id, function(summe) {
				$('.summe').replaceWith(summe);
			});
		});
	}
	
	// Ausgaben
	if (new_content != content && tabelle == 'ausgaben') {
		var tabindex = parseInt($('#new [name="brutto"]').attr('tabindex')); // hoechsten Tabindex merken
		new_row = '<tr id="new" class="new_col">' + $('#new').html() + '</tr>'; // Leerzeile merken
		// Zeile ersetzen
		$.getJSON('includes/update.php?id=' + id + '&spalte=' + spalte + '&content='
				+ encodeURIComponent(input), function(data) {
			$.each(data,function(key,val) {
				if (key=='id') {
					if (id=='new') {
						$('#new').attr('id',val.value).attr('class','data_row');
						$('#' + val.value).children('td.arrow').html('<svg width="18px" height="16px"></svg>').css('border-style','dotted');
						
						$(new_row).insertAfter('#' + val.value);
						$('#new td').text('');
						$('#new td').removeAttr('style');
						$('#new').children('td.arrow').text('+');
						$( '#new td.data' ).each(function() {
							tabindex++;
							$(this).attr('tabindex',tabindex);
						});
						
						$('#' + val.value).mousedown(ContextMenue); // Kontext Menue
						$('#new td[contenteditable]').blur(tdBlurred);
						$('#new td[contenteditable]').focus(tdFocussed);
					}
				}
				$( '#new td.dropdown' ).focus(DropDown); // Dropdown an die Leerzeile anbinden
				// traegt die neuen Werte in die Tabellenzellen ein
				$('#' + data.id.value).children('td[name="' + key + '"]').html(val.value).addClass(val.proof);
			});
			// Summe ersetzen
			$.get('includes/summe_' + tabelle + '.php', function(summe) {
				$('.summe').replaceWith(summe);
			});
		});
	}
}

