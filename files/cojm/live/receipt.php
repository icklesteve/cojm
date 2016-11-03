<?php

/*
    COJM Courier Online Operations Management
	receipt.php - Create a PDF Receipt, generally for 1 / 2 jobs
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

// modded from invoice.php 

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



 
$alpha_time = microtime(TRUE); 
 $filename="receipt.php";
 error_reporting(E_ALL);
ini_set('display_errors', '1');
 
 
// error_reporting( E_ERROR | E_WARNING | E_PARSE );

include "C4uconnect.php";

$page=$_POST['invpage']; // previewreceipt or createreceipt
$invoiceref=$_POST['invref'];
$ir='';
  


// main job loop

  

$query = "SELECT * 
FROM invoicing, Clients, cojm_payments, cojm_paymenttype 
WHERE Clients.CustomerID = invoicing.client
AND cojm_payments.paymentdate =  cast(invoicing.paydate as date)
AND cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
AND invoicing.ref = ? LIMIT 0,1";


$parameters = array($invoiceref);
$statement = $dbh->prepare($query);
$statement->execute($parameters);
$mainrow = $statement->fetch(PDO::FETCH_ASSOC);








$incoicereftext=$globalprefrow["globalname"].' Receipt Ref : ' . "$invoiceref" ;




$pdfheaderstring='Dated : ' . date('D jS M Y', strtotime($mainrow['paydate']));



$showdelivery=1;




$html=$mainrow['invoicetopmiddlehtml'];
$html.= '<tr><td colspan="2"> </td>';

if ($mainrow['showdelivery']==1) {
    $html.='<td> </td>';
}




$html.='

    <td style="
    border-left-width: 1px; border-left-color: #32649b; border-left-style: Solid;
    border-right-width: 1px; border-right-color: #32649b; border-right-style: Solid;
    border-top-width: 1px; border-top-color: #32649b; border-top-style: Solid;
    border-bottom-width: 1px; border-bottom-color: #'.$globalprefrow['invoicetotalcolour'].'; border-bottom-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].' ">
    VAT Tot</td>




    <td style="
    border-left-width: 1px; border-left-color: #32649b; border-left-style: Solid;
    border-right-width: 1px; border-right-color: #32649b; border-right-style: Solid;
    border-top-width: 1px; border-top-color: #32649b; border-top-style: Solid;
    border-bottom-width: 1px; border-bottom-color: #'.$globalprefrow['invoicetotalcolour'].'; border-bottom-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].' "  >
    ex VAT Tot</td>

    <td style="
    border-left-width: 1px; border-left-color: #32649b; border-left-style: Solid;
    border-right-width: 1px; border-right-color: #32649b; border-right-style: Solid;
    border-top-width: 1px; border-top-color: #32649b; border-top-style: Solid;
    border-bottom-width: 1px; border-bottom-color: #'.$globalprefrow['invoicetotalcolour'].'; border-bottom-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].';"  > 
    Receipt Tot</td>
    </tr>';


$html.='<tr ><td><strong></strong></td>';
$html.='<td><strong>';

$html.='</strong></td>';

if ($mainrow['showdelivery']==1) {
    $html.='<td> </td>';
}


$html.='
    <td id="tdvat" style="
    border-left-width: 1px; border-left-color: #32649b; border-left-style: Solid;
    border-right-width: 1px; border-right-color: #32649b; border-right-style: Solid;
    border-top-width: 1px; border-top-color: #'.$globalprefrow['invoicetotalcolour'].'; border-top-style: Solid;
    border-bottom-width: 1px; border-bottom-color: #32649b; border-bottom-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].';" >
    <strong>&'.$globalprefrow["currencysymbol"].number_format($mainrow['invvatcost'], 2, '.', ',').'</strong></td>

    <td id="tdnovat" style="
    border-left-width:   1px; border-left-color: #32649b; border-left-style: Solid;
    border-right-width:  1px; border-right-color: #32649b; border-right-style: Solid;
    border-bottom-width: 1px; border-bottom-color: #32649b; border-bottom-style: Solid;
    border-top-width:    1px; border-top-color: #'.$globalprefrow['invoicetotalcolour'].'; border-top-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].';" >
    <strong>&'.$globalprefrow["currencysymbol"].number_format($mainrow['cost'], 2, '.', ',').'</strong></td>

    <td id="tdtotal" style="
    border-left-width:   1px; border-left-color: #32649b; border-left-style: Solid;
    border-right-width:  1px; border-right-color: #32649b; border-right-style: Solid;
    border-bottom-width: 1px; border-bottom-color: #32649b; border-bottom-style: Solid;
    border-top-width:    1px; border-top-color: #'.$globalprefrow['invoicetotalcolour'].'; border-top-style: Solid;
    background-color: #'.$globalprefrow['invoicetotalcolour'].';" >
    <strong> &'.$globalprefrow["currencysymbol"].number_format(($mainrow['cost']+$mainrow['invvatcost']), 2, '.', ',').'</strong></td>

    </tr>
    
    
    
    </table>
    
    
    
    <h2>Payment made '.date('D jS M Y', strtotime($mainrow['paymentdate'])).', &'.$globalprefrow["currencysymbol"].number_format($mainrow['paymentamount'], 2, '.', ','). ' '.$mainrow['paymenttypename'].'

    </h2>  
    
    <h2>Paid in Full, Many Thanks.</h2>
    

    ';


















$html.='<br /><hr />';





$CustomerID=$mainrow['client'];


$totco2sql="SELECT co2saving, numberitems, CO2Saved, pm10saving, PM10Saved FROM Orders, Services 
WHERE Orders.ServiceID = Services.ServiceID 
AND Orders.status >= 90 
AND Orders.CustomerID='$CustomerID' ";



$totco2sql_result = mysql_query($totco2sql,$conn_id);
while ($totco2row = mysql_fetch_array($totco2sql_result)) {
     extract($totco2row);
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
    $html.= '<p>We have helped '.$mainrow['CompanyName'].' to save '.$ttableco2 .' CO<sub>2</sub> to date, along with '.$ttablepm10.' of PM<sub>10</sub> (particulate) emissions.</p><hr />';
}




///////////////////////////////////////////////////////////


$html.='




Tracking data (where available) can be downloaded in a KML file, viewable in <a href="earth.google.com/download-earth.html" title="Download Google Earth" >Google Earth</a>.
<br /><a href="http://www.cycle4u.co.uk/services/delivery-terms-and-conditions.html" 
title="Cycle 4 U Delivery Terms and Conditions" target="_self">Delivery Terms and Conditions</a>, 
<a target="_blank" title="Information Commissioners Office" href="http://www.ico.gov.uk">ICO</a> Registered.<br />
<img src="../../images/dont_print_emails.png" border="0" width="15" alt="Why not save some paper, " /> 
Please consider the environment before printing this receipt.
<br /><hr /><br /><p></p>
<table><tr><td><img style="float:left;" src="../../images/stories/c4u/SmallHeader.jpg" border="0" width="200" height="67" alt="Logo" /></td>
<td>
<img style="float:left;" src="../../images/stories/c4u/CyclelogisticsLogo_whitebg.jpg" width="295" height="47" border="0" alt="Cycle Logistics" />
</td></tr></table>




';



$html = str_replace ("&QUOT;", "&quot;", $html);
$html = str_replace ("&AMP;", "&amp;", $html);




if ($page=='createreceipt') {

    
    
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

    $endfilename=$globalprefrow['globalshortname'].'_'.$mainrow['CompanyName'].'_';

    if ($depnm) {
        $endfilename=$endfilename.$depnm.'_';
    }

    $endfilename=$endfilename.'Receipt_ref_'.$invoiceref.'.pdf';

    
    if ($mainrow['cost']<0) {
        $endfilename='__NON-ZERO_AMOUNT__'.$endfilename;
    }
    
    
    
    
    $pdf->Output($endfilename, 'D');
 
}
else {
    
 //   include "changejob.php";
   
    echo '<!DOCTYPE html> 
        <html lang="en"> 
        <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link href="../live/favicon.ico" rel="shortcut icon" type="image/x-icon" >
        <title>Receipt Preview</title>
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

 
 mysql_close();

