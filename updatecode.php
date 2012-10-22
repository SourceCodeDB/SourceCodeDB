<?php
include_once('header.php');
include_once('functions/db.php');
if ($_SESSION['Type'] == 'user') {
$username = $_SESSION['Username'];
$userreq = "Select ID from User where username = '$username'";
$userid = mysql_result(mysql_query($userreq),0) or die (mysql_error());
$table = "CodeContent";
$table2 = "CodeInfo";
$code = $_POST['code'];
$page = mysql_real_escape_string($_POST['p']);
$page = trim(htmlentities($page));
$descbad = mysql_real_escape_string($_POST['desc']);
$desc = trim(htmlentities($descbad));
$title2 = mysql_real_escape_string($_POST['title']);
$title = trim(htmlentities($title2));
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$ledit = $date.' '.$time;
$lang = mysql_real_escape_string($_POST['lang']);
$lang = trim(htmlentities($lang));
$diff = mysql_real_escape_string($_POST['diff']);
$diff = trim(htmlentities($diff));
$cat = mysql_real_escape_string($_POST['cat']);
$cat = trim(htmlentities($cat));
$source = mysql_real_escape_string($_POST['source']);
$source = trim(htmlentities($source));
$tags2 = mysql_real_escape_string($_POST['tags']);
$tags = trim(htmlentities($tags2));
$codeinfoid = mysql_real_escape_string($_POST['unique']);
$url = $codeinfoid;
$checkadmin = "SELECT PermissionID FROM User WHERE ID = '$userid'";
$useradmin = mysql_result(mysql_query($checkadmin),0) or die (mysql_error());
$userverify = "Select UserID from CodeInfo where StringID = '$codeinfoid'";
$userid2 = mysql_result(mysql_query($userverify),0) or die (mysql_error());
if ($_SESSION['ID'] == $userid2 || $useradmin >= 5) {
if ($_POST['publish']) {
	$published='1';
	$publog='Published';
}
else {
	$published='0';
	$publog='Saved Privately';
}
if (strlen($title) > 70) {
	echo '<div id="content">';
	echo 'Title cant be that long';
	echo '</div>';
}
elseif (strlen($title) < 1) {
	echo '<div id="content">';
	echo 'Title cant be that short';
	echo '</div>';
}
else {
if ($cat != 'none') {
	$sql = "UPDATE $table2 SET CategoryID='$cat' WHERE StringID=$codeinfoid";
}
if ($diff != 'none') {
	$sql = "UPDATE $table2 SET DifficultyID='$diff' WHERE StringID=$codeinfoid";
}
if ($lang != 'none') {
	$sql = "UPDATE $table2 SET LanguageID='$lang' WHERE StringID=$codeinfoid";
}
$checkifproj = mysql_result(mysql_query("SELECT TypeID FROM CodeInfo WHERE StringID='$codeinfoid'"),0);
if ($checkifproj == 3) {
$findrev = mysql_result(mysql_query("SELECT Revision FROM $table WHERE StringID='$codeinfoid' AND Page='$page'"),0);
}
else {
$findrev = mysql_result(mysql_query("SELECT Revision FROM $table WHERE StringID='$codeinfoid'"),0);
}
$revision = $findrev+1;
$revdate = date('j-m-y h:i:s');
//second sql only executes if code is not a project
$sql = "UPDATE $table SET StringID='0', RevStringID='$codeinfoid', RevDate='$revdate' WHERE StringID='$codeinfoid' AND Page='$page'";
$sql2 = "UPDATE $table2 SET LEdit='$ledit', Title='$title', Description='$desc', Published='$published', Tags='$tags', Moderated='0' WHERE StringID='$codeinfoid'";
$sql3 = "INSERT INTO $table (Content, StringID, Title, Description, Published, Source, Revision, Page) VALUES ('$code', '$codeinfoid', '$title', '$desc', '$published', '$source', '$revision', '$page')";
$sql4 = "UPDATE $table2 SET LEdit='$ledit', Published='$published', Moderated='0' WHERE StringID='$codeinfoid'";
mysql_query($sql) or die (mysql_error());
if ($checkifproj != 3) {
mysql_query($sql2) or die (mysql_error());
}
else {
mysql_query($sql4) or die (mysql_error());
}
mysql_query($sql3) or die (mysql_error());
$ipforlog = $_SERVER['REMOTE_ADDR'];
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userid', 'Updated and $publog Code: $title', '$ledit', '$ipforlog')");
echo 'Code updated';
echo ' <br /><br />';
$url = mysql_result(mysql_query("SELECT Url FROM CodeInfo WHERE StringID = '$codeinfoid'"),0);

?>
<script>
    <!--
    window.location= "http://sourcecodedb.com/<?php echo $url; ?>.html"
    //-->
</script>
<?php
}
}
else {
echo 'UBERBRAINCHILD do not like the hackzors';
}
}
else {
echo 'Sorry you must be logged in to do that';
}
include_once('footer.php');
?>