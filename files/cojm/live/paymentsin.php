<?php 
/*
    COJM Courier Online Operations Management
	paymentsin.php - New Payment by Client
    Copyright (C) 2017 S.Young cojm.co.uk

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
<meta name="viewport" content="width=device-width, height=device-height" >
<meta name="generator" content="COJM Expenses">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; 
?> 
<title><?php print ($title); ?> Single Payment</title>
<style>
 fieldset label, .aligned fieldset label {
    width:130px;
}
</style>


</head><body>
<?php 
include "changejob.php";
$adminmenu = "0";
$invoicemenu = "1";
$filename='paymentsin.php';
include "cojmmenu.php"; 

if (isset($_GET['paymentid'])) {
    $paymentid=trim($_GET['paymentid']);
} else {
    $paymentid='';
}


?>
<div class="Post" id="Post">
<div class="ui-state-highlight ui-corner-all p15" > 

<form id="paymentsin">
<input type="hidden" name="page" id="page" value="" >
<fieldset>
<label class="fieldLabel"> Ref </label>
<input class="caps ui-state-default ui-corner-all" type="number" step="1" name="paymentid" id="paymentid"

placeholder="Search ID"

value="<?php echo $paymentid; ?>">

<button id="paymentsearchbyid">Search Payment Ref</button>
<button id="addnewpayment"> New Payment </button>

</fieldset>

<hr />
<div id="paymentdetails" class="hideuntilneeded">

<fieldset>
    <label class="fieldLabel"> Amount &<?php echo $globalprefrow['currencysymbol']; ?></label>
    <input class="ui-state-default ui-corner-all caps" type="text" name="amountpaid" id="amountpaid" value="" placeholder="0.00" size="8">
</fieldset>

<fieldset>
    <label class="fieldLabel">Who From </label>
    <select class="ui-state-default ui-corner-all" id="combobox" name="client" >
    <option value="">Select..</option>
<?php

    $query = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient>0 ORDER BY CompanyName ASC";
    $stmt = $dbh->query($query);
    foreach ($stmt as $clrow) {
        $CustomerID = htmlspecialchars ($clrow['CustomerID']);
        $CompanyName = htmlspecialchars ($clrow['CompanyName']);
        print "<option ";
        if ($CustomerID == $clrow['CustomerID']) {
        //    echo "selected='SELECTED' ";
        }        
        echo ' value="'.$CustomerID.'">'.$CompanyName;
        echo '</option>';
    }
?>
    </select>
</fieldset>

<fieldset>
    <label class="fieldLabel"> Payment Date </label> 
    <input class="ui-state-default ui-corner-all caps" type="text" value="<?php echo date('d-m-Y'); ?>" id="paymentdate" size="12" name="paymentdate">
</fieldset>

<fieldset>
    <label class="fieldLabel"> Method of Payment </label>
    <select class="ui-state-default ui-corner-left" name="paymentmethod" id="paymentmethod" >
        <option selected value="" >Payment Method</option>
<?php

    $query = "SELECT paymenttypeid, paymenttypename FROM cojm_paymenttype ORDER BY paymenttypeid ASC";
    $stmt = $dbh->query($query);
    foreach ($stmt as $row) {        
        $paymenttypeid = htmlspecialchars ($row['paymenttypeid']);
        $paymenttypename = htmlspecialchars ($row['paymenttypename']);
        print "<option ";      
        echo ' value="'.$paymenttypeid.'">'.$paymenttypename;
        echo '</option>';
    }
?>
    </select>
</fieldset>


<fieldset>
    <label class="fieldLabel">Comments </label>
    <textarea class="ui-state-default ui-corner-all" 
    name="paymentcomment" 
    id="paymentcomment" 
    rows="2" 
    cols="50" 
    title="eg, Cheque Ref" 
    placeholder="eg, Cheque Ref" 
    style="padding-left: 3px;" ></textarea>
</fieldset>

<input type="hidden" id="newcomment" name="newcomment" value="">

</div>




</form>

</div>


<div id="paymentstats" >
</div>
<br />

</div>

<script type="text/javascript">

var formbirthday=<?php echo date("U"); ?>;

    $('#paymentsearchbyid').on('click', function (event) {
        event.preventDefault();    
        var notblankcheck=$("#paymentid").val();
        if (notblankcheck) {
            $.ajax({
                type: 'POST',
                url: 'ajax_lookup.php',
                data: {
                    lookuppage: 'paymentstuff',
                    paymentid: $("#paymentid").val()
                    
                },
                success:function(data){
                    $("#Post").append(data);
                },
                complete: function () {
                    $('#paymentcomment').trigger('autosize.resize');
                    var clientid=$("#combobox").val();
                    $( "#paymentstats" ).load( "ajax_lookup.php", { lookuppage: "paymentstuff", view: "client", clientid: clientid }, function() {
                        // alert( "Load was performed." );
                    });
                    
                    showmessage();
                },
                error:function (xhr, ajaxOptions, thrownError){
                    alert(thrownError); //throw any errors
                }
            });
        } else {
            message='Please add a payment reference to search';
            allok=0;
            showmessage();
            
        }
    });
    
       <?php
  if ($paymentid) {
      echo ' $("#paymentsearchbyid").click();   ';
  }
else {
    
    echo ' $( "#paymentstats" ).load( "ajax_lookup.php", { view: "initial", lookuppage: "paymentstuff" }, function() {}); ';
    
}  

?>
$(document).ready(function() {

    
    $(function () { // combobox + #toggle click
        $("#combobox").combobox();
        $("#toggle").click(function () {
            $("#combobox").toggle();
        });
    });

    
    $(function (){ // autosize comment
        $("#paymentcomment").autosize();
    });
    
    


    $('#addnewpayment').on('click', function (event) {
        event.preventDefault();            
        $("#page").val("addnewpayment");

        
        var newcommenttext = $('#paymentcomment').val().replace(/\n/g,"<br>");

        $("#newcomment").val(newcommenttext);
        
        var formdata=$('#paymentsin').serializeArray();
        
        $.ajax({
            type: 'POST',
            url: 'ajaxchangejob.php',
            data: formdata,
            success:function(data){
                // alert(data);
                $("#paymentdetails").append(data);
            },
            complete: function () {
                
                $("#paymentdetails").show(); 
                $("#amountpaid").val("0.00");
            $("select#combobox").val("");
            $("select#paymentmethod").val(""); 
            $("#paymentcomment").val("");
            $(".ui-autocomplete-input").val("");
            $("#editpayment").removeClass("hideuntilneeded"); 
            $( "#paymentstats" ).load( "ajax_lookup.php", { view: "initial", lookuppage: "paymentstuff" }, function() {
                showmessage();
            });            
                
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });
    });


	$(function() { // datepicker
		var dates = $( "#paymentdate" ).datepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
            dateFormat: 'dd-mm-yy',
			changeMonth:false
		});
	});
    


    $("#amountpaid").change(function () {
        // var amountcheck = $("#amountpaid").val();
        if ($("#amountpaid").val()) {
            var rounded = parseFloat($("#amountpaid").val()).toFixed(2); // rounded = 258.20
            // alert(rounded);
            if (rounded =="NaN") {
                message='Not a Number';
                allok=0;
                showmessage();
                $("#amountpaid").val("0.00");
            } else {
                $("#amountpaid").val(rounded);
            }
        } else {
            $("#amountpaid").val("0.00");
        }

        
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditpayment',
                whatchanged: 'amountpaid',
                formbirthday: formbirthday,
                paymentid: $("#paymentid").val(),
                paymentclient: $("#combobox").val(),
                amountpaid: $("#amountpaid").val()
            },
            type: 'post',
            success:function(data){
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
    



    $('#paymentdate').change(function (){
        var paymentdate=$("#paymentdate").val();
        if (paymentdate) {
        message='';
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditpayment',
                whatchanged: 'paymentdate',
                formbirthday: formbirthday,
                paymentid: $("#paymentid").val(),
                paymentclient: $("#combobox").val(),
                paymentdate: paymentdate
            },
            type: 'post',
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
        } else {
            message=' Please enter date ';
            allok=0;
            showmessage();
        }
    });






    $('#paymentmethod').change(function (){
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditpayment',
                whatchanged: 'paymentmethod',
                formbirthday: formbirthday,
                paymentid: $("#paymentid").val(),
                paymentclient: $("#combobox").val(),
                paymentmethod: $("#paymentmethod").val()
            },
            type: 'post',
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
    
    
    $('#paymentcomment').change(function (){
        var newcommenttext = $('#paymentcomment').val().replace(/\n/g,"<br>");
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditpayment',
                whatchanged: 'paymentcomment',
                formbirthday: formbirthday,
                paymentid: $("#paymentid").val(),
                paymentclient: $("#combobox").val(),
                paymentcomment: newcommenttext
            },
            type: 'post',
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
    
    
    
    
    
    
    
    
});

function comboboxchanged() {
    
            $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditpayment',
                whatchanged: 'paymentclient',
                formbirthday: formbirthday,
                paymentid: $("#paymentid").val(),
                paymentclient: $("#combobox").val()
            },
            type: 'post',
            success:function(data){
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
</script>
<?php

include "footer.php";

echo '</body></html>';
 
?>