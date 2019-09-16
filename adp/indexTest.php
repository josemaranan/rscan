<?php 
ob_start();
//ini_set('display_errors','1');

/*
if(!empty($_REQUEST['activeLink']))
{ 
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
}
else
{
	ini_set('session.cache_limiter', 'private'); 
}
*/
/* 1 ============================================ Declaring Classes ====================== */
clearstatcache();
//include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/config.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
//$employeeeMaintenanceObj = new ClassQuery();

//include_once($_SERVER['DOCUMENT_ROOT']."/adp/includes/adpClassFile.inc_test.php");
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/adpClassFile.inc.php");
$employeeeMaintenanceObj = new ADPEmployeeClass();


/*Step 1*/
//include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();



include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");

//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/config.inc.php');



//$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');

/*=============================== End of Declaring Class Files ======================= */




/* 2 ============================= DECLARING VARIABLES  =========================== */

$adpTask = '';
$adpMode = '';
$employeeID='';
$scrollingSyle = 'style="display:none;"';
$bottomLinkFlag = false;
$pageTitle = 'ReadiNet - ADP Payroll - Home Page';
$dhtmlCalendar = 'dhtmlgoodies_calendar.js?random=20060118';

$topLevelHeading = '';
$calendarControls = true;

//echo '<pre>';

/* ============================= DECLARING VARIABLES  =========================== */



/* 3 ============================================= POST VARIABLES ========================== */

/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';*/


if(isset($_REQUEST['adpMode']))
{
	$adpMode = $_REQUEST['adpMode'];
}

if(isset($_REQUEST['adpTask']))
{
	$adpTask = $_REQUEST['adpTask'];
}

if(isset($_REQUEST['hdnEmployeeID']))
{
	$employeeID = $_REQUEST['hdnEmployeeID'];
}

switch($adpMode)
{
	case 'hr':
		$topLevelHeading = 'Human Resources Access';
	break;
	
	case 'emp':
		$topLevelHeading = 'Employee Self Service';
	break;
}

//echo 'hhhhhhhhhhhhhhhhhh'.$topLevelHeading;
/* 4 ============================================= POST VARIABLES ========================== */


if(!empty($adpTask))
{
	$scrollingSyle = 'style="display:block;"';
}

