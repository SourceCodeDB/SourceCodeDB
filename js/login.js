$(document).ready(function() {

            $(".signin").click(function(e) {          
				e.preventDefault();
                $("fieldset#signin_menu").toggle();
				$(".signin").toggleClass("menu-open");
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
