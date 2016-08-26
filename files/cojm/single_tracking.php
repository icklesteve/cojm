<?php 
/*
    COJM Courier Online Operations Management
	single_tracking.php - Shows 1 job, to be used as a php include on a page on your website
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

echo '<div id="cojmsingletrackdiv" class="cojm">';

$postedref=trim($_POST['quicktrackref']); 
if ($postedref) {} else { $postedref = trim($_GET['quicktrackref']); }

$postedref=strip_tags($postedref);
$postedref=str_replace("'","\'", $postedref);
$postedref = strtoupper($postedref);
$postedref = substr($postedref, 0, 13);  // abcdef


if (ctype_alnum($postedref)) {
       // echo " 1 ";
    } else {
      //  echo " 2 ";
	  $postedref='';
    }

$query="SELECT * FROM Orders
INNER JOIN Clients 
INNER JOIN Services 
INNER JOIN status 
INNER JOIN Cyclist 
WHERE Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
AND Orders.status = status.status
AND Orders.CyclistID = Cyclist.CyclistID 
AND Orders.publictrackingref = ? LIMIT 0,1";











$query = "SELECT 
numberitems, ID, ShipDate, trackerid, publictrackingref, publicstatusname, Orders.status, poshname, Service, jobcomments, CollectPC,
 ShipPC, fromfreeaddress,
 enrpc1, enrpc2, enrpc3, enrpc4, enrpc5, enrpc6, enrpc7, enrpc8, enrpc9, enrpc10, enrpc11, enrpc12, enrpc13, enrpc14, enrpc15,
 enrpc16, enrpc17, enrpc18, enrpc19, enrpc20, enrft1, enrft2, enrft3, enrft4, enrft5, enrft6, enrft7, enrft8, enrft9, enrft10,
 enrft11, enrft12, enrft13, enrft14, enrft15, enrft16, enrft17, enrft18, enrft19, enrft20, tofreeaddress , targetcollectiondate, 
 duedate, deliveryworkingwindow, starttravelcollectiontime, waitingstarttime, collectiondate, starttrackpause, finishtrackpause, 
 podsurname, distance, co2saving, CO2Saved, pm10saving, PM10Saved, opsmaparea, opsmapsubarea
FROM Orders
INNER JOIN Clients 
INNER JOIN Services 
INNER JOIN status 
INNER JOIN Cyclist 
WHERE Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
AND Orders.status = status.status
AND Orders.CyclistID = Cyclist.CyclistID 
AND Orders.publictrackingref = ? LIMIT 0,1";



$query = "SELECT *
FROM Orders
INNER JOIN Clients 
INNER JOIN Services 
INNER JOIN status 
INNER JOIN Cyclist 
WHERE Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
AND Orders.status = status.status
AND Orders.CyclistID = Cyclist.CyclistID 
AND Orders.publictrackingref = ? LIMIT 0,1";








$parameters = array($postedref);
$statement = $dbh->prepare($query);
$statement->execute($parameters);
$row = $statement->fetch(PDO::FETCH_ASSOC);
	


if ($postedref=='') { echo '<h1>Please enter a Tracking Reference.</h1><hr />'; }

 else {
if ($row['ID'] == "" ) { echo '<h1>Tracking Reference Not Recognised, please re-try.</h1><hr />'; }





if ($row['ID']) {  // starts main table

$numberitems= trim(strrev(ltrim(strrev($row['numberitems']), '0')),'.');

$thistrackerid=$row['trackerid'];



echo '
<h1>'.$row['publictrackingref'].'</h1>
<hr />
<table id="cojm" class="cojm" cellspacing="0" style="table-layout:auto;">
<tbody>
<tr>
<th class="stleft"> Job Status</th>
<th class="stright">';




if ($row['status']<'101') { echo $row['publicstatusname'];
} else { // do not show job invoice status, just the complete phrase
$row['status']='100'; 

$completestatusquery="SELECT publicstatusname FROM `status` WHERE status=100 LIMIT 0,1;";
$q= $dbh->query($completestatusquery);
$completestatus = $q->fetchColumn();
echo $completestatus;

} 




echo '</th></tr>
<tr><td>'.$globalprefrow['glob5'].'</td><td>'. $row['poshname'].'</td></tr>

<tr><td>Service </td><td> '. $numberitems .' x ' .$row['Service'] .'</td></tr>';

if ($row['jobcomments']) { echo '<tr><td>Comments </td><td>'. $row['jobcomments'] .'</td></tr>';  } 

$linkCollectPC=trim($row["CollectPC"]);
$linkCollectPC = str_replace(" ", "%20", "$linkCollectPC", $count);

$linkShipPC=trim($row["ShipPC"]);
$linkShipPC = str_replace(" ", "%20", "$linkShipPC", $count);



 if ((trim($row['CollectPC'])) or (trim($row['fromfreeaddress']))) {
 
 
 echo '<tr><td colspan="2"><hr /></td></tr>';

echo '<tr><td>From</td><td>'.$row['fromfreeaddress'].' <a target="_blank" href="http://maps.google.com/maps?q='. $linkCollectPC.'">'. 
$row['CollectPC'].' </a> </td></tr>';  }




$lPC=$row["enrpc1"];if (($lPC)or($row['enrft1'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft1'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc1'].'</a></td></tr>'; }
$lPC=$row["enrpc2"];if (($lPC)or($row['enrft2'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft2'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc2'].'</a></td></tr>'; }
$lPC=$row["enrpc3"];if (($lPC)or($row['enrft3'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft3'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc3'].'</a></td></tr>'; }
$lPC=$row["enrpc4"];if (($lPC)or($row['enrft4'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft4'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc4'].'</a></td></tr>'; }
$lPC=$row["enrpc5"];if (($lPC)or($row['enrft5'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft5'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc5'].'</a></td></tr>'; }
$lPC=$row["enrpc6"];if (($lPC)or($row['enrft6'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft6'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc6'].'</a></td></tr>'; }
$lPC=$row["enrpc7"];if (($lPC)or($row['enrft7'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft7'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc7'].'</a></td></tr>'; }
$lPC=$row["enrpc8"];if (($lPC)or($row['enrft8'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft8'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc8'].'</a></td></tr>'; }
$lPC=$row["enrpc9"];if (($lPC)or($row['enrft9'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft9'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc9'].'</a></td></tr>'; }
$lPC=$row["enrpc10"];if (($lPC)or($row['enrft10'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft10'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc10'].'</a></td></tr>'; }
$lPC=$row["enrpc11"];if (($lPC)or($row['enrft11'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft11'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc11'].'</a></td></tr>'; }
$lPC=$row["enrpc12"];if (($lPC)or($row['enrft12'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft12'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc12'].'</a></td></tr>'; }
$lPC=$row["enrpc13"];if (($lPC)or($row['enrft13'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft13'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc13'].'</a></td></tr>'; }
$lPC=$row["enrpc14"];if (($lPC)or($row['enrft14'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft14'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc14'].'</a></td></tr>'; }
$lPC=$row["enrpc15"];if (($lPC)or($row['enrft15'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft15'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc15'].'</a></td></tr>'; }
$lPC=$row["enrpc16"];if (($lPC)or($row['enrft16'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft16'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc16'].'</a></td></tr>'; }
$lPC=$row["enrpc17"];if (($lPC)or($row['enrft17'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft17'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc17'].'</a></td></tr>'; }
$lPC=$row["enrpc18"];if (($lPC)or($row['enrft18'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft18'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc18'].'</a></td></tr>'; }
$lPC=$row["enrpc19"];if (($lPC)or($row['enrft19'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft19'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc19'].'</a></td></tr>'; }
$lPC=$row["enrpc20"];if (($lPC)or($row['enrft20'])){$lPC=str_replace(" ","%20","$lPC",$c);echo'<tr><td>via</td><td>'.$row['enrft20'].' <a target="_blank" href="http://maps.google.com/maps?q='.$lPC.'">'.$row['enrpc20'].'</a></td></tr>'; }

if ((trim($row['ShipPC'])) or (trim($row['tofreeaddress']))) {  
echo '<tr><td>To</td><td>'.$row['tofreeaddress'].' <a target="_blank" href="http://maps.google.com/maps?q='. $linkShipPC.'">'. $row['ShipPC'].'</a></td></tr> '; 



}

echo '<tr><td colspan="2"><hr /></td></tr>';

echo '<tr><td>Target collection ';
echo '</td><td>';
echo date('H:i A', strtotime($row['targetcollectiondate'])); 



if (date('U', strtotime($row['collectionworkingwindow']))>10) { 

echo '- '.date('H:i A', strtotime($row['collectionworkingwindow']));

}


echo date(', l jS F Y', strtotime($row['targetcollectiondate'])).'</td></tr>';






echo '<tr><td>Target delivery </td><td>'. date('H:i A', strtotime($row['duedate'])); 

if (date('U', strtotime($row['deliveryworkingwindow']))>10) { echo '- '.date('H:i A', strtotime($row['deliveryworkingwindow'])); } 


echo date(', l jS F Y', strtotime($row['duedate'])); echo '</td></tr>';








echo '<tr><td colspan="2"><hr /></td></tr>';
if ($row['starttravelcollectiontime'] > 10) { 
echo '<tr><td>En route to collection </td><td>'. date('H:i A, l jS F, Y', strtotime($row['starttravelcollectiontime'])).'</td></tr>'; } 
if ($row['waitingstarttime'] >10) { echo '<tr><td>On Site : </td><td>'. date('H:i A, l jS F Y', strtotime($row['waitingstarttime'])).'</td></tr>'; }
if ($row['collectiondate']>10) { echo '<tr><td>Time of Collection </td><td>'. date('H:i A, l jS F Y', strtotime($row['collectiondate'])).'</td></tr>'; }
if ($row['starttrackpause']>10) { echo '<tr><td>Delivery Paused at </td><td>'. date('H:i A, l jS F Y', strtotime($row['starttrackpause'])).'</td></tr>'; 
if ($row['finishtrackpause']>10) { echo '<tr><td>Delivery Resumed at </td><td>'. date('H:i A, l jS F Y', strtotime($row['finishtrackpause'])).'</td></tr>';
 } }


if ($row['ShipDate']>10) { echo '<tr><td>Time of Delivery </td><td>'. date('H:i A, l jS F Y', strtotime($row['ShipDate'])).'</td></tr>'; 

echo '<tr><td colspan="2"><hr /></td></tr>';

}

if ($row['status']>'77') {


 if ($row['podsurname']) { echo '<tr><td>Delivery Surname : </td><td>'. $row["podsurname"].'</td></tr>'; }
  
  
}





$query = "SELECT * FROM cojm_pod WHERE id = :getid LIMIT 0,1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':getid', $row['publictrackingref'], PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
if ($total=='1') {
echo "<tr><td colspan='2'><img alt='proof of delivery' style='width:100%;' 
src='".$globalprefrow['httproots']."/cojm/podimage.php?id=".$row['publictrackingref']."' /></td></tr><tr><td > </td><td> </td></tr>";
}











if ($row['distance']<>0.0) {

echo '<tr><td>Crow Flies Distance</td><td>'.$row['distance'].' '.$globalprefrow['distanceunit'].'</td></tr>'; }

if ($row['co2saving']>0.1)  {$tableco2=$row["co2saving"]; }
	 else { $tableco2 = ($row['numberitems'])*($row["CO2Saved"]); }
	 
if ($row['pm10saving']>0.1) {$tablepm10=$row["pm10saving"]; }
     else { $tablepm10=($row['numberitems'])*($row["PM10Saved"]); }	

$compco2=$tableco2;
$comppm10=$tablepm10;	 
if ($tablepm10>1000) {
$tablepm10=($tablepm10/1000);
$tablepm10 = number_format($tablepm10, 1, '.', ',');
$tablepm10= $tablepm10.'kg '; }
 else { $tablepm10=$tablepm10.' grams'; 
} 

if ($tableco2>1000) {
$tableco2=($tableco2/1000);
$tableco2 = number_format($tableco2, 1, '.', ',');
$tableco2= $tableco2.'kg '; }
 else {
 if ($tableco2>1) { $tableco2=$tableco2.' grams'; 
}} 


if ($compco2)  { echo "<tr><td>Estimated CO<sub>2</sub> Saved </td><td>". $tableco2.' </td></tr>';   
if ($comppm10) { echo "<tr><td>Estimated PM<sub>10</sub> Saved </td><td>".$tablepm10.'</td></tr>'; }}











if ($row['opsmaparea']) {
	
$areaid=$row['opsmaparea'];

$nameareaquery="SELECT opsname FROM opsmap WHERE opsmapid=$areaid LIMIT 0,1;";
$q= $dbh->query($nameareaquery);
$areaname = $q->fetchColumn();

echo ' <tr><td> Distribution Area </td><td>'.$areaname.'</td></tr>';

}



///   GPS Tracking
$collecttime=strtotime($row['starttravelcollectiontime']); 
if (strtotime($row['starttravelcollectiontime'])<60) { $collecttime = strtotime($row['collectiondate']); }


$startpause=strtotime($row['starttrackpause']); 
$finishpause=strtotime($row['finishtrackpause']);  

// if ($collecttime<10) { $collecttime=strtotime($row['starttravelcollectiontime']);  } 
$delivertime=strtotime($row['ShipDate']);

 
if (($startpause > 10) and ( $finishpause < 10)) { $delivertime=$startpause; } 
if ($startpause <10) { $startpause=9999999999; } 
if (($row['status']<86) and ($delivertime < 200)) { $delivertime=999999999999; } 
if ($row['status']<50) { $delivertime=0; } 
if ($collecttime < 10) { $collecttime=9999999999;} 

$sql = "SELECT timestamp, latitude, longitude FROM `instamapper` 
WHERE `device_key` = '$thistrackerid' 
AND `timestamp` > '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' AND '$finishpause' 
AND `timestamp` < '$delivertime' 
ORDER BY `timestamp` ASC "; 

$instamapperstmt = $dbh->prepare($sql);
$instamapperstmt->execute();
$instasumtot = $instamapperstmt->rowCount();












if (($instasumtot>0) or ($row['opsmaparea'])) { /// tracking or opsmap present so show map

echo '<script src="//maps.googleapis.com/maps/api/js?v=3.22&amp;sensor=false" type="text/javascript"></script>
<script>
  if (typeof jQuery === "undefined") {
    var script_tag = document.createElement("script");
    script_tag.setAttribute("type","text/javascript");
    script_tag.setAttribute("src","https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js")
    script_tag.onload = main; // Run main() once jQuery has loaded
    script_tag.onreadystatechange = function () { if (this.readyState == "complete" || this.readyState == "loaded") main(2); }
    document.getElementsByTagName("head")[0].appendChild(script_tag);
} else {
    main(1);
}
function main(alreadyused) {
jQuery.noConflict();
google.maps.event.addDomListener(window, "load", initialize);
}

</script>
  ';




$areajs='';
$prevts='';
$linecoords='';
$numbercords='0'; 
$numbericons='0';
$lantot='0';
$lontot='0'; 

$max_lat = '-99999';
$min_lat =  '99999';
$max_lon = '-99999';
$min_lon =  '99999';
 
$mapID=$row['ID'];

echo '<tr><td colspan="2">

<style>

div.map-sub-area-label { 

background:white;
font-size:14px;
padding:3px;
}

div.map-sub-area-selected {
font-size:18px;
font-weight:bold;
padding:6px;
}

div.printinfo {
display:none; 
position:absolute;
top: 10px;
right:10px;
width:200px;
}

div.printinfo p {
background:white;
font-size:18px;
line-height:20px;
padding-bottom:5px;
padding-right:5px;
padding-top: 3px;
text-align:right;
}

button#btn-exit-full-screen {
display:none;
border: 1px solid rgba(0, 0, 0, 0.15);
border-radius:2px;
box-shadow : 0 1px 4px -1px rgba (0, 0, 0, 0.3);
height: 32px;
width: 30px;
position:absolute;
right: 0;
top:5px;
background: transparent url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAMAAADzapwJAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MjUzMzJCNjE1Q0Q5MTFFNTg2OEE4QTA3NURERDI1OEEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MjUzMzJCNjI1Q0Q5MTFFNTg2OEE4QTA3NURERDI1OEEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyNTMzMkI1RjVDRDkxMUU1ODY4QThBMDc1REREMjU4QSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyNTMzMkI2MDVDRDkxMUU1ODY4QThBMDc1REREMjU4QSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PjTesdQAAAAYUExURZ+fn+Xl5RcXF01NTf///zIyMmFhYfDw8KnTeccAAAAFdFJOU/////8A+7YOUwAAAI5JREFUeNp0kVEOBCEIQwsVuP+NV8URx93tBwkvNVBBmwq4dTkie2Q1XTJsHK6HKImD+pLJxK6XODD0Sx3GnIb1hNNkgTR7DuCajsc2+UPV98ad297UKghFvEId61aAclOcwo1901H4jETR4rjieMaRf+Fb400tBhb7+bFNeNM8WpxHi7plkzwxIdl/BBgATG8FP/Ix0XUAAAAASUVORK5CYII=)
no-repeat scroll 50% 50% / 22px 22px;
cursor:pointer;
}

button#btn-exit-full-screen:hover { 
background: #ebebeb url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAMAAADzapwJAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MjUzMzJCNjE1Q0Q5MTFFNTg2OEE4QTA3NURERDI1OEEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MjUzMzJCNjI1Q0Q5MTFFNTg2OEE4QTA3NURERDI1OEEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyNTMzMkI1RjVDRDkxMUU1ODY4QThBMDc1REREMjU4QSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyNTMzMkI2MDVDRDkxMUU1ODY4QThBMDc1REREMjU4QSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PjTesdQAAAAYUExURZ+fn+Xl5RcXF01NTf///zIyMmFhYfDw8KnTeccAAAAFdFJOU/////8A+7YOUwAAAI5JREFUeNp0kVEOBCEIQwsVuP+NV8URx93tBwkvNVBBmwq4dTkie2Q1XTJsHK6HKImD+pLJxK6XODD0Sx3GnIb1hNNkgTR7DuCajsc2+UPV98ad297UKghFvEId61aAclOcwo1901H4jETR4rjieMaRf+Fb400tBhb7+bFNeNM8WpxHi7plkzwxIdl/BBgATG8FP/Ix0XUAAAAASUVORK5CYII=)
no-repeat scroll 50% 50% / 23px 23px;
}
 
button#printbutton { 
display:none;
border: 1px solid rgba(0, 0, 0, 0.15);
border-radius:2px;
box-shadow : 0 1px 4px -1px rgba (0, 0, 0, 0.3);
height: 32px;
width: 28px;
position:relative;
float:right;
top: 5px;
background: #ffffff url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAMAAADzapwJAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QUQ4Nzk5MzA1Q0Q3MTFFNUI5RDlCNjlCRTYyNDJCM0MiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QUQ4Nzk5MzE1Q0Q3MTFFNUI5RDlCNjlCRTYyNDJCM0MiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpBRDg3OTkyRTVDRDcxMUU1QjlEOUI2OUJFNjI0MkIzQyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpBRDg3OTkyRjVDRDcxMUU1QjlEOUI2OUJFNjI0MkIzQyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PqofrKwAAAAYUExURf7+/l9fX87OzhYWFiwsLKurq5OTk4ODgzY9+YAAAAABdFJOUwBA5thmAAAAZElEQVR42p3RWw6AIAxEUUpn6P53rLxqAxoSrz9yjJDQ1BLFSKXBZA+B8c40r3Qp7T3Uv+lT3AnfjPvZ2RIydxaCdtr7zEavBM4mI8sIDP/9F+vK/UjWW4tc15xzWKZw4DHjyRfAIQWmgeGdlAAAAABJRU5ErkJggg==)
no-repeat scroll 50% 50% / 22px 22px;
cursor:pointer;
}


button#printbutton:hover { 
background: #ebebeb url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAMAAADzapwJAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QUQ4Nzk5MzA1Q0Q3MTFFNUI5RDlCNjlCRTYyNDJCM0MiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QUQ4Nzk5MzE1Q0Q3MTFFNUI5RDlCNjlCRTYyNDJCM0MiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpBRDg3OTkyRTVDRDcxMUU1QjlEOUI2OUJFNjI0MkIzQyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpBRDg3OTkyRjVDRDcxMUU1QjlEOUI2OUJFNjI0MkIzQyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PqofrKwAAAAYUExURf7+/l9fX87OzhYWFiwsLKurq5OTk4ODgzY9+YAAAAABdFJOUwBA5thmAAAAZElEQVR42p3RWw6AIAxEUUpn6P53rLxqAxoSrz9yjJDQ1BLFSKXBZA+B8c40r3Qp7T3Uv+lT3AnfjPvZ2RIydxaCdtr7zEavBM4mI8sIDP/9F+vK/UjWW4tc15xzWKZw4DHjyRfAIQWmgeGdlAAAAABJRU5ErkJggg==)
no-repeat scroll 50% 50% / 23px 23px;
}

div.btn-full-screen {  position:relative; z-Index: 5; }

button#btn-enter-full-screen {
border: 1px solid rgba(0, 0, 0, 0.15);
border-radius:2px;
box-shadow : 0 1px 4px -1px rgba (0, 0, 0, 0.3);
cursor:pointer;
height: 32px;
position:absolute;
right: 9px;
top: 9px;
width:30px;
z-Index: 2;
background: #ffffff url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAM8AAADOCAMAAACuA6bIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RjlEMzVBN0M0QjNBMTFFNTlEMzNBQzg5OTNENEI2N0IiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RjlEMzVBN0Q0QjNBMTFFNTlEMzNBQzg5OTNENEI2N0IiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpGOUQzNUE3QTRCM0ExMUU1OUQzM0FDODk5M0Q0QjY3QiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpGOUQzNUE3QjRCM0ExMUU1OUQzM0FDODk5M0Q0QjY3QiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pr0fXxQAAAAMUExURf///83NzWpqahoaGo/lO08AAAABdFJOUwBA5thmAAAB7ElEQVR42uzdUWrEMAxFUVvd/55LodBhhv5Y0tOLuVpAkoODoogoXmvfFGut+LonYuPBgwcPnqd5Yj03Nh48ePDgwYMHDx48ePDgwYMHDx48ePC0eHZFJxYPHjx48OBJeeL4SxTT9TEsJHL3W1zm8QNl80Fc5nEDHXrCFXTmie0KOvUsU9CxxxR07vEEJTyWoIzHEZTyGIJyHj9Q0mMHynrcQGmPGSjv8QIVeKxAFR4nUInHCFTj8QEVeWxAVR4XUJnHBFTn8QAVeixAlR4HUKnHAFTrmQcVe8ZB1Z5pULlnGFTvmW0F13tmQQ2eUVCHZxLU4hkE9XjmQE2eMVCXZwrU5hkC9XlmQI2eEVCnZwLU6hkA9Xr0oGaPHNTtUYPaPWJQv0cLEnikIIVHCZJ4hCCNRwcSeWQglUcFknlEIJ1HA1LOA6prufb5RjGof15TCwpd+vmJfYHnBXTF+rwkoHWH5xd0RX77A0l6CSF7R9H0em6Yr8eDBw8ePHgOau4b/n+NBw8ePHge5inZ98fHE7v4YHjw4MGDBw8ePHjw4MGDBw8ePHjw4MGDBw8ePHjw4MGDBw8ePHjw4MGDBw8ePHjw4MGDBw8ePHjw4MGDB8/bDq2NEf9dQtt8Ix48ePDg8fZsTXw+A1vO8i3AAAYjbMv7yJmmAAAAAElFTkSuQmCC) 
no-repeat scroll 50% 50% / 22px 22px;
 }

button#btn-enter-full-screen:hover { 
background: #ebebeb url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAM8AAADOCAMAAACuA6bIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RjlEMzVBN0M0QjNBMTFFNTlEMzNBQzg5OTNENEI2N0IiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RjlEMzVBN0Q0QjNBMTFFNTlEMzNBQzg5OTNENEI2N0IiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpGOUQzNUE3QTRCM0ExMUU1OUQzM0FDODk5M0Q0QjY3QiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpGOUQzNUE3QjRCM0ExMUU1OUQzM0FDODk5M0Q0QjY3QiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pr0fXxQAAAAMUExURf///83NzWpqahoaGo/lO08AAAABdFJOUwBA5thmAAAB7ElEQVR42uzdUWrEMAxFUVvd/55LodBhhv5Y0tOLuVpAkoODoogoXmvfFGut+LonYuPBgwcPnqd5Yj03Nh48ePDgwYMHDx48ePDgwYMHDx48ePC0eHZFJxYPHjx48OBJeeL4SxTT9TEsJHL3W1zm8QNl80Fc5nEDHXrCFXTmie0KOvUsU9CxxxR07vEEJTyWoIzHEZTyGIJyHj9Q0mMHynrcQGmPGSjv8QIVeKxAFR4nUInHCFTj8QEVeWxAVR4XUJnHBFTn8QAVeixAlR4HUKnHAFTrmQcVe8ZB1Z5pULlnGFTvmW0F13tmQQ2eUVCHZxLU4hkE9XjmQE2eMVCXZwrU5hkC9XlmQI2eEVCnZwLU6hkA9Xr0oGaPHNTtUYPaPWJQv0cLEnikIIVHCZJ4hCCNRwcSeWQglUcFknlEIJ1HA1LOA6prufb5RjGof15TCwpd+vmJfYHnBXTF+rwkoHWH5xd0RX77A0l6CSF7R9H0em6Yr8eDBw8ePHgOau4b/n+NBw8ePHge5inZ98fHE7v4YHjw4MGDBw8ePHjw4MGDBw8ePHjw4MGDBw8ePHjw4MGDBw8ePHjw4MGDBw8ePHjw4MGDBw8ePHjw4MGDB8/bDq2NEf9dQtt8Ix48ePDg8fZsTXw+A1vO8i3AAAYjbMv7yJmmAAAAAElFTkSuQmCC) 
no-repeat scroll 50% 50% / 23px 23px;
}

div#map-container { 
line-height:14px;
z-Index: 4;
}

span.inlinemapcopy {
background: white none repeat scroll 0 00;
color: #111111;
font-weight:bold;
opacity:0.8;
padding-left:4px;
padding-right:6px;
}


div#cojmsingletrackdiv table#cojm.cojm tbody tr td div#map-container div.btn-full-screen div.printinfo form input.ui-state-default.ui-corner-all.address {
font-size: 14px !important;
width: calc( 100% - 12px) !important;
text-align:right;
}
</style>
<div id="map-container" >
 <div class="btn-full-screen" >
 <button id="btn-enter-full-screen" title="Full Screen Map"> &nbsp; </button>
 <div class="printinfo">
 <img alt="'.$globalprefrow['globalshortname'].' Logo" src="'.$globalprefrow['adminlogo'].'" />
 <button id="btn-exit-full-screen" title="Exit Full Screen"> </button> 
';
 
 if ($row['status']<'80') { 
 
echo ' <p title="Target Collection">'. date('l jS M Y', strtotime($row['targetcollectiondate'])).'</p>';
 
 } else { 
 
echo ' <p title="Completed">'. date('l jS M Y', strtotime($row['ShipDate'])).'</p>';
  
 
 }
 
 echo '
<p>'.$row['publictrackingref'].'</p>
<form action="#" onsubmit="showAddress(this.address.value); return false" style=" background:none;">
<input title="Address Search" type="text" name="address" placeholder="Address Search" 
class="ui-state-default ui-corner-all address" />
</form>	
</div>
</div>
<div id="map" style="float:left; width: 100%; height: 400px;"></div>
  </div>
 <div style="clear:both; "></div>';


if ($row['opsmaparea'] <>'') {

$areajs.='  var worldCoords = [
    new google.maps.LatLng(85,180),
	new google.maps.LatLng(85,90),
	new google.maps.LatLng(85,0),
	new google.maps.LatLng(85,-90),
	new google.maps.LatLng(85,-180),
	new google.maps.LatLng(0,-180),
	new google.maps.LatLng(-85,-180),
	new google.maps.LatLng(-85,-90),
	new google.maps.LatLng(-85,0),
	new google.maps.LatLng(-85,90),
	new google.maps.LatLng(-85,180),
	new google.maps.LatLng(0,180),
	new google.maps.LatLng(85,180)]; ';


$stmt = $dbh->query("SELECT AsText(g) AS POLY FROM opsmap WHERE opsmapid=$areaid");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$score=$results['0'];
   	
	$p=$score['POLY'];
$trans = array("POLYGON" => "", "((" => "", "))" => "");
$p= strtr($p, $trans);
$pexploded=explode( ',', $p );
$areajs.=' 

 var polymarkers'.$areaid.' = [ ';
foreach ($pexploded as $v) {
$transf = array(" " => ",");
$v= strtr($v, $transf);
	$areajs=$areajs.'   
	new google.maps.LatLng('.$v.'),';

	if ($row['opsmapsubarea'] <1) { // in whhich case show bounds of sub areas instead
	$vexploded=explode( ',', $v );
	$tmpi='1';
	foreach ($vexploded as $testcoord) {
	if ($tmpi % 2 == 0) {
  if($testcoord>$max_lon) { $max_lon = $testcoord; }
  if($testcoord<$min_lon)  { $min_lon = $testcoord; }
} else { 
  if($testcoord>$max_lat) { $max_lat = $testcoord; }
  if($testcoord<$min_lat)  { $min_lat = $testcoord; }
}
	$tmpi++;
	}
	}	
	} // ends each in array

$areajs = rtrim($areajs, ','); 
$areajs=$areajs.'    ]; 
 poly'.$areaid.' = new google.maps.Polygon({
	paths: [worldCoords, polymarkers'.$areaid.'],
    strokeWeight: 3,
	strokeOpacity: 0.6,
     fillColor: "#667788",
	 fillOpacity: 0.3,
	 strokeColor: "#000000",
	 clickable:false,
	 map:map
  }); ';
  
// ends top layer






// sub layers stuff
$query = "SELECT opsmapid, opsname FROM opsmap WHERE corelayer=".$areaid;


if ($row['opsmapsubarea']) { $query.=" order by FIELD(opsmapid,'".$row['opsmapsubarea']."') ASC"; }

$stmt = $dbh->prepare($query);
$stmt->execute();
$lilsumtot = $stmt->rowCount();

if ($lilsumtot>'0') { 


// for google maps markers https://github.com/googlemaps/js-rich-marker/blob/gh-pages/src/richmarker-compiled.js
$areajs.='


// alert("'.$row['opsmapsubarea'].'");


	
	(function(){var b=true,f=false;function g(a){var c=a||{};this.d=this.c=f;if(a.visible==undefined)a.visible=b;if(a.shadow==undefined)a.shadow="7px -3px 5px rgba(88,88,88,0.7)";if(a.anchor==undefined)a.anchor=i.BOTTOM;this.setValues(c)}g.prototype=new google.maps.OverlayView;window.RichMarker=g;g.prototype.getVisible=function(){return this.get("visible")};g.prototype.getVisible=g.prototype.getVisible;g.prototype.setVisible=function(a){this.set("visible",a)};g.prototype.setVisible=g.prototype.setVisible;
g.prototype.s=function(){if(this.c){this.a.style.display=this.getVisible()?"":"none";this.draw()}};g.prototype.visible_changed=g.prototype.s;g.prototype.setFlat=function(a){this.set("flat",!!a)};g.prototype.setFlat=g.prototype.setFlat;g.prototype.getFlat=function(){return this.get("flat")};g.prototype.getFlat=g.prototype.getFlat;g.prototype.p=function(){return this.get("width")};g.prototype.getWidth=g.prototype.p;g.prototype.o=function(){return this.get("height")};g.prototype.getHeight=g.prototype.o;
g.prototype.setShadow=function(a){this.set("shadow",a);this.g()};g.prototype.setShadow=g.prototype.setShadow;g.prototype.getShadow=function(){return this.get("shadow")};g.prototype.getShadow=g.prototype.getShadow;g.prototype.g=function(){if(this.c)this.a.style.boxShadow=this.a.style.webkitBoxShadow=this.a.style.MozBoxShadow=this.getFlat()?"":this.getShadow()};g.prototype.flat_changed=g.prototype.g;g.prototype.setZIndex=function(a){this.set("zIndex",a)};g.prototype.setZIndex=g.prototype.setZIndex;
g.prototype.getZIndex=function(){return this.get("zIndex")};g.prototype.getZIndex=g.prototype.getZIndex;g.prototype.t=function(){if(this.getZIndex()&&this.c)this.a.style.zIndex=this.getZIndex()};g.prototype.zIndex_changed=g.prototype.t;g.prototype.getDraggable=function(){return this.get("draggable")};g.prototype.getDraggable=g.prototype.getDraggable;g.prototype.setDraggable=function(a){this.set("draggable",!!a)};g.prototype.setDraggable=g.prototype.setDraggable;
g.prototype.k=function(){if(this.c)this.getDraggable()?j(this,this.a):k(this)};g.prototype.draggable_changed=g.prototype.k;g.prototype.getPosition=function(){return this.get("position")};g.prototype.getPosition=g.prototype.getPosition;g.prototype.setPosition=function(a){this.set("position",a)};g.prototype.setPosition=g.prototype.setPosition;g.prototype.q=function(){this.draw()};g.prototype.position_changed=g.prototype.q;g.prototype.l=function(){return this.get("anchor")};g.prototype.getAnchor=g.prototype.l;
g.prototype.r=function(a){this.set("anchor",a)};g.prototype.setAnchor=g.prototype.r;g.prototype.n=function(){this.draw()};g.prototype.anchor_changed=g.prototype.n;function l(a,c){var d=document.createElement("DIV");d.innerHTML=c;if(d.childNodes.length==1)return d.removeChild(d.firstChild);else{for(var e=document.createDocumentFragment();d.firstChild;)e.appendChild(d.firstChild);return e}}function m(a,c){if(c)for(var d;d=c.firstChild;)c.removeChild(d)}
g.prototype.setContent=function(a){this.set("content",a)};g.prototype.setContent=g.prototype.setContent;g.prototype.getContent=function(){return this.get("content")};g.prototype.getContent=g.prototype.getContent;
g.prototype.j=function(){if(this.b){m(this,this.b);var a=this.getContent();if(a){if(typeof a=="string"){a=a.replace(/^\s*([\S\s]*)\b\s*$/,"$1");a=l(this,a)}this.b.appendChild(a);var c=this;a=this.b.getElementsByTagName("IMG");for(var d=0,e;e=a[d];d++){google.maps.event.addDomListener(e,"mousedown",function(h){if(c.getDraggable()){h.preventDefault&&h.preventDefault();h.returnValue=f}});google.maps.event.addDomListener(e,"load",function(){c.draw()})}google.maps.event.trigger(this,"domready")}this.c&&
this.draw()}};g.prototype.content_changed=g.prototype.j;function n(a,c){if(a.c){var d="";if(navigator.userAgent.indexOf("Gecko/")!==-1){if(c=="dragging")d="-moz-grabbing";if(c=="dragready")d="-moz-grab"}else if(c=="dragging"||c=="dragready")d="move";if(c=="draggable")d="pointer";if(a.a.style.cursor!=d)a.a.style.cursor=d}}
function o(a,c){if(a.getDraggable())if(!a.d){a.d=b;var d=a.getMap();a.m=d.get("draggable");d.set("draggable",f);a.h=c.clientX;a.i=c.clientY;n(a,"dragready");a.a.style.MozUserSelect="none";a.a.style.KhtmlUserSelect="none";a.a.style.WebkitUserSelect="none";a.a.unselectable="on";a.a.onselectstart=function(){return f};p(a);google.maps.event.trigger(a,"dragstart")}}
function q(a){if(a.getDraggable())if(a.d){a.d=f;a.getMap().set("draggable",a.m);a.h=a.i=a.m=null;a.a.style.MozUserSelect="";a.a.style.KhtmlUserSelect="";a.a.style.WebkitUserSelect="";a.a.unselectable="off";a.a.onselectstart=function(){};r(a);n(a,"draggable");google.maps.event.trigger(a,"dragend");a.draw()}}
function s(a,c){if(!a.getDraggable()||!a.d)q(a);else{var d=a.h-c.clientX,e=a.i-c.clientY;a.h=c.clientX;a.i=c.clientY;d=parseInt(a.a.style.left,10)-d;e=parseInt(a.a.style.top,10)-e;a.a.style.left=d+"px";a.a.style.top=e+"px";var h=t(a);a.setPosition(a.getProjection().fromDivPixelToLatLng(new google.maps.Point(d-h.width,e-h.height)));n(a,"dragging");google.maps.event.trigger(a,"drag")}}function k(a){if(a.f){google.maps.event.removeListener(a.f);delete a.f}n(a,"")}
function j(a,c){if(c){a.f=google.maps.event.addDomListener(c,"mousedown",function(d){o(a,d)});n(a,"draggable")}}function p(a){if(a.a.setCapture){a.a.setCapture(b);a.e=[google.maps.event.addDomListener(a.a,"mousemove",function(c){s(a,c)},b),google.maps.event.addDomListener(a.a,"mouseup",function(){q(a);a.a.releaseCapture()},b)]}else a.e=[google.maps.event.addDomListener(window,"mousemove",function(c){s(a,c)},b),google.maps.event.addDomListener(window,"mouseup",function(){q(a)},b)]}
function r(a){if(a.e){for(var c=0,d;d=a.e[c];c++)google.maps.event.removeListener(d);a.e.length=0}}
function t(a){var c=a.l();if(typeof c=="object")return c;var d=new google.maps.Size(0,0);if(!a.b)return d;var e=a.b.offsetWidth;a=a.b.offsetHeight;switch(c){case i.TOP:d.width=-e/2;break;case i.TOP_RIGHT:d.width=-e;break;case i.LEFT:d.height=-a/2;break;case i.MIDDLE:d.width=-e/2;d.height=-a/2;break;case i.RIGHT:d.width=-e;d.height=-a/2;break;case i.BOTTOM_LEFT:d.height=-a;break;case i.BOTTOM:d.width=-e/2;d.height=-a;break;case i.BOTTOM_RIGHT:d.width=-e;d.height=-a}return d}
g.prototype.onAdd=function(){if(!this.a){this.a=document.createElement("DIV");this.a.style.position="absolute"}if(this.getZIndex())this.a.style.zIndex=this.getZIndex();this.a.style.display=this.getVisible()?"":"none";if(!this.b){this.b=document.createElement("DIV");this.a.appendChild(this.b);var a=this;google.maps.event.addDomListener(this.b,"click",function(){google.maps.event.trigger(a,"click")});google.maps.event.addDomListener(this.b,"mouseover",function(){google.maps.event.trigger(a,"mouseover")});
google.maps.event.addDomListener(this.b,"mouseout",function(){google.maps.event.trigger(a,"mouseout")})}this.c=b;this.j();this.g();this.k();var c=this.getPanes();c&&c.overlayImage.appendChild(this.a);google.maps.event.trigger(this,"ready")};g.prototype.onAdd=g.prototype.onAdd;
g.prototype.draw=function(){if(!(!this.c||this.d)){var a=this.getProjection();if(a){var c=this.get("position");a=a.fromLatLngToDivPixel(c);c=t(this);this.a.style.top=a.y+c.height+"px";this.a.style.left=a.x+c.width+"px";a=this.b.offsetHeight;c=this.b.offsetWidth;c!=this.get("width")&&this.set("width",c);a!=this.get("height")&&this.set("height",a)}}};g.prototype.draw=g.prototype.draw;g.prototype.onRemove=function(){this.a&&this.a.parentNode&&this.a.parentNode.removeChild(this.a);k(this)};
g.prototype.onRemove=g.prototype.onRemove;var i={TOP_LEFT:1,TOP:2,TOP_RIGHT:3,LEFT:4,MIDDLE:5,RIGHT:6,BOTTOM_LEFT:7,BOTTOM:8,BOTTOM_RIGHT:9};window.RichMarkerPosition=i;
})();
';


while($lilrow = $stmt->fetch(/* PDO::FETCH_ASSOC */)) {

$lilareaid=$lilrow['opsmapid'];
$lilareaname=$lilrow['opsname'];



$stmtt = $dbh->query("SELECT AsText(g) AS POLY FROM opsmap WHERE opsmapid=$lilareaid");
$results = $stmtt->fetchAll(PDO::FETCH_ASSOC);
$score=$results['0'];	
	
$p=$score['POLY'];
	
// $moreinfotext=$moreinfotext.'<br /> p is :'.$p.':';
$trans = array("POLYGON" => "", "((" => "", "))" => "");
$p= strtr($p, $trans);
//	$moreinfotext=$moreinfotext.'<br /> p is '.$p;
$pexploded=explode( ',', $p );
$areajs.='  var polymarkers'.$lilareaid.' = [ ';
foreach ($pexploded as $v) {
// $moreinfotext=$moreinfotext. "Current value of \$a: $v.\n";
$transf = array(" " => ",");
$v= strtr($v, $transf);
// $moreinfotext=$moreinfotext. " $v.\n";
$areajs.='   
	new google.maps.LatLng('.$v.'),';
	
	
	
	if ($row['opsmapsubarea']==$lilareaid) { // in which case show bounds
	$vexploded=explode( ',', $v );
	$tmpi='1';
	foreach ($vexploded as $testcoord) {
	if ($tmpi % 2 == 0) {
  if($testcoord>$max_lon) { $max_lon = $testcoord; }
  if($testcoord<$min_lon)  { $min_lon = $testcoord; }
} else { 
  if($testcoord>$max_lat) { $max_lat = $testcoord; }
  if($testcoord<$min_lat)  { $min_lat = $testcoord; }
}
	$tmpi++;
	}
	}		
	
	
} // ends each in array

$areajs= rtrim($areajs, ',').'    ]; ';


if ($row['opsmapsubarea']==$lilareaid) { 
// $areajs.= ' alert(" 707 '.$lilareaname.' "); ';


$areajs.='
 poly'.$lilareaid.' = new google.maps.Polygon({
	paths: [polymarkers'.$lilareaid.'],
    strokeWeight: 5,
	strokeOpacity: 1,
	 fillOpacity: 0,
	 strokeColor: "#FF8000",
	 clickable: false,
	 map: map
  }); ';

} else {

if ($row['opsmapsubarea']) {  $fillop=0.1; } else {  $fillop=0; }

$areajs.='
 poly'.$lilareaid.' = new google.maps.Polygon({
	paths: [polymarkers'.$lilareaid.'],
    strokeWeight: 4,
	strokeOpacity: 0.35,
     fillColor: "#111111",
	 fillOpacity: '.$fillop.',
	 strokeColor: "#000000",
	 clickable: false,
	 map: map
  }); ';
  
}
  
  
  
  $areajs.='
var bounds'.$lilareaid.' = new google.maps.LatLngBounds();
var i;  
for (i = 0; i < polymarkers'.$lilareaid.'.length; i++) {
 bounds'.$lilareaid.'.extend(polymarkers'.$lilareaid.'[i]);
}
var cent=(bounds'.$lilareaid.'.getCenter());
 '."
   marker = new RichMarker({
          position: cent,
          map: map,
          draggable: false,
          content: '<div class=";
		  $areajs.='"map-sub-area-label';
if ($row['opsmapsubarea']==$lilareaid) {  $areajs.=' map-sub-area-selected';  }
		  $areajs.='">'.$lilareaname.'</div>'."'
           });
";
  
  

} // ends lil sub area row extract

} // ends sub area

} // ends row opsmaparea<>''




