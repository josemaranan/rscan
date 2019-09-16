<?php
//$employeeeMaintenanceObj->setUSLocations();

//$employData = $employeeeMaintenanceObj->getEmployeeInformation();
//$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();

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
unset($dynamicClient);
unset($dynamicClientName);
unset($sqlQuery);
unset($resultsSet);

$sqlQuery = "SELECT Rnet.dbo.[fn_spGetEmployeePrimaryClient] ('".$employeeID."', '".$hDate ."') ";
$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
$dynamicClientName = mssql_result($resultsSet,0,0);
unset($sqlQuery);
unset($resultsSet);

$sqlQuery = "SELECT [description] FROM ctlclients with (nolock) where clientName = '".$dynamicClientName."' ";
$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);
$dynamicClient = mssql_result($resultsSet,0,0);
unset($sqlQuery);
unset($resultsSet);

$Filter .= " employeeID = '".$employeeID."' ";
$Filter .= " and location in".$employeeeMaintenanceObj->UserDetails->Locations;
$query="SELECT [employeeID] FROM  [ctlEmployees] WITH (NOLOCK) where ".$Filter; 
$rst = $employeeeMaintenanceObj->execute($query);
$num = $employeeeMaintenanceObj->getNumRows($rst);
if($num==0)
{
	header('Location: index.php?hdnEmployeeID='.$employeeID.'&adpMode=hr&adpTask=empDates');
	exit;
}
		
$sqlEmpDet = "	SELECT 
					e.firstName, e.lastName, loc.description 
				FROM 
					ctlEmployees e WITH (NOLOCK)
				LEFT JOIN
					ctlLocations loc WITH (NOLOCK)
				ON
					e.location = loc.location
				WHERE 
					employeeID = '$employeeID' ";
$rstEmpDet = $employeeeMaintenanceObj->execute($sqlEmpDet);
while ($rowEmpDet=mssql_fetch_array($rstEmpDet)) 
{
	$empName = $rowEmpDet[firstName]." ".$rowEmpDet[lastName];
	$locDesc = $rowEmpDet[description];
}
?>

<script language="javascript" type="text/javascript">
		
