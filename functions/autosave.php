<?php
include_once('db.php');
session_start();
date_default_timezone_set('Europe/Stockholm'); // specifierar timezone till svensk
function generateRandStr($length){
      $randstr = "";
      for($i=0; $i<$length; $i++){
         $randnum = mt_rand(0,9);
         $randstr .= $randnum;
      }
      $query = "SELECT COUNT(*) FROM CodeContent WHERE TempID = '{$randstr}'";

    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);

    if ($row['COUNT(*)'] > 0) { // if it already exists, do it again

        $randstr = generateRandStr(11);
    }
      return $randstr;
}
$string = generateRandStr(11);
$title = mysql_real_escape_string($_POST['title']);
$title = trim(htmlentities($title));
$content = mysql_real_escape_string($_POST['code']);
$content = trim(htmlentities($content));
$desc = mysql_real_escape_string($_POST['desc']); 
$desc = trim(htmlentities($desc));
$source = mysql_real_escape_string($_POST['source']);
$source = trim(htmlentities($source));
$tags = mysql_real_escape_string($_POST['tags']); 
$tags = trim(htmlentities($tags));
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$ledit = $date.' '.$time;
$userid = $_SESSION['ID'];
if ($title == null || $desc == null || $content == null) {
	echo 'autosave will begin when Title, Description, and Code is being used.';
}
else {
if ($userid == null) {
	echo 'there was an error during autosave';
}
else {
//save contents to database
$findtemp = mysql_query("SELECT TempID FROM CodeInfo WHERE IsTemp='1' AND UserID='$userid'");
if (mysql_num_rows($findtemp) < 1) {
mysql_query("INSERT INTO CodeInfo (Title, Description, Tags, TempID, IsTemp, LEdit, UserID, Date) VALUES ('$title', '$desc', '$tags', '$string', '1', '$ledit', '$userid', '$ledit')"); 
mysql_query("INSERT INTO CodeContent (Title, Content, Source, Description, Tags, TempID, IsTemp) VALUES ('$title', '$content', '$source', '$desc', '$tags', '$string', '1')");
}
else {
$tempid = mysql_result($findtemp,0);
mysql_query("UPDATE CodeInfo SET Title = '$title', Description = '$desc', Tags = '$tags', LEdit = '$ledit' WHERE TempID = '$tempid'"); 
mysql_query("UPDATE CodeContent SET Title = '$title', Content = '$content', Source = '$source', Description = '$desc', Tags = '$tags' WHERE TempID = '$tempid'");
}
 
//get timestamp

$findtemp2 = mysql_query("SELECT TempID FROM CodeInfo WHERE IsTemp='1' AND UserID='$userid'");
$tempid2 = mysql_result($findtemp2,0);
if ($tempid2 == null) {
echo 'unable to autosave<br/>';
}
else {
$result = mysql_query("SELECT LEdit FROM CodeInfo WHERE TempID = '$tempid'"); 
$timestamp = mysql_result($result, 0); 
 
//output timestamp 
echo 'Last Saved: '.$timestamp;
}
}
}
?>