<?php
include_once 'dbzugang.php';
session_start();

if ($_SESSION['tabelle'] == 'jobs') {
echo '<div class="dropdownlist"><ul>';
	$ergebnis = mysql_query("SELECT kundenkuerzel FROM kunden");
	while($row = mysql_fetch_object($ergebnis)) {
		echo '<li class="dropdownitem">'.$row->kundenkuerzel.'</li>';
	}
	echo '<li><a href="kunden.php"><i>Kunden</i></a></li></ul></div>';
}

if ($_SESSION['tabelle'] == 'ausgaben') {
	$kostenart_ausgaben = array();
	echo '<div class="dropdownlist"><ul>';
	$ergebnis = mysql_query("SELECT kostenart FROM ausgaben_kostenart ORDER BY kostenart_order");
	while($row_kostenart = mysql_fetch_object($ergebnis)) {
		$kostenart_ausgaben[] = $row_kostenart->kostenart;
		echo '<li class="dropdownitem">'.$row_kostenart->kostenart.'</li>';
	}
	$ergebnis = mysql_query("SELECT DISTINCT kostenart FROM ausgaben");
	while($row_ausgaben = mysql_fetch_object($ergebnis)) {
		if (in_array($row_ausgaben->kostenart, $kostenart_ausgaben) == FALSE && $row_ausgaben->kostenart != '') {
			echo '<li class="dropdownitem">'.$row_ausgaben->kostenart.'</li>';
		}
	}
	echo '</ul></div>';
}
?>