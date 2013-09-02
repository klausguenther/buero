<?php
session_start();
$_SESSION['tabelle'] = 'jobs_positionen';
$job_id = $_REQUEST['id'];

include_once 'includes/dbzugang.php';
include_once 'includes/summe_jobs_positionen.php';
checkTimer();
?>
<html>
<head>
<meta charset="utf-8">
<?php RGB(0,0,255)?>
<script language="JavaScript">
<!-- 
var tabelle = 'jobs_positionen';
var job_id = <?php echo $job_id; ?>; 
//-->
</script>
<script type="text/javascript" src="includes/jquery-1.9.1.js"></script>
<script type="text/javascript" src="includes/delete.js"></script>
<script type="text/javascript" src="includes/kontextmenue.js"></script>
<script type="text/javascript" src="includes/buero.js"></script>
<script type="text/javascript" src="includes/auswahl_funktionen.js"></script>
</head>
<body>
<?php	
/* Kopf ausgeben */
$ergebnis = mysql_query("SELECT kunde, job FROM jobs WHERE id =$job_id");
$row = mysql_fetch_object($ergebnis);
echo '<div class="arrow">'.LeftArrow('jobliste.php').'</div>';
echo '<h1>Jobzettel '.$row->kunde.' '.$row->job.'</a></h1>';
?>
<table>
<tr>
	<td class="arrow"></td>
	<td>Position</td>
	<td>Beschreibung</td>
	<td class="arrow"></td>
	<td class="numeric">Stundenanzahl</td>
	<td class="numeric">Stundensatz</td>
	<td class="numeric">UmStSatz</td>
	<td class="numeric">Netto</td>
	<td class="numeric">UmSt</td>
	<td class="numeric">Brutto</td>
</tr>
<?php
/* Daten ausgeben */
$ergebnis = mysql_query("SELECT * FROM jobs_positionen WHERE job_id =$job_id");
while($row = mysql_fetch_object($ergebnis))
{
	list ($stundenanzahl_data, $stundenanzahl, $stundensatz_data, $stundensatz, 
		$umstsatz_data, $umstsatz, $netto_data, $netto, $umst_data, $umst, $brutto_data, $brutto) = Calculation($row->id);
	echo '<tr id="'.$row->id.'" class="data_row">'; // TODO lauter echos rausnehmen
	echo '<td style="border-style:dotted" class="arrow"></td>';
	echo '<td class="data" name="position" contenteditable="true">'.$row->position.'</td>';
	echo '<td class="data" name="beschreibung" contenteditable="true">'.$row->beschreibung.'</td>';
	echo '<td class="arrow">'.RightArrow('', $row->id).'</td>';
	echo '<td class="data numeric" name="stundenanzahl" contenteditable="true"'.Proof($stundenanzahl, $row->stundenanzahl).'>'.ZahlZuStd($stundenanzahl).'</td>';
	echo '<td class="data numeric" name="stundensatz" contenteditable="true"'.Proof($stundensatz, $row->stundensatz).'>'.ZahlZuEur($stundensatz_data).'</td>';
	echo '<td class="data numeric" name="umstsatz" contenteditable="true"'.Proof($umstsatz, $row->umstsatz).'>'.ZahlZuPro($umstsatz_data).'</td>';
	echo '<td class="data numeric" name="netto" contenteditable="true"'.Proof($netto, $row->netto).'>'.ZahlZuEur($netto_data).'</td>';
	echo '<td class="data numeric" name="umst" contenteditable="true"'.Proof($umst, $row->umst).'>'.ZahlZuEur($umst_data).'</td>';
	echo '<td class="data numeric" name="brutto" contenteditable="true"'.Proof($brutto, $row->brutto).'>'.ZahlZuEur($brutto_data).'</td></tr>';
}
?>
<tr id="new" class="new_col">
	<td class="arrow">+</td>
	<td class="data" name="postion" contenteditable="true"></td>
	<td class="data" name="beschreibung" contenteditable="true"></td>
	<td class="arrow"></td>
	<td class="data numeric" name="stundenanzahl" contenteditable="true"></td>
	<td class="data numeric" name="stundensatz" contenteditable="true"></td>
	<td class="data numeric" name="umstsatz" contenteditable="true"></td>
	<td class="data numeric" name="netto" contenteditable="true"></td>
	<td class="data numeric" name="umst" contenteditable="true"></td>
	<td class="data numeric" name="brutto" contenteditable="true"></td>
</tr>
<?php GetSummeJobzettel($job_id); // <tfoot> ?>
</table>
</body>
</html>