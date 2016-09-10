<?php 
/*
    COJM Courier Online Operations Management
	changejob.php - Handles non-ajax job changes ( the controller in MVC language :-), also see ajaxchangejob.php
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







$cj_time = microtime(TRUE);

$origid='';


// echo memory_get_usage();

if (!isset($cyclistid)) { $cyclistid=''; }

if (!isset($infotext)) { $infotext=''; }

$moreinfotext=''; // used for page sql debugging, not changejob.php

$cojmaction='';
if (isset ($pagetext)) {} else { $pagetext=''; }
if (isset ($alerttext)) {} else { $alerttext=''; }

if ($globalprefrow['showdebug']>0) {  // error handler function
    function myErrorHandler($errno, $errstr, $errfile, $errline) {
        global $infotext;
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting
            return;
        }

        switch ($errno) {
            case E_USER_ERROR:
                echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
                echo "  Fatal error on line $errline in file $errfile";
                echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                echo "Aborting...<br />\n";
                exit(1);
            break;
    
            case E_USER_WARNING:
                echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
            break;
    
            case E_USER_NOTICE:
                echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;
            
            default:
                $infotext.= "<br />$errstr on line $errline in $errfile\n";
                echo " $errstr on line $errline in $errfile<br /> \n";
            break;
        }
    
        /* Don't execute PHP internal error handler */
        return true;
    }
    
    error_reporting(E_ALL);
    // set to the user defined error handler
    $old_error_handler = set_error_handler("myErrorHandler");
}



if (isset($_POST['page'])) { $page=trim($_POST['page']); } else { if (isset($_GET['page'])) {  $page=trim($_GET['page']); } } 

if (isset($page)) {} else { $page=''; }
 
 
if (!isset($cyclistid)) { $cyclistid=''; }


if (!$cyclistid) { 
    if (isset($_SERVER["PHP_AUTH_USER"])) { $cyclistid=$_SERVER["PHP_AUTH_USER"]; }
    else if (isset($_SERVER["REMOTE_USER"])) { $cyclistid=$_SERVER["REMOTE_USER"]; }
}


if (isset($_POST['id'])) { $id = trim($_POST['id']); } else { if (isset($_GET['id'])) { $id=trim($_GET['id']); }}

if (isset($id)) { $ID=$id; $oldid=$ID; $origid=$id; } else $id='';

$nowepoch=date("U"); 

if (isset($_POST['formbirthday'])) {
    $formbirthday=trim($_POST['formbirthday']); 
    $nowepoch=$nowepoch-$formbirthday; 
}

include("GeoCalc.class.php");



if (preg_match('/iPhone|Android|Blackberry/i', $_SERVER['HTTP_USER_AGENT'])) { $mobdevice='1'; } else { $mobdevice=''; }


if (isset($_POST['numberitems'])) {
    $numberitems=trim($_POST['numberitems']);
    $numberitems= str_replace(",", "", "$numberitems");
}


if (isset($_POST['viewtype'])) { $viewtype=$_POST['viewtype']; }


$today = date(" H:i A D j M Y");







/////////////////////     FINISHED VARIABLES          /////////////
/////////////////////     OK HERE WE GO :-)           ////////////////////////
/////////////////////     SEE DIA GRAM FOR MORE INFO  ////////////


// $infotext.=' Server Timezone : '.date_default_timezone_get();
// UTC default?

if (date_default_timezone_get()=='UTC') { $nowepoch=-900000000000000; }

