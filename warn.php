<?php
include_once('header.php');
$id = $_GET['p'];
$username = $_SESSION['ID'];
$frompagesend = $_GET['link'];
$sqlquery = mysql_query("SELECT PermissionID FROM User WHERE ID = $username");
$sqlresult = mysql_result($sqlquery,0);
if ($_SESSION['Type'] == 'user' && $sqlresult >= 5) {
$findrequests = mysql_query("SELECT * FROM User WHERE ID = $id");
$data = mysql_fetch_array($findrequests);

if ($_SESSION['ID']) {
if ($_POST['message'] == 'sent') {
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
echo 'Please wait 5 seconds between warnings';
}
elseif (strtotime($logtime) - strtotime($findlastmsg) > 5) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Sent warning to $usernamelog', '$logtime', '$ipforlog')");
mysql_query("INSERT INTO Warn (ID, UserID, Location, AdminID, Date, Reason) VALUES ('', '$id', '$frompage', '$username', '$logtime', '$content')");
mysql_query("INSERT INTO Messages (ID, UserSent, UserRec, Date, Title, Content, IP) VALUES ('', '$username', '$id', '$logtime', '$title', '$content', '$ipforlog')");
echo 'Warning sent!';
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
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Tried sending warning to self', '$logtime', '$ipforlog')");
echo 'You cant send yourself a message';
}
else {
echo '<form method="post" action="warn.php?p=';
echo $id;
echo '">';
echo 'Warn';
echo ': ';
echo $data['Username'];
echo '<br /><br />';
echo 'Title';
echo ': ';
echo '<input type="text" name="title" value="You have received a warning">';
echo '<br /><br />';
echo 'Reason for warning';
echo ': <br />';
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
echo 'You dont have permission to view this message';
}
}
else {
echo 'Sorry you must be logged in to see your messages.';
}
include_once('footer.php');
?>