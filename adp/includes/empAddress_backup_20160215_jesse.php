<?php
//$employeeeMaintenanceObj->setUSLocations();
unset($sqlMainQry);
unset($rstMainQry);

$employeeeMaintenanceObj->setADPClients();
$clientsArray = $employeeeMaintenanceObj->getADPClients();

$sqlStates = "SELECT [state] , [description] FROM results.dbo.ctlStates WITH (NOLOCK) WHERE countryCode = 'U' ";
$rstStates = $employeeeMaintenanceObj->execute($sqlStates);
while($adstate = mssql_fetch_assoc($rstStates))
{	
	$statedropdown[$adstate['state']] = $adstate['description'];
}
	mssql_free_result($rstStates);

$sqlAddressInfo = "	SELECT 
							a.street1,
							a.street2,
							a.city,
							a.zip,
							a.state,
							b.description AS stateDesc,
							c.description AS countryDesc
						FROM 
							results.dbo.ctlEmployeeAddresses a WITH (NOLOCK) 
						JOIN
							results.dbo.ctlStates b WITH (NOLOCK) 
						ON
							a.state = b.state
						JOIN
							results.dbo.ctlCountries c WITH (NOLOCK)
						ON
							a.country = c.countryCode
						WHERE 
							a.employeeID = '$employeeID'
						AND 
							addressType = 'home' ";
$rstAddressInfo = $employeeeMaintenanceObj->execute($sqlAddressInfo);
while($adrow = mssql_fetch_assoc($rstAddressInfo))
{	
	$empoyeeAddressInfo[] = $adrow;
}
mssql_free_result($rstAddressInfo);

$sqlOtherAddressInfo = "	SELECT 
						a.street1,
						a.street2,
						a.city,
						a.zip,
						a.state,
						b.description AS stateDesc,
						c.description AS countryDesc
					FROM 
						results.dbo.ctlEmployeeAddresses a WITH (NOLOCK) 
					JOIN
						results.dbo.ctlStates b WITH (NOLOCK) 
					ON
						a.state = b.state
					JOIN
						results.dbo.ctlCountries c WITH (NOLOCK)
					ON
						a.country = c.countryCode
					WHERE 
						a.employeeID = '$employeeID'
					AND 
						addressType = 'other' ";
$rstOtherAddressInfo = $employeeeMaintenanceObj->execute($sqlOtherAddressInfo);
while($otherAddrow = mssql_fetch_assoc($rstOtherAddressInfo))
{	
	$empoyeeOtherAddressInfo[] = $otherAddrow;
}
mssql_free_result($rstOtherAddressInfo);


unset($sqlMainQry);
unset($rstMainQry);

$sqlContactInfo = "SELECT 
							b.phone home,
							c.phone	mobile,
							d.emailAddress
						FROM 
							results.dbo.ctlEmployees a WITH (NOLOCK) 
						LEFT JOIN
							results.dbo.ctlEmployeePhones b WITH (NOLOCK)
						ON
							a.employeeID = b.employeeID
						AND
							b.phoneType = 'home'
						LEFT JOIN
							results.dbo.ctlEmployeePhones c WITH (NOLOCK)
						ON
							a.employeeID = c.employeeID
						AND
							c.phoneType = 'mobile'
						LEFT JOIN
							results.dbo.ctlEmployeeEmailAddresses d WITH (NOLOCK)
						ON
							a.employeeID = d.employeeID
						AND
							d.emailAddressType = 'personal'	
						WHERE 
							a.employeeID = '$employeeID' ";
$rstContactInfo = $employeeeMaintenanceObj->execute($sqlContactInfo);	
while($rowContactInfo = mssql_fetch_assoc($rstContactInfo))
{	
	$employeeContactInfo[] = $rowContactInfo;
}
mssql_free_result($rstContactInfo);
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
echo 'Modify Contact Information';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');

$employeeeMaintenanceObj->getTopLevelEmployeeInfo();

echo $htmlTagObj->openTag('div', 'id="topHeading" class="outer"');
echo 'Modify Contact Information';
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div', 'id="emptyDiv" class="outer"');
echo $htmlTagObj->closeTag('div');


echo $htmlTagObj->openTag('div', 'id="scrollingdatagrid" class="scrollingdatagrid"');

//Html Form starts here
$htmlForm->action = 'employeeAddress_process.php';
$htmlForm->name = 'employeeAddress';
$htmlForm->id = 'searchForm';
$htmlForm->method = 'POST';

echo $htmlForm->startForm();


