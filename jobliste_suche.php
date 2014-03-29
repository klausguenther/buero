<?php
session_start();
$_SESSION['tabelle'] = 'jobs_suche';

include_once 'includes/dbzugang.php';

?>
<html>
<head>
<meta charset="utf-8">
<?php RGB(0,0,0)?>
<script language="JavaScript">
<!-- 
var tabelle = 'jobs_suche';
//-->
</script>
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/delete.js"></script>
<script type="text/javascript" src="js/kontextmenue.js"></script>
<script type="text/javascript" src="js/suche.js"></script>
<script type="text/javascript" src="js/buero.js"></script>
<style type="text/css">
<!--
* {font-style:italic;}
-->
</style>
</head>
<body>
<div class="arrow"><?=LeftArrow('jobliste.php'); ?></div>
<a href="#" onmouseup="suche();"><h1>Jobliste Suche</h1></a>
<table>
<thead>
	<tr>
		<td></td>
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
<?php $tabindex = 1; ?>
	<tr id="new" class="new_col">
		<td class="arrow" style="border:1px dotted;">+</td>
		<td class="data dropdown" name="kunde" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data" name="job" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data datum" name="abgabe" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data numeric" name="rechnungsnr" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data datum" name="rechnungsdatum" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<td class="data datum" name="abschluss" contenteditable="true" tabindex="<?=$tabindex++?>"></td>
		<!-- <td class="data numeric" name="brutto" contenteditable="true" tabindex="<? // $tabindex++?>"></td> -->
		<td style="border:1px dotted;"></td>
	</tr>
</tbody> 
</table>
</body>
</html>