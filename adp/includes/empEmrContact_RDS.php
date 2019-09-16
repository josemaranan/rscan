<?php
//$employeeeMaintenanceObj->setUSLocations();
unset($sqlMainQry);
unset($rstMainQry);

$employeeeMaintenanceObj->setADPClients();
$clientsArray = $employeeeMaintenanceObj->getADPClients();

$sqlEmergencyDetails = "SELECT 
								*
							FROM
								[ctlEmployeeEmergencyContactInformation] WITH (NOLOCK)
							WHERE 
								employeeID = $employeeID "; 
	$rstEmergencyDetails = $employeeeMaintenanceObj->execute($sqlEmergencyDetails);
	while($rowEmpEmergencyDetails = mssql_fetch_assoc($rstEmergencyDetails))
	{	
		$empoyeeEmergencyDetails[] = $rowEmpEmergencyDetails;
	}
	mssql_free_result($rstEmergencyDetails);
	
	unset($sqlStates);
	unset($rstStates);
	unset($adstate);
	$sqlStates = "SELECT [state] , [description] FROM results.dbo.ctlStates WITH (NOLOCK) WHERE countryCode = 'U' ";
	$rstStates = $employeeeMaintenanceObj->execute($sqlStates);
	while($adstate = mssql_fetch_assoc($rstStates))
	{	
		$statedropdown[$adstate['state']] = $adstate['description'];
	}
	mssql_free_result($rstStates);
?>
<style type="text/css">
#adpsearchTable th{
	padding-left:50px;
}

</style>
<?php
echo $htmlTagObj->openTag('div', 'id="topHeading" class="outer"');
echo $topLevelHeading;
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="businessRuleHeading" class="outer"');
echo 'Modify Emergency Contact Information';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo();

echo $htmlTagObj->openTag('div', 'id="topHeading" class="outer"');
echo 'Modify Emergency Contact Information';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="scrollingdatagrid" class="scrollingdatagrid"');
echo $htmlTagObj->openTag('div', 'id="singlePixelBorder" style="padding:8px;"'); 

// Effective date
$htmlTextElement->name = 'txtEmEffecDate';
$htmlTextElement->id = 'txtEmEffecDate';
$htmlTextElement->value = date('m/d/Y');
$htmlTextElement->readonly = 'true';
$htmlTextElement->size = '7';
$htmlTextElement->accesskey = 'true';
$txtEmEffecDate = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

// Loading First NAme
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtEmFirstName';
$htmlTextElement->id = 'txtEmFirstName';
$htmlTextElement->value =  stripslashes($empoyeeEmergencyDetails[0]['firstName']);
$htmlTextElement->maxLength = '30';
$txtEmFirstName = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtEmFirstName', stripslashes($empoyeeEmergencyDetails[0]['firstName']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'firstName' , 'ctlEmployeeEmergencyContactInformation#firstName');

// Loading Middle Name
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtEmLastName';
$htmlTextElement->id = 'txtEmLastName';
$htmlTextElement->value =  stripslashes($empoyeeEmergencyDetails[0]['lastName']);
$htmlTextElement->maxLength = '30';
$txtEmLastName = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtEmLastName', stripslashes($empoyeeEmergencyDetails[0]['lastName']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'lastName' , 'ctlEmployeeEmergencyContactInformation#lastName');

// Loading Last NAme
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtEmPhone';
$htmlTextElement->id = 'txtEmPhone';
$htmlTextElement->value =  stripslashes($empoyeeEmergencyDetails[0]['phone']);
$htmlTextElement->maxLength = '30';
$txtEmPhone = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtEmPhone', stripslashes($empoyeeEmergencyDetails[0]['phone']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'phone' , 'ctlEmployeeEmergencyContactInformation#phone');

// Loading Email
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtEmEmail';
$htmlTextElement->id = 'txtEmEmail';
$htmlTextElement->value =  stripslashes($empoyeeEmergencyDetails[0]['emailAddress']);
$htmlTextElement->maxLength = '30';
$txtEmEmail = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtEmEmail', stripslashes($empoyeeEmergencyDetails[0]['emailAddress']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'emailAddress' , 'ctlEmployeeEmergencyContactInformation#emailAddress');