echo ' 
  <script type="text/javascript"> 
function initialize() {
var geocoder = null;
   var locations = [';

   



$lattot='0';
$lontot='0';

while($map = $instamapperstmt->fetch(/* PDO::FETCH_ASSOC */)) {
$englishlast=date('H:i A D j M', $map['timestamp']); 
	 
$map['latitude']=round($map['latitude'],5);
$map['longitude']=round($map['longitude'],5);

  if($map['longitude']>$max_lon) { $max_lon = $map['longitude']; }
  if($map['longitude']<$min_lon) { $min_lon = $map['longitude']; }
  if($map['latitude']>$max_lat) { $max_lat = $map['latitude']; }
  if($map['latitude']<$min_lat)  { $min_lat = $map['latitude']; }
  
 $linecoords=$linecoords.' ['.$map['latitude'] . "," . $map['longitude'].'],';
 $numbercords++; 
 $thists=date('H:i A D j M ', $map['timestamp']);	 
	  if ($thists<>$prevts) {
	 $numbericons++;
 $comments=date('H:i D j M ', $map['timestamp']) . ' ';

	 
echo "['" . $comments ."',". $map['latitude'] . "," . $map['longitude'] . "," . $numbericons ."],"; 

$latestlat=	$map['latitude'];
$latestlong=$map['longitude'];
// $lattot=$latestat;
// $lontot=$latestlong;

$prevts=date('H:i A D j M ', $map['timestamp']); 
$lattot=$lattot+$map['latitude'];
$lontot=$lontot+$map['longitude'];
	 
} // ends loop for different minute

// ends php loop
}

	if ($numbericons>'0') {
 $lattot=($lattot / $numbericons );
 $lontot=($lontot / $numbericons );
	}
		// restarts javascript
echo '  
  ];
  
  var element = document.getElementById("map");
		
 var mapTypeIds = [];
            var mapTypeIds = ["OSM", "roadmap", "satellite", "OCM"]
			
		 var map = new google.maps.Map(element, {
                center: new google.maps.LatLng('. $globalprefrow['glob1'].','.$globalprefrow['glob2'].'),
                zoom: 11,
                mapTypeId: "OSM",
				 mapTypeControl: true,
				 scaleControl: true,
                mapTypeControlOptions: {
                mapTypeIds: mapTypeIds,
				style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                }
            });
	
     map.mapTypes.set("OSM", new google.maps.ImageMapType({
                getTileUrl: function(coord, zoom) {
                    return "https://a.tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
                },
                tileSize: new google.maps.Size(256, 256),
                name: "OSM",
				alt: "Open Street Map",
                maxZoom: 19
            }));	
	
            map.mapTypes.set("OCM", new google.maps.ImageMapType({
                getTileUrl: function(coord, zoom) {
                    return "https://a.tile.thunderforest.com/cycle/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
                },
                tileSize: new google.maps.Size(256, 256),
                name: "OCM",
				alt: "Open Cycle Map",
                maxZoom: 20
            }));

var osmcopyr="'."<span class='inlinemapcopy' > &copy; <a style='color:#444444' " .
               "href='https://www.openstreetmap.org/copyright' target='_blank'>OpenStreetMap</a> contributors</span>".'"

 var outerdiv = document.createElement("div");
outerdiv.className  = "outerdiv";
  outerdiv.style.fontSize = "10px";
  outerdiv.style.opacity = "0.7";
  outerdiv.style.whiteSpace = "nowrap";
  outerdiv.style.padding = "0px 0px 0px 6px";
  outerdiv.style.position = "fixed";
		
map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(outerdiv);				
			
google.maps.event.addListener( map, "maptypeid_changed", function() {
var checkmaptype = map.getMapTypeId();
if ( checkmaptype=="OSM" || checkmaptype=="OCM") { 
jQuery("div.outerdiv").html(osmcopyr);
jQuery("span.printcopyr").html(" " + osmcopyr+ " ");

} else { 
jQuery("div.outerdiv").text("");
jQuery("span.printcopyr").html(" Map Data &copy; Google Maps ");
}

});

// if OSM / OCM set as default, show copyright
jQuery(document).ready(function() {setTimeout(function() {
jQuery("div.outerdiv").html(osmcopyr);
jQuery("span.printcopyr").html(" " + osmcopyr + " " );
},4000);});

  var googleMapWidth =  jQuery("#map").css("width");
  var googleMapHeight = jQuery("#map").css("height");

jQuery("#btn-enter-full-screen").click(function() {
    // Gui
    jQuery("#btn-enter-full-screen").hide();

   jQuery("div.printinfo").show();
    jQuery("#btn-exit-full-screen").show();
 	
	
    jQuery("#map-container").css({
        position: "fixed",
        left: "0",
        width: "100%",
        backgroundColor: "white",
	 height:"100%",
	 top:"0px"
	 });
	 
    jQuery("#map").css({
        height: "100%"
    });

    google.maps.event.trigger(map, "resize");
	map.fitBounds(bounds);
	
    return false;
});	
		
	jQuery(document).keyup(function(e) {
     if (e.keyCode == 27) { // escape key maps to keycode `27`
//	 alert(" escape pressed ");
	 	 jQuery("#btn-exit-full-screen").trigger("click");
    }
});	
		
	jQuery("#btn-exit-full-screen").click(function() {
    jQuery("#map-container").css({
        position: "relative",
        top: 0,
        width: googleMapWidth,
        height: googleMapHeight,
        backgroundColor: "transparent"
    });

    google.maps.event.trigger(map, "resize");
	map.fitBounds(bounds);

    // Gui
    jQuery("#btn-exit-full-screen").hide();
	jQuery("#printbutton").hide();
	jQuery("div.printinfo").hide();
    jQuery("#btn-enter-full-screen").show();
	return false;
});
	

 geocoder = new google.maps.Geocoder(); 
 window.showAddress = function(address) {
    geocoder.geocode( { 
	"address": address + " , UK ",
	"region":   "uk",
    "bounds": bounds 
	}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
          map.setCenter(results[0].geometry.location);
            var infowindow = new google.maps.InfoWindow(
                { content: "<div class=&#39;info&#39;>"+address+"</div>",
				    position: results[0].geometry.location,
                map: map
                });
			infowindow.open(map);
          } else {
            alert("No results found");
          }
        } else {
          alert("Search was not successful : " + status);
        }
      });
 }

