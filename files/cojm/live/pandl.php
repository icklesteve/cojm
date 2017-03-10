<?php 

$alpha_time = microtime(TRUE);

error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }

include 'changejob.php';

$invoicemenu ="1";
$filename='pandl.php';
$adminmenu = "0";

if (isset($_GET['year'])) {

$year=$_GET['year'];
$showyear=$_GET['year']; } else {

 $showyear =date("Y"); 
 $year =date("Y"); }
 
 
if (isset($_GET['incomeselect'])) { 
$incomeselect=$_GET['incomeselect'];

} else { 

$incomeselect='invoicein';
}


 
 

$fromyear=($showyear-'1');
$toyear=($showyear+'1');
$collectionsfromdate = $fromyear . "-12-31 23:59:59";
$collectionsuntildate = $toyear . "-01-01 00:00:01";


$licost='';
$tablecost='';
$lico='';
$unlicost='';
$unlico='';
$batchcount='';
$rmico='';
$rmcost='';
$jan='';
$jan1='';
$jan2='';
$jan3='';
$jan4='';
$jan5='';
$jan6='';
$jan7='';
$jan8='';
$jan9='';
$jan10='';
$fjan='';

$feb='';
$feb1='';
$feb2='';
$feb3='';
$feb4='';
$feb5='';
$feb6='';
$feb7='';
$feb8='';
$feb9='';
$feb10='';

$ffeb='';




$mar='';
$mar1='';
$mar2='';
$mar3='';
$mar4='';
$mar5='';
$mar6='';
$mar7='';
$mar8='';
$mar9='';
$mar10='';
$fmar='';



$apr='';
$apr1='';
$apr2='';
$apr3='';
$apr4='';
$apr5='';
$apr6='';
$apr7='';
$apr8='';
$apr9='';
$apr10='';
$fapr='';



$may='';
$may1='';
$may2='';
$may3='';
$may4='';
$may5='';
$may6='';
$may7='';
$may8='';
$may9='';
$may10='';
$fmay='';



$jun='';
$jun1='';
$jun2='';
$jun3='';
$jun4='';
$jun5='';
$jun6='';
$jun7='';
$jun8='';
$jun9='';
$jun10='';
$fjun='';





$jul='';
$jul1='';
$jul2='';
$jul3='';
$jul4='';
$jul5='';
$jul6='';
$jul7='';
$jul8='';
$jul9='';
$jul10='';
$fjul='';




$aug='';
$aug1='';
$aug2='';
$aug3='';
$aug4='';
$aug5='';
$aug6='';
$aug7='';
$aug8='';
$aug9='';
$aug10='';
$faug='';



$sep='';
$sep1='';
$sep2='';
$sep3='';
$sep4='';
$sep5='';
$sep6='';
$sep7='';
$sep8='';
$sep9='';
$sep10='';
$fsep='';


$oct='';
$oct1='';
$oct2='';
$oct3='';
$oct4='';
$oct5='';
$oct6='';
$oct7='';
$oct8='';
$oct9='';
$oct10='';
$foct='';


$nov='';
$nov1='';
$nov2='';
$nov3='';
$nov4='';
$nov5='';
$nov6='';
$nov7='';
$nov8='';
$nov9='';
$nov10='';
$fnov='';


$dec='';
$dec1='';
$dec2='';
$dec3='';
$dec4='';
$dec5='';
$dec6='';
$dec7='';
$dec8='';
$dec9='';
$dec10='';
$fdec='';


$finjan='';
$finfeb='';
$finmar='';
$finapr='';
$finmay='';
$finjun='';
$finjul='';
$finaug='';
$finsep='';
$finoct='';
$finnov='';
$findec='';




$janunlicost='';
$febunlicost='';
$marunlicost='';
$aprunlicost='';
$mayunlicost='';
$jununlicost='';
$julunlicost='';
$augunlicost='';
$sepunlicost='';
$octunlicost='';
$novunlicost='';
$decunlicost='';


$fjanunlicost='';
$ffebunlicost='';
$fmarunlicost='';
$faprunlicost='';
$fmayunlicost='';
$fjununlicost='';
$fjulunlicost='';
$faugunlicost='';
$fsepunlicost='';
$foctunlicost='';
$fnovunlicost='';
$fdecunlicost='';





$janlicost='';
$feblicost='';
$marlicost='';
$aprlicost='';
$maylicost='';
$junlicost='';
$jullicost='';
$auglicost='';
$seplicost='';
$octlicost='';
$novlicost='';
$declicost='';



$fjanlicost='';
$ffeblicost='';
$fmarlicost='';
$faprlicost='';
$fmaylicost='';
$fjunlicost='';
$fjullicost='';
$fauglicost='';
$fseplicost='';
$foctlicost='';
$fnovlicost='';
$fdeclicost='';



$janhourcost='';
$febhourcost='';
$marhourcost='';
$aprhourcost='';
$mayhourcost='';
$junhourcost='';
$julhourcost='';
$aughourcost='';
$sephourcost='';
$octhourcost='';
$novhourcost='';
$dechourcost='';



$fjanhourcost='';
$ffebhourcost='';
$fmarhourcost='';
$faprhourcost='';
$fmayhourcost='';
$fjunhourcost='';
$fjulhourcost='';
$faughourcost='';
$fsephourcost='';
$focthourcost='';
$fnovhourcost='';
$fdechourcost='';



$injan='';
$infeb='';
$inmar='';
$inapr='';
$inmay='';
$injun='';
$injul='';
$inaug='';
$insep='';
$inoct='';
$innov='';
$indec='';



$vatjan='';
$vatfeb='';
$vatmar='';
$vatapr='';
$vatmay='';
$vatjun='';
$vatjul='';
$vataug='';
$vatsep='';
$vatoct='';
$vatnov='';
$vatdec='';



$janother='';
$febother='';
$marother='';
$aprother='';
$mayother='';
$junother='';
$julother='';
$augother='';
$sepother='';
$octother='';
$novother='';
$decother='';


$evatjan='';
$evatfeb='';
$evatmar='';
$evatapr='';
$evatmay='';
$evatjun='';
$evatjul='';
$evataug='';
$evatsep='';
$evatoct='';
$evatnov='';
$evatdec='';


?><!DOCTYPE html> 
<html lang="en"> 
<head> 
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" >
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<?php echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>'; ?>
<title>COJM <?php print ($showyear); ?> Profit and Loss</title>
</head>
<body >
<?php 

$invoicemenu='1';

include "cojmmenu.php"; ?><div class="Post">


<div class="ui-state-highlight ui-corner-all" style="padding: 0.5em;">
  <?
    $day = "";
// echo $year;
      	//get day timestamp for feburary 29 for this year
    	$day =  date("d", mktime(0, 0, 0, 2, 29, date($year)));	
    	/*
    		check if day equals 29. 
    		If day is 29 then it must be the leap year. if day is 01, then it not a leap year.
    	*/
    	if($day == 29)
    	{
    		$leapyear='29';
    	} else { $leapyear='28'; }
//    echo "leap year is $leapyear";
   ?>
   <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="get"> 
<?php
 echo ' <select class="ui-state-default ui-corner-left" name="year">';

$i=$showyear-4; echo '<option> '.$i;
$i++; echo "<option> $i\n"; 
$i++; echo "<option> $i\n"; 
$i++; echo "<option> $i\n"; 
$i++; 
echo "<option selected> $i\n"; 
$i++; echo "<option> $i\n"; 
$i++; echo "<option> $i\n";
$i++; echo "<option> $i\n";
$i++; echo "<option> $i\n"; 
echo "</select>\n"; 
?>


<select class="ui-state-default ui-corner-left" name="incomeselect">
<option <?php if ($incomeselect=='invoicein') { echo ' selected '; } ?> value="invoicein">View by Reconciled Invoice</option>
<option <?php if ($incomeselect=='tarcollect') { echo ' selected '; } ?> value="tarcollect">Operations View</option>
</select>





<button type="submit" > Go ! </button></form>
<?php 


// <option <?php if ($incomeselect=='invoiceout') { echo ' selected '; } echo value="invoiceout">View by Invoice Sent</option>';
 


// echo $searchexpensecode;
// if ($showyear) { 

echo '</div> <br />';

// echo '<br>From : '.$collectionsfromdate.' To : '.$collectionsuntildate;

$sql = "SELECT expensedate, expensecost, expensecode, expensevat, paid FROM expenses WHERE expensedate >= ? AND expensedate <= ? ";

