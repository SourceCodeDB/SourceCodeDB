<?php
date_default_timezone_set('Europe/Stockholm'); // specifierar timezone till svensk
include_once("functions/db.php");
session_start();
$userid = $_SESSION['ID'];
$ipforlog = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userid', 'Logged out', '$logtime', '$ipforlog')");
session_destroy();

echo "success";
	
?>