<?php
session_start();
$_SESSION['tabelle'] = 'ausgaben_suche';

include_once 'includes/dbzugang.php';
?>
<html>
<head>
<meta charset="utf-8">
<?php RGB(0,0,0)?>
<script language="JavaScript">
<!-- 
var tabelle = 'ausgaben_suche';
var job_id;
//-->
</script>
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/kontextmenue.js"></script>
<script type="text/javascript" src="js/auswahl_funktionen.js"></script>
<script type="text/javascript" src="js/suche.js"></script>
<script type="text/javascript" src="js/buero.js"></script>
<style type="text/css">
<!--
* {font-style:italic;}
-->
</style>
</head>
<body>
<div class="arrow"><?=LeftArrow('ausgaben.php'); ?></div>
<a href="#" onmouseup="suche();"><h1>Ausgaben Suche</h1></a>
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
<?php $tabindex = 1; ?>
	<tr id="new" class="new_col">
		<td class="arrow">+</td>
		<td class="data datum" name="datum" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data" name="empfaenger" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data" name="verwendungszweck" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data dropdown" name="kostenart" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data numeric" name="minderung" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data numeric" name="umstsatz" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data numeric" name="brutto" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
	</tr>
</tbody>
</table>
</body>
</html>