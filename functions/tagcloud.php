<?php
function createTagCloud($tags)  
{     
    //I pass through an array of tags  
    $i=0;  
    foreach($tags as $tag)  
    {  
    	if ($i < 200) {
         //the tag id, passed through  
        $name = $tag; //the tag name, also passed through in the array  
        //using the mysql count command to sum up the tutorials tagged with that id  
        $sql = "SELECT COUNT(*) AS totalnum FROM Tags WHERE Tag LIKE '%".$name."%'";  
          
        //create the resultset and return it  
        $res = mysql_query($sql);  
        $res = mysql_fetch_assoc($res);  
          
        //check there are results ;)
        if($res)  
        {  
            //build an output array, with the tag-name and the number of results  
            $output[$i]['tag'] = $name;   
            $output[$i]['num'] = $res['totalnum'];  
        }  
        }
        $i++; 
        
    }  
      
    /*this is just calling another function that does a similar SQL statement, but returns how many pieces of content I have*/  
    $total_tuts = mysql_num_rows(mysql_query("SELECT * FROM CodeInfo"));  
      
    //ugh, XHTML in PHP?  Slap my hands - this isn't best practice, but I was obviously feeling lazy  
    $html = '<ul class="tagcloud">';  
    //iterate through each item in the $output array (created above)  
    foreach($output as $tag)  
    {  
        //get the number-of-tag-occurances as a percentage of the overall number  
        $ratio = (100 / $total_tuts) * $tag['num'];  
          
        //round the number to the nearest 10  
        $ratio =  round($ratio,-1);  
          
        /*append that classname onto the list-item, so if the result was 20%, it comes out as cloud-20*/  
        $html.= '<li class="cloud-'.$ratio.'"><a href="http://sourcecodedb.com/tags.php?tag='.$tag['tag'].'">'.$tag['tag'].'</a></li>';           
    }  
      
    //close the UL  
    $html.= '</ul>';  
      
    return $html;   
}


$gettags = mysql_query("SELECT Tag FROM Tags");
$i = 0;
$tags = array();
while ($tag = mysql_fetch_array($gettags)) {
	$match = 0;
	foreach ($tags as $t) {
		if (strtolower($t) == strtolower($tag['Tag'])) {
			$match = 1;
		}
	}
	if ($match == 0) {
	$tags[$i] = $tag['Tag'];
	$i++;
	}
}

//while ($thetags = mysql_fetch_array($gettags)) {
//	if ($thetags['Tags'] != null) {
//	$newtag[] = $thetags['Tags'];
//	}
//}
//foreach ($newtag as $thetag) {
//	$tagarray[] = explode(", ", $thetag);
//}
//
//function array_flatten_recursive($array) { 
//   if (!$array) return false;
//   $flat = array();
//   $RII = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
//   foreach ($RII as $value) $flat[] = $value;
//   return $flat;
//}
//
//
//$finaltag = array_flatten_recursive($tagarray);
//$finaltag2 = array_unique($finaltag);
echo createTagCloud($tags);
?>