<?php 

$alpha_time = microtime(TRUE);
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include "changejob.php";

$title = "COJM";

?><!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title); ?> Add PC </title>
    <script src="https://maps.google.co.uk/maps?file=api&amp;v=2&amp;key=AIzaSyAQR0YkeZdeyV_u2tnRHD-v28PR4upoVaI" type="text/javascript"></script>
    <script type="text/javascript">
 function load() {
      if (GBrowserIsCompatible()) {
        var map2 = new GMap2(document.getElementById("map2"));
        map2.addControl(new GSmallMapControl());
        map2.addControl(new GMapTypeControl());
        var center = new GLatLng(<?php echo $globalprefrow['glob1'].','.$globalprefrow['glob2']; ?>);
        map2.setCenter(center, 15);
        geocoder = new GClientGeocoder();
        var marker = new GMarker(center, {draggable: true});  
        map2.addOverlay(marker);
        document.getElementById("lat").innerHTML = center.lat().toFixed(5);
        document.getElementById("lng").innerHTML = center.lng().toFixed(5);

	  GEvent.addListener(marker, "dragend", function() {
       var point = marker.getPoint();
	      map2.panTo(point);
       document.getElementById("lat").innerHTML = point.lat().toFixed(5);
       document.getElementById("lng").innerHTML = point.lng().toFixed(5);

        });


	 GEvent.addListener(map2, "moveend", function() {
		  map2.clearOverlays();
    var center = map2.getCenter();
		  var marker = new GMarker(center, {draggable: true});
		  map2.addOverlay(marker);
		  document.getElementById("lat").innerHTML = center.lat().toFixed(5);
	   document.getElementById("lng").innerHTML = center.lng().toFixed(5);


	 GEvent.addListener(marker, "dragend", function() {
      var point =marker.getPoint();
	     map2.panTo(point);
      document.getElementById("lat").innerHTML = point.lat().toFixed(5);
	     document.getElementById("lng").innerHTML = point.lng().toFixed(5);

        });
 
        });

      }
    }

	   function showAddress(address) {
	   var map2 = new GMap2(document.getElementById("map2"));
       map2.addControl(new GSmallMapControl());
       map2.addControl(new GMapTypeControl());
       if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + " Postcode not found.");
            } else {
		  document.getElementById("lat").innerHTML = point.lat().toFixed(5);
	   document.getElementById("lng").innerHTML = point.lng().toFixed(5);
		 map2.clearOverlays()
			map2.setCenter(point, 14);
   var marker = new GMarker(point, {draggable: true});  
		 map2.addOverlay(marker);

		GEvent.addListener(marker, "dragend", function() {
      var pt = marker.getPoint();
	     map2.panTo(pt);
      document.getElementById("lat").innerHTML = pt.lat().toFixed(5);
	     document.getElementById("lng").innerHTML = pt.lng().toFixed(5);
        });


	 GEvent.addListener(map, "moveend", function() {
		  map2.clearOverlays();
    var center = map.getCenter();
		  var marker = new GMarker(center, {draggable: true});
		  map2.addOverlay(marker);
		  document.getElementById("lat").innerHTML = center.lat().toFixed(5);
	   document.getElementById("lng").innerHTML = center.lng().toFixed(5);

	 GEvent.addListener(marker, "dragend", function() {
     var pt = marker.getPoint();
	    map2.panTo(pt);
    document.getElementById("lat").innerHTML = pt.lat().toFixed(5);
	   document.getElementById("lng").innerHTML = pt.lng().toFixed(5);
        });
 
        });

            }
          }
        );
      }
    }
    </script>
<script type="text/javascript">
function trim (str)
{
     return str.replace (/^\s+|\s+$/g, '');
}
function changeText3(){
var name_element = document.getElementById('newpc');
var postcode = name_element.value;
	if (trim(postcode) == '')
{
   alert ('Please enter Postcode');
} else {
	var oldHTML = document.getElementById('lat').innerHTML;
	var oldlHTML = document.getElementById('lng').innerHTML;
	var newHTML = "<input type='hidden' name='lat' value='" + oldHTML + "' />"
	var newHTML = newHTML + "<input type='hidden' name='lng' value='" + oldlHTML + "' />"
	var newHTML = newHTML + "<input type='hidden' name='id' value='<?php echo $id; ?>' />"
	var newHTML = newHTML + "<input type='hidden' name='newpc' value='" + postcode + "' />"
	var newHTML = newHTML + "<button type='submit'>Add Postcode to Database</button>";
	document.getElementById('para').innerHTML = newHTML;
}
}
</script>
</head>
<body onload="load()" onunload="GUnload()" >
<?php 
 $adminmenu=0; $settingsmenu=1;
$filename='newpc.php';
 include "cojmmenu.php"; 
 
 if (isset($_GET['selectpc'])) {  $selectpc=trim($_GET['selectpc']); } else { $selectpc=''; }
 
?><div class="Post">
<div class="ui-state-highlight ui-corner-all p15" >


<strong>Enter Postcode, then move marker if needed.</strong>
<form action="#" onsubmit="showAddress(this.address.value); return false">
       Postcode to add :       
      <input class="ui-state-default ui-corner-all" style="text-transform: uppercase;" id="newpc" type="text" 
	  size="10" name="address" value=" <?php echo $selectpc; ?>" />
      <button type="submit"  > Search </button>
      </form>
	
<form action="<?php if ($ID) { echo 'order.php'; } else echo '#'; ?>" method="post" >
<input type="hidden" name="page" value="newpostcode" />
<div style="position:relative; float:left;">
Town : <input class="ui-state-default ui-corner-all" type='text' name='town' value=' <?php echo $globalprefrow['glob3']; ?> ' />
Region : <input class="ui-state-default ui-corner-all" type='text' name='area' value=' <?php echo $globalprefrow['glob4']; ?> ' />
<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>"></div>
<div id="para" > <button onclick='changeText3()' >Confirm Position on Map</button></div> 
</form>

</div><br />
<div class='line'></div>

<div id="map2" class="ordermap"><br/></div>
<a href="getlatlon.php"> Get Latitude / Longitude from a Single Postcode </a>
<div style="opacity:0" id="lat"></div>
<div style="opacity:0" id="lng"></div>
</div>

<?php 
include 'footer.php';
mysql_close(); 

echo '</body></html>';