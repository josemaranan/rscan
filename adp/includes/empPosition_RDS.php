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
$rst = $employeeeMaintenanceObj->execute($query);
$num = $employeeeMaintenanceObj->getNumRows($rst);
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
$rstLocation = $employeeeMaintenanceObj->execute($sqlLocation);
$empLocationDesc = mssql_result($rstLocation,0,0);
//END of Loaction

//Shift Pattern Type
$sqlShift="	SELECT 
				shiftPreference
			FROM 
				[ctlShiftPreferences] WITH (NOLOCK)
			WHERE 
				shiftPreferenceID = '".$shiftPreID. "' ";
$rstShift = $employeeeMaintenanceObj->execute($sqlShift);
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
$rstPOS = $employeeeMaintenanceObj->execute($sqlPOS);
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
$rstLoc = $employeeeMaintenanceObj->execute($sqlLoc);
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
$rstJobCode = $employeeeMaintenanceObj->execute($sqlJobCode);
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
$rstEffDate = $employeeeMaintenanceObj->execute($sqlEffDate);
$numrowsEffDate = $employeeeMaintenanceObj->getNumRows($rstEffDate);

if($numrowsEffDate >= 1) 
{
	$result = $employeeeMaintenanceObj->bindingInToArray($rstEffDate);
	
	$i=0;
	foreach($result AS $id => $row)
	{
		$effectiveDates[$i] = date("m/d/Y",strtotime($row['startDate']));
		$i++;
	}
}
else
{
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
$rstAllPos = $employeeeMaintenanceObj->execute($sqlAllPOS);
$num = $employeeeMaintenanceObj->getNumRows($rstAllPos);
	
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

//echo $htmlTagObj->openTag('div', 'id="scrollingdatagrid" class="scrollingdatagrid"');

$selectJobsQuery = "SELECT [positionID], [description] FROM ctlPositions WITH (NOLOCK) WHERE businessFunction='" . $filterLocaton . "' ORDER BY [description]";

$jobCodeRs = $employeeeMaintenanceObj->execute($selectJobsQuery);
$numrowsJobCodes = $employeeeMaintenanceObj->getNumRows($jobCodeRs);
if($numrowsJobCodes >= 1) 
{
	$result = $employeeeMaintenanceObj->bindingInToArray($jobCodeRs);
	
	foreach($result AS $id => $row)
	{
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
$commonListBox->onChange = 'populateClients(this.value)';
$ddlJobCode = $commonListBox->AddRow('', 'Please choose');
$ddlJobCode = $commonListBox->convertArrayToDropDown();
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
$commonListBox->customArray = $effectiveDates;
$commonListBox->selectedItem = $startDate;
$commonListBox->optionKey = 'startDate';
$commonListBox->optionVal = 'description';
$effectiveDate = $commonListBox->AddRow('', 'Please choose');
$effectiveDate = $commonListBox->convertArrayToDropDown();
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
$htmlButtonElement->value = 'Submit';
$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->style = 'text-align: left;';
$htmlButtonElement->type = 'submit';
$htmlButtonElement->onclick = 'JavaScript:return checkValidations();';
$btnUpdate = $htmlButtonElement->renderHtml();
$htmlButtonElement->resetProperties();

/*

<div id="AE" style="display:none;" >
            <input type="button" name="btnClients" id="btnClients" value="Add Clients" 
            onclick="return AddClients('<?php echo $employeeID;?>','AE');" />
            </div>
            
            <div id="RTM" style="display:none;" >
            <input type="button" name="btnClients" id="btnClients" value="Add Clients" 
            onclick="return AddClients('<?php echo $employeeID;?>','RTM');" />
            <input type="button" name="btnLocations" id="btnLocations" value="Add Locations" 
            onclick="return AddLocations('<?php echo $employeeID;?>');" />
            </div>

*/
$emptyTd = '<div id="AE" style="display:none;" >';
$emptyTd .= '<input type="button" name="btnClients" id="btnClients" value="Add Clients" onclick="return AddClients(\'' . $employeeID . '\',\'AE\');" />';
$emptyTd .= '</div> <div id="RTM" style="display:none;" >';
$emptyTd .= '<input type="button" name="btnClients" id="btnClients" value="Add Clients" onclick="return AddClients(\'' . $employeeID . '\',\'RTM\');" />
             <input type="button" name="btnLocations" id="btnLocations" value="Add Locations" onclick="return AddLocations(\'' . $employeeID . '\');" /></div>';
			 
$emptyTd1 = '<div id="BackMsg" style="display:none; color:#F00; font-size:12px;" >
            Associate (Back Office) is a complementary position to the Results Associate position, which provides additional access rights. In order to be granted the Associate (Back Office) position, an employee must also have the Results Associate position in their profile. Associate (Back Office) cannot be the employee\'s primary position
        	</div>';
			
$emptyTd2 = '<div id="CLIENTDETAILS" class="clientClass">
        </div>
        
        <div id="LOCATIONDETAILS" >
        </div>';
		
$emptyTd3 = '<div id="loadHiddenValues" style="margin:0px; padding:0px;">
        </div>';

$htmlForm->fieldSet = TRUE;
$formLegend = $htmlForm->addLegend('Position Update');

$ddlJobCode		= $htmlTextElement->addLabel($ddlJobCode, 'Job Code:', '#ff0000','');
$emptyTd		= $htmlTextElement->addLabel($emptyTd, '', '#ff0000','');
$emptyTd1		= $htmlTextElement->addLabel($emptyTd1, '', '#ff0000','');
$emptyTd2		= $htmlTextElement->addLabel($emptyTd2, '', '#ff0000','');
$emptyTd3		= $htmlTextElement->addLabel($emptyTd3, '', '#ff0000','');
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
$tableObj->searchFields['emptyTd1'] = $emptyTd1;
$tableObj->searchFields['emptyTd2'] = $emptyTd2;
$tableObj->searchFields['effectiveDate'] = $effectiveDate;
$tableObj->searchFields['newTr'] = '\n';
$tableObj->searchFields['isPrimary'] = $isPrimary;
$tableObj->searchFields['button'] = $btnUpdate;
$tableObj->searchFields['emptyTd3'] = $emptyTd3;
$searchForm = $tableObj->searchFormTableComponent();

//Html Form starts here
$htmlForm->action = 'positionUpdate_Process_RDS.php?employeeID=' . $employeeID;
$htmlForm->name = 'form_data';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'POST';

echo $htmlTagObj->openTag('div', 'id="adpsearchFieldSet"');
echo $formLegend;
echo $htmlForm->startForm();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'employeeID';
$htmlTextElement->id = 'employeeID';
$htmlTextElement->value = $emp_array[employeeID];
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnEmployeeID';
$htmlTextElement->id = 'hdnEmployeeID';
$htmlTextElement->value = $emp_array[employeeID];
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnType';
$htmlTextElement->id = 'hdnType';
$htmlTextElement->value = $type;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnEmployeeLocation';
$htmlTextElement->id = 'hdnEmployeeLocation';
$htmlTextElement->value = $location;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnValidate';
$htmlTextElement->id = 'hdnValidate';
$htmlTextElement->value = '1';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

echo $searchForm;
echo $htmlTagObj->closeTag('fieldset');
echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('div');


echo $htmlTagObj->openTag('div', 'style="margin:0px; padding:0px;"');
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/ReportTable.inc.php');

$Table=new ReportTable();
$Table->Width="90%";
$Table->Align="right";
$Table->Spacing="2";
$Table->Border='2';

$Col=& $Table->AddColumn("Column1");

$Row=& $Table->AddHeader();
$Row->Cells["Column1"]->Value="Position History";
$Row->Cells["Column1"]->sorting = FALSE;
$Row->Cells["Column1"]->ColumnSpan=6;

$Col=& $Table->AddColumn("Column2");
$Col=& $Table->AddColumn("Column3");
$Col=& $Table->AddColumn("Column4");
$Col=& $Table->AddColumn("Column5");
$Col=& $Table->AddColumn("Column6");

$Row=& $Table->AddHeader();
$Row->Cells["Column1"]->Value="Job Code";
$Row->Cells["Column1"]->HorizontalAlignment = 'center';
$Row->Cells["Column2"]->Value="Is Primary?";
$Row->Cells["Column2"]->HorizontalAlignment = 'center';
$Row->Cells["Column3"]->Value="Start Date";
$Row->Cells["Column3"]->HorizontalAlignment = 'center';
$Row->Cells["Column4"]->Value="Clients/Locations";
$Row->Cells["Column4"]->HorizontalAlignment = 'center';
$Row->Cells["Column5"]->Value="End Date";
$Row->Cells["Column5"]->HorizontalAlignment = 'center';
$Row->Cells["Column6"]->Value="";
$Row->Cells["Column6"]->sorting = FALSE;
$Row->Cells["Column6"]->HorizontalAlignment = 'center';

unset($row);
$divcount = 1;
$i=0;
unset($isAdpJobCode);
while ($row=mssql_fetch_array($rstAllPos)) 
{
	$isAdpJobCode = $row['isAdpJobCode'];
	$positionID = $row['positionID'];
	
	$SQLPOSDES =  "SELECT position FROM ctlPositions WITH (NOLOCK) WHERE positionID = $positionID";
	$rst1 = $employeeeMaintenanceObj->execute($SQLPOSDES);
	
	if($row1=mssql_fetch_array($rst1)) 
	{	
		$positionDec=$row1['position'];
	}
	$effectiveDate = date("m/d/Y");
	if(!empty($row[effectiveDate]))
	{
		$effectiveDate=date('m/d/Y',strtotime(stripslashes($row['effectiveDate'])));		
	}
	if(!empty($row[endDate]))
	{
		$endDate=date('m/d/Y',strtotime($row[endDate]));
	}
	if(!empty($row[isPrimary]))
	{
		$isPrimary1= $row[isPrimary];
	}
	else
	{
		$isPrimary1= 'N';
	}
	
	
	$SQL_SD = "	SELECT 
					[payDate],[startDate]
				FROM 
					[ctlLocationPaydateSchedules] WITH (NOLOCK)
				WHERE 
					Location LIKE '$payrollLocationCur'
				AND
					isFinalized IS NULL ";
					
	//$rst_SD=mssql_query($SQL_SD, $db);
	$rst_SD = $employeeeMaintenanceObj->execute($SQL_SD);
	
	$num_rowsSD = $employeeeMaintenanceObj->getNumRows($rst_SD);

	//Html Form starts here
	$htmlForm->action = 'positionUpdate_Process1_RDS.php';
	$htmlForm->name = 'form_' . $positionID . '_' . $effectiveDate;
	$htmlForm->id = 'form_' . $positionID . '_' . $effectiveDate;
	$htmlForm->method = 'POST';
	
	
	echo $htmlTagObj->openTag('div','class="scrollingdatagrid" id="scrollingdatagrid" visible="true"'); // bhanu
	
	
	echo $htmlForm->startForm();
	$Row=& $Table->AddRow();
	
	$Row->Cells["Column1"]->Value = $positionDec;
	
	$td2Value = '';
	if($positionID!='155') 
	{ 
		$htmlTextElement->name = 'isPrimary_' . $positionID . '_' . $effectiveDate;
		$htmlTextElement->id = 'isPrimary_' . $positionID . '_' . $effectiveDate;
		$htmlTextElement->value = 'Y';
		$htmlTextElement->txt = '';
		$htmlTextElement->type = 'checkbox'; //isDefaultChkd
		$htmlTextElement->isDefaultChkd = ($isPrimary1 == 'Y') ? '1' : '';
		$htmlTextElement->onClick = ($isPrimary1=='Y') ? 'return false' : 'return checkADPJobCode("isPrimary_' . $positionID . '_' . $effectiveDate . '", "' . $isAdpJobCode . '"); return false;';
		$htmlTextElement->onkeydown = ($isPrimary1=='Y') ? 'return false' : '';
		$td2Value = $htmlTextElement->renderHtml();
		$htmlTextElement->resetProperties();
		
		$htmlTextElement->type = 'hidden';
		$htmlTextElement->name = 'hdn_' . $positionID . '_' . $effectiveDate;
		$htmlTextElement->id = 'hdn_' . $positionID . '_' . $effectiveDate;
		$htmlTextElement->value = $positionID;
		$td2Value .= $htmlTextElement->renderHtml();
		$htmlTextElement->resetProperties();
		
		$td2Value .= $htmlTagObj->openTag('div', 'id="dis_' . $i .'"'); 
		$td2Value .= $htmlTagObj->closeTag('div');
		
		$employeeeMaintenanceObj->gethiddenValues('hdn_'.$positionID.'_'.$effectiveDate.'', $primaryPositionID ,'results', 'ctlEmployeePositions', 'positionID' , 'ctlEmployeePositions#positionID');

		?>
        <script type="text/javascript">
		htmlData5('getHiddenValues.php','posID=<?php echo $positionID;?>&hdnEmployeID=<?php echo $employeeID;?>','dis_<?php echo $i;?>');
		</script>
        <?php
	} 
	else 
	{
		
		$htmlTextElement->name = 'isPrimary_' . $positionID . '_' . $effectiveDate;
		$htmlTextElement->id = 'isPrimary_' . $positionID . '_' . $effectiveDate;
		$htmlTextElement->value = 'Y';
		$htmlTextElement->disabled = 'disabled';
		$htmlTextElement->type = 'checkbox'; //isDefaultChkd
		$td2Value .= $htmlTextElement->renderHtml();
		$htmlTextElement->resetProperties();
		
		$htmlTextElement->type = 'hidden';
		$htmlTextElement->name = 'hdn_' . $positionID . '_' . $effectiveDate;
		$htmlTextElement->id = 'hdn_' . $positionID . '_' . $effectiveDate;
		$htmlTextElement->value = $positionID;
		$td2Value .= $htmlTextElement->renderHtml();
		$htmlTextElement->resetProperties();
		
		$td2Value .= $htmlTagObj->openTag('div', 'id="dis_' . $i .'"'); 
		$td2Value .= $htmlTagObj->closeTag('div');
		
		$employeeeMaintenanceObj->gethiddenValues('hdn_'.$positionID.'_'.$effectiveDate.'', $primaryPositionID ,'results', 'ctlEmployeePositions', 'positionID' , 'ctlEmployeePositions#positionID');
		
		?>
        <script type="text/javascript">
		htmlData5('getHiddenValues.php','posID=<?php echo $positionID;?>&hdnEmployeID=<?php echo $employeeID;?>','dis_<?php echo $i;?>');
		</script>
		<?php
	}
	$Row->Cells["Column2"]->Value = $td2Value;
	
	// Loading City
	$htmlTextElement->type = 'text';
	$htmlTextElement->name = 'startDate_' . $positionID . '_' . $effectiveDate;
	$htmlTextElement->id = 'startDate_' . $positionID . '_' . $effectiveDate;
	$htmlTextElement->value =  date('m/d/Y',strtotime($effectiveDate));
	$htmlTextElement->readonly = 'readonly';
	$td3Value = $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'hdnVar_' . $positionID . '_' . $effectiveDate;
	$htmlTextElement->id = 'hdnVar_' . $positionID . '_' . $effectiveDate;
	$htmlTextElement->value = '1';
	$td3Value .= $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'hdnEffDate_' . $positionID . '_' . $effectiveDate;
	$htmlTextElement->id = 'hdnEffDate_' . $positionID . '_' . $effectiveDate;
	$htmlTextElement->value = date('m/d/Y',strtotime($effectiveDate));
	$td3Value .= $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$td3Value .= $htmlTagObj->anchorTag('#', 'edit', 'onclick="return hideshowDiv(\'' . $positionID . '_' . $effectiveDate . '\');"'); 
	
	$td3Value .= $htmlTagObj->openTag('div', 'id="showhide_' . $positionID . '_' . $effectiveDate .'" style="display:none"'); 
	
	$SDArray = array();
	if($num_rowsSD >= 1) 
	{
		$result = $employeeeMaintenanceObj->bindingInToArray($rst_SD);

		foreach($result as $id => $row)
		{
			$SDdate = date("m/d/Y",strtotime($row[startDate]));
			$SDArray[$SDdate] = $SDdate;
		}
	} 
	else 
	{
		$SDdate = date("m/d/Y");
		$SDArray[$SDdate] = $SDdate;
	}
	
	// Loading Locations
	$commonListBox->name = 'ddlStartDate_' . $positionID . '_' . $effectiveDate;
	$commonListBox->id 	= 'ddlStartDate_' . $positionID . '_' . $effectiveDate;
	$commonListBox->customArray = $SDArray;
	$commonListBox->selectedItem = date('m/d/Y',strtotime($effectiveDate));
	$commonListBox->optionKey = 'location';
	$commonListBox->optionVal = 'description';
	$commonListBox->onChange = "populateTextbox('" . $positionID . "_" . $effectiveDate ."');";
	$td3Value .= $commonListBox->AddRow('', 'Please choose');
	$td3Value .= $commonListBox->convertArrayToDropDown();
	//$td3Value .= $commonListBox->display();
	$commonListBox->resetProperties();
	$td3Value .= $htmlTagObj->closeTag('div');
	
	$Row->Cells["Column3"]->Value = $td3Value;
	
	$td4Value ='';
	if(empty($endDate)) 
	{ 
		if($positionID=='30') 
		{
			//button Search 
			$htmlButtonElement->id = 'btnClients';
			$htmlButtonElement->name = 'btnClients';
			$htmlButtonElement->value = 'Add/Edit Clients';
			$htmlButtonElement->onclick = 'return posHisEditClients("' . $employeeID . '", "' . $divcount . '", "' . $positionID . '", "AE"); return false;';
			$htmlButtonElement->style = 'text-align: left;';
			$htmlButtonElement->type = 'button';
			$td4Value .= $htmlButtonElement->renderHtml();
			$htmlButtonElement->resetProperties();
		}
		
		if($positionID=='132' || $positionID=='47') 
		{
			$htmlButtonElement->id = 'btnClients';
			$htmlButtonElement->name = 'btnClients';
			$htmlButtonElement->value = 'Add/Edit Clients';
			$htmlButtonElement->onclick = 'return posHisEditClients("' . $employeeID . '", "' . $divcount . '", "' . $positionID . '", "RTM"); return false;';
			$htmlButtonElement->style = 'text-align: left;';
			$htmlButtonElement->type = 'button';
			$td4Value .= $htmlButtonElement->renderHtml();
			$htmlButtonElement->resetProperties();
			
			$htmlButtonElement->id = 'btnLocations';
			$htmlButtonElement->name = 'btnLocations';
			$htmlButtonElement->value = 'Add/Edit Locations';
			$htmlButtonElement->onclick = 'return posHisEditLocs("' . $employeeID . '", "' . $divcount . '", "' . $positionID . '"); return false;';
			$htmlButtonElement->style = 'text-align: left;';
			$htmlButtonElement->type = 'button';
			$td4Value .= $htmlButtonElement->renderHtml();
			$htmlButtonElement->resetProperties();
		}
	}
	$Row->Cells["Column4"]->Value = $td4Value;
	
	$htmlTextElement->type = 'text';
	$htmlTextElement->name = 'endDate_' . $positionID . '_' . $effectiveDate;
	$htmlTextElement->id = 'endDate_' . $positionID . '_' . $effectiveDate;
	$htmlTextElement->value =  $endDate;
	$htmlTextElement->readonly = 'readonly';
	$htmlTextElement->accesskey = 'true';
	$td5Value = $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	?>
   
    <?php
	//$td5Value .= $htmlTagObj->imgTag('https://' . $_SERVER['HTTP_HOST'] . '/Include/images/calendar.gif', 'id="imgEndDate_' . $positionID . '_' . $effectiveDate . '" alt="Choose Start Date" onclick="javascript:displayCalendar(document.getElementById(\'endDate_' . $positionID . '_' . $effectiveDate . '\'),\'mm/dd/yyyy\',document.getElementById(\'imgEndDate_' . $positionID . '_' . $effectiveDate .'\'))" style="border-top-width: 0px; border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px"'); 
	
	$htmlCustomButtonElement = new HtmlCustomButtonElement('submit');
	
	$endDateColName = "endDate_" . $positionID . "_" . addslashes($effectiveDate);
	$htmlCustomButtonElement->id = '';
	$htmlCustomButtonElement->name = '';
	$htmlCustomButtonElement->value = 'Clear';
	$htmlCustomButtonElement->onclick = 'javascript: document.getElementById(\'' . $endDateColName . '\').value = \'\'';
	$htmlCustomButtonElement->type = 'button';
	$td5Value .= $htmlCustomButtonElement->renderHtml();
	$htmlCustomButtonElement->resetProperties();
	
	$Row->Cells["Column5"]->Value = $td5Value;
	
	$td6Value = '';
	$htmlCustomButtonElement->id = 'btn_' . $positionID . '_' . $effectiveDate;
	$htmlCustomButtonElement->name = 'btnSubmit';
	$htmlCustomButtonElement->value = 'Save';
	$htmlCustomButtonElement->onclick = 'JavaScript:return checkValidations2(\'' . $positionID . '_' . $effectiveDate . '\')';
	$htmlCustomButtonElement->style = 'text-align: left;';
	$htmlCustomButtonElement->type = 'submit';
	$td6Value .= $htmlCustomButtonElement->renderHtml();
	$htmlCustomButtonElement->resetProperties();
	
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'hdnEmployeeID';
	$htmlTextElement->id = 'hdnEmployeeID';
	$htmlTextElement->value = $employeeID;
	$td6Value .= $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'hdnPositionID';
	$htmlTextElement->id = 'hdnPositionID';
	$htmlTextElement->value = $positionID;
	$td6Value .= $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$htmlTextElement->type = 'hidden';
	$htmlTextElement->name = 'hdnEffectiveDate';
	$htmlTextElement->id = 'hdnEffectiveDate';
	$htmlTextElement->value = $effectiveDate;
	$td6Value .= $htmlTextElement->renderHtml();
	$htmlTextElement->resetProperties();
	
	$Row->Cells["Column6"]->Value = $td6Value;
	
	
	$Row=& $Table->AddRow();
	$tdValue = '';
	
	$tdValue .= $htmlTagObj->openTag('div', 'id="CLIENTDETAILSEDIT' . $divcount . '" class="clientClass" style="text-align:left;"'); 
//	$tdValue .= 'test';
	$tdValue .= $htmlTagObj->closeTag('div'); 
	$tdValue .= $htmlTagObj->openTag('div', 'id="LOCATIONDETAILSEDIT' . $divcount . '" style="text-align:left;"'); 
	$tdValue .= $htmlTagObj->closeTag('div'); 
	
	$Row->Cells["Column1"]->Value = $tdValue;
	//$Row->Cells["Column1"]->ColumnSpan=6;

	echo $htmlForm->endForm();
	echo $htmlTagObj->closeTag('div');

	$i++;
	$endDate='';
	$effectiveDate='';
	$divcount++;
}
//echo $htmlTagObj->openTag('div','class="scrollingdatagrid" id="scrollingdatagrid" visible="true"');
echo $Table->Display();


echo $htmlTagObj->closeTag('div');
//echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="dialogMain" class="window"');
echo $htmlTagObj->openTag('p', 'style="float:left; margin-left:10px; color:#F00; text-align:left; font-weight:bold;"');
echo 'All agents are required to have a supervisor asigned. <br />
    Please select a supervisor and effective date below.';
echo $htmlTagObj->anchorTag('#', 'Close <img src="../../../Include/images/round_red_close_button.jpg"  border="0" width="20px" height="20px" />', 'class="close"  style="float:right; border:0px;"');
echo $htmlTagObj->closeTag('p');

echo $htmlTagObj->openTag('div', 'id="emptyDiv"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="replace""');
include_once('supervisorUpdatemodalWindow.php');
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="mask""');
echo $htmlTagObj->closeTag('div');

//echo $htmlTagObj->closeTag('div');

if($_REQUEST['type']=='PositionExist')
{ 
	print '<script type="text/javascript">alert("Same position already exists.  You will need to add an end date to the pre-existing position");</script>';
}

if($_REQUEST['type']=='sDateExist')
{ 
	print '<script type="text/javascript">alert("Position already exists for this Start Date");</script>';
}
//*/
?>
	
		    

<script type="text/javascript">

$(document).ready(function() {	
						   
	var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"]; ?>';
	var this_id = '';
	var contains;
	
	$('input[type=text]').each(function () 
	{
		this_id = $(this).attr('id');
		contains = this_id.indexOf('endDate_');
		//alert(contains);
		if(contains == 0)
		{
			this_id = this_id.split('/');
			this_id = this_id[0] + '\\/' + this_id[1] + '\\/' + this_id[2];
			
			$("#"+this_id).datepicker({
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
		}
	});
	
   
   //select all the a tag with name equal to modal
	//if close button is clicked
	$('.window .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		$('#mask').hide();
		$('.window').hide();
		$('#replace').html("&nbsp;"); /* THIS WAS ADDED BCZ. TO INITIALIZE THE DOM AGAIN */ 
	});		
		
	//if mask is clicked
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});	
	
	//wrote by Juan
	$('#WIDE_LOGO #menu a[name=ToggleLegend]').click(function(e) {
		//Cancel the link behavior
		e.preventDefault();			
		$('#legend').toggle("normal");
	});	
	
});

function checkValidations()
{
	
	var selectedLoc = document.getElementById('hdnEmployeeLocation').value;
	var selPosition = document.getElementById('ddlJobCode').value;
	var alertmsg='';
	var validatePriority='';
	
	//var mainFlag = true;
	if(document.form_data.ddlJobCode.selectedIndex== "")
	{
		alert("Please Select Job Code");
		document.form_data.ddlJobCode.focus();
		return false;
		mainFlag = false;
		
	} 
	else if(document.form_data.effectiveDate.value == "")
	{ 
		alert("Please Choose Effective Date");			
		document.form_data.effectiveDate.focus();
		return false;
		mainFlag = false;
	}
	/*else if(document.getElementById('isPrimary').checked==true)
	{
		htmlIsPrimaryCheck('checkADPJobCode.php', 'selPosition='+selPosition+'&selectedLoc='+selectedLoc, '', 'form_data');
		return false;
	}*/
	else if (document.getElementById('hdnValidate').value==1 )
	{
		if(document.getElementById('CLIENTDETAILS').innerHTML!='' || document.getElementById('LOCATIONDETAILS').innerHTML!='')
		{
			if(document.getElementById('CLIENTDETAILS').innerHTML!='')
			{
				if(!validateAllClientValues())	
				{
					mainFlag = false;	
					return false;
				} 
			}
			
			if(document.getElementById('LOCATIONDETAILS').innerHTML!='')
			{
				if(!validateAllLocationValues())	
				{
					mainFlag = false;	
					return false;
				} 
			}
		}
	}
	
	else 
	{
		mainFlag = true;
	}
	
	if(mainFlag)
	{
		//alert('Final');
		//return false;
		document.form_data.submit();	
	}
}

function validIsPrimaryFalse()
{
	alert('ADP job code does not exist for this position.  This cannot be a US employee\'s primary position.');
	return false;
}
function remainingPart()
{
	mainFlag = true;	
	if (document.getElementById('hdnValidate').value==1 )
	{
		if(document.getElementById('CLIENTDETAILS').innerHTML!='' || document.getElementById('LOCATIONDETAILS').innerHTML!='')
		{
			if(document.getElementById('CLIENTDETAILS').innerHTML!='')
			{
				if(!validateAllClientValues())	
				{
					mainFlag = false;	
					return false;
				} 
			}
			
			if(document.getElementById('LOCATIONDETAILS').innerHTML!='')
			{
				if(!validateAllLocationValues())	
				{
					mainFlag = false;	
					return false;
				} 
			}
		}
	}
	
	if(mainFlag)
	{
		//alert('Final2');
		//return false;
		document.form_data.submit();	
	}
}
function populateClients(position)
{
	//alert(position);
	document.getElementById('AE').style.display='none';
	document.getElementById('RTM').style.display='none';
	document.getElementById('BackMsg').style.display='none';
	document.getElementById('CLIENTDETAILS').innerHTML = '';
	document.getElementById('CLIENTDETAILS').style.display='none';
	
	document.getElementById('LOCATIONDETAILS').innerHTML = '';
	document.getElementById('LOCATIONDETAILS').style.display='none'
	
	document.getElementById('isPrimary').disabled = false;
	document.getElementById('hdnValidate').value=1;
	document.getElementById('CLIENTDETAILS').innerHTML = '';
	hdnEmp2 = document.getElementById('hdnEmployeeID').value;
	/* New code for getting hidden values */
	if(position!='')
	{
		htmlData('getHiddenValues.php','posID='+position+'&hdnEmployeID='+hdnEmp2,'loadHiddenValues');
	}
	
	/*End*/
	
	if(document.getElementById('ddlJobCode').value == 30)
	{
			checkAllAEValidations();
	}
	else if(document.getElementById('ddlJobCode').value == 132 || document.getElementById('ddlJobCode').value == 47)
	{
			checkAllRTMValidations();
			//checkAllAEValidations();
	}
	else if(document.getElementById('ddlJobCode').value == 156)
	{

		hdnEmp = document.getElementById('hdnEmployeeID').value;
		document.getElementById('BackMsg').style.display='block';
		document.getElementById('isPrimary').disabled = true;
		//positionCheck('validateposition.php', 'position='+position+'&empID='+hdnEmp, 'BackMsg');
	} 
	
	makeItDynamic();
	
}

function checkAllAEValidations()
{
	document.getElementById('AE').style.display='block';
	document.getElementById('CLIENTDETAILS').style.display='block';	
	document.getElementById('hdnValidate').value=2;
}
function checkAllRTMValidations()
{
	document.getElementById('hdnValidate').value=3;
	document.getElementById('RTM').style.display='block';
	document.getElementById('LOCATIONDETAILS').style.display='block';
	document.getElementById('CLIENTDETAILS').style.display='block';	
}

function AddClients(employID,position)
{
	posID = document.getElementById('ddlJobCode').value;
	var selectedLoc = document.getElementById('hdnEmployeeLocation').value;
	//htmlData('populateClients_selectMT.php','employID='+employID+'&posID='+posID+'&posType='+position+'&type=Clients', 'CLIENTDETAILS');
	htmlData('populateClients_selectMT.php','employID='+employID+'&posID='+posID+'&posType='+position+'&selectedLoc='+selectedLoc+'&type=Clients', 'CLIENTDETAILS');
}
function AddLocations(employID)
{
	posID = document.getElementById('ddlJobCode').value;
	htmlData('populateClients_selectMT.php','employID='+employID+'&posID='+posID+'&type=Locat','LOCATIONDETAILS');
}

function validateAllLocationValues()
{
	
	var alllocationFlag = false;
	isChecked=false;
	var	alertmsg = '';
	var allLocs = document.getElementById('hdnRawLocs').value;
	var splitAllLocs = allLocs.split(',');
	var splitAllLocsCnt = splitAllLocs.length;
	for(var i=0; i < splitAllLocsCnt; i++)
	{
		var locVal ='';
		locVal = document.getElementsByName(splitAllLocs[i]+'[]');
		locValLen = locVal.length;
		for(var j=0; j < locValLen; j++)
		{
				
			if(locVal[j].checked==true)
			{
				isChecked=true;
				var locdescription  = locVal[j].value.split('****');
				//alert(locdescription[0]);
				//var locdescription  = locdescription1[1].value.split('$$$');
				//alert(locdescription[0]);
				resultmsg =  checkEffEndDate(j,splitAllLocs[i], locdescription[1]);
				if(resultmsg!='')
				{
					alertmsg = 	alertmsg + resultmsg;
				}
			}
		}
	}
	/*if(!isChecked)
	{
		alllocationFlag = false;
		alert('Please select at least one location');
		return alllocationFlag;
	} else*/	
	if(alertmsg!='')
	{
		alllocationFlag = false;
		alert(alertmsg);
		return alllocationFlag;
	} else {
		alllocationFlag = true;
	}
	//alert(alllocationFlag);
	
	return alllocationFlag;
}

function validateAllClientValues()
{
	
	var allclientFlag = true;
	
	isChecked=false;
	var	alertmsg = '';
	var allClients = document.getElementById('hdnRawClients').value;
	var splitAllClients = allClients.split(',');
	var splitAllClientsCnt = splitAllClients.length;
//alert(splitAllClientsCnt);
	for(var i=0; i < splitAllClientsCnt; i++)
	{
		var clientVal ='';
		//alert(splitAllClients[i]);
		clientVal = document.getElementsByName(splitAllClients[i]+'[]');
		clientValLen = clientVal.length;
		//alert(clientValLen);
		for(var j=0; j < clientValLen; j++)
		{
			if(clientVal[j].checked==true)
			{
				isChecked=true;
				//var lobdescription  = clientVal[j].value.split('****');
				var lobdescriptionRaw  = clientVal[j].value.split('****');
				var lobdescription  = lobdescriptionRaw[1].split('$$$');				
				resultmsg =  checkEffEndDate(j,splitAllClients[i], lobdescription[0]);
				if(resultmsg!='')
				{
					alertmsg = 	alertmsg + resultmsg;
				}
			}
		}
	}
	if(alertmsg!='')
	{
		allclientFlag = false;
		alert(alertmsg);
		return allclientFlag;
		//return false;
	} 

return allclientFlag;

}
	  

function checkforLOBs(clientName)
{
	
	var flag = false;
	clientVal = document.getElementsByName(clientName+'[]');
	clientValLen = clientVal.length;
	for(var j=0; j<clientValLen; j++)
	{
		if(clientVal[j].checked==true)
		{
			var flag = true;	
		}
	}
	
		return flag;
}

function allEndDates(clientName)
{
	
	
	var flag = true;
	clientVal = document.getElementsByName('endDate_'+clientName+'[]');
	clientValLen = clientVal.length;
	
	effectiveVal = document.getElementsByName('effectiveDate_'+clientName+'[]'); 
	effectiveLen = effectiveVal.length;
	var EffectiveDate=new Array();
	var EndDate=new Array();
	//alert(clientValLen);
	for(var j=0; j<effectiveLen; j++)
	{
		if(effectiveVal[j].value!='')
		{
			EffectiveDate.push(effectiveVal[j].value);
		}
	}
	
	for(kk=0; kk<clientValLen; kk++)
	{
		if(clientVal[kk].value!='')
		{
			EndDate.push(clientVal[kk].value);
		}
	}
	
	if(EffectiveDate.length==EndDate.length)
	{
			var flag = false;
			
	}
	
	return flag;
}
						
function checkforLOBs(clientName)
{
	
	var flag = false;
	clientVal = document.getElementsByName(clientName+'[]');
	clientValLen = clientVal.length;
	for(var j=0; j<clientValLen; j++)
	{
		if(clientVal[j].checked==true)
		{
			var flag = true;	
		}
	}
	
		return flag;
}

function allEndDates(clientName)
{
	
	
	var flag = true;
	clientVal = document.getElementsByName('endDate_'+clientName+'[]');
	clientValLen = clientVal.length;
	
	effectiveVal = document.getElementsByName('effectiveDate_'+clientName+'[]'); 
	effectiveLen = effectiveVal.length;
	var EffectiveDate=new Array();
	var EndDate=new Array();
	//alert(clientValLen);
	for(var j=0; j<effectiveLen; j++)
	{
		if(effectiveVal[j].value!='')
		{
			EffectiveDate.push(effectiveVal[j].value);
		}
	}
	
	for(kk=0; kk<clientValLen; kk++)
	{
		if(clientVal[kk].value!='')
		{
			EndDate.push(clientVal[kk].value);
		}
	}
	
	if(EffectiveDate.length==EndDate.length)
	{
			var flag = false;
			
	}
	
	return flag;
}

function checkADPJobCode(btnID,adpJobCode)
{
	if(adpJobCode=='N')
	{
		alert('ADP job code does not exist for this position.  This cannot be a US employee\'s primary position.');
		document.getElementById(btnID).checked=false;
		return false;
	}
}

function checkValidations2(dt)
{
	var  alertmsg='';
	var splitPos = dt.split('_');
	var ispri = "isPrimary_"+dt;
	var enddt = "endDate_"+dt;
	var stDt = "startDate_"+dt;
	var ip = document.getElementById(ispri).value;
	var ed = document.getElementById(enddt).value;
	var sd = document.getElementById(stDt);
	
	if(sd.value == "")
	{ 
		  alert("Please Choose Start Date");			
		  sd.focus();
		  return false;
	}
	if (document.getElementById(ispri).checked == 1 && ed != "" )
	{
			alert("End Date must be NULL to Activate the IsPrimary");
			return false;
	}
	
	if(splitPos[0]==132 || splitPos[0]==47)
	{
		
		
		if(document.getElementById('hdnRawLocs').value!='')
		{
			isChecked=false;
			var allLocs = document.getElementById('hdnRawLocs').value;
			var splitAllLocs = allLocs.split(',');
			//alert(splitAllLocs);
			var splitAllLocsCnt = splitAllLocs.length;
			for(var i=0; i < splitAllLocsCnt; i++)
			{
				var locVal ='';
				//alert(splitAllLocs[i]);
				locVal = document.getElementsByName(splitAllLocs[i]+'[]');
				locValLen = locVal.length;
				//alert(locValLen);
				for(var j=0; j < locValLen; j++)
				{
					if(locVal[j].checked==true)
					{
						isChecked=true;
						var locdescription  = locVal[j].value.split('****');
						resultmsg =  checkEffEndDate(j,splitAllLocs[i], locdescription[1]);
						if(resultmsg!='')
						{
							alertmsg = 	alertmsg + resultmsg;
						}
					}
				}
			}

			if(alertmsg!='')
			{
				alert(alertmsg);
				return false;
			}
		}	
	}

	if(splitPos[0]==30 || splitPos[0]==132 || splitPos[0]==47)
	{
		
		if(document.getElementById('hdnRawClients').value!='')
		{
			isChecked=false;
			var allClients = document.getElementById('hdnRawClients').value;
			var splitAllClients = allClients.split(',');
			var splitAllClientsCnt = splitAllClients.length;
			for(var i=0; i < splitAllClientsCnt; i++)
			{
				var clientVal ='';
				clientVal = document.getElementsByName(splitAllClients[i]+'[]');
				clientValLen = clientVal.length;
				for(var j=0; j < clientValLen; j++)
				{
					if(clientVal[j].checked==true)
					{
						isChecked=true;
						var lobdescriptionRaw  = clientVal[j].value.split('****');
						var lobdescription  = lobdescriptionRaw[1].split('$$$');
						resultmsg =  checkEffEndDate(j,splitAllClients[i], lobdescription[0]);
						if(resultmsg!='')
						{
							alertmsg = 	alertmsg + resultmsg;
						}
					}
				}
			}
			if(alertmsg!='')
			{
				alert(alertmsg);
				return false;
			}
		} // eof
	}
}


function checkforLOBsEdit(clientName)
{
	var flag = false;
	clientVal = document.getElementsByName(clientName+'[]');
	clientValLen = clientVal.length;
	for(var j=0; j<clientValLen; j++)
	{
		if(clientVal[j].checked==true)
		{
			var flag = true;	
		}
	}
	
		return flag;
}

function allEndDatesEdit(clientName)
{
	
	/*var flag = false;
	clientVal = document.getElementsByName('endDate_'+clientName+'[]');
	clientValLen = clientVal.length;
	//alert(clientValLen);
	for(var j=0; j<clientValLen; j++)
	{
		if(clientVal[j].value=='')
		{
			var flag = true;	
		}
	}
	
		return flag;*/
		
	var flag = true;
	clientVal = document.getElementsByName('endDate_'+clientName+'[]');
	clientValLen = clientVal.length;
	
	effectiveVal = document.getElementsByName('effectiveDate_'+clientName+'[]'); 
	effectiveLen = effectiveVal.length;
	var EffectiveDate=new Array();

	var EndDate=new Array();
	//alert(clientValLen);
	for(var j=0; j<effectiveLen; j++)
	{
		if(effectiveVal[j].value!='')
		{
			EffectiveDate.push(effectiveVal[j].value);
		}
	}
	
	for(kk=0; kk<clientValLen; kk++)
	{
		if(clientVal[kk].value!='')
		{
			EndDate.push(clientVal[kk].value);
		}
	}
	
	if(EffectiveDate.length==EndDate.length)
	{
			var flag = false;
			
	}
	
	return flag;	
	
}


function checkEffEndDate(rowID,clientName,lobVal)
{
	effectiveCheck = document.getElementsByName('effectiveDate_'+clientName+'[]');
	//alert(lobVal);
	var msg = '';
	if(effectiveCheck[rowID].value=='')
	{
		var msg = 'Effective date is empty for "'+lobVal+'"\n';
		return msg;
	} 
	else 
	{
		return msg;	
	}
	
	//return false;
}

function disableEffEndDate(rowID,clientName)
{
	effectiveCheck = document.getElementsByName('effectiveDate_'+clientName+'[]');
	effectiveCheck[rowID].disabled = true;
}
	 
 
function hideshowDiv(dt)
{
   var hdnVar = "hdnVar_"+dt; 
   var divID = "showhide_"+dt;
   var hv = document.getElementById(hdnVar).value;
	 
   if (hv == 1)
   {
	  document.getElementById(divID).style.display = "block";
	  document.getElementById(hdnVar).value = 0;
   }
   else
   {
	  document.getElementById(divID).style.display = "none";
	  document.getElementById(hdnVar).value = 1;
   }  
	return false;  
}

function populateTextbox(dt)
{
	var ddlSDate = "ddlStartDate_"+dt;
	var txtSDate = "startDate_"+dt;
	var divID = "showhide_"+dt;
	var hdnVar = "hdnVar_"+dt;
	var hdnEffDate = "hdnEffDate_"+dt;
	
	if(document.getElementById(ddlSDate).value != "")
	{
		document.getElementById(txtSDate).value = document.getElementById(ddlSDate).value;
	}
	else
	{
		document.getElementById(txtSDate).value = document.getElementById(hdnEffDate).value;
	}
	
	document.getElementById(divID).style.display = "none";
	document.getElementById(hdnVar).value = 1;

}
function posHisEditClients(employID,divCount,posID,posType)
{
	//alert(employID);alert(divCount);
	/*document.getElementById('AE').style.display='none';
	document.getElementById('CLIENTDETAILS').style.display='none';*/
	//$(".clientClass").hide();
	$("div[class='clientClass']").empty(); 
	//$("#CLIENTDETAILSEDIT"+divCount).show(); 
	
	htmlData('populateClients_selectMT1.php','employID='+employID+'&posID='+posID+'&posType='+posType+'&type=Clients','CLIENTDETAILSEDIT'+divCount);
}
function posHisEditLocs(employID,divCount,posID)
{
	//alert(employID);alert(divCount);
	/*document.getElementById('RTM').style.display='none';
	document.getElementById('LOCATIONDETAILS').style.display='none';*/
	$("div[class='clientClass']").empty(); 
	htmlData('populateClients_selectMT1.php','employID='+employID+'&posID='+posID+'&type=Locat','LOCATIONDETAILSEDIT'+divCount);
}
function lobToggle(clntNameCnt)
{
	//alert(clntNameCnt);
	var totalClient = document.getElementById('hdnRawClients').value;
	var totalClientArr = totalClient.split(',');
	var totalClientcount = totalClientArr.length;
	
	for(xy=0; xy<totalClientcount;xy++)
	{
		
		document.getElementById(totalClientArr[xy]).style.display = 'none';
		document.getElementById('img'+totalClientArr[xy]).innerHTML = '<img src="../../../SkillChangeRequestPortal/includes/asc.gif"  />';
	}
	document.getElementById(clntNameCnt).style.display = 'block';
	document.getElementById('img'+clntNameCnt).innerHTML = '<img src="../../../SkillChangeRequestPortal/includes/desc.gif"  />';
	
}
function lobToggleEdit(clntNameCnt)
{
	//alert(clntNameCnt);
	var totalClient = document.getElementById('hdnRawClients').value;
	var totalClientArr = totalClient.split(',');
	var totalClientcount = totalClientArr.length;
	for(xy=0; xy<totalClientcount;xy++)
	{
		document.getElementById(totalClientArr[xy]).style.display = 'none';
		document.getElementById('img'+totalClientArr[xy]).innerHTML = '<img src="../../../SkillChangeRequestPortal/includes/asc.gif"  />';
	}
	document.getElementById(clntNameCnt).style.display = 'block';
	document.getElementById('img'+clntNameCnt).innerHTML = '<img src="../../../SkillChangeRequestPortal/includes/desc.gif"  />';
	
}
function checkAll(clntName, val) 
{
	if (!val)
	{
		doCheck = false
	}
	else
	{
		doCheck = true
	}
	clientVal = document.getElementsByName(clntName+'[]');
	clientLen = clientVal.length;
	for(var x = 0;x < clientLen;x++)
	{
		if(doCheck)
		{
			clientVal[x].checked=true;
		}
		else
		{
			clientVal[x].checked=false;
			removeFields(x, clntName);
		}
	}

}
function completeAll(clientName)
{
	clientVal = document.getElementsByName(clientName+'[]');
	clientLen = clientVal.length;
	var chkFlag = false;
	for(var x = 0;x < clientLen;x++)
	{
		if(clientVal[x].checked==true)
		{
			copyfileds(x, clientName);
			chkFlag = true;
		}
	}
	if(!chkFlag)
	{
		alert('Please select at least one checkbox');
		return false;
		
	}
}
function copyfileds(rowID, clientName)
{
	effectiveDateN = document.getElementsByName('effectiveDate_'+clientName+'[]');
	effectiveDateVal = document.getElementById('effectiveDate_'+clientName).value;
	
	endDateN = document.getElementsByName('endDate_'+clientName+'[]');
	endDateNVal = document.getElementById('endDate_'+clientName).value;
	
	effectiveDateN[rowID].value = effectiveDateVal;
	endDateN[rowID].value = endDateNVal;
}
function selectNone(clientName)
{
	clientVal = document.getElementsByName(clientName+'[]');
	clientLen = clientVal.length;
	for(var x = 0;x < clientLen;x++)
	{
		clientVal[x].checked=false;
		removeFields(x, clientName);
	}
}
function removeFields(rowID, clientName)
{
	//clientChkMain = document.getElementsByName(clientName);
	effectiveDateN = document.getElementsByName('effectiveDate_'+clientName+'[]');
	effectiveDateMain = document.getElementsByName('effectiveDate_'+clientName);
	endDateN = document.getElementsByName('endDate_'+clientName+'[]');
	endDateMain = document.getElementsByName('endDate_'+clientName);
	
	//clientChkMain[0].checked=false;
	effectiveDateMain[0].value = '';
	endDateMain[0].value = '';
	effectiveDateN[rowID].value = '';
	endDateN[rowID].value = '';
}
function lobChkbox(dispName , val) 
{
	if (!val)
	{
		doCheck = false
	}
	else
	{
		doCheck = true
	}
	
	if(!doCheck)
	{
		alert(dispName);
		effectiveDateVal = document.getElementById('effectiveDate_'+dispName).value;
		alert(effectiveDateVal);
		effectiveDateVal = '';
	}
}
function greaterFunc(dispName,type)
{
	//alert(type);
	if(type=='Loc')
	{
		var str1 = document.getElementById('effectiveDateLoc_'+dispName).value;
		var str2 = document.getElementById('endDateLoc_'+dispName).value;
		mainEndDate = document.getElementsByName('endDateLoc_'+dispName);
	}
	else
	{
		var str1 = document.getElementById('effectiveDate_'+dispName).value;
		var str2 = document.getElementById('endDate_'+dispName).value;
		mainEndDate = document.getElementsByName('endDate_'+dispName);
	}
	
	var dt1  = parseInt(str1.substring(0,2),10); 
	var mon1 = parseInt(str1.substring(3,5),10); 
	var yr1  = parseInt(str1.substring(6,10),10); 
	var dt2  = parseInt(str2.substring(0,2),10); 
	var mon2 = parseInt(str2.substring(3,5),10); 
	var yr2  = parseInt(str2.substring(6,10),10); 
	var date1 = new Date(yr1, mon1, dt1); 
	var date2 = new Date(yr2, mon2, dt2); 
	
	if(str1=='')
	{
		alert('Effective date should not be empty');
		mainEndDate[0].value='';
	}
	if(date2 < date1) 
	{ 
		alert("End date must be greater than effective date"); 
		mainEndDate[0].value='';
		return false; 
	} 
	
}
function chkClientCheckBox(dispName)
{
	document.getElementById('chkDispName_'+dispName).checked=true;
}

function checkORuncheckAll(checkValue)
{
	if (!checkValue)
	{
		doCheck = false
	}
	else
	{
		doCheck = true
	}
	var allLocs = document.getElementById('hdnRawLocs').value;
	var splitAllLocs = allLocs.split(',');
	var splitAllLocsCnt = splitAllLocs.length;
	//alert(splitAllLocsCnt);
	//alert(doCheck);
	for(var x=0; x < splitAllLocsCnt; x++)
	{	
		var locChkVal ='';
		locChkVal = document.getElementsByName(splitAllLocs[x]+'[]');
		locValLen = locChkVal.length;
		for(var j=0; j < locValLen; j++)
		{
			if(doCheck)
			{
				locChkVal[j].checked=true;
			}
			else
			{
				locChkVal[j].checked=false;
				unCheckLoc(splitAllLocs[x],j);
			}
		}
	}
}
function unCheckLoc(loc,rowID)
{
	effectiveDateN = document.getElementsByName('effectiveDate_'+loc+'[]');
	endDateN = document.getElementsByName('endDate_'+loc+'[]');
	
	effectiveDateN[rowID].value = '';

	endDateN[rowID].value = '';
}

function completeAllLoc(checkValue)
{
	topEffDate = document.getElementById('effectiveDateLoc_').value;
	topEndDate = document.getElementById('endDateLoc_').value;
	//alert(topEffDate);
	if(topEffDate=='' && topEndDate=='')
	{
		alert('Please select either effective date or end date');
		return false;
	}
	else
	{
		var allLocs = document.getElementById('hdnRawLocs').value;
		var splitAllLocs = allLocs.split(',');
		var splitAllLocsCnt = splitAllLocs.length;
		for(var x=0; x < splitAllLocsCnt; x++)
		{	
			var locChkVal ='';
			var effDateLoc ='';
			var endDateLoc ='';
			locChkVal = document.getElementsByName(splitAllLocs[x]+'[]');
			effDateLoc = document.getElementsByName('effectiveDate_'+splitAllLocs[x]+'[]');
			endDateLoc = document.getElementsByName('endDate_'+splitAllLocs[x]+'[]');
			locValLen = locChkVal.length;
			for(var j=0; j < locValLen; j++)
			{
				if(locChkVal[j].checked==true)
				{
					effDateLoc[j].value = topEffDate;
					endDateLoc[j].value = topEndDate;
				}
			}
		}
	}
}

function chkLocCheckBox(loc)
{
	document.getElementById(loc).checked=true;
}

function storedisprimaryValue(clientVal)
{
	
	//alert(clientVal);
	
	if(document.getElementById('DivDiv'+clientVal).checked==true)
	{
		document.getElementById('hdnIsPrimary').value='';
		document.getElementById('hdnIsPrimary').value = clientVal;
		
										   
	}
	else {
		
		document.getElementById('hdnIsPrimary').value='';
	}
}

function clearendDate(enddateId)
{
	
	document.getElementById(enddateId).value='';
	return false;
}
function validateIsPrimaryAE(clientName,fName,posID)
{
	//alert(clientName);
	if(fName=='add')
	{
		lobToggle(clientName);
	}
	else if(fName=='edit')
	{
		lobToggleEdit(clientName);
	}
	var isPrimaryAEFlag = false;
	effectiveDateN = document.getElementsByName('effectiveDate_'+clientName+'[]');
	effDateLen = effectiveDateN.length;
	//alert(effDateLen);
	for(var j = 0; j < effDateLen; j++)
	{
		if(effectiveDateN[j].value!='')
		{
			isPrimaryAEFlag = true;
		}
		
	}
	if(!isPrimaryAEFlag)
	{
		alert('Please check at least one effective date for '+clientName);
		return false;
	}
	else
	{
		return true;
	}
}
</script>

<?php
$typeNew = $_REQUEST['typeNew'];
if($typeNew=="existed")
{
	echo "<script type='text/javascript'>alert('Record already exists with the same key values (Supervisor,effeciveDate)');</script>";
}


if(isset($_REQUEST['supNotExist']))
{
?>
<script type="text/javascript">
function modalWindow()
{
	var id = '#dialogMain';
	$(id).css('width', '450');
	$(id).css('height', '170');
	
	//Get the screen height and width
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	
	//Set heigth and width to mask to fill up the whole screen
	$('#mask').css({'width':maskWidth,'height':maskHeight});
	
	//transition effect		
	$('#mask').fadeIn(1000);	
	$('#mask').fadeTo("slow",0.8);	
	
	//Get the window height and width
	var winH = $(window).height();
	var winW = $(window).width();
	  
	//Set the popup window to center
	$(id).css('top',  winH/2-$(id).height()/2);
	$(id).css('left', winW/2-$(id).width()/2);
	
	//transition effect
	$(id).fadeIn(2000); 
	
	
}

modalWindow();
function closeWindow()
{
	
		//Cancel the link behavior
		
		$('#mask').hide();
		$('.window').hide();
		$('#replace').html("&nbsp;"); /* THIS WAS ADDED BCZ. TO INITIALIZE THE DOM AGAIN */ 
	
}

function checkcurrentDateValidations()
{
	var datemainFlag = true;
	var today = new Date();
	var newDate = document.getElementById('effectiveDateModal').value;
	
	
	var todayStamp = new Date(today);    	
	var newDateStamp= new Date(newDate);
		
		
	//alert(newDateStamp);
	//alert(todayStamp);
	if(document.getElementById('ddlSupervisors').value=='')
	{
		alert('Please choose supervisor');	
		datemainFlag = false;
		document.getElementById('ddlSupervisors').focus();
	}
	else if(newDateStamp>todayStamp)
	{
			alert('Effective date should not exceed today');
			document.getElementById('effectiveDate').focus();
			datemainFlag = false;
	}
	
	if(!datemainFlag)
	{
		return false;
	}
	else
	{
		return true;	
	}
	
	
}
</script>
<?php } ?>