$relationArray = array('father' => 'Father', 'mother' => 'Mother', 'spouse' => 'Spouse', 'guardian' => 'Guardian', 'other' => 'Other');
// Loading Locations
$commonListBox->name = 'ddlEmRelationship';
$commonListBox->id 	= 'ddlEmRelationship';
$commonListBox->customArray = $relationArray;
$commonListBox->selectedItem = stripslashes($empoyeeEmergencyDetails[0]['relationShip']);
//$commonListBox->optionKey = 'relationShip';
//$commonListBox->optionVal = 'description';
$ddlEmRelationship = $commonListBox->AddRow('', 'Please choose');
$ddlEmRelationship = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlEmRelationship', stripslashes($empoyeeEmergencyDetails[0]['relationShip']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'relationShip' , 'ctlEmployeeEmergencyContactInformation#relationShip');

$htmlTextElement->name = 'chkEmSpouseWrk';
$htmlTextElement->id = 'chkEmSpouseWrk';
$htmlTextElement->value = 'Y';
$htmlTextElement->txt = 'Do you have a spouse that works for The Results Companies?';
$htmlTextElement->type = 'checkbox'; //isDefaultChkd
$htmlTextElement->isDefaultChkd = ($empoyeeEmergencyDetails[0]['isSpouseWorkInResults'] == 'Y') ? '1' : '';
$chkEmSpouseWrk = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('chkEmSpouseWrk', stripslashes($empoyeeEmergencyDetails[0]['isSpouseWorkInResults']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'isSpouseWorkInResults' , 'ctlEmployeeEmergencyContactInformation#isSpouseWorkInResults');

// Loading Last NAme
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtEmAddress1';
$htmlTextElement->id = 'txtEmAddress1';
$htmlTextElement->value =  stripslashes($empoyeeEmergencyDetails[0]['address1']);
$htmlTextElement->maxLength = '30';
$txtEmAddress1 = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtEmAddress1', stripslashes($empoyeeEmergencyDetails[0]['address1']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'address1' , 'ctlEmployeeEmergencyContactInformation#address1');

// Loading Last NAme
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtEmAddress2';
$htmlTextElement->id = 'txtEmAddress2';
$htmlTextElement->value =  stripslashes($empoyeeEmergencyDetails[0]['address2']);
$htmlTextElement->maxLength = '30';
$txtEmAddress2 = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtEmAddress2', stripslashes($empoyeeEmergencyDetails[0]['address2']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'address2' , 'ctlEmployeeEmergencyContactInformation#address2');

// Loading Last NAme
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtEmCity';
$htmlTextElement->id = 'txtEmCity';
$htmlTextElement->value =  stripslashes($empoyeeEmergencyDetails[0]['city']);
$htmlTextElement->maxLength = '30';
$txtEmCity = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtEmCity', stripslashes($empoyeeEmergencyDetails[0]['city']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'city' , 'ctlEmployeeEmergencyContactInformation#city');

// Loading Locations
$commonListBox->name = 'ddlEmState';
$commonListBox->id 	= 'ddlEmState';
$commonListBox->customArray = $statedropdown;
$commonListBox->selectedItem = stripslashes($empoyeeEmergencyDetails[0]['state']);
$commonListBox->optionKey = 'location';
$commonListBox->optionVal = 'description';
$ddlEmState = $commonListBox->AddRow('', 'Please choose');
$ddlEmState = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlEmState', stripslashes($empoyeeEmergencyDetails[0]['state']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'state' , 'ctlEmployeeEmergencyContactInformation#state');


// Loading Last NAme
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtEmzip';
$htmlTextElement->id = 'txtEmzip';
$htmlTextElement->value =  stripslashes($empoyeeEmergencyDetails[0]['postal']);
$htmlTextElement->maxLength = '30';
$txtEmzip = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtEmzip', stripslashes($empoyeeEmergencyDetails[0]['postal']) ,'results', 'ctlEmployeeEmergencyContactInformation', 'postal' , 'ctlEmployeeEmergencyContactInformation#postal');

//button Search 
$htmlButtonElement->id = 'search';
$htmlButtonElement->name = 'search';
$htmlButtonElement->value = 'Save';
$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->style = 'text-align: left;';
$htmlButtonElement->onclick = 'return validateEmergencyInfo("' . $curDate . '");';
$htmlButtonElement->type = 'submit';
$btnSearch = $htmlButtonElement->renderHtml();
$htmlButtonElement->resetProperties();

$emptyData			= $htmlTextElement->addLabel($emptyData, '', '#ff0000','');
$txtEmEffecDate		= $htmlTextElement->addLabel($txtEmEffecDate, 'Effective Date', '#ff0000','');
$txtEmFirstName		= $htmlTextElement->addLabel($txtEmFirstName, 'First Name', '#ff0000','');
$txtEmLastName		= $htmlTextElement->addLabel($txtEmLastName, 'Last Name', '#ff0000','');
$txtEmPhone			= $htmlTextElement->addLabel($txtEmPhone, 'Phone', '#ff0000','');
$txtEmEmail			= $htmlTextElement->addLabel($txtEmEmail, 'Email', '#ff0000','');
$ddlEmRelationship	= $htmlTextElement->addLabel($ddlEmRelationship, 'Relationship', '#ff0000','');
$chkEmSpouseWrk		= $htmlTextElement->addLabel($chkEmSpouseWrk, '', '#ff0000','');
$txtEmAddress1		= $htmlTextElement->addLabel($txtEmAddress1, 'Address 1', '#ff0000','');
$txtEmAddress2		= $htmlTextElement->addLabel($txtEmAddress2, 'Address 2', '#ff0000','');
$txtEmCity			= $htmlTextElement->addLabel($txtEmCity, 'City', '#ff0000','');
$ddlEmState			= $htmlTextElement->addLabel($ddlEmState, 'State/Prov', '#ff0000','');
$txtEmzip			= $htmlTextElement->addLabel($txtEmzip, 'Postal/Zip:', '#ff0000','');

$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->setTableClass('');
$tableObj->setTableAttr();

$tableObj->searchFields['emptyData'] = $emptyData;
$tableObj->searchFields['txtEmEffecDate'] = $txtEmEffecDate;
$tableObj->searchFields['txtEmFirstName'] = $txtEmFirstName;
$tableObj->searchFields['txtEmLastName'] = $txtEmLastName;
$tableObj->searchFields['txtEmPhone'] = $txtEmPhone;
$tableObj->searchFields['txtEmEmail'] = $txtEmEmail;
$tableObj->searchFields['ddlEmRelationship'] = $ddlEmRelationship;
$tableObj->searchFields['chkEmSpouseWrk'] = $chkEmSpouseWrk;
$tableObj->searchFields['txtEmAddress1'] = $txtEmAddress1;
$tableObj->searchFields['txtEmAddress2'] = $txtEmAddress2;
$tableObj->searchFields['txtEmCity'] = $txtEmCity;
$tableObj->searchFields['ddlEmState'] = $ddlEmState;
$tableObj->searchFields['txtEmzip'] = $txtEmzip;
$tableObj->searchFields['button'] = $btnSearch;

$searchForm = $tableObj->searchFormTableComponent();

$htmlForm->action = 'employeeEmergencyInfo_process_RDS.php';
$htmlForm->name = 'employeeEmergencyInfo';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'POST';

echo $htmlForm->startForm();
echo $htmlTagObj->openTag('div', 'class="empAddressmainTable" style="width:95%;"'); 
echo $searchForm;
//echo 'entered';
//*
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnEmployeeID';
$htmlTextElement->id = 'hdnEmployeeID';
$htmlTextElement->value = $employeeID;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

echo $htmlTagObj->closeTag('div');
echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');
//*/
?>

<script language="javascript">
var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"]; ?>';
$( "#txtEmEffecDate" ).datepicker({
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