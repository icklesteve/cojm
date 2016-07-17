<?php

$found='';

// jobcomments
$sql="SELECT * FROM Orders
WHERE Orders.jobcomments LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";

$sql_result = mysql_query($sql,$conn_id)  or mysql_error();
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

$sql_result = mysql_query($sql,$conn_id)  or mysql_error();
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
$sql_result = mysql_query($sql,$conn_id)  or mysql_error();
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
WHERE Orders.fromfreeaddress LIKE '%".$searchid."%' 
OR Orders.CollectPC LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error();
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' as collection address </h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['fromfreeaddress'].' , '.$row['CollectPC'].') </p>'; }
echo '</div></div><br /><div class="line"></div><br />';
$found='1';
} // ends rum_rows loop










// tofreeaddress
$sql="SELECT * FROM Orders
WHERE Orders.tofreeaddress LIKE '%".$searchid."%' 
OR Orders.ShipPC LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error();
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' as delivery address</h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['tofreeaddress'].' , '.$row['ShipPC'].') </p>'; }
echo '</div></div><br /><div class="line"></div><br />';
$found='1';
} // ends rum_rows loop


// requestor
$sql="SELECT * FROM Orders
WHERE Orders.requestor LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error();
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
$sql_result = mysql_query($sql,$conn_id)  or mysql_error();
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
$sql_result = mysql_query($sql,$conn_id)  or mysql_error();
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>'0') {
 echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
 if ($num_rows>'49') { echo 'At least '; } echo $num_rows.' Job';
 if ($num_rows<>'1') { echo 's'; } echo ' found with '.$searchid.' in expense description.</h3>';
 while ($row = mysql_fetch_array($sql_result)) { extract($row);
 echo '<p><a href="expenses.php?page=selectexpense&expenseref='.$row['expenseref'].'">'.$row['expenseref'].'</a> 

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