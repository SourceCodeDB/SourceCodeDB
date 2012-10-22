<?php
session_start();
if ($_SESSION['Type'] == 'user') {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<div style="background:#222;color:#fff;-moz-border-radius:5px;-webkit-border-radius:5px;width:565px;padding:3px 8px 3px 8px;">
<form>
<table>
<tr>
<td>
Old Email:
<input type="text" id="oldmail"/>
</td>
<td>
New email:
<input type="text" id="new1mail"/>
</td>
<td>
Retype new email:
<input type="text" id="new2mail"/>
</td>
<td>
<input type="button" style="-moz-border-radius:5px;-webkit-border-radius:5px;background:#000;color:#fff;padding:4px 10px 5px 10px;font-size:11px;font-weight:bold;margin-top:15px" value="change" onclick="changeEmail('oldmail', 'new1mail', 'new2mail')"/>
</td>
</tr>
</table>
</form>
<div id="info"></div>
</body>
</html>

<?php
}
else
{
	echo "Registration required!";
}
?>
