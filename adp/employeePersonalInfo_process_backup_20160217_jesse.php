<?php
//include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
ini_set('display_errors','1');
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/lib/RDSData/DbConfig.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
exit;*/
/* Transaction Query Implements */

unset($transactionQuery);

$transactionQuery = " BEGIN TRANSACTION employeePersonalInfo_process ";

$employeeID = $_REQUEST['hdnEmployeeID'];
$effectiveDate = $_REQUEST['txtPerEffecDate'];



$sqlSSN = " SELECT COMMON.DBO.fn_rnetV3_Decrypt(secureSSN,'".DBMS_PASSWORD."') [secureSSN] FROM results.dbo.ctlEmployees WITH (NOLOCK) WHERE employeeID = '$employeeID'";
//echo $sqlSSN;exit;
$rstSSN = $employeeeMaintenanceObj->execute($sqlSSN);
while($rowSSN = mssql_fetch_assoc($rstSSN))
{
	$dbSSN = $rowSSN['secureSSN'];
}
//echo $dbSSN;exit;


$flag = 'N';

$fstName = addslashes($_REQUEST['txtPerFirstName']); 
$lstName = addslashes($_REQUEST['txtPerLastName']); 
$middleName = addslashes($_REQUEST['txtPerMiddleName']); 

$maritalStatus = addslashes($_REQUEST['ddlPerMaritalStatus']); 
$dob = $_REQUEST['txtPerDOB']; 
$ethnicity = addslashes($_REQUEST['ddlPerRaceEthnicity']); 
$highEducation = addslashes($_REQUEST['ddlPerHigestEducation']); 
$gender = addslashes($_REQUEST['ddlPerGender']); 
$citizenStatus = addslashes($_REQUEST['ddlPerCitizenStatus']); 
$visaType = addslashes($_REQUEST['ddlPerVisaType']); 
$ssNumber = str_replace('*','',$_REQUEST['txtPerSSNumber']);
//$againSSNumber = $_REQUEST['txtPerAgainSSNumber']; 
$modifiedBy = $_SESSION[empID];
$modifiedDate = date('m/d/Y');
$fromPage = false;

if(isset($_REQUEST['fromPage']))
{
		if($_REQUEST['fromPage'] == 'emp' || $_REQUEST['fromPage'] == 'myRnet')
		{
				$fromPage = true;
		}
}


if(strlen($ssNumber)>4 && $dbSSN!=$ssNumber)
{
	$flag = 'Y';
}
//echo $flag;exit;



$qryUpdate = " 	UPDATE 
					ctlEmployees  
				SET ";
				
	if(!empty($fstName))
	{
		$qryUpdate .= " firstName = '".$fstName."', ";
	}
	
	if(!empty($lstName))
	{
		$qryUpdate .= " lastName = '".$lstName."', ";
	}
	
	if(!empty($middleName))
	{
		$qryUpdate .= " middle = '".$middleName."',";
	}
	
	if(!empty($maritalStatus))
	{
		$qryUpdate .= " maritalStatus = '".$maritalStatus."', ";
	}
	
	if(!empty($dob))
	{
		$qryUpdate .= " dob = '".$dob."',";
	}
	
	if(!empty($ethnicity))
	{
		$qryUpdate .= " ethnicity = '".$ethnicity."',";
	}
	
	if(!empty($highEducation))
	{
		$qryUpdate .= " educationLevel = '".$highEducation."', ";
	}
	
	if(!empty($gender))
	{
		$qryUpdate .= " gender = '".$gender."',";
	}
	
	if(!empty($citizenStatus))
	{
		$qryUpdate .= " citizenshipStatus = '".$citizenStatus."', ";
	}else {
		$qryUpdate .= " citizenshipStatus = NULL, ";
	}
	
	if(!empty($visaType))
	{
		$qryUpdate .= " visaType = '".$visaType."',";
	} else {
		$qryUpdate .= " visaType = NULL , ";
	}
			
					
					
if($flag=='Y')					
{
	$qryUpdate .= "	secureSSN = common.dbo.fn_rnetV3_Encrypt('$ssNumber','".DBMS_PASSWORD."'),";
}
$qryUpdate .= "		modifiedBy = '".$modifiedBy."',
					modifiedDate = '".$modifiedDate."'
				WHERE 
					employeeID= '".$employeeID."' ";
//echo $qryUpdate;exit;
//$rstDQry = $employeeeMaintenanceObj->execute($qryUpdate);

$transactionQuery .= $qryUpdate;
	$transactionQuery .= " IF(@@ERROR <> 0) BEGIN
						PRINT 'Failed to update  ctlEmployees'
						ROLLBACK TRANSACTION
						END";
$transactionQuery .= " COMMIT TRANSACTION employeePersonalInfo_process ";


$rstDQry = $employeeeMaintenanceObj->execute($transactionQuery);					


if($rstDQry)
{

	
	$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDate);
	mssql_free_result($rstDQry);
	if(!$fromPage)
	{
		if($fromPage == 'myRnet')
		{
			echo true;
		}
		else
		{
		header("Location: index.php?hdnEmployeeID=".$employeeID."&adpMode=emp&adpTask=empPersonalInfo&error=PersonalInfoSucess&activeLink=20");
		}
	}
	else
	{
			header("Location: index.php?hdnEmployeeID=".$employeeID."&adpMode=emp&adpTask=empViewPersonalInfo&error=PersonalInfoSucess&activeLink=20");
	}
	//header("Location: employeeAuthentication.php?employeeID=$employeeID");	
	exit;
}
?>		
