<?php 

/*
    COJM Courier Online Operations Management
	new_cojm_department.php - Edit Clients Departments
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
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$title = "COJM";
?><!doctype html>
<html lang="en"><head>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height" >
<?php
echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>';
?>
<title><?php print ($title); ?> Department Details</title>
</head><body>
<?php 
$adminmenu=1;

$tempjs='';
$dlt='';
$dlm='';

$filename="new_cojm_client.php";

$hasforms='1';

include "changejob.php";
include "cojmmenu.php"; 

echo '<div class="Post Spaceout">';


if (isset($_POST['clientid'])) { $clientid=trim($_POST['clientid']); } else { $clientid=''; }
if (!$clientid) { if (isset($_GET['clientid'])) { $clientid=trim($_GET['clientid']); } }


if (isset($_GET['depid'])) { $posteddepid=$_GET['depid']; } else { $posteddepid=''; }
if (isset($_POST['depid'])) { $posteddepid=$_POST['depid']; }



?>
<div class="ui-state-highlight ui-corner-all p15" >
<p>
<?php


if ($posteddepid) {
    
    
    echo '
    <script>
    $(document).ready(function(){
    window.location.href = "#tabs-'.$posteddepid.'";	
    });
    </script>
    ';
    
    
    $sql = "SELECT * FROM clientdep 
INNER JOIN Clients ON Clients.CustomerID = clientdep.associatedclient
WHERE clientdep.depnumber = ? ";
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$posteddepid]);

}

else {

    $sql = "SELECT * FROM Clients WHERE CustomerID = ? ";
    
    $prep = $dbh->prepare($sql);
    $prep->execute([$clientid]);

    
}


$clrow = $prep->fetch(PDO::FETCH_ASSOC);

    
if ($clrow) {
    
    $clientid=$clrow['CustomerID'];
    
    $sql = "SELECT CustomerID, CompanyName FROM Clients WHERE isdepartments='1' ORDER BY CompanyName";
    $prep = $dbh->query($sql);
    $stmt = $prep->fetchAll();
    
    if ($stmt) {
        echo '<form action="new_cojm_department.php" method="post"> ';
        
        
        echo '
        <select class="ui-state-default ui-corner-all"  id="combobox" name="clientid" >
        <option value="">Select one...</option>';
        foreach ($stmt as $l) {
            $CompanyName = htmlspecialchars ($l['CompanyName']);
            print"<option "; 
            if ($l['CustomerID'] == $clientid) {
                echo "SELECTED "; 
                $thiscompanyname=$CompanyName;
            }
            echo ' value="'.$l['CustomerID'].'">'.$CompanyName.'</option>';
        } 
        
        echo '</select> 
        <button type="submit"> Select Client </button> 
        </form>';
        
        
        
        
        if (isset($thiscompanyname)) {
            echo '
            <form action="new_cojm_client.php" method="post">
            <input type="hidden" name="page" value="selectclientdepartment" >
            <input type="hidden" name="formbirthday" value="'.date("U").'">
            <input type="hidden" name="clientid" value="'.$clientid.'">
            <button type="submit"> Switch to '.$thiscompanyname.' Core </button>
            </form>';
        }
        
        
    
        
        
        echo ' <br />
        <form action="#" method="post">
        <input type="hidden" name="page" value="createnewdep" />
        <input type="hidden" name="clientid" value="'.$clientid.'" />
        <button type="submit"> Create new Department </button>
        <input type="hidden" name="formbirthday" value="'. date("U").'">
        Name : <input class="ui-state-default ui-corner-all pad" type="text" name="newdepname" size="15" />
        </form>';
        
        
    }
    else {
        echo '
                <div class="ui-state-error ui-corner-all p15"> 
                    <p><span class="ui-icon ui-icon-alert p15" ></span> 
                    <strong>No Clients set up with Departments</strong></p>
                </div><br />';
    }
        
    echo '</p> 
    </div>
    
    <div class="vpad"></div>
    <div class="line"></div>
    <div class="vpad"></div>';
    
    if ($clientid)  {
        
        echo '<form action="new_cojm_department.php#" method="post">
        <input type="hidden" name="clientid" value="'. $clientid.'">
        <input type="hidden" name="formbirthday" value="'. date("U").'">
        <input type="hidden" name="page" value="editdepartment" >';
        
        
        $sql = "SELECT * FROM clientdep WHERE associatedclient = ? ORDER BY isactivedep DESC , depnumber DESC  ";
        

        $sumtot=0;
        
        
        $prep = $dbh->prepare($sql);
        $prep->execute([$clientid]);
        $stmt = $prep->fetchAll();
    
        foreach ($stmt as $row) {
            
            // echo ' <div class="deptlist"> ';
            
            $sumtot++;
            
            $dlt.= '
            <div id="tabs-'.$row['depnumber'].'" class="p15"> 
            
            <fieldset><label >
            
            Name </label><input type="text" class="ui-state-default ui-corner-all pad" name="depname'. $row['depnumber'].'" value="'. $row['depname'].'">
            
            Is Active : <input type="checkbox" name="isactivedep'.$row['depnumber'].'" value="1" ';
            if ($row['isactivedep']>0) { $dlt.= 'checked';} 
            
            $dlt.= ' ></fieldset> 
            <fieldset><label > Password </label> <input type="text" class="ui-state-default ui-corner-all clearField pad" placeholder="Password " 
            name="deppassword'.$row['depnumber'].'" value="'.$row['deppassword'].'"> 
            
            </fieldset>
            <div class="line"></div>
            <fieldset><label >
            Phone </label><input type="text" class="ui-state-default ui-corner-all clearField pad" size="20" placeholder="'.$clrow['PhoneNumber'].'" 
            name="depphone'. $row['depnumber'].'" value="'.$row['depphone'].'">';
            
            if ($row['depphone'])  { $dlt.= ' Dep Tel : '.$row['depphone'].'. '; }  
            
            
            $dlt.=' </fieldset>
            <fieldset><label >
            Email </label> <input type="text" class="ui-state-default ui-corner-all clearField pad" size="60" placeholder="'.$clrow['EmailAddress'].'" 
            name="depemail'. $row['depnumber'].'" validation="required email" value="'.$row['depemail'].'">
            </label>
            ';
            
            
            
            
            $dlt.='
            <div class="line"></div>
            ';
            
            
            
            $dlt.= ' 
            
            <fieldset><label >
            
            Address </label> <input type="text" placeholder="'.$clrow['Address'].'" class="ui-state-default ui-corner-all clearField pad" size="40" 
            name="depaddone'. $row['depnumber'].'" value="'. $row['depaddone'].'"> 
            <input type="text" placeholder="'. $clrow['Address2'].'" class="ui-state-default ui-corner-all clearField pad" size="40" 
            name="depaddtwo'. $row['depnumber'].'" value="'. $row['depaddtwo'].'"> 
            </fieldset>
            
            <fieldset><label > &nbsp; </label>
            <input type="text" placeholder="'. $clrow['City'].'" class="ui-state-default ui-corner-all clearField pad" size="20" 
            name="depaddthree'. $row['depnumber'].'" value="'. $row['depaddthree'].'">
            
            <input type="text" class="ui-state-default ui-corner-all clearField pad" size="20" placeholder="'. $clrow['County'].'" 
            name="depaddfour'. $row['depnumber'].'" value="'. $row['depaddfour'].'">
            
            <input type="text" class="ui-state-default ui-corner-all clearField pad" size="10" placeholder="'. $clrow['CountryOrRegion'].'" 
            name="depaddfive'. $row['depnumber'].'" value="'. $row['depaddfive'].'">
            </fieldset>
            
            <fieldset><label > Postcode </label>
            <input type="text" class="ui-state-default ui-corner-all clearField pad" size="12" placeholder="'. $clrow['Postcode'].'" 
            name="depaddsix'. $row['depnumber'].'"  value="'. $row['depaddsix'].'"> </fieldset>';
            
            
            // $clrow['Address'].', '. $clrow['Address2'].'. '. $clrow['City'].', '. $clrow['County'].', '. $clrow['CountryOrRegion'].'. '. $clrow['Postcode'].'<br />'; 
            
            $dlt.=' <fieldset><label > &nbsp; </label>';
            
            if ($row['depname']) { $dlt.= $row['depname'].', '; } 
            $dlt=$dlt. $thiscompanyname.', ';
            if ($row['depaddone']) { $dlt.= $row['depaddone'].', '; } else { $dlt.= $clrow['Address'].', '; }
            if ($row['depaddtwo']) { $dlt.= $row['depaddtwo'].', '; } else { $dlt.= $clrow['Address2'].', '; }
            if ($row['depaddthree']) { $dlt.= $row['depaddthree'].', '; } else { $dlt.= $clrow['City'].', '; }
            if ($row['depaddfour']) { $dlt.= $row['depaddfour'].', '; } else { $dlt.= $clrow['County'].', '; }
            if ($row['depaddfive']) { $dlt.= $row['depaddfive'].', '; } else { $dlt.= $clrow['CountryOrRegion'].', '; }
            if ($row['depaddsix']) {
            $PC=$row['depaddsix'];
            $PC= str_replace(" ", "+", "$PC", $count);
            $dlt=$dlt. ' <a title="View in Maps" target="_blank" href="https://www.google.co.uk/maps?q='. $PC. '">'. $row['depaddsix'].'</a>.';
            
            } else {
            
            $PC=$clrow['Postcode'];
            $PC= str_replace(" ", "+", "$PC", $count);
            $dlt.= ' <a title="View in Maps" target="_blank" href="https://www.google.co.uk/maps?q='. $PC. '">'. $clrow['Postcode'].'</a>.';
            
            
            
            }
            
            
            
            $dlt.= '</fieldset> <div class="line"></div>
            
            <fieldset><label > Requestor </label> <input type="text" class="ui-state-default ui-corner-all clearField pad" size="20" placeholder="Requested By " name="deprequestor'. 
            $row['depnumber'].'" value="'.$row['deprequestor'].'"> 
            </fieldset>
            <fieldset><label >Default Service </label>  ';
            
            
            
            ////////////   SERVICE           ////////////////
            
            $sql=" SELECT Service 
            FROM Services 
            WHERE activeservice='1'
            AND ServiceID = ?
            LIMIT 1  ";
            
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$clrow['defaultservice']]);
            $isfavservice = $stmt->fetchColumn();            
            

            $dlt.= ("<select class=\"ui-state-default ui-corner-left\" name=\"depservice".$row['depnumber']."\">"); 
            $dlt.= '<option value="">Use '.$thiscompanyname.' default - '.$isfavservice.'</option>';
            
            $sql = " SELECT ServiceID, 
            Service 
            FROM Services 
            WHERE activeservice='1' 
            ORDER BY serviceorder DESC, ServiceID ASC"; 
            
            $prep = $dbh->query($sql);
            $stmt = $prep->fetchAll();
        
            foreach ($stmt as $s) {
                $Service = htmlspecialchars ($s['Service']);
                $dlt.= ("<option "); 
                if ($row['depservice'] == $s['ServiceID']) { $dlt.= " SELECTED "; }	
                $dlt.= ' value="'.$s['ServiceID'].'">'.$Service.'</option>';
            }
            $dlt.= ("</select> </fieldset>"); 
            
            /////////     ENDS    SERVICE ///////////////////////////////////////////////  
            
            

            
            

            
            $sql = " SELECT favadrft , favadrpc
            FROM cojm_favadr 
            WHERE favadrisactive='1'
            AND favadrclient = ?
            AND favadrid = ?
            LIMIT 1  ";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$clientid,$clrow['defaultfromtext']]);
            $ff = $stmt->fetch(PDO::FETCH_ASSOC);            
            
            
            $sql = " SELECT favadrft , favadrpc
            FROM cojm_favadr 
            WHERE favadrisactive='1'
            AND favadrclient = ?
            AND favadrid = ?
            LIMIT 1 ";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$clientid,$clrow['defaulttotext']]);
            $ft = $stmt->fetch(PDO::FETCH_ASSOC);             
            
            
            $dlt.=' <fieldset><label >Default PU </label>';
            

            
            $dlt.= ("<select class=\"ui-state-default ui-corner-left\" name=\"depdeffromft". $row['depnumber']."\">"); 
            $dlt.= '<option value="">Use '.$thiscompanyname.' default : '.$ff['favadrft'].', '.$ff['favadrpc'].'</option>';
            
            $sql = "SELECT favadrid, favadrft, favadrpc 
            FROM cojm_favadr 
            WHERE favadrisactive='1'
            AND favadrclient = ? "; 
            $prep = $dbh->prepare($sql);
            $prep->execute([$clientid]);
            $stmt = $prep->fetchAll();

            foreach ($stmt as $favrow) {
                $favadrft = htmlspecialchars ($favrow['favadrft']);	
                $favadrpc = htmlspecialchars ($favrow['favadrpc']); 
                $dlt.= ("<option "); 
                if ($row['depdeffromft'] == $favrow['favadrid']) {  $dlt.= " SELECTED "; }	
                $dlt.= ' value="'.$favrow['favadrid'].'">'.$favadrft.', '.$favadrpc.' </option>" ';
            }
            $dlt.= (" </select> </fieldset> "); 
            /////////     ENDS  DEFAULT PU ///////////////////////////////////////////////
            
            
            
            
            
            
            
            
            
            
            $dlt.= ' <fieldset><label > Default Drop </label>';
            
            $dlt.= ("<select class=\"ui-state-default ui-corner-left\" name=\"depdeftoft".$row['depnumber']."\">"); 
            $dlt.= '<option value="">Use '.$thiscompanyname.' default : '.$ft['favadrft'].', '.$ft['favadrpc'].'</option>';
            
            
            foreach ($stmt as $favrow) {
                $favadrft = htmlspecialchars ($favrow['favadrft']);	
                $favadrpc = htmlspecialchars ($favrow['favadrpc']); 
                $dlt.= ("<option "); 
                if ($row['depdeftoft'] == $favrow['favadrid']) {  $dlt.= " SELECTED "; }	
                $dlt.= ' value="'.$favrow['favadrid'].'">'.$favadrft.', '.$favadrpc.' </option>" ';
            }
            

            $dlt.= (" </select> </fieldset> "); 
            /////////     ENDS  DEFAULT PU ///////////////////////////////////////////////
            
            
            
            
            $dlt.='  
            <fieldset><label >Priv. Comments</label>
            <textarea class="normal ui-state-default ui-corner-all clearField pad" style="width: 65%; outline: none;" placeholder="Department Comments not shown to client" 
            name="depcomment'. $row['depnumber'].'" >'. $row['depcomment'].'</textarea>  </fieldset>
            
            
            <fieldset><label >
            
            
            Department ID </label> '.$row['depnumber'].'</fieldset>
            
            ';
            
            
            
            $dlt.='
            
            <div class="line"></div>
            <fieldset><label > <button formaction="#tabs-'.$row['depnumber'].'" type="submit" > Edit Departments </button></label>'.$sumtot.' Department(s) </fieldset>
            ';
            
            
            $dlt.= ' </div>';
            
            
            $dlm.='<li><a href="#tabs-'.$row['depnumber'].'">'.$row['depname'].'</a></li>';
            
            
        } ////  ends department loop 
            
        
        echo '<div id="tabs"><ul>';
        
        echo $dlm.'</ul>';
        echo $dlt. '  </div> </form>';
        
    } // ends check for client selected or new client
} 
    
echo '</div>';


echo '<script type="text/javascript">
$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+25));

	
	$(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
		$( "#combobox" ).toggle();	});	});
	});
</script>';



include "footer.php";

 ?>
<script type="text/javascript">
		$(function() {
		$(function() {	$("#tabs").tabs();	});
		
			$(function(){ $(".normal").autosize();	});
		});
function comboboxchanged() {}
</script>
</body></html>