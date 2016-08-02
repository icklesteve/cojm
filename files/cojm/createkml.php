<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();


include "live/C4uconnect.php";



 $cyclistid="createkml";
 $linecoord='';
 $prevts='';
$id=trim($_GET['id']); if ($id=="") { $id = trim($_POST['id']); }
 
$kml = array('<?xml version="1.0" encoding="UTF-8"?>');
$kml[] = ' <kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2">';
$kml[] = ' <Document>';
$kml[] = ' <name>'.$globalprefrow['globalshortname'].' '.$id.'</name>';
$kml[] = ' <open>1</open>';


$kml[] = ' <LookAt>
     <longitude>'.$globalprefrow['glob2'].'</longitude>
      <latitude>'.$globalprefrow['glob1'].'</latitude>  
      <altitude>5000</altitude>
     <heading>0</heading>
     <tilt>10</tilt>
	 <range>5500</range>
      <altitudeMode>relativeToGround</altitudeMode>
    </LookAt>';





$kml[] = '<Style id="Lump">
<LineStyle><color>CD55ee44</color><width>10</width></LineStyle>
<PolyStyle><color>C45F9EA0</color></PolyStyle>
</Style>
<Style id="Path">
<LineStyle><color>FF000000</color><width>50</width></LineStyle>
</Style>';

$kml[] = ' <Style id="bikeStyle">';
$kml[] = '   <LabelStyle>';
$kml[] = ' <scale>0</scale>  ';
$kml[] = '   </LabelStyle> ';
$kml[] = ' </Style>';
$kml[] = ' <Style id="linebikestyle"><LineStyle> '.$globalprefrow['clweb4'].' </LineStyle></Style>';

$kml[] = '
 <Style id="normalState">
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
	<Icon> <href>'.$globalprefrow['clweb3'].'</href></Icon>
    </IconStyle>
    <LabelStyle>
      <color>ffffffff</color>
	       <scale>1.1</scale>
    </LabelStyle>
  </Style>
  <StyleMap id="styleMapExample">
    <Pair>
      <key>normal</key>
      <styleUrl>#normalState</styleUrl>
    </Pair>
    <Pair>
      <key>highlight</key>
      <styleUrl>#highlightState</styleUrl>
    </Pair>
  </StyleMap>';


$jobid=$id;
$query="SELECT poshname, trackerid, starttrackpause, finishtrackpause, collectiondate, ShipDate, status, opsmaparea, opsmapsubarea
 FROM Orders, Cyclist WHERE Orders.CyclistID = Cyclist.CyclistID AND Orders.publictrackingref = ? LIMIT 0,1";
 
$sth = $dbh->prepare($query);
$parameters = array($id);
$sth->execute($parameters);
$row = $sth->fetch();

$sql = "SELECT opsname, descrip FROM opsmap WHERE opsmapid='".$row['opsmaparea']."' LIMIT 0,1 "; 
$sth = $dbh->prepare($sql);
$sth->execute();
$trow = $sth->fetch();





if ($trow['opsname']) { // starts area stuff
$areacoord=array();
$sql="SELECT AsText(g) AS POLY FROM opsmap WHERE opsmapid=".$row['opsmaparea']." LIMIT 0,1";


$sth = $dbh->prepare($sql);
$sth->execute();
$score = $sth->fetch();


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
	$holdforasec=$testcoord; } 
else	{


$areacoord[]=$testcoord.','.$holdforasec.',0.0 ';

	}

$tmpi++;

	} // ends every single coord loop


}


$areacoordout = join("\n", $areacoord);


$kml[] = '<Placemark><name>'.$trow['opsname'].'</name> ';

// $kml[] = '<description>Description YES</description>';

$kml[] = '
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
</coordinates></LinearRing></outerBoundaryIs>
<innerBoundaryIs><LinearRing><coordinates>';

$kml[]=$areacoordout;


$kml[] = '</coordinates></LinearRing></innerBoundaryIs>
</Polygon>
</Placemark>
';



} // ends polygon area stuff





$cyclist=$row['poshname']; $trackerid=$row['trackerid'];
$startpause=strtotime($row['starttrackpause']); $finishpause=strtotime($row['finishtrackpause']);  
$collecttime=strtotime($row['collectiondate']); $delivertime=strtotime($row['ShipDate']);
if (($startpause > 10) and ( $finishpause < 10)) { $delivertime=$startpause; }
if ($startpause <10) { $startpause=9999999999; }
if (($row['status']<86) and ($delivertime < 200)) { $delivertime=9999999999; }
if ($row['status']<50) { $delivertime=0; }
if ($collecttime < 10) { $collecttime=9999999999;}
$sql = "
SELECT longitude, latitude, timestamp FROM `instamapper` 
WHERE `device_key` = '$trackerid' 
AND `timestamp` >= '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' 
AND '$finishpause' 
AND `timestamp` <= '$delivertime' 
ORDER BY `timestamp` ASC ";

$sth = $dbh->prepare($sql);
$sth->execute();
while($map = $sth->fetch(/* PDO::FETCH_ASSOC */)) {
    // do loop stuff



$map['latitude']=round($map['latitude'],5);
$map['longitude']=round($map['longitude'],5);

$linecoord = $linecoord. $map['longitude']	. ',' . $map['latitude'].'
	';	
if ( $prevts<>(date('H:i A l-jS-F,Y', $map['timestamp']))) {
  $kml[] = ' <Placemark>';
  $kml[] = ' <name>' . date('H:i A D jS M', $map['timestamp']) . '</name>';
  $kml[] = '    <visibility>1</visibility> ';
  $tempc= '<description><![CDATA['. date ('D jS F, Y', $map['timestamp']).'<br />'. $cyclist;
 $tempc=$tempc.' ]]></description>';
  $kml[] = $tempc;
  $kml[] = ' <styleUrl>#styleMapExample</styleUrl>';
  $kml[] = ' <Point>';
  $kml[] = " <coordinates>" . $map['longitude'] . ","  . $map['latitude'] . ",0" .'</coordinates>' ;
  $kml[] = ' </Point>';
  $kml[] = ' </Placemark>';
  $tempa=$map['longitude'];
  $tempb=$map['latitude'];
  $filenamedate=date ('l-jS-F-Y', $map['timestamp']);
$prevts=date ('H:i A l-jS-F,Y', $map['timestamp']);
}
  }

$kml[] = " <Placemark>
	 <styleUrl>#linebikestyle</styleUrl>
 <LineString>    
     <coordinates>".$linecoord. " </coordinates>
</LineString>
</Placemark>";
$kml[] = '     <ScreenOverlay>
        <name>'.$globalprefrow['globalshortname'].'</name>
        <visibility>1</visibility>
        <Icon><href>'.($globalprefrow['adminlogoabs']).'</href></Icon>
        <overlayXY x="0" y="-1" xunits="fraction" yunits="fraction"/>
        <screenXY x="0" y="0" xunits="fraction" yunits="fraction"/>
        <rotationXY x="0" y="0" xunits="fraction" yunits="fraction"/>
        <size x="0" y="0" xunits="fraction" yunits="fraction"/>
      </ScreenOverlay>
 </Document></kml>';
$kmlOutput = join("\n", $kml);

$cyclist = str_replace(" ", "-", "$cyclist", $count);

header('Content-type: application/vnd.google-earth.kml+xml');
header('Content-Disposition:attachment; filename="'.$jobid.'-'.$globalprefrow['globalshortname'].'-tracking-' . $cyclist . '-' . $filenamedate.'.kml"');

echo $kmlOutput;
?>