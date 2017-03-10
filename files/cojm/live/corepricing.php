<?php 
/*
    COJM Courier Online Operations Management
	corepricing.php - Options for changing the checkbox pricing
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

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
error_reporting( E_ERROR | E_WARNING | E_PARSE );
$title = "COJM";
include "C4uconnect.php";
?><!doctype html>
<html lang="en">
<head>
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<link rel="stylesheet" href="css/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>
<script type="text/javascript" src="js/jquery-ui.1.8.7.min.js"></script>
<title><?php print ($title); ?> Core Pricing</title>
<style>	

</style>
</head><body>
<?php 

$hasforms='1';
include "changejob.php";

$settingsmenu='1';
$invoicemenu='0';
$filename='corepricing.php';
include "cojmmenu.php"; 
 // echo 'extras page'; 
 ?>

<div class="Post">

<div class="ui-state-highlight ui-corner-all" style="padding: 1em;"> 
<p>Pricing to exclude any VAT or tax element.  This and other factors are defined within the services.</p>
<p>On calculating the price, the order set here will define which price modifier is processed first.</p>
<p>Drag and Drop each row to change the order.</p>
<p>Whatever order you select to price by, the mileage rates will always show first in the individual job screen.<p>
<p>Value for cost and percentage can be both positive and negative.</p>
<p>Percentages are calculated as 100 being 100% of the cost of the step before, 150 would be price in previous step +50%.</p>
<p>Items with a zero cost will not be displayed (except First and subsequent mileage), use this as a means of disabling in the menus.</p>
<p>Items set to 100% will not change the price, however will be checkable within the menus.</p>
<p>The ASAP and cargobike fields are used to highlight jobs for scheduling purposes.</p>
</div>



<div style="padding: 1em;">	
<table id="cbbsettings" class="acc" style="overflow:auto;"> <!-- overflow is firefox fix for sorting   -->
<thead>
<tr>
<th class="hidden" scope="col"> Order </th>
<th scope="col"> Name </th>
<th scope="col"> Cost or % </th>
<th scope="col"> Added or Multipler % </th>
<th scope="col">ASAP<br /><img style="height:16px;" alt="asap" title="ASAP Logo" src="<?php echo $globalprefrow['image5']; ?>"></th>
<th scope="col">Cargo<br /><img style="height:16px;" alt="cargo" title="Cargobike Logo" src="<?php echo $globalprefrow['image6']; ?>"></th>
<th scope="col">x via addresses</th>
<th scope="col"> Comments </th>
</tr>
</thead>


<tbody>
<?php

$sql = "SELECT * FROM chargedbybuild ORDER BY cbborder ASC";


$idmax=1;
$prep = $dbh->query($sql);
foreach ($prep as $row) {

?>
<tr id="<?php echo $row['chargedbybuildid']; ?>">
<td class="hidden" >
<input data-id="<?php echo $row['chargedbybuildid']; ?>" data-type="cbborder" class="priority ui-state-default ui-corner-all pad" 
type="text" size="3" maxlength="4" value="<?php echo $cbborder; ?>">
</td>

<td>
<input data-id="<?php echo $row['chargedbybuildid']; ?>" data-type="cbbname" class="ui-state-default ui-corner-all pad" 
type="text" size="30" maxlength="30" value="<?php echo $cbbname; ?>">
</td>

<td>
<span id="plus<?php echo $row['chargedbybuildid']; ?>" class="mod"><?php if ( $cbbmod=='+') { echo " &".$globalprefrow['currencysymbol']; } ?> &nbsp; </span>

<input data-id="<?php echo $row['chargedbybuildid']; ?>" data-type="cbbcost" class="ui-state-default ui-corner-all pad" 
type="text" size="7" maxlength="8" value="<?php echo $row['cbbcost']; ?>">
<span id="times<?php echo $row['chargedbybuildid']; ?>" class="mod"><?php if ( $cbbmod=='x') { echo " % "; } ?> &nbsp; </span>
</td>

<td>
<select data-id="<?php echo $chargedbybuildid; ?>" data-type="cbbmod" class="ui-state-default ui-corner-left" >
<option 

<?php if ( $row['cbbmod']=='+') { echo  ' SELECTED '; } ?>
 value="+">Added to total</option>
<option 
<?php if ( $row['cbbmod']=='x') { echo  ' SELECTED '; } ?>
value="x">Multiplied as percentage</option>
</select></td>

<td> <input data-id="<?php echo $chargedbybuildid; ?>" data-type="cbbasap" type="checkbox" value="0" 
<?php if ($row['cbbasap']>0) { echo ' checked'; } ?> > 
</td>

<td> <input data-id="<?php echo $chargedbybuildid; ?>" data-type="cbbcargo" type="checkbox" value="0" 
<?php if ($row['cbbcargo']>0) { echo 'checked';} ?> > 
</td>

<td> <input data-id="<?php echo $chargedbybuildid; ?>" data-type="cbbmultivia" type="checkbox" value="0" 
<?php if ($row['cbbmultivia']>0) { echo 'checked';} ?> > 
</td>




<td>
<input data-id="<?php echo $row['chargedbybuildid']; ?>" data-type="cbbcomment" type="text" class="ui-state-default ui-corner-all pad" 
size="65" maxlength="100" value="<?php echo $row['cbbcomment']; ?>">

</td>
</tr>

<?php
$idmax++; 
}
?>

</tbody>
</table>

<?php  if ($idmax<21)  { ?>
<button id="newrow">New</button>
<?php } ?>
</div>
<div class="line"></div>
<br />
</div>
<script>

var formbirthday=<?php echo microtime(TRUE); ?>; 


$(document).ready(function() {
	
    //Helper function to keep table row from collapsing when being sorted
    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index)
        {
          $(this).width($originals.eq(index).width())
        });
        return $helper;
    };

    //Make diagnosis table sortable
    $("#cbbsettings tbody").sortable({
        helper: fixHelperModified,
        stop: function(event,ui) {renumber_table('#cbbsettings')}
    });

});

var orderstring='';

//Renumber table rows
function renumber_table(tableID) {
    $(tableID + " tr").each(function() {
        count = $(this).parent().children().index($(this)) + 1;
        $(this).find('.priority').html(count);
		
   rowid = '' + $(this).closest('tr').attr("id");
   
   if (rowid>0) {	orderstring=orderstring + rowid + ',' + count + ';'; }
		
    });
	 orderstring = btoa(orderstring); // base 64 encodes so can be transmitted ok

	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxeditglobals',
		formbirthday:formbirthday,
		globalname:'cbborder',
		newvalue:orderstring},
		type:'post',
        success: function(data) {
$('.Post').append(data);
	},
		complete: function(data) {
		showmessage();
		}
});

}





$(document).ready(function() {
	

var idmax=<?php echo $idmax; ?>;
var chargedbybuildid;
var testtype;
var newvalue;
var checked;

$("#newrow").click(function(){

if (idmax>19) { $("#newrow").hide(); }

var newtr=	'<tr id="' + idmax + '"><td class="hidden" ><input data-id="' + idmax +
 '" data-type="cbborder" type="text" ' +
 ' value="' + idmax + '"></td><td><input data-id="' + 
 idmax + '" data-type="cbbname" class="newrow ui-state-default ui-corner-all pad" type="text" size="30" maxlength="30" ' +
 ' ></td><td>' +
 ' <span id="plus' + idmax +'" class="mod"> <?php  echo " &".$globalprefrow['currencysymbol'];?> </span> ' + 
 ' <input data-id="' + idmax + '" data-type="cbbcost" class="newrow ui-state-default ui-corner-all pad" ' +
 ' type="text" size="7" maxlength="8" value="0"> <span id="times' + idmax +'" class="mod"> </span> </td>' +
 ' <td><select data-id="' + idmax + '" data-type="cbbmod" ' +
 ' class="newrow ui-state-default ui-corner-left" ><option value="+">Added to total</option>' +
 ' <option value="x">Multiplied as percentage</option>'+
 ' </select></td><td> <input class="newrow " data-id="' + idmax + '" data-type="cbbasap" type="checkbox" value="0" ></td>' +
 ' <td> <input class="newrow " data-id="' + idmax + '" data-type="cbbcargo" type="checkbox" value="0" > </td>' +
 ' <td> <input class="newrow " data-id="' + idmax + '" data-type="cbbmultivia" type="checkbox" value="0" > </td>' + 
 ' <td><input data-id="' + idmax + '" data-type="cbbcomment" type="text" class="newrow ui-state-default ui-corner-all pad" ' +
 ' size="65" maxlength="100" ></td></tr>';


//		alert(newtr);
		$('#cbbsettings > tbody:last-child').append(newtr);
 
	
		    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxeditglobals',
		formbirthday:formbirthday,
		globalname:'cbbsettings',
		testtype:'newrow',
		chargedbybuildid:idmax},
		type:'post',
        success: function(data) {
$('.Post').append(data);
	},
		complete: function(data) {
		showmessage();
			idmax=idmax+1;
		$($(".newrow")).change(function(e) {

 chargedbybuildid=$(this).data('id');
 testtype=$(this).data('type');
 newvalue=$(this).val();


if($(this).prop('checked')) { // something when checked
checked=1;
} else { // something else when not
checked=0;
}

senddata();	
	
});	
		}
});
	});
	
	
	
	
	
$('#cbbsettings input, #cbbsettings select').change(function(e) {

 chargedbybuildid=$(this).data('id');
 testtype=$(this).data('type');
 newvalue=$(this).val();


if($(this).prop('checked')) { // something when checked
checked=1;
} else { // something else when not
checked=0;
}

senddata();	
	
});

function senddata() {	
	

//		alert(chargedbybuildid + ' ' + testtype + ' ' + newvalue + ' ' + checked);


if ( testtype=='cbbmod' ) {  
// alert("selector"); 

if ( newvalue=='+') {
$("#plus"+chargedbybuildid).html('<?php echo " &".$globalprefrow['currencysymbol']; ?>'); $("#times"+chargedbybuildid).html('');
}


else { 

$("#plus"+chargedbybuildid).html(''); $("#times"+chargedbybuildid).html(' % ');


}

}

		 newvalue = btoa(newvalue); // base 64 encodes so can be transmitted ok
	    $.ajax({
        url: 'ajaxchangejob.php',  //Server script to process data
		data: {
		page:'ajaxeditglobals',
		formbirthday:formbirthday,
		globalname:'cbbsettings',
		checked:checked,
		testtype:testtype,
		chargedbybuildid:chargedbybuildid,
		newvalue:newvalue},
		type:'post',
        success: function(data) {
$('.Post').append(data);
	},
		complete: function(data) {
		showmessage();
		}
});
   }
 

});  // ends page ready



</script>

<?php 

include 'footer.php';

?>
</body></html>