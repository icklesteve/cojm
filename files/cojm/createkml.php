<?php 

/*
    COJM Courier Online Operations Management
	createkml.php - Outputs tracking / map in kml format for google earth
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


include "live/C4uconnect.php";

$totlon=0;
$totlat=0;
$i=0;
 $cyclistid="createkml";
 $linecoord='';
 $prevts='';
$id=trim($_GET['id']); if ($id=="") { $id = trim($_POST['id']); }
 

$jobid=$id;
$query="SELECT poshname, trackerid, starttrackpause, finishtrackpause, collectiondate, ShipDate, status, opsmaparea, opsmapsubarea
 FROM Orders, Cyclist WHERE Orders.CyclistID = Cyclist.CyclistID AND Orders.publictrackingref =:getid LIMIT 0,1";
 
$sth = $dbh->prepare($query);
$sth->bindParam(':getid', $id, PDO::PARAM_INT); 
$sth->execute();

$found = $sth->rowCount();

if ($found<1) { $dbh=null; die; }


$row = $sth->fetch();


// rider tracking
$insta='';

$trackerid=$row['trackerid'];
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
		
$totlon=$totlon+$map['longitude'];
$totlat=$totlat+$map['latitude'];
$i++;
	
if ( $prevts<>(date('H:i A l-jS-F,Y', $map['timestamp']))) {
  $insta.= ' <Placemark><open>0</open>';
  $insta.= ' <name>' . date('H:i A D jS M', $map['timestamp']) . '</name>';
  $insta.= '    <visibility>1</visibility> ';
  $insta.= '<description><![CDATA['. date ('D jS F, Y', $map['timestamp']).'<br />'. $row['poshname'].' ]]></description>';
  $insta.= ' <styleUrl>#styleMapExample</styleUrl>';
  $insta.= ' <Point>';
  $insta.=" <coordinates>" . $map['longitude'] . ","  . $map['latitude'] . ",0" .'</coordinates>' ;
  $insta.= ' </Point>';
  $insta.= ' </Placemark>';
  $tempa=$map['longitude'];
  $tempb=$map['latitude'];
  $filenamedate=date ('l-jS-F-Y', $map['timestamp']);
$prevts=date ('H:i A l-jS-F,Y', $map['timestamp']);

} // ends once per min check
} // ends instamapper loop
	
$opsm='';	

$sql = "SELECT opsname, descrip FROM opsmap WHERE opsmapid='".$row['opsmaparea']."' LIMIT 0,1 "; 
$sth = $dbh->prepare($sql);
$sth->execute();
$trow = $sth->fetch();
	
$arealat=0;
$arealon=0;
$j=0;

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

$arealat=$arealat+$testcoord;
$arealon=$arealon+$holdforasec;
$j++;
$totlon=$totlon+$testcoord;
$totlat=$totlat+$holdforasec;
$i++;
	}

$tmpi++;

	} // ends every single coord loop

} // ends area loop


$areacoordout = join("\n", $areacoord);

$opsm.= '<Placemark><name>'.$trow['opsname'].'</name> 
<LookAt>
     <longitude>'.($arealat/$j).'</longitude>
      <latitude>'.($arealon/$j).'</latitude>  
      <altitude>5000</altitude>
     <heading>0</heading>
     <tilt>10</tilt>
	 <range>5500</range>
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
</coordinates></LinearRing></outerBoundaryIs>
<innerBoundaryIs><LinearRing><coordinates>'.$areacoordout.'</coordinates></LinearRing></innerBoundaryIs>
</Polygon>
</Placemark>
';

} // ends polygon area stuff





$kml = array('<?xml version="1.0" encoding="UTF-8"?>');
$kml[] = ' <kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2">';
$kml[] = ' <Document>';
$kml[] = ' <name>'.$globalprefrow['globalshortname'].' '.$id.'</name>';
$kml[] = ' <open>0</open>';

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

$kml[] = ' <LookAt>
     <longitude>'.($totlon/$i).'</longitude>
      <latitude>'.($totlat/$i).'</latitude>  
      <altitude>5000</altitude>
     <heading>0</heading>
     <tilt>10</tilt>
	 <range>5500</range>
      <altitudeMode>relativeToGround</altitudeMode>
    </LookAt>';

	
	$kml[]=$opsm;  
	
$kml[] = " <Placemark>
	 <styleUrl>#linebikestyle</styleUrl>
 <LineString>    
     <coordinates>".$linecoord. " </coordinates>
</LineString>
</Placemark>";


$kml[]=$insta;  

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

header('Content-type: application/vnd.google-earth.kml+xml');
header('Content-Disposition:attachment; filename="'.$jobid.'-'.$globalprefrow['globalshortname'].'-tracking-' . $filenamedate.'.kml"');

echo $kmlOutput;
?>