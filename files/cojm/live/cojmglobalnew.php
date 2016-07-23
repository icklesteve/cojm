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

<link rel="stylesheet" href="js/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" />
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>

<title><?php print ($title); ?> </title>
</head><body>
<?php 
$hasforms='0';
 include "changejob.php"; // just to get rider login id displayed !!!
$filename='cojmglobal.php';
$adminmenu=0; $settingsmenu=1;
include "cojmmenu.php"; 

$sql = "SELECT * FROM globalprefs"; 
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$globalprefrow=mysql_fetch_array($sql_result);


?>



<div class="Post Spaceout">

<p>You will need to do a full page refresh after some settings to check images / styles are displaying ok ( on the to-do list for version 2.1 ) </p>

<div id="tabs"><ul>
<li><a href="#tabs-4">General</a></li>
<li><a href="#tabs-7">Theme</a></li>
<li><a href="#tabs-1"><?php echo $globalprefrow['globalshortname']; /* companyname */ ?> Details</a></li>
<li><a href="#tabs-6">Maps / Tracking Icons</a></li>
<li><a href="#tabs-8">Favourite Tags</a></li>
<li><a href="#tabs-5">Financial</a></li>	
<li><a href="#tabs-3">System Info</a></li>
<li><a href="#tabs-2">Advanced</a></li>
</ul>


<div id="tabs-4"> <!-- general settings -->
<?php // general settings  ?>

<fieldset><label class="fieldLabel"> Number jobs displayed on homepage </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="numjobs" value="<? echo $globalprefrow['numjobs']; ?>">
</fieldset>

<fieldset><label class="fieldLabel"> Number jobs displayed on mobile </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="numjobsm" value="<? echo $globalprefrow['numjobsm']; ?>">
</fieldset>




<fieldset><label class="fieldLabel"> Index display Style </label> 
<select class="ui-state-default ui-corner-all pad" id="glob6" >
<option <?php if ( $globalprefrow['glob6']=='1' ) { echo 'SELECTED'; } ?> value="1"> Colour alternates on Individual job </option>
<option <?php if ( $globalprefrow['glob6']=='2' ) { echo 'SELECTED'; } ?> value="2"> Colour alternates on Day Difference </option>
</select>
</fieldset>









<fieldset><label class="fieldLabel"> Number jobs on <?php echo $globalprefrow['glob5']; ?> home </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="3" id="courier2" value="<? echo $globalprefrow['courier2']; ?>"></fieldset>

	


<fieldset><label for="glob11" class="fieldLabel"> Show Working Windows</label>
<input type="checkbox" id="glob11" value="1" <?php if ($globalprefrow['glob11']=='1') { echo 'checked';} ?>></fieldset>



	
<fieldset><label class="fieldLabel"> Cyclist / Rider / Driver / Messenger </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="15" id="glob5" value="<?php echo $globalprefrow['glob5']; ?>"></fieldset>
	
	
	
<?php
		
$ridername = mysql_result(mysql_query(" SELECT cojmname from Cyclist WHERE `Cyclist`.`CyclistID` = '1' LIMIT 1", $conn_id), 0);
$ridernamf = mysql_result(mysql_query(" SELECT poshname from Cyclist WHERE `Cyclist`.`CyclistID` = '1' LIMIT 1", $conn_id), 0);
  
 
	
?>
	
	
	

<fieldset><label class="fieldLabel"> Unallocated COJM <?php echo $globalprefrow['glob5']; ?> name </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="unrider1" value="<?php echo $ridername; ?>"></fieldset>


<fieldset><label class="fieldLabel"> Unallocated Public <?php echo $globalprefrow['glob5']; ?> name</label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="unrider2" value="<?php echo $ridernamf; ?>"></fieldset>

	
	
	
	

<div class="line"></div>
<fieldset><label class="fieldLabel"> JPG Admin Logo Relative </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="70" id="adminlogo" value="<?php echo $globalprefrow['adminlogo']; ?>">
<br /> default is  ../../images/my_logo_199x60.jpg

