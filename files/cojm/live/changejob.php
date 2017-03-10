<?php 
/*
    COJM Courier Online Operations Management
	changejob.php - Handles non-ajax job changes ( the controller in MVC language :-), also see ajaxchangejob.php
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


$cj_time = microtime(TRUE);

$origid='';


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

// include("GeoCalc.class.php");



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


    if (isset($_POST['vertices'])) { $vertices=trim($_POST['vertices']); } else { $vertices=''; }
    // vertices used in a few pages

    if ($page=='uploadkml') {

        $allowedExts = array("kml","KML");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        $infotext.='<p>Type: '.$_FILES["file"]["type"].' </p>';
        
        if (in_array($extension, $allowedExts)) {
            
        // $pagetext.='<p> Allowed </p>';
            
            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
            } else {
                $fileName = $label.$_FILES["file"]["name"];
                //        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
                //        echo "Type: " . $_FILES["file"]["type"] . "<br>";
                //        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
                //        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
                

                $tmpName=$_FILES["file"]["tmp_name"];		
                
                $fp      = fopen($tmpName, 'r');
                $content = fread($fp, filesize($tmpName));
                // $content = addslashes($content);
                fclose($fp);  
                
                if(!get_magic_quotes_gpc()) {
                    $fileName = addslashes($fileName);
                }
                
                $file_contents = file_get_contents($tmpName);

                $saveto='cache/'.$fileName;

                
                // file_put_contents($saveto,$content);
                
                $myfile = fopen($saveto, "w");
                fwrite($myfile, $content);
                fclose($myfile);
                
                
                $xml = simplexml_load_file($saveto);
                
                if ($xml) {
                    $placemarks = $xml->Folder->Placemark->Polygon->outerBoundaryIs->LinearRing->coordinates;   
                    // $infotext.='<p>Coords '.$placemarks.' </p>';
                    $cor_d  =  explode(' ', $placemarks);
                    $qtmp=array();
                    foreach($cor_d as $value){
                        if (trim($value)) {
                        $tmp = explode(',',$value);
                        $ttmp=$tmp[1];
                        $tmp[1]=$tmp[0];
                        $tmp[0]=$ttmp; 
                        $qtmp[]= '' . $tmp[0] . ' ' .$tmp[1].'';
                        }
                    }
                                        
                    $vertices= join(", ",$qtmp);

                    // $pagetext.=' vertices '. $vertices;
                    $pagetext.='<p>Uploaded '.$fileName.' </p>';
                }
            }
        } else {
            $pagetext.='Issue uploading kml, please recheck and retry. ';
        }
    }

    
    if ($vertices) {
            $infotext.=' vertices: '.$vertices;
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

        
    if ($page=='uploadkml') {
            if (isset($_POST['areaname'])) {
                $areaname=trim($_POST['areaname']);
                
                         $area = sprintf("POLYGON((%s))", $vertices); 
            $sql="INSERT INTO opsmap (type,opsname, g) VALUES ( 
            '2', 
            :areaname,
            PolygonFromText(:vertices) );";
   
                
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':areaname', $areaname, PDO::PARAM_INT); 
            $stmt->bindParam(':vertices', $area, PDO::PARAM_INT); 
    
            $stmt->execute();
            $result = $dbh->lastInsertId();
                
            if ($result){
                $infotext.="<br />Success";
                $pagetext.='<p>Success</p>';
                $pagetext.='<p>New area '.$areaname.' created from KML File.</p>';
                $infotext.='<p>New OpsMapArea '.$result.' created.</p>';
                $areaid=$result;
                global $areaid;
            }
                
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
            if (($areaid) and ($vertices) and ($areaname)) {
                $query = " UPDATE opsmap 
                SET inarchive=:inarchive, 
                opsname=:opsname, 
                descrip=:descrip, 
                g= PolygonFromText(:vertices),
                istoplayer=:istoplayer,
                corelayer=:corelayer 
                WHERE opsmapid=:opsmapid ";
                
                $area = sprintf("POLYGON((%s))", $vertices); 
                
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':inarchive', $inarchive, PDO::PARAM_INT); 
                $stmt->bindParam(':opsname', $areaname, PDO::PARAM_INT);
                $stmt->bindParam(':descrip', $areacomments, PDO::PARAM_INT); 
                $stmt->bindParam(':vertices', $area, PDO::PARAM_STR);
                $stmt->bindParam(':istoplayer', $istoplayer, PDO::PARAM_INT); 
                $stmt->bindParam(':corelayer', $corelayer, PDO::PARAM_INT);                    
                $stmt->bindParam(':opsmapid', $areaid, PDO::PARAM_INT);                    
                
                $stmt->execute();

                $pagetext.='<p>Edited area '.$areaname.'.</p>';
                $infotext.='<p>Edited OpsMapArea '.$areaid.' </p>';
            } else {
                $infotext.=' No vertices or areaid or name passed ';
                $alerttext.=' No vertices or areaid or name passed ';
            }
        } // ends page editarea

    
    if ($page=='opsmapnewarea') {
            $infotext =$infotext. ' new ops map area ';
            if (isset($_POST['areaname'])) { $areaname=trim($_POST['areaname']);} else {$areaname=''; }
            if (isset($_POST['areacomments'])) { $areacomments=trim($_POST['areacomments']);} else { $areacomments=''; }
            if (isset($_POST['inarchive'])) { $inarchive=trim($_POST['inarchive']); } else { $inarchive='0'; } // Show Working Windows
            if (isset($_POST['istoplayer'])) { $istoplayer=trim($_POST['istoplayer']); } else { $istoplayer='0'; } // Show Working Windows
            if (isset($_POST['corelayer'])) { $corelayer=trim($_POST['corelayer']);} else { $corelayer='0'; }
            $area = sprintf("POLYGON((%s))", $vertices); 
            $sql="INSERT INTO opsmap (type,opsname,istoplayer,corelayer,descrip, g) VALUES ( 
            '2', 
            :areaname,
            :istoplayer,
            :corelayer,
            :areacomments,
            PolygonFromText(:vertices) );";

            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':areaname', $areaname, PDO::PARAM_INT); 
            $stmt->bindParam(':istoplayer', $istoplayer, PDO::PARAM_INT); 
            $stmt->bindParam(':corelayer', $corelayer, PDO::PARAM_INT); 
            $stmt->bindParam(':areacomments', $areacomments, PDO::PARAM_INT); 
            $stmt->bindParam(':vertices', $area, PDO::PARAM_INT); 
    
            $stmt->execute();
            $result = $dbh->lastInsertId();
                
            if ($result){
                $infotext.="<br />Success";
                $pagetext.='<p>Success</p>';
                $pagetext.='<p>New area '.$areaname.' created.</p>';
                $infotext.='<p>New OpsMapArea '.$result.' created.</p>';
                $areaid=$result;
                global $areaid;
            }

            $page='editarea';	

        } // ends page= opsmapnewarea
    


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


 try {

        $dbh->prepare($sql)->execute([$emailfrom, 
        $emailbcc, 
        $emailfromname, 
        $htmlemailheader, 
        $emailbody, 
        $htmlemailbody, 
        $emailfooter, 
        $htmlemailfooter, 
        $email1, 
        $email2, 
        $email3, 
        $email4,
        $email5,
        $email6,
        $email7,
        $email8,
        $email9,
        $email10,
        $email11,
        $email12,
        $email13,
        $email14,
        $email15,
        $email16,
        $email17,
        $email18,
        $email19,
        $email20]);
        

$infotext.="<br />Updated Email Setup"; 
$pagetext.="<p>Updated Email Setup</p>"; 
} 
catch(PDOException $e) { $alerttext.= $e->getMessage(); $infotext.= $e->getMessage(); }



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
        
        
        $query=" SELECT `PZ_Postcode`
        FROM  `postcodeuk` 
        WHERE  `PZ_Postcode` 
        LIKE  ? LIMIT 0,1
        ";



        $parameters = array($newpc);
        $statement = $dbh->prepare($query);
        $statement->execute($parameters);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

    
    
    if ($row) {
    
        // $infotext.='<br />ifexistingpc : '.$ifexistingpc; 
        // $infotext.='<br />lat : '.$lat; 
        // $infotext.='<br />lon : '.$lng; 
        try {
            $sql = "UPDATE `postcodeuk` 
            SET  `PZ_zero` =  '1' ,
            `PZ_northing` =  ? ,
            `PZ_easting` =  ?
            WHERE 
            `PZ_Postcode` =  ? LIMIT 1;";
            
            $dbh->prepare($sql)->execute([$lat,$lng,$newpc]);
            
            $infotext.='<br /> Postcode updated ';
            $pagetext.='<p> Postcode '.$newpc.' updated </p>';
            
        }
        catch(PDOException $e) { $alerttext.= $e->getMessage(); $infotext.= $e->getMessage(); }
        
    } else {  // ends no existing pc
    
        try {
            $sql="INSERT INTO `postcodeuk` (`PZ_Postcode`, `PZ_northing`, `PZ_easting`, `PZ_zero`) VALUES 
            (?, ?, ?, '1');";
            $dbh->prepare($sql)->execute([$newpc,$lat,$lng]);
            $pagetext.='<p> Postcode added </p>';
            $infotext.='<br /> Postcode added ';
        }
        catch(PDOException $e) { $alerttext.= $e->getMessage(); $infotext.= $e->getMessage(); }
    } // ends existing postcode

    if ($id) {
    
        // calls recalc mileage in case page has come from an id, not menu
        $calcmileage=1;
        $cojmaction='recalcprice';
        include 'ajaxchangejob.php';

    }
    
    
    } // ends page =editpostcode



    if ($page=='editglobalstatus') {
        // $infotext.='<br />Editing Status Text';
        $i=1;
        While ($i < 125) {
            if (isset($_POST["statusid$i"])) {
                if (($_POST["statusid$i"])=='1') {
                    $statusname=trim($_POST["statusname$i"]);
                    $publicstatusname=trim($_POST["publicstatusname$i"]);
                    $infotext.= '<br />'.$i.' '.$activestatus.' '.$statusname.' '.$publicstatusname;
                    try {
                        $sql = "UPDATE status SET statusname=?, publicstatusname=? WHERE status=? LIMIT 1";
                        $dbh->prepare($sql)->execute([$statusname,$publicstatusname,$i]);
                    }
                    catch(PDOException $e) {
                        $alerttext.= $e->getMessage(); $infotext.= $e->getMessage();
                        $alerttext.='<p>Problem updating status '.$i.'</p>';
                        $infotext.="<br /><strong>An error occured during updating updating status".$i."!</strong>".$sql;                        
                    }
                } // ends check for activeid
            } // ends isset check
            $i++;
        }
        $pagetext.='<p>Statuses updated</p>';
        $infotext.='<p>Status update complete</p>';
    } // ends edit global status text




    if ($page=="editcyclistdetails") {
        $infotext.= '<br />Editing Cyclist Details';    
        
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
        } else {
            $dobdate='';
        }
        
        
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
        } else {
            $startdate='';
        }
        
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
        
        $cyclistjoomlanumber=trim($_POST['cyclistjoomlanumber']);
        
        $sql = "SELECT CyclistID FROM Cyclist 
        WHERE `Cyclist`.`CyclistID` <> ?  
        AND `Cyclist`.`isactive` = '1'      
        AND  `Cyclist`.`cojmname` = ? LIMIT 1 ";
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$thiscyclist,$cojmname]);
        $userExists = $stmt->fetchColumn();


        if ($userExists) {
            $alerttext.='<p><strong>'.$cojmname.' already exists, please use a different name.</strong></p>';
            $infotext.='<br /><strong>'.$cojmname.' already exists</strong>';
        } else {
            if (($thiscyclist) and ($cojmname)) {
                $sql = "UPDATE Cyclist SET 
                poshname=(UPPER(?)) , 
                isactive=? ,  
                cojmname=(UPPER(?)) , 
                mobilenumber=? , 
                trackerid=? ,  
                cyclistjoomlanumber=? ,
                housenumber=(UPPER(?)) , 
                streetname=(UPPER(?)) , 
                city=(UPPER(?)) , 
                postcode=(UPPER(?)) ,  
                contractstartdate=? , 
                icename=(UPPER(?)) , 
                icenumber=(UPPER(?)) , 
                notes=(UPPER(?))
                WHERE CyclistID = ? LIMIT 1 ";
                
                try {
                    $sth=$dbh->prepare($sql);
                    $data=array($poshname,$isactive,$cojmname,$mobilenumber,$trackerid,$cyclistjoomlanumber,$housenumber,$streetname,$city,$postcode,$startdate,$icename,$icenumber,$notes,$thiscyclist);
                    $sth->execute($data);
                    $infotext.="<br />Success";
                    $pagetext.='<p>'.$globalprefrow['glob5'].' details updated</p>';                    
                }
            
                catch(PDOException $e) {
                    $alerttext.= $e->getMessage();
                    $infotext.= $e->getMessage();
                    $infotext.=" An error occured during update!<br>".$sql;  
                    $alerttext.=" <p>An error occured during update!</p>";
                }

            
                if (!$mobdevice) {
                    
                    $sql = "UPDATE Cyclist SET 
                    DOB=? , 
                    ninumber=(UPPER(?)) , 
                    sortcode=? , 
                    accountnum=? , 
                    bankname=(UPPER(?))
                    WHERE CyclistID=?
                    LIMIT 1"; 
        
                    try {
                        $sth=$dbh->prepare($sql);
                        $data=array($dobdate,$ninumber,$sortcode,$accountnum,$bankname,$thiscyclist);
                        $sth->execute($data);
                        $infotext.="<br />Success";
                        $pagetext.='<p>'.$globalprefrow['glob5'].' Personal details updated</p>';                    
                    }
                
                    catch(PDOException $e) {
                        $alerttext.= $e->getMessage();
                        $infotext.= $e->getMessage();
                        $infotext.=" An error occured during personal details update!<br>".$sql;  
                        $alerttext.=" <p>An error occured during update!</p>";
                    }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
                } // ends mobile device check            
        
            } // ends cojmname
        }
    } // ends page=editcyclist details



    if ($page=='addnewcyclist') {
        $infotext.='<p><b>New Cyclist</b></p>'; 
    
        if (isset($_POST['CompanyName'])) { $cojmname=trim($_POST['CompanyName']); }
        if (isset($_POST['CompanyName'])) { $poshname=trim($_POST['CompanyName']); }
        

        $trackerid=mt_rand(1000099, 99999999);
        
        

        $sql = "SELECT CyclistID FROM Cyclist 
        WHERE `Cyclist`.`CyclistID` <> ?  
        AND `Cyclist`.`isactive` = '1'      
        AND  `Cyclist`.`cojmname` = ? LIMIT 1 ";



        $sql = "SELECT CyclistID FROM Cyclist WHERE trackerid = ? LIMIT 1";
        
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$trackerid]);
        $alreadyExists = $stmt->fetchColumn();
        if ($alreadyExists) {
            $trackerid=mt_rand(100099, 99999999);
        }
        
        
        $sql="INSERT INTO Cyclist 
            (poshname, isactive, cojmname, trackerid) 
            VALUES 
            (?,'1',?,?); ";
        
        
        try {
            $dbh->prepare($sql)->execute([$poshname, $cojmname, $trackerid]);
            $infotext.="<br />Success";
            $pagetext.='<p>'.$globalprefrow['glob5'].' details updated</p>';
        
        
            $thiscyclist = $dbh->lastInsertId();
            $newcyclistid=$thiscyclist;
            $pagetext.='<p>New '.$globalprefrow['glob5'].' '.$thiscyclist.' '.$cojmname.' created.</p>';
            $infotext.='<p>New Rider No. '.$thiscyclist.' created.</p>'; 
        
        }
        catch(PDOException $e) {
            $alerttext.= $e->getMessage();
            $infotext.= $e->getMessage();
            $infotext.=" An error occured during update!<br>".$sql;  
            $alerttext." <p>An error occured during update!</p>";
        }
        
    
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
        
        if ($thisserviceid) {
        
            $sql = "UPDATE Services SET Service=? ,
            servicecomments=? , 
            Price=? , 
            serviceorder=? ,
            CO2Saved=? , 
            PM10Saved=? , 
            LicensedCount=? , 
            UnlicensedCount=? , 
            batchdropcount=? , 
            hourlyothercount=? , 
            RMcount=? , 
            slatime=? , 
            sldtime=? , 
            asapservice=? ,
            cargoservice=? , 
            vatband=? ,
            activeservice=? , 
            isregular=? ,
            chargedbybuild=? ,
            chargedbycheck=? ,
            canhavemap=?
            WHERE ServiceID=? LIMIT 1"; 
            
            try {
                $dbh->prepare($sql)->execute([$Service, $servicecomments, $Price,$serviceorder,$CO2Saved,$PM10Saved,$LicensedCount,$UnlicensedCount,$batchdropcount,$hourlyothercount,$RMcount,$slatime,$sldtime,$asapservice,$cargoservice,$vatband,$activeservice,$isregular,$chargedbybuild,$chargedbycheck,$canhavemap,$thisserviceid]);
                $infotext.="<br />Update service Success"; 
                $pagetext.="<p>".$Service." Updated</p>";
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $infotext.=" An error occured during update!<br>".$sql;  
                $alerttext." <p>An error occured during Service update!</p>";
            }
        }
        
        
        
        
        
        if ($thisserviceid=='') {
            if ($Service) {
                $infotext.='<p><strong>New Service</strong></p>';

                $sql="INSERT INTO Services 
                (Service,activeservice  ) 
                    VALUES
                ('New Service ','1'   )
                ";
                
                
                
                try {
                    $dbh->prepare($sql)->execute();
                    $thisserviceid = $dbh->lastInsertId();
                    $pagetext.='<p>New service '.$thisserviceid.' '.$Service.' Created.</p>';
                    $infotext.='<br />New service '.$thisserviceid.' '.$Service.' Created.'.$sql;
                    }
                    
                catch(PDOException $e) {
                    $alerttext.= $e->getMessage();
                    $infotext.= $e->getMessage();
                    $infotext.=" An error occured during New Service!<br>".$sql;  
                    $alerttext." <p>An error occured during update!</p>";
                }
                
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
            CompanyName=? , 
            EmailAddress=? , 
            invoiceEmailAddress=? , 
            PhoneNumber=?, 
            Title=? , 
            Forename=? , 
            Surname=? , 
            MobileNumber=? , 
            Address=? , 
            Address2=? , 
            invoiceAddress=? , 
            invoiceAddress2=? , 
            co2apiref=? , 
            isactiveclient=? , 
            City=? , 
            County=? , 
            Postcode=(UPPER(?)) , 
            CountryOrRegion=? , 
            Notes=? ,
            htmlemail=? , 
            JoomlaUser=? , 
            JoomlaUser2=? , 
            JoomlaUser3=? , 
            invoiceCity=? , 
            invoiceCounty=? , 
            invoicePostcode=(UPPER(?)) , 
            invoiceterms=? ,
            invoiceCountryOrRegion=? ,
            invoicetype=? ,
            cbbdiscount=? ,
            defaultfromtext=(UPPER(?)) ,
            defaulttotext=(UPPER(?)) ,
            defaultrequestor=(UPPER(?)) ,
            defaultservice=? ,
            clientvatno=(UPPER(?)) ,
            clientregno=(UPPER(?)) ,
            isdepartments=?,
            cemail1=?,
            cemail2=?,
            cemail3=?,
            cemail4=?,
            cemail5=?
            WHERE CustomerID=? LIMIT 1";
            try {
                $dbh->prepare($sql)->execute([$CompanyName, $emailaddress, $invoiceEmailAddress,$PhoneNumber,$Title,$Forename,$Surname,$MobileNumber,$Address,$Address2,$invoiceAddress,$invoiceAddress2,$co2apiref,$isactiveclient,$city,$County,$Postcode,$CountryOrRegion,$Notes,$htmlemail,$JoomlaUser,$JoomlaUser2,$JoomlaUser3,$invoiceCity,$invoiceCounty,$invoicePostcode,$invoiceterms,$invoiceCountryOrRegion,$invoicetype,$cbbdiscount,$defaultfrom,$defaultto,$defaultrequestor,$defaultservice,$clientvatno,$clientregno,$isdepartments,$cemail1,$cemail2,$cemail3,$cemail4,$cemail5,$clientid]);
                $infotext.="<br />Success!"; 
                $pagetext.="<p>Details updated for ".$CompanyName.".</p>"; 
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $infotext.=" An error occured during update!<br>".$sql;  
                $alerttext." <p>An error occured during Client update!</p>";
            }
        } // ends check to see if client updated, not new client
    } // ends page=editclient
 


    if ($page=='createnewcl') {
        if (isset($_POST['CompanyName'])) {
            $CompanyName=trim($_POST['CompanyName']);
            if ($CompanyName) {
                $infotext.='<br />New client';
                
                $sql="INSERT INTO Clients 
                (CompanyName, isactiveclient ) 
                    VALUES
                (?, '1' ) ";               
                
                try {
                    $dbh->prepare($sql)->execute([$CompanyName]);
                    $clientid = $dbh->lastInsertId();
                    $infotext.=" New Client!<br>".$clientid.' '.$CompanyName;
                    $pagetext.=" <p>New Client</p>".$clientid." ".$companyname;                
                }
                catch(PDOException $e) {
                    $alerttext.= $e->getMessage();
                    $infotext.= $e->getMessage();
                    $infotext.=" An error occured New Client!<br>".$sql;  
                    $alerttext." <p>An error occured during update!</p>";
                }
            }
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
                depname=? ,
                isactivedep=? , 
                deppassword=? , 
                deprequestor=(UPPER(?)) , 
                depemail=? , 
                depphone=? , 
                depservice=? , 
                depcomment=? , 
                depdeffromft=(UPPER(?)) ,
                depdeftoft=(UPPER(?)) , 
                depaddone=? , 
                depaddtwo=? , 
                depaddthree=? , 
                depaddfour=? , 
                depaddfive=? , 
                depaddsix=(UPPER(?)) ,
                depjoom=?
                WHERE depnumber=? 
                AND associatedclient=?
                LIMIT 1"; 
                try {
                    $dbh->prepare($sql)->execute([$depname, $isactivedep, $deppassword,$deprequestor,$depemail,$depphone,$depservice,$depcomment,$depdeffromft,$depdeftoft,$depaddone,$depaddtwo,$depaddthree,$depaddfour,$depaddfive,$depaddsix,$depjoom,$i,$clientid]);
                    $infotext.="<br />Updated ".$depname;
                }
                catch(PDOException $e) {
                    $alerttext.= $e->getMessage();
                    $infotext.= $e->getMessage();
                    $infotext.=" An error occured during update!<br>".$sql;  
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
        
            $sql = "SELECT * FROM Clients WHERE CustomerID = ? ";
            $statement = $dbh->prepare($sql);
            $statement->execute([$clientid]);
            $clrow = $statement->fetch(PDO::FETCH_ASSOC);
            
            $infotext.='<br />'. $clrow['Address'].', '. $clrow['Address2'].'. '. $clrow['City'].', 
            '. $clrow['County'].', '. $clrow['CountryOrRegion'].'. '. $clrow['Postcode']; 
            
            $depaddone=trim($clrow['Address']);
            $depaddtwo=trim($clrow['Address2']);
            $depaddthree=trim($clrow['City']);
            $depaddfour=trim($clrow['County']);
            $depaddfive=trim($clrow['CountryOrRegion']);
            $depaddsix=trim($clrow['Postcode']);
            
            
            $sql="INSERT INTO clientdep 
            ( associatedclient,
            isactivedep ,
            depaddone ,
            depaddtwo ,
            depaddthree ,
            depaddfour ,
            depaddfive ,
            depaddsix ,
            depname) 
            VALUES
            (?,
            '1',
            ? ,
            ? ,
            ? ,
            ?,
            ? ,
            ? ,
            ? ) ";             
            
        
            try {
                $dbh->prepare($sql)->execute([$clientid,$depaddone,$depaddtwo,$depaddthree,$depaddfour,$depaddfive,$depaddsix,$newdepname]);
                $newdepid = $dbh->lastInsertId();
                $infotext.=" New Dep!<br>".$newdepid;
                $pagetext.=" <p>New Dep</p>".$newdepid;                
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $infotext.=" An error occured New Department!<br>".$sql;  
                $alerttext." <p>An error occured during New Department!</p>";
            }            
        
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
                favadrft=(UPPER(?)) , 
                favadrpc=(UPPER(?)) ,
                favadrcomments=(UPPER(?))
                WHERE favadrid=? LIMIT 1";
                
                try {
                    $dbh->prepare($sql)->execute([$favadrft, $favadrpc, $favadrcomments,$thisfavadrid]);
                    $infotext.="<br />Success!"; 
                    $pagetext.="<p>Details updated for ".$favadrft." - Job Details Unchanged.</p>"; 
                }
                catch(PDOException $e) {
                    $alerttext.= $e->getMessage();
                    $infotext.= $e->getMessage();
                    $infotext.=" An error occured during update!<br>"; 
                    $alerttext.="<p>An error occured during update!</p>"; 
                }
            } // ends quick edit from order via redir.php
        }  // ends if ($page=='aftercheckaseditfav') 


        if ($page=='editthisfavadr') {

            $infotext.='<br />Editing Favourite details';
            if (($favadrclient) and ($thisfavadrid<>'')) {
                $sql = "UPDATE cojm_favadr SET 
                favadrft=(UPPER(?)) , 
                favadrpc=(UPPER(?)) ,
                favadrclient=? ,
                favadrisactive=? ,
                favadrcomments=(UPPER(?)),
                favusr1=? , 
                favusr2=? , 
                favusr3=? , 
                favusr4=? , 
                favusr5=? , 
                favusr6=? , 
                favusr7=? , 
                favusr8=? , 
                favusr9=? , 
                favusr10=? , 
                favusr11=? , 
                favusr12=? , 
                favusr13=? , 
                favusr14=? , 
                favusr15=? , 
                favusr16=? , 
                favusr17=? , 
                favusr18=? , 
                favusr19=? , 
                favusr20=?  
                WHERE favadrid=? LIMIT 1";
                
                try {
                    $dbh->prepare($sql)->execute([$favadrft,$favadrpc,$favadrclient,$favadrisactive,$favadrcomments,$favusr1,$favusr2,$favusr3,$favusr4,$favusr5,$favusr6,$favusr7,$favusr8,$favusr9,$favusr10,$favusr11,$favusr12,$favusr13,$favusr14,$favusr15,$favusr16,$favusr17,$favusr18,$favusr19,$favusr20,$thisfavadrid]);
                    $infotext.="<br />Success!"; 
                    $pagetext.="<p>Details updated for ".$favadrft.".</p>"; 
                }
                catch(PDOException $e) {
                    $alerttext.= $e->getMessage();
                    $infotext.= $e->getMessage();
                    $infotext.=" An error occured during update!<br>"; 
                    $alerttext.="<p>An error occured during update!</p>"; 
                }                
                
                
            } // ends check for client AND favadrid
 
        } // ends ($page=='editthisfavadr')



        if (($oldfavadrft) and ($oldfavadrpc)) {
            $i=0;
            $sumtot=0;
            while ($i<22) {
                $sql = "SELECT ID FROM Orders WHERE status < '86' 
                AND enrpc".$i." = :pc AND enrft".$i." = :ft ";
                
                $prep = $dbh->prepare($sql);
                $prep->bindParam(':pc', $oldfavadrpc, PDO::PARAM_INT);
                $prep->bindParam(':ft', $oldfavadrft, PDO::PARAM_INT);            
                $prep->execute();
                $stmt = $prep->fetchAll();
                
                if ($stmt)  {
                    foreach ($stmt as $chngrow) {
                        
                        $sql = "UPDATE Orders SET 
                        enrft".$i."=(UPPER(?)), 
                        enrpc".$i."=(UPPER(?)) 
                        WHERE ID=? LIMIT 1";

                        try {
                            $dbh->prepare($sql)->execute([$favadrft, $favadrpc, $chngrow['ID']]);
                            $sumtot++;
                        }
                        catch(PDOException $e) {
                            $alerttext.= $e->getMessage();
                            $infotext.= $e->getMessage();
                            $infotext.="<br /><strong>1641 An error occured during updating postcodes!</strong>"; 
                            $alerttext.="<p>Error 1642 occured during updating ".$sql."</p>";
                        }
                    } // ends loop for jobs matching ft, pc and status
                $pagetext.='<p>'.$sumtot.' Future address changed </p>';
                } // total is more than 0 for matching addresses
            $i++;
            } // ends $i loop
        } // ends check to see if (($oldfavadrft) and ($oldfavadrpc)) {


        if (($thisfavadrid=='') and ($favadrft)) {
            $infotext.='New Favourite';
            
            $sql="INSERT INTO cojm_favadr 
            (favadrclient, 
            favadrft, 
            favadrpc, 
            favadrisactive,
            favadrcomments
            ) VALUES (
            ?,
            (UPPER(?)),
            (UPPER(?)),
            '1',
            (UPPER(?))   )
            ";
            
            try {
                $dbh->prepare($sql)->execute([$CompanyName]);
                $insertid = $dbh->lastInsertId();
                $infotext.="<br />New fav id ".$insertid.' '.$favadrft; 
                $pagetext.="<p>New favourite added ".$favadrft.".</p>";                
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $infotext.=" An error occured New Favourite!<br>".$sql;  
                $alerttext." <p>An error occured during update!</p>";
            }
        } // end new client and check companyname>0 

        if ( $page=='addaftercheckasnewfav') {
            $infotext.='New Favourite 1665'; 
        }

    } // ends page=newfav(?) or page=quickeditfrom  or page=addaftercheckasnewfav


    if ($page=='editinvcomment') { // EDITS INVOICE COMMENT
        if (isset( $_POST['ref'])) { $invoiceref=$_POST['ref']; } else { $invoiceref=''; }
        if (isset($_POST['invcomments'])) { $invcomments=$_POST['invcomments']; } else { $invcomments=''; }
        
        if ($invoiceref) {
        
            $sql = " UPDATE `invoicing` SET `invcomments` = ? WHERE ref=? ";	

            try {
                $dbh->prepare($sql)->execute([$invcomments, $invoiceref]);
                $pagetext.='<p>Updated comments for invoice ref '.$invoiceref.'</p>';
                $infotext.='<br /> Invoice '.$invoiceref.' comment edited to '.$invcomments;
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $alerttext.='<br />Unable to edit invoice comment<br />'; 
                $infotext.='<br />Unable to edit invoice comment<br />'.$sql;
            }
        } // checks for invoice ref
    } // finishes page= editinv comment




    if ($page=='markinvpaid') {
        // sets vars
        if (isset( $_POST['ref'])) { $invoiceref=$_POST['ref']; } else { $invoiceref=''; }
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
            
        // echo "<h4>Invoice method field found</h4>" . $invmethod . " " . $datepassed . " " . $invoiceref ;
        if ($invoiceref) {
            $sql="UPDATE `invoicing` SET `paydate` = ? WHERE CONCAT( `invoicing`.`ref` ) =? ";
            
            try {
                $dbh->prepare($sql)->execute([$invoicedate, $invoiceref]);
                $pagetext.='<p><strong> Reconciled invoice ref '.$invoiceref.'</strong></p>';
                $infotext.='<p><strong> Reconciled invoice ref '.$invoiceref.'</strong></p>';
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $alerttext.='<br />Unable to change invoice<br />'; 
                $infotext.='<br />Unable to change invoice<br />'.$sql;
            }


            $sql = "UPDATE Orders SET status='120' WHERE invoiceref= :invoiceref"; 

            try {
                $prep = $dbh->prepare($sql);
                $prep->bindParam(':invoiceref', $invoiceref, PDO::PARAM_INT);
                $prep->execute();
                $num_rows = $prep->rowCount();
                $pagetext.= '<p>Updated '.$num_rows.' jobs</p>';
                $infotext.= '<p> Updated '.$num_rows.' jobs</p>';
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $alerttext.= '<br /><strong>An error occured during updating individual jobs, 1530 </strong>';
                $infotext.='<br /><strong>An error occured during updating individual jobs cj 1530</strong>';
            }
        } // checks for invoice ref
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
            
            $sql="UPDATE `invoicing` SET `chasedate` = ? WHERE CONCAT( `invoicing`.`ref` ) = ? ";
            
            try {
                $dbh->prepare($sql)->execute([$duedate, $ref]);
                $pagetext.='<p>First Time Invoice Chased</p>';
                $infotext.= "<br />First time chased".$duedate;
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $alerttext.='<br />Fail 1569 <br />'; 
                $infotext.='<br />Fail<br />'.$sql;
            }
        }
        
        if ($chasetype=='2') {
            $sql="UPDATE `invoicing` SET `chasedate2` = ? WHERE CONCAT( `invoicing`.`ref` ) = ? ";
            
            try {
                $dbh->prepare($sql)->execute([$duedate, $ref]);
                $pagetext.='<p>2nd Time Invoice Chased</p>';
                $infotext.= "<br />2nd time chased".$duedate;
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $alerttext.='<br />Fail 1581 <br />'; 
                $infotext.='<br />Fail<br />'.$sql;
            }
            
        }
        
        if ($chasetype=='3') {
            $sql="UPDATE `invoicing` SET `chasedate3` = ? WHERE CONCAT( `invoicing`.`ref` ) = ? ";
            
            try {
                $dbh->prepare($sql)->execute([$duedate, $ref]);
                $pagetext.='<p>3rd Time Invoice Chased</p>';
                $infotext.= "<br />3rd time chased".$duedate;
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $alerttext.='<br />Fail 1598 <br />'; 
                $infotext.='<br />Fail<br />'.$sql;
            }
            
        }
        
        
    } // ends page = edit chase invoice



    if ($page=='deleteinv') {
        if (isset( $_POST['ref'])) { $invoiceref=$_POST['ref']; } else { $invoiceref=''; }
        if ($invoiceref) {
            $sql = "DELETE from invoicing WHERE ref= :invoiceref ";	
            try {
                $prep = $dbh->prepare($sql);
                $prep->bindParam(':invoiceref', $invoiceref, PDO::PARAM_INT);
                $prep->execute();

                $pagetext.=' Invoice ref '.$invoiceref.' deleted';
                $infotext.=' Invoice ref '.$invoiceref.' deleted';
                
                $sql = "SELECT ID FROM Orders WHERE (`Orders`.`invoiceref` = :invoiceref )  ";

                $prep = $dbh->prepare($sql);
                $prep->bindParam(':invoiceref', $invoiceref, PDO::PARAM_INT);
                $prep->execute();
                $stmt = $prep->fetchAll();

                foreach ($stmt as $dtrow) {
                    $dtid=$dtrow['ID'];
                    try {
                        $sql = "UPDATE Orders SET status='100', invoiceref='' WHERE ID = :id ";
                        $prep = $dbh->prepare($sql);
                        $prep->bindParam(':id', $dtid, PDO::PARAM_INT);
                        $prep->execute();
                        
                        $browser=$_SERVER["HTTP_USER_AGENT"];
                        $sql="INSERT INTO cojm_audit (auditid,audituser,auditorderid,auditpage,auditfilename,auditmobdevice,
                        auditbrowser,audittext,auditcjtime,auditpagetime,auditmidtime,auditinfotext)   
                        VALUES ('',?,?,?,'view_all_invoices.php',?,
                        ?,'Invoice ref $invoiceref removed','','','','Invoice removed from job')";                
                
                
                        try {
                            $dbh->prepare($sql)->execute([$cyclistid, $dtid,$page,$mobdevice,$browser]);
                        }
                        catch(PDOException $e) {
                            // $alerttext.= $e->getMessage();
                            $infotext.= $e->getMessage();
                            $alerttext.= '<div class="moreinfotext"><h1> Problem saving audit log </h1></div>';
                            $infotext.= '<br />Problem saving audit log on remove invoice details';
                        }
                    }

                    catch(PDOException $e) {
                        // $alerttext.= $e->getMessage();
                        $infotext.= $e->getMessage();
                        $alerttext.= '<br /><strong>An error occured during updating individual job, check audit log. </strong>'; 
                        $infotext.='<br /><strong>An error occured during updating individual job '.$dtid.' '.$sql.' </strong>';
                    } 
            
                } // ends row extract for individual job
            }
            catch(PDOException $e) {
                $alerttext.= $e->getMessage();
                $infotext.= $e->getMessage();
                $alerttext.= '<br /><strong>An error occured during updating deleting invoice, 1629 </strong>';
                $infotext.='<br /><strong>An error occured during deleting invoice cj 1629 </strong>';
            }        
        } // checks for invoice ref
    } // ends page=deleteinv




    if ($page=='invnotpaid') {

        if (isset( $_POST['ref'])) {
            $invoiceref=$_POST['ref'];
        } else {
            $invoiceref='';
        }
        
        if ($invoiceref) {
            $sql="UPDATE `invoicing` SET `paydate` = '', `cash` = '', `cheque` = '', `bacs` = '', `paypal` = '' WHERE CONCAT( `invoicing`.`ref` ) =? ";
            try {
                $dbh->prepare($sql)->execute([$invoiceref]);
                $infotext.= "<br />Removed Reconciliation  details<br>";
                $pagetext.='<p>Reconciliation removed on Invoice '.$invoiceref.'</p>';
            
            
                $updatequery = "UPDATE Orders SET status ='110' WHERE invoiceref = :ref";
                
                $stmt = $dbh->prepare($updatequery);
                $stmt->bindParam(':ref', $invoiceref, PDO::PARAM_INT);
                $stmt->execute();
                $total = $stmt->rowCount();
                $infotext.=$total.' jobs updated to status 110 ';
                $pagetext.=$total.' jobs updated to awaiting reconciliation ';

            }
            catch(PDOException $e) {
                $infotext.= $e->getMessage();
                $infotext.= "<br />Unable to Remove invoice Reconciliation details<br>";
                $alerttext.='<p>Reconciliation NOT removed on Invoice ref '.$invoiceref.'</p>';
            }
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
        

                $sql="DELETE FROM instamapper WHERE device_key=? AND timestamp > ? AND timestamp < ? ";
                
                $stmt = $dbh->prepare($sql);
                $stmt->execute([$device_key,$startdate,$finishdate]);
                $total = $stmt->rowCount();
                    
                if ($total>0) {
                    $alerttext.="". $total.' tracking positions deleted.<br> 
                    There may still be cached GPS for individual jobs on that day.';
                    $infotext.=$total.' tracking positions deleted.<br>';
        
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


    
    if ($page == "confirmdelete" ) {
        
        if ($mobdevice) {
            
            $sql =  " 
            UPDATE Orders 
            SET status='86', 
            privatejobcomments = concat('** DELETED FROM MOBILE ** ',privatejobcomments),
            ShipDate = now() ,
            collectiondate = now() 
            WHERE ID=? ";
            
            
            $dbh->prepare($sql)->execute([$id]);
            
            
            $alerttext.="<p><strong>Job ref ".$id." moved to admin as from mobile device.</strong></p>";
            $infotext.='<br />Delete job from mobile aka requeuing to admin ';                        
        
        }
        else {
        
            $sql = "DELETE from Orders WHERE ID=?";
            $dbh->prepare($sql)->execute([$id]);
            
            $alerttext.="<p><strong>Delete option confirmed, job ref ".$id." deleted. </strong></p>";	
            $infotext.="<br /><strong>Delete option confirmed,<br> ID ".$id." Deleted. mobdevice is ".$mobdevice."</strong>";	
        }
        
        
        
        
    } // END OF DELETION CONFIRM

    
    
    
    
    
    
} else {// finishes epoch time


}





if ($page=='addtodb') { // save invoice to db



    $existinginvref='';

    $sql = "SELECT ref FROM invoicing WHERE (`invoicing`.`ref` =? )  ";
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$newinvoiceref]);
    $stmt = $prep->fetchAll();
    
    if ($stmt) {
        $pagetext.= '<h3>Not changing Invoice as existing invoice with same ref.';
    }
    else {
        
        $query = "SELECT CompanyName, invoiceEmailAddress, EmailAddress
        FROM  Clients WHERE Clients.CustomerID = ? LIMIT 0,1";
        $statement = $dbh->prepare($query);
        $statement->execute([$clientid]);
        $clrow = $statement->fetch(PDO::FETCH_ASSOC);
    
        $clientname = $clrow['CompanyName'];
        $clientemailinv = $clrow['invoiceEmailAddress'];
        $clientemail = $clrow['EmailAddress'];
        
        
        if ($clientemailinv) {
            $pagetext.= '<a href="../live/new_cojm_client.php?clientid='.$clientid.'">'.$clientname.'</a> Invoice Email : '.$clientemailinv;
        }
    
    
    
        if ($clientemail) {
            $pagetext.= '<br /><a href="../live/new_cojm_client.php?clientid='.$clientid.'">'.$clientname.'</a> General Email : '.$clientemail;
        }
    
    
        if ($invoiceselectdep) {
            $query = "SELECT depname, depemail
            FROM  clientdep WHERE depnumber = ? LIMIT 0,1";
            $statement = $dbh->prepare($query);
            $statement->execute([$invoiceselectdep]);
            $deprow = $statement->fetch(PDO::FETCH_ASSOC);
            
            $depname = $deprow['depname'];
            $depemail = $deprow['depemail'];
    
            if ($clientemail) {
                $pagetext.= '<br /><a href="../live/new_cojm_department.php?depid='.$invoiceselectdep.'">'.$depname.'</a> Department Email : '.$depemail;
            }
        }



        

        try {
            $sql = "INSERT INTO invoicing ( ref, invdate1, created, client, cost, invvatcost,invdue, invoicedept, invcomments, invoicetopmiddlehtml, showdelivery ) 
            VALUES ( ?, ? , now() , ? , ?, ? , ? , ?, ?, ?, ? ) ";
         
            $dbh->prepare($sql)->execute([$newinvoiceref,$invoicesqldate,$clientid,$tablecost,$tablevatcost,$invoiceduesqldate,$invoiceselectdep,$invcomments,$topmiddlehtml,$showdelivery]);
        
            $infotext.= "<br />New invoice ref '.$invoiceref.'<br>";
            $pagetext.= ' New Invoice Ref <a href="view_all_invoices.php?viewtype=individualinvoice&ref='.$newinvoiceref.'">'.$newinvoiceref.'</a>';
            
            
            $audittext='<strong>Added to Invoice Ref '.$newinvoiceref.'</strong>';
                
                
            foreach ($invoicejobarray as $value) {
                
                
                  try {
                    $sql = "UPDATE Orders SET status ='110', invoiceref =? WHERE ID=? LIMIT 1";
                      
                    $dbh->prepare($sql)->execute([$newinvoiceref,$value]);
                    
                    $orderupdate++;
                    $statement = $dbh->prepare("INSERT INTO cojm_audit 
                    (auditorderid, audituser, auditpage, audittext, auditinfotext, auditdatetime, auditfilename)
                    values 
                    (:orderid, :audituser, :page, :audittext, :auditinfotext, now(), :auditfilename) ");
    
                    $statement->bindParam(':orderid', $value, PDO::PARAM_STR);
                    $statement->bindParam(':audituser', $cyclistid, PDO::PARAM_STR);
                    $statement->bindParam(':page', $page, PDO::PARAM_STR);
                    $statement->bindParam(':audittext', $audittext, PDO::PARAM_STR);
                    $statement->bindParam(':auditinfotext', $infotext, PDO::PARAM_STR);
                    $statement->bindParam(':auditfilename', $filename, PDO::PARAM_STR);
                    $statement->execute();
                }
                
                
                catch(PDOException $e) {
                    $allok=0;
                    $message.=" Issue saving Invoice on Individual job update<br /> ";
                    $message.=$e->getMessage();
                    $infotext.=" Issue saving Invoice on Individual job update<br /> ";
                    $infotext.=$e->getMessage();
                }
                
            } // ends individ job row extraction
            
            if ($orderupdate<'1') {
                $pagetext.= '<h1>No  jobs changed.</h1>';
            }
            
            if ($orderupdate)  {
                $pagetext.=' Updated status to invoiced in '.$orderupdate.' job';
                if ($orderupdate<>'1') {
                    $pagetext.= 's';
                }
            }


        }
        
        catch(PDOException $e) {
            $infotext.= $e->getMessage();
            $infotext.= "<br />Unable to Create invoice<br>";
            $alerttext.='<p><strong>Unable to create Invoice Ref '.$newinvoiceref.'</strong></p>';
        }
        
        
        $sql="update Clients SET lastinvoicedate = GREATEST(lastinvoicedate, ?) where CustomerID = ?  ";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$collectionsuntildate,$clientid]);
        $total = $stmt->rowCount();
        $infotext.='<br /> client invoice until date '.$collectionsuntildate.' changed : '.$total;
        
    } // ends check for existing invoice ref

} // ends page='addtodb' ( new invoice )



if ($page == "createnewfromexisting" ) {
        if ($oldid) {



        if (isset($_POST['dateshift'])) { $dateshift=$_POST['dateshift']; }

        if (isset($_POST['currorsched'])) { $currorsched=trim($_POST['currorsched']); } else { $currorsched=''; }


        // $pagetext.='<p>New job created from ref <a href="order.php?id='.$oldid.'">'.$oldid.'</a></p>';
        $infotext.='<br />New job created from ref <a href="order.php?id='.$oldid.'">'.$oldid.'</a>';

        
        $query="SELECT * FROM Orders where ID = ? LIMIT 1";
        $statement = $dbh->prepare($query);
        $statement->execute([$oldid]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        
        $status=$row['status']; 
        $serviceid=$row['ServiceID']; 
        $cost=$row['FreightCharge']; 
        $vatcharge=$row['vatcharge']; 
        $timerequested=$row['jobrequestedtime']; 
        $targetcollectiondate = $row['targetcollectiondate'];
        
        // echo "target collection date from row : "; echo $targetcollectiondate;
        // $targetcollectiondate=date("Y-m-d H:i:s");
        $temp_ar=explode("-",$targetcollectiondate);
        $spltime_ar=explode(" ",$temp_ar[2]); 
        $temptime_ar=explode(":",$spltime_ar[1]); 
        if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) {
            $temptime_ar[0] = 0; 
            $temptime_ar[1] = 0;
            $temptime_ar[2] = 0;
        }
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
        
        if ($row['ShipDate']>20) {
            $deliverydate=$row['ShipDate'];
        $temp_ar=explode("-",$deliverydate); $spltime_ar=explode(" ",$temp_ar[2]); $temptime_ar=explode(":",$spltime_ar[1]); 
        if (($temptime_ar[0] == '') || ($temptime_ar[1] == '') || ($temptime_ar[2] == '')) {
            $temptime_ar[0] = 0; $temptime_ar[1] = 0; 
            $temptime_ar[2] = 0; 
            }
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
        

        
        $numberitems=$row['numberitems'];
        $customerid=$row['CustomerID'];
        $enrpc21=$row['enrpc21'];
        $requestor=$row['requestor'];
        $orderdep=$row['orderdep'];
        $enrft0=$row['enrft0'];
        $enrft21=$row['enrft21'];
        $enrpc0=$row['enrpc0'];
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
        $handoverpostcode=$row['handoverpostcode'];
        $handoverCyclistID=$row['handoverCyclistID'];        
        
        if ($row['iscustomprice']=='1') {
            $pagetext.='<p> New job is pricelocked</p>';
        }
        

        

        if ($status <49  ){
        
            $nextactiondate = $targetcollectiondate; 
            $starttrackpause='';
            $finishtrackpause='';
        
        } 
        else {
            $nextactiondate = $duedate;
        }

        
        if ($status < "59" ) {
            $collectiondate="";
            $waitingtime="";
        }
        
        if ($status < "70" ){
            $deliverydate="";
        }


        if ($status>'86') {
            $status='86';
        }
 


        // $infotext.='<br />2469 Client Discount : '.$clientdiscount;
        // $infotext.=' <br>Currorsched='.$currorsched;
        
        if ($currorsched=='unsched') {
            $status='30';
            $collectiondate='';
            $deliverydate='';
            $starttrackpause='';
            $finishtrackpause='';
            $nextactiondate=$targetcollectiondate;
        }
        
        
        // actually create new from existing
        
        $cyclist=$row['CyclistID'];
        $autostartchain=$row['autostartchain'];
        
        if ($id) {

            $sql="INSERT INTO Orders 
            (ID,
            ts,
            CustomerID,
            OrderDate, 
            enrpc0, 
            ServiceID,
            enrpc21,
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
            enrft0,
            enrft21,
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
            ?,
            now(), 
            ?, 
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            now(),
            (UPPER(?)),
            (UPPER(?)),
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            (UPPER(?)),
            ?,
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            (UPPER(?)),
            ?,
            ?,
            ?,
            (UPPER(?)),
            ?,
            ?,
            ?,
            ?
            ) "; 
            
            
            try {
                $stmt = $dbh->prepare($sql);
                $stmt->execute([
                $customerid,
                $enrpc0,
                $serviceid,
                $enrpc21,
                $numberitems,
                $duedate,
                $deliverydate,
                $status,
                $targetcollectiondate,
                $jobcomments,
                $privjobcomments,
                $nextactiondate,
                $collectiondate,
                $cost,
                $vatcharge,
                $cyclist,
                $deliveryworkingwindow,
                $collectionworkingwindow,
                $starttrackpause,
                $finishtrackpause,
                $waitingtime,
                $requestor,
                $orderdep,
                $enrft0,
                $enrft21,
                $clientjobreference,
                $distance,
                $clientdiscount,
                $waitingmins,
                $cbb1,
                $cbb2,
                $cbb3,
                $cbb4,
                $cbb5,
                $cbb6,
                $cbb7,
                $cbb8,
                $cbb9,
                $cbb10,
                $cbb11,
                $cbb12,
                $cbb13,
                $cbb14,
                $cbb15,
                $cbb16,
                $cbb17,
                $cbb18,
                $cbb19,
                $cbb20,
                $cbbc1,
                $cbbc2,
                $cbbc3,
                $cbbc4,
                $cbbc5,
                $cbbc6,
                $cbbc7,
                $cbbc8,
                $cbbc9,
                $cbbc10,
                $cbbc11,
                $cbbc12,
                $cbbc13,
                $cbbc14,
                $cbbc15,
                $cbbc16,
                $cbbc17,
                $cbbc18,
                $cbbc19,
                $cbbc20,
                $enrpc1,
                $enrpc2,
                $enrpc3,
                $enrpc4,
                $enrpc5,
                $enrpc6,
                $enrpc7,
                $enrpc8,
                $enrpc9,
                $enrpc10,
                $enrpc11,
                $enrpc12,
                $enrpc13,
                $enrpc14,
                $enrpc15,
                $enrpc16,
                $enrpc17,
                $enrpc18,
                $enrpc19,
                $enrpc20,
                $enrft1,
                $enrft2,
                $enrft3,
                $enrft4,
                $enrft5,
                $enrft6,
                $enrft7,
                $enrft8,
                $enrft9,
                $enrft10,
                $enrft11,
                $enrft12,
                $enrft13,
                $enrft14,
                $enrft15,
                $enrft16,
                $enrft17,
                $enrft18,
                $enrft19,
                $enrft20,
                $opsmaparea,
                $opsmapsubarea,
                $iscustomprice,
                $handoverpostcode,
                $handoverCyclistID,
                $autostartchain,
                $co2saving,
                $pm10saving
                ]);
                $newjobid = $dbh->lastInsertId();
                $pagetext.="<p>Created ". $newjobid.' from '. $id .'</p>';
                $infotext.="<br />Created ". $newjobid.' from '. $id;
                $id=$newjobid;
                $ID=$newjobid;
                
                $newsecurity_code=addTrackref($id);
                $sql = "UPDATE Orders SET publictrackingref=? WHERE ID=? LIMIT 1";
                $dbh->prepare($sql)->execute([$newsecurity_code,$id]);

                
                
                $cojmaction='recalcprice';
                include 'ajaxchangejob.php';
            }
            
            catch(PDOException $e) {
                $infotext.= $e->getMessage();
                $infotext.= "<br />Unable to Create New Job from Existing<br>";
                $alerttext.='<p><strong>Unable to create New Job from Existing</strong></p>';
            }
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
    
    $sql="update Clients SET isactiveclient = GREATEST(isactiveclient, '1') where CustomerID = ?  ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$CustomerID]);
    $total = $stmt->rowCount();
    if ($total) {
        $infotext.="<br />3144 CLIENT Updated as active";
        $pagetext.="<p>Client made active </p>";
    }

    if (isset($_POST['collecttext'])) { $enrft0=trim($_POST['collecttext']); } else { $enrft0=''; }
    if (isset($_POST['delivertext'])) { $enrft21=trim($_POST['delivertext']); } else { $enrft21=''; }
    
    
    $requestedby=htmlspecialchars(trim($_POST['requestedby']));
    
    if (isset($_POST['newjobselectdep'])) { $newjobdepid=trim($_POST['newjobselectdep']); } else { $newjobdepid=''; }
    if (isset($_POST['deliverpc'])) { $deliverpc=trim($_POST['deliverpc']); } else { $deliverpc=''; }
    if (isset($_POST['enrpc0'])) { $enrpc0=trim($_POST['enrpc0']); } else { $enrpc0=''; }
    
    if (isset($_POST['frombox'])) { $frombox=trim($_POST['frombox']); } else { $frombox=''; }
    if (isset($_POST['tobox'])) { $tobox=trim($_POST['tobox']); } else { $tobox=''; }
    
    $infotext.='<p>from '.$frombox.' to '.$tobox.'</p>';
    
    if ($frombox) {
        $sql="SELECT favadrpc, favadrft from cojm_favadr WHERE favadrid=? AND favadrisactive='1' LIMIT 0,1";
        $statement = $dbh->prepare($sql);
        $statement->execute([$frombox]);
        $adrow = $statement->fetch(PDO::FETCH_ASSOC);
        $enrpc0 = $adrow['favadrpc'];
        $enrft0= $adrow['favadrft'];
    }
    
    
    if ($tobox) {
        $sql="SELECT favadrpc, favadrft from cojm_favadr WHERE favadrid=? AND favadrisactive='1' LIMIT 0,1";
        $statement = $dbh->prepare($sql);
        $statement->execute([$tobox]);
        $adrow = $statement->fetch(PDO::FETCH_ASSOC);
        $deliverpc = $adrow['favadrpc'];
        $enrft21= $adrow['favadrft'];
    }
    
    

    if ( $globalprefrow["inaccuratepostcode"]<>'1') { // accurate postcodes
    
        $deliverpc = strtoupper(str_replace(' ','',$deliverpc));
        $enrpc0 = strtoupper(str_replace(' ','',$enrpc0));
    
        $start=substr($deliverpc, 0, -3);  
        $deliverpc=$start.' '.substr($deliverpc, -3); // 'ies'  
        $start=substr($enrpc0, 0, -3);  
        $enrpc0=$start.' '.substr($enrpc0, -3); // 'ies' 
    }
    
    $jobcomments=trim($_POST['jobcomments']);
    $jobcomments = str_replace("'", "&#39;", "$jobcomments");
    
    $jobcomments = trim(htmlspecialchars($jobcomments));
    
    $serviceid=trim($_POST['serviceID']);
    

    $nextactiondate = $targetcollectiondate;

    
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

    $sql="INSERT INTO Orders 
    (
    CustomerID,
    requestor ,
    ServiceID ,
    CyclistID,
    enrpc0, 
    enrpc21,
    enrft0,
    enrft21,
    numberitems, 
    status,
    jobrequestedtime,
    jobcomments,
    orderdep,
    targetcollectiondate,
    collectionworkingwindow ,
    duedate ,
    deliveryworkingwindow ,
    nextactiondate,
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
    ?,
    (UPPER(?)) ,
    ? ,
    '1',
    (UPPER(?)), 
    (UPPER(?)),
    (UPPER(?)),
    (UPPER(?)),
    '1',
    '30',
    now() ,
    (UPPER(?)),
    ?,
    ?,
    ?,
    ? ,
    ? ,
    ? ,
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? , 
    ? 
    )
    ";
    
    try {
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
        $CustomerID,
        $requestedby,
        $serviceid,
        $enrpc0,
        $deliverpc,
        $enrft0,
        $enrft21,
        $jobcomments,
        $newjobdepid,
        $targetcollectiondate,
        $collectionworkingwindow,
        $duedate,
        $deliveryworkingwindow,
        $nextactiondate,
        $chkcbb4,
        $chkcbb5,
        $chkcbb6,
        $chkcbb7,
        $chkcbb8,
        $chkcbb9,
        $chkcbb10,
        $chkcbb11,
        $chkcbb12,
        $chkcbb13,
        $chkcbb14,
        $chkcbb15,
        $chkcbb16,
        $chkcbb17,
        $chkcbb18,
        $chkcbb19,
        $chkcbb20
        ]);
        $total = $stmt->rowCount();

        $id = $dbh->lastInsertId();

    
        $ID=$id;
        $origid=$id;
        $emailclientconfirmnewjob="";
        
        $pagetext.="<p>Created Job Ref ". $id.'</p>';
        $infotext.="<br />Created job has ID : " . $id;
        // $idref=string()$id);
        
        $newsecurity_code=addTrackref($id);
        $sql = "UPDATE Orders SET publictrackingref=? WHERE ID=? LIMIT 1";
        $dbh->prepare($sql)->execute([$newsecurity_code,$id]);
        
        
    
        $calcmileage=1;
        $cojmaction='recalcprice';
        include 'ajaxchangejob.php';
    
    }
    
    catch(PDOException $e) {
        $infotext.= $e->getMessage()."<br />Unable to Create New Job <br>";
        $alerttext.=$e->getMessage().'<p><strong>Unable to create New Job </strong></p>';
    }    
    
}  // ends  new job from ajax




function addTrackref($id) {
        
        $infotext.=' in function with id '.$id;
        
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
        $infotext.="Generated tracking reference ".$newsecurity_code;
        
        
        
        return $newsecurity_code;
        
        
        
    };
    
    






/////////////////////////    FUNCTIONS    //////////////////////////////


/////////////////      FUNCTION FOR FORMATTING MONEY VALUES ////////////////////////////////////////
function formatMoney($money) {
    if (floor($money) == $money) {
        $money=number_format(($money), 0, '.', ',');
    }
    else if (round($money, 1)==$money){
        $money=number_format(($money), 1, '.', ',');
    }
    else {
        $money=number_format(($money), 2, '.', ',');
    }
    return $money;
}



/////////////////      STARTS PLURAL FUNCTION                    ///////////////////////////
// eg echo plural($diff);
function plural($num) {
	if ($num != 1) {
		return "s";
    }
}
/////////////////      ENDS PLURAL FUNCTION                      ///////////////////////////





if (($page<>'newjobfromajax')) {

// $ID=$origid;
$id=$origid;

}


if ($page=='createnewfromexisting') { $id=$newjobid; }



$caudtext='<hr><b>'. $cyclistid.' : '.$today.'</b>'. $infotext; 



// $infotext.='<br />ID found : '.$id;
 
$orderauditid=$id;
 


// A SCRIPT TIMER
$now_time = microtime(TRUE);
$cj_lapse_time = $now_time - $cj_time;
$cj_msec = $cj_lapse_time * 1000.0;
$cj_echo = number_format($cj_msec, 1);


function time2str($ts) { //Relative Date Function  // used in order.php and ajaxordermap
	if(!ctype_digit($ts)) {
           $ts = strtotime($ts); 
       }		
	$tempdaydiff=date('z', $ts)-date('z');
	
	$diff = time() - $ts;
	if($diff == 0){	
           return 'now'; 
       }
	elseif($diff > 0)
	{
		$day_diff = floor($diff / 86400);
		if($day_diff == 0)
		{
			if($diff < 60) return ' Just now. ';
			if($diff < 120) return ' 1 min ago. ';
			if($diff < 3600) return ' '.floor($diff / 60) . ' min ago. ';
			if($diff < 7200) return ' 1 hr, ' . floor(($diff-3600) / 60) . ' min ago. ';
			if($diff < 86400) return floor($diff / 3600) . ' hours ago';
		}
		if($tempdaydiff=='-1') { return 'Yesterday '. date('A', $ts).'. '; }
		if($day_diff < 7) return ' Last '. date('D A', $ts).'. ';
           if($day_diff < 31) return date('D', $ts).' '. ceil($day_diff / 7) . ' weeks ago. ';
		if($day_diff < 60) return 'Last month';
		return date('D M Y', $ts);
	}
	else
	{
		$diff = abs($diff);
		$day_diff = floor($diff / 86400);
		if($day_diff == 0)
		{
			if($diff < 120) return 'In a minute';
			if($diff < 3600) return 'In ' . floor($diff / 60) . ' mins. ';
			if($diff < 7200) { return ' 1hr, ' . floor(($diff-3600) / 60) . ' mins. '; }
		//	if(($diff < 86400) and ($tempday<>date('z', $ts))) {  return ' Tomorrow ';    }
			
			if($diff < 86400) return ' ' . floor($diff / 3600) . ' hrs. ';
		}
		if($tempdaydiff == 1) return ' Tomorrow '. date('A', $ts).'. ';
		if($day_diff < 4) return date(' D A', $ts);
		if($day_diff < 7 + (7 - date('w'))) return date('D ', $ts).'next week. ';
		if(ceil($day_diff / 7) < 4) return date('D ', $ts).' in ' . ceil($day_diff / 7) . ' weeks. ';
		if(date('n', $ts) == date('n') + 1) return date('D', $ts).' next month. ';
		return date('D M Y', $ts);
	}
}


?>