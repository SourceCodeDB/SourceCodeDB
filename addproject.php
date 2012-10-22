<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
include_once('header.php');

require_once("fbsdk/facebook.php");
 $config = array(
    'appId' => '225370617476152',
    'secret' => '03622faf6618ea99449f21079d13643f',
  );
  $facebook = new Facebook($config);
  $fbuid = $facebook->getUser();
if (isset($_SESSION['Type'])) {
if ($_SESSION['Type'] == 'user') {
$username = mysql_real_escape_string($_SESSION['Username']);
$userreq = "Select ID from User where username = '$username'";
$userid = mysql_result(mysql_query($userreq),0) or die (mysql_error());
$table = "CodeContent";
$table2 = "CodeInfo";
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$ledit = $date.' '.$time;
$descbad = mysql_real_escape_string($_POST['desc']);
$desc = htmlentities($descbad);
$title2 = mysql_real_escape_string($_POST['title']);
$title = htmlentities($title2);
if (isset($_POST['publish'])) {
		$published='1';
		$publog='Published';
	}
	elseif (isset($_POST['Save Privately'])) {
		$published='0';
		$publog='Saved';
	}

function isValidURL($url)
{
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}
$lang = mysql_real_escape_string($_POST['lang']);
$lang = htmlentities($lang);
$diff = mysql_real_escape_string($_POST['diff']);
$diff = htmlentities($diff);
$cat = mysql_real_escape_string($_POST['cat']);
$cat = htmlentities($cat);

function generateRandStr($length){
      $randstr = "";
      for($i=0; $i<$length; $i++){
         $randnum = mt_rand(0,9);
         $randstr .= $randnum;
      }
      $query = "SELECT COUNT(*) FROM CodeContent WHERE StringID = '{$randstr}'";

    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);

    if ($row['COUNT(*)'] > 0) { // if it already exists, do it again

        $randstr = generateRandStr(11);
    }
      return $randstr;
}
   
$codeinfoid = generateRandStr(11);
$urltitle = geturl($title);
$url = mysql_real_escape_string($urltitle);
if ($_SESSION['ID'] == $userid) {
$code1 = $_POST['code'];
$filedesc1 = $_POST['filedesc'];
$filename1 = $_POST['filename'];
$source1 = $_POST['source'];
$tags1 = $_POST['tags'];
$p=1;
foreach(array_keys($code1) as $i) {
        if (isset($source1[$i])) {
            $source = mysql_real_escape_string($source1[$i]);
        }
        else {
            $source = "";
        }
	$source = htmlentities($source);
	$filedescbad = $filedesc1[$i];
	$code = $code1[$i];
	$filename = $filename1[$i];
	$filename = htmlentities($filename);
        if (isset($tags1[$i])) {
            $tags = $tags1[$i];
        }
        else {
            $tags = "";
        }
	$tags = htmlentities($tags);
	$filedesc = htmlentities($filedescbad);
	if(strlen($source) > 0 && !isValidURL($source))
	{
 	echo 'Please enter valid URL including http://';
 	echo '<br>';
 	exit;
	}
	if (strlen($filename) > 70) {
	echo '<div id="content">';
	echo 'Title cant be that long';
	echo '</div>';
	exit;
	}
	elseif (strlen($filename) < 1) {
	echo '<div id="content">';
	echo 'Title cant be that short';
	echo '</div>';
	exit;
	}
	else {
	$sql = "INSERT INTO $table (ID, Content, Title, StringID, Description, Published, Source, Page) values ('', '$code', '$filename', $codeinfoid,'$filedesc','$published', '$source', '$p')";
	mysql_query($sql) or die (mysql_error());
	$p++;
	}
}
$sql2 = "INSERT INTO $table2 (ID, Date, UserID, LanguageID, DifficultyID, LEdit, Title, Url, CategoryID, Description, StringID, Published, TypeID, Revision) values ('', '$ledit', '$userid', '$lang', '$diff', '$ledit', '$title','$url','$cat','$desc', $codeinfoid, '$published', '3', '1')";
mysql_query($sql2) or die (mysql_error());
$ipforlog = $_SERVER['REMOTE_ADDR'];
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userid', '$publog Project: $title', '$ledit', '$ipforlog')");
//post to twitter
require 'twitteroauth/tmhOAuth.php';
require 'twitteroauth/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key' => 'O309gjFzlOkrnwsPA8ebcQ',
  'consumer_secret' => '3jFijBpGQ38TQi0JDzfXyqwJyQ2vTqjWYbSuGNx3U',
  'user_token' => '403388089-0uDI7UU8A8dUD9EGhM0YnmRfpiTxatNBCU8d1VgD',
  'user_secret' => 'HxMiCJAuMKzWSrZtZTJKsdhsnyGvk0HprX98pL3Q6YU',
));
$tmessage = $username.' just uploaded a new project titled '.$title.' at http://SourceCodeDB.com/'.$url.'.html';

$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
  'status' => $tmessage
));





if (isset($fbuid) && $_POST['facebook'] == 'Y') {
// add a status message
UpdateMonthlyRatings();
UpdateOverallRatings();
try {
    $status = $facebook->api('/me/feed', 'POST', array('message' => 'Just uploaded some new code called '.$title.' to http://SourceCodeDB.com/'.$url.'.html'));
    mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userid', 'Shared $title on Facebook', '$ledit', '$ipforlog')");
}
catch (Exception $e) {
    echo 'We were unable to post to facebook but your code has been saved';
    mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userid', 'Error: $e', '$ledit', '$ipforlog')");
}
}
echo 'Project Submitted! Once it has been approved it will show up on the main page.';
include_once('footer.php');
?>
<script>
    <!--
    window.location= "http://sourcecodedb.com/<?php echo $url; ?>.html"
    //-->
</script>
<?php
}
}
else {
echo 'Sorry you must be logged in to do that';
}
}
else {
echo 'Sorry you must be logged in to do that';
}
include_once('footer.php');
?>