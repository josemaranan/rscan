<?php
//$employeeeMaintenanceObj->setUSLocations();
$locArray = $employeeeMaintenanceObj->getUsLocations();
$reportinglocArray = $employeeeMaintenanceObj->getUSReportingLocations();
$payGroupLocationArray = $employeeeMaintenanceObj->getUSPayGroupLocations();
$allLocationArray = $employeeeMaintenanceObj->getAllLocations();

$locArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($locArray, 'location','description');
$reportinglocArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($reportinglocArray, 'location','description');
$payGroupLocationArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($payGroupLocationArray, 'location','paygroup');

$allLocationArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($allLocationArray, 'location','description');

$maxPayrollLocation = $employeeeMaintenanceObj->getEmployeeCurrentPayrollLocation($employeeID);
$usLocationwithOutCorporate = $employeeeMaintenanceObj->getLocationsWithOutCorporate();

unset($usLocationArry03062013);
foreach($locArrayKeyValue as $locArrayKeyValueK=>$locArrayKeyValueV)
{
	$usLocationArry03062013 .= $locArrayKeyValueK.',';
}
$usLocationArry03062013 = substr($usLocationArry03062013,0,-1);


$locationBasedState = $employeeeMaintenanceObj->getLocationBasedState($employADPData[0]['location']);


echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="businessRuleHeading" class="outer"');
echo 'ADP Data';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo(); 

echo $htmlTagObj->openTag('div','id="singlePixelBorder"');
echo $htmlTagObj->openTag('div','id="topHeading"');
echo 'Modify Payroll Information';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="scrollingdatagrid" id="scrollingdatagrid" visible="true"');

// form starts here
$htmlForm->action = 'employeeADPinfo_process.php';
$htmlForm->name = 'employeeADPinfo';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'post';
echo $htmlForm->startForm();


$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 3;
$tableObj->width = '50%';
$tableObj->tableStyle = 'border-collapse: collapse';
$tableObj->setTableClass('');
$tableObj->setTableAttr();



// define elements

$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtADPJobCode';
$htmlTextElement->id = 'txtADPJobCode';
$htmlTextElement->disabled = 'true';
//$htmlTextElement->accesskey = 'true';
$htmlTextElement->value = stripslashes($employADPData[0]['adpJobCode']);
$txtADPJobCode =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtADPEffecDate';
$htmlTextElement->id = 'txtADPEffecDate';
$htmlTextElement->readonly = 'true';
$htmlTextElement->accesskey = 'true';
$htmlTextElement->value = $curDate;
$txtADPEffecDate =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$lbltxtADPJobCode	= $htmlTextElement->addLabel($txtADPJobCode, 'Job Code :', '#ff0000',true);
$lbltxtADPEffecDate	= $htmlTextElement->addLabel($txtADPEffecDate, 'Effective Date:', '#ff0000',true);

// ddl adp pay group
$commonListBox->name = 'ddlADPPayGroup';
$commonListBox->id = 'ddlADPPayGroup';
$commonListBox->AddRow('', 'Please choose');
foreach($payGroupLocationArray as $payGroupQueryK=>$payGroupQueryV)
{
	$commonListBox->AddRow($payGroupQueryV[location], $payGroupQueryV[paygroup]);
	
}
$commonListBox->selectedItem = $employData[0]['payrollLocation'];
$ddlADPPayGroup = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

$employeeeMaintenanceObj->gethiddenValues('ddlADPPayGroup', stripslashes($employData[0]['payrollLocation']) ,'results', 'ctlEmployees', 'payrollLocation', 'ctlEmployees#payrollLocation');

if(!empty($maxPayrollLocation))
{
	$ddlADPPayGroup .= '<div id="divFLAG_1">';
	$ddlADPPayGroup .= '<input type="hidden" name="flagI" id="flagI" value="">';
	$employeeeMaintenanceObj->gethiddenValues('flagI', '' ,'results', 'ctlEmployees', 'payrollLocation' , 'ctlEmployees#flagI');
	
	$ddlADPPayGroup .= '</div>';
}
// end ddl 


$commonListBox->name = 'ddlADPWorkLocation';
$commonListBox->id = 'ddlADPWorkLocation';
$commonListBox->AddRow('', 'Please choose');
foreach($allLocationArray as $locArray22K=>$locArray22V)
{
	if($locArray22V[location] != 806) // Removing GSS LOCATION
	{
		$commonListBox->AddRow($locArray22V[location], $locArray22V[description]);
	}
	
}
$commonListBox->selectedItem = $employADPData[0]['location'];
$commonListBox->onChange = "return getStateValue(this.value , '".$locationBasedState."' , '".$usLocationArry03062013."'); return false;";
$ddlADPWorkLocation = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$ddlADPWorkLocation .= '<div id="LOCATIONBASEDSTATE"></div>';

