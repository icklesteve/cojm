<?php 

/*
    COJM Courier Online Operations Management
	map.php - Displays Locations of future collections / drops / latest tracking / Ops Areas
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
include "C4uconnect.php";
if ($globalprefrow['forcehttps']>0) {
if ($serversecure=='') {  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); } }
$title = "COJM";
include 'changejob.php';


if (isset($_GET['searchtype'])) { $searchtype=trim($_GET['searchtype']);} else { $searchtype=''; }

$markersfound='0';
$max_lat = '-99999';
$min_lat =  '99999';
$max_lon = '-99999';
$min_lon =  '99999';
$testc='';

$hasforms=0;
?><!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php print ' Map : '.($title); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<?php

echo '<link rel="stylesheet" type="text/css" href="'. $globalprefrow['glob10'].'" >
<link rel="stylesheet" type="text/css" href="../css/cojmmap.css">
<link rel="stylesheet" href="css/themes/'. $globalprefrow['clweb8'].'/jquery-ui.css" type="text/css" >
<script type="text/javascript" src="js/'. $globalprefrow['glob9'].'"></script>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" > ';

echo '<script src="//maps.googleapis.com/maps/api/js?v='.$globalprefrow['googlemapver'].'&amp;libraries=geometry&amp;key='.$globalprefrow['googlemapapiv3key'].'" type="text/javascript"></script>
<script src="../js/maptemplate.js" type="text/javascript"></script> ';


if ($globalprefrow['distanceunit']=='miles') {
    $dunit= 'mph ';
} else {
    $dunit= 'kmph ';
}



?>
<style>
/* starts spinner on page load, only for ajax pages  */
#toploader { display:block; }
</style>
<script>
<?php 

echo ' var dunit="'.$dunit.'";'; ?>

var globlat=<?php echo $globalprefrow['glob1']; ?>;
var globlon=<?php echo $globalprefrow['glob2']; ?>;

printtext=' <p class="mapprint"> Live Operations Map </p> ';

var slidertime;
var b64riders;
var oldb64riders;
var b64jobsrow;
var oldb64jobsrow;
var oms;
var jobsrow;
var linearray = [];

var image2 = "<?php echo $globalprefrow['image2']; ?>";
var image3 = "<?php echo $globalprefrow['image3']; ?>";
var image7 = "<?php echo $globalprefrow['image7']; ?>";
var bounds;
var viatocheck=[7,11,15,19,23,27,31,35,39,43,47,51,55,59,63,67,71,75,79,83];

var statuses=[];
var infowindow;


<?php

$query = "SELECT statusname, status FROM status WHERE activestatus=1 AND status<101 ORDER BY status";

$data = $dbh->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);
foreach($data as $statusname => $status) {
    echo ' statuses.push({id:'.$status.',name:"'.$statusname.'"}); ';
}

?>




function lookupstatusname(id) {
    
    var result = $.grep(statuses, function(e){ return e.id === id; });
    
    if (result.length === 1) {
        // access the foo property using result[0].foo
        return result[0].name;
    }
}



function lookupridername(id) {
    
    
    
}







function loadmapfromtemplate() {
    
    
    
    
//    $(document).ready(function () {
        initialize();
        

      
      
        autorefreshmap();
        
        $("#prevridertime").change(refreshmap);
        $("#leftmenuselectrider").change(applyriderfilter);
        $("#jobslider").change(timesliderchanged);
        
        
//    });
}


google.maps.event.addDomListener(window, 'load', loadmapfromtemplate);



function timesliderchanged() {
    refreshmap();
}
















