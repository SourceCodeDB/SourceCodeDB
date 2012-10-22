<?php
function UpdateMonthlyRatings() {
$findusers=mysql_query("SELECT * FROM User")or die(mysql_error());
$u=0;
$safe=0;
//post to twitter
require_once '../twitteroauth/tmhOAuth.php';
require_once '../twitteroauth/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key' => 'O309gjFzlOkrnwsPA8ebcQ',
  'consumer_secret' => '3jFijBpGQ38TQi0JDzfXyqwJyQ2vTqjWYbSuGNx3U',
  'user_token' => '403388089-0uDI7UU8A8dUD9EGhM0YnmRfpiTxatNBCU8d1VgD',
  'user_secret' => 'HxMiCJAuMKzWSrZtZTJKsdhsnyGvk0HprX98pL3Q6YU',
));

    
while($users=mysql_fetch_array($findusers)) { //for every user
	$date55 = date("Y-m-d"); 
	$time55 = date("G:i:s"); 
	$logtime = $date55.' '.$time55;
	$time = time();
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipforlog = $_SERVER['REMOTE_ADDR'];
        }
        else {
            $ipforlog = "Server";
        }
	/*
	Using $time for generating time strings for the one-in-a-zillion
	chance that someone runs this at the precise stroke of midnight.
	*/
	$year = date('Y',$time);
	
	$month = date('F',$time);
	#Current Month
	$day_count = date('d',$time);
	#Count of the days in current month.
	$month_start = strtotime($year.' '.$month);
	//echo $month_start;
	//echo '<br/>';
	#Unix timestamp of the start of a month.
	#Unix timestamp of the final second of a month.
	$monthidt = mysql_query("SELECT MonthID FROM MonthlyRating ORDER BY MonthID DESC");
	if (mysql_num_rows($monthidt) < 1) {
		$monthid = 1;
	}
	else {
        $lastday = mysql_result(mysql_query("SELECT Date FROM MonthlyRating ORDER BY MonthID DESC"),0);
        if (date('Ymd', $time) == date('Ymd', strtotime($lastday))) {
	$monthid = mysql_result($monthidt,0);
        }
        else {
	if ($day_count == 1 && $safe == 0) {
                $monthid = mysql_result($monthidt,0);
		$highestrated = mysql_result(mysql_query("SELECT UserID FROM MonthlyRating WHERE MonthID = '$monthid' ORDER BY Rating DESC, Points DESC"),0);
		$highestpoints = mysql_result(mysql_query("SELECT UserID FROM MonthlyRating WHERE MonthID = '$monthid' ORDER BY Points DESC, Rating DESC"),0);
		mysql_query("INSERT INTO Medals (Type, UserID, Date) VALUES ('Highest Rated of The Month!','$highestrated','$logtime')");
		mysql_query("INSERT INTO Medals (Type, UserID, Date) VALUES ('Highest Points of The Month!','$highestpoints','$logtime')");
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '0', 'Medals awarded!', '$logtime', '$ipforlog')");
		$monthid++;
		$safe++;
	}
        else {
            $monthid = mysql_result($monthidt,0);
        }
        }
	}
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
	$codes = mysql_query("SELECT * FROM CodeInfo WHERE UserID = '$userid' AND IsTemp != '1'");
	if (mysql_num_rows($codes) > 0) {
		while ($data1 = mysql_fetch_array($codes)) {
			$codedate = strtotime($data1['Date']);
			if ($codedate >= $month_start) {
			$codecount+=10;
			}
			$string = $data1['StringID'];
			$findratings = mysql_query("SELECT * FROM Rating WHERE StringID = $string");
			if (mysql_num_rows($findratings) > 0) {
				while ($data2 = mysql_fetch_array($findratings)) {
					$ratingdate = strtotime($data2['Date']);
					if ($ratingdate >= $month_start) {
					$ratingres = $data2['Score'];
					$totalrating+=$ratingres;
					$nr++;
					}
				}
			}
		}
	}
	$bookmarks = mysql_query("SELECT * FROM Bookmarks WHERE UserID = $userid");
	if (mysql_num_rows($bookmarks) > 0) {
		while ($data3 = mysql_fetch_array($bookmarks)) {
			$bookmarkdate = strtotime($data3['Date']);
			if ($bookmarkdate >= $month_start) {
			$bookcount++;
			}
		}
	}
	$comments = mysql_query("SELECT * FROM Comments WHERE UserID='$userid'");
	if (mysql_num_rows($comments) > 0) {
		
		while ($data4 = mysql_fetch_array($comments)) {
			$commentdate = strtotime($data4['Date']);
			if ($commentdate >= $month_start) {
			$comcount+=2;
		}
		}
	}
	$ratings = mysql_query("SELECT * FROM Rating WHERE UserID = $userid");
	if (mysql_num_rows($ratings) > 0) {
		while ($data5 = mysql_fetch_array($ratings)) {
			$ratingdate = strtotime($data5['Date']);
			if ($ratingdate >= $month_start) {
			$ratecount+=3;
			}
		}
	}
	$requests = mysql_query("SELECT * FROM Request WHERE UserID='$userid' AND Approved='1'");
	if (mysql_num_rows($requests) > 0) {
		while ($data6 = mysql_fetch_array($requests)) {
			$requestdate = strtotime($data6['SubmitDate']);
			if ($requestdate >= $month_start) {
			$reqcount+=10;
			}
		}
	}
	$warnings = mysql_query("SELECT * FROM Warn WHERE UserID='$userid'");
	if (mysql_num_rows($warnings) > 0) {
		while ($data7 = mysql_fetch_array($warnings)) {
			$warndate = strtotime($data7['Date']);
			if ($warndate >= $month_start) {
			$warncount+= -10;
			}
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
	if ($nr > 0) {
		$overall+=$totalrating/$nr;
	}
	else {
		$overall=0;
	}
	$overall=round($overall, 2);
        
	$checkranked = mysql_query("SELECT * FROM MonthlyRating WHERE UserID='$userid' AND MonthID = '$monthid'")or die(mysql_error());
	$countranked = mysql_num_rows($checkranked);
	//echo $logtime;
	//echo '<br/>';
	$checkbanned = mysql_num_rows(mysql_query("SELECT * FROM Ban WHERE UserID='$userid'"));
	if ($checkbanned > 0) {
		//echo 'user banned, skipping';
	}
	else {
	if ($countranked == 0) {
		mysql_query("INSERT INTO MonthlyRating (ID, UserID, Points, Rating, Date, MonthID) VALUES ('', '$userid', '$total', '$overall', '$logtime', '$monthid')")or die(mysql_error());
		//echo 'Added new ranked user<br/>';
	}
	else {
            if ($day_count == 1) {
                mysql_query("INSERT INTO MonthlyRating (ID, UserID, Points, Rating, Date, MonthID) VALUES ('', '$userid', '$total', '$overall', '$logtime', '$monthid')")or die(mysql_error());
		//echo 'Added new ranked user<br/>';
            }
            else {
		mysql_query("UPDATE MonthlyRating SET Points='$total', Rating='$overall' WHERE UserID='$userid'")or die(mysql_error());
		//echo 'Updated ranked user<br/>';
            }
	}
	}
}
mysql_query("DELETE MonthlyRating FROM MonthlyRating LEFT OUTER JOIN User AS usr ON usr.ID = MonthlyRating.UserID WHERE usr.ID IS NULL");
$ipforlog = 'Server';
$username = 'Server';
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Updated Monthly Ranked and Ratings', '$logtime', '$ipforlog')");

$tmessage = 'Monthly rankings have been updated http://sourcecodedb.com/ #sourcecode #php #opensource';

$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
  'status' => $tmessage
));
echo '</br>';
print_r($tmhOAuth->response);
echo '</br>';
echo $code.'</br>';


}
?>