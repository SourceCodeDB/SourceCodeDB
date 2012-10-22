<?php
include_once('header.php');
if(isset($_SESSION['ID'])) {
$username = $_SESSION['ID'];
$sqlquery = mysql_query("SELECT PermissionID FROM User WHERE ID = $username");
$sqlresult = mysql_result($sqlquery,0);
if ($sqlresult >= 5) {
if (isset($_POST['remove']) || isset($_POST['ban'])) {
if ($_POST['remove'] == 'nouser') {
	$from = mysql_real_escape_string($_POST['from']);
	if ($from == 'all') {
		mysql_query("DELETE Avatar FROM Avatar LEFT OUTER JOIN User AS usr ON usr.ID = Avatar.UserID WHERE usr.ID IS NULL");
		mysql_query("DELETE Bookmarks FROM Bookmarks LEFT OUTER JOIN User AS usr ON usr.ID = Bookmarks.UserID WHERE usr.ID IS NULL");
		mysql_query("DELETE CodeInfo FROM CodeInfo LEFT OUTER JOIN User AS usr ON usr.ID = CodeInfo.UserID WHERE usr.ID IS NULL");
		mysql_query("DELETE Comments FROM Comments LEFT OUTER JOIN User AS usr ON usr.ID = Comments.UserID WHERE usr.ID IS NULL");
		mysql_query("DELETE Rating FROM Rating LEFT OUTER JOIN User AS usr ON usr.ID = Rating.UserID WHERE usr.ID IS NULL");
		mysql_query("DELETE Request FROM Request LEFT OUTER JOIN User AS usr ON usr.ID = Request.UserID WHERE usr.ID IS NULL");
		mysql_query("DELETE News FROM News LEFT OUTER JOIN User AS usr ON usr.ID = News.UserID WHERE usr.ID IS NULL");
		mysql_query("DELETE Ranked FROM Ranked LEFT OUTER JOIN User AS usr ON usr.ID = Ranked.UserID WHERE usr.ID IS NULL");
		$ipforlog = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d"); 
		$time = date("G:i:s"); 
		$logtime = $date.' '.$time;
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Removed Everything with no matching userid', '$logtime', '$ipforlog')");
		
echo 'Success';	
	}
	else {
		mysql_query("DELETE $from FROM $from LEFT OUTER JOIN User AS usr ON usr.ID = $from.UserID WHERE usr.ID IS NULL");
		$ipforlog = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d"); 
		$time = date("G:i:s"); 
		$logtime = $date.' '.$time;
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Removed $from with no matching userid', '$logtime', '$ipforlog')");
		echo 'Success';	
	}
}
elseif ($_POST['remove'] == 'deluser') {
	$userpost = mysql_real_escape_string($_POST['user']);
	$sqlreq8 = mysql_query("SELECT ID FROM User WHERE Username = '$userpost'");
	$sqlres8 = mysql_result($sqlreq8,0);
	if ($sqlres8 != NULL) {
		mysql_query("DELETE Avatar FROM Avatar WHERE UserID = $sqlres8");
		mysql_query("DELETE Bookmarks FROM Bookmarks WHERE UserID = $sqlres8");
		mysql_query("DELETE CodeInfo FROM CodeInfo WHERE UserID = $sqlres8");
		mysql_query("DELETE Comments FROM Comments WHERE UserID = $sqlres8");
		mysql_query("DELETE Rating FROM Rating WHERE UserID = $sqlres8");
		mysql_query("DELETE Request FROM Request WHERE UserID = $sqlres8");
		mysql_query("DELETE News FROM News WHERE UserID = $sqlres8");
		mysql_query("DELETE User FROM User WHERE ID = $sqlres8");
		$ipforlog = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d"); 
		$time = date("G:i:s"); 
		$logtime = $date.' '.$time;
		mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Deleted user $userpost', '$logtime', '$ipforlog')");
		
echo 'Success';
}
else {
	$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Failed to delete user $userpost', '$logtime', '$ipforlog')");
	
	echo 'Could not find a username matching your query';
	
}
	
	
}
if ($_POST['ban'] == 'Y') {
$banusername = mysql_real_escape_string($_POST['username']);
$banusername = trim(htmlentities($banusername));
$finduid = mysql_result(mysql_query("SELECT ID FROM User WHERE Username = '$banusername'"),0);
if ($finduid == NULL) {
	echo 'Something went wrong';
}
else {
	header("Location: http://sourcecodedb.com/ban.php?p=".$finduid);
}

}
}


$Comment = "SELECT UserID FROM Comments";
$Comment = mysql_query($Comment);
$i=0;
while($Comments = mysql_fetch_array($Comment))
{
	$i++;
}
$Bookmarks = "SELECT UserID FROM Bookmarks";
$Bookmarks = mysql_query($Bookmarks);
$k=0;
while($Bookmark = mysql_fetch_array($Bookmarks))
{
	$k++;
}
$myCode = "SELECT UserID, Published FROM CodeInfo";
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
$userData = "SELECT Register, Email FROM User";
$userData = mysql_query($userData);
while($userInfo = mysql_fetch_array($userData))
{
	$reg = $userInfo['Register'];
	$mail = $userInfo['Email'];
}
?>
<br />
<h4>
<?php echo 'Site Statistics'; ?></h4>
<div style="position:relative;width:200px;">
<table>
<tr><td>
<?php echo 'Bookmarks'?>:
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
</table>
</div>
<div style="position:relative;width:400px;">
<form method="post" action="admin.php">
<input type="hidden" name="remove" value="nouser">
<p><?php echo 'Remove all'; ?> <select name="from">
<option value="Bookmarks"><?php echo 'Bookmarks'; ?></option>
<option value="Avatar"><?php echo 'Avatars'; ?></option>
<option value="Comments"><?php echo 'Comments'; ?></option>
<option value="CodeInfo"><?php echo 'Codes'; ?></option>
<option value="Rating"><?php echo 'Ratings'; ?></option>
<option value="News"><?php echo 'News'; ?></option>
<option value="Ranked"><?php echo 'Rankings'; ?></option>
<option value="all"><?php echo 'Everything'; ?></option>
</select> <?php echo 'that belongs to no user '; ?>
<input type="submit" name="submit" value="Submit"></p>
</form>
<br />
<script>
function suggest(inputString){
        if(inputString.length == 0) {
            $('#suggestions').fadeOut();
        } else {
        $('#user').addClass('load');
            $.post("functions/suggestuser.php", { user: ""+inputString+""}, function(data){
                if(data.length >0) {
                    $('#suggestions').fadeIn();
                    $('#suggestionsList').html(data);
                    $('#user').removeClass('load');
                }
            });
        }
    }
 
function fill(thisValue) {
    $('#user').val(thisValue);
    setTimeout("$('#suggestions').fadeOut();", 200);
}
function suggest2(inputString){
        if(inputString.length == 0) {
            $('#suggestions2').fadeOut();
        } else {
        $('#username').addClass('load');
            $.post("functions/suggestuser2.php", { user: ""+inputString+""}, function(data){
                if(data.length >0) {
                    $('#suggestions2').fadeIn();
                    $('#suggestionsList2').html(data);
                    $('#username').removeClass('load');
                }
            });
        }
    }
 
function fill2(thisValue) {
    $('#username').val(thisValue);
    setTimeout("$('#suggestions2').fadeOut();", 200);
}
</script>
<form method="post" action="admin.php">
<input type="hidden" name="remove" value="deluser">
Delete user <input type="text" name="user" id="user" onkeyup="suggest(this.value);"><div id="suggestions" class="suggestionsBox2" style="display: none;">
<img style="position: relative; top: -12px; left: 30px;" src="images/arrow.png" alt="upArrow" />
<div id="suggestionsList" class="suggestionList"></div></div><input type="submit" name="submit" value="Submit">
</form>
</div>
<div id="change"></div>
<br>
<form method="post" action="admin.php">
<input type="hidden" name="ban" value="Y">
Ban user <input type="text" name="username" id="username" onkeyup="suggest2(this.value);"><div id="suggestions2" class="suggestionsBox4" style="display: none;">
<img style="position: relative; top: -12px; left: 30px;" src="images/arrow.png" alt="upArrow" />
<div id="suggestionsList2" class="suggestionList2"></div></div><input type="submit" name="submit" value="Submit">
</form>
</div>
<div id="change"></div>
<br>
<ul class="tabs" a name="b">
	<li><a href="#" onClick="blur()"><?php echo 'Site log'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Public codes'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Private codes'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Comments'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Moderation'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Warn'; ?></a></li>
	<li><a href="#" onClick="blur()"><?php echo 'Bans'; ?></a></li>
</ul>

<!-- tab "panes" -->
<div class="panes" width="700px">
	<div>
	<table class="margin" cellspacing="0px" width="800px">
	<?php
	$log="SELECT * FROM Log ORDER BY ID DESC LIMIT 50";
	$logquery=mysql_query($log);
	?>
	<tr>
	<td width='300px'><?php echo 'Action'; ?></td>
	<td align="right" colspan="4" width="200px"><?php echo 'Time'; ?></td>
	</tr>
	
	<?php
	$i = "#FFFFFF";
	while($logresult = mysql_fetch_array($logquery))
	{
		echo "<tr bgcolor='".$i."'><td width='300px' colspan='2'>";
		if ($logresult['UserID'] == 0) {
		echo $logresult['Action']."<br />";
		}
		else {
		$useridlog = $logresult['UserID'];
		$usernamelog = mysql_result(mysql_query("SELECT Username FROM User WHERE ID = $useridlog"),0);
		echo "<a href=".$usernamelog.".htm>".$logresult['Action']."</a><br />";
		}
		echo "IP: ".$logresult['IP'];
		echo "</td><td align='right' width='180px'>".$logresult['Time']."<br />";
		echo "User ID: ".$logresult['UserID']."</td>";
		echo "</tr>";
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
	$Mycode = "SELECT Title, UserID, Date, StringID, Language, Published, Url FROM CodeInfo, Language WHERE Language.ID=CodeInfo.LanguageID AND Published=1 AND IsTemp!='1'";
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
	$Mycode = "SELECT Title, UserID, Date, StringID, Language, Published, Url FROM CodeInfo, Language WHERE Language.ID=CodeInfo.LanguageID AND Published=0 AND IsTemp!='1'";
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
	$Comment = "SELECT Comments.ID, CodeInfo.UserID, Content, CodeInfo.StringID, CodeInfo.Url, count(title) , Max(DATE(Comments.Date)) as date, Title FROM Comments, CodeInfo WHERE CodeInfo.StringID = Comments.StringID  AND IsTemp!='1' Group By Title";
	$Comment = mysql_query($Comment);
	while($Comments = mysql_fetch_array($Comment))
	{
	$title = $Comments['Url'];
	echo "<tr bgcolor='".$i."'><td width='150px' colspan='2'><a href='/".$title.".html'>".$Comments['Title']."</a> (".$Comments['count(title)'].")
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
	<table class="margin" cellspacing="0px" width="800px">
	<tr>
	<td width='300px'><?php echo 'Needs to be moderated'; ?></td>
	<td align="right" colspan="3" width="00px"><?php echo 'User'; ?></td>
	</tr>
	<?php
	$i = "#FFFFFF";
	$Moderated = "SELECT UserID, StringID, Title, Url FROM CodeInfo WHERE Moderated = 0 AND IsTemp!='1'";
	$Moderated = mysql_query($Moderated);
	while($tomod = mysql_fetch_array($Moderated))
	{
	$title = $tomod['Url'];
	echo "<tr bgcolor='".$i."'><td width='150px' colspan='2'><a href='/".$title.".html'>".$tomod['Title']."</a>
	</td><td align='right' width='150px'>".$tomod['UserID']."</td></tr>";
	if($i == "#F2F2F2")
	$i = "#FFFFFF";
	else
	$i = "#F2F2F2";
	}
	
	$Moderated2 = "SELECT UserID, StringID FROM Comments WHERE Moderated = 0";
	$Moderated2 = mysql_query($Moderated2);
	while($tomod2 = mysql_fetch_array($Moderated2))
	{
	$stringcom = $tomod2['StringID'];
	$finduser = mysql_query("SELECT Url FROM CodeInfo Where StringID = '$stringcom' AND IsTemp!='1'");
	$title = mysql_result($finduser,0);
	echo "<tr bgcolor='".$i."'><td width='150px' colspan='2'><a href='/".$title.".html'>".mysql_result($finduser,0)."</a>
	</td><td align='right' width='150px'>".$tomod2['UserID']."</td></tr>";
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
	<td width='300px'><?php echo 'Warnings'; ?></td>
	<td align="right" colspan="3" width="00px"><?php echo 'User'; ?></td>
	</tr>
	<?php
	$i = "#FFFFFF";
	$warns = "SELECT UserID, Reason, Location FROM Warn";
	$warns = mysql_query($warns);
	while($warn = mysql_fetch_array($warns))
	{
	echo "<tr bgcolor='".$i."'><td width='150px' colspan='2'><a href='".$warn['Location']."'>".$warn['Reason']."</a>
	</td><td align='right' width='150px'>".$warn['UserID']."</td></tr>";
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
	<td width='300px'><?php echo 'Bans'; ?></td>
	<td align="right" colspan="3" width="00px"><?php echo 'User'; ?></td>
	</tr>
	<?php
	$i = "#FFFFFF";
	$bans = "SELECT UserID, Reason, Location FROM Ban";
	$bans = mysql_query($bans);
	while($ban = mysql_fetch_array($bans))
	{
	echo "<tr bgcolor='".$i."'><td width='150px' colspan='2'><a href='".$ban['Location']."'>".$ban['Reason']."</a>
	</td><td align='right' width='150px'>".$ban['UserID']."</td></tr>";
	if($i == "#F2F2F2")
	$i = "#FFFFFF";
	else
	$i = "#F2F2F2";
	}
	
?>
</table>
	</div>
</div>

<?php
}
else {
$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if ($_SESSION['ID']) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid - '0';
	}
	$realip=$_SERVER['HTTP_CLIENT_IP'];
	$realip2=$_SERVER['HTTP_X_FORWARDED_FOR'];
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Illegal attempt admin.php', '$logtime', '$ipforlog')");
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Real IP #1: $realip', '$logtime', '$ipforlog')");
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Real IP #2: $realip2', '$logtime', '$ipforlog')");

echo 'Sorry you need to be an admin to access this page';
}
}
else
{
$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if ($_SESSION['ID']) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid - '0';
	}
	$realip=$_SERVER['HTTP_CLIENT_IP'];
	$realip2=$_SERVER['HTTP_X_FORWARDED_FOR'];
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Illegal attempt admin.php', '$logtime', '$ipforlog')");
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Real IP #1: $realip', '$logtime', '$ipforlog')");
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Real IP #2: $realip2', '$logtime', '$ipforlog')");
	
	echo 'Registration required!';
	
}
?>

<?php
include_once('footer.php');
?>