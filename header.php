<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once("functions/functions.php");
session_start();
//Proxy check
//$iprange = '46.246.';
//if (substr($_SERVER['REMOTE_ADDR'], 0, strlen($iprange)) === $iprange) {
//$ipforlog = $_SERVER['REMOTE_ADDR'];
//$date = date("Y-m-d"); 
//$time = date("G:i:s"); 
//$logtime = $date.' '.$time;
//mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Uberbrainchild blocked the hacker', '$logtime', '$ipforlog')");
//    header('Location: http://sourcecodedb.com/blocked.php');
//    exit(0);
//}
//
// Make sessions safe
//
if(!isset($_SESSION['ua'])) {
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $_SESSION['ua'] = md5($_SERVER['HTTP_USER_AGENT']);
    }
} else {
    if($_SESSION['ua'] != md5($_SERVER['HTTP_USER_AGENT'])) {
    	session_destroy();
        header('Location: http://sourcecodedb.com/out.php');
        exit(0);
    }
}
if(!isset($_SESSION['you'])) {
    if (isset($_SERVER['REMOTE_ADDR'])) {
    $_SESSION['you'] = md5($_SERVER['REMOTE_ADDR']);
    }
} else {
    if($_SESSION['you'] != md5($_SERVER['REMOTE_ADDR'])) {
    	session_destroy();
        header('Location: http://sourcecodedb.com/out.php');
        exit(0);
    }
}

//

date_default_timezone_set('Europe/Stockholm'); // specifierar timezone till svensk

/*
// Check for bans
*/

if (isset($_SESSION['Type'])) {
	$banuserid = $_SESSION['ID'];
	$finduser = mysql_query("SELECT * FROM Ban WHERE UserID = $banuserid");
	if (mysql_num_rows($finduser) > 0) {
		?>
		<script>
    	<!--
    	window.location= "http://sourcecodedb.com/banned.php"
    	//-->
    	</script>
<?php
	header('Location: http://sourcecodedb.com/banned.php');
	}
}

/*
//Twitter API
*/
                
//require_once('twitteroauth/twitteroauth.php');
 
//define("CONSUMER_KEY", "O309gjFzlOkrnwsPA8ebcQ");
//define("CONSUMER_SECRET", "3jFijBpGQ38TQi0JDzfXyqwJyQ2vTqjWYbSuGNx3U");
//define("OAUTH_TOKEN", "403388089-0uDI7UU8A8dUD9EGhM0YnmRfpiTxatNBCU8d1VgD");
//define("OAUTH_SECRET", "HxMiCJAuMKzWSrZtZTJKsdhsnyGvk0HprX98pL3Q6YU");
// 
//$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
//$content = $connection->get('account/verify_credentials');

/*
// Här börjar facebook login funktionen, första delen tar med facebook.php som 
// måste finnas med för att tala med facebook och få infromation
*/

//if (isset($fbuid)) { //Detta kollar om session är aktiv
//$user_profile = $facebook->api('/me','GET'); //nu kan information hämtas via $user_profile['informationduvillha']
//$fbusername = $user_profile['username']; //Get the facebook username
//}
//if ($fbuid && $_SESSION['Type'] != 'user') { //kollar om facebook session är öppen men att man inte är inloggad på sourcecodedb.com, detta är ofta om man tryckt login och ett konto har inte registrerats
//	if ($_POST['hidden'] == 'newusername') { //om användaren inte har ett facebook username så tar vi det han eller hon skrev in och använder det som session username
//        	$fbusername2 = mysql_real_escape_string($_POST['username']);
//	$query = mysql_query("SELECT * FROM User WHERE FacebookID='$fbuid'"); //tar information där användarnamn från db
//	$numrows = mysql_num_rows($query);
//	if ($numrows!=0) //om användarnman hittades så gör if
//	{
//		
//		while($row = mysql_fetch_assoc($query))
//		{
//				$dbusername = $row['Username']; // tar användar info från db
//				$dbid = $row['ID'];
//				$dbfname = $row['Fname'];
//				$dblname = $row['Lname'];
//		}
//		
//			$_SESSION['ID']=$dbid; //och skapar session
//			$_SESSION['Username']=$dbusername;
//			$_SESSION['Fname']=$dbfname;
//			$_SESSION['Lname']=$dblname;
//			$_SESSION['Type']='user';
//				
//	}
//
//}
//else {
//$fbusername = $user_profile['username']; //Get the facebook username
//}
//}

