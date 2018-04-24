<?php 
/*
    COJM Courier Online Operations Management
	clientviewtargetcollection.php - General Purpose Job Lookup
    Copyright (C) 2018 S.Young cojm.co.uk

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
$numjobs=0;
$vattablecost='0';
$showdepcol='';
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
if (isset($_GET['areaid'])) { $areaid=trim($_GET['areaid']);} else { $areaid='all'; }





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
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
';

?>
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<script type="text/javascript" src="js/jquery-ui.1.8.7.min.js"></script>
<script type="text/javascript" src="js/jquery.floatThead.js"></script>
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
	
	

echo ' <select id="combobox" size="14" name="clientid" class="ui-state-highlight ui-corner-left">
<option value="">Select one...</option>
<option 
';
 if ($clientid=="all") {echo ' SELECTED ';} 
echo ' value="all">All Clients</option>';

foreach ($currentclientdata as $CustomerIDlist => $CompanyName) {
    print'<option value="'.$CustomerIDlist.'" ';
	if ($CustomerIDlist == $clientid) {
        echo "SELECTED "; 
    }
    
    echo '>' . htmlspecialchars ($CompanyName).'</option>';

}    


echo '</select>		';

	
	
	
	
	
	

$sql = "SELECT depnumber, depname FROM clientdep WHERE associatedclient = ? ORDER BY depname"; 
        $prep = $dbh->prepare($sql);
        $prep->execute([$clientid]);
        $stmt = $prep->fetchAll();

// echo $sumtot.' Department(s) : '.$viewselectdep;	

if ($stmt) {

echo ' <select class="ui-state-default ui-corner-left" name="viewselectdep" >
<option value="">All Departments</option>';

foreach ($stmt as $crow) {
 
    $CustomerID = $crow['depnumber'];
    $CompanyName = htmlspecialchars($crow['depname']); 
    print'<option ';
    
    if ($CustomerID==$viewselectdep) { echo ' SELECTED '; }
    
    echo 'value= "'.$CustomerID.'" >'.$CompanyName.'</option>';
} 

echo '</select> ';


} else {
    $viewselectdep='';
}
	
	
	
	
	
	
	
	
	
	
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

<button class="newjobsubmit" type="submit" >Search</button> ';


echo '<hr />';


// echo $globalprefrow['glob5'].' ';

$sql = "SELECT CyclistID, cojmname, trackerid FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 

        $prep = $dbh->query($sql);
        $stmt = $prep->fetchAll();
echo '<select name="newcyclistid" class="ui-state-highlight ui-corner-left">';

echo '<option value="all">All '.$globalprefrow['glob5'].'s</option>';

foreach ($stmt as $orow) {

    print ("<option "); 
    if ($orow['CyclistID'] == $newcyclistid) {
        echo ' selected="selected" ';
        $thistrackerid=$orow['trackerid'];
    } 
    echo 'value="'.$orow['CyclistID'].'">'.$orow['cojmname'].'</option>';
}
print ("</select> "); 





// echo $servicetype;


$sql = "
SELECT ServiceID, 
Service 
FROM Services 
ORDER BY serviceorder DESC, ServiceID ASC"; 
        $prep = $dbh->query($sql);
        $stmt = $prep->fetchAll();

print (" <select class=\"ui-state-default ui-corner-left\" name=\"servicetype\"  >"); 

echo '<option ';
if ($servicetype=='all') { echo 'selected'; }
echo ' value="all">All Services</option>';

foreach ($stmt as $orow) {
    
    $ServiceID = $orow['ServiceID'];	
    $Service = htmlspecialchars($orow['Service']); 
    print (" <option "); 
	if ($ServiceID == $servicetype) { echo " SELECTED "; }
    echo ' value="'.$ServiceID.'">'.$Service.'</option>';
    }
    print ("</select>"); 



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
<option '; if ($orderby=='actualdeliver') { echo 'selected'; } echo ' value="actualdeliver">Actual Delivery Time</option>
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




echo ' <select name="areaid" class="ui-state-highlight ui-corner-left">';
echo ' <option value="all" > Choose Area </option> ';

$sql = "SELECT opsmapid, opsname, descrip, istoplayer FROM opsmap WHERE type=2 AND corelayer='0' ";

$prep = $dbh->query($sql);
$stmt = $prep->fetchAll();

foreach ($stmt as $orow) {
            
    print '<option title="'.$orow['descrip'].'" ';
    if ($orow['opsmapid']==$areaid) {
        echo ' selected="selected" ';
    } 

    echo 'value="'.$orow['opsmapid'].'" >' .$orow['opsname'];
    if ($orow['istoplayer']=='1') { 
        echo ' ++ ';
    }
    echo '</option>';
}

echo '</select>';
echo '</div></form>';


$conditions = array();
$parameters = array();
$where = "";



if ($timetype=='collect') {
    $conditions[] = " ( ( Orders.collectiondate > :sqlstart AND Orders.collectiondate < :sqlenda  ) or ( Orders.finishtrackpause > :sqlstartb AND Orders.finishtrackpause < :sqlendb ) ) ";    
    $parameters[":sqlstarta"] = $sqlstart;
    $parameters[":sqlstartb"] = $sqlstart;
    $parameters[":sqlenda"] = $sqlend;
    $parameters[":sqlendb"] = $sqlend;
    
}



if ($timetype=='deliver') {
    $conditions[] = "  ( Orders.ShipDate > :sqlstart AND Orders.ShipDate < :sqlend  ) ";
    $parameters[":sqlstart"] = $sqlstart;
    $parameters[":sqlend"] = $sqlend;
} 
 
 
 
if ($timetype=='tarcollect') {
    $conditions[] = " ( Orders.targetcollectiondate > :sqlstart AND Orders.targetcollectiondate < :sqlend ) ";
    $parameters[":sqlstart"] = $sqlstart;
    $parameters[":sqlend"] = $sqlend;
} 
 

if ($timetype=='actualcollect') {
    $conditions[] = " ( Orders.collectiondate > :sqlstart AND Orders.collectiondate < :sqlend ) ";
    $parameters[":sqlstart"] = $sqlstart;
    $parameters[":sqlend"] = $sqlend;
}
 
 
 
if ($timetype=='tardeliver') {
    $conditions[] = " ( Orders.duedate > :sqlstart AND Orders.duedate < :sqlend ) ";
    $parameters[":sqlstart"] = $sqlstart;
    $parameters[":sqlend"] = $sqlend;
}
 

 
if ($statustype=='notinvoiced') {
    $conditions[] =   " Orders.status < '110' ";
}

 
 
if ($statustype=='notinvoicedcomp') { 
    $conditions[] =  "  Orders.status < '110' AND Orders.status > '99' ";
} 
 

if ($clientid<>'all') {
    $conditions[] =" Orders.CustomerID = :clientid "; 
    $parameters[":clientid"] = $clientid;    
}

if ($viewselectdep<>'') {
    $conditions[] =" Orders.orderdep = :viewselectdep "; 
    $parameters[":viewselectdep"] = $viewselectdep;        
    }

if ($newcyclistid<>'all') {
    $conditions[] =" Orders.CyclistID = :newcyclistid "; 
    $parameters[":newcyclistid"] = $newcyclistid;
    }
    
if ($deltype=='licensed') {
    $conditions[] = " Services.LicensedCount ='1' ";
}

if ($deltype=='deliveries') {
    $conditions[] = "  Services.UnlicensedCount='1' ";
}

if ($deltype=='hourly') {
    $conditions[] = "  Services.hourlyothercount='1' ";
}

if ($deltype=='other') {
    $conditions[] = "  ( Services.UnlicensedCount<>'1' AND Services.hourlyothercount<>'1' AND Services.LicensedCount <>'1' ) ";
}

if ($servicetype<>'all') {
    $conditions[] =" Services.ServiceID = :servicetype "; 
    $parameters[":servicetype"] = $servicetype;
}

if ($areaid<>'all') {
    $conditions[] =" Orders.opsmaparea = :areaid "; 
    $parameters[":areaid"] = $areaid;
}











    if (count($conditions) > 0) {
        $where = implode(' AND ', $conditions);
    }
    
    
     $sql = "
SELECT * FROM Orders
INNER JOIN Services ON Orders.ServiceID = Services.ServiceID 
INNER JOIN Cyclist ON Orders.CyclistID = Cyclist.CyclistID 
INNER JOIN status ON Orders.status = status.status
INNER JOIN Clients ON Orders.CustomerID = Clients.CustomerID
LEFT JOIN clientdep ON Orders.orderdep = clientdep.depnumber 
". ($where != "" ? " WHERE $where" : "");



if ($orderby=='targetcollection') { $sql.=" ORDER BY `Orders`.`targetcollectiondate` ASC "; }
if ($orderby=='pricehilow') { $sql.=" ORDER BY `Orders`.`FreightCharge` DESC,  `Orders`.`targetcollectiondate` DESC "; }
if ($orderby=='status') { $sql.=" ORDER BY `Orders`.`status` ASC "; }
if ($orderby=='id') { $sql.=" ORDER BY `Orders`.`ID` ASC "; }
if ($orderby=='numberitems') { $sql.=" ORDER BY `Orders`.`numberitems` DESC "; }
if ($orderby=='nextaction') { $sql.=" ORDER BY `Orders`.`nextactiondate` DESC "; }
if ($orderby=='actualdeliver') { $sql.=" ORDER BY `Orders`.`ShipDate` DESC "; }

// echo $sql;    
    
    

    try {
        if (empty($parameters)) {
            $result = $dbh->query($sql);
        }
        else {
            $statement = $dbh->prepare($sql);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $result = $statement->fetchAll();
        }
    }
    catch(Exception $ex) {
        echo $ex->getMessage();
    }
    
    
    
    
// normal
// client
// clientprice

$firstrun='1';
$today = date(" H:i A, D j M");



    
if ($result) {
    

    $showdep=0;
    
    if ($clientview<>'normal') { echo '<br />'; }
    
    echo '<div class="vpad"></div>
    
    <table id="clientviewtargetcollection" class="acc" ';
    
    if ($clientview=='normal') { echo ' style="width:100%;"  '; }
    
    echo '><thead><tr><th scope="col">COJM ID</th>';
    
    if ($clientid=='all')  { echo '<th scope="col">Client</th>'; }
    
    
    
    echo ' <th class="depcol hideuntilneeded" scope="col"> Department </th> '; 
    
    $i='1';
    
    if ($newcyclistid=='all') { echo '<th scope="col">'.$globalprefrow['glob5'].'</th>'; }
    
    echo '<th scope="col">Service</th>';
    
    if ($clientview<>'client') { echo '<th title="Incl. VAT" scope="col">Net Cost</th>'; }
    
    echo '<th scope="col">Job Status</th>
    <th title="Including Area" scope="col">To / From</th>
    <th scope="col">Target Collection</th>
    <th scope="col">Collection</th>
    <th scope="col">Target Delivery</th>
    <th scope="col">Delivery</th>
    </tr>
    </thead>
    <tbody>
    
    ';
    
    $tablecost='';
    $tabletotal='';
    $temptrack='';
    $tottimedif='';
    $secmod='';
    
    foreach ($result as $row ) {
        $numjobs++;
            
        if ($row['opsmaparea']) { array_push($areaarray,$row['opsmaparea']); }
        array_push($subareaarray,$row['opsmapsubarea']);
        
            
        // echo $row['opsmaparea'].$row['opsmapsubarea'].' found, in array :';
        // print_r(array_values($areaarray)).'<br />';
        
        echo '<tr><td>';
        
        
        if ($clientview<>'normal') {
        
            echo '<a target="_blank" href="'. $globalprefrow['locationquickcheck'].'?quicktrackref='; 
            echo $row['publictrackingref'].'">'. $row['publictrackingref'].'</a>';
        } else {
            echo '<a href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a>';
            if ($viewcomments=='1') { 
                $shortcomments = (substr($row['jobcomments'],0,30));
                $privateshortcomments = (substr($row['privatejobcomments'],0,30));
                echo ' '.$shortcomments.' '.$privateshortcomments.' '.$row['podsurname'];
            } else {
                if (($row['jobcomments']) or ($row['privatejobcomments']) or ($row['podsurname']) ) {
                    echo ' <img src="../images/page_java.gif" alt="Job Comments" title="'. $row['jobcomments'].' ' . $row['privatejobcomments'].' ' . $row['podsurname'].'" > ' ;
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
            


            $sql="SELECT timestamp FROM `instamapper` 
            WHERE `device_key` = ?
            AND `timestamp` > ?
            AND `timestamp` NOT BETWEEN ?
            AND ?
            AND `timestamp` < ?
            ORDER BY `timestamp` ASC 
            LIMIT 1";             
            
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$thistrackerid,$collecttime,$startpause,$finishpause,$delivertime]);
            $foundlast = $stmt->fetchColumn();
            
            if ($foundlast){
                $trackingtext= 'Tracking started ' . date('H:i A D jS ', $foundlast) . ', '; 
                
                
                $sql="SELECT timestamp FROM `instamapper` 
                WHERE `device_key` = ?
                AND `timestamp` > ?
                AND `timestamp` NOT BETWEEN ?
                AND ?
                AND `timestamp` < ?
                ORDER BY `timestamp` DESC 
                LIMIT 1";             
                
                $stmt = $dbh->prepare($sql);
                $stmt->execute([$thistrackerid,$collecttime,$startpause,$finishpause,$delivertime]);
                $foundlastb = $stmt->fetchColumn();
                
                if ($foundlastb){
                    $trackingtext.= ' Last updated ' . date('H:i A D jS ', $foundlastb) . ', '; 
                }
            }
            
            if ($trackingtext) {
                echo '<a href="../createkml.php?id='. $row['publictrackingref'].'"><img src="../images/icon_world_dynamic.gif" alt="Download Tracking" title="'.$trackingtext.'"></a>';
                
                array_push($gpxarray,$row['publictrackingref']);
            }
            
            
            
            
            $query = "SELECT * FROM cojm_pod WHERE id = :getid LIMIT 0,1";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':getid', $row['publictrackingref'], PDO::PARAM_INT); 
            $stmt->execute();
            $total = $stmt->rowCount();
            if ($total=='1') {
                echo ' <img src="../images/noteb_pod_20x21.png" style="height:19px; width:18px;" alt="POD" title="POD" > ';
            }
            
            echo '</td>';
            
            
        } 
        
        if (($row['isdepartments']=='1') and ($clientid<>'all'))  { $showdepcol=1; }
        

        echo ' <td class="depcol hideuntilneeded"> ';
        if ($row['depname']) {
            if ($clientview=='normal') {
                echo ' (<a href="new_cojm_department.php?depid='.$row['orderdep'].'">'.$row['depname'].'</a>) ';
            } else {
                echo ' '.$row['depname'].' ';
            }
        }
        echo '</td>'; 
        
        
        if ($clientid=='all')  {
            
            echo '<td>';
        
            echo '<a href="new_cojm_client.php?clientid='.$row['CustomerID'].'">'.$row['CompanyName'].'</a>';
            
            
            if ($row['depname']) {
                if ($clientview=='normal') {
                    echo ' (<a href="new_cojm_department.php?depid='.$row['orderdep'].'">'.$row['depname'].'</a>) ';
                }
                else {
                echo ' '.$drow['depname'].' ';
                }
            }
            echo '</td>';
        }
        
        
        
        if ($newcyclistid=='all') {
            echo '<td>';
            if ($row['CyclistID']<>1) {
                if ($clientview<>'normal') {
                    echo ''.$row['poshname'].' ';
                } else {
                    echo '<a href="cyclist.php?thiscyclist='.$row['CyclistID'].'">'.$row['cojmname'].'</a>';
                }
            } // ends rider not unallocated
            echo '</td>';
        }
        
        
        echo '<td>'. formatmoney($row["numberitems"]) .' x '. $row['Service'].'</td>';
        
        if ($clientview<>'client') {
            echo '<td  class="rh" title="&'.$globalprefrow['currencysymbol'].number_format(($row['vatcharge']), 2, '.', ',').' VAT" >&'. $globalprefrow['currencysymbol']. number_format(($row['vatcharge']+$row["FreightCharge"]), 2, '.', ','); 
            if (($row["numberitems"])>'1') {
                echo ' ( &'.$globalprefrow['currencysymbol'].number_format((($row['vatcharge']+$row["FreightCharge"]) / $row["numberitems"] ), 2, '.', ',') . ' ea ) ';
            }
            echo '</td>'; 
        }
        echo '<td>'. $row['statusname'].' </td><td>';
        
        
        if ((trim($row['enrft0'])) or (trim($row['enrpc0']))) { echo ' PU '; }
        
        echo $row['enrft0']. ' ';  
        
        if (trim($row['enrpc0'])) { 
        
        $linkenrpc0 = strtoupper(str_replace(' ','+',$row['enrpc0'])); 
        
        echo '<a target="_blank" class="newwin" href="http://maps.google.com/maps?q='. $linkenrpc0.'">'. $row['enrpc0'].'</a>'; }
        
        
        if ((trim($row['enrft0'])) or (trim($row['enrpc0']))) { 
        if ( (trim($row['enrft21'])) or (trim($row['enrpc21']))) { echo '<br /> '; }
        }
        
        
        
        
        
        if ( (trim($row['enrft21'])) or (trim($row['enrpc21']))) { echo ' To '; }
        
        echo $row['enrft21'].' ';
        
        if (trim($row['enrpc21'])) {
            
            
        $linkenrpc21 = strtoupper(str_replace(' ','+',$row['enrpc21'])); 	
            
        echo ' <a target="_blank" class="newwin" href="http://maps.google.com/maps?q='. $linkenrpc21.'">'. $row['enrpc21'].'</a>'; }
        
        
        
        
        
        if ($row['opsmaparea']) {
            $sql = " SELECT opsname FROM opsmap WHERE opsmapid=? ";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$row['opsmaparea']]);
            $name = $stmt->fetchColumn();
    
            echo $name.' ';
        
            if ($row['opsmapsubarea']) {
                $sql = " SELECT opsname FROM opsmap WHERE opsmapid=? ";
                $stmt = $dbh->prepare($sql);
                $stmt->execute([$row['opsmapsubarea']]);
                $name = $stmt->fetchColumn();
                echo ' ( Sub Area '.$name.' ) ';
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
        
        $temptrack=$temptrack.'<input type="hidden" name="tr'.$i.'" value="'.$row['ID'].'" />';
        
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
    
    
    if ($sarearesult) {
        // echo ' &amp; Sub Areas : <input type="checkbox" name="showsubarea" value="1" checked />';
    }
    
    
    
    
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
    
    
    
    
    
    
    
    
    echo '</div></div>';
    
    
    
    // <form action="createbatchkml.php" method="post">
    // echo $temptrack; 
    // <button type="submit"> Download all tracking data for these jobs</button>
    // </form>
    // <br />
    
    
    
    
    } else {
    
    if ($start<>'') {
    
    echo '<h2>No Results Found</h2>';
    }
}
    

 echo '<div class="vpad"></div><div class="line"></div><br /></div>';

 
 echo '<script type="text/javascript">	
$(document).ready(function() {	
	$( "#combobox" ).combobox();
	 
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
	    $("#rangeBa, #rangeBb").daterangepicker();  
        ';
        
        
        if ($showdepcol==1){
            echo ' $(".depcol").show();   ';
        }
        
        
        
        
        
if ($clientview=='normal') {
    
    echo ' var menuheight=$("#sticky_navigation").height();
    $("#clientviewtargetcollection").floatThead({
        position: "fixed",
        top: menuheight
    });';
}

echo '
			 });

function comboboxchanged() { }

</script>';

include 'footer.php';

echo '</body></html>';