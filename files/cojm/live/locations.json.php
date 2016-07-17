<?php error_reporting(E_ALL);
include_once "C4uconnect.php";
include_once("GeoCalc.class.php");

if ($globalprefrow['forcehttps']>0) { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$todayend = (date("Y-m-d"));

function rstrtrim($str, $remove=null) 
{ 
    $str    = (string)$str; 
    $remove = (string)$remove;    
    if(empty($remove)) {   return rtrim($str); } 
    
    $len = strlen($remove); 
    $offset = strlen($str)-$len; 
    while($offset > 0 && $offset == strpos($str, $remove, $offset)) 
    { 
        $str = substr($str, 0, $offset); 
        $offset = strlen($str)-$len; 
    } 
   return rtrim($str);    
} // ends function

$i=0;








////////    SEE IF TRACKING DATA FOR TODAY FOR RIDERS

 $query = "SELECT CyclistID, cojmname FROM Cyclist 
 WHERE isactive='1' 
 AND CyclistID > '1'
 ORDER BY CyclistID"; $result_id = mysql_query ($query, $conn_id); 
  while (list ($CyclistID, $cojmname) = mysql_fetch_row ($result_id))
   { // echo $CyclistID;

$sql = "SELECT cojmname,trackerid FROM Cyclist WHERE CyclistID = '$CyclistID' ";
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
$OctStartDate++; } $BSTEndDate = (date("U", strtotime($BSTEndDate))+(60*60)); $now = mktime(); if (($now >= $BSTStartDate) && ($now <= $BSTEndDate)){
// echo "We are now in BST"; 
// $map['timestamp']=$map['timestamp']+3600; 
} else { 
// echo "We are now in GMT"; 
} 

$newSqlString = gmdate(" H:i ", $map['timestamp']);
$ewSqlString = gmdate("d-m-Y", $map['timestamp']);

if ($ewSqlString==$sGMTMySqlString) {
 	$comments=$comments."['".$row['cojmname'].'<br>'.date('H:i', ($map['timestamp']));
if ($map['speed']) { $comments=$comments.'<br>'. $map['speed'] .' '.$globalprefrow['distanceunit'].' per hour.'; }
	 $comments=$comments. "',". $map['latitude'] . "," . $map['longitude'] . "," . $i ."  ],"; 
	$i=$i+1;	
$cyclistname=$row['cojmname'];
$latestlat=	$map['latitude'];
$latestlong=$map['longitude'];
      $oGC = new GeoCalc(); $dRadius = 0.07; 
        $dLongitude = $map['longitude'];
        $dLatitude = $map['latitude']; $dAddLat = $oGC->getLatPerKm() * $dRadius; $dAddLon = $oGC->getLonPerKmAtLat($dLatitude) * $dRadius;
        $dNorthBounds = $dLatitude + $dAddLat;
        $dSouthBounds = $dLatitude - $dAddLat; $dWestBounds = $dLongitude - $dAddLon; $dEastBounds = $dLongitude + $dAddLon;
         $strQuery = "SELECT PZ_northing, PZ_easting, PZ_Postcode FROM postcodeuk " .
                   "WHERE PZ_northing > $dSouthBounds " .
                   "AND PZ_northing < $dNorthBounds " .
                   "AND PZ_easting > $dWestBounds " .
                   "AND PZ_easting < $dEastBounds";
$sql_result = mysql_query($strQuery,$conn_id)  or mysql_error(); $sumtot=mysql_affected_rows(); 
$dDist=99999999999; $startdit=9999999999999;
 while ($row = mysql_fetch_array($sql_result)) {  extract($row);
        $oGC = new GeoCalc(); 
      $dDist = $oGC->EllipsoidDistance($map["latitude"],$map["longitude"],$row["PZ_northing"],$row["PZ_easting"]);
if ($dDist<$startdit) { $dDist=$startdit; $thispc=$row['PZ_Postcode']; }
  }
 
$start= substr($thispc, 0, -3); 
$string = $thispc;  
$cyclistpos= $start.' '.substr($string, -3); // 'ies'  






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
$outs=$outs."<br/><a href='order.php?id=".$outsrow['ID']."'>".$outsrow['ID'].'</a> '.$outsrow['CollectPC'].' to '.$outsrow['ShipPC'].', '.$outsrow['statusname'];
}

$cyclistts=date('H:i', ($map['timestamp'])) ;

$tempcontent='<div><strong>'.$cyclistname.'</strong> was near to '.$cyclistpos.' at '.$cyclistts.'<br/>'.$toutstanding.' job';
 if  ($toutstanding !=1) { $tempcontent=$tempcontent. 's'; }
 $tempcontent=$tempcontent. ' outstanding'.$outs.'</div>';
$numcyclists=$numcyclists+1;
$temptitle= $cyclistname.' '. $cyclistts;
$idnum=$idnum+1;
$idtext=$idtext.'{
"id":"'.$idnum.'",
"category":"'.$cyclistname.'",
"zI":"15000",
"img":"cycling.png",
"name":"'.$temptitle.'",
"title":"'.$temptitle.'",
"street_address":"'.$tempcontent.'",
"lat":"'.$latestlat.'",
"lng":"'.$latestlong.'"
},'; 
if ($cyclistname) { if (strpos($cattext,$cyclistname) == true) { } else { $cattext=$cattext.'"'.$cyclistname.'",'; } }

