<?php
/*
    COJM Courier Online Operations Management
	order.php - Update single job
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
// if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";

if ($globalprefrow['forcehttps']>'0') { if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$hasforms='1';
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


?><!doctype html><html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height" >
<meta name="generator" content="COJM www.cojm.co.uk">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<link id="pagestyle" rel="stylesheet" type="text/css" href="<?php echo $globalprefrow['glob10']; ?>" >
<script type="text/javascript" src="js/<?php echo $globalprefrow['glob9']; ?>"></script>
<?php 

include "changejob.php";

$query = "SELECT *
FROM Orders
INNER JOIN Clients 
INNER JOIN Services 
INNER JOIN status 
left join clientdep ON Orders.orderdep = clientdep.depnumber
left JOIN Cyclist ON Orders.CyclistID = Cyclist.CyclistID
WHERE Orders.CustomerID = Clients.CustomerID 
AND Orders.ServiceID = Services.ServiceID
AND Orders.status = status.status
AND Orders.ID = ? LIMIT 0,1";

$statement = $dbh->prepare($query);
$statement->execute([$id]);
$row = $statement->fetch(PDO::FETCH_ASSOC);

$cojmid=$id;



if ($row['ID']) {
    
    $query = "SELECT * FROM cojm_pod WHERE id = :getid LIMIT 0,1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':getid', $row['publictrackingref'], PDO::PARAM_INT); 
    $stmt->execute();
    $haspod = $stmt->rowCount();
    $formbirthday=microtime(TRUE);


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
var googlemapapiv3key="<?php echo $globalprefrow['googlemapapiv3key']; ?>";
var canshowareafromservice<?php if ($row['canhavemap']) { echo '=1'; } ?>;

function downloadJSAtOnload() {
 var element = document.createElement("script");
 element.src = "js/order.js";
 document.body.appendChild(element);
 }

 if (window.addEventListener)
 window.addEventListener("load", downloadJSAtOnload, false);
 else if (window.attachEvent)
 window.attachEvent("onload", downloadJSAtOnload);
 else window.onload = downloadJSAtOnload;

</script>
<title><?php echo $id; ?> COJM</title>
<style>
/* starts spinner on page load, only for ajax pages  */
#toploader { display:block; }
</style>

<?php
} // ends check for valid job ID
?>
</head><body class="orderpage">
<?php

$filename='order.php'; 
include "cojmmenu.php"; 

