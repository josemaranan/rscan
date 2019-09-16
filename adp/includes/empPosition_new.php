<?php
//include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class/rnetPositionListBox.inc.php");
//$listPosObj = new rnetPositionListBox();
//error_reporting(E_ALL);
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
$type = ''; 
if(isset($_REQUEST['type']))
{
	$type = $_REQUEST['type'];
}

$Filter .= " employeeID = '".$employeeID."' ";
//$Filter .= " and location in".$Me->Locations;

$query="SELECT 
			[employeeID],[firstName],[lastName],[location],[workAtHome],[shiftPreferenceID] 
		FROM 
			[ctlEmployees] WITH (NOLOCK) 
		WHERE 
			".$Filter; 
$rst = $employeeeMaintenanceObj->ExecuteQuery($query);
$num = mssql_num_rows($rst);
if($num==0)
{
	echo "<script type='text/javascript'>window.location='index.php';</script>";
}
$emp_array = mssql_fetch_array($rst);
$shiftPreID = $emp_array['shiftPreferenceID'];
$channelID = $emp_array['channelID'];

//Location
$sqlLocation ="	SELECT 
					description 
				FROM  
					ctlLocations WITH (NOLOCK)
				WHERE 
					location  = ".$emp_array[location]." ";
$rstLocation = $employeeeMaintenanceObj->ExecuteQuery($sqlLocation);
$empLocationDesc = mssql_result($rstLocation,0,0);
//END of Loaction

//Shift Pattern Type
$sqlShift="	SELECT 
				shiftPreference
			FROM 
				[ctlShiftPreferences] WITH (NOLOCK)
			WHERE 
				shiftPreferenceID = '".$shiftPreID. "' ";
$rstShift = $employeeeMaintenanceObj->ExecuteQuery($sqlShift);
$shftPattrnType = mssql_result($rstShift,0,0);
//END of Shift Pattern Type

$sqlPOS = "	SELECT 
				positionID 
			FROM 
				ctlEmployeePositions WITH (NOLOCK) 
			WHERE 
				employeeID = '$employeeID' 
			AND 
				isPrimary = 'Y' ";
$rstPOS = $employeeeMaintenanceObj->ExecuteQuery($sqlPOS);
while($row=mssql_fetch_array($rstPOS)) 
{	
	$positionID = $row[0];
	$primaryPositionID = $row[0];;
}

if($positionID==10)
{
	$display = 'block';
}
else
{
	$display = 'none';

}
$sqlLoc = "	SELECT 
				location,corporateAccess 
			FROM 
				ctlEmployees WITH (NOLOCK) 
			WHERE
				employeeID = " .$employeeID;
$rstLoc = $employeeeMaintenanceObj->ExecuteQuery($sqlLoc);
while($rowLoc=mssql_fetch_array($rstLoc)) 
{	
	$location = $rowLoc['location'];
	$corporateAccess  = $rowLoc['corporateAccess'];
}
if($location == '801' || $location == '800' || $location == '802' || $location == '803')
{
	$filterLocaton = 'Corporate';
	
}
else
{
	$filterLocaton = 'Field Staff';
	
}
$filterLocaton = 'Corporate';

if($corporateAccess == 'Y' && $location != '801' && $location != '800' && $location != '802' && $location != '803')
{
	$sqlJobCode="	SELECT positionID,position FROM  ctlPositions WITH (NOLOCK) ORDER BY position";
}
else
{
	$sqlJobCode="	SELECT positionID,position FROM ctlPositions WITH (NOLOCK) WHERE 
					businessFunction = '".$filterLocaton."' ORDER BY position";
}
$rstJobCode = $employeeeMaintenanceObj->ExecuteQuery($sqlJobCode);
//END of Job Code

unset($payrollLocationCur);
$payrollLocationCur = $employeeeMaintenanceObj->getEmployeeCurrentPayrollLocation($employeeID);
//Effective Date
$sqlEffDate="	SELECT 
					[payDate],
					[startDate]
				FROM 
					[ctlLocationPaydateSchedules] WITH (NOLOCK)
				WHERE 
					Location LIKE '$payrollLocationCur'
				AND
					isFinalized IS NULL";
