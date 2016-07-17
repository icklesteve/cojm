google.setOnLoadCallback(function(){
    myMApp();
});

var map, mapOptions, panoMap, resultBounds, timeout, markers  = [];

/**
 * Function      myMApp
 * @author:      Stephan Schmitz <eyecatchup@gmail.com>
 *
 * @description: *  Initializes a new google.maps.Map with custom styles.
 *               *  Gets data from an external JSON file and creates
 *                  marker objects from that data.
 *               *  Creates a jQuery animated link list to toggle the mapp-
 *                  ed marker icons by the specified filter variable.
 **/
function myMApp(){

    /**
     * The initial Map center.
     **/
    var homeLocation = new google.maps.LatLng(52.5, -2);

    /**
     * Set the initial options for the map object to be created.
     **/
    mapOptions = {
        zoom:      6,
        center:    homeLocation,
		maxZoom: 17,
    }

    /**
     * Initialize a new google.maps.Map object.
     **/
    map = new google.maps.Map($('#map')[0], mapOptions);

    /**
     * Create a custom style with a minimal
     * map layout to enhance the user's overview.
     **/
    var customStyles = [
      {
        featureType: "administrative.country",
        elementType: "labels",
        stylers: [
          { visibility: "on" },
          { lightness: 30 },
          { gamma: 3 }
        ]
      },{
        featureType: "poi",
        elementType: "all",
        stylers: [
          { visibility: "off" }
        ]
      },{
        featureType: "landscape",
        elementType: "all",
        stylers: [
          { lightness: 50 }
        ]
      }
    ];

    /**
     * Custom map style requires also a name to be set!
     */
    var customMapType = new google.maps.StyledMapType(
      customStyles, {name: "plainMap"});

    /**
     * Sets the registry to associate the passed string
     * identifier with the passed MapType to be available
     * for the map object.
     **/
    map.mapTypes.set('plainMap', customMapType);

    /**
     * Assign the custom map type to the map.
     */
    map.setMapTypeId('plainMap');

    /**
     * Add a listener that changes the map type when zooming closer.
     */
    google.maps.event.addListener(map, "zoom_changed", function() {
        if(map.getZoom() > 6 &&
           google.maps.MapType != google.maps.MapTypeId.ROADMAP)
        {
            map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
        }
      
    });

 

    /**
     * Get the JSON data that shall be mapped.
     **/
    $.getJSON("locations.json.php", function(json){

        /**
         * Make sure we have a markers array, holding some data.
         **/
        if (json.markers && json.markers.length){

            /**
             * New bounds object for the total results' bb.
             **/
            var bounds = new google.maps.LatLngBounds();

            /**
             * Loop through the JSON array's markers array.
             **/
            $.each(json.markers, function(i){

                /**
                 * 'this' refers to the array element
                 * of the current loop iteration index.
                 **/
                var data = this;

                /**
                 * Objects position as google.maps.LatLng object.
                 **/
                var point = new google.maps.LatLng(data.lat,data.lng)

                /**
                 * Extend the total results' bounds object by point.
                 **/
                bounds.extend(point);

                /**
                 * Data object that shall be assigned to the marker object.
                 **/
                var marker_data = {
                    "id":       data.id,
                    "name":     data.name,
                    "title":    data.title,
                    "street":   data.street_address,
                    "city":     data.city,
                    "country":  data.country,
                    "cntryDial":data.tel_cc,
                    "cityDial": data.tel_cityDial,
                    "phone":    data.phone,
                    "fax":      data.fax,
                    "www":      data.www,
                    "lat":      data.lat,
                    "lng":      data.lng,
                    "category": data.category,
					"img"     : data.img,
					"zI" :      data.zI
                };

                /**
                 * New google.maps.Marker object for 'this'.
                 **/
                var marker =  new google.maps.Marker({
                        map:      map,
                        icon:     'images/'+data.img,
                        flat:     false,
						zI:   data.zI,
                        category: data.category,
                        title:    unescape(data.title),
                        data:     marker_data
                });

                /**
                 * Map markers with raising timeout (just eye-catching).
                 **/
                $.doTimeout(i*1, function(){
                    marker.setValues({
                        position: point,
                        animation:google.maps.Animation.DROP
                    });
                }); i++;

                /**
                 * Push the marker object to the markers array.
                 **/
                markers.push(marker);

                /**
                 * Add event listener that will trigger the infoWindow.
                 **/
                google.maps.event.addListener(marker, "click", function(){

                    /**
                     * panTo just looks 'nicer' than setCenter.
					     map.panTo(marker.position);
                     **/
                

                    /**
                     * Add a delay for a smoother pop-up animation.
                     **/
                    $.doTimeout(1, function(){

                        /**
                         * Call the custom info Window function.
                         **/
                        showDetails(marker.data);

                        /**
                         * Workaround for an issue that loads some tiles
                         * in plain grey when initializing a map and on-
                         * screen of that map object are timed very close.
                         **/
                        google.maps.event.trigger(panoMap, 'resize');
                    });
                });
            });

            /**
             * Save all markers' bounding box to a global variable.
             **/
            resultBounds = bounds;

            /**
             * Set the viewport to optimal fit to all results' bb.
             **/
            map.fitBounds(resultBounds);
        }

        /**
         * Make sure we have a categories array, holding some data.
         **/
        if (json.categories && json.categories.length){

            $('#category_toogle_ul').empty();

            /**
             * Write the list element misused as button to show the filters.
             */
            $("<li />")
            .addClass('button_map')
            .attr('id','select')
            .html('Filter by:')
            .click(function(){
                if ( $('.resli').css('display')=='none'){
                     $('.resli').slideDown('fast');}
                else{$('.resli').slideUp('fast');}
            })
            .appendTo('#category_toogle_ul');

            /**
             * Append the list element, that will reset the filter.
             * The onClick function will call the toogleCategories function.
             **/
            $("<li />")
            .addClass('resli active all')
            .html('<span><img src="https://chart.googleapis.com/chart?chst='+
                  'd_simple_text_icon_left&chld=Show+All|16|FFF|star|24|FFF'+
                  '|444" class="all" /></span>')
            .click(function(e){
                e.preventDefault();
                toogleCategories('all');
                $('.resli').removeClass('inactive').addClass('active');
            })
            .appendTo('#category_toogle_ul');

			
			
			
            /**
             * Loop through the JSON array's categories array.
             **/
            $.each(json.categories, function(){

                /**
                 * 'this' refers to the array element
                 * of the current loop iteration index.
                 **/
                var category = this;

                /**
                 * URL of the 'category icon' used as list element.
                 **/
                var cat_icon='https://chart.googleapis.com/chart?chst=' +
                    'd_simple_text_icon_left&chld=' + category + '|16|' +
                    'FFF|bicycle|24|FFF|444';

					
		
                /**
                 * Append a list element for the current  category and
                 * add the click handler, that will call the
                 * toogleCategories function.
                 **/
				 
				 
                $("<li />")
                .addClass('resli active '+ category)
                .html('<span><img src="' + cat_icon + '" '+
                      'class="' + category + '" /></span>')
                .mouseover(function(){
                    if($('.active').length == (json.categories.length+1)){
                    startBounce(category); }})
                .mouseout(function(){ stopBounce(); })
                .click(function(e){
                    e.preventDefault();
                    toogleCategories(category);
                    $('.resli').removeClass('active').addClass('inactive');
                    $(this).removeClass('inactive').addClass('active');
                 })
                 .appendTo('#category_toogle_ul');
            });
        }
    });

   

    /**
     * Set onClick functions for the reset button.
     **/
    $('#reset_map').click(function(e){
        e.preventDefault();
        toogleCategories('all');
        $('.resli')
        .removeClass('inactive')
        .addClass('active');
        if ($('.resli').css('display')!='none'){
            $('.resli').slideUp('fast');}
        map.fitBounds(resultBounds);
    });
}


