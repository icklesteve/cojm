<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
include "../../administrator/cojm/updatetracking.php";
 $cyclistid="createbatchkml";
 
$id=trim($_GET['id']); if ($id=="") { $id = trim($_POST['id']); }

$kml = array('<?xml version="1.0" encoding="UTF-8"?>');
$kml[] = ' <kml xmlns="http://www.opengis.net/kml/2.2"
xmlns:gx="http://www.google.com/kml/ext/2.2">';
$kml[] = ' <Document>';
$kml[] = ' <name>'.$globalprefrow['globalshortname'].' Tracking Report</name>';

$kml[] = ' <open>1</open>';
$kml[] = ' <Style id="bikeStyle">';
$kml[] = ' <IconStyle id="bikeIcon">';
$kml[] = ' <Icon>';
$kml[] = ' <href>'.$globalprefrow['clweb3'].'</href>';
$kml[] = ' </Icon>';
$kml[] = ' </IconStyle>
<BalloonStyle>
<text>$[description]</text></BalloonStyle>';
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
<Icon> <href>'.$globalprefrow['clweb3'].'</href></Icon>
      <scale>1.0</scale>
    </IconStyle>
    <LabelStyle>
      <scale>1.1</scale>
      <color>ffffffff</color>
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
  </StyleMap>

';




















$i='1';

while ($i<1000) {



$trid= trim($_POST["tr$i"]);


if ($trid) {




 
// include "cojm/live/changejob.php";

//  echo 'here';

$jobid=$id;
$query="SELECT * FROM Orders, Cyclist WHERE Orders.CyclistID = Cyclist.CyclistID AND Orders.ID = '$trid'";

// echo $query;

$result=mysql_query($query, $conn_id); $row=mysql_fetch_array($result);
$cyclist=$row['poshname']; $trackerid=$row['trackerid'];
$startpause=strtotime($row['starttrackpause']); $finishpause=strtotime($row['finishtrackpause']);  
$collecttime=strtotime($row['collectiondate']); $delivertime=strtotime($row['ShipDate']);
if (($startpause > 10) and ( $finishpause < 10)) { $delivertime=$startpause; }
if ($startpause <10) { $startpause=9999999999; }
if (($row['status']<86) and ($delivertime < 200)) { $delivertime=9999999999; }
if ($row['status']<50) { $delivertime=0; }
if ($collecttime < 10) { $collecttime=9999999999;}
$sql = "
SELECT * FROM `instamapper` 
WHERE `device_key` = '$trackerid' 
AND `timestamp` >= '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' 
AND '$finishpause' 
AND `timestamp` <= '$delivertime' 
ORDER BY `timestamp` ASC ";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 

while ($map = mysql_fetch_array($sql_result)) { extract($map);


  $kml[] = ' <Placemark id="placemark' . $id . '">';
  $kml[] = ' <name>' . date('H:i', $map['timestamp']) . '</name>';
  
  $tempc= '<description><![CDATA[ <div> Timestamp : ' .date ('H:i A, ', $map['timestamp']). '<br>'.
  date ('l jS \of F, Y', $map['timestamp']).'<br>Delivery Ref : '.$row['ID'].'<br>Cyclist : '. $cyclist;
 if ($map['speed']) { $tempc=$tempc.' <br>Speed : ' .$map['speed'] .' '.$globalprefrow['distanceunit'].' per hour.'; }
  $tempc=$tempc.'</div> ]]></description>';
  $kml[] = $tempc;
  $kml[] = ' <styleUrl>' .'#styleMapExample' .'</styleUrl>';
  $kml[] = ' <Point>';
  $kml[] = " <coordinates>" . $map['longitude'] . ","  . $map['latitude'] . ",". $map['altitude'] .'</coordinates>' ;
  $kml[] = ' </Point>';
  $kml[] = ' </Placemark>';
  $tempa=$map['longitude'];
  $tempb=$map['latitude'];
  $filenamedate=date ('l jS \of F, Y', $map['timestamp']);
  }
  

  
  
  
// route  

$kml[] = " <Placemark>
    
	 <styleUrl>#linebikestyle</styleUrl>
 <LineString>    
	<extrude>1</extrude>
     <coordinates>";
		
$sql = "
SELECT * FROM `instamapper` 
WHERE `device_key` = '$trackerid' 
AND `timestamp` >= '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' AND '$finishpause' 
AND `timestamp` <= '$delivertime' 
ORDER BY `timestamp` ASC ";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
while ($map = mysql_fetch_array($sql_result)) { extract($map);		
	$kml [] = $map['longitude']	. ',' . $map['latitude'];	}
$kml[]="</coordinates>
</LineString>
</Placemark>";

}


$i++;
}





$kml[] = '<LookAt>
      <longitude>'.$tempa.'</longitude>
      <latitude>'.$tempb.'</latitude>  
      <heading>0</heading>     
'.$globalprefrow['clweb5'].'
      <altitudeMode>relativeToGround</altitudeMode>
    </LookAt>
      <visibility>0</visibility>
	      <ScreenOverlay>
        <name>'.$globalprefrow['globalshortname'].'</name>
        <visibility>1</visibility>
        <Icon>
          <href>'.$globalprefrow['httproot']. "/images/".($globalprefrow['adminlogo']).'</href>
        </Icon>
        <overlayXY x="0" y="-1" xunits="fraction" yunits="fraction"/>
        <screenXY x="0" y="0" xunits="fraction" yunits="fraction"/>
        <rotationXY x="0" y="0" xunits="fraction" yunits="fraction"/>
        <size x="0" y="0" xunits="fraction" yunits="fraction"/>
      </ScreenOverlay>


 </Document></kml>';
$kmlOutput = join("\n", $kml);
header('Content-type: application/vnd.google-earth.kml+xml');
header('Content-Disposition:attachment; filename="'.$globalprefrow['globalshortname'].'_tracking_report_total.kml"');

echo $kmlOutput;
?>