$prep = $dbh->prepare($sql);
$prep->execute([$collectionsfromdate,$collectionsuntildate]);
$stmt = $prep->fetchAll();
foreach ($stmt as $row)  {
    

	 $m=date('m', strtotime($row['expensedate']));
	 if ($row['paid']<1) {
	 //  Total expense = total, vat element phrased as "of which VAT "
	 
	 
if ($m=='01') { $fjan=$fjan + $row['expensecost']; }
if ($m=='02') { $ffeb=$ffeb + $row['expensecost']; }	 
if ($m=='03') { $fmar=$fmar + $row['expensecost']; }
if ($m=='04') { $fapr=$fapr + $row['expensecost']; }
if ($m=='05') { $fmay=$fmay + $row['expensecost']; }
if ($m=='06') { $fjun=$fjun + $row['expensecost']; }
if ($m=='07') { $fjul=$fjul + $row['expensecost']; }
if ($m=='08') { $faug=$faug + $row['expensecost']; }
if ($m=='09') { $fsep=$fsep + $row['expensecost']; }
if ($m=='10') { $foct=$foct + $row['expensecost']; }
if ($m=='11') { $fnov=$fnov + $row['expensecost']; }
if ($m=='12') { $fdec=$fdec + $row['expensecost']; }	
	 } else {
// expenses 
if ($m=='01') { $jan=$jan + $row['expensecost']; $evatjan=$evatjan+$row['expensevat']; if ($row['expensecode']==1) {$jan1=$jan1+$row['expensecost'];} if ($row['expensecode']==2) {$jan2=$jan2+$row['expensecost'];}  if ($row['expensecode']==3) {$jan3=$jan3+$row['expensecost'];} if ($row['expensecode']==4) {$jan4=$jan4+$row['expensecost'];} if ($row['expensecode']==5) {$jan5=$jan5+$row['expensecost'];} if ($row['expensecode']==6) {$jan6=$jan6+$row['expensecost'];} if ($row['expensecode']==7) {$jan7=$jan7+$row['expensecost'];} if ($row['expensecode']==8) {$jan8=$jan8+$row['expensecost'];} if ($row['expensecode']==9) {$jan9=$jan9+$row['expensecost'];} if ($row['expensecode']==10) {$jan10=$jan10+$row['expensecost'];}   }
if ($m=='02') { $feb=$feb + $row['expensecost']; $evatfeb=$evatfeb+$row['expensevat']; if ($row['expensecode']==1) {$feb1=$feb1+$row['expensecost'];} if ($row['expensecode']==2) {$feb2=$feb2+$row['expensecost'];}  if ($row['expensecode']==3) {$feb3=$feb3+$row['expensecost'];} if ($row['expensecode']==4) {$feb4=$feb4+$row['expensecost'];} if ($row['expensecode']==5) {$feb5=$feb5+$row['expensecost'];} if ($row['expensecode']==6) {$feb6=$feb6+$row['expensecost'];} if ($row['expensecode']==7) {$feb7=$feb7+$row['expensecost'];} if ($row['expensecode']==8) {$feb8=$feb8+$row['expensecost'];} if ($row['expensecode']==9) {$feb9=$feb9+$row['expensecost'];} if ($row['expensecode']==10) {$feb10=$feb10+$row['expensecost'];}   }	 
if ($m=='03') { $mar=$mar + $row['expensecost']; $evatmar=$evatmar+$row['expensevat']; if ($row['expensecode']==1) {$mar1=$mar1+$row['expensecost'];} if ($row['expensecode']==2) {$mar2=$mar2+$row['expensecost'];}  if ($row['expensecode']==3) {$mar3=$mar3+$row['expensecost'];} if ($row['expensecode']==4) {$mar4=$mar4+$row['expensecost'];} if ($row['expensecode']==5) {$mar5=$mar5+$row['expensecost'];} if ($row['expensecode']==6) {$mar6=$mar6+$row['expensecost'];} if ($row['expensecode']==7) {$mar7=$mar7+$row['expensecost'];} if ($row['expensecode']==8) {$mar8=$mar8+$row['expensecost'];} if ($row['expensecode']==9) {$mar9=$mar9+$row['expensecost'];} if ($row['expensecode']==10) {$mar10=$mar10+$row['expensecost'];}   }
if ($m=='04') { $apr=$apr + $row['expensecost']; $evatapr=$evatapr+$row['expensevat']; if ($row['expensecode']==1) {$apr1=$apr1+$row['expensecost'];} if ($row['expensecode']==2) {$apr2=$apr2+$row['expensecost'];}  if ($row['expensecode']==3) {$apr3=$apr3+$row['expensecost'];} if ($row['expensecode']==4) {$apr4=$apr4+$row['expensecost'];} if ($row['expensecode']==5) {$apr5=$apr5+$row['expensecost'];} if ($row['expensecode']==6) {$apr6=$apr6+$row['expensecost'];} if ($row['expensecode']==7) {$apr7=$apr7+$row['expensecost'];} if ($row['expensecode']==8) {$apr8=$apr8+$row['expensecost'];} if ($row['expensecode']==9) {$apr9=$apr9+$row['expensecost'];} if ($row['expensecode']==10) {$apr10=$apr10+$row['expensecost'];}   }
if ($m=='05') { $may=$may + $row['expensecost']; $evatmay=$evatmay+$row['expensevat']; if ($row['expensecode']==1) {$may1=$may1+$row['expensecost'];} if ($row['expensecode']==2) {$may2=$may2+$row['expensecost'];}  if ($row['expensecode']==3) {$may3=$may3+$row['expensecost'];} if ($row['expensecode']==4) {$may4=$may4+$row['expensecost'];} if ($row['expensecode']==5) {$may5=$may5+$row['expensecost'];} if ($row['expensecode']==6) {$may6=$may6+$row['expensecost'];} if ($row['expensecode']==7) {$may7=$may7+$row['expensecost'];} if ($row['expensecode']==8) {$may8=$may8+$row['expensecost'];} if ($row['expensecode']==9) {$may9=$may9+$row['expensecost'];} if ($row['expensecode']==10) {$may10=$may10+$row['expensecost'];}   }
if ($m=='06') { $jun=$jun + $row['expensecost']; $evatjun=$evatjun+$row['expensevat']; if ($row['expensecode']==1) {$jun1=$jun1+$row['expensecost'];} if ($row['expensecode']==2) {$jun2=$jun2+$row['expensecost'];}  if ($row['expensecode']==3) {$jun3=$jun3+$row['expensecost'];} if ($row['expensecode']==4) {$jun4=$jun4+$row['expensecost'];} if ($row['expensecode']==5) {$jun5=$jun5+$row['expensecost'];} if ($row['expensecode']==6) {$jun6=$jun6+$row['expensecost'];} if ($row['expensecode']==7) {$jun7=$jun7+$row['expensecost'];} if ($row['expensecode']==8) {$jun8=$jun8+$row['expensecost'];} if ($row['expensecode']==9) {$jun9=$jun9+$row['expensecost'];} if ($row['expensecode']==10) {$jun10=$jun10+$row['expensecost'];}   }
if ($m=='07') { $jul=$jul + $row['expensecost']; $evatjul=$evatjul+$row['expensevat']; if ($row['expensecode']==1) {$jul1=$jul1+$row['expensecost'];} if ($row['expensecode']==2) {$jul2=$jul2+$row['expensecost'];}  if ($row['expensecode']==3) {$jul3=$jul3+$row['expensecost'];} if ($row['expensecode']==4) {$jul4=$jul4+$row['expensecost'];} if ($row['expensecode']==5) {$jul5=$jul5+$row['expensecost'];} if ($row['expensecode']==6) {$jul6=$jul6+$row['expensecost'];} if ($row['expensecode']==7) {$jul7=$jul7+$row['expensecost'];} if ($row['expensecode']==8) {$jul8=$jul8+$row['expensecost'];} if ($row['expensecode']==9) {$jul9=$jul9+$row['expensecost'];} if ($row['expensecode']==10) {$jul10=$jul10+$row['expensecost'];}   }
if ($m=='08') { $aug=$aug + $row['expensecost']; $evataug=$evataug+$row['expensevat']; if ($row['expensecode']==1) {$aug1=$aug1+$row['expensecost'];} if ($row['expensecode']==2) {$aug2=$aug2+$row['expensecost'];}  if ($row['expensecode']==3) {$aug3=$aug3+$row['expensecost'];} if ($row['expensecode']==4) {$aug4=$aug4+$row['expensecost'];} if ($row['expensecode']==5) {$aug5=$aug5+$row['expensecost'];} if ($row['expensecode']==6) {$aug6=$aug6+$row['expensecost'];} if ($row['expensecode']==7) {$aug7=$aug7+$row['expensecost'];} if ($row['expensecode']==8) {$aug8=$aug8+$row['expensecost'];} if ($row['expensecode']==9) {$aug9=$aug9+$row['expensecost'];} if ($row['expensecode']==10) {$aug10=$aug10+$row['expensecost'];}   }
if ($m=='09') { $sep=$sep + $row['expensecost']; $evatsep=$evatsep+$row['expensevat']; if ($row['expensecode']==1) {$sep1=$sep1+$row['expensecost'];} if ($row['expensecode']==2) {$sep2=$sep2+$row['expensecost'];}  if ($row['expensecode']==3) {$sep3=$sep3+$row['expensecost'];} if ($row['expensecode']==4) {$sep4=$sep4+$row['expensecost'];} if ($row['expensecode']==5) {$sep5=$sep5+$row['expensecost'];} if ($row['expensecode']==6) {$sep6=$sep6+$row['expensecost'];} if ($row['expensecode']==7) {$sep7=$sep7+$row['expensecost'];} if ($row['expensecode']==8) {$sep8=$sep8+$row['expensecost'];} if ($row['expensecode']==9) {$sep9=$sep9+$row['expensecost'];} if ($row['expensecode']==10) {$sep10=$sep10+$row['expensecost'];}   }
if ($m=='10') { $oct=$oct + $row['expensecost']; $evatoct=$evatoct+$row['expensevat']; if ($row['expensecode']==1) {$oct1=$oct1+$row['expensecost'];} if ($row['expensecode']==2) {$oct2=$oct2+$row['expensecost'];}  if ($row['expensecode']==3) {$oct3=$oct3+$row['expensecost'];} if ($row['expensecode']==4) {$oct4=$oct4+$row['expensecost'];} if ($row['expensecode']==5) {$oct5=$oct5+$row['expensecost'];} if ($row['expensecode']==6) {$oct6=$oct6+$row['expensecost'];} if ($row['expensecode']==7) {$oct7=$oct7+$row['expensecost'];} if ($row['expensecode']==8) {$oct8=$oct8+$row['expensecost'];} if ($row['expensecode']==9) {$oct9=$oct9+$row['expensecost'];} if ($row['expensecode']==10) {$oct10=$oct10+$row['expensecost'];}   }
if ($m=='11') { $nov=$nov + $row['expensecost']; $evatnov=$evatnov+$row['expensevat']; if ($row['expensecode']==1) {$nov1=$nov1+$row['expensecost'];} if ($row['expensecode']==2) {$nov2=$nov2+$row['expensecost'];}  if ($row['expensecode']==3) {$nov3=$nov3+$row['expensecost'];} if ($row['expensecode']==4) {$nov4=$nov4+$row['expensecost'];} if ($row['expensecode']==5) {$nov5=$nov5+$row['expensecost'];} if ($row['expensecode']==6) {$nov6=$nov6+$row['expensecost'];} if ($row['expensecode']==7) {$nov7=$nov7+$row['expensecost'];} if ($row['expensecode']==8) {$nov8=$nov8+$row['expensecost'];} if ($row['expensecode']==9) {$nov9=$nov9+$row['expensecost'];} if ($row['expensecode']==10) {$nov10=$nov10+$row['expensecost'];}   }
if ($m=='12') { $dec=$dec + $row['expensecost']; $evatdec=$evatdec+$row['expensevat']; if ($row['expensecode']==1) {$dec1=$dec1+$row['expensecost'];} if ($row['expensecode']==2) {$dec2=$dec2+$row['expensecost'];}  if ($row['expensecode']==3) {$dec3=$dec3+$row['expensecost'];} if ($row['expensecode']==4) {$dec4=$dec4+$row['expensecost'];} if ($row['expensecode']==5) {$dec5=$dec5+$row['expensecost'];} if ($row['expensecode']==6) {$dec6=$dec6+$row['expensecost'];} if ($row['expensecode']==7) {$dec7=$dec7+$row['expensecost'];} if ($row['expensecode']==8) {$dec8=$dec8+$row['expensecost'];} if ($row['expensecode']==9) {$dec9=$dec9+$row['expensecost'];} if ($row['expensecode']==10) {$dec10=$dec10+$row['expensecost'];}   }
	 }
	 }

