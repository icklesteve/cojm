<?php

$html='';
$invhtml='';

if(isSet($_POST['newjobselectclient'])) { $newjobclientid = $_POST['newjobselectclient'];
include "C4uconnect.php";

$query="SELECT * FROM Clients WHERE Clients.CustomerID = '$newjobclientid' LIMIT 1"; 
$result=mysql_query($query, $conn_id); $row=mysql_fetch_array($result);
if ($row['CompanyName']) {  // echo 'Client found : '.$row['CompanyName'];




// starts check for unpaid invoices
$tablecost='0'; $todayDate = date("Y-m-d");// current date
$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "");
$dateamonthago = date('Y-m-d H:i:s', $dateOneMonthAdded);
$sql = "SELECT cost, invvatcost FROM invoicing  
   WHERE (`invoicing`.`paydate` =0 ) 
   AND (`invoicing`.`invdue` < '$dateamonthago' ) 
   AND (`invoicing`.`client` = '$newjobclientid' ) ";
//   echo $sql;
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 
$num_rows = mysql_num_rows($sql_result); if ($num_rows>'0') {
while ($irow = mysql_fetch_array($sql_result)) { extract($irow); $tablecost=$tablecost+$irow['cost']+$irow['invvatcost']; } 
$tablecost= number_format($tablecost, 2, '.', ','); 
$invhtml=' '.$num_rows.' Overdue Invoice'; if ($num_rows>'1') { $invhtml.='s '; }

$invhtml.=" <span title='Incl. VAT'> (&". $globalprefrow['currencysymbol']. $tablecost.') </span> ';

$itablecost='0'; 
$isql = "SELECT cost, invvatcost FROM invoicing  
   WHERE (`invoicing`.`paydate` =0 )
   AND (`invoicing`.`client` = '$newjobclientid' ) ";
//   echo $sql;
$isql_result = mysql_query($isql,$conn_id) or die(mysql_error()); 
$inum_rows = mysql_num_rows($isql_result); $inum_rows=$inum_rows-$num_rows;
if ($inum_rows>'0') {
while ($irow = mysql_fetch_array($isql_result)) { extract($irow); $itablecost=$itablecost+$irow['cost']+$irow['invvatcost']; } 
$itablecost= $itablecost-$tablecost;
$itablecost= number_format($itablecost, 2, '.', ','); 
$invhtml.=' + '.$inum_rows." in date <span title='Incl. VAT'> (&". $globalprefrow['currencysymbol']. $itablecost.') </span>. ';
} // ends check for in time

// echo $html;

} // ends check for overdue invoices





if ($row['isdepartments']=='1') {
// starts has departments
$query = "SELECT depnumber, depname FROM clientdep WHERE associatedclient = '$newjobclientid' AND isactivedep='1' ORDER BY depname"; 
$result_id = mysql_query ($query, $conn_id) or mysql_error(); $sumtot=mysql_affected_rows();
echo '<div class="fs">
<div class="fsl"> ';

echo $sumtot.' Deps </div> <div class="left"> <select class="ui-state-default ui-corner-all " 
id="newjobselectdep" name="newjobselectdep" ><option value="">Select one...</option>';

 while (list ($CustomerIDlist, $CompanyName) = mysql_fetch_row ($result_id)) { $CustomerID = htmlspecialchars ($CustomerID); 
$CompanyName = htmlspecialchars($CompanyName); 
print'<option value="'.$CustomerIDlist.'">'.$CompanyName.'</option>';} echo '</select> </div>

<div id="afterdepselect" class="left"> </div> 
<div class="clear"> </div>
</div>
';

echo '<input type="hidden" name="newjobclientid" value="'.$newjobclientid.'">
<script>
	'; ?>
	 clientdetails=" <a href='new_cojm_client.php?clientid=<?php echo $newjobclientid; ?>' target='_blank' class='showclient' " + 
" title='<?php echo $row['CompanyName']; ?> Details' type='button'> &nbsp; </a> <?php echo $invhtml; ?> "

<? echo '
$("div#afterclientselect").html(clientdetails);
	$(function() {
		$( "#newjobselectdep" ).newdepcombobox();
		 setTimeout( function() { $("#depselectbutton").click() }, 150 );
	});

</script>';

// ends has departments
} else {
// starts no departments





$html= '
<div class="cbbnewjobl">
<div class="fs">
<div class="fsl">   Requested By </div>
<input type="text" class="caps ui-state-default ui-corner-all w490"  name="requestedby" value="'. $row['defaultrequestor'].'">
</div>
';

$sql="SELECT favadrid, favadrft, favadrpc FROM cojm_favadr WHERE  favadrclient = '$newjobclientid' AND favadrisactive ='1' ";
$sql_result = mysql_query($sql,$conn_id);
$sumtot=mysql_affected_rows(); 



$html=$html. '

<div class="fs">
<div class="fsl">   Collection </div>
<select name="frombox" id="frombox">
<option value=""> Select One ...</option>';


while ($favrow = mysql_fetch_array($sql_result)) { extract($favrow);
$html=$html. '
<option value="'.$favrow['favadrid'].'"';
if ($favrow['favadrid']==$row['defaultfromtext']) { $html=$html. ' SELECTED '; }
$html=$html. '>'.$favrow['favadrft'].', '.$favrow['favadrpc'].'</option>';
}


$html=$html. '</select>';

// $html=$html.' <button name="showallfav" form="nosubmit" title="All Favourites" type="button" id="showallfav" class="showallfav"> &nbsp; </button>';


$html=$html. '</div>

<div class="fs">
<div class="fsl">   Delivery </div>
<select name="tobox" id="tobox"><option value=""> Select One ...</option>';

$sql_result = mysql_query($sql,$conn_id);

while ($favrow = mysql_fetch_array($sql_result)) { extract($favrow);
$html=$html. '
<option value="'.$favrow['favadrid'].'"';
if ($favrow['favadrid']==$row['defaulttotext']) { $html=$html. ' SELECTED '; }
$html=$html. '>'.$favrow['favadrft'].', '.$favrow['favadrpc'].'</option>';
}
$html=$html. '</select>'; 

$html=$html.' <button name="showallfav" title="All Favourites" id="showallfav" type="button" class="showallfav"> &nbsp; </button> ';

$html=$html.' </div>';

$html=$html. '<div class="fs">
<div class="fsl"> </div>
<input type="text" placeholder="Instructions" class="w490 caps ui-state-default ui-corner-all" name="jobcomments" /> </div>';





//////////////////////// starts service code

$html=$html. ' <div class="fs"><div class="fsl">   Service </div>';
$query = "SELECT ServiceID, Service , slatime, sldtime
FROM Services 
WHERE activeservice='1' 
ORDER BY serviceorder DESC, ServiceID ASC"; 
$result_id = mysql_query ($query, $conn_id); 
$html=$html. ("<select class=\"jlabel ui-state-default ui-corner-left\" name=\"serviceID\">"); 

while (list ($ServiceID, $Service, $slatime, $sldtime) = mysql_fetch_row ($result_id)) {	
$ServiceID = htmlspecialchars ($ServiceID);	
$Service = htmlspecialchars ($Service); $html=$html. ("<option "); 
{	if ($row['defaultservice'] == $ServiceID) { $html=$html. " SELECTED "; 
$thisslatime=$slatime;
$thissldtime=$sldtime;
}
}
$html=$html. ("value=\"$ServiceID\">$Service</option> 
"); } 
$html=$html. ("</select></div>"); 

$defaultservice=trim($row['defaultservice']);
// $ifcbbservice = mysql_result(mysql_query("SELECT chargedbycheck FROM Services WHERE ServiceID = $defaultservice LIMIT 0,1 ", $conn_id), 0);
/////////     ENDS    SERVICE ///////////////////////////////////////////////


 
// $html=$html. '<div class="vpad"></div>';
// if ($thisslatime) {  echo 'default sla is '.$thisslatime; }
// if ($thissldtime) {  echo 'default sld is '.$thissldtime; }

$html=$html. '<div class="fs"><div class="fsl">   PU Due </div>';
$html=$html. '<select class="ui-state-default ui-corner-left" name="ajcolldue">';
$html=$html. '<option value="now">Now </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:15:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="15">15 mins </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:30:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="30">30 mins </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:45:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="45">45 mins </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='01:00:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="60">1 hour </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='01:30:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="90">1 &amp;1/2 hours </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='02:00:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="120">2 hours </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='03:00:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="180">3 hours </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:08') { $html=$html. 'SELECTED'; } $html=$html. ' value="next8">Next 8AM </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:09') { $html=$html. 'SELECTED'; } $html=$html. ' value="next9">Next 9AM </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:10') { $html=$html. 'SELECTED'; } $html=$html. ' value="next10">Next 10AM </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:11') { $html=$html. 'SELECTED'; } $html=$html. ' value="next11">Next 11AM </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:12') { $html=$html. 'SELECTED'; } $html=$html. ' value="next12">Next 12PM </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:13') { $html=$html. 'SELECTED'; } $html=$html. ' value="next13">Next 1PM </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:14') { $html=$html. 'SELECTED'; } $html=$html. ' value="next14">Next 2PM </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:15') { $html=$html. 'SELECTED'; } $html=$html. ' value="next15">Next 3PM </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:16') { $html=$html. 'SELECTED'; } $html=$html. ' value="next16">Next 4PM </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:17') { $html=$html. 'SELECTED'; } $html=$html. ' value="next17">Next 5PM </option>';
$html=$html. "\n".' <option ';  if ($thisslatime=='00:00:18') { $html=$html. 'SELECTED'; } $html=$html. ' value="next18">Next 6PM </option>';
$html=$html. "\n".' </select></div>';