$rstEffDate = $employeeeMaintenanceObj->ExecuteQuery($sqlEffDate);
$numrowsEffDate = mssql_num_rows($rstEffDate);
if($numrowsEffDate >= 1) {
	$result = $employeeeMaintenanceObj->bindingInToArray($rstEffDate);
	
	$i=0;
	foreach($result AS $id => $row){
		$effectiveDates[$i] = date("m/d/Y",strtotime($row[startDate]));
		$i++;
	}
}else{
	$effectiveDates[0] = date("m/d/Y");
}


$sqlAllPOS = "	
IF OBJECT_ID('tempdb.dbo.#tempEmloyeePositions') IS NOT NULL
DROP TABLE #tempEmloyeePositions
CREATE TABLE  #tempEmloyeePositions
(
     employeeID INT NULL,
     positionID INT NULL,
     effectiveDate DATETIME NULL,
     endDate DATETIME NULL,
     isPrimary char(1) NULL,
     isAdpJobCode CHAR(1) NULL
)

INSERT INTO #tempEmloyeePositions
SELECT employeeID , positionID , effectiveDate , endDate , isPrimary , 'Y'
FROM
	ctlEmployeePositions (nolock)
WHERE
	employeeID = '$employeeID'
AND 
	ISNULL(positionID, '') != ''

UPDATE 
	a
SET 
	isAdpJobCode = 'N'
FROM 
	#tempEmloyeePositions a WITH (NOLOCK)
JOIN

	ctlPositions b WITH (NOLOCK)
ON
	a.positionID = b.positionID 
JOIN
	ctlEmployees e WITH (NOLOCK)
ON
	a.employeeID = e.employeeID
JOIN
	ctlLocations l WITH (NOLOCK)
ON
	e.location = l.location
WHERE
	adpJobCode IS NULL
AND
	l.country = 'United States of America'

SELECT * FROM #tempEmloyeePositions (NOLOCK) ";
$rstAllPos = $employeeeMaintenanceObj->ExecuteQuery($sqlAllPOS);
$num = mssql_num_rows($rstAllPos);
	
//END of Position History	

if(isset($_REQUEST['returnPositionID']))
{
	$positionID = $_REQUEST['returnPositionID']; 
}

if(isset($_REQUEST['returneffectiveDate']))
{
	$startDate = $_REQUEST['returneffectiveDate']; 
}

echo $htmlTagObj->openTag('div', 'id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="businessRuleHeading" class="outer"');
echo 'Position Update';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo();

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="scrollingdatagrid" class="scrollingdatagrid"');

$selectJobsQuery = "SELECT [positionID], [description] FROM ctlPositions WITH (NOLOCK) WHERE isActive = 'Y' AND businessFunction='" . $filterLocaton . "' ORDER BY [description]";

$jobCodeRs = $employeeeMaintenanceObj->ExecuteQuery($selectJobsQuery);
$numrowsJobCodes = mssql_num_rows($jobCodeRs);
if($numrowsJobCodes >= 1) {
	$result = $employeeeMaintenanceObj->bindingInToArray($jobCodeRs);
	
	foreach($result AS $id => $row){
		$jobeCodeArray[$row['positionID']] = $row['description'];
	}
}


// Loading Locations
$commonListBox->name = 'ddlJobCode';
$commonListBox->id 	= 'ddlJobCode';
$commonListBox->customArray = $jobeCodeArray;
//$commonListBox->selectedItem = stripslashes($empoyeeAddressInfo[0]['state']);
$commonListBox->optionKey = 'ddlJobCode';
$commonListBox->optionVal = 'description';
$ddlJobCode = $commonListBox->AddRow('', 'Please choose');
$ddlOthState = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlJobCode', $positionID ,'results', 'ctlEmployeePositions', 'positionID' , 'ctlEmployeePositions#positionID');