function addtomap() {
    slidertime = $("#jobslider").val();
    
    
    
    var hrs;

    if (slidertime==1) { hrs='Hr'; } else { hrs='Hrs'; }
    $("#jobsliderval").text(slidertime + hrs);
    
    var riderslider=$("#prevridertime").val();
    
    if (riderslider==1) { hrs='Hr'; } else { hrs='Hrs'; }
    
    $("#prevriderhours").text(riderslider + hrs);
    
    
    var lineSymbol = {
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
        strokeOpacity: 0.3
    };
    
    
    var infowindow = new google.maps.InfoWindow({
        pixelOffset: new google.maps.Size(0,30)
    });
    
    function createMarker(latlng,name,html,mapicon,markerclass,index,id) {
        
        var finalLatLng = latlng;
        var thismapicon=mapicon;
        var thisindex=index;
        var thisid=id;
        
        if (markers.length != 0) {
            for (var markerloop=0; markerloop < markers.length; markerloop++) {
                var existingMarker = markers[markerloop];
                var pos = existingMarker.getPosition();
        
                if (finalLatLng.equals(pos)) {
                    //update the position of the coincident marker by applying a small multipler to its coordinates
                    var newLat = finalLatLng.lat() + (Math.random() -.5) / 1500;// * (Math.random() * (max - min) + min);
                    var newLng = finalLatLng.lng() + (Math.random() -.5) / 1500;// * (Math.random() * (max - min) + min);
                    finalLatLng = new google.maps.LatLng(newLat,newLng);
                }
            }
        }
        
        
        
        
        var contentString = html;
        
        
        var marker = new google.maps.Marker({
            position: finalLatLng,
            icon: thismapicon,
            map: map,
            title: name,
            optimized: false,
            zIndex: thisindex,
            id : thisid,
            class : markerclass
        });
    
    
        bounds.extend(finalLatLng);
        markers.push(marker);

        google.maps.event.addListener(marker, "mouseover", createinfowindow);
        google.maps.event.addListener(marker, "click", createinfowindow);        
        
        function createinfowindow(e){
    
            // console.log("marker mouseover in new func");
            console.log("marker mouseover class:" + this.class );
                        
                        
                        
            var markerclass=this.class;
            
            if (/rider/i.test(markerclass)) {
                console.log("rider icon");
                
                iwindowtext = " rider infotext " + html;
                
                
                
            } else {
                console.log("not rider icon");
            
            }
            

            
            
            infowindow.setContent("<div class='bigmapinfowindow'>" + html + " <hr /> </div>");
            infowindow.setOptions({ disableAutoPan: true });
            infowindow.open(map, marker);
        
        };
   
        return finalLatLng;
   
   
   
   
    }
    
    
    

    
    
    
    
    if ((oldb64riders==b64riders) && (oldb64jobsrow == b64jobsrow)) {
        // console.log("data unchanged ");
    } else {
        console.log("data changed ");
    
        
        
        var i;
        
        oldb64jobsrow = b64jobsrow;
        
        oldb64riders=b64riders;
        riders = JSON.parse(b64DecodeUnicode(b64riders));
    
        newjobsrow=b64DecodeUnicode(b64jobsrow);
        
        
        // $("#test").html(newjobsrow);
        
        
        jobsrow = JSON.parse(newjobsrow);
        // console.log("jobsrow: " + jobsrow);
    
        // Reset the markers array
        for (i=0; i<markers.length; i++) {
            markers[i].setMap(null);
        }    
        
        for (i=0; i<linearray.length; i++) {
            linearray[i].setMap(null);
        }    
                
        linearray = [];
        
        
        
        
        
        
        
        markers = [];
        bounds = new google.maps.LatLngBounds();            
        
        
        var joberrordata="";
        var idlist="";
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        var tabletext=' <table class="acc onehundred" > <tbody>';
        
        
        for (i = 0; i < riders.length; i++) {
            var rider = riders[i];
            
            var thisfinalLatLng = new google.maps.LatLng(rider[1], rider[2]);

            // function     function createMarker(latlng,name,html,mapicon,markerclass,index,id) {
            
            
            var infotexthtml="<div class=''><h3>" + rider[0] + "</h3><p>" + rider[8] + "</p>  </div>";
            
            
            
            
            tabletext=tabletext + '<tr id="rider' + rider[4] + '"><td><a href="cyclist.php?thiscyclist=' + rider[4] + '">' + rider[0] + '</a></td><td>';
            
            if (rider[6]>0) { tabletext=tabletext + rider[6] + dunit; }
            
            tabletext=tabletext + '</td>' + '<td>' + rider[8]+ '</td>' + '<td>' + rider[5] + ' </td> </tr>';
            
            
            // function createMarker(latlng,name,html,mapicon,markerclass,index,id) {
            createMarker(thisfinalLatLng,rider[0],infotexthtml,rider[7],("rider"+rider[4]),rider[3],("rider"+rider[4]));
        
        }
    
        tabletext = tabletext+ " </tbody></table> <hr /> ";
        
        
        if (riders.length==0) { tabletext=''; }
        $("#ridertable").html(tabletext);
        
        if (riders.length==1) {
            var tmp=' 1 Rider has';
        } else {
            var tmp=riders.length + ' Riders have ';
        }

        $("#totalriderpositions").text(tmp);

        /*
        
        job positions
        
        */
        
 
 
 
 
 
 
 
        var job;
        
        
        var unschedtext='';
        var numunsched=0;
        var totaljobs=0;
        
        
        // console.log("jobsrow.length " + jobsrow.length + " i " + i);
        
        
        
        
        for (i=0; i < jobsrow.length; i++) {

            job = jobsrow[i];
            
            console.log("order loop, id " + job[0] );
            
            // console.log("jobsrow.length " + jobsrow.length  + " i " + i);
            
            
            var linedata=[];
        
            
        
            idlist += "<p id=" + job[0] + ">" + job[0] + "</p>";

            totaljobs++;
            
            
            if (job[1]==1) { // add to unscheduled / ( job alert ?? ) list
                unschedtext = unschedtext + ' <a href="order.php?id=' + job[0] + '">' + job[0] + '</a> ';
                numunsched++;
            
            }
            
                  
            
            // console.log("order id " + job[4] + job[5] );

            var viasfound=0;            


            // console.log("jobsrow.length " + jobsrow.length);

            
            
            for ( var v = 0; v < viatocheck.length; v++) {
                var temp = viatocheck[v]+1;
                var temptwo = viatocheck[v]+2;
                var tempthree= viatocheck[v]+3;
                
                // console.log( v + ":" + temp + ":" + (job[viatocheck[v]]) + ":" + (job[temp]) + ":"  + (job[temptwo]) + ":"  + (job[tempthree]) + ":");
                
                            // console.log("jobsrow.length " + jobsrow.length  + " i " + i);
                
                
                if (job[viatocheck[v]]+job[temp]==="") {
                    // console.log(" not found ");
                } else {

                    
                    if (job[temptwo]==0) {
                        console.log("no lat found for via job ref  " + job[0]);
                        
                        // alert("no lat found for collection");
                        
                        joberrordata += "<p>No via " + job[viatocheck[v]] + " location for <a title='" + job[0] + "' href='order.php?id=" + job[0] + "'>" + job[0] + "</a></p>";
                    
                                
                    } else {
                    
                    
                        iwindowtext="<h3>Via point " + job[viatocheck[v]] + " </h3><h4> " + job[0] + "</h4>";
                        
                        viasfound++;
                        // console.log(" __________________________________   found via, adding marker");
                        // add marker
                        
                        var finalLatLng = new google.maps.LatLng(job[temptwo], job[tempthree]);
                        
                        var name=job[viatocheck[v]] + " " + job[temp];
    
                        
                        
                        finalLatLng = createMarker(finalLatLng,name,iwindowtext,image7,(" via " + job[0] + " rider" + job[1]),1005,("via"+job[0]));
                        // function createMarker(latlng,name,html,mapicon,markerclass,index,id) {
                        
                        linedata.push(finalLatLng);
                        
                        
                        // add to main to / from line array
                    
                    }
                    
                    
                }
                
                
                
                
                
            }
            
            // console.log("jobsrow.length " + jobsrow.length + " i " + i);
            
            

            // + " <p>" + jobsrow[i][7]  + jobsrow[i][8] + jobsrow[i][9] + jobsrow[i][10] + "</p> " 
            // + " <p>" + jobsrow[i][11] + jobsrow[i][12] + jobsrow[i][13] + jobsrow[i][14] + "</p> " 
            // + " <p>" + jobsrow[i][15] + jobsrow[i][16] + jobsrow[i][17] + jobsrow[i][18] + "</p> " 
            // + " <p>" + jobsrow[i][19] + jobsrow[i][20] + jobsrow[i][21] + jobsrow[i][22] + "</p> " 
            // + " <p>" + jobsrow[i][23] + jobsrow[i][24] + jobsrow[i][25] + jobsrow[i][26] + "</p> " 
            // + " <p>" + jobsrow[i][27] + jobsrow[i][28] + jobsrow[i][29] + jobsrow[i][30] + "</p> " 
            // + " <p>" + jobsrow[i][31] + jobsrow[i][32] + jobsrow[i][33] + jobsrow[i][34] + "</p> " 
            // + " <p>" + jobsrow[i][35] + jobsrow[i][36] + jobsrow[i][37] + jobsrow[i][38] + "</p> " 
            // + " <p>" + jobsrow[i][39] + jobsrow[i][40] + jobsrow[i][41] + jobsrow[i][42] + "</p> " 
            // + " <p>" + jobsrow[i][43] + jobsrow[i][44] + jobsrow[i][45] + jobsrow[i][46] + "</p> " 
            // + " <p>" + jobsrow[i][47] + jobsrow[i][48] + jobsrow[i][49] + jobsrow[i][50] + "</p> " 
            // + " <p>" + jobsrow[i][51] + jobsrow[i][52] + jobsrow[i][53] + jobsrow[i][54] + "</p> " 
            // + " <p>" + jobsrow[i][55] + jobsrow[i][56] + jobsrow[i][57] + jobsrow[i][58] + "</p> "                             
            // + " <p>" + jobsrow[i][59] + jobsrow[i][60] + jobsrow[i][61] + jobsrow[i][62] + "</p> " 
            // + " <p>" + jobsrow[i][63] + jobsrow[i][64] + jobsrow[i][65] + jobsrow[i][66] + "</p> "                             
            // + " <p>" + jobsrow[i][67] + jobsrow[i][68] + jobsrow[i][69] + jobsrow[i][70] + "</p> " 
            // + " <p>" + jobsrow[i][71] + jobsrow[i][72] + jobsrow[i][73] + jobsrow[i][74] + "</p> " 
            // + " <p>" + jobsrow[i][75] + jobsrow[i][76] + jobsrow[i][77] + jobsrow[i][78] + "</p> " 
            // + " <p>" + jobsrow[i][79] + jobsrow[i][80] + jobsrow[i][81] + jobsrow[i][82] + "</p> " 
            // + " <p>" + jobsrow[i][83] + jobsrow[i][84] + jobsrow[i][85] + jobsrow[i][86] + "</p> " 
            // + " <p>" + jobsrow[i][87] + jobsrow[i][88] + jobsrow[i][89] + jobsrow[i][90] + "</p> " 
            // + " <p>" + jobsrow[i][91] + jobsrow[i][92] + jobsrow[i][93] + jobsrow[i][94] + "</p> " 





      
            
            

            if (job[4]==0) {
                // console.log("no lat found for collection job ref  " + job[0]);
                
                // alert("no lat found for collection");
                
                joberrordata += "<p>No Collection location for <a title='" + job[0] + "' href='order.php?id=" + job[0] + "'>" + job[0] + "</a></p>";
                
                
            } else {
                
                
                if (job[2]<50) { // add collection marker
            
                
                    // console.log("lat found for collection job ref  " + job[0]);
                    
                    var finalLatLng = new google.maps.LatLng(job[5], job[6]);
                    //check to see if any of the existing markers match the latlng of the new marker
                        
    
                    
                    var viatext="";
                    var viaplural;
                    if (viasfound==1) { viaplural="" } else { viaplural="s" }
                    if (viasfound>0) { viatext="<p> via " + viasfound + " location" + viaplural + "</p>"; }
                    
                    var iwindowtext = "<h3><a title='" + job[0] + "' href='order.php?id=" + job[0] + "'>" + job[0] + "</a> "
                    + lookupstatusname(job[2]) 
                    
                    + " </h3> <h4>"
                    + $("#leftmenuselectrider option[value=" + job[1] + "]").text()
                    
                    + "</h4> <hr /> "
                    + " <p> PU: " + job[3] + " " + job[4] + " </p> ";
                    
                    if (viasfound>0) {
                        iwindowtext +=viatext;
                    }
                    
                    iwindowtext += " <p> To: " + job[87] + " " + job[88] + "</p> ";
                
                    finalLatLng = createMarker(finalLatLng,job[3],iwindowtext,image2,(" job collecticon " + job[0] + " rider" + job[1]),1006,("collect"+job[0]));
    
                    linedata.unshift(finalLatLng);                
    
                    } else {
                        // collected, just need to add marker to start
                        
    
                        
                    }
                
            
                }
            

            
            
            
            if (job[89]==0) { // delivery
                // console.log("no lat found for delivery job ref  " + job[0]);
                
                // alert("no lat found for collection");
                
                joberrordata += "<p>No Delivery location for <a title='" + job[0] + "' href='order.php?id=" + job[0] + "'>" + job[0] + "</a></p>";
                
                
            } else {
                
                

                    // console.log("lat found for delivery job ref  " + job[0]);
                    
                    var finalLatLng = new google.maps.LatLng(job[89], job[90]);
                    //check to see if any of the existing markers match the latlng of the new marker
                        
    
                    
                    var viatext="";
                    var viaplural;
                    if (viasfound==1) { viaplural="" } else { viaplural="s" }
                    if (viasfound>0) { viatext="<p> via " + viasfound + " location" + viaplural + "</p>"; }
                    
                    var iwindowtext = "<h3><a title='" + job[0] + "' href='order.php?id=" + job[0] + "'>" + job[0] + "</a> "
                    + lookupstatusname(job[2]) 
                    
                    + " </h3> <h4>"
                    + $("#leftmenuselectrider option[value=" + job[1] + "]").text()
                    
                    + "</h4> <hr /> "
                    + " <p> PU: " + job[3] + " " + job[4] + " </p> ";
                    
                    if (viasfound>0) {
                        iwindowtext +=viatext;
                    }
                    
                    iwindowtext += " <p> To: " + job[87] + " " + job[88] + "</p> ";
                
                    finalLatLng = createMarker(finalLatLng,job[3],iwindowtext,image3,(" job deliver" + job[0]+ " rider" + job[1]),1006,("deliver"+job[0]));
    
                    linedata.push(finalLatLng);
    

            
                }
                        
            
            
            
            
            
            
            
            
            
            
            // console.log("jobsrow.length " + jobsrow.length + " i " + i );
            console.log("linedata: " +  linedata.length + " " + linedata);
            
            line = new google.maps.Polyline({
                path: linedata,
                geodesic: true,
                strokeOpacity: 0.7,
                icons: [{
                    icon: lineSymbol,
                    repeat: "50px"
                }],
                map: map,
                class: "rider"+job[1],
                id: "line"+job[0],
                zIndex: 1600,
                optimized: false
            });
            
            
            linearray.push(line);
            // markers.push(line);
            
            
            
            
        } // end of job loop
        
        
        if (joberrordata!=="") {
            joberrordata+=" <hr /> ";
        }
        
        
        $("#joberrordata").html(joberrordata);
        
        
        if (idlist!=="") {
            idlist+=" <hr /> ";
        }
        
        
        $("#idlist").html(idlist);
        
        
        $("#unscheduleddetails").html(unschedtext);
        $("#numunsched").text(numunsched);
        
        if (totaljobs==1) {
            var tmp='1 Job';
        } else {
            var tmp=totaljobs + ' Jobs ';
        }
        
        $("#totaljobs").text(tmp);
        
        
        
        var nummarkers = markers.length;
        // alert(nummarkers);
        
        if (($("#scaleonrefresh").is(':checked')) && (nummarkers>0))    {
            map.fitBounds(bounds);
        }
        
        if (nummarkers==1) {
            map.setZoom(17);
        }
    
    }
    
    

    
    
    applyriderfilter();
    
    // function createMarker(latlng,name,html,mapicon,markerclass,index,id) {
    
    
    
    
    
    $("#toploader").fadeOut();
}


    function applyriderfilter() {
        var riderfilter=$("#leftmenuselectrider").val();
        
        var showhideloop;
        


        // merge to 1 array ?
        
        var superarray = markers.concat(linearray);

        
        if (riderfilter=='') {
            console.log("All riders");
            for (showhideloop = 0; showhideloop < superarray.length; showhideloop++) {
                superarray[showhideloop].setVisible(true);
            }
          
            
        } else {
            
            
            
            // console.log("riderfilter: " + riderfilter);
            // console.log("riderfilter length: " + riderfilter.length);
            
            

            
            
            console.log("______________________________________________________   superarray" + superarray.length);
            
            
            for (showhideloop = 0; showhideloop < superarray.length; showhideloop++) {

                console.log("riderfilter: " + riderfilter);
                console.log (superarray[showhideloop].class)
                
                
                console.log ("________________________________________   CLASS : " + superarray[showhideloop].class)
                
                
                var visibleflag =0;
                
                var thismarkerclass=superarray[showhideloop].class;
                
                for (var arrayloop = 0; arrayloop < riderfilter.length; arrayloop++) {
                    var ridertocheck="rider"+riderfilter[arrayloop];
                    if (thismarkerclass.includes(ridertocheck)) {
                        visibleflag=1;
                    }
                }
                

                
                

                if (visibleflag>0) {
                    superarray[showhideloop].setVisible(true);
                } else {
                    superarray[showhideloop].setVisible(false);
                }
                
                
            } // each marker loop
            
        } // check for not all markers
        
        console.log(" if visible set bounds then rest map ");
        
        
    }

    function refreshmap() {
        $("#toploader").show();
        oldriders = riders;
        oldjobsrow = jobsrow;
        $.ajax({
            type: "post",
            cache:false,
            url:"ajax_lookup.php",
            data: {
                lookuppage: "livemap",
                ridertimerange: $("#prevridertime").val(),
                jobslider: $("#jobslider").val()
            },
            success: function (data){
                $("#gmap_wrapper").append(data);
            },
            complete: function (data) {
                addtomap();
            }
        });
    }

    function autorefreshmap() {
        

        
        refreshmap();
        setTimeout(autorefreshmap, 20000);
    }

    
    

    
    
