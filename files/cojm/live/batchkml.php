<?php 

/*
    COJM Courier Online Operations Management
	batchkml.php - Outputs tracking / map in kml/z format for google earth
    Copyright (C) 2016 S.Young cojm.co.uk

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

include "C4uconnect.php";

 error_reporting(E_ALL);

 $cyclistid="createkml";
 $linecoord='';
 $prevts='';
 $errorjobs='';
 $arkml=array();
 $foundjobs='0';
 $cached=array();
 $found=0;

$gpxarray = unserialize($_POST['gpxarray']);
$areagpxarray   = unserialize($_POST['areagpxarray']);
$sareagpxarray  = unserialize($_POST['sareagpxarray']);
$projectname=$_POST['projectname'];

// print_r($gpxarray);
if ($projectname=='')  { $projectname==''; }

$arrlength = count($areagpxarray);
 
reset($gpxarray);
while (list(, $id) = each($gpxarray)) {
$query="SELECT ID, ShipDate, status, collectiondate FROM Orders WHERE Orders.publictrackingref = '$id' LIMIT 0,1";
$result=mysql_query($query, $conn_id); $orow=mysql_fetch_array($result);
$testfile="cache/jstrack/".date('Y', strtotime($orow['ShipDate']))."/".date('m', strtotime($orow['ShipDate']))."/".$orow['ID'].'tracks.kml';
if (!file_exists($testfile)) { $errorjobs++; } else { 
$foundjobs++;
$foundid=$id;
$cached[]  = file_get_contents($testfile);
}
}  
 
$kml = array('<?xml version="1.0" encoding="UTF-8"?>');
$kml[] = ' <kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2">';

$kml[] = '<Folder>
	<name>'.$projectname.' '.$globalprefrow['globalshortname'].' GPS Tracking</name>
	<open>1</open>';

	
	
	$kml[] = '<Style id="Lump">
<LineStyle><color>CD55ee44</color><width>10</width></LineStyle>
<PolyStyle><color>C45F9EA0</color></PolyStyle>
	 <BalloonStyle>
      <displayMode>hide</displayMode>
    </BalloonStyle>
</Style>
<Style id="Path">
<LineStyle><color>FF000000</color><width>50</width></LineStyle>
</Style>';	
	
	
	
$kml[] = ' <Style id="linebikestyle"><LineStyle> '.$globalprefrow['clweb4'].' </LineStyle></Style>';
$kml[] = ' <Style id="normalState">
    <IconStyle>
      <scale>0.8</scale>
<Icon> <href>'.$globalprefrow['clweb3'].'</href></Icon>
    </IconStyle>
    <LabelStyle>
      <scale>0</scale>
    </LabelStyle>
  </Style>
  <Style id="highlightState">
    <IconStyle>
	<scale>1.1</scale>
<Icon><href>'.$globalprefrow['clweb3'].'</href></Icon>
    </IconStyle>
    <LabelStyle>
      <color>ffffffff</color>
	      <scale>1.1</scale>
    </LabelStyle>
  </Style>
  <StyleMap id="cojmstyle">
    <Pair>
      <key>normal</key>
      <styleUrl>#normalState</styleUrl>
    </Pair>
    <Pair>
      <key>highlight</key>
      <styleUrl>#highlightState</styleUrl>
    </Pair>
  </StyleMap>';

  
	
	
	
// stats

$kml[] = '<description> ';
 
 
if ($errorjobs) { $kml[] = $errorjobs.' Errors, check web view for error messages. <br />'; 
 $projectname='ERRORS-'.$projectname;
}


if ($foundjobs=='1') { 
 $kml[] = $foundjobs.' Track '; 
} else {
 $kml[] = $foundjobs.' Tracks '; 
}

 if ($arrlength=='1') { 
 $kml[] = $arrlength.' Area '; 
 }
if ($arrlength>'1') { 
 $kml[] = $arrlength.' Areas '; 
}
 
 
 $kml[] = '</description>   ';
 
 
 
 
 
 
 
 
 
 
 // move to below
 

	
	
	
	
	

	
	
	
	
	
	
	
	
	
// PROJECT NAME / AREA NAME


$totlon=0;
$totlat=0;
$i=0;
$areakml=array();
	

// $kml[] = ' '.$arrlength.' areas found ';	
$areagpxarray = array_values($areagpxarray);
$areax = '0';
$areadesc=array();
$areacoord=array();


while ( $areax < ($arrlength)) {
$areaid=$areagpxarray[$areax];
$btmareaquery = "SELECT opsname, descrip FROM opsmap WHERE opsmapid='".$areaid."' "; 
$btmareaqueryres = mysql_query ($btmareaquery, $conn_id); 
$trow=mysql_fetch_array($btmareaqueryres);
if ($trow['opsname']) { // starts area stuff
$areadesc[]=$trow['opsname'];
$result = mysql_query("SELECT AsText(g) AS POLY FROM opsmap WHERE opsmapid=".$areaid);
if (mysql_num_rows($result)) {
	$areacoord='';
    $score = mysql_fetch_assoc($result);
	$p=$score['POLY'];
$trans = array("POLYGON" => "", "((" => "", "))" => "");
$p= strtr($p, $trans);
$pexploded=explode( ',', $p );
foreach ($pexploded as $v) {
$transf = array(" " => ",");
$v= strtr($v, $transf);
	$vexploded=explode( ',', $v );
	$tmpi='0';
	foreach ($vexploded as $testcoord) {
	if ($tmpi % 2 == 0) {
	$holdforasec=$testcoord; 
	$tempb=$holdforasec;
	} 
else	{
$areacoord[]=$testcoord.','.$holdforasec.',0.0 ';

$totlon=$totlon+$testcoord;
$totlat=$totlat+$holdforasec;
$i++;

$tempa=$testcoord;
	}
$tmpi++;
	} // ends every single coord loop
}
}
$areacoordout = join("\n", $areacoord);

$arkml[] = '<innerBoundaryIs><LinearRing><coordinates>';
$arkml[]=$areacoordout;
$arkml[] = '</coordinates></LinearRing></innerBoundaryIs>';
} // ends check for area name
$areax++;
} // ends area loop



if ($arrlength) {
if ($arrlength=='1') {
$areakml[] = ' <Placemark><name>'.$trow['opsname'].'</name> ';
} else { 
$areakml[] = ' <Placemark><name> Areas </name> '; }

if ($arrlength>'1') {


$areakml[] = '<description>';
$areadescout = join("\n", $areadesc);
$areakml[] = $areadescout;
$areakml[] = ' </description>';

}

// $totlon=$totlon+$testcoord;
// $totlat=$totlat+$holdforasec;
// $i++;

$areakml[] = '<LookAt>
     <longitude>'.($totlon/$i).'</longitude>
      <latitude>'.($totlat/$i).'</latitude>  
      <altitude>3000</altitude>
     <heading>0</heading>
     <tilt>10</tilt>
	 <range>3500</range>
      <altitudeMode>relativeToGround</altitudeMode>
    </LookAt>
	<styleUrl>#Lump</styleUrl>
<Polygon>
<tessellate>1</tessellate>
<altitudeMode>clampToGround</altitudeMode>
<outerBoundaryIs><LinearRing><coordinates>
 180,85 
  90,85 
   0,85 
 -90,85 
-180,85 
-180,0 
-180,-85 
 -90,-85 
   0,-85 
  90,-85 
 180,-85 
 180,0 
 180,85 
</coordinates></LinearRing></outerBoundaryIs>';

$arkmlout = join("\n", $arkml);

$areakml[] = $arkmlout. '</Polygon>
</Placemark>';
}


 $kml[] = '<LookAt>';
 
if ($i>0) { // use area average for lookat

$kml[] = '
     <longitude>'.($totlon/$i).'</longitude>
      <latitude>'.($totlat/$i).'</latitude>  ';

} else { // get lat lon of gps tracking to use


$query="SELECT trackerid, starttrackpause, finishtrackpause, collectiondate, ShipDate, status
 FROM Orders, Cyclist WHERE Orders.CyclistID = Cyclist.CyclistID AND Orders.publictrackingref =:getid LIMIT 0,1";
 
$sth = $dbh->prepare($query);
$sth->bindParam(':getid', $foundid, PDO::PARAM_INT); 
$sth->execute();

$found = $sth->rowCount();
$row = $sth->fetch();
$trackerid=$row['trackerid'];
$startpause=strtotime($row['starttrackpause']); $finishpause=strtotime($row['finishtrackpause']);  
$collecttime=strtotime($row['collectiondate']); $delivertime=strtotime($row['ShipDate']);
if (($startpause > 10) and ( $finishpause < 10)) { $delivertime=$startpause; }
if ($startpause <10) { $startpause=9999999999; }
if (($row['status']<86) and ($delivertime < 200)) { $delivertime=9999999999; }
if ($row['status']<50) { $delivertime=0; }
if ($collecttime < 10) { $collecttime=9999999999;}

$sql = "
SELECT longitude, latitude FROM `instamapper` 
WHERE `device_key` = '$trackerid' 
AND `timestamp` > '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' 
AND '$finishpause' 
AND `timestamp` < '$delivertime' 
ORDER BY `timestamp` ASC LIMIT 0,1";

$sth = $dbh->prepare($sql);
$sth->execute();







while($map = $sth->fetch(/* PDO::FETCH_ASSOC */)) {
	
 
 $kml[] = '
     <longitude>'.$map['longitude'].'</longitude>
      <latitude>'.$map['latitude'].'</latitude>  ';  

}


	  }



