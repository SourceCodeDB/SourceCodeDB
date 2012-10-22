<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('monthlyratings.php');
include_once('ratings.php');

UpdateOverallRatings();
echo 'ok!</br>';
UpdateMonthlyRatings();
echo 'done';
?>
