<?php 
error_reporting( E_ERROR | E_WARNING | E_PARSE );
$alpha_time = microtime(TRUE);

include "../../administrator/cojm/updatetracking.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include 'changejob.php';

$filename='invoice.php';
$adminmenu ="0";
$invoicemenu = "1";
$title = "COJM Invoicing DB";
$today = date(" H:i A, D j"); 

?><!DOCTYPE html> 
<html lang="en"> 
<head> 
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php echo '<link rel="stylesheet" href="js/themes/'.$globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" />'; ?>
<link rel="stylesheet" type="text/css" href="cojm.css" >
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<title><?php print ($title); ?> </title>
</head>
<body>
<?php include "cojmmenu.php"; ?><div class="Post">

<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<h4>Invoices need Chasing over 1 month</h4>
<?
$tablecost=0;
$todayDate = date("Y-m-d");// current date
//Add one day to today
$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "-1 month");
$dateamonthago = date('Y-m-d H:i:s', $dateOneMonthAdded);

   $sql = "SELECT * FROM invoicing 
   INNER JOIN Clients 
   ON invoicing.client = Clients.CustomerID 
   WHERE (`invoicing`.`paydate` =0 ) 
   AND (`invoicing`.`invdate1` < '$dateamonthago' ) 
   ORDER BY `invoicing`.`invdate1` ";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 
	 
?>
<table class="ord" ><tbody>
<tr>
<th scope="col">Client</th>
<th scope="col">Invoice Ref</th>
<th scope="col" style="text-align: right;" >Amount</th>
<th scope="col">Date Sent</th>
<th scope="col">Date Chased</th>
<th scope="col">Date Chased 2</th>
<th scope="col">Date Chased 3</th>
</tr>
<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>
<? while ($row = mysql_fetch_array($sql_result)) { extract($row); ?>
<tr>
<td><?php echo $CompanyName;



$tempdep=$row['invoicedept'];

$depsql="SELECT * from clientdep 
WHERE depnumber='$tempdep' LIMIT 0,1";
$dsql_result = mysql_query($depsql,$conn_id)  or mysql_error();


// echo $depsql;




while ($drow = mysql_fetch_array($dsql_result)) {
     extract($drow);
echo ' ('.$drow['depname'].') ';
}







echo '</td><td>'. $ref.'</td><td style="text-align: right;">&'. $globalprefrow['currencysymbol']. $cost.'</td>
<td>'. date('D j M Y', strtotime($invdate1)) .'</td><td>'; 
 if ($chasedate>2) {echo date('D j M Y', strtotime($chasedate));} ?></td>
<td><?php if ($chasedate2>2) {echo date('D j M Y', strtotime($chasedate2));} ?></td>
<td><?php if ($chasedate3>2) {echo date('D j M Y', strtotime($chasedate3));} ?></td>
</tr>
<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>
<?php $tablecost=$tablecost+$cost; } 
$tablecost= number_format($tablecost, 2, '.', ''); ?>
</tbody></table>
<h4>Total Amount Invoiced awaiting payment over 1 month: &<?php echo $globalprefrow['currencysymbol']. $tablecost; ?>
</h4>
</div></div>
<br>
<div class="line"></div><br />
<?php
$tablecost=0;
   $sql = "SELECT CompanyName, ref, invdate1, cost, invoicedept FROM invoicing 
   INNER JOIN Clients 
   ON invoicing.client = Clients.CustomerID 
   WHERE (`invoicing`.`paydate` =0 ) 
   ORDER BY `invoicing`.`invdate1` ";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 
?>

<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">

<h4>All Outstanding Invoices</h4>
<table  class="ord" ><tbody>
<tr><th scope="col">Client</th>
<th scope="col">Invoice Ref</th>
<th scope="col">Date Sent</th>
<th scope="col" style="text-align: right;" >Amount</th></tr>


<tr><td> </td><td> </td><td> </td><td> </td></tr>
<?

$trow='';
$tempthree='';


