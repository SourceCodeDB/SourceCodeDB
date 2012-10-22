<?php
include_once('header.php');
$id = $_GET['p'];
if ($_SESSION['Type'] == 'user') {
$username = $_SESSION['ID'];
$findrequests = mysql_query("SELECT * FROM Request WHERE ID = $id");
$data = mysql_fetch_array($findrequests);
$title = $data['Title'];

$sqlquery = mysql_query("SELECT PermissionID FROM User WHERE ID = $username");
$sqlresult = mysql_result($sqlquery,0);
if ($sqlresult >= 5) {
if ($_POST['approve'] == 'Y') {
	mysql_query("UPDATE Request SET Approved='1' WHERE ID = $id");
	$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Approved Request: $title', '$logtime', '$ipforlog')");
	echo '<b>';
	echo 'Success';
	echo '</b><br /><br />';
}
echo 'Title: ';
echo $data['Title'];
echo '<br />';
echo 'Date';
echo ': ';
echo $data['SubmitDate'];
echo '<br />';
echo 'By';
echo ': ';
$theuserid = $data['UserID'];
$selectuser = mysql_query("SELECT Username FROM User WHERE ID = $theuserid");
echo mysql_result($selectuser,0);
echo '<br />';
echo 'Approved';
echo ': ';
if ($data['Approved'] == '1') {
echo 'Yes';
}
else {
if ($_POST['approve'] == 'Y') {
echo 'Yes';
}
else {
echo 'No';
}
}
echo '<br />';
echo 'The Request';
echo ': <br />';
echo $data['Content'];
echo '<br /><br />';
if ($data['Approved'] == '1') {
echo '<B>';
echo 'This comment is approved';
echo '</B>';
}
else {
if ($_POST['approve'] == 'Y') {
echo '<B>';
echo 'This comment is approved';
echo '</B>';
}
else {
echo '<form method="post" action="viewrequest.php?p=';
echo $id;
echo '">';
echo '<input type="hidden" name="approve" value="Y">';
echo '<input type="submit" name="submit" value="Approve">';
echo '</form>';
}
}


}
elseif ($_SESSION['ID'] && $data['Approved'] == 1) {
echo 'Title';
echo ': ';
echo $data['Title'];
echo '<br />';
echo 'Date';
echo ': ';
echo $data['SubmitDate'];
echo '<br />';
echo 'By';
echo ': ';
$theuserid = $data['UserID'];
$selectuser = mysql_query("SELECT Username FROM User WHERE ID = $theuserid");
echo mysql_result($selectuser,0);
echo '<br />';
echo 'Approved';
echo ': ';
if ($data['Approved'] == '1') {
echo 'Yes';
}
else {
echo 'No';
}
echo '<br />';
echo 'The Request';
echo ': <br />';
echo $data['Content'];
echo '<br />';
}
else {
echo 'Request not approved';
}

}
else {
echo 'Sorry you must be logged in to see and use the request features.';
}
include_once('footer.php');
?>