<?php 

$alpha_time = microtime(TRUE);

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

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

if (isset($_GET['thiscyclist'])) { $thiscyclist=trim($_GET['thiscyclist']); } else { $thiscyclist=''; }
if (isset($_GET['paymentmethod'])) { $paymentmethod=trim($_GET['paymentmethod']); } else { $paymentmethod=''; }
// if ($paymentmethod=='') { $paymentmethod='All'; }
if (isset($_GET['collectyear'])) { $year=trim($_GET['collectyear']); } else { if (isset($_GET['collectyear'])) { $year=trim($_GET['collectyear']); }}
if (isset($_GET['collectmonth'])) { $month=trim($_GET['collectmonth']); } else {if (isset($_GET['collectmonth'])) { $month=trim($_GET['collectmonth']);} }
if (isset($_GET['collectday'])) { $day=trim($_GET['collectday']); } else {if (isset($_GET['collectday'])) { $day=trim($_GET['collectday']);} }

$hour="23";
$minutes="59";

if (isset($year)) {

    $collectionsuntildate = $year . "-" . $month . "-" . $day . " " . $hour . ":" . $minutes . ":59";
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
// $infotext=$infotext. 'starts : '.$inputstart.'<br /> ends : '.$inputend;

}

// $infotext=$infotext. ' from '.$collectionsfromdate.' until '.$collectionsuntildate;

if (isset($_GET['searchexpensecode'])) { $searchexpensecode=trim($_GET['searchexpensecode']); }

else { $searchexpensecode=''; }

// echo 'search id : '.$searchexpensecode;


if (isset($inputstart)) { if ($inputstart=='//') { $inputstart=''; } } else { $inputstart=''; }
if (isset($inputend)) { if ($inputend=='//') { $inputend='';} } else { $inputend=''; }

$temptab='';
$vattablecost='0';


?><!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<title>COJM : Search Expenses</title>
<link rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<link rel="stylesheet" href="css/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<script type="text/javascript" src="js/jquery-ui.1.8.7.min.js"></script>
<script type="text/javascript" src="js/jquery.floatThead.js"></script>
<style>
@media print {
    .infotext { display:none; }
    .moreinfotext { display:none; }
    .cojmcopyright { display:none; }
    .loggedinas { display:none; }
}
</style>
</head>
<body>
<? 

$adminmenu = "0";
$invoicemenu='1';

include "cojmmenu.php"; ?>
<div class="Post">
<form action="expenseview.php#" method="get">
<div class="ui-state-highlight ui-corner-all p15" >

From <input class="ui-state-highlight ui-corner-all pad" size="11" type="text" name="from" value="<?php echo $inputstart; ?>" id="rangeBa" />
To <input class="ui-state-highlight ui-corner-all pad"  size="11" type="text" name="to" value="<?php echo $inputend; ?>" id="rangeBb" />	Category <select class="ui-state-highlight ui-corner-left" name="searchexpensecode">
<option value="all">All Categories</option>        
<?php 
$query = "SELECT expensecode, smallexpensename, expensedescription FROM expensecodes ORDER BY expensecode";
$result_id = mysql_query ($query, $conn_id); 

while (list ($expensecode, $smallexpensename, $expensedescription) = mysql_fetch_row ($result_id)) { 
    $expensedescription = htmlspecialchars ($expensedescription);
    $expensecode = htmlspecialchars ($expensecode); 
    $smallexpensename = htmlspecialchars ($smallexpensename); 
    print"
            <option ";
    if ($expensecode == $searchexpensecode) {echo "SELECTED "; }
    print ("value=\"$expensecode\">$smallexpensename</option>\n");
} ?>
        </select>
        Method
        <select class="ui-state-highlight ui-corner-left" name="paymentmethod"> 
            <option value="">All</option>
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

    <option value="">Paid &amp; Future </option>
    <option <?php if ($ifpaid=='paid') { echo 'selected'; } ?> value="paid">Paid</option>
    <option <?php if ($ifpaid=='future') { echo 'selected'; } ?> value="future">Future</option>

</select>


 
<select class="ui-state-highlight ui-corner-left" name="orderby">
    <option value="">Order by Date </option>
    <option
    <?php if ($orderby=='highlo') { echo 'selected'; } ?>
        value="highlo">High to Low</option>
