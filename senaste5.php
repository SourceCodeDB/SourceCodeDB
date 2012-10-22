<?php
function LimitText($Text,$Max) {
   if (strlen($Text) >= $Max) {
       $words = explode(" ", $Text);
       $check=1;
       while (strlen($Text) >= $Max) {
           $c=count($words)-$check;          
           $Text=substr($Text,0,(strlen($words[$c])+1)*(-1));
           $check++;
       }
       $Text.="...";
   }
 
   return $Text;
}

//Bestämmer vad som ska hämtas från Mysql och sortera efter datum i fallande ordning
$koder = "SELECT Title, Language, Category, Date, Difficulty, Username, Views, Ledit, StringID, Published, CodeInfo.Description, Moderated, Url 
FROM CodeInfo, Language, Difficulty, User, Category 
WHERE Language.ID=CodeInfo.LanguageID 
And Difficulty.ID=CodeInfo.DifficultyID
And Category.ID=CodeInfo.CategoryID
And User.ID=CodeInfo.UserID
And Published=1
AND CodeInfo.Moderated=1
 ORDER BY Date DESC LIMIT 5";

//Hämtar data till variabeln från mysql som är beskrivet ovanför
$kodinformation = mysql_query($koder)or die(mysql_error());




?>
<!--gör en tabell av all data som hämtas ifrån databasen -->
<table cellspacing="0px">

<?php
//Variabel till loopen som listar upp dem 5 senaste inlagda koderna

//Loopar dem 5 senaste inlagda koderna
while($codeinfo = mysql_fetch_array($kodinformation))
{
$oDate = strtotime($codeinfo['Date']);
$sDate = date("M jS, Y ",$oDate);
$sTime = date("h:ia ",$oDate);
$title2 = $codeinfo['Title'];
$title = $codeinfo['Title'];
$title2 = LimitText($title2,40);
$language = $codeinfo['Language'];
$description = LimitText($codeinfo['Description'],70);
$title = $codeinfo['Url'];
//Skrver ut koden
	echo '<table><tr><td width="250px"';
	echo ' align="left" colspan="2"><a href="/'.$title.'.html">'.$title2.'</a></td>';
	echo '<td width="50px" align="left" style="font-size:0.8em;">'.$language.'</td>
<td width="70px" align="right" style="font-size:0.8em;">'.$sDate.' at '.$sTime.'</td></tr><tr>';
	echo '<td colspan="4">'.$description.'</td></tr></table>';
}
?>

</body>
</HTML>