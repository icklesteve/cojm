<?php 
/*
    COJM Courier Online Operations Management
	index.php - main job schedule
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

if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include "changejob.php";
 
echo '<!DOCTYPE html> <html lang="en"> <head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, height=device-height" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<META HTTP-EQUIV="Refresh" CONTENT="'. $globalprefrow['formtimeout'].'; URL=index.php"> 
<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
<title>COJM : '. ($cyclistid).'</title>
<style>
#toploader { display:block; }
</style>
</head>
<body id="bodytop" >';

$hasforms='1';
$filename="index.php";
include "cojmmenu.php"; 


if($mobdevice) {
        $numberofresults=$globalprefrow['numjobsm'];
    } else {
        $numberofresults=$globalprefrow['numjobs'];
    }
if ($page == "showall" ) { $numberofresults='1000'; }

?>

<div id="Post" class="Post c9 lh16">
<div id="indexajax"> </div>  
</div>

<script type="text/javascript">
  
var formbirthday=<?php echo microtime(TRUE); ?>;
var numberofresults=<?php echo $numberofresults; ?>;

    function refreshindex() {
      
        dataString = "lookuppage=indexlist" +  
        "&numberofresults=" + numberofresults +
        "&showall=";

        $.ajax({
            type: "POST",
            url:"ajax_lookup.php",
            data: dataString,
            success: function (data){
                $("#indexajax").html(data)
            },
            complete: function () {
                $("#toploader").fadeOut();
                
                $(".indexstatus").bind("change", function (e) {
                    var id = e.target.id;
                    orderid = parseInt(id.match(/(\d+)$/)[0], 10);
                    $("#toploader").show();
                    var newstatus=$("#"+id).val();
                    $.ajax({
                        url: 'ajaxchangejob.php',
                        data: {
                            page: 'ajaxorderstatus',
                            formbirthday: formbirthday,
                            id: orderid,
                            newstatus: newstatus
                        },
                        type: 'post',
                        success: function (data) {
                            $('#Post').prepend(data);
                        },
                        complete: function () {
                            refreshindex();
                            showmessage();
                            $("#toploader").fadeOut();
                        }
                    });
                }); 
      
      
                $(".indexrider").bind("change", function (e) {
                    var id = e.target.id;
                    orderid = parseInt(id.match(/(\d+)$/)[0], 10);
                    $("#toploader").show();
                    var newrider=$("#"+id).val();
                    $.ajax({
                        url: 'ajaxchangejob.php',
                        data: {
                            page: 'ajaxchangerider',
                            formbirthday: formbirthday,
                            id: orderid,
                            newrider: newrider
                        },
                        type: 'post',
                        success: function (data) {
                            $('#Post').prepend(data);
                        },
                        complete: function () {
                            showmessage();
                            $("#toploader").fadeOut();
                        }
                    });
                });  

            }
        });
      
      }


    refreshindex();

</script>

<?php include "footer.php"; 
echo ' </body></html> ';
   