<?php
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
$numberoftracks='';
$gmapdata='';
$numberofondbtracks='';
$alreadyondb='';
$addedtodb='';

$totavglat='';
$totavglon='';
$avgcount='1';
$extensions='';
$testvar='';
$map['latitude']='';

$trackoutofrange='0';

$tempdate='';
$newsql = array(); 


// INITIALIZATION
require_once($classpath."require.php");
$ExecTimeWatch = new StopWatch();

// $TrackStats = new TrackStatsGfx();

$gpxfile=date("U");
//$gpxfile='sample.gpx';

// echo ' gpxpath : '.$gpxpath;
// echo ' gpxfile : '.$gpxfile;

$i='1';


// $file = str_replace("gpx10:speed", "gpx10speed", $file);


// echo $file;

// PARSE GPX FILE AND STORE DATA
// $gpx_xml = @simplexml_load_file($file);




// $path_to_file = 'path/to/the/file';
$file_contents = file_get_contents($file);
$file_contents = str_replace("gpx10:speed","gpx10speed",$file_contents);
$file_contents = str_replace("gpx10:course","gpx10course",$file_contents);
file_put_contents($file,$file_contents);



// file_put_contents($file,$file_contents);


$cachelocation='cache/gpxuploads/'.date("Y").'-'.date("m").'-'.date("d").'-'.date("His").'-'.$thisCyclistID.'.gpx';

file_put_contents($cachelocation,$file_contents);





stream_filter_register('xmlutf8', 'ValidUTF8XMLFilter');

// file_put_contents('test.xml', '<a>foo'.chr(0).'</a>');
// $doc = simplexml_load_file('test.xml'); => Char 0x0 out of allowed range

$gpx_xml = simplexml_load_file("php://filter/read=xmlutf8/resource=$file");


// echo $gpx_xml->asXML();

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



if ($gpx_xml === FALSE) echo ("Cannot open XML document $file (file does not exist or is not valid XML)!");



