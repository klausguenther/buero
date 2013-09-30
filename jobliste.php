<?php
session_start();
$_SESSION['tabelle'] = 'jobs';

include_once 'includes/dbzugang.php';
checkTimer();
?>
<html>
<head>
<meta charset="utf-8">
<?php RGB(0,150,0)?>
<script language="JavaScript">
<!-- 
var tabelle = 'jobs';
var job_id;
//-->
</script>
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/kontextmenue.js"></script>
<script type="text/javascript" src="js/buero.js"></script>
<script type="text/javascript" src="js/auswahl_funktionen.js"></script>
</head>
<body>
<div class="arrow" style="border-style:dotted;"></div>
<h1>Jobliste 2013</h1>
<table>
<thead>
	<tr>
		<td>&nbsp;</td>
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
$ergebnis = mysql_query("SELECT * FROM jobs ORDER BY rechnungsnr ASC");
$tabindex = 1;
while($row = mysql_fetch_object($ergebnis))
{
?>
	<tr id="<?=$row->id ?>"class="data_row">
	<td class="arrow"><?=RightArrow($row->id, '') ?></td>
	<td class="dropdown" name="kunde"><?=$row->kunde ?></td>
	<td class="data" name="job" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->job ?></td>
	<td class="data datum" name="abgabe" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuDatum($row->abgabe) ?></td>
	<td class="data" name="rechnungsnr" contenteditable="true" tabindex="<?=$tabindex++?>">
	<?php
	if ($row->rechnungsnr > 0) {
		echo sprintf("%05d", $row->rechnungsnr);
	}
	?>
	<td class="data datum" name="rechnungsdatum" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuDatum($row->rechnungsdatum) ?></td>
	<td class="data datum" name="abschluss" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuDatum($row->abschluss) ?></td>
	<td class="numeric">
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
		<td class="dropdown" name="kunde"></td>
		<td class="data" name="job" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data" name="abgabe" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data" name="rechnungsnr" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data numeric" name="rechnungsdatum" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data numeric" name="abschluss" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="numeric"></td>
	</tr>
</tbody>
<?php include_once 'includes/summe_jobs.php'; // <tfoot> ?> 
</table>
</body>
</html>