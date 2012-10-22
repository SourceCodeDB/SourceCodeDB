<?php
include_once('header.php');
include_once('functions/checkemailfunction.php');
if (!isset($_SESSION['Type'])) {
if (isset($_POST['hidden']) && $_POST['hidden'] == 'send') {

$name = mysql_real_escape_string($_POST['Fname']);

$lname = mysql_real_escape_string($_POST['Lname']);

$username = mysql_real_escape_string($_POST['username']);  
  
$email = mysql_real_escape_string($_POST['email']);

$password = mysql_real_escape_string($_POST['password2']);

$password2 = mysql_real_escape_string($_POST['password3']);

$result = "SELECT * FROM User WHERE Username='$username'";
$r = mysql_query($result);

if (strlen($name) < 3) {
$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - name too short', '$logtime', '$ipforlog')");
    	echo 'name too short';
    }
    elseif (strlen($name) > 19) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - name too long', '$logtime', '$ipforlog')");
    	echo 'name too long';
    }
    elseif (!preg_match('/^\w+$/', $name)) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - name illegal chars', '$logtime', '$ipforlog')");
    	echo 'Only alphabetical characters in name plz.';
    }
    elseif (strlen($lname) < 3) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - Lname too short', '$logtime', '$ipforlog')");
    	echo 'last name too short';
    }
    elseif (strlen($lname) > 29) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - Lname too long', '$logtime', '$ipforlog')");
    	echo 'last name too long';
    }
    elseif (!preg_match('/^\w+$/', $lname)) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - Lname illegal chars', '$logtime', '$ipforlog')");
    	echo 'Only alphabetical characters in last name plz.';
    }
    elseif (check_email_address($email) == false) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - email wrong', '$logtime', '$ipforlog')");
    	echo 'email is wrong :)';
    }
    elseif(mysql_num_rows($r) >= 1){
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - UN exists', '$logtime', '$ipforlog')");
            echo 'Username Unavailable';
        }
    elseif(strlen($username) < 4) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - UN too short', '$logtime', '$ipforlog')");
   		echo 'username too short';
   	}
    elseif(strlen($username) > 15) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - UN too long', '$logtime', '$ipforlog')");
   		echo 'username too Long :)';
   	}
    elseif($username == 'administrator' || $username == 'html' || $username == 'java' || $username == 'ObjectiveC' || $username == 'HTML') {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - tried to reg as admin', '$logtime', '$ipforlog')");
   		echo 'mm, yea, not gonna happen';
   	}
    elseif (!preg_match('/^\w+$/', $username)) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - UN invalid chars', '$logtime', '$ipforlog')");
    		echo 'Only alphabetical characters in username plz.';
    	}
    elseif (strlen($password2) < 6) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - password too short', '$logtime', '$ipforlog')");
    		echo 'password too short';
    	}
    elseif (strlen($password2) > 30) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - password too long', '$logtime', '$ipforlog')");
    		echo 'Dude your password should not be that long :) Thats ridiculous!';
    	}
    elseif ($password != $password2) {
    $ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal Registration - password don't match', '$logtime', '$ipforlog')");
    		echo 'Your passwords do not match buddy, might want to check that.';
    	}
    else {
    $passwordmd5 = md5(md5($password)."ILIKEPIE");	
mysql_query("INSERT INTO User (Fname, Lname, Email, Username, Password, Register, Private) VALUES ('$name', '$lname', '$email', '$username', '$passwordmd5', Curdate(), '1')")or die (mysql_error()); 
$thereggeduser = mysql_result(mysql_query("SELECT ID FROM User ORDER BY ID DESC LIMIT 1"),0);
mysql_query("INSERT INTO UserRank (UserID, Title) VALUES ('$thereggeduser','Regular user')")or die (mysql_error());
$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Normal registration - $username registered sucess', '$logtime', '$ipforlog')");
echo 'You have been registered! An email with your username and other info will be sent to you.';
$to = $email;
	$success = 'Y';
 $subject = "SourceCodeDB Registration";
 $body = "Welcome to SourceCodeDB, You have now been regsitered.\n
 Username: ".$username."\n
 Password: ".$password."\n
 Thank you!";
 $headers = 'From: no-reply@sourcecodedb.com' . "\r\n" .
   'Reply-To: no-reply@sourcecodedb.com' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();
try {
mail($to, $subject, $body, $headers);
}
 catch (ErrorException $e) {
     echo "could not send email";
 }
}
}
?>
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