while ($row = mysql_fetch_array($sql_result)) { extract($row); 

echo '<tr><td>'. $CompanyName;



$tempdep=$row['invoicedept'];


$depsql="SELECT * from clientdep 
WHERE depnumber='$tempdep' LIMIT 0,1";
$dsql_result = mysql_query($depsql,$conn_id)  or mysql_error();





while ($drow = mysql_fetch_array($dsql_result)) {
     extract($drow);
echo ' ('.$drow['depname'].') ';
}



echo '</td><td>'. $ref.'</td>
<td>'. date('D j M Y', strtotime($invdate1)).'</td>
<td style="text-align: right;">&'. $globalprefrow['currencysymbol'] .$cost; ?></td></tr>
<tr><td> </td><td> </td><td> </td><td> </td></tr>
<?php $tablecost=$tablecost+$cost; }

$tablecost= number_format($tablecost, 2, '.', '');
echo '</tbody></table>
<h4>Total Amount Invoiced awaiting payment : &'. $globalprefrow['currencysymbol']. $tablecost.'
</h4></div></div>
<br /><div class="line"></div><br />';


  // A SCRIPT TIMER
        $omega_time = microtime(TRUE);
    $lapse_time = $omega_time - $alpha_time;
    $lapse_msec = $lapse_time * 1000.0;
    $lapse_echo = number_format($lapse_msec, 1);
//    echo "<br/> $lapse_echo MILLISECONDS";





$sql = "SELECT * FROM Clients ORDER BY lastinvoicedate";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 

while ($row = mysql_fetch_array($sql_result)) { extract($row);
$lastdate = "SELECT collectiondate FROM Orders WHERE CustomerID=$CustomerID ORDER BY collectiondate DESC LIMIT 0 , 1";
$sql_result_last = mysql_query($lastdate,$conn_id) or die(mysql_error()); while ($lastrow = mysql_fetch_array($sql_result_last)) { extract($lastrow); 

// if (($lastrow['collectiondate'])>($lastinvoicedate)) {

$tarow= ' <tr><td> </td><td> </td><td> </td><td> </td></tr>';
$tarow=$tarow. '<tr><td>'. $CompanyName.'</td><td>'. date('D j M Y', strtotime($lastinvoicedate)).'</td><td>';
$tarow=$tarow. date('D j M Y', strtotime($lastrow['collectiondate'])).'</td><td style="text-align: right;">  &'. $globalprefrow['currencysymbol']; 

$temptwo=''; $tempdate=$lastrow['collectiondate']; 
$sqlcostage = "SELECT * FROM Orders WHERE CustomerID = '$CustomerID' 
AND status > '98' 
AND status < '108' 
AND collectiondate <= '$tempdate' ";
$sql_resultcost = mysql_query($sqlcostage,$conn_id)  or mysql_error(); 
while ($costrow = mysql_fetch_array($sql_resultcost)) { extract($costrow); $temptwo=$temptwo+$costrow['FreightCharge']; } 
$temptwo= number_format($temptwo, 2, '.', '');
$tarow=$tarow. $temptwo; 
$tempthree=$tempthree+$temptwo;

$tarow=$tarow. '</td></tr>';

if ($temptwo<>0.00)
{

// array("$clientids","$CustomerID");

$trow=$trow.$tarow;

}

// }

}

}




?><div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">

Requires Invoicing : <?php echo '&'.$globalprefrow['currencysymbol'].$tempthree; ?>

<table class="ord" ><tbody>
<tr>
<th scope="col">Client</th>
<th scope="col">Invoiced Until</th>
<th scope="col">Last Collected</th>
<th scope="col" style="text-align: right;" >Cost</th>
</tr>
<?php



// print_r($clientids);


echo $trow. '<tr><td> </td><td> </td><td> </td><td> </td></tr>
<tr><td> </td><td> </td><td> Total : </td><td> &'.$globalprefrow['currencysymbol'].$tempthree.'</td></tr>';

echo '</tbody></table>';
echo '</div></div><br /></body></html>';


mysql_close();
?>