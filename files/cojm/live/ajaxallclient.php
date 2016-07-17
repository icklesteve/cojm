<?php

include "C4uconnect.php";

$sql="SELECT CustomerID, CompanyName FROM Clients order by CompanyName asc";

$sql_result = mysql_query($sql,$conn_id);

// tell the browser what's coming
header('Content-type: application/json');
 
$string= ' [ ';
 
while ($favrow = mysql_fetch_array($sql_result)) { extract($favrow);
	 
	
	 
$string= $string. ' {"oV":"'. $favrow['CustomerID'].'","oD":"'. $favrow['CompanyName'].'"},';
	 }

 echo rtrim($string,',') . ']';

?>