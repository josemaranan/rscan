
<?php
unset($sqlMainQry);
unset($rstMainQry);

$employeeeMaintenanceObj->setEmployeeSupervisorInformation($employeeID);
$fullPartTimeArray = $employeeeMaintenanceObj->getEmployeeSupervisorInformation();

$supArray = $employeeeMaintenanceObj->getSupervisors($employeeID);


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
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->width = '50%';
//$tableObj->tableStyle = 'border-collapse: collapse';
$tableObj->setTableClass('');
$tableObj->setTableAttr();


// form starts here
$htmlForm->action = 'supervisorAdd_Process_RDS.php';
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

$commonListBox->name = 'ddlSupervisors';
$commonListBox->id 	= 'ddlSupervisors';
$commonListBox->AddRow('', 'Please choose');
foreach($supArray as $supArrayK=>$supArrayV)
{
	$commonListBox->AddRow($supArrayV['employeeID'], ucwords(strtolower($supArrayV['lastName'])).', '.ucwords(strtolower($supArrayV['firstName'])));
	
}
$commonListBox->selectedItem = '';
$ddlSupervisors = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

// hidden fields
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'effectiveDate';
$htmlTextElement->id = 'effectiveDate';
//$htmlTextElement->readonly = 'true';
$htmlTextElement->accesskey = 'true';
$htmlTextElement->value = date('m/d/Y',strtotime('now'));
$txtEffectiveDate =  $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


//button Search 
$htmlButtonElement->type = 'submit';
$htmlButtonElement->name = 'Add';
$htmlButtonElement->value = 'Add';
$htmlButtonElement->style = 'text-align: center;';
$htmlButtonElement->onclick = 'return validateSup();';
$htmlButtonElement->colspan = '2';
$btnSave = $htmlButtonElement->renderHtml();

$employeeeMaintenanceObj->gethiddenValues('ddlSupervisors', $supervisorID ,'results', 'ctlEmployeeSupervisors', 'supervisorID' , 'ctlEmployeeSupervisors#supervisorID');

$lblddlSupervisors	= $htmlTextElement->addLabel($ddlSupervisors, 'Supervisor:', '#ff0000',true);
$lbltxtEffectiveDate	= $htmlTextElement->addLabel($txtEffectiveDate, 'Effective Date:', '#ff0000',true);


$tableObj->searchFields['lblddlSupervisors'] = $lblddlSupervisors; 
$tableObj->searchFields['lbltxtEffectiveDate'] = $lbltxtEffectiveDate; 
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
</script>
