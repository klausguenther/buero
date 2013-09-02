<?php
date_default_timezone_set('Europe/Berlin');

/* mit Server verbinden */
$con = mysql_connect('localhost', 'root', 'klaus');
if (!$con) {
	die('Serverfehler: '.mysql_error());
}

/* Kodierung festlegen */
mysql_set_charset ( "utf8", $con);

/* mit Datenbank verbinden */
$db = mysql_select_db('buero');
if (!$db) {
	die('Datenbankfehler: '.mysql_error());
}

include_once 'funktionen.php';
include_once 'cal_jobs_stunden.php';
include_once 'format_functions.php';
include_once 'navigation_functions.php';
include_once 'css.php';
?>