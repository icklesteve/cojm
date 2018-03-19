<?php 
/*
    COJM Courier Online Operations Management
	single_tracking.php - Shows 1 job, to be used as a php include on a page on your website
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

echo ' <script> var cojmshowgmap=0; </script> ';

echo '<div id="cojmsingletrackdiv" class="cojm">';




$postedref=trim($_POST['quicktrackref']); 
if ($postedref) {} else { $postedref = trim($_GET['quicktrackref']); }

$postedref=strip_tags($postedref);
$postedref=str_replace("'","\'", $postedref);
$postedref = strtoupper($postedref);
$postedref = substr($postedref, 0, 13);


if (ctype_alnum($postedref)) {
       // echo " 1 ";
} else {
      //  echo " 2 ";
	  $postedref='';
}

$query = "SELECT 
numberitems, 
ID, 
ShipDate, 
trackerid, 
publictrackingref, 
publicstatusname, 
Orders.status, 
poshname, 
Service, 
jobcomments, 
 enrpc0,
 enrpc1, 
 enrpc2, 
 enrpc3, 
 enrpc4, 
 enrpc5, 
 enrpc6, 
 enrpc7, 
 enrpc8, 
 enrpc9, 
 enrpc10, 
 enrpc11, 
 enrpc12, 
 enrpc13, 
 enrpc14, 
 enrpc15,
 enrpc16,
 enrpc17, 
 enrpc18, 
 enrpc19, 
 enrpc20,
 enrpc21, 
 enrft0,
 enrft1, 
 enrft2, 
 enrft3, 
 enrft4, 
 enrft5, 
 enrft6, 
 enrft7, 
 enrft8, 
 enrft9, 
 enrft10,
 enrft11, 
 enrft12, 
 enrft13, 
 enrft14, 
 enrft15, 
 enrft16, 
 enrft17, 
 enrft18, 
 enrft19, 
 enrft20, 
 enrft21 , 
 targetcollectiondate, 
 duedate, 
 deliveryworkingwindow, 
 starttravelcollectiontime,
 waitingstarttime, 
 collectiondate, 
 starttrackpause, 
 finishtrackpause, 
 podsurname, 
 distance, 
 co2saving, 
 CO2Saved, 
 pm10saving, 
 PM10Saved, 
 opsmaparea, 
 opsmapsubarea
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
	


if ($postedref=='') {
    echo '<h1>Please enter a Tracking Reference.</h1><hr />';
    }
else if ($row['ID'] == "" ) {
    echo '<h1>Tracking Reference Not Recognised, please re-try.</h1><hr />';
}
else if ($row['ID']) {  // starts main table

    $numberitems= trim(strrev(ltrim(strrev($row['numberitems']), '0')),'.');
    $thistrackerid=$row['trackerid'];

    echo ' <h1>'.$row['publictrackingref'].'</h1>
    <hr />
    <table id="cojm" class="cojm" cellspacing="0" style="table-layout:auto;">
    <tbody>
    <tr>
    <th class="stleft"> Job Status</th>
    <th class="stright">';

    if ($row['status']<'101') {
        echo $row['publicstatusname'];
    } else { // do not show job invoice status, just the complete phrase
        $row['status']='100';
        $completestatusquery="SELECT publicstatusname FROM `status` WHERE status=100 LIMIT 0,1;";
        $q= $dbh->query($completestatusquery);
        $completestatus = $q->fetchColumn();
        echo $completestatus;
    } 
    
    echo '</th></tr>';
    
    if ($row['CyclistID']>1) {
        echo '<tr><td>'.$globalprefrow['glob5'].'</td><td>'. $row['poshname'].'</td></tr>';
    }
    
    echo ' <tr><td>Service </td><td> '. $numberitems .' x ' .$row['Service'] .'</td></tr>';
    
    if ($row['jobcomments']) {
        echo ' <tr><td>Comments </td><td>'. $row['jobcomments'] .'</td></tr>';
    } 
    
    $linkCollectPC=trim($row["enrpc0"]);
    $linkCollectPC = str_replace(" ", "%20", "$linkCollectPC", $count);
    $linkShipPC=trim($row["enrpc21"]);
    $linkShipPC = str_replace(" ", "%20", "$linkShipPC", $count);
    
    
    if ((trim($row['enrpc0'])) or (trim($row['enrft0']))) {
        echo '<tr><td colspan="2"><hr /></td></tr>';
        echo '<tr><td>From</td><td>'.$row['enrft0'].' <a target="_blank" href="http://maps.google.com/maps?q='. $linkCollectPC.'">'. 
        $row['enrpc0'].' </a> </td></tr>';
    }
    
    
    
    
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
    
    if ((trim($row['enrpc21'])) or (trim($row['enrft21']))) {  
        echo '<tr><td>To</td><td>'.$row['enrft21'].' <a target="_blank" href="http://maps.google.com/maps?q='. $linkShipPC.'">'. $row['enrpc21'].'</a></td></tr> '; 
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
    if (date('U', strtotime($row['deliveryworkingwindow']))>10) {
        echo '- '.date('H:i A', strtotime($row['deliveryworkingwindow']));
    }
    
    echo date(', l jS F Y', strtotime($row['duedate'])). '</td></tr>';
    
    
    echo '<tr><td colspan="2"><hr /></td></tr>';
    if ($row['starttravelcollectiontime'] > 10) { 
        echo '<tr><td>En route to collection </td><td>'. date('H:i A, l jS F, Y', strtotime($row['starttravelcollectiontime'])).'</td></tr>';
    } 
    if ($row['waitingstarttime'] >10) {
        echo '<tr><td>On Site : </td><td>'. date('H:i A, l jS F Y', strtotime($row['waitingstarttime'])).'</td></tr>';
    }
    if ($row['collectiondate']>10) {
        echo '<tr><td>Time of Collection </td><td>'. date('H:i A, l jS F Y', strtotime($row['collectiondate'])).'</td></tr>';
    }
    if ($row['starttrackpause']>10) {
        echo '<tr><td>Delivery Paused at </td><td>'. date('H:i A, l jS F Y', strtotime($row['starttrackpause'])).'</td></tr>'; 
        if ($row['finishtrackpause']>10) {
            echo '<tr><td>Delivery Resumed at </td><td>'. date('H:i A, l jS F Y', strtotime($row['finishtrackpause'])).'</td></tr>';
        }
    }
    
    
    if ($row['ShipDate']>10) {
        echo '<tr><td>Time of Delivery </td><td>'. date('H:i A, l jS F Y', strtotime($row['ShipDate'])).'</td></tr>'; 
        echo '<tr><td colspan="2"><hr /></td></tr>';
    }
    
    if ($row['status']>'77') {
        if ($row['podsurname']) {
            echo '<tr><td>Delivery Surname : </td><td>'. $row["podsurname"].'</td></tr>';
        }
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
        else { $tablepm10.=' grams'; 
        } 
        
        if ($tableco2>1000) {
            $tableco2=($tableco2/1000);
            $tableco2 = number_format($tableco2, 1, '.', ',');
            $tableco2.= 'kg ';
        }
        else {
            if ($tableco2>1) {
                $tableco2.=' grams'; 
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
        
        
        if ($row['status']<'80') {
        
            // echo ' <p title="Target Collection">'. date('l jS M Y', strtotime($row['targetcollectiondate'])).'</p>';
        
        } else { 
        
            // echo ' <p title="Completed">'. date('l jS M Y', strtotime($row['ShipDate'])).'</p>';
        }
        
        

        echo '<tr><td colspan="2">
        <div id="map-container" >
        <div id="map-canvas" style="width: 100%; height: 400px;"></div>
        </div>        
        ';
        
        
        if ($row['opsmaparea'] <>'') {
        

        
        
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
        
        
        

        $lattot='0';
        $lontot='0';
        $locationjs='';
        
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
                $comments=date('H:i D j M', $map['timestamp']) . '';
                
                
                $locationjs.= '["' . $comments .'",'. $map['latitude'] . ',' . $map['longitude'] . ',' . $numbericons .'],'; 
                
                $latestlat=	$map['latitude'];
                $latestlong=$map['longitude'];
                
                $prevts=date('H:i A D j M ', $map['timestamp']); 
                $lattot=$lattot+$map['latitude'];
                $lontot=$lontot+$map['longitude'];
                
            } // ends loop for different minute
        
        }
        
        if ($numbericons>'0') {
            $lattot=($lattot / $numbericons );
            $lontot=($lontot / $numbericons );
        }

                ?>
<script>
cojmshowgmap=1;
var all = [<?php echo rtrim($linecoords, ','); ?>];
    
function custominitialize() {
    // google.maps.event.trigger(map, "resize");
    // map.fitBounds(bounds);

    <?php echo $areajs; ?>
    
    
    printtext = "<p class='mapprint'><?php echo nl2br($postedref); ?></p>";
        
    bounds = new google.maps.LatLngBounds();
    bounds.extend(new google.maps.LatLng(<?php echo $max_lat.', '.$min_lon; ?>)); // upper left
    bounds.extend(new google.maps.LatLng(<?php echo $max_lat.', '.$max_lon; ?>)); // upper right
    bounds.extend(new google.maps.LatLng(<?php echo $min_lat.', '.$max_lon; ?>)); // lower right
    bounds.extend(new google.maps.LatLng(<?php echo $min_lat.', '.$min_lon; ?>)); // lower left
    
    map.fitBounds(bounds);
    
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
        url: "<?php echo $globalprefrow['clweb3']; ?>",
        size: new google.maps.Size(20, 20),
        origin: new google.maps.Point(0,0),
        anchor: new google.maps.Point(10, 10)
    };  
    
    var infowindow = new google.maps.InfoWindow();
    var marker, i;
    
    
    var locations = [<?php echo rtrim($locationjs, ','); ?>];

    console.log(locations.length);
    
    console.log(locations);
    
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
            
} // ends initialise function

</script>
<?php
                if ($instasumtot) {
                    echo ' <br />Tracking last updated at '. $englishlast.', with '.number_format ($numbercords, 0, '.', ',').' GPS positions.';
                }
                
                if ($row['status']>70) { 
                    echo '<p class="download"><a href="'.$globalprefrow['httproots'].'/cojm/createkml.php?id='.$row['publictrackingref'].'">Download as Google Earth KML File</a></p>';
                }
                
                echo '</td></tr>';
                
    
            } // edns check for $instasumtot tracking positions & areaid
    
        echo '</tbody></table>
        <br />
        <hr />
        ';
        
        // echo 'Total tracking records : '.$sumtot . $error;
        
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
<script>
var globlat=<?php echo $globalprefrow['glob1']; ?>;
var globlon=<?php echo $globalprefrow['glob2']; ?>;
var globalshortname="<?php echo $globalprefrow['globalshortname']; ?>";
var adminlogo="<?php echo $globalprefrow['adminlogoabs']; ?>";



function loadScript(url, callback) {
    // Adding the script tag to the head as suggested before
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = url;

    // Then bind the event to the callback function.
    // There are several events for cross browser compatibility.
    script.onreadystatechange = callback;
    script.onload = callback;

    // Fire the loading
    head.appendChild(script);
    // console.log("loading " + url);
}


function main(alreadyused) {
    loadScript("https://maps.googleapis.com/maps/api/js?v=<?php echo $globalprefrow['googlemapver']; 
    ?>&key=<?php echo $globalprefrow['googlemapapiv3key']; ?>", loadmorejsc);
}


function loadmorejsc () {
    console.log("loadmorejsc");
google.maps.event.addDomListener(window, "load", loadmorejsb);

}

function loadmorejsb () {
    console.log("loadmorejsb");
    loadScript("../cojm/js/maptemplate.js", loadmapfromtemplate);
};

/*

function loadmorejsa () {
    console.log("loadmorejsa");
    loadScript("../cojm/js/richmarker.js", loadmorejsb);
};


*/

function loadmapfromtemplate() {
    jQuery(document).ready(function () {
        initialize();
        custominitialize();
    });
}


function jquerytest () {
    if (typeof jQuery === "undefined") {
        console.log("Loading jQuery");
        var script_tag = document.createElement("script");
        script_tag.setAttribute("type","text/javascript");
        script_tag.setAttribute("src","https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js");
        script_tag.onload = main; // Run main() once jQuery has loaded
        script_tag.onreadystatechange = function () { if (this.readyState == "complete" || this.readyState == "loaded") main(2); }
        document.getElementsByTagName("head")[0].appendChild(script_tag);
    } else {
        main(1);
            console.log("jQuery as $ already present");
    }
}


(function() {
   // your page initialization code here
   // the DOM will be available here
   if (cojmshowgmap>0) { jquerytest(); }

})();

</script>