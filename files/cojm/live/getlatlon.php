<?php 

/*
    COJM Courier Online Operations Management
	getlatlon.php - 
    Copyright (C) 2017 S.Young cojm.co.uk

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
WHERE  `PZ_Postcode` =  ?
LIMIT 0,1"; 


$statement = $dbh->prepare($query);
$statement->execute([$pc1]);
$pcrow = $statement->fetch(PDO::FETCH_ASSOC);

echo '<br /> <p> [ '.$pcrow["PZ_northing"].','.$pcrow["PZ_easting"].'], </p>';

?>
</body>