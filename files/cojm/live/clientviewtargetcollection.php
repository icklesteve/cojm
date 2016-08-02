<?php 
/*
    COJM Courier Online Operations Management
	clientviewtargetcollection.php - General Purpose Job Lookup
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

$alpha_time = microtime(TRUE);

$infotext='';

error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
include "changejob.php";
$adminmenu = "1";
$lengthtext='';
$numjobs='0';
$vattablecost='0';
$gpxarray = array();
$areaarray = array();
$subareaarray = array();

if (isset($_GET['clientid'])) { $clientid=trim($_GET['clientid']); } else { $clientid='all'; }
if (isset($_GET['clientview'])) { $clientview=trim($_GET['clientview']); } else { $clientview='normal'; }
if (isset($_GET['newcyclistid'])) { $newcyclistid=trim($_GET['newcyclistid']); } else { $newcyclistid=''; }
if (isset($_GET['viewselectdep'])) { $viewselectdep=trim($_GET['viewselectdep']); } else { $viewselectdep=''; }
if (isset($_GET['from'])) { $start=trim($_GET['from']); } else { $start=''; }
if (isset($_GET['to'])) { $end=trim($_GET['to']); } else { $end=''; }
if (isset($_GET['deltype'])) { $deltype=trim($_GET['deltype']);} else { $deltype='all'; }
if (isset($_GET['orderby'])) { $orderby=trim($_GET['orderby']);} else {$orderby='targetcollection'; }
if (isset($_GET['viewcomments'])) { $viewcomments=trim($_GET['viewcomments']);} else {$viewcomments=''; }
if (isset($_GET['timetype'])) { $timetype=trim($_GET['timetype']);} else { $timetype='tarcollect'; }
if (isset($_GET['servicetype'])) { $servicetype=trim($_GET['servicetype']);} else { $servicetype='all'; }
if (isset($_GET['statustype'])) { $statustype=trim($_GET['statustype']);} else { $statustype='all'; }






// do initial sweep for ID's + status, sort by collection date if all completeish  ( order by status desc limit 1 ?? )









// echo ' here '.$deltype;

if ($start) {

$trackingtext='';
$tstart = str_replace("%2F", ":", "$start", $count);
$tstart = str_replace("/", ":", "$start", $count);
$tstart = str_replace(",", ":", "$tstart", $count);
$tstart = str_replace("-", ":", "$tstart", $count);
$temp_ar=explode(":","$tstart"); 
$day=$temp_ar['0']; 
$month=$temp_ar['1']; 
$year=$temp_ar['2']; 
$hour='00';
$minutes='00';
$second='00';
$sqlstart= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
$dstart= date("U", mktime($hour, $minutes, $second, $month, $day, $year));
if ($year) { $inputstart=$day.'/'.$month.'/'.$year; }
} else  { // nothing posted
$inputstart='';
$sqlstart='';

}



if ($end) {

$tend = str_replace("%2F", ":", "$end", $count);
$tend = str_replace("/", ":", "$end", $count);
$tend = str_replace(",", ":", "$tend", $count);
$tend = str_replace("-", ":", "$tend", $count);
$temp_ar=explode(":",$tend); 
$day=$temp_ar['0'];
$month=$temp_ar['1'];
$year=$temp_ar['2'];
$hour='23';
$minutes= '59';
$second='59';
if ($year) { $inputend=$day.'/'.$month.'/'.$year; }
$sqlend= date("Y-m-d H:i:s", mktime(23, 59, 59, $month, $day, $year));
$dend=date("U", mktime(23, 59, 59, $month, $day, $year));

}

else { 

$sqlend='3000-12-25 23:59:59'; 
$inputend=''; 
$dend='';

}

$title='COJM ';


?><!DOCTYPE html> 
<html lang="en"> 
<head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<?php
echo '
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
';

?>
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" /> <!----  for ie table floathead   -->
<script type="text/javascript" src="js/jquery-ui.1.8.7.min.js"></script>  <!----  for table floathead   -->
<script type="text/javascript" src="js/jquery.floatThead.js"></script>  <!----  for table floathead   -->
<title><?php print ($title); ?> View by Client and Date</title>
</head>
<body>
<? 
$filename="clientviewtargetcollection.php";
include "cojmmenu.php"; 
echo '<div class="Post">
<form action="clientviewtargetcollection.php" method="get" id="cvtc"> 
	<div class="ui-state-highlight ui-corner-all p15">
';
	
	

echo ' <select id="combobox" size="14" name="clientid">
<option value="">Select one...</option>
<option 
';
 if ($clientid=="all") {echo ' SELECTED ';} 
echo ' value="all">All Clients</option>';

$query = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient>0 ORDER BY CompanyName";
$result_id = mysql_query ($query, $conn_id);
while (list ($CustomerID, $CompanyName) = mysql_fetch_row ($result_id))
{
	$CustomerID = htmlspecialchars ($CustomerID);
	$CompanyName = htmlspecialchars ($CompanyName);
		print"<option ";
	if ($CustomerID == $clientid) {echo "SELECTED "; } ;
		print ("value=\"$CustomerID\">$CompanyName</option>\n");
}

echo '</select>		';

	
	
	
	
	
	

$query = "SELECT depnumber, depname FROM clientdep WHERE associatedclient = '$clientid' ORDER BY depname"; 
$result_id = mysql_query ($query, $conn_id) or mysql_error();  

$sumtot=mysql_affected_rows();

// echo $sumtot.' Department(s) : '.$viewselectdep;	

if ($sumtot>'0') {

echo ' <select class="ui-state-default ui-corner-left" name="viewselectdep" >
<option value="">All Departments</option>';
 while (list ($CustomerIDlist, $CompanyName) = mysql_fetch_row ($result_id)) { 
 
 $CustomerID = htmlspecialchars ($CustomerID); 
$CompanyName = htmlspecialchars($CompanyName); 
print'<option ';

if ($CustomerIDlist==$viewselectdep) { echo ' SELECTED '; }

echo 'value= "'.$CustomerIDlist.'" >'.$CompanyName.'</option>';} 

echo '</select> ';


} else { $viewselectdep=''; }
	
	
	
	
	
	
	
	
	
	
//	echo ' Collections From ';



echo ' <select name="timetype" class="ui-state-highlight ui-corner-left">
<option '; if ($timetype=='tarcollect') { echo 'selected'; } echo ' value="tarcollect">Target Collection</option>
<option '; if ($timetype=='collect') { echo 'selected'; } echo ' value="collect">Collections &amp; Resumes</option>
<option '; if ($timetype=='deliver') { echo 'selected'; } echo ' value="deliver">Deliveries</option>
<option '; if ($timetype=='actualcollect') { echo 'selected'; } echo ' value="actualcollect">Just Collections</option>
<option '; if ($timetype=='tardeliver') { echo 'selected'; } echo ' value="tardeliver">Target Delivery</option>
</select>';






// actualcollect






echo ' <input class="ui-state-default ui-corner-all pad" size="10" type="text" name="from" value="'. $inputstart .'" id="rangeBa" />			
To		<input class="ui-state-default ui-corner-all pad"  size="10" type="text" name="to" value="'.  $inputend.'" id="rangeBb" />			
';






// ends end of check for departments


echo '<hr />';


// echo $globalprefrow['glob5'].' ';

$query = "SELECT CyclistID, cojmname, trackerid FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 
$result_id = mysql_query ($query, $conn_id); 
echo '<select name="newcyclistid" class="ui-state-highlight ui-corner-left">';

echo '<option value="all">All '.$globalprefrow['glob5'].'s</option>';

while (list ($CyclistID, $cojmname, $trackerid) = mysql_fetch_row ($result_id)) { print ("<option "); 
if ($CyclistID == $newcyclistid) {echo ' selected="selected" '; $thistrackerid=$trackerid; } 
print ("value=\"$CyclistID\">$cojmname</option>"); } 
print ("</select> "); 





// echo $servicetype;


$query = "
SELECT ServiceID, 
Service 
FROM Services 
ORDER BY serviceorder DESC, ServiceID ASC"; 
$result_id = mysql_query ($query, $conn_id); 
print (" <select class=\"ui-state-default ui-corner-left\" name=\"servicetype\"  >"); 

echo '<option '; if ($servicetype=='all') { echo 'selected'; } echo ' value="all">All Services</option>';

while (list ($ServiceID, $Service) = mysql_fetch_row ($result_id)) {	$ServiceID = htmlspecialchars ($ServiceID);	
$Service = htmlspecialchars ($Service); print ("
<option "); 
{	if ($ServiceID == $servicetype) echo " SELECTED "; }	
print ("value=\"$ServiceID\">$Service</option>"); } print ("</select>"); 

















echo ' <select name="deltype" class="ui-state-highlight ui-corner-left">
<option '; if ($deltype=='all') { echo 'selected'; } echo ' value="all">All Job Types</option>
<option '; if ($deltype=='deliveries') { echo 'selected'; } echo ' value="deliveries">Deliveries</option>
<option '; if ($deltype=='hourly') { echo 'selected'; } echo ' value="hourly">Hourly Rate</option>
<option '; if ($deltype=='licensed') { echo 'selected'; } echo ' value="licensed">Licensed</option>
<option '; if ($deltype=='other') { echo 'selected'; } echo ' value="other">Others</option>
</select>';


echo ' Order By <select name="orderby" class="ui-state-highlight ui-corner-left">
<option '; if ($orderby=='targetcollection') { echo 'selected'; } echo ' value="targetcollection">Target Collection</option>
<option '; if ($orderby=='pricehilow') { echo 'selected'; } echo ' value="pricehilow">Price High to Low</option>
<option '; if ($orderby=='status') { echo 'selected'; } echo ' value="status">Job Status</option>
<option '; if ($orderby=='id') { echo 'selected'; } echo ' value="id">Job Reference</option>
<option '; if ($orderby=='numberitems') { echo 'selected'; } echo ' value="numberitems">Number Items</option>
<option '; if ($orderby=='nextaction') { echo 'selected'; } echo ' value="nextaction">Next Action Time (Like Homepage)</option>
</select>';


echo ' <select name="clientview" class="ui-state-highlight ui-corner-left">
<option '; if ($clientview=='normal') { echo 'selected'; } echo ' value="normal">Normal View</option>
<option '; if ($clientview=='client') { echo 'selected'; } echo ' value="client">Copy to Client</option>
<option '; if ($clientview=='clientprice') { echo 'selected'; } echo ' value="clientprice">Copy with Price</option>
</select>';

echo ' <select name="viewcomments" class="ui-state-highlight ui-corner-left">
<option '; if ($viewcomments=='') { echo 'selected'; } echo ' value="normal">Icon Comments</option>
<option '; if ($viewcomments=='1') { echo 'selected'; } echo ' value="1">Display Comments</option>
</select> ';



echo ' <select name="statustype" class="ui-state-highlight ui-corner-left">
<option '; if ($statustype=='all') { echo 'selected'; } echo ' value="all">All Statuses</option>
<option '; if ($statustype=='notinvoicedcomp') { echo 'selected'; } echo ' value="notinvoicedcomp">Uninvoiced Complete</option>
<option '; if ($statustype=='notinvoiced') { echo 'selected'; } echo ' value="notinvoiced">Uninvoiced All</option>
</select> ';



echo '
<button class="newjobsubmit" type="submit" >Search</button>
</div></form>';


 $sql = "
SELECT * FROM Orders
INNER JOIN Services 
INNER JOIN Cyclist
INNER JOIN status
INNER JOIN Clients 
ON Orders.ServiceID = Services.ServiceID 
AND Orders.CyclistID = Cyclist.CyclistID 
AND Orders.status = status.status 
AND Orders.CustomerID = Clients.CustomerID 

WHERE 

";


 if ($timetype=='collect') { $sql=$sql."  
 
( ( Orders.collectiondate > '$sqlstart' AND Orders.collectiondate < '$sqlend'  ) 
 
 or (
 
 
 Orders.finishtrackpause > '$sqlstart' AND Orders.finishtrackpause < '$sqlend' 
 
 
 ) )
 
 
 "; }



 if ($timetype=='deliver') { $sql=$sql."  
 
 ( Orders.ShipDate > '$sqlstart' AND Orders.ShipDate < '$sqlend'  ) 
 
 "; } 
 
 
 
  if ($timetype=='tarcollect') { $sql=$sql."  
 
 ( Orders.targetcollectiondate > '$sqlstart' AND Orders.targetcollectiondate < '$sqlend'  ) 
 
 "; } 
 
 
   if ($timetype=='actualcollect') { $sql=$sql."  
 
 ( Orders.collectiondate > '$sqlstart' AND Orders.collectiondate < '$sqlend'  ) 
 
 "; } 
 
 
 
  if ($timetype=='tardeliver') { $sql=$sql."  
 
 ( Orders.duedate > '$sqlstart' AND Orders.duedate < '$sqlend'  ) 
 
 "; } 
 

 
 if ($statustype=='notinvoiced') { 
 $sql.=  " AND Orders.status < '110' ";
 }

 
 
 if ($statustype=='notinvoicedcomp') { 
 $sql.=  " AND Orders.status < '110' AND Orders.status > '99' ";
 } 
 
 
 
 
 
 


if ($clientid<>'all') { $sql = $sql. " AND Orders.CustomerID = '$clientid' "; }
if ($viewselectdep<>'') { $sql = $sql. " AND Orders.orderdep = '$viewselectdep' "; }
if ($newcyclistid<>'all') { $sql=$sql. " AND Orders.CyclistID = '$newcyclistid' "; }
if ($deltype=='licensed') { $sql=$sql." AND Services.LicensedCount ='1' "; }
if ($deltype=='deliveries') { $sql=$sql." AND Services.UnlicensedCount='1' "; }
if ($deltype=='hourly') { $sql=$sql." AND Services.hourlyothercount='1' "; }
if ($deltype=='other') { $sql=$sql." AND Services.UnlicensedCount<>'1' AND Services.hourlyothercount<>'1' AND Services.LicensedCount <>'1' "; }

if ($servicetype<>'all') { $sql=$sql." AND Services.ServiceID ='$servicetype' "; }






if ($orderby=='targetcollection') { $sql=$sql." ORDER BY `Orders`.`targetcollectiondate` ASC "; }
if ($orderby=='pricehilow') { $sql=$sql." ORDER BY `Orders`.`FreightCharge` DESC,  `Orders`.`targetcollectiondate` DESC "; }
if ($orderby=='status') { $sql=$sql." ORDER BY `Orders`.`status` ASC "; }
if ($orderby=='id') { $sql=$sql." ORDER BY `Orders`.`ID` ASC "; }
if ($orderby=='numberitems') { $sql=$sql." ORDER BY `Orders`.`numberitems` DESC "; }
if ($orderby=='nextaction') { $sql=$sql." ORDER BY `Orders`.`nextactiondate` DESC "; }


// normal
// client
// clientprice




if ($globalprefrow['showdebug']>0) {

 echo '<br />'. $sql;

}


$sql_result = mysql_query($sql,$conn_id)  or mysql_error();
$num_rows = mysql_num_rows($sql_result);
$firstrun='1';
$today = date(" H:i A, D j M");




if ($num_rows>'0') {


$numjobs=$num_rows;

if ($clientview<>'normal') { echo '<br />'; }



$tablecost='';
$tabletotal='';
$temptrack='';
$tottimedif='';
$secmod='';

while ($row = mysql_fetch_array($sql_result)) {
     extract($row);

	 
if ($row['opsmaparea']) { array_push($areaarray,$row['opsmaparea']); }
array_push($subareaarray,$row['opsmapsubarea']);

	 
// echo $row['opsmaparea'].$row['opsmapsubarea'].' found, in array :';
// print_r(array_values($areaarray)).'<br />';

	 
if ($firstrun=='1') { echo '<div class="vpad"></div>

<table id="clientviewtargetcollection" class="acc" ';

if ($clientview=='normal') { echo ' style="width:100%;"  '; }

echo '><thead><tr><th scope="col">COJM ID</th>';

if ($clientid=='all')  { echo '<th scope="col">Client</th>'; }

if (($row['isdepartments']=='1') and ($clientid<>'all'))  { echo '<th scope="col">Department</th>'; }


$i='1';

if ($newcyclistid=='all') { echo '<th scope="col">'.$globalprefrow['glob5'].'</th>'; }

echo '<th scope="col">Service</th>';

if ($clientview<>'client') { echo '<th title="Incl. VAT" scope="col">Net Cost</th>'; }

echo '<th scope="col">Job Status</th>
<th scope="col">To / From</th>
<th scope="col">Target Collection</th>
<th scope="col">Collection</th>
<th scope="col">Target Delivery</th>
<th scope="col">Delivery</th>
</tr>
</thead>
<tbody>

';


// echo '<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td>';
// if ($newcyclistid=='all') { echo '<td> </td>'; }
// if ($clientview<>'client') { echo '<td> </td>'; }
// if ($row['isdepartments']=='1')  { echo '<td> </td'; } 
// if ($clientid=='all')  { echo '<td> </td>'; }

// echo '</tr>';



$firstrun='0';

}	 // ends first run




echo '<tr><td>';


if ($clientview<>'normal') {

echo '<a target="_blank" href="'. $globalprefrow['locationquickcheck'].'?quicktrackref='; 
echo $row['publictrackingref'].'">'. $row['publictrackingref'].'</a>';
 } else {
echo '<a href="order.php?id='. $ID.'">'. $ID.'</a>';



 if ($viewcomments=='1') { 

 $shortcomments = (substr($row['jobcomments'],0,30));
$privateshortcomments = (substr($row['privatejobcomments'],0,30));
 
 
 
echo ' '.$shortcomments.' '.$privateshortcomments;







 }
 
 
 else {


if (($row['jobcomments']) or ($row['privatejobcomments']) or ($row['podsurname']) ) {

echo ' <img src="images/page_java.gif" alt="Job Comments" title="'. $row['jobcomments'].' ' . $row['privatejobcomments'].' ' . $row['podsurname'].'" > ' ;


}


}






// adds tracking icon if data present

$trackingtext='';
$thistrackerid=$row['trackerid'];

 $startpause=strtotime($row['starttrackpause']); 
$finishpause=strtotime($row['finishtrackpause']); $collecttime=strtotime($row['collectiondate']); 
$delivertime=strtotime($row['ShipDate']); if (($startpause > '10') and ( $finishpause < '10')) { $delivertime=$startpause; } 
if ($startpause <'10') { $startpause='9999999999'; } if (($row['status']<'86') and ($delivertime < '200')) { $delivertime='9999999999'; } 
if ($row['status']<'50') { $delivertime='0'; } if ($collecttime < '10') { $collecttime='9999999999'; } 
$findlast="SELECT timestamp FROM `instamapper` 
WHERE `device_key` = '$thistrackerid' 
AND `timestamp` >= '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' 
AND '$finishpause' 
AND `timestamp` <= '$delivertime' 
ORDER BY `timestamp` ASC 
LIMIT 1"; 
$sql_result2 = mysql_query($findlast,$conn_id)  or mysql_error(); 
while ($foundlast = mysql_fetch_array($sql_result2)) { extract($foundlast); $englishlast= date('H:i A D jS ', $foundlast['timestamp']); 
$trackingtext= 'Tracking started ' . $englishlast . ', '; } 

$findlast="SELECT timestamp FROM `instamapper` 
WHERE `device_key` = '$thistrackerid' 
AND `timestamp` >= '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' 
AND '$finishpause' 
AND `timestamp` <= '$delivertime' 
ORDER BY `timestamp` DESC 
LIMIT 1"; 
$sql_result2 = mysql_query($findlast,$conn_id)  or mysql_error(); 
while ($foundlast = mysql_fetch_array($sql_result2)) { extract($foundlast); $englishlast= date('H:i A D jS', $foundlast['timestamp']); 
$trackingtext= $trackingtext.' Last updated ' . $englishlast . '.'; }



if ($trackingtext) {
echo '<a href="../createkml.php?id='. $row['publictrackingref'].'"><img src="images/icon_world_dynamic.gif" alt="Download Tracking" title="'.$trackingtext.'"></a>';

array_push($gpxarray,$row['publictrackingref']);
}




$query = "SELECT * FROM cojm_pod WHERE id = :getid LIMIT 0,1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':getid', $row['publictrackingref'], PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
if ($total=='1') {

echo ' <img src="images/noteb_pod_20x21.png" style="height:19px; width:18px;" alt="POD" title="POD" > ';
}









echo '</td>';


} 



if (($row['isdepartments']=='1') and ($clientid<>'all'))  {

echo '<td>';

$tempdep=$row['orderdep'];

$depsql="SELECT * from clientdep 
INNER JOIN Orders
On Orders.orderdep=clientdep.depnumber 
WHERE Orders.orderdep='$tempdep' LIMIT 0,1";
$dsql_result = mysql_query($depsql,$conn_id)  or mysql_error();

while ($drow = mysql_fetch_array($dsql_result)) {
     extract($drow);
echo '<a href="new_cojm_department.php?clientid='.$row['CustomerID'].'#tabs-'.$drow['depnumber'].'">'.$drow['depname'].'</a>';	 
}

echo '</td>'; }

if ($clientid=='all')  { echo '<td>';

echo '<a href="new_cojm_client.php?clientid='.$row['CustomerID'].'">'.$row['CompanyName'].'</a>';

$tempdep=$row['orderdep'];

$depsql="SELECT depname from clientdep 
INNER JOIN Orders
On Orders.orderdep=clientdep.depnumber 
WHERE Orders.orderdep='$tempdep' LIMIT 0,1";
$dsql_result = mysql_query($depsql,$conn_id)  or mysql_error();

while ($drow = mysql_fetch_array($dsql_result)) {
     extract($drow);
echo ' (<a href="new_cojm_department.php?clientid='.$row['CustomerID'].'#tabs-'.$row['orderdep'].'">'.$drow['depname'].'</a>) ';
}

echo '</td>'; }



if ($newcyclistid=='all') {
	
	echo '<td>';
	
if ($row['CyclistID']<>1) {	
	
if ($clientview<>'normal') { echo ''.$row['poshname'].' '; } else {

echo '<a href="cyclist.php?thiscyclist='.$row['CyclistID'].'">'.$row['cojmname'].'</a>';
 echo  ' '; }

 
} // ends rider not unallocated
 
 
 echo '</td>';
 
 }


echo '<td>'. formatmoney($row["numberitems"]) .' x '. $row['Service'];


echo '</td>';

if ($clientview<>'client') { echo '<td  class="rh" title="&'.$globalprefrow['currencysymbol'].number_format(($row['vatcharge']), 2, '.', ',').' VAT" >&'. $globalprefrow['currencysymbol']. number_format(($row['vatcharge']+$row["FreightCharge"]), 2, '.', ','); 

if (($row["numberitems"])>'1') { echo ' ( &'.$globalprefrow['currencysymbol'].number_format((($row['vatcharge']+$row["FreightCharge"]) / $row["numberitems"] ), 2, '.', ',') . ' ea ) '; }


echo '</td>'; }
echo '<td>'. $row['statusname'].' </td><td>';


if ((trim($row['fromfreeaddress'])) or (trim($row['CollectPC']))) { echo ' PU ';}

echo $row['fromfreeaddress']. ' ';  

if (trim($row['CollectPC'])) { 

$linkCollectPC = strtoupper(str_replace(' ','+',$row['CollectPC'])); 

echo '<a target="_blank" class="newwin" href="http://maps.google.com/maps?q='. $linkCollectPC.'">'. $row['CollectPC'].'</a>'; }


if ((trim($row['fromfreeaddress'])) or (trim($row['CollectPC']))) { 
if ( (trim($row['tofreeaddress'])) or (trim($row['ShipPC']))) { echo '<br /> '; }
}





if ( (trim($row['tofreeaddress'])) or (trim($row['ShipPC']))) { echo ' To '; }

echo $row['tofreeaddress'].' ';

if (trim($row['ShipPC'])) {
	
	
$linkShipPC = strtoupper(str_replace(' ','+',$row['ShipPC'])); 	
	
echo ' <a target="_blank" class="newwin" href="http://maps.google.com/maps?q='. $linkShipPC.'">'. $row['ShipPC'].'</a>'; }
 
 
 
 
 
if ($row['opsmaparea']) {
$opsmaparea=$row['opsmaparea'];

$areaquery = "SELECT opsmapid, opsname, descrip, istoplayer FROM opsmap WHERE opsmapid='$opsmaparea' "; 

 $areaqueryres = mysql_query ($areaquery, $conn_id); 
 
 while (list ($listopsmapid, $listopsname, $descrip, $istoplayer ) = mysql_fetch_row ($areaqueryres)) {
 echo $listopsname.' ';
   
 if ($row['opsmapsubarea']) { echo ' ( Sub Area '.$row['opsmapsubarea'].' ) ';  }

} 
}
 
 
 
 
echo '</td><td>';



echo date('H:i D j M ', strtotime($row['targetcollectiondate'])); 

if (date('Y')<>date('Y', strtotime($row['targetcollectiondate']))) { echo date('Y', strtotime($row['targetcollectiondate']));  }


echo '</td><td class="strong">';
if ($row['collectiondate']>'2') { echo date('H:i D j M ', strtotime($row['collectiondate']));

if (date('Y')<>date('Y', strtotime($row['collectiondate']))) { echo date('Y', strtotime($row['collectiondate']));  } } 
 
 echo '</td><td>'. date('H:i D j M ', strtotime($row['duedate']));
 
if (date('Y')<>date('Y', strtotime($row['duedate']))) { echo date('Y', strtotime($row['duedate']));  }
 
 
 echo '</td><td class="strong">';
if ($row['ShipDate']>'2') {echo date('H:i D j M ', strtotime($row['ShipDate']));

if (date('Y')<>date('Y', strtotime($row['ShipDate']))) { echo date('Y', strtotime($row['ShipDate']));  }}
  
echo '</td></tr>
';

$tablecost = $tablecost + $row["FreightCharge"];
$vattablecost = $vattablecost + $row['vatcharge'];
$tabletotal = $tabletotal + $row['numberitems'];

$temptrack=$temptrack.'<input type="hidden" name="tr'.$i.'" value="'.$ID.'" />';

$i++;




$secmod='0';



if ((($row['status']) >'76' ) and ($row['CyclistID']<>'1')) {
$tottimec=strtotime($row['starttrackpause']);
$tottimed=strtotime($row['finishtrackpause']);
if (($tottimec>'1') AND ($tottimed>'1')) { $secmod=($tottimed-$tottimec); }
$tottimea=strtotime($row['collectiondate']); 
$tottimeb=strtotime($row['ShipDate']); 
$tottimedif=($tottimedif+$tottimeb-$tottimea-$secmod);


} // ends check greater than status 76




// echo $tottimedif.'<br />';






 }
 
// echo '<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td>';
// if ($newcyclistid=='all') { echo '<td> </td>'; }
//if ($clientview<>'client') { echo '<td> </td>'; }
//if ($row['isdepartments']=='1')  { echo '<td> </td>'; }
//if ($clientid=='all')  { echo '<td> </td>'; }
//echo '</tr>';

echo '</tbody></table><div class="vpad"></div>';


if ($clientview<>'normal') { echo '<br />'; }



$echotablecost=number_format(($tablecost), 2, '.', ',');
$echovattablecost=number_format(($vattablecost), 2, '.', ',');
$echotottablecost=number_format(($vattablecost+$tablecost), 2, '.', ',');



if (($gpxarray) or ($areaarray)) {

// print_r($gpxarray);

$sergpxarray=serialize($gpxarray); 




$arearesult = array_unique($areaarray);

// $arearesult= (array_filter($resulta));

reset($arearesult);


$arearesult = array_values($arearesult);

$sareagpxarray=serialize($arearesult); 

$sresulta= (array_filter($subareaarray));
$sarearesult = array_unique($sresulta);
$ssareagpxarray=serialize($sarearesult); 




echo '<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<form action="batchkml.php" method="post" name="gpxarrayform" id="gpxarrayform">
<input type="hidden" name="gpxarray" value='."'".$sergpxarray."'".'>
<input type="hidden" name="areagpxarray" value='."'".$sareagpxarray."'".'>
<input type="hidden" name="sareagpxarray" value='."'".$ssareagpxarray."'".'>
<button name="btn_submit" value="kml" type="submit">Download Tracking in KML </button>
<button name="btn_submit" value="kmz" type="submit">Download Tracking in KMZ (smaller) </button>
<input type="text" name="projectname"  class="ui-state-highlight ui-corner-all" placeholder="Add Project Name" size="20" />
</form></div>
';



echo '<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<form action="batchhtmltracking.php" method="post" name="batchhtmltrackingform" id="batchhtmltrackingform">
<input type="hidden" name="gpxarray" value='."'".$sergpxarray."'".'>
<input type="hidden" name="areagpxarray" value='."'".$sareagpxarray."'".'>
<input type="hidden" name="sareagpxarray" value='."'".$ssareagpxarray."'".'>
<button name="btn_submit" value="htmlpreview" type="submit">View Tracking in 1 page </button>
<button name="btn_submit" value="batchhtmltracking" type="submit">Download Tracking to display map in 1 html file </button>
<input type="text" name="projectname" class="ui-state-highlight ui-corner-all" placeholder="Add Project Name" size="20" /> ';


if ($arearesult) { echo '

Show Areas : 
<input type="checkbox" name="showarea" value="1" checked />';  } 


if ($sarearesult) { echo '

&amp; Sub Areas : 
<input type="checkbox" name="showsubarea" value="1" checked />';  }




echo ' </form></div> ';




}

// $gpxarray



echo '<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">';



echo '

<span title="Incl. VAT">Grand Total </span>: &'.  $globalprefrow['currencysymbol'].$echotottablecost.'<br />

<span title="Excl. VAT">Total Excl. VAT  </span>: &'.  $globalprefrow['currencysymbol'].$echotablecost.'<br />
Total VAT : &'.  $globalprefrow['currencysymbol'].$echovattablecost.'<br />

Total Jobs : '.$numjobs.'<br />
<span title="Incl. VAT">
Net Avg per Job : &'.$globalprefrow['currencysymbol'].number_format((($tablecost+$vattablecost)/$numjobs), 2, '.', ',').' </span>

<br />
Total Volume : '. $tabletotal.'<br />
';

if ($tabletotal>'0') {

echo '
<span title="Incl. VAT">Net Avg per Volume : &'.$globalprefrow['currencysymbol'].number_format((($tablecost+$vattablecost)/$tabletotal), 2, '.', ',').'
</span>
<br />

';

}
$inputval = $tottimedif; // USER DEFINES NUMBER OF SECONDS FOR WORKING OUT | 3661 = 1HOUR 1MIN 1SEC 
$unitd =86400;
$unith =3600;        // Num of seconds in an Hour... 
$unitm =60;            // Num of seconds in a min... 
$dd = intval($inputval / $unitd);       // days
$hh_remaining = ($inputval - ($dd * $unitd));
$hh = intval($hh_remaining / $unith);    // '/' given value by num sec in hour... output = HOURS 
$ss_remaining = ($hh_remaining - ($hh * $unith)); // '*' number of hours by seconds, then '-' from given value... output = REMAINING seconds 
$mm = intval($ss_remaining / $unitm);    // take remaining sec and devide by sec in a min... output = MINS 
$ss = ($ss_remaining - ($mm * $unitm));        // '*' number of mins by seconds, then '-' from remaining sec... output = REMAINING seconds. 
if ($dd==1) {$lengthtext=$lengthtext. $dd . " day "; } if ($dd>1 ) { $lengthtext=$lengthtext. $dd . " days "; }
if ($hh==1) {$lengthtext=$lengthtext. $hh . " hr "; } if ($hh>1) { $lengthtext=$lengthtext. $hh . " hrs "; }
if ($mm>1 ) {$lengthtext=$lengthtext. $mm . " mins. "; } if ($mm==1) {$lengthtext=$lengthtext. $mm . " min. "; }
// number_format($tablecost, 2, '.', '')
 if ($mm) {  $lengthtext=$lengthtext. "(". number_format((($dd*24)+($mm/60)+$hh), 2, '.', ''). 'hrs)'; } 
// echo ($tottimedif/60).' minutes';

if (trim($lengthtext)) { 


echo '<span title="Jobs with an allocated rider">Total PU to drop</span>: ' .$lengthtext .'

<br /> 


<span title="Incl. VAT">Net Avg per Hour : &'.$globalprefrow['currencysymbol'].number_format((($tablecost+$vattablecost)/((($dd*24)+($mm/60)+$hh))), 2, '.', ',').'
</span>
<br />


';



}






if ($dend) { 


// echo '<br />'. round(((($dend-$dstart)+'1')/'3600')/'24').' days'; 


// echo '<br /><span title="Incl. VAT">Net &'.  $globalprefrow['currencysymbol'].round(($tablecost+$vattablecost)/round((((($dend-$dstart)+'1')/'3600')/'24')),2) .' average per day. </span>';





}


echo '</div></div>';



// <form action="createbatchkml.php" method="post">
// echo $temptrack; 
// <button type="submit"> Download all tracking data for these jobs</button>
// </form>
// <br />




} else {

if ($start<>'') {

echo '<h2>No Results Found</h2>';}
}


 echo '<div class="vpad"></div><div class="line"></div><br /></div>';

 
 echo '<script type="text/javascript">	
$(document).ready(function() {	
	$( "#combobox" ).combobox();
	 
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
	    $("#rangeBa, #rangeBb").daterangepicker();  
			 });
			 
			 
			 
function comboboxchanged() { }			 
			


$("#clientviewtargetcollection").floatThead({
	position: "fixed",
	top: 36
});			

</script>';

include 'footer.php';

mysql_close();  

echo '</body></html>';