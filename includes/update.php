<?php
session_start();

/* Zugangsdaten */
include_once "dbzugang.php";

/* Variablen übernehmen */
$tabelle = $_SESSION['tabelle'];
$id = $_REQUEST['id'];
$job_id = $_REQUEST['job_id'];
$spalte = $_REQUEST['spalte'];
$content = $_REQUEST['content'];
$new_row = FALSE;

/* Neue Zeile eintragen. Zeiger $new_row wird auf TRUE gesetzt. */
if ($id == 'new') {
	$ergebnis = mysql_query("INSERT INTO $tabelle (id) VALUES ('')");
	$id = mysql_insert_id();
	if ($tabelle == 'jobs_positionen') { // Eintragen der Referenz $job_id in jobs_positionen
		mysql_query("UPDATE jobs_positionen SET job_id='$job_id' WHERE id='$id'");
	}
	if ($tabelle == 'jobs_stunden') { // Eintragen der Referenz $jobs_position_id in jobs_stunden
		mysql_query("UPDATE jobs_stunden SET jobs_positionen_id='$job_id' WHERE id='$id'");
	}
	$new_row = true;
}

/* Daten eintragen */
mysql_query("UPDATE $tabelle SET $spalte='$content' WHERE id='$id'");

// Rückgabe Kunden
if ($tabelle == 'kunden') {
	$ergebnis = mysql_query("SELECT * FROM kunden WHERE id ='$id'"); // Zeile holen
	$row = mysql_fetch_object($ergebnis);

	echo '<tr id="'.$row->id.'"class="data_row">
		<td style="border-style:dotted" class="arrow"></td>
		<td class="data" name="kundenkuerzel" contenteditable="true">'.$row->kundenkuerzel.'</td>
		<td class="data" name="firma" contenteditable="true">'.$row->firma.'</td>
		<td class="data" name="name" contenteditable="true">'.$row->name.'</td>
		<td class="data" name="vorname" contenteditable="true">'.$row->vorname.'</td>
		<td class="data" name="strasse" contenteditable="true">'.$row->strasse.'</td>
		<td class="data" name="plz" contenteditable="true">'.$row->plz.'</td>
		<td class="data" name="ort" contenteditable="true">'.$row->ort.'</td>
		<td class="data" name="anrede" contenteditable="true">'.$row->anrede.'</td>
		</tr>';
}

if ($new_row == true && $tabelle == 'kunden') {
	echo '<tr id="new" class="new_col">
		<td class="arrow">+</td>
		<td class="data" name="kundenkuerzel" contenteditable="true"></td>
		<td class="data" name="firma" contenteditable="true"></td>
		<td class="data" name="name" contenteditable="true"></td>
		<td class="data" name="vorname" contenteditable="true"></td>
		<td class="data" name="strasse" contenteditable="true"></td>
		<td class="data" name="plz" contenteditable="true"></td>
		<td class="data" name="ort" contenteditable="true"></td>
		<td class="data" name="anrede" contenteditable="true"></td>
		</tr>';
}

// Rückgabe Jobliste
if ($tabelle == 'jobs') {
	$ergebnis = mysql_query("SELECT * FROM jobs WHERE id ='$id'"); // Zeile holen
	$row = mysql_fetch_object($ergebnis);

	$brutto_cache = '';
	$result = mysql_query("SELECT brutto_cache FROM jobs_positionen WHERE job_id=$id");
	$jobs_positionen_row = mysql_fetch_object($result);
	$brutto_cache = $jobs_positionen_row->brutto_cache;

	echo '<tr id="'.$row->id.'"class="data_row">
	<td class="arrow">'.RightArrow($row->id, '').'</td>
	<td class="data" name="kunde" contenteditable="true">'.$row->kunde.'</td>
	<td class="data" name="job" contenteditable="true">'.$row->job.'</td>
	<td class="data datum" name="abgabe" contenteditable="true">'.ZahlZuDatum($row->abgabe).'</td>
	<td class="data" name="rechnungsnr" contenteditable="true">'.ZahlAnz(sprintf("%05d", $row->rechnungsnr)).'</td>
	<td class="data datum" name="rechnungsdatum" contenteditable="true">'.ZahlZuDatum($row->rechnungsdatum).'</td>
	<td class="data datum" name="abschluss" contenteditable="true">'.ZahlZuDatum($row->abschluss).'</td>
	<td class="numeric">'.ZahlZuEur($brutto_cache).'</td></tr>';
}

if ($new_row == true && $tabelle == 'jobs') {
	echo '<tr id="new" class="new_col">
	<td class="arrow">+</td>
	<td class="data" name="kunde" contenteditable="true"></td>
	<td class="data" name="job" contenteditable="true"></td>
	<td class="data datum" name="abgabe" contenteditable="true"></td>
	<td class="data" name="rechnungsnr" contenteditable="true"></td>
	<td class="data datum" name="rechnungsdatum" contenteditable="true"></td>
	<td class="data datum" name="abschluss" contenteditable="true"></td>
	<td class="numeric"></td>
	</tr>';
}