$html=$html. '<div class="fs">
<div class="fsl">   Drop Due </div> ';
$html=$html. '<select class="jlabel ui-state-default ui-corner-left" name="ajdelldue">';
$html=$html. ' <option value="now">Now </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:15:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="15">15 mins </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:30:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="30">30 mins </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:45:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="45">45 mins </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='01:00:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="60">1 hour </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='01:30:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="90">1 &amp;1/2 hours </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='02:00:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="120">2 hours </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='03:00:00') { $html=$html. 'SELECTED'; } $html=$html. ' value="180">3 hours </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:08') { $html=$html. 'SELECTED'; } $html=$html. ' value="next8">Next 8AM  </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:09') { $html=$html. 'SELECTED'; } $html=$html. ' value="next9">Next 9AM  </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:10') { $html=$html. 'SELECTED'; } $html=$html. ' value="next10">Next 10AM  </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:11') { $html=$html. 'SELECTED'; } $html=$html. ' value="next11">Next 11AM </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:12') { $html=$html. 'SELECTED'; } $html=$html. ' value="next12">Next 12PM  </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:13') { $html=$html. 'SELECTED'; } $html=$html. ' value="next13">Next 1PM  </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:14') { $html=$html. 'SELECTED'; } $html=$html. ' value="next14">Next 2PM  </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:15') { $html=$html. 'SELECTED'; } $html=$html. ' value="next15">Next 3PM </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:16') { $html=$html. 'SELECTED'; } $html=$html. ' value="next16">Next 4PM </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:17') { $html=$html. 'SELECTED'; } $html=$html. ' value="next17">Next 5PM </option>';
$html=$html. "\n".' <option ';  if ($thissldtime=='00:00:18') { $html=$html. 'SELECTED'; } $html=$html. ' value="next18">Next 6PM </option>';
$html=$html. ' </select></div> ';


