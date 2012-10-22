<?php
session_start();
include_once("db.php");
if (isset($_SESSION['Type'])) {
$id = $_SESSION['ID'];
$admin = "SELECT ID, PermissionID FROM User WHERE ID='$id' AND PermissionID >= 5";
$admin = mysql_query($admin);
while($admins = mysql_fetch_array($admin))
{
?>
<form action="#" method="post">
Title: <input type="text" name="title" /><br />
Content:<br /><textarea rows="10" cols="50" name="content" />
<input type="submit" value="Create" />
</form>
<?php
}
}
?>
