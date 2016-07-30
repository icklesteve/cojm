<?php

/*
    COJM Courier Online Operations Management
	upload.php - Receives live GPS data from apps like OpenGPSTracker
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


if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
include "live/C4uconnect.php";

if (isset($_GET['latitude'])) { $latitude=round($_GET['latitude'],5); } else { $latitude=''; }
if (isset($_GET['longitude'])) { $longitude=round($_GET['longitude'],5); } else { $longitude=''; }
if (isset($_GET['trackid'])) { $device_key=trim($_GET['trackid']); } else { $device_key=''; }
if (isset($_GET['alt'])) { $alt=round($_GET['alt'],1); } else { $alt=''; }
if (isset($_GET['speed'])) { $speed=trim($_GET['speed']); } else { $speed='0'; }
if (isset($_GET['bear'])) { $heading=trim($_GET['bear']); } else { $heading=''; }
if (isset($_GET['time'])) { $timestamp=trim($_GET['time']); } else { $timestamp=''; }
$device_label='LiveOpenGPS';
$timestamp=substr($timestamp, 0, -3);  


if ($speed) {
 if ($globalprefrow['distanceunit']=='miles') {
$speed=($speed*'0.621');
}
$speed=round($speed,1);
}


 
 if (($device_key) and ($timestamp) and ($latitude) and ($longitude)) {
 
 
 
 
 $to = $globalprefrow['glob8'];
 $subject = "COJM Live Tracking  on ".$globalprefrow['backupemailfrom'];
 $body =       "\n Tracking on line 16 upload.php"; 
 $body =$body. "\n line 49 Time is ".$timestamp;
 $body =$body. "\n Lat is ".$latitude;
 $body =$body. "\n Lon is ".$longitude;
 $body =$body. "\n Device Key is ".$device_key;
 $body =$body. "\n Speed is ".$speed;
 $body =$body. "\n Altitude is ".$alt;
 $body =$body. "\n Bearing is ".$heading;
 $body =$body. "\n Time difference ".($timestamp-(date("U")));
 $body =$body. "\n Time diff via U ".date("U", $timestamp);
 $body =$body. "\n U               ".date("U"); 
 $body =$body. "\n New time  via U ".date("U", $timestamp); 
 
 
 
 
// check if record already exists	  
 $checkexists ="SELECT timestamp FROM instamapper WHERE timestamp = '$timestamp' AND device_key = '$device_key' LIMIT 1";
 $checktrack = mysql_query($checkexists, $conn_id) or mysql_error(); 

$num_rows=mysql_num_rows($checktrack);
if ($num_rows<1) {
  
//	  if ($device_key>0) {  // echo 'located device key';

$newpoint="INSERT INTO instamapper (id,device_key,device_label,timestamp,latitude,longitude,altitude,speed,heading,added) 
	  VALUES ('','$device_key','$device_label','$timestamp','$latitude','$longitude','$alt','$speed','$heading'," . time() . ")";

	  
$body='\n'.$newpoint.$body;
	
	
$newpointtwo=str_replace("@", ":", "$newpoint", $errorcount);

 
// if ($notavariable=='hjguhjbvfyuhj') {

mysql_query($newpoint, $conn_id) or mysql_error(); 	

if (mysql_error() or ($errorcount)) {
$body='\n\n MYSQL ERROR WHEN ADDING TO DB \n'.$newpoint.' '.$body;

if (mail($to, $subject, $body)) {    echo"<p>Message successfully sent!</p>";



// echo $to.' '.$subject.' '.$body;


  } else { echo("<p>Message delivery failed...</p>"); }
 } // ends error
// } // emds not a variable
} // ends rum_rows


// temp to make sure works
// echo $to.' '.$subject.' '.$body;


//   if (mail($to, $subject, $body)) {
//   echo("<p>Message successfully sent!</p>");
// } else { echo("<p>Message delivery failed...</p>"); }

} // ends check for device key and timestamp









// if in debug mode


 if (mail($to, $subject, $body)) {    echo("<p>Message successfully sent!</p>");

// echo $to.' '.$subject.' '.$body;

  } else { echo("<p>Message delivery failed...</p>"); }
 
 
 


echo ' 400 script completed OK ';
// end debug mode


?>