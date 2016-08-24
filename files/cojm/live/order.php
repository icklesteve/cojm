<?php
/*
    COJM Courier Online Operations Management
	order.php - Update single job
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
// if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";

if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$hasforms='1';
$bottomhtml='';
$favcomments='';
$pcrow1["PZ_easting"]='';
$trackingtext='';
$collecttime ='';
$startpause='';
$finishpause='';
$delivertime ='';
$dateshift='';
$areaid='';
$topdescrip='';
$subareacomments='';


include "changejob.php";


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


$query="SELECT * FROM Orders, Clients, Services, status, Cyclist
WHERE Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID 
AND Orders.status = status.status 
AND Orders.CyclistID = Cyclist.CyclistID
AND Orders.ID = '$id' LIMIT 1"; $result=mysql_query($query, $conn_id); $row=mysql_fetch_array($result);


$cojmid=$id;
?><!doctype html><html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height" >
<meta name="generator" content="COJM www.cojm.co.uk">
<title><?php echo $id; ?> COJM</title>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<link id="pagestyle" rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<link rel="stylesheet" href="css/themes/<?php echo $globalprefrow['clweb8']; ?>/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>
<?php 
if ($row['ID']) {
    if (($row['isdepartments']=='1')) {
        $orderdep=$row['orderdep']; $depquery="SELECT * FROM clientdep WHERE depnumber = '$orderdep' LIMIT 1";
        $result=mysql_query($depquery); $drow=mysql_fetch_array($result);
    }

    
    
    $query = "SELECT * FROM cojm_pod WHERE id = :getid LIMIT 0,1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $row['publictrackingref'], PDO::PARAM_INT); 
    $stmt->execute();
    $haspod = $stmt->rowCount();
    
    
    
    
    
    
    
    $formbirthday=microtime(TRUE);

    
    
    $CollectPC=trim($row['CollectPC']);
    $ShipPC=trim($row['ShipPC']);
    $prShipPC= str_replace(" ", "+", "$ShipPC");
    $prCollectPC= str_replace(" ", "+", "$CollectPC");
    $linkfafrom=trim($row['fromfreeaddress']);
    $linkfafrom= str_replace(" ", "+", "$linkfafrom");

    

    ?>
<script>
var id='<?php echo $row['ID']; ?>';
var publictrackingref='<?php echo $row['publictrackingref']; ?>';
var allok=1;
var statustoohigh='Unable to edit, status too high';
var formbirthday=<?php echo $formbirthday; ?>; 
var oldclientorder=<?php echo $row['CustomerID']; ?>;
var chargedbybuild='<?php echo $row['chargedbybuild']; ?>';
var initialrequestor='<?php echo $row['requestor']; ?>';
var haspod=<?php echo $haspod; ?>;
var initialdeporder=<?php echo $row['orderdep']; ?>;
var initialstatus=<?php echo $row['status']; ?>;
var initialclientjobreference='<?php echo $row['clientjobreference']; ?>';
var initialtargetcollectiondate="<?php if (date('U', strtotime($row['targetcollectiondate']))>10) { 
echo date('d/m/Y H:i', strtotime($row['targetcollectiondate'])); } ?>";
var initialcollectionworkingwindow="<?php if (date('U', strtotime($row['collectionworkingwindow']))>10) { 
echo date('d/m/Y H:i', strtotime($row['collectionworkingwindow'])); } ?>";
var initialstarttravelcollectiontime="<?php if (date('U', strtotime($row['starttravelcollectiontime']))>10) { 
echo date('d/m/Y H:i', strtotime($row['starttravelcollectiontime'])); } ?>";
var initialwaitingstarttime="<?php if (date('U', strtotime($row['waitingstarttime']))>10) { 
echo date('d/m/Y H:i', strtotime($row['waitingstarttime'])); } ?>";
var initialcollectiondate="<?php if (date('U', strtotime($row['collectiondate']))>10) { 
echo date('d/m/Y H:i', strtotime($row['collectiondate'])); } ?>";
var initialstarttrackpause="<?php if (date('U', strtotime($row['starttrackpause']))>10) { 
echo date('d/m/Y H:i', strtotime($row['starttrackpause'])); } ?>";
var initialfinishtrackpause="<?php if (date('U', strtotime($row['finishtrackpause']))>10) { 
echo date('d/m/Y H:i', strtotime($row['finishtrackpause'])); } ?>";
var initialduedate="<?php if (date('U', strtotime($row['duedate']))>10) { 
echo date('d/m/Y H:i', strtotime($row['duedate'])); } ?>";
var initialdeliveryworkingwindow="<?php if (date('U', strtotime($row['deliveryworkingwindow']))>10) { 
echo date('d/m/Y H:i', strtotime($row['deliveryworkingwindow'])); } ?>";
var initialShipDate="<?php if (date('U', strtotime($row['ShipDate']))>10) { 
echo date('d/m/Y H:i', strtotime($row['ShipDate'])); } ?>";
var initialjobrequestedtime="<?php if (date('U', strtotime($row['jobrequestedtime']))>10) { 
echo date('d/m/Y H:i', strtotime($row['jobrequestedtime'])); } ?>";
var waitingmins=<?php echo $row['waitingmins']; ?>;
var podsurname="<?php echo $row['podsurname']; ?>";
var waitingtimedelay=<?php echo $globalprefrow['waitingtimedelay']; ?>;
var initialjobcomments<?php if ($row["jobcomments"]) { echo '=1'; } ?>;
var initialprivatejobcomments<?php if ($row["privatejobcomments"]) { echo '=1'; } ?>;
</script>

<script src="//maps.googleapis.com/maps/api/js?v=3.22&amp;key=<?php echo $globalprefrow['googlemapapiv3key']; ?>" type="text/javascript"></script>
<script src="js/order.js" type="text/javascript"></script>
<script src="js/richmarker.js" type="text/javascript"></script>
<style>
/* starts spinner on page load, only for ajax pages  */
#toploader { display:inline; }
</style>

<?php
} // ends check for valid job ID
?>
</head><body >
<?php

$filename='order.php'; 
include "cojmmenu.php"; 

