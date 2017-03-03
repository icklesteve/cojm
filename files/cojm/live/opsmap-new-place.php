<?php
$alpha_time = microtime(TRUE);
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
$filename="opsmap-new-area.php";

include "C4uconnect.php";
include "changejob.php";

if (isset($_POST['opsmapid'])) { $opsmapid=trim($_POST['opsmapid']);} else {$opsmapid=''; }

?><!DOCTYPE html>
<html lang="en">
<head>
<meta name="HandheldFriendly" content="true" >
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, height=device-height" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<link rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<link rel="stylesheet" href="css/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>
<script src="//maps.googleapis.com/maps/api/js?v=3.22&key=<?php echo $globalprefrow['googlemapapiv3key']; ?>" type="text/javascript"></script>
<title>OpsMap Place</title>

<?php

$query = "SELECT * FROM opsmap WHERE opsmapid=".$opsmapid ; 
$sql_result = mysql_query ($query, $conn_id);  
$sumtot=mysql_affected_rows();
if ($sumtot>'0') {
    while ($row = mysql_fetch_array($sql_result)) {
        extract($row);
        $type=$row['type'];
        $opsname=$row['opsname'];
        $opsmapid=$row['opsmapid'];
        $descrip=$row['descrip'];
        $istoplayer=$row['istoplayer'];
        $corelayer=$row['corelayer'];
        $inarchive=$row['inarchive'];
        $initiallat=$row['lat'];
        $initiallon=$row['lng'];
    }
}

if (!$initiallat) {  $initiallat=$globalprefrow['glob1']; }
if (!$initiallon) {  $initiallon=$globalprefrow['glob2']; }
?>
<script type="text/javascript"> 
function initialize() {

    var EditForm =
	'<form action="ajax-save.php" method="POST" name="EditMarker" id="EditMarker" class="EditMarker">'+
	'<div class="marker-edit">'+
    '<div class="fs"><div class="fsli"> Name </div>'+
	'<input type="text" name="pName" class="save-name ui-state-default ui-corner-all w170" '+
    'placeholder="Enter Title" maxlength="50" value="<?php echo $opsname; ?>" /></div>'+
    '<div class="fs"><div class="fsli"> Info</div>'+
	'<textarea name="pDesc" class="save-desc w170 normal ui-state-highlight ui-corner-all " placeholder="Comments" maxlength="300">'+
	'<?php echo $descrip; ?></textarea></div>'+
    '<div class="fs"><div class="fsli">Type </div>'+
	'<select name="pType" class="save-type ui-state-highlight ui-corner-left">'+
	'<option <?php if ($markertype=='1') { echo ' selected="selected" '; } ?> value="1">General</option>'+
	'<option <?php if ($markertype=='2') { echo ' selected="selected" '; } ?> value="2">Access </option>'+
    '<option <?php if ($markertype=='3') { echo ' selected="selected" '; } ?> value="3">Safety</option>'+
	'</select></div> '+
    '<div class="fs"><div class="fsli">Archived </div> '+
	'<input type="checkbox" name="inarchive" value="1"  <?php if ($inarchive=='1') { echo " checked "; } ?> /> </div> '+
    '<input name="opsmapid" type="hidden" value="<?php echo $opsmapid; ?>"> ' + 
    '<input name="action" type="hidden" value="editmarker"> ' + 			
	'<input name="latlang" type="hidden" id="latlang" value="<?php echo $initiallat.','.$initiallon; ?>" >'+
    '</div> <div class="fs"><div class="fsli"> &nbsp; </div>'+
	'<button name="edit-marker" id="edit-marker" class="edit-marker">Edit Details</button> </div> '+
    '<div class="fs"> Click and drag the marker to relocate.</div> '+
	'</form>';
    
    var element = document.getElementById("map-canvas");
    var mapTypeIds = [];
    var mapTypeIds = ["OSM", "roadmap", "satellite", "OCM"];
    var map = new google.maps.Map(element, {
        center: new google.maps.LatLng(<?php echo $initiallat.','.$initiallon; ?>),
        zoom: 11,
        mapTypeId: "OSM",
        draggableCursor: "crosshair",
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
        maxZoom: 20
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
    
    var osmcopyr="<span class='osmcopyr'> &copy; <a href='https://www.openstreetmap.org/copyright' " +
    "target='_blank'>OpenStreetMap</a> contributors</span>";
    
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
    $(document).ready(function() {setTimeout(function() {
        $("div#outerdiv").html(osmcopyr);
    },3000);});
    
    var point = new google.maps.LatLng(<?php echo $initiallat.','.$initiallon; ?>);

    var marker = new google.maps.Marker({
        position: point,
        map: map,
        draggable:true,
        title: '".$opsname."'
    });

    var infowindow = new google.maps.InfoWindow({
		position:point,
    });
   
    infowindow.setContent(EditForm);
    infowindow.open(map,marker);  


    //add click listner to open infowindow     
    google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker); // click on marker opens info window 
    });


    google.maps.event.addListener(marker, 'dragend', function() {
        document.getElementById('latlang').value = marker.getPosition().toUrlValue();
    });

    google.maps.event.addListener(infowindow, 'domready', function() {
        $(function(){
            $('.normal').autosize();
        });
        
        $('#edit-marker').on('click', function (event) {
            event.preventDefault();
            var formdata=$('#EditMarker').serializeArray();
            
            $.ajax({
                type: 'POST',
                url: 'ajaxopsmap_process.php',
                data: formdata,
                success:function(data){
                    alert(data);
                },
                error:function (xhr, ajaxOptions, thrownError){
                    alert(thrownError); //throw any errors
                }
            });
        });
    }); // ends infowindow listener
    
    $(window).resize(function () {
        var h = $(window).height();
        offsetTop = 72; // Calculate the top offset
        $('#gmap_wrapper').css('height', (h - offsetTop));
    }).resize();

}

google.maps.event.addDomListener(window, 'load', initialize); 
</script>
</head>
<body>
<?php

$adminmenu='1';
include "cojmmenu.php";

echo '
<div id="gmap_wrapper" >
<div class="full_map" style="padding-left:0px;" id="search_map">
<div id="map-canvas" class="onehundred" ></div>
</div>
</div>'; 

include "footer.php";
echo ' </body> </html>';