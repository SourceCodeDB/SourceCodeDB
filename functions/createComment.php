<?php
session_start();
include_once("db.php");
$parentid = mysql_real_escape_string($_POST['pid']);
if ($_SESSION['Type'] == 'user') {
?>
<form action="#" method="post">
<input type="hidden" name="childcom" value="Y">
<input type="hidden" name="childcomid" value="<?php echo $parentid; ?>">
<textarea rows="10" cols="50" name="content" /><br />
<input type="submit" value="Submit" />
</form>
<?php
}
?>