<?php 

$alpha_time = microtime(TRUE);

error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {

 if ($serversecure=='') { 
 
 
echo 'ss : '.$serversecure;
 
 
// header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } 

 }
 
 
 }
 
$title = "COJM Settings";
?><!doctype html>
<html lang="en"><head>

<link rel="stylesheet" href="js/themes/<?php 
if (isset($_POST['clweb8'])) { echo  $_POST['clweb8']; }  else { echo $globalprefrow['clweb8']; }
?>/jquery-ui.css" type="text/css" />
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>

<title><?php print ($title); ?> </title>
	<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
	$(function(){
				  $('#rangeBa, #rangeBb').daterangepicker();  
			 });
	</script></head><body>
<?php 
$hasforms='1';
include "changejob.php";
$filename='cojmglobal.php';
$adminmenu=0; $settingsmenu=1;
include "cojmmenu.php"; 
?><div class="Post Spaceout">

<form action="#" method="post">
<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>" />
<input type="hidden" name="page" value="editglobals" />


<div id="tabs"><ul>
<li><a href="#tabs-7">Theme</a></li>
<li><a href="#tabs-4">General</a></li>
<li><a href="#tabs-1"><?php echo $globalprefrow['globalshortname']; ?> Details</a></li>
<li><a href="#tabs-6">Maps / Tracking Icons</a></li>
<li><a href="#tabs-8">Favourite Tags</a></li>
<li><a href="#tabs-2">Advanced</a></li>
<li><a href="#tabs-5">Financial</a></li>	
<li><a href="#tabs-3">System Info</a></li>
</ul>




<?php //  setup  

$sql = "SELECT * FROM globalprefs"; $sql_result = mysql_query($sql,$conn_id)  or mysql_error(); $globalprefrow=mysql_fetch_array($sql_result);

?>
<div id="tabs-2">
<fieldset><label for="txtName" class="fieldLabel"> Page Timeout </label> 
<select class="ui-state-default ui-corner-left" name="formtimeout">
<option <?php if ( $globalprefrow['formtimeout']=='125' ) { echo 'SELECTED'; } ?> value="125">2 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='300' ) { echo 'SELECTED'; } ?> value="300">5 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='600' ) { echo 'SELECTED'; } ?> value="600">10 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='900' ) { echo 'SELECTED'; } ?> value="900">15 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='1200' ) { echo 'SELECTED'; } ?> value="1200">20 mins</option>

</select>

 timeout reminder not shown on mobile devices, if another user has changed job then the page may have already timed out.

</fieldset>


<div class="line"></div>
<fieldset><label for="txtName" class="fieldLabel"> Show Page Load Times </label>
<input type="checkbox" name="glob7" value="1" <?php if ($globalprefrow['glob7']=='1') { echo 'checked';} ?>></fieldset>



<fieldset><label for="txtName" class="fieldLabel"> Show COJM Debug info </label>
<input type="checkbox" name="adminlogoback" value="1" <?php if ($globalprefrow['adminlogoback']>0) { echo 'checked';} ?>></fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Force admin https SSL </label>
<input type="checkbox" name="forcehttps" value="1" <?php if ($globalprefrow['forcehttps']>0) { echo 'checked';} ?> ></fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Show Settings on Mobile device</label>
<input type="checkbox" name="showsettingsmobile" value="1" <?php if ($globalprefrow['showsettingsmobile']>0) { echo 'checked';} ?>></fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Test Joomla Login </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="4" name="testjoomlalogin" value="<?php echo $globalprefrow['testjoomlalogin']; ?>"></fieldset>


<div class="line"> </div>

	
<fieldset><label for="txtName" class="fieldLabel"> grams CO<sub>2</sub> saved per 
<?php if ($globalprefrow['distanceunit']=='miles') { echo 'mile '; } else { echo $globalprefrow['distanceunit']; } ?> </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="co2perdist" value="<?php echo $globalprefrow['co2perdist']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> grams PM<sub>10</sub> saved per 
<?php if ($globalprefrow['distanceunit']=='miles') { echo 'mile '; } else { echo $globalprefrow['distanceunit']; } ?> </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="pm10perdist" value="<?php echo $globalprefrow['pm10perdist']; ?>"></fieldset>

