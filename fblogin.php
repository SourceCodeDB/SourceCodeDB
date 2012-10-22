<?php
require_once('fbsdk/facebook.php');
$facebook = new Facebook(array(
  'appId' => '225370617476152',
  'secret' => '9b30db40c98130897451f87648568238',
));

	$referrer = 'http://sourcecodedb.com/loggedin.php';
  $params = array('scope' => 'read_stream, email', 'redirect_uri' => $referrer); //parameter vi vill använda från facebook användare
  $loginUrl = $facebook->getLoginUrl($params);
  
  /* This will give an error. Note the output
 * above, which is before the header() call */
header('Location: '.$loginUrl);
?>