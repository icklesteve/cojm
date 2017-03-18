<?php 

/*
    COJM Courier Online Operations Management
	gpstracking.php - Displays rider GPS history
    Copyright (C) 2017 S.Young cojm.co.uk

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
if ($globalprefrow['forcehttps']>0) { if ($serversecure=='') { header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include "changejob.php";
$title='COJM : '.$cyclistid;
$row='';
$dstart='';
$gmapdata='';
$i='';
$linecoords='';
$loop='0';
$latestlat='';
$latestlon='';
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
$foundcache=0;

$max_lat = '-99999';
$min_lat =  '99999';
$max_lon = '-99999';
$min_lon =  '99999';

$testfile='';

if (isset($_POST['confirmgpx'])) { $confirmgpx=trim($_POST['confirmgpx']); } else { $confirmgpx=''; }
if (isset($_GET['from'])) { $start=trim($_GET['from']); } else { $start=''; }
if (isset($_GET['to'])) { $end=trim($_GET['to']); } else { $end=''; }
if (isset($_GET['newcyclist'])) { $CyclistID=trim($_GET['newcyclist']); } else { $CyclistID='all'; }


$thisCyclistID=$CyclistID;

if (isset($_GET['clientview'])) { $clientview=trim($_GET['clientview']); } else { $clientview=''; }


if ($start) { // $inputstart
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
} // $error.=' sqlstart is '. $sqlstart .'<br />';




if ($end) { // $dend

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



?><!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height " >
<link rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<link rel="stylesheet" href="css/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>
<script src="https://maps.google.com/maps/api/js?libraries=geometry&key=<?php echo $globalprefrow['googlemapapiv3key']; ?>"></script>
<?php if ($clientview=='cluster') { echo '<script type="text/javascript" src="js/markerclusterer.js"></script> '; } ?>
<style>
 div.info {  color:green; font-weight:bold; } 
form#cvtc div.ui-state-highlight.ui-corner-all.p15 input.ui-autocomplete-input.ui-widget.ui-widget-content { width:200px; }

#toploader { display:block; }
</style>
<title>COJM GPS Tracking</title>
<script>
 
var max_lat = [];
var min_lat = [];
var max_lon = [];
var min_lon = [];
var cluster;
var gmarkers = [];
var markercount = [];
var lineplotscount = [];

function initialize() {

    var geoXml = null;
    var geocoder = null;
    var marker, i, j, lat, lng;
    var element = document.getElementById("map-canvas");
    var imagehighlight = {
        url: "../images/plot-20-20-339900-square-pad.png",
        size: new google.maps.Size(20, 20),
        origin: new google.maps.Point(0,0),
        anchor: new google.maps.Point(10, 10)
        };  
		
    var mapTypeIds = ["OSM", "roadmap", "satellite", "OCM"];
    var map = new google.maps.Map(element, {
        center: new google.maps.LatLng(<?php echo $globalprefrow['glob1'].','.$globalprefrow['glob2']; ?>),
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

    var osmcopyr="<span style='background: white; color:#444444; padding-right: 6px; padding-left: 6px; margin-right:-12px;'> &copy; <a style='color:#444444;' href='https://www.openstreetmap.org/copyright' target='_blank'>OpenStreetMap</a> contributors</span>";


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
        } else {
            $("div#outerdiv").text("");
        }
    });


    // if OSM / OCM set as default, show copyright
    $(document).ready(function() {
        setTimeout(function() {
            $("div#outerdiv").html(osmcopyr);
        },3000);
    });
 
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

<?php

$dinterim=$dstart;

while ($dinterim<$dend) { // each day loop

    $dinterif=$dinterim+'86399';

    // $tabledatestart= date('H:i A ', $smap['timestamp']); 
    
    
    // dstart - submit start
    // dinterim - loop start
    // dinterif - loop finish
    // dend - submit finish
    
    
    // $error.='<br /> dstart : ' . date('H:i A D j M',$dstart) . ' <br />dinterim : '.date('H:i A D j M',$dinterim).' <br />dinterif : '.date('H:i A D j M',$dinterif).' <br /> dend '.date('H:i A D j M',$dend).' <hr />';
    
    
    if ($thisCyclistID == 'all') { // choose which sql lookup for rider or all riders for timestamp given
        $sql="SELECT * FROM instamapper
        INNER JOIN Cyclist ON instamapper.device_key = Cyclist.trackerid
        WHERE `timestamp` >= ? 
        AND `timestamp` <= ? 
        GROUP BY device_key 
        ORDER BY `timestamp` ASC "; 
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array($dinterim, $dinterif));
    } else { 
        $sql="SELECT * FROM instamapper
        INNER JOIN Cyclist ON instamapper.device_key = Cyclist.trackerid 
        WHERE `timestamp` >= ? AND `timestamp` <= ?
        AND `CyclistID` = ? 
        GROUP BY device_key 
        ORDER BY `timestamp` ASC ";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array($dinterim, $dinterif, $thisCyclistID));
    }
    

    $row_count = $stmt->rowCount();
    $prevts='';
    $tabledatestart='';
    
    if ($stmt) {
    
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // got a valid device key for a particular day
            $foundtracks++;
            $device_key=$row['device_key'];
            
            $tabledatestart= date('H:i A ', $row['timestamp']); 
            $cojmname=$row['cojmname'];
            $CyclistID=$row['CyclistID'];
            
            
            $tabledate= date('D j M ', $dinterim); 
            $tableyear= date('Y', $dinterim);
                
                
            $tabledatelink='clientviewtargetcollection.php?clientid=all&timetype=tarcollect&from='. date(('j'), $dinterim). 
            '%2F' .date(('n'), $dinterim).'%2F'. date(('Y'), $dinterim).'%2F&to='. date(('j'), $dinterim).'%2F'. date(('n'), $dinterim).'%2F'.
            date(('Y'), $dinterim). '&servicetype=all&deltype=all&orderby=targetcollection&clientview=normal&viewcomments=normal&statustype=all'.
            '&newcyclistid='.$CyclistID;
    
            // $error.=  '<br /> Interim is '.date('H:i D j M', $dinterif);
            
            
            $checkdate=date('Y-m-d', $dinterim);
            
            $today=date('Y-m-d');
            
            $displaydate=date('D j M', $dinterim);
            $displayyear=date('Y', $dinterim);
            
            $markervar='markers'.date('Y_m_d', $dinterim).'_'.$device_key;
            $linevar='line'.date('Y_m_d', $dinterim).'_'.$device_key;
            $oro=date('Y_m_d', $dinterim).'_'.$device_key;
            
            
            
            if ($checkdate==$today) { // not yet cached, use db
    
                $orderjs=' var '.$markervar.' = [';
                $linecoords='';
                $prevts='';
                $i='0';
                $j=0;
                
                $sql = "SELECT latitude, longitude, speed, timestamp FROM `instamapper`  
                WHERE `device_key` = ? 
                AND `timestamp` >= ?  
                AND `timestamp` <= ?
                ORDER BY `timestamp` ASC"; 
    
                $statement = $dbh->prepare($sql);
                $statement->execute([$device_key,$dinterim,$dinterif]);
                if (!$statement) throw new Exception("Query execution error.");
                $liveresult = $statement->fetchAll();
                foreach ($liveresult as $map) {
                    $map['latitude']=round($map['latitude'],5);
                    $map['longitude']=round($map['longitude'],5);
                
                    if($map['longitude']>$max_lon) { $max_lon = $map['longitude']; }
                    if($map['longitude']<$min_lon) { $min_lon = $map['longitude']; }
                    if($map['latitude']>$max_lat) { $max_lat = $map['latitude']; }
                    if($map['latitude']<$min_lat)  { $min_lat = $map['latitude']; }
                
                    $i=$i+'1';
                    $linecoords=$linecoords.' ['.$map['latitude'] . "," . $map['longitude'].'],';
                    $thists=date('H:i A D j M ', $map['timestamp']);
                    $tabledatefinish=date('H:i A', $map['timestamp']);
                    
                    if ($thists<>$prevts) {
                        $j++;    
                        $comments=date('H:i D j M ', $map['timestamp']).'<br />';
                        if ($map['speed']) {
                            $comments.= round($map['speed']);
                            if ($globalprefrow['distanceunit']=='miles') { $comments.= 'mph '; } 
                            if ($globalprefrow['distanceunit']=='km') { $comments.= 'km ph '; }
                        }
                        $orderjs.= '["' . $comments .'",'. $map['latitude'] . ',' . $map['longitude'] . ',"' . date("U", $map["timestamp"]) . "_" . $device_key . "_" . $i .'"],'; 
                        $prevts=date('H:i A D j M ', $map['timestamp']);
                        $latestlat=$latestlat+$map['latitude'];
                        $latestlon=$latestlon+$map['longitude'];
                        $loop++;
                    }
                }
    
                
                $orderjs = rtrim($orderjs, ',');
                $linecoords = rtrim($linecoords, ',');
                    
                $orderjs.=  '
                    ]; 
                
                var '.$linevar.' = [
                '.$linecoords. '
                ];
                
                    markercount.push("'.$j.'");
                    lineplotscount.push("'.$i.'");
                    max_lon.push("'.$max_lon.'"); 
                    min_lon.push("'.$min_lon.'"); 
                    max_lat.push("'.$max_lat.'"); 
                    min_lat.push("'.$min_lat.'");  
                ';
                
                echo $orderjs;
                
                
                $tabletext.='<tr id="'.$oro.'">
                <td>'.$cojmname.'</td>
                <td><a href="'.$tabledatelink.'" title="">'.$tabledate.'</a></td>
                <td>'.$tabledatestart.'</td>
                <td>'.$tabledatefinish.'</td>
                <td>Live Database</td></tr>';
                
                $foundincache=0;
                
            } // finishes track is today
            else { // track is not today
                $testfile="cache/jstrack/".date('Y/m', $dinterim).'/'.date('Y_m_d', $dinterim).'_'.$device_key.'.js';
                if (file_exists($testfile)) { $foundincache=1; } else { $foundincache=0; }
                
                if ($foundincache<>1) {
                    $stmt = $dbh->prepare("SELECT cojmadmin_id FROM cojm_admin 
                    WHERE  cojm_admin_stillneeded='1' 
                    AND cojmadmin_rider_gps='1' 
                    AND cojmadmin_rider_id= ?
                    AND cojm_admin_rider_date= ?
                    LIMIT 0,1");
                    $stmt->execute([$device_key,$checkdate]);
                    $gpsadminrow = $stmt->fetchColumn();
                
                    // $error.= $alreadyindbquery;
                
                    if($gpsadminrow) {
                        $tableerror.=' <tr class="error"> <td>'.$cojmname.'</td> <td colspan="2" title="'.$displayyear.'">'.$displaydate.' </td> <td colspan="2"> Awaiting Caching  </td> </tr> ';
                    }
                    else {
                        $stmt = $dbh->prepare("INSERT INTO cojm_admin 
                        (cojm_admin_stillneeded, cojmadmin_rider_gps, cojmadmin_rider_id, cojm_admin_rider_date) 
                            VALUES ('1', '1', ?, ? ) ");
                        $result = $stmt->execute([$device_key,$checkdate]);
                        if ($result){
                            $tableerror.=' <tr class="error"> <td>'.$cojmname.'</td> <td>'.$displaydate.' </td> <td colspan="3"> Cache task created </td> </tr> ';
                        }
                    } // check job already in admin q
                }
                
                
                
                
                if ($foundincache==1) {
                    $includejs[] = ' <script src="'.$testfile.'" > </script>';
                    $foundcache++;
                    
                    $tstmt = $dbh->prepare("SELECT timestamp FROM `instamapper` 
                    WHERE `device_key` = ?
                    AND `timestamp` >= ? 
                    AND `timestamp` <= ?
                    ORDER BY `timestamp` DESC 
                    LIMIT 0,1 ;");
                    $tstmt->execute([$device_key,$dinterim,$dinterif]);
                    $fmap = $tstmt->fetchColumn();
                    $tabledatefinish= date('H:i A ', $fmap);
                    
                    $tabletext.='<tr id="'.$oro.'">
                    <td>'.$cojmname.'</td>
                    <td><a href="'.$tabledatelink.'" title="'.$tableyear.'">'.$tabledate.'</a></td>
                    <td>'.$tabledatestart.'</td>
                    <td>'.$tabledatefinish.'</td>
                    <td>';
                    
                    $tabletext.='<button class="clrcachebtn" id="clrcache-'.$oro.'">Refresh Cache</button>';
                    $tabletext.='</td> </tr>';
                    
                    ?>
                        $("#clrcache-<?php echo $oro; ?>").click(function(){
                            $.ajax({
                                url: "ajaxchangejob.php",
                                data: {
                                    page: "ajaxremovegpscache",
                                    folder: "<?php echo date(('Y/m'), $dinterim); ?>",
                                    trackingid: "<?php echo $oro; ?>" },
                                    type: "post",
                                    success: function (data) {
                                        $("#infotext").append(data);
                                    },
                                complete: function () {
                                    showmessage();
                                }
                            });
                        });
                    <?php
                    
                }
            }
            
            
            if ((file_exists($testfile)) or ($checkdate==$today)) { // create actions js for each rider day
                
                if ($clientview=='cluster') {
                    ?>
                        // in daily loop
                        cluster=cluster +  (<?php echo $linevar; ?>);
                        for (var i = 0; i < <?php echo $linevar; ?>.length; i++) {
                            var lat = <?php echo $linevar; ?>[i][0];
                            var lng = <?php echo $linevar; ?>[i][1];
                            var latLng = new google.maps.LatLng(lat, lng);
                            var marker = new google.maps.Marker({map: map, position: latLng,});
                            gmarkers.push(marker);
                        }
                        
                    <?php	
                        
                }
                else { // clientview Normal
                
                ?>
    
                    var gmarkers<?php echo $oro; ?>=[];
                
                    for (i = 0; i < markers<?php echo $oro; ?>.length; i++) {
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(<?php echo $markervar; ?>[i][1], <?php echo $markervar; ?>[i][2]),
                            map: map,
                            icon: image,
                        });
                        
                        gmarkers<?php echo $oro; ?>.push(marker);
                        
                        google.maps.event.addListener(marker, "mouseover", (function(marker, i) {
                            return function() {
                                $("#toploader").show();
                                infowindow.setContent(" <div class='info'> " + <?php echo $markervar; ?>[i][0] + "<div class='ajaxinfowin'></div> </div>");
                                var markervar = <?php echo $markervar; ?>[i][3];
                                $.ajax({
                                    type: "POST",
                                    url:"ajax_lookup.php",
                                    data: {
                                        markervar: markervar,
                                        lookuppage: "ajaxgpsorderlookup"
                                    },
                                    success: function(data){
                                        $(".ajaxinfowin").html(data);
                                    },
                                    complete: function (){
                                        $("#toploader").fadeOut();
                                    },
                                });
                                infowindow.setOptions({ disableAutoPan: true });
                                infowindow.open(map, marker);
                                $("tr#<?php echo $oro; ?>").addClass("highlight");
                                polyline<?php echo $oro; ?>.setOptions({strokeColor: "#339900", strokeWeight: 4 });
                                
                                for (j=0; j<gmarkers<?php echo $oro; ?>.length; j++) {
                                    gmarkers<?php echo $oro; ?>[j].setIcon(imagehighlight);
                                    gmarkers<?php echo $oro; ?>[j].setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
                                }
                            };
                        })(marker, i));
                        
                        google.maps.event.addListener(marker, "mouseout", function() {
                            $("tr#<?php echo $oro; ?>").removeClass( "highlight" );
                            polyline<?php echo $oro; ?>.setOptions({strokeColor: "#000000", strokeWeight: 4 });
                            for (var j=0; j<gmarkers<?php echo $oro; ?>.length; j++) {
                                gmarkers<?php echo $oro; ?>[j].setIcon(image);
                                gmarkers<?php echo $oro; ?>[j].setZIndex(1);
                            }
                        });
                    }
                    
                    var route<?php echo $oro; ?> = [];
                    for (j = 0; j < line<?php echo $oro; ?>.length; j++) {
                        lat = line<?php echo $oro; ?>[j][0];
                        lng = line<?php echo $oro; ?>[j][1];
                        marker = new google.maps.LatLng(lat, lng);
                        route<?php echo $oro; ?>.push(marker);
                    }
                    
                    var polyline<?php echo $oro; ?> = new google.maps.Polyline({
                        path: route<?php echo $oro; ?>,
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
                
                
                    $("tr#<?php echo $oro; ?>").mouseover(function() {
                        $("tr#<?php echo $oro; ?>").addClass( "highlight" );
                        polyline<?php echo $oro; ?>.setOptions({
                            strokeColor: "#339900",
                            strokeWeight: 4}
                        );
                        
                        for (var j=0; j<gmarkers<?php echo $oro; ?>.length; j++) {
                            gmarkers<?php echo $oro; ?>[j].setIcon(imagehighlight);
                            gmarkers<?php echo $oro; ?>[j].setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
                        }
                    });
                    
                    $("tr#<?php echo $oro; ?>").mouseout(function() {
                        $("tr#<?php echo $oro; ?>").removeClass( "highlight" );
                        polyline<?php echo $oro; ?>.setOptions({
                            strokeColor: "#000000", 
                            strokeWeight: 4}
                        );
                        for (var j=0; j<gmarkers<?php echo $oro; ?>.length; j++) {
                            gmarkers<?php echo $oro; ?>[j].setIcon(image);
                            gmarkers<?php echo $oro; ?>[j].setZIndex(1);
                        }
                    });
                    
                <?php
                } // ends view = normal, not clustered
            } // ends cached ok or today check
        } // ends rows in individual day lookup
    
    }
    
    $dinterim=$dinterim+'86400';

} // each day loop


if ($clientview=='cluster') { echo ' var markerCluster = new MarkerClusterer(map, gmarkers);  '; } 

?>

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
        var menuheight=0;
        $(".top_menu_line").each(function( index ) {
            if ($(this).is(':visible')) {
                menuheight = menuheight + $( this ).height();
            }
        });
//        var h = ;
        $("#gmap_wrapper").css("height", ($(window).height() - menuheight));
    }).resize();

    geocoder = new google.maps.Geocoder(); 
    window.showAddress = function(address) {
        geocoder.geocode( { 
            "address": address + " , UK ",
            "region": "uk",
            "bounds": bounds 
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                    map.setCenter(results[0].geometry.location);
                    var infowindow = new google.maps.InfoWindow({
                        content: "<div class='info'>"+address+"</div>",
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
    };


    function sum(input){
        
        if (toString.call(input) !== "[object Array]") { 
            return false;  
        }
        
        var total =  0;  
        for(var i=0;i<input.length;i++) {
            if(isNaN(input[i])){  
                continue;  
            }
            total += Number(input[i]);  
        }  
        return total;  
    }
    

    $("#javastotals").html("Total " + sum(markercount) + " infopoints, " + sum(lineplotscount) + " plots.");
    
    google.maps.event.addListenerOnce(map, 'idle', function(){ //loaded fully
        $("#toploader").fadeOut();
    });
}
 

  $(document).ready(function() { // js for date range picker & rider selector needs to be out of initialise in case of 0 rows
        $( "#combobox" ).combobox();
        $("#rangeBa, #rangeBb").daterangepicker();  
    });
    function comboboxchanged() { };


</script>
<?php

echo '</head><body ';

 if ($foundtracks>'0') { echo 'onload="initialize()" '; }
echo '>';

$adminmenu=0;
$filename="gpstracking.php";
include "cojmmenu.php"; 


// loading icon
?>
<div id="gmap_wrapper">
<div class="full_map" id="search_map">
<div id="map-canvas" class="onehundred" > </div>
</div>
<div class="gmap_left" id="scrolltable">
<div class="pad10">
<form action="gpstracking.php" method="get" id="cvtc"> 
<div class="ui-state-highlight ui-corner-all p15">

<?php

$query = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' AND Cyclist.CyclistID > '1' ORDER BY CyclistID "; 
$data = $dbh->query($query)->fetchAll();

echo ' <select id="combobox" name="newcyclist" style="width:200px;" class=" ui-state-highlight ui-corner-left">';
echo ' <option value="all"';

if ($thisCyclistID == 'all') {echo ' selected="selected" '; }
echo '>All '.$globalprefrow['glob5'].'s</option>';

foreach ($data as $riderrow ) {
    print ("<option "); 
    if ($riderrow['CyclistID'] == $thisCyclistID) { echo ' selected="selected" ';  } 
    echo ' value="'. $riderrow['CyclistID'].'">'.$riderrow['cojmname'].'</option>';
}
print ("</select>"); 	
	
echo '
    <input class="ui-state-default ui-corner-all pad" size="10" type="text" name="from" value="'. $inputstart .'" id="rangeBa" />			
    To <input class="ui-state-default ui-corner-all pad"  size="10" type="text" name="to" value="'.  $inputend.'" id="rangeBb" /> 

 <select name="clientview" class="ui-state-highlight ui-corner-left">
 <option '; if ($clientview=='normal') { echo 'selected'; } echo ' value="normal">Normal View</option>
 <option '; if ($clientview=='cluster') { echo 'selected'; } echo ' value="cluster">Clustered</option>
 </select>

 <button type="submit" >Search</button> </div></form>';

 
if ($foundtracks=='0') { echo ' <h1> No Results Found </h1> '; }

if ($tableerror) { echo '<table><tbody>'.$tableerror.'</tbody></table>'; }

echo $error;

if ($foundtracks<>'0') {
    echo '
    <form action="#" onsubmit="showAddress(this.address.value); return false" style=" background:none;">
    <input title="Address Search" type="text" style="width: 274px; padding-left:6px;" name="address" placeholder="Map Address Search . . ." 
    class="ui-state-default ui-corner-all address" />
    </form>	
    ';
    
    echo ' <br /> <p>'.$foundtracks.' tracks found, '.$foundcache.' cached.</p> ';
    
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
    <tr><td colspan="5"><span id="javastotals"> </span></td></tr>
    </tbody></table>
    ';
}
else {
    echo ' <script>
    $("#toploader").hide();
    </script> ';
}

?>

<br />
<hr />
<p>Mouseover info points to see jobs which are / were en route. </p>

<hr />
<br />
</div> 
</div> 
</div>
<?php

$page='GPS Tracking';

include "footer.php";

echo join(" ", $includejs); 

echo '  </body> </html>';
