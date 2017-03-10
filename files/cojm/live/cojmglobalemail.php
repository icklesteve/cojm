<?php 

/*
    COJM Courier Online Operations Management
	cojmglobalemail.php - Edit Email Text Options
    Copyright (C) 2016 S.Young cojm.co.uk

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

error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

$title = "COJM Email Settings";

?><!DOCTYPE html> 
<html lang="en"> <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title); ?> </title>
</head><body>
<? 
$adminmenu=0;
$settingsmenu=1;
$hasforms='1';
$filename='cojmglobalemail.php';
include "changejob.php";



// $infotext=$infotext.'<p><strong>Variables you can use</strong></p>
// $id : shows the cojm reference
// <br>$c02text : estimated CO<sub>2</sub> saving.
// <br>\n is advised every 150 characters.


include "cojmmenu.php"; 
?><div class="Post Spaceout">
<div class="ui-widget">	<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;"><p>

<form action="#" method="post" />
<input type="hidden" name="page" value="editglobalemail" />
<input type="hidden" name="formbirthday" value="<?php echo date("U");  ?>" />

<?php echo '<fieldset><label for="txtName" class="fieldLabel"> Email From Address </label>'; ?>
<input class="ui-state-default ui-corner-all pad"
 type="text" size=30 name="emailfrom" value="<? echo $globalprefrow['emailfrom']; ?>"></fieldset>

<?php echo '<fieldset><label for="txtName" class="fieldLabel"> Email From Name </label>'; ?>
<input class="ui-state-default ui-corner-all pad" 
type="text" size=30 name="emailfromname" value="<? echo $globalprefrow['emailfromname']; ?>"></fieldset>

<?php echo '<fieldset><label for="txtName" class="fieldLabel"> Bcc all emails sent to </label>'; ?>
 <input type="text" class="ui-state-default ui-corner-all pad" 
size=30 name="emailbcc" value="<? echo $globalprefrow['emailbcc']; ?>"></fieldset>

<div class="line"></div>


<?php echo '<fieldset><label for="txtName" class="fieldLabel"> Email Title : <span style="float:right"> '.$globalprefrow['globalshortname'].'</span> </label>'; ?>


 <input class="ui-state-default ui-corner-all pad" type="text" size=50 name="email1" value="<? echo $globalprefrow['email1']; ?>"> 123456 / CLIENT REFERENCE</fieldset>

 <div class="line"></div>

<fieldset><label for="txtName" class="fieldLabel">Good Morning, </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="email14" value="<? echo $globalprefrow['email14']; ?>"></fieldset>


<fieldset><label for="txtName" class="fieldLabel">Good Afternoon, </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="email15" value="<? echo $globalprefrow['email15']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel">Good Evening, (after 7pm) </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="30" name="email16" value="<? echo $globalprefrow['email16']; ?>"></fieldset>


<fieldset><label for="txtName" class="fieldLabel"> Update for delivery ref : </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email2" value="<? echo $globalprefrow['email2']; ?>"> 123456 / CLIENT REFERENCE</fieldset>
<fieldset><label for="txtName" class="fieldLabel">Delivery Status : </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="60" name="email3" value="<? echo $globalprefrow['email3']; ?>"></fieldset>
<fieldset><label for="txtName" class="fieldLabel">, signed with the surname </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email8" value="<? echo $globalprefrow['email8']; ?>"></fieldset>
<fieldset><label for="txtName" class="fieldLabel">Collection Scheduled for </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email4" value="<? echo $globalprefrow['email4']; ?>"></fieldset>
<fieldset><label for="txtName" class="fieldLabel">Collected : </label> 
<input type="text" class="ui-state-default ui-corner-all pad" size="60" name="email5" value="<? echo $globalprefrow['email5']; ?>"></fieldset>
<fieldset><label for="txtName" class="fieldLabel">Delivery Scheduled for : </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email6" value="<? echo $globalprefrow['email6']; ?>"></fieldset>
<fieldset><label for="txtName" class="fieldLabel">Delivery completed at : </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email7" value="<? echo $globalprefrow['email7']; ?>"></fieldset>
<fieldset><label for="txtName" class="fieldLabel">Proof of delivery available online </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email9" value="<? echo $globalprefrow['email9']; ?>"></fieldset>
<fieldset><label for="txtName" class="fieldLabel">Estimated minimum CO2 saving : </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email10" value="<? echo $globalprefrow['email10']; ?>"></fieldset>
<fieldset><label for="txtName" class="fieldLabel">Estimated minimum PM10 saving : </label> 
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email11" value="<? echo $globalprefrow['email11']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel">The total cost for this service </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email13" value="<? echo $globalprefrow['email13']; ?>"></fieldset>


<fieldset><label for="txtName" class="fieldLabel">To date, we have helped </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email12" value="<? echo $globalprefrow['email12']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel">to save </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email17" value="<? echo $globalprefrow['email17']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel">Further info may be available by entering tracking ref </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email18" value="<? echo $globalprefrow['email18']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel">via our website, </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email19" value="<? echo $globalprefrow['email19']; ?>"></fieldset>

<fieldset><label for="txtName" class="fieldLabel">or following this link </label>
<input class="ui-state-default ui-corner-all pad" type="text" size="60" name="email20" value="<? echo $globalprefrow['email20']; ?>"></fieldset>


<div class="line"></div>


<? // <p>Email Header :  <br><TEXTAREA name="emailheader" rows="10" cols="<? echo $globalprefrow['composeemailwidth'] .'">'. $globalprefrow['emailheader']. '</TEXTAREA></p>';
?><p>HTML Email Header : <a href="http://www.w3schools.com/html/html_colors.asp" target="_blank">Colour Guide, use HEX, ie #000000</a>
<br><TEXTAREA name="htmlemailheader" rows="10" class="ui-state-default ui-corner-all autosize" 
style="width: 100%;"><? echo $globalprefrow['htmlemailheader']; ?></TEXTAREA></p>

<p>Plain Email Close :  <br><TEXTAREA class="ui-state-default ui-corner-all autosize" name="emailbody" rows="10" 
style="width:100%;"><? echo $globalprefrow['emailbody']; ?></TEXTAREA></p>
<p>HTML Email Close :  <br><TEXTAREA class="ui-state-default ui-corner-all autosize" name="htmlemailbody" rows="10" 
style="width:100%;"><? echo $globalprefrow['htmlemailbody']; ?></TEXTAREA></p>


<p>Email Footer :  <br><TEXTAREA class="ui-state-default ui-corner-all autosize" name="emailfooter" rows="10" 
style="width:100%;"><? echo $globalprefrow['emailfooter']; ?></TEXTAREA></p>
<p>HTML Email Footer :  <br><TEXTAREA class="ui-state-default ui-corner-all autosize" name="htmlemailfooter" rows="10" 
style="width:100%;"><? echo $globalprefrow['htmlemailfooter']; ?></TEXTAREA></p>

<div class="line"></div>

<button type="submit" >Edit Settings</button>
</form>

</p></div></div>
<div class="vpad"> </div>
<div class="line"></div>
<div class="vpad"> </div>

<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 1em;"> 
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
				<strong>Check the spam rating of your email. </strong> 
				After changing these settings, check the spam rating in the full headers of your email.<br >
 if your spamassassin score is higher than 1.0 , check 
 <a target="_blank" href="http://help.campaignmonitor.com/topic.aspx?t=104">http://help.campaignmonitor.com/topic.aspx?t=104</a>
 or download the utiity from <a target="_blank" href="http://www.mailingcheck.com/">mailchecking.com </a></p>
			</div>
		</div>

<div class="vpad"> </div>
<div class="line"></div>
<div class="vpad"> </div>
</div><br />

<? 
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

 ?>
</body></html>