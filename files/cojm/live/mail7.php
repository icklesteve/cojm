<?php 
/*
    COJM Courier Online Operations Management
	mail7.php - Sends single job email
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






if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
error_reporting( E_ERROR | E_WARNING | E_PARSE );
$title = "COJM ";
$filename='order.php'; 
include "C4uconnect.php";
include 'changejob.php';
$mailid1=$_POST['id1'];

if (isset($_POST['afteredit'])) { $afteredit=$_POST['afteredit']; } else { $afteredit=''; }
if (isset($_POST['newemailtext1'])) { $newemailtext1=trim($_POST['newemailtext1']); } else { $newemailtext1=''; }
if (isset($_POST['newemailtext2'])) { $newemailtext2=trim($_POST['newemailtext2']); } else { $newemailtext2=''; }
if (isset($_POST['newemailtext3'])) { $newemailtext3=trim($_POST['newemailtext3']); } else { $newemailtext3=''; }
if (isset($_POST['newemailtext4'])) { $newemailtext4=trim($_POST['newemailtext4']); } else { $newemailtext4=''; }
if (isset($_POST['newemailtext5'])) { $newemailtext5=trim($_POST['newemailtext5']); } else { $newemailtext5=''; }
if (isset($_POST['newemailtext6'])) { $newemailtext6=trim($_POST['newemailtext6']); } else { $newemailtext6=''; }
if (isset($_POST['newemailtext7'])) { $newemailtext7=trim($_POST['newemailtext7']); } else { $newemailtext7=''; }
if (isset($_POST['newemailtext8'])) { $newemailtext8=trim($_POST['newemailtext8']); } else { $newemailtext8=''; }
if (isset($_POST['newemailtext9'])) { $newemailtext9=trim($_POST['newemailtext9']); } else { $newemailtext9=''; }
if (isset($_POST['newemailtext10'])) { $newemailtext10=trim($_POST['newemailtext10']); } else { $newemailtext10=''; }
if (isset($_POST['newemailtext11'])) { $newemailtext11=trim($_POST['newemailtext11']); } else { $newemailtext11=''; }
if (isset($_POST['newemailtext12'])) { $newemailtext12=trim($_POST['newemailtext12']); } else { $newemailtext12=''; }
if (isset($_POST['newemailtext13'])) { $newemailtext13=trim($_POST['newemailtext13']); } else { $newemailtext13=''; }
if (isset($_POST['newemailtext14'])) { $newemailtext14=trim($_POST['newemailtext14']); } else { $newemailtext14=''; }
if (isset($_POST['newemailtext15'])) { $newemailtext15=trim($_POST['newemailtext15']); } else { $newemailtext15=''; }
if (isset($_POST['newemailtext16'])) { $newemailtext16=trim($_POST['newemailtext16']); } else { $newemailtext16=''; }


if (isset($_POST['newto'])) { $newto=trim($_POST['newto']); } else { $newto=''; }

 $to=$newto;
// echo '<h1>newto : '.$newto.'</h1>';
  
$emailtext4='';
$emailtext5='';  
$ttableco2='';  
$ttablepm10='';
$plainbodytext='';  
$emailchang2='';
$emailchang4='';
$emailchang16='';
$emailtext6='';
$emailtext8='';
$drow='';
$emailtext7='';
$CO2text='';
$pm10text='';



// if ($mailid1=="") { }


?><!DOCTYPE html> 
<html lang="en"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title) . $mailid1 ?> Send Single Email </title>
<?php
echo '</head><body>
';

$bcc = $globalprefrow['emailbcc'];
$from= $globalprefrow['emailfrom'];

$today = date("H:i a,  l jS F Y.");

$id="$mailid1";

$query="SELECT * FROM Orders, Clients, Services, status 
 WHERE Orders.CustomerID = Clients.CustomerID 
 AND Orders.ServiceID = Services.ServiceID 
 AND Orders.status = status.status 
 AND Orders.ID = '$id' LIMIT 1";
 $result=mysql_query($query);
 $row=mysql_fetch_array($result);
 

 $clientemail = $row['EmailAddress']; 
 
 if ($row['orderdep'])  {
 // $infotext=$infotext.'Is Department';  
 $orderdep=$row['orderdep']; $depquery="SELECT * FROM clientdep WHERE depnumber = '$orderdep' LIMIT 1";
 $result=mysql_query($depquery); $drow=mysql_fetch_array($result);
 // $infotext=$infotext.'<br />Dep is '.$drow['depname'];
 
  if ($drow['depemail']) { $clientemail=$drow['depemail']; }
 if ($drow['deprequestor'])  { $custforename = $drow['deprequestor'];} 
 
 
 
 }  
 $cost = $row['FreightCharge'];
 $collectiondate = $row['collectiondate'];
 $deliverytime = ($row['ShipDate']) ;
 $carbonsavingthis = $row['CO2Saved'];
 $pm10this = $row['PM10Saved'];
 $numberitems = $row['numberitems'];
 $CustomerID=$row['CustomerID'];
 $numberitems= trim(strrev(ltrim(strrev($row['numberitems']), '0')),'.');
 
 $shippc = $row['ShipPC'];
 $collectpc = $row['CollectPC'];
 $customername=$row['CompanyName'];
 $podsurname = $row['podsurname'];
 $custforename = $row['Forename'].' '.$row['Surname'];
 $status = $row['status'];
 $service = $row['Service'];


 $publictrackingreference= $row['publictrackingref'];

  
 // getting month and year variables to display pod
 $temp_ar=explode("-",$deliverytime);
 $spltime_ar=explode(" ",$temp_ar['2']); $temptime_ar=explode(":",$spltime_ar['1']); 
 if (($temptime_ar['0'] == '') || ($temptime_ar['1'] == '') || ($temptime_ar['2'] == '')) { $temptime_ar['0'] = '0'; $temptime_ar['1'] = '0'; 
 $temptime_ar['2'] = '0'; }
 $day=$spltime_ar['0']; $month=$temp_ar['1']; $year=$temp_ar['0']; $hour=$temptime_ar['0']; $minutes=$temptime_ar['1'];


 $query = "SELECT statusname, status FROM status ORDER BY status"; $result_id = mysql_query ($query, $conn_id); 
while (list ($statusname, $status) = mysql_fetch_row ($result_id)) { $status = htmlspecialchars ($status); 
$statusname = htmlspecialchars ($statusname); 
if ($row['status'] == $status) { $statustext = $statusname; } 
}



// $emailtext1='Dear '.$custforename.',';
if (date('a')=='am') { $emailtext1=$globalprefrow['email14']; } else { $emailtext1=$globalprefrow['email15']; }
if (date('H')>'19') { $emailtext1=$globalprefrow['email16']; }


$emailtext2=  $globalprefrow['email2'].' '. $id ;
if (trim($row['clientjobreference'])) { $emailtext2=$emailtext2. ' / '.$row['clientjobreference']; }
  
 

$emailtext3= $globalprefrow['email3'].' '.$statustext;
if ($podsurname) { $emailtext3=$emailtext3.$globalprefrow['email8'].' '.$podsurname.'.';}


// collection

if ($row['status'] <'55' ) { // uncollected
$emailtext4 = $emailtext4 . $globalprefrow['email4'].' '. date('H:i A', strtotime($row['targetcollectiondate'])); 
 if ($row['allowcollectww']=="1") { $emailtext4 = $emailtext4 . '- '.date('H:i A', strtotime($row['collectionworkingwindow'])); } 
$emailtext4 = $emailtext4 . date(', l jS F Y.', strtotime($row['targetcollectiondate'])) . ''; 
} else { // collected
$emailtext4=$emailtext4.$globalprefrow['email5'].' '.date('H:i A, l jS F Y.', strtotime($collectiondate)); }

// from address
if ( trim($row['CollectPC']) or (trim($row['fromfreeaddress']))) { $emailtext4 = $emailtext4 . ' From ' .$row['fromfreeaddress'].' '. $row['CollectPC'].'.'; }






// delivery

 if ($row['status']<'70') { // not delivered
$emailtext5=$emailtext5.$globalprefrow['email6'].' '. date('H:i', strtotime($row['duedate']));
if ($row['allowdeliverww']<>"1") { $emailtext5=$emailtext5. date(' A, ', strtotime($row['duedate']));  }
if ($row['allowdeliverww']=="1") { 
if (date('A', strtotime($row['duedate']))==date('A', strtotime($row['deliveryworkingwindow']))) { } else { 
$emailtext5=$emailtext5. date(' A, ', strtotime($row['duedate'])); }}
if ($row['allowdeliverww']=="1") { $emailtext5=$emailtext5. '- '.date('H:i A, ', strtotime($row['deliveryworkingwindow'])); }   
$emailtext5=$emailtext5. date('l jS F Y.', strtotime($row['duedate'])); 
} else { // delivered
$emailtext5=$emailtext5. $globalprefrow['email7'].' '. date('H:i A, l jS F Y.', strtotime($deliverytime)); }

if (trim($row['ShipPC']) or (trim($row['tofreeaddress']))) { $emailtext5=$emailtext5 . ' To '.$row['tofreeaddress'].' ' . $row['ShipPC'] . '.'; }










$emailtext14='';


 
 
$emailtext16=''; 
 
if (($row['enrpc1']) or ($row['enrft1'])) {
 
 $emailtext16='Via '.$row['enrft1'].' '.$row['enrpc1'];
  
 }







if ($row['jobcomments']=="") { } else {
 $emailtext7= $row['jobcomments']; 
};





$query = "SELECT * FROM cojm_pod WHERE id = :getid LIMIT 0,1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':getid', $row['publictrackingref'], PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
if ($total=='1') {


 $emailtext8= $globalprefrow['email9'].' '; // "pod can be viewed online"
// $emailtext8.=$globalprefrow['httproots']."/cojm/podimage.php?id&#61;".$row['publictrackingref'];
 
}





/////////////////////////////////////////////////////////////////////////////////////
if ($row['co2saving']>'0.1')  {$tableco2=$row["co2saving"]; }
	 else { $tableco2 = ($row['numberitems'])*($row["CO2Saved"]); }
	 
if ($row['pm10saving']>'0.1') {$tablepm10=$row["pm10saving"]; }
     else { $tablepm10=($row['numberitems'])*($row["PM10Saved"]); }	

$compco2=$tableco2;
$comppm10=$tablepm10;	 
if ($tablepm10>'1000') {
$tablepm10=($tablepm10/'1000');
$tablepm10 = number_format($tablepm10, 1, '.', ',');
$tablepm10= $tablepm10.'kg '; }
 else { if ($tablepm10>'0') { $tablepm10=$tablepm10.'g'; 
}} 

if ($tableco2>'1000') {
$tableco2=($tableco2/'1000');
$tableco2 = number_format($tableco2, 1, '.', ',');
$tableco2= $tableco2.'kg '; }
 else {
 if ($tableco2>'1') { $tableco2=$tableco2.'g'; 
}} 

if ($compco2)  { $CO2text= $globalprefrow['email10'] .' '.$tableco2.', ';    }
if ($comppm10) { $pm10text=$globalprefrow['email11'].' '.$tablepm10.'.'; }
 
 
 
 
 $emailtext9= $CO2text . $pm10text; 

///////////////////////  ENDS CO2/PM10 SAVING  ////////////////////////////////////////////////






///////////////////////  STARTS TOTAL PRICE   ////////////////////////////////////////////////


$vatcharge=number_format($row['vatcharge'], 2, '.', '');

$emailtext10=$globalprefrow['email13'].' ';

$emailtext10=$emailtext10.'&'.$globalprefrow['currencysymbol']. $cost.' ';
$emailtext10=$emailtext10.'+ &'.$globalprefrow['currencysymbol']. $vatcharge.' VAT';


$emailtext11=$globalprefrow['emailbody'];
$emailtext12=$globalprefrow['emailfooter'];

///////////////////////  ENDS TOTAL PRICE   ////////////////////////////////////////////////









$totco2sql="SELECT * FROM Orders, Services 
WHERE Orders.ServiceID = Services.ServiceID 
AND Orders.status >= 77 
AND Orders.CustomerID='$CustomerID'";



$totco2sql_result = mysql_query($totco2sql,$conn_id);
while ($totco2row = mysql_fetch_array($totco2sql_result)) {
     extract($totco2row);
	 if ($totco2row['co2saving']>'0.1') { $ttableco2=$ttableco2+$totco2row["co2saving"]; }
	 else { $ttableco2 = $ttableco2 + (($totco2row['numberitems'])*($totco2row["CO2Saved"])); }
	 if ($totco2row['pm10saving']>'0.1') {$ttablepm10=$ttablepm10+$totco2row["pm10saving"]; }
     else { $ttablepm10=$ttablepm10 + (($totco2row['numberitems'])*($totco2row["PM10Saved"])); }	 
}


$tcomppm10=$ttablepm10;
$tcompco2=$ttableco2;
 $ttablepm10 = number_format($ttablepm10, 1, '.', ',');
 
 

 
if ($ttablepm10>'1000') {
$ttablepm10=($ttablepm10/'1000');
$ttablepm10= $ttablepm10.'kg'; }
 else { $ttablepm10=$ttablepm10.'g'; } 
if ($ttableco2>'1000') { $ttableco2=($ttableco2/'1000'); $ttableco2 = number_format($ttableco2, 1, '.', ',');
$ttableco2= $ttableco2.'kg'; } else { $ttableco2=$ttableco2.'g'; }

if ($tcompco2) {
$emailtext13= $globalprefrow['email12'].' '.$row['CompanyName'].' '.$globalprefrow['email17'].' '.$ttableco2 .' CO2';
if ($tcomppm10) {
$emailtext13=$emailtext13.', '.$ttablepm10.' PM10'; } $emailtext13=$emailtext13.'.'; }
   
   
   
$emailtext15 = $globalprefrow['email18'].' '.$publictrackingreference.' '.$globalprefrow['email19'].' '. $globalprefrow['httproot'].' '.
$globalprefrow['email20'].' '.$globalprefrow['locationquickcheck'].'?quicktrackref='.$row['publictrackingref'];


// echo 'Tracking Ref : <a target="_blank" href="'. $globalprefrow['locationquickcheck'].'?quicktrackref='; 
// echo $row['publictrackingref'].'">'. $row['publictrackingref'].'</a>';





if ($page=='confirmactionemail') {
if ($emailtext1==$newemailtext1 ) { $emailchang1='0';} else { $emailtext1=$newemailtext1; $emailchang1='1'; }
if ($emailtext2==$newemailtext2 ) { $emailchang2='0';} else { $emailtext2=$newemailtext2; $emailchang2='1'; }
if ($emailtext3==$newemailtext3 ) { $emailchang3='0';} else { $emailtext3=$newemailtext3; $emailchang3='1'; }
if ($emailtext4==$newemailtext4 ) { $emailchang4='0';} else { $emailtext4=$newemailtext4; $emailchang4='1'; }
if ($emailtext5==$newemailtext5 ) { $emailchang5='0';} else { $emailtext5=$newemailtext5; $emailchang5='1'; }
if ($emailtext6==$newemailtext6 ) { $emailchang6='0';} else { $emailtext6=$newemailtext6; $emailchang6='1'; }
if ($emailtext7==$newemailtext7 ) { $emailchang7='0';} else { $emailtext7=$newemailtext7; $emailchang7='1'; }
if ($emailtext8==$newemailtext8 ) { $emailchang8='0';} else { $emailtext8=$newemailtext8; $emailchang8='1'; }
if ($emailtext9==$newemailtext9 ) { $emailchang9='0';} else { $emailtext9=$newemailtext9; $emailchang9='1'; }
if ($emailtext13==$newemailtext13 ) { $emailchang13='0';} else { $emailtext13=$newemailtext13; $emailchang13='1'; }
if ($emailtext10==$newemailtext10 ) { $emailchang10='0';} else { $emailtext10=$newemailtext10; $emailchang10='1'; }
if ($emailtext11==$newemailtext11 ) { $emailchang11='0';} else { $emailtext11=$newemailtext11; $emailchang11='1'; }
if ($emailtext12==$newemailtext12 ) { $emailchang12='0';} else { $emailtext12=$newemailtext12; $emailchang12='1'; }
if ($emailtext14==$newemailtext14 ) { $emailchang14='0';} else { $emailtext14=$newemailtext14; $emailchang14='1'; }
if ($emailtext15==$newemailtext15 ) { $emailchang15='0';} else { $emailtext15=$newemailtext15; $emailchang15='1'; }
if ($emailtext16==$newemailtext16 ) { $emailchang16='0';} else { $emailtext16=$newemailtext16; $emailchang16='1'; }


}

if ($newemailtext1)  { $plainbodytext=$newemailtext1. PHP_EOL .PHP_EOL; }
if ($newemailtext2)  { $plainbodytext=$plainbodytext.$newemailtext2.PHP_EOL .PHP_EOL;  }
if ($newemailtext3)  { $plainbodytext=$plainbodytext.$newemailtext3.PHP_EOL .PHP_EOL ;  }
if ($newemailtext4)  { $plainbodytext=$plainbodytext.$newemailtext4.PHP_EOL .PHP_EOL ;  }
if ($newemailtext5)  { $plainbodytext=$plainbodytext.$newemailtext5.PHP_EOL .PHP_EOL ;  }
if ($newemailtext16)  { $plainbodytext=$plainbodytext.$newemailtext16.PHP_EOL .PHP_EOL ;  }

if ($newemailtext6)  { $plainbodytext=$plainbodytext.$newemailtext6.PHP_EOL .PHP_EOL ;  }

if ($newemailtext7)  { $plainbodytext=$plainbodytext.$newemailtext7.PHP_EOL .PHP_EOL ;  }
if ($newemailtext8)  { $plainbodytext=$plainbodytext.$newemailtext8.PHP_EOL .PHP_EOL ;  }
if ($newemailtext9)  { $plainbodytext=$plainbodytext.$newemailtext9.PHP_EOL .PHP_EOL ;  }
if ($newemailtext14) { $plainbodytext=$plainbodytext.$newemailtext14.PHP_EOL .PHP_EOL ;  }
if ($newemailtext10) { $plainbodytext=$plainbodytext.$newemailtext10.PHP_EOL .PHP_EOL ;  }
if ($newemailtext15) { $plainbodytext=$plainbodytext.$newemailtext15.PHP_EOL .PHP_EOL ;  }
if ($newemailtext13) { $plainbodytext=$plainbodytext.$newemailtext13.PHP_EOL .PHP_EOL ;  }
if ($newemailtext11) { $plainbodytext=$plainbodytext.$newemailtext11.PHP_EOL .PHP_EOL ;  }
if ($newemailtext12) { $plainbodytext=$plainbodytext.$newemailtext12.PHP_EOL .PHP_EOL ;  } 
$plainbodytext=$plainbodytext.'Powered by COJM'.PHP_EOL .PHP_EOL ;

$fhtmltext ='<html><head> 
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">'
. $globalprefrow['htmlemailheader'].'</head><body>';
if ($newemailtext1)  { $fhtmltext=$fhtmltext.'<p>'.$newemailtext1.'</p>'. PHP_EOL; }

if ($newemailtext2)  { $fhtmltext=$fhtmltext.'<p>'.$newemailtext2.'</p>'.PHP_EOL ;  }

if ($newemailtext3)  { $fhtmltext=$fhtmltext.'<p>'.$newemailtext3.'</p>'.PHP_EOL ;  }

if ($emailchang4=='0') {

// if ($newemailtext4)  { $fhtmltext=$fhtmltext.'<p>4'.$newemailtext4.'</p>'.PHP_EOL ;  }

 $CollectPC=trim($row['CollectPC']);
 $CollectPC2 = str_replace(" ", "+", "$CollectPC", $count);


///// start of text collection

$fhtmltext=$fhtmltext.'<p>';

if ($row['status'] <'55' ) { // uncollected
$fhtmltext=$fhtmltext. $globalprefrow['email4'].' '. date('H:i A', strtotime($row['targetcollectiondate'])); 
 if ($row['allowcollectww']=="1") { $fhtmltext=$fhtmltext. '- '.date('H:i A', strtotime($row['collectionworkingwindow'])); } 
$fhtmltext=$fhtmltext. date(', l jS F Y.', strtotime($row['targetcollectiondate'])) . ''; 
} else { // collected
$fhtmltext=$fhtmltext.$globalprefrow['email5'].' '.date('H:i A, l jS F Y.', strtotime($collectiondate)); }

// from address
if ( trim($row['CollectPC']) or (trim($row['fromfreeaddress']))) { $fhtmltext=$fhtmltext. ' From ' .$row['fromfreeaddress'].' '. 
'<a target="_blank" href="http://maps.google.co.uk/maps?q'.'&#61;'. $CollectPC2.'">'.$row['CollectPC'].'</a>'.'.'; }

///// end of collection

} else if ($newemailtext4)  { $fhtmltext=$fhtmltext.'<p>'.$newemailtext4.'</p>'.PHP_EOL;  }

 $ShipPC=trim($row['ShipPC']);
 $ShipPC2 = str_replace(" ", "+", "$ShipPC", $count);




if ($emailchang4=='0') { 

$fhtmltext=$fhtmltext .'<p>';

 if ($row['status']<'70') { // not delivered
$fhtmltext=$fhtmltext.$globalprefrow['email6'].' '. date('H:i', strtotime($row['duedate']));
if ($row['allowdeliverww']<>"1") { $fhtmltext=$fhtmltext. date(' A, ', strtotime($row['duedate']));  }
if ($row['allowdeliverww']=="1") { 
if (date('A', strtotime($row['duedate']))==date('A', strtotime($row['deliveryworkingwindow']))) { } else { 
$fhtmltext=$fhtmltext. date(' A, ', strtotime($row['duedate'])); }}
if ($row['allowdeliverww']=="1") { $fhtmltext=$fhtmltext. '- '.date('H:i A, ', strtotime($row['deliveryworkingwindow'])); }   
$fhtmltext=$fhtmltext. date('l jS F Y.', strtotime($row['duedate'])); 
} else { // delivered
$fhtmltext=$fhtmltext. $globalprefrow['email7'].' '. date('H:i A, l jS F Y.', strtotime($deliverytime)); }


$fhtmltext=$fhtmltext. ' To ' . $row['tofreeaddress'].' '.
'<a target="_blank" href="http://maps.google.co.uk/maps?q'.'&#61;'. $ShipPC2.'">'.$row['ShipPC'].'</a>';

$fhtmltext=$fhtmltext .'.</p>'.PHP_EOL;

} else if ($newemailtext5)  { $fhtmltext=$fhtmltext.'<p>'.$newemailtext5.'</p>'.PHP_EOL ;  }



// via

 $enrpc1=trim($row['enrpc1']);
 $enrpc1 = str_replace(" ", "+", "$enrpc1", $count);

if (($emailchang16=='0') and (($enrpc1) or ($row['enrft1']))) {

$fhtmltext=$fhtmltext. '<p> Via ' . $row['enrft1'].' '.
'<a target="_blank" href="http://maps.google.co.uk/maps?q'.'&#61;'. $enrpc1.'">'.$row['enrpc1'].'</a>';

$fhtmltext=$fhtmltext .'.</p>'.PHP_EOL;

} else if ($newemailtext16) { $fhtmltext=$fhtmltext.'<p>'.$newemailtext16.'</p>'.PHP_EOL ; }














if ($newemailtext7)  { $fhtmltext.='<p>'.$newemailtext7.'</p>'.PHP_EOL ;  }


if ($newemailtext8)  {
if ($emailchang8=='0') { 


$query = "SELECT * FROM cojm_pod WHERE id = :getid LIMIT 0,1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':getid', $row['publictrackingref'], PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
if ($total=='1') {


$fhtmltext.='<a title="View POD" href="'.$globalprefrow['httproots'].'/cojm/podimage.php?id&#61;'.$row['publictrackingref'].'">'.$emailtext8.'</a>'; // show pod link as per non html

// no point in displaying the actual image as will be probably be ad-blocked as obviously php generated,
// will be resolved when move to email library when will be attached inline.
// $fhtmltext.='<img alt="Proof of delivery" src="'.$globalprefrow['httproots']."/cojm/podimage.php?id=".$row['publictrackingref'].'"> <br />'; 

}
 
 
 } else { 
$fhtmltext=$fhtmltext.'<p>'.$newemailtext8.'</p>'.PHP_EOL ;  }
}

if ($newemailtext9)  { $fhtmltext=$fhtmltext.'<p>'.$newemailtext9.'</p>'.PHP_EOL ;  }
if ($newemailtext14)  { $fhtmltext=$fhtmltext.'<p>'.$newemailtext14.'</p>'.PHP_EOL ;  }

if ($newemailtext10) { $fhtmltext=$fhtmltext.'<p>'.$newemailtext10.'</p>'.PHP_EOL ;  }
if ($newemailtext15) { 
if ($emailchang15=='0') { 
$fhtmltext=$fhtmltext.'<p>'.$globalprefrow['email18'].' '.$publictrackingreference
.' '.$globalprefrow['email19'].' <a target="_blank" href="'.$globalprefrow['httproot'].'">'.$globalprefrow['httproot'].'</a>
 <a href="'.$globalprefrow['locationquickcheck'].'?quicktrackref&#61;'.$row['publictrackingref'].'" target="_blank" >'.
 $globalprefrow['email20'].'</a>';
 
 
$fhtmltext=$fhtmltext .'.</p>'.PHP_EOL;


} else { $fhtmltext=$fhtmltext.'<p>'.$newemailtext15.'</p>'.PHP_EOL ;  } }

if ($newemailtext13) { $fhtmltext=$fhtmltext.'<p>'.$newemailtext13.'</p>'.PHP_EOL ;  }

if ($newemailtext11) { 
if ($emailchang11=='0') {
$fhtmltext=$fhtmltext.$globalprefrow['htmlemailbody'].PHP_EOL ; }
else {
$fhtmltext=$fhtmltext.'<p>'.$newemailtext11.'</p>'.PHP_EOL ;  }
}

if ($newemailtext12) {  if ($emailchang12=='0') {
$fhtmltext=$fhtmltext.''.$globalprefrow['htmlemailfooter'].''.PHP_EOL ;  } 
else { $fhtmltext=$fhtmltext.'<p>'.$newemailtext12.'</p>'.PHP_EOL; }
}
$fhtmltext =$fhtmltext.'<br /><small>Powered by <a href="http://www.cojm.co.uk" target="_blank">COJM</a></small> </body></html>';







$headers = 'From: '.$from. PHP_EOL;
$headers =$headers. 'Return-path: '.$from. PHP_EOL; 
$headers = $headers . 'Repy-To: '.$from . PHP_EOL.
           "X-Mailer: COJM-Courier-Online-Job-Management" . PHP_EOL.
           "Bcc: $bcc";
 // Generate a boundary string    
 $semi_rand = md5(time());    
 $mime_boundary = "==Multipart_COJM_Delivery_Boundary_x{$semi_rand}x";    
// Add the headers for a file attachment    
 $headers .= "\nMIME-Version: 1.0\n" .    
             "Content-Type: multipart/alternative;\n" .    
             " boundary=\"{$mime_boundary}\"";
$htmltext=$plainbodytext;	
 // Add a multipart boundary above the plain message    
 $messageplain = "This is a multi-part message in MIME format.\n\n" .    
            "--{$mime_boundary}\n" .    
            "Content-Type: text/plain; charset=\"utf-8\"\n" .    
            "Content-Transfer-Encoding: quoted-printable\n\n" .    
            $plainbodytext . "\n\n";
			
 // Add a multipart boundary above the plain message    
 $messagehtml = "--{$mime_boundary}\n" .    
            "Content-Type: text/html; charset=\"utf-8\"\n" .    
            "Content-Transfer-Encoding: quoted-printable\n\n" .  
			$fhtmltext .  "\n\n";			
			$message = $messageplain . $messagehtml ;	
$newfrom = htmlspecialchars ($from);
$subject = $globalprefrow['globalshortname']." ".$globalprefrow['email1']." " . $mailid1 .'';		

if (trim($row['clientjobreference'])) { $subject=$subject. ' / '.$row['clientjobreference']; }
  
$tempformtext='';


// $adminmenu=1;

 if ($page=='confirmactionemail') {
 if ($afteredit=='69') {
  $message = wordwrap($message, 70, PHP_EOL);
  $ok = @mail($to, $subject, $message, $headers, "-f$from");    
}
  if ($ok) {    
 $tempformtext=$tempformtext."<h2><strong>Mail sent!</strong></h2>";    
} else {    
 $tempformtext=$tempformtext. "<h1>Message not sent.</h1>";    
} 
} else {

$tempformtext='
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="id1" value="'.$row['ID'].'" >
<input type="hidden" name="afteredit" value="69" >
<input type="hidden" name="page" value="confirmactionemail" >
<button type="submit"> Confirm Send Email </button>';
 }
 
 if ($newto) { $clientemail=$newto; }
 

 $ID=$row['ID'];
 include "cojmmenu.php";
 
 ?>

<div class="Post">
<form action="#" method="post" >

<div class="ui-widget">	
<div class="ui-state-highlight ui-corner-all" style="padding: 1em; width:auto;">
<p>
Return to job <a href="order.php?id=<?php echo $id; ?>"><?php echo $id; ?></a>

</p>


<fieldset><label class="fieldLabel">From </label> <?php echo $globalprefrow['emailfrom']; ?></fieldset>
<fieldset><label class="fieldLabel">To </label>
<input class="ui-state-default ui-corner-all pad" type="text" name="newto" size="40" value="<?php echo $clientemail; ?>"></fieldset>

<?php

if ($bcc) { echo  '<fieldset><label class="fieldLabel">Bcc </label>'.$bcc.'</fieldset>'; }
echo '<fieldset><label class="fieldLabel"> Subject </label>' . $subject .'</fieldset>';
 
echo  '<fieldset><label class="fieldLabel"> &nbsp;</label>';
 
echo $tempformtext;

echo '</fieldset>
</div>
</div>
<div class="vpad">
</div>
<div class="line"></div>
<div class="vpad"></div>
<div class="ui-widget">	
<div class="ui-state-highlight ui-corner-all" style="padding: 1em; width:auto;">
<p>';

if ($emailtext1) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext1" rows="1" style="width:100%;">'.$emailtext1.'</TEXTAREA>'; }
if ($emailtext2) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext2" rows="1" style="width:100%;">'.$emailtext2.'</TEXTAREA>'; }
if ($emailtext3) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext3" rows="1" style="width:100%;">'.$emailtext3.'</TEXTAREA>'; }
if ($emailtext4) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext4" rows="1" style="width:100%;">'.$emailtext4.'</TEXTAREA>'; }
if ($emailtext5) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext5" rows="1" style="width:100%;">'.$emailtext5.'</TEXTAREA>'; }
if ($emailtext16) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext16" rows="1" style="width:100%;">'.$emailtext16.'</TEXTAREA>'; } 
if ($emailtext6) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext6" rows="1" style="width:100%;">'.$emailtext6.'</TEXTAREA>'; } 
if ($emailtext7) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext7" rows="1" style="width:100%;">'.$emailtext7.'</TEXTAREA>'; }
if ($emailtext8) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext8" rows="2" style="width:100%;">'.$emailtext8.'</TEXTAREA>'; }
if ($emailtext9) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext9" rows="1" style="width:100%;">'.$emailtext9.'</TEXTAREA>'; } 
if ($emailtext14) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext14" rows="2" style="width:100%;">'.$emailtext14.'</TEXTAREA>'; }
if ($emailtext10) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext10" rows="1" style="width:100%;">'.$emailtext10.'</TEXTAREA>'; }
if ($emailtext15) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext15" rows="2" style="width:100%;">'.$emailtext15.'</TEXTAREA>'; }
if (isset($emailtext13)) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext13" rows="1" style="width:100%;">'.$emailtext13.'</TEXTAREA>'; }
if ($emailtext11) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext11" rows="10" style="width:100%;">'.$emailtext11.'</TEXTAREA>'; } 
if ($emailtext12) { echo '<TEXTAREA class="normal ui-state-default ui-corner-all" name="newemailtext12" rows="10" style="width:100%;">'.$emailtext12.'</TEXTAREA>'; }

echo '</p></div></div>';
echo '<input type="hidden" name="id1" value="'.$row['ID'].'" >
</form></div>
';

 echo '<script type="text/javascript">
$(document).ready(function() {
	
	  $(function(){ $(".normal").autosize();	});
	
	
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+15));
});
</script>';

echo '</body></html>';

mysql_close();
?>