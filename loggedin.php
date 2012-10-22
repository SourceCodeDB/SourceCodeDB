<?php
include_once('header.php');
require_once('fbsdk/facebook.php');

$facebook = new Facebook(array(
  'appId' => '225370617476152',
  'secret' => '9b30db40c98130897451f87648568238',
));

  $fbuid = $facebook->getUser(); //Detta tar facebook användarens id från en aktiv session
/*
// Detta är facebook login funktionen som inte funkar något vidare med användare
// utan ett facebook username :/
*/
?>
<!-- den här skripten kollar om något har fyllt i username fältet man får om man har ett facebook username som matchar ett existerande username -->
<script>
var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

$(document).ready(function() {
	$('#usernameLoading').hide();
	$('#username2').blur(function(){
		delay(function(){
	  $('#usernameLoading').show();
      $.post("functions/checkuser.php", {
        username: $('#username2').val()
      }, function(response){
        $('#usernameResult').fadeOut();
        setTimeout("finishAjax('usernameResult', '"+escape(response)+"')", 400);
      });
    	return false;
	}, 200 );
	});
});
function finishAjax(id, response) {
  $('#usernameLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax
</script>
  <?
  if($fbuid) { //kollar om facebook session är aktiv
  $user_profile = $facebook->api('/me','GET');
        $name = $user_profile['first_name']; //hämtar facebook användares namn
        $lname = $user_profile['last_name']; //hämtar facebook användares efternamn
        $fbid = $user_profile['id'];//hämtar facebook användares profil id
        $fbusername = $user_profile['username'];
        $fbusername2 = $user_profile['username'];
        $fbdupidcheck = "SELECT * FROM User WHERE FacebookID='$fbid'"; //tar alla detaljer om användare där facebook användar id är lika med aktiva facebook sessions id
        $fbcheckid = mysql_query($fbdupidcheck);
        $fbiddb = mysql_fetch_array($fbcheckid);
        $email = $user_profile['email']; //hämtar facebook användares email
        $fburl = 'http://facebook.com/' . $fbid; //Genererar profil url för facebook användare
        $result = "SELECT * FROM User WHERE Username = '$fbusername'"; //kollar om användarens facebook username mathcar ett användarnamn, fbusername deklareras i headern
        $result3 = "SELECT * FROM User WHERE Username = '$fbusername2'"; //kollar om användarens facebook username mathcar ett användarnamn, fbusername2 deklareras i headern
        $avatarurl = 'http://graph.facebook.com/' . $fbid . '/picture'; //genererar url för liten profil bild
        if (isset($_SESSION['ID'])) {
        $useridfb = $_SESSION['ID']; //tar session id från inloggad användare pa sourcecodedb
        }
        else {
            $useridfb = '';
        }
        if (isset($_SESSION['ID'])) {
  $result2 = "SELECT FacebookID FROM User WHERE ID='$useridfb'";//tar facebook if från databasen för inloggad användare
        }
  $ipforlog = $_SERVER['REMOTE_ADDR'];
  $date = date("Y-m-d"); 
  $time = date("G:i:s"); 
  $logtime = $date.' '.$time;
  if (!isset($pullid)) {
      $pullid = "";
  }
  mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$pullid', 'Just checking $name $lname', '$logtime', '$ipforlog')");
  $r3 = mysql_query($result3);
  $r = mysql_query($result);
}
if ($fbiddb != NULL && $fbuid) { //om facebook login har tryckts och användaren finns i databasen so gör vi en session
	$pullusername = $fbiddb['Username']; // tar användar info från db
	$pullid = $fbiddb['ID'];
	$pullfname = $fbiddb['Fname'];
	$pulllname = $fbiddb['Lname'];
	$checkemail = mysql_result(mysql_query("SELECT Email FROM User WHERE Email='$email'"),0);
	$checkname = mysql_result(mysql_query("SELECT Fname FROM User WHERE Fname='$name'"),0);
	if ($checkemail == NULL) {
	echo 'Sorry login could not be verified at this time';
	}
	elseif ($checkname == NULL) {
	echo 'Sorry login could not be verified at this time';
	}
	else {
	$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$pullid', 'Logged in with Facebook success', '$logtime', '$ipforlog')");
	$_SESSION['ID']=$pullid; //och skapar session
	$_SESSION['Username']=$pullusername;
	$_SESSION['Fname']=$pullfname;
	$_SESSION['Lname']=$pulllname;
	$_SESSION['Type']='user';
	echo 'You have been logged in with facebook';
	echo '</div>';
	include_once('footer.php');
	?>
	    <script>
    <!--
    window.location= "http://sourcecodedb.com/"
    //-->
    </script>
    <?php
    }
}
elseif ($_POST['hidden'] == 'newusername' && $fbiddb == NULL) {
	if(mysql_num_rows($r3) != 0){
		$ipforlog = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d"); 
		$time = date("G:i:s"); 
		$logtime = $date.' '.$time;
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '0', 'Unavailable Username (FB)', '$logtime', '$ipforlog')");
            echo 'Username Unavailable';
        }
        elseif(strlen($fbusername2) < 4) {
        	$ipforlog = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d"); 
		$time = date("G:i:s"); 
		$logtime = $date.' '.$time;
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '0', 'Too short Username (FB)', '$logtime', '$ipforlog')");
                echo 'Too short';
        }
        elseif(strlen($fbusername2) > 15) {
        	$ipforlog = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d"); 
		$time = date("G:i:s"); 
		$logtime = $date.' '.$time;
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '0', 'Too long Username (FB)', '$logtime', '$ipforlog')");
                echo 'Too Long :)';
        }
        elseif($fbusername2 == 'administrator') {
        	$ipforlog = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d"); 
		$time = date("G:i:s"); 
		$logtime = $date.' '.$time;
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '0', 'attempt to register as admin (FB)', '$logtime', '$ipforlog')");
                echo 'mm, yea, not gonna happen';
        }
        elseif (!preg_match('/^\w+$/', $fbusername2)) {
        	$ipforlog = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d"); 
		$time = date("G:i:s"); 
		$logtime = $date.' '.$time;
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '0', 'Registration non alpha chars (FB)', '$logtime', '$ipforlog')");
                echo 'Only alphabetical characters plz.';
        }
        else {
        mysql_query("INSERT INTO User (Fname, Lname, Email, Username, Register, FacebookID, FBProfileUrl, Private) VALUES ('$name', '$lname', '$email', '$fbusername2', Curdate(), '$fbid', '$fburl', '1')") or die (mysql_error());
        $thereggeduser = mysql_result(mysql_query("SELECT ID FROM User ORDER BY ID DESC LIMIT 1"),0);
	mysql_query("INSERT INTO UserRank (UserID, Title) VALUES ('$thereggeduser','Regular user')")or die (mysql_error());
        $finduseridfb1 = mysql_query("SELECT ID FROM User WHERE FacebookID = '$fbid'") or die (mysql_error());
        $founduseridfb1 = mysql_result($finduseridfb1,0);
        mysql_query("INSERT INTO Avatar (UserID, Location) VALUES ('$founduseridfb1', '$avatarurl')") or die (mysql_error());
        $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$founduseridfb1', 'Registered with Facebook success', '$logtime', '$ipforlog')");
	echo 'You have been registered using facebook, please wait while we log you in!';
        $fbiddbreq2 = mysql_query("SELECT * FROM User WHERE FacebookID = '$fbid'") or die (mysql_error());
        $fbiddb2 = mysql_fetch_assoc($fbiddbreq2);
        $pullusername2 = $fbiddb2['Username']; // tar användar info från db
	$pullid2 = $fbiddb2['ID'];
	$pullfname2 = $fbiddb2['Fname'];
	$pulllname2 = $fbiddb2['Lname'];
	$_SESSION['ID']=$pullid2; //och skapar session
	$_SESSION['Username']=$pullusername2;
	$_SESSION['Fname']=$pullfname2;
	$_SESSION['Lname']=$pulllname2;
	$_SESSION['Type']='user';
	echo 'and now you have been logged in with facebook';
	?>
	    <script>
    <!--
    window.location= "http://sourcecodedb.com/"
    //-->
    </script>
    <?php
    }
}
elseif ($useridfb == '' && $fbusername == NULL && $fbiddb == NULL && $fbuid) { //Om inget session id finns men användarens fb username är lika med någon användarnamn i databasen så frågar vi efter nytt användarnamn
        ?>
        <p><?php echo 'You dont seem to have a username specified, please type in a new name.'; ?></p>
        <table width="800" height="182" border="0">
    <form action="loggedin.php" method="post">
        <tr>
      <td height="30"><label for="username"><?php echo 'Username'; ?>:</label></td>
      <td><input type="text" name="username" id="username2" /> 
      </td>
      <td><span id="usernameLoading"><img src="images/indicator.gif" alt="Loading" /></span>
<span id="usernameResult"></span></p></td>
    </tr>
    <tr>
    <input type="hidden" name="hidden" value="newusername">
      <td width="75" height="30"><input type="submit" value="<?php echo 'Submit'; ?>" /></td>
      <td width="62"><input type="reset" value= "Reset" /></td>
    </tr>
  </table></form>
        <?php
        }
        elseif ($useridfb == NULL && mysql_num_rows($r) >= 1 && $fbiddb == NULL && $fbuid && $_POST['hidden'] != 'newusername') { //Om inget session id finns men användarens fb username är lika med någon användarnamn i databasen så frågar vi efter nytt användarnamn
        ?>
        <p><?php echo 'Your facebook username matches another user in our database, please type a new name.'; ?>
        <table width="800" height="182" border="0">
    <form action="loggedin.php" method="post">
        <tr>
      <td height="30"><label for="username"><?php echo 'Username'; ?>:</label></td>
      <td><input type="text" name="username" id="username2" /> 
      </td>
      <td><span id="usernameLoading"><img src="images/indicator.gif" alt="Loading" /></span>
<span id="usernameResult"></span></p></td>
    </tr>
    <tr>
    <input type="hidden" name="hidden" value="newusername">
      <td width="75" height="30"><input type="submit" value= "<?php echo 'Submit'; ?>" /></td>
      <td width="62"><input type="reset" value= "Reset" /></td>
    </tr>
  </table></form>
        <?php
        }
        elseif ($useridfb == NULL && mysql_num_rows($r) == 0 && $fbiddb == NULL && $fbuid) {
	if(strlen($fbusername) < 4) {
                echo 'your facebook username is too short, please contact an administrator';
        }
        elseif(strlen($fbusername) > 15) {
                echo 'your facebook username is too long, please contact an administrator';
        }
        elseif($fbusername == 'administrator') {
                echo 'mm, yea, not gonna happen';
        }
        elseif (!preg_match('/^\w+$/', $fbusername)) {
                echo 'your facebook username can only contain alphabetical characters, please contact an administrator';
        }
        else {
        mysql_query("INSERT INTO User (Fname, Lname, Email, Username, Register, FacebookID, FBProfileUrl, Private) VALUES ('$name', '$lname', '$email', '$fbusername', Curdate(), '$fbid', '$fburl', '1')") or die (mysql_error());
        $thereggeduser = mysql_result(mysql_query("SELECT ID FROM User ORDER BY ID DESC LIMIT 1"),0);
	mysql_query("INSERT INTO UserRank (UserID, Title) VALUES ('$thereggeduser','Regular user')")or die (mysql_error());
        $finduseridfb1 = mysql_query("SELECT ID FROM User WHERE FacebookID = '$fbid'") or die (mysql_error());
        $founduseridfb1 = mysql_result($finduseridfb1,0);
        mysql_query("INSERT INTO Avatar (UserID, Location) VALUES ('$founduseridfb1', '$avatarurl')") or die (mysql_error());
	echo 'You have been registered using facebook, please wait while we log you in!';
	$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$founduseridfb1', 'Registered with Facebook success', '$logtime', '$ipforlog')");
        $fbiddbreq3 = mysql_query("SELECT * FROM User WHERE FacebookID = '$fbid'") or die (mysql_error());
        $fbiddb3 = mysql_fetch_assoc($fbiddbreq3);
        $pullusername3 = $fbiddb3['Username']; // tar användar info från db
	$pullid3 = $fbiddb3['ID'];
	$pullfname3 = $fbiddb3['Fname'];
	$pulllname3 = $fbiddb3['Lname'];
	$_SESSION['ID']=$pullid3; //och skapar session
	$_SESSION['Username']=$pullusername3;
	$_SESSION['Fname']=$pullfname3;
	$_SESSION['Lname']=$pulllname3;
	$_SESSION['Type']='user';
	echo 'and now you have been logged in with facebook';
	?>
	    <script>
    <!--
    window.location= "http://sourcecodedb.com/"
    //-->
    </script>
    <?php
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
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Illegal attempt loggedin.php', '$logtime', '$ipforlog')");
	
        echo 'You seem to have gotten to this page by mistake';
        }
include_once('footer.php');
?>