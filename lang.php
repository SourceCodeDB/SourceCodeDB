<?php
session_start();
$frompage = $_SERVER["HTTP_REFERER"];
//changes language
if ($_SESSION['lang'] == 'sv_SV') {
	unset($_SESSION['lang']);
	$_SESSION['lang'] = 'en_EN';
}
else {
	unset($_SESSION['lang']);
	$_SESSION['lang'] = 'sv_SV';
}
echo 'Language being changed, please wait';
?>
<script>
    <!--
    window.location= "<?php echo $frompage; ?>"
    //-->
</script>
  