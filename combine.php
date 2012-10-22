<?php
include_once('header.php');

require_once("fbsdk/facebook.php");
 $config = array(
    'appId' => '225370617476152',
    'secret' => '03622faf6618ea99449f21079d13643f',
  );

  $facebook = new Facebook($config);
  $fbuid = $facebook->getUser(); //Detta tar facebook användarens id från en aktiv session
error_reporting(E_ALL);
ini_set('display_errors', '0');

if ($_SESSION['Type'] == 'user') {
    if($fbuid) {

      // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
      try {

        $user_profile = $facebook->api('/me','GET');
        $name = $user_profile['first_name'];
        $lname = $user_profile['last_name'];
        $fbid = $user_profile['id'];
        $usernamechecker = $_SESSION['ID'];
        $fbusername2 = $user_profile['username'];
        $fbdupidcheck = "SELECT * FROM User WHERE FacebookID='$fbid'";
        $fbcheckid = mysql_query($fbdupidcheck);
        $email = $user_profile['email'];
        $profileurl = $fbid;
        $fburl = 'http://facebook.com/' . $profileurl;
        $result = "SELECT * FROM User WHERE Username='$fbusername2'";
$r = mysql_query($result);

if(mysql_num_rows($r) >= 1){
	$selectuid = mysql_query("SELECT ID FROM User WHERE FacebookID = '$fbid'") or die (mysql_error());
	while (mysql_fetch_array($selectuid)) {	
		$theselectedid = $selectuid['ID'];
		$selectcommentid = mysql_query("SELECT ID FROM Comments WHERE UserID = '$theselectedid' ") or die (mysql_error());
		while (mysql_fetch_array($selectcommentid)) {	
			$thecommentid = $selectcommentid['ID'];
			mysql_query("UPDATE Comments SET UserID='$usernamechecker' WHERE ID='$thecommentid'") or die (mysql_error());
		}
	}
	while (mysql_fetch_array($selectuid)) {	
		$theselectedid2 = $selectuid['ID'];
		$selectcommentid2 = mysql_query("SELECT ID FROM CodeInfo WHERE UserID = '$theselectedid2' ") or die (mysql_error());
		while (mysql_fetch_array($selectcommentid2)) {	
			$thecommentid2 = $selectcommentid2['ID'];
			mysql_query("UPDATE CodeInfo SET UserID='$usernamechecker' WHERE ID='$thecommentid2'") or die (mysql_error());
		}
	}
	while (mysql_fetch_array($selectuid)) {	
		$theselectedid3 = $selectuid['ID'];
		$selectcommentid3 = mysql_query("SELECT ID FROM Rating WHERE UserID = '$theselectedid3' ") or die (mysql_error());
		while (mysql_fetch_array($selectcommentid3)) {	
			$thecommentid3 = $selectcommentid3['ID'];
			mysql_query("UPDATE Rating SET UserID='$usernamechecker' WHERE ID='$thecommentid3'") or die (mysql_error());
		}
	}
	while (mysql_fetch_array($selectuid)) {	
		$theselectedid4 = $selectuid['ID'];
		$selectcommentid4 = mysql_query("SELECT ID FROM Bookmarks WHERE UserID = '$theselectedid4' ") or die (mysql_error());
		while (mysql_fetch_array($selectcommentid4)) {	
			$thecommentid4 = $selectcommentid4['ID'];
			mysql_query("UPDATE Bookmarks SET UserID='$usernamechecker' WHERE ID='$thecommentid4'") or die (mysql_error());
		}
	}
	while (mysql_fetch_array($selectuid)) {	
		$theselectedid4 = $selectuid['ID'];
		$selectcommentid4 = mysql_query("SELECT ID FROM News WHERE UserID = '$theselectedid4' ") or die (mysql_error());
		while (mysql_fetch_array($selectcommentid4)) {	
			$thecommentid4 = $selectcommentid4['ID'];
			mysql_query("UPDATE News SET UserID='$usernamechecker' WHERE ID='$thecommentid4'") or die (mysql_error());
		}
	}
	mysql_query("DELETE FROM User WHERE FacebookID='$fbid'") 
	or die (mysql_error());
	$avatarurl = 'http://graph.facebook.com/' . $fbid . '/picture';
	mysql_query("UPDATE User SET FacebookID='$fbid', FBProfileUrl='$fburl' WHERE ID='$usernamechecker'") 
	or die (mysql_error());
	mysql_query("INSERT Avatar (UserID, Location) VALUES ('$usernamechecker', '$avatarurl')") 
	or die (mysql_error());
	$ipforlog = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d"); 
		$time = date("G:i:s"); 
		$logtime = $date.' '.$time;
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$usernamechecker', '$fbusername2 was merged with this user', '$logtime', '$ipforlog')");
	echo 'You have been registered and your other SourceCodeDB.com account has been merged with this one.';
	}	

        else {
        mysql_query("UPDATE User SET FacebookID='$fbid', FBProfileUrl='$fburl' WHERE ID='$usernamechecker'") 
	or die (mysql_error());
	mysql_query("INSERT INTO Avatar (UserID, Location) VALUES ('$usernamechecker', '$avatarurl')") 
	or die (mysql_error());
	$ipforlog = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d"); 
		$time = date("G:i:s"); 
		$logtime = $date.' '.$time;
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$usernamechecker', 'Added a facebook account', '$logtime', '$ipforlog')");
	echo 'Your facebook account has been added';
	echo '<br />';
}
}


       catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
        $login_url = $facebook->getLoginUrl(); 
        echo 'Please';
        echo ' <a href="' . $loginurl . '">';
        echo 'login.';
        echo '</a>';
        error_log($e->getType());
        error_log($e->getMessage());
      }   
    } else {

      // No user, print a link for the user to login
      echo 'Please';
        echo ' <a href="' . $loginurl . '">';
        echo 'login.';
        echo '</a>';

    }
    }
    else {
    ?>
    <p><?php echo 'You are not logged in?'; ?></p><br />
    <?php
    
      echo 'Please';
        echo ' <a href="' . $loginurl . '">';
        echo 'login.';
        echo '</a>';
}
  ?>

  </body>
</html>


<?php
include_once('footer.php');
?>