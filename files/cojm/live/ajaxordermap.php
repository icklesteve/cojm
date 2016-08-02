<?php
/*
    COJM Courier Online Operations Management
	ajaxordermap.php - called by order.php js to show map div + some times
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










// show rider tracking if present
// show area map if present
// show sub areas if present

// update span #totaltime


include_once "C4uconnect.php";
include_once ("GeoCalc.class.php");

if (isset($_POST['page'])) { $page=trim($_POST['page']); } else { exit();  }
if (isset($_POST['id'])) { $postedid = trim($_POST['id']); }




$sql = "SELECT 
publictrackingref,
cojmname,
trackerid, 
starttrackpause,
finishtrackpause,
collectiondate,
targetcollectiondate,
collectionworkingwindow,
deliveryworkingwindow,
duedate,
ShipDate,
status,
opsmaparea,
opsmapsubarea
FROM Orders 
INNER JOIN Cyclist
WHERE Orders.CyclistID = Cyclist.CyclistID 
AND `Orders`.`id` = :getid LIMIT 0,1";

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':getid', $postedid, PDO::PARAM_INT); 
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total = $stmt->rowCount();


if ($row['opsmaparea']) { 



$sql = "SELECT 
opsname,
descrip
FROM opsmap 
WHERE opsmap.opsmapid = :opsmapid LIMIT 0,1";
$astmt = $dbh->prepare($sql);
$astmt->bindParam(':opsmapid', $row['opsmaparea'], PDO::PARAM_INT); 
$astmt->execute();
$arow = $astmt->fetch(PDO::FETCH_ASSOC);
// $atotal = $astmt->rowCount();

$mainareaname=$arow['opsname'];
$mainareadescrip=$arow['descrip'];

}






if ($row['opsmapsubarea']>'0') { 



$sql = "SELECT 
opsname,
descrip
FROM opsmap 
WHERE opsmap.opsmapid = :opsmapid LIMIT 0,1";
$bstmt = $dbh->prepare($sql);
$bstmt->bindParam(':opsmapid', $row['opsmapsubarea'], PDO::PARAM_INT); 
$bstmt->execute();
$brow = $bstmt->fetch(PDO::FETCH_ASSOC);
// $atotal = $astmt->rowCount();

$subareaname=$brow['opsname'];
$subareadescrip=$brow['descrip'];

}








// name descrip of area

// name descrip of sub area















$thistrackerid=$row['trackerid'];


$startpause=strtotime($row['starttrackpause']); 
$finishpause=strtotime($row['finishtrackpause']); $collecttime=strtotime($row['collectiondate']); 
$delivertime=strtotime($row['ShipDate']); if (($startpause > '10') and ( $finishpause < '10')) { $delivertime=$startpause; } 
if ($startpause <'10') { $startpause='9999999999'; } if (($row['status']<'86') and ($delivertime < '200')) { $delivertime='9999999999'; } 
if ($row['status']<'50') { $delivertime='0'; } if ($collecttime < '10') { $collecttime='9999999999';} 
$findlast="SELECT * FROM `instamapper` 
WHERE `device_key` = '$thistrackerid' 
AND `timestamp` >= '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' 
AND '$finishpause' 
AND `timestamp` <= '$delivertime' 
ORDER BY `timestamp` ASC 
LIMIT 1"; 
$sql_result = mysql_query($findlast,$conn_id)  or mysql_error(); 
while ($foundlast = mysql_fetch_array($sql_result)) { extract($foundlast); 


$englishfirst= date('H:i jS', $foundlast['timestamp']); 
$englishfirstda= date('H:i', $foundlast['timestamp']); 
$englishfirstd= date('jS', $foundlast['timestamp']); 



}

$findlast="SELECT * FROM `instamapper` 
WHERE `device_key` = '$thistrackerid' 
AND `timestamp` >= '$collecttime' 
AND `timestamp` NOT BETWEEN '$startpause' 
AND '$finishpause' 
AND `timestamp` <= '$delivertime' 
ORDER BY `timestamp` DESC 
LIMIT 1"; 
$sql_result = mysql_query($findlast,$conn_id)  or mysql_error(); 
while ($foundlast = mysql_fetch_array($sql_result)) { extract($foundlast); 
$englishlast= date('H:i jS', $foundlast['timestamp']); 
$englishlastd=date('jS', $foundlast['timestamp']);

if ($englishlastd==$englishfirstd) {

$trackingtext= ' Tracking ' . $englishfirstda . '-' . $englishlast . ''; 


} else {

$trackingtext= ' Tracking ' . $englishfirst . ' - ' . $englishlast . ''; 
}

}
  
 

// start of tracking script
 $sql = "SELECT latitude, longitude, speed, timestamp FROM `instamapper`  WHERE `device_key` = '$thistrackerid' AND `timestamp` >= '$collecttime' AND `timestamp` 
NOT BETWEEN '$startpause' AND '$finishpause' AND `timestamp` <= '$delivertime' ORDER BY `timestamp` ASC"; 
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$lattot='0'; 
$lontot='0'; 
$sumtot=mysql_affected_rows(); 





if (($sumtot>'0.5') or ($row['opsmaparea'] <>'')) {




$areajs='';
$orderjs='';

$max_lat = '-99999';
$min_lat =  '99999';
$max_lon = '-99999';
$min_lon =  '99999';


$btmdescrip='';
$topdescrip='';



$checkifarchivearea='';




if ($sumtot>'0.5') {

$linecoords='';
 $latestlat=	'';
$latestlon='';
$loop='';

	
$orderjs=' 

var locations = [';
$prevts='';
$i='0';
while ($map = mysql_fetch_array($sql_result)) {      extract($map); 

$map['latitude']=round($map['latitude'],5);
$map['longitude']=round($map['longitude'],5);

  if($map['longitude']>$max_lon) { $max_lon = $map['longitude']; }
  if($map['longitude']<$min_lon) { $min_lon = $map['longitude']; }
  if($map['latitude']>$max_lat) { $max_lat = $map['latitude']; }
  if($map['latitude']<$min_lat)  { $min_lat = $map['latitude']; }

$i=$i+'1';
$thispc='';
 $linecoords=$linecoords.' ['.$map['latitude'] . "," . $map['longitude'].'],';
	 $lattot=$lattot+$map['latitude'];
	 $lontot=$lontot+$map['longitude'];
	 $thists=date('H:i A D j M ', $map['timestamp']);

 if ($thists<>$prevts) {

  $comments=date('H:i D j M ', $map['timestamp']).'<br />';
 
      $oGC = new GeoCalc(); $dRadius = '0.15'; 
        $dLongitude = $map['longitude'];
        $dLatitude = $map['latitude']; $dAddLat = $oGC->getLatPerKm() * $dRadius; $dAddLon = $oGC->getLonPerKmAtLat($dLatitude) * $dRadius;
        $dNorthBounds = $dLatitude + $dAddLat;
        $dSouthBounds = $dLatitude - $dAddLat; $dWestBounds = $dLongitude - $dAddLon; $dEastBounds = $dLongitude + $dAddLon;
         $strQuery = "SELECT PZ_northing, PZ_easting, PZ_Postcode FROM postcodeuk " .
                   "WHERE PZ_northing > $dSouthBounds " .
                   "AND PZ_northing < $dNorthBounds " .
                   "AND PZ_easting > $dWestBounds " .
                   "AND PZ_easting < $dEastBounds";
$trsql_result = mysql_query($strQuery,$conn_id)  or mysql_error(); 
$trsumtot=mysql_affected_rows(); 
$dDist='99999999999'; $startdit='9999999999999';
 while ($trrow = mysql_fetch_array($trsql_result)) {  extract($trrow);
        $oGC = new GeoCalc(); 
      $dDist = $oGC->EllipsoidDistance($map["latitude"],$map["longitude"],$trrow["PZ_northing"],$trrow["PZ_easting"]);
if ($dDist<$startdit) { $dDist=$startdit; $thispc=$trrow['PZ_Postcode'];
$start= substr($thispc, 0, -3); 
$cyclistpos= $start.' '.substr($thispc, -3); // 'ies'  
} }
 
if (isset($cyclistpos)) { $comments=$comments.''.$cyclistpos.'     '; }
 
 if ($map['speed']) {  $comments=$comments . ''. round($map['speed']);
 if ($globalprefrow['distanceunit']=='miles') { $comments=$comments. 'mph '; } 
 if ($globalprefrow['distanceunit']=='km') { $comments=$comments. 'km ph '; } }

$orderjs.= "['" . $comments ."',". $map['latitude'] . "," . $map['longitude'] . "," .$postedid.'-'. $i ."],"; 
$prevts=date('H:i A D j M ', $map['timestamp']); 
	
$latestlat=$latestlat+$map['latitude'];
$latestlon=$latestlon+$map['longitude'];
$loop++;


} // checks timestamp different
} // finished waypoint loop
$lattot=($latestlat / $loop );
$lontot=($latestlon / $loop );
		// restarts javascript

	//	echo ' i is '.$i;
		
$orderjs.=  '
    ]; 

  var lineCoordinates = [
 '.$linecoords. '
  ];



var gmarkers = [];
for (var j = 0; j < lineCoordinates.length; j++) {
        var lat = lineCoordinates[j][0];
        var lng = lineCoordinates[j][1];
        var marker = new google.maps.LatLng(lat, lng);
     gmarkers.push(marker);
}


  var line = new google.maps.Polyline({
    path: gmarkers,
	geodesic: true,
	strokeOpacity: 0.3,
    icons: [{
    icon: lineSymbol,
    repeat: "50px"
    }],
    map: map
  });
  
  
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
		  	icon: image,
      });
      google.maps.event.addListener(marker, "mouseover", (function(marker, i) {
        return function() {
          infowindow.setContent(" <div style=\" width: 110px; \"> "+locations[i][0] + " </div> " );
		  infowindow.setOptions({ disableAutoPan: true });
          infowindow.open(map, marker);
        }
      })(marker, i));
    }';
	
} // ends sumtot > 0.5 rider tracking

 




if ($row['opsmaparea'] <>'') {



$areajs.= '
  var worldCoords = [
    new google.maps.LatLng(85,180),
	new google.maps.LatLng(85,90),
	new google.maps.LatLng(85,0),
	new google.maps.LatLng(85,-90),
	new google.maps.LatLng(85,-180),
	new google.maps.LatLng(0,-180),
	new google.maps.LatLng(-85,-180),
	new google.maps.LatLng(-85,-90),
	new google.maps.LatLng(-85,0),
	new google.maps.LatLng(-85,90),
	new google.maps.LatLng(-85,180),
	new google.maps.LatLng(0,180),
	new google.maps.LatLng(85,180)];

';


$areaid=$row['opsmaparea'];
$result = mysql_query("SELECT AsText(g) AS POLY FROM opsmap WHERE opsmapid=".$areaid);
if (mysql_num_rows($result)) {
    $score = mysql_fetch_assoc($result);
	$p=$score['POLY'];
$trans = array("POLYGON" => "", "((" => "", "))" => "");
$p= strtr($p, $trans);
$pexploded=explode( ',', $p );

$areajs.= ' 

 var polymarkers'.$areaid.' = [ ';
foreach ($pexploded as $v) {
$transf = array(" " => ",");
$v= strtr($v, $transf);
$areajs.= '   
	new google.maps.LatLng('.$v.'),';
	
	
	if ($row['opsmapsubarea'] <1) { // show bounds of sub area instead
	$vexploded=explode( ',', $v );
	$tmpi='1';
	foreach ($vexploded as $testcoord) {
	if ($tmpi % 2 == 0) {
  if($testcoord>$max_lon) { $max_lon = $testcoord; }
  if($testcoord<$min_lon)  { $min_lon = $testcoord; }
} else { 
  if($testcoord>$max_lat) { $max_lat = $testcoord; }
  if($testcoord<$min_lat)  { $min_lat = $testcoord; }
}
	$tmpi++;
	}
	
	}	
	
	
	
} // ends each in array

$areajs = rtrim($areajs, ','); 

$areajs.='    ]; 

';
  
//  strokeColor: "#FF0000",

$areajs.=' 
 poly'.$areaid.' = new google.maps.Polygon({
	paths: [worldCoords, polymarkers'.$areaid.'],
    strokeWeight: 3,
	strokeOpacity: 0.6,
     fillColor: "#667788",
	 fillOpacity: 0.2,
	 strokeColor: "#000000",
	 clickable:false,
	 map:map
  });
   
';

} // ends top layer





if ($row['opsmapsubarea']>'0') {


$areaid=$row['opsmapsubarea'];
$result = mysql_query("SELECT AsText(g) AS POLY FROM opsmap WHERE opsmapid=".$areaid);
if (mysql_num_rows($result)) {
    $score = mysql_fetch_assoc($result);
	$p=$score['POLY'];
$trans = array("POLYGON" => "", "((" => "", "))" => "");
$p= strtr($p, $trans);
$pexploded=explode( ',', $p );
$areajs.=' 

 var polymarkers'.$areaid.' = [ ';
foreach ($pexploded as $v) {
$transf = array(" " => ",");
$v= strtr($v, $transf);
	$areajs=$areajs.'   
	new google.maps.LatLng('.$v.'),';

	
	
	$vexploded=explode( ',', $v );
	$tmpi='1';
	foreach ($vexploded as $testcoord) {
	if ($tmpi % 2 == 0) {
  if($testcoord>$max_lon) { $max_lon = $testcoord; }
  if($testcoord<$min_lon)  { $min_lon = $testcoord; }
} else { 
  if($testcoord>$max_lat) { $max_lat = $testcoord; }
  if($testcoord<$min_lat)  { $min_lat = $testcoord; }
}
	$tmpi++;
	
	}
	
	
} // ends each in array

$areajs = rtrim($areajs, ','); 
$areajs=$areajs.'    ]; 

';
  
//  strokeColor: "#FF0000",

$areajs.=' 
 poly'.$areaid.' = new google.maps.Polygon({
	paths: [worldCoords, polymarkers'.$areaid.'],
    strokeWeight: 12,
	strokeOpacity: 0.5,
     fillColor: "#5555FF",
	 fillOpacity: 0.15,
	 strokeColor: "#00FF00",
	 clickable: false,
	 map: map
  });
'; 

} // ends main sub area


$lilquery = "SELECT * FROM opsmap WHERE opsmapid<>".$row['opsmapsubarea']." AND corelayer=".$row['opsmaparea']; 

} else { // else no subarea

$lilquery = "SELECT * FROM opsmap WHERE corelayer=".$row['opsmaparea']; 

	}

$lilsql_result = mysql_query ($lilquery, $conn_id) or mysql_error();  
$lilsumtot=mysql_affected_rows();

// echo ' alert(" on '.$opsname.' '.$sumtot.' found '.$query.'"); ';

$lilareaarray=[];

if ($lilsumtot>'0') {
while ($lilrow = mysql_fetch_array($lilsql_result)) { extract($lilrow); 

$lilareaid=$lilrow['opsmapid'];

$lilareaarray[]=$lilrow['opsmapid'];

$lilareaname=$lilrow['opsname'];
$lilareadescrip=$lilrow['descrip'];

// echo ' alert(" extra area '. $lilareaid .'"); ';

$lilresult = mysql_query("SELECT AsText(g) AS POLY FROM opsmap WHERE opsmapid=".$lilareaid);

    $score = mysql_fetch_assoc($lilresult);
	$p=$score['POLY'];
$trans = array("POLYGON" => "", "((" => "", "))" => "");
$p= strtr($p, $trans);
$pexploded=explode( ',', $p );
$areajs.='  var polymarkers'.$lilareaid.' = [ ';
foreach ($pexploded as $v) {
$transf = array(" " => ",");
$v= strtr($v, $transf);
$areajs.='   
	new google.maps.LatLng('.$v.'),';
	
	
} // ends each in array

$areajs = rtrim($areajs, ','); 
$areajs.='    ]; 

 poly'.$lilareaid.' = new google.maps.Polygon({
	paths: [polymarkers'.$lilareaid.'],
    strokeWeight: 3,
	strokeOpacity: 0.2,
	 strokeColor: "#000000",
	  fillOpacity: 0,
	 clickable: false,
	 map: map
  });

var bounds'.$lilareaid.' = new google.maps.LatLngBounds();
var i;  
for (i = 0; i < polymarkers'.$lilareaid.'.length; i++) {
 bounds'.$lilareaid.'.extend(polymarkers'.$lilareaid.'[i]);
}
var cent=(bounds'.$lilareaid.'.getCenter());
 '."
   marker".$lilareaid." = new RichMarker({
          position: cent,
		  flat: true,
          map: map,
          draggable: false,
          content: '<div class=".'"map-sub-area-label"><a href="opsmap-new-area.php?page=showarea&amp;areaid='.$lilareaid.'">'.$lilareaname.'</a></div>'."'
           });
";
 

} // ends lil area row extract

} // ends check lil sum tot

} // ends main area







 if ($sumtot>'0.5') {


echo ' <a href="../createkml.php?id='. $row['publictrackingref'].'">'.$trackingtext.'</a>. ';
}

echo '
<div id="map-container" >
 <div class="btn-full-screen" >
 <button id="btn-enter-full-screen" title="Full Screen Map"> &nbsp; </button>
 <button id="mylocation" title="Current Position"> &nbsp; </button>
 <button id="btn-exit-full-screen" title="Exit Full Screen"> </button>
 <button id="printbutton" title="Print Map" > </button>
 <div class="printinfo">
 <img alt="'.$globalprefrow['globalshortname'].' Logo" src="'.$globalprefrow['adminlogo'].'" />
<p>'. date('l jS M Y', strtotime($row['targetcollectiondate'])).'</p>';

 if ($mainareaname)  { echo '<p>'.$mainareaname; if ($mainareadescrip)  { echo '('.$mainareadescrip.')'; } echo '</p>'; }
 
 // sub area name & comments in brackets go here

if ($subareaname) { echo '<p> '.$subareaname; if ($subareadescrip) { echo ' ('.$subareadescrip.') '; } echo '</p>'; }
 
echo '<p> '.$postedid.' </p>';

if ($row['CyclistID']<>'1') { echo '<p> '.$row['cojmname'].' </p>'; }


?>
</div>
 </div>
 <div class="ordermap" id="ordermap" ></div>
 </div><script>
 
var element = document.getElementById("ordermap");

 var mapTypeIds = [];
            var mapTypeIds = ["OSM", "roadmap", "satellite", "OCM"]
			
		 var map = new google.maps.Map(element, {
                center: new google.maps.LatLng('. $lattot . ',' . $lontot.'),
                zoom: 11,
                mapTypeId: "OSM",
				scaleControl: true,
				 mapTypeControl: true,
                mapTypeControlOptions: {
                mapTypeIds: mapTypeIds,
				style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
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
	
	
var osmcopyr='<span class=\"inlinemapcopy\" > &copy; <a style=\"color:#444444\" href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\">OpenStreetMap</a> contributors</span> ';

 var outerdiv = document.createElement("div");
outerdiv.className  = "outerdiv";
  outerdiv.style.fontSize = "10px";
  outerdiv.style.opacity = "0.7";
  outerdiv.style.whiteSpace = "nowrap";
  outerdiv.style.padding = "0px 0px 0px 6px";
		
map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(outerdiv);	

google.maps.event.addListener( map, "maptypeid_changed", function() {
var checkmaptype = map.getMapTypeId();
if ( checkmaptype=="OSM" || checkmaptype=="OCM") { 
$("div.outerdiv").html(osmcopyr);
$("span.printcopyr").html(" " + osmcopyr+ " ");

} else { 
$("div.outerdiv").text("");
$("span.printcopyr").html(" Map Data &copy; Google Maps ");
}

});


// if OSM / OCM set as default, show copyright
$(document).ready(function() {setTimeout(function() {
$("div.outerdiv").html(osmcopyr);
$("span.printcopyr").html(" " + osmcopyr + " " );
},3000);});
	
	
	
	

	
	
	
	
	
  var lineSymbol = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
 strokeOpacity: 0.3
 };
 
 <?php 
 
 if (!$min_lat) { $min_lat=0; }
 
 
 echo '
    bounds = new google.maps.LatLngBounds();
    bounds.extend(new google.maps.LatLng('.$max_lat.', '.$min_lon.')); // upper left
    bounds.extend(new google.maps.LatLng('.$max_lat.', '.$max_lon.')); // upper right
    bounds.extend(new google.maps.LatLng('.$min_lat.', '.$max_lon.')); // lower right
    bounds.extend(new google.maps.LatLng('.$min_lat.', '.$min_lon.')); // lower left
 	map.fitBounds(bounds);
'; ?>

	
	if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(
    currentpositionsuccess,
    errorCallback_highAccuracy,
    {maximumAge:600000, timeout:5000, enableHighAccuracy: true}
); 
		

  } else {
  error("Geo Location is not supported");
}
  function currentpositionsuccess(position) {
   mycoords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
 var mypositionimage = {
    url: "../images/bluedot.png",
scaledSize: new google.maps.Size(26, 26), // scaled size
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(13, 13)
  }; 
  mymarker = new google.maps.Marker({
      position: mycoords,
      map: map,
      title:"Accurate to "+ position.coords.accuracy + "m",
	  icon: mypositionimage,
	  clickable:true
  });	
    $("#mylocation").click(function() {	map.panTo(mycoords); });
	
	
   navigator.geolocation.getCurrentPosition(
    changesuccess,
    errorCallback_highAccuracy,
    {maximumAge:600000, timeout:5000, enableHighAccuracy: true}

); 
 
 
 
 
 
 function changesuccess(position) {
	 
	 
	 
// alert(" function changesuccess has fired ");	 
	 
	 
	 var currentdate = new Date(); 
	 
 mycoords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
 mytitle= "Accurate to " + position.coords.accuracy + "m, updated " + currentdate.getHours() + ":"  
                + currentdate.getMinutes();
mymarker.setPosition(mycoords);  
mymarker.setTitle(mytitle);

    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
        $("#map-container").append(mytitle);
  }	
  }
  
function errorCallback_highAccuracy(position) {
    var msg = "<p>Cant get your location (high accuracy attempt). ";
    $("#map-container").append(msg);
	
	   $("#mylocation").hide();
	
}



function successCallback(position) {  }
  
  
  
  var googleMapWidth = $("#ordermap").css("width");
  var googleMapHeight = $("#ordermap").css("height");


//Used for centering the map on print
  function center_map(size) {
  lastPos = map.getCenter(); 
  if (lastPos != null) {
    
		swapStyleSheet("css/fullscreenmap.css");
	
	  $("#ordermap").css({
        height: "700px",
		width: "1050px"
    });
	
	$(".printinfo").css({
		display: "block"
	});
	
	 $("#map-container").css({
	 top: "0" });
	<?php
	
		if ($areaid) { echo '
	poly'.$areaid.'.setOptions({
		fillColor: "white",
			 fillOpacity: 0.5,
		});

		'; }
		
		?>
		
		

		
  google.maps.event.trigger(map, "resize");
    map.setCenter(lastPos);
  return true;
  }
  else {
    return false;
  }
  }
  
  function loadPrint() {

  window.print();
    setTimeout(function () {
		
<?php		
		if ($areaid) { echo '
	poly'.$areaid.'.setOptions({
		fillColor: "#667788",
		 fillOpacity: 0.4,
		 	  clickable: false
		});	
		';
		}
		
		
		
		?>
		
		swapStyleSheet("<?php echo $globalprefrow['glob10']; ?>");

	$(".printinfo").css({
		display: "none"
	});

		 $("#map-container").css({
	top: "36px",
	height: "calc (100% - 36px)"
	 });
		
	  $("#ordermap").css({
        height: "100%",
		width: "100%"
    });		
		
		
    $("#btn-exit-full-screen").toggle();
    $("#printbutton").toggle();
		
 google.maps.event.trigger(map, "resize");
    map.setCenter(lastPos);
	
	}, 100);
}
 
$("#printbutton").click(function() {
		
    $("#btn-exit-full-screen").toggle();
    $("#printbutton").toggle();
	
     if (center_map(800)) {
   	
    google.maps.event.addListenerOnce(map, "tilesloaded", function(){
		google.maps.event.addListenerOnce(map, "idle", function(){		
	
		  window.setTimeout(loadPrint, 100);  
	  
		});
		 });	  

    document.getElementById("ordermap").style.height = googleMapHeight + "px";
    document.getElementById("ordermap").style.width = googleMapWidth + "px";		
		
     }
  });
	
$("#btn-enter-full-screen").click(function() {

    $("#map-container").css({
        position: "fixed",
        left: "0",
		top: "36px",
        width: "100%",
        backgroundColor: "white",
		height: "calc(100% - 36px)"
    });

    $("#ordermap").css({
        height: "100%"
    });

    google.maps.event.trigger(map, "resize");
	map.fitBounds(bounds);

    // Gui
    $("#btn-enter-full-screen").toggle();
    $("#btn-exit-full-screen").toggle();
    $("#printbutton").toggle();
	$("#back-top").css({
		position: "unset"
	});
	
	$(document).keyup(function(e) {
     if (e.keyCode == 27) { // escape key maps to keycode `27`
//	 alert(" escape pressed ");
	 	 $("#btn-exit-full-screen").trigger("click");
    }
});	
    return false;
});

$("#btn-exit-full-screen").click(function() {

    $("#map-container").css({
        position: "relative",
        top: 0,
        width: googleMapWidth,
        height: googleMapHeight,
        backgroundColor: "transparent"
    });

    google.maps.event.trigger(map, "resize");
	map.fitBounds(bounds);

    // Gui
    $("#btn-enter-full-screen").show();
    $("#btn-exit-full-screen").hide();
	$("#printbutton").hide();
	$("#back-top").css({
		position: "fixed"
	});
    return false;
});
  
function swapStyleSheet(sheet){
	document.getElementById("pagestyle").setAttribute("href", sheet);
}
 
<?php   echo $areajs.$orderjs; ?>
</script><?php


} else { // check for tracking OR maps
	
echo '<script>
$("#orderajaxmap").hide();
</script>';	
	
}








// if waitingstarttime use that instead



if (($row['status']) >'76' ) {


echo '<script>

// alert(" ln 987 ");

</script>';





$secmod='';
$lengthtext='';
$tottimec=strtotime($row['starttrackpause']);
$tottimed=strtotime($row['finishtrackpause']);
if (($tottimec>'1') AND ($tottimed>'1')) { $secmod=($tottimed-$tottimec); }
$tottimea=strtotime($row['collectiondate']); 
$tottimeb=strtotime($row['ShipDate']); 
$tottimedif=($tottimeb-$tottimea-$secmod);
$inputval = $tottimedif; // USER DEFINES NUMBER OF SECONDS FOR WORKING OUT | 3661 = 1HOUR 1MIN 1SEC 
$unitd ='86400';
$unith ='3600';        // Num of seconds in an Hour... 
$unitm ='60';            // Num of seconds in a min... 
$dd = intval($inputval / $unitd);       // days
$hh_remaining = ($inputval - ($dd * $unitd));
$hh = intval($hh_remaining / $unith);    // '/' given value by num sec in hour... output = HOURS 
$ss_remaining = ($hh_remaining - ($hh * $unith)); // '*' number of hours by seconds, then '-' from given value... output = REMAINING seconds 
$mm = intval($ss_remaining / $unitm);    // take remaining sec and devide by sec in a min... output = MINS 
$ss = ($ss_remaining - ($mm * $unitm));        // '*' number of mins by seconds, then '-' from remaining sec... output = REMAINING seconds. 
if ($dd=='1') {$lengthtext=$lengthtext. $dd . "day "; } if ($dd>'1' ) { $lengthtext=$lengthtext. $dd . "days "; }
if ($hh=='1') {$lengthtext=$lengthtext. $hh . "hr "; } if ($hh>'1') { $lengthtext=$lengthtext. $hh . "hrs "; }
if ($mm>'1' ) {$lengthtext=$lengthtext. $mm . "mins "; } if ($mm=='1') {$lengthtext=$lengthtext. $mm . "min "; }
if ($dd) {} else { if ($mm) {   
$thrs= number_format((($mm/60)+$hh), 2, '.', '');
$lengthtext=$lengthtext.'('. (float)$thrs .'hrs)'; } }
} // ends check greater than status 76

 if (trim($lengthtext)) {  $lengthtext= "Tot ".$lengthtext; } 
 
 echo '
 <script>

 </script>
 ';



 if (date('U', strtotime($row['collectionworkingwindow']))>10) { 
$collectiontext= ' '.time2str(($row['collectionworkingwindow']));
 } else {
$collectiontext=' '.time2str(($row['targetcollectiondate']));  }
 
 
if (date('U', strtotime($row['deliveryworkingwindow']))>10) { 

 $deliverytext= time2str($row['deliveryworkingwindow']); }

 else { $deliverytext=time2str(($row['duedate']));  }


 
 if ($row['collectiondate']>'10') { $collectiondatetext= time2str($row['collectiondate']); } else {
	 $collectiondatetext='';
 }
 
 
 if ($row['ShipDate']>'10') { $ShipDatetext= time2str($row['ShipDate']); } else {
	 $ShipDatetext='';
 } 
 
 
 
 

  echo '
<script>
 $("#collectiontext").html("'.$collectiontext.'"); 
 $("#collectiondatetext").html("'.$collectiondatetext.'");
 $("#deliverytext").html("'.$deliverytext.'");
 $("#ShipDatetext").html("'.$ShipDatetext.'");
 $("#totaltime").html("'.$lengthtext.'");
</script>
 ';	








 
 
 
 
 
	function time2str($ts)
	{
		if(!ctype_digit($ts))
			$ts = strtotime($ts);


// echo ' cj 5643 ';		
		
		$tempdaydiff=date('z', $ts)-date('z');
		
// echo $tempday.' '.date('z');		// passed date day
		
// 		$tempday<>date('z', $ts)
		
//		$tempnay
		
		
//		echo $tempdaydiff;
		
		
		$diff = time() - $ts;
		if($diff == 0)
			return 'now';
		elseif($diff > 0)
		{
			$day_diff = floor($diff / 86400);
			if($day_diff == 0)
			{
				if($diff < 60) return ' Just now. ';
				if($diff < 120) return ' 1 min ago. ';
				if($diff < 3600) return ' '.floor($diff / 60) . ' min ago. ';
				if($diff < 7200) return ' 1 hr, ' . floor(($diff-3600) / 60) . ' min ago. ';
				
				
			if($diff < 86400) return floor($diff / 3600) . ' hours ago';
			
			}
			
			if($tempdaydiff=='-1') { return 'Yesterday '. date('A', $ts).'. '; }
			
//			if($day_diff == 1) return 'Yesterday';
			if($day_diff < 7) return ' Last '. date('D A', $ts).'. ';
			
			//date('D', $ts).' '. $day_diff . ' days ago';
	

	if($day_diff < 31) return date('D', $ts).' '. ceil($day_diff / 7) . ' weeks ago. ';
			if($day_diff < 60) return 'Last month';
			return date('D M Y', $ts);
		}
		else
		{
			$diff = abs($diff);
			$day_diff = floor($diff / 86400);
			if($day_diff == 0)
			{
				if($diff < 120) return 'In a minute';
				if($diff < 3600) return 'In ' . floor($diff / 60) . ' mins. ';
				if($diff < 7200) { return ' 1hr, ' . floor(($diff-3600) / 60) . ' mins. '; }
			//	if(($diff < 86400) and ($tempday<>date('z', $ts))) {  return ' Tomorrow ';    }
				
				if($diff < 86400) return ' ' . floor($diff / 3600) . ' hrs. ';
			}
			if($tempdaydiff == 1) return ' Tomorrow '. date('A', $ts).'. ';
			if($day_diff < 4) return date(' D A', $ts);
			if($day_diff < 7 + (7 - date('w'))) return date('D ', $ts).'next week. ';
			if(ceil($day_diff / 7) < 4) return date('D ', $ts).' in ' . ceil($day_diff / 7) . ' weeks. ';
			if(date('n', $ts) == date('n') + 1) return date('D', $ts).' next month. ';
			return date('D M Y', $ts);
		}
	}

//////////////////     ENDS RELATIVE DATE FUNCTION                ////////////////////////////////

 
 

$dbh=null;

?>