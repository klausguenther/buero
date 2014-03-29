<?php 
session_start();
if(isset($_SESSION['tabelle'])) {$tabelle = $_SESSION['tabelle'];} else {$tabelle = '';}
if(isset($_REQUEST['job_id'])) {$job_id = $_REQUEST['job_id'];} else {$job_id = '';}

include_once 'includes/dbzugang.php';
setlocale(LC_TIME, "de_DE.UTF-8");

$ergebnis = mysql_query("SELECT * FROM jobs WHERE id =$job_id");
$row = mysql_fetch_object($ergebnis);
// Anschrift
$result = mysql_query("SELECT * FROM kunden WHERE kundenkuerzel ='$row->kunde'");
$kunde = mysql_fetch_object($result);
	if ($kunde) {
		$anschrift = '';
		if ($kunde->firma) {$anschrift .= $kunde->firma.'<br>';}
		if ($kunde->firma && ($kunde->vorname || $kunde->name)) {$anschrift .= 'z.H. ';}
		if ($kunde->vorname) {$anschrift .= $kunde->vorname.' ';}
		if ($kunde->name) {$anschrift .= $kunde->name;}
		if ($kunde->vorname || $kunde->name) {$anschrift .= '<br>';}
		if ($kunde->strasse) {$anschrift .= $kunde->strasse.'<br>';}
		if ($kunde->plz) {$anschrift .= $kunde->plz.' ';}
		if ($kunde->ort) {$anschrift .= $kunde->ort;}
		// Anrede
		$anrede = $kunde->anrede;
	} else {
		$anschrift = '<a href="kunden.php"><svg class="arrow" width="18px" height="16px"><polygon points="4,6 4,16 14,11" fill="#000" /></svg></a>'.$row->kunde;
		$anrede = '';
	}
// Rechnungsnummer
if ($row->rechnungsnr > 0) {
	$rechnungsnr = $row->rechnungsnr;
} else { // wenn leer naechst hoehere Nummer einsetzen
	$aktuelles_jahr = date("Y").'-00-00';
	$get_nummer = mysql_query("SELECT MAX(rechnungsnr) FROM jobs WHERE rechnungsdatum >'$aktuelles_jahr'");
	$max_nummer = mysql_fetch_array($get_nummer);
	$rechnungsnr = $max_nummer['MAX(rechnungsnr)'] + 1;
	mysql_query("UPDATE jobs SET rechnungsnr='$rechnungsnr' WHERE id='$job_id'");
}
// Leistungszeitraum
if ($row->abgabe == '0000-00-00' || $row->abgabe == NULL) { // wenn leer aktuelles Datum einsetzen
	$abgabe = date("Y-m-d");
	mysql_query("UPDATE jobs SET abgabe='$abgabe' WHERE id='$job_id'");
	$leistungszeitraum = strftime("%b %Y", strtotime($abgabe));
} else {
	$leistungszeitraum = strftime("%b %Y", strtotime($row->abgabe));
}
// Rechnungsdatum
if ($row->rechnungsdatum == '0000-00-00' || $row->rechnungsdatum == NULL) { // wenn leer aktuelles Datum einsetzen
	$rechnungsdatum = date("Y-m-d");
	mysql_query("UPDATE jobs SET rechnungsdatum='$rechnungsdatum' WHERE id='$job_id'");
} else {
	$rechnungsdatum = $row->rechnungsdatum;
}
?>
<!DOCTYPE svg:svg PUBLIC
"-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN"
"http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd">


