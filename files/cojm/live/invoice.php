<?php

/*
    COJM Courier Online Operations Management
	invoice.php - Create a PDF invoice
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

// modded from :

//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2010-08-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */


 if (!isset($_POST['addresstype'])) { die; }
 
 
$alpha_time = microtime(TRUE); 
 $filename="invoice.php";
 error_reporting(E_ALL);
ini_set('display_errors', '1');
 
 
// error_reporting( E_ERROR | E_WARNING | E_PARSE );

include "C4uconnect.php";

$page=$_POST['page']; // preview , createpdf , addtodb


// date to invoice until
$invoicetodate=trim($_POST['invoicetodate']);
$invoicetodate = str_replace("/", ":", "$invoicetodate", $count);
$invoicetodate = str_replace(",", ":", "$invoicetodate", $count);
$invoicetodate = str_replace("-", ":", "$invoicetodate", $count);

if ($invoicetodate) {

    $temp_ar=explode(":",$invoicetodate); 
    $startday=$temp_ar['0']; 
    $startmonth=$temp_ar['1']; 
    $startyear=$temp_ar['2'];
    
    $collectionsuntildate=date("Y-m-d 23:59:59", mktime(01, 01, 01, $startmonth, $startday, $startyear));

} else {
    $collectionsuntildate = date('Y-m-d 23:59:59');
}



// date to invoice from
$fromdate=trim($_POST['fromdate']);
if ($fromdate=='') { $fromdate='30/03/1979'; }
$fromdate = str_replace("/", ":", "$fromdate", $count);
$fromdate = str_replace(",", ":", "$fromdate", $count);
$fromdate = str_replace("-", ":", "$fromdate", $count);
$temp_ar=explode(":",$fromdate); 
$startday=$temp_ar['0']; 
$startmonth=$temp_ar['1']; 
$startyear=$temp_ar['2'];
$fromdate=date("Y-m-d 00:00:00", mktime(01, 01, 01, $startmonth, $startday, $startyear));


$setinvoiced=$_POST['setinvoiced'];
$newsetinvoiced=$_POST['setinvoiced'];
$hourly=$_POST['hourly'];
$exacttime=$_POST['exacttime'];


$showdelivery=$_POST['showdelivery'];
if ($showdelivery<>1) { $showdelivery=0; }
$showdeliveryaddress=$_POST['showdeliveryaddress'];
$clientid=trim($_POST['clientid']); 
$invoiceselectdep = trim($_POST['invoiceselectdep']);

$existinginvoiceref=$_POST['existinginvoiceref'];
$invdatemod=$_POST['invdate'];
$invcomments=trim($_POST['invcomments']);

$newinvdate=trim($_POST['newinvdate']);

$addresstype= trim($_POST['addresstype']);
$ir='0';
  

// Make sure that the client matches the department
if ($invoiceselectdep<>'') {
$query = "SELECT associatedclient FROM clientdep WHERE depnumber=$invoiceselectdep LIMIT 0,1"; 
$q= $dbh->query($query);
$completestatus = $q->fetchColumn();
$clientid=$completestatus;
}

$invoicedept=$_POST['invoiceselectdep'];

$query = "SELECT 
invoiceterms, 
CompanyName, 
invoiceAddress, 
invoiceAddress2, 
invoiceCity, 
invoiceCounty, 
invoicePostcode
FROM Clients 
WHERE Clients.CustomerID = ? LIMIT 0,1";


$parameters = array($clientid);
$statement = $dbh->prepare($query);
$statement->execute($parameters);
$clientrow = $statement->fetch(PDO::FETCH_ASSOC);


$day=date("d");
$month=date("m");
$year=date("Y");


// echo $newinvdate;

$temp_ar=explode("-",$newinvdate); 
$day=$temp_ar['0']; 
$month=$temp_ar['1']; 
$year=$temp_ar['2'];







$invoicedate= date("l jS F Y", mktime(01, 01, 01, $month, $day, $year));

$invduedate = date("l jS F Y", mktime(01, 01, 01, $month, ($day+$clientrow['invoiceterms']), $year));

$invoicesqldate= date ("Y-m-d H:i:s", mktime(01, 01, 01, $month, $day, $year));

$invoiceduesqldate= date ("Y-m-d H:i:s", mktime(01, 01, 01, $month, ($day+$clientrow['invoiceterms']), $year));

$invoiceref=date("Ymd", mktime(01, 01, 01, $month, $day, $year)). $clientid ;

if ($invoiceselectdep) { $invoiceref=$invoiceref.$invoiceselectdep; }
if ($existinginvoiceref) { $invoiceref=$existinginvoiceref; }
$incoicereftext=$globalprefrow["globalname"].' Invoice Ref : ' . "$invoiceref" ;




