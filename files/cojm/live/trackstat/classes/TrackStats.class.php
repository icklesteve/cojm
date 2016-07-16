<?php

/**
 * Set of parameters of a single point
 * 
 * @package TrackStats
 * @license GNU GPL 
 **/


class Point {
    public $lat;
    public $lon;
    public $ele;
    public $timestamp;
    public $time_from_start;
    public $dist_from_start;
    public $segment_start;
    public $segment_end;
   
    /**
     * Constructor
     **/
    function __construct($pointparams) {
       $this->lat = $pointparams["Latitude"];
       $this->lon = $pointparams["Longitude"];
       $this->ele = $pointparams["Elevation"];
       $this->timestamp = $pointparams["Timestamp"];
       $this->time_from_start = FALSE;
       $this->dist_from_start = FALSE;
       $this->segment_start = FALSE;
       $this->segment_end = FALSE;
    }
}



/**
 * Class provides functions to create statistics from GPS data
 * 
 * @package TrackStats
 * @license GNU GPL 
 **/


class TrackStats {

    /**#@+
     * @access protected
     **/
    protected $track_name;
    protected $total_distance = 0;
    protected $num_points = 0;
    protected $num_segments = 0;
    protected $start_time = FALSE;
    protected $stop_time = FALSE;
    protected $max_elevation = FALSE;
    protected $min_elevation = FALSE;
    protected $points = array();
    protected $WGS84calc;
    /**#@-*/



    /**
     * Contructor
     **/
    function __construct() {
       $this->WGS84calc = new WGS84calculations();
       $this->Reset();
    }



    /**
     * Deletes all stored data
     **/
    function Reset() {
       $this->track_name = "";
       $this->total_distance = 0;
       $this->num_points = 0;
       $this->num_segments = 0;
       $this->start_time = FALSE;
       $this->stop_time = FALSE;
       $this->max_elevation = FALSE;
       $this->min_elevation = FALSE;
       $this->points = array();
    }



    /**
     * Adds a point to the end and recalculates statistics
     *      
     * Angles in grades, elevation in metres, time (Unix timestamp) in seconds
     * 
     * @param array point array parameters: "Latitude", "Longitude", "Elevation", "Timestamp"
     **/
    function AddPoint($point = Array()) {
       $this->points[$this->num_points] = new Point($point);
       if ($this->num_points == 0) {
          $this->min_elevation = $this->points[$this->num_points]->ele;
          $this->max_elevation = $this->points[$this->num_points]->ele;
          $this->start_time = $this->points[$this->num_points]->timestamp;
          $this->stop_time = $this->points[$this->num_points]->timestamp;
          $this->points[$this->num_points]->segment_start = TRUE;
          $this->num_segments++;
       } else {
          $this->total_distance+= $this->GetDistance($this->points[$this->num_points-1], $this->points[$this->num_points]);
          if ($this->points[$this->num_points]->ele !== FALSE) $this->min_elevation = min($this->min_elevation, $this->points[$this->num_points]->ele);
          if ($this->points[$this->num_points]->ele !== FALSE) $this->max_elevation = max($this->max_elevation, $this->points[$this->num_points]->ele);
          $this->stop_time = $this->points[$this->num_points]->timestamp;
          $this->points[$this->num_points]->time_from_start = $this->stop_time - $this->start_time;
          $this->points[$this->num_points]->dist_from_start = $this->total_distance;
          if ($this->points[$this->num_points-1]->segment_end === TRUE) {
             $this->points[$this->num_points]->segment_start = TRUE;
             $this->num_segments++;
          }
       }
       $this->num_points++;
    }



    /**
     * Adds a delimiter between two track segments
     **/
    function AddSegmentDelimiter() {
       if ($this->num_points == 0) return;
       $this->points[$this->num_points-1]->segment_end = TRUE;
    }



    /**
     * Sets track name
     * UTF-8 encoding
     * 
     * @param string track name
     **/
    function SetTrackName($name) {
       $this->track_name = $name;
    }



    /**
     * Returns track name
     * 
     * @return string
     **/
    function GetTrackName() {
       return $this->track_name;
    }



    /**
     * Returns total distance
     *      
     * horizontal projection, in metres
     * 
     * @return float               
     **/
    function GetTotalDistance() {
       return $this->total_distance;
    }



    /**
     * Returns number of points in the track
     * 
     * @return unsigned int
     **/
    function GetNumPoints() {
       return $this->num_points;
    }



    /**
     * Returns number of segments in the track
     * 
     * @return unsigned int
     **/
    function GetNumSegments() {
       return $this->num_segments;
    }



    /**
     * Returns the minimal elevation
     *      
     * In metres     
     * 
     * @return double
     **/
    function GetMinElevation() {
       return $this->min_elevation;
    }



    /**
     * Returns the maximal elevation
     *      
     * In metres     
     * 
     * @return double
     **/
    function GetMaxElevation() {
       return $this->max_elevation;
    }



    /**
     * Returns time of the first point
     *      
     * Unix timestamp     
     * 
     * @return unsigned int
     **/
    function GetStartTime() {
       return $this->start_time;
    }



    /**
     * Returns time of the last point
     *      
     * Unix timestamp     
     * 
     * @return unsigned int
     **/
    function GetStopTime() {
       return $this->stop_time;
    }



    /**
     * Returns distance of two points
     *      
     * In metres
     * Uses WGS84 class
     * 
     * @access protected
     * 
     * @param Point
     * @param Point
     * @return double
     **/
    protected function GetDistance($point1, $point2) {
       $WGS84_p1 = new WGS84datum($point1->lat, $point1->lon);
       $WGS84_p2 = new WGS84datum($point2->lat, $point2->lon);
       return $this->WGS84calc->FlatDistance($WGS84_p1, $WGS84_p2);
    }
    
    /**
     * Returns average elevation of specified range
     *      
     * In metres     
     * 
     * @access protected
     * 
     * @param array range ("time_start" and "time_stop" or "dist_start" and "dist_stop")
     * @return double
     **/
    protected function GetElevationAverage($param = Array()) {
       $sum = 0;
       $count = 0;
       if (($param["time_start"] !== FALSE) and ($param["time_stop"] !== FALSE)) {
          foreach($this->points as $point) {
            if (($point->timestamp >= $param["time_start"]) and ($point->timestamp <= $param["time_stop"]) and ($point->ele !== FALSE)) {
              $sum+= $point->ele;
              $count++;
            }
          }
       } else if (($param["dist_start"] !== FALSE) and ($param["dist_stop"] !== FALSE)) {
          foreach($this->points as $point) {
            if (($point->dist_from_start >= $param["dist_start"]) and ($point->dist_from_start <= $param["dist_stop"]) and ($point->ele !== FALSE)) {
              $sum+= $point->ele;
              $count++;
            }
          }
       }
       if ($count > 0) $result = $sum / $count;
       else $result = FALSE;
       return $result;
    }



    /**
     * Returns distance of specified range
     * 
     * @access protected
     * 
     * @param array range ("time_start" and "time_stop")
     * @return double
     **/
    protected function GetTrackDistance($param = Array()) {
       $count = 0;
       foreach($this->points as $point) {
         if (($point->timestamp >= $param["time_start"]) and ($point->timestamp <= $param["time_stop"])) {
            if ($count == 0) $start = $point->dist_from_start;
            $stop = $point->dist_from_start;
            $count++;
         }
       if ($count > 0) $result = $stop - $start;
       else $result = FALSE; 
       }
       return $result;
    }
}
?>