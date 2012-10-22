<?php
session_start();
include_once("db.php");
if (isset($_SESSION['Type'])) {
if ($_SESSION['Type'] == 'user') {
$id = $_SESSION['ID'];
$test = "SELECT Password FROM User WHERE ID='".$id."'";
$test = mysql_query($test);
$old = mysql_real_escape_string($_POST['oldpw']);
$new1 = mysql_real_escape_string($_POST['new1pw']);
$new2 = mysql_real_escape_string($_POST['new2pw']);

while($change = mysql_fetch_array($test))
{
	if(md5($old)==$change['Password'])
	{
		if($new1==$new2)
		{
            	if (strlen($new1) < 6) {
                    echo "Too short!";
            	}
            elseif (strlen($new1) > 30) {
                    echo "Password max length is 30 chracters";
            	}
            else {
            		mysql_query("UPDATE User SET Password='".md5($new1)."' WHERE ID='".$id."'");
                    echo "success";
            	}
    		}
    		else
		{
			echo "Your passwords do not match!";
		}
		}
		else
		{
			echo "Wrong password!";
		}
	}
	
}
else
{
	echo "regreq";
}
}
else
{
	echo "regreq";
}
?>