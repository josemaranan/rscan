<?php
echo '<link rel="stylesheet" type="text/css" href="../RNetIncludes/tokeninput/css/token-input.css">';
echo '<link rel="stylesheet" type="text/css" href="../RNetIncludes/tokeninput/css/token-input-facebook.css">';
echo '<script type="text/javascript" src="../RNetIncludes/tokeninput/js/jquery.tokeninput.js" language="javascript"></script>';

unset($sqlMainQry);
unset($rstMainQry);

$employeeeMaintenanceObj->setEmployeeSupervisorInformation($employeeID);
$fullPartTimeArray = $employeeeMaintenanceObj->getEmployeeSupervisorInformation();

$supArray = $employeeeMaintenanceObj->getSupervisors($employeeID);

//  token input
$sqlLocC = "SELECT location, corporateAccess FROM  [ctlEmployees] WITH (NOLOCK) WHERE employeeID = " .$employeeID;
$rstLocC = $employeeeMaintenanceObj->execute($sqlLocC);
while($rowLocC = mssql_fetch_assoc($rstLocC)) 
{	
	$locationID = $rowLocC['location'];
	$businessFunction = $rowLocC['corporateAccess'];
}
mssql_free_result($rstLocC);

$SQLbsfn = ($businessFunction == "Y") ? " EXEC rnet.dbo.report_spPopulateSupervisors $locationID, 'Y'" : " EXEC rnet.dbo.report_spPopulateSupervisors $locationID, 'N'";
// token input
$rstTokens = $employeeeMaintenanceObj->execute($SQLbsfn);
$tokenInputsArr = array();
if(!empty($rstTokens))
{
	
	//$RDSObj->bindingInToArray($rstTokens);
	while($row = mssql_fetch_assoc($rstTokens))
	{
		$listOfTokens['fullName'] = $row[firstName].' '.$row[lastName];
		$listOfTokens['employeeID'] = $row[employeeID];
		$listOfTokens['supervisorName'] = $row[supervisorName];
		$listOfTokens['location'] = $row[location];
		$listOfTokens['position'] = $row[position];
		$tokenInputsArr[] = $listOfTokens;
	}
	 
}
$json_array = json_encode($tokenInputsArr);
//print_r($json_array); exit;
// /END / token input

echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="businessRuleHeading" class="outer"');
echo 'Modify Supervisor';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo(); 

echo $htmlTagObj->openTag('div','id="singlePixelBorder" class="outer"');

echo $htmlTagObj->openTag('div','id="topHeading"');
echo 'Add Supervisor';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');


$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 1;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->width = '50%';
//$tableObj->tableStyle = 'border-collapse: collapse';
$tableObj->setTableClass('');
$tableObj->setTableAttr();


// form starts here
$htmlForm->action = 'supervisorAdd_Process.php';
$htmlForm->name = 'frmPosition';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'post';
echo $htmlForm->startForm();

// To disable finalized pay periods. 
print($employeeeMaintenanceObj->getValidateCalendarControls($employeeID, 'Min')); 

// hidden fields
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'employeeID';
$htmlTextElement->id = 'employeeID';
$htmlTextElement->value = $employeeID;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();



/*
$commonListBox->name = 'ddlSupervisors';
$commonListBox->id = 'ddlSupervisors';
$commonListBox->AddRow('', 'Please choose');
foreach($supArray as $supArrayK=>$supArrayV)
{
	//$commonListBox->AddRow($supArrayV['employeeID'], ucwords(strtolower($supArrayV['lastName'])).', '.ucwords(strtolower($supArrayV['firstName'])));
	
}
$commonListBox->selectedItem = '';
$ddlSupervisors = $commonListBox->display();
$commonListBox->resetProperties();*/

/*
$commonListBox->name = 'ddlSupervisors';
$commonListBox->id 	= 'ddlSupervisors';
$commonListBox->AddRow('', 'Please choose');
foreach($supArray as $supArrayK=>$supArrayV)
{
	$commonListBox->AddRow($supArrayV['employeeID'], ucwords(strtolower($supArrayV['lastName'])).', '.ucwords(strtolower($supArrayV['firstName'])));
	
}
$commonListBox->selectedItem = '';
$ddlSupervisors = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();*/


$htmlTextElement->name='ddlSupervisors';  // Did not change id , name due to Token Input
$htmlTextElement->id='ddlSupervisors';
$htmlTextElement->value = '';
$htmlTextElement->type = 'hidden';
echo $htmlTextElement->renderHtml();

