<?php
include_once('header.php');
include_once('functions/createzip.php');
require('functions/_drawrating.php');

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

if (isset($_GET['title'])) {
	$urltitle = $_GET['title'];
}
else {
	$urltitle="";
}
if ($urltitle != "") {
$idquery = mysql_query("SELECT StringID FROM CodeInfo WHERE Url = '$urltitle' AND IsTemp!='1'");
if (mysql_num_rows($idquery) > 0) {
$id = mysql_result($idquery,0);
}
else {
    $id = 0;
}

$viewsquery = mysql_query("SELECT Views FROM CodeInfo WHERE Url = '$urltitle' AND IsTemp!='1'");
if (mysql_num_rows($viewsquery) > 0) {
$views = mysql_result($viewsquery,0);
$views++;
mysql_query("UPDATE CodeInfo SET Views = '$views' WHERE StringID = '$id'");
}

if (isset($_SESSION['ID'])) {
	$username = $_SESSION['ID'];
}
else {
	$username = 0;
}
if (isset($_SESSION['Username'])) {
$tusername = $_SESSION['Username'];
}
else {
	$tusername = "";
}
$sqlquery = mysql_query("SELECT PermissionID FROM User WHERE ID = $username");
if (mysql_num_rows($sqlquery) > 0) {
$sqlresult = mysql_result($sqlquery,0);
}
else { 
    $sqlresult = 0;
}
$favcheck = "Select * from Bookmarks Where UserID='".$username."'AND StringID='".$id."'";
$favret = mysql_query($favcheck) or die(mysql_error());
$rows= mysql_num_rows($favret);
include_once('code/geshi.php');
if (isset($_POST['moderated']) && $sqlresult >= 5) {
    $comidpost = mysql_real_escape_string($_POST['comid']);
    mysql_query("UPDATE Comments SET Moderated=1 WHERE ID = $comidpost");
    $ipforlog = $_SERVER['REMOTE_ADDR'];
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $logtime = $date.' '.$time;
    mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Approved Comment $comidpost', '$logtime', '$ipforlog')");
    echo 'Comment Approved';
}
if (isset($_POST['moderatedcode']) && $sqlresult >= 5) {
    mysql_query("UPDATE CodeInfo SET Moderated=1 WHERE StringID = $id");
    $ipforlog = $_SERVER['REMOTE_ADDR'];
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $logtime = $date.' '.$time;
    mysql_query("INSERT INTO Log (ID, UserID, Action, Time, IP) VALUES ('', '$username', 'Approved Code $id', '$logtime', '$ipforlog')");
    echo 'Code approved<br/>';
    $findproject = mysql_query("SELECT * FROM CodeContent WHERE StringID = '$id'");
    $projectcount = mysql_num_rows($findproject);
    if ($projectcount > 1) {
    mkdir('project');
    $proji=0;
    while ($projdata = mysql_fetch_array($findproject)) {
    $projsource = $projdata['Content'];
    $projtitle = $projdata['Title'];
    
    //Create a temporary directory
    
    $myFile = "project/".$projtitle;
    $fh = fopen($myFile, 'w') or die("can't open file");
    fwrite($fh, $projsource);
    fclose($fh);
    $files_to_zip[$proji] = $myFile;
    $proji++;
    }
    
    if (!is_dir('downloads')) {
    	mkdir('downloads');
    }
    
    $zip = "downloads/".$id.".zip";
    if (file_exists($zip)) {
    	unlink($zip);
    }
    //if true, good; if false, zip creation failed
    $zipresult = create_zip($files_to_zip,$zip);
    
    //remove files
    foreach ($files_to_zip as $tmpfile) {
    	unlink($tmpfile);
    }
    
    //Delete the temporary directory
    rmdir('project');
    echo 'zip file created';
    }
}
if (isset($_POST['commentadd'])) {
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $ledit = $date.' '.$time;
    $table4 = "Comments";
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
    $lastcom = mysql_query("SELECT LEdit FROM Comments WHERE UserID = $username ORDER BY LEdit DESC LIMIT 1");
    $findlastcom = mysql_result($lastcom,0);
    if (strtotime($logtime) - strtotime($findlastcom) < 30) {
        mysql_query("INSERT INTO Log(ID, UserID, Action, Time, IP) VALUES('', '$username', 'Posted comment within 30 seconds of last comment', '$logtime', '$ipforlog')");
        echo 'You can only post a comment every 30 seconds';
        ;
    } else {
        mysql_query("INSERT INTO Log(ID, UserID, Action, Time, IP) VALUES('', '$username', 'Posted comment on $id', '$logtime', '$ipforlog')");
        $comadd = "INSERT INTO $table4 (StringID, UserID, Date, Content, LEdit) values ($id, '$username', '$ledit', '$codecontenttrim', '$ledit')";
        mysql_query($comadd) or die(mysql_error());
        $titleofcode = mysql_result(mysql_query("SELECT Title FROM CodeInfo WHERE StringID = '$id'"),0);
        $idmail = mysql_result(mysql_query("SELECT UserID FROM CodeInfo WHERE StringID = '$id'"),0);
        $findrequests45 = mysql_query("SELECT * FROM User WHERE ID = $idmail");
$data45 = mysql_fetch_array($findrequests45);
$notifyon = $data45['Notifications'];
if ($notifyon == 1) {
$to = $data45['Email'];
 $subject = "New Comment at SourceCodeDb.com";
 $body = "You have received a new comment from ".$tusername.".\n
 ------------------------------------------- \n
 Code: ".$titleofcode." \n
 
 Comment: ".$codecontenttrim." \n
 
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
//post to twitter

$tmessage = $tusername.' just commented on http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
  'status' => $tmessage
));

      
        $parts=parse_url('http://sourcecodedb.com/functions/update_ratings.php');
 
        $fp = fsockopen($parts['host'],
        isset($parts['port'])?$parts['port']:80,
        $errno, $errstr, 30);
 
        if (!$fp) {
        } else {
            $out = "POST ".$parts['path']." HTTP/1.1\r\n";
            $out.= "Host: ".$parts['host']."\r\n";
            $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out.= "Content-Length: ".strlen($parts['query'])."\r\n";
            $out.= "Connection: Close\r\n\r\n";
            if (isset($parts['query'])) $out.= $parts['query'];

            fwrite($fp, $out);
            fclose($fp);
        }
        
        echo 'Comment submitted!';
        echo '<BR /><BR />';
        echo $codecontenttrim;
    }
}
if (isset($_POST['childcom'])) {
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $ledit = $date.' '.$time;
    $table4 = "Comments";
    $codecontent = mysql_real_escape_string($_POST['content']);
    $childcomid = mysql_real_escape_string($_POST['childcomid']);
    $childcomlvl = mysql_result(mysql_query("SELECT ChildLvl FROM Comments WHERE ID = $childcomid"),0);
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
    $lastcom = mysql_query("SELECT LEdit FROM Comments WHERE UserID = $username ORDER BY LEdit DESC LIMIT 1");
    $findlastcom = mysql_result($lastcom,0);
    if (strtotime($logtime) - strtotime($findlastcom) < 30) {
        mysql_query("INSERT INTO Log(ID, UserID, Action, Time, IP) VALUES('', '$username', 'Posted comment within 30 seconds of last comment', '$logtime', '$ipforlog')");
        echo 'You can only post a comment every 30 seconds';
        ;
    } else {
        mysql_query("INSERT INTO Log(ID, UserID, Action, Time, IP) VALUES('', '$username', 'Replied to comment on $id', '$logtime', '$ipforlog')");
        $comadd = "INSERT INTO $table4 (StringID, UserID, Date, Content, LEdit, Parent, ChildLvl) values ($id, '$username', '$ledit', '$codecontenttrim', '$ledit', '$childcomid', '$childcomlvl')";
        mysql_query($comadd) or die(mysql_error());
        
        $tmessage = $tusername.' just commented on http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
  'status' => $tmessage
));


        
        echo 'Comment submitted!';
        echo '<BR /><BR />';
        echo $codecontenttrim;
    }
}
if (isset($_POST['deletecomment'])) {
    $commentid = mysql_real_escape_string($_POST['commentid']);
    $comdel = "DELETE FROM Comments where ID = ".$commentid;
    
    mysql_query($comdel) or die(mysql_error());
    echo 'Comment deleted!';
    echo '<BR /><BR />';
}
if (isset($_POST['favdeladd'])) {
    if ($rows >0) {
        $favdel = "DELETE FROM Bookmarks where UserID='".$username."'AND StringID='".$id."'";
        
        mysql_query($favdel) or die(mysql_error());
        echo 'Bookmark deleted!';
        echo '<BR /><BR />';
    } else {
    	$date = date("Y-m-d");
    	$time = date("G:i:s");
    	$logtime = $date.' '.$time;
        $favadd = "INSERT INTO Bookmarks (UserID, StringID, Date) values ('".$username."', '".$id."', '".$logtime."')";
        
        mysql_query($favadd) or die(mysql_error());
        echo 'Bookmark saved!';
        echo '<BR /><BR />';
    }
    $favret = mysql_query($favcheck) or die(mysql_error());
    $rows= mysql_num_rows($favret);
}
$table = "CodeContent";
$table2 = "CodeInfo";
$table3 = "Language";
$table4 = "Comments";
$req = "SELECT * from $table where StringID = '$id' AND IsTemp!='1'";
$res = mysql_query($req);
$data3 = mysql_fetch_array($res);
$source = $data3['Content'];
$contentsource = $data3['Source'];
$req2 = "SELECT * from $table2 where StringID = '$id' AND IsTemp!='1'";
$res2 = mysql_query($req2);
$data = mysql_fetch_array($res2);
$theuserid = $data['UserID'];
$langid = $data['LanguageID'];
$diffid = $data['DifficultyID'];
$description = $data['Description'];
$title = $data['Title'];
$lastedit = $data['LEdit'];
$tags = $data['Tags'];
if ($diffid == 1) {
    $difficulty = 'Easy';
} else {
    $difficulty = 'Hard';
}
$req3 = "SELECT * from $table3 where ID = '$langid'";
$res3 = mysql_query($req3);
$data2 = mysql_fetch_array($res3);
$language = $data2['GeshiName'];
$languagename = $data2['Language'];
$usrcheck5 = "Select * from User Where ID = '$theuserid'";
$user5 = mysql_query($usrcheck5) or die(mysql_error());
$request6 = mysql_fetch_array($user5);
$user6 = $request6['Username'];
$usertitlequery = mysql_query("Select Title from UserRank Where UserID = '$theuserid'");
if (mysql_num_rows($usertitlequery) > 0) {
$usertitle = mysql_result($usertitlequery,0);
}
else {
    $usertitle = "";
}
function displaycomments($cd, $id, $sqlresult) {
            while ($commentdata = mysql_fetch_array($cd)) {
                $oDate = strtotime($commentdata['Date']);
                $sDate = date("F jS, Y ",$oDate);
                $sTime = date("h:ia",$oDate);
                $comcount = $commentdata['ChildLvl'];
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
                $usercomid = $commentdata['UserID'];
                $findusr = "SELECT Username FROM User WHERE ID = $usercomid";
                $usercomq = mysql_query($findusr);
                $findavatar = "SELECT Location FROM Avatar WHERE UserID = $usercomid";
                $useravatar = mysql_query($findavatar);
                if (mysql_num_rows($useravatar) > 0) {
                $avatarlink = mysql_result($useravatar, 0);
                }
                if (isset($avatarlink)) {
                    echo '<img src="';
                    echo $avatarlink;
                    echo '" border="0"> ';
                }
                if (isset($_SESSION['ID']) && $usercomid != $_SESSION['ID']) {
                    echo ' <a href="sendmessage.php?p=';
                    echo $usercomid;
                    echo '"> <img src="images/email_icon.gif" border="0"> </a> ';
                }
                echo '<a href="http://sourcecodedb.com/';
                echo mysql_result($usercomq, 0);
                echo '.htm">';
                echo mysql_result($usercomq, 0);
                $usercomtitle = mysql_result(mysql_query("Select Title from UserRank Where UserID = '$usercomid'"),0);
                echo '</a> - '.$usercomtitle;
                echo '</span>';
                
                echo '</td></tr><tr><td style="padding-left:10px;"><span style="font-size:x-small">';
                echo $sDate ."at ".$sTime;
                echo '</span></td></tr><tr><td style="padding-left:20px;padding-top:15px;padding-bottom:10px;padding-right:10px;"><span style="color:#666;"> ';
                echo nl2br(stripslashes($commentdata['Content']));
                echo '</span></td>';
                if (isset($_SESSION['ID']) && ($_SESSION['ID'] == $commentdata['UserID'] || $sqlresult >= 5)) {
                    echo '</tr><tr><td><form action="';
                    echo '#';
                    echo '" method="post"><input type="hidden" name="commentid" value="';
                    echo $commentdata['ID'];
                    echo '"><input type="hidden" name="deletecomment" value="Y">';
                    echo '<input type="submit" name"submit" value="Delete"';
                    echo ' onClick="';
                    echo 'return confirm("';
                    echo 'Are you sure you want to delete this code?';
                    echo '");';
                    echo '"></form>';
                    $commodid = $commentdata['ID'];
                    $moderated = mysql_query("SELECT Moderated FROM Comments WHERE ID = $commodid");
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
                        echo $commentdata['ID'];
                        echo '"><input type="submit" name"submit" value="Approve"></form>';
                    }
                    echo '</td>';
                }
                echo '</tr><tr>';
                echo '<td>';
                $commentid = $commentdata['ID'];
                if (isset($_SESSION['Type']) && $comcount < 6) {
                	echo '<input type="button" value="';
			echo 'Reply';
			echo '" onclick="reply(';
			echo "'".$commentdata['ID']."'";
			echo ');"/>';
			echo '<div id="'.$commentid.'" style="display: none;">';
			?>
			<form action="#" method="post">
			<input type="hidden" name="childcom" value="Y">
			<input type="hidden" name="childcomid" value="<?php echo $commentid; ?>">
			<textarea rows="10" cols="50" name="content" /></textarea><br />
			<input type="submit" value="Submit" />
			</form>
			<?php
			echo '</div>';
                }
                echo '</td></tr>';
                echo '</table><br>'; 
                $findreplies = mysql_query("SELECT * FROM Comments WHERE StringID = ".$id." AND Parent = ".$commentid);
                if (mysql_num_rows($findreplies) > 0) {
                $findreplies2 = mysql_query("SELECT * FROM Comments WHERE StringID = ".$id." AND Parent = ".$commentid." ORDER BY ID ASC");
                echo displaycomments($findreplies2, $id, $sqlresult);
                }
                $comcount=0;
                }
            }
