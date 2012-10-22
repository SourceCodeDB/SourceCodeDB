<?php
include_once('db.php');
$email = mysql_real_escape_string($_POST['email']);

echo $email.'<br />';

$email = trim(htmlentities($email));

echo check_email_address($email);

function check_email_address($email) {
    if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/" , $email))
    {
    list($username,$domain)=split('@',$email);

    if(!getmxrr ($domain,$mxhosts)) {
    return '<span style="color:#f00">Porblems with the domain check it</span>';
    }
    return '<span style="color:#0c0">Thats acceptable :)</span>';
    }
    return '<span style="color:#f00">In every email there should be an @ symbol or something in front of the @ symbol.</span>';
}