<html>
<head>
<meta charset="utf-8">
<?php RGB(0,0,255)?>
<style type="text/css">
* {margin:0px; padding:0px;}
body {width:800px; font-family:Arial, Helvetica; font-size:11pt; line-height:24px;}
@media print {.arrow {display:none;}}
.marker {position:absolute; top:410; left:34px; width:18px; height:1px; background-color:#f66;}
.adresse {position:absolute; top:30; left:590px;}
.absender{position:absolute; top:180; left:59px;} .absender text{font-family:Arial, Helvetica; font-size:8pt;}
#anschrift {position:absolute; top:210px; left:60px; width:325px; height:150px;}
#anschrift a {display:block; text-decoration:none; vertical-align:bottom;}
#rechnungsnr {position:absolute; top:430px; left:60px; width:245px; height:20px;}
#leistungszeitraum {position:absolute; top:430px; left:320px; width:245px; height:20px;}
#rechnungsdatum {position:absolute; top:430px; left:630px; width:170px; height:20px;}
#rechnung {position:absolute; top:490px; left:60px; width:740px;}
#anrede {position:relative; top:0px; left:0px; width:740px; padding-bottom:20px;}
#position {position:relative; width:570px; margin-top:20px;}
#betrag {position:relative; float:right; width:135px; margin-top:20px; text-align:right; vertical-align:bottom; padding-right:25px;}
#summe {position:relative; margin-top:10px; padding-top:10px; width:715px; border-top:1px dotted;}
#summe td:lastchild {border-bottom:1px dotted #000;}
#fuss {position:relative; top:50px; width:740px; height:50px;}
</style>

</head>
<body>

<?php
if ($tabelle == 'jobs_positionen') {
	echo '<div class="arrow">'.LeftArrow('jobzettel.php','id',$job_id).'</div>';
} else {
	echo '<div class="arrow">'.LeftArrow('jobliste.php').'</div>';
}
?>
<div class="marker"></div>

<svg class="adresse" xmlns="http://www.w3.org/2000/svg"
xmlns:xlink="http://www.w3.org/1999/xlink"
xmlns:ev="http://www.w3.org/2001/xml-events"
version="1.1" baseProfile="full"
width="210px" height="270px" viewBox="0 0 210 270">
<circle fill="none" stroke="#f66" stroke-width="2px" cx="15" cy="15" r="13"/>
<circle fill="#f66" cx="15" cy="15" r="4"/>
<text x="40" y="20">Klaus G&uuml;nther</text>
<text x="40" y="40">Obentrautstra&szlig;e 53d</text>
<text x="40" y="60">10963 Berlin</text>
<text x="40" y="100">Funk 0 15 20/3 65 69 10</text>
<text x="40" y="120">mail@robor.de</text>
<text x="40" y="140">www.robor.de</text>
<text x="40" y="180">Berliner Volksbank</text>
<text x="40" y="200">BLZ 10090000</text>
<text x="40" y="220">KtNr 710839901</text>
<text x="40" y="260">StNr 322/61433</text>
</svg>

<svg class="absender" xmlns="http://www.w3.org/2000/svg"
xmlns:xlink="http://www.w3.org/1999/xlink"
xmlns:ev="http://www.w3.org/2001/xml-events"
version="1.1" baseProfile="full"
width="325px" height="16px" viewBox="0 0 325 16">
<circle fill="none" stroke="#f66" stroke-width="1px" cx="8" cy="8" r="7"/>
<circle fill="#f66" cx="8" cy="8" r="2"/>
<text x="20" y="12">Klaus G&uuml;nther | Obentrautstra&szlig;e 53d | 10963 Berlin</text>
</svg>

<div id="anschrift"><?php echo $anschrift; ?></div>
<div id="rechnungsnr"><b>Rechnung</b> Nr.: <?php echo sprintf("%05d", $rechnungsnr); ?></div>
<div id="rechnungsdatum">Berlin, <?php echo ZahlZuDatum($rechnungsdatum); ?></div>
<div id="leistungszeitraum">Leistungszeitraum <?php echo $leistungszeitraum; ?></div>

<div id="rechnung">
<div id="anrede">
<?php
if ($anrede) {
	echo $anrede;
} else {
	echo 'Sehr geehrte Damen und Herren,<br>f&uuml;r Ihren Auftrag bedanke ich mich und berechne f&uuml;r meine Leistungen:';
}?></div>
<?php
$i = 0;
$job = $row->job;
$ergebnis = mysql_query("SELECT * FROM jobs_positionen WHERE job_id =$job_id");
while($row = mysql_fetch_object($ergebnis))
{
	list ($stundenanzahl_data, $stundenanzahl, $stundensatz_data, $stundensatz,
			$umstsatz_data, $umstsatz, $netto_data, $netto, $umst_data, $umst, $brutto_data, $brutto) = Calculation($row->id);
	if ($i == 0 && $row->position && $job) {$i++; echo '<div id="position"><b>'.$job.'</b></div>';}
	if ($netto_data) {echo '<div id="betrag">'.ZahlZuEur($netto_data).'</div>';}
	echo '<div id="position">';
	$position = '';
	if ($i == 0 && $job) {$position = $job;}
	if ($row->position) {if ($position) {$position .= ', ';} $position .= $row->position;}	
	if ($row->beschreibung) {if ($position) {$position .= '<br>';} $position .= $row->beschreibung;}
	if ($stundenanzahl && $stundensatz) {if ($position) {$position .= ', ';} $position .= ZahlZuStd($stundenanzahl).'&nbsp;a&nbsp;'.ZahlZuEur($stundensatz_data);}
	if ($umstsatz_data) {if ($position) {$position .= ', ';} $position .= 'UmSt&nbsp;'.ZahlZuPro($umstsatz_data);}
	echo $position;
	echo '</div>';
	$i++;
}
echo '<div id="summe"><table>';
include_once 'includes/summe_jobs_positionen.php';
echo '</table></div>';
?>
<div id="fuss">Mit freundlichen Gr&uuml;&szlig;en, Klaus G&uuml;nther</div>
</div>
</body>
</html>
