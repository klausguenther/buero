<?php
session_start ();
$_SESSION ['tabelle'] = 'jobs_positionen';
$job_id = $_REQUEST ['id'];

include_once 'includes/dbzugang.php';
include_once 'includes/summe_jobs_positionen.php';
checkTimer ();
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
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/kontextmenue.js"></script>
<script type="text/javascript" src="js/buero.js"></script>
<script type="text/javascript" src="js/auswahl_funktionen.js"></script>
</head>
<body>
<?php
/* Kopf ausgeben */
$ergebnis = mysql_query ( "SELECT kunde, job FROM jobs WHERE id =$job_id" );
$row = mysql_fetch_object ( $ergebnis );
echo '<div class="arrow">' . LeftArrow ( 'jobliste.php' ) . '</div>';
echo '<h1>Jobzettel ' . $row->kunde . ' ' . $row->job . '</a></h1>';
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
$ergebnis = mysql_query ( "SELECT * FROM jobs_positionen WHERE job_id =$job_id ORDER BY id ASC" );
$tabindex = 1;
while ( $row = mysql_fetch_object ( $ergebnis ) ) {
	list ( $stundenanzahl_data, $stundenanzahl, $stundensatz_data, $stundensatz, $umstsatz_data, $umstsatz, $netto_data, $netto, $umst_data, $umst, $brutto_data, $brutto ) = Calculation ( $row->id );
	?>
	<tr id="<?=$row->id?>" class="data_row">
			<td style="border-style: dotted" class="arrow"></td>
			<td class="data" name="position" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->position?></td>
			<td class="data" name="beschreibung" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->beschreibung?></td>
			<td class="arrow"><?=RightArrow('', $row->id)?></td>
			<td class="data std numeric <?=Proof($stundenanzahl, $row->stundenanzahl)?>" name="stundenanzahl" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuStd($stundenanzahl)?></td>
			<td class="data euro numeric <?=Proof($stundensatz, $row->stundensatz)?>" name="stundensatz" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuEur($stundensatz_data)?></td>
			<td class="data numeric <?=Proof($umstsatz, $row->umstsatz)?>" name="umstsatz" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuPro($umstsatz_data)?></td>
			<td class="data euro numeric <?=Proof($netto, $row->netto)?>" name="netto" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuEur($netto_data)?></td>
			<td class="data euro numeric <?=Proof($umst, $row->umst)?>" name="umst" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuEur($umst_data)?></td>
			<td class="data euro numeric <?=Proof($brutto, $row->brutto)?>" name="brutto" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuEur($brutto_data)?></td>
		</tr>
<?php
}
?>
<tr id="new" class="new_col">
			<td class="arrow">+</td>
			<td class="data" name="position" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
			<td class="data" name="beschreibung" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
			<td class="arrow"></td>
			<td class="data std numeric" name="stundenanzahl" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
			<td class="data euro numeric" name="stundensatz" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
			<td class="data numeric" name="umstsatz" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
			<td class="data euro numeric" name="netto" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
			<td class="data euro numeric" name="umst" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
			<td class="data euro numeric" name="brutto" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		</tr>
<?php GetSummeJobzettel($job_id); // <tfoot> ?>
</table>
</body>
</html>