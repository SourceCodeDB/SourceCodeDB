<?php
include_once('header.php');

include_once('code/geshi.php');
$codeid = $_GET['c'];
$pageid = $_GET['p'];
?>
<p>Click on the filename or title to view the source</p>
<?php
$filecount=1;
$reqk = "SELECT * FROM CodeContent where RevStringID = '$codeid' AND Page = '$pageid'";
$resk = mysql_query($reqk);
while ($findfiles = mysql_fetch_array($resk)) {
   $filename = $findfiles['Title'];
   $filedesc = $findfiles['Description'];
                    $filesource = $findfiles['Content'];
                    $revisionnumber = $findfiles['Revision'];
                    $pagenumber = $findfiles['Page'];
                    $revdate = $findfiles['RevDate'];
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
                    ?></a> - Last edited: <?php echo $revdate; ?><br />
                    <div id="toggleText<?php echo $filecount; ?>" style="display: none">
                    <?php
                    echo '<br/>';
                    echo nl2br($filedesc);
                    echo '<br/>';
                    if (!isset($language)) {
                        //TODO lookup a nice general language or make a function to figure out the language
                        $language = "PHP";
                    }
                    $geshi = new GeSHi($filesource, $language);
                    $geshi->set_header_type(GESHI_HEADER_DIV);
                    $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 999999);
                    $geshi->enable_classes();
                    echo $geshi->parse_code();
                    
                    echo 'Revision: '.$revisionnumber;
                    ?>
                    </div>
                    <?php
                    $filecount++;
                }
            
$url = mysql_result(mysql_query("SELECT Url FROM CodeInfo WHERE StringID = '$codeid'"),0);
?>
<br/>
<p><a href="http://sourcecodedb.com/<?php echo $url; ?>.html">Back</a></p><br><br><br>

<?php
include_once('footer.php');
?>