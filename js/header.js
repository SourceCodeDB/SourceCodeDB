$(document).ready(function() {

            $(".login_class").click(function(e) {          
				e.preventDefault();
                $("fieldset#signin_menu").toggle();
				$(".login_class").toggleClass("menu-open");
            });
			
			$("fieldset#signin_menu").mouseup(function() {
				return false;
			});
					
			$(document).mouseup(function(e) {
				if($(e.target).parent("a.signin").length==0){
					$(".signin").removeClass("menu-open");
					$("fieldset#signin_menu").hide();
				}
			});

			$("#login").click(function() {
	
		var action = $("#form1").attr('action');
		var form_data = {
			username: $("#username").val(),
			password: $("#password").val(),
			is_ajax: 1
		};
		$.ajax({
			type: "POST",
			url: action,
			data: form_data,
			success: function(response)
			{
				if(response == 'success')
				{
						window.location = '' 
				}
				else if(response == 'nothing')
					$("#message").html("<p class='error'>Enter a username and a password!</p>");
				else if(response == 'wrong')
					$("#message").html("<p class='error'>Invalid username and/or password.</p>");
				//else if(response == 'nouser')
					//$("#message").html("<p class='error'>Username does not exist.</p>");	
			}
		});
		
		return false;
	});	
	
		$('#logout').click(function(){
			$.ajax({ 
				type: "POST",
            	url: 'logout.php', 
            	success: function(response)
				{
					if(response == 'success'){
						window.location = '' }		
				}
			});
		return false;
		});
        });
		
		
// DROPDOWN

// Copyright 2006-2007 javascript-array.com

var timeout	= 500;
var closetimer	= 0;
var ddmenuitem	= 0;

// open hidden layer
function mopen(id)
{	
	// cancel close timer
	mcancelclosetime();

	// close old layer
	if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';

	// get new layer and show it
	ddmenuitem = document.getElementById(id);
	ddmenuitem.style.visibility = 'visible';

}
// close showed layer
function mclose()
{
	if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
}

// go close timer
function mclosetime()
{
	closetimer = window.setTimeout(mclose, timeout);
}

// cancel close timer
function mcancelclosetime()
{
	if(closetimer)
	{
		window.clearTimeout(closetimer);
		closetimer = null;
	}
}

// close layer when click-out
document.onclick = mclose; 


