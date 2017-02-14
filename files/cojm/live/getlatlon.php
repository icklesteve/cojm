<?php 
$alpha_time = microtime(TRUE);
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
error_reporting( E_ERROR | E_WARNING | E_PARSE );

?><!doctype html>
<html lang="en"><head><meta charset='utf-8'/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >

<?php
echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >';

$temppc=$_POST['temppc'];

?>

</head>
<body>

<form action="#" method="post">
<input name ="temppc" type="text"><?php echo $temppc; ?></input>

</form>


<?php  
	 
$pc1 = str_replace (" ", "", $temppc);
$query="SELECT * 
FROM  `postcodeuk` 
WHERE  `PZ_Postcode` =  '$pc1'
LIMIT 0,1"; 
$result=mysql_query($query, $conn_id); 
$pcrow=mysql_fetch_array($result); 

echo '<br />';

 echo '<p> [ '.$pcrow["PZ_northing"].','.$pcrow["PZ_easting"].'], </p>';

// echo $query;

mysql_close(); 
?>
</body>