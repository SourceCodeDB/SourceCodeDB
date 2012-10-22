<?php

function checkdup($url) {
$checkfordup = mysql_query("SELECT URL FROM CodeInfo WHERE URL='$url'");
if (!isset($i)) {
	$i=1;
}
if (mysql_num_rows($checkfordup) > 0) {
	if ($i > 1) {
		$url = substr($url, 0, -1);
	}
	$url = $url.$i;
	$i++;
	$checkfordup2 = mysql_query("SELECT URL FROM CodeInfo WHERE URL='$url'");
	if (mysql_num_rows($checkfordup2) > 0) {
		checkdup($url);
	}
	else {
		return $url;
	}
} else {
return $url;
}

}

function geturl($title) {
$title = str_replace("#", "-sharp", $title);
$title = str_replace("/", "-or", $title);
$title = str_replace("$", "", $title);
$title = str_replace("&amp;", "-and", $title);
$title = str_replace("&", "-and", $title);
$title = str_replace("+", "-plus", $title);
$title = str_replace(",", "", $title);
$title = str_replace(":", "", $title);
$title = str_replace(";", "", $title);
$title = str_replace("=", "-equals", $title);
$title = str_replace("?", "", $title);
$title = str_replace("@", "-at", $title);
$title = str_replace("<", "", $title);
$title = str_replace(">", "", $title);
$title = str_replace("%", "", $title);
$title = str_replace("{", "", $title);
$title = str_replace("}", "", $title);
$title = str_replace("|", "", $title);
$title = str_replace("\\", "", $title);
$title = str_replace("^", "", $title);
$title = str_replace("~", "", $title);
$title = str_replace("[", "", $title);
$title = str_replace("]", "", $title);
$title = str_replace("`", "", $title);
$title = str_replace("'", "", $title);
$title = str_replace("\"", "", $title);
$title = str_replace(" ", "-", $title);
if (substr($title, -1) == "-") {
	$title = substr($title, 0, -1);
}

$checkfordup = mysql_query("SELECT URL FROM CodeInfo WHERE URL='$title'");


$url = checkdup($title);

return $url;

}
?>