if ($found<1) { 


 $kml[] = '    <longitude>'.$globalprefrow['glob2'].'</longitude>
      <latitude>'.$globalprefrow['glob1'].'</latitude>  
';

}
	
	
	
	
	  $kml[] = '
      <altitude>3000</altitude>
     <heading>0</heading>
     <tilt>10</tilt>
	 <range>3500</range>
      <altitudeMode>relativeToGround</altitudeMode>
    </LookAt>';





$areakmlc = join("\n", $areakml);
$kml[] = $areakmlc;



 $tcached = join("\n", $cached);
 $kml[] =  $tcached;
 
 
 
 
 
 $kml[] = '     <ScreenOverlay>
        <name>'.$globalprefrow['globalshortname'].'</name>
        <visibility>1</visibility>
        <Icon><href>'.($globalprefrow['adminlogoabs']).'</href></Icon>
        <overlayXY x="0" y="-1" xunits="fraction" yunits="fraction"/>
        <screenXY x="0" y="0" xunits="fraction" yunits="fraction"/>
        <rotationXY x="0" y="0" xunits="fraction" yunits="fraction"/>
        <size x="0" y="0" xunits="fraction" yunits="fraction"/>
      </ScreenOverlay>';
 
 
 
 
 
 
 
 
$kml[] = '</Folder>';
$kml[] = '</kml>';
$kmlOutput = join("\n", $kml);

