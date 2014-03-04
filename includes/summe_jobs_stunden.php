<?php
/* Summe Stunden Zeiterfassung berechnen */
function GetSumStdZeiterfassung($jobs_positionen_id) {
	include_once 'dbzugang.php';
	
	$summe_stundenanzahl = '';
	$ergebnis = mysql_query("SELECT * FROM jobs_stunden WHERE jobs_positionen_id =$jobs_positionen_id");
	while($row = mysql_fetch_object($ergebnis)) {
		if ($row->stunden) {
			$summe_stundenanzahl += $row->stunden;
		} else {
			$summe_stundenanzahl += GetStd('', $row->id);
		}
	}
	echo '<tfoot class="summe"><td colspan="7" class="numeric">';
	if ($summe_stundenanzahl != 0) {
		echo '<b>Gesamt '.ZahlZuStd($summe_stundenanzahl).'</b>';
	}
	echo '</td></tr></tfoot>';
}

if(isset($_REQUEST['jobs_positionen_id'])) {
	$jobs_positionen_id = $_REQUEST['jobs_positionen_id'];
	echo GetSumStdZeiterfassung($jobs_positionen_id);
}
?>