// VAT element of expense, not added to total 	 
$evattot=$evatjan+$evatfeb+$evatmar+$evatapr+$evatmay+$evatjun+$evatjul+$evataug+$evatsep+$evatoct+$evatnov+$evatdec;
	 


	 if ($incomeselect=='tarcollect') { 

	 
// completeish jobs

 $sql = "SELECT targetcollectiondate, vatcharge, FreightCharge, LicensedCount, UnlicensedCount, hourlyothercount FROM Orders 
 INNER JOIN Services ON Orders.ServiceID = Services.ServiceID 
 WHERE targetcollectiondate >= ? AND targetcollectiondate <= ? AND status > 59 ";


$prep = $dbh->prepare($sql);
$prep->execute([$collectionsfromdate,$collectionsuntildate]);
$stmt = $prep->fetchAll();
foreach ($stmt as $row)  {
$tablecost = $tablecost + $row["FreightCharge"];
$m='';
$m=date('m', strtotime($row['targetcollectiondate']));

if ($m=='01') { $vatjan=$vatjan+$row['vatcharge']; $injan=$injan + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $janlicost = $janlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $janunlicost = $janunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $janhourcost = $janhourcost + $row["FreightCharge"];} else { $janother=$janother+$row["FreightCharge"]; } }
if ($m=='02') { $vatfeb=$vatfeb+$row['vatcharge']; $infeb=$infeb + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $feblicost = $feblicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $febunlicost = $febunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $febhourcost = $febhourcost + $row["FreightCharge"];} else { $febother=$febother+$row["FreightCharge"]; } }
if ($m=='03') { $vatmar=$vatmar+$row['vatcharge']; $inmar=$inmar + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $marlicost = $marlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $marunlicost = $marunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $marhourcost = $marhourcost + $row["FreightCharge"];} else { $marother=$marother+$row["FreightCharge"]; } }
if ($m=='04') { $vatapr=$vatapr+$row['vatcharge']; $inapr=$inapr + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $aprlicost = $aprlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $aprunlicost = $aprunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $aprhourcost = $aprhourcost + $row["FreightCharge"];} else { $aprother=$aprother+$row["FreightCharge"]; } }
if ($m=='05') { $vatmay=$vatmay+$row['vatcharge']; $inmay=$inmay + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $maylicost = $maylicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $mayunlicost = $mayunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $mayhourcost = $mayhourcost + $row["FreightCharge"];} else { $mayother=$mayother+$row["FreightCharge"]; } }
if ($m=='06') { $vatjun=$vatjun+$row['vatcharge']; $injun=$injun + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $junlicost = $junlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $jununlicost = $jununlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $junhourcost = $junhourcost + $row["FreightCharge"];} else { $junother=$junother+$row["FreightCharge"]; } }
if ($m=='07') { $vatjul=$vatjul+$row['vatcharge']; $injul=$injul + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $jullicost = $jullicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $julunlicost = $julunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $julhourcost = $julhourcost + $row["FreightCharge"];} else { $julother=$julother+$row["FreightCharge"]; } }
if ($m=='08') { $vataug=$vataug+$row['vatcharge']; $inaug=$inaug + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $auglicost = $auglicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $augunlicost = $augunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $aughourcost = $aughourcost + $row["FreightCharge"];} else { $augother=$augother+$row["FreightCharge"]; } }
if ($m=='09') { $vatsep=$vatsep+$row['vatcharge']; $insep=$insep + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $seplicost = $seplicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $sepunlicost = $sepunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $sephourcost = $sephourcost + $row["FreightCharge"];} else { $sepother=$sepother+$row["FreightCharge"]; } }
if ($m=='10') { $vatoct=$vatoct+$row['vatcharge']; $inoct=$inoct + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $octlicost = $octlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $octunlicost = $octunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $octhourcost = $octhourcost + $row["FreightCharge"];} else { $octother=$octother+$row["FreightCharge"]; } }
if ($m=='11') { $vatnov=$vatnov+$row['vatcharge']; $innov=$innov + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $novlicost = $novlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $novunlicost = $novunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $novhourcost = $novhourcost + $row["FreightCharge"];} else { $novother=$novother+$row["FreightCharge"]; } }
if ($m=='12') { $vatdec=$vatdec+$row['vatcharge']; $indec=$indec + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $declicost = $declicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $decunlicost = $decunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $dechourcost = $dechourcost + $row["FreightCharge"];} else { $decother=$decother+$row["FreightCharge"]; } }
		 
 }
 

 
$sql = "SELECT targetcollectiondate, vatcharge, FreightCharge, LicensedCount, UnlicensedCount, hourlyothercount FROM Orders 
INNER JOIN Services ON Orders.ServiceID = Services.ServiceID 
WHERE targetcollectiondate >= ? AND targetcollectiondate <= ? AND status < 60 ";

