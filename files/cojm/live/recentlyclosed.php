<?php 
$alpha_time = microtime(TRUE);
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$title = "COJM";
include 'changejob.php';
$hasforms='1';
?><!doctype html>
<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php print ($title); ?>Last 100 Closed Jobs</title>
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
</head>
<body>
<?php

$adminmenu="1";
$filename="recentlyclosed.php";
include "cojmmenu.php";

$sql = "SELECT *
FROM `Orders`
INNER JOIN Clients
INNER JOIN Services 
INNER JOIN Cyclist
INNER JOIN status
ON Orders.CustomerID = Clients.CustomerID
AND Orders.ServiceID = Services.ServiceID
AND Orders.CyclistID = Cyclist.CyclistID 
AND Orders.status = status.status 
WHERE `Orders`.`status` >70
ORDER BY `Orders`.`Shipdate` DESC
LIMIT 0 , 100";

// execute SQL query and get result
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error());
	 
echo '<div class="Post">
	<div class="ui-widget ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">

<h4>Last 100 jobs by delivery time</h4></div>
<table id="acc" class="acc" >
<tbody>';

$today = date(" H:i A D j");
echo '<tr>
<th scope="col">Delivery</th>
<th scope="col"> </th>
<th scope="col">From</th>
<th scope="col">Client</th>
<th scope="col">Service</th>
<th scope="col">Status</th>
<th scope="col">Comments</th>
</tr>

';

while ($row = mysql_fetch_array($sql_result)) {
     extract($row);
	 
	 $numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');

$CollectPC=$row['CollectPC'];
$ShipPC=$row['ShipPC'];
$prShipPC= str_replace(" ", "%20", "$ShipPC", $count);
$prCollectPC= str_replace(" ", "%20", "$CollectPC", $count);
	

echo '<tr><td><a href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a> ';

	
echo ''.date('H:i A D j M ', strtotime($row['ShipDate'])); 

echo '</td><td>';

echo $row['cojmname'].'</td>';

echo '
<td><a target="_blank" href="http://maps.google.co.uk/maps?q='. $prCollectPC.'">'. $CollectPC.'</a>';
if ((!$ShipPC) or ($ShipPC==' ')) {} else {echo " to "; }
echo '<a target="_blank" href="http://maps.google.co.uk/maps?q='. $prShipPC.'">'. $ShipPC.'</a></td>
<td>'. $row['CompanyName'];


$tempdep=$row['orderdep'];

$depsql="SELECT * from clientdep 
INNER JOIN Orders
On Orders.orderdep=clientdep.depnumber 
WHERE Orders.orderdep='$tempdep' LIMIT 0,1";
$dsql_result = mysql_query($depsql,$conn_id)  or mysql_error();

while ($drow = mysql_fetch_array($dsql_result)) { extract($drow); echo ' ('.$drow['depname'].') '; }





echo '</td>
<td>'. $row['numberitems'].' x '. $row['Service'].'</td>
<td>'. $row['statusname'] .'</td>
<td>'. $row['jobcomments'].' '.$row['privatejobcomments'].'</td>
</tr>';
// echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';


// End while loop
}

echo '</tbody></table></div>';

include "footer.php";

echo '</body></html>';
mysql_close();