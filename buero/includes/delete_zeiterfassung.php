<?php
/* Zugangsdaten */
include_once "dbzugang.php";

/* Variablen uebernehmen */
$jobs_positionen_id = $_REQUEST['id'];
$jobs_positionen_stundenanzahl = $_REQUEST['gesamtstunden'];
$jobs_positionen_stundenanzahl = strtr($jobs_positionen_stundenanzahl, "-",".");

echo $jobs_positionen_stundenanzahl;
if ($jobs_positionen_stundenanzahl) {
	$result = mysql_query("UPDATE jobs_positionen SET stundenanzahl='$jobs_positionen_stundenanzahl' WHERE id='$jobs_positionen_id'");
}
	
/* Datensaetze loeschen */
mysql_query("DELETE FROM jobs_stunden WHERE jobs_positionen_id='$jobs_positionen_id'");
?>