<?php
/* Navigation */

// Schreibt die timer_job_id und timer_jobs_positionen_id wenn der Timer läuft in die Session
function checkTimer() {
	$_SESSION['timer_job_id'] = 0;
	$_SESSION['timer_jobs_positionen_id'] = 0;
	$timer = mysql_query("SELECT jobs_positionen_id FROM jobs_stunden WHERE (NOT start_datum = '00-00-0000') AND (NOT start_zeit = '00:00:00') AND end_datum = '00-00-0000' AND end_zeit = '00:00:00'");
	$timer_position_id = mysql_fetch_object($timer);
	if (ISSET($timer_position_id->jobs_positionen_id)) {
		$_SESSION['timer_jobs_positionen_id'] = $timer_position_id->jobs_positionen_id;
		$timer_job = mysql_query("SELECT job_id FROM jobs_positionen WHERE id =$timer_position_id->jobs_positionen_id");
		$timer_job_id = mysql_fetch_object($timer_job);
		$_SESSION['timer_job_id'] = $timer_job_id->job_id;
	}
}

// Pfeil oder Uhr "weiter", mit Link zu jobzettel.php oder zeiterfassung.php (aktuelle id wird übergeben).
// Uhrschalter für zeiterfassung.php
function RightArrow($job_id = 0, $jobs_position_id = 0, $jobs_stunden_id = 0) {
	$right_arrow = '<svg class="arrow" width="18px" height="16px"><polygon points="4,4 4,14 14,9" fill="#000" /></svg>';
	$clock_off ='<svg class="clock" width="18px" height="18px"><circle cx="9" cy="9" r="7" stroke="black" stroke-width="2" fill="none"/><line x1="9" y1="2" x2="9" y2="9" stroke="#000" stroke-width="2"/></svg>';
	$clock_on ='<svg class="clock" width="18px" height="18px"><circle cx="9" cy="9" r="7" stroke="black" stroke-width="2" fill="none"/>
		<line id="clock_line" x1="9" y1="2" x2="9" y2="9" stroke="#000" stroke-width="2" transform="rotate(180,9,9)" /></svg></a>';
	$return = '<svg width="18px" height="16px"></svg>';
	
	if ($job_id > 0) { // jobliste
		$return = '<a href="jobzettel.php?id='.$job_id.'">'.$right_arrow.'</a>';
		if ($_SESSION['timer_job_id'] == $job_id) {
			$return = '<a href="jobzettel.php?id='.$job_id.'">'.$clock_on.'</a>';
		}
	}
	if ($jobs_position_id > 0) { // jobzettel
		$return = '<a href="zeiterfassung.php?id='.$jobs_position_id.'">'.$right_arrow.'</a>';
		if ($_SESSION['timer_jobs_positionen_id'] == $jobs_position_id) {
			$return = '<a href="zeiterfassung.php?id='.$jobs_position_id.'">'.$clock_on.'</a>';
		}
	}
	if ($jobs_stunden_id > 0) { // zeiterfassung
		$result_stunden = mysql_query("SELECT * FROM jobs_stunden WHERE id =$jobs_stunden_id");
		while($std = mysql_fetch_object($result_stunden)) {
			if ($std->start_zeit == '00:00:00' && $std->end_zeit == '00:00:00' && $std->stunden == 0) {
				$return = $clock_off;
			}
			if ($std->start_zeit != '00:00:00' && $std->end_zeit == '00:00:00' && $std->stunden == 0) {
				$return = $clock_on;
			}
		}
	}
	return $return;
}

// Pfeil "zurück", optional mit Link.
function LeftArrow($url, $key='', $val='') { // Bsp.: (<a href="url?key=val">)LINKSPFEIL(</a>)
	$left_arrow = '<svg class="arrow" width="18px" height="16px"><polygon points="4,9 14,4 14,14" fill="#000" /></svg>';
	$return = '<svg width="18px" height="16px"></svg>';
	if ($url != '') {
		if ($key != '') {
			$key='?'.$key;
		}
		if ($val != '') {
			$val='='.$val;
		}
		$url = $url.$key.$val;
		$return = '<a href="'.$url.'">'.$left_arrow.'</a>';
	}
	return $return;
}
?>