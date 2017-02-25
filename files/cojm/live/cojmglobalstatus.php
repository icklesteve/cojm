<?php 

/*
    COJM Courier Online Operations Management
	cojmglobalstatus.php - Edit status names
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
	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<form action="#" method="post">
<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>">
<input type="hidden" name="page" value="editglobalstatus">
<table class="acc">
<tr><th scope="col">Status </th>
<th scope="col">COJM Text</th>
<th scope="col">Public Text</th>
<th scope="col">Comments</th></tr>

<?php

$query = "SELECT status, statusname, publicstatusname, activestatus, activestatuscyclist, statuscomment FROM status ORDER BY status";
$stmt = $dbh->query($query);
foreach ($stmt as $row) {
    $publicstatusname = htmlspecialchars ($row['publicstatusname']); 
    $statusname = htmlspecialchars ($row['statusname']);
    
    echo '<tr><td>'. $row['status'].'</td>
    <input type="hidden" name="statusid'. $row['status'].'" value="1" />
    <td><input type="text" class="ui-state-default ui-corner-all pad" size="30" name="statusname'. $row['status'].'" value="'. $statusname.'"></td>
    <td><input type="text" class="ui-state-default ui-corner-all pad" size="50" name="publicstatusname'. $row['status'].'" value="'. $publicstatusname.'"></td>';
    
    echo '<td>'. $row['statuscomment'].'</td></tr>';
}
?>
</table><br />
<button type="submit"> Edit Status Names</button>
</form>


<hr />
</div></div>
<br />
<?php 

include 'footer.php';

 ?>
</body></html>