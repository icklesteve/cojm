<?php error_reporting(E_ALL);

include_once "C4uconnect.php";
include_once("GeoCalc.class.php");

if ($globalprefrow['forcehttps']>0) { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$todayend = (date("Y-m-d")).'23:59:59';




$ThisYear = (date("Y"));
$MarStartDate = ($ThisYear."-03-25");
$OctStartDate = ($ThisYear."-10-25");
$MarEndDate = ($ThisYear."-03-31"); 
$OctEndDate = ($ThisYear."-10-31");
while ($MarStartDate <= $MarEndDate) {
    $day = date("l", strtotime($MarStartDate)); 
    if ($day == "Sunday"){
        $BSTStartDate = ($MarStartDate);
    } $MarStartDate++;
}
$BSTStartDate = (date("U", strtotime($BSTStartDate))+(60*60)); 
while ($OctStartDate <= $OctEndDate) {
    $day = date("l", strtotime($OctStartDate));
    if ($day == "Sunday") {
        $BSTEndDate = ($OctStartDate);
    } 
    $OctStartDate++;
}

$BSTEndDate = (date("U", strtotime($BSTEndDate))+(60*60));
$now = mktime();
if (($now >= $BSTStartDate) && ($now <= $BSTEndDate)){
// echo "We are now in BST"; 
// $map['timestamp']=$map['timestamp']+3600; 
} else { 
// echo "We are now in GMT"; 
}









function rstrtrim($str, $remove=null) {
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


    $tUnixTime = time();
    $sGMTSqlString = gmdate("d-m-Y", $tUnixTime);


////////    SEE IF TRACKING DATA FOR TODAY FOR RIDERS

$sql = "SELECT CyclistID, cojmname, trackerid FROM Cyclist 
 WHERE isactive='1' 
 AND CyclistID > '1'
 ORDER BY CyclistID";

$prep = $dbh->query($sql);
$stmt = $prep->fetchAll();

foreach ($stmt as $riderrow) { // echo $CyclistID;

    $CyclistID=$riderrow['CyclistID'];
    $cojmname=$riderrow['cojmname'];
    $thisonetracker=$riderrow['trackerid'];
    
    $sql = "SELECT * FROM `instamapper` WHERE `device_key` = ? ORDER BY `timestamp` DESC LIMIT 0,1";

    $prep = $dbh->prepare($sql);
    $prep->execute([$riderrow['trackerid']]);
    $stmt = $prep->fetchAll();
    
    if ($stmt) {
        foreach ($stmt as $map) {
            extract($map); 
            

            $newSqlString = gmdate(" H:i ", $map['timestamp']);
            $ewSqlString = gmdate("d-m-Y", $map['timestamp']);
            
            if ($ewSqlString==$sGMTSqlString) {
                $comments.="['".$riderrow['cojmname'].'<br>'.date('H:i', ($map['timestamp']));
                if ($map['speed']) { $comments.='<br>'. $map['speed'] .' '.$globalprefrow['distanceunit'].' per hour.'; }
                $comments.= "',". $map['latitude'] . "," . $map['longitude'] . "," . $i ."  ],"; 
                $i=$i+1;	
                $cyclistname=$row['cojmname'];
                $latestlat=	$map['latitude'];
                $latestlong=$map['longitude'];
                $oGC = new GeoCalc();
                $dRadius = 0.07; 
                $dLongitude = $map['longitude'];
                $dLatitude = $map['latitude'];
                $dAddLat = $oGC->getLatPerKm() * $dRadius;
                $dAddLon = $oGC->getLonPerKmAtLat($dLatitude) * $dRadius;
                $dNorthBounds = $dLatitude + $dAddLat;
                $dSouthBounds = $dLatitude - $dAddLat;
                $dWestBounds = $dLongitude - $dAddLon;
                $dEastBounds = $dLongitude + $dAddLon;
                    $beer = "   SELECT PZ_Postcode, 
                    ( 3959 * acos( cos( radians(?) ) * cos( radians( PZ_northing ) ) * cos( radians( PZ_easting ) - radians(?) ) + sin( radians(?) ) * sin( radians( PZ_northing ) ) ) ) AS distance ,
                    PZ_easting
                    FROM postcodeuk 
                    WHERE PZ_northing > ?
                    AND PZ_northing < ?
                    AND PZ_easting < ? 
                    AND PZ_easting > ?
                    ORDER BY distance 
                    LIMIT 1; ";

                    $cbstmt = $dbh->prepare($beer);
                    $cbstmt->execute([$map["latitude"],$map["longitude"],$map["latitude"],$dSouthBounds,$dNorthBounds,$dWestBounds,$dEastBounds]);
                    $data = $cbstmt->fetchAll();


                    if ($data) {
                        $thispc=$data[0][PZ_Postcode];
                        $start= substr($thispc, 0, -3); 
                        $cyclistpos= $start.' '.substr($thispc, -3);
                    }                
                
                
                $toutstanding=0;
                
                
                $sql="
                SELECT * FROM Orders 
                INNER JOIN Clients 
                INNER JOIN Services 
                INNER JOIN status
                ON 
                Orders.CustomerID = Clients.CustomerID 
                AND Orders.ServiceID = Services.ServiceID 
                AND Orders.status = status.status 
                WHERE `Orders`.`status` <70 
                AND `Orders`.`CyclistID`= ?
                AND `Orders`.`nextactiondate` < ?
                ORDER BY `Orders`.`nextactiondate` 
                ";
                
                
                $prep = $dbh->prepare($sql);
                $prep->execute([$CyclistID,$todayend]);
                $stmt = $prep->fetchAll();
                
                foreach ($stmt as $outsrow) {                

                    $outs.="<br/><a href='order.php?id=".$outsrow['ID']."'>".$outsrow['ID'].'</a> '.$outsrow['enrpc0'].' to '.$outsrow['enrpc21'].', '.$outsrow['statusname'];
                    $toutstanding++;
                }
                
                $cyclistts=date('H:i', ($map['timestamp'])) ;
                
                $tempcontent='<div><strong>'.$cojmname.'</strong> was near to '.$cyclistpos.' at '.$cyclistts.'<br/>'.$toutstanding.' job';
                if  ($toutstanding !=1) { $tempcontent.= 's'; }
                $tempcontent.= ' outstanding'.$outs.'</div>';
                $numcyclists=$numcyclists+1;
                $temptitle= $cojmname.' '. $cyclistts;
                $idnum=$idnum+1;
                $idtext=$idtext.'{
                "id":"'.$idnum.'",
                "category":"'.$cojmname.'",
                "zI":"15000",
                "img":"cycling.png",
                "name":"'.$temptitle.'",
                "title":"'.$temptitle.'",
                "street_address":"'.$tempcontent.'",
                "lat":"'.$latestlat.'",
                "lng":"'.$latestlong.'"
                },'; 
                if ($cojmname) {
                    if (strpos($cattext,$cojmname) == true) { } else { $cattext.='"'.$cojmname.'",'; }
                }
                
            $temptitle='';
            $tempcontent='';
            $cyclistts='';
            $outs='';
            
            
            } // checks tracking position is today
        } // loop for latest tracking position found
    } // found valid instamapper id
} // end of loop for each rider

/////   ENDS CURRENT RIDER POSITION


$cj_time = microtime(TRUE);



// loop for jobs awaiting collection

$sql="
SELECT * FROM Orders 
INNER JOIN Clients 
INNER JOIN Services 
INNER JOIN Cyclist
ON 
Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID
AND Orders.CyclistID = Cyclist.CyclistID  
WHERE `Orders`.`status` <52 
AND `Orders`.`nextactiondate` < ?
ORDER BY `Orders`.`nextactiondate` 
";

$prep = $dbh->prepare($sql);
$prep->execute([$todayend]);
$stmt = $prep->fetchAll();

foreach ($stmt as $uncrow) {
    if ($uncrow['enrpc0']) {
        $pc1 = str_replace (" ", "", $uncrow['enrpc0']);
        $query="SELECT * 
        FROM  `postcodeuk` 
        WHERE  `PZ_Postcode` =  ?
        LIMIT 1"; 
        $statement = $dbh->prepare($query);
        $statement->execute([$pc1]);
        $pcrow = $statement->fetch(PDO::FETCH_ASSOC);
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
            $collecttime.= date(' A ', strtotime($uncrow['targetcollectiondate']));
        } 
        if ($uncrow['allowcollectww']=="1") {
            $collecttime.= '- '.date('H:i A ', strtotime($uncrow['collectionworkingwindow']));
        } 
        
        $numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');
        $outsc="<br/><a href='order.php?id=".$uncid."'>".$uncid.'</a> ';
        $temptitle= 'Collection Due '.$collecttime.' by '.$uncrow['cojmname'];
        $tempcontent='<div><strong>Uncollected</strong>'.$outsc.' Collection due '.$collecttime.'<br>From '.
        $uncrow['enrpc0'].' to '.$uncrow['enrpc21']. " <br />".$numberitems ." x ".$uncrow['Service'] ."<br /> ".$uncrow['CompanyName']." </div>";
        
        if ($uncrow['jobcomments']) { $tempcontent.='<div>'.$uncrow['jobcomments'].'</div>'; }
        if ($uncrow['privatejobcomments']) { $tempcontent.='<div>'.$uncrow['privatejobcomments'].'</div>'; }
        
        
        
        if (($pclat) and ($pclon)) {
            if ($uncrow['cojmname']) { if (strpos($cattext,$uncrow['cojmname']) == true) { } else { $cattext.='"'.$uncrow['cojmname'].'",'; }}
            $idnum=$idnum+1;
            $idtext.='{"id":"'.$idnum.'","category":"'.$uncrow['cojmname'].'","zI":"90","img":"share.png","name":"'.
            $temptitle.'","title":"'.$temptitle.'","street_address":"'.$tempcontent.'","lat":"'.$pclat.'","lng":"'.$pclon.'"},';
            $tempcontent='';
        }
    } // ends check to make sure collection postcode    
} // ends loop for check for scheduled collections


