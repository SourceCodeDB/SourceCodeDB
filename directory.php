<?php 
include_once('header.php');
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
if ($_GET['section']) {
$section = $_GET['section'];
$section = trim(htmlentities($section));
//Vanlig textsträng med ett mysql kommando
$koder = mysql_query("Select * FROM CodeInfo WHERE LanguageID = '$section' And Published='1'")or die(mysql_error());
// or DifficultyID='".$dif2."'

//Hämta all data från mysql som användaren har valt att visas


?>
<!--gör en tabell av all data som hämtas ifrån databasen-->
<table cellspacing="1px" id="sortingTable">
        <thead> 
		<tr>
		<th width="295px" id="sortByTitle" bgcolor="#000">
		<font color="white">Title</font>
		</th>
		<th width="85px" id="sortByDifficulty" bgcolor="#000">
		<font color="white">Difficulty</font>
		</th>
		<th width="120px" id="sortByLanguage" bgcolor="#000">
		<font color="white">Language</font>
		</th>
		<th width="120px" id="sortByUserID" bgcolor="#000">
		<font color="white">User</font>
		</th>
		<th width="90px" id="sortByCategory" bgcolor="#000">
		<font color="white">Category</font>
		</th>
		<th width="90px" id="sortByLastEdit" bgcolor="#000">
		<font color="white">Last edit</font>
		</th>
		<th width="70px" id="sortByViews" bgcolor="#000">
		<font color="white">Views</font>
		</th>
		<th width="65px" id="sortByAVG(Score)" bgcolor="#000">
		<font color="white">Rating</font>
		</th>
		<th width="64px" id="sortByComments" bgcolor="#000">
		<font color="white">Posts</font>
		</th>
		</tr>
        </thead> 
		</table>

<table cellspacing="1px">
<?php
$k = "#FBFBFB";
if (mysql_num_rows($koder) < 1) {
	echo 'No codes have been added to this language yet';
}
//Kolla igenom alla värden i databasen och skriv ut alla värden som stämmer överens med sql frågan
while($codeinfo = mysql_fetch_array($koder))
{

$date = $codeinfo['LEdit'];
$url = $codeinfo['Url'];
$codeid = $codeinfo['StringID'];
$userid = $codeinfo['UserID'];
$langid = $codeinfo['LanguageID'];
$diffid = $codeinfo['DifficultyID'];
$catid = $codeinfo['CategoryID'];
$username = mysql_result(mysql_query("SELECT Username FROM User WHERE ID='$userid'"),0);
$language = mysql_result(mysql_query("SELECT Language FROM Language WHERE ID='$langid'"),0);
$difficulty = mysql_result(mysql_query("SELECT Difficulty FROM Difficulty WHERE ID='$diffid'"),0);
$category = mysql_result(mysql_query("SELECT Category FROM Category WHERE ID='$catid'"),0);
$comcount = mysql_result(mysql_query("SELECT COUNT(*) FROM Comments WHERE StringID='$codeid'"),0);
$rating = mysql_result(mysql_query("SELECT AVG(Score) FROM Rating WHERE StringID='$codeid'"),0);

echo "<tr><td><table bgcolor='$k'><tr><td width='295px'><a href='/".$url.".html' onclick='addView(".$codeinfo['StringID'].")'>".$codeinfo['Title']."</a></td><td width='85px' bgcolor='$k'>".$difficulty."</td><td width='120px' bgcolor='$k'>".$language."</td><td width='120px' bgcolor='$k'><a href='".$username.".html'>".$username."</a></td><td width='90px' bgcolor='$k'>".$category."</td><td width='90px' bgcolor='$k'>".$date."</td><td width='70px' bgcolor='$k'>".$codeinfo['Views']."</td><td width='65px' bgcolor='$k'>".substr($rating, 0, 4)."</td><td width='65px' bgcolor='$k'>".$comcount."</td></tr>";
echo "<tr><td colspan='9'><span style='margin-left:40px'>".LimitText($codeinfo['Description'],150)."</span></td></tr>";
echo "</table></td></tr>";
if($k=="#E2E2E2")
$k="#FBFBFB";
else
$k="#E2E2E2";
}
echo '</table>';
echo '<br/><br/>';
}
else {
echo '<br/><br/>';
echo 'Click a language to view the available codes.<br/><br/>';
$findlangs = mysql_query("SELECT * FROM Language");
while ($foundlangs = mysql_fetch_array($findlangs)) {
	$langsid = $foundlangs['ID'];
	$langstitle = $foundlangs['Language'];
	echo '<a href="directory.php?section='.$langsid.'">'.$langstitle.'</a><br/><br/>';
}
echo '<br/><br/><br/>';
}

include_once('footer.php');
?>