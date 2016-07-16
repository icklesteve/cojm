<?php


$phpMySQLAutoBackup_version="1.6.3";
$backupruntype="run99.php";
$backupdescription="Only Globals Test Backup";


//interval between backups - stops malicious attempts at bringing down your server by making multiple requests to run the backup
$time_interval=3600;// 3600 = one hour - only allow the backup to run once each hour

//DEBUGGING
define('DEBUG', 1);//set to 0 when done testing


 

// Turn off all error reporting unless debugging
if (DEBUG) {  error_reporting(E_ALL); $time_interval=1;// seconds - only allow backup to run once each x seconds
}
else error_reporting(0);


// Below you can uncomment the variables to specify separate tables to backup,
// leave commented out and ALL tables will be included in the backup.

$table_select[0]="globalprefs";


//note: when you uncomment $table_select only the named tables will be backed up.

// Below you can uncomment the variables to specify separate tables to EXCLUDE from the TOTAL backup,
// leave commented out and ALL tables will be included in the backup.
// $table_exclude[0]="instamapper";
// $table_exclude[1]="postcodeuk";
//$table_exclude[2]="ThirdTableName-to-exclude";
//note: when you uncomment $table_exclude these tables will be excluded from your backup.

$limit_to=10000000; //total rows to export - IF YOU ARE NOT SURE LEAVE AS IS
$limit_from=0; //record number to start from - IF YOU ARE NOT SURE LEAVE AS IS
//the above variables are used in this formnat:
//  SELECT * FROM tablename LIMIT $limit_from , $limit_to



// No more changes required below here
// ---------------------------------------------------------


// echo ' run3.php '.(LOCATION."phpmysqlautobackup.php");

define('BALOCATION', realpath(dirname(__FILE__)) ."/files/");
define('LOCATION', realpath(dirname(__FILE__)) ."/files/");

// $infotext.= ' <br /> run3.php ';

include(BALOCATION."phpmysqlautobackup.php");

?>