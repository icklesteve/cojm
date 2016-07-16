<?php

if (isset($conn_id)) { 
// echo " found conn id ";
} else {  
include "live/C4uconnect.php";
// echo " not found "; 
}



$cojmsql="
SELECT CO2Saved, co2saving, PM10Saved, pm10saving, numberitems FROM Orders, Services WHERE Orders.ServiceID = Services.ServiceID AND Orders.status >= 77 AND Orders.numberitems>0";
$cojmsql_result = mysql_query($cojmsql,$conn_id);
// $today = date(" H:i A, D j M");
while ($cojmrow = mysql_fetch_array($cojmsql_result)) {
     extract($cojmrow);
	 $newSqlString = date('Y', strtotime($cojmrow['ShipDate'])); 
	 if ($cojmrow['co2saving']>000.1) {   
	 $cojmtableco2 = $cojmtableco2 + $cojmrow["co2saving"];
	 } else { 
	 $cojmtableco2 = $cojmtableco2 + (($cojmrow['numberitems'])*($cojmrow["CO2Saved"]));
	  }
	  	  
		  	 if ($cojmrow['pm10saving']>0.001) {
		   $cojmtablepm10 = $cojmtablepm10 + $cojmrow['pm10saving'];  }
	 
	
	 else {
	  $cojmtablepm10 = $cojmtablepm10 + (($cojmrow['numberitems'])*($cojmrow["PM10Saved"]));  }
	 
	
	
	 }



 // totals under here
 
 
  if ($cojmtablepm10>1000) {
$cojmtablepm10=($cojmtablepm10/1000);
$cojmtablepm10 = number_format($cojmtablepm10, 1, '.', ',');
$cojmtablepm10= $cojmtablepm10.' Kg '; }
 else {
 if ($cojmtablepm10>1) { $cojmtablepm10=$cojmtablepm10.' grams'; 
}} 
 
 
 
 
 $compco2=$cojmtableco2;
 
 
  if ($cojmtableco2>1000) {
$cojmtableco2=($cojmtableco2/1000);
$cojmtableco2 = number_format($cojmtableco2, 0, '.', ',');
$cojmtableco2= $cojmtableco2.' Kg '; }
 else {
 if ($cojmtableco2>1) { $cojmtableco2=$cojmtableco2.' grams'; 
}}


echo ' We have saved Birmingham <strong>'.$cojmtableco2.' CO<sub>2</sub></strong>, 
along with '.$cojmtablepm10.'  of PM<sub>10</sub> (exhaust) emissions (compared with really small vans). ';



echo '<hr/>Equivalent to ';


// $r=mysql_query("SELECT count(*) FROM emissionscomparison WHERE co2>0");
// $d=mysql_fetch_row($r);


// echo $d[0];


// $rand= mt_rand(0,$d[0] -1);
// $rand++;

// echo $rand;


// SELECT * FROM table ORDER BY RAND() LIMIT 10;


$cojmcompsql="SELECT * FROM emissionscomparison WHERE co2>0 ORDER BY RAND() LIMIT 0, 1"; 
$cojmcompsql_result = mysql_query($cojmcompsql,$conn_id);

// echo $cojmcompsql;

while ($cojmcompsqlrow = mysql_fetch_array($cojmcompsql_result)) { extract($cojmcompsqlrow);

echo  number_format(($compco2 / $cojmcompsqlrow['co2']), 0, '.', ',');	 

echo ' <a href="'.$cojmcompsqlrow['link'].'" title="CO2 Comparison" target="_blank">'.$cojmcompsqlrow['description'].'</a>.';

}

// echo '<hr/><small><a href="http://www.cojm.co.uk/" title="COJM Courier Software" target="_blank">Powered by COJM</a></small>';


 
?>