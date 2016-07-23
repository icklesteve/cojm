<?php 
error_reporting( E_ERROR | E_WARNING | E_PARSE );

$alpha_time = microtime(TRUE);

include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include 'changejob.php';

$title='COJM ';

?><!DOCTYPE html> 
<html lang="en"> 
<head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title); ?> Create Invoice</title></head>
<body>
<?php 
$adminmenu ="0";
$invoicemenu = "1";
$filename='pdfview.php';

$tempthree='';
$trow='';
$tarow='';

$clientids = array('');
$depselectjs='';
$tablecost='0';

$todayDate = date("Y-m-d");// current date

//Add one day to today
$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "-1 month");
$dateamonthago = date('Y-m-d H:i:s', $dateOneMonthAdded);
// echo "After adding one month: ".date('l dS \o\f F Y', $dateOneMonthAdded)."<br>After taking away a month : ". $dateamonthago."<br><br>";

$sql = "SELECT * FROM Clients ORDER BY lastinvoicedate";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 

while ($row = mysql_fetch_array($sql_result)) { extract($row);
$lastdate = "SELECT collectiondate FROM Orders WHERE CustomerID=$CustomerID ORDER BY collectiondate DESC LIMIT 0 , 1";
$sql_result_last = mysql_query($lastdate,$conn_id) or die(mysql_error()); while ($lastrow = mysql_fetch_array($sql_result_last)) { extract($lastrow); 




$temptwo=''; $tempdate=$lastrow['collectiondate']; 
$sqlcostage = "SELECT * FROM Orders WHERE CustomerID = '$CustomerID' 
AND status > '98' 
AND status < '108' 
AND collectiondate <= '$tempdate'
And FreightCharge <> '0.00'
 ";
$sql_resultcost = mysql_query($sqlcostage,$conn_id)  or mysql_error(); 
while ($costrow = mysql_fetch_array($sql_resultcost)) { extract($costrow); $temptwo=$temptwo+$costrow['FreightCharge']; } 

$tempthree=$tempthree+$temptwo;


if (($temptwo<>'0.00') and ($temptwo))
{
array_push($clientids, "$CustomerID");

$tarow= ' ';

// $tarow= ' <tr><td> </td><td> </td><td> </td><td> </td></tr>';
$tarow=$tarow. '<tr><td><a href="new_cojm_client.php?clientid='.$CustomerID.'">'. $CompanyName.'</a></td><td>';



$firstdate = "SELECT * FROM Orders WHERE CustomerID=$CustomerID AND status < '108' AND status > '98' ORDER BY collectiondate ASC LIMIT 0 , 1";
$sql_result_first = mysql_query($firstdate,$conn_id) or die(mysql_error()); while ($firstrow = mysql_fetch_array($sql_result_first)) { extract($firstrow); 
$tarow=$tarow.date('D j M Y', strtotime($firstrow['collectiondate']));
}




$tarow=$tarow.'</td><td>'. date('D j M Y', strtotime($lastrow['collectiondate']));

$temptwo= number_format($temptwo, 2, '.', '');

$tarow=$tarow.'</td><td class="rh">  &'. $globalprefrow['currencysymbol']; 
$tarow=$tarow. $temptwo; 
$tarow=$tarow. '</td></tr>';



$trow=$trow.$tarow;













} // ends loop for uninvoiced jobs > 0.00

} // end check for last collection date order loop

} // ends loop clients last databased invoice date





include "cojmmenu.php"; 
echo '<div class="Post">

<div class="hangleft ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">


<p>

<form name="f1" action="../../cojm/tcpdf/invoice.php" method="post"> 
<fieldset><label for="pageclientid" class="fieldLabel"> Client : </label>
<select  class="ui-state-default ui-corner-left" id="pageclientid" name="clientid">';




$query = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient=1 ORDER BY CompanyName";
$result_id = mysql_query ($query, $conn_id);
while (list ($CustomerID, $CompanyName) = mysql_fetch_row ($result_id))
{

if(in_array($CustomerID, $clientids))
	{
	$CustomerID = htmlspecialchars ($CustomerID);
	$CompanyName = htmlspecialchars ($CompanyName);
		print"<option "; print ("value=\"$CustomerID\">$CompanyName</option>\n");
}}

echo '
</select>';




echo '

<a id="clientlink" class="showclient" title="Client Details" target="_blank" href="new_cojm_client.php"> </a>';













