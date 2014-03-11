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
	$search_ausgaben = "WHERE (datum LIKE '%".date('Y')."%')";
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
<tr>
	<td></td>
	<td></td>
	<td class="numeric">Netto</td>
	<td class="numeric">UmSt</td>
	<td class="numeric">Brutto</td>
</tr>
<?php
/* Einnahmen ausgeben */
$einnahmen_netto = array();
$einnahmen_umst = array();
$einnahmen_brutto = array();
$summe_einnahmen_netto = '';
$summe_einnahmen_umst = '';
$summe_einnahmen_brutto = '';
$job_id = mysql_query("SELECT id FROM jobs $search_jobliste");
while($id = mysql_fetch_object($job_id)) {
	
	$summe_umst = array();
	$summe_netto = array();
	$summe_brutto = array();
	$ergebnis = mysql_query("SELECT * FROM jobs_positionen WHERE job_id =$id->id");
	while($row = mysql_fetch_object($ergebnis)) {
	list ($stundenanzahl_data, $stundenanzahl, $stundensatz_data, $stundensatz, 
		$umstsatz_data, $umstsatz, $netto_data, $netto, $umst_data, $umst, $brutto_data, $brutto) = Calculation($row->id);
		if ($umstsatz == NULL) {$umstsatz = 0;}
		if(!isset($summe_netto[''.$umstsatz])) {$summe_netto[''.$umstsatz] = 0;}
		if(!isset($summe_umst[''.$umstsatz])) {$summe_umst[''.$umstsatz] = 0;}
		if(!isset($summe_brutto[''.$umstsatz])) {$summe_brutto[''.$umstsatz] = 0;}
		$summe_netto["".$umstsatz]+= $netto;
		$summe_umst["".$umstsatz]+= $umst;
		$summe_brutto["".$umstsatz]+= $brutto;
	} /* end while */
	
	foreach($summe_netto as $umstsatz => $netto) {
		if(!isset($einnahmen_netto[''.$umstsatz])) {$einnahmen_netto[''.$umstsatz] = 0;}
		$einnahmen_netto[''.$umstsatz]+= $netto;
	}
	foreach($summe_umst as $umstsatz => $umst) {
		if(!isset($einnahmen_umst[''.$umstsatz])) {$einnahmen_umst[''.$umstsatz] = 0;}
		$einnahmen_umst[''.$umstsatz]+= $umst;
	}
	foreach($summe_brutto as $umstsatz => $brutto) {
		if(!isset($einnahmen_brutto[''.$umstsatz])) {$einnahmen_brutto[''.$umstsatz] = 0;}
		$einnahmen_brutto[''.$umstsatz]+= $brutto;
	} 
}
$i = 0;
krsort($einnahmen_netto);
$netto_einnahmen = array();
foreach($einnahmen_netto as $key => $val) {$netto_einnahmen[$i] = $val; $summe_einnahmen_netto += $val; $i++;};
$i = 0;
krsort($einnahmen_umst);
$umst_einnahmen = array();
foreach($einnahmen_umst as $key => $val) {$umst_einnahmen[$i] = $val; $summe_einnahmen_umst += $val; $i++;};
$i = 0;
krsort($einnahmen_brutto);
$brutto_einnahmen = array();
foreach($einnahmen_brutto as $key => $val) {$brutto_einnahmen[$i] = $val; $summe_einnahmen_brutto += $val; $i++;};

$i = 0;
foreach($einnahmen_brutto as $key => $val) {
if ($key > 0) {
	$key = 'UmSt '.$key.' %';
} else {
	$key = 'ohne UmSt';
}
?>
<tr class="data_row">
<td style="border-style: dotted" class="arrow"></td>
	<td class="data" name="einnahmeart">Einnahmen <?=$key ?></td>
	<td class="euro numeric" name="einnahmen_netto"><?=AnzNull(ZahlZuEur($netto_einnahmen[$i])) ?></td>
	<td class="euro numeric" name="einnahmen_umst"><?=AnzNull(ZahlZuEur($umst_einnahmen[$i])) ?></td>
	<td class="euro numeric" name="einnahmen_brutto"><?=AnzNull(ZahlZuEur($brutto_einnahmen[$i])) ?></td>
</tr>
<?php
$i++;
}
?>
<tr>
	<td></td>
	<td class="numeric"><b>Summe Einnahmen</b></td>
	<td class="numeric"><b><?=AnzNull(ZahlZuEur($summe_einnahmen_netto)) ?></b></td>
	<td class="numeric"><b><?=AnzNull(ZahlZuEur($summe_einnahmen_umst)) ?></b></td>
	<td class="numeric"><b><?=AnzNull(ZahlZuEur($summe_einnahmen_brutto)) ?></b></td>
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
	while($row_kosten = mysql_fetch_object($ergebnis)) {
		$result = mysql_query("SELECT formular_zeile FROM ausgaben_kostenart WHERE kostenart='$row_kostenart->kostenart'");
		while ($zeile = mysql_fetch_object($result)) {
			$formular_zeile = $zeile->formular_zeile;
		}
		
		$netto = $row_kosten->brutto * (100 / (100 + $row_kosten->umstsatz));
		$umst = $row_kosten->brutto - $netto;
		if ($row_kosten->minderung) {
			$netto = $netto * $row_kosten->minderung / 100;
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
	<td class="euro numeric" name="ausgaben_netto"><?=AnzNull(ZahlZuEur($kostenart_netto)) ?></td>
	<td class="euro numeric" name="ausgaben_umst"><?=AnzNull(ZahlZuEur($kostenart_umst)) ?></td>
	<td class="euro numeric" name="ausgaben_brutto"><?=AnzNull(ZahlZuEur($kostenart_brutto)) ?></td>
</tr>
<?php } ?>
<tr>
	<td></td>
	<td class="numeric"><b>Summe Ausgaben</b></td>
	<td class="numeric" name="abz_netto"><b><?=AnzNull(ZahlZuEur($summe_ausgaben_netto)) ?></b></td>
	<td class="numeric" name="umst"><b><?=AnzNull(ZahlZuEur($summe_ausgaben_umst)) ?></b></td>
	<td class="numeric" name="brutto"><b><?=AnzNull(ZahlZuEur($summe_ausgaben_brutto)) ?></b></td>
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
	<td class="numeric" name="abz_netto"><b><?=AnzNull(ZahlZuEur($summe_einnahmen_netto - $summe_ausgaben_netto)) ?></b></td>
	<td class="numeric" name="umst"><b><?=AnzNull(ZahlZuEur($summe_einnahmen_umst - $summe_ausgaben_umst)) ?></b></td>
	<td class="numeric" name="brutto"><b><?=AnzNull(ZahlZuEur($summe_einnahmen_brutto - $summe_ausgaben_brutto)) ?></b></td>
</tr>
</tfoot>
</table>
</body>
</html>