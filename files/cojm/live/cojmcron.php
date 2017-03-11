<?php
/*
    COJM Courier Online Operations Management
	cojmcron.php - Runs one of the cron jobs if need be
    Copyright (C) 2017 S.Young cojm.co.uk

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

if ($globalprefrow['showdebug']>0) { // error handler function
    function myErrorHandler($errno, $errstr, $errfile, $errline) {
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



GLOBAL $infotext;
GLOBAL $backupinfotext;
GLOBAL $backupruntype;
GLOBAL $auditerror;

$backupdescription='';
$transfer_backup_infotext='';
$totalorders='0';
$auditerror=0;

#cojmcron.php

$sumtot='';
$backupruntype='COJMCron.php';
$infotext.= ' started cojmcron, ';
// $infotext.= '<br /> Now is '.$nowsecs.' <br /> ';


$hidelastfiredstats=1;

require "cronstats.php";

// echo ' sumtot : '.$sumtot;

$ranacron=0;

if ($sumtot) {
    $ranacron=1;
    $infotext.=  ' already running '.$sumtot.'  cronjob. ';
	// adds comment to audit log to say tried & failed
	$backupruntype="No Cron Ran";
	$backupdescription="COJM Cron job already running ";
    $auditerror=1;
} else {
    $infotext.= ' nothing running at moment. ';

    // every 6 hours, after 6am, 12pm and 18pm and 00 hrs


    // id=1 run.php
    // id=2 run2.php
    // id=3 cojm-12-hr-stats.php
    // id=13 monthlybackupstats.php
    // id=4 gpsadmin
    // id=5 gpsadminrider
    // id=6 addgpsriderstats


    if (($lastran1)<($shouldhavelastran1)) {
        $ranacron=1;
        $infotext.= ' Once per day after 6pm run.php included ';
        $stmt = $dbh->query("UPDATE cojm_cron SET currently_running=1 WHERE ID='1' LIMIT 1");

        try {
            require  "phpmysqlautobackup/run.php";
        }
        
        catch(Exception $e) {
            $auditerror=1;
            $infotext.= "Message : " . $e->getMessage();
            $infotext.= "Code : " . $e->getCode();
        }
        
        $sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='1' LIMIT 1";	
        $stmt = $dbh->query($sql);
    }
    elseif (($lastran2)<($shouldhavelastran4)) {
        $ranacron=1;          
        $sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='2' LIMIT 1";
        $stmt = $dbh->query($sql);
        $infotext.=  ' starting run2.php ';
        require  "phpmysqlautobackup/run2.php";	
        $sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='2' LIMIT 1"; 
        $stmt = $dbh->query($sql);
        $infotext.=  ' finished run2.php once / hr backup';
    }
    elseif ((($lastran6)<($shouldhavelastran6))) {
        $ranacron=1;
        $sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='6' LIMIT 1";
        $stmt = $dbh->query($sql);
        $infotext.= ' Daily check for previous days trackng to cache ';
        require  "phpmysqlautobackup/gps-admin-rider-daily-check.php";
        $sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='6' LIMIT 1";
        $stmt = $dbh->query($sql);
    }
    elseif ((($lastran13)<($shouldhavelastran5))) {
        $ranacron=1;        
        $sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='13' LIMIT 1";
        $stmt = $dbh->query($sql);
        $infotext.= ' Monthly monthly-backup-stats.php included ';
        require  "phpmysqlautobackup/monthly-backup-stats.php";	
        $sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='13' LIMIT 1"; 
        $stmt = $dbh->query($sql);
    }
    elseif ((($lastran3)<($shouldhavelastran7))) {
        $ranacron=1;        
        $sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='3' LIMIT 1";
        $stmt = $dbh->query($sql);
        $infotext.= ' 12 hour cojm stats update ';
        require  "phpmysqlautobackup/cojm-12-hr-stats.php";
        $sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='3' LIMIT 1";
        $stmt = $dbh->query($sql);
    }
    else {
        $infotext.="<br /> No Scheduled jobs to run, checking admin queues";
        
        $gpsrideradmintotal = $dbh->query("SELECT COUNT(*) FROM cojm_admin WHERE cojm_admin_stillneeded='1' AND cojmadmin_rider_gps='1'")->fetchColumn();        
        $gpsadmintotal = $dbh->query("SELECT COUNT(*) FROM cojm_admin WHERE cojm_admin_stillneeded='1' AND cojmadmin_tracking='1'")->fetchColumn();
        $infotext.= '<br /> '.$gpsrideradmintotal.' Job(s) in Rider GPS Admin Q ';
        $infotext.= '<br /> '.$gpsadmintotal.' Job(s) in individ job GPS Admin Q ';
	
        if ($gpsrideradmintotal>0) {
            $ranacron=1;            
            $sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='5' LIMIT 1";
            $stmt = $dbh->query($sql);
            $infotext.= '<br /> gps-admin-rider.php included ( Whole day cache )';
            require  "phpmysqlautobackup/gps-admin-rider.php";	
            $sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='5' LIMIT 1"; 
            $stmt = $dbh->query($sql);
            $infotext.=  ' finished running gps-admin-rider.php ';
        }
        if ($gpsadmintotal>0)	 {
            $ranacron=1;            
            $sql = "UPDATE cojm_cron SET currently_running=1 WHERE ID='4' LIMIT 1";
            $stmt = $dbh->query($sql);
            $infotext.= '<br /> about to require gps-admin.php ( kmz + js for individ job ) ';
            require  "phpmysqlautobackup/gps-admin.php";
            $sql = "UPDATE cojm_cron SET currently_running=0 , time_last_fired=".date("U")." WHERE ID='4' LIMIT 1";
            $stmt = $dbh->query($sql);
            $infotext.=  ' finished gps admin ';
        }
    }

} // finishes not running at present check

$now_time = microtime(TRUE);
$cj_lapse_time = $now_time - $cj_time;
$cj_msec = $cj_lapse_time * 1000.0;
$cj_echo = number_format($cj_msec, 1);	
	
$infotext.=  '<br /> finished cojmcron in '.$cj_echo.'ms. ';	

$backupdescription.= '<br />'.$transfer_backup_infotext;


if ($ranacron==1) {
    try {

        $auditinfotext = str_replace("'", ":", "$infotext", $count);
        $auditinfotext = str_replace("'", ":", "$infotext", $count);

        $query = " INSERT INTO cojm_audit 
        (audituser,
        auditpage,
        auditfilename,
        audittext,
        auditpagetime,
        auditinfotext,
        auditorderid,
        auditerror)
    VALUES 
    ('CojmCron',
    'cojmcron.php',
    :backupruntype ,
    :backupdescription , 
    :cj_msec ,
    :auditinfotext ,
    '0',
    :auditerror
    ) 
    ";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':backupruntype', $backupruntype, PDO::PARAM_INT);
        $stmt->bindParam(':backupdescription', $backupdescription, PDO::PARAM_INT);
        $stmt->bindParam(':cj_msec', $cj_msec, PDO::PARAM_INT);
        $stmt->bindParam(':auditinfotext', $auditinfotext, PDO::PARAM_INT);
        $stmt->bindParam(':auditerror', $auditerror, PDO::PARAM_INT);
        $stmt->execute();

        $newauditid = $dbh->lastInsertId();
        
        $infotext.=' Audit log updated, ID '.$newauditid;
    }
    catch(PDOException $e) { $infotext.= $e->getMessage(); }
}

if ($globalprefrow['showdebug']>0) { echo $backupdescription.'<hr />' . $infotext; }

?>