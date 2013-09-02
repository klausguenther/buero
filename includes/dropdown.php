<?php
include_once 'dbzugang.php';

echo '<div class="dropdown"><ul>';
$ergebnis = mysql_query("SELECT kundenkuerzel FROM kunden");
while($row = mysql_fetch_object($ergebnis))
{
	echo '<li class="dropdown">'.$row->kundenkuerzel.'</li>';
}
echo '<li><a href="kunden.php"><i>Kunden</i></a></li></ul></div>';
?>