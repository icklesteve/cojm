<?php
/*
    COJM Courier Online Operations Management
	gps-admin-rider.php - caches js rider gps tracking
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




$infotext.= ' <br /> In gps-admin-rider.php ln 4';

$gpsadmin = mysql_query("
SELECT cojmadmin_id, cojmadmin_rider_id, cojm_admin_rider_date FROM cojm_admin 
WHERE cojm_admin_stillneeded='1' AND cojmadmin_rider_gps='1' 
ORDER BY cojm_admin_rider_date DESC
LIMIT 1 
") or die(mysql_error());

$gpsadminrow = mysql_fetch_array($gpsadmin); 

if($gpsadminrow) {
$cojmadmin_rider_id= $gpsadminrow['cojmadmin_rider_id']; 
$cojm_admin_ref=$gpsadminrow['cojmadmin_id']; 
$riderdate=$gpsadminrow['cojm_admin_rider_date'];
$pexploded=explode( '-', $riderdate );
$thisyear=$pexploded['0'];
$thismonth=$pexploded['1'];
$thisday=$pexploded['2'];
$collecttime=gmmktime( 00, 00, 01, $thismonth, $thisday, $thisyear );
$delivertime=gmmktime( 23, 59, 59, $thismonth, $thisday, $thisyear );

$ID=$thisyear.'_'.$thismonth.'_'.$thisday.'_'.$cojmadmin_rider_id;

$infotext.= '<br /> rider  '.$cojmadmin_rider_id.' to be done '.$riderdate.' admin ref '.$cojm_admin_ref.'.  ';

$query =  " update cojm_admin set cojmadminstart = now() where cojmadmin_id ='$cojm_admin_ref'";	
mysql_query($query, $conn_id);

$query="SELECT cojmname FROM Cyclist WHERE trackerid = '$cojmadmin_rider_id' LIMIT 1"; $result=mysql_query($query, $conn_id); $orow=mysql_fetch_array($result);


 $sql = "SELECT latitude, longitude, speed, timestamp FROM `instamapper`  
 WHERE `device_key` = '$cojmadmin_rider_id' 
 AND `timestamp` >= '$collecttime'  AND `timestamp` <= '$delivertime' 
 ORDER BY `timestamp` ASC"; 
 
 $infotext.='<br /> 47 '.$sql.'<br />';
 
 
 
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 

$sumtot=mysql_affected_rows(); 

$infotext.=' <br /> sumtot is '.$sumtot;


if ($sumtot>'0.5') {

 $linecoords='';
 $prevts='';
 $markercount='0';
 $linecount='0';
 $customjs='';
 
 
$max_lat = '-99999';
$min_lat =  '99999';
$max_lon = '-99999';
$min_lon =  '99999';
 
 $markerout=array();
 $linarray=array();
 $linarray[]=' var line'.$ID.' = [ ';
 $markerout[]=' var markers'.$ID.' = [';

 
while ($map = mysql_fetch_array($sql_result)) {      extract($map); 

$linecount++;
$map['latitude']=round($map['latitude'],5);
$map['longitude']=round($map['longitude'],5);

$linarray[] = ' ['.$map['latitude'] . "," . $map['longitude'].'],';

$thists=date('H:i A D j M ', $map['timestamp']);

if ($thists<>$prevts) { // markers

  if($map['longitude']>$max_lon) { $max_lon = $map['longitude']; }
  if($map['longitude']<$min_lon) { $min_lon = $map['longitude']; }
  if($map['latitude']>$max_lat) { $max_lat = $map['latitude']; }
  if($map['latitude']<$min_lat)  { $min_lat = $map['latitude']; }

$markercount++;	 
$comments= date(' H:i D j M ', $map['timestamp']).'<br />';

if ($map['speed']) {  $comments=$comments . ''. round($map['speed']);
if ($globalprefrow['distanceunit']=='miles') { $comments=$comments. 'mph '; } 
else if ($globalprefrow['distanceunit']=='km') { $comments=$comments. 'km ph '; } }
 
 $comments=$comments . $orow['cojmname'];
 
 
$markerout[]=" ['" . $comments ."',". $map['latitude'] . "," . $map['longitude'] . ', "' .date('U', $map['timestamp']).'_'.$cojmadmin_rider_id.'_'. $markercount .'"],'; 
$prevts=date('H:i A D j M ', $map['timestamp']); 
	 
} // ends marker loop
} // ends polyline loop


$markerout = join("\n", $markerout);
$markerout = rtrim($markerout, ',').'    ]; ';


// echo ''.$markerout.'<hr />';
 
// $linarray[]= ' ]; ';
 
 $lineout = join("\n", $linarray);
 $lineout = rtrim($lineout, ','); 
 $lineout=$lineout. ' ]; ';

 $lineout.='
 
  markercount.push("'.$markercount.'");
 lineplotscount.push("'.$linecount.'");
 max_lon.push("'.$max_lon.'"); 
 min_lon.push("'.$min_lon.'"); 
 max_lat.push("'.$max_lat.'"); 
 min_lat.push("'.$min_lat.'");  ';

$mypath="cache/jstrack/".$thisyear."/".$thismonth."/";

if (!file_exists($mypath)) { 

$infotext.=' Creating directory '.$mypath;

mkdir($mypath, 0777, true); 


}
$filename = $mypath.$ID.'.js';
$handle = fopen($filename,"w");
$filecontent = $markerout.$lineout;
fwrite($handle,$filecontent);
fclose($handle);

$infotext.= " <br />created JS CacheFile ".$filename." <br /> ". $markercount." markers and ".$linecount." line points.";


} // sumtot > 0.5 positions found check



  $query =  "update cojm_admin set cojm_admin_stillneeded=0 where cojmadmin_id ='$cojm_admin_ref'";	
mysql_query($query, $conn_id);








$query =  " update cojm_admin set cojmadminfinish = now() where cojmadmin_id ='$cojm_admin_ref'";	
mysql_query($query, $conn_id);












$infotext.=' Ending gps admin row ';





} // ends check for gps admin row

?>