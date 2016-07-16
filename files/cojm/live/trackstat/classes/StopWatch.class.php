<?php

/**
 * Script execution time measurement
 * 
 * @package StopWatch
 * @license GNU GPL 
 **/


class StopWatch {

   /**
    * @access protected
    **/
   protected $start_timestamp;



   /**
    * Constructor
    **/
   function __construct() {
     list($usec, $sec) = explode(" ", microtime());
     $this->start_timestamp = $sec + $usec;
   }



   /**
    * Returns time in miliseconds
    **/
   function GetTime() {
     list($usec, $sec) = explode(" ", microtime());
     $current_timestamp = $sec + $usec;
     $time = round(($current_timestamp - $this->start_timestamp) * 1000);
     return $time;
  }

}
?>