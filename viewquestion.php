<?php
include_once('header.php');
//post to twitter
require 'twitteroauth/tmhOAuth.php';
require 'twitteroauth/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key' => 'O309gjFzlOkrnwsPA8ebcQ',
  'consumer_secret' => '3jFijBpGQ38TQi0JDzfXyqwJyQ2vTqjWYbSuGNx3U',
  'user_token' => '403388089-0uDI7UU8A8dUD9EGhM0YnmRfpiTxatNBCU8d1VgD',
  'user_secret' => 'HxMiCJAuMKzWSrZtZTJKsdhsnyGvk0HprX98pL3Q6YU',
));
function LimitText($Text,$Min,$Max,$MinAddChar)
    {
        if (strlen($Text) < $Max) {
            $Text;
        } else {
            $words = explode(" ", $Text);
            unset($Text);
            foreach($words as $word) {
                if (strlen($word) >= $Max) {
                    $Text .= substr_replace($word, '', 70, -1) . "...\n";
                } else {
                    $Text .= $word . ' ';
                }
            }
        }
        
        return $Text;
    }
    
    function cleanForShortURL($toClean)
    {
        return strtr($toClean, $GLOBALS['normalizeChars']);
    }
    
if (isset($_GET['p'])) {
	$id = $_GET['p'];
}
else {
	$id = "";
}
if (isset($_SESSION['ID'])) {
$username = $_SESSION['ID'];
}
$id = trim(htmlentities($id));
$username = trim(htmlentities($username));
$sqlquery = mysql_query("SELECT PermissionID FROM User WHERE ID = $username");
$sqlresult = mysql_result($sqlquery,0);
$findrequests = mysql_query("SELECT * FROM Question WHERE ID = $id");
$data = mysql_fetch_array($findrequests);
$title = $data['Title'];

if (isset($_POST['deletecomment']) && isset($_SESSION['ID'])) {
    $commentid = mysql_real_escape_string($_POST['commentid']);
    $comdel = "DELETE FROM Answer where ID = ".$commentid;
    
    mysql_query($comdel) or die(mysql_error());
    echo 'Answer deleted!';
    echo '<BR /><BR />';
}

if (isset($_POST['moderated']) && $sqlresult >= 5) {
    $comidpost = mysql_real_escape_string($_POST['comid']);
    mysql_query("UPDATE Answer SET Moderated=1 WHERE ID = $comidpost");
    $ipforlog = $_SERVER['REMOTE_ADDR'];
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $logtime = $date.' '.$time;
    mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Approved answer $comidpost', '$logtime', '$ipforlog')");
    echo 'Answer Approved<BR/><br/>';
}

if (isset($_POST['childans']) && isset($_SESSION['ID'])) {
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $ledit = $date.' '.$time;
    $codecontent = mysql_real_escape_string($_POST['content']);
    $childcomid = mysql_real_escape_string($_POST['childcomid']);
    $childcomlvl = mysql_result(mysql_query("SELECT ChildLvl FROM Answer WHERE ID = $childcomid"),0);
    $childcomlvl++;
    $Text = $codecontent;
    $Min = 0;
    $Max = 70;
    $MinAddChar = '<BR>';
    $GLOBALS['normalizeChars'] = array(
    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
    );
    $toClean = LimitText($Text,$Min,$Max,$MinAddChar);
    $Text2 = cleanForShortURL($toClean);
    $codecontent2 = wordwrap($Text2, 80, "\n");
    $codecontenttrim = trim(htmlentities($codecontent2));
    $ipforlog = $_SERVER['REMOTE_ADDR'];
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $logtime = $date.' '.$time;
    $lastcom = mysql_query("SELECT Date FROM answer WHERE UserID = $username ORDER BY Date DESC LIMIT 1");
    $findlastcom = mysql_result($lastcom,0);
    if (strtotime($logtime) - strtotime($findlastcom) < 30) {
        mysql_query("INSERT INTO Log(ID, UserID, Action, Time, IP) VALUES('', '$username', 'Posted answer within 30 seconds of last answer', '$logtime', '$ipforlog')");
        echo 'You can only post a reply every 30 seconds';
        ;
    } else {
        mysql_query("INSERT INTO Log(ID, UserID, Action, Time, IP) VALUES('', '$username', 'Replied to answer on $id', '$logtime', '$ipforlog')");
        $comadd = "INSERT INTO Answer (QuestionID, UserID, Date, Content, Parent, ChildLvl) values ($id, '$username', '$ledit', '$codecontenttrim', '$childcomid', '$childcomlvl')";
        mysql_query($comadd) or die(mysql_error());
        
        $tmessage = $tusername.' just answered on http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    
$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
  'status' => $tmessage
));



        echo 'Answer submitted!';
        echo '<BR /><BR />';
        echo $codecontenttrim;
        echo '<BR /><BR />';
    }
}

