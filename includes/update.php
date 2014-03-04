<?php
session_start();

/* Zugangsdaten */
include_once "dbzugang.php";

/* Variablen übernehmen */
$tabelle = $_SESSION['tabelle'];
if (isset($_REQUEST['id'])) {$id = $_REQUEST['id'];}
if (isset($_REQUEST['job_id'])) {$job_id = $_REQUEST['job_id'];}
if (isset($_REQUEST['spalte'])) {$spalte = $_REQUEST['spalte'];}
if (isset($_REQUEST['content'])) {$content = $_REQUEST['content'];}

/* Neue Zeile eintragen. Zeiger $new_row wird auf TRUE gesetzt. */
if ($id == 'new') {
	if ($tabelle == 'kunden') {
		$ergebnis = mysql_query("INSERT INTO kunden (".$spalte.") VALUES ('".$content."')");
	}
	if ($tabelle == 'jobs') {
		$ergebnis = mysql_query("INSERT INTO jobs (".$spalte.") VALUES ('".$content."')");
	}
	if ($tabelle == 'jobs_positionen') {
		$ergebnis = mysql_query("INSERT INTO jobs_positionen (".$spalte.",job_id) VALUES ('".$content."',".$job_id.")");
	}
	if ($tabelle == 'jobs_stunden') {
		$ergebnis = mysql_query("INSERT INTO jobs_stunden (".$spalte.",jobs_positionen_id) VALUES ('".$content."',".$job_id.")");
	}
	if ($tabelle == 'ausgaben') {
		$ergebnis = mysql_query("INSERT INTO ausgaben (".$spalte.") VALUES ('".$content."')");
	}
	$id =  mysql_insert_id();
	autocompleteAusgaben($id, $spalte, $content);

} else {
	mysql_query("UPDATE $tabelle SET $spalte='$content' WHERE id='$id'");
	autocompleteAusgaben($id, $spalte, $content);
}

// Rückgabe Kunden
if ($tabelle == 'kunden') {
	$ergebnis = mysql_query("SELECT * FROM kunden WHERE id ='$id'"); // Zeile holen
	$row = mysql_fetch_object($ergebnis);
	echo '{
			"id" : { "value" : "'.$row->id.'", "proof" : "" },
			"kundenkuerzel" : { "value" : "'.$row->kundenkuerzel.'", "proof" : "" },
			"firma" : { "value" : "'.$row->firma.'", "proof" : "" },
			"name" : { "value" : "'.$row->name.'", "proof" : "" },
			"vorname" : { "value" : "'.$row->vorname.'", "proof" : "" },
			"strasse" : { "value" : "'.$row->strasse.'", "proof" : "" },
			"plz" : { "value" : "'.$row->plz.'", "proof" : "" },
			"ort" : { "value" : "'.$row->ort.'", "proof" : "" },
			"anrede" : { "value" : "'.$row->anrede.'", "proof" : "" }
		}';
}

// Rückgabe Jobliste
if ($tabelle == 'jobs') {
	$ergebnis = mysql_query("SELECT * FROM jobs WHERE id ='$id'"); // Zeile holen
	$row = mysql_fetch_object($ergebnis);

	$brutto_cache = '';
	$result = mysql_query("SELECT brutto_cache FROM jobs_positionen WHERE job_id=$id");
	$jobs_positionen_row = mysql_fetch_object($result);
	if ($jobs_positionen_row) {
		$brutto_cache = $jobs_positionen_row->brutto_cache;
	}
	
	echo '{
		"id" : { "value" : "'.$row->id.'", "proof" : "" },
		"kunde" : { "value" : "'.$row->kunde.'", "proof" : "" },
		"job" : { "value" : "'.$row->job.'", "proof" : "" },
		"abgabe" : { "value" : "'.ZahlZuDatum($row->abgabe).'", "proof" : "" },
		"rechnungsnr" : { "value" : "'.ZahlAnz(sprintf("%05d", $row->rechnungsnr)).'", "proof" : "" },
		"rechnungsdatum" : { "value" : "'.ZahlZuDatum($row->rechnungsdatum).'", "proof" : "" },
		"abschluss" : { "value" : "'.ZahlZuDatum($row->abschluss).'", "proof" : "" },
		"brutto" : { "value" : "'.ZahlZuEur($brutto_cache).'", "proof" : "" }	
	}';
}

