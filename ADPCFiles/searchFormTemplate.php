<?php

echo $this->htmlTagObj->openTag('form', 'id="adpcSearchForm" class="minusDiv" style="margin-bottom:0px;"');

include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/locationsClass.inc.php');
$objRNetLocations = new RNetLocations();
$getLocationElement = $objRNetLocations->showLocations('ddlLocations','ddlLocations','');
$locationDDL = $getLocationElement;

$hdnElement = $this->setTextElement('hdnTabID','hdnTabID',$this->selectedTab,'Y');


$LocationsDdl 		= $this->htmlTextElement->addLabel($locationDDL.$hdnElement, 'Location', '#ff0000', FALSE);
$firstName 			= $this->htmlTextElement->addLabel($this->setTextElement('txtFirstName','txtFirstName',''), 'First Name', '', FALSE);
$lastName 			= $this->htmlTextElement->addLabel($this->setTextElement('txtLastName','txtLastName',''), 'Last Name', '', FALSE);
$employeeID 		= $this->htmlTextElement->addLabel($this->setTextElement('txtEmployeeID','txtEmployeeID',''), 'Employee ID', '', FALSE);
$onClick 			= 'onclick="generateGrid();"';
$btnValue			= 'Search';
$buttonLbl	 		= $this->htmlTextElement->addLabel($this->setButtonElement($onClick,$btnValue), '', '', FALSE);


$this->htmlForm->fieldSet = TRUE;
$formLegend 		= $this->htmlForm->addLegend('Employee Search');

//search form in table format
$this->tableObj->tableId 	= 'searchTable';
$this->tableObj->tableClass = 'searchtab';
$this->tableObj->maxCol 	= 5;
$this->tableObj->searchFields['location'] 	= $LocationsDdl;
$this->tableObj->searchFields['empId'] 		= $employeeID;
$this->tableObj->searchFields['firstname'] 	= $firstName;
$this->tableObj->searchFields['lastname'] 	= $lastName;
$this->tableObj->searchFields['buttonLbl'] 	= $buttonLbl;

$empSearchForm 	= $this->tableObj->searchFormTableComponent();

//echo $this->htmlTagObj->openTag('div', 'id="searchBody" class="outer"');
//echo $searchForm;
echo $formLegend;
echo $empSearchForm;
echo $this->htmlTagObj->closeTag('fieldset');
echo $this->htmlTagObj->closeTag('form');
?>