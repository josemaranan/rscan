// JavaScript Document
function populateModalWindows(url, width , height, var1, var2)
{
	var id = '#dialog';
	
	if (typeof document.body.style.maxHeight == "undefined") 
	{
		height = height-20;
	}
	
	$('#dialog').css('width', width);
	$('#replace_main').css('width', width);
	
	$('#dialog').css('height', height);
	$('#replace_main').css('height', height);
			
	//Get the screen height and width
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	
	$('#mask').css({'width':maskWidth,'height':maskHeight});
	
	//transition effect		
	$('#mask').fadeIn(1000);	
	$('#mask').fadeTo("slow",0.8);	

	//Get the window height and width
	var winH = $(window).height();
	var winW = $(window).width();
		  
	//Set the popup window to center
	$(id).css('top',  winH/2-$(id).height()/2);
	$(id).css('left', winW/2-$(id).width()/2);

	//transition effect
	$(id).fadeIn(2000);
			//$('#dialog').html('');
	document.getElementById('dialog').innerHTML = '<img src="images/progress.gif" width="100px" height="100pxl">' + ' Please Wait...';
	
	/* for only positions we are using 
		two more variables called
		hdnCorporate
		hdnSite
	*/
	var hdnCorporate = $('#hdnCorporate').val();
	var hdnSite = $('#hdnSite').val();
	//alert(url);
	//$('#ddlPayperiod').html('<option value="">Please wait</option>');
	$.post(url,   
	{ 
		var1:var1,
		var2:var2,
		hdnCorporate:hdnCorporate,
		hdnSite:hdnSite
	},   
	function(data)
	{
	  // alert(data);
		if(data!='error')
		{
			document.getElementById('dialog').innerHTML = '';
			$('#dialog').html(data);	
		}					
	}); 
	
	return false;	
}
function closeMask()
{
		$('#mask').hide();
		$('.window').hide();
		$('#replace_main').html("&nbsp;"); /* THIS WAS ADDED BCZ. TO INITIALIZE THE DOM AGAIN */ 
}


function loadCallMeNow()
{
	populateModalWindows('callMeNow.php', '225', '300', '', '');
}

function callMeNowProcess(processID)
{
	var frmStatus = true;
	if(processID == 2)
	{
		if($('#phoneNo').val() == '')
		{
			alert("Please enter your phone number");	
			$('#phoneNo').focus();
			frmStatus = false;			
		}
	}
	
	if(frmStatus == true)
	{
		populateModalWindows('callMeNowProcess.php', '225', '300', '', '');
	}
	
}

function loadCallMeLater()
{
	$('#selectTimeDiv').show();
}

function populateCallMeLater()
{
	var frmStatus = true;
	if($('#ddlTime').val() == '')
	{
		alert('Please select preferred time');
		$('#ddlTime').focus();
		frmStatus = false;
	}
	
	if($('#ddlDays').val() == '')
	{
		alert('Please select preferred Day');
		$('#ddlDays').focus();
		frmStatus = false;
	}
	
	if(frmStatus == true)
	{
		populateModalWindows('callMeLater.php', '225', '300', $('#ddlDays').val(), $('#ddlTime').val());
	}
}

function callMeLaterProcess(processID)
{
	var frmStatus = true;
	if(processID == 2)
	{
		if($('#phoneNo').val() == '')
		{
			alert("Please enter your phone number");	
			$('#phoneNo').focus();
			frmStatus = false;			
		}
		else
		{
			if(!$('#phoneNo').val().match(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/))
			{
				alert("Please enter valid phone number");	
				$('#phoneNo').focus();
				frmStatus = false;	
			}
		}
	}
	//alert($('#var1').val());
	if(frmStatus == true)
	{
		populateModalWindows('callMeLaterProcess.php', '225', '300', $('#var1').val(), $('#var2').val());
	}
	
}
