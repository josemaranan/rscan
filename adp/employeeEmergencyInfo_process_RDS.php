<?php
//ini_set('display_errors','1');
//include_once($_SERVER['DOCUMENT_ROOT']."/Include/logADPPayrollChangesClass.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();
echo 'emr...'; exit;
/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
exit;*/

$employeeID = $_REQUEST['hdnEmployeeID'];
$effectiveDate = $_REQUEST['txtEmEffecDate'];

//$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDate);

$sqlQry = "SELECT COUNT(*) AS rowCnt FROM ctlEmployeeEmergencyContactInformation WITH (NOLOCK) WHERE employeeID = $employeeID ";
$rstQry = $employeeeMaintenanceObj->execute($sqlQry);
if($rowQry = mssql_fetch_array($rstQry))
{
	$rowCnt = $rowQry['rowCnt'];
}
mssql_free_result($rstQry);


$fstName = addslashes($_REQUEST['txtEmFirstName']);
$lstName = addslashes($_REQUEST['txtEmLastName']);
$phoneNo = addslashes($_REQUEST['txtEmPhone']);
$email = addslashes($_REQUEST['txtEmEmail']);
$relationship = addslashes($_REQUEST['ddlEmRelationship']);
/*if($relationship=='spouse')
{
	$isSpouseEmp = $_REQUEST['chkEmSpouseWrk'];
}
else
{
	$isSpouseEmp = 'N';
}*/
if($_REQUEST['chkEmSpouseWrk'])
{
	$isSpouseEmp = $_REQUEST['chkEmSpouseWrk'];
}
else
{
	$isSpouseEmp = 'N';
}
$address1 = addslashes($_REQUEST['txtEmAddress1']);
$address2 = addslashes($_REQUEST['txtEmAddress2']);
$city = addslashes($_REQUEST['txtEmCity']);
$state = addslashes($_REQUEST['ddlEmState']);
$zip = addslashes($_REQUEST['txtEmzip']);
$modifiedBy = $_SESSION[empID];
$modifiedDate = date("m/d/Y");

if($rowCnt>0)
{
   //$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDate);
	$updQry = "	UPDATE 
					[ctlEmployeeEmergencyContactInformation]
				SET 
					firstName = '".$fstName."',
					lastName = '".$lstName."',
					phone = '".$phoneNo."',
					emailAddress = '".$email."',
					relationShip = '".$relationship."',
					isSpouseWorkInResults = '".$isSpouseEmp."',
					address1 = '".$address1."',
					address2 = '".$address2."',
					city = '".$city."',
					state = '".$state."',
					postal = '".$zip."',
					effectiveDate = '".$effectiveDate."',
					[modifiedBy] = '".$modifiedBy."',
					[modifiedDate] = '".$modifiedDate."'
				WHERE 
					[employeeID] = $employeeID ";
	//echo $updQry;exit;				
	$rstDQry = $employeeeMaintenanceObj->execute($updQry);
	mssql_free_result($rstDQry);
}
else
{
	$insQry = "	INSERT INTO [ctlEmployeeEmergencyContactInformation]
				(
					employeeID,
					firstName,
					lastName,
					phone,
					emailAddress,
					relationShip,
					isSpouseWorkInResults,
					address1,
					address2,
					city,
					state,
					postal,
					effectiveDate,
					modifiedBy,
					modifiedDate 
				)
				VALUES
				(
					$employeeID, 
					'".$fstName."',
					'".$lstName."',
					'".$phoneNo."',
					'".$email."',
					'".$relationship."',
					'".$isSpouseEmp."',
					'".$address1."',
					'".$address2."',
					'".$city."',
					'".$state."',
					'".$zip."',
					'".$effectiveDate."',
					'".$modifiedBy."',
					'".$modifiedDate."'
				)";
	//echo $updQry;exit;				
	$rstDQry = $employeeeMaintenanceObj->execute($insQry);
	mssql_free_result($rstDQry);				
}

if($rstDQry)
{
	if(isset($_REQUEST['fromPage']) && $_REQUEST['fromPage'] == 'myRnet')
	{
		return true;
	}
	else
	{
	   //header("Location: employeeAuthentication.php?employeeID=$employeeID");
	   header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=emp&adpTask=empEmrContact&error=EmerContactInfoSucess&activeLink=20");
	   exit;
	}
}
   

?>