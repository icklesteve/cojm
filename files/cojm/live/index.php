<?php 
/*
    COJM Courier Online Operations Management
	index.php - main job schedule
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

$showasap='';
$showcargo='';
$seeifnextday='';
$hasforms='1';
$javascript='';
$dayflag='0';
$countrows='0';
$tempfirstrun='';

// phpinfo();

include "C4uconnect.php";


if ($globalprefrow['forcehttps']>'0') {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }




include "changejob.php";
 

echo '<!DOCTYPE html> <html lang="en"> <head> 
<meta http-equiv="Content-Type" 
 content="text/html; charset=utf-8"> ';

// echo ' <meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=1.0, initial-scale=1.0, user-scalable=no" "> ';

 echo ' 
 <meta name="viewport" content="width=device-width, height=device-height" > ';




echo '
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<META HTTP-EQUIV="Refresh" CONTENT="'. $globalprefrow['formtimeout'].'; URL=index.php"> 

<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>';






 echo '<title>COJM : '. ($cyclistid).'</title></head><body id="bodytop" >';

$filename="index.php";
include "cojmmenu.php"; 

echo '<div id="Post" class="Post c9 lh16">';

if($mobdevice) { $numberofresults=$globalprefrow['numjobsm']; } else {$numberofresults=$globalprefrow['numjobs']; } 
if ($page == "showall" ) { $numberofresults='10000'; }  

$sql = "
SELECT * FROM Orders 
INNER JOIN Clients 
INNER JOIN Services
INNER JOIN Cyclist 
ON 
Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
AND Orders.CyclistID  = Cyclist.CyclistID 
WHERE `Orders`.`status` <70 
ORDER BY `Orders`.`nextactiondate` , ID
LIMIT 0 , $numberofresults";



$sql_result = mysql_query($sql,$conn_id) or die (mysql_error()); $sumtot=mysql_affected_rows();

while ($row = mysql_fetch_array($sql_result)) { extract($row);

$shortcomments = (substr($row['jobcomments'],0,40));
$privateshortcomments = (substr($row['privatejobcomments'],0,40));
$bankholtext=' ';
$seeifnextnewday=date('z', strtotime($nextactiondate));
$countrows=$countrows+'1';
$numberitems= trim(strrev(ltrim(strrev($numberitems), '0')),'.');
$CollectPC=trim($CollectPC);
$ShipPC=trim($ShipPC);
$cyclistname=$row['cojmname']; 
$date4 = strtotime($nextactiondate); 
$date2 = time();
$diffdate= ($date4 - $date2 );		
$dtrgcoll = (strtotime($targetcollectiondate)-time()); 
$dtrgdeli = (strtotime($duedate)-time()); 
$linkShipPC = str_replace(" ", "+", "$ShipPC", $count);
$linkCollectPC = str_replace(" ", "+", "$CollectPC", $count);
 $fromfreeaddresslink=$row["fromfreeaddress"];
 $fromfreeaddresslink= str_replace(" ", "+", "$fromfreeaddresslink", $count);
 $tofreeaddresslink=$row["tofreeaddress"];
 $tofreeaddresslink= str_replace(" ", "+", "$tofreeaddresslink", $count);
$temp='';
if ( $globalprefrow['glob6']=='1' ) {  } //  value="1"> Colour alternates on Individual job </option>
if ( $globalprefrow['glob6']=='2' ) {  } //  value="2"> Colour alternates on Day Difference </option>

	 
if (($seeifnextday<>$seeifnextnewday) and ($tempfirstrun=='1')) {	 
echo '<div class="linevpad"> </div>'; 
// echo ' FLIP COLOUR SCHEME FLAG + 1 DAY ';
$dayflag++; }
$tempfirstrun='1';
$i='0';
$showcargo=''; 
$showasap='';

echo '<div class="ui-corner-all '; if ( $globalprefrow['glob6']=='1' ) { if ( $countrows & '1' ) {  //odd
echo ' ui-state-highlight '; } else { echo ' ui-state-default '; }} if ( $globalprefrow['glob6']=='2' ) { if ( $dayflag & '1' ) {  //odd
echo ' ui-state-default '; } else { echo ' ui-state-highlight '; }} echo '"  > ';


/////  CHECK TO SEE TO DISPLAY JUST 1 ASAP OR CARGOBIKE LOGO     ////////////////////////////////////
while ($i<'50') {
if (isset($row["cbb$i"])) {
if (($row["cbb$i"])>'0.01') {  // echo 'cbb row '.$i.' found active'; 
$asapcbb = mysql_result(mysql_query("
 SELECT cbbasap
 from chargedbybuild 
 WHERE `chargedbybuild`.`chargedbybuildid`=$i 
 LIMIT 0,1
", $conn_id), 0);
if ($asapcbb>'0.01') { // echo 'FOUND ASAP CBB'; 
$showasap='1'; }

$asapcbb = mysql_result(mysql_query("
 SELECT cbbcargo
 from chargedbybuild 
 WHERE `chargedbybuild`.`chargedbybuildid`=$i 
 LIMIT 0,1
", $conn_id), 0);
if ($asapcbb>'0.01') { // echo 'FOUND GARGO CBB'; 
$showcargo='1';
}}} $i++; } // ends loop,  also checks in service settings
if ($row['asapservice']=='1') { $showasap='1'; }
if ($row['cargoservice']=='1') { $showcargo='1'; }







echo ' 
<span class="indexorder">

<a href="order.php?id='. $ID.'">'. $ID.'</a> ';


if ($showasap=='1') { echo '<img class="indexicon" title="ASAP" alt="ASAP" src="'.$globalprefrow['image5'].'">'; } 
if ($showcargo=='1') { echo '<img class="indexicon" title="Cargobike " alt="Cargo" src="'.$globalprefrow['image6'].'">'; } 
if (($row['CyclistID']<>'1') and ($row['lookedatbycyclisttime']>10)) { echo '<img class="indexicon" alt="Viewed" title="Viewed '.
date('H:i A D jS M', strtotime($row["lookedatbycyclisttime"])) .'" src="'.$globalprefrow['viewedicon'].'">'; 
} else { 
// echo '<img class="indexicon" title= "Unviewed" alt="Unviewed" src="'.$globalprefrow['unviewedicon'].'">'; 

}



////////////     COMMENT TEXT ///////////////////////////////////////////////////////////////


echo ' <a href="new_cojm_client.php?clientid='.$row['CustomerID'].'">'.$CompanyName.'</a>';


if ($row['orderdep']) { $depname = mysql_result(mysql_query("SELECT depname FROM clientdep WHERE depnumber = '$orderdep' LIMIT 0,1 ", $conn_id), 0);

echo ' (<a href="new_cojm_department.php?depid='.$row['orderdep'].'">'.$depname.'</a>) ';

} 



if ($row['batchdropcount']<'1') { echo ', '.$numberitems.' x '. $Service .' '; }
$n='0'; $i='1'; while ($i<'21') { if (((trim($row["enrpc$i"]))<>'') or (trim($row["enrft$i"]<>''))) { $n++; } $i++; }
if ($n=='1') { $temp=$temp. ' via '.htmlspecialchars($row["enrft1"]) .' '.htmlspecialchars($row["enrpc1"]).''; }
if ($n>'1') { $temp=$temp. ' via '.$n.' stops '; }
if (($shortcomments)) { $temp=$temp. ' - '.$shortcomments; }
if ($privateshortcomments) { $temp=$temp. ' - '.$privateshortcomments; }  echo ' '.$temp.' ';




echo '
</span>
<table class="index">
<tbody> <tr>';

if ($mobdevice<>'1') { echo ' <td rowspan="2"> '; } else { echo ' <td colspan="3"> '; }



/////////////      STATUS SELECT   //////////////////////////////////////
$oldstatus=$row['status'];
echo '<form action="#" method="post" id="'. $row['ID'] .'" autocomplete="off">
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="oldstatus" value="'. $oldstatus.'">
<input type="hidden" name="page" value="editstatus"><input type="hidden" name="id" value="'. $row['ID'] .'">';
 $query = "SELECT statusname, status FROM status WHERE activestatus=1 AND status<101 ORDER BY status ASC"; 
$result_id = mysql_query ($query, $conn_id); echo '<select id="stat'. $row['ID'] .'" class="';
 if ( $globalprefrow['glob6']=='1' ) { if ( $countrows & '1' ) {  //odd
echo ' ui-state-highlight '; } else { echo ' ui-state-default '; }}
if ( $globalprefrow['glob6']=='2' ) { if ( $dayflag & '1' ) {  //odd
echo ' ui-state-default '; } else { echo ' ui-state-highlight '; }}
echo ' ui-corner-left" name="newstatus" >';
while (list ($statusname, $status) = mysql_fetch_row ($result_id)) { $status = htmlspecialchars ($status); 
$statusname = htmlspecialchars ($statusname); print ("<option "); { if ($row['status'] == $status) echo ' selected="selected" '; } 
print ("value=\"$status\">$statusname</option>"); } print ("</select>");


//////////     CYCLIST   DROPDOWN     ///////////////////////////////////
$query = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 
$result_id = mysql_query ($query, $conn_id); echo '<input type="hidden" name="oldcyclist" value="'.$row['CyclistID'].'" >
<select name="newcyclist" id="cyc'. $row['ID'] .'" class="';
if ($row['CyclistID']=='1') { echo ' blinking '; } if ( $globalprefrow['glob6']=='1' ) { if ( $countrows & '1' ) {  //odd
echo ' ui-state-highlight '; } else { echo ' ui-state-default '; }} if ( $globalprefrow['glob6']=='2' ) { if ( $dayflag & '1' ) {  //odd
echo ' ui-state-default '; } else { echo ' ui-state-highlight '; }} echo ' ui-corner-left">';
while (list ($CyclistID, $cojmname) = mysql_fetch_row ($result_id)) { $cojmname=htmlspecialchars($cojmname);  print ("<option "); 

if ($row['CyclistID'] == $CyclistID) {echo ' selected="selected" '; } 

if ($CyclistID == '1') {echo ' class="unalo" '; } 

print ("value=\"$CyclistID\">$cojmname</option>"); } 

print ("</select>"); 
$javascript=$javascript."$('#stat". $row['ID']."').change(function() { $('#".$row['ID']."').submit(); }); $('#cyc".$row['ID'].
"').change(function() { $('#".$row['ID']."').submit(); }); ";



echo '</form> ';




if ($mobdevice=='1') { 

echo ' </td></tr><tr><td> ';


} else {

echo ' </td> <td> ';

 }


 if (($row['status']) <'51' ) {
 if ($dtrgcoll<0) { echo ' <span class="blinking"> '; } echo ' Target PU'; if ($dtrgcoll<0) { echo ' </span> '; }
echo ' 
</td>
<td> 
';
if ($dtrgcoll<0) { echo ' <span class="blinking" > '; }
echo date('H:i A ', strtotime($targetcollectiondate)); 


if (date('U', strtotime($row['collectionworkingwindow']))>10) {

echo '- '.date('H:i A ', strtotime($collectionworkingwindow)); }


 

 $today=date('z');  $check=date('z', strtotime($targetcollectiondate)); $month=date('M', strtotime($targetcollectiondate));
 $cmont=date('M'); $year=date('Y', strtotime($targetcollectiondate)); $cyea=date('Y'); if ($check==($today-'1')) { echo ' Yesterday '; } else
if ($check==$today) { echo ' Today '; } else if ($check==($today+'1')) { echo ' Tomorrow '; } else { echo date(' l ', strtotime($targetcollectiondate)); }
echo date(' jS ', strtotime($targetcollectiondate)); if (($month<>$cmont) or ($year<>$cyea)) { echo date(' M ', strtotime($targetcollectiondate)); }
if ($year<>$cyea) { echo date(' Y ', strtotime($targetcollectiondate)); } if ($dtrgcoll<0) { echo ' </span> '; }
}  else { // ends collection due , now  collected
 echo ' PU was at 
 </td>
 <td> 
 '. date('H:i A ', strtotime($row['collectiondate'])); $today=date('z'); $check=date('z', strtotime($collectiondate)); 
 $month=date('M', strtotime($collectiondate)); $cmont=date('M'); $year=date('Y', strtotime($collectiondate));
 $cyea=date('Y'); if ($check==($today-'1')) { echo ' Yesterday '; } else if ($check==$today) { echo ' Today '; } else  
if ($check==($today+'1')) { echo ' Tomorrow '; } else { echo date(' l ', strtotime($collectiondate));  } 
echo date(' jS ', strtotime($collectiondate)); if (($month<>$cmont) or ($year<>$cyea)) { echo date(' M ', strtotime($collectiondate));   }
if ($year<>$cyea) { echo date(' Y ', strtotime($collectiondate));   } } // ends collection due / collected




///   check if bank hols
$query = "SELECT * FROM bankhols"; $result_id = mysql_query ($query, $conn_id); 
$bankhol_result = mysql_query($query,$conn_id) or die (mysql_error()); 
while ($bhrow = mysql_fetch_array($bankhol_result)) { extract($bhrow); 
$temp_ar=explode("-",$targetcollectiondate);$spltime_ar=explode(" ",$temp_ar['2']); $temptime_ar=explode(":",$spltime_ar['1']); 
if (($temptime_ar['0']=='')||($temptime_ar['1']=='')||($temptime_ar['2']=='')){$temptime_ar['0']='0';$temptime_ar['1']='0';$temptime_ar['2']='0';}
$day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; 
$coldatetocheck=$year.'-'.$month.'-'.$day;
if ($coldatetocheck==$bhrow['bankholdate']) { 
$tempflag='1';
echo '<span class="bankhol" > '. $bhrow['bankholcomment'].'</span>'; 
}
} // ends bankhol loop
// ends check for bank hols



echo ' </td> <td> ';
 
 
 if ((($CollectPC) =="" ) and ($row['fromfreeaddress']=="" )) {  } else {

if ( $globalprefrow["inaccuratepostcode"]=='1') {



// echo ' full address link '; 
echo ' <a class="newwin" target="_blank" href="https://www.google.com/maps/?q='.$fromfreeaddresslink.'+'.$linkCollectPC .'">'.$row["fromfreeaddress"].' '.$CollectPC.'</a> ';
 } else { echo $row['fromfreeaddress'].' ';
 
 if ($CollectPC) {
 
 echo '<a '; if ($CyclistID=='1') { echo ' style="'.$globalprefrow["highlightcolourno"].'"'; } 
 echo ' target="_blank" class="newwin" href="https://www.google.com/maps/?q='. $linkCollectPC .'">'. $CollectPC .'</a> '; 
 }
 
 
}} // ends check to see if needs to display links and From




//// CASH PAYMENT CHECK    ////////////////////////////////////////////////////
if ($row['invoicetype']=='3') { echo " <span style='". $globalprefrow['courier6']."'>Payment on PU &". 
$globalprefrow['currencysymbol'].($row['FreightCharge']+$row['vatcharge']).'</span>'; }
 


 echo ' &nbsp; 
 </td>
 </tr> 
 <tr>
 <td> 
 ';
 
 
 
 
 if ($dtrgdeli<0) { echo ' <span class="blinking" > '; } echo ' Target Drop '; if ($dtrgdeli<0) { echo ' </span> '; }

 echo '
 </td>
 <td>
 ';

 
 
// due date / time   ///////////////////////////////////////////////
if ($dtrgdeli<0) { echo ' <span class="blinking" > '; }
echo date('H:i A ', strtotime($duedate)); 

if (date('U', strtotime($row['deliveryworkingwindow']))>10) { 
echo '- '.date('H:i A ', strtotime($deliveryworkingwindow)); } 


$today=date('z'); $check=date('z', strtotime($duedate)); $month=date('M', strtotime($duedate));
$cmont=date('M'); $year=date('Y', strtotime($duedate)); $cyea=date('Y');
if ($check==($today-'1')) { echo ' Yesterday '; } else if ($check==$today) { echo ' Today '; } else  
if ($check==($today+'1')) { echo ' Tomorrow '; } else { echo date(' l ', strtotime($duedate));  } echo date(' jS ', strtotime($duedate)); 
if (($month<>$cmont) or ($year<>$cyea)) { echo date(' M ', strtotime($duedate));   } if ($year<>$cyea) { echo date(' Y ', strtotime($duedate)); }
if ($dtrgdeli<'0') { echo ' </span> '; }




  
// check if bank hols   /////////////////////////////////////////////////////////
$query = "SELECT * FROM bankhols"; $result_id = mysql_query ($query, $conn_id); 
$bankhol_result = mysql_query($query,$conn_id) or die (mysql_error()); while ($bhrow = mysql_fetch_array($bankhol_result)) { extract($bhrow);
$temp_ar=explode("-",$duedate); $spltime_ar=explode(" ",$temp_ar['2']); $temptime_ar=explode(":",$spltime_ar['1']); 
if (($temptime_ar['0']=='')||($temptime_ar['1']=='')||($temptime_ar['2']=='')){$temptime_ar['0']='0';$temptime_ar['1']='0';$temptime_ar['2']='0';} 
$day=$spltime_ar['0']; $month=$temp_ar['1']; $year=$temp_ar['0']; $hour=$temptime_ar['0']; $minutes=$temptime_ar['1'];
$deldatetocheck=$year.'-'.$month.'-'.$day; 
if ($deldatetocheck==$bhrow['bankholdate']) {
 echo '<span class="bankhol"> '. $bhrow['bankholcomment'].'</span>'; 
}
} // loop 



 echo '
 </td>
 <td>
 ';
  
 
if (($ShipPC) or ($row['tofreeaddress'])) {
if ( $globalprefrow["inaccuratepostcode"]=='1'){ echo ' ';


echo '<a '; 
if ($CyclistID=='1') { echo ' style="'.$globalprefrow["highlightcolourno"].'"'; } 

echo ' target="_blank" class="newwin" href="https://www.google.com/maps/?q='.$tofreeaddresslink.'+'. $linkShipPC.'">'.$row['tofreeaddress'].' '. $ShipPC.'</a> '; 

} else {

 echo $row['tofreeaddress'];

 
if ($ShipPC)  {

echo ' <a'; if ($CyclistID=='1') { echo ' style="'.$globalprefrow["highlightcolourno"].'"'; } 
 echo ' target="_blank" class="newwin" href="https://www.google.com/maps/?q='; echo $linkShipPC.'">'. $ShipPC.'</a> '; 

}

 } }


 
if ($row['invoicetype']=='4') { echo " <span style='". $globalprefrow['courier6']."'>Payment on Drop   &". 
$globalprefrow['currencysymbol'].($row['FreightCharge']+$row['vatcharge']).'</span>'; } 





 echo ' &nbsp; 
 </td> 
 </tr> 
 </tbody>
 </table> 
</div>
';

$seeifnextday=date('z', strtotime($nextactiondate));


} // ends end of individual job, ie $row['variable']









$sql = "SELECT ID FROM Orders WHERE `Orders`.`status` <70 ";
$sql_result = mysql_query($sql,$conn_id) or die (mysql_error());
$totsumtot=mysql_affected_rows(); // echo $totsumtot.' jobs undelivered '. $sumtot.' '.$numberofresults;

if ($totsumtot=='0') { echo '<div class="linevpad "></div><div class="ui-state-highlight ui-corner-all p15" > 
<p><strong> No future or active jobs found on system.</strong>.</p></div><div class="vpad "></div>'; }



if ($page=="showall") { echo '<div class="linevpad "></div><div class="ui-state-highlight ui-corner-all p15" > 
<p>	<strong> All '.$sumtot.' jobs displayed</strong>.</p></div><div class="vpad "></div>'; }
 
 
 if ($totsumtot>$sumtot) { echo '<div class="linevpad "></div><div class="ui-state-highlight ui-corner-all p15" > 
<form action="#" method="post"><input type="hidden" name="page" value="showall" /><p><strong> Next '.$numberofresults.
' jobs displayed out of a total of '.$totsumtot.'.<button type="submit" >Show all jobs</button></strong></p></form></div>';} 
  
  
  echo '<div class="linevpad"></div></div>
  <script type="text/javascript">
  $(document).ready(function() {
  '.$javascript. '
  });
  </script> ';
  
 
	include "footer.php";
  
  echo ' </body></html> ';
 mysql_close(); 