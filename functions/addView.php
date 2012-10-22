<?php
include_once("db.php");
$id = mysql_real_escape_string($_POST['ID']);
$addView = "UPDATE CodeInfo  SET Views=Views+1 WHERE StringID='".$id."'";
mysql_query($addView);
?>