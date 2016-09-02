<?php
	$hostname = "localhost";
	$username = "root";
	$password = "";
	$dbname = "myfirstproject";


	$dbconn = mysqli_connect($hostname, $username, $password) or die("Unable to connect to MySQL");
	$selectedDB = mysqli_select_db($dbconn, $dbname) or die(mysqli_error($dbconn));
        
      
?>
