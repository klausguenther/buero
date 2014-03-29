<?php
session_start();
$_SESSION['tabelle'] = 'jobs';

include_once 'includes/dbzugang.php';
checkTimer();

// Suche abfragen
if (isset($_SESSION['search_jobliste'])) {
	$search_jobliste = $_SESSION['search_jobliste'];
} else {
	$_SESSION['search_jobliste'] = $search_jobliste = "WHERE (abschluss > '".date('Y')."-00-00') OR (abschluss IS NULL) OR (abschluss = '0000-00-00')";
}
if (isset($_REQUEST['search'])) {
	if ($_REQUEST['search'] == 'WHERE ') {
		$_SESSION['search_jobliste'] = $search_jobliste = "WHERE (abschluss > '".date('Y')."-00-00') OR (abschluss IS NULL) OR (abschluss = '0000-00-00')";
	} else {
		$search_jobliste = urldecode($_REQUEST['search']);
		$search_jobliste = str_replace("\'", "'", $search_jobliste);
		$_SESSION['search_jobliste'] = $search_jobliste;
	}
}
?>
<html>
<head>
<meta charset="utf-8">
<?php RGB(0,0,0)?>
<script language="JavaScript">
<!-- 
var tabelle = 'jobs';
var job_id;
//-->
</script>
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/kontextmenue.js"></script>
<script type="text/javascript" src="js/auswahl_funktionen.js"></script>
<script type="text/javascript" src="js/buero.js"></script>
</head>
<body>
<div class="arrow"><?=LeftArrow('index.php'); ?></div>
<a href="jobliste_suche.php"><h1>Jobliste</h1></a>
<table>
<thead>
	<tr>
		<td></td>
		<td>Kunde</td>
		<td>Job</td>
		<td>Fertigstellung</td>
		<td>RcnNr</td>
		<td>Rechnungsdatum</td>
		<td>Zahlungseingang</td>
		<td class="numeric">Brutto Summe</td>
	</tr>
</thead>
<tbody>
<?php
/* Daten ausgeben */
$ergebnis = mysql_query("SELECT * FROM jobs $search_jobliste ORDER BY EXTRACT(YEAR FROM rechnungsdatum), rechnungsnr ASC");
$tabindex = 1;
while($row = mysql_fetch_object($ergebnis))
{
?>
	<tr id="<?=$row->id ?>"class="data_row">
	<td class="arrow"><?=RightArrow($row->id, '') ?></td>
	<td class="data dropdown" name="kunde" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->kunde ?></td>
	<td class="data" name="job" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->job ?></td>
	<td class="data datum" name="abgabe" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuDatum($row->abgabe) ?></td>
	<td class="data" name="rechnungsnr" contenteditable="true" tabindex="<?=$tabindex++?>">
	<?php
	if ($row->rechnungsnr > 0) {
		echo sprintf("%05d", $row->rechnungsnr);
	}
	?></td>
	<td class="data datum" name="rechnungsdatum" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuDatum($row->rechnungsdatum) ?></td>
	<td class="data datum" name="abschluss" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuDatum($row->abschluss) ?></td>
	<td class="euro numeric" name="brutto">
	<?php
	$brutto_cache = '';
	$result = mysql_query("SELECT brutto_cache FROM jobs_positionen WHERE job_id=$row->id"); // Brutto_Cache aus jobs_positionen holen
	if (!mysql_num_rows($result) == 0) {
		$jobs_positionen_row = mysql_fetch_object($result);
		$brutto_cache = $jobs_positionen_row->brutto_cache;
	}
	echo ZahlZuEur($brutto_cache);
	echo '</td>';
	echo '</tr>';
}
?>
	<tr id="new" class="new_col">
		<td class="arrow">+</td>
		<td class="data dropdown" name="kunde" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data" name="job" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data datum" name="abgabe" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data" name="rechnungsnr" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data datum" name="rechnungsdatum" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data datum" name="abschluss" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="euro numeric"></td>
	</tr>
</tbody>
<?php include_once 'includes/summe_jobs.php'; // <tfoot> ?> 
</table>
</body>
</html>