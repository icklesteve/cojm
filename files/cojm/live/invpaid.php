<?php 
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "../../administrator/cojm/updatetracking.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include'changejob.php';

$hasforms='1';
$invoicemenu = "1";
$adminmenu ="0";
$filename='invpaid.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="cojm.css" >
<link rel="stylesheet" href="js/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" />
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<title> COJM : Invoice Paid</title></head>
<body>
<?php include "cojmmenu.php"; ?>
<div class="Post"><?php
// echo $invoicedate;
$temp=mysql_affected_rows();



?>
<div class="ui-widget">
<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<h3>Enter details of Invoice Payment : </h3>
<form action="invpaid.php" method="post"> 
<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>">
<input type="hidden" name="page" value="markinvpaid">

<fieldset><label for="invoicedate" class="fieldLabel"> 
Invoice Ref : </label><select name="ref" class="ui-state-default ui-corner-left" ><?php

   $sql = "SELECT * FROM invoicing 
   INNER JOIN Clients 
   ON invoicing.client = Clients.CustomerID 
   WHERE (`invoicing`.`paydate` =0 ) 
   ORDER BY `invoicing`.`invdate1` ";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
while ($row = mysql_fetch_array($sql_result)) { extract($row);

$deptext='';

if ($row['invoicedept']>0) {

$invoicedept=$row['invoicedept'];

$dquery = "SELECT depname FROM clientdep WHERE depnumber = '$invoicedept' LIMIT 1"; 
$dresult_id = mysql_query ($dquery, $conn_id) or mysql_error();  
$sumtot=mysql_affected_rows();
// echo $sumtot.' Department(s) : ';	
 while (list ($dCompanyName) = mysql_fetch_row ($dresult_id)) { 
$dCompanyName = htmlspecialchars($dCompanyName); 
$deptext =' ('.$dCompanyName.')';

} 
} // ends check for department

print'<option value="'.$row['ref'].'"> '.$row['ref'].' '.$row['CompanyName'].$deptext.' &'. $globalprefrow['currencysymbol'].$row['cost'].'</option> ';

}
?></select>
</fieldset>

<div class="vpad"> </div>

<fieldset><label for="invoicedate" class="fieldLabel"> Payment Date </label> 
<input class="ui-state-default ui-corner-all caps" type="text" value="<?php echo date('d-m-Y', strtotime('now')); ?>" 
id="invoicedate" size="12" name="invoicedate"></fieldset>
<div class="vpad"> </div>

<fieldset><label for="invoicedate" class="fieldLabel">
Method of Payment </label>

<select class="ui-state-default ui-corner-left" name="invmethod" >
<option selected value="" >Select Payment Method</option>
<option value="cash" >Cash</option>
<option value="cheque" >Cheque</option>
<option value="bacs" >BACS</option>
<option value="paypal" >Paypal</option>
</select>
</fieldset>
<div class="vpad"> </div>
<div class="line"></div>
<button type="submit"> Mark as Paid </button>
</form>
<div class="line"></div></div></div><?php 

mysql_close(); ?><br /></div></body></html>
<script type="text/javascript">

$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+15));
});

	$(function() {
		var dates = $( "#invoicedate" ).datepicker({
			numberOfMonths: 1,
			changeYear:true,
			firstDay: 1,
            dateFormat: 'dd-mm-yy',
			changeMonth:true,
		  beforeShow: function(input, instance) { 
            $(input).datepicker('setDate',  new Date() );
        }
		});
	});
	</script>