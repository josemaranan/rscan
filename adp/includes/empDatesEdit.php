<?php
//$employeeeMaintenanceObj->setUSLocations();

//$employData = $employeeeMaintenanceObj->getEmployeeInformation();
//$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();
/*
include_once($_SERVER["DOCUMENT_ROOT"]."/Users/site_administration/siteManagement/employementDatesClass_adp.inc.php");
*/

include_once($_SERVER["DOCUMENT_ROOT"]."/Users/site_administration/siteManagement/employementDatesClass_adp.inc.php");

unset($sqlMainQry);
unset($rstMainQry);
//echo $datePlus30Days;exit;
unset($show);
$show = 'N';

unset($datePlus30Days);
$datePlus30Days = date('m/d/Y',strtotime('+30 days'));

//$hireDate = $_GET["hireDate"];

//$hireDate = date('m/d/Y',strtotime($hireDate));

//limited access to locations for juan.ponder(user)
	
// Get ClientName Dynamically
unset($dynamicClient);
unset($dynamicClientName);
unset($sqlQuery);
unset($resultsSet);


$employeeeDates = new EmploymentDates($employeeID,'ADP',$hireDate,'edit');

//################ Main body starts here
echo $htmlTagObj->openTag('div', 'id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="businessRuleHeading" class="outer"');
echo 'Employment Dates';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo();

$employeeeDates->displayHtml();



if($_GET[res] == 'hireDateAlreadyExisted')
{?>
	<script type="text/javascript">
    	alert('Employee already has previous hire date of <?php echo $_GET[hireDate1];?> ');
    </script>
<?php 
} 
else if($_GET[res] == 'updateTermdate')
{?>
	<script type="text/javascript">
    	alert('Employee must have a term date for existing hire date ');
    </script>
<?php 
}
else if($_GET[res] == 'hireDateExistedBetween')
{?>
	<script type="text/javascript">
    	alert('Hire date cannot be in pre-existing range for this employee');
    </script>
<?php 
}
?>


<style type="text/css">
/*#searchBody{
	margin-left: 0px !important;
}
#rightADPPanel {
	overflow:hidden !important;
	width:950px !important;
}*/

#searchFieldSet fieldset{
	width:95% !important;
}
</style>

<script type="text/javascript">
var show = 'yes';
//var termReason = <?php //echo json_encode($TerminationReasons);?>;
var termReason = $('#TerminationReasons').val();

//alert(document.form_data.termDate.value);
if((show == 'yes') && (termReason != ''))
{	
//loadNCNS('<?php //echo $TerminationReasons;?>');
loadNCNS(termReason);
document.getElementById('ddlNCNS').value = '<?php echo $NCNS;?>';
}

$(function (){
	var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"]; ?>';
	$( "#hireDate, #termDate" ).datepicker({
		  showOn: "button",
		  buttonImage: hostUrl+"/Include/images/calendar.gif",
		  buttonText:'Calendar',
		  buttonImageOnly: true,
		  showWeek:true,
		  changeMonth:true,
		  changeYear:true,
		  showButtonPanel:true,
		  closeText: "Close"
	});

	$( "#hireDate" ).on('change',function(){
		//alert(startDateForChangeReason+' - '+this.value);
		showHideChangeReason_ADP();
	});

	$( "#termDate" ).on('change',function(){
		//alert(startDateForChangeReason+' - '+this.value);
		showHideChangeReason_ADP_TermDate();
	});
	
	$(".searchtabADP tbody tr:nth-last-child(2)").hide();
	isMandatoryChangeReason = false;
});

