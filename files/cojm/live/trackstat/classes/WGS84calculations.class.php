<?php

/**
 * Calculations with WGS84 datum
 * 
 * @package WGS84
 **/


class WGS84datum {
    public $lat;
    public $lon;

    /**
     * Constructor
     *
     * @param double $lat
     * @param double $lon
     **/
    function __construct($lat, $lon) {
       $this->lat = $lat;
       $this->lon = $lon;
    }
}



/**
 * Calculations with WGS84 datum
 * 
 * @package WGS84
 * @link http://williams.best.vwh.net/avform.htm
 **/

class WGS84calculations {

    /**
     * Constructor
     **/
    function __construct() {
       //
    }



    /**
     * Spocte vzdalenost zadanych bodu
     *
     * Vzorec
     *   dist [rad] = 2*asin(sqrt((sin((lat1-lat2)/2))^2 + cos(lat1)*cos(lat2)*(sin((lon1-lon2)/2))^2))
     *   dist [m] = dist [rad] * 6367445 m
     *     
     * @param WGS84datum $datum1
     * @param WGS84datum $datum2
     * @return double
     **/
    function FlatDistance($datum1, $datum2) {
       if (is_numeric($datum1->lat)) $lat1 = (double) $datum1->lat/180*M_PI; else return FALSE;
       if (is_numeric($datum1->lon)) $lon1 = (double) $datum1->lon/180*M_PI; else return FALSE;
       if (is_numeric($datum2->lat)) $lat2 = (double) $datum2->lat/180*M_PI; else return FALSE;
       if (is_numeric($datum2->lon)) $lon2 = (double) $datum2->lon/180*M_PI; else return FALSE;
       
       $dist_rad = (double) (2*asin(sqrt(pow(sin(($lat1-$lat2)/2), 2) + cos($lat1)*cos($lat2)*pow(sin(($lon1-$lon2)/2), 2))));
       $dist = $dist_rad * 6367445;

       return $dist;
    }



    /**
     * Spocte azimut od souradnice1 k souradnici2
     *
     * Vzorec
     *   az [rad] = atan((sin(lon1-lon2)*cos(lat2)) / (cos(lat1)*sin(lat2)-sin(lat1)*cos(lat2)*cos(lon1-lon2)))    
     *          
     * @param WGS84datum $datum1
     * @param WGS84datum $datum2     
     * @return double
     **/
    function FlatAzimuth($datum1, $datum2) {
       if (is_numeric($datum1->lat)) $lat1 = (double) $datum1->lat/180*M_PI; else return FALSE;
       if (is_numeric($datum1->lon)) $lon1 = (double) $datum1->lon/180*M_PI; else return FALSE;
       if (is_numeric($datum2->lat)) $lat2 = (double) $datum2->lat/180*M_PI; else return FALSE;
       if (is_numeric($datum2->lon)) $lon2 = (double) $datum2->lon/180*M_PI; else return FALSE;
       
       // NEFUNGUJE SPRAVNE !!!
       $az = (double) @(atan((sin($lon1-$lon2)*cos($lat2)) / (cos($lat1)*sin($lat2)-sin($lat1)*cos($lat2)*cos($lon1-$lon2))));
       //$az = (int) round(fmod($az+2*M_PI, 2*M_PI)/(2*M_PI)*360);
       $az = (int) ($az / (2*M_PI) * 360);

       return $az;
    }
}
?>