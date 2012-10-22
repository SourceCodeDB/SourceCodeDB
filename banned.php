<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
session_start();
include_once("functions/db.php");
date_default_timezone_set('Europe/Stockholm'); // specifierar timezone till svensk
/*
// Här börjar auto logout för facebook
*/

/*
// Här slutar auto logout för facebook
*/
/*
// Här börjar auto logout
*/
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 15 minates ago
	$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	$useridlog = $_SESSION['ID'];
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$useridlog', 'Logged out automatically', '$logtime', '$ipforlog')");
    session_destroy();   // destroy session data in storage
   
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
/*
// Autologout slut
*/
/*
// Här börjar facebook login funktionen, första delen tar med facebook.php som 
// måste finnas med för att tala med facebook och få infromation
*/
require_once("fbsdk/facebook.php");
 $config = array(
    'appId' => '225370617476152',
    'secret' => '03622faf6618ea99449f21079d13643f',
  );

  $facebook = new Facebook($config);
  $params = array('scope' => 'read_stream, friends_likes, email', 'redirect_uri' => 'http://sourcecodedb.com/loggedin.php'); //parameter vi vill använda från facebook användare
  $loginUrl = $facebook->getLoginUrl($params);
  $params3 = array('scope' => 'read_stream, friends_likes, email', 'redirect_uri' => 'http://sourcecodedb.com/combine.php'); //combine.php ska kombinera en sourcecodedb.com account med en facebook account
  $loginUrl2 = $facebook->getLoginUrl($params3);
  $params2 = array( 'next' => 'http://sourcecodedb.com/logout.php' );
  $logoutUrl = $facebook->getLogoutUrl($params2);
  $fbuid = $facebook->getUser(); //Detta tar facebook användarens id från en aktiv session
if (isset($fbuid)) { //Detta kollar om session är aktiv
$user_profile = $facebook->api('/me','GET'); //nu kan information hämtas via $user_profile['informationduvillha']
$fbusername = $user_profile['username']; //Get the facebook username
}
if (isset($fbuid) && $_SESSION['Type'] != 'user') { //kollar om facebook session är öppen men att man inte är inloggad på sourcecodedb.com, detta är ofta om man tryckt login och ett konto har inte registrerats
	if ($_POST['hidden'] == 'newusername') { //om användaren inte har ett facebook username så tar vi det han eller hon skrev in och använder det som session username
        	$fbusername2 = mysql_real_escape_string($_POST['username']);
	$query = mysql_query("SELECT * FROM User WHERE FacebookID='$fbuid'"); //tar information där användarnamn från db
	$numrows = mysql_num_rows($query);
	if ($numrows!=0) //om användarnman hittades så gör if
	{
		
		while($row = mysql_fetch_assoc($query))
		{
				$dbusername = $row['Username']; // tar användar info från db
				$dbid = $row['ID'];
				$dbfname = $row['Fname'];
				$dblname = $row['Lname'];
		}
		
			$_SESSION['ID']=$dbid; //och skapar session
			$_SESSION['Username']=$dbusername;
			$_SESSION['Fname']=$dbfname;
			$_SESSION['Lname']=$dblname;
			$_SESSION['Type']='user';
				
	}

}
else {
$fbusername = $user_profile['username']; //Get the facebook username
}
}

