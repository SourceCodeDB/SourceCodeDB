$(document).ready(function() {
	$('#usernameLoading').hide();
	$('#username').blur(function(){
	  alert("IT DID SOMETHING?");
	  $('#usernameLoading').show();
      $.post("../functions/checkuser.php", {
        username: $('#username').val()
      }, function(response){
        $('#usernameResult').fadeOut();
        setTimeout("finishAjax('usernameResult', '"+escape(response)+"')", 400);
      });
    	return false;
	});
});
function finishAjax(id, response) {
  $('#usernameLoading').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} //finishAjax