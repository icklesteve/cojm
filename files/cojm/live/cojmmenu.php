<?php 
/*
    COJM Courier Online Operations Management
	cojmmenu.php - Main Menu which inserts at top each page
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


if(preg_match('/iPhone|Android|Blackberry/i', $_SERVER['HTTP_USER_AGENT'])) {  $mobdevice="1";} else { $mobdevice='0'; }
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
var showdebug='.$globalprefrow['showdebug'].';
var mobdevice='.$mobdevice.'; ';

if ($hasforms) {
    echo 'var pagetimeout = '.$globalprefrow['formtimeout'].'; var initialpagetimeout = '.$globalprefrow['formtimeout'].'; ';
} else {
    echo 'var pagetimeout = 0; initialpagetimeout =0; ';
}



// set a longer timeout if in debug mode
if ($globalprefrow['showdebug']=='1') { 
    echo ' ptdelay=12000; alertdelay=15000; ';
}

echo ' </script> ';



echo ' <div id="infotext" class="infotext" >';
echo '<span class="ctitle">C</span><span class="ctitle">O</span><span class="ctitle">J</span><span class="ctitle">M</span>';

if (($mobdevice=='1') and ($globalprefrow['showdebug']>'0')) {
    $pagetext.=$infotext;
}

if ($globalprefrow['showdebug']>'0') {
    echo '<div id="activestatus">DEBUG MODE<br />'.$infotext.'</div>';
}
echo '</div>'; //ends infotext div


// echo '<div id="loggedinas">Logged in as '.$cyclistid.'</div>';



echo '<div class="top_menu_line clearfix';


echo '" id="sticky_navigation">
<ul>
<li><a href="index.php"'; 
if ($filename=='index.php') { echo ' class="selected"'; } 
echo ' >Home</a></li>';
echo '<li><a title="New Job. If open, re-click to hide" id="togglenewjobchoose" href="#">New</a></li>'; 
echo '<li><a href="whereis3.php"'; 
if ($filename=='whereis3.php') { echo ' class="selected"';}  
echo '>Map</a></li>'; 

echo '
<li id="adminlink"><a href="fwr.php"'; 
if ($filename=='fwr.php') { echo ' class="selected"'; } 
echo '>Admin</a></li>
<li><form action="order.php" id="menusearch" method="get" >
<input class="ui-corner-all topbox" id="menusearchinput" placeholder="Search" '; ?>data-autosize-input='{ "space": 14 }'<?php 
echo ' name="id" ';
if ($id) { echo 'type="number" step="1"'; } else { echo ' type="text" '; }
echo ' value="'.$id.'">';







if ($filename=="order.php") { echo '<button class="menusearch topbox" form="menusearch" type="submit" title="Refresh"></button> '; }
echo ' </form></li>';



echo '<li><a id="menuds" href="clientviewtargetcollection.php" class="';
if ($filename=='clientviewtargetcollection.php') { echo 'selected'; } else { echo 'hideuntilneeded'; }
echo '">Date Search</a></li>';





if ($filename=='opsmap.php') { echo '<li><a href="opsmap.php" class="selected"> Ops Map</a></li>'; }
if ($filename=="gpstracking.php") { echo '<li><a href="startuploadgpx.php" class="selected" >GPS</a></li>'; }




if (isset($menuhtml)) {  
    echo '<li>'.$menuhtml.'</li>';
}


if ($hasforms=='1'){
    echo '<li>  <span id="cdtext" class="hideuntilneeded"> Timing Out</span>  </li>
    ';
}

echo '</ul>';

echo '<div id="toploader" class="clearfix"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>';

echo '</div>'; // id sticky navigation

if (isset($pagetext)) { $pagetext=$pagetext; } else {$pagetext='';} 


echo '<div class="pagetext success" id="pagetext" ';
if (trim($pagetext)) { } else { echo ' style="display:none;" '; } echo ' >'.$pagetext.'</div>'; 

echo'<div id="alerttext" class="error ui-corner-all" ';
if ((isset($alerttext)) and ($alerttext)) { } else { echo ' style="display:none;" '; } echo ' >'. $alerttext.' </div> ';


if ((isset($adminmenu)) and ($adminmenu=='1')) {
    echo '<div class="top_menu_line clearfix"><ul>';
    echo '  <li><a id="toggleinvoicemenuchoose" href="#">Finance ▼</a></li>
            <li><a href="new_cojm_client.php"'; 
    if ($filename=='new_cojm_client.php') { echo ' class="selected"'; } 
    echo '>Client</a></li>';
    
    echo '<li><a href="cyclist.php"'; if ($filename=='cyclist.php') { echo ' class="selected"';} 
    echo '> '.$globalprefrow['glob5'].'</a></li>';

    echo '<li><a href="opsmap.php"';
    if ($filename=='opsmap.php') { echo ' class="selected"';} 

    echo '> Ops Map</a></li>';

    echo '<li><a href="favusr.php"'; 
    if ($filename=='favusr.php') { echo' class="selected"'; } 
    echo '>Favourites</a></li>
            <li><a href="recentlyclosed.php"'; 
    if ($filename=='recentlyclosed.php') { echo ' class="selected"';} 
    echo '>Last 100</a></li>
    <li><a href="startuploadgpx.php"'; 
    if ($filename=='startuploadgpx.php') { echo ' class="selected"'; } 
    echo '>GPS</a></li>
    <li><a href="dashboard.php"'; 
    if ($filename=='dashboard.php') { echo ' class="selected"'; }
    echo '>Stats</a></li>
    <li><a href="help.php"'; 
    if ($filename=='help.php') { echo ' class="selected"'; } 
    echo '>Help</a></li>';
    if ($settingsmenu<>'1') { echo '<li><a href="#" id="togglesettingsmenuchoose" >Settings ▼</a></li>'; }
    echo '</ul>';
    echo '</div>'; 
}



if (($settingsmenu<>'1') and ($invoicemenu<>'1') and ($adminmenu<>'1')) {} else {
    echo '<div '; 
    if ($invoicemenu<>'1') { echo 'id="toggleinvoicemenu" '; }
    echo ' class="top_menu_line clearfix';
    if ($invoicemenu<>'1') { echo ' toggleinvoicemenu'; }
    echo ' ">
    <ul>';
    echo '<li><a href="view_all_invoices.php"'; if ($filename=='view_all_invoices.php') { echo ' class="selected"'; }
    echo '>Invoicing</a></li>';
    echo '<li><a href="pdfview.php"'; 
    if ($filename=='pdfview.php') { echo ' class="selected"'; }
    echo '>New Invoice</a></li>';
    echo '<li><a href="paymentsin.php"'; 
    if ($filename=='paymentsin.php') { echo ' class="selected"'; }
    echo '>Payment</a></li>';

    echo '<li><a href="singleexpense.php"'; 
    if ($filename=='singleexpense.php') { echo ' class="selected"'; }
    echo '>Expense</a></li>';
    echo '<li><a href="expenseview.php"'; 
    if ($filename=='expenseview.php') { echo ' class="selected"'; }
    echo '> P+L Search</a></li>';
    echo '<li><a href="pandl.php"'; 
    if ($filename=='pandl.php') { echo ' class="selected"'; }
    echo '>P+L</a></li> 
    </ul>';
    echo '</div>';
}
 
 
 
if (($settingsmenu<>'1') and ($invoicemenu<>'1') and ($adminmenu<>'1')) {} else {

    echo '<div ';
    if ($settingsmenu<>'1') { echo 'id="togglesettingsmenu" '; }
    echo ' class="top_menu_line clearfix';
    if ($settingsmenu<>'1') { echo ' togglesettingsmenu '; }
    echo ' "><ul> ';
    echo '<li><a href="cojmglobal.php"';
    if ($filename=='cojmglobal.php') { echo ' class="selected"'; }
    echo '>Main Settings</a></li>';
    echo '<li><a href="service.php"'; 
    if ($filename=='service.php') { echo ' class="selected"'; } 
    echo '>Services</a></li>';
    echo '<li><a href="corepricing.php"'; 
    if ($filename=='corepricing.php') { echo ' class="selected"'; }
    echo '>Checkbox Pricing</a></li>';
    echo '<li><a href="cojmglobalemail.php"';
    if ($filename=='cojmglobalemail.php') { echo ' class="selected"';} 
    echo '>Set Email</a></li>';
    echo '<li><a href="cojmglobalstatus.php"'; 
    if ($filename=='cojmglobalstatus.php') { echo ' class="selected"'; } 
    echo '>Status Text</a></li>';
    if ($globalprefrow['showdebug']>'0') {
        echo '<li><a href="debug.php"'; 
        if ($filename=='debug.php') { echo ' class="selected"'; } 
        echo '> php + db info </a></li>';
    }  
    if (($globalprefrow['inaccuratepostcode'])=='0') {
        echo '<li><a title="Add or Edit Postcode" href="newpc.php"';
        if ($filename=='newpc.php') { echo ' class="selected"'; }
        echo '>Add Postcode</a></li>';
    }
    //  <a href="editbankhol.php">Bank Hols</a> 

    echo '<li><a href="backupinfo.php"'; 
    if ($filename=='backupinfo.php') { echo ' class="selected"'; }
    echo '>Backups</a></li>';
    echo '<li><a href="cojmaudit.php"'; 
    if ($filename=='cojmaudit.php') { echo ' class="selected"'; } 
    echo '>System Log</a></li>';
    echo '</ul>';
    echo '</div>';
}



echo '<div id="togglenewjob" class="ui-widget spaceout ui-state-highlight ui-corner-all innerpad clearfix" >';


$CustomerID='';

echo '<form action="order.php" method="post" id="newjob_form" accept-charset="utf-8"><input type="hidden" name="formbirthday" value="'. date("U").'" />

<input type="hidden" name="page" value="newjobfromajax" />
<div class="fsl"> Client </div>
<div class="left">
<select class="caps ui-state-default ui-corner-all" id="newjobselectclient" name="newjobselectclient" >
<option value="">Select one...</option>';


$query = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient>0 ORDER BY CompanyName"; 

$currentclientdata = $dbh->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);
foreach ($currentclientdata as $CustomerIDlist => $CompanyName) {
    print'<option value="'.$CustomerIDlist.'">'.htmlspecialchars ($CompanyName).'</option>';
}
echo '</select> </div>  
<div id="afterclientselect" class="left">
<a href="#" title="Add Inactive Clients" class="showinactiveclient" id="showinactiveclient"> </a> </div>
<div id="status"></div>  </form> </div>';
