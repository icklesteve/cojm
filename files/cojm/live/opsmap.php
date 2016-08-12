<?php 

/*
    COJM Courier Online Operations Management
	opsmap.php - Displays POI & Ops Areas
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
$title = "COJM";
include 'changejob.php';

if (isset($_GET['searchtype'])) { $searchtype=trim($_GET['searchtype']);} else { $searchtype=''; }

$markersfound='0';
$max_lat = '-99999';
$min_lat =  '99999';
$max_lon = '-99999';
$min_lon =  '99999';
$testc='';
$js='';

$hasforms='1';
?><!doctype html>
<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php print ' Ops Map : '.($title); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>';
 ?>

<style> div.info { font-weight:bold; }

div.marker-info-win { max-width:250px; }
h1.marker-heading { padding:0; }

div.fsli  { width:50px;   float: left;
    min-height: 1px;
    padding-right: 8px;
    position: relative;
    text-align: right;
    top: 2px; }
.w170 {  width:170px;  }


img.opsmapicon { 

height:30px;
vertical-align:bottom;
width:30px;

}

table.nolines td { 
font-size:18px;
padding:1px;

}

</style>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php
echo '<script src="//maps.googleapis.com/maps/api/js?v=3.22&amp;libraries=geometry&amp;key='.$globalprefrow['googlemapapiv3key'].'" type="text/javascript"></script>'; 


if ($searchtype=='') { $query = "SELECT * FROM opsmap WHERE inarchive<>1 AND corelayer='0' "; }
if ($searchtype=='archive') { $query = "SELECT * FROM opsmap WHERE corelayer='0' "; }

  $showallrow='';
  $tablerow='';
  $clickrow='';
  

$sql_result = mysql_query ($query, $conn_id) or mysql_error();  

$sumtot=mysql_affected_rows();

// echo $sumtot .' Rows found in opsmap ';

while ($row = mysql_fetch_array($sql_result)) { extract($row);


if ($row['type']=='1') {


$markersfound++;


$lat=$row['lat'];
$lng=$row['lng'];


if ($lat>$max_lat) { $max_lat=$lat; }
if ($lat<$min_lat) { $min_lat=$lat; }
if ($lng>$max_lon) { $max_lon=$lng; }
if ($lng<$min_lon) { $min_lon=$lng; }



} 

elseif ($row['type']=='2') {


$tablerow.= '
<tr id="'.$row['opsmapid'].'" >
<td><a href="opsmap-new-area.php?page=showarea&amp;areaid='.$row['opsmapid'].'">'.$row['opsname'].'</a></td>';

$tablerow.= '<td>';
if ($row['istoplayer']=='1') { $tablerow.= ' <span class="album" title="Has Layers"> </span> '; }
// $tablerow.= '</td><td> ';

$tablerow.= $row['descrip'].'</td>';



$tablerow.= '</tr>';


// echo '<br />'.$row['code'].' '.$row['descrip'].' '.$row['g'];

$areaid=$row['opsmapid'];
$result = mysql_query("SELECT AsText(g) AS POLY FROM opsmap WHERE opsmapid=".$areaid);
if (mysql_num_rows($result)) {
    $score = mysql_fetch_assoc($result);
	$p=$score['POLY'];
$trans = array("POLYGON" => "", "((" => "", "))" => "");
$p= strtr($p, $trans);
$pexploded=explode( ',', $p );
$js=$js.' 

 var polymarkers'.$areaid.' = '; 
 
 $jsloop = ' [ ';

foreach ($pexploded as $v) {
$transf = array(" " => ",");
$v= strtr($v, $transf);
	$jsloop.='   
	new google.maps.LatLng('.$v.'),';
	$vexploded=explode( ',', $v );
	$tmpi='1';
	foreach ($vexploded as $testcoord) {	
	
	if ($testcoord) {
	
if ($tmpi % 2 == 0) {
  if($testcoord>$max_lon) { $max_lon = $testcoord; }
  if($testcoord<$min_lon)  { $min_lon = $testcoord; }
} else { 
  if($testcoord>$max_lat) { $max_lat = $testcoord; }
  if($testcoord<$min_lat)  { $testc.='<br/>151 '.$testcoord; $min_lat = $testcoord;  }
} $tmpi++;
	} // ends test coord valid check
}
} // ends each in array
$jsloop= rtrim($jsloop, ','). ' ] ';

  
$js.=$jsloop.' ; ';  
  
//  strokeColor: "#FF0000",

$js.=' 
var poly'.$areaid.' = new google.maps.Polygon({
    paths: polymarkers'.$areaid.',
    strokeWeight: 2,
	strokeOpacity: 0.8,
     fillColor: "#5555FF",
	 fillOpacity: 0.25,
	 strokeColor: "#000000",
	 map:map,
	 clickable: false
  });
  
  totalareas = totalareas + 1;
  
';

$clickrow.=' 
console.log(google.maps.geometry.poly.containsLocation(event.latLng, poly'.$areaid.'));
 if(google.maps.geometry.poly.containsLocation(event.latLng, poly'.$areaid.') == true) {
  areasfound=areasfound+1;
  
  
// $( "#jssearch" ).append( "<p> Found individ '.$areaid.'</p>" );

$( "#'.$areaid.'" ).addClass( " myClass " );
$( "#'.$areaid.'" ).removeClass( " hidden " );
	}

else { 
$( "#'.$areaid.'" ).removeClass( " myClass " );
$( "#'.$areaid.'" ).addClass( " hidden " );
}
	';
	
	
$showallrow.=' 
$( "#'.$areaid.'" ).removeClass( " hidden " ); ';

}



} // ends type=2


} // ends db row loop

echo '
<script>

function initialize() {
	
var geocoder = null;
var totalareas=0;
markertype1=0;
markertype2=0;
markertype3=0;

var element = document.getElementById("map-canvas");
		
 var mapTypeIds = [];
            var mapTypeIds = ["OSM", "roadmap", "satellite", "OCM"]
			
		 var map = new google.maps.Map(element, {
                center: new google.maps.LatLng('. $globalprefrow['glob1'].','.$globalprefrow['glob2'].'),
                zoom: 11,
				disableDoubleClickZoom: true,
                mapTypeId: "OSM",
				scaleControl: true,
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
                maxZoom: 19
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

';



        //Load Markers from the XML File, Check (opsmap_process.php)			
if ($searchtype=='archive') { 
echo ' $.get("opsmap_process.php?archive=1", function (data) { '; } else {
echo ' $.get("opsmap_process.php?archive=0", function (data) { '; 
	}
			
			
?>			
            $(data).find("marker").each(function () {
                 //Get user input values for the marker from the form
                  var name      = $(this).attr('name');
                  var address   = $(this).attr('address');
                  var markertype      = $(this).attr('markertype');
                  var point     = new google.maps.LatLng(parseFloat($(this).attr('lat')),parseFloat($(this).attr('lng')));
				  var opsmapid  = $(this).attr('opsmapid');
			  
                  //call create_marker() function for xml loaded maker
                  //create_marker(opsmapid, point, name, address, false, false, false, "<?php echo $globalprefrow['clweb3']; ?>");
				  
// var iconPath= 	"<?php echo $globalprefrow['clweb3']; ?>";			  
//		alert(typeof markertype);			  





				  
if (markertype==1) {
markertype1++;				  				  
var iconPath= {
	url: "../images/info-50-50-trans.gif",
 scaledSize: new google.maps.Size(30, 30), // scaled size
    origin: new google.maps.Point(0,0),
   anchor: new google.maps.Point(15, 15)
}; }

else if (markertype==2) {
markertype2++;		
	var iconPath= {
	url: "../images/access-50-50-trans.gif",
 scaledSize: new google.maps.Size(30, 30), // scaled size
    origin: new google.maps.Point(0,0),
   anchor: new google.maps.Point(15, 15)
}; }
	
else if (markertype==3) {
markertype3++;		
	var iconPath= {
	url: "../images/alert-50-50-trans.gif",
 scaledSize: new google.maps.Size(30, 30), // scaled size
    origin: new google.maps.Point(0,0),
   anchor: new google.maps.Point(15, 15)
}; }	
	
	

$(document).ready(function() {
$("span#markertype1span").html(markertype1);	
$("span#markertype2span").html(markertype2);	
$("span#markertype3span").html(markertype3);	
});
	
	
	
	
	

				  
    //new marker
    var marker = new google.maps.Marker({
        position: point,
        map: map,
        draggable:false,
        title: name ,
        icon: iconPath
    });
    
    //Content structure of info Window for the Markers
    var contentString = $('<div class="marker-info-win">'+
    '<h1 class="marker-heading">'+name+'</h1>'+
    '<p>' + address + ' </p> <br />'+
	'<form action="opsmap-new-place.php?" method="post" >' +
	'<button type="submit" name="remove-marker" class="remove-marker" title="Edit Place">Edit Marker</button>'+
    '<input name="opsmapid" type="hidden" value=\"' + opsmapid + '\"> ' + 
	'</form>' + 
    '</div>');    

    //Create an infoWindow
    var infowindow = new google.maps.InfoWindow();
    //set the content of infoWindow
    infowindow.setContent(contentString[0]);

    //Find remove button in infoWindow
    var removeBtn   = contentString.find('button.remove-marker')[0];

   //Find save button in infoWindow
    var saveBtn     = contentString.find('button.save-marker')[0];
        
    //add click listner to open infowindow     
    google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker); // click on marker opens info window 
    });
      				  
            });
        }); 
        
        //drop a new marker on right click
        google.maps.event.addListener(map, 'rightclick', function(event) {
            //Edit form to be displayed with new marker
            var EditForm = '<form action="opsmap_process.php" method="POST" name="SaveMarker" id="SaveMarker">'+
			'<div class="marker-edit">'+
            '<div class="fs"><div class="fsli"> Name </div>'+
			'<input type="text" name="pName" class="save-name ui-state-default ui-corner-all w170" placeholder="Enter Title" maxlength="40" />'+
			'</div>'+
          '<div class="fs"><div class="fsli"> Info</div>'+
			'<textarea name="pDesc" class="save-desc w170 normal ui-state-highlight ui-corner-all" placeholder="Comments" maxlength="150"></textarea>'+
			'</div>'+
            '<div class="fs"><div class="fsli">Type </div>'+
			'<select name="pType" class="save-type ui-state-highlight ui-corner-left">'+
			'<option value="1">General</option>'+
			'<option value="2">Access </option>'+
            '<option value="3">Safety</option></select>'+
            '<button name="save-marker" class="save-marker">Save Marker Details</button>'+
			'</div></form>';
			
			
			$(function(){ $('.normal').autosize();	});

            //call create_marker() function
          create_marker( 0, event.latLng, 'New Marker', EditForm, true, true, true, "<?php echo $globalprefrow['clweb3']; ?>");
			
        });                             
    <?php

echo $js;
  
echo '
  
  // bounds for polygons hardcoded via php
  var  bounds = new google.maps.LatLngBounds();
 
    bounds.extend(new google.maps.LatLng('.$max_lat.', '.$min_lon.')); // upper left
    bounds.extend(new google.maps.LatLng('.$max_lat.', '.$max_lon.')); // upper right
    bounds.extend(new google.maps.LatLng('.$min_lat.', '.$max_lon.')); // lower right
    bounds.extend(new google.maps.LatLng('.$min_lat.', '.$min_lon.')); // lower left
	 '; 
  
  
  
  echo "
google.maps.event.addListener(map, 'click', function(event) {
var areasfound	   = 0;
$( '#showAll' ).removeClass( ' hidden ' );
$( '#jssearch' ).empty();	  
$( '#jssearch' ).append (  ' ');
".$clickrow. "
$( '#jssearch' ).append (' <p> ');
$( '#jssearch' ).append (areasfound);
$( '#jssearch' ).append (' areas found out of ');
$( '#jssearch' ).append (totalareas);


$( '#jssearch' ).append ('.</p> ');

if (areasfound == 0 ) {


$( '#opstable' ).addClass( ' hidden ' );
	}
else { 

$( '#opstable' ).removeClass( ' hidden ' );
}
  });
 

 
// function hide_zone1_kml(){
//            geoXml.docs[0].gpolygons[0].setMap(null);  
 //   }
  

$('#showAll').click(function() {
	
".$showallrow."
	
	
$( '#opstable' ).removeClass( ' hidden ' );	
	$( '#showAll' ).addClass( ' hidden ' );
$( '#jssearch' ).empty();	
});
";
?>

//############### Create Marker Function ##############
function create_marker(opsmapid, MapPos, MapTitle, MapDesc,  InfoOpenDefault, DragAble, Removable, iconPath)
{
    //new marker
    var marker = new google.maps.Marker({
        position: MapPos,
        map: map,
        draggable:DragAble,
        title: MapTitle ,
        icon: iconPath
    });
    
    //Content structure of info Window for the Markers
    var contentString = $('<div class="marker-info-win">'+
    '<div class="marker-inner-win"><span id=\" + opsmapid + \" class="info-content">'+
    '<h1 class="marker-heading">'+MapTitle+'</h1>'+
    MapDesc+ 
    '</span>' + 
    '</div></div>');    

    //Create an infoWindow
    var infowindow = new google.maps.InfoWindow();
    //set the content of infoWindow
    infowindow.setContent(contentString[0]);

    //Find remove button in infoWindow
    var removeBtn   = contentString.find('button.remove-marker')[0];

   //Find save button in infoWindow
    var saveBtn     = contentString.find('button.save-marker')[0];

			$(function(){ $('.normal').autosize();	});
    
    if(typeof saveBtn !== 'undefined') //continue only when save button is present
    {
 	
        //add click listner to save marker button
        google.maps.event.addDomListener(saveBtn, "click", function(event) {
			event.preventDefault();
            var mReplace = contentString.find('span.info-content'); //html to be replaced after success
            var mName = contentString.find('input.save-name')[0].value; //name input field value
            var mDesc  = contentString.find('textarea.save-desc')[0].value; //description input field value
            var mType = contentString.find('select.save-type')[0].value; //type of marker
            
            if(mName =='' || mDesc =='')
            {
                alert("Please enter Name and Description!");
            }else{
                //call save_marker function and save the marker details
                save_marker(marker, mName, mDesc, mType, mReplace);
            }
        });
    }
    
    //add click listner to save marker button        
    google.maps.event.addListener(marker, 'mouseover', function() {
            infowindow.open(map,marker); // click on marker opens info window 
    });
      
    if(InfoOpenDefault) //whether info window should be open by default
    {
      infowindow.open(map,marker);
	  
google.maps.event.addListener(infowindow, 'domready', function() {

$(function(){ $('.normal').autosize();	});	  
	  
});
	  
    }
}


//############### Save Marker Function ##############
function save_marker(Marker, mName, mAddress, mType, replaceWin)
{
	
var savemarker="savemarker";	
    //Save new marker using jQuery Ajax
    var mLatLang = Marker.getPosition().toUrlValue(); //get marker position
    var myData = {name : mName, address : mAddress, latlang : mLatLang, type : mType , action: savemarker}; //post variables
    console.log(replaceWin);        
    $.ajax({
      type: "POST",
      url: "opsmap_process.php",
      data: myData,
      success:function(data){
            replaceWin.html(data); //replace info window with new html
            Marker.setDraggable(false); //set marker to fixed
            Marker.setIcon('<?php echo $globalprefrow['clweb3']; ?>'); //replace icon
        },
        error:function (xhr, ajaxOptions, thrownError){
            alert(thrownError); //throw any errors
        }
    });
}

$(window).resize(function () {
    var h = $(window).height(),
        offsetTop = 72; // Calculate the top offset

    $('#gmap_wrapper').css('height', (h - offsetTop));
}).resize();


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
 
 $('#searchtype').change(function() { $('#searchopsmap').submit(); }); 
 
 map.fitBounds(bounds);

<?php
echo "

}

 function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}


google.maps.event.addDomListener(window, 'load', initialize);
    </script>

</head><body>  ";
  $adminmenu="1";
$filename="opsmap.php";
include "cojmmenu.php";


echo '
<div id="gmap_wrapper" >
<div class="full_map" id="search_map">
<div id="map-canvas" class="onehundred" ></div></div>
<div class="gmap_left" id="scrolltable">
<div class="pad10">
<form id="searchopsmap" action="opsmap.php">';

// echo $searchtype;

echo '
<select id="searchtype" name="searchtype" class="ui-state-highlight ui-corner-left">
<option ';
if ($searchtype=='') {  echo ' selected="selected" '; }
echo 'value="">Active</option>';
echo '<option ';
if ($searchtype=='archive') {  echo ' selected="selected" '; }
echo ' value="archive">Active + Archived</option>';

echo '
</select>
</form>
';

echo '
<form action="#" onsubmit="showAddress(this.address.value); return false" style=" background:none;">
<input title="Address Search" type="text" style="width: 274px; padding-left:6px;" name="address" placeholder="Map Address Search . . ." 
class="ui-state-default ui-corner-all address" />
</form>	
';



echo '

<br />
<hr />

<div id="jssearch"></div>

<button class=" hidden " id="showAll">Show all Areas</button>

<table id="opstable" class="ord"><tbody><tr>
<th>Name </th>
<th> </th>
</tr>
'.$tablerow;


echo '
</tbody></table>
<br />
<table class="nolines">
<tbody>
<tr><td><img alt="Marker Type 1" class="opsmapicon" src="../images/info-50-50-trans.gif" /> </td><td> <span id="markertype1span"> </span> </td><td>General</td></tr>
<tr><td><img alt="Marker Type 2" class="opsmapicon" src="../images/access-50-50-trans.gif" /> </td><td>  <span id="markertype2span"> </span> </td><td> Access </td></tr>
<tr><td><img alt="Marker Type 3" class="opsmapicon" src="../images/alert-50-50-trans.gif" /> </td><td>  <span id="markertype3span"> </span>  </td><td> Safety </td></tr>

</tbody>
</table>

<p>'.$markersfound.' Locations, SQL Lookup in archive.</p>

<p> Left click on map to search areas. </p>
<p> Left click on marker to view / edit. </p>
<p> Right click on map to add a marker. </p>
';

// echo $testc;

echo '
<br />

 <form action="opsmap-new-area.php">
<button type="submit" title="New Area">Create Blank New Area</button>
</form>
</div>		
		</div>
		
</div>		
';



include "footer.php";

echo '</body></html>';
mysql_close();