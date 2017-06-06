//     COJM Courier Online Operations Management
//     maptemplate.js - Javascript File used to initialise google maps template with custom controls
//     Copyright (C) 2017 S.Young cojm.co.uk
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as
//    published by the Free Software Foundation, either version 3 of the
//    License, or (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.

// console.log("in maptemplate.js ln3");

    // Compare Array Function
    // http://stackoverflow.com/questions/7837456/how-to-compare-arrays-in-javascript
    
    
    // Warn if overriding existing method
if(Array.prototype.equals)
    console.warn("Overriding existing Array.prototype.equals. Possible causes: New API defines the method, there's a framework conflict or you've got double inclusions in your code.");
// attach the .equals method to Array's prototype to call it on any array
Array.prototype.equals = function (array) {
    // if the other array is a falsy value, return
    if (!array)
        return false;

    // compare lengths - can save a lot of time 
    if (this.length != array.length)
        return false;

    for (var i = 0, l=this.length; i < l; i++) {
        // Check if we have nested arrays
        if (this[i] instanceof Array && array[i] instanceof Array) {
            // recurse into the nested arrays
            if (!this[i].equals(array[i]))
                return false;       
        }           
        else if (this[i] != array[i]) { 
            // Warning - two different object instances will never be equal: {x:20} != {x:20}
            return false;   
        }           
    }       
    return true;
}
// Hide method from for-in loops
Object.defineProperty(Array.prototype, "equals", {enumerable: false});
    
    
    
var maplastupdated;
var ridertable;
var totalareas=0;
var markertype1=0;
var markertype2=0;
var markertype3=0;
var iconPath;
var map;

var markers = []; // Create a marker array to hold your markers
var riders = [];
var oldriders = [];
var jobsrow = [];
var oldjobsrow = [];
var mylat,mylon;

var firstrungetlocation=0;
var mycoords;
var printtext='';
var printcopyr;
var worldCoords = [];
var mainareamarkers=[];
var existingmap=0;
var oldb64lineCoordinates='';
var oldb64locations='';
var oldmainarea='';
var mainarea=[];
var b64mainarea=[];
var maxlat = -99;
var maxlng = -99;
var minlat = 999;
var minlng = 999;

var locations=[];

var ridermaxlat = -99;
var ridermaxlng = -99;
var riderminlat = 999;
var riderminlng = 999;
    
    
var allsubareamarkers=[];    
var subareaarray=[];
var mainareamaxlat = -99;
var mainareamaxlng = -99;
var mainareaminlat = 999;
var mainareaminlng = 999;    
    
var oldheight=0;
var line;
var polymainarea;
var maptimes;
var mapofselectedopsmapsubarea;
var mapsofsubareas=[];
var b64jobstuff;
var oldb64jobstuff;
var jobstuffcoords=[];
var jobarray=[];

