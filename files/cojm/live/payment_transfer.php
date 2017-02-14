<?php

/*
    COJM Courier Online Operations Management
	ajax_payment_lookup.php - Handles Ajax Requests made from gpstracking.php and returns list of jobs en-route at time of tracking
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


    include "C4uconnect.php";
    if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  exit(); } }

    $paymentid=(trim($_POST['paymentid']));

    $i=0;
    $j=0;
        
        $query= ' SELECT * FROM invoicing WHERE paydate <> "0000-00-00 00:00:00" ';
    try {        
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':paymentid', $paymentid, PDO::PARAM_INT);
        $stmt->execute();
        $total = $stmt->rowCount();
        echo $total;
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                
                    $temp_ar=explode(" ",$row['paydate']); 
                    $start=$temp_ar[0];

                echo ' found '.
                ' client: '.$row['client'].
                ' cost: '.$row['cost'].
                ' cash: '. $row['cash'] .
                ' cheque: '.$row['cheque'].
                ' bacs: '.$row['bacs'].
                ' paypal: '.$row['paypal'].
                ' paydate: '.$start;

                
                
                if ($row['cash']<>0) {  $type=1; }                
                if ($row['cheque']<>0) {  $type=2; }
                if ($row['bacs']<>0) {  $type=3; }
                if ($row['paypal']<>0) {  $type=4; }                

                
                
                echo ' type: '.$type. 
                ' <br /> ';                
                

                $paymentcomment='Transfer from old system ';
                
                    try {
                        $querya = "INSERT INTO cojm_payments
                        SET paymentdate=:paymentdate,
                        paymentamount=:paymentamount,
                        paymentclient=:paymentclient,
                        paymenttype=:paymenttype,
                        paymentcomment=:paymentcomment        ";
                        $stmta = $dbh->prepare($querya);
                        $stmta->bindParam(':paymentdate', $start, PDO::PARAM_INT); 
                        $stmta->bindParam(':paymentamount', $row['cost'], PDO::PARAM_INT); 
                        $stmta->bindParam(':paymentclient', $row['client'], PDO::PARAM_INT);
                        $stmta->bindParam(':paymenttype', $type, PDO::PARAM_INT);
                        $stmta->bindParam(':paymentcomment', $paymentcomment, PDO::PARAM_INT);        
        
                        $stmta->execute();

                        $total = $stmta->rowCount();
                        $infotext.=$total.' row updated, ';
                        if ($total=='1') {
                           
                            $i++;
                        }
                    }
                    catch(PDOException $e) { echo $e->getMessage(); }
                }
            }
        catch(PDOException $e) {
        echo $e->getMessage();
        }
    
echo $i .' records transferred ';    
    
$dbh=null;