<?php
session_start();
if ($_SESSION['Type'] == 'user') {
?>
<div style="background:#222;color:#fff;-moz-border-radius:5px;-webkit-border-radius:5px;width:565px;padding:3px 8px 3px 8px;">
<form>
<table>
<tr>
<td>
Old password:
<input type="password" id="old"/>
</td>
<td>
New password:
<input type="password" id="new1"/>
</td>
<td>
Retype new password:
<input type="password" id="new2"/>
</td>
<td>
<input type="button" style="-moz-border-radius:5px;-webkit-border-radius:5px;background:#000;color:#fff;padding:4px 10px 5px 10px;font-size:11px;font-weight:bold;margin-top:15px" value="Change" onclick="changePassword('old', 'new1', 'new2')"/>
</td>
</tr>
</table>
</form>
<div id="changing"></div>
<div id="info" style="color:#fff"></div>
</body>
</html>

<?php
}
else
{
	echo "Registration required!";
}
?>