<div class="line"> </div>
	
<fieldset><label for="txtName" class="fieldLabel"> minutes difference </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="waitingtimedelay" value="<?php echo $globalprefrow['waitingtimedelay']; ?>">
on-site time to en-route with delivery before prompting to add waiting time
</fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Rider Top menu selected colour </label> #<input 
class="ui-state-default ui-corner-all pad" type="text" size="8" name="courier3" value="<? echo $globalprefrow['courier3']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Rider Logo Location </label> <input 
class="ui-state-default ui-corner-all pad" type="text" size="60" name="courier4" value="<? echo $globalprefrow['courier4']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Rider Logo Style </label> <input 
class="ui-state-default ui-corner-all pad" type="text" size="60" name="courier5" value="<? echo $globalprefrow['courier5']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Rider COC or COD Style </label> <input 
class="ui-state-default ui-corner-all pad" type="text" size="60" name="courier6" value="<? echo $globalprefrow['courier6']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Alert Email Address </label> <input 
class="ui-state-default ui-corner-all pad" placeholder="me@example.com" type="email" size="60" name="glob8" value="<? echo $globalprefrow['glob8']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> googlemapapiv3key </label> <input 
class="ui-state-default ui-corner-all pad" placeholder="Get from google for your domain" size="60" name="googlemapapiv3key" value="<? echo $globalprefrow['googlemapapiv3key']; ?>"></fieldset>


</div>




















<div id="tabs-1">
	
<fieldset><label for="txtName" class="fieldLabel" style="width:250px;"> Name </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="30" name="globalname" value="<? echo $globalprefrow['globalname']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Short Name </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="globalshortname" value="<? echo $globalprefrow['globalshortname']; ?>"></fieldset>
	
<fieldset><label for="txtName" class="fieldLabel"> Address 1 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" name="myaddress1" value="<? echo $globalprefrow['myaddress1']; ?>"></fieldset>
	
<fieldset><label for="txtName" class="fieldLabel"> Address 2 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" name="myaddress2" value="<? echo $globalprefrow['myaddress2']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Address 3 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" name="myaddress3" value="<? echo $globalprefrow['myaddress3']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Address 4 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" name="myaddress4" value="<? echo $globalprefrow['myaddress4']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Address 5 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" name="myaddress5" value="<? echo $globalprefrow['myaddress5']; ?>"></fieldset>
		
</div>






<div id="tabs-3">

<fieldset><label for="txtName" class="fieldLabel"> Currency </label> &<? echo $globalprefrow['currencysymbol']; ?></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Distance </label> <?
if ($globalprefrow['distanceunit']=='miles') { echo ' miles '; } 
if ($globalprefrow['distanceunit']=='km') { echo ' km '; } ?></fieldset>


<fieldset><label for="txtName" class="fieldLabel"> root http </label> <? echo $globalprefrow['httproot']; ?></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> root https </label> <? echo $globalprefrow['httproots']; ?></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Inaccurate Postcodes </label> 
<input type="checkbox"  value="1" <?php if ($globalprefrow['inaccuratepostcode']>0) { echo 'checked';} ?>></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Backup sent from </label> <? echo $globalprefrow['backupemailfrom']; ?></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Backup sent to </label> <? echo $globalprefrow['backupemailto']; ?></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Location Quick Check </label><? echo $globalprefrow['locationquickcheck']; ?></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Location Client Invoice page </label><? echo $globalprefrow['clweb6']; ?></fieldset>




<fieldset>
<label for="txtName" class="fieldLabel"> Website usage policy location </label> 
<?php echo $globalprefrow['clweb2']; ?>
</fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Rider CSS File : </label> 
<? echo $globalprefrow['courier1']; ?></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Show Licensed Mail Options</label>
<input type="checkbox" name="showpostcomm" value="1" <?php if ($globalprefrow['showpostcomm']>0) { echo 'checked';} ?>></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> COJM JS File </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="glob9" value="<? echo $globalprefrow['glob9']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> COJM CSS File </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="glob10" value="<? echo $globalprefrow['glob10']; ?>"></fieldset>


<fieldset><label class="fieldLabel"> SERVER_PORT </label><?php echo $_SERVER['SERVER_PORT']; ?></fieldset>