</script>
</head>
<body> 
<?php 


//  $adminmenu="1";
$filename="map.php";
include "cojmmenu.php";

?>

<div id="gmap_wrapper" >
<div class="full_map" id="search_map">
<div id="map-canvas" class="onehundred" ></div>
</div>
<div class="gmap_left" id="scrolltable">
<div class="pad10">
<h3>Live Job Map</h3>
<span id="maplastupdated">Last Updated : </span>

<?php

$query = "SELECT CyclistID, cojmname FROM Cyclist WHERE Cyclist.isactive='1' ORDER BY CyclistID"; 
$riderdata = $dbh->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);
    

    
//////////     CYCLIST   DROPDOWN     ///////////////////////////////////



$menuhtml= ' <select id="leftmenuselectrider" multiple="multiple" class="ui-corner-left ui-state-default bigmap" title="Filter by '.$globalprefrow['glob5'].'" >';
$menuhtml.= ' <option selected value=""> All '.$globalprefrow['glob5'].'s </option> ';

foreach ($riderdata as $ridernum => $ridername) {
    $ridername=htmlspecialchars($ridername);
    $menuhtml.= ("<option ");
    $menuhtml.= ("value=\"$ridernum\">$ridername</option>");
}
$menuhtml.= '</select> ';

echo $menuhtml;

