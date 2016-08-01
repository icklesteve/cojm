<?php 

/*
    COJM Courier Online Operations Management
	cyclist.php - Edit Rider Details & Settings
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

include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include "changejob.php";
$thiscyclist='';
$showinactive='';
if (isset($_POST['showinactive'])) { $showinactive=trim($_POST['showinactive']); } 
if (isset($_GET['showinactive'])) { $showinactive=trim($_GET['showinactive']); }
if (isset($_POST['thiscyclist'])) { $thiscyclist=$_POST['thiscyclist']; } 
if (isset($_GET['thiscyclist'])) { $thiscyclist=trim($_GET['thiscyclist']); }
if (!$thiscyclist) { $thiscyclist='1'; }
if (isset($newcyclistid)) { $thiscyclist=$newcyclistid; }

// if ($mobdevice) {} else {}


$tempwaitingcheck = mysql_result(mysql_query("
SELECT isactive FROM Cyclist WHERE CyclistID=$thiscyclist  LIMIT 1
", $conn_id), 0);

if ($tempwaitingcheck<>'1') { $showinactive='1'; }

if ($thiscyclist=='1') { $title="COJM : Select ".$globalprefrow['glob5'];} else { 


$query = "SELECT cojmname FROM Cyclist WHERE CyclistID=$thiscyclist  LIMIT 1";
$result_id = mysql_query ($query, $conn_id); 



while (list ($cojmname) = mysql_fetch_row ($result_id)) { 
$title=$globalprefrow['glob5'].' : '.$cojmname; } 

}

// $title="COJM : ".$globalprefrow['glob5'].' ';

// while (list ($cojmname) = mysql_fetch_row ($result_id)) { $title=$title.$cojmname; }

?><!DOCTYPE html> 
<html lang="en"> 
<head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >

<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; 

echo '<title>'.$title.'</title></head><body>';
$adminmenu = "1";
$hasforms='1';
$filename="cyclist.php";
include "cojmmenu.php"; 



echo '<div class="Post Spaceout">
<div class="ui-state-highlight ui-corner-all p15">
<form action="#" method="get" > ';





 if ($showinactive<>'1') {
 $query = "SELECT CyclistID, cojmname, isactive FROM Cyclist WHERE isactive='1' AND CyclistID >'1' ORDER BY CyclistID"; 
 $result_id = mysql_query ($query, $conn_id); 
} else { $query = "SELECT CyclistID, cojmname, isactive FROM Cyclist WHERE CyclistID >'1' ORDER BY CyclistID";
$result_id = mysql_query ($query, $conn_id); }
 print ("<select class=\"ui-state-highlight ui-corner-left\" id=\"combobox\" name=\"thiscyclist\">\n"); 
 
 echo ' <option value="">Select one...</option>';
 
 while (list ($CyclistID, $cojmname, $isactive) = mysql_fetch_row ($result_id))
 { print ("<option "); if ($CyclistID == $thiscyclist) {echo " SELECTED ";  }
 print ("value=\"$CyclistID\">$cojmname");
 
 if ($isactive>0) { echo ' ACTIVE'; } 
 
echo ("</option>\n"); } print '</select><button type="submit"> Select '.$globalprefrow['glob5'].' </button> ';

echo ' Show Inactive? <input type="checkbox" name="showinactive" value="1" '; 
 if ($showinactive>0) { echo 'checked';} 
echo ' />  </form>';










if ($thiscyclist=='1') {
echo '
<br />
 <form action="#" method="post" > 
<input type="hidden" name="formbirthday" value="'. date("U").'">
<input type="hidden" name="page" value="addnewcyclist">
<button type="submit"> Create New '.$globalprefrow['glob5'].' </button>

Name : <input class="ui-state-default ui-corner-all pad" type="text" name="CompanyName" size="15" />

</form>';


}


echo '
</div> ';


//  echo $thiscyclist;

if ($thiscyclist<>'1') {

$sql = "SELECT * FROM Cyclist WHERE CyclistID = '$thiscyclist' LIMIT 1";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$row=mysql_fetch_array($sql_result);


echo '

<form action="#" method="post">
<input type="hidden" name="page" value="editcyclistdetails">
<input type="hidden" name="formbirthday" value="'. date("U").'">
<input type="hidden" name="thiscyclist" value="'. $row['CyclistID'].'">

<div id="tabs">
<ul>
<li><a href="#tabs-1">Name</a></li> 
<li><a href="#tabs-2">Contact</a></li>
<li><a href="#tabs-4">ICE</a></li>';

if (!$mobdevice) {

echo '<li><a href="#tabs-3">Personal</a></li>';

}

echo '<li><a href="#tabs-5">'. $globalprefrow['globalshortname'].' Details</a></li>	';




echo '</ul>
	
<div id="tabs-1">

<fieldset><label class="fieldLabel"> COJM Name </label>
 <input style="    width: 150px;
    min-width: 150px;
    max-width: 300px;
    transition: width 0.25s;" class="ui-state-default ui-corner-all caps" placeholder="COJM Name"
  id ="cojmname"
	type="text" name="cojmname" size="20" data-autosize-input='."'".'{ "space": 40 }'."' "; 
echo '
	value="'. $row['cojmname'].'" />
</fieldset>

<fieldset><label class="fieldLabel"> Public Name </label>
 <input class="ui-state-default ui-corner-all caps" placeholder="Public Name" type="text" name="poshname" size="20" value="'. $row["poshname"].'" />
</fieldset>  


<fieldset><label class="fieldLabel"> Is Active </label><input type="checkbox" name="isactive" value="1" ';
 if ($row['isactive']>0) { echo 'checked';} 
  echo ' />  </fieldset> 
  
  
<fieldset><label class="fieldLabel"> Notes </label>
<textarea style="width: 65%; outline: none;" class="normal pad ui-state-default ui-corner-all caps" name="description" >'.$row['notes'].'</textarea>
</fieldset>

';



echo '</div>';

echo '<div id="tabs-2">';

echo '
<fieldset><label class="fieldLabel"> Mobile Number </label>
<input class="ui-state-default ui-corner-all caps" type="text" name="mobilenumber" size="20" value="'.$row['mobilenumber'].'" />
</fieldset>

<fieldset><label class="fieldLabel">  House Number </label> 
<input class="ui-state-default ui-corner-all caps" type="text" name="housenumber" size="5" value="'.$row['housenumber'].'" />
</fieldset>

<fieldset><label class="fieldLabel"> Street Name </label>
 <input class="ui-state-default ui-corner-all caps" type="text" name="streetname" size="20" value="'.$row['streetname'].'" />
 </fieldset>
 
 <fieldset><label class="fieldLabel"> City </label>
 <input class="ui-state-default ui-corner-all caps" type="text" name="city" size="20" value="'.$row['city'].'" />
 </fieldset>
 
 <fieldset><label class="fieldLabel"> Postcode </label>
 <input class="ui-state-default ui-corner-all caps" type="text" name="postcode" size="10" value="'.$row['postcode'].'">
 <a href="http://www.royalmail.com/postcode-finder" target="_blank" >Lookup Postcode</a>
 </fieldset>

</div>';

if (!$mobdevice) {

echo '
<div id="tabs-3">
<fieldset><label for="dob" class="fieldLabel"> Date of Birth </label>
<input class="ui-state-default ui-corner-all caps" type="text" value="';
 if ($row['DOB']>10) { echo date('d-m-Y', strtotime($row['DOB'])); }
echo '" id="dob" size="12" name="dob" > </fieldset>


<fieldset><label class="fieldLabel"> NI Number </label>
 <input class="ui-state-default ui-corner-all caps" type="text" name="ninumber" size="10" value="'. $row['ninumber'].'" />
</fieldset>

<fieldset><label class="fieldLabel"> Sort Code </label>
 <input class="ui-state-default ui-corner-all caps" type="text" name="sortcode" size="20" value="'.$row['sortcode'].'" />
 </fieldset>
 
 <fieldset><label class="fieldLabel"> Account Number </label>
 <input class="ui-state-default ui-corner-all caps" type="text" name="accountnum" size="20" value="'.$row['accountnum'].'" />
 </fieldset>
 
 <fieldset><label class="fieldLabel"> Bank Name </label>
 <input class="ui-state-default ui-corner-all caps" type="text" name="bankname" size="20" value="'.$row['bankname'].'" />
</fieldset>

</div>

';



} // ends mobile check



echo '<div id="tabs-4">

 <fieldset><label class="fieldLabel">  ICE Name </label>
 <input class="ui-state-default ui-corner-all caps" type="text" name="icename" size="30" value="'.$row['icename'].'" />
 </fieldset>
 
 <fieldset><label class="fieldLabel"> ICE Number </label> 
 <input class="ui-state-default ui-corner-all caps" type="text" name="icenumber" size="30" value="'.$row['icenumber'].'">
 </fieldset>
</div>



<div id="tabs-5">

<fieldset><label class="fieldLabel"> COJM ID </label> ' . $thiscyclist.'</fieldset>


<fieldset><label class="fieldLabel" for="contractstartdate"> Start Date </label> <input class="ui-state-default ui-corner-all caps" type="text" value="';
if ($row['contractstartdate']>10) {echo date('d-m-Y', strtotime($row['contractstartdate'])); } 
echo '" id="contractstartdate" size="12" name="contractstartdate"></fieldset> 





<input type="hidden" name="cyclistjoomlanumber" value="'. $row['cyclistjoomlanumber'].'">



<fieldset><label class="fieldLabel"> Tracking ID </label> ';
if (trim($row['trackerid'])=='') { echo $row['CyclistID']; } else { echo $row['trackerid']; } 
echo '</fieldset>

<fieldset><label class="fieldLabel"> Self-Hosted GPS Tracker </label> ';

echo $globalprefrow['httproots'].'/cojm/upload.php?trackid=';


if (trim($row['trackerid'])=='') { echo $row['CyclistID']; } else { echo $row['trackerid']; } 
echo '

</fieldset>

</div>';





echo '
<div class="line"> </div>
<fieldset><label class="fieldLabel">  
<button type="submit"> Edit '.$globalprefrow['glob5'].' Details </button>
</label></fieldset>

<div class="line"></div>

</div>'; // finishes tab







echo '<input type="hidden" name="trackerid" value="';if(trim($row['trackerid'])){echo $row['trackerid'];} else {echo $row['CyclistID'];} echo '" />';



if ($showinactive>'0') { echo '<input type="hidden" name="showinactive" value="1" />';} 









// echo $thiscyclist;


 

echo '
<script type="text/javascript">
$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+15));
	
		$(function() {
		$("#tabs").tabs();	});
	
		$(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
		$( "#combobox" ).toggle();	});	});
	
	$("cojmname").autosizeInput();

	$(function() {
		var dates = $( "#dob" ).datepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
            dateFormat: "dd-mm-yy",
			changeMonth:false,
		yearRange: "1940:2020"
		});

		var dates = $( "#contractstartdate" ).datepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
            dateFormat: "dd-mm-yy",
			changeMonth:false
		});
	});
	});
	</script>';
	
	
	
	
	echo '</form>';

}


echo '<br /></div>';
	
	
	

 echo '<script> 
 
 $(document).ready(function() {
 ';
 
  if ($thiscyclist=='1') {
 
echo '  setTimeout( function() { $("#comboboxbutton").click() }, 100 ); '; }
 
 echo '
 
 $(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
		$( "#combobox" ).toggle();	});	});
 		$(function(){ $(".normal").autosize();	});
 
 });	
 
 
 function comboboxchanged() { }	
 
 </script>';
	
	
	
	include "footer.php";
	
mysql_close(); 
echo '</body></html>';