<?php 

/*
    COJM Courier Online Operations Management
	cojmaudit.php - Shows audit log via ajax
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

error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";

$inputstart='';
$inputend='';

include "changejob.php";

if (isset($_GET['orderid'])) {
    $orderid=trim($_GET['orderid']);
    } else {
    $orderid='';
    $inputstart=date("j/n/Y");
    $inputend=date("j/n/Y");
}

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
<div class="ui-widget ui-state-highlight ui-corner-all" style="padding: 0.5em; width:auto;">
<p>Actions From 
<input id="rangeBa" form="form" class="ui-state-highlight ui-corner-all pad" size="10" type="text" name="from" value="<?php echo $inputstart; ?>" />

To
<input id="rangeBb" form="form" class="ui-state-highlight ui-corner-all pad"  size="10" type="text" name="to" value="<?php echo $inputend; ?>"  />

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
<input type="checkbox" name="showtimes" class="showtimes" value="1"  > Times
<input type="checkbox" name="showdebug" class="showdebug" value="1"  > Debug Text
<input type="checkbox" name="showpageviews" id="showpageviews" value="1"  > Include Views

</p>
</div>
</form>

<div class="vpad"></div>
<div id="content"></div>
<br /></div>
<script>

$(document).ready(function() {
	
    var auditpage="cojmaudit";
    var showtimes=0;
    var showpageviews=0; 
    var showdebug=0;
    var from;
    var to;
    var client;
    var auditpage;
    var dataString;
    var loadingtext=" <div class='loadingsuccess'>  Loading Results </div>"; 
    var orderid<?php if ($orderid>0) { echo ' ='.$orderid; } ?>;

    function refreshpage() {

        $("#toploader").show();
        $("#content").html(loadingtext);
        
        showdebug = $('input.showdebug:checked').map(function() {
            return this.value;
        }).get()

        showtimes = $('input.showtimes:checked').map(function() {
            return this.value;
        }).get()
        
        showpageviews = $('input#showpageviews:checked').map(function() {
            return this.value;
        }).get()

        from = $("#rangeBa").val();
        to = $("#rangeBb").val();
        client = $("#page").val();
        orderid = $("#orderid").val();
        
        dataString = "auditpage=" + auditpage + 
        "&from=" + from + 
        "&to=" + to + 
        "&page=" + client + 
        "&orderid=" + orderid + 
        "&showtimes=" + showtimes + 
        "&showdebug=" + showdebug +
        "&showpageviews=" + showpageviews +
        "&lookuppage=cojmaudit";

        $.ajax({
            type: "POST",
            url:"ajax_lookup.php",
            data: dataString,
            success: function (data){
                $("#content").html(data)
            },
            complete: function () {
                $("#toploader").hide();
            }
        });

}

<?php
if (isset($_GET['orderid'])) { // check for 1st time form submittal
    echo '	
    $("#toploader").show();		
    $("#content").html(loadingtext);
    setTimeout( function() {
        refreshpage();
    }, 50 );';
}
?>

	$("#rangeBa, #rangeBb").daterangepicker( {
        onClose: function(){
            refreshpage();
        }
    });

    $("#form").change(function() {
        refreshpage();
    });
});
</script>
<?php

include 'footer.php';

echo '</body></html>';