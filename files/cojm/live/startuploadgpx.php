<?php 

/*
    COJM Courier Online Operations Management
	startuploadgpx.php - GPS Tab, upload .gpx files, start searching gps history, delete rider gps by day 
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
$title='COJM : '.$cyclistid;
$row='';
$dstart='';
$gmapdata='';
$i='';
$linecoords='';
$loop='0';
$lattot='';
$lontot='';
$map='';
$heading='';

$max_lat = '-99999';
$min_lat =  '99999';
$max_lon = '-99999';
$min_lon =  '99999';


if (isset($_POST['confirmgpx'])) { $confirmgpx=trim($_POST['confirmgpx']); } else { $confirmgpx=''; }
if (isset($_GET['from'])) { $start=trim($_GET['from']); } else { $start=''; }
if (isset($_GET['to'])) { $end=trim($_GET['to']); } else { $end=''; }
if (isset($_POST['newcyclist'])) { $CyclistID=trim($_POST['newcyclist']); }
elseif (isset($_GET['newcyclist'])) { $CyclistID=trim($_GET['newcyclist']); } else { $CyclistID=''; }

$thisCyclistID=$CyclistID;


if ($start) {

    $trackingtext='';
    $tstart = str_replace("%2F", ":", "$start", $count);
    $tstart = str_replace("/", ":", "$start", $count);
    $tstart = str_replace(",", ":", "$tstart", $count);
    $temp_ar=explode(":","$tstart"); 
    $day=$temp_ar['0']; 
    $month=$temp_ar['1']; 
    $year=$temp_ar['2']; 
    $hour='00';
    $minutes='00';
    $second='00';
    $sqlstart= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
    $dstart= date("U", mktime($hour, $minutes, $second, $month, $day, $year));



    if ($year) { $inputstart=$day.'/'.$month.'/'.$year; }
} else  { 
    // nothing posted
    $inputstart='';
    $sqlstart='';
}


if ($end) {
    $tend = str_replace("%2F", ":", "$end", $count);
    $tend = str_replace("/", ":", "$end", $count);
    $tend = str_replace(",", ":", "$tend", $count);
    $temp_ar=explode(":",$tend); 
    $day=$temp_ar['0'];
    $month=$temp_ar['1'];
    $year=$temp_ar['2'];
    $hour='23';
    $minutes= '59';
    $second='59';
    if ($year) { $inputend=$day.'/'.$month.'/'.$year; }
    $sqlend= date("Y-m-d H:i:s", mktime(23, 59, 59, $month, $day, $year));
    $dend=date("U", mktime(23, 59, 59, $month, $day, $year));
} else {
    $sqlend='3000-12-25 23:59:59'; 
    $inputend=''; 
    $dend='';
}


echo '<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<script src="//maps.googleapis.com/maps/api/js?key='.$globalprefrow['googlemapapiv3key'].'&sensor=false" type="text/javascript"></script>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height " >
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>

<title>COJM GPS Tracking</title></head><body class="c9" OnKeyPress="return disableKeyPress(event)">';

$agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/iPhone|Android|Blackberry/i', $agent)) { $adminmenu=""; } else { $adminmenu ="1"; }

$filename="startuploadgpx.php";
include "cojmmenu.php"; 



$query = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 
$riderdata = $dbh->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);







echo '<div class="Post"> 
<form action="gpstracking.php" method="get" id="cvtc"> 
<div class="ui-state-highlight ui-corner-all p15"> ';

echo '<select id="combobox" size="14"  name="newcyclist" class="ui-state-highlight ui-corner-left">';
echo '<option value="all"';
if ($thisCyclistID == '') {echo ' selected="selected" '; }
echo '>Search All '.$globalprefrow['glob5'].'s</option>';

foreach ($riderdata as $ridernum => $ridername) {
    $ridername=htmlspecialchars($ridername);
    print ("<option ");
    if ($thisCyclistID == $ridernum) { echo ' selected="selected" '; }
    if ($ridernum == '1') { echo ' class="unalo" '; }
    print ("value=\"$ridernum\">$ridername</option>");
}

print ("</select>"); 	




echo '

<input class="ui-state-default ui-corner-all pad" placeholder="Date From" size="10" type="text" name="from" value="'. $inputstart .'" id="rangeBa" />			

<input class="ui-state-default ui-corner-all pad" placeholder="Date To" size="10" type="text" name="to" value="'.  $inputend.'" id="rangeBb" /> 

 <button type="submit" >Search</button>
 </div>
 </form>
';






  
echo ' <br />

<div class="ui-state-highlight ui-corner-all p15 " >

<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="startuploadgpx.php" method="POST">
<p>
<label for="file">Upload GPX File : </label>
<input type="file" name="file" id="file" /> 
';

echo '<select name="newcyclist" class="ui-state-default ui-corner-left"> ';
foreach ($riderdata as $ridernum => $ridername) {
    $ridername=htmlspecialchars($ridername);
    print ("<option ");
    if ($thisCyclistID == $ridernum) { echo ' selected="selected" '; }
    if ($ridernum == '1') { echo ' class="unalo" '; }
    print ("value=\"$ridernum\">$ridername</option>");
}
print ("</select> ");

echo ' <select name="confirmgpx" class="ui-state-default ui-corner-left">
<option value="confirm">Add Tracks to COJM</option>
<option '; 
if ($confirmgpx=='normal') {  echo 'selected'; }
echo ' value="normal">Preview File</option>

</select>
<button type="submit" name="submit" >Upload GPX file</button></p>
</form>
</div>';




$allowedExtensions = array("gpx"); 
foreach ($_FILES as $file) {
    
    $file['name']=$file['name'];
    if ($file['tmp_name'] > '') {
		if ( $thisCyclistID=='' or $thisCyclistID=='1' ) {		
            echo '<div class="ui-state-error ui-corner-all" style="padding: 1em;">
            <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
            <strong>Please select '.$globalprefrow['glob5'].' </strong></p></div>'; 
		} else {
            $expl=explode(".", strtolower($file['name']));
            if (!in_array(end($expl), $allowedExtensions)) { 
                echo '<div class="ui-state-error ui-corner-all" style="padding: 1em;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				<strong> '.$file['name'].' is not a GPX file, please re-try.</strong></p></div><br />';
            } else {
                // echo 'GPX File located :-)';
                if ($_FILES["file"]["error"] > 0) {
                    echo "Error: " . $_FILES["file"]["error"] . "<br />";
                } else {
                    echo "<br /><h3> Uploaded : " . $_FILES["file"]["name"].' </h3>';
                    // echo "Type: " . $_FILES["file"]["type"] . "<br />";
                    //  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                    //  echo "Stored in: " . $_FILES["file"]["tmp_name"];
                    $file = ($_FILES["file"]["tmp_name"]);
                    // $ext = ($_FILES["file"]["extension"]);
                    // echo " File extension is ".$ext;
                    // $target = "/cache/newnamegpx.".$ext;
                    include"trackstats.php";
                }
            }
        }
    }
}
 

  

  
// starts DELETE TRACKING
echo '
<br />

  
<div class="ui-state-error ui-corner-all p15" >
<form action="startuploadgpx.php" method="POST" >
  Delete tracking positions from  ';

echo '<input class="ui-state-default ui-corner-all caps" type="text" value="';

if (isset($_POST['gpsdeletedate'])) { echo $_POST['gpsdeletedate']; } echo '" id="gpsdeletedate" size="12" name="gpsdeletedate"> for ';


echo '<select name="newcyclist" class="ui-state-default ui-corner-left"> <option value=""> Select Rider </option>';


foreach ($riderdata as $ridernum => $ridername) {
    if ($ridernum>1) {
        $ridername=htmlspecialchars($ridername);
        print ("<option ");
        if ($thisCyclistID == $ridernum) { echo ' selected="selected" '; }
        if ($ridernum == '1') { echo ' class="unalo" '; }
        print ("value=\"$ridernum\">$ridername</option>");
    }
}









print ("</select>"); 

echo '
<input type="hidden" name="page" value="deletegps"/>
<input type="hidden" name="formbirthday" value="'. date("U").'">
<button type="submit" name="submit" >Delete Positions</button>
</form>
<br /></div>';
// ENDS DELETE TRACKING
     
echo '</div><br />';
  
?>
<script type="text/javascript">
$(document).ready(function() {
	$(function() {
		var dates = $( "#gpsdeletedate" ).datepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
            dateFormat: 'dd-mm-yy ',
			changeMonth:false,
		  beforeShow: function(input, instance) { 
            $(input).datepicker('setDate',  new Date() );
        }
		});
	});
    
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
	    $("#rangeBa, #rangeBb").daterangepicker();  

	});

function comboboxchanged() { }    
function datepickeronchange() { }
</script>
<?php

include "footer.php";
echo '  </body> </html>';
mysql_close(); 

$dbh=null;