</select>

<?php

if ($searchexpensecode=="6") {

    echo $globalprefrow['glob5']. ' : ';
    // . $thiscyclist;

    $query = "SELECT CyclistID, cojmname FROM Cyclist WHERE isactive='1' ORDER BY CyclistID"; 
    $result_id = mysql_query ($query, $conn_id); 
    print ("<select class=\"ui-state-highlight ui-corner-left\" name=\"thiscyclist\">\n"); 
 
    echo '<option value="All" >All</option>';
 
    while (list ($CyclistID, $cojmname) = mysql_fetch_row ($result_id)) {
        print ("<option ");
        if ($CyclistID == $thiscyclist) {
            echo " SELECTED ";
        }
        print ("value=\"$CyclistID\">$cojmname</option>\n");
    }
    print ("</select>"); 




    echo '
    <select class="ui-state-highlight ui-corner-left" name="viewtype">
        <option '; if ($viewtype=='normal')   { echo 'selected'; } echo ' value="normal">Normal View</option>
        <option '; if ($viewtype=='view2') { echo 'selected'; } echo ' value="view2">Print for Rider</option>
    </select> ';



}


echo ' 

<button type="submit" >Search</button>

</div>
</form>
';

if (isset($collectionsfromdate)) {

    
    
    $sql="
    SELECT * FROM expenses 
    INNER JOIN Cyclist 
    INNER JOIN expensecodes
    ON expenses.cyclistref = Cyclist.CyclistID 
    AND expenses.expensecode = expensecodes.expensecode 
    ";
    
    $sql.= " WHERE expensedate >= '$collectionsfromdate' AND expensedate <= '$collectionsuntildate' "; 
    
    
    
    if ($searchexpensecode=='all') {
        $sql.= " ";
    } else {
        $sql.=" AND expenses.expensecode=$searchexpensecode ";
    }
    
    
    if ($ifpaid=='paid') { $sql = $sql. " AND paid='1' "; }
    if ($ifpaid=='future') { $sql = $sql. " AND paid='0' "; }
    
    
    if (($searchexpensecode==6) AND ($thiscyclist>1)) { $sql.= "
    AND expenses.expensecode=$searchexpensecode 
    AND expenses.cyclistref= '$thiscyclist'  
    "; }
    
    
    // echo ' pm: '. $paymentmethod;
    
    if ($paymentmethod) { $sql.= " AND expenses.$paymentmethod <> '0.00'  "; }
    
    
    
    
    
    
    
    
    if ($orderby=='highlo') {
    
    $sql = $sql. " ORDER BY `expenses`.`expensecost` DESC ";
    
        } else {
    
    $sql = $sql. " ORDER BY `expenses`.`expensedate` ASC ";
    
    }
    
    
    
    // echo $sql;
        $sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
        $num_rows = mysql_num_rows($sql_result);
    
    
    
    
    
    if (($searchexpensecode) and ($inputstart) and ($num_rows>'0')) {
        
        
        
        
        $temptab='<p><br /><div class="vpad"> </div>
        <table class="acc" id="expenseview">        
        <tr>
        <thead>
        <th scope="col">Reference</th>
        <th title="Incl. VAT" scope="col">Net Amount</th>
        <th scope="col">VAT </th>
        <th scope="col">Date</th>';
        
        if ($viewtype<>'view2') {
            $temptab.= '
            <th scope="col">'.$globalprefrow['glob5'].'</th>
            <th scope="col">Paid to</th>
            <th scope="col">Type</th>';
        }
        
        $temptab.= '
        <th scope="col">Method </th>
        <th scope="col"> </th>
        </tr>
        <thead>
        <tbody>';
        
        
        $tablecost='';
        
        // echo ' payment : '.$paymentmethod;
        
        while ($row = mysql_fetch_array($sql_result)) {
            
            extract($row);
                    
            $tablecost = $tablecost + $row["expensecost"];
            $vattablecost = $vattablecost + $row["expensevat"];	
                    
            $temptab.= '
            <tr> 
            <td>';
            
            
            
            if ($viewtype=='view2') { // view is rider report
            
                $temptab.= $expenseref. ' '; 
            } else { // view is NOT rider report
            
            $temptab.= '
            <a class="newwin" href="singleexpense.php?expenseref='.$expenseref. '" target="_blank">'.$expenseref.'</a>'; 
            
            } // ends check rider report
            
            if ($row['paid']<'1') { $temptab.= ' UNPAID'; }
            
            $temptab.= '</td>
            <td class="rh"> &'. $globalprefrow['currencysymbol']. $row['expensecost'].
            '</td>
            <td> ';
            
            if ($row['expensevat']>'0') {  $temptab=$temptab. ' &'.$globalprefrow['currencysymbol']. $row['expensevat']; }
            
            $temptab=$temptab. '
            
            </td>
            <td class="rh">'. date('j / m / Y', strtotime($row['expensedate'])).'</td>';
            
            
            
            
            if ($viewtype<>'view2') { // NOT rider report view
                
                $temptab.= '<td>';
                if ($CyclistID<>'1') { $temptab.= $cojmname; }   
                $temptab.='</td>
                <td>'.$row['whoto'].'</td>
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
        
        
        $temptab.='</tbody></table></p>';
        
        
        
        
        
        
        if ($viewtype=='view2') { // rider report view
            $sqlc = "SELECT * FROM Cyclist WHERE CyclistID=$thiscyclist LIMIT 0,1";  
            $sql_resultc = mysql_query($sqlc,$conn_id)  or mysql_error(); 
            while ($rowc = mysql_fetch_array($sql_resultc)) {
                extract($rowc);
            
                echo ''.$globalprefrow['courier9'].'
                <br />
                <h3>Payments from </h3>
                
                <h5>'.$globalprefrow['globalname'].'</h5>
                <p>'.$globalprefrow['myaddress1'].'
                <br />'.$globalprefrow['myaddress2'].'
                <br />'.$globalprefrow['myaddress3'].'
                <br />'.$globalprefrow['myaddress4'].'
                <br />'.$globalprefrow['myaddress5'].'</p>
                
                <h2>Payments to</h2>
                
                <h5>'.$rowc['poshname'].'</h5>
                <p>'.$rowc['housenumber'].'
                <br />'.$rowc['streetname'].'
                <br />'.$rowc['city'].'
                <br />'.$rowc['postcode'].'</p>
                <h3>'.$start.' until '.$end.'</h3>';
                
            }
        
        } // ends rider report view
        
        
        $grosscost=$tablecost-$vattablecost;
        $ttablecost= number_format($tablecost, 2, '.', ',');
        $tvattablecost= number_format($vattablecost, 2, '.', ',');
        $tgrosscost= number_format($grosscost, 2, '.', ',');
        
        
        echo '
        <br />
        <p> Grand Total : &'. $globalprefrow['currencysymbol']. $ttablecost;
        
        if ($tvattablecost>'0') {
            echo ' <br /> Excl. VAT : &'. $globalprefrow['currencysymbol']. $tgrosscost;
            echo '<br /> Total Vat : &'. $globalprefrow['currencysymbol'] .$tvattablecost;
        }
        
        echo ' </p> ';
        
        
        
        
        
        echo $temptab;
        
        if ($viewtype=='view2') { // rider report view
            echo '<hr />';
            echo $globalprefrow['courier10']; 
            echo '<div style="clear:both;"> </div>
            <p>Report generated '.date("l jS F Y").'.</p>
            <hr />';
        } // ends rider report view
        
        
        
    } else if ($num_rows==0){
        echo '
        <div class="ui-state-highlight ui-corner-all p15" >
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
        <strong> No Expenses found.</strong>
        </div>';
    } // ends searchexpensecode check

} // ends collectionsfromdate

echo '<br />';
echo '</div>';

include 'footer.php';

?>
<script type="text/javascript">	
$(document).ready(function() {	
	$(function(){
				  $('#rangeBa, #rangeBb').daterangepicker();  
			 });
	$(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();
		});
	});
    
    
    var menuheight=$("#sticky_navigation").height();
$("#expenseview").floatThead({
    position: "fixed",
    top: menuheight
});
});
    
	</script>


<?php

echo '</body></html>';
mysql_close(); 