// Effective date
$htmlTextElement->name = 'txtEffecDate';
$htmlTextElement->id = 'txtEffecDate';
$htmlTextElement->value = date('m/d/Y');
$htmlTextElement->readonly = 'true';
$htmlTextElement->size = '7';
//$htmlTextElement->accesskey = 'true';
$txtEffecDate = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

// Loading Address1
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtAddress1';
$htmlTextElement->id = 'txtAddress1';
$htmlTextElement->value =  stripslashes($empoyeeAddressInfo[0]['street1']);
$htmlTextElement->maxLength = '30';
$txtAddress1 = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtAddress1', stripslashes($empoyeeAddressInfo[0]['street1']) ,'results', 'ctlEmployeeAddresses', 'street1' , 'ctlEmployeeAddresses#street1Home');

// Loading Address2
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtAddress2';
$htmlTextElement->id = 'txtAddress2';
$htmlTextElement->value =  stripslashes($empoyeeAddressInfo[0]['street2']);
$htmlTextElement->maxLength = '30';
$txtAddress2 = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtAddress2', stripslashes($empoyeeAddressInfo[0]['street2']) ,'results', 'ctlEmployeeAddresses', 'street2' , 'ctlEmployeeAddresses#street2Home');

// Loading City
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtCity';
$htmlTextElement->id = 'txtCity';
$htmlTextElement->value =  stripslashes($empoyeeAddressInfo[0]['city']);
$htmlTextElement->maxLength = '30';
$txtCity = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtCity', stripslashes($empoyeeAddressInfo[0]['city']) ,'results', 'ctlEmployeeAddresses', 'city' , 'ctlEmployeeAddresses#cityHome');

// Loading Locations
$commonListBox->name = 'ddlState';
$commonListBox->id 	= 'ddlState';
$commonListBox->customArray = $statedropdown;
$commonListBox->selectedItem = stripslashes($empoyeeAddressInfo[0]['state']);
$commonListBox->optionKey = 'location';
$commonListBox->optionVal = 'description';
$ddlState = $commonListBox->AddRow('', 'Please choose');
$ddlState = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlState', stripslashes($empoyeeAddressInfo[0]['state']) ,'results', 'ctlEmployeeAddresses', 'state' , 'ctlEmployeeAddresses#stateHome');

// Loading City
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtzip';
$htmlTextElement->id = 'txtzip';
$htmlTextElement->value =  stripslashes($empoyeeAddressInfo[0]['zip']);
$htmlTextElement->maxLength = '30';
$txtzip = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtzip', stripslashes($empoyeeAddressInfo[0]['zip']) ,'results', 'ctlEmployeeAddresses', 'zip' , 'ctlEmployeeAddresses#zipHome');

// Loading Address1
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtOthAddress1';
$htmlTextElement->id = 'txtOthAddress1';
$htmlTextElement->value =  stripslashes($empoyeeOtherAddressInfo[0]['street1']);
$htmlTextElement->maxLength = '30';
$txtOthAddress1 = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtOthAddress1', stripslashes($empoyeeOtherAddressInfo[0]['street1']) ,'results', 'ctlEmployeeAddresses', 'street1' , 'ctlEmployeeAddresses#street1Other');

// Loading Address2
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtOthAddress2';
$htmlTextElement->id = 'txtOthAddress2';
$htmlTextElement->value =  stripslashes($empoyeeOtherAddressInfo[0]['street2']);
$htmlTextElement->maxLength = '30';
$txtOthAddress2 = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtOthAddress2', stripslashes($empoyeeOtherAddressInfo[0]['street2']) ,'results', 'ctlEmployeeAddresses', 'street2' , 'ctlEmployeeAddresses#street2Other');

// Loading City
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtOthCity';
$htmlTextElement->id = 'txtOthCity';
$htmlTextElement->value =  stripslashes($empoyeeOtherAddressInfo[0]['city']);
$htmlTextElement->maxLength = '30';
$txtOthCity = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtOthCity', stripslashes($empoyeeOtherAddressInfo[0]['city']) ,'results', 'ctlEmployeeAddresses', 'city' , 'ctlEmployeeAddresses#cityOther');

// Loading Locations
$commonListBox->name = 'ddlOthState';
$commonListBox->id 	= 'ddlOthState';
$commonListBox->customArray = $statedropdown;
$commonListBox->selectedItem = stripslashes($empoyeeOtherAddressInfo[0]['state']);
$commonListBox->optionKey = 'location';
$commonListBox->optionVal = 'description';
$ddlOthState = $commonListBox->AddRow('', 'Please choose');
$ddlOthState = $commonListBox->convertArrayToDropDown();
$commonListBox->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('ddlOthState', stripslashes($empoyeeOtherAddressInfo[0]['state']) ,'results', 'ctlEmployeeAddresses', 'state' , 'ctlEmployeeAddresses#stateOther');

