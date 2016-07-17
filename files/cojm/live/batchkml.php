<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

include "C4uconnect.php";

 error_reporting(E_ALL);

 $cyclistid="createkml";
 $linecoord='';
 $prevts='';
 $errorjobs='';
 $arkml=array();
 $foundjobs='0';
 $cached=array();

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
$cached[]  = file_get_contents($testfile);
}
}  
 
$kml = array('<?xml version="1.0" encoding="UTF-8"?>');
$kml[] = ' <kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2">';

$kml[] = '<Folder>
	<name>'.$projectname.' '.$globalprefrow['globalshortname'].' GPS Tracking</name>
	<open>1</open>';
	

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
 
 
 $kml[] = '</description>   
<LookAt>
     <longitude>'.$globalprefrow['glob2'].'</longitude>
      <latitude>'.$globalprefrow['glob1'].'</latitude>  
      <altitude>3000</altitude>
     <heading>0</heading>
     <tilt>10</tilt>
	 <range>3500</range>
      <altitudeMode>relativeToGround</altitudeMode>
    </LookAt>';
	
	
	
	
	
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

  
  
  
  
  
  
  
  
  
  
// PROJECT NAME / AREA NAME




	

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
$kml[] = ' <Placemark><name>'.$trow['opsname'].'</name> ';
} else { 
$kml[] = ' <Placemark><name> Areas </name> '; }

if ($arrlength>'1') {


$kml[] = '<description>';
$areadescout = join("\n", $areadesc);
$kml[] = $areadescout;
$kml[] = ' </description>';

}


$kml[] = '<LookAt>
     <longitude>'.$tempa.'</longitude>
      <latitude>'.$tempb.'</latitude>  
      <altitude>3000</altitude>
     <heading>0</heading>
     <tilt>10</tilt>
	 <range>3500</range>
      <altitudeMode>relativeToGround</altitudeMode>
    </LookAt>';





$kml[] =  '<styleUrl>#Lump</styleUrl>
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



$kml[] = $arkmlout. '</Polygon>
</Placemark>';

	
}



 
 
 
 
 
 
 
 
 $tcached = join("\n", $cached);
 
 $kml[] =  $tcached;
 
 
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
// header('Content-Disposition:attachment; filename="'.$projectname.'-'.$globalprefrow['globalshortname'].'-tracking-'.date("U").'.kmz"');
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