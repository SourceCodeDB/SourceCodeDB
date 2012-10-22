<?php
header("Cache-Control: no-cache");
header("Pragma: nocache");

require('_config-rating.php'); // get the db connection info

//getting the values
$vote_sent = preg_replace("/[^0-9]/","",$_REQUEST['j']);
$id_sent = preg_replace("/[^0-9a-zA-Z]/","",$_REQUEST['q']);
$ip_num = preg_replace("/[^0-9\.]/","",$_REQUEST['t']);
$units = preg_replace("/[^0-9]/","",$_REQUEST['c']);
if ($units >= '5') {
    die("That aint gon work buddy :)");
}
if ($vote_sent >= '5') {
    die("Hacking are we?");
}

$ip = $_SERVER['REMOTE_ADDR'];
if ($ip != $ip_num) die("See here... my friend tells me your real ip different from what you are telling me, looks like we got a problem, DENiED!");
$userid = $_SESSION['ID'];
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
$voted=mysql_num_rows(mysql_query("SELECT used_ips FROM $rating_dbname.$rating_tableName WHERE used_ips LIKE '%".$ip."%' AND StringID='".$id_sent."' "));
if(!$voted) {     //if the user hasn't yet voted, then vote normally...

	if (($vote_sent >= 1 && $vote_sent <= $units)) { // keep votes within range, make sure IP matches - no monkey business!
		$update = "INSERT INTO $rating_dbname.$rating_tableName (StringID, used_ips, Score, UserID) VALUES ('$id_sent', '$ip', '$vote_sent', '$userid')";
		$result = mysql_query($update)or die(" Error: ".mysql_error());		
	} 
} //end for the "if(!$voted)"
// these are new queries to get the new values!
$newtotals = mysql_query("SELECT used_ips, SUM(Score) FROM $rating_dbname.$rating_tableName WHERE StringID='$id_sent' ")or die(" Error: ".mysql_error());
$newtotals2 = mysql_query("SELECT used_ips FROM $rating_dbname.$rating_tableName WHERE StringID='$id_sent' ")or die(" Error: ".mysql_error());
$numbers = mysql_fetch_assoc($newtotals);
$numberofrows = mysql_num_rows($newtotals2);
$count = $numberofrows;//how many votes total
$current_rating = $numbers['SUM(Score)'];//total number of rating added together and stored
$tense = ($count==1) ? "vote" : "votes"; //plural form votes/vote

// $new_back is what gets 'drawn' on your page after a successful 'AJAX/Javascript' vote

$new_back = array();

$new_back[] .= '<ul class="unit-rating" style="width:'.$units*$rating_unitwidth.'px;">';
$new_back[] .= '<li class="current-rating" style="width:'.@number_format($current_rating/$count,2)*$rating_unitwidth.'px;">Current rating.</li>';
$new_back[] .= '<li class="r1-unit">1</li>';
$new_back[] .= '<li class="r2-unit">2</li>';
$new_back[] .= '<li class="r3-unit">3</li>';
$new_back[] .= '<li class="r4-unit">4</li>';
$new_back[] .= '<li class="r5-unit">5</li>';
$new_back[] .= '<li class="r6-unit">6</li>';
$new_back[] .= '<li class="r7-unit">7</li>';
$new_back[] .= '<li class="r8-unit">8</li>';
$new_back[] .= '<li class="r9-unit">9</li>';
$new_back[] .= '<li class="r10-unit">10</li>';
$new_back[] .= '</ul>';
$new_back[] .= '<p class="voted">Rating: <strong>'.@number_format($current_rating/$count,1).'</strong>/'.$units.' ('.$count.' '.$tense.' cast) ';
$new_back[] .= '<span class="thanks">Thanks for voting!</span></p>';

$allnewback = join("\n", $new_back);

// ========================

//name of the div id to be updated | the html that needs to be changed
$output = "unit_long$id_sent|$allnewback";
echo $output;
?>