<?php
/*
    COJM Courier Online Operations Management
	opsmap-new-area.php - Add / Edit Opsmap
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
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
$filename="opsmap-new-area.php";

include "C4uconnect.php";

    if (isset($_GET['areaid'])) { 
        $areaid=trim($_GET['areaid']);
    }
    else if (isset($_POST['areaid'])) { 
            $areaid=trim($_POST['areaid']);
        }
    else {
    
    $areaid=''; 
}


include "changejob.php"; // page = editarea or opsmapnewarea

$opsname='';
$descrip='';
$istoplayer='';
$corelayer='0';
$inarchive='';
$coord=array();


$query = "SELECT type, inarchive, corelayer, lat, lng, opsmapid, opsname, istoplayer, descrip, AsText(g) AS POLY FROM opsmap WHERE opsmapid=? "; 

$parameters = array($areaid);
$statement = $dbh->prepare($query);
$statement->execute($parameters);
$row = $statement->fetch(PDO::FETCH_ASSOC);



?><!DOCTYPE html>
<html lang="en">
<head>
<meta name="HandheldFriendly" content="true" >
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, height=device-height" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<link rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<link rel="stylesheet" href="css/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="https://maps.google.com/maps/api/js?libraries=geometry&amp;key=<?php echo $globalprefrow['googlemapapiv3key']; ?>"></script>
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>
<script src="js/richmarker.js" type="text/javascript"></script>
<title>OpsMap Area <?php echo $row['opsname']; ?></title>
<style> #toploader { display:inline; } </style>
<script type="text/javascript"> 

var markers = [];
var poly, map;
var path = new google.maps.MVCArray();
var bounds;


// custom label text hides labels
google.maps.Marker.prototype.setLabel = function(label){
        this.label = new MarkerLabel({
          map: this.map,
          marker: this,
          text: label
        });
        this.label.bindTo('position', this, 'position');
    };
var MarkerLabel = function(options) {
        this.setValues(options);
        this.span = document.createElement('span');
        this.span.className = 'map-marker-label';
    };
MarkerLabel.prototype = $.extend(new google.maps.OverlayView(), {
        onAdd: function() {
            this.getPanes().overlayImage.appendChild(this.span);
            var self = this;
        //    this.listeners = [ google.maps.event.addListener(this, 'position_changed', function() { self.draw();    })];
        },
        draw: function() {
            var text = "";
            var position = this.getProjection().fromLatLngToDivPixel(this.get('position'));
            this.span.innerHTML = text;
            this.span.style.left = (position.x - (markerSize.x / 2)) - (text.length * 3) + 10 + 'px';
            this.span.style.top = (position.y - markerSize.y + 40) + 'px';
        }
    });




function initialize() {
        var geocoder = null;
        $(function(){ $(".normal").autosize();	});

        var element = document.getElementById("map-canvas");

        var mapTypeIds = ["OSM", "roadmap", "satellite", "OCM"];
			
		var map = new google.maps.Map(element, {
                center: new google.maps.LatLng(<?php echo $globalprefrow['glob1'].','.$globalprefrow['glob2']; ?>),
                zoom: 11,
                mapTypeId: "OSM",
			    draggableCursor: "crosshair",
				 mapTypeControl: true,
				 scaleControl: true,
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
	
    var osmcopyr="<span style='background: white; color:#444444; padding-right: 6px; padding-left: 6px; margin-right:-12px;'> &copy; " +
    " <a style='color:#444444' href='https://www.openstreetmap.org/copyright' target='_blank'>OpenStreetMap</a> contributors</span>";

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
    },3000);
    });

	
    var padded_points=[];
    
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
        new google.maps.LatLng(85,180)
    ];
  
  	
	poly = new google.maps.Polygon({
        strokeWeight: 3,
        fillColor: '#5555FF',
        map: map,
	 	clickable: false
    });
	
    poly.setPaths(new google.maps.MVCArray([path]));
    google.maps.event.addListener(map, 'click', addPoint);


	var image = {
    url: '<?php echo $globalprefrow['clweb3']; ?>',
    size: new google.maps.Size(20, 20),
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(10, 10)
    };  
	
    geocoder = new google.maps.Geocoder(); 
    $("#areageocodeaddress").change(function (e) { // map address search
    // e == our event data
    e.preventDefault();

	var addtocheck=$("#areageocodeaddress").val();

    if (addtocheck.length>2) {
	
        geocoder.geocode( {
        "address": addtocheck + " , UK ",
        "region":   "uk",
        "bounds": bounds 
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
            if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
            map.setCenter(results[0].geometry.location);
                var infowindow = new google.maps.InfoWindow(
                    { content: "<div class='info'>"+addtocheck+"</div>",
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
});

<?php	


if ($row) { // show passed area
    $max_lat = '-99999';
    $min_lat =  '99999';
    $max_lon = '-99999';
    $min_lon =  '99999';

    $mtype=$row['type'];
    $mopsname=$row['opsname'];
    $mdescrip=$row['descrip'];
    $mistoplayer=$row['istoplayer'];
    $corelayer=$row['corelayer'];
    $minarchive=$row['inarchive'];

	$p=$row['POLY'];

    if ($p<>'') { // there is text in the polygon field for the main area :-)
 
        $trans = array("POLYGON" => "", "((" => "", "))" => "");
        $p= strtr($p, $trans);
        //	$moreinfotext.='<br /> p is '.$p;
        $pexploded=explode( ',', $p );
        $js='  var polymarkers = [ ';
        foreach ($pexploded as $v) {
            // $moreinfotext.= "Current value of \$a: $v.\n";
            $transf = array(" " => ",");
            $v= strtr($v, $transf);
            // $moreinfotext.= " $v.\n";
            $js.=' new google.maps.LatLng('.$v.'),';
	
            // get lat / lon pairings in array to test if clockwise or anti
            //	$coord[]=$v;
		
            $vexploded=explode( ',', $v );
            $tmpi='1';
	
            foreach ($vexploded as $testcoord) {
                if ($tmpi % 2 == 0) {
                    if($testcoord>$max_lon) { $max_lon = $testcoord; }
                    if($testcoord<$min_lon) { $min_lon = $testcoord; }
                } else { 
                if($testcoord>$max_lat) { $max_lat = $testcoord; }
                if($testcoord<$min_lat) { $min_lat = $testcoord; }
                }
                $tmpi++;
            }
        } // ends each in array

        $js = rtrim($js, ','); 
        echo $js.'    ]; 
        
            $.each(polymarkers, function(key, pt) {
        var current_point = pt; //The current point
        var next_point = polymarkers[key + 1]; //The point immediately after the current point

        //Check that were not on the last point 
        if (typeof next_point !== "undefined") {
            //Get a 10th of the difference in latitude between current and next points
            var lat_incr = (next_point.lat() - current_point.lat()) / 10;

            //Get a 10th of the difference in longitude between current and next points
            var lng_incr = (next_point.lng() - current_point.lng()) / 10;

            //Add the current point to a new padded_points array
            padded_points.push(current_point);

            //Now add 10 additional points at lat_incr & lng_incr intervals between current and next points (in the new padded_points array)
            for (var i = 1; i <= 10; i++) {
                var new_pt = new google.maps.LatLng(current_point.lat() + (i * lat_incr), current_point.lng() + (i * lng_incr));
                padded_points.push(new_pt);
            }
        }
    });
        ';

    }
    else { // no points on db, create blank map
        echo '  var polymarkers = [google.maps.LatLng('. $globalprefrow['glob1'].','.$globalprefrow['glob2'].')]; ';
        $max_lat = $globalprefrow['glob1']+0.01;
        $min_lat = $globalprefrow['glob1']+0.01;
        $max_lon = $globalprefrow['glob2']+0.01;
        $min_lon = $globalprefrow['glob2']+0.01;
    } 



    if ($corelayer) {  // has a parent area which we'll show
    
        $query = "SELECT AsText(g) AS POLY FROM opsmap WHERE opsmapid=? "; 
        $parameters = array($corelayer);
        $statement = $dbh->prepare($query);
        $statement->execute($parameters);
        $score = $statement->fetch(PDO::FETCH_ASSOC);
    
        $p=$score['POLY'];
    
        if ($p) {
            // echo ' alert(" g found "); ';
                
            $trans = array("POLYGON" => "", "((" => "", "))" => "");
            $p=strtr($p, $trans);
            $pexploded=explode( ',', $p );
            $areajs=' var polymarkers'.$corelayer.' = [ ';
            foreach ($pexploded as $v) {
                $transf = array(" " => ",");
                $v=strtr($v, $transf);
                $areajs.=' new google.maps.LatLng('.$v.'),';
            } // ends each in array
        $areajs = rtrim($areajs, ',');
        $areajs.=' ]; 

    $.each(polymarkers'.$corelayer.', function(key, pt) {
        var current_point = pt;//The current point
        var next_point = polymarkers'.$corelayer.'[key + 1]; //The point immediately after the current point

        //Check that were not on the last point 
        if (typeof next_point !== "undefined") {
            //Get a 10th of the difference in latitude between current and next points
            var lat_incr = (next_point.lat() - current_point.lat()) / 10;

            //Get a 10th of the difference in longitude between current and next points
            var lng_incr = (next_point.lng() - current_point.lng()) / 10;

            //Add the current point to a new padded_points array
            padded_points.push(current_point);

            //Now add 10 additional points at lat_incr & lng_incr intervals between current and next points (in the new padded_points array)
            for (var i = 1; i <= 10; i++) {
                var new_pt = new google.maps.LatLng(current_point.lat() + (i * lat_incr), current_point.lng() + (i * lng_incr));
                padded_points.push(new_pt);
            }
        }
    });

    poly'.$corelayer.' = new google.maps.Polygon({
        paths: [worldCoords, polymarkers'.$corelayer.'],
        strokeWeight: 4,
        strokeOpacity: 0.8,
        fillColor: "#fcd66e",
        fillOpacity: 0.45,
        strokeColor: "#000000",
        clickable: false,
        map: map
    }); '; 



        }
    } // ends has a parent area



    if ($mistoplayer=='1') {     // get right sql to select all children or siblings
        $sql="SELECT type, inarchive, corelayer, lat, lng, opsmapid, opsname, istoplayer, descrip, AsText(g) AS POLY  FROM opsmap WHERE  corelayer= :areaid ORDER BY opsname ASC ";        
            
        $prep = $dbh->prepare($sql);
        $prep->bindParam(':areaid', $areaid, PDO::PARAM_INT);
        $prep->execute();
        $result = $prep->fetchAll();
        $titletext=' Children';
        
    }
    elseif ($corelayer) {

        //        echo ' alert(" core layer '. $corelayer.' ");   ';     
        $titletext=' Siblings';
        
        
        
        $sql="SELECT type, inarchive, corelayer, lat, lng, opsmapid, opsname, istoplayer, descrip, AsText(g) AS POLY  FROM opsmap WHERE opsmapid <> :areaid AND corelayer= :corelayer ORDER BY opsname ASC ";        
            
        $prep = $dbh->prepare($sql);
        $prep->bindParam(':areaid', $areaid, PDO::PARAM_INT);
        $prep->bindParam(':corelayer', $corelayer, PDO::PARAM_INT);
        $prep->execute();
        $result = $prep->fetchAll();
        
    } else {
        $result=null;
    }




    if ($result) { // there are children or siblings
        $moreinfotext.= '<h3>'.$lilsumtot.' '.$titletext.'</h3>';

        // echo ' alert(" result ");   ';
        
        foreach ($result as $lilrow) {

            $lilareaid=$lilrow['opsmapid'];
            $lilareaname=$lilrow['opsname'];
            $lilareadescrip=$lilrow['descrip'];
            $moreinfotext.= ' <p><a title="Edit Sub Area" href="opsmap-new-area.php?areaid='.$lilareaid.'">'.$lilareaname.'</a>';
            if ($lilareadescrip) {
                $moreinfotext.= ' '.$lilareadescrip; 
            }
            $moreinfotext.= '</p>';    
            
            $p=$lilrow['POLY'];
            $trans = array("POLYGON" => "", "((" => "", "))" => "");
            $p= strtr($p, $trans);
            $pexploded=explode( ',', $p );
            $js='  var polymarkers'.$lilareaid.' = [ ';
            foreach ($pexploded as $v) {
                $transf = array(" " => ",");
                $v= strtr($v, $transf);
                $js=$js.' new google.maps.LatLng('.$v.'),';
            } // ends each in array

            $js = rtrim($js, ','); 
            echo $js.'    ]; ';
?>

    poly<?php echo $lilareaid; ?> = new google.maps.Polygon({
        paths: [polymarkers<?php echo $lilareaid; ?>],
        strokeWeight: 4,
        strokeOpacity: 0.4,
        fillColor: "#ffffff",
        fillOpacity: 0.1,
        strokeColor: "#000000",
        clickable: false,
        map: map
    });
  

    var bounds<?php echo $lilareaid; ?> = new google.maps.LatLngBounds();
    var i;  
    for (i = 0; i < polymarkers<?php echo $lilareaid; ?>.length; i++) {
        bounds<?php echo $lilareaid; ?>.extend(polymarkers<?php echo $lilareaid; ?>[i]);
    }
    var cent=(bounds<?php echo $lilareaid; ?>.getCenter());

    var link='<div class="map-sub-area-label"><a href="opsmap-new-area.php?areaid=<?php echo $lilareaid; ?>" ' +
    '><?php echo $lilareaname; ?></a></div>';

 
   marker = new RichMarker({
        position: cent,
        map: map,
        draggable: false,
        content: link
    });



    $.each(polymarkers<?php echo $lilareaid; ?>, function(key, pt) {
        var current_point = pt;//The current point
        var next_point = polymarkers<?php echo $lilareaid; ?>[key + 1];//The point immediately after the current point

        //Check that were not on the last point 
        if (typeof next_point !== "undefined") {
            //Get a 10th of the difference in latitude between current and next points
            var lat_incr = (next_point.lat() - current_point.lat()) / 10;

            //Get a 10th of the difference in longitude between current and next points
            var lng_incr = (next_point.lng() - current_point.lng()) / 10;

            //Add the current point to a new padded_points array
            padded_points.push(current_point);

            //Now add 10 additional points at lat_incr & lng_incr intervals between current and next points (in the new padded_points array)
            for (var i = 1; i <= 10; i++) {
                var new_pt = new google.maps.LatLng(current_point.lat() + (i * lat_incr), current_point.lng() + (i * lng_incr));
                padded_points.push(new_pt);
            }
        }
    });

<?php

        } // ends lil area row loop
    } // ends check lil sum tot

    if (isset($areajs)) { echo $areajs; }

?>

    bounds = new google.maps.LatLngBounds();
    bounds.extend(new google.maps.LatLng(<?php echo $max_lat.", ".$min_lon; ?>)); // upper left
    bounds.extend(new google.maps.LatLng(<?php echo $max_lat.", ".$max_lon; ?>)); // upper right
    bounds.extend(new google.maps.LatLng(<?php echo $min_lat.", ".$max_lon; ?>)); // lower right
    bounds.extend(new google.maps.LatLng(<?php echo $min_lat.", ".$min_lon; ?>)); // lower left
	map.fitBounds(bounds);
	
	var k = polymarkers.length-1;
    for (var j = 0; j < k; j++) {
        var beerp = polymarkers[j];
        initialaddPoint(beerp);
		updateform();
    }
    function initialaddPoint(beerp) {
        path.insertAt(path.length, beerp);
        var marker = new google.maps.Marker({
            position: beerp,
            label: String(path.length),
            map: map,
            icon: image,
            draggable: true ,
            draggableCursor: 'crosshair'
        });
        markers.push(marker);
        marker.setTitle('' + path.length);
        google.maps.event.addListener(marker, 'click', function() {
            marker.setMap(null);
            for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
            markers.splice(i, 1);
            path.removeAt(i);
            updateform();
        });
        
        google.maps.event.addListener(marker, 'dragend', function() {
        for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
        path.setAt(i, marker.getPosition());
	  	updateform();
        });
    }
    
<?php

    }
    else { // no area passed
        $mistoplayer='';
        $minarchive ='';
    }

?>

    // left click
    function addPoint(event) {
        path.insertAt(path.length, event.latLng);
        var marker = new google.maps.Marker({
            position: event.latLng,
            label: String(path.length),
            map: map,
            icon: image,
            draggable: true ,
            draggableCursor: 'crosshair'
        });
        markers.push(marker);
        marker.setTitle('#' + path.length);
        google.maps.event.addListener(marker, 'click', function() {
            marker.setMap(null);
            for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
            markers.splice(i, 1);
            path.removeAt(i);
            updateform();
        });
        
        google.maps.event.addListener(marker, 'dragend', function() {
            for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
            path.setAt(i, marker.getPosition());
            updateform();
        });
        updateform();
    }



    // rightclick
    google.maps.event.addListener(map, 'rightclick', function(e) {

        var beer=find_closest_point_on_path(e.latLng,padded_points);
	
        path.insertAt(path.length, beer);
        var marker = new google.maps.Marker({
            position: beer,
            label: String(path.length),
            map: map,
            icon: image,
            draggable: true ,
            draggableCursor: 'crosshair'
        });
        
        markers.push(marker);
        marker.setTitle('#' + path.length);
        google.maps.event.addListener(marker, 'click', function() {
            marker.setMap(null);
            for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
            markers.splice(i, 1);
            path.removeAt(i);
            updateform();
        });
    
        google.maps.event.addListener(marker, 'dragend', function() {
            for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
            path.setAt(i, marker.getPosition());
            updateform();
        });
        updateform();
    });


	function find_closest_point_on_path(drop_pt,path_pts){

        distances = new Array();//Stores the distances of each pt on the path from the marker point 
        distance_keys = new Array();//Stores the key of point on the path that corresponds to a distance
        
        //For each point on the path
        $.each(path_pts,function(key, path_pt){
            //Find the distance in a linear crows-flight line between the marker point and the current path point
            var R = 6371; // km
            var dLat = (path_pt.lat()-drop_pt.lat()).toRad();
            var dLon = (path_pt.lng()-drop_pt.lng()).toRad();
            var lat1 = drop_pt.lat().toRad();
            var lat2 = path_pt.lat().toRad();

            var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
            var d = R * c;
            //Store the distances and the key of the pt that matches that distance
            distances[key] = d;
            distance_keys[d] = key; 
            
        });
        //Return the latLng obj of the second closest point to the markers drag origin. If this point doesn't exist snap it to the actual closest point as this should always exist
		var jon=path_pts[distance_keys[_.min(distances)]];
    return jon;
    }

    /** Converts numeric degrees to radians */
    if (typeof(Number.prototype.toRad) === 'undefined') {
        Number.prototype.toRad = function() {
            return this * Math.PI / 180;
        };
    }


    function updateform() {
        var vertices = poly.getPath();
        var contentString = ' '; 
        var firstlat = '';
        var firstlon = '';
        var ab= '';
        var ab = vertices.getAt(0);
        firstlat = ab.lat().toFixed(6);
        firstlon = ab.lng().toFixed(6); 	 

        for (var i =0; i < vertices.getLength(); i++) {
            var xy = vertices.getAt(i);
            contentString += ' ' + xy.lat().toFixed(6) + ' ' + xy.lng().toFixed(6)+ ',';
        } 
        contentString += ' ' + firstlat + ' ' + firstlon;
	   $('#vertices').val(contentString);
    }


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

    google.maps.event.addListenerOnce(map, 'idle', function(){ //loaded fully
    $("#toploader").fadeOut(750);
    });




} // ends initialise