For PDF Invoices and other, aim for about 200px x 60px
</fieldset>



<fieldset><label class="fieldLabel"> JPG Admin Logo Absolute </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="70" id="adminlogoabs" value="<?php echo $globalprefrow['adminlogoabs']; ?>">
<br /> default is  http://www.cojm.co.uk/images/my_logo_199x60.jpg
For PDF Invoices and other, aim for about 200px x 60px

</fieldset>


<fieldset><label class="fieldLabel"> Admin Logo Width </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="5" id="adminlogowidth" value="<?php echo $globalprefrow['adminlogowidth']; ?>"></fieldset>



<fieldset><label class="fieldLabel"> Admin Logo Height </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="5" id="adminlogoheight" value="<?php echo $globalprefrow['adminlogoheight']; ?>">

</fieldset>


<fieldset><label class="fieldLabel">
Admin Logo Preview </label>

<img alt="Please check settings if you cant see 2 logos" title="Relative" src="<?php echo $globalprefrow['adminlogo']; ?>" />

<img alt="Please check settings if you cant see 2 logos" title="Absolute" src="<?php echo $globalprefrow['adminlogoabs']; ?>" />


</fieldset>
<div class="line"></div>








	

<fieldset><label class="fieldLabel"> Highlight Colour </label> #
<input type="text" class="ui-state-default ui-corner-all pad" size="7" id="highlightcolour" value="<?php echo $globalprefrow['highlightcolour']; ?>">
<span style="padding:5px; background-color:#<?php echo $globalprefrow['highlightcolour']; ?>"> Currently set to this </span>
<a class="newwin" href="http://www.w3schools.com/html/html_colors.asp" target="_blank">Colour Guide</a>, use HEX, without the hash.
</fieldset>




<fieldset><label class="fieldLabel"> No <?php echo $globalprefrow['glob5']; /* Rider  */ ?> or Postcode </label> 
<input type="text" class="ui-corner-all pad" style="<?php echo $globalprefrow['highlightcolourno']; ?>" size="70" id="highlightcolourno" value="<?php echo $globalprefrow['highlightcolourno']; ?>">
</fieldset>





