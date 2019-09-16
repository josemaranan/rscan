<?php
//$employeeeMaintenanceObj->setUSLocations();
unset($sqlMainQry);
unset($rstMainQry);

$sqlPersonalInfo = "SELECT 
							a.firstName,
							a.lastName,
							CONVERT(VARCHAR(20),a.dob,101) dob,
							a.gender,
							a.middle,
							common.dbo.fn_rnetV3_Decrypt(a.secureSSN,'".DBMS_PASSWORD."') [secureSSN],
							a.ethnicity,
							a.maritalStatus,
							a.educationLevel,
							a.citizenshipStatus,
							a.visaType,
							e.description as educationLevelDescription
						FROM 
							results.dbo.ctlEmployees a WITH (NOLOCK) 
						LEFT JOIN
							results.dbo.ctlEducationLevelDetails e WITH (NOLOCK)
						ON
							a.educationLevel = e.educationLevelID
						WHERE 
							a.employeeID = '$employeeID' ";


	$rstPersonalInfo = $employeeeMaintenanceObj->execute($sqlPersonalInfo);	
	while($rowPersonalInfo = mssql_fetch_assoc($rstPersonalInfo))
	{	
		$employeePersonalInfo[] = $rowPersonalInfo;
	}
	mssql_free_result($rstPersonalInfo);
	
	//RACE QRY
	$sqlRaceEthnicity = " SELECT * FROM ctlRaceEthnicityDetails WITH (NOLOCK) ORDER BY description ";
	$rstRaceEthnicity = $employeeeMaintenanceObj->execute($sqlRaceEthnicity);
	$rowsRaceEthnicityNum = $employeeeMaintenanceObj->getNumRows($rstRaceEthnicity);
	if($rowsRaceEthnicityNum>=1)
	{
		$raceEthnicityArray = $employeeeMaintenanceObj->bindingInToArray($rstRaceEthnicity);
	}
	mssql_free_result($rstRaceEthnicity);
	
	//EDUCATION LEVELS QRY
	$sqlEducationLevels = " SELECT * FROM ctlEducationLevelDetails WITH (NOLOCK)  ORDER BY description ";
	$rstEducationLevels = $employeeeMaintenanceObj->execute($sqlEducationLevels);
	$rowsEducationLevelsNum = $employeeeMaintenanceObj->getNumRows($rstEducationLevels);
	if($rowsEducationLevelsNum>=1)
	{
		$educationLevelArray = $employeeeMaintenanceObj->bindingInToArray($rstEducationLevels);
	}
	mssql_free_result($rstEducationLevels);

	//CITIZENSHIP QRY
	$sqlCitizenShip = " SELECT * FROM ctlCitizenShipStatuses WITH (NOLOCK) ORDER BY [description] ";
	$rstCitizenShip = $employeeeMaintenanceObj->execute($sqlCitizenShip);
	$rowsCitizenShipNum = $employeeeMaintenanceObj->getNumRows($rstCitizenShip);
	if($rowsCitizenShipNum>=1)
	{
		$citizenShipArray = $employeeeMaintenanceObj->bindingInToArray($rstCitizenShip);
	}
	mssql_free_result($rstCitizenShip);
	
	//VISATYPE QRY
	$sqlVisaType = " SELECT * FROM ctlVisaTypes WITH (NOLOCK) ORDER BY [description] ";
	$rstVisaType = $employeeeMaintenanceObj->execute($sqlVisaType);
	$rowsVisaTypeNum = $employeeeMaintenanceObj->getNumRows($rstVisaType);
	if($rowsVisaTypeNum>=1)
	{
		$visaTypeArray = $employeeeMaintenanceObj->bindingInToArray($rstVisaType);
	}
	mssql_free_result($rstVisaType);
	
	//MARITALSTATUS QRY
	$sqlMaritalStatus = " SELECT * FROM ctlMaritalStatuses WITH (NOLOCK) ORDER BY [description] ";
	$rstMaritalStatus = $employeeeMaintenanceObj->execute($sqlMaritalStatus);
	$rowsMaritalStatusNum = $employeeeMaintenanceObj->getNumRows($rstMaritalStatus);
	if($rowsMaritalStatusNum>=1)
	{
		$maritalStatusArray = $employeeeMaintenanceObj->bindingInToArray($rstMaritalStatus);
	}
	mssql_free_result($rstMaritalStatus);
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
echo 'Modify Personal Information';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo();

echo $htmlTagObj->openTag('div', 'id="topHeading" class="outer"');
echo 'Modify Personal Information';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="scrollingdatagrid" class="scrollingdatagrid"');
echo $htmlTagObj->openTag('div', 'id="singlePixelBorder" style="padding:8px;"'); 

