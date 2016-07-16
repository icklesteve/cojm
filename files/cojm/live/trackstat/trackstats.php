<?php
/**********************************************************
 * Creates statistics from given GPX file
 **********************************************************/

// INPUTS
// $gpxfile = $_REQUEST['GPXfile'];
$templatefile = "template_EN.html";



// CONFIGURATION
$cachepath = "cache/";
$classpath = "classes/";
$templatepath = "";
$gpxpath = "gpx/";
define(DEBUG, FALSE);



// INITIALIZATION
require_once($classpath."require.php");
$ExecTimeWatch = new StopWatch();
$TrackStats = new TrackStatsGfx();

$gpxfile='Track201205281948.gpx';
//$gpxfile='sample.gpx';

echo ' gpxpath : '.$gpxpath;
echo ' gpxfile : '.$gpxfile;

// PARSE GPX FILE AND STORE DATA
$gpx_xml = @simplexml_load_file($gpxpath.$gpxfile);
if ($gpx_xml === FALSE) die("Cannot open XML document $gpxfile (file does not exist or is not valid XML)!");

if (DEBUG === TRUE) $tracknumber = 1;
foreach ($gpx_xml->trk as $track) {
  if (DEBUG === TRUE) printf("Track %d, %d segments, name \"%s\"<br>\n", $tracknumber++, count($track->trkseg), $track->name);
  if (DEBUG === TRUE) $tracksegmentnumber = 1;
  if (count($track->name)) $TrackStats->SetTrackName($track->name);
  foreach ($track->trkseg as $tracksegment) {
    if (DEBUG === TRUE) printf("--Segment %d, %d trackpoints<br>\n", $tracksegmentnumber++, count($tracksegment->trkpt));
    foreach ($tracksegment->trkpt as $trackpoint) {
       $lat = FALSE;
       $lon = FALSE;
       foreach($trackpoint->attributes() as $attr => $value) {
         if ($attr == "lat") $lat = (double) $value;
         if ($attr == "lon") $lon = (double) $value;
       }
       if (count($trackpoint->ele)) $ele = (int) $trackpoint->ele; else $ele = FALSE;
       if (count($trackpoint->time)) $timestamp = strtotime($trackpoint->time); else $timestamp = FALSE;
       $TrackStats->AddPoint(array("Latitude" => $lat, "Longitude" => $lon, "Elevation" => $ele, "Timestamp" => $timestamp));
       if (DEBUG === TRUE) printf("----Trackpoint lat %f, lon %f, ele %d, time %s, dist %f, azimuth %d<br>\n",
               $lat, $lon, $ele, date("Y-m-d H:i:s", $timestamp), $dist, $az);
    }
    $TrackStats->AddSegmentDelimiter();
  }
}



// CREATE OUTPUT
Header("Expires: ".GMDate("D, d M Y H:i:s")." GMT");

$template = @file_get_contents($templatepath.$templatefile);
if ($template === FALSE) $template = "Cannot load template";

$TrackStats->elevation_label = "Nadmorská výška";
$TrackStats->distance_label = "Vzdálenost";
$img = $TrackStats->GetTimeFigure();
$pngfilename = sprintf("%s.png", $gpxfile);
imagepng($img, $cachepath.$pngfilename);

$template = str_replace("{GPXfilename}", iconv("CP1250", "UTF-8", $gpxfile), $template);
$template = str_replace("{TrackName}", $TrackStats->GetTrackName(), $template);
$template = str_replace("{TrackNumPoints}", $TrackStats->GetNumPoints(), $template);
$template = str_replace("{TrackNumSegments}", $TrackStats->GetNumSegments(), $template);
$template = str_replace("{TrackStartTime}", date("d.m.Y H:i:s", $TrackStats->GetStartTime()), $template);
$template = str_replace("{TrackStopTime}", date("d.m.Y H:i:s", $TrackStats->GetStopTime()), $template);
$template = str_replace("{TrackTotalDistance_km}", round($TrackStats->GetTotalDistance() / 1000, 2), $template);
$template = str_replace("{TrackMinElevation_m}", $TrackStats->GetMinElevation(), $template);
$template = str_replace("{TrackMaxElevation_m}", $TrackStats->GetMaxElevation(), $template);
$template = str_replace("{TimeFigureURL}", $cachepath.urlencode(iconv("CP1250", "UTF-8", $pngfilename)), $template);
$template = str_replace("{ExecTime_ms}", $ExecTimeWatch->GetTime(), $template);


print($template);

?>