function top10(top)
{
	$.post("top10.php",
	{Top:top},
	function(data)
	{
		$("#results_top10").html(data);
	});
}

function addView(id)
{
	$.post("functions/addView.php",
	{ID:id},
	function(data)
	{
	});
}