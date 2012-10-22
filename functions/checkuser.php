<?php
include_once("db.php");
$username = mysql_real_escape_string($_POST['username']);

echo $username.'<br />';

$username = trim(htmlentities($username));

echo check_username($username); // call the check_username function and echo the results.

function check_username($username){
    $result = "SELECT * FROM User WHERE Username='$username'";
    $r = mysql_query($result);
        if(mysql_num_rows($r) >= 1){
            return '<span style="color:#f00">Username Unavailable</span>';
        }
   	elseif(strlen($username) < 4) {
   		return '<span style="color:#f00">Too short</span>';
   	}
   	elseif(strlen($username) > 15) {
   		return '<span style="color:#f00">Too Long :)</span>';
   	}
   	elseif($username == 'administrator') {
   		return '<span style="color:#f00">mm, yea, not gonna happen</span>';
   	}
   	elseif (!preg_match('/^\w+$/', $username)) {
    		return '<span style="color:#f00">Only alphabetical characters plz.</span>';
    	}
    return '<span style="color:#0c0">Username Available</span>';
}
?>