if (isset($_POST['deletecode']) && $theuserid == $_SESSION['ID']) {
    mysql_query("DELETE CodeInfo FROM CodeInfo WHERE StringID = $id");
    mysql_query("DELETE CodeContent FROM CodeContent WHERE StringID = $id");
    $ipforlog = $_SERVER['REMOTE_ADDR'];
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $logtime = $date.' '.$time;
    mysql_query("INSERT INTO Log(ID, UserID, Action, Time, IP) VALUES('', '$username', 'Deleted Code $id', '$logtime', '$ipforlog')");
    echo 'Code Deleted';
} else if (isset($_POST['deletecode']) && $sqlresult >= 5) {
    mysql_query("DELETE CodeInfo FROM CodeInfo WHERE StringID = $id");
    mysql_query("DELETE CodeContent FROM CodeContent WHERE StringID = $id");
    $ipforlog = $_SERVER['REMOTE_ADDR'];
    $date = date("Y-m-d");
    $time = date("G:i:s");
    $logtime = $date.' '.$time;
    mysql_query("INSERT INTO Log(ID, UserID, Action, Time, IP) VALUES('', '$username', 'Deleted Code $id', '$logtime', '$ipforlog')");
    echo 'Code Deleted';
} else {
    $geshi = new GeSHi($source, $language);
    
    $geshi->set_header_type(GESHI_HEADER_DIV);
    $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 999999);
    $geshi->enable_classes();
    if (mysql_num_rows($res) != 0) {
        if ((isset($_SESSION['ID']) && ($_SESSION['ID'] == $data['UserID'] && $data['Published'] == 0)) || $sqlresult >= 5) {
            //HÃ¤r bÃ¶rjar fÃ¶rsta versionen av displaycode
            echo '<div>';
            echo '<h2> ' . $title . '</h2>';
            echo '<table><tr><td width="400px">';
            echo '<h4> ';
            echo 'Author';
            echo ': ';
            echo '<a href="http://sourcecodedb.com/';
            echo $user6;
            echo '.htm">';
            echo $user6;
            echo '</a>';
            echo ' - '.$usertitle.'</h4>';
            $findavatar3 = "SELECT Location FROM Avatar WHERE UserID = '$theuserid'";
            $useravatar3 = mysql_query($findavatar3);
            if (mysql_num_rows($useravatar3) > 0) { 
            $avatarlink3 = mysql_result($useravatar3, 0);
            }
            if ($avatarlink3 != NULL) {
                echo '<img src="';
                echo $avatarlink3;
                echo '" border="0"> ';
            }
            if ($theuserid != $_SESSION['ID'] && $_SESSION['ID']) {
                echo ' <a href="sendmessage.php?p=';
                echo $theuserid;
                echo '"> <img src="images/email_icon.gif" border="0"></a>';
            }
            echo '</td><td>';
            echo '<table id="panel"><tr><td width="200px">';
            echo rating_bar($id,'5');
            echo '</td>';
            
            
            
            if (isset($_SESSION['Type'])) {
                echo '<td><form action="#" method="post">';
                echo '<input type="hidden" name="favdeladd" value="Y">';
                if ($rows>0 ) {
                    echo '<input type="image" src="stylesheets/images/Bookmark-del-icon.png" border="0" align="right" alt="Delete Favourite"/>';
                } else {
                    echo '<input type="image" src="stylesheets/images/Bookmark-add-icon.png" border="0" align="right" alt="Add Favourite"/>';
                }
                echo '</form></td>';
            }
            
            echo '</tr></table>';
            echo '</td></tr></table>';
            echo '<br />';
            echo 'Language';
            echo ': ';
            echo $languagename;
            echo ' | ';
            echo 'Difficulty';
            echo ': ';
            echo $difficulty;
            echo ' | ';
            echo 'Last Edited';
            echo ': ';
            echo $lastedit;
            
            if (mysql_num_rows($res) > 1) {
            	$filename = 'downloads/'.$id.'.zip';

		if (file_exists($filename)) {
			echo "<br/>";
   			echo '<a href="downloads/'.$id.'.zip">Download all files in the project</a>';
		} else {
			echo "<br/>";
    			echo "Files are pending approval";
		}
            }
            if (strlen($contentsource) > 1) {
                echo '<br />';
                echo '<b>';
                echo 'Source';
                echo ': ';
                echo '<a href="';
                echo $contentsource;
                echo '">';
                echo $contentsource;
                echo '</a>';
                echo '</b>';
            }
            echo '<br /><br />';
            echo '<div style="font-size:1.2em;font-style:italic;"> ' . nl2br(stripslashes($description)) . '</div><BR />';
            if (mysql_num_rows($res) > 1) {
                echo 'This is a project, click on a file below to view the source';
                echo '<br /><br />';
                $filecount=1;
                $reqk = "SELECT * from $table where StringID = '$id'";
                $resk = mysql_query($reqk);
                while ($findfiles = mysql_fetch_array($resk)) {
                    $filename = $findfiles['Title'];
                    $filedesc = $findfiles['Description'];
                    $filesource = $findfiles['Content'];
                    $revisionnumber = $findfiles['Revision'];
                    $pagenumber = $findfiles['Page'];
                    unset($geshi);
                    
                    ?>
                    <script language="javascript">
                    function toggle<?php echo $filecount;
                    ?>() {
                        var ele = document.getElementById("toggleText<?php echo $filecount; ?>");
                        var text = document.getElementById("displayText<?php echo $filecount; ?>");
                        if (ele.style.display == "block") {
                            ele.style.display = "none";
                            text.innerHTML = "<?php echo $filename; ?>";
                        } else {
                            ele.style.display = "block";
                            text.innerHTML = "<?php echo $filename; ?>";
                        }
                    }
                    </script>
                    <a id="displayText<?php echo $filecount; ?>" href="javascript:toggle<?php echo $filecount; ?>();"><?php echo $filename;
                    ?></a><br />
                    <div id="toggleText<?php echo $filecount; ?>" style="display: none">
                    <?php
                    echo '<br/>';
                    echo nl2br($filedesc);
                    echo '<br/>';
                    $geshi = new GeSHi($filesource, $language);
                    $geshi->set_header_type(GESHI_HEADER_DIV);
                    $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 999999);
                    $geshi->enable_classes();
                    echo $geshi->parse_code();
                    
                    echo 'Revision: '.$revisionnumber;
                    echo '<br/>';
                    
                    if ($revisionnumber > 1) {
                    	echo '<a href="http://sourcecodedb.com/revisions.php?p='.$pagenumber.'&c='.$id.'">View previous versions</a>';
                    	echo '<br/><br/>';
                    }
                    ?>
                    </div>
                    <?php
                    $filecount++;
                }
            } else {
                
                echo $geshi->parse_code();
                $revisionnumber2 = mysql_result(mysql_query("SELECT Revision FROM CodeContent WHERE StringID = '$id'"),0);
                echo 'Revision: '.$revisionnumber2;
                echo '<br/>';
                    
                if ($revisionnumber2 > 1) {
           	    	echo '<a href="http://sourcecodedb.com/revisions.php?p=1&c='.$id.'">View previous versions</a>';
           	    	echo '<br/>';
                }
            }
            if ($_SESSION['ID'] == $data['UserID'] || $sqlresult >= 5) {
                echo '<br /><table><tr><td><a href="editcode.php?p=';
                echo $id;
                echo '">Edit code</a></td><td><form method="post" action="';
                echo '#';
                echo '"><input type="hidden" name="deletecode" value="Y">';
                echo '<input type="submit" name"submit" value="Delete"';
                echo ' onClick="';
                echo 'return confirm("';
                echo 'Are you sure you want to delete this code?';
                echo '");';
                echo '"></form>';
                $moderated2 = mysql_query("SELECT Moderated FROM CodeInfo WHERE StringID = $id");
                if (mysql_result($moderated2,0) == 0 && $sqlresult >= 5) {
                    echo '</td><td><form method="post" action="ban.php?p=';
                    echo $theuserid;
                    echo '&link=http://';
                    echo $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                    echo '"><input type="hidden" name="ban" value="Y"><input type="submit" name"submit" value="Ban User"></form>';
                    echo '</td><td><form method="post" action="warn.php?p=';
                    echo $theuserid;
                    echo '&link=http://';
                    echo $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                    echo '"><input type="hidden" name="warn" value="Y"><input type="submit" name"submit" value="Warn User"></form>';
                    echo '</td><td><form method="post" action="';
                    echo '#';
                    echo '"><input type="hidden" name="moderatedcode" value="Y"><input type="submit" name"submit" value="Approve"></form>';
                }
                echo '</td></tr></table><br />';
            }
            if (strlen($tags) != NULL) {
                echo '<br/>';
                echo 'Tags';
                echo ': ';
                echo $tags;
            }
            
            echo '<br />';
            echo 'COMMENTS';
            echo '<BR />';
            
            $commentdisplay = "SELECT * FROM ".$table4." WHERE StringID = ".$id." AND Parent = 0 ORDER BY ID ASC";
            //FÃ¶rsta versionen av displaycode - Comments delen
            $cd = mysql_query($commentdisplay) or die(mysql_error());
            echo displaycomments($cd, $id, $sqlresult);
            
            
            
            if ($_SESSION['Type'] == 'user') {
                ?>
                <form action="#" method="post"><br />
                
                <br /><br />
                <b><?php echo 'Comment';
                ?></b><BR />
                <textarea name="code" rows="10" cols="85"></textarea><br>
                <input type="hidden" name="commentadd" value="Y">
                <input type="submit" name="submit" value="Submit">
                </form>
                <BR />
                <BR />
                <?php
            } else if ($_SESSION['Type'] != 'user') {
                echo '<BR><BR>';
                echo 'You must be logged in to comment';
                echo '<BR /><BR />';
            } else {
                echo 'something is wrong';
            }
        } else if ($data['Published'] == 0) {
            echo 'Code not published';
        } else {
            //HÃ¤r bÃ¶rjar andra versionen av displaycode
            echo '<div>';
            echo '<h2> ' . $title . '</h2>';
            echo '<table><tr><td width="400px">';
            echo '<h4> ';
            echo 'Author';
            echo ': ';
            echo '<a href="http://sourcecodedb.com/';
            echo $user6;
            echo '.htm">';
            echo $user6;
            echo '</a>';
            echo ' - '.$usertitle.'</h4>';
            $findavatar3 = "SELECT Location FROM Avatar WHERE UserID = '$theuserid'";
            $useravatar3 = mysql_query($findavatar3);
            if (mysql_num_rows($useravatar3) > 0) {
            $avatarlink3 = mysql_result($useravatar3, 0);
            }
            if (isset($avatarlink3)) {
                echo '<img src="';
                echo $avatarlink3;
                echo '" border="0"> ';
            }
            if (isset($_SESSION['ID']) && $theuserid != $_SESSION['ID']) {
                echo ' <a href="sendmessage.php?p=';
                echo $theuserid;
                echo '"> <img src="images/email_icon.gif" border="0"></a>';
            }
            echo '</td><td>';
            echo '<table id="panel"><tr><td width="200px">';
            echo rating_bar($id,'5');
            echo '</td>';
            
            
            
            if (isset($_SESSION['Type'])) {
                echo '<td><form action="#" method="post">';
                echo '<input type="hidden" name="favdeladd" value="Y">';
                if ($rows>0 ) {
                    echo '<input type="image" src="stylesheets/images/Bookmark-del-icon.png" border="0" align="right" alt="Delete Favourite"/>';
                } else {
                    echo '<input type="image" src="stylesheets/images/Bookmark-add-icon.png" border="0" align="right" alt="Add Favourite"/>';
                }
                echo '</form></td>';
            }
            
            echo '</tr></table>';
            echo '</td></tr></table>';
            echo '<br />';
            echo 'Language';
            echo ': ';
            echo $languagename;
            echo ' | ';
            echo 'Difficulty';
            echo ': ';
            echo $difficulty;
            echo ' | ';
            echo 'Last Edited';
            echo ': ';
            echo $lastedit;
            if (mysql_num_rows($res) > 1) {
            	$filename = 'downloads/'.$id.'.zip';

		if (file_exists($filename)) {
			echo "<br/>";
   			echo '<a href="downloads/'.$id.'.zip">Download all files in the project</a>';
		} else {
			echo "<br/>";
    			echo "Files are pending approval";
		}
            }
            if (strlen($contentsource) > 1) {
                echo '<br />';
                echo '<b>';
                echo 'Source';
                echo ': ';
                echo '<a href="';
                echo $contentsource;
                echo '">';
                echo $contentsource;
                echo '</a>';
                echo '</b>';
            }
            echo '<br /><br />';
            echo '<div style="font-size:1.2em;font-style:italic;"> ' . nl2br(stripslashes($description)) . '</div><BR />';
            
            if (mysql_num_rows($res) > 1) {
                echo 'This is a project, click on a file below to view the source';
                echo '<br /><br />';
                $filecount=1;
                $reqk = "SELECT * from $table where StringID = '$id'";
                $resk = mysql_query($reqk);
                while ($findfiles = mysql_fetch_array($resk)) {
                    $filename = $findfiles['Title'];
                    $filedesc = $findfiles['Description'];
                    $filesource = $findfiles['Content'];
                    $revisionnumber = $findfiles['Revision'];
                    $pagenumber = $findfiles['Page'];
                    unset($geshi);
                    
                    ?>
                    <script language="javascript">
                    function toggle<?php echo $filecount;
                    ?>() {
                        var ele = document.getElementById("toggleText<?php echo $filecount; ?>");
                        var text = document.getElementById("displayText<?php echo $filecount; ?>");
                        if (ele.style.display == "block") {
                            ele.style.display = "none";
                            text.innerHTML = "<?php echo $filename; ?>";
                        } else {
                            ele.style.display = "block";
                            text.innerHTML = "<?php echo $filename; ?>";
                        }
                    }
                    </script>
                    <a id="displayText<?php echo $filecount; ?>" href="javascript:toggle<?php echo $filecount; ?>();"><?php echo $filename;
                    ?></a><br />
                    <div id="toggleText<?php echo $filecount; ?>" style="display: none">
                    <?php
                    echo '<br/>';
                    echo nl2br($filedesc);
                    echo '<br/>';
                    $geshi = new GeSHi($filesource, $language);
                    $geshi->set_header_type(GESHI_HEADER_DIV);
                    $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 999999);
                    $geshi->enable_classes();
                    echo $geshi->parse_code();
                    
                    echo 'Revision: '.$revisionnumber;
                    echo '<br/>';
                    
                    if ($revisionnumber > 1) {
                    	echo '<a href="http://sourcecodedb.com/revisions.php?p='.$pagenumber.'&c='.$id.'">View previous versions</a>';
                    	echo '<br/><br/>';
                    }
                    ?>
                    </div>
                    <?php
                    $filecount++;
                }
            } else {
                
                echo $geshi->parse_code();
                $revisionnumber2 = mysql_result(mysql_query("SELECT Revision FROM CodeContent WHERE StringID = '$id'"),0);
                echo 'Revision: '.$revisionnumber2;
                echo '<br/>';
                    
                if ($revisionnumber2 > 1) {
           	    	echo '<a href="http://sourcecodedb.com/revisions.php?p=1&c='.$id.'">View previous versions</a>';
           	    	echo '<br/>';
                }
            }
            
            if ((isset($_SESSION['ID']) && $_SESSION['ID'] == $data['UserID']) || $sqlresult >= 5) {
                echo '<br /><table><tr><td><a href="editcode.php?p=';
                echo $id;
                echo '">Edit code</a></td><td><form method="post" action="';
                echo '#';
                echo '"><input type="hidden" name="deletecode" value="Y">';
                echo '<input type="submit" name"submit" value="Delete"';
                echo ' onClick="';
                echo 'return confirm("';
                echo 'Are you sure you want to delete this code?';
                echo '");';
                echo '"></form>';
                $moderated2 = mysql_query("SELECT Moderated FROM CodeInfo WHERE StringID = $id");
                if (mysql_result($moderated2,0) == 0 && $sqlresult >= 5) {
                    echo '</td><td><form method="post" action="ban.php?p=';
                    echo $theuserid;
                    echo '&link=http://';
                    echo $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                    echo '"><input type="hidden" name="ban" value="Y"><input type="submit" name"submit" value="Ban User"></form>';
                    echo '</td><td><form method="post" action="warn.php?p=';
                    echo $theuserid;
                    echo '&link=http://';
                    echo $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                    echo '"><input type="hidden" name="warn" value="Y"><input type="submit" name"submit" value="Warn User"></form>';
                    echo '</td><td><form method="post" action="';
                    echo '#';
                    echo '"><input type="hidden" name="moderatedcode" value="Y"><input type="submit" name"submit" value="Approve"></form>';
                }
                echo '</td></tr></table><br />';
            }
            
            echo '<br />';
            echo 'COMMENTS';
            echo '<BR />'; 
            $commentdisplay = "SELECT * FROM ".$table4." WHERE StringID = ".$id." AND Parent = 0 ORDER BY ID ASC";
            //andra versionen av displaycode - Comments Delen
            $cd = mysql_query($commentdisplay) or die(mysql_error());
            echo displaycomments($cd, $id, $sqlresult);
            
            if (isset($_SESSION['Type'])) {
                ?>
                <form action="#" method="post"><br />
                
                <br /><br />
                <b><?php echo 'Comment';
                ?></b><BR />
                <textarea name="code" rows="10" cols="85"></textarea><br>
                <input type="hidden" name="commentadd" value="Y">
                <input type="submit" name="submit" value="Submit">
                </form>
                <BR />
                <BR />
                <?php
            } else {
                echo '<BR><BR>';
                echo 'You must be logged in to comment';
                echo '<BR /><BR />';
            }
        }
    } else {
        echo 'This code is no longer here';
    }
}
} 
else {
        echo 'This code is no longer here';
}
?>
</div>
<?php
include_once('footer.php');
?>