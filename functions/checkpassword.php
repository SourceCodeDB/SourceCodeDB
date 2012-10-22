<?php
include_once('db.php');
if (isset($_POST['password2']) && isset($_POST['password3'])) {
$password = mysql_real_escape_string($_POST['password2']);
$password2 = mysql_real_escape_string($_POST['password3']);

$password = trim(htmlentities($password));
$password2 = trim(htmlentities($password2));

echo check_name($password,$password2); // call the check_username function and echo the results.

function check_name($password,$password2){
    	if (strlen($password2) < 6) {
    		return '<span style="color:#f00">Too short</span>';
    	}
    	elseif (strlen($password2) > 30) {
    		return '<span style="color:#f00">Dude your password should not be that long :) Thats ridiculous!</span>';
    	}
    	elseif ($password != $password2) {
    		return '<span style="color:#f00">Your passwords do not match buddy, might want to check that.</span>';
    	}
    	else {
    		return '<span style="color:#0c0">We got a match!</span>';
    	}
}
}
else {
    return '';
}

?>