function showDetails(data){

    /**
     * Objects position as google.maps.LatLng object.
     **/
    var point = new google.maps.LatLng(data.lat,data.lng);

   
    /**
     * The 'template' for the data, shown in the info window.
     **/
    var br = '<br>';
    $('#data').empty()
    .html(data.name+br+
          data.street +br+
          '<em><span style="font-size:70%;">'+
          'Cyclist: '+data.category+'</span></em>');

    /**
     * Set the dialog options for the info windows.
     **/
    $('#dialog').dialog({
        modal:        true,
        position:     ['center','center'],
        title:        data.title,
        autoOpen:     true,
        closeOnEscape:true,
        draggable:    true,
        show:         "slide",
        hide:         "slide"
    });
}

/**
 * @description: Loops through the map's markers and
 *               filter visibility by cat parameter.
 *
 * @param   cat  string   Either 'all', or the name of a category.
 **/
function toogleCategories(cat){

    /**
     * Make sure we have a markers array.
     **/
    if (markers) {

        /**
         * Loop through the markers array.
         **/
        $.each(markers, function(){

            /**
             * 'this' refers to the array element
             * of the current loop iteration index.
             **/
            var m = this;

            /**
             * If value of cat equals 'all', set all markers'
             * visibility to true.
             **/
            if(cat=='all'){
                m.setVisible(true);
            }

            /**
             * Else if value of paramater cat is not equals value of
             * current marker's category AND the marker object's
             * current visibility status is true, set that to false.
             **/
            else if(m.category != cat && m.getVisible()==true) {
                m.setVisible(false);
            }

            /**
             * Else if value of paramater cat is equals value of
             * current marker's category AND the marker object's
             * current visibility status is false, set that to true.
             **/
            else if(m.category == cat && m.getVisible()==false){
                m.setVisible(true);
            }
        });
    }
}