if (isset($_POST['answeradd']) && isset($_SESSION['ID'])) {
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $ledit = $date.' '.$time;
    $codecontent = mysql_real_escape_string($_POST['code']);
    $Text = $codecontent;
    $Min = 0;
    $Max = 70;
    $MinAddChar = '<BR>';
    $GLOBALS['normalizeChars'] = array(
    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
    );
    
    $toClean = LimitText($Text,$Min,$Max,$MinAddChar);
    $Text2 = cleanForShortURL($toClean);
    $codecontent2 = wordwrap($Text2, 80, "\n");
    $codecontenttrim = trim(htmlentities($codecontent2));
    $ipforlog = $_SERVER['REMOTE_ADDR'];
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $logtime = $date.' '.$time;
    $lastcom = mysql_query("SELECT Date FROM Answer WHERE UserID = $username ORDER BY Date DESC LIMIT 1");
    $findlastcom = mysql_result($lastcom,0);
    if (strtotime($logtime) - strtotime($findlastcom) < 30) {
        mysql_query("INSERT INTO Log(ID, UserID, Action, Time, IP) VALUES('', '$username', 'Posted answer within 30 seconds of last answer', '$logtime', '$ipforlog')");
        echo 'You can only post a answer every 30 seconds<br/>';
        ;
    } else {
        mysql_query("INSERT INTO Log(ID, UserID, Action, Time, IP) VALUES('', '$username', 'Posted answer on $id', '$logtime', '$ipforlog')");
        $comadd = "INSERT INTO Answer (QuestionID, UserID, Date, Content, Parent) values ($id, '$username', '$ledit', '$codecontenttrim', '1')";
        mysql_query($comadd) or die(mysql_error());
        $titleofcode = mysql_result(mysql_query("SELECT Title FROM Question WHERE QuestionID = '$id'"),0);
        $idmail = mysql_result(mysql_query("SELECT UserID FROM Question WHERE QuestionID = '$id'"),0);
        $findrequests45 = mysql_query("SELECT * FROM User WHERE ID = $idmail");
$data45 = mysql_fetch_array($findrequests45);
$notifyon = $data45['Notifications'];
if ($notifyon == 1) {
$to = $data45['Email'];
 $subject = "New answer at SourceCodeDb.com";
 $body = "You have received a new answer from ".$tusername.".\n
 ------------------------------------------- \n
 Code: ".$titleofcode." \n
 
 Answer: ".$codecontenttrim." \n
 
 ------------------------------------------- \n
 Regards, 
 The SourceCodeDB Team \n
 
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
        $tmessage = $tusername.' just answered on http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    
$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
  'status' => $tmessage
));
        UpdateMonthlyRatings();
        UpdateOverallRatings();
        echo 'Answer submitted!<br/>';
        echo '<BR /><BR />';
        echo $codecontenttrim;
        echo '<BR /><BR />';
    }
}

