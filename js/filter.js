//Skicka knappstatus till jquerytest.php
function getStates(value1, value2, value3, value4, value5, value6, value7, value8, value10, value11, value12, value13, value14, value15, value16, value17, value18)
{
	//alert(value1 + "\n" + value2 + "\n" + value3 + "\n" + value4 + "\n" + value5 + "\n" + value6 + "\n" + value7 + "\n" + value8 + "\n" + value9 + "\n" + value10 + "\n" + value11 + "\n");
	$.post("functions/jquerytest.php",
	{Csharp:value1,
	PHP:value2,
	Java:value3,
	Cplusplus:value4,
	ASP:value5,
	ObjectiveC:value6,
	Lett:value7,
	Svar:value8,
	Search:value10,
	Arrow:value11,
	HTML:value12,
	JS:value13,
	C:value15,
	SQL:value16,
	Python:value17,
	PERL:value18,
	VB:value14},
		   
	//Skriv ut resultatet som jquerytest.php har kommit fram till i <div> med id results
	
	function(data)
	{
	var h = document.getElementById('sortingTable');
	if(data.length < 570)
		h.className="hideTable";
    else
		h.className="showTable";
	$("#results").html(data);
	});
}

	 function updateTable()
	 {
	 	//Metod som tar in alla knappstatus som sedan skickas vidare till getStates funktionen
		   getStates(
		   checkToggleState('btn1'),
		   checkToggleState('btn2'),
		   checkToggleState('btn3'),
		   checkToggleState('btn4'),
		   checkToggleState('btn5'),
		   checkToggleState('btn6'),
		   checkToggleState('btn7'),
		   checkToggleState('btn8'),
		   getValue('Search'),
		   checkToggleArrow(),
		   checkToggleState('btn9'),
		   checkToggleState('btn10'),
		   checkToggleState('btn11'),
		   checkToggleState('btn12'),
		   checkToggleState('btn13'),
		   checkToggleState('btn14'),
		   checkToggleState('btn15')
		   );
	 }
	 <!-- Funktion för att ändra knapparnas ON/OFF värde när man väljer kategori -->
	 function setCategory(c)
	 {
	 var seleceted;
	 	if(c=="All")
		{
			selected="All";
		}
	 	else if(c=="Web")
		{
			selected="Web";
		}
		else if(c=="Windows")
		{
			selected="Windows";
		}
		else if(c=="iPhone")
		{
			selected="iPhone";
		}
		else if(c=="Android")
		{
			selected="Android";
		}
		else if(c=="Linux")
		{
			selected="Linux";
		}
		else if(c=="XNA")
		{
			selected="XNA";
		}
		else
			selected = "Other";
			
		document.getElementById("category").firstChild.data=selected;
		updateTable();
		
	 }
	 
	 function addTextAreaCallback(textArea, callback, delay) {
    var timer = null;
    textArea.onkeyup = function() {
        if (timer) {
            window.clearTimeout(timer);
        }
        timer = window.setTimeout( function() {
            timer = null;
            callback();
        }, delay );
    };
    textArea = null;
}

	 
	 function getCategory()
	 {
		if(document.getElementById("category").firstChild.data == "Windows")
			return 1;
		else if(document.getElementById("category").firstChild.data == "Web")
		 	return 2;
		else if(document.getElementById("category").firstChild.data == "iPhone")
		 	return 3;
		else if(document.getElementById("category").firstChild.data == "Android")
		 	return 4;
		else if(document.getElementById("category").firstChild.data == "Linux")
		 	return 5;
		else if(document.getElementById("category").firstChild.data == "Other")
		 	return 6;
		else if(document.getElementById("category").firstChild.data == "All")
			return 10;
		else
			return 7;
		 
	 }
	 
	 function toggleState(id)
	 {
	 var h = document.getElementById(id);
     if(h.className == "on")
     	h.className="off";
	else if(h.className == "off")
     	h.className="on";
     	else if(h.className == "off2")
     	h.className="on2";
     	else
     	h.className="off2";
	 	updateTable();
     }
	 
	 function getValue(id)
	 {
		 var h = document.getElementById(id);
		 return h.value;
	 }
	 
	 function clearAll(item)
	 {
		 if(item!=document.getElementById('sortByTitle'))
		 {
		 document.getElementById('sortByTitle').className="updown";
		 }
		 if(item!=document.getElementById('sortByDifficulty'))
		 {
		 	document.getElementById('sortByDifficulty').className="updown";
		 }
		 if(item!=document.getElementById('sortByLanguage'))
		 {
		 	document.getElementById('sortByLanguage').className="updown";
		 }
		 if(item!=document.getElementById('sortByUserID'))
		 {
		 	document.getElementById('sortByUserID').className="updown";
		 }
		 if(item!=document.getElementById('sortByCategory'))
		 {
		 	document.getElementById('sortByCategory').className="updown";
		 }
		 if(item!=document.getElementById('sortByViews'))
		 {
		 	document.getElementById('sortByViews').className="updown";
		 }
		 if(item!=document.getElementById('sortByLastEdit'))
		 {
		 	document.getElementById('sortByLastEdit').className="updown";
		 }
		 if(item!=document.getElementById('sortByAVG(Score)'))
		 {
		 	document.getElementById('sortByAVG(Score)').className="updown";
		 }
		 if(item!=document.getElementById('sortByComments'))
		 {
		 	document.getElementById('sortByComments').className="updown";
		 }
	 }
	 
	 function toggleArrow(item)
	 {
	 clearAll(item);
     if(item.className == "up" || item.className == "updown")
	 {
     	item.className="down";
     }
	 else
	 {
     	item.className="up";
     }
	 	updateTable();
     }
	 
	 function checkToggleArrow()
	 {
		 if(document.getElementById('sortByTitle').className=="up")
		 {
		 	return "Title,ASC";
		 }
		 else if (document.getElementById("sortByTitle").className=="down")
		 {
			 return "Title,DESC";
		 }
		 else if(document.getElementById('sortByDifficulty').className=="up")
		 {
		 	return "Difficulty,ASC";
		 }
		 else if(document.getElementById("sortByDifficulty").className=="down")
		 {
			 return "Difficulty,DESC";
		 }
		 else if(document.getElementById('sortByLanguage').className=="up")
		 {
		 	return "Language,ASC";
		 }
		 else if(document.getElementById("sortByLanguage").className=="down")
		 {
		 	return "Language,DESC";
		 }
		 else if(document.getElementById('sortByUserID').className=="up")
		 {
		 	return "Username,ASC";
		 }
		 else if(document.getElementById("sortByUserID").className=="down")
		 {
		 	return "Username,DESC";
		 }
		 else if(document.getElementById('sortByCategory').className=="up")
		 {
		 	return "Category,ASC";
		 }
		 else if(document.getElementById("sortByCategory").className=="down")
		 {
		 	return "Category,DESC";
		 }
		 else if(document.getElementById('sortByViews').className=="up")
		 {
		 	return "Views,ASC";
		 }
		 else if(document.getElementById("sortByViews").className=="down")
		 {
		 	return "Views,DESC";
		 }
		 else if(document.getElementById('sortByAVG(Score)').className=="up")
		 {
		 	return "AVG(Score),ASC";
		 }
		 else if(document.getElementById("sortByAVG(Score)").className=="down")
		 {
		 	return "AVG(Score),DESC";
		 }
		 else if(document.getElementById('sortByComments').className=="up")
		 {
		 	return "COUNT(Distinct Comments.ID),ASC";
		 }
		 else if(document.getElementById("sortByComments").className=="down")
		 {
		 	return "COUNT(Distinct Comments.ID),DESC";
		 }
		 else if(document.getElementById("sortByLastEdit").className=="up")
		 {
		 	return "LEdit,ASC";
		 }
			 return "LEdit,DESC";
	 }
	 
		
		//För att kontrollera om knappen är av eller om den är på
		function checkToggleState(id)
		{
		var h = document.getElementById(id);
			if(h.className == "on" || h.className == "on2")
			{
				return "JA";
			}
			else
			{
				return "NEJ";
			}
		}
		
		function addView(id)
		{
			$.post("functions/addView.php",
			{ID:id},
			function(data)
			{
			});
		}