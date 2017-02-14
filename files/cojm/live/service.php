<?php 

$alpha_time = microtime(TRUE); 

error_reporting( E_ERROR | E_WARNING | E_PARSE );
$title = "COJM";

if (isset($_POST['serviceid'])) { $thisserviceid=$_POST['serviceid']; } else { $thisserviceid=''; }
include "C4uconnect.php";

?><!doctype html>
<html lang="en"><head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title); ?> New / Edit Service</title>
</head><body>
<?php 

include "changejob.php";

$filename="service.php";
$settingsmenu='1';
$invoicemenu='0';
$adminmenu='0';
include "cojmmenu.php"; 


?><div class="Post">
	<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto; ">

<form action="#" method="post" >
<input type="hidden" name="page" value="selectservice">

<select class="ui-state-highlight ui-corner-left" name="serviceid"><?php 
$query = "SELECT ServiceID, Service FROM Services 
ORDER BY activeservice DESC, serviceorder DESC, ServiceID ASC";
$result_id = mysql_query ($query, $conn_id); while (list ($ServiceID, $Service) = mysql_fetch_row ($result_id)) { 
$ServiceID = htmlspecialchars ($ServiceID); $Service = htmlspecialchars ($Service); print"<option "; 
if ($ServiceID == $thisserviceid) {echo "SELECTED "; } ; print ("value=\"$ServiceID\">$Service</option>\n");} ?></select>

<button type="submit"> Select Service </button>
</form>
<div class="vpad"> </div>
<form action="#" method="post" >
<input type="hidden" name="page" value="createnew">
<button type="submit">Create New Service</button>
</form>

</div></div>
 
<div class="vpad "></div>

