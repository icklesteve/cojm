<?php

$backupruntype="monthly-backup-stats.php";
$backupdescription="Monthly Backup Info Email";
$transfer_backup_infotext=' Monthly Backup Email ';

// $infotext.= ' got to ln 13 in backupstats <br />';

require "cronstats.php";



$bcc = $globalprefrow['emailbcc'];
$from= $globalprefrow['emailfrom'];

$plainbodytext=' Further info on each backup is available in the main audit log. <br />'.$crontext.'<br />'.$cronjobtable.'<br />'.$lastmonthandrecentlog;


$headers = 'From: '.$from. PHP_EOL;
$headers =$headers. 'Return-path: '.$from. PHP_EOL; 
$headers = $headers . 'Repy-To: '.$from . PHP_EOL.
           "X-Mailer: COJM-Courier-Online-Job-Management" . PHP_EOL.
		   "Cc: ".$globalprefrow['glob8'];
 $semi_rand = md5(time());     // Generate a boundary string    
 $mime_boundary = "==Multipart_COJM_Delivery_Boundary_x{$semi_rand}x";    
// Add the headers for a file attachment    
 $headers .= "\nMIME-Version: 1.0\n" .    
             "Content-Type: multipart/alternative;\n" .    
             " boundary=\"{$mime_boundary}\"";
			 
			 

			 
			 
$htmltext=$backupdescription.'<br />'.$plainbodytext;	



$plainbodytext.= $backupdescription.' Powered by COJM ' .$plainbodytext.' Powered by COJM ';


$htmltext ='<html><head> 
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">

<STYLE type=text/css>
BODY { BACKGROUND-COLOR: #ffffff; MARGIN-BOTTOM: 5px; COLOR: #000000; MARGIN-LEFT: 20px; FONT-SIZE: 11pt;
 }
div.line { width:100%; padding:10px 0 0 5px 0; border:2px solid #fcd66e; border-top:5px; border-right:0; }


table.backupinfo tr.major-error { background-color:#D80532; color: #ffffff; }  
table.backupinfo tr.minor-error { background-color:#e6b41a;  }
table.backupinfo tr.all-good { background-color:#7BDA2D; color:#006014; }
table.backupinfo { font-size: 16px;    border-spacing: 0px; }
table.backupinfo td { padding: 10px 50px 10px 40px; }

table.backupinfo th { text-align:left; padding: 10px 50px 10px 40px; }

table.backupinfo tr.alternate:nth-of-type(odd)  {
   background-color: #161d2a; color: #a5a4cf;
   }

table.backupinfo tr.alternate:nth-of-type(even)  {
background-color: #a5a4cf; color: #030914; 
}

</STYLE>


</head><body>'.$htmltext.'<br /> <small>Powered by <a href="http://www.cojm.co.uk" target="_blank">COJM</a></small> </body></html>';









 // Add a multipart boundary above the plain message    
 $messageplain = "This is a multi-part message in MIME format.\n\n" .    
            "--{$mime_boundary}\n" .    
            "Content-Type: text/plain; charset=\"utf-8\"\n" .    
            "Content-Transfer-Encoding: quoted-printable\n\n" .    
            $plainbodytext . "\n\n";
			
 // Add a multipart boundary above the plain message    
 $messagehtml = "--{$mime_boundary}\n" .    
            "Content-Type: text/html; charset=\"utf-8\"\n" .    
            "Content-Transfer-Encoding: quoted-printable\n\n".$htmltext;
			
			$message = $messageplain . $messagehtml ;	
$newfrom = htmlspecialchars ($from);
$subject = $globalprefrow['globalshortname']." Monthly Backup Report";		



$to='cojm@cojm.co.uk';



  $message = wordwrap($message, 70, PHP_EOL);
  $ok = @mail($to, $subject, $message, $headers, "-f$from");    

  if ($ok) {  $transfer_backup_infotext." Mail sent "; } else { $transfer_backup_infotext. " Message not sent. "; } 


//DEBUGGING

?>