// Loading City
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtOthzip';
$htmlTextElement->id = 'txtOthzip';
$htmlTextElement->value =  stripslashes($empoyeeOtherAddressInfo[0]['zip']);
$htmlTextElement->maxLength = '30';
$txtOthzip = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtOthzip', stripslashes($empoyeeOtherAddressInfo[0]['zip']) ,'results', 'ctlEmployeeAddresses', 'zip' , 'ctlEmployeeAddresses#zipOther');

// Loading City
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtPerHomePhone';
$htmlTextElement->id = 'txtPerHomePhone';
$htmlTextElement->value =  stripslashes($employeeContactInfo[0]['home']);
$htmlTextElement->maxLength = '30';
$txtPerHomePhone = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtPerHomePhone', stripslashes($employeeContactInfo[0]['home']) ,'results', 'ctlEmployeePhones', 'phone' , 'ctlEmployeePhones#phone');

// Loading City
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtPerMobilePhone';
$htmlTextElement->id = 'txtPerMobilePhone';
$htmlTextElement->value =  stripslashes($employeeContactInfo[0]['mobile']);
$htmlTextElement->maxLength = '30';
$txtPerMobilePhone = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtPerMobilePhone', stripslashes($employeeContactInfo[0]['mobile']) ,'results', 'ctlEmployeePhones', 'phone' , 'ctlEmployeePhones#phone');

// Loading City
$htmlTextElement->type = 'text';
$htmlTextElement->name = 'txtPerEmail';
$htmlTextElement->id = 'txtPerEmail';
$htmlTextElement->value =  stripslashes($employeeContactInfo[0]['emailAddress']);
$htmlTextElement->maxLength = '30';
$txtPerEmail = $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('txtPerEmail', stripslashes($employeeContactInfo[0]['emailAddress']) ,'results', 'ctlEmployeeEmailAddresses', 'emailAddress' , 'ctlEmployeeEmailAddresses#emailAddressPersonal');
//*
//button Search 
$htmlButtonElement->id = 'search';
$htmlButtonElement->name = 'search';
$htmlButtonElement->value = 'Save';
$htmlButtonElement->Class = 'WSGInputButton';
$htmlButtonElement->style = 'text-align: left;';
$htmlButtonElement->onclick = 'return validateAddressInfo("' . $curDate . '");';
$htmlButtonElement->type = 'submit';
$btnSearch = $htmlButtonElement->renderHtml();
$htmlButtonElement->resetProperties();

$emptyData		= $htmlTextElement->addLabel($emptyData, '', '#ff0000','');
$txtEffecDate	= $htmlTextElement->addLabel($txtEffecDate, 'Effective Date', '#ff0000','');
$txtAddress1	= $htmlTextElement->addLabel($txtAddress1, 'Address 1', '#ff0000','');
$txtAddress2	= $htmlTextElement->addLabel($txtAddress2, 'Address 2', '#ff0000','');
$txtCity		= $htmlTextElement->addLabel($txtCity, 'City', '#ff0000','');
$ddlState		= $htmlTextElement->addLabel($ddlState, 'State/Prov', '#ff0000','');
$txtzip			= $htmlTextElement->addLabel($txtzip, 'Postal/Zip', '#ff0000','');

$txtOthAddress1	= $htmlTextElement->addLabel($txtOthAddress1, 'Address 1', '#ff0000','');
$txtOthAddress2	= $htmlTextElement->addLabel($txtOthAddress2, 'Address 2', '#ff0000','');
$txtOthCity		= $htmlTextElement->addLabel($txtOthCity, 'City', '#ff0000','');
$ddlOthState	= $htmlTextElement->addLabel($ddlOthState, 'State/Prov', '#ff0000','');
$txtOthzip		= $htmlTextElement->addLabel($txtOthzip, 'Postal/Zip', '#ff0000','');

$txtPerHomePhone	= $htmlTextElement->addLabel($txtPerHomePhone, 'Home Phone', '#ff0000','');
$txtPerMobilePhone	= $htmlTextElement->addLabel($txtPerMobilePhone, 'Mobile Phone', '#ff0000','');
$txtPerEmail		= $htmlTextElement->addLabel($txtPerEmail, 'Personal Email', '#ff0000','');


