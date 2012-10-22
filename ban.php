<?php
include_once('header.php');
if (isset($_GET['p']) || isset($_SESSION['ID']) || isset($_SESSION['Type'])) {
$id = $_GET['p'];
$id = trim(htmlentities($id));
$frompagesend = $_GET['link'];
$username = $_SESSION['ID'];
$sqlquery = mysql_query("SELECT PermissionID FROM User WHERE ID = $username");
$sqlresult = mysql_result($sqlquery,0);
if ($_SESSION['Type'] == 'user' && $sqlresult >= 5) {
$findrequests = mysql_query("SELECT * FROM User WHERE ID = $id");
$data = mysql_fetch_array($findrequests);

if (isset($_SESSION['ID'])) {
if (isset($_POST['message'])) {
$title = mysql_real_escape_string($_POST['title']);
$title = trim(htmlentities($title));
$content = mysql_real_escape_string($_POST['content']);
$content = trim(htmlentities($content));
$ipforlog = $_SERVER['REMOTE_ADDR'];
$frompage = mysql_real_escape_string($_POST['from']);
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
$usernamelog = $data['Username'];
$lastmsg = mysql_query("SELECT Date FROM Warn WHERE AdminID = $username ORDER BY Date DESC LIMIT 1");
$findlastmsg = mysql_result($lastmsg,0);
if (strtotime($logtime) - strtotime($findlastmsg) < 5) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Tried to send messages too often', '$logtime', '$ipforlog')");
echo 'Please wait 5 seconds between bans';
}
elseif (strtotime($logtime) - strtotime($findlastmsg) > 5) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Banned $usernamelog', '$logtime', '$ipforlog')");
mysql_query("INSERT INTO Ban (ID, UserID, Location, AdminID, Date, Reason) VALUES ('', '$id', '$frompage', '$username', '$logtime', '$content')");
mysql_query("INSERT INTO Messages (ID, UserSent, UserRec, Date, Title, Content, IP) VALUES ('', '$username', '$id', '$logtime', '$title', '$content', '$ipforlog')");
echo 'Ban sent!';
$email = mysql_result(mysql_query("SELECT Email FROM User WHERE ID = $id"),0);
$to = $email;
$success = 'Y';
$subject = "SourceCodeDB - You have been banned";
$body = "You have been banned for the following reason: ".$content."\n";
$headers = 'From: no-reply@sourcecodedb.com' . "\r\n" .
   'Reply-To: no-reply@sourcecodedb.com' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();
try {
mail($to, $subject, $body, $headers);
}
 catch (ErrorException $e) {
     echo "could not send email";
 }
echo '</div>';
include_once('footer.php');
?>
<script>
    <!--
    window.location= "<?php echo $frompage; ?>"
    //-->
    </script>
    <?php
}
}
elseif ($username == $id) {
$ipforlog = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
$usernamelog = $data['Username'];
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Tried to ban self', '$logtime', '$ipforlog')");
echo 'You cant ban yourself?';
}
else {
echo '<form method="post" action="ban.php?p=';
echo $id;
echo '">';
echo 'Ban';
echo ': ';
echo $data['Username'];
echo '<br /><br />';
echo 'Title:';
echo ' <input type="text" name="title" value="You have been banned">';
echo '<br /><br />';
echo 'Reason for ban:';
echo ' <br />';
echo '<textarea name="content" rows="10" cols="85"></textarea>';
echo '<br /><br />';
echo '<input type="hidden" name="message" value="sent">';
echo '<input type="hidden" name="from" value="';
echo $frompagesend;
echo '">';
echo '<input type="submit" name="submit" value="Send">';
echo '</form>';
}
}
else {
$ipforlog = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Tried to access sendmessage.php', '$logtime', '$ipforlog')");
echo 'You dont have permission to ban users';
}
?>
<?php
}
else {
echo 'Sorry you must be logged in to ban users.';
}
}
else {
echo 'Sorry you must be logged in to ban users.';
}
include_once('footer.php');
?>