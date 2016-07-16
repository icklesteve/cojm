<?php

if(isset($_GET['id'])) {

include "live/C4uconnect.php";
$id = $_GET['id'];

$query = "SELECT * FROM cojm_pod WHERE id = :getid LIMIT 0,1";

$stmt = $dbh->prepare($query);
$stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
$stmt->execute();

$total = $stmt->rowCount();

if ($total=='1') {
$obj = $stmt->fetchObject();


$sLastModified = date('D, d M Y H:i:s', strtotime($obj->time)) . ' GMT';
$size=$obj->size;
$type=$obj->type;  
$name=$obj->name;

header("Content-length: $size");
header("Content-type: $type");
header("Content-Disposition: inline; filename=$name");
header('Cache-Control: private');
header("Last-Modified: $sLastModified");
header("Expires: ".gmdate("D, d M Y H:i:s", time()+99991800)." GMT");
header("Cache-Control: max-age=9991800, cache, no-transform");
header("pragma: cache");


echo $obj->content;

}

else { echo 'No Image found'; }

}
$dbh=null;
exit;

?>