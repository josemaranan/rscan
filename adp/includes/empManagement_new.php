<?php
$locArray = $employeeeMaintenanceObj->getUsLocations();
$empStausArray = $employeeeMaintenanceObj->getEmploymentStatuses();
$payGroupLocationArray = $employeeeMaintenanceObj->getUSPayGroupLocations();
$payGroupLocationArrayKeyValue = $employeeeMaintenanceObj->convertArrayKeyValuePair($payGroupLocationArray, 'location','paygroup');
$employeeeMaintenanceObj->setUSADPPayGroupLocations();
$locADPArray = $employeeeMaintenanceObj->getUSADPPayGroupLocations();


if(isset($_REQUEST['search']) == 'Generate Report')
{
	if (isset($_REQUEST['ddlLocations']))
	{
		$location = addslashes($_REQUEST['ddlLocations']);
		unset($_SESSION['employeePayrolLoc']);
		$_SESSION['employeePayrolLoc'] = $location;
	} 
	else
	{ 
		$location = $_SESSION['employeePayrolLoc'];
	}
	
	if (isset($_REQUEST['txtFirstName']))
	{
		$firstName = addslashes($_REQUEST['txtFirstName']);
		unset($_SESSION['employeePayrolFstName']);
		$_SESSION['employeePayrolFstName'] = $firstName;
	} 
	else
	{ 
		$firstName = $_SESSION['employeePayrolFstName'];
	}
	
	if (isset($_REQUEST['txtLastName']))
	{
		$lastName = addslashes($_REQUEST['txtLastName']);
		unset($_SESSION['employeePayrolLstName']);
		$_SESSION['employeePayrolLstName'] = $lastName;
	} 
	else
	{ 
		$lastName = $_SESSION['employeePayrolLstName'];
	}
	
	if (isset($_REQUEST['txtEmployeeID']))
	{
		$employeeID = addslashes($_REQUEST['txtEmployeeID']);
		unset($_SESSION['employeePayrolID']);
		$_SESSION['employeePayrolID'] = $employeeID;
	} 
	else
	{ 
		$employeeID = $_SESSION['employeePayrolID'];
	}
	
	if (isset($_REQUEST['ddlStatus']))
	{
		$employmentStatus = addslashes($_REQUEST['ddlStatus']);
		unset($_SESSION['employeePayrolStatus']);
		$_SESSION['employeePayrolStatus'] = $employmentStatus;
	} 
	else
	{ 
		$employmentStatus = $_SESSION['employeePayrolStatus'];
	}
		
	if (isset($_REQUEST['txtAvayaID']))
	{
		$avayaID = addslashes($_REQUEST['txtAvayaID']);
		unset($_SESSION['employeePayrolAvayaID']);
		$_SESSION['employeePayrolAvayaID'] = $avayaID;
	} 
	else
	{ 
		$avayaID = $_SESSION['employeePayrolAvayaID'];
	}

	
	//Main Qry
	foreach($locADPArray as $locArrayK=>$locArrayV)
	{
		$usaLocs .= $locArrayV['location'].',';
	}
	//echo substr($usaLocs,0,-1);exit;
	
	$accessedLocations = substr($usaLocs,0,-1);
	//$sqlMainQry = "EXEC  RNet.dbo.[report_spSearchPayrollEmployees]   '$location','$firstName','$lastName','$employeeID','','$avayaID','','$employmentStatus', '$accessedLocations', '$RestrctEmpId' ";
	/* $sqlMainQry = " EXEC  RNet.dbo.[report_spSearchManageEmployees]   '$location','$firstName','$lastName','$employeeID','','','$avayaID','','$employmentStatus', '$accessedLocations' ";*/
	if($location=='800')
	{
		$spLocation = '800,801,803'	;
	}
	else
	{
		$spLocation = $location;
	}
	$sqlMainQry = " EXEC  RNet.dbo.[report_spSearchManageEmployeesADPPayrollUSHR] '$spLocation','$firstName','$lastName','$employeeID','','','$avayaID','','$employmentStatus', '$accessedLocations' ";
		
	
	//echo $sqlMainQry;
	$rstMainQry = $employeeeMaintenanceObj->ExecuteQuery($sqlMainQry);
	$rowsMainQryNum = mssql_num_rows($rstMainQry);
	if($rowsMainQryNum>=1)
	{
		$mainArray = $employeeeMaintenanceObj->bindingInToArray($rstMainQry);
	}
	else
	{
		$calendarControls = false;	
	}
	//END of Main Qry
	mssql_free_result($rstMainQry);
}

echo $htmlTagObj->openTag('div', 'id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="businessRuleHeading" class="outer"');
echo 'Business Rules';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="businessRuleContent" class="outer"');
echo $htmlTagObj->openTag('p','');
echo 'Human Resources users will use this dashboard to manage employee data.  Start by searching for and selecting an employee below.  Then use the launch buttons to manage employee data.  Click the Human Resource Access link at left to start a new employee search. <br /><br />
Use the Employee Self Service link to edit employee data that is also editable by employees.';
echo $htmlTagObj->closeTag('p');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="formSearchFields" class="outer"');