switch($adpTask)
{
	case 'empModifyContractor':
		$hireDate = date('m/d/Y', strtotime($_REQUEST['hdnHireDate']));
		$includeString = 'include_once(\'includes/empModifyContractor.php\');';
		$pageTitle = 'RNet - ADP Payroll - Modify Contractor';
		$bottomLinkFlag = true;
	    break;
	
	case 'empManagement':
		$includeString = 'include_once(\'includes/empManagement.php\');';
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Management';
		break;
	
	case 'empDetails':
		$includeString = 'include_once(\'includes/empDetails.php\');';
		$bottomLinkFlag = true;
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Details';
		break;
	
	case 'empDates':
		$includeString = 'include_once(\'includes/empDates.php\');'; 
		$bottomLinkFlag = true;
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Dates';
		break;
	
	case 'empDatesAdd':
		$includeString = 'include_once(\'includes/empDatesAdd.php\');';
		$bottomLinkFlag = true;
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Add Hire Dates';
		break;
	
	case 'empDatesDelete':
		$hireDate = date('m/d/Y', strtotime($_REQUEST['hdnHireDate']));
		$termDate = date('m/d/Y', strtotime($_REQUEST['hdnTermDate']));
	
		$function = $_REQUEST['hdnFunction'];
		$includeString = 'include_once(\'includes/empDatesDelete.php\');';
		break;
	
	case 'empDatesEdit':
		if(!empty($_REQUEST['hdnHireDate']))
		{
			$hireDate = date('m/d/Y', strtotime($_REQUEST['hdnHireDate']));
		}
		else
		{
			$hireDate = '';	
		}
		
		if(!empty($_REQUEST['hireDateReviewedBy']))
		{
			$hireDateReviewedBy = $_REQUEST['hireDateReviewedBy'];
		}
		else
		{
			$hireDateReviewedBy = 'N';	
		}
			
		$type = 'ADP';
		$bottomLinkFlag = true;
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Edit Hire Dates';
		$includeString = 'include_once(\'includes/empDatesEdit.php\');';
		break;
	
	case 'empFullTime':
		$bottomLinkFlag = true;
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Part Time / Full Time';
		$includeString = 'include_once(\'includes/empFullTime.php\');';
		break;
	
	case 'empFullTimeEdit':
		$bottomLinkFlag = true;
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Part Time / Full Time - Edit';
		$includeString = 'include_once(\'includes/empFullTimeEdit.php\');';
		break;
	
	
	case 'empPosition':
		$bottomLinkFlag = true;
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Position Update';
		$includeString = 'include_once(\'includes/empPosition.php\');';
		$dhtmlCalendar = 'dhtmlgoodies_calendar_new.js?random=20060118';
		break;
		
	case 'empPayrollData':
		$bottomLinkFlag = true;
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Payroll Data';
		$includeString = 'include_once(\'includes/empPayrollData.php\');'; 
		break;
	
	case 'empClient':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Client';
		$includeString = 'include_once(\'includes/empClient.php\');'; 
		break;
	
	case 'empAddress':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Client';
		$includeString = 'include_once(\'includes/empAddress.php\');';  
		break;
	
	case 'empPersonalInfo':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Personal Information';
		$includeString = 'include_once(\'includes/empPersonalInfo.php\');';
		break;
	
	case 'empSalary':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Salary Information';
		$includeString = 'include_once(\'includes/empSalary.php\');';
		break;
	
	case 'empSalaryAdd':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Add Employee Salary Information';
		$includeString = 'include_once(\'includes/empSalaryAdd_RDS.php\');';
		break;
	
	case 'empSalaryEdit':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Add Employee Salary Information';
		$includeString = 'include_once(\'includes/empSalaryEdit_RDS.php\');';
		break;
	
	case 'empEmrContact':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Add Employee Salary Information';
		$includeString = 'include_once(\'includes/empEmrContact.php\');'; 
		break;
	
	case 'empViewPersonalInfo':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Personal Information - View Mode';
		$includeString = 'include_once(\'includes/empViewPersonalInfo.php\');';
		break;
	
	case 'empViewSalary':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Salary Information - View Mode';
		$includeString = 'include_once(\'includes/empViewSalary.php\');';
		break;
	
	case 'empViewPayrollData':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Salary Information - View Mode';
		$includeString = 'include_once(\'includes/empViewPayrollData.php\');';
		break;
	
	case 'empPushADPC':
		$pageTitle = 'ReadiNet - ADP Payroll - Push Employee to ADPC';
		$includeString = 'include_once(\'includes/empPushADPC.php\');';
		$bottomLinkFlag = true;
		break;
	
	
	/*
	case 'empUploadPhoto':
		$pageTitle = 'ReadiNet - ADP Payroll - Upload Employee Photo';
		$includeString = 'include_once(\'includes/empUploadPhoto.php\');';  ////
		$bottomLinkFlag = true;
		break;
	*/
	
	case 'empRemoval':
		$pageTitle = 'ReadiNet - ADP Payroll - Remove Employee Records';
		$includeString = 'include_once(\'includes/empRemoval.php\');';
		$bottomLinkFlag = true;
		break;
	
	/////////////////Work Item #38193
	case 'empSupervisor':
		$bottomLinkFlag = true;
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Employee Client';
		$includeString = 'include_once(\'includes/empSupervisor.php\');';
		break;
	/////////////////Work Item #38193
	
	default:
		case '':
		$scrollingSyle = 'style="display:none;"';
		$pageTitle = 'ReadiNet - ADP Payroll - Home Page';
		break;
}



/* 5 ===================================== Build HTML Content ================================== */

$htmlObject->htmlMetaTagsTitle($pageTitle);