/*
// Här slutar facebook funktionen för headern, resten finns i loggedin.php
*/
// Multilanguage
//if ($_SESSION['lang'] == 'sv_SV') {
//$locale = 'sv_SE';
//putenv("LC_ALL=$locale");
//setlocale(LC_ALL, $locale);
//bindtextdomain("sv_SE", "locale");
//textdomain("sv_SE");
//}
//else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
<?php
if (isset($_GET['title'])) {
$hurl = $_GET['title'];

$currentFile = $_SERVER["PHP_SELF"];
$parts = Explode('/', $currentFile);
$curpage = $parts[count($parts) - 1];
$hfindcode = mysql_query("SELECT * FROM CodeInfo WHERE Url = '$hurl' && IsTemp='0'");
while ($hfound = mysql_fetch_array($hfindcode)) {
	$htags = $hfound['Tags'];
	$hdesc = $hfound['Description'];
	$htitle = $hfound['Title'];
}
if (isset($htitle)) {
echo '<title>';
echo $htitle;
echo ' - SourceCodeDB.com</title>';
echo '<meta name="keywords" content="'.$htags.'">';
}
elseif (isset($_GET['user'])) {
$huser = $_GET['user'];
echo '<title>';
echo $huser;
echo '&rsquo;s profile on SourceCodeDB.com</title>';
}
}
else {
?>
<title>SourceCodeDB.com</title>
<?php
echo '<meta name="keywords" content="';
echo 'source, code, database, sourcecode db, open source, library, php, java, javascript, c sharp, objective c, html, css, ASP, c++';
echo '">';
}
if (isset($hdesc)) {
$metadesc = str_replace("\n", " ", $hdesc);
echo '
<meta name="description" content="';
echo $metadesc;
echo '">
';
}
else {
echo '
<meta name="description" content="';
echo 'SourceCodeDB is a place for all programmers, beginners and experts, to find and share useful source code on the net. All content on this site is free to use for everyone.';
echo '">';
}

?>
<meta name="google-site-verification" content="xlCjU0n9VeagsXzo_vpwiQ5Er1WpR5n7-y2iSf7hgRY" />
<link href="stylesheets/header.css" rel="stylesheet" type="text/css">
<link href="stylesheets/body.css" rel="stylesheet" type="text/css">
<link href="stylesheets/rating.css" rel="stylesheet" type="text/css">
<link href="stylesheets/footer.css" media="screen, projection" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="code/lib/codemirror.css">
<script src="js/jquery.min.js"></script>
<script src="js/jquery.tools.min.js"></script>
<script src="js/header.js" type="text/javascript"></script>
<script src="js/filter.js" type="text/javascript"></script>
<script src="js/News.js" type="text/javascript"></script>
<script type="text/javascript" src="js/News.js"></script>
<script src="js/MinaSidor.js" type="text/javascript"></script>
<script type="text/javascript" src="js/relcopy.js"></script>
<script src="code/lib/codemirror.js"></script>
<script src="code/mode/xml/xml.js"></script>
<script src="code/mode/javascript/javascript.js"></script>
<script src="code/mode/css/css.js"></script>
<script src="code/mode/htmlmixed/htmlmixed.js"></script>

<script>
    function reply(id) {
        var state = document.getElementById(id).style.display;
            if (state == 'block') {
                document.getElementById(id).style.display = 'none';
            } else {
                document.getElementById(id).style.display = 'block';
            }
        }
</script>

<script>

// tre linjer skapar varje fil/del i addproject.php
$(function(){
$('a.add').relCopy();
});

$(document).ready(function () {
    $('img.menu_class').click(function () {
    $('ul.menu').slideToggle('medium');
    });
});

$(document).ready(function () {
    $('img.search_class').click(function () {
    $('ul.sddm').slideToggle('medium');
    $('.menu2').slideToggle('medium');
    $('.results').slideToggle('slow');
    $('.results2').slideToggle('fast');
    });
});
$(document).ready(function () {
    $('img.login_class').click(function () {
    $('ul.signin_menu').slideToggle('medium');
   });
});

    function reply(id) {
        var state = document.getElementById(id).style.display;
            if (state == 'block') {
                document.getElementById(id).style.display = 'none';
            } else {
                document.getElementById(id).style.display = 'block';
            }
        }
</script>

</head>
<body>
<!--    
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/sv_SE/all.js#xfbml=1&appId=225370617476152";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
-->


<div class="wrapper">
	<div class="header">
		<div class="headertop">
		<a href="http://sourcecodedb.com"><img src="images/v2/logo.png" border="0" class="logov2"></a>
		<img src="images/v2/menu.png" class="menu_class">
		<ul class="menu">
