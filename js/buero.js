/* Dropdown Menue */
function DropDown(e) {
	if (!e) {var e = window.event;}
    e.stopPropagation();
	var inputfield = $(document.activeElement);
	var offset = $(this).offset();
	var width = $(this).width();
	$(this).width(width);
	
	// Dropdownmenue aus 'kunden.php' holen und anzeigen
	$.get('includes/dropdown.php', function(dropdown) {
		$('table').before(dropdown);
		$('.dropdownlist').css('top', (offset.top + 26));
		$('.dropdownlist').css('left', (offset.left - 1));
		$('.dropdownlist').mouseleave(RemoveDropdown);
		$('.data').focus(RemoveDropdown);
		$('.dropdownlist ul').css('width', (width + 4));
		$('.dropdownitem').click(PutInput);
	});
	
	function PutInput() {
		input = $(this).text();
		inputfield.text(input);
		$(inputfield).blur().next().focus();
	}
	
	function RemoveDropdown() {
		$('.dropdownlist').remove();
	}
	
	/* Dropdownmenue mit Pfeiltasten steuern
	$('.dropdownlist ul li:first-child').addClass('selected');
	var li = $('.dropdownitem');
	var liSelected;
	$(window).keydown(function(e){
	    if(e.which === 40){
	        if(liSelected){
	            liSelected.removeClass('selected');
	            next = liSelected.next();
	            if(next.length > 0){
	                liSelected = next.addClass('selected');
	            }else{
	                liSelected = li.eq(0).addClass('selected');
	            }
	        }else{
	            liSelected = li.eq(0).addClass('selected');
	        }
	    }else if(e.which === 38){
	        if(liSelected){
	            liSelected.removeClass('selected');
	            next = liSelected.prev();
	            if(next.length > 0){
	                liSelected = next.addClass('selected');
	            }else{
	                liSelected = li.last().addClass('selected');
	            }
	        }else{
	            liSelected = li.last().addClass('selected');
	        }
	    }
	});
	*/
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

/* Shortcuts */

/* Init */
$(document).ready(function() {
	$('.data_row').mousedown(ContextMenue); // Kontext Menue
	$('.dropdown').focus(DropDown); // Dropdown Menue
	
	$('[contenteditable]').focus(tdFocussed);
	$('[contenteditable]').blur(tdBlurred);
	$('.arrow').mouseover(function () {
		$('.data:focus').blur();
		$('.dropdown:focus').blur();
	});

	// document.onkeypress = keyinput; // Shortcuts

	RotateClock();
});
