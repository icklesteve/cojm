<?php

$track_time = microtime(TRUE);
error_reporting( E_ERROR | E_WARNING | E_PARSE );
// REMEMBER TO RESET FILE PERMISSIONS ON UPLOAD

function cookbook_connect () {
// you will need to change the 4 variables below to your own database details.

 $host_name = "localhost";
 $user_name = "username";
 $db_name   = "databasename";
 $password  = "password"; 

define('BACKUPPASSWD', 'changemetosomethingdecent'); 
define("REMOTEFTPPASSWD","changemetosomethingdecent"); // ftp password
 
define('DBHOST', $host_name);
define('DBUSER', $user_name);
define('DBPASS', $password );
define('DBNAME',  $db_name );
define('DB_CHARSET', 'utf8'); 
 
// $dbh=new PDO("mysql:dbname=$db_name;host=$host_name;charset=utf8", $user_name, $password);
// $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
 
 
 
 
try {
        $dbh = new PDO(
            'mysql' . ':host=' . DBHOST . ';dbname=' . DBNAME . ';charset=' . DB_CHARSET,
            DBUSER,
            DBPASS,
            [
                PDO::ATTR_PERSISTENT            => true,
                PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES    => false

            ]
        );
    } catch ( PDOException $e ) {
        echo 'ERROR!';
        print_r( $e );
    } 
 
 
 
 
 
 
	$conn_id = @mysql_connect ($host_name, $user_name, $password);
	if (!$conn_id)
	{
		# If mysql_errno()/mysql_error() work for failed connections, use
		# them (invoke with no argument). Otherwise, use $php_errormsg.
		if (mysql_errno ())
		{
			 die (sprintf ("Cannot connect to server: %s (%d)\n",
					htmlspecialchars (mysql_error ()),
					mysql_errno ()));
		}
		else
		{
		 die ("Cannot connect to server: "
				. htmlspecialchars ($php_errormsg) . "\n");
		}
	}
	if (!@mysql_select_db ($db_name))
	{
		 die (sprintf ("Cannot select database: %s (%d)\n",
				htmlspecialchars (mysql_error ($conn_id)),
				mysql_errno ($conn_id)));
		}
	return ($conn_id);

}




 $conn_id = cookbook_connect ();
// mysql_set_charset('utf8');



// header('Content-Type: text/html; charset=utf-8');
GLOBAL $globalprefrow;
GLOBAL $httproots;
$sql = "SELECT * FROM globalprefs"; $sql_result = mysql_query($sql,$conn_id)  or mysql_error(); $globalprefrow=mysql_fetch_array($sql_result);

// if ($globalprefrow['adminlogoback']>0) { 



$httproots=$globalprefrow['httproots'];


ob_start('fatal_error_handler');

function fatal_error_handler($buffer){
    $error=error_get_last();
    if($error['type'] == 1){
        // type, message, file, line
        $newBuffer='<html><header><title>Fatal Error </title></header>
                    <style>                 
                    .error_content{                     
                        background: ghostwhite;
                        vertical-align: middle;
                        margin:0 auto;
                        padding:10px;
                        width:50%;                              
                     } 
                     .error_content label{color: red;font-family: Georgia;font-size: 16pt;font-style: italic;}
                     .error_content ul li{ background: none repeat scroll 0 0 FloralWhite;                   
                                border: 1px solid AliceBlue;
                                display: block;
                                font-family: monospace;
                                padding: 2%;
                                text-align: left;
                      }
                    </style>
                    <body style="text-align: center;">  
                      <div class="error_content">
                          <label >Fatal Error </label>
                          <ul>
                            <li><b>Line</b> '.$error['line'].'</li>
                            <li><b>Message</b> '.$error['message'].'</li>
                            <li><b>File</b> '.$error['file'].'</li>                             
                          </ul>

                          <a href="javascript:history.back()"> Back </a>                          
                      </div>
                    </body></html>';

        return $newBuffer;

    }

    return $buffer;

}


// }



GLOBAL $serversecure;

if($_SERVER['https'] == 1) /* Apache */ {
$serversecure='Apache';
} elseif ($_SERVER['https'] == 'on') /* IIS */ {
$serversecure='iis';
} elseif ($_SERVER['SERVER_PORT'] == 443) /* others */ {
$serversecure='443';
} elseif ($_SERVER["HTTPS"] == 'on' ) /* others */ {
$serversecure='APACHE';
} else
{
$serversecure=''; /* just using http */
}

// echo 'ss : '.$serversecure;

 // A SCRIPT TIMER
        $omega_time = microtime(TRUE);
    $lapse_timetwo = $omega_time - $track_time;
    $lapse_msectwo = $lapse_timetwo * 1000.0;
    $lapse_echotwo = number_format($lapse_msectwo, 1);
//    $infotext=$infotext. "$lapse_echotwo ms updatetracking<br/>";

?>