$prep = $dbh->prepare($sql);
$prep->execute([$collectionsfromdate,$collectionsuntildate]);
$stmt = $prep->fetchAll();
foreach ($stmt as $row)  {
    
$tablecost = $tablecost + $row["FreightCharge"];
$m=date('m', strtotime($row['targetcollectiondate']));

if ($m=='01') { $vatjan=$vatjan+$row['vatcharge']; $finjan=$finjan + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $fjanlicost = $fjanlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $fjanunlicost = $fjanunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $fjanhourcost = $fjanhourcost + $row["FreightCharge"];} else { $janother=$janother+$row["FreightCharge"]; } }
if ($m=='02') { $vatfeb=$vatfeb+$row['vatcharge']; $finfeb=$finfeb + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $ffeblicost = $ffeblicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $ffebunlicost = $ffebunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $ffebhourcost = $ffebhourcost + $row["FreightCharge"];} else { $febother=$febother+$row["FreightCharge"]; } }
if ($m=='03') { $vatmar=$vatmar+$row['vatcharge']; $finmar=$finmar + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $fmarlicost = $fmarlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $fmarunlicost = $fmarunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $fmarhourcost = $fmarhourcost + $row["FreightCharge"];} else { $marother=$marother+$row["FreightCharge"]; } }
if ($m=='04') { $vatapr=$vatapr+$row['vatcharge']; $finapr=$finapr + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $faprlicost = $faprlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $faprunlicost = $faprunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $faprhourcost = $faprhourcost + $row["FreightCharge"];} else { $aprother=$aprother+$row["FreightCharge"]; } }
if ($m=='05') { $vatmay=$vatmay+$row['vatcharge']; $finmay=$finmay + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $fmaylicost = $fmaylicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $fmayunlicost = $fmayunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $fmayhourcost = $fmayhourcost + $row["FreightCharge"];} else { $mayother=$mayother+$row["FreightCharge"]; } }
if ($m=='06') { $vatjun=$vatjun+$row['vatcharge']; $finjun=$finjun + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $fjunlicost = $fjunlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $fjununlicost = $fjununlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $fjunhourcost = $fjunhourcost + $row["FreightCharge"];} else { $junother=$junother+$row["FreightCharge"]; } }
if ($m=='07') { $vatjul=$vatjul+$row['vatcharge']; $finjul=$finjul + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $fjullicost = $fjullicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $fjulunlicost = $fjulunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $fjulhourcost = $fjulhourcost + $row["FreightCharge"];} else { $julother=$julother+$row["FreightCharge"]; } }
if ($m=='08') { $vataug=$vataug+$row['vatcharge']; $finaug=$finaug + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $fauglicost = $fauglicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $faugunlicost = $faugunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $faughourcost = $faughourcost + $row["FreightCharge"];} else { $augother=$augother+$row["FreightCharge"]; } }
if ($m=='09') { $vatsep=$vatsep+$row['vatcharge']; $finsep=$finsep + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $fseplicost = $fseplicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $fsepunlicost = $fsepunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $fsephourcost = $fsephourcost + $row["FreightCharge"];} else { $sepother=$sepother+$row["FreightCharge"]; } }
if ($m=='10') { $vatoct=$vatoct+$row['vatcharge']; $finoct=$finoct + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $foctlicost = $foctlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $foctunlicost = $foctunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $focthourcost = $focthourcost + $row["FreightCharge"];} else { $octother=$octother+$row["FreightCharge"]; } }
if ($m=='11') { $vatnov=$vatnov+$row['vatcharge']; $finnov=$finnov + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $fnovlicost = $fnovlicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $fnovunlicost = $fnovunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $fnovhourcost = $fnovhourcost + $row["FreightCharge"];} else { $novother=$novother+$row["FreightCharge"]; } }
if ($m=='12') { $vatdec=$vatdec+$row['vatcharge']; $findec=$findec + $row['FreightCharge']+$row['vatcharge']; if ($row["LicensedCount"] >'0') { $fdeclicost = $fdeclicost + $row["FreightCharge"];} else if ($row["UnlicensedCount"] >'0') { $fdecunlicost = $fdecunlicost + $row["FreightCharge"];} else if ($row["hourlyothercount"] >'0') { $fdechourcost = $fdechourcost + $row["FreightCharge"];} else { $decother=$decother+$row["FreightCharge"]; } }

 }
 
 
 // ends if ($incomeselect=='tarcollect') { 
	 }




	 
 $vattot=$vatjan+$vatfeb+$vatmar+$vatapr+$vatmay+$vatjun+$vatjul+$vataug+$vatsep+$vatoct+$vatnov+$vatdec;
 
 $totother=$janother+$febother+$marother+$aprother+$mayother+$junother+$julother+$augother+$sepother+$octother+$novother+$decother;
 
 $fintot=$finjan+$finfeb+$finmar+$finapr+$finmay+$finjun+$finjul+$finaug+$finsep+$finoct+$finnov+$findec;
 
 $tot8=$jan8+$feb8+$mar8+$apr8+$may8+$jun8+$jul8+$aug8+$sep8+$oct8+$nov8+$dec8;
 $tot7=$jan7+$feb7+$mar7+$apr7+$may7+$jun7+$jul7+$aug7+$sep7+$oct7+$nov7+$dec7; 
 $tot5=$jan5+$feb5+$mar5+$apr5+$may5+$jun5+$jul5+$aug5+$sep5+$oct5+$nov5+$dec5;
 $tot4=$jan4+$feb4+$mar4+$apr4+$may4+$jun4+$jul4+$aug4+$sep4+$oct4+$nov4+$dec4; 
 $tot2=$jan2+$feb2+$mar2+$apr2+$may2+$jun2+$jul2+$aug2+$sep2+$oct2+$nov2+$dec2;
 $tot6=$jan6+$feb6+$mar6+$apr6+$may6+$jun6+$jul6+$aug6+$sep6+$oct6+$nov6+$dec6; 
 $tot1=$jan1+$feb1+$mar1+$apr1+$may1+$jun1+$jul1+$aug1+$sep1+$oct1+$nov1+$dec1; 
 $tot3=$jan3+$feb3+$mar3+$apr3+$may3+$jun3+$jul3+$aug3+$sep3+$oct3+$nov3+$dec3; 
 $tot9=$jan9+$feb9+$mar9+$apr9+$may9+$jun9+$jul9+$aug9+$sep9+$oct9+$nov9+$dec9; 
 $tot10=$jan10+$feb10+$mar10+$apr10+$may10+$jun10+$jul10+$aug10+$sep10+$oct10+$nov10+$dec10; 

$ftotexp=$fjan+$ffeb+$fmar+$fapr+$fmay+$fjun+$fjul+$faug+$fsep+$foct+$fnov+$fdec;
$totcompleteexpense=$jan+$feb+$mar+$apr+$may+$jun+$jul+$aug+$sep+$oct+$nov+$dec;
$exptot=$ftotexp+$totcompleteexpense; 
 
 
 $totunlicost=$janunlicost+$febunlicost+$marunlicost+$aprunlicost+$mayunlicost+$jununlicost+$julunlicost+$augunlicost+$sepunlicost+$octunlicost+$novunlicost+$decunlicost+$fjanunlicost+$ffebunlicost+$fmarunlicost+$faprunlicost+$fmayunlicost+$fjununlicost+$fjulunlicost+$faugunlicost+$fsepunlicost+$foctunlicost+$fnovunlicost+$fdecunlicost;      
 $totlicost=$janlicost+$feblicost+$marlicost+$aprlicost+$maylicost+$junlicost+$jullicost+$auglicost+$seplicost+$octlicost+$novlicost+$declicost+$fjanlicost+$ffeblicost+$fmarlicost+$faprlicost+$fmaylicost+$fjunlicost+$fjullicost+$fauglicost+$fseplicost+$foctlicost+$fnovlicost+$fdeclicost;
 
 $tothourcost=$janhourcost+$febhourcost+$marhourcost+$aprhourcost+$mayhourcost+$junhourcost+$julhourcost+$aughourcost+$sephourcost+$octhourcost+$novhourcost+$dechourcost;  
 $ftothourcost=$fjanhourcost+$ffebhourcost+$fmarhourcost+$faprhourcost+$fmayhourcost+$fjunhourcost+$fjulhourcost+$faughourcost+$fsephourcost+$focthourcost+$fnovhourcost+$fdechourcost;
 
 $intot=$injan+$infeb+$inmar+$inapr+$inmay+$injun+$injul+$inaug+$insep+$inoct+$innov+$indec;  

$ftotunlicost=$fjanunlicost+$ffebunlicost+$fmarunlicost+$faprunlicost+$fmayunlicost+$fjununlicost+$fjulunlicost+$faugunlicost+$fsepunlicost+$foctunlicost+$fnovunlicost+$decunlicost; 
$ftotlicost=$fjanlicost+$ffeblicost+$fmarlicost+$faprlicost+$fmaylicost+$fjunlicost+$fjullicost+$fauglicost+$fseplicost+$foctlicost+$fnovlicost+$declicost; 

 // Licensed : <?php echo  $lico . ' at &#xa3;'; echo number_format(($licost / $lico), 2, '.', ','); 
// Unlicensed : <?php echo $unlico; closephp at &#xa3;<?php echo number_format(($unlicost / $unlico), 2, '.', ','); 
// Both : &#xa3;<?php echo number_format((( ($licost+$unlicost) / ($lico+$unlico) )), 2, '.', ','); 





















?> 
<table style="width:100%;   font-weight: bold;" class="acc alignright" ><tbody>
<tr>
<th class="rh" scope="col"></th>
<th class="rh" scope="col">January</th>
<th class="rh" scope="col">February</th>
<th class="rh" scope="col">March</th>
<th class="rh" scope="col">April</th>
<th class="rh" scope="col">May</th>
<th class="rh" scope="col">June</th>
<th class="rh" scope="col">July</th>
<th class="rh" scope="col">August</th>
<th class="rh" scope="col">September</th>
<th class="rh" scope="col">October</th>
<th class="rh" scope="col">November</th>
<th class="rh" scope="col">December</th>
<th class="rh" scope="col">Total</th>
</tr>
<tr><td colspan="14" ><div class="line"></div></td></tr>
<?php  


