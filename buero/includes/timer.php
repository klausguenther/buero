<?php
/* Setzt oder beendet den Timer */
date_default_timezone_set('Europe/Berlin');
include_once 'dbzugang.php';

$id = $_REQUEST['id'];
$jobs_positionen_id = $_REQUEST['jobs_positionen_id'];
$timestamp = time();
$datum = date("Y.m.d",$timestamp);
$zeit = date("H:i:00",$timestamp);

// aktuellen Timer stoppen
$result = mysql_query("SELECT id FROM jobs_stunden WHERE (NOT start_datum = '0000-00-00') AND (NOT start_zeit = '00:00:00') AND end_datum = '0000-00-00' AND end_zeit = '00:00:00'");
while ($row = mysql_fetch_object($result)) {
	if ($row->id != $id) {
		mysql_query("UPDATE jobs_stunden SET end_datum='$datum', end_zeit='$zeit' WHERE id='$row->id'");
	}
}
// neuen Timer erzeugen
if ($id == 'new') {
	$result = mysql_query("INSERT INTO jobs_stunden (id) VALUES ('')");
	$new_id = mysql_insert_id();
	mysql_query("UPDATE jobs_stunden SET jobs_positionen_id='$jobs_positionen_id', start_datum='$datum', start_zeit='$zeit' WHERE id='$new_id'");
} else {
	$result = mysql_query("SELECT * FROM jobs_stunden WHERE id ='$id'");
	$row = mysql_fetch_object($result);
	if ($row->start_datum == '0000-00-00' || $row->start_zeit == '00:00:00') {
		if ($row->start_datum == '0000-00-00') {
			mysql_query("UPDATE jobs_stunden SET start_datum='$datum' WHERE id='$id'");
		}
		if ($row->start_zeit == '00:00:00') {
			mysql_query("UPDATE jobs_stunden SET start_zeit='$zeit' WHERE id='$id'");
		}
	} else {
		if ($row->end_datum == '0000-00-00') {
			mysql_query("UPDATE jobs_stunden SET end_datum='$datum' WHERE id='$id'");
		}
		if ($row->end_zeit == '00:00:00') {
			mysql_query("UPDATE jobs_stunden SET end_zeit='$zeit' WHERE id='$id'");
		}
	}
}
?>