<?php 
/*
    COJM Courier Online Operations Management
	expenses.php - New / Edit Expense
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

include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

$title = "COJM";
?>
<!doctype html>
<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<meta name="generator" content="COJM Expenses">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; 
?>

<title><?php print ($title); ?> Expenses</title>
</head><body>
<?php 
include "changejob.php";
$adminmenu = "0";
$invoicemenu = "1";
$hasforms='1';
$filename='expenses.php';
include "cojmmenu.php"; 


if (isset($expenseref)) {} else {
if (isset($_POST['expenseref'])) { $expenseref=$_POST['expenseref']; }
else if (isset($_GET['expenseref'])) { $expenseref=$_GET['expenseref']; }
else $expenseref='';
}


$sql = "SELECT * FROM expenses WHERE expenseref = '$expenseref' LIMIT 1";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$row=mysql_fetch_array($sql_result);

?>

<div class="Post">

<div class="">
<div class="ui-state-highlight ui-corner-all p15" > 
<form action="?page=selectexpense" method="post" >
<fieldset><label class="fieldLabel"> Search Ref or Leave Blank for new</label>
<input type="hidden" name="formbirthday" value="<?php echo date("U").'">

<input class="caps ui-state-default ui-corner-all" 
TABINDEX=1 type="number" step="1" name="expenseref" size="6" value="'. $expenseref.'">
<button type="submit"> Select </button>'; 

if ($page<>'createnew') { echo ' <a href="?page=createnew">Create New</a> '; }

// if (($row['expenseref']=='') and ($page<>'createnew')) { echo ' No Expense Reference matching this ID found.'; }


echo '
 </fieldset>
</form>
<div class="vpad"> </div>
<div class="line"></div>
<div class="vpad"> </div>
<form action="?page=editexpense" method="post">
<input type="hidden" name="formbirthday" value="'. date("U").'">
<input type="hidden" name="expenseref" value="'.$row['expenseref'].'">';

?>

<fieldset><label class="fieldLabel">
 Cost  <span style="position:relative; float:right;">
 &<?php echo $globalprefrow['currencysymbol'];?> &nbsp;</span> </label>
 
 <input class="caps ui-state-default ui-corner-all" type="text" 
 name="expensecost" size="7" value="<?php 
echo $row["expensecost"]; ?>"></fieldset>

<div class="vpad"> </div>

<fieldset><label class="fieldLabel">
 of which VAT <span style="position:relative; float:right;">
 &<?php echo $globalprefrow['currencysymbol'];?> &nbsp;</span></label>
 <input class="caps ui-state-default ui-corner-all" type="text" name="expensevat" size="6" value="<?php 
 echo $row["expensevat"]; ?>"></fieldset>
 
 <div class="vpad"> </div>
 <fieldset><label class="fieldLabel">
 
 Department </label>
<select class="ui-state-default ui-corner-left" name="expensecode">
<?php 

$expensetext='';

$query = "SELECT expensecode, smallexpensename, expensedescription FROM expensecodes ORDER BY expensecode"; 



$result_id = mysql_query ($query, $conn_id); 
while (list ($expensecode, $smallexpensename, $expensedescription) = mysql_fetch_row ($result_id)) { 
$expensedescription = htmlspecialchars ($expensedescription);   
$expensecode = htmlspecialchars ($expensecode); $smallexpensename = htmlspecialchars ($smallexpensename); 
print"<option ";
if ($expensecode == $row['expensecode']) {echo "SELECTED "; $expensetext=$expensedescription;}
print ("value=\"$expensecode\">$smallexpensename</option>\n");
 
 } ?></select>
  <?php echo $expensetext; ?></fieldset>
<div class="vpad"> </div>
  <fieldset><label class="fieldLabel">Who To </label>
 <input class="caps ui-state-default ui-corner-all" type="text" name="whoto" value="<?php echo $row['whoto']; ?>"></fieldset>

 <div class="vpad"> </div>
 <fieldset><label class="fieldLabel">
 <?php echo $globalprefrow['glob5'].'</label>';
 
 
 
 
 
 
 
 
 
 
 

 $query = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 
 $result_id = mysql_query ($query, $conn_id); 
 print ("<select class=\"ui-state-default ui-corner-left\" name=\"cyclistref\">\n"); 
 while (list ($CyclistID, $cojmname) = mysql_fetch_row ($result_id))
 { print ("<option "); if ($row['cyclistref'] == $CyclistID) {echo " SELECTED ";  }
print ("value=\"$CyclistID\">$cojmname</option>\n");
 }

 
// display adittional option if inactive rider
$query = "SELECT isactive, cojmname FROM Cyclist WHERE CyclistID = :CyclistID LIMIT 0,1";
$depstmt = $dbh->prepare($query);
$depstmt->bindParam(':CyclistID', $row['cyclistref'], PDO::PARAM_INT); 
$depstmt->execute();
$riderfound = $depstmt->rowCount();

if ($riderfound>0)
{
$dep = $depstmt->fetchObject();
$isactive=$dep->isactive;
$cojmname=$dep->cojmname;

 if ($isactive<>1) { 
 echo ' <option selected value="'.$row['cyclistref'].'">'.$cojmname.' Inactive</option> ';
 } 
} // ends check for rider found
 
 

 print ("</select></fieldset>");
?>

<div class="vpad"> </div>


<fieldset><label for="expensedate" class="fieldLabel"> Date </label> 
<input class="ui-state-default ui-corner-all caps" type="text" value="<?php 
if ($row['expensedate']>'10') {echo date('d-m-Y', strtotime($row['expensedate'])); } else { echo date('d-m-Y', strtotime('now')); } ?>" 
id="expensedate" size="12" name="expensedate"></fieldset>

<div class="vpad"> </div>


<fieldset><label class="fieldLabel">Description </label>
<TEXTAREA class="ui-state-default ui-corner-all" name="description" rows="2" cols="50"> <?php echo $row['description']; ?></TEXTAREA></fieldset>

<div class="vpad"> </div>

<fieldset><label class="fieldLabel"> Paid </label> 
<select class="ui-state-default ui-corner-left" name="paid"> 
<option <?php if ($row['paid']<1) { echo 'selected'; } ?>> No
<option <?php if ($row['paid']>0) { echo 'selected'; } ?> > Yes
</select></fieldset>

<div class="vpad"> </div>

<fieldset><label class="fieldLabel"> Method </label> 
<select class="ui-state-default ui-corner-left" name="paymentmethod"> 
<option value="" > &nbsp; </option>
<?php 
 if ($globalprefrow['gexpc1']){ echo '<option value="expc1"'; if ($row['expc1']>0) { echo ' selected '; }  echo '> '.$globalprefrow['gexpc1'].'</option>'; }
 if ($globalprefrow['gexpc2']){ echo '<option value="expc2"'; if ($row['expc2']>0) { echo ' selected '; }  echo '> '.$globalprefrow['gexpc2'].'</option>'; }
 if ($globalprefrow['gexpc3']){ echo '<option value="expc3"'; if ($row['expc3']>0) { echo ' selected '; }  echo '> '.$globalprefrow['gexpc3'].'</option>'; }
 if ($globalprefrow['gexpc4']){ echo '<option value="expc4"'; if ($row['expc4']>0) { echo ' selected '; }  echo '> '.$globalprefrow['gexpc4'].'</option>'; }
 if ($globalprefrow['gexpc5']){ echo '<option value="expc5"'; if ($row['expc5']>0) { echo ' selected '; }  echo '> '.$globalprefrow['gexpc5'].'</option>'; }
 if ($globalprefrow['gexpc6']){ echo '<option value="expc6"'; if ($row['expc6']>0) { echo ' selected '; }  echo '> '.$globalprefrow['gexpc6'].'</option>'; }
?>
 </select>
Cheque Ref <input class="ui-state-default ui-corner-all caps" type="text" size="15" name="chequeref" value="<?php echo $row['chequeref']; ?>">
</fieldset>
<div class="vpad line"></div>
<fieldset><label class="fieldLabel">


<?php


if ($page=='createnew') { echo ' <button type="submit" > New Expense </button> <label>'; } else { 


echo '<button type="submit" > Edit Expense </button> </label>
<select class="ui-state-default ui-corner-left" name="newfromoldexpense"> 
<option value="0" > Edit
<option value="1" > Duplicate
</select>
</fieldset>'; }

?>



</form>
</div>
</div>
<div class="vpad"> </div>
<?php

$sql = "SELECT * FROM expenses WHERE (`expenses`.`expensedate` ='0000-00-00 00:00:00' ) ";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 
 while ($row = mysql_fetch_array($sql_result)) {
     extract($row);

echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 1em;"> 
				<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
				<p>No date on expense ref '.$row['expenseref'].'</p></div></div><div class="vpad"> </div>'; }

$sql = "SELECT * FROM expenses WHERE (`expenses`.`expensecost` ='0' ) ";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); 
 while ($row = mysql_fetch_array($sql_result)) {
     extract($row);
echo '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 1em;"> 
				<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
				<p>Zero cost on expense ref '.$row['expenseref'].'</p></div></div><div class="vpad"> </div>'; }

				
				?>

				<script type="text/javascript">
$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+20));

	$(function() {
		var dates = $( "#expensedate" ).datepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
            dateFormat: 'dd-mm-yy',
			changeMonth:false
		});
	});
	});
	</script>


<br /><div class="vpad"> </div>
</div>

<?php

include "footer.php";

echo '</body></html>';
 mysql_close(); ?>