if ($row['ID']) {

    echo '<div id="Post" class="Post lh24 clearfix';
    if ($row['status']>99) { echo ' complete'; }
    
    echo '">
    <div class="hangleft">
    <div class="ui-corner-all ui-state-highlight addresses" >
    <div class="fs">
    <div class="fsl">';
    
    // echo 'innacpc is '. $globalprefrow['inaccuratepostcode'];
      
    $enrpc0=trim($row['enrpc0']);
    $prenrpc0= str_replace(" ", "+", "$enrpc0");
    $linkfafrom=trim($row['enrft0']);
    $linkfafrom= str_replace(" ", "+", "$linkfafrom");

    
    $enrpc21=trim($row['enrpc21']);
    $prenrpc21= str_replace(" ", "+", "$enrpc21", $count);
    $prfreea=trim($row["enrft21"]);
    $prfreea= str_replace(" ", "+", "$prfreea", $count);
    $tolinktxt='';
    
    
        if (($globalprefrow['inaccuratepostcode'])=='0') {
        $pcenrpc0= str_replace(" ", "", "$enrpc0", $count);
        if (trim($row['enrpc0'])) {
            $sql = 'SELECT PZ_northing FROM  `postcodeuk` WHERE  `PZ_Postcode` LIKE ? LIMIT 0 , 1';  
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$pcenrpc0]);
            $frompcondb = $stmt->fetchAll();
        }
        
        
        
        
        $pcprenPC= str_replace(" ", "", "$enrpc21", $count);
        if (trim($pcprenPC)) {
            $sql = 'SELECT PZ_northing FROM  `postcodeuk` WHERE  `PZ_Postcode` LIKE ? LIMIT 0 , 1';  
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$pcprenPC]);
            $enrpc21ondb = $stmt->fetchAll();
        }
    
        
        
        
        
    }
    

    if (($enrpc0) or ($linkfafrom)) {


        if ($globalprefrow["inaccuratepostcode"]=='1') {
            $pulinktxt =  $linkfafrom.'+'.$prenrpc0;
        } 
        else { // accurate postcode
            if ($prenrpc0) {
                $pulinktxt= $prenrpc0;
            }
        }
    }

    
    
    

    
    if (($enrpc21) or ($prfreea)) {
        if ((($globalprefrow['inaccuratepostcode'])=='0') and ($enrpc21)) {
            $tolinktxt=$prenrpc21;
        }
        if ((($globalprefrow['inaccuratepostcode'])=='1') and (($enrpc21) or ($prfreea))) {
            $tolinktxt = $prfreea.'+'.$prenrpc21.' ';
        }
    }
    
    
    
    echo ' <a id="viewinmap0" title="View in Maps" target="_blank" class="newwin';
    if (!$pulinktxt) {
        echo ' hideuntilneeded';
    }
    echo '" href="https://'.$globalprefrow['addresssearchlink'].$pulinktxt.'">PU</a> ';
    
    

    if ((trim($row['enrft0'])) or (trim($row['enrpc0']))) {
        $sql = 'SELECT favadrcomments FROM cojm_favadr WHERE 
            favadrft = ?
            AND favadrclient= ?
            AND favadrpc= ?
            AND favadrisactive="1" 
            LIMIT 0 , 1';  
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$row["enrft0"],$row['CustomerID'],$row['enrpc0']]);
        $favadrrow = $stmt->fetchAll();
        if ($favadrrow) {
        $newfavf=0;
        $favcomments=$favadrrow[0]['favadrcomments'];
        } 
        else {
            $newfavf=1;
        } // ends found / not found
    } // ends check for address to check
    

    echo '<button id="editfav0" title="Add / Edit Favourite" class="editfav';
    if ((!trim($row['enrft0'])) and (!trim($row['enrpc0']))) {
        echo ' hideuntilneeded';
    }
    
    
    if ($newfavf<>0) {
        echo ' newfav';
    }
    
    
    
    echo '"> Edit Favourite </button>';
    

 
    echo '<button class="chngfav';
    if ($row['status']>99) { echo ' hideuntilneeded'; }
    echo '" title="Insert Favourite" id="jschangfavfr"> &nbsp;  </button>
    </div>
    <input class="addfield caps ui-state-default ui-corner-left freetext" ';
    echo 'id="enrft0" type="text" title="Collection Address" placeholder="From . . ." value="'. $row["enrft0"].'" /><input ';
    echo ' size="9" placeholder="Postcode" class="addfield caps ui-state-default ui-corner-right';


    if (($globalprefrow['inaccuratepostcode'])==0) {
        if ((((!trim($row['enrpc0']))) and (trim($row['enrft0']))) or (((trim($row['enrpc0']))) and (!$frompcondb))) {
                echo ' ui-state-error';
        }
    }

    
    echo '" id="enrpc0" type="text" title="Collection Postcode" maxlength="9" value="'.trim($row["enrpc0"]).'">';
    
    
    if (($globalprefrow['inaccuratepostcode'])==0) {    
        
        echo ' <button id="addpostcodebutton0" title="Add Postcode" class="addpostcodebutton';
                
        if (($frompcondb) or (!trim($row["enrpc0"]))) { 
            echo ' hideuntilneeded'; 
        } 
        
        echo '"> Add Postcode </button>       ';
        
    }
    
    
    echo " <button class='activewheneditable addvia";
    if ($row['status']>99){
        echo ' hideuntilneeded';
    }
    echo "' id='togglenr1choose' title='Add Via' > Add via </button>";

    echo '</div> 
    <div id="favcomment0" class="favcomments fsr';
    if (!$favcomments) { echo ' hideuntilneeded'; }
    echo '"> '.$favcomments.' </div> ';

    echo '<div id="orderviadiv"></div>';
    
 
    echo '<div class="fs"><div class="fsl"> '; // starts to address
    
    

    
    echo '<a title="View in Maps" id="viewinmap21" class="newwin';
    if (!$tolinktxt) {
        echo ' hideuntilneeded';
    }
    echo '" target="_blank" href="https://'.$globalprefrow['addresssearchlink']. $tolinktxt.'">To</a>';
    
    

    $favcomments='';
    if ((trim($row['enrft21'])) or (trim($row['enrpc21']))) {
        $sql = 'SELECT favadrcomments FROM cojm_favadr WHERE 
            favadrft = ?
            AND favadrclient= ?
            AND favadrpc= ?
            AND favadrisactive="1" 
            LIMIT 0 , 1';  
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$row["enrft21"],$row['CustomerID'],$row['enrpc21']]);
        $favadrrow = $stmt->fetchAll();
        if ($favadrrow) {        
            $favcomments=$favadrrow[0]['favadrcomments'];
        }
        
    } // ends check for address to check
    
    
    echo '<button id="editfav21" title="Add / Edit Favourite" class="editfav';
    if (!$favadrrow) {
        echo ' newfav';
    }
        
        
    if ((!(trim($row["enrft21"]))) and (!(trim($row["enrpc21"])))) {        
        echo ' hideuntilneeded';
    }
    echo '"> Add / Edit Favourite </button>';
    
    
    
    
    
    

    echo ' <button class="chngfav';
    if ($row['status']>99) { echo ' hideuntilneeded'; }
    echo '" title="Insert Favourite" id="jschangfavto"> &nbsp;  </button> ';
    
    
    
    echo ' </div>
    
    <input type="text" id="enrft21" class="addfield caps ui-state-default ui-corner-left freetext';
    echo '" placeholder="To . . ." value="'.$row["enrft21"].'" title="Delivery Address" ><input placeholder="Postcode" size="9" ';
    echo ' class="addfield caps ui-state-default ui-corner-right';
    


    if (($globalprefrow['inaccuratepostcode'])=='0') {
        if (((!(trim($row['enrpc21']))) and (trim($row['enrft21']))) or ((!$enrpc21ondb) and (trim($row['enrpc21'])))) {
            echo ' ui-state-error';
        }
    }


    echo '" type="text" id="enrpc21" title="Delivery Postcode" value="'. trim($row["enrpc21"]).'"> ';
    
    if ( $globalprefrow["inaccuratepostcode"]=='0') {
        echo ' <button id="addpostcodebutton21" title="Add Postcode" class="addpostcodebutton';
        if (($enrpc21ondb) or (!trim($row["enrpc21"]))){
            echo ' hideuntilneeded';
        }
        echo '"> Add Postcode </button> ';
    }

    
    
    if ( $globalprefrow["inaccuratepostcode"]=='0') {
        echo '<span id="orderdistance">';
        if ($row['distance']>'0') {
            echo $row['distance'].' '. $globalprefrow['distanceunit'];
        }
        echo '</span>';
    }
    
    
    
    echo '</div>';
    echo '<div id="favcomment21" class="favcomments fsr';
    if (!$favcomments) {
        echo ' hideuntilneeded';
    }

    echo '">'.$favcomments.'</div>';
    
    if ( $globalprefrow["inaccuratepostcode"]=='1') {
        echo ' <input type="text" class="caps ui-state-default ui-corner-all" id="distance" size="4" value="'.
        $row['distance']. '" maxlength="5" />' . $globalprefrow['distanceunit'];
    }
    
    echo ' <div class="clrfix"> </div> ';
    

    // starts area selectors
    echo '<div id="areaselectors" ';
    if (($row['canhavemap']<>'1') or (($row['status']>99) and (!$opsmaparea))) {
        echo 'class="hideuntilneeded" ';
    }
    echo '>';
    $showsubarea='0';
    $checkifarchivearea=0;
    if ($row['opsmaparea']>0) {
        $stmt = $dbh->prepare("SELECT inarchive FROM opsmap WHERE opsmapid=? LIMIT 0,1");
        $stmt->execute([$row['opsmaparea']]);
        $checkifarchivearea = $stmt->fetchColumn();
    }
    
    if ($checkifarchivearea) {
        $topareaquery = "SELECT opsmapid, inarchive, opsname, descrip, istoplayer FROM opsmap WHERE type=2 AND corelayer='0' "; 
    }
    else {
        $topareaquery = "SELECT opsmapid, inarchive, opsname, descrip, istoplayer FROM opsmap WHERE type=2 AND inarchive<>1 AND corelayer='0' "; 
    }

    
    echo '<div class="fs"><div class="fsl"> </div> 
    <select id="opsmaparea" name="opsmaparea" class="ui-state-default ui-corner-left">
    <option value="" > Choose Area </option>';
    
    $stmt = $dbh->prepare($topareaquery);
    $stmt->execute();

    $areaarray = $stmt->fetchAll();
   
    foreach ($areaarray as $arearow ) {
        print ("<option ");
        if ($row['opsmaparea'] == $arearow['opsmapid']) {
            echo ' selected="selected" ';
            $showsubarea=$arearow['istoplayer'];
            $topname=$arearow['opsname'];
            $topdescrip=$arearow['descrip'];
        } 

        echo 'value="'.$arearow['opsmapid'].'" >';
        
        if ($arearow['inarchive']) { echo ' ARCHIVED '; }
        
        echo $arearow['opsname'];
        if ($arearow['istoplayer']=='1') { 
            echo ' ++ ';
        }
        
        echo '</option>';
    }
    echo '</select>
    <a id="arealink" class="showclient marright10';

    if ($row['opsmaparea']<1) {
        echo ' hideuntilneeded';
    }
    echo '" title="Area Details" target="_blank" href="opsmap-new-area.php?areaid='.$row['opsmaparea'].'"> </a>';

    
    echo '<script> var initialhassubarea='.$showsubarea.'; </script>';

    echo ' <select id="opsmapsubarea" name="opsmapsubarea" class="ui-state-default ui-corner-left';