<fieldset><label class="fieldLabel"> HTTPS </label><?php echo $_SERVER["HTTPS"]; ?></fieldset>




<fieldset><label class="fieldLabel"> backup ftp server </label><?php echo $globalprefrow['backupftpserver']; ?></fieldset>
<fieldset><label class="fieldLabel"> backup ftp username </label><?php echo $globalprefrow['backupftpusername']; ?></fieldset>
<fieldset><label class="fieldLabel"> backup ftp passwd </label><?php echo $globalprefrow['backupftppasswd']; ?></fieldset>




</div>





<div id="tabs-4">
<?php // general settings  ?>

<fieldset><label for="txtName" class="fieldLabel"> Number jobs displayed on homepage </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="numjobs" value="<? echo $globalprefrow['numjobs']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Number jobs displayed on mobile </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="numjobsm" value="<? echo $globalprefrow['numjobsm']; ?>"></fieldset>




<fieldset><label for="txtName" class="fieldLabel"> Index display Style </label> 
<select class="ui-state-default ui-corner-all pad" name="glob6" >
<option <?php if ( $globalprefrow['glob6']=='1' ) { echo 'SELECTED'; } ?> value="1"> Colour alternates on Individual job </option>
<option <?php if ( $globalprefrow['glob6']=='2' ) { echo 'SELECTED'; } ?> value="2"> Colour alternates on Day Difference </option>
</select>
</fieldset>









<fieldset><label for="txtName" class="fieldLabel"> Number jobs on <?php echo $globalprefrow['glob5']; ?> home </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="3" name="courier2" value="<? echo $globalprefrow['courier2']; ?>"></fieldset>

	


<fieldset><label for="glob11" class="fieldLabel"> Show Working Windows</label>
<input type="checkbox" name="glob11" value="1" <?php if ($globalprefrow['glob11']=='1') { echo 'checked';} ?>></fieldset>



	
<fieldset><label for="txtName" class="fieldLabel"> Cyclist / Rider </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="glob5" value="<?php echo $globalprefrow['glob5']; ?>"></fieldset>
	
	
	
<?php
		
$ridername = mysql_result(mysql_query(" SELECT cojmname from Cyclist WHERE `Cyclist`.`CyclistID` = '1' LIMIT 1", $conn_id), 0);
$ridernamf = mysql_result(mysql_query(" SELECT poshname from Cyclist WHERE `Cyclist`.`CyclistID` = '1' LIMIT 1", $conn_id), 0);
  
 
	
?>
	
	
	

<fieldset><label for="txtName" class="fieldLabel"> Unallocated COJM <?php echo $globalprefrow['glob5']; ?> name </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="unrider1" value="<?php echo $ridername; ?>"></fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Unallocated Public <?php echo $globalprefrow['glob5']; ?> name</label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="unrider2" value="<?php echo $ridernamf; ?>"></fieldset>

	
	
	
	

<div class="line"></div>
<fieldset><label for="txtName" class="fieldLabel"> JPG Admin Logo Relative </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="50" name="adminlogo" value="<?php echo $globalprefrow['adminlogo']; ?>">
default is  ../../images/my_logo_199x60.jpg
</fieldset>



<fieldset><label for="txtName" class="fieldLabel"> JPG Admin Logo Absolute </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="50" name="adminlogoabs" value="<?php echo $globalprefrow['adminlogoabs']; ?>">
default is  http://www.cojm.co.uk/images/my_logo_199x60.jpg
</fieldset>





<fieldset><label for="txtName" class="fieldLabel"> Admin Logo Height </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="5" name="adminlogoheight" value="<?php echo $globalprefrow['adminlogoheight']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Admin Logo Width </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="5" name="adminlogowidth" value="<?php echo $globalprefrow['adminlogowidth']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel">
PDF Invoices and other, 200px x 60px </label>

<img alt="Please check settings if you cant see 2 logos" title="Relative" src="<?php echo $globalprefrow['adminlogo']; ?>" />

<img alt="Please check settings if you cant see 2 logos" title="Absolute" src="<?php echo $globalprefrow['adminlogoabs']; ?>" />


</fieldset>
<div class="line"></div>








	

