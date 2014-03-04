<?php
session_start();
$_SESSION['tabelle'] = 'ausgaben';

include_once 'includes/dbzugang.php';

if (isset($_SESSION['search_ausgaben'])) {
	$search_ausgaben = $_SESSION['search_ausgaben'];
} else {
	$_SESSION['search_ausgaben'] = $search_ausgaben = "WHERE (datum > '".date('Y')."-00-00') OR (datum IS NULL) OR (datum = '0000-00-00')";
}
if (isset($_REQUEST['search'])) {
	if ($_REQUEST['search'] == 'WHERE ') {
		$_SESSION['search_ausgaben'] = $search_ausgaben = "WHERE (datum > '".date('Y')."-00-00') OR (datum IS NULL) OR (datum = '0000-00-00')";
	} else {
		$search_ausgaben = urldecode($_REQUEST['search']);
		$search_ausgaben = str_replace("\'", "'", $search_ausgaben);
		$_SESSION['search_ausgaben'] = $search_ausgaben;
	}
}
?>
<html>
<head>
<meta charset="utf-8">
<?php RGB(0,0,0)?>
<script language="JavaScript">
<!-- 
var tabelle = 'ausgaben';
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
<a href="ausgaben_suche.php"><h1>Ausgaben</h1></a>
<table>
<thead>
	<tr>
		<td>&nbsp;</td>
		<td>Datum</td>
		<td>Empf&auml;nger</td>
		<td>Verwendungszweck</td>
		<td style="width:130px;">Kostenart</td>
		<td class="numeric">AfA/Abzug</td>
		<td class="numeric">UmStSatz</td>
		<td class="numeric">Brutto Summe</td>
	</tr>
</thead>
<tbody>
<?php
/* Daten ausgeben */
$ergebnis = mysql_query("SELECT * FROM ausgaben $search_ausgaben ORDER BY datum ASC");
$tabindex = 1;
while($row = mysql_fetch_object($ergebnis))
{
?>
	<tr id="<?=$row->id ?>"class="data_row">
	<td style="border-style: dotted" class="arrow"></td>
	<td class="data datum" name="datum" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuDatum($row->datum) ?></td>
	<td class="data" name="empfaenger" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->empfaenger ?></td>
	<td class="data" name="verwendungszweck" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->verwendungszweck ?></td>
	<td class="data dropdown" name="kostenart" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->kostenart ?></td>
	<td class="data numeric" name="minderung" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuPro($row->minderung) ?></td>
	<td class="data numeric" name="umstsatz" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuPro($row->umstsatz) ?></td>
	<td class="data euro numeric" name="brutto" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuEur($row->brutto) ?></td>
	</tr>
<?php } ?>	
	<tr id="new" class="new_col">
		<td class="arrow">+</td>
		<td class="data datum" name="datum" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data" name="empfaenger" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data" name="verwendungszweck" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data dropdown" name="kostenart" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data numeric" name="minderung" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data numeric" name="umstsatz" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data euro numeric" name="brutto" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	</tr>
</tbody>
<?php include_once 'includes/summe_ausgaben.php'; // <tfoot> ?>
</table>
</body>
</html>