if ($row['ID']) {

    echo '<div id="Post" class="Post lh24">';

    if ($row['status']<'100') { // get rid of this when fully ajaxed :-)
    echo '<form action="order.php#" method="post" accept-charset="utf-8" id="allorder" novalidate>
        <input type="hidden" name="formbirthday" form="allorder" value="'. date("U").'">
        <input type="hidden" name="id" id="id" form="allorder" value="'.$row['ID'].'">
        <input type="hidden" name="page" form="allorder" value="edituidate"></form>';
    }


    echo '
    <div class="hangleft">
    <div class="ui-corner-all ui-state-highlight addresses" >
    <div class="fs">
    <div class="fsl">';


    if ((trim($CollectPC)<>'') or (trim($row['fromfreeaddress'])<>'')) {


        if ($globalprefrow["inaccuratepostcode"]=='1') {
            $fromfreeaddresslink=trim($row["fromfreeaddress"]);
            $fromfreeaddresslink= str_replace(" ", "+", "$fromfreeaddresslink");
            $tofreeaddresslink=trim($row["tofreeaddress"]);
            $tofreeaddresslink= str_replace(" ", "+", "$tofreeaddresslink");
            echo ' <a title="View in Maps" class="newwin" target="_blank" href="https://www.google.co.uk/maps/?q='. 
            $fromfreeaddresslink.'+'.$prCollectPC .'">From</a> ';
        } 
        else { // accurate postcode
            if ($prCollectPC) {
                echo ' <a title="View in Maps" target="_blank" class="newwin" href="https://www.google.co.uk/maps/?q='. 
                $prCollectPC .'">From</a> ';
            }
        }
    } // ends check for pc or freetext



    if ((trim($row['fromfreeaddress'])) or (trim($row['CollectPC']))) {
        $sql = "SELECT * FROM cojm_favadr WHERE 
                favadrft = '".$row["fromfreeaddress"]."' 
                AND favadrclient= '".$row['CustomerID']."'
                AND favadrpc= '".$row['CollectPC']."'
                AND favadrisactive='1' 
                LIMIT 1";
        $sql_result = mysql_query($sql,$conn_id) or mysql_error(); 
        $favadrrow=mysql_fetch_array($sql_result);
        if ($favadrrow['favadrft']==$row["fromfreeaddress"]) { // echo ' found '; 
            $favf=$favadrrow['favadrid'];
            $favcomments=$favadrrow['favadrcomments'];
        } 
        else {
            $newfavf='1';
            echo' <input class="favadr" type="submit" form="newfavcollect" value="Add to Favourites"  title="Add to Favourites" />';
        } // ends found / not found
    } // ends check for address to check

 

    if ($row['status']<'100') {
        $activefavs='1'; 
    } 
    else {
        $activefavs='0';
    }

    if ($activefavs=='1') {
        echo '<button class="chngfav" title="Change Address" id="jschangfavfr"> &nbsp;  </button>'; 
    }
    echo '</div> <input form="allorder" class="allorder caps ui-state-default ui-corner-left freetext" ';
    echo 'id="fromfreeaddress" type="text" placeholder="From . . ." name="fromfreeaddress" value="'.
        $row["fromfreeaddress"].'" /><input ';
    if (($globalprefrow['inaccuratepostcode'])=='0') {
        if (((!$pcrow1["PZ_easting"]) and (trim($row["fromfreeaddress"]))) or (($sumtot=='0') and (trim($row['CollectPC']))) or ((trim($ShipPC) and (!trim($CollectPC)))))  {
            echo ' style="'.$globalprefrow['highlightcolourno'].'"';
        }
    }
    echo ' size="9" form="allorder" placeholder="Postcode" class="allorder caps ui-state-default ui-corner-right"';
    echo ' name="CollectPC" type="text" maxlength="9" value="'.trim($row["CollectPC"]).'">';

    if ($row['status']<'100') { // check to see if postcode on database
        if (($globalprefrow['inaccuratepostcode'])=='0') {
            $pcCollectPC= str_replace(" ", "", "$CollectPC", $count);
            if (trim($row['CollectPC'])) {
                $sql = 'SELECT PZ_northing FROM  `postcodeuk` WHERE  `PZ_Postcode` LIKE  "'.$pcCollectPC.'" LIMIT 0 , 1';  
                $result = mysql_query($sql, $conn_id);
                $sumtot=mysql_affected_rows();
                if ($sumtot>'0'){ 
                } 
                else {
                    echo ' <a href="newpc.php?selectpc='.trim($row['CollectPC']).'&amp;id='.$row['ID'].'">Add PC</a>';
                }
            }
        }
    }

    $ntot=0;
    $n=1;
    while ($n<21) {
        $prenPC=trim($row["enrpc$n"]);
        $prenFT=trim($row["enrft$n"]);
        if (($prenPC) or ($prenFT)) {
            $ntot=$ntot+1;
        }
        $n++;
    }

    if (($row['status']<'100') or ($ntot>0)) {

    if ($ntot==0) {
        echo " <span id='togglenr1choose' ><a href='#'>Add via</a></span>";
        }
    }

    echo '</div> ';



    if ($favcomments) {
        echo ' <div class="favcomments fsr"> '.$favcomments.' </div> ';
        }
    $favcomments='';

    if (($row['status']<'100') or ($ntot>0)) { ///  starts via loops
        if ($ntot==0) {
            echo '<div id="togglenr1" >'; 
        }
        else {
            echo '<div>';
        }
        
        $i='1';
        while ($i<'21') {
            echo ' <div class="fs"><div class="fsl" > &nbsp; ';  
            $prenPC=trim($row["enrpc$i"]); 
            $prenPC=str_replace(" ", "+", "$prenPC", $count);
            $prenFT=trim($row["enrft$i"]);
            $prenFT=str_replace(" ", "+", "$prenFT", $count);
            if (($prenPC) or ($prenFT)) {
                if ((($globalprefrow['inaccuratepostcode'])=='0') and ($prenPC)) {
                    echo '<a title="View in Maps" class="newwin" target="_blank" href="https://www.google.co.uk/maps/?q='.
                    $prenPC.'">via</a> ';
                }
                
                if ((($globalprefrow['inaccuratepostcode'])=='1') and (($prenFT) or ($prenPC))) {
                    echo '<a title="View in Maps" class="newwin" target="_blank" href="https://www.google.co.uk/maps/?q='.
                    $prenFT.'%20'.$prenPC.'">via</a> ';
                }
            }
            
            $favcomments='';
            if ((trim($row["enrft$i"])) or (trim($row["enrpc$i"]))) {
                $clientorder=$row['CustomerID'];
                $fromfreeaddress=$row["enrft$i"];
                $collectpc=$row["enrpc$i"];
                $sql = "SELECT * FROM cojm_favadr 
                WHERE favadrft = '$fromfreeaddress' 
                AND favadrclient= '$clientorder' 
                AND favadrpc= '$collectpc'
                AND favadrisactive='1'
                LIMIT 1"; 
                $sql_result = mysql_query($sql,$conn_id) or mysql_error();
                $favadrrow=mysql_fetch_array($sql_result);
                if ($favadrrow['favadrft']==$fromfreeaddress) {
                    $favcomments=$favadrrow['favadrcomments'];
                }
                else {
                    echo'<input class="favadr" type="submit" form="newfavenr'.$i.
                    '" value="Add to Favourites"  title="Add to Favourites" >';


                $bottomhtml.= '<form action="#" id="newfavenr'.$i.'" method="post" >
                <input type="hidden" name="page" value="editnewfav">
                <input type="hidden" name="formbirthday" value="'. date("U").'">
                <input type="hidden" name="clientorder" value="'.$row['CustomerID'].'">
                <input type="hidden" name="id" value="'.$row['ID'].'">
                <input type="hidden" name="fromfreeaddress" value="'.$row["enrft$i"].'" />
                <input type="hidden" name="CollectPC" value="'.$row["enrpc$i"].'" /> </form> ';
                } // ends found / not found
            } // ends check for address to check

            if ($activefavs=='1') {
                echo '<button class="chngfav" title="Change Address" id="jschangfavvia'.$i.'"> &nbsp;  </button>';
                }
            echo ' </div>
            <input placeholder="via . . ." type="text" form="allorder" class="allorder caps ui-state-default ui-corner-left freetext" name="enrft'.$i.'" value="'.$row["enrft$i"].'">
            <input size="9" form="allorder" ';

            if (($globalprefrow['inaccuratepostcode'])=='0') {
                if (((!trim($prenPC)) and (trim($row["enrft$i"]))) or (($sumtot=='0') and (trim($prenPC))))  {
                    echo ' style="'.$globalprefrow['highlightcolourno'].'"';
                }
            }
            echo ' class="allorder caps ui-state-default ui-corner-right" placeholder="Postcode" name="enrpc'.
            $i.'" type="text" id="TextBox'.$i.'" value="'.$row["enrpc$i"].'">';
            if (($globalprefrow['inaccuratepostcode'])=='0') { ///  check to see if postcode on database //

                $pcprenPC=trim($row["enrpc$i"]); 
                $pcprenPC= str_replace(" ", "", "$pcprenPC", $count);
                if (trim($pcprenPC)) {
                    $sql = 'SELECT PZ_northing FROM  `postcodeuk` 
                    WHERE  `PZ_Postcode` LIKE  "'.$pcprenPC.'"
                    LIMIT 0 , 1';
                    $result = mysql_query($sql, $conn_id);
                    $sumtot=mysql_affected_rows();
                    if ($sumtot>'0'){
                    }
                    else {
                        echo ' <a href="newpc.php?selectpc='.trim($row["enrpc$i"]).'&amp;id='.$row['ID'].'">Add Postcode</a>';
                    }
                }
            } // ends check to see if postcode

            if ($ntot>0) {
                $ntot--;
                }
    
            // echo ' ntotis '.$ntot;
    
            if ($n<'1') {
                $n='0';
            }
    
            if ($i=='1') {
                if (($ntot<1) and ($row['status']<'100')) {
                    echo ' <span id="togglenr2choose"><a href="#">Add more vias</a></span>';
                }
                echo'</div>'; 
    
                if ($favcomments) {
                    echo '<div class="favcomments fsr">'. $favcomments .'</div>';
                }
                $favcomments='';
    
                if ($ntot<1) {
                    echo '<div id="togglenr2">';
                } else {
                        echo '<div>';
                }
            }
            else if ($i=='5') {
                if (($ntot<1) and ($row['status']<'100')) {
                    echo ' <span id="togglenr3choose"><a href="#">Add more vias</a></span>';
                }
                echo'</div>'; 
                if ($favcomments) {
                    echo '<div class="favcomments fsr">'.$favcomments.'</div>';
                }
                $favcomments='';
                
                if ($ntot<1) {
                    echo '<div id="togglenr3">';
                }
                else {
                    echo '<div>';
                }
            }
            else if ($i=='10') {
                if (($ntot<1) and ($row['status']<'100')) {
                    echo ' <span id="togglenr4choose"><a href="#">Add more vias</a></span>';
                }
                echo'</div>'; 
                if ($favcomments) {
                    echo '<div class="favcomments fsr">'. $favcomments .'</div>';
                }
                $favcomments='';
                if ($ntot<'1') {
                    echo '<div id="togglenr4">';
                }
                else {
                    echo '<div>';
                }
            }
            else if ($i=='15') {
                if (($ntot<1) and ($row['status']<'100')) {
                    echo ' <span id="togglenr5choose"><a href="#">Add more vias</a></span>';
                }
                echo'</div>'; 
                if ($favcomments) {
                    echo '<div class="favcomments fsr">'.$favcomments.'</div>';
                }
                $favcomments='';
                
                if ($ntot<1) {
                    echo '<div id="togglenr5">';
                }
                else {
                    echo '<div>';
                }
            }
            else {
                echo '</div>'; 
                if ($favcomments) {
                    echo '<div class="favcomments fsr">'. $favcomments .'</div>';
                }
                $favcomments='';
            }
            
            $i=$i+'1';
        } // less than 21 loop
    
        echo '</div></div></div></div></div>'; // ends via loops
    
    
    } // ends favourites or job status < 100


    /// starts to address
    $ShipPC=trim($ShipPC);
    $prShipPC= str_replace(" ", "+", "$ShipPC", $count);
    $prfreea=trim($row["tofreeaddress"]);
    $prfreea= str_replace(" ", "+", "$prfreea", $count);

    echo '<div class="fs"><div class="fsl"> ';
    if (($ShipPC) or ($prfreea)) { 
        if ((($globalprefrow['inaccuratepostcode'])=='0') and ($ShipPC)) {
            echo '<a title="View in Maps" class="newwin" target="_blank" href="https://www.google.co.uk/maps/?q='.
            $prShipPC.'">To</a>';
        }
        if ((($globalprefrow['inaccuratepostcode'])=='1') and (($ShipPC) or ($prfreea))) {
            echo '<a title="View in Maps" class="newwin" target="_blank" href="https://www.google.co.uk/maps/?q='.
            $prfreea.'%20'.$prShipPC.'">To</a> ';
        }
    }

    $favcomments='';
    if ((trim($row['tofreeaddress'])) or (trim($row['ShipPC']))) {
        $clientorder=$row['CustomerID'];
        $fromfreeaddress=$row["tofreeaddress"];
        $collectpc=$row['ShipPC'];
        $sql = "SELECT * FROM cojm_favadr WHERE 
        favadrft = '$fromfreeaddress' 
        AND favadrclient= '$clientorder'
        AND favadrpc= '$collectpc'
        AND favadrisactive='1' 
        LIMIT 1"; 
        $sql_result = mysql_query($sql,$conn_id)  or mysql_error(); 
        $favadrrow=mysql_fetch_array($sql_result);
        if ($favadrrow['favadrft']==$fromfreeaddress) { // echo ' found '; 
            $favcomments=$favadrrow['favadrcomments'];
        }
        else {
            $newfavt='1';
            echo' <input class="favadr" type="submit" form="newfavto" value="Add to Favourites"  title="Add / Edit Favourite" />';
        } // ends found / not found
    } // ends check for address to check

    if ($activefavs=='1') {
        echo ' <button class="chngfav" title="Change Address" id="jschangfavto"> &nbsp;  </button> ';
    }
    
    echo ' </div>
    <input type="text" form="allorder" class="allorder caps ui-state-default ui-corner-left freetext" name="tofreeaddress" placeholder="To . . ." value="'.$row["tofreeaddress"].'"><input placeholder="Postcode" size="9" form="allorder" ';
    if (($globalprefrow['inaccuratepostcode'])=='0') {
        if (((!trim($ShipPC)) and (trim($row["tofreeaddress"]))) or (($sumtot=='0') and (trim($ShipPC))) or ((!trim($ShipPC) and (trim($CollectPC))))) {
            echo 'style="'.$globalprefrow['highlightcolourno'].'"';
        }
    }
    echo ' class="allorder caps ui-state-default ui-corner-right" name="ShipPC" type="text" id="TextBox21" value="'.
    trim($row["ShipPC"]).'"> ';
    
    if (($globalprefrow['inaccuratepostcode'])=='0') { // check to see if postcode on database //
        $pcprenPC= str_replace(" ", "", "$ShipPC", $count);
        if (trim($pcprenPC)) {
            $sql = 'SELECT PZ_northing FROM  `postcodeuk` WHERE  `PZ_Postcode` LIKE  "'.$pcprenPC.'" LIMIT 0 , 1';
            $result = mysql_query($sql, $conn_id);
            $sumtot=mysql_affected_rows();
            if ($sumtot>'0'){
            } 
            else {
                echo ' <a href="newpc.php?selectpc='.trim($ShipPC).'&amp;id='.$row['ID'].'">Add Postcode</a> ';
            }
        }
    }


    if ( $globalprefrow["inaccuratepostcode"]=='0') {
        if ($row['distance']>'0') {
            echo $row['distance'].' '. $globalprefrow['distanceunit'];
        }
    }
    
    echo '</div>';
    
    if ($favcomments) {
        echo '<div class="favcomments fsr">'.$favcomments.'</div>';
    }
    $favcomments='';
    
    if ( $globalprefrow["inaccuratepostcode"]=='1') {
        echo ' <input form="allorder" type="text" class="caps ui-state-default ui-corner-all" name="distance" size="4" value="'.
        $row['distance']. '" maxlength="5" />' . $globalprefrow['distanceunit'];
    }
    
    echo ' <div class="clrfix"> </div> ';
    
    
    if ($row['status']<'100') {
        echo ' <button class="right" form="allorder" type="submit" > Edit Addresses </button><hr />';
    }
 


    // starts area selectors
    echo '<div id="areaselectors" class="hideuntilneeded">';
    $showsubarea='0';
    if ($row['opsmaparea']) {
        $opsmaparea=$row['opsmaparea'];
        $checkifarchivearea=mysql_result(mysql_query("SELECT inarchive FROM opsmap WHERE opsmapid=$opsmaparea LIMIT 1 ", $conn_id), 0);
        // echo ' aa:'.$checkifarchivearea;
    }
    
    if ($checkifarchivearea=='1') {
        $topareaquery = "SELECT opsmapid, opsname, descrip, istoplayer FROM opsmap WHERE type=2 AND corelayer='0' "; 
    }
    else {
        $topareaquery = "SELECT opsmapid, opsname, descrip, istoplayer FROM opsmap WHERE type=2 AND inarchive<>1 AND corelayer='0' "; 
    }

    $topareaqueryres = mysql_query ($topareaquery, $conn_id);
    echo '<div class="fs"><div class="fsl"> </div> 
    <select id="opsmaparea" name="opsmaparea" class="ui-state-default ui-corner-left">
    <option value="" > Choose Area </option>';
    
    while (list ($listopsmapid, $listopsname, $descrip, $istoplayer ) = mysql_fetch_row ($topareaqueryres)) {
        print ("<option ");
        if ($row['opsmaparea'] == $listopsmapid) {
            echo ' selected="selected" ';
            $showsubarea=$istoplayer;
            $topname=$listopsname;
            $topdescrip=$descrip;
        } 

        echo 'value="'.$listopsmapid.'" >' .$listopsname;
        if ($istoplayer=='1') { 
            echo ' ++ ';
        }
        echo '</option>';
    }
    echo '</select>
    <a id="arealink" class="showclient marright10 hideuntilneeded" title="Area Details" target="_blank" href="opsmap-new-area.php?areaid='.$row['opsmaparea'].'"> </a>';


    $btmareaquery = "SELECT opsmapid, opsname, descrip  FROM opsmap WHERE type=2 AND inarchive<>1 AND corelayer='".$row['opsmaparea']."' "; 
    $btmareaqueryres = mysql_query ($btmareaquery, $conn_id);
    
    echo ' <select id="opsmapsubarea" name="opsmapsubarea" class="ui-state-default ui-corner-left hideuntilneeded">
    <option value="" > Choose Sub Area </option>';
    while (list ($listopsmapid, $listopsname, $descrip ) = mysql_fetch_row ($btmareaqueryres)) {
        print ("<option "); 
        if ($row['opsmapsubarea'] == $listopsmapid) {
            echo ' selected="selected" '; 
            // $btmdescrip=$descrip;
            $subareaname=$listopsname;
            $subareacomments=$descrip;
        } 
        echo 'value="'.$listopsmapid.'" >' .$listopsname.' '.$descrip;
        echo '</option>';
    }

    echo '</select>
    <a id="subarealink" class="showclient hideuntilneeded" title="Sub Area Details" target="_blank" href="opsmap-new-area.php?areaid='.$row['opsmapsubarea'].'"> </a>
    </div>
    <div id="areacomments" class="favcomments fsr';
    
    
    
    if (($topdescrip) or ($subareacomments)) { } else {
        echo ' hideuntilneeded';
    }
    
    
    
    echo '"> '.$topdescrip.'
    <span id="subareacomments"> ';
    if ($subareacomments) {
        echo ' ('.$subareacomments.') ';
    }
    echo '</span> 
    </div>
    </div>';
    ///////     ends area selector


    echo ' </div>'; // ends distance container






    echo '<div class="ui-corner-all ui-state-highlight addresses">'; // select rider
    echo '<div class="fs"><div class="fsl"> '.$globalprefrow['glob5'].'</div>';
    if ($row['isactive']=='1') {
        $cyclistquery = "SELECT CyclistID, cojmname, trackerid, isactive FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 
    } else {	
        $cyclistquery = "SELECT CyclistID, cojmname, trackerid, isactive FROM Cyclist ORDER BY CyclistID"; 
    }


    $cyclistresult_id = mysql_query ($cyclistquery, $conn_id); 
    echo '<input form="allorder" type="hidden" name="oldcyclist" value="'.$row['CyclistID'].'" >';

    echo '<select id="newrider" name="newcyclist" class="ui-state-default ui-corner-left ';

    if ($row['CyclistID']=='1') { 
        echo ' red ';
    }

    echo ' ">';

    while (list ($CyclistID, $cojmname, $trackerid, $isactive) = mysql_fetch_row ($cyclistresult_id)) {
        print ("<option ");
        if ($row['CyclistID'] == $CyclistID) {
            echo ' selected="selected" ';
            $thistrackerid=$trackerid;
        }
        echo 'value="'.$CyclistID.'" ';
        
        if (($CyclistID=='1') or ($isactive<>'1')) {
            echo ' class="unalo" ';
        }
        echo '>'.$cojmname;

        if ($isactive<>'1') {
            echo ' Inactive ';
        }
        
        echo '</option>';
    }
    print ("</select>");
    echo '<a id="showriderlink" class="showclient';
    if ($row['CyclistID']=='1') {
        echo ' hidden';
    }
    
    echo '" title="'.$row['cojmname'].' Details" target="_blank" href="cyclist.php?thiscyclist='.$row['CyclistID'].'"> </a>';
    if ($row['isactive']<>'1') {
        echo ' Inactive ';
    }

    echo '</div>'; // finishes select rider
    echo '</div>'; // finishes container



    

    echo '<div class="ui-corner-all ui-state-highlight addresses">';   // STATUS /// + times container
    if ($row['status']<'101') {
        echo '<div class="fs"><div class="fsl"></div> ';
        $query = "SELECT statusname, status FROM status WHERE activestatus=1 AND status<101 ORDER BY status";
        $result_id = mysql_query ($query, $conn_id);
        print (" <select id=\"newstatus\" name=\"newstatus\" class=\"ui-state-default ui-corner-left\" >\n");
        while (list ($statusname, $status) = mysql_fetch_row ($result_id)) {
            $status = htmlspecialchars ($status);
            $statusname = htmlspecialchars ($statusname);
            print ("<option ");
            if ($row['status'] == $status) {
                echo " SELECTED ";
            }
            echo 'value="'.$status.'">'.$statusname.'</option>';
        }
        print (" </select>");
        echo '</div>';
    }   /////////////    ENDS STATUS          //////////// 




    echo '<div class="fs"><div class="fsl"> Target PU </div> 
        <input type="text" class="ui-state-default ui-corner-all caps dpinput" name="targetcollectiondate" id="targetcollectiondate" value="';
    if ($row['targetcollectiondate']>'10') {
        echo date('d/m/Y H:i', strtotime($row['targetcollectiondate']));
    }
    echo '" /> ';


    if ($globalprefrow['glob11']=='1') {

        echo '<button class="hideuntilneeded" id="allowww" >Add Slot</button> 
        <span class="hideuntilneeded" id="allowwwuntil"> until </span>
        <input type="text" class="caps ui-state-default ui-corner-all dpinput hideuntilneeded" name="collectionworkingwindow" id="collectionworkingwindow" value="';
        if ($row['collectionworkingwindow']>'10') {
            echo date('d/m/Y H:i', strtotime($row['collectionworkingwindow']));
        } 
        echo '" />';
    }
    
    echo ' <span id="collectiontext"></span> ';
    echo '</div>';

    
    echo '<div id="starttravelcollectiontimediv" class="fs"><div class="fsl">En route PU</div> '; 
    echo '<input type="text" class="caps ui-state-default ui-corner-all dpinput" name="starttravelcollectiontime" id="starttravelcollectiontime" value="';
    if ($row['starttravelcollectiontime']>'10') { 
        echo date('d/m/Y H:i', strtotime($row['starttravelcollectiontime']));
    }
    echo '" /> </div>';

    echo '<div id="waitingstarttimediv" class="fs hideuntilneeded"><div class="fsl">On site PU </div> ';  	
    echo '<input type="text" class="caps ui-state-default ui-corner-all dpinput" name="waitingstarttime" '; 
    echo 'id="waitingstarttime" value="'; 
    if ($row['waitingstarttime']>'10') { 
        echo date('d/m/Y H:i', strtotime($row['waitingstarttime']));
    }
    echo '" /> </div> ';


    echo '
    <div id="collectiondatediv" class="fs orderhighlight hideuntilneeded">
    <div class="fsl">PU </div> 
    <input type="text" class="caps ui-state-default ui-corner-all dpinput" name="collectiondate" id="collectiondate" 
    value="'; 
    if ($row['collectiondate']>'10') {
        echo date('d/m/Y H:i', strtotime($row['collectiondate']));
    }
    echo '" />


    <button id="toggleresumechoose" class="toggleresumechoose" title="Add Pause / Resume"> &nbsp; </button> 
    <span id="collectiondatetext"></span> 
    </div>  
    <div id="toggleresume" class="toggleresume fs" >
    <div class="fsl">
    <span class="toggleresumechoose" title="Pause / Resume"> &nbsp; </span>
    Paused
    </div>
    <input type="text" class="caps ui-state-default ui-corner-all dpinput" name="starttrackpause" id="starttrackpause" value="';
    
    if ($row['starttrackpause']>'10') { 
        echo date('d/m/Y H:i', strtotime($row['starttrackpause']));
    }
    
    echo '" />
    Restarted : 
    <input type="text" class="caps ui-state-default ui-corner-all dpinput" name="finishtrackpause" id="finishtrackpause" value="';
    if (date('U', strtotime($row['finishtrackpause']))>10) {
        echo date('d/m/Y H:i', strtotime($row['finishtrackpause']));
    }
    echo '" /> 
    </div> '; // ends pause / resume container
 
 
 
 
 
 
 
    echo ' <div class="fs"><div class="fsl">Target Drop </div> ';
    echo '<input type="text" class="caps ui-state-default ui-corner-all dpinput" name="duedate" ';
    echo 'id="duedate" value="'; 
    if ($row['duedate']>'10') { 
        echo date('d/m/Y H:i', strtotime($row['duedate']));
    }
    echo '" />';


    if ($globalprefrow['glob11']=='1') { // ends check for ww
        echo ' <button class="hideuntilneeded" id="allowdww" > Add Slot </button> 
        <span class="hideuntilneeded" id="untildww"> until </span> 
        <input type="text" class="caps ui-state-default ui-corner-all dpinput hideuntilneeded" 
        name="deliveryworkingwindow" id="deliveryworkingwindow" value="';
        if (date('U', strtotime($row['deliveryworkingwindow']))>10) {
            echo date('d/m/Y H:i', strtotime($row['deliveryworkingwindow']));
        }
        echo '" />';
    }

    echo ' <span id="deliverytext"></span> </div> 
    <div id="ShipDatediv" class="orderhighlight fs hideuntilneeded">
    <div class="fsl" >  ';
    
    $docalc = mysql_result(mysql_query("SELECT statusname from status WHERE `status`.`status`='100' LIMIT 1", $conn_id), 0);
    echo $docalc. ' </div> ';
    
    echo '<input type="text" class="caps ui-state-default ui-corner-all dpinput" name="ShipDate" id="ShipDate" value="'; 
    if ($row['ShipDate']>'10') {
        echo date('d/m/Y H:i', strtotime($row['ShipDate']));
    } 
    echo '" /> 
    <span id="ShipDatetext">'.time2str($row['ShipDate']).'</span> 
    <span id="totaltime"></span>
    </div> ';




    echo ' <div class="fs"><div class="fsl">Requested </div> ';
    echo ' <input type="text" class="caps ui-state-default ui-corner-all dpinput" name="jobrequestedtime" ';
    echo 'id="jobrequestedtime" value="';
    if ($row['jobrequestedtime']>'10') { 
        echo date('d/m/Y H:i', strtotime($row['jobrequestedtime']));
    }
    echo '" />
    </div>
    </div>';  // ends time container


    
    
    
    
    echo '
    <div class="ui-corner-all ui-state-highlight addresses"> 
    <div class="fs"><div class="fsl">
    <input id="numberitems" class="ui-state-default ui-corner-left pad numberitems" '; ?>data-autosize-input='{ "space": 6 }' <?php
    echo ' name="numberitems" ';
    
    $numberitems= trim(strrev(ltrim(strrev($row['numberitems']), '0')),'.');
    echo ' value="'. $numberitems. '" > x </div>'; 

    
    
    


    ////////////   SERVICE           ////////////////
    if ($row['activeservice']=='1') {
        $query = "
        SELECT ServiceID,
        Service 
        FROM Services 
        WHERE activeservice='1' 
        ORDER BY serviceorder DESC, ServiceID ASC"; 
        $result_id = mysql_query ($query, $conn_id); 
        print ("<select id =\"serviceid\" class=\"ui-state-default ui-corner-left\" name=\"serviceid\" >"); 
        while (list ($ServiceID, $Service) = mysql_fetch_row ($result_id)) {
            $ServiceID = htmlspecialchars ($ServiceID);	
            $Service = htmlspecialchars ($Service);
            print ("<option "); 
                if ($row['ServiceID'] == $ServiceID) {
                    echo " SELECTED ";
                    $selectedservicename=$Service;
                }
            print ("value=\"$ServiceID\">$Service</option>");
        }
        print ("</select>"); 
    }
    else {
        echo $row['Service']. ' INACTIVE SERVICE ';
    }

    echo '</div>
    <div id="servicecomments" class="favcomments fsr';
    if ($row['servicecomments']=='') {
        echo ' hideuntilneeded';
    }

    echo '">'. $row['servicecomments'].'</div>';
    ///    ENDS SERVICE   ////////////
    
    
    echo '<div id="jobcommentsdiv" class="fs">
    <div class="fsl">Instructions</div>
    <textarea id="jobcomments" class="normal caps ui-state-highlight ui-corner-all orderjobcomments" name="jobcomments" >'.
    $row['jobcomments'].'</textarea>
    </div>
    <div id="privatejobcommentsdiv" class="fs">
    <div class="fsl">Priv Note</div>
    <textarea id="privatejobcomments" class="normal caps ui-state-highlight ui-corner-all orderjobcomments" name="privatejobcomments">'.
    $row['privatejobcomments'].'</textarea></div>
    </div>';



    ///////////               pod stuff


    echo '

    <div id="podcontainer" class="ui-corner-all ui-state-highlight addresses';

    if (($row['status']<'99') or (($row['status']>'99') and (($haspod==1) or ($row["podsurname"]<>'')))) {
    }
    else {
        echo ' hideuntilneeded';
    }
    echo '">
    <div id="podsurnamecontainer" class="fs"><div class="fsl">POD </div>
    <input type="text" id="podsurname" class="caps ui-state-default ui-corner-all" name="podsurname" size="25" maxlength="40" value="'.$row["podsurname"].'">


    <input type="file"  form="uploadpodform" name="file" id="uploadpodfile" accept="image/png, image/gif, image/jpeg" />
    <div class="fsr hideuntilneeded" id="uploadpodprogress" ><progress></progress></div>
    </div>
    <div id="podimagecontainer" class="fsr hideuntilneeded"> 
    <span id="ajaxremovepod" title="Remove POD" > &nbsp; </span>
    <img id="orderpod" class="orderpod" alt="POD" ';

    if ($haspod>0) {
        echo 'src="../podimage.php?id='.$row['publictrackingref'].'" ';
    }


    echo ' >
    </div>
    </div>';
    
    echo ' </div> '; // ends div floatleft
    
    echo ' <div class="hangright">';
    echo '<div class="ui-corner-all ui-state-highlight addresses">';



    /////////////       CHARGED BY BUILD / CHECK SETTINGS             ////////////////////////////////
    $query = "
    SELECT 
    chargedbybuildid, 
    cbbname, 
    cbbcost 
    FROM chargedbybuild 
    WHERE cbbcost <> '0.00'
    ORDER BY cbborder"; 
    $result_id = mysql_query ($query, $conn_id); 
    $i='1';

    echo ' <table id="cbb" class="ord hideuntilneeded" ><tbody> ';
    echo ' <tr id="baseservicecbb" class="hideuntilneeded" ><td id="baseservicecbbtext"> ';
    echo ' '.$numberitems.' x '.$selectedservicename.' </td><td> &'.$globalprefrow["currencysymbol"].'
    <span id="baseservicecbbprice">'. ( $numberitems * $row["Price"]) .'</span></td> 

    <td colspan="2"> </td>

    </tr> ';


    while (list ($chargedbybuildid, $cbbname, $cbbcost) = mysql_fetch_row ($result_id)) {
        $cbbname = htmlspecialchars ($cbbname);
        if ($chargedbybuildid=='1') {
            if ($i%2) {
                echo ' <tr ';
                if ($i=='1') {
                    echo ' id="mileagerow" ';
                }
                echo ' > ';
            }
            echo'<td> <label><input type="checkbox" name="cbbc'. $chargedbybuildid .'" value="1" class="cbbcheckbox"';
            if ($row["cbbc$chargedbybuildid"]<>'0.00') {
                echo ' checked ';
            }
            echo'> '. $cbbname. '</label></td>
            <td id="cbb'.$chargedbybuildid.'"> &'.$globalprefrow["currencysymbol"]. $row["cbb$chargedbybuildid"]. ' </td> ';
            if ($i%2) {
            }
            else {
                echo ' </tr>  ';
            }
        } // ends buildid=1
        
        if ($chargedbybuildid=='2') {
            if ($i%2) {
                echo ' <tr> ';
            }
            echo ' <td> ';

            echo ' <label><input type="checkbox" name="cbbc'.$chargedbybuildid.'" value="1" class="cbbcheckbox" '; 
            if ($row["cbbc$chargedbybuildid"]<>'0.00') { 
                echo ' checked ';
            } 
            echo'> '.$cbbname.'</label></td>
            <td id="cbb'.$chargedbybuildid.'"> &'.$globalprefrow["currencysymbol"].$row["cbb$chargedbybuildid"]. ' </td> ';
            if ($i%2) {
            }
            else {
                echo ' </tr> ';
            }
        }

        if ($chargedbybuildid=='3') {
            if ($i%2) {
                echo '<tr>';
            }
            echo '<td> <select class="ui-state-default ui-corner-left wspeca cbbcheckbox" name="waitingmins" id="waitingmins" > '; 
            $waitmin='0';
            while ( $waitmin<100 ) {
                echo '<option'; 
                if ($waitmin==$row['waitingmins']) {
                    echo ' SELECTED';
                }
                echo ' value="'.$waitmin.'">'.$waitmin.'</option>';
                $waitmin=$waitmin+'5';
            }
            echo '</select>';
            echo' mins waiting time </td><td id="cbb'.$chargedbybuildid.'"> 
            &'.$globalprefrow["currencysymbol"].$row["cbb$chargedbybuildid"].'</td>';
            if ($i%2) {
            } else {
                echo '</tr>';
            }
        }
        
        if ($chargedbybuildid>'3' ) {
            if ($i%2) { 
                echo ' <tr> ';
            }

            echo '<td>
            <label><input type="checkbox" name="cbbc'.$chargedbybuildid.'" value="1" class="cbbcheckbox" '; 
            if ($row["cbbc$chargedbybuildid"]<>'0') {
                echo ' checked ';
            }
            echo '> '.$cbbname.'</label>
            </td>
            <td id="cbb'.$chargedbybuildid.'">&'.$globalprefrow["currencysymbol"].$row["cbb$chargedbybuildid"].'</td>';


            if ($i%2) {
            }
            else {
                echo ' </tr> ';
            }
        }

        $i=$i+'1';

    } // ends loop for cbb loop

    if ($i%2) {
        echo '<tr><td colspan="4"> ';
    }
    else {
        echo ' <td> ';
    }

    echo '</td>
    <td> </td>
    </tr>';

    echo '</tbody></table>';
    /////////////////////////////////// ends cbb job




    echo '<div class="fs" ><div class="fsl">
    <span id="pricerowleft">';
    if ($row['vatcharge']<>'0.00') {
    echo ' &'. $globalprefrow["currencysymbol"] . number_format (($row['vatcharge']+$row['FreightCharge']), 2, '.', ','). ' Tot ';
    }
    echo '</span>';

    echo ' &'. $globalprefrow["currencysymbol"] . '</div>
    
    <input id="newcost" type="text" title="excl. VAT" '; ?>data-autosize-input='{ "space": 6 }' <?php 
    echo 'class="ui-state-default ui-corner-all caps" name="newcost" value="'.$row["FreightCharge"].'">
    
    <button id="buttoncancelpricelock" class="cancelpricelock hideuntilneeded" title="Cancel Pricelock">&nbsp;</button>
    <span id="pricerow">';
    if ($row['FreightCharge']<>'0.00') {
        if ($row['vatcharge']<>'0.00') {
            echo ' + &';
            $tempvatcost= number_format($row['vatcharge'], 2, '.', ',');
            echo $globalprefrow["currencysymbol"] . $tempvatcost . ' VAT ';
        }
        else {
            echo ' No VAT ';
        }
    } // ends check for main charge <>0.00



    if ($row['clientdiscount']<>'0.00') {
        echo ' Discount : '. $row["cbbdiscount"].'% (&'. $globalprefrow["currencysymbol"].
        number_format($row['clientdiscount'], 2, '.', '').') ';
    }
    
    if ($row['invoicetype']=='3') {
        echo " <span style='". $globalprefrow['courier6']."'> Payment on PU </span>";
    }
    
    if ($row['invoicetype']=='4') {
        echo " <span style='". $globalprefrow['courier6']."'> Payment on Drop </span>";
    } 

    if ($row['numberitems'] > '1') {
        if ($row['numberitems'] > '49') {
            echo ' &' .$globalprefrow["currencysymbol"]. 
            number_format(($row["FreightCharge"] / ($row['numberitems']/'1000')), 2, '.', '') .' / k ';
        }
        else {
            echo ' avg &' .$globalprefrow["currencysymbol"].
            number_format(($row["FreightCharge"] / ($row['numberitems'])), 2, '.', '') .' ea. ';
        }
    }
    
    echo '</span>
    <span id="orderinvoice">';
    if ($row['status']>'100') { // show invoice link
        echo $row['statusname'].'<a href="view_all_invoices.php?viewtype=individualinvoice&amp;formbirthday='. date("U").
        '&amp;clientid='.$row['CustomerID'].'&amp;ref='.$row['invoiceref'].'">'.$row['invoiceref'].'</a> ';
    }
    
    echo '</span>
    </div>
    </div>';
    
    echo ' <div class="ui-corner-all ui-state-highlight addresses">
    <div id="client" class="fs"> <div class="fsl">
    <a id="clientlink" class="showclient" title="'.$row['CompanyName'].
    ' Details" target="_blank" href="new_cojm_client.php?clientid='.$row['CustomerID'].'"> </a>
    </div>';
    
    if ($row['isactiveclient'] =='1' ) { // SQL for Client still in active
        $query = "SELECT CustomerID, CompanyName, isactiveclient FROM Clients WHERE isactiveclient>0 ORDER BY CompanyName";
    }
    else {
        $query = "SELECT CustomerID, CompanyName, isactiveclient FROM Clients ORDER BY CompanyName";
    }

    $result_id = mysql_query ($query, $conn_id); 
	echo '<select class="ui-state-default ui-corner-all" id="combobox" name="clientorder" ><option value="">Select..</option>';
    while (list ($CustomerIDlist, $CompanyName, $isactive) = mysql_fetch_row ($result_id)) {
        $CustomerID = htmlspecialchars ($CustomerID);
        $CompanyName = htmlspecialchars ($CompanyName);
        print "<option ";
        if ($CustomerIDlist == $row['CustomerID']) {
            echo "selected='SELECTED' ";
        }
        if ($isactive <>'1' ) {
            echo ' class="unalo" ';
        }
        
        echo ' value="'.$CustomerIDlist.'">'.$CompanyName;
        if ($isactive <>'1' ) {
            echo ' INACTIVE ';
        }
        echo '</option>';
    }
    echo '</select> ';
    if ($row['isactiveclient'] <>'1' ) { echo ' INACTIVE '; }
    
    
    echo '</div>';
    echo ' <div id="clientNotes" class="fsr favcomments';
    if ($row['Notes']=='') {
        echo ' hideuntilneeded';
    }

    echo '" > '. $row['Notes'].' </div> ';
    echo '  <div id="clientdep" class="fs hideuntilneeded">
    <div class="fsl">
    <a id="clientdeplink" class="showclient';
    if ($row['orderdep']<'1') {
        echo ' hideuntilneeded';
    }
    echo '" title="'.$drow['depname'].' Details" 
    target="_blank" href="new_cojm_department.php?depid='.$row['orderdep'].'"> </a>
    </div>';
    $query = "SELECT depnumber, depname , isactivedep FROM clientdep 
    WHERE associatedclient = '".$row['CustomerID']."' ORDER BY isactivedep DESC, depname";
    $result_id = mysql_query ($query, $conn_id) or mysql_error();
    $sumtot=mysql_affected_rows(); // echo $sumtot.' Department(s) : ';
    echo '<select class="ui-state-default ui-corner-left" id="orderselectdep" >
    <option value="0" >No Department</option>';
    while (list ($CustomerIDlist, $CompanyName, $isactivedep ) = mysql_fetch_row ($result_id)) {
        $CustomerID = htmlspecialchars ($CustomerID);
        $CompanyName = htmlspecialchars($CompanyName);
        print'<option ';
        if ($CustomerIDlist==$row['orderdep']) {
            echo ' SELECTED ';
        }
        echo 'value="'.$CustomerIDlist.'">'.$CompanyName;
        if ($isactivedep<>'1') {
            echo ' Inactive ';
        }
        echo '</option>';
    }
    echo '</select> ';
    
    echo '</div>
    <div id="clientdepnotes" class="fsr favcomments">';
    if (isset($drow['depcomment'])) {
        if (trim($drow['depcomment'])) {
            echo $drow['depcomment'];
        }
    }
    echo '</div>';
    
    echo '<div class="fs" id="requestordiv"><div class="fsl">Requestor</div>
    <input id="requestor" type="text" class="caps ui-state-default ui-corner-all" name="requestor" size="28" value="'. 
    $row['requestor'].'" /></div> ';
    
    echo '<div id="clientjobreferencediv" class="fs"><div class="fsl">Clients Ref </div>
    <input id="clientjobreference" type="text" title="Client Reference" class="caps ui-state-default ui-corner-all"
    name="clientjobreference" size="28" value="'.$row['clientjobreference'].'"> </div> ';
    
    
    echo ' <div class="fs"><div class="fsl">  Online Ref </div> <a title="Online Job Details" target="_blank" class="newwin" href="'. $globalprefrow['locationquickcheck'].'?quicktrackref='. $row['publictrackingref'].'">'. $row['publictrackingref'].'</a> ';
    
    echo ' <span id="emissionsaving">';
    
    if ($row['co2saving']) {
        if ($row['co2saving']>'1000') {
            echo ' '. number_format(($row['co2saving']/'1000'), 1).'Kg CO<sub>2</sub> ';
        } 
        else {
            echo ' '. $row['co2saving'].'g CO<sub>2</sub> ';
        }
    }
    else {
        if ($row['CO2Saved']) {
            $co=($row['CO2Saved']*$row["numberitems"]);
            if ($co>'1000') {
                $co=number_format(($co/'1000'), 1).'Kg ';
            } else { $co=$co.'g'; } echo ' '.$co .' CO<sub>2</sub> ';
        }
    }
    
    if ($row['pm10saving']>'0.01') {
        echo ' '. $row['pm10saving'].'g PM<sub>10</sub>';
    } else {
        if ($row['PM10Saved']<>'0.0') {
            echo ' '. ($row['PM10Saved']*$row["numberitems"]).'g PM<sub>10</sub> ';
        }
    }
    
    
    echo ' </span>
    </div>
    </div>
    </div>
    <div class="hangright">
    <div class="ui-corner-all ui-state-highlight addresses">
    <div class="fs"><div class="fsl"> Duplicate</div>
    <form action="order.php#" method="post">
    <input type="hidden" name="formbirthday" value="'.date("U").'">
    <input type="hidden" name="id" value="'.$row['ID'].'">
    <input type="hidden" name="page" value="createnewfromexisting">';
    
    
    $sql='SELECT statusname FROM status WHERE status=100 LIMIT 1';
    $sth=$dbh->prepare($sql);
    // $data=array($newstatus);
    // $sth->execute($data);
    $sth->execute();
    $result=$sth->fetchColumn();
    
    echo '<select id="currorsched" class="ui-state-default ui-corner-left" name="currorsched" >
    <option value="current" SELECTED> ';
    
    if ($row['status']>'100') {
        echo $result;
    }
    else {
        echo $row['statusname'];
    }
    echo '</option> <option value="unsched" > Uncollected</option></select>';
    
    
    echo '<select class="ui-state-default ui-corner-left" name="dateshift" >
    <option value="0" >Same</option>
    <option';

    if (($dateshift=='24') or ($dateshift=='72')) { echo ' SELECTED '; } echo ' value="24" >+ Day</option> <option';
    if ($dateshift=='168') { echo ' SELECTED '; } echo ' value="168" >+ Week</option> <option';
    if ($dateshift=='-24') { echo ' SELECTED '; } echo ' value="-24" >- Day</option><option ';
    if ($dateshift=='-168') { echo ' SELECTED '; } echo 'value="-168" >- Week</option> <option';
    if ($dateshift=='48') { echo ' SELECTED '; } echo ' value="48" >++ Day</option> <option ';
    if ($dateshift=='-48') { echo ' SELECTED '; } echo 'value="-48" >-- Day</option><option ';
    if ($dateshift=='720') { echo ' SELECTED '; } echo ' value="72" >+++ Day</option><option ';
    if ($dateshift=='528') { echo ' SELECTED '; } echo ' value="528" >+ 22 Days</option><option ';
    
    echo 'value="720" >+ 30 Days</option>
    </select>
    <button type="submit">New Job</button>
    </form>
    </div>
    <div class="fsr">
    <form action="mail7.php?" method="post" ><input type="hidden" name="formbirthday" value="'.date("U").'">
    <input type="hidden" name="id1" value="'.$row['ID'].'" >
    <button type="submit" >Send Email</button>
    </form> ';
    
    echo '<button id="orderaudit"> Audit Trail </button> ';
    if (!$mobdevice) {
        echo ' <button class="deleteord" id="deleteord"> Delete Job </button> ';
    }
    else {
        echo '<button class="deleteord" id="deleteordmob"> Delete Job </button>';
    }



    echo '<span id="ajaxinfo"> &nbsp; </span>
    <form name="uploadpodform" id="uploadpodform" enctype="multipart/form-data">
    <input form="uploadpodform" type="hidden" name="page" value="orderaddpod">
    <input form="uploadpodform" type="hidden" name="id" value="'. $row['ID'].'" >
    <input form="uploadpodform" type="hidden" name="publicid" value="'.$row['publictrackingref'].'" >
    <input form="uploadpodform" type="hidden" name="formbirthday" value="'. date("U"). '">
    </form>';



    if (!$mobdevice) {
        echo '<form action="index.php#" method="post" id="frmdel">
        <input type="hidden" name="formbirthday" value="'.date("U").'">
        <input type="hidden" name="id" value="'.$row['ID'].'">
        <input type="hidden" name="page" value="confirmdelete">
        </form>';

    }
    else {  // ends check for mobile device
        echo '<form action="index.php#" method="post" id="frmdelmob">
        <input type="hidden" name="formbirthday" value="'.date("U").'">
        <input type="hidden" name="id" value="'.$row['ID'].'">
        <input type="hidden" name="page" value="confirmdeletemobile">
        </form>';
    } // ends mobile delete 


    echo '</div>
    </div>
     
    <div id="orderajaxmap" class="ui-corner-all ui-state-highlight addresses hideuntilneeded"></div>
    
    </div> <br /> '; // ends div hangright
    
    $tmp= '<input type="email" id="email" name="email">';
    if (isset($newfavf)) {
        if ($newfavf=='1') {
            echo '<form action="#" id="newfavcollect" method="post" >
            <input type="hidden" name="page" value="editnewfav">
            <input type="hidden" name="clientorder" value="'.$row['CustomerID'].'">
            <input type="hidden" name="id" value="'.$row['ID'].'">
            <input type="hidden" name="fromfreeaddress" value="'.$row['fromfreeaddress'].'" />
            <input type="hidden" name="CollectPC" value="'.$row['CollectPC'].'" />
            <input type="hidden" name="formbirthday" value="'. date("U").'">
            </form> ';
        }
    }

    // $favto
    if (isset($newfavt)) {
        if ($newfavt=='1') {
            echo '<form action="#" id="newfavto" method="post" >
            <input type="hidden" name="page" value="editnewfav">
            <input type="hidden" name="clientorder" value="'.$row['CustomerID'].'">
            <input type="hidden" name="id" value="'.$row['ID'].'">
            <input type="hidden" name="fromfreeaddress" value="'.$row['tofreeaddress'].'" />
            <input type="hidden" name="CollectPC" value="'.$row['ShipPC'].'" />
            <input type="hidden" name="formbirthday" value="'. date("U").'">
            </form> ';
        }
    }
    
    echo $bottomhtml; // bottomhtml is to do with fav addresses forms
    
    
    echo '</div> ';
    
}
else { // no COJM ID located
    echo ' <div class="ui-state-highlight ui-corner-all p15" > 
	<p><span class="ui-icon ui-icon-info"></span>
	<strong>Hmmm, </strong> No COJM Reference with this ID located.</p> </div><br />';
    $searchid=strtoupper(trim($id));
    if ($searchid) {
        include "ordersearch.php";
    } // ends check for ID		
}

