<?php
$thisPage="mypages";
include_once('header.php');
if ($_SESSION['Type'] == 'user') {
$username = $_SESSION['Username'];
$id = $_SESSION['ID'];
$sqlquery = mysql_query("SELECT PermissionID FROM User WHERE ID = $id");
$sqlresult = mysql_result($sqlquery,0);
if(isset($_POST['delmsg'])) {
	mysql_query("DELETE FROM Messages WHERE ID IN(".implode(",",mysql_real_escape_string($_POST['selected'])).")"); 
}
if(isset($_POST['strID']))
{
$favdel = "DELETE FROM Bookmarks where UserID='".$id."' AND StringID='".mysql_real_escape_string($_POST['strID'])."'";
mysql_query($favdel) or die(mysql_error());
}
if (isset($_POST['desc']) && $sqlresult >= 5) {
	$desc = mysql_query("SELECT DscModerated FROM User WHERE ID = '".$id."' AND DscModerated = 0");
	if (mysql_num_rows($desc) > 0) {
		mysql_query("UPDATE User SET DscModerated = 1 WHERE ID = '".$id."'");
	}
	else {
		echo 'an error occured';
	}
}
if (isset($_POST['web']) && $sqlresult >= 5) {
	$web = mysql_query("SELECT WebModerated FROM User WHERE ID = '".$id."' AND WebModerated = 0");
	if (mysql_num_rows($web) > 0) {
		mysql_query("UPDATE User SET WebModerated = 1 WHERE ID = '".$id."'");
	}
	else {
		echo 'an error occured';
	}
}
if (isset($_POST['s'])) {
$semail = 0;
$snewsletter = 0;
$sprivate = 0;
$description = "";
$website = "";
$description = mysql_query("SELECT Description FROM User WHERE ID = '".$id."'");
$website = mysql_query("SELECT Website FROM User WHERE ID = '".$id."'");
$description = mysql_real_escape_string(trim(htmlentities($description)));
$website = mysql_real_escape_string(trim(htmlentities($website)));
if ($description == $_POST['description']) {
	$dscmod = 1;
}
else {
	$description = $_POST['description'];
	$dscmod = 0;
}
if ($website == $_POST['website']) {
	$webmod = 1;
}
else {
	$website = $_POST['website'];
	$webmod = 0;
}
if ($sqlresult >= 5) {
	$userrank = mysql_real_escape_string($_POST['userrank']);
	$userrank - trim(htmlentities($userrank));
	mysql_query("UPDATE UserRank SET Title='".$userrank."' WHERE UserID ='".$id."'");
}
if (isset($_POST['email']) && $_POST['email'] == 'Yes') {
	$semail = 1;
}
if (isset($_POST['newsletter']) && $_POST['newsletter'] == 'Yes') {
	$snewsletter = 1;
}
if (isset($_POST['private']) && $_POST['private'] == 'Yes') {
	$sprivate = 1;
}
$snewsletter = mysql_real_escape_string(trim(htmlentities($snewsletter)));
$sprivate = mysql_real_escape_string(trim(htmlentities($sprivate)));
$semail = mysql_real_escape_string(trim(htmlentities($semail)));

mysql_query("UPDATE User SET Newsletter='".$snewsletter."', Private='".$sprivate."', Notifications='".$semail."', Description = '".$description."', DscModerated = '".$dscmod."', Website = '".$website."', WebModerated = '".$webmod."' WHERE ID ='".$id."'");


}


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
$userData = "SELECT Register, Email, Private, Notifications, Newsletter, Description, DscModerated, Website, WebModerated FROM User WHERE ID='".$id."'";
$userData = mysql_query($userData);
while($userInfo = mysql_fetch_array($userData))
{
	$reg = $userInfo['Register'];
	$mail = $userInfo['Email'];
	$private = $userInfo['Private'];
	$notify = $userInfo['Notifications'];
	$newsletter = $userInfo['Newsletter'];
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
	$avatarlink = mysql_result($useravatar, 0);
	if ($avatarlink != NULL) {
	echo '<img src="';
	echo $avatarlink;
	echo '" border="0"> ';
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
echo $username;
?>
</td></tr><tr><td>
<?php echo 'Rank title'; ?>:
</td><td>
<?php
echo $usertitle;
?>

</td></tr><tr><td>
<?php echo 'Join date'; ?>:
</td><td>
<?php
echo $reg;
?>
</td></tr>
<tr><td>
<?php echo 'Email address'; ?>:
</td><td>
<?php
echo $mail;
?>
</td></tr>

<tr><td>
<?php echo 'First name'; ?>:
</td><td>
<?php
echo $_SESSION['Fname'];
?>
</td></tr>

<tr><td>
<?php echo 'Last name'; ?>:
</td><td>
<?php
echo $_SESSION['Lname'];
?>
</td></tr>
</table>
</div>
<div class="userinfo2">
<table>
<tr><td>
<?php echo 'Bookmarks'; ?>:
</td><td>
<?php
echo $k;
?>

<tr><td>
<?php echo 'Public codes'; ?>:
</td><td>
<?php
echo $m;
?>

<tr><td>
<?php echo 'Private codes'; ?>:
</td><td>
<?php
echo $n;
?>

</td></tr>
<tr><td>
<?php echo 'Comments'; ?>:
</td>
<td>
<?php
echo $i;
?>
</td>
</tr>
<?php
$fbdupidcheck = "SELECT * FROM User WHERE ID='$id'";
$fbcheckid = mysql_query($fbdupidcheck);
$fbcheckid2 = mysql_fetch_array($fbcheckid);
if($fbcheckid2['FacebookID'] == 0) {
?>
<tr><td>
<?php echo 'Add facebook'; ?>:
</td>
<td>
<a href="<?php echo $loginUrl2; ?>"><?php echo 'here'; ?></a>
</td>
</tr>
<?php
}
?>
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
		if ($sqlresult >= 5) {
			echo '<form method="POST" action="#"><input type="hidden" name="desc"><input type="submit" value="Approve" name="submit"></form>';
		}
		else {
		echo 'Pending moderation';
		}
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
		if ($sqlresult >= 5) {
			echo '<form method="POST" action="#"><input type="hidden" name="web"><input type="submit" value="Approve" name="submit"></form>';
		}
		else {
		echo 'Pending moderation';
		}
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
	$date = date("F - Y", $date);
	echo '<tr><td>'.$type.' for '.$date.'</td></tr>';
}
echo '</table>';
}

?>
</td>
</tr>
</table>
</div>
<div style="position:relative;top:-220px;left:550px;">
<input type="button" id="pw" class="up_change" value="<?php echo 'Change password'; ?>" onclick="getPasswordForm(this)"/>
<input type="button" id="mail" class="up_change" value="<?php echo 'Change email'; ?>" onclick="getEmailForm(this)" />
<div id="changing"></div>
<?php
$messages5 = mysql_query("SELECT * FROM Messages WHERE UserRec = $id AND Viewed=0");
$numbermessages = mysql_num_rows($messages5);
?>
<div id="change"></div>
</div>
<br>
<ul class="tabs" a name="b">
<li><a href="#" onClick="blur()"><?php
	echo 'Messages';
	echo ' (';
	echo $numbermessages;
	echo ')';
	?>
	</a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Bookmarks'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Public codes'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Private codes'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Comments'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Settings'; ?></a></li>
	
</ul>

<!-- tab "panes" -->
<div class="panes">
	<div>
	<table class="margin" cellspacing="0px" width="800px">
	<?php
	$messages = "SELECT * FROM Messages WHERE UserRec = $id ORDER BY ID DESC LIMIT 50";
	$messages2 = mysql_query($messages);
	?>
	<tr>
	<td width='300px'><?php echo 'Message'; ?></td>
	<td align="right" colspan="4" width="200px"><?php echo 'Date'; ?></td>
	</tr>
	<form method="post" action="#">
	<input type="hidden" name="delmsg" value="Y">
	<?php
	$i = "#FFFFFF";
	while ($message=mysql_fetch_array($messages2))
	{
		$findsenderid = $message['UserSent'];
		$getsender = mysql_query("SELECT Username FROM User WHERE ID = $findsenderid");
		$thesender = mysql_result($getsender,0);
		echo "<tr bgcolor='".$i."'><td width='300px' colspan='2'><input type='checkbox' name='selected[]' id='selected' value='".$message['ID']."'><a href='showmessage.php?p=".$message['ID']."'>".$message['Title']."</a> - <a href='http://sourcecodedb.com/".$thesender.".htm'>".$thesender."</a></td><td align='right' width='180px'>".$message['Date']."</td></tr>";
		if($i == "#F2F2F2")
		$i = "#FFFFFF";
		else
		$i = "#F2F2F2";
	}
	?>
	<tr><td>
	<?php if (mysql_num_rows($messages2) > 0) { ?>
	<input type="submit" name="submit" value="Delete">
	<?php } ?>
	</form>
	</td></tr>
	</table>
	</div>
	<div>
	<table class="margin" cellspacing="0px" width="800px">
	<?php
	$savedCode="SELECT Title, Language, CodeInfo.StringID, CodeInfo.Url FROM Bookmarks, CodeInfo, Language WHERE Bookmarks.UserID='".$id."' AND Bookmarks.StringID=CodeInfo.StringID AND Language.ID=CodeInfo.LanguageID GROUP BY Title";
	$savedCode=mysql_query($savedCode);
	?>
	<tr>
	<td width='300px'><?php echo 'Code'; ?></td>
	<td align="right" colspan="4" width="200px"><?php echo 'Language'; ?></td>
	</tr>
	
	<?php
	$i = "#FFFFFF";
	while($savedCodes = mysql_fetch_array($savedCode))
	{
		$title = $savedCodes['Title'];
		$url = $savedCodes['Url'];
		echo "<tr bgcolor='".$i."'><td width='200px' colspan='2'><a href='/".$url.".html'>".$savedCodes['Title']."</a>
		</td><td align='right' width='180px'>".$savedCodes['Language']."</td>";
		echo "<td width='20px' colspan='2'><a href='' onclick='deleteBookmark(".$savedCodes['StringID'].")'><img src='../images/Bookmark-del-small.png' Alt='Delete bookmark' border='0' width='16' Height='16'/></a></tr>";
		if($i == "#F2F2F2")
		$i = "#FFFFFF";
		else
		$i = "#F2F2F2";
	}
	?>
	</table>
	</div>
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
	<?php
	$Mycode = "SELECT Title, UserID, Date, StringID, Language, Published, Url FROM CodeInfo, Language
	WHERE Language.ID=CodeInfo.LanguageID AND Published=0
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
	$Comment = "SELECT Comments.ID, CodeInfo.UserID, Content, CodeInfo.Url, CodeInfo.StringID,  count(title) , Max(DATE(Comments.Date)) as date, Title
 	FROM Comments, CodeInfo
	WHERE Comments.UserID =".$id."
	AND CodeInfo.StringID =Comments.StringID
Group By Title";
	$Comment = mysql_query($Comment);
	while($Comments = mysql_fetch_array($Comment))
	{
	$title = $Comments['Title'];
	$title = $Comments['Url'];
	echo "<tr bgcolor='".$i."'><td width='200px' colspan='2'><a href='/".$title.".html'>".$Comments['Title']."</a>
	</td><td align='right' width='150px'>".$Comments['date']."</td></tr>";
	if($i == "#F2F2F2")
	$i = "#FFFFFF";
	else
	$i = "#F2F2F2";
	}
?>
</table>
	</div>
	<div>
	<form action="#" method="post">
	<table class="margin" cellspacing="0px" width="800px">
	<tr>
	<td width='300px'><b><?php echo 'Settings'; ?></b></td>
	<td align="right" width="100px" height="30px"></td>
	<td align="right" width="400px" height="30px"></td>
	</tr>
	<tr bgcolor="#FFFFFF"><td height="30px">Receive Notifications via email</td><td><input type="checkbox" name="email" value="Yes" <?php if ($notify == '1') { echo 'checked="checked"'; } ?>></td>
	<td>If someone sends you a private message, comments on your code, or replies to one of your comments we will send you an email.</td></tr>
	<tr bgcolor="#F2F2F2"><td height="30px">Receive the SCDB Newsletter</td><td><input type="checkbox" name="newsletter" value="Yes" <?php if ($newsletter == '1') { echo 'checked="checked"'; } ?>></td>
	<td>Sometimes we send out newsletters to our members when we have useful information or updates.</td></tr>
	<tr bgcolor="#FFFFFF"><td height="30px">Private profile?</td><td><input type="checkbox" name="private" value="Yes" <?php if ($private == '1') { echo 'checked="checked"'; } ?>></td>
	<td>This hides your first and last name from your public profile.</td></tr>
	<tr bgcolor="#F2F2F2"><td height="30px">Description</td><td><textarea name="description"><?php echo mysql_result(mysql_query("SELECT Description FROM User WHERE ID = '".$id."'"), 0); ?></textarea></td>
	<td>A description about you.</td></tr>
	<tr bgcolor="#FFFFFF"><td height="30px">Website</td><td><input type="text" name="website" value="<?php echo mysql_result(mysql_query('SELECT Website FROM User WHERE ID = '.$id), 0); ?>"></td>
	<td>Your website URL (For example http://sourcecodedb.com).</td></tr>
	<?php 
	if ($sqlresult >= 5) { 
	?>
	<tr bgcolor="#F2F2F2"><td height="30px">Custom rank</td><td><input type="text" name="userrank" value="<?php echo mysql_result(mysql_query('SELECT Title FROM UserRank WHERE UserID = '.$id), 0); ?>"></td>
	<td>Change your rank title, admins only.</td></tr>
	<?php
	}
	?>
	<tr bgcolor="#F2F2F2"><td height="30px"></td><td><input type="hidden" name="s" value="y"><input type="submit" name="submit" value="Save"></td></tr>
</table>
</form>
	</div>

<?php
}
else
{
	echo 'Registration required!';
}
?>
</div>
<?php
include_once('footer.php');
?>

