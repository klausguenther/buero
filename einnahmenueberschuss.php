<?php
session_start();
$_SESSION['tabelle'] = 'ausgaben';

$veranlagung = 'abschluss'; // 'rechnungsdatum'

include_once 'includes/dbzugang.php';

if (isset($_SESSION['search_jobliste'])) { // Suche Einnahmen
	$search_jobliste = str_replace(" OR (abschluss IS NULL) OR (abschluss = '0000-00-00')", "", $_SESSION['search_jobliste']);
} else {
	$search_jobliste = "WHERE (abschluss > '".date('Y')."-00-00')";
}

if (isset($_SESSION['search_ausgaben'])) { // Suche Ausgaben
	$search_ausgaben = $_SESSION['search_ausgaben'];
} else {
	$search_ausgaben = $_SESSION['search_ausgaben'] = "WHERE (datum LIKE '%".date('Y')."%')";
}

?>
<html>
<head>
<meta charset="utf-8">
<?php RGB(0,150,0)?>
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/kontextmenue.js"></script>
<script type="text/javascript" src="js/auswahl_funktionen.js"></script>
<script type="text/javascript" src="js/buero.js"></script>
</head>
<body>
<div class="arrow"><?=LeftArrow('index.php'); ?></div>
<h1>EÜR</h1>
<table>
<thead></thead>
<tbody>
<?php
/* Einnahmen ausgeben */
$summe_einnahmen_netto = '';
$summe_einnahmen_umst = '';
$summe_einnahmen_brutto = '';
$job_id = mysql_query("SELECT id FROM jobs $search_jobliste");
while($id = mysql_fetch_object($job_id)) {

	$summe_umst = array ();
	$summe_netto = 0;
	$summe_brutto = 0;
	$ergebnis = mysql_query("SELECT * FROM jobs_positionen WHERE job_id =$id->id");
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
	
	$summe_einnahmen_netto += $summe_netto;
	foreach($summe_umst as $umstsatz => $umst) {$summe_einnahmen_umst += $umst;} // Summe UmSt 7% + UmSt 19%
	$summe_einnahmen_brutto += $summe_brutto;
} ?>
<tr>
	<td></td>
	<td></td>
	<td class="numeric">Netto</td>
	<td class="numeric">UmSt</td>
	<td class="numeric">Brutto</td>
</tr>

<tr class="data_row">
	<td style="border-style: dotted" class="arrow">11</td>
	<td class="data" name="einnameart">Einnahmen</td>
	<td class="euro numeric" name="einnahmen_netto"><?=ZahlZuEur($summe_einnahmen_netto) ?></td>
	<td class="euro numeric" name="einnahmen_umst"><?=ZahlZuEur($summe_einnahmen_umst) ?></td>
	<td class="euro numeric" name="einnahmen_brutto"><?=ZahlZuEur($summe_einnahmen_brutto) ?></td>
</tr>

<tr>
	<td></td>
	<td class="numeric"><b>Summe Einnahmen</b></td>
	<td class="numeric"><b><?=ZahlZuEur($summe_einnahmen_netto) ?></b></td>
	<td class="numeric"><b><?=ZahlZuEur($summe_einnahmen_umst) ?></b></td>
	<td class="numeric"><b><?=ZahlZuEur($summe_einnahmen_brutto) ?></b></td>
</tr>

<tr>
	<td colspan="5">&nbsp;</td>
</tr>

<tr>
	<td></td>
	<td></td>
	<td class="numeric">Netto</td>
	<td class="numeric">UmSt</td>
	<td class="numeric">Brutto</td>
</tr>

<?php
$search_kosten = str_replace('WHERE ','(',$search_ausgaben).')';
$summe_ausgaben_netto = '';
$summe_ausgaben_umst = '';
$summe_ausgaben_brutto = '';

/* Ausgaben ausgeben */
$ausgaben_kostenart = mysql_query("SELECT DISTINCT kostenart FROM ausgaben $search_ausgaben");
while($row_kostenart = mysql_fetch_object($ausgaben_kostenart)) {
	$formular_zeile = '';
	$kostenart_netto = '';
	$kostenart_umst = '';
	$kostenart_brutto = '';
	
	$ergebnis = mysql_query("SELECT kostenart, umstsatz, brutto, minderung FROM ausgaben WHERE $search_kosten AND (kostenart='$row_kostenart->kostenart')");
	while($row = mysql_fetch_object($ergebnis)) {
		$result = mysql_query("SELECT formular_zeile FROM ausgaben_kostenart WHERE kostenart='$row_kostenart->kostenart'");
		while ($zeile = mysql_fetch_object($result)) {
			$formular_zeile = $zeile->formular_zeile;
		}
		
		$netto = $row->brutto * (100 / (100 + $row->umstsatz));
		$umst = $row->brutto - $netto;
		if ($row->minderung) {
			$netto = $netto * $row->minderung / 100;
		}
		$brutto = $netto + $umst;
		
		$kostenart_netto += $netto;
		$kostenart_umst += $umst;
		$kostenart_brutto += $brutto;
	} 
	
	$summe_ausgaben_netto += $kostenart_netto;
	$summe_ausgaben_umst += $kostenart_umst;
	$summe_ausgaben_brutto += $kostenart_brutto;
?>
<tr class="data_row">
	<td style="border-style: dotted" class="arrow"><?=$formular_zeile ?></td>
	<td class="data" name="kostenart"><?=$row_kostenart->kostenart ?></td>
	<td class="euro numeric" name="ausgaben_netto"><?=ZahlZuEur($kostenart_netto) ?></td>
	<td class="euro numeric" name="ausgaben_umst"><?=ZahlZuEur($kostenart_umst) ?></td>
	<td class="euro numeric" name="ausgaben_brutto"><?=ZahlZuEur($kostenart_brutto) ?></td>
</tr>
<?php } ?>
<tr>
	<td></td>
	<td class="numeric"><b>Summe Ausgaben</b></td>
	<td class="numeric" name="abz_netto"><b><?=ZahlZuEur($summe_ausgaben_netto) ?></b></td>
	<td class="numeric" name="umst"><b><?=ZahlZuEur($summe_ausgaben_umst) ?></b></td>
	<td class="numeric" name="brutto"><b><?=ZahlZuEur($summe_ausgaben_brutto) ?></b></td>
</tr>
</body>
<tfoot>
<tr>
	<td colspan="5">&nbsp;</td>
</tr>

<tr>
	<td style="border-bottom:1px solid">&nbsp;</td>
	<td style="border-bottom:1px solid">&nbsp;</td>
	<td class="numeric" style="border-bottom:1px solid">Netto</td>
	<td class="numeric" style="border-bottom:1px solid">UmSt</td>
	<td class="numeric" style="border-bottom:1px solid">Brutto</td>
</tr>
<tr>
	<td></td>
	<td class="numeric"><b>Gewinn</b></td>
	<td class="numeric" name="abz_netto"><b><?=ZahlZuEur($summe_einnahmen_netto - $summe_ausgaben_netto) ?></b></td>
	<td class="numeric" name="umst"><b><?=ZahlZuEur($summe_einnahmen_umst - $summe_ausgaben_umst) ?></b></td>
	<td class="numeric" name="brutto"><b><?=ZahlZuEur($summe_einnahmen_brutto - $summe_ausgaben_brutto) ?></b></td>
</tr>
</tfoot>
</table>
</body>
</html>