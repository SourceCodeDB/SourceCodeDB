<?php
include_once('header.php');
$id = $_GET['p'];
if ($_SESSION['Type'] == 'user') {
$username = $_SESSION['ID'];
$findrequests = mysql_query("SELECT * FROM Messages WHERE ID = $id");
$data = mysql_fetch_array($findrequests);
$title = $data['Title'];

if ($_SESSION['ID'] == $data['UserRec']) {
$read = '1';
mysql_query("UPDATE Messages SET Viewed='$read' WHERE ID = $id");
echo '<b>';
echo 'Title';
echo ':</b> ';
echo $data['Title'];
echo '<br /><br />';
echo '<b>';
echo 'Sent';
echo ':</b> ';
echo $data['Date'];
echo '<br /><br />';
echo '<b>';
echo 'By';
echo ':</b> ';
$theuserid = $data['UserSent'];
$selectuser = mysql_query("SELECT Username FROM User WHERE ID = $theuserid");
echo mysql_result($selectuser,0);
echo '<br /><br />';
echo '<b>';
echo 'Message';
echo ':</b> ';
echo $data['Content'];
echo '<br /><br />';
echo '<a href="sendmessage.php?p=';
echo $theuserid;
echo '">';
echo 'Reply to message';
echo '</a>';
}
else {
$ipforlog = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Tried to view message that is not his/hers', '$logtime', '$ipforlog')");
echo 'You dont have permission to view this message';
}

}
else {
echo 'Sorry you must be logged in to see your messages.';
}
include_once('footer.php');
?>