// Rückgabe Jobzettel
if ($tabelle == 'jobs_positionen') {
	$ergebnis = mysql_query("SELECT * FROM jobs_positionen WHERE id ='$id'"); // Zeile holen
	$row = mysql_fetch_object($ergebnis);
	list ($stundenanzahl_data, $stundenanzahl, $stundensatz_data, $stundensatz,
		$umstsatz_data, $umstsatz, $netto_data, $netto, $umst_data, $umst, $brutto_data, $brutto) = Calculation($row->id);
	echo '{
		"id" : { "value" : "'.$row->id.'", "proof" : "" },
		"position" : { "value" : "'.$row->position.'", "proof" : "" },
		"beschreibung" : { "value" : "'.$row->beschreibung.'", "proof" : "" },
		"stundenanzahl" : { "value" : "'.ZahlZuStd($stundenanzahl_data).'", "proof" : "'.Proof($stundenanzahl, $row->stundenanzahl).'" },
		"stundensatz" : { "value" : "'.ZahlZuEur($stundensatz_data).'", "proof" : "'.Proof($stundensatz, $row->stundensatz).'" },
		"umstsatz" : { "value" : "'.ZahlZuPro($umstsatz_data).'", "proof" : "'.Proof($umstsatz, $row->umstsatz).'" },
		"netto" : { "value" : "'.ZahlZuEur($netto_data).'", "proof" : "'.Proof($netto, $row->netto).'" },
		"umst" : { "value" : "'.ZahlZuEur($umst_data).'", "proof" : "'.Proof($umst, $row->umst).'" },
		"brutto" : { "value" : "'.ZahlZuEur($brutto_data).'", "proof" : "'.Proof($brutto, $row->brutto).'" }
	}'; // ZahlZuStd($stundenanzahl _data ? )
}

// Rückgabe Zeiterfassung
if ($tabelle == 'jobs_stunden') {
	$ergebnis = mysql_query("SELECT * FROM jobs_stunden WHERE id ='$id'"); // Zeile holen
	$row = mysql_fetch_object($ergebnis);
	
	$clock = mysql_real_escape_string(RightArrow(0, 0, $row->id));
	
	$stunden = '';
	if ($row->stunden) {
		$stunden = $row->stunden;
	} else {
		$stunden = GetStd('', $id);
	}
	
	echo '{
		"id" : { "value" : "'.$row->id.'", "proof" : "" },
		"clock" : { "value" : "'.$clock.'", "proof" : "" },
		"start_datum" : { "value" : "'.ZahlZuDatum($row->start_datum).'", "proof" : "" },
		"start_zeit" : { "value" : "'.ZahlZuZeit($row->start_zeit).'", "proof" : "" },
		"end_datum" : { "value" : "'.ZahlZuDatum($row->end_datum).'", "proof" : "" },
		"end_zeit" : { "value" : "'.ZahlZuZeit($row->end_zeit).'", "proof" : "" },
		"beschreibung" : { "value" : "'.$row->beschreibung.'", "proof" : "" },
		"stunden" : { "value" : "'.ZahlZuStd($stunden).'", "proof" : "" }
	}'; // clock ?
}

// Rückgabe Ausgaben
if ($tabelle == 'ausgaben') {
	$ergebnis = mysql_query("SELECT * FROM ausgaben WHERE id ='$id'"); // Zeile holen
	$row = mysql_fetch_object($ergebnis);
	
	echo '{
		"id" : { "value" : "'.$row->id.'", "proof" : "" },
		"datum" : { "value" : "'.ZahlZuDatum($row->datum).'", "proof" : "" },
		"empfaenger" : { "value" : "'.$row->empfaenger.'", "proof" : "" },
		"verwendungszweck" : { "value" : "'.$row->verwendungszweck.'", "proof" : "" },
		"kostenart" : { "value" : "'.$row->kostenart.'", "proof" : "" },
		"umstsatz" : { "value" : "'.ZahlZuPro($row->umstsatz).'", "proof" : "" },
		"brutto" : { "value" : "'.ZahlZuEur($row->brutto).'", "proof" : "" },
		"minderung" : { "value" : "'.ZahlZuPro($row->minderung).'", "proof" : "" }
	}';
}

// Autocomplete Ausgaben
function autocompleteAusgaben($id, $spalte, $content) {
	if ($spalte == 'empfaenger') {
		$ergebnis = mysql_query("SELECT COUNT(*)
				AS max, verwendungszweck, kostenart, umstsatz, minderung
				FROM ausgaben
				WHERE empfaenger ='$content'
				GROUP BY verwendungszweck
				ORDER BY max DESC
				LIMIT 0, 1");
		$row = mysql_fetch_object($ergebnis);
		mysql_query("UPDATE ausgaben SET verwendungszweck='$row->verwendungszweck' WHERE id='$id'");
		mysql_query("UPDATE ausgaben SET kostenart='$row->kostenart' WHERE id='$id'");
		mysql_query("UPDATE ausgaben SET umstsatz='$row->umstsatz' WHERE id='$id'");
		mysql_query("UPDATE ausgaben SET minderung='$row->minderung' WHERE id='$id'");
	}
}
?>