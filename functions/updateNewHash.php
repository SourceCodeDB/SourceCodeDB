<?php
include_once("db.php");
$count = 0;
//$lepasswords = mysql_query("SELECT * FROM User")or die(mysql_error());
if (mysql_num_rows($lepasswords) > 0) {
    while ($thispass = mysql_fetch_array($lepasswords)) {
        $id = $thispass['ID'];
        $pass = $thispass['Password'];
        if (isset($pass) && $pass != "") {
        $highlyencryptedstuff = md5($pass."ILIKEPIE");
        mysql_query("UPDATE User SET Password = '$highlyencryptedstuff' WHERE ID = '$id'");
        echo $highlyencryptedstuff."</br>";
        $count++;
        }
    }
}

echo "Like ".$count." Passwords were updated";
?>
