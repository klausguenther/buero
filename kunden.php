<?php
session_start();
$_SESSION['tabelle'] = 'kunden';

include_once 'includes/dbzugang.php';
?>
<html>
<head>
<meta charset="utf-8">
<?php RGB(255,115,0)?>
<style type="text/css">
 td {
 	max-width:150px;
    overflow:hidden;
    text-overflow:ellipsis;
 	white-space:nowrap;
 }
</style>
<script language="JavaScript">
<!-- 
var tabelle = 'kunden';
var job_id;
//-->
</script>
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/kontextmenue.js"></script>
<script type="text/javascript" src="js/buero.js"></script>
</head>
<body>
<div class="arrow" style="border-style:dotted;"></div>
<h1>Kunden</h1>
<table>
<thead>
	<tr>
		<td>&nbsp;</td>
		<td>Kunde</td>
		<td>Firma</td>
		<td>Name</td>
		<td>Vorname</td>
		<td>Stra&szlig;e</td>
		<td>PLZ</td>
		<td>Ort</td>
		<td>Anrede</td>
	</tr>
</thead>
<tbody>
<?php
$ergebnis = mysql_query("SELECT * FROM kunden ORDER BY kundenkuerzel ASC");
$tabindex = 1;
while($row = mysql_fetch_object($ergebnis))
{
?>
<tr id="<?=$row->id?>"class="data_row">
	<td style="border-style:dotted" class="arrow"></td>
	<td class="data" name="kundenkuerzel" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->kundenkuerzel?></td>
	<td class="data" name="firma" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->firma?></td>
	<td class="data" name="name" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->name?></td>
	<td class="data" name="vorname" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->vorname?></td>
	<td class="data" name="strasse" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->strasse?></td>
	<td class="data" name="plz" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->plz?></td>
	<td class="data" name="ort" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->ort?></td>
	<td class="data" name="anrede" contenteditable="true" tabindex="<?=$tabindex++?>"><?=$row->anrede?></td>
</tr>
<?php
}
?>
<tr id="new" class="new_col">
	<td class="arrow">+</td>
	<td class="data" name="kundenkuerzel" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="firma" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="name" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="vorname" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="strasse" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="plz" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="ort" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	<td class="data" name="anrede" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
</tr>
</tbody>
</table>
</body>
</html>
