<?php
//$employeeeMaintenanceObj->setUSLocations();

//$employData = $employeeeMaintenanceObj->getEmployeeInformation();
//$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();

/* include_once($_SERVER["DOCUMENT_ROOT"]."/Users/site_administration/siteManagement/employementDatesClass_adp.inc.php"); */

include_once($_SERVER["DOCUMENT_ROOT"]."/Users/site_administration/siteManagement/employementDatesClass_adp.inc.php");



unset($sqlMainQry);
unset($rstMainQry);
unset($datePlus30Days);
$datePlus30Days = date('m/d/Y',strtotime('+30 days'));
//echo $datePlus30Days;exit;

if(isset($_REQUEST['hireDate']))
{
	$hDate = $_REQUEST['hireDate'];
}
else
{
	$hDate = date('m/d/Y');
}


if(isset($_REQUEST['type']))
{
	$type = $_REQUEST['type'];
}
else
{
	$type = 'active';
}


if($type == 'rehire')
{
	$tp = 'none';
	$notes = 're-hire'; 
}
else
{
	$tp = 'block';
}


//limited access to locations for juan.ponder(user)
// Get ClientName Dynamically
$employeeeDates = new EmploymentDates($employeeID,'ADP',$hDate,'add');
?>
<style type="text/css">
#searchBody{
	margin-left: 0px !important;
}
#rightADPPanel {
	overflow:hidden !important;
	width:950px !important;
}

#searchFieldSet fieldset{
	width:95% !important;
}
</style>
<script language="javascript" type="text/javascript">

$(function (){
	
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
	//alert('validate');
	//alert('TermDate: '+document.form_data.termDate.value);
	
	//alert(startDateForChangeReason+' - '+isMandatoryChangeReason); return false;
	   
	if (document.form_data.termDate.value == "") 
	{
		 document.getElementById("withoutTermDateTD").style.display = "block";
		 
		 var elementJobSt = document.getElementById('ddlJobStatus').value;
   
		if(elementJobSt == "")
		{
			alert("Please Select the Job Status");
			document.form_data.ddlJobStatus.focus();
			return false;
		}
	}
	else
	{
		  document.getElementById("withoutTermDateTD").style.display = "none";
	}

	if(isMandatoryChangeReason == true && ( $.trim($("#txtChangeReason").val()) ) == '')
	{
		alert("Please enter reason");
		$("#txtChangeReason").focus();
		return false;
	}
	
	if (document.form_data.termDate.value != "") 
	{
		document.getElementById("divTerminationDetailsTH").style.display = "block";
		document.getElementById("divTerminationDetailsTD").style.display = "block";
		document.getElementById("rehireableTH").style.display = "block";
		document.getElementById("rehireableTD").style.display = "block";
		document.getElementById("lstDayWorkedTH").style.display = "block";
		document.getElementById("lstDayWorkedTD").style.display = "block";
		//document.getElementById("wasTermVolTH").style.display = "block";
		//document.getElementById("wasTermVolTD").style.display = "block";
		//document.getElementById("supConfirmTH").style.display = "block";
		//document.getElementById("supConfirmTD").style.display = "block";

		
		var elementRef1 = document.getElementById('ddlTerminationReasons').value;
		var elementRef2 = document.getElementById('ddlRehireable').value;
		var elementRef3 = document.getElementById('ddlvoluntary').value;
		var ddlNCNS = document.getElementById('ddlNCNS').value;
		
		var selTermDate = new Date(document.form_data.termDate.value);
		var plus30 = new Date(document.getElementById('hdnDatePlus30Days').value);
		var oneMonth = document.getElementById('hdnDatePlus30Days').value;
		
		var lastDayWorked = new Date(document.getElementById('lastDayWorked').value);
		var termDate = new Date(document.getElementById('termDate').value);
		
		if(selTermDate>plus30)
		{
			//alert("Term date should be limited to one month"); 
			alert('Term date should be less than '+ oneMonth); 
			document.form_data.termDate.focus();			
			return false;
		}

		if(document.getElementById('lastDayWorked').value == '')
		{
			alert("Please Select Last Working Date"); 
			document.getElementById('lastDayWorked').focus();			
			return false;
		}
		//else if(document.getElementById('lastDayWorked').value > document.getElementById('termDate').value)
		else if(lastDayWorked > termDate)
		{
			alert("Last worked date should be less than or equal to term date"); 
			document.getElementById('lastDayWorked').focus();			
			return false;
		}

		if(elementRef1 =="")
		{ 
			alert("Please Select Termination Reason"); 
			document.form_data.ddlTerminationReasons.focus();			
			return false;
		}

		if(elementRef2 =="")
		{ 
			alert("Please Select Re-Hireable"); 
			document.form_data.ddlRehireable.focus();			
			return false;
		}

		/*if(elementRef3 =="")
		{ 
			alert("Please Select Was Termination Voluntary"); 
			document.form_data.ddlvoluntary.focus();			
			return false;
		}*/
		
		if(ddlNCNS =="")
		{ 
			alert("Please Select No Call , No Show"); 
			document.form_data.ddlNCNS.focus();			
			return false;
		}

		return ValidateDate('hireDate','termDate');

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
	//$('#ddlvoluntaryDisp').val('');
	//$('#ddlvoluntaryDisp').val('loading');
	$('#NCNSDATA').html('');
	$('#NCNSTD').hide(); 
	$('#NCNSDATA').hide();
	var comStr = '';
	
	
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
				/*if(data=='true')
				{
					$('#ddlvoluntaryDisp').val('Yes');
				}
				else
				{
					$('#ddlvoluntaryDisp').val('No');	
				}*/
			}
			else
			{
				$('#ddlvoluntary').val('false');
				//$('#ddlvoluntaryDisp').val('No');
			}
			
		} 
	); 
	return false	
}
 
 
/* ncns */

function loadNCNS(termID)
{	

	$.post("getYesNoFlag.php",   
	{ 
			terRID:termID
	},   
			function(data)
			{ 
				var comStr = '';
				comStr = '<select name="ddlNCNS" id="ddlNCNS" onchange="return loadRehireLogic(this.value); return false;">';
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
				comStr  +=  '<option value="">Please Choose</option>';
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
</script>

 </script>
 <?php
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
	alert('Employee already has previous hire date of <?php echo $hDate; ?> ');
	</script>
<?php 
} 
else if($_GET[res] == 'updateTermdate')
{?>
	<script type="text/javascript">
	alert('Employee must have a term date for existing hire date');
	</script>
<?php 
}
else if($_GET[res] == 'hireDateExistedBetween')
{?>
	<script type="text/javascript">
	alert('Hire date cannot be in pre-existing range for this employee');
	</script>
<?php 
}?>


