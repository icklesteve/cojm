<?php 

/*
    COJM Courier Online Operations Management
	bulkjobs.php - Shows audit log via ajax
    Copyright (C) 2017 S.Young cojm.co.uk

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
<link rel="stylesheet" href="css/themes/'.$globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" />'; ?>
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
left join clientdep ON Orders.orderdep = clientdep.depnumber
WHERE Orders.CustomerID = Clients.CustomerID
AND Orders.ServiceID = Services.ServiceID
AND Orders.CyclistID = Cyclist.CyclistID 
AND Orders.status = status.status 
AND Orders.FreightCharge <> 0.00 
ORDER BY `Orders`.`numberitems` DESC
LIMIT 0 , 100";


	 
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
</tr>
';



$sth = $dbh->prepare($sql);
$sth->execute($parameters);


while($row = $sth->fetch()) {
    
    
    $numberitems= trim(strrev(ltrim(strrev($row['numberitems']), '0')),'.');
    $enrpc0=$row['enrpc0'];
    $enrpc21=$row['enrpc21'];
    $prenrpc21= str_replace(" ", "%20", "$enrpc21", $count);
    $prenrpc0= str_replace(" ", "%20", "$enrpc0", $count);
    
    echo '<tr><td><a href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a> ';
    echo ''.date(' D j M Y', strtotime($row['ShipDate'])); 
    echo '</td>';
    echo '<td>'.$numberitems;
    echo '</td><td title="excl. VAT">&'.$globalprefrow['currencysymbol'].' '.$row['FreightCharge'];
    echo '</td><td>';
    
    // $numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');
    
    
    echo number_format(($row['FreightCharge']/$row['numberitems']),3);
    echo '</td><td>';
    echo number_format((($row['FreightCharge']/$row['numberitems'])*1000),2);
    
    echo '</td><td>'. $row['CompanyName'];
    
    
    if ($row['depname']) {  echo ' ('.$row['depname'].') '; }
    
    echo '</td><td>'. $row['statusname'] .'</td>
    <td>'. $row['jobcomments'].' '.$row['privatejobcomments'].'</td>
    </tr>';
    
} // End while loop

echo '</tbody></table></div></div><br /></div></body></html>';
