<?php
$infotext.= ' <br /> In gps-admin-rider-daily-check.php ln 2 <br />';

$checkloop='0';
$ridergpsdayloopa='-1';
$ridergpsdayloopb='0';

while ($checkloop<'10') {
$checkloop++;

///  1 day ago
$collecttime=mktime( 00, 00, 01, $nowmonth, $nowday+$ridergpsdayloopa, $nowyear );
$delivertime=mktime( 00, 00, 00, $nowmonth, $nowday+$ridergpsdayloopb, $nowyear );
$tcollecttime=date('H:i:s A D j M ', $collecttime);
$tdelivertime=date('H:i:s A D j M ', $delivertime);
$checkdate=date('Y-m-d', $collecttime);

 $sql = "SELECT DISTINCT device_key FROM `instamapper`  WHERE `timestamp` >= '$collecttime' AND `timestamp` <= '$delivertime' ORDER BY `device_key` ASC"; 
 $infotext.= '<br /> '.$tcollecttime. ' '. $tdelivertime;
 $sql_result = mysql_query($sql,$conn_id)  or mysql_error(); $sumtot=mysql_affected_rows(); if ($sumtot>'0.5') {
 while ($row = mysql_fetch_array($sql_result)) {      extract($row); 
 $infotext.=' device key is '.$row['device_key'];
 $dev_key=$row['device_key'];
  $gpsadmin = mysql_query("
SELECT cojmadmin_id FROM cojm_admin 
WHERE  cojm_admin_stillneeded='1' AND cojmadmin_rider_gps='1' AND cojmadmin_rider_id='$dev_key' AND cojm_admin_rider_date='$checkdate'
ORDER BY cojmadmin_id ASC LIMIT 1 
") or die(mysql_error());
$gpsadminrow = mysql_fetch_array($gpsadmin); 
if($gpsadminrow) {
 $infotext.=''.' job already outstanding on system ';
}
 else { 
 
 $infotext.=' no job outstanding on system, ';
 $testfile="cache/jstrack/".date('Y/m', $collecttime).'/'.date('Y_m_d', $collecttime).'_'.$dev_key.'.js';
  $infotext.='<br />'.$shouldhavelastran6.'<br />';
 
if (file_exists($testfile)) { 
  $infotext.='  found in cache, ';
 
}
else {
 $infotext.=' not found '. $testfile.' in cache, ';
 $sql="INSERT INTO cojm_admin 
   (cojm_admin_stillneeded, cojmadmin_rider_gps, cojmadmin_rider_id, cojm_admin_rider_date) 
    VALUES ('1', '1', '$dev_key', '$checkdate' )   ";
    $result = mysql_query($sql, $conn_id);
 if ($result){
 $infotext=$infotext."<br />39 Success adding admin job";
 $thiscyclist=mysql_insert_id(); 
   $infotext=$infotext.'<p>Admin Task '.$thiscyclist.' created.</p>'; 
 } else {
 $infotext=$infotext.mysql_error()." An error occured during setting admin q <br>".$sql;  
 } // ends sql
} // not found in cache
 } // ends no existing job in q
} // ends rider check
} // ends day check

$ridergpsdayloopa--;
$ridergpsdayloopb--;

} // ends day loop

?>