<?php
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();

/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
exit;*/


/* Transaction Query Implements */
	
$employeeID = $_REQUEST['hdnEmployeeID'];
$effectiveDate = $_REQUEST['txtADPEffecDate'];


$workLocation = $_REQUEST['ddlADPWorkLocation']; 
$repLocation = $_REQUEST['ddlADPRepLocation']; 
$ddlADPPayGroup = $_REQUEST['ddlADPPayGroup'];
							
$compRate = addslashes($_REQUEST['txtADPCompRate']); 
$empType = addslashes($_REQUEST['ddlADPEmployeeType']); 
$modifiedBy = $_SESSION['empID'];
$modifiedDate = date('m/d/Y');

$gssLoc = '';
$virtualLoc = '';
if(!empty($_REQUEST['chkGSS']))
{
	$gssLoc = $_REQUEST['chkGSS'];
}
if(!empty($_REQUEST['chkVirtual']))
{
	$virtualLoc = $_REQUEST['chkVirtual'];
}
//echo 'XXXX-----'.$gssLoc;exit;

$selPayrolRates = " SELECT COUNT(*) AS recCnt FROM ctlEmployeePayrollRates WITH (NOLOCK) WHERE employeeID = $employeeID AND startDate = '".$effectiveDate."' ";
$rstPayrolRates = $employeeeMaintenanceObj->execute($selPayrolRates);
while($rowPayrolRates = mssql_fetch_assoc($rstPayrolRates))
{
	$recCnt = $rowPayrolRates['recCnt'];
}
mssql_free_result($rstPayrolRates);

$selPayrolLoc = " SELECT COUNT(*) AS locCnt FROM RNet.dbo.prmEmployeePayrollLocations WITH (NOLOCK) WHERE employeeID = $employeeID AND effectiveDate = '".$effectiveDate."' ";
$rstPayrolLoc = $employeeeMaintenanceObj->execute($selPayrolLoc);
while($rowPayrolLoc = mssql_fetch_assoc($rstPayrolLoc))
{
	$locCnt = $rowPayrolLoc['locCnt'];
}
mssql_free_result($rstPayrolLoc);


 

$transactionQuery = " BEGIN TRANSACTION employeeADPinfo_process ";
$updateEmps = " UPDATE 
					ctlEmployees  
				SET 
					adpReportingLocation = '".$repLocation."',
					location = '".$workLocation."',
					modifiedBy = '".$modifiedBy."',
					modifiedDate = '".$modifiedDate."'";
		if(!empty($gssLoc))
		{
			$updateEmps.="
				,GSS = '$gssLoc'";
		}
		else
		{
			$updateEmps.="
				,GSS = 'N'";
		}
		if(!empty($virtualLoc))
		{
			$updateEmps.="
				,virtual = '$virtualLoc'";
		}
		else
		{
			$updateEmps.="
				,virtual = 'N'";
		}
$updateEmps.= "
				WHERE 
					employeeID= '".$employeeID."' ";
//echo $updateEmps;exit;

//$rstUpdateEmps = $employeeeMaintenanceObj->execute($updateEmps);
$transactionQuery .= $updateEmps;
$transactionQuery .= " IF(@@ERROR <> 0) BEGIN
						PRINT 'Failed to Update into ctlEmployees'
						ROLLBACK TRANSACTION
						END";


/*
if($recCnt>0)
{
	$updPayrolRates = "	UPDATE 
							[ctlEmployeePayrollRates]
						SET 
							[payType] = '$empType', 
							[amount] = '$compRate',
							[modifiedBy] = '$modifiedBy',
							[modifiedDate] = '".$modifiedDate."'
						WHERE 
							[employeeID] = $employeeID
						AND
							startDate= '".$effectiveDate."' ";
	$rstpUpdPayrolRates = $employeeeMaintenanceObj->execute($updPayrolRates);	
	mssql_free_result($rstpUpdPayrolRates);
}
else
{
	$insPayrolRates = "	INSERT INTO [ctlEmployeePayrollRates]
							([employeeID]
							,[startDate]
							,[payType]
							,[amount]
							,[modifiedBy]
							,[modifiedDate])
						VALUES
							('$employeeID'
							,'$effectiveDate'
							,'$empType'
							,'$compRate'
							,'$modifiedBy'
							,'".$modifiedDate."')";
	$rstInsPayrolRates = $employeeeMaintenanceObj->execute($insPayrolRates);
	mssql_free_result($rstInsPayrolRates);
}
*/