$lblddlADPPayGroup	= $htmlTextElement->addLabel($ddlADPPayGroup, 'Pay Group (ADP) :', '#ff0000',true);
$lblddlADPWorkLocation	= $htmlTextElement->addLabel($ddlADPWorkLocation, 'Work Location:', '#ff0000',true);


$commonListBox->name = 'ddlADPRepLocation';
$commonListBox->id = 'ddlADPRepLocation';
$commonListBox->AddRow('', 'Please choose');
foreach($reportinglocArray as $locArrayK=>$locArrayV)
{
	if($locArrayV[location] == 806) 
	{ 
		$optionValue =  'GSS';
	} 
	else 
	{ 
		$optionValue =  $locArrayV[location];
	}	
	$commonListBox->AddRow($optionValue, $locArrayV[description]);
	
}
$commonListBox->selectedItem = $employADPData[0]['adpReportingLocation'];
$ddlADPRepLocation = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

 $employeeeMaintenanceObj->gethiddenValues('ddlADPRepLocation', stripslashes($employADPData[0]['adpReportingLocation']) ,'results', 'ctlEmployees', 'adpReportingLocation' , 'ctlEmployees#adpReportingLocation');
 
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtADPFileNo';
$htmlTextElement->id = 'txtADPFileNo';
$htmlTextElement->disabled = 'true';
//$htmlTextElement->accesskey = 'true';
$htmlTextElement->value = str_pad($employeeID,6,0,STR_PAD_LEFT);
$txtADPFileNo =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
 
$lblddlADPRepLocation	= $htmlTextElement->addLabel($ddlADPRepLocation, 'Reporting Location:', '#ff0000',true);
$lbltxtADPFileNo	= $htmlTextElement->addLabel($txtADPFileNo, 'File Number:', '#ff0000',true);

$htmlTextElement->name = 'chkGSS';
$htmlTextElement->id = 'chkGSS';
$htmlTextElement->value = 'Y';
$htmlTextElement->type = 'checkbox'; //isDefaultChkd
$htmlTextElement->isDefaultChkd = ($employADPData[0]['GSS']=='Y') ? '1' : ''; 
$chkGSS = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

// hidden fields
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnChkGSS';
$htmlTextElement->id = 'hdnChkGSS';
$htmlTextElement->value = $employADPData[0]['GSS'];
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('hdnChkGSS', stripslashes($employADPData[0]['GSS']) ,'results', 'ctlEmployees', 'GSS' , 'ctlEmployees#GSS');


$htmlTextElement->name = 'chkVirtual';
$htmlTextElement->id = 'chkVirtual';
$htmlTextElement->value = 'Y';
$htmlTextElement->type = 'checkbox'; //isDefaultChkd
$htmlTextElement->isDefaultChkd = ($employADPData[0]['virtual']=='Y') ? '1' : '';
if($employData[0]['payrollLocation']!='800')
{
	$htmlTextElement->disabled = 'true';
}
$chkVirtual = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

 
$lblchkGSS	= $htmlTextElement->addLabel($chkGSS, 'GSS:', '#ff0000','');
$lblchkVirtual	= $htmlTextElement->addLabel($chkVirtual, 'Virtual:', '#ff0000','');


$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtADPCompRate';
$htmlTextElement->id = 'txtADPCompRate';
$htmlTextElement->disabled = 'true';
$htmlTextElement->value = $employADPData[0]['amount'];
$txtADPCompRate =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


$commonListBox->name = 'ddlADPEmployeeType';
$commonListBox->id = 'ddlADPEmployeeType';
$commonListBox->AddRow('', 'Please choose');
$commonListBox->AddRow('1', 'Salary (Exempt)');
$commonListBox->AddRow('2', 'Hourly (Non-Exempt)');
$commonListBox->selectedItem = $employData[0]['payType'];
$ddlADPEmployeeType = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();   //  need to disable

// hidden fields
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnCOMPFREQUENCY';
$htmlTextElement->id = 'hdnCOMPFREQUENCY';
$htmlTextElement->value = stripslashes($employData[0]['payType']);
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$employeeeMaintenanceObj->gethiddenValues('hdnCOMPFREQUENCY', stripslashes($employData[0]['payType']) ,'results', 'ctlEmployeePayrollRates', 'comFrequency' , 'ctlEmployeePayrollRates#comFrequency');

