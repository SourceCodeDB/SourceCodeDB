<?php
include_once('header.php');
$thisPage="index";
?>
<script language="javascript"> 
function toggle() {
	var ele = document.getElementById("toggleText");
	var text = document.getElementById("displayText");
	if(ele.style.display == "block") {
    	ele.style.display = "none";
		text.innerHTML = "here.";
  	}
	else {
		ele.style.display = "block";
		text.innerHTML = "(hide)";
	}
} 
</script>
<hr>
<?php echo 'For'; ?> <strong><?php echo 'new'; ?></strong> <?php echo 'users of SourceCodeDB you can read about how to use the site'; ?> <a id="displayText" href="javascript:toggle();"><strong><?php echo 'here.'; ?></strong></a>
<div id="toggleText" style="display: none">
<table width="502" height="665" border="0">
    <tr>
      <td height="36"><p class="style3">
      <div align="left"><b><?php echo 'This is a step to step guide on how to use SourceCodeDB'; ?></b></div></td>
    </tr>
    <tr>
      <td height="43"><p class="style2"><?php echo 'Start by registering to the website. That gives you access to'; ?> &quot;<?php echo 'My pages'; ?>&quot;. <?php echo 'You will find the register link in the top right hand side of the page.'; ?></span></td>
    </tr>
    <tr>
      <td width="496"><div align="left"><img src="images/4.png"/></div></td>
    </tr>
    <tr>
      <td height="43"><p class="style2"><?php echo 'Sign in is also found in the top right.'; ?></p></td>
    </tr>
    <tr>
      <td><img src="images/5.png"/></td>
    </tr>
    <tr>
      <td height="43"><p class="style2"><?php echo 'Press the Search button and then the language you would like to see code for. You can select more than just one at a time.'; ?> </p></td>
    </tr>
    <tr>
      <td><img src="images/2.png" width="316" height="29" /></td>
    </tr>
    <tr>
      <td height="43"><p class="style2"><?php echo 'The difficulty filter is alredy activated, so if you would like to see just one press the one you'; ?> <strong><?php echo 'dont'; ?></strong> <?php echo 'want to see.'; ?> </p></td>
    </tr>
    <tr>
      <td><img src="images/3.png" width="124" height="28" /></td>
    </tr>
    <tr>
      <td height="43"><p class="style2"><?php echo 'Click the title of the code you would like to see.'; ?> </p></td>
    </tr>
    <tr>
      <td height="30"><p class="style2"><img src="images/6.png" width="425" height="94" /></p></td>
    </tr>
    <tr>
      <td height="43"><span class="style2"><?php echo 'And there is the code.'; ?></span></td>
    </tr>
    <tr>
      <td><p class="style2"><img src="images/7.png" width="254" height="121" /></p></td>
    </tr>
    <tr>
      <td><?php echo 'Now all thats left is to get started! Welcome to SourceCodeDB!'; ?></td>
    </tr>  
  </table></div><hr><br/>
<div id="content-left">
<div class="content-left-body">
<table><tr><th width="200px"><?php echo 'User'; ?>
</th><th width="200px"><?php echo 'Points'; ?>
</th><th width="200px"><?php echo 'Rating'; ?>
</th></tr>
<?php
$findusers = mysql_query("SELECT * FROM MonthlyRating ORDER BY MonthID DESC, Points DESC, Rating DESC LIMIT 10");
while ($data = mysql_fetch_array($findusers)) {
$userid = $data['UserID'];
$points = $data['Points'];
$rating = $data['Rating'];
$theuser = mysql_query("SELECT Username FROM User WHERE ID = '$userid'");
$checkifbanned = mysql_query("SELECT UserID FROM Ban WHERE UserID = '$userid'");

if ($points == 0 && $rating == 0) {
	echo '<tr><td colspan="3">';
	echo 'No more users have points this month.';
	echo '</td></tr>';
	echo '<tr><td colspan="3">';
	echo '<a href="addcode.php">Upload some code</a> or <a href="ranked.php">see overall top users.</a>';
	echo '</td></tr>';
	break;
}
else {
	echo '<tr><td>';
}
if (mysql_num_rows($checkifbanned) > 0) {
    echo '[Banned]';
}
else {
echo '<a href="';
echo mysql_result($theuser,0);;
echo '.htm">';
echo mysql_result($theuser,0);
echo '</a>';
}
echo '</td><td>';
echo $points;
echo '</td><td>';
echo $rating;
echo '</td></tr>';
}
?>
</table>
</div>
</div>
<div id="content-right">
	<div class="div-right-body">
		<?php include_once('senaste5.php'); ?>
	</div>
</div>
<div id="content-left2">
<div class="content-left2-body">
<h1>Ask a question!</h1>
<form method="post" action="questions.php">
<?php echo 'Title: (max 150 chars)'; ?><br />
<input type="text" name="title" size="46"><br />
<?php echo 'Question'; ?>: (3000 word limit)<br />
<textarea name="content" rows="10" cols="40"></textarea>
<br />
<input type="hidden" name="qsubmit" value="Y">
<?php
if (isset($_SESSION['ID'])) {
?>
<input type="submit" name="submit" value="<?php echo 'Submit Question'; ?>">
<?php
} else {
echo '<a href="http://sourcecodedb.com/register.php">Register to ask a question!</a>';
}
?>
<br/><br/>

</form>
</div>
</div>
<div id="content-right2"><?php include_once('latestnews.php'); ?></div>

<?php
include_once('footer.php');
?>