function displayanswers($cd, $id, $sqlresult) {
            while ($answerdata = mysql_fetch_array($cd)) {
                $oDate = strtotime($answerdata['Date']);
                $sDate = date("F jS, Y ",$oDate);
                $sTime = date("h:ia",$oDate);
                $comcount = $answerdata['ChildLvl'];
                echo '<table class="comment" style="background:#F2F2F2;width:';
                if ($comcount > 0) {
                $subsize = $comcount*30;
                $size = 710-$subsize;
                echo $size.'px; margin-left:'.$subsize.'px;';
                }
                else {
                echo '710px;';
                }
                echo '"><tr><td><span style="font-size:1.3em;font-weight:bold;color:#666;
		padding-left:10px;">';
                $usercomid = $answerdata['UserID'];
                $findusr = "SELECT Username FROM User WHERE ID = $usercomid";
                $usercomq = mysql_query($findusr);
                $findavatar = "SELECT Location FROM Avatar WHERE UserID = $usercomid";
                $useravatar = mysql_query($findavatar);
                $avatarlink = mysql_result($useravatar, 0);
                if ($avatarlink != NULL) {
                    echo '<img src="';
                    echo $avatarlink;
                    echo '" border="0"> ';
                }
                if ($usercomid != $_SESSION['ID'] && $_SESSION['ID']) {
                    echo ' <a href="sendmessage.php?p=';
                    echo $usercomid;
                    echo '"> <img src="images/email_icon.gif" border="0"> </a> ';
                }
                echo '<a href="http://sourcecodedb.com/';
                echo mysql_result($usercomq, 0);
                echo '.htm">';
                echo mysql_result($usercomq, 0);
                echo '</a>';
                echo '</span>';
                
                echo '</td></tr><tr><td style="padding-left:10px;"><span style="font-size:x-small">';
                echo $sDate ."at ".$sTime;
                echo '</span></td></tr><tr><td style="padding-left:20px;padding-top:15px;padding-bottom:10px;padding-right:10px;"><span style="color:#666;"> ';
                echo nl2br(stripslashes($answerdata['Content']));
                echo '</span></td>';
                if ($_SESSION['ID'] == $answerdata['UserID'] || $sqlresult >= 5) {
                    echo '</tr><tr><td><form action="';
                    echo '#';
                    echo '" method="post"><input type="hidden" name="commentid" value="';
                    echo $answerdata['ID'];
                    echo '"><input type="hidden" name="deletecomment" value="Y">';
                    echo '<input type="submit" name"submit" value="Delete"';
                    echo ' onClick="';
                    echo 'return confirm("';
                    echo 'Are you sure you want to delete this code?';
                    echo '");';
                    echo '"></form>';
                    $commodid = $answerdata['ID'];
                    $moderated = mysql_query("SELECT Moderated FROM Answer WHERE ID = $commodid");
                    if (mysql_result($moderated,0) == 0 && $sqlresult >= 5) {
                        echo '</td><td><form method="post" action="ban.php?p=';
                        echo $usercomid;
                        echo '&link=http://';
                        echo $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                        echo '"><input type="hidden" name="ban" value="Y"><input type="submit" name"submit" value="Ban User"></form>';
                        echo '</td><td><form method="post" action="warn.php?p=';
                        echo $usercomid;
                        echo '&link=http://';
                        echo $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                        echo '"><input type="hidden" name="warn" value="Y"><input type="submit" name"submit" value="Warn User"></form>';
                        echo '</td><td><form method="post" action="';
                        echo '#';
                        echo '"><input type="hidden" name="moderated" value="Y"><input type="hidden" name="comid" value="';
                        echo $answerdata['ID'];
                        echo '"><input type="submit" name"submit" value="Approve"></form>';
                    }
                    echo '</td>';
                }
                echo '</tr><tr>';
                echo '<td>';
                $commentid = $answerdata['ID'];
                if ($_SESSION['Type'] == 'user' && $comcount < 6) {
                	echo '<input type="button" value="';
			echo 'Reply';
			echo '" onclick="reply(';
			echo "'".$answerdata['ID']."'";
			echo ');"/>';
			echo '<div id="'.$commentid.'" style="display: none;">';
			?>
			<form action="#" method="post">
			<input type="hidden" name="childans" value="Y">
			<input type="hidden" name="childcomid" value="<?php echo $commentid; ?>">
			<textarea rows="10" cols="50" name="content" /></textarea><br />
			<input type="submit" value="Submit" />
			</form>
			<?php
			echo '</div>';
                }
                echo '</td></tr>';
                echo '</table><br>'; 
                $findreplies = mysql_query("SELECT * FROM Answer WHERE QuestionID = ".$id." AND Parent = ".$commentid);
                if (mysql_result($findreplies,0) != 0) {
                $findreplies2 = mysql_query("SELECT * FROM Answer WHERE QuestionID = ".$id." AND Parent = ".$commentid." ORDER BY ID ASC");
                echo displayanswers($findreplies2, $id, $sqlresult);
                }
                $comcount=0;
                }
            }



