<?php
// aktuellen Timer stoppen
$timestamp = time();
$datum = date("d.m.Y",$timestamp);
$zeit = date("H:i",$timestamp);
$result = mysql_query("SELECT id FROM jobs_stunden WHERE (NOT start_datum = '0000-00-00') AND (NOT start_zeit = '00:00:00') AND end_datum = '0000-00-00' AND end_zeit = '00:00:00'");
while ($row = mysql_fetch_object($result)) {
	if ($row->id != $id) {
		mysql_query("UPDATE jobs_stunden SET end_datum='$datum', end_zeit='$zeit' WHERE id='$row->id'");
	}
}
?>