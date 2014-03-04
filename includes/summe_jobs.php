<?php
if (!isset($_SESSION)) session_start();

include_once 'dbzugang.php';

$search_jobliste = $_SESSION['search_jobliste'];

$summe_brutto = '';
$summe_offen = '';
$ergebnis = mysql_query("SELECT id, abschluss FROM jobs $search_jobliste");
	while($row = mysql_fetch_object($ergebnis))
	{
		$brutto_cache = '';
		$result = mysql_query("SELECT brutto_cache FROM jobs_positionen WHERE job_id=$row->id"); // Brutto_Cache aus jobs_positionen holen
		if (!mysql_num_rows($result) == 0) {
			$jobs_positionen_row = mysql_fetch_object($result);
			$brutto_cache = $jobs_positionen_row->brutto_cache;
			if ($row->abschluss == '' || $row->abschluss == '0000-00-00') {
				$summe_offen += $brutto_cache;
			} else {
				$summe_brutto += $brutto_cache;
			}
		}
	}

$return = '<tfoot class="summe">';
if ($summe_brutto) {
	$return .= '<tr>';
	$return .= '<td colspan="7" class="numeric"><b>Einnahmen</b></td>';
	$return .= '<td class="numeric"><b>'.ZahlZuEur($summe_brutto).'</b></td>';
	$return .= '</tr>';
}
if ($summe_offen) {
	$return .= '<tr>';
	$return .= '<td colspan="7" class="numeric">Aussenst&auml;nde</td>';
	$return .= '<td class="numeric">'.ZahlZuEur($summe_offen).'</td>';
	$return .= '</tr>';
}
if ($summe_offen && $summe_brutto) {
	$return .= '<tr>';
	$return .= '<td colspan="7" style="text-align:right;">Summe</td>';
	$return .= '<td class="numeric">'.ZahlZuEur($summe_brutto + $summe_offen).'</td>';
	$return .= '</tr>';
}
$return .= '</tfoot>';

echo $return;
?>