function startBounce(cat){

    /**
     * Make sure we have a markers array.
     **/
    if (markers) {

        /**
         * Loop through the markers array.
         **/
        $.each(markers, function(){

            /**
             * If the value of paramater cat is equals value of
             * current marker's category bounce the marker.
             **/
            if(this.category == cat){
               this.setAnimation(google.maps.Animation.BOUNCE);
            }
        });
    }
}

function stopBounce(){

    /**
     * Make sure we have a markers array.
     **/
    if (markers) {

        /**
         * Loop through the markers array.
         **/
        $.each(markers, function(){

            /**
             * Stop animations.
             **/
            this.setAnimation(null);
        });
    }
}



/**
 * jQuery doTimeout: Like setTimeout, but better! - v1.0 - 3/3/2010
 * http://benalman.com/projects/jquery-dotimeout-plugin/
 *
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 **/
(function($){var a={},c="doTimeout",d=Array.prototype.slice;$[c]=function(){return b.apply(window,[0].concat(d.call(arguments)))};$.fn[c]=function(){var f=d.call(arguments),e=b.apply(this,[c+f[0]].concat(f));return typeof f[0]==="number"||typeof f[1]==="number"?this:e};function b(l){var m=this,h,k={},g=l?$.fn:$,n=arguments,i=4,f=n[1],j=n[2],p=n[3];if(typeof f!=="string"){i--;f=l=0;j=n[1];p=n[2]}if(l){h=m.eq(0);h.data(l,k=h.data(l)||{})}else{if(f){k=a[f]||(a[f]={})}}k.id&&clearTimeout(k.id);delete k.id;function e(){if(l){h.removeData(l)}else{if(f){delete a[f]}}}function o(){k.id=setTimeout(function(){k.fn()},j)}if(p){k.fn=function(q){if(typeof p==="string"){p=g[p]}p.apply(m,d.call(n,i))===true&&!q?o():e()};o()}else{if(k.fn){j===undefined?e():k.fn(j===false);return true}else{e()}}}})(jQuery);