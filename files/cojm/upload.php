<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
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
 
 
 
 
 
 
 
 
 
 
 
 
 $to = "cojm@cojm.co.uk, ".$globalprefrow['glob8'];
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
 $to = "cojm@cojm.co.uk";
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
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











 if (mail($to, $subject, $body)) {    echo("<p>Message successfully sent!</p>");

// echo $to.' '.$subject.' '.$body;

  } else { echo("<p>Message delivery failed...</p>"); }
 
 
 















echo ' 400 script completed OK ';

?>