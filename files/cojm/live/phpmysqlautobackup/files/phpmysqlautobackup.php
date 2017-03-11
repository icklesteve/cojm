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
1.5.4      Nov 2009   version added to xmail
1.5.5      Feb 2011  more options for config added - email reports only and/or backup, save backup file to local and/or remote server.
                                Reporter added: email report of last 6 (or more) backup stats (date, total bytes exported, total lines exported) plus any errors
                                MySQL error reporting added  and Automated version checker added
1.6.0      Dec 2011  PDO version
1.6.2      Sept 2012 - updated newline to constant:  NEWLINE
********************************************************************************************/
$phpMySQLAutoBackup_version="1.6.2";

$from_emailaddress = $globalprefrow['backupemailfrom']; // your email address to show who the email is from (should be different $to_emailaddress)
$report_emailaddress = $globalprefrow['backupemailto']; //address to send reports, not the backup just details on last backups (can be same as above)
$to_emailaddress = ""; // your email address to send backup files to
                       //best to specify an email address on a different server than the MySQL db  ;-)

$send_email_backup='';//set to 1 and will send the backup file attached to the email address above
$send_email_report='';//set to 1 and will send an email report to the email address above




$ftp_username=$globalprefrow['backupftpusername']; // your ftp username
$ftp_password=$globalprefrow['backupftppasswd']; // your ftp password
$ftp_server=$globalprefrow['backupftpserver']; // eg. ftp.yourdomainname.com





$ftp_path="/".date('Y').'-'.date('m')."/"; // can be just "/" or "/public_html/securefoldername/"

// $ftp_path="/public_html/backups/".$nowyear."/"; // can be just "/" or "/public_html/securefoldername/"


/****************************************************************************************
The settings below are for the more the more advanced user  - in the majority of cases no changes will be required below. */
define('NEWLINE',"\n"); //email attachment - if backup file is included within email body then change this to "\r\n"
define('LOG_REPORTS_MAX', 600);//the total number of reports to retain - set this to any number you wish (better to keep below 50 as all reports are included in the email)
$save_backup_zip_file_to_server = 1; // if set to 1 then the backup files will be saved in the folder: /phpMySQLAutoBackup/backups/
                                    //(you must also chmod this folder for write access to allow for file creation)
define('TOTAL_BACKUP_FILES_TO_RETAIN',300);//the total number of backups files to retain, e.g. 10. the 10 most recent files mtime (modified date) are kept, older versions are deleted


define('DBDRIVER', 'mysql');
define('DBPORT', '3306');

$tempinfotext='';
GLOBAL $tempinfotext;
GLOBAL $transfer_backup_infotext;


$backup_type="\n\n BACKUP Type: Full database backup (all tables included)\n\n";
if (isset($table_select))
{
 $backup_type="\n\n BACKUP Type: partial, includes tables:\n";
 foreach ($table_select as $key => $value) $backup_type.= "  $value;\n";
}
if (isset($table_exclude))
{
 $backup_type="\n\n BACKUP Type: partial, EXCLUDES tables:\n";
 foreach ($table_exclude as $key => $value) $backup_type.= "  $value;\n";
}
$errors="";


 
include ("phpmysqlautobackup_extras.php");


include(BALOCATION."schema_for_export.php");
$versionCheck = new version();
$version_info=$versionCheck->check($phpMySQLAutoBackup_version);
$backup_info="\n".$version_info."\n\n";

$backup_info.=$backup_type;

 $backup_info.= $recordBackup->get();

// zip the backup and email it
 $backup_file_name = 'mysql_'.$backupruntype.'_'.$_SERVER['HTTP_HOST'].strftime("_%d_%b_%Y_time_%H_%M_%S.sql",time()).'.gz';
 $dump_buffer = gzencode($buffer);


if ($save_backup_zip_file_to_server) write_backup($dump_buffer, $backup_file_name);



$password = BACKUPPASSWD;
$outfile = BALOCATION.'backups/'.date("Y").'-'.date("m").'/'.$backup_file_name.'.zip';
$infile  = BALOCATION.'backups/'.date("Y").'-'.date("m").'/'.$backup_file_name;


shell_exec("zip -j -P $password $outfile $infile");
// readfile($outfile);
// @unlink($outfile);


$justgz_file_name=$backup_file_name;
$backup_file_name.='.zip';


// readfile ($infile); 
 
 
 

//FTP backup file to remote server
if (isset($ftp_username))
{
 //write the backup file to local server ready for transfer if not already done so
 if (!$save_backup_zip_file_to_server) write_backup($dump_buffer, $backup_file_name);
  $transfer_backup =  new transfer_backup();

// $data = transfer_backup();

// $transfer_backup_infotext = $transfer_backup[0];
// $bytes = $transfer_backup[1];

 $errors.= $transfer_backup->transfer_data($ftp_username,$ftp_password,$ftp_server,$ftp_path,$backup_file_name,$lines_exported,$backupdescription);


}










if ($send_email_backup) xmail($to_emailaddress,$from_emailaddress, "phpMySQLAutoBackup: $backup_file_name", $dump_buffer, $backup_file_name, $backup_type, $phpMySQLAutoBackup_version);
// if ($send_email_report) {
 $msg_email_backup="";
 $msg_ftp_backup="";
 $msg_local_backup="";
 if ($send_email_backup) $msg_email_backup="\nthe email with the backup attached has been sent to: $to_emailaddress \n";
 if (isset($ftp_username)) $msg_ftp_backup="\nthe password protected backup zip file has been transferred to: $ftp_server $ftp_path \n  $transfer_backup_infotext  \n";
 if ($save_backup_zip_file_to_server) $msg_local_backup="\n also saved locally. \n";
 if ($errors=="") $errors="None captured."; 

 if ($send_email_report) {
 mail($report_emailaddress,
                                  "COJM $backupdescription using ($backup_file_name)",
                                  "ERRORS: $errors \nSAVE or DELETE THIS MESSAGE - no backup is attached $msg_email_backup $msg_ftp_backup $msg_local_backup \n$backup_info \n ",
                                  "From: $from_emailaddress\nReply-To:$from_emailaddress");		  
					 }


 $infotext.= '<p>'."\n\nERRORS: ".$errors.'<br />'.$backup_info.'<br />'.$msg_ftp_backup.'<br />'.$msg_local_backup.'</p>';


// $infotext.= $transfer_backup_infotext;


// echo ' try passwd zip ';
unlink(BALOCATION."backups/".date("Y").'-'.date("m").'/'.$justgz_file_name);

?>