$htmlButtonElement->id = 'btnClients';
$htmlButtonElement->name = 'btnClients';
$htmlButtonElement->value = 'Add Clients';
//$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->style = 'text-align: left;';
$htmlButtonElement->onclick = 'return AddClients("' . $employeeID . '", "AE");';
$htmlButtonElement->type = 'button';
$btnClients = $htmlButtonElement->renderHtml();
$htmlButtonElement->resetProperties();

$htmlButtonElement->id = 'btnClients';
$htmlButtonElement->name = 'btnClients';
$htmlButtonElement->value = 'Add Clients';
//$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->style = 'text-align: left;';
$htmlButtonElement->onclick = 'return AddClients("' . $employeeID . '", "RTM");';
$htmlButtonElement->type = 'button';
$btnClients = $htmlButtonElement->renderHtml();
$htmlButtonElement->resetProperties();

$htmlButtonElement->id = 'btnLocations';
$htmlButtonElement->name = 'btnLocations';
$htmlButtonElement->value = 'Add Locations';
//$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->style = 'text-align: left;';
$htmlButtonElement->onclick = 'return AddLocations("' . $employeeID . '");';
$htmlButtonElement->type = 'button';
$btnLocations = $htmlButtonElement->renderHtml();
$htmlButtonElement->resetProperties();

// Effective date
$commonListBox->name = 'effectiveDate';
$commonListBox->id 	= 'effectiveDate';
$commonListBox->sqlQry = $effectiveDates;
$commonListBox->selectedItem = $startDate;
$commonListBox->optionKey = 'startDate';
$commonListBox->optionVal = 'description';
$effectiveDate = $commonListBox->AddRow('', 'Please choose');
$effectiveDate = $commonListBox->display();
$commonListBox->resetProperties();

$htmlTextElement->name = 'isPrimary';
$htmlTextElement->id = 'isPrimary';
$htmlTextElement->value = 'Y';
$htmlTextElement->txt = '';
$htmlTextElement->type = 'checkbox'; //isDefaultChkd
//$htmlTextElement->isDefaultChkd = ($empoyeeEmergencyDetails[0]['isSpouseWorkInResults'] == 'Y') ? '1' : '';
$isPrimary = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

//button Search 
$htmlButtonElement->id = 'btnUpdate';
$htmlButtonElement->name = 'btnUpdate';
$htmlButtonElement->value = 'View';
$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->style = 'text-align: left;';
$htmlButtonElement->type = 'submit';
$htmlButtonElement->onclick = 'JavaScript:return checkValidations();';
$btnUpdate = $htmlButtonElement->renderHtml();
$htmlButtonElement->resetProperties();


$htmlForm->fieldSet = TRUE;
$formLegend = $htmlForm->addLegend('Position Update');

$ddlJobCode		= $htmlTextElement->addLabel($ddlJobCode, 'Job Code:', '#ff0000','');
$emptyTd		= $htmlTextElement->addLabel($emptyTd, '', '#ff0000','');
$effectiveDate	= $htmlTextElement->addLabel($effectiveDate, 'Effective Date:', '#ff0000','true');
$isPrimary		= $htmlTextElement->addLabel($isPrimary, 'Is Primary?', '#ff0000','true');

//Form block 1
$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->setTableClass('');
$tableObj->setTableAttr();

$tableObj->searchFields['ddlJobCode'] = $ddlJobCode;
$tableObj->searchFields['emptyTd'] = $emptyTd;
$tableObj->searchFields['effectiveDate'] = $effectiveDate;
$tableObj->searchFields['isPrimary'] = $isPrimary;
$tableObj->searchFields['button'] = $btnUpdate;

//Html Form starts here
$htmlForm->action = 'positionUpdate_Process.php?employeeID=' . $employeeID;
$htmlForm->name = 'form_data';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'POST';

echo $htmlTagObj->openTag('div', 'id="adpsearchFieldSet"');
echo $htmlForm->startForm();

echo 'entered';

echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

?>
