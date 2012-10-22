<?php
include_once('db.php');
			$user = mysql_real_escape_string($_POST['user']);
			if(strlen($user) >0) {

				$query = mysql_query("SELECT Username FROM User WHERE Username LIKE '$user%' LIMIT 10");
				if(mysql_num_rows($query) > 0) {
				echo '<ul>';
					while ($result = mysql_fetch_array($query)) {
	         			echo '<li onClick="fill2(\''.addslashes($result['Username']).'\');">'.$result['Username'].'</li>';
	         		}
				echo '</ul>';
					
				} else {
					echo 'no users';
				}
			} else {
				// do nothing
			}
?>