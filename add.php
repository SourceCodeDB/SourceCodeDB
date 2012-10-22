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
function finishAjax(id, response) {
  $('#codeLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
  $('#loading').fadeOut();
} //finishAjax

function finishAjax2(id, response) {
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax

$(document).ready(function() {
	$("#addupload").live('click', function() {
    	$.post("functions/addupload.php", function(response){
    	$('#loading').fadeIn();
        $('#addcontent').fadeOut();
        setTimeout("finishAjax('addcontent', '"+escape(response)+"')", 2000);
      }, "html");
    	return false;
    	});
    	$("#addcode").live('click', function() {
    	$.post("functions/addcode.php", function(response){
    	$('#loading').fadeIn();
        $('#addcontent').fadeOut();
        setTimeout("finishAjax('addcontent', '"+escape(response)+"')", 2000);
      }, "html");
    	return false;
    	});
    	$("#addproject").live('click', function() {
    	$.post("functions/addproject.php", function(response){
    	$('#loading').fadeIn();
        $('#addcontent').fadeOut();
        setTimeout("finishAjax('addcontent', '"+escape(response)+"')", 2000);
      });
    	return false;
    	});
    	$("#addtutorial").live('click', function() {
    	$.post("functions/addtutorial.php", function(response){
    	$('#loading').fadeIn();
        $('#addcontent').fadeOut();
        setTimeout("finishAjax('addcontent', '"+escape(response)+"')", 2000);
      });
    	return false;
    	});
    	
    	$('#titleLoading').hide();
	$('#title1').live('blur', function() {
	delay(function(){
	$('#titleLoading').show();
      $.post("functions/checktitle.php", {
        title: $('#title1').val()
      }
      , function(response){
        $('#titleLoading').hide();
        $('#titleResult').fadeOut();
        setTimeout("finishAjax2('titleResult', '"+escape(response)+"')", 400);
      });
    	return false;
    	}, 200 );
	});
	
});


var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

function AutoSave() {
	editor.save();
}
</script>
<div id="addcontent">
<?php
echo '<p>';
echo 'Upload code, a project, or maybe a tutorial. Simply choose from one of the choices below to get started Thank you for making SourceCodeDB.com better!';
echo '</p>';


echo '<p><b>Choose what you would like to upload!</b></p></br>';

?>
<div id="addupload">Upload a zip file</div>
<div id="addcode">Add code</div>
<div id="addproject">Add a project</div>
<div id="addtutorial">Add a tutorial</div>
</div>
<div id="loading" style="display:none;"><img src="images/indicator.gif" alt="Loading" /></div>

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

  var editor = CodeMirror.fromTextArea($("#code1"), {
    lineNumbers: true,
    matchBrackets: true,
    mode: "htmlmixed"
  });
</script>
<?php


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

include_once('footer.php');

?>
