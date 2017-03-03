<?php 

/*
    COJM Courier Online Operations Management
	new_cojm_department.php - Edit Clients Departments
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
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$title = "COJM";
?><!doctype html>
<html lang="en"><head>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height" >
<?php
echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>';
?>
<title><?php print ($title); ?> Department Details</title>
</head><body>
<?php 
$adminmenu=1;

$tempjs='';
$dlt='';
$dlm='';

$filename="new_cojm_client.php";

$hasforms='1';

include "changejob.php";
include "cojmmenu.php"; 

echo '<div class="Post Spaceout">';


if (isset($_POST['clientid'])) { $clientid=trim($_POST['clientid']); } else { $clientid=''; }
if (!$clientid) { if (isset($_GET['clientid'])) { $clientid=trim($_GET['clientid']); } }




if (isset($_GET['depid'])) { $posteddepid=$_GET['depid']; } else { $posteddepid=''; }
if (isset($_POST['depid'])) { $posteddepid=$_POST['depid']; }

if ($posteddepid) {

$query = "SELECT associatedclient FROM clientdep  WHERE depnumber=$posteddepid LIMIT 0,1"; 

$clientid = mysql_result(mysql_query($query, $conn_id), 0); 
// move tab to correct department if #tab unset

echo '
<script>
$(document).ready(function(){
window.location.href = "#tabs-'.$posteddepid.'";	
});
</script>
';

}













?>
<div class="ui-state-highlight ui-corner-all p15" >
<p>
<form action="#" method="post">
<input type="hidden" name="page" value="selectclientdepartment" >
<?php

$query = "SELECT CustomerID, CompanyName FROM Clients WHERE isdepartments='1' ORDER BY CompanyName"; 
$result_id = mysql_query ($query, $conn_id); 
$sumtotclients=mysql_affected_rows();

echo '<input type="hidden" name="page" value="selectclient">
<select class="ui-state-default ui-corner-all"  id="combobox" name="clientid" ><option value="">Select one...</option>';
 while (list ($CustomerIDlist, $CompanyName) = mysql_fetch_row ($result_id)) { $CustomerID = htmlspecialchars ($CustomerID); 
$CompanyName = htmlspecialchars ($CompanyName); print"<option "; if ($CustomerIDlist == $clientid) {
echo "SELECTED "; 

$thiscompanyname=$CompanyName;

} ; 
echo ' value="'.$CustomerIDlist.'">'.$CompanyName.'</option>';} echo '</select> 
<button type="submit"> Select Client </button> 
</form>';




if (isset($thiscompanyname)) {
echo '
<form action="new_cojm_client.php" method="post">
<input type="hidden" name="page" value="selectclientdepartment" >
<input type="hidden" name="formbirthday" value="'.date("U").'">
<input type="hidden" name="clientid" value="'.$clientid.'">
<button type="submit"> Switch to '.$thiscompanyname.' Core </button>
</form>';
}


if ($clientid) {
$sql = "SELECT * FROM Clients WHERE CustomerID = '$clientid' ";
$sql_result = mysql_query($sql,$conn_id); 
$clrow=mysql_fetch_array($sql_result);


echo ' <br />
<form action="#" method="post">
<input type="hidden" name="page" value="createnewdep" />
<input type="hidden" name="clientid" value="'.$clientid.'" />
<button type="submit"> Create new Department </button>
<input type="hidden" name="formbirthday" value="'. date("U").'">
Name : <input class="ui-state-default ui-corner-all pad" type="text" name="newdepname" size="15" />
</form>';


}
 
 echo '</p> 
</div>

<div class="vpad"></div>
<div class="line"></div>
<div class="vpad"></div>';

if ($clientid)  {

echo '<form action="new_cojm_department.php#" method="post">
<input type="hidden" name="clientid" value="'. $clientid.'">
<input type="hidden" name="formbirthday" value="'. date("U").'">
<input type="hidden" name="page" value="editdepartment" >';

$sql = "SELECT * FROM clientdep WHERE associatedclient = '$clientid' ORDER BY isactivedep DESC , depnumber DESC  ";

// echo '<br />'.$sql;

$sql_result = mysql_query($sql,$conn_id); 
$sumtot=mysql_affected_rows();


while ($row = mysql_fetch_array($sql_result)) {
     extract($row);

// echo ' <div class="deptlist"> ';



$dlt=$dlt. '
<div id="tabs-'.$row['depnumber'].'" class="p15"> 

<fieldset><label >

Name </label><input type="text" class="ui-state-default ui-corner-all pad" name="depname'. $row['depnumber'].'" value="'. $row['depname'].'">

Is Active : <input type="checkbox" name="isactivedep'.$row['depnumber'].'" value="1" ';
 if ($row['isactivedep']>0) { $dlt=$dlt. 'checked';} 
 
 $dlt=$dlt. ' ></fieldset> 
<fieldset><label > Password </label> <input type="text" class="ui-state-default ui-corner-all clearField pad" placeholder="Password " 
name="deppassword'.$row['depnumber'].'" value="'.$row['deppassword'].'"> 

</fieldset>
 <div class="line"></div>
 <fieldset><label >
Phone </label><input type="text" class="ui-state-default ui-corner-all clearField pad" size="20" placeholder="'.$clrow['PhoneNumber'].'" 
name="depphone'. $row['depnumber'].'" value="'.$row['depphone'].'">';

 if ($row['depphone'])  { $dlt=$dlt. ' Dep Tel : '.$row['depphone'].'. '; }  


$dlt=$dlt.' </fieldset>
 <fieldset><label >
Email </label> <input type="text" class="ui-state-default ui-corner-all clearField pad" size="60" placeholder="'.$clrow['EmailAddress'].'" 
name="depemail'. $row['depnumber'].'" validation="required email" value="'.$row['depemail'].'">
</label>
 ';
 
 

 
 $dlt=$dlt.'
 <div class="line"></div>
 ';
 

 
 $dlt=$dlt. ' 
 
  <fieldset><label >
  
 Address </label> <input type="text" placeholder="'.$clrow['Address'].'" class="ui-state-default ui-corner-all clearField pad" size="40" 
 name="depaddone'. $row['depnumber'].'" value="'. $row['depaddone'].'"> 
  <input type="text" placeholder="'. $clrow['Address2'].'" class="ui-state-default ui-corner-all clearField pad" size="40" 
  name="depaddtwo'. $row['depnumber'].'" value="'. $row['depaddtwo'].'"> 
  </fieldset>
  
   <fieldset><label > &nbsp; </label>
 <input type="text" placeholder="'. $clrow['City'].'" class="ui-state-default ui-corner-all clearField pad" size="20" 
 name="depaddthree'. $row['depnumber'].'" value="'. $row['depaddthree'].'">
 
 <input type="text" class="ui-state-default ui-corner-all clearField pad" size="20" placeholder="'. $clrow['County'].'" 
 name="depaddfour'. $row['depnumber'].'" value="'. $row['depaddfour'].'">
 
 <input type="text" class="ui-state-default ui-corner-all clearField pad" size="10" placeholder="'. $clrow['CountryOrRegion'].'" 
 name="depaddfive'. $row['depnumber'].'" value="'. $row['depaddfive'].'">
 </fieldset>
 
   <fieldset><label > Postcode </label>
  <input type="text" class="ui-state-default ui-corner-all clearField pad" size="12" placeholder="'. $clrow['Postcode'].'" 
 name="depaddsix'. $row['depnumber'].'"  value="'. $row['depaddsix'].'"> </fieldset>';

 
 // $clrow['Address'].', '. $clrow['Address2'].'. '. $clrow['City'].', '. $clrow['County'].', '. $clrow['CountryOrRegion'].'. '. $clrow['Postcode'].'<br />'; 

 $dlt=$dlt.' <fieldset><label > &nbsp; </label>';
 
 if ($row['depname']) { $dlt=$dlt. $row['depname'].', '; } 
$dlt=$dlt. $thiscompanyname.', ';
if ($row['depaddone']) { $dlt=$dlt. $row['depaddone'].', '; } else { $dlt=$dlt. $clrow['Address'].', '; }
if ($row['depaddtwo']) { $dlt=$dlt. $row['depaddtwo'].', '; } else { $dlt=$dlt. $clrow['Address2'].', '; }
if ($row['depaddthree']) { $dlt=$dlt. $row['depaddthree'].', '; } else { $dlt=$dlt. $clrow['City'].', '; }
if ($row['depaddfour']) { $dlt=$dlt. $row['depaddfour'].', '; } else { $dlt=$dlt. $clrow['County'].', '; }
if ($row['depaddfive']) { $dlt=$dlt. $row['depaddfive'].', '; } else { $dlt=$dlt. $clrow['CountryOrRegion'].', '; }
if ($row['depaddsix']) {
$PC=$row['depaddsix'];
$PC= str_replace(" ", "+", "$PC", $count);
$dlt=$dlt. ' <a title="View in Maps" target="_blank" href="https://www.google.co.uk/maps?q='. $PC. '">'. $row['depaddsix'].'</a>.';
 
 } else {
 
 $PC=$clrow['Postcode'];
$PC= str_replace(" ", "+", "$PC", $count);
$dlt=$dlt. ' <a title="View in Maps" target="_blank" href="https://www.google.co.uk/maps?q='. $PC. '">'. $clrow['Postcode'].'</a>.';
 
 
 
 }
 


 $dlt=$dlt. '</fieldset> <div class="line"></div>

  <fieldset><label > Requestor </label> <input type="text" class="ui-state-default ui-corner-all clearField pad" size="20" placeholder="Requested By " name="deprequestor'. 
 $row['depnumber'].'" value="'.$row['deprequestor'].'"> 
</fieldset>
 <fieldset><label >Default Service </label>  ';


  
   ////////////   SERVICE           ////////////////
   
   
   
   
   
   
   
  $clfav = $clrow['defaultservice'];
   
   
  $isfavservice = mysql_result(mysql_query("
SELECT Service 
FROM Services 
WHERE activeservice='1'
AND ServiceID = '$clfav'
LIMIT 1
 ", $conn_id), 0);     
   
// echo 'Service : ';
// echo '<input type="hidden" name="numberitems" value="'. $row["numberitems"].'">'; 
$query = "
SELECT ServiceID, 
Service 
FROM Services 
WHERE activeservice='1' 
ORDER BY serviceorder DESC, ServiceID ASC"; 
$result_id = mysql_query ($query, $conn_id); 
$dlt=$dlt. ("<select class=\"ui-state-default ui-corner-left\" name=\"depservice".$row['depnumber']."\">"); 

$dlt=$dlt. '<option value="">Use '.$thiscompanyname.' default - '.$isfavservice.'</option>';

while (list ($ServiceID, $Service) = mysql_fetch_row ($result_id)) {	
$ServiceID = htmlspecialchars ($ServiceID);	
$Service = htmlspecialchars ($Service); $dlt=$dlt. ("<option "); 
{	if ($row['depservice'] == $ServiceID) $dlt=$dlt. " SELECTED "; }	
$dlt=$dlt. ("value=\"$ServiceID\">$Service</option>"); } $dlt=$dlt. ("</select> </fieldset>"); 

/////////     ENDS    SERVICE ///////////////////////////////////////////////  
  
  
//  echo $clrow['defaultfromtext'];
  $deftotxt=$clrow['defaultfromtext'];
  $deffromtxt=$clrow['defaulttotext'];
  
  
  
// $query = "SELECT favadrid, favadrft, favadrpc FROM cojm_favadr WHERE favadrisactive='1' AND favadrclient = '$clientid' "; 
  
  
  

 
 $query = "SELECT favadrid, favadrft, favadrpc 
FROM cojm_favadr 
WHERE favadrisactive='1'
AND favadrclient = '$clientid' "; 
$result_id = mysql_query ($query, $conn_id); 
 
 while (list ($favadrid, $favadrft, $favadrpc) = mysql_fetch_row ($result_id)) {
$isfavadrft = htmlspecialchars ($favadrft);	
$isfavadrpc = htmlspecialchars ($favadrpc); }
 
 
 
  
  
  
  
    $isfavadrff = mysql_result(mysql_query("
SELECT favadrft 
FROM cojm_favadr 
WHERE favadrisactive='1'
AND favadrclient = '$clientid'
AND favadrid = '$deftotxt'
 LIMIT 1
 ", $conn_id), 0);
 
  
    $isfavadrpf = mysql_result(mysql_query("
SELECT favadrpc 
FROM cojm_favadr 
WHERE favadrisactive='1'
AND favadrclient = '$clientid'
AND favadrid = '$deftotxt'
 LIMIT 1
 ", $conn_id), 0);
 
  
  
  
  
  
  
  
 $dlt=$dlt.' <fieldset><label >Default PU </label>';
$query = "SELECT favadrid, favadrft, favadrpc 
FROM cojm_favadr 
WHERE favadrisactive='1'
AND favadrclient = '$clientid' "; 
$result_id = mysql_query ($query, $conn_id); 
 $dlt=$dlt. ("<select class=\"ui-state-default ui-corner-left\" name=\"depdeffromft". $row['depnumber']."\">"); 
 $dlt=$dlt. '<option value="">Use '.$thiscompanyname.' default : '.$isfavadrff.' '.$isfavadrpf.'</option>';
while (list ($favadrid, $favadrft, $favadrpc) = mysql_fetch_row ($result_id)) {
$favadrft = htmlspecialchars ($favadrft);	
$favadrpc = htmlspecialchars ($favadrpc); 
 $dlt=$dlt. ("<option "); 
 if ($row['depdeffromft'] == $favadrid) {  $dlt=$dlt. " SELECTED "; }	
 $dlt=$dlt. ("value=\"$favadrid\">$favadrft, $favadrpc</option>"); }  $dlt=$dlt. (" </select> </fieldset> "); 
/////////     ENDS  DEFAULT PU ///////////////////////////////////////////////










 $dlt=$dlt. ' <fieldset><label > Default Drop </label>';
$query = "SELECT favadrid, favadrft, favadrpc 
FROM cojm_favadr 
WHERE favadrisactive='1'
AND favadrclient = '$clientid' "; 
$result_id = mysql_query ($query, $conn_id); 
 $dlt=$dlt. ("<select class=\"ui-state-default ui-corner-left\" name=\"depdeftoft".$row['depnumber']."\">"); 
 $dlt=$dlt. '<option value="">Use '.$thiscompanyname.' default : '.$isfavadrft.' '.$isfavadrpc.'</option>';
 
while (list ($favadrid, $favadrft, $favadrpc) = mysql_fetch_row ($result_id)) {
$favadrft = htmlspecialchars ($favadrft);	
$favadrpc = htmlspecialchars ($favadrpc); 
 $dlt=$dlt. ("<option "); 
if ($row['depdeftoft'] == $favadrid) {  $dlt=$dlt. " SELECTED "; }	
 $dlt=$dlt. ("value=\"$favadrid\">$favadrft, $favadrpc</option>"); }  $dlt=$dlt. (" </select> </fieldset> "); 
/////////     ENDS  DEFAULT PU ///////////////////////////////////////////////


   
  
$dlt=$dlt.'  
 <fieldset><label >Priv. Comments</label>
  <textarea class="normal ui-state-default ui-corner-all clearField pad" style="width: 65%; outline: none;" placeholder="Department Comments not shown to client" 
name="depcomment'. $row['depnumber'].'" >'. $row['depcomment'].'</textarea>  </fieldset>


<fieldset><label >


 Department ID </label> '.$row['depnumber'].'</fieldset>
 


';


$old=' 
 <fieldset><label >
 
JoomlaID </label> <input type="text" class="ui-state-default ui-corner-all clearField pad" placeholder="JoomlaID" size ="5" name="depjoom'.
$row['depnumber'].'" value="'.$row['depjoom'].'"> 
 Test Login '.$globalprefrow['testjoomlalogin'].' 
 
 
 </fieldset>
 
 ';
 
 
 $dlt.='
 
  <div class="line"></div>
  <fieldset><label > <button formaction="#tabs-'.$row['depnumber'].'" type="submit" > Edit Departments </button></label>'.$sumtot.' Department(s) </fieldset>
';


$dlt=$dlt. ' </div>';
  
  
  $dlm=$dlm.'<li><a href="#tabs-'.$row['depnumber'].'">'.$row['depname'].'</a></li>';
  
  
  } ////  ends department loop 
  
  
  echo '<div id="tabs"><ul>';
  
  echo $dlm.'</ul>';
  echo $dlt. '  </div> </form>';



} // ends check for client selected or new client


// echo $sumtotclients.' clients found using departments.';

if ($sumtotclients=='0') { echo '
			<div class="ui-state-error ui-corner-all p15"> 
				<p><span class="ui-icon ui-icon-alert p15" ></span> 
				<strong>No Clients set up with Departments</strong></p>
			</div><br />';
}

echo '</div>';


echo '<script type="text/javascript">
$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+25));

	
	$(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
		$( "#combobox" ).toggle();	});	});
	});
</script>';



include "footer.php";

 ?>
<script type="text/javascript">
		$(function() {
		$(function() {	$("#tabs").tabs();	});
		
			$(function(){ $(".normal").autosize();	});
		
		$( "#combobox" ).combobox();
	
	});

	
	
	function comboboxchanged() {}
	

	
	</script>
</body></html>