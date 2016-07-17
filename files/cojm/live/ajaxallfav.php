<?php

include "C4uconnect.php";

$sql="SELECT favadrid, favadrft, favadrpc FROM cojm_favadr WHERE favadrisactive ='1' GROUP BY favadrft, favadrpc ";

$sql_result = mysql_query($sql,$conn_id);

// tell the browser what's coming
header('Content-type: application/json');
 
$string= ' [ ';
 
while ($favrow = mysql_fetch_array($sql_result)) { extract($favrow);
	 
	 if ((trim($favrow['favadrft'])) or (trim($favrow['favadrpc']))) {
	 
$string= $string. ' {"oV":"'. $favrow['favadrid'].'","oD":"'. $favrow['favadrft'].', '. $favrow['favadrpc'].'"},';
	} }

 echo rtrim($string,',') . ']';

?>