<?php
session_start();
date_default_timezone_set('Europe/Stockholm'); // specifierar timezone till svensk
	$is_ajax = $_REQUEST['is_ajax'];
	if(isset($is_ajax) && $is_ajax)
	{
		$username = strtolower($_REQUEST['username']);
		$password = $_REQUEST['password'];
		
		
if ($username&&$password)
{
	include_once("functions/db.php");
	$query = mysql_query("SELECT * FROM User WHERE Username='$username'");
	
	$numrows = mysql_num_rows($query);
	
	if ($numrows!=0)
	{
		
		while($row = mysql_fetch_assoc($query))
		{
				$dbusername = strtolower($row['Username']);
				$sessionusername = $row['Username'];
				$dbpassword = $row['Password'];
				$dbid = $row['ID'];
				$dbfname = $row['Fname'];
				$dblname = $row['Lname'];
		}
		
		// check to see if they match!
		if ($username==$dbusername&&md5(md5($password)."ILIKEPIE")==$dbpassword)
		{
			$ipforlog = $_SERVER['REMOTE_ADDR'];
			$date = date("Y-m-d"); 
			$time = date("G:i:s"); 
			$logtime = $date.' '.$time;
			mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$dbid', 'Logged in success', '$logtime', '$ipforlog')");
			$_SESSION['ID']=$dbid;
			$_SESSION['Username']=$sessionusername;
			$_SESSION['Fname']=$dbfname;
			$_SESSION['Lname']=$dblname;
			$_SESSION['Type']='user';
			echo "success";
			
		}
		else {
	$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if ($_SESSION['ID']) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid - '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Wrong password $ipforlog', '$logtime', '$ipforlog')");
			echo "wrong";
		
				
	}
	}
	else {
	$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if ($_SESSION['ID']) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid - '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'login with wrong username $ipforlog', '$logtime', '$ipforlog')");
		echo "wrong";
	}
}
else {
	$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if ($_SESSION['ID']) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid - '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Messing with login function $ipforlog', '$logtime', '$ipforlog')");
	echo "nothing";


	}
	}
?>