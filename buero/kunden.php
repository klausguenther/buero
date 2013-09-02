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
<script type="text/javascript" src="includes/jquery-1.9.1.js"></script>
<script type="text/javascript" src="includes/delete.js"></script>
<script type="text/javascript" src="includes/kontextmenue.js"></script>
<script type="text/javascript" src="includes/buero.js"></script>
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
while($row = mysql_fetch_object($ergebnis))
{
	echo '<tr id="'.$row->id.'"class="data_row">
		<td style="border-style:dotted" class="arrow"></td>
		<td class="data" name="kundenkuerzel">'.$row->kundenkuerzel.'</td>
		<td class="data" name="firma">'.$row->firma.'</td>
		<td class="data" name="name">'.$row->name.'</td>
		<td class="data" name="vorname">'.$row->vorname.'</td>
		<td class="data" name="strasse">'.$row->strasse.'</td>
		<td class="data" name="plz">'.$row->plz.'</td>
		<td class="data" name="ort">'.$row->ort.'</td>
		<td class="data" name="anrede">'.$row->anrede.'</td>
		</tr>';
}
?>
	<tr id="new" class="new_col">
		<td class="arrow">+</td>
		<td class="data" name="kundenkuerzel"></td>
		<td class="data" name="firma"></td>
		<td class="data" name="name"></td>
		<td class="data" name="vorname"></td>
		<td class="data" name="strasse"></td>
		<td class="data" name="plz"></td>
		<td class="data" name="ort"></td>
		<td class="data" name="anrede"></td>
	</tr>
</tbody>
</table>
</body>
</html>
