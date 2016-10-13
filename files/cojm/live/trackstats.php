<?php

// needs original trackstats license adding



/*
    COJM Courier Online Operations Management
	trackstats.php - Create a PDF invoice
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


/**********************************************************
 * Creates statistics from given GPX file
 **********************************************************/


 
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

$gpx_xml = simplexml_load_file("php://filter/read=xmlutf8/resource=$file");

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
    echo ' <div id="ordermap" style="position: relative; width: 100%; height: 350px;"></div>
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
    </script>';
}
 



?>