function Validate()
{
	
	/*if(document.getElementById('hdnType').value == 'ADP' && document.getElementById('hdnDynamicString').value=='')
		{
			
				if(document.getElementById('chkConfirmHireDate').checked==false )
				{
					alert('As a member of the Human Resources team, your confirmation of the correct hire date is required on this record');
					return false;
				}
				
				
		}*/
	//alert(startDateForChangeReason+' - '+isMandatoryChangeReason); //return false;    
	
	if(document.form_data.hireDate.value == '')
	{
		alert('Plese select hire date');
		return false;
		
	}
	else if (document.form_data.termDate.value != "") 
	{
		document.getElementById("divTerminationDetailsTH").style.display = "block";
		document.getElementById("divTerminationDetailsTD").style.display = "block";
		document.getElementById("rehireableTH").style.display = "block";
		document.getElementById("rehireableTD").style.display = "block";
		//document.getElementById("wasTermVolTH").style.display = "block";
		//document.getElementById("wasTermVolTD").style.display = "block";
		document.getElementById("supConfirmTH").style.display = "block";
		document.getElementById("supConfirmTD").style.display = "block";
		document.getElementById("lstDayWorkedTH").style.display = "block";
		document.getElementById("lstDayWorkedTD").style.display = "block";
		
		
		var elementRef1 = document.getElementById('ddlTerminationReasons').value;
		var elementRef2 = document.getElementById('ddlRehireable').value;
		var elementRef3 = document.getElementById('ddlvoluntary').value;
		
		var selTermDate = new Date(document.form_data.termDate.value);
		var plus30 = new Date(document.getElementById('hdnDatePlus30Days').value);
		var ddlNCNS = document.getElementById('ddlNCNS').value;
		
		var lastDayWorked = new Date(document.getElementById('lastDayWorked').value);
		var termDate = new Date(document.getElementById('termDate').value);
		
		if(selTermDate>plus30)
		{
			alert("Term date should be limited to one month"); 
			document.form_data.termDate.focus();			
			return false;
		}
		else if(elementRef1 =="")
		{ 
			alert("Please Select Termination Reason"); 
			document.form_data.ddlTerminationReasons.focus();			
			return false;
		}
		else if(document.getElementById('lastDayWorked').value == '')
		{
			alert("Please Select Last Working Date"); 
			document.getElementById('lastDayWorked').focus();			
			return false;
		}
		else if(lastDayWorked > termDate)
		{
			alert("Last worked date should be less than or equal to term date"); 
			document.getElementById('lastDayWorked').focus();			
			return false;
		}
		else if(elementRef2 =="")
		{ 
			alert("Please Select Re-Hireable"); 
			document.getElementById('ddlRehireable').focus();
			//document.form_data.ddlRehireable.focus();			
			return false;
		}
		else if(elementRef3 =="")
		{ 
			alert("Please Select Was Termination Voluntary"); 
			//document.form_data.ddlvoluntary.focus();			
			return false;
		}
		else if(ddlNCNS =="")
		{ 
			alert("Please Select No Call , No Show"); 
			//document.form_data.ddlNCNS.focus();			
			return false;
		}
		
		if(isMandatoryChangeReason == true && ( $.trim($("#txtChangeReason").val()) ) == '')
		{
			alert("Please enter reason");
			$("#txtChangeReason").focus();
			return false;
		}
		
		return ValidateDate('hireDate','termDate');
	}
	else if(document.getElementById('ddlTerminationReasons').value != '' && document.form_data.termDate.value == "")
	{
		alert("Please select term date");
		document.form_data.termDate.focus();
		return false;
	}
	else if(isMandatoryChangeReason == true && ( $.trim($("#txtChangeReason").val()) ) == '')
	{
		alert("Please enter reason");
		$("#txtChangeReason").focus();
		return false;
	}
}
		
function ValidateDate(ctrlHDate,ctrlTDate)
{
	var HDate = document.getElementById(ctrlHDate).value;    	
	var TDate =  document.getElementById(ctrlTDate).value;		   
	var alertReason =  'Term Date must be greater than Hire Date.' 
	var endDate = new Date(TDate);    	
	var startDate= new Date(HDate);
	 
	if(HDate != '' && TDate != '' && startDate > endDate)
	{
		alert(alertReason);
		return false;
	}
}

 
function populateVolumeReduction(termIDID)
{
	//alert(termIDID);
	$('#volumereductionlable').show(); 
	$('#volumereductiondata').show();
	$('#volumereductiondata').html='';
	$('#ddlvoluntary').val('');
	/*$('#ddlvoluntaryDisp').val('');
	$('#ddlvoluntaryDisp').val('loading');*/
	$('#NCNSDATA').html('');
	$('#NCNSTD').hide(); 
	$('#NCNSDATA').hide();
	var comStr = '';
	
	document.getElementById('newIsVolTerm').innerHTML='';
	
	
	document.getElementById('volumereductiondata').innerHTML = '<img src="../../../Include/images/progress.gif">' + ' Please Wait...';	
		  
	$.post("populateVolumeReduction.php",   
	{ 
		terRID:termIDID,
		empID:'<?php  echo $employeeID;?>'
	},   
		function(data)
		{ 
			 if(data!='')
			 {
				$('#volumereductionlable').show(); 
				$('#volumereductiondata').show(); 
				$('#volumereductiondata').html(data);
			 } 
			 else 
			 {
				$('#volumereductionlable').hide(); 
				$('#volumereductiondata').hide(); 
				$('#volumereductiondata').html = '';
			 }
		} 
	); 
	
	populateVoluntary(termIDID);
	loadNCNS(termIDID);
	
	return false;
}

	/* Voluntary / Involuntary drop down */

