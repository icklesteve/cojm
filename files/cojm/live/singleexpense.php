<?php 
/*
    COJM Courier Online Operations Management
	singleexpense.php - Display & Edit an expense
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
if ($globalprefrow['forcehttps']>0) { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

$script='';

if (isset($_POST['expenseref'])) {
    $expenseref=$_POST['expenseref'];
} else if (isset($_GET['expenseref'])) {
    $expenseref=$_GET['expenseref'];
}
else {
    $expenseref='';
}

if ($expenseref) {
    $script.=' setTimeout( function() { $("#expensesearchbyid").click() }, 150 ); ';
}

//  if expenseref, add js to click search button

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
<title><?php print ($title); ?> Single Expense</title>
</head><body>
<?php 
include "changejob.php";
$adminmenu = "0";
$invoicemenu = "1";
$filename='singleexpense.php';
include "cojmmenu.php"; 

?>
<div class="Post" id="Post">
<div class="ui-state-highlight ui-corner-all p15" > 

<form id="singleexpense">
<input type="hidden" name="page" id="page" value="lookup" >
<fieldset>
<label class="fieldLabel"> Ref </label>
<input class="caps ui-state-default ui-corner-left" name="expenseid" id="expenseid" type="number" step="1"
 placeholder="Search ID" value="<?php echo $expenseref ?>">

<button id="expensesearchbyid">Search Expense Ref</button>

</fieldset>

<hr />

<div id="expensedetails" class="hideuntilneeded">

<fieldset>
<label class="fieldLabel"> Amount &<?php echo $globalprefrow['currencysymbol']; ?></label>
<input class="caps ui-state-default ui-corner-all" type="text" name="amount" id="amount" value="">
</fieldset>

<fieldset><label class="fieldLabel">
 of which VAT <span style="position:relative; float:right;">
 &<?php echo $globalprefrow['currencysymbol'];?> &nbsp;</span></label>
<input class="caps ui-state-default ui-corner-all" type="text" name="expensevat" id="expensevat" size="6" value=""></fieldset>

<fieldset><label class="fieldLabel"> Department </label>
<select class="ui-state-default ui-corner-left" name="expensecode" id="expensecode">
<option value="0"> &nbsp; </option>
<?php 

$expensetext='';

$query = "SELECT expensecode, smallexpensename, expensedescription FROM expensecodes ORDER BY expensecode"; 

$result_id = mysql_query ($query, $conn_id); 
while (list ($expensecode, $smallexpensename, $expensedescription) = mysql_fetch_row ($result_id)) { 
    $expensedescription = htmlspecialchars ($expensedescription);   
    $expensecode = htmlspecialchars ($expensecode);
    $smallexpensename = htmlspecialchars ($smallexpensename); 
    print"<option ";
    print ("value=\"$expensecode\">$smallexpensename </option>\n");
} ?></select>
</fieldset> 
 
<fieldset><label class="fieldLabel">Who To </label>
<input class="caps ui-state-default ui-corner-all" type="text" name="whoto" id="whoto" value="">
</fieldset>

<fieldset><label class="fieldLabel">
<?php echo $globalprefrow['glob5'].'</label>';
 
$query = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 
$result_id = mysql_query ($query, $conn_id); 
print ("<select class=\"ui-state-default ui-corner-left\" name=\"cyclistref\" id=\"cyclistref\">\n"); 
while (list ($CyclistID, $cojmname) = mysql_fetch_row ($result_id)) {
    print ("<option value=\"$CyclistID\">$cojmname</option>\n");
}

print ("</select></fieldset>");
?>

<fieldset>
<label class="fieldLabel"> Expense Date </label> 
<input class="ui-state-default ui-corner-all caps" type="text" value="<?php echo date('d-m-Y'); ?>" id="expensedate" size="12" name="expensedate">
</fieldset>


<fieldset><label class="fieldLabel">Comments </label>
<textarea class="ui-state-default ui-corner-all" name="expensecomment" id="expensecomment" rows="2" cols="50" placeholder="eg, Cheque Ref" ></textarea>
</fieldset>

<input type="hidden" id="newcomment" name="newcomment" value="">

<fieldset>
<label class="fieldLabel"> Paid </label> 
<select class="ui-state-default ui-corner-left" name="paid" id="paid"> 
<option value="0" <?php if ($row['paid']<1) { echo 'selected'; } ?>> No
<option value="1" <?php if ($row['paid']>0) { echo 'selected'; } ?> > Yes
</select>
</fieldset>


<fieldset>
<label class="fieldLabel"> Method </label> 
<select class="ui-state-default ui-corner-left" name="paymentmethod" id="paymentmethod"> 
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
<span id="chequeref"></span>
</fieldset>


<fieldset><label class="fieldLabel"> &nbsp; </label>
<button id="editexpense" class="hideuntilneeded"> Edit Expense </button>
</fieldset>
<hr />
</div>

<fieldset><label class="fieldLabel"> &nbsp; </label>
<button id="addnewexpense"> Create New Expense </button>
</fieldset>

</form>

</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    
    $(function () {
        $("#combobox").combobox();
        $("#toggle").click(function () {
            $("#combobox").toggle();
        });
    });

    $(function (){ // autosize
        $("#expensecomment").autosize();
    });
    
	$(function() {
		var dates = $( "#expensedate" ).datepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
            dateFormat: 'dd-mm-yy',
			changeMonth:false
		});
	});

    $("#amount").change(function () {
        // var amountcheck = $("#amountpaid").val();
        if ($("#amount").val()) {
            var rounded = parseFloat($("#amount").val()).toFixed(2); // rounded = 258.20
            // alert(rounded);
            
            if (rounded =="NaN") {
                alert ("Not a Number");
                $("#amount").val("0.00");
            } else {
                $("#amount").val(rounded);
            }
        } else {
            $("#amount").val("0.00");
        }
    });

    $('#expensesearchbyid').on('click', function (event) {
        event.preventDefault();    
        var notblankcheck=$("#expenseid").val();
        if (notblankcheck) {
            
            message='';
            $("#page").val("lookup");
            
            var formdata=$('#singleexpense').serializeArray();
            $.ajax({
                type: 'POST',
                url: 'ajax_expense_lookup.php',
                data: formdata,
                success:function(data){
                    $("#Post").append(data);
                },
                complete: function () {
                    $('#expensecomment').trigger('autosize.resize');
                    showmessage();
                },
                error:function (xhr, ajaxOptions, thrownError){
                    alert(thrownError); //throw any errors
                }
            });
        } else {
            message='Please add an expense reference to search';
            allok=0;
            showmessage();
        }
    });
    

    $('#editexpense').on('click', function (event) { 
        event.preventDefault();
        message='';
        var notblankcheck=$("#expenseid").val();
        if (notblankcheck) {
            $("#page").val("editexpense");
            var newcommenttext = $('#expensecomment').val().replace(/\n/g,"<br>");
            $("#newcomment").val(newcommenttext);
            var formdata=$('#singleexpense').serializeArray();
        
        $.ajax({
            type: 'post',
            url: 'ajaxchangejob.php',
            data: formdata,
            success:function(data){
                // alert(data);
                $("#Post").append(data);
            },
            complete: function () {
                showmessage();
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });
            
        }
    
    });

    $('#addnewexpense').on('click', function (event) {
        event.preventDefault();
        message='';
        $("#page").val("addnewexpense");

        
        var newcommenttext = $('#expensecomment').val().replace(/\n/g,"<br>");

        $("#newcomment").val(newcommenttext);
        
        var formdata=$('#singleexpense').serializeArray();
        
        $.ajax({
            type: 'POST',
            url: 'ajaxchangejob.php',
            data: formdata,
            success:function(data){
                // alert(data);
                $("#Post").append(data);
            },
            complete: function () {
                showmessage();
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });
    });    
    <?php echo $script; ?>
});

function comboboxchanged() { }
</script>
<?php

include "footer.php";

echo '</body></html>';
mysql_close(); 
$dbh=null;
 
?>