//     Underscore.js 1.8.3
//     http://underscorejs.org
//     (c) 2009-2015 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
//     Underscore may be freely distributed under the MIT license.
(function(){
    function n(n){function t(t,r,e,u,i,o){for(;i>=0&&o>i;i+=n){var a=u?u[i]:i;e=r(e,t[a],a,t)}return e}return function(r,e,u,i){e=b(e,i,4);var o=!k(r)&&m.keys(r),a=(o||r).length,c=n>0?0:a-1;return arguments.length<3&&(u=r[o?o[c]:c],c+=n),t(r,e,u,o,c,a)}}function t(n){return function(t,r,e){r=x(r,e);for(var u=O(t),i=n>0?0:u-1;i>=0&&u>i;i+=n)if(r(t[i],i,t))return i;return-1}}function r(n,t,r){return function(e,u,i){var o=0,a=O(e);if("number"==typeof i)n>0?o=i>=0?i:Math.max(i+a,o):a=i>=0?Math.min(i+1,a):i+a+1;else if(r&&i&&a)return i=r(e,u),e[i]===u?i:-1;if(u!==u)return i=t(l.call(e,o,a),m.isNaN),i>=0?i+o:-1;for(i=n>0?o:a-1;i>=0&&a>i;i+=n)if(e[i]===u)return i;return-1}}function e(n,t){var r=I.length,e=n.constructor,u=m.isFunction(e)&&e.prototype||a,i="constructor";for(m.has(n,i)&&!m.contains(t,i)&&t.push(i);r--;)i=I[r],i in n&&n[i]!==u[i]&&!m.contains(t,i)&&t.push(i)}var u=this,i=u._,o=Array.prototype,a=Object.prototype,c=Function.prototype,f=o.push,l=o.slice,s=a.toString,p=a.hasOwnProperty,h=Array.isArray,v=Object.keys,g=c.bind,y=Object.create,d=function(){},m=function(n){return n instanceof m?n:this instanceof m?void(this._wrapped=n):new m(n)};"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=m),exports._=m):u._=m,m.VERSION="1.8.3";var b=function(n,t,r){if(t===void 0)return n;switch(null==r?3:r){case 1:return function(r){return n.call(t,r)};case 2:return function(r,e){return n.call(t,r,e)};case 3:return function(r,e,u){return n.call(t,r,e,u)};case 4:return function(r,e,u,i){return n.call(t,r,e,u,i)}}return function(){return n.apply(t,arguments)}},x=function(n,t,r){return null==n?m.identity:m.isFunction(n)?b(n,t,r):m.isObject(n)?m.matcher(n):m.property(n)};m.iteratee=function(n,t){return x(n,t,1/0)};var _=function(n,t){return function(r){var e=arguments.length;if(2>e||null==r)return r;for(var u=1;e>u;u++)for(var i=arguments[u],o=n(i),a=o.length,c=0;a>c;c++){var f=o[c];t&&r[f]!==void 0||(r[f]=i[f])}return r}},j=function(n){if(!m.isObject(n))return{};if(y)return y(n);d.prototype=n;var t=new d;return d.prototype=null,t},w=function(n){return function(t){return null==t?void 0:t[n]}},A=Math.pow(2,53)-1,O=w("length"),k=function(n){var t=O(n);return"number"==typeof t&&t>=0&&A>=t};m.each=m.forEach=function(n,t,r){t=b(t,r);var e,u;if(k(n))for(e=0,u=n.length;u>e;e++)t(n[e],e,n);else{var i=m.keys(n);for(e=0,u=i.length;u>e;e++)t(n[i[e]],i[e],n)}return n},m.map=m.collect=function(n,t,r){t=x(t,r);for(var e=!k(n)&&m.keys(n),u=(e||n).length,i=Array(u),o=0;u>o;o++){var a=e?e[o]:o;i[o]=t(n[a],a,n)}return i},m.reduce=m.foldl=m.inject=n(1),m.reduceRight=m.foldr=n(-1),m.find=m.detect=function(n,t,r){var e;return e=k(n)?m.findIndex(n,t,r):m.findKey(n,t,r),e!==void 0&&e!==-1?n[e]:void 0},m.filter=m.select=function(n,t,r){var e=[];return t=x(t,r),m.each(n,function(n,r,u){t(n,r,u)&&e.push(n)}),e},m.reject=function(n,t,r){return m.filter(n,m.negate(x(t)),r)},m.every=m.all=function(n,t,r){t=x(t,r);for(var e=!k(n)&&m.keys(n),u=(e||n).length,i=0;u>i;i++){var o=e?e[i]:i;if(!t(n[o],o,n))return!1}return!0},m.some=m.any=function(n,t,r){t=x(t,r);for(var e=!k(n)&&m.keys(n),u=(e||n).length,i=0;u>i;i++){var o=e?e[i]:i;if(t(n[o],o,n))return!0}return!1},m.contains=m.includes=m.include=function(n,t,r,e){return k(n)||(n=m.values(n)),("number"!=typeof r||e)&&(r=0),m.indexOf(n,t,r)>=0},m.invoke=function(n,t){var r=l.call(arguments,2),e=m.isFunction(t);return m.map(n,function(n){var u=e?t:n[t];return null==u?u:u.apply(n,r)})},m.pluck=function(n,t){return m.map(n,m.property(t))},m.where=function(n,t){return m.filter(n,m.matcher(t))},m.findWhere=function(n,t){return m.find(n,m.matcher(t))},m.max=function(n,t,r){var e,u,i=-1/0,o=-1/0;if(null==t&&null!=n){n=k(n)?n:m.values(n);for(var a=0,c=n.length;c>a;a++)e=n[a],e>i&&(i=e)}else t=x(t,r),m.each(n,function(n,r,e){u=t(n,r,e),(u>o||u===-1/0&&i===-1/0)&&(i=n,o=u)});return i},m.min=function(n,t,r){var e,u,i=1/0,o=1/0;if(null==t&&null!=n){n=k(n)?n:m.values(n);for(var a=0,c=n.length;c>a;a++)e=n[a],i>e&&(i=e)}else t=x(t,r),m.each(n,function(n,r,e){u=t(n,r,e),(o>u||1/0===u&&1/0===i)&&(i=n,o=u)});return i},m.shuffle=function(n){for(var t,r=k(n)?n:m.values(n),e=r.length,u=Array(e),i=0;e>i;i++)t=m.random(0,i),t!==i&&(u[i]=u[t]),u[t]=r[i];return u},m.sample=function(n,t,r){return null==t||r?(k(n)||(n=m.values(n)),n[m.random(n.length-1)]):m.shuffle(n).slice(0,Math.max(0,t))},m.sortBy=function(n,t,r){return t=x(t,r),m.pluck(m.map(n,function(n,r,e){return{value:n,index:r,criteria:t(n,r,e)}}).sort(function(n,t){var r=n.criteria,e=t.criteria;if(r!==e){if(r>e||r===void 0)return 1;if(e>r||e===void 0)return-1}return n.index-t.index}),"value")};var F=function(n){return function(t,r,e){var u={};return r=x(r,e),m.each(t,function(e,i){var o=r(e,i,t);n(u,e,o)}),u}};m.groupBy=F(function(n,t,r){m.has(n,r)?n[r].push(t):n[r]=[t]}),m.indexBy=F(function(n,t,r){n[r]=t}),m.countBy=F(function(n,t,r){m.has(n,r)?n[r]++:n[r]=1}),m.toArray=function(n){return n?m.isArray(n)?l.call(n):k(n)?m.map(n,m.identity):m.values(n):[]},m.size=function(n){return null==n?0:k(n)?n.length:m.keys(n).length},m.partition=function(n,t,r){t=x(t,r);var e=[],u=[];return m.each(n,function(n,r,i){(t(n,r,i)?e:u).push(n)}),[e,u]},m.first=m.head=m.take=function(n,t,r){return null==n?void 0:null==t||r?n[0]:m.initial(n,n.length-t)},m.initial=function(n,t,r){return l.call(n,0,Math.max(0,n.length-(null==t||r?1:t)))},m.last=function(n,t,r){return null==n?void 0:null==t||r?n[n.length-1]:m.rest(n,Math.max(0,n.length-t))},m.rest=m.tail=m.drop=function(n,t,r){return l.call(n,null==t||r?1:t)},m.compact=function(n){return m.filter(n,m.identity)};var S=function(n,t,r,e){for(var u=[],i=0,o=e||0,a=O(n);a>o;o++){var c=n[o];if(k(c)&&(m.isArray(c)||m.isArguments(c))){t||(c=S(c,t,r));var f=0,l=c.length;for(u.length+=l;l>f;)u[i++]=c[f++]}else r||(u[i++]=c)}return u};m.flatten=function(n,t){return S(n,t,!1)},m.without=function(n){return m.difference(n,l.call(arguments,1))},m.uniq=m.unique=function(n,t,r,e){m.isBoolean(t)||(e=r,r=t,t=!1),null!=r&&(r=x(r,e));for(var u=[],i=[],o=0,a=O(n);a>o;o++){var c=n[o],f=r?r(c,o,n):c;t?(o&&i===f||u.push(c),i=f):r?m.contains(i,f)||(i.push(f),u.push(c)):m.contains(u,c)||u.push(c)}return u},m.union=function(){return m.uniq(S(arguments,!0,!0))},m.intersection=function(n){for(var t=[],r=arguments.length,e=0,u=O(n);u>e;e++){var i=n[e];if(!m.contains(t,i)){for(var o=1;r>o&&m.contains(arguments[o],i);o++);o===r&&t.push(i)}}return t},m.difference=function(n){var t=S(arguments,!0,!0,1);return m.filter(n,function(n){return!m.contains(t,n)})},m.zip=function(){return m.unzip(arguments)},m.unzip=function(n){for(var t=n&&m.max(n,O).length||0,r=Array(t),e=0;t>e;e++)r[e]=m.pluck(n,e);return r},m.object=function(n,t){for(var r={},e=0,u=O(n);u>e;e++)t?r[n[e]]=t[e]:r[n[e][0]]=n[e][1];return r},m.findIndex=t(1),m.findLastIndex=t(-1),m.sortedIndex=function(n,t,r,e){r=x(r,e,1);for(var u=r(t),i=0,o=O(n);o>i;){var a=Math.floor((i+o)/2);r(n[a])<u?i=a+1:o=a}return i},m.indexOf=r(1,m.findIndex,m.sortedIndex),m.lastIndexOf=r(-1,m.findLastIndex),m.range=function(n,t,r){null==t&&(t=n||0,n=0),r=r||1;for(var e=Math.max(Math.ceil((t-n)/r),0),u=Array(e),i=0;e>i;i++,n+=r)u[i]=n;return u};var E=function(n,t,r,e,u){if(!(e instanceof t))return n.apply(r,u);var i=j(n.prototype),o=n.apply(i,u);return m.isObject(o)?o:i};m.bind=function(n,t){if(g&&n.bind===g)return g.apply(n,l.call(arguments,1));if(!m.isFunction(n))throw new TypeError("Bind must be called on a function");var r=l.call(arguments,2),e=function(){return E(n,e,t,this,r.concat(l.call(arguments)))};return e},m.partial=function(n){var t=l.call(arguments,1),r=function(){for(var e=0,u=t.length,i=Array(u),o=0;u>o;o++)i[o]=t[o]===m?arguments[e++]:t[o];for(;e<arguments.length;)i.push(arguments[e++]);return E(n,r,this,this,i)};return r},m.bindAll=function(n){var t,r,e=arguments.length;if(1>=e)throw new Error("bindAll must be passed function names");for(t=1;e>t;t++)r=arguments[t],n[r]=m.bind(n[r],n);return n},m.memoize=function(n,t){var r=function(e){var u=r.cache,i=""+(t?t.apply(this,arguments):e);return m.has(u,i)||(u[i]=n.apply(this,arguments)),u[i]};return r.cache={},r},m.delay=function(n,t){var r=l.call(arguments,2);return setTimeout(function(){return n.apply(null,r)},t)},m.defer=m.partial(m.delay,m,1),m.throttle=function(n,t,r){var e,u,i,o=null,a=0;r||(r={});var c=function(){a=r.leading===!1?0:m.now(),o=null,i=n.apply(e,u),o||(e=u=null)};return function(){var f=m.now();a||r.leading!==!1||(a=f);var l=t-(f-a);return e=this,u=arguments,0>=l||l>t?(o&&(clearTimeout(o),o=null),a=f,i=n.apply(e,u),o||(e=u=null)):o||r.trailing===!1||(o=setTimeout(c,l)),i}},m.debounce=function(n,t,r){var e,u,i,o,a,c=function(){var f=m.now()-o;t>f&&f>=0?e=setTimeout(c,t-f):(e=null,r||(a=n.apply(i,u),e||(i=u=null)))};return function(){i=this,u=arguments,o=m.now();var f=r&&!e;return e||(e=setTimeout(c,t)),f&&(a=n.apply(i,u),i=u=null),a}},m.wrap=function(n,t){return m.partial(t,n)},m.negate=function(n){return function(){return!n.apply(this,arguments)}},m.compose=function(){var n=arguments,t=n.length-1;return function(){for(var r=t,e=n[t].apply(this,arguments);r--;)e=n[r].call(this,e);return e}},m.after=function(n,t){return function(){return--n<1?t.apply(this,arguments):void 0}},m.before=function(n,t){var r;return function(){return--n>0&&(r=t.apply(this,arguments)),1>=n&&(t=null),r}},m.once=m.partial(m.before,2);var M=!{toString:null}.propertyIsEnumerable("toString"),I=["valueOf","isPrototypeOf","toString","propertyIsEnumerable","hasOwnProperty","toLocaleString"];m.keys=function(n){if(!m.isObject(n))return[];if(v)return v(n);var t=[];for(var r in n)m.has(n,r)&&t.push(r);return M&&e(n,t),t},m.allKeys=function(n){if(!m.isObject(n))return[];var t=[];for(var r in n)t.push(r);return M&&e(n,t),t},m.values=function(n){for(var t=m.keys(n),r=t.length,e=Array(r),u=0;r>u;u++)e[u]=n[t[u]];return e},m.mapObject=function(n,t,r){t=x(t,r);for(var e,u=m.keys(n),i=u.length,o={},a=0;i>a;a++)e=u[a],o[e]=t(n[e],e,n);return o},m.pairs=function(n){for(var t=m.keys(n),r=t.length,e=Array(r),u=0;r>u;u++)e[u]=[t[u],n[t[u]]];return e},m.invert=function(n){for(var t={},r=m.keys(n),e=0,u=r.length;u>e;e++)t[n[r[e]]]=r[e];return t},m.functions=m.methods=function(n){var t=[];for(var r in n)m.isFunction(n[r])&&t.push(r);return t.sort()},m.extend=_(m.allKeys),m.extendOwn=m.assign=_(m.keys),m.findKey=function(n,t,r){t=x(t,r);for(var e,u=m.keys(n),i=0,o=u.length;o>i;i++)if(e=u[i],t(n[e],e,n))return e},m.pick=function(n,t,r){var e,u,i={},o=n;if(null==o)return i;m.isFunction(t)?(u=m.allKeys(o),e=b(t,r)):(u=S(arguments,!1,!1,1),e=function(n,t,r){return t in r},o=Object(o));for(var a=0,c=u.length;c>a;a++){var f=u[a],l=o[f];e(l,f,o)&&(i[f]=l)}return i},m.omit=function(n,t,r){if(m.isFunction(t))t=m.negate(t);else{var e=m.map(S(arguments,!1,!1,1),String);t=function(n,t){return!m.contains(e,t)}}return m.pick(n,t,r)},m.defaults=_(m.allKeys,!0),m.create=function(n,t){var r=j(n);return t&&m.extendOwn(r,t),r},m.clone=function(n){return m.isObject(n)?m.isArray(n)?n.slice():m.extend({},n):n},m.tap=function(n,t){return t(n),n},m.isMatch=function(n,t){var r=m.keys(t),e=r.length;if(null==n)return!e;for(var u=Object(n),i=0;e>i;i++){var o=r[i];if(t[o]!==u[o]||!(o in u))return!1}return!0};var N=function(n,t,r,e){if(n===t)return 0!==n||1/n===1/t;if(null==n||null==t)return n===t;n instanceof m&&(n=n._wrapped),t instanceof m&&(t=t._wrapped);var u=s.call(n);if(u!==s.call(t))return!1;switch(u){case"[object RegExp]":case"[object String]":return""+n==""+t;case"[object Number]":return+n!==+n?+t!==+t:0===+n?1/+n===1/t:+n===+t;case"[object Date]":case"[object Boolean]":return+n===+t}var i="[object Array]"===u;if(!i){if("object"!=typeof n||"object"!=typeof t)return!1;var o=n.constructor,a=t.constructor;if(o!==a&&!(m.isFunction(o)&&o instanceof o&&m.isFunction(a)&&a instanceof a)&&"constructor"in n&&"constructor"in t)return!1}r=r||[],e=e||[];for(var c=r.length;c--;)if(r[c]===n)return e[c]===t;if(r.push(n),e.push(t),i){if(c=n.length,c!==t.length)return!1;for(;c--;)if(!N(n[c],t[c],r,e))return!1}else{var f,l=m.keys(n);if(c=l.length,m.keys(t).length!==c)return!1;for(;c--;)if(f=l[c],!m.has(t,f)||!N(n[f],t[f],r,e))return!1}return r.pop(),e.pop(),!0};m.isEqual=function(n,t){return N(n,t)},m.isEmpty=function(n){return null==n?!0:k(n)&&(m.isArray(n)||m.isString(n)||m.isArguments(n))?0===n.length:0===m.keys(n).length},m.isElement=function(n){return!(!n||1!==n.nodeType)},m.isArray=h||function(n){return"[object Array]"===s.call(n)},m.isObject=function(n){var t=typeof n;return"function"===t||"object"===t&&!!n},m.each(["Arguments","Function","String","Number","Date","RegExp","Error"],function(n){m["is"+n]=function(t){return s.call(t)==="[object "+n+"]"}}),m.isArguments(arguments)||(m.isArguments=function(n){return m.has(n,"callee")}),"function"!=typeof/./&&"object"!=typeof Int8Array&&(m.isFunction=function(n){return"function"==typeof n||!1}),m.isFinite=function(n){return isFinite(n)&&!isNaN(parseFloat(n))},m.isNaN=function(n){return m.isNumber(n)&&n!==+n},m.isBoolean=function(n){return n===!0||n===!1||"[object Boolean]"===s.call(n)},m.isNull=function(n){return null===n},m.isUndefined=function(n){return n===void 0},m.has=function(n,t){return null!=n&&p.call(n,t)},m.noConflict=function(){return u._=i,this},m.identity=function(n){return n},m.constant=function(n){return function(){return n}},m.noop=function(){},m.property=w,m.propertyOf=function(n){return null==n?function(){}:function(t){return n[t]}},m.matcher=m.matches=function(n){return n=m.extendOwn({},n),function(t){return m.isMatch(t,n)}},m.times=function(n,t,r){var e=Array(Math.max(0,n));t=b(t,r,1);for(var u=0;n>u;u++)e[u]=t(u);return e},m.random=function(n,t){return null==t&&(t=n,n=0),n+Math.floor(Math.random()*(t-n+1))},m.now=Date.now||function(){return(new Date).getTime()};var B={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"},T=m.invert(B),R=function(n){var t=function(t){return n[t]},r="(?:"+m.keys(n).join("|")+")",e=RegExp(r),u=RegExp(r,"g");return function(n){return n=null==n?"":""+n,e.test(n)?n.replace(u,t):n}};m.escape=R(B),m.unescape=R(T),m.result=function(n,t,r){var e=null==n?void 0:n[t];return e===void 0&&(e=r),m.isFunction(e)?e.call(n):e};var q=0;m.uniqueId=function(n){var t=++q+"";return n?n+t:t},m.templateSettings={evaluate:/<%([\s\S]+?)%>/g,interpolate:/<%=([\s\S]+?)%>/g,escape:/<%-([\s\S]+?)%>/g};var K=/(.)^/,z={"'":"'","\\":"\\","\r":"r","\n":"n","\u2028":"u2028","\u2029":"u2029"},D=/\\|'|\r|\n|\u2028|\u2029/g,L=function(n){return"\\"+z[n]};m.template=function(n,t,r){!t&&r&&(t=r),t=m.defaults({},t,m.templateSettings);var e=RegExp([(t.escape||K).source,(t.interpolate||K).source,(t.evaluate||K).source].join("|")+"|$","g"),u=0,i="__p+='";n.replace(e,function(t,r,e,o,a){return i+=n.slice(u,a).replace(D,L),u=a+t.length,r?i+="'+\n((__t=("+r+"))==null?'':_.escape(__t))+\n'":e?i+="'+\n((__t=("+e+"))==null?'':__t)+\n'":o&&(i+="';\n"+o+"\n__p+='"),t}),i+="';\n",t.variable||(i="with(obj||{}){\n"+i+"}\n"),i="var __t,__p='',__j=Array.prototype.join,"+"print=function(){__p+=__j.call(arguments,'');};\n"+i+"return __p;\n";try{var o=new Function(t.variable||"obj","_",i)}catch(a){throw a.source=i,a}var c=function(n){return o.call(this,n,m)},f=t.variable||"obj";return c.source="function("+f+"){\n"+i+"}",c},m.chain=function(n){var t=m(n);return t._chain=!0,t};var P=function(n,t){return n._chain?m(t).chain():t};m.mixin=function(n){m.each(m.functions(n),function(t){var r=m[t]=n[t];m.prototype[t]=function(){var n=[this._wrapped];return f.apply(n,arguments),P(this,r.apply(m,n))}})},m.mixin(m),m.each(["pop","push","reverse","shift","sort","splice","unshift"],function(n){var t=o[n];m.prototype[n]=function(){var r=this._wrapped;return t.apply(r,arguments),"shift"!==n&&"splice"!==n||0!==r.length||delete r[0],P(this,r)}}),m.each(["concat","join","slice"],function(n){var t=o[n];m.prototype[n]=function(){return P(this,t.apply(this._wrapped,arguments))}}),m.prototype.value=function(){return this._wrapped},m.prototype.valueOf=m.prototype.toJSON=m.prototype.value,m.prototype.toString=function(){return""+this._wrapped},"function"==typeof define&&define.amd&&define("underscore",[],function(){return m})}).call(this);
//# sourceMappingURL=underscore-min.map


</script>

</head>
<body onload="initialize()">
<?php 

// $type=$row['type'];
// $opsname=$row['opsname'];
// $descrip=$row['descrip'];
// $istoplayer=$row['istoplayer'];
// $corelayer=$row['corelayer'];
// $inarchive=$row['inarchive'];

// $infotext.=' <br />page is '.$page;

$filename='opsmap.php';
// $adminmenu='1';
include "cojmmenu.php";

?>

<div id="gmap_wrapper" >
<div class="full_map" id="search_map">
<div id="map-canvas" class="onehundred" ></div></div>
<div class="gmap_left" id="scrolltable">
<div class="pad10">
<div class="ui-state-highlight ui-corner-all p15">
<form id="opsmapnewareaform" action="opsmap-new-area.php" method="post" >
<input type="text" title="Area Name, Visible to Client" class=" pad ui-state-default ui-corner-all" name="areaname" 
style="width:210px; font-size:20px; height:29px;" 
value="<?php if (isset($mopsname)) { echo $mopsname; } ?>" placeholder="Area Name" />

<?php



if ($areaid) {
    echo ' <a class="showclient" title="Refresh Area" href="opsmap-new-area.php?areaid='.$areaid.'"> </a>'; 
}


?>
<br />
<textarea id="jobcomments" title="Area Comments Not visible to Client" class="normal ui-state-highlight ui-corner-all " placeholder="Area Comments" name="areacomments" 
style="width: 100%; outline: none; height:20px;"><?php if (isset($mdescrip)) { echo $mdescrip; } ?></textarea>


<?php

if ($mistoplayer<>'1') {
    $sql = "SELECT opsmapid, opsname, descrip FROM opsmap WHERE istoplayer='1' ";
    $stmt = $dbh->query($sql);
    echo '  <select title="Parent Area" name="corelayer" class="ui-state-default ui-corner-left" style="width:215px;"><option value="" > Parent Scheme </option>';

    foreach ($stmt as $arow) {
        echo '<option title="'.$arow['descrip'].'" ';
        if ($corelayer == $arow['opsmapid']) {echo ' selected="selected" ';  } 
        echo 'value="'.$arow['opsmapid'].'" >'.$arow['opsname'].'</option>';
    }

    echo ' </select> ';

    if ($corelayer) {
        echo ' <a class="showclient" title="View Parent Area" href="opsmap-new-area.php?areaid='.$corelayer.'"> </a>'; 
    }
}



if ($corelayer=='0') { // has no parent area so show a checkbox to promote to parent status
    echo ' <br /> <input type="checkbox" name="istoplayer" value="1" title="Promote to Parent Area" ';
    if ($mistoplayer=='1') { echo 'checked';}
    echo ' /> Is Parent Scheme ';
}


// Archeve Checkbox
echo '

<br />
<input type="checkbox" name="inarchive" value="1" '; if ($minarchive=='1') { echo 'checked';} echo ' />  Archived area ? ';
 
 
 if (($page=='') and ($areaid=='')) { // add area or edit area button

    echo '
    <input type="hidden" name="page" value="opsmapnewarea" />
    <input type="hidden" name="existingarea" value="'.$areaid.'" />
    <br />
    <button id="opsmapareasubmit">Add Area </button>';

} else {
	 

    echo '
    <input type="hidden" name="areaid" value="'.$areaid.'" />
    <input type="hidden" name="page" value="editarea" />
    <br />
    <button title="Edit Area" id="opsmapareasubmit">Edit Area</button>';
	 
	 
	 
 }

 ?>
 
<br />
<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>">
<input type="hidden" id="vertices" name="vertices" value="" />
</form>
</div>
 
<hr />
 
<input id="areageocodeaddress" title="Address Search" type="text" style="width: 98%;" placeholder="Map Address Search . . ." 
class="ui-state-default ui-corner-all address pad" />
 
<hr />

<?php if ($moreinfotext) { echo '<div class="ui-state-highlight ui-corner-all p15">'.$moreinfotext.' </div><hr /> '; } ?>



<form action="opsmap-new-area.php" method="post">
<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>">
<button type="submit" title="New Area">Create Blank New Area</button>
</form>
<hr />

<div class="ui-state-highlight ui-corner-all p15">
<p>Left Click on the map to insert a point at end of chain. </p>
<br />
<p>Right Click on the map to insert a point which snaps to nearest existing border.</p>
<br />
<p>Left Click on a point to remove a point. </p>
<br />
<p>Left Drag a point to move it. </p>
<br />
<p>Minimum 3 points needed to define an area.</p>
</div>

 
<br />

</div>
 </div>
 </div>
<?php

include "footer.php";
echo ' </body> </html>';