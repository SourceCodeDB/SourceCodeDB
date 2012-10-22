<span class="news-title"><?php echo 'News'; ?></span>

<?php
//Bestämmer vad som ska hämtas från Mysql och sortera efter datum i fallande ordning
$nyheter = "SELECT Title, Content, Created, Username FROM News, User WHERE News.UserID=User.ID ORDER BY Created DESC LIMIT 1";

//Hämtar data till variabeln från mysql som är beskrivet ovanför
$nyhetsinfo = mysql_query($nyheter);

//Variabel till loopen som listar upp dem 5 senaste inlagda koderna

//Loopar dem 5 senaste inlagda koderna
while($newsinfo = mysql_fetch_array($nyhetsinfo))
{
$oDate = strtotime($newsinfo['Created']);
$body = $newsinfo['Content'];
$body = LimitText($body,300);
$body = nl2br($body);
$title = $newsinfo['Title'];
$username = $newsinfo['Username'];
$sDate = date("M jS, Y ",$oDate);
$sTime = date("h:ia ",$oDate);

echo '<div class="news-body">';
echo '<table><tr><td width="350px" align="left" colspan="2"><a href="news.php"><b>'.$title.'</b></a></td>';
echo '</tr><tr><td width="50px" align="left">'.$username.'</td>';
echo '<td width="275px" align="right">'.$sDate.' at '.$sTime.'</td>';
echo '</tr><tr>';
echo '<td colspan="2">'.$body.'</td>';
echo '</tr><tr><td></td><td align="right"><a href="news.php">See more news</a></td>';
echo '</tr></table><BR/><BR/>';
echo '</div>';
}
?>
