<?php 

/*
    COJM Courier Online Operations Management
	startuploadgpx.php - GPS Tab, upload .gpx files, start searching gps history, delete rider gps by day 
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


<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height " >
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" type="text/css" href="../css/cojmmap.css">
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >

<script src="//maps.googleapis.com/maps/api/js?v='.$globalprefrow['googlemapver'].'&amp;libraries=geometry&amp;key='.$globalprefrow['googlemapapiv3key'].'" type="text/javascript"></script>
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
<script src="../js/maptemplate.js" type="text/javascript"></script>

';
?>
<script>

        var globlat=<?php echo $globalprefrow['glob1']; ?>;
var globlon=<?php echo $globalprefrow['glob2']; ?>;

</script>
<?php

echo ' <title>COJM GPS Tracking</title></head><body class="c9">';

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
<input type="file" name="file" id="file" accept=".gpx" /> 
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
    
    $infotext.=' startuploadgpx.php ln 215 found uploaded file ';

    
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
                $infotext.=' startuploadgpx.php ln 232 GPX File located ';
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
                    
                    $infotext.=' startuploadgpx.php ln 244 including trackstats.php ';










 
// INPUTS
// $gpxfile = $_REQUEST['GPXfile'];
$templatefile = "template_EN.html";

// CONFIGURATION
$cachepath = "trackstat/cache/";
$classpath = "trackstat/classes/";
$templatepath = "trackstat/";
$gpxpath = "trackstat/gpx/";
define('DEBUG', FALSE);
$numberoftracks=0;
$gmapdata='';
$alreadyondb=0;

$totavglat='';
$totavglon='';
$avgcount='1';
$extensions='';
$map['latitude']='';

$trackoutofrange=0;

$tempdate='';
$newsql = array(); 
$firstrun=1;

$stmt = $dbh->prepare("SELECT trackerid FROM Cyclist WHERE CyclistID=?");
$stmt->execute([$thisCyclistID]);
$device_key = $stmt->fetchColumn();
// echo ' thiscyclistid= '. $thisCyclistID .' dev key is '.$device_key.'. ';

$device_label='GPX Upload';


// INITIALIZATION
require_once($classpath."require.php");
$ExecTimeWatch = new StopWatch();


class ValidUTF8XMLFilter extends php_user_filter {
  protected static $pattern = '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u';
  function filter($in, $out, &$consumed, $closing)
  {
    while ($bucket = stream_bucket_make_writeable($in)) {
      $bucket->data = preg_replace(self::$pattern, '', $bucket->data);
      $consumed += $bucket->datalen;
      stream_bucket_append($out, $bucket);
    }
    return PSFS_PASS_ON;
  }
}



// $TrackStats = new TrackStatsGfx();

$gpxfile=date("U");
//$gpxfile='sample.gpx';

// echo ' gpxpath : '.$gpxpath;
// echo ' gpxfile : '.$gpxfile;

$i=0;

$file_contents = file_get_contents($file);
$file_contents = str_replace("gpx10:speed","gpx10speed",$file_contents);
$file_contents = str_replace("gpx10:course","gpx10course",$file_contents);
file_put_contents($file,$file_contents);

stream_filter_register('xmlutf8', 'ValidUTF8XMLFilter');

$gpx_xml = simplexml_load_file("php://filter/read=xmlutf8/resource=".$file);





if ($gpx_xml === FALSE) { echo ("Cannot open XML document $file (file does not exist or is not valid XML)!"); }



if (DEBUG === TRUE) $tracknumber = '1';
foreach ($gpx_xml->trk as $track) {
    if (DEBUG === TRUE) printf("Track %d, %d segments, name \"%s\"<br>\n", $tracknumber++, count($track->trkseg), $track->name);
    if (DEBUG === TRUE) $tracksegmentnumber = '1';

    foreach ($track->trkseg as $tracksegment) {
        if (DEBUG === TRUE) { printf("--Segment %d, %d trackpoints<br>\n", $tracksegmentnumber++, count($tracksegment->trkpt)); }
        foreach ($tracksegment->trkpt as $trackpoint) {
            $lat = FALSE;
            $lon = FALSE;
            foreach($trackpoint->attributes() as $attr => $value) {
                if ($attr == "lat") $lat = (double) $value;
                if ($attr == "lon") $lon = (double) $value;
            }
            if (count($trackpoint->ele)) { $ele = (int) $trackpoint->ele; } else { $ele = FALSE; }
            if (count($trackpoint->time))        $timestamp = ($trackpoint->time); else $timestamp = FALSE;
            if (count($trackpoint->speed))       $speed= ($trackpoint->speed); else $speed = FALSE;
            if (count($trackpoint->extensions))  {
                $extensions= $trackpoint->extensions;
                foreach ($trackpoint->extensions as $extensionpoint) {
                    if (count($extensionpoint->gpx10speed)) { $speed= $extensionpoint->gpx10speed;  } else { $speed = ''; }
                    if (count($extensionpoint->gpx10course)) { $heading= $extensionpoint->gpx10course;   } else { $heading = ''; }
                }
            } else { $extensions = FALSE; }


            if ($firstrun==0) {
                if ($confirmgpx=='confirm') {
                    $testfile="cache/jstrack/".date('Y/m', $timestamp).'/'.date('Y_m_d', $timestamp).'_'.$device_key.'.js';
                    $infotext.=" trackstats.php 179 file : ". $testfile.' <br />';
                    if (!file_exists($testfile)) {
                        $infotext.= ' <br /> cj 2349 Cache does not exist, no action needed. '.$testfile;
                    } else {
                        $infotext.=  ' <br /> cj 2351 Cache exists, needs deleting. '.$testfile;	
                        unlink($testfile);
                        if (file_exists($testfile)) {
                            $infotext.=  ' not deleted ';
                        }
                    }
                } // ends confirm check
            $firstrun=1;                
            } // ends firstrun




            $timestamp = strtotime($timestamp);
            $map['latitude']=round($map['latitude'],5);

            if ($speed) {
                if ($globalprefrow['distanceunit']=='miles') {
                    $speed=($speed*'0.621');
                }

                $speed=round($speed);
            }

            $lat=round($lat,5);
            $lon=round($lon,5);	
		
            $totavglat=$totavglat+$lat;
            $totavglon=$totavglon+$lon;


            $avglat=($totavglat/$avgcount);
            $avglon=($totavglon/$avgcount);

            if ((($lat<$avglat-'1') or ($lat>$avglat+'1')) or (($lon<$avglon-'1') or ($lon>$avglon+'1'))) {

                $trackoutofrange++;
                $avgcount=$avgcount-'1';
                $totavglat=$totavglat-$lat;
                $totavglon=$totavglon-$lon;
            } else {


                if ($lon>$max_lon) { $max_lon = $lon; }
                if ($lon<$min_lon) { $min_lon = $lon; }
                if ($lat>$max_lat) { $max_lat = $lat; }
                if ($lat<$min_lat) { $min_lat = $lat; }
           

                if ($confirmgpx=='confirm') { // adds to database // changed from confirm
                    try {
                      $newersql = "INSERT INTO instamapper ( device_key,device_label,timestamp,latitude,longitude,altitude,speed,heading,added) VALUES ".
                     " ( :device_key, :device_label, :timestamp , :lat, :lon, :ele , :speed , :heading, :now ) ";
                      $dbh->prepare($newersql)->execute([$device_key, $device_label, $timestamp, $lat, $lon, $ele, $speed, $heading, date("U") ]);
                
                       $numberoftracks++;
                     
                    }  catch (PDOException $e) {
                        if ($e->errorInfo[1] == 1062) {
                            $alreadyondb++;
                        } else {
                            echo  $e->getMessage();
                        }
                    }
                }


                $linecoords=$linecoords.' ['.$lat . "," . $lon.'],';


                if ($tempdate<>date(('H:i D j M '), $timestamp)) {
                    $tempdate=date(('H:i D j M '), $timestamp);
                    $markertext=$tempdate.'<br />'.$speed;
                    if ($globalprefrow['distanceunit']=='miles') { $markertext=$markertext. 'mph '; }
                    if ($globalprefrow['distanceunit']=='km') { $markertext=$markertext. 'km ph '; }
                    $gmapdata=$gmapdata. "['" . $markertext ."',". $lat. "," . $lon . "," . $i ."],"; 
                }

                $i++;

                $latestlat=	$lat;
                $latestlong=$lon;
            }

            $avgcount++;

        }

    }  // ends track segment

} // ends individual track loop


if ($confirmgpx<>'confirm') { echo '<h3> Previewing GPX file, NOT stored to COJM. </h3>'; }

if ($trackoutofrange>'0') { echo '<h3>There were '.$trackoutofrange.' positions out of range. </h3>'; }

if ($alreadyondb>0) { echo '<h2> '.$alreadyondb.' tracks already stored in COJM</h2>';}
if ($numberoftracks>0) { echo '<h2> '.$numberoftracks.' tracks added to COJM.</h2>';}

if ($gmapdata) {
    echo '

<div id="map=parent" style="height:350px; width:100%; position:relative;">

    <div id="map-canvas" style="position: relative; width: 100%; height: 350px;"></div>
    
    </div>
    
    
    
    <script type="text/javascript">
        var locations = ['.$gmapdata.'  ];

        function custominitialize() {
        
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
            strokeOpacity: 0.8
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
            strokeOpacity: 0.8,
            icons: [{
                icon: lineSymbol,
                offset: "0",
                repeat: "50px"
            }],
            map: map
        });
        
        
        }
        
    
    
    function loadmapfromtemplate() {
    
    initialize();
    $(document).ready(function () {
        
        custominitialize();
    });
}


google.maps.event.addDomListener(window, "load", loadmapfromtemplate);
    
    
    
    
    </script>';
}
 



































































                    
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
