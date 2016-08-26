<?php 

/*
    COJM Courier Online Operations Management
	recentlyclosed.php - Last 100 closed jobs
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
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

echo '<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Last 100 Closed Jobs</title>
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<script type="text/javascript" src="js/jquery-ui.1.8.7.min.js"></script>
<script type="text/javascript" src="js/jquery.floatThead.js"></script>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<style> td.commentcolumn, th.commentcolumn { width:30%; } </style>
</head>
<body>';

include 'changejob.php';
$hasforms='1';
$adminmenu="1";
$filename="recentlyclosed.php";
include "cojmmenu.php";

	 
echo '<div class="Post">
	<div class="ui-widget ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<h4>Last 100 jobs by delivery time</h4>
</div>
<table id="recentlyclosed" class="acc" >
<thead>
<tr>
<th scope="col">Delivery</th>
<th scope="col"> </th>
<th scope="col">From</th>
<th scope="col">Client</th>
<th scope="col">Service</th>
<th scope="col">Status</th>
<th scope="col" class="commentcolumn">Comments</th>
</tr>
</thead>
<tbody>';

$sql = "SELECT ID, 
ShipDate, 
Orders.CyclistID, 
cojmname, 
CollectPC, 
ShipPC, 
CompanyName, 
depname, 
numberitems, 
Service, 
statusname, 
jobcomments, 
privatejobcomments
FROM Orders
INNER JOIN Clients ON Orders.CustomerID = Clients.CustomerID
INNER JOIN Services ON Orders.ServiceID = Services.ServiceID
INNER JOIN Cyclist ON Orders.CyclistID = Cyclist.CyclistID
INNER JOIN status ON Orders.status = status.status
left join clientdep ON Orders.orderdep = clientdep.depnumber
WHERE `Orders`.`status` >70
ORDER BY `Orders`.`Shipdate` DESC
LIMIT 0 , 100";

$stmt = $dbh->prepare($sql);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    echo '<tr><td><a href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a>'. 
    date(' H:i A D jS', strtotime($row['ShipDate'])).'</td><td>';
    if ($row['CyclistID']>1) { echo $row['cojmname']; }
    echo '</td><td>';
    if ((trim($row['CollectPC']))<>'') { 
        echo '<a target="_blank" href="http://maps.google.co.uk/maps?q='. 
    str_replace(" ", "+", (trim($row['CollectPC']))) .'">'. trim($row['CollectPC']).'</a>';
    }

    if ((trim($row['ShipPC']))<>'') { 
        echo ' to <a target="_blank" href="http://maps.google.co.uk/maps?q='. 
    str_replace(" ", "+", $row['ShipPC']) . '">'. trim($row['ShipPC']).'</a>';
    }
    echo '</td>
    <td>'. $row['CompanyName'];
    
    if ($row['depname']<>"") { echo ' ('.$row['depname'].') '; }
        
    echo '</td>
    <td>'. trim(strrev(ltrim(strrev($row['numberitems']), '0')),'.') .' x '. $row['Service'].'</td>
    <td>'. $row['statusname'] .'</td>
    <td class="commentcolumn">'. $row['jobcomments'].' '.$row['privatejobcomments'].'</td></tr>';
}

echo '</tbody></table></div>';

echo '<script>	
$(document).ready(function() {
    var menuheight=$("#sticky_navigation").height();
    $("#recentlyclosed").floatThead({
        position: "fixed",
        top: menuheight
    });
});
</script>';

include "footer.php";

echo '</body></html>';
mysql_close();