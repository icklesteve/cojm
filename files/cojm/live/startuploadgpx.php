<?php 
$alpha_time = microtime(TRUE);



include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

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
$clusterdata='';
$tabletext='';
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

if (isset($_GET['clientview'])) { $clientview=trim($_GET['clientview']); } else { $clientview=''; }


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
} else  { // nothing posted
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

}

else { 

$sqlend='3000-12-25 23:59:59'; 
$inputend=''; 
$dend='';

}


echo '<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">';


echo '<script src="//maps.googleapis.com/maps/api/js?key='.$globalprefrow['googlemapapiv3key'].'&sensor=false" type="text/javascript"></script>'; 

if ($clientview=='cluster') {
echo '
   <script type="text/javascript" src="//google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>
';
}

echo '
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height " >
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>

<title>COJM GPS Tracking</title></head><body class="c9" OnKeyPress="return disableKeyPress(event)">';

$agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/iPhone|Android|Blackberry/i', $agent)) { $adminmenu=""; } else { $adminmenu ="1"; }

$filename="startuploadgpx.php";
include "cojmmenu.php"; 

echo '<div class="Post"> 
<form action="gpstracking.php" method="get" id="cvtc"> 
<div class="ui-state-highlight ui-corner-all p15"> ';


// echo $globalprefrow['clweb3'];
// echo 'Temp Upload Directory : '.ini_get('upload_tmp_dir').'<br>';
// print shell_exec( 'whoami' );


$query = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' AND Cyclist.CyclistID > '1' ORDER BY CyclistID "; 
$result_id = mysql_query ($query, $conn_id); 
echo '<select id="combobox" size="14"  name="newcyclist" class="ui-state-highlight ui-corner-left">';
// echo ' <option value="">Select one...</option> ';
echo '<option value="all"';

if ($thisCyclistID == 'all') {echo ' selected="selected" '; }

echo '>All '.$globalprefrow['glob5'].'s</option>';

while (list ($CyclistID, $cojmname) = mysql_fetch_row ($result_id)) { print ("<option "); 
if ($CyclistID == $thisCyclistID) {echo ' selected="selected" ';  } 
print ("value=\"$CyclistID\">$cojmname</option>"); } 
print ("</select>"); 	
	