if ($incomeselect=='invoicein') {
 
$sql = "SELECT * FROM invoicing WHERE paydate >= ? AND paydate <= ? ";
$prep = $dbh->prepare($sql);
$prep->execute([$collectionsfromdate,$collectionsuntildate]);
$stmt = $prep->fetchAll();
foreach ($stmt as $row)  {

$m=date('m', strtotime($row['paydate'])); // echo $m;
 
 if ($m=='01') { $injan=$injan+$row['cost']+$row['invvatcost']; $vatjan=$vatjan+ $row['invvatcost']; }
 if ($m=='02') { $infeb=$infeb+$row['cost']+$row['invvatcost']; $vatfeb=$vatfeb+ $row['invvatcost']; }
 if ($m=='03') { $inmar=$inmar+$row['cost']+$row['invvatcost']; $vatmar=$vatmar+ $row['invvatcost']; }
 if ($m=='04') { $inapr=$inapr+$row['cost']+$row['invvatcost']; $vatapr=$vatapr+ $row['invvatcost']; }
 if ($m=='05') { $inmay=$inmay+$row['cost']+$row['invvatcost']; $vatmay=$vatmay+ $row['invvatcost']; }
 if ($m=='06') { $injun=$injun+$row['cost']+$row['invvatcost']; $vatjun=$vatjun+ $row['invvatcost']; }
 if ($m=='07') { $injul=$injul+$row['cost']+$row['invvatcost']; $vatjul=$vatjul+ $row['invvatcost']; }
 if ($m=='08') { $inaug=$inaug+$row['cost']+$row['invvatcost']; $vataug=$vataug+ $row['invvatcost']; }
 if ($m=='09') { $insep=$insep+$row['cost']+$row['invvatcost']; $vatsep=$vatsep+ $row['invvatcost']; }
 if ($m=='10') { $inoct=$inoct+$row['cost']+$row['invvatcost']; $vatoct=$vatoct+ $row['invvatcost']; }
 if ($m=='11') { $innov=$innov+$row['cost']+$row['invvatcost']; $vatnov=$vatnov+ $row['invvatcost']; }
 if ($m=='12') { $indec=$indec+$row['cost']+$row['invvatcost']; $vatdec=$vatdec+ $row['invvatcost']; }

 $intot=$injan+$infeb+$inmar+$inapr+$inmay+$injun+$injul+$inaug+$insep+$inoct+$innov+$indec;   
 $vattot=$vatjan+$vatfeb+$vatmar+$vatapr+$vatmay+$vatjun+$vatjul+$vataug+$vatsep+$vatoct+$vatnov+$vatdec;
 
}

echo '<tr><td>Reconciled Invoices Exc VAT</td>
 <td> '; if ($injan) { echo '&'.$globalprefrow['currencysymbol'].number_format(($injan), 2, '.', ','); } echo '</td>
 <td> '; if ($infeb) { echo '&'.$globalprefrow['currencysymbol'].number_format(($infeb), 2, '.', ','); } echo '</td>
 <td> '; if ($inmar) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inmar), 2, '.', ','); } echo '</td>
 <td> '; if ($inapr) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inapr), 2, '.', ','); } echo '</td>
 <td> '; if ($inmay) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inmay), 2, '.', ','); } echo '</td>
 <td> '; if ($injun) { echo '&'.$globalprefrow['currencysymbol'].number_format(($injun), 2, '.', ','); } echo '</td>
 <td> '; if ($injul) { echo '&'.$globalprefrow['currencysymbol'].number_format(($injul), 2, '.', ','); } echo '</td>
 <td> '; if ($inaug) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inaug), 2, '.', ','); } echo '</td>
 <td> '; if ($insep) { echo '&'.$globalprefrow['currencysymbol'].number_format(($insep), 2, '.', ','); } echo '</td>
 <td> '; if ($inoct) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inoct), 2, '.', ','); } echo '</td>
 <td> '; if ($innov) { echo '&'.$globalprefrow['currencysymbol'].number_format(($innov), 2, '.', ','); } echo '</td>
 <td> '; if ($indec) { echo '&'.$globalprefrow['currencysymbol'].number_format(($indec), 2, '.', ','); } echo '</td> 
 <td> '; if ($intot) { echo '&'.$globalprefrow['currencysymbol'].number_format(($intot), 2, '.', ','); } echo '</td>
</tr>';



if ($vattot) {


?>

<tr><td>Inv Paid VAT</td>

 <td> <?php if ($vatjan) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatjan), 2, '.', ','); } ?></td>
 <td> <?php if ($vatfeb) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatfeb), 2, '.', ','); } ?></td> 
 <td> <?php if ($vatmar) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatmar), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatapr) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatapr), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatmay) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatmay), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatjun) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatjun), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatjul) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatjul), 2, '.', ','); } ?></td>  
 <td> <?php if ($vataug) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vataug), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatsep) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatsep), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatoct) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatoct), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatnov) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatnov), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatdec) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatdec), 2, '.', ','); } ?></td>   
 <td> <?php if ($vattot) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vattot), 2, '.', ','); } ?></td>  
 
</tr>

<?php } ?>

 <tr><td colspan="14" ><div class="line"></div></td></tr>
 
 <?php
 
$injan=$injan+$vatjan;
$infeb=$infeb+$vatfeb;
$inmar=$inmar+$vatmar;
$inapr=$inapr+$vatapr;
$inmay=$inmay+$vatmay;
$injun=$injun+$vatjun;
$injul=$injul+$vatjul;
$inaug=$inaug+$vataug;
$insep=$insep+$vatsep;
$inoct=$inoct+$vatoct;
$innov=$innov+$vatnov;
$indec=$indec+$vatdec;
$intot=$intot+$vattot;




echo '
 <tr>
 <td title="Incl. VAT">Total Net Reconciled</td>

 <td> '; if ($injan) { echo '&'.$globalprefrow['currencysymbol'].number_format(($injan), 2, '.', ','); } echo '</td>
 <td> '; if ($infeb) { echo '&'.$globalprefrow['currencysymbol'].number_format(($infeb), 2, '.', ','); } echo '</td>
 <td> '; if ($inmar) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inmar), 2, '.', ','); } echo '</td>
 <td> '; if ($inapr) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inapr), 2, '.', ','); } echo '</td>
 <td> '; if ($inmay) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inmay), 2, '.', ','); } echo '</td>
 <td> '; if ($injun) { echo '&'.$globalprefrow['currencysymbol'].number_format(($injun), 2, '.', ','); } echo '</td>
 <td> '; if ($injul) { echo '&'.$globalprefrow['currencysymbol'].number_format(($injul), 2, '.', ','); } echo '</td>
 <td> '; if ($inaug) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inaug), 2, '.', ','); } echo '</td>
 <td> '; if ($insep) { echo '&'.$globalprefrow['currencysymbol'].number_format(($insep), 2, '.', ','); } echo '</td>
 <td> '; if ($inoct) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inoct), 2, '.', ','); } echo '</td>
 <td> '; if ($innov) { echo '&'.$globalprefrow['currencysymbol'].number_format(($innov), 2, '.', ','); } echo '</td>
 <td> '; if ($indec) { echo '&'.$globalprefrow['currencysymbol'].number_format(($indec), 2, '.', ','); } echo '</td> 
 <td> '; if ($intot) { echo '&'.$globalprefrow['currencysymbol'].number_format(($intot), 2, '.', ','); } echo '</td>

</tr>

';

}

 // ends view invoices by paid date































