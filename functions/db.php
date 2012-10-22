<?php
$con = mysql_connect("localhost","DBNAME","PASSWORD");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("DBNAME", $con);
?>