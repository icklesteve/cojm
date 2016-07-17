<?php 

$alpha_time = microtime(TRUE);

error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$adminmenu='0';
$settingsmenu='1';
$hasforms='1';
$title = "COJM";
?><!DOCTYPE html> 
<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title); ?> Invoice Settings </title>
</head><body>
<?php 

$filename='cojmglobalinvoice.php';
include "changejob.php";
$sql = "SELECT * FROM globalprefs"; $sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$globalprefrow=mysql_fetch_array($sql_result);
include "cojmmenu.php"; 
?>
<div class="Post Spaceout ui-widget ui-state-highlight ui-corner-all" style="padding: 1em;">

<form action="#" method="post">
<input type="hidden" name="page" value="editglobalinvoice">


<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>">
<p>YOU ARE STRONGLY ADVISED TO ONLY MAKE 1 CHANGE AT A TIME !</p>
<div class="line"></div>
<p>Invoice Table Alternative Colour ( white is ffffff ) : 
<input type="text" class="ui-state-default ui-corner-all" size="8" name="invoicefooter" value=" <?php echo $globalprefrow['invoicefooter']; ?>">
Invoice Total Colour ( white is ffffff ) : 
<input type="text" class="ui-state-default ui-corner-all" size="8" name="invoicetotalcolour" value=" <?php echo $globalprefrow['invoicetotalcolour']; ?>">
</p>
<div class="line"></div>


<p>Title Font Name 

<select  class="ui-state-default ui-corner-left"  name="invoice1" >
<option <?php if ($globalprefrow['invoice1']=='tradegothicltstd') { echo ' selected="selected" '; } ?> value="tradegothicltstd"> Trade Gothic </option>
<option <?php if ($globalprefrow['invoice1']=='tradegothicltstdb') { echo ' selected '; } ?> value="tradegothicltstdb"> Trade Gothic Bold </option>
<option <?php if ($globalprefrow['invoice1']=='tradegothicltstdbi') { echo ' selected '; } ?> value="tradegothicltstdbi"> Trade Gothic Bold Italic </option>
<option <?php if ($globalprefrow['invoice1']=='almohanad') { echo ' selected '; } ?> value="almohanad"> Almohanad </option>
<option <?php if ($globalprefrow['invoice1']=='dejavusans') { echo ' selected '; } ?> value="dejavusans"> Dejavusans </option>
<option <?php if ($globalprefrow['invoice1']=='freesans') { echo ' selected '; } ?> value="freesans"> freesans </option>
<option <?php if ($globalprefrow['invoice1']=='freeserif') { echo ' selected '; } ?> value="freeserif"> freeserif </option>
<option <?php if ($globalprefrow['invoice1']=='helvetica') { echo ' selected="selected" '; } ?> value="helvetica"> Helvetica </option>
<option <?php if ($globalprefrow['invoice1']=='times') { echo ' selected '; } ?> value="times"> Times </option>
</select>

 Font Size <input class="ui-state-default ui-corner-all" type="text" size="5" name="invoice2" value="<? echo $globalprefrow['invoice2']; ?>"></p>

<p>Body Font Name 
<select  class="ui-state-default ui-corner-left"  name="invoice5" >
<option <?php if ($globalprefrow['invoice5']=='tradegothicltstd') { echo ' selected="selected" '; } ?> value="tradegothicltstd"> Trade Gothic </option>
<option <?php if ($globalprefrow['invoice5']=='tradegothicltstdb') { echo ' selected '; } ?> value="tradegothicltstdb"> Trade Gothic Bold </option>
<option <?php if ($globalprefrow['invoice5']=='tradegothicltstdbi') { echo ' selected '; } ?> value="tradegothicltstdbi"> Trade Gothic Bold Italic </option>
<option <?php if ($globalprefrow['invoice5']=='almohanad') { echo ' selected '; } ?> value="almohanad"> Almohanad </option>
<option <?php if ($globalprefrow['invoice5']=='dejavusans') { echo ' selected '; } ?> value="dejavusans"> Dejavusans </option>
<option <?php if ($globalprefrow['invoice5']=='freesans') { echo ' selected '; } ?> value="freesans"> freesans </option>
<option <?php if ($globalprefrow['invoice5']=='freeserif') { echo ' selected '; } ?> value="freeserif"> freeserif </option>
<option <?php if ($globalprefrow['invoice5']=='helvetica') { echo ' selected="selected" '; } ?> value="helvetica"> Helvetica </option>
<option <?php if ($globalprefrow['invoice5']=='times') { echo ' selected '; } ?> value="times"> Times </option>
</select>
 Font Size <input class="ui-state-default ui-corner-all caps" type="text" size="5" name="invoice6" value="<? echo $globalprefrow['invoice6']; ?>"></p>


