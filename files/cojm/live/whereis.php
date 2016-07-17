<?php 
error_reporting(E_ALL);

// A SCRIPT TIMER
$alpha_time = microtime(TRUE);

include "../../administrator/cojm/updatetracking.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

$title = "COJM";
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<META 
     HTTP-EQUIV="Refresh"
     CONTENT="60; URL=<?php echo $_SERVER['PHP_SELF'];?>">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="cojm.css" >
<title><?php print ($title); ?> Whereabouts</title>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
</head><body>
<?php 

$adminmenu ="1";
include "cojmmenu.php"; 
// echo $infotext;
$mapnum=1;
?>
<br>
<a href="whereisbig.php">Big Map</a><br>
<table id="acc" class="acc" cellspacing="0" style="table-layout:auto;">
<tbody><tr><th colspan=2>
<?php
$today = date(" H:i A D");
echo '<strong>Page Created : '.$today; ?>, updated : <?php  
$lasttracked="SELECT * FROM `instamapper` ORDER BY `instamapper`.`timestamp` DESC LIMIT 0 , 1 "; 
$tracking_result = mysql_query($lasttracked,$conn_id) or die(mysql_error()); 
while ($trackedrow = mysql_fetch_array($tracking_result)) { extract($trackedrow);  }
echo date('H:i A D', $timestamp); ?>
</strong>
</th></tr>
<?php // echo $thiscyclist;
 $query = "SELECT CyclistID, cojmname FROM Cyclist ORDER BY CyclistID"; $result_id = mysql_query ($query, $conn_id); 
  while (list ($CyclistID, $cojmname) = mysql_fetch_row ($result_id))
   { // echo $CyclistID;

$sql = "SELECT * FROM Cyclist WHERE CyclistID = '$CyclistID' ";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$row=mysql_fetch_array($sql_result);
$thisonetracker=$row['trackerid'];

// Create the UNIX Timestamp, using the current system time
$tUnixTime = time();
// Convert that UNIX Timestamp into a string (GMT), safe for MySql
$nsql = "SELECT * FROM `instamapper` WHERE `device_key` = '$thisonetracker' ORDER BY `timestamp` DESC LIMIT 0,1";
$sql_result = mysql_query($nsql,$conn_id)  or mysql_error();
$lattot='0'; $lontot='0';
$sumtot=mysql_affected_rows();

if ($sumtot>0.5) {
while ($map = mysql_fetch_array($sql_result)) {
     extract($map); 
$sGMTMySqlString = gmdate("d-m-Y", $tUnixTime);
// echo $sGMTMySqlString;	




// declare some start variables 
$ThisYear = (date("Y")); 
$MarStartDate = ($ThisYear."-03-25"); 
$OctStartDate = ($ThisYear."-10-25"); 
$MarEndDate = ($ThisYear."-03-31"); 
$OctEndDate = ($ThisYear."-10-31"); 
while ($MarStartDate <= $MarEndDate) 
{ $day = date("l", strtotime($MarStartDate)); 
if ($day == "Sunday"){ $BSTStartDate = ($MarStartDate); } $MarStartDate++;} 
$BSTStartDate = (date("U", strtotime($BSTStartDate))+(60*60)); 
//work out the Unix timestamp for 1:00am GMT on the last Sunday of October, when BST ends 
while ($OctStartDate <= $OctEndDate) { $day = date("l", strtotime($OctStartDate)); 
if ($day == "Sunday"){ $BSTEndDate = ($OctStartDate); } $OctStartDate++; } 
$BSTEndDate = (date("U", strtotime($BSTEndDate))+(60*60)); 
//Check to see if we are now in BST 
$now = mktime(); 
if (($now >= $BSTStartDate) && ($now <= $BSTEndDate)){ 
// echo "We are now in BST"; 
$map['timestamp']=$map['timestamp']+3600; 
} 
else { 
// echo "We are now in GMT"; 
} 






$newSqlString = gmdate(" H:i ", $map['timestamp']);
$ewSqlString = gmdate("d-m-Y", $map['timestamp']);

if ($ewSqlString==$sGMTMySqlString) { 
$agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/iPhone|Android|Blackberry/i', $agent)) { $position="padding: 20px; "; } else { $adminmenu ="1"; }

// if ( $mapnum % 2 ) { $position = ' position: relative; float: left; '; } else { $position = ' position: relative;  float: right;'; }

if ( ($mapnum+1) %2 ) { echo'</td><td>'; }
if ( $mapnum %2 ) { echo'<tr><td>'; }
?>
<form action="cyclist.php?page=selectcyclist" method="post" > 
<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>">
<input type="hidden" name="thiscyclist" value="<?php echo $CyclistID; ?>" >
<input type="hidden" name="viewtype" value="Today">
<input type="submit" value="<?php echo $row['cojmname']; ?>"> 
 <?php echo ' Position last updated at ' .$newSqlString; 
 
 
// echo $map['latitude'] . "," . $map['longitude'];
 
     include_once("GeoCalc.class.php");
      $oGC = new GeoCalc();
      $dRadius = 0.07;  // in kilometers</p>
        $dLongitude = $map['longitude'];
        $dLatitude = $map['latitude'];
        $dAddLat = $oGC->getLatPerKm() * $dRadius;
        $dAddLon = $oGC->getLonPerKmAtLat($dLatitude) * $dRadius;
        $dNorthBounds = $dLatitude + $dAddLat;
        $dSouthBounds = $dLatitude - $dAddLat;
        $dWestBounds = $dLongitude - $dAddLon;
        $dEastBounds = $dLongitude + $dAddLon;
//        print "<br>Radius: $dRadius kilometers\n";
         $strQuery = "SELECT PZ_northing, PZ_easting, PZ_Postcode FROM postcodeuk " .
                   "WHERE PZ_northing > $dSouthBounds " .
                   "AND PZ_northing < $dNorthBounds " .
                   "AND PZ_easting > $dWestBounds " .
                   "AND PZ_easting < $dEastBounds";
 $sql_result = mysql_query($strQuery,$conn_id)  or mysql_error();
 $sumtot=mysql_affected_rows(); 
 // echo $sumtot.' Postcodes found';
 $dDist=99999999999;
 $startdit=9999999999999;
 while ($row = mysql_fetch_array($sql_result)) {
     extract($row);
 //echo $row['PZ_Postcode'].' ';
        $oGC = new GeoCalc(); 
      $dDist = $oGC->EllipsoidDistance($map["latitude"],$map["longitude"],$row["PZ_northing"],$row["PZ_easting"]);
// echo $dDist.'<p>       Convert distance from kilometers to miles';
//      $dDistMiles = ConvKilometersToMiles($dDist);
//	  $dDistMiles= round($dDistMiles, 1);    
//	  $dDist= round($dDist, 1);    
//	  $var = substr($var,0,-1);
if ($dDist<$startdit) { $dDist=$startdit; $thispc=$row['PZ_Postcode']; }
//	   echo $dDistMiles;
  }
  
 // echo $thispc;
 
$start= substr($thispc, 0, -3); 
$string = $thispc;  
// echo substr($string, 0); // 'categories' because we asked to start at the first character (which is 0 from the first example above) and goes right until end of string  
// echo substr($string, 3); // 'egories' because started at the third character in the string and went to the end  
// echo substr($string, -1); // 's' this is different from above because it starts at the end and goes left that number of characters  
echo $start;

echo ' '.substr($string, -3); // 'ies'  
  
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 ?>
</form>

  <div id="map<?php echo $mapnum; ?>" style="width: 400px; height: 200px; <?php echo $position; ?>"> </div>
  <script type="text/javascript">
    var locations = [
<?php
	 $lattot=$lattot+$map['latitude'];
	 $lontot=$lontot+$map['longitude'];
	 $comments=''.date('H:i A D j M ', ($map['timestamp'])) . '<br>'. $map['speed'] .' '.$globalprefrow['distanceunit'].' per hour.';
	echo "['".$comments."',". $map['latitude'] . "," . $map['longitude'] . "," . $i ."],"; $i=$i++;
	
$latestlat=	$map['latitude'];
$latestlong=	$map['longitude'];
$lattot=($lattot / $sumtot );
$lontot=($lontot / $sumtot );
		// restarts javascript
?>
    ];

    var map<?php echo $mapnum; ?> = new google.maps.Map(document.getElementById('map<?php echo $mapnum; ?>'), {
      zoom: 14,
      center: new google.maps.LatLng(<?php echo $latestlat . ',' . $latestlong;  ?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var infowindow = new google.maps.InfoWindow();
    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map<?php echo $mapnum; ?>
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map<?php echo $mapnum; ?>, marker);
        }
      })(marker, i));
    } 
  </script>
<?php 
 // ends check to see if data exists 
$mapnum++; if ( ($mapnum) %2 ) { echo'</tr>'; }
}}} }


echo '</tbody></table>';

  // A SCRIPT TIMER
        $omega_time = microtime(TRUE);
    $lapse_time = $omega_time - $alpha_time;
    $lapse_msec = $lapse_time * 1000.0;
    $lapse_echo = number_format($lapse_msec, 1);
    echo "<br/> $lapse_echo MILLISECONDS";
mysql_close(); 


echo '</body></html>';