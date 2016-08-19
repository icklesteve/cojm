<?php
/*
    COJM Courier Online Operations Management
	ajaxkickcron.php - Logs screen size to audit trail, starts cojmcron
    Copyright (C) 2016 S.Young cojm.co.uk

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


if(isSet($_POST['screenWidth']))  { $screenWidth = $_POST['screenWidth']; } else { $screenWidth=''; }
if(isSet($_POST['screenHeight'])) { $screenHeight = $_POST['screenHeight']; } else { $screenHeight=''; }
if(isSet($_POST['newauditid']))   { $newauditid = $_POST['newauditid']; } else { $newauditid=''; }

$infotext='';

include_once "C4uconnect.php";

if ($newauditid) {

$newpoint="UPDATE cojm_audit SET auditscreenheight='".$screenHeight."', auditscreenwidth='".$screenWidth."' WHERE auditid='".$newauditid."'"; 
$result = mysql_query($newpoint, $conn_id);

if ($result){ 

if ($globalprefrow['showdebug']>'0') { echo ' Screen size added '.$newauditid;  }
} else { echo ' <h1>Screen Size NOT worked! </h1>'.$newauditid; } 
} else { echo ' no audit id '.$newauditid; }

 include 'cojmcron.php';

?>