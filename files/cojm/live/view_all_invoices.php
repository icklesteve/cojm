<?php

/*
    COJM Courier Online Operations Management
	view_all_invoices.php - New Job Ajax Helper for Clients with Departments
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
$tempthree='';
$trow='';

include "C4uconnect.php";
if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

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

if (isset($_GET['orderby'])) { $orderby=trim($_GET['orderby']); } else { $orderby=''; }
if (isset($_POST['orderby'])) { $orderby=trim($_POST['orderby']); } 


if (isset($_GET['invoicesearchphrase'])) { $invoicesearchphrase=trim($_GET['invoicesearchphrase']); } else { $invoicesearchphrase=''; }
if (isset($_POST['invoicesearchphrase'])) { $orinvoicesearchphrasederby=trim($_POST['invoicesearchphrase']); } 


if ($clientid<>'all') {
    $sql = "SELECT isactiveclient FROM Clients WHERE CustomerID=?  LIMIT 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$clientid]);
    $tempwaitingcheck = $stmt->fetchColumn();
    
    if ($tempwaitingcheck<>'1') {
        $showinactive='1';
    }
}


$adminmenu = "0";
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
    $sqlstart= date("Y-m-d H:i:s", mktime(00, 00, 00, $month, $day, $year));
} else { $sqlstart=''; $end=''; } 



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

if ($year) {
    $inputend=$day.'/'.$month.'/'.$year;
    $sqlend= date("Y-m-d H:i:s", mktime(23, 59, 59, $month, $day, $year));
} else { $inputend=''; $sqlend=''; }




if ($viewtype=='') {

    $tablecost='0';
    $awcount=0;

    $sqlcostage = "SELECT SUM(FreightCharge + vatcharge) AS cost, count(*) AS number FROM Orders WHERE status > '98' AND status < '108' ";    
    $awaiting = $dbh->query($sqlcostage)->fetchAll();
    
    $toptext.=' '.$awaiting[0]['number'].' Jobs awaiting Invoicing 
    (&'.$globalprefrow['currencysymbol'].number_format($awaiting[0]['cost'], 2, '.', ',').' Net ) ';
}

if ($viewtype=='individualinvoice') { $hasforms='1'; } // adds a page timeout

$filename='view_all_invoices.php';

?><!doctype html>
<html lang="en"><head>
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<title>COJM : View Invoice by Date</title>
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
<script type="text/javascript" src="js/jquery.floatThead.js"></script>
</head>
<body>';
$adminmenu ="0";
include "cojmmenu.php";

echo '<div class="Post">
<div class="ui-state-highlight ui-corner-all p15" id="searchdiv" >
<form action="view_all_invoices.php" method="get"> 
Invoices ';


echo '
<select id="orderby" name="orderby" class="ui-state-highlight ui-corner-left">
<option ';
if ($orderby=='due') {
echo ' selected ';
}
echo ' value="due">Due</option> ';
echo ' <option ';
if ($orderby=='sent') {
    echo ' selected ';
}
echo ' value="sent">Sent</option> ';
echo ' <option ';
if ($orderby=='recon') {
    echo ' selected ';
}
echo ' value="recon">Reconciled</option> ';
echo '
</select>
';


echo '
From 
<input title="Leave Dates Blank for Unreconciled" class="ui-state-highlight ui-corner-all pad" size="11" type="text" name="from" value="'. $inputstart.'" id="rangeBa" />			
To 
<input title="Leave Dates Blank for Unreconciled" class="ui-state-highlight ui-corner-all pad" size="11" type="text" name="to" value="'. $inputend.'" id="rangeBb" />			
<input type="hidden" name="formbirthday" value="'. date("U").'">
Client : <select id="combobox" class="ui-state-highlight" name="clientid">
<option value="">Select one...</option>
<option ';

if ($clientid=="all") {echo ' SELECTED ';} echo ' value="all">All</option>';


if ($showinactive>'0') {
    $query = "SELECT CustomerID, CompanyName FROM Clients ORDER BY CompanyName";
} else {
    $query = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient>0 ORDER BY CompanyName";
}
    $data = $dbh->query($query)->fetchAll();
    foreach ($data as $clientrow ) {
        $CustomerID = htmlspecialchars ($clientrow['CustomerID']);
        $CompanyName = htmlspecialchars ($clientrow['CompanyName']);

	print"<option ";
	if ($CustomerID == $clientid) { echo "SELECTED "; }
    print ("value=\"$CustomerID\">$CompanyName</option>\n");
}
echo '</select>	';


echo ' Show Inactive Clients? <input type="checkbox" name="showinactive" value="1" '; 
 if ($showinactive>0) { echo 'checked';} 
echo ' /> ';

$query = "SELECT depnumber, depname FROM clientdep WHERE associatedclient = :clientid ORDER BY depname"; 
$cbstmt = $dbh->prepare($query);
$cbstmt->bindParam(':clientid', $clientid, PDO::PARAM_INT); 
$cbstmt->execute();
$data = $cbstmt->fetchAll();

if ($data) {
    echo '<select class="ui-state-default ui-corner-left" name="viewselectdep" >
    <option value="">All Departments</option>';
    foreach ($data as $deprow ) {  
        $CompanyName = htmlspecialchars($deprow['depname']);
        $CustomerIDlist = htmlspecialchars ($deprow['depnumber']);
        print'<option ';
        if ($CustomerIDlist==$viewselectdep) { echo ' SELECTED '; }
        echo 'value= "'.$CustomerIDlist.'" >'.$CompanyName.'</option>';
    }
    echo '</select> ';

} else {
    $viewselectdep='';
}  // ends end of check for sumtot



echo '
<select name="clientview" class="ui-state-highlight ui-corner-left">
<option  value="normal">Normal View</option>
<option ';

if ($clientview=='client') { echo ' SELECTED="SELECTED" '; }

echo ' value="client">Copy / Paste </option>
</select>
<input id="invoicesearchphrase" 
name="invoicesearchphrase" 
placeholder="Amount or Reference" 
class="caps ui-state-default ui-corner-all pad" 
title="Include decimal places for price search"
value="'.$invoicesearchphrase.'"/>

<button id="invoiceajaxsearch" type="submit"> Search </button><br />

<select name="viewtype" class="ui-state-highlight ui-corner-left">
<option value="searchinvoice" ';
if ($viewtype=='searchinvoice') {
    echo ' selected ';
}
echo '> Search Invoices</option>
<option value="statmnt" ';
if ($viewtype=='statmnt') {
    echo ' selected ';
}
echo '> Statement View </option>
<option value="individualinvoice" ';
if ($viewtype=='individualinvoice') {
    echo ' selected ';
}
echo '> Single Invoice </option>
</select>
</form>
<hr />
'.$toptext.'
</div>
';

if ($viewtype=='individualinvoice') {

    if (isset($_POST['ref'])) { $ref=trim($_POST['ref']); }
    if (!$ref) { $ref=trim($_GET['ref']); }
  
    $sql="SELECT * FROM invoicing
    INNER JOIN Clients ON invoicing.client = Clients.CustomerID 
    LEFT JOIN  clientdep ON clientdep.depnumber = invoicing.invoicedept
    WHERE invoicing.ref= ?
    LIMIT 0,1 ";

    $parameters = array($ref);
    $statement = $dbh->prepare($sql);
    $statement->execute($parameters);
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        echo '
        <div class="ui-state-default ui-corner-all p15" >
        <fieldset>
        <form action="view_all_invoices.php#" method="post">
        <label class="fieldLabel">
        <button type="submit" >'.$row['ref'].'</button> </label>
        <input type="hidden" name="clientid" value="'. $row['client'].'">
        <input type="hidden" name="viewtype" value="individualinvoice" >
        <input type="hidden" name="formbirthday" value="'. date("U") .'">
        <input type="hidden" name="page" value="" >
        <input type="hidden" name="ref" value="'.$row['ref'].'">
        <input type="hidden" name="from" value="'. $inputstart.'">
        <input type="hidden" name="to" value="'. $inputend.'">
        <input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
        </form> '.$row['ref'].'
        </fieldset>
        <div class="vpad"></div>
        <fieldset><label class="fieldLabel">Client </label>';
        // . $CompanyName.'';

        echo '<a href="new_cojm_client.php?clientid='.$row['client'].'">'.$row['CompanyName'].'</a>';



        if ($row['invoicedept']) {
            echo ' (<a href="new_cojm_department.php?depid='.$row['depnumber'].'">'.$row['depname'].'</a>) ';
        }
        echo '</fieldset>';
 

        $tempvatcost= number_format($row['vatcharge'], 2, '.', ','); 
 

        echo '

        <div class="vpad"> </div>
        <fieldset><label  class="fieldLabel">Charge </label> &'.$globalprefrow['currencysymbol']. number_format($row['cost'], 2, '.', ',').'</fieldset>
        <div class="vpad"> </div>
        <fieldset><label  class="fieldLabel">VAT Element </label> &'.$globalprefrow['currencysymbol']. number_format($row['invvatcost'], 2, '.', ',').'</fieldset>
        <div class="vpad"> </div>
        <fieldset>
        <label  class="fieldLabel">Invoice Total </label>
        &'.$globalprefrow['currencysymbol']. '<strong>'.number_format(($row['cost']+$row['invvatcost']), 2, '.', ',').'</strong>
        </fieldset>';
 
 
        if (strtotime($row['invdate1'])<>"") {
            echo '<div class="vpad"></div>
            <fieldset>
            <label class="fieldLabel"> Invoice Date </label>
            '.date('D jS M Y', strtotime($row['invdate1'])).'</fieldset>';
        }


        $invoicedon= (strtotime($row['invdate1']));
        $paidon=(strtotime($row['paydate']));
        
        if ((strtotime($row['paydate']))>0) {
            $diff=$paidon-$invoicedon;
        } else {
            $diff=(((date('U'))-$invoicedon));
        }
        // echo $diff;
        
        if ($diff>0) {
            echo '
            <div class="vpad"></div>
            <fieldset>
            <label class="fieldLabel"> Days from Invoice Date</label>
            '.((number_format(($diff/3600)/24))-1).'</fieldset>';
        }
        
        if (strtotime($row['invdue'])<>"") {
            echo '<div class="vpad"></div>
            <fieldset>
            <label class="fieldLabel"> Invoice Due by </label>
            '.date('D jS M Y', strtotime($row['invdue'])).'
            </fieldset>'; 



            $invoicedon= (strtotime($row['invdue']));
            $paidon=(strtotime($row['paydate']));
            if ((strtotime($row['paydate']))>"0") {
                $diff=$paidon-$invoicedon;
                } else {
                    $diff=((date('U'))-$invoicedon);
                }
            // echo $diff;
 
 
            if ($diff>0) {
                
                $daysoverdue=number_format((($diff/3600)/24));
                
                echo '
                <div class="vpad"></div>
                <fieldset>
                <label class="fieldLabel"> Days after Due Date</label>
                '.$daysoverdue.'</fieldset>';
                
                
                $partialyear=$daysoverdue/365;
                $irate=($globalprefrow['invoice7']/100);
                $interest=($row["cost"]+$row["invvatcost"])*(pow((1+(($irate)/365)),(365*$partialyear)));
                $interest=$interest-($row["cost"]+$row["invvatcost"]);
                
                echo '
                <div class="vpad"></div>
                <fieldset>
                <label class="fieldLabel"> Overdue Interest ( '.$globalprefrow['invoice7'].'% ) </label>
                &'.$globalprefrow['currencysymbol']. number_format(($interest), 2, '.', ',').'</fieldset>';
                
                
            }

            if ($diff<0) { echo '
                <div class="vpad"></div>
                <fieldset>
                <label class="fieldLabel"> Days until Due Date</label>
                '.number_format((($diff/3600)/-24)+1).'</fieldset>';
            }
        }
        
        if ((strtotime($row['paydate']))>"0") {
            echo '<div class="vpad"></div>
            <fieldset><label class="fieldLabel"> Reconciled </label>'.date(' D jS M Y ', strtotime($row['paydate'])).'</fieldset> ';


        }


        echo '
        <form action="view_all_invoices.php#" method="post"> 
 
        <input type="hidden" name="viewtype" value="individualinvoice" >
        <input type="hidden" name="formbirthday" value="'. date("U") .'">
        <input type="hidden" name="page" value="editinvcomment" >
        <input type="hidden" name="ref" value="'.$row['ref'].'">
        <input type="hidden" name="from" value="'. $inputstart.'">
        <input type="hidden" name="to" value="'. $inputend.'">
        <input type="hidden" name="clientid" value="'. $clientid.'">
        <input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" >
        
        <fieldset>
        <label class="fieldLabel"> <button type="submit" > Edit Comments </button> </label><textarea 
        id="invcomments" placeholder="Invoice Comments" class="normal caps ui-state-default ui-corner-all " name="invcomments" 
        style="width: 65%; outline: none;">'.$row['invcomments'].'</textarea></fieldset></form>';
        
        
        if ((strtotime($row['paydate']))>0) {
        
        
            echo ' 
            <fieldset><label class="fieldLabel">
            
            <button title="Using payment(s) for same day as invoice reconciled" id="previewpdfreceipt">Preview PDF Receipt</button> </label> 
            <button title="Using payment(s) for same day as invoice reconciled" id="createpdfreceipt">Create PDF Receipt</button>
            
            
            </fieldset>';

            echo '
            <form id="f1" name="f1" action="receipt.php" method="post"> 
            <input type="hidden" id="invpage" name="invpage" value="" >
            <input type="hidden" name="invref" value="'.$row['ref'].'">
            </form>
            ';
            
        }
        
        
        
        
        if (((strtotime($row['paydate']))>"0") and  ((strtotime($chasedate))<"0") ) {} else { 
            echo '<div class="vpad"> </div>
            <div class="ui-widget">	<div class="ui-state-default ui-corner-all" style="padding: 0.5em; width:auto;">';
        }
        
        
        if ((strtotime($row['chasedate']))<"0") {
            if ((strtotime($row['paydate']))<"0") { // unchased 1st time
        
                echo '
                <form action="view_all_invoices.php#" method="post"> 
                <fieldset><label class="fieldLabel"> <button type="submit" > Add 1st Reminder </button> </label>
        
                <input type="hidden" name="invchasetype" value="1" >
                <input type="hidden" name="viewtype" value="individualinvoice" >
                <input type="hidden" name="formbirthday" value="'. date("U") .'">
                <input type="hidden" name="page" value="editinvchase" >
                <input type="hidden" name="ref" value="'.$row['ref'].'">
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
        
                </fieldset>
                </form>';
        
            }
        } else { // finishes check not already paid     // starts already chased
        
        
            echo  '<form action="view_all_invoices.php#" method="post"> 
            <fieldset><label class="fieldLabel">'; 
        
            if ((strtotime($row['chasedate2']))>"0") {
                echo ' 1st Reminder ';
            } else {
        
                echo '<button type="submit" > Remove 1st Reminder </button> ';
            }
        
            echo '</label>
        
            <input type="hidden" name="viewtype" value="individualinvoice" >
            <input type="hidden" name="page" value="editinvchase" >
            <input type="hidden" name="formbirthday" value="'. date("U") .'">
            <input type="hidden" name="invchasetype" value="1" >
            <input type="hidden" name="ref" value="'.$row['ref'].'">
            <input type="hidden" name="from" value="'. $inputstart.'">
            <input type="hidden" name="to" value="'. $inputend.'">
            <input type="hidden" name="chasedate" value="69">
            <input type="hidden" name="clientid" value="'. $clientid.'">
            <input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
            '. date('D jS M Y', strtotime($row['chasedate'])). ' </fieldset></form>';
            
        } // ends already chased
        
        
        
        
        
        
        
        
        if ((strtotime($row['chasedate2']))<"0") { // not already chased
            if ((strtotime($row['paydate']))<"0") { // unpaid
                if ((strtotime($row['chasedate']))>"0") { // has been chased 1st time
        
        
                    echo '
                    <form action="view_all_invoices.php#" method="post"> 
                    <div class="vpad"></div>
                    <fieldset><label class="fieldLabel">';
                    
                    
                    echo ' <button type="submit" > Add 2nd Reminder </button>';
                    
                    
                    echo ' </label>
                    <input type="hidden" name="viewtype" value="individualinvoice" >
                    <input type="hidden" name="formbirthday" value="'. date("U") .'">
                    <input type="hidden" name="page" value="editinvchase" >
                    <input type="hidden" name="invchasetype" value="2" >
                    <input type="hidden" name="ref" value="'.$row['ref'].'">
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
        } else {
            echo '
            <div class="vpad"></div>
        
            <form action="view_all_invoices.php#" method="post"> 
            <fieldset><label class="fieldLabel"> ';
        
            if ((strtotime($row['chasedate3']))>"0") {
                echo ' 2nd Reminder ';
            } else {
                echo '<button type="submit" > Remove 2nd Reminder </button>';
        
            }   
        
            echo ' </label>
            '.date('D jS M Y', strtotime($row['chasedate2'])).'
            <input type="hidden" name="viewtype" value="individualinvoice" >
            <input type="hidden" name="formbirthday" value="'. date("U") .'">
            <input type="hidden" name="page" value="editinvchase" >
            <input type="hidden" name="invchasetype" value="2" >
            <input type="hidden" name="ref" value="'.$row['ref'].'">
            <input type="hidden" name="from" value="'. $inputstart.'">
            <input type="hidden" name="to" value="'. $inputend.'">
            <input type="hidden" name="chasedate" value="69">
            <input type="hidden" name="clientid" value="'. $clientid.'">
            <input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
            </fieldset></form>';
        }
        
        
        
        
        
        // chase 3rd time
        
        if ((strtotime($row['chasedate3']))<"0") { 
            if ((strtotime($row['chasedate']))>"0")  {
                if ((strtotime($row['chasedate2']))>"0") {
        
        
                    echo '<form action="view_all_invoices.php#" method="post"> 
                    <div class="vpad"></div>
                    
                    <fieldset><label class="fieldLabel"> <button type="submit" > Add 3rd Reminder </button> </label>
                    <input type="hidden" name="viewtype" value="individualinvoice" >
                    <input type="hidden" name="formbirthday" value="'. date("U") .'">
                    <input type="hidden" name="page" value="editinvchase" >
                    <input type="hidden" name="invchasetype" value="3" >
                    <input type="hidden" name="ref" value="'.$row['ref'].'">
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
                }
            }
        }
        else { 
        
            echo '<form action="view_all_invoices.php#" method="post">
            <div class="vpad"></div>
            
            <fieldset><label class="fieldLabel"> <button type="submit" > Remove 3rd Reminder </button> </label>
            '.date('D jS M Y', strtotime($row['chasedate3'])).'
            
            <input type="hidden" name="viewtype" value="individualinvoice" >
            <input type="hidden" name="formbirthday" value="'. date("U") .'">
            <input type="hidden" name="page" value="editinvchase" >
            <input type="hidden" name="invchasetype" value="3" >
            <input type="hidden" name="ref" value="'.$row['ref'].'">
            <input type="hidden" name="from" value="'. $inputstart.'"> 
            <input type="hidden" name="to" value="'. $inputend.'">
            <input type="hidden" name="chasedate" value="69">
            <input type="hidden" name="clientid" value="'. $clientid.'">
            <input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
            </fieldset></form>';
        }
        
        if (((strtotime($row['paydate']))>"0") and  ((strtotime($row['chasedate']))<"0") ) {} else { 
            echo '</div></div>';
        }
        
        if ((strtotime($row['paydate']))<"0") { // unpaid 
        
            echo '
            <div class="vpad"> </div>
            <div class="ui-state-default ui-corner-all p15">
            <form action="view_all_invoices.php#" method="post"> 
            <input type="hidden" name="formbirthday" value="'. date("U").'">
            <input type="hidden" name="page" value="markinvpaid" />
            <input type="hidden" name="ref" value="'.$row['ref'].'" />
            <input type="hidden" name="viewtype" value="individualinvoice" />
            
            <input type="hidden" name="from" value="'. $inputstart.'"> 
            <input type="hidden" name="to" value="'. $inputend.'">
            <input type="hidden" name="clientid" value="'. $clientid.'">
            <input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
            
            
            <fieldset><label class="fieldLabel">
            <button type="submit"> Mark as Reconciled </button>
            </label> 
            <input class="ui-state-default ui-corner-all caps" type="text" value="'. date('d-m-Y', strtotime('now')).'" 
            id="invoicedate" size="12" name="invoicedate"></fieldset>


            </form></div>
            '; 
        }
        

        
        echo '
        <div id="orderdetails"> </div>
        <div class="vpad"> </div>
        <div class="ui-state-error ui-corner-all" style="padding: 0.5em; width:auto;"> ';

        
        
        if ((strtotime($row['paydate']))>"0") {
            echo '
            <form action="#" method="post" id="frm2"> 
            <fieldset><label class="fieldLabel">
            <input type="hidden" name="formbirthday" value="'. date("U").'">
            <input type="hidden" name="page" value="invnotpaid" />
            <input type="hidden" name="ref" value="'.$row['ref'].'" />
            <input type="hidden" name="from" value="'. $inputstart.'"> 
            <input type="hidden" name="to" value="'. $inputend.'">
            <input type="hidden" name="clientid" value="'. $clientid.'">
            <input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
            <input type="hidden" name="viewtype" value="individualinvoice" />
            <button id="invnotpaid"> Remove Reconciliation </button>
            </label>
            </fieldset>
            </form>
            '; 
        } else {  
          echo '  
        <form action="#" method="post" id="frm1"> 
        <fieldset><label class="fieldLabel">
        <input type="hidden" name="formbirthday" value="'. date("U").'">
        <input type="hidden" name="page" value="deleteinv" />
        <input type="hidden" name="ref" value="'.$row['ref'].'" />
        <input type="hidden" name="from" value="'. $inputstart.'"> 
        <input type="hidden" name="to" value="'. $inputend.'">
        <input type="hidden" name="clientid" value="'. $clientid.'">
        <input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
        
        <button id="deleteinv"> Delete Invoice </button>
        </label>
        </fieldset>
        </form>';  
        }
        
        
        echo '</div>'; // error box
        echo '</div>'; // invoice div
        
        echo '<script type="text/javascript"> 
        $(document).ready(function(){
            $( "#orderdetails" ).load( "ajax_lookup.php", { lookuppage: "invoiceorderlist", invoiceref: "'.$row['ref'].'" }, function() {
                $("#toploader").fadeOut();
            });
        });
        </script>';

    } // ends check for valid invoice ref
    
} // ends viewtype == individualinvoice
else if (($viewtype=='searchinvoice') or ($viewtype=='')) {
    
    $conditions = array();
    $parameters = array();
    $where = "";
    
    if ($orderby=='sent') {
        $orderbydb='invdate1';
    } else if ($orderby=='recon') {
        $orderbydb='paydate';
    } else {
        $orderbydb='invdue';    
    }

    
    if (($sqlstart) and ($sqlend)) {
        $conditions[] = " " . $orderbydb . " >= :sqlstart ";
        $parameters[":sqlstart"] = $sqlstart;
    
        $conditions[] = " " . $orderbydb . " <= :sqlend ";
        $parameters[":sqlend"] = $sqlend;
    }
    else {
        $conditions[] = " invoicing.paydate = :paydate ";
        $parameters[":paydate"] = '0000-00-00 00:00:00';
    }
        
    if ($invoicesearchphrase) {
        $conditions[] = " ( invoicing.ref LIKE :testrefa OR invoicing.cost LIKE :testrefb OR invoicing.invcomments LIKE :testrefc ) ";
        $parameters[":testrefa"] = "%".$invoicesearchphrase."%";
        $parameters[":testrefb"] = "%".$invoicesearchphrase."%";
        $parameters[":testrefc"] = "%".$invoicesearchphrase."%";    
    }



    // echo $clientid.$collectionsfromdate.$collectionsuntildate;

    if (($clientid=='all') or ($clientid=='' )) {

    }
    else {

        echo '<script>
        $(document).ready(function(){
            $( "#paymentstats" ).load( "ajax_lookup.php", { view: "client", clientid: "'.$clientid.'", lookuppage: "paymentstuff" }, function() {
                // alert( "Load was performed." );
            });
        });
        </script>';
    }
    
    
    if ($clientid<>'all') {
        $conditions[] = " CustomerID = :clientid ";
        $parameters[":clientid"] = $clientid;
    }
    
    
    if ($viewselectdep<>'') {
        $conditions[] = " invoicedept = :invoicedept ";
        $parameters[":invoicedept"] = $viewselectdep;
    }
    
    
    if (count($conditions) > 0) {
        $where = implode(' AND ', $conditions);
    }

    // check if $where is empty string or not
    $query = "SELECT * FROM invoicing
        INNER JOIN Clients ON invoicing.client = Clients.CustomerID 
        LEFT JOIN  clientdep ON clientdep.depnumber = invoicing.invoicedept
    " . ($where != "" ? " WHERE $where" : "");
        
        
    if ($orderby=='sent') {
        $query.= " ORDER BY `invoicing`.`invdate1` ASC ";
    }  else if ($orderby=='recon') {
        $query.= " ORDER BY `invoicing`.`paydate` ASC ";
    } else {
        $query.= " ORDER BY `invoicing`.`invdue` ASC ";
    }
    
    
    
    // echo $query;
    
    

    try {
        if (empty($parameters)) {
            $result = $dbh->query($query);
        }
        else {
            $statement = $dbh->prepare($query);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $result = $statement->fetchAll();
        }
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    
    
   
    if ($result) {
        
        $tablecost='';
        $numrows=0;
        $overduecount=0;
        $duecount=0;
        $reconcilecolumn=0;
        
        if ($clientview=='client') {
            echo '<br />';
        }
        
        $a= '<table class="acc 1426" id="expenseview" ';

        if ($clientview<>'client') {
            $a.= 'style="width:100%;" ';
        }
 
        $a.= '><thead>
        <tr><th scope="col">Invoice Ref </th>
        <th scope="col" class="rh">Net &'.$globalprefrow['currencysymbol'].' </th>
        <th scope="col">Client </th>
        <th scope="col">Invoice Date </th>
        <th scope="col">Sent Days </th>
        <th scope="col">Due Date </th>
        <th scope="col">Due Days </th>
        <th scope="col" class="reconcilecolumn" >Reconciled </th>';


        if ($clientview<>'client') {
            $a.= '
            <th scope="col"> </th>
            <th scope="col">Chase 1</th>
            <th scope="col">Chase 2</th>
            <th scope="col">Chase 3</th>';
        }
        
        $a.= '<th scope="col">Comments</th> </tr> </thead> <tbody>';

        foreach ($result as $row ) {
            $date5 = (strtotime($row['invdate1'])); 
            $date2 = (strtotime($row['chasedate'])); 
            $date3 = (strtotime($row['chasedate2'])); 
            $date4 = (strtotime($row['chasedate3'])); 
            $invoicedon= (strtotime($row['invdate1'])); 
            $paidon=(strtotime($row['paydate'])); // now reconciled date

            $a.='<tr id="tr'.$row['ref'].'"><td>';

            
            if ($row['paydate']=='0000-00-00 00:00:00'){
                $duecount++;
                $duemoney=$duemoney+$row["cost"]+$row["invvatcost"];
            }
            

            if (($row['invdue'] < date("Y-m-d 00:00:00")) and ($row['paydate']=='0000-00-00 00:00:00')) {
                $overduecount++;
                $overduemoney=$overduemoney+$row["cost"]+$row["invvatcost"];
                
            // $a.= ' overdue ';    
            }
            
            if ($clientview<>'client') {

                $a.= '
                <form action="view_all_invoices.php" method="post"> 
                <input type="hidden" name="viewtype" value="individualinvoice" >
                <input type="hidden" name="formbirthday" value="'. date("U") .'">
                <input type="hidden" name="page" value="" >
                <input type="hidden" name="ref" value="'.$row['ref'].'">
                <input type="hidden" name="from" value="'. $inputstart.'">
                <input type="hidden" name="to" value="'. $inputend.'">
                <input type="hidden" name="clientid" value="'.$clientid.'" />
                <input type="hidden" name="viewselectdep" value="'.$viewselectdep.'" />
                <button type="submit" >'.$row['ref'].'</button></form>
                
                
                 <button id="invoicedetails'.$row['ref'].'" class="invoicedetails "> ▼ </button>
                <button id="hideinvoicedetails'.$row['ref'].'" class="hideinvoicedetails hideuntilneeded"> ▲ </button>
                
                
                ';
            } else {
                $a.=$row['ref'];
            }




            $a.='</td>
            <td class="rh"> '. '&'.$globalprefrow['currencysymbol']. number_format(($row["cost"]+$row["invvatcost"]), 2, '.', ',').'</td>
            <td>';

            if ($clientview<>'client') {
                $a.='<a href="new_cojm_client.php?clientid='.$clientid.'">'.$row['CompanyName'].'</a>';
            } else {
                $a.= $row['CompanyName'].' ';
            }


            if ($row['invoicedept']) {
                if ($clientview<>'client') {
                    $a.=' (<a href="new_cojm_department.php?depid='.$row['invoicedept'].'">'.$row['depname'].'</a>) ';
                } else {
                    $a.=' ('.$row['depname'].') ';
                }
            }
     
            
            $a.='</td><td>';
            if (strtotime($row['invdate1'])=="") { } else {
                if ($clientview<>'client') {
                    $a.= date('D j M Y', strtotime($row['invdate1']));
                } else {
                    $a.= date('l jS F Y', strtotime($row['invdate1']));
                }
            }
            
            $a.='</td> ';
            

            if ((strtotime($row['paydate']))>"0") {
                $a.=' <td title=" From Sent until Reconciled ">';
                $diff=$paidon-$invoicedon;
                $days=(number_format(($diff/3600)/24));
                if ($days>0) {
                    $a.= $days;
                }
                echo ' </td> ';
            } else {
                $a.=' <td title="Since Sent"> ';
                $diff=((date('U'))-$invoicedon);
                $days=(number_format(($diff/3600)/24))-1;
                if ($days>0) {
                    $a.= $days;
                }
                echo '</td>';
            }
                        
            
            
            $a.=' <td>';
            
            if ((strtotime($row['invdue']))>"0") {
                if ($clientview<>'client') {
                    $a=$a. date('D j M Y', strtotime($row['invdue']));
                } else {
                    $a=$a. date('l jS F Y', strtotime($row['invdue']));
                }
            }
            $a.='</td> ';
            
            $stinvdue=strtotime($row['invdue']);

            if ((strtotime($row['paydate']))>"0") {
                $a.=' <td title="Paid vs Due Date"> ';
                $diff=$paidon-$stinvdue;
                $days=(number_format(($diff/3600)/24));
                $interest=0;
            } else {
                $a.=' <td title="Days Outstanding"> ';
                $diff=((date('U'))-$stinvdue);
                $days=((number_format(($diff/3600)/24))-1);
                
                
                
                $partialyear=$days/365;
                $irate=($globalprefrow['invoice7']/100);
                $interest=($row["cost"]+$row["invvatcost"])*(pow((1+(($irate)/365)),(365*$partialyear)));
                $interest=$interest-($row["cost"]+$row["invvatcost"]);
                
            }

            $a.= ' <span ';
            if ($days<1) {
                $a.= ' style="color:green;"';
            } else {
                $a.= ' style="color:red;"';                
            }
            
            if ($days=='-0') { $days=0; }
            
            
            $a.= '>';            
            $a.= $days;
            $a.=' </span> ';    
      
            $a.=' </td> ';
            
            
            
            
            
            
            
            
            
 
 

            $a.='<td class="reconcilecolumn" >';
            $date1 = (strtotime($row['paydate']));
            if ((strtotime($row['paydate']))>"0") {
                $reconcilecolumn++;
                if ($clientview<>'client') {
                    $a.= date('D j M Y', strtotime($row['paydate']));
                } else {
                    $a.= date('l jS F Y', strtotime($row['paydate']));
                }
            }
            
            $a=$a.'</td> ';
 
 
            if ($clientview<>'client') {
                $a=$a.'<td>';
                $a=$a.'</td>';
                $a=$a.'<td>';
                // $a=$a. $date2;
                if ((strtotime($row['chasedate']))<"0") {} else {                
                    $a=$a. date('D j M Y', strtotime($row['chasedate']));
                } // ends already chased
                
                $a=$a.'</td><td>';

                if ((strtotime($row['chasedate2']))<"0") {} else {
                    $a=$a. date('D j M Y', strtotime($row['chasedate2'])).'';
                }
                $a=$a.'</td><td>';
            
                if ((strtotime($row['chasedate3']))<"0") {} else {
                    $a.= date('D jS M Y', strtotime($row['chasedate3']));
                }
                $a.= '</td>';
            } // ends check for clientview
    
            // echo $a;
            $a.= '<td>'.$row['invcomments'].' ';
            

            if ($interest>0) {
                $a.='Interest &'.$globalprefrow['currencysymbol']. number_format(($interest), 2, '.', ',');
            }
            
            $a.= '</td>';
            $a.= '</tr>';
            $tablecost = $tablecost + $row["cost"]+$row["invvatcost"];
            // echo ' 1664 ' . $tablecost;
            $numrows++;
        }
 
        // echo $a;
        
        
        $a.=' </tbody> <tfoot>  ';
        
        

        if ($clientview=='client') {
            $a.= '<tr><td> Total </td><td class="rh"> &'. $globalprefrow['currencysymbol']. number_format($tablecost, 2, '.', ',').'</td><td colspan="6"></td></tr>';
        }
        
        else {
            
            $a.= '<tr><td> Total </td><td class="rh"> &'. $globalprefrow['currencysymbol']. number_format($tablecost, 2, '.', ',').'</td><td colspan="7"></td></tr>';            
        }
        
        
        
        $a.= '</tfoot> </table>';
    
    
    

    
    
    
        if ($clientview=='client') {
            echo '<br /><p>Total : &'. $globalprefrow['currencysymbol']. number_format($tablecost, 2, '.', ',').'</p>' ;
        } else {
            
            
            
            if ($overduecount) {
                echo '<div class="ui-state-highlight ui-corner-all clearfix undersearch" >
                <h3> '.$overduecount.' Invoice';
                if ($overduecount<>1) { echo 's'; } 
                echo ' Overdue </h3>
                <p title="Incl. VAT">Total Net Cost : &'. $globalprefrow['currencysymbol']. number_format($overduemoney, 2, '.', ',').'
                </p>
                </div>
                ';
            }
            
            if ($duecount) {
                echo '<div class="ui-state-highlight ui-corner-all clearfix undersearch">
                <h3> '.$duecount.' Invoice';
                if ($duecount<>1) { echo 's'; } 
                echo ' Due </h3>
                <p title="Incl. VAT">Total Net Cost : &'. $globalprefrow['currencysymbol']. number_format($duemoney, 2, '.', ',').'
                </p>
                </div>
                ';
            }            
            
            
            if ($viewtype=='searchinvoice') {
            
                echo ' 
                <div class="ui-state-highlight ui-corner-all clearfix undersearch" style="">
                <h3> '.$numrows.' Invoice';
                if ($numrows<>1) { echo 's'; } 
                echo ' </h3>
    
                <p title="Incl. VAT">Total Net Cost : &'. $globalprefrow['currencysymbol']. number_format($tablecost, 2, '.', ',').'
                </p>
                </div> ';
           
            }
            
            
            
            if ((!$tstart) and (!$end)) {
            
            echo ' 
            <div class="ui-state-highlight ui-corner-all clearfix undersearch" style="">
            <h3> No Dates Selected </h3>
 
            <p> Displaying unreconciled Invoices </p>
            </div>
            ';
                        
            
            
            
            }
            
            
            
            
        }
        
        
        
        echo $a;
        
        if (($reconcilecolumn==0) and ($clientview<>'client')) {
            echo '
            <script>
            $(".reconcilecolumn").hide();
            </script>
            ';
        }        
        
        
        
        
    } else { // ends number rows, no invoices found

        if (($clientid) and ($sqlend)) {

            echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"> 
			<p><strong> No invoices found </strong> . . .</p>
			</div></div>';
        }
    }
} // ends page = searchinvoice
else if ($viewtype=='statmnt') {
    
    $tablerow = array();
    $tablecost='';
    $numrows=0;
    $overduecount=0;
    $duecount=0;
    $a.= '';    
    
    $conditions = array();
    $parameters = array();
    $where = "";
    
    if (($sqlstart) and ($sqlend)) {
        $conditions[] = " invdate1 >= :sqlstart ";
        $parameters[":sqlstart"] = $sqlstart;
    
        $conditions[] = " invdate1 <= :sqlend ";
        $parameters[":sqlend"] = $sqlend;
    }
    
    if ($clientid<>'all') {
        $conditions[] = " CustomerID = :clientid ";
        $parameters[":clientid"] = $clientid;
    }
    
    
    if (count($conditions) > 0) {
        $where = implode(' AND ', $conditions);
    }

    $query = "SELECT * FROM invoicing
        INNER JOIN Clients ON invoicing.client = Clients.CustomerID 
        LEFT JOIN  clientdep ON clientdep.depnumber = invoicing.invoicedept
    " . ($where != "" ? " WHERE $where" : "");

    try {
        if (empty($parameters)) {
            $result = $dbh->query($query);
        }
        else {
            $statement = $dbh->prepare($query);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $result = $statement->fetchAll();
        }
    }
    catch(Exception $ex) {
        echo $ex->getMessage();
    }
    
    $numinvoice=0;
   
    if ($result) {
        foreach ($result as $row ) {
            
            if ($row['paydate'] == '0000-00-00 00:00:00'){
                $notpaid=1;
            } else {
                $notpaid=0;
            }
            
            
            $tablerow[] = array(
            "date"=>(strtotime($row['invdate1'])),
            "ref"=>$row['ref'],
            "amount"=>($row["cost"]+$row["invvatcost"]),
            "isinvoice"=>1,
            "client"=>$clientid,
            "CompanyName"=>($row['CompanyName']),
            "depname"=>($row['depname']),
            "notpaid"=>$notpaid,
            "comments"=>($row['invcomments'])
            );

            $invoicecost+=$row["cost"]+$row["invvatcost"];
            $numinvoice++;
        }
 


        
        echo ' 
        <div class="ui-state-highlight ui-corner-all clearfix undersearch" >
        <h3> '.$numinvoice.' Invoice';
        if ($numinvoice<>1) { echo 's'; } 
        echo ' </h3>

        <p title="Incl. VAT">Total Net Cost : &'. $globalprefrow['currencysymbol']. number_format($invoicecost, 2, '.', ',').'
        </p>
        </div>
        ';
        
    } else { // ends number rows, no invoices found

        echo ' No Invoices for selected dates. ';
    }


    $conditions = array();
    $parameters = array();
    $where = "";
    

    if ($sqlstart) {
        $conditions[] = " paymentdate >= :sqlstart ";
        $parameters[":sqlstart"] = $sqlstart;
    }
    
    if ($sqlend) {
        $conditions[] = " paymentdate <= :sqlend ";
        $parameters[":sqlend"] = $sqlend;
    }
    
    if ($clientid<>'all') {
        $conditions[] = " CustomerID = :clientid ";
        $parameters[":clientid"] = $clientid;
    }
    
    
    
    if (count($conditions) > 0) {
        $where = implode(' AND ', $conditions);
    }

    // check if $where is empty string or not
    $query = " SELECT paymentid, paymentdate, paymentamount, paymentclient, paymenttype, paymenttypename, paymentcomment, paymentedited, paymentcreated, CompanyName FROM cojm_payments 
        left JOIN cojm_paymenttype ON cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
        left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID
    " . ($where != "" ? " WHERE $where" : "");

    try {
        if (empty($parameters)) {
            $result = $dbh->query($query);
        }
        else {
            $statement = $dbh->prepare($query);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $result = $statement->fetchAll();
        }
    }
    catch(Exception $ex) {
        echo $ex->getMessage();
    }
    
    
    
        $numpayments=0;
        $paymentcost=0;
   
    if ($result) {
        foreach ($result as $row ) {
            $tablerow[] = array(
            "date"=>(strtotime($row['paymentdate'])),
            "ref"=>$row['paymentid'],
            "amount"=>$row["paymentamount"],
            "isinvoice"=>0,
            "client"=>$clientid,
            "paymenttypename"=>($row['paymenttypename']),
            "CompanyName"=>($row['CompanyName']),
            "comments"=>($row['paymentcomment'])
            );

            $paymentcost=$paymentcost+$row["paymentamount"];
            $numpayments++;
        }
 

        echo ' 
        <div class="ui-state-highlight ui-corner-all clearfix undersearch" >
        <h3> '.$numpayments.' Payment';
        if ($numpayments<>1) { echo 's'; } 
        echo ' </h3>

        <p title="Incl. VAT">Total Payments : &'. $globalprefrow['currencysymbol']. number_format($paymentcost, 2, '.', ',').'
        </p>
        </div>
        ';
        
    } else { // ends number rows, no invoices found

        echo ' No Payments for selected dates. ';
    }
    
    
    
    
    
    

    $conditions=array();
    $parameters=array();   
    if (($sqlstart) and ($sqlend)) {
        if ($clientid<>'all') {
            $conditions[] = " paymentclient = :clientid ";
            $parameters[":clientid"] = $clientid;
        }
        $conditions[] = " paymentdate <= :sqlend ";
        $parameters[":sqlend"] = $sqlstart;
        $where = implode(' AND ', $conditions);
        $query = "SELECT SUM(paymentamount) AS paymentamount FROM cojm_payments ". ($where != "" ? " WHERE $where" : "");
        try {
            $statement = $dbh->prepare($query);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $prevpayresult = $statement->fetchAll();
        }
        catch(Exception $ex) {
            echo $ex->getMessage();
        }
        
        
    // print_r ($prevpayresult);
    }
    
    
    
    $conditions=array();
    $parameters=array();   
    if (($sqlstart) and ($sqlend)) {
        if ($clientid<>'all') {
            $conditions[] = " client = :clientid ";
            $parameters[":clientid"] = $clientid;
        }
        $conditions[] = " invdate1 <= :sqlend ";
        $parameters[":sqlend"] = $sqlstart;
        $where = implode(' AND ', $conditions);
        $query = "SELECT SUM(cost + invvatcost) AS cost FROM invoicing ". ($where != "" ? " WHERE $where" : "");
        try {
            $statement = $dbh->prepare($query);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $prevresult = $statement->fetchAll();
        }
        catch(Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    
    $runningtotal=0;
    $runningtotal=$prevresult[0]['cost']-$prevpayresult[0]['paymentamount'];
    
    echo ' <div class="ui-state-highlight ui-corner-all clearfix undersearch">
    <p> Previously Invoiced : 
    &'. $globalprefrow['currencysymbol']. number_format($prevresult[0]['cost'], 2, '.', ',').' </p>
    <p> Previous Payments : 
    &'. $globalprefrow['currencysymbol']. number_format($prevpayresult[0]['paymentamount'], 2, '.', ',').' </p>
    <p> Carried Balance :  <span class="rh strong"> 
    &'. $globalprefrow['currencysymbol']. number_format($runningtotal, 2, '.', ',').' </span> </p> 
    </div> ';
    
    

    // var_dump($tablerow); 

    $sortArray = array(); 

    foreach($tablerow as $tableitem){
        foreach($tableitem as $key=>$value){ 
            if(!isset($sortArray[$key])){ 
                $sortArray[$key] = array(); 
            } 
            $sortArray[$key][] = $value; 
        } 
    } 
    
    $orderby = "date"; //change this to whatever key you want from the array
    array_multisort($sortArray[$orderby],SORT_ASC,$tablerow);
    // var_dump($tablerow); 
    
    
    echo ' <div style="clear:both;"> </div> ';
    
        if ($clientview=='client') {
        echo ' <br /> ';
    }

    echo '
    <table class="acc clear';
    if ($clientview<>'client') {
        echo ' biggertext';
    }
    echo '">
    <thead>
    <tr>
    <th>Date</th>
    <th>Ref</th>
    <th>Invoice</th>
    <th>Payment</th>
    <th>Balance</th>
    <th> </th>
    </tr>
    </thead>
    <tbody> ';
    
    
    
    if ($prevresult[0]['cost']) {
        echo ' <tr>
        <td colspan="4"> </td>
        <td class="rh">&'. $globalprefrow['currencysymbol']. number_format($runningtotal, 2, '.', ',').'</td>
        <td>Previous Transactions</td>
        </tr>';
    }
    
    
    foreach($tablerow as $tableitem) {
        if ($tableitem['isinvoice']=='1') {
            $runningtotal=$runningtotal+$tableitem['amount'];
        } else {
            $runningtotal=$runningtotal-$tableitem['amount'];
        }
        
        if (number_format($runningtotal, 2, '.', ',')=='-0.00') {
            $runningtotal=0;
        }
        
        //            "date"=>(strtotime($row['invdate1'])),
        //            "ref"
        //            "amount"=>($row["cost"]+$row["invvatcost"]),
        //            "isinvoice"=>1,
        //            "client"=>$clientid,
        //            "CompanyName"=>($row['CompanyName']),
        //            "depname"=>($row['depname']),
        //            "comments"=>($row['invcomments'])
        
        
        echo '<tr id="tr'.$tableitem['ref'].'">';
        echo '<td class="rh">'.date('D j M Y', $tableitem['date']).'</td>';
        
        if ($tableitem['isinvoice']) {
            echo '<td> Invoice ';
            
            if ($clientview<>'client') {            
            
                echo '<a
                href="view_all_invoices.php?viewtype=individualinvoice&amp;ref='.$tableitem['ref'].'"
                title="Invoice '.$tableitem['ref'].'">'.$tableitem['ref'].'</a>
                <button id="invoicedetails'.$tableitem['ref'].'" class="invoicedetails "> ▼ </button>
                <button id="hideinvoicedetails'.$tableitem['ref'].'" class="hideinvoicedetails hideuntilneeded"> ▲ </button> ';
            } else {
                
                echo $tableitem['ref'];
            }
            
            
            echo '
            </td>';
        } else {
            echo '<td> Payment ';
            if ($clientview<>'client') {            
                        
                echo '<a 
                href="paymentsin.php?paymentid='.$tableitem['ref'].'"
                title="Payment '.$tableitem['ref'].'">'.$tableitem['ref'].'</a>
                <button id="paymentdetails'.$tableitem['ref'].'" class="paymentdetails "> ▼ </button>
                <button id="hidepaymentdetails'.$tableitem['ref'].'" class="hidepaymentdetails hideuntilneeded"> ▲ </button>
                </td>';
            } else {
                echo $tableitem['ref'];
            }
        }
        
        
        if ($tableitem['isinvoice']) {
            echo '<td class="rh">&'. $globalprefrow['currencysymbol']. number_format($tableitem['amount'], 2, '.', ',').'</td> <td> </td>';
        } else {
            echo '<td> </td><td class="rh" title="'.$tableitem['paymenttypename'].'" >&'. $globalprefrow['currencysymbol']. number_format($tableitem['amount'], 2, '.', ',').'</td>';
        }
        
        echo '<td class="rh">  &'. $globalprefrow['currencysymbol']. number_format($runningtotal, 2, '.', ',').' </td> ';
        
        echo '<td>';
        if (($tableitem['notpaid']==1) and ($clientview<>'client')) {
            echo '  <button id="reconcile'.$tableitem['ref'].'" class="reconcileinvoicebutton"> Reconcile </button> ';
        }
        echo $tableitem['comments'].' ';
        
        if ($clientid=='all'){
            echo $tableitem['CompanyName'];
        }
        
        
        
        echo '</td>';
        echo '</tr>';
    }
    echo ' 
    </tbody>    
    <tfoot>
    <tr>
    <td colspan="2"></td>
    <td> &'. $globalprefrow['currencysymbol']. number_format($invoicecost, 2, '.', ',').' </td>
    <td> &'. $globalprefrow['currencysymbol']. number_format($paymentcost, 2, '.', ',').'</td>
    <td class="rh">  &'. $globalprefrow['currencysymbol']. number_format($runningtotal, 2, '.', ',').'</td>
    <td> Outstanding Due </td>
    </tr>
    </tfoot>
    </table>';
}


?>
<br />
<div id="paymentstats"> </div>
</div>
<br />
<script type="text/javascript">	
$(document).ready(function() {

    $(".paymentdetails").bind("click", function (e) {
        var paymentid = e.target.id;
        paymentid = parseInt(paymentid.match(/(\d+)$/)[0], 10);
        $("#toploader").show();
        $("#paymentdetails"+paymentid).hide();
        $("#hidepaymentdetails"+paymentid).show();
        
        $("#tr"+paymentid).after("<tr><td colspan='6' id='stats" + paymentid + "'> </td></tr>");
        $("#stats"+paymentid).load( "ajax_lookup.php", { lookuppage: "paymentdetail", paymentref: paymentid }, function() {
            $("#toploader").fadeOut();
        });
    });

    $(".hidepaymentdetails").bind("click", function (e) {
        var paymentid = e.target.id;
        paymentid = parseInt(paymentid.match(/(\d+)$/)[0], 10);
        $("#paymentdetails"+paymentid).show();
        $("#hidepaymentdetails"+paymentid).hide();
        $("#stats"+paymentid).remove();
    });

    $(".hideinvoicedetails").bind("click", function (e) {
        var invoiceid = e.target.id;
        invoiceid = parseInt(invoiceid.match(/(\d+)$/)[0], 10);
        $("#invoicedetails"+invoiceid).show();
        $("#hideinvoicedetails"+invoiceid).hide();
        $("#stats"+invoiceid).remove();
    });

    $(".invoicedetails").bind("click", function (e) {
        var invoiceid = e.target.id;
        invoiceid = parseInt(invoiceid.match(/(\d+)$/)[0], 10);
        $("#toploader").show();
        $("#invoicedetails"+invoiceid).hide();
        $("#hideinvoicedetails"+invoiceid).show();
        
        $("#tr"+invoiceid).after("<tr><td colspan='6' id='stats" + invoiceid + "'> </td></tr>");
        $("#stats"+invoiceid).load( "ajax_lookup.php", { lookuppage: "invoiceorderlist", invoiceref: invoiceid }, function() {
            $("#toploader").fadeOut();
        });
    });

    $(".reconcileinvoicebutton").bind("click", function (e) {
        var invoiceid = e.target.id;
        invoiceid = parseInt(invoiceid.match(/(\d+)$/)[0], 10);
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        
        if(dd<10) {
            dd='0'+dd
        } 
        
        if(mm<10) {
            mm='0'+mm
        } 
        
        today = dd + '-' + mm + '-' + yyyy;

        e.preventDefault();
        $.Zebra_Dialog(' ' +
            ' <input class="ui-state-default ui-corner-all caps" type="text" value="' + today + '" id="dateselector" size="12" > ' +
            '  ',
            {
            "type": "question",
            "title": "Please select Date",
            "buttons": [{
                caption: "Reconcile",
                callback: function () {
                    $("#toploader").show();
                    var dateselector=$("#dateselector").val();
    
                    $.ajax({
                        type: "post",
                        data: { page: "reconcileinvoice", 
                            ref: invoiceid, 
                            invoicedate:dateselector
                            },
                        url: "ajaxchangejob.php",
                        success: function (data) {
                            $('#searchdiv').append(data);
                            },
                        complete: function() {
                            showmessage();
                            $("#toploader").fadeOut();
                        }
                    });
                }
            },{
                caption: "Cancel"
            } ]
            }
            
        );
        
        
        var dates = $( "#dateselector" ).datepicker({
            numberOfMonths: 1,
            changeYear:false,
            firstDay: 1,
            dateFormat: "dd-mm-yy",
            changeMonth:false,
            yearRange: "1940:2020"
        });


    });

    $( "#combobox" ).combobox();
	$( "#toggle" ).click(function() {
		$( "#combobox" ).toggle();
	});
    
	$("#rangeBa, #rangeBb").daterangepicker();  
	$(function(){ $(".normal").autosize();	});
    
    $("#createpdfreceipt").click(function () {
        $("#invpage").val("createreceipt");
        $("#f1").submit();       
    });
            
    $("#previewpdfreceipt").click(function () {
        $("#invpage").val("previewreceipt");
        $("#f1").attr('target', '_blank');
        $("#f1").submit();       
    });          
             
             
<?php if ($clientview<>'client') { ?>
    
    
    var menuheight=$("#sticky_navigation").height();
    $("#expenseview").floatThead({
        position: "fixed",
        top: menuheight
    });

<?php } ?>
             
             
             
    $('#deleteinv').bind('click', function(e) {
        e.preventDefault();
        $.Zebra_Dialog('<strong>Are you sure ?</strong><br />Invoice <?php echo $row['ref']; ?> will be deleted. <br />All jobs will revert to completed status.',
        {
            'type':'warning',
            'width':'350',
            'title':'Delete Invoice ?',
            'buttons':[
                {caption: 'Delete', callback: function() { document.getElementById("frm1").submit(); } },
                {caption: 'Do NOT Delete', callback: function() {} }
            ]
        });
    });




    $('#invnotpaid').bind('click', function(e) {
        e.preventDefault();
        $.Zebra_Dialog('<strong>Are you sure ?</strong><br />Reconcilliation details for this invoice ref <?php echo $row['ref']; ?><br />will be cancelled.', {
            'type':'warning',
            'width':'350',
            'title':'Remove Reconcilliation Details ?',
            'buttons':[{
            caption: 'Remove Reconcilliation', callback: function() { document.getElementById("frm2").submit(); }},
            {caption: 'Cancel', callback: function() {}}
        ]});
    });
    

    
    $(document).ready(function() {
        var max = 0;
        $("label").each(function(){
            if ($(this).width() > max)
            max = $(this).width();    
        });
        $("label").width((max+15));
    });         
    
    
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


    function datepickeronchange() { }
});

function comboboxchanged() { }				 
</script>
<?php include "footer.php";
  

?></body></html>