$pdfheaderstring='Invoice Date : ' . $invoicedate.', Due by ' . $invduedate;



  
  
if ($existinginvoiceref) { $invoiceref=$existinginvoiceref; }

$newinvoiceref=$invoiceref;
$pdfheadertitle=$globalprefrow['globalshortname'].' Invoice';
  

$to = date('l jS F Y', strtotime($collectionsfromdate)); 
$from = date('l jS F Y', strtotime($collectionsuntildate)); 


$conditions = array();
$parameters = array();
$where = "";


$conditions[] = " `Orders`.`CustomerID` = :clientid ";
$parameters[":clientid"] = $clientid;

if ($invoiceselectdep>'0') {
    $conditions[] = " `Orders`.`orderdep` = :invoiceselectdep ";
    $parameters[":invoiceselectdep"] = $invoiceselectdep;
}

$conditions[] = " `Orders`.`collectiondate` >= :fromdate ";
$parameters[":fromdate"] = $fromdate;

$conditions[] = " `Orders`.`collectiondate` <= :collectionsuntildate ";
$parameters[":collectionsuntildate"] = $collectionsuntildate;


$conditions[] = " `Orders`.`status` < 110 ";
$conditions[] = " `Orders`.`status` > 99 ";    


if (count($conditions) > 0) {
    $where = implode(' AND ', $conditions);
}





$sql = "SELECT collectiondate FROM Orders ". ($where != "" ? " WHERE $where" : "");

$sql.=" ORDER BY `Orders`.`collectiondate` ASC LIMIT 0,1";


$statement = $dbh->prepare($sql);
$statement->execute($parameters);
if (!$statement) throw new Exception("Query execution error.");
$todate = $statement->fetchColumn();



$to = date('l jS F Y', strtotime($todate)); 
$html.= '<table id="invoiceaddresses" border="0" cellspacing="2" cellpadding="1">
<tr>
<th><strong>To :</strong></th>
<th><strong>From :</strong></th>
</tr>

<tr>
<td>'.$clientrow['CompanyName'];


if ($invoiceselectdep<>'')  {
    $orderdep=$row['orderdep'];
    $sql="SELECT * FROM clientdep WHERE depnumber = ? LIMIT 1";
    
    $statement = $dbh->prepare($sql);
    $statement->execute([$invoiceselectdep]);
    $drow = $statement->fetch(PDO::FETCH_ASSOC);
    
    $html.=' ('.$drow['depname'].') ';
    $depnm=$drow['depname'];
}  



 






$html.='</td>
    <td>'.$globalprefrow['globalname'].'</td>
    </tr>

    <tr>
    <td>'.$clientrow['invoiceAddress'].'</td>
    <td>'.$globalprefrow['myaddress1'].'</td>
    </tr>

    <tr>
    <td>'.$clientrow['invoiceAddress2'].'</td>
    <td>'.$globalprefrow['myaddress2'].'</td>
    </tr>
    
    <tr>
    <td>'.$clientrow['invoiceCity'].'</td>
    <td>'.$globalprefrow['myaddress3'].'</td>
    </tr>
    
    <tr>
    <td>'.$clientrow['invoiceCounty'].'</td>
    <td>'.$globalprefrow['myaddress4'].'</td>
    </tr>
    
    <tr>
    <td>'.$clientrow['invoicePostcode'].'</td>
    <td>'.$globalprefrow['myaddress5'].'</td>
    </tr>
    
    </table>
    <hr />';

    
    

if ($to==$from)  {
    $html.='<h4>For services on '.$from.'</h4>';
} 
else {
    $html.='<h4>For services between '.$to.' and '.$from.'.</h4>';
}



if ($invcomments) {
    $html.=' <h4>'.$invcomments.'</h4>';
}




$html.='<hr /><div style=" height: 40px; "></div>';






$html.='<table id="invoiceorderloop" style="width:100%;" cellspacing="0" cellpadding="2" border="0" ><tr>';

if ($showdelivery) {
    $html.='<th >';
    } 
else {
    $html.='<th >';
    }


    
$html.='<strong>Our<br />Ref</strong></th>';




if ($showdelivery=="1" ) {
    $html.='<th width="20%">';
}
else {
    $html.='<th width="20%">';
}




if ($hourly) {
    $html.='<strong>From</strong>';
}
else {
    $html.='<strong>Collection</strong>';
} 

$html.='</th>';

if ($showdelivery=="1") {
    $html.='<th width="20%">';  
    if ($hourly) {
        $html.='<strong>Until</strong></th>';
    }
    else {
        $html.='<strong>Delivery</strong></th>';
    }
}