$htmlForm->action = 'employeePersonalInfo_process.php';
$htmlForm->name = 'employeePersonalinfo';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'POST';

echo $htmlForm->startForm();


// Effective date
$htmlTextElement->name = 'txtPerEffecDate';
$htmlTextElement->id = 'txtPerEffecDate';
$htmlTextElement->value = date('m/d/Y');
$htmlTextElement->readonly = 'true';
$htmlTextElement->size = '7';
$htmlTextElement->accesskey = 'true';
$txtPerEffecDate = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

// Loading First NAme
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtPerFirstName';
$htmlTextElement->id = 'txtPerFirstName';
$htmlTextElement->value =  stripslashes($employeePersonalInfo[0]['firstName']);
$htmlTextElement->maxLength = '30';
$txtPerFirstName = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtPerFirstName', stripslashes($employeePersonalInfo[0]['firstName']) ,'results', 'ctlEmployees', 'firstName' , 'ctlEmployees#firstName');

// Loading Middle Name
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtPerMiddleName';
$htmlTextElement->id = 'txtPerMiddleName';
$htmlTextElement->value =  stripslashes($employeePersonalInfo[0]['middle']);
$htmlTextElement->maxLength = '30';
$txtPerMiddleName = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtPerMiddleName', stripslashes($employeePersonalInfo[0]['middle']) ,'results', 'ctlEmployees', 'middle' , 'ctlEmployees#middle');

// Loading Last NAme
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtPerLastName';
$htmlTextElement->id = 'txtPerLastName';
$htmlTextElement->value =  stripslashes($employeePersonalInfo[0]['lastName']);
$htmlTextElement->maxLength = '30';
$txtPerLastName = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtPerLastName', stripslashes($employeePersonalInfo[0]['lastName']) ,'results', 'ctlEmployees', 'lastName' , 'ctlEmployees#lastName');

// Date of birth
$htmlTextElement->name = 'txtPerDOB';
$htmlTextElement->id = 'txtPerDOB';
$htmlTextElement->value = !empty($employeePersonalInfo[0]['dob']) ? $employeePersonalInfo[0]['dob'] : '';
$htmlTextElement->readonly = 'true';
$htmlTextElement->size = '7';
//$htmlTextElement->accesskey = 'true';
$txtPerDOB = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtPerDOB', stripslashes($employeePersonalInfo[0]['dob']) ,'results', 'ctlEmployees', 'dob' , 'ctlEmployees#DOB');

foreach($maritalStatusArray AS $key => $val)
{
	$marStatusArr[$val['maritalStatus']] = $val['description'];
}

// Loading Locations
$commonListBox->name = 'ddlPerMaritalStatus';
$commonListBox->id 	= 'ddlPerMaritalStatus';
$commonListBox->customArray = $marStatusArr;
$commonListBox->selectedItem = stripslashes($employeePersonalInfo[0]['maritalStatus']);
$commonListBox->optionKey = 'maritalStatus';
$commonListBox->optionVal = 'description';
$ddlPerMaritalStatus = $commonListBox->AddRow('', 'Please choose');
$ddlPerMaritalStatus = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlPerMaritalStatus', stripslashes($employeePersonalInfo[0]['maritalStatus']) ,'results', 'ctlEmployees', 'maritalStatus' , 'ctlEmployees#maritalStatus');

foreach($raceEthnicityArray AS $key => $val)
{
	$race[$val['raceEthnicityID']] = $val['description'];
}

// Loading Locations
$commonListBox->name = 'ddlPerRaceEthnicity';
$commonListBox->id 	= 'ddlPerRaceEthnicity';
$commonListBox->customArray = $race;
$commonListBox->selectedItem = stripslashes($employeePersonalInfo[0]['ethnicity']);
$commonListBox->optionKey = 'raceEthnicityID';
$commonListBox->optionVal = 'description';
$ddlPerRaceEthnicity = $commonListBox->AddRow('', 'Please choose');
$ddlPerRaceEthnicity = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlPerRaceEthnicity', stripslashes($employeePersonalInfo[0]['ethnicity']) ,'results', 'ctlEmployees', 'ethnicity' , 'ctlEmployees#ethnicity');

foreach($educationLevelArray AS $key => $val)
{
	$eduLevelArr[$val['educationLevelID']] = $val['description'];
}
// Loading Locations
$commonListBox->name = 'ddlPerHigestEducation';
$commonListBox->id 	= 'ddlPerHigestEducation';
$commonListBox->customArray = $eduLevelArr;
$commonListBox->selectedItem = stripslashes($employeePersonalInfo[0]['educationLevel']);
$commonListBox->optionKey = 'educationLevelID';
$commonListBox->optionVal = 'description';
$ddlPerHigestEducation = $commonListBox->AddRow('', 'Please choose');
$ddlPerHigestEducation = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlPerHigestEducation', stripslashes($employeePersonalInfo[0]['educationLevel']) ,'results', 'ctlEmployees', 'educationLevel' , 'ctlEmployees#educationLevel');

