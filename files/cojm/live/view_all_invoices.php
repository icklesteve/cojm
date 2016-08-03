<?php

/*
    COJM Courier Online Operations Management
	view_all_invoices.php - New Job Ajax Helper for Clients with Departments
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

$tempthree='';
$trow='';


include "C4uconnect.php";
if ($globalprefrow['forcehttps']>'0') {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }


include('changejob.php');


if (isset($_GET['viewtype'])) { $viewtype=trim($_GET['viewtype']); } else { $viewtype=''; }
if (isset($_POST['viewtype'])) { $viewtype=trim($_POST['viewtype']); } 

if (isset($_GET['clientview'])) { $clientview=trim($_GET['clientview']); } else { $clientview=''; }
if (isset($_POST['clientview'])) { $clientview=trim($_POST['clientview']); }

if (isset($_GET['viewselectdep'])) { $viewselectdep=trim($_GET['viewselectdep']); } else { $viewselectdep=''; }
if (isset($_POST['viewselectdep'])) { $viewselectdep=trim($_POST['viewselectdep']); } 


if (isset($_GET['showinactive'])) { $showinactive=trim($_GET['showinactive']); } else { $showinactive=''; }
if (isset($_POST['showinactive'])) { $showinactive=trim($_POST['showinactive']); } 


if (isset($_GET['clientid'])) { $clientid=trim($_GET['clientid']); } else { $clientid='all'; }
if (isset($_POST['clientid'])) { $clientid=trim($_POST['clientid']); } 



if ($clientid<>'all') {

$tempwaitingcheck = mysql_result(mysql_query("
SELECT isactiveclient FROM Clients WHERE CustomerID=$clientid  LIMIT 1
", $conn_id), 0);

if ($tempwaitingcheck<>'1') { $showinactive='1'; }

}

// $query = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient>0 ORDER BY CompanyName";







// $infotext=$infotext.'<br />'. $viewselectdep;

$invoicemenu = "1";
$toptext='';
$b='';

if (isset($_POST['collectyear'])) { $year=trim($_POST['collectyear']); } else { $year=''; }
if (isset($_POST['collectmonth'])) { $month=$_POST['collectmonth']; } else { $month=''; }
if (isset($_POST['collectday'])) { $day=$_POST['collectday']; } else { $day=''; }
$hour="23";
$minutes="59";
$collectionsuntildate = $year . "-" . $month . "-" . $day . " " . $hour . ":" . $minutes . ":59";


if (isset($_POST['deliveryear'])) {

$year=$_POST['deliveryear'];
$month=$_POST['delivermonth'];
$day=$_POST['deliverday'];
$hour="00";
$minutes="00";
$collectionsfromdate = $year . "-" . $month . "-" . $day . " " . $hour . ":" . $minutes . ":00";

}


$adminmenu = "0";




// %2F1

if (isset($_GET['from'])) { $tstart=trim($_GET['from']); } else { $tstart=''; }
if (isset($_POST['from'])) { $tstart=trim($_POST['from']); }

if (isset($_GET['to'])) { $end=trim($_GET['to']); } else { $end=''; }
if (isset($_POST['to'])) { $end=trim($_POST['to']); }


if (($tstart) and ($end)) {

$tstart = str_replace("%2F", ":", "$tstart", $count);
$tstart = str_replace("/", ":", "$tstart", $count);
$tstart = str_replace(",", ":", "$tstart", $count);
$temp_ar=explode(":",$tstart); 
$day=$temp_ar['0']; 
$month=$temp_ar['1']; 
$year=$temp_ar['2']; 
$hour= '00';
$minutes= '00';
$second='00';

// echo ' day   : '.$day;
// echo ' month : '.$month;
// echo ' year  : '.$year;
// echo ' hour : '.$hour;
// echo ' min : '.$minutes.'<br />';
// $sqlstart= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year));
$sqlstart= date("Y-m-d H:i:s", mktime(00, 00, 00, $month, $day, $year)); }

else { $sqlstart=''; $end=''; } 



if ($year) { $inputstart=$day.'/'.$month.'/'.$year; } else { $inputstart=''; }
// $infotext=$infotext. '<br />start : '.$sqlstart;

if ($end) {
$tend = str_replace("%2F", ":", "$end", $count);
$tend = str_replace("/", ":", "$tend", $count);
$tend = str_replace(",", ":", "$tend", $count);
$temp_ar=explode(":",$tend); 
$day=$temp_ar['0']; 
$month=$temp_ar['1']; 
$year=$temp_ar['2']; 
$second='59';
}

if ($year) { $inputend=$day.'/'.$month.'/'.$year;

$sqlend= date("Y-m-d H:i:s", mktime(23, 59, 59, $month, $day, $year));
 } else { $inputend=''; $sqlend=''; }

// echo ' day   : '.$day;
// echo ' month : '.$month;
// echo ' year  : '.$year;
// echo ' hour : '.$hour;
// echo ' min : '.$minutes.'<br />';
// $sqlstart= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year));

// $infotext=$infotext. '<br />end : '.$sqlend;
// if (($sqlstart) and (!$year) and ($clientid)) { $sqlend=''; }



if ($viewtype=='') {


$bview='';

$tablecost='0';
$todayDate = date("Y-m-d");// current date
//Add one day to today

$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "");

$dateamonthago = date('Y-m-d H:i:s', $dateOneMonthAdded);

   $sql = "SELECT * FROM invoicing 
   INNER JOIN Clients 
   ON invoicing.client = Clients.CustomerID 
   WHERE (`invoicing`.`paydate` =0 ) 
   AND (`invoicing`.`invdue` < '$dateamonthago' ) 
   ORDER BY `invoicing`.`invdue` ASC ";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 
	 
	 $num_rows = mysql_num_rows($sql_result);
	 if ($num_rows>'0') {



$a='<tr>
<th scope="col">Invoice Ref</th>
<th scope="col">Client</th>
<th scope="col" class="rh" >Net Amount</th>
<th scope="col">Date Sent</th>
<th scope="col">Due Date</th>
<th scope="col">Reminded</th>
<th scope="col">2nd Reminder</th>
<th scope="col">3rd Reminder</th>
<th style="width: 20%;" scope="col">Comments</th>
</tr>
<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>';

while ($row = mysql_fetch_array($sql_result)) { extract($row); 

$a=$a. '<tr><td>
<form action="view_all_invoices.php#" method="post"> 
<input type="hidden" name="viewtype" value="individualinvoice" >
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="page" value="" >
<input type="hidden" name="ref" value="'.$ref.'">
<input type="hidden" name="from" value="'. $inputstart.'">
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="clientid" value="'.$clientid.'" />
<button type="submit" >'.$ref.'</button></form>
</td><td>';

// echo $CompanyName;


$a=$a. ' <a href="new_cojm_client.php?clientid='.$row['CustomerID'].'">'.$CompanyName.'</a>';

$tempdep=$row['invoicedept'];

if ($tempdep) {

$dclientname = mysql_result(mysql_query("SELECT depname FROM clientdep WHERE depnumber='$tempdep' LIMIT 0,1"), '0');
$a=$a. ' (<a href="new_cojm_department.php?depid='.$tempdep.'">'.$dclientname.'</a>) ';
	
}




$a=$a. '</td><td class="rh">&'. $globalprefrow['currencysymbol']. number_format(($cost+$invvatcost), 2, '.', ',').'</td>
<td>'. date('D j M Y', strtotime($invdate1)) .'</td><td>'.date('D j M Y', strtotime($invdue)).'</td><td>'; 
 if ($chasedate>'2') { $a=$a. date('D j M Y', strtotime($chasedate));} 
 
 $a=$a. '</td><td>'; 
 if ($chasedate2>'2') { $a=$a. date('D j M Y', strtotime($chasedate2));} 
$a=$a. '</td><td>'; 
if ($chasedate3>'2') {$a=$a. date('D j M Y', strtotime($chasedate3));} 
 $a=$a. '</td><td>'.$row['invcomments'].'</td></tr>';

$tablecost=$tablecost+$cost+$invvatcost; } 

$invcount=$num_rows;
$costcount=$tablecost;


$tablecost= number_format($tablecost, 2, '.', ','); 


$bview=$bview.'<table class="acc" >
<tbody>
<caption> <h3>'.$num_rows.' Overdue Invoices (&'. $globalprefrow['currencysymbol']. $tablecost.')</h3></caption>
'. $a. '</tbody></table><div class="vpad line"></div>';


$toptext=$toptext.' '.$num_rows.' Overdue Invoices (&'. $globalprefrow['currencysymbol']. $tablecost.'). ';



} // ends check for overdue









$todayDate = date("Y-m-d");// current date
//Add one day to today
$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "-1 month");
$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "");

$dateamonthago = date('Y-m-d H:i:s', $dateOneMonthAdded);




$tablecost='0';









   $sql = "SELECT CompanyName, ref, invdate1, cost, invoicedept, invdue, CustomerID, invcomments FROM invoicing 
   INNER JOIN Clients 
   ON invoicing.client = Clients.CustomerID 
   WHERE (`invoicing`.`paydate` =0 ) 
   AND (`invoicing`.`invdue` > '$dateamonthago' ) 
   ORDER BY `invoicing`.`invdue` ASC ";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 


	 $num_rows = mysql_num_rows($sql_result);
	 if ($num_rows>'0') {


$b= '
<tr>
<th scope="col">Invoice Ref</th>
<th scope="col">Client</th>
<th scope="col" class="rh" >Net Amount</th>
<th scope="col">Date Sent</th>
<th scope="col">Due Date</th>
<th scope="col">Comments</th>
</tr>
';


$trow='';
$tempthree='';


while ($row = mysql_fetch_array($sql_result)) { extract($row); 




$b=$b. '<tr><td>

<form action="view_all_invoices.php#" method="post"> 
<input type="hidden" name="viewtype" value="individualinvoice" >
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="page" value="" >
<input type="hidden" name="ref" value="'.$ref.'">
<input type="hidden" name="from" value="'. $inputstart.'">
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="clientid" value="'.$clientid.'" />
<button type="submit" >'.$row['ref'].'</button></form>


</td><td><a href="new_cojm_client.php?clientid='.$CustomerID.'">'.$CompanyName.'</a>';



$tempdep=$row['invoicedept'];

if ($tempdep) {

$dclientname = mysql_result(mysql_query("SELECT depname FROM clientdep WHERE depnumber='$tempdep' LIMIT 0,1"), '0');
$b=$b. ' (<a href="new_cojm_department.php?depid='.$tempdep.'">'.$dclientname.'</a>) ';
	
}

// $tablecost= number_format($tablecost, 2, '.', ''); 

$b=$b. '</td>
<td class="rh">&'. $globalprefrow['currencysymbol'] .number_format(($cost), 2, '.', ','). '</td>
<td>'. date('D j M Y', strtotime($invdate1)).'</td><td>'. date('D j M Y', strtotime($invdue)). 
'</td><td>'.$row['invcomments'].'</td></tr>';
 $tablecost=$tablecost+$cost; }

$intimecost= number_format($tablecost, 2, '.', ',');

$bview=$bview. '<div class="vpad"></div>
<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<table  class="acc" >
<caption><h3>'.$num_rows.' within time limit (&'. $globalprefrow['currencysymbol']. ($intimecost).')</h3></caption>
<tbody>'.$b.'</tbody></table>
</div></div>
<div class=" vpad line"></div>';


$toptext=$toptext.' '.($num_rows+$invcount).' in total, (&'. $globalprefrow['currencysymbol']. number_format(($costcount+$tablecost), 2, '.', ',').'). ';

} // ends check for rows








// starts check for awaiting invoicing
$awcount='';
$sql = "SELECT CustomerID, CompanyName, lastinvoicedate FROM Clients ORDER BY lastinvoicedate";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 
while ($row = mysql_fetch_array($sql_result)) { extract($row);
$CustomerID = $row['CustomerID'];
$lastdate = "
SELECT collectiondate FROM Orders 
WHERE CustomerID=$CustomerID 
AND status < '108' 
AND status > '98' 
ORDER BY collectiondate DESC 
LIMIT 0 , 1";

$sql_result_last = mysql_query($lastdate,$conn_id) or die(mysql_error()); while ($lastrow = mysql_fetch_array($sql_result_last)) { extract($lastrow); 

$tarow='';


$tarow=$tarow. '<tr><td><a href="new_cojm_client.php?clientid='.$row['CustomerID'].'">'.$row['CompanyName'].'</a></td><td>';


if ($row['lastinvoicedate']<>'0000-00-00 00:00:00') {
$tarow=$tarow.date('D j M Y', strtotime($row['lastinvoicedate']));
}

$tarow=$tarow.'</td><td>';


$firstdate = mysql_result(mysql_query("SELECT collectiondate FROM Orders WHERE CustomerID=$CustomerID AND status < '108' AND status > '98' ORDER BY collectiondate ASC LIMIT 0 , 1"), '0');
$tarow=$tarow.date('D j M Y', strtotime($firstdate));


$tarow=$tarow.'</td><td>';




$tarow=$tarow. date('D j M Y', strtotime($lastrow['collectiondate'])).'</td><td class="rh">  &'. $globalprefrow['currencysymbol']; 




$temptwo=''; $tempdate=$lastrow['collectiondate']; 
$sqlcostage = "SELECT FreightCharge, vatcharge FROM Orders WHERE CustomerID = '$CustomerID' 
AND status > '98' 
AND status < '108' 
AND collectiondate <= '$tempdate' ";


$sql_resultcost = mysql_query($sqlcostage,$conn_id)  or mysql_error(); 
while ($costrow = mysql_fetch_array($sql_resultcost)) { extract($costrow); $temptwo=$temptwo+$costrow['FreightCharge']+$costrow['vatcharge']; $awcount++; }

$tempthree=$tempthree+$temptwo;
 
$temptwo= number_format($temptwo, 2, '.', ',');
$tarow=$tarow. $temptwo; 




$tarow=$tarow. '</td></tr>';

if ($temptwo<>'0.00')
{ $trow=$trow.$tarow;
}
}
}

$tempthree= number_format($tempthree, 2, '.', ',');



$bview=$bview. '
<div class="vpad"> </div>
<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<table class="acc" >
<caption>
<h3>'.$awcount.' Jobs Require Invoicing ('. '&'.$globalprefrow['currencysymbol'].$tempthree. ' Net ) </h3>
</caption>
<tbody>
<tr>
<th scope="col">Client</th>
<th scope="col">Last Invoiced</th>
<th scope="col">Invoice from</th>
<th scope="col">Last Collected</th>
<th scope="col" class="rh" title="Incl. VAT" >Net Amount</th>
</tr>'. $trow. '
<tr><td> </td><td> </td><td></td><td class="rh"> Total : </td><td class="rh"> &'.$globalprefrow['currencysymbol'].$tempthree.'</td></tr>
</tbody></table>
</div>';

$toptext=$toptext.' '.$awcount.' Jobs awaiting Invoicing (&'.$globalprefrow['currencysymbol'].$tempthree.' Net ) ';

} // ends page==''


// Platform 11: 1W51 (Rear) - 2P05 (Front)
// Platform 12: 2T13 (Rear) - 1W53 (Front)




if ($viewtype=='individualinvoice') {   $hasforms='1';   }



$adminmenu ="0";
$filename='view_all_invoices.php';

?><!doctype html>
<html lang="en"><head>
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<title>COJM : View Invoice by Date</title>
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
</head>
<body>';

 include "cojmmenu.php"; 





echo '<div class="Post">
<form action="view_all_invoices.php" method="get"> 
<div class="ui-state-highlight ui-corner-all p15" >

Invoices Sent From <input class="ui-state-highlight ui-corner-all pad" size="11" type="text" name="from" value="'. $inputstart.'" id="rangeBa" />			
To <input class="ui-state-highlight ui-corner-all pad" size="11" type="text" name="to" value="'. $inputend.'" id="rangeBb" />			
<input type="hidden" name="formbirthday" value="'. date("U").'">
';


// echo ' clientid '.$clientid.'<br />';


echo '
Client : <select id="combobox" class="ui-state-highlight" name="clientid">
<option value="">Select one...</option>
<option '; if ($clientid=="all") {echo ' SELECTED ';} echo ' value="all">All</option>';


if ($showinactive>'0') { 
$query = "SELECT CustomerID, CompanyName FROM Clients ORDER BY CompanyName";
} else {
$query = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient>0 ORDER BY CompanyName";
}
$result_id = mysql_query ($query, $conn_id);
while (list ($CustomerID, $CompanyName) = mysql_fetch_row ($result_id))
{
	$CustomerID = htmlspecialchars ($CustomerID);
	$CompanyName = htmlspecialchars ($CompanyName);
		print"<option ";
	if ($CustomerID == $clientid) {echo "SELECTED "; } ;
		print ("value=\"$CustomerID\">$CompanyName</option>\n");
}

echo '</select>	';


echo ' Show Inactive Clients? <input type="checkbox" name="showinactive" value="1" '; 
 if ($showinactive>0) { echo 'checked';} 
echo ' /> ';


$query = "SELECT depnumber, depname FROM clientdep WHERE associatedclient = '$clientid' ORDER BY depname"; 
$result_id = mysql_query ($query, $conn_id) or mysql_error();  

$sumtot=mysql_affected_rows();

// echo $sumtot.' Department(s) : '.$viewselectdep;	

if ($sumtot>'0') {

echo '<select class="ui-state-default ui-corner-left" name="viewselectdep" >
<option value="">All Departments</option>';
 while (list ($CustomerIDlist, $CompanyName) = mysql_fetch_row ($result_id)) { 
 
 $CustomerID = htmlspecialchars ($CustomerID); 
$CompanyName = htmlspecialchars($CompanyName); 
print'<option ';

if ($CustomerIDlist==$viewselectdep) { echo ' SELECTED '; }

echo 'value= "'.$CustomerIDlist.'" >'.$CompanyName.'</option>';} 

echo '</select> ';


} else { $viewselectdep=''; }  // ends end of check for sumtot







echo '
<select name="clientview" class="ui-state-highlight ui-corner-left">
<option  value="normal">Normal View</option>
<option ';

if ($clientview=='client') { 

echo ' SELECTED="SELECTED" ';

}

echo ' value="client">Copy / Paste </option>
</select>

	';





echo '
<button type="submit"> Search </button><br />
'.$toptext.'
<input type="hidden" name="viewtype" value="searchinvoice" />

</div></form><div class="vpad"> </div>'; 





if ($viewtype=='individualinvoice') {



if (isset($_POST['ref'])) { $ref=trim($_POST['ref']); }
if (!isset($ref)) { $ref=trim($_GET['ref']); }

if ($ref) {



$sql="SELECT * FROM invoicing
INNER JOIN Clients ON invoicing.client = Clients.CustomerID 
WHERE invoicing.ref='$ref'
LIMIT 0,1 ";


$sql_result=mysql_query($sql,$conn_id) or mysql_error();

// echo $sql;


 $sumtot=mysql_affected_rows();

 if ($sumtot>'0')  {




while ($row = mysql_fetch_array($sql_result)) {
     extract($row); }








echo '
<div class="ui-widget">	<div class="ui-state-default ui-corner-all p15" >
<fieldset><form action="view_all_invoices.php#" method="post"> <label for="txtName" class="fieldLabel"> <button type="submit" >'.$ref.'</button> </label>
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewtype" value="individualinvoice" >
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="page" value="" >
<input type="hidden" name="ref" value="'.$ref.'">
<input type="hidden" name="from" value="'. $inputstart.'">
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
</form>
</fieldset>';




echo '
<div class="vpad"></div>
<fieldset><label for="txtName" class="fieldLabel">Client </label>';
// . $CompanyName.'';

echo '<a href="new_cojm_client.php?clientid='.$clientid.'">'.$CompanyName.'</a>';


$depsql="SELECT * from clientdep WHERE depnumber='$invoicedept' LIMIT 0,1";
$dsql_result = mysql_query($depsql,$conn_id)  or mysql_error();

while ($drow = mysql_fetch_array($dsql_result)) { extract($drow); 
// echo ' ('.$drow['depname'].') ';

echo ' (<a href="new_cojm_department.php?depid='.$drow['depnumber'].'">'.$drow['depname'].'</a>) ';

 }
echo '</fieldset>';
 

 $tempvatcost= number_format($row['vatcharge'], 2, '.', ','); 
 

echo '

<div class="vpad"> </div>
<fieldset><label for="txtName" class="fieldLabel">Charge </label> &'.$globalprefrow['currencysymbol']. number_format($cost, 2, '.', ',').'</fieldset>
<div class="vpad"> </div>
<fieldset><label for="txtName" class="fieldLabel">VAT Element </label> &'.$globalprefrow['currencysymbol']. number_format($invvatcost, 2, '.', ',').'</fieldset>
<div class="vpad"> </div>
<fieldset><label for="txtName" class="fieldLabel">Invoice Total </label> &'.$globalprefrow['currencysymbol']. '<strong>'.number_format(($cost+$invvatcost), 2, '.', ',').'</strong></fieldset>
';
 
 
if (strtotime($invdate1)<>"") { echo '<div class="vpad"></div>
<fieldset><label for="txtName" class="fieldLabel"> Invoice Date </label>
'.date('D jS M Y', strtotime($invdate1)).'</fieldset>'; } 




$invoicedon= (strtotime($invdate1)); $paidon=(strtotime($paydate));
if ((strtotime($paydate))>"0") { $diff=$paidon-$invoicedon; } else { $diff=((date('U'))-$invoicedon);  }
// echo $diff;
 
 
 if ($diff>0) { echo '
 <div class="vpad"></div>
 <fieldset><label for="txtName" class="fieldLabel"> Days from Invoice Date</label>'.number_format(($diff/3600)/24).'</fieldset>'; }




if (strtotime($invdue)<>"") { echo '<div class="vpad"></div>
<fieldset><label for="txtName" class="fieldLabel"> Invoice Due by </label>
'.date('D jS M Y', strtotime($invdue)).'</fieldset>'; 



$invoicedon= (strtotime($invdue)); $paidon=(strtotime($paydate));
if ((strtotime($paydate))>"0") { $diff=$paidon-$invoicedon; } else { $diff=((date('U'))-$invoicedon);  }
// echo $diff;
 
 
 if ($diff>0) { echo '
 <div class="vpad"></div>
 <fieldset><label for="txtName" class="fieldLabel"> Days after Due Date</label>'.number_format(($diff/3600)/24).'</fieldset>'; }

 if ($diff<0) { echo '
 <div class="vpad"></div>
 <fieldset><label for="txtName" class="fieldLabel"> Days until Due Date</label>'.number_format(($diff/3600)/-24).'</fieldset>'; }








} 





if ((strtotime($paydate))>"0") {
 echo '<div class="vpad"></div>
<fieldset><label for="txtName" class="fieldLabel"> Payment Date </label>'.date(' D jS M Y ', strtotime($paydate)).'</fieldset> 

<div class="vpad"> </div>
<fieldset><label for="txtName" class="fieldLabel"> Payment Method </label>

 '; 
if ($cash>'0') { echo 'Cash';} if ($cheque>'0') { echo 'Cheque';}
if ($bacs>'0') { echo 'BACS Transfer';} if ($paypal>'0') { echo 'Paypal';} 
 echo ' </fieldset>';
}


 echo '
 
 
 <form action="view_all_invoices.php#" method="post"> 
 
 
 
<input type="hidden" name="viewtype" value="individualinvoice" >
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="page" value="editinvcomment" >
<input type="hidden" name="ref" value="'.$ref.'">
<input type="hidden" name="from" value="'. $inputstart.'">
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" >
 
 
 
 
 <fieldset><label for="invcomments" class="fieldLabel"> <button type="submit" > Edit Comments </button> </label><textarea 
 id="invcomments" placeholder="Invoice Comments" class="normal caps ui-state-default ui-corner-all " name="invcomments" 
style="width: 65%; outline: none;">'.$invcomments.'</textarea></fieldset></form>';



 
 echo '
</div></div>';


if (((strtotime($paydate))>"0") and  ((strtotime($chasedate))<"0") ) {} else { 
echo '<div class="vpad"> </div>
<div class="ui-widget">	<div class="ui-state-default ui-corner-all" style="padding: 0.5em; width:auto;">'; }
 
 

 
if ((strtotime($chasedate))<"0") {
if ((strtotime($paydate))<"0") { // unchased 1st time

echo '
<form action="view_all_invoices.php#" method="post"> 
<fieldset><label for="txtName" class="fieldLabel"> <button type="submit" > Add 1st Reminder </button> </label>

<input type="hidden" name="invchasetype" value="1" >
<input type="hidden" name="viewtype" value="individualinvoice" >
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="page" value="editinvchase" >
<input type="hidden" name="ref" value="'.$ref.'">
<input type="hidden" name="from" value="'. $inputstart.'">
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" >
<select class="ui-state-default ui-corner-left" name="chasedate" >
<option selected value="0" >Today</option>
<option value="24" >Tomorrow</option>
<option value="48" >Day After</option>
<option value="-24" >Yesterday</option>
</select>

</fieldset></form>
';

}
} // finishes check not already paid

else { // starts already chased


echo  '<form action="view_all_invoices.php#" method="post"> 
<fieldset><label for="txtName" class="fieldLabel">'; 

if ((strtotime($chasedate2))>"0") { echo ' 1st Reminder '; } else {

echo '<button type="submit" > Remove 1st Reminder </button> ';
}

echo '</label>

<input type="hidden" name="viewtype" value="individualinvoice" >
<input type="hidden" name="page" value="editinvchase" >
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="invchasetype" value="1" >
<input type="hidden" name="ref" value="'.$ref.'">
<input type="hidden" name="from" value="'. $inputstart.'">
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="chasedate" value="69">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
'. date('D jS M Y', strtotime($chasedate)). ' </fieldset></form>';

} // ends already chased


 
 
 
 
 
 
if ((strtotime($chasedate2))<"0") { // not already chased
if ((strtotime($paydate))<"0") { // unpaid
if ((strtotime($chasedate))>"0") { // has been chased 1st time


echo '
<form action="view_all_invoices.php#" method="post"> 
<div class="vpad"></div>
<fieldset><label for="txtName" class="fieldLabel">';


echo ' <button type="submit" > Add 2nd Reminder </button>';


echo ' </label>
<input type="hidden" name="viewtype" value="individualinvoice" >
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="page" value="editinvchase" >
<input type="hidden" name="invchasetype" value="2" >
<input type="hidden" name="ref" value="'.$ref.'">
<input type="hidden" name="from" value="'. $inputstart.'">
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
<select class="ui-state-default ui-corner-left" name="chasedate" >
<option selected value="0" >Today</option>
<option value="24" >Tomorrow</option>
<option value="48" >Day After</option>
<option value="-24" >Yesterday</option>
</select>
</fieldset>
</form>';
}
}
} 

else { echo '
<div class="vpad"></div>

 <form action="view_all_invoices.php#" method="post"> 
<fieldset><label for="txtName" class="fieldLabel"> ';

if ((strtotime($chasedate3))>"0") { echo ' 2nd Reminder '; } else {


echo '<button type="submit" > Remove 2nd Reminder </button>';

}

echo ' </label>
 '.date('D jS M Y', strtotime($chasedate2)).'
 <input type="hidden" name="viewtype" value="individualinvoice" >
 <input type="hidden" name="formbirthday" value="'. date("U") .'">
 <input type="hidden" name="page" value="editinvchase" >
<input type="hidden" name="invchasetype" value="2" >
<input type="hidden" name="ref" value="'.$ref.'">
<input type="hidden" name="from" value="'. $inputstart.'">
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="chasedate" value="69">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
</fieldset></form>';
  } 
 
 
 
 
 
 // chase 3rd time
 
if ((strtotime($chasedate3))<"0") { 
if ((strtotime($chasedate))>"0")  {
if ((strtotime($chasedate2))>"0") {


echo '<form action="view_all_invoices.php#" method="post"> 
<div class="vpad"></div>

<fieldset><label for="txtName" class="fieldLabel"> <button type="submit" > Add 3rd Reminder </button> </label>
<input type="hidden" name="viewtype" value="individualinvoice" >
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="page" value="editinvchase" >
<input type="hidden" name="invchasetype" value="3" >
<input type="hidden" name="ref" value="'.$ref.'">
<input type="hidden" name="from" value="'. $inputstart.'"> 
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
<select class="ui-state-default ui-corner-left" name="chasedate" >
<option selected value="0" >Today</option>
<option value="24" >Tomorrow</option>
<option value="48" >Day After</option>
<option value="-24" >Yesterday</option>
</select>
</fieldset>
</form>
';
 }}}
 else { 
 
 echo '<form action="view_all_invoices.php#" method="post">
<div class="vpad"></div>

 <fieldset><label for="txtName" class="fieldLabel"> <button type="submit" > Remove 3rd Reminder </button> </label>
 '.date('D jS M Y', strtotime($chasedate3)).'
 
 <input type="hidden" name="viewtype" value="individualinvoice" >
 <input type="hidden" name="formbirthday" value="'. date("U") .'">
 <input type="hidden" name="page" value="editinvchase" >
<input type="hidden" name="invchasetype" value="3" >
<input type="hidden" name="ref" value="'.$ref.'">
 <input type="hidden" name="from" value="'. $inputstart.'"> 
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="chasedate" value="69">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
</fieldset></form>';
 

 }
 
 
 
if (((strtotime($paydate))>"0") and  ((strtotime($chasedate))<"0") ) {} else { 
echo '</div></div>'; }
 
 

 
 
 
if ((strtotime($paydate))<"0") { // unpaid 
 
 
 echo '
 <div class="vpad"> </div>
 <div class="ui-widget">	<div class="ui-state-default ui-corner-all p15">
<form action="view_all_invoices.php#" method="post"> 
<input type="hidden" name="formbirthday" value="'. date("U").'">
<input type="hidden" name="page" value="markinvpaid" />
<input type="hidden" name="ref" value="'.$ref.'" />
<input type="hidden" name="viewtype" value="individualinvoice" />

<input type="hidden" name="from" value="'. $inputstart.'"> 
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />



<fieldset><label for="invoicedate" class="fieldLabel"> Payment Date </label> 
<input class="ui-state-default ui-corner-all caps" type="text" value="'. date('d-m-Y', strtotime('now')).'" 
id="invoicedate" size="12" name="invoicedate"></fieldset>
<div class="vpad"> </div>

<fieldset><label for="invoicedate" class="fieldLabel">
Method of Payment </label>

<select class="ui-state-default ui-corner-left" name="invmethod" >
<option selected value="" >Select Payment Method</option>
<option value="cash" >Cash</option>
<option value="cheque" >Cheque</option>
<option value="bacs" >BACS</option>
<option value="paypal" >Paypal</option>
</select>
</fieldset>
<div class="vpad "> </div>
<button type="submit"> Mark as Paid </button>
</form></div></div>
 '; 
 } // ends unpaid
 
 
$sql = "
SELECT * FROM Orders 
INNER JOIN Services 
INNER JOIN Cyclist 
INNER JOIN Clients 
INNER JOIN status 
ON Orders.ServiceID = Services.ServiceID 
AND Orders.CyclistID = Cyclist.CyclistID 
AND Orders.status = status.status 
AND Orders.CustomerID = Clients.CustomerID 
WHERE Orders.invoiceref = '$ref' 
ORDER BY `Orders`.`ShipDate` ASC";




// normal
// client
// clientprice

$sql_result = mysql_query($sql,$conn_id)  or mysql_error();
$num_rows = mysql_num_rows($sql_result);
$firstrun='1';
$today = date(" H:i A, D j M");

if ($num_rows>'0') {


$tablecost='';
$tabletotal='';
$temptrack='';
$tottimedif='';
$secmod='';



echo '<div class="vpad"></div><table class="acc" style="width:100%;"><tbody><tr><th scope="col">COJM ID</th>';




$i='1';



echo '<th scope="col">'.$globalprefrow['glob5'].'</th>
<th scope="col">Service</th>
<th scope="col">Cost ex VAT</th>
<th scope="col">From </th>
<th scope="col">To </th>
<th scope="col">Collection</th>
<th scope="col">Delivery</th>
</tr>';

while ($row = mysql_fetch_array($sql_result)) { extract($row);

echo '<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>
<tr>
<td><a href="order.php?id='. $ID.'">'. $ID.'</a></td>
<td>'. $cojmname.'</td>
<td>'. formatmoney($row["numberitems"]) .' x '. $Service.'</td>
<td>&'. $globalprefrow['currencysymbol'].$row["FreightCharge"].'</td>
<td>'. $fromfreeaddress;  
if (trim($CollectPC)) { echo ' <a target="_blank" href="http://maps.google.com/maps?q='. $row['CollectPC'].'">'. $row['CollectPC'].'</a>'; }
echo '</td><td>'. $tofreeaddress.' ';
if (trim($ShipPC)) {echo ' <a target="_blank" href="http://maps.google.com/maps?q='. $row['ShipPC'].'">'. $row['ShipPC'].'</a>'; }
echo '</td>
<td>'.date('H:i D jS M ', strtotime($row['collectiondate'])).'</td>
<td>'.date('H:i D jS M ', strtotime($row['ShipDate'])).'</td></tr>';

$tablecost = $tablecost + $row["FreightCharge"];
$tabletotal = $tabletotal + $numberitems;

$temptrack=$temptrack.'<input type="hidden" name="tr'.$i.'" value="'.$ID.'" />';

$i++;

$tottimec=strtotime($row['starttrackpause']);
$tottimed=strtotime($row['finishtrackpause']);
if (($tottimec>'1') AND ($tottimed>'1')) { $secmod=($tottimed-$tottimec); }
$tottimea=strtotime($row['collectiondate']); 
$tottimeb=strtotime($row['ShipDate']); 
$tottimedif=($tottimedif+$tottimeb-$tottimea-$secmod);


} 
 
echo '';
echo '</tbody></table>';





$lengthtext='';


$inputval = $tottimedif; // USER DEFINES NUMBER OF SECONDS FOR WORKING OUT | 3661 = 1HOUR 1MIN 1SEC 
$unitd ='86400';
$unith ='3600';        // Num of seconds in an Hour... 
$unitm ='60';            // Num of seconds in a min... 
$dd = intval($inputval / $unitd);       // days
$hh_remaining = ($inputval - ($dd * $unitd));
$hh = intval($hh_remaining / $unith);    // '/' given value by num sec in hour... output = HOURS 
$ss_remaining = ($hh_remaining - ($hh * $unith)); // '*' number of hours by seconds, then '-' from given value... output = REMAINING seconds 
$mm = intval($ss_remaining / $unitm);    // take remaining sec and devide by sec in a min... output = MINS 
$ss = ($ss_remaining - ($mm * $unitm));        // '*' number of mins by seconds, then '-' from remaining sec... output = REMAINING seconds. 
if ($dd=='1') {$lengthtext=$lengthtext. $dd . " day "; } if ($dd>'1' ) { $lengthtext=$lengthtext. $dd . " days "; }
if ($hh=='1') {$lengthtext=$lengthtext. $hh . " hr "; } if ($hh>'1') { $lengthtext=$lengthtext. $hh . " hrs "; }
if ($mm>'1' ) {$lengthtext=$lengthtext. $mm . " mins. "; } if ($mm=='1') {$lengthtext=$lengthtext. $mm . " min. "; }
// number_format($tablecost, 2, '.', '')
if ($dd) {} else { if ($mm) {   $lengthtext=$lengthtext. "(". number_format((($mm/'60')+$hh), 2, '.', ''). 'hrs)'; } }
// echo ($tottimedif/60).' minutes';











 if (($lengthtext) or ($tabletotal)) { 
 

echo '<div class="vpad"></div>
<div class="ui-widget">	<div class="ui-state-default ui-corner-all" style="padding: 0.5em; width:auto;">';

if ($tabletotal) {
echo '<fieldset><label for="txtName" class="fieldLabel"> Total Volume </label> '. $tabletotal.'</fieldset>'; 
}

if (trim($lengthtext)) { echo '<fieldset><label for="txtName" class="fieldLabel"> 
Time Taken </label>'.$lengthtext. ' from collection to delivery.</fieldset>';
}

 
 echo '</div></div>'; }
 
} // ends rum rows loop





 echo '
 <div class="vpad"> </div>
 <div class="ui-widget">	<div class="ui-state-error ui-corner-all" style="padding: 0.5em; width:auto;">
<fieldset><label for="invoicedate" class="fieldLabel">

 
 <form action="#" method="post" id="frm1"> 
<input type="hidden" name="formbirthday" value="'. date("U").'">
<input type="hidden" name="page" value="deleteinv" />
<input type="hidden" name="ref" value="'.$ref.'" />
<input type="hidden" name="from" value="'. $inputstart.'"> 
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />

<a href="javascript:void(0)" id="deleteinv"><button> Delete Invoice </button></a>
</form>
</label>';


if ((strtotime($paydate))>"0") {

echo '
<form action="#" method="post" id="frm2"> 
<input type="hidden" name="formbirthday" value="'. date("U").'">
<input type="hidden" name="page" value="invnotpaid" />
<input type="hidden" name="ref" value="'.$ref.'" />
<input type="hidden" name="from" value="'. $inputstart.'"> 
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
<input type="hidden" name="viewtype" value="individualinvoice" />

<a href="javascript:void(0)" id="invnotpaid"><button> Remove Payment </button></a>
</form>
';

}


echo '</fieldset></div></div>';
echo '<script type="text/javascript">
';

?>

    $('#deleteinv').bind('click', function(e) {
        e.preventDefault();
        $.Zebra_Dialog('<strong>Are you sure ?</strong><br />Invoice <?php echo $ref; ?> will be deleted. <br />All jobs will revert to completed status.', {
            'type':     'warning',
					'width' : '350',
            'title':    'Delete Invoice ?',
            'buttons':  [
                            {caption: 'Delete', callback: function() {
document.getElementById("frm1").submit();
}},
                            {caption: 'Do NOT Delete', callback: function() {}}
                        ]
        });
    });




    $('#invnotpaid').bind('click', function(e) {
        e.preventDefault();
        $.Zebra_Dialog('<strong>Are you sure ?</strong><br />Payment details for this invoice ref <?php echo $ref; ?><br />will be cancelled.', {
            'type':     'warning',
					'width' : '350',
            'title':    'Remove Payment Details ?',
            'buttons':  [
                            {caption: 'Remove payment', callback: function() {
document.getElementById("frm2").submit();
}},
                            {caption: 'Cancel', callback: function() {}}
                        ]
        });
    });










<?php

echo '

$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+15));
}); ';    ?>

	$(function() {
		var dates = $( "#invoicedate" ).datepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
            dateFormat: 'dd-mm-yy',
			changeMonth:false,
		  beforeShow: function(input, instance) { 
            $(input).datepicker('setDate',  new Date() );
        }
		});
	});
	
	<?php
	
echo '	
</script>';




} // ends check for valid invoice ref


} // ends check for ref



} // ends viewtype == individualinvoice



















// echo $viewtype;

if ($viewtype=='searchinvoice' ) {

// echo $clientid.$collectionsfromdate.$collectionsuntildate;

if (($clientid=='all') or ($clientid=='' )) {

$sql="SELECT * FROM invoicing
INNER JOIN Clients ON invoicing.client = Clients.CustomerID 
WHERE invdate1 >= '$sqlstart' 
AND invdate1 <= '$sqlend' 
ORDER BY `invoicing`.`invdate1` ASC"; 

} else {


// need to add check for client having department to the if below
if ($viewselectdep<>'') {

$sql="SELECT * FROM invoicing
INNER JOIN Clients ON invoicing.client = Clients.CustomerID 
WHERE invdate1 >= '$sqlstart' 
AND invdate1 <= '$sqlend'
AND CustomerID = '$clientid' 
AND invoicedept = '$viewselectdep'
ORDER BY `invoicing`.`invdate1` ASC";


} else {


$sql="SELECT * FROM invoicing
INNER JOIN Clients ON invoicing.client = Clients.CustomerID 
WHERE invdate1 >= '$sqlstart' 
AND invdate1 <= '$sqlend'
AND CustomerID = '$clientid' 
ORDER BY `invoicing`.`invdate1` ASC";

} // ends dep check 


}


if (!($sqlstart) AND (!($sqlend)) AND ($clientid)) { 

// echo 'no start or end but client'; 


if ($viewselectdep<>'') {

$sql="SELECT * FROM invoicing
INNER JOIN Clients ON invoicing.client = Clients.CustomerID 
AND invoicing.paydate = '0000-00-00 00:00:00'
AND CustomerID = '$clientid' 
AND invoicedept = '$viewselectdep'
ORDER BY `invoicing`.`invdate1` ASC";

} else {

$sql="SELECT * FROM invoicing
INNER JOIN Clients ON invoicing.client = Clients.CustomerID 
AND invoicing.paydate = '0000-00-00 00:00:00'
AND CustomerID = '$clientid' 
ORDER BY `invoicing`.`invdate1` ASC";

} // ends dep check 


if ($clientid=='all') {

$sql="SELECT * FROM invoicing
INNER JOIN Clients ON invoicing.client = Clients.CustomerID 
AND invoicing.paydate = '0000-00-00 00:00:00'
ORDER BY `invoicing`.`invdate1` ASC";

}

}


$sql_result=mysql_query($sql,$conn_id) or mysql_error();

// echo $sql;


 $sumtot=mysql_affected_rows();

 if ($sumtot>'0')  {


$today = date(" H:i A, D j M");



if ($clientview=='client') { echo '<br />';}


echo '<table class="acc 1426"';

if ($clientview<>'client') {

echo '
 style="width:100%;" '; }
 
 echo '><tbody>
<tr><th scope="col">Invoice Ref</th>
<th scope="col" class="rh">Net &'.$globalprefrow['currencysymbol'].'</th>
<th scope="col">Client</th>
<th scope="col">Invoice Date</th>
<th scope="col">Due Date</th>
<th scope="col">Days</th>
<th scope="col">Payment Date</th>
';


if ($clientview<>'client') { 

echo '
<th scope="col">Payment Method</th>
<th scope="col">Chase 1</th>
<th scope="col">Chase 2</th>
<th scope="col">Chase 3</th>

';

}
echo '<th scope="col">Comments</th> </tr>';



$b='';
$tablecost='';
echo $b;

// Loop through the data set and extract each row in to it's own variable set
while ($row = mysql_fetch_array($sql_result)) {
     extract($row);
$date5 = (strtotime($row['invdate1'])); 
$date2 = (strtotime($row['chasedate'])); 
$date3 = (strtotime($row['chasedate2'])); 
$date4 = (strtotime($row['chasedate3'])); 
$invoicedon= (strtotime($row['invdate1'])); 
$paidon=(strtotime($row['paydate']));
	
	
$a='<tr><td>';

if ($clientview<>'client') {

$a=$a.'
<form action="view_all_invoices.php" method="post"> 
<input type="hidden" name="viewtype" value="individualinvoice" >
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="page" value="" >
<input type="hidden" name="ref" value="'.$ref.'">
<input type="hidden" name="from" value="'. $inputstart.'">
<input type="hidden" name="to" value="'. $inputend.'">
<input type="hidden" name="clientid" value="'.$clientid.'" />
<input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
<button type="submit" >'.$ref.'</button></form>';

} else { 

$a=$a.$ref;

}


// $temptwo= number_format($temptwo, 2, '.', '');


$a=$a.'
</td><td class="rh"> '. '&'.$globalprefrow['currencysymbol']. number_format(($row["cost"]+$row["invvatcost"]), 2, '.', ',').'</td>
<td>';

if ($clientview<>'client') {
$a=$a.'<a href="new_cojm_client.php?clientid='.$clientid.'">'.$CompanyName.'</a>';
} else { $a=$a. $CompanyName.' '; }

$tempdep=$row['invoicedept'];
	 
	 if ($tempdep) {
	
	 $clientdepname = mysql_result(mysql_query("SELECT depname FROM clientdep WHERE depnumber='$tempdep' LIMIT 0,1"), '0');
	 
	 if ($clientview<>'client') {
	 
$a=$a. ' (<a href="new_cojm_department.php?depid='.$tempdep.'">'.$clientdepname.'</a>) ';

	 } else {
	 
$a=$a. ' ('.$clientdepname.') ';

}
}


$a=$a. '</td><td>';

if (strtotime($row['invdate1'])=="") { } else { 

if ($clientview<>'client') {

$a=$a. date('D j M Y', strtotime($row['invdate1']));

} else { 
$a=$a. date('l jS F Y', strtotime($row['invdate1']));


}

 } 
$a=$a.'</td>';



$a=$a.'<td>';
 
 if ((strtotime($row['invdue']))>"0") {
 
if ($clientview<>'client') {
$a=$a. date('D j M Y', strtotime($row['invdue'])); 
} else { 
$a=$a. date('l jS F Y', strtotime($row['invdue']));

}
} 
 $a=$a.'</td> <td> ';


 
  // days
 
if ((strtotime($row['paydate']))>"0") { $diff=$paidon-$invoicedon; } else { $diff=((date('U'))-$invoicedon);  }
 if ($diff>0) { $a=$a. number_format(($diff/3600)/24); }
$a=$a.'</td>';
 
 



$a=$a.'<td>';
 $date1 = (strtotime($row['paydate'])); 
 
 if ((strtotime($row['paydate']))>"0") {
 
if ($clientview<>'client') {
$a=$a. date('D j M Y', strtotime($row['paydate'])); 
} else { 
$a=$a. date('l jS F Y', strtotime($row['paydate']));

}
} 
 $a=$a.'</td> ';
 
 
 




 
 
 
if ($clientview<>'client') {
 
 
 $a=$a.'<td>';
if ($row['cash']>'0') { $a=$a. 'Cash';} if ($row['cheque']>'0') { $a=$a. 'Cheque';}
if ($row['bacs']>'0') { $a=$a. 'BACS Transfer';} if ($row['paypal']>'0') { $a=$a. 'Paypal';}
$a=$a.'</td>';






$a=$a.'<td>';


// $a=$a. $date2;

if ((strtotime($row['chasedate']))<"0") { // unchased 1st time
if ((strtotime($row['paydate']))<"0") { // unpaid

} // finishes check not already paid
} // finishes check not already chased 

else { // starts already chased


$a=$a. date('D j M Y', strtotime($row['chasedate'])); } // ends already chased



$a=$a.'</td><td>';

if ((strtotime($row['chasedate2']))<"0") { // not already chased
if ((strtotime($row['paydate']))<"0") { // unpaid
if ((strtotime($row['chasedate']))<"0") {} else { // has been chased 1st time
}}} 

else { $a=$a. date('D j M Y', strtotime($row['chasedate2'])).''; } 
 $a=$a.'</td><td>';


if ((strtotime($row['chasedate3']))<"0") { echo ""; 
if ((strtotime($row['chasedate']))<"0") {} else {
if ((strtotime($row['chasedate2']))<"0") {} else {

 }}}
 else { 
 
 $a=$a. date('D jS M Y', strtotime($row['chasedate3'])); 
 
 } 
 
 
  $a=$a.'</td>';
 
 
} // ends check for clientview

echo $a;


echo '<td>'.$row['invcomments'].'</td>';
 
echo '</tr>'.$b;
$tablecost = $tablecost + $row["cost"]+$row["invvatcost"];

// echo ' 1664 ' . $tablecost;

 }
 
 if ($clientview=='client') { 
 
 echo '<tr><td> Total </td><td class="rh"> &'. $globalprefrow['currencysymbol']. number_format($tablecost, 2, '.', ',').'</td><td colspan="6"></td></tr>';
 
 }
 
 echo '</tbody>
</table>';


if ($clientview=='client') { 

echo '<br /><p>Total : &'. $globalprefrow['currencysymbol']. number_format($tablecost, 2, '.', ',').'</p>' ;

	} else {

echo '<div class="vpad"> </div>
<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;"><p>
Total Net Cost within date range : &'. $globalprefrow['currencysymbol']. number_format($tablecost, 2, '.', ',').'
</p></div></div>';

}


} else { // ends number rows, no invoices found

if (($clientid) and ($sqlend)) {

echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"> 
				<p><strong> No invoices found </strong> . . .</p>
			</div></div>';
}
}
} // ends page = searchinvoice




if ($viewtype=='') { 


echo $bview;


} // ends page==''



 
 
 echo '<br /></div><br />
 
 <script type="text/javascript">	
$(document).ready(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
				  $("#rangeBa, #rangeBb").daterangepicker();  
				  $(function(){ $(".normal").autosize();	});  
			 });
			 
			 
function comboboxchanged() { }				 
</script>
';
 
include "footer.php";
  
  mysql_close(); 
 
 ?></body></html>