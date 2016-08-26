<?php

/*
    COJM Courier Online Operations Management
	ajaxgpsorderlookup.php - Handles Ajax Requests made from gpstracking.php and returns list of jobs en-route at time of tracking
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


if (isset($_POST['markervar'])) {
    
    include "C4uconnect.php";

    if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  exit(); } }

    $markervar=(trim($_POST['markervar']));
    $markervarexploded=explode( '_', $markervar );
    // echo ' time is '.$markervarexploded[0].' rider is '.$markervarexploded[1];
    $timetocheck=date('Y-m-d H:i:59', $markervarexploded[0]);
    $timetocheckearly=date('Y-m-d H:i:00', ($markervarexploded[0])-60);


    try {
        
        $query= ' SELECT ID, CompanyName, depname
        FROM Orders 
        INNER JOIN Cyclist ON Orders.CyclistID = Cyclist.CyclistID
        INNER JOIN Clients ON Orders.CustomerID = Clients.CustomerID
        left join clientdep ON Orders.orderdep = clientdep.depnumber
        WHERE trackerid = :trackerid
        AND ( ';
        $query.='( (collectiondate < :timetochecka) and (starttrackpause > :timetocheckb) ) ';
        $query.='or ( ( collectiondate < :timetocheckc ) and ( ShipDate > :timetocheckd ) and ( finishtrackpause = "0000-00-00 00:00:00" ) ) and ( starttrackpause = "0000-00-00 00:00:00" ) ';
        $query.='or ( (finishtrackpause < :timetocheckf ) and ( finishtrackpause <> "0000-00-00 00:00:00" ) and ( starttrackpause <> "0000-00-00 00:00:00" ) and ( ShipDate = "0000-00-00 00:00:00" ) ) ';
        $query.='or ( (finishtrackpause < :timetocheckg ) and ( finishtrackpause <> "0000-00-00 00:00:00" ) and ( ShipDate > :timetochecke ) ) ';
        $query.='or ( ( collectiondate < :timetocheckh ) and ( ShipDate = "0000-00-00 00:00:00" ) and ( finishtrackpause = "0000-00-00 00:00:00" ) and ( starttrackpause = "0000-00-00 00:00:00" ) and ( collectiondate <> "0000-00-00 00:00:00" )  ) ';
        $query.=') ';
        
        // btwn collection & pause,  
        // between collection & delivery, no pause or resume
        // between resume and now , no delivery
        // > resume, resume exists, < delivery
        // > collection, collection exists, no pause, resume or delivery
        
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':timetochecka', $timetocheck, PDO::PARAM_INT);
        $stmt->bindParam(':timetocheckb', $timetocheckearly, PDO::PARAM_INT);
        $stmt->bindParam(':timetocheckc', $timetocheck, PDO::PARAM_INT);
        $stmt->bindParam(':timetocheckd', $timetocheckearly, PDO::PARAM_INT);
        $stmt->bindParam(':timetochecke', $timetocheckearly, PDO::PARAM_INT);
        $stmt->bindParam(':timetocheckf', $timetocheck, PDO::PARAM_INT);
        $stmt->bindParam(':timetocheckg', $timetocheck, PDO::PARAM_INT);
        $stmt->bindParam(':timetocheckh', $timetocheck, PDO::PARAM_INT);
        $stmt->bindParam(':trackerid', ($markervarexploded[1]), PDO::PARAM_INT);
        $stmt->execute();
        $total = $stmt->rowCount();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            echo '<a href="order.php?id='.$row['ID'].'" title="" >'.$row['ID'].'</a> '.
            $row['CompanyName'];
            if ($row['depname']<>"") { echo ' ('.$row['depname'].') '; }
            echo '<br />';
        }
    }
    
    catch(PDOException $e) {
        echo $e->getMessage();
    }
$dbh=null;
}