//Form block 1
$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->setTableClass('');
$tableObj->setTableAttr();

//$tableObj->searchFields['emptyData'] = $emptyData;
$tableObj->searchFields['txtEffecDate'] = $txtEffecDate;
$tableObj->searchFields['txtAddress1'] = $txtAddress1;
$tableObj->searchFields['txtAddress2'] = $txtAddress2;
$tableObj->searchFields['txtCity'] = $txtCity;
$tableObj->searchFields['ddlState'] = $ddlState;
$tableObj->searchFields['txtzip'] = $txtzip;
$searchForm1 = $tableObj->searchFormTableComponent();
unset($tableObj->searchFields);

//Form block 2
$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->setTableClass('');
$tableObj->setTableAttr();

$tableObj->searchFields['txtOthAddress1'] = $txtOthAddress1;
$tableObj->searchFields['txtOthAddress2'] = $txtOthAddress2;
$tableObj->searchFields['txtOthCity'] = $txtOthCity;
$tableObj->searchFields['ddlOthState'] = $ddlOthState;
$tableObj->searchFields['txtOthzip'] = $txtOthzip;

$searchForm2 = $tableObj->searchFormTableComponent();
unset($tableObj->searchFields);

//Form block 3
$tableObj->tableId = 'adpsearchTable';
$tableObj->maxCol = 2;
$tableObj->border = 0;
$tableObj->cellSpacing = 2;
$tableObj->setTableClass('');
$tableObj->setTableAttr();

$tableObj->searchFields['txtPerHomePhone'] = $txtPerHomePhone;
$tableObj->searchFields['txtPerMobilePhone'] = $txtPerMobilePhone;
$tableObj->searchFields['txtPerEmail'] = $txtPerEmail;
$tableObj->searchFields['button'] = $btnSearch;
$searchForm3 = $tableObj->searchFormTableComponent();



echo $htmlTagObj->openTag('div', 'id="singlePixelBorder" style="padding:8px;"'); 

echo $htmlTagObj->openTag('div', 'id="homeAddress" style="width:98%;"'); 
echo $htmlTagObj->openTag('div', 'class="empAddressleftHeading"'); 
echo 'Home Address';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'id="empAddressmainTable"'); 
echo $searchForm1;	
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('hr', '');
echo $htmlTagObj->closeTag('hr');

echo $htmlTagObj->openTag('div', 'id="otherAddress" style="width:98%;"'); 
echo $htmlTagObj->openTag('div', 'class="empAddressleftHeading"'); 
echo 'Other Address';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'id="empAddressmainTable"'); 
echo $searchForm2;	
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('hr', '');
echo $htmlTagObj->closeTag('hr');

echo $htmlTagObj->openTag('div', 'id="otherAddress" style="width:98%;"'); 
echo $htmlTagObj->openTag('div', 'class="empAddressleftHeading"'); 
echo 'Contact Info';
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->openTag('div', 'id="empAddressmainTable"'); 
echo $searchForm3;	
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnPhoneType';
$htmlTextElement->id = 'hdnPhoneType';
$htmlTextElement->value = '';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();
$employeeeMaintenanceObj->gethiddenValues('hdnPhoneType', '' ,'results', 'ctlEmployeePhones', 'phoneType' , 'ctlEmployeePhones#phoneType');

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnEmployeeID';
$htmlTextElement->id = 'hdnEmployeeID';
$htmlTextElement->value = $employeeID;
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnHomePhone';
$htmlTextElement->id = 'hdnHomePhone';
$htmlTextElement->value = $employeeContactInfo[0]['home'];
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnMobilePhone';
$htmlTextElement->id = 'hdnMobilePhone';
$htmlTextElement->value = $employeeContactInfo[0]['mobile'];
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnEmailAddress';
$htmlTextElement->id = 'hdnEmailAddress';
$htmlTextElement->value = $employeeContactInfo[0]['emailAddress'];
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

echo $htmlForm->endForm();
echo $htmlTagObj->closeTag('div');
echo $htmlTagObj->closeTag('div');
//*/
?>

<script type="text/javascript">
/*$(document).ready(function (){
	$("#search").click(function() {
		var txtEffecDate = $('txtEffecDate').val();
		validateAddressInfo(txtEffecDate);									  
	});
});*/
var hostUrl = '<?php echo "https://".$_SERVER["HTTP_HOST"]; ?>';
$(document).ready(function (){
	$( "#txtEffecDate" ).datepicker({
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
});
</script>