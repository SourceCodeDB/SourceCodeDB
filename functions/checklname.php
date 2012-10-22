<?php
include_once('db.php');
$lname = mysql_real_escape_string($_POST['lname']);

echo $lname.'<br />';

$lname = trim(htmlentities($lname));

echo check_name($lname); // call the check_username function and echo the results.

function check_name($lname){
    if (strlen($lname) < 3) {
    	return '<span style="color:#f00">Too short</span>';
    }
    elseif (strlen($lname) > 29) {
    	return '<span style="color:#f00">Too long</span>';
    }
    elseif (!preg_match('/^\w+$/', $lname)) {
    	return '<span style="color:#f00">Only alphabetical characters plz.</span>';
    }
    else {
    	return '<span style="color:#0c0">Good!</span>';
    }
}