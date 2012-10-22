<?php
session_start();
include_once("db.php");
if (isset($_SESSION['Type'])) {
if ($_SESSION['Type'] == 'user') {
$id = $_SESSION['ID'];
$test = "SELECT Email FROM User WHERE ID='".$id."'";
$test = mysql_query($test);
$old = mysql_real_escape_string($_POST['oldpw']);
$new1 = mysql_real_escape_string($_POST['new1pw']);
$new2 = mysql_real_escape_string($_POST['new2pw']);

function check_email_address($email) {
      		// First, we check that there's one @ symbol,
      		// and that the lengths are right.
      if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
        // Email invalid because wrong number of characters
        // in one section or wrong number of @ symbols.
        return false;
      }
      // Split it into sections to make life easier
      $email_array = explode("@", $email);
      $local_array = explode(".", $email_array[0]);
      for ($i = 0; $i < sizeof($local_array); $i++) {
        if
    (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
    ↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
    $local_array[$i])) {
          return false;
        }
      }
      // Check if domain is IP. If not,
      // it should be valid domain name
      if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
          if
    (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
    ↪([A-Za-z0-9]+))$",
    $domain_array[$i])) {
            return false;
          }
        }
      }
      	mysql_query("UPDATE User SET Email='".$new1."' WHERE ID='".$id."'");
	echo "success";
    }

while($change = mysql_fetch_array($test))
{
	if($old==$change['Email'])
	{
		if($new1==$new2)
		{
			check_email_address($new1);
		}
		else
		{
			echo "Wrong email!";
		}
	}
	else
	{
		echo "Wrong email!";
	}
}
}
else
{
	echo "Registration required!";
}
}
else
{
	echo "Registration required!";
}
?>