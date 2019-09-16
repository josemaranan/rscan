<?php
//$employeeeMaintenanceObj->setUSLocations();
unset($sqlMainQry);
unset($rstMainQry);

$employeeeMaintenanceObj->setADPClients();
$clientsArray = $employeeeMaintenanceObj->getADPClients();


echo $htmlTagObj->openTag('div','id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="businessRuleHeading" class="outer"');
echo 'Modify Client';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','class="outer" id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo(); 

echo $htmlTagObj->openTag('div','id="singlePixelBorder" class="outer"');

echo $htmlTagObj->openTag('div','id="topHeading"');
echo 'Modify Client';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

// form starts here
$htmlForm->action = 'employeeClient_process_RDS.php';
$htmlForm->name = 'employeeClientinfo';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'post';
echo $htmlForm->startForm();

$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 3;
$tableObj->width = '50%';
$tableObj->setTableClass('');
$tableObj->setTableAttr();


$commonListBox->name = 'ddlADPClientCode';
$commonListBox->id = 'ddlADPClientCode';
$commonListBox->AddRow('', 'Please choose');
foreach($clientsArray as $clientsArrayK=>$clientsArrayV)
{
	$commonListBox->AddRow($clientsArrayV['clientCode'], $clientsArrayV['clientDescription']);
	
}
$commonListBox->selectedItem = $employADPData[0]['adpclientCode'];
$ddlADPClientCode = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();

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
$htmlButtonElement->onclick = 'return validateClientInfo();';
$htmlButtonElement->colspan = '2';
$btnSave = $htmlButtonElement->renderHtml();


$lblADPClientsCode	= $htmlTextElement->addLabel($ddlADPClientCode, 'Client:', '#ff0000',true);


$tableObj->searchFields['lblADPClientsCode'] = $lblADPClientsCode; 
$tableObj->searchFields['btnSave'] = $btnSave;
echo $tableObj->searchFormTableComponent();

echo $htmlForm->endForm();

?>
 
