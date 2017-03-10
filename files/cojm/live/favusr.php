<?php 


/*
    COJM Courier Online Operations Management
	favusr.php
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
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

include "C4uconnect.php";

$title = "COJM";
$clientid='';

if (isset($_POST['thisfavadrid'])) {
    $thisfavadrid=trim($_POST['thisfavadrid']);
} 
else { 
    $thisfavadrid=''; 
    if (isset($_GET['thisfavadrid'])) {
        $thisfavadrid=trim($_GET['thisfavadrid']);
    } 
}
if (isset($_POST['cojmid'])) { $cojmid=trim($_POST['cojmid']); } else { $cojmid=''; }
if (isset($_POST['showinactive'])) { $showinactive=trim($_POST['showinactive']); } else { $showinactive=''; }


if (isset($_GET['clientid'])) { $clientid=trim($_GET['clientid']); }
if (isset($_POST['clientid'])) { $clientid=trim($_POST['clientid']); } 
if (isset($_POST['clientorder'])) { $clientid=trim($_POST['clientorder']); }
if ($clientid=='') { $clientid='all'; }
$infotext=$infotext.'<p>id: '.$clientid.'</p>';

$i='0';

include "changejob.php";

?><!doctype html>
<html lang="en"><head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height " >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title); ?> New / Edit Favourite Address</title>
<script type="text/javascript">	
$(document).ready(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
			$( "#combobox" ).toggle();	}); });
</script></head><body>
<?php 

$hasforms='1';
$filename="favusr.php";
$invoicemenu='0';
$adminmenu='1';
include "cojmmenu.php"; 

echo '<div class="Post">
<div class="ui-widget ui-state-highlight ui-corner-all p15" >
<p>
<form action="#" method="post">
<input type="hidden" name="page" value="selectclient" >
<input type="hidden" name="formbirthday" value="'.date("U").'">
';





echo '<input type="hidden" name="page" value="selectclient">
<select class="ui-state-default ui-corner-all pad"  id="combobox" name="clientid" ><option value="">Select one...</option>
<option '; 
if ($clientid=='all') { echo ' SELECTED '; } echo 'value="all">All</option>';


$sql = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient>0 ORDER BY CompanyName"; 
$prep = $dbh->query($sql);
$stmt = $prep->fetchAll();
    
foreach ( $stmt as $row) {
    $CustomerID = htmlspecialchars ($row['CustomerID']); 
    $CompanyName = htmlspecialchars ($row['CompanyName']);
    print"<option "; 
    if ($CustomerID == $clientid) {echo "SELECTED "; } ; 
    echo ' value="'.$CustomerID.'">'.$CompanyName.'</option>';
}
echo '</select> ';

echo ' <button type="submit"> Select Client </button> 
 Show Inactive addresses ? <input type="checkbox" name="showinactive" value="1" ';  if ($showinactive>0) { echo 'checked';} echo ' />

</form></p>
<div class="vpad"> </div>
<form action="#" method="post" >
<input type="hidden" name="page" value="newfavourite">
<button type="submit">New Favourite</button>
</form>
</div>
<div class="vpad "></div>';

 if (($page=='newfavourite') or ($thisfavadrid<>'') ) {
echo '<div class="vpad"> </div>	<div class="ui-widget ui-state-default ui-corner-all" style="padding: 0.5em; width:auto; "><p>
<form action="favusr.php#" method="post">
<input type="hidden" name="page" value="editthisfavadr" />
<input type="hidden" name="formbirthday" value="'. date("U").'">
<input type="hidden" name="clientid" value="'.$clientid.'">';

if ($page=='newfavourite') {
$thisfavadrid='-1';
}

$sql = "SELECT * FROM cojm_favadr WHERE favadrid = ? LIMIT 1";

$statement = $dbh->prepare($sql);
$statement->execute([$thisfavadrid]);
$row = $statement->fetch(PDO::FETCH_ASSOC);


echo '<input type="hidden" name="thisfavadrid" value="'. $thisfavadrid.'"> <input type="hidden" name="oldfavadrpc" value="'. $row['favadrpc'].'" >
<input type="hidden" name="oldfavadrft" value="'. $row['favadrft'].'" >


<div style="position:relative; float:left; padding-left:50px; line-height:21px;">
<fieldset><label for="name" class="fieldLabel">  Address </label>
<input type="text" size="100" placeholder="77, High St, Mytown" class="caps ui-state-default ui-corner-all" name="favadrft" value="'. $row['favadrft'].'"> 
</fieldset> 


<fieldset><label for="name" class="fieldLabel">  Postcode </label>
<input type="text" size="13" class="caps ui-state-default ui-corner-all" name="favadrpc" value="'. $row['favadrpc'].'"></fieldset> 

<fieldset><label for="name" class="fieldLabel">  Is Active </label>
<input type="checkbox" name="favadrisactive" '; if ($row['favadrisactive']>0) { echo 'checked';} echo ' > </fieldset>

<fieldset><label for="name" class="fieldLabel">  Comments </label>
<input type="text" size="100" class="caps ui-state-default ui-corner-all" name="favadrcomments" value="'. $row['favadrcomments'].'"></fieldset> 

<select class="ui-state-default ui-corner-left pad" name="favadrclient" > ';

$sql = "SELECT CustomerID, CompanyName FROM Clients WHERE isactiveclient>0 ORDER BY CompanyName"; 
$prep = $dbh->query($sql);
$stmt = $prep->fetchAll();
    
foreach ( $stmt as $crow) {
    $CustomerID = htmlspecialchars ($crow['CustomerID']); 
    $CompanyName = htmlspecialchars ($crow['CompanyName']);
    print"<option "; 
    if ($CustomerID == $row['favadrclient']) {echo "SELECTED "; } ; 
    echo ' value="'.$CustomerID.'">'.$CompanyName.'</option>';
}
echo '</select> ';









echo '
<div class="vpad"> </div>
</div>';



//////  FAVOURITE TAGS   ////////////////////////////////////////////////
echo '<div style="position:relative; float:left; padding-left:50px;">
 <table><tbody>'; $i='1'; while ($i<'21') { if ($globalprefrow["favusrn$i"]) { if ( $i & 1 ) { echo'<tr>'; } 
echo '<td>'.$globalprefrow["favusrn$i"].'</td><td><input type="checkbox" name="favusr'.$i.'" value="1" '; if ($row["favusr$i"]>0) { echo 'checked';} 
echo ' /> </td>'; if ( $i & 1 ) {} else { echo '</tr> '; } } $i++; }echo '</tbody></table></div>';



echo '
<div class="vpad" style="clear:both;"> </div>
<button type="submit" style=" margin-left:50px;"';
if ($cojmid) { echo ' title="This will change the address where used in any future jobs for '.$thiscompanyname. '"'; }
echo ' > Edit Favourite</button> ';
if ($cojmid) { echo ' or <a title="'.$cojmid.'" href="order.php?id='.$cojmid.'">Return to Job</a>
<input type="hidden" name="cojmid" value="'.$cojmid.'" > '; }




echo '</form></div><div class="vpad"></div>';

} // ends page = 









echo '<div class="vpad"></div>
<table class="acc" ><tbody>';

$rpttext='<tr>
<th scope="col"></th>
<th scope="col"> Client</th>
<th scope="col">Address</th>
<th scope="col">Comments</th>
<th scope="col">Last Visit</th>
<th scope="col"> Tags</th>
</tr>';








if ($clientid) {
    if ($showinactive=='1') { 
        $sql = "SELECT * FROM cojm_favadr  INNER JOIN Clients 
        ON cojm_favadr.favadrclient = Clients.CustomerID WHERE  cojm_favadr.favadrclient = ? ";  
    } else {
        $sql = "SELECT * FROM cojm_favadr  INNER JOIN Clients 
        ON cojm_favadr.favadrclient = Clients.CustomerID WHERE  cojm_favadr.favadrclient = ? AND cojm_favadr.favadrisactive ='1'"; 
    }
    $prep = $dbh->prepare($sql);
    $prep->execute([$clientid]);
    $stmt = $prep->fetchAll();
} 


if ($clientid=='all') {
    if($showinactive<>'') {
        $sql = "SELECT * FROM cojm_favadr INNER JOIN Clients ON cojm_favadr.favadrclient = Clients.CustomerID ";
    } else {
        $sql = "SELECT * FROM cojm_favadr INNER JOIN Clients ON cojm_favadr.favadrclient = Clients.CustomerID WHERE cojm_favadr.favadrisactive = '1' ";
    }
    $prep = $dbh->query($sql);
    $stmt = $prep->fetchAll();
}


foreach ($stmt as $row) {
    
    
    

    $i=$i+'1';
    
    if (($i=='1') or ($i=='11') or ($i=='21')or ($i=='31') or ($i=='41') or ($i=='51') or ($i=='61') or ($i=='71') or ($i=='81')) { echo $rpttext; }
    
    echo '<tr';if ($row['favadrid']==$thisfavadrid){ echo ' style="background-color:#'.$globalprefrow['highlightcolour'].'; " '; } echo '>
    
    <td> 
    <form action="favusr.php" method="post"><input type="hidden" name="page" value="selectfavadr" />
    <input type="hidden" name="clientid" value="'.$clientid.'" /><input type="hidden" name="thisfavadrid" value="'.$row['favadrid'].'" />';
    if ($showinactive>'0') { echo '<input type="hidden"  name="showinactive" value="1" />'; }
    
    echo '<button style="" type="submit">Select</button></form>
    </td><td>
    '.$row['CompanyName'].'
    </td><td>
    '.$row['favadrft'].' <a target="_blank" href="http://maps.google.co.uk/maps?q='.$row['favadrpc'].'">'. $row['favadrpc'].'</a> 
    </td><td>'
    .$row['favadrcomments']. '
    </td><td>';
    if (date('U', strtotime($row['favadrlastvisit']))>'10') { echo date(' H:i D j M Y', strtotime($row['favadrlastvisit'])); }
    echo '</td><td>';
    
    if ($row['favadrisactive']<'1') { echo ' INACTIVE ';}
    
    if ($row['favusr1']=='1') { echo $globalprefrow['favusrn1'].' '; } if ($row['favusr2']=='1') { echo $globalprefrow['favusrn2'].' '; }
    if ($row['favusr3']=='1') { echo $globalprefrow['favusrn3'].' '; } if ($row['favusr4']=='1') { echo $globalprefrow['favusrn4'].' '; }
    if ($row['favusr5']=='1') { echo $globalprefrow['favusrn5'].' '; } if ($row['favusr6']=='1') { echo $globalprefrow['favusrn6'].' '; }
    if ($row['favusr7']=='1') { echo $globalprefrow['favusrn7'].' '; } if ($row['favusr8']=='1') { echo $globalprefrow['favusrn8'].' '; }
    if ($row['favusr9']=='1') { echo $globalprefrow['favusrn9'].' '; } if ($row['favusr10']=='1') { echo $globalprefrow['favusrn10'].' '; }
    if ($row['favusr11']=='1') { echo $globalprefrow['favusrn11'].' '; } if ($row['favusr12']=='1') { echo $globalprefrow['favusrn12'].' '; }
    if ($row['favusr13']=='1') { echo $globalprefrow['favusrn13'].' '; } if ($row['favusr14']=='1') { echo $globalprefrow['favusrn14'].' '; }
    if ($row['favusr15']=='1') { echo $globalprefrow['favusrn15'].' '; } if ($row['favusr16']=='1') { echo $globalprefrow['favusrn16'].' '; }
    if ($row['favusr17']=='1') { echo $globalprefrow['favusrn17'].' '; } if ($row['favusr18']=='1') { echo $globalprefrow['favusrn18'].' '; }
    if ($row['favusr19']=='1') { echo $globalprefrow['favusrn19'].' '; } if ($row['favusr20']=='1') { echo $globalprefrow['favusrn20'].' '; }
    echo '</td></tr>';
} // ends extract row loop

echo '</table>
<br />
<div class="line"> </div>
<br />
</div>
<script type="text/javascript">
$(document).ready(function() {
    var max = 0;
    $("label").each(function(){
        if ($(this).width() > max)
            max = $(this).width();    
    });
    $("label").width((max+15));
});

function comboboxchanged() {};


</script>';

include "footer.php";

echo '</body></html>';
