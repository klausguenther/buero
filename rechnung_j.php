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
<link href='http://fonts.googleapis.com/css?family=Carrois+Gothic' rel='stylesheet' type='text/css'>
<?php RGB(0,0,0)?>
<style type="text/css">
* {margin:0px; padding:0px; font-family:'Solex','Carrois Gothic SC', sans-serif; font-size:11pt; line-height:20px;}
text {font-size:8pt;}
body {width:800px;}
@media print {.arrow {display:none;}}
.marker {position:absolute; top:410; left:34px; width:18px; height:1px; background-color:#000;}
.adresse {position:absolute; top:235; left:580px;}
.absender{position:absolute; top:245; left:79px;} .absender text{font-size:8pt;}
#anschrift {position:absolute; top:265px; left:80px; width:325px; height:150px;}
#anschrift a {display:block; text-decoration:none; vertical-align:bottom;}
#rechnungsnr {position:absolute; top:455px; left:80px; width:245px; height:20px;}
#leistungszeitraum {position:relative; width:665px; margin-top:20px;}
#rechnungsdatum {position:absolute; top:410px; left:80px; width:170px; height:20px;}
#rechnung {position:absolute; top:520px; left:80px; width:665px;}
#anrede {position:relative; top:0px; left:0px; width:740px; padding-bottom:20px;}
#position {position:relative; width:665px; margin-top:20px;}
#betrag {position:relative; float:right; width:135px; margin-top:20px; text-align:right; vertical-align:bottom;}
#summe {position:relative; margin-top:10px; padding-top:10px; width:665px; border-top:1px dotted;}
#summe td:lastchild {border-bottom:1px dotted #000;}
#fuss {position:relative; top:50px; width:740px; height:50px;}
</style>

</head>
<body style="background-image: url(tix.jpg); background-size: 850px auto; background-repeat: no-repeat;" >

<?php
if ($tabelle == 'jobs_positionen') {
	echo '<div class="arrow">'.LeftArrow('jobzettel.php','id',$job_id).'</div>';
} else {
	echo '<div class="arrow">'.LeftArrow('jobliste.php').'</div>';
}
?>
<!-- <div class="marker"></div> -->

<img src="logo.svg" style="position:absolute; top:120px; left:620; width:140px;" alt="">

<svg class="adresse" xmlns="http://www.w3.org/2000/svg"
xmlns:xlink="http://www.w3.org/1999/xlink"
xmlns:ev="http://www.w3.org/2001/xml-events"
version="1.1" baseProfile="full"
width="240px" height="270px" viewBox="0 0 240 270">

<text x="40" y="20" font-weight="bold">Jennifer Tix</text>
<text x="40" y="35" font-weight="bold">Grafikdesignerin</text>
<text x="40" y="65">Telefon 030 - 55 61 70 25</text>
<text x="40" y="80">Telefon 0176 - 21 06 40 55</text>
<text x="40" y="95">grafik@jennifertix.de</text>
<text x="40" y="110" font-weight="bold">www.jennifertix.de</text>
<text x="40" y="140">Josef-Orlopp-Str. 48</text>
<text x="40" y="155">D-10365 Berlin</text>
<text x="40" y="185">USt.ID-Nr. DE 281 284 490</text>
</svg>

<svg class="absender" xmlns="http://www.w3.org/2000/svg"
xmlns:xlink="http://www.w3.org/1999/xlink"
xmlns:ev="http://www.w3.org/2001/xml-events"
version="1.1" baseProfile="full"
width="325px" height="16px" viewBox="0 0 325 16">
<text x="0" y="12">Jennifer Tix | Josef-Orlopp-Stra&szlig;e 48 | 10365 Berlin</text>
</svg>

<div id="anschrift"><?php echo $anschrift; ?></div>
<div id="rechnungsnr"><b>Rechnung Nr. <?php echo $rechnungsnr.'_'.date('y'); ?><br><?php echo $row->job;?></b></div>
<div id="rechnungsdatum">Berlin, <?php echo ZahlZuDatum($rechnungsdatum); ?></div>

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
	if ($i == 0 && $row->position && $job) {$i++; echo '<div id="position" style="border-bottom:1px dotted;"><b>'.$job.'</b></div>';}
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
echo '<div id="leistungszeitraum">Leistungszeitraum '.$leistungszeitraum.'</div>';
echo '<div id="summe"><table>';
include_once 'includes/summe_jobs_positionen.php';
echo '</table></div>';
?>
<div id="fuss">Mit freundlichen Gr&uuml;&szlig;en, Jennifer Tix</div>
</div>
</body>
</html>
