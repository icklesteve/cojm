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
 && isset($_GET["trackid"])) {

$latitude=round($_GET['lat'],5);
$longitude=round($_GET['lon'],5);
$device_key=trim($_GET['trackid']);
 if (isset($_GET['t'])) { 
 $timestamp=trim($_GET['t']); 
 $timestamp=substr($timestamp, 0, -3); 
 } else { $timestamp=date("U"); }

$device_label='LiveOpenGPS';
 

include "live/C4uconnect.php";

// check for valid device key passed
try {
$sql = "SELECT cojmname FROM Cyclist WHERE trackerid=:device_key AND isactive='1'"; 
$result = $dbh->prepare($sql); 
$result->bindParam(':device_key', $device_key, PDO::PARAM_INT); 
$result->execute(); 
$validriderid = $result->fetchColumn(); 
}
catch(PDOException $e) { $body.="\n " .$e->getMessage(); }


 $to = $globalprefrow['glob8'];
 $subject = "COJM Live Tracking  on ".$globalprefrow['backupemailfrom'];
 $body = "\n Tracking in upload.php"; 
 $body.= "\n Timestamp is ".$timestamp;
 $body.= "\n Lat is ".$latitude;
 $body.= "\n Lon is ".$longitude;
 $body.= "\n Device Key is ".$device_key;
 $body.= "\n Time difference ".($timestamp-(date("U")))."second";
 $body.= "\n Rider Found : ".$validriderid;

if  (!$device_key) { $body.="\n No Tracker ID Set"; }
elseif (!$validriderid) { $body.="\n Tracker ID Not Found "; } 
else {

try {
$query = "INSERT INTO instamapper 
SET 
device_key=:device_key, 
device_label=:device_label, 
timestamp=:timestamp,
latitude=:latitude,
longitude=:longitude,
added=:added";

$stmt = $dbh->prepare($query);
$stmt->bindParam(':device_key', $device_key, PDO::PARAM_INT); 
$stmt->bindParam(':device_label', $device_label, PDO::PARAM_INT); 
$stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_INT); 
$stmt->bindParam(':latitude', $latitude, PDO::PARAM_INT); 
$stmt->bindParam(':longitude', $longitude, PDO::PARAM_INT); 
$stmt->bindParam(':added', time(), PDO::PARAM_INT); 
$stmt->execute();
$body.="\n Insert ID : ". $dbh->lastInsertId();
}
catch(PDOException $e) { $body.= $e->getMessage(); }

} 


if ($globalprefrow['showdebug']>'0') { // if in debug mode
if (mail($to, $subject, $body)) { echo("<p>Message successfully sent!</p>"); }
echo $body;
} // end debug mode

$dbh=null;

} // ends check for post values

exit;
?>