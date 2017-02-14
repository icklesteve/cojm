<?php 
/*
    COJM Courier Online Operations Management
	backupinfo.php
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

include "cojmmenu.php"; 
echo '<div class="Post Spaceout">';

$report=' ';
require "cronstats.php";

echo $cronjobtable;
echo $lastmonthandrecentlog;  
 
echo ' <p> Backup failure emails sent to '.$globalprefrow['glob8'].' </p> ';
echo '<p>'.$crontext.'</p> </div><br />';

include 'footer.php';
echo '</body></html>';