<?php

/*
    COJM Courier Online Operations Management
	ajax_expense_lookup.php - Handles Ajax Requests made from singleexpense.php
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

if (isset($_POST['expenseid'])) {  
    include "C4uconnect.php";
    if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  exit(); } }

    $expenseid=(trim($_POST['expenseid']));

    try {
        $query= ' SELECT *
        FROM expenses
        INNER JOIN Cyclist, expensecodes
        WHERE expenses.cyclistref = Cyclist.CyclistID
        AND expenses.expensecode = expensecodes.expensecode
        AND expenseref = :expenseid ';
        
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':expenseid', $expenseid, PDO::PARAM_INT);
        $stmt->execute();
        $total = $stmt->rowCount();
        // echo $expenseid.' '.$total;
        
        if ($total>0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                if ($row['paymentdate']>'10') { $paymentdate= date('d-m-Y', strtotime($row['paymentdate'])); }
                
                if ($row['expc1']>0) { $expmethod='expc1'; }
                if ($row['expc2']>0) { $expmethod='expc2'; }
                if ($row['expc3']>0) { $expmethod='expc3'; }
                if ($row['expc4']>0) { $expmethod='expc4'; }
                if ($row['expc5']>0) { $expmethod='expc5'; }
                if ($row['expc6']>0) { $expmethod='expc6'; }
                $displaydate='';

                if (date('U', strtotime($row['expensedate']))>20) {
                    $displaydate=date('d-m-Y', strtotime($row['expensedate']));
                }
                 
            echo '<script>';
            
            if ($row['isactive']<>1) {
                        
                echo " $('#cyclistref').append($('<option>', {
                    value: ".$row['cyclistref'].",
                    text: '".$row['cojmname']." Inactive'
                })); ";
            }
            
            echo '
            allok=1;
            message=" Expense '.$row['expenseref'].' Located ";
            
            $("#amount").val("'.$row['expensecost'].'");
            $("expenseid").val("'.$row['expenseref'].'");
            $("#expensevat").val("'.$row['expensevat'].'");
            $("select#expensecode").val("'.$row['expensecode'].'");
            $("#expensedescription").html("'.$row['expensedescription'].'");
            $("#whoto").val("'.$row['whoto'].'");              
            $("select#cyclistref").val("'.$row['cyclistref'].'");
            $("#expensedate").val("'. $displaydate.'");  
            $("select#paid").val("'.$row['paid'].'");
            $("select#paymentmethod").val("'.$expmethod.'");
            $("#chequeref").val("'.$row['chequeref'].'");
            var str = "'.(trim($row['description'])).'";
            var regex = /<br\s*[\/]?>/gi;
            $("#expensecomment").val(str.replace(regex, "\n"));
            $("#expensedetails").removeClass("hideuntilneeded");
            $("#expensecomment").trigger("autosize.resize");';
            
            if ($row['expensecode']=='6') {
                echo ' $("#riderselect").removeClass("hideuntilneeded"); ';
            } else {
                echo ' $("#riderselect").addClass("hideuntilneeded"); ';
            }
            
            echo ' </script>';
            }
        } else {
            echo '<script>
            $("#amount").val("");
            $("#expensevat").val("");
            $("select#expensecode").val("0");
            $("#whoto").val(""); 
            $("select#cyclistref").val("");
            $("#expensedate").val("");
            $("select#paid").val("0");
            $("#chequeref").val("");
            $("select#paymentmethod").val("");
            $("#expensecomment").val("");
            $("#expensedescription").val("");
            $("#editexpense").addClass("hideuntilneeded");
            $("#expensedetails").addClass("hideuntilneeded");
            $("#riderselect").addClass("hideuntilneeded");
            allok=0;
            message=" No Expense Located ";
            </script>';
        }
    }
    
    catch(PDOException $e) {
        echo $e->getMessage();
    }
$dbh=null;
}