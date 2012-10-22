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
if ($_SESSION['Type'] == 'user') {
$username = $_SESSION['Username'];
$userreq = "Select ID from User where username = '$username'";
$userid = mysql_result(mysql_query($userreq),0) or die (mysql_error());
$table = "CodeContent";
$table2 = "CodeInfo";
$code = $_POST['code'];
$descbad = mysql_real_escape_string($_POST['desc']);
$desc = trim(htmlentities($descbad));
$title2 = mysql_real_escape_string($_POST['title']);
$title = trim(htmlentities($title2));
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$ledit = $date.' '.$time;
$source = mysql_real_escape_string($_POST['source']);
$source = trim(htmlentities($source));
$tags = mysql_real_escape_string($_POST['tags']);
$tags = trim(htmlentities($tags));
$lang = mysql_real_escape_string($_POST['lang']);
$lang = trim(htmlentities($lang));
$diff = mysql_real_escape_string($_POST['diff']);
$diff = trim(htmlentities($diff));
$cat = mysql_real_escape_string($_POST['cat']);
$cat = trim(htmlentities($cat));
function isValidURL($url)
{
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}
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
$url = geturl($title);
$userverify = "Select UserID from CodeInfo where StringID = '$codeinfoid'";
if ($_SESSION['ID'] == $userid) {
if ($_POST['publish']) {
	$published='1';
	$publog='Published';
}
elseif ($_POST['save']) {
	$published='0';
	$publog='Save Privately';
}
}
if(strlen($source) > 0 && !isValidURL($source))
{
 echo 'Please enter valid URL including http://';
 echo '<br>';
}
if (strlen($title) > 70) {
	echo 'Title cant be that long';
}
elseif (strlen($title) < 1) {
	echo 'Title cant be that short';
}
else {
$sql = "INSERT INTO $table (ID, Content, Title, StringID, Description, Published, Source) values ('', '$code', '$title', $codeinfoid,'$desc','$published', '$source')";
$sql2 = "INSERT INTO $table2 (ID, Date, UserID, LanguageID, DifficultyID, LEdit, Title, Url, CategoryID, Description, StringID, Published, TypeID, Tags, Revision) values ('', '$ledit', '$userid', '$lang', '$diff', '$ledit', '$title','$url','$cat','$desc', $codeinfoid, '$published', '1', '$tags', '1')";
mysql_query($sql) or die (mysql_error());
mysql_query($sql2) or die (mysql_error());
$tempid = mysql_result(mysql_query("SELECT TempID FROM CodeInfo WHERE UserID='$userid' AND TempID!='0'"),0);
mysql_query("DELETE CodeContent FROM CodeContent WHERE IsTemp=1 AND TempID='$tempid'");
mysql_query("DELETE CodeInfo FROM CodeInfo WHERE IsTemp=1 AND UserID='$userid'");
$ipforlog = $_SERVER['REMOTE_ADDR'];
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userid', '$publog Code: $title', '$ledit', '$ipforlog')");
//post to twitter
require 'twitteroauth/tmhOAuth.php';
require 'twitteroauth/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key' => 'O309gjFzlOkrnwsPA8ebcQ',
  'consumer_secret' => '3jFijBpGQ38TQi0JDzfXyqwJyQ2vTqjWYbSuGNx3U',
  'user_token' => '403388089-0uDI7UU8A8dUD9EGhM0YnmRfpiTxatNBCU8d1VgD',
  'user_secret' => 'HxMiCJAuMKzWSrZtZTJKsdhsnyGvk0HprX98pL3Q6YU',
));

$tmessage = $username.' just uploaded a new code titled '.$title.' at http://SourceCodeDB.com/'.$url.'.html';
    
$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
  'status' => $tmessage
));



if ($fbuid && $_POST['facebook'] == 'Y') {
// add a status message
try {
    $status = $facebook->api('/me/feed', 'POST', array('message' => 'Just uploaded some new code called '.$title.' to http://SourceCodeDB.com/'.$url.'.html'));
    mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userid', 'Shared $title on Facebook', '$ledit', '$ipforlog')");
}
catch (Exception $e) {
    echo 'We were unable to post to facebook but your code has been saved';
    mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userid', 'Error: $e', '$ledit', '$ipforlog')");
}
}
echo 'Code Submitted! Once it has been approved it will show up on the main page.';

//asyncronous loading of ratings
    $parts=parse_url('http://sourcecodedb.com/functions/update_ratings.php'); //This is the file you want to run in the background
     
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
include_once('footer.php');
?>