<?php 
/*
    COJM Courier Online Operations Management
	cojmmenu.php - Main Menu which inserts at top each page
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



$agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/iPhone|Android|Blackberry/i', $agent)) {  $mobdevice="1";} else { $mobdevice=''; }
if (isset($hasforms)) { $hasforms='1'; } else { $hasforms=''; }
if ((isset($adminmenu)) and ($adminmenu=='1')) { $adminmenu='1'; } else { $adminmenu=''; }		
if ((isset($settingsmenu)) and ($settingsmenu=='1')) { $settingsmenu='1'; } else { $settingsmenu=''; }		
if ((isset($invoicemenu)) and ($invoicemenu=='1')) { $invoicemenu='1'; } else { $invoicemenu=''; }	



// pt delay - page text display length
// pt slide - how long it slides for


echo '
<script>
var ptdelay=3000;
var ptslide=500;
var alertdelay=8000;
var alertslide=1000;
var allok=1;
var message="";
</script>
';


// set a longer timesout if in debug mode
if ($globalprefrow['showdebug']=='1') { 
echo '
<script>
ptdelay=10000;
alertdelay=15000;
</script>
';
}



echo ' <div id="infotext" class="infotext" >';
echo '<span class="ctitle">C</span><span class="ctitle">O</span><span class="ctitle">J</span><span class="ctitle">M</span>';

// echo '<div id="loggedinas">Logged in as '.$cyclistid.'</div>';


if (isset($mobdevice) and ($mobdevice=='1') )  { if ($globalprefrow['showdebug']>'0') { 
$pagetext=$pagetext.$infotext; 

} } else { $mobdevice='';
 }
if (($globalprefrow['showdebug']>'0')) { 
echo 'DEBUG MODE';

echo $infotext; }



if ($globalprefrow['showdebug']>'0') { echo '<div class="activestatus" id="activestatus"></div>'; }



echo '</div>'; //ends infotext div








// echo '<div id="demo_top_wrapper"> ';
// echo '<div id="sticky_navigation_wrapper">';
echo '
<div class="sticky_navigation demo_top_wrapper" id="sticky_navigation_wrapper">

<ul><li><a href="index.php"'; 
if ($filename=='index.php') echo ' class="selected"'; echo ' >Home</a></li>';
if ($filename<>'whereis3.php') { echo '<li><div id="togglenewjobchoose" ><a href="#">New </a></div></li>'; }
if (($globalprefrow['inaccuratepostcode'])=='0') { echo '<li><a href="whereis3.php"'; 
if ($filename=='whereis3.php') { echo ' class="selected"';}  echo '>Map</a></li>'; }
echo '<li><a href="clientviewtargetcollection.php"'; if ($filename=='clientviewtargetcollection.php') { echo ' class="selected"'; } 
echo '>Date Search</a></li>
<li><a href="fwr.php"'; if ($filename=='fwr.php') { echo ' class="selected"'; } echo '>Admin</a></li>
<li><form action="order.php" id="menusearch" method="get" >
<input class="menuorder ui-state-default ui-corner-all" placeholder="Search" ';


?>
	data-autosize-input='{ "space": 14 }' 
<?php


echo ' name="id" ';

if ($id) { echo 'type="number" step="1"'; } else { echo ' type="text" '; }


echo ' value="'.$id.'">';
if (($filename=="order.php") or ($mobdevice=='1')) { 

echo '<button class="menusearch" form="menusearch" type="submit" > <img class="menusearch" src="images/refresh.png" alt="Refresh" /></button>';

} 


echo ' </form></li>';

if ($hasforms=='1'){echo '<li><span id="cdtext"> </span></li><li><b class="hidden" id="show-time" >'. $globalprefrow['formtimeout'].'</b></li>';}



echo '</ul>';

	
echo '</div>';



		
if (isset($pagetext)) { $pagetext=$pagetext; } else {$pagetext='';} 


echo '<div class="pagetext success" id="pagetext" ';
if (trim($pagetext)) { } else { echo ' style="display:none;" '; } echo ' >'.$pagetext.'</div>'; 

echo'<div id="alerttext" class="error ui-corner-all alerttext" ';
if ((isset($alerttext)) and ($alerttext)) { } else { echo ' style="display:none;" '; } echo ' >'. $alerttext.' </div> ';






if ((isset($adminmenu)) and ($adminmenu=='1')) { 
echo '<div class="demo_top_wrapper"><div class="sticky_navigation"><ul>';
echo '<li><div id="toggleinvoicemenuchoose" ><a href="#">Finance</a></div></li><li><a href="new_cojm_client.php"'; 
if ($filename=='new_cojm_client.php') { echo ' class="selected"'; } echo '>Client</a></li>';
echo '<li><a href="cyclist.php"'; if ($filename=='cyclist.php') { echo ' class="selected"';} 
echo '> '.$globalprefrow['glob5'].'</a></li>';

echo '<li><a href="opsmap.php"'; if ($filename=='opsmap.php') { echo ' class="selected"';} 

echo '> Ops Map</a></li><li><a href="favusr.php"'; if ($filename=='favusr.php') { echo' class="selected"'; } echo '>Favourites</a></li>
<li><a href="recentlyclosed.php"'; 
if ($filename=='recentlyclosed.php') { echo ' class="selected"';} echo '>Last 100</a></li>
<li><a href="startuploadgpx.php"'; if ($filename=='startuploadgpx.php') echo ' class="selected"'; echo '>GPS</a></li>
<li><a href="dashboard.php"'; if ($filename=='dashboard.php') echo ' class="selected"'; echo '>Stats</a></li>
<li><a href="help.php"'; if ($filename=='help.php') { echo ' class="selected"'; } echo '>Help</a></li>';
 if ($settingsmenu<>'1') { echo '<li><div id="togglesettingsmenuchoose" ><a href="#" >Settings</a></div></li>'; }
echo '</ul></div></div>'; 
}



if (($settingsmenu<>'1') and ($invoicemenu<>'1') and ($adminmenu<>'1')) {} else {
echo '<div '; 
if ($invoicemenu<>'1') { echo 'id="toggleinvoicemenu" '; }
echo ' class="demo_top_wrapper ';
if ($invoicemenu<>'1') { echo 'toggleinvoicemenu '; }


echo ' ">
<div class="sticky_navigation"><ul>';
echo '<li><a style="float:left;" href="view_all_invoices.php"'; if ($filename=='view_all_invoices.php') echo ' class="selected"'; echo '>Invoicing</a></li>';
echo '<li><a style="float:left;" href="pdfview.php"'; if ($filename=='pdfview.php') echo ' class="selected"'; echo '>New Invoice</a></li>';
echo '<li><a style="float:left;" href="expenseview.php"'; if ($filename=='expenseview.php') echo ' class="selected"'; echo '> Expenses</a></li>';
echo '<li><a style="float:left;" href="expenses.php?page=createnew"'; if ($filename=='expenses.php') echo ' class="selected"'; echo '>New Expense</a></li>';
echo '<li><a style="float:left;" href="pandl.php"'; if ($filename=='pandl.php') echo ' class="selected"'; echo '>P+L</a></li> 
</ul></div></div>';
} 
 
 
 
if (($settingsmenu<>'1') and ($invoicemenu<>'1') and ($adminmenu<>'1')) {} else {

echo '<div '; 

if ($settingsmenu<>'1') { echo 'id="togglesettingsmenu" '; }


echo ' class="demo_top_wrapper ';

if ($settingsmenu<>'1') { echo ' togglesettingsmenu '; }


echo ' "><div class="sticky_navigation_wrapper"><div class="sticky_navigation"><ul> ';
echo '<li><a href="cojmglobal.php"'; if ($filename=='cojmglobal.php') echo ' class="selected"'; echo '>Main Settings</a></li>';
echo '<li><a href="service.php"'; if ($filename=='service.php') { echo ' class="selected"'; } echo '>Services</a></li>';
echo '<li><a href="corepricing.php"'; if ($filename=='corepricing.php') echo ' class="selected"'; echo '>Distance Pricing</a></li>';
echo '<li><a href="cojmglobalemail.php"'; if ($filename=='cojmglobalemail.php') echo ' class="selected"'; echo '>Set Email</a></li>';
echo '<li><a href="cojmglobalstatus.php"'; if ($filename=='cojmglobalstatus.php') echo ' class="selected"'; echo '>Status Text</a></li>';
if ($globalprefrow['showdebug']>'0') { 
echo '<li><a href="debug.php"'; if ($filename=='debug.php') echo ' class="selected"'; echo '>Debug</a></li>'; }  
if (($globalprefrow['inaccuratepostcode'])=='0') {
echo '<li><a href="newpc.php"'; if ($filename=='newpc.php') echo ' class="selected"'; echo '>Add Postcode</a></li>'; }
//  <a href="editbankhol.php">Bank Hols</a> 

echo '<li><a href="backupinfo.php"'; if ($filename=='backupinfo.php') echo ' class="selected"'; echo '>Backups</a></li>';
echo '<li><a href="cojmaudit.php"'; if ($filename=='cojmaudit.php') echo ' class="selected"'; echo '>System Log</a></li>';

echo '</ul></div></div></div>'; }



echo '<div id="togglenewjob" class="newjobfrommenu ui-widget spaceout ui-state-highlight ui-corner-all innerpad" >';
$query = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient>0 ORDER BY CompanyName"; 
$result_id = mysql_query ($query, $conn_id); $CustomerID='';
echo '<form action="order.php" method="post" id="newjob_form" accept-charset="utf-8"><input type="hidden" name="formbirthday" value="'. date("U").'" />
<input type="hidden" name="page" value="newjobfromajax" />
<div class="fsl"> Client </div><div class="left">
<select class="caps ui-state-default ui-corner-all" id="newjobselectclient" name="newjobselectclient" ><option value="">Select one...</option>';
 while (list ($CustomerIDlist, $CompanyName) = mysql_fetch_row ($result_id)) { $CompanyName = htmlspecialchars ($CompanyName); 
 print'<option value="'.$CustomerIDlist.'">'.$CompanyName.'</option>';} echo '</select> </div>  <div id="afterclientselect" class="left">
<a href="#" title="Add Inactive Clients" class="showinactiveclient" id="showinactiveclient"> </a> </div> 
 <div id="status"></div> <div id="depstatus"></div> </form> </div>';

// echo '<div id="loggedinas">Logged in as '.$cyclistid.'</div>';



// if ($globalprefrow['showdebug']>'0') { echo '<br /> DEBUG MODE'; }