// starts check for oustanding deliveries
$sql="
SELECT * FROM Orders 
INNER JOIN Clients 
INNER JOIN Services 
INNER JOIN Cyclist
ON 
Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
AND Orders.CyclistID = Cyclist.CyclistID 
AND `Orders`.`status` <77 
AND `Orders`.`nextactiondate` < ?
ORDER BY `Orders`.`nextactiondate` ";

$prep = $dbh->prepare($sql);
$prep->execute([$todayend]);
$stmt = $prep->fetchAll();

foreach ($stmt as $undrow) {

    if ((trim($undrow['enrpc21'])) and (trim($undrow['enrpc0']))) {
        $pc1 = str_replace (" ", "", $undrow['enrpc21']);
        $query="SELECT * FROM  `postcodeuk` WHERE  `PZ_Postcode` =  ? LIMIT 1 "; 
        $statement = $dbh->prepare($query);
        $statement->execute([$pc1]);
        $pcrow = $statement->fetch(PDO::FETCH_ASSOC);
        
        $undid=$undrow['ID'];
        $pclon=$pcrow['PZ_easting'];
        $pclat=$pcrow['PZ_northing'];
        $collecttime= date('H:i', strtotime($undrow['duedate'])); 
        
        if ((date('A', strtotime($undrow['duedate']))==date('A', strtotime($undrow['deliveryworkingwindow']))) 
        OR ( date('U', strtotime($undrow['deliveryworkingwindow'])>100 ) )) {
        } else {
            $collecttime.= date(' A ', strtotime($undrow['duedate']));
            // echo ' deliver not same';
        }
        if ($undrow['allowdeliverww']=="1") {
            $collecttime.= '- '.date('H:i A ', strtotime($undrow['deliveryworkingwindow']));
        }
        
        $numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');
        $outsd="<br/><a href='order.php?id=".$undid."'>".$undid."</a> ";
        
        if ( date('U', strtotime($undrow['collectiondate'])>100)) {
            $temptitle.="Collected ".date('H:i A', strtotime($undrow['collectiondate'])).',';
        }
        else {
            $temptitle.= 'Uncollected, ';
        }
        
        $temptitle.=" due $collecttime by ".$undrow['cojmname'].", ";
        $tempcontent='<div><strong>Delivery not yet collected, due '.$collecttime.'</strong>'.$outsd.' From '.$undrow['enrpc0'].' to '.$undrow['enrpc21'];
        if ($undrow['status']>52)  {
            $tempcontent='<br /><strong>'.$undrow['cojmname'].'</strong> Collected at '.date('H:i A', strtotime($undrow['collectiondate'])).'.';
        }
            
        $tempcontent.=" <br />$numberitems x ". $undrow['Service'] . " <br /> " . $undrow['CompanyName'] . " </div>";
        if ($undrow['jobcomments']) {$tempcontent.= '<div>'.$undrow['jobcomments'].'</div>'; }
        if ($undrow['privatejobcomments']) {$tempcontent.='<div>'.$undrow['privatejobcomments'].'</div>'; }
        
        if ($undrow['cojmname']) {
            $idnum=$idnum+1;
            $idtext=$idtext.'{
            "id":"'.$idnum.'",
            "category":"'.$undrow['cojmname'].'",
            "zI":"30",
            "img":"regroup.png",
            "name":"'.$temptitle.'",
            "title":"'.$temptitle.'",
            "street_address":"'.$tempcontent.'",
            "lat":"'.$pclat.'",
            "lng":"'.$pclon.'"},';
            if (strpos($cattext,$undrow['cojmname']) == true) { } else { $cattext.= '"'.$undrow['cojmname'].'",'; }
        }
        $temptitle='';
        $tempcontent='';
    } // ends check to make sure delivery postcode
} // ends loop for check for undelivered jobs


