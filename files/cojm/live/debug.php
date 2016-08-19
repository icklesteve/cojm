<?php 
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$settingsmenu=1;
$adminmenu = "1";

$title = "COJM";
?><!DOCTYPE html> 
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title); ?> Debug</title>
</head><body>
<? 

$adminmenu ="1";
$invoicemenu = "";
include "cojmmenu.php"; 


echo '<div class="Post"><br/>

http://www.intodns.com/

<br />

https://validator.w3.org/nu/

<br />

';




echo '<br><br>';





 phpinfo();







mysql_close(); ?>
</div></body></html>