if (DEBUG === TRUE) $tracknumber = '1';
foreach ($gpx_xml->trk as $track) {
  if (DEBUG === TRUE) printf("Track %d, %d segments, name \"%s\"<br>\n", $tracknumber++, count($track->trkseg), $track->name);
  if (DEBUG === TRUE) $tracksegmentnumber = '1';
  
 // if (count($track->name)) $TrackStats->SetTrackName($track->name);
  
  foreach ($track->trkseg as $tracksegment) {
    if (DEBUG === TRUE) printf("--Segment %d, %d trackpoints<br>\n", $tracksegmentnumber++, count($tracksegment->trkpt));
    foreach ($tracksegment->trkpt as $trackpoint) {
       $lat = FALSE;
       $lon = FALSE;
       foreach($trackpoint->attributes() as $attr => $value) {
         if ($attr == "lat") $lat = (double) $value;
         if ($attr == "lon") $lon = (double) $value;
       }
       if (count($trackpoint->ele))         $ele = (int) $trackpoint->ele; else $ele = FALSE;
       if (count($trackpoint->time))        $timestamp = ($trackpoint->time); else $timestamp = FALSE;
	   if (count($trackpoint->speed))       $speed= ($trackpoint->speed); else $speed = FALSE;
	   if (count($trackpoint->extensions))  { $extensions= $trackpoint->extensions; // echo ' extension '; 
	   
	
    foreach ($trackpoint->extensions as $extensionpoint) {
	if (count($extensionpoint->gpx10speed)) { $speed= $extensionpoint->gpx10speed;  } else { $speed = ''; }
	if (count($extensionpoint->gpx10course)) { $heading= $extensionpoint->gpx10course;   } else { $heading = ''; }
	} // extension loop

 
 } else  { $extensions = FALSE; }


// $timestamp = str_replace ("T", " ", strtoupper($timestamp));
// $timestamp = str_replace ("Z", " ", strtoupper($timestamp));
// echo	($timestamp).' ';

$timestamp = strtotime($timestamp);
   $map['latitude']=round($map['latitude'],5);

if ($speed) {
 if ($globalprefrow['distanceunit']=='miles') {
$speed=($speed*'0.621');
}

$speed=round($speed);
}

	   
	
//	echo '<br />FOR COJM purposes, lat is '.$lat.', lon is '.$lon.', altitude is '.$ele.' timestamp is '.date("U", $timestamp);

//	   	echo $timestamp.'<br />';	
		
		$lat=round($lat,5);
$lon=round($lon,5);
		
		
		$device_key=$_POST['newcyclist'];
		$device_label='GPX Upload';
		$latitude=$lat;
		$longitude=$lon;
		$altitude=$ele;
//		$speed='';
//		$heading='';
		
		
		
$totavglat=$totavglat+$lat;
$totavglon=$totavglon+$lon;


$avglat=($totavglat/$avgcount);
$avglon=($totavglon/$avgcount);
		
// echo '<br />'.$avglat.' '.$avglon;		


if ((($lat<$avglat-'1') or ($lat>$avglat+'1')) or (($lon<$avglon-'1') or ($lon>$avglon+'1'))) {

$trackoutofrange=$trackoutofrange+'1';
// echo '<br /> LAT '.$lat.' OUT OF RANGE LON '.$lon.' OUT OF RANGE '; 
// exit ("on 183");
$avgcount=$avgcount-'1';
$totavglat=$totavglat-$lat;
$totavglon=$totavglon-$lon;
} else {


  if($lon>$max_lon) { $max_lon = $lon; }
  if($lon<$min_lon) { $min_lon = $lon; }
  
  if($lat>$max_lat)  { $max_lat = $lat; }
  if($lat<$min_lat)  { $min_lat = $lat; }


// $TrackStats->AddPoint(array("Latitude" => $lat, "Longitude" => $lon, "Elevation" => $ele, "Timestamp" => $timestamp));
		

$checkexists ="SELECT COUNT(*) FROM instamapper WHERE timestamp = '". $timestamp."' AND device_key = '". $device_key. "' LIMIT 0,1; ";

 
// echo '<br />'.$checkexists;

 $checktrack = mysql_query($checkexists) or die(mysql_error());

// echo '<br /> 270 ';
 
//  die();
 
 
 
 
 $testrow=mysql_fetch_array($checktrack) or die(mysql_error());
 
 
//  echo '<br /> 287 ';
 
 
 
 $num_rowsb = $testrow[0];
// echo "Total rows: " . $num_rowsb;
 
 
 $next='0';



// $num_rows=mysql_num_rows($checktrack);
 
// $num_rowsb='1';
 
  if ($num_rowsb<'1') {
  


if ($confirmgpx=='confirm') { // adds to database
  
// dev key
// timestamp
// latitude
// longitude 
// speed


   $newsql = 'INSERT INTO instamapper (device_key,device_label,timestamp,latitude,longitude,altitude,speed,heading,added) VALUES 
   '. '("'.$device_key.'", "'.$device_label.'", "'.$timestamp.'", "'.$latitude.'", "'.$longitude.'", "'.$altitude.
	'", "'.$speed.'", "'.$heading.'", '.time().' ) ';

	$numberoftracks++;
	
 $insertsql = mysql_query($newsql, $conn_id) or die(mysql_error()); 
	
//	echo $newsql;
// mysql_query(" $newsql or mysql_error()");	  
// echo ("INSERT INTO instamapper (device_key,device_label,timestamp,latitude,longitude,altitude,speed,heading,added) VALUES $newsql or mysql_error()");	  
//if (mysql_error()) { echo 'mysql error '.mysql_error().' on the request '.$newpoint.'<br />';	} else { $numberoftracks++; }
	  
	  
$addedtodb='1';	  
	  
	  
	  } // ends database confirm

} // ends check to see if on database


if ($num_rowsb=='1') {  $alreadyondb='1'; $numberofondbtracks++; }



// echo ' 272 ';

 $linecoords=$linecoords.' ['.$latitude . "," . $longitude.'],';


if ($tempdate<>date(('H:i D j M '), $timestamp)) {


// echo ' 280 ';

$tempdate=date(('H:i D j M '), $timestamp);
$markertext=$tempdate.'<br />'.$speed;

 if ($globalprefrow['distanceunit']=='miles') { $markertext=$markertext. 'mph '; } 
 if ($globalprefrow['distanceunit']=='km') { $markertext=$markertext. 'km ph '; } 

$gmapdata=$gmapdata. "['" . $markertext ."',". $latitude. "," . $longitude . "," . $i ."],"; 
}

$i++;

$latestlat=	$latitude;
$latestlong=$longitude;
		

} // ends check for valid lattitude and longitude

$avgcount++;

// echo ' 327 ';

	


	} // ends individual position loop
	
//	echo '<br /> 382 ';
	
 
	
 //   $TrackStats->AddSegmentDelimiter();

//	echo ' 388 ';


	
	}  // ends track segment
  
//  echo ' 403 ';
  

  
} // ends individual track loop


// echo ' 403 ';


if ($trackoutofrange>'0') { echo '<h3>There were '.$trackoutofrange.' tracking positions out of range. </h3>'; }

// $template = @file_get_contents($templatepath.$templatefile);
// if ($template === FALSE) $template = "Cannot load template";

// $TrackStats->elevation_label = "Elevation";
// $TrackStats->distance_label = "Distance";
// $img = $TrackStats->GetTimeFigure();
// $pngfilename = sprintf("%s.png", $gpxfile);
// imagepng($img, $cachepath.$pngfilename);

// $template = str_replace("{GPXfilename}", iconv("CP1250", "UTF-8", $gpxfile), $template);
// $template = str_replace("{TrackName}", $TrackStats->GetTrackName(), $template);
// $template = str_replace("{TrackNumPoints}", $TrackStats->GetNumPoints(), $template);
// $template = str_replace("{TrackNumSegments}", $TrackStats->GetNumSegments(), $template);
// $template = str_replace("{TrackStartTime}", date("H:i l d.m.Y ", $TrackStats->GetStartTime()), $template);
// $template = str_replace("{TrackStopTime}", date("H:i l d.m.Y ", $TrackStats->GetStopTime()), $template);
// $template = str_replace("{TrackTotalDistance_km}", round($TrackStats->GetTotalDistance() / 1000, 2), $template);
// $template = str_replace("{TrackMinElevation_m}", $TrackStats->GetMinElevation(), $template);
// $template = str_replace("{TrackMaxElevation_m}", $TrackStats->GetMaxElevation(), $template);
// $template = str_replace("{TimeFigureURL}", $cachepath.urlencode(iconv("CP1250", "UTF-8", $pngfilename)), $template);
// $template = str_replace("{ExecTime_ms}", $ExecTimeWatch->GetTime(), $template);

if ($alreadyondb=='1') { echo '<h2> '.$numberofondbtracks.' locations were found which were already on the GPS tracking Database</h2>';}
if ($addedtodb=='1') { echo '<h2> '.$numberoftracks.' locations were added to the GPS tracking database.</h2>';}

// print($template);

?>