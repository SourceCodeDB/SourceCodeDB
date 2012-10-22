function getPasswordForm(item)
{
	toggleThis('pw');
	if((getToggleState('mail')=="down_change"))
	{
	toggleUP('mail');
	}
	if(item.className=="down_change")
	{
	$("#changing").html("");
	$.post("functions/pasw.php",
	function(data)
	{
		$("#change").html(data);
	});
	}
	else
	{
		$("#info").html("");
		$("#change").html("");
	}
}

$(function() { 
$("ul.tabs").tabs("div.panes > div");
});

$(function() { 
$("ul.admintabs").tabs("div.adminpanes > div");
});

function getEmailForm(item)
{
	toggleThis('mail');
	if((getToggleState('pw')=="down_change"))
	{
	toggleUP('pw');
	}
	if(item.className=="down_change")
	{
	$("#changing").html("");
	$.post("functions/email.php",
	function(data)
	{
		$("#change").html(data);
	});
	}
	else
	{
		$("#info").html("");
		$("#change").html("");
	}
}
function toggleUP(id)
{
var d=document.getElementById(id);
d.className="up_change";
}

function toggleDOWN(id)
{
var d=document.getElementById(id);
d.className="down_change";
}

function toggleThis(id)
{
	var d=document.getElementById(id);
	if(d.className=="up_change")
	{
	//alert("Changed to DOWN");
	d.className="down_change";
	}
	else
	{
	//alert("Changed to UP");
	d.className="up_change";
	}
}

function getToggleState(id)
{
	var d=document.getElementById(id);
	return d.className;
}

function deleteBookmark(strid)
{

	$.post("mypages.php",
	{strID:strid},
	function(data)
	{
		$("#code").html(data);
	});
}

function deleteComment(comid)
{

	$.post("mypages.php",
	{comID:comid},
	function(data)
	{

		$("#code").html(data);
		
	});

}
	 
	 function changePassword(value1, value2, value3)
	 {
	 	  
		  var old = (document.getElementById(value1)).value;
		  var new1 = (document.getElementById(value2)).value;
		  var new2 = (document.getElementById(value3)).value;
		  $.post("functions/changepw.php", {oldpw:old, new1pw:new1, new2pw:new2},
	function(data)
	{
		if(data=="success")
		{
			toggleThis('mail');
			if((getToggleState('pw')=="down_change"))
			{
			toggleUP('pw');
			}
			$("#info").html("");
			$("#change").html("");
			$("#changing").html("Password was successfully changed!");
		}
		else if(data=="regreq")
		{
			$("#changing").html("You need to be logged in.");
		}
		$("#info").html(data);
	});
}
	function changeEmail(value1, value2, value3)
	 {
		  var old = (document.getElementById(value1)).value;
		  var new1 = (document.getElementById(value2)).value;
		  var new2 = (document.getElementById(value3)).value;
		  $.post("functions/changeEmail.php", {oldpw:old, new1pw:new1, new2pw:new2},
	//Skriv ut resultatet som jquerytest.php har kommit fram till i <div> med id results
	function(data)
	{
		if(data=="success")
			  {
				  $("#email").html("Email was successfully changed!");
			  }
		$("#info2").html(data);
	});
	 }
	 

	 
