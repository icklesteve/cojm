<?php 

/*
    COJM Courier Online Operations Management
	footer.php - Should be called at bottom of every page, adds copyright, audit log, back to top + triggers cron check
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

$omega_time = microtime(TRUE); 
$lapse_time = $omega_time - $alpha_time; 
$lapse_msec = $lapse_time * '1000.0'; 
$lapse_echo = number_format($lapse_msec, 1);
if ($mobdevice<>'1') { echo '  
<a id="back-top" href="#bodytop"></a>
<div class="cojmcopyright">Logged in as '.$cyclistid.'
<br /><a target="_blank" href="http://www.cojm.co.uk/">Powered by COJM &copy; 2010-'.date('Y').'</a>
<span class="printcopyr"></span></div>
'; }
 if ($globalprefrow['glob7']=='1') { echo "<div class='moreinfotext'> $cj_echo / $lapse_echo ms. </div>"; 
 echo "<div class='moreinfotext'> $moreinfotext </div>"; 
 } 
 if (is_numeric("$orderauditid")) { } else { $orderauditid=''; }	
 if ($mobdevice=='') { $mobdevice='0'; } 
 $infotext=trim($infotext);
 $transf = array("'" => "&#39;");
$infotext= strtr($infotext, $transf);
 if (substr("$infotext", 0, 6) === '<br />') { $infotext= substr("$infotext", 6); } 

$pagetext=$alerttext.$pagetext;
$browser=$_SERVER["HTTP_USER_AGENT"];


$statement = $dbh->prepare("INSERT INTO cojm_audit 
(auditorderid, audituser, auditpage, auditfilename, auditmobdevice, auditbrowser, audittext, auditcjtime, auditpagetime, auditinfotext,auditdatetime) 
values 
(:orderid, :audituser, :page, :referrer, :auditmobdevice, :auditbrowser, :audittext, :auditcjtime, :auditpagetime, :auditinfotext, now())
");

$statement->bindParam(':orderid', $orderauditid, PDO::PARAM_STR);
$statement->bindParam(':audituser', $cyclistid, PDO::PARAM_STR);
$statement->bindParam(':page', $page, PDO::PARAM_STR);
$statement->bindParam(':referrer', $filename, PDO::PARAM_STR);
$statement->bindParam(':auditmobdevice', $mobdevice, PDO::PARAM_STR);
$statement->bindParam(':auditbrowser', $browser, PDO::PARAM_STR);
$statement->bindParam(':audittext', $pagetext, PDO::PARAM_STR);
$statement->bindParam(':auditcjtime', $cj_msec, PDO::PARAM_STR);
$statement->bindParam(':auditinfotext', $infotext, PDO::PARAM_STR);
$statement->bindParam(':auditpagetime', $lapse_msec, PDO::PARAM_STR);
$statement->execute();
$newauditid = $dbh->lastInsertId();
 
echo ' <script> var initialauditid='.$newauditid.'; </script>';