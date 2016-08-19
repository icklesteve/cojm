<?php

/*
    COJM Courier Online Operations Management
	checkdep.php - New Job Ajax Helper for Clients with Departments
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


$html='';
$newjobdepid = $_POST['newjobdepid'];
include "C4uconnect.php";
// echo'Department selected: '.$newjobdepid;
$query="SELECT * FROM clientdep WHERE depnumber = '$newjobdepid' LIMIT 1"; 
$result=mysql_query($query, $conn_id); $row=mysql_fetch_array($result);
$newjobclientid=$row['associatedclient'];
$depname=$row['depname'];
$query="SELECT * FROM Clients WHERE Clients.CustomerID = '$newjobclientid' LIMIT 1"; 
$result=mysql_query($query, $conn_id); $clientrow=mysql_fetch_array($result);
$query = "SELECT depnumber, depname FROM clientdep WHERE associatedclient = '$newjobclientid' AND isactivedep='1' ORDER BY depname"; 
$result_id = mysql_query ($query, $conn_id) or mysql_error(); $sumtot=mysql_affected_rows();








$tablecost='0'; $todayDate = date("Y-m-d");// current date
$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "");
$dateamonthago = date('Y-m-d H:i:s', $dateOneMonthAdded);
$sql = "SELECT * FROM invoicing  
   WHERE (`invoicing`.`paydate` =0 ) 
   AND (`invoicing`.`invdue` < '$dateamonthago' ) 
   
   AND (`invoicing`.`invoicedept` = '$newjobdepid' ) ";
   
   
//   echo $sql;



$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 
$num_rows = mysql_num_rows($sql_result); if ($num_rows>'0') {
while ($row = mysql_fetch_array($sql_result)) { extract($row); $tablecost=$tablecost+$cost+$invvatcost; } 
$tablecost= number_format($tablecost, 2, '.', ','); 
$html=$html.' '.$num_rows.' Overdue Invoice'; if ($num_rows>'1') { $html=$html.'s '; }

$html=$html." <span title='Incl. VAT'>(&". $globalprefrow['currencysymbol']. $tablecost.')</span> ';

$itablecost='0'; 
$isql = "SELECT * FROM invoicing  
   WHERE (`invoicing`.`paydate` =0 )
   AND (`invoicing`.`invoicedept` = '$newjobdepid' ) ";
   
   
 //  echo $isql;



$isql_result = mysql_query($isql,$conn_id) or die(mysql_error()); 
$inum_rows = mysql_num_rows($isql_result); $inum_rows=$inum_rows-$num_rows;
if ($inum_rows>'0') {
while ($row = mysql_fetch_array($isql_result)) { extract($row); $itablecost=$itablecost+$cost+$invvatcost; } 
$itablecost= $itablecost-$tablecost;
$itablecost= number_format($itablecost, 2, '.', ','); 
$html=$html.' + '.$inum_rows." in date <span title='Incl. VAT'>(&". $globalprefrow['currencysymbol']. $itablecost.')</span>. ';
} else {// ends check for in time
// $html=$html. '.';
}
} // ends check for overdue







echo '<input type="hidden" name="newjobdepid" value="'.$newjobdepid.'">

<div class="fs">
<div class="fsl"> '. $sumtot.' Deps </div> 
 <div class="left">
 <select class="ui-state-default ui-corner-all" id="newjobselectcheckdep" name="newjobselectcheckdep" >
 <option value="">Select one...</option>';
while (list ($CustomerIDlist, $CompanyName) = mysql_fetch_row ($result_id)) { 
$CompanyName = htmlspecialchars($CompanyName); 
echo '
<option ';
if ($CustomerIDlist==$newjobdepid) { echo ' selected ';    }
echo 'value="'.$CustomerIDlist.'">'.$CompanyName.'</option>';} echo '</select> ';

echo ' </div> ';

echo '<div id="afterdepselect" class="left"> 
<a href="new_cojm_department.php?depid='.$newjobdepid.'" target="_blank" class="showclient showclientdep" 
title="'. $depname.' Details"> &nbsp; </a>
'.$html.'

</div> ';


echo '</div>
<div class="cbbnewjobl">
';



/////////////     STARTS  SERVICE  ///////////////////////////////////////////////
$servicehtml= '<div class="fs">
<div class="fsl">Service </div>';


$fromclient='';

$query = "SELECT ServiceID, 
Service , slatime, sldtime
FROM Services 
WHERE activeservice='1' 
ORDER BY serviceorder DESC, ServiceID ASC"; 
$sresult_id = mysql_query ($query, $conn_id); 

if (($row['depservice']=='') and  (trim($clientrow['defaultservice'])))  {
$defaultservice=trim($clientrow['defaultservice']);
$fromclient=' From Client ';
} else { $defaultservice=trim($row['depservice']); }

// echo ' dep service is '.$row['depservice'].' client service is  '.$clientrow['defaultservice'].' default is '.$defaultservice;

$servicehtml=$servicehtml. ("<select class=\"jlabel ui-state-default ui-corner-left\" name=\"serviceID\">"); 
while (list ($ServiceID, $Service, $slatime, $sldtime) = mysql_fetch_row ($sresult_id)) {	
$ServiceID = htmlspecialchars ($ServiceID);	
$Service = htmlspecialchars ($Service); 

// echo $slatime.$sldtime;

$servicehtml=$servicehtml.  " <option ";
if ($defaultservice == $ServiceID){
$servicehtml=$servicehtml.  " SELECTED ";
$thisslatime=$slatime;
$thissldtime=$sldtime;
}
$servicehtml=$servicehtml.  ("value=\"$ServiceID\">$Service</option>"); } $servicehtml=$servicehtml.  " </select> ".$fromclient."</div> ";

// $ifcbbservice = mysql_result(mysql_query("SELECT chargedbycheck FROM Services WHERE ServiceID = $defaultservice LIMIT 0,1 ", $conn_id), 0);
// echo ' if cbb : '.$ifcbbservice;

// echo $thisslatime;
// echo $thissldtime;
/////////     ENDS    SERVICE ///////////////////////////////////////////////




if ($row['deppassword']) { // echo ' Password Found';
echo '<div class="fs">
<div class="fsl"> Password </div>
<span class="blinking">'.$row['deppassword'].'</span></div>'; }

// echo $clientrow['defaultrequestor'];
$fromclient='';
if ((trim($row['deprequestor'])=='') and (trim($clientrow['defaultrequestor'])))  { 
$requestor=$clientrow['defaultrequestor']; 
$fromclient=' (From Client default) ';

} else { $requestor=$row['deprequestor']; }


echo '<div class="fs">
<div class="fsl">  Requested By </div>
<input type="text" class="caps ui-state-default ui-corner-all"  name="requestedby" value="'.$requestor.'">'.$fromclient.'</div>';
$fromclient='';
$sql="SELECT * FROM cojm_favadr WHERE  favadrclient = '$newjobclientid' AND favadrisactive ='1' "; $sql_result = mysql_query($sql,$conn_id);
$sumtot=mysql_affected_rows(); 

// if ($sumtot>'0')  {


if ((trim($row['depdeffromft'])=='') and (trim($clientrow['defaultfromtext']))) {
$from=trim($clientrow['defaultfromtext']);
$fromclient=' (From Client default) ';
} else { 
$from=trim($row['depdeffromft']);
}

// echo ' Client : ' .$clientrow['defaultfromtext'].' Department : '.$row['depdeffromft'].' Used : '.$from;


echo '<div class="fs">
<div class="fsl"> Collection </div>
<select name="frombox" id="frombox"><option value=""> Select One ...</option>';
while ($favrow = mysql_fetch_array($sql_result)) { extract($favrow);
echo '<option ';
if ($from==$favrow['favadrid']) { echo ' selected '; }
echo 'value="'.$favrow['favadrid'].'">'.$favrow['favadrft'].', '.$favrow['favadrpc'].'</option>'; 
}



echo '</select>


'.$fromclient.'</div>

<div class="fs">
<div class="fsl"> Delivery </div>
<select name="tobox" id="tobox"><option value=""> Select One ...</option>';

$fromclient='';


if ((trim($row['depdeftoft'])=='') and (trim($clientrow['defaulttotext']))) {
$to=trim($clientrow['defaulttotext']);
$fromclient=' (From Client default) '; } else { 
$to=trim($row['depdeftoft']); }
// echo ' Client : ' .$clientrow['defaulttotext'].' Department : '.$row['depdeftoft'].' Used : '.$to;
$sql_result = mysql_query($sql,$conn_id);
while ($favrow = mysql_fetch_array($sql_result)) { extract($favrow);
echo ' <option ';
if ($to==$favrow['favadrid']) { echo ' selected '; }
echo ' value="'.$favrow['favadrid'].'">'.$favrow['favadrft'].', '.$favrow['favadrpc'].'</option>';
}
echo '</select>



 <button name="showallfav" title="All Favourites" id="showallfav" type="button" class="showallfav"> &nbsp; </button> 


'.$fromclient.'</div>';


// } // ends check for favourites

$fromclient='';


echo '<div class="fs">
<div class="fsl"> </div><input type="text" placeholder="Instructions" class="w490 caps ui-state-default ui-corner-all" name="jobcomments" /> </div>'. $servicehtml.
'
<div class="fs">
<div class="fsl"> PU Due </div>';
echo '<select class=" ui-state-default ui-corner-left" name="ajcolldue">';
echo '<option value="now">Now </option>';
echo '<option ';  if ($thisslatime=='00:15:00') { echo 'SELECTED'; } echo ' value="15">15 mins</option>';
echo '<option ';  if ($thisslatime=='00:30:00') { echo 'SELECTED'; } echo ' value="30">30 mins</option>';
echo '<option ';  if ($thisslatime=='00:45:00') { echo 'SELECTED'; } echo ' value="45">45 mins</option>';
echo '<option ';  if ($thisslatime=='01:00:00') { echo 'SELECTED'; } echo ' value="60">1 hour</option>';
echo '<option ';  if ($thisslatime=='01:30:00') { echo 'SELECTED'; } echo ' value="90">1 &amp;1/2 hours</option>';
echo '<option ';  if ($thisslatime=='02:00:00') { echo 'SELECTED'; } echo ' value="120">2 hours</option>';
echo '<option ';  if ($thisslatime=='03:00:00') { echo 'SELECTED'; } echo ' value="180">3 hours</option>';
echo '<option ';  if ($thisslatime=='00:00:08') { echo 'SELECTED'; } echo ' value="next8">Next 8AM  </option>';
echo '<option ';  if ($thisslatime=='00:00:09') { echo 'SELECTED'; } echo ' value="next9">Next 9AM  </option>';
echo '<option ';  if ($thisslatime=='00:00:10') { echo 'SELECTED'; } echo ' value="next10">Next 10AM  </option>';
echo '<option ';  if ($thisslatime=='00:00:11') { echo 'SELECTED'; } echo ' value="next11">Next 11AM </option>';
echo '<option ';  if ($thisslatime=='00:00:12') { echo 'SELECTED'; } echo ' value="next12">Next 12PM  </option>';
echo '<option ';  if ($thisslatime=='00:00:13') { echo 'SELECTED'; } echo ' value="next13">Next 1PM  </option>';
echo '<option ';  if ($thisslatime=='00:00:14') { echo 'SELECTED'; } echo ' value="next14">Next 2PM  </option>';
echo '<option ';  if ($thisslatime=='00:00:15') { echo 'SELECTED'; } echo ' value="next15">Next 3PM </option>';
echo '<option ';  if ($thisslatime=='00:00:16') { echo 'SELECTED'; } echo ' value="next16">Next 4PM </option>';
echo '<option ';  if ($thisslatime=='00:00:17') { echo 'SELECTED'; } echo ' value="next17">Next 5PM </option>';
echo '<option ';  if ($thisslatime=='00:00:18') { echo 'SELECTED'; } echo ' value="next18">Next 6PM </option>';
echo '</select></div>';

echo '<div class="fs">
<div class="fsl"> Drop Due </div>';
echo '<select class="jlabel ui-state-default ui-corner-left" name="ajdelldue">';
echo '<option value="now">Now </option>';
echo '<option ';  if ($thissldtime=='00:15:00') { echo 'SELECTED'; } echo ' value="15">15 mins</option>';
echo '<option ';  if ($thissldtime=='00:30:00') { echo 'SELECTED'; } echo ' value="30">30 mins</option>';
echo '<option ';  if ($thissldtime=='00:45:00') { echo 'SELECTED'; } echo ' value="45">45 mins</option>';
echo '<option ';  if ($thissldtime=='01:00:00') { echo 'SELECTED'; } echo ' value="60">1 hour</option>';
echo '<option ';  if ($thissldtime=='01:30:00') { echo 'SELECTED'; } echo ' value="90">1 &amp;1/2 hours</option>';
echo '<option ';  if ($thissldtime=='02:00:00') { echo 'SELECTED'; } echo ' value="120">2 hours</option>';
echo '<option ';  if ($thissldtime=='03:00:00') { echo 'SELECTED'; } echo ' value="180">3 hours</option>';
echo '<option ';  if ($thissldtime=='00:00:08') { echo 'SELECTED'; } echo ' value="next8">Next 8AM  </option>';
echo '<option ';  if ($thissldtime=='00:00:09') { echo 'SELECTED'; } echo ' value="next9">Next 9AM  </option>';
echo '<option ';  if ($thissldtime=='00:00:10') { echo 'SELECTED'; } echo ' value="next10">Next 10AM  </option>';
echo '<option ';  if ($thissldtime=='00:00:11') { echo 'SELECTED'; } echo ' value="next11">Next 11AM </option>';
echo '<option ';  if ($thissldtime=='00:00:12') { echo 'SELECTED'; } echo ' value="next12">Next 12PM  </option>';
echo '<option ';  if ($thissldtime=='00:00:13') { echo 'SELECTED'; } echo ' value="next13">Next 1PM  </option>';
echo '<option ';  if ($thissldtime=='00:00:14') { echo 'SELECTED'; } echo ' value="next14">Next 2PM  </option>';
echo '<option ';  if ($thissldtime=='00:00:15') { echo 'SELECTED'; } echo ' value="next15">Next 3PM </option>';
echo '<option ';  if ($thissldtime=='00:00:16') { echo 'SELECTED'; } echo ' value="next16">Next 4PM </option>';
echo '<option ';  if ($thissldtime=='00:00:17') { echo 'SELECTED'; } echo ' value="next17">Next 5PM </option>';
echo '<option ';  if ($thissldtime=='00:00:18') { echo 'SELECTED'; } echo ' value="next18">Next 6PM </option>';
echo '</select></div>

</div>
<div class="cbbnewjobr">
';



$query = "SELECT chargedbybuildid, 
cbbname, 
cbbcost 
FROM chargedbybuild 
WHERE cbbcost <> '0.00'
AND chargedbybuildid > 3
ORDER BY cbborder"; 
$result_id = mysql_query ($query, $conn_id); 

$i='1';


while (list ($chargedbybuildid, $cbbname, $cbbcost) = mysql_fetch_row ($result_id)) { 
$cbbname = htmlspecialchars ($cbbname); 
echo '<div class="fs">
<div class="fsl"> <input type="checkbox" name="chkcbb'.$chargedbybuildid.'" value="1"></div> '.$cbbname.' </div>';
$i=$i+'1';
} 


echo '<div class="fs">
<div class="fsl"> </div>
<button class="newjobsubmit" type="submit"> Create New Job </button></div>
</div>
<div class="clear"> </div>
<script type="text/javascript">
$(document).ready(function() {
    
	$(function() {
		$( "#frombox" ).frombox();
		$( "#toggle" ).click(function() {
			$( "#frombox" ).toggle();
		});
	});
	$(function() {
		$( "#tobox" ).tobox();
		$( "#toggle" ).click(function() {
			$( "#tobox" ).toggle();
		});
	});
		$(function() {
		$( "#newjobselectcheckdep" ).newjobselectcheckdep();
		$( "#toggle" ).click(function() {
			$( "#newjobselectcheckdep" ).toggle();
		});
	});		
	});
</script>';
?>