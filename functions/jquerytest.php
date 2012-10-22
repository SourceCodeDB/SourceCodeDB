<?php
include("db.php");

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

//Deklarera alla värden som skickats till den här phpfilen
$Csharp = $_POST['Csharp'];
$Java = $_POST['Java'];
$PHP = $_POST['PHP'];
$Cplusplus = $_POST['Cplusplus'];
$ASP = $_POST['ASP'];
$ObjectiveC = $_POST['ObjectiveC'];
$HTML = $_POST['HTML'];
$JS = $_POST['JS'];
$VB = $_POST['VB'];
$C = $_POST['C'];
$SQL = $_POST['SQL'];
$Python = $_POST['Python'];
$PERL = $_POST['PERL'];

$Lett = $_POST['Lett'];
$Svar = $_POST['Svar'];
$Search = $_POST['Search'];
$Arrow = $_POST['Arrow'];

$Sortby = substr($Arrow,0, strpos($Arrow, ','));
$Arrow = substr($Arrow, strpos($Arrow,',')+1);
//Detta håller reda på vad som är iklickat i filtret och ska därför visas i listan
if ($Csharp == "JA")
{
$lang1 = 1;
}
if ($PHP == "JA")
{
$lang3 = 2;
}
if ($Cplusplus == "JA")
{
$lang4 = 3;
}
if ($Java == "JA")
{
$lang2 = 4;
}
if ($ASP == "JA")
{
$lang5 = 5;
}
if ($ObjectiveC == "JA")
{
$lang6 = 6;
}
if ($HTML == "JA")
{
$lang7 = 7;
}
if ($JS == "JA")
{
$lang8 = 8;
}
if ($VB == "JA")
{
$lang9 = 9;
}
if ($C == "JA")
{
$lang10 = 10;
}
if ($SQL == "JA")
{
$lang11 = 11;
}
if ($Python == "JA")
{
$lang12 = 12;
}
if ($PERL == "JA")
{
$lang13 = 13;
}


if($Csharp == "NEJ" && $PHP == "NEJ" && $Cplusplus == "NEJ" && $Java == "NEJ" && $ASP == "NEJ" && $ObjectiveC == "NEJ"  && $HTML == "NEJ" && $JS == "NEJ" && $VB == "NEJ" && $Search!="")
{
	$lang1=1;
	$lang2=2;
	$lang3=3;
	$lang4=4;
	$lang5=5;
	$lang6=6;
	$lang7=7;
	$lang8=8;
	$lang9=9;
	$lang10=10;
	$lang11=11;
	$lang12=12;
	$lang13=13;
}

if ($Lett == "JA")
{
$dif1 = 1;
}
if ($Svar == "JA")
{
$dif3 = 3;
}
if($Search!="")
{
$searchString = "And (Title LIKE '%$Search%' OR Username LIKE '%$Search%' OR CodeInfo.Description LIKE '%$Search%')";
}

//Vanlig textsträng med ett mysql kommando
$koder = "Select Title, Difficulty, Language, Username, User.ID, Category, Views, CodeInfo.Date, CodeInfo.Url, CodeInfo.LEdit, CodeInfo.StringID, CodeInfo.Description, Published, AVG(Score), COUNT(Distinct Comments.ID)
FROM CodeInfo
Left JOIN Rating 
ON CodeInfo.StringID=Rating.StringID 
Left Join User ON CodeInfo.UserID=User.ID
Left Join Language ON CodeInfo.LanguageID=Language.ID
Left Join Difficulty ON CodeInfo.DifficultyID=Difficulty.ID
Left Join Category ON CodeInfo.CategoryID=Category.ID
LEFT JOIN Comments ON Comments.StringID = CodeInfo.StringID
WHERE (LanguageID='".$lang1."' or LanguageID='".$lang2."' or LanguageID='".$lang3."' or LanguageID='".$lang4."' or LanguageID='".$lang5."' or LanguageID='".$lang6."' or LanguageID='".$lang7."' or LanguageID='".$lang8."' or LanguageID='".$lang9."' or LanguageID='".$lang10."' or LanguageID='".$lang11."' or LanguageID='".$lang12."' or LanguageID='".$lang13."')
And (DifficultyID='".$dif1."' or DifficultyID='".$dif3."')
And Published=1 ".$searchString."
Group by Title, Language, Category, Date, Difficulty, Username , Views
ORDER BY ".$Sortby." ".$Arrow;
// or DifficultyID='".$dif2."'

//Hämta all data från mysql som användaren har valt att visas
$kodinformation = mysql_query($koder);

?>
<!--gör en tabell av all data som hämtas ifrån databasen-->
<table cellspacing="0px">
<?php
$k = "#FBFBFB";
//Kolla igenom alla värden i databasen och skriv ut alla värden som stämmer överens med sql frågan
while($codeinfo = mysql_fetch_array($kodinformation))
{

$date = strtotime($codeinfo['LEdit']);
$date = date("Y-m-d", $date);
$url = $codeinfo['Url'];

echo "<tr><td><table bgcolor='$k'><tr><td width='295px'><a href='/".$url.".html' onclick='addView(".$codeinfo['StringID'].")'>".$codeinfo['Title']."</a></td><td width='85px' bgcolor='$k'>".$codeinfo['Difficulty']."</td><td width='120px' bgcolor='$k'>".$codeinfo['Language']."</td><td width='120px' bgcolor='$k'><a href='".$codeinfo['Username'].".html'>".$codeinfo['Username']."</a></td><td width='90px' bgcolor='$k'>".$codeinfo['Category']."</td><td width='90px' bgcolor='$k'>".$date."</td><td width='70px' bgcolor='$k'>".$codeinfo['Views']."</td><td width='65px' bgcolor='$k'>".number_format($codeinfo['AVG(Score)'],2)."</td><td width='65px' bgcolor='$k'>".$codeinfo['COUNT(Distinct Comments.ID)']."</td></tr>";
echo "<tr><td colspan='9'><span style='margin-left:40px'>".LimitText($codeinfo['Description'],150)."</span></td></tr>";
echo "</table></td></tr>";
if($k=="#E2E2E2")
$k="#FBFBFB";
else
$k="#E2E2E2";
}
echo '</table>';
?>
