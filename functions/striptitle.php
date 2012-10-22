<?php

function StripUrl($title)

{

$title = str_replace("#", "sharp", $title);

$title = str_replace("/", "or", $title);

$title = str_replace("$", "", $title);

$title = str_replace("&amp;", "and", $title);

$title = str_replace("&", "and", $title);

$title = str_replace("+", "plus", $title);

$title = str_replace(",", "", $title);

$title = str_replace(":", "", $title);

$title = str_replace(";", "", $title);

$title = str_replace("=", "equals", $title);

$title = str_replace("?", "", $title);

$title = str_replace("@", "at", $title);

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

return $title;

}

?>