var jobmaxlat = -99;
var jobmaxlng = -99;
var jobminlat = 999;
var jobminlng = 999;
var b64clientlocations;
var oldb64clientlocations;
var clientcoords=[];
var clientdetailarray=[];
var goldStar;
var mylocationicon;
// console.log("in maptemplate.js ln124");



    
function initialize() {
    

    
    
    
    
    console.log("initialize function triggered ln137");
    
    var geocoder = null;
 
    var element = document.getElementById("map-canvas");
    var mapTypeIds = ["OSM", "roadmap", "satellite", "hybrid", "terrain", "OCM", "OTM"];
    
    
    goldStar = {
        path: 'M 125,5 155,90 245,90 175,145 200,230 125,180 50,230 75,145 5,90 95,90 z',
        fillColor: 'yellow',
        fillOpacity: 0.3,
        scale: 0.15,
        strokeColor: 'gold',
        strokeWeight: 2,
        anchor: new google.maps.Point(125, 125)
    };    
    
    
    mylocationicon = {
    path: google.maps.SymbolPath.CIRCLE,
    fillOpacity: 0.4,
    fillColor: '#0000FF',
    strokeOpacity: 1.0,
    strokeColor: '#A668E6',
    strokeWeight: 2.0, 
    scale: 10 //pixels
  }
    
    
    var mymapstyle;

    var mapwidth=$("#map-canvas").width();
    
    if (mapwidth>450) {
            mymapstyle=google.maps.MapTypeControlStyle.HORIZONTAL_BAR;
        } else {
            mymapstyle=google.maps.MapTypeControlStyle.DROPDOWN_MENU;
        }
    
    map = new google.maps.Map(element, {
        center: new google.maps.LatLng(globlat,globlon),
        zoom: 11,
        mapTypeId: "OSM",
		scaleControl: true,
		mapTypeControl: true,
        fullscreenControl: true,
        mapTypeControlOptions: {
            mapTypeIds: mapTypeIds,
            style: mymapstyle
        }
    });
	
    map.mapTypes.set("OSM", new google.maps.ImageMapType({
        getTileUrl: function(coord, zoom) {
            return "https://a.tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
        },
        tileSize: new google.maps.Size(256, 256),
        name: "OSM",
        alt: "Open Street Map",
        maxZoom: 19
    }));	
	
    map.mapTypes.set("OCM", new google.maps.ImageMapType({
        getTileUrl: function(coord, zoom) {
                return "https://a.tile.thunderforest.com/cycle/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
        },
        tileSize: new google.maps.Size(256, 256),
        name: "OCM",
        alt: "Open Cycle Map",
        maxZoom: 19
    }));
    

    map.mapTypes.set("OTM", new google.maps.ImageMapType({
        getTileUrl: function(coord, zoom) {
                return "https://a.tile.thunderforest.com/transport/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
        },
        tileSize: new google.maps.Size(256, 256),
        name: "OTM",
        alt: "Open Transit Map",
        maxZoom: 19
    }));

    
    
    var bikeLayer = new google.maps.BicyclingLayer();
    var transitLayer = new google.maps.TransitLayer();
    var trafficLayer = new google.maps.TrafficLayer();
    var gmapincyclelayer=0;
    var gmapintransitlayer=0;
    var gmapintrafficlayer=0;
    
      /**
       * The CenterControl adds a control to the map that recenters the map on
       * Chicago.
       * This constructor takes the control DIV as an argument.
       * @constructor
       */
    function CenterControl(controlDiv, map) { // cycle layer

        // Set CSS for the control border.
        var controlUI = document.createElement('div');
        controlUI.title = 'Google Cycling Layer';
        controlUI.id = 'gmapcyclelayerbutton';
        controlUI.className = 'map-control';
        controlUI.style.display = 'none';
        controlDiv.appendChild(controlUI);

        // Setup the click event listeners: simply set the map to Chicago.
        controlUI.addEventListener('click', function() {
            
            if (gmapincyclelayer==1) {
                $("#gmapcyclelayerbutton").removeClass("gmapbuttonselected");
                bikeLayer.setMap(null);
                gmapincyclelayer=0;
            }
            
            else {
                gmapincyclelayer=1;            
                bikeLayer.setMap(map);
                $("#gmapcyclelayerbutton").addClass("gmapbuttonselected");
            }          
        });

    }
    
    
    // Create the DIV to hold the control and call the CenterControl()
    // constructor passing in this DIV.
    var centerControlDiv = document.createElement('div');
    var centerControl = new CenterControl(centerControlDiv, map);


    
    function CenterControlb(controlDivb, map) { // transit layer

        // Set CSS for the control border.
        var controlUIb = document.createElement('div');
        controlUIb.title = 'Google Transit Layer';
        controlUIb.id = 'gmaptransitlayerbutton';
        controlUIb.className = 'map-control';
        controlUIb.style.display = 'none';
        controlDivb.appendChild(controlUIb);


        // Setup the click event listeners: simply set the map to Chicago.
        controlUIb.addEventListener('click', function() {
            
            if (gmapintransitlayer==1) {
                transitLayer.setMap(null);
                $("#gmaptransitlayerbutton").removeClass("gmapbuttonselected");
                gmapintransitlayer=0;
            } else {
                gmapintransitlayer=1;
                $("#gmaptransitlayerbutton").addClass("gmapbuttonselected");
                transitLayer.setMap(map);
            }
        });

    }
    
    
    // Create the DIV to hold the control and call the CenterControl()
    // constructor passing in this DIV.
    var centerControlDivb = document.createElement('div');
    var centerControlb = new CenterControlb(centerControlDivb, map);

    
    
    function CenterControlc(controlDivc, map) { // traffic layer

        // Set CSS for the control border.
        var controlUIc = document.createElement('div');
        controlUIc.title = 'Google Traffic Layer';
        controlUIc.id = 'gmaptrafficlayerbutton';
        controlUIc.className = 'map-control';
        controlUIc.style.display = 'none';
        controlDivc.appendChild(controlUIc);

        // Setup the click event listeners: simply set the map to Chicago.
        controlUIc.addEventListener('click', function() {
            
            if (gmapintrafficlayer==1) {
                trafficLayer.setMap(null);
                gmapintrafficlayer=0;
                $("#gmaptrafficlayerbutton").removeClass("gmapbuttonselected");
                
            } else {
                gmapintrafficlayer=1;
                trafficLayer.setMap(map);
                $("#gmaptrafficlayerbutton").addClass("gmapbuttonselected");
            }
        });
    }
    
    
    // Create the DIV to hold the control and call the CenterControl()
    // constructor passing in this DIV.
    var centerControlDivc = document.createElement('div');
    var centerControlc = new CenterControlc(centerControlDivc, map);    

    
    function CenterControld(controlDivd, map) { // mapsearch

        // Set CSS for the control border.
        var controlUId = document.createElement('input');
        controlUId.title = 'Address Search';
        controlUId.type = 'text';
        controlUId.placeholder = 'Search Map';        
        controlUId.id = 'geocodeaddress';
        controlUId.className = 'mapsearchbutton map-control';
        controlUId.style.display = '';
        controlDivd.appendChild(controlUId);

        
        controlUId.addEventListener('focus', function() {
            
            $("#geocodeaddress").removeClass("mapsearchbutton");
            $("#geocodeaddress").addClass("ui-state-default ui-corner-all");
        });
        

        // Setup the click event listeners: simply set the map to Chicago.
        controlUId.addEventListener('change', function() {
            
            var addtocheck=$("#geocodeaddress").val();
            
            if (addtocheck==''){
            } else {
            
                // alert(" 249 changed ");
                $("#toploader").show();
                geocoder = new google.maps.Geocoder(); 
                bounds = new google.maps.LatLngBounds();
                
                geocoder.geocode( { 
                "address": addtocheck + " , UK ",
                "region":   "uk",
                "bounds": bounds 
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                            map.setCenter(results[0].geometry.location);
                            var infowindow = new google.maps.InfoWindow(
                                { content: "<div class='info'>"+addtocheck+"</div>",
                                    position: results[0].geometry.location,
                                    map: map
                                });
                            infowindow.open(map);
                        } else {
                            alert("No results found");
                        }
                    } else {
                        alert("Search was not successful : " + status);
                    }
                    $("#toploader").fadeOut();
                });
            }
        });
    }
    
    
    // Create the DIV to hold the control and call the CenterControl()
    // constructor passing in this DIV.
    var centerControlDivd = document.createElement('div');
    var centerControld = new CenterControld(centerControlDivd, map);    
    
    
    
    
    
    function CenterControle(controlDive, map) { // current position

        // Set CSS for the control border.
        var controlUIe = document.createElement('div');
        controlUIe.title = 'Current Position';
        controlUIe.id = 'mylocationbutton';
        controlUIe.className = 'map-control';
        // controlUIe.style.opacity = '0.4';
        controlDive.appendChild(controlUIe);

        // Setup the click event listeners: simply set the map to Chicago.
        controlUIe.addEventListener('click', function() {
            console.log (" my location button ");
            if (mycoords) {
                map.panTo(mycoords);
            }
            
        });
    }
    
    
    // Create the DIV to hold the control and call the CenterControl()
    // constructor passing in this DIV.
    var centerControlDive = document.createElement('div');
    var centerControle = new CenterControle(centerControlDive, map);       
    
    function CenterControlf(controlDivf, map) { // print screen

        // Set CSS for the control border.
        var controlUIf = document.createElement('div');
        controlUIf.title = 'Print View';
        controlUIf.id = 'printbutton';
        controlUIf.className = 'map-control';
        // controlUIe.style.opacity = '0.4';
        controlDivf.appendChild(controlUIf);

        // Setup the click event listeners: simply set the map to Chicago.
        controlUIf.addEventListener('click', function() {
            console.log (" print screen button ");

            printAnyMaps();
            
            // window.print();

        });
    }
    
    

    // Create the DIV to hold the control and call the CenterControl()
    // constructor passing in this DIV.
    var centerControlDivf = document.createElement('div');
    var centerControlf = new CenterControlf(centerControlDivf, map);       
    


    
    centerControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.LEFT_CENTER].push(centerControlDiv);
    
    centerControlDivb.index = 1;
    map.controls[google.maps.ControlPosition.LEFT_CENTER].push(centerControlDivb);
    
    centerControlDivc.index = 1;
    map.controls[google.maps.ControlPosition.LEFT_CENTER].push(centerControlDivc);
    

    centerControlDivd.index = 2;
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDivd);
    

    centerControlDivf.index = 2;
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDivf);
    
    centerControlDive.index = 2;
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDive);


    
    var osmlink="Map Data &copy; <a style='color:#444444' href='https://www.openstreetmap.org/copyright' target='_blank'>OpenStreetMap</a> contributors";
    var osmcopyr="<span style='background: white; color:#444444; padding-left: 4px; '> " + osmlink +  "</span>";

    var gmaplink = "Map Data &copy; Google"
    
    var outerdiv = document.createElement("div");
    outerdiv.id = "outerdiv";
    outerdiv.className = "gmnoprint";
    
    map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(outerdiv);

    
    
    
    function checkcopyr (){
        var checkmaptype = map.getMapTypeId();
        
        if ( checkmaptype=="OSM" || checkmaptype=="OCM" || checkmaptype=="OTM") {
            $("div#outerdiv").html(osmcopyr);
            printcopyr=osmlink;
            
        } else {
            $("div#outerdiv").text("");
            $("span.printcopyr").html(gmaplink);
            printcopyr=gmaplink;
        }
            
        if ( checkmaptype=="roadmap" || checkmaptype=="terrain" || checkmaptype=="hybrid") {
            $("#gmapcyclelayerbutton").show();
            $("#gmaptransitlayerbutton").show();
            $("#gmaptrafficlayerbutton").show();
            
        } else {
            $("#gmaptransitlayerbutton").hide();
            $("#gmapcyclelayerbutton").hide();
            $("#gmaptrafficlayerbutton").hide();
        }
    }
    
    
    
    google.maps.event.addListener( map, "maptypeid_changed", checkcopyr);

    
    google.maps.event.addListenerOnce(map, 'idle', function(){
    //loaded fully
        $("#toploader").fadeOut();
        checkcopyr();
    });
    


    $(window).resize(function () {
        var menuheight=0;
        $(".top_menu_line").each(function( index ) {
            if ($(this).is(':visible')) {
                menuheight = menuheight + $( this ).height();
            }
        });
        //        var h = ;
        $("#gmap_wrapper").css("height", ($(window).height() - menuheight));
    }).resize();


    function zeroPad(num, places) {
    var zero = places - num.toString().length + 1;
    return Array(+(zero > 0 && zero)).join("0") + num;
    }



    worldCoords = [
        new google.maps.LatLng(85,180),
        new google.maps.LatLng(85,90),
        new google.maps.LatLng(85,0),
        new google.maps.LatLng(85,-90),
        new google.maps.LatLng(85,-180),
        new google.maps.LatLng(0,-180),
        new google.maps.LatLng(-85,-180),
        new google.maps.LatLng(-85,-90),
        new google.maps.LatLng(-85,0),
        new google.maps.LatLng(-85,90),
        new google.maps.LatLng(-85,180),
        new google.maps.LatLng(0,180),
        new google.maps.LatLng(85,180)]; 
 
 



        
        
        
    $("#mylocationbutton").css({ opacity: "1"});
    
    /*
    
    var mypositionimage = {
        url: bluedotlink,
        scaledSize: new google.maps.Size(26, 26), // scaled size
        origin: new google.maps.Point(0,0),
        anchor: new google.maps.Point(13, 13),
        zIndex: 1
    }; 


    */
    
    
    if (navigator.geolocation) {
        
        
        navigator.geolocation.getCurrentPosition(
        currentpositionsuccess,errorCallback_highAccuracy,{
            maximumAge:600000,
            timeout:5000,
            enableHighAccuracy: true
        }); 
    

        if (location.protocol=="file:") {
            console.log( " file protocol, do not get location updates");
        
        } else {
            // console.log( " web protocol, get location updates as may be viewing on mobile");

            setInterval(function() { // runs once a minute to get latest position
                navigator.geolocation.getCurrentPosition(currentpositionsuccess, errorCallback_highAccuracy,{
                    maximumAge:60000,
                    timeout:3000,
                    enableHighAccuracy: true
                });
            }, 60 * 1000); // 60 * 1000 milsec    
        
        }
        
        
    } else {
        error("Geo Location is not supported");
        console.log( 'GEO NOT SUPPORTED 453' );
        $("#mylocationbutton").hide();
    }
    
    function currentpositionsuccess(position) {
        // console.log( ' currentpositionsuccess 480 ' );
        mycoords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        var currentdate = new Date(); 
        mytitle= "Accurate to " + position.coords.accuracy + "m, updated " + zeroPad(currentdate.getHours(), 2) + ":"  + zeroPad(currentdate.getMinutes(), 2);
        $("#mylocationbutton").css({ opacity: "1"});
        
        if (firstrungetlocation==0) {
            // console.log( ' no marker 485 ' );
            mymarker = new google.maps.Marker({
                position: mycoords,
                map: map,
                title:mytitle,
                icon: mylocationicon,
                zIndex:1,
                cursor: 'url("https://maps.gstatic.com/mapfiles/openhand_8_8.cur"), default',
                optimized: false
            });   
            firstrungetlocation=1;   
            
        } else {
            // console.log( ' already marker 494 ' );
            mymarker.setPosition(mycoords);  
            mymarker.setTitle(mytitle);
            // var latitude = position.coords.latitude;
            // var longitude = position.coords.longitude;
            
        }

        $(document).ready(function () {
            
            $("#mylocationbutton").css({ opacity: "1"});
            $('#mylocationbutton').prop('title', mytitle);
            
            setTimeout(myTimeout1, 3000);
        });
        
    }
    
    
    function myTimeout1() {
        $("#mylocationbutton").css({ opacity: "1"});
        $('#mylocationbutton').prop('title', mytitle);
        
    }
    

    
    function errorCallback_highAccuracy(position) {
        $("#mylocationbutton").hide();
    }
    
    var googleMapWidth;
    var googleMapHeight;
    
    function printAnyMaps() {
        
        var printlegend=' <div id="printlegend" >  '
        + ' <button id="btn-exit-print-screen" title="Normal View" > </button>  '
        + ' <p><img alt="' + globalshortname + ' Logo" src="' + adminlogo + '"> </p> ' +
        printtext 
        + ' <p class="mapprint" id="mapprintcopyr">' + printcopyr + '<br />Powered by COJM</p></div>';
    
        $("#map-canvas").append(printlegend);
        $(".map-control").hide();
        $(".gm-fullscreen-control").hide();
        $(".gm-style-mtc").hide();
        $(".gmnoprint").hide();
        
        
        //  '	poly'.$areaid.'.setOptions({ fillColor: "#778899"	});	';
        // poly'.$areaid.'.setOptions({ fillColor: "white" });	
        
        googleMapWidth = $("#map-canvas").css("width");
        googleMapHeight = $("#map-canvas").css("height");
        
        $("#map-canvas").height(700);
        $("#map-canvas").width(1050);
        $("#map-canvas").css({overflow: "visible"});
        google.maps.event.trigger(map, 'resize');
        var $body = $('body');
        var $mapContainer = $('#map-canvas');
        var $mapContainerParent = $mapContainer.parent();
        var $printContainer = $('<div class="printContainer" style="position:relative; height:700px; width: 1050px; overflow:hidden ">');
    
    
        $printContainer
            .append($mapContainer)
            .prependTo($body);
        
        var $content = $body
            .children()
            .not($printContainer)
            .detach();
        
            
        var $oldcontent = $body
            .children()
            .not($printContainer)
            .not('script')
            .detach(); 
            
        var $patchedStyle = $('<style media="print">')
            .text(
            'img { max-width: none !important; }' +
            'a[href]:after { content: ""; }' +
            ' @page { margin: 0.5cm; }' +
            ' #btn-exit-print-screen { display:none; } ' +
            ' #backfromprintlink { display:none; } '
            )
            .appendTo('head');
    
            
        var $patchedStyleb = $('<style>')
            .text(
            ' '+
            ' div.gm-style-cc { display:none; } '
            )
            .appendTo('head');
            
            var $patchedStylec = $('<div>')
            .html('<button id="backfromprintlink"><h1 >Exit Print View</h1></button>')
            .appendTo($body);            
            
        console.log (" map loaded 404 ");
        
        // alert(" Works best in Firefox ");
        



        
        $(document).keyup(function(e) {
            if (e.keyCode == 27) { // escape key maps to keycode `27`
                backfromprint();
            }
        });	
        
        
        $("#btn-exit-print-screen").click(function() { 
            backfromprint();
        });
        
        $("#backfromprintlink").click(function() { 
            backfromprint();
        });        
        
            
        function backfromprint() {
            
            // $("#btn-exit-full-screen").trigger("click");
            console.log ("from print view putting back to normal view");
            
            $("#printlegend").remove();
            
            $body.prepend($content);
            $mapContainerParent.prepend($mapContainer);
            
            $printContainer.remove();
            $patchedStyle.remove();
            $patchedStyleb.remove();
            $patchedStylec.remove();
            $("#map-canvas").css({overflow: "hidden"});
        
            $("#map-canvas").css("height", googleMapHeight);
            $("#map-canvas").css("width", "");
            
            $(".map-control").show();
            $(".gm-fullscreen-control").show();
            $(".gm-style-mtc").show();
            $(".gmnoprint").show();
            
            
            
            
            
            

            
            
            
            
            checkcopyr();
            google.maps.event.trigger(map, 'resize');
        }

    }
    

}


 
function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}






	// for google maps markers https://github.com/googlemaps/js-rich-marker/blob/gh-pages/src/richmarker-compiled.js
	
	(function(){var b=true,f=false;function g(a){var c=a||{};this.d=this.c=f;if(a.visible==undefined)a.visible=b;if(a.shadow==undefined)a.shadow="7px -3px 5px rgba(88,88,88,0.7)";if(a.anchor==undefined)a.anchor=i.BOTTOM;this.setValues(c)}g.prototype=new google.maps.OverlayView;window.RichMarker=g;g.prototype.getVisible=function(){return this.get("visible")};g.prototype.getVisible=g.prototype.getVisible;g.prototype.setVisible=function(a){this.set("visible",a)};g.prototype.setVisible=g.prototype.setVisible;
g.prototype.s=function(){if(this.c){this.a.style.display=this.getVisible()?"":"none";this.draw()}};g.prototype.visible_changed=g.prototype.s;g.prototype.setFlat=function(a){this.set("flat",!!a)};g.prototype.setFlat=g.prototype.setFlat;g.prototype.getFlat=function(){return this.get("flat")};g.prototype.getFlat=g.prototype.getFlat;g.prototype.p=function(){return this.get("width")};g.prototype.getWidth=g.prototype.p;g.prototype.o=function(){return this.get("height")};g.prototype.getHeight=g.prototype.o;
g.prototype.setShadow=function(a){this.set("shadow",a);this.g()};g.prototype.setShadow=g.prototype.setShadow;g.prototype.getShadow=function(){return this.get("shadow")};g.prototype.getShadow=g.prototype.getShadow;g.prototype.g=function(){if(this.c)this.a.style.boxShadow=this.a.style.webkitBoxShadow=this.a.style.MozBoxShadow=this.getFlat()?"":this.getShadow()};g.prototype.flat_changed=g.prototype.g;g.prototype.setZIndex=function(a){this.set("zIndex",a)};g.prototype.setZIndex=g.prototype.setZIndex;
g.prototype.getZIndex=function(){return this.get("zIndex")};g.prototype.getZIndex=g.prototype.getZIndex;g.prototype.t=function(){if(this.getZIndex()&&this.c)this.a.style.zIndex=this.getZIndex()};g.prototype.zIndex_changed=g.prototype.t;g.prototype.getDraggable=function(){return this.get("draggable")};g.prototype.getDraggable=g.prototype.getDraggable;g.prototype.setDraggable=function(a){this.set("draggable",!!a)};g.prototype.setDraggable=g.prototype.setDraggable;
g.prototype.k=function(){if(this.c)this.getDraggable()?j(this,this.a):k(this)};g.prototype.draggable_changed=g.prototype.k;g.prototype.getPosition=function(){return this.get("position")};g.prototype.getPosition=g.prototype.getPosition;g.prototype.setPosition=function(a){this.set("position",a)};g.prototype.setPosition=g.prototype.setPosition;g.prototype.q=function(){this.draw()};g.prototype.position_changed=g.prototype.q;g.prototype.l=function(){return this.get("anchor")};g.prototype.getAnchor=g.prototype.l;
g.prototype.r=function(a){this.set("anchor",a)};g.prototype.setAnchor=g.prototype.r;g.prototype.n=function(){this.draw()};g.prototype.anchor_changed=g.prototype.n;function l(a,c){var d=document.createElement("DIV");d.innerHTML=c;if(d.childNodes.length==1)return d.removeChild(d.firstChild);else{for(var e=document.createDocumentFragment();d.firstChild;)e.appendChild(d.firstChild);return e}}function m(a,c){if(c)for(var d;d=c.firstChild;)c.removeChild(d)}
g.prototype.setContent=function(a){this.set("content",a)};g.prototype.setContent=g.prototype.setContent;g.prototype.getContent=function(){return this.get("content")};g.prototype.getContent=g.prototype.getContent;
g.prototype.j=function(){if(this.b){m(this,this.b);var a=this.getContent();if(a){if(typeof a=="string"){a=a.replace(/^\s*([\S\s]*)\b\s*$/,"$1");a=l(this,a)}this.b.appendChild(a);var c=this;a=this.b.getElementsByTagName("IMG");for(var d=0,e;e=a[d];d++){google.maps.event.addDomListener(e,"mousedown",function(h){if(c.getDraggable()){h.preventDefault&&h.preventDefault();h.returnValue=f}});google.maps.event.addDomListener(e,"load",function(){c.draw()})}google.maps.event.trigger(this,"domready")}this.c&&
this.draw()}};g.prototype.content_changed=g.prototype.j;function n(a,c){if(a.c){var d="";if(navigator.userAgent.indexOf("Gecko/")!==-1){if(c=="dragging")d="-moz-grabbing";if(c=="dragready")d="-moz-grab"}else if(c=="dragging"||c=="dragready")d="move";if(c=="draggable")d="pointer";if(a.a.style.cursor!=d)a.a.style.cursor=d}}
function o(a,c){if(a.getDraggable())if(!a.d){a.d=b;var d=a.getMap();a.m=d.get("draggable");d.set("draggable",f);a.h=c.clientX;a.i=c.clientY;n(a,"dragready");a.a.style.MozUserSelect="none";a.a.style.KhtmlUserSelect="none";a.a.style.WebkitUserSelect="none";a.a.unselectable="on";a.a.onselectstart=function(){return f};p(a);google.maps.event.trigger(a,"dragstart")}}
function q(a){if(a.getDraggable())if(a.d){a.d=f;a.getMap().set("draggable",a.m);a.h=a.i=a.m=null;a.a.style.MozUserSelect="";a.a.style.KhtmlUserSelect="";a.a.style.WebkitUserSelect="";a.a.unselectable="off";a.a.onselectstart=function(){};r(a);n(a,"draggable");google.maps.event.trigger(a,"dragend");a.draw()}}
function s(a,c){if(!a.getDraggable()||!a.d)q(a);else{var d=a.h-c.clientX,e=a.i-c.clientY;a.h=c.clientX;a.i=c.clientY;d=parseInt(a.a.style.left,10)-d;e=parseInt(a.a.style.top,10)-e;a.a.style.left=d+"px";a.a.style.top=e+"px";var h=t(a);a.setPosition(a.getProjection().fromDivPixelToLatLng(new google.maps.Point(d-h.width,e-h.height)));n(a,"dragging");google.maps.event.trigger(a,"drag")}}function k(a){if(a.f){google.maps.event.removeListener(a.f);delete a.f}n(a,"")}
function j(a,c){if(c){a.f=google.maps.event.addDomListener(c,"mousedown",function(d){o(a,d)});n(a,"draggable")}}function p(a){if(a.a.setCapture){a.a.setCapture(b);a.e=[google.maps.event.addDomListener(a.a,"mousemove",function(c){s(a,c)},b),google.maps.event.addDomListener(a.a,"mouseup",function(){q(a);a.a.releaseCapture()},b)]}else a.e=[google.maps.event.addDomListener(window,"mousemove",function(c){s(a,c)},b),google.maps.event.addDomListener(window,"mouseup",function(){q(a)},b)]}
function r(a){if(a.e){for(var c=0,d;d=a.e[c];c++)google.maps.event.removeListener(d);a.e.length=0}}
function t(a){var c=a.l();if(typeof c=="object")return c;var d=new google.maps.Size(0,0);if(!a.b)return d;var e=a.b.offsetWidth;a=a.b.offsetHeight;switch(c){case i.TOP:d.width=-e/2;break;case i.TOP_RIGHT:d.width=-e;break;case i.LEFT:d.height=-a/2;break;case i.MIDDLE:d.width=-e/2;d.height=-a/2;break;case i.RIGHT:d.width=-e;d.height=-a/2;break;case i.BOTTOM_LEFT:d.height=-a;break;case i.BOTTOM:d.width=-e/2;d.height=-a;break;case i.BOTTOM_RIGHT:d.width=-e;d.height=-a}return d}
g.prototype.onAdd=function(){if(!this.a){this.a=document.createElement("DIV");this.a.style.position="absolute"}if(this.getZIndex())this.a.style.zIndex=this.getZIndex();this.a.style.display=this.getVisible()?"":"none";if(!this.b){this.b=document.createElement("DIV");this.a.appendChild(this.b);var a=this;google.maps.event.addDomListener(this.b,"click",function(){google.maps.event.trigger(a,"click")});google.maps.event.addDomListener(this.b,"mouseover",function(){google.maps.event.trigger(a,"mouseover")});
google.maps.event.addDomListener(this.b,"mouseout",function(){google.maps.event.trigger(a,"mouseout")})}this.c=b;this.j();this.g();this.k();var c=this.getPanes();c&&c.overlayImage.appendChild(this.a);google.maps.event.trigger(this,"ready")};g.prototype.onAdd=g.prototype.onAdd;
g.prototype.draw=function(){if(!(!this.c||this.d)){var a=this.getProjection();if(a){var c=this.get("position");a=a.fromLatLngToDivPixel(c);c=t(this);this.a.style.top=a.y+c.height+"px";this.a.style.left=a.x+c.width+"px";a=this.b.offsetHeight;c=this.b.offsetWidth;c!=this.get("width")&&this.set("width",c);a!=this.get("height")&&this.set("height",a)}}};g.prototype.draw=g.prototype.draw;g.prototype.onRemove=function(){this.a&&this.a.parentNode&&this.a.parentNode.removeChild(this.a);k(this)};
g.prototype.onRemove=g.prototype.onRemove;var i={TOP_LEFT:1,TOP:2,TOP_RIGHT:3,LEFT:4,MIDDLE:5,RIGHT:6,BOTTOM_LEFT:7,BOTTOM:8,BOTTOM_RIGHT:9};window.RichMarkerPosition=i;
})();
	



// console.log("in maptemplate.js ln774 finishing");
