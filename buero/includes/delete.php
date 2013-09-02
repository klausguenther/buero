<?php
session_start();

/* Zugangsdaten */
include_once "dbzugang.php";

/* Variablen uebernehmen */
$tabelle = $_SESSION['tabelle'];
$id = $_REQUEST['id'];

/* Datensatz loeschen */
if ($tabelle == 'jobs') {
	$ergebnis = mysql_query("DELETE FROM jobs_positionen WHERE job_id='$id'");
	// hier fehlt noch löschen von jobs_positionen > jobs_stunden
}
if ($tabelle == 'jobs_positionen') {
	$ergebnis = mysql_query("DELETE FROM jobs_stunden WHERE jobs_positionen_id='$id'");
}
$ergebnis = mysql_query("DELETE FROM $tabelle WHERE id='$id'");

echo $id;
?>