<li><a href="questions.php"><?php echo 'Questions'; ?></a></li>
<li><a href="ranked.php"><?php echo 'Top Users'; ?></a></li>
<?php if (isset($_SESSION['ID'])) {?><li><a href="mypages.php"><?php echo 'My Profile'; ?><?php
$sessionid = $_SESSION['ID'];
$messages5 = mysql_query("SELECT * FROM Messages WHERE UserRec = $sessionid AND Viewed=0");
$numbermessages = mysql_num_rows($messages5);
echo ' ('.$numbermessages.')';
?></a></li>
<li><a href="sendmessage.php"><?php echo 'Send Msg'; ?></a></li>
<li><a href="addcode.php"><?php echo 'Add code'; ?></a></li>
<li><a href="project.php"><?php echo 'Add Project'; ?></a></li>
<?php
$username9 = $_SESSION['ID'];
$sqlquery9 = mysql_query("SELECT PermissionID FROM User WHERE ID = $username9");
$sqlresult9 = mysql_result($sqlquery9,0);
if ($sqlresult9 >= 5) {?>
<li><a href="admin.php"><?php echo 'Admin'; ?></a></li>
<?php
}
?>
<li><a href="requests.php"><?php echo 'Requests'; ?></a></li>
<?php
} else {?><li><a href="register.php"><?php echo 'Register' ?></a></li><?php }?>
		</ul>


<?php
if (isset($_SESSION['ID'])) {
	echo '<a href="#" id="logout" class="logout"><img src="images/v2/logout.png"></a>';
}
else {
	echo '<img src="images/v2/login.png" class="login_class" border="0">';
}
?>

  <ul class="signin_menu">
  	
  	<li>
    <form id="form1" action="login.php" method="post">
      <?php echo 'Username'; ?>
      <input type="text" name="username" id="username" />
      
        <?php echo 'Password'; ?>
        <input type="password" name="password" id="password" />
      
       <?php echo 'Not a member?'; ?> - <a href="register.php"><?php echo 'Register'; ?></a> <?php echo 'now!'; ?>
       <input type="submit" id="login" name="login" value="<?php echo 'Sign in'; ?>" />
      
            <center><div id="message"></div></center>
	</form>
	<?php echo 'Or log in with Facebook'; ?>:
	<a href="/fblogin.php"><img src="images/loginfb.png" border="0"></a>
	</li>
	
  </ul>

</div>

</div>

<div id="dropdown">
<div id="menu" class="menu2">
<input type="button" id="btn12" value="C" class="off" onClick="toggleState('btn12')" onFocus="this.blur()">
<input type="button" id="btn1" value="C#" class="off" onClick="toggleState('btn1')" onFocus="this.blur()">
<input type="button" id="btn4" value="C++" class="off" onClick="toggleState('btn4')" onFocus="this.blur()">
<input type="button" id="btn11" value="VB" class="off" onClick="toggleState('btn11')" onFocus="this.blur()">
<input type="button" id="btn9" value="Java" class="off" onClick="toggleState('btn9')" onFocus="this.blur()">
<input type="button" id="btn6" value="Obj.C" class="off" onClick="toggleState('btn6')" onFocus="this.blur()">
<input type="button" id="btn2" value="PHP" class="off" onClick="toggleState('btn2')" onFocus="this.blur()">
<input type="button" id="btn13" value="SQL" class="off" onClick="toggleState('btn13')" onFocus="this.blur()">
<input type="button" id="btn3" value="HTML" class="off" onClick="toggleState('btn3')" onFocus="this.blur()">
<input type="button" id="btn10" value="JS" class="off" onClick="toggleState('btn10')" onFocus="this.blur()">
<input type="button" id="btn14" value="Python" class="off" onClick="toggleState('btn14')" onFocus="this.blur()">
<input type="button" id="btn5" value="ASP" class="off" onClick="toggleState('btn5')" onFocus="this.blur()">
<input type="button" id="btn15" value="PERL" class="off" onClick="toggleState('btn15')" onFocus="this.blur()">
<input type="button" id="btn7" value="Easy" class="on2" onClick="toggleState('btn7')" onFocus="this.blur()">
<input type="button" id="btn8" value="Hard" class="on2" onClick="toggleState('btn8')" onFocus="this.blur()">

<input class="searchfield" id="Search" type="text"  onkeyup="addTextAreaCallback(this, updateTable, 200)"/>


</div>

</div>
<img src="images/v2/search.png" class="search_class">
<div id="content">

<!--Filter-->
<div id="results2" class="results2" align="left" style="color:#FFF">
<table cellspacing="1px" class="hideTable" id="sortingTable">
        <thead> 
		<tr>
		<th width="295px" id="sortByTitle" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Title
		</th>
		<th width="85px" id="sortByDifficulty" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Difficulty
		</th>
		<th width="120px" id="sortByLanguage" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Language
		</th>
		<th width="120px" id="sortByUserID" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		User
		</th>
		<th width="90px" id="sortByCategory" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Category
		</th>
		<th width="90px" id="sortByLastEdit" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Last edit
		</th>
		<th width="70px" id="sortByViews" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Views
		</th>
		<th width="65px" id="sortByAVG(Score)" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Rating
		</th>
		<th width="64px" id="sortByComments" class="updown" bgcolor="#000" onClick="toggleArrow(this)" style="cursor:pointer;cursor:hand">
		Posts
		</th>
		</tr>
        </thead> 
		</table>
</div>
<div align="left" id="results" class="results"></div>