<?php 

/*
    COJM Courier Online Operations Management
	gpstracking.php - Displays rider GPS history
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
$error='';
$js=array();
$cjs=array();
$includejs=array();
$foundtracks='0';
$tableerror='';

$max_lat = '-99999';
$min_lat =  '99999';
$max_lon = '-99999';
$min_lon =  '99999';


if (isset($_POST['confirmgpx'])) { $confirmgpx=trim($_POST['confirmgpx']); } else { $confirmgpx=''; }
if (isset($_GET['from'])) { $start=trim($_GET['from']); } else { $start=''; }
if (isset($_GET['to'])) { $end=trim($_GET['to']); } else { $end=''; }
if (isset($_GET['newcyclist'])) { $CyclistID=trim($_GET['newcyclist']); } else { $CyclistID='all'; }
// if (isset($_POST['newcyclist'])) { $CyclistID=trim($_POST['newcyclist']); }  


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
$sqlstart= date("Y-m-d H:i:s", gmmktime($hour, $minutes, $second, $month, $day, $year));
$dstart= date("U", gmmktime($hour, $minutes, $second, $month, $day, $year));
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
$sqlend= date("Y-m-d H:i:s", gmmktime(23, 59, 59, $month, $day, $year));
$dend=date("U", gmmktime(23, 59, 59, $month, $day, $year));

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

echo '<script src="https://maps.google.com/maps/api/js?libraries=geometry&key='.$globalprefrow['googlemapapiv3key'].'"></script> 
';



if ($clientview=='cluster') {
echo '   <script type="text/javascript" src="js/markerclusterer.js"></script> ';
}

echo '
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height " >
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
<style>
 div.info {  color:green; font-weight:bold; } 
form#cvtc div.ui-state-highlight.ui-corner-all.p15 input.ui-autocomplete-input.ui-widget.ui-widget-content { width:200px; }
</style>
<title>COJM GPS Tracking</title>';






// echo ' dstart is '.$dstart;
 
 
 if ($dstart<>'') {
 
 
// echo ' <br /> 173 ';
 

 

 
 $dinterim=$dstart;

while ($dinterim<$dend) {
	
	
// echo ' <br /> 184 ';	
	
	
$dinterif=$dinterim+'86399';
// echo '<br /> dstart : '.$dstart.' dinterim : '.$dinterim.' dinterif : '.$dinterif.' dend '.$dend.'';
if ($thisCyclistID == 'all') {




$sql="SELECT DISTINCT device_key FROM `instamapper` 
WHERE `timestamp` >= ? AND `timestamp` <= ?
 "; 

} else { 


$sql="SELECT DISTINCT device_key FROM `instamapper` 
INNER JOIN Cyclist ON instamapper.device_key = Cyclist.trackerid 
WHERE `timestamp` >= ? AND `timestamp` <= ?
AND `CyclistID` = ".$thisCyclistID.'

';

 }
 
 
// $error.= '<br /> 213 '.$sql;
// echo $sql; 
 
 
 
$stmt = $dbh->prepare($sql);



$stmt->execute(array($dinterim, $dinterif));


// var_dump($stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE)); 




$row_count = $stmt->rowCount();
// echo ' rc '. $row_count. $dinterim.' '.$dinterif;


// $sql_resulth = mysql_query($sql,$conn_id)  or mysql_error();
// $num_rows = mysql_num_rows($sql_resulth);

if ($row_count>'0') {
	
	
	$prevts='';
$tablecount='';
$tabledatestart='';
	
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 

// echo ' 214 ';

// print_r($row);

$device_key=$row['device_key'];



	
	
//	echo '<br /> 228 '.$dinterim.' '.date('Y/m', $dinterim);
// $error.=  '<br /> 221 '.$dinterim.' '.date('Y/m', $dinterim);	
	



// while (list ($device_key) = mysql_fetch_row ($sql_resulth)) {
	
	
	
	
$i++;
$tablecount++;

 $testfile="cache/jstrack/".date('Y/m', $dinterim).'/'.date('Y_m_d', $dinterim).'_'.$device_key.'.js';

$checkdate=date('Y-m-d', $dinterim);

$displaydate=date('D j M', $dinterim);
$displayyear=date('Y', $dinterim);

	$ssql="SELECT timestamp, cojmname, CyclistID FROM `instamapper` 
	INNER JOIN Cyclist ON instamapper.device_key = Cyclist.trackerid  
WHERE `device_key` = '$device_key' 
AND `timestamp` >= '$dinterim' 
AND `timestamp` <= '$dinterif' 
ORDER BY `timestamp` ASC 
LIMIT 0,1 ;"; 



$ssql_resulth = mysql_query($ssql,$conn_id)  or mysql_error();
$snum_rows = mysql_num_rows($ssql_resulth);
if ($snum_rows>'0') { while ($smap = mysql_fetch_array($ssql_resulth)) {

$tabledatestart= date('H:i A ', $smap['timestamp']); 
$cojmname=$smap['cojmname'];
$CyclistID=$smap['CyclistID'];

} }





















if (!file_exists($testfile)) {


$alreadyindbquery="
SELECT cojmadmin_id FROM cojm_admin 
WHERE  cojm_admin_stillneeded='1' AND cojmadmin_rider_gps='1' AND cojmadmin_rider_id='$device_key' AND cojm_admin_rider_date='$checkdate'
ORDER BY cojmadmin_id ASC LIMIT 0,1 ; ";

  $gpsadmin = mysql_query($alreadyindbquery) or die(mysql_error());
$gpsadminrow = mysql_fetch_array($gpsadmin); 


// $error.= $alreadyindbquery;

if($gpsadminrow) {
// $error.= '<br /> job already outstanding on system '.$gpsadminrow['cojmadmin_id'];

$tableerror.=' <tr class="error"> <td>'.$cojmname.'</td> <td title="'.$displayyear.'">'.$displaydate.' </td> <td colspan="2"> Awaiting Caching  </td> </tr> ';



}
 else { 
// $error.='<br />'.$testfile. ' not found and  no job outstanding on system. ';






 $sql="INSERT INTO cojm_admin 
   (cojm_admin_stillneeded, cojmadmin_rider_gps, cojmadmin_rider_id, cojm_admin_rider_date) 
    VALUES ('1', '1', '$device_key', '$checkdate' )   ";
    $result = mysql_query($sql, $conn_id);
 if ($result){
// echo "<br />247 Success adding admin job";
 $thiscyclist=mysql_insert_id(); 
// $error.= ' Admin Task '.$thiscyclist.' created.';


$tableerror.=' <tr class="error"> <td>'.$cojmname.'</td> <td>'.$displaydate.' </td> <td colspan="3"> Cache task created </td> </tr> ';

 
 } else {
$error.= mysql_error()." An error occured during setting admin q <br>".$sql;  
 } // ends sql


 } // check job already in admin q
 
 
}
else
	{ // file exists
	echo '    ';
	
	
	
$foundtracks++;	
	
	
$markervar='markers'.date('Y_m_d', $dinterim).'_'.$device_key;
$linevar='line'.date('Y_m_d', $dinterim).'_'.$device_key;
$orow['ID']=date('Y_m_d', $dinterim).'_'.$device_key;

if ($clientview=='cluster') {
	
$includejs[] = ' <script src="'.$testfile.'" > </script>';

	
// $cjs[] = file_get_contents($testfile);
	
$cjs[] = ' cluster=cluster +  ('.$linevar.');  

for (var i = 0; i < '.$linevar.'.length; i++) {
        var lat = '.$linevar.'[i][0];
        var lng = '.$linevar.'[i][1];
        var latLng = new google.maps.LatLng(lat, lng);
        var marker = new google.maps.Marker({map: map, position: latLng,});
        gmarkers.push(marker);
}
	';
	
	
} else {
	
	
// $js[] = file_get_contents($testfile);

$includejs[] = ' <script src="'.$testfile.'" > </script>';

$js[] = '
  var marker, i;
  var gmarkers'.$orow['ID'].'=[];
  
    for (i = 0; i < markers'.$orow['ID'].'.length; i++) {
      marker = new google.maps.Marker({
        position: new google.maps.LatLng('.$markervar.'[i][1], '.$markervar.'[i][2]),
        map: map,
		icon: image,
      });
	  
	  gmarkers'.$orow['ID'].'.push(marker)

 google.maps.event.addListener(marker, "mouseover", (function(marker, i) {
        return function() {
infowindow.setContent(" <div class='."'".' info '."'".'> "+'.$markervar.'[i][0] + "<div class='."'".' ajaxinfowin '."'".'></div> </div> " );
setTimeout( function() {

var auditpage="cojmaudit";
var markervar = '.$markervar.'[i][3];
var dataString = "markervar=" + markervar;
$.ajax({
    type: "POST",
    url:"ajaxgpsorderlookup.php",
    data: dataString,
    success: function(data){
	$(".ajaxinfowin").html(data)
 //   alert(loadingtext); //only for testing purposes
    }
});
	
	}, 0 );
		  infowindow.setOptions({ disableAutoPan: true });
          infowindow.open(map, marker);
		  
		  

	$( "tr#'.$orow['ID'].'" ).addClass( "highlight" );
		  polyline'.$orow['ID'].'.setOptions({strokeColor: "#339900", strokeWeight: 4 });
	 for (var j=0; j<gmarkers'.$orow['ID'].'.length; j++) {
	  gmarkers'.$orow['ID'].'[j].setIcon(imagehighlight);
	  gmarkers'.$orow['ID'].'[j].setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
	 }
        }
		
      })(marker, i));
	  
	  
 google.maps.event.addListener(marker, "mouseout", function() {

	$( "tr#'.$orow['ID'].'" ).removeClass( "highlight" );
		  polyline'.$orow['ID'].'.setOptions({strokeColor: "#000000", strokeWeight: 4 });
	 for (var j=0; j<gmarkers'.$orow['ID'].'.length; j++) {
	  gmarkers'.$orow['ID'].'[j].setIcon(image);
	  gmarkers'.$orow['ID'].'[j].setZIndex(1);

	 }
 }); 
  
    }
	
	var route'.$orow['ID'].' = [];
for (var j = 0; j < line'.$orow['ID'].'.length; j++) {
        var lat = line'.$orow['ID'].'[j][0];
        var lng = line'.$orow['ID'].'[j][1];
        var marker = new google.maps.LatLng(lat, lng);
     route'.$orow['ID'].'.push(marker);
}
	
	var polyline'.$orow['ID'].' = new google.maps.Polyline({
    path: route'.$orow['ID'].',
	geodesic: true,
	strokeWeight: 4,
	strokeOpacity: 0.6,
	strokeColor: "#000000",
    icons: [{
    icon: lineSymbol,
    repeat: "50px"
    }],
    map: map
  });
	
	
$( "tr#'.$orow['ID'].'" ).mouseover(function() {
	$( "tr#'.$orow['ID'].'" ).addClass( "highlight" );
		  polyline'.$orow['ID'].'.setOptions({strokeColor: "#339900", strokeWeight: 4 });
	 for (var j=0; j<gmarkers'.$orow['ID'].'.length; j++) {
	  gmarkers'.$orow['ID'].'[j].setIcon(imagehighlight);
	  gmarkers'.$orow['ID'].'[j].setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
	 }
	});

$( "tr#'.$orow['ID'].'" ).mouseout(function() {
	$( "tr#'.$orow['ID'].'" ).removeClass( "highlight" );
		  polyline'.$orow['ID'].'.setOptions({strokeColor: "#000000", strokeWeight: 4 });
	 for (var j=0; j<gmarkers'.$orow['ID'].'.length; j++) {
	  gmarkers'.$orow['ID'].'[j].setIcon(image);
	  gmarkers'.$orow['ID'].'[j].setZIndex(1);
	 }
	});
';
}





	
	$tabledate= date('D j M ', $dinterim); 
	$tableyear= date('Y', $dinterim);
	
	
	$tabledatelink='clientviewtargetcollection.php?clientid=all&timetype=tarcollect&from='.
date(('j'), $dinterim).'%2F'.
date(('n'), $dinterim).'%2F'.
date(('Y'), $dinterim).'%2F&to='.
date(('j'), $dinterim).'%2F'.
date(('n'), $dinterim).'%2F'.
date(('Y'), $dinterim).'&servicetype=all&deltype=all&orderby=targetcollection&clientview=normal&viewcomments=normal&statustype=all'.
'&newcyclistid='.$CyclistID;
	
	
	
	
	
	
	
	$fsql="SELECT timestamp FROM `instamapper` 
WHERE `device_key` = '$device_key' 
AND `timestamp` >= '$dinterim' 
AND `timestamp` <= '$dinterif' 
ORDER BY `timestamp` DESC 
LIMIT 0,1 ;"; 
$fsql_resulth = mysql_query($fsql,$conn_id)  or mysql_error();
$fnum_rows = mysql_num_rows($fsql_resulth);
if ($fnum_rows>'0') { while ($fmap = mysql_fetch_array($fsql_resulth)) { 
$tabledatefinish= date('H:i A ', $fmap['timestamp']); } }

$tabletext.='<tr id="'.$orow['ID'].'">
<td>'.$cojmname.'</td>
<td><a href="'.$tabledatelink.'" title="'.$tableyear.'">'.$tabledate.'</a></td>
<td>'.$tabledatestart.'</td>
<td>'.$tabledatefinish.'</td>
<td><button class="clrcachebtn" id="clrcache-'.$orow['ID'].'">Refresh Cache</button></td>
</tr>';

$js[] = '
$("#clrcache-'.$orow['ID'].'").click(function(){
	    $.ajax({
        url: "ajaxchangejob.php",  //Server script to process data
		data: {
		page:"ajaxremovegpscache",
		folder:"'.date(('Y/m'), $dinterim).'",
		trackingid:"'.$orow['ID'].'" },
		type:"post",
        success: function(data) {
 $("#infotext").append(data);
// alert(data);
	},
		complete: function(data) {
		showmessage();
		}
});
});

';


















} // file exists	
}
} // ends num rows in individual day lookup
else { 

// $error.=  ' 399 none in day ';

}


// $error.=  ' 400 day loop '. $loop;
$dinterim=$dinterim+'86400';

} // each day loop



//  starts javascript






// echo $includejs;

 echo '<script>
 
 var max_lat = [];
var min_lat = [];
var max_lon = [];
var min_lon = [];
var cluster;

var gmarkers = [];

var markercount = [];
var lineplotscount = [];

</script> ';


echo join(" ", $includejs);



echo '
<script>

function initialize() {

var geoXml = null;
var geocoder = null;
var element = document.getElementById("map-canvas");
		
    var imagehighlight = {
  url: "../images/plot-20-20-339900-square-pad.png",
 size: new google.maps.Size(20, 20),
   origin: new google.maps.Point(0,0),
   anchor: new google.maps.Point(10, 10)
  };  
		
 var mapTypeIds = [];
            var mapTypeIds = ["OSM", "roadmap", "satellite", "OCM"]
			
			
		
			
		 var map = new google.maps.Map(element, {
                center: new google.maps.LatLng('. $globalprefrow['glob1'].','.$globalprefrow['glob2'].'),
                zoom: 11,
                mapTypeId: "OSM",
				 mapTypeControl: true,
                mapTypeControlOptions: {
                mapTypeIds: mapTypeIds
                }
            });
			
			
		
			
	
     map.mapTypes.set("OSM", new google.maps.ImageMapType({
                getTileUrl: function(coord, zoom) {
                    return "https://a.tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
                },
                tileSize: new google.maps.Size(256, 256),
                name: "OSM",
				alt: "Open Street Map",
                maxZoom: 19
            }));	
	
            map.mapTypes.set("OCM", new google.maps.ImageMapType({
                getTileUrl: function(coord, zoom) {
                    return "https://a.tile.thunderforest.com/cycle/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
                },
                tileSize: new google.maps.Size(256, 256),
                name: "OCM",
				alt: "Open Cycle Map",
                maxZoom: 20
            }));

var osmcopyr="'."<span style='background: white; color:#444444; padding-right: 6px; padding-left: 6px; margin-right:-12px;'> &copy; <a style='color:#444444' " .
               "href='https://www.openstreetmap.org/copyright' target='_blank'>OpenStreetMap</a> contributors</span>".'"


 var outerdiv = document.createElement("div");
outerdiv.id = "outerdiv";
  outerdiv.style.fontSize = "10px";
  outerdiv.style.opacity = "0.7";
  outerdiv.style.whiteSpace = "nowrap";
	
map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(outerdiv);	








google.maps.event.addListener( map, "maptypeid_changed", function() {
var checkmaptype = map.getMapTypeId();
if ( checkmaptype=="OSM" || checkmaptype=="OCM") { 
$("div#outerdiv").html(osmcopyr);
} else { $("div#outerdiv").text(""); }
});





// if OSM / OCM set as default, show copyright
$(document).ready(function() {setTimeout(function() {
$("div#outerdiv").html(osmcopyr);
},3000);});


 
  var lineSymbol = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
 strokeOpacity: 0.4
 };
 
var infowindow = new google.maps.InfoWindow();
 
  var image = {
    url: "../images/icon242.png",
    size: new google.maps.Size(20, 20),
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(10, 10)
  };   
  ';


 
 
 
 



if ($clientview=='cluster') {

echo join("\n", $cjs); 
echo ' var markerCluster = new MarkerClusterer(map, gmarkers);  ';


} else { 

echo join("\n", $js);

}




echo '

var gmax_lon = Math.max.apply(Math, max_lon); 
var gmax_lat = Math.max.apply(Math, max_lat); 
var gmin_lon = Math.min.apply(Math, min_lon); 	
var gmin_lat = Math.min.apply(Math, min_lat); 	

    bounds = new google.maps.LatLngBounds();
    bounds.extend(new google.maps.LatLng(gmax_lat, gmin_lon)); // upper left
    bounds.extend(new google.maps.LatLng(gmax_lat, gmax_lon)); // upper right
    bounds.extend(new google.maps.LatLng(gmin_lat, gmin_lon)); // lower right
    bounds.extend(new google.maps.LatLng(gmin_lat, gmax_lon)); // lower left
 
 map.fitBounds(bounds); 
 
 
  $(window).resize(function () {
    var h = $(window).height(),
        offsetTop = 72; // Calculate the top offset

    $("#gmap_wrapper").css("height", (h - offsetTop));
}).resize();
 
 ';
 
 
		?>
	   
 geocoder = new google.maps.Geocoder(); 
 window.showAddress = function(address) {
    geocoder.geocode( { 
	"address": address + " , UK ",
	"region":   "uk",
    "bounds": bounds 
	}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
          map.setCenter(results[0].geometry.location);
            var infowindow = new google.maps.InfoWindow(
                { content: "<div class='info'>"+address+"</div>",
				    position: results[0].geometry.location,
                map: map
                });
			infowindow.open(map);
          } else {
            alert("No results found");
          }
        } else {
          alert("Search was not successful : " + status);
        }
      });
 }
<?php
 
 
 echo '
 

}
 // google.maps.event.addDomListener(window, "load", initialize); 

 
</script>';


 }


echo '</head><body ';

 if ($foundtracks>'0') { echo 'onload="initialize()" '; }


echo '>';

$agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/iPhone|Android|Blackberry/i', $agent)) { $adminmenu=""; } else { $adminmenu ="1"; }

$filename="startuploadgpx.php";
include "cojmmenu.php"; 


echo ' 
<div id="gmap_wrapper" >
<div class="full_map" id="search_map">';
if ($foundtracks=='0') { echo ' <h1> No Results Found </h1> '; 


 if ($tableerror) { echo '<table><tbody>'.$tableerror.'</tbody></table>'; }

// echo $error;

}


echo '<div id="map-canvas" class="onehundred" >';


echo '</div></div>
<div class="gmap_left" id="scrolltable">
<div class="pad10">


';




echo '
<form action="gpstracking.php" method="get" id="cvtc"> 
<div class="ui-state-highlight ui-corner-all p15"> ';



$query = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' AND Cyclist.CyclistID > '1' ORDER BY CyclistID "; 
$result_id = mysql_query ($query, $conn_id); 
echo '<select id="combobox" size="14"  name="newcyclist" style="width:200px;" class=" ui-state-highlight ui-corner-left">';
// echo ' <option value="">Select one...</option> ';
echo '<option value="all"';

if ($thisCyclistID == 'all') {echo ' selected="selected" '; }
echo '>All '.$globalprefrow['glob5'].'s</option>';
while (list ($CyclistID, $cojmname) = mysql_fetch_row ($result_id)) { print ("<option "); 
if ($CyclistID == $thisCyclistID) {echo ' selected="selected" ';  } 
print ("value=\"$CyclistID\">$cojmname</option>"); }
print ("</select>"); 	
	

echo '
<input class="ui-state-default ui-corner-all pad" size="10" type="text" name="from" value="'. $inputstart .'" id="rangeBa" />			
To		<input class="ui-state-default ui-corner-all pad"  size="10" type="text" name="to" value="'.  $inputend.'" id="rangeBb" /> 

 <select name="clientview" class="ui-state-highlight ui-corner-left">
 <option '; if ($clientview=='normal') { echo 'selected'; } echo ' value="normal">Normal View</option>
 <option '; if ($clientview=='cluster') { echo 'selected'; } echo ' value="cluster">Clustered</option>
 </select>

 <button type="submit" >Search</button> </div></form>';



// echo ' <div id="map-canvas" style="width: 850px; height: 430px; position:relative; float:left;"></div> ';


if ($clientview<>'cluster') { }


// echo ' <div id="scrolltable" style=" height: 430px; position:relative; padding-left:10px; overflow-y:scroll;"> ';



if ($foundtracks<>'0') {


echo '
<form action="#" onsubmit="showAddress(this.address.value); return false" style=" background:none;">
<input title="Address Search" type="text" style="width: 274px; padding-left:6px;" name="address" placeholder="Map Address Search . . ." 
class="ui-state-default ui-corner-all address" />
</form>	
';










echo ' <br /> <p>'.$foundtracks.' tracks found.</p> ';

  echo $error;

echo '
<br />
<table class="ord"><tbody><tr>
<th>Rider</th>
<th>Date</th>
<th>Start</th>
<th>Finish</th>
<th></th>
</tr>
'.$tableerror.$tabletext.'
</tbody></table>
';

}



echo '

<br />
<br />
</div> 
';

// echo '<div style="clear:both;"> </div> ';
  
  echo '</div> </div>';


  echo '<script type="text/javascript">	
$(document).ready(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
	    $("#rangeBa, #rangeBb").daterangepicker();  
		';
		
		echo '
			 });
			 
			 
function comboboxchanged() { }			 
			 
			 
</script>';

$page='GPS Tracking';

include "footer.php";

echo '  </body> </html>';

$dbh=null;

 mysql_close(); 