<?php 
/*
    COJM Courier Online Operations Management
	singleexpense.php - Display & Edit an expense
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
if ($globalprefrow['forcehttps']>0) { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

if (isset($_POST['expenseref'])) {
    $expenseref=$_POST['expenseref'];
} else if (isset($_GET['expenseref'])) {
    $expenseref=$_GET['expenseref'];
}
else {
    $expenseref='';
}



//  if expenseref, add js to click search button

$title = "COJM";
?>
<!doctype html>
<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height " >
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
<div class="Post clearfix" id="Post">

    <div class="ui-state-highlight ui-corner-all p15 aligned" > 
        <form id="singleexpense">
            <input type="hidden" name="page" id="page" value="lookup" >
            <fieldset>
                <label class="fieldLabel"> Ref </label>
                <input class="caps ui-state-default ui-corner-left" name="expenseid" id="expenseid" type="number" step="1"
 placeholder="Search ID" value="<?php echo $expenseref ?>">

                <button id="expensesearchbyid">Search Expense Ref</button>
                <button id="addnewexpense"> New Expense </button>
            </fieldset>
        </form>
        <hr />





        <div id="expensedetails" class="hideuntilneeded ">

            <fieldset> <label class="fieldLabel"> Total Amount &<?php echo $globalprefrow['currencysymbol']; ?> </label>
<input class="caps ui-state-default ui-corner-all" type="text" name="amount" id="amount" size="6" />
</fieldset>

            <fieldset><label class="fieldLabel">
 of which VAT <span style="position:relative; float:right;">
 &<?php echo $globalprefrow['currencysymbol']; ?> &nbsp;</span></label>
<input class="caps ui-state-default ui-corner-all" type="text" name="expensevat" id="expensevat" size="6">
</fieldset>


            <fieldset> <label class="fieldLabel"> Paid </label> 
<select class="ui-state-default ui-corner-left" name="paid" id="paid"> 
<option value="0" > No </option>
<option value="1" > Yes </option>
</select>
</fieldset>


            <fieldset> <label class="fieldLabel"> Expense Date </label> 
<input class="ui-state-default ui-corner-all caps" type="text" value="" id="expensedate" size="12" name="expensedate">
</fieldset>

            <fieldset> <label class="fieldLabel"> Department </label>
<select class="ui-state-default ui-corner-left" name="expensecode" id="expensecode">
<option value=""> Select Expense Code </option> 
<?php 

$expensetext='';
$sql = "SELECT expensecode, smallexpensename FROM expensecodes ORDER BY expensecode"; 

$stmt = $dbh->query($sql);
$data = $stmt->fetchAll();

foreach ($data as $e) {
    $smallexpensename = htmlspecialchars ($e['smallexpensename']); 
    if ($smallexpensename=='') { $smallexpensename=' &nbsp; '; }
    print"<option ";
    print 'value="'.$e['expensecode'].'">'.$smallexpensename.' </option>';
}
?>
</select>
<span id="expensedescription"> </span>
</fieldset> 



            <fieldset id="riderselect" class="hideuntilneeded"><label class="fieldLabel">
<?php echo $globalprefrow['glob5'].'</label>';

print ("<select class=\"ui-state-default ui-corner-left\" name=\"cyclistref\" id=\"cyclistref\">\n");
 
$sql = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID";


$stmt = $dbh->query($sql);
$data = $stmt->fetchAll();

foreach ($data as $c) {
    echo '<option value="'.$c['CyclistID'].'">'.htmlspecialchars($c['cojmname']).'</option>';
}

?>
</select>
</fieldset> 

 
            <fieldset><label class="fieldLabel">Who To </label>
<input class="caps ui-state-default ui-corner-all" type="text" name="whoto" id="whoto" value="">
</fieldset>


            <fieldset>
<label class="fieldLabel"> Method </label> 
<select class="ui-state-default ui-corner-left" name="paymentmethod" id="paymentmethod"> 
<option value="" > &nbsp; </option>
<?php 
 if ($globalprefrow['gexpc1']){ echo '<option value="expc1"> '.$globalprefrow['gexpc1'].'</option>'; }
 if ($globalprefrow['gexpc2']){ echo '<option value="expc2"> '.$globalprefrow['gexpc2'].'</option>'; }
 if ($globalprefrow['gexpc3']){ echo '<option value="expc3"> '.$globalprefrow['gexpc3'].'</option>'; }
 if ($globalprefrow['gexpc4']){ echo '<option value="expc4"> '.$globalprefrow['gexpc4'].'</option>'; }
 if ($globalprefrow['gexpc5']){ echo '<option value="expc5"> '.$globalprefrow['gexpc5'].'</option>'; }
 if ($globalprefrow['gexpc6']){ echo '<option value="expc6"> '.$globalprefrow['gexpc6'].'</option>'; }
?>
</select>
        <span id="chequeref"></span>
        </fieldset>

            <fieldset><label class="fieldLabel">Comments </label>
        <textarea 
        class="ui-state-default ui-corner-all" 
        name="expensecomment" 
        id="expensecomment" 
        rows="2" 
        cols="40" 
        placeholder="eg, Cheque Ref"
        style="padding-left: 3px;"
        ></textarea>
        </fieldset>

        
                    <fieldset> <label class="fieldLabel"> Spent Locally </label> 
<select class="ui-state-default ui-corner-left" id="localexpense">
<option value=""> </option>
<option value="1" > Yes </option>
<option value="0" > No </option>
</select>
</fieldset>
        
        
        
        
        
            <fieldset><label class="fieldLabel">Created </label>
        <span id="expcr"></span>
        </fieldset>        
        
            <fieldset><label class="fieldLabel">Last Updated </label>
        <span id="explastupdated"></span>
        </fieldset>
       

            <input type="hidden" id="newcomment" name="newcomment" value="">
            <hr />
        </div>
    </div>

    <div id="expensestats"> </div>

</div>
    
<script type="text/javascript">
$(document).ready(function() {
    $(function (){ // autosize
        $("#expensecomment").autosize();
    });
    var formbirthday=<?php echo date("U"); ?>;
    var calcvatpc=<?php echo $globalprefrow['vatbandexpense']; ?>;
    
	$(function() { // expensedate datepicker
		var dates = $( "#expensedate" ).datepicker({
			numberOfMonths: 1,
			changeYear:false,
			firstDay: 1,
            dateFormat: 'dd-mm-yy',
			changeMonth:false,
            onSelect: function(d,i){
                if(d !== i.lastVal){
                    $(this).change();
                }
            }
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
         
        var expenseref=$("#expenseid").val();
        if (expenseref) {
            message='';
            $.ajax({
                url: 'ajaxchangejob.php',
                data: {
                    page: 'ajeditexpense',
                    whatchanged: 'expensecost',
                    formbirthday: formbirthday,
                    expenseref: expenseref,
                    expensecost: $("#amount").val()
                },
                type: 'post',
                success:function(data){
                    $("#Post").append(data);
                },
                complete: function () {
                    $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });
                    showmessage();
                },
                error:function (xhr, ajaxOptions, thrownError){
                    alert(thrownError); //throw any errors
                }
            });
        }
        
        
        // update VAT if set
        
        if (calcvatpc) {
            
            var newvat=(($("#amount").val())-($("#amount").val()/(1+(calcvatpc/100)))).toFixed(2);
            
            // alert ("New VAT " + newvat);
            if (newvat =="NaN") {
                alert ("Not a Number");
                $("#expensevat").val("0.00");
            } else {
                $("#expensevat").focus().val(newvat);
            }
        }
    });

    
    
    function expvatsubmit() {
            if ($("#expensevat").val()) {
            var rounded = parseFloat($("#expensevat").val()).toFixed(2); // rounded = 258.20
            // alert(rounded);
            
            if (rounded =="NaN") {
                alert ("Not a Number");
                $("#expensevat").val("0.00");
            } else {
                $("#expensevat").val(rounded);
            }
        } else {
            $("#expensevat").val("0.00");
        }
         
        var expenseref=$("#expenseid").val();
        if (expenseref) {
        message='';
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditexpense',
                whatchanged: 'expensevat',
                formbirthday: formbirthday,
                expenseref: expenseref,
                expensevat: $("#expensevat").val()
            },
            type: 'post',
            success:function(data){
                // alert(data);
                $("#Post").append(data);
            },
            complete: function () {
                showmessage();
                $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });
        }
    }
    

    
    $("#expensevat").blur(function () {
        expvatsubmit();
    });    
    
    
    $("#expensecode").change(function () {
        var expenseref=$("#expenseid").val();
        if (expenseref) {
        message='';
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditexpense',
                whatchanged: 'expensecode',
                formbirthday: formbirthday,
                expenseref: expenseref,
                expensecode: $("#expensecode").val()
            },
            type: 'post',
            success:function(data){
                // alert(data);
                $("#Post").append(data);
            },
            complete: function () {
                $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });
                showmessage();
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });            
        
        }
    });
    
    
    
    $("#whoto").change(function () {
        var expenseref=$("#expenseid").val();
        if (expenseref) {
        message='';
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditexpense',
                whatchanged: 'whoto',
                formbirthday: formbirthday,
                expenseref: expenseref,
                whoto: $("#whoto").val()
            },
            type: 'post',
            success:function(data){
                // alert(data);
                $("#Post").append(data);
            },
            complete: function () {
                showmessage();
                $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });            
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });            
        
        }
    });
    
    
    
    $("#cyclistref").change(function () {
        var expenseref=$("#expenseid").val();
        if (expenseref) {
        message='';
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditexpense',
                whatchanged: 'cyclistref',
                formbirthday: formbirthday,
                expenseref: expenseref,
                cyclistref: $("#cyclistref").val()
            },
            type: 'post',
            success:function(data){
                // alert(data);
                $("#Post").append(data);
            },
            complete: function () {
                showmessage();
                $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });            
        
        }
    });

    $('#expensedate').change(function (){
        var expenseref=$("#expenseid").val();
        var expensedate=$("#expensedate").val();
        if (expensedate) {
        message='';
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditexpense',
                whatchanged: 'expensedate',
                formbirthday: formbirthday,
                expenseref: expenseref,
                expensedate: expensedate
            },
            type: 'post',
            success:function(data){
                // alert(data);
                $("#Post").append(data);
            },
            complete: function () {
                showmessage();
                $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });            
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

    
    $('#expensecomment').change(function (){
        var expenseref=$("#expenseid").val();
        var newcommenttext = $('#expensecomment').val().replace(/\n/g,"<br>");
        $("#newcomment").val(newcommenttext);
        if (expenseref) {
        message='';
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditexpense',
                whatchanged: 'description',
                formbirthday: formbirthday,
                expenseref: expenseref,
                description: $("#newcomment").val()
            },
            type: 'post',
            success:function(data){
                // alert(data);
                $("#Post").append(data);
            },
            complete: function () {
                showmessage();
                $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });            
        }
    });
    




    $("#localexpense").change(function () {
        var expenseref=$("#expenseid").val();
        if (expenseref) {
        message='';
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditexpense',
                whatchanged: 'localexpense',
                formbirthday: formbirthday,
                expenseref: expenseref,
                localexpense: $("#localexpense").val()
            },
            type: 'post',
            success:function(data){
                // alert(data);
                $("#Post").append(data);
                $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });
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



    
    
    $("#paid").change(function () {
        var expenseref=$("#expenseid").val();
        if (expenseref) {
        message='';
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditexpense',
                whatchanged: 'paid',
                formbirthday: formbirthday,
                expenseref: expenseref,
                paid: $("#paid").val()
            },
            type: 'post',
            success:function(data){
                // alert(data);
                $("#Post").append(data);
                $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });
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
     
    
    $("#paymentmethod").change(function () {
        var expenseref=$("#expenseid").val();
        if (expenseref) {
        message='';
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajeditexpense',
                whatchanged: 'paymentmethod',
                formbirthday: formbirthday,
                expenseref: expenseref,
                paymentmethod: $("#paymentmethod").val()
            },
            type: 'post',
            success:function(data){
                // alert(data);
                $("#Post").append(data);
            },
            complete: function () {
                showmessage();
                $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });            
        
        }
    });
      
    
    function getexpense() {
        var notblankcheck=$("#expenseid").val();
        if (notblankcheck) {
            message='';
            var formdata=$('#singleexpense').serializeArray();
            $.ajax({
                type: 'POST',
                url: 'ajax_lookup.php',
                data: {
                    lookuppage: 'individexpense',
                    expenseid: $("#expenseid").val()
                },
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
    }
    
    
    $('#expensesearchbyid').on('click', function (event) {
        event.preventDefault();
        getexpense();
        
    });    

    $("#expenseid").change(function () {
        getexpense();
    });
    

    $('#addnewexpense').on('click', function (event) {
        event.preventDefault();
        message='';
        
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'addnewexpense',
                formbirthday: formbirthday
            },
            type: 'post',
            success:function(data){
                // alert(data);
                $("#Post").append(data);
            },
            complete: function () {
                
                $('#expensecomment').trigger('autosize.resize');
                showmessage();
                $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' });                
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });
    });    

    function downloadJSAtOnload() {
        var notblankcheck=$("#expenseid").val();
        if (notblankcheck) {
            $("#expensesearchbyid").click();
        }

        $( "#expensestats" ).load( "ajax_lookup.php", { lookuppage:'updateexptable' }, function() {});
    }
    if (window.addEventListener)
    window.addEventListener("load", downloadJSAtOnload, false);
    else if (window.attachEvent)
    window.attachEvent("onload", downloadJSAtOnload);
    else window.onload = downloadJSAtOnload;
});


</script>
<?php

include "footer.php";
echo '</body></html>';
 
?>