<?php $omega_time = microtime(TRUE); $lapse_time = $omega_time - $alpha_time; $lapse_msec = $lapse_time * '1000.0'; $lapse_echo = number_format($lapse_msec, 1);
if ($mobdevice<>'1') { echo '  
<p id="back-top"><a href="#bodytop"><span></span></a></p> 
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
 $newpoint="INSERT INTO cojm_audit (auditid,audituser,auditorderid,auditpage,auditfilename,auditmobdevice,
 auditbrowser,audittext,auditcjtime,auditpagetime,auditmidtime,auditinfotext)   
 VALUES ('','$cyclistid','$orderauditid','$page','$filename','$mobdevice',
 '$browser','$pagetext','$cj_msec','$lapse_msec','','$infotext')";
 mysql_query($newpoint, $conn_id) or mysql_error(); $newauditid=mysql_insert_id();
 if (mysql_error()) { echo '<div class="moreinfotext"><h1> Problem saving audit log </h1></div>'.$newpoint; } // ends error
echo ' <script> var initialauditid='.$newauditid.'; $(document).ready(function () { setTimeout( function () { pageloadedfine(); }, 950 ); ';
if ($globalprefrow['showdebug']>'0') { echo ' setTimeout( function () { $("#activestatus").slideUp(1500); }, 20000 );'; }
echo '}); </script>';