function populateVoluntary(termIDID)
{


$.post("populateVoluntary.php",   
	{ 
		terRID:termIDID
	},   
		function(data)
		{ 
			if(data!='')
			{
				$('#ddlvoluntary').val(data);
				if(data=='true')
				{
					//$('#ddlvoluntaryDisp').val('Yes');
					document.getElementById('newIsVolTerm').innerHTML='Yes';
				}
				else
				{
					//$('#ddlvoluntaryDisp').val('No');	
					document.getElementById('newIsVolTerm').innerHTML='No';
				}
			}
			else
			{
				$('#ddlvoluntary').val('false');
				//$('#ddlvoluntaryDisp').val('No');
				document.getElementById('newIsVolTerm').innerHTML='No';
			}
			
		} 
	); 
	return false	
}

function loadNCNS(termID)
{	

	$.post("getYesNoFlag.php",   
	{ 
			terRID:termID
	},   
			function(data)
			{ 
			
				var comStr = '';
				comStr = '<select name="ddlNCNS" id="ddlNCNS" onchange="return loadRehireLogic(this.value); return false;" >';
				yesNoFlag = data;
				document.getElementById('NCNSTD').style.display = 'block';
				document.getElementById('NCNSDATA').style.display = 'block';
				
				//if(termID == 'V04' || termID == 'V09' || termID == 'V26')
				if(yesNoFlag=='Y')
				{
				//$("#ddlNCNS").html('<option value="Y">Yes</option>');
					comStr  +=  '<option value="Y">Yes</option>';
				}
				else
				{
				//("#ddlNCNS").html('<option value="">choose</option>');	
				//$("#ddlNCNS").html('<option value="Y">Yes</option>');
				//$("#ddlNCNS").html('<option value="N">No</option>');
				comStr  +=  '<option value="">choose</option>';
				comStr  +=  '<option value="Y">Yes</option>';
				comStr  +=  '<option value="N">No</option>';
				
				}
				
				comStr  += '</select>';
				//alert(comStr);
				$("#NCNSDATA").html(comStr);
				loadRehireLogic(yesNoFlag);
			} 
		); 
	
}

function loadRehireLogic(yesNoFlag)
{
	$("#rehireableTD").html('');
	
	var fstr = ' <select name="ddlRehireable"  id="ddlRehireable" style="width:auto;">';
	
	if(yesNoFlag=='Y')
	{
	//$("#ddlNCNS").html('<option value="Y">Yes</option>');
		fstr  +=  '<option value="False">No</option>';
		
	}
	else
	{
	//("#ddlNCNS").html('<option value="">choose</option>');	
	//$("#ddlNCNS").html('<option value="Y">Yes</option>');
	//$("#ddlNCNS").html('<option value="N">No</option>');
	fstr  +=  '<option value="">Please Choose</option>';
	fstr  +=  '<option value="True">Yes</option>';
	fstr  +=  '<option value="False">No</option>';
	
	}
	fstr  += '</select>';
				//alert(comStr);
	$("#rehireableTD").html(fstr);
}


function loghdnOriginalHireDate(logorigiID, minHireDate, origHireDateStamp,origHireDate,type)
{
	//alert(logorigiID);
	//alert(minHireDate);
	//alert(origHireDate);
	//alert(type);
	
	hireDateSystemStamp = document.getElementById('hdnhDateStamp').value;
	
	//alert(hireDateSystem);
	//var hireDateSystemStamp = new Date(hireDateSystem);
	//var origHireDateStamp = new Date(origHireDate);
	
	//alert(hireDateSystemStamp);
	//alert(origHireDateStamp);
	
	if(type=='ADP')
	{
		if(document.getElementById(logorigiID).checked==true)
		{
			document.getElementById('hdnOriginalHireDate').value = document.getElementById('hireDate').value;	
		}
		else
		{	if(hireDateSystemStamp==origHireDateStamp)
			{
				//alert(minHireDate);
				
				document.getElementById('hdnOriginalHireDate').value = minHireDate;	
			}
			else
			{
				//alert(origHireDate);
				
				document.getElementById('hdnOriginalHireDate').value = origHireDate;
			}
		}
	}
	else
	{
		//alert('p');
		if(hireDateSystemStamp==origHireDateStamp)
		{
			
		document.getElementById('hdnOriginalHireDate').value = document.getElementById('hireDate').value; 
		}
	}
	
	
}



</script>
