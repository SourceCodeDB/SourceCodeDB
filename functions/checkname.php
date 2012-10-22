<?php
include_once('db.php');
$name = mysql_real_escape_string($_POST['name']);

echo $name.'<br />';

$name = trim(htmlentities($name));

echo check_name($name); // call the check_username function and echo the results.

function check_name($name){
    if (strlen($name) < 3) {
    	return '<span style="color:#f00">Too short</span>';
    }
    elseif (strlen($name) > 19) {
    	return '<span style="color:#f00">Too long</span>';
    }
    elseif (!preg_match('/^\w+$/', $name)) {
    	return '<span style="color:#f00">Only alphabetical characters plz.</span>';
    }
    else {
    	return '<span style="color:#0c0">Good!</span>';
    }
}