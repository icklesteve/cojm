<?php

/*
    COJM Courier Online Operations Management
	cojm-12-hr-stats.php - called by cron to generate stats / do database checks
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

if (isSet($infotext)) {} else {
	$infotext='';
	}


$infotext.= ' <br /> In cojm-12-hr-stats.php ln 2 <br />';

$error=0;
$msg='';

$checkloop='0';
$ridergpsdayloopa='-1';
$ridergpsdayloopb='0';

$sql = "SELECT COUNT(*) FROM Orders";
$astmt = $dbh->prepare($sql);
$astmt->execute();
$result = $astmt->fetchColumn();
$infotext.='<br/>'.$result.' Jobs in DB';
$msg=$result." Jobs in DB \r\n";


$sql = "SELECT COUNT(*) FROM instamapper";
$astmt = $dbh->prepare($sql);
$astmt->execute();
$instamapperresult = $astmt->fetchColumn();
$infotext.='<br/>'.$instamapperresult.' GPS plots in DB';
$msg.=''.$instamapperresult." GPS plots in DB \r\n";


$sql = "SELECT COUNT(*) FROM postcodeuk";
$astmt = $dbh->prepare($sql);
$astmt->execute();
$postcoderesult = $astmt->fetchColumn();
$infotext.='<br/>'.$postcoderesult.' Postcodes in DB';
$msg.=''.$postcoderesult." Postcodes in DB \r\n";


$sql = "SELECT COUNT(*) FROM cojm_audit";
$astmt = $dbh->prepare($sql);
$astmt->execute();
$auditresult = $astmt->fetchColumn();
$infotext.='<br/>'.$auditresult.' Audit Logs in DB';
$msg.=''.$auditresult." Audit Logs in DB \r\n";




$cojmtableco2=0;
$cojmtablepm10=0;

$sql = "
SELECT 
CO2Saved, 
co2saving, 
PM10Saved, 
pm10saving, 
numberitems 
FROM Orders, Services WHERE Orders.ServiceID = Services.ServiceID AND Orders.status >= 77 AND Orders.numberitems>0";
$astmt = $dbh->prepare($sql);
$astmt->execute();

$rows = $astmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $cojmrow) {

if ($cojmrow['co2saving']>0.01) {   
	$cojmtableco2 = $cojmtableco2 + $cojmrow["co2saving"];
	 } else { 
	 $cojmtableco2 = $cojmtableco2 + (($cojmrow['numberitems'])*($cojmrow["CO2Saved"]));
	  }
	  	  
if ($cojmrow['pm10saving']>0.001) {
	$cojmtablepm10 = $cojmtablepm10 + $cojmrow['pm10saving'];  
	} else {
	 $cojmtablepm10 = $cojmtablepm10 + (($cojmrow['numberitems'])*($cojmrow["PM10Saved"]));  
	 }
	
} // ends row loop


   try {
$statement = $dbh->prepare("INSERT INTO cojm_selfstats 
(time, totorders, totinstamapper, totpostcodes, totaudit, totco2, totpm10) 
values 
(now(), :totorders, :instamapperresult, :postcoderesult, :auditresult, :totco2, :totpm10)");

$statement->bindParam(':totorders', $result, PDO::PARAM_STR);
$statement->bindParam(':instamapperresult', $instamapperresult, PDO::PARAM_STR);
$statement->bindParam(':postcoderesult', $postcoderesult, PDO::PARAM_STR);
$statement->bindParam(':auditresult', $auditresult, PDO::PARAM_STR);
$statement->bindParam(':totco2', $cojmtableco2, PDO::PARAM_STR);
$statement->bindParam(':totpm10', $cojmtablepm10, PDO::PARAM_STR);
$statement->execute();
$insertid = $dbh->lastInsertId();


$infotext.="<br />Stats Added, id ".$insertid;
$msg.="Stats Added, id ".$insertid."\n";


} // ends try
 
catch(PDOException $e) { 
$infotext.=' <br /> '. $e->getMessage(); 
$error++;
}





$sql="SELECT 
g1.id,
g1.totorders old,
g2.totorders new,
g1.time from_date,
g2.time to_date,
(g2.totorders - g1.totorders ) AS diff
FROM cojm_selfstats g1
INNER JOIN
cojm_selfstats g2 on g2.id=g1.id + 1
WHERE
g1.time >= date_sub(now(), interval 24 hour) AND
g2.totorders<g1.totorders";

$astmt = $dbh->prepare($sql);
$astmt->execute();
$result = $astmt -> fetchAll();

foreach( $result as $row ) {
$msg.='Order Tot Lowered between '.$row['from_date'].' and '.$row['to_date'].' , old '.$row['old'].' new '.$row['new'].' diff '. $row['diff']."\n";

$error++;

// audit log

}






$sql="SELECT 
g1.id,
g1.totinstamapper old,
g2.totinstamapper new,
g1.time from_date,
g2.time to_date,
(g2.totinstamapper - g1.totinstamapper ) AS diff
FROM cojm_selfstats g1
INNER JOIN
cojm_selfstats g2 on g2.id=g1.id + 1
WHERE
g1.time >= date_sub(now(), interval 24 hour) AND
g2.totinstamapper<g1.totinstamapper";

$astmt = $dbh->prepare($sql);
$astmt->execute();
$result = $astmt -> fetchAll();




foreach( $result as $row ) {

$msg.='GPS Tracks went down between '.$row['from_date'].' and '.$row['to_date'].' , old '.$row['old'].' new '.$row['new'].' diff '. $row['diff']."\r\n";

// audit log

$error++;

}



$msg.="\r\n\r\n";






if (($error>5)or($globalprefrow['showdebug']>0)){




 $to=$globalprefrow['glob8'];
 $from=$globalprefrow['emailfrom'];
$headers = 'From: '.$from. PHP_EOL;
 $headers.= "X-Mailer: COJM-Courier-Online-Job-Management" . PHP_EOL;
$from=$globalprefrow['emailfrom'];
$subject='Databse Monitor '.$globalprefrow['globalname'];
 
 if ($globalprefrow['showdebug']>0) { $subject.=' Debug Mode'; } else { $subject='COJM ERROR '.$subject; }
 
 $message = str_replace('&nbsp;',' ',($msg.$infotext));
 $message = wordwrap(($message), 70);
  
 mail($to, $subject, $message, $headers, "-f$from");

}

$infotext.=$msg;
?>