$cssJsArray = array('CSS'=>array('readiNetAll.css', 'adpcss.css' , 'modalwindowzindex.css','dhtmlgoodies_calendar.css?random=20051112'), 'JS'=>array($dhtmlCalendar , 'table.js','jquery-1.9.1.min.js','adp/includes/adpdymicwidthHeightv.js', 'adp/includes/ajax.js', 'adp/includes/adpJavascript.js'));

$htmlObject->loadCSSJsFiles($cssJsArray);

$cssFiles  = array( '/Include/CSS/jquery-ui-1.10.3.custom.css','/RNetIncludes/tokeninput/css/token-input.css','/RNetIncludes/tokeninput/css/token-input-facebook.css');
$jsFiles   = array('RNetIncludes/js/jquery-1.10.2.js', 'RNetIncludes/js/jquery-ui.min.js', 'RNetIncludes/js/script.js','RNetIncludes/tokeninput/js/jquery.tokeninput.js');

$headerObj->cssSource = $cssFiles;
$cssFiles = $headerObj->getCssSourceFiles();
echo $cssFiles;

$headerObj->jsSource = $jsFiles;
$jsFiles = $headerObj->getJsSourceFiles();
echo $jsFiles;

/* Step 3 */
// Load body tag and left menu.
// Don't pass any thing if dont want left menu.
$htmlObject->loadBodyTag('leftMenu');

/* Step - 4 Load header part */
// Send object of DB class.
if(!empty($employeeID))
{
	$pageHyperlinks = array(
							'Main Menu'=>'Clients/Results/index.php',
							'Employee Summary'=>array(
													  'url'=>'#' , 
													  'name'=>'Employee', 
													  'onclick'=>'gotoNextPage('.$employeeID.',\''.$adpMode.'\', \'empDetails\'); return false;'
													  )
							);	
}
else
{
	$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php');	
}


$htmlObject->htmlHeadPart($employeeeMaintenanceObj->UserDetails->User, $pageHyperlinks);

/* ===================================== Build HTML Content ================================== */



// $employeeID = $employeeeMaintenanceObj->UserDetails->User;

/* 6 ============================= ADP PAYROLL RELATED FUNCTIONS =========================== */
$accessbleEmployees = $employeeeMaintenanceObj->buttonAccessEmployees($employeeeMaintenanceObj->accessPositions);

$accessLimit = $employeeeMaintenanceObj->accessLimits($accessbleEmployees , $employeeeMaintenanceObj->UserDetails->User);

//echo $employeeeMaintenanceObj->accessPositions; exit();
//echo print_r($accessbleEmployees); exit();
//echo 'ssssssssssssssssss'.$accessLimit;  exit;

$employeeeMaintenanceObj->setLeftMenu($accessLimit);
$getCountryName = $employeeeMaintenanceObj->getCountryName($employeeeMaintenanceObj->UserDetails->User);	



$employeeeMaintenanceObj->setUSLocations();

$employeeeMaintenanceObj->setUSReportingLocations();

if($adpTask=='empManagement')
{
	$employeeeMaintenanceObj->setUSPayGroupLocations('Yes');
}
else
{
	$employeeeMaintenanceObj->setUSPayGroupLocations();
}
$employeeeMaintenanceObj->setEmploymentStatuses();
$employeeeMaintenanceObj->setAllLocations();
$employeeeMaintenanceObj->setLocationsWithOutCorporate();

if($getCountryName!='United States of America' && $accessLimit!='fullAccess')
{
	$bottomLinkFlag = false;
	$scrollingSyle = 'style="display:none;"';
	$pageTitle = 'ReadiNet - ADP Payroll - Authrized Page';
	$topLevelHeading = 'Employee Self Service';
	$includeString = 'include_once(\'includes/empAuthorize.php\');';
	
}

