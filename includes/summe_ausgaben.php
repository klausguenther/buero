<?php
if (!isset($_SESSION)) session_start();

include_once 'dbzugang.php';

$search_ausgaben = $_SESSION['search_ausgaben'];

$summe_netto = 0;
$summe_umst = 0;
$summe_brutto = 0;

$ergebnis = mysql_query("SELECT umstsatz, brutto, minderung FROM ausgaben $search_ausgaben");
while($row = mysql_fetch_object($ergebnis))
{
	$netto = $row->brutto * (100 / (100 + $row->umstsatz));
	
	if ($row->minderung) {$summe_netto += $netto * $row->minderung / 100;} else {$summe_netto += $netto;}
	$summe_umst += $row->brutto - $netto;
	$summe_brutto += $row->brutto;
}
?>
 
<tfoot class="summe">
<tr>
<td class="data numeric" colspan="7">abzugsfähige Ausgaben</td>
<td class="data numeric"><?=ZahlZuEur($summe_netto) ?></td>
</tr>	
<tr>
<td class="data numeric" colspan="7">gezahlte UmSt</td>
<td class="data numeric"><?=ZahlZuEur($summe_umst) ?></td>
</tr>
<tr>
<td class="data numeric" colspan="7"><b>Summe Ausgaben</b></td>
<td class="data numeric"><b><?=ZahlZuEur($summe_brutto) ?></b></td>
</tr>
</tfoot>