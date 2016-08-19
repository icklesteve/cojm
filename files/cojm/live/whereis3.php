<?php 

$alpha_time = microtime(TRUE);
include "C4uconnect.php";
include_once ("changejob.php");

$comments='';
$newdat='';
$numuncollected='';
$tofittext='';
$uptodate='';
$numcyclists='';
$cyclistts='';

if ($globalprefrow['forcehttps']>0) { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$title = "COJM Map Overview";
$todayend = (date("Y-m-d"));

if (isset($_GET['maptimeout'])) { $maptimeout=trim($_GET['maptimeout']); } else { $maptimeout=''; }

if (isset($_POST['timelength'])) { $timelength=trim($_POST['timelength']); } else { 

if (isset($_GET['timelength'])) { $timelength=$_GET['timelength']; } else { $timelength=''; }  


 }
 
if (($timelength) AND ($timelength<30)) {$timelength=30;}

if(($maptimeout) AND (isset($_POST['timelength']))) { die(header('Location: whereis3.php?maptimeout=true&timelength='.$timelength)); } 


$menuextra='<form action="?maptimeout=active" method="post" >
<input type="text" name="timelength" size="2" maxlength="3" value="'.$timelength.'">s.
<input class="submit" type="submit" value="';

if ($timelength=='') { $menuextra=$menuextra. 'Set'; } else { $menuextra=$menuextra. 'Set ';}

$menuextra=$menuextra.' Refresh"></form>';



?><!doctype html>
<html lang="en">
<head> <title>COJM Todays Live Job Map</title>
<?php if ($timelength) { echo '<meta http-equiv="refresh" content="'.$timelength.'"/>'; } 
// <link rel="stylesheet" href="adv.maps.marker.toggle.css">

echo '
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'.$globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" />
'; ?>
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">



<script src="https://www.google.com/jsapi?autoload=%7B%22modules%22%3A%5B%7B%22name%22%3A%22maps%22%2C%22version%22%3A%223%22%2C%22https%22%3A%22maps.google.co.uk%22%2C%22language%22%3A%22en-GB%22%2C%22other_params%22%3A%22sensor%3Dfalse%22%7D%2C%7B%22name%22%3A%22jquery%22%2C%22version%22%3A%221.6%22%7D%2C%7B%22name%22%3A%22jqueryui%22%2C%22version%22%3A%221.8.6%22%7D%5D%7D"></script>
<style type="text/css">
html {
       overflow-y: hidden;
}
<?php //  this stops the scrollbar being displayed on right hand side
?>
</style>

</head>
<body >
<?php
$filename='whereis3.php';
include"cojmmenu.php";
$infotext=''; // resets variable for later use in table 
?>
<div style="position:absolute; margin-top:36px;" id="map"> </div>
	     <div id="dialog">
   <div id="data"></div>
        <div id="panoMap"></div>
    </div>
    <script src="adv.maps.marker.toggle.js"></script>
	 <div id="category_toogle">
        <div class="button_map" id="reset_map">Reset</div>
        <ul id="category_toogle_ul"></ul> </div>
	<div style="position:fixed; bottom:0%; right:0%; opacity:0.6;">
<table class="c10" dir="ltr" ><tbody class="c10">
<?php


$i=0;
$lattot='0'; $lontot='0';
 // echo $thiscyclist;
 $query = "SELECT CyclistID, cojmname FROM Cyclist 
 WHERE isactive='1' 
 AND CyclistID > '1'
 ORDER BY CyclistID"; $result_id = mysql_query ($query, $conn_id); 
  while (list ($CyclistID, $cojmname) = mysql_fetch_row ($result_id))
   { // echo $CyclistID;

$sql = "SELECT cojmname,trackerid FROM Cyclist WHERE CyclistID = '$CyclistID' LIMIT 1";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$row=mysql_fetch_array($sql_result);
$thisonetracker=$row['trackerid'];
$tUnixTime = time();
$nsql = "SELECT * FROM `instamapper` WHERE `device_key` = '$thisonetracker' ORDER BY `timestamp` DESC LIMIT 0,1";
$sql_result = mysql_query($nsql,$conn_id)  or mysql_error();
$sumtot=mysql_affected_rows();

if ($sumtot>0.5) {
while ($map = mysql_fetch_array($sql_result)) {
     extract($map); 
$sGMTMySqlString = gmdate("d-m-Y", $tUnixTime);
// echo $sGMTMySqlString;	

// declare some start variables 
$ThisYear = (date("Y")); $MarStartDate = ($ThisYear."-03-25"); $OctStartDate = ($ThisYear."-10-25"); $MarEndDate = ($ThisYear."-03-31"); 
$OctEndDate = ($ThisYear."-10-31"); while ($MarStartDate <= $MarEndDate) { $day = date("l", strtotime($MarStartDate)); 
if ($day == "Sunday"){ $BSTStartDate = ($MarStartDate); } $MarStartDate++;} $BSTStartDate = (date("U", strtotime($BSTStartDate))+(60*60)); 
while ($OctStartDate <= $OctEndDate) { $day = date("l", strtotime($OctStartDate)); if ($day == "Sunday"){ $BSTEndDate = ($OctStartDate); } 
$OctStartDate++; } $BSTEndDate = (date("U", strtotime($BSTEndDate))+(60*60)); $now = time(); if (($now >= $BSTStartDate) && ($now <= $BSTEndDate)){
// echo "We are now in BST"; 
// $map['timestamp']=$map['timestamp']+3600; 
} else { 
// echo "We are now in GMT"; 
} 

$newSqlString = gmdate(" H:i ", $map['timestamp']);
$ewSqlString = gmdate("d-m-Y", $map['timestamp']);

if ($ewSqlString==$sGMTMySqlString) { 
 	
	 $comments=$comments."['".$row['cojmname'].'<br>'.date('H:i', ($map['timestamp'])). '<br>'. $map['speed'] 
	 .' '.$globalprefrow['distanceunit'].' per hour.'. "',". $map['latitude'] . "," . $map['longitude'] . "," . $i 
	."  ],"; 
	$i=$i+1;
	
$cyclistname=$row['cojmname'];


if ($map['timestamp']>$uptodate) {$uptodate=$map['timestamp']; }






$seeifsched="

SELECT * FROM Orders 
INNER JOIN Clients 
INNER JOIN Services 
INNER JOIN status
ON 
Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
AND Orders.status = status.status 
WHERE `Orders`.`status` <70 
AND `Orders`.`CyclistID`=$CyclistID
AND `Orders`.`nextactiondate` < '$todayend 23:59:59'
ORDER BY `Orders`.`nextactiondate` 

";
 $outssql_result = mysql_query($seeifsched,$conn_id)  or mysql_error();
 $toutstanding=mysql_affected_rows();
 while ($outsrow = mysql_fetch_array($outssql_result)) {
     extract($outsrow);

}

$numcyclists=$numcyclists+1;

$temptitle= $cyclistname.' '. $cyclistts;

$temptitle='';
$tempcontent='';
$cyclistts='';
$outs='';



// end of cyclist loop
}}}}




// see if unscheduled job
$seeifunsched="
SELECT * FROM Orders 
INNER JOIN Clients 
INNER JOIN Services 
ON 
Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
WHERE `Orders`.`status` <77 
AND `Orders`.`CyclistID` = 1
AND `Orders`.`nextactiondate` < '$todayend 23:59:59'
ORDER BY `Orders`.`nextactiondate` 
";

// echo $seeifunsched;

 $unssql_result = mysql_query($seeifunsched,$conn_id)  or mysql_error();
 $totunsched=mysql_affected_rows();
 while ($unsrow = mysql_fetch_array($unssql_result)) {
     extract($unsrow);

if ($unsrow['CollectPC']) {	 } // ends check to make sure collection postcode
else { $infotext=$infotext. 'No Collection Postcode for <a href="order.php?id='.$ID.'">'.$ID.'</a><br />'; }

} // ends loop for check for unscheduled jobs



// loop for jobs awaiting collection

$seeifucdel="
SELECT * FROM Orders 
INNER JOIN Clients 
INNER JOIN Services 
INNER JOIN Cyclist
ON 
Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID
AND Orders.CyclistID = Cyclist.CyclistID  
WHERE `Orders`.`status` <52 
AND `Orders`.`nextactiondate` < '$todayend 23:59:59'
ORDER BY `Orders`.`nextactiondate` 
";

// echo $seeifucdel;



 $uncsql_result = mysql_query($seeifucdel,$conn_id)  or mysql_error();
 $totuncel=mysql_affected_rows();
 while ($uncrow = mysql_fetch_array($uncsql_result)) {
     extract($uncrow);

//	 echo $uncrow['cojmname'];
	 
	 
if ($uncrow['CollectPC']) {	 
	 
$pc1 = str_replace (" ", "", $uncrow['CollectPC']);
$query="SELECT * 
FROM  `postcodeuk` 
WHERE  `PZ_Postcode` =  '$pc1'
LIMIT 1"; 
$result=mysql_query($query, $conn_id); 
$pcrow=mysql_fetch_array($result); 
// echo '<p>pc1 : '.$pc1.$pcrow["PZ_easting"].$pcrow["PZ_northing"].'</p>';

$uncid=$uncrow['ID'];
$pclon=$pcrow['PZ_easting'];
$pclat=$pcrow['PZ_northing'];



// needs check for no postcode


$collecttime= date('H:i ', strtotime($uncrow['targetcollectiondate'])); 
if (date('A', strtotime($uncrow['targetcollectiondate']))==date('A', strtotime($uncrow['collectionworkingwindow']))) {
// echo ' the same colect';
} 
else { 
// echo ' not same collect';
$collecttime=$collecttime. date(' A ', strtotime($uncrow['targetcollectiondate'])); } 
if ($uncrow['allowcollectww']=="1") { $collecttime=$collecttime. '- '.date('H:i A ', strtotime($uncrow['collectionworkingwindow'])); } 

 $lattot=$lattot+$pclat;
	 $lontot=$lontot+$pclon;
	 $i=$i+1;
	 $numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');
$outsc="<br/><a href='order.php?id=".$uncid."'>".$uncid.'</a> ';

   $temptitle= 'Collection Due '.$collecttime.' by '.$cojmname;
   $tempcontent='<div><strong>Uncollected</strong>'.$outsc.' Collection due '.$collecttime.'<br>From '.
   $uncrow['CollectPC'].' to '.$uncrow['ShipPC']. " <br />$numberitems x $Service<br /> $CompanyName</div>";

// echo ' uncollected '.$uncid;
if ($pclat) {

$newdat=$newdat.'
// job awaiting collection
      { lat: '.$pclat.', lng: '.$pclon.', title: "'.$temptitle.'", image: "images/share.png", zI:40, content : "'.$tempcontent.'" }, ';
$temptitle='';
$tempcontent='';

$numuncollected=$numuncollected+1;
$tofittext=$tofittext.' new google.maps.LatLng ('.$pclat.','.$pclon.'), ';
} 

else { $infotext=$infotext. 'No Postcode on Database for <a href="order.php?id='.$ID.'">'.$ID.'</a><br />'; }

} // ends check to make sure collection postcode

else { $infotext=$infotext. 'No Collection Postcode for <a href="order.php?id='.$ID.'">'.$ID.'</a><br />'; }

} // ends loop for check for scheduled collections


// starts check for oustanding deliveries


$seeifundel="
SELECT * FROM Orders 
INNER JOIN Clients 
INNER JOIN Services 
INNER JOIN Cyclist
ON 
Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
AND Orders.CyclistID = Cyclist.CyclistID 
AND `Orders`.`status` <77 
AND `Orders`.`nextactiondate` < '$todayend 23:59:59'
ORDER BY `Orders`.`nextactiondate` ";

// echo $seeifunsched;

 $undsql_result = mysql_query($seeifundel,$conn_id)  or mysql_error();
 $totundel=mysql_affected_rows();
 while ($undrow = mysql_fetch_array($undsql_result)) {
     extract($undrow);

if ($undrow['ShipPC']) {	 
$pc1 = str_replace (" ", "", $undrow['ShipPC']);
$query="SELECT * 
FROM  `postcodeuk` 
WHERE  `PZ_Postcode` =  '$pc1'
LIMIT 1"; 
$result=mysql_query($query, $conn_id); $pcrow=mysql_fetch_array($result); 
$undid=$undrow['ID']; $pclon=$pcrow['PZ_easting']; $pclat=$pcrow['PZ_northing'];

$temptitle='';
$tempcontent='';
} // ends check to make sure delivery postcode

else { $infotext=$infotext. 'No Delivery Postcode for <a href="order.php?id='.$ID.'">'.$ID.'</a><br />'; }

} // ends loop for check for undelivered jobs
























if ($uptodate) {

$uptodate=date('H:i', ($uptodate));
}
 
  // A SCRIPT TIMER
        $omega_time = microtime(TRUE);
    $lapse_time = $omega_time - $alpha_time;
    $lapse_msec = $lapse_time * 1000.0;
    $lapse_echo = number_format($lapse_msec, 1);


//	$infotext=$infotext.'Map last updated at '.date("H;i");

	
	

// $infotext=$infotext."$lapse_echo MS<br/>";
 if ($infotext) { ?><tr><td colspan="2" style="text-align:right;"><?php echo $infotext; ?></td></tr><?php } 
if ($totunsched>0) { ?><tr><td style="text-align:right;"><?php echo $totunsched; ?> Awaiting<br />Scheduling</td><td><img 
alt="Awaiting Scheduling" src="<?php echo $globalprefrow['image1']; ?>"></td></tr><?php } 
if ($numuncollected) { ?><tr><td style="text-align:right;"><?php echo $numuncollected; ?> Awaiting<br />Collection</td><td><img 
alt="Awaiting Collection" src="<?php echo $globalprefrow['image2']; ?>"></td></tr><?php } 
if ($totundel) { ?><tr><td style="text-align:right;">Awaiting Delivery<br /><?php if (!$infotext) {echo ($totundel-$numuncollected).' POB';} ?>
</td><td><img alt="Awaiting Delivery" src="<?php echo $globalprefrow['image3']; ?>"></td></tr><?php } 
if ($numcyclists>0) { ?><tr><td style="text-align:right;"><?php echo $numcyclists; ?> Cyclist<?php 
if ($numcyclists>1) { echo 's'; } ?>, updated<br /><?php echo $uptodate; ?></td><td><img alt="Cyclist" src="<?php echo $globalprefrow['image4']; ?>"></td></tr><?php } ?>
<tr><td colspan="2" style="text-align:right;">Map last updated at <?php echo date("H:i"); ?></td></tr>
<tr><td colspan="2" style="text-align:right;"><?php echo $menuextra; ?></td></tr>
</table></div>

 <?php
 
 if ($totunsched>0) { echo'
<audio autoplay >
  <source src="../../sounds/'. $globalprefrow['sound1'].'.ogg" type="audio/ogg" >
  <source src="../../sounds/'. $globalprefrow['sound1'].'.mp3" type="audio/mp3" >
  Your browser does not support the audio tag!
</audio>';
 }
 

 include 'footer.php';
 
mysql_close(); 

echo '</body></html>';