$projectname = strtoupper(str_replace(' ','-',$projectname)); 
$projectname = strtoupper(str_replace("'",'-',$projectname)); 
$projectname = strtoupper(str_replace('"','-',$projectname)); 

if($_REQUEST['btn_submit']=="kmz") {
// if ($outputtype=='kmz') { 


if ($projectname<>'') {

$filename=$projectname.'-'.$globalprefrow['globalshortname'].'-Tracking.kmz';
} else {
$filename=$globalprefrow['globalshortname'].'-Tracking-'.date("U").'.kmz';

}

 header('Content-type: application/vnd.google-earth.kmz');
 header('Content-Disposition:attachment; filename="'.$filename.'"');


$file = "cache/tempfile.kmz";
$zip = new ZipArchive();

if ($zip->open($file, ZIPARCHIVE::CREATE)!==TRUE) {
exit("cannot open <$file>\n");
}
$zip->addFromString("doc.kml", $kmlOutput);
$zip->close();
echo file_get_contents($file);

} 

if($_REQUEST['btn_submit']=="kml") {

if ($projectname<>'') {

$filename=$projectname.'-'.$globalprefrow['globalshortname'].'-Tracking.kml';
} else {
$filename=$globalprefrow['globalshortname'].'-Tracking-'.date("U").'.kml';

}

 header('Content-type: application/vnd.google-earth.kml+xml');
 header('Content-Disposition:attachment; filename="'.$filename.'"');

echo $kmlOutput;

}

?>