<?php
include_once('header.php');
?>
<table><tr><td width="400px">
<h3><?php echo 'Top Ranked'; ?></h3>
<table><tr><th width="200px"><?php echo 'User'; ?>
</th><th width="200px"><?php echo 'Rank'; ?>
</th></tr>
<?php
$findusers = mysql_query("SELECT * FROM Ranked ORDER BY Points DESC LIMIT 10");
while ($data = mysql_fetch_array($findusers)) {
$userid = $data['UserID'];
$points = $data['Points'];
$theuser = mysql_query("SELECT Username FROM User WHERE ID = $userid");
echo '<tr><td>';
echo '<a href="';
echo mysql_result($theuser,0);;
echo '.htm">';
echo mysql_result($theuser,0);
echo '</a></td><td>';
echo $points;
echo '</td></tr>';
}
?>
</table>
</td>
<td width="400px">
<h3><?php echo 'Top Rated'; ?></h3>
<table><tr>
<th width="200px"><?php echo 'User'; ?>
</th><th width="200px"><?php echo 'Rating'; ?>
</th></tr>
<?php
$findusers = mysql_query("SELECT * FROM Ranked ORDER BY Rating DESC LIMIT 10");
while ($data = mysql_fetch_array($findusers)) {
$userid = $data['UserID'];
$points = $data['Rating'];
$theuser = mysql_query("SELECT Username FROM User WHERE ID = $userid");
echo '<tr><td>';
echo '<a href="';
echo mysql_result($theuser,0);;
echo '.htm">';
echo mysql_result($theuser,0);
echo '</a></td><td>';
echo $points;
echo '</td></tr>';
}
?>
</table>
</td></tr></table>
<?php
include_once('footer.php');
?>