echo '</fieldset>
<fieldset><label for="orderselectdep" class="fieldLabel"> Department : </label>';




/////////////
// echo 'orderdep is '.$row['orderdep']; 

$newjobclientid=$row['CustomerID'];
$temp=$row['orderdep'];

// $query = "
// SELECT depnumber, depname, associatedclient 
// FROM clientdep 
// WHERE isactivedep='1' 
// ORDER BY associatedclient, depname"; 

 $query = "
 SELECT depnumber, depname, CompanyName, CustomerID 
 FROM clientdep, Clients
 WHERE clientdep.associatedclient = Clients.CustomerID 
 AND clientdep.isactivedep='1' 
 ORDER BY Clients.CompanyName, clientdep.depname"; 

// $query = "SELECT depnumber, depname FROM clientdep WHERE associatedclient = '$newjobclientid' AND isactivedep='1' ORDER BY depname"; 

$result_id = mysql_query ($query, $conn_id) or mysql_error();  
$sumtot=mysql_affected_rows();

// echo $sumtot.' Department(s) : ';	

echo '<select class="ui-state-default ui-corner-left" id="orderselectdep" name="orderselectdep" >
<option value="">All Departments</option>';
 while (list ($CustomerIDlist, $CompanyName, $clientname) = mysql_fetch_row ($result_id)) { 
$CustomerID = htmlspecialchars ($CustomerID); 
$CompanyName = htmlspecialchars($CompanyName); 



$temptwod='0.00';

$sqlcostaged = "SELECT * FROM Orders WHERE orderdep = '$CustomerIDlist' 
AND status > '98' 
AND status < '108' ";
$sql_resultcostd = mysql_query($sqlcostaged,$conn_id)  or mysql_error(); 
while ($costrowd = mysql_fetch_array($sql_resultcostd)) { extract($costrowd); $temptwod=floatval($temptwod+$costrowd['FreightCharge']); } 

$depcharge= number_format(floatval($temptwod), 2, '.', '');



 if ($depcharge<>0.00) {

print'<option ';

if ($CustomerIDlist==$row['orderdep']) { echo ' SELECTED '; }


// echo $depcharge;
// echo $costrowd['FreightCharge'];

echo 'value="'.$CustomerIDlist.'">&'.$globalprefrow['currencysymbol'].' '.$depcharge.' '.$clientname.' -- '.$CompanyName.'</option>';

$depselectjs.='  

if ( newdep=='.$CustomerIDlist.') { $("#pageclientid").val("'.$CustomerID.'"); } 
';


 } // ends check for charge>0

} // ends list of departments 

echo '</select> ';


//////////////////////////////////////////


echo '

<a id="clientdeplink" class="showclient" title="Department Details" target="_blank" href="new_cojm_department.php"> </a>

';












echo '
</fieldset>

<fieldset><label for="expensedate" class="fieldLabel"> Date to Invoice Until</label> 
<input class="ui-state-default ui-corner-all caps" type="text" value="'. date('d-m-Y', strtotime('now')); 


echo '" 
id="expensedate" size="12" name="expensedate"></fieldset>

<fieldset><label for="existinginvoiceref" class="fieldLabel"> Existing invoice ref ? </label>
<input type="text" class="ui-state-default ui-corner-all caps" name="existinginvoiceref" id="existinginvoiceref"
 size="22" maxlength="20" >
</fieldset>
<fieldset><label for="exacttime" class="fieldLabel"> Exact time or just the date : </label> 
<select class="ui-state-default ui-corner-left" name="exacttime" id="exacttime" >
<option selected value="1" >Exact Time</option>
<option value="0" >Just the day</option>
</select> </fieldset>

<fieldset><label for="hourly" class="fieldLabel"> From/Until instead of Collection/Delivery </label>

<select class="ui-state-default ui-corner-left" name="hourly" id="hourly">
<option selected value="0" >No</option>
<option value="1" >Yes</option>
</select></fieldset>

<fieldset><label for="showdelivery" class="fieldLabel">Show delivery date ? </label>
<select class="ui-state-default ui-corner-left" name="showdelivery" id="showdelivery">
<option  value="0" >No</option>
<option selected value="1" >Yes</option>
</select>
 </fieldset>
 
 
  <fieldset><label for="addresstype" class="fieldLabel">Full address / Postcode? </label>
