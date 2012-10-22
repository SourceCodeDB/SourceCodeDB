<?php
header("Cache-Control: no-cache");
header("Pragma: nocache");
include_once('functions.php');
require('_config-rating.php'); // get the db connection info
//
//getting the values
$vote_sent = preg_replace("/[^0-9]/","",$_REQUEST['j']);
$id_sent = preg_replace("/[^0-9a-zA-Z]/","",$_REQUEST['q']);
$ip_num = preg_replace("/[^0-9\.]/","",$_REQUEST['t']);
$units = preg_replace("/[^0-9]/","",$_REQUEST['c']);
if ($units > 5) die("That aint gon work buddy :)");
if ($vote_sent > 5) die("Hacking are we?");
$ip = $_SERVER['REMOTE_ADDR'];
$referer  = $_SERVER['HTTP_REFERER'];
session_start();
$userid = $_SESSION['ID'];
if ($clicked == true) {
header("Location: $referer"); // go back to the page we came from 
exit;
}
$clicked = true;
$verifyuser = mysql_result(mysql_query("SELECT ID FROM $rating_dbname.User WHERE ID = '$userid'"),0);
if ($verifyuser == Null) {
header("Location: $referer"); // go back to the page we came from 
exit;
}
$doublevote = mysql_result(mysql_query("SELECT UserID FROM $rating_dbname.Rating WHERE UserID = '$userid' AND StringID = '$id_sent'"),0);
if ($doublevote != Null) {
header("Location: $referer"); // go back to the page we came from 
exit;
}

if ($vote_sent > $units) die("Sorry, vote appears to be invalid."); // kill the script because normal users will never see this.

//connecting to the database to get some information
$query = mysql_query("SELECT used_ips FROM $rating_dbname.$rating_tableName WHERE StringID='$id_sent' ")or die(" Error: ".mysql_error());
$numbers = mysql_fetch_assoc($query);
$checkIP = unserialize($numbers['used_ips']);

// checking to see if the first vote has been tallied
// or increment the current number of votes

// if it is an array i.e. already has entries the push in another value
((is_array($checkIP)) ? array_push($checkIP,$ip_num) : $checkIP=array($ip_num));
$insertip=serialize($checkIP);

//IP check when voting
$voted = mysql_query("SELECT used_ips FROM $rating_dbname.Rating WHERE UserID = '$userid' AND StringID='$id_sent'")or die(mysql_error());
if(mysql_num_rows($voted) == null) {     //if the user hasn't yet voted, then vote normally...
$i=0;
$date = date("Y-m-d");
$time = date("G:i:s");
$logtime = $date.' '.$time;
while ($i < 1) {
    if (($vote_sent >= 1 && $vote_sent <= $units) && ($ip == $ip_num)) { // keep votes within range
	$update = "INSERT INTO $rating_dbname.$rating_tableName (StringID, used_ips, Score, UserID, Date) VALUES ('$id_sent', '$ip', '$vote_sent', '$userid', '$logtime')";
	$result = mysql_query($update);
        $parts=parse_url('http://sourcecodedb.com/functions/update_ratings.php');
 
        $fp = fsockopen($parts['host'],
        isset($parts['port'])?$parts['port']:80,
        $errno, $errstr, 30);
 
        if (!$fp) {
        } else {
            $out = "POST ".$parts['path']." HTTP/1.1\r\n";
            $out.= "Host: ".$parts['host']."\r\n";
            $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out.= "Content-Length: ".strlen($parts['query'])."\r\n";
            $out.= "Connection: Close\r\n\r\n";
            if (isset($parts['query'])) $out.= $parts['query'];

            fwrite($fp, $out);
            fclose($fp);
        }
                $i++;
    }	
} 
header("Location: $referer"); // go back to the page we came from 
exit;
} //end for the "if(!$voted)"
?>