<?php

/*
    COJM Courier Online Operations Management
	ordersearch.php - included in individual job screen ( order.php ), if a cojm ID is not found from a search in top menu
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

$found='';

$pdosearch= "%$searchid%";


// jobcomments
$sql="SELECT ID, jobcomments, FreightCharge FROM Orders
WHERE Orders.jobcomments LIKE ?
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$prep = $dbh->prepare($sql);
$prep->execute([$pdosearch]);
$stmt = $prep->fetchAll();
if ($stmt) {
    $num_rows=0;
    $txt='';
    foreach($stmt as $row) {
        $txt.= '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['jobcomments'].') ( &'. $globalprefrow["currencysymbol"] .$row['FreightCharge'].' )</p>';
        $num_rows++;
    }
    echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
    if ($num_rows>49) { echo 'At least '; }
    echo $num_rows.' Job';
    if ($num_rows<>1) { echo 's'; }
    echo ' found with '.$searchid.' in comments </h3>'.$txt;
    echo '</div></div><br /><div class="line"></div><br />';
    $found='1';
}




// privatejobcomments
$sql="SELECT ID, privatejobcomments, FreightCharge FROM Orders
WHERE Orders.privatejobcomments LIKE ?
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$prep = $dbh->prepare($sql);
$prep->execute([$pdosearch]);
$stmt = $prep->fetchAll();
if ($stmt) {
    $num_rows=0;
    $txt='';
    foreach($stmt as $row) {
        $txt.= '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['privatejobcomments'].') ( &'. $globalprefrow["currencysymbol"] .$row['FreightCharge'].' )</p>';
        $num_rows++;
    }
    echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
    if ($num_rows>49) { echo 'At least '; }
    echo $num_rows.' Job';
    if ($num_rows<>1) { echo 's'; }
    echo ' found with '.$searchid.' in Private Job Comments </h3>'.$txt;
    echo '</div></div><br /><div class="line"></div><br />';
    $found='1';
}









// clientjobreference
$sql="SELECT ID, clientjobreference, FreightCharge FROM Orders
WHERE Orders.clientjobreference LIKE ?
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";
$prep = $dbh->prepare($sql);
$prep->execute([$pdosearch]);
$stmt = $prep->fetchAll();
if ($stmt) {
    $num_rows=0;
    $txt='';
    foreach($stmt as $row) {
        $txt.= '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['clientjobreference'].') ( &'. $globalprefrow["currencysymbol"] .$row['FreightCharge'].' )</p>';
        $num_rows++;
    }
    echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
    if ($num_rows>49) { echo 'At least '; }
    echo $num_rows.' Job';
    if ($num_rows<>1) { echo 's'; }
    echo ' found with '.$searchid.' in Client Reference </h3>'.$txt;
    echo '</div></div><br /><div class="line"></div><br />';
    $found='1';
}






// fromfreeaddress
$sql="SELECT * FROM Orders
WHERE Orders.enrft0 LIKE ?
OR Orders.enrpc0 LIKE ?
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";

$prep = $dbh->prepare($sql);
$prep->execute([$pdosearch,$pdosearch]);
$stmt = $prep->fetchAll();
if ($stmt) {
    $num_rows=0;
    $txt='';
    foreach($stmt as $row) {
        $txt.= '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['enrft0'].' , '.$row['enrpc0'].') ( &'. $globalprefrow["currencysymbol"] .$row['FreightCharge'].' )</p>';
        $num_rows++;
    }
    echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
    if ($num_rows>49) { echo 'At least '; }
    echo $num_rows.' Job';
    if ($num_rows<>1) { echo 's'; }
    echo ' found with '.$searchid.' in Collection Address </h3>'.$txt;
    echo '</div></div><br /><div class="line"></div><br />';
    $found='1';
}







// enrft21
$sql="SELECT * FROM Orders
WHERE Orders.enrft21 LIKE ?
OR Orders.enrpc21 LIKE ?
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";

$prep = $dbh->prepare($sql);
$prep->execute([$pdosearch,$pdosearch]);
$stmt = $prep->fetchAll();
if ($stmt) {
    $num_rows=0;
    $txt='';
    foreach($stmt as $row) {
        $txt.= '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['enrft21'].' , '.$row['enrpc21'].') ( &'. $globalprefrow["currencysymbol"] .$row['FreightCharge'].' )</p>';
        $num_rows++;
    }
    echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
    if ($num_rows>49) { echo 'At least '; }
    echo $num_rows.' Job';
    if ($num_rows<>1) { echo 's'; }
    echo ' found with '.$searchid.' in Delivery Address </h3>'.$txt;
    echo '</div></div><br /><div class="line"></div><br />';
    $found='1';
}


// requestor
$sql="SELECT * FROM Orders
WHERE Orders.requestor LIKE ?
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";


$prep = $dbh->prepare($sql);
$prep->execute([$pdosearch]);
$stmt = $prep->fetchAll();
if ($stmt) {
    $num_rows=0;
    $txt='';
    foreach($stmt as $row) {
        $txt.= '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['requestor'].') ( &'. $globalprefrow["currencysymbol"] .$row['FreightCharge'].' )</p>';
        $num_rows++;
    }
    echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
    if ($num_rows>49) { echo 'At least '; }
    echo $num_rows.' Job';
    if ($num_rows<>1) { echo 's'; }
    echo ' found with '.$searchid.' as requesting service </h3>'.$txt;
    echo '</div></div><br /><div class="line"></div><br />';
    $found='1';
}















// invoice ref
$sql="SELECT * FROM Orders
WHERE Orders.invoiceref LIKE '%".$searchid."%' 
ORDER BY `Orders`.`nextactiondate` DESC
LIMIT 0,50";


$prep = $dbh->prepare($sql);
$prep->execute([$pdosearch]);
$stmt = $prep->fetchAll();
if ($stmt) {
    $num_rows=0;
    $txt='';
    foreach($stmt as $row) {
        $txt.= '<p><a href="order.php?id='.$row['ID'].'">'.$row['ID'].'</a> ('.$row['invoiceref'].') ( &'. $globalprefrow["currencysymbol"] .$row['FreightCharge'].' )</p>';
        $num_rows++;
    }
    echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
    if ($num_rows>49) { echo 'At least '; }
    echo $num_rows.' Job';
    if ($num_rows<>1) { echo 's'; }
    echo ' found with '.$searchid.' as Invoice Reference </h3>'.$txt;
    echo '</div></div><br /><div class="line"></div><br />';
    $found='1';
}







// expenses.description
$sql="SELECT * FROM expenses
WHERE expenses.description LIKE ?
LIMIT 0,50";

$prep = $dbh->prepare($sql);
$prep->execute([$pdosearch]);
$stmt = $prep->fetchAll();
if ($stmt) {
    $num_rows=0;
    $txt='';
    foreach($stmt as $row) {
        $txt.= '<p><a href="singleexpense.php?expenseref='.$row['expenseref'].'">'.$row['expenseref'].'</a> '.$row['description'].'</p>';
        $num_rows++;
    }
    echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
    if ($num_rows>49) { echo 'At least '; }
    echo $num_rows.' Job';
    if ($num_rows<>1) { echo 's'; }
    echo ' found with '.$searchid.' in Expense Description </h3>'.$txt;
    echo '</div></div><br /><div class="line"></div><br />';
    $found='1';
}






// expenses.description
$sql="SELECT * FROM expenses
WHERE expenses.whoto LIKE ?
LIMIT 0,50";

$prep = $dbh->prepare($sql);
$prep->execute([$pdosearch]);
$stmt = $prep->fetchAll();
if ($stmt) {
    $num_rows=0;
    $txt='';
    foreach($stmt as $row) {
        $txt.= '<p><a href="singleexpense.php?expenseref='.$row['expenseref'].'">'.$row['expenseref'].'</a> '.$row['whoto'].'</p>';
        $num_rows++;
    }
    echo '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;"><h3>';
    if ($num_rows>49) { echo 'At least '; }
    echo $num_rows.' Job';
    if ($num_rows<>1) { echo 's'; }
    echo ' found with '.$searchid.' in Expense Payee </h3>'.$txt;
    echo '</div></div><br /><div class="line"></div><br />';
    $found='1';
}









 if ($found=='') {
echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0.5em;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				<strong>Sorry,</strong> Unable to find any other references for '.$ID.'.</p></div></div>';
} // no search term found


?>