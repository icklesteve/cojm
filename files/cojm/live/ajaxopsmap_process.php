<?php

include "C4uconnect.php";


################ Save & delete markers #################
if($_POST) //run only if there's a post data
{
    //make sure request is comming from Ajax
    $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 
    if (!$xhr){ 
        header('HTTP/1.1 500 Error: Request must come from Ajax!'); 
        exit(); 
    }
    	
    // get marker position and split it for database
    $mLatLang   = explode(',',$_POST["latlang"]);
    $mLat       = filter_var($mLatLang[0], FILTER_VALIDATE_FLOAT);
    $mLng       = filter_var($mLatLang[1], FILTER_VALIDATE_FLOAT);

    
	if(isset($_POST['action']) && $_POST["action"]=='savemarker') {
	
        //more validations are encouraged, empty fields etc.
    $mName      = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $mAddress   = filter_var($_POST["address"], FILTER_SANITIZE_STRING); // actually description
    $mType      = filter_var($_POST["type"], FILTER_SANITIZE_STRING);
    
	// replace line breaks for breaks	
$obj['descrip'] = str_replace(array("\r", "\n"), '<br />', $obj['descrip']);

	
	
	
	
$query=("INSERT INTO opsmap (opsname, descrip, lat, lng, type, markertype) VALUES ('$mName','$mAddress',$mLat, $mLng, '1', '$mType')");
 
$result = $dbh->exec($query);
$opsmapid = $dbh->lastInsertId();



 if (!$opsmapid) {  
          header('HTTP/1.1 500 Error: Could not create marker 44');  
          exit();
    }
	
    $output = '<h1 class="marker-heading">'.$mName.'</h1><p>'.$mAddress.'</p> <br /> ';
	$output.= '<form action="opsmap-new-place.php" method="post" >';
	$output.= '<button type="submit" name="remove-marker" class="remove-marker" title="Edit Place">Edit Marker</button>';
	$output.= '<input name="opsmapid" type="hidden" value="'. $opsmapid . '"> '; 
	$output.= '</form>';
	
    exit($output);
	
} // ends check save marker

else if (isset($_POST['action']) && $_POST["action"]=='editmarker') {
	
	
	
	$output= '';
	
	if (isset($_POST['inarchive']) && $_POST["inarchive"]=='1') { $inarchive='1'; } else { $inarchive='0'; }
	
    $inarchive      = filter_var($_POST["inarchive"], FILTER_SANITIZE_STRING);
    $opsmapid   = filter_var($_POST["opsmapid"], FILTER_SANITIZE_STRING);
    $pName      = filter_var($_POST["pName"], FILTER_SANITIZE_STRING);
    $pDesc   = filter_var($_POST["pDesc"], FILTER_SANITIZE_STRING);
    $pType      = filter_var($_POST["pType"], FILTER_SANITIZE_STRING);
	
	$pDesc = str_replace(array("\r", "\n"), '<br />', $pDesc);

	try {

$stmt = $dbh->prepare("UPDATE opsmap SET inarchive=?, opsname=?, descrip=? , type='1' , markertype=? ,lat=? , lng=?
 WHERE opsmapid=?");
$stmt->execute(array($inarchive, $pName, $pDesc, $pType, $mLat, $mLng, $opsmapid));
 
	}   catch(PDOException $ex) {
   
header('HTTP/1.1 500 Error: Could not create marker 81');  
          exit();

 //  echo "An Error occured!"; //user friendly message
  
} 
	
	$output=$output.'Saved Marker'; 
	    exit($output);
	}

} // ends check 

if($_GET) //run only if there's a post data
{

################ Continue generating Map XML #################

//Create a new DOMDocument object
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers"); //Create new element node
$parnode = $dom->appendChild($node); //make the node show up 

// Select all the rows in the markers table
if ($_GET['archive']=='0') { $query=("SELECT * FROM opsmap WHERE type='1' AND inarchive ='0' "); } else { 
if ($_GET['archive']=='1') { $query=("SELECT * FROM opsmap WHERE type='1'  "); } }

$stmt = $dbh->query($query);
$row_count = $stmt->rowCount();
// echo $row_count.' rows selected';

if ($row_count<'1') {
    header('HTTP/1.1 500 Error: Could not get markers 110'); 
    exit();
} 

//set document header to text/xml
header("Content-type: text/xml"); 

// Iterate through the rows, adding XML nodes for each

while($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
//    echo $row['field1'].' '.$row['field2']; //etc...
	
// replace line breaks for breaks	
$obj['descrip'] = str_replace(array("\r", "\n"), '<br />', $obj['descrip']);


	
  $node = $dom->createElement("marker");  
  $newnode = $parnode->appendChild($node);   
  $newnode->setAttribute("name",$obj['opsname']);
  $newnode->setAttribute("address", $obj['descrip']);  
  $newnode->setAttribute("lat", $obj['lat']);  
  $newnode->setAttribute("lng", $obj['lng']);  
  $newnode->setAttribute("markertype", $obj['markertype']);   
  $newnode->setAttribute("opsmapid", $obj['opsmapid']);   
}
echo $dom->saveXML(); 

} // ends check for get in request

$dbh=null; // close database connection

 ?>