<fieldset><label class="fieldLabel"> Job viewed 
<?php echo '<img class="littleset" alt="viewedicon" title="viewedicon" src="'.$globalprefrow['viewedicon'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="viewedicon" value="<?php echo $globalprefrow['viewedicon']; ?>">
</fieldset>





<fieldset><label class="fieldLabel"> Not yet viewed 
<?php echo '<img class="littleset" alt="unviewedicon" title="unviewedicon" src="'.$globalprefrow['unviewedicon'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="unviewedicon" value="<?php echo $globalprefrow['unviewedicon']; ?>">
</fieldset>




<fieldset><label class="fieldLabel"> ASAP Icon 
<?php echo '<img class="littleset" alt="asap" title="asap" src="'.$globalprefrow['image5'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image5" value="<?php echo $globalprefrow['image5']; ?>">
</fieldset>


<fieldset><label class="fieldLabel"> Cargo Icon 
<?php echo '<img class="littleset" alt="cargo" title="cargo" src="'.$globalprefrow['image6'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image6" value="<?php echo $globalprefrow['image6']; ?>">
</fieldset>


<fieldset class="hideuntilneeded" ><label class="fieldLabel"> Awaiting Scheduling Annoying Sound 
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="sound1" value="<?php echo $globalprefrow['sound1']; ?>">
<a target="_blank" href="<?php echo $globalprefrow['httproot']; ?>/sounds/" >Sound Gallery</a>
Only use the main part of the filename, ie without the .mp3 extenstion.
</fieldset>
</div>


<div id="tabs-7"> <!-- theme -->
<?php // theme settings  ?>

<fieldset><label class="fieldLabel"> COJM Theme </label> 
<select class="ui-state-default ui-corner-left" id="clweb8" >
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

<p> Current Theme Test Area : </p>


<div class="ui-widget">
			<div class="ui-state-default ui-corner-all" style="padding: 0.2em;"> 
	UI State D 123456 abc 123 
<select class="ui-state-default ui-corner-left" name="notneeded1">
<option value="1">Option 1</option>
<option value="2">Option 2</option>
<option value="3">Option 3</option>
</select>
</div></div>

<br />
<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 0.2em;"> 
	UI State H 123456 abc 123 
<select class="ui-state-highlight ui-corner-left" name="notneeded2">
<option value="1">Option 1</option>
<option value="2">Option 2</option>
<option value="3">Option 3</option>
</select>
</div></div>


<br />
<div class="ui-widget">
			<div class="ui-state-error ui-corner-all" style="padding: 0.2em;"> 
	UI State E 123456 abc 123 
<select class="ui-state-error ui-corner-left" name="notneeded3">
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


<div id="tabs-1">   <!-- business details -->
	
<fieldset><label class="fieldLabel" style="width:250px;"> Name </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="30" id="globalname" value="<? echo $globalprefrow['globalname']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Short Name </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="globalshortname" value="<? echo $globalprefrow['globalshortname']; ?>"></fieldset>
	
<fieldset><label class="fieldLabel"> Address 1 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" id="myaddress1" value="<? echo $globalprefrow['myaddress1']; ?>"></fieldset>
	
<fieldset><label class="fieldLabel"> Address 2 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" id="myaddress2" value="<? echo $globalprefrow['myaddress2']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Address 3 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" id="myaddress3" value="<? echo $globalprefrow['myaddress3']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Address 4 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" id="myaddress4" value="<? echo $globalprefrow['myaddress4']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Address 5 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="50" id="myaddress5" value="<? echo $globalprefrow['myaddress5']; ?>"></fieldset>
		
</div>

<div id="tabs-6">  <!-- maps / tracking icons -->
<?php // tracking and maps setup ?>



<fieldset><label class="fieldLabel"> Awaiting Scheduling 
<?php echo '<img class="littleset" alt="image1" title="image1" src="'.$globalprefrow['image1'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image1" value="<?php echo $globalprefrow['image1']; ?>">

See the /cojm/live/images/ directory for all images

</fieldset>


<fieldset><label class="fieldLabel"> Awaiting Collection 
<?php echo '<img class="littleset" alt="image2" title="image2" src="'.$globalprefrow['image2'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image2" value="<?php echo $globalprefrow['image2']; ?>">
</fieldset>

<fieldset><label class="fieldLabel"> Awaiting Delivery 
<?php echo '<img class="littleset" alt="image3" title="image3" src="'.$globalprefrow['image3'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image3" value="<?php echo $globalprefrow['image3']; ?>">
</fieldset>


<fieldset><label class="fieldLabel"> Cyclist Icon
<?php echo '<img class="littleset" alt="image4" title="image4" src="'.$globalprefrow['image4'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="30" id="image4" value="<?php echo $globalprefrow['image4']; ?>">
</fieldset>




<fieldset><label class="fieldLabel"> Dot for Google Earth
<?php echo '<img class="littleset" alt="imagecge" title="clweb3" src="'.$globalprefrow['clweb3'].'">'; ?>
</label>
<input type="text" class="ui-state-default ui-corner-all pad" size="50" id="clweb3" value="<?php echo $globalprefrow['clweb3']; ?>">
Needs full root https:// address
</fieldset>







<div class="line"></div>

<fieldset><label class="fieldLabel"> Default Google Map Latitude </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="10" id="glob1" value="<?php echo $globalprefrow['glob1']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Default Google Map Longitude </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="10" id="glob2" value="<?php echo $globalprefrow['glob2']; ?>"></fieldset>



<fieldset>
<label class="fieldLabel"> Line style for Google Earth </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="50" id="clweb4" value="<?php echo $globalprefrow['clweb4']; ?>">
For use in the download kml / kmz files.
</fieldset>



<fieldset>
<label class="fieldLabel"> Initial Google Earth View </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="50" id="clweb5" value=" <?php echo $globalprefrow['clweb5']; ?>">
For use in the download kml file
</fieldset>

<fieldset class="hideuntilneeded" ><label class="fieldLabel"> Default New Postcode Town </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="15" id="glob3" value="<?php echo $globalprefrow['glob3']; ?>"></fieldset>

<fieldset class="hideuntilneeded" ><label class="fieldLabel"> Default New Postcode Locality </label>
<input type="text" class="ui-state-default ui-corner-all pad" size="15" id="glob4" value="<?php echo $globalprefrow['glob4']; ?>"></fieldset>

</div>

<div id="tabs-8">  <!-- favourite tags -->

<p>
To be integrated into favourites menu in future release

</p>

<fieldset><label class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn1" value="<?php echo $globalprefrow['favusrn1']; ?>" /> 

</label>
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn2" value="<?php echo $globalprefrow['favusrn2']; ?>" /> </fieldset>

<fieldset><label class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn3" value="<?php echo $globalprefrow['favusrn3']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn4" value="<?php echo $globalprefrow['favusrn4']; ?>" /> </fieldset>
<fieldset><label class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn5" value="<?php echo $globalprefrow['favusrn5']; ?>" /> 

</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn6" value="<?php echo $globalprefrow['favusrn6']; ?>" /> </fieldset>
<fieldset>

<label class="fieldLabel">  
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn7" value="<?php echo $globalprefrow['favusrn7']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn8" value="<?php echo $globalprefrow['favusrn8']; ?>" /> </fieldset>
<fieldset>

<label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn9" value="<?php echo $globalprefrow['favusrn9']; ?>" /> 
 </label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn10" value="<?php echo $globalprefrow['favusrn10']; ?>" /> </fieldset>
<fieldset>

<label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn11" value="<?php echo $globalprefrow['favusrn11']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn12" value="<?php echo $globalprefrow['favusrn12']; ?>" /> </fieldset>
<fieldset><label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn13" value="<?php echo $globalprefrow['favusrn13']; ?>" /> 
 </label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn14" value="<?php echo $globalprefrow['favusrn14']; ?>" /> </fieldset>
<fieldset><label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn15" value="<?php echo $globalprefrow['favusrn15']; ?>" />
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn16" value="<?php echo $globalprefrow['favusrn16']; ?>" /> </fieldset>
<fieldset><label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn17" value="<?php echo $globalprefrow['favusrn17']; ?>" /> 
 </label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn18" value="<?php echo $globalprefrow['favusrn18']; ?>" /> </fieldset>
<fieldset><label class="fieldLabel"> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn19" value="<?php echo $globalprefrow['favusrn19']; ?>" /> 
</label> 
<input class="ui-state-default ui-corner-all pad" size="20" type="text" id="favusrn20" value="<?php echo $globalprefrow['favusrn20']; ?>" /> </fieldset>




</div>

<div id="tabs-5">   <!-- Financial Settings -->
<?php // financial setup ?>



<fieldset><label class="fieldLabel"> VAT Band A (%) </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="vatbanda" value="<? echo $globalprefrow['vatbanda']; ?>">

The VAT charge within a job is set by which service is used.
</fieldset>

<fieldset><label class="fieldLabel"> VAT Band B (%) </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="vatbandb" value="<? echo $globalprefrow['vatbandb']; ?>"></fieldset>

<h3>Expense Type Names, leave blank if not required</h3>

<fieldset><label class="fieldLabel"> Expense Type 1  </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc1" value="<? echo $globalprefrow['gexpc1']; ?>">
(eg Petty Cash)
</fieldset>

<fieldset><label class="fieldLabel"> Expense Type 2  </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc2" value="<? echo $globalprefrow['gexpc2']; ?>">
(eg Business Account)
</fieldset>

<fieldset><label class="fieldLabel"> Expense Type 3 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc3" value="<? echo $globalprefrow['gexpc3']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Expense Type 4 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc4" value="<? echo $globalprefrow['gexpc4']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Expense Type 5 </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc5" value="<? echo $globalprefrow['gexpc5']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Expense Type 6  </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="20" id="gexpc6" value="<? echo $globalprefrow['gexpc6']; ?>">

(only expense type which will request cheque numbers)

</fieldset>


<hr />

<fieldset><label> Text before list of rider payments </label>
<textarea class="ui-state-default ui-corner-all autosize" id="courier9"
style="width:60%;"><? echo $globalprefrow['courier9']; ?></textarea>
</fieldset>


<fieldset><label> Text after list of rider payments </label>
<textarea class="ui-state-default ui-corner-all autosize" id="courier10"
style="width:60%;"><? echo $globalprefrow['courier10']; ?></textarea>
</fieldset>

</div>

<div id="tabs-3">   <!-- System Info -->


<fieldset><label class="fieldLabel"> COJM Version </label> <? echo $globalprefrow['cojmversion']; ?></fieldset>



<fieldset><label class="fieldLabel"> Currency </label> &<? echo $globalprefrow['currencysymbol']; ?>
</fieldset>

<fieldset><label class="fieldLabel"> Distance </label> <?
if ($globalprefrow['distanceunit']=='miles') { echo ' miles '; } 
if ($globalprefrow['distanceunit']=='km') { echo ' km '; } ?></fieldset>


<fieldset><label class="fieldLabel"> root http </label> <? echo $globalprefrow['httproot']; ?></fieldset>

<fieldset><label class="fieldLabel"> root https </label> <? echo $globalprefrow['httproots']; ?></fieldset>


<fieldset><label class="fieldLabel"> Backup sent from </label> <? echo $globalprefrow['backupemailfrom']; ?></fieldset>

<fieldset><label class="fieldLabel"> Backup sent to </label> <? echo $globalprefrow['backupemailto']; ?></fieldset>

<fieldset><label class="fieldLabel"> Location Quick Check </label><? echo $globalprefrow['locationquickcheck']; ?></fieldset>

<fieldset><label class="fieldLabel"> Location Client Invoice page </label><? echo $globalprefrow['clweb6']; ?></fieldset>




<fieldset>
<label class="fieldLabel"> Website usage policy location </label> 
<?php echo $globalprefrow['clweb2']; ?>
</fieldset>


<fieldset><label class="fieldLabel"> Rider CSS File : </label> 
<? echo $globalprefrow['courier1']; ?></fieldset>




<fieldset><label class="fieldLabel"> SERVER_PORT </label><?php echo $_SERVER['SERVER_PORT']; ?></fieldset>

<fieldset><label class="fieldLabel"> HTTPS </label><?php echo $_SERVER["HTTPS"]; ?></fieldset>








</div>

<div id="tabs-2">  <!-- Advanced Settings -->
<fieldset><label class="fieldLabel"> Page Timeout </label> 
<select class="ui-state-default ui-corner-left" id="formtimeout">
<option <?php if ( $globalprefrow['formtimeout']=='125' ) { echo 'SELECTED'; } ?> value="125">2 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='300' ) { echo 'SELECTED'; } ?> value="300">5 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='600' ) { echo 'SELECTED'; } ?> value="600">10 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='900' ) { echo 'SELECTED'; } ?> value="900">15 mins</option>
<option <?php if ( $globalprefrow['formtimeout']=='1200' ) { echo 'SELECTED'; } ?> value="1200">20 mins</option>

</select>

 timeout reminder not shown on mobile devices, if another user has changed job then the page may have already timed out.

</fieldset>


<div class="line"></div>
<fieldset><label class="fieldLabel"> Show Page Load Times </label>
<input type="checkbox" id="glob7" value="1" <?php if ($globalprefrow['glob7']=='1') { echo 'checked';} ?>></fieldset>



<fieldset><label class="fieldLabel"> Force Show COJM Debug info </label>
<input type="checkbox" id="adminlogoback" value="1" <?php if ($globalprefrow['adminlogoback']>0) { echo 'checked';} ?>></fieldset>


<fieldset><label class="fieldLabel"> Force admin https SSL </label>
<input type="checkbox" id="forcehttps" value="1" <?php if ($globalprefrow['forcehttps']>0) { echo 'checked';} ?> ></fieldset>


<fieldset><label class="fieldLabel"> Show Settings on Mobile device</label>
<input type="checkbox" id="showsettingsmobile" value="1" <?php if ($globalprefrow['showsettingsmobile']>0) { echo 'checked';} ?>></fieldset>


<fieldset><label class="fieldLabel"> COJM JS File </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="glob9" value="<? echo $globalprefrow['glob9']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> COJM CSS File </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" id="glob10" value="<? echo $globalprefrow['glob10']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Show Licensed Mail Options</label>
<input type="checkbox" name="showpostcomm" value="1" <?php if ($globalprefrow['showpostcomm']>0) { echo 'checked';} ?>></fieldset>

<fieldset><label class="fieldLabel"> Inaccurate Postcodes </label> 
<input type="checkbox"  value="1" <?php if ($globalprefrow['inaccuratepostcode']>0) { echo 'checked';} ?>></fieldset>

<div class="line"> </div>

<fieldset><label class="fieldLabel"> backup ftp server </label><?php echo $globalprefrow['backupftpserver']; ?></fieldset>
<fieldset><label class="fieldLabel"> backup ftp username </label><?php echo $globalprefrow['backupftpusername']; ?></fieldset>
<fieldset><label class="fieldLabel"> backup ftp passwd </label><?php echo $globalprefrow['backupftppasswd']; ?></fieldset>




<div class="line"> </div>

	
<fieldset><label class="fieldLabel"> grams CO<sub>2</sub> saved per 
<?php if ($globalprefrow['distanceunit']=='miles') { echo 'mile '; } else { echo $globalprefrow['distanceunit']; } ?> </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="co2perdist" value="<?php echo $globalprefrow['co2perdist']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> grams PM<sub>10</sub> saved per 
<?php if ($globalprefrow['distanceunit']=='miles') { echo 'mile '; } else { echo $globalprefrow['distanceunit']; } ?> </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="pm10perdist" value="<?php echo $globalprefrow['pm10perdist']; ?>"></fieldset>

<div class="line"> </div>
	
<fieldset><label class="fieldLabel"> minutes difference </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="10" name="waitingtimedelay" value="<?php echo $globalprefrow['waitingtimedelay']; ?>">
on-site time to en-route with delivery before prompting to add waiting time
</fieldset>


<fieldset><label class="fieldLabel"> Rider Top menu selected colour </label> #<input 
class="ui-state-default ui-corner-all pad" type="text" size="8" name="courier3" value="<? echo $globalprefrow['courier3']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Rider Logo Location </label> <input 
class="ui-state-default ui-corner-all pad" type="text" size="60" name="courier4" value="<? echo $globalprefrow['courier4']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Rider Logo Style </label> <input 
class="ui-state-default ui-corner-all pad" type="text" size="60" name="courier5" value="<? echo $globalprefrow['courier5']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Rider COC or COD Style </label> <input 
class="ui-state-default ui-corner-all pad" type="text" size="60" name="courier6" value="<? echo $globalprefrow['courier6']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> Alert Email Address </label> <input 
class="ui-state-default ui-corner-all pad" placeholder="me@example.com" type="email" size="60" name="glob8" value="<? echo $globalprefrow['glob8']; ?>"></fieldset>

<fieldset><label class="fieldLabel"> googlemapapiv3key </label> <input 
class="ui-state-default ui-corner-all pad" placeholder="Get from google for your domain" size="60" name="googlemapapiv3key" value="<? echo $globalprefrow['googlemapapiv3key']; ?>"></fieldset>


</div>


</div>

</div>

<script type="text/javascript">
	$(function(){ $(".autosize").autosize();	}); // needs to be called before tabs
	$(function() {	$("#tabs").tabs(); });
	
	$(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+15));
});
	
	$(function(){ $("#rangeBa, #rangeBb").daterangepicker();  });	


var formbirthday=<?php echo microtime(TRUE); ?>; 	
var globalname='';
var newvalue='';


$("#numjobs").change(function () {
 globalname='numjobs';	
 newvalue=$("#numjobs").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#numjobsm").change(function () {
 globalname='numjobsm';	
 newvalue=$("#numjobsm").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob6").change(function () {
 globalname='glob6';	
 newvalue=$("#glob6").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#courier2").change(function () {
 globalname='courier2';	
 newvalue=$("#courier2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob11").change(function () {
 globalname='glob11';
if($('#glob11').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob1").change(function () {
 globalname='glob1';	
 newvalue=$("#glob1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob2").change(function () {
 globalname='glob2';	
 newvalue=$("#glob2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob3").change(function () {
 globalname='glob3';	
 newvalue=$("#glob3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob4").change(function () {
 globalname='glob4';	
 newvalue=$("#glob4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob5").change(function () {
 globalname='glob5';	
 newvalue=$("#glob5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#unrider1").change(function () {
 globalname='unrider1';	
 newvalue=$("#unrider1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#unrider2").change(function () {
 globalname='unrider2';	
 newvalue=$("#unrider2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#adminlogo").change(function () {
 globalname='adminlogo';	
 newvalue=$("#adminlogo").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#adminlogoabs").change(function () {
 globalname='adminlogoabs';	
 newvalue=$("#adminlogoabs").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#adminlogowidth").change(function () {
 globalname='adminlogowidth';	
 newvalue=$("#adminlogowidth").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#adminlogoheight").change(function () {
 globalname='adminlogoheight';	
 newvalue=$("#adminlogoheight").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#highlightcolour").change(function () {
 globalname='highlightcolour';	
 newvalue=$("#highlightcolour").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#highlightcolourno").change(function () {
 globalname='highlightcolourno';	
 newvalue=$("#highlightcolourno").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#viewedicon").change(function () {
 globalname='viewedicon';	
 newvalue=$("#viewedicon").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#unviewedicon").change(function () {
 globalname='unviewedicon';	
 newvalue=$("#unviewedicon").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image1").change(function () {
 globalname='image1';	
 newvalue=$("#image1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image2").change(function () {
 globalname='image2';	
 newvalue=$("#image2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image3").change(function () {
 globalname='image3';	
 newvalue=$("#image3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image4").change(function () {
 globalname='image4';	
 newvalue=$("#image4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image5").change(function () {
 globalname='image5';	
 newvalue=$("#image5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#image6").change(function () {
 globalname='image6';	
 newvalue=$("#image6").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#sound1").change(function () {
 globalname='sound1';	
 newvalue=$("#sound1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#globalname").change(function () {
 globalname='globalname';	
 newvalue=$("#globalname").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#globalshortname").change(function () {
 globalname='globalshortname';	
 newvalue=$("#globalshortname").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#myaddress1").change(function () {
 globalname='myaddress1';	
 newvalue=$("#myaddress1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#myaddress2").change(function () {
 globalname='myaddress2';	
 newvalue=$("#myaddress2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#myaddress3").change(function () {
 globalname='myaddress3';	
 newvalue=$("#myaddress3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#myaddress4").change(function () {
 globalname='myaddress4';	
 newvalue=$("#myaddress4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#myaddress5").change(function () {
 globalname='myaddress5';	
 newvalue=$("#myaddress5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#clweb3").change(function () {
 globalname='clweb3';	
 newvalue=$("#clweb3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#clweb4").change(function () {
 globalname='clweb4';	
 newvalue=$("#clweb4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#clweb5").change(function () {
 globalname='clweb5';	
 newvalue=$("#clweb5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn1").change(function () {
 globalname='favusrn1';	
 newvalue=$("#favusrn1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn2").change(function () {
 globalname='favusrn2';	
 newvalue=$("#favusrn2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn3").change(function () {
 globalname='favusrn3';	
 newvalue=$("#favusrn3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn4").change(function () {
 globalname='favusrn4';	
 newvalue=$("#favusrn4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn5").change(function () {
 globalname='favusrn5';	
 newvalue=$("#favusrn5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn6").change(function () {
 globalname='favusrn6';	
 newvalue=$("#favusrn6").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn7").change(function () {
 globalname='favusrn7';	
 newvalue=$("#favusrn7").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn8").change(function () {
 globalname='favusrn8';	
 newvalue=$("#favusrn8").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn9").change(function () {
 globalname='favusrn9';	
 newvalue=$("#favusrn9").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn10").change(function () {
 globalname='favusrn10';	
 newvalue=$("#favusrn10").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn11").change(function () {
 globalname='favusrn11';	
 newvalue=$("#favusrn11").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn12").change(function () {
 globalname='favusrn12';	
 newvalue=$("#favusrn12").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn13").change(function () {
 globalname='favusrn13';	
 newvalue=$("#favusrn13").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn14").change(function () {
 globalname='favusrn14';	
 newvalue=$("#favusrn14").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn15").change(function () {
 globalname='favusrn15';	
 newvalue=$("#favusrn15").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn16").change(function () {
 globalname='favusrn16';	
 newvalue=$("#favusrn16").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn17").change(function () {
 globalname='favusrn17';	
 newvalue=$("#favusrn17").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn18").change(function () {
 globalname='favusrn18';	
 newvalue=$("#favusrn18").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#favusrn19").change(function () {
 globalname='favusrn19';	
 newvalue=$("#favusrn19").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#favusrn20").change(function () {
 globalname='favusrn20';	
 newvalue=$("#favusrn20").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#vatbanda").change(function () {
 globalname='vatbanda';	
 newvalue=$("#vatbanda").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#vatbandb").change(function () {
 globalname='vatbandb';	
 newvalue=$("#vatbandb").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#gexpc1").change(function () {
 globalname='gexpc1';	
 newvalue=$("#gexpc1").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#gexpc2").change(function () {
 globalname='gexpc2';	
 newvalue=$("#gexpc2").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#gexpc3").change(function () {
 globalname='gexpc3';	
 newvalue=$("#gexpc3").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#gexpc4").change(function () {
 globalname='gexpc4';	
 newvalue=$("#gexpc4").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#gexpc5").change(function () {
 globalname='gexpc5';	
 newvalue=$("#gexpc5").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});

$("#gexpc6").change(function () {
 globalname='gexpc6';	
 newvalue=$("#gexpc6").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#courier9").change(function () {
 globalname='courier9';	
 newvalue=$("#courier9").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});



$("#courier10").change(function () {
 globalname='courier10';	
 newvalue=$("#courier10").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob7").change(function () {
 globalname='glob7';
if($('#glob7').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob9").change(function () {
 globalname='glob9';
 newvalue=$("#glob9").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#glob10").change(function () {
 globalname='glob10';
 newvalue=$("#glob10").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});






$("#adminlogoback").change(function () {
 globalname='adminlogoback';
if($('#adminlogoback').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});



$("#forcehttps").change(function () {
 globalname='forcehttps';
if($('#forcehttps').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});


$("#showsettingsmobile").change(function () {
 globalname='showsettingsmobile';
if($('#showsettingsmobile').prop('checked')) { // something when checked
	newvalue=1;
} else { // something else when not
	newvalue=0;
}
//  alert(globalname +' ' + newvalue);
changedvar();
});




















$("#formtimeout").change(function () {
 globalname='formtimeout';	
 newvalue=$("#formtimeout").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});















$("#clweb8").change(function () {
 globalname='clweb8';	
 newvalue=$("#clweb8").val();	
//  alert(globalname +' ' + newvalue);
changedvar();
});
		
function changedvar(){
// alert(globalname + ' ' + newvalue);
 newvalue = btoa(newvalue);
	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxeditglobals',
		formbirthday:formbirthday,
		globalname:globalname,
		newvalue:newvalue},
		type:'post',
        success: function(data) {
$('#tabs').append(data);
	},
		complete: function(data) {
		showmessage();
		}
});
}
</script>
<?php  include 'footer.php'; echo '</body></html>';