$sqlquery = mysql_query("SELECT PermissionID FROM User WHERE ID = $username");
$sqlresult = mysql_result($sqlquery,0);
if ($sqlresult >= 5) {
if (isset($_POST['approve'])) {
	mysql_query("UPDATE Question SET Approved='1' WHERE ID = $id");
	$ipforlog = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d"); 
	$time = date("G:i:s"); 
	$logtime = $date.' '.$time;
	mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Approved Question: $title', '$logtime', '$ipforlog')");
	echo '<b>';
	echo 'Success';
	echo '</b><br /><br />';
}
echo '<b>';
echo 'Title: ';
echo $data['Title'];
echo '<br /></b>';
echo 'Date';
echo ': ';
echo $data['Date'];
echo '<br />';
echo 'By';
echo ': ';
$theuserid = $data['UserID'];
$selectuser = mysql_query("SELECT Username FROM User WHERE ID = $theuserid");
echo '<a href="http://sourcecodedb.com/';
echo mysql_result($selectuser,0);
echo '.htm">';
echo mysql_result($selectuser,0);
echo '</a>';
echo '<br />';
echo '<br /><b>';
echo 'Question';
echo ': </b><br />';
echo $data['Content'];
echo '<br /><br />';
if ($data['Approved'] == '1') {
echo '<B>';
echo 'This question is approved';
echo '</B>';
}
else {
if ($_POST['approve'] == 'Y') {
echo '<B>';
echo 'This question is approved';
echo '</B>';
}
else {
echo '<form method="post" action="viewquestion.php?p=';
echo $id;
echo '">';
echo '<input type="hidden" name="approve" value="Y">';
echo '<input type="submit" name="submit" value="Approve">';
echo '</form>';
}
}


}
else {
echo '<b>';
echo 'Title';
echo ': ';
echo $data['Title'];
echo '<br /></b>';
echo 'Date';
echo ': ';
echo $data['Date'];
echo '<br />';
echo 'By';
echo ': ';
$theuserid = $data['UserID'];
$selectuser = mysql_query("SELECT Username FROM User WHERE ID = $theuserid");
echo mysql_result($selectuser,0);
echo '<br /><b>';
echo 'Question';
echo ': </b><br />';
echo $data['Content'];
echo '<br />';
}

$answerdisplay = "SELECT * FROM Answer WHERE QuestionID = ".$id." AND Parent = 0 ORDER BY ID ASC";
$ad = mysql_query($answerdisplay) or die(mysql_error());
echo displayanswers($ad, $id, $sqlresult);

echo '<br/><br/><b>Answers</b><br/><br/>';

if ($_SESSION['Type'] == 'user') {
                ?>
                <form action="#" method="post">
                <b><?php echo 'Add answer';
                ?></b><BR />
                <textarea name="code" rows="10" cols="85"></textarea><br>
                <input type="hidden" name="answeradd" value="Y">
                <input type="submit" name="submit" value="Submit">
                </form>
                <BR />
                <BR />
                <?php
            } else if ($_SESSION['Type'] != 'user') {
                echo '<BR><BR>';
                echo 'You must be logged in to Answer';
                echo '<BR /><BR />';
            } else {
                echo 'something is wrong';
            }

include_once('footer.php');
?>