if($locCnt==0)
{
	$insPayLocQry = "	INSERT INTO RNet.dbo.prmEmployeePayrollLocations 
						(
							employeeID,
							payrollLocation,
							effectiveDate,
							modifiedBy,
							modifiedDate
						)
						VALUES
						(
							'$employeeID',
							'$ddlADPPayGroup',
							'".$effectiveDate."',
							'$modifiedBy',
							'".$modifiedDate."'
						)";
						
	//$rstInsPayLoc = $employeeeMaintenanceObj->execute($insPayLocQry);
	$transactionQuery .= $insPayLocQry;
	$transactionQuery .= " IF(@@ERROR <> 0) BEGIN
						PRINT 'Failed to Insert into prmEmployeePayrollLocations'
						ROLLBACK TRANSACTION
						END";
						
}
else
{
	$updPayLocQry = "	UPDATE 
							RNet.dbo.prmEmployeePayrollLocations 
						SET 
							payrollLocation = '$ddlADPPayGroup',
							effectiveDate = '".$effectiveDate."',
							modifiedBy = '$modifiedBy',
							modifiedDate = '".$modifiedDate."'
						WHERE
							employeeID = $employeeID
						AND
							payrollLocation = '".$_REQUEST['hdnADPPayrollChanges']['ddlADPPayGroup']."'
						AND
							effectiveDate = '".$effectiveDate."' ";
	//echo $updPayLocQry;exit;
	//$rstInsPayLoc = $employeeeMaintenanceObj->execute($updPayLocQry);
	
	$transactionQuery .= $updPayLocQry;
	$transactionQuery .= " IF(@@ERROR <> 0) BEGIN
						PRINT 'Failed to Update into prmEmployeePayrollLocations'
						ROLLBACK TRANSACTION
						END";
}

$transactionQuery .= " COMMIT TRANSACTION employeeADPinfo_process ";

$rstUpdateEmps = $employeeeMaintenanceObj->execute($transactionQuery);


if($_REQUEST['hdnADPPayrollChanges']['ddlADPPayGroup']!=$ddlADPPayGroup)
{
	//$prevLocDesc = $employeeeMaintenanceObj->getLocationDescription($_REQUEST['hdnADPPayrollChanges']['ddlADPPayGroup']);
	//$currLocDesc = $employeeeMaintenanceObj->getLocationDescription($ddlADPPayGroup);
	
$prevLocDesc = $employeeeMaintenanceObj->getPayGroupLocationDescription($_REQUEST['hdnADPPayrollChanges']['ddlADPPayGroup']);
$currLocDesc = $employeeeMaintenanceObj->getPayGroupLocationDescription($ddlADPPayGroup);

	 include_once($_SERVER["DOCUMENT_ROOT"].'/Payroll/PayrollLocation/payrollLocChangeEmailTemplate.php');


include_once($_SERVER["DOCUMENT_ROOT"].'/Payroll/PayrollLocation/payrollLocChangeEmailTemplateforLRPs.php');

}


if($rstUpdateEmps)
{
	$requiredArray = $employeeeMaintenanceObj->updateLogFiles($_REQUEST, $employeeID, $effectiveDate);
	mssql_free_result($rstUpdateEmps);
	unset($requiredArray);
	//header("Location: employeeAuthentication.php?employeeID=$employeeID");	
	header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empPayrollData&error=EditADPInfoSuccess&activeLink=20");
	exit;
}


?>