$genderArray = array('M' => 'Male', 'F' => 'Female');

// Loading Locations
$commonListBox->name = 'ddlPerGender';
$commonListBox->id 	= 'ddlPerGender';
$commonListBox->customArray = $genderArray;
$commonListBox->selectedItem = stripslashes($employeePersonalInfo[0]['gender']);
$commonListBox->optionKey = 'gender';
$commonListBox->optionVal = 'description';
$ddlPerGender = $commonListBox->AddRow('', 'Please choose');
$ddlPerGender = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlPerGender', stripslashes($employeePersonalInfo[0]['gender']) ,'results', 'ctlEmployees', 'gender' , 'ctlEmployees#gender');

foreach($citizenShipArray AS $key => $val)
{
	$citizenArr[$val['citizenShipStatus']] = $val['description'];
}
// Loading Locations
$commonListBox->name = 'ddlPerCitizenStatus';
$commonListBox->id 	= 'ddlPerCitizenStatus';
$commonListBox->customArray = $citizenArr;
$commonListBox->selectedItem = stripslashes($employeePersonalInfo[0]['citizenshipStatus']);
$commonListBox->optionKey = 'citizenShipStatus';
$commonListBox->optionVal = 'description';
$ddlPerCitizenStatus = $commonListBox->AddRow('', 'Please choose');
$ddlPerCitizenStatus = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlPerCitizenStatus', stripslashes($employeePersonalInfo[0]['citizenshipStatus']) ,'results', 'ctlEmployees', 'citizenshipStatus' , 'ctlEmployees#citizenshipStatus');

foreach($visaTypeArray AS $key => $val)
{
	$vistaTypeArr[$val['visaType']] = $val['description'];
}
// Loading Locations
$commonListBox->name = 'ddlPerVisaType';
$commonListBox->id 	= 'ddlPerVisaType';
$commonListBox->customArray = $vistaTypeArr;
$commonListBox->selectedItem = stripslashes($employeePersonalInfo[0]['visaType']);
$commonListBox->optionKey = 'visaType';
$commonListBox->optionVal = 'description';
$ddlPerVisaType = $commonListBox->AddRow('', 'Please choose');
$ddlPerVisaType = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlPerVisaType', stripslashes($employeePersonalInfo[0]['visaType']) ,'results', 'ctlEmployees', 'visaType' , 'ctlEmployees#visaType');


$secureSSNValue = str_pad(substr($employeePersonalInfo[0]['secureSSN'],5,4),9,'*',STR_PAD_LEFT);
unset($_SESSION['emloyeeAuthReveal']);
$_SESSION['emloyeeAuthReveal'] = $employeePersonalInfo[0]['secureSSN'];
// Loading Last NAme
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtPerSSNumber';
$htmlTextElement->id = 'txtPerSSNumber';
$htmlTextElement->value =  !empty($employeePersonalInfo[0]['secureSSN']) ? $secureSSNValue : '';
$htmlTextElement->maxLength = '9';
$htmlTextElement->onkeypress = 'return onlyNumbers();';
$htmlTextElement->onfocus = 'javaScript:if(this.value=="' . $secureSSNValue . '") this.value="";';
$htmlTextElement->onblur = 'javaScript:if(this.value=="") this.value="' . $secureSSNValue . '";';
$htmlTextElement->oncopy = 'true';
$htmlTextElement->onpaste = 'true';
$htmlTextElement->oncut = 'true';
$txtPerSSNumber = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtPerSSNumber', $secureSSNValue ,'results', 'ctlEmployees', 'secureSSN' , 'ctlEmployees#secureSSN');

$txtPerSSNumber .= $htmlTagObj->anchorTag('#','Reveal SSN', 'onclick="return revealFunction(); return false;"  title="Reveal SSN" id="revealID"');

//button Search 
$htmlButtonElement->id = 'search';
$htmlButtonElement->name = 'search';
$htmlButtonElement->value = 'Save';
$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->style = 'text-align: left;';
$htmlButtonElement->onclick = 'return validatePersonalInfo("' . $curDate . '");';
$htmlButtonElement->type = 'submit';
$btnSearch = $htmlButtonElement->renderHtml();
$htmlButtonElement->resetProperties();