$html.='<th ><strong>Service</strong></th>
    <th ><strong>VAT<br />Element</strong></th>
    <th ><strong>Total<br />Excl. VAT</strong></th></tr>';
    


// main job loop
$conditions = array();
$parameters = array();
$where = "";


$conditions[] = " Orders.ServiceID = Services.ServiceID ";
$conditions[] = " Orders.CyclistID = Cyclist.CyclistID ";
$conditions[] = " `Orders`.`CustomerID` = :clientid ";
$parameters[":clientid"] = $clientid;
if ($invoiceselectdep>'0') {
    $conditions[] = " `Orders`.`orderdep` = :invoiceselectdep ";
    $parameters[":invoiceselectdep"] = $invoiceselectdep;
}
$conditions[] = " `Orders`.`collectiondate` >= :fromdate ";
$parameters[":fromdate"] = $fromdate;

$conditions[] = " `Orders`.`collectiondate` <= :collectionsuntildate ";
$parameters[":collectionsuntildate"] = $collectionsuntildate;


$conditions[] = " `Orders`.`status` < 110 ";
$conditions[] = " `Orders`.`status` > 99 ";    


$where = implode(' AND ', $conditions);


$sql = "SELECT  * FROM   Orders, 
    Services,
    Cyclist ". ($where != "" ? " WHERE $where" : "");

$sql.=" ORDER BY `Orders`.`collectiondate` ASC";


$statement = $dbh->prepare($sql);
$statement->execute($parameters);
$stmt = $statement->fetchAll();
if (!$statement) throw new Exception("Query execution error.");


$i='0'; 

$invoicejobarray=array();

