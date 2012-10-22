<?php
include_once('header.php');
$id = $_GET['p'];
$username = $_SESSION['Username'];
$idofuser = $_SESSION['ID'];
if ($_SESSION['Type'] == 'user') {
include_once('functions/db.php');
$table = "Language";
$table2 = "Difficulty"; 
$table3 = "Category"; 
$table4 = "CodeContent";
$table5 = "CodeInfo";
$sql = "SELECT * FROM $table";
$sql2 = "SELECT * FROM $table2";
$sql3 = "SELECT * FROM $table3";
$res = mysql_query($sql);
$res2 = mysql_query($sql2);
$res3 = mysql_query($sql3);
$userverify = "Select UserID from CodeInfo where StringID = '$id'";
$userid = mysql_result(mysql_query($userverify),0) or die (mysql_error());
$findcodeinfo = mysql_query("SELECT * FROM $table4 WHERE StringID = $id");
$findcodeinfo2 = mysql_query("SELECT * FROM $table5 WHERE StringID = $id");
$checkadmin = "SELECT PermissionID FROM User WHERE ID = $idofuser";
$useradmin = mysql_result(mysql_query($checkadmin),0) or die (mysql_error());
if ($_SESSION['ID'] == $userid || $useradmin >= 5) {
$code=1;
while ($thecodeinfo = mysql_fetch_array($findcodeinfo)) {
?>
<form action="updatecode.php" method="post">
<input type="hidden" name="username" value="<?php
echo $username;
?>" />
<?php echo 'Title'; ?><br>
<input type="text" size="30" name="title" value="<?php
echo $thecodeinfo['Title'];
?>"><br>
<?php echo 'Description'; ?><br>
<textarea name="desc" rows="10" cols="85"><?php
echo $thecodeinfo['Description'];
?></textarea><br>
<?php echo 'Edit your code'; ?><br>
<textarea name="code" id="code<?php echo $code; ?>" rows="30" cols="85"><?php
echo htmlentities($thecodeinfo['Content']);
?></textarea><br>

<?php
$thecodeinfo2 = mysql_fetch_array($findcodeinfo2);
echo 'Source';
echo ': <input type="text" name="source" value="';
echo $thecodeinfo['Source'];
echo '"><p>';
echo 'Leave blank if code is yours or there is no source but if you have taken this code from another website please tell us your source so that credit can be given.';
echo '</p>';
echo 'Tags';
echo ': <input type="text" name="tags" value="';
echo $thecodeinfo2['Tags'];;
echo '"><p>';
echo 'Tag your code with keywords, sperate each word or phrase with a comma.';
echo '</p>';
echo 'Language';
echo '<br><select name="lang">';
echo '<option value="';
echo 'none';
echo '">';
echo 'Dont change';
echo '</option>';
while ($data = mysql_fetch_array($res)) {
          echo '<option value="';
		  echo $data['ID'];
		  echo '">';
		  echo $data['Language'];
		  echo '</option>';
		  }
echo '</select><br>';
echo 'Difficulty';
echo '<br><select name="diff">';
echo '<option value="';
echo 'none';
echo '">';
echo 'Dont change';
echo '</option>';
while ($data2 = mysql_fetch_array($res2)) {
          echo '<option value="';
		  echo $data2['ID'];
		  echo '">';
		  echo $data2['Difficulty'];
		  echo '</option>';
		  }
echo '</select><br>';
echo 'Category';
echo '<br><select name="cat">';
echo '<option value="';
echo 'none';
echo '">';
echo 'Dont change';
echo '</option>';
while ($data3 = mysql_fetch_array($res3)) {
          echo '<option value="';
		  echo $data3['ID'];
		  echo '">';
		  echo $data3['Category'];
		  echo '</option>';
		  }
echo '</select>';
?>

<input type="hidden" name="unique" value="<?php 
echo $id; 
?>" />
<input type="hidden" name="p" value="<?php 
echo $thecodeinfo['Page']; 
?>" />
<input type="hidden" name="submitted" value="Y" />
<br /><br />
<table> <tr><td><input type="submit" value="Save Privately" name="save" /></td><td><input type="submit" value="Publish" name="publish" /></td></tr></table>
</form>
<script>
  var editor = CodeMirror.fromTextArea(document.getElementById("code<?php echo $code; ?>"), {
    lineNumbers: true,
    matchBrackets: true,
    mode: "htmlmixed"
  });
</script>
<?php
$code++;
}
}
else {
echo 'you dont own this code?';
}
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
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Illegal attempt editcode.php $ipforlog', '$logtime', '$ipforlog')");
echo 'sorry you must be logged in to do that';
}
include_once('footer.php');
?>