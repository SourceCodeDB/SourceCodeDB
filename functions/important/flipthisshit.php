<?php
echo check_name($i); // call the check_username function and echo the results.
$i = mt_rand(1,4);
function check_name($i){
    if ($i == 3) {
    	return '<style type="text/css">
 
html body {
  -webkit-transform: rotate(90deg);
  -moz-transform: rotate(90deg);
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=1);
}
 
</style>';
    }
    elseif ($i == 2) {
    	return '<style type="text/css">
 
html body {
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
}
 
</style>';
    }
    else {
    	return '<style type="text/css">
 
html body {
  -webkit-transform: rotate(270deg);
  -moz-transform: rotate(270deg);
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
}
 
</style>';
    }
}
    
?>