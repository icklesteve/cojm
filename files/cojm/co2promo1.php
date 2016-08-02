<?php

/*
    COJM Courier Online Operations Management
	co2promo1.php - displays co2 & pm10 saved
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

if (isset($conn_id)) {  /* db connection already set */   } else {  
include "live/C4uconnect.php";
}

$sql = "SELECT totco2, totpm10, time FROM cojm_selfstats ORDER BY id desc LIMIT 0,1";
$astmt = $dbh->prepare($sql);
$astmt->execute();
$obj = $astmt->fetchObject();

$cojmtableco2=$obj->totco2;
$cojmtablepm10=$obj->totpm10;
$lastupdated=$obj->time;
	 
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

echo '<span title="Last Updated '.date('H:i D jS M Y', strtotime($lastupdated)).'"> We have saved '.$globalprefrow['myaddress4'].' <strong>'.$cojmtableco2.' CO<sub>2</sub></strong>, 
along with '.$cojmtablepm10.'  of PM<sub>10</sub> (exhaust) emissions (compared with small vans).</span> ';
echo '<hr/>Equivalent to ';

$sql="SELECT * FROM emissionscomparison WHERE co2>0 ORDER BY RAND() LIMIT 0, 1"; 
$astmt = $dbh->prepare($sql);
$astmt->execute();
$rows = $astmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $cojmcompsqlrow) {

echo  number_format(($compco2 / $cojmcompsqlrow['co2']), 0, '.', ',');	 
echo ' <a href="'.$cojmcompsqlrow['link'].'" title="CO2 Comparison" target="_blank">'.$cojmcompsqlrow['description'].'</a>.';

}

?>