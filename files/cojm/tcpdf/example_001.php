<?php
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

  include "../../administrator/updatetracking.php";

$setinvoiced=$_POST['setinvoiced'];
$year=$_POST['collectyear'];
$month=$_POST['collectmonth'];
$day=$_POST['collectday'];
$hour="23";
$minutes="59";
$collectionsuntildate = $year . "-" . $month . "-" . $day . " " . $hour . ":" . $minutes . ":59";
$setinvoiced=$_POST['setinvoiced'];
$newsetinvoiced=$_POST['setinvoiced'];
$hourly=$_POST['hourly'];
$exacttime=$_POST['exacttime'];
$year=$_POST['deliveryear'];
$month=$_POST['delivermonth'];
$day=$_POST['deliverday'];
$hour="00";
$minutes="00";
$collectionsfromdate = $year . "-" . $month . "-" . $day . " " . $hour . ":" . $minutes . ":00";
$showdelivery=$_POST['showdelivery'];
$clientid=$_POST['clientid']; 
$existinginvoiceref=$_POST['existinginvoiceref'];
$invdatemod=$_POST['invdate'];
$invcomments=$_POST['invcomments'];
$nowdate = date("Y-m-d H:i:s"); 
 
  
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

//	define ('K_PATH_MAIN', '../../../cojm/tcpdf/');
	
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
	define ('K_PATH_FONTS', 'fonts/');

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
	define ('K_BLANK_IMAGE', K_PATH_IMAGES.'images/_blank.png');

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
		 $existinginvoiceref=$_POST['existinginvoiceref'];
	 	 if ($existinginvoiceref) { $temp2=$existinginvoiceref; }
	 
	 
	 
	 
	 
$temp_ar=explode("-",$nowdate); $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; $temptime_ar[1] = 0; $temptime_ar[2] = 0; }
$day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; $second = 00; 
$temp2= date("Ymd", mktime($hour, $minutes, $second, $month, $day+$invdatemod, $year));
$invoiceref=date("$temp2") . $clientid ;
if ($existinginvoiceref) { $invoiceref=$existinginvoiceref; }
$temp1=$globalprefrow["globalname"].' Invoice Ref : ' . "$invoiceref" ;

	 
	 $today = date("l jS \of F Y");  
	 $nowdate = date("Y-m-d H:i:s"); 

