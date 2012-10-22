<?php
include_once('db.php');
$title = mysql_real_escape_string($_POST['title']);

$title = trim(htmlentities($title));
if ($title != "") {

    $checktitle = mysql_query("SELECT * FROM CodeInfo WHERE (Title LIKE '%$title%') AND IsTemp = 0 LIMIT 5");
    if (mysql_num_rows($checktitle) > 0) {
    	echo '<br />';
    	echo 'We found similar code, make sure its not a duplicate';
    	echo '<br />';
    	$count = 1;
    	while ($data = mysql_fetch_array($checktitle)) {
    		echo $count.'. <b>'.$data['Title'].'</b><br />';
    		echo $data['Description'];
    		echo '<br />';
    		$count++;
    	}
    	}
    	else {
    		echo '<br/>no similar codes found.';
    	}
}
else {
    echo '';
}
    		
?>