<?php
include_once('header.php');
if (isset($_GET['user']) && $_GET['user'] != "") {
$getuser=$_GET['user'];
$getuser=str_replace("/", "", $getuser);
$getuser = trim(htmlentities(addslashes($getuser)));
$finduserid = mysql_query("SELECT ID FROM User WHERE Username='$getuser'");
if (mysql_num_rows($finduserid) == 1) {
$id = mysql_result($finduserid,0);
if (isset($_SESSION['ID'])) {
$username = $_SESSION['ID'];
}
else {
    $username = 0;
}
if ($username == $id ) {
	?>
	<script>
    <!--
    window.location= "http://sourcecodedb.com/mypages.php"
    //-->
    </script>
    <?php
}
$bannedquery = mysql_query("SELECT UserID FROM Ban WHERE UserID = '".$id."'");
if (mysql_num_rows($bannedquery) > 0) {
$banned = mysql_result($bannedquery,0);
}
if (!isset($banned)) {
$Comment = "SELECT UserID FROM Comments WHERE UserID='".$id."'";
$Comment = mysql_query($Comment);
$i=0;
while($Comments = mysql_fetch_array($Comment))
{
	$i++;
}
$Bookmarks = "SELECT UserID FROM Bookmarks WHERE UserID='".$id."'";
$Bookmarks = mysql_query($Bookmarks);
$k=0;
while($Bookmark = mysql_fetch_array($Bookmarks))
{
	$k++;
}
$myCode = "SELECT UserID, Published FROM CodeInfo WHERE UserID='".$id."'";
$myCode = mysql_query($myCode);
$m=0;
$n=0;
while($myCodes = mysql_fetch_array($myCode))
{
	if($myCodes['Published']==1)
	$m++;
	else
	$n++;
}
$userData = "SELECT Fname, Lname, Username, Register, Email, Private, Description, DscModerated, Website, WebModerated FROM User WHERE ID='".$id."'";
$userData = mysql_query($userData);
while($userInfo = mysql_fetch_array($userData))
{
	$reg = $userInfo['Register'];
	$mail = $userInfo['Email'];
	$username2 = $userInfo['Username'];
	$firstname = $userInfo['Lname'];
	$lastname = $userInfo['Fname'];
	$private = $userInfo['Private'];
	$description = $userInfo['Description'];
	$dscmod = $userInfo['DscModerated'];
	$website = $userInfo['Website'];
	$webmod = $userInfo['WebModerated'];
}
$userRank = mysql_query("SELECT Title FROM UserRank WHERE UserID='$id'");
while($userrank = mysql_fetch_array($userRank)) {
	$usertitle = $userrank['Title'];
}
$findavatar = "SELECT Location FROM Avatar WHERE UserID = $id";
	$useravatar = mysql_query($findavatar);
        if (mysql_num_rows($useravatar) > 0) {
	$avatarlink = mysql_result($useravatar, 0);
	if ($avatarlink != NULL) {
	echo '<img src="';
	echo $avatarlink;
	echo '" border="0"> ';
	}
        }
	else {
	?>
<img src="stylesheets/images/avatar.png" border="1px #000"/>
<?php
}
?><br />
<div class="userinfo1">
<table>
<tr><td width="90px">
<?php echo 'Username'; ?>:
</td>
<td>
<?php
if (isset($username2)) {
echo $username2;
}
?>

</td></tr>
<tr><td>
<?php echo 'Rank title'; ?>:
</td><td>
<?php
if (isset($usertitle)) {
echo $usertitle;
}
?>
</td></tr>
<tr><td>
<?php echo 'Join date'; ?>:
</td><td>
<?php
if (isset($reg)) {
echo $reg;
}
?>
</td></tr>
<?php
if (isset($private)) {
if ($private != '1') {
?>
<tr><td>
<?php echo 'First name'; ?>:
</td><td>
<?php
if (isset($firstname)) {
echo $firstname;
}
?>
</td></tr>

<tr><td>
<?php echo 'Last name'; ?>:
</td><td>
<?php
if (isset($lastname)) {
echo $lastname;
}
?>
</td></tr>
<?php
} 
}
?>
</table>
</div>
<div class="userinfo2">
<table>
<tr><td>
<?php echo 'Bookmarks'; ?>:
</td><td>
<?php
if (isset($k)) {
echo $k;
}
?>

<tr><td>
Public codes:
</td><td>
<?php
if (isset($m)) {
echo $m;
}
?>

<tr><td>
<?php echo 'Private codes'; ?>:
</td><td>
<?php
if (isset($n)) {
echo $n;
}
?>

</td></tr>
<tr><td>
<?php echo 'Comments'; ?>:
</td>
<td>
<?php
if (isset($i)) {
echo $i;
}
?>
</td>
</tr>
<tr><td>
<?php echo 'Send Message'; ?>:
</td>
<td>
<?php
if (isset($_SESSION['ID'])) {
if ($id != $_SESSION['ID'] && $_SESSION['ID']) {
	echo ' <a href="sendmessage.php?p=';
	echo $id;
	echo '">Send a message <img src="images/email_icon.gif" border="0"></a>';
	}
}
	?>
</td>
</tr>
</table>
</div>
<div class="userinfo3">
<table>
<tr>
<td width="150px">Description</td>
<td width="250px"><?php
if ($description != null && $description != "") {
	if ($dscmod == 1) {
		echo $description;
	}
	else {
		echo 'Pending moderation';
	}
}
?>
</td></tr>
<tr>
<td>Website</td>
<td>
<?php
if ($website != null && $website != "") {
	if ($webmod == 1) {
		echo '<a href="'.$website.'">'.$website.'</a>';
	}
	else {
		echo 'Pending moderation';
	}
}
?>
</td>
</tr>
<tr>
<td>Medals</td>
<td>
<?php
$medals = mysql_query("SELECT * FROM Medals WHERE UserID = '".$id."'");
if (mysql_num_rows($medals) > 0) {
echo '<table>';
while ($datamed = mysql_fetch_array($medals)) {
	$type = $datamed['Type'];
	$date = $datamed['Date'];
	$date = strtotime('-1 month', strtotime($date));
	$date = date("F - Y", strtotime($date));
	echo '<tr><td>'.$type.' for '.$date.'</td></tr>';
}
echo '</table>';
}

?>
</td>
</tr>
</table>
</div>
<br>
<ul class="tabs" a name="b">
	<li><a href="#" onClick="blur()"><?php echo 'Public codes'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Comments'; ?></a></li>
</ul>

<!-- tab "panes" -->
<div class="panes">
	<div>
	<?php
	$Mycode = "SELECT Title, UserID, Date, StringID, Language, Published, Url FROM CodeInfo, Language
	WHERE Language.ID=CodeInfo.LanguageID AND Published=1
	And CodeInfo.UserID='".$id."'";
	$Mycode = mysql_query($Mycode);
	?>
	<table class="margin" cellspacing="0px" width="800px">
	<tr>
	<td width='200px'><?php echo 'Code'; ?></td>
	<td align="right" colspan="3" width="100px"><?php echo 'Language'; ?></td>
	</tr>
	<?php
	$i = "#FFFFFF";
	while($Mycodes = mysql_fetch_array($Mycode))
	{
		$title = $Mycodes['Title'];
		$title = $Mycodes['Url'];
		echo "<tr bgcolor='".$i."'><td width='200px' colspan='2'><a href='/".$title.".html'>".$Mycodes['Title']."</a>
		</td>";
		echo "<td align='right' width='100px'>".$Mycodes['Language']."</td></tr>";
		if($i == "#F2F2F2")
		$i = "#FFFFFF";
		else
		$i = "#F2F2F2";
	}
	?>
	</table>
	</div>
	<div>
	<table class="margin" cellspacing="0px" width="800px">
	<tr>
	<td width='300px'><?php echo 'Comment section'; ?></td>
	<td align="right" colspan="3" width="00px"><?php echo 'Time'; ?></td>
	</tr>
	<?php
	$i = "#FFFFFF";
	$Comment = "SELECT Comments.ID, CodeInfo.UserID, Content, Url, CodeInfo.StringID,  count(title) , Max(DATE(Comments.Date)) as date, Title
 	FROM Comments, CodeInfo
	WHERE Comments.UserID =".$id."
	AND CodeInfo.StringID =Comments.StringID
Group By Title";
	$Comment = mysql_query($Comment);
        if (mysql_num_rows($Comment) > 0) {
	while($Comments = mysql_fetch_array($Comment))
	{
		$title = $Comments['Title'];
		$title = $Comments['Url'];
	echo "<tr bgcolor='".$i."'><td width='150px' colspan='2'><a href='/".$title.".html'>".$Comments['Title']."</a> (".$Comments['count(title)'].")
	</td><td align='right' width='150px'>".$Comments['date']."</td></tr>";
	if($i == "#F2F2F2")
	$i = "#FFFFFF";
	else
	$i = "#F2F2F2";
	}
        }
}
else {
echo 'This user has been banned';
}
}
else {
echo 'This user could not be found';
}
}
else {
echo 'This user could not be found';
}
?>
</table>
	</div>
</div>
<?php
include_once('footer.php');
?>