$temptitle='';
$tempcontent='';
$cyclistts='';
$outs='';


} // checks tracking position is today
} // loop for latest tracking position found
} // found valid instamapper id
} // end of loop for each rider

/////   ENDS CURRENT RIDER POSITION




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

     if ($jobcomments) {$tempcontent=$tempcontent.'<div>'.$jobcomments.'</div>'; }
  if ($privatejobcomments) {$tempcontent=$tempcontent.'<div>'.$privatejobcomments.'</div>'; }
   
   
   
if (($pclat) and ($pclon)) {
if ($cojmname) { if (strpos($cattext,$cojmname) == true) { } else { $cattext=$cattext.'"'.$cojmname.'",'; }}
$idnum=$idnum+1;
$idtext=$idtext.'{"id":"'.$idnum.'","category":"'.$cojmname.'","zI":"90","img":"share.png","name":"'.
$temptitle.'","title":"'.$temptitle.'","street_address":"'.$tempcontent.'","lat":"'.$pclat.'","lng":"'.$pclon.'"},'; 
$temptitle='';
$tempcontent='';
} 
} // ends check to make sure collection postcode
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

$undsql_result = mysql_query($seeifundel,$conn_id)  or mysql_error();
$totundel=mysql_affected_rows(); while ($undrow = mysql_fetch_array($undsql_result)) { extract($undrow);

if ((trim($undrow['ShipPC'])) and (trim($undrow['CollectPC']))) { $pc1 = str_replace (" ", "", $undrow['ShipPC']);
$query="SELECT * FROM  `postcodeuk` WHERE  `PZ_Postcode` =  '$pc1' LIMIT 1"; 
$result=mysql_query($query, $conn_id); $pcrow=mysql_fetch_array($result); 
$undid=$undrow['ID']; $pclon=$pcrow['PZ_easting']; $pclat=$pcrow['PZ_northing'];
$collecttime= date('H:i', strtotime($undrow['duedate'])); 
if ((date('A', strtotime($undrow['duedate']))==date('A', strtotime($undrow['deliveryworkingwindow']))) 
OR ( date('U', strtotime($undrow['deliveryworkingwindow'])>100 ) ))
 { } else { 
 
 $collecttime=$collecttime. date(' A ', strtotime($undrow['duedate']));
// echo ' deliver not same';
 } 
if ($undrow['allowdeliverww']=="1") { $collecttime=$collecttime. '- '.date('H:i A ', strtotime($undrow['deliveryworkingwindow'])); } 

$numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');
$outsd="<br/><a href='order.php?id=".$undid."'>".$undid."</a> ";

// $temptitle="
   if ( date('U', strtotime($undrow['collectiondate'])>100)) { $temptitle=$temptitle."Collected ".date('H:i A', strtotime($undrow['collectiondate'])).','; }
   else {$temptitle=$temptitle.'Uncollected, ';}

   $temptitle=$temptitle." due $collecttime by $cojmname, ";
   $tempcontent='<div><strong>Delivery not yet collected, due '.$collecttime.'</strong>'.$outsd.' From '.$undrow['CollectPC'].' to '.$undrow['ShipPC'];
   if ($undrow['status']>52)  { 
   $tempcontent='<br /><strong>'.$cojmname.'</strong> Collected at '.date('H:i A', strtotime($undrow['collectiondate'])).'.'; }
    
$tempcontent=$tempcontent." <br />$numberitems x $Service<br /> $CompanyName</div>";
  if ($jobcomments) {$tempcontent=$tempcontent.'<div>'.$jobcomments.'</div>'; }
  if ($privatejobcomments) {$tempcontent=$tempcontent.'<div>'.$privatejobcomments.'</div>'; }



if ($cojmname) {
$idnum=$idnum+1;
$idtext=$idtext.'{
"id":"'.$idnum.'",
"category":"'.$cojmname.'",
"zI":"30",
"img":"regroup.png",
"name":"'.$temptitle.'",
"title":"'.$temptitle.'",
"street_address":"'.$tempcontent.'",
"lat":"'.$pclat.'",
"lng":"'.$pclon.'"},';
if (strpos($cattext,$cojmname) == true) { } else { $cattext=$cattext.'"'.$cojmname.'",'; }
}





$temptitle='';
$tempcontent='';
} // ends check to make sure delivery postcode
} // ends loop for check for undelivered jobs










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

