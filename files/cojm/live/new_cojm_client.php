<?php 



$alpha_time = microtime(TRUE);
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>'0') {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$title = "COJM";
$hasforms='1';



?><!doctype html>
<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, height=device-height" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta name="HandheldFriendly" content="true" >
<?php
echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
<title>Client Details</title>
</head><body>';

include "changejob.php";

if (isset($clientid)) {} else { if (isset($_POST['clientid'])) { $clientid=trim($_POST['clientid']); } else { $clientid=''; } }

if (!$clientid) { if (isset($_GET['clientid'])) { $clientid=trim($_GET['clientid']); }}


$adminmenu='1';
$filename="new_cojm_client.php";

include "cojmmenu.php"; 

echo '<div class="Post Spaceout">

<div class="ui-state-highlight ui-corner-all p15">

<form action="new_cojm_client.php" method="get">';

$query = "SELECT CustomerID, CompanyName FROM Clients ORDER BY CompanyName"; 
$result_id = mysql_query ($query, $conn_id); 

echo '
<select class="ui-state-default ui-corner-all pad"  id="combobox" name="clientid" ><option value="">Select one...</option>';
 while (list ($CustomerIDlist, $CompanyName) = mysql_fetch_row ($result_id)) { $CustomerID = htmlspecialchars ($CustomerID); 
$CompanyName = htmlspecialchars ($CompanyName); print"<option "; if ($CustomerIDlist == $clientid) {echo "SELECTED "; } ; 
echo ' value="'.$CustomerIDlist.'">'.$CompanyName.'</option>';} echo '</select> ';


echo '
<button type="submit"> Select Client </button> 
</form>


';


$sql = "SELECT * FROM Clients WHERE CustomerID = '$clientid' LIMIT 0,1";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$row=mysql_fetch_array($sql_result);

if ($row['isdepartments']=='1') {
echo '

<br/> 
<form action="new_cojm_department.php" method="post">
<input type="hidden" name="page" value="selectclientdepartment" >
<input type="hidden" name="formbirthday" value="'.date("U").'">
<input type="hidden" name="clientid" value="'.$row['CustomerID'].'">
<button type="submit"> Switch to '.$row['CompanyName'].' departments</button>
</form>';
}

if (!$clientid) {

echo ' 
<br />

<form action="#" method="post">
<input type="hidden" name="page" value="createnewcl" />
<button type="submit"> Create new Client </button>
<input type="hidden" name="formbirthday" value="'. date("U").'">
Name : <input class="ui-state-default ui-corner-all pad" type="text" name="CompanyName" size="15" />
</form>';

}

echo '
</div>
<div class="vpad"> </div>';


if ($clientid)  {



echo '
<form action="#" method="post">
<input type="hidden" name="formbirthday" value="'. date("U").'">
<input type="hidden" name="page" value="editclient" >

<input type="hidden" name="clientid" value="'.$row['CustomerID'].'">

<div id="tabs">
<ul>
<li><a href="#tabs-7">'.$row['CompanyName'].'</a></li>
<li><a href="#tabs-1">Details</a></li>
<li><a href="#tabs-2">Contact</a></li>
<li><a href="#tabs-3">Invoicing</a></li>
<li><a href="#tabs-5">Favourites</a></li>
<li><a href="#tabs-4">COJM Options</a></li>
<li><a href="#tabs-6">CO2 Stats</a></li>
</ul>



<div id="tabs-1">


<fieldset><label class="fieldLabel"> Client Name </label>
<input type="text" class="ui-state-default ui-corner-all pad" name="CompanyName" value="'.$row['CompanyName']; ?>"></fieldset>


<fieldset><label  class="fieldLabel">  Is Active Client </label>
<input type="checkbox" name="isactiveclient" value="1" <?php if ($row['isactiveclient']>0) { echo 'checked';} ?> > </fieldset>

<fieldset><label class="fieldLabel"> Uses Departments </label>
<input type="checkbox" name="isdepartments" value="1" <?php if ($row['isdepartments']>0) { echo 'checked';} ?> >  </fieldset>

<fieldset><label  class="fieldLabel"> Phone Number </label> 
<input type="text" class="ui-state-default ui-corner-all pad" name="PhoneNumber" value="<?php echo $row['PhoneNumber']; ?>"> </fieldset>

<fieldset><label class="fieldLabel"> Client Email </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="40" name="emailaddress"
value="<?php echo $row['EmailAddress']; ?>"> </fieldset>

<fieldset><label class="fieldLabel" >Notes   </label><textarea id="Notes" class="normal ui-state-default ui-corner-all pad" 
name="Notes" style="width: 65%; outline: none; height:20px;"><?php echo trim($row['Notes']); ?></textarea> </fieldset>
(shown in other staff COJM logins)
 
<?php 

echo '<br /><div class="line"></div><br />
<button type="submit" formaction="#tabs-1" > Edit Client Details </button>
<br /><div class="line"></div><br />
';


 ?>
</div>
<div id="tabs-2">

<fieldset><label >  Title </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="6" name="Title" value="<?php echo $row['Title']; ?>"> </fieldset>
<fieldset><label >  Forename </label>
 <input type="text" class="ui-state-default ui-corner-all pad" name="Forname" value="<?php echo $row['Forename']; ?>"> </fieldset>
<fieldset><label > Client Surname </label>
 <input type="text" class="ui-state-default ui-corner-all pad" name="Surname" value="<?php echo $row['Surname']; ?>"> </fieldset>
<fieldset><label > Client Mobile </label>
 <input type="text" class="ui-state-default ui-corner-all pad"  size="15" name="MobileNumber" value="<?php echo $row['MobileNumber']; ?>"> </fieldset>
 
 <div class="line"> </div>

<fieldset><label > St Number </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="40" name="Address" value="<?php echo $row['Address']; ?>"> </fieldset>
<fieldset><label > St Name </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="40" name="Address2" value="<?php echo $row['Address2']; ?>"> </fieldset>
<fieldset><label  class="fieldLabel">  City </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="City" value="<?php echo $row['City']; ?>"> </fieldset>
<fieldset><label > County  </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="County" value="<?php echo $row['County']; ?>"> </fieldset>
<fieldset><label  > Country  </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="CountryOrRegion" value="<?php echo $row['CountryOrRegion']; ?>"> </fieldset>
<fieldset><label >  Postcode </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="12" name="Postcode" value="<?php echo $row['Postcode']; ?>"> 
  </fieldset>
 <?php
 
  if (trim($row['Address']))  { echo $row['Address'] .', '; }
 if (trim($row['Address2'])) { echo $row['Address2'] .', '; }
 if (trim($row['City']))     { echo $row['City'] .', '; }
 if (trim($row['County']))   { echo $row['County'].', '; }
 
 if (trim($row['Postcode'])) { echo ' <a target="_blank" href="http://maps.google.co.uk/maps?q='. $row['Postcode']. '">'. $row['Postcode'].'</a>'; }

 
 
 
 
 
 
 echo '
<br /><div class="line"></div><br />
<button type="submit" formaction="#tabs-2" > Edit Client Details </button>
<br /><div class="line"></div><br />
';

 ?>
 
 </div>







<div id="tabs-3">


<fieldset><label > 
<strong>Invoicing</strong> Terms : </label>
<select name="invoicetype" class="ui-state-default ui-corner-left" >
<option <?php if ($row['invoicetype']=='0') { echo 'selected '; } ?>value="0" >Account - Payment after Invoice (monthly invoice)</option>
<option <?php if ($row['invoicetype']=='1') { echo 'selected '; } ?>value="1" >Website Booked Pre-pay</option>
<option <?php if ($row['invoicetype']=='2') { echo 'selected '; } ?>value="2" >Immediate - Payment on ordering (Visa over phone)</option>
<option <?php if ($row['invoicetype']=='3') { echo 'selected '; } ?>value="3" >Payment on Collection (via Courier)</option>
<option <?php if ($row['invoicetype']=='4') { echo 'selected '; } ?>value="4" >Payment on Delivery (via Courier)</option>
<option <?php if ($row['invoicetype']=='5') { echo 'selected '; } ?>value="5" >Client in Credit Prepay</option>
</select>
 If account, due 
<input type="text" size="2" class="ui-state-default ui-corner-all pad" name="invoiceterms" value="<?php echo $row['invoiceterms']; ?>"> days from sending.
</fieldset>



<fieldset><label >  Email </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="60" name="invoiceemailaddress" value="<?php echo $row['invoiceEmailAddress']; ?>">
 </fieldset>
 

<fieldset><label > St Number </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="40" name="invoiceAddress" value="<?php echo $row['invoiceAddress']; ?>"> 
 </fieldset>
 
 
<fieldset><label >   St Name  </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="40" name="invoiceAddress2" value="<?php echo $row['invoiceAddress2']; ?>"> 
 </fieldset>
 
 
 
<fieldset><label >  City  </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="20" name="invoiceCity" value="<?php echo $row['invoiceCity']; ?>">
</fieldset>


<fieldset><label >  County   </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="invoiceCounty" value="<?php echo $row['invoiceCounty']; ?>">
</fieldset>


<fieldset><label > Country  </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="20" name="invoiceCountryOrRegion" value="<?php echo $row['invoiceCountryOrRegion']; ?>">
</fieldset>

<fieldset><label  >  Postcode </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="12" name="invoicePostcode" value="<?php echo $row['invoicePostcode']; ?>">
 <a target="_blank" href="http://maps.google.co.uk/maps?q=<?php echo $row['invoicePostcode'];?>"><?php echo $row['invoicePostcode']; ?></a> 
</fieldset>

 
<fieldset><label >  VAT Reg </label>
  <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="clientvatno" value="<?php echo $row['clientvatno']; ?>">
  </fieldset>
  
  <fieldset><label>  Comp House Reg </label>
  <input type="text" class="ui-state-default ui-corner-all pad" size="20" name="clientregno" value="<?php echo $row['clientregno']; ?>">
</fieldset> 

  <fieldset><label  >  Percentage Discount </label>
 <input type="text" class="ui-state-default ui-corner-all pad" size="6" name="cbbdiscount" value="<?php echo $row['cbbdiscount']; ?>"> %.

discount on each job for being good client 

</fieldset>



<?php

echo '
<br /><div class="line"></div><br />
<button type="submit" formaction="#tabs-3" > Edit Client Details </button>
<br /><div class="line"></div><br />
';

?>
</div>



<div id="tabs-4">


<fieldset><label > Joomla User </label>
 <input class="ui-state-default ui-corner-all caps" type="text" size="4" name="JoomlaUser" value="<?php echo $row['JoomlaUser']; ?>"> 
 Test Login <?php echo $globalprefrow['testjoomlalogin']; ?> 
 </fieldset>
 
<fieldset><label > Joomla User2 </label>
 <input class="ui-state-default ui-corner-all caps" type="text" size="4" name="JoomlaUser2" value="<?php echo $row['JoomlaUser2']; ?>"> </fieldset>
 
<fieldset><label > Joomla User3 </label>
 <input class="ui-state-default ui-corner-all caps" type="text" size="4" name="JoomlaUser3" value="<?php echo $row['JoomlaUser3']; ?>"> </fieldset>

<fieldset><label > CO2 API Ref </label>
 <input class="ui-state-default ui-corner-all pad" type="text" size="11" name="co2apiref" value="<?php echo $row['co2apiref']; ?>"> (12) chars 
</fieldset>
 
  <fieldset><label > COJM Client ID </label>
  <?php echo $clientid; ?> 
</fieldset>
 <div class="vpad"> </div>
 
 
<input type="hidden" name="htmlemail" value="0" /> 

<?php

 echo '  <fieldset><label>   </label>';
 
 
// echo '<input type="checkbox" name="cemail1" value="1" '; 
// if ($row['cemail1']>0) { echo 'checked';} 
// echo '> ';

echo 'Send auto email job complete - NOT YET Working Will  Send email to client / department when job goes from needing admin to complete.</fieldset>';
 
echo ' 
<input type="hidden" name="cemail2" value="0" />
<input type="hidden" name="cemail3" value="0" /> 
<input type="hidden" name="cemail4" value="0" />
<input type="hidden" name="cemail5" value="0" />
';
 
 echo '
<br /><div class="line"></div><br />
<button type="submit" formaction="#tabs-4" > Edit Client Details </button>
<br /><div class="line"></div><br />
</div>';
 
 



// sets defaults

echo '<div id="tabs-5">';

if ($row['isdepartments']=='1') {

echo '<strong>

These defaults will be only be used if there are no department defaults set.</strong><div class="vpad"> </div>'; }

echo '
<fieldset><label > Default Requestor </label>
<input type="text" class="caps ui-state-default ui-corner-all" size="20" name="defaultrequestor" value="'. $row['defaultrequestor'].'"></fieldset>
<fieldset><label > Default Service </label>';
 

   ////////////   SERVICE           ////////////////
$query = "
SELECT ServiceID, 
Service 
FROM Services 
WHERE activeservice='1' 
ORDER BY serviceorder DESC, ServiceID ASC"; 
$result_id = mysql_query ($query, $conn_id); 
print ("<select class=\"ui-state-default ui-corner-left\" name=\"defaultservice\">"); 

// if (!$row['depservice']) {  }

echo ' <option value=""> No Default </option> ';

while (list ($ServiceID, $Service) = mysql_fetch_row ($result_id)) {	$ServiceID = htmlspecialchars ($ServiceID);	
$Service = htmlspecialchars ($Service); print ("<option "); 
{	if ($row['defaultservice'] == $ServiceID) echo " SELECTED "; }	
print ("value=\"$ServiceID\">$Service</option>"); } print ("</select></fieldset> "); 
/////////     ENDS    SERVICE ///////////////////////////////////////////////



echo '<fieldset><label > Default PU </label>';
$query = "SELECT favadrid, favadrft, favadrpc 
FROM cojm_favadr 
WHERE favadrisactive='1'
AND favadrclient = '$clientid' "; 
$result_id = mysql_query ($query, $conn_id); 
print ("<select class=\"ui-state-default ui-corner-left\" name=\"defaultfrom\">"); 
echo '<option value="">No Default</option>';
while (list ($favadrid, $favadrft, $favadrpc) = mysql_fetch_row ($result_id)) {
$favadrft = htmlspecialchars ($favadrft);	
$favadrpc = htmlspecialchars ($favadrpc); 
print ("<option "); 
if ($row['defaultfromtext'] == $favadrid) { echo " SELECTED "; }	
print ("value=\"$favadrid\">$favadrft $favadrpc</option>"); } print ("</select>"); 
/////////     ENDS  DEFAULT PU ///////////////////////////////////////////////
echo '</fieldset>';




echo '<fieldset><label > Default Drop </label>';
$query = "SELECT favadrid, favadrft, favadrpc 
FROM cojm_favadr 
WHERE favadrisactive='1'
AND favadrclient = '$clientid' "; 
$result_id = mysql_query ($query, $conn_id); 
print ("<select class=\"ui-state-default  pad ui-corner-left\" name=\"defaultto\">"); 
echo '<option value="">No Default</option>';
while (list ($favadrid, $favadrft, $favadrpc) = mysql_fetch_row ($result_id)) {
$favadrft = htmlspecialchars ($favadrft);	
$favadrpc = htmlspecialchars ($favadrpc); 
print ("<option "); 
if ($row['defaulttotext'] == $favadrid) { echo " SELECTED "; }	
print ("value=\"$favadrid\">$favadrft $favadrpc</option>"); } print ("</select>"); 
/////////     ENDS  DEFAULT PU ///////////////////////////////////////////////
echo '</fieldset>';












 
 $sql="SELECT * FROM cojm_favadr
WHERE  favadrclient = '$clientid' AND favadrisactive ='1' LIMIT 0,50";

$sql_result = mysql_query($sql,$conn_id);

$sumtot=mysql_affected_rows(); if ($sumtot>'0')  { 


echo '
<div class="vpad"> </div>
<table class="acc"><tbody>

<tr>
<td> Click Address to edit </td>
<td>Postcode</td>
<td>Comments</td>
<td>Last Visited</td>
<td>Tags</td>
</tr>';


while ($row = mysql_fetch_array($sql_result)) { extract($row);
$PC=$row['favadrpc'];

$PC= str_replace(" ", "%20", "$PC", $count);

echo ' <tr><td>
<a title="Edit Favourite" href="favusr.php?page=selectfavadr&amp;clientid='.$clientid.'&amp;thisfavadrid='.$row['favadrid'].'" >'.$row['favadrft'].'</a>
</td><td>
 <a target="_blank" href="http://maps.google.co.uk/maps?q='. $PC. '">'. $row['favadrpc'].'</a>
</td><td>
'. $row['favadrcomments'].'
</td><td>';

if ($row['favadrlastvisit']>0) { echo date(' H:i D j M Y', strtotime($row['favadrlastvisit'])); }

echo '
</td>
<td>
';

if ($row['favusr1']=='1') { echo $globalprefrow['favusrn1'].' '; }
if ($row['favusr2']=='1') { echo $globalprefrow['favusrn2'].' '; }
if ($row['favusr3']=='1') { echo $globalprefrow['favusrn3'].' '; }
if ($row['favusr4']=='1') { echo $globalprefrow['favusrn4'].' '; }
if ($row['favusr5']=='1') { echo $globalprefrow['favusrn5'].' '; }
if ($row['favusr6']=='1') { echo $globalprefrow['favusrn6'].' '; }
if ($row['favusr7']=='1') { echo $globalprefrow['favusrn7'].' '; }
if ($row['favusr8']=='1') { echo $globalprefrow['favusrn8'].' '; }
if ($row['favusr9']=='1') { echo $globalprefrow['favusrn9'].' '; }
if ($row['favusr10']=='1') { echo $globalprefrow['favusrn10'].' '; }
if ($row['favusr11']=='1') { echo $globalprefrow['favusrn11'].' '; }
if ($row['favusr12']=='1') { echo $globalprefrow['favusrn12'].' '; }
if ($row['favusr13']=='1') { echo $globalprefrow['favusrn13'].' '; }
if ($row['favusr14']=='1') { echo $globalprefrow['favusrn14'].' '; }
if ($row['favusr15']=='1') { echo $globalprefrow['favusrn15'].' '; }
if ($row['favusr16']=='1') { echo $globalprefrow['favusrn16'].' '; }
if ($row['favusr17']=='1') { echo $globalprefrow['favusrn17'].' '; }
if ($row['favusr18']=='1') { echo $globalprefrow['favusrn18'].' '; }
if ($row['favusr19']=='1') { echo $globalprefrow['favusrn19'].' '; }
if ($row['favusr20']=='1') { echo $globalprefrow['favusrn20'].' '; }

 
echo ' </td></tr>';


}
 
 
echo '</table>';


if ($sumtot>'49')  { echo ' Only 50 favourites shown for performance reasons. '; }


} else { // ends check for favourites

echo ' No favourite adresses for this contact.';
}
echo '
<br /><div class="line"></div>
<button type="submit" formaction="#tabs-5" > Edit Client Details </button>
<br /><div class="line"></div>
 </div>
';
 





echo ' 
<div id="tabs-6">
';

if ($row['lastinvoicedate']>'1') {
 echo '<fieldset><label > Invoiced until </label> '.date(' D j M Y', strtotime($row['lastinvoicedate'])) . '</fieldset>'; }  
 
 
 
 
 
 
 
 
 
$tableco2='';
$yeartableco2=''; 
$lasttableco2='';
$tablepm10='';
$yeartablepm10='';
$lasttablepm10='';
$thisyear = date("Y");
$lastyear = $thisyear-'1';
 

$sql="SELECT ShipDate, co2saving, CO2Saved, numberitems, pm10saving, PM10Saved FROM Orders, Services 
WHERE Orders.ServiceID = Services.ServiceID 
AND Orders.CustomerID = '$clientid' 
AND Orders.status >= 77 ";

$sql_result = mysql_query($sql,$conn_id);

while ($row = mysql_fetch_array($sql_result)) { extract($row);
$newSqlString = date('Y', strtotime($row['ShipDate']));
	
if ($row['co2saving']) {
 
 $tableco2=$tableco2+$row["co2saving"];
 if ($newSqlString==$thisyear) { $yeartableco2=$yeartableco2+$row["co2saving"]; }
 if ($newSqlString==$lastyear) { $lasttableco2=$lasttableco2+$row["co2saving"]; }
 
 } else if ($row['CO2Saved'])  { 
 
$tableco2=$tableco2+($row['CO2Saved']*$row["numberitems"]);
  
if ($newSqlString==$thisyear) { $yeartableco2=$yeartableco2+($row['CO2Saved']*$row["numberitems"]); }
if ($newSqlString==$lastyear) { $lasttableco2=$lasttableco2+($row['CO2Saved']*$row["numberitems"]); }
} 

 
 
 
 
 
if ($row['pm10saving']>'0.01')  { 
$tablepm10=$tablepm10+($row['pm10saving']);
 if ($newSqlString==$thisyear) { $yeartablepm10=$yeartablepm10+$row["pm10saving"]; }
 if ($newSqlString==$lastyear) { $lasttablepm10=$lasttablepm10+$row["pm10saving"]; }

} else if ($row['PM10Saved']<>'0.0') { 
$tablepm10=$tablepm10+($row['PM10Saved']*$row["numberitems"]);
if ($newSqlString==$thisyear) { $yeartablepm10=$yeartablepm10+($row['PM10Saved']*$row["numberitems"]); }
if ($newSqlString==$lastyear) { $lasttablepm10=$lasttablepm10+($row['PM10Saved']*$row["numberitems"]); }
} 


} // ends row loop





 // totals under here
if ($tablepm10>'1000') {
$tablepm10=($tablepm10/'1000');
$tablepm10 = number_format($tablepm10, 1, '.', ',');
$tablepm10= $tablepm10.' Kg '; 
} else {
 if ($tablepm10>'1') { $tablepm10=$tablepm10.' grams'; 
}
}



  if ($yeartablepm10>'1000') {
$yeartablepm10=($yeartablepm10/'1000');
$yeartablepm10 = number_format($yeartablepm10, 1, '.', ',');
$yeartablepm10= $yeartablepm10.' Kg '; 
} else {
 if ($yeartablepm10>'1') { $yeartablepm10=$yeartablepm10.' grams'; 
}
}



  if ($lasttablepm10>'1000') {
$lasttablepm10=($lasttablepm10/'1000');
$lasttablepm10 = number_format($lasttablepm10, 1, '.', ',');
$lasttablepm10= $lasttablepm10.' Kg '; 
} else {
 if ($lasttablepm10>'1') { $lasttablepm10=$lasttablepm10.' grams'; 
}
}





 
 
 
 
if ($tableco2>'1000') {
$tableco2=($tableco2/'1000');
$tableco2 = number_format($tableco2, 1, '.', ',');
$tableco2= $tableco2.' Kg '; }
 else {
 if ($tableco2>'1') { $tableco2=$tableco2.' grams'; 
}
}
 
if ($yeartableco2>'1000') {
$yeartableco2=($yeartableco2/'1000');
$yeartableco2 = number_format($yeartableco2, 1, '.', ',');
$yeartableco2= $yeartableco2.' Kg '; 
} else {
 if ($yeartableco2>'1') { $yeartableco2=$yeartableco2.' grams'; 
}
}


if ($lasttableco2>'1000') {
$lasttableco2=($lasttableco2/'1000');
$lasttableco2 = number_format($lasttableco2, 1, '.', ',');
$lasttableco2= $lasttableco2.' Kg '; 
} else {
 if ($lasttableco2>'1') { $lasttableco2=$lasttableco2.' grams'; 
}
}







if ($yeartableco2=='') { $yeartableco2='0 grams'; }
if ($tablepm10=='') { $tablepm10='0 grams'; }
if ($tableco2=='') { $tableco2='0 grams'; }


//	echo $globalprefrow['adminlogo'];
//  echo "/images/".basename($globalprefrow['adminlogo']);


	
echo '

<div class="vpad"> </div>

<fieldset><label > Total CO<sub>2</sub> saved </label>'.$tableco2.'</fieldset><div class="vpad"> </div>
<fieldset><label > CO<sub>2</sub> in '.$lastyear.' </label>'.$lasttableco2.'</fieldset><div class="vpad"> </div>
<fieldset><label > CO<sub>2</sub> in '.$thisyear.' </label>'.$yeartableco2.'</fieldset><div class="vpad"> </div>
<fieldset><label > Total PM<sub>10</sub> saved </label>'.$tablepm10.'</fieldset><div class="vpad"> </div>
<fieldset><label > PM<sub>10</sub> in '.$lastyear.' </label>'.$lasttablepm10.'</fieldset><div class="vpad"> </div>
<fieldset><label > PM<sub>10</sub> in '.$thisyear.' </label>'.$yeartablepm10.'</fieldset><br /><div class="line"></div><br />
<button type="submit" formaction="#tabs-6" > Edit Client Details </button>
<br /><div class="line"></div><br />





</div> 



<div id="tabs-7">



';

$less65vol='0';
$less65net='0';
$less65cnt='0';

$c65vol='0';
$c65net='0';
$c65cnt='0';

$c108vol='0';
$c108net='0';
$c108cnt='0';


$totvol='0';
$totnet='0';
$totcnt='0';

  $thisyrvol='0';
  $thisyrnet='0';
  $thisyrcnt='0';
  
  
    $lastyrvol='0';
  $lastyrnet='0';
  $lastyrcnt='0';
  
  
$order90vol='0';
$order90net='0';
$order90cnt='0';	


 $order180vol='0';
$order180net='0';
$order180cnt='0';	 

  $thisyear=date("Y");
  $lastyear=date("Y")-'1';
  
$fromarray=array();
$toarray=array();


$format = 'Y-m-d 00:00:00';   
 
$date = date ( $format );   
  
  
$date90ago=  date ( $format, strtotime ( '-90 day' . $date ) );
$date180ago=  date ( $format, strtotime ( '-180 day' . $date ) ); 
  
  
$date90agou=  date ("U", strtotime ($date90ago));  
$date180agou=  date ("U", strtotime ($date180ago));    
// echo ' 900 days ago? '. $date180ago;   
// echo ' 180 days ago? '. $date180ago;  

 
 $newsql = "SELECT numberitems, FreightCharge, vatcharge, status , ShipDate, fromfreeaddress, CollectPC, tofreeaddress, ShipPC 
 FROM Orders WHERE Orders.CustomerID = '$clientid' ";


 $sql_result = mysql_query($newsql,$conn_id) or die(mysql_error()); 
	 $bigtot=mysql_affected_rows();
 if ($bigtot>"0") { 
 
 
 // echo $bigtot.' total jobs.'; 
 
 while ($row = mysql_fetch_array($sql_result)) {   extract($row);
 
 
 $checkdate= date ("U", strtotime ($row['ShipDate']));  
 
 
// echo ' <br /> '. $date180ago.' '. $row['ShipDate'].' '.$date180agou.' '.$checkdate;
 
 
 $tempaddress=$row['fromfreeaddress'].' '.$row['CollectPC'];
 
   if (trim($tempaddress)<>'') {
//	   echo ' found address '. $tempaddress;
	   array_push($fromarray,$tempaddress);     }
	   
	  $tempaddress=$row['tofreeaddress'].' '.$row['ShipPC'];
 
   if (trim($tempaddress)<>'') {
//	   echo ' found address '. $tempaddress;
	   array_push($toarray,$tempaddress);     }  
 

	 
 if ($checkdate>=$date180agou) { 
 
 
 $order180vol=$order180vol+$row['numberitems'];
$order180net=$order180net+$row['FreightCharge']+$row['vatcharge'];
$order180cnt++;



if (($checkdate>=$date90agou)) {
$order90vol=$order90vol+$row['numberitems'];
$order90net=$order90net+$row['FreightCharge']+$row['vatcharge'];
$order90cnt++;	 
 }

 
 }
 
 
 
 
 
 if (($row['status'])<'65') {
$less65vol=$less65vol+$row['numberitems'];
$less65net=$less65net+$row['FreightCharge']+$row['vatcharge'];
$less65cnt++;
 }
 
  if (($row['status'])=='65') {
$c65vol=$c65vol+$row['numberitems'];
$c65net=$c65net+$row['FreightCharge']+$row['vatcharge'];
$c65cnt++;
 }
 
 
   if ((($row['status'])>'65') and (($row['status'])<'108')) {
$c108vol=$c108vol+$row['numberitems'];
$c108net=$c108net+$row['FreightCharge']+$row['vatcharge'];
$c108cnt++;
   }
   
   
  if (date('Y', strtotime($row['ShipDate']))==$thisyear) { 
  $thisyrvol=$thisyrvol+$row['numberitems'];
  $thisyrnet=$thisyrnet+$row['FreightCharge']+$row['vatcharge'];
  $thisyrcnt++;
  }
   
  
  if (date('Y', strtotime($row['ShipDate']))==$lastyear) { 
  $lastyrvol=$lastyrvol+$row['numberitems'];
  $lastyrnet=$lastyrnet+$row['FreightCharge']+$row['vatcharge'];
  $lastyrcnt++;
  }  
   
   
//  if () {
//	   array_push($gpxarray,$row['publictrackingref']);   
//  }
   
$totvol=$totvol+$row['numberitems'];
$totnet=$totnet+$row['FreightCharge']+$row['vatcharge'];
$totcnt++;
   
 } // ends row loop

 
 
 
 
 
 
 
 } else { echo '<h1>No Job Data Available</h1>'; }









	  

	
echo '


<table class="clientstats acc">
<tbody>
<tr>
<th> Job Status </th>
<th> Num Jobs</th>
<th> Num Items </th>
<th> Net Cost </th>
</tr>
	
	

	
<tr>
<td>Scheduled / Paused</td>
<td> '.formatMoney($less65cnt).'</td>
<td> '.formatMoney($less65vol).'</td>
<td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($less65net, 2, '.', ',').'</td>
</tr>	
	
	
<tr>
<td> En-Route </td>
<td> '.$c65cnt.'</td>
<td> '.$c65vol.'</td>
<td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($c65net, 2, '.', ',').'</td>
</tr>
	
<tr>
<td> Complete / Uninvoiced </td>
<td> '.formatMoney($c108cnt).'</td>
<td> '.formatMoney($c108vol).'</td>
<td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($c108net, 2, '.', ',').'</td>
</tr>	
	
 <tr>
<td title="Completed Jobs"> 90 Day Avg </td>
<td> '.formatMoney(number_format((($order90cnt / '90')), 2, '.', '')).'</td>
<td> '.formatMoney(number_format((($order90vol / '90')), 2, '.', '')).'</td>
<td class="rh"> &'.$globalprefrow['currencysymbol'].number_format((($order90net / '90')), 2, '.', ',').'</td>
</tr>
 
 
 
  <tr>
<td title="Completed Jobs"> 180 Day Avg </td>
<td> '.formatMoney(number_format((($order180cnt / '180')), 2, '.', '')).'</td>
<td> '.formatMoney(number_format((($order180vol / '180')), 2, '.', '')).'</td>
<td class="rh"> &'.$globalprefrow['currencysymbol'].number_format((($order180net / '180')), 2, '.', ',').'</td>
</tr>	
	
	
	
	
	  <tr>
<td title="Completed Jobs"> '.$thisyear.' </td>
<td> '.formatMoney($thisyrcnt).'</td>
<td> '.formatMoney($thisyrvol).'</td>
<td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($thisyrnet, 2, '.', ',').'</td>
</tr>	


  <tr>
<td title="Completed Jobs"> '.$lastyear.' </td>
<td> '.formatMoney($lastyrcnt).'</td>
<td> '.formatMoney($lastyrvol).'</td>
<td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($lastyrnet, 2, '.', ',').'</td>
</tr>	
	
		  <tr>
<td title="Completed Jobs" >Total All Time</td>
<td> '.formatMoney($totcnt).'</td>
<td> '.formatMoney($totvol).'</td>
<td class="rh"> &'.$globalprefrow['currencysymbol'].number_format($totnet, 2, '.', ',').'</td>
</tr>
	
	</tbody>
	
 </table> 

 
 
 <table class="acc clientstats">

 <tbody>
 <tr>
<th> Invoice Status </th>
<th> Debtor Days</th>
<th> Num Items </th>
<th> Net Cost </th>
</tr>
	
	
<tr>
<td>Outstanding </td>
<td> </td>
<td> </td>
<td class="rh"> </td>
</tr>	
	
<tr>
<td> Paid This Year </td>
<td> </td>
<td> </td>
<td class="rh"> </td>
</tr>	
 
 <tr>
<td> Paid Last Year </td>
<td> </td>
<td> </td>
<td class="rh"> </td>
</tr>


 <tr>
<td> 90 Day Avg </td>
<td> </td>
<td> </td>
<td class="rh"> </td>
</tr>
 
 
 
  <tr>
<td> 180 Day Avg </td>
<td> </td>
<td> </td>
<td class="rh"> </td>
</tr>
 
   <tr>
<td> Total</td>
<td> </td>
<td> </td>
<td class="rh"> </td>
</tr>
 
 </tbody>
</table> 
 
 <div style="clear:both"> </div>
 

';
 
 
 
 
 
 
 
 
 
 
 
 
 $frequencies = array_count_values($fromarray);
arsort($frequencies); // Sort by the most frequent matches first.
$tenFrequencies = array_slice($frequencies, 0, 10, TRUE); // Only get the top 10 most frequent
$topTenfrom = array_keys($tenFrequencies);
 
 
 if (count($tenFrequencies)) {
 
echo ' <table class="clientstats acc">
<tbody>
<tr>
<th colspan="2">Top '.count($tenFrequencies).'   PU locations </th></tr>'; 
 
foreach ($tenFrequencies as $key => $value) {
       echo "<tr><td> 	$value </td><td> $key </td></tr>";
}
echo '</tbody></table>';
 }
 
 $frequencies = array_count_values($toarray);
arsort($frequencies); // Sort by the most frequent matches first.
$tenFrequencies = array_slice($frequencies, 0, 10, TRUE); // Only get the top 10 most frequent
$topTento = array_keys($tenFrequencies);
 
 


 if (count($tenFrequencies)) {
 
echo '


<table class="clientstats acc">
<tbody>
<tr>
<th colspan="2">Top '.count($tenFrequencies).'   Drop locations </th></tr>';
 

 
foreach ($tenFrequencies as $key => $value) {
    echo "<tr><td> 	$value </td><td> $key </td></tr>";
	
}

echo '</tbody></table>';

 }




 














$sql = "SELECT *
FROM `Orders`
INNER JOIN Clients
INNER JOIN Services 
INNER JOIN Cyclist
INNER JOIN status

ON Orders.CustomerID = Clients.CustomerID
AND Orders.ServiceID = Services.ServiceID
AND Orders.CyclistID = Cyclist.CyclistID 
AND Orders.status = status.status 
WHERE `Orders`.`status` >70
	 AND Orders.CustomerID = '$clientid' 
ORDER BY `Orders`.`Shipdate` DESC
LIMIT 0 , 10";



// execute SQL query and get result
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error());



echo '


<table style="position:relative; float:left;" class="acc" >
<tbody>

<tr>
<th scope="col">Last 10 Drops</th>
<th scope="col"> </th>
<th scope="col"> </th>';

  if (($row['isdepartments']=='1'))   { 



echo '
<th scope="col">Dep</th> ';


}

echo '
<th scope="col">Service</th>
<th scope="col">Status</th>
<th scope="col">Comments</th>
</tr>



';



while ($row = mysql_fetch_array($sql_result)) {
     extract($row);
	 
	 
	 
//	 $numberitems= number_format($row['numberitems'], 0, '.', ',');
	 

$CollectPC=$row['CollectPC'];
$ShipPC=$row['ShipPC'];
$prShipPC= str_replace(" ", "%20", "$ShipPC", $count);
$prCollectPC= str_replace(" ", "%20", "$CollectPC", $count);
	

echo '<tr><td><a href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a> ';

	
echo ''.date('H:i A D j M ', strtotime($row['ShipDate'])); 

echo '</td><td>';

echo $row['cojmname'].'</td>';

echo '
<td><a target="_blank" href="http://maps.google.co.uk/maps?q='. $prCollectPC.'">'. $CollectPC.'</a>';
if ((!$ShipPC) or ($ShipPC==' ')) {} else {echo " to "; }
echo '<a target="_blank" href="http://maps.google.co.uk/maps?q='. $prShipPC.'">'. $ShipPC.'</a></td>

';

  if (($row['isdepartments']=='1'))   {




echo '


<td>';




$tempdep=$row['orderdep'];

$depsql="SELECT depname from clientdep 
INNER JOIN Orders
On Orders.orderdep=clientdep.depnumber 
WHERE Orders.orderdep='$tempdep' LIMIT 0,1";
$dsql_result = mysql_query($depsql,$conn_id)  or mysql_error();

while ($drow = mysql_fetch_array($dsql_result)) { extract($drow); echo ' ('.$drow['depname'].') '; }





echo '</td>';



  }
  
  
  echo '
<td>'.formatmoney($row["numberitems"]).' x '. $row['Service'].'</td>
<td>'. $row['statusname'] .'</td>
<td>'. $row['jobcomments'].' '.$row['privatejobcomments'].'</td>
</tr>';
// echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';


// End while loop
}





echo '



</tbody>
</table>


<div style="clear:both;"> </div>



debtor days

top 10 PUs
top 10 Drops


















</div>


 ';
 
 
echo ' </div> </form>';

} // ends check for client selected or new client

 
 echo '</div><br />';

 
 if ($clientid=='') {
 echo '<script>
 $(document).ready(function() { setTimeout( function() { $("#comboboxbutton").click() }, 100 ); });			
</script>';
 }
 


echo '<script type="text/javascript">
$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+35));

		$(function() {	$("#tabs").tabs();	});
	$(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
		$( "#combobox" ).toggle();	});	});

			$(function(){ $(".normal").autosize();	});
	});
	

	function comboboxchanged() { }	
</script>';

include "footer.php";

 mysql_close(); 
echo '</body></html>';