if ($showsubarea<>'1') {
    echo ' hideuntilneeded';
}
    echo '">
    <option value="" > Choose Sub Area </option>';
    
    if ($row['opsmaparea']>0) {

        $pbtmareaquery = "SELECT opsmapid, inarchive, opsname, descrip  FROM opsmap WHERE type=2 AND corelayer= :opsmaparea "; 
        $stmt = $dbh->prepare($pbtmareaquery);
        $stmt->bindParam(':opsmaparea', $row['opsmaparea'], PDO::PARAM_INT); 
        $stmt->execute();
        $favdata = $stmt->fetchAll();
        if ($favdata) {
            foreach ($favdata as $subarearow ) {
                print ("<option "); 
                if ($row['opsmapsubarea'] == $subarearow['opsmapid']) {
                    echo ' selected="selected" '; 
                    $subareacomments=$subarearow['descrip'];
                } 
                echo 'value="'.$subarearow['opsmapid'].'" >' .$subarearow['opsname'].' '.$subarearow['descrip'];
                echo '</option>';
            }
        }
    }
    echo '</select>
    <a id="subarealink" class="showclient';
    if ($row['opsmapsubarea']<1) {
        echo ' hideuntilneeded';
    }
    echo '" title="Sub Area Details" target="_blank" href="opsmap-new-area.php?areaid='.$row['opsmapsubarea'].'"> </a>
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
    
    echo '<select id="newrider" name="newcyclist" class="ui-state-default ui-corner-left ';
    if ($row['CyclistID']=='1') { 
        echo ' red ';
    }
    echo ' ">';

    
    if ($row['isactive']=='1') {
        $cyclistquery = "SELECT CyclistID, cojmname, isactive FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 
    } else {	
        $cyclistquery = "SELECT CyclistID, cojmname, isactive FROM Cyclist ORDER BY CyclistID"; 
    }
    
    $data = $dbh->query($cyclistquery)->fetchAll();
    
    foreach ($data as $riderrow ) {
        echo " <option ";
        if ($row['CyclistID'] == $riderrow['CyclistID']) {
            echo ' selected="selected" ';
        }
        echo ' value="'.$riderrow['CyclistID'].'" ';
        
        if (($riderrow['CyclistID']=='1') or ($riderrow['isactive']<>'1')) {
            echo ' class="unalo" ';
        }
        echo ' > ';

        if ($riderrow['isactive']<>'1') {
            echo ' INACTIVE ';
        }

        echo $riderrow['cojmname'];
        echo ' </option> ';
    }
    print ("</select>");
    
    echo '<a id="showriderlink" class="showclient';
    if ($row['CyclistID']=='1') {
        echo ' hidden';
    }
    
    echo '" title="'.$row['cojmname'].' Details" target="_blank" href="cyclist.php?thiscyclist='.$row['CyclistID'].'"> </a>';
    if ($row['isactive']<>'1') {
        echo ' INACTIVE ';
    }

    echo '</div>'; // finishes select rider
    echo '</div>'; // finishes container



    

    echo '<div class="ui-corner-all ui-state-highlight addresses">';   // STATUS /// + times container
    if ($row['status']<'101') { // show select status if uninvoiced
        echo '<div class="fs"><div class="fsl"></div> ';
        print (" <select id=\"newstatus\" name=\"newstatus\" class=\"ui-state-default ui-corner-left\" >\n");

        $query = "SELECT statusname, status FROM status WHERE activestatus=1 AND status<101 ORDER BY status";
        
        $data = $dbh->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);
        foreach($data as $statusname => $status) {
            print ("<option ");
            if ($row['status'] == $status) {
                echo " SELECTED ";
            }
            
            if ($status < 77) {
                echo 'class="hideifcomplete" ';
            }
            
            if ($status == 100 ){
                echo 'id="completeoption" ';
            }
            
            echo 'value="'.$status.'">'.
            htmlspecialchars ($statusname).'</option>';
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

        echo '<button class="hideuntilneeded addslot" id="allowww" title="Add Slot">Add Slot</button> 
        <span class="';
        if (date('U', strtotime($row['collectionworkingwindow']))<10) {
            echo 'hideuntilneeded';
        }
        
        echo '" id="allowwwuntil"> until </span>
        
        
        <input type="text" class="caps ui-state-default ui-corner-all dpinput';

        if (date('U', strtotime($row['collectionworkingwindow']))<10) {     
            echo ' hideuntilneeded';
        }
        echo '" name="collectionworkingwindow" id="collectionworkingwindow" value="';
        if ($row['collectionworkingwindow']>'10') {
            echo date('d/m/Y H:i', strtotime($row['collectionworkingwindow']));
        } 
        echo '" />';
    }
    
    echo ' <span id="collectiontext"></span> ';
    echo '</div>';

    
    echo '<div id="starttravelcollectiontimediv" class="fs';
        if (($row['status']>99) or ($row['status']<41)) {
            echo ' hideuntilneeded';
        }
    echo '"><div class="fsl">En route PU</div> '; 
    echo '<input type="text" class="caps ui-state-default ui-corner-all dpinput" name="starttravelcollectiontime" id="starttravelcollectiontime" value="';
    if ($row['starttravelcollectiontime']>'10') { 
        echo date('d/m/Y H:i', strtotime($row['starttravelcollectiontime']));
    }
    echo '" /> </div>';

    
    
    echo '<div id="waitingstarttimediv" class="fs';
        if (($row['status']>99) or ($row['status']<51)) {    
    echo ' hideuntilneeded';
        }
    echo '"><div class="fsl">On site PU </div> ';  	
    echo '<input type="text" class="caps ui-state-default ui-corner-all dpinput" name="waitingstarttime" '; 
    echo 'id="waitingstarttime" value="'; 
    if ($row['waitingstarttime']>'10') { 
        echo date('d/m/Y H:i', strtotime($row['waitingstarttime']));
    }
    echo '" /> </div> ';


    echo '
    <div id="collectiondatediv" class="fs orderhighlight';
    if (date('U', strtotime($row['collectiondate']))<10) {   
        echo ' hideuntilneeded';
    }
    echo '">
    <div class="fsl">PU </div> 
    <input type="text" class="caps ui-state-default ui-corner-all dpinput" name="collectiondate" id="collectiondate" 
    value="'; 
    if ($row['collectiondate']>'10') {
        echo date('d/m/Y H:i', strtotime($row['collectiondate']));
    }
    echo '" />
    <button id="toggleresumechoose" class="toggleresumechoose';
    
    if (
    ($row['status']<60)or(($row['starttrackpause']<'10')and(date('U', strtotime($row['finishtrackpause']))<10)and($row['status']>99))) { 
        echo ' hideuntilneeded';
    }
    
    
    echo '" title="Add Pause / Resume"> &nbsp; </button> 
    <span id="collectiondatetext"></span> 
    </div>  
    <div id="toggleresume" class="toggleresume fs';
    
    if (($row['starttrackpause']<'10') and (date('U', strtotime($row['finishtrackpause']))<10)) {
        echo ' hideuntilneeded';
    }

        
    echo '" >
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
        echo ' <button class="hideuntilneeded addslot" id="allowdww" title="Add Slot"> Add Slot </button> 
        <span class="hideuntilneeded" id="untildww"> until </span> 
        <input type="text" class="caps ui-state-default ui-corner-all dpinput hideuntilneeded" 
        name="deliveryworkingwindow" id="deliveryworkingwindow" value="';
        if (date('U', strtotime($row['deliveryworkingwindow']))>10) {
            echo date('d/m/Y H:i', strtotime($row['deliveryworkingwindow']));
        }
        echo '" />';
    }

    echo ' <span id="deliverytext"></span> </div> 
    <div id="ShipDatediv" class="orderhighlight fs';
    
    if ($row['status']<80) {
        echo ' hideuntilneeded';
    }
    
    echo '">
    <div class="fsl" >  ';
    
    $completetext = $dbh->query("SELECT statusname from status WHERE `status`.`status`='100' LIMIT 0,1")->fetchColumn();
    echo $completetext. ' </div> ';
    
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
    <div class="fs">
    <div class="fsl" id="ordernumberitemscontainer">
    <input id="numberitems" class="ui-state-default ui-corner-left pad numberitems" '; ?>data-autosize-input='{ "space": 6 }' <?php
    echo ' name="numberitems" ';
    
    $numberitems= trim(strrev(ltrim(strrev($row['numberitems']), '0')),'.');
    echo ' value="'. $numberitems. '" > x </div>'; 

    
    
    


    ////////////   SERVICE           ////////////////
    if ($row['activeservice']=='1') {

        print ("<select id =\"serviceid\" class=\"ui-state-default ui-corner-left\" name=\"serviceid\" >"); 

        $query = "
        SELECT ServiceID,
        Service 
        FROM Services 
        WHERE activeservice='1' 
        ORDER BY serviceorder DESC, ServiceID ASC"; 
        $data = $dbh->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);
        foreach($data as $ServiceID => $Service) {
            
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
    
    
    echo '<div id="jobcommentsdiv" class="fs';
    
    
    if (($row['status']>99) and (!$row['jobcomments'])) {
        echo ' hideuntilneeded';
    }
    
    
    echo '">
    <div class="fsl">Instructions</div>
    <textarea id="jobcomments" class="normal caps ui-state-highlight ui-corner-all orderjobcomments';
    echo '" name="jobcomments" >'. $row['jobcomments'].'</textarea>
    </div>
    <div id="privatejobcommentsdiv" class="fs';
    
    if (($row['status']>99) and (!$row['privatejobcomments'])) {
        echo ' hideuntilneeded';
    }    
    
    echo '">
    <div class="fsl">Priv Note</div>
    <textarea id="privatejobcomments" class="normal caps ui-state-highlight ui-corner-all orderjobcomments" name="privatejobcomments">'.
    $row['privatejobcomments'].'</textarea></div>
    </div>';



    ///////////               pod stuff

    
    
    echo '<div id="podcontainer" class="ui-corner-all ui-state-highlight addresses';

    if (($row['status']<'99') or (($row['status']>'99') and (($haspod==1) or ($row["podsurname"]<>'')))) {
    }
    else {
        echo ' hideuntilneeded';
    }
    echo '">
    <div id="podsurnamecontainer" class="fs"><div class="fsl">POD </div>
    <input type="text" id="podsurname" placeholder="Surname" class="caps ui-state-default ui-corner-all" name="podsurname" size="25" maxlength="40" value="'.$row["podsurname"].'">';
    
    
    
    echo '
    <input type="file" form="uploadpodform" name="file" id="uploadpodfile" ';

    if ($haspod==1) {
    echo ' class="hideuntilneeded" ';
    }
    
    echo '    
    accept="image/png, image/gif, image/jpeg" />
    <div class="fsr hideuntilneeded" id="uploadpodprogress" ><progress></progress></div>
    </div>
    <div id="podimagecontainer" class="fsr';
    if ($haspod<1) {
    echo ' hideuntilneeded';
    }
    echo '"> 
    <span id="ajaxremovepod" title="Remove POD" > &nbsp; </span>
    <img id="orderpod" class="orderpod" alt="POD" ';

    if ($haspod>0) {
        echo 'src="../podimage.php?id='.$row['publictrackingref'].'" ';
    } else {
        echo ' src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEAAAAALAAAAAABAAEAAAI=" '; // blank image
    }


    echo ' >
    </div>
    </div>';
    
    echo ' </div> '; // ends div floatleft
    
    echo ' <div class="hangright">';
    echo '<div class="ui-corner-all ui-state-highlight addresses">';


    
    
    
    
    
    
    

    /////////////       CHARGED BY BUILD / CHECK SETTINGS             ////////////////////////////////
    echo ' <table id="cbb" class="ord';
    if ($row['chargedbycheck']<>'1') { echo ' hideuntilneeded'; }
    echo '" ><thead> ';

    echo ' <tr id="baseservicecbb" ';
    if ($row['chargedbybuild']=='1') { echo 'class="hideuntilneeded" '; }
    echo '>

    <td><span id="baseservicecbbtext">'.$numberitems.' x '.$selectedservicename.'</span>
    <span class="cbbprice" id="baseservicecbbprice"> &'.$globalprefrow["currencysymbol"].
    number_format(($numberitems * $row["Price"]), 2, '.', '') .'</span></td>
    <td></td>
    </tr> </thead><tbody>';
    
    echo '<tr id="mileagerow" '; 
    if ($row['chargedbybuild']<>'1') { echo 'class="hideuntilneeded" '; }
    echo '>';

    $query = "
    SELECT 
    chargedbybuildid, 
    cbbname
    FROM chargedbybuild 
    WHERE cbbcost <> '0.00'
    AND chargedbybuildid < '3'
    ORDER BY chargedbybuildid ASC"; 
    
    $data = $dbh->query($query)->fetchAll();
    foreach ($data as $cbbrow ) {
        $cbbname = htmlspecialchars ($cbbrow['cbbname']);
        $chargedbybuildid = htmlspecialchars ($cbbrow['chargedbybuildid']);
        if ($chargedbybuildid==1) {
            echo '<td> '. $cbbname. ' 
            <span class="cbbprice" id="cbb'.$chargedbybuildid.'"> &'.$globalprefrow["currencysymbol"] . $row["cbb$chargedbybuildid"]. ' </span> </td>';

        } // ends buildid=1
        
        if ($chargedbybuildid==2) {
            echo '<td> '.$cbbname.'
            <span class="cbbprice" id="cbb'.$chargedbybuildid.'"> &'.$globalprefrow["currencysymbol"].$row["cbb$chargedbybuildid"]. '  </span> </td>';
        }
    }
    
    
    echo '</tr>';
    
    
    
    
    
    
    $query = "
    SELECT 
    chargedbybuildid, 
    cbbname
    FROM chargedbybuild 
    WHERE cbbcost <> '0.00'
    AND chargedbybuildid <> '1'
    AND chargedbybuildid <> '2'
    ORDER BY cbborder"; 

    $i=1;

    $data = $dbh->query($query)->fetchAll();
    foreach ($data as $cbbrow ) {
        $chargedbybuildid=$cbbrow['chargedbybuildid'];
        $cbbname = htmlspecialchars ($cbbrow['cbbname']);
        $seeifnewtr='';
        $seeifnewtrend='';
        $tidytrloop='';
        
        if ($i%2) {
            $seeifnewtr = ' <tr> ';
            $tidytrloop='<td> </td> ';
        }
        else {
            $seeifnewtrend='</tr>';
        }
        
        echo $seeifnewtr;
        
        
        

        if ($chargedbybuildid==3) {
            echo '<td> ';
            echo ' <select class="ui-state-default ui-corner-left wspeca cbbcheckbox" name="waitingmins" id="waitingmins" > '; 
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
            echo' mins waiting time
            <span class="cbbprice" id="cbb'.$chargedbybuildid.'"> 
            &'.$globalprefrow["currencysymbol"]  . $row["cbb$chargedbybuildid"].' </span> ';

        }
        
        if ($chargedbybuildid>3) {
            echo '<td> ';
            echo ' <label><input type="checkbox" name="cbbc'.$chargedbybuildid.'" value="1" class="cbbcheckbox" '; 
            if ($row["cbbc$chargedbybuildid"]<>'0') {
                echo ' checked ';
            }
            echo '> '.$cbbname.' <span id="cbb'.$chargedbybuildid.'" class="cbbprice"> &'.$globalprefrow["currencysymbol"].$row["cbb$chargedbybuildid"].' </span> </label>';



        }
        
        echo '</td> '.$seeifnewtrend;

        $i++;

    } // ends loop for cbb loop

    echo $tidytrloop;
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
    
    <button id="buttoncancelpricelock" class="cancelpricelock';
    
    if ($row['iscustomprice']<>'1') { 
        echo ' hideuntilneeded';
    }
    
    echo '" title="Cancel Pricelock">&nbsp;</button>
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
        echo $row['statusname'].' <a href="view_all_invoices.php?viewtype=individualinvoice&amp;formbirthday='. date("U").
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

	echo '<select class="ui-state-default ui-corner-all" id="combobox" name="clientorder" ><option value="">Select..</option>';

    $data = $dbh->query($query)->fetchAll();
    foreach ($data as $clientrow ) {
        $CustomerIDlist = htmlspecialchars ($clientrow['CustomerID']);
        $CompanyName = htmlspecialchars ($clientrow['CompanyName']);
        print "<option ";
        if ($CustomerIDlist == $row['CustomerID']) {
            echo "selected='SELECTED' ";
        }
        if ($clientrow['isactiveclient'] <>1) {
            echo ' class="unalo" ';
        }
        
        echo ' value="'.$CustomerIDlist.'">';

        if ($clientrow['isactiveclient'] <>1 ) {
            echo ' INACTIVE ';
        }
        
        echo $CompanyName;
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
    echo '  <div id="clientdep" class="fs';
    
    if ($row['isdepartments']<>1) {
        echo '    hideuntilneeded';
    }
    echo '">
    <div class="fsl">
    <a id="clientdeplink" class="showclient';
    if ($row['orderdep']<'1') {
        echo ' hideuntilneeded';
    }
    echo '" title="'.$row['depname'].' Details" 
    target="_blank" href="new_cojm_department.php?depid='.$row['orderdep'].'"> </a>
    </div>';

    echo '<select class="ui-state-default ui-corner-left" id="orderselectdep" >
    <option value="0" >No Department</option>';
    
    $query = "SELECT depnumber, depname , isactivedep FROM clientdep 
    WHERE associatedclient = :CustomerID ORDER BY isactivedep DESC, depname";

    
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':CustomerID', $row['CustomerID'], PDO::PARAM_INT); 
    $stmt->execute();
    
    $data = $stmt->fetchAll();
    foreach ($data as $deprow ) {    
    
        $depname = htmlspecialchars($deprow['depname']);
        print'<option ';
        if ($deprow['depnumber']==$row['orderdep']) {
            echo ' SELECTED ';
        }
        echo 'value="'.$deprow['depnumber'].'">';
        if ($deprow['isactivedep']<>1) {
            echo ' INACTIVE ';
        }
        echo $depname;
        echo '</option>';
    }
    
    
    echo '</select> ';
    
    echo '</div>
    <div id="clientdepnotes" class="fsr favcomments">';
    if (isset($row['depcomment'])) {
        if (trim($row['depcomment'])) {
            echo $row['depcomment'];
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
    
    <div id="orderajaxmap" class="ui-corner-all ui-state-highlight addresses hideuntilneeded clearfix"></div>
    
    <div class="ui-corner-all ui-state-highlight addresses">    
    
    <div class="fs">
    <div class="fsl"> Duplicate</div>
    <form action="order.php#" method="post">
    <input type="hidden" name="formbirthday" value="'.date("U").'">
    <input type="hidden" name="id" value="'.$row['ID'].'">
    <input type="hidden" name="page" value="createnewfromexisting">';
    
    
    
    
    echo '<select id="currorsched" class="ui-state-default ui-corner-left';

    if ($row['status']<31) {
        echo ' hideuntilneeded';
    }
    
    echo '" name="currorsched" >
    <option value="current" SELECTED> ';
    
    if ($row['status']>'100') {
        echo $completetext;
    }
    else {
        echo $row['statusname'];
    }
    echo '</option> <option value="unsched" > Uncollected</option>
    </select>';
    
    
    echo '<select class="ui-state-default ui-corner-left';

    echo '" name="dateshift" >
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

    echo ' <button class="deleteord" id="deleteord"> Delete Job </button> ';




    echo '<span id="ajaxinfo"> &nbsp; </span>
    
    <form name="uploadpodform" id="uploadpodform" enctype="multipart/form-data">
    <input form="uploadpodform" type="hidden" name="page" value="orderaddpod">
    <input form="uploadpodform" type="hidden" name="id" value="'. $row['ID'].'" >
    <input form="uploadpodform" type="hidden" name="publicid" value="'.$row['publictrackingref'].'" >
    <input form="uploadpodform" type="hidden" name="formbirthday" value="'. date("U"). '">
    </form>';



        echo '<form action="index.php#" method="post" id="frmdel">
        <input type="hidden" name="formbirthday" value="'.date("U").'">
        <input type="hidden" name="id" value="'.$row['ID'].'">
        <input type="hidden" name="page" value="confirmdelete">
        </form>';




    echo '</div>
    </div>
     

    </div> <br /> '; // ends div hangright
    
    
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

echo ' <link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" > ';

include "footer.php";

echo '</body></html>';
