function GetXmlHttpObject(handler)
{
	var objXMLHttp=null   
	if (window.XMLHttpRequest)   
	{
		objXMLHttp=new XMLHttpRequest()   
	}
	else if (window.ActiveXObject)   
	{       
		objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")   
	}
	return objXMLHttp
}
/*function stateChanged()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		document.getElementById(DEP).innerHTML= xmlHttp.responseText;   
	}
	else
	{
		//alert(xmlHttp.status);   
	}
}*/
// Will populate data on the LOB Select Box based on input
function htmlData(url, qStr, DEP){
	if (url.length==0)   
	{
		document.getElementById(DEP).innerHTML="";       
		return;   
	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)   
	{
		alert ("Browser does not support HTTP Request");  
		return;   
	}   
	url=url+"?"+qStr;
	url=url+"&sid="+Math.random(); 
	//alert(url);  
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{
			document.getElementById(DEP).innerHTML= xmlHttp.responseText;   
		}
		else
		{
			//alert(xmlHttp.status);   
		}
	}
	xmlHttp.open("GET",url,true) ; 
	xmlHttp.send(null);
}