$(document).ready(function() {
	$('#nameLoading').hide();
	$('#name').blur(function(){
	delay(function(){
	  $('#nameLoading').show();
      $.post("functions/checkname.php", {
        name: $('#name').val()
      }
      , function(response){
        $('#nameResult').fadeOut();
        setTimeout("finishAjax2('nameResult', '"+escape(response)+"')", 400);
      });
    	return false;
    	}, 200 );
	});
});
function finishAjax2(id, response) {
  $('#nameLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax
$(document).ready(function() {
	$('#lnameLoading').hide();
	$('#lname').blur(function(){
	delay(function(){
	  $('#lnameLoading').show();
      $.post("functions/checklname.php", {
        lname: $('#lname').val()
      }
      , function(response){
        $('#lnameResult').fadeOut();
        setTimeout("finishAjax3('lnameResult', '"+escape(response)+"')", 400);
      });
    	return false;
    	}, 200 );
	});
});
function finishAjax3(id, response) {
  $('#lnameLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax
$(document).ready(function() {
	$('#emailLoading').hide();
	$('#email').blur(function(){
	delay(function(){
	  $('#emailLoading').show();
      $.post("functions/checkemail.php", {
        email: $('#email').val()
      }
      , function(response){
        $('#emailResult').fadeOut();
        setTimeout("finishAjax4('emailResult', '"+escape(response)+"')", 400);
      });
    	return false;
    	}, 200 );
	});
});
function finishAjax4(id, response) {
  $('#emailLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax
$(document).ready(function() {
	$('#passwordLoading').hide();
	$('#password3').blur(function(){
	delay(function(){
	  $('#passwordLoading').show();
      $.post("functions/checkpassword.php", {
        password2: $('#password2').val(),
        password3: $('#password3').val()
      }
      , function(response){
        $('#passwordResult').fadeOut();
        setTimeout("finishAjax5('passwordResult', '"+escape(response)+"')", 400);
      });
    	return false;
    	}, 100 );
	});
});
function finishAjax5(id, response) {
  $('#passwordLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax
</script>	
<?php
if (!isset($success)) {
?>
<div id="Layer2">

  <table width="800" height="182" border="0">
    <form action="register.php" method="post">
    <tr>
      <td width="108" height="30"><?php echo 'First Name'; ?>: </td>
      <td width="184"><input name="Fname" type="text" id="name" value="<?php if (isset($_POST['username'])) { echo $_POST['Fname']; } ?>" /> </td>
      <td width="500"><span id="nameLoading"><img src="images/indicator.gif" alt="Loading" /></span>
<span id="nameResult"></span></p></td>
    </tr>
    
	<tr>
      <td height="30"><?php echo 'Last Name'; ?>:</td>
      <td><input name="Lname" type="text" id="lname" value="<?php if (isset($_POST['username'])) { echo $_POST['Lname']; } ?>" /></td>
      <td><span id="lnameLoading"><img src="images/indicator.gif" alt="Loading" /></span>
<span id="lnameResult"></span></p></td>
    </tr>
	
	<tr>
      <td height="30"><?php echo 'Email'; ?>:</td>
      <td><input name="email" type="text" id="email" value="<?php if (isset($_POST['username'])) { echo $_POST['email']; } ?>" /></td>
      <td><span id="emailLoading"><img src="images/indicator.gif" alt="Loading" /></span>
<span id="emailResult"></span></p></td>
    </tr>
	
	<tr>
      <td height="30"><label for="username"><?php echo 'Username'; ?>:</label></td>
      <td><input type="text" name="username" id="username2" value="<?php if (isset($_POST['username'])) { echo $_POST['username']; } ?>" /> 
      </td>
      <td><span id="usernameLoading"><img src="images/indicator.gif" alt="Loading" /></span>
<span id="usernameResult"></span></p></td>
    </tr>
	
	<tr>
      <td height="30"><?php echo 'Password'; ?>:</td>
      <td><input name="password2" type="password" id="password2" /></td>
      <td></td>
      
    </tr>
    <tr>
      <td height="30"><?php echo 'Again'; ?>:</td>
      <td><input name="password3" type="password" id="password3" /></td>
      <td><span id="passwordLoading"><img src="images/indicator.gif" alt="Loading" /></span>
<span id="passwordResult"></span></p></td>
    </tr>
	
    	
  </table>
</div>
<div id="Layer3"></div>
<div id="Layer5">
  <table width="147" height="30" border="0">
    <tr>
    <input type="hidden" name="hidden" value="send">
      <td width="75" height="30"><input type="submit" value= "<?php echo 'Submit'; ?>" /></td>
      <td width="62"><input type="reset" value= "<?php echo 'Reset'; ?>" /></td>
    </tr>
  </table>
</div></form>
<?php
}
?>





<?php
}
else {
$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Illegal attempt to register.php', '$logtime', '$ipforlog')");
echo 'umm, you are already registered and should not need to register again?';
}
include_once('footer.php');
?>