// if income type = jobs by target collection date
 if ($incomeselect=='tarcollect') { 


     ?>



<tr>
  <td><strong>Projected Income </strong></td>
 <td colspan="13" ><div class="line"></div></td></tr>
<?php if ($totlicost+$ftotlicost) { ?>

<tr>
<td>Licensed </td>
<td> <?php if ($janlicost+$fjanlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/01/'. $year.'&amp;from=01/01/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($janlicost+$fjanlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($feblicost+$ffeblicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to='.$leapyear.'/02/'. $year.'&amp;from=01/02/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($feblicost+$ffeblicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($marlicost+$fmarlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/03/'. $year.'&amp;from=01/03/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($marlicost+$fmarlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($aprlicost+$faprlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/04/'. $year.'&amp;from=01/04/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($aprlicost+$faprlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($maylicost+$fmaylicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/05/'. $year.'&amp;from=01/05/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($maylicost+$fmaylicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($junlicost+$fjunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/06/'. $year.'&amp;from=01/06/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($junlicost+$fjunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($jullicost+$fjullicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/07/'. $year.'&amp;from=01/07/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($jullicost+$fjullicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($auglicost+$fauglicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/08/'. $year.'&amp;from=01/08/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($auglicost+$fauglicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($seplicost+$fseplicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/09/'. $year.'&amp;from=01/09/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($seplicost+$fseplicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($octlicost+$foctlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/10/'. $year.'&amp;from=01/10/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($octlicost+$foctlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($novlicost+$fnovlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/11/'. $year.'&amp;from=01/11/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($novlicost+$fnovlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($declicost+$fdeclicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/12/'. $year.'&amp;from=01/12/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($declicost+$fdeclicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($totlicost+$ftotlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/12/'. $year.'&amp;from=01/01/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=licensed&amp;orderby=pricehilow">'. number_format(($totlicost+$ftotlicost), 2, '.', ',').'</a>'; } ?></td>
</tr>

<?php } ?>

<tr>
 <td>Deliveries</td>
<td> <?php if ($janunlicost+$fjanunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/01/'. $year.'&amp;from=01/01/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($janunlicost+$fjanunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($febunlicost+$ffebunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to='.$leapyear.'/02/'. $year.'&amp;from=01/02/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($febunlicost+$ffebunlicost), 2, '.', ',').'</a>';  } ?></td>
<td> <?php if ($marunlicost+$fmarunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/03/'. $year.'&amp;from=01/03/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($marunlicost+$fmarunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($aprunlicost+$faprunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/04/'. $year.'&amp;from=01/04/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($aprunlicost+$faprunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($mayunlicost+$fmayunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/05/'. $year.'&amp;from=01/05/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($mayunlicost+$fmayunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($jununlicost+$fjununlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/06/'. $year.'&amp;from=01/06/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($jununlicost+$fjununlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($julunlicost+$fjulunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/07/'. $year.'&amp;from=01/07/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($julunlicost+$fjulunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($augunlicost+$faugunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/08/'. $year.'&amp;from=01/08/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($augunlicost+$faugunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($sepunlicost+$fsepunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/09/'. $year.'&amp;from=01/09/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($sepunlicost+$fsepunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($octunlicost+$foctunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/10/'. $year.'&amp;from=01/10/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($octunlicost+$foctunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($novunlicost+$fnovunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/11/'. $year.'&amp;from=01/11/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($novunlicost+$fnovunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($decunlicost+$fdecunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/12/'. $year.'&amp;from=01/12/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($decunlicost+$fdecunlicost), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($totunlicost+$ftotunlicost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/12/'. $year.'&amp;from=01/01/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=deliveries&amp;orderby=pricehilow">'. number_format(($totunlicost+$ftotunlicost), 2, '.', ',').'</a>'; } ?></td>
</tr>

 
<tr>
 <td>Hourly Rate</td>
  <td> <?php if ($janhourcost+$fjanhourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/01/'. $year.'&amp;from=01/01/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($janhourcost+$fjanhourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($febhourcost+$ffebhourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to='.$leapyear.'/02/'. $year.'&amp;from=01/02/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($febhourcost+$ffebhourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($marhourcost+$fmarhourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/03/'. $year.'&amp;from=01/03/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($marhourcost+$fmarhourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($aprhourcost+$faprhourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/04/'. $year.'&amp;from=01/04/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($aprhourcost+$faprhourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($mayhourcost+$fmayhourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/05/'. $year.'&amp;from=01/05/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($mayhourcost+$fmayhourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($junhourcost+$fjunhourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/06/'. $year.'&amp;from=01/06/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($junhourcost+$fjunhourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($julhourcost+$fjulhourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/07/'. $year.'&amp;from=01/07/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($julhourcost+$fjulhourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($aughourcost+$faughourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/08/'. $year.'&amp;from=01/08/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($aughourcost+$faughourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($sephourcost+$fsephourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/09/'. $year.'&amp;from=01/09/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($sephourcost+$fsephourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($octhourcost+$focthourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/10/'. $year.'&amp;from=01/10/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($octhourcost+$focthourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($novhourcost+$fnovhourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=30/11/'. $year.'&amp;from=01/11/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($novhourcost+$fnovhourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($dechourcost+$fdechourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/12/'. $year.'&amp;from=01/12/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($dechourcost+$fdechourcost), 2, '.', ',').'</a>'; } ?></td>
  <td> <?php if ($tothourcost+$ftothourcost) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?to=31/12/'. $year.'&amp;from=01/01/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=hourly&amp;orderby=pricehilow">'. number_format(($tothourcost+$ftothourcost), 2, '.', ',').'</a>'; } ?></td>
  </tr>

 
 
 
 <?php   




if ($totother) {


 ?> 
<tr>
<td>Other</td>
<td> <?php if ($janother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/01/'. $year.'&amp;from=01/01/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($janother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($febother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to='.$leapyear.'/02/'. $year.'&amp;from=01/02/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($febother), 2, '.', ',').'</a>';} ?></td>
<td> <?php if ($marother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/03/'. $year.'&amp;from=01/03/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($marother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($aprother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=30/04/'. $year.'&amp;from=01/04/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($aprother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($mayother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/05/'. $year.'&amp;from=01/05/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($mayother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($junother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=30/06/'. $year.'&amp;from=01/06/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($junother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($julother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/07/'. $year.'&amp;from=01/07/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($julother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($augother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/08/'. $year.'&amp;from=01/08/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($augother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($sepother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=30/09/'. $year.'&amp;from=01/09/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($sepother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($octother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/10/'. $year.'&amp;from=01/10/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($octother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($novother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=30/11/'. $year.'&amp;from=01/11/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($novother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($decother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/12/'. $year.'&amp;from=01/12/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($decother), 2, '.', ',').'</a>'; } ?></td>
<td> <?php if ($totother) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/12/'. $year.'&amp;from=01/01/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;deltype=other&amp;orderby=pricehilow">'. number_format(($totother), 2, '.', ',').'</a>'; } ?></td>
</tr> 
<?php 

 }



// echo $intot.'<br>'.$totlicost.'<br>'.$totunlicost.'<br>'.$tothourcost.'<br>'.$fintot.'<br>'.$ftotlicost.'<br>'.$ftotunlicost.'<br>'.$ftothourcost;
// echo '<br>'.($intot+$fintot-$totlicost-$ftotlicost-$totunlicost-$ftotunlicost-$tothourcost-$ftothourcost);
 

if ($vattot) {  ?>
 
 
 <tr>
<td> vatjan  VAT</td>
 <td> <?php if ($vatjan) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatjan), 2, '.', ','); } ?></td>
 <td> <?php if ($vatfeb) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatfeb), 2, '.', ','); } ?></td> 
 <td> <?php if ($vatmar) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatmar), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatapr) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatapr), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatmay) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatmay), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatjun) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatjun), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatjul) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatjul), 2, '.', ','); } ?></td>  
 <td> <?php if ($vataug) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vataug), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatsep) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatsep), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatoct) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatoct), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatnov) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatnov), 2, '.', ','); } ?></td>  
 <td> <?php if ($vatdec) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vatdec), 2, '.', ','); } ?></td>   
 <td> <?php if ($vattot) { echo '&'.$globalprefrow['currencysymbol'].number_format(($vattot), 2, '.', ','); } ?></td>  
 </tr>

 <?php  }  ?>
 
 
  <tr><td colspan="14" ><div class="line"></div></td></tr>

  
<?php if ($intot) {    ?>  

 <tr><td title="Incl. VAT">   Net Complete </td>
 <td> <?php if ($injan) { echo '&'.$globalprefrow['currencysymbol'].number_format(($injan), 2, '.', ','); } ?></td>
 <td> <?php if ($infeb) { echo '&'.$globalprefrow['currencysymbol'].number_format(($infeb), 2, '.', ','); } ?></td> 
 <td> <?php if ($inmar) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inmar), 2, '.', ','); } ?></td>  
 <td> <?php if ($inapr) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inapr), 2, '.', ','); } ?></td>  
 <td> <?php if ($inmay) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inmay), 2, '.', ','); } ?></td>  
 <td> <?php if ($injun) { echo '&'.$globalprefrow['currencysymbol'].number_format(($injun), 2, '.', ','); } ?></td>  
 <td> <?php if ($injul) { echo '&'.$globalprefrow['currencysymbol'].number_format(($injul), 2, '.', ','); } ?></td>  
 <td> <?php if ($inaug) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inaug), 2, '.', ','); } ?></td>  
 <td> <?php if ($insep) { echo '&'.$globalprefrow['currencysymbol'].number_format(($insep), 2, '.', ','); } ?></td>  
 <td> <?php if ($inoct) { echo '&'.$globalprefrow['currencysymbol'].number_format(($inoct), 2, '.', ','); } ?></td>  
 <td> <?php if ($innov) { echo '&'.$globalprefrow['currencysymbol'].number_format(($innov), 2, '.', ','); } ?></td>  
 <td> <?php if ($indec) { echo '&'.$globalprefrow['currencysymbol'].number_format(($indec), 2, '.', ','); } ?></td>   
 <td> <?php if ($intot) { echo '&'.$globalprefrow['currencysymbol'].number_format(($intot), 2, '.', ','); } ?></td>  
  </tr> 
 
 <?php  }  
 
 if ($incomeselect=='tarcollect') {
 
 
 ?>
 

 <tr><td title="Incl. VAT">   Net Scheduled / Active</td>
 <td> <?php if ($finjan) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finjan), 2, '.', ','); } ?></td>
 <td> <?php if ($finfeb) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finfeb), 2, '.', ','); } ?></td> 
 <td> <?php if ($finmar) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finmar), 2, '.', ','); } ?></td>  
 <td> <?php if ($finapr) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finapr), 2, '.', ','); } ?></td>  
 <td> <?php if ($finmay) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finmay), 2, '.', ','); } ?></td>  
 <td> <?php if ($finjun) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finjun), 2, '.', ','); } ?></td>  
 <td> <?php if ($finjul) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finjul), 2, '.', ','); } ?></td>  
 <td> <?php if ($finaug) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finaug), 2, '.', ','); } ?></td>  
 <td> <?php if ($finsep) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finsep), 2, '.', ','); } ?></td>  
 <td> <?php if ($finoct) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finoct), 2, '.', ','); } ?></td>  
 <td> <?php if ($finnov) { echo '&'.$globalprefrow['currencysymbol'].number_format(($finnov), 2, '.', ','); } ?></td>  
 <td> <?php if ($findec) { echo '&'.$globalprefrow['currencysymbol'].number_format(($findec), 2, '.', ','); } ?></td>   
 <td> <?php if ($fintot) { echo '&'.$globalprefrow['currencysymbol'].number_format(($fintot), 2, '.', ','); } ?></td>  
 </tr> 
  
 <?php   }  ?>
  
 
 <tr><td colspan="14" ><div class="line"></div></td></tr>



<?php   ?>
 
 <tr> <td title="Incl. VAT">   Total Net Income</td>
 <td> <?php if ($injan+$finjan) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/01/'. $year.'&amp;from=01/01/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($injan+$finjan), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($infeb+$finfeb) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to='.$leapyear.'/02/'. $year.'&amp;from=01/02/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($infeb+$finfeb), 2, '.', ',').'</a>'; }  ?></td>
 <td> <?php if ($inmar+$finmar) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/03/'. $year.'&amp;from=01/03/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($inmar+$finmar), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($inapr+$finapr) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=30/04/'. $year.'&amp;from=01/04/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($inapr+$finapr), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($inmay+$finmay) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/05/'. $year.'&amp;from=01/05/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($inmay+$finmay), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($injun+$finjun) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=30/06/'. $year.'&amp;from=01/06/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($injun+$finjun), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($injul+$finjul) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/07/'. $year.'&amp;from=01/07/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($injul+$finjul), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($inaug+$finaug) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/08/'. $year.'&amp;from=01/08/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($inaug+$finaug), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($insep+$finsep) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=30/09/'. $year.'&amp;from=01/09/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($insep+$finsep), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($inoct+$finoct) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/10/'. $year.'&amp;from=01/10/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($inoct+$finoct), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($innov+$finnov) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=30/11/'. $year.'&amp;from=01/11/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($innov+$finnov), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($indec+$findec) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/12/'. $year.'&amp;from=01/12/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($indec+$findec), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($intot+$fintot) { echo '&'.$globalprefrow['currencysymbol'].'<a href="clientviewtargetcollection.php?deltype=all&amp;to=31/12/'. $year.'&amp;from=01/01/'. $year.'&amp;clientid=all&amp;newcyclistid=all&amp;orderby=pricehilow">'. number_format(($intot+$fintot), 2, '.', ',').'</a>'; } ?></td </tr>

 
 
<?php


  }

// ends if ($incomeselect=='tarcollect') { 













 ?>
 
 
 
<tr><td colspan="14" ><div class="line"></div></td></tr>
<tr><td>Expenses</td><td colspan="13" ><div class="line"></div></td></tr>



<?php if ($tot6) { ?>

 <tr><td>Staff</td>
 <td> <?php if ($jan6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($feb6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($mar6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($apr6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($may6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jun6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jul6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($aug6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($sep6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($oct6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($nov6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($dec6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec6), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($tot6) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=6&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($tot6), 2, '.', ',').'</a>'; } ?></td>

 <?php }




 if ($tot3) {  ?>
 
 <tr><td>Passed to Sub</td>
 <td> <?php if ($jan3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($feb3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($mar3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($apr3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($may3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jun3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jul3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($aug3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($sep3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($oct3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($nov3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($dec3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec3), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($tot3) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=3&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($tot3), 2, '.', ',').'</a>'; } ?></td>
 
 <?php }





 if ($tot1)  {  ?>
 
 
 <tr><td>Transport</td>
 <td> <?php if ($jan1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($feb1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($mar1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($apr1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($may1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jun1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jul1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($aug1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($sep1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($oct1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($nov1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($dec1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec1), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($tot1) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=1&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($tot1), 2, '.', ',').'</a>'; } ?></td>
 </tr>
 
 
 
<?php }  if ($tot2)  { ?>
 
 

 <tr><td>Telephony</td>
 <td> <?php if ($jan2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($feb2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($mar2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($apr2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($may2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jun2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jul2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($aug2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($sep2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($oct2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($nov2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($dec2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec2), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($tot2) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=2&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($tot2), 2, '.', ',').'</a>'; } ?></td>
  </tr>
  
  
<?php  }  if ($tot4)  {  ?>
  

 <tr><td>Promo</td>
 <td> <?php if ($jan4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($feb4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($mar4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($apr4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($may4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jun4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jul4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($aug4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($sep4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($oct4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($nov4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($dec4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec4), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($tot4) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=4&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($tot4), 2, '.', ',').'</a>'; } ?></td>
 </tr>

 
<?php    

  }


if ($tot5) {

?> 
 
 <tr><td>Office</td>
 <td> <?php if ($jan5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($feb5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($mar5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($apr5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($may5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jun5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jul5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($aug5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($sep5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($oct5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($nov5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov5), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($dec5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec5), 2, '.', ',').'</a>'; } ?></td> 
 <td> <?php if ($tot5) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=5&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($tot5), 2, '.', ',').'</a>'; } ?></td>
  </tr>  
<?php }






 if ($tot7) { ?>
  
   <tr><td>Licenses</td>
 <td> <?php if ($jan7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($feb7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($mar7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($apr7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($may7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jun7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jul7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($aug7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($sep7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($oct7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($nov7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov7), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($dec7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec7), 2, '.', ',').'</a>'; } ?></td> 
 <td> <?php if ($tot7) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=7&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($tot7), 2, '.', ',').'</a>'; } ?></td>
 
 
<?php  } 



if ($tot8) {

?> 
   <tr><td>Insurance</td>
 <td> <?php if ($jan8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($feb8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($mar8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($apr8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($may8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jun8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jul8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($aug8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($sep8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($oct8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($nov8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($dec8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec8), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($tot8) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=8&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($tot8), 2, '.', ',').'</a>'; } ?></td>
 </tr>
 
 
 
<?php  }  if ($tot9) {    ?>

 <tr><td>Rent</td>
 <td> <?php if ($jan9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($feb9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($mar9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($apr9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($may9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jun9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jul9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($aug9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($sep9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($oct9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($nov9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($dec9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec9), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($tot9) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=9&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($tot9), 2, '.', ',').'</a>'; } ?></td>
 </tr>

<?php }  





if ($tot10) {     ?>
 
  <tr><td>Bike Stuff</td>
 <td> <?php if ($jan10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($feb10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($mar10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($apr10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($may10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jun10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($jul10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($aug10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($sep10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($oct10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($nov10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($dec10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec10), 2, '.', ',').'</a>'; } ?></td>
 <td> <?php if ($tot10) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=10&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($tot10), 2, '.', ',').'</a>'; } ?></td>
 </tr> 
<?php  } ?>
 
<tr><td colspan="14" ><div class="line"></div></td></tr>





<?php 

if (($incomeselect=='tarcollect') and ($ftotexp)) { ?>
<tr><td>  Scheduled Expenses</td>
<td><?php if ($fjan)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($fjan), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($ffeb)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($ffeb), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($fmar)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($fmar), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($fapr)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($fapr), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($fmay)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($fmay), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($fjun)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($fjun), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($fjul)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($fjul), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($faug)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($faug), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($fsep)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($fsep), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($foct)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($foct), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($fnov)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($fnov), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($fdec)    { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($fdec), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($ftotexp) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;ifpaid=future&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($ftotexp), 2, '.', ',').'</a>'; } ?></td>
</tr>
<?php } 






// jan completed exp
// fjan future


// used twice, putin total var
if ($totcompleteexpense) {



?>

<tr >
<td title="Incl. VAT">  Completed Expenses</td>
<td><?php if ($jan) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($feb) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($mar) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($apr) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($may) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($jun) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($jul) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($aug) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($sep) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($oct) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($nov) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov), 2, '.', ','). '</a> '; } ?></td>
<td><?php if ($dec) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec), 2, '.', ','). '</a> '; } ?></td>
<td><?php echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($totcompleteexpense), 2, '.', ','). '</a> ';  ?></td>
</tr>
<?php



}



if ($evattot=='donotdisplay') {

?>
<tr>
<td title=" future expenses" >  evatjan - current and future VAT Expenses</td>

<td><?php if ($evatjan) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatjan), 2, '.', ','); } ?></td> 
<td><?php if ($evatfeb) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatfeb), 2, '.', ','); } ?></td> 
<td><?php if ($evatmar) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatmar), 2, '.', ','); } ?></td> 
<td><?php if ($evatapr) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatapr), 2, '.', ','); } ?></td> 
<td><?php if ($evatmay) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatmay), 2, '.', ','); } ?></td> 
<td><?php if ($evatjun) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatjun), 2, '.', ','); } ?></td> 
<td><?php if ($evatjul) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatjul), 2, '.', ','); } ?></td> 
<td><?php if ($evataug) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evataug), 2, '.', ','); } ?></td> 
<td><?php if ($evatsep) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatsep), 2, '.', ','); } ?></td> 
<td><?php if ($evatoct) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatoct), 2, '.', ','); } ?></td> 
<td><?php if ($evatnov) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatnov), 2, '.', ','); } ?></td> 
<td><?php if ($evatdec) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evatdec), 2, '.', ','); } ?></td> 
<td><?php if ($evattot) { echo '&'.$globalprefrow['currencysymbol']. number_format(($evattot), 2, '.', ','); } ?></td> 
</tr>

<?php  

}

// check exptot

if (($incomeselect=='tarcollect') and ($exptot)) {

?>
<tr >
<td title="Incl. VAT" >  Total Expenses</td>
<td><?php if (($jan) or ($fjan)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=01&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jan+$fjan), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($feb) or ($ffeb)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=02&amp;delivermonth=02&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($feb+$ffeb), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($mar) or ($fmar)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=03&amp;delivermonth=03&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($mar+$fmar), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($apr) or ($fapr)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=04&amp;delivermonth=04&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($apr+$fapr), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($may) or ($fmay)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=05&amp;delivermonth=05&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($may+$fmay), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($jun) or ($fjun)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=06&amp;delivermonth=06&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jun+$fjun), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($jul) or ($fjul)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=07&amp;delivermonth=07&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($jul+$fjul), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($aug) or ($faug)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=08&amp;delivermonth=08&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($aug+$faug), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($sep) or ($fsep)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=09&amp;delivermonth=09&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($sep+$fsep), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($oct) or ($foct)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=10&amp;delivermonth=10&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($oct+$foct), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($nov) or ($fnov)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=11&amp;delivermonth=11&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($nov+$fnov), 2, '.', ',').'</a>'; } ?></td>
<td><?php if (($dec) or ($fdec)) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=12&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($dec+$fdec), 2, '.', ',').'</a>'; } ?></td>
<td><?php if ($exptot) { echo '&'.$globalprefrow['currencysymbol'];?><a href="expenseview.php?searchexpensecode=all&amp;collectyear=<?php echo $year; ?>&amp;deliveryear=<?php echo  $year; ?>&amp;collectmonth=12&amp;delivermonth=01&amp;collectday=31&amp;deliverday=01"><?php echo number_format(($exptot), 2, '.', ',').'</a>'; } ?></td>
</tr>
<tr><td colspan="14" ><div class="line"></div></td></tr>
<?php   }  
 
 
// <tr><td colspan="14" ><div class="line"></div></td></tr>
 
 
 
 
 
 // Live total excl. future
 
 $profitjan=$injan-$jan;
 $profitfeb= $infeb-$feb;
 $profitmar=$inmar-$mar;
 $profitapr=$inapr-$apr;
 $profitmay=$inmay-$may;
 $profitjun=$injun-$jun;
 $profitjul=$injul-$jul;
 $profitaug=$inaug-$aug;
 $profitsep=$insep-$sep;
 $profitoct=$inoct-$oct;
 $profitnov=$innov-$nov;
 $profitdec=$indec-$dec;
 $profittot=(($injan+$infeb+$inmar+$inapr+$inmay+$injun+$injul+$inaug+$insep+$inoct+$innov+$indec)
-
($jan+$feb+$mar+$apr+$may+$jun+$jul+$aug+$sep+$oct+$nov+$dec));
?> 
 
<tr>
<td title="Incl. VAT ">  Net Profit  </td>
<td> <?php if ($profitjan) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitjan), 2, '.', ','); } ?> </td>
<td> <?php if ($profitfeb) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitfeb), 2, '.', ','); } ?> </td>
<td> <?php if ($profitmar) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitmar), 2, '.', ','); } ?> </td>
<td> <?php if ($profitapr) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitapr), 2, '.', ','); } ?> </td>
<td> <?php if ($profitmay) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitmay), 2, '.', ','); } ?> </td>
<td> <?php if ($profitjun) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitjun), 2, '.', ','); } ?> </td>
<td> <?php if ($profitjul) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitjul), 2, '.', ','); } ?> </td>
<td> <?php if ($profitaug) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitaug), 2, '.', ','); } ?> </td>
<td> <?php if ($profitsep) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitsep), 2, '.', ','); } ?> </td>
<td> <?php if ($profitoct) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitoct), 2, '.', ','); } ?> </td>
<td> <?php if ($profitnov) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitnov), 2, '.', ','); } ?> </td> 
<td> <?php if ($profitdec) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profitdec), 2, '.', ','); } ?> </td>
<td> <?php if ($profittot) { echo '&'.$globalprefrow['currencysymbol'] . number_format(($profittot), 2, '.', ','); } ?> </td>
</tr>
 <?php 
 





 
