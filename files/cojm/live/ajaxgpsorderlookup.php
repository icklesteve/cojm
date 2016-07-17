<?php

include "C4uconnect.php";

if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  exit(); } }
if (isset($_POST['markervar'])) { $markervar=(trim($_POST['markervar'])); }

// echo ' 14 beer '.$markervar;

$markervarexploded=explode( '_', $markervar );

// echo ' time is '.$markervarexploded[0].' rider is '.$markervarexploded[1];

$timetocheck=date('Y-m-d H:i', $markervarexploded[0]);

$newsql= ' SELECT ID, starttrackpause, finishtrackpause, handoverCyclistID FROM Orders 
INNER JOIN Cyclist ON Orders.CyclistID = Cyclist.CyclistID 
WHERE trackerid='.$markervarexploded[1].'
AND ShipDate  > "'.$timetocheck.'"
AND collectiondate < "'.$timetocheck.'"

';

// echo $newsql;

$sql="SELECT * FROM cojm_favadr WHERE favadrclient = '$clientid' AND favadrisactive ='1' ";

$sql_result = mysql_query($newsql,$conn_id);

 while ($favrow = mysql_fetch_array($sql_result)) { extract($favrow);

// echo 'pause :  '.$favrow['starttrackpause'].'<br />';
// echo 'resume : '.$favrow['finishtrackpause'].'<br />';
 
// if (($favrow['starttrackpause'])<>'0000-00-00 00:00:00') {  echo ' found start pause '; }
// if (($favrow['finishtrackpause'])<>'0000-00-00 00:00:00') {  echo ' found resume '; }
 
 
 
 if (($timetocheck>$favrow['starttrackpause']) and ($timetocheck<$favrow['finishtrackpause'])) { 
 
// echo ' DO NOT INCLUDE ';
 
 } else { 
 
 // echo ' include '.$favrow['ID'];  
 
 
 echo '<a href="order.php?id='.$favrow['ID'].'" title="" >'.$favrow['ID'].'</a>';
 echo '<br />';
 
 }
 
 
//echo '<option value="'.$favrow['favadrid'].'">'.$favrow['favadrft'].' '.$favrow['favadrpc'].'</option>';

 } // ends extract row
