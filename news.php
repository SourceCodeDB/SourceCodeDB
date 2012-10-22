<?php
include_once("functions/db.php");
if(!isset($_POST['NewsID']) && (!isset($_POST['Todo'])))
{
include_once("header.php");
}
if(!isset($_POST['NewsID']) && (!isset($_POST['Todo'])))
echo "<div id='content'>";
if (isset($_SESSION['Type'])) {
$username = $_SESSION['Username'];
$id = $_SESSION['ID'];
$admin = "SELECT ID, PermissionID FROM User WHERE ID='$id' AND PermissionID>=5";
$admin = mysql_query($admin);
while($admins = mysql_fetch_array($admin))
{
if(isset($_POST['title']) && isset($_POST['content']))
{
$title2 = mysql_real_escape_string($_POST['title']);
$title = trim(htmlentities($title2));
$content2 = mysql_real_escape_string($_POST['content']);
$content = trim(htmlentities($content2));
$date = date('Y-m-d G:i:s');
$checkadmin = "SELECT PermissionID FROM User WHERE ID = '$id'";
$useradmin = mysql_result(mysql_query($checkadmin),0) or die (mysql_error());
if($useradmin >= 5) {
$insertNews="INSERT INTO News(UserID, Title, Content, Created) VALUES (".$id.",'$title','$content','$date')";
$ipforlog = $_SERVER['REMOTE_ADDR'];
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$id', 'Added News: $title', '$date', '$ipforlog')");
mysql_query($insertNews) or die (mysql_error());
$findrequests45 = mysql_query("SELECT * FROM User");
while ($data45 = mysql_fetch_array($findrequests45)) {
    $notifyon = $data45['Notifications'];
    if ($notifyon == 1) {
        $to = $data45['Email'];
        $subject = $title;
        $body = $username." Just posted some news at SourceCodeDB.<br/><br/>
        ".preg_replace('/\v+|\\\r\\\n/','<br/>',$content)." <br/><br/>
        Regards,<br/>
        The SourceCodeDB Team <br/>
        <br/>
        To stop receiving these messages update your settings at SourceCodeDB.com";
        $headers = "Content-Type: text/html" . "\r\n";
        $headers .= 'From: no-reply@sourcecodedb.com' . "\r\n" .
        'Reply-To: no-reply@sourcecodedb.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
        try {
        mail($to, $subject, $body, $headers);
        mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '', 'Email sent to $to', '$date', 'Server')");
        }
        catch (ErrorException $e) {
            echo "could not send email";
        }
    }
}
require 'twitteroauth/tmhOAuth.php';
require 'twitteroauth/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key' => 'O309gjFzlOkrnwsPA8ebcQ',
  'consumer_secret' => '3jFijBpGQ38TQi0JDzfXyqwJyQ2vTqjWYbSuGNx3U',
  'user_token' => '403388089-0uDI7UU8A8dUD9EGhM0YnmRfpiTxatNBCU8d1VgD',
  'user_secret' => 'HxMiCJAuMKzWSrZtZTJKsdhsnyGvk0HprX98pL3Q6YU',
));

$tmessage = $tusername.' added some news at http://sourcecodedb.com/news.php';

$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
  'status' => $tmessage
));

}
}
if(isset($_POST['NewsID']))
{
	$newsID = mysql_real_escape_string($_POST['NewsID']);
}
elseif(isset($_POST['Todo']))
{
	$todo = mysql_real_escape_string($_POST['Todo']);
        $todo = trim(htmlentities($todo));
	$changeID = mysql_real_escape_string($_POST['changeID']);
        $changeID = trim(htmlentities($changeID));
	$changeTitle2 = mysql_real_escape_string($_POST['changeTitle']);
	$changeTitle = trim(htmlentities($changeTitle2));
	$changeContent2 = mysql_real_escape_string($_POST['changeContent']);
	$changeContent = trim(htmlentities($changeContent2));
	$checkadmin = "SELECT PermissionID FROM User WHERE ID = '$id'";
	$useradmin = mysql_result(mysql_query($checkadmin),0) or die (mysql_error());
	if($todo=="update" && $useradmin >= 5)
	{
		$edit = "UPDATE News SET Content='".$changeContent."', Title='".$changeTitle."' WHERE ID='".$changeID."'";
		mysql_query($edit);
	}
	elseif($todo=="delete" && $useradmin >= 5)
	{
		$delete = "DELETE FROM News WHERE ID='".$changeID."'";
		mysql_query($delete);
	}
}
else
{
	echo "<input type='button' id='News' value='";
	echo 'Create News';
	echo " [ + ]' onclick='createNews()'/>";
}
}

?>
<!--<br><br />-->
<div id="form"></div>
<?php
}
$New = "SELECT *,News.ID as NID FROM News, User WHERE News.UserID=User.ID ORDER BY Created DESC";
$New = mysql_query($New);
?>
<div id="NewsList">
<?php
while($News=mysql_fetch_array($New))
{
    if (isset($newsID)) {
	if($newsID==$News['NID'])
	{
		echo "<table border='0px' width='500px' cellspacing='0px'>
		<td>Title: <input type='text' value=\"".$News['Title']."\" id='editTitle'/></td></tr>";
		echo "<tr><tr><td>Content:</td></tr><tr><td colspan='3'><textarea rows='10' cols='50' id='editContent'>".nl2br($News['Content'])."</textarea></td></tr></table>";
		$admin = "SELECT ID, PermissionID FROM User WHERE ID='".$id."' AND PermissionID>=5";
		$admin = mysql_query($admin);
		while($admins = mysql_fetch_array($admin))
		{
		?>
		<input type="button" value="Save" onclick="modifyNews('<?php echo $newsID; ?>','editTitle','editContent', 'update')"/>
        	<input type="button" value="Delete" onclick="modifyNews('<?php echo $newsID; ?>','editTitle','editContent', 'delete')"/>
        <br><br>
		<?php
		}
	}
    }
	else
	{
		echo "<table border='0px' width='710px' cellspacing='0px' bgcolor='F2F2F2'><tr bgcolor='#444' style='color:#fff;'><td align='left' style='padding:5px;'>Author: ".$News['Username']."</td>
		<td align='right' width='150px' style='font-size:0.9em;padding:5px;'>".$News['Created']."</td></tr>
		<tr><td style='padding-left:10px;padding-top:5px;font-size:1.2em;font-weight:bold;width:200px;'>".$News['Title']."</td></tr><tr><td colspan='2' style='padding:10px;'>".nl2br($News['Content'])."</td></tr>
		<tr><td colspan='2' align='right'>";
                if (isset($id)) {
		$admin = "SELECT ID, PermissionID FROM User WHERE ID='$id' AND PermissionID>=5";
		$admin = mysql_query($admin);
		while($admins = mysql_fetch_array($admin))
		{
		echo "<input type='button' value='Edit' onclick='editNews(\"".$News['NID']."\")' />";
		}
                }
		echo "</td></tr><br></table>";
	}
}
?>
</div>
</div>
<?php
if(!isset($_POST['NewsID']) && (!isset($_POST['Todo'])))
include_once("footer.php");
?>