$temp_ar=explode("-",$nowdate); $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; $temptime_ar[1] = 0; $temptime_ar[2] = 0; }
$day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; $second = 00; 
$temp2= date("l jS \of F Y", mktime($hour, $minutes, $second, $month, $day+$invdatemod, $year));
$invduedate = date("l jS \of F Y", mktime($hour, $minutes, $second, $month+1, $day+$invdatemod, $year));
$invoicemysqldate= date ("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day+$invdatemod, $year));
$invoiceduemysqldate= date ("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month+1, $day+$invdatemod, $year));

$pdfheaderstring='Invoice Date : ' . $temp2.', Due by ' . $invduedate;
	 
	
	
	
		
	
	
	
	
	
	/**
	 * header title
	 */
	define ('PDF_HEADER_TITLE', $temp1);

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
	define ('PDF_FONT_NAME_MAIN', 'helvetica');

	/**
	 * default main font size
	 */
	define ('PDF_FONT_SIZE_MAIN', 10);

	/**
	 * default data font name
	 */
	define ('PDF_FONT_NAME_DATA', 'helvetica');

	/**
	 * default data font size
	 */
	define ('PDF_FONT_SIZE_DATA', 8);

	/**
	 * default monospaced font name
	 */
	define ('PDF_FONT_MONOSPACED', 'helvetica');

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
  
require_once('config/lang/eng.php');
require_once('tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($globalprefrow['globalshortname']);

if ($existinginvoiceref) { $invoiceref=$existinginvoiceref; }

$newinvoiceref=$invoiceref;
$pdfheadertitle=$globalprefrow['globalshortname'].' Invoice';

$pdf->SetTitle("$invoiceref");
$pdf->SetSubject("$temp1");
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
$pdf->SetFont('helvetica', '', 9);

// add a page
$pdf->AddPage();

$query = "SELECT * FROM Clients WHERE Clients.CustomerID = $clientid";
$result_id = mysql_query ($query, $conn_id);
$clientrow=mysql_fetch_array($result_id);

$to = date('l jS \of F Y', strtotime($collectionsfromdate)); 
$from = date('l jS \of F Y', strtotime($collectionsuntildate)); 
$compnm=$clientrow['CompanyName'];
$html = '<table border="0" cellspacing="2" cellpadding="1">
	<tr>
		<th><h4>From :</h4></th>
		<th><h4>To :</h4></th>
	</tr>
<tr></tr>
<tr><td>'.$globalprefrow['globalname'].'</td><td>'.$clientrow['CompanyName'].'</td></tr>
<tr><td>'.$globalprefrow['myaddress1'].'</td><td>'.$clientrow['Address'].'</td></tr>
<tr><td>'.$globalprefrow['myaddress2'].'</td><td>'.$clientrow['Address2'].'</td></tr>
<tr><td>'.$globalprefrow['myaddress3'].'</td><td>'.$clientrow['City'].'</td></tr>
<tr><td>'.$globalprefrow['myaddress4'].'</td><td>'.$clientrow['County'].'</td></tr>
<tr><td>'.$globalprefrow['myaddress5'].'</td><td>'.$clientrow['Postcode'].'</td></tr>
</table>
<hr><br><h4>For services between '.$to.' and '.$from.'.</h4>';

if ($invcomments) { $html=$html.' <h4>'.$invcomments.'</h4>'; }

$html=$html.'<hr ><br><h4> </h4><br>';
$html=$html.'<div><table cellspacing="0" cellpadding="1" border="0" ><tr>';

if ($showdelivery) { $html=$html .'<th width="7%">'; } 
else { $html=$html . '<th width="7%">'; } $html=$html . '<strong>Our<br>Ref</strong></th>';
if ($showdelivery=="1" ) { $html=$html .'<th width="26%">'; } 
else { $html=$html . '<th width="40%">'; }
if ($hourly) { $html=$html . ''; } else { $html=$html . '<strong>Collection</strong>'; } 
$html=$html.'</th>';
if ($showdelivery=="1") { $html=$html.'<th width="26%">'; } 
if ($hourly) { $html=$html . 'Until</th>'; } 
else 
{ $html=$html . '<strong>Delivery</strong></th>'; } 
$html=$html.'<th width="25%"><strong>Service</strong></th>
<th width="8%"><strong>VAT<br>Element</strong></th>
<th width="8%"><strong>Total<br>Inc. VAT</strong></th></tr>';

$i=0; 

$sql = "SELECT * FROM 
Orders, 
Services 
WHERE  Orders.ServiceID = Services.ServiceID 
AND CustomerID = '$clientid' 
AND `Orders`.`collectiondate` >= '$collectionsfromdate' 
AND `Orders`.`collectiondate` <= '$collectionsuntildate' 
ORDER BY `Orders`.`collectiondate` ASC";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 

// table loop
while ($row = mysql_fetch_array($sql_result)) { extract($row);
 
 if ($exacttime) {
 $to   = date('H:i D jS F Y', strtotime($row['ShipDate'])); 
 $from = date('H:i D jS F Y', strtotime($row['collectiondate'])); 
 } 
 else { 
 $to   = date('D jS F Y', strtotime($row['ShipDate'])); 
 $from = date('D jS F Y', strtotime($row['collectiondate'])); 
 }

 $tablevatcost =$tablevatcost  + $row["vatcharge"]; 
 $tablecost =$tablecost  + $row["FreightCharge"];
 $tableitems=$tableitems + $numberitems;
 $i=$i+1; if( $i & 1 ) { $bgc=$globalprefrow['invoicefooter'];} else {$bgc='ffffff';}
$html=$html.'<tr style="background-color: #'.$bgc.'";><td>'.$row[ID].'</td><td>'.$from.'</td>';
if ($showdelivery=="1") { $html=$html.'<td>'.$to.'</td>'; }
$numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');
$html=$html.'<td>'.$numberitems.' x '.$Service.'</td>
<td>&'.$globalprefrow["currencysymbol"].$row["vatcharge"].'</td>
<td>&'.$globalprefrow["currencysymbol"].$row["FreightCharge"].'</td>

</tr>';
$html=$html.'<tr style="background-color: #'.$bgc.'";>
<td colspan="6" >';
if ($row['CollectPC']) { $html=$html.'From <a target="_blank" href="http://maps.google.co.uk/maps?q='.$row['CollectPC'].'">'.$row['CollectPC']
.'</a> '; }
if ($row['ShipPC']) {
$html=$html.' To <a target="_blank" href="http://maps.google.co.uk/maps?q='.$row["ShipPC"].'">'. $row["ShipPC"].'</a>. '; }
if ($row['jobcomments']) { $html=$html.$row['jobcomments'].' '; }
if ($row['podname']=="") { $poddisplaytext=""; } else {
$temp_ar=explode("-",$row['ShipDate']);
$spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; $temptime_ar[1] = 0; 
$temptime_ar[2] = 0; } $day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1];

 $poddisplaytext = '<a target="_blank" href="';
 $poddisplaytext = $poddisplaytext . $globalprefrow['httproots']."/pod/$year/$month/";
 $poddisplaytext = $poddisplaytext . $row['podname'];
 $poddisplaytext = $poddisplaytext . '"' . '>Proof of delivery viewable on-line</a>. ';
}

$query = "SELECT CyclistID, cojmname, trackerid FROM Cyclist ORDER BY CyclistID"; 
$result_idt = mysql_query ($query, $conn_id); 
while (list ($CyclistID, $cojmname, $trackerid) = mysql_fetch_row ($result_idt)) { 
if ($row['CyclistID'] == $CyclistID) { $thistrackerid=$trackerid; }}  
 $startpause=strtotime($row['starttrackpause']); 
 $finishpause=strtotime($row['finishtrackpause']);  
 $collecttime=strtotime($row['collectiondate']); 
 $delivertime=strtotime($row['ShipDate']); 
 if (($startpause > 10) and ( $finishpause < 10)) { $delivertime=$startpause; } 
 if ($startpause <10) { $startpause=9999999999; } 
 if (($row['status']<86) and ($delivertime < 200)) { $delivertime=9999999999; } 
 if ($row['status']<50) { $delivertime=0; } 
 if ($collecttime < 10) { $collecttime=9999999999;} 
// $html=$html.' Start pause : '.$startpause.' collect : '.$collecttime.' trackerid : '.$thistrackerid.' delivertime : '.$delivertime.'';
$trasql = "SELECT * FROM `instamapper` 
WHERE `device_key` = '$thistrackerid' 
AND `timestamp` >= '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' AND '$finishpause' 
AND `timestamp` <= '$delivertime' "; 
$trasql_result = mysql_query($trasql,$conn_id)  or mysql_error(); 
$trsumtot=mysql_affected_rows();   
 if ($trsumtot>0.5) { $html=$html.' <a href="'.$globalprefrow['httproots'].'/createkml.php?id='.$row['publictrackingref'].'">Tracking data available on-line</a>. '; } 

// $html=$html.' total tracks: '.$trsumtot;
$html=$html.$poddisplaytext.'</td></tr>';

if ($setinvoiced==1) {
$updatequery = "UPDATE Orders SET status =110 WHERE ID=$row[ID]";
mysql_query($updatequery,$conn_id) or die(mysql_error()); 
$updatequery = "UPDATE Orders SET invoiceref =$newinvoiceref WHERE ID=$row[ID]";
mysql_query($updatequery,$conn_id) or die(mysql_error()); 
} // end check to see if invoice details added to order database 

} // end loop individual job

$i=0; if( $i & 1 ) { $bgc=$globalprefrow['invoicefooter']; } else { $bgc='ffffff'; }
$html=$html.$htmlnew.'<tr style="background-color: #'.$bgc.'";><td colspan="6"> </td></tr>';
$i=$i+1; if( $i & 1 ) { $bgc=$globalprefrow['invoicefooter']; } else { $bgc='ffffff'; }

$tablecost= number_format($tablecost, 2, '.', ''); 
$tablevatcost= number_format($tablevatcost, 2, '.', ''); 

$html=$html.'<tr style="background-color: #'.$bgc.'";><td> </td>';
if ($showdelivery) { $html=$html .'<td> </td>'; }
$html=$html .'<td><strong>Totals : </strong></td><td><strong>'.$tableitems.' Items</strong></td>
<td><strong>&'.$globalprefrow["currencysymbol"].$tablevatcost.'</strong></td>
<td><strong>&'.$globalprefrow["currencysymbol"].$tablecost.'</strong></td></tr>
<tr><td colspan="6"><hr></td></tr>
</table></div>';

if ( $setinvoiced == "1" ) {

// $html=$html.'<br/><b>Updating Invoice DB</b><hr/>'. $newinvoiceref . "<br/>";
$sql = "DELETE from invoicing WHERE ref='$newinvoiceref'";	mysql_query($sql, $conn_id);
$sql = "INSERT INTO invoicing ( ref, invdate1, created, client, cost, invdue ) 
VALUES ( '$newinvoiceref', '$invoicemysqldate' , now() , '$clientid' , '$tablecost', '$invoiceduemysqldate' ) "; 
$result = mysql_query($sql);
if ($result){ $html=$html . ""; } else { echo "<h1>An error occured during database update</h1>"; }

// get last invoiced date
$sql = "SELECT lastinvoicedate from Clients WHERE CustomerID=$clientid";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 

while ($row = mysql_fetch_array($sql_result)) { extract($row); }
 $date4 = strtotime($lastinvoicedate);
 $date2 = strtotime($collectionsuntildate);
 $diffdate= ($date4 - $date2 );

if ( $diffdate < 0 ) { 

$sql="UPDATE `Clients` SET `lastinvoicedate` = '$collectionsuntildate' WHERE CONCAT( `Clients`.`CustomerID` ) =$clientid";
$result = mysql_query($sql, $conn_id);
if ($result){ $html=$html . ""; } else { $html= "<h1>An error occured during database update</h1>"; }

} // end of making sure client database latest invoice time is latest

// end of updating databse check
}

$html=$html.$globalprefrow['invoicefooter3'].$newinvoiceref.$globalprefrow['invoicefooter4'];;

// output the HTML content
$pdf->writeHTML($html, true, false, false, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
//Close and output PDF document
 $pdf->Output($globalprefrow['globalshortname'].'_'.$compnm.'_Invoice_ref_'.$newinvoiceref.'.pdf', 'D');
 
 mysql_close();
// original $pdf->Output("$invoiceref" . '.pdf', 'I');
// $pdf->Output($invoiceref.'.pdf','F');