// Rückgabe Jobzettel
if ($tabelle == 'jobs_positionen') {
	$ergebnis = mysql_query("SELECT * FROM jobs_positionen WHERE id ='$id'"); // Zeile holen
	$row = mysql_fetch_object($ergebnis);
	list ($stundenanzahl_data, $stundenanzahl, $stundensatz_data, $stundensatz,
		$umstsatz_data, $umstsatz, $netto_data, $netto, $umst_data, $umst, $brutto_data, $brutto) = Calculation($row->id);
	echo '<tr id="'.$row->id.'" class="data_row"><td style="border:dotted 1px #000" class="arrow"></td>
	<td class="data" name="position" contenteditable="true">'.$row->position.'</td>
	<td class="data" name="beschreibung" contenteditable="true">'.$row->beschreibung.'</td>
	<td class="arrow">'.RightArrow('', $row->id).'</td>
	<td class="data std numeric" name="stundenanzahl" contenteditable="true"'.Proof($stundenanzahl, $row->stundenanzahl).'>'.ZahlZuStd($stundenanzahl).'</td>
	<td class="data numeric" name="stundensatz" contenteditable="true"'.Proof($stundensatz, $row->stundensatz).'>'.ZahlZuEur($stundensatz_data).'</td>
	<td class="data numeric" name="umstsatz" contenteditable="true"'.Proof($umstsatz, $row->umstsatz).'>'.ZahlZuPro($umstsatz_data).'</td>
	<td class="data numeric" name="netto" contenteditable="true"'.Proof($netto, $row->netto).'>'.ZahlZuEur($netto_data).'</td>
	<td class="data numeric" name="umst" contenteditable="true"'.Proof($umst, $row->umst).'>'.ZahlZuEur($umst_data).'</td>
	<td class="data numeric" name="brutto" contenteditable="true"'.Proof($brutto, $row->brutto).'>'.ZahlZuEur($brutto_data).'</td></tr>';
}

if ($new_row == true && $tabelle == 'jobs_positionen') {
	echo '<tr id="new" class="new_col">
	<td class="arrow">+</td>
	<td class="data" name="postion" contenteditable="true"></td>
	<td class="data" name="beschreibung" contenteditable="true"></td>
	<td class="arrow">'.RightArrow('', $row->id).'</td>
	<td class="data std numeric" name="stundenanzahl" contenteditable="true"></td>
	<td class="data numeric" name="stundensatz" contenteditable="true"></td>
	<td class="data numeric" name="umstsatz" contenteditable="true"></td>
	<td class="data numeric" name="netto" contenteditable="true"></td>
	<td class="data numeric" name="umst" contenteditable="true"></td>
	<td class="data numeric" name="brutto" contenteditable="true"></td>
	</tr>';
}

// Rückgabe Zeiterfassung
if ($tabelle == 'jobs_stunden') {
	$ergebnis = mysql_query("SELECT * FROM jobs_stunden WHERE id ='$id'"); // Zeile holen
	$row = mysql_fetch_object($ergebnis);
	$clock = RightArrow(0, 0, $row->id);
	if ($clock == '<svg width="18px" height="16px"></svg>') {$style = 'style="border-style:dotted"';} else {$style = '';}
	$stundenanzahl = GetStd('', $id);
	
	echo '<tr id="'.$row->id.'" class="data_row">
	<td class="arrow" '.$style.'>'.$clock.'</td>
	<td class="data datum" name="start_datum" contenteditable="true">'.ZahlZuDatum($row->start_datum).'</td>
	<td class="data" name="start_zeit" contenteditable="true">'.ZahlZuZeit($row->start_zeit).'</td>
	<td class="data datum" name="end_datum" contenteditable="true">'.ZahlZuDatum($row->end_datum).'</td>
	<td class="data" name="end_zeit" contenteditable="true">'.ZahlZuZeit($row->end_zeit).'</td>
	<td class="data" name="beschreibung" contenteditable="true">'.$row->beschreibung.'</td>
	<td class="data std numeric" name="stundenanzahl" contenteditable="true">'.ZahlZuStd($stundenanzahl).'</td>
	</tr>';
}

if ($new_row == true && $tabelle == 'jobs_stunden') {
	echo '<tr id="new" class="new_col">
	<td class="arrow" '.$style.'>'.$clock.'</td>
	<td class="data datum" name="start_datum" contenteditable="true"></td>
	<td class="data" name="start_zeit" contenteditable="true"></td>
	<td class="data datum" name="end_datum" contenteditable="true"></td>
	<td class="data" name="end_zeit" contenteditable="true"></td>
	<td class="data" name="beschreibung" contenteditable="true"></td>
	<td class="data std numeric" name="stundenanzahl" contenteditable="true"></td>
	</tr>';
}
?>