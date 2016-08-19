<?php 

$alpha_time = microtime(TRUE);

error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";



$inputstart='';
$inputend='';

include "changejob.php";

if (isset($_GET['orderid'])) { $orderid=trim($_GET['orderid']); } else { 
$orderid=''; 

$inputstart=date("j/n/Y");
$inputend=date("j/n/Y");


}

// include "changejob.php";

$adminmenu = "1";
$title='COJM Audit';

?><!DOCTYPE html> 
<html lang="en"> 
<head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<?php
echo '
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>

<script>
var showtimes=0;
</script>
';

?>
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<title><?php print ($title); ?></title>
</head>
<body>
<? 
$settingsmenu='1';
$invoicemenu='0';
$filename="cojmaudit.php";
include "cojmmenu.php"; 

?>
<div class="Post">
<form id="form" action="#" method="post"> 
<div class="ui-widget ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;"><p>

Actions From 
<input form="form" class="ui-state-highlight ui-corner-all pad" size="10" type="text" name="from" value="<?php echo $inputstart; ?>" id="rangeBa" />			
To		
<input form="form" class="ui-state-highlight ui-corner-all pad"  size="10" type="text" name="to" value="<?php echo $inputend; ?>" id="rangeBb" />


<select id="page" name="page" class="ui-state-highlight ui-corner-left">
<option value=""> All Actions</option>
<option value="cojmcron.php">Backups ( automated )</option>
<option value="editclient">Client ( Edit )</option>
<option value="markinvpaid">Invoice Paid</option>
<option value="editinvcomment">Invoice Comment Edited</option>
<option value="orderaddpod">POD Added</option>
<option value="ajaxremovepod">POD Removed</option>
</select>

<input class="ui-state-highlight ui-corner-all pad" id="orderid" name="orderid" placeholder="Search by job ref" value="<?php echo $orderid; ?>" />
<input type="checkbox" name="showtimes" class="showtimes" value="1"  > Show Page Creation Times
<input type="checkbox" name="showdebug" class="showdebug" value="1"  > Show Debug Text

</p></div></form>

<div class="vpad"></div>
<div id="content"></div>
<br /></div>
<script type="text/javascript">
$(document).ready(function() {
	
var loadingtext=" <div class='loadingsuccess'>  Loading Results </div>"; 

<?php

if (isset($_GET['orderid'])) { // 

echo '	
$("#spinner").show();		
$("#content").html(loadingtext);

 setTimeout( function() {
 var from = $("#rangeBa").val();
var to = $("#rangeBb").val();
var client = $("#page").val();
var auditpage="cojmaudit";
var orderid = '.$_GET['orderid'].';
var dataString = "auditpage=" + auditpage + "&from=" + from + "&to=" + to + "&page=" + client + "&orderid=" + orderid +"&showtimes=" + showtimes;
$.ajax({
    type: "POST",
    url:"ajaxaudit.php",
    data: dataString,
    success: function(data){
	$("#content").html(data)
//    alert(loadingtext); //only for testing purposes

$("#spinner").hide();
    }
});
	
	}, 50 );
';


} // ends check for 1st time form submittal

// both posts are needed as not picking up on date changes

?>


	    $("#rangeBa, #rangeBb").daterangepicker( {
		onClose: function(){
			$("#spinner").show();
			
$("#content").html(loadingtext);


     var showdebug = $('input.showdebug:checked').map(function(){
             return this.value;
        }).get()

		     var showtimes = $('input.showtimes:checked').map(function(){
             return this.value;
        }).get()
		
		

	//    alert(" test 110 " ); //only for testing purposes
var from = $("#rangeBa").val();
var to = $("#rangeBb").val();
var client = $("#page").val();
var auditpage="cojmaudit";
var orderid = $("#orderid").val();
var showtimes= $("#showtimes").val();



var dataString = "auditpage=" + auditpage + "&from=" + from + "&to=" + to + "&page=" + client + "&orderid=" + orderid + "&showtimes=" + showtimes + "&showdebug=" + showdebug ;

$.ajax({
    type: "POST",
    url:"ajaxaudit.php",
    data: dataString,
    success: function(data){
	$("#content").html(data)
	$("#spinner").hide();
 //   alert(data); //only for testing purposes
    }
});
 }
})


$("#form").change(function() {
	
	
$("#spinner").show();	
$("#content").html(loadingtext);
			
			
	
	

     var showdebug = $('input.showdebug:checked').map(function(){
             return this.value;
        }).get()
		
		
			     var showtimes = $('input.showtimes:checked').map(function(){
             return this.value;
        }).get()

	

	
var from = $("#rangeBa").val();
var to = $("#rangeBb").val();
var client = $("#page").val();
var auditpage="cojmaudit";

var orderid = $("#orderid").val();
var dataString = "auditpage=" + auditpage + "&from=" + from + "&to=" + to + "&page=" + client + "&orderid=" + orderid + "&showtimes=" + showtimes + "&showdebug=" + showdebug ;
$.ajax({
    type: "POST",
    url:"ajaxaudit.php",
    data: dataString,
    success: function(data){
	$("#content").html(data)
 //   alert(data); //only for testing purposes
$("#spinner").hide(); 
 }
});
});
});
</script>
<?php

include 'footer.php';
mysql_close();  
echo '</body></html>';