if ($nowepoch < $globalprefrow['formtimeout']) {

    if (($page=='editarea') or ($page=='opsmapnewarea')) { // checks for anti-clockwise in ops maps + edit & new areas

        if ($vertices) {
            $pexploded=explode( ',', $vertices );
        
            foreach ($pexploded as $tv) {
                $tv=trim($tv);
                $ttransf = array(" " => ",");
                $tcoord[]= strtr($tv, $ttransf);
            }
        
            $tarrlength = count($tcoord);
            $tareax = '0';
            $twinding='0';
        
            while ( $tareax < ($tarrlength-1)) {
                $do=( explode( ',', $tcoord[$tareax+1] ) );
                $co=( explode( ',', $tcoord[$tareax] ) );
                $twinding=$twinding+ (($co[0]-$do[0]) * ($do[1]+$co[1]));
                $tareax++;
            }
        
            if ($twinding<'0') {
                $infotext.= '<br /> ant-clockwise ';
                $newvertices='';
                $tareax=($tarrlength-'1');
                while ( $tareax > '-1') {
                    $co=( explode( ',', $tcoord[$tareax] ));
                    $newvertices.=' '.$co[0].' '.$co[1].', ';
                    $tareax--;
                }
                $newvertices = ''.rtrim($newvertices, ', ').' '; 
                $vertices=$newvertices;
            }
        }

        
        
        if ($page=='editarea') {
    
            $infotext =$infotext. ' in edit ops map area ';
            if (isset($_POST['areaid'])) { $areaid=trim($_POST['areaid']);} else { $areaid=''; }
            if (isset($_POST['areaname'])) { $areaname=trim($_POST['areaname']);} else { $areaname=''; }
            if (isset($_POST['areacomments'])) { $areacomments=trim($_POST['areacomments']);} else { $areacomments=''; }
            if (isset($_POST['inarchive'])) { $inarchive=trim($_POST['inarchive']); } else { $inarchive='0'; }
            if (isset($_POST['istoplayer'])) { $istoplayer=trim($_POST['istoplayer']); } else { $istoplayer='0'; }
            if (isset($_POST['corelayer'])) { $corelayer=trim($_POST['corelayer']);} else { $corelayer='0'; }
            
            
            if ($areaid) {
                if ($vertices=='') {
                    $sql="UPDATE opsmap 
                    SET inarchive='".$inarchive."', 
                    opsname='".$areaname."' , 
                    descrip='".$areacomments."' , 
                    istoplayer='".$istoplayer."',
                    corelayer='".$corelayer."'
                    WHERE opsmapid=".$areaid; 
                }
                else { 
            
                $sql="UPDATE opsmap 
                SET inarchive='".$inarchive."', 
                opsname='".$areaname."' , 
                descrip='".$areacomments."' , 
                g= PolygonFromText('POLYGON((".$vertices."))'),
                istoplayer='".$istoplayer."',
                corelayer='".$corelayer."'
                WHERE opsmapid=".$areaid; 
                }
            
            }
            else { // no area id
            
            
            $sql="INSERT INTO opsmap (type,opsname,istoplayer,corelayer,descrip";
            
            if ($vertices) { 
            $sql.=",g";
            
            }
            $sql.=") VALUES ( '2', '".
            $areaname."','".
            $istoplayer."','".
            $corelayer."','".
            $areacomments."'";
            if ($vertices) { 
            $sql.=", PolygonFromText('POLYGON(( ".$vertices."))')";
            }
            $sql.=");";
            
            
            }
            
            
            
            
            
            $result = mysql_query($sql, $conn_id);
            
            
            // $infotext.= $sql;
            
            if ($result){
                
            $infotext.="<br />Success";
            $pagetext.='<p>Success</p>';
            $pagetext.='<p>Edited area '.$areaname.'.</p>';
            $infotext.='<p>Edited OpsMapArea '.$areaid.' </p>'; 
            } else {
            $infotext.=mysql_error()." An error occured during editing area!<br>".$sql;  
            $alerttext.=mysql_error()." <p>An error occured during editing area!</p>".$sql;
            } // ends 
            
            // } // ends vertices check
            
        } // ends page editarea
    
    
        if ($page=='opsmapnewarea') {
        
            $infotext =$infotext. ' in new ops map area ';
    
            // if ($vertices=='') { $infotext.='<br /> NO VERTICES PASSED'; } else {
    
    
    
    
            if (isset($_POST['areaname'])) { $areaname=trim($_POST['areaname']);} else {$areaname=''; }
    if (isset($_POST['areacomments'])) { $areacomments=trim($_POST['areacomments']);} else { $areacomments=''; }
        
    if (isset($_POST['inarchive'])) { $inarchive=trim($_POST['inarchive']); } else { $inarchive='0'; } // Show Working Windows
    
    if (isset($_POST['istoplayer'])) { $istoplayer=trim($_POST['istoplayer']); } else { $istoplayer='0'; } // Show Working Windows
    if (isset($_POST['corelayer'])) { $corelayer=trim($_POST['corelayer']);} else { $corelayer='0'; }
        
        
    //	$vertices = rtrim($vertices, ',');	
    //	$infotext.='<br />'. $areaname; 	
    //  $infotext.='<br />'. $areacomments; 
    //  $infotext.='<br />'. $vertices; 
    //	$infotext.='<br />'. $output; 	
        
    $sql="INSERT INTO opsmap (type,opsname,istoplayer,corelayer,descrip";
    
    if ($vertices) { 
    $sql.=",g";
    
    }
    $sql.=") VALUES ( '2', '".
    $areaname."','".
    $istoplayer."','".
    $corelayer."','".
    $areacomments."'";
    if ($vertices) { 
    $sql.=", PolygonFromText('POLYGON(( ".$vertices."))')";
    }
    $sql.=");";
    $infotext.= $sql;
        
    // echo $sql;
        
        $result = mysql_query($sql, $conn_id);
    if ($result){
    $infotext.="<br />Success";
    $pagetext.='<p>Success</p>';
    $areaid=mysql_insert_id(); 
    $pagetext.='<p>New area '.$areaname.' created.</p>';
    $infotext.='<p>New OpsMapArea '.$areaid.' created.</p>'; 
    } else {
    $infotext.=mysql_error()." An error occured during creating new area!<br>".$sql;  
    $alerttext.=mysql_error()." <p>An error occured during adding new area!</p>".$sql;
    } // ends 
    
    $page='editarea';	
    
    // } // ends vertices check
        
    
        
    } // ends page= opsmapnewarea
    
    }



    if ($page=='editglobalemail') {


$emailfrom=trim($_POST['emailfrom']);
$emailfromname=trim($_POST['emailfromname']);
// $emailheader=trim($_POST['emailheader']);        ///////////     SPARE
$htmlemailheader=trim($_POST['htmlemailheader']);
$emailbody=trim($_POST['emailbody']);
$htmlemailbody=trim($_POST['htmlemailbody']);
$emailfooter=trim($_POST['emailfooter']);
$htmlemailfooter=trim($_POST['htmlemailfooter']);
$emailbcc=trim($_POST['emailbcc']);
$email1=trim($_POST['email1']);
$email2=trim($_POST['email2']);
$email3=trim($_POST['email3']);
$email4=trim($_POST['email4']);
$email5=trim($_POST['email5']);
$email6=trim($_POST['email6']);
$email7=trim($_POST['email7']);
$email8=trim($_POST['email8']);
$email9=trim($_POST['email9']);
$email10=trim($_POST['email10']);
$email11=trim($_POST['email11']);
$email12=trim($_POST['email12']);
$email13=trim($_POST['email13']);
$email14=trim($_POST['email14']);
$email15=trim($_POST['email15']);
$email16=trim($_POST['email16']);
$email17=trim($_POST['email17']);
$email18=trim($_POST['email18']);
$email19=trim($_POST['email19']);
$email20=trim($_POST['email20']);




if ($emailfrom) {


// emailheader='$emailheader' , 

$sql = "UPDATE globalprefs SET 
emailfrom='$emailfrom' , 
emailbcc='$emailbcc' , 
emailfromname='$emailfromname' ,
htmlemailheader='$htmlemailheader' , 
emailbody='$emailbody' ,
htmlemailbody='$htmlemailbody' ,
emailfooter='$emailfooter' , 
htmlemailfooter='$htmlemailfooter' ,
email1='$email1' ,
email2='$email2' ,
email3='$email3' ,
email4='$email4' ,
email5='$email5' ,
email6='$email6' ,
email7='$email7' ,
email8='$email8' ,
email9='$email9' ,
email10='$email10' ,
email11='$email11' ,
email12='$email12' ,
email13='$email13' ,
email14='$email14' ,
email15='$email15' ,
email16='$email16' ,
email17='$email17' ,
email18='$email18' ,
email19='$email19' ,
email20='$email20' 
"; 
$result = mysql_query($sql, $conn_id);
if ($result){ 
$infotext.="<br />Updated Email Setup"; 
$pagetext.="<p>Updated Email Setup</p>"; 
} 
else { 
$infotext.=mysql_error()."<br /><strong>An error occured during updating email settings</strong>"; 
$alerttext.="<p><strong>An error occured during updating email settings</strong></p>"; 
} 

} // ends chek for emai from
} // finishes global email



    if ($page=='newpostcode') {
 
 $lat=trim($_POST['lat']);
$lng=trim($_POST['lng']);
$town=trim($_POST['town']);
$area=trim($_POST['area']);
$newpc=trim($_POST['newpc']);
$newpc = str_replace(" ", "", "$newpc", $count); 
$newpc = str_replace (" ", "", strtoupper($newpc));
$infotext.='<br />Adding new postcode '.$newpc;


$existingquery=" SELECT `PZ_Postcode`
FROM  `postcodeuk` 
WHERE  `PZ_Postcode` 
LIKE  '$newpc' LIMIT 0,1
";

// echo $existingquery;

$ifexistingpc = mysql_num_rows(mysql_query($existingquery, $conn_id));


// $sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
// $num_rows = mysql_num_rows($sql_result);


if ($ifexistingpc>0) {  


// $infotext.='<br />ifexistingpc : '.$ifexistingpc; 
// $infotext.='<br />lat : '.$lat; 
// $infotext.='<br />lon : '.$lng; 

$sql = "UPDATE `postcodeuk` 
SET  `PZ_zero` =  '1' ,
 `PZ_northing` =  '$lat' ,
 `PZ_easting` =  '$lng'
WHERE 
`PZ_Postcode` =  '$newpc' LIMIT 1;";
 
 $result = mysql_query($sql, $conn_id);
if ($result){ 
$infotext.='<br /> Postcode updated ';
$pagetext.='<p> Postcode updated </p>';

} else { $infotext.="<br /><strong>Error occured in updating postcode</strong>".$sql; }

} else {  // ends no existing pc


$sql="INSERT INTO `postcodeuk` (`PZ_Postcode`, `PZ_northing`, `PZ_easting`, `PZ_zero`) VALUES 
('$newpc', '$lat', '$lng', '1');";
 $result = mysql_query($sql, $conn_id);
if ($result){ 
$pagetext.='<p> Postcode added </p>';
$infotext.='<br /> Postcode added ';
} else { 
$alerttext.="<p><strong>Error occured in adding postcode</strong></p>"; 
$infotext.="<br /><strong>Error occured in adding postcode</strong>".$sql; 
} // sql result
} // ends existing postcode

if ($id) {

// calls recalc mileage in case page has come from an id, not menu
calcmileage($id, $globalprefrow['distanceunit'], $globalprefrow['co2perdist'], $globalprefrow['pm10perdist']);
$cojmaction='recalcprice';
}


} // ends page =editpostcode




    if ($page=='editglobalstatus') {

// $infotext.='<br />Editing Status Text';
$i=0;
While ($i < 500) {
if (isset($_POST["statusid$i"])) {
if (($_POST["statusid$i"])=='1') {

// do check to make sure that no jobs with that current status are selected

if (isset($_POST["activestatus$i"])) { $activestatus= trim($_POST["activestatus$i"]); } else { $activestatus='0'; }
$statusname=trim($_POST["statusname$i"]);
$publicstatusname=trim($_POST["publicstatusname$i"]);

 // check for active jobs at changed status
 $sql = "SELECT * FROM Orders 
 WHERE (`Orders`.`status` = '$i' )
 ORDER BY `Orders`.`ID` ";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); $sumtot=mysql_affected_rows(); 

if ($sumtot>0)  {
if ($activestatus=='0') {

$infotext.= '<br /><strong>Unable to deactive status'.$i.',</strong><br /><strong>'.$sumtot.' Jobs Outstanding.';
$pagetext.= '<p><strong>Unable to deactive status'.$i.',</strong><br /><strong>'.$sumtot.' Jobs Outstanding.</p>';

while ($row = mysql_fetch_array($sql_result)) { extract($row); 
$infotext.= '<br /><a target="_blank" href="order.php?id='. $row['ID'] .'">'. $row['ID'].'</a>';
$pagetext.= '<p><a target="_blank" href="order.php?id='. $row['ID'] .'">'. $row['ID'].'</p>';
} // ends check for jobs in loop 
$activestatus='1';
} // see if active
} // see if need to loop
$infotext.= '<br />'.$i.' '.$activestatus.' '.$statusname.' '.$publicstatusname;
$sql = "UPDATE status SET statusname='$statusname', activestatus='$activestatus', 
publicstatusname='$publicstatusname' WHERE status='$i' LIMIT 1"; $result = mysql_query($sql, $conn_id);
if ($result){ 
// $infotext.="<br />Status updated for id ".$i.' '.$sql; 
} 
else { 
$alerttext.='<p>Problem updating status '.$i.'</p>';
$infotext.="<br /><strong>An error occured during updating updating status".$i."!</strong>".$sql; }

} // ends check for activeid

} // ends isset check

$i++; }
$pagetext.='<p>Statuses updated</p>';
$infotext.='<p>Status update complete</p>';
} // ends edit global status text




    if ($page=="editcyclistdetails") {


$thiscyclist=$_POST['thiscyclist'];


if ((isset($_POST['dob'])) and ($_POST['dob'])) {
$dob=trim($_POST['dob']);
$dob = str_replace("/", ":", "$dob", $count);
$dob = str_replace(",", ":", "$dob", $count);
$dob = str_replace("-", ":", "$dob", $count);
$temp_ar=explode(":",$dob); 
$dobday=$temp_ar[0]; 
$dobmonth=$temp_ar[1]; 
$dobyear=$temp_ar[2]; 
$dobdate=date("Y-m-d H:i:s", mktime(01, 01, 01, $dobmonth, $dobday, $dobyear));
if (!$dob) { $dobdate=''; }
} else { $dobdate=''; }


if ((isset($_POST['contractstartdate'])) and ($_POST['contractstartdate'])) {
$contractstartdate=trim($_POST['contractstartdate']);
$contractstartdate = str_replace("/", ":", "$contractstartdate", $count);
$contractstartdate = str_replace(",", ":", "$contractstartdate", $count);
$contractstartdate = str_replace("-", ":", "$contractstartdate", $count);
$temp_ar=explode(":",$contractstartdate); 
$startday=$temp_ar[0]; 
$startmonth=$temp_ar[1]; 
$startyear=$temp_ar[2];
$startdate=date("Y-m-d H:i:s", mktime(01, 01, 01, $startmonth, $startday, $startyear));
if (!$contractstartdate) { $startdate=''; }
} else { $startdate=''; }

$mobilenumber=trim($_POST['mobilenumber']);
$cojmname=trim($_POST['cojmname']);
$poshname=trim($_POST['poshname']);
$trackerid=trim($_POST['trackerid']);
$housenumber=trim($_POST['housenumber']);
$streetname=trim($_POST['streetname']);
$postcode=trim($_POST['postcode']);
$icename=trim($_POST['icename']);
$icenumber=trim($_POST['icenumber']);
$ninumber=trim($_POST['ninumber']);
$sortcode=trim($_POST['sortcode']);
$accountnum=trim($_POST['accountnum']);
$bankname=trim($_POST['bankname']);
$notes=trim($_POST['description']);
$city=trim($_POST['city']);


if (($globalprefrow['inaccuratepostcode'])==0) {
$postcode = str_replace (" ", "", strtoupper($postcode));
$start=substr($postcode, 0, -3);  $postcode=trim($start.' '.substr($postcode, -3)); // 'ies'  
}





if (isset($_POST['isactive'])) { $isactive=trim($_POST['isactive']); } else { $isactive=''; }


$infotext = $infotext . '<br />Editing Cyclist Details';
// echo 'Mobile Number : ' . $mobilenumber;

$cyclistjoomlanumber=trim($_POST['cyclistjoomlanumber']);

$sql = "SELECT * FROM Cyclist 
WHERE `Cyclist`.`CyclistID` <> $thiscyclist  
AND `Cyclist`.`isactive` = '1'      
AND  `Cyclist`.`cojmname` = '$cojmname' LIMIT 1 ";


$sql_result = mysql_query($sql,$conn_id); 
$sumtot=mysql_affected_rows(); if ($sumtot>0)  {

$alerttext.='<p><strong>'.$cojmname.' already exists, please use a different name.</strong></p>';
$infotext.='<br /><strong>'.$cojmname.' already exists</strong>'; } 

if ($thiscyclist) {
if ($cojmname) {

if ($mobdevice) {




 $sql = "UPDATE Cyclist SET 
 poshname=(UPPER('$poshname')) , 
 isactive='$isactive' ,  
 cojmname=(UPPER('$cojmname')) , 
 mobilenumber='$mobilenumber' , 
 trackerid='$trackerid' ,  
 cyclistjoomlanumber='$cyclistjoomlanumber' ,
 housenumber=(UPPER('$housenumber')) , 
 streetname=(UPPER('$streetname')) , 
 city=(UPPER('$city')) , 
 postcode=(UPPER('$postcode')) ,  
 contractstartdate='$startdate' , 
 icename=(UPPER('$icename')) , 
 icenumber=(UPPER('$icenumber')) , 
 notes=(UPPER('$notes')) 
 WHERE CyclistID='$thiscyclist' 
 LIMIT 1"; 


} else {

 $sql = "UPDATE Cyclist SET 
 poshname=(UPPER('$poshname')) , 
 isactive='$isactive' ,  
 cojmname=(UPPER('$cojmname')) , 
 mobilenumber='$mobilenumber' , 
 trackerid='$trackerid' ,  
 DOB='$dobdate' , 
 cyclistjoomlanumber='$cyclistjoomlanumber' ,
 housenumber=(UPPER('$housenumber')) , 
 streetname=(UPPER('$streetname')) , 
 city=(UPPER('$city')) , 
 postcode=(UPPER('$postcode')) ,  
 contractstartdate='$startdate' , 
 ninumber=(UPPER('$ninumber')) , 
 icename=(UPPER('$icename')) , 
 icenumber=(UPPER('$icenumber')) , 
 sortcode='$sortcode' , 
 accountnum='$accountnum' , 
 bankname=(UPPER('$bankname')) , 
 notes=(UPPER('$notes')) 
 WHERE CyclistID='$thiscyclist' 
 LIMIT 1"; 
 
 
 
 } // ends mobile device check
 
 
 } // ends cojmname
  
 
 $result = mysql_query($sql, $conn_id);
 if ($result){ 
 $infotext.="<br />Success";
 $pagetext.='<p>'.$globalprefrow['glob5'].' details updated</p>';
 } else {
 $infotext.=mysql_error()." An error occured during update!<br>".$sql;  
 $alerttext.=mysql_error()." <p>An error occured during update!</p>";
 } // ends 
 } // ends check on thiscyclist
 

} // ends page=editcyclist details




    if ($page=='addnewcyclist') {

if (isset($_POST['CompanyName'])) { $cojmname=trim($_POST['CompanyName']); }
if (isset($_POST['CompanyName'])) { $poshname=trim($_POST['CompanyName']); }



$trackerid=mt_rand(99, 99999999);


$sql = "SELECT CyclistID FROM Cyclist WEHRE trackerid ='$trackerid' LIMIT 0,1"; $result = mysql_query($sql, $conn_id);

// $infotext.='Res:'.$result;

// $checkiftrackerid = mysql_result(mysql_query("SELECT CyclistID FROM Cyclist WEHRE trackerid ='$trackerid' LIMIT 0,1", $conn_id), 0);

if ($result>'0') { $trackerid=mt_rand(99, 99999999); }







 $infotext.='<p><b>New Cyclist</b></p>'; 

   $sql="INSERT INTO Cyclist 
   (poshname, isactive, cojmname, trackerid) 
    VALUES
  ('$poshname',
   '1',
   '$cojmname',
   '$trackerid'
)   ";
   
   

   
   
    $result = mysql_query($sql, $conn_id);
 if ($result){
 $infotext.="<br />Success";
 $pagetext.='<p>'.$globalprefrow['glob5'].' details updated</p>';
 
  
 $thiscyclist=mysql_insert_id(); 
   $newcyclistid=$thiscyclist;
      $pagetext.='<p>New '.$globalprefrow['glob5'].' '.$thiscyclist.' '.$cojmname.' created.</p>';
   $infotext.='<p>New Rider No. '.$thiscyclist.' created.</p>'; 
   
 
 } else {
 $infotext.=mysql_error()." An error occured during update!<br>".$sql;  
 $alerttext.=mysql_error()." <p>An error occured during update!</p>";
 } // ends 



   



   
} // ends page=addnewcyclist



    if ($page=="editthisservice") {

// $infotext.='<br><strong>editing service details</strong>';

$Service=trim($_POST['Service']);
$servicecomments=trim($_POST['servicecomments']);
$Price=trim($_POST['Price']);
$CO2Saved=trim($_POST['CO2Saved']);
$PM10Saved=trim($_POST['PM10Saved']);

if (isset($_POST['LicensedCount'])) { $LicensedCount=trim($_POST['LicensedCount']); } else { $LicensedCount=''; }
if (isset($_POST['UnlicensedCount'])) { $UnlicensedCount=trim($_POST['UnlicensedCount']); } else { $UnlicensedCount=''; }
if (isset($_POST['batchdropcount'])) { $batchdropcount=trim($_POST['batchdropcount']); } else { $batchdropcount=''; }
if (isset($_POST['hourlyothercount'])) { $hourlyothercount=trim($_POST['hourlyothercount']); } else { $hourlyothercount=''; }
if (isset($_POST['asapservice'])) { $asapservice=trim($_POST['asapservice']); } else { $asapservice=''; }
if (isset($_POST['cargoservice'])) { $cargoservice=trim($_POST['cargoservice']); } else { $cargoservice=''; }
if (isset($_POST['RMcount'])) { $RMcount=trim($_POST['RMcount']); } else { $RMcount=''; }
if (isset($_POST['activeservice'])) { $activeservice=trim($_POST['activeservice']); } else { $activeservice=''; }
if (isset($_POST['isregular'])) { $isregular=trim($_POST['isregular']); } else { $isregular=''; }
if (isset($_POST['chargedbybuild'])) { $chargedbybuild=trim($_POST['chargedbybuild']); } else { $chargedbybuild=''; }
if (isset($_POST['chargedbycheck'])) { $chargedbycheck=trim($_POST['chargedbycheck']); } else { $chargedbycheck=''; }
if (isset($_POST['canhavemap'])) { $canhavemap=trim($_POST['canhavemap']); } else { $canhavemap=''; }

$slatime=trim($_POST['slatime']);
$sldtime=trim($_POST['sldtime']);
$serviceorder=trim($_POST['serviceorder']);
$vatband=trim($_POST['vatband']);
$thisserviceid=trim($_POST['serviceid']);





//28th june

if ($thisserviceid) {

$sql = "UPDATE Services SET Service='$Service' ,
 servicecomments='$servicecomments' , 
 Price='$Price' , 
 serviceorder='$serviceorder' ,
 CO2Saved='$CO2Saved' , 
PM10Saved='$PM10Saved' , 
LicensedCount='$LicensedCount' , 
UnlicensedCount='$UnlicensedCount' , 
batchdropcount='$batchdropcount' , 
hourlyothercount='$hourlyothercount' , 
RMcount='$RMcount' , 
slatime='$slatime' , 
sldtime='$sldtime' , 
asapservice='$asapservice' ,
cargoservice='$cargoservice' , 
vatband='$vatband' ,
activeservice='$activeservice' , 
isregular='$isregular' ,
chargedbybuild='$chargedbybuild' ,
chargedbycheck='$chargedbycheck' ,
canhavemap='$canhavemap' 
WHERE ServiceID='$thisserviceid' LIMIT 1"; 
$result = mysql_query($sql, $conn_id);
if ($result){ 
$infotext.="<br />Update service Success"; 
$pagetext.="<p>".$Service." Updated</p>"; 

} 
else { 
$infotext.=mysql_error()."<br /><strong>An error occured during updating service</strong>".$sql; 
$alerttext.="<p><strong>An error occured during updating service</strong></p>"; 

} 
 }
if ($thisserviceid=='') { if ($Service) { $infotext.='<p><strong>New Service</strong></p>';
   mysql_query("LOCK TABLES Services WRITE", $conn_id);
   mysql_query("INSERT INTO Services 
   (Service, 
   serviceorder,
   servicecomments, 
   Price, 
   CO2Saved, 
   PM10Saved, 
   LicensedCount, 
   UnlicensedCount, 
   batchdropcount, 
   hourlyothercount, 
   RMcount, 
   slatime, 
   sldtime, 
   activeservice,
   isregular,
   chargedbybuild, 
   vatband   ) 
     VALUES
   ('$Service',
   '$serviceorder',
   '$servicecomments',
   '$Price',
   '$CO2Saved', 
   '$PM10Saved', 
   '$LicensedCount',
   '$UnlicensedCount',
   '$batchdropcount',
   '$hourlyothercount',
   '$RMcount',
   '$slatime',
   '$sldtime',
   '$activeservice',
   '$isregular',
   '$chargedbybuild',
   '$vatband'   )
   ", $conn_id )
     or die(mysql_error()); 
   
  $thisserviceid=mysql_insert_id();  
mysql_query("UNLOCK TABLES", $conn_id);   

  $pagetext.='<p>New service '.$thisserviceid.' '.$Service.' Created.</p>';
  $infotext.='<br />New service '.$thisserviceid.' '.$Service.' Created.'.$sql;
   
 } // end new service

} // ends check for name > 0

} // ends page=editservice




    if ($page=="editclient") {

if (isset($_POST['CompanyName'])) { $CompanyName=trim($_POST['CompanyName']); }

$Notes=trim($_POST['Notes']);
$JoomlaUser=trim($_POST['JoomlaUser']);
$JoomlaUser2=trim($_POST['JoomlaUser2']);
$JoomlaUser3=trim($_POST['JoomlaUser3']);
$htmlemail=trim($_POST['htmlemail']);
// $monthlyinvoice=trim($_POST['monthlyinvoice']);    NO LONGER USED?

if (isset($_POST['isactiveclient'])) { $isactiveclient=trim($_POST['isactiveclient']); } else { $isactiveclient='0'; }

$invoiceterms=trim($_POST['invoiceterms']);
$invoicetype=trim($_POST['invoicetype']);
$Surname=trim($_POST['Surname']);
$Address=trim($_POST['Address']);
$Address2=trim($_POST['Address2']);

if (isset($_POST['County'])) { $County=trim($_POST['County']); } else { $County=''; }
if (isset($_POST['City'])) { $city=trim($_POST['City']); } else { $city=''; }
$Postcode=trim($_POST['Postcode']);

$emailaddress=trim($_POST['emailaddress']);
$invoiceEmailAddress=trim($_POST['invoiceemailaddress']);
$invoiceAddress=trim($_POST['invoiceAddress']);
$invoiceAddress2=trim($_POST['invoiceAddress2']);
$invoiceCity=trim($_POST['invoiceCity']);
$invoiceCounty=trim($_POST['invoiceCounty']);
$invoicePostcode=trim($_POST['invoicePostcode']);
$invoiceCountryOrRegion=trim($_POST['invoiceCountryOrRegion']);
$co2apiref=trim($_POST['co2apiref']);
// $creditaccount=trim($_POST['creditaccount']); NO LONGER USED, 1 or 0
$cbbdiscount=trim($_POST['cbbdiscount']);
// $defaultfrompc=trim($_POST['defaultfrompc']);
// $defaulttopc=trim($_POST['defaulttopc']);
// $defaultfromtext=trim($_POST['defaultfromtext']);
// $defaulttotext=trim($_POST['defaulttotext']);
$defaultrequestor=trim($_POST['defaultrequestor']);
$defaultservice=trim($_POST['defaultservice']);
$clientvatno=trim($_POST['clientvatno']);
$clientregno=trim($_POST['clientregno']);

if (isset($_POST['isdepartments'])) { $isdepartments=trim($_POST['isdepartments']); } else { $isdepartments=''; }

$PhoneNumber=trim($_POST['PhoneNumber']);
$Title=trim($_POST['Title']);
$Forename=trim($_POST['Forname']);


// $co2=trim($_POST['co2']);
// $pm10=trim($_POST['pm10']);
// $totalvolume=trim($_POST['totalvolume']);

$CountryOrRegion=trim($_POST['CountryOrRegion']);


$MobileNumber=trim($_POST['MobileNumber']);

if (isset($_POST['cemail1'])) { $cemail1=trim($_POST['cemail1']); } else { $cemail1='0'; }
if (isset($_POST['cemail2'])) { $cemail2=trim($_POST['cemail2']); } else { $cemail2='0'; }
if (isset($_POST['cemail3'])) { $cemail3=trim($_POST['cemail3']); } else { $cemail3='0'; }
if (isset($_POST['cemail4'])) { $cemail4=trim($_POST['cemail4']); } else { $cemail4='0'; }
if (isset($_POST['cemail5'])) { $cemail5=trim($_POST['cemail5']); } else { $cemail5='0'; }


$clientid=$_POST['clientid'];

if (!$invoiceEmailAddress) { $invoiceEmailAddress=$emailaddress; $pagetext.='<p>Invoice email set to main client as was missing</p>'; }
if (!$invoiceAddress) { $invoiceAddress=$Address; $pagetext.='<p>Invoice Address1 set to main client as was missing</p>'; }
if (!$invoiceAddress2) { $invoiceAddress2 = $Address2; $pagetext.='<p>Invoice Address2 set to main client as was missing</p>'; }
if (!$invoiceCity) { $invoiceCity = $city; $pagetext.='<p>Invoice City set to main client as was missing</p>'; }
if (!$invoiceCounty) { $invoiceCounty=$County; $pagetext.='<p>Invoice County set to main client as was missing</p>'; }
if (!$invoicePostcode) { $invoicePostcode=$Postcode; $pagetext.='<p>Invoice Postcode set to main client as was missing</p>'; }
if (!$invoiceCountryOrRegion) { $invoiceCountryOrRegion = $CountryOrRegion; $pagetext.='<p>Invoice Country set to main client as was missing</p>'; }


$defaultfrom=$_POST['defaultfrom'];
$defaultto=$_POST['defaultto'];


if (($globalprefrow['inaccuratepostcode'])==0) {


$Postcode = str_replace (" ", "", strtoupper($Postcode));

$start=substr($Postcode, 0, -3);  
$Postcode=trim($start.' '.substr($Postcode, -3)); // 'ies'  
}

if (($globalprefrow['inaccuratepostcode'])==0) {
$invoicePostcode = str_replace (" ", "", strtoupper($invoicePostcode));
$start=substr($invoicePostcode, 0, -3);  
$invoicePostcode=trim($start.' '.substr($invoicePostcode, -3)); // 'ies'  
}





$infotext.='<br />Editing client details<br />defaultfrom is'.$defaultfrom.'<br />defaultto is'.$defaultto;
if (($clientid) and ($CompanyName)) {
$sql = "UPDATE Clients SET 
CompanyName='$CompanyName' , 
EmailAddress='$emailaddress' , 
invoiceEmailAddress='$invoiceEmailAddress' , 
PhoneNumber='$PhoneNumber' , Title='$Title' , 
Forename='$Forename' , 
Surname='$Surname' , 
MobileNumber='$MobileNumber' , 
Address='$Address' , 
Address2='$Address2' , 
invoiceAddress='$invoiceAddress' , 
invoiceAddress2='$invoiceAddress2' , 
co2apiref='$co2apiref' , 
isactiveclient='$isactiveclient' , 
City='$city' , 
County='$County' , 
Postcode=(UPPER('$Postcode')) , 
CountryOrRegion='$CountryOrRegion' , 
MobileNumber='$MobileNumber' , 
Notes='$Notes' ,
htmlemail='$htmlemail' , 
JoomlaUser='$JoomlaUser' , 
JoomlaUser2='$JoomlaUser2' , 
JoomlaUser3='$JoomlaUser3' , 
invoiceCity='$invoiceCity' , 
invoiceCounty='$invoiceCounty' , 
invoicePostcode=(UPPER('$invoicePostcode')) , 
invoiceterms='$invoiceterms' ,
invoiceCountryOrRegion='$invoiceCountryOrRegion' ,
invoicetype='$invoicetype' ,
cbbdiscount='$cbbdiscount' ,
defaultfromtext=(UPPER('$defaultfrom')) ,
defaulttotext=(UPPER('$defaultto')) ,
defaultrequestor=(UPPER('$defaultrequestor')) ,
defaultservice='$defaultservice' ,
clientvatno=(UPPER('$clientvatno')) ,
clientregno=(UPPER('$clientregno')) ,
isdepartments='$isdepartments',
cemail1='$cemail1',
cemail2='$cemail2',
cemail3='$cemail3',
cemail4='$cemail4',
cemail5='$cemail5'
WHERE CustomerID='$clientid' LIMIT 1"; 
$result = mysql_query($sql, $conn_id);
if ($result){ 
$infotext.="<br />Success!"; 
$pagetext.="<p>Details updated for ".$CompanyName.".</p>"; 
} else { 
$infotext.=mysql_error()." An error occured during update!<br>"; 
$alerttext.="<p>An error occured during update!</p>"; 
} 
 
 
$i='1';
while ( $i < '5000') {
 
 
 if (isset($_POST["favadrid$i"] )) { 
 
 $infotext.='<br />Found an address';
 
 
 }
 
 $i++;
 
 } // ends favourite address loop
 
} // ends check to see if client updated, not new client

} // ends page=editclient
 


    if ($page=='createnewcl') {


if (isset($_POST['CompanyName'])) { $CompanyName=trim($_POST['CompanyName']); } else { $CompanyName=''; }


 if ($CompanyName) {
 $infotext.='<br />New client'; 
   mysql_query("LOCK TABLES Clients WRITE", $conn_id);
   mysql_query("INSERT INTO Clients 
   (CompanyName, 
 isactiveclient ) 
     VALUES
   ('$CompanyName',
   '1' )
   ", $conn_id
   )  or die(mysql_error()); 
   $clientid=mysql_insert_id();  
mysql_query("UNLOCK TABLES", $conn_id);   

$infotext.='<br />New client ID : '.$clientid;


$infotext.="<br />Success! ".$clientid; 
$pagetext.="<p>New client added with name ".$CompanyName.".</p>"; 

} // end  check companyname>0 


} // ends page=createnewcl
 


 
 

    if ($page=="editdepartment") {

$clientid=trim($_POST['clientid']);
// $infotext.='<br />Editing Department details';

$i=1;

while ($i<1000) {

if (isset($_POST["depname$i"])) { $depname=trim($_POST["depname$i"]); } else { $depname=''; }
if (isset($_POST["deprequestor$i"])) { $deprequestor=trim($_POST["deprequestor$i"]); } else { $deprequestor=''; }
if (isset($_POST["deppassword$i"])) { $deppassword=trim($_POST["deppassword$i"]); } else { $deppassword=''; }
if (isset($_POST["depemail$i"])) { $depemail=trim($_POST["depemail$i"]); } else { $depemail=''; }
if (isset($_POST["depaddone$i"])) { $depaddone=trim($_POST["depaddone$i"]); } else { $depaddone=''; }
if (isset($_POST["depaddtwo$i"])) { $depaddtwo=trim($_POST["depaddtwo$i"]); } else { $depaddtwo=''; }
if (isset($_POST["depaddthree$i"])) { $depaddthree=trim($_POST["depaddthree$i"]); } else { $depaddthree=''; }
if (isset($_POST["depaddfour$i"])) { $depaddfour=trim($_POST["depaddfour$i"]); } else { $depaddfour=''; }
if (isset($_POST["depaddfive$i"])) { $depaddfive=trim($_POST["depaddfive$i"]); } else { $depaddfive=''; }
if (isset($_POST["depaddsix$i"])) { $depaddsix=trim($_POST["depaddsix$i"]); } else { $depaddsix=''; }
if (isset($_POST["depdeffromft$i"])) { $depdeffromft=trim($_POST["depdeffromft$i"]); } else { $depdeffromft=''; }
if (isset($_POST["depdeftoft$i"])) { $depdeftoft=trim($_POST["depdeftoft$i"]); } else { $depdeftoft=''; }
if (isset($_POST["depcomment$i"])) { $depcomment=trim($_POST["depcomment$i"]); } else { $depcomment=''; }
if (isset($_POST["isactivedep$i"])) { $isactivedep=trim($_POST["isactivedep$i"]); } else { $isactivedep=''; }
if (isset($_POST["depphone$i"])) { $depphone=trim($_POST["depphone$i"]); } else { $depphone=''; }
if (isset($_POST["depservice$i"])) { $depservice=trim($_POST["depservice$i"]); } else { $sepservice=''; }
if (isset($_POST["depjoom$i"])) { $depjoom=trim($_POST["depjoom$i"]); } else { $depjoom=''; }



$sPattern = ' /\s*/m'; 
$sReplace = '';
$depaddsix=preg_replace( $sPattern, $sReplace, $depaddsix );

if (($globalprefrow['inaccuratepostcode'])==0) {


$start=substr($depaddsix, 0, -3);  $depaddsix=trim($start.' '.substr($depaddsix, -3)); // 'ies'  

}


if ($depname) {

// $infotext.='<br />'. $depname;

$sql = "UPDATE clientdep SET 
depname='$depname' ,
isactivedep='$isactivedep' , 
deppassword='$deppassword' , 
deprequestor=(UPPER('$deprequestor')) , 
depemail='$depemail' , 
depphone='$depphone' , 
depservice='$depservice' , 
depcomment='$depcomment' , 
depdeffromft=(UPPER('$depdeffromft')) ,
depdeftoft=(UPPER('$depdeftoft')) , 
depaddone='$depaddone' , 
depaddtwo='$depaddtwo' , 
depaddthree='$depaddthree' , 
depaddfour='$depaddfour' , 
depaddfive='$depaddfive' , 
depaddsix=(UPPER('$depaddsix')) ,
depjoom='$depjoom'
WHERE depnumber='$i' 
AND associatedclient='$clientid'
 LIMIT 1"; 


 $result = mysql_query($sql, $conn_id);
 if ($result){ $infotext.="<br />Updated ".$depname; } else { 
 $infotext.=mysql_error()." An error occured during updating department!<br>"; 
 $alerttext.=" <p>An error occured during updating department ".$i.' '.$depname."!</p>"; 
 } 

}
$i++;
} // ends $i loop


$pagetext.='<p>All departments for this client updated.</p>';

} // ends page = edit department



    if ( $page=='createnewdep') {

$clientid=trim($_POST['clientid']);
$newdepname=trim($_POST['newdepname']);

$infotext.='<br />ClientID : '.$clientid.'<br /> newdepname : '.$newdepname;




if (($clientid) and ($newdepname)) {

$sql = "SELECT * FROM Clients WHERE CustomerID = '$clientid' ";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$clrow=mysql_fetch_array($sql_result);

$infotext.='<br />'. $clrow['Address'].', '. $clrow['Address2'].'. '. $clrow['City'].', 
'. $clrow['County'].', '. $clrow['CountryOrRegion'].'. '. $clrow['Postcode']; 


$depaddone=trim($clrow['Address']);
$depaddtwo=trim($clrow['Address2']);
$depaddthree=trim($clrow['City']);
$depaddfour=trim($clrow['County']);
$depaddfive=trim($clrow['CountryOrRegion']);
$depaddsix=trim($clrow['Postcode']);


 $latestdepno = mysql_result(mysql_query("
 SELECT depnumber 
 from clientdep
ORDER BY depnumber DESC
 LIMIT 1 ", $conn_id), 0);
 if ($latestdepno) { //
 $infotext.= 'Latest dep number is : '.$latestdepno;
}
$latestdepno++;
   mysql_query("LOCK TABLES clientdep WRITE", $conn_id);
   mysql_query("INSERT INTO clientdep 
   (depnumber,
   associatedclient,
   isactivedep ,
   depaddone ,
   depaddtwo ,
   depaddthree ,
   depaddfour ,
   depaddfive ,
   depaddsix ,
   depname) 
   VALUES
   ('$latestdepno',
   '$clientid',
   '1',
   '$depaddone' ,
   '$depaddtwo' ,
   '$depaddthree' ,
   '$depaddfour' ,
   '$depaddfive' ,
   '$depaddsix' ,
   '$newdepname' ) ", $conn_id )  or die(mysql_error()); 
   $newdepid=mysql_insert_id();  
   mysql_query("UNLOCK TABLES", $conn_id);   
$infotext.= ' <br />Created new department '.$newdepid;

$pagetext.='<p>Created new Department </p>';



} // ends check for clientid and dep name
} // ends page=new department




    //////////////////     STARTS FAVOURITE ADDRESS

    if (($page=="editthisfavadr") or ( $page=="aftercheckaseditfav" ) or ( $page=='addaftercheckasnewfav')) {

if (isset($_POST['thisfavadrid'])) { $thisfavadrid=trim($_POST['thisfavadrid']); } else { $thisfavadrid=''; }
if (isset($_POST['favadrft'])) { $favadrft=trim($_POST['favadrft']); } else { $favadrft=''; }
if (isset($_POST['favadrpc'])) { $favadrpc=trim($_POST['favadrpc']); } else { $favadrpc=''; }
if (isset($_POST['favadrisactive'])) { $favadrisactive=trim($_POST['favadrisactive']); } else { $favadrisactive=''; } 
if (isset($_POST['favadrclient'])) { $favadrclient=trim($_POST['favadrclient']); } else { $favadrclient=''; }
if (isset($_POST['favadrcomments'])) { $favadrcomments=trim($_POST['favadrcomments']); } else { $favadrcomments=''; }

if ($favadrisactive=='on') { $favadrisactive='1'; }

$sPattern = ' /\s*/m'; 
$sReplace = '';
$favadrpc=preg_replace( $sPattern, $sReplace, $favadrpc );
$favadrpct=substr($favadrpc, 0, -3);  
$favadrpc=$favadrpct.' '.substr($favadrpc, -3); // 'ies' 

if (isset($_POST["favusr1"])) { $favusr1=trim($_POST['favusr1']); } else { $favusr1='0'; } if ($favusr1=='on') { $favusr1='1'; }
if (isset($_POST["favusr2"])) { $favusr2=trim($_POST['favusr2']); } else { $favusr2='0'; } if ($favusr2=='on') { $favusr2='1'; }
if (isset($_POST["favusr3"])) { $favusr3=trim($_POST['favusr3']); } else { $favusr3='0'; } if ($favusr3=='on') { $favusr3='1'; }
if (isset($_POST["favusr4"])) { $favusr4=trim($_POST['favusr4']); } else { $favusr4='0'; } if ($favusr4=='on') { $favusr4='1'; }
if (isset($_POST["favusr5"])) { $favusr5=trim($_POST['favusr5']); } else { $favusr5='0'; } if ($favusr5=='on') { $favusr5='1'; }
if (isset($_POST["favusr6"])) { $favusr6=trim($_POST['favusr6']); } else { $favusr6='0'; } if ($favusr6=='on') { $favusr6='1'; }
if (isset($_POST["favusr7"])) { $favusr7=trim($_POST['favusr7']); } else { $favusr7='0'; } if ($favusr7=='on') { $favusr7='1'; }
if (isset($_POST["favusr8"])) { $favusr8=trim($_POST['favusr8']); } else { $favusr8='0'; } if ($favusr8=='on') { $favusr8='1'; }
if (isset($_POST["favusr9"])) { $favusr9=trim($_POST['favusr9']); } else { $favusr9='0'; } if ($favusr9=='on') { $favusr9='1'; }
if (isset($_POST["favusr10"])) { $favusr10=trim($_POST['favusr10']); } else { $favusr10='0'; } if ($favusr10=='on') { $favusr10='1'; }
if (isset($_POST["favusr11"])) { $favusr11=trim($_POST['favusr11']); } else { $favusr11='0'; } if ($favusr11=='on') { $favusr11='1'; }
if (isset($_POST["favusr12"])) { $favusr12=trim($_POST['favusr12']); } else { $favusr12='0'; } if ($favusr12=='on') { $favusr12='1'; }
if (isset($_POST["favusr13"])) { $favusr13=trim($_POST['favusr13']); } else { $favusr13='0'; } if ($favusr13=='on') { $favusr13='1'; }
if (isset($_POST["favusr14"])) { $favusr14=trim($_POST['favusr14']); } else { $favusr14='0'; } if ($favusr14=='on') { $favusr14='1'; }
if (isset($_POST["favusr15"])) { $favusr15=trim($_POST['favusr15']); } else { $favusr15='0'; } if ($favusr15=='on') { $favusr15='1'; }
if (isset($_POST["favusr16"])) { $favusr16=trim($_POST['favusr16']); } else { $favusr16='0'; } if ($favusr16=='on') { $favusr16='1'; }
if (isset($_POST["favusr17"])) { $favusr17=trim($_POST['favusr17']); } else { $favusr17='0'; } if ($favusr17=='on') { $favusr17='1'; }
if (isset($_POST["favusr18"])) { $favusr18=trim($_POST['favusr18']); } else { $favusr18='0'; } if ($favusr18=='on') { $favusr18='1'; }
if (isset($_POST["favusr19"])) { $favusr19=trim($_POST['favusr19']); } else { $favusr19='0'; } if ($favusr19=='on') { $favusr19='1'; }
if (isset($_POST["favusr20"])) { $favusr20=trim($_POST['favusr20']); } else { $favusr20='0'; } if ($favusr20=='on') { $favusr20='1'; }


if (isset($_POST['oldfavadrft'])) { $oldfavadrft=trim($_POST['oldfavadrft']); } else { $oldfavadrft=''; }
if (isset($_POST['oldfavadrpc'])) { $oldfavadrpc=trim($_POST['oldfavadrpc']); } else { $oldfavadrpc=''; }
if (isset($_POST['cojmid'])) { $cojmid=trim($_POST['cojmid']); } else { $cojmid=''; }




if ($page=='aftercheckaseditfav') {

// $infotext.='<br />1495';
if (($favadrclient) and ($thisfavadrid<>'')) {
$sql = "UPDATE cojm_favadr SET 
favadrft=(UPPER('$favadrft')) , 
favadrpc=(UPPER('$favadrpc'))  
WHERE favadrid='$thisfavadrid' LIMIT 1"; 
$result = mysql_query($sql, $conn_id);
if ($result) { 
$infotext.="<br />Success!"; 
$pagetext.="<p>Details updated for ".$favadrft.".</p>"; 
} else {
$infotext.=mysql_error()." An error occured during update!<br>"; 
$alerttext.="<p>An error occured during update!</p>"; 
} // ends check for result / alert txt

} // ends quick edit from order via redir.php

}  // ends if ($page=='aftercheckaseditfav') 



if ($page=='editthisfavadr') {

$infotext.='<br />Editing Favourite details';
if (($favadrclient) and ($thisfavadrid<>'')) {
$sql = "UPDATE cojm_favadr SET 
favadrft=(UPPER('$favadrft')) , 
favadrpc=(UPPER('$favadrpc')) ,
favadrclient='$favadrclient' ,
favadrisactive='$favadrisactive' ,
favadrcomments=(UPPER('$favadrcomments')),
favusr1='$favusr1' , 
favusr2='$favusr2' , 
favusr3='$favusr3' , 
favusr4='$favusr4' , 
favusr5='$favusr5' , 
favusr6='$favusr6' , 
favusr7='$favusr7' , 
favusr8='$favusr8' , 
favusr9='$favusr9' , 
favusr10='$favusr10' , 
favusr11='$favusr11' , 
favusr12='$favusr12' , 
favusr13='$favusr13' , 
favusr14='$favusr14' , 
favusr15='$favusr15' , 
favusr16='$favusr16' , 
favusr17='$favusr17' , 
favusr18='$favusr18' , 
favusr19='$favusr19' , 
favusr20='$favusr20'  
WHERE favadrid='$thisfavadrid' LIMIT 1"; 
$result = mysql_query($sql, $conn_id);
if ($result){ 
$infotext.="<br />Success!"; 
$pagetext.="<p>Details updated for ".$favadrft.".</p>"; 
} else { 
$infotext.=mysql_error()." An error occured during update!<br>"; 
$alerttext.="<p>An error occured during update!</p>"; 
} // ends check for result or error
 
} // ends check for client AND favadrid
 
} // ends ($page=='editthisfavadr')





// CHECK FOR COLLECT ADDRESS

if (($oldfavadrft) and ($oldfavadrpc)) {
 $sql = "SELECT ID FROM Orders WHERE status < '86' 
 AND CollectPC = '".$oldfavadrpc."' 
 AND fromfreeaddress = '".$oldfavadrft."' ";
$sql_result = mysql_query($sql,$conn_id); $sumtot=mysql_affected_rows(); 
// $infotext.='<br /> 1574 '.$sumtot.' row found '.$sql;
 if ($sumtot>'0')  { while ($chngrow = mysql_fetch_array($sql_result)) { extract($chngrow); 
 $chngid=$chngrow['ID'];
 $sql = "UPDATE Orders SET 
fromfreeaddress=(UPPER('$favadrft')), 
CollectPC=(UPPER('$favadrpc')) 
WHERE ID='$chngid' LIMIT 1"; $result = mysql_query($sql, $conn_id);
if ($result){ // $infotext.="<br />1616 updated"; 
} else { // starts error check
$infotext.="<br /><strong>1619 An error occured during updating postcodes!</strong>"; 
$alerttext.="<p>Error 1620 occured during updating ".$sql."</p>"; 
} // ends error check
} // ends loop for jobs matching ft, pc and status
$pagetext.='<p>'.$sumtot.' Future Collection Adresses Changed </p>';
} // total is more than 0 for matching addresses
} // ends check to see if (($oldfavadrft) and ($oldfavadrpc)) {



// CHECK FOR DELIVERY ADDRESS

if (($oldfavadrft) and ($oldfavadrpc)) {
 $sql = "SELECT ID FROM Orders WHERE status < '86' 
 AND ShipPC = '".$oldfavadrpc."' 
 AND tofreeaddress = '".$oldfavadrft."' ";
$sql_result = mysql_query($sql,$conn_id); $sumtot=mysql_affected_rows(); 
// $infotext.='<br /> 1600 '.$sumtot.' row found '.$sql;
 if ($sumtot>'0')  { while ($chngrow = mysql_fetch_array($sql_result)) { extract($chngrow); 
 $chngid=$chngrow['ID'];
 $sql = "UPDATE Orders SET 
tofreeaddress=(UPPER('$favadrft')), 
ShipPC=(UPPER('$favadrpc')) 
WHERE ID='$chngid' LIMIT 1"; $result = mysql_query($sql, $conn_id);
if ($result){ // $infotext.="<br />1616 updated"; 
} else { // starts error check
$infotext.="<br /><strong>1609 An error occured during updating postcodes!</strong>"; 
$alerttext.="<p>Error 1610 occured during updating ".$sql."</p>"; 
} // ends error check
} // ends loop for jobs matching ft, pc and status
$pagetext.='<p>'.$sumtot.' Future Delivery Adresses Changed </p>';
} // total is more than 0 for matching addresses
} // ends check to see if (($oldfavadrft) and ($oldfavadrpc)) {





// CHECK FOR enrpc's

if (($oldfavadrft) and ($oldfavadrpc)) {
$i='1'; while ($i<'21') {
 $sql = "SELECT ID FROM Orders WHERE status < '86' 
 AND enrpc".$i." = '".$oldfavadrpc."' 
 AND enrft".$i." = '".$oldfavadrft."' ";
$sql_result = mysql_query($sql,$conn_id); $sumtot=mysql_affected_rows(); 
// $infotext.='<br /> 1628 '.$sumtot.' row found '.$sql;
 if ($sumtot>'0')  { while ($chngrow = mysql_fetch_array($sql_result)) { extract($chngrow); 
 $chngid=$chngrow['ID'];
 $sql = "UPDATE Orders SET 
enrft".$i."=(UPPER('$favadrft')), 
enrpc".$i."=(UPPER('$favadrpc')) 
WHERE ID='$chngid' LIMIT 1"; $result = mysql_query($sql, $conn_id);
if ($result){ // $infotext.="<br />1616 updated"; 
} else { // starts error check
$infotext.="<br /><strong>1641 An error occured during updating postcodes!</strong>"; 
$alerttext.="<p>Error 1642 occured during updating ".$sql."</p>"; 
} // ends error check
} // ends loop for jobs matching ft, pc and status
$pagetext.='<p>'.$sumtot.' Future enroute address changed </p>';
} // total is more than 0 for matching addresses
$i++;
} // ends $i loop
} // ends check to see if (($oldfavadrft) and ($oldfavadrpc)) {










 


 if (($thisfavadrid=='') and ($favadrft)) {
 $infotext.='New Favourite'; 
   mysql_query("LOCK TABLES cojm_favadr WRITE", $conn_id);
   mysql_query("INSERT INTO cojm_favadr 
   (favadrclient, 
   favadrft, 
   favadrpc, 
   favadrisactive,
   favadrcomments
   ) VALUES (
   '$favadrclient',
   (UPPER('$favadrft')),
   (UPPER('$favadrpc')),
   '1',
   (UPPER('$favadrcomments'))   )
   ", $conn_id
   )  or die(mysql_error()); 
   $insertid=mysql_insert_id();  
mysql_query("UNLOCK TABLES", $conn_id);   


$infotext.="<br />New fav id ".$insertid.' '.$favadrft; 
$pagetext.="<p>New favourite added ".$favadrft.".</p>"; 

} // end new client and check companyname>0 



if ( $page=='addaftercheckasnewfav') {

 $infotext.='New Favourite 1665'; 

}





} // ends page=newfav(?) or page=quickeditfrom  or page=addaftercheckasnewfav



    // $infotext.='<br />1692 ';


    if ($page=='editnewfav') {

// $infotext.='<br />1698 ';

if (isset($_POST['fromfreeaddress'])) { $fromfreeaddress=(trim($_POST['fromfreeaddress'])); } else {  $fromfreeaddress=''; }
if (isset($_POST['CollectPC'])) { $collectpc=(trim($_POST['CollectPC'])); } else {  $collectpc=''; }
if (isset($_POST['clientorder'])) { $clientorder=(trim($_POST['clientorder'])); } else {  $clientorder=''; }
if (isset($_POST['id'])) { $id=(trim($_POST['id'])); } else {  $id=''; }

$fromfreeaddress=strtoupper ($fromfreeaddress);
$collectpc=strtoupper ($collectpc);

// $filename="order.php";
// $adminmenu='1';
// echo $clientorder;
// echo $fromfreeaddress;
// echo $collectpc;
// echo $id;
$favadrcomments='';

$sql = "SELECT * FROM cojm_favadr, Clients 
WHERE cojm_favadr.favadrclient= '$clientorder'
AND (( cojm_favadr.favadrpc LIKE '%$collectpc%' ) OR ( cojm_favadr.favadrft LIKE '%$fromfreeaddress%'))
AND cojm_favadr.favadrisactive='1' 
AND cojm_favadr.favadrclient = Clients.CustomerID 
"; 

$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
$num_rows = mysql_num_rows($sql_result);

if ($num_rows>'0') { 

$sql = "SELECT * FROM cojm_favadr, Clients 
WHERE cojm_favadr.favadrclient= '$clientorder'
AND (( cojm_favadr.favadrpc LIKE '$collectpc' ) OR ( cojm_favadr.favadrft LIKE '$fromfreeaddress'))
AND cojm_favadr.favadrisactive='1' 
AND cojm_favadr.favadrclient = Clients.CustomerID 
"; $sql_result2 = mysql_query($sql,$conn_id)  or mysql_error(); 
$num_rows = mysql_num_rows($sql_result2);

$companyname='';
while ($avadrrow = mysql_fetch_array($sql_result2)) { extract($avadrrow); $companyname=$avadrrow['CompanyName']; } 



$alerttext.= ' <h3>There ';
if ($num_rows>'1') { $alerttext.= 'are'; } else { $alerttext.= 'is'; }
$alerttext.= ' already '.$num_rows.' favourite'; if ($num_rows>'1') { $alerttext.= 's'; }

$alerttext.= ' for '.$companyname.' with similar details</h3><br />';



$alerttext.= '
<table class="acc"><tr>
<th>Address</th>
<th>Postcode</th>
<th></th>
</tr><tr><td>'.$fromfreeaddress.'</td>
<td>'.$collectpc.'</td><td>
<form action="order.php?id='.$id.'" method="post"> 
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="page" value="addaftercheckasnewfav" >
<input type="hidden" name="favadrclient" value="'.$clientorder.'" />
<input type="hidden" name="favadrft" value="'.$fromfreeaddress.'" />
<input type="hidden" name="favadrpc" value="'.$collectpc.'" />
<input type="hidden" name="cojmid" value="'.$id.'" />
<input type="hidden" name="id" value="'.$id.'" />
<button type="submit" >Add as new favourite</button></form>
</td></tr>';


while ($favadrrow = mysql_fetch_array($sql_result)) { extract($favadrrow);
$alerttext.= '<tr><td>'.$favadrrow['favadrft'].'</td><td>'.$favadrrow['favadrpc'].'</td><td>
<form action="order.php?id='.$id.'" method="post" >
<input type="hidden" name="formbirthday" value="'. date("U") .'">
<input type="hidden" name="page" value="aftercheckaseditfav" >
<input type="hidden" name="favadrft" value="'.$fromfreeaddress.'" />
<input type="hidden" name="oldfavadrft" value="'.$favadrrow['favadrft'].'" />
<input type="hidden" name="oldfavadrpc" value="'.$favadrrow['favadrpc'].'" />
<input type="hidden" name="favadrpc" value="'.$collectpc.'" />
<input type="hidden" name="favadrclient" value="'.$clientorder.'" />
<input type="hidden" name="cojmid" value="'.$id.'" />
<input type="hidden" name="thisfavadrid" value="'.$favadrrow['favadrid'].'" />
<button type="submit" >Add new details to this existing location</button></form>
</td></tr>';
}

$alerttext.= '</table><br />';
// echo '</div></body></html>';

} else {

// echo '<br />New Favourite'; 
   mysql_query("LOCK TABLES cojm_favadr WRITE", $conn_id);
   mysql_query("INSERT INTO cojm_favadr 
   (favadrclient, 
   favadrft, 
   favadrpc, 
   favadrisactive,
   favadrcomments
   ) VALUES (
   '$clientorder',
   (UPPER('$fromfreeaddress')),
   (UPPER('$collectpc')),
   '1',
   (UPPER('$favadrcomments'))   )
   ", $conn_id
   )  or die(mysql_error()); $newfavid=mysql_insert_id();  
mysql_query("UNLOCK TABLES", $conn_id);   


$pagetext.="<p>Favourite added.</p>"; 


// echo '<br /> New fav added with id '.$newfavid;

// header('Location: '.$globalprefrow['httproots'].'/cojm/live/order.php?id='.$id); exit();

} // ends add new

} // ends $page==editnewfav




    if ($page=="editexpense") {

$infotext.='<p><strong>Editing expense details</strong></p>';




$expenseref=trim($_POST['expenseref']);
$expensecost=trim($_POST['expensecost']);
$expensevat=trim($_POST['expensevat']);
$expensecode=trim($_POST['expensecode']);
$whoto=trim(htmlspecialchars($_POST['whoto']));
$description=trim(htmlspecialchars($_POST['description']));
$cyclistref=trim($_POST['cyclistref']);
$paid=trim($_POST['paid']);
$chequeref=trim(htmlspecialchars($_POST['chequeref']));
$paymentmethod=trim($_POST['paymentmethod']);

 $whoto = str_replace("'", "&#39;", "$whoto");
  $description = str_replace("'", "&#39;", "$description");
  $chequeref = str_replace("'", "&#39;", "$chequeref");

if (isset($_POST['newfromoldexpense'])) { $newfromoldexpense=trim($_POST['newfromoldexpense']); } else { $newfromoldexpense=''; }


if (isset($_POST['expensedate'])) { $expensedate=trim($_POST['expensedate']); } else { $expensedate=''; }

if ($expensedate) {
$expensedate = str_replace("/", ":", "$expensedate", $count);
$expensedate = str_replace(",", ":", "$expensedate", $count);
$expensedate = str_replace("-", ":", "$expensedate", $count);
$temp_ar=explode(":",$expensedate); 
$startday=$temp_ar[0]; 
$startmonth=$temp_ar[1]; 
$startyear=$temp_ar[2];
$expensedate=date("Y-m-d H:i:s", mktime(01, 01, 01, $startmonth, $startday, $startyear));
}
 
if ($paid=='Yes') { $paid=1;}
if ($paid=='No') { $paid=0;}
if ($paymentmethod=='expc1') { $expc1=$expensecost; } else { $expc1=''; }
if ($paymentmethod=='expc2') { $expc2=$expensecost; } else { $expc2=''; }
if ($paymentmethod=='expc3') { $expc3=$expensecost; } else { $expc3=''; }
if ($paymentmethod=='expc4') { $expc4=$expensecost; } else { $expc4=''; }
if ($paymentmethod=='expc5') { $expc5=$expensecost; } else { $expc5=''; }
if ($paymentmethod=='expc6') { $expc6=$expensecost; } else { $expc6=''; }

if ($expenseref) {
if ($newfromoldexpense) {

$description='Created from ref '.$expenseref.' '.$description;

$infotext.='<br />New Expense '; 
   mysql_query("LOCK TABLES expenses WRITE", $conn_id);
   mysql_query("INSERT INTO expenses 
   (expensecost, expensevat, whoto, description, cyclistref, expensedate, expensecode, paid, expc2, expc3, expc1, expc4, expc5, expc6,
 chequeref) 
   
   VALUES
   ('$expensecost',
   '$expensevat',
   '$whoto',
   '$description',
   '$cyclistref', 
   '$expensedate', 
   '$expensecode',
   '$paid',
   '$expc2',
   '$expc3',
   '$expc1',
   '$expc4',
   '$expc5',
   '$expc6',
   '$chequeref' )
   ", $conn_id
   )  or die(mysql_error()); $expenseref=mysql_insert_id();  
mysql_query("UNLOCK TABLES", $conn_id);  



} else {
$sql = "UPDATE expenses SET expensecost='$expensecost' , expensevat='$expensevat' , whoto='$whoto' , description='$description' , cyclistref='$cyclistref' , 
expensedate='$expensedate' , expensecode='$expensecode' , paid='$paid' , expc2='$expc2' , expc3='$expc3' , 
expc1='$expc1' , expc4='$expc4' , expc5='$expc5' , expc6='$expc6' , chequeref='$chequeref'  
 WHERE expenseref='$expenseref' LIMIT 1"; 
  
 $result = mysql_query($sql, $conn_id);
if ($result){ $pagetext.="<p>Expense Updated</p>"; } else 

{ 
$infotext.=mysql_error()."<br />error occured during updating expense"; 
$alerttext.='<p>Error occured during updating expense</p>'; 

} 
 }}
 
if ($expenseref=='') { 
// $pagetext.='<p><b>New Expense</b></p>'; 
   mysql_query("LOCK TABLES expenses WRITE");
   mysql_query("INSERT INTO expenses 
   (expensecost, expensevat , whoto, description, cyclistref, expensedate, expensecode, paid, expc2, expc3, expc1, expc4, expc5, expc6,
 chequeref) 
   
   VALUES
   ('$expensecost',
   '$expensevat',
   '$whoto',
   '$description',
   '$cyclistref', 
   '$expensedate', 
   '$expensecode',
   '$paid',
   '$expc2',
   '$expc3',
   '$expc1',
   '$expc4',
   '$expc5',
   '$expc6',
   '$chequeref' )
   "
   )  or die(mysql_error()); $expenseref=mysql_insert_id();  
mysql_query("UNLOCK TABLES", $conn_id);   

 $pagetext.='<p>New Expense ref '.$expenseref.'</p>'; 
 $infotext.='<br />New Expense'; 

 
} // end new expense
} // ends page=editexpense





    if ($page=='editcorepricing') {
$infotext.='<br/>Editing distance pricing';

$i='21'; while ($i>0)  {

if (isset($_POST["chargedbybuildid$i"])) { 


$cbbname=trim($_POST["cbbname$i"]);
$cbbcost=trim($_POST["cbbcost$i"]);
$cbborder=trim($_POST["cbborder$i"]);
$cbbcomment=trim($_POST["cbbcomment$i"]);
$cbbmod=trim($_POST["cbbmod$i"]);


if (isset($_POST["cbbasap$i"])) { $cbbasap=trim($_POST["cbbasap$i"]); } else { $cbbasap="0"; }

if (isset($_POST["cbbcargo$i"])) { $cbbcargo=trim($_POST["cbbcargo$i"]); } else { $cbbcargo="0"; }



// $infotext.='<br />Found '.$i.' name : '.$cbbname.' order : '.$cbborder.' cost : '.$cbbcost.' mod : '.$cbbmod.' comment : '.$cbbcomment;  


if (isset($_POST["new$i"])) { $_POST["new$i"]=trim($_POST["new$i"]); } else { $_POST["new$i"]=''; }



if (trim($_POST["new$i"])=='') {




 $sql = "UPDATE chargedbybuild SET 
 cbbname='$cbbname', 
 cbbasap='$cbbasap', 
 cbbcargo='$cbbcargo' , 
 cbbmod='$cbbmod', 
 cbbcost='$cbbcost', 
 cbborder='$cbborder'
 WHERE chargedbybuildid = $i LIMIT 1"; 
 $result = mysql_query($sql, $conn_id);
 if ($result){ 
// $infotext.="<br>Updated individ cost "; 
} else { 
 $infotext.=mysql_error()."<br> <strong>An error occured during updating Core Pricing</strong>"; 
 $alerttext.="<p>Error occured during updating Core Pricing name ".$cbbname.'</p>'; 
}
} 



if ((trim($_POST["new$i"]))=='yes') { if ($cbbname) {

 $sql = "INSERT INTO chargedbybuild SET cbbname='$cbbname', cbbasap='$cbbasap', cbbcargo='$cbbcargo' , cbbmod='$cbbmod', cbbcost='$cbbcost', cbborder='$cbborder', cbbcomment='$cbbcomment', chargedbybuildid = '$i'"; 
 $result = mysql_query($sql, $conn_id);
 if ($result){ 
// $infotext.="<br>Updated individ cost "; 
 } else { 
 $infotext.=mysql_error()."<br> <strong>An error occured during updating Core Pricing</strong>"; 
 $alerttext.="<p>Error occured during updating Distance Pricing</p>"; 
 }
} 
}


}
$i=$i-1;
}

$infotext.= '<br />Updated Pricing'.$sql;;
$pagetext.= '<p>Updated Pricing</p>';

} // ends page=editcorepricing





    // EDITS INVOICE COMMENT

    if ($page=='editinvcomment') {


if (isset( $_POST['ref'])) { $invoiceref=$_POST['ref']; } else { $invoiceref=''; }
if (isset($_POST['invcomments'])) { $invcomments=$_POST['invcomments']; } else { $invcomments=''; }

if ($invoiceref) {

$sql = " UPDATE `invoicing` SET `invcomments` = '$invcomments' WHERE ref='$invoiceref'";	

$result = mysql_query($sql, $conn_id);
	
if ($result) {		
$pagetext.='<p>Updated comments for invoice ref '.$invoiceref.'</p>';
$infotext.='<br /> Invoice '.$invoiceref.' comment edited to '.$invcomments;

} else { 
$alerttext.='<br />Unable to edit invoice comment<br />'; 
$infotext.='<br />Unable to edit invoice comment<br />'.$sql;
} // ends invoice dtabase changed

} // checks for invoice ref
 
} // finishes page= editinv comment




    if ($page=='markinvpaid') {


// sets vars

if (isset( $_POST['ref'])) { $invoiceref=$_POST['ref']; } else { $invoiceref=''; }
if (isset($_POST['invmethod'])) { $invmethod=$_POST['invmethod']; } else { $invmethod=''; }


if (isset($_POST['invoicedate'])) { 

$invoicedate=trim($_POST['invoicedate']); 
$invoicedate = str_replace("/", ":", "$invoicedate", $count);
$invoicedate = str_replace(",", ":", "$invoicedate", $count);
$invoicedate = str_replace("-", ":", "$invoicedate", $count);
$temp_ar=explode(":",$invoicedate); 
$startday=$temp_ar[0]; 
$startmonth=$temp_ar[1]; 
$startyear=$temp_ar[2];

$invoicedate=date("Y-m-d 23:59:59", mktime(01, 01, 01, $startmonth, $startday, $startyear));


} else { $invoicedate=''; }
 
 
 
 
 // changes invoice after a couple of checks
 
if ($invmethod) { // echo "<h4>Invoice method field found</h4>" . $invmethod . " " . $datepassed . " " . $invoiceref ;
if ($invoiceref) {



$sql = "SELECT cost from invoicing WHERE ref=$invoiceref ";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 

$temp=mysql_affected_rows();

// $infotext.='<br />'.$temp.' cost selected with sql : '.$sql.' with result '.$sql_result;

while ($row = mysql_fetch_array($sql_result)) {
     extract($row); }

// echo $datepassed.' '. $invmethod . ' ' . $cost . ' ' . $invoiceref; 

$sql="UPDATE `invoicing` SET `paydate` = '$invoicedate', `$invmethod` = '$cost' WHERE CONCAT( `invoicing`.`ref` ) =$invoiceref ";
$result = mysql_query($sql, $conn_id);

$temp=mysql_affected_rows();


if ($temp>0){ 

$pagetext.='<p><strong> Updated invoice ref '.$invoiceref."</strong>, paid via ".$invmethod.'</p>'; } else { 
$alerttext.='<br />Unable to change invoice<br />'; 
$infotext.='<br />Unable to change invoice<br />'.$sql;

}


$sql = "UPDATE Orders SET status='120' WHERE invoiceref='$invoiceref'"; 
$result = mysql_query($sql, $conn_id);
$temp=mysql_affected_rows();
if ($result){ $pagetext.= '<p>
				<strong> Updated '.$temp.'</strong> jobs<p>';
$auditsql='';				
				} else { 

$alerttext.= '
<br /><strong>An error occured during updating individual jobs,</strong>
<br />Please contact COJM ASAP with the details of what you were attempting to do.'; 


$infotext.='<br /><strong>An error occured during updating individual jobs,</strong>';

} // ends updating individ jobs

} // checks for invoice ref


} else { $alerttext.='<p> No payment method selected </p>'; }
 
 
 
 
} // finishes page= invoice paid





    if ($page=="editinvchase") {

$infotext.=' <br />Editing invoice chase ';


$ref=trim($_POST['ref']);

$dateshift=trim($_POST['chasedate']);
$duedate= date("Y-m-d H:i:s" );
$temp_ar=explode("-",$duedate); $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { 
$temptime_ar[0] = 0; $temptime_ar[1] = 0; $temptime_ar[2] = 0; }
$day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; 
$second='00';
$duedate= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year));
$chasetype=$_POST['invchasetype'];


if ($dateshift==69) { $duedate=''; $infotext.='<br />Date cleared'; $pagetext.='<p>Date cleared, chase removed</p>';}


if ($chasetype=='1') {
$infotext.= "<br />First time chased";
$sql="UPDATE `invoicing` SET `chasedate` = '$duedate' WHERE CONCAT( `invoicing`.`ref` ) =$ref ";
$result = mysql_query($sql, $conn_id);
$infotext.="<br />". mysql_affected_rows().' invoice affected.<br>';

if ($result) {
$pagetext.='<p>First Time Invoice Chased</p>';
}
}

if ($chasetype=='2') {
$infotext.= "<br />Second time chased";
$sql="UPDATE `invoicing` SET `chasedate2` = '$duedate' WHERE CONCAT( `invoicing`.`ref` ) =$ref";
$result = mysql_query($sql, $conn_id);
$infotext.="<br />". mysql_affected_rows().' invoice affected.<br>';

if ($result) {
$pagetext.='<p>Second Time Invoice Chased</p>';
}

}

if ($chasetype=='3') {
$infotext.= "<br />Third time chased<br>";
$sql="UPDATE `invoicing` SET `chasedate3` = '$duedate' WHERE CONCAT( `invoicing`.`ref` ) =$ref";
$result = mysql_query($sql, $conn_id);
$infotext.="<br />". mysql_affected_rows().' invoice affected.<br>';


if ($result) {
$pagetext.='<p>Third Chased Changed</p>';
}

}


} // ends page = edit chase invoice





    if ($page=='deleteinv') {


if (isset( $_POST['ref'])) { $invoiceref=$_POST['ref']; } else { $invoiceref=''; }

 
if ($invoiceref) {


// echo $datepassed.' '. $invmethod . ' ' . $cost . ' ' . $invoiceref; 

$sql = "DELETE from invoicing WHERE ref='$invoiceref'";	

// $sql="UPDATE `invoicing` SET `paydate` = '$invoicedate', `$invmethod` = '$cost' WHERE CONCAT( `invoicing`.`ref` ) =$invoiceref ";
$result = mysql_query($sql, $conn_id);

$temp=mysql_affected_rows();


if ($temp>'0'){
$alerttext.='<p><strong> Deleted invoice ref '.$invoiceref.'</strong></p>'; 
$infotext.='<br /> Invoice Deleted ';
} else { 
$alerttext.='<br />Unable to delete invoice<br />'; 
$infotext.='<br />Unable to delete invoice<br />'.$sql;
} // ends invoice dtabase changed






   $dtsql = "SELECT ID FROM Orders 
   WHERE (`Orders`.`invoiceref` ='$invoiceref' )  ";
$dtsql_result = mysql_query($dtsql,$conn_id)  or mysql_error(); 
while ($dtrow = mysql_fetch_array($dtsql_result)) { extract($dtrow); 



$dtid=$dtrow['ID'];

$browser=$_SERVER["HTTP_USER_AGENT"];

$sql = "UPDATE Orders SET status='100', invoiceref='' WHERE ID='$dtid'"; 

$result = mysql_query($sql, $conn_id);

$temp=mysql_affected_rows();

if ($result){





 $newpoint="INSERT INTO cojm_audit (auditid,audituser,auditorderid,auditpage,auditfilename,auditmobdevice,
 auditbrowser,audittext,auditcjtime,auditpagetime,auditmidtime,auditinfotext)   
 VALUES ('','$cyclistid','$dtid','$page','view_all_invoices.php','$mobdevice',
 '$browser','Invoice ref $invoiceref removed','','','','Invoice removed from job')";
 mysql_query($newpoint, $conn_id) or mysql_error(); $newauditid=mysql_insert_id();
 if (mysql_error()) { $alerttext.= '<div class="moreinfotext"><h1> Problem saving audit log </h1></div>';


$infotext.= '<br />Problem saving audit log on remove invoice details'.$newpoint;


 } // ends error




} else { // job update fail
$alerttext.= '<br /><strong>An error occured during updating individual job, check audit log. </strong>'; 
$infotext.='<br /><strong>An error occured during updating individual job '.$dtid.' '.$sql.' </strong>';
} // ends updating individ jobs




} // ends row extract for individual job


} // checks for invoice ref

} // ends page=deleteinv




    if ($page=='invnotpaid') {

if (isset( $_POST['ref'])) { $invoiceref=$_POST['ref']; } else { $invoiceref=''; }

 
if ($invoiceref) {


$sql="UPDATE `invoicing` SET `paydate` = '', `cash` = '', `cheque` = '', `bacs` = '', `paypal` = '' WHERE CONCAT( `invoicing`.`ref` ) =$invoiceref";
$result = mysql_query($sql, $conn_id);


if ($result) {

$infotext.= "<br />Removed invoice payment details<br>";
$infotext.="<br />". mysql_affected_rows().' invoice affected.<br>';
$pagetext.='<p>Payment details removed on Invoice '.$invoiceref.'</p>';

} else {

$infotext.= "<br />Unable to Remove invoice payment details<br>";
$infotext.="<br />". mysql_affected_rows().' invoice affected, ref '.$invoiceref.'<br>';
$alerttext.='<p>Payment details NOT removed on Invoice ref '.$invoiceref.'</p>';



} // ends check for type of result

} // ends check for invoice ref

} // ends page = invnotpaid





    if ($page=='deletegps') {
        if ((isset($_POST['newcyclist'])) and (isset($_POST['gpsdeletedate']))) {
            $infotext.='<br />Delete Tracking Positions ' .$_POST['newcyclist'].'<br />';
            $thisCyclistID = $_POST['newcyclist'];
            $stmt = $dbh->prepare("SELECT trackerid FROM Cyclist WHERE CyclistID=?");
            $stmt->execute([$thisCyclistID]);
            $device_key = $stmt->fetchColumn();
            $infotext.= ' thiscyclistid= '. $thisCyclistID .' dev key is '.$device_key.'. ';
    
            if ($device_key>0) {
                // $infotext.='gpsdeletedate = '.$_POST['gpsdeletedate'];
        
                $startdate=$_POST['gpsdeletedate'];
                $startdate=$startdate.' 00:00:00';
                $infotext.='<br /> Posted Start date : '.$startdate; 
                $startdate=strtotime($startdate); 
                $infotext.='<br /> Start date : '.$startdate; 
        
        
                $finishdate=$_POST['gpsdeletedate'];
                $finishdate=$finishdate.' 23:59:59';
                $infotext.='<br /> Posted finish date : '.$finishdate; 
                $finishdate=strtotime($finishdate); 
                $infotext.='<br /> Finish date : '.$finishdate; 
        
                $sql="DELETE FROM instamapper WHERE device_key=".$device_key." AND timestamp > ".$startdate." AND timestamp < ".$finishdate;
                $result = mysql_query($sql, $conn_id);
        
                if (mysql_affected_rows()>'0') {
                    $alerttext.="". mysql_affected_rows().' tracking positions deleted.<br>';
                    $infotext.="". mysql_affected_rows().' tracking positions deleted.<br>';
        
                    $testfile="cache/jstrack/".date('Y/m', $startdate).'/'.date('Y_m_d', $startdate).'_'.$device_key.'.js';
                    $infotext.=" 2343 test file : ". $testfile.' <br />';
                    if (!file_exists($testfile)) {
                        $infotext.= ' <br /> cj 2349 Cache does not exist, no action needed. '.$testfile;
                    } else {
                        $infotext.=  ' <br /> cj 2351 Cache exists, needs deleting. '.$testfile;	
                        unlink($testfile);
                        if (file_exists($testfile)) {
                            $infotext.=  ' not deleted ';
                        }
                    }
        
                } else {
                    $pagetext.='Tracking data unchanged.';
                    $infotext.=' Tracking data unchanged.';
                }
            }
        }
    }


} // finishes epoch time





if ($page=='addtodb') { // new invoice

$clientname = mysql_result(mysql_query("SELECT CompanyName from Clients WHERE CustomerID='$clientid' LIMIT 1", $conn_id), 0);

$clientemailinv = mysql_result(mysql_query("SELECT invoiceEmailAddress from Clients WHERE CustomerID='$clientid' LIMIT 1", $conn_id), 0);
if ($clientemailinv) { $pagetext.= '<a href="../live/new_cojm_client.php?clientid='.$clientid.'">'.$clientname.'</a> Invoice Email : '.$clientemailinv;}



$clientemail = mysql_result(mysql_query("SELECT EmailAddress from Clients WHERE CustomerID='$clientid' LIMIT 1", $conn_id), 0);
if ($clientemail) { $pagetext.= '<br /><a href="../live/new_cojm_client.php?clientid='.$clientid.'">'.$clientname.'</a> General Email : '.$clientemail;}


if ($invoiceselectdep) {
$depname = mysql_result(mysql_query("SELECT depname from clientdep WHERE depnumber='$invoiceselectdep' LIMIT 1", $conn_id), 0);
$depemail = mysql_result(mysql_query("SELECT depemail from clientdep WHERE depnumber='$invoiceselectdep' LIMIT 1", $conn_id), 0);
if ($clientemail) { $pagetext.= '<br /><a href="../live/new_cojm_department.php?depid='.$invoiceselectdep.'">'.$depname.'</a> Department Email : '.$depemail;}
}

$existinginvref='';

   $dtsql = "SELECT * FROM invoicing 
   WHERE (`invoicing`.`ref` ='$newinvoiceref' )  ";
$dtsql_result = mysql_query($dtsql,$conn_id)  or mysql_error(); 
while ($dtrow = mysql_fetch_array($dtsql_result)) { extract($dtrow); 


if ($dtrow['ref']>0) { $existinginvref='1'; }  }




if ($existinginvref) {
    $pagetext.= '<h3>Not changing Invoice as existing invoice with same ref.';
    }
    else {
        if ($invoiceselectdep) {
            $sql = "SELECT * FROM Orders WHERE
            `Orders`.`orderdep` = '$invoiceselectdep'
            AND `Orders`.`collectiondate` >= '1'
            AND `Orders`.`collectiondate` <= '$collectionsuntildate'
            AND `Orders`.`status` < 110
            AND `Orders`.`status` > 90
            ORDER BY `Orders`.`collectiondate` ASC";
        } else {
            $sql = "SELECT * FROM Orders WHERE
            Orders.CustomerID = '$clientid' 
            AND `Orders`.`collectiondate` >= '1' 
            AND `Orders`.`collectiondate` <= '$collectionsuntildate'
            AND `Orders`.`status` < 110
            AND `Orders`.`status` > 90
            ORDER BY `Orders`.`collectiondate` ASC";
        }
        
        $sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
        $orderupdate='';
        
        // table loop
        while ($row = mysql_fetch_array($sql_result)) {
            extract($row);
            $updatequery = "UPDATE Orders SET status ='110', iscustomprice='1', invoiceref =$newinvoiceref WHERE ID=$row[ID]";
            mysql_query($updatequery,$conn_id) or die(mysql_error());
            $orderupdate++;
        } // ends individ job row extraction
        
        if ($orderupdate<'1') {
            $pagetext.= '<h1>No invoice details added to database as no jobs changed.</h1>';
        }
        
        $sql = "INSERT INTO invoicing ( ref, invdate1, created, client, cost, invvatcost,invdue, invoicedept, invcomments ) 
         VALUES ( '$newinvoiceref', '$invoicemysqldate' , now() , '$clientid' , '$tablecost', '$tablevatcost' , '$invoiceduemysqldate' , '$invoiceselectdep', '$invcomments' ) ";
        $result = mysql_query($sql, $conn_id);
        // $infotext= '<br />'.$sql;
        
        if ($result){
            $pagetext.= '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 1em;">
            <p>
            <form action="../live/view_all_invoices.php" method="post">
            New Invoice Ref : <button type="submit" >'.$newinvoiceref.'</button>
            <input type="hidden" name="viewtype" value="individualinvoice" >
            <input type="hidden" name="formbirthday" value="'. date("U") .'">
            <input type="hidden" name="page" value="" >
            <input type="hidden" name="ref" value="'.$newinvoiceref.'">
            </form>';
            
            if ($orderupdate)  {
                $pagetext.=' Updated status to invoiced in '.$orderupdate.' job';
                if ($orderupdate<>'1') {
                    $pagetext.= 's';
                }
            }

$pagetext.='</p></div></div><br />'; 
} else { 
$pagetext.= '<h1>An error occured during invoice database update <br />'.mysql_error().'</h1>'.$sql; }

// get last invoiced date
$sql = "SELECT lastinvoicedate from Clients WHERE CustomerID=$clientid";
$sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
if ($sql_result){ 
// $pdfheaderstring=$pdfheaderstring . "<h3>Found last invoice date</h3>"; 
} else { $pagetext.= "<h1>An error occured during selecting last client invoice date</h1>".$pdfheaderstring; }

while ($row = mysql_fetch_array($sql_result)) { extract($row); }
 $date4 = strtotime($lastinvoicedate);
 $date2 = strtotime($collectionsuntildate);
 $diffdate= ($date4 - $date2 );

if ( $diffdate < '0' ) { 

$sql="UPDATE `Clients` SET `lastinvoicedate` = '$collectionsuntildate' WHERE CONCAT( `Clients`.`CustomerID` ) =$clientid";
$result = mysql_query($sql, $conn_id);
if ($result){ 
// $pdfheaderstring=$pdfheaderstring . "<h1>Updated last invoice date</h1>"; 
} else { 
$pagetext.= "<h1>An error occured during client database update</h1>"; }

} // end of making sure client database latest invoice time is latest


} // ends check for existing invoice ref

} // ends page='addtodb' ( new invoice )



if ($page == "createnewfromexisting" ) {
        if ($oldid) {

        $cojmaction='recalcprice';

        if (isset($_POST['dateshift'])) { $dateshift=$_POST['dateshift']; }

        if (isset($_POST['currorsched'])) { $currorsched=trim($_POST['currorsched']); } else { $currorsched=''; }


        // $pagetext.='<p>New job created from ref <a href="order.php?id='.$oldid.'">'.$oldid.'</a></p>';
        $infotext.='<br />New job created from ref <a href="order.php?id='.$oldid.'">'.$oldid.'</a>';

        
        $query="SELECT * FROM Orders where ID = '$id' LIMIT 1";
        $result=mysql_query($query, $conn_id); $row=mysql_fetch_array($result);
        $status=$row['status']; $serviceid=$row['ServiceID']; $cost=$row['FreightCharge']; $vatcharge=$row['vatcharge']; 
        $timerequested=$row['jobrequestedtime']; $targetcollectiondate = $row['targetcollectiondate'];
        
        // echo "target collection date from row : "; echo $targetcollectiondate;
        // $targetcollectiondate=date("Y-m-d H:i:s");
        $temp_ar=explode("-",$targetcollectiondate); $spltime_ar=explode(" ",$temp_ar[2]); 
        $temptime_ar=explode(":",$spltime_ar[1]); 
        if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; 
        $temptime_ar[1] = 0; $temptime_ar[2] = 0; }
        $day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; $second = 00; 
        $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year));
        
        $collectiondate = $row['collectiondate'];
        $temp_ar=explode("-",$collectiondate); $spltime_ar=explode(" ",$temp_ar[2]); 
        $temptime_ar=explode(":",$spltime_ar[1]); 
        if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; $temptime_ar[1] = 0; 
        $temptime_ar[2] = 0; }
        $day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; $second = 00; 
        $collectiondate= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year));
        
        if ($row['duedate']>20) { 
            $duedate=$row['duedate'];
            $temp_ar=explode("-",$duedate); $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
            if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { 
                $temptime_ar[0] = 0; $temptime_ar[1] = 0; $temptime_ar[2] = 0; }
            $day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; 
            $duedate= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year));
        }
        
        if ($row['ShipDate']>20) { $deliverydate=$row['ShipDate'];
        $temp_ar=explode("-",$deliverydate); $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
        if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; $temptime_ar[1] = 0; 
        $temptime_ar[2] = 0; }
        $day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; $second = 00; 
        $deliverydate= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year)); }
        
        if ($row['deliveryworkingwindow']>20) { $temp_ar=explode("-",$row['deliveryworkingwindow']); 
        $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
        if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; $temptime_ar[1] = 0; 
        $temptime_ar[2] = 0; } $day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; 
        $second = 00; $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year)); }
        else { $deliveryworkingwindow=''; }
        
        if ($row['collectionworkingwindow']>20) { $row['collectionworkingwindow']; $temp_ar=explode("-",$row['collectionworkingwindow']); 
        $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
        if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; $temptime_ar[1] = 0; 
        $temptime_ar[2] = 0; } $day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; 
        $second = 00; $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year)); }
        else { $collectionworkingwindow=''; }
        
        
        if ($row['starttrackpause']>20) { $temp_ar=explode("-",$row['starttrackpause']); 
        $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
        if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; $temptime_ar[1] = 0; 
        $temptime_ar[2] = 0; } $day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; 
        $second = 00; $starttrackpause= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year)); }
        else { $starttrackpause=''; }
        
        if ($row['waitingstarttime']>20) { $waitingtime=$row['waitingstarttime']; $temp_ar=explode("-",$waitingtime); 
        $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
        if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; $temptime_ar[1] = 0; 
        $temptime_ar[2] = 0; } $day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; 
        $second = 00; $waitingtime= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year)); }
        else { $waitingtime=''; }
        
        if ($row['finishtrackpause']>20) { $finishtrackpause=$row['finishtrackpause']; $temp_ar=explode("-",$finishtrackpause); 
        $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
        if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) { $temptime_ar[0] = 0; $temptime_ar[1] = 0; 
        $temptime_ar[2] = 0; } $day=$spltime_ar[0]; $month=$temp_ar[1]; $year=$temp_ar[0]; $hour=$temptime_ar[0]; $minutes=$temptime_ar[1]; 
        $second = 00; $finishtrackpause= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year)); }
        else { $finishtrackpause=''; }
        
        if ($status <49  ){
        
            $nextactiondate = $targetcollectiondate; 
            $starttrackpause='';
            $finishtrackpause='';
        
        } 
        else {
            $nextactiondate = $duedate;
        }
        
        $numberitems=$row['numberitems'];
        $customerid=$row['CustomerID'];
        $shippc=$row['ShipPC'];
        $requestor=$row['requestor'];
        $orderdep=$row['orderdep'];
        $fromfreeaddress=$row['fromfreeaddress'];
        $tofreeaddress=$row['tofreeaddress'];
        $collectpc=$row['CollectPC'];
        $jobcomments = $row['jobcomments'];
        $privjobcomments = $row['privatejobcomments'];
        $clientjobreference = $row['clientjobreference'];
        $distance=$row['distance'];
        $waitingmins=$row['waitingmins'];
        $clientdiscount=$row['clientdiscount'];
        $co2saving=$row['co2saving'];
        $pm10saving=$row['pm10saving'];
        $opsmaparea=$row['opsmaparea'];
        $opsmapsubarea=$row['opsmapsubarea'];
        $cbb1=$row['cbb1'];
        $cbb2=$row['cbb2'];
        $cbb3=$row['cbb3'];
        $cbb4=$row['cbb4'];
        $cbb5=$row['cbb5'];
        $cbb6=$row['cbb6'];
        $cbb7=$row['cbb7'];
        $cbb8=$row['cbb8'];
        $cbb9=$row['cbb9'];
        $cbb10=$row['cbb10'];
        $cbb11=$row['cbb11'];
        $cbb12=$row['cbb12'];
        $cbb13=$row['cbb13'];
        $cbb14=$row['cbb14'];
        $cbb15=$row['cbb15'];
        $cbb16=$row['cbb16'];
        $cbb17=$row['cbb17'];
        $cbb18=$row['cbb18'];
        $cbb19=$row['cbb19'];
        $cbb20=$row['cbb20'];
        $cbbc1=$row['cbbc1'];
        $cbbc2=$row['cbbc2'];
        $cbbc3=$row['cbbc3'];
        $cbbc4=$row['cbbc4'];
        $cbbc5=$row['cbbc5'];
        $cbbc6=$row['cbbc6'];
        $cbbc7=$row['cbbc7'];
        $cbbc8=$row['cbbc8'];
        $cbbc9=$row['cbbc9'];
        $cbbc10=$row['cbbc10'];
        $cbbc11=$row['cbbc11'];
        $cbbc12=$row['cbbc12'];
        $cbbc13=$row['cbbc13'];
        $cbbc14=$row['cbbc14'];
        $cbbc15=$row['cbbc15'];
        $cbbc16=$row['cbbc16'];
        $cbbc17=$row['cbbc17'];
        $cbbc18=$row['cbbc18'];
        $cbbc19=$row['cbbc19'];
        $cbbc20=$row['cbbc20'];
        $enrpc1=$row['enrpc1'];
        $enrpc2=$row['enrpc2'];
        $enrpc3=$row['enrpc3'];
        $enrpc4=$row['enrpc4'];
        $enrpc5=$row['enrpc5'];
        $enrpc6=$row['enrpc6'];
        $enrpc7=$row['enrpc7'];
        $enrpc8=$row['enrpc8'];
        $enrpc9=$row['enrpc9'];
        $enrpc10=$row['enrpc10'];
        $enrpc11=$row['enrpc11'];
        $enrpc12=$row['enrpc12'];
        $enrpc13=$row['enrpc13'];
        $enrpc14=$row['enrpc14'];
        $enrpc15=$row['enrpc15'];
        $enrpc16=$row['enrpc16'];
        $enrpc17=$row['enrpc17'];
        $enrpc18=$row['enrpc18'];
        $enrpc19=$row['enrpc19'];
        $enrpc20=$row['enrpc20'];
        $enrft1=$row['enrft1'];
        $enrft2=$row['enrft2'];
        $enrft3=$row['enrft3'];
        $enrft4=$row['enrft4'];
        $enrft5=$row['enrft5'];
        $enrft6=$row['enrft6'];
        $enrft7=$row['enrft7'];
        $enrft8=$row['enrft8'];
        $enrft9=$row['enrft9'];
        $enrft10=$row['enrft10'];
        $enrft11=$row['enrft11'];
        $enrft12=$row['enrft12'];
        $enrft13=$row['enrft13'];
        $enrft14=$row['enrft14'];
        $enrft15=$row['enrft15'];
        $enrft16=$row['enrft16'];
        $enrft17=$row['enrft17'];
        $enrft18=$row['enrft18'];
        $enrft19=$row['enrft19'];
        $enrft20=$row['enrft20'];
        $iscustomprice=$row['iscustomprice'];
        
        if ($iscustomprice=='1') {
        
            $pagetext.='<p> New job is pricelocked</p>';
        }
        
        $handoverpostcode=$row['handoverpostcode'];
        $handoverCyclistID=$row['handoverCyclistID'];
        
        if ($status>'86') { $status='86'; }
        if ($status < "59" ){ $collectiondate=""; $waitingtime=""; };
        if ($status < "70" ){ $deliverydate=""; };
        
        
        $infotext.='<br />2469 Client Discount : '.$clientdiscount;
        
        // $infotext.=' <br>Currorsched='.$currorsched;
        
        if ($currorsched=='unsched') {
        
        $status='30';
        $collectiondate='';
        $deliverydate='';
        $nextactiondate=$targetcollectiondate;
        }
        
        
        
        
        
        
        
        
        
        // actually create new from existing
        
        $cyclist=$row['CyclistID'];
        $autostartchain=$row['autostartchain'];
        
        if ($id) {

   mysql_query("LOCK TABLES Orders WRITE", $conn_id);
   mysql_query("INSERT INTO Orders 
   (ID,
   ts,
   CustomerID,
   OrderDate, 
   CollectPC, 
   ServiceID,
   ShipPC,
   numberitems, 
   duedate, 
   ShipDate,
   status,
   targetcollectiondate,
   jobrequestedtime,
   jobcomments,
   privatejobcomments,
   nextactiondate,
   collectiondate,
   FreightCharge,
   vatcharge,
   CyclistID,
   deliveryworkingwindow,
   collectionworkingwindow,
   starttrackpause,
   finishtrackpause,
   waitingstarttime,
   requestor,  
   orderdep,
   fromfreeaddress,
   tofreeaddress,
   clientjobreference,
   distance,
   clientdiscount,
   waitingmins,
   cbb1,
   cbb2,
   cbb3,
   cbb4,
   cbb5,
   cbb6,
   cbb7,
   cbb8,
   cbb9,
   cbb10,
   cbb11,
   cbb12,
   cbb13,
   cbb14,
   cbb15,
   cbb16,
   cbb17,
   cbb18,
   cbb19,
   cbb20,
   cbbc1,
   cbbc2,
   cbbc3,
   cbbc4,
   cbbc5,
   cbbc6,
   cbbc7,
   cbbc8,
   cbbc9,
   cbbc10,
   cbbc11,
   cbbc12,
   cbbc13,
   cbbc14,
   cbbc15,
   cbbc16,
   cbbc17,
   cbbc18,
   cbbc19,
   cbbc20,
   enrpc1,
   enrpc2,
   enrpc3,
   enrpc4,
   enrpc5,
   enrpc6,
   enrpc7,
   enrpc8,
   enrpc9,
   enrpc10,
   enrpc11,
   enrpc12,
   enrpc13,
   enrpc14,
   enrpc15,
   enrpc16,
   enrpc17,
   enrpc18,
   enrpc19,
   enrpc20,
   enrft1,
   enrft2,
   enrft3,
   enrft4,
   enrft5,
   enrft6,
   enrft7,
   enrft8,
   enrft9,
   enrft10,
   enrft11,
   enrft12,
   enrft13,
   enrft14,
   enrft15,
   enrft16,
   enrft17,
   enrft18,
   enrft19,
   enrft20,
   opsmaparea,
opsmapsubarea,
   iscustomprice,
   handoverpostcode,
   handoverCyclistID,
   autostartchain,
   co2saving,
   pm10saving
    ) 
   VALUES
   ('',
   now(),
   '$customerid',
   now(), 
   '$collectpc', 
   '$serviceid',
   '$shippc',
   '$numberitems',
   '$duedate',
   '$deliverydate',
   '$status',
   '$targetcollectiondate',
     now(),
   (UPPER('$jobcomments')),
   (UPPER('$privjobcomments')),
   '$nextactiondate',
   '$collectiondate',
   '$cost',
   '$vatcharge',
   '$cyclist',
   '$deliveryworkingwindow',
   '$collectionworkingwindow',
   '$starttrackpause',
   '$finishtrackpause',
   '$waitingtime',
   (UPPER('$requestor')),
   '$orderdep',
   (UPPER('$fromfreeaddress')),
   (UPPER('$tofreeaddress')),
   (UPPER('$clientjobreference')),
   '$distance',
   '$clientdiscount',
   '$waitingmins',
   '$cbb1',
   '$cbb2',
   '$cbb3',
   '$cbb4',
   '$cbb5',
   '$cbb6',
   '$cbb7',
   '$cbb8',
   '$cbb9',
   '$cbb10',
   '$cbb11',
   '$cbb12',
   '$cbb13',
   '$cbb14',
   '$cbb15',
   '$cbb16',
   '$cbb17',
   '$cbb18',
   '$cbb19',
   '$cbb20',
   '$cbbc1',
   '$cbbc2',
   '$cbbc3',
   '$cbbc4',
   '$cbbc5',
   '$cbbc6',
   '$cbbc7',
   '$cbbc8',
   '$cbbc9',
   '$cbbc10',
   '$cbbc11',
   '$cbbc12',
   '$cbbc13',
   '$cbbc14',
   '$cbbc15',
   '$cbbc16',
   '$cbbc17',
   '$cbbc18',
   '$cbbc19',
   '$cbbc20',
   (UPPER('$enrpc1')),
   (UPPER('$enrpc2')),
   (UPPER('$enrpc3')),
   (UPPER('$enrpc4')),
   (UPPER('$enrpc5')),
   (UPPER('$enrpc6')),
   (UPPER('$enrpc7')),
   (UPPER('$enrpc8')),
   (UPPER('$enrpc9')),
   (UPPER('$enrpc10')),
   (UPPER('$enrpc11')),
   (UPPER('$enrpc12')),
   (UPPER('$enrpc13')),
   (UPPER('$enrpc14')),
   (UPPER('$enrpc15')),
   (UPPER('$enrpc16')),
   (UPPER('$enrpc17')),
   (UPPER('$enrpc18')),
   (UPPER('$enrpc19')),
   (UPPER('$enrpc20')),
   (UPPER('$enrft1')),
   (UPPER('$enrft2')),
   (UPPER('$enrft3')),
   (UPPER('$enrft4')),
   (UPPER('$enrft5')),
   (UPPER('$enrft6')),
   (UPPER('$enrft7')),
   (UPPER('$enrft8')),
   (UPPER('$enrft9')),
   (UPPER('$enrft10')),
   (UPPER('$enrft11')),
   (UPPER('$enrft12')),
   (UPPER('$enrft13')),
   (UPPER('$enrft14')),
   (UPPER('$enrft15')),
   (UPPER('$enrft16')),
   (UPPER('$enrft17')),
   (UPPER('$enrft18')),
   (UPPER('$enrft19')),
   (UPPER('$enrft20')),
   '$opsmaparea',
   '$opsmapsubarea',
   '$iscustomprice',
   (UPPER('$handoverpostcode')),
   '$handoverCyclistID',
   '$autostartchain',
   '$co2saving',
   '$pm10saving'
   ) ", $conn_id
   )  or die(mysql_error()); 
   
   $newjobid=mysql_insert_id();  
mysql_query("UNLOCK TABLES", $conn_id);   
// $ID=$id;

   
$pagetext.="<p>Created ". $newjobid.' from '. $id .'</p>';
$infotext.="<br />Created ". $newjobid.' from '. $id;


$id=$newjobid;
$ID=$newjobid;


// $infotext.='new id is'.$ID;




}

        } // ends check for duplicate job 
    } // ends page=createnewfrom existing



