<?php
/*
    COJM Courier Online Operations Management
	cojmcron.php - Runs one of the cron jobs if need be
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


$cj_time = microtime(TRUE);	

include_once "C4uconnect.php";

if (isSet($infotext)) {} else {$infotext='';}

if ($globalprefrow['showdebug']>0) {

// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...<br />\n";
        exit(1);
        break;

    case E_USER_WARNING:
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;
	    default:
//        $infotext=$infotext. "<br />$errstr on line $errline in $errfile\n";
		echo " $errstr on line $errline in $errfile<br /> \n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}

 error_reporting(E_ALL);
// set to the user defined error handler
$old_error_handler = set_error_handler("myErrorHandler");
}





// $infotext='';

GLOBAL $infotext;
GLOBAL $backupinfotext;
GLOBAL $backupruntype;

$backupdescription='';
$transfer_backup_infotext='';
$totalorders='0';


#cojmcron.php

$sumtot='';

$infotext.= ' started cojmcron, ';
// $infotext.= '<br /> Now is '.$nowsecs.' <br /> ';

require "cronstats.php";

// echo ' sumtot : '.$sumtot;
// $sumtot='0';  // comment out when not testing

    if ($sumtot) {  $infotext.=  ' already running '.$sumtot; 
	
	// adds comment to audit log to say tried & failed
	$backupruntype="No Cron Ran";
	$backupdescription="COJM Cron job already running ";
	
	} else {
$infotext.= ' nothing running at moment. '; 


$rt = mysql_query("SELECT COUNT(*) FROM Orders") or die(mysql_error());
$row = mysql_fetch_row($rt); if($row) { $totalorders= $row[0]; }

// $infotext.= ' Total orders : '. $totalorders.'. ';

// echo ' <br /> ';

// every 6 hours, after 6am, 12pm and 18pm and 00 hrs


// id=1 run.php
// id=2 run2.php
// id=3 cojm-12-hr-stats.php
// id=13 monthlybackupstats.php
// id=4 gpsadmin
// id=5 gpsadminrider
// id=6 addgpsriderstats


if (($lastran1)<($shouldhavelastran1)) {

$sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='1' LIMIT 1";  $result = mysql_query($sql, $conn_id);
if ($result){ $infotext.=  ' changed 160'; }  else { $infotext.=  " failed 160 "; } 
$infotext.= ' Once per day after 6pm run.php included ';
	
require  "phpmysqlautobackup/run.php";	

$sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='1' LIMIT 1"; 
$result = mysql_query($sql, $conn_id) or mysql_error(); if ($result){ $infotext.=  ' changed 153'; }  else { $infotext.=  " failed 153 "; } 	
	
	
} elseif (($lastran2)<($shouldhavelastran4)) {
          
$sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='2' LIMIT 1";  $result = mysql_query($sql, $conn_id);
if ($result){ $infotext.=  ' changed 159'; }  else { $infotext.=  " failed 159 "; } 

// $infotext.= ' Once per hour run2.php included ';
	
require  "phpmysqlautobackup/run2.php";	

$sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='2' LIMIT 1"; 
$result = mysql_query($sql, $conn_id) or mysql_error(); if ($result){ $infotext.=  ' changed 164'; }  else { $infotext.=  " failed 164 "; } 	
	
	
}  elseif ((($lastran6)<($shouldhavelastran6))) {
          
$sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='6' LIMIT 1";  $result = mysql_query($sql, $conn_id);
if ($result){ $infotext.=  ' changed 141'; }  else { $infotext.=  " failed 141 "; } 

// echo ' beers ';
 $infotext.= ' Daily check for previous days trackng to cache ';
 require  "phpmysqlautobackup/gps-admin-rider-daily-check.php";	


$sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='6' LIMIT 1"; 
$result = mysql_query($sql, $conn_id) or mysql_error(); if ($result){ $infotext.=  ' changed 149'; }  else { $infotext.=  " failed 149 "; } 	
	
}elseif ((($lastran13)<($shouldhavelastran5))) {
          
$sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='13' LIMIT 1";  $result = mysql_query($sql, $conn_id);
if ($result){ $infotext.=  ' changed 312'; }  else { $infotext.=  " failed 312 "; }

// echo ' beers ';
 $infotext.= ' Monthly monthly-backup-stats.php included ';
 require  "phpmysqlautobackup/monthly-backup-stats.php";	


$sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='13' LIMIT 1"; 
$result = mysql_query($sql, $conn_id) or mysql_error(); if ($result){ $infotext.=  ' changed 320'; }  else { $infotext.=  " failed 320 "; } 	
	

} elseif ((($lastran3)<($shouldhavelastran7))) {
 
$sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='3' LIMIT 1";  $result = mysql_query($sql, $conn_id);
if ($result){ $infotext.=  ' changed 312'; }  else { $infotext.=  " failed 312 "; }
	
// $infotext.='<br /> 7 should have been '. $shouldhavelastran7.'';

 $infotext.= ' 12 hour cojm stats update ';
 require  "phpmysqlautobackup/cojm-12-hr-stats.php";	

// id=3 cojm-12-hr-stats.php	
$sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='3' LIMIT 1"; 
$result = mysql_query($sql, $conn_id) or mysql_error(); if ($result){ $infotext.=  ' changed 320'; }  else { $infotext.=  " failed 320 "; } 	
	

	
}	else {
	

// temp

// require  "phpmysqlautobackup/cojm-12-hr-stats.php";	

	
	
	$infotext.="<br /> No Scheduled jobs to run, checking admin queues";
	

	
	
	

// $infotext.= ' checking if rider-gps-admin task needed ';
	
$gpsrideradmin = mysql_query("SELECT COUNT(*) FROM cojm_admin WHERE cojm_admin_stillneeded='1' AND cojmadmin_rider_gps='1' ") or die(mysql_error());
$gpsriderrow = mysql_fetch_row($gpsrideradmin); if($gpsriderrow) { $gpsrideradmintotal= $gpsriderrow[0]; }
$infotext.= '<br /> '.$gpsrideradmintotal.' Job(s) in Rider GPS Admin Q ';

	
if ($gpsrideradmintotal>'0') {


$sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='5' LIMIT 1";  $result = mysql_query($sql, $conn_id);
if ($result){ $infotext.=  ' changed 168'; }  else { $infotext.=  " failed 168 "; } 

 $infotext.= '<br /> gps-admin-rider.php included ';
 require  "phpmysqlautobackup/gps-admin-rider.php";	

 $sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='5' LIMIT 1"; 
$result = mysql_query($sql, $conn_id) or mysql_error(); if ($result){ $infotext.=  ' finished running gps-admin-rider.php '; }  else { $infotext.=  " failed running gps-admin-rider.php "; } 	







}
	
	
	
	
	
	
// $infotext.= ' checking if gps-admin task needed ';
	
$gpsadmin = mysql_query("SELECT COUNT(*) FROM cojm_admin WHERE cojm_admin_stillneeded='1' AND cojmadmin_tracking='1' ") or die(mysql_error());
$gpsadminrow = mysql_fetch_row($gpsadmin); if($gpsadminrow) { $gpsadmintotal= $gpsadminrow[0]; }
$infotext.= '<br /> '.$gpsadmintotal.' Job(s) in individ job GPS Admin Q ';
	
	
if ($gpsadmintotal>'0')	 {

$sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='4' LIMIT 1";  $result = mysql_query($sql, $conn_id);
if ($result){ $infotext.=  ' changed 173'; }  else { $infotext.=  " failed 173 "; } 

 $infotext.= '<br /> gps-admin.php included ';
 require  "phpmysqlautobackup/gps-admin.php";	

 $sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='4' LIMIT 1"; 
$result = mysql_query($sql, $conn_id) or mysql_error(); if ($result){ $infotext.=  ' changed 181'; }  else { $infotext.=  " failed 181 "; } 	

}
	
	
	




// $infotext.= ' test ';
// require  "phpmysqlautobackup/run99.php";	
// require  "phpmysqlautobackup/gps-admin-rider-daily-check.php";	





// $infotext.= ' Monthly monthly-backup-stats.php included ';
// require  "phpmysqlautobackup/monthly-backup-stats.php";	


	
}

}	// finishes not running at present check
		
	$now_time = microtime(TRUE);
    $cj_lapse_time = $now_time - $cj_time;
    $cj_msec = $cj_lapse_time * 1000.0;
    $cj_echo = number_format($cj_msec, 1);	
	
$infotext.=  '<br /> finished cojmcron in '.$cj_echo.'ms. ';	

$backupdescription= $backupdescription.'<br />'.$transfer_backup_infotext;


if ($backupruntype)	{
 $newpoint="INSERT INTO cojm_audit (auditid,audituser,auditorderid,auditpage,auditfilename,auditmobdevice,
 auditbrowser,audittext,auditcjtime,auditpagetime,auditmidtime,auditinfotext)   
 VALUES ('','CojmCron','','cojmcron.php','$backupruntype','',
 '','$backupdescription','','$cj_echo','','$infotext')";
 mysql_query($newpoint, $conn_id) or mysql_error(); $newauditid=mysql_insert_id();
 if (mysql_error()) { echo '<div class="moreinfotext"><h1> Problem saving audit log </h1></div>'; }
}






if ($globalprefrow['showdebug']>0) { echo $backupdescription.'<hr />' . $infotext; }

?>