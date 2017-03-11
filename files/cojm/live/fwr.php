<?php

/*
    COJM Courier Online Operations Management
	fwr.php - Further Work Required Admin Queue - also does some admin checks
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

$alpha_time = microtime(TRUE);
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include 'changejob.php';

$adminmenu ="1";
$invoicemenu = "0";
$settingsmenu='0';
$title = "COJM";

echo '<!DOCTYPE html> 
<html lang="en"> 
<head> 
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height" >
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<title>'. $title.' ADMIN</title></head><body>';

$filename="fwr.php";
include "cojmmenu.php";

echo '<div id="Post" class="Post">';


///////    START OF MAIN FWR JOBLIST

$fwrtot=0;
$fwrcost='0.00';
$html='';

 $sql = "
 SELECT 
p.ID,
p.status,
p.ShipDate,
p.enrpc21,
p.enrpc0,
p.enrft21,
p.enrft0,
p.opsmaparea,
p.opsmapsubarea,
p.CustomerID,
p.CyclistID,
p.jobcomments,
p.privatejobcomments,
u.CompanyName,
p.orderdep,
l.depname,
p.numberitems,
t.Service,
p.FreightCharge,
p.vatcharge,
p.CyclistID,
r.cojmname,
y.opsname,
y.descrip,
z.opsname AS `subareaname`,
z.descrip AS `subareadescrip`,
e.type

FROM Orders p
INNER JOIN Clients u ON p.CustomerID = u.CustomerID
INNER JOIN Services t ON p.ServiceID = t.ServiceID
left join clientdep l ON p.orderdep = l.depnumber
INNER JOIN Cyclist r ON p.CyclistID = r.CyclistID
left join opsmap y ON p.opsmaparea = y.opsmapid
left join opsmap z on p.opsmapsubarea = z.opsmapid
left join cojm_pod e ON p.publictrackingref = e.id

WHERE `p`.`status` = 86
ORDER BY `p`.`ShipDate` ASC";
 
$stmt = $dbh->query($sql);

     
if ($stmt) {
    $rhtml='';
    $html='';
    
    
    $rhtml= '
    <table class="acc" id="fwr">
    <thead>
    <tr>
    <th>Delivery Time</th>
    <th scope="col">To / From</th>
    <th scope="col">Client</th>
    <th scope="col">Service</th>
    <th scope="col">Ex VAT Cost</th>
    <th scope="col">'.$globalprefrow['glob5'].'</th>
    <th scope="col" style="width:24%;">Comments</th>
    </tr>
    </thead>
    <tbody>';
    
    

 
    foreach ($stmt as $row) {
        
        $fwrcost=$fwrcost+$row['FreightCharge']+$row['vatcharge'];
        $fwrtot++;
        
        
        $numberitems= trim(strrev(ltrim(strrev($row['numberitems']), '0')),'.');
        $rhtml.='<tr><td><a target="_blank" class="newwin" href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a> ';
        // if different month show month AND date
        if (date('M')<>(date('M', strtotime($row['ShipDate'])))) { 
            $rhtml.= date('H:i A D jS M', strtotime($row['ShipDate']));
        } else {
            $rhtml.= date('H:i A D jS', strtotime($row['ShipDate']));
        }
        
        $rhtml.= '</td><td>';
        
        if ((trim($row['enrft0'])) or (trim($row['enrpc0']))) {
            $linkenrpc0 = strtoupper(str_replace(' ','+',$row['enrpc0'])); 
            $rhtml.= 'PU '.$row['enrft0'].' <a target="_blank" class="newwin" href="http://maps.google.com/maps?q='. 
            $linkenrpc0. '">'. $row['enrpc0'].'</a> ';
        }
        
        if (((trim($row['enrft0'])<>'') or (trim($row['enrpc0'])<>'')) and ((trim($row['enrft21'])<>'') or (trim($row['enrpc21'])<>''))) {
            $rhtml=$rhtml. '<br />';
        }
        
        if ((trim($row['enrft21'])) or (trim($row['enrpc21']))) {
            $rhtml.= 'To ';
            $linkenrpc21 = strtoupper(str_replace(' ','+',$row['enrpc21']));
            $rhtml.= ''.$row['enrft21'].' <a target="_blank" class="newwin" href="http://maps.google.com/maps?q='. 
            $linkenrpc21.'">'. $row['enrpc21'].'</a>';
        }
        
        if ($row['opsmaparea']) {
            $rhtml.= ' To '.$row['opsname'].' ';
            if ($row['opsmapsubarea']) {
                $rhtml.=' ( '. $row['subareaname'].' ) ';
            }
        }
        
        $rhtml.='</td>
        <td><a href="new_cojm_client.php?clientid='.$row['CustomerID'].'">'.$row['CompanyName'].'</a>';
        
        if ($row['orderdep']) {
            $rhtml.=' (<a href="new_cojm_department.php?depid='.$row['orderdep'].'">'.$row['depname'].'</a>) ';
        }
 
        $rhtml.='</td>
        <td>';
        if ((formatmoney($row["numberitems"])<>0) and (formatmoney($row["numberitems"])<>1)) {
            $rhtml.=formatmoney($row["numberitems"]).' x ';
        }
        $rhtml.= $row['Service'].'</td>
        <td>&'.$globalprefrow['currencysymbol'].' '.$row['FreightCharge'].'</td>
        <td>';

        if ($row['CyclistID']<>1) {
            $rhtml.=' <a href="cyclist.php?thiscyclist='.$row['CyclistID'].'">'.$row['cojmname'].'</a> ';
            }
            
        $rhtml.='</td><td>';

        if ($row['type']<>'') {
            $rhtml.=' <img src="images/noteb_pod_20x21.png" alt="POD" title="POD" > ';
        }
        
        $rhtml.= ''.$row['jobcomments'].' '.$row['privatejobcomments'].'</td></tr>';
    } // end row loop





    $html.=$rhtml;



    $html.='</tbody></table>';

    $html.= '<div class="vpad line"></div>';
}


//////////////     ENDS MAIN FWR CHECK







    $sqlcostage = "SELECT SUM(FreightCharge + vatcharge) AS cost, count(*) AS number FROM Orders WHERE status > '98' AND status < '108' ";    
    $awaiting = $dbh->query($sqlcostage)->fetchAll();
    

	 
$fwrcost= number_format($fwrcost, 2, '.', '');


echo '<div class="ui-state-highlight ui-corner-all p15 undersearch" > 
	<p>
	<strong>'.$fwrtot.' Job';

if ($fwrtot<>1) { echo 's'; }
echo ' awaiting admin </strong> &'. $globalprefrow['currencysymbol'].$fwrcost.' inc vat.
    <br />
    <strong> '.$awaiting[0]['number'].' Jobs awaiting invoicing </strong>
    &'. $globalprefrow['currencysymbol'] . number_format($awaiting[0]['cost'], 2, '.', ',').' inc vat
    <br />';
    
$lasttracked="SELECT timestamp FROM `instamapper` ORDER BY `instamapper`.`timestamp` DESC LIMIT 0 , 1 ";
$timestamp = $dbh->query($lasttracked)->fetchAll();



echo '<strong>Tracking last updated</strong> '.date('H:i A, l jS M', $timestamp[0]['timestamp']).'.
    </p>
	</div>	';




// cyclist birthdays
$sql = "SELECT cojmname, DOB FROM Cyclist 
	WHERE isactive='1' AND CyclistID>1 AND DOB > 0
	ORDER BY `DOB` ";
$stmt = $dbh->query($sql);
foreach ($stmt as $row) {
    
    $temp_ar=explode("-",$row['DOB']);
    $spltime_ar=explode(" ",$temp_ar['2']);
    $temptime_ar=explode(":",$spltime_ar['1']); 
    if (($temptime_ar['0']=='')||($temptime_ar['1']=='')||($temptime_ar['2']=='')){
        $temptime_ar['0']='0';$temptime_ar['1']='0';$temptime_ar['2']='0';
    }
    $day=$spltime_ar['0']; $month=$temp_ar['1']; $year=$temp_ar['0']; 
    $d=$year."-".$month."-".$day; 
    $todaycheck=$month."-".$day;
    $olddate =  substr($d, 4);
    $newdate = date('Y') ."".$olddate;
    $nextyear = date('Y')+'1' ."".$olddate;


    if($newdate > date("Y-m-d")) {
        $start_ts = strtotime($newdate);
        $end_ts = strtotime(date("Y-m-d"));
        $diff = $end_ts - $start_ts;
        $n = round($diff / 86400);
        $return = substr($n, 1);
        $newreturn=$return;
    }
    else {
        $start_ts = strtotime($nextyear);
        $end_ts = strtotime(date("Y-m-d"));
        $diff = $end_ts - $start_ts;
        $n = round($diff / 86400);
        $return = substr($n, 1);
        $newreturn=$return;
    }

    if ($newreturn<'14') {

        // echo date('l', strtotime(date("Y-$month-$day")));

        echo '<h4>'.$newreturn.' days until '.$row['cojmname']."'s Birthday, who will be "; 
        echo ((date("Y")-$year)).' on '.date('l', strtotime(date("Y-$month-$day"))).', '.$day.' '.$month.' '.date("Y").'.</h4><br>';
    }

    if ($todaycheck==date("m-d")) {
        echo "<h3>".$row['cojmname']."'s Birthday TODAY! (". ((date("Y")-$year)). ") </h3>";
    }

} // ends loop













// check no uninvoiced or active jobs for inactive clients 
$sql = "SELECT ID, CompanyName FROM Orders 
	INNER JOIN Clients ON Orders.CustomerID = Clients.CustomerID 
	 WHERE 
     (`Orders`.`status` < '110' )	 
  	 AND
     (`Clients`.`isactiveclient` <> '1' )
	 ORDER BY `Orders`.`ID` ";



$inactivestmt = $dbh->query($sql)->fetchAll();

if ($inactivestmt) {
    $table.= '
    <table cellspacing="0" class="acc" ><tbody>
    <tr>
    <th scope="col">Job Ref</th>
    <th scope="col">Client</th>
    </tr>';

    foreach ($inactivestmt as $row) {
        $table.= '<tr>
        <td><a target="_blank" href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a></td>
        <td> '.$row['CompanyName'] .' </td>
        </tr>';
        $ordersumtot++;
    }
    
    $table.= '</tbody></table>';

    echo '<h3>'. $ordersumtot.' Uninvoiced Jobs with Client Inactive</h3>
    '.$table.'
    <div class="vpad line"></div>';
}






 
 
 
 
 


// recurring services
$rhtml='';
$flag='';
$sql = "SELECT ServiceID, Service FROM Services  
    WHERE ( `Services`.`isregular`='1' ) 
    AND ( `Services`.`activeservice`='1' ) ";

$prep = $dbh->query($sql);
$stmt = $prep->fetchAll();    
    
foreach ($stmt as $row) {
   
    $sql = "SELECT ID FROM Orders
	WHERE 
	(`Orders`.`status` <50 ) 
	AND
    (`Orders`.`serviceID` =? ) 
	ORDER BY `Orders`.`targetcollectiondate` ";

    $prep = $dbh->prepare($sql);
    $prep->execute([$row['ServiceID']]);
    $istmt = $prep->fetchAll();  
    
    $lastid='';
    $count=0;
    $previd='';
    foreach ($istmt as $irow) {
        //    extract('$orderrow'); 
        $lastid=$irow['ID'];
        $count++;
    }
    
    if ($count<10) {
        $flag='1';
        $rhtml.= ' <p>Only '.$count.' '.$row['Service'] .' remaining. ';

        if ($lastid<>'') {
            $rhtml.= ' Last one is <a href="order.php?id='. $lastid.'">'. $lastid.'</a>';
        }
        
        $rhtml.= ' </p>';             

    }
} // ends loop containing a regular service

if ($flag==1) {
    echo '    <div class="ui-state-highlight ui-corner-all p15 undersearch"> ';
    echo '<h4>Recurring Jobs</h4>'.$rhtml;
    echo '</div>';
    echo '<div class="vpad line"></div>';
}







echo $html.'<div class="vpad"> </div>';





// jobs with status as invoiced but no invoice ref for jobs within the last year

if ($globalprefrow['showdebug']>0) {

 
 
 
 
   
    
    // jobs with pod but no surname
    // should be stopped by javascript before entry
    $sql = "SELECT * FROM Orders
    INNER JOIN cojm_pod ON `Orders`.`publictrackingref` = `cojm_pod`.`id`
    WHERE (`cojm_pod`.`id` >'1' )
    AND (`Orders`.`podsurname` ='' )
    ORDER BY `Orders`.`ID` ";
    
    $prep = $dbh->query($sql);
    $stmt = $prep->fetchAll();
    if ($stmt)  {
        echo '<h4> Jobs with POD, no Surname</h4>
        <table cellspacing="0" class="acc"><tbody>
        <tr><th scope="col ">ID</th>
        <th scope="col"> </th></tr>';
        foreach ($stmt as $row) {
            echo '<tr><td><a target="_blank" href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a></td><td>'.$row['podname'].'</td></tr>';
        } 
        echo '</tbody></table><div class="line"></div><br />';
    }
    
    
    
    
    
   
   
   
   
   
   
   
   
   
   
   
   
   
    // $infotext.= ' checking if gps-admin task needed ';
    $sql = "SELECT COUNT(*) FROM cojm_admin WHERE cojm_admin_stillneeded='1' AND cojmadmin_tracking='1' ";
    $gpsadmintotal = $dbh->query($sql)->fetchColumn();
    if ($gpsadmintotal) {
        echo '<br /> '.$gpsadmintotal.' Job(s) in individ job GPS Admin Q ';
    }
    
    
    
    
    // $infotext.= ' checking if gps-admin task needed ';
    $sql = "SELECT COUNT(*) FROM cojm_admin WHERE cojm_admin_stillneeded='1' AND cojmadmin_rider_gps='1' ";
    $gpsadmintotal = $dbh->query($sql)->fetchColumn();
    if ($gpsadmintotal) {
        echo '<br /> '.$gpsadmintotal.' Job(s) in Rider GPS Admin Q ';
    }
    
    




















    
    
    
    
    // jobs with no collection or delivery date
    $sql = "SELECT ID FROM Orders 
        WHERE (((`Orders`.`status` >99 ) AND (`Orders`.`collectiondate` <110 ))
        OR 
        ((`Orders`.`status` >99 ) AND (`Orders`.`ShipDate` <110 )))
        ORDER BY `Orders`.`ID` ";
    
        $prep = $dbh->query($sql);
        $stmt = $prep->fetchAll();
        
    if ($stmt) {
        $tablecost=0; 
        foreach ($stmt as $row) {
            echo '<h4><a target="_blank" href="order.php?id='. $row['ID']. '">'. $row['ID'] .'</a> missing collection or delivery date</h4>';
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // dodgy expense references
    
    $sql = "SELECT expenseref FROM expenses WHERE (`expenses`.`expensedate` ='0000-00-00 00:00:00' ) 
        or (`expenses`.`expensecost` ='0' )
        ";
    $prep = $dbh->query($sql);
    $stmt = $prep->fetchAll();
    if ($stmt) {
        //	 echo '<h4>Either no date or no cost on :</h4>
        echo '<div class="vpad"> </div>
            <div class="ui-widget">
            <div class="ui-state-highlight ui-corner-all" style="padding: 1em;"> 
            <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
            <strong> Either no date or no cost on </strong> ';

        foreach ($stmt as $row ) {
            echo '<br />Expense ref '.$row['expenseref'];
        }
        echo '</p></div></div><div class="vpad line"></div>';
    }
    
    
    
    
    // jobs with customer id at 0
    $sql = "SELECT * FROM Orders WHERE `Orders`.`CustomerID`=0 ";
        $prep = $dbh->query($sql);
        $stmt = $prep->fetchAll();
    if ($stmt)  {
        echo '<div class="vpad"> </div>
            <div class="ui-widget">
            <div class="ui-state-error ui-corner-all" style="padding: 0.5em;"> 
            <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
            <strong>  Job(s) with Customer ID at 0 </strong> ';
        foreach ($stmt as $row) {
            echo '<div class="vpad"> </div>'. $row['ID'].'';
        }
        echo '</p></div></div>';
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // check co2 savings present
    
        $sql = "SELECT ID, Service, enrpc0, enrpc21 FROM Orders 
        INNER JOIN Services 
        ON 
        Orders.ServiceID = Services.ServiceID 
        WHERE 
        (`Orders`.`enrpc0` <> '' )	 
        AND
        (`Orders`.`enrpc21` <> '' )
        AND
        (`Orders`.`ServiceID` <> '901' )
        AND
        (`Orders`.`ServiceID` <> '9999' )
        AND
        (`Orders`.`ServiceID` <> '5' )	 
        AND
        (`Services`.`LicensedCount` <> '1' )
        AND
        (`Services`.`RMcount` <> '1' )
        AND
        (`Services`.`CO2Saved` = '' )
        AND
        (`Orders`.`co2saving` = '' )
        AND
        (`Orders`.`enrpc21` <> `Orders`.`enrpc0` )
        ORDER BY `Orders`.`ID` ";
        
        
    $prep = $dbh->query($sql);
    $stmt = $prep->fetchAll();
    
    
    if ($stmt) {
        echo '<div class="line"></div>
            <h4> Unlicensed Jobs with no individual carbon savings with both postcodes, and no CO2 in service field</h4>
            <table cellspacing="0" class="acc"><tbody>
            <tr>
            <th scope="col">Job Ref</th>
            <th scope="col">Service</th>
            <th scope="col">From</th>
            <th scope="col">To</th>
            </tr>';
        foreach ($stmt as $row) {
            echo '<tr><td><a target="_blank" href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a></td><td> '.$row['Service'] .' </td>
                <td><a target="_blank" href="http://maps.google.com/maps?q='. $row['enrpc0'].'">'. $row['enrpc0'].'</a></td> 
                <td><a target="_blank" href="http://maps.google.com/maps?q='. $row['enrpc21'].'">'. $row['enrpc21'].'</a></td>
                </tr>';
        } // ends loop
    
    echo '</tbody></table><div class="line"></div>';
    } // ends check for numrows
    
    
    /////     ends co2 check     //////////////////////////////////////////////////////////////////// 
    
    
    
    
    
    
    
    $sql = "SELECT * FROM Orders 
    WHERE `Orders`.`status` <>'30'  
    AND `Orders`.`status` <>'40'
    AND `Orders`.`status` <>'50'
    AND `Orders`.`status` <>'60'
    AND `Orders`.`status` <>'65'
    AND `Orders`.`status` <>'86'
    AND `Orders`.`status` <>'100'
    AND `Orders`.`status` <>'110'
    AND `Orders`.`status` <>'120' 
    ORDER BY `Orders`.`ID` ";
    $prep = $dbh->query($sql);
    $stmt = $prep->fetchAll();
    
    if ($stmt)  { 
        echo '<h4> Jobs with wrong status</h4>
        <table cellspacing="0" class="acc" ><tbody>
        <tr><th scope="col ">ID</th>
        <th scope="col">Status</th>';
        foreach ($stmt as $row) {
            echo '<tr><td><a target="_blank" href="order.php?id='.$row['ID'].'">'. $row['ID'].'</a></td><td>'.$row['status'].'</td></tr>';
        } 
        echo '</tbody></table><div class="line"></div>';
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // jobs with service type at 0
    $sql = "SELECT * FROM Orders WHERE `Orders`.`ServiceID`=0 ";
    $prep = $dbh->prepare($sql);
    $stmt = $prep->fetchAll();
    if ($stmt)  {
        echo '<br />
        <div class="ui-widget">
        <div class="ui-state-error ui-corner-all" style="padding: 1em;">
        <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
        <strong>  Job(s) with Service at 0 </strong> ';
        foreach ($stmt as $row) {
            echo '<br /><a target="_blank" href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a>';
        }
        echo '</p></div></div>';
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    $sql = "SELECT * FROM Orders 
    WHERE `Orders`.`status`>'100' 
    AND `Orders`.`invoiceref`='0' 
    AND `Orders`.`FreightCharge`>'0' 
    AND `Orders`.`ShipDate` >= date_sub(now(), interval 1 year)
    ORDER BY `Orders`.`FreightCharge` DESC ";
    $prep = $dbh->prepare($sql);
    $stmt = $prep->fetchAll();
    
    if ($stmt)  {
        echo '<br />
        <div class="ui-widget">
                    <div class="ui-state-error ui-corner-all" style="padding: 1em;"> 
                        <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
                        <strong> Job(s) with status > 100 and invoice ref = 0 for the last year</strong> ';
        foreach ($stmt as $row) {
            echo '<br /><a target="_blank" href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a> '.$row['FreightCharge'] .' ' .$row['collectiondate'];
        } 
        echo '</p></div></div>';
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
    
    
    
    
    
    
    
    // JOBS WITH DODGY DEPARTMENT
    
    $sql="SELECT * FROM `Orders` 
    LEFT JOIN `clientdep` ON `Orders`.`orderdep` = `clientdep`.`depnumber` ";
    
    // $sql="SELECT * FROM `Orders` ";
    
    
    $prep = $dbh->prepare($sql);
    $stmt = $prep->fetchAll();
    if ($stmt) {
        foreach ($stmt as $row) {
            if ($row['orderdep']>'0') {
                if ($row['CustomerID']<>$row['associatedclient']) { 
                    echo '
                    <br />
                    <div class="ui-widget">
                    <div class="ui-state-error ui-corner-all" style="padding: 1em;"> 
                    <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
                    <strong> Job with wrong department </strong> '; 
        
                    echo '<br />'.$row['ID'].' orderclient :'.$row['CustomerID'];
        
                    echo ' '.$row['associatedclient'];
        
                    echo '</p></div></div>';
                }
            }
            //  echo '<br />'. $row['ID'].' '.$row['associatedclient'].' '.$row['CustomerID'];
        } 
    }
    

} // ends check if debug mode





// Issues with backups
$cronissue='0';
$sql = "SELECT id, time_last_fired FROM cojm_cron WHERE `currently_running` = '1' ";

try {        
    $stmt = $dbh->query($sql);
    $plainbodytext='';
    // echo ' 964 new :  '. $total;
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $plainbodytext.=' CRON Currently running with ID '.$row['id'].'.  Last fired in unix : '. $row['time_last_fired'].'<br />';
        $oldtime=$row['time_last_fired'];
    
        $plainbodytext.=' row id : '.$row['id'].' row time_last_fired '. $row['time_last_fired'].'  ' ;
        // echo date('U', strtotime($oldtime));
        // echo ' '.date("U");
        
        
        
        $textoldtime=date('H:i D M Y', strtotime($oldtime));

        $crondiff=((date("U")-$oldtime)/60);

        $plainbodytext.=' oldtime: '.$oldtime.' textoldtime: '.$textoldtime.' was oldest time '. $crondiff.' mins ago.';


        if ($crondiff>5) {
            
            
            echo '<p> Oldest >5mins ago, resetting cron.';
            $sql = "UPDATE cojm_cron SET currently_running=0 WHERE id=".$row['id'];
            $prep = $dbh->query($sql);
    
            $plainbodytextnice = ' Cron check failed as already running.  
            If oldest was more than 5 mins ago this was reset, 
            however you need to contact your admin if you get this message too often.</p>';

	
        
        
        

        
            $plainbodytextnice=' There may be an issue with COJMCron, which schedules background jobs.  
            Further info is available in the main audit log.
            Cron checks failed as already running, cron reset. ';
            
            $to = $globalprefrow['emailbcc'];
            $from= $globalprefrow['emailfrom'];
            $subject = $globalprefrow['globalshortname']." possible Cron Issue";            
            $headers = 'From: '.$from. PHP_EOL;
            $headers =$headers. 'Return-path: '.$to. PHP_EOL; 
            $headers = $headers . 'Repy-To: '.$to . PHP_EOL.
                "X-Mailer: COJM-Courier-Online-Job-Management" . PHP_EOL.
                "Cc: ".$globalprefrow['glob8'];
            $semi_rand = md5(time());     // Generate a boundary string    
            $mime_boundary = "==Multipart_COJM_Delivery_Boundary_x{$semi_rand}x";    
            // Add the headers for a file attachment    
            $headers .= "\nMIME-Version: 1.0\n" .    
                    "Content-Type: multipart/alternative;\n" .    
                    " boundary=\"{$mime_boundary}\"";
            
        
            $plainbodytext.' Powered by COJM ';
        
            $htmltext ='<html><head> 
            <meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
            <STYLE type=text/css>
            BODY { BACKGROUND-COLOR: #e6b41a; MARGIN-BOTTOM: 5px; COLOR: #000000; MARGIN-LEFT: 20px; FONT-SIZE: 15pt; }
            div.line { width:100%; padding:10px 0 0 5px 0; border:2px solid #fcd66e; border-top:5px; border-right:0; }
            </STYLE>
            </head><body>'.$plainbodytext.'<br /> <small>Powered by <a href="http://www.cojm.co.uk" target="_blank">COJM</a></small> </body></html>';
        
            // Add a multipart boundary above the plain message    
            $messageplain = "This is a multi-part message in MIME format.\n\n" .    
            "--{$mime_boundary}\n" .    
            "Content-Type: text/plain; charset=\"utf-8\"\n" .    
            "Content-Transfer-Encoding: quoted-printable\n\n" .    
            $plainbodytext . "\n\n";
                            
            // Add a multipart boundary above the plain message    
            $messagehtml = "--{$mime_boundary}\n" .    
            
            "Content-Type: text/html; charset=\"utf-8\"\n" .    
            "Content-Transfer-Encoding: quoted-printable\n\n".$htmltext;
                    
            $message = $messageplain . $messagehtml ;	
            $newfrom = htmlspecialchars ($from);
        
        
        
            $message = wordwrap($message, 70, PHP_EOL);
            $ok = @mail($to, $subject, $message, $headers, "-f$from");    
        
        
        
        
        
        
            if ($ok) {
                $transfer_backup_infotext=" Mail sent ";
            } else {
                $transfer_backup_infotext= " Message not sent. ";
            }
            
            
            echo $plainbodytext;
            
            echo ' <hr /> ' . $transfer_backup_infotext;
        }
    }
}

catch(PDOException $e) {
    echo $e->getMessage();
} 


echo '<br /><br />
</div>';

?>

<script type="text/javascript">
function downloadJSAtOnload() {
var element = document.createElement("script");

element.src = "js/<?php echo $globalprefrow['glob9']; ?>";
document.body.appendChild(element);

}
if (window.addEventListener)
window.addEventListener("load", downloadJSAtOnload, false);
else if (window.attachEvent)
window.attachEvent("onload", downloadJSAtOnload);
else window.onload = downloadJSAtOnload;
</script>

<?php

include "footer.php";


?></body></html>