if (($page=="newjobfromajax" ) and (trim($_POST['serviceID'])) and (trim($_POST['newjobselectclient'])) ) {

    
    ///////     STARTS WORKING OUT TIMES //////////////
    
    
    $ajdelldue=trim($_POST['ajdelldue']);
    $ajcolldue=trim($_POST['ajcolldue']);
    
    
    // $infotext.='<br />ajcolldue : '.$ajcolldue;
    // $infotext.='<br />ajdelldue : '.$ajdelldue;
    
    
    // echo "target collection date from row : "; echo $targetcollectiondate;
    $nowdate=date("Y-m-d H:i:s");
    $temp_ar=explode("-",$nowdate); $spltime_ar=explode(" ",$temp_ar[2]); 
    $temptime_ar=explode(":",$spltime_ar[1]); 
    if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) {
        $temptime_ar[0] = 0; 
        $temptime_ar[1] = 0; $temptime_ar[2] = 0;
    }
    $day=$spltime_ar[0];
    $month=$temp_ar[1];
    $year=$temp_ar[0];
    $hour=$temptime_ar[0];
    $minutes=$temptime_ar[1];
    $second = $temptime_ar[2];
    
    if ($ajcolldue) {
        if ($ajcolldue=='now') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='now15') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
            $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '15', $second, $month, $day, $year)); 
        }
        
        if ($ajcolldue=='now30') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
            $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '30', $second, $month, $day, $year)); 
        }
        
        if ($ajcolldue=='now45') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
            $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '45', $second, $month, $day, $year)); 
        }
        
        if ($ajcolldue=='now60') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
            $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '60', $second, $month, $day, $year)); 
        }
        
        if ($ajcolldue=='now90') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
            $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '90', $second, $month, $day, $year)); 
        }
        
        if ($ajcolldue=='now120') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
            $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '120', $second, $month, $day, $year)); 
        }
        
        if ($ajcolldue=='now180') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
            $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '180', $second, $month, $day, $year)); 
        }
        
        if ($ajcolldue=='15to30') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes + '15', $second, $month, $day, $year));
            $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '30', $second, $month, $day, $year)); 
        }
        
        if ($ajcolldue=='15to45') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes + '15', $second, $month, $day, $year));
            $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '45', $second, $month, $day, $year)); 
        }
        
        if ($ajcolldue=='30to45') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes + '30', $second, $month, $day, $year));
            $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '45', $second, $month, $day, $year)); 
        }
        
        if ($ajcolldue=='15') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes+ '15', $second, $month, $day, $year));
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='30') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes+ '30', $second, $month, $day, $year));
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='45') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes+ '45', $second, $month, $day, $year));
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='60') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes+ '60', $second, $month, $day, $year));
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='90') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes+ '90', $second, $month, $day, $year));
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='120') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes+ '120', $second, $month, $day, $year));
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='180') {
            $targetcollectiondate= date("Y-m-d H:i:s", mktime($hour, $minutes+ '180', $second, $month, $day, $year));
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='next8') {
            if ($hour<8) {
                $targetcollectiondate= date("Y-m-d H:i:s", mktime('08', '00', '00', $month, $day, $year));
            } else {
                $targetcollectiondate= date("Y-m-d H:i:s", mktime('08', '00', '00', $month, $day + '1' , $year));
            }
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='next9') {
        if ($hour<9) {
                $targetcollectiondate= date("Y-m-d H:i:s", mktime('09', '00', '00', $month, $day, $year));
            }
            else {
                $targetcollectiondate= date("Y-m-d H:i:s", mktime('09', '00', '00', $month, $day + '1' , $year));
            }
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='next10') {
            if ($hour<10) {
                $targetcollectiondate= date("Y-m-d H:i:s", mktime('10', '00', '00', $month, $day, $year));
            }
            else {
                $targetcollectiondate= date("Y-m-d H:i:s", mktime('10', '00', '00', $month, $day + '1' , $year));
            }
            $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajcolldue=='next11') {
        if ($hour<11) { $targetcollectiondate= date("Y-m-d H:i:s", mktime('11', '00', '00', $month, $day, $year));
        } else { $targetcollectiondate= date("Y-m-d H:i:s", mktime('11', '00', '00', $month, $day + '1' , $year)); }
        $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajcolldue=='next12') {
        if ($hour<12) { $targetcollectiondate= date("Y-m-d H:i:s", mktime('12', '00', '00', $month, $day, $year));
        } else { $targetcollectiondate= date("Y-m-d H:i:s", mktime('12', '00', '00', $month, $day + '1' , $year)); }
        $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajcolldue=='next13') {
        if ($hour<13) { $targetcollectiondate= date("Y-m-d H:i:s", mktime('13', '00', '00', $month, $day, $year));
        } else { $targetcollectiondate= date("Y-m-d H:i:s", mktime('13', '00', '00', $month, $day + '1' , $year)); }
        $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajcolldue=='next14') {
        if ($hour<14) { $targetcollectiondate= date("Y-m-d H:i:s", mktime('14', '00', '00', $month, $day, $year));
        } else { $targetcollectiondate= date("Y-m-d H:i:s", mktime('14', '00', '00', $month, $day + '1' , $year)); }
        $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajcolldue=='next15') {
        if ($hour<15) { $targetcollectiondate= date("Y-m-d H:i:s", mktime('15', '00', '00', $month, $day, $year));
        } else { $targetcollectiondate= date("Y-m-d H:i:s", mktime('15', '00', '00', $month, $day + '1' , $year)); }
        $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajcolldue=='next16') {
        if ($hour<16) { $targetcollectiondate= date("Y-m-d H:i:s", mktime('16', '00', '00', $month, $day, $year));
        } else { $targetcollectiondate= date("Y-m-d H:i:s", mktime('16', '00', '00', $month, $day + '1' , $year)); }
        $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajcolldue=='next17') {
        if ($hour<17) { $targetcollectiondate= date("Y-m-d H:i:s", mktime('17', '00', '00', $month, $day, $year));
        } else { $targetcollectiondate= date("Y-m-d H:i:s", mktime('17', '00', '00', $month, $day + '1' , $year)); }
        $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajcolldue=='next18') {
        if ($hour<18) { $targetcollectiondate= date("Y-m-d H:i:s", mktime('18', '00', '00', $month, $day, $year));
        } else { $targetcollectiondate= date("Y-m-d H:i:s", mktime('18', '00', '00', $month, $day + '1' , $year)); }
        $collectionworkingwindow= '0000-00-00 00:00:00';
        }
        
        
        
        if ($ajdelldue=='now') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
        $deliveryworkingwindow= '0000-00-00 00:00:00'; 
        }
        
        if ($ajdelldue=='now15') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '15', $second, $month, $day, $year)); 
        }
        if ($ajdelldue=='now30') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '30', $second, $month, $day, $year)); 
        }
        if ($ajdelldue=='now45') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '45', $second, $month, $day, $year)); 
        }
        if ($ajdelldue=='now60') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '60', $second, $month, $day, $year)); 
        }
        if ($ajdelldue=='now90') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '90', $second, $month, $day, $year)); 
        }
        if ($ajdelldue=='now120') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '120', $second, $month, $day, $year)); 
        }
        if ($ajdelldue=='now180') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '180', $second, $month, $day, $year)); 
        }
        if ($ajdelldue=='15to30') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes + '15', $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '30', $second, $month, $day, $year)); 
        }
        if ($ajdelldue=='15to45') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes + '15', $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '45', $second, $month, $day, $year)); 
        }
        if ($ajdelldue=='30to45') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes + '30', $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '45', $second, $month, $day, $year)); 
        }
        if ($ajdelldue=='now180') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes, $second, $month, $day, $year));
        $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour, $minutes + '180', $second, $month, $day, $year)); 
        }
        
        if ($ajdelldue=='15') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes + '15', $second, $month, $day, $year));
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='30') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes + '30', $second, $month, $day, $year));
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='45') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes + '45', $second, $month, $day, $year));
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='60') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes + '60', $second, $month, $day, $year));
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='90') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes + '90', $second, $month, $day, $year));
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='120') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes + '120', $second, $month, $day, $year));
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='180') {
        $duedate= date("Y-m-d H:i:s", mktime($hour, $minutes + '180', $second, $month, $day, $year));
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        
        if ($ajdelldue=='next8') {
        if ($hour<8) { $duedate= date("Y-m-d H:i:s", mktime('8', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('8', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='next9') {
        if ($hour<9) { $duedate= date("Y-m-d H:i:s", mktime('9', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('9', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='next10') {
        if ($hour<10) { $duedate= date("Y-m-d H:i:s", mktime('10', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('10', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='next11') {
        if ($hour<11) { $duedate= date("Y-m-d H:i:s", mktime('11', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('11', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='next12') {
        if ($hour<12) { $duedate= date("Y-m-d H:i:s", mktime('12', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('12', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='next13') {
        if ($hour<13) { $duedate= date("Y-m-d H:i:s", mktime('13', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('13', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='next14') {
        if ($hour<14) { $duedate= date("Y-m-d H:i:s", mktime('14', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('14', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='next15') {
        if ($hour<15) { $duedate= date("Y-m-d H:i:s", mktime('15', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('15', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='next16') {
        if ($hour<16) { $duedate= date("Y-m-d H:i:s", mktime('16', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('16', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='next17') {
        if ($hour<17) { $duedate= date("Y-m-d H:i:s", mktime('17', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('17', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        if ($ajdelldue=='next18') {
        if ($hour<18) { $duedate= date("Y-m-d H:i:s", mktime('18', '00', '00', $month, $day, $year));
        } else { $duedate= date("Y-m-d H:i:s", mktime('18', '00', '00', $month, $day + '1' , $year)); }
        $deliveryworkingwindow= '0000-00-00 00:00:00';
        }
        
} // ends $ajdelldue check
    // $duedate= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year)); 
    // $deliveryworkingwindow= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year)); 
    // $collectionworkingwindow= date("Y-m-d H:i:s", mktime($hour + $dateshift, $minutes, $second, $month, $day, $year)); 
    // $infotext.= '<br />target collect : '.$targetcollectiondate;
    // $infotext.= '<br />allow collect ww : '.$allowcollectww;
    // $infotext.= '<br />target collect : '.$collectionworkingwindow;
    
    ////////////////////////       FINISHED WORKING OUT TIMES FOR NEW JOB FROM AJAX /////////////////////////////////
    
    
    
    $CustomerID=trim($_POST['newjobselectclient']);
    
    
    $checkactiveclient = mysql_result(mysql_query("SELECT isactiveclient FROM Clients WHERE CustomerID='$CustomerID' LIMIT 0,1"), '0');
    
    $infotext.= '<br /> checkactiveclient : '.$checkactiveclient;
    
    if ($checkactiveclient<>'1') {
        $sql = "UPDATE `Clients` SET `isactiveclient`='1' WHERE `CustomerID`='$CustomerID'; ";
        $result = mysql_query($sql, $conn_id);
        if ($result){
            $infotext.="<br />3658 CLIENT Updated as active";
            $pagetext.="<p>Client made active </p>";
        }
        else {
            $infotext.="<br /><strong> 3664 An error occured during updating client! </strong>".$sql;
            $alerttext.="<p><strong> 3665 An error occured during updating client! </p>";
        }
    }
    
    
    if (isset($_POST['collecttext'])) { $fromfreeaddress=trim($_POST['collecttext']); } else { $fromfreeaddress=''; }
    if (isset($_POST['delivertext'])) { $tofreeaddress=trim($_POST['delivertext']); } else { $tofreeaddress=''; }
    
    
    $requestedby=htmlspecialchars(trim($_POST['requestedby']));
    
    if (isset($_POST['newjobdepid'])) { $newjobdepid=trim($_POST['newjobdepid']); } else { $newjobdepid=''; }
    if (isset($_POST['deliverpc'])) { $deliverpc=trim($_POST['deliverpc']); } else { $deliverpc=''; }
    if (isset($_POST['collectpc'])) { $collectpc=trim($_POST['collectpc']); } else { $collectpc=''; }
    
    if (isset($_POST['frombox'])) { $frombox=trim($_POST['frombox']); } else { $frombox=''; }
    if (isset($_POST['tobox'])) { $tobox=trim($_POST['tobox']); } else { $tobox=''; }
    
    $infotext.='<p>from '.$frombox.' to '.$tobox.'</p>';
    
    if ($frombox) {
        $collectpc = mysql_result(mysql_query("SELECT favadrpc from cojm_favadr WHERE 
        favadrid='$frombox' AND favadrisactive='1' LIMIT 0,1"), '0');
    
        $fromfreeaddress = mysql_result(mysql_query("SELECT favadrft from cojm_favadr WHERE 
        favadrid='$frombox' AND favadrisactive='1' LIMIT 0,1"), '0');
    }
    
    
    if ($tobox) {
        $deliverpc = mysql_result(mysql_query("SELECT favadrpc from cojm_favadr WHERE 
        favadrid='$tobox' AND favadrisactive='1' LIMIT 0,1"), '0');
    
        $tofreeaddress = mysql_result(mysql_query("SELECT favadrft from cojm_favadr WHERE 
        favadrid='$tobox' AND favadrisactive='1' LIMIT 0,1"), '0');
    }
    
    

    if ( $globalprefrow["inaccuratepostcode"]<>'1') { // accurate postcodes
    
        $deliverpc = strtoupper(str_replace(' ','',$deliverpc));
        $collectpc = strtoupper(str_replace(' ','',$collectpc));
    
        $start=substr($deliverpc, 0, -3);  
        $deliverpc=$start.' '.substr($deliverpc, -3); // 'ies'  
        $start=substr($collectpc, 0, -3);  
        $collectpc=$start.' '.substr($collectpc, -3); // 'ies' 
    }
    
    $jobcomments=trim($_POST['jobcomments']);
    $jobcomments = str_replace("'", "&#39;", "$jobcomments");
    
    $jobcomments = trim(htmlspecialchars($jobcomments));
    
    $serviceid=trim($_POST['serviceID']);
    
    $serviceprice = mysql_result(mysql_query("SELECT Price from Services WHERE ServiceID='$serviceid' LIMIT 1"), 0);
    $vatband = mysql_result(mysql_query("SELECT vatband from Services WHERE ServiceID='$serviceid' LIMIT 1"), 0);
    $newcost='1' * $serviceprice;
    
    if (isset($globalprefrow['vatband'.$vatband])) {
            $newvatcost=($newcost)*(($globalprefrow['vatband'.$vatband])/'100');
    }
    else {
        $newvatcost='0.00';
    }
    
    $status='30';
    
    if ($status <'49'){
        $temp="requires collection time as next action";
        $nextactiondate = $targetcollectiondate;
    }
    else {
        $temp="requires delivery time as next action";
        $nextactiondate = $newduedate;
    }

    $numberitems='1';
    
    $serviceprice = mysql_result(mysql_query("SELECT Price from Services WHERE ServiceID='$serviceid' LIMIT 1", $conn_id), 0);
    $newcost=$numberitems * $serviceprice;
    
    if (isset($_POST['chkcbb4'])) { $chkcbb4=trim($_POST['chkcbb4']); } else { $chkcbb4='0'; }
    if (isset($_POST['chkcbb5'])) { $chkcbb5=trim($_POST['chkcbb5']); } else { $chkcbb5='0'; }
    if (isset($_POST['chkcbb6'])) { $chkcbb6=trim($_POST['chkcbb6']); } else { $chkcbb6='0'; }
    if (isset($_POST['chkcbb7'])) { $chkcbb7=trim($_POST['chkcbb7']); } else { $chkcbb7='0'; }
    if (isset($_POST['chkcbb8'])) { $chkcbb8=trim($_POST['chkcbb8']); } else { $chkcbb8='0'; }
    if (isset($_POST['chkcbb9'])) { $chkcbb9=trim($_POST['chkcbb9']); } else { $chkcbb9='0'; }
    if (isset($_POST['chkcbb10'])) { $chkcbb10=trim($_POST['chkcbb10']); } else { $chkcbb10='0'; }
    if (isset($_POST['chkcbb11'])) { $chkcbb11=trim($_POST['chkcbb11']); } else { $chkcbb11='0'; }
    if (isset($_POST['chkcbb12'])) { $chkcbb12=trim($_POST['chkcbb12']); } else { $chkcbb12='0'; }
    if (isset($_POST['chkcbb13'])) { $chkcbb13=trim($_POST['chkcbb13']); } else { $chkcbb13='0'; }
    if (isset($_POST['chkcbb14'])) { $chkcbb14=trim($_POST['chkcbb14']); } else { $chkcbb14='0'; }
    if (isset($_POST['chkcbb15'])) { $chkcbb15=trim($_POST['chkcbb15']); } else { $chkcbb15='0'; }
    if (isset($_POST['chkcbb16'])) { $chkcbb16=trim($_POST['chkcbb16']); } else { $chkcbb16='0'; }
    if (isset($_POST['chkcbb17'])) { $chkcbb17=trim($_POST['chkcbb17']); } else { $chkcbb17='0'; }
    if (isset($_POST['chkcbb18'])) { $chkcbb18=trim($_POST['chkcbb18']); } else { $chkcbb18='0'; }
    if (isset($_POST['chkcbb19'])) { $chkcbb19=trim($_POST['chkcbb19']); } else { $chkcbb19='0'; }
    if (isset($_POST['chkcbb20'])) { $chkcbb20=trim($_POST['chkcbb20']); } else { $chkcbb20='0'; }
    
    
    
    // actually create the job
    mysql_query("LOCK TABLES Orders WRITE", $conn_id);
    $sql="INSERT INTO Orders 
    (
    CustomerID,
    requestor ,
    ServiceID ,
    CyclistID,
    CollectPC, 
    ShipPC,
    fromfreeaddress,
    tofreeaddress,
    numberitems, 
    status,
    jobrequestedtime,
    jobcomments,
    vatcharge,
    orderdep,
    targetcollectiondate,
    collectionworkingwindow ,
    duedate ,
    deliveryworkingwindow ,
    FreightCharge,
    cbbc4,
    cbbc5,
    cbbc6,
    cbbc7,
    cbbc8,
    cbbc9,
    cbbc10,
    cbbc11,
    cbbc12,
    cbbc13,
    cbbc14,
    cbbc15,
    cbbc16,
    cbbc17,
    cbbc18,
    cbbc19,
    cbbc20
    
    ) 
    VALUES
    (
    '$CustomerID',
    (UPPER('$requestedby')) ,
    '$serviceid' ,
    '1',
    (UPPER('$collectpc')), 
    (UPPER('$deliverpc')),
    (UPPER('$fromfreeaddress')),
    (UPPER('$tofreeaddress')),
    '$numberitems',
    '$status',
    now() ,
    (UPPER('$jobcomments')),
    '$newvatcost',
    '$newjobdepid',
    '$targetcollectiondate',
    '$collectionworkingwindow' ,
    '$duedate' ,
    '$deliveryworkingwindow' ,
    '$newcost' ,
    '$chkcbb4' , 
    '$chkcbb5' , 
    '$chkcbb6' , 
    '$chkcbb7' , 
    '$chkcbb8' , 
    '$chkcbb9' , 
    '$chkcbb10' , 
    '$chkcbb11' , 
    '$chkcbb12' , 
    '$chkcbb13' , 
    '$chkcbb14' , 
    '$chkcbb15' , 
    '$chkcbb16' , 
    '$chkcbb17' , 
    '$chkcbb18' , 
    '$chkcbb19' , 
    '$chkcbb20' 
    )
    ";
    
    mysql_query($sql, $conn_id) or die(mysql_error()); 
    $id=mysql_insert_id();  
    
    mysql_query("UNLOCK TABLES", $conn_id);   
    $ID=$id;
    $origid=$id;
    $emailclientconfirmnewjob="";
    
    $pagetext.="<p>Created Job Ref ". $id.'</p>';
    $infotext.="<br />Created job has ID : " . $id;
    

    calcmileage($id, $globalprefrow['distanceunit'], $globalprefrow['co2perdist'], $globalprefrow['pm10perdist']);
    $cojmaction='recalcprice';

}  // ends  new job from ajax




 $sql = "SELECT * FROM Orders 
 WHERE (`Orders`.`ID` = '$id' )
 LIMIT 0,1 ";
$sql_result = mysql_query($sql,$conn_id) or die(mysql_error()); $sumtot=mysql_affected_rows(); 

    
    if ($sumtot>0)  { // individ job id found
    
    
    
    
    // $infotext.='<br />2639  checking form birthday for last edited time.';
    
    if ($nowepoch < $globalprefrow['formtimeout']) {
    
    $editedtime = mysql_result(mysql_query("
    SELECT ts 
    from Orders 
    WHERE `Orders`.`ID`=$id 
    LIMIT 1
    ", $conn_id), 0);
    
    if (($page<>'newjobfromajax') and ($page) and ($page<>'createnewfromexisting') ) {
        if ((($formbirthday+1) < (strtotime($editedtime)) ) and (date_default_timezone_get()<>'UTC' )) {
            $infotext.=' <br />cj2982 - Another user has modified since last refresh page is '.$page;
            $infotext.='<br /> nowepoch ' .$nowepoch;
            $alerttext.='<p><strong>Another user has modified since page was last refreshed, unable to change job details.</strong></p>';
        }
        else {
            $infotext.='<br /> formbirthday is  '.$formbirthday.'  ie '.date('Y m j H:i ', ($formbirthday)). '<br />edited is '.$editedtime.' '.(strtotime($editedtime)); 
    
    
    
    
    if ($page=="addfavtoorder") {
    
    if (isset($_POST['selectfavbox'])) { $selectfavbox=(trim($_POST['selectfavbox'])); } else { $selectfavbox=''; }
    if (isset($_POST['addr'])) { $addr=(trim($_POST['addr'])); } else { $addr=''; }
    
    if (($id) and ($selectfavbox) and ($addr)) {
    $infotext.='<br />FAV ADDRESS <br />'. $id.' ' . $selectfavbox.' ' . $addr;
    
    if ($addr=='fr')   { $addrft='fromfreeaddress'; $addrpc='CollectPC'; }
    if ($addr=='to')   { $addrft='tofreeaddress';   $addrpc='ShipPC'; }
    if ($addr=='via1') { $addrft='enrft1'; $addrpc='enrpc1'; }
    if ($addr=='via2') { $addrft='enrft2'; $addrpc='enrpc2'; }
    if ($addr=='via3') { $addrft='enrft3'; $addrpc='enrpc3'; }
    if ($addr=='via4') { $addrft='enrft4'; $addrpc='enrpc4'; }
    if ($addr=='via5') { $addrft='enrft5'; $addrpc='enrpc5'; }
    if ($addr=='via6') { $addrft='enrft6'; $addrpc='enrpc6'; }
    if ($addr=='via7') { $addrft='enrft7'; $addrpc='enrpc7'; }
    if ($addr=='via8') { $addrft='enrft8'; $addrpc='enrpc8'; }
    if ($addr=='via9') { $addrft='enrft9'; $addrpc='enrpc9'; }
    if ($addr=='via10') { $addrft='enrft10'; $addrpc='enrpc10'; }
    if ($addr=='via11') { $addrft='enrft11'; $addrpc='enrpc11'; }
    if ($addr=='via12') { $addrft='enrft12'; $addrpc='enrpc12'; }
    if ($addr=='via13') { $addrft='enrft13'; $addrpc='enrpc13'; }
    if ($addr=='via14') { $addrft='enrft14'; $addrpc='enrpc14'; }
    if ($addr=='via15') { $addrft='enrft15'; $addrpc='enrpc15'; }
    if ($addr=='via16') { $addrft='enrft16'; $addrpc='enrpc16'; }
    if ($addr=='via17') { $addrft='enrft17'; $addrpc='enrpc17'; }
    if ($addr=='via18') { $addrft='enrft18'; $addrpc='enrpc18'; }
    if ($addr=='via19') { $addrft='enrft19'; $addrpc='enrpc19'; }
    if ($addr=='via20') { $addrft='enrft20'; $addrpc='enrpc20'; }
    
    
    
    $sql="SELECT * FROM cojm_favadr WHERE favadrid = '$selectfavbox' AND favadrisactive ='1' LIMIT 0,1";
    $sql_result = mysql_query($sql,$conn_id);
    $sumtot=mysql_affected_rows(); if ($sumtot>'0')  {
    
    while ($favrow = mysql_fetch_array($sql_result)) { extract($favrow);
    
    $infotext.='<br />3487 '.$favrow['favadrft'].' '.$favrow['favadrpc'];
    
    $favadrft=$favrow['favadrft'];
    $favadrpc=$favrow['favadrpc'];
    
    } // ends loop for fav address
    } // ends check for fav address on db
    
    
    $sql = "UPDATE Orders SET ".$addrft." = '".$favadrft."', ".$addrpc." = '".$favadrpc."'  
    WHERE ID='$id' "; 
    $result = mysql_query($sql, $conn_id);
    if ($result){ 
    $infotext.="<br />3500 Address Updated ";
    $pagetext.="<p>Address updated </p>";
    
    
    
    calcmileage($ID, $globalprefrow['distanceunit'], $globalprefrow['co2perdist'], $globalprefrow['pm10perdist']);
    $cojmaction='recalcprice';
    
    
    
    } else { 
    $infotext.="<br /><strong> 3492 An error occured during updating address! </strong>".$sql; 
    $alerttext.="<p><strong> 3493 An error occured during updating address! </p>"; 
    }
    
    
    } // ends check for all variables
    } // ends check for page = addfavtoorder
    
    
    
    if ($page == "confirmdeletemobile") { 
    
    $infotext.='<br />Delete job from mobile aka requeuing to admin ';
    $query =  " 
    UPDATE Orders 
    SET status='86', 
    privatejobcomments = concat('** DELETED FROM MOBILE BY ".$cyclistid." ** ',privatejobcomments),
    ShipDate = now() ,
    collectiondate = now() 
    WHERE ID='$id'";	
    
    // $infotext.=$query;
    
    mysql_query($query, $conn_id);
    $alerttext.="<p><strong>Job ref ".$id." moved to admin as from mobile device.</strong></p>";	
    
    }
    
    
    
    
    
    
    
    
    
    
    // CONFIRMING DELETE OPTION
    if ($page == "confirmdelete" ) {
    
    
    $infotext.="<br /><strong>Delete option, job ref ".$id." deleted.</strong>";
    $query = "DELETE from Orders WHERE ID='$id'";	mysql_query($query, $conn_id);
    $alerttext.="<p><strong>Delete option confirmed, job ref ".$id." deleted.</strong></p>";	
    // $infotext.="<br /><strong>Delete option confirmed,<br> ID Deleted.</strong>";	
    
    } 
    
    
    
    // END OF DELETION CONFIRM
    
    
    
    
    if ($page=='edituidate') {
    
    $cojmaction='recalcprice';
    
    
    $ShipPC=trim($_POST['ShipPC']);
    $CollectPC=trim($_POST['CollectPC']);
    
    $sPattern = '/\s*/m'; 
    $sReplace = '';
    $ShipPC=preg_replace( $sPattern, $sReplace, $ShipPC );
    $CollectPC=preg_replace( $sPattern, $sReplace, $CollectPC );
    
    if ( $globalprefrow["inaccuratepostcode"]<>'1') {
    $start=substr($ShipPC, 0, -3);  
    $ShipPC=$start.' '.substr($ShipPC, -3); // 'ies'  
    $start=substr($CollectPC, 0, -3);  
    $CollectPC=$start.' '.substr($CollectPC, -3); // 'ies' 
    }
    
    
    
    $fromfreeaddress=trim($_POST['fromfreeaddress']);
    $tofreeaddress=trim($_POST['tofreeaddress']);
    $fromfreeaddress = str_replace("/", ",", "$fromfreeaddress", $count);
    $tofreeaddress = str_replace("/", ",", "$tofreeaddress", $count);
    $fromfreeaddress = str_replace("£", "GBP", "$fromfreeaddress", $count);
    $tofreeaddress = str_replace("£", "GBP", "$tofreeaddress", $count);
    $fromfreeaddress = str_replace("'", " ", "$fromfreeaddress", $count);
    $tofreeaddress = str_replace("'", " ", "$tofreeaddress", $count);
    $fromfreeaddress=preg_replace( '/\r\n/', ' ', trim($fromfreeaddress) );
    $tofreeaddress=preg_replace( '/\r\n/', ' ', trim($tofreeaddress) );
    $enrft1=trim($_POST['enrft1']);
    $enrpc1=trim($_POST['enrpc1']);
    $enrft2=trim($_POST['enrft2']);
    $enrpc2=trim($_POST['enrpc2']);
    $enrft3=trim($_POST['enrft3']);
    $enrpc3=trim($_POST['enrpc3']);
    $enrft4=trim($_POST['enrft4']);
    $enrpc4=trim($_POST['enrpc4']);
    $enrft5=trim($_POST['enrft5']);
    $enrpc5=trim($_POST['enrpc5']);
    $enrft6=trim($_POST['enrft6']);
    $enrpc6=trim($_POST['enrpc6']);
    $enrft7=trim($_POST['enrft7']);
    $enrpc7=trim($_POST['enrpc7']);
    $enrft8=trim($_POST['enrft8']);
    $enrpc8=trim($_POST['enrpc8']);
    $enrft9=trim($_POST['enrft9']);
    $enrpc9=trim($_POST['enrpc9']);
    $enrft10=trim($_POST['enrft10']);
    $enrpc10=trim($_POST['enrpc10']);
    $enrft11=trim($_POST['enrft11']);
    $enrpc11=trim($_POST['enrpc11']);
    $enrft12=trim($_POST['enrft12']);
    $enrpc12=trim($_POST['enrpc12']);
    $enrft13=trim($_POST['enrft13']);
    $enrpc13=trim($_POST['enrpc13']);
    $enrft14=trim($_POST['enrft14']);
    $enrpc14=trim($_POST['enrpc14']);
    $enrft15=trim($_POST['enrft15']);
    $enrpc15=trim($_POST['enrpc15']);
    $enrft16=trim($_POST['enrft16']);
    $enrpc16=trim($_POST['enrpc16']);
    $enrft17=trim($_POST['enrft17']);
    $enrpc17=trim($_POST['enrpc17']);
    $enrft18=trim($_POST['enrft18']);
    $enrpc18=trim($_POST['enrpc18']);
    $enrft19=trim($_POST['enrft19']);
    $enrpc19=trim($_POST['enrpc19']);
    $enrft20=trim($_POST['enrft20']);
    $enrpc20=trim($_POST['enrpc20']);
    
    
    $sPattern = ' /\s*/m'; 
    $sReplace = '';
    $enrpc1=preg_replace( $sPattern, $sReplace, $enrpc1 );
    $enrpc2=preg_replace( $sPattern, $sReplace, $enrpc2 );
    $enrpc3=preg_replace( $sPattern, $sReplace, $enrpc3 );
    $enrpc4=preg_replace( $sPattern, $sReplace, $enrpc4 );
    $enrpc5=preg_replace( $sPattern, $sReplace, $enrpc5 );
    $enrpc6=preg_replace( $sPattern, $sReplace, $enrpc6 );
    $enrpc7=preg_replace( $sPattern, $sReplace, $enrpc7 );
    $enrpc8=preg_replace( $sPattern, $sReplace, $enrpc8 );
    $enrpc9=preg_replace( $sPattern, $sReplace, $enrpc9 );
    $enrpc10=preg_replace( $sPattern, $sReplace, $enrpc10 );
    $enrpc11=preg_replace( $sPattern, $sReplace, $enrpc11 );
    $enrpc12=preg_replace( $sPattern, $sReplace, $enrpc12 );
    $enrpc13=preg_replace( $sPattern, $sReplace, $enrpc13 );
    $enrpc14=preg_replace( $sPattern, $sReplace, $enrpc14 );
    $enrpc15=preg_replace( $sPattern, $sReplace, $enrpc15 );
    $enrpc16=preg_replace( $sPattern, $sReplace, $enrpc16 );
    $enrpc17=preg_replace( $sPattern, $sReplace, $enrpc17 );
    $enrpc18=preg_replace( $sPattern, $sReplace, $enrpc18 );
    $enrpc19=preg_replace( $sPattern, $sReplace, $enrpc19 );
    $enrpc20=preg_replace( $sPattern, $sReplace, $enrpc20 );
    
    if (($globalprefrow['inaccuratepostcode'])==0) {
    $start=substr($enrpc1, 0, -3);  $enrpc1=trim($start.' '.substr($enrpc1, -3)); // 'ies'  
    $start=substr($enrpc2, 0, -3);  $enrpc2=trim($start.' '.substr($enrpc2, -3)); // 'ies' 
    $start=substr($enrpc3, 0, -3);  $enrpc3=trim($start.' '.substr($enrpc3, -3)); // 'ies'  
    $start=substr($enrpc4, 0, -3);  $enrpc4=trim($start.' '.substr($enrpc4, -3)); // 'ies'  
    $start=substr($enrpc5, 0, -3);  $enrpc5=trim($start.' '.substr($enrpc5, -3)); // 'ies'  
    $start=substr($enrpc6, 0, -3);  $enrpc6=trim($start.' '.substr($enrpc6, -3)); // 'ies'  
    $start=substr($enrpc7, 0, -3);  $enrpc7=trim($start.' '.substr($enrpc7, -3)); // 'ies'  
    $start=substr($enrpc8, 0, -3);  $enrpc8=trim($start.' '.substr($enrpc8, -3)); // 'ies'  
    $start=substr($enrpc9, 0, -3);  $enrpc9=trim($start.' '.substr($enrpc9, -3)); // 'ies'  
    $start=substr($enrpc10, 0, -3);  $enrpc10=trim($start.' '.substr($enrpc10, -3)); // 'ies'  
    $start=substr($enrpc11, 0, -3);  $enrpc11=trim($start.' '.substr($enrpc11, -3)); // 'ies'  
    $start=substr($enrpc12, 0, -3);  $enrpc12=trim($start.' '.substr($enrpc12, -3)); // 'ies'  
    $start=substr($enrpc13, 0, -3);  $enrpc13=trim($start.' '.substr($enrpc13, -3)); // 'ies'  
    $start=substr($enrpc14, 0, -3);  $enrpc14=trim($start.' '.substr($enrpc14, -3)); // 'ies'  
    $start=substr($enrpc15, 0, -3);  $enrpc15=trim($start.' '.substr($enrpc15, -3)); // 'ies'  
    $start=substr($enrpc16, 0, -3);  $enrpc16=trim($start.' '.substr($enrpc16, -3)); // 'ies'  
    $start=substr($enrpc17, 0, -3);  $enrpc17=trim($start.' '.substr($enrpc17, -3)); // 'ies'  
    $start=substr($enrpc18, 0, -3);  $enrpc18=trim($start.' '.substr($enrpc18, -3)); // 'ies'  
    $start=substr($enrpc19, 0, -3);  $enrpc19=trim($start.' '.substr($enrpc19, -3)); // 'ies'  
    $start=substr($enrpc20, 0, -3);  $enrpc20=trim($start.' '.substr($enrpc20, -3)); // 'ies'  
    }
    
    // $infotext.="<br><b>Updating Collection Postcode</b><br>New Postcode : ". $CollectPC. "<br>";
    $sql = "UPDATE Orders SET 
    fromfreeaddress=(UPPER('$fromfreeaddress')), 
    tofreeaddress=(UPPER('$tofreeaddress')), 
    enrpc1=(UPPER('$enrpc1')),
    enrft1=(UPPER('$enrft1')),
    enrpc2=(UPPER('$enrpc2')),
    enrft2=(UPPER('$enrft2')),
    enrpc3=(UPPER('$enrpc3')),
    enrft3=(UPPER('$enrft3')),
    enrpc4=(UPPER('$enrpc4')),
    enrft4=(UPPER('$enrft4')),
    enrpc5=(UPPER('$enrpc5')),
    enrft5=(UPPER('$enrft5')),
    enrpc6=(UPPER('$enrpc6')),
    enrft6=(UPPER('$enrft6')),
    enrpc7=(UPPER('$enrpc7')),
    enrft7=(UPPER('$enrft7')),
    enrpc8=(UPPER('$enrpc8')),
    enrft8=(UPPER('$enrft8')),
    enrpc9=(UPPER('$enrpc9')),
    enrft9=(UPPER('$enrft9')),
    enrpc10=(UPPER('$enrpc10')),
    enrft10=(UPPER('$enrft10')),
    enrpc11=(UPPER('$enrpc11')),
    enrft11=(UPPER('$enrft11')),
    enrpc12=(UPPER('$enrpc12')),
    enrft12=(UPPER('$enrft12')),
    enrpc13=(UPPER('$enrpc13')),
    enrft13=(UPPER('$enrft13')),
    enrpc14=(UPPER('$enrpc14')),
    enrft14=(UPPER('$enrft14')),
    enrpc15=(UPPER('$enrpc15')),
    enrft15=(UPPER('$enrft15')),
    enrpc16=(UPPER('$enrpc16')),
    enrft16=(UPPER('$enrft16')),
    enrpc17=(UPPER('$enrpc17')),
    enrft17=(UPPER('$enrft17')),
    enrpc18=(UPPER('$enrpc18')),
    enrft18=(UPPER('$enrft18')),
    enrpc19=(UPPER('$enrpc19')),
    enrft19=(UPPER('$enrft19')),
    enrpc20=(UPPER('$enrpc20')),
    enrft20=(UPPER('$enrft20')),
    CollectPC=(UPPER('$CollectPC')),  
    ShipPC=(UPPER('$ShipPC')) 
    WHERE ID='$id' LIMIT 1"; $result = mysql_query($sql, $conn_id);
    if ($result){ 
    $infotext.="<br />Postcodes updated<br />"; 
    //  $pagetext.='<p> cj 4406 Pt1 success</p>'; 
    } 
    else { 
    $infotext.="<br /><strong>An error occured during updating postcodes!</strong>"; 
    $alerttext.="<p>Error occured during updating postcodes</p>"; 
    
    }
    
    
    
    
    
    
    // $infotext.='<br />Editing new style dates / times';
    
    
    
    // refreshes job to make sure not changed by job status
    
    $query="SELECT * FROM Orders where ID = '$id' LIMIT 1";
    $result=mysql_query($query, $conn_id); $row=mysql_fetch_array($result);
    
    
    
    
    calcmileage($ID, $globalprefrow['distanceunit'], $globalprefrow['co2perdist'], $globalprefrow['pm10perdist']);
    $cojmaction='recalcprice';
    
    
    } ////////////////////      ENDS EDIT MAIN PAGE=edituidate          ///////////////////////////////////////////////////
    
    
    
    
    if (($page=="editstatus") or ($page=='edituidate')) {
    
    // $infotext.=' form birthday is '.$formbirthday. ' timestamp is '.strtotime($editedtime);
    
    $newcyclist=trim($_POST['newcyclist']);
    $oldcyclist=trim($_POST['oldcyclist']);
    $newstatus=$_POST['newstatus'];
    $oldstatus=$_POST['oldstatus'];
    
    if ($newcyclist<>$oldcyclist) {
    
    // $infotext.='<br/>Cyclist different, setting jov to unviewed';
    
    $sql = "UPDATE Orders SET lookedatbycyclisttime='0', CyclistID=$newcyclist WHERE ID = $id LIMIT 1"; 
    $result = mysql_query($sql, $conn_id);
    if ($result){ 
    $pagetext.="<p>".$globalprefrow['glob5']." Updated </p>"; 
    $infotext.="<br />Updated ".$globalprefrow['glob5']." from ".$oldcyclist.' to '.$newcyclist; 
    } 
    else { 
    $alerttext.="<p>Error occured during updating ".$globalprefrow['glob5']."</p>"; 
    $infotext.=mysql_error()."<br> <strong>An error occured during updating cyclist</strong>".$sql; 
    } 
    } // ends check for change in cyclist
    
    
    
    if (($newstatus)and ($newstatus<>$oldstatus)) {
    
    $oldstatustext = mysql_result(mysql_query("SELECT statusname FROM status WHERE status='$oldstatus' LIMIT 0,1", $conn_id), 0);
    $newstatustext = mysql_result(mysql_query("SELECT statusname FROM status WHERE status='$newstatus' LIMIT 0,1", $conn_id), 0);
    
    $docalc = mysql_result(mysql_query("
    SELECT lookedatbycyclisttime
    from Orders 
    WHERE `Orders`.`ID`=$id
    LIMIT 1
    ", $conn_id), 0);
    
    if  ($docalc=='0000-00-00 00:00:00') {
    
    // $infotext.='docalc is '.$docalc;
    $sql = "UPDATE Orders SET lookedatbycyclisttime=NOW() WHERE ID = $id LIMIT 1"; 
    $result = mysql_query($sql, $conn_id);
    if ($result){ $infotext.="<br>Job Viewed 1st time.";  
    } else { 
    $infotext.=mysql_error()."<br> <strong>An error occured during updating Databse to mark viewed</strong>"; 
    $alerttext.="<p>Error occured during updating Databse to mark viewed</p>"; 
    
    } // ends result check
    } // ends check for docalc
    
    
    
    $sql = "UPDATE Orders SET status='$newstatus' WHERE ID='$id' LIMIT 1"; $result = mysql_query($sql, $conn_id);
    if ($result){ 
    $pagetext.='<p>Status changed from '.$oldstatustext.' to '.$newstatustext.'</p>'; 
    $infotext.='<br />Status updated from '.$oldstatustext.' to '.$newstatustext;
    
    } else { 
    $infotext.="<br /> cj 4501 <strong>Error occured in status Update</strong>"; 
    $alerttext.="<p>Error cj 4501 occured in status Update</p>"; 
    } 
    
    
    // <option value="30">Collection Scheduled</option>
    // <option value="40">En-route to Collection</option>
    // <option value="50">Onsite at Collection</option>
    // <option value="60">Scheduled</option>
    // 62 Mail Batch in progress
    // <option value="65">En-route with delivery</option>
    // <option value="86">Completed needs admin</option>
    // <option value="100">Complete</option>
    // <option value="102">Reqs Invoicing</option>
    // <option value="110">Complete Invoice Sent</option>
    // <option value="120">Complete Invoice Paid</option>
    // <option value="122">Reqs Receipt</option> 
    
    
    
    
    // $Changing waiting time to current time
    if ($newstatus =='50') {
    $sql = "UPDATE Orders SET waitingstarttime=now() WHERE ID='$id' LIMIT 1"; $result2 = mysql_query($sql, $conn_id);
    if ($result2){ 
    $pagetext.="<p>".$globalprefrow['glob5']." at collection point.</p>";
    $infotext.="<p>On-Site time updated.</p>";
    } else { 
    $infotext.="<br /><strong>An error occured during update!</strong>".$sql; 
    $alerttext.="<p>Error occured during update!</p>"; 
    }}
    
    
    // item has been collected
    if (($oldstatus <'60' ) and ($newstatus >'59')) { 
    $sql = "UPDATE Orders SET collectiondate=now() WHERE ID='$id' LIMIT 1"; $result2 = mysql_query($sql, $conn_id);
    if ($result2){ 
    $infotext.="<br />Collection time updated ";
    $pagetext.="<p>Item Collected </p>";
    } else { 
    $infotext.="<br /><strong>An error occured during updating collection time !</strong>".$sql; 
    $alerttext.="<p>Error occured updating collection time </p>"; 
    }}
    
    
    // $infotext.="Pausing tracking";
    if ($newstatus =='60') {
    $sql = "UPDATE Orders SET starttrackpause=now() WHERE ID='$id' LIMIT 1"; $result27 = mysql_query($sql, $conn_id);
    if ($result27){ 
    $infotext.="<br />Paused time auto updated";
    $pagetext.="<p> Job Paused</p>";
    } else { 
    $infotext.="<br /><strong>An error occured during updating pause tracking time !</strong>".$sql; 
    $alerttext.="<p>Error occured during updating pause tracking time !</p>"; 
    }}
    
    
    
    if (($oldstatus =='60') and ($newstatus >'60')) {
    // $infotext.="Resuming tracking";
    $sql = "UPDATE Orders SET finishtrackpause=now() WHERE ID='$id' LIMIT 1"; $result27 = mysql_query($sql, $conn_id);
    if ($result27){ 
    $infotext.="<br />Resume time updated index";
    $pagetext.="<p>Job Resumed</p>";
    } else { 
    $infotext.="<br /><strong>An error occured during updating resuming tracking time !</strong>".$sql; 
    $alerttext.="<p>Error occured during updating resuming tracking time</p>"; 
    }}
    
    
    
    if (($oldstatus <'70' ) and ($newstatus>'70')) {
    $sql = "UPDATE Orders SET ShipDate=now() WHERE ID='$id' LIMIT 1"; $result3 = mysql_query($sql, $conn_id);
    if ($result3){ 
    $infotext.="<br />Delivery time updated";
    $pagetext.="<p>Delivered  </p>";
    } else { 
    $infotext.="<br /><strong>An error occured during updating delivery time!</strong>".$sql; 
    $alerttext.="<p>Error during updating delivery time</p>"; 
    }}
    
    
    
    if ($newstatus =='40') {
    $sql = "UPDATE Orders SET starttravelcollectiontime=now() WHERE ID='$id' LIMIT 1"; 
    $result4 = mysql_query($sql, $conn_id); if ($result4){ 
    $infotext.="<br /> en route time updated. ";
    $pagetext.="<p> On way to collection. </p>";
    } else { 
    $infotext.="<br /><strong>An error occured during updating start travel to collection time!</strong>".$sql; 
    $alerttext.="<p>Error during updating start travel to collection time</p>"; 
    }}
    
    
    // <option value="30">Collection Scheduled</option>
    // <option value="40">En-route to Collection</option>
    // <option value="50">Onsite at Collection</option>
    // <option value="60">Scheduled</option>
    // 62 Mail Batch in progress
    // <option value="65">En-route with delivery</option>
    // <option value="86">Completed needs admin</option>
    // <option value="100">Complete</option>
    // <option value="102">Reqs Invoicing</option>
    // <option value="110">Complete Invoice Sent</option>
    // <option value="120">Complete Invoice Paid</option>
    // <option value="122">Reqs Receipt</option> 
    
    
    if ($newstatus<$oldstatus) {
    
    
    $pagetext.="<p> Status reduced from ".$oldstatustext." to ".$newstatustext.'</p>';
    
    $infotext.='<br />Status gone down.';
    // check for times higher than job status
    if ($newstatus =='30') {
    $sql = "UPDATE Orders SET 
    starttravelcollectiontime='0000-00-00 00:00:00', 
    waitingstarttime ='0000-00-00 00:00:00',
    collectiondate='0000-00-00 00:00:00',
    starttrackpause = '0000-00-00 00:00:00',
    finishtrackpause ='0000-00-00 00:00:00',
    ShipDate ='0000-00-00 00:00:00'
    WHERE ID='$id' LIMIT 1"; 
    $result5 = mysql_query($sql, $conn_id); if ($result5){ 
    $infotext.="<br /> Times reduced OK. ";
    // $pagetext.="<p> Status reduced to Scheduled </p>";
    } else { 
    $infotext.="<br /><strong>An error occured ref cj4614 !</strong>".$sql; 
    $alerttext.="<p>Error ref cj4614 !</p>"; 
    }}
    
    if ($newstatus =='40') {
    $sql = "UPDATE Orders SET 
    waitingstarttime ='0000-00-00 00:00:00',
    collectiondate='0000-00-00 00:00:00',
    starttrackpause = '0000-00-00 00:00:00',
    finishtrackpause ='0000-00-00 00:00:00',
    ShipDate ='0000-00-00 00:00:00'
    WHERE ID='$id' LIMIT 1"; 
    $result5 = mysql_query($sql, $conn_id); if ($result5){ 
    $infotext.="<br /> Times cleared from job cj 4649. ";
    // $pagetext.="<p> On way to collection. </p>";
    } else { 
    $infotext.="<br /><strong>An error occured cd 4652!</strong>".$sql; 
    $alerttext.="<p>Error on cj 4652</p>"; 
    }}
    
    if ($newstatus =='50') {
    $sql = "UPDATE Orders SET 
    collectiondate='0000-00-00 00:00:00',
    starttrackpause = '0000-00-00 00:00:00',
    finishtrackpause ='0000-00-00 00:00:00',
    ShipDate ='0000-00-00 00:00:00'
    WHERE ID='$id' LIMIT 1"; 
    $result5 = mysql_query($sql, $conn_id); if ($result5){ 
    $infotext.="<br /> Times cleared from job cj 4666. ";
    // $pagetext.="<p> On way to collection. </p>";
    } else { 
    $infotext.="<br /><strong>An error occured cd 4666!</strong>".$sql; 
    $alerttext.="<p>Error on cj 4666</p>"; 
    }}
    
    
    if ($newstatus =='60') {
    $sql = "UPDATE Orders SET 
    finishtrackpause ='0000-00-00 00:00:00',
    ShipDate ='0000-00-00 00:00:00'
    WHERE ID='$id' LIMIT 1"; 
    $result5 = mysql_query($sql, $conn_id); if ($result5){ 
    $infotext.="<br /> Times cleared from job cj 4682. ";
    // $pagetext.="<p> On way to collection. </p>";
    } else { 
    $infotext.="<br /><strong>An error occured cd 4682!</strong>".$sql; 
    $alerttext.="<p>Error on cj 4682</p>"; 
    }}
    
    
    if ($newstatus =='65') {
    $sql = "UPDATE Orders SET 
    ShipDate ='0000-00-00 00:00:00'
    WHERE ID='$id' LIMIT 1"; 
    $result5 = mysql_query($sql, $conn_id); if ($result5){ 
    $infotext.="<br /> Times cleared from job cj 4694. ";
    // $pagetext.="<p> On way to collection. </p>";
    } else { 
    $infotext.="<br /><strong>An error occured cd 4694!</strong>".$sql; 
    $alerttext.="<p>Error on cj 4694</p>"; 
    }}
    
    
    
    } // ends status gone down
    
    
    
    // see if need to send email
    if (($oldstatus<$newstatus) and ($newstatus==='100')) {
    $infotext.='<br /> starts check to see if completed email needed';
    $qem1 = mysql_result(mysql_query("
    SELECT cemail1 FROM Clients 
    INNER JOIN Orders
    ON Clients.CustomerID = Orders.CustomerID
    WHERE Orders.ID = $ID LIMIT 0,1", $conn_id), 0);
    
    if ($qem1=='1') { 
    $infotext.='<br /> Will auto send completed email when code written!';
    }
    if ($qem1=='0') { 
    // $infotext.='<br /> Is Zero, does not send completed email';
    }
    
    
    
    
    
    $infotext.='<br /> starts check to see if tracking admin needed';
    
    
    $query="SELECT ID, status, trackerid, ShipDate, collectiondate, starttrackpause, finishtrackpause FROM Orders, Cyclist
    WHERE Orders.CyclistID = Cyclist.CyclistID
    AND Orders.ID = '$ID' LIMIT 1"; $result=mysql_query($query, $conn_id); $row=mysql_fetch_array($result);
    
    
    
    $thistrackerid=$row['trackerid'];
    
    $startpause=strtotime($row['starttrackpause']); 
    $finishpause=strtotime($row['finishtrackpause']); $collecttime=strtotime($row['collectiondate']); 
    $delivertime=strtotime($row['ShipDate']); if (($startpause > '10') and ( $finishpause < '10')) { $delivertime=$startpause; } 
    if ($startpause <'10') { $startpause='9999999999'; } if (($row['status']<'86') and ($delivertime < '200')) { $delivertime='9999999999'; } 
    if ($row['status']<'50') { $delivertime='0'; } if ($collecttime < '10') { $collecttime='9999999999'; } 
    
    
    
    
    
    
    $findlast="SELECT timestamp FROM `instamapper` 
    WHERE `device_key` = '$thistrackerid' 
    AND `timestamp` > '$collecttime' 
    AND `timestamp` NOT BETWEEN '$startpause' 
    AND '$finishpause' 
    AND `timestamp` < '$delivertime' 
    ORDER BY `timestamp` ASC 
    LIMIT 1"; 
    
    $sql_result2 = mysql_query($findlast,$conn_id)  or mysql_error(); 
    
    
    while ($res2 = mysql_fetch_assoc($sql_result2)) {
    
    
    $infotext.='<br /> res is '.$res2['timestamp'].' timestamp found';
    
    
    
    
    
    $sql="INSERT INTO cojm_admin 
    (cojm_admin_stillneeded, cojm_admin_job_ref, cojmadmin_tracking) 
        VALUES ('1', '$ID', '1' )   ";
    
    
        $result = mysql_query($sql, $conn_id);
    if ($result){
    $infotext.="<br />5220 Success adding admin job";
    // $pagetext.='<p>'.$globalprefrow['glob5'].' details updated</p>';
    
    
    $thiscyclist=mysql_insert_id(); 
    //  $newcyclistid=$thiscyclist;
    //     $pagetext.='<p>New '.$globalprefrow['glob5'].' '.$thiscyclist.' '.$cojmname.' created.</p>';
    $infotext.='<p>Admin Task '.$thiscyclist.' created.</p>'; 
    
    
    } else {
    $infotext.=mysql_error()." An error occured during setting admin q <br>".$sql;  
    $alerttext.=mysql_error()." <p>Error CJ5232 occured during update!</p>";
    } // ends 
    
    
    
    
    
    
    
    
    }
    
    
    $infotext.='<br /> finishes check to see if tracking admin needed';
    
    } // ends check raised to 100
    
    
    } // ends new and old status difference AND ends new status
    } // ends page=editui or editstatus 
    
    
    
    
    
    
    
    
    
    
    if ($page == "editcost" ) {
    
    $newcost=trim($_POST['newcost']);
    $vatband=trim($_POST['vatband']);
    
    
    // get vatbad from vatband in Services
    
    $infotext.="<br><b>Updating Job Cost</b><br>New Cost : ". $newcost;
    // $infotext.=$vatband.'<br /> and in globals '.
    // $infotext.=$globalprefrow['vatband'.$vatband];
    
    
    if (isset ($globalprefrow['vatband'.$vatband])) {
    
    $newvatcost=($newcost)*(($globalprefrow['vatband'.$vatband])/100);
    
    } else { $newvatcost='0.000';}
    
    //  $infotext.=$newvatcost;
    
    $sql = "UPDATE Orders SET FreightCharge='$newcost', vatcharge='$newvatcost', iscustomprice='1', clientdiscount='0.00' WHERE ID='$id' LIMIT 1"; 
    $result = mysql_query($sql, $conn_id);
    if ($result){ 
    $infotext.="<br />Cost Updated and locked to ".$newcost;
    $pagetext.="<p>Cost Updated and locked to ".$newcost.'</p>';
    } else { 
    
    $alerttext.="<p>An error occured during updating cost and VAT!</p>"; 
    $infotext.="<br />An error occured during updating cost and VAT!<br />".$sql; 
    
    }
    
    }// ends page=editcost
    
    
    
    } // these 2
    } // ends check to make sure job not modified by someone else at a time after the form was created
    
    
    } // ends time within global seconds check
    
    
    
    /////////////////////////////        RANDOM THINGS IN ID       MOVE TO CREATE NEW JOB SECTIONS    
    
    
    // checks for nextactiondate + 
    
    $query="SELECT * FROM Orders 
    where ID = '$id' LIMIT 1";
    $result=mysql_query($query, $conn_id);
    $row=mysql_fetch_array($result);
    
    if ($row['status'] <49  ){ $nextactiondate = $row['targetcollectiondate']; } else {$nextactiondate = $row['duedate']; }
    $sql = "UPDATE Orders SET nextactiondate='$nextactiondate' WHERE ID='$id' LIMIT 1";
    $result = mysql_query($sql, $conn_id); 
    if ($result){ 
    // $infotext.="<br />next action time updated"; 
    
    } else {
    $infotext.="<br />error occured during updating next action time ! ".$nextactiondate."</strong>"; 
    $alerttext.="<p>Error occured during updating next action time </p>"; 
    
    }
    
    
    if ($row['publictrackingref'] =='' ) {
    
    
    $length = 6;
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $chars_length = (strlen($chars) - 1);  // Length of character list
        $string = $chars{rand(0, $chars_length)}; // Start our string  
        for ($i = 1; $i < $length; $i = strlen($string)) // Generate random string
        {
            $r = $chars{rand(0, $chars_length)};  // Grab a random character from our list
            if ($r != $string{$i - 1}) $string .=  $r;  // Make sure the same two characters don't appear next to each other
        }
    
    $newsecurity_code=$id.$string; 
    // echo 'security code : '.$string.'<br>New Security code :'.$newsecurity_code.'<br>';
    // $infotext.="Generated tracking reference ".$newsecurity_code;
    
    $sql = "UPDATE Orders SET publictrackingref='$newsecurity_code' WHERE ID='$id' LIMIT 1";
    $result = mysql_query($sql, $conn_id); 
    if ($result){ 
    // $infotext.="<br >And added to database."; 
    } else { $infotext.="An error occured during adding the public tracking ref !<br>"; }
    
    } // ENDS CHECK FOR TRACKING REF
    
    
    
    
    
    
    
    
    
    
    
    
} //   ENDS CHECK FOR $id    // ////////////////////////////////////////////////////////////////////////////////////
    





/////////////////////////    FUNCTIONS    //////////////////////////////

Function calcmileage($ID, $distunit, $co2perdist, $pm10perdist)
{
GLOBAL $infotext; 
GLOBAL $globalprefrow;
GLOBAL $pagetext;
GLOBAL $alerttext;
$tempdist='';
$sql = "SELECT 
* 
FROM Orders, Clients, Services
WHERE Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
AND Orders.ID = ". $ID. " LIMIT 1";
$sql_result = mysql_query($sql)  or mysql_error();
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>0) {
while ($row = mysql_fetch_array($sql_result)) {

// $infotext.=' <br />calcmileage function 2011';

if ($ID) {

$row['enrpc0']=$row['CollectPC'];
// $infotext.=' <br />ID found : '.$ID;

// $infotext.=' <br />3618 CollectPC : '.$row['CollectPC'];

// $infotext.=' <br />enrPC0 : '.$row['enrpc0'];
// $infotext.=' <br />enrPC : '.$row['enrpc1'];
// $infotext.=' <br />enrPC : '.$row['enrpc2'];
// $infotext.=' <br />enrPC : '.$row['enrpc3'];
// $infotext.=' <br />enrPC : '.$row['enrpc4'];
// $infotext.=' <br />enrPC : '.$row['enrpc5'];
// $infotext.=' <br />enrPC : '.$row['enrpc6'];
// $infotext.=' <br />enrPC : '.$row['enrpc7'];
// $infotext.=' <br />enrPC : '.$row['enrpc8'];
// $infotext.=' <br />enrPC : '.$row['enrpc9'];
// $infotext.=' <br />enrPC : '.$row['enrpc10'];
// $infotext.=' <br />enrPC : '.$row['enrpc11'];
// $infotext.=' <br />enrPC : '.$row['enrpc12'];
// $infotext.=' <br />enrPC : '.$row['enrpc13'];
// $infotext.=' <br />enrPC : '.$row['enrpc14'];
// $infotext.=' <br />enrPC : '.$row['enrpc15'];
// $infotext.=' <br />enrPC : '.$row['enrpc16'];
// $infotext.=' <br />enrPC : '.$row['enrpc17'];
// $infotext.=' <br />enrPC : '.$row['enrpc18'];
// $infotext.=' <br />enrPC : '.$row['enrpc19'];
// $infotext.=' <br />enrPC : '.$row['enrpc20'];
// $infotext.=' <br />ShipPC : '.$row['ShipPC'];
$row['enrpc21']=$row['ShipPC'];

// $infotext.=' <br />enrpc21 : '.$row['enrpc21'];

// start of loop
$i='0';
$tempdist='';
if ($globalprefrow['inaccuratepostcode']<>'1') {

// $infotext.=' <br /> postcode flag is'.$globalprefrow['inaccuratepostcode'].'with no gap.';

$lastfoundpc1='';

while ($i<21) {
$j=$i+1;
$pc1 = str_replace (" ", "", strtoupper($row["enrpc$i"]));
$pc2 = str_replace (" ", "", strtoupper($row["enrpc$j"]));


if (($pc1) and ($pc2=='')) {
$lastfoundpc1=$pc1;
// $infotext = $infotext.'<br />First postcode found, no last postcode.';
// $infotext = $infotext.'<br />pc1 : '.$pc1.'<br />pc2 : '.$pc2.
// '<br />last found1 : '.$lastfoundpc1.'<br />last found2 : '.$lastfoundpc2;
}


if (($pc2) and ($pc1=='')) {
$pc1=$lastfoundpc1;
// $infotext = $infotext.'<br />Last postcode found, no first postcode.';
// $infotext = $infotext.'<br />pc1 : '.$pc1.'<br />pc2 : '.$pc2.
// '<br />last found1 : '.$lastfoundpc1.'<br />last found2 : '.$lastfoundpc2;
}

if (($pc1) and ($pc2)) {

$querypc1="SELECT * FROM  `postcodeuk` WHERE  `PZ_Postcode` =  '$pc1' LIMIT 1"; 
$result=mysql_query($querypc1); $pcrow1=mysql_fetch_array($result); 
$querypc2="SELECT * FROM  `postcodeuk` WHERE  `PZ_Postcode` =  '$pc2' LIMIT 1"; 
$result=mysql_query($querypc2); $pcrow2=mysql_fetch_array($result); 
$oGC = new GeoCalc(); 
$dDist = $oGC->EllipsoidDistance($pcrow1["PZ_northing"],$pcrow1["PZ_easting"],$pcrow2["PZ_northing"],$pcrow2["PZ_easting"]);

if ((!$pcrow1["PZ_easting"]) or (!$pcrow2["PZ_easting"])) {
$infotext.= '<strong>
<br />Collection PC '.$pc1.' not found, or <br />Delivery PC '.$pc2.' not found for location '.$i.'</strong>';

if (isset ($alerttext)) {} else { $alerttext=''; }


$alerttext.='<p><strong>'.$pc1.' or '.$pc2.' not found for location '.$i.'</strong></p>';




}
else {
// $infotext.=' <br />enrgap : '.$dDist;
$dDistMiles = ConvKilometersToMiles($dDist); 
// $infotext.=' <br />enrgap '.$i.' miles : '.$dDistMiles;
$tempdist=$tempdist+$dDist;
}
}
$i=$i+1;
} // ends i less 22

} else {     // ends check to ensure accurate postcode setup type

if (isset($_POST['distance'])) { $tempdist=trim($_POST['distance']); } else { $tempdist=''; }

// echo $tempdist;
// this is where we need the code to work out a non auto distance
// ending up with $tempdist in km
// $tempdist='1.23';

} // ends stuff to do without an accurate postcode


$dDistMiles = ConvKilometersToMiles($tempdist); 
if ($distunit=='km') { $tempdist=round($tempdist, 1); } else { $tempdist=round($dDistMiles, 1); }

// $infotext.= '<br />'.$tempdist.' '.$distunit;

$sql = "UPDATE Orders SET distance='$tempdist' WHERE ID = $ID LIMIT 1";
$result = mysql_query($sql);
if ($result){ 
    // $infotext.="<br />Distance updated to <strong>".$tempdist.'</strong>'; 
}
else { 

    $infotext.=mysql_error()."<br> <strong>An error occured during updating distance</strong>"; 
    $alerttext.="<p> <strong>An error occured during updating distance</strong><br />".mysql_error()."</p>"; 

}

$co2perdist=$co2perdist*$tempdist;
$pm10perdist=$pm10perdist*$tempdist;

// $infotext.="<br />co2 : ".$co2perdist;
// $infotext.="<br />pm10 : ".$pm10perdist;

// $infotext.='<br />rm : '.$row['RMcount'].' lice : '.  ($row['LicensedCount']);

if (($row['RMcount']) or ($row['LicensedCount'])) {

$co2perdist='';
$pm10perdist='';
}

// else { echo ($row['CO2Saved']*$row["numberitems"]); }
// if job is hourly rate

// $infotext.="<strong>Updating Emission Savings</strong><br>";
 $sql = "UPDATE Orders SET co2saving='$co2perdist' , pm10saving='$pm10perdist' WHERE ID='$ID' LIMIT 1"; 
 $result = mysql_query($sql);
// $infotext.='<br />'.$sql;
 
if ($result){ 
// $infotext.="<br />Emission savings updated"; 
} 
else { $infotext.="<br /><strong>An error occured during updating emissions savings</strong>"; } 



// echo $alerttext;


}
}

} /////////////////    END SCHECK FOR HAVING AN ID   /////////////////////////
} /////////////////   ENDS calcmileage function ////////////////////////////


/////////////////      FUNCTION FOR FORMATTING MONEY VALUES ////////////////////////////////////////
function formatMoney($money) { if (floor($money) == $money) { 

$money=number_format(($money), 0, '.', ',');



} 

else if (round($money, 1)==$money){ 


$money=number_format(($money), 1, '.', ',');



}

else { 

// $money=$money; 


$money=number_format(($money), 2, '.', ',');

}

return $money; } 



/////////////////      STARTS PLURAL FUNCTION                    ///////////////////////////
// eg echo plural($diff);
function plural($num) {
	if ($num != 1)
		return "s";
}
/////////////////      ENDS PLURAL FUNCTION                      ///////////////////////////


///////////////////////////////////   RECALC PRICE ///////////////////
if ($cojmaction=='recalcprice') {
$buildloopcharge='';

 $iscustomprice = mysql_result(mysql_query("
 SELECT iscustomprice 
 from Orders
 WHERE `Orders`.`ID`=$id 
 LIMIT 1
 ", $conn_id), 0);
 if ($iscustomprice=='0') {

 $infotext.='<br/>4513 Recalculating total price';

 

 
$ifcbbbuile = mysql_result(mysql_query("
 SELECT chargedbybuild 
 from Services 
 INNER JOIN Orders
 WHERE `Orders`.`ServiceID` = `Services`.`ServiceID`
 AND `Orders`.`ID`=$id 
 LIMIT 1
 ", $conn_id), 0);
 if ($ifcbbbuile=='1') { // mileage rate

$infotext.='<br />4531 about to Update 1st mile cost ';



$cbbnewcost = mysql_result(mysql_query("
SELECT cbbcost from chargedbybuild 
WHERE chargedbybuildid = 1
LIMIT 1
", $conn_id), 0); // gets 1st mile rate

$distance = mysql_result(mysql_query("
 SELECT distance 
 from Orders 
 WHERE `Orders`.`ID`=$id 
 LIMIT 1
", $conn_id), 0);

// $infotext.='<br/>4581 Distance is : '.$distance;

 
if ($distance>'0') {
// $infotext.='<br/>1st mile cost in total is : '.$cbbnewcost;
$buildloopcharge=$buildloopcharge+$cbbnewcost; 


// set cost on cbb1

 $sql = "UPDATE Orders 
 SET cbb1='$cbbnewcost' ,
 cbbc1='1'
 WHERE ID='$id' LIMIT 1"; 
 $result = mysql_query($sql, $conn_id);
// $infotext.='<br />4561'. $sql;


} else { 


 $sql = "UPDATE Orders 
 SET cbb1='0.00' ,
 cbbc1='0'
 WHERE ID='$id' LIMIT 1"; 
 $result = mysql_query($sql, $conn_id);




}



if ($distance>'1.00') {



 $sql = "UPDATE Orders 
 SET cbbc2='1',
 WHERE ID='$id' LIMIT 1"; 
 $result = mysql_query($sql, $conn_id);


$cbbnewcost = mysql_result(mysql_query("
SELECT cbbcost from chargedbybuild 
WHERE chargedbybuildid = 2
LIMIT 1
", $conn_id), 0); // gets 2nd mile rate





$cbbnewcost=($cbbnewcost*($distance-1)); 

// set cost on cbb2
 $sql = "UPDATE Orders 
 SET cbb2='$cbbnewcost'  ,
 cbbc2='1' 
 WHERE ID=$ID LIMIT 1"; 
 $result = mysql_query($sql, $conn_id);

 
// $infotext.='<br />4610 '. $result;


$buildloopcharge=$buildloopcharge+$cbbnewcost; 



} else { // ends dist greatrer than 1
$cbbnewcost='0.00';

// set cost on cbb2
 $sql = "UPDATE Orders 
 SET cbb2='0.00' ,
 cbbc2='0' 
 WHERE ID=$ID LIMIT 1"; 
 $result = mysql_query($sql, $conn_id);

// $infotext.='<br /> 4627 '. $sql;

 
}


// $infotext.='<br/>2nd mile sql and cost is : '.$sql.' ' .$cbbnewcost;


// set main price to zero

} else { // ends mileage rate, set service price : 



$tempcharge = mysql_result(mysql_query("
SELECT Price from Services 
INNER JOIN Orders
 WHERE `Orders`.`ServiceID` = `Services`.`ServiceID`
 AND `Orders`.`ID`=$id 
 LIMIT 1
", $conn_id), 0);
 $infotext.='<br/>Service Price : '.$tempcharge;


$numberitems = mysql_result(mysql_query("
SELECT numberitems from Orders
 WHERE `Orders`.`ID`=$id 
 LIMIT 1
", $conn_id), 0);

// $infotext.='<br/>Number Items : '.$numberitems;

$buildloopcharge=$buildloopcharge+($numberitems*$tempcharge);


// set cbb1 and 2 to zero


 
} // ends chck for distance / non-distance (non distance bit)




 
 // starts 2nd phase check box pricing
 

 

 
 $cbbnewcost = mysql_result(mysql_query("
SELECT cbbc2 from Orders 
 WHERE ID='$id'
LIMIT 1
", $conn_id), 0); // test


 
 
// $infotext.='<br /> cbbc2 is '.$cbbnewcost;
 
 
 
 
 
 
 
 
 
 $ifcbbbuild = mysql_result(mysql_query("
 SELECT chargedbycheck 
 from Services 
 INNER JOIN Orders
 WHERE `Orders`.`ServiceID` = `Services`.`ServiceID`
 AND `Orders`.`ID`=$id 
 LIMIT 1
 ", $conn_id), 0);
 if ($ifcbbbuild=='1') { // uses tick boxes

 $infotext.='<br/>Using tick boxes';

 
 
 

$query="
SELECT * FROM chargedbybuild 
WHERE chargedbybuildid > 2
ORDER BY cbborder ASC "; 

$sql_result = mysql_query($query,$conn_id)  or mysql_error(); 
while ($cbbrow = mysql_fetch_array($sql_result)) { extract($cbbrow);


// $infotext.='<br/> 4689 start loop charge :'.$buildloopcharge. ' cbbrow : ' .$chargedbybuildid;

 $calcsql="
 SELECT cbbc$chargedbybuildid 
 from Orders 
 WHERE `Orders`.`ID`=$id 
 LIMIT 1
";
  $docalc = mysql_result(mysql_query($calcsql, $conn_id), 0);
 
// $infotext.='<br />  tickbox for cbb'.$chargedbybuildid .' is '.$docalc;




if ($chargedbybuildid=='3') {
// if ($docalc=='1') {

$cbbwaitingcost = mysql_result(mysql_query("
SELECT waitingmins 
from Orders 
 WHERE ID='$id' LIMIT 1
", $conn_id), 0);

// $infotext.='<br/>Waiting time per 5 mins is : '.$cbbcost;
$cbbcost=(($cbbwaitingcost/5)*$cbbcost);

// $infotext.='<br/>Waiting cost docalc : '.$docalc.' in total is : '.$cbbcost;
$buildloopcharge=$buildloopcharge+$cbbcost;

 $sql = "UPDATE Orders 
 SET cbb$chargedbybuildid='$cbbcost' 
 WHERE ID='$id' LIMIT 1"; 
 
 $result = mysql_query($sql, $conn_id);
 if ($result){ 
 // $infotext.="<br />Order was updated ". $newcost .""; 
 } 
 else { $infotext.="<br /><strong>An error occured during updating cbb price</strong>"; } 
// }
}

if ($chargedbybuildid>3) {
if ($docalc=='1') { 

// $infotext.='<br/>Found charge  : '.$cbbmod.' '.$cbbcost;

if ($cbbmod=='x') { 
$cbbcost=($cbbcost/'100');
$cbbcost=(($buildloopcharge*$cbbcost)-$buildloopcharge);
// $infotext.='tempcharge : '.$cbbcost;
}

 $sql = "UPDATE Orders 
 SET cbb$chargedbybuildid='$cbbcost' 
 WHERE ID='$id' LIMIT 1"; 
 
 $result = mysql_query($sql, $conn_id);
 if ($result){ 
// $infotext.="<br />cbb ".$chargedbybuildid."  was updated ". $cbbcost .""; 
 } 
 else { $infotext.="<br /><strong>An error occured during updating cbb price</strong>"; } 

$buildloopcharge=$buildloopcharge+$cbbcost;

}

if ($docalc<>'1') { 
 $sql = "UPDATE Orders 
 SET cbb$chargedbybuildid='0.00' 
 WHERE ID='$id' LIMIT 1"; 
 
 $result = mysql_query($sql, $conn_id);
 if ($result){ 
 // $infotext.="<br />Order was updated ". $newcost .""; 
 } 
 else { $infotext.="<br /><strong>An error occured during updating cbb price</strong>"; } 

} // ends docalc<>1
} // ends buildid > 3
} // ends loop for jobs


// $infotext.='<br/>Total Build charge  : '.$buildloopcharge;


}  // ends using tick boxes






// $infotext.='<br/>Temp charge : '.$pricebeforediscount;

$cdiscount = mysql_result(mysql_query("
SELECT cbbdiscount from Clients
INNER JOIN Orders
WHERE Orders.CustomerID = Clients.CustomerID 
AND Orders.ID=$id 
LIMIT 1
", $conn_id), 0);
// $infotext.='<br/>Client Discount Percentage : '.$cdiscount;

$cdiscount=((100-$cdiscount)*0.01);
$priceexvat=$cdiscount*$buildloopcharge;
$clientdiscount=$buildloopcharge-$priceexvat;

// $infotext.='<br/>5260 Discount to client : '.$clientdiscount;
// $infotext.='<br/>5262 New ex-VAT Charge : '.$priceexvat;





// get services vatband

$vatband = mysql_result(mysql_query("
SELECT vatband from Services 
INNER JOIN Orders
WHERE `Orders`.`ServiceID` = `Services`.`ServiceID`
AND `Orders`.`ID`=$id 
LIMIT 1
", $conn_id), 0);

$newvatcost='0.000'; 

if ($vatband<>'0')  { 

 $infotext.='<br />6128 vatband is '.$vatband; 

 


// if (isset($globalprefrow['vatband'].$vatband)) {

$newvatcost=($priceexvat)*(($globalprefrow['vatband'.$vatband])/100);

$newvatcost=round($newvatcost, 2);

 $infotext.='<br/>VAT cost : '.$newvatcost;

  }

// else { $newvatcost='0.000'; }


 $sql = "UPDATE Orders 
 SET FreightCharge='$priceexvat', 
 vatcharge='$newvatcost', 
 clientdiscount='$clientdiscount' 
 WHERE ID='$id' LIMIT 1"; 
 
 $result = mysql_query($sql, $conn_id);
 if ($result){ $infotext.="<br />Cost updated to ". ($priceexvat+$newvatcost) .""; } 
 else { $infotext.="<br /><strong>An error occured during updating main pricing</strong>"; } 

// $infotext.='<br />5300 discount : '.$clientdiscount;
 
// } // ends random variable 

} // ends check for not to change custom price
} // ends cojmaction




if (($page<>'newjobfromajax')) {

// $ID=$origid;
$id=$origid;

}


if ($page=='createnewfromexisting') { $id=$newjobid; }



$caudtext='<hr><b>'. $cyclistid.' : '.$today.'</b>'. $infotext; 



// $sql = "UPDATE Orders SET caud=concat (caud,'$caudtext') WHERE ID='$id' LIMIT 1"; $result = mysql_query($sql, $conn_id);

// $infotext.='<br />ID found : '.$id;
 
 $orderauditid=$id;
 
// if ($page) { $infotext.='<br /> page is '.$page; }

// $infotext.='<br /> 4351 Collect : '.$collectpc.' Deliver : '.$deliverpc;



  // A SCRIPT TIMER
    $now_time = microtime(TRUE);
    $cj_lapse_time = $now_time - $cj_time;
    $cj_msec = $cj_lapse_time * 1000.0;
    $cj_echo = number_format($cj_msec, 1);
	
//    $infotext.= "<br />Changed job in $ch_echo ms.";
	
	$infotext.="";
	
	
	
//	include "phpmysqlautobackup/run3.php";
	
	

?>