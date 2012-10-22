<?php
include_once('header.php');
if ($_SESSION['Type'] == 'user') {
$username = $_SESSION['ID'];
if (isset($_POST['reqsubmit'])) {
$title = mysql_real_escape_string($_POST['title']);
$title = trim(htmlentities($title));
$content = mysql_real_escape_string($_POST['content']);
$content = trim(htmlentities($content));
$countwords = explode(" ",$content);
$count = 0;
while ($countwords) {
	$count++;
}
$content = trim(htmlentities($content));
$title = trim(htmlentities($title));
$findlast = mysql_query("SELECT SubmitDate FROM Request WHERE UserID = $username ORDER BY SubmitDate DESC LIMIT 1");
$findlastres = mysql_result($findlast,0);
$ipforlog = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
if (strlen($title) > 50) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Request title too long', '$logtime', '$ipforlog')");
echo 'Sorry your title is too long';
}
elseif (strlen($title) < 5) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Request title too short', '$logtime', '$ipforlog')");
echo 'Sorry your title is too short';
}
elseif ($count < 50) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Request too short (50 words)', '$logtime', '$ipforlog')");
echo 'Sorry you used less than 50 words';
}
elseif ($count > 3000) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Request too long (3000 words)', '$logtime', '$ipforlog')");
echo 'Sorry you cant use more than 3000 words';
}
elseif (strtotime($logtime) - strtotime($findlastres) < 1800) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Request within 15 minutes of last request', '$logtime', '$ipforlog')");
echo 'You can only send a request every 15 minutes';
}
else {
mysql_query("INSERT INTO Request (ID, UserID, Title, Content, SubmitDate, Approved) VALUES ('', '$username', '$title', '$content', '$logtime', '0')");
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Made a request titled $title', '$logtime', '$ipforlog')");
echo 'Request Submitted Successfully, we will now review and hopefully approve your request.';
}
}
?>
<p><?php echo 'Have an idea or want to see an example of a full project or just a simple piece of code? Submit a request here and we will do our best to upload a sample here at SourceCodeDB.com'; ?></p>
<p><?php echo 'For a request to be approved it must follow some guidelines.'; ?></p>
<ul>
<li><?php echo 'Do research to see if there are other examples already out there'; ?></li>
<li><?php echo 'Why should someone make this, how is it useful?'; ?></li>
<li><?php echo 'Give some details on how it could be used'; ?></li>
<li><?php echo 'Could it be based of of something that already exists?'; ?></li>
</ul>
<p><?php echo 'Your request should be around 200 to 500 words and be fairly thought out, we want to make this a useful brainstorming tool for developers.'; ?></p>
<br />
<form method="post" action="requests.php">
<?php echo 'Title: (50 char maximum)'; ?><br />
<input type="text" name="title"><br />
<?php echo 'Request'; ?>:<br />
<textarea name="content" rows="20" cols="90"></textarea>
<br />
<input type="hidden" name="reqsubmit" value="Y">
<input type="submit" name="submit" value="<?php echo 'Submit Request'; ?>">
</form>
<br />
<br />
<?php
$sqlquery = mysql_query("SELECT PermissionID FROM User WHERE ID = $username");
$sqlresult = mysql_result($sqlquery,0);
if ($sqlresult >= 5) {
$reqthereq = "SELECT ID, Title, SubmitDate, Approved FROM Request";
$resreq = mysql_query($reqthereq);
echo '<table><tr><th width="200px;">';
echo 'Title';
echo '</th><th width="250px;">';
echo 'Date';
echo '</th><th width="100px;">';
echo 'Approved';
echo '</th></tr>';
while ($data = mysql_fetch_array($resreq)) {
	echo '<tr><td><a href="viewrequest.php?p=';
	echo $data['ID'];
	echo '">';
	echo $data['Title'];
	echo '</a></td><td>';
	echo $data['SubmitDate'];
	echo '</td><td>';
	if ($data['Approved'] == 1) {
	echo 'Yes';
	}
	else {
	echo 'no';
	}
	echo '</td></tr>';
}
echo '</table>';
}
else {
$reqthereq = "SELECT ID, Title, SubmitDate FROM Request WHERE Approved = '1'";
$resreq = mysql_query($reqthereq);
echo '<table><tr><th width="200px;">';
echo 'Title';
echo '</th><th width="250px;">';
echo 'Date';
echo '</th></tr>';
while ($data = mysql_fetch_array($resreq)) {
	echo '<tr><td><a href="viewrequest.php?p=';
	echo $data['ID'];
	echo '">';
	echo $data['Title'];
	echo '</a></td><td>';
	echo $data['SubmitDate'];
	echo '</td></tr>';
}
echo '</table>';
}
}
else {
echo 'Sorry you must be logged in to see and use the request features.';
}
include_once('footer.php');
?>