<?php

include "C4uconnect.php";

if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  exit(); } }
if (isset($_GET['addr'])) { $addr=(trim($_GET['addr'])); }
if (isset($_GET['clientid'])) { $clientid=(trim($_GET['clientid'])); }
if (isset($_GET['jobid'])) { $jobid=(trim($_GET['jobid'])); }

$agent = $_SERVER['HTTP_USER_AGENT']; 
if(preg_match('/iPhone|Android|Blackberry/i', $agent)) { $mobdevice='1'; } else { $mobdevice=''; }






$sql="SELECT * FROM cojm_favadr WHERE  favadrclient = '$clientid' AND favadrisactive ='1' ";
$sql_result = mysql_query($sql,$conn_id);
echo '<form action="order.php#" method="post" id="selectfav">
<input type="hidden" name="formbirthday" value="'.date("U").'">
<input type="hidden" name="page" value="addfavtoorder">
<input type="hidden" name="addr" value="'.$addr.'">
<input type="hidden" name="id" value="'.$jobid. '">
<select  style="width: 150px;" name="selectfavbox"  id="selectfavbox" >
<option value=""> Select One ...</option>';
while ($favrow = mysql_fetch_array($sql_result)) { extract($favrow);
echo '<option value="'.$favrow['favadrid'].'">'.$favrow['favadrft'].' '.$favrow['favadrpc'].'</option>';
} // ends extract row
echo '</select> 
<button style="top:-6px;" name="showallfavo" onclick="return false" title="All Favourites" type="button" id="showallfavo" class="showallfav" > </button>
</form>
<script>
	$(function() {
		
		
		';

if ($mobdevice<>1) { echo '
		
		$( "#selectfavbox" ).selectfavbox();
		
		
		'; }

echo '
		
		$( "#selectfavbox").focus();
		$( "#toggle" ).click(function() {
		$( "#selectfavbox" ).toggle();	});	});	
	$(document).ready(function(){ setTimeout( function() {	$("#favbutton").click() }, 150 ); 
	$(document).on("click", "#showallfavo",	function (event) {
		 var toAppendto = "";
        event.preventDefault();
		event.stopPropagation(); 
        $("#showallfavo").addClass("loader");
        $.ajax({
            type: "GET",
            url: "ajaxallfav.php",
            async: false,
            dataType: "json",
            success: function (data) {
                $.each(data, function () {
                    toAppendto += "<option value=" + this.oV + ">" + this.oD + "</option>";
            });
				     return false;
            }
	        });
		$("#selectfavbox").val("");	
        $("#selectfavbox").append(toAppendto);
	 setTimeout( function() {	$("#favbutton").click()
	$("#showallfavo").remove();
	 }, 150 ); 
		   return false;
		    event.preventDefault(); 
    });
	});	
</script>';