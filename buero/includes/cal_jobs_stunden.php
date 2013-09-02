<?php
// Stundenrechnung
function Std($zeiterfassung_id) { // Stundenanzahl aus Zeiterfassung // wird in GetStd verwendet // wird in funktionen.php verwendet
	$stundenanzahl = 0;
	$result = mysql_query("SELECT * FROM jobs_stunden WHERE id =$zeiterfassung_id");
	while($std = mysql_fetch_object($result)) {
		if ($std->start_datum != '0000-00-00' && $std->start_zeit != '00:00:00' && $std->end_datum != '0000-00-00' && $std->end_zeit != '00:00:00' && $std->stunden == 0) {
			$start_time = strtotime($std->start_datum.','.$std->start_zeit);
			$end_time = strtotime($std->end_datum.', '.$std->end_zeit);
			$stundenanzahl = (ceil(($end_time - $start_time)/100)*100) / 3600;
			// echo $stundenanzahl.'<br>';
			
			//3600->60 min
			//1800->30 min
			// 900->15 min
			// 600->10 min
			// 300-> 5 min
			// 100_> 1 min
		} else {
			$stundenanzahl = $std->stunden;
		}
		return $stundenanzahl;
	}
}
function GetStd($jobs_positionen_id= 0, $zeiterfassung_id= 0) { // wird in zeiterfassung.php verwendet !!!
	$summe_stundenanzahl = '';
	if ($jobs_positionen_id > 0) {
		$result_std = mysql_query("SELECT * FROM jobs_stunden WHERE jobs_positionen_id =$jobs_positionen_id");
		while($std_anz = mysql_fetch_object($result_std)) {
			$summe_stundenanzahl += Std($std_anz->id);
		}
	}
	if ($zeiterfassung_id > 0) {
		$summe_stundenanzahl = Std($zeiterfassung_id);
	}
	return $summe_stundenanzahl;
}
?>