$forecastprofittot= 
($injan+$infeb+$inmar+$inapr+$inmay+$injun+$injul+$inaug+$insep+$inoct+$innov+$indec)
+
($finjan+$finfeb+$finmar+$finapr+$finmay+$finjun+$finjul+$finaug+$finsep+$finoct+$finnov+$findec)
-
($jan+$feb+$mar+$apr+$may+$jun+$jul+$aug+$sep+$oct+$nov+$dec)
-
($fjan+$ffeb+$fmar+$fapr+$fmay+$fjun+$fjul+$faug+$fsep+$foct+$fnov+$fdec);

if (($incomeselect=='tarcollect') and ($forecastprofittot)) { ?>
 
 <tr>
 <td  title="Incl. VAT ">   Forecast Net Profit </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($injan+$finjan-$fjan-$jan), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($infeb+$finfeb-$ffeb-$feb), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($inmar+$finmar-$fmar-$mar), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($inapr+$finapr-$fapr-$apr), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($inmay+$finmay-$fmay-$may), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($injun+$finjun-$fjun-$jun), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($injul+$finjul-$fjul-$jul), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($inaug+$finaug-$faug-$aug), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($insep+$finsep-$fsep-$sep), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($inoct+$finoct-$foct-$oct), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($innov+$finnov-$fnov-$nov), 2, '.', ','); ?> </td> 
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format(($indec+$findec-$fdec-$dec), 2, '.', ','); ?> </td>
<td> <?php echo '&'.$globalprefrow['currencysymbol'] . number_format($forecastprofittot, 2, '.', ','); ?> </td>
 
 </tr>
 
 <?php  

}
 
 
 
 
 $gptest =  ($injan+$infeb+$inmar+$inapr+$inmay+$injun+$injul+$inaug+$insep+$inoct+$innov+$indec)+($finjan+$finfeb+$finmar+$finapr+$finmay+$finjun+$finjul+$finaug+$finsep+$finoct+$finnov+$findec);
 
 
 if ($gptest) {
 
$gptot= number_format(((($injan+$infeb+$inmar+$inapr+$inmay+$injun+$injul+$inaug+$insep+$inoct+$innov+$indec)-($jan+$feb+$mar+$apr+$may+$jun+$jul+$aug+$sep+$oct+$nov+$dec)+($finjan+$finfeb+$finmar+$finapr+$finmay+$finjun+$finjul+$finaug+$finsep+$finoct+$finnov+$findec))/
(($injan+$infeb+$inmar+$inapr+$inmay+$injun+$injul+$inaug+$insep+$inoct+$innov+$indec)+($finjan+$finfeb+$finmar+$finapr+$finmay+$finjun+$finjul+$finaug+$finsep+$finoct+$finnov+$findec))*'100'), 2, '.', '');  
 
// echo ' gptot is :'.$gptot.':';
 

 if (($gptot<>'0.00') and ($gptot<>'100.00')) {


 ?>
 
<tr><td colspan="14" ><div class="line"></div></td></tr>


 <tr>
 <td title="Incl. VAT + Scheduled Expenses " > Total Net GP % </td>
 
<td> <?php if (($injan) or ($finjan)) { echo  number_format(((($injan+$finjan-$jan)/($injan+$finjan)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($infeb) or ($finfeb)) { echo  number_format(((($infeb+$finfeb-$feb)/($infeb+$finfeb)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($inmar) or ($finmar)) { echo  number_format(((($inmar+$finmar-$mar)/($inmar+$finmar)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($inapr) or ($finapr)) { echo  number_format(((($inapr+$finapr-$apr)/($inapr+$finapr)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($inmay) or ($finmay)) { echo  number_format(((($inmay+$finmay-$may)/($inmay+$finmay)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($injun) or ($finjun)) { echo  number_format(((($injun+$finjun-$jun)/($injun+$finjun)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($injul) or ($finjul)) { echo  number_format(((($injul+$finjul-$jul)/($injul+$finjul)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($inaug) or ($finaug)) { echo  number_format(((($inaug+$finaug-$aug)/($inaug+$finaug)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($insep) or ($finsep)) { echo  number_format(((($insep+$finsep-$sep)/($insep+$finsep)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($inoct) or ($finoct)) { echo  number_format(((($inoct+$finoct-$oct)/($inoct+$finoct)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($innov) or ($finnov)) { echo  number_format(((($innov+$finnov-$nov)/($innov+$finnov)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php if (($indec) or ($findec)) { echo  number_format(((($indec+$findec-$dec)/($indec+$findec)*'100')), 2, '.', ',').' % '; } ?></td>
<td> <?php echo $gptot.' % '; ?></td>
</tr>
 <?php  
 
 } 
 }
   ?>

</tbody>
</table>

<br/>



<div class="line"></div><br /></div>
<?php

include 'footer.php';

 ?></body>