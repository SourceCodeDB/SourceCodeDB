<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
echo 'ok';
//include 'db.php';

$query = mysql_query("SELECT * FROM CodeInfo")or die(mysql_error());

while ($data = mysql_fetch_array($query)) {
	$tags = $data['Tags'];
	$id = $data['StringID'];
	$tagsarray = explode(",", $tags);
	foreach ($tagsarray as $tag) {
		$tag = trim($tag);
		mysql_query("INSERT INTO Tags (Tag, StringID) VALUES ('$tag', '$id')")or die(mysql_error());
	}
}
echo 'done';

?>