echo '
<script>
$(document).ready(function() {
    $("#jschangfavfr").bind("click", function(e) {
        e.preventDefault();
        $.zebra_dialog("", {
            "source":  {
                "ajax": ("ajaxselectfav.php?addr=fr&clientid=" + oldclientorder + "&jobid=" + id )
            },
            "type": "question",
            //	width : 500 ,
            //	position : ["left + 20", "top + 30"],
            "title": "Please select new address",
	        "buttons":  [ {
                caption: "Cancel", callback: function() {
                    }
                },
			{
                caption: "Select", 
                callback: function() {
                    $("#selectfav").submit(); 
                }
			}]
        });
    $("#selectfavbox").focus();
	});	


    
';



if ($row['chargedbycheck']=='1') { echo ' $("#cbb").show(); '; }
if ($row['isdepartments']==1) {  echo ' $("#clientdep").show();  '; }
if ($row['canhavemap']=='1') { echo ' $("#areaselectors").show(); '; } // canhavemap from service
if ($row['opsmaparea']) { echo ' $("#arealink").show(); '; } else { echo ' $("#arealink").hide(); '; }
if ($showsubarea=='1') { echo ' $("#opsmapsubarea").show(); '; }
if ($row['opsmapsubarea']) { echo ' $("#subarealink").show(); '; } else { echo ' $("#subarealink").hide(); '; }

if ((date('U', strtotime($row['starttrackpause']))>10) or (date('U',strtotime($row['finishtrackpause']))>10)) { 
echo ' $("#toggleresume").show(); '; }





if ($row['iscustomprice']=='1') { 
    echo ' $("#buttoncancelpricelock").show(); ';
}




if ($haspod>0) {
    echo ' $("#uploadpodfile").hide(); $("#podimagecontainer").show(); ';
} else {
    echo ' $("#uploadpodfile").show(); $("#podimagecontainer").hide(); ';
}


if ($row['chargedbybuild']<>'1') { 
    echo ' $("#baseservicecbb").show(); ';
    echo ' $("#mileagerow").hide(); ';
}
else { 
    echo ' $("#baseservicecbb").hide(); ';
    echo ' $("#mileagerow").show(); ';
}

echo '

}); // ends document ready

</script>

<!-- all divs are float so orderfoot pads the bottom out for menu etc to display ok -->
<div class="orderfoot"> &nbsp; </div>
';


include "footer.php";
mysql_close();

$dbh=null;

echo '</body></html>';