if ($unsrow['CollectPC']) {	 
$pc1 = str_replace (" ", "", $unsrow['CollectPC']);
$query="SELECT * 
FROM  `postcodeuk` 
WHERE  `PZ_Postcode` =  '$pc1'
LIMIT 1"; 
$result=mysql_query($query, $conn_id); 
$pcrow=mysql_fetch_array($result); 
// echo '<p>pc1 : '.$pc1.$pcrow["PZ_easting"].$pcrow["PZ_northing"].'</p>';


if (($pclat) and ($pclon)) {

$unsid=$unsrow['ID'];
$pclon=$pcrow['PZ_easting'];
$pclat=$pcrow['PZ_northing'];
$collecttime=date('H:i A ', strtotime($targetcollectiondate)); 
$numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');
$outs="<br/><a href='order.php?id=".$unsid."'>".$unsid.'</a> ';

   $temptitle='Unscheduled, Collection Due '.$collecttime;
   $tempcontent= "<div><strong>Unscheduled</strong><br/><a href=$outs Collection due $collecttime<br>From ".
   $unsrow['CollectPC'].' to '.$unsrow['ShipPC']. " <br />$numberitems x $Service<br /> $CompanyName</div>";
  
  
  if ($jobcomments) {$tempcontent=$tempcontent.'<div>'.$jobcomments.'</div>'; }
  if ($privatejobcomments) {$tempcontent=$tempcontent.'<div>'.$privatejobcomments.'</div>'; }
   
  
 $newdat=$newdat.'
// Unscheduled job
      { lat: '.$pclat.', lng: '.$pclon.', title: "'.$temptitle.'", image: "images/theft.png", zI:100, content : "'.$tempcontent.'" }, ';

$idnum=$idnum+1;
$idtext=$idtext.'
{
"id":"'.$idnum.'",
"category":"Unscheduled",
"zI":"100",
"img":"theft.png",
"name":"'.$temptitle.'",
"title":"'.$temptitle.'",
"street_address":"'.$tempcontent.'",
"lat":"'.$pclat.'",
"lng":"'.$pclon.'"},';
	  
	   if (strpos($cattext,'Unscheduled') == true) { } else { $cattext=$cattext.'"Unscheduled",'; }

$temptitle='';
$tempcontent='';
 } // ends ckeck for $pclat and $pclon
} // ends check to make sure collection postcode
} // ends loop for check for unscheduled jobs



$cattext=rstrtrim($cattext, ',');
$idtext =rstrtrim($idtext, ',');

echo '{"categories":['.$cattext.'],"markers":['; 
echo $idtext.']}';
mysql_close(); 
?>