<?php
//$employeeeMaintenanceObj->setUSLocations();
unset($sqlMainQry);
unset($rstMainQry);

$employeeeMaintenanceObj->setEmployeeFullTimePartTimeInformation($employeeID);
$fullPartTimeArray = $employeeeMaintenanceObj->getEmployeeFullTimePartTimeInformation();

$timingTypesArray = $employeeeMaintenanceObj->getEmployeeTimingTypes();


echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="businessRuleHeading" class="outer"');
echo 'Modify Part Time / Full Time';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo(); 

echo $htmlTagObj->openTag('div','id="singlePixelBorder" class="outer"');
echo $htmlTagObj->openTag('div','id="topHeading"');
echo 'Add Part Time / Full Time  Information';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

// form starts here
$htmlForm->action = 'add_partTime_process_RDS.php';
$htmlForm->name = 'frmPosition';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'post';
echo $htmlForm->startForm();

// hidden fields
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'employeeID';
$htmlTextElement->id = 'employeeID';
$htmlTextElement->value = $employeeID;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 3;
$tableObj->width = '50%';
$tableObj->tableStyle = 'border-collapse: collapse';
$tableObj->setTableClass('');
$tableObj->setTableAttr();

// hidden fields
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'startDate';
$htmlTextElement->id = 'startDate';
$htmlTextElement->readonly = 'true';
$htmlTextElement->accesskey = 'true';
$htmlTextElement->value = $curDate;
$txtstartDate =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


$commonListBox->name = 'ddlTimingTypes';
$commonListBox->id = 'ddlTimingTypes';
foreach($timingTypesArray as $timingTypesArrayK=>$timingTypesArrayV)
{
	$commonListBox->AddRow($timingTypesArrayV['type'], $timingTypesArrayV['description']);
	
}
$commonListBox->selectedItem = '';
$ddlTimingTypes = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

$employeeeMaintenanceObj->gethiddenValues('ddlTimingTypes', $timingType ,'Rnet', 'prmEmployeePayrollPartTimeFullTime', 'fullPartTime' , 'ctlEmployeePayrollRates#fullPartTime');

//button Search 
$htmlButtonElement->type = 'submit';
$htmlButtonElement->name = 'Add';
$htmlButtonElement->value = 'Add';
$htmlButtonElement->style = 'text-align: center;';
$htmlButtonElement->colspan = '2';
$btnSave = $htmlButtonElement->renderHtml();

$lbltxtstartDate	= $htmlTextElement->addLabel($txtstartDate, 'Effective Date:', '#ff0000',true);
$lblddlTimingTypes	= $htmlTextElement->addLabel($ddlTimingTypes, 'Full-time or Part-time:', '#ff0000','');


$tableObj->searchFields['lbltxtstartDate'] = $lbltxtstartDate; 
$tableObj->searchFields['lblddlTimingTypes'] = $lblddlTimingTypes; 
$tableObj->searchFields['btnSave'] = $btnSave;
echo '<br/>'.$tableObj->searchFormTableComponent();

echo $htmlForm->endForm();


echo $htmlTagObj->openTag('div','id="emptyDiv"  class="outer"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="scrollingdatagrid" id="scrollingdatagrid" visible="true"');
echo $employeeeMaintenanceObj->getHistoricalPartTimeFullTimeInformation($employeeID);
echo $htmlTagObj->closeTag('div');
?>


<script type="text/javascript">
	
var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"]; ?>';
$( "#startDate" ).datepicker({
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
</script>