/*
// Här slutar facebook funktionen för headern, resten finns i loggedin.php
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<?php
$title = $_GET['title'];
$title = str_replace("-sharp", "#", $title);
$title = str_replace("-or", " /", $title);
$title = str_replace("", "$", $title);
$title = str_replace("-and", " &amp;", $title);
$title = str_replace("-and", "&", $title);
$title = str_replace("-plus", "+", $title);
$title = str_replace("-equals", "=", $title);
$title = str_replace("-at", "@", $title);
$title = str_replace("-", " ", $title);
$currentFile = $_SERVER["PHP_SELF"];
$parts = Explode('/', $currentFile);
$curpage = $parts[count($parts) - 1];
if (strlen($title) > 1) {
echo '<title>';
echo $title;
echo ' - SourceCodeDB.com</title>';
$title = str_replace(" ", ", ", $title);
echo '
<meta type="keywords" content"source, code, database, sourcecode db, open source, ';
echo $title;
echo '">';
}
else {
?>
<title>SourceCodeDB.com</title>
<?php
echo '
<meta type="keywords" content"';
echo 'source, code, database, sourcecode db, open source, library, php, java, javascript, c sharp, objective c, html, css, ASP, c++';
echo '">';
}
if ($curpage == 'displaycode.php') {
$id5 = $_GET['code'];
$req5 = "SELECT * from CodeInfo where StringID = '$id5'";
$res5 = mysql_query($req5);
$data5 = mysql_fetch_array($res5);
$metadescription = $data5['Description'];
$metadescription = str_replace("\n", " ", $metadescription);

echo '
<meta type="description" content"';
echo $metadescription;
echo '">
';
}
else {
echo '
<meta type="description" content"';
echo 'SourceCodeDB is a place for all programmers, beginners and experts, to find and share useful source code on the net. All content on this site is free to use for everyone.';
echo '">
';
}
?>
<link href="stylesheets/header.css" rel="stylesheet" type="text/css">
<link href="stylesheets/body.css" rel="stylesheet" type="text/css">
<link href="stylesheets/rating.css" rel="stylesheet" type="text/css">
<link href="stylesheets/footer.css" media="screen, projection" rel="stylesheet" type="text/css">
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/header.js" type="text/javascript"></script>
<script src="js/filter.js" type="text/javascript"></script>
<script src="js/MinaSidor.js" type="text/javascript"></script>
<script src="http://cdn.jquerytools.org/1.2.6/full/jquery.tools.min.js"></script>

</head>
<body>
<div id="top"><div style="text-align:left;position:absolute;font-size:x-small;font-weight:bold;"><a href="http://sourcecodedb.com">Home</a> | <a href="ranked.php">Top Users</a> | <a href="news.php">News</a> | 
<?php if (isset($_SESSION['ID'])) {?><a href="mypages.php">My Profile<?php
$sessionid = $_SESSION['ID'];
$messages5 = mysql_query("SELECT * FROM Messages WHERE UserRec = $sessionid AND Viewed=0");
$numbermessages = mysql_num_rows($messages5);
echo ' ('.$numbermessages.')';
?></a> | <a href="addcode.php">Upload code</a><?php
$username9 = $_SESSION['ID'];
$sqlquery9 = mysql_query("SELECT PermissionID FROM User WHERE ID = $username9");
$sqlresult9 = mysql_result($sqlquery9,0);
if ($sqlresult9 >= 5) {?>
 | <a href="admin.php">Admin</a>
<?php
}
?>
 | <a href="requests.php">Requests</a>
<?php
} else {?><a href="register.php">Register</a><?php }?></div>
<?php if (isset($_SESSION['ID'])) {
?><div style="text-align:right;margin-right:72px;margin-top:2px;font-size:x-small;">Active login: <a href='mypages.php' id='active'>
<?php 
$findfbusername = "SELECT Fname, Lname FROM User WHERE FacebookID = '$fbuid'";
$fbuser = mysql_query($findfbusername) or die(mysql_error());
	if (mysql_num_rows($fbuser) >= 1 && $fbuid != NULL) {
	while ($namesdata = mysql_fetch_array($fbuser)) {
	echo $namesdata['Fname'];
	echo ' ';
	echo $namesdata['Lname'];
	echo ' logged in with Facebook';
	}
	}
	else {
echo $_SESSION['Username'];
}
?></a><?php } else {?><div style="text-align:right;margin-right:72px;margin-top:2px;font-size:x-small;">Already a member?<?php }?></div></div>
<div id='topnav' class='topnav'>
<?php
if (isset($_SESSION['ID'])) {
	echo '<a href="' . $logoutUrl . '" id="logout" class="logout"><span>Logout</span></a>';
}
else {
	echo "<a href='login' class='signin'><span>Sign in</span></a>";
}
?>

  <fieldset id="signin_menu">
    <form id="form1" action="login.php" method="post">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" />
      </p>
      <p>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" />
      </p>
      <p>
       <div id="nomember">Not a member?</div><div id="nomember2">- <a href="register.php">Register</a> now!</div>
       <input type="submit" id="login" name="login" value="Sign in" />
      </p>
            <div id="message"></div>
	</form>
	<br />
	<a href="<?php echo $loginUrl; ?>"><img src="images/loginfb.png" border="0"></a>
  </fieldset>
<a href="http://sourcecodedb.com"><img src="stylesheets/images/logo.png" id="title" border="0" width="860" height="150"></a>
<div id="language">Language</div>
<div id="difficulty">Difficulty</div>
<div id="categorys">Category</div>
<div id="search">Search</div>
<!--
 <div id="tab-container">
  <ul>
    <li<?php if ($thisPage=="index") 
      echo " id=\"currentpage\""; ?>>
      <a href="index.php">Home</a></li>
       <?php if (isset($_SESSION['ID'])){?>
    <li<?php if ($thisPage=="mypages") 
      echo " id=\"currentpage\""; ?>>
      <a href="mypages.php">My Profile</a></li>
    <li<?php if ($thisPage=="addcode") 
      echo " id=\"currentpage\""; ?>>
      <a href="addcode.php">Upload Code</a></li>
	   <?php }else {?>
	<li<?php if ($thisPage=="register") 
      echo " id=\"currentpage\""; ?>>
      <a href="register.php">Register</a></li>
	   <?php }?>
    <li<?php if ($thisPage=="top10") 
      echo " id=\"currentpage\""; ?>>
      <a href="top10.php">Top 10</a></li>
    <li<?php if ($thisPage=="news") 
      echo " id=\"currentpage\""; ?>>
      <a href="news.php">News</a></li>
  </ul>  
</div>
-->

<div id="dropdown">
<a href="#All" onmouseover="mopen('m1')" onmouseout="mclosetime()"><div id="category">All</div></a>
<ul id="sddm">
<div id="m1" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
    <li>
        <a href="#All" class="underline" onClick="setCategory('All')">All</a>
        <a href="#Windows" class="underline" onClick="setCategory('Windows')">Windows</a>
        <a href="#Web" class="underline" onClick="setCategory('Web')">Web</a>
        <a href="#iPhone" class="underline" onClick="setCategory('iPhone')">iPhone</a>
        <a href="#Linux" class="underline" onClick="setCategory('Linux')">Linux</a>
        <a href="#Android" class="underline" onClick="setCategory('Android')">Android</a>
        <a href="#Other" class="underline" onClick="setCategory('Other')">Other</a>
        </div>
    </li>
</ul>
</div>
<div id="menu">
<input type="button" id="btn1" value="C#" class="off" onClick="toggleState('btn1')" onFocus="this.blur()">
<input type="button" id="btn4" value="C++" class="off" onClick="toggleState('btn4')" onFocus="this.blur()">
<input type="button" id="btn11" value="VB" class="off" onClick="toggleState('btn11')" onFocus="this.blur()">
<input type="button" id="btn9" value="Java" class="off" onClick="toggleState('btn9')" onFocus="this.blur()">
<input type="button" id="btn6" value="Obj.C" class="off" onClick="toggleState('btn6')" onFocus="this.blur()">
<input type="button" id="btn2" value="PHP" class="off" onClick="toggleState('btn2')" onFocus="this.blur()">
<input type="button" id="btn3" value="HTML" class="off" onClick="toggleState('btn3')" onFocus="this.blur()">
<input type="button" id="btn10" value="JS" class="off" onClick="toggleState('btn10')" onFocus="this.blur()">
<input type="button" id="btn5" value="ASP" class="off" onClick="toggleState('btn5')" onFocus="this.blur()" style="margin-right:5px;">


<input type="button" id="btn7" value="Easy" class="on2" onClick="toggleState('btn7')" onFocus="this.blur()">
<input type="button" id="btn8" value="Hard" class="on2" onClick="toggleState('btn8')" onFocus="this.blur()" style="margin-right:245px;">
</div>
<input class="searchfield" id="Search" type="text"  onkeyup="addTextAreaCallback(this, updateTable, 200)" style="position:absolute;left:745px;top:175px;"/>
<!--Filter-->
<br /><br />
<div align="left" style="color:#FFF">
<table cellspacing="1px" class="hideTable" id="sortingTable">
        <thead> 
		<tr>
		<th width="275px" id="sortByTitle" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Title
		</th>
		<th width="65px" id="sortByDifficulty" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Difficulty
		</th>
		<th width="100px" id="sortByLanguage" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Language
		</th>
		<th width="100px" id="sortByUserID" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		User
		</th>
		<th width="70px" id="sortByCategory" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Category
		</th>
		<th width="70px" id="sortByLastEdit" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Last edit
		</th>
		<th width="50px" id="sortByViews" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Views
		</th>
		<th width="55px" id="sortByAVG(Score)" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Rating
		</th>
		<th width="50px" id="sortByComments" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Posts
		</th>
		</tr>
        </thead> 
		</table>
</div>
<div align="left" id="results"></div>
</div>
<div id="content">
<h3><?php echo 'You have been banned!'; ?></h3>

<p><?php echo 'This could be because of spamming, abuse to our site, or many other reasons. Please read below.'; ?></p>
<?php
$finduserban = mysql_query("SELECT * FROM Ban WHERE UserID = $sessionid")or die(mysql_error());
while ($databan = mysql_fetch_array($finduserban)) {
	echo 'Reason';
	echo ': ';
	echo $databan['Reason'];
	echo '<br /><br />';
	echo 'Date';
	echo ': ';
	echo $databan['Date'];
	echo '<br /><br />';
}
?>

<p><?php echo 'If you feel that this ban has occured as an error please contact us at'; ?> <a href="mailto:contact@sourcecodedb.com">contact@sourcecodedb.com</a></p>
</div>
<?php
include_once('footer.php');
?>