// table loop
foreach ($stmt as $row) {
    
    
    array_push($invoicejobarray,$row['ID']);


    
    
    $query = "SELECT * FROM cojm_pod WHERE id = :getid LIMIT 0,1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $row['publictrackingref'], PDO::PARAM_INT); 
    $stmt->execute();
    $haspod = $stmt->rowCount();
    
    
 
    // if ($row['status']<110) {
 
    if ($exacttime) {
        $to = date('H:i D jS F Y', strtotime($row['ShipDate'])); 
        $from = date('H:i D jS F Y', strtotime($row['collectiondate'])); 
    }
    else { 
        $to = date('D jS F Y', strtotime($row['ShipDate']));
        $from = date('D jS F Y', strtotime($row['collectiondate']));
    }

    $tablevatcost =$tablevatcost  + $row["vatcharge"]; 
    $tablecost =$tablecost  + $row["FreightCharge"];
    $tableitems=$tableitems + $numberitems;
    $ir=$ir+1;
    if( $ir & 1 ) {
        $bgc=$globalprefrow['invoicefooter'];
    } else {
        $bgc='ffffff';
    }
    $html.='<tr style="background-color: #'.$bgc.'"><td>';

    if ((trim($globalprefrow['locationquickcheck'])) and ($showdeliveryaddress=="1")) {
        $html.='<a href="'.$globalprefrow['locationquickcheck'].'?quicktrackref='.$row['publictrackingref'].'" target="_blank">'.$row['publictrackingref'].'</a>';
    }
    else {
        $html.=$row['ID'];
    }

    $html.='</td><td>'.$from.'</td>';
    if ($showdelivery=="1") {
        $html.='<td>'.$to.'</td>';
    }
    $numberitems= trim(strrev(ltrim(strrev($row['numberitems']), '0')),'.');

    $tempvatcost= number_format($row['vatcharge'], 2, '.', ''); 

    $html.='<td>'.$numberitems.' x '.$row['Service'].'</td>
    <td>&'.$globalprefrow["currencysymbol"].$tempvatcost.'</td>
    <td>&'.$globalprefrow["currencysymbol"].$row["FreightCharge"].'</td>
    </tr>';
    $html.='<tr style="background-color: #'.$bgc.'">';

    if ($addresstype=='none') { }

    if ($showdelivery=="1") {
        $html.='<td> </td>';
    }
    $html.='<td colspan="5" >'; 

    $enrpc0=trim($row['enrpc0']);
    $enrpc21=trim($row['enrpc21']);
    $enrpc02 = str_replace(" ", "%20", "$enrpc0", $count);
    $enrpc212 = str_replace(" ", "%20", "$enrpc21", $count);
 
 
    $enrft0pc = str_replace(" ", "%20", "$enrft0", $count);
    $enrft21pc = str_replace(" ", "%20", "$enrft21", $count); 
    $htmlb='';

    if ($row['hourlyothercount']) {
        $htmlb.=$globalprefrow['glob5'].' : '.$row['poshname'].'. ';
    }


    // invoiceselectdep

    if (($row['orderdep']) and ($invoiceselectdep==''))  {
        // $infotext=$infotext.'Is Department';  

        $sql="SELECT depname FROM clientdep WHERE depnumber = ? LIMIT 1";
        $statement = $dbh->prepare($sql);
        $statement->execute([$row['orderdep']]);
        if (!$statement) throw new Exception("Query execution error.");
        $depname = $statement->fetchColumn();
        $htmlb.=' Department : '.$depname.'. ';
    }


    if ((trim($row['enrpc0'])) or (trim($row['enrft0']))) {

        if ($addresstype=='postcode') { // display just postcode
            $htmlb.='<span title="PickUp">PU</span> <a target="_blank" href="http://maps.google.com/maps?q='.$fromfreeaddresspc.'+'.$enrpc02.'">'.$row['enrpc0'].'</a> '; 
        }

        if ($addresstype=='full') { // use freetext details

            $htmlb.='<span title="PickUp">PU</span> <a target="_blank" href="http://maps.google.com/maps?q='.$row['enrft0'].'+'.$row['enrpc0'].'">'.$row['enrft0'].' '.$row['enrpc0'].'</a> <br />';
        }






        $n='0';
        $i='1'; 
        while ($i<'21') {
            if (((trim($row["enrpc$i"]))<>'') or (trim($row["enrft$i"]<>''))) {
                $n++;
            }
            $i++; 
        }
        if ($n=='1') {
                $htmlb.=' via '.$row["enrft1"] .' '.$row["enrpc1"].'<br />';
            }
        if ($n>'1') {
            $htmlb.=' via '.$n.' stops ';
        }

    } // ends check for collection

    if ($showdeliveryaddress=='1') {

        if ((trim($row['enrpc21'])) or (trim($row['enrft21']))) {

            if ($addresstype=='postcode') { // display just postcode
                $htmlb.='To <a target="_blank" href="http://maps.google.com/maps?q='.$enrft21pc.'%20'.$enrpc212.'">'.$row["enrpc21"].'</a>. ';
            }

            if ($addresstype=='full') { // display full
                $htmlb.='To <a target="_blank" href="http://maps.google.com/maps?q='.$enrft21pc.'%20'.$enrpc212.'">'.$row['enrft21'].' '.$row["enrpc21"].'</a>. <br />';
            }

        } // ends check for having text AND postcode

    } // ends check for option to show delivery address




    if  ($haspod) {
        $htmlb.= ' <a title="Proof of Delivery" href="'.$globalprefrow['httproots'].'/cojm/podimage.php?id='. $row['publictrackingref'].'" >POD</a> ';
    }







    $startpause=strtotime($row['starttrackpause']); 
    $finishpause=strtotime($row['finishtrackpause']);  
    $collecttime=strtotime($row['collectiondate']); 
    $delivertime=strtotime($row['ShipDate']); 
    if (($startpause > '10') and ( $finishpause < '10')) { $delivertime=$startpause; } 
    if ($startpause <'10') { $startpause='9999999999'; } 
    if (($row['status']<'86') and ($delivertime < '200')) { $delivertime='9999999999'; } 
    if ($row['status']<'50') { $delivertime='0'; } 
    if ($collecttime < '10') { $collecttime='9999999999';} 
    // $html=$html.' Start pause : '.$startpause.' collect : '.$collecttime.' trackerid : '.$thistrackerid.' delivertime : '.$delivertime.'';
    $sql = "SELECT * FROM `instamapper` 
    WHERE `device_key` = ?
    AND `timestamp` > ?
    AND `timestamp` NOT BETWEEN ? AND ?
    AND `timestamp` < ? LIMIT 0,2"; 
    
    // echo $trasql;
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$row['trackerid'],$collecttime,$startpause,$finishpause,$delivertime]);
    $stmt = $prep->fetchAll();
  
    if ($stmt) {
        $htmlb.=' <a href="'.$globalprefrow['httproots'].'/cojm/createkml.php?id='.$row['publictrackingref'].'">Tracking</a> ';
    }



    if ($row['requestor']) {
        $htmlb.='Requested by '. $row["requestor"].'. ';
    }

    if (trim($row['clientjobreference'])) {
        $htmlb.='Your Ref : ' .$row['clientjobreference'].'.  ';
    }


    if ($row['jobcomments']) {
        $htmlb.=$row['jobcomments'].' ';
    }


    ////////////////////////////////////////////////////////////////////////

    $compco2='';
    $comppm10='';
    $tableco2='';
    $tablepm10='';


    if ($row['co2saving']>'0.001')  {$tableco2=$row["co2saving"];   } else { $tableco2 =($row['numberitems'])*($row["CO2Saved"]);  }
    if ($row['pm10saving']>'0.001') {$tablepm10=$row["pm10saving"]; } else { $tablepm10=($row['numberitems'])*($row["PM10Saved"]); }	
	
    $compco2=$tableco2;
    $comppm10=$tablepm10;	


 
    if ($tablepm10>'1000') {
        $tablepm10=($tablepm10/'1000');
        $tablepm10 = number_format($tablepm10, 1, '.', ',');
        $tablepm10= $tablepm10.'kg';
    }
    else {
        if ($tablepm10>'0.001') { 
            $tablepm10 = number_format($tablepm10, 1, '.', ',');
            $tablepm10.=' grams';
        }
    } 

    if ($tableco2>'1000') {
        $tableco2=($tableco2/'1000');
        $tableco2 = number_format($tableco2, 1, '.', ',');
        $tableco2.= 'kg';
    }
    else {
        if ($tableco2>'0.001') {
            $tableco2.=' grams';
        }
    } 


    //	 echo ' table co2 : '.$tableco2.' table pm10 : '.$tablepm10;
	
	
    $CO2text='';
    $pm10text='';	

    if ($compco2>'0.001')  { $CO2text=" CO<sub>2</sub> Saved : ". $tableco2.'.';    }
    if ($comppm10>'0.0001') { $pm10text=" PM<sub>10</sub> Saved : ".$tablepm10.'.'; }
 
    $htmlb.= $CO2text . $pm10text; 

    ////////////////////////////////////////////////////////////////////////



 
    if ($row['iscustomprice']<>'1') { //

        // $htmlb=$htmlb.' Got to 770 ';
 
        if ($row['chargedbybuild']=='1') {

            $htmlb=$htmlb.'<br />';

            $sql = "SELECT * FROM chargedbybuild ORDER BY cbborder ASC";
            $prep = $dbh->query($sql);
            $stmt = $prep->fetchAll();            
            
            foreach ($stmt as $cbrow) {
                extract($cbrow);
                $cbr=$cbrow['chargedbybuildid'];

                if (($row["cbbc$cbr"]>'0') and ($row["cbb$cbr"]<>'0.00')) {
                    $htmlb.= ' '.$cbrow['cbbname'].' &'. $globalprefrow['currencysymbol'] . $row["cbb$cbr"];
                }
            }
        } // ends check to see if job is cbb
 
 
 
        if ($row['clientdiscount']>'0') {
            $htmlb.=' Client Discount : &'. $globalprefrow['currencysymbol'].$row["clientdiscount"]; 
        }
  
 
    } // ends check for custom price

 
 
 
    // $html=$html.$row['status'];
    // $html=$html.' total tracks: '.$trsumtot;
    $html.=$htmlb.'</td></tr>';


    if ($htmlb) { 
        $html.='<tr style="background-color: #'.$bgc.'" >';
        if ($showdelivery=="1") {
            $html.='<td> </td>';
        }
        $html.='<td colspan="5" > </td></tr>';
    }




} // end loop individual job

