<?php

/*
    COJM Courier Online Operations Management
	ordersearch.php - included in individual job screen ( order.php ), if a cojm ID is not found from a search in top menu
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

$found='';

// jobcomments
$sql="SELECT * FROM Orders
WHERE Orders.jobcomments LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";

$sql_result = mysql_query($sql,$conn_id);
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' in comments </h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['jobcomments'].') ( &'. $globalprefrow["currencysymbol"] .$row['FreightCharge'].' )</p>'; }
echo '</div></div><br /><div class="line"></div><br />';
$found='1';
} // ends rum_rows loop




// privatejobcomments
$sql="SELECT * FROM Orders
WHERE Orders.privatejobcomments LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";

$sql_result = mysql_query($sql,$conn_id);
$num_rows = mysql_num_rows($sql_result);

if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' in private comments </h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['privatejobcomments'].') </p>'; }
echo '</div></div><br /><div class="line"></div><br />';
$found='1';
} // ends rum_rows loop









// client ref
$sql="SELECT * FROM Orders
WHERE Orders.clientjobreference LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$sql_result = mysql_query($sql,$conn_id);
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' as client reference </h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['clientjobreference'].') </p>'; }
echo '</div></div><br /><div class="line"></div><br />';
$found='1';
} // ends rum_rows loop






// fromfreeaddress
$sql="SELECT * FROM Orders
WHERE Orders.enrft0 LIKE '%".$searchid."%' 
OR Orders.enrpc0 LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$sql_result = mysql_query($sql,$conn_id);
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' as collection address </h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['enrft0'].' , '.$row['enrpc0'].') </p>'; }
echo '</div></div><br /><div class="line"></div><br />';
$found='1';
} // ends rum_rows loop










// enrft21
$sql="SELECT * FROM Orders
WHERE Orders.enrft21 LIKE '%".$searchid."%' 
OR Orders.enrpc21 LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$sql_result = mysql_query($sql,$conn_id);
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' as delivery address</h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['enrft21'].' , '.$row['enrpc21'].') </p>'; }
echo '</div></div><br /><div class="line"></div><br />';
$found='1';
} // ends rum_rows loop


// requestor
$sql="SELECT * FROM Orders
WHERE Orders.requestor LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$sql_result = mysql_query($sql,$conn_id);
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' as requesting service.</h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['requestor'].') </p>'; }
echo '</div></div><br /><div class="line"></div><br />';
$found='1';
} // ends rum_rows loop










// invoice ref
$sql="SELECT * FROM Orders
WHERE Orders.invoiceref LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$sql_result = mysql_query($sql,$conn_id);
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' as invoice reference.</h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['requestor'].') </p>'; }
echo '</div></div><br /><div class="line"></div><br />';
$found='1';
} // ends rum_rows loop






// expense descrip
$sql="SELECT * FROM expenses
WHERE expenses.description LIKE '%".$searchid."%' 
LIMIT 0,50";
$sql_result = mysql_query($sql,$conn_id);
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' in expense description.</h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="singleexpense.php?expenseref='.$row['expenseref'].'">'.$row['expenseref'].'</a> 

'.$row['description'].'

 </p>'; }
echo '</div></div><br /><div class="line"></div><br />';
$found='1';
} // ends rum_rows loop
























 if ($found=='') {
echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0.5em;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				<strong>Sorry,</strong> Unable to find any other references for '.$ID.'.</p></div></div>';
} // no search term found


?>