if(!empty($employeeID))
{
		// S.P 1 - Emplooyee Information Widget
		
	$curDate = date('m/d/Y');
	$employeeeMaintenanceObj->setEmployeeInformation($employeeID, $curDate);
	$employData = $employeeeMaintenanceObj->getEmployeeInformation();
	
	// S.P 2 - Employee ADP related information.
	
	$employeeeMaintenanceObj->setEmployeeADPInformation($employeeID);
	$employADPData = $employeeeMaintenanceObj->getEmployeeADPInformation();
	
	
	$employeeeMaintenanceObj->setMissingColumns($employeeID);
	$employeeeMaintenanceObj->setbottomLinksDashBoard($employeeID, $adpMode , $accessLimit);
	
	$employeeeMaintenanceObj->setTopLevelEmployeeInfo($employData , $employADPData);
	
}


/* ============================= END =========================== */



/* 7 ======================================= Index Coding Starts =================================*/


echo $htmlTagObj->openTag('div','id="report_content"');
echo $htmlTagObj->openTag('div','id="leftADPPanel"');

$employeeeMaintenanceObj->getLeftMenu(); // Top left Menu

if($bottomLinkFlag)
{
	$employeeeMaintenanceObj->getbottomLinksDashBoard(); // Bottom Left Menu
	//echo "Menu";
}
echo $htmlTagObj->closeTag('div');

echo $htmlTagObj->openTag('div','id="rightADPPanel"');
//echo $includeString;
eval($includeString);

if(isset($rowsMainQryNum) && $rowsMainQryNum>0)
{ 
	echo $htmlTagObj->openTag('div','id="adpfooter2" class="outer"');
	echo $htmlTagObj->openTag('span','class="style1"');
	echo 'Total = '.$rowsMainQryNum;
	echo $htmlTagObj->closeTag('span');
	echo $htmlTagObj->closeTag('div');

}
    
echo $htmlTagObj->closeTag('div'); 


// form starts here
$htmlForm->action = $_SERVER['PHP_SELF'];
$htmlForm->name = 'adpForm';
$htmlForm->id = 'adpForm';
$htmlForm->method = 'post';
echo $htmlForm->startForm();

// hidden values
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'adpMode';
$htmlTextElement->id = 'adpMode';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'adpTask';
$htmlTextElement->id = 'adpTask';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnEmployeeID';
$htmlTextElement->id = 'hdnEmployeeID';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnHireDate';
$htmlTextElement->id = 'hdnHireDate';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'salaryInfoStDate';
$htmlTextElement->id = 'salaryInfoStDate';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'rowsSalaryInfoNum';
$htmlTextElement->id = 'rowsSalaryInfoNum';
$htmlTextElement->value = $rowsSalaryInfoNum;
echo $htmlTextElement->renderHtml();

$htmlTextElement->resetProperties();
$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'fullTimePartTimeEffectiveDate';
$htmlTextElement->id = 'fullTimePartTimeEffectiveDate';
$htmlTextElement->value = 'fullTimePartTimeEffectiveDate';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();


$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'activeLink';
$htmlTextElement->id = 'activeLink';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnFunction';
$htmlTextElement->id = 'hdnFunction';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hdnTermDate';
$htmlTextElement->id = 'hdnTermDate';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'lastRecord';
$htmlTextElement->id = 'lastRecord';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

$htmlTextElement->type = 'hidden';
$htmlTextElement->name = 'hireDateReviewedBy';
$htmlTextElement->id = 'hireDateReviewedBy';
echo $htmlTextElement->renderHtml();
$htmlTextElement->resetProperties();

echo $htmlForm->endForm();
// end form

// Get the minimum start date which the dates are enable.
if(!empty($employeeID) && ($calendarControls) )
{
	print($employeeeMaintenanceObj->getValidateCalendarControls($employeeID, 'Min', array('txtPerDOB'))); 
}
echo $htmlTagObj->closeTag('div');

if(isset($_REQUEST['error']))
{
	$errorMessage = $_REQUEST['error'];
	?>
	<script type="text/javascript">
		alert('<?php echo $employeeeMaintenanceObj->Errordefines[$errorMessage];?>');
	</script>
	<?php 
}

ob_flush();
?>
<script type="text/javascript">
makeItDynamic();
</script>