// Loading Pay Group list box
$commonListBox->name = 'ddlLocations';
$commonListBox->id 	= 'ddlLocations';
$commonListBox->customArray = $payGroupLocationArrayKeyValue;
$commonListBox->selectedItem = $location;
$commonListBox->optionKey = 'location';
$commonListBox->optionVal = 'description';
$locationDDL = $commonListBox->AddRow('', 'Please choose');
$locationDDL = $commonListBox->display();
$commonListBox->convertArrayToDropDown();

// Loading First name
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtFirstName';
$htmlTextElement->id = 'txtFirstName';
$htmlTextElement->value = $firstName;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

// Loading Last name
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtLastName';
$htmlTextElement->id = 'txtLastName';
$htmlTextElement->value = $lastName;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


// Loading Employee Id
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtEmployeeID';
$htmlTextElement->id = 'txtEmployeeID';
$htmlTextElement->value = $employeeID;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

// Loading Pay Group list box
$commonListBox->name = 'ddlStatus';
$commonListBox->id 	= 'ddlStatus';
$commonListBox->customArray = $empStausArray;
$commonListBox->selectedItem = $employmentStatus;
$commonListBox->optionKey = 'location';
$commonListBox->optionVal = 'description';
$locationDDL = $commonListBox->AddRow('', 'Please choose');
$locationDDL = $commonListBox->display();
$commonListBox->convertArrayToDropDown();

// Loading Employee Id
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtAvayaID';
$htmlTextElement->id = 'txtAvayaID';
$htmlTextElement->value = $avayaID;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$locationDDL	= $htmlTextElement->addLabel($locationDDL, 'Pay Group', '#ff0000','');
$firstName		= $htmlTextElement->addLabel($firstName, 'First Name', '#ff0000','');
$lastName		= $htmlTextElement->addLabel($lastName, 'Last Name', '#ff0000','');
$empId			= $htmlTextElement->addLabel($empId, 'Employee ID', '#ff0000','');
$ddlStatus		= $htmlTextElement->addLabel($ddlStatus, 'Employment Status', '#ff0000','');
$txtAvayaID		= $htmlTextElement->addLabel($txtAvayaID, 'Avaya ID', '#ff0000','');

$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->setTableAttr();

$tableObj->searchFields['location'] = $locationDDL;
$tableObj->searchFields['firstName'] = $firstName;
$tableObj->searchFields['lastName'] = $lastName;
$tableObj->searchFields['empId'] = $empId;
$tableObj->searchFields['ddlStatus'] = $ddlStatus;
$tableObj->searchFields['txtAvayaID'] = $txtAvayaID;
$tableObj->searchFields['button'] = $btnSearch;

//$tableObj->searchFields['selectReportType'] = $selectReportType;

//$tableObj->searchFields['radioLC'] = $radioLC;
//$tableObj->searchFields['radioL'] = $radioL;
//$tableObj->searchFields['radioC'] = $radioC;

$searchForm = $tableObj->searchFormTableComponent();

//Html Form starts here
$htmlForm->action = $_SERVER['PHP_SELF'];
$htmlForm->name = 'form_data';
$htmlForm->id = 'searchForm';
$htmlForm->action = 'POST';
echo $htmlForm->startForm();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'adpMode';
$htmlTextElement->id = 'adpMode';
$htmlTextElement->value = $adpMode;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'adpTask';
$htmlTextElement->id = 'adpTask';
$htmlTextElement->value = $adpTask;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'lastRecord';
$htmlTextElement->id = 'lastRecord';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

echo $searchForm;	
echo $htmlForm->endForm();
//Html Form ends here
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="scrollingdatagrid" class="scrollingdatagrid"');
//Table div for grid
$tableObj->headers[] = 'Actions';
$tableObj->headers[] = 'Employee ID';
$tableObj->headers[] = 'FirstName';
$tableObj->headers[] = 'LastName';
$tableObj->headers[] = 'Location';
$tableObj->headers[] = 'AvayaID';
$tableObj->headers[] = 'Status';

$tableObj->tableId = 'listTable';
$tableObj->width = '100%';
$tableObj->border = 0;
$tableObj->align = 'left';
$tableObj->cellPadding = '0';
$tableObj->bgColor = '#FFFFFF';
$tableObj->cellSpacing = '0';
$tableObj->tableClass = 'report table-autosort table-stripeclass:alternate';
$tableObj->zebra = 1;
$tableObj->fixedCol = 5;
$tableObj->setTableAttr("0", "3", "#FFFFFF", "3", "left", "100%");

foreach ($mainArray as $mainArrayK => $mainArrayV) {
	$data[$mainArrayK]['employID'] = $mainArrayV[employeeID];
	$data[$mainArrayK]['avayaIDs'] = $mainArrayV[avayaIDs];
	$data[$mainArrayK]['firstName'] = $mainArrayV[firstName];
	$data[$mainArrayK]['lastName'] = $mainArrayV[lastName];
	$data[$mainArrayK]['locationDescription'] = $mainArrayV[locationDescription];
	$data[$mainArrayK]['location'] = $mainArrayV[location];
	$data[$mainArrayK]['AuxCodeDescription'] = ($employmentStatus == 2) ? 'TERMINATED' : $mainArrayV[employmentStatusDescriptionEnd];
}

echo $tableReport = $tableObj->showTable($data, count($data));

echo $htmlTagObj->closeTag('div');

?>
