<?php

if(isSet($_POST['screenWidth']))  { $screenWidth = $_POST['screenWidth']; } else { $screenWidth=''; }
if(isSet($_POST['screenHeight'])) { $screenHeight = $_POST['screenHeight']; } else { $screenHeight=''; }
if(isSet($_POST['newauditid']))   { $newauditid = $_POST['newauditid']; } else { $newauditid=''; }

$infotext='';

include_once "C4uconnect.php";

if ($newauditid) {

$newpoint="UPDATE cojm_audit SET auditscreenheight='".$screenHeight."', auditscreenwidth='".$screenWidth."' WHERE auditid='".$newauditid."'"; 
$result = mysql_query($newpoint, $conn_id);

if ($result){ 

if ($globalprefrow['adminlogoback']>'0') { echo ' Screen size added '.$newauditid;  }
} else { echo ' <h1>Screen Size NOT worked! </h1>'.$newauditid; } 
} else { echo ' no audit id '.$newauditid; }

 include 'cojmcron.php';

?>