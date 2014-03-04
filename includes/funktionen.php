<?php

/* Kalkulation Jobzettel*/
function Calculation ($jobs_positionen_id) {
	$ergebnis = mysql_query("SELECT * FROM jobs_positionen WHERE id =$jobs_positionen_id");
	$row = mysql_fetch_object($ergebnis);
	if ($row->stundenanzahl) {$stundenanzahl = $row->stundenanzahl;} else {$stundenanzahl = '';}
	// Stundenanzahl aus Tabelle "jobs_stunden" holen
	if (!$jobs_positionen_id == '' && $stundenanzahl == '') {
		$result_std = mysql_query("SELECT * FROM jobs_stunden WHERE jobs_positionen_id =$jobs_positionen_id");
		while($std_anz = mysql_fetch_object($result_std)) {
			$stundenanzahl += Std($std_anz->id);
			$stundenanzahl = (ceil($stundenanzahl / 15 * 60) * (15 * 60)) / 3600; // auf Viertelstunde aufrunden
		}
	}
	if ($row->stundensatz) {$stundensatz = $row->stundensatz;} else {$stundensatz = '';}
	if ($row->umstsatz) {$umstsatz = $row->umstsatz;} else {$umstsatz = '';}
	if ($row->netto) {$netto = $row->netto;} else {$netto = '';}
	if ($row->umst) {$umst = $row->umst;} else {$umst = '';}
	if ($row->brutto) {$brutto = $row->brutto;} else {$brutto = '';}
	
	
	 
	/* Netto */
	if ($umstsatz && $umst) {$netto = $umst * 100 / $umstsatz;}
	if ($stundenanzahl && $stundensatz) {$netto = $stundenanzahl * $stundensatz;}
	if ($brutto && $umstsatz) {$netto = $brutto * (100 / (100 + $umstsatz));}
	
	/* UmSt */
	if ($netto && $umstsatz) {$umst = $netto * $umstsatz / 100;}
	if ($netto && $brutto) {$umst = $brutto - $netto;}
	
	/* Brutto */
	if ($netto && !$umst) {$brutto = $netto;}
	if ($netto && $umst) {$brutto = $netto + $umst;}
	
	/* UmStSatz */
	if ($netto && $umst) {$umstsatz = intval($umst / ($netto / 100));}
	if ($brutto && $umst) {$umstsatz = intval($umst / (($brutto - $umst) / 100));}
	
	/* Stundensatz */
	if ($stundenanzahl && $netto) {$stundensatz = $netto / $stundenanzahl;}
	if ($stundenanzahl && $umstsatz && $brutto) {$stundensatz = $brutto * (100 / (100 + $umstsatz)) / $stundenanzahl;}
	
	/* Stundenanzahl */
	if ($netto && $stundensatz) {$stundenanzahl = $netto / $stundensatz;}
	if ($brutto && $stundensatz) {$stundenanzahl = $brutto * (100 / (100 + $umstsatz)) / $stundensatz;}
	
	$stundenanzahl_data = $stundenanzahl;
	if ($row->stundenanzahl) {$stundenanzahl_data = $row->stundenanzahl;}
	$stundensatz_data = $stundensatz;
	if ($row->stundensatz) {$stundensatz_data = $row->stundensatz;}
	$umstsatz_data = $umstsatz;if ($row->umstsatz) {$umstsatz_data = $row->umstsatz;}
	$netto_data = $netto;
	if ($row->netto) {$netto_data = $row->netto;}
	$umst_data = $umst;
	if ($row->umst) {$umst_data = $row->umst;}
	$brutto_data = $brutto;
	if ($row->brutto) {$brutto = $row->brutto;}
	
	/* brutto_cache aktualisieren */
	// mysql_query("UPDATE jobs_positionen SET brutto_cache='$brutto' WHERE id='$jobs_positionen_id'");
	
	return array ($stundenanzahl_data, $stundenanzahl, $stundensatz_data, $stundensatz, 
		$umstsatz_data, $umstsatz, $netto_data, $netto, $umst_data, $umst, $brutto_data, $brutto);
}

/* Prüfung */
function Proof ($calculation = '', $data = '') {
	floatval($data);
	
	if ($data > 0 && $data != $calculation) { // $data && $calculation && $data == $calculation
		$return = 'error';
		return $return;
	}
	
}
?>