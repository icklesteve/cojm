<?php 

include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$title = "COJM";

include 'changejob.php';
$hasforms='1';

?><!doctype html>
<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="Refresh" CONTENT="<?php echo $globalprefrow['pagetimeout']; ?>; "> 
<?php echo '
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'.$globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" />'; ?>
<title><?php print ($title); ?> Bulk Jobs</title>
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
</head>
<body>
<?php

$adminmenu="1";
$filename="bulkjobs.php";
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
WHERE Orders.FreightCharge <> 0.00 
ORDER BY `Orders`.`numberitems` DESC
LIMIT 0 , 100";

$sql_result = mysql_query($sql,$conn_id) or die(mysql_error());
	 
echo '<div class="Post">
	<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<h4>Bulk Jobs </h4>
<table id="acc" class="acc" >
<tbody>';

$today = date(" D j M Y");
echo '<tr>
<th scope="col">'. $today.' </th>
<th scope="col">Items</th>
<th scope="col">Price</th>
<th scope="col">Per 1</th>
<th scope="col">Per 1000</th>
<th scope="col">Client</th>
<th scope="col">Status</th>
<th scope="col" style="width:30%;">Comments</th>
</tr><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

while ($row = mysql_fetch_array($sql_result)) { extract($row);
$numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');
$CollectPC=$row['CollectPC'];
$ShipPC=$row['ShipPC'];
$prShipPC= str_replace(" ", "%20", "$ShipPC", $count);
$prCollectPC= str_replace(" ", "%20", "$CollectPC", $count);

echo '<tr><td><a href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a> ';
echo ''.date(' D j M Y', strtotime($row['ShipDate'])); 
echo '</td>';
echo '<td>'.$numberitems;
echo '</td><td>&'.$globalprefrow['currencysymbol'].' '.$FreightCharge;
echo '</td><td>';

// $numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');


echo number_format(($FreightCharge/$numberitems),3);
echo '</td><td>';
echo number_format((($FreightCharge/$numberitems)*1000),2);

echo '</td><td>'. $row['CompanyName'];

$tempdep=$row['orderdep'];

$depsql="SELECT * from clientdep 
INNER JOIN Orders
On Orders.orderdep=clientdep.depnumber 
WHERE Orders.orderdep='$tempdep' LIMIT 0,1";
$dsql_result = mysql_query($depsql,$conn_id)  or mysql_error();

while ($drow = mysql_fetch_array($dsql_result)) { extract($drow); echo ' ('.$drow['depname'].') '; }

echo '</td><td>'. $row['statusname'] .'</td>
<td>'. $row['jobcomments'].' '.$row['privatejobcomments'].'</td>
</tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

} // End while loop

echo '</tbody></table></div></div><br /></div></body></html>';

mysql_close();