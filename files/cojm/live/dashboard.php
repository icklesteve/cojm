<?php 

/*
    COJM Courier Online Operations Management
	dashboard.php
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

error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
include "changejob.php";

setlocale(LC_MONETARY, 'en_GB');   // used to define curr


if (isset ($_POST['clientid'])) { $clientid=$_POST['clientid']; } else { $clientid=''; }

// %2F1


if (isset($_POST['from'])) {

$start=trim($_POST['from']);
$tstart = str_replace("%2F", ":", "$start", $count);
$tstart = str_replace("/", ":", "$start", $count);
$tstart = str_replace(",", ":", "$tstart", $count);
$temp_ar=explode(":",$tstart); 
$day=$temp_ar['0']; 
$month=$temp_ar['1']; 
$year=$temp_ar['2']; 
$hour='00';
$minutes='00';
$second='00';
$sqlstart= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
if ($year) { $inputstart=$day.'/'.$month.'/'.$year; }


} else {

$sqlstart='';
$inputstart='';

}

if (isset($_POST['to'])) {

$end=trim($_POST['to']);
$tend = str_replace("%2F", ":", "$end", $count);
$tend = str_replace("/", ":", "$end", $count);
$tend = str_replace(",", ":", "$tend", $count);
$temp_ar=explode(":",$tend); 
$day=$temp_ar['0']; 
$month=$temp_ar['1']; 
$year=$temp_ar['2']; 
$hour= '23';
$minutes= '59';
$second='59';
if ($year) { $inputend=$day.'/'.$month.'/'.$year; }
$sqlend= date("Y-m-d H:i:s", mktime(23, 59, 59, $month, $day, $year));
if (($sqlstart) and (!$year)) { $sqlend='3000-12-25 23:59:59'; }

} else {

$sqlend='';
$inputend='';

}

$rmcost='';
$licost='';


?><!DOCTYPE html> 
<html lang="en"> 
<head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<title>COJM : Dashboard</title>
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
	<script type="text/javascript">	
	$(document).ready(function() {
			$(function(){
				  $('#rangeBa, #rangeBb').daterangepicker();  
			 });
	$(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
		});
	});
	</script>
</head>
<body>
	<?php  
$filename="dashboard.php";
$invoicemenu='0';
$adminmenu='1';
include "cojmmenu.php";
?>

<div class="Post">
	<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 1em; width:auto;">
		<h2>COJM Dashboard</h2>

<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 1em; width:auto;"><p>
<form action="#" method="post"> 
Collections From	<input class="ui-state-default ui-corner-all" size="10" type="text" name="from" value="<?php echo $inputstart; ?>" id="rangeBa" />			
To <input class="ui-state-default ui-corner-all"  size="10" type="text" name="to" value="<?php echo $inputend; ?>" id="rangeBb" />			

<button action="submit">Submit</button>
</form>
<br />
	<p><a href="bulkjobs.php">View Bulk Jobs</a></p>
<br />
</div></div>
<?php

$tablecost='0';


if (trim($inputstart)) {

    $datenumberitems='';
    $dateFreightCharge='';
    $tablecost='';
    $tabletotal='';
    
    $lico='';
    $batchcount='';
    $rmico='';
    $unlicost='';
    $hourlycount='';
    $hourlycost='';
    $unlico='';
    
    $sql = "SELECT 
    numberitems,
    FreightCharge,
    LicensedCount,
    UnlicensedCount,
    RMcount,
    hourlyothercount
    FROM Orders 
    INNER JOIN Services ON Orders.ServiceID = Services.ServiceID 
    WHERE Orders.targetcollectiondate >= ?
    AND Orders.targetcollectiondate <= ? ";
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$sqlstart,$sqlend]);
    $stmt = $prep->fetchAll();
        
    foreach ( $stmt as $row) {
        
        $datenumberitems=$datenumberitems+$row["numberitems"];
        $dateFreightCharge=$dateFreightCharge+$row['FreightCharge'];


        $tablecost = $tablecost + $row["FreightCharge"];
        $licotemp = $row["LicensedCount"] * $row['numberitems'];
        if ($row["LicensedCount"] >'0') { $licost = $licost + $row["FreightCharge"];}
        $lico = $lico + $licotemp ;
        $unlicotemp = $row["UnlicensedCount"] * $row['numberitems'];
        if ($row["UnlicensedCount"] >'0') { $unlicost = $unlicost + $row["FreightCharge"];}
        $unlico = $unlico + $unlicotemp ; 
        
        $rmcotemp = $row["RMcount"] * $row['numberitems'];
        if ($row["RMcount"] >'0') { $rmcost = $rmcost + $row["FreightCharge"];}
        $rmico = $rmico + $rmcotemp ;	
        
        
        if ($row['hourlyothercount']>'0') {
            $hourlycost=$hourlycost + $row['FreightCharge'];
            $hourlycount=$hourlycount+ $row['numberitems'];
        }
    }

    echo '<br />'.$datenumberitems.' items.';
    echo '<br />&'. $globalprefrow['currencysymbol'].' '.number_format($dateFreightCharge).' Total Income ';
    echo '<br />&'. $globalprefrow['currencysymbol'].' '.number_format(( ($dateFreightCharge) / ($datenumberitems) ) , 2, '.', ',').' average per item.
    <div class="line"> </div><br />';

    if ($lico) {
        echo $lico . ' Licensed Items'; 
        echo '<br />&'. $globalprefrow['currencysymbol'].' '. $licost .' Licensed Income';  
        echo '<br />&'.$globalprefrow['currencysymbol'].' '. number_format(( ($licost) / ($lico) ) , 2, '.', ',').' Average Income
        <div class="line"> </div><br /> '; 
    }
    
    if ($unlico) {
        echo ' '.$unlico;
        if ($globalprefrow['showpostcomm']>'0') { echo ' Unlicensed Deliveries '; } else { echo ' Deliveries '; } 
    
        // echo '<br />'. $unlico.' Volume ';
        
        echo '<br />'. '&'.$globalprefrow['currencysymbol'].' '. $unlicost .' Income ';
        echo '<br />'. '&'.$globalprefrow['currencysymbol'].' '.number_format((($unlicost / $unlico)) , 2, '.', ',').' Average per item ';
        echo '<div class="line"> </div><br />';
    }
    
    if ($rmico) { 
    echo $rmico. ' Items passed to subcontractor  '; 
    
    echo '<br /> &'.$globalprefrow['currencysymbol'].' '. $rmcost.' Subcontractor Spend
    <br /> &'.$globalprefrow['currencysymbol'].' '.number_format(( ($rmcost) / ($rmico) ) , 2, '.', ',') .' Average subcontractor spend'; 
    
    echo '<div class="line"> </div><br />';
    
    }
    
    if ($hourlycount) { 
    
        echo $hourlycount .' hours booked in hourly rate
        
        <br /> &'.$globalprefrow['currencysymbol'].$hourlycost.' hourly cost  ';
        
        echo '<br /> &'.$globalprefrow['currencysymbol'].number_format(( ($hourlycost) / ($hourlycount) ) , 2, '.', ',') .' Average hourly rate '; 
        
        echo '<div class="line"> </div><br />'; 
    
    }
    

} // ends check for a posted start date


echo '
			</div></div><br />
</div>';

include "footer.php";

echo '</body>
</html>';