<fieldset><label for="txtName" class="fieldLabel"> Highlight Colour </label> #
<input type="text" class="ui-state-default ui-corner-all pad" size="7" name="highlightcolour" value="<?php echo $globalprefrow['highlightcolour']; ?>">
<input class="ui-corner-all" style="background-color:#<?php echo $globalprefrow['highlightcolour']; ?>" type="submit" value="Currently set to this">
<a href="http://www.w3schools.com/html/html_colors.asp" target="_blank">Colour Guide</a>, use HEX, without the hash.
</fieldset>




<fieldset><label for="txtName" class="fieldLabel"> No <?php echo $globalprefrow['glob5']; ?> or Postcode </label> 
<input type="text" class="ui-corner-all pad" style="<?php echo $globalprefrow['highlightcolourno']; ?>"size="70" name="highlightcolourno" value="<?php echo $globalprefrow['highlightcolourno']; ?>">
</fieldset>





<fieldset><label for="txtName" class="fieldLabel"> Job viewed 
<?php echo '<img class="littleset" alt="viewedicon" title="viewedicon" src="'.$globalprefrow['viewedicon'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="viewedicon" value="<?php echo $globalprefrow['viewedicon']; ?>">
</fieldset>





<fieldset><label for="txtName" class="fieldLabel"> Not yet viewed 
<?php echo '<img class="littleset" alt="unviewedicon" title="unviewedicon" src="'.$globalprefrow['unviewedicon'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="unviewedicon" value="<?php echo $globalprefrow['unviewedicon']; ?>">
</fieldset>




<fieldset><label for="txtName" class="fieldLabel"> ASAP Icon 
<?php echo '<img class="littleset" alt="asap" title="asap" src="'.$globalprefrow['image5'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="image5" value="<?php echo $globalprefrow['image5']; ?>">
</fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Cargo Icon 
<?php echo '<img class="littleset" alt="cargo" title="cargo" src="'.$globalprefrow['image6'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="image6" value="<?php echo $globalprefrow['image6']; ?>">
</fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Awaiting Scheduling Annoying Sound 
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="sound1" value="<?php echo $globalprefrow['sound1']; ?>">
<a target="_blank" href="<?php echo $globalprefrow['httproot']; ?>/sounds/" >Sound Gallery</a>
Only use the main part of the filename, ie without the .mp3 extenstion.
</fieldset>
</div>



<div id="tabs-5">
<?php // financial setup ?>



<fieldset><label for="txtName" class="fieldLabel"> VAT Band A (%) </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="vatbanda" value="<? echo $globalprefrow['vatbanda']; ?>">

The VAT charge within a job is set by which service is used.
</fieldset>

<fieldset><label for="txtName" class="fieldLabel"> VAT Band B (%) </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="vatbandb" value="<? echo $globalprefrow['vatbandb']; ?>"></fieldset>

<h3>Expense Type Names, leave blank if not required</h3>

<fieldset><label for="txtName" class="fieldLabel"> Expense Type 1  </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" name="gexpc1" value="<? echo $globalprefrow['gexpc1']; ?>">
(eg Petty Cash)
</fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Expense Type 2  </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" name="gexpc2" value="<? echo $globalprefrow['gexpc2']; ?>">
(eg Business Account)
</fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Expense Type 3 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" name="gexpc3" value="<? echo $globalprefrow['gexpc3']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Expense Type 4 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" name="gexpc4" value="<? echo $globalprefrow['gexpc4']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Expense Type 5 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" name="gexpc5" value="<? echo $globalprefrow['gexpc5']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Expense Type 6  </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" name="gexpc6" value="<? echo $globalprefrow['gexpc6']; ?>">

(only expense type which will request cheque numbers)

</fieldset>


<hr />

<fieldset><label> Text before list of rider payments </label>
<textarea class="ui-state-default ui-corner-all autosize" name="courier9"
style="width:60%;"><? echo $globalprefrow['courier9']; ?></textarea>
</fieldset>


<fieldset><label> Text after list of rider payments </label>
<textarea class="ui-state-default ui-corner-all autosize" name="courier10"
style="width:60%;"><? echo $globalprefrow['courier10']; ?></textarea>
</fieldset>

</div>




<div id="tabs-6">
<?php // tracking and maps setup ?>



<fieldset><label for="txtName" class="fieldLabel"> Awaiting Scheduling 
<?php echo '<img class="littleset" alt="image1" title="image1" src="'.$globalprefrow['image1'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="image1" value="<?php echo $globalprefrow['image1']; ?>">

 <a target="_blank" href="<?php echo $globalprefrow['httproots']; ?>/cojm/live/images/" >Image Gallery</a>

</fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Awaiting Collection 
<?php echo '<img class="littleset" alt="image2" title="image2" src="'.$globalprefrow['image2'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="image2" value="<?php echo $globalprefrow['image2']; ?>">
</fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Awaiting Delivery 
<?php echo '<img class="littleset" alt="image3" title="image3" src="'.$globalprefrow['image3'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="image3" value="<?php echo $globalprefrow['image3']; ?>">
</fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Cyclist Icon
<?php echo '<img class="littleset" alt="image4" title="image4" src="'.$globalprefrow['image4'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="image4" value="<?php echo $globalprefrow['image4']; ?>">
</fieldset>




<fieldset><label for="txtName" class="fieldLabel"> Cycling Icon for Google Earth
<?php echo '<img class="littleset" alt="imagecge" title="clweb3" src="'.$globalprefrow['clweb3'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="50" name="clweb3" value="<?php echo $globalprefrow['clweb3']; ?>">
Needs full root https:// address
</fieldset>







<div class="line"></div>

<fieldset><label for="txtName" class="fieldLabel"> Default Google Map Latitude </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="10" name="glob1" value="<?php echo $globalprefrow['glob1']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Default Google Map Longitude </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="10" name="glob2" value="<?php echo $globalprefrow['glob2']; ?>"></fieldset>



<fieldset>
<label for="txtName" class="fieldLabel"> Line style for google earth </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="50" name="clweb4" value="<?php echo $globalprefrow['clweb4']; ?>">
For use in the download kml file
</fieldset>



<fieldset>
<label for="txtName" class="fieldLabel"> Initial Google Earth View </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="50" name="clweb5" value=" <?php echo $globalprefrow['clweb5']; ?>">
For use in the download kml file
</fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Default New Postcode Town </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="15" name="glob3" value="<?php echo $globalprefrow['glob3']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel"> Default New Postcode Locality </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="15" name="glob4" value="<?php echo $globalprefrow['glob4']; ?>"></fieldset>

</div>



<div id="tabs-7">
<?php // theme settings  ?>
	



<fieldset><label for="txtName" class="fieldLabel"> COJM Theme </label> 
<select class="ui-state-default ui-corner-left" name="clweb8">
<option <?php if ( $globalprefrow['clweb8']=='base' ) { echo 'SELECTED'; } ?> value="base">base</option>
<option <?php if ( $globalprefrow['clweb8']=='blitzer' ) { echo 'SELECTED'; } ?> value="blitzer">blitzer</option>
<option <?php if ( $globalprefrow['clweb8']=='control' ) { echo 'SELECTED'; } ?> value="control">Control</option>
<option <?php if ( $globalprefrow['clweb8']=='cupertino' ) { echo 'SELECTED'; } ?> value="cupertino">cupertino</option>
<option <?php if ( $globalprefrow['clweb8']=='eggplant' ) { echo 'SELECTED'; } ?> value="eggplant">eggplant</option>
<option <?php if ( $globalprefrow['clweb8']=='hot-sneaks' ) { echo 'SELECTED'; } ?> value="hot-sneaks">hot-sneaks</option>
<option <?php if ( $globalprefrow['clweb8']=='humanity' ) { echo 'SELECTED'; } ?> value="humanity">humanity</option>
<option <?php if ( $globalprefrow['clweb8']=='overcast' ) { echo 'SELECTED'; } ?> value="overcast">overcast</option>
<option <?php if ( $globalprefrow['clweb8']=='pepper-grinder' ) { echo 'SELECTED'; } ?> value="pepper-grinder">pepper-grinder</option>
<option <?php if ( $globalprefrow['clweb8']=='redmond' ) { echo 'SELECTED'; } ?> value="redmond">redmond</option>
<option <?php if ( $globalprefrow['clweb8']=='smoothness' ) { echo 'SELECTED'; } ?> value="smoothness">smoothness</option>
<option <?php if ( $globalprefrow['clweb8']=='sunny' ) { echo 'SELECTED'; } ?> value="sunny">sunny</option>
<option <?php if ( $globalprefrow['clweb8']=='ui-lightness' ) { echo 'SELECTED'; } ?> value="ui-lightness">ui-lightness</option>
</select>


</fieldset>

<br />
<div class="ui-widget">
			<div class="ui-state-default ui-corner-all" style="padding: 0.2em;"> 
	UI State D 123456 abc 123 
<select class="ui-state-default ui-corner-left" name="notneeded">
<option value="1">Option 1</option>
<option value="2">Option 2</option>
<option value="3">Option 3</option>
</select>
</div></div>

<br />
<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0.2em;"> 
	UI State H 123456 abc 123 
<select class="ui-state-highlight ui-corner-left" name="notneeded">
<option value="1">Option 1</option>
<option value="2">Option 2</option>
<option value="3">Option 3</option>
</select>
</div></div>


<br />
<div class="ui-widget">
			<div class="ui-state-error ui-corner-all" style="padding: 0.2em;"> 
	UI State E 123456 abc 123 
<select class="ui-state-error ui-corner-left" name="notneeded">
<option value="1">Option 1</option>
<option value="2">Option 2</option>
<option value="3">Option 3</option>
</select>


<span style="<?php echo $globalprefrow['highlightcolourno']; ?>">

This is the highlight colour in error highlight ( unscheduled, not been collected  or delivery late )

</span>

</div></div>


<br />
From	<input class="ui-state-default ui-corner-all pad" size="10" type="text" name="from" value="" id="rangeBa" />			
To		<input class="ui-state-default ui-corner-all pad"  size="10" type="text" name="to" value="" id="rangeBb" />			

</div>


<div id="tabs-8">


<fieldset><label for="txtName" class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn1" value="<?php echo $globalprefrow['favusrn1']; ?>" /> 

</label>
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn2" value="<?php echo $globalprefrow['favusrn2']; ?>" /> </fieldset>

<fieldset><label for="txtName" class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn3" value="<?php echo $globalprefrow['favusrn3']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn4" value="<?php echo $globalprefrow['favusrn4']; ?>" /> </fieldset>
<fieldset><label for="txtName" class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn5" value="<?php echo $globalprefrow['favusrn5']; ?>" /> 

</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn6" value="<?php echo $globalprefrow['favusrn6']; ?>" /> </fieldset>
<fieldset><label for="txtName" class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn7" value="<?php echo $globalprefrow['favusrn7']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn8" value="<?php echo $globalprefrow['favusrn8']; ?>" /> </fieldset>
<fieldset><label for="txtName" class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn9" value="<?php echo $globalprefrow['favusrn9']; ?>" /> 
 </label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn10" value="<?php echo $globalprefrow['favusrn10']; ?>" /> </fieldset>
<fieldset><label for="txtName" class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn11" value="<?php echo $globalprefrow['favusrn11']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn12" value="<?php echo $globalprefrow['favusrn12']; ?>" /> </fieldset>
<fieldset><label for="txtName" class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn13" value="<?php echo $globalprefrow['favusrn13']; ?>" /> 
 </label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn14" value="<?php echo $globalprefrow['favusrn14']; ?>" /> </fieldset>
<fieldset><label for="txtName" class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn15" value="<?php echo $globalprefrow['favusrn15']; ?>" />
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn16" value="<?php echo $globalprefrow['favusrn16']; ?>" /> </fieldset>
<fieldset><label for="txtName" class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn17" value="<?php echo $globalprefrow['favusrn17']; ?>" /> 
 </label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn18" value="<?php echo $globalprefrow['favusrn18']; ?>" /> </fieldset>
<fieldset><label for="txtName" class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn19" value="<?php echo $globalprefrow['favusrn19']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" name="favusrn20" value="<?php echo $globalprefrow['favusrn20']; ?>" /> </fieldset>




</div>







<div class="line"></div>
<button type="submit" > Save Settings </button>
</form>
<div class="line"></div>

</div>

</div>

<?php // ends tab div


echo '<script type="text/javascript">
$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+15));

	
			$(function(){ $(".autosize").autosize();	});
	
	});
</script>';




include 'footer.php';
  




echo '</body></html>';