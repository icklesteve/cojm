<?php

/*******************************************************************************************
    phpMySQLAutoBackup  -  Author:  http://www.DWalker.co.uk - released under GPL License
           For support and help please try the forum at: http://www.dwalker.co.uk/forum/
********************************************************************************************
Version    Date              Comment
0.2.0      7th July 2005     GPL release
0.3.0      June 2006  Upgrade - added ability to backup separate tables
0.4.0      Dec 2006   removed bugs/improved code
1.4.0      Dec 2007   improved faster version
1.5.0      Dec 2008   improved and added FTP backup to remote site
1.5.5      Feb 2011  more options for config added - email reports only and/or backup, save backup file to local and/or remote server.
                                Reporter added: email report of last 6 (or more) backup stats (date, total bytes exported, total lines exported) plus any errors
                                MySQL error reporting added  and Automated version checker added
1.6.0      Dec 2011  PDO version
1.6.1      April 2012 - CURLOPT_TRANSFERTEXT turned off (to stop garbaging zip file on transfer) and bug removed from write_back
1.6.2      Sept 2012 - corrected issue with CONSTRAINT and FOREIGN KEYS, related to InnoDB functionality/restores
                     - $newline change to constant: NEWLINE
1.6.3      Oct 2012 - corrected bug with CONSTRAINT and added CHARSET - bug fix code gratefully received from: vit.bares@gmail.com
********************************************************************************************/
$phpMySQLAutoBackup_version="1.6.3";
// ---------------------------------------------------------
// you must add your details below:

$backupruntype="run.php";
$backupdescription="Main Daily Backup";


//interval between backups - stops malicious attempts at bringing down your server by making multiple requests to run the backup
$time_interval=2400;// 3600 = one hour - only allow the backup to run once each hour

//DEBUGGING
define('DEBUG', 1);//set to 0 when done testing




// Turn off all error reporting unless debugging
if (DEBUG)
{
 error_reporting(E_ALL);
 $time_interval=1;// seconds - only allow backup to run once each x seconds
}
else error_reporting(0);



// Below you can uncomment the variables to specify separate tables to backup,
// leave commented out and ALL tables will be included in the backup.
$table_select[0]="bankhols";
$table_select[1]="chargedbybuild";
$table_select[2]="clientdep";
$table_select[3]="Clients";
$table_select[4]="cojm_favadr";
$table_select[5]="Cyclist";
$table_select[6]="emissionscomparison";
$table_select[7]="expensecodes";
$table_select[8]="expenses";
$table_select[9]="globalprefs";
$table_select[10]="invoicing";
$table_select[11]="localpref";
$table_select[12]="Services";
$table_select[13]="status";
// $table_select[14]="Orders";

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


define('LOCATION', dirname(__FILE__) ."/files/");

define('BALOCATION', realpath(dirname(__FILE__)) ."/files/");


 include(BALOCATION."phpmysqlautobackup.php");



// echo ' <br /> run.php got to 140 ';


?>