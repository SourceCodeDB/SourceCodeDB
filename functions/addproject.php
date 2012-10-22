<?php
include_once('db.php');
require_once("../fbsdk/facebook.php");
 $config = array(
    'appId' => '225370617476152',
    'secret' => '03622faf6618ea99449f21079d13643f',
  );
  $facebook = new Facebook($config);
  $fbuid = $facebook->getUser();
  if (isset($_SESSION['Username'])) {
$username = $_SESSION['Username'];
  }
  else {
      $username = "";
  }
$table = "Language";
$table2 = "Difficulty"; 
$table3 = "Category"; 
$sql = "SELECT * FROM $table";
$sql2 = "SELECT * FROM $table2";
$sql3 = "SELECT * FROM $table3 ORDER BY ID ASC";
$res = mysql_query($sql);
$res2 = mysql_query($sql2);
$res3 = mysql_query($sql3);
echo '<form action="store.php" method="post">';
echo '<input id="username1" type="hidden" name="username[]" value="';
echo $username;
echo '" />';
echo '<p>';
echo 'You are adding a project! Press "Add another file" at the bottom to add more files. We take our code and users who abuse our site very seriously. Thank you for making SourceCodeDB.com better!';
echo '</p>';
echo '<p><b>';
echo 'Project title';
echo ':</b> ';
echo 'Please enter a title for your project';

echo '</p><input type="text" size="30" name="title"><br><p><b>';

echo 'Description of project';
echo ':</b> ';
echo 'Please enter description that will help users understand what the project is for.';
echo '</p><textarea id="desc1" name="desc" rows="10" cols="85"></textarea><br>';
echo 'Language';
echo '<br><select name="lang">';
while ($data = mysql_fetch_array($res)) {
          echo '<option value="';
		  echo $data['ID'];
		  echo '">';
		  echo $data['Language'];
		  echo '</option>';
		  }
echo '</select> ';
echo 'Difficulty';
echo ' <select name="diff">';
while ($data2 = mysql_fetch_array($res2)) {
          echo '<option value="';
		  echo $data2['ID'];
		  echo '">';
		  echo $data2['Difficulty'];
		  echo '</option>';
		  }
echo '</select> ';
echo 'Category';
echo ' <select name="cat">';
while ($data3 = mysql_fetch_array($res3)) {
          echo '<option value="';
		  echo $data3['ID'];
		  echo '">';
		  echo $data3['Category'];
		  echo '</option>';
		  }
echo '</select><br /><br /> <HR>';
echo '<div class="clone">';
echo '<p><b>';
echo 'Filename';
echo ':</b> ';
echo 'Please enter a filename and extension';

echo '</p><input type="text" size="30" name="filename[]"><br><p><b>';

echo 'Description';
echo ':</b> ';
echo 'Please enter description that will help users understand what the code does.';
echo '</p><textarea id="desc1" name="filedesc[]" rows="10" cols="85"></textarea><br>';
echo 'Enter your code here.';
echo '<br><textarea onkeydown="return insertTab(event,this);" onkeyup="return insertTab(event,this);" onkeypress="return insertTab(event,this);" id="code1" name="code[]" rows="30" cols="85"></textarea><br>';
echo '</div>';
echo 'Source';
echo ': <input id="source1" type="text" name="source[]"><p>';
echo 'Leave blank if code is yours or there is no source but if you have taken this code from another website please tell us your source so that credit can be given.';
echo '</p>';
echo 'Tags';
echo ': <input id="tags1" type="text" name="tags[]"><p>';
echo 'Tag your code with keywords, sperate each word or phrase with a comma.';
echo '</p>';
echo '<input id="submitted1" type="hidden" name="submitted[]" value="Y" />';
echo '<a href="#" class="add" rel=".clone">Add Another File</a><br/><br/>';
if ($fbuid) {
echo 'Share on facebook?';
echo ' <input id="facebook1" type="checkbox" name="facebook" value="Y"><br />';
}
echo '<table> <tr><td><input type="submit" value="Save Privately" name="save" /></td><td><input type="submit" value="Publish" name="publish" /></td></tr></table>';
echo '</form>';
?>