?>


<hr />



<div id="test"> </div>


<datalist id="tickmarks">
<option>0</option>
<option>12</option>
<option>24</option>
<option>36</option>
<option>48</option>
<option>96</option>
</datalist>


<input id="prevridertime" class="mapslider" title="Rider Positions" type="range" min="0" max="48" step="0.5" value="1" list="tickmarks" />
<span id="totalriderpositions">0 Riders have</span> GPS in previous <span id="prevriderhours">Hr</span>.

<hr />

<div class="sliderdiv">
<input id="jobslider" class="mapslider" title="Future Jobs" type="range" min="0" max="96" step="0.5" value="1" list="tickmarks" />

<span id="totaljobs">0 Jobs </span> in next <span id="jobsliderval">Hr</span>.
</div>

<hr />

<p title="Unscheduled jobs except bulk drops"><span id="numunsched">0</span> Unscheduled 
<span id="unscheduleddetails"></span>
</p>
<p>0 Scheduled</p>
<p><span id="">0</span> En-route 
<span id="enroutedetails"></span>
</p>
<hr />


<div id="joberrordata" > </div>


<div id="ridertable"> </div>

<div id="idlist" > </div>


<div id="sidebarfromajax"> </div>

<input class="cbbcheckbox" id="scaleonrefresh" name="scaleonrefresh" value="1" checked="" type="checkbox"> Auto rescale on refresh <br />

<hr />


Add rider filter to job / rider list in left menus <br />

Paused / En-Route dashed line <br />

Paused into left hand menu <br />

Add times ( collect-by, deliver by ) <br />

Add public / private jobcomments <br />

Add Service ( 1x Mileage Rate unless hide service name unchecked? ) <br />


Areas - from extra lookup - put results from each job into array<br />
Sub-areas - from extra lookup <br />
Labels - For Areas <br />

Title on rider details table <br />
Checkbox Pause / Resume Live Updates <br />

Cluster View <br />



Play sound on unscheduled + top menu bar alert <br />



Map key ( eg line colours in OSM / line colours in GMap Cycle Layer) <br />

Map Tiles Thunderforest OSM <br />

Block surrounding powered by cojm - seperate paragraphs <hr />



Github Todo - 

Filter by multiple services <br />




</div>		
</div>

</div>

<?php

include "footer.php";

echo '</body>
</html>';
