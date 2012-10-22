<?php
include_once('header.php');
if (isset($_SESSION['Username'])) {
    $username = $_SESSION['Username'];
}
if (isset($_SESSION['Type'])) {
if ($_SESSION['Type'] == 'user') {
require_once("fbsdk/facebook.php");
 $config = array(
    'appId' => '225370617476152',
    'secret' => '03622faf6618ea99449f21079d13643f',
  );
  $facebook = new Facebook($config);
  $fbuid = $facebook->getUser(); 
$table = "Language";
$table2 = "Difficulty"; 
$table3 = "Category"; 
$sql = "SELECT * FROM $table";
$sql2 = "SELECT * FROM $table2";
$sql3 = "SELECT * FROM $table3 ORDER BY ID ASC";
$res = mysql_query($sql);
$res2 = mysql_query($sql2);
$res3 = mysql_query($sql3);
$username = $_SESSION['Username'];
$userreq = "Select ID from User where Username = '$username'";
$userid = $_SESSION['ID'];
$findtemp = mysql_query("SELECT Title, Description, TempID FROM CodeInfo WHERE UserID='$userid' AND IsTemp=1 AND TempID != 0");
if (mysql_num_rows($findtemp) > 0) {
while ($tempres = mysql_fetch_array($findtemp)) {
	$tempid = $tempres['TempID'];
	if (strlen($tempid) > 2) {
	$temptitle = $tempres['Title'];
	$tempdesc = $tempres['Description'];
	$findtemp2 = mysql_query("SELECT Content, Source, Tags FROM CodeContent Where TempID = '$tempid' AND IsTemp=1");
	while ($tempres2 = mysql_fetch_array($findtemp2)) {
		$tempcontent = $tempres2['Content'];
		$tempsource = $tempres2['Source'];
		$temptags = $tempres2['Tags'];
	}
	}
} 
}
?>
<script>
var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();
function finishAjax3(id, response) {
  $('#titleLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax
function finishAjax3(id, response) {
  $('#codeLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax
$(document).ready(function() {
	$('#titleLoading').hide();
	$('#title1').blur(function(){
	delay(function(){
	$('#titleLoading').show();
      $.post("functions/checktitle.php", {
        title: $('#title1').val()
      }
      , function(response){
        $('#titleLoading').hide();
        $('#titleResult').fadeOut();
        setTimeout("finishAjax3('titleResult', '"+escape(response)+"')", 400);
      });
    	return false;
    	}, 200 );
	});
});
$(document).ready(function() {
	$('#codeLoading').hide();
	setInterval(function(){
	delay(function(){
	  $('#codeLoading').show();
    $.post("functions/autosave.php", {
        code: editor.getValue(), title: $('#title1').val(), desc: $('#desc').val(), source: $('#source').val(), tags: $('#tags').val(), username: "<?php echo $username; ?>"}
      , function(response){
        $('#codeResult').fadeOut();
        setTimeout("finishAjax3('codeResult', '"+escape(response)+"')", 400);
      });
    	return false;
    	}, 200 );
	}, 5000);
});
function AutoSave() {
	editor.save();
}
</script>
<?php
echo '<form action="storecode.php" method="post">';
echo '<input type="hidden" name="username" value="';
echo $username;
echo '" />';
echo '<p>';
echo 'Here you may upload code, please make sure your code is right and that you dont abuse our site. We take our code and users who abuse our site very seriously. Thank you for making SourceCodeDB.com better!';
echo '</p>';
echo '<p><b>';
echo 'Title';
echo ':</b> ';
echo 'Please enter a descriptive title';
echo '</p><input type="text" size="30" name="title" id="title1" value="';
if (isset($temptitle)) {
echo $temptitle;
}
echo '"><span id="titleLoading"><img src="images/indicator.gif" alt="Loading" /> Checking for similar codes</span>
<span id="titleResult"></span></p><br><p><b>';
echo 'Description';
echo ':</b> ';
echo 'Please enter description that will help users understand what the code does.';
echo '</p><textarea name="desc" rows="10" cols="85" id="desc">';
if (isset($tempdesc)) {
echo $tempdesc;
}
echo '</textarea><br>';
echo 'Enter your code!';
echo '<br><textarea name="code" rows="30" cols="85" id="code">';
if (isset($tempcontent)) {
echo stripslashes($tempcontent);
}
echo '</textarea>';

echo '<span id="codeLoading"><img src="images/indicator.gif" alt="Loading" /> Attempting autosave</span>';
echo '<span id="codeResult"></span>';
echo '</p><br>';
echo 'Source';
echo ': <input type="text" name="source" id="source" value="';
if (isset($tempsource)) {
echo $tempsource;
}
echo '"><p>';
echo 'Leave blank if code is yours or there is no source but if you have taken this code from another website please tell us your source so that credit can be given.';
echo '</p>';
echo 'Tags';
echo ': <input type="text" name="tags" id="tags" value="';
if (isset($temptags)) {
echo $temptags;
}
echo '"><p>';
echo 'Tag your code with keywords, sperate each word or phrase with a comma.';
echo '</p>';
if (isset($fbuid)) {
echo 'Share on facebook?';
echo ' <input type="checkbox" name="facebook" value="Y"><br />';
}
echo 'Language';
echo '<br><select name="lang">';
while ($data = mysql_fetch_array($res)) {
          echo '<option value="';
		  echo $data['ID'];
		  echo '">';
		  echo $data['Language'];
		  echo '</option>';
		  }
echo '</select><br>';
echo 'Difficulty';
echo '<br><select name="diff">';
while ($data2 = mysql_fetch_array($res2)) {
          echo '<option value="';
		  echo $data2['ID'];
		  echo '">';
		  echo $data2['Difficulty'];
		  echo '</option>';
		  }
echo '</select><br>';
echo 'Category';
echo '<br><select name="cat">';
while ($data3 = mysql_fetch_array($res3)) {
          echo '<option value="';
		  echo $data3['ID'];
		  echo '">';
		  echo $data3['Category'];
		  echo '</option>';
		  }
echo '</select><br><input type="hidden" name="submitted" value="Y" /><br /><br />';
echo '<table> <tr><td><input type="submit" value="Save Privately" name="save" /></td><td><input type="submit" value="Publish" name="publish" /></td></tr></table>';
echo '</form>';
}
else {
$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid - '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Illegal attempt addcode.php $ipforlog', '$logtime', '$ipforlog')");

echo 'sorry you must be logged in to post';
}
}
else {
$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	if (isset($_SESSION['ID'])) {
		$userlogid = $_SESSION['ID'];
	}
	else {
		$userlogid = '0';
	}
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$userlogid', 'Illegal attempt addcode.php $ipforlog', '$logtime', '$ipforlog')");

echo 'sorry you must be logged in to post';
}
?>
<script>
  var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
    lineNumbers: true,
    matchBrackets: true,
    mode: "htmlmixed",
    onBlur: function() {
    AutoSave()
    }
  });
$(document).ready(function() {
	setInterval(AutoSave(),1000)
});
</script>

<?php
include_once('footer.php');
?>