<?php
session_start();
$_SESSION['tabelle'] = 'jobs_stunden';
$jobs_positionen_id = $_REQUEST['id'];

include_once 'includes/dbzugang.php';
include_once 'includes/summe_jobs_stunden.php';
?>

<html>
<head>
<meta charset="utf-8">
<?php RGB(255,0,0)?>
<script language="JavaScript">
<!-- 
var tabelle = 'jobs_stunden';
var job_id = <?php echo $jobs_positionen_id; ?>;
//-->
</script>
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/kontextmenue.js"></script>
<script type="text/javascript" src="js/buero.js"></script>
<script type="text/javascript" src="js/auswahl_funktionen.js"></script>
</head>
<body onload="$('.clock').click(ToggleTimer);">
<?php	
/* Kopf ausgeben */
$ergebnis = mysql_query("SELECT job_id, position FROM jobs_positionen WHERE id =$jobs_positionen_id");
$row = mysql_fetch_object($ergebnis);
$job_id = $row->job_id;
$jobs_position = $row->position;

$ergebnis = mysql_query("SELECT kunde, job FROM jobs WHERE id =$job_id");
$row = mysql_fetch_object($ergebnis);

echo '<div class="arrow">'.LeftArrow('jobzettel.php','id',$job_id).'</div>';
echo '<h1>Zeiterfassung '.$row->kunde.'_'.$row->job.' '.$jobs_position.'</h1>';
?>
<table>
<thead>
<tr>
	<td class="arrow"></td>
	<td>Beginn</td>
	<td>Zeit</td>
	<td>Ende</td>
	<td>Zeit</td>
	<td>Beschreibung</td>
	<td class="numeric">Stunden</td>
</tr>
</thead>
<tbody>
<?php
// Daten ausgeben
$ergebnis = mysql_query("SELECT * FROM jobs_stunden WHERE jobs_positionen_id =$jobs_positionen_id ORDER BY start_datum DESC");
$tabindex = 1;
while($row = mysql_fetch_object($ergebnis)) {
	$clock = RightArrow(0, 0, $row->id);
	if ($clock == '<svg width="18px" height="16px"></svg>') {$style = 'style="border-style:dotted"';} else {$style = '';}
	if ($row->stunden) {
		$stunden = $row->stunden;
	} else {
		$stunden = GetStd('', $row->id);
	}
?>	
	<tr id="<?=$row->id?>" class="data_row">
	<td class="arrow" <?=$style?>><?=$clock?></td>
	<td class="data datum" name="start_datum" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuDatum($row->start_datum)?></td>
	<td class="data" name="start_zeit" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuZeit($row->start_zeit)?></td>
	<td class="data datum" name="end_datum" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuDatum($row->end_datum)?></td>
	<td class="data" name="end_zeit" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuZeit($row->end_zeit)?></td>
	<td class="data" name="beschreibung" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->beschreibung?></td>
	<td class="data std numeric" name="stunden" contenteditable="true" tabindex="<?=$tabindex++?>"><?=ZahlZuStd($stunden)?></td>
	</tr>
<?php
}
?>
<tr id="new" class="new_col">
	<td class="arrow"><svg class="clock" width="18px" height="18px"><circle cx="9" cy="9" r="7" stroke="black" stroke-width="2" fill="none"/><line x1="9" y1="2" x2="9" y2="9"  stroke="black" stroke-width="2"/></svg></td>
	<td class="data" name="start_datum" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="start_zeit" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="end_datum" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="end_zeit" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="beschreibung" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data numeric" name="stunden" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
</tr>
</tbody>
<?php GetSumStdZeiterfassung($jobs_positionen_id); // tfoot ?>
</table>
</body>
</html>
