<?php
include_once("db.php");
date_default_timezone_set('Europe/Stockholm'); // specifierar timezone till svensk

function UpdateOverallRatings() {
$findusers=mysql_query("SELECT * FROM User")or die(mysql_error());
$u=0;
while($users=mysql_fetch_array($findusers)) { //for every user
	$userid = $users['ID'];
	$joindate = $users['Register'];
	$total=0;
	$overall=0;
	$codecount=0;
	$totalrating=0;
	$nr=0;
	$bookcount=0;
	$comcount=0;
	$ratecount=0;
	$reqcount=0;
	$warncount=0;
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	$codes = mysql_query("SELECT * FROM CodeInfo WHERE UserID = $userid");
	if (mysql_num_rows($codes) > 0) {
		while ($data1 = mysql_fetch_array($codes)) {
			$codecount+=10;
			$string = $data1['StringID'];
			$findratings = mysql_query("SELECT * FROM Rating WHERE StringID = $string");
			if (mysql_num_rows($findratings) > 0) {
				while ($data2 = mysql_fetch_array($findratings)) {
					$ratingres = $data2['Score'];
					$totalrating+=$ratingres;
					$nr++;
				}
			}
		}
	}
	$bookmarks = mysql_query("SELECT * FROM Bookmarks WHERE UserID = $userid");
	if (mysql_num_rows($bookmarks) > 0) {
		while ($data3 = mysql_fetch_array($bookmarks)) {
			$bookcount++;
		}
	}
	$comments = mysql_query("SELECT * FROM Comments WHERE UserID = $userid");
	if (mysql_num_rows($comments) > 0) {
		while ($data4 = mysql_fetch_array($comments)) {
			$comcount+=2;
		}
	}
	$ratings = mysql_query("SELECT * FROM Rating WHERE UserID = $userid");
	if (mysql_num_rows($ratings) > 0) {
		while ($data5 = mysql_fetch_array($ratings)) {
			$ratecount+=3;
		}
	}
	$requests = mysql_query("SELECT * FROM Request WHERE UserID='$userid' AND Approved='1'");
	if (mysql_num_rows($requests) > 0) {
		while ($data6 = mysql_fetch_array($requests)) {
			$reqcount+=10;
		}
	}
	$warnings = mysql_query("SELECT * FROM Warn WHERE UserID='$userid'");
	if (mysql_num_rows($warnings) > 0) {
		while ($data7 = mysql_fetch_array($warnings)) {
			$warncount+= -10;
		}
	}
	$total+=$nr;
	$total+=$codecount;
	$total+=$totalrating;
	$total+=$bookcount;
	$total+=$comcount;
	$total+=$ratecount;
	$total+=$reqcount;
	$total+=$warncount;
	$total=floor($total);
	if ($nr > 5) {
		$overall+=$totalrating/$nr;
	}
	else {
		$overall=0;
	}
	$overall=round($overall, 2);
	$checkranked = mysql_query("SELECT * FROM Ranked WHERE UserID='$userid'")or die(mysql_error());
	$countranked = mysql_num_rows($checkranked);
	if ($countranked == 0) {
		mysql_query("INSERT INTO Ranked (ID, UserID, Points, Rating) VALUES ('', '$userid', '$total', '$overall')")or die(mysql_error());
		//echo 'Added new ranked user<br/>';
	}
	else {
		mysql_query("UPDATE Ranked SET Points='$total', Rating='$overall' WHERE UserID='$userid'")or die(mysql_error());
		//echo 'Updated ranked user<br/>';
	}
}
mysql_query("DELETE Ranked FROM Ranked LEFT OUTER JOIN User AS usr ON usr.ID = Ranked.UserID WHERE usr.ID IS NULL");
$ipforlog = 'Server';
$username = 'Server';
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Updated Ranked and Ratings', '$logtime', '$ipforlog')");
//post to twitter
require_once '../twitteroauth/tmhOAuth.php';
require_once '../twitteroauth/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key' => 'O309gjFzlOkrnwsPA8ebcQ',
  'consumer_secret' => '3jFijBpGQ38TQi0JDzfXyqwJyQ2vTqjWYbSuGNx3U',
  'user_token' => '403388089-0uDI7UU8A8dUD9EGhM0YnmRfpiTxatNBCU8d1VgD',
  'user_secret' => 'HxMiCJAuMKzWSrZtZTJKsdhsnyGvk0HprX98pL3Q6YU',
));

$tmessage = 'Rated and ranked users have been updated at http://sourcecodedb.com/ranked.php #sourcecode';
    
$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
  'status' => $tmessage
));
echo $code.'</br>';

}
?>