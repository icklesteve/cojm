<?php

/*
    COJM Courier Online Operations Management
	podimage.php - outputs the .jpg file uploaded as proof of delivery
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