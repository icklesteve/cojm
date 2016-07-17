<?php 

$alpha_time = microtime(TRUE);

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "../../administrator/cojm/updatetracking.php";
include "changejob.php";
$adminmenu = "1";
$lengthtext='';

$customjs='';

if (isset($_GET['clientid'])) { $clientid=trim($_GET['clientid']); } else { $clientid='all'; }
if (isset($_GET['clientview'])) { $clientview=trim($_GET['clientview']); } else { $clientview='normal'; }
if (isset($_GET['newcyclistid'])) { $newcyclistid=trim($_GET['newcyclistid']); } else { $newcyclistid=''; }
if (isset($_GET['viewselectdep'])) { $viewselectdep=trim($_GET['viewselectdep']); } else { $viewselectdep=''; }
if (isset($_GET['from'])) { $start=trim($_GET['from']); } else { $start=''; }
if (isset($_GET['to'])) { $end=trim($_GET['to']); } else { $end=''; }
if (isset($_GET['deltype'])) { $deltype=trim($_GET['deltype']);} else { $deltype='all'; }
if (isset($_GET['orderby'])) { $orderby=trim($_GET['orderby']);} else {$orderby='targetcollection'; }
if (isset($_GET['orderby'])) { $orderby=trim($_GET['orderby']);} else {$orderby='targetcollection'; }

// echo ' here '.$newcyclistid;

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

$title='COJM ';


?><!DOCTYPE html> 
<html lang="en"> 
<head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<?php
echo '
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
';


	echo '<script src="https://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>';  



?>
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<title><?php print ($title); ?> GPS Tracking</title>
</head>
<body>
<? 
$filename="clientviewtargetcollection.php";
include "cojmmenu.php"; 
echo '<div class="Post">
<form action="trackingbyday.php" method="get" id="cvtc"> 
	<div class="ui-state-highlight ui-corner-all p15">
';
	
	
	
	

$query = "SELECT CyclistID, cojmname, trackerid FROM Cyclist ORDER BY CyclistID"; 
$result_id = mysql_query ($query, $conn_id); 
echo '<select id="combobox" size="14"  name="newcyclistid" class="ui-state-highlight ui-corner-left">';
// echo ' <option value="">Select one...</option> ';
echo '<option value="all"';

if ($newcyclistid == 'all') {echo ' selected="selected" '; }

echo '>All '.$globalprefrow['glob5'].'s</option>';

while (list ($CyclistID, $cojmname, $trackerid) = mysql_fetch_row ($result_id)) { print ("<option "); 
if ($CyclistID == $newcyclistid) {echo ' selected="selected" '; $thistrackerid=$trackerid; } 
print ("value=\"$CyclistID\">$cojmname</option>"); } 
print ("</select>"); 	
	

echo '
Collections From	<input class="ui-state-default ui-corner-all pad" size="10" type="text" name="from" value="'. $inputstart .'" id="rangeBa" />			
To		<input class="ui-state-default ui-corner-all pad"  size="10" type="text" name="to" value="'.  $inputend.'" id="rangeBb" />			
';


// echo ' <select name="clientview" class="ui-state-highlight ui-corner-left">
// <option '; if ($clientview=='normal') { echo 'selected'; } echo ' value="normal">Normal View</option>
// <option '; if ($clientview=='client') { echo 'selected'; } echo ' value="client">Copy to Client</option>
// <option '; if ($clientview=='clientprice') { echo 'selected'; } echo ' value="clientprice">Copy with Price</option>
// </select>';




echo ' <button type="submit" >Search</button>
</div></form>';

$linecoords='';

$loop='0';
$i='0';

 echo '<div style="width:100%; height: 350px;" id="ordermap" ></div>
 
 <script type="text/javascript">
