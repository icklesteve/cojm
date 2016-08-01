<?php
/*
    COJM Courier Online Operations Management
	upload.php - Receives live GPS data from apps like OpenGPSTracker or Self-Hosted-GPS-Tracker
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
if (isset($_GET["lat"]) && preg_match("/^-?\d+\.\d+$/", $_GET["lat"])
 && isset($_GET["lon"]) && preg_match("/^-?\d+\.\d+$/", $_GET["lon"])
 && isset($_GET["t"]) && preg_match("/^-?\d+\.\d+$/", $_GET["t"])
 && isset($_GET["trackid"]) && preg_match("/^-?\d+\.\d+$/", $_GET["trackid"]) ) {

include "live/C4uconnect.php";

if (isset($_GET['lat'])) { $latitude=round($_GET['lat'],5); } else { $latitude=''; }
if (isset($_GET['lon'])) { $longitude=round($_GET['lon'],5); } else { $longitude=''; }
if (isset($_GET['trackid'])) { $device_key=trim($_GET['trackid']); } else { $device_key=''; }
if (isset($_GET['t'])) { $timestamp=trim($_GET['t']); } else { $timestamp=''; }

$device_label='LiveOpenGPS';
$timestamp=substr($timestamp, 0, -3);  

 $to = $globalprefrow['glob8'];
 $subject = "COJM Live Tracking  on ".$globalprefrow['backupemailfrom'];
 $body =       "\n Tracking in upload.php"; 
 $body =$body. "\n line 64 Timestamp is ".$timestamp;
 $body =$body. "\n Lat is ".$latitude;
 $body =$body. "\n Lon is ".$longitude;
 $body =$body. "\n Device Key is ".$device_key;
 $body =$body. "\n Time difference ".($timestamp-(date("U")))."second";

if ((is_numeric($device_key)) and (is_numeric($timestamp)) and (is_numeric($latitude)) and (is_numeric($longitude))) {

$newpoint="INSERT INTO instamapper (device_key,device_label,timestamp,latitude,longitude,added)
	  VALUES ('$device_key','$device_label','$timestamp','$latitude','$longitude','$alt','$speed','$heading'," . time() . ")";

$body='\n'.$newpoint.$body;

mysql_query($newpoint, $conn_id) or mysql_error();

if (mysql_error()) { $body='\n\n MYSQL ERROR WHEN ADDING TO DB \n'.$newpoint.' '.$body; }

} // ends check for device key and timestamp

// if in debug mode
if (mail($to, $subject, $body)) { echo("<p>Message successfully sent!</p>"); }
echo $body;
echo ' script completed OK ';
// end debug mode
}

echo ' script completed OK ';


?>