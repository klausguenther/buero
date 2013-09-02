<?php
/* Summe Jobzettel berechnen */
function GetSummeJobzettel($job_id) {
	include_once 'dbzugang.php';
	
	$summe_umst = array ();
	$summe_netto = 0;
	$summe_brutto = 0;
	$ergebnis = mysql_query("SELECT * FROM jobs_positionen WHERE job_id =$job_id");
	while($row = mysql_fetch_object($ergebnis)) {
	list ($stundenanzahl_data, $stundenanzahl, $stundensatz_data, $stundensatz, 
		$umstsatz_data, $umstsatz, $netto_data, $netto, $umst_data, $umst, $brutto_data, $brutto) = Calculation($row->id);

		if ($netto && $brutto) {
			$summe_netto += $netto;
			$summe_brutto += $brutto;
		}
		if (!array_key_exists($umstsatz, $summe_umst)) {
			$summe_umst[$umstsatz] = $umst;
		} else {
			$summe_umst[$umstsatz] += $umst;
		}
	}
	$return = '<tfoot class="summe">';
	if ($summe_netto != 0) {
		$return .= '<tr><td colspan="9" class="numeric">Netto Summe </td>';
		$return .= '<td class="numeric">'.ZahlZuEur($summe_netto).'</td></tr>';
	}

	foreach($summe_umst as $umstsatz => $umst) {
		if ($umst != 0) {
			$return .= '<tr><td colspan="9" class="numeric">UmSt '.$umstsatz.'% Summe</td>';
			$return .= '<td class="numeric">'.ZahlZuEur($umst).'</td></tr>';
		}
	}

	if ($summe_brutto != 0) {
		$return .= '<tr><td colspan="9" class="numeric"><b>Brutto Summe</b></td>';
		$return .= '<td class="numeric"><b>'.ZahlZuEur($summe_brutto).'</b></td></tr>';
	}
	$return .= '</tfoot>';
	
	/* brutto_cache aktualisieren */
	mysql_query("UPDATE jobs_positionen SET brutto_cache='$summe_brutto' WHERE job_id='$job_id'");
	
	echo $return;
}

if(isset($_REQUEST['job_id'])) {
	$job_id = $_REQUEST['job_id'];
	echo GetSummeJobzettel($job_id);
}
?>