<select class="ui-state-default ui-corner-left" name="addresstype" id="addresstype" >
<option selected value="full" >Full Address</option>
<option  value="postcode" >Just Postcode</option>
<option  value="none" >No Addresses</option>
</select>
</fieldset>
 
 
 
 
 <fieldset><label for="showdeliveryaddress" class="fieldLabel">Show delivery address ? </label>
<select class="ui-state-default ui-corner-left" name="showdeliveryaddress" id="showdeliveryaddress">
<option  value="0" >No</option>
<option selected value="1" >Yes</option>
</select>
</fieldset>
 
 
 
 
<fieldset><label for="invdate" class="fieldLabel"> Invoice Date : </label>
<select class="ui-state-default ui-corner-left" name="invdate" id="invdate">
<option selected value="0" >Today</option>
<option value="1" >Tomorrow</option>
<option value="2" >Day After</option>
<option value="7" >7 Days</option>
<option value="-1" >Yesterday</option>
<option value="-2" >Day before Yesterday</option>
</select></fieldset>
<br>Invoice Comments : '. $globalprefrow['invoicefooter2'].'<br>
<TEXTAREA class="ui-state-default ui-corner-left normal " style="padding-left:3px;" name="invcomments" rows="2" cols="50"></TEXTAREA> 
<br>
<div class="line"></div>';

?>

<button id="datesearchpreview">View in Date Search</button>

<button type='submit' onclick="f1.action='../../cojm/tcpdf/invoice.php?page=preview'; this.form.target='_blank'; return true;">Preview Invoice</button> 
<button type='submit' onclick="f1.action='../../cojm/tcpdf/invoice.php?page=createpdf'; return true;">Create PDF Invoice</button>
<button type='submit' onclick="f1.action='../../cojm/tcpdf/invoice.php?page=addtodb'; this.form.target='_blank'; return true;" 
style="color: Red;" > Commit invoices details to database </button>



<?php echo '
</form>


</div>



<div class="hangleft ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<table class="acc" ><tbody>
<tr>
<th scope="col">Client</th>
<th scope="col">Invoice From</th>
<th scope="col">Last Collection</th>
<th scope="col" class="rh" >Cost ex vat</th>
</tr>';



// print_r($clientids);


echo $trow. '
<tr><td> </td><td> </td><td> <strong>Total : </strong></td>
<td class="rh"> &'.$globalprefrow['currencysymbol'].'<strong>'.$tempthree.'</strong></td></tr>
</tbody></table>
<br />
<div class="line"></div>
</div><br />';


echo '<script type="text/javascript">
$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+15));
	
		$(function() {
		var dates = $( "#expensedate" ).datepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
            dateFormat: "dd-mm-yy",
			changeMonth:false
		});
	});
	
	
$(function(){ $(".normal").autosize();	});	
	
	
	
	
	
	$("#clientdeplink").click(function (e) {
	e.preventDefault();  
	
//	alert("clicked");


var pageclientid=$("#pageclientid").val();
var pagedepid=$("#orderselectdep").val();


var datelink = "new_cojm_department.php?depid=" + pagedepid + "#tabs-" + pagedepid;
	
	if (pagedepid=="") { } else {  window.open(datelink,"_blank") }

	
});
	
	
	
	
	
	
	
	
	
	
	

$("#clientlink").click(function (e) {
	
//	alert("clicked");


var pageclientid=$("#pageclientid").val();

var datelink = "new_cojm_client.php?clientid=" + pageclientid;
	
	
		e.preventDefault();  
   window.open(datelink,"_blank")

	
});
	
	
	
$("#datesearchpreview").click(function (e) {

var pageclientid=$("#pageclientid").val();

var pagedepid=$("#orderselectdep").val();

var todate=$("#expensedate").val();


var datelink = "clientviewtargetcollection.php?clientid=" + pageclientid;

datelink = datelink + "&viewselectdep=" + pagedepid;

datelink = datelink + "&from=01%2F01%2F2009&to=" + todate;

// alert (todate);

datelink = datelink + "&newcyclistid=all&servicetype=all&deltype=all&orderby=targetcollection";
datelink = datelink + "&clientview=normal&viewcomments=normal&statustype=notinvoicedcomp";
	
	e.preventDefault();  
   window.open(datelink,"_blank")


});



$("#orderselectdep").change(function() { 

var newdep=$("#orderselectdep").val();

// alert("department changed to " + newdep);

'.$depselectjs.'


});


	
	
	
}); // ends on pageload
</script>';

echo '</div>';

include "footer.php";

echo '</body>';

mysql_close(); 