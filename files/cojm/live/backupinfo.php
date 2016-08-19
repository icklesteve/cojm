<?php 

$alpha_time = microtime(TRUE);

error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) { if ($serversecure=='') { echo 'ss : '.$serversecure;
 // header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } 
 }
 }
 
$title = "COJM Backup";
?><!doctype html>
<html lang="en"><head>

<link rel="stylesheet" href="css/themes/<?php echo $globalprefrow['clweb8']; 
?>/jquery-ui.css" type="text/css" />
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>

<title><?php print ($title); ?> </title>
</head><body>
<?php 
$hasforms='1';
include "changejob.php";
$filename='backupinfo.php';
$adminmenu=0; $settingsmenu=1;

// include "cojmcron.php";

include "cojmmenu.php"; 
?><div class="Post Spaceout">




<?php 


$report=' ';


require "cronstats.php";

// echo $infotext;


echo $cronjobtable;


echo $lastmonthandrecentlog;  

 
echo ' <p> ';


    if ($sumtot) {  echo ' '.$sumtot. ' scheduled job job currently running. '; } else {
	echo  ' No scheduled jobs running at present. '; }


$rt = mysql_query("SELECT COUNT(*) FROM Orders") or die(mysql_error());
$row = mysql_fetch_row($rt); if($row) { $totalorders= $row[0]; }

echo ' Total orders : '. $totalorders.'.  Backup failure emails sent to '.$globalprefrow['glob8'].'          </p>    ';


echo '<p>'.$crontext.'</p>

 </div><br />';


include 'footer.php';
echo '</body></html>';