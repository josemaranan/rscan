// JScript File
function resize() 
{
	var documentHeight = document.documentElement.clientHeight;

	var documentwidth = document.documentElement.clientWidth;
	documentwidth = documentwidth-200; // 200 means main_menu ( side menu ) width.
	var allDivHeight = 0;
	
	//alert(documentHeight);
	//alert(documentwidth);
	
	//documentHeight = 552 
	//documentwidth = 1345
	
	// Top Logo Height = 105
	
	var allDivTags = document.getElementsByTagName('Div');
	var allDivLen = allDivTags.length;
	for(var i=0; i<allDivLen; i++)
	{	
		if(allDivTags[i].className=='outer')
		{
			
			var eachDivHieght = document.getElementById(allDivTags[i].id).offsetHeight;
			//var eachDivHieght = document.getElementById(allDivTags[i].id).height;
			//alert(eachDivHieght);
			allDivHeight += parseInt(eachDivHieght);
		}
		
		
	}
	//alert(allDivHeight);
	
	var requiredheight = parseInt(documentHeight)-parseInt(allDivHeight);
	var requiredheightFinal = requiredheight;
	//alert(requiredheightFinal);
	
	//var w = document.getElementById("scrollingdatagrid").offsetParent.clientHeight; 
	var x = document.getElementById("scrollingdatagrid").scrollHeight;
	//var s = x+34;  //add a little room for spacing in the table 
	
	//alert(w);
	//alert(x);
	//alert(requiredheightFinal);
	
	if (x > requiredheightFinal)  //if content is longer than available space
	{   
	  document.getElementById("scrollingdatagrid").style.height= requiredheightFinal + "px";
	  document.all.scrollingdatagrid.style.overflowX = "scroll";
	  document.all.scrollingdatagrid.style.overflowY = "scroll";
	}
	 else   //if content is shorter than available space
	{
	
		document.getElementById("scrollingdatagrid").style.height = "70%";
	
		document.all.scrollingdatagrid.style.overflowX = "scroll";
		document.all.scrollingdatagrid.style.overflowY = "scroll";
	}
	document.getElementById('scrollingdatagrid').style.width = documentwidth + "px";
	document.getElementsByTagName("html")[0].style.overflow = "hidden";

	
	

} // eof resize


function makeItDynamic()
{
	setTimeout('resize()', 2000); 
	document.body.style.overflow = 'hidden';
	//document.getElementsByTagName("html")[0].style.overflow = "hidden";

	var co_height = document.documentElement.clientHeight;
	document.getElementById('main_menu').style.height = co_height;

}
