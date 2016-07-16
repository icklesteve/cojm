<?php

$phpMySQLAutoBackup_version="1.6.3";
$backupruntype="run4.php";
$backupdescription=" Weekly every Job Backup Pt 2, 2001-4000 ";

$time_interval=600;// 3600 = one hour - only allow the backup to run once each hour

//DEBUGGING 
define('DEBUG', 1);//set to 0 when done testing

// Turn off all error reporting unless debugging
if (DEBUG) { error_reporting(E_ALL);
 $time_interval=1;// seconds - only allow backup to run once each x seconds
}
else error_reporting(0);

$table_select[0]="Orders";

$limit_from='2001'; //record number to start from - IF YOU ARE NOT SURE LEAVE AS IS
$limit_to='4000 '; //total rows to export - IF YOU ARE NOT SURE LEAVE AS IS

//the above variables are used in this formnat:
//  SELECT * FROM tablename LIMIT $limit_from , $limit_to


define('BALOCATION', realpath(dirname(__FILE__)) ."/files/");
define('LOCATION', realpath(dirname(__FILE__)) ."/files/");

$infotext.= ' <br /> in run4.php  ';

include(BALOCATION."phpmysqlautobackup.php");
?>