$totalincvat=$tablevatcost+$tablecost;



$topmiddlehtml=$html; // the bit to get stored in the database




$html.= '<tr><td colspan="2"> </td>';

if ($showdelivery=='1') {
    $html.='<td> </td>';
}




$html.='

    <td style="
    border-left-width: 1px;
    border-left-color: #32649b;
    border-left-style: Solid;
    border-right-width: 1px;
    border-right-color: #32649b;
    border-right-style: Solid;
    border-top-width: 1px;
    border-top-color: #32649b;
    border-top-style: Solid;
    border-bottom-width: 1px;
    border-bottom-color: #'.$globalprefrow['invoicetotalcolour'].';
    border-bottom-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].' ">
    VAT Total</td>




    <td style="
    border-left-width: 1px;
    border-left-color: #32649b;
    border-left-style: Solid;
    border-right-width: 1px;
    border-right-color: #32649b;
    border-right-style: Solid;
    border-top-width: 1px;
    border-top-color: #32649b;
    border-top-style: Solid;
    border-bottom-width: 1px;
    border-bottom-color: #'.$globalprefrow['invoicetotalcolour'].';
    border-bottom-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].' "  >
    ex VAT Total</td>

    <td style="
    border-left-width: 1px;
    border-left-color: #32649b;
    border-left-style: Solid;
    border-right-width: 1px;
    border-right-color: #32649b;
    border-right-style: Solid;
    border-top-width: 1px;
    border-top-color: #32649b;
    border-top-style: Solid;
    border-bottom-width: 1px;
    border-bottom-color: #'.$globalprefrow['invoicetotalcolour'].';
    border-bottom-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].';"  > 
    Grand Total</td>
    </tr>';

