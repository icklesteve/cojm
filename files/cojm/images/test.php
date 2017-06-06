<?php

	/*

		Single File PHP Gallery TEST FILE 1.1.3
		
		This is a test script for testing if server requirements are met for Single File PHP Gallery to run.
		Place this file in the directory where you want Single File PHP Gallery to be.
		Access the file from a browser. The output should tell if you if Single File PHP Gallery would be able to run.

		Download latest version here:
		http://sye.dk/sfpg/

	*/

	error_reporting(0);

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"><html><head>" .
	"<meta http-equiv=\"Content-Type\" content=\"text/html;charset=iso-8859-1\"><title>Single File PHP Gallery TEST FILE</title>" .
	"</head><body>Single File PHP Gallery TEST FILE 1.1.2<br>------------------------------<br>";


	// PHP version
	echo "Your PHP Version is: " . PHP_VERSION . ". " . (version_compare(PHP_VERSION, "5.0.0") < 0 ? "Which is below 5.0.0, the script will not work for you. You need to upgrade PHP to minimum 5.0.0." : "This is good.") . "<br>";


	// GD info
	if (function_exists("gd_info"))
	{
		$gdver = gd_info();
		echo "GD version: " . $gdver["GD Version"] . "<br>";
		if ($gdver["GIF Read Support"] and $gdver["GIF Create Support"] and function_exists("imagegif"))
		{
			echo "You have support for GIF images<br>";
		}
		else
		{
			echo "You do not have support for GIF images<br>";
		}

		if (($gdver["JPG Support"] or $gdver["JPEG Support"]) and function_exists("imagejpeg"))
		{
			echo "You have support for JPG/JPEG images<br>";
		}
		else
		{
			echo "You do not have support for JPG/JPEG images<br>";
		}

		if ($gdver["PNG Support"] and function_exists("imagepng"))
		{
			echo "You have support for PNG images<br>";
		}
		else
		{
			echo "You do not have support for PNG images<br>";
		}
	}
	else
	{
		echo "The GD library are not installed on the server. You need to add this. See here for information: <a href=\"http://www.php.net/manual/en/book.image.php\">PHP.net - Image Processing and GD</a><br>";
	}
	

	// EXIF & Rotation
	if (function_exists("read_exif_data"))
	{
		echo "The function \"read_exif_data\" have been found - EXIF information and Rotation is possible.<br>";
	}
	else
	{
		echo "The function \"read_exif_data\" does not exists on server - EXIF information and Rotation is not possible.<br>";
	}


	
	// memory limit	
	echo "Memory limit: " . ini_get('memory_limit') . ". Divide this number by 4 and you have the approximate maximum number of pixels per image the script will be able to create thumbs for.<br>";
	
	
	
	// create and write access
	$filename = "./sfpg_test_temp_file.txt";
	$content = "hello";
	if ($handle = fopen($filename, "w"))
	{
		echo "Created file: ($filename)<br>";
		if (fwrite($handle, $content) !== false)
		{
			echo "Wrote ($content) to file ($filename). PHP have write access to current directory.<br>";
		}
		else
		{
			echo "Cannot write to file ($filename). PHP do not have write access to current directory. You need to fix this.<br>";
		}
		fclose($handle);
		if (unlink($filename) === true)
		{
			echo "Deleted ($filename).<br>";
		}
		else
		{
			echo "Could not delete ($filename).<br>";
		}
	}
	else
	{
		echo "Cannot create file ($filename). PHP do not have write access to current directory. You need to fix this.<br>";
	}

	
	
	echo "------------------------------<br></body></html>";

?>