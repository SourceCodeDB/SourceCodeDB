<?php
function rating_bar($id,$units='',$static='') { 
require('_config-rating.php'); // get the db connection info
	
//set some variables
$ip = $_SERVER['REMOTE_ADDR'];
if (!$units) {$units = 10;}
if (!$static) {$static = FALSE;}
if (isset($_SESSION['ID'])) {
$userid=$_SESSION['ID'];
}
// get votes, values, ips for the current rating bar
$query=mysql_query("SELECT used_ips, SUM(Score) FROM $rating_dbname.$rating_tableName WHERE StringID='$id' ")or die(" Error: ".mysql_error());
$query2=mysql_query("SELECT used_ips, Score FROM $rating_dbname.$rating_tableName WHERE StringID='$id' ")or die(" Error: ".mysql_error());


// insert the id in the DB if it doesn't exist already
// see: http://www.masugadesign.com/the-lab/scripts/unobtrusive-ajax-star-rating-bar/#comment-121
$num_rows = mysql_num_rows($query2);
$numbers=mysql_fetch_assoc($query);
$current_rating = $numbers['SUM(Score)'];
$count = $num_rows;

if ($count > 1) {
    $tense = "votes";
} 
else {
    $tense = "vote";
}


// determine whether the user has voted, so we know how to draw the ul/li
if (isset($_SESSION['ID'])) {
$voted=mysql_num_rows(mysql_query("SELECT UserID, Score FROM $rating_dbname.$rating_tableName WHERE UserID LIKE $userid AND StringID='".$id."' ")); 
}
else {
    $voted = 0;
}

// now draw the rating bar
$rating_width = @number_format($current_rating/$count,2)*$rating_unitwidth;
$rating1 = @number_format($current_rating/$count,1);
$rating2 = @number_format($current_rating/$count,2);


if ($static == 'static') {

		$static_rater = array();
		$static_rater[] .= "\n".'<div class="ratingblock">';
		$static_rater[] .= '<div id="unit_long'.$id.'">';
		$static_rater[] .= '<ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
		$static_rater[] .= '<li class="current-rating" style="width:'.$rating_width.'px;">Currently '.$rating2.'/'.$units.'</li>';
		$static_rater[] .= '</ul>';
		$static_rater[] .= '<p class="static">Rating: <strong> '.$rating1.'</strong>/'.$units.' ('.$count.' '.$tense.' cast) <em>This is \'static\'.</em></p>';
		$static_rater[] .= '</div>';
		$static_rater[] .= '</div>'."\n\n";

		return join("\n", $static_rater);


} else {

      $rater ='';
      $rater.='<div class="ratingblock">';

      $rater.='<div id="unit_long'.$id.'">';
      $rater.='  <ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
      $rater.='     <li class="current-rating" style="width:'.$rating_width.'px;">Currently '.$rating2.'/'.$units.'</li>';

      for ($ncount = 1; $ncount <= $units; $ncount++) { // loop from 1 to the number of units
           if(!$voted && isset($_SESSION['ID'])) { // if the user hasn't yet voted, draw the voting stars
              $rater.='<li><a href="functions/db2.php?j='.$ncount.'&amp;q='.$id.'&amp;t='.$ip.'&amp;c=5" title="'.$ncount.' out of '.$units.'" class="r'.$ncount.'-unit rater" rel="nofollow">'.$ncount.'</a></li>';
           }
      }
      $ncount=0; // resets the count

      $rater.='  </ul>';
      $rater.='  <p';
      if($voted){ $rater.=' class="voted"'; }
      $rater.='>Rating: <strong> '.$rating1.'</strong>/'.$units.' ('.$count.' '.$tense.' cast)';
      $rater.='  </p>';
      $rater.='</div>';
      $rater.='</div>';
      return $rater;
 }
}
?>