$lbltxtADPCompRate	= $htmlTextElement->addLabel($txtADPCompRate, 'Compensation Rate:', '#ff0000','');
$lblddlADPEmployeeType	= $htmlTextElement->addLabel($ddlADPEmployeeType, 'Employee Type:', '#ff0000','');

$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtADPCompFreq';
$htmlTextElement->id = 'txtADPCompFreq';
$htmlTextElement->disabled = 'true';
$htmlTextElement->value = 'Bi-weekly';
$txtADPCompFreq =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtADPCompCode';
$htmlTextElement->id = 'txtADPCompCode';
$htmlTextElement->disabled = 'true';
$htmlTextElement->value = $employADPData[0]['adpWorkersCompCode'];
$txtADPCompCode =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$lbltxtADPCompFreq	= $htmlTextElement->addLabel($txtADPCompFreq, 'Compensation Frequency:', '#ff0000','');
$lbltxtADPCompCode	= $htmlTextElement->addLabel($txtADPCompCode, 'Worker\'s Comp Code:', '#ff0000','');


$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtADPEEOClass';
$htmlTextElement->id = 'txtADPEEOClass';
$htmlTextElement->disabled = 'true';
$htmlTextElement->value = $employADPData[0]['EEO1Class'];
$txtADPEEOClass =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtFLSA';
$htmlTextElement->id = 'txtFLSA';
$htmlTextElement->disabled = 'true';
$htmlTextElement->value = $employADPData[0]['FLSASts'];
$txtFLSA =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$lbltxtADPEEOClass	= $htmlTextElement->addLabel($txtADPEEOClass, 'EEO Class:', '#ff0000','');
$lbltxtFLSA	= $htmlTextElement->addLabel($txtFLSA, 'FLSA:', '#ff0000','');

// hidden fields
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnEmployeeID';
$htmlTextElement->id = 'hdnEmployeeID';
$htmlTextElement->value = $employeeID;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

//button Search 
$htmlButtonElement->type = 'submit';
$htmlButtonElement->name = 'Submit';
$htmlButtonElement->value = 'Save';
$htmlButtonElement->style = 'text-align: center;';
$htmlButtonElement->onclick = "return validateADPInfo('".$curDate."','".$usLocationwithOutCorporate.",'".$maxPayrollLocation."');";
$htmlButtonElement->colspan = '2';
$btnSave = $htmlButtonElement->renderHtml();
/*--------------------
-----------------*/

$tableObj->searchFields['lbltxtADPJobCode'] = $lbltxtADPJobCode; 
$tableObj->searchFields['lbltxtADPEffecDate'] = $lbltxtADPEffecDate;
$tableObj->searchFields['lblddlADPPayGroup'] = $lblddlADPPayGroup; 
$tableObj->searchFields['lblddlADPWorkLocation'] = $lblddlADPWorkLocation; 
$tableObj->searchFields['lblddlADPRepLocation'] = $lblddlADPRepLocation; 
$tableObj->searchFields['lbltxtADPFileNo'] = $lbltxtADPFileNo; 
$tableObj->searchFields['lblchkGSS'] = $lblchkGSS; 
$tableObj->searchFields['lblchkVirtual'] = $lblchkVirtual; 
$tableObj->searchFields['lbltxtADPCompRate'] = $lbltxtADPCompRate; 
$tableObj->searchFields['lblddlADPEmployeeType'] = $lblddlADPEmployeeType; 
$tableObj->searchFields['lbltxtADPCompFreq'] = $lbltxtADPCompFreq; 
$tableObj->searchFields['lbltxtADPCompCode'] = $lbltxtADPCompCode; 
$tableObj->searchFields['lbltxtADPEEOClass'] = $lbltxtADPEEOClass; 
$tableObj->searchFields['lbltxtFLSA'] = $lbltxtFLSA; 
$tableObj->searchFields['btnSave'] = $btnSave;
echo '<br/>'.$tableObj->searchFormTableComponent();

echo $htmlForm->endForm();

echo $htmlTagObj->closeTag('div');
?>

<script language="javascript" type="text/javascript">

$("#ddlADPEmployeeType").attr('disabled','disabled');

var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"]; ?>';
$( "#txtADPEffecDate" ).datepicker({
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

function getStateValue(location, prevState, usLocationArray)
{
	//alert(usLocationArray);
	if(location!='')
	{
		var fieldFlag = false;
		if(usLocationArray.search(location)!='-1')
		{
				//alert('se');
				var fieldFlag = true;
		}
		else
		{
				var fieldFlag = false;
		}
		if(fieldFlag)
		{
			htmlData('getLocationBasedState.php','location='+location+'&prevState='+prevState,'LOCATIONBASEDSTATE');
		}
	}
}
</script>