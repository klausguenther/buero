<?php
date_default_timezone_set('Europe/Berlin');
/* Anzeige */
// Gibt von einer Zahl die Zahl mit Stellen und Einheit zurück.

function ZahlAnz($format) { // verhindert die Rückgabe der Einheit bei 0
	if ($format != 0) {
		$format =  $format;
	} else {
		$format = '';
	}
	return $format;
}

function ZahlZuEur($zahl) { // Bsp. 10.50 €
	$format =  sprintf("%.2f", $zahl).'&nbsp;&euro;';
	$format = ZahlAnz($format);
	return $format;
}

function ZahlZuPro($zahl) { // Bsp.: 19 %
	$format =  $zahl.'&nbsp;%';
	$format = ZahlAnz($format);
	return $format;
}

function ZahlZuStd($zahl) { // Beisp.: 2:30 Std
	if ($zahl == '0' || $zahl == '') {
		return '';
	} else {
		$std = floor($zahl);
		$min = ($zahl - $std) * 60;
		$format = $std.':'.sprintf("%02d",$min).'&nbsp;Std.';
		$format = preg_replace("/:00/", "", $format);
		return $format;
	}
}

function ZahlZuDatum($zahl) { // Bsp.: 01.01.2013 Uhr
	if ($zahl == '0000-00-00' || $zahl == '') {
		return '';
	} else {
		$zahl = date_create($zahl);
		$format = date_format($zahl, 'd.m.Y');
		return $format;
	}
}

function ZahlZuZeit($zahl) { // Bsp.: 7:00 Uhr
	if ($zahl == '00:00:00' || $zahl == '') {
		return '';
	} else {
		$zeit = date_create($zahl);
		$format = date_format($zeit, 'H:i').' Uhr';
		return $format;
	}
}
?>