<?php if ($page) {
?><div class="vpad"> </div>	<div class="ui-widget">	<div class="ui-state-default ui-corner-all" style="padding: 0.5em; width:auto; "><p>

<form action="#" method="post">
<input type="hidden" name="page" value="editthisservice" />
<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>">
<?php
if ($page=='createnew') {
$thisserviceid='';
}

$sql = "SELECT * FROM Services WHERE ServiceID = '$thisserviceid' LIMIT 1"; $sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$row=mysql_fetch_array($sql_result);


// batchdropcount now used to not display service in main index screen

// slatime

// sldtime


?>
<input type="hidden" name="serviceid" value="<?php echo $thisserviceid; ?>">
<div style="position:relative; float:left; padding-left:50px; line-height:21px;">
<fieldset><label for="name" class="fieldLabel">  Name </label>
<input type="text" size="28" class="caps ui-state-default ui-corner-all" name="Service" value="<? echo $row['Service']; ?>"> 
</fieldset>
<div class="vpad"> </div>

<fieldset><label for="name" class="fieldLabel"> Order </label>
<input type="text" size="4" name="serviceorder" class="caps ui-state-default ui-corner-all" value="<? echo $row['serviceorder']; ?>">
Which order displayed in service dropdowns
</fieldset>
<div class="vpad"> </div>

<fieldset><label for="name" class="fieldLabel"> Price Ex VAT </label>
 &<?php echo $globalprefrow["currencysymbol"];?> <input type="text"  size="6" class="caps ui-state-default ui-corner-all" 
 name="Price" value="<?php 

// echo number_format($row['Price'], 4, '.', '');

echo(float)$row['Price'];

 ?>">

 
<select class="ui-state-default ui-corner-left" name="vatband"> 
<option value="0" <?php if ($row['vatband']=='0') { echo 'selected'; } ?>> Zero Rated </option>
<option value="a" <?php if ($row['vatband']=='a') { echo 'selected'; } ?> > Band A ( %<?php  echo $globalprefrow['vatbanda']; ?> ) </option>
<option value="b" <?php if ($row['vatband']=='b') { echo 'selected'; } ?> > Band B ( %<?php  echo $globalprefrow['vatbandb'].' ) </option>
</select>
</fieldset>
<div class="vpad"> </div>
<fieldset><label for="name" class="fieldLabel">  Collection SLA </label> ';

$thisslatime=$row['slatime'];

echo '<select class="ui-state-default ui-corner-left" name="slatime">';
echo '<option value="now">Now </option>';
echo '<option ';  if ($thisslatime=='00:15:00') { echo 'SELECTED'; } echo ' value="00:15:00">15 mins</option>';
echo '<option ';  if ($thisslatime=='00:30:00') { echo 'SELECTED'; } echo ' value="00:30:00">30 mins</option>';
echo '<option ';  if ($thisslatime=='00:45:00') { echo 'SELECTED'; } echo ' value="00:45:00">45 mins</option>';
echo '<option ';  if ($thisslatime=='01:00:00') { echo 'SELECTED'; } echo ' value="01:00:00">1 hour</option>';
echo '<option ';  if ($thisslatime=='01:30:00') { echo 'SELECTED'; } echo ' value="01:30:00">1 &amp;1/2 hours</option>';
echo '<option ';  if ($thisslatime=='02:00:00') { echo 'SELECTED'; } echo ' value="02:00:00">2 hours</option>';
echo '<option ';  if ($thisslatime=='03:00:00') { echo 'SELECTED'; } echo ' value="03:00:00">3 hours</option>';
echo '<option ';  if ($thisslatime=='00:00:08') { echo 'SELECTED'; } echo ' value="00:00:08">Next 8AM  </option>';
echo '<option ';  if ($thisslatime=='00:00:09') { echo 'SELECTED'; } echo ' value="00:00:09">Next 9AM  </option>';
echo '<option ';  if ($thisslatime=='00:00:10') { echo 'SELECTED'; } echo ' value="00:00:10">Next 10AM  </option>';
echo '<option ';  if ($thisslatime=='00:00:11') { echo 'SELECTED'; } echo ' value="00:00:11">Next 11AM </option>';
echo '<option ';  if ($thisslatime=='00:00:12') { echo 'SELECTED'; } echo ' value="00:00:12">Next 12PM  </option>';
echo '<option ';  if ($thisslatime=='00:00:13') { echo 'SELECTED'; } echo ' value="00:00:13">Next 1PM  </option>';
echo '<option ';  if ($thisslatime=='00:00:14') { echo 'SELECTED'; } echo ' value="00:00:14">Next 2PM  </option>';
echo '<option ';  if ($thisslatime=='00:00:15') { echo 'SELECTED'; } echo ' value="00:00:15">Next 3PM </option>';
echo '<option ';  if ($thisslatime=='00:00:16') { echo 'SELECTED'; } echo ' value="00:00:16">Next 4PM </option>';
echo '<option ';  if ($thisslatime=='00:00:17') { echo 'SELECTED'; } echo ' value="00:00:17">Next 5PM </option>';
echo '<option ';  if ($thisslatime=='00:00:18') { echo 'SELECTED'; } echo ' value="00:00:18">Next 6PM </option>';
echo '</select>';





// echo '<input type="text" class="caps ui-state-default ui-corner-all" name="slatime" size="8" value="'. $row['slatime'].'"> HH:mm:ss From job creation to target PU';

echo '</fieldset>


<div class="vpad"> </div>

<fieldset><label for="name" class="fieldLabel"> Delivery SLA </label>';

$thissldtime=$row['sldtime'];

echo '<select class="ui-state-default ui-corner-left" name="sldtime">';
echo '<option value="now">Now </option>';
echo '<option ';  if ($thissldtime=='00:15:00') { echo 'SELECTED'; } echo ' value="00:15:00">15 mins</option>';
echo '<option ';  if ($thissldtime=='00:30:00') { echo 'SELECTED'; } echo ' value="00:30:00">30 mins</option>';
echo '<option ';  if ($thissldtime=='00:45:00') { echo 'SELECTED'; } echo ' value="00:45:00">45 mins</option>';
echo '<option ';  if ($thissldtime=='01:00:00') { echo 'SELECTED'; } echo ' value="01:00:00">1 hour</option>';
echo '<option ';  if ($thissldtime=='01:30:00') { echo 'SELECTED'; } echo ' value="01:30:00">1 &amp;1/2 hours</option>';
echo '<option ';  if ($thissldtime=='02:00:00') { echo 'SELECTED'; } echo ' value="02:00:00">2 hours</option>';
echo '<option ';  if ($thissldtime=='03:00:00') { echo 'SELECTED'; } echo ' value="03:00:00">3 hours</option>';
echo '<option ';  if ($thissldtime=='00:00:08') { echo 'SELECTED'; } echo ' value="00:00:08">Next 8AM  </option>';
echo '<option ';  if ($thissldtime=='00:00:09') { echo 'SELECTED'; } echo ' value="00:00:09">Next 9AM  </option>';
echo '<option ';  if ($thissldtime=='00:00:10') { echo 'SELECTED'; } echo ' value="00:00:10">Next 10AM  </option>';
echo '<option ';  if ($thissldtime=='00:00:11') { echo 'SELECTED'; } echo ' value="00:00:11">Next 11AM </option>';
echo '<option ';  if ($thissldtime=='00:00:12') { echo 'SELECTED'; } echo ' value="00:00:12">Next 12PM  </option>';
echo '<option ';  if ($thissldtime=='00:00:13') { echo 'SELECTED'; } echo ' value="00:00:13">Next 1PM  </option>';
echo '<option ';  if ($thissldtime=='00:00:14') { echo 'SELECTED'; } echo ' value="00:00:14">Next 2PM  </option>';
echo '<option ';  if ($thissldtime=='00:00:15') { echo 'SELECTED'; } echo ' value="00:00:15">Next 3PM </option>';
echo '<option ';  if ($thissldtime=='00:00:16') { echo 'SELECTED'; } echo ' value="00:00:16">Next 4PM </option>';
echo '<option ';  if ($thissldtime=='00:00:17') { echo 'SELECTED'; } echo ' value="00:00:17">Next 5PM </option>';
echo '<option ';  if ($thissldtime=='00:00:18') { echo 'SELECTED'; } echo ' value="00:00:18">Next 6PM </option>';
echo '</select>';



?>

</fieldset>
<div class="vpad"> </div>

<fieldset><label for="name" class="fieldLabel"> Emission Savings </label>
 CO<sub>2</sub> <input type="text" size="7" name="CO2Saved" class="caps ui-state-default ui-corner-all" value="<?php echo $row['CO2Saved']; 
 ?>">g | PM<sub>10</sub> Saved : <input type="text" size="5" class="caps ui-state-default ui-corner-all" name="PM10Saved" value="<? echo $row['PM10Saved']; ?>"> g 
 Will be used if there is no distance
  </fieldset>
 
 <div class="vpad"> </div>
 
 

<fieldset><label for="name" class="fieldLabel"> Service Comments </label>
<TEXTAREA name="servicecomments" class="ui-state-default ui-corner-all " rows="3" cols="50" ><?php print $row['servicecomments']; ?></TEXTAREA>
</fieldset>

</div>


<div style="position:relative; float:left; padding-left:50px;">
 
 <table style="">
 <tbody>
<tr><td>  Active Service </td> <td>
<input type="checkbox" name="activeservice" value="1" <?php if ($row['activeservice']>0) { echo 'checked';} ?> > 
</td><td> </td></tr> 
 
<tr><td> <?php if ($globalprefrow['showpostcomm']>'0') { echo 'Unlicensed Delivery'; } else { echo 'Is a Delivery'; }  ?>  
</td><td>
<input type="checkbox" name="UnlicensedCount" value="1" <?php if ($row['UnlicensedCount']>0) { echo 'checked';} ?> >
</td>
<td> </td>
</tr>


<tr><td>Distance Price</td><td> 
<input type="checkbox" name="chargedbybuild" value="1" <?php if ($row['chargedbybuild']>0) { echo 'checked';} ?> > 
</td><td> </td></tr>

<tr><td>Built by check</td><td> 
<input type="checkbox" name="chargedbycheck" value="1" <?php if ($row['chargedbycheck']>0) { echo 'checked';} ?> > 
</td><td> </td></tr>


<tr><td> Hourly Rate </td><td>
<input type="checkbox" name="hourlyothercount" value="1" <?php if ($row['hourlyothercount']>0) { echo 'checked';} ?> >
</td><td> </td> </tr>


<tr><td> Show OpsMap Areas </td><td>
<input type="checkbox" name="canhavemap" value="1" <?php if ($row['canhavemap']>0) { echo 'checked';} ?> >
</td><td> </td> </tr>


<tr><td> ASAP Service </td><td>
<input type="checkbox" name="asapservice" value="1" <?php if ($row['asapservice']>0) { echo 'checked';} ?> > 
</td><td>Icon shown in job queue
</td></tr>


<tr><td> Cargo Bike 
</td><td><input type="checkbox" name="cargoservice" value="1" <?php if ($row['cargoservice']>0) { echo 'checked';} ?> > 
</td><td>Icon shown in job queue  
</td></tr>

<tr><td> Recurring job
</td><td>
<input type="checkbox" name="isregular" value="1" <?php if ($row['isregular']>0) { echo 'checked';} ?> > 
</td><td></td></tr>

 <?php if ($globalprefrow['showpostcomm']>0) { ?> 

<tr><td> Ofcom  </td><td>
<input type="checkbox" name="LicensedCount" value="1" <?php if ($row['LicensedCount']>0) { echo 'checked';} ?> > 
</td><td>Licensed mail items
</td></tr>

<tr><td>Sub to Royal Mail
</td><td>
 <input type="checkbox" name="RMcount" value="1" <?php if ($row['RMcount']>0) { echo 'checked';} ?> > 
</td> <td></td></tr>

<?php } ?>

<tr><td> Do NOT show service number &amp; <br /> name in main job screen 
</td><td>
 <input type="checkbox" name="batchdropcount" value="1" <?php if ($row['batchdropcount']>0) { echo 'checked';} ?> > 
</td> <td></td></tr>

</tbody>
</table>

</div>


<div class=" vpad" style="clear:both;"> </div>

<button type="submit" style=" margin-left:50px;" > Edit Service</button> 
</form></div></div>
<div class=" vpad"></div>
<?php 

}









echo '<div class=" vpad"></div><table class="acc" ><tbody>';

$rpttext='<tr>
<th scope="col">Service</th>
<th scope="col">Order</th>

<th scope="col"> Price</th>
<th scope="col"> VAT</th>
<th scope="col">Active </th>
<th scope="col">'; 
if ($globalprefrow['showpostcomm']>'0') { $rpttext=$rpttext. 'Unlicensed '; } else { $rpttext=$rpttext. 'Is Delivery'; }
$rpttext=$rpttext.'</th>';

// if (($globalprefrow['inaccuratepostcode'])==0) { 
$rpttext=$rpttext.'<th scope="col"><a href="corepricing.php">Distance </a></th>';
$rpttext=$rpttext.'<th scope="col"> Checkboxes shown </th>';

$rpttext=$rpttext.'<th scope="col"> Area Map </th>';

// }
$rpttext=$rpttext.'<th scope="col">Hourly </th>

<th scope="col">ASAP </th>
<th scope="col">Cargo </th>
<th scope="col"> Recurring</th>';

if ($globalprefrow['showpostcomm']>'0') { 
$rpttext=$rpttext.'<th scope="col">Ofcom</th>
<th scope="col">RM</th>'; }


// <th scope="col">Collection SLA</th>
// <th scope="col">Delivery SLA</th>
$rpttext=$rpttext.'
<th scope="col"> CO<sub>2</sub></th>
<th scope="col"> PM<sub>10</sub></th>
<th scope="col">Comments</th>
';


$rpttext=$rpttext.'</tr>';


// WHERE `Services`.`activeservice` = '1' 

$i='0';
$query = "SELECT * FROM Services ORDER BY activeservice DESC, serviceorder DESC, ServiceID ASC"; 

$sql_result = mysql_query($query,$conn_id)  or mysql_error(); 
while ($row = mysql_fetch_array($sql_result)) { extract($row);

$i=$i+'1';

if (($i=='1') or ($i=='11') or ($i=='21')or ($i=='31') or ($i=='41') or ($i=='51') or ($i=='61') or ($i=='71') or ($i=='81')) { echo $rpttext; }

echo '<tr';
 if ($ServiceID == $thisserviceid) { echo ' style="background-color:#'.$globalprefrow['highlightcolour'].'; " '; }

echo '>

<td> 

<form action="#" method="post" >
<input type="hidden" name="page" value="selectservice" />
<input type="hidden" name="serviceid" value="'.$ServiceID.'" />
<button style="width:100%;" type="submit">'.$Service.'</button>
</form>
</td>
<td>'.$serviceorder.'</td>

<td>'.'&'. $globalprefrow["currencysymbol"].$Price.'</td>
<td>'; 
if ($vatband=='0') { echo 'Zero'; } 
if ($vatband=='a') { echo  $globalprefrow['vatbanda'].'%'; }
if ($vatband=='b') { echo  $globalprefrow['vatbandb'].'%'; }

echo'</td>
<td>';

if ($activeservice=='1') { echo '<img class="px16" alt="Yes" src="images/icon_accept.gif">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }


echo '</td><td>';
if ($UnlicensedCount=='1') { echo '<img class="px16" alt="Yes" src="images/icon_accept.gif">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }

// if (($globalprefrow['inaccuratepostcode'])==0) { 
echo '</td><td>';
if ($chargedbybuild=='1') { echo '<img class="px16" alt="Yes" src="images/icon_accept.gif">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }
// }


echo '</td><td>';
if ($chargedbycheck=='1') { echo '<img class="px16" alt="Yes" src="images/icon_accept.gif">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }


echo '</td><td>';
if ($row['canhavemap']=='1') { echo '<img class="px16" alt="Yes" src="images/icon_accept.gif">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }




echo '</td><td>';
if ($hourlyothercount=='1') { echo '<img class="px16" alt="Yes" src="images/icon_accept.gif">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }


echo '</td><td>';
if ($asapservice=='1') { echo '<img class="px16" alt="Yes" src="'.$globalprefrow['image5'].'">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }

echo '</td><td>';
if ($cargoservice=='1') { echo '<img class="px16" alt="ASAP" src="'.$globalprefrow['image6'].'">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }


echo '</td><td>';
if ($isregular=='1') { echo '<img class="px16" alt="Cargo" src="images/icon_accept.gif">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }

if ($globalprefrow['showpostcomm']>'0') {
echo '</td><td>';
if ($LicensedCount=='1') { echo '<img class="px16" alt="Yes" src="images/icon_accept.gif">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }


echo '</td><td>';

if ($RMcount=='1') { echo '<img class="px16" alt="Yes" src="images/icon_accept.gif">'; }
else { echo '<img class="px16" alt="No" src="images/action_stop.gif">'; }

}

echo '</td>';
// <td>'.$slatime.'</td>
// <td>'.$sldtime.'</td>
echo '<td>'.$CO2Saved.' </td>
<td>'.$PM10Saved.' </td>
<td>'.$servicecomments.' </td> </tr> ';



} 

echo '</table><br /><div class="line"> </div><br /></div>
<script type="text/javascript">
$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+15));
});
</script>';

include "footer.php";

echo '</body></html>';

mysql_close(); 