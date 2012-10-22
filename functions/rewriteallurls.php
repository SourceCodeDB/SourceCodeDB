<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include_once('titletourl.php');

$titles = mysql_query("SELECT * FROM CodeInfo");
while ($titleqry = mysql_fetch_array($titles)) {
$title = $titleqry['Title'];
$id = $titleqry['StringID'];
$url = geturl($title);

echo $url.'<br/>';
try {
mysql_query("UPDATE CodeInfo SET Url='$url' WHERE StringID='$id'");
}
catch(Exception $e) {
echo 'Failed';
}

}
?>