<p>Footer Font Name 

<select  class="ui-state-default ui-corner-left"  name="invoice3" >
<option <?php if ($globalprefrow['invoice3']=='tradegothicltstd') { echo ' selected="selected" '; } ?> value="tradegothicltstd"> Trade Gothic </option>
<option <?php if ($globalprefrow['invoice3']=='tradegothicltstdb') { echo ' selected '; } ?> value="tradegothicltstdb"> Trade Gothic Bold </option>
<option <?php if ($globalprefrow['invoice3']=='tradegothicltstdbi') { echo ' selected '; } ?> value="tradegothicltstdbi"> Trade Gothic Bold Italic </option>
<option <?php if ($globalprefrow['invoice3']=='almohanad') { echo ' selected '; } ?> value="almohanad"> Almohanad </option>
<option <?php if ($globalprefrow['invoice3']=='dejavusans') { echo ' selected '; } ?> value="dejavusans"> Dejavusans </option>
<option <?php if ($globalprefrow['invoice3']=='freesans') { echo ' selected '; } ?> value="freesans"> freesans </option>
<option <?php if ($globalprefrow['invoice3']=='freeserif') { echo ' selected '; } ?> value="freeserif"> freeserif </option>
<option <?php if ($globalprefrow['invoice3']=='helvetica') { echo ' selected="selected" '; } ?> value="helvetica"> Helvetica </option>
<option <?php if ($globalprefrow['invoice3']=='times') { echo ' selected '; } ?> value="times"> Times </option>
</select>
 Font Size <input class="ui-state-default ui-corner-all caps" type="text" size="5" name="invoice4" value="<? echo $globalprefrow['invoice4']; ?>"></p>


<div class="line"></div>


<!--
<p>7 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice7" value="<? echo $globalprefrow['invoice7']; ?>"></p>
<p>8 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice8" value="<? echo $globalprefrow['invoice8']; ?>"></p>
<p>9 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice9" value="<? echo $globalprefrow['invoice9']; ?>"></p>
<p>10 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice10" value="<? echo $globalprefrow['invoice10']; ?>"></p>
<p>11 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice11" value="<? echo $globalprefrow['invoice11']; ?>"></p>
<p>12 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice12" value="<? echo $globalprefrow['invoice12']; ?>"></p>
<p>13 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice13" value="<? echo $globalprefrow['invoice13']; ?>"></p>
<p>14 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice14" value="<? echo $globalprefrow['invoice14']; ?>"></p>
<p>15 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice15" value="<? echo $globalprefrow['invoice15']; ?>"></p>
<p>16 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice16" value="<? echo $globalprefrow['invoice16']; ?>"></p>
<p>17 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice17" value="<? echo $globalprefrow['invoice17']; ?>"></p>
<p>18 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice18" value="<? echo $globalprefrow['invoice18']; ?>"></p>
<p>19 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice19" value="<? echo $globalprefrow['invoice19']; ?>"></p>
<p>20 :  <input class="ui-state-default ui-corner-all" type="text" size="50" name="invoice20" value="<? echo $globalprefrow['invoice20']; ?>"></p>


-->



<p>Phrases ready to copy / paste into invoice comments : </p> 

<fieldset><label> </label>

<textarea id="invoicefooter2" class="ui-state-default ui-corner-all autosize" name="invoicefooter2"  style="width: 65%; outline:none;" ><?php echo $globalprefrow['invoicefooter2']; ?>
</textarea></fieldset>
<div class="line"></div>

<p>Invoice Footer :</p> 
<textarea id="invoicefooter3" class="ui-state-default ui-corner-all autosize" name="invoicefooter3"  style="width: 65%; outline:none;"><?php echo $globalprefrow['invoicefooter3']; ?>
</textarea>
<p>The invoice reference number will be shown here, followed by </p>
<textarea id="invoicefooter4" class="ui-state-default ui-corner-all autosize" name="invoicefooter4" style="width: 65%; outline:none;" ><?php echo $globalprefrow['invoicefooter4']; ?></textarea>
<p>nb, no images with transparent backgrounds.</p>
<div class="line"></div>
<button type="submit"> Edit Settings </button>
</form><br />
<div class="line"></div>

</div>

<br />

<script type="text/javascript">

 		$(function(){ $(".autosize").autosize();	});

</script>






<?php

include 'footer.php';

?></body></html>
<?php mysql_close(); 