$bgc=$globalprefrow['invoicefooter']; 
$html.='<tr style="background-color: #'.$bgc.'" ><td><strong>Total :</strong></td>';
$html.='<td><strong>';
$modtableitems=$tableitems - floor($tableitems);
// $html.=' modtableitems:'.$modtableitems.' ';

if ($modtableitems=='0') {
    $tableitems= number_format($tableitems, 0, '.', ',');
}

$html.=$tableitems;

if ($tableitems=='1') {
    $html.=' Item';
} else {
    $html.=' Items';
}
$html.='</strong></td>';

if ($showdelivery) {
    $html.='<td>Invoice Terms : '.$clientrow['invoiceterms'].' days.</td>';
}



$html.='
    <td id="tdvat" style="
    border-left-width:   1px; 
    border-left-color: #32649b; 
    border-left-style: Solid;
    border-right-width:  1px;
    border-right-color: #32649b;
    border-right-style: Solid;
    border-top-width:    1px;
    border-top-color: #'.$globalprefrow['invoicetotalcolour'].';
    border-top-style: Solid;
    border-bottom-width: 1px;
    border-bottom-color: #32649b;
    border-bottom-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].';" >
    <strong>&'.$globalprefrow["currencysymbol"].number_format($tablevatcost, 2, '.', ',').'</strong></td>

    <td id="tdnovat" style="
    border-left-width:   1px; 
    border-left-color: #32649b; 
    border-left-style: Solid;
    border-right-width:  1px; 
    border-right-color: #32649b; 
    border-right-style: Solid;
    border-bottom-width: 1px;
    border-bottom-color: #32649b;
    border-bottom-style: Solid;
    border-top-width:    1px; 
    border-top-color: #'.$globalprefrow['invoicetotalcolour'].';
    border-top-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].';" >
    <strong>&'.$globalprefrow["currencysymbol"].number_format($tablecost, 2, '.', ',').'</strong></td>

    <td id="tdtotal" style="
    border-left-width:   1px; 
    border-left-color: #32649b; 
    border-left-style: Solid;
    border-right-width:  1px; 
    border-right-color: #32649b; 
    border-right-style: Solid;
    border-bottom-width: 1px; 
    border-bottom-color: #32649b; 
    border-bottom-style: Solid;
    border-top-width:    1px; 
    border-top-color: #'.$globalprefrow['invoicetotalcolour'].'; 
    border-top-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].';" >
    <strong> &'.$globalprefrow["currencysymbol"].number_format($totalincvat, 2, '.', ',').'</strong></td>

    </tr>
    <tr>';

if ($showdelivery=='1') {
    $html.='<td> </td>';
}

$html.='<td colspan="5"></td></tr>
</table><br /><hr />';








$sql="SELECT co2saving, numberitems, CO2Saved, pm10saving, PM10Saved FROM Orders, Services 
WHERE Orders.ServiceID = Services.ServiceID 
AND Orders.status >= 90 
AND Orders.CustomerID= ? ";


        $prep = $dbh->prepare($sql);
        $prep->execute([$clientid]);
        $stmt = $prep->fetchAll();


foreach ($stmt as $totco2row) {
	 if ($totco2row['co2saving']>'0.001') { $ttableco2=$ttableco2+$totco2row["co2saving"]; }
	 else { $ttableco2 = $ttableco2 + (($totco2row['numberitems'])*($totco2row["CO2Saved"])); }
	 
	 if ($totco2row['pm10saving']>'0.001') {$ttablepm10=$ttablepm10+$totco2row["pm10saving"]; }
     else { $ttablepm10=$ttablepm10 + (($totco2row['numberitems'])*($totco2row["PM10Saved"])); }	 
}


$tcomppm10=$ttablepm10;
$tcompco2=$ttableco2;

if ($ttablepm10>'1000') {
$ttablepm10=($ttablepm10/'1000');
 $ttablepm10 = number_format($ttablepm10, 1, '.', ',');
$ttablepm10= $ttablepm10.'kg'; }
 else { if ($ttablepm10>'0.00001') { 
  $ttablepm10 = number_format($ttablepm10, 1, '.', ',');
 $ttablepm10=$ttablepm10.' grams'; 
}} 

if ($tcompco2>'1000000') {
$ttableco2=($ttableco2/'1000'); $ttableco2 = number_format($ttableco2, 0, '.', ',');
$ttableco2= $ttableco2.'kg'; }

if (($tcompco2>'1000') and ($tcompco2<'1000000')) {
    $ttableco2=($ttableco2/'1000'); $ttableco2 = number_format($ttableco2, 1, '.', ',').'kg';
}

if ($tcompco2<'1000') { $ttableco2.=' grams'; }

