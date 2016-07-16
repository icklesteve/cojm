<?php


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










// $arow = $astmt->fetch(PDO::FETCH_ASSOC);
// $atotal = $astmt->rowCount();




   try {
$statement = $dbh->prepare("INSERT INTO cojm_selfstats 
(time, totorders, totinstamapper, totpostcodes, totaudit) 
values 
(now(), :totorders, :instamapperresult, :postcoderesult, :auditresult)");

$statement->bindParam(':totorders', $result, PDO::PARAM_STR);
$statement->bindParam(':instamapperresult', $instamapperresult, PDO::PARAM_STR);
$statement->bindParam(':postcoderesult', $postcoderesult, PDO::PARAM_STR);
$statement->bindParam(':auditresult', $auditresult, PDO::PARAM_STR);

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






if (($error>0)or($globalprefrow['adminlogoback']>0)){




 $to=$globalprefrow['glob8'];
 $from=$globalprefrow['emailfrom'];
$headers = 'From: '.$from. PHP_EOL;
// $headers =$headers. 'Return-path: '.$to. PHP_EOL; 
// $headers = $headers . 'Repy-To: '.$to . PHP_EOL;
 $headers.= "X-Mailer: COJM-Courier-Online-Job-Management" . PHP_EOL;
// $headers = $headers .		   "Cc: ".$globalprefrow['glob8'];

 $from=$globalprefrow['emailfrom'];


 $subject='Databse Monitor '.$globalprefrow['globalname'];
 
 if ($globalprefrow['adminlogoback']>0) { $subject.=' Debug Mode'; } else { $subject='COJM ERROR '.$subject; }
 
 
 
 
 $message = str_replace('&nbsp;',' ',($msg.$infotext));
 $message = wordwrap(($message), 70);
 
 
 mail($to, $subject, $message, $headers, "-f$from");


}


$infotext.=$msg;







?>