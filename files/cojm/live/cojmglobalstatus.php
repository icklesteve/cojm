<?php 

$alpha_time = microtime(TRUE);
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
include "changejob.php";
$title = "COJM ";
?><!DOCTYPE html> 
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title); ?> Status Text Settings </title>
</head><body>
<?php 
$hasforms='1';
$adminmenu='0';
$settingsmenu='1';
$filename='cojmglobalstatus.php';
include "cojmmenu.php"; 
?>
<div class="Post">
<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<form action="#" method="post">
<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>">
<input type="hidden" name="page" value="editglobalstatus">
<table class="acc">
<tr><th scope="col">Status </th>
<th scope="col">Active </th>
<th scope="col">COJM Text</th>
<th scope="col">Public Text</th>
<th scope="col"><?php echo $globalprefrow['glob5']; ?> (if active)</th>
<th scope="col">Comments</th></tr>
<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>
<?php $query = "SELECT status, statusname, publicstatusname, activestatus, activestatuscyclist, statuscomment FROM status ORDER BY status"; 
$result_id = mysql_query ($query, $conn_id); 
while (list ($status, $statusname, $publicstatusname, $activestatus, $activestatuscyclist, $statuscomment) = mysql_fetch_row ($result_id)) { 
$publicstatusname = htmlspecialchars ($publicstatusname); 
$statusname = htmlspecialchars ($statusname);
echo '<tr><td>'. $status.'</td>
<input type="hidden" name="statusid'. $status.'" value="1" />
<td><input type="checkbox" name="activestatus'. $status.'" value="1" ';
 if ($activestatus) { echo 'checked';} 
 echo ' ></td>
<input type="hidden" name="activestatus110" value="1">
<td><input type="text" class="ui-state-default ui-corner-all" size="30" name="statusname'. $status.'" value=" '. $statusname.'"></td>
<td><input type="text" class="ui-state-default ui-corner-all" size="50" name="publicstatusname'. $status.'" value=" '. $publicstatusname.'"></td>
<td>';

if ($activestatuscyclist=='1') { echo '<img height="16px" width="16px" alt="Yes" src="images/icon_accept.gif">'; } else {
echo '<img height="16px" width="16px" alt="No" src="images/action_stop.gif">'; }

echo '</td><td>'. $statuscomment.'</td></tr><tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>';
 } 
?>
</table><br /><div class="line"> </div><button type="submit"> Edit Status Names</button>
</form><div class="line"></div></div></div>
<br /></div>
<?php 

include 'footer.php';

mysql_close(); ?>
</body></html>