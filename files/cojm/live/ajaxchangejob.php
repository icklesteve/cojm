<?php 
/*
    COJM Courier Online Operations Management
	ajaxchangejob.php - Handles Ajax Requests made from various pages ( the controller in MVC language :-), also see changejob.php
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


include "C4uconnect.php";

if (isset($_POST['page'])) { $page=trim($_POST['page']); } else { exit();  }
if (isset($_POST['id'])) { $id = trim($_POST['id']); }
if (isset($_POST['formbirthday'])) { $formbirthday = trim($_POST['formbirthday']); }
if (isset($_POST['publicid'])) { $publicid = trim($_POST['publicid']); }

$newformbirthday=$formbirthday; // re-outputs original in case of error


$cojmaction='';
$calcmileage=0;
$infotext=' ajaxchangejob.php '.$page;
$allok=0;
$nextactiondatecheck='';
$script=" var message=''; ";

$message='';
$message;
$infotext;

// echo ' page : '.$page;
// echo ' id : '.$id;
// echo ' publicid : '. $publicid;
// if ($_POST["label"]) { $label = $_POST["label"]; }
// echo $_FILES["file"]["type"];

// $infotext.='<br /> requestime : '.$_SERVER["REQUEST_TIME_FLOAT"];



$query = "SELECT publictrackingref, ts, tsmicro, status FROM Orders WHERE id = :getid LIMIT 0,1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
$stmt->execute();
$hasid = $stmt->rowCount();

$infotext.='<br />ID Found : '.$hasid;

if ($hasid) {

    $obj = $stmt->fetchObject();
    $ts=$obj->ts;
    $ms=$obj->tsmicro;
    $currentstatus=$obj->status;
    $uts=date('U', strtotime($ts));
    
    // $infotext.='<br />ts Found : '.$ts;
    $infotext.='<br />uts Found : '.$uts;
    $infotext.='<br />ts micro : '.$ms;
    if ($uts>$ms) {
        $lastmod=$uts;
    }
    else {
        $lastmod=$ms;
    }

    $infotext.='<br /> last mod : '.$lastmod;
    $infotext.='<br /> submit : '.$formbirthday;
    $infotext.='<br /> status : '.$currentstatus;


    // $infotext.='<br /> serverstart and new birthday : '.$_SERVER["REQUEST_TIME_FLOAT"]; // USE THIS TIME FOR JOB CHANGED TIME

    $timesincechanged=($formbirthday-$lastmod);


    //////    CHANGE TO ZERO WHEN FULLY MOVED TO NEW TIMESTAMP, OLD ONE GETS ROUNDED UP  /////////////////////////////////
    // if ($timesincechanged<-2) { 


    // alert if timezone not set

    if (date_default_timezone_get()=='UTC') { $message.='** Server Timezone not set to local time<br />';}


    // tempfix with no timezone set
    if ($timesincechanged==999) {
        $message.='Another user / page has modified this job<br/>Please refresh page.';
        $infotext.='<br /> submit was '.$timesincechanged.' s before job last changed, NOT PROCEEDING';
    }
    else {
        $infotext.='<br /> submit was '.$timesincechanged.' s after job last changed, ok to proceed';
        
        if (($currentstatus>99) and ($page<>'ajaxorderstatus')) {
            $message.=' ACJ Unable to edit job with current status, please change back to Admin.';
        }
        else {




            //////////////////////            ADD CHECK FOR STATUS < COMPLETE         //////////////////////////////////////




            
            // change rider
            // status
            if ($page=='ajaxorderstatus') {
                $newdate=date("d/m/Y H:i");	
                $newstatus=trim($_POST['newstatus']);
                $nextactiondatecheck='1';
                
                // $message.=' Changing order status via ajax ';
                
                $query="SELECT statusname FROM status WHERE status='$newstatus' LIMIT 0,1";
                
                // $oldstatustext = mysql_result(mysql_query("SELECT statusname FROM status WHERE status='$oldstatus' LIMIT 0,1", $conn_id), 0);
                
                
                
                $sql='SELECT statusname FROM status WHERE status=?';
                $sth=$dbh->prepare($sql);
                $data=array($newstatus);
                $sth->execute($data);
                $newstatustext=$sth->fetchColumn();
                // $message.=$result;
                
                try {                    
                    $query = "UPDATE Orders SET status=:newstatus WHERE id=:getid";
                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                    $stmt->bindParam(':newstatus', $newstatus, PDO::PARAM_INT);
                    $stmt->execute();
                    $total = $stmt->rowCount();
                    if ($total=='1') {
                        $message.=' Status updated to '. $newstatustext.'<br />';
                        $allok=1;
                        // $cojmaction='recalcprice';
                        $script.=' initialstatus='.$newstatus.'; ';
                    
                        if ($newstatus =='40') {
                            $infotext.=' Adding Travel to Collection Time  ';
                            try {
                                $query = "UPDATE Orders SET starttravelcollectiontime=now() WHERE id=:getid LIMIT 1";
                                $stmt = $dbh->prepare($query);
                                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                                $stmt->execute();
                                $total = $stmt->rowCount();
                                if ($total=='1') {
                                    $message.=' Travel to Collection Time Added ';
                                    $allok=1;
                                    $script.=' initialstarttravelcollectiontime="'.$newdate.'"; ';
                                    $script.= ' $("#starttravelcollectiontime").val("'.$newdate.'");  ';
                                } // ends total changed ==1 check
                            } // ends try
                            catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                        } // ends status = 40
                
                
                        if ($newstatus =='50') {
                            $infotext.=' Adding Waiting Start Time  ';
                            try {
                                $query = "UPDATE Orders SET waitingstarttime=now() WHERE id=:getid LIMIT 1";
                                $stmt = $dbh->prepare($query);
                                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                                $stmt->execute();
                                $total = $stmt->rowCount();
                                if ($total=='1') {
                                    $message.=' Waiting Start Time Added ';
                                    $allok=1;
                                    $script.=' initialwaitingstarttime="'.$newdate.'";   $("#waitingstarttime").val("'.$newdate.'");  ';
                                }
                            } // ends try
                            catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                        }
                    
                    
                        if (($currentstatus <'60' ) and ($newstatus >'59')) {
                            $infotext.=' Adding Collection Time  ';
                        
                            try {
                                $query = "UPDATE Orders SET collectiondate=now() WHERE id=:getid LIMIT 1";
                                $stmt = $dbh->prepare($query);
                                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                                $stmt->execute();
                                $total = $stmt->rowCount();
                                if ($total=='1') {
                                    $message.=' Collection Time Added ';
                                    $allok=1;
                                    $script.=' initialcollectiondate="'.$newdate.'";   $("#collectiondate").val("'.$newdate.'");  ';
                                } // ends total changed ==1 check
                            } // ends try
                            catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                        }
                        
                    
                    
                        if ($newstatus =='60') {
                            $infotext.=' Adding Paused Time  ';
                            try {
                                $query = "UPDATE Orders SET starttrackpause=now() WHERE id=:getid LIMIT 1";
                                $stmt = $dbh->prepare($query);
                                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                                $stmt->execute();
                                $total = $stmt->rowCount();
                                if ($total=='1') {
                                    $message.=' Pause Time Added ';
                                    $allok=1;
                                    $script.='  $("#toggleresume").show(); initialstarttrackpause="'.$newdate.'";   $("#starttrackpause").val("'.$newdate.'");  ';
                                }
                            }
                            catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                        }
                    
                    
                        if (($currentstatus =='60') and ($newstatus >'60')) {
                            $infotext.=' Adding Resume Time ';
                            try {
                                $query = "UPDATE Orders SET finishtrackpause=now() WHERE id=:getid LIMIT 1";
                                $stmt = $dbh->prepare($query);
                                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                                $stmt->execute();
                                $total = $stmt->rowCount();
                                if ($total=='1') {
                                    $message.=' Resume Time Added ';
                                    $allok=1;
                                    $script.=' initialfinishtrackpause="'.$newdate.'";   $("#finishtrackpause").val("'.$newdate.'");  ';
                                }
                            } 
                            catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                        }
                    
                    
                    
                    if (($newstatus>85) and ($currentstatus<85)) {
                    $infotext.=' Adding Delivery time  ';
                    try {
                    $query = "UPDATE Orders SET ShipDate=now() WHERE id=:getid LIMIT 1";
                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                    $stmt->execute();
                    $total = $stmt->rowCount();
                    if ($total=='1') {
                    $message.=' Delivery Time Added ';
                    $allok=1;
                    $script.=' initialShipDate="'.$newdate.'";   $("#ShipDate").val("'.$newdate.'");  ';
                    
                    } // ends total changed ==1 check
                    } // ends try
                    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                    }
                    
                    
                    
                    
                
                if ($newstatus<$currentstatus) {
                
                $infotext.=' Reduction in Status ';
                
                if ($newstatus =='30') {
                
                try {
                
                $sql = "UPDATE Orders SET 
                starttravelcollectiontime='0000-00-00 00:00:00', 
                waitingstarttime ='0000-00-00 00:00:00',
                collectiondate='0000-00-00 00:00:00',
                starttrackpause = '0000-00-00 00:00:00',
                finishtrackpause ='0000-00-00 00:00:00',
                ShipDate ='0000-00-00 00:00:00'
                WHERE ID=:getid LIMIT 1"; 
                
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                $stmt->execute();
                $total = $stmt->rowCount();
                if ($total=='1') {
                // $script.=' ';
                $infotext.=' Times Updated ';
                $allok=1;
                
                // alert(" about to change ");
                
                $script.=' 
                initialstarttravelcollectiontime=""; $("#starttravelcollectiontime").val("");
                initialwaitingstarttime=""; $("#waitingstarttime").val("");
                initialcollectiondate=""; $("#collectiondate").val("");
                initialstarttrackpause=""; $("#starttrackpause").val("");
                initialfinishtrackpause=""; $("#finishtrackpause").val("");
                initialShipDate="";   $("#ShipDate").val(""); 
                ';
                
                
                } // ends total changed ==1 check
                } // ends try
                catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                
                
                
                
                
                }
                
                if ($newstatus =='40') {
                
                try {
                
                $sql = "UPDATE Orders SET 
                waitingstarttime ='0000-00-00 00:00:00',
                collectiondate='0000-00-00 00:00:00',
                starttrackpause = '0000-00-00 00:00:00',
                finishtrackpause ='0000-00-00 00:00:00',
                ShipDate ='0000-00-00 00:00:00'
                WHERE ID=:getid LIMIT 1"; 
                
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                $stmt->execute();
                $total = $stmt->rowCount();
                if ($total=='1') {
                // $script.=' ';
                $infotext.=' Times Updated ';
                $allok=1;
                
                $script.=' 
                initialwaitingstarttime=""; $("#waitingstarttime").val("");
                initialcollectiondate=""; $("#collectiondate").val("");
                initialstarttrackpause=""; $("#starttrackpause").val("");
                initialfinishtrackpause=""; $("#finishtrackpause").val("");
                initialShipDate="";   $("#ShipDate").val(""); 
                ';
                
                
                } // ends total changed ==1 check
                } // ends try
                catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                
                
                
                
                
                }
                
                if ($newstatus =='50') {
                
                try {
                
                $sql = "UPDATE Orders SET 
                collectiondate='0000-00-00 00:00:00',
                starttrackpause = '0000-00-00 00:00:00',
                finishtrackpause ='0000-00-00 00:00:00',
                ShipDate ='0000-00-00 00:00:00'
                WHERE ID=:getid LIMIT 1"; 
                
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                $stmt->execute();
                $total = $stmt->rowCount();
                if ($total=='1') {
                // $script.=' ';
                $infotext.=' Times Updated 349 ';
                $allok=1;
                
                $script.=' 
                initialcollectiondate=""; $("#collectiondate").val("");
                initialstarttrackpause=""; $("#starttrackpause").val("");
                initialfinishtrackpause=""; $("#finishtrackpause").val("");
                initialShipDate="";   $("#ShipDate").val(""); 
                ';
                
                } // ends total changed ==1 check
                } // ends try
                catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                
                } // ends status
                
                if ($newstatus =='60') {
                
                try {
                
                $sql = "UPDATE Orders SET 
                finishtrackpause ='0000-00-00 00:00:00',
                ShipDate ='0000-00-00 00:00:00'
                WHERE ID=:getid LIMIT 1"; 
                
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                $stmt->execute();
                $total = $stmt->rowCount();
                if ($total=='1') {
                // $script.=' ';
                $infotext.=' Times Updated 381 ';
                $allok=1;
                
                $script.=' 
                initialfinishtrackpause=""; $("#finishtrackpause").val("");
                initialShipDate="";   $("#ShipDate").val(""); 
                ';
                
                } // ends total changed ==1 check
                } // ends try
                catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                
                
                
                }
                
                if ($newstatus=='65') { $infotext.=' Deleting delivery time ';
                
                try {
                $query = "UPDATE Orders SET ShipDate='0000-00-00 00:00:00' WHERE id=:getid";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                $stmt->execute();
                $total = $stmt->rowCount();
                if ($total=='1') {
                
                $script.=' initialShipDate="";   $("#ShipDate").val(""); ';
                
                $message.=' Delivery Time Removed ';
                $allok=1;
                
                } // ends total changed ==1 check
                } // ends try
                catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
                
                }
                
                if (($currentstatus==100) and ($newstatus<100)) { // delete tracking cache if present
                
                $sql='SELECT ShipDate FROM Orders WHERE Orders.ID =? LIMIT 0,1';
                $sth=$dbh->prepare($sql);
                $data=array($id);
                $sth->execute($data);
                $ShipDate=$sth->fetchColumn();
                
                $testfile=__DIR__."/cache/jstrack/".date('Y',strtotime($ShipDate))."/".date('m',strtotime($ShipDate))."/".$id.'tracks.js';
                $kmltfile=__DIR__."/cache/jstrack/".date('Y',strtotime($ShipDate))."/".date('m',strtotime($ShipDate))."/".$id.'tracks.kml';
                
                if (!file_exists($testfile)) {
                $infotext.= ' <br /> 4911 Cache does not exist, no action needed. '.$testfile;
                } else {
                $infotext.=  ' <br /> 4918 Cache exists, needs deleting. '.$testfile;	
                unlink($testfile);
                unlink($kmltfile);
                if (file_exists($testfile)) {
                    $infotext.=  ' not deleted ';
                }
                }
                
                } // ends check to remove tracking cache if job is 100 to <100
                
                } // ends reduction in status
                
                
                
                
                
                
                
                
                
                } // ends total changed ==1 check
                } // ends try
                catch(PDOException $e) { $message.= $e->getMessage(); }
                
                } // ends page = ajax orderstatus
                
                
        
    
    
            if ($page=='ajaxchangerider') {
    if (isset($_POST['newrider'])) { $newrider = trim($_POST['newrider']); }
    try {
    
    $query = "UPDATE Orders SET lookedatbycyclisttime='0', CyclistID=:CyclistID  WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':CyclistID', $newrider, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    
    
    $query = "SELECT cojmname FROM Cyclist WHERE CyclistID=:CyclistID LIMIT 0,1";
    
    $depstmt = $dbh->prepare($query);
    $depstmt->bindParam(':CyclistID', $newrider, PDO::PARAM_INT); 
    $depstmt->execute();
    // $hasid = $depstmt->rowCount();
    
    $dep = $depstmt->fetchObject();
    $cojmname=$dep->cojmname;
    // $servicecomments=$dep->servicecomments;
    // $canhavemap=$dep->canhavemap;
    
    $message.="Rider changed to ".$cojmname;
    
    if ($newrider<>1) {
    
    $script.=' 
    $("#showriderlink").removeClass("hidden").attr("href", "cyclist.php?thiscyclist='.$newrider.'").attr("title", "'.$cojmname.' details"); 
    $("select#newrider").removeClass("red");
    ';
    } else { 
    $script.=' $("#showriderlink").addClass("hidden"); $("select#newrider").addClass("red"); ';
    
    
    }
    
    
    
    // $calcmileage=1;
    $allok=1;
    
    
    
    // $cojmaction='recalcprice';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    }
            // ends if if ($page=='ajaxchangerider')
    
    
    
    
    
    
    
    
            if ($page=='ajaxchangeserviceid') {
    if (isset($_POST['serviceid'])) { $serviceid = trim($_POST['serviceid']); }
    try {
    $query = "UPDATE Orders SET ServiceID=:serviceid , opsmaparea='' , opsmapsubarea='' WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':serviceid', $serviceid, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $query = "SELECT Service, servicecomments, chargedbycheck, canhavemap, chargedbybuild FROM Services WHERE ServiceID = :serviceid LIMIT 0,1";
    $depstmt = $dbh->prepare($query);
    $depstmt->bindParam(':serviceid', $serviceid, PDO::PARAM_INT); 
    $depstmt->execute();
    // $hasid = $clstmt->rowCount();
    $dep = $depstmt->fetchObject();
    $Service=$dep->Service;
    $servicecomments=$dep->servicecomments;
    $canhavemap=$dep->canhavemap;
    $chargedbycheck=$dep->chargedbycheck;
    $chargedbybuild=$dep->chargedbybuild;
    
    if ($chargedbycheck=='1') { $script.= ' $("#cbb").show(); '; 
    }	else { $script.= ' $("#cbb").hide(); ';	}
    
    
    
    if ($chargedbybuild<>'1') {
    $script.= ' $("#baseservicecbb").show(); ';
    $script.= ' $("#mileagerow").hide(); ';
    } else {
    $script.= ' $("#baseservicecbb").hide(); ';
    $script.= ' $("#mileagerow").show(); ';
    }
    
    
    if ($canhavemap=='') { $canhavemap='0'; }
    $script.=' canshowareafromservice=' . $canhavemap . ';  ';
    
    
    
    
    
    
    
    if ($canhavemap>0) {
    $script.=' $("#areaselectors").show();  ';
    } else { $script.='  $("#areaselectors").hide();  '; }
    
    
    
    
    if ($servicecomments) { $script.=' $("#servicecomments").html("'.$servicecomments.' ").show(); $("#servicecomments").show(); ';
    } else { $script.='  $("#servicecomments").hide();  '; }
    
    // $script.=' ordermapupdater(); ';
    
    $message.="Service changed to ".$Service;
    $calcmileage=1;
    $allok=1;
    
    
    
    $cojmaction='recalcprice';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    } // ends if if ($page=='ajaxchangeserviceid') {
    
    
    
            // status
            if ($page=='ajaxorderstatus') {
    
    $newdate=date("d/m/Y H:i");	
    $newstatus=trim($_POST['newstatus']);
    $nextactiondatecheck='1';
    
    // $message.=' Changing order status via ajax ';
    
    $query="SELECT statusname FROM status WHERE status='$newstatus' LIMIT 0,1";
    
    // $oldstatustext = mysql_result(mysql_query("SELECT statusname FROM status WHERE status='$oldstatus' LIMIT 0,1", $conn_id), 0);
    // $newstatustext = mysql_result(mysql_query($query, $conn_id), 0);
    
    // oldstatus is now $currentstatus
    
    
    
    $sql='SELECT statusname FROM status WHERE status=?';
    $sth=$dbh->prepare($sql);
    $data=array($newstatus);
    $sth->execute($data);
    $newstatustext=$sth->fetchColumn();
    
    // $message.=$result;
    
    try {
        
    $query = "UPDATE Orders SET status=:newstatus WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':newstatus', $newstatus, PDO::PARAM_INT);
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.=' Status updated to '. $newstatustext.'<br />';
    $allok=1;
    // $cojmaction='recalcprice';
    $script.=' initialstatus='.$newstatus.'; ';
    
    
    if ($newstatus =='40') { $infotext.=' Adding Travel to Collection Time  ';
    try {
    $query = "UPDATE Orders SET starttravelcollectiontime=now() WHERE id=:getid LIMIT 1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.=' Travel to Collection Time Added ';
    $allok=1;
    $script.=' initialstarttravelcollectiontime="'.$newdate.'";   $("#starttravelcollectiontime").val("'.$newdate.'");  ';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    } // ends status = 40
    
    
    if ($newstatus =='50') { $infotext.=' Adding Waiting Start Time  ';
    try {
    // $sql = "UPDATE Orders SET waitingstarttime=now() WHERE ID='$id' LIMIT 1"; 
    $query = "UPDATE Orders SET waitingstarttime=now() WHERE id=:getid LIMIT 1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.=' Waiting Start Time Added ';
    $allok=1;
    $script.=' initialwaitingstarttime="'.$newdate.'";   $("#waitingstarttime").val("'.$newdate.'");  ';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    }
    
    
    if (($currentstatus <'60' ) and ($newstatus >'59')) { $infotext.=' Adding Collection Time  ';
    
    
    
    
    try {
    $query = "UPDATE Orders SET collectiondate=now() WHERE id=:getid LIMIT 1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.=' Collection Time Added ';
    $allok=1;
    $script.=' initialcollectiondate="'.$newdate.'";   $("#collectiondate").val("'.$newdate.'");  ';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    }
    
    
    
    if ($newstatus =='60') { $infotext.=' Adding Paused Time  ';
    try {
    // $sql = "UPDATE Orders SET waitingstarttime=now() WHERE ID='$id' LIMIT 1"; 
    $query = "UPDATE Orders SET starttrackpause=now() WHERE id=:getid LIMIT 1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.=' Pause Time Added ';
    $allok=1;
    $script.='  $("#toggleresume").show(); initialstarttrackpause="'.$newdate.'";   $("#starttrackpause").val("'.$newdate.'");  ';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    }
    
    
    if (($currentstatus =='60') and ($newstatus >'60')) { $infotext.=' Adding Resume Time ';
    
    try {
    // $sql = "UPDATE Orders SET waitingstarttime=now() WHERE ID='$id' LIMIT 1"; 
    $query = "UPDATE Orders SET finishtrackpause=now() WHERE id=:getid LIMIT 1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.=' Resume Time Added ';
    $allok=1;
    $script.=' initialfinishtrackpause="'.$newdate.'";   $("#finishtrackpause").val("'.$newdate.'");  ';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    
    
    }
    
    
    
    if (($newstatus>85) and ($currentstatus<85)) {
    $infotext.=' Adding Delivery time  ';
    try {
    $query = "UPDATE Orders SET ShipDate=now() WHERE id=:getid LIMIT 1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.=' Delivery Time Added ';
    $allok=1;
    $script.=' initialShipDate="'.$newdate.'";   $("#ShipDate").val("'.$newdate.'");  ';
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    }
    
    
    
    
    
    if ($newstatus<$currentstatus) {
    
    $infotext.=' Reduction in Status ';
    
    if ($newstatus =='30') {
    
    try {
    
    $sql = "UPDATE Orders SET 
    starttravelcollectiontime='0000-00-00 00:00:00', 
    waitingstarttime ='0000-00-00 00:00:00',
    collectiondate='0000-00-00 00:00:00',
    starttrackpause = '0000-00-00 00:00:00',
    finishtrackpause ='0000-00-00 00:00:00',
    ShipDate ='0000-00-00 00:00:00'
    WHERE ID=:getid LIMIT 1"; 
    
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    // $script.=' ';
    $infotext.=' Times Updated ';
    $allok=1;
    
    // alert(" about to change ");
    
    $script.=' 
    initialstarttravelcollectiontime=""; $("#starttravelcollectiontime").val("");
    initialwaitingstarttime=""; $("#waitingstarttime").val("");
    initialcollectiondate=""; $("#collectiondate").val("");
    initialstarttrackpause=""; $("#starttrackpause").val("");
    initialfinishtrackpause=""; $("#finishtrackpause").val("");
    initialShipDate="";   $("#ShipDate").val(""); 
    ';
    
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    
    
    
    
    
    }
    
    if ($newstatus =='40') {
    
    try {
    
    $sql = "UPDATE Orders SET 
    waitingstarttime ='0000-00-00 00:00:00',
    collectiondate='0000-00-00 00:00:00',
    starttrackpause = '0000-00-00 00:00:00',
    finishtrackpause ='0000-00-00 00:00:00',
    ShipDate ='0000-00-00 00:00:00'
    WHERE ID=:getid LIMIT 1"; 
    
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    // $script.=' ';
    $infotext.=' Times Updated ';
    $allok=1;
    
    $script.=' 
    initialwaitingstarttime=""; $("#waitingstarttime").val("");
    initialcollectiondate=""; $("#collectiondate").val("");
    initialstarttrackpause=""; $("#starttrackpause").val("");
    initialfinishtrackpause=""; $("#finishtrackpause").val("");
    initialShipDate="";   $("#ShipDate").val(""); 
    ';
    
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    
    
    
    
    
    }
    
    if ($newstatus =='50') {
    
    try {
    
    $sql = "UPDATE Orders SET 
    collectiondate='0000-00-00 00:00:00',
    starttrackpause = '0000-00-00 00:00:00',
    finishtrackpause ='0000-00-00 00:00:00',
    ShipDate ='0000-00-00 00:00:00'
    WHERE ID=:getid LIMIT 1"; 
    
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    // $script.=' ';
    $infotext.=' Times Updated 349 ';
    $allok=1;
    
    $script.=' 
    initialcollectiondate=""; $("#collectiondate").val("");
    initialstarttrackpause=""; $("#starttrackpause").val("");
    initialfinishtrackpause=""; $("#finishtrackpause").val("");
    initialShipDate="";   $("#ShipDate").val(""); 
    ';
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    
    } // ends status
    
    if ($newstatus =='60') {
    
    try {
    
    $sql = "UPDATE Orders SET 
    finishtrackpause ='0000-00-00 00:00:00',
    ShipDate ='0000-00-00 00:00:00'
    WHERE ID=:getid LIMIT 1"; 
    
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    // $script.=' ';
    $infotext.=' Times Updated 381 ';
    $allok=1;
    
    $script.=' 
    initialfinishtrackpause=""; $("#finishtrackpause").val("");
    initialShipDate="";   $("#ShipDate").val(""); 
    ';
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    
    
    
    }
    
    if ($newstatus=='65') { $infotext.=' Deleting delivery time ';
    
    try {
    $query = "UPDATE Orders SET ShipDate='0000-00-00 00:00:00' WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    
    $script.=' initialShipDate="";   $("#ShipDate").val(""); ';
    
    $message.=' Delivery Time Removed ';
    $allok=1;
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); $allok=0; }
    
    }
    
    if (($currentstatus==100) and ($newstatus<100)) { // delete tracking cache if present
    
    $sql='SELECT ShipDate FROM Orders WHERE Orders.ID =? LIMIT 0,1';
    $sth=$dbh->prepare($sql);
    $data=array($id);
    $sth->execute($data);
    $ShipDate=$sth->fetchColumn();
    
    $testfile=__DIR__."/cache/jstrack/".date('Y',strtotime($ShipDate))."/".date('m',strtotime($ShipDate))."/".$id.'tracks.js';
    $kmltfile=__DIR__."/cache/jstrack/".date('Y',strtotime($ShipDate))."/".date('m',strtotime($ShipDate))."/".$id.'tracks.kml';
    
    if (!file_exists($testfile)) {
    $infotext.= ' <br /> 4911 Cache does not exist, no action needed. '.$testfile;
    } else {
    $infotext.=  ' <br /> 4918 Cache exists, needs deleting. '.$testfile;	
    unlink($testfile);
    unlink($kmltfile);
    if (file_exists($testfile)) {
        $infotext.=  ' not deleted ';
    }
    }
    
    } // ends check to remove tracking cache if job is 100 to <100
    
    } // ends reduction in status
    
    
    
    
    
    
    
    
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = ajax orderstatus
    
    
            // ajaxtargetcollectiondate
            if ($page=='ajaxtargetcollectiondate') {
    
    $jobrequestedtime=trim($_POST['targetcollectiondate']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    } else { $jobrequestedtime='0000-00-00 00:00:00'; }
    
    try {
    $query = "UPDATE Orders SET targetcollectiondate=:jobrequestedtime WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    // $script.='  initialtargetcollectiondate = "'.$_POST['targetcollectiondate'].'"; ';
    
    $message.=' Target Collection '.$jobrequestedtime;
    $allok=1;
    // $script.=' ordermapupdater(); ';
    
    $nextactiondatecheck='1';
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = ajaxtargetcollectiondate
    
    
            // collectionworkingwindow
            if ($page=='ajaxcollectionworkingwindow') {
    
    $jobrequestedtime=trim($_POST['collectionworkingwindow']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    } else { $jobrequestedtime='0000-00-00 00:00:00'; 
    
    
    $script.='
    
    $("#collectionworkingwindow").hide();
    
    ';
    
    
    }
    
    try {
    $query = "UPDATE Orders SET collectionworkingwindow=:jobrequestedtime WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    // $script.=' ordermapupdater(); ';
    
    
    // $script.=' var initialcollectionworkingwindow = "'.$_POST['collectionworkingwindow'].'"; ';
    
    
    
    $message.=' Collection Window '.$jobrequestedtime;
    $allok=1;
    // $script.=' ordermapupdater(); ';
    
    $nextactiondatecheck='1';
    
    
    
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page=='ajaxcollectionworkingwindow') {
    
    
    
            // starttravelcollectiontime
            if ($page=='ajaxstarttravelcollectiontime') {
    
    $jobrequestedtime=trim($_POST['starttravelcollectiontime']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    } else { $jobrequestedtime='0000-00-00 00:00:00'; }
    
    try {
    $query = "UPDATE Orders SET starttravelcollectiontime=:jobrequestedtime WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    
    $message.=' Travelling to collection '.$jobrequestedtime;
    $allok=1;
    // $script.=' var ajaxstarttravelcollectiontime = "'.trim($_POST['starttravelcollectiontime']).'"; ';
    
    
    
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = ajaxwaitingstart travel collection
    
    
    
            // ajaxwaitingstarttime
            if ($page=='ajaxwaitingstarttime') {
    
    $jobrequestedtime=trim($_POST['waitingstarttime']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    } else { $jobrequestedtime='0000-00-00 00:00:00'; }
    
    try {
    $query = "UPDATE Orders SET waitingstarttime=:jobrequestedtime WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    
    $message.=' On Site time changed to '.$jobrequestedtime;
    $allok=1;
    
    // $script.=' var initialwaitingstarttime="'.trim($_POST['waitingstarttime']).'"; ';
    
    
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = ajaxwaitingstarttime
    
    
    
    
            // collectiondate
            if ($page=='ajaxcollectiondate') {
    
    $jobrequestedtime=trim($_POST['collectiondate']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    } else { $jobrequestedtime='0000-00-00 00:00:00'; }
    
    try {
    $query = "UPDATE Orders SET collectiondate=:jobrequestedtime WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    
    // $script.=' var initialcollectiondate="'.trim($_POST['collectiondate']).'"; ';
    
    $message.=' Collection time changed to '.$jobrequestedtime;
    $allok=1;
    // $script.=' ordermapupdater(); ';
    
    $nextactiondatecheck='1';
    
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = ajaxcollectiondate
    
    
    
    
            if ($page=='ajaxstarttrackpause') {
    
    $jobrequestedtime=trim($_POST['starttrackpause']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    } else { $jobrequestedtime='0000-00-00 00:00:00'; }
    
    try {
    $query = "UPDATE Orders SET starttrackpause=:jobrequestedtime WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    
    $message.=' Pause time changed to '.$jobrequestedtime;
    $allok=1;
    // $script.="var initialstarttrackpause='".trim($_POST['starttrackpause'])."'; ";
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = starttrackpause
    
    
    
            if ($page=='ajaxfinishtrackpause') {
    
    $jobrequestedtime=trim($_POST['finishtrackpause']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    } else { $jobrequestedtime='0000-00-00 00:00:00'; }
    
    try {
    $query = "UPDATE Orders SET finishtrackpause=:jobrequestedtime WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    
    $message.=' Resume time changed to '.$jobrequestedtime;
    $allok=1;
    
    // $script.=' var initialfinishtrackpause="'.trim($_POST['finishtrackpause']).'"; ';
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = ajaxfinishtrackpause
    
    
    
            if ($page=='ajaxduedate') {
    
    $jobrequestedtime=trim($_POST['duedate']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    } else { $jobrequestedtime='0000-00-00 00:00:00'; }
    
    try {
    $query = "UPDATE Orders SET duedate=:jobrequestedtime WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    
    // $script.=' var initialduedate="'.trim($_POST['duedate']).'"; ';
    
    $message.=' Due Date changed to '.$jobrequestedtime;
    $allok=1;
    // $script.=' ordermapupdater(); ';
    
    $nextactiondatecheck='1';
    
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = ajaxduedate
    
    
    
    
            // ajaxdeliveryworkingwindow
            if ($page=='ajaxdeliveryworkingwindow') {
    
    $jobrequestedtime=trim($_POST['deliveryworkingwindow']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    } else { $jobrequestedtime='0000-00-00 00:00:00'; 
    
    
    $script.=' $("#deliveryworkingwindow").hide(); ';
    
    
    
    }
    
    try {
    $query = "UPDATE Orders SET deliveryworkingwindow=:jobrequestedtime WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    // $script.=' ordermapupdater(); ';
    $message.=' Delivery working window changed to '.$jobrequestedtime;
    $allok=1;
    
    // $script.=' var initialdeliveryworkingwindow="'.trim($_POST['deliveryworkingwindow']).'"; ';
    
    $nextactiondatecheck='1';
    
    
    
    
    
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = ajaxdeliveryworkingwindow
    
    
    
    
    
            if ($page=='ajaxShipDate') {
    
    $jobrequestedtime=trim($_POST['ShipDate']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    
    $query = "UPDATE Orders SET ShipDate=:jobrequestedtime WHERE id=:getid";
    
    
    } else { 
    
    $query = "UPDATE Orders SET ShipDate=:jobrequestedtime, status=65 WHERE id=:getid";
    
    $message.=' Status updated to en-route. ';
    
    $jobrequestedtime='0000-00-00 00:00:00'; 
    
    $script.=' $("select#newstatus").val("65"); initialstatus=65; ';
    
    
    }
    
    
    
    try {
    
    
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    
    $message.=' Complete time changed to '.$jobrequestedtime;
    $allok=1;
    
    // if ($jobrequestedtime=='0000-00-00 00:00:00') {  }
    
    
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = ajaxShipDate
    
    
    
    
            if ($page=='ajaxjobrequestedtime') {
    
    $jobrequestedtime=trim($_POST['jobrequestedtime']);
    
    if ($jobrequestedtime) {
    
    $jobrequestedtime = str_replace("%2F", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("%3A", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace("/", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(",", ":", "$jobrequestedtime", $count);
    $jobrequestedtime = str_replace(" ", ":", "$jobrequestedtime", $count);
    // $infotext.='<br />Target collect from : '.$jobrequestedtime.'<br /> until '.$collectionworkingwindow;
    $temp_ar=explode(":",$jobrequestedtime); 
    $collectionworkingwindowday=$temp_ar['0']; 
    $collectionworkingwindowmonth=$temp_ar['1']; 
    $collectionworkingwindowyear=$temp_ar['2']; 
    $collectionworkingwindowhour= $temp_ar['3'];
    $collectionworkingwindowminutes= $temp_ar['4'];
    $second='00';
    $jobrequestedtime= date("Y-m-d H:i:s", mktime($collectionworkingwindowhour, $collectionworkingwindowminutes, $second, 
    $collectionworkingwindowmonth, $collectionworkingwindowday, $collectionworkingwindowyear));
    } else { $jobrequestedtime='0000-00-00 00:00:00'; }
    
    try {
    $query = "UPDATE Orders SET jobrequestedtime=:jobrequestedtime WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':jobrequestedtime', $jobrequestedtime, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    
    $message.=' Time Requested changed to '.$jobrequestedtime;
    $allok=1;
    
    // $script.=' var initialjobrequestedtime="'.trim($_POST['jobrequestedtime']).'"; ';
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page = ajaxjobrequestedtime
    
    
    
    
    
            if ($page=='ajaxnumberitems') {
    if (isset($_POST['numberitems'])) { $numberitems = trim($_POST['numberitems']); }
    try {
    $query = "UPDATE Orders SET numberitems=:numberitems WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':numberitems', $numberitems, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.="Quantity changed to ".$numberitems;
    $allok=1;
    // $script.=' ordermapupdater(); ';
    $cojmaction='recalcprice';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    } // ends if if ($page=='ajaxnumberitems') {
    
    
    
    
    
            if ($page=='ajaxchangeserviceid') {
    if (isset($_POST['serviceid'])) { $serviceid = trim($_POST['serviceid']); }
    try {
    $query = "UPDATE Orders SET ServiceID=:serviceid , opsmaparea='' , opsmapsubarea='' WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':serviceid', $serviceid, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $query = "SELECT Service, servicecomments, chargedbycheck, canhavemap, chargedbybuild FROM Services WHERE ServiceID = :serviceid LIMIT 0,1";
    $depstmt = $dbh->prepare($query);
    $depstmt->bindParam(':serviceid', $serviceid, PDO::PARAM_INT); 
    $depstmt->execute();
    // $hasid = $clstmt->rowCount();
    $dep = $depstmt->fetchObject();
    $Service=$dep->Service;
    $servicecomments=$dep->servicecomments;
    $canhavemap=$dep->canhavemap;
    $chargedbycheck=$dep->chargedbycheck;
    $chargedbybuild=$dep->chargedbybuild;
    
    if ($chargedbycheck=='1') { $script.= ' $("#cbb").show(); '; 
    }	else { $script.= ' $("#cbb").hide(); ';	}
    
    
    
    if ($chargedbybuild<>'1') {
    $script.= ' $("#baseservicecbb").show(); ';
    $script.= ' $("#mileagerow").hide(); ';
    } else {
    $script.= ' $("#baseservicecbb").hide(); ';
    $script.= ' $("#mileagerow").show(); ';
    }
    
    
    
    
    
    
    
    
    
    if ($canhavemap>0) {
    $script.=' $("#areaselectors").show();  ';
    } else { $script.='  $("#areaselectors").hide();  '; }
    
    
    
    
    if ($servicecomments) { $script.=' $("#servicecomments").html("'.$servicecomments.' ").show(); $("#servicecomments").show(); ';
    } else { $script.='  $("#servicecomments").hide();  '; }
    
    // $script.=' ordermapupdater(); ';
    
    $message.="Service changed to ".$Service;
    $calcmileage=1;
    $allok=1;
    
    
    
    $cojmaction='recalcprice';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    } // ends if if ($page=='ajaxchangeserviceid') {
    
    
    
    
            if ($page=='ajaxjobcomments') {
    if (isset($_POST['jobcomments'])) { $jobcomments = trim($_POST['jobcomments']); }
    
    
    
    if ($jobcomments) {
    
    $jobcomments = trim(htmlspecialchars($jobcomments));
    $jobcomments = str_replace("'", "&#39;", "$jobcomments", $count);
    $jobcomments = str_replace("£", "&#163;", "$jobcomments", $count);
    
    
    $script.=' initialjobcomments=1; ';
    
    } else { // $message.=' job comments blank '; 
    
    $script.=' initialjobcomments="" ';
    
    }
    
    
    
    
    
    try {
    $query = "UPDATE Orders SET jobcomments=(UPPER(:jobcomments)) WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':jobcomments', $jobcomments, PDO::PARAM_INT); 
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.="Job Comments changed to ".addslashes(json_encode($jobcomments));
    $allok=1;
    // $cojmaction='recalcprice';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    } // ends page ajaxjobcomments
    
    
    
    
            if ($page=='ajaxprivatejobcomments') {
    if (isset($_POST['privatejobcomments'])) { $privatejobcomments = trim($_POST['privatejobcomments']); }
    
    if ($privatejobcomments) {
    
    
    
    
    
    // $privatejobcomments = trim(htmlspecialchars($privatejobcomments));
    $privatejobcomments = str_replace("'", "&#39;", "$privatejobcomments", $count);
    
    $privatejobcomments = str_replace("£", "&#163;", "$privatejobcomments", $count);
    
    
    // $privatejobcomments = strtoupper($privatejobcomments);
    
    // $privatejobcomments = str_replace("&POUND;", "&pound;", "$privatejobcomments", $count);
    
    
    // $message.=" count was ".$count; 
    
    $script.=' initialprivatejobcomments=1; ';
    
    }
    else
    {    
    $script.=' initialprivatejobcomments=""; ';
    
    }
    
    
    
    
    try {
    $query = "UPDATE Orders SET privatejobcomments=(UPPER(:privatejobcomments)) WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':privatejobcomments', $privatejobcomments, PDO::PARAM_INT); 
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.="Private Job Comments changed to ".addslashes(json_encode($privatejobcomments));
    $allok=1;
    // $cojmaction='recalcprice';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    } // ends page ajaxprivatejobcomments
    
    
    
    
            if ($page=='ajaxpodsurname') {
    if (isset($_POST['podsurname'])) { $podsurname = trim($_POST['podsurname']); }
    $podsurname = str_replace("'", "&#39;", "$podsurname", $count);
    $podsurname = str_replace('"', "&#39;", "$podsurname", $count);
    try {
    $query = "UPDATE Orders SET podsurname=(UPPER(:podsurname)) WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':podsurname', $podsurname, PDO::PARAM_INT); 
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.="POD Surname changed to ".$podsurname;
    $script.=" podsurname = '".$podsurname."';";
    
    $allok=1;
    // $cojmaction='recalcprice';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    } // ends page ajaxpodsurname
    
    
    
    
    
    
            if ($page=='ajaxremovepod') {
    
                $backupref=$id.'-'.date("U");
    
                try {
                    $query = "UPDATE cojm_pod SET id= :backupref WHERE id=:getid";
                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam(':backupref', $backupref, PDO::PARAM_INT); 
                    $stmt->bindParam(':getid', $publicid, PDO::PARAM_INT); 
                    $stmt->execute();
                    $total = $stmt->rowCount();
                    if ($total=='1') {
                        $message.="POD Removed<br/>Image backed up to <a href='../podimage.php?id=".$backupref."'>".$backupref."</a>";
                        $allok=1;
                        $script.='   haspod=0;
                        $("#uploadpodfile").show();
                        $("#podimagecontainer").hide(); alert("You will need to clear your browser cache");   ';
                    } // ends total changed ==1 check
                } // ends try
                
                catch(PDOException $e) { 
                $message.= $e->getMessage(); 
                }
                
            } // ends page ajax ajaxremovepod
    
    
    
    
    
            if ($page=='orderaddpod') {
    
    
    $maxpodsize=200000; // bytes
    
    
    
    $allowedExts = array("gif", "jpeg", "jpg", "png", "JPG");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);
    if ((($_FILES["file"]["type"] == "image/gif")
    || ($_FILES["file"]["type"] == "image/jpeg")
    || ($_FILES["file"]["type"] == "image/jpg")
    || ($_FILES["file"]["type"] == "image/pjpeg")
    || ($_FILES["file"]["type"] == "image/x-png")
    || ($_FILES["file"]["type"] == "image/png"))
    && ($_FILES["file"]["size"] < $maxpodsize)
    && in_array($extension, $allowedExts)) {
        
    
    //	echo ' 38 ';
        
        
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
    
    if(!get_magic_quotes_gpc())
    {
        $fileName = addslashes($fileName);
    }
    
    try {
    // Query
    $statement = $dbh->prepare("INSERT INTO cojm_pod 
    (id, name, size, content, type, time) 
    values 
    (:publicid, :name, :size, :content, :type, now())
    ");
    // Assign and execute query
    
    $statement->bindParam(':publicid', $publicid, PDO::PARAM_STR);
    $statement->bindParam(':name', $fileName, PDO::PARAM_STR);
    $statement->bindParam(':size', $_FILES["file"]["size"], PDO::PARAM_STR);
    $statement->bindParam(':content', $content, PDO::PARAM_STR);
    $statement->bindParam(':type', $_FILES["file"]["type"], PDO::PARAM_STR);
    $statement->execute();
    $message.="POD Added to Job";
    $message.=" <a href='../podimage.php?id=".$publicid."'>".$publicid."</a>";
    $allok=1;
    $script.=' haspod=1; 
    
    $("#ajaxremovepod").show(); 
    $("#podimagecontainer").show();
    $("#uploadpodfile").hide();
    $("#orderpod").attr("src", "../podimage.php?id='.$publicid.'"); 
    
    ';
    
    } // ends try
    
    
    
    catch(PDOException $e) { $message.= $e->getMessage(); }
        }
    } else {
        
    $message.='Filetype Not Supported ';
    
    if	($_FILES["file"]["size"] > 200000) { 
    
    $message.="Too Large, use a smaller file";
    
    }	
    }
    
    $script.="
    
    $('#uploadpodprogress').hide();
    ";
    
    } // ends if ($page=='orderaddpod') 
        
    
    
    
    
            if ($page=='ajaxcbb') {
    
    if (isset($_POST['cbbchecked'])) { $cbbchecked = trim($_POST['cbbchecked']); }
    if (isset($_POST['cbbname'])) { $cbbname = trim($_POST['cbbname']); }
    if (isset($_POST['waitingmins'])) { $waitingmins = trim($_POST['waitingmins']); }
    
    if ($cbbname=='waitingmins') { 
    $cbbname='cbbc3';
    if ($waitingmins>0) {  $cbbchecked=1; } else { $cbbchecked=0; }
    }
    
    $infotext.=$cbbname.' '.$cbbchecked.' '.$waitingmins;
    
    $cbbid=preg_replace("/[^0-9]/","",$cbbname);
    
    $query = "SELECT 
    cbbname
    FROM chargedbybuild
    WHERE 
    `chargedbybuild`.`chargedbybuildid` = :getid LIMIT 0,1";
    $clstmt = $dbh->prepare($query);
    $clstmt->bindParam(':getid', $cbbid, PDO::PARAM_INT); 
    $clstmt->execute();
    $client = $clstmt->fetchObject();
    $txtcbbname=$client->cbbname;
    
    try {
        
    $query = "UPDATE Orders SET ".$cbbname."=:cbbchecked, waitingmins=:waitingmins WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':cbbchecked', $cbbchecked, PDO::PARAM_INT); 
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->bindParam(':waitingmins', $waitingmins, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.=" ".$txtcbbname." ";
    
    if ($cbbchecked>0) {
    $message.=' Checked '; if ($cbbname=='cbbc3') { $message.=' to '.$waitingmins.' mins.'; }
    } else {
    $message.=' Unchecked ';
    }
    
    $allok=1;
    $cojmaction='recalcprice';
    $script.=' waitingmins= '.$waitingmins.'; ';
    
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends ($page=='ajaxcbb') 
    
    
    
    
    
    
    
            if ($page=='ajaxeditcost') {
    if (isset($_POST['newcost'])) { $newcost = trim($_POST['newcost']); }
    
    if ($newcost=='') { $newcost=0; }
    
    
    if (is_numeric($newcost)) {
    
    $query = "SELECT 
    vatband
    FROM Orders 
    INNER JOIN Services
    WHERE `Orders`.`ServiceID` = `Services`.`ServiceID`
    AND `Orders`.`id` = :getid LIMIT 0,1";
    $clstmt = $dbh->prepare($query);
    $clstmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $clstmt->execute();
    // $hasid = $clstmt->rowCount();
    $client = $clstmt->fetchObject();
    $vatband=$client->vatband;
    
    
    
    $infotext.='vat band found : '.$vatband.'<br />';
    
    if (isset ($globalprefrow['vatband'.$vatband])) {
    
    $newvatcost=($newcost)*(($globalprefrow['vatband'.$vatband])/100);
    
    } else { $newvatcost='0.000';}
    
    
    
    
    
    
    try {
    $query = "UPDATE Orders 
    SET FreightCharge=:newcost, 
    vatcharge=:newvatcost, 
    iscustomprice='1', 
    clientdiscount='0.00' ,
    cbb1=0,
    cbb2=0,
    cbb3=0,
    cbb4=0,
    cbb5=0,
    cbb6=0,
    cbb7=0,
    cbb8=0,
    cbb9=0,
    cbb10=0,
    cbb11=0,
    cbb12=0,
    cbb13=0,
    cbb14=0,
    cbb15=0,
    cbb16=0,
    cbb17=0,
    cbb18=0,
    cbb19=0,
    cbb20=0
    WHERE id=:getid";
    
    
    // $query='';
    
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':newcost', $newcost, PDO::PARAM_INT); 
    $stmt->bindParam(':newvatcost', $newvatcost, PDO::PARAM_INT); 
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
        
    $message.="Pricelocked to &".$globalprefrow["currencysymbol"]. number_format(($newcost), 2, '.', ',');
    $allok=1;
    $cojmaction='recalcprice'; // does not actually recalc price as custom flag but resets display for vat etc 
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    
    } else { // not numeric
    
    $message.='Please enter a numeric price.';
    
    }
    
    } // ends page ajaxeditcost
    
    
    
    
    
            if ($page == "ajaxcancelpricelock" ) {
    
    try {
        
    $query = "UPDATE Orders SET iscustomprice='0' WHERE id=:getid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
    $stmt->execute();
    $total = $stmt->rowCount();
    if ($total=='1') {
    $message.="Pricelock Cancelled ";
    $allok=1;
    $cojmaction='recalcprice';
    } // ends total changed ==1 check
    } // ends try
    catch(PDOException $e) { $message.= $e->getMessage(); }
    
    } // ends page=ajaxcancelpricelock
    
    
    
    

            if ($page=='ajaxchangeclient') {
        if (isset($_POST['oldclientorder'])) {
            $oldclientorder = trim($_POST['oldclientorder']);
        }
        
        if (isset($_POST['newclientorder'])) {
            $newclientorder = trim($_POST['newclientorder']);
        }

        if (($newclientorder<>'') and ($newclientorder<>$oldclientorder)) { // ok to proceed
            $infotext.='';
            try {
                $query = "UPDATE Orders SET CustomerID=:CustomerID, orderdep=0 WHERE id=:getid";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':CustomerID', $newclientorder, PDO::PARAM_INT); 
                $stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
                $stmt->execute();
                $total = $stmt->rowCount();
                if ($total=='1') {
                    $query = "SELECT CompanyName, isdepartments, Notes FROM Clients WHERE CustomerID = :CustomerID LIMIT 0,1";
                    $clstmt = $dbh->prepare($query);
                    $clstmt->bindParam(':CustomerID', $newclientorder, PDO::PARAM_INT); 
                    $clstmt->execute();
                    // $hasid = $clstmt->rowCount();
                    $client = $clstmt->fetchObject();
                    $clientname=$client->CompanyName;
                    $clientNotes=$client->Notes;
                    $isdepartments=$client->isdepartments;
	
                    $script.='$("#clientlink").attr("href", "new_cojm_client.php?clientid='.$newclientorder.'"); ';
                    $script.='$("#clientlink").attr("title", "'.$clientname.' Details"); ';
	
                    if ($clientNotes) {
                        $script.=' $("#clientNotes").html("'.$clientNotes.'").show(); ';
                    } else {
                        $script.=' $("#clientNotes").html("").hide(); ';
                    }
                    
                    if ($isdepartments=='1') {
                        $query = "SELECT depnumber, depname , isactivedep FROM clientdep WHERE associatedclient = :CustomerID ORDER BY isactivedep DESC, depname"; 
                        $deprowstmt   = $dbh->prepare($query);
                        $deprowstmt->bindParam(':CustomerID', $newclientorder, PDO::PARAM_INT); 
                        $deprowstmt->execute();
                        $deprow = $deprowstmt->fetchAll();
                        $script.='
                        toAppendto= "<option value=0 >No Department</option>" + ';
                        foreach ($deprow as $drow ) {
                            $script.='"<option value='.$drow['depnumber'].' >'.$drow['depname'];
                            if ($drow['isactivedep']<>'1') {
                                $script.=' Inactive ';
                            }
                            $script.='</option>" + ';
                        }
                        
                        $script.='"";
                        $("div#clientdep.fsr input.ui-autocomplete-input").val("");
                        $("#orderselectdep").html(toAppendto);	
                        $("#clientdep").show(); 
                        $("#depcomboboxbutton").click();';
                    }
                    else { // no departments for this client
	
                        $script.='	$("#clientdep").hide(); ';	
                        $script.='	$("#clientdepnotes").hide(); ';
                    }
	
                    $message.="Client updated to ".$clientname;
                    $allok=1;
                    $cojmaction='recalcprice';
                    $script.=' oldclientorder=' . $newclientorder . '; initialdeporder=-1; 
                    $("#autocompleteorderselectdep").val(""); ';
                } // ends total changed ==1 check
            } // ends try
            
            catch(PDOException $e) { $message.= $e->getMessage(); }
            
        }
        else { // ends check new / old clients different
            $message.='Issue with old / new client';
        }
    } // ends page ajaxchangeclient






            if ($page=='ajaxchangedep') {
    if (isset($_POST['olddeporder'])) { $olddeporder = trim($_POST['olddeporder']); }
if (isset($_POST['newdeporder'])) { $newdeporder = trim($_POST['newdeporder']); }

$infotext.='old : '.$olddeporder.'<br />';
$infotext.='new : '.$newdeporder.'<br />';

if ($olddeporder<>$newdeporder) {

$infotext.='different, ok to proceed<br />';


 
 try {
$query = "UPDATE Orders SET orderdep=:orderdep WHERE id=:getid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':orderdep', $newdeporder, PDO::PARAM_INT); 
$stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();

$infotext.=$total.' row updated <br />';



if ($total=='1') {


$query = "SELECT depname, associatedclient, depcomment FROM clientdep WHERE depnumber = :depnumber LIMIT 0,1";
$depstmt = $dbh->prepare($query);
$depstmt->bindParam(':depnumber', $newdeporder, PDO::PARAM_INT); 
$depstmt->execute();
// $hasid = $clstmt->rowCount();
$dep = $depstmt->fetchObject();
$depname=$dep->depname;
$associatedclient=$dep->associatedclient;
$depcomment=$dep->depcomment;

if ($newdeporder==0) { $message.="Department Removed"; } else { $message.="Department updated to ".$depname; }
$allok=1;
$script.=" var initialdeporder=$newdeporder;

";


if ($depcomment) { 
$script.=' $("#clientdepnotes").html("'.$depcomment.'").show(); ';
} else { 
$script.=' $("#clientdepnotes").html("").hide(); ';
}


if ($newdeporder>0) { 


$script.='$("#clientdeplink").show().attr("href", "new_cojm_department.php?depid='.$newdeporder.'"); ';
$script.='$("#clientdeplink").attr("title", "'.$depname.' Details"); ';

} else {
	
$script.='$("#clientdeplink").hide(); ';
	
}





} else { 

$message.='Unable to change Department, please refresh page.';

}	// ends check for 1 changed
} // ends try 

catch(PDOException $e) { $message.= $e->getMessage(); }


} else { // ends check for different values for old and new department

$message.='Not Changed as Same Department';

}

} // ends page==ajaxchangedep







            if ($page=='ajaxrequestor') {
if (isset($_POST['requestor'])) { $requestor = trim($_POST['requestor']); }
 $requestor = str_replace("'", "&#39;", "$requestor", $count);
try {
$query = "UPDATE Orders SET requestor=(UPPER(:requestor)) WHERE id=:getid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':requestor', $requestor, PDO::PARAM_INT); 
$stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
if ($total=='1') {
$message.="Job Requestor changed to ".$requestor;
$allok=1;

$script.=" initialrequestor= '".$requestor."';";

// $cojmaction='recalcprice';
} // ends total changed ==1 check
} // ends try
catch(PDOException $e) { $message.= $e->getMessage(); }
} // ends page ajaxrequestor






            if ($page=='ajaxclientjobreference') {
if (isset($_POST['clientjobreference'])) { $clientjobreference = trim($_POST['clientjobreference']); }
 $clientjobreference = str_replace("'", "&#39;", "$clientjobreference", $count);
try {
$query = "UPDATE Orders SET clientjobreference=(UPPER(:clientjobreference)) WHERE id=:getid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':clientjobreference', $clientjobreference, PDO::PARAM_INT); 
$stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
if ($total=='1') {
$message.="Client Ref changed to ".$clientjobreference;

$script.=" var initialclientjobreference='".$clientjobreference."';    ";

$allok=1;
// $cojmaction='recalcprice';
} // ends total changed ==1 check
} // ends try
catch(PDOException $e) { $message.= $e->getMessage(); }
} // ends page ajaxpodsurname




            if ($page=='ajaxchangeopsmaparea') {
if (isset($_POST['opsmaparea'])) { $opsmaparea = trim($_POST['opsmaparea']); }
try {
$query = "UPDATE Orders SET opsmaparea=:opsmaparea, opsmapsubarea='0' WHERE id=:getid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
$stmt->bindParam(':opsmaparea', $opsmaparea, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
if ($total=='1') {


$query = "SELECT opsname, descrip, istoplayer FROM opsmap WHERE opsmapid = :opsmaparea LIMIT 0,1";
$depstmt = $dbh->prepare($query);
$depstmt->bindParam(':opsmaparea', $opsmaparea, PDO::PARAM_INT); 
$depstmt->execute();
// $hasid = $clstmt->rowCount();
$dep = $depstmt->fetchObject();
$opsname=$dep->opsname;
$descrip=$dep->descrip;
$istoplayer=$dep->istoplayer;

if ($opsmaparea==0) { 
$message.="Ops Map Removed";
$script.=' $("#arealink").hide(); ';

} else {
$message.="Ops Map changed to ".$opsname;
$script.=' $("#arealink").show().attr("href", "opsmap-new-area.php?areaid='.$opsmaparea.'"); ';


}

$allok=1;

$script.=' $("#arealink").attr("title", "'.$opsname.' Details"); ';
$script.=' $("#subarealink").hide(); ';

if ($descrip) { 
$script.=' $("#areacomments").html("'.$descrip.'<span id=\"subareacomments\"></span>").show(); ';
} else {
$script.='$("#areacomments").hide(); ';	
}







if ($istoplayer>0) { 


$query = "SELECT depnumber, depname , isactivedep FROM clientdep WHERE associatedclient = :CustomerID ORDER BY isactivedep DESC, depname"; 


$query = "SELECT opsmapid, opsname, descrip  FROM opsmap WHERE type=2 AND inarchive<>1 AND corelayer=:opsmaparea ";
$deprowstmt   = $dbh->prepare($query);
$deprowstmt->bindParam(':opsmaparea', $opsmaparea, PDO::PARAM_INT); 
$deprowstmt->execute();
$deprow = $deprowstmt->fetchAll();



$script.='

toAppendto= "<option value=0 >Choose SubArea</option>" + ';

foreach ($deprow as $drow ) {
	
$script.='"<option value='.$drow['opsmapid'].' >'.$drow['opsname'];

// if ($drow['isactivedep']<>'1') { $script.=' Inactive '; }

$script.='</option>" +
';	
	
	}

$script.=' ""

$("#opsmapsubarea").val("");
$("#opsmapsubarea").html(toAppendto);	
$("#opsmapsubarea").show();
';

} else {
	
	

$script.='

$("#opsmapsubarea").hide();
$("#subarealink").hide();
';	
	
}




$script.='

// ordermapupdater();

';

// $cojmaction='recalcprice';
} // ends total changed ==1 check
} // ends try
catch(PDOException $e) { $message.= $e->getMessage(); }
} // ends if ($page=='ajaxchangeopsmaparea') 


	
	
            if ($page=='ajaxchangeopsmapsubarea') {
if (isset($_POST['opsmapsubarea'])) { $opsmapsubarea = trim($_POST['opsmapsubarea']); }
try {
$query = "UPDATE Orders SET opsmapsubarea=:opsmapsubarea WHERE id=:getid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':getid', $id, PDO::PARAM_INT); 
$stmt->bindParam(':opsmapsubarea', $opsmapsubarea, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
if ($total=='1') {


$query = "SELECT opsname, descrip, istoplayer FROM opsmap WHERE opsmapid = :opsmapsubarea LIMIT 0,1";
$depstmt = $dbh->prepare($query);
$depstmt->bindParam(':opsmapsubarea', $opsmapsubarea, PDO::PARAM_INT); 
$depstmt->execute();
// $hasid = $clstmt->rowCount();
$dep = $depstmt->fetchObject();
$opsname=$dep->opsname;
$descrip=$dep->descrip;
$istoplayer=$dep->istoplayer;

if ($opsmapsubarea==0) { 
$message.="Sub Area Removed";
} else {
$message.="Sub Area changed to ".$opsname;
}



$allok=1;


if ($opsmapsubarea>0) {
	
$script.=' $("#subarealink").show().attr("href", "opsmap-new-area.php?areaid='.$opsmapsubarea.'"); ';
$script.=' $("#subarealink").attr("title", "'.$opsname.' Details"); ';
$script.=' $("#subarealink").show(); ';

// finishes sub area > 0, now no sub area
} else {
	
$script.='
$("#subarealink").hide();
';	
	
}

if ($descrip) {
$script.=' $("#subareacomments").html(" ('.$descrip.') ").show(); 
 $("#areacomments").show(); 

';

} else {
$script.='  $("#subareacomments").hide();  ';
}

// $script.=' ordermapupdater(); ';

// $cojmaction='recalcprice';
} // ends total changed ==1 check
} // ends try
catch(PDOException $e) { $message.= $e->getMessage(); }
} // ends if ($page=='ajaxchangeopsmapsubarea') 

	
	
	
	
	
	
	
	
            ////////////////      RECALCS DISTANCE	
	
            if ($calcmileage==1) {


include("GeoCalc.class.php");



$sql = "SELECT 
CollectPC,
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
ShipPC,
RMcount,
LicensedCount
FROM Orders, Services
WHERE
Orders.ServiceID = Services.ServiceID 
AND Orders.ID = ". $id. " LIMIT 1";
$sql_result = mysql_query($sql)  or mysql_error();
$num_rows = mysql_num_rows($sql_result);
if ($num_rows>0) {
while ($row = mysql_fetch_array($sql_result)) {

// $infotext.=' <br />calcmileage function 2011';

if ($id) {

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

if ($globalprefrow['inaccuratepostcode']<>'1') {

// $infotext.=' <br /> postcode flag is'.$globalprefrow['inaccuratepostcode'].'with no gap.';
$tempdist='';
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

$sql = "UPDATE Orders SET distance='$tempdist' WHERE ID = $id LIMIT 1";
$result = mysql_query($sql);
if ($result){ 
 $infotext.="<br />1401 Distance updated to <strong>".$tempdist.'</strong>'; 
}
else { 

$infotext.=mysql_error()."<br> <strong>An error occured during updating distance</strong>"; 
$alerttext.="<p> <strong>An error occured during updating distance</strong><br />".mysql_error()."</p>"; 

}

$co2perdist=$globalprefrow['co2perdist']*$tempdist;
$pm10perdist=$globalprefrow['pm10perdist']*$tempdist;

 $infotext.="<br />co2 : ".$co2perdist;
 $infotext.="<br />pm10 : ".$pm10perdist;

// $infotext.='<br />rm : '.$row['RMcount'].' lice : '.  ($row['LicensedCount']);

if (($row['RMcount']) or ($row['LicensedCount'])) {

$co2perdist='';
$pm10perdist='';
}

// else { echo ($row['CO2Saved']*$row["numberitems"]); }
// if job is hourly rate

// $infotext.="<strong>Updating Emission Savings</strong><br>";
 $sql = "UPDATE Orders SET co2saving='$co2perdist' , pm10saving='$pm10perdist' WHERE ID='$id' LIMIT 1"; 
 $result = mysql_query($sql);
// $infotext.='<br />'.$sql;
 
if ($result){ 
 $infotext.="<br />Emission savings updated, co2 is".$co2perdist; 
// $infotext.='<br />'.$sql; 
 
} 
else { $message."<br /><strong>An error occured during updating emissions savings</strong>"; } 



// echo $alerttext;


}
}




// UPDATES CO2	
	




} /////////////////    END SCHECK FOR HAVING AN ID   /////////////////////////

}   ////////////////      FINISHES RECALC DISTANCE






            if ($nextactiondatecheck==1) { 



$query = "
SELECT 
status,
targetcollectiondate,
collectionworkingwindow,
duedate,
deliveryworkingwindow
FROM Orders 
INNER JOIN Services, Clients
WHERE `Orders`.`id` = :getid LIMIT 0,1";
$cpstmt = $dbh->prepare($query);
$cpstmt->bindParam(':getid', $id, PDO::PARAM_INT); 
$cpstmt->execute();
$cp = $cpstmt->fetchObject();

$status=$cp->status;
$targetcollectiondate=$cp->targetcollectiondate;
$duedate=$cp->duedate;
$collectionworkingwindow=$cp->collectionworkingwindow;
$deliveryworkingwindow=$cp->deliveryworkingwindow;









if ($status<49){

if ($collectionworkingwindow<>'0000-00-00 00:00:00')  { $nextactiondate=$collectionworkingwindow; } else {
$nextactiondate = $targetcollectiondate; 
}



} else { 

if ($deliveryworkingwindow<>'0000-00-00 00:00:00')  { $nextactiondate=$deliveryworkingwindow; } else {
$nextactiondate = $duedate; 
}


}
$sql = "UPDATE Orders SET nextactiondate='$nextactiondate' WHERE ID='$id' LIMIT 1";
$result = mysql_query($sql, $conn_id); 
if ($result){
	
$infotext.="<br />next action time updated"; 

} else {
$infotext.="<br />error occured during updating next action time ! ".$nextactiondate."</strong>"; 
$alerttext.="<p>Error occured during updating next action time </p>"; 

}




}



            ///////////////      RECALCS PRICE /////////////////////////////////////





            ///////////////////////////////////   RECALC PRICE ///////////////////

            if ($cojmaction=='recalcprice') {
$buildloopcharge='';


// $script.=" alert('recalc');  ";




$query = "
SELECT 
FreightCharge,
vatcharge,
clientdiscount,
iscustomprice, 
distance,
ts, 
tsmicro, 
chargedbybuild,
chargedbycheck,
waitingmins,
numberitems,
vatband,
Price,
cbbdiscount,
invoicetype,
co2saving,
CO2Saved,
pm10saving,
PM10Saved
FROM Orders 
INNER JOIN Services, Clients
WHERE `Orders`.`ServiceID` = `Services`.`ServiceID`
AND Orders.CustomerID = Clients.CustomerID 
AND `Orders`.`id` = :getid LIMIT 0,1";
$cpstmt = $dbh->prepare($query);
$cpstmt->bindParam(':getid', $id, PDO::PARAM_INT); 
$cpstmt->execute();
$cp = $cpstmt->fetchObject();

$iscustomprice=$cp->iscustomprice;
$ifcbbbuile=$cp->chargedbybuild;
$ifcbbbuild=$cp->chargedbycheck;
$distance=$cp->distance;
$numberitems=$cp->numberitems;
$cbbwaitingcost=$cp->waitingmins;
$orgiFreightCharge=$cp->FreightCharge;
$origvatcharge=$cp->vatcharge;
$origclientdiscount=$cp->clientdiscount;
$vatband=$cp->vatband;
$serviceprice=$cp->Price;
$cdiscount=$cp->cbbdiscount;
$origcdiscount=$cp->cbbdiscount;
$invoicetype=$cp->invoicetype;
$co2saving=$cp->co2saving;
$CO2Saved=$cp->CO2Saved;
$pm10saving=$cp->pm10saving;
$PM10Saved=$cp->PM10Saved;

// $infotext.='<br>301 custom : '.$iscustomprice;
// $infotext.='<br>301 custom : '.$ifcbbbuile;
// $infotext.='<br>301 Distance : '.$distance;


if ($iscustomprice=='0') {
 $infotext.='<br/>4513 Recalculating total price';

 
if ($ifcbbbuile=='1') { // mileage rate
$infotext.='<br />4531 about to Update 1st mile cost ';


$cbbnewcost = mysql_result(mysql_query("
SELECT cbbcost from chargedbybuild 
WHERE chargedbybuildid = 1
LIMIT 1
", $conn_id), 0); // gets 1st mile rate

 
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
 WHERE ID='$id' LIMIT 1"; 
 $result = mysql_query($sql, $conn_id);

 
// $infotext.='<br />4610 '. $result;


$buildloopcharge=$buildloopcharge+$cbbnewcost; 



} else { // ends dist greatrer than 1
$cbbnewcost='0.00';

// set cost on cbb2
 $sql = "UPDATE Orders 
 SET cbb2='0.00' ,
 cbbc2='0' 
 WHERE ID='$id' LIMIT 1"; 
 $result = mysql_query($sql, $conn_id);

// $infotext.='<br /> 4627 '. $sql;

 
}


// $infotext.='<br/>2nd mile sql and cost is : '.$sql.' ' .$cbbnewcost;


// set main price to zero

} else { // ends mileage rate, set service price : 



 $infotext.='<br/>Service Price : '.$serviceprice;

// $infotext.='<br/>Number Items : '.$numberitems;

$buildloopcharge=$buildloopcharge+($numberitems*$serviceprice);

// set cbb1 and 2 to zero

} // ends chck for distance / non-distance (non distance bit)




 
 // starts 2nd phase check box pricing
 

 

 
 $cbbnewcost = mysql_result(mysql_query("
SELECT cbbc2 from Orders 
 WHERE ID='$id'
LIMIT 1
", $conn_id), 0); // test


 
 
// $infotext.='<br /> cbbc2 is '.$cbbnewcost;
 
 

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

// $infotext.='<br/>Client Discount Percentage : '.$cdiscount;

$cdiscount=((100-$cdiscount)*0.01);
$priceexvat=$cdiscount*$buildloopcharge;
$clientdiscount=$buildloopcharge-$priceexvat;

// $infotext.='<br/>5260 Discount to client : '.$clientdiscount;
// $infotext.='<br/>5262 New ex-VAT Charge : '.$priceexvat;

// get services vatband
$newvatcost='0.000'; 
if ($vatband<>'0')  { 
$infotext.='<br />6128 vatband is '.$vatband; 


// if (isset($globalprefrow['vatband'].$vatband)) {

$newvatcost=($priceexvat)*(($globalprefrow['vatband'.$vatband])/100);

$newvatcost=round($newvatcost, 2);

 $infotext.='<br/>VAT cost : '.$newvatcost;

  }

// else { $newvatcost='0.000'; }


// $orgiFreightCharge=$cp->FreightCharge;
// $origvatcharge=$cp->vatcharge;
// $origclientdiscount=$cp->clientdiscount;


$infotext.='<br />FC Orig '.$orgiFreightCharge.' vs '.$priceexvat; if ($orgiFreightCharge==$priceexvat) { $infotext.=' same';}
$infotext.='<br />VAT Orig '.$origvatcharge.' vs '.$newvatcost; if ($origvatcharge==$newvatcost) { $infotext.=' same';}
$infotext.='<br />FC Orig '.$origclientdiscount.' vs '.$clientdiscount; if ($origclientdiscount==$clientdiscount) { $infotext.=' same';}

if (($orgiFreightCharge==$priceexvat) and ($origvatcharge==$newvatcost) and ($origclientdiscount==$clientdiscount)) {

$infotext.='No price update needed';

} else {
	
$infotext.='Price update needed';

 $sql = "UPDATE Orders 
 SET FreightCharge='$priceexvat', 
 vatcharge='$newvatcost', 
 clientdiscount='$clientdiscount' 
 WHERE ID='$id' LIMIT 1"; 


$priceexvat=number_format (($priceexvat), 2, '.', '');

 
 $result = mysql_query($sql, $conn_id);
 if ($result){ 
 
 $message.="<br />Price updated to &".$globalprefrow["currencysymbol"]. ($priceexvat+$newvatcost) .""; } 
 else { 
 
 $infotext.="<br /><strong>An error occured during updating main pricing</strong>"; 
 
 
 
 } 

 
 
 
 
} // ends check for main charge <>0.00
 
// $infotext.='<br />5300 discount : '.$clientdiscount;
} else { // ends check for not to change if a custom price

$priceexvat=$orgiFreightCharge;


}



// ends check that one of the price fields has changed

 
 
 
 
 
 
// $("#pricerow").html("<input id=\"newcost\" title=\"excl. VAT\" type=\"text\" form=\"editcost\" class=\"ui-state-default ui-corner-all caps numberitems\" name=\"newcost\" value=\"'.$priceexvat.'\"> ");
 
 
 
 $script.=' 
 $("#pricerowleft").html("';


$priceexvat=number_format (($priceexvat), 2, '.', '');
if ($newvatcost>'0.00') { $script.='<span title=\"Incl. VAT\"> &'.$globalprefrow["currencysymbol"].number_format (($newvatcost+$priceexvat), 2, '.', ',').'</span> ';
}



$script.='");
 
 
$("#newcost").val('.$priceexvat.'.toFixed(2));
';



if ($iscustomprice==1) {
$script.='$("#buttoncancelpricelock").show(); ';
	
	
} else { 

$script.='$("#buttoncancelpricelock").hide(); ';

}
 
 
 
 
if ($priceexvat<>'0.00') {
if ($newvatcost<>'0.00') {

$tempvatcost= number_format($newvatcost, 2, '.', ','); 

$script.='$("#pricerow").html("';
$script.= ' + &'. $globalprefrow["currencysymbol"]. $tempvatcost.' VAT ';
$script.='");';

} else {
		
$script.='$("#pricerow").html("';
$script.= ' No VAT. '; 
$script.='");';
	
}






if ((float)$origcdiscount<>'0') {

$script.='$("#pricerow").append("';
$script.= ' Discount : '. (float)$origcdiscount.'% (&'. $globalprefrow["currencysymbol"].number_format($clientdiscount, 2, '.', '').') ';
$script.='");
';

}





if ($invoicetype==3) { 

$script.='$("#pricerow").append("';
$script.=  " <span style='". $globalprefrow['courier6']."'> Payment on PU </span>"; 
$script.='");
';

} 
if ($invoicetype==4) { 

$script.='$("#pricerow").append("';
$script.=  " <span style='". $globalprefrow['courier6']."'> Payment on Drop </span>"; 
$script.='");
';

}



if ($numberitems>49) {
$script.='$("#pricerow").append("';
$script.= ' &' .$globalprefrow["currencysymbol"]. number_format(($priceexvat / ($numberitems/1000)), 2, '.', '') .' / k ';
$script.='");
';

} elseif ($numberitems>1) {
$script.='$("#pricerow").append("';
$script.=  ' &' .$globalprefrow["currencysymbol"]. number_format(($priceexvat / ($numberitems)), 2, '.', '') .' ea. ';
$script.='");
';

}


} 




// if show checkboxes etc for selected service then update them

if (($ifcbbbuile>0) or ($ifcbbbuild>0)) { 



$query = "SELECT 
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
cbb20
FROM Orders 
WHERE `Orders`.`id` = :getid LIMIT 0,1";

$cbstmt = $dbh->prepare($query);
$cbstmt->bindParam(':getid', $id, PDO::PARAM_INT); 
$cbstmt->execute();
while ($row = $cbstmt->fetch(PDO::FETCH_ASSOC)) {
 $i=1;
while ( $i<21) {
  
$script.='$("#cbb'.$i.'").html("';
$script.=' &'.$globalprefrow["currencysymbol"]. number_format(($row["cbb$i"]), 2, '.', '');
$script.='");
';

	$i++;
  }
	}

} // ends check for cbbable







// add code to update screen of crow flies mileage













// add code to update co2 savings on screen
// span id="emissionsaving

$co2text='';


if ($co2saving) {
if ($co2saving>'1000') {  $co2text=  number_format(($co2saving/'1000'), 1).'Kg CO<sub>2</sub> ';
} else { $co2text=$co2saving.'g CO<sub>2</sub> '; }
 } elseif ($CO2Saved)  {
 $co=($CO2Saved*$numberitems);
if ($co>'1000') { $co=number_format(($co/'1000'), 1).'Kg '; } else { $co=$co.'g'; } 
$co2text= ''.$co .' CO<sub>2</sub> '; 
 }
 if ($pm10saving>'0.01')  { $co2text.= ' '. $pm10saving.'g PM<sub>10</sub>'; } else { 
 if ($PM10Saved<>'0.0') { $co2text.= ' '. ($PM10Saved*$numberitems).'g PM<sub>10</sub> '; } } 


 
$script.= ' $("#emissionsaving").html("'.$co2text.'"); ';
 
 
 
 




} // ends cojmaction == recalcprice




            if ($allok==1) {

$newformbirthday=$_SERVER["REQUEST_TIME_FLOAT"];


try {
$query = "UPDATE Orders SET tsmicro= :tsmicro WHERE id=:getid";

$updt = $dbh->prepare($query);
$updt->bindParam(':tsmicro', $_SERVER["REQUEST_TIME_FLOAT"], PDO::PARAM_STR); 
$updt->bindParam(':getid', $id, PDO::PARAM_INT); 
$updt->execute();

$infotext.='<br /> microts changed ';




// $script.=' orderformlistener(); ';



}



catch(PDOException $e) {
$message.= $e->getMessage(); 
}

} // ends all ok with changing order


        } // ends check for status < invoiced

    } // ends form birthday > last time order changed
} // ends has valid order id




// other non-order pages




if ($page=='ajaxremovegpscache') { // formbirthday does not matter


if (isset($_POST['trackingid'])) { $id = trim($_POST['trackingid']); }
if (isset($_POST['folder'])) { $folder = trim($_POST['folder']); }


// echo ' 3064 ID is '.$id;

 $testfile="cache/jstrack/".$folder.'/'.$id.'.js';
$infotext.= ' test file is  '.$testfile;
 if (file_exists($testfile)) {
unlink($testfile);  
$infotext.= '  found in cache, deleted.'; 
$message="File removed from site cache.<br/>Remember to clear YOUR browser cache as well!";
$allok='1';
}


else { 
$message="No cache file found.";
}




$newformbirthday=date("U");











}




// manager / system admin top level security check should go here in in release 2.1



if ($page=='ajaxeditglobals') {

// $message="Editing a Global <br />";
$settingsid=1;

$globalname=$_POST['globalname'];

$infotext.=' globalname was '.$globalname.' <br />';


$newvalue=$_POST['newvalue'];

// $message.=' newvalue was '.$newvalue.'<br />';

// decode from post
$newvalue= trim(base64_decode($newvalue));

// get a html friendly version for the message
$newvaluet = htmlspecialchars($newvalue);

$infotext.=' newvalue is '.$newvaluet. ' <br /> ';






if ($globalname=='clweb8') {
 try {
$query = "UPDATE globalprefs SET clweb8=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Theme Updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='numjobs') {
 try {
$query = "UPDATE globalprefs SET numjobs=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Num Jobs in Index List Updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='numjobsm') {
 try {
$query = "UPDATE globalprefs SET numjobsm=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Num Jobs in Mobile Index List Updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='courier2') {
 try {
$query = "UPDATE globalprefs SET courier2=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Number jobs on Rider home updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='courier3') {
 try {
$query = "UPDATE globalprefs SET courier3=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Rider Top menu selected colour set to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='courier4') {
 try {
$query = "UPDATE globalprefs SET courier4=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Rider Logo Location set to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='courier5') {
 try {
$query = "UPDATE globalprefs SET courier5=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Rider Logo Style set to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='courier6') {
 try {
$query = "UPDATE globalprefs SET courier6=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Rider COC or COD Style set to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='glob1') {
 try {
$query = "UPDATE globalprefs SET glob1=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Latitude updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}

if ($globalname=='glob2') {
 try {
$query = "UPDATE globalprefs SET glob2=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Longitude updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}

if ($globalname=='glob3') {
 try {
$query = "UPDATE globalprefs SET glob3=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Postcode Town updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}

if ($globalname=='glob4') {
 try {
$query = "UPDATE globalprefs SET glob4=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Postcode Locality updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='glob5') {
 try {
$query = "UPDATE globalprefs SET glob5=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Rider name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='glob6') { // unused former checkbox
 try {
$query = "UPDATE globalprefs SET glob6=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Index Alternate Display updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='glob7') { // show page load times
 try {
$query = "UPDATE globalprefs SET glob7=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Show Page Load Times updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='glob8') {
 try {
$query = "UPDATE globalprefs SET glob8=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Alert email address updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='glob9') {
 try {
$query = "UPDATE globalprefs SET glob9=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Master JS updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='glob10') {
 try {
$query = "UPDATE globalprefs SET glob10=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Master CSS updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='glob11') {
 try {
$query = "UPDATE globalprefs SET glob11=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Show Working Windows updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='unrider1') {
 try {
$query = "UPDATE Cyclist SET cojmname=:newvalue WHERE CyclistID='1' ";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Unallocated COJM name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='unrider2') {
 try {
$query = "UPDATE Cyclist SET poshname=:newvalue WHERE CyclistID='1' ";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Unallocated Public name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='adminlogo') {
 try {
$query = "UPDATE globalprefs SET adminlogo=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Admin Logo Relative updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='adminlogo') {
 try {
$query = "UPDATE globalprefs SET adminlogo=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Admin Logo Relative updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='adminlogoabs') {
 try {
$query = "UPDATE globalprefs SET adminlogoabs=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Admin Logo Absolute updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='adminlogowidth') {
 try {
$query = "UPDATE globalprefs SET adminlogowidth=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Admin Logo Width updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='adminlogoheight') {
 try {
$query = "UPDATE globalprefs SET adminlogoheight=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Admin Logo Height updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='highlightcolour') {
 try {
$query = "UPDATE globalprefs SET highlightcolour=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Highlight colour updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='highlightcolourno') {
 try {
$query = "UPDATE globalprefs SET highlightcolourno=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Highlight css updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='viewedicon') {
 try {
$query = "UPDATE globalprefs SET viewedicon=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Viewed icon updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='unviewedicon') {
 try {
$query = "UPDATE globalprefs SET unviewedicon=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Unviewed icon updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='image1') {
 try {
$query = "UPDATE globalprefs SET image1=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Awaiting Scheduling icon updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='image2') {
 try {
$query = "UPDATE globalprefs SET image2=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Awaiting Collection icon updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='image3') {
 try {
$query = "UPDATE globalprefs SET image3=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Awaiting Delivery icon updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='image4') {
 try {
$query = "UPDATE globalprefs SET image4=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Rider icon updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='image5') {
 try {
$query = "UPDATE globalprefs SET image5=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='ASAP icon updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='image6') {
 try {
$query = "UPDATE globalprefs SET image6=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Cargobike icon updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='sound1') {
 try {
$query = "UPDATE globalprefs SET sound1=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Annoying Sound updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='globalname') {
 try {
$query = "UPDATE globalprefs SET globalname=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Global Name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='globalshortname') {
 try {
$query = "UPDATE globalprefs SET globalshortname=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Global Short  Name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='myaddress1') {
 try {
$query = "UPDATE globalprefs SET myaddress1=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='My Address 1 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='myaddress2') {
 try {
$query = "UPDATE globalprefs SET myaddress2=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='My Address 2 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='myaddress3') {
 try {
$query = "UPDATE globalprefs SET myaddress3=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='My Address 3 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='myaddress4') {
 try {
$query = "UPDATE globalprefs SET myaddress4=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='My Address 4 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='myaddress5') {
 try {
$query = "UPDATE globalprefs SET myaddress5=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='My Address 5 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='clweb3') {
 try {
$query = "UPDATE globalprefs SET clweb3=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Map Dot updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='clweb4') {
 try {
$query = "UPDATE globalprefs SET clweb4=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Google Earth Line Style updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='clweb5') {
 try {
$query = "UPDATE globalprefs SET clweb5=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Initial Google Earth View updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn1') {
 try {
$query = "UPDATE globalprefs SET favusrn1=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 1 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn2') {
 try {
$query = "UPDATE globalprefs SET favusrn2=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 2 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn3') {
 try {
$query = "UPDATE globalprefs SET favusrn3=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 3 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn4') {
 try {
$query = "UPDATE globalprefs SET favusrn4=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 4 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn5') {
 try {
$query = "UPDATE globalprefs SET favusrn5=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 5 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn6') {
 try {
$query = "UPDATE globalprefs SET favusrn6=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 6 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn7') {
 try {
$query = "UPDATE globalprefs SET favusrn7=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 7 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn8') {
 try {
$query = "UPDATE globalprefs SET favusrn8=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 8 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn9') {
 try {
$query = "UPDATE globalprefs SET favusrn9=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 9 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn10') {
 try {
$query = "UPDATE globalprefs SET favusrn10=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 10 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn11') {
 try {
$query = "UPDATE globalprefs SET favusrn11=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 11 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn12') {
 try {
$query = "UPDATE globalprefs SET favusrn12=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 12 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn13') {
 try {
$query = "UPDATE globalprefs SET favusrn13=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 13 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn14') {
 try {
$query = "UPDATE globalprefs SET favusrn14=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 14 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn15') {
 try {
$query = "UPDATE globalprefs SET favusrn15=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 15 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn16') {
 try {
$query = "UPDATE globalprefs SET favusrn16=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 16 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn17') {
 try {
$query = "UPDATE globalprefs SET favusrn17=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 17 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn18') {
 try {
$query = "UPDATE globalprefs SET favusrn18=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 18 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn19') {
 try {
$query = "UPDATE globalprefs SET favusrn19=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 19 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='favusrn20') {
 try {
$query = "UPDATE globalprefs SET favusrn20=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Tag 20 updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='vatbanda') {
 try {
$query = "UPDATE globalprefs SET vatbanda=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='VAT Band A updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='vatbandb') {
 try {
$query = "UPDATE globalprefs SET vatbandb=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='VAT Band B updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='gexpc1') {
 try {
$query = "UPDATE globalprefs SET gexpc1=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Expense Type 1 Name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='gexpc2') {
 try {
$query = "UPDATE globalprefs SET gexpc2=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Expense Type 2 Name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='gexpc3') {
 try {
$query = "UPDATE globalprefs SET gexpc3=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Expense Type 3 Name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='gexpc4') {
 try {
$query = "UPDATE globalprefs SET gexpc4=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Expense Type 4 Name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='gexpc5') {
 try {
$query = "UPDATE globalprefs SET gexpc5=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Expense Type 5 Name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='gexpc6') {
 try {
$query = "UPDATE globalprefs SET gexpc6=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Expense Type 6 Name updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='courier9') {
 try {
$query = "UPDATE globalprefs SET courier9=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Text Before Rider Payments Summary updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='courier10') {
 try {
$query = "UPDATE globalprefs SET courier10=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Text After Rider Payments Summary updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}

if ($globalname=='formtimeout') {
 try {
$query = "UPDATE globalprefs SET formtimeout=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Form Timeout updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='showdebug') { // show debug
 try {
$query = "UPDATE globalprefs SET showdebug=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Show Debug updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='forcehttps') { // force https
 try {
$query = "UPDATE globalprefs SET forcehttps=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Force Secure updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='showsettingsmobile') { // showsettingsmobile
 try {
$query = "UPDATE globalprefs SET showsettingsmobile=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Show Settings on Mob Devices updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='showpostcomm') { // showpostcomm
 try {
$query = "UPDATE globalprefs SET showpostcomm=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Show Licensed Mail Options updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='inaccuratepostcode') { // inaccuratepostcode     1 is yes, eg Crete.   0 is no, eg GB
 try {
$query = "UPDATE globalprefs SET inaccuratepostcode=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Show Inacurate Postcode Setting updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='backupftpserver') { 
 try {
$query = "UPDATE globalprefs SET backupftpserver=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Backup Server updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='backupftpusername') { 
 try {
$query = "UPDATE globalprefs SET backupftpusername=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Backup FTP Username updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='co2perdist') {   
 try {
$query = "UPDATE globalprefs SET co2perdist=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='CO2 saving per mile or km updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='pm10perdist') {   
 try {
$query = "UPDATE globalprefs SET pm10perdist=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='PM10 saving per mile or km updated to '.$newvaluet.'<br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='waitingtimedelay') {   
 try {
$query = "UPDATE globalprefs SET waitingtimedelay=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Waiting Time Delay Prompt updated to '.$newvaluet.' mins. <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='googlemapapiv3key') {   
 try {
$query = "UPDATE globalprefs SET googlemapapiv3key=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Google Maps API v3 Key updated to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoicefooter') {   
 try {
$query = "UPDATE globalprefs SET invoicefooter=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Alternate Row Colour changed to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoicefooter2') {   
 try {
$query = "UPDATE globalprefs SET invoicefooter2=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Copy / Pasteable comments updated to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoicefooter3') {   
 try {
$query = "UPDATE globalprefs SET invoicefooter3=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Invoice footer pt1 changed to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoicefooter4') {   
 try {
$query = "UPDATE globalprefs SET invoicefooter4=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Invoice footer pt2 changed to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoicetotalcolour') {   
 try {
$query = "UPDATE globalprefs SET invoicetotalcolour=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Invoice Total Colour Cell changed to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoice1') {   
 try {
$query = "UPDATE globalprefs SET invoice1=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Title Font changed to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoice2') {   
 try {
$query = "UPDATE globalprefs SET invoice2=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Title Font size changed to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoice3') {   
 try {
$query = "UPDATE globalprefs SET invoice3=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Footer Font changed to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoice4') {   
 try {
$query = "UPDATE globalprefs SET invoice4=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Footer Font size changed to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoice5') {   
 try {
$query = "UPDATE globalprefs SET invoice5=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Body Font changed to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='invoice6') {   
 try {
$query = "UPDATE globalprefs SET invoice6=:newvalue WHERE settingsid=:settingsid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':settingsid', $settingsid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Body Font size changed to '.$newvaluet.' <br />';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }
}


if ($globalname=='cbbsettings') {



if ($_POST['checked']=='1') {  $newvalue=0; $newvaluet=' Checked '; }
$type=$_POST['testtype'];
$chargedbybuildid=$_POST['chargedbybuildid'];






$newformbirthday=microtime(TRUE);
$infotext.='Submitted id '.$chargedbybuildid.' <br /> val was '.$newvaluet.' <br /> type ' .$type. ' <br /> ';


if (($type=='cbbmod') or ($type=='cbbname') or ($type=='cbbcost') or ($type=='cbbcomment') or ($type=='cbbcargo') or 
($type=='cbbasap'))  try {
$query = "UPDATE chargedbybuild SET ".$type."=:newvalue WHERE chargedbybuildid=:chargedbybuildid";
$stmt = $dbh->prepare($query);
// $stmt->bindParam(':type', $type, PDO::PARAM_INT); 
$stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':chargedbybuildid', $chargedbybuildid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='Distance Price Settings updated. <br />';
}
catch(PDOException $e) { $message.= $e->getMessage(); }


if ($type=='newrow') try {
$query = "INSERT INTO chargedbybuild SET chargedbybuildid=:chargedbybuildid, cbborder=:chargedbybuildidn, cbbcost=0 ";
$stmt = $dbh->prepare($query);
// $stmt->bindParam(':type', $type, PDO::PARAM_INT); 
// $stmt->bindParam(':newvalue', $newvalue, PDO::PARAM_INT); 
$stmt->bindParam(':chargedbybuildid', $chargedbybuildid, PDO::PARAM_INT); 
$stmt->bindParam(':chargedbybuildidn', $chargedbybuildid, PDO::PARAM_INT); 

$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
if ($total=='1') {
$allok='1';
$newformbirthday=microtime(TRUE);
$message.='New Row Added to Distance Price Settings. <br />';
$script.=' idmax='.($chargedbybuildid+1).'; ';
}
}
catch(PDOException $e) { $message.= $e->getMessage(); }

} // ends cbb settings


if ($globalname=='cbborder') {

$refarray = explode(";",$newvalue); foreach ($refarray as $value) {

$arr = explode(",", $value, 2);
$rowid = $arr[0];
$cbborder = $arr[1];
 $infotext.='value '.$value.' '.$rowid.' '.$cbborder.'<br /> ';

if ($rowid>0) try {
$query = "UPDATE chargedbybuild SET cbborder=:newvalue WHERE chargedbybuildid=:chargedbybuildid";
$stmt = $dbh->prepare($query);
// $stmt->bindParam(':type', $type, PDO::PARAM_INT); 
$stmt->bindParam(':newvalue', $cbborder, PDO::PARAM_INT); 
$stmt->bindParam(':chargedbybuildid', $rowid, PDO::PARAM_INT); 
$stmt->execute();
$total = $stmt->rowCount();
$infotext.=$total.' row updated <br />';
$allok='1';
$newformbirthday=microtime(TRUE);
}
catch(PDOException $e) { $message.= $e->getMessage(); }

} // ends loop through and get each row

$message.='Checkbox Order updated. <br />';

} // ends globalname=='cbborder












} // ends page=ajaxeditglobals













// ENDS MAIN CHANGEJOB  ADDS AUDIT LOG + OUTPUTS SCRIPT

// echo date(c).'<br />';
// echo $_SERVER["REQUEST_TIME_FLOAT"].'<br />';
// echo  microtime(TRUE);

$agent = $_SERVER['HTTP_USER_AGENT']; 

if(preg_match('/iPhone|Android|Blackberry/i', $agent)) {
// $infotext.='<br />Mobile device'; 
$mobdevice='1';
} else { $mobdevice=''; }


$referrer=$_SERVER["HTTP_REFERER"]; $refarray = explode("/",$referrer); foreach ($refarray as $value) { $referrer = $value; }
	
    $cj_msec = (microtime(TRUE)- $_SERVER["REQUEST_TIME_FLOAT"]) * 1000.0;
    $cj_echo = number_format($cj_msec, 1);
	

	
if (isset($_SERVER["PHP_AUTH_USER"])) { $audituser=$_SERVER["PHP_AUTH_USER"]; }

 else if (isset($_SERVER["REMOTE_USER"])) { if (!$audituser) { $audituser=$_SERVER["REMOTE_USER"]; } }
	
	

	

try {
$statement = $dbh->prepare("INSERT INTO cojm_audit 
(auditorderid,audituser,auditpage,auditfilename,auditmobdevice,auditbrowser,audittext,auditcjtime,auditinfotext,auditdatetime) 
values 
(:orderid, :audituser, :page, :referrer, :auditmobdevice, :auditbrowser, :audittext, :auditcjtime, :auditinfotext, now())
");

$statement->bindParam(':orderid', $id, PDO::PARAM_STR);
$statement->bindParam(':audituser', $audituser, PDO::PARAM_STR);
$statement->bindParam(':page', $page, PDO::PARAM_STR);
$statement->bindParam(':referrer', $referrer, PDO::PARAM_STR);
$statement->bindParam(':auditmobdevice', $mobdevice, PDO::PARAM_STR);
$statement->bindParam(':auditbrowser', $agent, PDO::PARAM_STR);
$statement->bindParam(':audittext', $message, PDO::PARAM_STR);
$statement->bindParam(':auditcjtime', $cj_echo, PDO::PARAM_STR);
$statement->bindParam(':auditinfotext', $infotext, PDO::PARAM_STR);

$statement->execute();
}

catch(PDOException $e) { 

$allok=0;
$message.=" Issue saving Audit Log <br /> ";
$message.=$e->getMessage();

}

// show page time according to setting	
 if ($globalprefrow['glob7']=='1') {  
$message.="ACJ in $cj_echo ms. ";
 }

if ($globalprefrow['showdebug']=='1') {
$message.="<br />DEBUG MODE --------------- <br />$infotext";

}

echo '
<script>
'.$script.'
var allok='.$allok.';
var formbirthday='.$newformbirthday.';
var message='.json_encode($message).';
</script>
';



// echo phpinfo();
$dbh=null;

?>