';
	
	
	
echo $areajs. '
    bounds = new google.maps.LatLngBounds();
    bounds.extend(new google.maps.LatLng('.$max_lat.', '.$min_lon.')); // upper left
    bounds.extend(new google.maps.LatLng('.$max_lat.', '.$max_lon.')); // upper right
    bounds.extend(new google.maps.LatLng('.$min_lat.', '.$max_lon.')); // lower right
    bounds.extend(new google.maps.LatLng('.$min_lat.', '.$min_lon.')); // lower left

 	map.fitBounds(bounds);
 

  var all = [
 '.$linecoords. '
  ];

var gmarkers = [];
for (var j = 0; j < all.length; j++) {
        var lat = all[j][0];
        var lng = all[j][1];
        var marker = new google.maps.LatLng(lat, lng);
     gmarkers.push(marker);
}

 var lineSymbol = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
 strokeOpacity: 0.6
 };	

  var line = new google.maps.Polyline({
    path: gmarkers,
	geodesic: true,
	strokeOpacity: 1,
	strokeWeight: 5,
	strokeColor: "#000000",
    icons: [{
    icon: lineSymbol,
    repeat: "50px"
    }],
    map: map
  });

   var image = {
    url: "'. $globalprefrow['clweb3'].'",
    size: new google.maps.Size(20, 20),
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(10, 10)
  };  
  
	var infowindow = new google.maps.InfoWindow();
    var marker, i;
    for (i = 0; i < locations.length; i++) {
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
		icon: image
      });
      google.maps.event.addListener(marker, "mouseover", (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
		  infowindow.setOptions({ disableAutoPan: true });
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
	
	}

 </script>';
 
 
 
 
 if ($instasumtot) { echo ' <br />Tracking last updated at '. $englishlast.', with '.number_format ($numbercords, 0, '.', ',').' GPS positions.'; }
  
   if ($row['status']>70) { 
echo '<p class="download"><a href="'.$globalprefrow['httproots'].'/cojm/createkml.php?id='.$row['publictrackingref'].'">Download as Google Earth KML File</a></p>'; } 

echo '</td></tr>';
 

 
} // edns check for $instasumtot tracking positions & areaid

 
 
 
 echo '</tbody></table>
 <br />
 <hr />
 ';

// echo 'Total tracking records : '.$sumtot . $error;
 }
 }
 
 echo ' <form id="quicktrackrefpage" action="" method="post" >
<input type="text" placeholder="Tracking Ref" class="capitals" tabindex="1" ';

if (!$postedref) {
echo ' autofocus="autofocus"';
}
echo ' name="quicktrackref" value="'. nl2br($postedref).'" maxlength="13" />
<input class="submit" type="submit" value="Search" /></form>';
 
echo '<hr /><p>Page created at '.date("H:i A, l jS F, Y").'.</p></div>';

?>