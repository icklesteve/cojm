<?php

include "C4uconnect.php";

$script='';
$id=$_POST['id'];

$query = "SELECT *
FROM Orders
WHERE Orders.ID = ? LIMIT 0,1";

$parameters = array($id);
$statement = $dbh->prepare($query);
$statement->execute($parameters);
$row = $statement->fetch(PDO::FETCH_ASSOC);

$ntot=1;    
$i=1;

while ($i<21) {
    
    $prenPC=str_replace(" ", "+", trim($row["enrpc$i"]));
    $prenFT=str_replace(" ", "+", trim($row["enrft$i"]));
    
    $linkpc='https://'.$globalprefrow['addresssearchlink'].$prenPC;
    $linkboth='https://'.$globalprefrow['addresssearchlink'].$prenFT.'+'.$prenPC;
    
    echo '<div id="togglenr'.$i.'" class="togglenr';
    if ((!$prenPC) and (!$prenFT)) {
        echo ' hideuntilneeded';
    } else {
        $ntot++;
    }
    
    echo '"> <div class="fs">
                <div class="fsl" > ';  
                

    if ($globalprefrow['inaccuratepostcode']=='0') {
        echo '<a class="newwin';
        if (!$prenPC) {
            echo ' hideuntilneeded';
        }
        echo '" id="viewinmap'.$i.'" title="View in Maps" target="_blank" href="'.$linkpc.'" >via</a> ';
    } else {
        echo '<a class="newwin';
            if ((!$prenPC) and (!$prenFT)) {
        echo ' hideuntilneeded';
            }
        echo '" id="viewinmap'.$i.'" title="View in Maps" target="_blank" href="'. $linkboth.'">via</a> ';        
    }
    

    
    
    
    
        
    $sql = "SELECT * FROM cojm_favadr WHERE 
    favadrft = ?
    AND favadrclient= '".$row['CustomerID']."'
    AND favadrpc= ?
    AND favadrisactive='1' 
    LIMIT 1";
    
    try {
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$row["enrft$i"],$row["enrpc$i"]]);
        $favdata = $stmt->fetchAll();
    }
    
    catch(PDOException $e) { $message.= $e->getMessage(); }
            
    if ($favdata) {
        $message.='<br /> Favourite Found ';
        if ($favdata[0]['favadrcomments']) {
            $favcomments=$favdata[0]['favadrcomments'];
        }
    } else {
        $infotext.='<br /> No Fav Found ';
        $favcomments='';
    }
    
    echo '<button title="Add / Edit Favourite" id="editfav'.$i.'" class="editfav';
    
    if (!$favdata) {
        echo ' newfav';
    }


    if ((!$row["enrft$i"]) and (!$row["enrpc$i"])) {
        echo ' hideuntilneeded';
    }
    
    echo '"> Edit Favourite </button>';    

    
    $postcodenotfoundtext='';
    $postcodeclass='';
    
    
    if (($globalprefrow['inaccuratepostcode'])=='0') { ///  check to see if postcode on database //

        $pcprenPC=trim($row["enrpc$i"]);
        $pctocheck= str_replace(" ", "", "$pcprenPC", $count);
        
        if (trim($pctocheck)) {
            $updatesql = "SELECT PZ_northing FROM  `postcodeuk` 
            WHERE  `PZ_Postcode` LIKE :pc
            LIMIT 0 , 1";
        
            $stmt = $dbh->prepare($updatesql);
            $stmt->bindParam(':pc', $pctocheck, PDO::PARAM_INT); 
            $stmt->execute();
            $obj = $stmt->fetchObject();
        
            if ($obj) {
                // You have the data! No need for the rowCount() ever!
            }
            else {
                $postcodeclass=" ui-state-error";
                $postcodenotfoundtext= ' Postcode Not Found ';
            }
        } else {
            
            if ((trim($row["enrft$i"]))) {
                $postcodeclass=" ui-state-error";
            }
        }
        
        
        
        
        
    } // ends check to see if postcode

    
    
    echo '
    <button 
    class="chngfav" 
    title="Choose Favourite" 
    id="jschangfavvia'.$i.'">
    </button>';

    echo ' </div>
    <input placeholder="via . . ."
    type="text"
    title="Via '.$i.' Address"
    class="addfield caps ui-state-default ui-corner-left freetext"
    name="enrft'.$i.'"
    id="enrft'.$i.'"
    value="'.$row["enrft$i"].'"><input 
    size="9" ';

    if (($globalprefrow['inaccuratepostcode'])=='0') {
        if (((!trim($prenPC)) and (trim($row["enrft$i"]))) or (($sumtot=='0') and (trim($prenPC))))  {
            echo ' style="'.$globalprefrow['highlightcolourno'].'"';
        }
    }
    
    
    
    
    
    
    echo '
    class="addfield caps ui-state-default ui-corner-right'.$postcodeclass.'"
    placeholder="Postcode" name="enrpc'.$i.'"
    type="text"
    title="Via '.$i.' Postcode"
    id="enrpc'.$i.'"
    value="'.$row["enrpc$i"].'">';
    
    echo ' <button title="Add Postcode" id="addpostcodebutton'.$i.'" class="addpostcodebutton';

    if (!$postcodenotfoundtext) { echo ' hideuntilneeded'; }    
    
    echo '"> Add PC </button>       ';
    
    
    
    if ($i<20) {
        echo '
        <button class="addvia hideuntilneeded" title="Add Via"
        id="togglenr'.($i+1).'choose" >Add Via </button>
        ';
    }

    echo '
    </div>
    <div id="favcomment'.$i.'" class="favcomments fsr';
    if (!$favcomments) {    
        echo ' hideuntilneeded';
    }
    echo '" >'. $favcomments .'</div>
        
</div> '; // ends via loops        
        
    $i++;
        
} // less than 21 loop


$j=0;
$tempcount=0;
while ( $j < 21 ) {
    if ( $j>$ntot-1) {
        $script.=' $("#togglenr'.$j.'choose").show(); ';
        $tempcount=$j;
    }
    
    $j++;
}

$script.='  $("#togglenr'.$ntot.'choose").addClass("activewheneditable"); ';


if (($row['status']>99) or ($ntot>1)) {
    // $script.= ' alert("'.$ntot.'"); ';
    $script.= ' $("#togglenr1choose").hide(); '; // needed on own as displayed straight after collection address
}

if ($ntot>1){
    $script.= ' $("#togglenr1choose").removeClass("activewheneditable")  ';
}

echo '<script> ' .$script.' </script> ';
echo $bottomhtml;

?>