echo '
Collections From	<input class="ui-state-default ui-corner-all pad" size="10" type="text" name="from" value="'. $inputstart .'" id="rangeBa" />			
To		<input class="ui-state-default ui-corner-all pad"  size="10" type="text" name="to" value="'.  $inputend.'" id="rangeBb" /> ';


 echo ' <select name="clientview" class="ui-state-highlight ui-corner-left">
 <option '; if ($clientview=='normal') { echo 'selected'; } echo ' value="normal">Normal View</option>

 <option '; if ($clientview=='cluster') { echo 'selected'; } echo ' value="cluster">Clustered</option>
 </select>

 <button type="submit" >Search</button> </div></form>';



   // begin Dave B's Q&D file upload security code 
  $allowedExtensions = array("gpx"); 
  foreach ($_FILES as $file) { 
    if ($file['tmp_name'] > '') {
		
		
		
		if ( $thisCyclistID=='' or $thisCyclistID=='1' ) {
		
		
		
		echo '
	<div class="ui-state-error ui-corner-all" style="padding: 1em;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				<strong>


		Please select '.$globalprefrow['glob5'].' </strong></p></div>'; 
		
		
		} else {
		
		$expl=explode(".", strtolower($file['name']));
		
      if (!in_array(end($expl), $allowedExtensions)) { 
			echo '
			<div class="ui-state-error ui-corner-all" style="padding: 1em;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				<strong>';
			
			
       echo ($file['name'].' is not a GPX file, please re-try.</strong></p></div><br />'); 
      } else 
	  { 
// echo 'GPX File located :-)';
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  } else {
//  echo "<br /> Upload: " . $_FILES["file"]["name"];
// echo "Type: " . $_FILES["file"]["type"] . "<br />";
//  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
//  echo "Stored in: " . $_FILES["file"]["tmp_name"];


$file = ($_FILES["file"]["tmp_name"]);


// $ext = ($_FILES["file"]["extension"]);


// echo " File extension is ".$ext;


// $target = "/cache/newnamegpx.".$ext; 

 include"trackstats.php"; 


// move_uploaded_file( $_FILES['userFile']['tmp_name'], $target);


}
}
}
}
} // ends check to make sure that some sort of file has been uploaded  
 
 
 
 


 
 
 if (($dstart<>'') and ($thisCyclistID)) {
 
 $dinterim=$dstart;

while ($dinterim<$dend) {
$dinterif=$dinterim+'86399';
// echo '<br /> dstart : '.$dstart.' dinterim : '.$dinterim.' dinterif : '.$dinterif.' dend '.$dend.'';
if ($thisCyclistID == 'all') {
$query = "SELECT CyclistID, cojmname, trackerid FROM Cyclist ORDER BY CyclistID"; }
else { $query = "SELECT CyclistID, cojmname, trackerid FROM Cyclist WHERE CyclistID = ". $thisCyclistID; }
$result_id = mysql_query ($query, $conn_id); 

echo $query;



while (list ($CyclistID, $cojmname, $trackerid) = mysql_fetch_row ($result_id)) {
$sql="SELECT latitude, longitude, speed, timestamp FROM `instamapper` 
WHERE `device_key` = '$trackerid' 
AND `timestamp` >= '$dinterim' 
AND `timestamp` <= '$dinterif' 
ORDER BY `timestamp` ASC "; 
$sql_resulth = mysql_query($sql,$conn_id)  or mysql_error();
$num_rows = mysql_num_rows($sql_resulth);
if ($num_rows>'0') {
$prevts='';
$tablecount='';
$tabledatestart='';
while ($map = mysql_fetch_array($sql_resulth)) {
$i++;
$tablecount++;

     extract($map); 
	 if ($clientview=='blurred') {
$map['latitude']=round($map['latitude'],2);
$map['longitude']=round($map['longitude'],2);
	 } elseif ($clientview=='cluster') {
$map['latitude']=round($map['latitude'],3);
$map['longitude']=round($map['longitude'],3);
	 } else {
$map['latitude']=round($map['latitude'],5);
$map['longitude']=round($map['longitude'],5); 
}
$map['speed']=round($map['speed']);


  if($map['longitude']>$max_lon) { $max_lon = $map['longitude']; }
  if($map['longitude']<$min_lon) { $min_lon = $map['longitude']; }

  
  if($map['latitude']>$max_lat) { $max_lat = $map['latitude']; }
  if($map['latitude']<$min_lat)  { $min_lat = $map['latitude']; }





	
if ($thisCyclistID<>'all') { $linecoords=$linecoords.' ['.$map['latitude'] . "," . $map['longitude'].'],';  }
  $clusterdata=$clusterdata.' ['.$map['latitude'] . "," . $map['longitude'].'],';
	$thists=date('H:i A D j M ', $map['timestamp']);
   if ($thists<>$prevts) {
  	 $comments=$cojmname.' <br />'.date('H:i D j M ', $map['timestamp']);
	 $comments=$comments.'<br />'.$map['speed'];
 if ($globalprefrow['distanceunit']=='miles') { $comments=$comments. 'mph '; } 
 if ($globalprefrow['distanceunit']=='km') { $comments=$comments. 'km ph '; } 
  $thists=date('H:i A D j M ', $map['timestamp']);

$gmapdata=$gmapdata. "['" . $comments ."',". $map['latitude'] . "," . $map['longitude'] . "," . $i ."],"; 

 $lattot=$lattot+$map['latitude'];
 $lontot=$lontot+$map['longitude'];
$prevts=date('H:i A D j M ', $map['timestamp']); 
$tabledate= date('D j M ', $map['timestamp']); 
$tabledatefinish=date('H:i A ', $map['timestamp']);






if ($tabledatestart=='') { $tabledatestart=date('H:i A ', $map['timestamp']);
 }

$loop++;
}
}

$tabletext=$tabletext.'<tr>
<td>'.$cojmname.'</td>
<td>'.$tabledate.'</td>
<td>'.$tabledatestart.'</td>
<td>'.$tabledatefinish.'</td>
<td>'.$tablecount.'</td>
</tr>';


}

}
$dinterim=$dinterim+'86400';
}

// echo $loop;

if ($loop) {

$avglat=($lattot/$loop);
$avglon=($lontot/$loop);
} else {

echo ' No tracking positions to display ';



}

} // ends check for $dstart
 



 
 if ($gmapdata) {
 
echo ' <div id="ordermap" style="position: relative; width: 100%; height: 350px;"></div> '; 

if ($clientview<>'cluster') {

echo '
 <script type="text/javascript">
  $(document).ready(function() {
  var locations = ['.$gmapdata.'  ];
    map = new google.maps.Map(document.getElementById("ordermap"), {
      zoom: 12,
      center: new google.maps.LatLng('. $avglat . ',' . $avglon.'),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
	
	
	 
     bounds = new google.maps.LatLngBounds();
    bounds.extend(new google.maps.LatLng('.$max_lat.', '.$min_lon.')); // upper left
    bounds.extend(new google.maps.LatLng('.$max_lat.', '.$max_lon.')); // upper right
    bounds.extend(new google.maps.LatLng('.$min_lat.', '.$max_lon.')); // lower right
    bounds.extend(new google.maps.LatLng('.$min_lat.', '.$min_lon.')); // lower left


 	map.fitBounds(bounds);
 
	
	 var image = {
    url: "'. $globalprefrow['clweb3'].'",
    size: new google.maps.Size(20, 20),
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(10, 10)
  };  
	
	
	
    var infowindow = new google.maps.InfoWindow();
    var marker, i;
    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
		icon: image
      });
      google.maps.event.addListener(marker, "mouseover", (function(marker, i) {
        return function() {
        infowindow.setContent(" <div style='."'".' width: 110px; '."'".'> "+locations[i][0] + " </div> " );
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  var all = ['.$linecoords. ' ] ;

  var lineSymbol = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
 strokeOpacity: 0.3
 };
 
 var gmarkers = [];

for (var j = 0; j < all.length; j++) {
        var lat = all[j][0];
        var lng = all[j][1];
        var marker = new google.maps.LatLng(lat, lng);
     gmarkers.push(marker);
	 }
 
  var line = new google.maps.Polyline({
    path: gmarkers,
	geodesic: true,
	strokeOpacity: 0.3,
    icons: [{
      icon: lineSymbol,
	       offset: "0",
      repeat: "50px"
    }],
    map: map
  });
	});
  </script>
'; 

} else { // ends normal map type , starts cluster map
 
 
 echo '
  <script type="text/javascript">
function initialize() {

var pos = new google.maps.LatLng('. $avglat . ',' . $avglon.');

var myOptions = {    zoom: 13,
    center: pos,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};

var map = new google.maps.Map(document.getElementById("ordermap"), myOptions);

map.setCenter(pos);

var all = [ '.$clusterdata.' ];
 
var gmarkers = [];
for (var i = 0; i < all.length; i++) {
        var lat = all[i][0];
        var lng = all[i][1];
        var latLng = new google.maps.LatLng(lat, lng);
        var marker = new google.maps.Marker({map: map, position: latLng,});
        gmarkers.push(marker);
}
var markerCluster = new MarkerClusterer(map, gmarkers);  
};
      google.maps.event.addDomListener(window, "load", initialize);
    </script> 
 ';
 
 
 
 
 } 
 if ($i) { echo '<p> '.$i.' tracking positions total, '.$loop .' displayed.</p>'; }
 

if ($tabletext) { 


echo '<table class="acc"><tbody><tr>
<th>Rider </th>
<th>Date </th>
<th>Start </th>
<th>Finish </th>
<th>Number Tracks </th>
</tr>
'.$tabletext.'</tbody></table>';
 
// echo $tabletext;
 
 }
 
 }
 
// echo '<div class="line"></div><br />';
  
  
  
  
echo ' <br />



<div class="ui-state-highlight ui-corner-all p15 " >

<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="startuploadgpx.php" method="POST">
<p>
 <label for="file">Filename:</label>
<input type="file" name="file" id="file" /> 
';


$query = "SELECT CyclistID, cojmname, trackerid FROM Cyclist WHERE Cyclist.isactive='1' AND Cyclist.CyclistID>'1' ORDER BY CyclistID"; 
$result_id = mysql_query ($query, $conn_id); 
echo '<select name="newcyclist" class="ui-state-default ui-corner-left"> <option value=""> </option>';
while (list ($CyclistID, $cojmname, $trackerid) = mysql_fetch_row ($result_id)) { 
if ($trackerid) { print ("<option "); 
if ($thisCyclistID == $trackerid) {echo " SELECTED "; $thistrackerid=$trackerid; } 
print ("value=\"$trackerid\">$cojmname</option>");
}
} 
print ("</select> "); 

echo ' <select name="confirmgpx" class="ui-state-default ui-corner-left">
<option selected value="normal">Preview</option>
<option value="confirm">Confirm</option>
</select>
<button type="submit" name="submit" >Upload GPX file</button></p>
</form>
</div><br />';

  
  
  
  
  
  
  
  
  
  
  
  
  
  // starts DELETE TRACKING
  
  echo ' 
<div class="ui-state-error ui-corner-all p15" >
<form action="startuploadgpx.php" method="POST" >
  Delete tracking positions from  ';

echo '<input class="ui-state-default ui-corner-all caps" type="text" value="';

if (isset($_POST['gpsdeletedate'])) { echo $_POST['gpsdeletedate']; } echo '" id="gpsdeletedate" size="12" name="gpsdeletedate"> for ';
$query = "SELECT CyclistID, cojmname, trackerid FROM Cyclist WHERE Cyclist.isactive='1' AND Cyclist.CyclistID>'1' ORDER BY CyclistID"; 
$result_id = mysql_query ($query, $conn_id); 
echo '<select name="newcyclist" class="ui-state-default ui-corner-left"> <option value=""> </option>';
while (list ($CyclistID, $cojmname, $trackerid) = mysql_fetch_row ($result_id)) { 
if ($trackerid) { print ("<option "); 
if ($thisCyclistID == $trackerid) {echo " SELECTED "; $thistrackerid=$trackerid; }
print ("value=\"$trackerid\">$cojmname</option>"); } } 
print ("</select>"); 

  echo '
  <input type="hidden" name="page" value="deletegps"/>
  <input type="hidden" name="formbirthday" value="'. date("U").'">
  <button type="submit" name="submit" >Delete Positions</button>
  </form>';
   
  echo '<br /></div>';
   
   
   // ENDS DELETE TRACKING
     
  echo '</div><br />';
  
  ?>
<script type="text/javascript">
$(document).ready(function() {
	$(function() {
		var dates = $( "#gpsdeletedate" ).datepicker({
			numberOfMonths: 1,
			changeYear:true,
			firstDay: 1,
            dateFormat: 'dd-mm-yy ',
			changeMonth:true,
		  beforeShow: function(input, instance) { 
            $(input).datepicker('setDate',  new Date() );
        }
		});
	});
	});
</script>
<?php

 echo '<script type="text/javascript">	
$(document).ready(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
	    $("#rangeBa, #rangeBb").daterangepicker();  
			 });
			 
function comboboxchanged() { }	
			 
</script>';


include "footer.php";

echo '  </body> </html>';
 mysql_close(); 