$(document).ready(function() {

';

echo "

var locations = [ ";




$dinterim=$dstart;

while ($dinterim<$dend) {
$dinterif=$dinterim+'86399';
// echo '<br /> dstart : '.$dstart.' dinterim : '.$dinterim.' dinterif : '.$dinterif.' dend '.$dend.'';
if ($newcyclistid == 'all') {
$query = "SELECT CyclistID, cojmname, trackerid FROM Cyclist ORDER BY CyclistID"; }
else { $query = "SELECT CyclistID, cojmname, trackerid FROM Cyclist WHERE CyclistID = ". $newcyclistid; }
$result_id = mysql_query ($query, $conn_id); 
while (list ($CyclistID, $cojmname, $trackerid) = mysql_fetch_row ($result_id)) {
$sql="SELECT * FROM `instamapper` 
WHERE `device_key` = '$trackerid' 
AND `timestamp` >= '$dinterim' 
AND `timestamp` <= '$dinterif' 
ORDER BY `timestamp` ASC "; 
$sql_resulth = mysql_query($sql,$conn_id)  or mysql_error();
$num_rows = mysql_num_rows($sql_resulth);
if ($num_rows>'0') {
$prevts='';
while ($map = mysql_fetch_array($sql_resulth)) {
$i=$i+'1';
     extract($map); 
//	 $lattot=$lattot+$map['latitude'];
//	 $lontot=$lontot+$map['longitude'];
if ($newcyclistid<>'all') {
 $linecoords=$linecoords.' new google.maps.LatLng('.$map['latitude'] . "," . $map['longitude'].') , ';
  }
if ($i%3==0) {
	 $comments=$cojmname.' '.date('H:i A D j M ', $map['timestamp']).'<br />';
	 $thists=date('H:i A D j M ', $map['timestamp']);

echo "['" . $comments ."',". $map['latitude'] . "," . $map['longitude'] . "," . $i ."],"; 

$loop++;
}
}
}
}
$dinterim=$dinterim+'86400';
}

// echo '<br /><h1>235</h1>';



echo '
    ];
	
	var iconBase = "https://maps.google.com/mapfiles/kml/shapes/";
    var map = new google.maps.Map(document.getElementById("ordermap"), {
      zoom: 12,
         center: new google.maps.LatLng(52.4846382,-1.90461999),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }); ';
	
	$days=round((((($dend-$dstart)+'1')/'3600')/'24'));
	
		if ($days=='1') {

echo '

  var lineCoordinates = [
 '.$linecoords. '
  ];
	// Define a symbol using a predefined path (an arrow)
  // supplied by the Google Maps JavaScript API.
  var lineSymbol = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
 strokeOpacity: 0.3

 };
 
  var line = new google.maps.Polyline({
    path: lineCoordinates,
	geodesic: true,
	strokeOpacity: 0.3,
    icons: [{
      icon: lineSymbol,
	       offset: "0",
      repeat: "50px"
    }],
    map: map
  });
';

}
	
		
	echo '
 	var infowindow = new google.maps.InfoWindow();
    var marker, i;
    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
		icon: ("https://maps.google.com/mapfiles/kml/pal4/icon24.png")
      });
      google.maps.event.addListener(marker, "click", (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }	
	});
	</script>
	';








$tottimedif='';

$inputval = $tottimedif; // USER DEFINES NUMBER OF SECONDS FOR WORKING OUT | 3661 = 1HOUR 1MIN 1SEC 
$unitd =86400;
$unith =3600;        // Num of seconds in an Hour... 
$unitm =60;            // Num of seconds in a min... 
$dd = intval($inputval / $unitd);       // days
$hh_remaining = ($inputval - ($dd * $unitd));
$hh = intval($hh_remaining / $unith);    // '/' given value by num sec in hour... output = HOURS 
$ss_remaining = ($hh_remaining - ($hh * $unith)); // '*' number of hours by seconds, then '-' from given value... output = REMAINING seconds 
$mm = intval($ss_remaining / $unitm);    // take remaining sec and devide by sec in a min... output = MINS 
$ss = ($ss_remaining - ($mm * $unitm));        // '*' number of mins by seconds, then '-' from remaining sec... output = REMAINING seconds. 
if ($dd==1) {$lengthtext=$lengthtext. $dd . " day "; } if ($dd>1 ) { $lengthtext=$lengthtext. $dd . " days "; }
if ($hh==1) {$lengthtext=$lengthtext. $hh . " hr "; } if ($hh>1) { $lengthtext=$lengthtext. $hh . " hrs "; }
if ($mm>1 ) {$lengthtext=$lengthtext. $mm . " mins. "; } if ($mm==1) {$lengthtext=$lengthtext. $mm . " min. "; }
// number_format($tablecost, 2, '.', '')
if ($dd) {} else { if ($mm) {   $lengthtext=$lengthtext. "(". number_format((($mm/60)+$hh), 2, '.', ''). 'hrs)'; } }
// echo ($tottimedif/60).' minutes';

if (trim($lengthtext)) { echo 'Time Taken '.$lengthtext. ' from collection to delivery.';}

if ($dend) { echo ' '.round((((($dend-$dstart)+'1')/'3600')/'24')).' days'; }



echo $days;

// echo '<br /> &'.  $globalprefrow['currencysymbol'].round($tablecost/round((((($dend-$dstart)+'1')/'3600')/'24')),2) .' average per day. ';


// <form action="createbatchkml.php" method="post">
// echo $temptrack; 
// <button type="submit"> Download all tracking data for these jobs</button>
// </form>
// <br />
if ($i) { echo ' '.$i.' tracking positions total, '.$loop .' displayed.'; }

 echo ' </div> ';

 
 echo '<script type="text/javascript">	
$(document).ready(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
	    $("#rangeBa, #rangeBb").daterangepicker();  
			 });
</script>';

include 'footer.php';

mysql_close();  

echo '</body></html>';