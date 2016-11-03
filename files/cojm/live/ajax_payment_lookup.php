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

if (isset($_POST['paymentid'])) {  
    include "C4uconnect.php";
    if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  exit(); } }

    $paymentid=(trim($_POST['paymentid']));

    try {
        
        $query= ' SELECT paymentdate, paymentamount, paymentclient, paymenttype, paymentcomment, CompanyName
        FROM cojm_payments
        INNER JOIN Clients 
        WHERE cojm_payments.paymentclient = Clients.CustomerID 
        AND paymentid = :paymentid ';
        
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':paymentid', $paymentid, PDO::PARAM_INT);
        $stmt->execute();
        $total = $stmt->rowCount();
        // echo $paymentid.' '.$total;
        
        if ($total>0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                if ($row['paymentdate']>'10') { $paymentdate= date('d-m-Y', strtotime($row['paymentdate'])); }
                
                
            echo '<script>
            allok=1;
            message=" Payment Located ";
            
            $("#amountpaid").val("'.$row['paymentamount'].'");
            $("#paymentdate").val("'.$paymentdate.'");  
            $("select#paymentmethod").val("'.$row['paymenttype'].'");
            $("#combobox").val("'.$row['paymentclient'].'");  
            $("#combobox").combobox("autocomplete", "'.$row['paymentclient'].'","'.$row['CompanyName'].'"); 

            
            var str = "'.(trim($row['paymentcomment'])).'";
            var regex = /<br\s*[\/]?>/gi;
            $("#paymentcomment").val(str.replace(regex, "\n"));
            $("#addnewpayment").addClass("hideuntilneeded");
            $("#editpayment").removeClass("hideuntilneeded");
            </script>';
            }
        } else {
            echo '<script>
            
            $("#amountpaid").val("");
            $("#paymentdate").val(""); 
            $("select#paymentmethod").val("");
            $("select#combobox").val("");
            $("#paymentcomment").val("");
            $("#addnewpayment").removeClass("hideuntilneeded");
            $("#editpayment").addClass("hideuntilneeded");
            $("#combobox").combobox("autocomplete", "","");
            allok=0;
            message=" No Payment Located ";
            </script>';
        }
    }
    
    catch(PDOException $e) {
        echo $e->getMessage();
    }
$dbh=null;
}