<?php
include_once('header.php');
if (isset($_SESSION['ID'])) {
$username = $_SESSION['ID'];
}
else {
    $username = 0;
}
if (isset($_POST['qsubmit']) && $username > 0) {
$title = mysql_real_escape_string($_POST['title']);
$title = trim(htmlentities($title));
$content = mysql_real_escape_string($_POST['content']);
$content = trim(htmlentities($content));
$countwords = explode(" ",$content);
$count = 0;
foreach ($countwords as $thewords) {
	$count++;
}
$content = trim(htmlentities($content));
$title = trim(htmlentities($title));
$findlast = mysql_query("SELECT Date FROM Question WHERE UserID = $username ORDER BY Date DESC LIMIT 1");
if (mysql_num_rows($findlast) > 0) {
$findlastres = mysql_result($findlast,0);
}
else {
    $findlastres = 0;
}
$ipforlog = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
if (strlen($title) > 150) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Question title too long', '$logtime', '$ipforlog')");
echo 'Sorry your title is too long<br/>';
}
elseif (strlen($title) < 5) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Question title too short', '$logtime', '$ipforlog')");
echo 'Sorry your title is too short<br/>';
}
elseif ($count < 5) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Question too short (5 word minimum)', '$logtime', '$ipforlog')");
echo 'Sorry you used less than 10 words<br/>';
}
elseif ($count > 3000) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Question too long (3000 words max)', '$logtime', '$ipforlog')");
echo 'Sorry you cant use more than 3000 words<br/>';
}
elseif (strtotime($logtime) - strtotime($findlastres) < 60) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Question within 1 minutes of last question', '$logtime', '$ipforlog')");
echo 'You can only send a question every 5 minutes<br/>';
}
else {
mysql_query("INSERT INTO Question (ID, UserID, Title, Content, Date, Approved) VALUES ('', '$username', '$title', '$content', '$logtime', '0')");
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Made a question titled $title', '$logtime', '$ipforlog')");
echo 'Question submitted successfully!<br/>';
}
}
?>
<h1>Ask a question!</h1>
<p><?php echo 'We have many members with many different areas of expertise, post your question here and you will be sure to receive a quick answer.'; ?></p>

<form method="post" action="questions.php">
<?php echo 'Title: (max 150 chars)'; ?><br />
<input type="text" name="title" size="46"><br />
<?php echo 'Question'; ?>: (3000 word limit)<br />
<textarea name="content" rows="10" cols="40"></textarea>
<br />
<input type="hidden" name="qsubmit" value="Y">
<?php
if (isset($_SESSION['ID'])) {
?>
<input type="submit" name="submit" value="<?php echo 'Submit Question'; ?>">
<?php
} else {
echo '<a href="http://sourcecodedb.com/register.php">Register to ask a question!</a>';
}
?>
</form>
<br />
<br />
<?php
$sqlquery = mysql_query("SELECT PermissionID FROM User WHERE ID = $username");
if (mysql_num_rows($sqlquery) > 0) {
$sqlresult = mysql_result($sqlquery,0);
}
else {
    $sqlresult = 0;
}
if ($sqlresult >= 5) {
$reqthereq = "SELECT ID, Title, Date, Approved FROM Question";
$resreq = mysql_query($reqthereq);
if (mysql_num_rows($resreq) < 1) {
	echo 'No questions at the moment<br/>';
}
else {
echo '<table><tr><th width="200px;">';
echo 'Title';
echo '</th><th width="250px;">';
echo 'Date';
echo '</th><th width="100px;">';
echo 'Approved';
echo '</th></tr>';
while ($data = mysql_fetch_array($resreq)) {
	echo '<tr><td><a href="viewquestion.php?p=';
	echo $data['ID'];
	echo '">';
	echo $data['Title'];
	echo '</a></td><td>';
	echo $data['Date'];
	echo '</td><td>';
	if ($data['Approved'] == 1) {
	echo 'Yes';
	}
	else {
	echo 'no';
	}
	echo '</td></tr>';
}
echo '</table><br/>';
}
}
else {
$reqthereq = "SELECT ID, Title, Date FROM Question";
$resreq = mysql_query($reqthereq);
if (mysql_num_rows($resreq) < 1) {
	echo 'No questions at the moment<br/>';
}
else {
echo '<table><tr><th width="200px;">';
echo 'Title';
echo '</th><th width="250px;">';
echo 'Date';
echo '</th></tr>';
while ($data = mysql_fetch_array($resreq)) {
	echo '<tr><td><a href="viewquestion.php?p=';
	echo $data['ID'];
	echo '">';
	echo $data['Title'];
	echo '</a></td><td>';
	echo $data['Date'];
	echo '</td></tr>';
}
echo '</table><br/>';
}
}


include_once('footer.php');
?>