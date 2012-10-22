<?php
session_start();
$username = $_SESSION['Username'];
$id = $_SESSION['ID'];
include_once('db.php');
if(isset($_POST['strID']))
{
$favdel = "DELETE FROM Bookmarks where UserID='".$id."' AND StringID='".mysql_real_escape_string($_POST['strID'])."'";
mysql_query($favdel) or die(mysql_error());
}
?>