// $html=$html.'<div id="newjobsubmita" class="fs"><div class="fsl">    </div> <button class="newjobsubmit" type="submit"> Create New Job </button></div>';


$html=$html.' </div> <div class="cbbnewjobr"> ';




$query = "SELECT 
chargedbybuildid, 
cbbname
FROM chargedbybuild 
WHERE cbbcost <> '0.00'
AND chargedbybuildid > 3
ORDER BY cbborder"; 
$result_id = mysql_query ($query, $conn_id); 

$i='1';



// $html=$html. '<table><tbody>';
while (list ($chargedbybuildid, $cbbname) = mysql_fetch_row ($result_id)) { 
$cbbname = htmlspecialchars ($cbbname); 
// $html=$html. '<tr><td> ';




$html=$html. '<div class="fs">
<div class="fsl">    <input type="checkbox" name="chkcbb'.$chargedbybuildid.'" value="1" '; 
$html=$html.'></div> '.$cbbname.' '.$row["cbb$chargedbybuildid"].' </div> ';

// $html=$html. '</td></tr>';
$i=$i+'1';
} // ends loop for valid cbbs




$html=$html. '<div class="fs" > <div class="fsl"> </div>
<button class="newjobsubmit" type="submit"> Create New Job </button></div></div>
<div class="clear"> </div> ';

echo $html;

?>
<script type="text/javascript">
$( "#frombox" ).frombox();
$( "#tobox" ).tobox();
	
$("input[name=requestedby]").focus().setCursorPosition(42);
	
clientdetails=" <a href='new_cojm_client.php?clientid=<?php echo $newjobclientid; ?>' target='_blank' class='showclient' " + 
" title='<?php echo $row['CompanyName']; ?> Details' > &nbsp; </a> <?php echo $invhtml; ?> "

$("div#afterclientselect").html(clientdetails);

</script>
<?php

} // ends check for having / not having departments


} else { echo 'ERROR : Unable to get client details from database.'; }
} // ends check for client

?>