<?php 

/*
    COJM Courier Online Operations Management
	expenseview.php - P+L Search
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

if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include "changejob.php";

$ifpaid='';
$orderby='';

if (isset($_GET['clientid'])) { $clientid=$_GET['clientid']; } else { $clientid=''; }
if (isset($_GET['viewtype'])) { $viewtype=$_GET['viewtype']; } else { $viewtype=''; }
if (isset($_GET['ifpaid'])) { $ifpaid=$_GET['ifpaid']; }
if (isset($_GET['orderby'])) { $orderby=$_GET['orderby']; }

if (isset($_GET['from'])) {
    $start=trim($_GET['from']); 

    $smallexpensename ='';
    $tstart = str_replace("%2F", ":", "$start", $count);
    $tstart = str_replace("/", ":", "$start", $count);
    $tstart = str_replace(",", ":", "$tstart", $count);

    if ($tstart) {
        $temp_ar=explode(":",$tstart); 
        $day=$temp_ar['0']; $month=$temp_ar['1']; 
        $year=$temp_ar['2']; 

        $hour='00';
        $minutes= '00';
        $second='00';
        $sqlstart= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));

        if ($year) { $inputstart=$day.'/'.$month.'/'.$year; }
    } else {
        $sqlstart='';
    }
} else {
    $start='';
}

if (isset($_GET['to'])) {
    $end=trim($_GET['to']);
    
    if ($end) {
    
        $tend = str_replace("%2F", ":", "$end", $count);
        $tend = str_replace("/", ":", "$end", $count);
        $tend = str_replace(",", ":", "$tend", $count);
        $temp_ar=explode(":",$tend); 
        $day=$temp_ar[0]; $month=$temp_ar[1]; 
        $year=$temp_ar[2]; 
    
        if ($year) { $inputend=$day.'/'.$month.'/'.$year; }
        $sqlend= date("Y-m-d H:i:s", mktime('23', '59', '59', $month, $day, $year));
        if (($sqlstart) and (!$year)) { $sqlend='3000-12-25 23:59:59'; } else { $sqlend=''; }
    
    } 
    else { $sqlend=''; }

}

$invoicemenu = "1";
$adminmenu = "0";
$filename='expenseview.php';


if (isset($_GET['view'])) { $view=trim($_GET['view']); } else { $view=''; }
if (isset($_POST['view'])) { $view=trim($_POST['view']); }


if (isset($_GET['searchphrase'])) { $searchphrase=trim($_GET['searchphrase']); } else { $searchphrase=''; }
if (isset($_POST['searchphrase'])) { $searchphrase=trim($_POST['searchphrase']); }






if (isset($_GET['thiscyclist'])) { $thiscyclist=trim($_GET['thiscyclist']); } else { $thiscyclist=''; }
if (isset($_GET['paymentmethod'])) { $paymentmethod=trim($_GET['paymentmethod']); } else { $paymentmethod=''; }


if (($paymentmethod<>'expc1') and ($paymentmethod<>'expc2') and ($paymentmethod<>'expc3') and ($paymentmethod<>'expc4') and ($paymentmethod<>'expc5') and ($paymentmethod<>'expc6')) {
    $paymentmethod='';
}




if (isset($_GET['collectyear'])) { $year=trim($_GET['collectyear']); } else { if (isset($_GET['collectyear'])) { $year=trim($_GET['collectyear']); }}
if (isset($_GET['collectmonth'])) { $month=trim($_GET['collectmonth']); } else {if (isset($_GET['collectmonth'])) { $month=trim($_GET['collectmonth']);} }
if (isset($_GET['collectday'])) { $day=trim($_GET['collectday']); } else {if (isset($_GET['collectday'])) { $day=trim($_GET['collectday']);} }

if (isset($year)) {
    $collectionsuntildate = $year . "-" . $month . "-" . $day . " 23:59:59";
    $inputend=$day.'/'.$month.'/'.$year; 

} else { $collectionsuntildate=''; }
  
if (isset($_GET['from'])) { } else { if (isset($year)) { $inputstart=$day.'/'.$month.'/'.$year; } }

if (isset($_GET['deliveryear']))  {
    $year=trim($_GET['deliveryear']);
} else {
    if (isset($_GET['deliveryear'])) {
        $year=trim($_GET['deliveryear']);
    }
}
if (isset($_GET['delivermonth'])) {
    $month=trim($_GET['delivermonth']);
} else {
    if (isset($_GET['delivermonth'])) {
        $month=$_GET['delivermonth'];
    }
}
if (isset($_GET['deliverday']))   {
    $day=trim($_GET['deliverday']);
} else {
    if (isset($_GET['deliverday'])) {
        $day=$_GET['deliverday'];
    }
}

$hour="00"; $minutes="00"; 

if (isset($year)) {
$collectionsfromdate = $year . "-" . $month . "-" . $day . " " . $hour . ":" . $minutes . ":00";
}

if (isset($_GET['from'])) { 
$collectionsfromdate=$sqlstart;
} else {

if (isset($year)) {
$inputstart=$day.'/'.$month.'/'.$year;
}
}


if (isset($_GET['searchexpensecode'])) { $searchexpensecode=trim($_GET['searchexpensecode']); }

else { $searchexpensecode=''; }


if (isset($inputstart)) { if ($inputstart=='//') { $inputstart=''; } } else { $inputstart=''; }
if (isset($inputend)) { if ($inputend=='//') { $inputend='';} } else { $inputend=''; }

$temptab='';
$vattablecost='0';


?><!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<title>COJM : P+L Search</title>
<link rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<link rel="stylesheet" href="css/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<script type="text/javascript" src="js/jquery-ui.1.8.7.min.js"></script>
<script type="text/javascript" src="js/jquery.floatThead.js"></script>
</head>
<body>
<? 

$adminmenu = "0";
$invoicemenu='1';

include "cojmmenu.php"; ?>
<div class="Post">
<form action="expenseview.php" method="get">
<div class="ui-state-highlight ui-corner-all p15" >

From <input class="ui-state-highlight ui-corner-all pad" size="11" type="text" name="from" value="<?php echo $inputstart; ?>" id="rangeBa" />
To <input class="ui-state-highlight ui-corner-all pad"  size="11" type="text" name="to" value="<?php echo $inputend; ?>" id="rangeBb" />

<?php

echo '<select id="view" name="view" class="ui-state-highlight ui-corner-left">

<option value="statmnt" ';
if ($view=='statmnt') {
    echo ' selected ';
}
echo '> Statement View </option>

<option value="expenses" ';
if ($view=='expenses') {
    echo ' selected ';
}
echo '> Just Expenses </option>

<option value="payments" ';
if ($view=='payments') {
    echo ' selected ';
}
echo '> Just Payments </option>
</select>
';

?>


<select class="ui-state-highlight ui-corner-left" name="orderby">
    <option value="">Order by Date </option>
    <option
    <?php if ($orderby=='highlo') { echo 'selected'; } ?>
        value="highlo">High to Low</option>
</select>

<input 
id="searchphrase" 
name="searchphrase" 
class="ui-state-highlight ui-corner-all pad" 
placeholder="Search Values"
title="Search Amounts, Comments, Expenses Paid To  "
value="<?php echo htmlspecialchars($searchphrase); ?>" 
size="14" 
type="text">

<button type="submit" >Search</button>

<hr />

<div id="expensesearchbar" <?php

if ($view<>'expenses') {
    echo ' class="hideuntilneeded" ';
}

?> 
>

Expense Category
<select class="ui-state-highlight ui-corner-left" name="searchexpensecode" id="searchexpensecode">
<option value="all">All Expense Departments</option>        
<?php 

$sql = "SELECT expensecode, smallexpensename, expensedescription FROM expensecodes ORDER BY expensecode";
    $prep = $dbh->query($sql);
    $stmt = $prep->fetchAll();
        
    foreach ( $stmt as $row) {
    
    $expensedescription = htmlspecialchars ($row['expensedescription']);
    $expensecode = htmlspecialchars ($row['expensecode']); 
    $smallexpensename = htmlspecialchars ($row['smallexpensename']); 
    print" <option ";
    if ($expensecode == $searchexpensecode) {echo "SELECTED "; }
    print ' value="'.$expensecode.'">'.$smallexpensename.'</option>';
} ?>
        </select>
        
        
Expense Method
<select class="ui-state-highlight ui-corner-left" name="paymentmethod" > 
            <option value="">All Expense Methods </option>
<?php 
 if ($globalprefrow['gexpc1']){ echo '<option value="expc1" '; if ('expc1'==$paymentmethod) { echo 'selected'; }  echo '> '.$globalprefrow['gexpc1'].'</option>'; } 
 if ($globalprefrow['gexpc2']){ echo '<option value="expc2" '; if ('expc2'==$paymentmethod) { echo 'selected'; }  echo '> '.$globalprefrow['gexpc2'].'</option>'; }  
 if ($globalprefrow['gexpc3']){ echo '<option value="expc3" '; if ('expc3'==$paymentmethod) { echo 'selected'; }  echo '> '.$globalprefrow['gexpc3'].'</option>'; } 
 if ($globalprefrow['gexpc4']){ echo '<option value="expc4" '; if ('expc4'==$paymentmethod) { echo 'selected'; }  echo '> '.$globalprefrow['gexpc4'].'</option>'; }  
 if ($globalprefrow['gexpc5']){ echo '<option value="expc5" '; if ('expc5'==$paymentmethod) { echo 'selected'; }  echo '> '.$globalprefrow['gexpc5'].'</option>'; } 
 if ($globalprefrow['gexpc6']){ echo '<option value="expc6" '; if ('expc6'==$paymentmethod) { echo 'selected'; }  echo '> '.$globalprefrow['gexpc6'].'</option>'; } 
 
?>

</select>

<select class="ui-state-highlight ui-corner-left" name="ifpaid">
    <option value="">Paid &amp; Future Expenses</option>
    <option <?php if ($ifpaid=='paid') { echo 'selected'; } ?> value="paid">Paid Expenses</option>
    <option <?php if ($ifpaid=='future') { echo 'selected'; } ?> value="future">Future Expenses</option>
</select>

<?php



    echo '<select class="ui-state-highlight ui-corner-left';
    if ($searchexpensecode<>6) {
        echo ' hideuntilneeded';
    }
    echo '" name="thiscyclist" id="thiscyclist"> ' ;
    echo '<option value="All" >All '. $globalprefrow['glob5']  .'s </option>';
 
    $sql = "SELECT CyclistID, cojmname FROM Cyclist WHERE isactive='1' ORDER BY CyclistID"; 
    
    $prep = $dbh->query($sql);
    $stmt = $prep->fetchAll();
        
    foreach ( $stmt as $row) {
        print ("<option ");
        if ($row['CyclistID'] == $thiscyclist) {
            echo " SELECTED ";
        }
        print 'value="'.$row['CyclistID'].'">'.$row['cojmname'].'</option>';
    }
    print ("</select>"); 

    echo '
    <select name="viewtype" id="viewtype" class="ui-state-highlight ui-corner-left';
    
    if ($searchexpensecode<>6) {
        echo ' hideuntilneeded';
    }
    
    echo '" >
        <option '; if ($viewtype=='normal')   { echo 'selected'; } echo ' value="normal">Normal View</option>
        <option '; if ($viewtype=='view2') { echo 'selected'; } echo ' value="view2">Print for Rider</option>
    </select> ';



echo ' </div>
</div>
</form> ';

if ($view=='expenses') {

    $conditions = array();
    $parameters = array();
    $where = "";
    $numpayments=0;
    $paymentcost=0;    

    if ($collectionsfromdate) {
        $conditions[] = " expenses.expensedate >= :sqlstart ";
        $parameters[":sqlstart"] = $collectionsfromdate;
    }
    
    if ($collectionsuntildate) {
        $conditions[] = " expenses.expensedate <= :sqlend ";
        $parameters[":sqlend"] = $collectionsuntildate;
    }

    
    if ($searchexpensecode<>'all') {
        $conditions[] = " expenses.expensecode = :expensecode ";
        $parameters[":expensecode"] = $searchexpensecode;        
    }
    
    if ($ifpaid=='paid') {
        $conditions[] = " expenses.paid='1' ";
        }
        
    if ($ifpaid=='future') {
        $conditions[] = " expenses.paid='0' ";
        }
    
    if ($paymentmethod) {
        $conditions[] = " expenses.$paymentmethod <> '0.00'  ";
    }

    
    if (($searchexpensecode==6) AND ($thiscyclist>1)) {
        $conditions[] = " expenses.cyclistref = :cyclistref ";
        $parameters[":cyclistref"] = $thiscyclist;        
    }    
    
    
    if ($searchphrase) {
        $conditions[] = " ( 
        expenses.expensecost LIKE :testrefa 
        OR expenses.whoto LIKE :testrefb 
        OR expenses.description LIKE :testrefc 
        ) ";
        $parameters[":testrefa"] = "%".$searchphrase."%";
        $parameters[":testrefb"] = "%".$searchphrase."%";
        $parameters[":testrefc"] = "%".$searchphrase."%";    
    }
    
    
    
    
    if (count($conditions) > 0) {
        $where = implode(' AND ', $conditions);
    }

    $query = " SELECT *
            FROM expenses
            left JOIN Cyclist ON expenses.cyclistref = Cyclist.CyclistID 
            left join expensecodes ON expenses.expensecode = expensecodes.expensecode 
    " . ($where != "" ? " WHERE $where" : "");

    
    if ($orderby=='highlo') {
        $query .= " ORDER BY expensecost DESC";    
    } else {
        $query .= " ORDER BY expensedate ASC";  
    }

    
    try {
        if (empty($parameters)) {
            $result = $dbh->query($query);
        }
        else {
            $statement = $dbh->prepare($query);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $result = $statement->fetchAll();
        }
    }
    catch(Exception $ex) {
        echo $ex->getMessage();
    }
    

    
    
    if ($result) {

        $temptab='<br /><div class="vpad"> </div>
        <table class="acc';
        
        if ($clientview<>'client') {
            $temptab.= ' biggertext';
        }
        
        $temptab.='" id="expenseview">        
        <thead>
        <tr>
        <th scope="col">Date</th>
        <th scope="col">Ref</th>
        <th title="Incl. VAT" scope="col">Net </th>
        <th scope="col">VAT </th>';
        
        if ($viewtype<>'view2') {
            $temptab.= '
            <th scope="col">Paid to</th>
            <th scope="col">Type</th>';
        }
        
        $temptab.= '
        <th scope="col">Method </th>
        <th scope="col"> </th>
        </tr>
        </thead>
        <tbody>';
        
        
        $tablecost='';
        $numexpenses=0;
        
        // echo ' payment : '.$paymentmethod;
        
        foreach ($result as $row ) {
            $numexpenses++;
                    
            $tablecost = $tablecost + $row["expensecost"];
            $vattablecost = $vattablecost + $row["expensevat"];	
                    
            $temptab.= ' <tr> ';
            $temptab.=  '<td class="rh">'.date('D j M Y', strtotime($row['expensedate'])).'</td>';
            $temptab.= ' <td> ';            
            
            
            if ($viewtype=='view2') { // view is rider report
                $temptab.= $row['expenseref']. ' '; 
            } else {
                $temptab.= ' <a href="singleexpense.php?expenseref='.$row['expenseref']. '" >'.$row['expenseref'].'</a>';
            }
            
            if ($row['paid']<'1') { $temptab.= ' UNPAID'; }
            
            $temptab.= '</td>
            <td class="rh"> &'. $globalprefrow['currencysymbol']. $row['expensecost'].
            '</td>
            <td> ';
            
            if ($row['expensevat']>'0') {  $temptab.= ' &'.$globalprefrow['currencysymbol']. $row['expensevat']; }
            
            $temptab.= ' </td> ';
            
            if ($viewtype<>'view2') { // NOT rider report view
                
                $temptab.=' <td>'.$row['whoto'];
                if ($row['CyclistID']<>'1') { $temptab.= ' '.$row['cojmname']; }                  

                $temptab.='</td>
                <td>'.$row['smallexpensename'].'  </td>';
            }
            
            $temptab.='<td>';
            
            if ($row['expc1']>0) { $temptab.= $globalprefrow['gexpc1']; } 
            if ($row['expc2']>0) { $temptab.= $globalprefrow['gexpc2']; } 
            if ($row['expc3']>0) { $temptab.= $globalprefrow['gexpc3']; } 
            if ($row['expc4']>0) { $temptab.= $globalprefrow['gexpc4']; } 
            if ($row['expc5']>0) { $temptab.= $globalprefrow['gexpc5']; } 
            if ($row['expc6']>0) { $temptab.= $globalprefrow['gexpc6'].' '.$row['chequeref']; } 
            $temptab.='</td><td>'. $row['description'].'</td>';
            $temptab.= '</tr>';
            
        } // ends expense ref loop
        
        
        $temptab.= '<tfoot>
        <tr>
        <td colspan="2"> Total </td>
        <td class="rh"> &'. $globalprefrow['currencysymbol']. number_format($tablecost, 2, '.', ',').'</td>
        <td class="rh"> &'. $globalprefrow['currencysymbol']. number_format($vattablecost, 2, '.', ',').'</td>
        ';
        if ($viewtype=='view2') { // rider report view
            $temptab.='<td > </td> ';
        } else {
            $temptab.='<td colspan="4"> </td> ';            
        }
        
        $temptab.='
        </tr>
        </tfoot> ';
        
        
        $temptab.='</tbody></table>';
        
        $grosscost=$tablecost-$vattablecost;
        $ttablecost= number_format($tablecost, 2, '.', ',');
        $tvattablecost= number_format($vattablecost, 2, '.', ',');
        $tgrosscost= number_format($grosscost, 2, '.', ',');

    }

    
    echo ' <div class="ui-state-highlight ui-corner-all clearfix undersearch" >
    <h3> '.$numexpenses.' Expense'; if ($numexpenses<>1) { echo 's'; } 
    echo ' </h3>
    <p title="Incl. VAT">Total Expenses : &'. $globalprefrow['currencysymbol']. number_format($tablecost, 2, '.', ',').'
    </p> ';
    
    if ($tvattablecost>'0') {
            echo ' <br /> Excl. VAT : &'. $globalprefrow['currencysymbol']. $tgrosscost;
            echo '<br /> Total Vat : &'. $globalprefrow['currencysymbol'] .$tvattablecost;
        }
    
    
    echo '
    </div> ';    

    
    if ($viewtype=='view2') { // rider report view
    
        $query = "SELECT * FROM Cyclist WHERE CyclistID=? LIMIT 0,1";
        $parameters = array($thiscyclist);
        $statement = $dbh->prepare($query);
        $statement->execute($parameters);
        $rowc = $statement->fetch(PDO::FETCH_ASSOC);

        if ($rowc) {
            
            echo '
            
            <div class="clear">
            <br />
            <hr />
            <br />
            
            '.$globalprefrow['courier9'].'
            <br />
            
            
            <div class="ui-state-highlight ui-corner-all clearfix undersearch">
            <h3>Payments from </h3>
            
            <h5>'.$globalprefrow['globalname'].'</h5>
            <p>'.$globalprefrow['myaddress1'].'
            <br />'.$globalprefrow['myaddress2'].'
            <br />'.$globalprefrow['myaddress3'].'
            <br />'.$globalprefrow['myaddress4'].'
            <br />'.$globalprefrow['myaddress5'].'</p>
            
            </div>
            
            <div class="ui-state-highlight ui-corner-all clearfix undersearch">
            
            <h3>Payments to</h3>
            
            <h5>'.$rowc['poshname'].'</h5>
            <p>'.$rowc['housenumber'].'
            <br />'.$rowc['streetname'].'
            <br />'.$rowc['city'].'
            <br />'.$rowc['postcode'].'</p>
            
            </div>
            
            <h2 class="clear">'.$start.' until '.$end.'</h3>
            
            </div> ';
            
        }
    
    } // ends rider report view
        
    
        echo $temptab;
        
        if ($viewtype=='view2') { // rider report view
            echo '<hr />';
            echo $globalprefrow['courier10']; 
            echo '<div style="clear:both;"> </div>
            <p>Report generated '.date("l jS F Y").'.</p>
            <hr />';
        } // ends rider report view
        
        
    
}



else if ($view=='statmnt') {
    $tablerow = array();
    $tablecost='';
    $numrows=0;
    $expenserows=0;
    $expensetotal=0;
    $duecount=0;
    $a.= '';    
    
    $conditions = array();
    $parameters = array();
    $where = "";
    
    $sql=" ";
    
    if ($collectionsfromdate)  {
        $conditions[] = " expensedate >= :sqlstart ";
        $parameters[":sqlstart"] = $collectionsfromdate;
    }
    
    
    if ($collectionsuntildate) {
        $conditions[] = " expensedate <= :sqlend ";
        $parameters[":sqlend"] = $collectionsuntildate;
    }


    
    if ($searchphrase) {
        $conditions[] = " ( 
        expenses.expensecost LIKE :testrefa 
        OR expenses.whoto LIKE :testrefb 
        OR expenses.description LIKE :testrefc 
        ) ";
        $parameters[":testrefa"] = "%".$searchphrase."%";
        $parameters[":testrefb"] = "%".$searchphrase."%";
        $parameters[":testrefc"] = "%".$searchphrase."%";    
    }    
    
    
    
    
    if (count($conditions) > 0) {
        $where = implode(' AND ', $conditions);
    }

    $query = "SELECT *
            FROM expenses
            left JOIN Cyclist ON expenses.cyclistref = Cyclist.CyclistID 
            left join expensecodes ON expenses.expensecode = expensecodes.expensecode 
    " . ($where != "" ? " WHERE $where" : "");

    try {
        if (empty($parameters)) {
            $result = $dbh->query($query);
        }
        else {
            $statement = $dbh->prepare($query);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $result = $statement->fetchAll();
        }
    }
    catch(Exception $ex) {
        echo $ex->getMessage();
    }
    

    if ($result) {
        foreach ($result as $row ) {
            $expenserows++;
            $comments='';
            $expensetotal=$expensetotal + $row["expensecost"] + $row["expensevat"];
            
            if ($row['CyclistID']<2) { $row['cojmname']=''; }

            $temptab='';
            if ($row['expc1']>0) { $temptab= $globalprefrow['gexpc1']; } 
            if ($row['expc2']>0) { $temptab= $globalprefrow['gexpc2']; } 
            if ($row['expc3']>0) { $temptab= $globalprefrow['gexpc3']; } 
            if ($row['expc4']>0) { $temptab= $globalprefrow['gexpc4']; } 
            if ($row['expc5']>0) { $temptab= $globalprefrow['gexpc5']; } 
            if ($row['expc6']>0) { $temptab= $globalprefrow['gexpc6'].' '.$row['chequeref']; } 

            
            
            // if ($row['paid']<'1') { $comments.= ' UNPAID '; }
            
            $comments.=$row['description'];
            
            $tablerow[] = array(
            "date"=>(strtotime($row['expensedate'])),
            "ref"=>$row['expenseref'],
            "amount"=>($row["expensecost"] + $row["expensevat"]),
            "isexpense"=>1,
            "client"=>$clientid,
            "CompanyName"=>($row['cojmname'].' '.$row['whoto'].' '.$temptab),
            "depname"=>($row['depname']),
            "notpaid"=>($row['paid']),
            "comments"=>($comments)
            );
        }

        }

    
    
        echo ' <div class="ui-state-highlight ui-corner-all clearfix undersearch" >
        <h3> '.$expenserows.' Expense'; if ($expenserows<>1) { echo 's'; } 
        echo ' </h3>
        <p title="Incl. VAT">Total Expenses : &'. $globalprefrow['currencysymbol']. number_format($expensetotal, 2, '.', ',').'
        </p>
        </div> ';
    
    
    
    
    
    

    
    $conditions = array();
    $parameters = array();
    $where = "";
    

    if ($collectionsfromdate) {
        $conditions[] = " paymentdate >= :sqlstart ";
        $parameters[":sqlstart"] = $collectionsfromdate;
    }
    
    if ($collectionsuntildate) {
        $conditions[] = " paymentdate <= :sqlend ";
        $parameters[":sqlend"] = $collectionsuntildate;
    }
    
    if ($clientid<>'all') {
        // $conditions[] = " CustomerID = :clientid ";
        // $parameters[":clientid"] = $clientid;
    }
    
    
        if ($searchphrase) {
        $conditions[] = " ( 
        cojm_payments.paymentamount LIKE :testrefa 
        OR cojm_payments.paymentcomment LIKE :testrefb
        ) ";
        $parameters[":testrefa"] = "%".$searchphrase."%";
        $parameters[":testrefb"] = "%".$searchphrase."%";  
    }  
    
    if (count($conditions) > 0) {
        $where = implode(' AND ', $conditions);
    }

    // check if $where is empty string or not
    $query = " SELECT paymentid, paymentdate, paymentamount, paymentclient, paymenttype, paymenttypename, paymentcomment, paymentedited, paymentcreated, CompanyName FROM cojm_payments 
        left JOIN cojm_paymenttype ON cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
        left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID
    " . ($where != "" ? " WHERE $where" : "");

    try {
        if (empty($parameters)) {
            $result = $dbh->query($query);
        }
        else {
            $statement = $dbh->prepare($query);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $result = $statement->fetchAll();
        }
    }
    catch(Exception $ex) {
        echo $ex->getMessage();
    }
    
    
    
        $numpayments=0;
        $paymentcost=0;
   
    if ($result) {
        foreach ($result as $row ) {
            $tablerow[] = array(
            "date"=>(strtotime($row['paymentdate'])),
            "ref"=>$row['paymentid'],
            "amount"=>$row["paymentamount"],
            "isexpense"=>0,
            "client"=>$clientid,
            "CompanyName"=>($row['CompanyName']),
            "comments"=>($row['paymentcomment'])
            );

            $paymentcost=$paymentcost+$row["paymentamount"];
            $numpayments++;
        }
 


        
    }

    echo ' <div class="ui-state-highlight ui-corner-all clearfix undersearch" >
    <h3> '.$numpayments.' Payment'; if ($numpayments<>1) { echo 's'; } 
    echo ' </h3>
    <p title="Incl. VAT">Total Payments : &'. $globalprefrow['currencysymbol']. number_format($paymentcost, 2, '.', ',').'
    </p>
    </div> ';

    
    if ($tablerow) {
        $sortArray = array(); 
    
        foreach($tablerow as $tableitem){ 
            foreach($tableitem as $key=>$value){ 
                if(!isset($sortArray[$key])){ 
                    $sortArray[$key] = array(); 
                } 
                $sortArray[$key][] = $value; 
            } 
        } 
        
        
        if ($orderby=='highlo') {
            $orderby = "amount";
                array_multisort($sortArray[$orderby],SORT_DESC,$tablerow);
        } else {
            $orderby = "date";
                array_multisort($sortArray[$orderby],SORT_ASC,$tablerow);
        }
    
        
    
        echo ' <div style="clear:both;"> </div> ';
        
            if ($clientview=='client') {
            echo ' <br /> ';
        }
    
        echo '
        <table class="acc clear';
        if ($clientview<>'client') {
            echo ' biggertext';
        }
        echo '">
        <thead>
        <tr>
        <th>Date</th>
        <th>Ref</th>
        <th>Expense</th>
        <th>Payment</th>
        <th>Balance</th>
        <th> </th>
        </tr>
        </thead>
        <tbody> ';
        
        
        
        if ($prevresult[0]['cost']) {
            echo ' <tr>
            <td colspan="4"> </td>
            <td class="rh">&'. $globalprefrow['currencysymbol']. number_format($runningtotal, 2, '.', ',').'</td>
            <td>Previous Transactions</td>
            </tr>';
        }
        
        
        foreach($tablerow as $tableitem) {
            if ($tableitem['isexpense']<>'1') {
                $runningtotal=$runningtotal+$tableitem['amount'];
            } else {
                $runningtotal=$runningtotal-$tableitem['amount'];
            }
            
            if (number_format($runningtotal, 2, '.', ',')=='-0.00') {
                $runningtotal=0;
            }
    
            echo '<tr id="tr'.$tableitem['ref'].'">';
            echo '<td class="rh">';


            if (($tableitem['isexpense']) and ($tableitem['notpaid']<>1)) {
                echo ' UNPAID ';
            }


            
            echo date('D j M Y', $tableitem['date']);
            
            
            
            echo '</td>';
            
            if ($tableitem['isexpense']) {
                echo '<td> Expense 
                
                <a href="singleexpense.php?expenseref='.$tableitem['ref']. '" >'.$tableitem['ref'].'</a>
                
                </td>';
            } else {
                echo '<td> Payment ';
                if ($clientview<>'client') {            
                            
                    echo '<a 
                    href="paymentsin.php?paymentid='.$tableitem['ref'].'"
                    title="Payment '.$tableitem['ref'].'">'.$tableitem['ref'].'</a></td>';
                } else {
                    echo $tableitem['ref'];
                }
            }
            
            
            if ($tableitem['isexpense']) {
                echo '<td class="rh">&'. $globalprefrow['currencysymbol']. number_format($tableitem['amount'], 2, '.', ',').'</td> <td> </td>';
            } else {
                echo '<td> </td>
                <td class="rh">&'. $globalprefrow['currencysymbol']. number_format($tableitem['amount'], 2, '.', ',').'</td>';
            }
            
            echo '<td class="rh">  &'. $globalprefrow['currencysymbol']. number_format($runningtotal, 2, '.', ',').' </td> ';
            
            echo '<td>';
    
            echo $tableitem['CompanyName'].' '.$tableitem['comments'].'</td>';
            echo '</tr>';
        }
        echo ' 
        </tbody>    
        <tfoot>
        <tr>
        <td class="rh" colspan="2"> Total </td>
        <td class="rh"> &'. $globalprefrow['currencysymbol']. number_format($expensetotal, 2, '.', ',').'</td>
        <td class="rh"> &'. $globalprefrow['currencysymbol']. number_format($paymentcost, 2, '.', ',').'</td>
        <td class="rh">  &'. $globalprefrow['currencysymbol']. number_format($runningtotal, 2, '.', ',').'</td>
        <td> </td>
        </tr>
        </tfoot>
        </table>';
    }
    
    
    
} // view stmnt

else if ($view=='payments') {
    
    $conditions = array();
    $parameters = array();
    $where = "";
    $numpayments=0;
    $paymentcost=0;    

    if ($collectionsfromdate) {
        $conditions[] = " paymentdate >= :sqlstart ";
        $parameters[":sqlstart"] = $collectionsfromdate;
    }
    
    if ($collectionsuntildate) {
        $conditions[] = " paymentdate <= :sqlend ";
        $parameters[":sqlend"] = $collectionsuntildate;
    }

    
    if ($searchphrase) {
        $conditions[] = " ( 
        cojm_payments.paymentamount LIKE :testrefa 
        OR cojm_payments.paymentcomment LIKE :testrefb
        ) ";
        $parameters[":testrefa"] = "%".$searchphrase."%";
        $parameters[":testrefb"] = "%".$searchphrase."%";  
    }    
    
    
    
    if (count($conditions) > 0) {
        $where = implode(' AND ', $conditions);
    }

    $query = " SELECT paymentid, paymentdate, paymentamount, paymentclient, paymenttype, paymenttypename, paymentcomment, paymentedited, paymentcreated, CompanyName FROM cojm_payments 
        left JOIN cojm_paymenttype ON cojm_payments.paymenttype = cojm_paymenttype.paymenttypeid 
        left JOIN Clients ON cojm_payments.paymentclient = Clients.CustomerID
    " . ($where != "" ? " WHERE $where" : "");

    
    if ($orderby=='highlo') {
        $query .= " ORDER BY paymentamount DESC";    
    } else {
        $query .= " ORDER BY paymentdate ASC";  
    }
    
    try {
        if (empty($parameters)) {
            $result = $dbh->query($query);
        }
        else {
            $statement = $dbh->prepare($query);
            $statement->execute($parameters);
            if (!$statement) throw new Exception("Query execution error.");
            $result = $statement->fetchAll();
        }
    }
    catch(Exception $ex) {
        echo $ex->getMessage();
    }
    

   
    if ($result) {
        $table.=' 
        <table class="acc clear';
        
        if ($clientview<>'client') {
            $table.= ' biggertext';
        }
        $table.=' " >
        <thead>
        <tr>
        <td> Date </td>
        <td> Ref </td>
        <td> Amount </td>
        <td> Method </td>
        <td> </td>
        </tr>
        </thead><tbody> ';
        
        
        foreach ($result as $row ) {
            $table.=' <tr>';
            $table.=  '<td class="rh">'.date('D j M Y', strtotime($row['paymentdate'])).'</td>';
            $table.= '<td> ';
            
            if ($clientview<>'client') {
                $table.= '<a 
                href="paymentsin.php?paymentid='.$row['paymentid'].'"
                title="Payment '.$row['paymentid'].'">'.$row['paymentid'].'</a></td>';
            } else {
                $table.= $row['paymentid'];
            }
            
            
            $table.= ' </td> <td class="rh">  &'. $globalprefrow['currencysymbol']. number_format($row['paymentamount'], 2, '.', ',').' </td> ';
            
            
            $table.='<td> '.$row['paymenttypename'].' </td>';        
            $table.= '<td>' .$row['paymentcomment'].' '. $row['CompanyName'].' </td>';
            $table.=' </tr> ';

            $paymentcost=$paymentcost+$row["paymentamount"];
            $numpayments++;
        }
        
        $table.= '
        <tfoot>
        <tr>
        <td colspan="2">Total Payments</td>
        <td class="rh">  &'. $globalprefrow['currencysymbol']. number_format($paymentcost, 2, '.', ',').'</td>
        <td colspan="2"> </td>
        </tr>
        </tfoot> ';
        
        $table.=' </tbody> </table> ';
 

        
    }
    echo ' 
    <div class="ui-state-highlight ui-corner-all clearfix undersearch" >
    <h3> '.$numpayments.' Payment';
    if ($numpayments<>1) { echo 's'; } 
    echo ' </h3>

    <p title="Incl. VAT">Total Payments : &'. $globalprefrow['currencysymbol']. number_format($paymentcost, 2, '.', ',').'
    </p>
    </div>
    '.$table;    
}


echo '<br />';
echo '</div>';

include 'footer.php';

?>
<script type="text/javascript">	
$(document).ready(function() {
    
    
    $('#view').change(function() {
        var view=$("select#view").val();
        
        if (view==='expenses') {
            $("#expensesearchbar").show();            
        } else {
            $("#expensesearchbar").hide();
        }
    });
    
    
    
    
    $('#searchexpensecode').change(function() {
        var searchexpensecode=$("select#searchexpensecode").val();
        
        if (searchexpensecode==='6') {
            $("#thiscyclist").show();
            $("#viewtype").show();
        } else {
            $("#thiscyclist").hide();
            $("#viewtype").hide();
        }
    });    
    
    
    
    
    
	$(function(){
				  $('#rangeBa, #rangeBb').daterangepicker();  
			 });
	$(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
	});
    
<?php if ($viewtype<>'view2') { ?>
    
    var menuheight=$("#sticky_navigation").height();
$(".acc").floatThead({
    position: "fixed",
    top: menuheight
});

<?php } ?>

});
    
	</script>
<?php
echo '</body></html>';