$emptyData				= $htmlTextElement->addLabel($emptyData, '', '#ff0000','');
$txtPerEffecDate		= $htmlTextElement->addLabel($txtPerEffecDate, 'Effective Date', '#ff0000','');
$txtPerFirstName		= $htmlTextElement->addLabel($txtPerFirstName, 'First Name', '#ff0000','');
$txtPerMiddleName		= $htmlTextElement->addLabel($txtPerMiddleName, 'Middle Name/Initial', '#ff0000','');
$txtPerLastName			= $htmlTextElement->addLabel($txtPerLastName, 'Last Name', '#ff0000','');
$txtPerDOB				= $htmlTextElement->addLabel($txtPerDOB, 'Date of Birth', '#ff0000','');
$ddlPerMaritalStatus	= $htmlTextElement->addLabel($ddlPerMaritalStatus, 'Marital Status', '#ff0000','');
$ddlPerRaceEthnicity	= $htmlTextElement->addLabel($ddlPerRaceEthnicity, 'Race', '#ff0000','');
$ddlPerHigestEducation	= $htmlTextElement->addLabel($ddlPerHigestEducation, 'Highest Education Level', '#ff0000','');
$ddlPerGender			= $htmlTextElement->addLabel($ddlPerGender, 'Gender', '#ff0000','');
$ddlPerCitizenStatus	= $htmlTextElement->addLabel($ddlPerCitizenStatus, 'Citizenship Status', '#ff0000','');
$ddlPerVisaType			= $htmlTextElement->addLabel($ddlPerVisaType, 'Visa Type', '#ff0000','');
$txtPerSSNumber			= $htmlTextElement->addLabel($txtPerSSNumber, 'Social Security Number', '#ff0000','');

$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->setTableClass('');
$tableObj->setTableAttr();

//$tableObj->searchFields['emptyData'] = $emptyData;
$tableObj->searchFields['txtPerEffecDate'] = $txtPerEffecDate;
$tableObj->searchFields['txtPerFirstName'] = $txtPerFirstName;
$tableObj->searchFields['txtPerMiddleName'] = $txtPerMiddleName;
$tableObj->searchFields['txtPerLastName'] = $txtPerLastName;
$tableObj->searchFields['txtPerDOB'] = $txtPerDOB;
$tableObj->searchFields['ddlPerMaritalStatus'] = $ddlPerMaritalStatus;
$tableObj->searchFields['ddlPerRaceEthnicity'] = $ddlPerRaceEthnicity;
$tableObj->searchFields['ddlPerHigestEducation'] = $ddlPerHigestEducation;
$tableObj->searchFields['ddlPerGender'] = $ddlPerGender;
$tableObj->searchFields['ddlPerCitizenStatus'] = $ddlPerCitizenStatus;
$tableObj->searchFields['ddlPerVisaType'] = $ddlPerVisaType;
$tableObj->searchFields['txtPerSSNumber'] = $txtPerSSNumber;
$tableObj->searchFields['button'] = $btnSearch;

$searchForm = $tableObj->searchFormTableComponent();
echo $htmlTagObj->openTag('div', 'class="empAddressmainTable" style="width:95%;"'); 
echo $searchForm;
//echo 'entered';
//*
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnPerSSNumber';
$htmlTextElement->id = 'hdnPerSSNumber';
$htmlTextElement->value = !empty($employeePersonalInfo[0]['secureSSN']) ? 'Y' : 'N';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnLastFourDigits';
$htmlTextElement->id = 'hdnLastFourDigits';
$htmlTextElement->value = !empty($employeePersonalInfo[0]['secureSSN']) ? substr($employeePersonalInfo[0]['secureSSN'],5,4) : '';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

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
var d = new Date();
var toYear = d.getFullYear(); 
var currentYear = d.getFullYear();
toYear = toYear+50;

var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"]; ?>';
$( "#txtPerDOB" ).datepicker({
	  showOn: "button",
	  buttonImage: hostUrl+"/Include/images/calendar.gif",
	  buttonText:'Calendar',
	  buttonImageOnly: true,
	  showWeek:true,
	  changeMonth:true,
	  changeYear:true,
	  yearRange: "1940:"+currentYear,
	  showButtonPanel:true,
	  closeText: "Close"
});  


function htmlDataNew(i)
{
	//alert(i);
			   $.post("checkAuthReveal.php",   
			   { 
			   	p:i
			   },   
			   function(data)
			   { 
			   		var result = data.split('||');
					
			   		if(result[0]!='yes')
					{
						//$('#revealSSN').show();
						$('#revealID').hide();
						$('#txtPerSSNumber').val(result[1]);
					}
					else
					{
						$('#revealID').show();
						$('#txtPerSSNumber').val(result[1]);
						//$('#revealSSN').hide();	
					}
			   } 	
); 
		
		return false;
}

function revealFunction()
{
	htmlDataNew('load');
	setTimeout('htmlDataNew(\'next\')', 4000); 
}
</script>