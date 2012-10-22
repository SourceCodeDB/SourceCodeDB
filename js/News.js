function createNews()
{
	$.post("../functions/createForm.php",
	function(data)
	{
		if ($('#form').is(':empty'))
		{
			$("#form").html(data + "<br>");
			$("#News").val("Create News [ - ]");
		}
		else
		{
			$("#form").html("")
			$("#News").val("Create News [ + ]");
		}
	});
}

function editNews(id)
{
	$.post("../news.php",
			{NewsID:id},
			function(data)
			{
			$("#NewsList").html(data);
			});
}

function modifyNews(id, title, content, todo)
{
	var title = document.getElementById(title).value;
	var content = document.getElementById(content).value;
	$.post("../news.php",
		  {changeID:id, changeTitle:title, changeContent:content, Todo:todo},
		  function(data)
		  {	
		  	$("#NewsList").html(data);
		  });
}