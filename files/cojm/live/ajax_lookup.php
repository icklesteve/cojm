<?php

/*
    COJM Courier Online Operations Management
	ajax_lookup.php - Anything that needs looking up via ajax
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



if (isSet($_GET['lookuppage'])) { $lookuppage=$_GET['lookuppage']; } elseif (isSet($_POST['lookuppage'])) { $lookuppage=$_POST['lookuppage']; }

if ($lookuppage) {
    include "C4uconnect.php";
//    echo ' 10 lookuppage : '.$lookuppage;
    

















    if ($lookuppage=='cojmaudit') {
        if (isSet($_GET['auditpage'])) { $view=$_GET['auditpage']; } else { $view=$_POST['auditpage']; }
if (isSet($_GET['page'])) { $page=$_GET['page']; } elseif (isSet($_POST['page'])) { $page=$_POST['page']; }
if (isSet($_POST['showdebug'])) { $showdebug=$_POST['showdebug']; } else { $showdebug=''; }
if (isSet($_POST['showtimes'])) { $showtimes=$_POST['showtimes']; } else { $showtimes=''; }
if (isSet($_GET['orderid'])) { $orderid=$_GET['orderid'];   } else { $orderid=$_POST['orderid']; }

if  (isset($_POST['clientid'])) { $clientid=trim($_POST['clientid']); } else { $clientid=''; }
if  (isset($_POST['clientview'])) { $clientview=trim($_POST['clientview']); } else { $clientview=''; }
if  (isset($_POST['newcyclistid'])) { $newcyclistid=trim($_POST['newcyclistid']); } else { $newcyclistid=''; }
if (isset($_POST['viewselectdep'])) { $viewselectdep=trim($_POST['viewselectdep']); } else { $viewselectdep=''; }
if (isSet($_POST['showpageviews'])) { $showpageviews=$_POST['showpageviews'];   } else { $showpageviews=''; }
if  (isset($_POST['from'])) {
    
    $start=trim($_POST['from']); 
    
    if ($start) {

        if ($clientid=='') { $clientid='all'; }
        $trackingtext='';
        $tstart = str_replace("%2F", ":", "$start", $count);
        $tstart = str_replace("/", ":", "$start", $count);
        $tstart = str_replace(",", ":", "$tstart", $count);
        $temp_ar=explode(":","$tstart"); 
        $day=$temp_ar['0']; 
        $month=$temp_ar['1']; 
        $year=$temp_ar['2']; 
        $hour='00';
        $minutes='00';
        $second='00';
        $sqlstart= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
        $dstart= date("U", mktime($hour, $minutes, $second, $month, $day, $year));
        if ($year) { $inputstart=$day.'/'.$month.'/'.$year; }
    } else { $inputstart=''; $sqlstart=''; }

} else { // nothing posted
    $inputstart='';
    $sqlstart='';
}

if (isset($_POST['to'])) {
    $end=trim($_POST['to']);

    if ($end) {

        $tend = str_replace("%2F", ":", "$end", $count);
        $tend = str_replace("/", ":", "$end", $count);
        $tend = str_replace(",", ":", "$tend", $count);
        $temp_ar=explode(":",$tend); 
        $day=$temp_ar['0']; 
        $month=$temp_ar['1']; 
        $year=$temp_ar['2']; 
        $hour='23';
        $minutes= '59';
        $second='59';
        if ($year) { $inputend=$day.'/'.$month.'/'.$year; }
        $sqlend= date("Y-m-d H:i:s", mktime(23, 59, 59, $month, $day, $year));
        $dend=date("U", mktime(23, 59, 59, $month, $day, $year));
    }
    else {
        $sqlend='3000-12-25 23:59:59'; $inputend='';
    }
} else { 
    $inputend='';
    $sqlend='';
}


$idlocated='';


$conditions = array();
$parameters = array();
$where = "";

$conditions[] = " `auditdatetime` <> '' ";

if ($sqlstart) {
    $conditions[] = " auditdatetime >= :sqlstart ";
    $parameters[":sqlstart"] = $sqlstart;
}
if ($sqlend) {
    $conditions[] = " auditdatetime <= :sqlend ";
    $parameters[":sqlend"] = $sqlend;
}

if ($showpageviews<>1) {
    $conditions[] = " (( `auditpage` <>'') OR  (`audittext` <>'' )) ";
}

if ($page) {
    $conditions[] = " auditpage = :auditpage ";
    $parameters[":auditpage"] = $page;
}


if ($orderid) {
    $query="SELECT ID FROM Orders WHERE Orders.ID = :id LIMIT 1";
    $prep = $dbh->prepare($query);
    $prep->bindParam(':id', $orderid, PDO::PARAM_INT);
    $prep->execute();
    $stmt = $prep->fetchAll();    
    if ($stmt) {
        $conditions[] = " auditorderid = :auditorderid ";
        $parameters[":auditorderid"] = $orderid;
        $idlocated='1';
    } // ends located
} // ends orderid present


    
    if (count($conditions) > 0) {
        $where = implode(' AND ', $conditions);
    }

    // check if $where is empty string or not
    $query = "SELECT * FROM  `cojm_audit`
    " . ($where != "" ? " WHERE $where" : "");


$query.= " ORDER BY auditdatetime DESC ";

    try {
        if (empty($parameters)) {
            $result = $dbh->query($query);
        }
        else {
            $statement = $dbh->prepare($query);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $result = $statement->fetchAll();
            $pdocount = $statement->rowCount();
        }
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
   
if ($result) {

    echo '<div class="success"> '.$pdocount.' result';
    if ($pdocount<>'1') { echo 's '; }
    echo ' found ';
    echo ' </div><br />'; 


    echo ' <table class="orderaudit">
    <thead>
    <tr>
    <th>Time</th>
    <th>User</th>';

    if ($idlocated=='') {
        echo ' <th>Job Ref</th>';
    }

    echo ' <th>Text</th>';
    if ($showdebug) {
        echo ' <th>Debug Text</th>';
    }
    echo ' <th>Page</th>
    <th>Action</th>';
    if ($showtimes) {
        echo '<th> CJ ms</th><th> MID ms</th><th> PAGE ms</th>';
    }


    echo ' <th colspan="2">Screen Size</th>
    <th>OS</th>
    <th>Browser</th>
    </tr>
    </thead>
    <tbody>';


    foreach ($result as $audrow) {
        $rowbrowser = new Browser($agent_string=$audrow['auditbrowser']); 
        $rowplatform=$rowbrowser->getPlatform();
        $rowversion=$rowbrowser->getVersion();
        $rowbrowsername=$rowbrowser->getBrowser();
        
        echo ' <tr>
        <td>'.date('H:i D jS M Y', strtotime($audrow['auditdatetime'])).'</td>
        <td>'.$audrow['audituser'].'</td>';
        if ($idlocated=='') {
            echo '<td>';
            if ($audrow['auditorderid']<>'0') {
                echo '<a target="_blank" class="newwin" href="order.php?id='. $audrow['auditorderid'].'">'. $audrow['auditorderid'].'</a>';
            }
            echo '</td>';
        }
        echo ' <td >'.$audrow['audittext'].'</td>';
        if ($showdebug) {
            echo '<td>'.$audrow['auditinfotext'].'</td>';
        }
        echo '
        <td>'.$audrow['auditfilename'].'</td>
        <td>'.$audrow['auditpage'].'</td>';
        if ($showtimes) {
            echo '<td>';
            if ($auditcjtime) { echo $auditcjtime; }
            echo ' </td> <td>';
            if ($auditmidtime) { echo $auditmidtime; }
            echo ' </td><td> ';
            if ($auditpagetime) { echo $auditpagetime; }
            echo '</td> ';	
        }
        echo ' <td>';
        if ($audrow['auditmobdevice']=='1') {
            echo'<span class="mobileonline" title="Mobile Device" ></span>';
        }
        else {
            echo '<span class="desktoponline" title="Desktop" ></span>';
        }
        echo '</td>
        <td>';
        if (($audrow['auditscreenwidth']) or ($audrow['auditscreenheight'])) {
            echo $audrow['auditscreenwidth'].' x '.$audrow['auditscreenheight'];
        }
        echo '</td> ';
        echo '<td>' . $rowplatform . ' </td> <td> '.$rowbrowsername;
        if ($rowversion) { echo ' <br /> v '.$rowversion; }
        echo '</td>';
        echo ' </tr>';

    } // ends row extract

    echo '</tbody> </table>';
    
    
//    echo ' auditpage = '.$auditpage;
    
}
else {
    echo ' <div class="successinfo" >No Results Found. </div> ';
}



        
        
    }



    if ($lookuppage=='invoiceorderlist'){
        $invoiceref=$_POST['invoiceref'];
        $sql = "
        SELECT * FROM Orders 
        left JOIN Services ON Orders.ServiceID = Services.ServiceID 
        left JOIN Cyclist ON Orders.CyclistID = Cyclist.CyclistID
        left JOIN Clients ON Orders.CustomerID = Clients.CustomerID
        left JOIN status ON Orders.status = status.status 
        WHERE Orders.invoiceref = :ref
        ORDER BY `Orders`.`ShipDate` ASC";
        
        $prep = $dbh->prepare($sql);
        $prep->bindParam(':ref', $invoiceref, PDO::PARAM_INT);
        $prep->execute();
        $stmt = $prep->fetchAll();
    
        $firstrun='1';
        
        if ($stmt) {

            $i='1';
            $tablecost='';
            $tabletotal='';
            $temptrack='';
            $tottimedif='';
            $secmod='';
            echo '<div class="vpad"></div>
            <table class="acc normalsize" style="width:100%;">
            <tbody>
            <tr>
            <th scope="col">COJM ID</th>
            <th scope="col">'.$globalprefrow['glob5'].'</th>
            <th scope="col">Service</th>
            <th scope="col">Cost ex VAT</th>
            <th scope="col">From </th>
            <th scope="col">To </th>
            <th scope="col">Collection</th>
            <th scope="col">Delivery</th>
            </tr>';
            
            foreach ($stmt as $orow) {
            
                echo '
                <tr>
                <td><a href="order.php?id='. $orow['ID'].'">'. $orow['ID'].'</a></td>
                <td>'. $orow['cojmname'].'</td>
                <td>'. formatmoney($orow["numberitems"]) .' x '. $orow['Service'].'</td>
                <td>&'. $globalprefrow['currencysymbol'].$orow["FreightCharge"].'</td>
                <td>'. $orow['enrft0'];  
                if (trim($orow['enrpc0'])) {
                    echo ' <a target="_blank" href="http://maps.google.com/maps?q='. $orow['enrpc0'].'">'. $orow['enrpc0'].'</a>';
                    }
                echo '</td><td>'. $orow['enrft21'].' ';
                if (trim($orow['enrpc21'])) {
                    echo ' <a target="_blank" href="http://maps.google.com/maps?q='. $orow['enrpc21'].'">'. $orow['enrpc21'].'</a>';
                }
                echo '</td>
                <td>'.date('H:i D jS M ', strtotime($orow['collectiondate'])).'</td>
                <td>'.date('H:i D jS M ', strtotime($orow['ShipDate'])).'</td></tr>';
                
                $tablecost = $tablecost + $orow["FreightCharge"];
                $tabletotal = $tabletotal + $orow['numberitems'];
                
                $temptrack=$temptrack.'<input type="hidden" name="tr'.$i.'" value="'.$orow['ID'].'" />';
                
                $i++;
                
                $tottimec=strtotime($orow['starttrackpause']);
                $tottimed=strtotime($orow['finishtrackpause']);
                if (($tottimec>'1') AND ($tottimed>'1')) { $secmod=($tottimed-$tottimec); }
                $tottimea=strtotime($orow['collectiondate']); 
                $tottimeb=strtotime($orow['ShipDate']); 
                $tottimedif=($tottimedif+$tottimeb-$tottimea-$secmod);
            
            
            } 
            
            echo '</tbody></table>';
            
            
            
            
            
            $lengthtext='';
            
            
            $inputval = $tottimedif; // USER DEFINES NUMBER OF SECONDS FOR WORKING OUT | 3661 = 1HOUR 1MIN 1SEC 
            $unitd ='86400';
            $unith ='3600';        // Num of seconds in an Hour... 
            $unitm ='60';            // Num of seconds in a min... 
            $dd = intval($inputval / $unitd);       // days
            $hh_remaining = ($inputval - ($dd * $unitd));
            $hh = intval($hh_remaining / $unith);    // '/' given value by num sec in hour... output = HOURS 
            $ss_remaining = ($hh_remaining - ($hh * $unith)); // '*' number of hours by seconds, then '-' from given value... output = REMAINING seconds 
            $mm = intval($ss_remaining / $unitm);    // take remaining sec and devide by sec in a min... output = MINS 
            $ss = ($ss_remaining - ($mm * $unitm));        // '*' number of mins by seconds, then '-' from remaining sec... output = REMAINING seconds. 
            if ($dd=='1') {$lengthtext=$lengthtext. $dd . " day "; } if ($dd>'1' ) { $lengthtext=$lengthtext. $dd . " days "; }
            if ($hh=='1') {$lengthtext=$lengthtext. $hh . " hr "; } if ($hh>'1') { $lengthtext=$lengthtext. $hh . " hrs "; }
            if ($mm>'1' ) {$lengthtext=$lengthtext. $mm . " mins. "; } if ($mm=='1') {$lengthtext=$lengthtext. $mm . " min. "; }
            // number_format($tablecost, 2, '.', '')
            if ($dd) {} else { if ($mm) {   $lengthtext=$lengthtext. "(". number_format((($mm/'60')+$hh), 2, '.', ''). 'hrs)'; } }
            // echo ($tottimedif/60).' minutes';
            
            
            
            
            if (($lengthtext) or ($tabletotal)) {
                echo '<div class="vpad"></div>
                <div class="ui-widget">	<div class="ui-state-default ui-corner-all" style="padding: 0.5em; width:auto;">';
            
                if ($tabletotal) {
                    echo '<fieldset><label for="txtName" class="fieldLabel"> Total Volume </label> '. $tabletotal.'</fieldset>'; 
                }
            
                if (trim($lengthtext)) {
                    echo '<fieldset><label for="txtName" class="fieldLabel"> 
                    Time Taken </label>'.$lengthtext. ' from collection to delivery.</fieldset>';
                }
            
            
                echo '</div>';
            }
        
        } // ends rum rows loop
        
        
        
    
}

    if (($lookuppage=='individexpense') and (isset($_POST['expenseid']))) {
        if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  exit(); } }
    
        $expenseid=(trim($_POST['expenseid']));
    
        try {
            $query= ' SELECT *
            FROM expenses
            left JOIN Cyclist ON expenses.cyclistref = Cyclist.CyclistID 
            left join expensecodes ON expenses.expensecode = expensecodes.expensecode 
            WHERE expenseref = :expenseid ';
            
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':expenseid', $expenseid, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
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
                $("#explastupdated").html("'.date('H:i D jS M Y', strtotime($row['expts'])).'");
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
                $("#expensecomment").trigger("autosize.resize");
                
                window.history.pushState("object or string", "Expense '.$row['expenseref'].'", "/cojm/live/singleexpense.php?expenseref='.$row['expenseref'].'"); ';
                
                if ($row['expensecode']=='6') {
                    echo ' $("#riderselect").removeClass("hideuntilneeded"); ';
                } else {
                    echo ' $("#riderselect").addClass("hideuntilneeded"); ';
                }
                
                echo ' </script>';
                
            } else {
                echo '<script>
                $("#amount").val("");
                $("#expensevat").val("");
                $("select#expensecode").val("");
                $("#whoto").val(""); 
                $("select#cyclistref").val("");
                $("#expensedate").val("");
                $("#explastupdated").html("");
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

    }
    


    if ($lookuppage=='paymentdetail'){
        if (isset($_POST['paymentref'])) {
            $paymentref=(trim($_POST['paymentref']));
            try {
                $query= ' SELECT paymentdate, paymentamount, paymentclient, paymenttypename, paymentcomment, CompanyName, isactiveclient
                FROM cojm_payments
                left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID 
                left JOIN cojm_paymenttype ON cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
                WHERE paymentid = :paymentid LIMIT 0,1';
                
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':paymentid', $paymentref, PDO::PARAM_INT);
                $stmt->execute();
                $total = $stmt->rowCount();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($total) {
                    if ($row['paymentdate']>'10') { echo date('d-m-Y', strtotime($row['paymentdate'])); }
                    
                    echo ' &'. $globalprefrow['currencysymbol'].$row['paymentamount'].' '.
                    $row['paymenttypename'].' '.
                    
                    $row['CompanyName'].' '.$row['paymentcomment'];

                }
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }


    
    if ($lookuppage=='paymentstuff'){

        if (isset($_POST['paymentid'])) {  
            $paymentid=(trim($_POST['paymentid']));
            try {
                $query= ' SELECT paymentdate, paymentamount, paymentclient, paymenttype, paymentcomment, CompanyName, isactiveclient
                FROM cojm_payments
                left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID 
                WHERE paymentid = :paymentid LIMIT 0,1';
                
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':paymentid', $paymentid, PDO::PARAM_INT);
                $stmt->execute();
                $total = $stmt->rowCount();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($total) {
                        
                    if ($row['paymentdate']>'10') { $paymentdate= date('d-m-Y', strtotime($row['paymentdate'])); }
                    
                    echo '<script>
                    allok=1;
                    message=" Payment Located ";
                    $("#paymentdetails").show();
                    $("#amountpaid").val("'.$row['paymentamount'].'");
                    $("#paymentdate").val("'.$paymentdate.'");  
                    $("select#paymentmethod").val("'.$row['paymenttype'].'");';
                    
                    if ($row['isactiveclient']<>'1') {
                        echo '
                        $("#combobox").prepend("<option selected value='.$row['paymentclient'].'>'.$row['CompanyName'].'</option> " ); ';
                    }
        
                    echo '            
                    $("#combobox").combobox("autocomplete", "'.$row['paymentclient'].'","'.$row['CompanyName'].'"); 
                    $("select#combobox").val("'.$row['paymentclient'].'");
                    $("#combobox").val("'.$row['paymentclient'].'");  
        
                    var str = "'.(trim($row['paymentcomment'])).'";
                    var regex = /<br\s*[\/]?>/gi;
                    $("#paymentcomment").val(str.replace(regex, "\n"));
                    $("#editpayment").removeClass("hideuntilneeded");
                    window.history.pushState("object or string", "Payment Ref '.$paymentid.'", "/cojm/live/paymentsin.php?paymentid='.$paymentid.'");
                    
                    </script>';
                    
                } else {
                    echo '<script>
                    $("#paymentdetails").hide();
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
        
        }

        
        $view=$_POST['view'];
        $clientid=trim($_POST['clientid']);
        if (!$clientid) {
            $view='initial';
        }
        
        
        if ($view=='initial') {
            $sql="
            SELECT paymentid, paymentdate, paymentamount, paymentclient, paymenttype, paymenttypename, paymentcomment, paymentedited, paymentcreated, CompanyName FROM cojm_payments 
            left JOIN cojm_paymenttype ON cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
            left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID
            ORDER BY paymentdate DESC LIMIT 0,10";
            $stmt = $dbh->query($sql);
        }
        
        if ($view=='client') {
            $sql="
            SELECT paymentid, paymentdate, paymentamount, paymentclient, paymenttype, paymenttypename, paymentcomment, paymentedited, paymentcreated, CompanyName FROM cojm_payments 
            left JOIN cojm_paymenttype ON cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
            left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID
            WHERE Clients.CustomerID = :clientid
            ORDER BY paymentdate DESC LIMIT 0,10";        
            
            $prep = $dbh->prepare($sql);
            $prep->bindParam(':clientid', $clientid, PDO::PARAM_INT);
            $prep->execute();
            $stmt = $prep->fetchAll();
        }
        
        
        if ($stmt) {
        
            echo ' <table class="acc" id="lastten" style="float:left;">
            <caption>Last 10 Payments</caption>
            <tr>
            <thead>
            <th scope="col">Reference</th>
            <th scope="col">Amount</th>
            <th scope="col">Date</th>
            <th scope="col">Client</th>
            <th scope="col">Type</th>
            <th scope="col">Comments</th>
            <th> Created</th>
            <th> Edited</th>
            </tr>
            </thead><tbody> ';
            
    
            foreach ($stmt as $row) {
                echo '<tr> <td> <a href="paymentsin.php?paymentid='.$row['paymentid']. '">'.$row['paymentid'].'</a></td>';
                echo '<td class="rh"> &'. $globalprefrow['currencysymbol']. $row['paymentamount']. '</td> ';
                echo '<td class="rh">'. date('D jS M Y', strtotime($row['paymentdate'])).'</td> ';
                echo '<td> '.$row['CompanyName'].' </td> ';
                echo '<td>'.$row['paymenttypename'].' </td>';
                echo '<td>'. $row['paymentcomment'].'</td>';
                echo '<td class="rh">'. date('H:i D jS M Y', strtotime($row['paymentcreated'])).'</td> ';
                echo '<td class="rh">'. date('H:i D jS M Y', strtotime($row['paymentedited'])).'</td> ';
                echo '</tr>';
            } // ends expense ref loop
            
            
            echo ' </tbody> </table> ';
            
            
        }
            
            
            
        if ($view=='initial') {
            $sql="
            SELECT paymentid, paymentdate, paymentamount, paymentclient, paymenttype, paymenttypename, paymentcomment, paymentedited, paymentcreated, CompanyName 
            FROM cojm_payments 
            left JOIN cojm_paymenttype ON cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
            left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID
            ORDER BY paymentedited DESC LIMIT 0,10";
            $stmt = $dbh->query($sql);
        }
        
        if ($view=='client') {
            $sql="
            SELECT paymentid, paymentdate, paymentamount, paymentclient, paymenttype, paymenttypename, paymentcomment, paymentedited, paymentcreated, CompanyName 
            FROM cojm_payments 
            left JOIN cojm_paymenttype ON cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
            left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID
            WHERE cojm_payments.paymentclient = :clientid
            ORDER BY paymentedited DESC LIMIT 0,10";        
            
            $prep = $dbh->prepare($sql);
            $prep->bindParam(':clientid', $clientid, PDO::PARAM_INT);
            $prep->execute();
            $stmt = $prep->fetchAll();
        }
        
                        
            
        if ($stmt) {    
            echo '
            <table class="acc" id="lastedit" style="float:left;">
            <caption>Last 10 Edited Payments</caption>
            <tr>
            <thead>
            <th scope="col">Reference</th>
            <th scope="col">Amount</th>
            <th scope="col">Date</th>
            <th scope="col">Client</th>
            <th scope="col">Type</th>
            <th scope="col">Comments</th>
            <th> Created</th>
            <th> Edited</th>
            </tr>
            </thead>
            <tbody> ';
            
            foreach ($stmt as $row) {
                echo '<tr> <td> <a href="paymentsin.php?paymentid='.$row['paymentid']. '">'.$row['paymentid'].'</a></td>';
                echo '<td class="rh"> &'. $globalprefrow['currencysymbol']. $row['paymentamount']. '</td> ';
                echo '<td class="rh">'. date('D jS M Y', strtotime($row['paymentdate'])).'</td> ';
                echo '<td> '.$row['CompanyName'].' </td> ';
                echo '<td>'.$row['paymenttypename'].' </td>';
                echo '<td>'. $row['paymentcomment'].'</td>';
                echo '<td class="rh">'. date('H:i D jS M Y', strtotime($row['paymentcreated'])).'</td> ';
                echo '<td class="rh">'. date('H:i D jS M Y', strtotime($row['paymentedited'])).'</td> ';
                echo '</tr>';
            } // ends expense ref loop
            
            echo '
            </tbody>
            </table> ';
        }
    
            
        if ($view=='initial') {
            $sql="
            SELECT paymentid, paymentdate, paymentamount, paymentclient, paymenttype, paymenttypename, paymentcomment, paymentedited, paymentcreated, CompanyName FROM cojm_payments 
            left JOIN cojm_paymenttype ON cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
            left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID
            ORDER BY paymentcreated DESC LIMIT 0,10";
            $stmt = $dbh->query($sql);
        }
        
        if ($view=='client') {
            $sql="
            SELECT paymentid, paymentdate, paymentamount, paymentclient, paymenttype, paymenttypename, paymentcomment, paymentedited, paymentcreated, CompanyName FROM cojm_payments 
            left JOIN cojm_paymenttype ON cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
            left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID
            WHERE Clients.CustomerID = :clientid
            ORDER BY paymentcreated DESC LIMIT 0,10";        
            
            $prep = $dbh->prepare($sql);
            $prep->bindParam(':clientid', $clientid, PDO::PARAM_INT);
            $prep->execute();
            $stmt = $prep->fetchAll();
        }
        
    
        if ($stmt) {
    
            echo '
            <table class="acc" id="lastten" style="float:left;">
            <caption>Last 10 Created Payments</caption>
            <tr>
            <thead>
            <th scope="col">Reference</th>
            <th scope="col">Amount</th>
            <th scope="col">Date</th>
            <th scope="col">Client</th>
            <th scope="col">Type</th>
            <th scope="col">Comments</th>
            <th> Created</th>
            <th> Edited</th>
            </tr>
            </thead><tbody> ';

            foreach ($stmt as $row) {
                echo '<tr> <td> <a href="paymentsin.php?paymentid='.$row['paymentid']. '">'.$row['paymentid'].'</a></td>';
                echo '<td class="rh"> &'. $globalprefrow['currencysymbol']. $row['paymentamount']. '</td> ';
                echo '<td class="rh">'. date('D jS M Y', strtotime($row['paymentdate'])).'</td> ';
                echo '<td> '.$row['CompanyName'].' </td> ';
                echo '<td>'.$row['paymenttypename'].' </td>';
                echo '<td>'. $row['paymentcomment'].'</td>';
                echo '<td class="rh">'. date('H:i D jS M Y', strtotime($row['paymentcreated'])).'</td> ';
                echo '<td class="rh">'. date('H:i D jS M Y', strtotime($row['paymentedited'])).'</td> ';
                echo '</tr>';
            } // ends expense ref loop
    
            echo '</tbody> </table> ';
        }

    }


    if ($lookuppage=='updateexptable') { // update expense tables
        // echo ' Updating Expense Tables ';
        echo ' <script> $("#explastupdated").html("'.date("H:i ").'Today"); </script> ';
        $sql="
        SELECT expenseref, expts, paid, expensecost, expensevat, expensedate, whoto, smallexpensename, CyclistID, cojmname, expc1, expc2, expc3, expc4, expc5, expc6, chequeref, description FROM expenses 
        INNER JOIN Cyclist 
        INNER JOIN expensecodes
        ON expenses.cyclistref = Cyclist.CyclistID 
        AND expenses.expensecode = expensecodes.expensecode 
        ORDER BY expts DESC LIMIT 0,10
        ";
        
        $stmt = $dbh->query($sql);
        
        
        echo ' <table class="acc" id="lastedit" style="float:left;">
        <caption>Last 10 Edited Expenses</caption>
        <tr>
        <thead>
        <th scope="col">Reference</th>
        <th title="Incl. VAT" scope="col">Net Amount</th>
        <th scope="col">VAT </th>
        <th scope="col">Edited</th>
        <th scope="col">'. $globalprefrow['glob5'] .' </th>
        <th scope="col">Paid to</th>
        <th scope="col">Type</th>
        <th scope="col">Method </th>
        <th scope="col">Comments</th>
        </tr>
        </thead>
        <tbody> ';
        
        
        
        
        
        foreach ($stmt as $row) {
            $loop.= '<tr> <td> <a href="singleexpense.php?expenseref='.$row['expenseref']. '">'.$row['expenseref'].'</a>';     
            if ($row['paid']<'1') { $loop.= ' UNPAID'; }
            $loop.= '</td> <td class="rh"> &'. $globalprefrow['currencysymbol']. $row['expensecost']. '</td><td> ';
            if ($row['expensevat']>'0') { $loop.=' &'.$globalprefrow['currencysymbol']. $row['expensevat']; }
            $loop.= ' </td> <td class="rh">'. date('H:i D jS M Y', strtotime($row['expts'])).'</td> <td>';
            if ($row['CyclistID']<>'1') { $loop.= $row['cojmname']; }
            $loop.= '</td> <td>'.$row['whoto'].'</td> <td>'.$row['smallexpensename'].'  </td> <td>';
            if ($row['expc1']>0) { $loop.= $globalprefrow['gexpc1']; } 
            if ($row['expc2']>0) { $loop.= $globalprefrow['gexpc2']; } 
            if ($row['expc3']>0) { $loop.= $globalprefrow['gexpc3']; } 
            if ($row['expc4']>0) { $loop.= $globalprefrow['gexpc4']; } 
            if ($row['expc5']>0) { $loop.= $globalprefrow['gexpc5']; } 
            if ($row['expc6']>0) { $loop.= $globalprefrow['gexpc6'].' '.$row['chequeref']; }
            $loop.= '</td><td>'. $row['description'].'</td></tr>';
        } // ends expense ref loop
        
        echo $loop;
        echo ' </tbody> </table> ';
        
        echo ' <table class="acc" id="lastten" style="float:left;">
        <caption>Last 10 Created Expenses</caption>
        <tr>
        <thead>
        <th scope="col">Reference</th>
        <th title="Incl. VAT" scope="col">Net Amount</th>
        <th scope="col">VAT </th>
        <th scope="col">Created</th>
        <th scope="col">'. $globalprefrow['glob5'] .' </th>
        <th scope="col">Paid to</th>
        <th scope="col">Type</th>
        <th scope="col">Method </th>
        <th scope="col">Comments</th>
        </tr>
        </thead>
        <tbody> ';

        $sql="
        SELECT expenseref, paid, expensecost, expensevat, expensedate, whoto, smallexpensename, CyclistID, cojmname, expc1, expc2, expc3, expc4, expc5, expc6, chequeref, description FROM expenses 
        left JOIN Cyclist ON expenses.cyclistref = Cyclist.CyclistID 
        left JOIN expensecodes ON expenses.expensecode = expensecodes.expensecode 
        ORDER BY expenseref DESC LIMIT 0,10; ";
       
        $stmt = $dbh->query($sql);
        foreach ($stmt as $row) {
            
            echo '
            <tr> 
            <td>
            <a href="singleexpense.php?expenseref='.$row['expenseref']. '">'.$row['expenseref'].'</a>'; 
            
            
            if ($row['paid']<'1') { echo ' UNPAID'; }
            
            echo '</td>
            <td class="rh"> &'. $globalprefrow['currencysymbol']. $row['expensecost'].
            '</td>
            <td> ';
            
            if ($row['expensevat']>'0') { echo' &'.$globalprefrow['currencysymbol']. $row['expensevat']; }
            
            echo '
            </td>
            <td class="rh">'. date('H:i D jS M Y', strtotime($row['expensedate'])).'</td>
            <td>';
            
            if ($row['CyclistID']<>'1') { echo $row['cojmname']; }   
            echo '</td>
            <td>' . $row['whoto'].'</td>
            <td>' . $row['smallexpensename'] . ' </td> <td>';
            if ($row['expc1']>0) { echo $globalprefrow['gexpc1']; } 
            if ($row['expc2']>0) { echo $globalprefrow['gexpc2']; } 
            if ($row['expc3']>0) { echo $globalprefrow['gexpc3']; } 
            if ($row['expc4']>0) { echo $globalprefrow['gexpc4']; } 
            if ($row['expc5']>0) { echo $globalprefrow['gexpc5']; } 
            if ($row['expc6']>0) { echo $globalprefrow['gexpc6'].' '.$row['chequeref']; } 
            
            echo '</td><td>'. $row['description'].'</td>';
            echo '</tr>';
            
        } // ends expense ref loop

        echo '
        </tbody>
        </table> ';
        
    }

    
    if ($lookuppage=='allclientjson') {
        // tell the browser what's coming
        header('Content-type: application/json');
        $sql="SELECT CustomerID, CompanyName FROM Clients order by CompanyName asc";
        $prep = $dbh->prepare($sql);
        $prep->execute();
        $stmt = $prep->fetchAll(); 
        $string= ' [ ';
        foreach ($stmt as $favrow) {
            $string .= ' {"oV":"'. $favrow['CustomerID'].'","oD":"'. $favrow['CompanyName'].'"},';
        }
        echo rtrim($string,',') . ']';
    }



    if ($lookuppage=='allfavjson') {
        // tell the browser what's coming
        header('Content-type: application/json');
        
        if ($_POST['clientid']) {
            $clientid=$_POST['clientid'];
            $sql="SELECT favadrid, favadrft, favadrpc FROM cojm_favadr WHERE favadrisactive ='1' and favadrclient = :clientid GROUP BY favadrft, favadrpc ";
            $prep = $dbh->prepare($sql);
            $prep->bindParam(':clientid', $clientid, PDO::PARAM_INT);
            $prep->execute();
        }

        if ($_POST['clientid']=='all'){
            $sql="SELECT favadrid, favadrft, favadrpc FROM cojm_favadr WHERE favadrisactive ='1' GROUP BY favadrft, favadrpc ";
            $prep = $dbh->prepare($sql);
            $prep->execute();
        }

        $stmt = $prep->fetchAll();        
        
        $string= ' [ ';
        foreach ($stmt as $favrow) {
            if ((trim($favrow['favadrft'])) or (trim($favrow['favadrpc']))) { 
                $string .= ' {"oV":"'. $favrow['favadrid'].'","oD":"'. $favrow['favadrft'].', '. $favrow['favadrpc'].'"},';
            }
        }
        echo rtrim($string,',') . ']';
    }


    
    if ($lookuppage=='ajaxgpsorderlookup') { 
        $markervar=(trim($_POST['markervar']));
        $markervarexploded=explode( '_', $markervar );
        // echo ' time is '.$markervarexploded[0].' rider is '.$markervarexploded[1];
        $timetocheck=date('Y-m-d H:i:59', $markervarexploded[0]);
        $timetocheckearly=date('Y-m-d H:i:00', ($markervarexploded[0])-60);
    
        try {
            
            $query= ' SELECT ID, CompanyName, depname
            FROM Orders 
            INNER JOIN Cyclist ON Orders.CyclistID = Cyclist.CyclistID
            INNER JOIN Clients ON Orders.CustomerID = Clients.CustomerID
            left join clientdep ON Orders.orderdep = clientdep.depnumber
            WHERE trackerid = :trackerid
            AND ( ';
            $query.='( (collectiondate < :timetochecka) and (starttrackpause > :timetocheckb) ) ';
            $query.='or ( ( collectiondate < :timetocheckc ) and ( ShipDate > :timetocheckd ) and ( finishtrackpause = "0000-00-00 00:00:00" ) ) and ( starttrackpause = "0000-00-00 00:00:00" ) ';
            $query.='or ( (finishtrackpause < :timetocheckf ) and ( finishtrackpause <> "0000-00-00 00:00:00" ) and ( starttrackpause <> "0000-00-00 00:00:00" ) and ( ShipDate = "0000-00-00 00:00:00" ) ) ';
            $query.='or ( (finishtrackpause < :timetocheckg ) and ( finishtrackpause <> "0000-00-00 00:00:00" ) and ( ShipDate > :timetochecke ) ) ';
            $query.='or ( ( collectiondate < :timetocheckh ) and ( ShipDate = "0000-00-00 00:00:00" ) and ( finishtrackpause = "0000-00-00 00:00:00" ) and ( starttrackpause = "0000-00-00 00:00:00" ) and ( collectiondate <> "0000-00-00 00:00:00" )  ) ';
            $query.=') ';
            
            // btwn collection & pause,  
            // between collection & delivery, no pause or resume
            // between resume and now , no delivery
            // > resume, resume exists, < delivery
            // > collection, collection exists, no pause, resume or delivery
            
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':timetochecka', $timetocheck, PDO::PARAM_INT);
            $stmt->bindParam(':timetocheckb', $timetocheckearly, PDO::PARAM_INT);
            $stmt->bindParam(':timetocheckc', $timetocheck, PDO::PARAM_INT);
            $stmt->bindParam(':timetocheckd', $timetocheckearly, PDO::PARAM_INT);
            $stmt->bindParam(':timetochecke', $timetocheckearly, PDO::PARAM_INT);
            $stmt->bindParam(':timetocheckf', $timetocheck, PDO::PARAM_INT);
            $stmt->bindParam(':timetocheckg', $timetocheck, PDO::PARAM_INT);
            $stmt->bindParam(':timetocheckh', $timetocheck, PDO::PARAM_INT);
            $stmt->bindParam(':trackerid', ($markervarexploded[1]), PDO::PARAM_INT);
            $stmt->execute();
            $total = $stmt->rowCount();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
                echo '<a href="order.php?id='.$row['ID'].'" title="" >'.$row['ID'].'</a> '.
                $row['CompanyName'];
                if ($row['depname']<>"") { echo ' ('.$row['depname'].') '; }
                echo '<br />';
            }
        }
        
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
}


/////////////////      FUNCTION FOR FORMATTING MONEY VALUES ////////////////////////////////////////
function formatMoney($money) {
    if (floor($money) == $money) {
$money=number_format(($money), 0, '.', ',');
} 
else if (round($money, 1)==$money){
$money=number_format(($money), 1, '.', ',');
}
else { 
$money=number_format(($money), 2, '.', ',');
}
return $money; } 



/**
// http://www.awcore.com/archive?file=571&path=Browser.php

     * File: Browser.php
     * Author: Chris Schuld (http://chrisschuld.com/)
     * Last Modified: August 20th, 2010
     * @version 1.9
     * @package PegasusPHP
     *
     * Copyright (C) 2008-2010 Chris Schuld  (chris@chrisschuld.com)
     *
     * This program is free software; you can redistribute it and/or
     * modify it under the terms of the GNU General Public License as
     * published by the Free Software Foundation; either version 2 of
     * the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details at:
     * http://www.gnu.org/copyleft/gpl.html
     *
     *
     * Typical Usage:
     *
     *   $browser = new Browser();
     *   if( $browser->getBrowser() == Browser::BROWSER_FIREFOX && $browser->getVersion() >= 2 ) {
     *      echo 'You have FireFox version 2 or greater';
     *   }
     *
     * User Agents Sampled from: http://www.useragentstring.com/
     *
     * This implementation is based on the original work from Gary White
     * http://apptools.com/phptools/browser/
     *
     */
 
    class Browser {
        private $_agent = '';
        private $_browser_name = '';
        private $_version = '';
        private $_platform = '';
        private $_os = '';
        private $_is_aol = false;
        private $_is_mobile = false;
        private $_is_robot = false;
        private $_aol_version = '';
 
        const BROWSER_UNKNOWN = ' ';
        const VERSION_UNKNOWN = '';
        const PLATFORM_UNKNOWN = ' '; 
        const BROWSER_OPERA = 'Opera';                            // http://www.opera.com/
        const BROWSER_OPERA_MINI = 'Opera Mini';                  // http://www.opera.com/mini/
        const BROWSER_WEBTV = 'WebTV';                            // http://www.webtv.net/pc/
        const BROWSER_IE = 'Internet Explorer';                   // http://www.microsoft.com/ie/
        const BROWSER_POCKET_IE = 'Pocket Internet Explorer';     // http://en.wikipedia.org/wiki/Internet_Explorer_Mobile
        const BROWSER_KONQUEROR = 'Konqueror';                    // http://www.konqueror.org/
        const BROWSER_ICAB = 'iCab';                              // http://www.icab.de/
        const BROWSER_OMNIWEB = 'OmniWeb';                        // http://www.omnigroup.com/applications/omniweb/
        const BROWSER_FIREBIRD = 'Firebird';                      // http://www.ibphoenix.com/
        const BROWSER_FIREFOX = 'Firefox';                        // http://www.mozilla.com/en-US/firefox/firefox.html
        const BROWSER_ICEWEASEL = 'Iceweasel';                    // http://www.geticeweasel.org/
        const BROWSER_SHIRETOKO = 'Shiretoko';                    // http://wiki.mozilla.org/Projects/shiretoko
        const BROWSER_MOZILLA = 'Mozilla';                        // http://www.mozilla.com/en-US/
        const BROWSER_AMAYA = 'Amaya';                            // http://www.w3.org/Amaya/
        const BROWSER_LYNX = 'Lynx';                              // http://en.wikipedia.org/wiki/Lynx
        const BROWSER_SAFARI = 'Safari';                          // http://apple.com
        const BROWSER_IPHONE = 'iPhone';                          // http://apple.com
        const BROWSER_IPOD = 'iPod';                              // http://apple.com
        const BROWSER_IPAD = 'iPad';                              // http://apple.com
        const BROWSER_CHROME = 'Chrome';                          // http://www.google.com/chrome
        const BROWSER_ANDROID = 'Android';                        // http://www.android.com/
        const BROWSER_GOOGLEBOT = 'GoogleBot';                    // http://en.wikipedia.org/wiki/Googlebot
        const BROWSER_SLURP = 'Yahoo! Slurp';                     // http://en.wikipedia.org/wiki/Yahoo!_Slurp
        const BROWSER_W3CVALIDATOR = 'W3C Validator';             // http://validator.w3.org/
        const BROWSER_BLACKBERRY = 'BlackBerry';                  // http://www.blackberry.com/
        const BROWSER_ICECAT = 'IceCat';                          // http://en.wikipedia.org/wiki/GNU_IceCat
        const BROWSER_NOKIA_S60 = 'Nokia S60 OSS Browser';        // http://en.wikipedia.org/wiki/Web_Browser_for_S60
        const BROWSER_NOKIA = 'Nokia Browser';                    // * all other WAP-based browsers on the Nokia Platform
        const BROWSER_MSN = 'MSN Browser';                        // http://explorer.msn.com/
        const BROWSER_MSNBOT = 'MSN Bot';                         // http://search.msn.com/msnbot.htm
                                                                  // http://en.wikipedia.org/wiki/Msnbot  (used for Bing as well)
         
        const BROWSER_NETSCAPE_NAVIGATOR = 'Netscape Navigator';  // http://browser.netscape.com/ (DEPRECATED)
        const BROWSER_GALEON = 'Galeon';                          // http://galeon.sourceforge.net/ (DEPRECATED)
        const BROWSER_NETPOSITIVE = 'NetPositive';                // http://en.wikipedia.org/wiki/NetPositive (DEPRECATED)
        const BROWSER_PHOENIX = 'Phoenix';                        // http://en.wikipedia.org/wiki/History_of_Mozilla_Firefox (DEPRECATED)
 

        const PLATFORM_WINDOWS = 'Windows';
        const PLATFORM_WINDOWS_CE = 'Windows CE';
        const PLATFORM_APPLE = 'Apple';
        const PLATFORM_LINUX = 'Linux';
        const PLATFORM_OS2 = 'OS/2';
        const PLATFORM_BEOS = 'BeOS';
        const PLATFORM_IPHONE = 'iPhone';
        const PLATFORM_IPOD = 'iPod';
        const PLATFORM_IPAD = 'iPad';
        const PLATFORM_BLACKBERRY = 'BlackBerry';
        const PLATFORM_NOKIA = 'Nokia';
        const PLATFORM_FREEBSD = 'FreeBSD';
        const PLATFORM_OPENBSD = 'OpenBSD';
        const PLATFORM_NETBSD = 'NetBSD';
        const PLATFORM_SUNOS = 'SunOS';
        const PLATFORM_OPENSOLARIS = 'OpenSolaris';
        const PLATFORM_ANDROID = 'Android';
         
        const OPERATING_SYSTEM_UNKNOWN = 'unknown';
 
        public function Browser($useragent="") {
            $this->reset();
            if( $useragent != "" ) {
                $this->setUserAgent($useragent);
            }
            else {
                $this->determine();
            }
        }
 
        /**
        * Reset all properties
        */
        public function reset() {
            $this->_agent = isset($browsercheck) ? $browsercheck : "";
            $this->_browser_name = self::BROWSER_UNKNOWN;
            $this->_version = self::VERSION_UNKNOWN;
            $this->_platform = self::PLATFORM_UNKNOWN;
            $this->_os = self::OPERATING_SYSTEM_UNKNOWN;
            $this->_is_aol = false;
            $this->_is_mobile = false;
            $this->_is_robot = false;
            $this->_aol_version = self::VERSION_UNKNOWN;
        }
 
        /**
        * Check to see if the specific browser is valid
        * @param string $browserName
        * @return True if the browser is the specified browser
        */
        function isBrowser($browserName) { return( 0 == strcasecmp($this->_browser_name, trim($browserName))); }
 
        /**
        * The name of the browser.  All return types are from the class contants
        * @return string Name of the browser
        */
        public function getBrowser() { return $this->_browser_name; }
        /**
        * Set the name of the browser
        * @param $browser The name of the Browser
        */
        public function setBrowser($browser) { return $this->_browser_name = $browser; }
        /**
        * The name of the platform.  All return types are from the class contants
        * @return string Name of the browser
        */
        public function getPlatform() { return $this->_platform; }
        /**
        * Set the name of the platform
        * @param $platform The name of the Platform
        */
        public function setPlatform($platform) { return $this->_platform = $platform; }
        /**
        * The version of the browser.
        * @return string Version of the browser (will only contain alpha-numeric characters and a period)
        */
        public function getVersion() { return $this->_version; }
        /**
        * Set the version of the browser
        * @param $version The version of the Browser
        */
        public function setVersion($version) { $this->_version = preg_replace('/[^0-9,.,a-z,A-Z-]/','',$version); }
        /**
        * The version of AOL.
        * @return string Version of AOL (will only contain alpha-numeric characters and a period)
        */
        public function getAolVersion() { return $this->_aol_version; }
        /**
        * Set the version of AOL
        * @param $version The version of AOL
        */
        public function setAolVersion($version) { $this->_aol_version = preg_replace('/[^0-9,.,a-z,A-Z]/','',$version); }
        /**
        * Is the browser from AOL?
        * @return boolean True if the browser is from AOL otherwise false
        */
        public function isAol() { return $this->_is_aol; }
        /**
        * Is the browser from a mobile device?
        * @return boolean True if the browser is from a mobile device otherwise false
        */
        public function isMobile() { return $this->_is_mobile; }
        /**
        * Is the browser from a robot (ex Slurp,GoogleBot)?
        * @return boolean True if the browser is from a robot otherwise false
        */
        public function isRobot() { return $this->_is_robot; }
        /**
        * Set the browser to be from AOL
        * @param $isAol
        */
        public function setAol($isAol) { $this->_is_aol = $isAol; }
        /**
         * Set the Browser to be mobile
         * @param boolean $value is the browser a mobile brower or not
         */
        protected function setMobile($value=true) { $this->_is_mobile = $value; }
        /**
         * Set the Browser to be a robot
         * @param boolean $value is the browser a robot or not
         */
        protected function setRobot($value=true) { $this->_is_robot = $value; }
        /**
        * Get the user agent value in use to determine the browser
        * @return string The user agent from the HTTP header
        */
        public function getUserAgent() { return $this->_agent; }
        /**
        * Set the user agent value (the construction will use the HTTP header value - this will overwrite it)
        * @param $agent_string The value for the User Agent
        */
        public function setUserAgent($agent_string) {
            $this->reset();
            $this->_agent = $agent_string;
            $this->determine();
        }
        /**
         * Used to determine if the browser is actually "chromeframe"
         * @since 1.7
         * @return boolean True if the browser is using chromeframe
         */
        public function isChromeFrame() {
            return( strpos($this->_agent,"chromeframe") !== false );
        }
        /**
        * Returns a formatted string with a summary of the details of the browser.
        * @return string formatted string with a summary of the browser
        */
        public function __toString() {
            return "<strong>Browser Name:</strong>{$this->getBrowser()}<br/>\n" .
                   "<strong>Browser Version:</strong>{$this->getVersion()}<br/>\n" .
                   "<strong>Browser User Agent String:</strong>{$this->getUserAgent()}<br/>\n" .
                   "<strong>Platform:</strong>{$this->getPlatform()}<br/>";
        }
        /**
         * Protected routine to calculate and determine what the browser is in use (including platform)
         */
        protected function determine() {
            $this->checkPlatform();
            $this->checkBrowsers();
            $this->checkForAol();
        }
        /**
         * Protected routine to determine the browser type
         * @return boolean True if the browser was detected otherwise false
         */
         protected function checkBrowsers() {
            return (
                // well-known, well-used
                // Special Notes:
                // (1) Opera must be checked before FireFox due to the odd
                //     user agents used in some older versions of Opera
                // (2) WebTV is strapped onto Internet Explorer so we must
                //     check for WebTV before IE
                // (3) (deprecated) Galeon is based on Firefox and needs to be
                //     tested before Firefox is tested
                // (4) OmniWeb is based on Safari so OmniWeb check must occur
                //     before Safari
                // (5) Netscape 9+ is based on Firefox so Netscape checks
                //     before FireFox are necessary
                $this->checkBrowserWebTv() ||
                $this->checkBrowserInternetExplorer() ||
                $this->checkBrowserOpera() ||
                $this->checkBrowserGaleon() ||
                $this->checkBrowserNetscapeNavigator9Plus() ||
                $this->checkBrowserFirefox() ||
                $this->checkBrowserChrome() ||
                $this->checkBrowserOmniWeb() ||
 
                // common mobile
                $this->checkBrowserAndroid() ||
                $this->checkBrowseriPad() ||
                $this->checkBrowseriPod() ||
                $this->checkBrowseriPhone() ||
                $this->checkBrowserBlackBerry() ||
                $this->checkBrowserNokia() ||
 
                // common bots
                $this->checkBrowserGoogleBot() ||
                $this->checkBrowserMSNBot() ||
                $this->checkBrowserSlurp() ||
 
                // WebKit base check (post mobile and others)
                $this->checkBrowserSafari() ||
                 
                // everyone else
                $this->checkBrowserNetPositive() ||
                $this->checkBrowserFirebird() ||
                $this->checkBrowserKonqueror() ||
                $this->checkBrowserIcab() ||
                $this->checkBrowserPhoenix() ||
                $this->checkBrowserAmaya() ||
                $this->checkBrowserLynx() ||
                $this->checkBrowserShiretoko() ||
                $this->checkBrowserIceCat() ||
                $this->checkBrowserW3CValidator() ||
                $this->checkBrowserMozilla() /* Mozilla is such an open standard that you must check it last */
            );
        }
 
        /**
         * Determine if the user is using a BlackBerry (last updated 1.7)
         * @return boolean True if the browser is the BlackBerry browser otherwise false
         */
        protected function checkBrowserBlackBerry() {
            if( stripos($this->_agent,'blackberry') !== false ) {
                $aresult = explode("/",stristr($this->_agent,"BlackBerry"));
                $aversion = explode(' ',$aresult[1]);
                $this->setVersion($aversion[0]);
                $this->_browser_name = self::BROWSER_BLACKBERRY;
                $this->setMobile(true);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the user is using an AOL User Agent (last updated 1.7)
         * @return boolean True if the browser is from AOL otherwise false
         */
        protected function checkForAol() {
            $this->setAol(false);
            $this->setAolVersion(self::VERSION_UNKNOWN);
 
            if( stripos($this->_agent,'aol') !== false ) {
                $aversion = explode(' ',stristr($this->_agent, 'AOL'));
                $this->setAol(true);
                $this->setAolVersion(preg_replace('/[^0-9\.a-z]/i', '', $aversion[1]));
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is the GoogleBot or not (last updated 1.7)
         * @return boolean True if the browser is the GoogletBot otherwise false
         */
        protected function checkBrowserGoogleBot() {
            if( stripos($this->_agent,'googlebot') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'googlebot'));
                $aversion = explode(' ',$aresult[1]);
                $this->setVersion(str_replace(';','',$aversion[0]));
                $this->_browser_name = self::BROWSER_GOOGLEBOT;
                $this->setRobot(true);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is the MSNBot or not (last updated 1.9)
         * @return boolean True if the browser is the MSNBot otherwise false
         */
        protected function checkBrowserMSNBot() {
            if( stripos($this->_agent,"msnbot") !== false ) {
                $aresult = explode("/",stristr($this->_agent,"msnbot"));
                $aversion = explode(" ",$aresult[1]);
                $this->setVersion(str_replace(";","",$aversion[0]));
                $this->_browser_name = self::BROWSER_MSNBOT;
                $this->setRobot(true);
                return true;
            }
            return false;
        }       
         
        /**
         * Determine if the browser is the W3C Validator or not (last updated 1.7)
         * @return boolean True if the browser is the W3C Validator otherwise false
         */
        protected function checkBrowserW3CValidator() {
            if( stripos($this->_agent,'W3C-checklink') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'W3C-checklink'));
                $aversion = explode(' ',$aresult[1]);
                $this->setVersion($aversion[0]);
                $this->_browser_name = self::BROWSER_W3CVALIDATOR;
                return true;
            }
            else if( stripos($this->_agent,'W3C_Validator') !== false ) {
                // Some of the Validator versions do not delineate w/ a slash - add it back in
                $ua = str_replace("W3C_Validator ", "W3C_Validator/", $this->_agent);
                $aresult = explode('/',stristr($ua,'W3C_Validator'));
                $aversion = explode(' ',$aresult[1]);
                $this->setVersion($aversion[0]);
                $this->_browser_name = self::BROWSER_W3CVALIDATOR;
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is the Yahoo! Slurp Robot or not (last updated 1.7)
         * @return boolean True if the browser is the Yahoo! Slurp Robot otherwise false
         */
        protected function checkBrowserSlurp() {
            if( stripos($this->_agent,'slurp') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'Slurp'));
                $aversion = explode(' ',$aresult[1]);
                $this->setVersion($aversion[0]);
                $this->_browser_name = self::BROWSER_SLURP;
                $this->setRobot(true);
                $this->setMobile(false);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Internet Explorer or not (last updated 1.7)
         * @return boolean True if the browser is Internet Explorer otherwise false
         */
        protected function checkBrowserInternetExplorer() {
 
            // Test for v1 - v1.5 IE
            if( stripos($this->_agent,'microsoft internet explorer') !== false ) {
                $this->setBrowser(self::BROWSER_IE);
                $this->setVersion('1.0');
                $aresult = stristr($this->_agent, '/');
                if( preg_match('/308|425|426|474|0b1/i', $aresult) ) {
                    $this->setVersion('1.5');
                }
                return true;
            }
            // Test for versions > 1.5
            else if( stripos($this->_agent,'msie') !== false && stripos($this->_agent,'opera') === false ) {
                // See if the browser is the odd MSN Explorer
                if( stripos($this->_agent,'msnb') !== false ) {
                    $aresult = explode(' ',stristr(str_replace(';','; ',$this->_agent),'MSN'));
                    $this->setBrowser( self::BROWSER_MSN );
                    $this->setVersion(str_replace(array('(',')',';'),'',$aresult[1]));
                    return true;
                }
                $aresult = explode(' ',stristr(str_replace(';','; ',$this->_agent),'msie'));
                $this->setBrowser( self::BROWSER_IE );
                $this->setVersion(str_replace(array('(',')',';'),'',$aresult[1]));
                return true;
            }
            // Test for Pocket IE
            else if( stripos($this->_agent,'mspie') !== false || stripos($this->_agent,'pocket') !== false ) {
                $aresult = explode(' ',stristr($this->_agent,'mspie'));
                $this->setPlatform( self::PLATFORM_WINDOWS_CE );
                $this->setBrowser( self::BROWSER_POCKET_IE );
                $this->setMobile(true);
 
                if( stripos($this->_agent,'mspie') !== false ) {
                    $this->setVersion($aresult[1]);
                }
                else {
                    $aversion = explode('/',$this->_agent);
                    $this->setVersion($aversion[1]);
                }
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Opera or not (last updated 1.7)
         * @return boolean True if the browser is Opera otherwise false
         */
        protected function checkBrowserOpera() {
            if( stripos($this->_agent,'opera mini') !== false ) {
                $resultant = stristr($this->_agent, 'opera mini');
                if( preg_match('/\//',$resultant) ) {
                    $aresult = explode('/',$resultant);
                    $aversion = explode(' ',$aresult[1]);
                    $this->setVersion($aversion[0]);
                }
                else {
                    $aversion = explode(' ',stristr($resultant,'opera mini'));
                    $this->setVersion($aversion[1]);
                }
                $this->_browser_name = self::BROWSER_OPERA_MINI;
                $this->setMobile(true);
                return true;
            }
            else if( stripos($this->_agent,'opera') !== false ) {
                $resultant = stristr($this->_agent, 'opera');
                if( preg_match('/Version\/(10.*)$/',$resultant,$matches) ) {
                    $this->setVersion($matches[1]);
                }
                else if( preg_match('/\//',$resultant) ) {
                    $aresult = explode('/',str_replace("("," ",$resultant));
                    $aversion = explode(' ',$aresult[1]);
                    $this->setVersion($aversion[0]);
                }
                else {
                    $aversion = explode(' ',stristr($resultant,'opera'));
                    $this->setVersion(isset($aversion[1])?$aversion[1]:"");
                }
                $this->_browser_name = self::BROWSER_OPERA;
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Chrome or not (last updated 1.7)
         * @return boolean True if the browser is Chrome otherwise false
         */
        protected function checkBrowserChrome() {
            if( stripos($this->_agent,'Chrome') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'Chrome'));
                $aversion = explode(' ',$aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_CHROME);
                return true;
            }
            return false;
        }
 
 
        /**
         * Determine if the browser is WebTv or not (last updated 1.7)
         * @return boolean True if the browser is WebTv otherwise false
         */
        protected function checkBrowserWebTv() {
            if( stripos($this->_agent,'webtv') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'webtv'));
                $aversion = explode(' ',$aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_WEBTV);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is NetPositive or not (last updated 1.7)
         * @return boolean True if the browser is NetPositive otherwise false
         */
        protected function checkBrowserNetPositive() {
            if( stripos($this->_agent,'NetPositive') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'NetPositive'));
                $aversion = explode(' ',$aresult[1]);
                $this->setVersion(str_replace(array('(',')',';'),'',$aversion[0]));
                $this->setBrowser(self::BROWSER_NETPOSITIVE);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Galeon or not (last updated 1.7)
         * @return boolean True if the browser is Galeon otherwise false
         */
        protected function checkBrowserGaleon() {
            if( stripos($this->_agent,'galeon') !== false ) {
                $aresult = explode(' ',stristr($this->_agent,'galeon'));
                $aversion = explode('/',$aresult[0]);
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_GALEON);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Konqueror or not (last updated 1.7)
         * @return boolean True if the browser is Konqueror otherwise false
         */
        protected function checkBrowserKonqueror() {
            if( stripos($this->_agent,'Konqueror') !== false ) {
                $aresult = explode(' ',stristr($this->_agent,'Konqueror'));
                $aversion = explode('/',$aresult[0]);
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_KONQUEROR);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is iCab or not (last updated 1.7)
         * @return boolean True if the browser is iCab otherwise false
         */
        protected function checkBrowserIcab() {
            if( stripos($this->_agent,'icab') !== false ) {
                $aversion = explode(' ',stristr(str_replace('/',' ',$this->_agent),'icab'));
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_ICAB);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is OmniWeb or not (last updated 1.7)
         * @return boolean True if the browser is OmniWeb otherwise false
         */
        protected function checkBrowserOmniWeb() {
            if( stripos($this->_agent,'omniweb') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'omniweb'));
                $aversion = explode(' ',isset($aresult[1])?$aresult[1]:"");
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_OMNIWEB);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Phoenix or not (last updated 1.7)
         * @return boolean True if the browser is Phoenix otherwise false
         */
        protected function checkBrowserPhoenix() {
            if( stripos($this->_agent,'Phoenix') !== false ) {
                $aversion = explode('/',stristr($this->_agent,'Phoenix'));
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_PHOENIX);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Firebird or not (last updated 1.7)
         * @return boolean True if the browser is Firebird otherwise false
         */
        protected function checkBrowserFirebird() {
            if( stripos($this->_agent,'Firebird') !== false ) {
                $aversion = explode('/',stristr($this->_agent,'Firebird'));
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_FIREBIRD);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Netscape Navigator 9+ or not (last updated 1.7)
         * NOTE: (http://browser.netscape.com/ - Official support ended on March 1st, 2008)
         * @return boolean True if the browser is Netscape Navigator 9+ otherwise false
         */
        protected function checkBrowserNetscapeNavigator9Plus() {
            if( stripos($this->_agent,'Firefox') !== false && preg_match('/Navigator\/([^ ]*)/i',$this->_agent,$matches) ) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_NETSCAPE_NAVIGATOR);
                return true;
            }
            else if( stripos($this->_agent,'Firefox') === false && preg_match('/Netscape6?\/([^ ]*)/i',$this->_agent,$matches) ) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_NETSCAPE_NAVIGATOR);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Shiretoko or not (https://wiki.mozilla.org/Projects/shiretoko) (last updated 1.7)
         * @return boolean True if the browser is Shiretoko otherwise false
         */
        protected function checkBrowserShiretoko() {
            if( stripos($this->_agent,'Mozilla') !== false && preg_match('/Shiretoko\/([^ ]*)/i',$this->_agent,$matches) ) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_SHIRETOKO);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Ice Cat or not (http://en.wikipedia.org/wiki/GNU_IceCat) (last updated 1.7)
         * @return boolean True if the browser is Ice Cat otherwise false
         */
        protected function checkBrowserIceCat() {
            if( stripos($this->_agent,'Mozilla') !== false && preg_match('/IceCat\/([^ ]*)/i',$this->_agent,$matches) ) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_ICECAT);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Nokia or not (last updated 1.7)
         * @return boolean True if the browser is Nokia otherwise false
         */
        protected function checkBrowserNokia() {
            if( preg_match("/Nokia([^\/]+)\/([^ SP]+)/i",$this->_agent,$matches) ) {
                $this->setVersion($matches[2]);
                if( stripos($this->_agent,'Series60') !== false || strpos($this->_agent,'S60') !== false ) {
                    $this->setBrowser(self::BROWSER_NOKIA_S60);
                }
                else {
                    $this->setBrowser( self::BROWSER_NOKIA );
                }
                $this->setMobile(true);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Firefox or not (last updated 1.7)
         * @return boolean True if the browser is Firefox otherwise false
         */
        protected function checkBrowserFirefox() {
            if( stripos($this->_agent,'safari') === false ) {
                if( preg_match("/Firefox[\/ \(]([^ ;\)]+)/i",$this->_agent,$matches) ) {
                    $this->setVersion($matches[1]);
                    $this->setBrowser(self::BROWSER_FIREFOX);
                    return true;
                }
                else if( preg_match("/Firefox$/i",$this->_agent,$matches) ) {
                    $this->setVersion("");
                    $this->setBrowser(self::BROWSER_FIREFOX);
                    return true;
                }
            }
            return false;
        }
 
        /**
         * Determine if the browser is Firefox or not (last updated 1.7)
         * @return boolean True if the browser is Firefox otherwise false
         */
        protected function checkBrowserIceweasel() {
            if( stripos($this->_agent,'Iceweasel') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'Iceweasel'));
                $aversion = explode(' ',$aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_ICEWEASEL);
                return true;
            }
            return false;
        }
        /**
         * Determine if the browser is Mozilla or not (last updated 1.7)
         * @return boolean True if the browser is Mozilla otherwise false
         */
        protected function checkBrowserMozilla() {
            if( stripos($this->_agent,'mozilla') !== false  && preg_match('/rv:[0-9].[0-9][a-b]?/i',$this->_agent) && stripos($this->_agent,'netscape') === false) {
                $aversion = explode(' ',stristr($this->_agent,'rv:'));
                preg_match('/rv:[0-9].[0-9][a-b]?/i',$this->_agent,$aversion);
                $this->setVersion(str_replace('rv:','',$aversion[0]));
                $this->setBrowser(self::BROWSER_MOZILLA);
                return true;
            }
            else if( stripos($this->_agent,'mozilla') !== false && preg_match('/rv:[0-9]\.[0-9]/i',$this->_agent) && stripos($this->_agent,'netscape') === false ) {
                $aversion = explode('',stristr($this->_agent,'rv:'));
                $this->setVersion(str_replace('rv:','',$aversion[0]));
                $this->setBrowser(self::BROWSER_MOZILLA);
                return true;
            }
            else if( stripos($this->_agent,'mozilla') !== false  && preg_match('/mozilla\/([^ ]*)/i',$this->_agent,$matches) && stripos($this->_agent,'netscape') === false ) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_MOZILLA);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Lynx or not (last updated 1.7)
         * @return boolean True if the browser is Lynx otherwise false
         */
        protected function checkBrowserLynx() {
            if( stripos($this->_agent,'lynx') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'Lynx'));
                $aversion = explode(' ',(isset($aresult[1])?$aresult[1]:""));
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_LYNX);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Amaya or not (last updated 1.7)
         * @return boolean True if the browser is Amaya otherwise false
         */
        protected function checkBrowserAmaya() {
            if( stripos($this->_agent,'amaya') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'Amaya'));
                $aversion = explode(' ',$aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_AMAYA);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Safari or not (last updated 1.7)
         * @return boolean True if the browser is Safari otherwise false
         */
        protected function checkBrowserSafari() {
            if( stripos($this->_agent,'Safari') !== false && stripos($this->_agent,'iPhone') === false && stripos($this->_agent,'iPod') === false ) {
                $aresult = explode('/',stristr($this->_agent,'Version'));
                if( isset($aresult[1]) ) {
                    $aversion = explode(' ',$aresult[1]);
                    $this->setVersion($aversion[0]);
                }
                else {
                    $this->setVersion(self::VERSION_UNKNOWN);
                }
                $this->setBrowser(self::BROWSER_SAFARI);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is iPhone or not (last updated 1.7)
         * @return boolean True if the browser is iPhone otherwise false
         */
        protected function checkBrowseriPhone() {
            if( stripos($this->_agent,'iPhone') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'Version'));
                if( isset($aresult[1]) ) {
                    $aversion = explode(' ',$aresult[1]);
                    $this->setVersion($aversion[0]);
                }
                else {
                    $this->setVersion(self::VERSION_UNKNOWN);
                }
                $this->setMobile(true);
                $this->setBrowser(self::BROWSER_IPHONE);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is iPod or not (last updated 1.7)
         * @return boolean True if the browser is iPod otherwise false
         */
        protected function checkBrowseriPad() {
            if( stripos($this->_agent,'iPad') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'Version'));
                if( isset($aresult[1]) ) {
                    $aversion = explode(' ',$aresult[1]);
                    $this->setVersion($aversion[0]);
                }
                else {
                    $this->setVersion(self::VERSION_UNKNOWN);
                }
                $this->setMobile(true);
                $this->setBrowser(self::BROWSER_IPAD);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is iPod or not (last updated 1.7)
         * @return boolean True if the browser is iPod otherwise false
         */
        protected function checkBrowseriPod() {
            if( stripos($this->_agent,'iPod') !== false ) {
                $aresult = explode('/',stristr($this->_agent,'Version'));
                if( isset($aresult[1]) ) {
                    $aversion = explode(' ',$aresult[1]);
                    $this->setVersion($aversion[0]);
                }
                else {
                    $this->setVersion(self::VERSION_UNKNOWN);
                }
                $this->setMobile(true);
                $this->setBrowser(self::BROWSER_IPOD);
                return true;
            }
            return false;
        }
 
        /**
         * Determine if the browser is Android or not (last updated 1.7)
         * @return boolean True if the browser is Android otherwise false
         */
        protected function checkBrowserAndroid() {
            if( stripos($this->_agent,'Android') !== false ) {
                $aresult = explode(' ',stristr($this->_agent,'Android'));
                if( isset($aresult[1]) ) {
                    $aversion = explode(' ',$aresult[1]);
                    $this->setVersion($aversion[0]);
                }
                else {
                    $this->setVersion(self::VERSION_UNKNOWN);
                }
                $this->setMobile(true);
                $this->setBrowser(self::BROWSER_ANDROID);
                return true;
            }
            return false;
        }
 
        /**
         * Determine the user's platform (last updated 1.7)
         */
        protected function checkPlatform() {
            if( stripos($this->_agent, 'windows') !== false ) {
                $this->_platform = self::PLATFORM_WINDOWS;
            }
            else if( stripos($this->_agent, 'iPad') !== false ) {
                $this->_platform = self::PLATFORM_IPAD;
            }
            else if( stripos($this->_agent, 'iPod') !== false ) {
                $this->_platform = self::PLATFORM_IPOD;
            }
            else if( stripos($this->_agent, 'iPhone') !== false ) {
                $this->_platform = self::PLATFORM_IPHONE;
            }
            elseif( stripos($this->_agent, 'mac') !== false ) {
                $this->_platform = self::PLATFORM_APPLE;
            }
            elseif( stripos($this->_agent, 'android') !== false ) {
                $this->_platform = self::PLATFORM_ANDROID;
            }
            elseif( stripos($this->_agent, 'linux') !== false ) {
                $this->_platform = self::PLATFORM_LINUX;
            }
            else if( stripos($this->_agent, 'Nokia') !== false ) {
                $this->_platform = self::PLATFORM_NOKIA;
            }
            else if( stripos($this->_agent, 'BlackBerry') !== false ) {
                $this->_platform = self::PLATFORM_BLACKBERRY;
            }
            elseif( stripos($this->_agent,'FreeBSD') !== false ) {
                $this->_platform = self::PLATFORM_FREEBSD;
            }
            elseif( stripos($this->_agent,'OpenBSD') !== false ) {
                $this->_platform = self::PLATFORM_OPENBSD;
            }
            elseif( stripos($this->_agent,'NetBSD') !== false ) {
                $this->_platform = self::PLATFORM_NETBSD;
            }
            elseif( stripos($this->_agent, 'OpenSolaris') !== false ) {
                $this->_platform = self::PLATFORM_OPENSOLARIS;
            }
            elseif( stripos($this->_agent, 'SunOS') !== false ) {
                $this->_platform = self::PLATFORM_SUNOS;
            }
            elseif( stripos($this->_agent, 'OS\/2') !== false ) {
                $this->_platform = self::PLATFORM_OS2;
            }
            elseif( stripos($this->_agent, 'BeOS') !== false ) {
                $this->_platform = self::PLATFORM_BEOS;
            }
            elseif( stripos($this->_agent, 'win') !== false ) {
                $this->_platform = self::PLATFORM_WINDOWS;
            }
 
        }
    }



?>