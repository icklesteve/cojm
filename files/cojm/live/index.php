<?php 
/*
    COJM Courier Online Operations Management
	index.php - main job schedule
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
</head>
<body id="bodytop" >';

$hasforms='1';
$filename="index.php";
include "cojmmenu.php"; 

echo '<div id="Post" class="Post c9 lh16">';


$totsumtot = $dbh->query("SELECT count(1) FROM Orders WHERE `Orders`.`status` <70 ")->fetchColumn();

if($mobdevice) { $numberofresults=$globalprefrow['numjobsm']; } else {$numberofresults=$globalprefrow['numjobs']; } 
if ($page == "showall" ) { $numberofresults='1000'; }


$cbbasapdata = $dbh->query('SELECT chargedbybuildid from chargedbybuild WHERE cbbasap = "1" and cbbcost <> 0 ')->fetchAll(PDO::FETCH_COLUMN);
    
$cbbcargodata = $dbh->query('SELECT chargedbybuildid from chargedbybuild WHERE cbbcargo = "1" and cbbcost <> 0 ')->fetchAll(PDO::FETCH_COLUMN);
    

$query = "SELECT statusname, status FROM status WHERE activestatus=1 AND status<101 ORDER BY status ASC";   
$statusdata = $dbh->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);

$query = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 
$riderdata = $dbh->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);
    
    
$query = "SELECT bankholcomment, bankholdate FROM bankhols WHERE bankholdate >= ( CURRENT_DATE - interval 1 week ) ";
$bankholdata = $dbh->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);


$seeifnextday='';
$javascript='';
$dayflag='0';
$tempfirstrun='';



$query='
SELECT
p.ID,
p.nextactiondate,
p.jobcomments,
p.privatejobcomments,
p.targetcollectiondate,
p.collectionworkingwindow,
p.duedate,
p.deliveryworkingwindow,
p.cbb1,
p.cbb2,
p.cbb3,
p.cbb4,
p.cbb5,
p.cbb6,
p.cbb7,
p.cbb8,
p.cbb9,
p.cbb10,
p.cbb11,
p.cbb12,
p.cbb13,
p.cbb14,
p.cbb15,
p.cbb16,
p.cbb17,
p.cbb18,
p.cbb19,
p.cbb20,
p.CustomerID,
p.orderdep,
p.numberitems,
p.CollectPC,
p.fromfreeaddress,
p.enrpc1,
p.enrpc2,
p.enrpc3,
p.enrpc4,
p.enrpc5,
p.enrpc6,
p.enrpc7,
p.enrpc8,
p.enrpc9,
p.enrpc10,
p.enrpc11,
p.enrpc12,
p.enrpc13,
p.enrpc14,
p.enrpc15,
p.enrpc16,
p.enrpc17,
p.enrpc18,
p.enrpc19,
p.enrpc20,
p.enrft1,
p.enrft2, 
p.enrft3, 
p.enrft4, 
p.enrft5, 
p.enrft6, 
p.enrft7, 
p.enrft8, 
p.enrft9, 
p.enrft10, 
p.enrft11, 
p.enrft12, 
p.enrft13, 
p.enrft14, 
p.enrft15, 
p.enrft16, 
p.enrft17, 
p.enrft18, 
p.enrft19, 
p.enrft20, 
p.status, 
p.CyclistID, 
p.collectiondate, 
p.FreightCharge, 
p.vatcharge,
p.ShipPC,
p.tofreeaddress,
t.asapservice,
t.cargoservice,
u.CompanyName,
t.batchdropcount,
t.Service,
u.invoicetype,
y.opsname,
y.descrip,
z.opsname AS `subareaname`,
z.descrip AS `subareadescrip`,
l.depname

FROM Orders p
INNER JOIN Clients u ON p.CustomerID = u.CustomerID
INNER JOIN Services t ON p.ServiceID = t.ServiceID
left join clientdep l ON p.orderdep = l.depnumber
left join opsmap y ON p.opsmaparea = y.opsmapid
left join opsmap z on p.opsmapsubarea = z.opsmapid
WHERE `p`.`status` <70 
ORDER BY `p`.`nextactiondate` , ID
LIMIT :numberofresults';

$stmt = $dbh->prepare($query);


$pdonumberofresults = NULL;
// $numberofresults

$stmt->bindParam(':numberofresults', ($pdonumberofresults), PDO::PARAM_INT);

$pdonumberofresults = $numberofresults;

$stmt->execute();
$sumtot = $stmt->rowCount();


while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $seeifnextnewday=date('z', strtotime($row['nextactiondate']));
    if (($seeifnextday<>$seeifnextnewday) and ($seeifnextday<>'')) {
        echo '<div class="linevpad"> </div>';
        $dayflag++;
    }
    if ( $dayflag & '1' ) {  //odd , sets $dayclass
        $dayclass= 'ui-state-default';
    } else {
        $dayclass= 'ui-state-highlight';
    }
    
    

    $showasap='';
    $showcargo='';
    $shortcomments = (substr($row['jobcomments'],0,40));
    $privateshortcomments = (substr($row['privatejobcomments'],0,40));
	
    $latecoll = (time()-(strtotime($row['targetcollectiondate']))); // high if collection running late
    if (date('U', strtotime($row['collectionworkingwindow']))>10) {    
    $latecoll = (time()-(strtotime($row['collectionworkingwindow']))); // high if collection running late
    }
    
    $latedeli = (time()-(strtotime($row['duedate']))); // high if delivery running late
    if (date('U', strtotime($row['deliveryworkingwindow']))>10) {
    $latedeli = (time()-(strtotime($row['deliveryworkingwindow']))); // high if delivery running late    
    }

    $javascript.="$('#stat". $row['ID']."').change(function() { $('#".$row['ID']."').submit(); });
    $('#cyc".$row['ID']."').change(function() { $('#".$row['ID']."').submit(); }); ";

    $i=1;
    $showcargo=''; 
    $showasap='';

    echo '<div class="ui-corner-all '.$dayclass.'"  > ';


    while ($i<21) { /////  CHECK TO SEE TO DISPLAY JUST 1 ASAP OR CARGOBIKE LOGO     //
        if (($row["cbb$i"])<>0) { // order cbb has price 
            if (in_array("$i", $cbbasapdata)) {
                $showasap='1';
            }
            
            if (in_array("$i", $cbbcargodata)) {
                $showcargo='1';
            }
        }
        $i++;
    }

    if ($row['asapservice']=='1') { $showasap='1'; }
    if ($row['cargoservice']=='1') { $showcargo='1'; }


    echo ' <span class="indexorder"><a class="indexjoblink" href="order.php?id='. $row['ID'].'">'. $row['ID'].'</a> ';

    
    if ($showasap=='1') {
        echo '<img class="indexicon" title="ASAP" alt="ASAP" src="'.$globalprefrow['image5'].'">';
    }
        
    if ($showcargo=='1') {
        echo '<img class="indexicon" title="Cargobike " alt="Cargo" src="'.$globalprefrow['image6'].'">';
    }
    
    
    ////////////     COMMENT TEXT ///////////////////////////////////////////////////////////////


    echo ' <a href="new_cojm_client.php?clientid='.$row['CustomerID'].'">'.$row['CompanyName'].'</a>';

    if ($row['orderdep']>0) { echo ' (<a href="new_cojm_department.php?depid='.$row['orderdep'].'">'.$row['depname'].'</a>) '; }
    
    
    if ($row['batchdropcount']<>'1') {
        echo ' '.trim(strrev(ltrim(strrev($row['numberitems']), '0')),'.').' x '. $row['Service'] .' ';
    }
    
    $n=0;
    $i=1;
    while ($i<21) { // get number of via addresses
        if (((trim($row["enrpc$i"]))<>'') or (trim($row["enrft$i"]<>''))) { $n++; } $i++; }
    
    if ($n==1) { // via 1 stop
        echo ' via '.htmlspecialchars($row["enrft1"]) .' '.htmlspecialchars($row["enrpc1"]).'';
    }
    else if ($n>1) { // via n stops
        echo ' via '.$n.' stops ';
    }
    
    if (($shortcomments)) {
        echo ' - '.$shortcomments;
    }
    
    if ($privateshortcomments) {
        echo ' - '.$privateshortcomments;
    }

    
    echo '
    </span>
    
    <table class="index">
    <tbody> <tr>';
    
    if ($mobdevice<>'1') { // td rowspan=2
        echo ' <td rowspan="2"> ';
    }
    else { // td colspan=3
        echo ' <td colspan="3"> ';
    }
    


    /////////////      STATUS SELECT   //////////////////////////////////////
    echo '<form action="#" method="post" id="'. $row['ID'] .'" autocomplete="off">
        <input type="hidden" name="formbirthday" value="'. date("U") .'">
        <input type="hidden" name="oldstatus" value="'. $row['status'].'">
        <input type="hidden" name="page" value="editstatus"><input type="hidden" name="id" value="'. $row['ID'] .'">';
    
    echo '<select id="stat'. $row['ID'] .'" class="'.$dayclass.' ui-corner-left" name="newstatus" >';
    
    foreach($statusdata as $loopstatusname => $loopstatusnum) {
        print ("<option ");
        if ($row['status'] == $loopstatusnum) { echo 'selected="selected" ';}
        print ("value=\"$loopstatusnum\">$loopstatusname</option>"); 
    }
    print ("</select>");



    
    //////////     CYCLIST   DROPDOWN     ///////////////////////////////////
    echo '<input type="hidden" name="oldcyclist" value="'.$row['CyclistID'].'" >
    <select name="newcyclist" id="cyc'. $row['ID'] .'" class="'.$dayclass.' ui-corner-left';

    if ($row['CyclistID']=='1') { echo ' red'; }
    echo '">';

    foreach ($riderdata as $ridernum => $ridername) {
        $ridername=htmlspecialchars($ridername);
        print ("<option ");
        if ($row['CyclistID'] == $ridernum) { echo ' selected="selected" '; }
        if ($ridernum == '1') { echo ' class="unalo" '; }
        print ("value=\"$ridernum\">$ridername</option>");
    }
    echo '</select></form> ';
    
    
    if ($mobdevice=='1') {
        echo ' </td></tr><tr><td> ';
    } else {
        echo ' </td> <td> ';
    }
    
    
    if (($row['status']) <'51' ) {
        if ($latecoll>0) { echo '<span class="red">';}
        echo 'Target PU';
        if ($latecoll>0) { echo '</span>'; }
        
        echo '</td>
        <td>';
    
        if ($latecoll>0) { echo ' <span class="red" >'; }

        echo date('H:i', strtotime($row['targetcollectiondate']));
        if (date('U', strtotime($row['collectionworkingwindow']))>10) {
            echo '-'.date('H:i ', strtotime($row['collectionworkingwindow']));
        }
    
        $today=date('z');
        $check=date('z', strtotime($row['targetcollectiondate']));
        $month=date('M', strtotime($row['targetcollectiondate']));
        $cmont=date('M');
        $year=date('Y', strtotime($row['targetcollectiondate']));
        $cyea=date('Y');
        if ($check==($today-'1')) {
            echo ' Yesterday ';
        } else
        if ($check==$today) {
            echo ' Today ';
        }
        else if ($check==($today+'1')) {
            echo ' Tomorrow ';
        }
        else {
            echo date(' l ', strtotime($row['targetcollectiondate']));
        }
        
        echo date(' jS ', strtotime($row['targetcollectiondate']));
        if (($month<>$cmont) or ($year<>$cyea)) {
            echo date(' M ', strtotime($row['targetcollectiondate']));
        }
        if ($year<>$cyea) {
            echo date(' Y ', strtotime($row['targetcollectiondate']));
        }
        if ($latecoll>0) { echo '</span> '; }
    }
    else { // ends collection due , now  collected
        echo ' PU was at 
        </td>
        <td> '. date('H:i', strtotime($row['collectiondate']));
        $today=date('z');
        $check=date('z', strtotime($row['collectiondate'])); 
        $month=date('M', strtotime($row['collectiondate']));
        $cmont=date('M');
        $year=date('Y', strtotime($row['collectiondate']));
        $cyea=date('Y');
        if ($check==($today-'1')) {
            echo ' Yesterday ';
        }
        else if ($check==$today) {
            echo ' Today ';
        }
        else if ($check==($today+'1')) {
            echo ' Tomorrow ';
        }
        else {
            echo date(' l ', strtotime($row['collectiondate']));
        } 
        echo date(' jS ', strtotime($row['collectiondate']));
        if (($month<>$cmont) or ($year<>$cyea)) {
            echo date(' M ', strtotime($row['collectiondate']));
        }
        if ($year<>$cyea) {
            echo date(' Y ', strtotime($row['collectiondate']));
        }
        
    } // ends collection due / collected
    
    
    
    
    $testdate = substr($row['targetcollectiondate'],0,10);
    $bhtext=(array_search("$testdate", $bankholdata));
    if ($bhtext) { echo '<span class="bankhol">'.$bhtext.'</span>'; }
    

    echo ' </td> <td> ';
    




    
    if ((trim($row['CollectPC'])) or ($row['fromfreeaddress'])) {
        if ( $globalprefrow["inaccuratepostcode"]=='1') { // echo ' full address link '; 
            echo ' <a class="newwin" target="_blank" href="https://www.google.com/maps/?q='.
            str_replace(" ", "+", trim($row['fromfreeaddress'])).'+'.str_replace(" ", "+", trim($row['CollectPC'])) .'">'.$row["fromfreeaddress"].' '.trim($row['CollectPC']).'</a> ';
        }
        else { // uk style address link
            echo $row['fromfreeaddress'].' ';
            if (trim($row['CollectPC'])) {
                echo '<a target="_blank" class="newwin" href="https://www.google.com/maps/?q='.
                str_replace(" ", "+", trim($row['CollectPC'])).'">'.trim($row['CollectPC']) .'</a> ';
            }
        }
    } // ends check to see if needs to display links and From
    
    
    //// CASH PAYMENT CHECK    ////////////////////////////////////////////////////
    if ($row['invoicetype']=='3') {
        echo " <span title='Incl. VAT' style='". $globalprefrow['courier6']."'>Payment on PU &". 
        $globalprefrow['currencysymbol'].($row['FreightCharge']+$row['vatcharge']).'</span>';
    }
    
    
    echo ' &nbsp; 
    </td>
    
    
    
    
    
    
    </tr>
    
    <tr>
    
    
    
    
    
    
    <td> 
    ';
    
    
    
    if ($latedeli>0) { echo ' <span class="red" >'; }
    
    echo 'Target Drop';
    
    if ($latedeli>0) { echo '</span> '; }
    
    echo '
    </td>
    
    
    
    
    <td>
    ';
    
    
    
    // due date / time   ///////////////////////////////////////////////
    if ($latedeli>0) { echo ' <span class="red" >'; }
    echo date('H:i', strtotime($row['duedate'])); 
    
    if (date('U', strtotime($row['deliveryworkingwindow']))>10) {
        echo '-'.date('H:i', strtotime($row['deliveryworkingwindow']));
    }
    
    
    $today=date('z');
    $check=date('z', strtotime($row['duedate']));
    $month=date('M', strtotime($row['duedate']));
    $cmont=date('M');
    $year=date('Y', strtotime($row['duedate']));
    $cyea=date('Y');

    if ($check==($today-'1')) { echo ' Yesterday '; }
    else if ($check==$today) { echo ' Today '; }
    else if ($check==($today+'1')) { echo ' Tomorrow '; }
    else { echo date(' l ', strtotime($row['duedate'])); }
    
    echo date(' jS ', strtotime($row['duedate']));
    
    if (($month<>$cmont) or ($year<>$cyea)) {
        echo date(' M ', strtotime($row['duedate']));
    }
    if ($year<>$cyea) {
        echo date(' Y ', strtotime($row['duedate']));
    }
    if ($latedeli>0) { echo '</span> '; }
    
    
    
    
    
    $testdate = substr($row['duedate'],0,10);
    $bhtext=(array_search("$testdate", $bankholdata));
    if ($bhtext) { echo '<span class="bankhol">'.$bhtext.'</span>'; }
    
    echo '
    </td>
    <td>
    ';
    
    
    
    if ($row['opsname']<>'') {  echo '<span title="'.$row['descrip'].'">'.$row['opsname'].'</span> '; }
    if ($row['subareaname']<>'') {  echo ' <span title="'.$row['subareadescrip'].'">('.$row['subareaname'].')</span> '; }
    
    if ((trim($row['ShipPC'])) or ($row['tofreeaddress'])) {
        if ( $globalprefrow["inaccuratepostcode"]=='1'){
            echo '<a target="_blank" class="newwin" href="https://www.google.com/maps/?q='.
            str_replace(" ", "+", trim($row["tofreeaddress"])).'+'. str_replace(" ", "+", trim($row['ShipPC'])).'">'.$row['tofreeaddress'].' '. $ShipPC.'</a> ';
        }
        else { // uk address
            echo $row['tofreeaddress'];
            if (trim($row['ShipPC'])) {
                echo ' <a target="_blank" class="newwin" href="https://www.google.com/maps/?q=';
                echo str_replace(" ", "+", trim($row['ShipPC'])).'">'. trim($row['ShipPC']).'</a> ';
            }
        }
    }

    
    
    
    if ($row['invoicetype']=='4') { // echo payment on drop
        echo " <span style='". $globalprefrow['courier6']."'>Payment on Drop   &". 
        $globalprefrow['currencysymbol'].($row['FreightCharge']+$row['vatcharge']).'</span>';
    }
    
    
    echo ' &nbsp; 
    </td> 
    </tr> 
    </tbody>
    </table> 
    </div>
    ';
    
    $seeifnextday=date('z', strtotime($row['nextactiondate']));
    

} // ends individual job loop





if ($totsumtot=='0') { // show no jobs message
    echo '<div class="linevpad "></div><div class="ui-state-highlight ui-corner-all p15" > 
    <p><strong> No future or active jobs found on system.</strong>.</p></div><div class="vpad "></div>';
}


if ($page=="showall") { // show number of jobs displayed
    echo '<div class="linevpad "></div><div class="ui-state-highlight ui-corner-all p15" > 
    <p>	<strong> All '.$sumtot.' jobs displayed</strong>.</p></div><div class="vpad "></div>';
}
 
 
if ($totsumtot>$sumtot) { // show all jobs button
    echo '<div class="linevpad "></div><div class="ui-state-highlight ui-corner-all p15" > 
    <form action="" method="get">
    <input type="hidden" name="page" value="showall" />
    <p><strong> Next '.$numberofresults.' jobs displayed out of a total of '.$totsumtot.'.
    <button type="submit" >Show all jobs</button>
    </strong>
    </p>
    </form>
    </div>';
}
  
echo '<div class="linevpad"></div>
</div>

  <script type="text/javascript">
  $(document).ready(function() {
  '.$javascript. '
  });
  </script> ';
  
include "footer.php";
  
echo ' </body></html>';
mysql_close();
$dbh=null;