// see if unscheduled job
$sql="
SELECT * FROM Orders 
INNER JOIN Clients 
INNER JOIN Services 
ON 
Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
WHERE `Orders`.`status` <77 
AND `Orders`.`CyclistID` = 1
AND `Orders`.`nextactiondate` < ?
ORDER BY `Orders`.`nextactiondate` 
";

$prep = $dbh->prepare($sql);
$prep->execute([$todayend]);
$stmt = $prep->fetchAll();

foreach ($stmt as $unsrow) {

    if ($unsrow['enrpc0']) {
        $pc1 = str_replace (" ", "", $unsrow['enrpc0']);
        $query="SELECT * 
        FROM  `postcodeuk` 
        WHERE  `PZ_Postcode` =  ?
        LIMIT 1"; 
        $statement = $dbh->prepare($query);
        $statement->execute([$pc1]);
        $pcrow = $statement->fetch(PDO::FETCH_ASSOC);
        // echo '<p>pc1 : '.$pc1.$pcrow["PZ_easting"].$pcrow["PZ_northing"].'</p>';
        
        
        if (($pclat) and ($pclon)) {
        
            $unsid=$unsrow['ID'];
            $pclon=$pcrow['PZ_easting'];
            $pclat=$pcrow['PZ_northing'];
            $collecttime=date('H:i A ', strtotime($unsrow['targetcollectiondate'])); 
            $numberitems= trim(strrev(ltrim(strrev($unsrow['numberitems']), '0')),'.');
            $outs="<br/><a href='order.php?id=".$unsid."'>".$unsid.'</a> ';
            
            $temptitle='Unscheduled, Collection Due '.$collecttime;
            $tempcontent= "<div><strong>Unscheduled</strong><br/><a href=$outs Collection due $collecttime<br>From ".
            $unsrow['enrpc0'].' to '.$unsrow['enrpc21']. " <br />$numberitems x ".$unsrow['Service']." <br /> ". $unsrow['CompanyName'] . " </div> ";
            
            
            if ($unsrow['jobcomments']) { $tempcontent.= '<div>'.$unsrow['jobcomments'].'</div>'; }
            if ($unsrow['privatejobcomments']) { $tempcontent.='<div>'.$unsrow['privatejobcomments'].'</div>'; }
            
            
            $newdat.='
            // Unscheduled job
                { lat: '.$pclat.', lng: '.$pclon.', title: "'.$temptitle.'", image: "images/theft.png", zI:100, content : "'.$tempcontent.'" }, ';
            
            $idnum=$idnum+1;
            $idtext.='
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
                
            if (strpos($cattext,'Unscheduled') == true) { } else { $cattext.='"Unscheduled",'; }
            
            $temptitle='';
            $tempcontent='';
        }    // ends ckeck for $pclat and $pclon
    } // ends check to make sure collection postcode
} // ends loop for check for unscheduled jobs



$cattext=rstrtrim($cattext, ',');
$idtext =rstrtrim($idtext, ',');

echo '{"categories":['.$cattext.'],"markers":['; 
echo $idtext.']}';



// A SCRIPT TIMER
$now_time = microtime(TRUE);
$cj_lapse_time = $now_time - $cj_time;
$cj_msec = $cj_lapse_time * 1000.0;
$cj_echo = number_format($cj_msec, 1);

?>