function Validate()
{
	
	/*if (document.form_data.termDate.value == "") 
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
	}*/
	
	if (document.form_data.termDate.value != "") 
	{
		document.getElementById("divTerminationDetailsTH").style.display = "block";
		document.getElementById("divTerminationDetailsTD").style.display = "block";
		document.getElementById("rehireableTH").style.display = "block";
		document.getElementById("rehireableTD").style.display = "block";
		document.getElementById("wasTermVolTH").style.display = "block";
		document.getElementById("wasTermVolTD").style.display = "block";
		document.getElementById("supConfirmTH").style.display = "block";
		document.getElementById("supConfirmTD").style.display = "block";
		
		
		var elementRef1 = document.getElementById('ddlTerminationReasons').value;
		var elementRef2 = document.getElementById('ddlRehireable').value;
		var elementRef3 = document.getElementById('ddlvoluntary').value;
		var ddlNCNS = document.getElementById('ddlNCNS').value;
		var selTermDate = new Date(document.form_data.termDate.value);
		var plus30 = new Date(document.getElementById('hdnDatePlus30Days').value);
		var oneMonth = document.getElementById('hdnDatePlus30Days').value;
		if(selTermDate>plus30)
		{
			//alert("Term date should be limited to one month"); 
			alert('Term date should be less than '+ oneMonth); 
			document.form_data.termDate.focus();			
			return false;
		}
		else if(elementRef1 =="")
		{ 
			alert("Please Select Termination Reason"); 
			document.form_data.ddlTerminationReasons.focus();			
			return false;
		}
		else if(elementRef2 =="")
		{ 
			alert("Please Select Re-Hireable"); 
			document.form_data.ddlRehireable.focus();			
			return false;
		}
		else if(elementRef3 =="")
		{ 
			alert("Please Select Was Termination Voluntary"); 
			document.form_data.ddlvoluntary.focus();			
			return false;
		}
		else if(ddlNCNS =="")
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
	//populateVoluntary(termIDID);
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
 
echo $htmlTagObj->openTag('div', 'id="scrollingdatagrid" class="scrollingdatagrid" visible="true"');
echo $htmlTagObj->openTag('div', 'id="adpsearchFieldSet"');
$htmlForm->fieldSet = TRUE;
$formLegend = $htmlForm->addLegend('Employment Dates');

$locationText =  $locDesc;

$employeeIdText = $employeeID;

$nameText = $empName;

$htmlTextElement->name = 'hireDate';
$htmlTextElement->id = 'hireDate';
$htmlTextElement->value = $hDate;
$htmlTextElement->readonly = 'true';
$htmlTextElement->size = '7';
$htmlTextElement->accesskey = 'true';
$hireDate = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('hireDate', '' ,'Rnet', 'PrmEmployeeCareerHistory', 'hireDate' ,  'prmEmployeeCareerHistory#hireDate');

$htmlTextElement->name = 'termDate';
$htmlTextElement->id = 'termDate';
$htmlTextElement->value = $tDate;
$htmlTextElement->readonly = 'true';
$htmlTextElement->size = '7';
$htmlTextElement->accesskey = 'true';
$termDate = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('termDate', '' ,'Rnet', 'PrmEmployeeCareerHistory', 'termDate' ,  'prmEmployeeCareerHistory#termDate');


$sqlTermResaons = " SELECT * from ctlTerminationReasons WITH (NOLOCK) WHERE isActive = 'Y' ORDER BY terminationReason ";
$rstTermResaons = $employeeeMaintenanceObj->execute($sqlTermResaons);
$num_rows = mssql_num_rows($rstTermResaons);

if($num_rows >= 1) 
{
		$resultTermination = $employeeeMaintenanceObj->bindingInToArray($rstTermResaons);
}

foreach($resultTermination as $id => $row)
{
	$terminationArray[$row['terminationReasonID']] = $row['terminationReason'];
}

$commonListBox->name = 'ddlTerminationReasons';
$commonListBox->id 	= 'ddlTerminationReasons';
$commonListBox->customArray = $terminationArray;
$commonListBox->optionKey = 'terminationReasonID';
$commonListBox->optionVal = 'description';
$commonListBox->onChange = 'return populateVolumeReduction(this.value); return false;';
$ddlTerminationReasons .= $commonListBox->AddRow('', 'Please choose');
$ddlTerminationReasons .= $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

$volumereduction = $htmlTagObj->openTag('div', 'id="volumereductiondata" style="display:none;"');
$volumereduction .= $htmlTagObj->closeTag('div');
$volumereductionLabel = !empty($dynamicClient) ? $dynamicClient : ''; 
$volumereductionLabel .= 'Volume Reduction ?';

$rehireableTHArray = array('0' => 'Yes', '1' => 'No');
$commonListBox->name = 'ddlRehireable';
$commonListBox->id 	= 'ddlRehireable';
$commonListBox->customArray = $rehireableTHArray;
$commonListBox->optionKey = 'ddlRehireable';
$commonListBox->optionVal = 'description';
$commonListBox->selectedItem = $Rehireable;
$ddlRehireable .= $commonListBox->AddRow('', 'Please choose');
$ddlRehireable .= $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('hireDate', '' ,'Rnet', 'PrmEmployeeCareerHistory', 'hireDate' ,  'prmEmployeeCareerHistory#hireDate');

$ddlvoluntary = $htmlTagObj->openTag('div', 'id="newIsVolTerm"');
$ddlvoluntary .= $htmlTagObj->closeTag('div');

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'ddlvoluntary';
$htmlTextElement->id = 'ddlvoluntary';
$htmlTextElement->value = '';
$ddlvoluntary .= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$curDate =  date('m/d/Y');
$sqlSUPERV = "	".$sqlTemEmployeeSupervisor."
			EXEC RNet.dbo.[standard_spEmployeeSupervisor] '%','$employeeID','$curDate'
			SELECT supervisorID FROM #tempEmployeeSupervisor WITH (NOLOCK)  ";
$rstSUP = $employeeeMaintenanceObj->execute($sqlSUPERV);
if($row=mssql_fetch_array($rstSUP)) 
{	
	$supervisorID = $row[supervisorID];
}
mssql_free_result($rstSUP);
$htmlTextElement->name = 'chkSupervisorConfirm';
$htmlTextElement->id = 'chkSupervisorConfirm';
$htmlTextElement->value = 'Y';
if($supervisorID != $_SESSION['empID']) 
{
	$htmlTextElement->disabled = 'disabled';
}
$htmlTextElement->type = 'checkbox'; //isDefaultChkd
$chkSupervisorConfirm = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$notesTxt = $htmlTagObj->textAreatag('name="notes" id="notes" cols="40" rows="4"', $notes);

$sqlJOBST = " IF OBJECT_ID('tempdb.dbo.#tempEmployeeStatus') IS NOT NULL
				DROP TABLE #tempEmployeeStatus
				
				CREATE TABLE #tempEmployeeStatus
				(
					employeeID INT NULL,
					effectiveDate DATETIME NULL
				)	
				
				INSERT INTO #tempEmployeeStatus
				SELECT 
					employeeID , 
					MAX(effectiveDate) 
				FROM 
					ctlEmployeeStatuses 
				WHERE 
					employeeID = '".$employeeID."'
				GROUP BY 
					employeeID
				
				SELECT 
					a.employmentStatus 
				FROM 
						ctlEmployeeStatuses a WITH (NOLOCK)
				JOIN
					#tempEmployeeStatus b WITH (NOLOCK)
				ON
					a.employeeID = b.employeeID
				AND
					a.effectiveDate = b.effectiveDate";
$rstJOBST = $employeeeMaintenanceObj->execute($sqlJOBST);
if ($row=mssql_fetch_array($rstJOBST)) 
{	
	$empStatusID = $row[employmentStatus];
}
if($res == 'dateexisted') 
{
	$empStatusID = $_GET["jobStatus"];
}

$SQL = " SELECT description,employmentStatus FROM ctlEmploymentStatuses WITH (NOLOCK) WHERE employmentStatus != 2 ORDER BY description ";
$rst = $employeeeMaintenanceObj->execute($SQL);
$num_rowsSD = $employeeeMaintenanceObj->getNumRows($rst);
if($num_rowsSD >= 1) 
{
	$result = $employeeeMaintenanceObj->bindingInToArray($rst);
}

foreach($result as $id => $row)
{
	 if($row[description]== 'PRODUCTION' || $row[description]== 'TERMINATED' || $row[description]== 'LOA' || $row[description]== 'NEWHIRE TRAINING'|| $row[description]== 'NESTING') 
	 {
		 $woTermDate[$row[employmentStatus]] = $row[description];
	 }
}

$withoutTermDateTD = 'Please select an employment status for this employee:<br />';
	
$commonListBox->name = 'withoutTermDateTD';
$commonListBox->id 	= 'withoutTermDateTD';
$commonListBox->customArray = $woTermDate;
$commonListBox->optionKey = 'employmentStatus';
$commonListBox->optionVal = 'description';
$commonListBox->selectedItem = $empStatusID;
$withoutTermDateTD .= $commonListBox->AddRow('', 'Please choose');
$withoutTermDateTD .= $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

$withoutTermDateTD .= '<br /><br />	Ensure that this employee has an Active Directory login';

$htmlButtonElement->id = 'btnUpdate';
$htmlButtonElement->name = 'btnUpdate';
$htmlButtonElement->value = 'Submit';
$htmlButtonElement->onclick = 'return Validate();';
$htmlButtonElement->style = 'text-align: left;';
$htmlButtonElement->type = 'submit';
$sbmButton = $htmlButtonElement->renderHtml();
$htmlButtonElement->resetProperties();


//###################Labeling the form elements
$locationText	= $htmlTextElement->addLabel($locationText, 'Location', '#ff0000','');
$employeeIdText	= $htmlTextElement->addLabel($employeeIdText, 'Employee ID', '#ff0000','');
$nameText		= $htmlTextElement->addLabel($nameText, 'Name:', '#ff0000','');
$hireDate		= $htmlTextElement->addLabel($hireDate, 'Hire Date', '#ff0000','');
$termDate		= $htmlTextElement->addLabel($termDate, 'Term Date', '#ff0000','');

$htmlTextElement->thStyle = 'display: none;';
$htmlTextElement->tdStyle = 'display: none;';
$htmlTextElement->rnetv4ThID = 'divTerminationDetailsTH';
$htmlTextElement->rnetv4TdID = 'divTerminationDetailsTD';
$ddlTerminationReasons  = $htmlTextElement->addLabel($ddlTerminationReasons, 'Termination Reasons', '#ff0000','');

$htmlTextElement->thStyle = 'display: none;';
$htmlTextElement->tdStyle = 'display: none;';
$htmlTextElement->rnetv4ThID = 'volumereductionlable';
$volumereduction		  = $htmlTextElement->addLabel($volumereduction, $volumereductionLabel, '#ff0000','');

$ddlNCNSTD = $htmlTagObj->openTag('div', 'id="NCNSDATA" style="display:none"');
$commonListBox->name = 'ddlNCNS';
$commonListBox->id 	= 'ddlNCNS';
$commonListBox->optionKey = '';
$commonListBox->optionVal = '';
$ddlNCNSTD .= $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$ddlNCNSTD .= $htmlTagObj->closeTag('div');

$htmlTextElement->thStyle 		= 'display: none;';
//$htmlTextElement->tdStyle 		= 'display: none;';
$htmlTextElement->rnetv4ThID 	= 'NCNSTD';
$ddlNCNSTD		 				= $htmlTextElement->addLabel($ddlNCNSTD, 'No Call, No Show ?', '#ff0000','');

$htmlTextElement->thStyle = 'display: none;';
$htmlTextElement->tdStyle = 'display: none;';
$htmlTextElement->rnetv4ThID = 'rehireableTH';
$htmlTextElement->rnetv4TdID = 'rehireableTD';
$ddlRehireable  = $htmlTextElement->addLabel($ddlRehireable, 'Re-Hireable?', '#ff0000','');

$htmlTextElement->thStyle = 'display: none;';
$htmlTextElement->tdStyle = 'display: none;';
$htmlTextElement->rnetv4ThID = 'wasTermVolTH';
$htmlTextElement->rnetv4TdID = 'wasTermVolTD';
$ddlvoluntary  = $htmlTextElement->addLabel($ddlvoluntary, 'Was termination voluntary?', '#ff0000','');

$htmlTextElement->thStyle = 'display: none;';
$htmlTextElement->tdStyle = 'display: none;';
$htmlTextElement->rnetv4ThID = 'supConfirmTH';
$htmlTextElement->rnetv4TdID = 'supConfirmTD';
$chkSupervisorConfirm  = $htmlTextElement->addLabel($chkSupervisorConfirm, 'Supervisor Confirmation', '#ff0000','');

$notesTxt  = $htmlTextElement->addLabel($notesTxt, 'Notes', '#ff0000','');
$emptyTr  = $htmlTextElement->addLabel('&nbsp;', '', '#ff0000','');
$htmlTextElement->thStyle = 'display: none;';
$htmlTextElement->tdStyle = 'display: none;';
$htmlTextElement->colspan = '2';
$htmlTextElement->rnetv4TdID = 'withoutTermDateTD';
$withoutTermDateTD  = $htmlTextElement->addLabel($withoutTermDateTD, '', '#ff0000','');

$emptyTr1  = $htmlTextElement->addLabel('&nbsp;', '', '#ff0000','');

//Form block 1
$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->setTableClass('');
$tableObj->setTableAttr();

$tableObj->searchFields['locationText'] = $locationText;
$tableObj->searchFields['newLine'] = '\n';

$tableObj->searchFields['employeeIdText'] = $employeeIdText;
$tableObj->searchFields['newLine1'] = '\n';

$tableObj->searchFields['nameText'] = $nameText;
$tableObj->searchFields['newLine2'] = '\n';

$tableObj->searchFields['hireDate'] = $hireDate;
$tableObj->searchFields['newLine3'] = '\n';

$tableObj->searchFields['termDate'] = $termDate;
$tableObj->searchFields['newLine4'] = '\n';

$tableObj->searchFields['ddlTerminationReasons'] = $ddlTerminationReasons;
$tableObj->searchFields['newLine5'] = '\n';

$tableObj->searchFields['volumereduction'] = $volumereduction;
$tableObj->searchFields['newLine6'] = '\n';

$tableObj->searchFields['ddlNCNSTD'] = $ddlNCNSTD;
$tableObj->searchFields['newLine7'] = '\n';

$tableObj->searchFields['ddlRehireable'] = $ddlRehireable;
$tableObj->searchFields['newLine8'] = '\n';

$tableObj->searchFields['ddlvoluntary'] = $ddlvoluntary;
$tableObj->searchFields['newLine9'] = '\n';

$tableObj->searchFields['chkSupervisorConfirm'] = $chkSupervisorConfirm;
$tableObj->searchFields['newLine10'] = '\n';

$tableObj->searchFields['notesTxt'] = $notesTxt;
$tableObj->searchFields['newLine11'] = '\n';

$tableObj->searchFields['emptyTr'] = $emptyTr;
$tableObj->searchFields['newLine12'] = '\n';

$tableObj->searchFields['withoutTermDateTD'] = $withoutTermDateTD;
$tableObj->searchFields['emptyTr1'] = $emptyTr1;
$tableObj->searchFields['newLine13'] = '\n';

$tableObj->searchFields['button'] = $sbmButton;

$searchForm = $tableObj->searchFormTableComponent();

//Html Form starts here
$htmlForm->action = 'adp_employee_careerHistory_Add_Process_RDS.php?employeeID=' . $employeeID . '&type=' . $type;
$htmlForm->name = 'form_data';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'POST';


echo $formLegend;
echo $htmlForm->startForm();

$htmlTextElement->type='hidden';
$htmlTextElement->name='hdnhDate';
$htmlTextElement->id='hdnhDate';
$htmlTextElement->value = $hDate;
echo $hdnDate1	= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type='hidden';
$htmlTextElement->name='hdntDate';
$htmlTextElement->id='hdntDate';
$htmlTextElement->value = $tDate;
echo $hdnDate2	= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type='hidden';
$htmlTextElement->name='hdnDatePlus30Days';
$htmlTextElement->id='hdnDatePlus30Days';
$htmlTextElement->value = $datePlus30Days;
echo $hdnPlusDays	= $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

echo $searchForm;

echo $htmlTagObj->closeTag('fieldset');
echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');
 
 
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

<script language="javascript" type="text/javascript">
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
});
</script>