// hidden fields
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'effectiveDate';
$htmlTextElement->id = 'effectiveDate';
//$htmlTextElement->readonly = 'true';
$htmlTextElement->accesskey = 'true';
$htmlTextElement->value = date('m/d/Y',strtotime('now'));
$txtEffectiveDate =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->name='blah';
$htmlTextElement->id='txtSupervisor';
$htmlTextElement->value = '';
$txtSupervisor	= $htmlTextElement->renderHtml();

$textArea = $htmlTagObj->textAreatag('rows="4" cols="50" id="txaReason" name="txaReason" ', '')  ;


//button Search 
$htmlButtonElement->type = 'submit';
$htmlButtonElement->name = 'Add';
$htmlButtonElement->value = 'Add';
$htmlButtonElement->style = 'text-align: center;';
$htmlButtonElement->onclick = 'return validateSup2();';
$htmlButtonElement->colspan = '2';
$btnSave = $htmlButtonElement->renderHtml();

$employeeeMaintenanceObj->gethiddenValues('ddlSupervisors', $supervisorID ,'results', 'ctlEmployeeSupervisors', 'supervisorID' , 'ctlEmployeeSupervisors#supervisorID');

$lblddlSupervisors	= $htmlTextElement->addLabel($txtSupervisor, 'Supervisor:', '#ff0000',true);
$lbltxtEffectiveDate	= $htmlTextElement->addLabel($txtEffectiveDate, 'Effective Date:', '#ff0000',true);
$textAreaStr 			= $htmlTextElement->addLabel($textArea, 'Change Reason', '', TRUE);



$tableObj->searchFields['lblddlSupervisors'] = $lblddlSupervisors; 
$tableObj->searchFields['lbltxtEffectiveDate'] = $lbltxtEffectiveDate; 
$tableObj->searchFields['textAreaStr'] 	= $textAreaStr;
$tableObj->searchFields['btnSave'] = $btnSave;
echo $tableObj->searchFormTableComponent();



echo $htmlForm->endForm();


echo $htmlTagObj->openTag('div','id="emptyDiv"  class="outer"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="scrollingdatagrid" id="scrollingdatagrid" visible="true"');
echo $employeeeMaintenanceObj->getHistoricalSupervisorInformation($employeeID);
echo $htmlTagObj->closeTag('div');
?>


<script type="text/javascript">
	
var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"] ?>';
$( "#effectiveDate" ).datepicker({
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
$(document).ready(function() {
	var objArr = <?php echo $json_array ?>;   
	$("#txtSupervisor").tokenInput(
		objArr,
		{
			propertyToSearch: "fullName",
			resultsFormatter: function(item){ return "<li>" + item.fullName + " | " + item.location + " | " + item.position + "</li>" },
			tokenFormatter: function(item) { return "<li>" + item.fullName + " | " + item.location + " | " + item.position + "</li>" },
			tokenLimit: 1,
			theme: "facebook",
			onAdd: function(item) { $("#ddlSupervisors").val(item.employeeID); },
			onDelete: function(item) { $("#ddlSupervisors").val(''); }
		}
	);
	//makeItDynamic();

	$("#rnetv4_Change_Reason").parent('tr').hide();

	$("#effectiveDate").change( function () {

			var minDate = $("#hdnCalendarMinStartDate").val();
			var effectiveDateVal = new Date(this.value);
			var payPeriodMin = new Date(minDate);

			if(effectiveDateVal >= payPeriodMin)
			{
				$("#rnetv4_Change_Reason").parent('tr').hide();
			}
			else
			{
				$("#rnetv4_Change_Reason").parent('tr').show();
			}
		})

		
});

function validateSup2()
{
	var chkToken = $("#ddlSupervisors").val();
	var txtChangeReason = $("#txaReason").val();
	var isSubmit  = true;
	
	if( $("#rnetv4_Change_Reason").parent('tr').css('display') != 'none' )
	{
		var isTextAreaVisible = true;
	}
	else
	{
		var isTextAreaVisible = false;
	}
	
	//alert(chkToken);
	if(chkToken == '')
	{
		$("#txtSupervisor").focus();
		alert("Please enter supervisor name");
		isSubmit = false;
		return false;
	}

	else if(txtChangeReason=="" && isTextAreaVisible)
	{
		alert("Please enter change reason");
		$("#txaReason").focus();
		isSubmit = false;
		return false;
	}

	if(isSubmit)
	{
		return true;
	}
	else
	{
		return false;
	}
}
</script>
