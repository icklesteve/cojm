<?php 

$alpha_time = microtime(TRUE);

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
error_reporting( E_ERROR | E_WARNING | E_PARSE );
$title = "COJM";
include "C4uconnect.php";
?><!doctype html>
<html lang="en">
<head>
<meta name="HandheldFriendly" content="true" >
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" >
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="js/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title><?php print ($title); ?> Core Pricing</title>
</head><body>
<?php 

$hasforms='1';
include "changejob.php";

$settingsmenu='1';
$invoicemenu='0';
$filename='corepricing.php';
include "cojmmenu.php"; 
 // echo 'extras page'; 
 
 $query = "SELECT * FROM chargedbybuild ORDER BY cbborder ASC"; 
// while ($costrow = mysql_fetch_array($result_id)) { extract($row);

echo '<div class="Post">

<form action="#" method="post">
<input type="hidden" name="formbirthday" value="'. date("U").'">
<input type="hidden" name="page" value="editcorepricing">

<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 1em;"> 
				<p>
			
<table class="acc"><tbody>
<tr>
<th scope="col"> Order </th>
<th scope="col"> ID </th>
<th scope="col"> Name </th>
<th scope="col"> Cost or % </th>
<th scope="col"> Added or Multipler % </th>
<th scope="col"> ASAP </th>
<th scope="col"> Cargobike </th>

<th scope="col"> Comments </th>
</tr>';
$idmax=1;
$sql_result = mysql_query($query,$conn_id)  or mysql_error(); 
while ($row = mysql_fetch_array($sql_result)) { extract($row);


echo '<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>
<tr>
<td><input class="ui-state-default ui-corner-all" type="text" name="cbborder'.$chargedbybuildid.'" size="3" maxlength="4" value=" '. $cbborder.'"></td>
<td> <input type="hidden" name="chargedbybuildid'.$chargedbybuildid.'" value="'. $chargedbybuildid.'">'.$chargedbybuildid.'</td>
<td><input class="ui-state-default ui-corner-all" type="text" name="cbbname'.$chargedbybuildid.'" size="30" maxlength="30" value=" '.$cbbname.'"></td>
<td>';
if ( $cbbmod=='+') { echo ' &'. $globalprefrow['currencysymbol']; }
echo '<input class="ui-state-default ui-corner-all" type="text" name="cbbcost'.$chargedbybuildid.'" size="7" maxlength="8" value=" '.$cbbcost.'">';
if ( $cbbmod=='x') { echo ' % '; }
echo '</td>
<td><select class="ui-state-default ui-corner-left" name="cbbmod'.$chargedbybuildid.'">';
if ( $cbbmod=='+') { echo  '<option  SELECTED value="+">Added to total</option><option value="x">Multiplied as percentage</option>'; } else
{ echo  '<option value="+">Added to total</option><option SELECTED value="x">Multiplied as percentage</option>'; }

// <input type="text" name="cbbmod'.$idmax.'" size="3" maxlength="3" value=" '.$cbbmod.' "></td>

echo '</select></td>
<td> <input type="checkbox" name="cbbasap'.$chargedbybuildid.'" value="1" '; 
if ($row['cbbasap']>0) { echo ' checked'; } 
echo ' > </td>
<td> <input type="checkbox" name="cbbcargo'.$chargedbybuildid.'" value="1" ';
 if ($row['cbbcargo']>0) { echo 'checked';} 
 echo ' > </td>
<td><input type="text" class="ui-state-default ui-corner-all" name="cbbcomment'.$chargedbybuildid.'" size="65" maxlength="100" value=" '.$cbbcomment.'"></td>
</tr>';

$idmax=$idmax+1; 

// echo $idmax;
}

if ($idmax<'21') {
echo '<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>
<tr>
<td><input class="ui-state-default ui-corner-all" type="text" name="cbborder'.$idmax.'" size="3" maxlength="3" value=" "></td>
<td> <input type="hidden" name="chargedbybuildid'.$idmax.'" value=" '.$idmax.'">'.$idmax.'</td>
<td><input class="ui-state-default ui-corner-all" type="text" name="cbbname'.$idmax.'" size="30" maxlength="30" value=" "></td>
<td>';
 echo ' &'. $globalprefrow['currencysymbol']; 
echo ' <input class="ui-state-default ui-corner-all" type="text" name="cbbcost'.$idmax.'" size="7" maxlength="8" value=" ">';
 echo ' % '; 
echo '</td>
<td><select class="ui-state-default ui-corner-left" name="cbbmod'.$idmax.'">';
 echo  '<option  SELECTED value="+">Added to total</option><option value="x">Multiplied as percentage</option>'; 

// <input type="text" name="cbbmod'.$idmax.'" size="3" maxlength="3" value=" '.$cbbmod.' "></td>

echo '</select></td>
<td> <input type="checkbox" name="cbbasap'.$chargedbybuildid.'" value="1" '; 
if ($row['cbbasap']>0) { echo ' checked'; } 
echo ' > </td>
<td> <input type="checkbox" name="cbbcargo'.$chargedbybuildid.'" value="1" ';
 if ($row['cbbcargo']>0) { echo 'checked';} 
 echo ' > </td>

<td><input class="ui-state-default ui-corner-all" type="text" name="cbbcomment'.$idmax.'" size="65" maxlength="100" value=" "></td>
</tr>
<input type="hidden" name="new'.$idmax.'" value="yes">
';


echo '<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>';

}




echo '
</tbody>
</table>


</p></div></div><br />
<button type="submit"> Edit </button>
';

?><br />

<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="padding: 1em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				
				
To add a custom charge (there can be 20 IDs in total), make sure it has a name.
<br />Pricing to exclude any VAT or tax element.  This and other factors are defined within the services.
<br />On calculating the price, the order set here will define which price modifier is processed first.
<br />Value for cost and percentage can be both positive and negative.
<br />Percentages are calculated as 100 being 100% of the cost of the step before, 150 would be price in previous step +50%.
<br />Items with a zero cost will not be displayed (except First and subsequent mileage), use this as a means of disabling in the menus.
<br />Items set to 100% will not change the price, however will be checkable within the menus.
<br />The ASAP and cargobike fields are used to highlight jobs for scheduling purposes.

</p>
			</div>
		</div><br />
		

</form>
<div class="line"></div><br /></div>
<?php 

include 'footer.php';

mysql_close(); ?>
</body></html>