if ($tcompco2) {
    $html.= '<p>We have helped '.$clientrow['CompanyName'].' to save '.$ttableco2 .' CO<sub>2</sub> to date, along with '.$ttablepm10.' of PM<sub>10</sub> (particulate) emissions.</p><hr />';
}




///////////////////////////////////////////////////////////


$html.=$globalprefrow['invoicefooter3'].$newinvoiceref.$globalprefrow['invoicefooter4'];



$html = str_replace ("&QUOT;", "&quot;", $html);
$html = str_replace ("&AMP;", "&amp;", $html);




if ($page=='createpdf') {

    
    
    // DOCUMENT_ROOT fix for IIS Webserver
    if ((!isset($_SERVER['DOCUMENT_ROOT'])) OR (empty($_SERVER['DOCUMENT_ROOT']))) {
        if(isset($_SERVER['SCRIPT_FILENAME'])) {
            $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
        } elseif(isset($_SERVER['PATH_TRANSLATED'])) {
            $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
        } else {
            // define here your DOCUMENT_ROOT path if the previous fails (e.g. '/var/www')
            $_SERVER['DOCUMENT_ROOT'] = '/';
        }
    }
    
    // Automatic calculation for the following K_PATH_MAIN constant
    $k_path_main = str_replace( '\\', '/', realpath(substr(dirname(__FILE__), 0, 0-strlen('config'))));
    if (substr($k_path_main, -1) != '/') {
        $k_path_main .= '/';
    }
    
    /**
    * Installation path (/var/www/tcpdf/).
    * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
    */
    define ('K_PATH_MAIN', $k_path_main);
    
        
        
    // Automatic calculation for the following K_PATH_URL constant
    $k_path_url = $k_path_main; // default value for console mode
    if (isset($_SERVER['HTTP_HOST']) AND (!empty($_SERVER['HTTP_HOST']))) {
        if(isset($_SERVER['HTTPS']) AND (!empty($_SERVER['HTTPS'])) AND strtolower($_SERVER['HTTPS'])!='off') {
            $k_path_url = 'https://';
        } else {
            $k_path_url = 'http://';
        }
        $k_path_url .= $_SERVER['HTTP_HOST'];
        $k_path_url .= str_replace( '\\', '/', substr(K_PATH_MAIN, (strlen($_SERVER['DOCUMENT_ROOT']) - 1)));
    }
    
    /**
    * URL path to tcpdf installation folder (http://localhost/tcpdf/).
    * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
    */
    define ('K_PATH_URL', $k_path_url);
    
    
    
    /**
    * path for PDF fonts
    * use K_PATH_MAIN.'fonts/old/' for old non-UTF8 fonts
    */
    define ('K_PATH_FONTS', '../tcpdf/fonts/');
        
    
    /**
    * cache directory for temporary files (full path)
    */
    define ('K_PATH_CACHE', K_PATH_MAIN.'cache/');
    
    /**
    * cache directory for temporary files (url path)
    */
    define ('K_PATH_URL_CACHE', K_PATH_URL.'cache/');
    
    /**
    *images directory
    */
    define ('K_PATH_IMAGES', '');
    
    /**
    * blank image
    */
    define ('K_BLANK_IMAGE', K_PATH_IMAGES.'../images/_blank.png');
    
    /**
    * page format
    */
    define ('PDF_PAGE_FORMAT', 'A4');
    
    /**
    * page orientation (P=portrait, L=landscape)
    */
    define ('PDF_PAGE_ORIENTATION', 'P');
    
    /**
    * document creator
    */
    define ('PDF_CREATOR', 'COJM / TCPDF');
    
    /**
    * document author
    */
    define ('PDF_AUTHOR', 'COJM / '.$globalprefrow["globalname"]);
    
            
    /**
    * header title
    */
    define ('PDF_HEADER_TITLE', $incoicereftext);
    
    /**
    * header description string
    */
    define ('PDF_HEADER_STRING', "$pdfheaderstring");
    
    /**
    * image logo
    */
    define ('PDF_HEADER_LOGO', $globalprefrow['adminlogo']);
    
    $tempwidth=($globalprefrow['adminlogowidth']*25.4/150);
        
    /**
    * header logo image width [mm]
    */
    define ('PDF_HEADER_LOGO_WIDTH', $tempwidth);
    
    /**
    *  document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch]
    */
    define ('PDF_UNIT', 'mm');
    
    /**
    * header margin
    */
    define ('PDF_MARGIN_HEADER', 5);
    
    /**
    * footer margin
    */
    define ('PDF_MARGIN_FOOTER', 10);
    
    /**
    * top margin
    */
    define ('PDF_MARGIN_TOP', 25);
    
    /**
    * bottom margin
    */
    define ('PDF_MARGIN_BOTTOM', 25);
    
    /**
    * left margin
    */
    define ('PDF_MARGIN_LEFT', 15);
    
    /**
    * right margin
    */
    define ('PDF_MARGIN_RIGHT', 15);
    
    /**
    * default main font name
    */
    define ('PDF_FONT_NAME_MAIN', $globalprefrow['invoice1']);
    
    /*
    * default main font size
    */
    define ('PDF_FONT_SIZE_MAIN', $globalprefrow['invoice2']);
    
    /**
    * default data font name
    */
    define ('PDF_FONT_NAME_DATA', $globalprefrow['invoice3']);
    
    /**
    * default data font size
    */
    define ('PDF_FONT_SIZE_DATA', $globalprefrow['invoice4']);
    
    /**
    * default monospaced font name
    */
    define ('PDF_FONT_MONOSPACED', $globalprefrow['invoice5']);
    
    /**
    * ratio used to adjust the conversion of pixels to user units
    */
    define ('PDF_IMAGE_SCALE_RATIO', 1.25);
    
    /**
    * magnification factor for titles
    */
    define('HEAD_MAGNIFICATION', 1.1);
    
    /**
    * height of cell repect font height
    */
    define('K_CELL_HEIGHT_RATIO', 1.25);
    
    /**
    * title magnification respect main font size
    */
    define('K_TITLE_MAGNIFICATION', 1.3);
    
    /**
    * reduction factor for small font
    */
    define('K_SMALL_RATIO', 2/3);
    
    /**
    * set to true to enable the special procedure used to avoid the overlappind of symbols on Thai language
    */
    define('K_THAI_TOPCHARS', false);
    
    /**
    * if true allows to call TCPDF methods using HTML syntax
    * IMPORTANT: For security reason, disable this feature if you are printing user HTML content.
    */
    define('K_TCPDF_CALLS_IN_HTML', false);
    
        
    // uses these settings instead of default
    define('K_TCPDF_EXTERNAL_CONFIG', true);
    
    

    
    
    
    require_once('../tcpdf/config/lang/eng.php');
    require_once('../tcpdf/tcpdf.php');

    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($globalprefrow['globalshortname']);

    $pdf->SetTitle("$invoiceref");
    $pdf->SetSubject("$incoicereftext");
    $pdf->SetKeywords($globalprefrow['globalshortname']);

    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE."", PDF_HEADER_STRING);

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    //set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    //set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //set some language-dependent strings
    $pdf->setLanguageArray($l);

    // ---------------------------------------------------------
    // set font
    $pdf->SetFont($globalprefrow['invoice5'], '', $globalprefrow['invoice6']);

    // add a page
    $pdf->AddPage();

    // output the HTML content
    $pdf->writeHTML($html, true, false, false, false, '');
    // reset pointer to the last page
    $pdf->lastPage();
    // ---------------------------------------------------------
    //Close and output PDF document

    $endfilename=$globalprefrow['globalshortname'].'_'.$clientrow['CompanyName'].'_';

    if ($depnm) {
        $endfilename=$endfilename.$depnm.'_';
    }

    $endfilename=$endfilename.'Invoice_ref_'.$newinvoiceref.'.pdf';

    
    if ($totalincvat<0.01) {
        $endfilename='__ZERO_OR_NEGATIVE_AMOUNT__'.$endfilename;
    }
    
    
    
    
    $pdf->Output($endfilename, 'D');
 
}
else {
    
   
    echo '<!DOCTYPE html> 
        <html lang="en"> 
        <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link href="../live/favicon.ico" rel="shortcut icon" type="image/x-icon" >
        <title>Invoice Preview</title>
        <style>
            #invoiceaddresses td, #invoiceaddresses th  { 
            width:350px;
            text-align:left; 
        }
  
        #invoiceorderloop td, #invoiceorderloop th {    
            text-align:left;    
        }
  
        </style>
        </head>
        <body style="margin:0px;">';
        
        
    include "changejob.php";
        
    

    if ($pagetext) { echo $pagetext; }
    
    echo'
    <div class="Post" style="background:gray;">
    <div style="width:800px; margin:auto; background:white;">
    <div style="padding:20px;">';

    // 800px paper width

    if ($globalprefrow['showdebug']>'0') { echo $infotext; }

    echo '
    <img style="float:left; padding-right:20px;" alt="Logo" title="Logo" src="'.$globalprefrow['adminlogoabs'].'" />
    <strong>'.$incoicereftext.'</strong>
    <br />'. $pdfheaderstring.'

    <div style="clear:both;"> </div>
    <hr />
    '.$html.'
    </div>
    </div>
    </div>
    </body>
    </html>';

}