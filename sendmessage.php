<?php
include_once('header.php');
if (isset($_GET['p'])) {
$id = $_GET['p'];
}
else {
    $id = "";
}
$frompagesend = $_SERVER["HTTP_REFERER"];
if ($_SESSION['Type'] == 'user') {
$username = $_SESSION['ID'];
$findrequests = mysql_query("SELECT * FROM User WHERE ID = $id");
if (mysql_num_rows($findrequests)) {
$data = mysql_fetch_array($findrequests);
}
if ($_SESSION['ID']) {
if (isset($_POST['message'])) {
$title = trim(htmlentities($_POST['title']));
$title = mysql_real_escape_string($title);
$content = mysql_real_escape_string($_POST['content']);
$content = trim(htmlentities($content));
$frompage = mysql_real_escape_string($_POST['from']);
$ipforlog = $_SERVER['REMOTE_ADDR'];
$usersentto = trim(htmlentities($_POST['user']));
$findrequests2 = mysql_query("SELECT * FROM User WHERE Username = '$usersentto'");
$userid2 = mysql_fetch_array($findrequests2);
$id2 = $userid2['Username'];
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
if (isset($data)) {
$usernamelog = $data['Username'];
}
else {
    $usernamelog = "";
}
$lastmsg = mysql_query("SELECT Date FROM Messages WHERE UserSent = $username ORDER BY Date DESC LIMIT 1");
$findlastmsg = mysql_result($lastmsg,0);
if (strtotime($logtime) - strtotime($findlastmsg) < 30) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Tried to send messages too often', '$logtime', '$ipforlog')");
echo 'Please wait 30 seconds between messages';
}
elseif (mysql_num_rows($findrequests2) != 1) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Tried to send to invalid user', '$logtime', '$ipforlog')");
echo 'Invalid user';
}
elseif (strtotime($logtime) - strtotime($findlastmsg) > 30) {
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Sent message to $usernamelog', '$logtime', '$ipforlog')");
mysql_query("INSERT INTO Messages (ID, UserSent, UserRec, Date, Title, Content, IP) VALUES ('', '$username', '$id2', '$logtime', '$title', '$content', '$ipforlog')");
$findrequests3 = mysql_query("SELECT * FROM User WHERE ID = $id2");
$data2 = mysql_fetch_array($findrequests3);
$notifyon = $data2['Notifications'];
if ($notifyon == 1) {
$to = $data2['Email'];
$success = 'Y';
 $subject = "New Message at SourceCodeDb.com";
 $body = "You have received a new message from ".$usernamelog.".\n
 ------------------------------------------- \n
 Title: ".$title." \n
 
 Content: ".$content." \n
 
 ------------------------------------------- \n
 Regards,
 The SourceCodeDB Team 
 
 To stop receiving these messages update your settings at SourceCodeDB.com";
$headers = 'From: no-reply@sourcecodedb.com' . "\r\n" .
   'Reply-To: no-reply@sourcecodedb.com' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();
try {
mail($to, $subject, $body, $headers);
}
 catch (ErrorException $e) {
     echo "could not send email";
 }
}
echo 'Message sent!';
echo '</div>';
include_once('footer.php');
?>
<script>
    <!--
    window.location= "<?php echo $frompage; ?>"
    //-->
    </script>
    <?php
}
}
elseif ($username == $id) {
$ipforlog = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
if (isset($data)) {
$usernamelog = $data['Username'];
}
else {
    $usernamelog = "";
}
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Tried sending message to self', '$logtime', '$ipforlog')");
echo 'You cant send yourself a message';
}
else {
?>
<script>
function suggest(inputString){
        if(inputString.length == 0) {
            $('#suggestions').fadeOut();
        } else {
        $('#user').addClass('load');
            $.post("functions/suggestuser.php", { user: ""+inputString+""}, function(data){
                if(data.length >0) {
                    $('#suggestions').fadeIn();
                    $('#suggestionsList').html(data);
                    $('#user').removeClass('load');
                }
            });
        }
    }
 
function fill(thisValue) {
    $('#user').val(thisValue);
    setTimeout("$('#suggestions').fadeOut();", 200);
}
</script>
<?php
if ($id == "") {
echo '<form method="post" action="#">';
}
else {
echo '<form method="post" action="sendmessage.php?p=';
echo $id;
echo '">';
}
echo 'Send To';
echo ': ';
echo '<input id="user" onkeyup="suggest(this.value);" type="text" name="user" value="';
if (isset($data)) {
echo $data['Username'];
}
echo '">';
?>
<div id="suggestions" class="suggestionsBox" style="display: none;">
<img style="position: relative; top: -12px; left: 30px;" src="images/arrow.png" alt="upArrow" />
<div id="suggestionsList" class="suggestionList"></div>
</div>
<?php
echo '<br /><br />';
echo 'Title';
echo ': ';
echo '<input type="text" name="title">';
echo '<br /><br />';
echo 'Message';
echo ': <br />';
echo '<textarea name="content" rows="10" cols="85"></textarea>';
echo '<br /><br />';
echo '<input type="hidden" name="from" value="';
echo $frompagesend;
echo '">';
echo '<input type="hidden" name="message" value="sent">';
echo '<input type="submit" name="submit" value="';
echo 'Send';
echo '">';
echo '</form>';
}
}
else {
$ipforlog = $_SERVER['REMOTE_ADDR'];
$date = date("Y-m-d"); 
$time = date("G:i:s"); 
$logtime = $date.' '.$time;
mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Tried to access sendmessage.php', '$logtime', '$ipforlog')");
echo 'You dont have permission to view this message';
}

}
else {
echo 'Sorry you must be logged in to see your messages.';
}
include_once('footer.php');
?>